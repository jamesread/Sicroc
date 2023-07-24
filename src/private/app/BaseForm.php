<?php

namespace Sicroc;

interface BaseForm
{
    public function setupProcessedState(\Sicroc\ProcessedFormState $state): void;
}
