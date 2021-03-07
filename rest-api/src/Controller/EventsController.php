<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;



class EventsController extends RestController {
	
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Fight');
		$this->loadComponent("Treasure");
        $this->viewBuilder()->setLayout('main');
		$this->loadModel("JediUserChars");
        $this->loadModel("JediEventsSingleRanking");
		$this->loadModel("JediEventsHighscoreRanking");
        $this->loadModel("JediEventsSingleFightReports");
        $this->loadModel("JediEvents");
		$this->loadModel("JediUserStatistics");
		$this->loadModel("JediItemsJewelry");
		$this->loadModel("JediItemsWeapons");
    }

    public $paginate = [
        // Other keys here.
        'limit' => 10
    ];

    public function ranking()
    {
        $cur_event = $this->JediEvents->find()->where(["type" => "single"])->last();
        if($cur_event == null) {
            $this->set("no",true);
        }
        else {
            $this->set("no",false);
        }
        $this->set("cur_event",$cur_event);

        $event = $this->JediEventsSingleRanking->find()->where(["userid" => $this->Auth->User("id")])->first();
        
        //rejuice
        if(!empty($event))
        {
            $time = FrozenTime::now();
            $time->nice('Europe/Berlin', 'de-DE');
            $now = $time->i18nFormat('dd.MM.yyyy','Europe/Berlin', 'de-DE');
            $last_reset = $event->last_reset->i18nFormat('dd.MM.yyyy','Europe/Berlin', 'de-DE');
            if($now != $last_reset)
            {
                $event->attemps = 5;
                $event->last_reset = $time->i18nFormat('YYYY-MM-dd','Europe/Berlin', 'de-DE');
                $this->JediEventsSingleRanking->save($event);
            }
        }

        //beitreten
        if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "join" && empty($event) && !empty($cur_event))
        {
            $new = $this->JediEventsSingleRanking->newEntity();
            $new->userid = $this->Auth->User("id");
            $new->points = 500;
            $new->attemps = 5;
            $new->fights = 0;
            $new->last_reset = time();
            $new->event_id = $cur_event["id"];
            $this->JediEventsSingleRanking->save($new);
            $this->redirect(["action" => "ranking"]);
        }
        //init duel
        if($this->request->is("post") && $this->request->getData("userid") != "" && $event->attemps > 0)
        {  
            $userid = $this->request->getData("userid");
            
            $fight = $this->loadModel("JediFights")->newEntity();
            $fight->type = "duel";
            $fight->type2 = "event";
            $fight->opentime = time();
            $fight->startin = 0;
            $fight->status = "fighting";
            $this->loadModel("JediFights")->save($fight);
            $fightid = $this->loadModel("JediFights")->find()->select(["fightid"])->last();

            //Attacker
            $players = $this->loadModel("JediFightsPlayers")->newEntity();
            $players->fightid = $fightid["fightid"];
            $players->userid = $this->Auth->User("id");
            $players->teamid = 0;
            $players->position = 0;
            $players->npc = "n";
            $this->loadModel("JediFightsPlayers")->save($players);

            //Gegner
            $players = $this->loadModel("JediFightsPlayers")->newEntity();
            $players->fightid = $fightid["fightid"];
            $players->userid = $userid;
            $players->teamid = 1;
            $players->position = 0;
            $players->npc = "n";
            $this->loadModel("JediFightsPlayers")->save($players);
            $defender_event = $this->JediEventsSingleRanking->get($userid);
            $defender_event->fights += 1;
            $this->JediEventsSingleRanking->save($defender_event);

            $fight = $this->Fight->fight($fight["fightid"]);

            $event->fights += 1;
            $event->attemps -= 1;
            $this->JediEventsSingleRanking->save($event);
			
        }

        //getting fight_reports
        $fight_reps_a = $this->JediEventsSingleFightReports->find()->where(["attacker" => $this->Auth->User("id")])->order(["zeit" => "DESC"])->limit(15);
        $this->set("fight_reps_a",$fight_reps_a);
        $fight_reps_d = $this->JediEventsSingleFightReports->find()->where(["defender" => $this->Auth->User("id")])->order(["zeit" => "DESC"])->limit(15);
        $this->set("fight_reps_d",$fight_reps_d);

        $players = $this->JediEventsSingleRanking->find()->select(["userid", "points", "fights"])->order(["points" => "DESC"])->order(["fights" => "ASC"]);
        $players->leftJoin(
                    ["JediUserChars" => "jedi_user_chars"],
                    ["JediUserChars.userid = JediEventsSingleRanking.userid"])
                ->select(["JediUserChars.username"]);
		
        $this->set("event",$event);
        $this->set("players",$this->paginate($players));

        $player_count = $this->JediEventsSingleRanking->find()->select(["userid", "points", "fights"])->order(["points" => "DESC"])->order(["fights" => "ASC"])->count();
        $this->set("count",$player_count);
    }
	
	public function milestone()
	{
		$cur_event = $this->JediEvents->find()->where(["type" => "highscore"])->last();
		$highscore_type = $cur_event->highscore_type;
		
        if($cur_event == null) $this->set("no","no");
        $this->set("cur_event",$cur_event);
		
		$event_user = $this->JediEventsHighscoreRanking->find()->where(["userid" => $this->Auth->User("id")])->first();
		
		//beitreten
        if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "join" && empty($event) && !empty($cur_event))
        {
            $new = $this->JediEventsHighscoreRanking->newEmptyEntity();
            $new->userid = $this->Auth->User("id");
            $new->start_para = $this->JediUserStatistics->get($new->userid)->$highscore_type;
			$new->joined = date("Y-m-d H:i:s");
            $new->event_id = $cur_event["id"];
            $this->JediEventsHighscoreRanking->save($new);
            $this->redirect(["action" => "milestone"]);
        }
		
		//Update now_para
		$event_players = $this->JediEventsHighscoreRanking->find();
		foreach($event_players as $key => $event_player)
		{
			$event_u = $this->JediEventsHighscoreRanking->get($event_player->userid);
			$user = $this->JediUserStatistics->get($event_player->userid);
			$event_u->now = $user->$highscore_type;
			$this->JediEventsHighscoreRanking->save($event_u);
		}
		
		//calc ranking
		$players = $this->JediEventsHighscoreRanking->find()->select(["userid", "start_para", "now", "joined", "points" => "now - start_para"])->order(["points" => "DESC"])->order(["joined" => "DESC"]);
        $players->leftJoin(
                    ["JediUserChars" => "jedi_user_chars"],
                    ["JediUserChars.userid = JediEventsHighscoreRanking.userid"])
                ->select(["JediUserChars.username"]);
				
		$this->set("highscore_type",$highscore_type);
        $this->set("players",$this->paginate($players));
		$this->set("event",$event_user);
		$this->set("Auth",$this->Auth->User());
		
		$loot = $this->price($cur_event->id, $cur_event->price_type);
		$this->set("loot",$loot);
		
	}
	
	public function item()
	{
		$i = $this->request->getQuery('i');
		$item = $this->Treasure->loot("ranc4", 20, "raid", "price", $i);
		$this->set("loot",$item);
	}
	
	function price($eventid, $price_type)
	{
		if($price_type == "rings")
		{
			$prices = $this->JediItemsJewelry->find()->where(["sizex" => $eventid])->all();
		}
		else
		{
			$prices = $this->JediItemsWeapons->find()->where(["sizex" => $eventid])->all();
		}
		return $prices;		
	}
}

?>