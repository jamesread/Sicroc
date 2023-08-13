<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\ElementInput;
use libAllure\ElementSelect;
use libAllure\DatabaseFactory;
use Sicroc\TableConfiguration;

use function libAllure\util\san;

class FormTableAddColumn extends Form implements \Sicroc\BaseForm
{
    private TableConfiguration $tc;

    public function __construct()
    {
        parent::__construct('addColumn', 'Add Column');

        $this->tc = new TableConfiguration(san()->filterInt('tc'));

        $this->addElementReadOnly('tc', $this->tc->id, 'tc');
        $this->addElementReadOnly('db', $this->tc->database);
        $this->addElementReadOnly('table', $this->tc->table);

        $this->addElement(new ElementInput('name', 'Name'));
        $this->getElement('name')->setMinMaxLengths(1, 255);

        $el = new ElementSelect('type', 'Type');
        $el->addOption('varchar(255)');
        $el->addOption('datetime default current_timestamp()');
        $el->addOption('float(8,2)');
        $el->addOption('tinyint(1)');
        $el->addOption('int');
        $this->addElement($el);

        $this->addDefaultButtons('Create column');
    }

    public function process()
    {
        $sql = 'ALTER TABLE ' . $this->tc->database . '.' . $this->tc->table . ' ADD ' . $this->getElementValue('name') . ' ' . $this->getElementValue('type');

        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->redirect('?pageIdent=TABLE_VIEW&tc=' . $this->getElementValue('tc'));
            $state->preventRender('block');
        }
    }
}
