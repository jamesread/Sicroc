<?php

use libAllure\Form;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\DatabaseFactory;

use function libAllure\util\san;

class FormTableAddColumn extends Form
{
    public function __construct()
    {
        parent::__construct('addColumn', 'Add Column');

        $this->addElementReadOnly('db', san()->filterString('db'), 'db');
        $this->addElementReadOnly('table', san()->filterString('table'), 'table');
        $this->addElement(new ElementInput('name', 'Name'));
        $this->getElement('name')->setMinMaxLengths(1, 255);

        $el = new ElementSelect('type', 'Type');
        $el->addOption('varchar(255)');
        $el->addOption('datetime default current_timestamp()');
        $el->addOption('float(8,2)');
        $el->addOption('tinyint(1)');
        $el->addOption('int');
        $this->addElement($el);
        $this->addDefaultButtons();
    }

    public function process()
    {
        $sql = 'ALTER TABLE ' . $this->getElementValue('db') . '.' . $this->getElementValue('table') . ' ADD ' . $this->getElementValue('name') . ' ' . $this->getElementValue('type');

        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();
    }
}
