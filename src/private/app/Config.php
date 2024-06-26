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
            'TEMPLATE_CACHE_DIRECTORY' => '/tmp/Sicroc/',
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

    public function read(): void
    {
        $this->readConfigFile();
        $this->readEnvironmentVariables();
    }

    private function readConfigFile(): void
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

    private function readEnvironmentVariables(): void
    {
        $this->tryReadEnvironmentVariable('DB_DSN');
    }

    private function tryReadenvironmentVariable(string $name): void
    {
        $value = getenv($name);

        if ($value != false) {
            $this->arguments[$name] = $value;
        }
    }

    public function get(string $name): string
    {
        return $this->arguments[$name];
    }

    public function getAll(): array
    {
        return $this->arguments;
    }
}
