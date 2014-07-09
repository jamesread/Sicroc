<?php

require_once CONTROLLERS_DIR . 'Page.php';
require_once CONTROLLERS_DIR . 'Table.php';
require_once CONTROLLERS_SYSTEM_DIR . 'Navigation.php';

abstract class LayoutManager {
	private static $principle;
	private static $method;
	private static $widgets;
	private static $page;

	public static function getPage() {
		return self::$page;
	}

	public static function setLayout($principle, $method) {
		global $db;

		self::$page = new Page();
		self::$page->resolve();
		self::$page->assignTpl();
	}

	public static function render() {
		global $tpl;

		$navigation = new Navigation(self::$page->page['id']);

		$tpl->assign('navigation', $navigation->getSectionTitles());

		self::assertPageRenderable();

		$tpl->display('layout.' . self::$page->page['layout'] . '.tpl');
	}

	private static function assertPageRenderable() {
		assert(!empty(self::$page));
		assert(!empty(self::$page->page));
		assert(!empty(self::$page->page['layout']));
	}

	private static function resolvePrinciple($p) {
		return (empty($p)) ? new Page() : new $p();
	}
}

?>
