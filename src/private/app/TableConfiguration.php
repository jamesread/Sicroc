<?php

namespace Sicroc;

use libAllure\ElementSelect;
use libAllure\ElementCheckbox;
use libAllure\ElementInput;
use libAllure\ElementHidden;
use libAllure\ElementFile;
use libAllure\ElementNumeric;
use libAllure\ElementDate;
use libAllure\QueryBuilder;
use libAllure\DatabaseStatement;
use libAllure\DatabaseFactory;

class TableConfiguration
{
    public readonly int $id;
    public readonly ?string $table;
    public readonly ?string $database;
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
    public ?string $keycol; // Is only available after we select some rows

    public ?string $lastQuery;

    private array $rows;
    private array $foreignKeys;

    public array $conditionalFormatting;

    public ?string $error = null;

    private ?DatabaseStatement $stmt;

    public bool $loaded = false;

    private QueryBuilder $qb;

    public function __construct(int $tcId, int $singleRowId = null, $loadDefault = true)
    {
        $this->id = $tcId;

        if ($singleRowId) {
            $this->singleRowId = $singleRowId;
        } else {
            $this->singleRowId = null;
        }

        $sql = 'SELECT `table`, `database`, orderColumn, orderAsc, createPhrase, createPageDelegate, listPhrase, editPhrase, editPageDelegate, showId, showTypes FROM table_configurations WHERE id = :id';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $fields = $stmt->fetchRow(\PDO::FETCH_OBJ);

        if ($fields == false) {
            throw new \Exception("Cannot find table configuration {$tcId} in the database.");
        }

        $this->table = $fields->table;
        $this->database = $fields->database;
        $this->showId = ($fields->showId == true);
        $this->showTypes = ($fields->showTypes == true);
        $this->order = ($fields->orderColumn ? $fields->orderColumn : 'id');
        $this->orderDirection = ($fields->orderAsc ? 'ASC' : 'DESC');
        $this->createPhrase = ($fields->createPhrase ? $fields->createPhrase : 'Insert');
        $this->createPageDelegate = $fields->createPageDelegate;
        $this->listPhrase = ($fields->listPhrase ? $fields->listPhrase : 'List');
        $this->editPhrase = ($fields->editPhrase ? $fields->editPhrase : 'Edit');
        $this->editPageDelegate = $fields->editPageDelegate;

        $this->foreignKeys = $this->getForeignKeys();
        $this->conditionalFormatting = $this->getConditionalFormatting();

        $this->loaded = false;

        if ($loadDefault) {
            $this->loadTable();
        }
    }

    public function loadTable(): void
    {
        $this->rows = $this->getRowData();
        $this->applyConditionalFormatting();

        $this->headers = $this->getHeadersFromRowData();

        $this->addForeignKeyDescriptions();

        $this->loaded = true;
    }

    private function getConditionalFormatting(): array
    {
        $sql = 'SELECT cf.cell_style, cf.field, cf.operator, cf.cell_value, cf.display_as FROM table_conditional_formatting cf WHERE tc = :id ORDER BY priority_order ASC';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);

        return $stmt->fetchAll();
    }

    private function addForeignKeyDescriptions(): void
    {
        foreach ($this->foreignKeys as $fkey) {
            for ($i = 0; $i < sizeof($this->rows); $i++) {
                $row = $this->rows[$i];

                $realCol = $fkey['foreignField'];

                $this->rows[$i][$fkey['sourceField'] . '_fk_description'] = $this->rows[$i][$fkey['sourceField'] . '_fk_description'];

                unset($this->headers[$fkey['sourceField'] . '_fk_description']);
            }
        }
    }

    public function getForeignKeys(): array
    {
        // FIXME Check database
        $sql = 'SELECT * FROM table_fk_metadata WHERE sourceTable = :sourceTable';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute([
            ':sourceTable' => $this->table,
        ]);

        $ret = array();

        foreach ($stmt->fetchAll() as $fkey) {
            $ret[$fkey['sourceField']] = $fkey;
        }

        return $ret;
    }

    public function getQbForRowData(): \libAllure\QueryBuilder
    {
        if (!$this->table) {
            $this->error = 'Table is not set.';
            return 'SELECT version()';
        }

        $qb = new \libAllure\QueryBuilder();

        $qb->from($this->table, null, $this->database);
        $qb->fields('*')->groupBy('id');

        if (isset($this->singleRowId)) {
            $qb->whereEqualsValue('id', $this->singleRowId);
        }

        foreach ($this->foreignKeys as $fkey) {
            $qb->join($fkey['foreignTable'], null, $fkey['foreignDatabase'])->onFromFieldsEq($fkey['sourceField'], $fkey['foreignField']);
            $qb->fields([$fkey['foreignDescription'], $fkey['sourceField'] . '_fk_description']);
        }

        if (!empty($this->order)) {
            $qb->orderBy($this->order . ' ' . $this->orderDirection);
        }

        $this->qb = $qb;

        return $qb;
    }

    public function setRowDataQb(QueryBuilder $qb): void
    {
        $this->qb = $qb;
    }

    private function getRowData(): array
    {
        if (!isset($this->qb)) {
            $this->qb = $this->getQbForRowData();
        }

        $sql = $this->qb->build();
        $this->lastQuery = $sql;

        $sql = $this->qb->build();
        $this->lastQuery = $sql;

        try {
            $this->stmt = \libAllure\DatabaseFactory::getInstance()->prepare($sql);
            $this->stmt->execute();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return array();
        }

        return $this->stmt->fetchAll();
    }

    private function applyConditionalFormatting(): void
    {
        foreach ($this->rows as $index => $cells) {
            $this->rows[$index]['meta'] = [
                'cell_style' => null,
                'cell_style_field' => null,
            ];

            foreach ($this->conditionalFormatting as $rule) {
                $this->rows[$index]['meta'] = array_merge(
                    $this->rows[$index]['meta'],
                    $this->applyConditionalFormattingRule($index, $cells, $rule)
                );
            }
        }
    }

    private function applyConditionalFormattingRule(string $key, array $cells, array $rule): array
    {
        $ret = [
        ];

        $value = null;

        if (isset($this->rows[$key][$rule['field']])) {
            $value = $this->rows[$key][$rule['field']];
        }

        if ($value != null) {
            if ($this->doesConditionalFormattingRuleApplyToRow($rule, $value)) {
                $ret['cell_style'] = $rule['cell_style'];
                $ret['cell_style_field'] = $rule['field'];

                switch ($rule['display_as']) {
                    case 'hyperlink':
                        $this->rows[$key][$rule['field']] = '<a href = "' . $value . '">' . $value . '</a>';
                }
            }
        }

        return $ret;
    }

    private function doesConditionalFormattingRuleApplyToRow(array $rule, string $value): bool
    {
        switch ($rule['operator']) {
            case 'contains':
                if (stripos($value, $rule['cell_value']) !== false) {
                    return true;
                }

                break;
            case 'equals':
                if ($value == $rule['cell_value']) {
                    return true;
                }

                break;
            case 'always':
                return true;
        }

        return false;
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

    public function getHeadersOfType(): array
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

    private function getHeadersFromRowData(): array
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

    public function getRows(): array
    {
        if (!$this->loaded) {
            $this->loadTable();
        }

        return $this->rows;
    }

    public function getElementForColumn(array $header): ?\libAllure\Element
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

        if ($header['name'] == 'picture') {
            $header['native_type'] = 'FILENAME';
        }

        $el = null;

        switch ($header['native_type']) {
            case 'LONG':
            case 'FLOAT':
                $el = new ElementNumeric($header['name'], $header['name'], $val, $header['native_type']);
                $el->setMinMaxLengths(0, 64);
                break;
            case 'DATE':
                $el = new ElementInput($header['name'], $header['name'], $val, $header['native_type']);
                $el->type = 'date';
                break;
            case 'DATETIME':
                $el = new ElementInput($header['name'], $header['name'], $val, $header['native_type']);
                $el->type = 'datetime-local';
                break;
            case 'FILENAME':
                $el = new ElementFile($header['name'], $header['name'], null);
                $el->tempDir = '/var/www/html/sicroc_uploads_temp/';
                $el->destinationDir = '/var/www/html/sicroc_uploads/';
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

                $qb = new QueryBuilder();
                $qb->from($fk['foreignTable'], null, $fk['foreignDatabase'])->fields([$fk['foreignField'], 'fkey'], [$fk['foreignDescription'], 'description']);

                //$sql = 'SELECT ' . $fk['foreignField'] . ' AS fkey, ' . $fk['foreignDescription'] . ' AS description FROM ' . $fk['foreignTable'] . ' ORDER BY description';
                $stmt = DatabaseFactory::getInstance()->prepare($qb->build());
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

        if (!\libAllure\Session::getUser()->getData('showDatatypes')) {
            $el->description = null;
        }

        return $el;
    }
}
