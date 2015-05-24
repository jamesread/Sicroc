<?php

class Config {
	private static $arguments = array(); 
	private static $configFile = './sicroc-config.ini';
	
	public static function init() {
		self::$arguments['DB_DSN'] = 'mysql:dbname=Sicroc';
		self::$arguments['DB_USER'] = 'root';
		self::$arguments['DB_PASS'] = '';
		self::$arguments['TEMPLATE_CACHE_DIRECTORY'] = '/var/cache/httpd/Sicroc/';
	}

	public static function write() {
		if (self::isNotInitialized()) {
			self::init();
		}

		$content = '';

		foreach (self::$arguments as $key => $value) {
			$content .= $key . '=' . $value . "\n";
		}

		file_put_contents(self::$configFile, $content);
	}

	private static function isNotInitialized() {
		return empty(self::$arguments);
	}

	public static function read() {
		if (self::isNotInitialized()) {
			self::init();
		}

		if (!file_exists(self::$configFile)) {
			throw new Exception('No sicroc configuration file found: ' . self::$configFile . '. Is it installed in this directory?');
		}

		$configLines = file_get_contents(self::$configFile);

		foreach (explode("\n", $configLines) as $line) {
			$kv = explode('=', $line, 1);

			if (!empty($kv) && count($kv) == 2) {
				self::$arguments[$kv[0]] = trim($kv[1]);
			}
		}
	}

	public static function get($name) {
		return self::$arguments[$name];
	}
}

?>
