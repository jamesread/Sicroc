<?php

namespace Sicroc\Forms;

use libAllure\Form;
use libAllure\ElementSelect;
use libAllure\DatabaseFactory;
use libAllure\Shortcuts as LA;
use Sicroc\ProcessedFormState;

class FormAddUserToGroup extends Form implements \Sicroc\BaseForm
{
    public function __construct()
    {
        $this->addElementUser();
        $this->addElementUserGroup();
        $this->addDefaultButtons('Add');
    }

    private function addElementUser(): void
    {
        $sql = 'SELECT id, username FROM users ';
        $stmt = LA::db()->prepare($sql);
        $stmt->execute();

        $selectUser = new ElementSelect('user', 'User');
        $selectUser->setSize(5);

        foreach ($stmt->fetchAll() as $user) {
            $selectUser->addOption($user['username'], $user['id']);
        }

        $this->addElement($selectUser);
    }

    private function addElementUserGroup(): void
    {
        $sql = 'SELECT id, title FROM groups ';
        $stmt = LA::stmt($sql);
        $stmt->execute();

        $selectGroup = new ElementSelect('group', 'Group');

        foreach ($stmt->fetchAll() as $group) {
            $selectGroup->addOption($group['title'], $group['id']);
        }

        $this->addElement($selectGroup);
    }

    public function process(): void
    {
        $user = $this->getElementValue('user');
        $group = $this->getElementValue('group');

        $sql = 'INSERT INTO group_memberships (`group`, `user`) VALUES (:group, :user)';

        $stmt = LA::stmt($sql);
        $this->bindStatementValues($stmt, [
            'user',
            'group'
        ]);

        $stmt->execute();
    }

    public function setupProcessedState(ProcessedFormState $state): void
    {
        if ($state->processed) {
            $state->setProcessedMessage('User added', 'good', false);
        }
    }
}
