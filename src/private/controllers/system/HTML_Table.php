<?php

class HTML_Table {
    private $data = array();

    public function addRow($items) {
		if (is_array($items) && sizeof(func_get_args()) == 1) {
        } else {
            $items = func_get_args();
        }

        $this->data[] = $items;
    }

    public function display() {
		$ret = '';
        $ret .= '<table>';

        foreach ($this->data as $row) {
            $ret .= '<tr>';

            foreach ($row as $cell) {
                $ret .= ($row == $this->data[0]) ? '<th>' . $cell . '</th>' : '<td>' . $cell . '</td>';
            }

           	$ret .= '</tr>';
        }

        $ret .= '</table>';
		$ret .= '<p>There are <strong>' . (sizeof($this->data) - 1) . '</strong> rows in this table.</p>';

		return $ret;
   }

   public function setData(PDOStatement $stmt) {
        global $db;

        $stmt->execute();

		$keyCol = null;
        $fields = array();
		// headers

        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $col = $stmt->getColumnMeta($i);
            $type = (isset($col['native_type'])) ? $col['native_type'] : 'UNKNOWN';

            if (in_array('primary_key', $col['flags']) && $keyCol == null) {
                $keycol = $col['name'];
            }

            $fields[] = $col['name'] . '<br /><span class = "subtle">' . $type . '</span>';
        }

        $this->addRow($fields);
        $table = 'events';

		// data rows
        foreach ($stmt->fetchAll(PDO::FETCH_NAMED) as $row) {
            $row[$keycol] = '<a href = "viewRow.php?table=' . $table . '&amp;keyCol=' . $keycol . '&amp;key=' . $row[$keycol] . '">' . $row[$keycol] . '</a>';

            $this->addRow($row);
        }
    }

}
?>
