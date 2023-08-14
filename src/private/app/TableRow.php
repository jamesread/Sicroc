<?php

namespace Sicroc;

use Sicroc\TableConfiguration;
use libAllure\Shortcuts AS LA;

class TableRow extends Widget
{
    private ?array $rows = [];
    private ?int $id;
    private TableConfiguration $tc;

    public function widgetSetupCompleted()
    {
        $this->id = LA::san()->filterUint('primaryKey');
        $this->tc = new TableConfiguration(LA::san()->filterString('tc'), $this->id);

        $this->navigation->add('?pageIdent=TABLE_VIEW&amp;tc=' . $this->tc->id, $this->tc->listPhrase);
        $this->navigation->add('?pageIdent=TABLE_ROW_EDIT&amp;tc=' . $this->tc->id . '&amp;primaryKey=' . $this->id, 'Edit');
        $this->navigation->add('?pageIdent=TABLE_ROW_DELETE&amp;tc=' . $this->tc->id . '&amp;primaryKey=' . $this->id, 'Delete');

        $this->rows = $this->tc->getRows();
    }

    public function render()
    {
        if (empty($this->rows)) {
            $this->simpleMessage('Row not found', 'bad');
        } else {
            $this->tpl->assign('row', $this->rows[0]);
            $this->tpl->display('tableRow.tpl');
        }
    }
}
