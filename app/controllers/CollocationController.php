<?php

class CollocationController extends \ControllerBase
{
    public function indexAction()
    {
        var_dump(\Collocation::find()->toArray());
    }
    
    public function createAction()
    {
        for ($i = 0; $i < 10; $i++) {
            $collocation = new \Collocation;
            $collocation->eng = $this->faker->word;
            $collocation->rus = $this->faker->word;
            $collocation->save();
            
            var_dump($collocation->toArray());
        }
    }
}
