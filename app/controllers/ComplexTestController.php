<?php

class ComplexTestController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $this->view->pick('complex-test/index');
    }
    
    public function addOneAction()
    {
        $this->view->pick('complex-test/add-one');
    }
}

