<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->overload();

/**
 * Bugsnag. Before framework
 */
//if (getenv('APPLICATION_ENV') !== 'development') {
//    $bugsnag = \Bugsnag\Client::make(getenv('BUGSNAG_KEY'));
//    $bugsnag->getConfig()->setReleaseStage(getenv('APPLICATION_ENV'));
//    $bugsnag->getConfig()->setNotifyReleaseStages(['production', 'staging']);
//    \Bugsnag\Handler::register($bugsnag);
//}

$appName = (!empty(getenv('APP_NAME'))) ? strtoupper(getenv('APP_NAME')) : '';
$name = sprintf('%s%s', $appName, $_SERVER['REQUEST_URI']);

if (extension_loaded('newrelic')) {
    newrelic_name_transaction($name);
}

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Zend\Expressive\Application $app */
    $app = $container->get(\Zend\Expressive\Application::class);
    $factory = $container->get(\Zend\Expressive\MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
