<?php

use \libAllure\Form;
use \libAllure\DatabaseFactory;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;

class FormSectionCreate extends Form {
	public function __construct() {
		parent::__construct('formSectionCreate', 'Section Create');

		$sectionToEdit = san()->filterUint('sectionToEdit');
		$section = $this->getSection($sectionToEdit);

		$this->addElement(new ElementInput('title', 'Title', $section['title']));
		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'INSERT INTO sections (title) VALUES (:title)';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->execute();
	}
}

?>
