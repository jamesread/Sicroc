<?php

use libAllure\Form;
use libAllure\DatabaseFactory;
use libAllure\ElementInput;
use libAllure\ElementSelect;

use function libAllure\util\san;

class FormSectionCreate extends Form
{
    public function __construct()
    {
        parent::__construct('formSectionCreate', 'Section Create');

        $sectionToEdit = san()->filterUint('sectionToEdit');

        $this->addElement(new ElementInput('title', 'Title'));
        $this->addDefaultButtons();
    }

    public function process()
    {
        $sql = 'INSERT INTO sections (title, master) VALUES (:title, 1)';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->execute();
    }
}
