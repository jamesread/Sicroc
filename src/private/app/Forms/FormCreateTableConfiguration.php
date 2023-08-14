<?php

namespace Sicroc\Forms;

use libAllure\ElementAlphaNumeric;
use libAllure\ElementCheckbox;

class FormCreateTableConfiguration extends \libAllure\Form
{
    public function __construct()
    {
        parent::__construct();

        $this->addElement(new ElementAlphaNumeric('db', 'Database', ''));
        $this->getElement('db')->setMinMaxLengths(1, 64);
        $this->addElement(new ElementAlphaNumeric('table', 'Table', ''));
        $this->addElement(new ElementCheckbox('createTableWidget', 'Create Table Widget?', true));

        $this->addDefaultButtons('Create');
    }

    public function process()
    {
        $sql = 'INSERT INTO table_configurations (`table`, `database`) VALUES (:table, :db) ';
        $stmt = \libAllure\util\stmt($sql);
        $this->bindStatementValues($stmt, [
            'table',
            'db',
        ]);

        $stmt->execute();

        if ($this->getElementValue('createTableWidget')) {
// TODO            $sql = 'INSERT INTO widget';
        }
    }

    public function setupProcessedState($state)
    {
        if ($state->processed) {
            $state->redirectIdent('TABLE_CONFIGURATION_LIST');
        }
    }
}
