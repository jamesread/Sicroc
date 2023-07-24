<?php

namespace Sicroc\Widgets;

class WidgetTest extends \Sicroc\Widget
{
    public function render()
    {
        $this->simpleMessage('test');
    }
}
