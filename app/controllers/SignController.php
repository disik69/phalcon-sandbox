<?php

class SignController extends \ControllerBase
{
    public function inFormAction()
    {
        $this->view->pick('sign/in');
    }

    public function inAction()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = User::findFirst(array(
            'conditions' => 'email = :email: AND password = :password:',
            'bind' => array('email' => $email, 'password' => sha1($password))
        ));
        
        if ($user) {
            $this->session->set('user', array(
                'id' => $user->id,
                'email' => $user->email,
            ));
            
            return $this->response->redirect();
        } else {
            $this->flash->error('Wrong email/password');

            $this->dispatcher->forward(array(
                'controller' => 'sign',
                'action' => 'getIn',
            ));
        }

    }
    
    public function outAction()
    {
        $this->session->destroy();
        
        return $this->response->redirect('signin');
    }
}
