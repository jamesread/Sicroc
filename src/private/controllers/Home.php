<?php

class HomeController extends ViewableController {
	function getVersion() {
		return 0.1;
	}
		
	function index() {
		global $tpl;


		$tpl->display('home.tpl');
	}
}

?>
