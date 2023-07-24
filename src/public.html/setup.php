<?php

require_once __DIR__ . '/../private/init.php';

sicrocInit();

$bs = new \Sicroc\BaseDatabaseStructure();
$bs->check();

?>
Setup complete.
