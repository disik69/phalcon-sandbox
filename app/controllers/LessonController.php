<?php

class LessonController extends \ControllerBase
{
    public function indexAction()
    {
        var_dump(\Lesson::find()->toArray());
    }
    
    public function createAction()
    {
        foreach (\Lesson::find() as $lesson) {
            $lesson->delete();
        }        
        
        $users = \User::find();
        
        for ($i = 0; $i < 10; $i++) {
            $lesson = new \Lesson;
            $lesson->name = $this->faker->word;
            $lesson->user = $users[rand(0, count($users) - 1)];
            $lesson->save();
            
            var_dump($lesson->toArray());
        }
    }
    
    public function addCollocationAction()
    {
        $collocations = \Collocation::find();
        $lessons = \Lesson::find();
        
        foreach ($lessons as $lesson) {
            $lesson->collocation = $collocations[rand(0, count($collocations) - 1)];
            $lesson->save();
            
            var_dump($lesson->toArray(), $lesson->getCollocation()->toArray());
        }
    }
}
