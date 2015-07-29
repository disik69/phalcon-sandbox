<?php

use \Phalcon\Tag;
use \Phalcon\Mvc\View;
use \Phalcon\Mvc\Router;
use \Phalcon\DI\FactoryDefault;

// Create a DI
$di = new FactoryDefault();
    
// Set Routing
$di->set('router', function () {
    $router = new Router(false);

    $router->notFound('Error::notFound');

    $router->add('/', 'User::index');
    $router->add('/users', 'User::index');
    $router->add('/users-create', 'User::create');

    $router->add('/{controller:collocation}/{action:[a-zA-Z0-9_-]+}/{id:(\d*)}')
    ->convert('action', function ($action) {
        return preg_replace('/[-_]/', '', strtoupper($action));
    });

    $routeGroup = new \Phalcon\Mvc\Router\Group();

    $routeGroup->add('/{controller:collocation}/{action:index}')
    ->setName('collocation');

    $router->mount($routeGroup);

    return $router;
});

// Set the database service
$di->set('db', function () use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    
    return new $dbclass(
        array(
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->name,
        )
    );
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
