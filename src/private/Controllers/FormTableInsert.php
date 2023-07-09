<?php

use \libAllure\Form;

class FormTableInsert extends Form {
	public function __construct($controller) {
		parent::__construct('formTableInsert', 'Insert into table');

		$table = san()->filterString('table');

		try {
			$sql = 'SELECT * FROM ' . $table . ' LIMIT 1';
			$stmt = db()->prepare($sql);
			$stmt->execute();
		} catch (Exception $e) {
			throw new Exception("Can't even select 1 row from table:" . $table);
		}

		$fields = array();

		$foreignKeys = Table::getForeignKeys($table);

		foreach ($this->getHeaders($stmt) as $header) {
			$fields[] = $header['name'];

			Table::handleHeaderElement($this, $header, $foreignKeys);
		}

		$this->fields = $fields;

		$this->addElementReadOnly('Table', san()->filterString('table'), 'table');
		$this->addDefaultButtons();
	}

	private function getHeaders($stmt) {
		$headers = array();
		$this->keycol = null;

		for ($i = 0; $i < $stmt->columnCount(); $i++) {
			$col = $stmt->getColumnMeta($i);

			if (in_array('primary_key', $col['flags']) && $this->keycol == null) {
				$this->keycol = $col['name'];
			}

			$headers[] = $col;
		}

		return $headers;
	}


	public function process() {
		$fields = implodeQuoted($this->fields, '`');
		$values = array();

		foreach ($this->fields as $field) {
			$values[] = $this->getElementValue($field);
		}

		$sql = 'INSERT INTO ' . $this->getElementValue('table') . ' (' . $fields . ') VALUES (' . implodeQuoted($values, '"', true) . ') '; 
		$stmt = db()->prepare($sql);
		$stmt->execute();
	}
}

?>
