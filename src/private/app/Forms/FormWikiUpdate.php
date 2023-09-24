<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\Sanitizer;
use libAllure\DatabaseFactory;
use libAllure\ElementTextbox;

class FormWikiUpdate extends Form
{
    private false|array $page;

    public function __construct()
    {
        parent::__construct('formWikiUpdate', 'Wiki Update');
        $this->page = $this->getPage();

        $this->addElementReadOnly('Page Title', $this->page['principle'], 'pageTitle');
        $this->addElement(new ElementTextbox('content', 'Content', $this->page['content']));

        $this->addDefaultButtons('Save page');
    }

    private function actualGetPage(string $page): false|array
    {
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

    private function getPage(): array
    {
        $page = Sanitizer::getInstance()->filterString('pageTitle');

        $wiki = $this->actualGetPage($page);

        if ($wiki == null) {
            $sql = 'INSERT INTO wiki_content (principle) VALUES (:principle)';
            $stmt = DatabaseFactory::getInstance()->prepare($sql);
            $stmt->bindValue(':principle', $page);
            $stmt->execute();

            $wiki = $this->actualGetPage($page);

            if ($wiki == null) {
                throw new \Exception('Could not create page');
            }
        }

        return $wiki;
    }

    public function process(): void
    {
        $sql = 'UPDATE wiki_content SET content = :content WHERE principle = :principle';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':content', $this->getElementValue('content'));
        $stmt->bindValue(':principle', $this->getElementValue('pageTitle'));
        $stmt->execute();
    }
}
