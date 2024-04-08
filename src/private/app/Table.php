<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\Element;
use libAllure\Shortcuts as LA;

class Table extends Widget
{
    private ?TableConfiguration $tc = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function getArguments(): array
    {
        $args = array();
        $args[] = array('type' => 'int', 'name' => 'table_configuration', 'default' => 0, 'description' => 'Table Configuration');

        return $args;
    }

    public function widgetSetupCompleted(): void
    {
        $tc = $this->getArgumentValue('table_configuration');

        if ($tc == null) {
            $tc = LA::san()->filterUint('tc');
        }

        if ($tc != null) {
            $this->tc = new TableConfiguration($tc);

            if (!$this->tc->loaded) {
                $this->tc = null;
                return;
            }

            if ($this->tc->createPageDelegate == null) {
                $this->navigation->add('?pageIdent=TABLE_INSERT&amp;tc=' . $this->tc->id, $this->tc->createPhrase);
            } else {
                $this->navigation->add('?page=' . $this->tc->createPageDelegate, $this->tc->createPhrase);
            }
            $this->navigation->addSeparator();
            $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;tc=' . $this->tc->id, 'Table Structure');
            $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_ROW_EDIT&amp;tc=1&amp;primaryKey=' . $this->tc->id, 'Table Configuration');
            $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_CONDITIONAL_FORMATTING&amp;tc=' . $this->tc->id, 'Conditional Formatting');
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

        if ($this->page != null) {
            $_SESSION['lastTcViewPage'] = $this->page->getId();
        }
    }

    public function render(): void
    {
        if ($this->tc == null) {
            $this->simpleErrorMessage('TableConfiguration has not been set');
        } elseif ($this->tc->error != null) {
            $this->simpleErrorMessage($this->tc->error);
        } else {
            $this->tpl->assign('headers', $this->tc->headers);
            $this->tpl->assign('rows', $this->tc->getRows());
            $this->tpl->assign('tc', $this->tc);
            $this->tpl->assign('showTypes', $this->tc->showTypes);
            $this->tpl->assign('primaryKey', $this->tc->keycol);
            $this->tpl->assign('table', [
                'name' => $this->tc->table,
                'db' => $this->tc->database,
            ]);

            $this->tpl->display('table.tpl');
        }
    }
}
