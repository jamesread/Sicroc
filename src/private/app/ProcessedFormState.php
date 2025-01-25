<?php

namespace Sicroc;

class ProcessedFormState
{
    public bool $processed = false;
    public null|string $message = 'The form has been submitted.';
    public ?string $messageClass = 'neutral';
    public ?string $redirectUrl = null;
    public bool $shouldRender = true;

    public function preventRender(string $message, string $messageClass = 'good'): void
    {
        $this->message = $message;
        $this->messageClass = $messageClass;
        $this->shouldRender = false;
    }

    public function setProcessedMessage(string $msg, string $messageClass = 'neutral', bool $shouldRender = true): void
    {
        $this->message = $msg;
        $this->messageClass = $messageClass;
        $this->shouldRender = $shouldRender;
    }

    public function redirect(string $url): void
    {
        $this->redirectUrl = $url;
    }

    public function redirectIdent(string $ident): void
    {
        $this->redirect('?pageIdent=' . $ident);
    }
}
