<?php

use \libAllure\Form;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\ElementTextbox;

class FormWikiUpdate extends Form {
	public function __construct() {
		parent::__construct('formWikiUpdate', 'Wiki Update');
		$this->page = $this->getPage();

		$this->addElementReadOnly('Page Title', $this->page['principle'], 'pageTitle');
		$this->addElement(new ElementTextbox('content', 'Content', $this->page['content']));

		$this->addDefaultButtons();
	}

	private function actualGetPage($page) {
		$sql = 'SELECT w.principle, w.content FROM wiki_content w WHERE w.principle = :principle';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':principle', $page);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			return false;
		} else {
			return $stmt->fetchRow();
		}
	}

	private function getPage() {
		$page = Sanitizer::getInstance()->filterString('pageTitle');

		$wiki = $this->actualGetPage($page);

		if ($wiki == null) {
			$sql = 'INSERT INTO wiki_content (principle) VALUES (:principle)';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->bindValue(':principle', $page);
			$stmt->execute();

			$wiki = $this->actualGetPage($page);

			if ($wiki == null) {
				throw new Exception('Could not create page');
			}
		}

		return $wiki;
	}

	public function process() {
		$sql = 'UPDATE wiki_content SET content = :content WHERE principle = :principle';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':content', $this->getElementValue('content'));
		$stmt->bindValue(':principle', $this->getElementValue('pageTitle'));
		$stmt->execute();
	}
}

?>
