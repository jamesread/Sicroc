<?php

namespace Sicroc\Forms;

use libAllure\Shortcuts as LA;

class FormCreateUsergroup extends \libAllure\Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        parent::__construct('createUsergroup', 'Create Usergroup');

        $this->addElement(new \libAllure\ElementInput('title', 'Title'));

        $this->addDefaultButtons('Create usergroup');
    }

    public function process()
    {
        $sql = 'INSERT INTO `groups` (title) values (:title); ';
        $stmt = LA::db()->prepare($sql);
        $this->bindStatementValues($stmt, [
            'title'
        ]);

        $stmt->execute();
    }

    public function setupProcessedState($state): void
    {
        $state->setProcessedMessage('Group created.', 'good');
    }
}
