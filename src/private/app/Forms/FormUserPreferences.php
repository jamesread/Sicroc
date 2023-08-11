<?php

namespace Sicroc\Forms;

use libAllure\Session;
use libAllure\ElementCheckbox;

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

        $user->getData('username', false);
    }
}
