<?php

class TableRelatedRows extends ViewableController {
	public function display() {

	}

	public function render() {
		$widget = $this->page->getWidgetByType('TableRow');

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

?>
