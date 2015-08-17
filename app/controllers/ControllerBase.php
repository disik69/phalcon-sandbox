<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public $user;
    
    public function initialize()
    {
        $this->user = $this->session->get('user');
        
        $this->view->setVar('user', $this->user);
    }
}
