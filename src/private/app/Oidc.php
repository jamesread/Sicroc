<?php

namespace Sicroc;

class Oidc extends Widget
{
    public function render(): void
    {
        $this->tpl->display('oidc.tpl');
    }
}
