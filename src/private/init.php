<?php

define('BASE_DIR', realpath(dirname(__FILE__) . '/../') . '/');
define('PRIVATE_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', BASE_DIR . '/public/');

$autoloader = require_once PRIVATE_DIR . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'autoload.php';

use Composer\Semver\VersionParser;

\Composer\InstalledVersions::satisfies(new VersionParser(), 'jwread/lib-allure', '^8.0.2') or trigger_error('libAllure needs to be installed', E_USER_ERROR);

define('CONTROLLERS_DIR', PRIVATE_DIR . '/Controllers/');
define('CONTROLLERS_SYSTEM_DIR', PRIVATE_DIR . '/controllers/system/');
define('MODELS_DIR', PRIVATE_DIR . '/models/');
define('TEMPLATES_DIR', PRIVATE_DIR . 'views' . DIRECTORY_SEPARATOR);

\libAllure\IncludePath::addLibAllure();

\libAllure\ErrorHandler::getInstance()->beGreedy();

date_default_timezone_set('Europe/London');
