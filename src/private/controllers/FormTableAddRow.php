<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;

class FormTableAddRow extends Form {
	public function __construct() {
		parent::__construct('addRow', 'Add Row');

		$this->addElementReadOnly('table', san()->filterString('table'), 'table');
		$this->addElement(new ElementInput('name', 'Name'));
		$el = new ElementSelect('type', 'Type');
		$el->addOption('varchar(255)');
		$el->addOption('tinyint(1)');
		$el->addOption('int');
		$this->addElement($el);
		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'ALTER TABLE ' . $this->getElementValue('table') . ' ADD ' . $this->getElementValue('name') . ' ' . $this->getElementValue('type');

		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();
	}
}

?>
