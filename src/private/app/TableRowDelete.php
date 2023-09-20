<?php

namespace Sicroc;

use Sicroc\TableConfiguration;
use libAllure\Shortcuts as LA;

class TableRowDelete extends Widget
{
    public function render(): void
    {
        $tc = new TableConfiguration(LA::san()->filterUint('tc'));
        $id = LA::san()->filterUint('primaryKey');

        $sql = 'DELETE FROM ' . $tc->table . ' WHERE id = :id';
        $stmt = LA::db()->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);

        $this->tpl->assign('message', 'Row deleted.');
        $this->tpl->display('simple.tpl');
    }
}
