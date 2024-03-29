<?php

namespace Sicroc\Forms;

use Sicroc\ProcessedFormState;
use Sicroc\Page;
use libAllure\Form;
use libAllure\ElementInput;
use libAllure\Sanitizer;
use libAllure\DatabaseFactory;
use libAllure\Shortcuts as LA;

class FormWidgetUpdate extends Form implements \Sicroc\BaseForm
{
    private array $widget;

    public function __construct()
    {
        parent::__construct('formWidgetUpdate', 'Widget update properties');

        $this->widget = $this->getWidget();

        $this->addSection('Essentials');
        $this->addElementReadOnly('ID', $this->widget['id']);
        $this->addElementReadOnly('Type', $this->widget['viewableController']);
        $this->addElementHidden('widgetToUpdate', $this->widget['id']);

        $this->widget = Page::resolveWidget($this->widget);

        $this->addSection('Arguments');
        if ($this->widget['inst']->hasArguments()) {
            $argValues = $this->widget['inst']->getArgumentValues();

            foreach ($this->widget['inst']->getArguments() as $arg) {
                $value = null;

                if (isset($argValues[$arg['name']])) {
                    $value = $argValues[$arg['name']];
                }

                $el = $this->widget['inst']->getArgumentElement($arg['name'], $arg['type'], $value);
                $el->description = $arg['description'];

                $this->addElement($el);
            }
        } else {
            $this->addElementReadOnly('Arguments:', 'This widget has 0 arguments');
        }

        $this->addSection('Metadata');
        $this->addElement(new ElementInput('title', 'Title', $this->widget['title']));

        $this->addDefaultButtons('Save widget');
    }

    private function getWidget(): array
    {
        $id = Sanitizer::getInstance()->filterUint('widgetToUpdate');
        $sql = 'SELECT wi.id, wi.title, wt.viewableController FROM widget_instances wi JOIN widget_types wt ON wi.type = wt.id WHERE wi.id = :id LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchRowNotNull();
    }

    public function process(): void
    {
        $sql = 'UPDATE widget_instances w SET w.title = :title WHERE w.id = :id ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->bindValue(':id', $this->widget['id']);
        $stmt->execute();

        foreach ($this->widget['inst']->getArguments() as $arg) {
            $sql = 'INSERT INTO widget_argument_values (`key`, `value`, `widget`) VALUES (:key1, :value1, :widget) ON DUPLICATE KEY UPDATE value = :value2';
            $stmt = LA::stmt($sql);
            $stmt->bindValue(':key1', $arg['name']);
            $stmt->bindValue(':value1', $this->getElementValue($arg['name']));
            $stmt->bindValue(':value2', $this->getElementValue($arg['name']));
            $stmt->bindValue(':widget', $this->widget['id']);
            $stmt->execute();
        }
    }

    public function setupProcessedState(ProcessedFormState $state): void
    {
        $state->setProcessedMessage('Widget saved');
    }
}
