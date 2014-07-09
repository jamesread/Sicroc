<?php

require_once '../private/common.php';

use \libAllure\Sanitizer;

$id = Sanitizer::getInstance()->filterUint('id');
$page = new Page();
$page->resolve();

echo json_encode($page->widgets);

?>
