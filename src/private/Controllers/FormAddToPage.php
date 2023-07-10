<?php

use \libAllure\Form;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\ElementSelect;

class FormAddToPage extends Form
{
    public function __construct()
    {
        parent::__construct('formAddToPage', 'Add widget'); 

        $this->page = $this->getPage();

        $this->addElementReadOnly('Page ID', Sanitizer::getInstance()->filterUint('pageToEdit'), 'pageToEdit');
        $this->addElement($this->getWidgetSelectionElement());
        $this->addDefaultButtons('Add widget');
    }

    private function getPage()
    {
        $sanitizer = new Sanitizer();

        $sql = 'SELECT p.id, p.title FROM pages p WHERE p.id = :page LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':page', $sanitizer->filterUint('pageToEdit'));
        $stmt->execute();

        return $stmt->fetchRow();
    }


    private function getWidgetSelectionElement()
    {
        $el = new ElementSelect('widget', 'Widget instance');

        $sql = 'SELECT w.id, t.viewableController AS type, w.title, w.method, w.principle FROM widget_instances w LEFT JOIN widget_types t ON w.type = t.id ORDER BY type, w.principle, w.method';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $itemWidget) {
            $el->addOption($itemWidget['type'] . ' - ' . $itemWidget['principle'] . '::' . $itemWidget['method'] . ' (' . $itemWidget['title'] . ')', $itemWidget['id']);
        }

        $el->description = '<a href = "?pageIdent=WIDGET_REGISTER">Register new...</a><br />';
        $el->description .= '<a href = "?pageIdent=WIDGET_CREATE">Instanciate...</a>';

        return $el;
    }

    public function process()
    {
        $sql = 'INSERT INTO page_content (page, widget) values (:page, :widget) ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':page', $this->getElementValue('pageToEdit'));
        $stmt->bindValue(':widget', $this->getElementValue('widget'));
        $stmt->execute();
    }
}

?>
