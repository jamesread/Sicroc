<?php

use \libAllure\Form;
use \libAllure\DatabaseFactory;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;

class FormSectionUpdate extends Form {
	public function __construct() {
		parent::__construct('formSectionUpdate', 'Section Update');

		$sectionToEdit = san()->filterUint('sectionToEdit');
		$section = $this->getSection($sectionToEdit);

		$this->addElementHidden('sectionToEdit', $sectionToEdit);
		$this->addElement(new ElementInput('title', 'Title', $section['title']));
		$this->addElement($this->getElementMaster($section['master']));
		$this->addElement($this->getElementIndexPage($section['index']));
		$this->addDefaultButtons();
	}

	private function getElementIndexPage($currentIndex) {
		$sql = 'SELECT p.id, p.title FROM pages p ORDER BY p.title ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$el = new ElementSelect('indexPage', 'Index Page');

		foreach ($stmt->fetchAll() as $page) {
			$el->addOption($page['title'], $page['id']);
		}

		$el->setValue($currentIndex);

		return $el;

	}

	private function getElementMaster($current) {
		$sql = 'SELECT s.id, s.title FROM sections s';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$el = new ElementSelect('master', 'Master section');
		$el->addOption('(none)', null);

		foreach ($stmt->fetchAll() as $section) {
			$el->addOption($section['title'], $section['id']);
		}

		$el->setValue($current);

		return $el;
	}

	private function getSection($id) {
		$stmt = DatabaseFactory::getInstance()->prepareSelectById('sections', $id, 'title', 'master', '`index`');
		$stmt->execute();

		return $stmt->fetchRowNotNull();
	}

	public function process() {
		$sql = 'UPDATE sections SET title = :title, master = :master, `index` = :index WHERE id = :id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':id', $this->getElementValue('sectionToEdit'));
		$stmt->bindValue(':master', $this->getElementValue('master'));
		$stmt->bindValue(':index', $this->getElementValue('indexPage'));
		$stmt->execute();
	}
}

?>
