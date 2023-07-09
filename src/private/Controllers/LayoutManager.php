<?php

namespace Sicroc\Controllers;

use \Sicroc\Controllers\Page;
use \Sicroc\Controllers\Table;
use \Sicroc\Controllers\Navigation;

class LayoutManager {
    private $principle;
    private $method;
    private $widgets;
    private $page;

    private static $inst;

    private function __construct() {
        global $db;
    }

    public static function get() {
        if (self::$inst == NULL) {
            self::$inst = new LayoutManager();
            self::$inst->resolvePage();
        }

        return self::$inst;
    }

    private function resolvePage() {
        $this->page = new Page();
        $this->page->resolve();
        $this->page->assignTpl();
    }

    public function getPage() {
        return $this->page;
    }

    public function render() {
        global $tpl;

        $navigation = new Navigation($this->page->page['id']);

        $tpl->assign('navigation', $navigation->getSectionTitles());

        $this->assertPageRenderable();

        $tpl->display('layout.' . $this->page->page['layout'] . '.tpl');
    }

    private function assertPageRenderable() {
        assert(!empty($this->page));
        assert(!empty($this->page->page));
        assert(!empty($this->page->page['layout']));
    }

    private function resolvePrinciple($p) {
        return (empty($p)) ? new Page() : new $p();
    }
}

?>
