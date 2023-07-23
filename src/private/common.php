<?php

require_once 'init.php';

use Sicroc\Config;

Config::read();

require_once 'libAllure/util/shortcuts.php';

$db = new \libAllure\Database(Config::get('DB_DSN'), Config::get('DB_USER'), Config::get('DB_PASS'));
\libAllure\DatabaseFactory::registerInstance($db);

\libAllure\Session::start();
$backend = new \libAllure\AuthBackendDatabase($db);
\libAllure\AuthBackend::setBackend(new \libAllure\AuthBackendDatabase($db));

$tpl = new \libAllure\Template(Config::get('TEMPLATE_CACHE_DIRECTORY'), PRIVATE_DIR . DIRECTORY_SEPARATOR .  'views/');
