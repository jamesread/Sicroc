<?php

namespace Sicroc\Forms;

use Sicroc\TableConfiguration;
use libAllure\Form;
use libAllure\Shortcuts as LA;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\ElementNumeric;

class FormTableConditionalFormatting extends Form
{
    private TableConfiguration $tc;

    public function __construct()
    {
        $this->tc = new TableConfiguration(LA::san()->filterUint('tc'));

        $this->addElementReadOnly('Table Configuration', $this->tc->id, 'tc');
        $this->addElementReadOnly('DB', $this->tc->database);
        $this->addElementReadOnly('Table', $this->tc->table);

        $el = $this->addElement(new ElementNumeric('priority', 'Priority'));
        $el->type = 'number';
        $el->setValue(100);

        $this->addSection('Condition');

        $el = $this->addElement(new ElementSelect('field', 'Field', null, null));

        foreach (array_column($this->tc->headers, 'name') as $header) {
            $el->addOption($header);
        }


        $sel = new ElementSelect('operator', 'Operator');
        $sel->addOption('always');
        $sel->addOption('contains');
        $sel->addOption('equals');
        $this->addElement($sel);

        $el = new ElementInput('value', 'Value');
        $el->setMinMaxLengths(1, 64);
        $this->addElement($el);

        $this->addSection('Format');
        $sel = new ElementSelect('display_as', 'Display');
        $sel->addOption('text');
        $sel->addOption('hyperlink');
        $this->addElement($sel);

        $el = new ElementInput('foreground', 'Text foreground');
        $el->type = 'color';
        $el->setValue('#000000');
        $this->addElement($el);

        $el = new ElementInput('background', 'Cell background');
        $el->type = 'color';
        $el->setValue('#ffffff');
        $this->addElement($el);

        $el = new ElementInput('css', 'Extra CSS');
        $this->addElement($el);

        $this->addDefaultButtons('Create');
    }

    public function process(): void
    {
        $css = 'background-color: ' . $this->getElementValue('background') . '; color: ' . $this->getElementValue('foreground') . '; ' . $this->getElementValue('css');

        $sql = 'INSERT INTO table_conditional_formatting (tc, field, cell_style, operator, cell_value, priority_order, display_as) VALUES (:tc, :field, :css, :operator, :cell_value, :priority_order, :display_as)';
        $stmt = LA::stmt($sql);
        $stmt->execute([
            'tc' => $this->tc->id,
            'field' => $this->getElementValue('field'),
            'css' => $css,
            'operator' => $this->getElementValue('operator'),
            'cell_value' => $this->getElementValue('value'),
            'priority_order' => $this->getElementValue('priority'),
            'display_as' => $this->getElementValue('display_as'),
        ]);
    }
}
