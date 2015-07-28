<?php

class Collocation extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('collocations');
        
        $this->hasMany('id', 'CollocationLesson', 'collocation_id');
        
        $this->hasManyToMany('id', 'CollocationLesson', 'collocation_id', 'lesson_id', 'Lesson', 'id');
    }
}
