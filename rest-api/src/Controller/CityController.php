<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;


class CityController extends RestController {

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Fight');
		$this->loadComponent('maxHealth');
    }

    public function layer()
    {
        $this->loadModel("JediFights");
        $this->loadModel("JediFightsPlayers");
        $this->loadModel("JediFightReports");
        $this->loadModel("JediTreasures");
        
        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
        $skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		
		$this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);  

		$skills['max_health'] = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model); 
        $skills['max_mana'] = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
        $skills['max_energy'] = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);
		
		$skills["health_width"] = round($char["health"] * 100 / $skills["max_health"]);
        $skills["mana_width"] = round($char["mana"] * 100 / $skills["max_mana"]);
        $skills["energy_width"] = round($char["energy"] * 100 / $skills["max_energy"]);
        //Kein Leben für Layer
        if($char->health <= 10 && $char->actionid != 2)
        {
            $this->Flash->error("Check your health");
        }

        //Keine Energy für Layer
        if($char->energy < 1 && $char->actionid != 2)
        {
            $this->Flash->error("check your energy");
        }

        //Keine Waffe für Layer
        if($char->item_hand == 0)
        {
            $this->Flash->error("better equip a weapon");
        }

        if((!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first())) && $char->actionid != 2)
        {
            $this->set("busy",true);
        }

		//casting fseei
		if($this->request->getData("cast"))
		{
			if($this->request->getData("cast") == 1)
			{
				$tmpforces = $this->maxHealth->tempBonusForces($jewelry_model, $weapons_model);
				$tmpfseei = $tmpforces["tmpfseei"];
				$fseei = $skills->fseei + $tmpfseei;
				
				if($skills->fseei > 0)
				{
					$a = (0.3*$skills->level)*($fseei/(0.7*$skills->level));
					
					$tmpcast = rand(ceil(15*$a/100),ceil(25*$a/100));
					
					if($tmpcast >= 1) 
					{ 
						$manacon = round($a / 4);
					}
					if ($manacon <= 0 ) 
					{
						$manacon = 1;
					}
					if($manacon <= $char->mana)
					{
						$char->mana -= $manacon;
						$char->tmpcast = $tmpcast;
						$this->JediUserChars->save($char);
						$this->set("cast",true);
					}
					else
					{
						$this->set("cast",false);
					}
				}
				else
				{
					$this->set("cast",false);
				}
			}
		}
		
        if($this->request->getParam('pass'))
        {
            if($this->request->getParam('pass')[0] == "attack" && $this->request->getData("fight") == "y")
            {
				$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id")); //nochmal nen neuen user holen wenn attack aufgerufen wurde
                if($this->request->getData("a") != 3 && $char->actionid == 0 && $char->targetid == 0)
                {
                    $fights = $this->loadModel("JediFights");
                    $fights_player = $this->loadModel("JediFightsPlayers");

                    //get position
                    $a = $this->request->getData("a");
                    $b = $this->request->getData("b");

                    $char->targetid = 2;
                    $char->actionid = 2;
                    $char->location = "Layer 2";
                    $char->location2 = $a."_".$b;
                    $char->targettime = time()+(300);

                    $this->JediUserChars->save($char);

                    $monster = explode("_", $char->location2);
                    if($monster[0] == 2)
                    {
                        $attack =  2;
                        $coopnpc = rand(0,5);
                    }
                    elseif($monster[0] == 1)
                    {
                        $attack = 2;
                        $coopnpc = 0; 
                    }

                    //more rats?
					if($char->health/7 > 18 && rand(0,3) < 3)
					{
						$morerats = rand(1,3);
					}
					elseif($char->health/7 > 22 && rand(0,3) < 3)
                    {
                        $morerats = rand(1,5);
                    }
                    elseif($char->health/7 > 30 && rand(0,3) < 3)
                    {
                        $morerats = rand(1,7);
                    }
                    elseif($char->health/7 > 50 && rand(0,3) < 3)
                    {
                        $morerats = rand(1,9);
                    }
                    elseif($char->health/7 > 70 && rand(0,3) < 3)
                    {
                        $morerats = rand(1,11);
                    }
                    else
                    {
                        $morerats = 0;
                    }

                    if($skills->level < 5)
                    {
                        $morerats = 0;
                    }
                    if($skills->level > 50 && $morerats >= 1)
                    {
                        $morerats = $morerats + rand(1,3);
                    }
                    elseif($skills->level > 75 && $morerats >= 2) 
                    {   
                        $morerats = $morerats + rand(2,4);
                    }
                    elseif($skills->level > 100 && $morerats >= 3)
                    {
                        $morerats = $morerats + rand(3,6);
                    }
                    
                    if($morerats > 0)
                    {
                        $type = "coopnpc";
                    }
                    else
                    {
                        $type = "duelnpc";
                    }

                    //in die DB
                    $fights = $this->JediFights->newEntity();
                    $fights->type = $type;
                    $fights->opentime = time();
                    $fights->startin = 300;
                    $this->JediFights->save($fights);
                    $fightId = $this->JediFights->find()->last();

                    $fights_player = $this->JediFightsPlayers->newEntity();
                    $fights_player->fightid = $fightId["fightid"];
                    $fights_player->userid = $this->Auth->User("id");
                    $fights_player->teamid = 0;
                    $this->JediFightsPlayers->save($fights_player);

                    if($morerats > 0)
                    {
                        $i = $morerats + 1; //eine Ratte ist standardmäßig dabei. Dann zusätzlich $morerats
                        while($i > 0)
                        {
                            $i--;
                            $fights_player = $this->JediFightsPlayers->newEntity();
                            $fights_player->fightid = $fightId["fightid"];
                            $fights_player->userid = 2;
                            $fights_player->teamid = 1;
                            $fights_player->npc = "y";
                            $this->JediFightsPlayers->save($fights_player);
                        }  
                    }
                    else
                    {
                        $fights_player = $this->JediFightsPlayers->newEntity();
                        $fights_player->fightid = $fightId["fightid"];
                        $fights_player->userid = 2;
                        $fights_player->teamid = 1;
                        $fights_player->npc = "y";
                        $this->JediFightsPlayers->save($fights_player);
                    }                    
                }
                else
                {
                    //There is nothing to attack
                    $this->set('message','There was nothing to attack.');
                    
                }
            }       
        }
        $char->targettime_diff = $char->targettime-time();

        $this->set('char',$char);
		$this->set('skills',$skills);

        if($char->targettime > time())
        {
            $this->set('doing','yes');
        }
		elseif($char->actionid == 2 && $char->targetid == 2)
        {
            $this->set('doing','yes');
        }
        else
        {
            $this->set('doing','no');
        }
		/*
		if($this->Auth->User("id") == 20)
		{
			$fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
			debug($fight_player[0]);
			debug($this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray());
		}
		*/
        if($this->request->getParam('pass'))
        {
            if($this->request->getParam('pass')[0] == "fight")
            {	
                $fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
                
				if(empty($fight_player))
				{
					$fight_report = $this->JediFightReports->get($char->lastfightid);
					$this->set('fight_report',$fight_report);
					
					$char->actionid = 0;
                    $char->targetid = 0;
                    $char->targettime = 0;
                    $char->lastfightid = 0;
                    $char->tmpcast = 0;
                    $char->location2 = "3_1";
                    $this->JediUserChars->save($char);
				}
                elseif ($fight_player[0]["fightid"])
                {
					$fight = $this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray();
                    $startin = $fight[0]["opentime"] + $fight[0]["startin"] - time();
                    if ($startin <= 0)
                    {
                        //This is where the action happens
                        $fight = $this->Fight->fight($fight_player[0]["fightid"]);
                    }
                }
            }
            elseif($this->request->getParam('pass')[0] == "view" && $char->actionid == 2)
            {
                $fight_report = $this->JediFightReports->get($char->lastfightid);
                $this->set('fight_report',$fight_report);

                $fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
                if(!empty($fight_player))
                {
                    $fight = $this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray();
                }

                if(empty($fight_player[0]["fightid"]))
                {
                    //nachdem der Bericht angezeigt worden ist statis aufräumen
                    $char->actionid = 0;
                    $char->targetid = 0;
                    $char->targettime = 0;
                    $char->lastfightid = 0;
                    $char->tmpcast = 0;
                    $char->location2 = "3_1";
                    $this->JediUserChars->save($char);
                }
            }
        }
        #if(!empty($loot)) $this->set('old_loot',$loot);

        /*ungelesener Bericht?
        if($char->actionid == 2 && $char->targetid == 2 && $char->targettime < time())
        {
            $this->redirect(['controller' => 'city', 'action' => 'layer', 'fight']);
        }
		*/
		
		//checking if player - alliance is in a raid
		$alli_raid = $this->JediFights->find()->where(["alliance" => $char->alliance])->first();
		if($alli_raid != null)
		{
			$this->set("raid",true);
		}
		else
		{
			$this->set("raid",false);
		}
    }

	public function layer2()
	{
		$this->loadModel("JediFights");
        $this->loadModel("JediFightsPlayers");
        $this->loadModel("JediFightReports");
        $this->loadModel("JediTreasures");
        
        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
        $skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		
		$this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);  

		$skills['max_health'] = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model); 
        $skills['max_mana'] = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
        $skills['max_engery'] = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);
		
        //Kein Leben für Layer
        if($char->health <= 10 && $char->actionid != 2)
        {
            $this->Flash->error("Check your health");
            return $this->redirect(['controller' => 'character', 'action' => 'overview']);
        }

        //Keine Energy für Layer
        if($char->energy < 1 && $char->actionid != 2)
        {
            $this->Flash->error("check your energy");
            return $this->redirect(['controller' => 'character', 'action' => 'overview']);
        }

        //Keine Waffe für Layer
        if($char->item_hand == 0)
        {
            $this->Flash->error("better equip a weapon");
            return $this->redirect(['controller' => 'character', 'action' => 'inventory']);
        }

        if((!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first())) && $char->actionid != 2)
        {
            $this->Flash->error("You are already fighting somewhere else");
            return $this->redirect(['controller' => 'character', 'action' => 'overview']);
        }

		//casting fseei
		if($this->request->getData("cast"))
		{
			if($this->request->getData("cast") == 1)
			{
				$tmpforces = $this->maxHealth->tempBonusForces($jewelry_model, $weapons_model);
				$tmpfseei = $tmpforces["tmpfseei"];
				$fseei = $skills->fseei + $tmpfseei;
				
				if($skills->fseei > 0)
				{
					$a = (0.3*$skills->level)*($fseei/(0.7*$skills->level));
					
					$tmpcast = rand(ceil(15*$a/100),ceil(25*$a/100));
					
					if($tmpcast >= 1) 
					{ 
						$manacon = round($a / 4);
					}
					if ($manacon <= 0 ) 
					{
						$manacon = 1;
					}
					if($manacon <= $char->mana)
					{
						$char->location2 = "".$this->request->getData("a")."_".$this->request->getData("b")."";
						$char->mana -= $manacon;
						$char->tmpcast = $tmpcast;
						$this->JediUserChars->save($char);
						$this->set("cast",true);
					}
					else
					{
						$this->set("cast",false);
					}
				}
				else
				{
					$this->set("cast",false);
				}
			}
		}
		
        if($this->request->getParam('pass'))
        {
            if($this->request->getParam('pass')[0] == "attack" && $this->request->getData("fight") == "y")
            {
				$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id")); //nochmal nen neuen user holen wenn attack aufgerufen wurde
                if($this->request->getData("a") != 3 && $char->actionid == 0 && $char->targetid == 0)
                {
                    $fights = $this->loadModel("JediFights");
                    $fights_player = $this->loadModel("JediFightsPlayers");

                    //get position
                    $a = $this->request->getData("a");
                    $b = $this->request->getData("b");

                    $char->targetid = 2;
                    $char->actionid = 2;
                    $char->location = "Layer 2";
                    $char->location2 = $a."_".$b;
                    $char->targettime = time()+(300);

                    $this->JediUserChars->save($char);

                    $monster = explode("_", $char->location2);
                    if($monster[0] == 2)
                    {
                        $attack =  2;
                        $coopnpc = rand(0,5);
                    }
                    elseif($monster[0] == 1)
                    {
                        $attack = 2;
                        $coopnpc = 0; 
                    }

                    $type = "duelnpc";

                    //in die DB
                    $fights = $this->JediFights->newEmptyEntity();
                    $fights->type = $type;
                    $fights->opentime = time();
                    $fights->startin = 300;
                    $this->JediFights->save($fights);
                    $fightId = $this->JediFights->find()->last();

                    $fights_player = $this->JediFightsPlayers->newEmptyEntity();
                    $fights_player->fightid = $fightId["fightid"];
                    $fights_player->userid = $this->Auth->User("id");
                    $fights_player->teamid = 0;
                    $this->JediFightsPlayers->save($fights_player);

					$fights_player = $this->JediFightsPlayers->newEmptyEntity();
					$fights_player->fightid = $fightId["fightid"];
					$fights_player->userid = 8;
					$fights_player->teamid = 1;
					$fights_player->npc = "y";
					$this->JediFightsPlayers->save($fights_player);                   

                    $this->redirect(['action' => 'layer2']);
                }
                else
                {
                    //There is nothing to attack
                    $this->set('message','There was nothing to attack.');
                    
                }
            }       
        }
        $char->targettime_diff = $char->targettime-time();

        $this->set('char',$char);
		$this->set('skills',$skills);

        if($char->targettime > time())
        {
            $this->set('doing','yes');
        }
		elseif($char->actionid == 2 && $char->targetid == 2)
        {
            $this->set('doing','yes');
        }
        else
        {
            $this->set('doing','no');
        }
		/*
		if($this->Auth->User("id") == 20)
		{
			$fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
			debug($fight_player[0]);
			debug($this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray());
		}
		*/
        if($this->request->getParam('pass'))
        {
            if($this->request->getParam('pass')[0] == "fight")
            {	
                $fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
                
				if(empty($fight_player))
				{
					$fight_report = $this->JediFightReports->get($char->lastfightid);
					$this->set('fight_report',$fight_report);
					
					$char->actionid = 0;
                    $char->targetid = 0;
                    $char->targettime = 0;
                    $char->lastfightid = 0;
                    $char->tmpcast = 0;
                    $char->location2 = "3_1";
                    $this->JediUserChars->save($char);
				}
                elseif ($fight_player[0]["fightid"])
                {
					$fight = $this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray();
                    $startin = $fight[0]["opentime"] + $fight[0]["startin"] - time();
                    if ($startin <= 0)
                    {
                        //This is where the action happens
                        $fight = $this->Fight->fight($fight_player[0]["fightid"]);
                    }
                }
            }
            elseif($this->request->getParam('pass')[0] == "view" && $char->actionid == 2)
            {
                $fight_report = $this->JediFightReports->get($char->lastfightid);
                $this->set('fight_report',$fight_report);

                $fight_player = $this->JediFightsPlayers->find()->select(['fightid'])->where(['userid' => $this->Auth->User("id"), 'npc' => 'n'])->toArray();
                if(!empty($fight_player))
                {
                    $fight = $this->JediFights->find()->select(['opentime', 'startin'])->where(['fightid' => $fight_player[0]["fightid"], 'OR' => [['type' => 'duelnpc'], 'type' => 'coopnpc']])->toArray();
                }

                if(empty($fight_player[0]["fightid"]))
                {
                    //nachdem der Bericht angezeigt worden ist statis aufräumen
                    $char->actionid = 0;
                    $char->targetid = 0;
                    $char->targettime = 0;
                    $char->lastfightid = 0;
                    $char->tmpcast = 0;
                    $char->location2 = "3_1";
                    $this->JediUserChars->save($char);
                }
            }
        }
        #if(!empty($loot)) $this->set('old_loot',$loot);

        /*ungelesener Bericht?
        if($char->actionid == 2 && $char->targetid == 2 && $char->targettime < time())
        {
            $this->redirect(['controller' => 'city', 'action' => 'layer', 'fight']);
        }
		*/
		
		//checking if player - alliance is in a raid
		$alli_raid = $this->JediFights->find()->where(["alliance" => $char->alliance])->first();
		if($alli_raid != null)
		{
			$this->set("raid",true);
		}
		else
		{
			$this->set("raid",false);
		}
	}
	
    public function arena()
    {
        $this->loadModel("JediFights");
        $this->loadModel("JediFightsPlayers");
        $this->loadModel("JediFightReports");
        $this->loadModel("JediTreasures");
        $this->loadModel("JediUserChars");
        $this->loadModel("JediUserSkills");
        
        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
		$char->skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		$this->set("char",$char);
		$this->set("skills",$char->skills);
     
        //Kein Leben für Arena
        if($char->health <= 20 && $char->actionid != 15)
        {
			$this->Flash->error("Better check your health");
			return;
        }

		$char->location = "Arena";
		$this->JediUserChars->save($char);
		
        //Bericht gelesen
        if($this->request->getParam('pass') && $this->request->getParam('pass')[0] == "clear")
        {
            $char->actionid = 0;
            $char->lastfightid = 0;
			$this->JediUserChars->save($char);
			return;
        }

		//calculate restriction
		//1 (15%)
		$res["1_low"] = $char->skills->level - floor($char->skills->level * 0.15);
		$res["1_high"] = $char->skills->level + floor($char->skills->level * 0.15);

		//2 (27%)
		$res["2_low"] = $char->skills->level - floor($char->skills->level * 0.27);
		$res["2_high"] = $char->skills->level + floor($char->skills->level * 0.27);

		//3 (39%)
		$res["3_low"] = $char->skills->level - floor($char->skills->level * 0.39);
		$res["3_high"] = $char->skills->level + floor($char->skills->level * 0.39);            

		$this->set("res",$res);
			
        //Open Fight
        if($this->request->getParam('pass') && $this->request->getParam('pass')[0] == "open")
        {
            if(!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first()))
            {
				$this->Flash->error("You are already fighting somewhere else");
				return;
            }
			
			//Keine Energy für Arena
			if($char->energy < 1)
			{
				$this->Flash->error("Better check your energy");
				return;
			}
			
			//was anderes
			if($char->actionid != 0)
			{
				$this->Flash->error("You are busy");
				return;
			}
			
            $this->set("open","yes");

            if($this->request->is(['post']))
            {
                $posts = $this->request->getData()["formData"];
                
                $choosen_res = $posts["res"];
				$choosen_type = $posts["type"];
				$bet = $posts["bet"];
				$cost = $this->request->getData()["cost"];
                if(isset($posts["lower"]))  $lower = $posts["lower"];
                if(isset($posts["higher"])) $higher = $posts["higher"];

                if(($cost + $bet) > $char->cash)
                {
					$this->Flash->error("Your cannot afford the rent");
					return;
                }

                $fight_db = $this->JediFights->newEntity();
                $fight_db->type = $choosen_type;
                $fight_db->opentime = time();
                $fight_db->startin = 300;
                $fight_db->bet = $bet;
                
                if(isset($lower) && $lower == 1)
                {
                    if($choosen_res == 1)
                    {
                        $fight_db->minstr = $res["1_low"];
                    }
                    elseif($choosen_res == 2)    
                    {
                        $fight_db->minstr = $res["2_low"];
                    }       
                    elseif($choosen_res == 3)
                    {
                        $fight_db->minstr = $res["3_low"];
                    }
                }
                if(isset($higher) && $higher == 1)
                {
                    if($choosen_res == 1)
                    {
                        $fight_db->maxstr = $res["1_high"];
                    }
                    elseif($choosen_res == 2)    
                    {
                        $fight_db->maxstr = $res["2_high"];
                    }       
                    elseif($choosen_res == 3)
                    {
                        $fight_db->maxstr = $res["3_high"];
                    }
                }
                $fight_db->status = "open";
                $this->JediFights->save($fight_db);

                $fighter_db = $this->JediFightsPlayers->newEntity();
                $fightId = $this->JediFights->find()->last();
                $fighter_db->fightid = $fightId["fightid"];
                $fighter_db->userid = $this->Auth->User("id");
                $fighter_db->teamid = 0;
                $fighter_db->position = 0;
                $fighter_db->npc = "n";
                $this->JediFightsPlayers->save($fighter_db);

                $char->cash -= ($cost + $bet);
                $char->actionid = 15;
                $char->targetid = 0;
                $char->targettime = 0;
				$char->location = "Arena";
                $this->JediUserChars->save($char);
			}
        }        
        //Joinen
        if($this->request->getParam('pass') && $this->request->getParam('pass')[0] == "join")
        {
            $fightid = $this->request->getParam('pass')[1];

            //load fight
            $fight = $this->JediFights->find()->where(["fightid" => $fightid])->first();
            $fight_players = $this->JediFightsPlayers->find()->where(["fightid" => $fightid]);
   
            //Gründe warum man dem fight nicht joinen kann
            if(!empty($this->JediFightsPlayers->find()->where(["userid" => $this->Auth->User("id")])->where(["npc" => "n"])->first()))
            {
				$this->Flash->error("You are already fighting somewhere else");
				return;
            }

            if($fight->status != "open")
            {
				$this->Flash->error("this fight is already full");
				return;
            }

            if($fight->maxstr < $char->skills->level && $fight->maxstr != 0)
            {
				$this->Flash->error("Your level is too high");
				return;
            }

            if($fight->minstr > $char->skills->level && $fight->minstr != 0)
            {
				$this->Flash->error("Your level is too low");
				return;
            }

            if($fight->bet > $char->cash)
            {
				$this->Flash->error("Your cannot afford the bet");
				return;
            }

			//Keine Energy für Arena
			if($char->energy < 1)
			{
				$this->Flash->error("Better check your energy");
				return;
			}
			
            if($fight->type == "duel" && $fight->status == "open")
            {
                //belegte Seite ermitteln
                $full_side = $fight_players->select('teamid')->first();
  
                if($full_side->teamid == 0)
                {
                    //joinen auf team1
                    $side = 1;
                }
                elseif($full_side->teamid == 1)
                {
                    //joinen auf team0
                    $side = 0;
                }

                $join_player = $this->JediFightsPlayers->newEntity();
                $join_player->fightid = $fightid;
                $join_player->userid = $this->Auth->User("id");
                $join_player->teamid = $side;
                $join_player->position = 0;
                $join_player->npc = "n";
                $this->JediFightsPlayers->save($join_player);

                $char->actionid = 15;
                $char->targetid = 0;
                $char->targettime = 0;
				$char->location = "Arena";
                $char->cash -= $fight->bet;
                $this->JediUserChars->save($char);

                $fight->status = "preparing";
                $fight->opentime = time();
                $this->JediFights->save($fight);
            }

            if($fight->type == "coop" && $fight->status == "open")
            {
                //gewählte Seite
                $side = $this->request->getParam('pass')[2];

                //Checken ob nicht doch schon voll
                $query = $fight_players->where(["teamid" => $side]);
                $count = $query->count();
                if($count >= 2)
                {
					$this->Flash->error("Team is allready full");
					return;
                }
                //belegte Position ermitteln
                $full_position = $fight_players->select('position')->where(["teamid" => $side])->first();
				if(empty($full_position))
				{
					$position = 0;
				}
                elseif($full_position->position == 0)
                {
                    //joinen auf position1
                    $position = 1;
                }
                elseif($full_position->position == 1)
                {
                    //joinen auf position0
                    $position = 0;
                }

                $join_player = $this->JediFightsPlayers->newEntity();
                $join_player->fightid = $fightid;
                $join_player->userid = $this->Auth->User("id");
                $join_player->teamid = $side;
                $join_player->position = $position;
                $join_player->npc = "n";
                $this->JediFightsPlayers->save($join_player);

                $char->actionid = 15;
                $char->targetid = 0;
                $char->targettime = 0;
				$char->location = "Arena";
                $char->cash -= $fight->bet;
                $this->JediUserChars->save($char);

                $fight->opentime = time();

                $players = $this->JediFightsPlayers->find()->where(["fightid" => $fightid])->count();

                if($players == 4)
                {
                    $fight->status = "preparing";
                }
                $this->JediFights->save($fight);
            }
        }
        //Cancel
        if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "cancel")
        {
            //get fight
            $fight_id = $this->JediFightsPlayers->find()->select("fightid")->where(["userid" => $this->Auth->User("id")])->first();
            //wenn user in gar keinem fight
            if(empty($fight_id["fightid"]))
            {
				return;
            }
            //remove player from db
            $fight_player = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id["fightid"]])->where(["userid" => $this->Auth->User("id")])->first();
            $this->JediFightsPlayers->delete($fight_player);

			$char->actionid = 0;
			$char->targetid = 0;
			$char->targettime = 0;
			$this->JediUserChars->save($char);
			
			$fight = $this->JediFights->find()->where(["fightid" => $fight_id["fightid"]])->first();
			$fight->status = "open";
			$fight->opentime = time();
			$this->JediFights->save($fight);
			
            //checken ob noch einer dabei ist
            $count = $this->JediFightsPlayers->find()->where(["fightid" => $fight_id["fightid"]])->count();
            //wenn keiner mehr dabei ist
            if($count == 0)
            {
                $this->JediFights->delete($this->JediFights->get($fight_id["fightid"]));
            }
        }

        //Alle kämpfe für die Tabelle holen
        $fights = $this->JediFights->find()->where(['OR' => [['type' => 'duel'], ['type' => 'coop']]])->where(["type2" => ""])->toArray();
        
        //evtl schon einer fertig?
        foreach ($fights as $key => $fight)
        {
            $startin = $fight["opentime"] + $fight["startin"] - time();
            if ($startin <= 0 && $fight["status"] != "open")
            {
                //This is where the action happens
                $fight = $this->Fight->fight($fight["fightid"]);
            }
        }

        //Falls noch ein Bericht nicht gelesen wurde
        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
        if($char->lastfightid != "0")
        {
            $fight_report = $this->JediFightReports->find()->where(['md5' => $char->lastfightid])->first();
            $this->set('fight_report',$fight_report);
        }

        //Alle kämpfe für die Tabelle holen
        $fights = $this->JediFights->find()->where(['OR' => [['type' => 'duel'], ['type' => 'coop']]])->where(["type2" => ""])->toArray();

        if($fights)
        {
            $this->set('fights',$fights);

            //Einzelne Fighter holen
            $connection = ConnectionManager::get('default');
            $fighters = $connection
                        ->execute('SELECT * FROM jedi_fights_players INNER JOIN jedi_fights ON jedi_fights_players.fightid = jedi_fights.fightid WHERE jedi_fights.type = :duel OR jedi_fights.type = :coop', ['duel' => "duel", "coop" => "coop"])
                        ->fetchAll('assoc');

            for ($i=0; $i < count($fighters); $i++)
            { 
                $fighters[$i]["char"] = $this->JediUserChars->get($fighters[$i]["userid"]);
                $fighters[$i]["skills"] = $this->JediUserSkills->get($fighters[$i]["userid"]);   
            }
            $this->set('fighters',$fighters);
        }
		
		//Fightid für die countdownfunction
		$fight_id_for_counter = $this->JediFightsPlayers->find()->select("fightid")->where(["userid" => $this->Auth->User("id")])->first();
		if(!empty($fight_id_for_counter))
		{
			$this->set("user_fight_id_counter",$fight_id_for_counter->fightid);
		}
		else
		{
			$this->set("user_fight_id_counter",0);
		}
		
		$fight_reps = $this->JediFightReports->find()->select(["zeit", "headline", "md5"])->where(["type" => "a"])->order(["zeit" => "DESC"])->limit(20);
        $this->set("fight_reps",$fight_reps);
    }

    public function bar()
    {
        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
        $skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));

		if($char->actionid == 0 && $char->targetid == 0 && $char->targettime == 0)
		{
			$this->set("open",true);
		}
		else
		{
			$this->Flash->error("You are busy");
			$this->set("open",false);
			return;
		}
		
		$this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);  

		$skills['max_health'] = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model); 
        $skills['max_mana'] = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
        $skills['max_engery'] = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);

        $char->location = "Bar";
		$this->set("char",$char);
		$this->set("skills",$skills);
		
        //Buy
        if($this->request->getParam("pass") && $this->request->getParam("pass")[0] == "buy")
        {
            $barman = $this->loadModel("JediNpcChars")->get(3);

            //mana
            //small
            if($this->request->getParam("pass")[1] == "m")
            {
                //Achtung! fixer preis
                if($char->cash < 10)
                {
                    $this->Flash->error("You have not enough cash");
                    return $this->redirect(["action" => "bar"]);
                }
                $char->cash -= 10;
                $char->mana += 50;
                $barman->cash += 10;
				if($char->mana > $skills['max_mana']) $char->mana = $skills['max_mana'];
            }

			//health
			elseif($this->request->getParam("pass")[1] == "h")
            {
				$max_health = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model);
				$char->health += 50;
				if($char->health > $max_health) $char->health = $max_health;
            }
			//Energy
			elseif($this->request->getParam("pass")[1] == "e")
            {
				//Achtung! fixer preis
                if($char->cash < 150)
                {
                    $this->Flash->error("You have not enough cash");
                    return $this->redirect(["action" => "bar"]);
                }
				
				$char->energy += 10;
				$char->cash -= 150;
				$barman->cash += 150;
				if($char->energy > $skills['max_engery']) $char->energy = $skills['max_engery'];
            }
			
            $this->JediUserChars->save($char);
            $this->JediNpcChars->save($barman);
        }
    }
	
	public function apa()
	{
		$char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));
		$skills = $this->loadModel("JediUserSkills")->get($this->Auth->User("id"));
		$apa = $this->loadModel("JediCityApartments")->get($char->base);
		
		if($char->actionid == 0 && $char->targetid == 0 && $char->targettime == 0)
		{
			$this->set("open",true);
			$this->set("sleep",false);
		}
		elseif($char->actionid == 1)
		{
			$this->set("open",true);
			$this->set("sleep",true);
		}
		else
		{
			$this->set("open",false);
			$this->set("sleep",false);
		}
		
		$maxsleeptime = 6;
		
		if($this->request->is("post"))
		{
			$duration = $this->request->getData()["duration"];
			
			if($char->actionid == 0 && $char->targetid == 0 && $char->targettime == 0)
			{
				$sleeptime = $duration * 60 * 60;
			}
			else
			{
				$open = false;
			}
			
			if($duration > 6) $duration = 6;
			
			$sleeptime = $duration * 60 * 60;
			$char->actionid = 1;
			$char->targetid = $char->base; //base
			$char->targettime = time() + $sleeptime;
			$this->JediUserChars->save($char);
			
			$apa->sleep = "yes";
			$apa->sleepingsince = time();
			$apa->sleepingfor = $sleeptime;
			$this->JediCityApartments->save($apa);
		}
		
		if($char->actionid == 1)
		{
			$lefttime = $apa->sleepingsince + $apa->sleepingfor - time();
  
			#echo "Schlafen Dauer: ".$apa->sleepingfor." Seit:".$apa->sleepingsince." left:".$lefttime."sekunden";
			// vielleicht schon fertig?
			if ($lefttime <= 0)
			{
				$this->LoadModel('JediItemsJewelry');
				$jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
				
				$this->LoadModel('JediItemsWeapons');
				$weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);  

				$char->max_health = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model); 
				$char->max_mana = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
				$char->max_energy = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);
				
				$lefttime = 0;
				//energie erhalten
				$addvaluehp = (100 * ($apa->sleepingfor / 60 / 60))/$maxsleeptime;
				$addvaluemn = (100 * ($apa->sleepingfor / 60 / 60))/$maxsleeptime;
				$addvalueen = (100 * ($apa->sleepingfor / 60 / 60))/$maxsleeptime;
				$realenergy = round(($addvalueen * $char->max_energy)/100);
				$realmana = round(($addvaluemn * $char->max_mana)/100);
				$realhp = round(($addvaluehp * $char->max_health)/100);
				#echo "Mana+: ".$addvaluemn."% real:".$realmana." aktuell:".$char->mana." max:".$char->max_mana;
	
				$healthp = $char->health * 100 / $char->max_health;
				$manap = $char->mana * 100 / $char->max_mana;
				$mxhpreg = 50 * $char->max_health / 100;
				$mxmnreg = 60 * $char->max_mana / 100;
				if($healthp < 50)
				{
					$char->health += $realhp;
					if($char->health > $mxhpreg)
					{
						$char->health = $mxhpreg;
					}
				}
				if($manap < 60)
				{
					$char->mana += $realmana;
					if($char->mana > $mxmnreg)
					{	
						$char->mana = $mxmnreg;
					}
				}
				if ( $char->health > $char->max_health)
				{
					$char->health = $char->max_health; 
				}
				if ( $char->mana > $char->max_mana) 
				{
					$char->mana = $char->max_mana; 
				}
				
				$char->energy += $realenergy;
				if ($char->energy > $char->max_energy)
				{
					$char->energy = $char->max_energy;
				}
								
				//aufr�umen
				$char->actionid = 0;
				$char->targetid = 0;
				$char->targettime = 0;
				$this->JediUserChars->save($char);
				
				$apa->sleep = 0;
				$apa->sleepingsince = 0;
				$apa->sleepingfor = 0;
				$this->JediCityApartments->save($apa);
			}

			if($apa->sleep == "yes")
			{
				$progress = ($apa->sleepingfor - $lefttime) * 100 / $apa->sleepingfor;
				$this->set("progress",$progress);
				$this->set("duration",$apa->sleepingfor);
			}
			$this->set("timer",$lefttime);
		}
	}
	public $paginate = [	
			'sortWhitelist' => [
				'itemid', 'name', 'mindmg', 'maxdmg', 'price', 'qlvl', 'reql', 'reqs', 'stat1_value', 'stat2_value', 'stat3_value', 'stat4_value', 'stat5_value'
			],
			// Other keys here.
			'limit' => 20,
			'order' => ['itemid' => 'desc']
    ];
	
	public function auction ()
	{	
		$this->loadModel("JediCityAhItems");
		$this->loadModel("JediCityAhOffers");
		$this->loadModel("JediCityAhLog");
		$this->loadModel("JediUserChars");
		$this->loadModel("JediUserSkills");
		$this->loadModel("JediItemsJewelry");
		$this->loadModel("JediItemsWeapons");
		$this->loadModel("JediItemsBooks");
		$this->loadModel("JediItemsBots");
		$this->loadModel("JediItemsMisc");
		
		$connection = ConnectionManager::get('default');
		
		$action = $this->request->getQuery("action");
		$view = $this->request->getQuery("view");
		$id = $this->request->getQuery("id");
		$this->set("action",$action);
		$this->set("view",$view);
		$this->set("id",$id);
		
		$char = $this->JediUserChars->get($this->Auth->User("id"));
        $char->skills = $this->JediUserSkills->get($this->Auth->User("id"));
		$this->set("char",$char);
		
		//get all auction
		$all = $this->JediCityAhItems->find()->all();
		
		//eine Auktion bereits beendet?
		foreach($all as $key => $auction)
		{
			if($auction->endtime < time())
			{
				//Keine Gebote
				if($auction->act_price_user == 0)
				{
					//item zurück ins inv
					if($auction->itemtype == "rings")
					{
						$item = $this->JediItemsJewelry->get($auction->itemid);
						$item->position = "inv";
						$this->JediItemsJewelry->save($item);
					}
					else
					{
						$item = $this->JediItemsWeapons->get($auction->itemid);
						$item->position = "inv";
						$this->JediItemsWeapons->save($item);
					}
					
					$log = $this->JediCityAhLog->newEmptyEntity();
					$log->auctionid = $auction->auctionid;
					$log->itemid = $auction->itemid;
					$log->itemtype = $auction->itemtype;
					$log->seller = $auction->seller;
					$log->buyer = 0;
					$log->price = 0;
					$log->type_of_buy = "no bid";
					$log->time = time();
					$this->JediCityAhLog->save($log);
					
				}
				else
				{
					//Geld transfer
					$buyer = $this->JediUserChars->get($auction->act_price_user);
					$return_value = $connection->execute(
										'SELECT max_bet, userid, time FROM jedi_city_ah_offers
										WHERE auctionid = :auctionid AND userid = :userid
											AND time = (SELECT MAX(time)
											FROM jedi_city_ah_offers
											WHERE auctionid = :auctionid
											AND userid = :userid)',
											["auctionid" => $auction->auctionid,
											"userid" => $buyer->userid])
											->fetch('assoc');
					$buyer->cash -= $return_value["max_bet"] - $auction->act_price;
					
					$seller = $this->JediUserChars->get($auction->seller);
					$seller->cash += $auction->act_price;
					
					$this->JediUserChars->save($buyer);
					$this->JediUserChars->save($seller);
					//Item transfer
					if($auction->itemtype == "rings")
					{
						$item = $this->JediItemsJewelry->get($auction->itemid);
						$item->position = "inv";
						$item->ownerid = $auction->act_price_user;
						$this->JediItemsJewelry->save($item);
					}
					else
					{
						$item = $this->JediItemsWeapons->get($auction->itemid);
						$item->position = "inv";
						$item->ownerid = $auction->act_price_user;
						$this->JediItemsWeapons->save($item);
					}
					
					$log = $this->JediCityAhLog->newEmptyEntity();
					$log->auctionid = $auction->auctionid;
					$log->itemid = $auction->itemid;
					$log->itemtype = $auction->itemtype;
					$log->seller = $auction->seller;
					$log->buyer = $auction->act_price_user;
					$log->price = $auction->act_price;
					$log->type_of_buy = "time ended";
					$log->time = time();
					$this->JediCityAhLog->save($log);
				}
				
				$this->JediCityAhItems->delete($auction);
			}
		}
		
		if($this->request->getData("sell")) //alle schritte durchlaufen -> Auktion in Datenbank eintragen
		{
			if($this->request->getData("itemtype") == "rings")
			{
				$item = $this->JediItemsJewelry->get($this->request->getData("itemid"));
				if($item->position != "inv")
				{
					return $this->Flash->error("Item nicht in deinem Inventar");
				}
				else
				{
					$item->position = "auc";
					$this->JediItemsJewelry->save($item);
				}	
			}
			else
			{
				$item = $this->JediItemsWeapons->get($this->request->getData("itemid"));
				if($item->position != "inv")
				{
					return $this->Flash->error("Item nicht in deinem Inventar");
				}
				else
				{
					$item->position = "auc";
					$this->JediItemsWeapons->save($item);
				}
			}
			
			$auction = $this->JediCityAhItems->newEmptyEntity();
			$auction->itemid = $this->request->getData("itemid");
			$auction->itemtype = $this->request->getData("itemtype");
			$auction->seller = $this->Auth->User("id");
			$auction->starttime = time();
			$auction->endtime = time() + $this->request->getData("duration");
			$auction->duration = $this->request->getData("duration");
			$auction->inc_time = 1800;
			$auction->startprice = $this->request->getData("start");
			$auction->inc_price = $this->request->getData("step");
			$auction->instant_price = $this->request->getData("insta");
			$auction->direct_price = $this->request->getData("direct");
			$auction->act_price = 0;
			$auction->act_price_user = 0;
			$this->JediCityAhItems->save($auction);
			$this->redirect(["action" => "auction"]);
		}
		
		//aktion cancel
		if($action == "cancel")
		{
			$auction = $this->JediCityAhItems->get($id);
			if($auction->seller == $this->Auth->User("id") && $auction->act_price_user == 0) //meine Auktion und keine Gebote
			{
				$cancel_auc = true;	
			}
			else
			{
				$cancel_auc = false;
			}
			$this->set("cancel_auc",$cancel_auc);
			$this->set("auction",$auction);
			
			if($this->request->getQuery("a") == "yes")
			{
				if($auction->seller == $this->Auth->User("id") && $auction->act_price_user == 0) //meine Auktion und keine Gebote
				{
					if($auction->itemtype == "rings")
					{
						$item = $this->JediItemsJewelry->get($auction->itemid);
						$item->position = "inv";
						$this->JediItemsJewelry->save($item);
					}
					else
					{
						$item = $this->JediItemsWeapons->get($auction->itemid);
						$item->position = "inv";
						$this->JediItemsWeapons->save($item);
					}
					
					$this->JediCityAhItems->delete($auction);
					
					return $this->redirect(["action" => "auction"]);
				}
				else
				{
					$cancel_auc = false;
				}
			}
		}
		
		//einzelne Auktion
		if(isset($id))
		{
			$auction = $this->JediCityAhItems->get($id);
			
			//meine Auktion?
			$my_act = false;
			if($auction->seller == $this->Auth->User("id"))
			{
				$my_act = true;
			}
			$this->set("my_act",$my_act);
			
			//Sofortkauf / instant buy - Direktkauf / direct buy
			if(isset($this->request->getAttribute("params")["?"]["buy"]))
			{
				if($this->request->getAttribute("params")["?"]["buy"] == "instant" && $auction->instant_price != 0 && $auction->act_price_user == 0)
				{
					if($char->cash >= $auction->instant_price)
					{
						$char->cash -= $auction->instant_price;
						$this->JediUserChars->save($char);
						
						$seller = $this->JediUserChars->get($auction->seller);
						$seller->cash += $auction->instant_price;
						$this->JediUserChars->save($seller);
						
						$log = $this->JediCityAhLog->newEmptyEntity();
						$log->auctionid = $auction->auctionid;
						$log->itemid = $auction->itemid;
						$log->itemtype = $auction->itemtype;
						$log->seller = $auction->seller;
						$log->buyer = $this->Auth->User("id");
						$log->price = $auction->instant_price;
						$log->type_of_buy = "instant";
						$log->time = time();
						$this->JediCityAhLog->save($log);
					}
					else
					{
						$this->Flash->error("You have not enough cash");
					}
				}
				elseif($this->request->getAttribute("params")["?"]["buy"] == "direct" && $auction->direct_price != 0)
				{
					if($char->cash >= $auction->direct_price)
					{
						//Alter höchstbietender bekommt sein max_bet zurück
						$return_value = $connection->execute(
										'SELECT max_bet, userid, time FROM jedi_city_ah_offers
										WHERE auctionid = :auctionid AND userid = :userid
											AND time = (SELECT MAX(time)
											FROM jedi_city_ah_offers
											WHERE auctionid = :auctionid
											AND userid = :userid)',
											["auctionid" => $id,
											"userid" => $auction->act_price_user])
											->fetch('assoc');	
						
						$old_max_bet_user = $this->JediUserChars->get($auction->act_price_user);
						$old_max_bet_user->cash += $return_value["max_bet"];
						$this->JediUserChars->save($old_max_bet_user);
						
						$char->cash -= $auction->direct_price;
						$this->JediUserChars->save($char);
						
						$seller = $this->JediUserChars->get($auction->seller);
						$seller->cash += $auction->direct_price;
						$this->JediUserChars->save($seller);
							
						$log = $this->JediCityAhLog->newEmptyEntity();
						$log->auctionid = $auction->auctionid;
						$log->itemid = $auction->itemid;
						$log->itemtype = $auction->itemtype;
						$log->seller = $auction->seller;
						$log->buyer = $this->Auth->User("id");
						$log->price = $auction->direct_price;
						$log->type_of_buy = "direct";
						$log->time = time();
						$this->JediCityAhLog->save($log);
					}
					else
					{
						$this->Flash->error("You have not enough money");
					}
				}
				//Item transfer
				if($auction->itemtype == "rings")
				{
					$item = $this->JediItemsJewelry->get($auction->itemid);
					$item->position = "inv";
					$item->ownerid = $this->Auth->User("id");
					$this->JediItemsJewelry->save($item);
				}
				else
				{
					$item = $this->JediItemsWeapons->get($auction->itemid);
					$item->position = "inv";
					$item->ownerid = $this->Auth->User("id");
					$this->JediItemsWeapons->save($item);
				}
				$this->JediCityAhItems->delete($auction);
				$this->redirect(["action" => "auction"]);
			}
			
			//ein Gebot wurde abgegeben
			if($this->request->getData("bet"))
			{
				$gebot = $this->request->getData("gebot");
				
				$max_bet = $connection->execute("SELECT MAX(max_bet), userid FROM jedi_city_ah_offers
												WHERE auctionid = :auctionid",
									["auctionid" => $id])
									->fetch();
				$max_bet = $max_bet[0];
				
				//Noch kein Gebot da, also ist es das Stargebot
				if($auction->act_price == 0)
				{
					if($char->cash >= $gebot)
					{
						$char->cash -= $gebot;
						
						$auction->endtime += $auction->inc_time;
						$auction->act_price = $gebot;
						$auction->act_price_user = $this->Auth->User("id");
						
						$offer = $this->JediCityAhOffers->newEmptyEntity();
						$offer->auctionid = $id;
						$offer->userid = $this->Auth->User("id");
						$offer->max_bet = $gebot;
						$offer->time = time();
						
						$this->JediCityAhOffers->save($offer);
						$this->JediUserChars->save($char);
						$this->JediCityAhItems->save($auction);
						
						return $this->redirect(["action" => "auction"]);
					}
					else
					{
						$this->Flash->error("Not enough cash");
					}
				}
				//Neuer Höchstbietender
				elseif($gebot > $max_bet && $auction->act_price_user != $this->Auth->User("id"))
				{
					if($char->cash >= $gebot)
					{
						$char->cash -= $gebot;
						
						//Alter höchstbietender bekommt sein max_bet zurück
						$return_value = $connection->execute(
										'SELECT max_bet, userid, time FROM jedi_city_ah_offers
										WHERE auctionid = :auctionid AND userid = :userid
											AND time = (SELECT MAX(time)
											FROM jedi_city_ah_offers
											WHERE auctionid = :auctionid
											AND userid = :userid)',
											["auctionid" => $id,
											"userid" => $auction->act_price_user])
											->fetch('assoc');	
						
						$old_max_bet_user = $this->JediUserChars->get($auction->act_price_user);
						$old_max_bet_user->cash += $return_value["max_bet"];
						$this->JediUserChars->save($old_max_bet_user);
						
						$auction->endtime += $auction->inc_time;
						$auction->act_price = $gebot;
						$auction->act_price_user = $this->Auth->User("id");
						
						$offer = $this->JediCityAhOffers->newEmptyEntity();
						$offer->auctionid = $id;
						$offer->userid = $this->Auth->User("id");
						$offer->max_bet = $gebot;
						$offer->time = time();
						
						$this->JediCityAhOffers->save($offer);
						$this->JediUserChars->save($char);
						$this->JediCityAhItems->save($auction);
						
					}
					else
					{
						$this->Flash->error("Not enough cash");
					}
				}
				//bisheriger Höchstbietender erhöht sein Maximalgebot
				elseif($auction->act_price_user == $this->Auth->User("id"))
				{
					$return_value = $connection->execute(
										'SELECT max_bet, userid, time FROM jedi_city_ah_offers
										WHERE auctionid = :auctionid AND userid = :userid
											AND time = (SELECT MAX(time)
											FROM jedi_city_ah_offers
											WHERE auctionid = :auctionid
											AND userid = :userid)',
											["auctionid" => $id,
											"userid" => $this->Auth->User("id")])
											->fetch('assoc');
											
					$price_change = $gebot - $return_value["max_bet"];

					if($char->cash >= $price_change)
					{
						$char->cash -= $price_change;					
				
						$offer = $this->JediCityAhOffers->newEmptyEntity();
						$offer->auctionid = $id;
						$offer->userid = $this->Auth->User("id");
						$offer->max_bet = $gebot;
						$offer->time = time();
						
						$this->JediUserChars->save($char);
						$this->JediCityAhOffers->save($offer);
					}
					else
					{
						$this->Flash->error("Not enough cash");
					}
				}
				//Wenn Bieter nicht den MaxBet des Höchstbietenden erreicht
				elseif($auction->act_price_user != $this->Auth->User("id") && $gebot <= $max_bet)
				{
					if($char->cash >= $gebot)
					{
						$auction->endtime += $auction->inc_time;
						$auction->act_price = $gebot;
						
						$offer = $this->JediCityAhOffers->newEmptyEntity();
						$offer->auctionid = $id;
						$offer->userid = $this->Auth->User("id");
						$offer->max_bet = $gebot;
						$offer->time = time();
						
						$this->JediCityAhOffers->save($offer);
						$this->JediUserChars->save($char);
						$this->JediCityAhItems->save($auction);
					}
					else
					{
						$this->Flash->error("Not enough cash");
					}
				}
			}
			
			//check for instant buy
			$instant = false; //standard wert
			if($auction->instant_price != 0) // instant buy ist vorgesehen
			{
				if($auction->act_price_user == 0) //kein gebot bisher
				{
					$instant = true;
				}
			}
			
			//check for direct buy
			$direct = false;
			if($auction->direct_price != 0) //Direct buy ist vorgesehen
			{
				if(($auction->act_price + $auction->inc_price) < $auction->direct_price)
				{
					$direct = true;
				}
			}
			
			//check for cancle
			$cancel = false;
			if($auction->act_price_user == 0)
			{
				if($auction->seller == $this->Auth->User("id"))
				{
					$cancel = true;
				}
			}
			
			//if you are high highest bidder
			$max_bet = false;
			$max_bet_credits = null;
			if($auction->act_price_user == $this->Auth->User("id"))
			{
				$max_bet = true;
				
				$max_bet_credits = $connection->execute(
										'SELECT max_bet, userid, time FROM jedi_city_ah_offers
										WHERE auctionid = :auctionid AND userid = :userid
											AND time = (SELECT MAX(time)
											FROM jedi_city_ah_offers
											WHERE auctionid = :auctionid
											AND userid = :userid)',
											["auctionid" => $auction->auctionid,
											"userid" => $this->Auth->User("id")])
											->fetch('assoc');		
			}
			
			$auction->seller = $this->JediUserChars->get($auction->seller);
			
			if($auction->act_price_user != 0)
			{
				$auction->act_price_user = $this->JediUserChars->get($auction->act_price_user);
			}		
			
			if($auction->itemtype == "rings")
			{
				$item = $this->JediItemsJewelry->get($auction->itemid);
			}
			else
			{
				$item = $this->JediItemsWeapons->get($auction->itemid);
			}
			
			if($auction->act_price_user != "0")
			{
				$new_price = $auction->act_price + $auction->inc_price;
			}
			else
			{
				$new_price = $auction->startprice;	
			}
			
			$this->set("new_price",$new_price);
			$this->set("loot",$item);
			$this->set("auction",$auction);
			$this->set("instant",$instant);
			$this->set("direct",$direct);
			$this->set("cancel",$cancel);
			$this->set("max_bet",$max_bet);
			$this->set("max_bet_credits",$max_bet_credits);
		}
		
		//get all auction from one user
		if($view == "selling")
		{
			$seller = $this->request->getQuery("seller");
			$sellerid = $this->JediUserChars->find()->where(["username" => $seller])->first()->userid;
			$all = $this->JediCityAhItems->find()->where(["seller" => $sellerid])->all();
		}
		elseif($view == "new")
		{
			if(isset($this->request->getAttribute("params")["?"]["i"]))
			{
				//Dinge im inventar
				if($this->request->getAttribute('params')["?"]["i"] == "weapons")
				{
					$img = "weapons";
					$table = "JediItemsWeapons";
					$specialstat1 = "mindmg";
					$specialstat2 = "maxdmg";
				}
				elseif($this->request->getAttribute('params')["?"]["i"] == "rings")
				{
					$img = "rings";
					$table = "JediItemsJewelry";
					$specialstat1 = "crafted";
					$specialstat2 = "nodrop";           
				}
				elseif($this->request->getAttribute('params')["?"]["i"] == "bots")
				{
					$img = "bots";
					$table = "JediItemsBots";
					$specialstat1 = "crafted";
					$specialstat2 = "nodrop";
				}
				elseif($this->request->getAttribute('params')["?"]["i"] == "books")
				{
					$img = "books";
					$table = "JediItemsBooks";
					$specialstat1 = "crafted";
					$specialstat2 = "nodrop";
				}
				elseif($this->request->getAttribute('params')["?"]["i"] == "misc")
				{
					$img = "misc";
					$table = "JediItemsMisc";
					$specialstat1 = "crafted";
					$specialstat2 = "nodrop";
				}

				$query = $this->$table->find()
										->where(['ownerid' => $this->Auth->User("id")])
										->where(['position' => 'inv']);
				if(!empty($query))
				{
					$query->select([
						'itemid',
						'img',
						'stat1_mod' => 'SUBSTRING_INDEX(`stat1`, ",", 1)', 
						'stat1_value' => 'CAST(SUBSTRING_INDEX(`stat1`, ",", -1) AS UNSIGNED)',
						'stat1_stat' => 'SUBSTRING_INDEX(SUBSTRING_INDEX(`stat1`, ",", 2),",",-1)',
						'stat2_mod' => 'SUBSTRING_INDEX(`stat2`, ",", 1)', 
						'stat2_value' => 'CAST(SUBSTRING_INDEX(`stat2`, ",", -1) AS UNSIGNED)',
						'stat2_stat' => 'SUBSTRING_INDEX(SUBSTRING_INDEX(`stat2`, ",", 2),",",-1)',
						'stat3_mod' => 'SUBSTRING_INDEX(`stat3`, ",", 1)', 
						'stat3_value' => 'CAST(SUBSTRING_INDEX(`stat3`, ",", -1) AS UNSIGNED)',
						'stat3_stat' => 'SUBSTRING_INDEX(SUBSTRING_INDEX(`stat3`, ",", 2),",",-1)',
						'stat4_mod' => 'SUBSTRING_INDEX(`stat4`, ",", 1)', 
						'stat4_value' => 'CAST(SUBSTRING_INDEX(`stat4`, ",", -1) AS UNSIGNED)',
						'stat4_stat' => 'SUBSTRING_INDEX(SUBSTRING_INDEX(`stat4`, ",", 2),",",-1)',
						'stat5_mod' => 'SUBSTRING_INDEX(`stat5`, ",", 1)', 
						'stat5_value' => 'CAST(SUBSTRING_INDEX(`stat5`, ",", -1) AS UNSIGNED)',
						'stat5_stat' => 'SUBSTRING_INDEX(SUBSTRING_INDEX(`stat5`, ",", 2),",",-1)',
						'name', 'qlvl', 'reql', 'reqs', $specialstat1, $specialstat2,
						'price'
					]);

					$this->set("items", $this->paginate($query));
					$this->set("img",$img);
				}
			}
			
			if($this->request->getData("itemid"))
			{
				$img = $this->request->getData("img");
				if(!$img)
				{
					$img = $this->request->getData("itemtype");
				}
				
				$itemid = $this->request->getData("itemid");
				
				if($img == "rings")
				{
					$item = $this->JediItemsJewelry->get($itemid);
				}
				else
				{
					$item = $this->JediItemsWeapons->get($itemid);
				}
				$value = rand(($item->price*0.975) , ($item->price*1.025));
				$startprice = round(0.1*$value);
				
				$this->set("value",$value);
				$this->set("startprice",$startprice);
				$this->set("itemtype",$img);
				$this->set("itemid",$itemid);
			}
			
			if($this->request->getData("itemid") && $this->request->getData("itemtype")) //zweite seite der Angebotserstellung
			{
				$auction_data = $this->request->getData();
				
				if($auction_data["itemtype"] == "rings")
				{
					$item = $this->JediItemsJewelry->get($auction_data["itemid"]);
				}
				else
				{
					$item = $this->JediItemsWeapons->get($auction_data["itemid"]);
				}
				$auction_data["days"] = $auction_data["duration"] / 60 / 60 / 24;
				
				$this->set("type",$auction_data["itemtype"]);
				$this->set("item",$item);
				$this->set("second_page",true);
				$this->set("data",$auction_data);
			}
		}
		elseif($view == "sold")
		{
			$sold_items = $this->JediCityAhLog->find()->where(["seller" => $this->Auth->User("id")])->order(["time" => "DESC"]);
			foreach($sold_items as $key => $auction)
			{
				// Create from a timestamp
				$auction->time = FrozenTime::createFromTimestamp($auction->time, 'Europe/Berlin');
				
				if($auction->buyer != 0)
				{
					$auction->buyer = $this->JediUserChars->get($auction->buyer)->username;
				}
				else
				{
					$auction->buyer = "";
				}
				
				if($auction->itemtype == "rings")
				{
					$auction->item = $this->JediItemsJewelry->get($auction->itemid)->name;
				}
				else
				{
					$auction->item = $this->JediItemsWeapons->get($auction->itemid)->name;
				}
			}
			$this->set("sold",$sold_items);
		}
		elseif($view == "bought")
		{
			$bought_items = $this->JediCityAhLog->find()->where(["buyer" => $this->Auth->User("id")])->order(["time" => "DESC"]);
			foreach($bought_items as $key => $auction)
			{
				// Create from a timestamp
				$auction->time = FrozenTime::createFromTimestamp($auction->time, 'Europe/Berlin');
				
				$auction->buyer = $this->JediUserChars->get($auction->buyer)->username;
				if($auction->itemtype == "rings")
				{
					$auction->item = $this->JediItemsJewelry->get($auction->itemid)->name;
				}
				else
				{
					$auction->item = $this->JediItemsWeapons->get($auction->itemid)->name;
				}
			}
			$this->set("bought",$bought_items);
		}
		elseif($view == "bids")
		{
			//get all auction
			$all = $connection->execute('SELECT * FROM jedi_city_ah_items
										WHERE auctionid = ANY (SELECT auctionid FROM jedi_city_ah_offers WHERE userid = :userid GROUP BY auctionid)',
										["userid" => $this->Auth->User("id")])->fetchAll('assoc');
		}
		
		foreach($all as $key => $one)
		{
			$one->seller = $this->JediUserChars->get($one->seller);
			$one->bids = $this->JediCityAhOffers->find()->where(["auctionid" => $one->auctionid])->count();
			
			if($one->act_price_user != 0)
			{
				$one->max_bet_user = $this->JediUserChars->get($one->act_price_user);
			}		
			
			if($one->itemtype == "rings")
			{
				$one->item = $this->JediItemsJewelry->get($one->itemid);
			}
			else
			{
				$one->item = $this->JediItemsWeapons->get($one->itemid);
			}
		}
		$this->set("all",$all);
	}
	
	public function store()
	{
		
	}

    public function getUser($id)
    {
        return $this->JediUserChars->get($id);
    }
}
?>