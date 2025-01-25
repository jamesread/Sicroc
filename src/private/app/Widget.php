<?php

namespace Sicroc;

use libAllure\Session;
use libAllure\Shortcuts as LA;
use libAllure\HtmlLinksCollection;
use libAllure\Template;
use libAllure\Element;
use libAllure\ElementSelect;
use libAllure\ElementCheckbox;
use Sicroc\Controller;

abstract class Widget
{
    public HtmlLinksCollection $navigation;
    public int $widgetId;
    public bool $displayEdit = false;

    protected ?array $argValues = null;

    protected Template $tpl;

    public ?\Sicroc\Page $page;

    public function __construct()
    {
        global $tpl; // FIXME

        $this->tpl = $tpl;

        if (Session::isLoggedIn()) {
            if (Session::getUser()->getData('editMode')) {
                $this->displayEdit = true;
            }
        }

        $this->navigation = new HtmlLinksCollection();
        $this->tpl->assign('title', get_class($this));
        $this->tpl->assign('base', 'http://www.tydus.net/Sicroc/src/public/');

        $this->assignUser();
    }

    public function getTitle(): string
    {
        return 'Untitled controller';
    }


    public function widgetSetupCompleted(): void
    {
    }

    private function assignUser(): void
    {
        global $tpl;

        $user = array (

        );

        if (!Session::isLoggedIn()) {
            $user['username'] = 'Guest';
        } else {
            $user['username'] = Session::getUser()->getUsername();
        }

        $tpl->assign('user', $user);
    }

    public function render(): void
    {
        $this->simpleMessage('This is a simple widget (widget has not overridden Controller::render(). )');
    }

    public static function getLink(string $caption, string $controller, string $method = 'index', array $params = []): string
    {
        return '<a href = "' . self::getUrl($controller, $method, $params) . '">' . $caption . '</a>';
    }

    public static function getUrl(mixed $controller, string $method = 'index', array $params = []): string
    {
        if (is_object($controller)) {
            $controller = get_class($controller);
        } else {
            $controller = strval($controller);
        }

        $query = http_build_query($params);

        return '?controller=' . $controller . '&amp;method=' . $method . '&amp;page=' . $params['page'];
    }

    public function getArgumentValues(): array
    {
        if ($this->argValues == null) {
            $sql = 'SELECT v.`key`, v.value FROM widget_argument_values v WHERE v.widget = :widget';
            $stmt = LA::stmt($sql);
            $stmt->bindValue(':widget', $this->widgetId);
            $stmt->execute();

            $this->argValues = array();

            foreach ($stmt->fetchAll() as $arg) {
                $this->argValues[$arg['key']] = $arg['value'];
            }
        }

        return $this->argValues;
    }

    public function getArgumentValue(string $name): string|null
    {
        $this->getArgumentValues();

        if (isset($this->argValues[$name])) {
            return $this->argValues[$name];
        } else {
            return null;
        }
    }

    public function getArguments(): array
    {
        return array();
    }

    public function hasArguments(): bool
    {
        return sizeof($this->getArguments()) > 0;
    }

    public function getArgumentElement(string $name, string $type, mixed $val = 0): Element
    {
        switch ($type) {
            case 'boolean':
                $el = new ElementCheckbox($name, $name);

                return $el;
        }

        switch ($name) {
            case 'table_configuration':
                $el = new ElementSelect($name, $name);
                $el->addOption('---', null);

                $sql = 'SELECT tc.id, tc.database, tc.table FROM table_configurations tc ORDER BY tc.database, tc.table';

                $stmt = LA::stmt($sql);
                $stmt->execute();

                foreach ($stmt->fetchAll() as $tc) {
                    $el->addOption($tc['database'] . '.' . $tc['table'], $tc['id']);
                }

                $el->setValue($val);

                return $el;
            default:
                $el = new \libAllure\ElementInput($name, $name, $val);
                $el->setMinMaxLengths(0, 1024);
        }

        return $el;
    }

    public function simpleMessage(string $message, string $messageClass = 'neutral'): void
    {
        $this->tpl->assign('messageClass', $messageClass);
        $this->tpl->assign('message', $message);
        $this->tpl->display('simple.tpl');
    }

    public function simpleErrorMessage(string $message): void
    {
        $this->simpleMessage($message, 'bad');
    }

    public function shouldRender(): bool {
        return true;
    }
}
