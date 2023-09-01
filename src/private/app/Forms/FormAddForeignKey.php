<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\ElementCheckbox;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\Sanitizer;
use libAllure\Shortcuts as LA;
use Sicroc\TableConfiguration;

class FormAddForeignKey extends Form
{
    private TableConfiguration $tc;

    public function __construct()
    {
        parent::__construct('addFk', 'Add Foreign Key');

        $this->tc = new TableConfiguration(LA::san()->filterUint('tc'));

        $this->addElementReadOnly('TC', $this->tc->id, 'tc');

        $this->addElement($this->getElementSourceField());
        $this->addElement(new ElementInput('foreignTable', 'Foreign Table', ''));
        $this->addElement(new ElementInput('foreignField', 'Foreign Field', 'id', 'This normally should be `id`.'));
        $this->getElement('foreignField')->setMinMaxLengths(0, 64);
        $this->addElement(new ElementInput('foreignDescription', 'Descriptive field', ''));

        $this->addDefaultButtons();
    }

    private function getElementSourceField()
    {
        $el = new ElementSelect('sourceField', 'Source field');

        $el->addOption('--', null);

        foreach ($this->tc->getHeadersOfType('LONG') as $header) {
            $el->addOption($header['name']);
        }

        return $el;
    }

    public function process()
    {
        //$sql = 'ALTER TABLE ' . Sanitizer::getInstance()->filterString('table') . ' ADD CONSTRAINT FOREIGN KEY (' . $this->getElementValue('sourceField') . ') REFERENCES ' . $this->getElementValue('foreignTable') . '(' .  $this->getElementValue('foreignField') . ') ';
        /**
        $sql = 'ALTER TABLE ' . $this->tc->table . ' ADD CONSTRAINT FOREIGN KEY (' . $this->getElementValue('sourceField') . ') REFERENCES ' . $this->getElementValue('foreignTable') . '(:foreignField) ';
        $stmt = db()->prepare($sql);
        $stmt->bindValue('foreignField', $this->getElementValue('foreignField'));
        $stmt->execute();
         */

        $sql = 'INSERT INTO table_fk_metadata (sourceTable, sourceField, foreignTable, foreignField, foreignDescription) VALUES (:sourceTable, :sourceField, :foreignTable, :foreignField, :foreignDescription)';
        $stmt = LA::stmt($sql);
        $stmt->bindValue(':sourceTable', $this->tc->table);
        $stmt->bindValue(':sourceField', $this->getElementValue('sourceField'));
        $stmt->bindValue(':foreignTable', $this->getElementValue('foreignTable'));
        $stmt->bindValue(':foreignField', $this->getElementValue('foreignField'));
        $stmt->bindValue(':foreignDescription', $this->getElementValue('foreignDescription'));
        $stmt->execute();
    }
}
