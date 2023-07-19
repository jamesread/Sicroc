<?php

namespace Sicroc\Controllers;

use libAllure\DatabaseFactory;
use libAllure\ElementSelect;
use libAllure\ElementCheckbox;

use function libAllure\util\vde;
use function libAllure\util\stmt;

class Table extends Widget
{
    public $displayEdit = false;
    private TableConfiguration|null $tc;

    public function __construct()
    {
        parent::__construct();
    }

    public function getArguments()
    {
        $args = array();
        $args[] = array('type' => 'int', 'name' => 'table_configuration', 'default' => 0, 'description' => 'Table Configuration');
        $args[] = array('type' => 'varchar', 'name' => 'order', 'default' => '', 'description' => 'Order by');
        $args[] = array('type' => 'boolean', 'name' => 'showid', 'default' => '0', 'description' => 'Show ID & Edit');
        $args[] = array('type' => 'boolean', 'name' => 'showtypes', 'default' => '0', 'description' => 'Show types');

        return $args;
    }

    public function widgetSetupCompleted()
    {
        $tc = $this->getArgumentValue('table_configuration');

        if ($tc != null) {
            $this->tc = new TableConfiguration($tc);
            $this->navigation->add('?pageIdent=TABLE_INSERT&amp;tc=' . $this->tc->id, 'Insert');
            $this->navigation->addSeparator();
            $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;tc=' . $this->tc->id, 'Structure...');
        } else {
            $this->tc = null;
        }

        /*
        hack, move to TableConfiguration
        if ($this->tc != null) {
            foreach ($this->tc->getHeaders() as $col => $header) {
                if (strpos($col, '_fk') !== false) {
                    unset($this->headers[$col]);
                }
            }
        }
         */

        $this->navigation->addIf(LayoutManager::get()->getEditMode(), '?pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate=' . $this->widgetId, 'Update widget');
    }

    public function render()
    {

        if ($this->tc == null) {
            $this->tpl->assign('tableError', 'TableConfiguration has not been set');
        } else {
            $this->tpl->assign('tableError', $this->tc->error);
            $this->tpl->assign('headers', $this->tc->getHeaders());
            $this->tpl->assign('rows', $this->tc->getRows());
            $this->tpl->assign('tc', $this->tc->id);
            $this->tpl->assign('showTypes', $this->getArgumentValue('showtypes'));
            $this->tpl->assign('primaryKey', $this->tc->keycol);
            $this->tpl->assign('table', [
                'name' => $this->tc->table,
                'db' => $this->tc->database,
            ]);
        }

        $this->tpl->display('table.tpl');
    }

    public function getArgumentElement(string $name, string $type, $default = 0)
    {
        switch ($type) {
        case 'boolean':
            $el = new ElementCheckbox($name, $name);

            return $el;
        }

        switch ($name) {
        case 'table_configuration':
            $el = new ElementSelect($name, $name);
            $el->addOption('---', null);

            $sql = 'SELECT tc.id, tc.database, tc.table FROM table_configurations tc ORDER BY tc.database, tc.table';

            $stmt = stmt($sql);
            $stmt->execute();

            foreach ($stmt->fetchAll() as $tc) {

                $el->addOption($tc['database'] . '.' . $tc['table'], $tc['id']);
            }

            $el->setValue($default);

            return $el;
        default:
            return parent::getArgumentElement($name, $type, $default);
        }
    }
}

?>
