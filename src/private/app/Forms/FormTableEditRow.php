<?php

namespace Sicroc\Forms;

use Sicroc\TableConfiguration;
use libAllure\Shortcuts as LA;
use Sicroc\Utils;

class FormTableEditRow extends \libAllure\Form implements \Sicroc\BaseForm
{
    private \Sicroc\TableConfiguration $tc;
    private array $fields;

    public function __construct()
    {
        parent::__construct('editRow', 'Edit Row');

        $primaryKeyValue = LA::san()->filterUint('primaryKey');

        $tcId = LA::san()->filterUint('tc');
        $this->tc = new TableConfiguration($tcId, $primaryKeyValue);

        if (!$this->tc->loaded) {
            throw new \Exception('cant load tc');
        }

        $this->addElementReadOnly('Table Configuration', $this->tc->id, 'tc');
        $this->addElementReadOnly('Primary Key', $primaryKeyValue, $this->tc->keycol);

        $this->fields = [];

        foreach ($this->tc->headers as $key => $header) {
            $this->fields[] = $header['name'];

            $el = $this->tc->getElementForColumn($header);

            if ($el != null) {
                $this->addElement($el);
            }
        }

        $this->addElementHidden('redirectTo', LA::san()->filterString('redirectTo'));

        $this->addDefaultButtons('Save');
    }

    public function process()
    {
        $fields = Utils::implodeQuoted($this->fields, '`');
        $values = array();
        unset($this->fields[0]);

        $sql = 'UPDATE ' . $this->tc->database . '.' . $this->tc->table . ' SET ';

        foreach ($this->fields as $field) {
            $val = $this->getElementValue($field);

            if (empty($val)) {
                $val = ' null';
            } else {
                $val = ' "' . $val .  '"';
            }

            $sql .= '`' . $field . '` = ' . $val . ', ';
        }

        $sql .= $this->tc->keycol . ' = ' . $this->tc->keycol;
        $sql .= ' WHERE ' . $this->tc->keycol . ' = ' . $this->getElementValue($this->tc->keycol);

        $stmt = LA::db()->prepare($sql);
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $redirectTo = $this->getElementValue('redirectTo');
            if (is_numeric($redirectTo)) {
                $state->redirect('?page=' . $redirectTo);
            } else {
                $state->redirect('?pageIdent=TABLE_ROW&tc=' . $this->tc->id . '&primaryKey=' . $this->getElementValue($this->tc->keycol));
            }
        }
    }
}
