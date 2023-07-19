<?php

use \libAllure\ElementInput;
use \libAllure\ElementPassword;

use \libAllure\Session;

use \Sicroc\BaseForm;

class FormLogin extends \libAllure\util\FormLogin implements \Sicroc\Controllers\BaseForm 
{
    public $alternativeMessage;

    public function __construct()
    {
        parent::__construct();

        if (Session::isLoggedIn()) {
            $this->alternativeMessage = 'You are already logged in';
        }
    }

    public function setupProcessedAction($state) : void {
        $state->yay();
    }
}

?>
