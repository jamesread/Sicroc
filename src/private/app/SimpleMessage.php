<?php

namespace Sicroc;

class SimpleMessage extends Widget
{
    private ?string $message;
    private ?string $class;

    public function __construct($message, $class = 'neutral')
    {
        parent::__construct();

        $this->message = $message;
        $this->class = $class;
    }

    public function render()
    {
        $this->simpleMessage($this->message, $this->class);
    }
}
