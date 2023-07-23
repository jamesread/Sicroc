<?php

use \Sicroc\Controllers\TableConfiguration;

use function \libAllure\util\san;
use function \libAllure\util\db;

class FormTableEditRow extends \libAllure\Form implements \Sicroc\Controllers\BaseForm
{
    private \Sicroc\Controllers\TableConfiguration $tc;
    private array $fields;

    public function __construct()
    {
        parent::__construct('editRow', 'Edit Row');

        $primaryKeyValue = san()->filterUint('primaryKey');

        $tcId = san()->filterUint('tc');
        $this->tc = new TableConfiguration($tcId, $primaryKeyValue);

        $this->addElementReadOnly('Table Configuration', $this->tc->id, 'tc');
        $this->addElementReadOnly('Primary Key', $primaryKeyValue, $this->tc->keycol);

        $this->fields = [];

        foreach ($this->tc->getHeaders() as $key => $header) {
            $this->fields[] = $header['name'];

            $el = $this->tc->getElementForColumn($header);

            if ($el != null) {
                $this->addElement($el);
            }
        }

        $this->addElementHidden('redirectTo', san()->filterString('redirectTo'));

        $this->addDefaultButtons('Save');
    }


    private function getHeaders($stmt)
    {
        $headers = array();
        $this->keycol = null;

        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $col = $stmt->getColumnMeta($i);


            if (in_array('primary_key', $col['flags']) && $this->keycol == null) {
                $this->keycol = $col['name'];
            }

            $headers[$col['name']] = $col;
        }

        return $headers;
    }

    public function process()
    {
        $fields = implodeQuoted($this->fields, '`');
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

        $stmt = db()->prepare($sql);
        $stmt->execute();

    }

    public function setupProcessedState($state): void 
    {
        if ($state->processed) {
            $redirectTo = $this->getElementValue('redirectTo');
            if (is_numeric($redirectTo)) {
                $state->redirect('?page=' . $redirectTo);
            } else {
                $state->redirect('?pageIdent=TABLE_ROW&amp;tc=' . $this->tc->table);
            }

        }

    }
}

?>
