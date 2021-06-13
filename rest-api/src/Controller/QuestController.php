<?php
namespace App\Controller;
use Cake\Mailer\Mailer;
use Cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestController extends RestController
{
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Quest');
		$this->loadComponent('Fight');
		$this->loadModel("Quests");
		$this->loadModel("UserQuests");
		$this->loadModel("QuestSteps");
		$this->loadModel("UserQuestSteps");
		$this->loadModel("QuestBedingungen");
		$this->loadModel("JediUserChars");
		$this->loadModel("JediUserSkills");
		$this->loadModel("JediFightsPlayers");
    }
	
	public function index()
	{
		$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
		$char->skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		
		$this->Quest->aktiviere_quest();
		
		$quest_comp = $this->Quest->pruefe_auf_quests($this->Auth->User("id"), $char->location);
		
		$quests = $this->Quests->find()->where(["gelistet" => 1]);
		$this->set("quests",$quests);
		
		foreach($quests as $key => $quest)
		{
			$user_quest = $this->UserQuests->find()->where(["quest_id" => $quest->quest_id])->where(["user_id" => $this->Auth->User("id")])->first();
			
			//Wenn noch gar nichts in der DB
			if($user_quest == null)
			{
				$user_quest = $this->UserQuests->newEntity();
				$user_quest->quest_id = $quest->quest_id;
				$user_quest->user_id = $this->Auth->User("id");
				$user_quest->status = 0;
				
				//sowie alle steps einfügen
				//steps zählen
				$quest_steps = $this->QuestSteps->find()->where(["quest_id" => $quest->quest_id])->count();
				
				for ($i=1; $i < $quest_steps+1; $i++)
				{ 
					$steps = $this->UserQuestSteps->newEntity();
					$steps->user_id = $this->Auth->User("id");
					$steps->quest_id = $quest->quest_id;
					$steps->step_id = $i;
					$steps->status = 0;
					$this->UserQuestSteps->save($steps);
				}
				
			}
			
			//Wenn startbedingung erfüllt status des quest auf 1 sowie des ersten steps
			if($this->check_bedingung_quest($quest->quest_id) == true && $user_quest->status == 0)
			{
				$user_quest->status = 1;
				//Und den ersten Step auf 1 setzen
				$steps = $this->UserQuestSteps->newEntity();
				$steps->user_id = $this->Auth->User("id");
				$steps->quest_id = $quest->quest_id;
				$steps->step_id = 1;
				$steps->status = 1;
				$this->UserQuestSteps->save($steps);
			}
			
			$this->UserQuests->save($user_quest);
			
			//status als wort holen
			$user_quests[$user_quest["quest_id"]]["status"] = $this->getStatus($user_quest->status);
		}
		$this->set("user_quests",$user_quests);
	}
	
	public function quest($id)
	{
		$quest = $this->Quests->get($id);
		$user_quest = $this->UserQuests->find()->where(["quest_id" => $id])->where(["user_id" => $this->Auth->User("id")])->first();
				
		if($user_quest->status == 3)
		{
			$text = $quest->erledigttext;
		}
		else
		{
			$text = $quest->einleitungstext;
		}
		////////////////////////////////////////////////////////////
		$quest_steps = $this->QuestSteps->find()->where(["quest_id" => $id]);		
		$user_quest_steps = $this->UserQuestSteps->find()->where(["quest_id" => $id])->where(["user_id" => $this->Auth->User("id")])->toArray();
		
		foreach($user_quest_steps as $key => $user_quest_step)
		{
			$user_step[$user_quest_step["step_id"]]["status"] = $this->getStatus($user_quest_step["status"]);
			if($user_quest_step["status"] == 2 OR $user_quest_step["status"] == 3) {
				$user_step["active_step"] = $user_quest_step["step_id"];
			}
		}
		/////////////////////////////////////////////////////////////
		$this->set("user_quest",$user_quest);
		$this->set("user_step",$user_step);
		$this->set("quest_steps",$quest_steps);
		$this->set("status",$this->getStatus($user_quest->status));
		$this->set("text",$text);
		$this->set("quest",$quest);
	}
	public function step($quest_id = 0, $step_id = 0)
	{
		if($quest_id == 0 && $step_id == 0) {
			$quest_id = $this->request->getParam("pass")[0];
			$step_id = $this->request->getParam("pass")[1];
		}
		
		$quest_steps = $this->QuestSteps->find()->where(["quest_id" => $quest_id])->where(["step_id" => $step_id])->first();		
		$user_quest_steps = $this->UserQuestSteps->find()->where(["quest_id" => $quest_id])->where(["step_id" => $step_id])->first();
		
		if($user_quest_steps->status == 3)
		{
			$text = $quest_steps->erledigttext;
		}
		else
		{
			$text = $quest_steps->einleitungstext;
		}
		
		$this->set("text",$text);
		$this->set("quest_steps",$quest_steps);
		$this->set("user_quest_steps",$user_quest_steps);
	}
	
	function check_bedingung_quest($quest_id)
	{		
		$quest = $this->Quests->get($quest_id);
		$bedingung_id = $quest->startbedingung;
		$bedingung_func = $this->QuestBedingungen->find()->select(["funktion"])->where(["bedingungs_id" => $bedingung_id])->first();
		
		$startbedingung = $this->translate_func($bedingung_func->funktion);
		$startparameter = $quest->startparameter;
		
		if($startbedingung >= $startparameter)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	function getStatus($status)
	{
		switch ($status) {
			case '0':
				return "not available yet";
				break;
			case '1':
				return "available";
				break;
			case '2':
				return "active";
				break;
			case '3':
				return "done";
				break;
			default:
				return "NaN";
				break;
		}
	}
	function translate_func($func)
	{
		$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
		$char->skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		
		switch ($func) {
			case 'level':
				return $char->skills->level;
				break;
			case '3':
				return "done";
			default:
				return "NaN";
				break;
		}
	}

	public function wait()
	{
		$connection = ConnectionManager::get('default');
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$char->skills = $this->JediUserSkills->get($this->Auth->User("id"));
		
		$quest = $this->Quest->getStepText($this->Auth->User("id"));
		$form = null;
		$miss = null;
		$win = null;
		$lefttime = null;
		$time = null;
		$progress = null;
		$cancel = null;
		$countdown = null;
		$sucess = null;
		
		if (isset($_GET['cancel_quest']) && isset($_GET['sure']))
		{
		  $stmt = $connection->prepare("UPDATE jedi_user_chars set actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
		  $stmt->execute(['user_id' => $this->Auth->User("id")]);
		}
									
		//Bestätigt Countdown beginnt zu laufen
		if(isset($_GET['wait']) && $char->actionid == 0)
		{
			$time = time() + $quest['dauer'];
			$stmt = $connection->prepare("UPDATE jedi_user_chars SET actionid = '4', targetid = 0, targettime = '$time' WHERE userid = :user_id");
			$stmt->execute(['user_id' => $this->Auth->User("id")]);
		}
		
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		
		if($char->actionid == 0)	
		{			
			$form ="form";
		}
		
		//Countdown läuft warten bis fertig
		if($char->actionid == 4)
		{
			$time = $quest['dauer'];
			$lefttime = $char->targettime - time();
			$progress = ($time - $lefttime) * 100 / $time;
			
			if($progress > "100") $progress = "100";
			
			//Evtl. schon fertig
			if ($lefttime <= 0)
			{			
				$chance = $quest['chance'];
				$sucess = rand(1,100);
				
				if($quest['hidden'] == '0' AND ($chance > $sucess))
				{
					$miss = false;
					$win = true;
					
					$this->Quest->endQuestStep($this->Auth->User("id"), $quest['quest_id'], $quest['step_id']);
					
					$stmt = $connection->prepare("UPDATE jedi_user_chars SET actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
					$stmt->execute(['user_id' => $this->Auth->User("id")]);
				}
				elseif($quest['hidden'] == '0' AND ($chance < $sucess))
				{
					$miss = true;
					$win = false;
					
					$stmt = $connection->prepare("UPDATE jedi_user_chars SET actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
					$stmt->execute(['user_id' => $this->Auth->User("id")]);					
				}
			}
			else
			{
				$cancel =
				"
					form
				";		
			}
		}
		$this->set("response",array($form, $time, $char->targettime, $progress, $miss, $win, $cancel, $sucess));
	}

	public function simple() {
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$quest = $this->Quest->getStepText($this->Auth->User("id"));

		if($this->Quest->check_bedingung_quest_step($quest['quest_id'], $quest['step_id'], $this->Auth->User("id")))
			{
				//step details
				$step = $this->step($quest['quest_id'], $quest['step_id']);
				if($step["user_quest_steps"]["status"] != 3) {
					$this->Quest->endQuestStep($this->Auth->User("id"), $quest['quest_id'], $quest['step_id']);
				}

				$win = true;
				$miss = false;
			}
		else {
			$miss = true;
			$win = false;
		}

		$this->set("response",array(0,0,$char->targettime,0,$miss,$win,0,0));
	}
	
	public function fight() {
		$connection = ConnectionManager::get('default');
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$quest = $this->Quest->getStepText($this->Auth->User("id"));
		$fight = null;
		$miss = null;
		$win = null;

		//Bestätigt Countdown beginnt zu laufen -> fight starten
		if(isset($_GET['fight']) && $char->actionid == 0 && $char->energy >= 1)
		{
			$time = time() + $quest['dauer'];
			$stmt = $connection->prepare("UPDATE jedi_user_chars SET actionid = '4', targetid = 0, targettime = '$time' WHERE userid = :user_id");
			$stmt->execute(['user_id' => $this->Auth->User("id")]);
            
            $fight = $this->loadModel("JediFights")->newEntity();
            $fight->type = "duelnpc";
            $fight->type2 = "quest";
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
            $players->userid = $quest["gegner_id"];
            $players->teamid = 1;
            $players->position = 0;
            $players->npc = "y";
            $this->loadModel("JediFightsPlayers")->save($players);
		}

		if($char->energy < 1) {
			$this->set("error","energy");
		}

		$char = $this->JediUserChars->get($this->Auth->User("id"));

		//Countdown läuft warten bis fertig
		if($char->actionid == 4)
		{
			$lefttime = $char->targettime - time();
			
			//Evtl. schon fertig
			if ($lefttime <= 0)
			{			
				$fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
				//hier findet der fight statt
				$fight = $this->Fight->fight($fight_player[0]["fightid"]);
				if($fight[0] == "team_0") {
					$win = true;
					$miss = false;
					$this->Quest->endQuestStep($this->Auth->User("id"), $quest['quest_id'], $quest['step_id']);
				}
				//Wenn es egal ist ob man gewinnt oder verliert und trotzdem weiter kommt
				elseif($quest->erledigt_bedingung == 6 && $quest->erledigt_parameter == 0) {
					$win = false;
					$miss = true;
					$this->Quest->endQuestStep($this->Auth->User("id"), $quest['quest_id'], $quest['step_id']);
				}
				else {
					$win = false;
					$miss = true;
				}

				$stmt = $connection->prepare("UPDATE jedi_user_chars SET actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
				$stmt->execute(['user_id' => $this->Auth->User("id")]);
			}
			else
			{
				$cancel =
				"
					form
				";		
			}
		}

		$this->set("response",array(0,0,$char->targettime,0,$miss,$win,0,0,$quest,$fight));
	}
	public function puzzle() {
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$quest = $this->Quest->getStepText($this->Auth->User("id"));
		$miss = null;
		$win = null;

		if($quest['quest_id'] == 2) {
			$img = "door";
		}
		else {
			$img = "";
		}
		//Puzzle gelöst
		if($this->request->is("post") && $this->request->getData()["win"] == 1)
			{
				$this->Quest->endQuestStep($this->Auth->User("id"), $quest['quest_id'], $quest['step_id']);

				$win = true;
				$miss = false;
			}

		$this->set("response",array(0,0,$char->targettime,0,$miss,$win,0,0,$img));
	}
}
?>