<?php

class CollocationController extends \ControllerBase
{
    public function indexAction()
    {
        var_dump(
            $this->url->get(
                array(
                    'for' => 'collocation',
                    'controller' => 'collocation',
                    'action' => 'index',
                )
            ),
            $this->dispatcher->getParams(),
            \Collocation::find()->toArray()
        );
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
