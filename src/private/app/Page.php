<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\Sanitizer;
use libAllure\Session;
use Sicroc\Widget;
use Sicroc\WidgetForm;
use Sicroc\Table;
use Sicroc\WikiContent;
use Sicroc\SimpleMessage;

class Page
{
    private array $widgets;
    private array $page;

    private function loadPage(): void
    {
        if (isset($_REQUEST['pageIdent'])) {
            $page = $this->getPageByIdent();
        } else {
            $page = $this->getPageById();
        }

        $this->page = $page;
    }

    private function getPageById(): array
    {
        $pageId = Sanitizer::getInstance()->filterUint('page');

        if ($pageId === 0) {
            $pageId = 1;
        }

        $sql = 'SELECT p.id, p.title, p.layout, p.isSystem FROM pages p WHERE p.id = :id LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', $pageId);
        $stmt->execute();

        $page = $stmt->fetchRow();

        if ($page == null) {
            throw new \Exception('Page not found by ID:' . $pageId);
        }

        return $page;
    }

    private function getPageByIdent(): array
    {
        $pageIdent =  Sanitizer::getInstance()->filterString('pageIdent');

        $sql = 'SELECT p.id, p.title, p.layout, p.isSystem FROM pages p WHERE p.ident = :ident LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':ident', $pageIdent);
        $stmt->execute();

        try {
            return $stmt->fetchRowNotNull();
        } catch (\Exception $e) {
            throw new \Exception('Page not found by title: ' . $pageIdent);
        }
    }

    public function getId(): ?int
    {
        return $this->page['id'];
    }

    public function getWidgetByType(string $search): ?object
    {
        foreach ($this->widgets as $widget) {
            if (get_class($widget['inst']) == $search) {
                return $widget;
            }
        }

        return null;
    }

    private function getWidgets(int $pageId): array
    {
        $sql = 'SELECT wt.viewableController, wi.id, wi.title FROM page_content c JOIN widget_instances wi ON c.widget = wi.id JOIN widget_types wt ON wi.type = wt.id  WHERE c.page = :pageId ORDER BY c.ordinal';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':pageId', $pageId);
        $stmt->execute();

        $widgets = $stmt->fetchAll();

        return $widgets;
    }

    private function resolveWidgets(array $widgets): array
    {
        foreach ($widgets as $key => $widget) {
            $widgets[$key] = self::resolveWidget($widget, $this);
        }

        return $widgets;
    }

    private function renderWidgets(array $widgets): array
    {
        foreach ($widgets as $key => $widget) {
            $widgets[$key] = self::renderWidget($widget);
        }

        return $widgets;
    }

    private static function renderWidget(array $widget): array
    {
        if (!isset($widget['inst'])) {
            return $widget;
        }

        if ($widget['shouldRender']) {
            try {
                ob_start();

                $widget['inst']->render();
                $widget['content'] .= ob_get_clean();
            } catch (\Exception $e) {
                $widget['shouldRender'] = true;
                $widget['content'] = self::renderWidgetException($e);
            }
        }

        return $widget;
    }

    public static function resolveWidget(array $widget, ?Page $page = null): array
    {
        $widgetRet = $widget;
        $widgetRet['shouldRender'] = true;

        try {
            assert(!empty($widget['viewableController']));

            $widgetRet['inst'] = $inst = new $widget['viewableController']();
        } catch (\Exception $e) {
            throw new \Exception('Cannot instanticate widget: ' . $widget['viewableController']);
        }

        $widgetRet['inst']->page = $page;
        $widgetRet['inst']->widgetId = $widget['id'];
        $widgetRet['content'] = '';

        try {
            if (empty($widget['title'])) {
                $widgetRet['title'] = $widgetRet['inst']->getTitle();
            }

            foreach ($widgetRet['inst']->getArguments() as $arg) {
                if (isset($arg['required']) && $arg['required']) {
                    if ($widgetRet['inst']->getArgumentValue($arg['name']) == null) {
                        throw new \Exception('Argument: ' . $arg['name'] . ' is required');
                    }
                }
            }

            $widgetRet['inst']->widgetSetupCompleted();
            $widgetRet['shouldRender'] = $widgetRet['inst']->shouldRender();
        } catch (\Exception $e) {
            $widgetRet['inst']->displayEdit = true;
            $widgetRet['shouldRender'] = true;
            $widgetRet['content'] = self::renderWidgetException($e);
        }

        return $widgetRet;
    }

    private static function renderWidgetException(\Exception $e): string
    {
        $html = '';
        $html .= '<div class = "framedBox">';
        $html .= '<p class = "bad"><strong>Exception thrown while trying to setup or render a widget.</strong></p>';
        $html .= '<p>';
        $html .= '<strong>Type:</strong> ' . get_class($e) . '<br/>';
        $html .= '<strong>Message:</strong> ' . $e->getMessage() . '<br />';
        $html .= '<strong>Line:</strong> ' . $e->getLine() . '<br />';
        $html .= '</p>';
        $html .= '</div>';

        return $html;
    }

    public function resolve(): void
    {
        $this->widgets = array();

        try {
            $this->loadPage();
        } catch (\Exception $e) {
            $this->createFakePageForError($e);
            return;
        }

        $this->widgets = $this->getWidgets($this->page['id']);

        if ($this->page['id'] == 1 && empty($this->widgets)) {
            $msg = new SimpleMessage('Sicroc started up successfully! However, the homepage is empty. <br /><br />This is most likely because you have just run database migrations, and now need to run <a href = "setup.php">setup</a> to finish populating the database.', 'good');
            $this->widgets[] = [
                'inst' => $msg,
                'content' => null,
                'id' => 0,
                'shouldRender' => true,
            ];

            $this->widgets = $this->renderWidgets($this->widgets);
        } else {
            $this->widgets = $this->resolveWidgets($this->widgets);
            $this->widgets = $this->renderWidgets($this->widgets);
        }
    }

    private function createFakePageForError(\Exception $e): void
    {
        $this->page = array(
            'id' => 0,
            'layout' => 'normal',
            'title' => 'Critical Error',
            'isSystem' => true,
        );

        $msg = new SimpleMessage($e->getMessage(), 'bad');
        $this->widgets[] = [
            'inst' => $msg,
            'content' => null,
            'id' => 0,
            'shouldRender' => true
        ];

        $this->widgets = $this->renderWidgets($this->widgets);
    }

    public function getForTpl(): array
    {
        return $this->page;
    }

    public function getWidgetsForTpl(): array
    {
        return $this->widgets;
    }

    public function isSystem(): bool
    {
        return $this->page['isSystem'] === true;
    }

    public function getLayout(): string
    {
        return $this->page['layout'];
    }
}
