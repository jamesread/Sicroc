<?php

namespace Sicroc;

use libAllure\ElementSelect;
use libAllure\ElementCheckbox;
use libAllure\ElementInput;
use libAllure\ElementHidden;
use libAllure\ElementNumeric;
use libAllure\ElementDate;

use function libAllure\util\stmt;
use function libAllure\util\db;

class TableConfiguration
{
    public readonly int $id;
    public readonly ?string $table;
    public readonly ?string $database;
    public readonly ?string $keycol;
    public readonly ?int $singleRowId;

    public readonly ?string $editPhrase;
    public readonly ?int $editPageDelegate;

    public readonly ?string $createPhrase;
    public readonly ?int $createPageDelegate;

    public readonly ?string $listPhrase;

    public readonly string $order;
    public readonly string $orderDirection;

    public readonly bool $showId;
    public readonly bool $showTypes;

    public ?array $headers;

    private array $rows;
    private array $foreignKeys;

    public ?string $error = null;

    private $stmt;

    public function __construct($tcId, int $singleRowId = null)
    {
        $this->id = $tcId;

        if ($singleRowId) {
            $this->singleRowId = $singleRowId;
        }

        $this->load();
    }

    public function load()
    {
        $sql = 'SELECT `table`, `database`, orderColumn, orderAsc, createPhrase, createPageDelegate, listPhrase, editPhrase, editPageDelegate, showId, showTypes FROM table_configurations WHERE id = :id';
        $stmt = stmt($sql);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $fields = $stmt->fetchRow(\PDO::FETCH_OBJ);

        if ($fields != false) {
            $this->table = $fields->table;
            $this->database = $fields->database;
            $this->showId = ($fields->showId == true);
            $this->showTypes = ($fields->showTypes == true);
            $this->order = ($fields->orderColumn ? $fields->orderColumn : 'id');
            $this->orderDirection = ($fields->orderAsc ? 'ASC' : 'DESC');
            $this->createPhrase = ($fields->createPhrase ? $fields->createPhrase : 'Insert');
            $this->createPageDelegate = $fields->createPageDelegate;
            $this->listPhrase = ($fields->createPhrase ? $fields->listPhrase : 'List');
            $this->editPhrase = ($fields->editPhrase ? $fields->editPhrase : 'Edit');
            $this->editPageDelegate = $fields->editPageDelegate;
            $this->loadTable();
        }
    }

    public function loadTable()
    {
        $this->foreignKeys = $this->getForeignKeys();
        $this->rows = $this->getRowData();
        $this->headers = $this->getHeadersFromRowData();
        $this->addForeignKeyDescriptions();
    }


    private function addForeignKeyDescriptions()
    {
        foreach ($this->foreignKeys as $fkey) {

            for ($i = 0; $i < sizeof($this->rows); $i++) {
                $row = $this->rows[$i];

                $realCol = $fkey['foreignField'];

                $this->rows[$i][$fkey['sourceField']] .= ' (' . $this->rows[$i][$fkey['sourceField'] . '_fk_description'] . ')';

                unset($this->rows[$i][$fkey['sourceField'] . '_fk_description']);
                unset($this->headers[$fkey['sourceField'] . '_fk_description']);
                unset($this->headers['index_page_fk_description']);
            }
        }
    }

    public function getForeignKeys(): array
    {
        // FIXME Check database
        $sql = 'SELECT * FROM table_fk_metadata WHERE sourceTable = :sourceTable';
        $stmt = db()->prepare($sql);
        $stmt->execute([
            ':sourceTable' => $this->table,
        ]);

        $ret = array();

        foreach ($stmt->fetchAll() as $fkey) {
            $ret[$fkey['sourceField']] = $fkey;
        }

        return $ret;
    }

    private function queryRowDataQb(): string
    {
        if (!$this->table) {
            $this->error = 'Table is not set.';
            return 'SELECT version()';
        }

        $qb = new \libAllure\QueryBuilder();

        $qb->from($this->table, null, $this->database);
        $qb->fields('*');

        foreach ($this->foreignKeys as $fkey) {
            $qb->join($fkey['foreignTable'], ' ')->onEq($fkey['foreignTable'] . '.' . $fkey['foreignField'], $fkey['sourceField']);
            $qb->fields([$fkey['foreignTable'] . '.' . $fkey['foreignDescription'], $fkey['sourceField'] . '_fk_description']);
        }

        if (isset($this->singleRowId)) {
            $qb->whereEqualsValue('id', $this->singleRowId);
        }

        $qb->groupBy('id');

        if (!empty($this->order)) {
            $qb->orderBy($this->order . ' ' . $this->orderDirection);
        }

        return $qb->build();
    }

    private function getRowData()
    {
        $sqlQb = $this->queryRowDataQb();
        $sqlHacky = $this->queryRowDataHacky();

//        \libAllure\util\vde($sqlQb, $sqlHacky);

        $sql = $sqlQb;

        try {
            $this->stmt = \libAllure\DatabaseFactory::getInstance()->prepare($sql);
            $this->stmt->execute();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return array();
        }

        return $this->stmt->fetchAll();
    }

    private function queryRowDataHacky(): string
    {
        $table = $this->table;

        if ($table == null) {
            $this->keycol = null;
            $this->stmt = null;
            return 'SELECT version()';
        };

        $sql = 'SELECT ' . $table . '.*';

        $ftables = array();
        if (!empty($this->foreignKeys)) {
            $sql .= ', ';

            foreach ($this->foreignKeys as $key) {
                $sql .= $key['foreignTable'] . '.' . $key['foreignDescription'] . ' AS ' . $key['sourceField'] . '_fk,';
                $ftables[] = $key['foreignTable'];
            }
            $sql[strlen($sql) - 1] = ' ';
        }

        $sql .= ' FROM `' . $this->database . '`.`' . $table . '`';

        if (count($ftables) > 0) {
            foreach ($this->foreignKeys as $fk) {
                $sql .= ' LEFT JOIN ' . $fk['foreignTable'] . ' ON ' . $fk['foreignTable'] . '.' . $fk['foreignField'] . ' = ' . $fk['sourceField'];
            }
        }

        if (isset($this->singleRowId)) {
            $sql .= ' WHERE ' . $table . '.id = ' . $this->singleRowId . ' ';
        }

        $sql .= ' GROUP BY ' . $table . '.id';

        if (!empty($this->order)) {
            $sql .= ' ORDER BY ' . $this->order . ' DESC';
        }

        return $sql;
    }

    public function getHeadersOfType()
    {
        $searchTypes = func_get_args();
        $ret = array();

        foreach ($this->headers as $header) {
            if (in_array($header['native_type'], $searchTypes)) {
                $ret[] = $header;
            }
        }

        return $ret;
    }

    private function getHeadersFromRowData()
    {
        if ($this->stmt == null) {
            return array();
        }

        if (!empty($this->headers)) {
            return $this->headers;
        }

        $headers = array();

        for ($i = 0; $i < $this->stmt->columnCount(); $i++) {
            $col = $this->stmt->getColumnMeta($i);

            if (($col['name'] == 'id' || in_array('primary_key', $col['flags']))) {
                $this->keycol = $col['name'];
            }

            if (!isset($col['native_type'])) {
                $col['native_type'] = 'BOOLEAN';
            }

            $headers[$col['name']] = $col;
        }

        return $headers;
    }

    public function getRows()
    {
        if (false && $this->keycol != null) {
            foreach ($this->rows as &$row) {
                $row[$this->keycol] = '<a href = "">' . $row[$this->keycol] . '</a>';
            }
        }

        return $this->rows;
    }

    public function getElementForColumn($header): ?\libAllure\Element
    {
        if (isset($this->singleRowId)) {
            $row = current($this->rows);
            $val = isset($row[$header['name']]) ? $row[$header['name']] : null;
        } else {
            $val = null;
        }

        if (!isset($header['native_type'])) {
            $header['native_type'] = 'BOOLEAN';
        }

        if (in_array($header['name'], array_keys($this->foreignKeys))) {
            $header['native_type'] = 'FK';
        }

        $isRequired = in_array('not_null', $header['flags']);

        if ($header['name'] == 'id') {
            return null;
        }

        $el = null;

        switch ($header['native_type']) {
            case 'LONG':
            case 'FLOAT':
                $el = new ElementNumeric($header['name'], $header['name'], $val, $header['native_type']);
                $el->setMinMaxLengths(0, 64);
                break;
            case 'DATETIME':
                $el = new ElementDate($header['name'], $header['name'], $val, $header['native_type']);
                break;
            case 'VAR_STRING':
                $el = new ElementInput($header['name'], $header['name'], $val, $header['native_type']);
                $el->setMinMaxLengths(0, 64);
                break;
            case 'TINY':
            case 'TINYINT':
            case 'BOOLEAN':
                $el = new ElementCheckbox($header['name'], $header['name'], $val == 1);

                $isRequired = false;
                break;
            case 'FK':
                $key = $header['name'];
                $fk = $this->foreignKeys[$header['name']];

                $sql = 'SELECT ' . $fk['foreignField'] . ' AS fkey, ' . $fk['foreignDescription'] . ' AS description FROM ' . $fk['foreignTable'] . ' ORDER BY description';
                $stmt = db()->prepare($sql);
                $stmt->execute();

                $el = new ElementSelect($key, $key);
                $el->addOption('--null--', '');

                foreach ($stmt->fetchAll() as $frow) {
                    $el->addOption($frow['description'], $frow['fkey']);
                }

                $el->setValue($val);

                break;
            default:
                $el = new ElementHidden($header['name'], $val, $header['name']);
        }

        $el->setRequired($isRequired);

        return $el;
    }
}
