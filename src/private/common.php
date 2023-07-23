<?php

require_once 'init.php';

use Sicroc\Config;

Config::read();

require_once 'procedural/interface.php';
require_once 'libAllure/util/shortcuts.php';

$db = new \libAllure\Database(Config::get('DB_DSN'), Config::get('DB_USER'), Config::get('DB_PASS'));
\libAllure\DatabaseFactory::registerInstance($db);

\libAllure\Session::start();
$backend = new \libAllure\AuthBackendDatabase($db);
\libAllure\AuthBackend::setBackend(new \libAllure\AuthBackendDatabase($db));

//
// setup error handlr
//

if (isset($_REQUEST['httpError'])) {
    $eeh->httpError(intval($_REQUEST['httpError']));
}

$tpl = new \libAllure\Template(Config::get('TEMPLATE_CACHE_DIRECTORY'), PRIVATE_DIR . DIRECTORY_SEPARATOR .  'views/');
//$tpl = new HTML_Template_Sigma('/var/www/Sicroc/templates/raw/', '/var/www/Sicroc/templates/compiled');

$breadcrumbs = array();
$breadcrumbs[] = '<a href = "index.php">index</a>';
