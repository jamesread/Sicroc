<?php

namespace Sicroc\Controllers\Widgets;

class WidgetTest extends \Sicroc\Controllers\Widget
{
    public function render()
    {
        $this->simpleMessage('test');
    }
}
