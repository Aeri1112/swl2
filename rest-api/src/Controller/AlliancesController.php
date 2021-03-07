<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Mailer\Mailer;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Rest\Controller\RestController;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlliancesController extends RestController
{
	public function initialize(): void
    {
		parent::initialize();
		$this->loadModel("JediUserChars");
		$this->loadModel("JediUserSkills");	
		$this->loadModel("JediAlliances");		
		$this->loadModel("JediFightsPlayers");
		$this->loadModel("JediFights");
		$this->loadModel("Accounts");
        $this->loadComponent('maxHealth');
        $this->loadComponent('Paginator');
    }
	
	public function index()
	{
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$char->skills = $this->JediUserSkills->get($this->Auth->User("id"));

		//check alli
		if($char->alliance == 0)
		{
			$no_alliance = true;
			//List all allis
			$alliances = $this->JediAlliances->find();
			$alliances = $alliances->selectAllExcept($this->JediAlliances,["cash","attemps","last_reset","password"]);
			$this->set("alliances",$this->paginate($alliances));
		}
		else
		{
			$no_alliance = false;
			
			$alliance = $this->JediAlliances->find()->select(["id","pic","description","name","short","last_reset"])->where(["id" => $char->alliance])->first();
			$this->set("alliance",$alliance);
			
			//rejuice raid
			$time = FrozenTime::now();
            $time->nice('Europe/Berlin', 'de-DE');
            $now = $time->i18nFormat('dd.MM.yyyy','Europe/Berlin', 'de-DE');
            $last_reset = $alliance->last_reset->i18nFormat('dd.MM.yyyy','Europe/Berlin', 'de-DE');
            if($now != $last_reset)
            {
                $alliance->attemps = 5;
                $alliance->last_reset = $time->i18nFormat('YYYY-MM-dd','Europe/Berlin', 'de-DE');
                $this->JediAlliances->save($alliance);
            }
			
			//check raid
			$alliance_fight = $this->JediFights->find()->where(["alliance" => $alliance->id])->where(["status" => "open"])->first();
			$this->set("alli_fight",$alliance_fight);
			
			//check running raid
			$raid_running = $this->JediFights->find()->where(["alliance" => $alliance->id])->where(["status" => "preparing"])->first();
			if($raid_running != null)
			{
				$this->set("raid_running",true);
			}
			else
			{
				$this->set("raid_running",false);
			}

			if($char->userid == $alliance->leader OR $char->userid == $alliance->coleader)
			{
				$this->set("is_leader",true);
			}
			else
			{
				$this->set("is_leader",false);
			}
		}
		$this->set("no_alliance",$no_alliance);
	}
	
	public function all()
	{
		//List all allis
		$alliances = $this->JediAlliances->find();
		$alliances = $alliances->selectAllExcept($this->JediAlliances,["cash","attemps","last_reset","password"]);
		$this->set("alliances",$this->paginate($alliances));
	}
	
	public function create()
	{
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$char->skills = $this->JediUserSkills->get($this->Auth->User("id"));
		
		//check alli
		if($char->alliance == 0)
		{
			$no_alliance = true;
		}
		else
		{
			$no_alliance = false;
		}
		
		//create
		if($this->request->is("post"))
		{
			if($char->cash > 5000)
			{
				$name_taken = $this->JediAlliances->find()->where(["name" => $this->request->getData("a_name")])->first();
				$tag_taken = $this->JediAlliances->find()->where(["short" => $this->request->getData("a_tag")])->first();
				
				if($name_taken != null OR $tag_taken != null)
				{
					$this->Flash->error("Alliancename or Tag allready taken");
				}
				else
				{
					$alliance = $this->JediAlliances->newEmptyEntity();
					$alliance->name = $this->request->getData("a_name");
					$alliance->short = $this->request->getData("a_tag");
					$alliance->leader = $this->Auth->User("id");
					$alliance->password = $this->request->getData("password");
					$alliance->alignment = 1;
					$alliance->attemps = 5;
					$alliance->last_reset = time();
					
					$this->JediAlliances->save($alliance);
					
					#$char->cash -= 5000;
					$char->alliance = $this->JediAlliances->find()->last()->id;
					$this->JediUserChars->save($char);
					
					$this->redirect(["action" => "index"]);
				}		
			}
			else
			{
				$this->Flash->error("You have not enough money");
			}
		}
		$this->set("no_alliance",$no_alliance);
	}
	public function join($id)
	{
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		
		$alliance = $this->JediAlliances->get($id);
		
		//check alli
		if($char->alliance == 0)
		{
			$no_alliance = true;
		}
		else
		{
			$no_alliance = false;
		}
		
		if($this->request->is("post") && $no_alliance == true)
		{
			$time = FrozenTime::now();
			if($char->alli_cooldown > $time)
			{
				$this->set("error","Your cooldown is not over");
			}
			elseif($this->request->getData("password") == $alliance->password)
			{
				$char->alliance = $id;
				$this->JediUserChars->save($char);
			}
			else
			{
				$this->set("error","Wrong password");
			}
		}
		$this->set("no_alliance",$no_alliance);
	}
	
	public function view($id)
	{	
		$alliance = $this->JediAlliances->find()->select(["id","coleader","leader","name","short"])->where(["id" => $id])->first();
		
		//actions
		if($this->request->is("post"))
		{
			$change_user = $this->request->getData("userid");
			
			//change leader
			if($this->request->getData("selectedOption") == "leader" && $this->Auth->User("id") == $alliance->leader)
			{
				//Prüfung ob der Char Coleader ist. Dann Coleader auf 0 setzen
				if($alliance->coleader == $change_user)
				{
					$alliance->coleader = 0;
				}
				$alliance->leader = $change_user;
				$this->JediAlliances->save($alliance);
			}
			//change coleader
			elseif($this->request->getData("selectedOption") == "coleader" && ($this->Auth->User("id") == $alliance->coleader OR $this->Auth->User("id") == $alliance->leader))
			{
				//Prüfung ob der Char nicht schon Leader oder Coleader ist
				if($alliance->leader != $change_user && $alliance->coleader != $change_user)
				{
					$alliance->coleader = $change_user;
					$this->JediAlliances->save($alliance);
				}				
			}
			//kick
			elseif($this->request->getData("selectedOption") == "kick" && ($this->Auth->User("id") == $alliance->coleader OR $this->Auth->User("id") == $alliance->leader))
			{
				$change_user_model = $this->JediUserChars->get($change_user);
				
				if($alliance->leader != $change_user && $alliance->coleader != $change_user)
				{
					$change_user_model->alliance = 0;
					$change_user_model->alli_cooldown = time() + 86400;
				}
				elseif($alliance->coleader == $change_user)
				{
					$alliance->coleader = 0;
					$change_user_model->alliance = 0;
					$change_user_model->alli_cooldown = time() + 86400;
					$this->JediAlliances->save($alliance);
				}
				$this->JediUserChars->save($change_user_model);
			}
		}
		
		$char = $this->JediUserChars->get($this->Auth->User("id"));
				
		$leader = $this->JediUserChars->get($alliance->leader);
		if($alliance->coleader != 0)
		{
			$coleader = $this->JediUserChars->get($alliance->coleader);
			$this->set("coleader",$coleader);
		}	
		
		//check alli
		if($char->alliance == 0)
		{
			$no_alliance = true;
		}
		else
		{
			$no_alliance = false;
		}
		$this->set("no_alliance",$no_alliance);
		$this->set("user_alliance",$char->alliance);
		$this->set("leader",$leader);
		$this->set("members",$this->get_alli_member($id));
		$this->set("alliance",$alliance);
		$this->set("char",$char);
	}
	
	function get_alli_member($id)
	{
		$alli_member = $this->JediUserChars->find()->where(["alliance" => $id])->order(["username"])->all();
		foreach ($alli_member as $key => $value) {
			$last_acc = $this->Accounts->get($value->userid)->last_activity->wasWithinLast("5 minutes");
			
			$value->online = $last_acc;
		}
		return $alli_member;
	}
	
	public function raid()
	{
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		
		//check alli
		if($char->alliance == 0)
		{
			$no_alliance = true;
			return;
		}
		else
		{
			$no_alliance = false;
			$alliance = $this->JediAlliances->get($char->alliance);
			$this->set("alliance",$alliance);
		}
		//check raid
		$alliance_fight = $this->JediFights->find()->where(["alliance" => $alliance->id])->where(["status" => "open"])->first();
		$this->set("alli_fight",$alliance_fight);
		
		//check running raid
		$raid_running = $this->JediFights->find()->where(["alliance" => $alliance->id])->where(["status" => "preparing"])->first();
		if($raid_running != null)
		{
			$this->set("raid_running",true);
		}
		else
		{
			$this->set("raid_running",false);
		}
		//get fight-members
		if($alliance_fight != null)
		{
			$raid_members = $this->JediFightsPlayers->find()->where(["fightid" => $alliance_fight->fightid])
															->where(["npc" => "n"])->all();
			
			$i = 0;
			foreach($raid_members as $key => $raid_member)
			{
				$this->LoadModel('JediItemsJewelry');
				$jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $raid_member->userid]);
				
				$this->LoadModel('JediItemsWeapons');
				$weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $raid_member->userid]);        
	
				$members[$i] = $this->JediUserChars->get($raid_member->userid);
				$members[$i]->skills = $this->JediUserSkills->get($raid_member->userid);
				
				$members[$i]->MaxHealth = $this->maxHealth->calc_maxHp($members[$i]->skills->cns, $members[$i]->skills->level, $jewelry_model, $weapons_model);
				$members[$i]->MaxMana = $this->maxHealth->calc_maxMana($members[$i]->skills->spi, $members[$i]->skills->itl, $members[$i]->skills->level, $jewelry_model, $weapons_model);
				
				$members[$i]->HealthPro = round($members[$i]->health * 100 / $members[$i]->MaxHealth);
				$members[$i]->ManaPro = round($members[$i]->mana * 100 / $members[$i]->MaxMana);
				$i++;
			}
			$this->set("raid_members",$members);
		}

		if($char->userid == $alliance->leader OR $char->userid == $alliance->coleader)
		{
			$this->set("is_leader",true);
		}
		else
		{
			$this->set("is_leader",false);
		}
		
		//Cancel
        if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "cancel")
        {
            //get fight
            $fight_id = $this->JediFightsPlayers->find()->select("fightid")->where(["userid" => $this->request->getData("userid")])->first();

            //remove player from db
            $fight_player = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id->fightid])->where(["userid" => $this->request->getData("userid")])->first();
            $this->JediFightsPlayers->delete($fight_player);

			$char = $this->JediUserChars->get($this->request->getData("userid"));

			$char->actionid = 0;
			$char->targetid = 0;
			$char->targettime = 0;
			$this->JediUserChars->save($char);
						
            //checken ob noch einer dabei ist
            $count = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id->fightid])->where(["npc" => "n"])->count();
            //wenn keiner mehr dabei ist
            if($count == 0)
            {
                $this->JediFights->delete($this->JediFights->get($fight_id->fightid));
				$this->JediFightsPlayers->delete($this->JediFightsPlayers->find()->where(["npc" => "y"])->where(["fightid" => $fight_id->fightid])->first());
				
				$alliance->attemps += 1;
				if($alliance->attemps >= 5) {
					$alliance->attemps = 5;
				}
				$this->JediAlliances->save($alliance);
            }
        }
		
		//Join
		if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "join")
		{
			if(!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first()))
            {
                $this->Flash->error("You are already fighting somewhere else");
                return;
            }
			if($char->actionid != 0 OR $char->targetid != 0 OR $char->targettime != 0)
            {
                $this->Flash->error("You are doing something else");
                return;
            }
			
			$fight_id = $this->JediFights->find()->where(["alliance" => $char->alliance])->where(["status" => "open"])->first();
			
			if($fight_id != null)
			{
				$player = $this->JediFightsPlayers->newEntity();
				$player->fightid = $fight_id->fightid;
				$player->userid = $char->userid;
				$player->teamid = 0;
				$player->position = 0;
				$player->npc = "n";
				$this->JediFightsPlayers->save($player);
				
				$char->actionid = 1;
				$char->targetid = 2;
				$char->tmpcast = 0;
				$this->JediUserChars->save($char);
			}
		}
		//add
		if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "add")
		{
			$char = $this->JediUserChars->get($this->request->getData("userid"));

			if(!empty($this->JediFightsPlayers->find()->where(["userid" => $this->request->getData("userid")])->where(["npc" => "n"])->first()))
            {
                $this->Flash->error("You are already fighting somewhere else");
                return;
            }
			if($char->actionid != 0 OR $char->targetid != 0 OR $char->targettime != 0)
            {
                $this->Flash->error("You are doing something else");
                return;
            }
			
			$fight_id = $this->JediFights->find()->where(["alliance" => $char->alliance])->where(["status" => "open"])->first();
			
			if($fight_id != null)
			{
				$player = $this->JediFightsPlayers->newEntity();
				$player->fightid = $fight_id->fightid;
				$player->userid = $this->request->getData("userid");
				$player->teamid = 0;
				$player->position = 0;
				$player->npc = "n";
				$this->JediFightsPlayers->save($player);
				
				$char->actionid = 1;
				$char->targetid = 2;
				$char->tmpcast = 0;
				$this->JediUserChars->save($char);
			}
		}

		//get Free alli members to join
		$free_members = $this->JediUserChars->find()->select(["userid"])->where(["alliance" => $alliance->id, "actionid" => 0, "targetid" => 0, "targettime" => 0])->all();
		
		if(!$free_members->isEmpty()) {
			$i = 0;
				foreach($free_members as $key => $free_member)
				{
					$this->LoadModel('JediItemsJewelry');
					$jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $free_member->userid]);
					
					$this->LoadModel('JediItemsWeapons');
					$weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $free_member->userid]);        
		
					$fmembers[$i] = $this->JediUserChars->get($free_member->userid);
					$fmembers[$i]->skills = $this->JediUserSkills->get($free_member->userid);
					
					$fmembers[$i]->MaxHealth = $this->maxHealth->calc_maxHp($fmembers[$i]->skills->cns, $fmembers[$i]->skills->level, $jewelry_model, $weapons_model);
					$fmembers[$i]->MaxMana = $this->maxHealth->calc_maxMana($fmembers[$i]->skills->spi, $fmembers[$i]->skills->itl, $fmembers[$i]->skills->level, $jewelry_model, $weapons_model);
					
					$fmembers[$i]->HealthPro = round($fmembers[$i]->health * 100 / $fmembers[$i]->MaxHealth);
					$fmembers[$i]->ManaPro = round($fmembers[$i]->mana * 100 / $fmembers[$i]->MaxMana);
					$i++;
				}
			$this->set("free_members",$fmembers);
		}
		//start
		if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "start")
		{
			if($alliance->leader == $char->userid OR $alliance->coleader == $char->userid)
			{
				$fight_id = $this->JediFights->find()->where(["alliance" => $char->alliance])->where(["status" => "open"])->first();
				$fight_id->opentime = time();
				$fight_id->status = "preparing";
				$this->JediFights->save($fight_id);
				
				//GEGEN WEN KÄMPFEN WIR?
				$enemy = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id->fightid])->where(["npc" => "y"])->first();
				if($enemy->userid == 1)
				{
					$location2 = "bigrat";
				}
				elseif($enemy->userid == 9)
				{
					$location2 = "reek";
				}
				
				$raid_members = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id->fightid])
																->where(["npc" => "n"])->all();
																
				foreach($raid_members as $key => $raid_member)
				{
					$member = $this->JediUserChars->get($raid_member->userid);
					$member->actionid = 2;
					$member->targettime = time() + 600;
					$member->location2 = $location2;
					$this->JediUserChars->save($member);
				}
			}
			else
			{
				$this->Flash->error("You are not allowed to start a raid");
				return;
			}
		}
		$this->set("no_alliance",$no_alliance);
				
		//Init coopnpc-fight
		if($this->request->is("post") && $alliance_fight == null)
		{
			if(!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first()))
            {
                $this->Flash->error("You are already fighting somewhere else");
                return;
            }
			
			if($alliance->leader != $char->userid && $alliance->coleader != $char->userid)
			{
				$this->Flash->error("You are not allowed to start a raid");
				return;
			}
		
			if($alliance->attemps > 0)
			{
				$npcid = $this->request->getData("npc");
				
				$fight = $this->JediFights->newEntity();
				$fight->type = "coopnpc";
				$fight->alliance = $alliance->id;
				$fight->startin = 600;
				$fight->opentime = time();
				$fight->status = "open";
				$this->JediFights->save($fight);
				$fight_id = $this->JediFights->find()->last()->fightid;
				
				$players = $this->JediFightsPlayers->newEntity();
				$players->fightid = $fight_id;
				$players->userid = $char->userid;
				$players->teamid = 0;
				$players->position = 0;
				$players->npc = "n";
				$this->JediFightsPlayers->save($players);
				
				$char->targettime = time() + 600;
				$char->actionid = 1;
				$char->targetid = 2;
				$this->JediUserChars->save($char);
				
				$players = $this->JediFightsPlayers->newEntity();
				$players->fightid = $fight_id;
				$players->userid = $npcid;
				$players->teamid = 1;
				$players->position = 0;
				$players->npc = "y";
				$this->JediFightsPlayers->save($players);
				
				$alliance->attemps -= 1;
				$this->JediAlliances->save($alliance);
			}
			else
			{
				$this->Flash->error("No more attemps");
			}
		}
	}
	
	public function leave()
	{
		$char = $this->JediUserChars->get($this->Auth->User("id"));
		$alliance = $this->JediAlliances->get($char->alliance);
		
		if($alliance->leader != $char->userid && $alliance->coleader != $char->userid)
		{
			$char->alliance = 0;
			$char->alli_cooldown = time() + 86400;
		}
		elseif($alliance->coleader == $char->userid)
		{
			$alliance->coleader = 0;
			$char->alliance = 0;
			$char->alli_cooldown = time() + 86400;
			$this->JediAlliances->save($alliance);
		}
		$this->JediUserChars->save($char);
	}
	
	public function research()
	{
		
	}
}