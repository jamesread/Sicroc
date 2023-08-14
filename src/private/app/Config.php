<?php

namespace Sicroc;

class Config
{
    private array $arguments = [];

    private static ?Config $instance = null;

    public function __construct()
    {
        $this->arguments = [
            'DB_DSN' => 'mysql:dbname=Sicroc',
            'DB_USER' => 'root',
            'DB_PASS' => '',
            'TEMPLATE_CACHE_DIRECTORY' => '/var/cache/httpd/Sicroc/',
            'TIMEZONE' => 'Europe/London',
        ];
    }

    public static function getInstance(): Config
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    public function read()
    {
        $this->readConfigFile();
        $this->readEnvironmentVariables();
    }

    private function readConfigFile()
    {
        $configFileLocations = [
            '/etc/Sicroc/sicroc-config.ini',
            '/etc/Sicroc/config.ini',
            'config.ini',
        ];

        foreach ($configFileLocations as $possibleLocation) {
            if (file_exists($possibleLocation)) {
                $content = @parse_ini_file($possibleLocation, false);

                if ($content === false) {
                    throw new \Exception('Could not parse file as a INI: ' . $possibleLocation);
                }

                $this->arguments = array_merge($this->arguments, $content);
            }
        }
    }

    private function readEnvironmentVariables()
    {
        $this->tryReadEnvironmentVariable('DB_DSN');
    }

    private function tryReadenvironmentVariable($name)
    {
        $value = getenv($name);

        if ($value != false) {
            $this->arguments[$name] = $value;
        }
    }

    public function get($name)
    {
        return $this->arguments[$name];
    }

    public function getAll()
    {
        return $this->arguments;
    }
}
