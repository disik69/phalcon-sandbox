<?php

class User extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('users');
        
        $this->hasMany('id', 'Lesson', 'user_id');
    }
}
