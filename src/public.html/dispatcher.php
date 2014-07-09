<?php

require_once '../private/common.php';

$principle = san()->filterString('controller');
$method = san()->filterString('method');

LayoutManager::setLayout($principle, $method);
LayoutManager::render();

?>
