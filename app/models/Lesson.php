<?php

class Lesson extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('lessons');
        
        $this->belongsTo('user_id', 'User', 'id');
        
        $this->hasMany('id', 'CollocationLesson', 'lesson_id');
        
        $this->hasManyToMany('id', 'CollocationLesson', 'lesson_id', 'collocation_id', 'Collocation', 'id');
    }
}
