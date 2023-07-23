<?php

use libAllure\ElementInput;
use libAllure\ElementPassword;
use libAllure\Session;
use Sicroc\BaseForm;

class FormLogin extends \libAllure\util\FormLogin implements \Sicroc\Controllers\BaseForm
{
    public $alternativeMessage;

    public function __construct()
    {
        parent::__construct();
    }

    public function setupProcessedState($state): void
    {
        if (Session::isLoggedIn()) {
            $state->preventRender('You are already logged in.');
            return;
        }

        if ($state->processed) {
            $state->preventRender('You have logged in.');
        }
    }
}
