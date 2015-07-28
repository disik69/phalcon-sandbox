<?php

class LessonController extends \ControllerBase
{
    public function indexAction()
    {
        
    }
    
    public function createAction()
    {
        $users = \User::find();
        
        for ($i = 0; $i < 10; $i++) {
            $lesson = new \Lesson;
            $lesson->name = $this->faker->word;
            $lesson->user = $users[rand(0, count($users) - 1)];
            $lesson->save();
            
            var_dump($lesson->toArray());
        }
    }
}
