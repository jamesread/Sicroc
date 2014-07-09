<?php

require_once 'libAllure/Sanitizer.php';

use \libAllure\ElementHidden;
use \libAllure\Sanitizer;

class ControllerForm extends ViewableController {
	public function widgetSetupCompleted() {
		$principle = $this->getArgumentValue('formClass');

		if (!empty($principle)) {
			require_once CONTROLLERS_DIR . $principle . '.php';
			$this->f = new $principle($this);
			$this->f->addElementDetached(new ElementHidden('page', null, LayoutManager::getPage()->getId()));
		}
	}

	public function getArguments() {
		$args = array();
		$args[] = array('type' => 'varchar', 'name' => 'formClass', 'default' => '', 'description' => 'The name of the form class');

		return $args;
	}

	public function display() {
		global $tpl;

		if (!isset($this->f)) {
			return;
		}

		if ($this->f->validate()) {
			$this->f->process();

			if (isset($this->f->redirectUrl)){
				if (isset($this->f->redirectMessage)) {
					$redirectMessage = $this->f->redirectMessage;
				} else {
					$redirectMessage = 'Form submitted';
				}

				redirect($this->f->redirectUrl, $redirectMessage);
			}

			$tpl->assign('message', '<span class = "good">Form submitted.</span> ');
			$tpl->display('simple.tpl');
		}

		$tpl->assignForm($this->f);
	}

	public function render() {
		global $tpl;

		if (isset($this->f)) {
			$tpl->display('form.tpl');
		} else {
			$tpl->assign('message', 'This widget is assigned with a form controller, but no form has been constructed, possibly due to some sort of error. Sorry that this message is mostly useless.');
			$tpl->display('simple.tpl');
		}

	}

	public function validate() {
		global $tpl;

		$this->f->process();

	}

	public function getTitle() {
		if (isset($this->f)) {
			return 'Form: ' . $this->f->getTitle();
		} else {
			return 'No form constructed';
		}
	}
}

?>
