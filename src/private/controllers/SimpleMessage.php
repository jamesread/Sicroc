<?php

class SimpleMessage extends ViewableController {
	public function __construct($message) {
		$this->message = $message;
	}

	public function render() {
		global $tpl;
		$tpl->assign('message', $this->message);
		$tpl->display('simple.tpl');
	}
}

?>
