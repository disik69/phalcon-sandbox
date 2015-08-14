<?php

class ErrorController extends \ControllerBase
{
    public function notFoundAction()
    {
        $this->view->pick('error/not-found');
    }
    
    public function unauthorizedAction()
    {
        return $this->response->setStatusCode(401);
    }
}

