<?php

class SignController extends \ControllerBase
{
    public function getInAction()
    {
        $this->view->pick('sign/in');
    }

    public function postInAction()
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
                'email' => $user->name
            ));
            
            $direct = array('controller' => 'index', 'action' => 'index');
        } else {
            $this->flash->error('Wrong email/password');

            $direct = array('controller' => 'sign', 'action' => 'getIn');
        }

        $this->dispatcher->forward($direct);
    }
}
