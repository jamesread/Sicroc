<?php

namespace Sicroc;

class SimpleMessage extends Widget
{
    private ?string $message = null;
    private ?string $class = null;

    public function __construct(string $message, string $class = 'neutral')
    {
        parent::__construct();

        $this->message = $message;
        $this->class = $class;
    }

    public function render(): void
    {
        $this->simpleMessage($this->message, $this->class);
    }
}
