<?php

namespace Sicroc\Forms;

use libAllure\ElementInput;
use libAllure\Shortcuts as LA;

class FormWidgetClassRegister extends \libAllure\Form
{
    public function __construct()
    {
        $this->addElement(new ElementInput('viewableController', 'Viewable Controller Class'));

        $this->addDefaultButtons('Register Widget Type');
    }

    public function process()
    {
        $sql = 'INSERT INTO widget_types (viewableController) VALUES (:viewableController)';
        $stmt = LA::db()->prepare($sql);
        $stmt->bindValue(':viewableController', $this->getElementValue('viewableController'));
        $stmt->execute();
    }
}
