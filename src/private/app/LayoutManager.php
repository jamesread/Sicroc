<?php

namespace Sicroc;

use Sicroc\Page;
use Sicroc\Table;
use Sicroc\Navigation;
use libAllure\Session;

class LayoutManager
{
    private $principle;
    private $method;
    private $widgets;
    private $page;

    private static $inst;

    private function __construct()
    {
        global $db;
    }

    public static function get()
    {
        if (self::$inst == null) {
            self::$inst = new LayoutManager();
        }

        return self::$inst;
    }

    private function resolvePage()
    {
        $this->page->assignTpl();
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getEditMode()
    {
        $isSystemPage = $this->page->isSystem();

        if (Session::isLoggedIn()) {
            $v = Session::getUser()->getData('editMode');
            return $v;
        } else {
            return false;
        }
    }

    public function getCurrentSection()
    {
        $sql = 'SELECT * FROM sections s WHERE s.index = :page ';
        $stmt = \libAllure\util\stmt($sql);
        $stmt->bindValue(':page', $this->page->getId());
        $stmt->execute();

        $section = $stmt->fetchRow();

        if ($section == false) {
            return array(
                'title' => 'nosection',
                'index' => 0,
                'id' => 0,
            );
        } else {
            return $section;
        }
    }

    public function render()
    {
        global $tpl;

        assert($tpl != null);

        $this->page = new Page();
        $this->page->resolve();

        $this->assertPageRenderable();

        $this->nav = new Navigation();
        $this->nav->lastPage($this->page->getId());

        $tpl->assign('navigation', $this->nav->getLinks());
        $tpl->assign('section', $this->getCurrentSection());
        $tpl->assign('editMode', $this->getEditMode());
        $tpl->assign('isLoggedIn', Session::isLoggedIn());

        $tpl->assign('page', $this->page->getForTpl());
        $tpl->assign('widgets', $this->page->getWidgetsForTpl());

        $tpl->display('layout.' . $this->page->getLayout() . '.tpl');
    }

    private function assertPageRenderable()
    {
        assert(!empty($this->page));
        assert(!empty($this->page->page));
        assert(!empty($this->page->page['layout']));
    }
}
