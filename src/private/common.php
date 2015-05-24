<?php

require_once 'init.php';

Config::read();

require_once 'procedural/interface.php';
require_once 'libAllure/util/shortcuts.php';
require_once 'libAllure/Exceptions.php';
require_once 'libAllure/Database.php';
require_once 'libAllure/Logger.php';
require_once 'libAllure/Session.php';
require_once 'libAllure/Template.php';
require_once 'libAllure/Form.php';
require_once 'libAllure/HtmlLinksCollection.php';
require_once 'libAllure/AuthBackendDatabase.php';

$db = new \libAllure\Database(Config::get('DB_DSN'), Config::get('DB_USER'), Config::get('DB_PASS'));
\libAllure\DatabaseFactory::registerInstance($db);

require_once PRIVATE_DIR . 'Controller.php';
require_once CONTROLLERS_DIR . 'DsHandler.php';
require_once PRIVATE_DIR . 'Model.php';
require_once CONTROLLERS_SYSTEM_DIR . 'Navigation.php';
require_once MODELS_DIR . 'LinkList.php';
require_once MODELS_DIR . 'WikiBlock.php';
require_once CONTROLLERS_DIR . 'FormLogin.php';
require_once CONTROLLERS_DIR . 'LayoutManager.php';
require_once MODELS_DIR . 'Dataset.php';
require_once CONTROLLERS_SYSTEM_DIR . 'HTML_Table.php';

\libAllure\Session::start();
$backend = new \libAllure\AuthBackendDatabase($db);
$backend->createTables();
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

?>
