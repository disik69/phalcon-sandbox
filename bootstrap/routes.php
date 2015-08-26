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
    'controller' => 'lesson',
    'action' => 'list',
));

$router->addPost('/lesson/create', array(
    'controller' => 'lesson',
    'action' => 'create',
));

$router->add('/lesson/(\d+)/delete', array(
    'controller' => 'lesson',
    'action' => 'delete',
    'id' => 1,
))->setName('delete-lesson');

$router->add('/lesson/(\d+)/edit', array(
    'controller' => 'lesson',
    'action' => 'edit',
    'id' => 1,
))->setName('edit-lesson');

$router->addPost('/lesson/(\d+)/add-collocation', array(
    'controller' => 'lesson',
    'action' => 'addCollocation',
    'id' => 1,
))->setName('add-collocation-lesson');

$router->add('/lesson/(\d+)/delete-collocation/(\d+)', array(
    'controller' => 'lesson',
    'action' => 'deleteCollocation',
    'lessonId' => 1,
    'collocationId' => 2,
))->setName('delete-collocation-lesson');

$router->add('/lesson/(\d+)/run/(direct|inverse)', array(
    'controller' => 'lesson',
    'action' => 'run',
    'id' => 1,
    'set' => 2,
))->setName('run-lesson');

$router->add('/signin', array(
    'controller' => 'sign',
    'action' => 'inForm',
));

$router->addPost('/signin', array(
    'controller' => 'sign',
    'action' => 'in',
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

$ajax->addPost('/test', array(
    'controller' => '',
    'action' => '',
));

$router->mount($ajax);

$router->removeExtraSlashes(true);

return $router;

