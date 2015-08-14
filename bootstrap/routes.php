<?php

$router = new \Phalcon\Mvc\Router(false);

//$router->add('/:controller', array(
//    'controller' => 1,
//    'action' => 'index',
//))->convert('controller', function ($controller) {
//    return strtolower($controller);
//});

//$router->add('/:controller/:action/:params', array(
//    'controller' => 1,
//    'action' => 2,
//    'params' => 3,
//))->convert('controller', function ($controller) {
//    return strtolower($controller);
//})->convert('action', function ($action) {
//    $wordsArr = preg_split('/[-_]/', $action, null, PREG_SPLIT_NO_EMPTY);
//    $wordsStr = strtolower(array_shift($wordsArr));
//
//    foreach ($wordsArr as $value) {
//        $wordsStr .= ucfirst(strtolower($value)) ;
//    }
//    
//    return $wordsStr;
//});

$router->add('/', array(
    'controller' => 'index',
    'action' => 'index',
));

$router->addGet('/signin', array(
    'controller' => 'sign',
    'action' => 'getIn',
));

$router->addPost('/signin', array(
    'controller' => 'sign',
    'action' => 'postIn',
));

$router->add('/signout', array(
    'controller' => 'sign',
    'action' => 'out',
));

$router->notFound(array(
    'controller' => 'error',
    'action' => 'notFound',
));

/**
 * ajax
 */
$ajax = new \Phalcon\Mvc\Router\Group();

$ajax->setPrefix('/ajax');
$ajax->beforeMatch(array(new AjaxFilter(), 'check'));

$ajax->add('/test', array(
    'controller' => 'index',
    'action' => 'ajaxTest',
));

$router->mount($ajax);

$router->removeExtraSlashes(true);

return $router;

