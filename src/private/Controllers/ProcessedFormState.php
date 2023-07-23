<?php

namespace Sicroc\Controllers;

class ProcessedFormState
{
    public bool $processed = false;
    public null|string $processedMessage = null;
    public ?string $nonRenderMessage = null;
    public ?string $nonRenderMessageClass = 'bad';

    public function preventRender($message, $class = 'good')
    {
        $this->nonRenderMessage = $message;
        $this->nonRenderMessageClass = $class;
    }

    public function redirect($url)
    {
        $this->preventRender('FIXME Redirect: ' . $url);
    }
}
