<?php

require_once CONTROLLERS_DIR . 'Table.php';

class FormTableEditRow extends \libAllure\Form {
	public function __construct() {
		parent::__construct('editRow', 'Edit Row');

		$this->addElementReadOnly('Table', san()->filterString('table'), 'table');

		$table = san()->filterString('table');

		$sql = 'SELECT * FROM ' . san()->filterString('table') . ' WHERE id = ' . san()->filterUint('primaryKey') . ' LIMIT 1';
		$stmt = db()->prepare($sql);
		$stmt->execute();

		$row = $stmt->fetchRow();

		$fields = array();

		$foreignKeys = Table::getForeignKeys($table);

		foreach ($this->getHeaders($stmt) as $key => $header) {
			$fields[] = $header['name'];
	
			Table::handleHeaderElement($this, $header, $foreignKeys, $row); 
		}

		$this->addElementHidden('redirectTo', san()->filterString('redirectTo'));

		$this->fields = $fields;

		$this->addDefaultButtons('Save');
	}


	private function getHeaders($stmt) {
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

	public function process() {
		$fields = implodeQuoted($this->fields, '`');
		$values = array();
		unset($this->fields[0]);

		$sql = 'UPDATE ' . $this->getElementValue('table') . ' SET ';

		foreach ($this->fields as $field) {
			$val = $this->getElementValue($field);

			if (empty($val)) {
				$val = ' null';
			} else {
				$val = ' "' . $val .  '"';
			}

			$sql .= '`' . $field . '` = ' . $val . ', ';

		}

		$sql .= $this->keycol . ' = ' . $this->keycol;
		$sql .= ' WHERE ' . $this->keycol . ' = ' . $this->getElementValue($this->keycol);

		$stmt = db()->prepare($sql);
		$stmt->execute();

		$redirectTo = $this->getElementValue('redirectTo');

		if (is_numeric($redirectTo)) {
			$this->redirectUrl = '?page=' . $redirectTo;
		} else {
			$this->redirectUrl = '?pageIdent=TABLE_ROW&amp;table=' . $this->getElementValue('table') . '&amp;primaryKey=' . $this->getElementValue($this->keycol);
		}
	}
}

?>
