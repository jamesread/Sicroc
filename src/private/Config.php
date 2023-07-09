<?php

namespace Sicroc;

class Config {
    private static $arguments = array(); 

    private static function init() {
        self::$arguments['DB_DSN'] = 'mysql:dbname=Sicroc';
        self::$arguments['DB_USER'] = 'root';
        self::$arguments['DB_PASS'] = '';
        self::$arguments['TEMPLATE_CACHE_DIRECTORY'] = '/var/cache/httpd/Sicroc/';
    }

    public static function write() {
        throw new Exception("Writing config to file is deprecated, because config can come from various sources.");

                /**
                if (self::isNotInitialized()) {
                        self::init();
                }

                $content = '';

                foreach (self::$arguments as $key => $value) {
                        $content .= $key . '=' . $value . "\n";
                }

                file_put_contents(self::$configFile, $content);

                 */
    }

    private static function isNotInitialized() {
        return empty(self::$arguments);
    }

    public static function read() {
        if (self::isNotInitialized()) {
            self::init();
        }

        self::readConfigFile();
        self::readEnvironmentVariables();
    }

    private static function readConfigFile() {
        $configFileLocations = [
            '/etc/Sicroc/sicroc-config.ini',
            '/etc/Sicroc/config.ini',
            'config.ini',
        ];

        foreach ($configFileLocations as $possibleLocation) {
            if (file_exists($possibleLocation)) {
                $content = @parse_ini_file($possibleLocation, false);

                if ($content === false) {
                    throw new Exception('Could not parse file as a INI: ' . $possibleLocation);
                }

                self::$arguments = array_merge(self::$arguments, $content);
            }
        }
    }

    private static function readEnvironmentVariables() {
        self::tryReadEnvironmentVariable('DB_DSN');
    }

    private static function tryReadenvironmentVariable($name) {
        $value = getenv($name);

        if ($value != FALSE) {
            self::$arguments[$name] = $value;
        }
    }

    public static function get($name) {
        return self::$arguments[$name];
    }

    public static function getAll() {
        return self::$arguments;
    }
}

?>
