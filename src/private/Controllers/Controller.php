<?php

namespace Sicroc\Controllers;

use \libAllure\Session;

abstract class Controller
{
    public function getAdminLinks()
    {
    }

    public function getNavigationMain()
    {
    }

    public function classHasParent($class, $parent)
    {
        $parents = class_parents($class);

        foreach ($parents as $mybottom) {
            if ($mybottom == $parent) {
                return true;
            }
        }

        return false;
    }

    public function getTitle()
    {
        return 'Untitled controller';
    }
}

?>
