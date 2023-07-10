<?php

use libAllure\Session;
use libAllure\ElementCheckbox;

class FormUserPreferences extends \libAllure\Form {
    public string $alternativeMessage;

    public function __construct() {
        parent::__construct('userPrefs', 'User Preferences');

        if (!Session::isLoggedIn()) {
            $this->alternativeMessage = 'You are not logged in';
        } else {
            $user = Session::getUser();

            $this->addElement(new ElementCheckbox('editMode', 'Edit Mode', $user->getData('editMode')));
        }

        $this->addDefaultButtons();
    }

    public function process() {
        $user = Session::getUser();

        $user->setData('editMode', $this->getElementValue('editMode'));
        $user->getData('username', false);
    }
}

?>
