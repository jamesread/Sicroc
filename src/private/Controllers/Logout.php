<?php

namespace Sicroc\Controllers;

use \libAllure\Session;

class Logout extends Widget
{
    public function display()
    {
    }
 
    public function getTitle()
    {
        return "Logout";
    }

    public function render()
    {
        if (Session::isLoggedIn()) {
            Session::logout();

            $this->simpleMessage('You have been logged out');
        } else {
            $this->simpleMessage('You were not logged in.');
        }
    }
}
