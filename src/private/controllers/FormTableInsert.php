<?php

use \libAllure\Form;
use \libAllure\ElementInput;

class FormTableInsert extends Form {
	public function __construct($controller) {
		parent::__construct('formTableInsert', 'Insert into table');

		$tbl = $controller->getArgumentValue('table');
		$tbl = san()->filterString('table');

		try {
			$sql = 'SELECT * FROM ' . $tbl . ' LIMIT 1';
			$stmt = db()->prepare($sql);
			$stmt->execute();
		} catch (Exception $e) {
			throw new Exception("Can't get initial table info.");
		}

		$fields = array();

		foreach ($this->getHeaders($stmt) as $header) {
			$fields[] = $header['name'];

			switch ($header['native_type']) {
				case 'FLOAT':
					$this->addElement(new ElementInput($header['name'], $header['name'], '0.0'));
					$this->getElement($header['name'])->setMinMaxLengths(0, 64);
					break;
				case 'VAR_STRING':
					$this->addElement(new ElementInput($header['name'], $header['name']));
					break;
				default: 
					$this->addElementReadOnly($header['name'] . ' (' . $header['native_type'] . ')' , '', $header['name']);	
			}
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

		$sql = 'INSERT INTO ' . $this->getElementValue('table') . ' (' . $fields . ') VALUES (' . implodeQuoted($values) . ') '; 
		$stmt = db()->prepare($sql);
		$stmt->execute();
	}
}

?>
