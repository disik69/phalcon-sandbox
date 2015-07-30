<?php

use \Phalcon\Loader;
use \Phalcon\Mvc\Application;
use \Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    include_once BASE_PATH . 'vendor/autoload.php';
    
    /**
     * Read the configuration
     */
    $config = new ConfigIni(BASE_PATH . 'app/config/config.ini');
    
    // Register an autoloader
    $loader = new Loader();
    
    $loader->registerDirs(
        array(
            BASE_PATH . 'app/controllers/',
            BASE_PATH . 'app/models/',
            BASE_PATH . 'app/plugins/',
            BASE_PATH . 'app/extends/',
        )
    )->register();

    /**
     * Load application services
     */
    $di = require(BASE_PATH . 'bootstrap/di.php');
    
    $application = new Application($di);
    
    echo $application->handle()->getContent();
} catch (Exception $e) {
    if ($config->app->debug) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
}

