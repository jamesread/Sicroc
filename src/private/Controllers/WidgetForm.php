<?php

namespace Sicroc\Controllers;

use libAllure\ElementHidden;
use libAllure\Sanitizer;

class WidgetForm extends Widget
{
    private \libAllure\Form $f;

    private \Sicroc\Controllers\ProcessedFormState $state;

    public function widgetSetupCompleted()
    {
        $formClass = $this->getArgumentValue('formClass');

        $this->state = new \Sicroc\Controllers\ProcessedFormState();

        if (!empty($formClass)) {
            if (!@include_once CONTROLLERS_DIR . $formClass . '.php') {
                throw new Exception('Could not include PHP class for form: ' . CONTROLLERS_DIR . $formClass . '.php');
            }

            $this->f = new $formClass($this);
            $this->f->addElementDetached(new ElementHidden('page', null, LayoutManager::get()->getPage()->getId()));

            $this->setupForm();
        }
    }

    public function setupForm()
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

                \Sicroc\util\redirect($this->f->redirectUrl, $redirectMessage); // FIXME not a good place to put this, maybe move to render()
            }

            $this->state->processed = true;
        }

        if ($this->f instanceof \Sicroc\Controllers\BaseForm) {
                $this->f->setupProcessedState($this->state);
        }
    }

    public function getArguments()
    {
        $args = array();
        $args[] = array('type' => 'varchar', 'name' => 'formClass', 'default' => '', 'description' => 'The name of the form class');

        return $args;
    }

    public function render()
    {
        if ($this->state->processed) {
            $msg = $this->state->processedMessage;

            if ($msg == null) {
                $msg = 'The form has been submitted.';
            }

            $this->simpleMessage($msg, 'good');
            return;
        }

        if (isset($this->state->nonRenderMessage)) {
            $this->simpleMessage($this->state->nonRenderMessage, $this->state->nonRenderMessageClass);
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
