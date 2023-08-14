<?php

namespace Sicroc\Forms;

use libAllure\Form;
use Sicroc\TableConfiguration;

use function libAllure\util\san;
use function libAllure\util\db;
use function Sicroc\implodeQuoted;

class FormTableInsert extends Form implements \Sicroc\BaseForm
{
    private string|null $keycol;
    private array $fields;
    private ?\Sicroc\TableConfiguration $tc;

    public function __construct($controller)
    {
        parent::__construct('formTableInsert', 'Insert into table');

        $this->tc = new TableConfiguration(san()->filterUint('tc'));

        $this->addElementReadOnly('Table Configuration', $this->tc->id, 'tc');

        $fields = array();

        foreach ($this->tc->headers as $header) {
            $el = $this->tc->getElementForColumn($header);

            if ($el != null) {
                $fields[] = $header['name'];

                $this->addElement($el);
            }
        }

        $this->fields = $fields;

        $this->addDefaultButtons('Insert');
    }


    public function process()
    {
        $fields = implodeQuoted($this->fields, '`');
        $values = array();

        foreach ($this->fields as $field) {
            $values[] = $this->getElementValue($field);
        }

        $sql = 'INSERT INTO ' . $this->tc->database . '.' . $this->tc->table . ' (' . $fields . ') VALUES (' . \Sicroc\implodeQuoted($values, '"', true) . ') ';
        $stmt = db()->prepare($sql);
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->redirect('?pageIdent=TABLE_VIEW&tc=' . $this->tc->id);
        }
    }
}
