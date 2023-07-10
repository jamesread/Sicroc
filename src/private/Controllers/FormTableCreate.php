<?php

use \libAllure\Form;
use \libAllure\ElementInput;

class FormTableCreate extends Form
{
    public function __construct()
    {
        $this->addElement(new ElementInput('table', 'Table', null, 'The name of the database table, no spaces.'));
        $this->addDefaultButtons();
    }

    public function process()
    {
        $sql = 'CREATE TABLE data' . $this->getElementValue('table') . ' (id int not null primary key auto_increment)';
        $stmt = db()->prepare($sql);
        $stmt->execute();

        $this->redirectUrl = '?pageIdent=ADMIN';
    }
}

?>
