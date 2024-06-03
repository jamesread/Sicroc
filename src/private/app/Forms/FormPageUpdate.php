<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\Sanitizer;
use libAllure\DatabaseFactory;
use libAllure\ElementInput;
use libAllure\ElementHidden;
use libAllure\ElementSelect;

class FormPageUpdate extends Form
{
    private array $page;

    public function __construct()
    {
        parent::__construct('pageEdit', 'Page properties');

        $this->page = $this->getPage();

        $this->addElement(new ElementInput('title', 'Title', $this->page['title']));

        $this->addElement(new ElementHidden('pageToEdit', 'Page to edit', Sanitizer::getInstance()->filterUint('pageToEdit')));
        $this->addDefaultButtons('Save');
    }

    private function getPage(): array
    {
        $sanitizer = Sanitizer::getInstance();

        $sql = 'SELECT p.id, p.title FROM pages p WHERE p.id = :page LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':page', $sanitizer->filterUint('pageToEdit'));
        $stmt->execute();

        return $stmt->fetchRowNotNull();
    }

    public function process(): void
    {
        $sql = 'UPDATE pages SET title = :title WHERE id = :id LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->bindValue(':id', $this->page['id']);
        $stmt->execute();
    }
}
