<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\DatabaseFactory;

class FormPageCreate extends Form {
	public function __construct() {
		parent::__construct('formPageCreate', 'Page Create');

		$this->addElement(new ElementInput('title', 'Title'));
		$this->addElement(new ElementInput('ident', 'Ident'));

		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'INSERT INTO pages (title, ident) VALUES (:title, :ident)';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':ident', $this->getElementValue('ident'));
		$stmt->execute();
	}
}

?>
