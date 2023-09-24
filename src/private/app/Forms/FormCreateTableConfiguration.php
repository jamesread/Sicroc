<?php

namespace Sicroc\Forms;

use Sicroc\BaseDatabaseStructure;
use Sicroc\ProcessedFormState;
use libAllure\ElementAlphaNumeric;
use libAllure\ElementCheckbox;
use libAllure\Shortcuts as LA;

class FormCreateTableConfiguration extends \libAllure\Form
{
    public function __construct()
    {
        parent::__construct();

        $this->addElement(new ElementAlphaNumeric('db', 'Database', ''));
        $this->getElement('db')->setMinMaxLengths(1, 64);
        $this->addElement(new ElementAlphaNumeric('table', 'Table', ''));
        $this->addElement(new ElementCheckbox('createTable', 'Create table in database?', true));
        $this->addElement(new ElementCheckbox('createTableWidgetAndPage', 'Create table widget & page?', true));
        $this->addElement(new ElementCheckbox('createNavigation', 'Add Page to navigation?', true));

        $this->addDefaultButtons('Create');
    }

    public function process(): void
    {
        if ($this->getElementValue('createTable')) {
            $sql = 'CREATE TABLE ' . $this->getElementValue('db') . '.' . $this->getElementValue('table') . ' (id int not null primary key auto_increment)';
            $stmt = LA::stmt($sql);
            $stmt->execute();
        }

        $sql = 'INSERT INTO table_configurations (`table`, `database`) VALUES (:table, :db) ';
        $stmt = LA::stmt($sql);
        $this->bindStatementValues($stmt, [
            'table',
            'db',
        ]);

        $stmt->execute();

        $tbl = $this->getElementValue('table');
        $db = $this->getElementValue('db');

        $dbs = new BaseDatabaseStructure();

        if ($this->getElementValue('createTableWidgetAndPage')) {
            $dbs->addPage($tbl, $tbl, [
                $dbs->defineWidgetTable($tbl, $db),
            ]);

            $dbs->execute();

            if ($this->getElementValue('createNavigation')) {
                $pageId = $dbs->ensurePageExists([
                    'ident' => $tbl,
                    'title' => $tbl
                ]);

                $sql = 'INSERT INTO navigation_links (title, index_page, master) VALUES (:title, :index, 1) ';
                $stmt = LA::db()->prepare($sql);
                $stmt->bindValue('title', $tbl);
                $stmt->bindValue('index', $pageId);
                $stmt->execute();
            }
        }
    }

    public function setupProcessedState(ProcessedFormState $state): void
    {
        if ($state->processed) {
            $state->redirectIdent('TABLE_CONFIGURATION_LIST');
        }
    }
}
