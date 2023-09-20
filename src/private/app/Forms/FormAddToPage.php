<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\Sanitizer;
use libAllure\DatabaseFactory;
use libAllure\ElementSelect;

class FormAddToPage extends Form
{
    public function __construct()
    {
        parent::__construct('formAddToPage', 'Add widget');

        $this->addElementReadOnly('Page ID', Sanitizer::getInstance()->filterUint('pageToEdit'), 'pageToEdit');
        $this->addElement($this->getWidgetSelectionElement());
        $this->addDefaultButtons('Add widget');
    }

    private function getWidgetSelectionElement()
    {
        $el = new ElementSelect('widget', 'Widget instance');

        $sql = 'SELECT w.id, t.viewableController AS type, w.title, w.title FROM widget_instances w LEFT JOIN widget_types t ON w.type = t.id ORDER BY type, w.title';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $itemWidget) {
            $el->addOption($itemWidget['type'] . ' - ' . $itemWidget['title'], $itemWidget['id']);
        }

        $el->description = '<a href = "?pageIdent=WIDGET_CREATE" class = "button">Instanciate type...</a>';
        $el->description .= '<a href = "?pageIdent=WIDGET_REGISTER" class = "button">Register new type...</a>';

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

    public function setupProcessedState($state)
    {
        if ($state->processed) {
            $state->preventRender('Widget added.');
        }
    }
}
