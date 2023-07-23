<?php

use libAllure\Form;
use libAllure\ElementInput;
use libAllure\DatabaseFactory;

class FormUsergroupCreate extends Form
{
    public function __construct()
    {
        $this->addElement(new ElementInput('title', 'Title'));
        $this->addDefaultButtons();
    }

    public function process()
    {
        $sql = 'INSERT INTO groups (title) VALUES (:title) ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);

        $stmt->bindValue('title', $this->getElementValue('title'));
        $stmt->execute();
    }
}
