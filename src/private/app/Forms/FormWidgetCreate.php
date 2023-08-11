<?php

namespace Sicroc\Forms;

use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\Form;
use libAllure\DatabaseFactory;

class FormWidgetCreate extends Form implements \Sicroc\BaseForm
{
    private string $redirectUrl;

    public function __construct()
    {
        $this->addElement($this->getElementType());
        $this->addElement(new ElementInput('title', 'Title'));
        $this->addDefaultButtons('Instanciate');
    }

    private function getElementType()
    {
        $el = new ElementSelect('type', 'Type');

        foreach ($this->getAvailableTypes() as $type) {
            $el->addOption($type['viewableController'], $type['id']);
        }

        return $el;
    }

    private function getAvailableTypes()
    {
        $sql = 'SELECT wt.id, wt.viewableController FROM widget_types wt';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function process()
    {
        $sql = 'INSERT INTO widget_instances (type, title) VALUES (:type, :title) ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $this->bindElementToStatement($stmt, 'type');
        $this->bindElementToStatement($stmt, 'title');
        $stmt->execute();

        $this->redirectUrl = '?pageIdent=WIDGET_INSTANCE_UPDATE&widgetToUpdate=' . DatabaseFactory::getInstance()->lastInsertId();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->redirect($this->redirectUrl);
        }
    }
}