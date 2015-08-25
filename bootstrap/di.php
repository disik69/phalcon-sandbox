<?php

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;

// Create a DI
$di = new FactoryDefault();
    
// Set Routing
$di->set('router', function () {
    return require BASE_PATH . 'bootstrap/routes.php';
});

$di->set('dispatcher', function () {
    $eventsManager = new \Phalcon\Events\Manager();
    
    $eventsManager->attach('dispatch:beforeDispatch', new \SecurityPlugin);
    
    $eventsManager->attach('dispatch:beforeException', function ($event, $dispatcher, $exception) {
        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(array(
                    'controller' => 'error',
                    'action' => 'notFound'
                ));
                return false;
        }
    });
    
    $dispather = new Dispatcher();
    
    $dispather->setEventsManager($eventsManager);
    
    return $dispather;
});

$di->setShared('profiler', function () {
    return new \Phalcon\Db\Profiler();
});

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
            'dbname'   => $config->database->dbname,
            'options'  => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
            )
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

$di->set('session', function () {
   $session = new \Phalcon\Session\Adapter\Files();
   $session->start();
   
   return $session;
});

$di->set('flash', function () {
    $flash = new \Phalcon\Flash\Session(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );

    return $flash;
});

return $di;
