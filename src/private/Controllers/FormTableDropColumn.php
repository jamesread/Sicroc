<?php

class FormTableDropColumn extends \libAllure\Form
{
    public function __construct()
    {
        parent::__construct('formTableDropColumn', 'Drop Column');

        $this->addDefaultButtons();
    }
}
