<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\DatabaseFactory;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\Shortcuts as LA;

class FormSectionCreate extends Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        parent::__construct('formSectionCreate', 'Section Create');

        $sectionToEdit = LA::san()->filterUint('sectionToEdit');

        $this->addElement(new ElementInput('title', 'Title'));
        $this->addDefaultButtons('Create');
    }

    public function process()
    {
        $sql = 'INSERT INTO sections (title, master) VALUES (:title, 1)';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->redirectIdent('SECTION_LIST');
        }
    }
}
