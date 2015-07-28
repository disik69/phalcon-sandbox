<?php

namespace Phalcon\Mvc\Model;

interface BehaviorInterface
{

    /**
     * Phalcon\Mvc\Model\Behavior
     *
     * @param array $options 
     */
	public function __construct($options = null);

    /**
     * This method receives the notifications from the EventsManager
     *
     * @param string $type 
     * @param \Phalcon\Mvc\ModelInterface $model 
     */
	public function notify($type, \Phalcon\Mvc\ModelInterface $model);

    /**
     * Calls a method when it's missing in the model
     *
     * @param \Phalcon\Mvc\ModelInterface $model 
     * @param string $method 
     * @param array $arguments 
     */
	public function missingMethod(\Phalcon\Mvc\ModelInterface $model, $method, $arguments = null);

}
