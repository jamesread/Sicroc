<?php

namespace Sicroc;

class ProcessedFormState
{
    public bool $processed = false;
    public null|string $processedMessage = 'The form has been submitted.';
    public ?string $nonRenderMessage = null;
    public ?string $nonRenderMessageClass = 'bad';
    public ?string $redirectUrl = null;
    public bool $shouldRender = true;

    public function preventRender($message, $class = 'good'): void
    {
        $this->shouldRender = false;
        $this->nonRenderMessage = $message;
        $this->nonRenderMessageClass = $class;
    }

    public function setProcessedMessage($msg, $shouldRender = false): void
    {
        $this->processedMessage = $msg;
        $this->shouldRender = $shouldRender;
    }

    public function redirect($url): void
    {
        $this->redirectUrl = $url;
    }
}
