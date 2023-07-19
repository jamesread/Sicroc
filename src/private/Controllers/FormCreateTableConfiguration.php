<?php

use \libAllure\ElementAlphaNumeric;

class FormCreateTableConfiguration extends \libAllure\Form {
    public function __construct() {
        parent::__construct();

        $this->addElement(new ElementAlphaNumeric('db', 'Database', ''));
        $this->getElement('db')->setMinMaxLengths(1, 64);
        $this->addElement(new ElementAlphaNumeric('table', 'Table', ''));

        $this->addDefaultButtons('Create');
    }

    public function process() {
        $sql = 'INSERT INTO table_configurations (`table`, `database`) VALUES (:table, :db) ';
        $stmt = \libAllure\util\stmt($sql);
        $this->bindStatementValues($stmt, [
            'table',
            'db',
        ]);

        $stmt->execute();
    }
}
