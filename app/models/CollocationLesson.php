<?php

class CollocationLesson extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('collocation_lesson');
        
        $this->belongsTo('lesson_id', 'Lesson', 'id');
        
        $this->belongsTo('collocation_id', 'Collocation', 'id');
    }
}
