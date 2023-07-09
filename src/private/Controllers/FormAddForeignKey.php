<?php

use \libAllure\Form;
use \libAllure\ElementCheckbox;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;

class FormAddForeignKey extends Form {
    public function __construct() {
        parent::__construct('addFk', 'Add Foreign Key');

        $table = san()->filterString('table');

        $this->addElementReadOnly('Table', $table, 'table');

        $this->addElement($this->getElementSourceField($table));
        $this->addElement(new ElementInput('foreignTable', 'Foreign Table', ''));
        $this->addElement(new ElementInput('foreignField', 'Foreign Field', 'id', 'This normally should be `id`.'));
        $this->getElement('foreignField')->setMinMaxLengths(0, 64);
        $this->addElement(new ElementInput('foreignDescription', 'Descriptive field', ''));

        $this->addDefaultButtons();
    }

    private function getElementSourceField($table) {
        $el = new ElementSelect('sourceField', 'Source field');

        $tbl = new Table($table);
        $tbl->widgetSetupCompleted();

        $el->addOption('--', null);

        foreach ($tbl->getHeadersOfType('INT1') as $header) {
            $el->addOption($header['name']);
        }

        return $el;
    }

    public function process() {
        $sql = 'ALTER TABLE ' . san()->filterString('table') . ' ADD CONSTRAINT FOREIGN KEY (' . $this->getElementValue('sourceField'). ') REFERENCES ' . $this->getElementValue('foreignTable'). '(' .  $this->getElementValue('foreignField').') ';
        $stmt = db()->prepare($sql);
        $stmt->execute();

        $sql = 'INSERT INTO table_fk_metadata (sourceTable, sourceField, foreignTable, foreignField, foreignDescription) VALUES (:sourceTable, :sourceField, :foreignTable, :foreignField, :foreignDescription)';
        $stmt = db()->prepare($sql);
        $stmt->bindValue(':sourceTable', $this->getElementValue('table'));
        $stmt->bindValue(':sourceField', $this->getElementValue('sourceField'));
        $stmt->bindValue(':foreignTable', $this->getElementValue('foreignTable'));
        $stmt->bindValue(':foreignField', $this->getElementValue('foreignField'));
        $stmt->bindValue(':foreignDescription', $this->getElementValue('foreignDescription'));
        $stmt->execute();
    }
}

?>
