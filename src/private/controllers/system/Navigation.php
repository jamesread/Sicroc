<?php

use \libAllure\DatabaseFactory;

class Navigation extends Controller {
	public function __construct($pageId) {
		$this->section = $this->getSection($pageId);
	}

	private function getSection() {
		global $tpl;

		$sql = 'SELECT s.id, s.title FROM sections s WHERE s.id = :id ORDER BY s.title ASC LIMIT 1';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':id', 1);
		$stmt->execute();

		$section = $stmt->fetchRow();

		$tpl->assign('section', $section);

		return $section;
	}
	
	private function getSubsections() {
		$sql = 'SELECT s.title, s.master, s.index FROM sections s WHERE s.master = :masterSectionId ORDER BY s.title ASC';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':masterSectionId', $this->section['id']);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/**
	 * @return LinkList
	 */
	public function getSectionTitles() {
		$ll = new LinkList('Navigation');

		foreach ($this->getSubsections() as $section) {
			$ll->add($section['title'], 'Page', 'index', array('page' => $section['index']));
		}

		return $ll->get();
	}

}

?>
