<?php

namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;


class CharacterController extends AppController {

    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('main');
		$this->loadComponent('Quest');
		
    }

    public function overview()
    {			
		$this->LoadModel('JediUserChars');  
        $char = $this->JediUserChars->get($this->Authentication->getIdentity()->id);
        $this->set('char',$char);

        $this->LoadModel('JediUserSkills');  
        $skills = $this->JediUserSkills->get($this->Authentication->getIdentity()->id);

        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Authentication->getIdentity()->id]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->Authentication->getIdentity()->id]);        
		
		//Voraussetzungen für einen Quest erfüllt?
		if($this->Authentication->getIdentity()->id == 20)
		{
			$this->Quest->aktiviere_quest();
			$aktiviere = $this->Quest->pruefe_auf_quests($this->Authentication->getIdentity()->id, $char->location);

			if($aktiviere == 1)
			{
				$step_details = $this->Quest->getStepText($this->Authentication->getIdentity()->id);
				if($step_details["typ"] == "wait")
				{
					$this->set("quest_output",$this->Quest->wait($this->Authentication->getIdentity()->id));
				}
				$this->set("step_details",$step_details);
				$this->set("quest",true);
			}
		}
		
        //////////////////////////////////////////
		
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
        $skills['max_engery'] = $this->maxHealth->calc_maxEnergy($skills->cns, $skills->agi, $skills->level, $jewelry_model, $weapons_model);
        $this->set('skills',$skills);
    }
    public function abilities()
    {
        $this->LoadModel('JediUserSkills');  
        $skills = $this->JediUserSkills->get($this->request->getAttribute('identity')->id);

        if($skills->rsp > 0)
        {
            if($this->request->is(['put']))
            {
                $posts = $this->request->getData();
                $skills[$posts["train"]] += 1;
                $skills->rsp -= 1;
                $this->JediUserSkills->save($skills);
            }
        }
        $this->set('skills',$skills);
        
        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->request->getAttribute('identity')->id]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->request->getAttribute('identity')->id]);

        $this->set('tempBonus',$this->maxHealth->tempBonus($jewelry_model, $weapons_model));
    }
    public function forces()
    {
        $this->LoadModel('JediUserSkills');  
        $skills = $this->JediUserSkills->get($this->request->getAttribute('identity')->id);

        if($skills->rfp > 0)
        {
            if($this->request->is(['put']))
            {
                $posts = $this->request->getData();
                $skills[$posts["train"]] += 1;
                $skills->rfp -= 1;
                $this->JediUserSkills->save($skills);
            }
        }
        $this->set('skills',$skills);
        
        $this->LoadModel('JediItemsJewelry');
        $jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->request->getAttribute('identity')->id]);
        
        $this->LoadModel('JediItemsWeapons');
        $weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->request->getAttribute('identity')->id]);

        $this->set('tempBonus',$this->maxHealth->tempBonus($jewelry_model, $weapons_model)); 
        $this->set('tempBonusForces',$this->maxHealth->tempBonusForces($jewelry_model, $weapons_model));       
    }

    public $paginate = [	
			'sortWhitelist' => [
				'itemid', 'name', 'mindmg', 'maxdmg', 'price', 'qlvl', 'reql', 'reqs', 'stat1_value', 'stat2_value', 'stat3_value', 'stat4_value', 'stat5_value'
			],
			// Other keys here.
			'limit' => 9,
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

        $this->viewBuilder()->setLayout('react');

        $char = $this->JediUserChars->get($this->request->getAttribute('identity')->id);
        $char->skills = $this->JediUserSkills->get($this->request->getAttribute('identity')->id);

        
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
                $this->redirect(['action' => 'inventory']);
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

                    if(!empty($this->JediItemsWeapons->find()->where(['ownerid' => $this->request->getAttribute('identity')->id])
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

                    if(!empty($this->JediItemsJewelry->find()->where(['ownerid' => $this->request->getAttribute('identity')->id])
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
                $this->redirect(['action' => 'inventory']);
            }
        }
		elseif($char->actionid != 0 && $char->targetid != 0 && $char->targettime != 0)
		{
			$this->Flash->error(__('You are busy'));
		}
        $this->set("char",$char);
       
        //Ausgerüstete Dinge
        $query = $this->JediItemsWeapons->find()
                                        ->where(['ownerid' => $this->request->getAttribute('identity')->id])
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
                                        ->where(['ownerid' => $this->request->getAttribute('identity')->id])
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
                                        ->where(['ownerid' => $this->request->getAttribute('identity')->id])
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
                                    ->where(['ownerid' => 4])
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
					'name', 'qlvl', 'reql', 'reqs', 
					'price'
				]);

				$this->set("items", $this->paginate($query));
				$this->set("img",$img);
			}
        }
    }

    private function calc_xp_next_lvl($level)
    {
        return round(((15 * ($level * $level)) + 100 + pow(4,($level/12))));
    }
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('maxHealth');
        $this->loadComponent('Paginator');
    }
}

?>