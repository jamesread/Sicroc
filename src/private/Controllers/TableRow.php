<?php

class TableRow extends Widget
{
    public function display()
    {
        $this->id = san()->filterString('primaryKey');
        $this->table = san()->filterString('table');
        $this->navigation->add('?pageIdent=TABLE_ROW_EDIT&amp;table=' . $this->table . '&amp;primaryKey=' . $this->id, 'Edit');
        $this->navigation->add('?pageIdent=TABLE_ROW_DELETE&amp;table=' . $this->table . '&amp;primaryKey=' . $this->id, 'Delete');

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $this->id;
        $stmt = db()->prepare($sql);
        $stmt->execute();

        $this->row = $stmt->fetchRow();
    }

    public function render()
    {

        global $tpl;
        $tpl->assign('row', $this->row);
        $tpl->display('tableRow.tpl');
    }
}

?>
