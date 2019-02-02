<?php

ini_set('display_errors', 'On');

define('BASE_DIR', realpath(dirname(__FILE__) . '/../') . '/');
define('PRIVATE_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', BASE_DIR . '/public/');

require_once PRIVATE_DIR . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'autoload.php';

define('CONTROLLERS_DIR', PRIVATE_DIR . '/controllers/');
define('CONTROLLERS_SYSTEM_DIR', PRIVATE_DIR . '/controllers/system/');
define('MODELS_DIR', PRIVATE_DIR . '/models/');
define('TEMPLATES_DIR', PRIVATE_DIR . 'views' . DIRECTORY_SEPARATOR);

$_GET['showError'] = true; // FIXME

// set include path for public files.
set_include_path(
	get_include_path() .
	PATH_SEPARATOR . '/usr/share/php/smarty/libs/'
	);

\libAllure\IncludePath::add_libAllure();

date_default_timezone_set('UTC');

require_once 'libAllure/ErrorHandler.php';

\libAllure\ErrorHandler::getInstance()->beGreedy();

require_once 'Config.php';

?>
