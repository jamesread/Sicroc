<?php

namespace Sicroc\Controllers;

use libAllure\DatabaseFactory;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\ElementCheckbox;
use libAllure\ElementDate;

use function libAllure\util\vde;
use function libAllure\util\stmt;

class Table extends Widget
{
    public $displayEdit = false;

    public function __construct($principle = null, $singleRowId = null)
    {
        parent::__construct();

        $this->singleRowId = $singleRowId;

        $this->tc = new TableConfiguration($principle);
    }

    public function getArguments()
    {
        $args = array();
        $args[] = array('type' => 'varchar', 'name' => 'order', 'default' => '', 'description' => 'Order by');
        $args[] = array('type' => 'boolean', 'name' => 'showid', 'default' => '0', 'description' => 'Show ID & Edit');
        $args[] = array('type' => 'boolean', 'name' => 'showtypes', 'default' => '0', 'description' => 'Show types');

        return $args;
    }

    public function display()
    {
        $this->navigation->add('?pageIdent=TABLE_INSERT&amp;db=' . $this->getArgumentValue('db') . '&amp;table=' . $this->getArgumentValue('table'), 'Insert');
        $this->navigation->addSeparator();
        $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;db=' . $this->getArgumentValue('db') . '&amp;table=' . $this->getArgumentValue('table'), 'Structure...');
        $this->navigation->addIf(LayoutManager::get()->getEditMode(), '?pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate=' . $this->widgetId, 'Update widget');

        foreach ($this->tc->headers as $col => $header) {
            if (strpos($col, '_fk') !== false) {
                unset($this->headers[$col]);
            }
        }
    }

    public function render()
    {
        $this->tpl->assign('tableError', null);
        $this->tpl->assign('headers', $this->tc->headers);
        $this->tpl->assign('rows', $this->tc->getRows());
        $this->tpl->assign('tc', array('tc' => $this->tc->getId()));
        $this->tpl->assign('showTypes', $this->getArgumentValue('showtypes'));

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
        case 'table':
            $el = new ElementSelect($name, $name);
            $el->addOption('---', null);

            $db = $this->getArgumentValue('db');

            if (!empty($db)) {
                $sql = 'SHOW TABLES IN ' . $db;

                $stmt = stmt($sql);
                $stmt->execute();


                foreach ($stmt->fetchAll() as $row) {
                    $el->addOption($row['Tables_in_' . $db]);
                }

                $el->setValue($default);
            }

            return $el;
        default:
            return parent::getArgumentElement($name, $type, $default);
        }
    }
}

?>
