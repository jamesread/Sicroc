<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\DatabaseFactory;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\Shortcuts as LA;

class FormNavigationLinkCreate extends Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        parent::__construct('formNavigatonLinkCreate', 'Create Navigation Link');

        $sectionToEdit = LA::san()->filterUint('sectionToEdit');

        $this->addElement(new ElementInput('title', 'Title'));
        $this->addDefaultButtons('Create link');
    }

    public function process()
    {
        $sql = 'INSERT INTO navigaton_links (title, master) VALUES (:title, 1)';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->redirectIdent('NAVIGATION_LIST');
        }
    }
}
