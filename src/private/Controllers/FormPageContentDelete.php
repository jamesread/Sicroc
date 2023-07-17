<?php

use \libAllure\Form;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\ElementSelect;

class FormPageContentDelete extends Form
{
    public function __construct()
    {
        parent::__construct('formPageContentDelete', 'Delete page widget');

        $pageId = Sanitizer::getInstance()->filterUint('pageToEdit');

        $this->addElementHidden('pageId', $pageId);
        $this->addElement($this->getElementWidgetSelect($pageId));
        $this->addDefaultButtons('Delete widget');
    }

    private function getElementWidgetSelect($id)
    {
        $sql = 'SELECT c.id, w.principle, w.title, w.method FROM page_content c JOIN widget_instances w ON c.widget = w.id WHERE c.page = :pageId';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':pageId', $id);
        $stmt->execute();

        $el = new ElementSelect('widget', 'Widget');
        $el->setSize(5);

        foreach ($stmt->fetchAll() as $itemWidget) {
            $caption = $itemWidget['title'] . ' (' . $itemWidget['principle'] . '::' . $itemWidget['method'] . ')';

            $el->addOption($caption, $itemWidget['id']);
        }

        return $el;
    }

    public function process()
    {
        $sql = 'DELETE FROM page_content WHERE id = :contentId';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':contentId', $this->getElementValue('widget'));
        $stmt->execute();
    }
}

?>
