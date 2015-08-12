<?php

class ErrorController extends \ControllerBase
{
    public function notFoundAction()
    {
        $this->view->pick('error/not-found');
    }
}

