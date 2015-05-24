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

class Config {
	private static $arguments = array(); 
	
	public function __construct() {
		$this->arguments['DB_DSN'] = 'mysql:Sicroc';
		$this->arguments['DB_USER'] = 'root';
		$this->arguments['DB_PASS'] = '';
		$this->arguments['TEMPLATE_CACHE_DIRECTORY'] = '/var/cache/httpd/Sicroc/';
	}

	public static function write() {
		$content = '';

		foreach ($this->arguments as $key => $value) {
			$content .= $key . '=' . $value . "\n";
		}

		file_put_contents('.sicroc/config.ini');
	}

	public static function read() {
		$configLines = file_get_contents('.sicroc/config.ini');

		foreach ($configLines as $line) {
			$kv = split('=', $line, 1);

			$this->arguments[$kv[0]] = trim($kv[1]);
		}
	}

	public static function get($name) {
		return $this->arguments[$name];
	}
}

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

$db = new \libAllure\Database(Config::get('DB_DSN'), Config::get('DB_USER', Config::get('DB_PASS'));
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

$tpl = new \libAllure\Template(Config->get('TEMPLATE_CACHE_DIR'), PRIVATE_DIR . DIRECTORY_SEPARATOR .  'views/');
//$tpl = new HTML_Template_Sigma('/var/www/Sicroc/templates/raw/', '/var/www/Sicroc/templates/compiled');

$breadcrumbs = array();
$breadcrumbs[] = '<a href = "index.php">index</a>';

?>
