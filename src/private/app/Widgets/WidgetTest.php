<?php

namespace Sicroc\Widgets;

class WidgetTest extends \Sicroc\Widget
{
    public function render(): void
    {
        $this->simpleMessage('test');
    }
}
