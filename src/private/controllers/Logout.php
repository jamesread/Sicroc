<?php

use \libAllure\Session;

class Logout extends ViewableController {
	public function display() {}
	public function render() {
		Session::logout();

		global $tpl;

		$tpl->assign('message', 'You have been logged out.');
		$tpl->display('simple.tpl');
	}
}
