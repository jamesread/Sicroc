<?php

use \libAllure\DatabaseFactory;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;
use \libAllure\ElementCheckbox;


class Table extends ViewableController {
	public $page;
	public $displayEdit = false;

	private $keycol = null;
	private $headers = array();
	private $rows = array();

	public function __construct($principle = null, $singleRowId = null) {
		parent::__construct();

		$this->singleRowId = $singleRowId;
	}

	public function widgetSetupCompleted() {
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
		$stmt = dbData()->prepare($sql);
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
		$table = $this->getArgumentValue('table');
		
		if ($table == null) {
			$this->keycol = null;
			$this->stmt = null;
			return array();
		};

		$foreignKeys = self::getForeignKeys($table);

		$sql = 'SELECT ' . $table . '.*';

		$ftables = array();
		if (count($foreignKeys) > 0) {
			$sql .= ', ';

			foreach ($foreignKeys as $key) {
				$sql .= $key['foreignTable'] . '.' . $key['foreignDescription'] . ' AS ' . $key['sourceField'] . '_fk,';
				$ftables[] = $key['foreignTable'];
			}
			$sql[strlen($sql) - 1] = ' ';
		}

		$sql .=' FROM `' . $table . '`'; 

		if (count($ftables) > 0) {
			foreach ($foreignKeys as $fk) {
				$sql .= ' LEFT JOIN ' . $fk['foreignTable'] . ' ON ' . $fk['foreignTable'] . '.' . $fk['foreignField'] . ' = ' . $fk['sourceField'];
			}
		}

		if (isset($this->singleRowId)) {
			$sql .= ' WHERE '. $table .'.id = ' . $this->singleRowId . ' ';
		}

		$sql .= ' GROUP BY ' . $table . '.id';

		$this->stmt = DatabaseFactory::getInstance('data')->prepare($sql);
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

		$this->navigation->add('?pageIdent=TABLE_INSERT&amp;table=' . $this->getArgumentValue('table'), 'Insert');
		$this->navigation->addSeparator();
		$this->navigation->add('dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;table=' . $this->getArgumentValue('table'), 'Structure...');
		$this->navigation->add('?pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate=' . $this->widgetId, 'Update widget');

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
		$tpl->assign('table', array('name' => $this->getArgumentValue('table'), 'primaryKey' => $this->keycol));

		$tpl->display('table.tpl');
	}

	public function getArgumentElement($name, $default = 0) {
		switch ($name) {
		case 'table':
			$sql = 'SHOW TABLES';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->execute();

			$el = new ElementSelect($name, $name);

			$el->addOption('---', null);

			foreach ($stmt->fetchAll() as $row) {
				$el->addOption($row['Tables_in_Sicroc']);
			}

			$el->setValue($default);
			
			return $el;
		default:
			return parent::getArgumentElement($name, $default);
		}
	}

	public static function handleHeaderElement($form, $header, $foreignKeys, $row = null) {
		if (isset($row[$header['name']])) {
			$val= $row[$header['name']];
		} else {
			$val = '';
		}

		if (!isset($header['native_type'])) {
			$header['native_type'] = 'BOOLEAN';
		}

		if (in_array($header['name'], array_keys($foreignKeys))) {
			$header['native_type'] = 'FK';
		}

		switch ($header['native_type']) {
			case 'LONG':
			case 'FLOAT':
				$form->addElement(new ElementInput($header['name'], $header['name'], $val, $header['native_type']));
				$form->getElement($header['name'])->setMinMaxLengths(0, 64);
				break;
			case 'VAR_STRING':
				$form->addElement(new ElementInput($header['name'], $header['name'], $row[$header['name']], $header['native_type']));
				break;
			case 'TINY':
			case 'TINYINT':
			case 'BOOLEAN':
				$form->addElement(new ElementCheckbox($header['name'], $header['name'], $val));
				break;
			case 'FK':
				$key = $header['name'];
				$fk = $foreignKeys[$header['name']];

				$sql = 'SELECT ' . $fk['foreignField'] . ' AS fkey, ' . $fk['foreignDescription'] . ' AS description FROM ' . $fk['foreignTable'];
				$stmt = db()->prepare($sql);
				$stmt->execute();

				$el = new ElementSelect($key, $key);
				$el->addOption('--null--', '');

				foreach ($stmt->fetchAll() as $frow) {
					$el->addOption($frow['description'], $frow['fkey']);
				}

				$el->setValue($val);

				$form->addElement($el);
				break;
			default: 
				$form->addElementReadOnly($header['name'] . ' (' . $header['native_type'] . ')' , $row[$header['name']], $header['name']);	
		}
	}
}

?>
