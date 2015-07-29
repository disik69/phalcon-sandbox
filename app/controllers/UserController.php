<?php

class UserController extends \ControllerBase
{
    public function indexAction()
    {
        $users = \User::find();
        
        var_dump(count($users));
    }
    
    public function createAction()
    {
        foreach (\User::find() as $user) {
            $user->delete();
        }
        
        for ($i = 0; $i < 10; $i++) {
            $user = new \User;
            $user->email = $this->faker->email;
            $user->password = sha1($user->email);
            $user->save();
            
            var_dump($user->toArray());
        }
    }
}
