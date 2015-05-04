<?php
/*******************************************************************************

  Copyright (C) 2004-2006 xconspirisist (xconspirisist@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*******************************************************************************/

ini_set('display_errors', 'On');

define('BASE_DIR', realpath(dirname(__FILE__) . '/../'));
define('PRIVATE_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', BASE_DIR . '/public/');

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

date_default_timezone_set('UTC');

require_once 'libAllure/ErrorHandler.php';

\libAllure\ErrorHandler::getInstance()->beGreedy();

require_once 'config.php';

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

$db = new \libAllure\Database(Config::DB_DSN, Config::DB_USER, Config::DB_PASS);
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

$tpl = new \libAllure\Template(Config::TEMPLATE_CACHE_DIR, PRIVATE_DIR . DIRECTORY_SEPARATOR .  'views/');
//$tpl = new HTML_Template_Sigma('/var/www/Sicroc/templates/raw/', '/var/www/Sicroc/templates/compiled');

$breadcrumbs = array();
$breadcrumbs[] = '<a href = "index.php">index</a>';

?>
