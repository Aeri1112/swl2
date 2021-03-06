<?php

namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;


class CharacterController extends RestController {

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('maxHealth');
        $this->loadComponent("Quest", ["token" => $this->token, "payload" => $this->payload]);
        $this->loadModel("JediUserChars");
    }

    //api fetch user function character/user&id=
    public function user() {

        $requestID = $this->request->getQuery("id");
        if(!$this->request->getQuery("id")) {
            $requestID = $this->Auth->User("id");
        }
        $this->LoadModel('JediUserChars');  
        $char = $this->JediUserChars->get($requestID);

        $this->LoadModel('JediUserSkills');  
        $skills = $this->JediUserSkills->get($requestID);

        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $requestID]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $requestID]); 

        $response["char"] = $char;
        $response["skills"] = $skills;

        $this->set("user",$response);
    }

    //api fetch user function character/npc&id=
    public function npc() {

        $requestID = $this->request->getQuery("id");
        if(!$this->request->getQuery("id")) {
            $requestID = $this->Auth->User("id");
        }
        $char = $this->loadModel("JediNpcChars")->get($requestID);

        $skills = $this->loadModel("JediNpcSkills")->get($requestID);

        $this->LoadModel('JediItemsJewelryNpc');
        $jewelry_model = $this->JediItemsJewelryNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $requestID]);
        
        $this->LoadModel('JediItemsWeaponsNpc');
        $weapons_model = $this->JediItemsWeaponsNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $requestID]); 

        $response["char"] = $char;
        $response["skills"] = $skills;

        $this->set("user",$response);
    }

    public function SaveUser() {
        $this->LoadModel("JediUserChars");
        $this->LoadModel("JediUserSkills");

        $where = $this->request->getData("where");
        $what = $this->request->getData("what");
        $amount = $this->request->getData("amount");

        if($where == "char") {
            $char = $this->JediUserChars->get($this->Auth->User("id"));
            $char[$what] += $amount;
            $this->JediUserChars->save($char);
        }
    }

    public function overview()
    {			
        $token = $this->token;

        $payload = $this->payload;
        $this->set("token",$token);
        $this->set("payload",$payload->id);
        
		$this->LoadModel('JediUserChars');  
        $char = $this->JediUserChars->get($payload->id);
        $this->set('char',$char);

        $this->LoadModel('JediUserSkills');  
        $skills = $this->JediUserSkills->get($payload->id);

        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $payload->id]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $payload->id]);        

        //set location
		$char->location = "Overview";
		$this->JediUserChars->save($char);
		//pruefe auf quest
		$this->Quest->aktiviere_quest();
		$quest_comp = $this->Quest->pruefe_auf_quests($payload->id, $char->location);
		if($char->actionid != 0 || $char->targetid != 0 || $char->targettime != 0) {
			$this->set("quest",0);
		}
		else {
			$this->set("quest",$quest_comp);
		}

        //Levelup?
        if($skills->xp >= $this->calc_xp_next_lvl($skills->level))
        {
            $xp_ueberschuss = $skills->xp - $this->calc_xp_next_lvl($skills->level);
            $this->set('levelUp','ya');
            $skills->level += 1;
            $skills->rsp += 5;
            $skills->rfp += 3;
            $skills->xp = $xp_ueberschuss;
            $char->health = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model);
            $char->mana = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
            $char->energy = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);
			$this->JediUserSkills->save($skills);
            $this->JediUserChars->save($char);
        }

        $side["perc"] = round((abs($skills->side) / 32768 * 100),2);
        if($skills->side < 0)
        {
            $side["side"] = "dark";
            $side["white_begin"] = 50+($side["perc"]/2);
        }
        elseif($skills->side == 0)
        {
            $side["side"] = "neutral";
            $side["white_begin"] = 50;
        }
        else
        {
            $side["side"] = "light";
            $side["white_begin"] = 50-($side["perc"]/2);
        }
        $this->set('side',$side);

        $skills['next_level_xp'] = $this->calc_xp_next_lvl($skills->level);
        $skills['max_health'] = $this->maxHealth->calc_maxHp($skills->cns, $skills->level, $jewelry_model, $weapons_model); 
        $skills['max_mana'] = $this->maxHealth->calc_maxMana($skills->spi, $skills->itl, $skills->level, $jewelry_model, $weapons_model);
        $skills['max_energy'] = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);

        $skills["level_width"] = round($skills["xp"] * 100 / $skills["next_level_xp"], 2);
        $skills["health_width"] = round($char["health"] * 100 / $skills["max_health"]);
        $skills["mana_width"] = round($char["mana"] * 100 / $skills["max_mana"]);
        $skills["energy_width"] = round($char["energy"] * 100 / $skills["max_energy"]);
        
        $this->set('skills',$skills);
        $this->set("rsp",$skills->rsp);
        $this->set("rfp",$skills->rfp);
    }

    public function abilities()
    {
        $char = $this->JediUserChars->get($this->Auth->User("id"));
        //set location
		$char->location = "Abis";
		$this->JediUserChars->save($char);
		//pruefe auf quest
		$this->Quest->aktiviere_quest();
		$quest_comp = $this->Quest->pruefe_auf_quests($this->Auth->User("id"), $char->location);
		if($char->actionid != 0 || $char->targetid != 0 || $char->targettime != 0) {
			$this->set("quest",0);
		}
		else {
			$this->set("quest",$quest_comp);
		}

        $this->LoadModel('JediUserSkills');  
        //Hier nur abis abfragen, um über react loopen zu können
        $skills = $this->JediUserSkills->find()->select(["cns","agi","lsa","lsd","dex","tac","spi","itl"])->where(["userid" => $this->Auth->User("id")]);
        
        //dann nochmal die skillpoints abfragen
        $skillPoints = $this->JediUserSkills->find()->select(["rsp"])->where(["userid" => $this->Auth->User("id")])->first();
        $forcePoints = $this->JediUserSkills->find()->select(["rfp"])->where(["userid" => $this->Auth->User("id")])->first();

        $this->set('skills',$skills);
        
        //skillen
        if($skillPoints->rsp > 0)
        {
            if($this->request->is(['post']))
            {
                $skills = $this->JediUserSkills->get($this->Auth->User("id"));
                $posts = $this->request->getData("train");
                $skills[$posts] += 1;
                $skills->rsp -= 1;
                $this->JediUserSkills->save($skills);
            }
        }
        //dann nochmal die skillpoints abfragen
        $skillPoints = $this->JediUserSkills->find()->select(["rsp"])->where(["userid" => $this->Auth->User("id")])->first();
        $forcePoints = $this->JediUserSkills->find()->select(["rfp"])->where(["userid" => $this->Auth->User("id")])->first();

        $skills->rfp = $forcePoints;
        $skills->rsp = $skillPoints;
        $this->set("rsp",$skillPoints->rsp);
        $this->set("rfp",$forcePoints->rfp);

        
        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);

        $this->set('tempBonus',$this->maxHealth->tempBonus($jewelry_model, $weapons_model));
    }

    public function points($points) {
        $this->LoadModel('JediUserSkills'); 
        $skillPoints = $this->JediUserSkills->find()->select([$points])->where(["userid" => $this->Auth->User("id")])->first();
        $this->set("points",$skillPoints);
    }

    public function forces()
    {
        $char = $this->JediUserChars->get($this->Auth->User("id"));
        //set location
		$char->location = "Forces";
		$this->JediUserChars->save($char);
		//pruefe auf quest
		$this->Quest->aktiviere_quest();
		$quest_comp = $this->Quest->pruefe_auf_quests($this->Auth->User("id"), $char->location);
		if($char->actionid != 0 || $char->targetid != 0 || $char->targettime != 0) {
			$this->set("quest",0);
		}
		else {
			$this->set("quest",$quest_comp);
		}

        $this->LoadModel('JediUserSkills');  

        $skills = $this->JediUserSkills->get(["userid" => $this->Auth->User("id")]);
        
        //dann nochmal die skillpoints abfragen
        $skillPoints = $this->JediUserSkills->find()->select(["rfp"])->where(["userid" => $this->Auth->User("id")])->first();
        $abiPoints = $this->JediUserSkills->find()->select(["rsp"])->where(["userid" => $this->Auth->User("id")])->first();
        
        if($skillPoints->rfp > 0)
        {
            if($this->request->is(['post']))
            {
                $skills = $this->JediUserSkills->get($this->Auth->User("id"));
                $posts = $this->request->getData("train");
                $skills[$posts] += 1;
                $skills->rfp -= 1;
                $this->JediUserSkills->save($skills);
            }
        }
        //dann nochmal die skillpoints abfragen
        $skillPoints = $this->JediUserSkills->find()->select(["rfp"])->where(["userid" => $this->Auth->User("id")])->first();
        $abiPoints = $this->JediUserSkills->find()->select(["rsp"])->where(["userid" => $this->Auth->User("id")])->first();

        $this->set("rfp",$skillPoints->rfp);
        $this->set("rsp",$abiPoints->rsp);

        $skills = $this->JediUserSkills->get(["userid" => $this->Auth->User("id")]);
        $this->set('skills',$skills);
        
        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Auth->User("id")]);

        $this->set('tempBonusForces',$this->maxHealth->tempBonusForces($jewelry_model, $weapons_model));       
    }

    public $paginate = [	
			'sortWhitelist' => [
				'itemid', 'name', 'mindmg', 'maxdmg', 'price', 'qlvl', 'reql', 'reqs', 'stat1_value', 'stat2_value', 'stat3_value', 'stat4_value', 'stat5_value'
			],
			// Other keys here.
			'limit' => 10,
			'order' => ['itemid' => 'desc']
    ];

    public function inventory()
    {
        $this->loadModel("JediItemsJewelry");
        $this->loadModel("JediItemsWeapons");
        $this->loadModel("JediItemsBooks");
        $this->loadModel("JediItemsBots");
        $this->loadModel("JediItemsMisc");
        $this->LoadModel('JediUserChars'); 
        $this->LoadModel("JediUserSkills");

        $char = $this->JediUserChars->get($this->Auth->User("id"));
        $char->skills = $this->JediUserSkills->get($this->Auth->User("id"));

        //set location
		$char->location = "Inventar";
		$this->JediUserChars->save($char);
		//pruefe auf quest
		$this->Quest->aktiviere_quest();
		$quest_comp = $this->Quest->pruefe_auf_quests($this->Auth->User("id"), $char->location);
		if($char->actionid != 0 || $char->targetid != 0 || $char->targettime != 0) {
			$this->set("quest",0);
		}
		else {
			$this->set("quest",$quest_comp);
		}
        
        if($this->request->getParam('pass') && $char->actionid == 0 && $char->targetid == 0 && $char->targettime == 0)
        {
            //Dequip
            if($this->request->getParam('pass')[0] == "dequip")
            {
                $item = $this->request->getParam('pass')[1];
                
                if($item == "weapon")
                {
                    $char->item_hand = 0;
                    $this->JediUserChars->save($char);

                    $item = $this->JediItemsWeapons->get($this->request->getParam('pass')[2]);
                    $item->position = "inv";
                    $this->JediItemsWeapons->save($item);
                }
                elseif($item == "ring1")
                {
                    $char->item_finger1 = 0;
                    $this->JediUserChars->save($char);

                    $item = $this->JediItemsJewelry->get($this->request->getParam('pass')[2]);
                    $item->position = "inv";
                    $this->JediItemsJewelry->save($item);
                }
                elseif($item == "ring2")
                {
                    $char->item_finger2 = 0;
                    $this->JediUserChars->save($char);

                    $item = $this->JediItemsJewelry->get($this->request->getParam('pass')[2]);
                    $item->position = "inv";
                    $this->JediItemsJewelry->save($item);
                }
                #$this->redirect(['action' => 'inventory']);
            }

            //Equip
            if($this->request->getParam('pass')[0] == "equip")
            {
                $item = $this->request->getParam('pass')[1];
                $itemid = $this->request->getParam('pass')[2];

                if($item == "weapons")
                {
                    $weapon = $this->JediItemsWeapons->get($itemid);

                    //Voraussetzungen nicht erfüllt
                    if($weapon->reql > $char->skills->level OR $weapon->reqs > $char->skills->dex)
                    {
                        $this->Flash->error("You do not meet the requirements");
                        return $this->redirect(["action" => 'inventory']);
                    }

                    if(!empty($this->JediItemsWeapons->find()->where(['ownerid' => $this->Auth->User("id")])
                                                        ->where(['itemid' => $itemid])
                                                        ->where(['position' => 'inv'])
                                                        ->first()))
                    {
                        if($char->item_hand == 0)
                        {
                            $char->item_hand = $itemid;
                        }
                        else
                        {
                            $replaced_weapon = $this->JediItemsWeapons->get($char->item_hand);
                            $replaced_weapon->position = "inv";
                            $this->JediItemsWeapons->save($replaced_weapon);
                            
                            $char->item_hand = $itemid;
                        }
                        
                        $weapon->position = "eqp";

                        $this->JediItemsWeapons->save($weapon); 
                        $this->JediUserChars->save($char);
                    }
                    else
                    {
                        $this->Flash->error(__('not your Weapon or not in your inventory'));
                    } 
                }
                elseif($item == "rings")
                {
                    $ring = $this->JediItemsJewelry->get($itemid);

                    //Voraussetzungen nicht erfüllt
                    if($ring->reql > $char->skills->level OR $ring->reqs > $char->skills->dex)
                    {
                        $this->Flash->error("You do not meet the requirements");
                        return $this->redirect(["action" => 'inventory']);
                    }

                    if(!empty($this->JediItemsJewelry->find()->where(['ownerid' => $this->Auth->User("id")])
                                                        ->where(['itemid' => $itemid])
                                                        ->where(['position' => 'inv'])
                                                        ->first()))
                    {
                        if($char->item_finger1 == 0)
                        {
                            $char->item_finger1 = $itemid;
                        }
                        else
                        {
							if($char->item_finger2 != 0)
							{
								$replaced_ring = $this->JediItemsJewelry->get($char->item_finger2);
								$replaced_ring->position = "inv";
								$this->JediItemsJewelry->save($replaced_ring);
							}
              
                            $char->item_finger2 = $itemid;
                        }
                        $ring = $this->JediItemsJewelry->get($itemid);
                        $ring->position = "eqp";

                        $this->JediItemsJewelry->save($ring);
                        $this->JediUserChars->save($char);
                    }
                    else
                    {
                        $this->Flash->error(__('not your Ring or not in your inventory'));
                    }    
                }
                #$this->redirect(['action' => 'inventory']);
            }
        }
		elseif($char->actionid != 0 && $char->targetid != 0 && $char->targettime != 0)
		{
			$this->set("error","you can not switch Items. You are currently doing something else");
		}
        $this->set("char",$char);
       
        //Ausgerüstete Dinge
        $query = $this->JediItemsWeapons->find()
                                        ->where(['ownerid' => $this->Auth->User("id")])
                                        ->where(['itemid' => $char->item_hand])
                                        ->where(['position' => 'eqp'])
                                        ->first();
        if(!empty($query))
        {
            $query->stat1 = explode(",", $query->stat1);
            $query->stat1 = implode(" ", $query->stat1);
            $query->stat2 = explode(",", $query->stat2);
            $query->stat2 = implode(" ", $query->stat2);
            $query->stat3 = explode(",", $query->stat3);
            $query->stat3 = implode(" ", $query->stat3);
            $query->stat4 = explode(",", $query->stat4);
            $query->stat4 = implode(" ", $query->stat4);
            $query->stat5 = explode(",", $query->stat5);
            $query->stat5 = implode(" ", $query->stat5);
        
            $this->set("act_weapon",$query);
        }                        
        
        $query = $this->JediItemsJewelry->find()
                                        ->where(['ownerid' => $this->Auth->User("id")])
                                        ->where(['itemid' => $char->item_finger1])
                                        ->where(['position' => 'eqp'])
                                        ->first();
        if(!empty($query))
        {
            $query->stat1 = explode(",", $query->stat1);
            $query->stat1 = implode(" ", $query->stat1);
            $query->stat2 = explode(",", $query->stat2);
            $query->stat2 = implode(" ", $query->stat2);
            $query->stat3 = explode(",", $query->stat3);
            $query->stat3 = implode(" ", $query->stat3);
            $query->stat4 = explode(",", $query->stat4);
            $query->stat4 = implode(" ", $query->stat4);
            $query->stat5 = explode(",", $query->stat5);
            $query->stat5 = implode(" ", $query->stat5);
        
            $this->set("act_jewelry1",$query);
        } 

        $query = $this->JediItemsJewelry->find()
                                        ->where(['ownerid' => $this->Auth->User("id")])
                                        ->where(['itemid' => $char->item_finger2])
                                        ->where(['position' => 'eqp'])
                                        ->first();
        if(!empty($query))
        {
            $query->stat1 = explode(",", $query->stat1);
            $query->stat1 = implode(" ", $query->stat1);
            $query->stat2 = explode(",", $query->stat2);
            $query->stat2 = implode(" ", $query->stat2);
            $query->stat3 = explode(",", $query->stat3);
            $query->stat3 = implode(" ", $query->stat3);
            $query->stat4 = explode(",", $query->stat4);
            $query->stat4 = implode(" ", $query->stat4);
            $query->stat5 = explode(",", $query->stat5);
            $query->stat5 = implode(" ", $query->stat5);

            $this->set("act_jewelry2",$query);
        }

        if(isset($this->request->getAttribute("params")["?"]))
        {
            //Dinge im inventar
            if($this->request->getAttribute('params')["?"]["id"] == "weapons")
            {
                $img = "weapons";
                $table = "JediItemsWeapons";
                $specialstat1 = "mindmg";
                $specialstat2 = "maxdmg";
            }
            elseif($this->request->getAttribute('params')["?"]["id"] == "rings")
            {
                $img = "rings";
                $table = "JediItemsJewelry";
                $specialstat1 = "crafted";
                $specialstat2 = "nodrop";           
            }
            elseif($this->request->getAttribute('params')["?"]["id"] == "bots")
            {
                $img = "bots";
                $table = "JediItemsBots";
                $specialstat1 = "crafted";
                $specialstat2 = "nodrop";
            }
            elseif($this->request->getAttribute('params')["?"]["id"] == "books")
            {
                $img = "books";
                $table = "JediItemsBooks";
                $specialstat1 = "crafted";
                $specialstat2 = "nodrop";
            }
            elseif($this->request->getAttribute('params')["?"]["id"] == "misc")
            {
                $img = "misc";
                $table = "JediItemsMisc";
                $specialstat1 = "crafted";
                $specialstat2 = "nodrop";
            }

            $query = $this->$table->find()
                                    ->where(['ownerid' => $this->Auth->User("id")])
                                    ->where(['position' => 'inv']);

            if($this->request->getQuery('search')) {

                $searchFor = $this->request->getQuery('search');
                $this->set("integer",is_numeric($searchFor));
                if(is_numeric($searchFor)) {
                    $searchFor = intval($searchFor);
                    $query = $query->where(["OR" => [["name LIKE" => "%".$searchFor."%"], 
                                                    ["mindmg" => $searchFor],
                                                    ["maxdmg" => $searchFor],
                                                    ["stat1 LIKE" => "%".$searchFor."%"],
                                                    ["stat2 LIKE" => "%".$searchFor."%"],
                                                    ["stat3 LIKE" => "%".$searchFor."%"],
                                                    ["stat4 LIKE" => "%".$searchFor."%"],
                                                    ["stat5 LIKE" => "%".$searchFor."%"]]]);
                }
                else {
                    $query = $query->where(["OR" => [["name LIKE" => "%".$searchFor."%"],
                                                    ["stat1 LIKE" => "%".$searchFor."%"],
                                                    ["stat2 LIKE" => "%".$searchFor."%"],
                                                    ["stat3 LIKE" => "%".$searchFor."%"],
                                                    ["stat4 LIKE" => "%".$searchFor."%"],
                                                    ["stat5 LIKE" => "%".$searchFor."%"]]]);
                }
            }

			if(!empty($query))
			{
				$query->select([
					'itemid',
                    'img',
                    $specialstat1,
                    $specialstat2,
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
					'name', 'qlvl', 'reql', 'reqs', 'consumable', 'sizex',
					'price'
				]);
                $this->set("totalItems",$query->count());
                $this->set("items", $this->paginate($query));
				$this->set("img",$img);
			}
        }
    }

    private function calc_xp_next_lvl($level)
    {
        return round(((15 * ($level * $level)) + 100 + pow(4,($level/12))));
    }
}

?>