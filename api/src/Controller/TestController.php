<?php

namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Datasource\ConnectionManager;

class TestController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {	
        $this->viewBuilder()->setLayout('main');
		$this->loadModel("JediUserChars");
		$this->loadModel("JediUserSkills");	
        $this->loadModel("Accounts");
        $this->loadModel("JediUserMessages");	
		$this->connection = ConnectionManager::get('default');
    }

    public function index()
    {
        #$objChatroom = new chatrooms;
        #$chatrooms   = $objChatroom->getAllChatRooms();
        #$chatrooms = $this->JediUserMessages->find()->where(['send_to' => 0])->order(["id" => "DESC"])->limit(50)->all();
		$stmt = $this->connection->prepare("SELECT * FROM (
											SELECT * FROM jedi_user_messages WHERE send_to = 0 ORDER BY id DESC LIMIT 50
											) sub
											ORDER BY id ASC");
		$stmt->execute();
		$chatrooms = $stmt->fetchAll('assoc');

        foreach($chatrooms as $key => $msg)
        {
            $chatrooms[$key]["send_from_name"] = $this->JediUserChars->get($msg["send_from"])->username;
			#$chatrooms[$key]["send"] = ;
        }
        
        $this->set("chatrooms",$chatrooms);

        #$objUser = new users;
        #$users   = $objUser->getAllUsers();
        $users = $this->JediUserChars->find()->all();
        $this->set("users",$users);
		
		$this->set("char",$this->JediUserChars->get($this->Auth->User("id")));
    }
}