<?php

use \Phalcon\Loader;
use \Phalcon\Tag;
use \Phalcon\Mvc\Url;
use \Phalcon\Mvc\View;
use \Phalcon\Mvc\Application;
use \Phalcon\DI\FactoryDefault;
use \Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {
    define('APP_PATH', realpath('..') . '/');
    
    include_once APP_PATH . 'vendor/autoload.php';
    
    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(
        array(
            APP_PATH . 'app/controllers/',
            APP_PATH . 'app/models/',
            APP_PATH . 'app/plugins/',
        )
    )->register();
    
    // Create a DI
    $di = new FactoryDefault();
    
    // Set the database service
    $di['db'] = function() {
        return new DbAdapter(array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "qwerty",
            "dbname"   => "english_roulette"
        ));
    };
    
    // Setting up the view component
    $di['view'] = function() {
        $view = new View();
        $view->setViewsDir(APP_PATH . 'app/views/'); 
        return $view;
    };
    
    // Setup the tag helpers
    $di['tag'] = function() {
        return new Tag();
    };
    
    $di->set('logger', function () {
        $logger = new \LoggerPhpAdapterPlugin(APP_PATH . 'app/logs/' . date('d-m-Y.') . 'php');
        
        return $logger;
    });
    
    $di->set('faker', function () {
        return \Faker\Factory::create();
    });
    
    // Handle the request
    $application = new Application($di);
    
    echo $application->handle()->getContent();
} catch (Exception $e) {
     echo "Exception: ", $e->getMessage();
}

