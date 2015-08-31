<?php

class LessonController extends \ControllerBase
{
    public function listAction()
    {
        $this->assets->addCss('css/lesson/list.css');
        $this->assets->addJs('js/lesson/list.js');
        
        $list = User::findFirst($this->user['id'])->getLesson()->toArray();
        
        $this->view->setVar('list', $list);
        $this->view->pick('lesson/list');
    }
    
    public function createAction()
    {
        $name = $this->request->getPost('name');
        
        if (! empty($name)) {
            $lesson = new Lesson();
            
            $lesson->name = $name;
            $lesson->user = User::findFirst($this->user['id']);
            
            $lesson->save();
        }
        
        return $this->response->redirect();
    }
    
    public function deleteAction()
    {
        $id = $this->dispatcher->getParam('id');
        
        $result = User::findFirst($this->user['id'])->getLesson($id);
        $lesson = $result[0];
        
        if ($lesson) {
            $lesson->delete();
        }
        
        return $this->response->redirect();
    }
    
    public function editAction()
    {
        $this->assets->addCss('css/lesson/edit.css');
        $this->assets->addJs('js/lesson/edit.js');
        
        $id = $this->dispatcher->getParam('id');
        
        $lesson = $this->modelsManager->executeQuery(
            'SELECT cl.id, c.eng, IFNULL(cl.alt_rus, c.rus) rus ' .
            'FROM Lesson l ' . 
            'JOIN CollocationLesson cl ' . 
            'JOIN Collocation c ON c.id = cl.collocation_id ' . 
            'WHERE l.user_id = :userId: AND l.id = :lessonId:',
            array(
                'userId' => $this->user['id'],
                'lessonId' => $id,
            )
        )->toArray();
        
        $this->view->setVar('lesson', $lesson);
        $this->view->pick('lesson/edit');
    }
    
    public function addCollocationAction()
    {
        extract($_POST);
        
        $id = $this->dispatcher->getParam('id');
        
        if ((! empty($eng)) && (! empty($rus))) {
            $result = User::findFirst($this->user['id'])->getLesson($id);
            $lesson = $result[0];
            
            $collocationLesson = new CollocationLesson();

            $collocation = Collocation::findFirst(array(
                'conditions' => 'eng = :eng:',
                'bind' => array('eng' => $eng),
            ));
            
            if ($collocation) {
                if ($collocation->rus != $rus) {
                    $collocationLesson->alt_rus = $rus;
                }
            } else {
                $rawJson = file_get_contents(
                    'https://dictionary.yandex.net/api/v1/dicservice.json/lookup' . 
                    '?key=' . $this->config->dictionary_yandex_net->key .
                    '&lang=en-ru' .
                    '&text=' . urlencode($eng)
                );
                
                $response = json_decode($rawJson, true);
                
                $collocation = new Collocation();
                
                $collocation->eng = $eng;
                $collocation->rus = $rus;
                if (! empty($response['def'][0]['ts'])) {
                    $collocation->ts = $response['def'][0]['ts'];
                }
                $collocation->save();
            }
            
            $collocationLesson->lesson = $lesson;
            $collocationLesson->collocation = $collocation;
            $collocationLesson->save();
        }
        
        return $this->response->redirect('lesson/' . $id . '/edit');
    }
    
    public function deleteCollocationAction()
    {
        $lessonId = $this->dispatcher->getParam('lessonId');
        $collocationId = $this->dispatcher->getParam('collocationId');
        
        $result = User::findFirst($this->user['id'])->getLesson($lessonId);
        $lesson = $result[0];
        
        $result = $lesson->getCollocationLesson($collocationId);
        $collocationLesson = $result[0];
        
        if ($collocationLesson) {
            $collocationLesson->delete();
        }
        
        return $this->response->redirect('lesson/' . $lessonId . '/edit');
    }
    
    public function runAction()
    {
        $this->assets->addCss('css/lesson/run.css');
        $this->assets->addJs('js/lesson/run.js');
        
        $id = $this->dispatcher->getParam('id');
        
        $lesson = $this->modelsManager->executeQuery(
            'SELECT cl.id, c.eng, IFNULL(cl.alt_rus, c.rus) rus, c.ts ' .
            'FROM Lesson l ' . 
            'JOIN CollocationLesson cl ' . 
            'JOIN Collocation c ON c.id = cl.collocation_id ' . 
            'WHERE l.user_id = :userId: AND l.id = :lessonId:',
            array(
                'userId' => $this->user['id'],
                'lessonId' => $id,
            )
        )->toArray();
        
        shuffle($lesson);
        
        $this->view->setVar('lesson', $lesson);
        $this->view->pick('lesson/run');
    }
}

