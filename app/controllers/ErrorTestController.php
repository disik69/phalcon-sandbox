<?php

class ErrorTestController extends \Phalcon\Mvc\Controller
{
    public function notFoundAction()
    {
        $this->view->setVar('test_var', '!!!');
                
        $this->view->pick('error-test/not-found');
    }
}

