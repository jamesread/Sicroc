<?php

namespace Sicroc;

use libAllure\ElementHidden;
use libAllure\Sanitizer;

class WidgetForm extends Widget
{
    private ?\libAllure\Form $f;

    private \Sicroc\ProcessedFormState $state;

    public function widgetSetupCompleted(): void
    {
        $formClass = $this->getArgumentValue('formClass');

        $this->state = new \Sicroc\ProcessedFormState();

        if ($formClass == 'SicrocFormsFormLogin') {
            $formClass = "\Sicroc\Forms\FormLogin";
        }

        if (!empty($formClass) && class_exists($formClass)) {
            /**
            if (!@include_once 'app/'CONTROLLERS_DIR . $formClass . '.php') {
                throw new Exception('Could not include PHP class for form: ' . CONTROLLERS_DIR . $formClass . '.php');
            }
             */

            $this->f = new $formClass($this);
            $this->f->addElementDetached(new ElementHidden('page', null, LayoutManager::get()->getPage()->getId()));

            $this->setupForm();
        } else {
            $this->f = null;
        }
    }

    private function setupForm(): void
    {
        if (!isset($this->f)) {
            return;
        }

        if ($this->f->validate()) {
            $this->f->process();

            $this->state->processed = true;
        }

        if ($this->f instanceof \Sicroc\BaseForm) {
            $this->f->setupProcessedState($this->state);
        }
    }

    public function getArguments(): array
    {
        $args = array();
        $args[] = array('type' => 'varchar', 'name' => 'formClass', 'default' => '', 'description' => 'The name of the form class');

        return $args;
    }

    public function shouldRender(): bool
    {

        return $this->state->shouldRender;
    }

    public function render(): void
    {
        if ($this->state->processed) {
            if ($this->state->redirectUrl != null) {
                $redirectMessage = 'FIXME Redirect message';

                Utils::redirect($this->state->redirectUrl, $redirectMessage);
                return;
            }

            $this->simpleMessage($this->state->message, $this->state->messageClass);

            if (!$this->state->shouldRender) {
                return;
            }
        }

        if (!$this->state->shouldRender) {
            $this->simpleMessage('nrm: ' . $this->state->message, $this->state->messageClass);
            return;
        }

        if ($this->f != null) {
            $this->tpl->assignForm($this->f);
            $this->tpl->display('form.tpl');
        } else {
            $this->simpleMessage('This widget is assigned with a form controller, but no form has been constructed, possibly due to some sort of error. Sorry that this message is mostly useless.', 'bad');
        }
    }

    public function validate(): void
    {
        $this->f->process();
    }

    public function getTitle(): string
    {
        if (isset($this->f)) {
            return 'Form: ' . $this->f->getTitle();
        } else {
            return 'No form constructed';
        }
    }
}
