<?php

use \libAllure\Form;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;

class FormAddUserToGroup extends Form {
	public function __construct() {
		$this->addElementUser();
		$this->addElementUserGroup();
		$this->addDefaultButtons('Add');
	} 

	private function addElementUser() {
		$sql = 'SELECT id, username FROM users ';
		$stmt = stmt($sql);
		$stmt->execute();
		
		$selectUser = new ElementSelect('user', 'User');
		$selectUser->setSize(5);

		foreach ($stmt->fetchAll() as $user) {
			$selectUser->addOption($user['username'], $user['id']);
		}

		$this->addElement($selectUser);
	}

	private function addElementUserGroup() { 
		$sql = 'SELECT id, title FROM groups ';
		$stmt = stmt($sql);
		$stmt->execute();

		$selectGroup = new ElementSelect('group', 'Group');

		foreach ($stmt->fetchAll() as $group) {
			$selectGroup->addOption($group['title'], $group['id']);
		}

		$this->addElement($selectGroup);

	}

	public function process() {
		$user = $this->getElementValue('user');
		$group = $this->getElementValue('group');

		var_dump($user, $group);

		exit;

		$stmt = DatabaseFactory::getInstance()->prepare($sql);

		$stmt->bindValue('title', $this->getElementValue('title'));
		$stmt->execute();
	}
}

?>
