<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementPassword;

use \libAllure\Session;

class FormLogin extends \libAllure\util\FormLogin
{
    public function __construct()
    {
        parent::__construct();

        if (Session::isLoggedIn()) {
            $this->alternativeMessage = 'You are already logged in';
        }
    }
}

?>
