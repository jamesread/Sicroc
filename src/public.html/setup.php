<?php

require_once __DIR__ . '/../private/init.php';

sicrocInit();

$bs = new \Sicroc\BaseDatabaseStructure();
$bs->defineCoreStructure();
$bs->execute();

$tpl->assign('page', [
    'title' => 'SETUP',
]);

$tpl->display('widgets/header.minimal.tpl');

$tpl->assign('message', 'Setup complete. ' . $bs->changeCount . ' changes made.');
$tpl->assign('messageClass', 'box good');

$tpl->display('setupComplete.tpl');

$tpl->display('widgets/footer.tpl');
