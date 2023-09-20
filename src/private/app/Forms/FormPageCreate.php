<?php

namespace Sicroc\Forms;

use Sicroc\ProcessedFormState;
use libAllure\Form;
use libAllure\ElementInput;
use libAllure\DatabaseFactory;

class FormPageCreate extends Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        parent::__construct('formPageCreate', 'Page Create');

        $this->addElement(new ElementInput('title', 'Title'));
        $this->addElement(new ElementInput('ident', 'Ident'));

        $this->addDefaultButtons('Create page');
    }

    public function process(): void
    {
        $sql = 'INSERT INTO pages (title, ident) VALUES (:title, :ident)';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->bindValue(':ident', $this->getElementValue('ident'));
        $stmt->execute();
    }

    public function setupProcessedState(ProcessedFormState $state): void
    {
        if ($state->processed) {
            $state->redirectIdent('PAGE_LIST');
        }
    }
}
