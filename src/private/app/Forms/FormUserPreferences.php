<?php

namespace Sicroc\Forms;

use libAllure\Session;
use libAllure\ElementCheckbox;
use libAllure\ElementSelect;

class FormUserPreferences extends \libAllure\Form implements \Sicroc\BaseForm
{
    public string $alternativeMessage;

    public function __construct()
    {
        parent::__construct('userPrefs', 'User Preferences');

        if (Session::isLoggedIn()) {
            $user = Session::getUser();

            if ($user->hasPriv('ADMIN')) {
                $this->addElement(new ElementCheckbox('editMode', 'Edit Mode', $user->getData('editMode')));
            }

            $this->addElement(new ElementCheckbox('showDatatypes', 'Show data types', $user->getData('showDatatypes')));

            $fk = $this->addElement(new ElementSelect('fkStyle', 'Foreign Key Style'));
            $fk->addOption('id (description)', 'ID_DESC');
            $fk->addOption('description', 'DESC_ONLY');
            $fk->setValue($user->getData('fkStyle'));

            $groups = Session::getUser()->getUsergroups();
            $groups = implode(', ', array_column($groups, 'title'));
            $this->addElementReadOnly('usergroups', $groups);
        }

        $this->addDefaultButtons('Save preferences');
    }

    public function setupProcessedState($state): void
    {
        if (!Session::isLoggedIn()) {
            $state->preventRender('You are not logged in yet.', 'bad');
            return;
        }

        $state->setProcessedMessage('Preferences saved.');
    }

    public function process()
    {
        $user = Session::getUser();

        if ($user->hasPriv('ADMIN')) {
            $user->setData('editMode', $this->getElementValue('editMode'));
        }

        $user->setData('showDatatypes', $this->getElementValue('showDatatypes'));
        $user->setData('fkStyle', $this->getElementValue('fkStyle'));

        $user->getData('username', false);
    }
}
