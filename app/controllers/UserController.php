<?php

class UserController extends \ControllerBase
{
    public function indexAction()
    {
        var_dump(\User::find()->toArray());
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
    
    public function getStatisticAction()
    {
        $phqlQuery = 'SELECT u.id, l.* FROM User u JOIN Lesson l';
//        $phqlQuery = 'SELECT u.* FROM User u';
        $statistic = $this->modelsManager->executeQuery($phqlQuery);
        
        var_dump(get_class($statistic));
        
        foreach ($statistic as $row) {
//            if ($row->u->id == 11) {
//                $row->u->email = 'disik1@my.com';
//                $row->u->save();
//            }
            
            var_dump(get_class($row));
        }
        
    }
}
