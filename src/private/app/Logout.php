<?php

namespace Sicroc;

use libAllure\Session;

class Logout extends Widget
{
    public function display(): void
    {
    }

    public function getTitle(): string
    {
        return "Logout";
    }

    public function render(): void
    {
        if (Session::isLoggedIn()) {
            Session::logout();

            $this->simpleMessage('You have been logged out');
        } else {
            $this->simpleMessage('You do not need to logout, as you were not logged in.');
        }
    }
}
