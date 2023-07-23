<?php

namespace Sicroc\Controllers;

interface BaseForm
{
    public function setupProcessedState(\Sicroc\Controllers\ProcessedFormState $state): void;
}
