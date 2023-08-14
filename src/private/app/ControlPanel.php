<?php

namespace Sicroc;

use libAllure\HtmlLinksCollection;
use libAllure\Session;

class ControlPanel extends \Sicroc\Widget
{
    public function render()
    {
        $this->tpl->display('controlPanel.tpl');
    }
}
