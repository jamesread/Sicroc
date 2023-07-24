<?php

namespace Sicroc;

class TableRelatedRows extends Widget
{
    public function render()
    {
        $widget = $this->page->getWidgetByType('TableRow');

        if ($widget == null) {
            throw new Exception("Need a TableRow widget on the same page for this widget to function.");
        }

        $table = $widget['inst']->table;

        $sql = 'SELECT m.foreignTable, m.foreignField, m.sourceField FROM table_fk_metadata m WHERE m.sourceTable = :sourceTable ';

        $stmt = stmt($sql);
        $stmt->bindValue(':sourceTable', $table);
        $stmt->execute();

        global $tpl;

        foreach ($stmt->fetchAll() as $relation) {
            $fid = $widget['inst']->row[$relation['sourceField']];

            $table = new Table($relation['foreignTable'], $fid);

            echo $relation['foreignTable'];

            $table->render();
            echo '<br />';
        }
    }
}
