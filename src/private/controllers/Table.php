<?php

use \libAllure\DatabaseFactory;
use \libAllure\ElementSelect;

class Table extends ViewableController {
	public $page;

	public function __construct($principle = null, $singleRowId = null) {
		parent::__construct();
		global $db;

		$this->singleRowId = $singleRowId;

		$this->principle = $principle;

		$this->rows = $this->getRowData();
		$this->headers = $this->getHeaders();
		$this->rows = $this->mangleForeignData();
	}

	private function mangleForeignData() {
		for ($i = 0; $i < sizeof($this->rows); $i++) {
			$row = $this->rows[$i];
			$cols = array_keys($row);

			for ($j = 0; $j < sizeof($row); $j++) {
				$col = $cols[$j];
				$val = $row[$col];


				if (strpos($col, '_fk') !== false) {
					$realCol = str_replace('_fk', '', $col);

					if (!empty($this->rows[$i][$col])) {
						$ftable = $this->headers[$col]['table'];
						$this->rows[$i][$realCol] .= ' (<a href = "?pageIdent=TABLE_ROW&amp;primaryKey=' . $this->rows[$i][$realCol] . '&amp;table=' . $ftable . '">' . $this->rows[$i][$col] . '</a>)';
					}

					unset($this->rows[$i][$col]);
				}
			}
		}

		return $this->rows;
	}

	public static function getForeignKeys($sourceTable) {
		$sql = 'SELECT * FROM table_fk_metadata WHERE sourceTable = :sourceTable';
		$stmt = db()->prepare($sql);
		$stmt->bindValue(':sourceTable', $sourceTable);
		$stmt->execute();

		$foreignKeys = $stmt->fetchAll();
		$ret = array();

		foreach ($foreignKeys as $key) {
			$ret[$key['sourceField']] = $key;
		}

		return $ret;
	}

	public function getArguments() {
		$args = array();
		$args[] = array('type' => 'varchar', 'name' => 'table', 'default' => '', 'description' => 'The database table name');

		return $args;
	}

	private function getRowData() {
		if ($this->principle == null) {
			$this->keycol = null;
			$this->stmt = null;
			return array();
		};

		$foreignKeys = self::getForeignKeys($this->principle);

		$sql = 'SELECT ' . $this->principle . '.*';

		$ftables = array();
		if (count($foreignKeys) > 0) {
			$sql .= ', ';

			foreach ($foreignKeys as $key) {
				$sql .= $key['foreignTable'] . '.' . $key['foreignDescription'] . ' AS ' . $key['sourceField'] . '_fk,';
				$ftables[] = $key['foreignTable'];
			}
			$sql[strlen($sql) - 1] = ' ';
		}

		$sql .=' FROM `' . $this->principle . '`'; 

		if (count($ftables) > 0) {
			foreach ($foreignKeys as $fk) {
				$sql .= ' LEFT JOIN ' . $fk['foreignTable'] . ' ON ' . $fk['foreignTable'] . '.' . $fk['foreignField'] . ' = ' . $fk['sourceField'];
			}
		}

		if (isset($this->singleRowId)) {
			$sql .= ' WHERE '. $this->principle .'.id = ' . $this->singleRowId . ' ';
		}

		$sql .= ' GROUP BY ' . $this->principle . '.id';

		$this->stmt = DatabaseFactory::getInstance()->prepare($sql);
		$this->stmt->execute();

		return $this->stmt->fetchAll();
	}

	public function getHeadersOfType() {
		$searchTypes = func_get_args();
		$ret = array();

		foreach ($this->headers as $header) {
			if (in_array($header['native_type'], $searchTypes)) {
				$ret[] = $header;
			}
		}

		return $ret;
	}

	private function getHeaders() {
		if ($this->stmt == null) {
			return array();
		}

		$headers = array();
		$this->keycol = null;

		for ($i = 0; $i < $this->stmt->columnCount(); $i++) {
			$col = $this->stmt->getColumnMeta($i);

			if (($col['name'] == 'id' || in_array('primary_key', $col['flags'])) && $this->keycol == null) {
				$this->keycol = $col['name'];
			}

			if (!isset($col['native_type'])) {
				$col['native_type'] = 'BOOLEAN';
			}

			$headers[$col['name']] = $col;
		}

		return $headers;
	}

	private function getRows() {
		if (false && $this->keycol != null) {
			foreach ($this->rows as &$row) {
					$row[$this->keycol] = '<a href = "">' . $row[$this->keycol]. '</a>';
			}
		}

		return $this->rows;
	}

	public function display() {
		return $this->index();
	}

	public function index() {
		global $tpl;

		$this->navigation->add('?pageIdent=TABLE_INSERT&amp;table=' . $this->principle, 'Insert');
		$this->navigation->add('dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;table=' . $this->principle, 'Structure...');

		foreach ($this->headers as $col => $header) {
			if (strpos($col, '_fk') !== false) {
				unset($this->headers[$col]);
			}
		}

	}

	public function render() {
		global $tpl;
		$tpl->assign('headers', $this->headers);
		$tpl->assign('rows', $this->getRows());
		$tpl->assign('table', array('name' => $this->principle, 'primaryKey' => $this->keycol));

		$tpl->display('table.tpl');
	}

	public function getArgumentElement($name, $default = 0) {
		switch ($name) {
		case 'table':
			$sql = 'SHOW TABLES';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->execute();

			$el = new ElementSelect($name, $name);

			foreach ($stmt->fetchAll() as $row) {
				$el->addOption($row['Tables_in_Sicroc']);
			}

			$el->setValue($default);
			
			return $el;
		default:
			return parent::getArgumentElement($name, $default);
		}
	}
}

?>
