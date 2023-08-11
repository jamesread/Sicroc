<?php

function sicrocInit()
{
    require_once 'libraries/autoload.php';

    require_once 'utils.php';

    setupLibAllure();
    setupDatabase();
    setupTemplateEngine();
    setupTimezone();
}

function setupLibAllure()
{
    \Composer\InstalledVersions::satisfies(
        new \Composer\Semver\VersionParser(),
        'jwread/lib-allure',
        '^8.0.2'
    ) or trigger_error('libAllure needs to be installed', E_USER_ERROR);

    // FIXME shortcuts should be autoloadable
    \libAllure\IncludePath::addLibAllure();
    require_once 'libAllure/util/shortcuts.php';

    \libAllure\ErrorHandler::getInstance()->beGreedy();
}

function setupDatabase()
{
    global $db; // Needed for libAllure Shortcuts

    $config = \Sicroc\Config::getInstance();
    $config->read();

    $db = new \libAllure\Database($config->get('DB_DSN'), $config->get('DB_USER'), $config->get('DB_PASS'));
    \libAllure\DatabaseFactory::registerInstance($db);

    \libAllure\Session::setSessionName('sicroc');
    \libAllure\Session::setCookieLifetimeInSeconds(10000000);
    \libAllure\Session::start();
    $backend = new \libAllure\AuthBackendDatabase($db);
    \libAllure\AuthBackend::setBackend(new \libAllure\AuthBackendDatabase($db));
}

function setupTemplateEngine()
{
    global $tpl;

    $tplCacheDirectory = \Sicroc\Config::getInstance()->get('TEMPLATE_CACHE_DIRECTORY');

    $tpl = new \libAllure\Template(
        $tplCacheDirectory,
        __DIR__ . '/views/',
    );
}

function setupTimezone()
{
    date_default_timezone_set(\Sicroc\Config::getInstance()->get('TIMEZONE', 'Europe/London'));
}
