<?php

namespace Sicroc\Controllers;

use \libAllure\ElementHidden;
use \libAllure\Sanitizer;

class WidgetForm extends Widget
{
    private \libAllure\Form $f;

    public function widgetSetupCompleted()
    {
        $principle = $this->getArgumentValue('formClass');

        if (!empty($principle)) {
            if (!@include_once CONTROLLERS_DIR . $principle . '.php') {
                throw new Exception('Could not include PHP class for form: ' . CONTROLLERS_DIR . $principle . '.php');
            }

            $this->f = new $principle($this);
            $this->f->addElementDetached(new ElementHidden('page', null, LayoutManager::get()->getPage()->getId()));
        }
    }

    public function getArguments()
    {
        $args = array();
        $args[] = array('type' => 'varchar', 'name' => 'formClass', 'default' => '', 'description' => 'The name of the form class');

        return $args;
    }

    public function display()
    {
        if (!isset($this->f)) {
            return;
        }

        if ($this->f->validate()) {
            $this->f->process();

            if (isset($this->f->redirectUrl)) {
                if (isset($this->f->redirectMessage)) {
                    $redirectMessage = $this->f->redirectMessage;
                } else {
                    $redirectMessage = 'Form submitted';
                }

                redirect($this->f->redirectUrl, $redirectMessage);
            }

            $this->simpleMessage('formSubmitted', 'good');
        }

    }

    public function render()
    {
        if (isset($this->f->alternativeMessage)) {
            $this->simpleMessage('Alt: ' . $this->f->alternativeMessage);
            return;
        }

        if (isset($this->f)) {
            $this->tpl->assignForm($this->f);
            $this->tpl->display('form.tpl');
        } else {
            $this->simpleMessage('This widget is assigned with a form controller, but no form has been constructed, possibly due to some sort of error. Sorry that this message is mostly useless.');
        }
    }

    public function validate()
    {
        global $tpl;

        $this->f->process();

    }

    public function getTitle()
    {
        if (isset($this->f)) {
            return 'Form: ' . $this->f->getTitle();
        } else {
            return 'No form constructed';
        }
    }
}

?>
