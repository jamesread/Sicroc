<?php

namespace Sicroc\Forms;

use libAllure\ElementInput;
use libAllure\ElementPassword;
use libAllure\Session;
use libAllure\User;
use Sicroc\BaseForm;

class FormLogin extends \libAllure\util\FormLogin implements \Sicroc\BaseForm
{
    public $alternativeMessage;

    public function __construct()
    {
        parent::__construct();
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed) {
            $state->preventRender('You have logged in.');
            return;
        }

        if (Session::isLoggedIn()) {
            $state->preventRender('You are already logged in.');
            return;
        }

        if (empty(User::getCountLocalUsers())) {
            $state->preventRender('There are zero users registerd. If you have just setup Sicroc, please <a href = "?pageIdent=REGISTER">register the first user</a>.');
            return;
        }
    }
}
