<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->view->disable();        
    }
}
