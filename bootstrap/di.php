<?php

use \Phalcon\Tag;
use \Phalcon\Mvc\View;
use \Phalcon\Mvc\Router;
use \Phalcon\DI\FactoryDefault;

// Create a DI
$di = new FactoryDefault();
    
// Set Routing
$di->set('router', function () {
    $router = new Router();
    
    $router->add('/', 'User::index');
    
    $router->add('/:controller(/*)', array(
        'controller' => 1, 
        'action' => 'index',
    ));
    
    $router->add('/:controller/:action/:params', array(
        'controller' => 1,
        'action' => 2,
        'params' => 3,
    ))->convert('action', function ($action) {
        return preg_replace('/[-_]/', '', $action);
    });
    
    $router->notFound('Error::notFound');

    return $router;
});

$di->set('profiler', function () {
    return new \Phalcon\Db\Profiler();
}, true);

// Set the database service
$di->set('db', function () use ($config, $di) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    
    $eventsManager = new \Phalcon\Events\Manager();

    $profiler = $di->get('profiler');

    $eventsManager->attach('db', function ($event, $connection) use ($profiler) {
        if ($event->getType() == 'beforeQuery') {
            $profiler->startProfile($connection->getSQLStatement());
        }
        if ($event->getType() == 'afterQuery') {
            $profiler->stopProfile();
        }
    });
    
    $connection = new $dbclass(
        array(
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->name,
        )
    );
    
    $connection->setEventsManager($eventsManager);
    
    return $connection;
});

// Setting up the view component
$di->set('view', function () {
    $view = new View();
    $view->setViewsDir(BASE_PATH . 'app/views/'); 
    return $view;
});

// Setup the tag helpers
$di->set('tag', function () {
    return new Tag();
});

$di->set('logger', function () {
    $logger = new \LoggerPhpAdapter(BASE_PATH . 'app/logs/' . date('d-m-Y.') . 'php');

    return $logger;
});

$di->set('faker', function () {
    return \Faker\Factory::create();
});

$di->set('url', function () {
    return new \UrlProvider();
});

return $di;
