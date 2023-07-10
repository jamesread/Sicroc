<?php

namespace Sicroc\Controllers;

use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;
use \libAllure\Session;

use \Sicroc\Controllers\Widget;
use \Sicroc\Controllers\WidgetForm;
use \Sicroc\Controllers\Table;
use \Sicroc\Controllers\WikiContent;
use \Sicroc\Controllers\SimpleMessage;

class Page extends Widget
{
    private function getPage()
    {
        if (isset($_REQUEST['pageIdent'])) {
            $page = $this->getPageByIdent();
        } else {
            $page = $this->getPageById();
        }

        return $page;
    }

    private function getPageById()
    {
        $pageId = Sanitizer::getInstance()->filterUint('page');

        if ($pageId === 0) {
            $pageId = 1;
        }

        $sql = 'SELECT p.id, p.title, p.layout, p.isSystem FROM pages p WHERE p.id = :id LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', $pageId);
        $stmt->execute();

        try {
            return $stmt->fetchRowNotNull();
        } catch (Exception $e) {
            throw new Exception('Page not found by ID:' . $pageId);
        }
    }

    public function getPageByIdent()
    {
        $pageIdent =  Sanitizer::getInstance()->filterString('pageIdent');

        $sql = 'SELECT p.id, p.title, p.layout, p.isSystem FROM pages p WHERE p.ident = :ident LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':ident', $pageIdent);
        $stmt->execute();

        try {
            return $stmt->fetchRowNotNull();
        } catch (Exception $e) {
            throw new Exception('Page not found by title:' . $pageIdent);
        }

    }

    public function getId()
    {
        return $this->page['id'];
    }

    public function edit()
    {
        global $tpl;

        // use the part page atm, although technically it's not correct.
        $tpl->assign('page', $this->getPage());

        $widgets = array(
            array(
                'title' => 'Admin part for page',
                'content' => 'None yet. :)'
            )
        );

        $tpl->assign('widgets', $widgets);
    }

    public function getWidgetByType($search)
    {
        foreach ($this->widgets as $widget) {
            if (get_class($widget['inst']) == $search) {
                return $widget;
            }
        }

        return null;
    }

    private function getWidgets($pageId)
    {
        $sql = 'SELECT wt.viewableController, wi.id, wi.title, wi.method FROM page_content c JOIN widget_instances wi ON c.widget = wi.id JOIN widget_types wt ON wi.type = wt.id  WHERE c.page = :pageId ORDER BY c.order';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':pageId', $pageId);
        $stmt->execute();

        $widgets = $stmt->fetchAll();

        return $widgets;
    }

    private function resolveWidgets($widgets)
    {
        foreach($widgets as $key => $widget) {
            $widgets[$key] = self::resolveWidget($widget, $this);
        }

        return $widgets;
    }

    private function renderWidgets($widgets)
    {
        foreach ($widgets as $key => $widget) {
            $widgets[$key] = self::renderWidget($widget);
        }

        return $widgets;
    }

    private static function renderWidget($widget)
    {
        if (!isset($widget['inst'])) {
            return $widget;
        }

        try {
            ob_start();
            call_user_func(array($widget['inst'], 'render'));
            $widget['content'] .= ob_get_clean();
        } catch (Exception $e) {
            $widgetRet['content'] = self::renderWidgetException($e);
        }

        return $widget;
    }

    public static function resolveWidget($widget, $page = null)
    {
        $widgetRet = $widget;

        try {
            if (empty($widget['method'])) {
                $widget['method'] = 'display';
            }
            assert(!empty($widget['viewableController']));

            $widgetRet['inst'] = $inst = new $widget['viewableController']();
            //            $widgetRet['inst'] = $inst = new WidgetForm();
            $widgetRet['inst']->page = $page;
            $widgetRet['inst']->widgetId = $widget['id'];
            $widgetRet['inst']->widgetSetupCompleted(); 

            if (!is_callable(array($inst, $widget['method']))) {
                throw new Exception('Method is not callable on widget: ' . get_class($widgetRet['inst']) . '::' .  $widget['method']);
            }

            global $tpl;

            ob_start();
            call_user_func(array($inst, $widget['method']));
            $widgetRet['content'] = ob_get_clean();

            if (empty($widget['title'])) {
                $widgetRet['title'] = $widgetRet['inst']->getTitle();
            }
        } catch (Exception $e) {
            $widgetRet['inst']->displayEdit = true;
            $widgetRet['content'] = self::renderWidgetException($e);
        }

        return $widgetRet;
    }

    private static function renderWidgetException($e)
    {
        $html = '';
        $html .= '<div class = "framedBox">';
        $html .= '<p class = "bad"><strong>Exception thrown while trying to setup or render a widget.</strong></p>';
        $html .= '<p>';
        $html .= '<strong>Type:</strong> ' . get_class($e) .'<br/>';
        $html .= '<strong>Message:</strong> ' . $e->getMessage() . '<br />';
        $html .= '<strong>Line:</strong> ' . $e->getLine() .'<br />';
        $html .= '</p>';
        $html .= '</div>';

        return $html;
    }

    public function resolve()
    {
        $this->widgets = array();

        global $tpl;
        $tpl->assign('isLoggedIn', Session::isLoggedIn());

        try { 
            $this->page = $this->getPage();

            $tpl->assign('page', $this->page);
        } catch (Exception $e) {
            $this->page = array(
                'id' => 0,
                'layout' => 'minimal',
                'title' => $e->getMessage(),
                'isSystem' => true,
            );
            $this->widgets = array();

            $msg = new SimpleMessage('<a href = "?pageIdent=PAGE_CREATE">Create?</a>');
            $this->widgets[] = array('inst' => $msg, 'content' => null);
            $this->widgets = $this->renderWidgets($this->widgets);
            return;
        }

        $this->widgets = $this->getWidgets($this->page['id']);
        $this->widgets = $this->resolveWidgets($this->widgets);
        $this->widgets = $this->renderWidgets($this->widgets);
    }

    public function assignTpl()
    {
        global $tpl;

        $tpl->assign('page', $this->page);
        $tpl->assign('widgets', $this->widgets);
    }

    public function isSystem() 
    {
        return $this->page['isSystem'];
    }
}

?>
