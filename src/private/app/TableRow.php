<?php

namespace Sicroc;

use Sicroc\TableConfiguration;
use Sicroc\Utils;
use libAllure\Shortcuts as LA;

class TableRow extends Widget
{
    private array $rows = [];
    private ?int $id;
    private TableConfiguration $tc;

    public function widgetSetupCompleted(): void
    {
        $this->id = LA::san()->filterUint('primaryKey');
        $this->tc = new TableConfiguration(LA::san()->filterString('tc'), $this->id, false);
        $this->tc->loadTable(false);

        $this->navigation->add('?pageIdent=TABLE_VIEW&amp;tc=' . $this->tc->id, $this->tc->listPhrase);
        $this->navigation->add('?pageIdent=TABLE_ROW_EDIT&amp;tc=' . $this->tc->id . '&amp;primaryKey=' . $this->id, 'Edit');
        $this->navigation->add('?pageIdent=TABLE_ROW_DELETE&amp;tc=' . $this->tc->id . '&amp;primaryKey=' . $this->id, 'Delete');

        $this->rows = $this->tc->getRows();
    }

    public function render(): void
    {
        if (empty($this->rows)) {
            $message = 'Row not found.';

            if (Utils::getSiteSetting('TC_DEBUG_SQL')) {
                $message .= '<hr /> ' . $this->tc->lastQuery;
            }

            $this->simpleMessage($message, 'bad');
        } else {
            $this->tpl->assign('row', $this->rows[0]);
            $this->tpl->display('tableRow.tpl');
        }
    }
}
