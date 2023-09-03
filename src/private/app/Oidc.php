<?php

namespace Sicroc;

class Oidc extends Widget
{
    public function render()
    {
        $this->tpl->display('oidc.tpl');
    }
}
