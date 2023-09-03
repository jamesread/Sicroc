<?php

namespace Sicroc;

use libAllure\Session;
use libAllure\Shortcuts as LA;
use Sicroc\Controller;

abstract class Widget
{
    public $navigation;
    public $widgetId;
    public $displayEdit = false;

    protected $tpl;

    public ?\Sicroc\Page $page;

    public function __construct($principle = null)
    {
        global $tpl; // FIXME

        $this->tpl = $tpl;

        if (Session::isLoggedIn()) {
            if (Session::getUser()->getData('editMode')) {
                $this->displayEdit = true;
            }
        }

        $this->navigation = new \libAllure\HtmlLinksCollection();
        $this->tpl->assign('title', get_class($this));
        $this->tpl->assign('base', 'http://www.tydus.net/Sicroc/src/public/');

        $this->assignUser();
    }

    public function getAdminLinks()
    {
    }

    public function getNavigationMain()
    {
    }

    public function classHasParent($class, $parent)
    {
        $parents = class_parents($class);

        foreach ($parents as $mybottom) {
            if ($mybottom == $parent) {
                return true;
            }
        }

        return false;
    }

    public function getTitle()
    {
        return 'Untitled controller';
    }


    public function widgetSetupCompleted()
    {
    }

    private function assignUser()
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

    public function render()
    {
        $this->simpleMessage('This is a simple widget (widget has not overridden Controller::render(). )');
    }

    public static function getLink($caption, $controller, $method = 'index', $params = array())
    {
        return '<a href = "' . self::getUrl($controller, $method, $params) . '">' . $caption . '</a>';
    }

    public static function getUrl($controller, $method = 'index', $params = array())
    {
        if (is_object($controller)) {
            $controller = get_class($controller);
        } else {
            $controller = strval($controller);
        }

        $query = http_build_query($params);

        return '?controller=' . $controller . '&amp;method=' . $method . '&amp;page=' . $params['page'];
    }

    protected $argValues = null;

    public function getArgumentValues()
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

    public function getArgumentValue($name)
    {
        $this->getArgumentValues();

        if (isset($this->argValues[$name])) {
            return $this->argValues[$name];
        } else {
            return null;
        }
    }

    public function getArguments()
    {
        return array();
    }

    public function hasArguments()
    {
        return sizeof($this->getArguments()) > 0;
    }

    public function getArgumentElement(string $name, string $type, $val = 0)
    {
        $el = new \libAllure\ElementInput($name, $name, $val);
        $el->setMinMaxLengths(0, 1024);
        return $el;
    }

    public function simpleMessage($message, $messageClass = 'neutral')
    {
        $this->tpl->assign('messageClass', $messageClass);
        $this->tpl->assign('message', $message);
        $this->tpl->display('simple.tpl');
    }

    public function simpleErrorMessage($message)
    {
        $this->simpleMessage($message, 'bad');
    }
}
