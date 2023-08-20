<?php

namespace Sicroc\Forms;

use \libAllure\ElementInput;
use \libAllure\ElementPassword;
use \libAllure\DatabaseFactory;
use \libAllure\AuthBackend;

class FormRegister extends \libAllure\Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        parent::__construct('formRegister', 'Register');

        $this->addElement(new ElementInput('username', 'Username'));
        $this->addElement(new ElementPassword('password', 'Password'));
        $this->addElement(new ElementPassword('passwordAgain', 'Password (Confirm)'));

        $this->addDefaultButtons('Register');
    }

    public function validateExtended()
    {
        $sql = 'SELECT username FROM users WHERE username = :username';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute([
            'username' => $this->getElementValue('username'),
        ]);

        if ($stmt->numRows() != 0) {
            $this->setElementError('username', 'That username is already taken.');
        }

        if ($this->getElementValue('password') != $this->getElementValue('passwordAgain')) 
        {
            $this->setElementError('passwordAgain', 'The passwords do not match.');
        }
    }

    public function process()
    {
        $sql = 'INSERT INTO users (username, password) VALUES (:username, :password) ';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute([
            'username' => $this->getElementValue('username'),
            'password' => AuthBackend::getInstance()->hashPassword($this->getElementValue('password')),
        ]);
    }

    public function setupProcessedState($state): void
    {
        if ($state->processed)
        {
            $state->redirect('?pageIdent=LOGIN');
        }
    }
}
