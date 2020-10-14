<?php
namespace App\Controller;
use Cake\Mailer\Mailer;
use Cake\Event\EventInterface;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestController extends AppController
{
	public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('main');
		$this->loadModel("Quests");
		$this->loadModel("UserQuests");
		$this->loadModel("QuestSteps");
		$this->loadModel("UserQuestSteps");
		$this->loadModel("QuestBedingungen");
    }
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Quest');
    }
	
	public function index()
	{
		$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
		$char->skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		
		$this->Quest->aktiviere_quest();
		
		$quest_comp = $this->Quest->pruefe_auf_quests($this->Auth->User("id"), $char->location);
		debug($quest_comp);
		
		$quests = $this->Quests->find()->where(["gelistet" => 1]);
		$this->set("quests",$quests);
		
		foreach($quests as $key => $quest)
		{
			$user_quest = $this->UserQuests->find()->where(["quest_id" => $quest->quest_id])->first();
			
			//Wenn noch gar nichts in der DB
			if($user_quest == null)
			{
				$user_quest = $this->UserQuests->newEmptyEntity();
				$user_quest->quest_id = $quest->quest_id;
				$user_quest->user_id = $this->Auth->User("id");
				$user_quest->status = 0;
				
				//sowie alle steps einfügen
				//steps zählen
				$quest_steps = $this->QuestSteps->find()->where(["quest_id" => $quest->quest_id])->count();
				
				for ($i=1; $i < $quest_steps+1; $i++)
				{ 
					$steps = $this->UserQuestSteps->newEmptyEntity();
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
				$steps = $this->UserQuestSteps->newEmptyEntity();
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
		$user_quest = $this->UserQuests->find()->where(["quest_id" => $id])->first();
				
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
		$user_quest_steps = $this->UserQuestSteps->find()->where(["quest_id" => $id])->toArray();
		
		foreach($user_quest_steps as $key => $user_quest_step)
		{
			$user_step[$user_quest_step["step_id"]]["status"] = $this->getStatus($user_quest_step["status"]);
		}
		/////////////////////////////////////////////////////////////
		$this->set("user_quest",$user_quest);
		$this->set("user_step",$user_step);
		$this->set("quest_steps",$quest_steps);
		$this->set("status",$this->getStatus($user_quest->status));
		$this->set("text",$text);
		$this->set("quest",$quest);
	}
	public function step()
	{
		$quest_id = $this->request->getParam("pass")[0];
		$step_id = $this->request->getParam("pass")[1];
		
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
}
?>