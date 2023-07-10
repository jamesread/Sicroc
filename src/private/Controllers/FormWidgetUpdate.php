<?php

use \Sicroc\Controllers\Page;

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;

use function \libAllure\util\stmt;

class FormWidgetUpdate extends Form
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
        $this->addElement(new ElementInput('method', 'Method', $this->widget['method']));

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

        $this->addDefaultButtons();
    }

    private function getWidget()
    {
        $id = Sanitizer::getInstance()->filterUint('widgetToUpdate');
        $sql = 'SELECT wi.id, wi.title, wi.method, wt.viewableController FROM widget_instances wi JOIN widget_types wt ON wi.type = wt.id WHERE wi.id = :id LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchRowNotNull();
    }

    public function process()
    {
        $sql = 'UPDATE widget_instances w SET w.title = :title, method = :method WHERE w.id = :id ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':title', $this->getElementValue('title'));
        $stmt->bindValue(':id', $this->widget['id']);
        $this->bindElementToStatement($stmt, 'method');
        $stmt->execute();

        foreach ($this->widget['inst']->getArguments() as $arg) {
            $sql = 'INSERT INTO widget_argument_values (`key`, `value`, `widget`) VALUES (:key1, :value1, :widget) ON DUPLICATE KEY UPDATE value = :value2';
            $stmt = stmt($sql);
            $stmt->bindValue(':key1', $arg['name']);
            $stmt->bindValue(':value1', $this->getElementValue($arg['name']));
            $stmt->bindValue(':value2', $this->getElementValue($arg['name']));
            $stmt->bindValue(':widget', $this->widget['id']);
            $stmt->execute();
        }
    }
}

?>
