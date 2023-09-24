<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\Sanitizer;
use libAllure\DatabaseFactory;
use libAllure\ElementSelect;
use Sicroc\ProcessedFormState;

class FormPageContentDelete extends Form implements \Sicroc\BaseForm
{
    private array $widgets;

    public function __construct()
    {
        parent::__construct('formPageContentDelete', 'Delete page widget');

        $pageId = Sanitizer::getInstance()->filterUint('pageToEdit');

        $this->addElementHidden('pageToEdit', $pageId);
        $this->addElement($this->getElementWidgetSelect($pageId));
        $this->addDefaultButtons('Delete widget');
    }

    public function setupProcessedState(ProcessedFormState $state): void
    {
        if (empty($this->widgets)) {
            $state->preventRender('The page is empty.', 'ok');
        }
    }

    private function getElementWidgetSelect(int $id): ElementSelect
    {
        $sql = 'SELECT c.id, w.title FROM page_content c JOIN widget_instances w ON c.widget = w.id WHERE c.page = :pageId';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':pageId', $id);
        $stmt->execute();

        $el = new ElementSelect('widget', 'Widget');
        $el->setSize(5);

        $this->widgets = $stmt->fetchAll();

        foreach ($this->widgets as $itemWidget) {
            $caption = $itemWidget['title'];

            $el->addOption($caption, $itemWidget['id']);
        }

        return $el;
    }

    public function process(): void
    {
        $sql = 'DELETE FROM page_content WHERE id = :contentId';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':contentId', $this->getElementValue('widget'));
        $stmt->execute();
    }
}
