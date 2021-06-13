<?php

namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Rest\Controller\RestController;
use Cake\Datasource\ConnectionManager;

class MessagesController extends RestController {

    public function index()
    {
        $this->LoadModel('JediUserMessages');

        $this->set('JediUserChars',$this->LoadModel('JediUserChars'));

        $chatList = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_from' => $this->Auth->User("id")]]])->group(['send_to', 'send_from'])->all();
        foreach ($chatList as $key => $chatUser) {
            if($chatUser->send_to == $this->Auth->User("id"))
            {
                $user[] = $chatUser->send_from;
            }
            else
            {
                $user[] = $chatUser->send_to;
            }
            
        }
        $this->set('chatList',array_unique($user));
     
        //einzelner Chat wurde ausgewählt
        if($this->request->getParam('pass'))
        {
            $user = $this->request->getParam('pass')[0];
            $this->set('view','ya');
            $this->set('user',$user);

            $messages = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_to' => $user]]])->where(['OR' => [['send_from' => $user], ['send_from' => $this->Auth->User("id")]]])->order(['send' => 'desc'])->all();
            $this->set('messages',$messages);
        }
        else
        {
            $this->set('view','nah');
        }
    }
    public function receive()
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->LoadModel('JediUserMessages');
        $this->set('JediUserChars',$this->LoadModel('JediUserChars'));

        $chatList = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_from' => $this->Auth->User("id")]]])->group(['send_to', 'send_from'])->all();
        foreach ($chatList as $key => $chatUser) {
            if($chatUser->send_to == $this->Auth->User("id"))
            {
                $user[] = $chatUser->send_from;
            }
            else
            {
                $user[] = $chatUser->send_to;
            }
            
        }
        $this->set('chatList',array_unique($user));

        if($this->request->getData("type") == "receive")
        {
            $user = $this->request->getParam('pass')[0];
            $this->set('user',$user);

            $messages = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_to' => $user]]])->where(['OR' => [['send_from' => $user], ['send_from' => $this->Auth->User("id")]]])->order(['send' => 'desc'])->all();
            $this->set('messages',$messages); 
            $this->set('__serialize',['messages']);               
        }  
    }
    public function chat()
    {
        $this->LoadModel('JediUserMessages');
        $this->set('JediUserChars',$this->LoadModel('JediUserChars'));

        $chatList = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_from' => $this->Auth->User("id")]]])->where(['send_to !=' => "0"])->group(['send_to', 'send_from'])->all();
        foreach ($chatList as $key => $chatUser) {
            if($chatUser->send_to == $this->Auth->User("id"))
            {
                $user[] = $chatUser->send_from;
            }
            else
            {
                $user[] = $chatUser->send_to;
            }
            
        }
        if(isset($user))
        {
            $this->set('chatList',array_unique($user));
            //unread messages
            $user_send_from = array_unique($user);
            foreach ($user_send_from as $key => $user_from) {
                $unread_by_user = $this->JediUserMessages->find()->where(['send_to' => $this->Auth->User("id"), 'send_from' => $user_from, 'status' => '0'])->all();
                $unread[$user_from] = count($unread_by_user);
            }
            $this->set('unread',$unread);
        }
        
        
    }
    public function send()
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->LoadModel('JediUserMessages');
        $this->set('JediUserChars',$this->LoadModel('JediUserChars'));

        if($this->request->is('ajax'))
        {
            if($this->request->getData('type') == "send")
            {
                $messages = $this->JediUserMessages->newEmptyEntity();
                $messages->send_from = $this->Auth->User("id");
                $messages->send_to = $this->request->getData('send_to');
                $messages->text = $this->request->getData('text');
                $messages->status = 0;
                $time = FrozenTime::now();
                $messages->send = $time->i18nFormat('YYYY-MM-dd HH:mm:ss', 'Europe/Paris');
                $this->JediUserMessages->save($messages);
            }    
                $unread_by_user = $this->JediUserMessages->find()->where(['send_to' => $this->Auth->User("id"), 'send_from' => $this->request->getData('send_to'), 'status' => '0'])->all();
                foreach ($unread_by_user as $key => $unread_m) {
                    $message = $this->JediUserMessages->get($unread_m->id);
                    $message->status = 1;
                    $this->JediUserMessages->save($message);
                }
                $messages = $this->loadHistory($this->request->getData('send_to'));
                $this->set('user',$this->request->getData('send_to'));
                $this->set('messages',$messages);
            
        } 
    }
    private function loadHistory($id)
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->LoadModel('JediUserMessages');
        $this->set('JediUserChars',$this->LoadModel('JediUserChars')); 
        
        $messages = $this->JediUserMessages->find()->where(['OR' => [['send_to' => $this->Auth->User("id")], ['send_to' => $id]]])->where(['OR' => [['send_from' => $id], ['send_from' => $this->Auth->User("id")]]])->order(['send' => 'desc'])->all();
        return $messages; 
    }
    public function groupChat()
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->LoadModel('JediUserMessages');

        if($this->request->is('ajax'))
        {
            if($this->request->getData('action') == "insert_data")
            {
                $messages = $this->JediUserMessages->newEmptyEntity();
                $messages->send_from = $this->Auth->User("id");
                $messages->send_to = 0;
                $messages->text = $this->request->getData('chat_message');
                $messages->status = 1;
                $time = FrozenTime::now();
                $messages->send = $time->i18nFormat('YYYY-MM-dd HH:mm:ss', 'Europe/Paris');
                $this->JediUserMessages->save($messages);

                echo $this->fetch_group_chat_history();
            }
            if($this->request->getData('action') == "fetch_data")
            {
                echo $this->fetch_group_chat_history();
            }
        }
    }
    function fetch_group_chat_history()
    {
        $this->LoadModel('JediUserMessages');
        $users = $this->LoadModel('JediUserChars');

        $result = $this->JediUserMessages->find()->where(['send_to' => 0])->order(['send' => 'DESC'])->limit(50)->toArray();
    
     $output = '<ul class="list-unstyled">';
     foreach($result as $row)
     {
      $user_name = '';
      if($row["send_from"] == $this->Auth->User("id"))
      {
       $user_name = '<b class="text-success">Du</b>';
      }
      else
      {
       $user_name = '<b class="text-danger">'.$users->get($row["send_from"])->username.'</b>';
      }
    
      $output .= '
    
      <li style="border-bottom:1px dotted #ccc">
       <p class="m-0">'.$user_name.' - '.$row['text'].' 
        <div align="right">
         - <small><em>'.$row['send'].'</em></small>
        </div>
       </p>
      </li>
      ';
     }
     $output .= '</ul>';
     return $output;
    }

    public function search()
    {
        $connection = ConnectionManager::get('default');

        $searched_user = $connection->execute('SELECT CAST(userid AS UNSIGNED) as userid FROM jedi_user_chars WHERE BINARY username = :username AND status = "active"', ['username' => $this->request->getData('suchbegriff')])->fetch("assoc");        
        
        if(empty($searched_user))
        {
            
        }
        else
        {
            $this->set("userid",$searched_user["userid"]);
        }
    }
}

?>