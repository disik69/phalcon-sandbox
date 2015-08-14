<?php

use \Phalcon\Loader;
use \Phalcon\Debug;
use \Phalcon\Mvc\Application;
use \Phalcon\Config\Adapter\Ini as ConfigIni;

include_once BASE_PATH . 'vendor/autoload.php';

/**
 * Read the configuration
 */
$config = new ConfigIni(BASE_PATH . 'app/config/config.ini');

if ($config->app->debug) {
    $debug = new Debug(); 
    $debug->listen();
}

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    array(
        BASE_PATH . 'app/controllers/',
        BASE_PATH . 'app/models/',
        BASE_PATH . 'app/plugins/',
        BASE_PATH . 'app/extends/',
        BASE_PATH . 'app/filters/',
    )
)->register();

/**
 * Load application services
 */
$di = require(BASE_PATH . 'bootstrap/di.php');

$application = new Application($di);

echo $application->handle()->getContent();