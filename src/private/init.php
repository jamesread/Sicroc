<?php

function sicrocInit()
{
	require_once 'libraries/autoload.php';

	header('Content-Type: text/html; charset=UTF-8');

    ini_set('session.gc_maxlifetime', '525960');

    try {
        $config = \Sicroc\Config::getInstance();
        $config->read();
    } catch (Exception $e) {
        startupError('Could not initialize config. ' . $e->getMessage());
    }

    setupTemplateEngine();
    setupLibAllure();

    try {
        setupDatabase($config);
    } catch (Exception $e) {
        startupError('Could not initialize database. ' . $config->get('DB_DSN') . ' : ' . $e->getMessage());
    }

    setupTimezone();
}

/**
 * This is only used within this file, if one of the core dependencies like the
 * Database, Temlating, or other things cannot be found. While inline HTML is
 * ugly, we cannot assume anything else is working (Templating), and we know
 * that no headers or anything else will have been sent.
 */
function startupError($message)
{
    $message = nl2br($message);
    $message = <<<HTML
<head>
<title>Sicroc startup error</title>
<style type = "text/css">
body {
    background-color: #efefef;
    font-family: sans-serif;
    padding: 2em;
}
</style>
</head>
<body>
    <h1>Sicroc startup error</h1>
    $message
</body>
HTML;
    echo $message;

    exit;
}


function setupLibAllure()
{
    $requiredVersion = '^8.1.19';

    \Composer\InstalledVersions::satisfies(
        new \Composer\Semver\VersionParser(),
        'jwread/lib-allure',
        $requiredVersion
    ) or startupError('libAllure needs to be installed with at least version: ' . $requiredVersion);

    \libAllure\ErrorHandler::getInstance()->beGreedy();

    \libAllure\Sanitizer::getInstance()->enableSearchingPrefixKeys();
}

function requireDatabaseVersion(string $requiredMigration): void
{
    try {
        $sql = 'SELECT id FROM gorp_migrations';
        $stmt = \libAllure\DatabaseFactory::getInstance()->query($sql);
        $versionRows = array_column($stmt->fetchAll(), 'id');
    } catch (Exception $e) {
        startupError('Sicroc has connected to the database successfully, but it could not read migrations table. <br /><br />This is probably because you have an empty database. Try running the database migration scripts to get up to date.');
    }

    natsort($versionRows);
    $databaseMigration = end($versionRows);

    if ($databaseMigration != $requiredMigration) {
        startupError('This version of Sicroc requires the database to be at migration: <strong>' . $requiredMigration . '</strong> and you currently have migration: <strong>' . $databaseMigration . '</strong>. You probably need to upgrade the database.');
    }
}

function setupDatabase($config)
{
    global $db; // Needed for libAllure Shortcuts

    $db = new \libAllure\Database($config->get('DB_DSN'), $config->get('DB_USER'), $config->get('DB_PASS'));
    \libAllure\DatabaseFactory::registerInstance($db);

    requireDatabaseVersion('27.userLastTcViewPage.sql');

    \libAllure\Session::setSessionName('sicroc');
    \libAllure\Session::setCookieLifetimeInSeconds(10000000);
    \libAllure\Session::start();

    $backend = new \libAllure\AuthBackendDatabase($db);
    \libAllure\AuthBackend::setBackend(new \libAllure\AuthBackendDatabase($db));
}


function hasPrivWrapper($a, $b)
{
    var_dump($a, $b);
    exit;
}

function setupTemplateEngine()
{
    global $tpl;

    $tplCacheDirectory = \Sicroc\Config::getInstance()->get('TEMPLATE_CACHE_DIRECTORY');

    $tpl = new \libAllure\Template(
        $tplCacheDirectory,
        __DIR__ . '/views/',
    );

    $tpl->setCaching(Smarty::CACHING_OFF);
    $tpl->setErrorReporting(E_ALL | E_STRICT);
    $tpl->registerModifier('count', 'count');
    $tpl->registerClass('\libAllure\Session', '\libAllure\Session');
    $tpl->registerModifier('hasPriv', ['\libAllure\Session', 'hasPriv']);
    $tpl->registerModifier('stripos', 'stripos');
    $tpl->registerModifier('getUser', '\libAllure\Session::getUser');
    $tpl->registerModifier('htmlentities', 'htmlentities');
    $tpl->registerModifier('var_dump', 'var_dump');
}

function setupTimezone()
{
    date_default_timezone_set(\Sicroc\Config::getInstance()->get('TIMEZONE', 'Europe/London'));
}
