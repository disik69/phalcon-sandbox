<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        if ($user = $this->session->get('user')) {
            $this->view->setVar('user', $user);
        }
    }
}
