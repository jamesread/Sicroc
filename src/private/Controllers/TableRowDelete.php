<?php

class TableRowDelete extends Widget {
    public function display() {
        $table = san()->filterString('table');
        $id = san()->filterUint('primaryKey');

        $sql = 'DELETE FROM ' . $table . ' WHERE id = ' . $id;

        $stmt = db()->prepare($sql);
        $stmt->execute();

        global $tpl;
        $tpl->assign('message', 'Row deleted.');
        $tpl->display('simple.tpl');

    }
}

?>
