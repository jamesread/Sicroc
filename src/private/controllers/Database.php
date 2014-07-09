<?php

class Database extends PDO {
	public function __construct() {
		parent::__construct('mysql:host=localhost;dbname=Sicroc', 'Sicroc', 'toomanysecrets');
	}

	public function query($sql) {
		//echo $sql;
		$result = parent::query($sql);

		$err = $this->errorInfo();
		//if ($err[0] != "") {
		//	throw new DatabaseException($err);
		//}	

		return new DatabaseResult($result);
	}

	public function escape($s) {
		return $s;
		return $this->quote($s);
	}
}

class DatabaseException extends Exception {
	public function __construct($m) {
		parent::__construct(print_r($m, true));
	}
}

class DatabaseResult {
	private $result;
	const FM_ORDERED = 2;
	const FM_ASSOC = 3;


	public function __construct($result) {
		$this->result = $result;
	}

	public function numRows() {
		return $this->result->rowCount();
	}

	public function fetchRow($mode = DatabaseResult::FM_ASSOC) {
		if ($mode == DatabaseResult::FM_ASSOC) {
			$row = $this->result->fetch(PDO::FETCH_ASSOC);
		} else {
			$row = $this->result->fetch();
		}

		return $row;
	}

	public function fetchAll() {
		$results = $this->result->fetchAll();

		return $results;
	}
}

$db = new Database();

?>
