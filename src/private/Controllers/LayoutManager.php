<?php

namespace Sicroc\Controllers;

use \Sicroc\Controllers\Page;
use \Sicroc\Controllers\Table;
use \Sicroc\Controllers\Navigation;

use \libAllure\Session;

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
            self::$inst->resolvePage();
        }

        return self::$inst;
    }

    private function resolvePage()
    {
        $this->page = new Page();
        $this->page->resolve();
        $this->page->assignTpl();
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getEditMode() {
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
                'id' => 0,
            );
        } else {
            return $section;
        }
    }

    public function render()
    {
        global $tpl;

        $navigation = new Navigation();

        $tpl->assign('navigation', $navigation->getLinks());
        $tpl->assign('section', $this->getCurrentSection());
        $tpl->assign('editMode', $this->getEditMode());

        $this->assertPageRenderable();

        $tpl->display('layout.' . $this->page->getLayout() . '.tpl');
    }

    private function assertPageRenderable()
    {
        assert(!empty($this->page));
        assert(!empty($this->page->page));
        assert(!empty($this->page->page['layout']));
    }

    private function resolvePrinciple($p)
    {
        return (empty($p)) ? new Page() : new $p();
    }
}

?>
