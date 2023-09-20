<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\ElementHidden;
use libAllure\Shortcuts as LA;
use libAllure\Session;
use Sicroc\TableConfiguration;
use Sicroc\Utils;

class FormTableInsert extends Form implements \Sicroc\BaseForm
{
    private array $fields;
    private ?\Sicroc\TableConfiguration $tc;

    public function __construct()
    {
        parent::__construct('formTableInsert', 'Insert into table');

        $this->tc = new TableConfiguration(LA::san()->filterUint('tc'));

        if (Session::getUser()->getData('showTcOnRowForms')) {
            $this->addElementReadOnly('Table Configuration', $this->tc->id, 'tc');
        } else {
            $this->addElement(new ElementHidden('tc', $this->tc->id, 'tc'));
        }

        $fields = array();

        foreach ($this->tc->headers as $header) {
            $el = $this->tc->getElementForColumn($header);

            if (strpos($header['name'], '_fk_')) {
                continue;
            }

            if ($el != null) {
                $fields[] = $header['name'];

                $val = LA::san()->filterString($header['name']);

                if ($val != null) {
                    $el->setValue($val);
                }

                $this->addElement($el);
            }
        }

        $this->fields = $fields;

        $this->addDefaultButtons($this->tc->createPhrase);
    }


    public function process()
    {
        $fields = Utils::implodeQuoted($this->fields, '`');
        $values = array();

        foreach ($this->fields as $field) {
            $values[] = $this->getElementValue($field);
        }

        $sql = 'INSERT INTO ' . $this->tc->database . '.' . $this->tc->table . ' (' . $fields . ') VALUES (' . Utils::implodeQuoted($values, '"', true) . ') ';
        $stmt = LA::db()->prepare($sql);
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            if (isset($_SESSION['lastTcViewPage'])) {
                $state->redirect('?page=' . $_SESSION['lastTcViewPage']);
            } else {
                $state->redirect('?pageIdent=TABLE_VIEW&tc=' . $this->tc->id);
            }
        }
    }
}
