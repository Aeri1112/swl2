<?php

namespace App\Controller;
use Cake\Event\EventInterface;

class ScoreController extends AppController {

    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('main');
    }

    public function player()
    {
        $this->set("players",$this->loadModel("JediUserChars")->find()->where(["status" => "active"])->all());
        $this->set("userid",$this->Auth->User("id"));
    }

}