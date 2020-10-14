<?php

namespace App\Controller;
use Cake\Event\EventInterface;

class ChatController extends AppController {

    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('main');
    }

    public function index()
    {
        
    }
}

?>