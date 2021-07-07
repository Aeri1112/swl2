<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class FightComponent extends Component
{
    public $components = ['maxHealth', 'Treasure'];

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->JediFights = TableRegistry::get('JediFights');
        $this->JediFightsPlayers = TableRegistry::get('JediFightsPlayers');
        $this->JediUserChars = TableRegistry::get('JediUserChars');
        $this->JediUserSkills = TableRegistry::get('JediUserSkills');
        $this->JediFightLocks = TableRegistry::get('JediFightLocks');
        $this->JediNpcChars = TableRegistry::get('JediNpcChars');
        $this->JediNpcSkills = TableRegistry::get('JediNpcSkills');
        $this->JediItemsJewelryNpc = TableRegistry::get('JediItemsJewelryNpc');
        $this->JediItemsWeaponsNpc = TableRegistry::get('JediItemsWeaponsNpc');
        $this->JediItemsJewelry = TableRegistry::get('JediItemsJewelry');
        $this->JediItemsWeapons = TableRegistry::get('JediItemsWeapons');
        $this->JediFightReports = TableRegistry::get('JediFightReports');
        $this->JediUserStatistics = TableRegistry::get('JediUserStatistics');
        $this->JediEventsSingleRanking = TableRegistry::get('JediEventsSingleRanking');
        $this->JediEventsSingleFightReports = TableRegistry::get('JediEventsSingleFightReports');
        $this->JediKwl = TableRegistry::get('JediKwl');
        $this->Bonus = $this->maxHealth;
        $this->Loot = $this->Treasure;
    }

    public function fight($id)
    {
        $comments = false;

        $fight_data = $this->JediFights->get($id);

        $timeleft = $fight_data->opentime + $fight_data->startin - time();
        
        if ($timeleft > 0)
        {
            $this->getController()->redirect(['controller' => 'character', 'action' => 'overview']);
        }
        else
        {
          //fightid lock
          $fight_locks = $this->JediFightLocks->newEntity();
          $fight_locks->fightid = $id;
          $fight_locks->since = time();
          $this->JediFightLocks->save($fight_locks);
        }

        //Member
        $members = $this->JediFightsPlayers->find()->where(['fightid' => $id])->all();
        
		//Userlevel für mitwachsenden NPC
		$user_for_npc_1 = $this->JediFightsPlayers->find()->where(["fightid" => $id])->where(["npc" => "n"])->first();
		$user_for_npc_1->char = $this->JediUserChars->get($user_for_npc_1->userid);
		$user_for_npc_1->skills = $this->JediUserSkills->get($user_for_npc_1->userid);
		
        //DB aufräumen
        $query = $this->JediFightsPlayers->query();
        $query->delete()
            ->where(['fightid' => $id])
            ->execute();
        $query = $this->JediFights->query();
        $query->delete()
            ->where(['fightid' => $id])
            ->execute();

        $i = -1;
        $team0 = array();
        $team0_level = 0;
        $team1 = array();
        $team1_level = 0;

        foreach ($members as $key => $member) {
            $i++;

            if($member->npc == "y")
            {
                $member->char = $this->JediNpcChars->get($member->userid);
                $member->skills = $this->JediNpcSkills->get($member->userid);
                $member->tempBonus = $this->Bonus->tempBonus($this->JediItemsJewelryNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]),$this->JediItemsWeaponsNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
                $member->tempBonusForces = $this->Bonus->tempBonusForces($this->JediItemsJewelryNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]),$this->JediItemsWeaponsNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
                $npcid = $member->char->userid;
				//Maxhealth + maxmana berechnen - vor der addition, da in der funktion die Boni bereits ermittelt werden               
                $member->maxhealth = $this->Bonus->calc_maxHp($member->skills->cns, $member->skills->level, $this->JediItemsJewelryNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]), $this->JediItemsWeaponsNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
                $member->maxmana = $this->Bonus->calc_maxMana($member->skills->spi, $member->skills->itl, $member->skills->level, $this->JediItemsJewelryNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]), $this->JediItemsWeaponsNpc->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
            }
            elseif($member->npc == "n")
            {
                $member->char = $this->JediUserChars->get($member->userid);
                $member->skills = $this->JediUserSkills->get($member->userid);
                $member->tempBonus = $this->Bonus->tempBonus($this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]),$this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
                $member->tempBonusForces = $this->Bonus->tempBonusForces($this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]),$this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
            
                //Meister/Pada XP Bonus
                //Ich bin größer 75 und habe keine 0 als masterid bedeutet ich bin ein Meister und habe einen Schüler
                //ich bin der meister
                if($member->skills->level >= 75 && $member->char->masterid != 0)
                {
                    $member->char->meisterid = $member->userid;
                    $member->char->padaid = $member->char->masterid;
                    $member->tempBonus["tmppxp"] = $member->tempBonus["tmppxp"] + 3;
                }
                //ich bin der Pada
                elseif($member->skills->level < 75 && $member->char->masterid != 0)
                {
                    $member->char->meisterid = $member->char->masterid;
                    $member->char->padaid = $member->userid;
                    $member->tempBonus["tmppxp"] = $member->tempBonus["tmppxp"] + 5;
                }
                //Maxhealth + maxmana berechnen - vor der addition, da in der funktion die Boni bereits ermittelt werden               
                $member->maxhealth = $this->Bonus->calc_maxHp($member->skills->cns, $member->skills->level, $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]), $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
                $member->maxmana = $this->Bonus->calc_maxMana($member->skills->spi, $member->skills->itl, $member->skills->level, $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]), $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $member->userid]));
            
                if($fight_data->type2 == "event")
                {
                    $member->char->health = $member->maxhealth;
                    $member->char->mana = $member->maxmana;
                }           
            }


            //Werte addieren
            if($member->skills->cns > 0) 
			{
				$member->skills->cns = $member->skills->cns + $member->tempBonus["tmpcns"];
			}
			if($member->skills->agi > 0) 
			{
				$member->skills->agi = $member->skills->agi + $member->tempBonus["tmpagi"];
			}
			if($member->skills->spi > 0) 
			{
				$member->skills->spi = $member->skills->spi + $member->tempBonus["tmpspi"];
			}
			if($member->skills->itl > 0) 
			{
				$member->skills->itl = $member->skills->itl + $member->tempBonus["tmpitl"];
			}
			if($member->skills->tac > 0) 
			{
				$member->skills->tac = $member->skills->tac + $member->tempBonus["tmptac"];
			}
			if($member->skills->dex > 0) 
			{
				$member->skills->dex = $member->skills->dex + $member->tempBonus["tmpdex"];
			}
			if($member->skills->lsa > 0) 
			{
				$member->skills->lsa = $member->skills->lsa + $member->tempBonus["tmplsa"];
			}
			else
			{
				$member->skills->lsa = 1;
			}
			if($member->skills->lsd > 0) 
			{
				$member->skills->lsd = $member->skills->lsd + $member->tempBonus["tmplsd"];
			}
			else
			{
				$member->skills->lsd = 1;
			}
            //LSD auf 1,3 des Levels begrenzen?
            if($member->skills->lsd > ($member->skills->level*1.3))
            {
                $member->skills->lsd = $member->skills->level*1.3;
            }

			if($member->skills->fspee > 0)
			{
				$member->skills->fspee = $member->skills->fspee + $member->tempBonusForces["tmpfspee"];
			}
            if($member->skills->fjump > 0)
			{
				$member->skills->fjump = $member->skills->fjump + $member->tempBonusForces["tmpfjump"];
            }
			if($member->skills->fpush > 0)
			{
				$member->skills->fpush = $member->skills->fpush + $member->tempBonusForces["tmpfpush"];
			}
            if($member->skills->fpull > 0)
			{
				$member->skills->fpull = $member->skills->fpull + $member->tempBonusForces["tmpfpull"];
			}	
            if($member->skills->fseei > 0)
			{
				$member->skills->fseei = $member->skills->fseei + $member->tempBonusForces["tmpfseei"];
			}
            if($member->skills->fsabe > 0)
			{
				$member->skills->fsabe = $member->skills->fsabe + $member->tempBonusForces["tmpfsabe"];
			}

            if($member->skills->fproj > 0) 
			{
				$member->skills->fproj = $member->skills->fproj + $member->tempBonusForces["tmpfproj"];
			}
            if($member->skills->fpers > 0) 
			{
				$member->skills->fpers = $member->skills->fpers + $member->tempBonusForces["tmpfpers"];
			}
            if($member->skills->fblin > 0) 
			{
				$member->skills->fblin = $member->skills->fblin + $member->tempBonusForces["tmpfblin"];
			}
            if($member->skills->fconf > 0) 
			{
				$member->skills->fconf = $member->skills->fconf + $member->tempBonusForces["tmpfconf"];
			}
            if($member->skills->fheal > 0) 
			{
				$member->skills->fheal = $member->skills->fheal + $member->tempBonusForces["tmpfheal"];
			}
            if($member->skills->fteam > 0) 
			{
				$member->skills->fteam = $member->skills->fteam + $member->tempBonusForces["tmpfteam"];
			}
            if($member->skills->fabso > 0) 
			{
				$member->skills->fabso = $member->skills->fabso + $member->tempBonusForces["tmpfabso"];
			}
            if($member->skills->fprot > 0) 
			{
				$member->skills->fprot = $member->skills->fprot + $member->tempBonusForces["tmpfprot"];
			}
            if($member->skills->fthro > 0) 
			{
				$member->skills->fthro = $member->skills->fthro + $member->tempBonusForces["tmpfthro"];
			}
            if($member->skills->frage > 0) 
			{
				$member->skills->frage = $member->skills->frage + $member->tempBonusForces["tmpfrage"];
			}
            if($member->skills->fgrip > 0) 
			{
				$member->skills->fgrip = $member->skills->fgrip + $member->tempBonusForces["tmpfgrip"];
			}
            if($member->skills->fdrai > 0) 
			{
				$member->skills->fdrai = $member->skills->fdrai + $member->tempBonusForces["tmpfdrai"];
			}
            if($member->skills->fthun > 0) 
			{
				$member->skills->fthun = $member->skills->fthun + $member->tempBonusForces["tmpfthun"];
			}
            if($member->skills->fchai > 0) 
			{
				$member->skills->fchai = $member->skills->fchai + $member->tempBonusForces["tmpfchai"];
			}
            if($member->skills->fdead > 0) 
			{
				$member->skills->fdead = $member->skills->fdead + $member->tempBonusForces["tmpfdead"];
			}
            if($member->skills->fdest > 0) 
			{
				$member->skills->fdest = $member->skills->fdest + $member->tempBonusForces["tmpfdest"];
			}
            if($member->skills->ftnrg > 0) 
			{
				$member->skills->ftnrg = $member->skills->ftnrg + $member->tempBonusForces["tmpftnrg"];
			}
            if($member->skills->frvtl > 0) 
			{
				$member->skills->frvtl = $member->skills->frvtl + $member->tempBonusForces["tmpfrvtl"];
			}

            //Geschlechtsspezifischer text
            if($member->char->sex == "f")
            {
                $member->herhim = "her";
                $member->heshe = "she";
                $member->herhis = "her";
                $member->herhimself = "herself";
            }
            else
            {
                $member->herhim = "him";
                $member->heshe = "he";
                $member->herhis = "his";
                $member->herhimself = "himself";                
            }

            //Waffe "anlegen"
            //Waffe ist angelegt
            if($member->char->item_hand != 0)
            {
                if($member->npc == "n")
                {
                    $wpn = $this->JediItemsWeapons->get($member->char->item_hand);
                }
                else
                {
                    $wpn = $this->JediItemsWeaponsNpc->get($member->char->item_hand);
                }
                $member->iswpn = 1;
                $member->wpn = $wpn;
            }
            //Keine Waffe
            else
            {
                $member->wpn = new \stdClass();
                $member->wpn->mindmg = 1;
                $member->wpn->maxdmg = round(sqrt($member->skills->cns / 2) + sqrt($member->skills->level));
                $member->iswpn = 0;
                $member->wpn->reqskill = 1;
            }
			///////////////////////////////////////////////////
			//das selbe für den user für den mitwachsenden npc
			//Waffe "anlegen"
            //Waffe ist angelegt
            if($user_for_npc_1->char->item_hand != 0)
            {
                $wpn = $this->JediItemsWeapons->get($user_for_npc_1->char->item_hand);
                $user_for_npc_1->iswpn = 1;
                $user_for_npc_1->wpn = $wpn;
            }
            //Keine Waffe
            else
            {
                $user_for_npc_1->wpn = new \stdClass();
                $user_for_npc_1->wpn->mindmg = 1;
                $user_for_npc_1->wpn->maxdmg = round(sqrt($user_for_npc_1->skills->cns / 2) + sqrt($user_for_npc_1->skills->level));
                $user_for_npc_1->iswpn = 0;
                $user_for_npc_1->wpn->reqskill = 1;
            }
			
			//Preferences
            $fight_pref = explode(',',$member->char->fpreferences);
            $member->char->stance = $fight_pref[0];
            $member->char->initiative = $fight_pref[1];
            $member->char->heroic = $fight_pref[2];
            $member->char->innocents = $fight_pref[3];
            $member->char->surroundings = $fight_pref[4];
            $member->char->prefereddef = $fight_pref[5];
            $member->char->preferedoff = $fight_pref[6];
			if(isset($fight_pref[7]))
			{
				$member->switchoff_1 = $fight_pref[7];
			}
			else
			{
				$member->switchoff_1 = "";
			}
			if(isset($fight_pref[8]))
			{
				$member->switchoff_2 = $fight_pref[8];
			}
			else
			{
				$member->switchoff_2 = "";
			}
			if(isset($fight_pref[9]))
			{
				$member->switchoff_3 = $fight_pref[9];
			}
			else
			{
				$member->switchoff_3 = "";
			}
			
            $member->playerscast = array();
            $member->forcesdie = array();
			
            if (!empty($member->skills->fspee)) {
              for ($f = 0; $f < $member->skills->fspee; $f++){
				if($member->switchoff_1 == 0 OR $member->switchoff_2 == 0 OR $member->switchoff_3 == 0)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 0);
				}
              }
            }
            if (!empty($member->skills->fjump)) {
              for ($f = 0; $f < $member->skills->fjump; $f++) {
                if($member->switchoff_1 == 1 OR $member->switchoff_2 == 1 OR $member->switchoff_3 == 1)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 1);
				}
              }
            }
            if (!empty($member->skills->fpull)) {
              for ($f = 0; $f < $member->skills->fpull; $f++) {
                if($member->switchoff_1 == 2 OR $member->switchoff_2 == 2 OR $member->switchoff_3 == 2)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 2);
				}
              }
            }
            if (!empty($member->skills->fpush)) {
              for ($f = 0; $f < $member->skills->fpush; $f++) {
                if($member->switchoff_1 == 3 OR $member->switchoff_2 == 3 OR $member->switchoff_3 == 3)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 3);
				}
              }
            }
            if (!empty($member->skills->fseei)) {
              for ($f = 0; $f < $member->skills->fseei; $f++) {
                if($member->switchoff_1 == 4 OR $member->switchoff_2 == 4 OR $member->switchoff_3 == 4)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 4);
				}
              }
            }
            if (!empty($member->skills->fsabe)) {
              for ($f = 0; $f < $member->skills->fsabe; $f++) {
                if($member->switchoff_1 == 5 OR $member->switchoff_2 == 5 OR $member->switchoff_3 == 5)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 5);
				}
              }
            }
            
            if (!empty($member->skills->fpers)) {
              for ($f = 0; $f < $member->skills->fpers; $f++) {
                if($member->switchoff_1 == 6 OR $member->switchoff_2 == 6 OR $member->switchoff_3 == 6)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 6);
				}
              }
            }
            if (!empty($member->skills->fproj)) {
              for ($f = 0; $f < $member->skills->fproj; $f++) {
                if($member->switchoff_1 == 7 OR $member->switchoff_2 == 7 OR $member->switchoff_3 == 7)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 7);
				}
              }
            }
            if (!empty($member->skills->fblin)) {
              for ($f = 0; $f < $member->skills->fblin; $f++) {
                if($member->switchoff_1 == 8 OR $member->switchoff_2 == 8 OR $member->switchoff_3 == 8)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 8);
				}
              }
            }
            if (!empty($member->skills->fconf)) {
              for ($f = 0; $f < $member->skills->fconf; $f++) {
                if($member->switchoff_1 == 9 OR $member->switchoff_2 == 9 OR $member->switchoff_3 == 9)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 9);
				}
              }
            }
            if (!empty($member->skills->fheal)) {
              for ($f = 0; $f < $member->skills->fheal; $f++) {
                if($member->switchoff_1 == 10 OR $member->switchoff_2 == 10 OR $member->switchoff_3 == 10)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 10);
				}
              }
            }
            if (!empty($member->skills->fteam) && $fight_data->type != "duel" && $fight_data->type != "duelnpc") {
              for ($f = 0; $f < $member->skills->fteam; $f++) {
                if($member->switchoff_1 == 11 OR $member->switchoff_2 == 11 OR $member->switchoff_3 == 11)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 11);
				}
              }
            }
            if (!empty($member->skills->fprot)) {
              for ($f = 0; $f < $member->skills->fprot; $f++) {
                if($member->switchoff_1 == 12 OR $member->switchoff_2 == 12 OR $member->switchoff_3 == 12)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 12);
				}
              }
            }
            if (!empty($member->skills->fabso)) {
              for ($f = 0; $f < $member->skills->fabso; $f++) {
                if($member->switchoff_1 == 13 OR $member->switchoff_2 == 13 OR $member->switchoff_3 == 13)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 13);
				}
              }
            }
            if (!empty($member->skills->frvtl)  && ($fight_data->type != "duel" && $fight_data->type != "duelnpc" && $fight_data->type != "coopnpc")) {
              for ($f = 0; $f < $member->skills->frvtl; $f++) {
                if($member->switchoff_1 == 14 OR $member->switchoff_2 == 14 OR $member->switchoff_3 == 14)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 14);
				}
              }
            }
            if (!empty($member->skills->fthro)) {
              for ($f = 0; $f < $member->skills->fthro; $f++) {
                if($member->switchoff_1 == 15 OR $member->switchoff_2 == 15 OR $member->switchoff_3 == 15)
				{
					
				}
				else
				{
					array_push($member->forcesdie,15);
				}
              }
            }
            if (!empty($member->skills->frage)) {
              for ($f = 0; $f < $member->skills->frage; $f++) {
                if($member->switchoff_1 == 16 OR $member->switchoff_2 == 16 OR $member->switchoff_3 == 16)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 16);
				}
              }
            }
            if (!empty($member->skills->fgrip)) {
              for ($f = 0; $f < $member->skills->fgrip; $f++) {
                if($member->switchoff_1 == 17 OR $member->switchoff_2 == 17 OR $member->switchoff_3 == 17)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 17);
				}
              }
            }
            if (!empty($member->skills->fdrai)) {
              for ($f = 0; $f < $member->skills->fdrai; $f++) {
                if($member->switchoff_1 == 18 OR $member->switchoff_2 == 18 OR $member->switchoff_3 == 18)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 18);
				}
              }
            }
            if (!empty($member->skills->fthun)) {
              for ($f = 0; $f < $member->skills->fthun; $f++) {
                if($member->switchoff_1 == 19 OR $member->switchoff_2 == 19 OR $member->switchoff_3 == 19)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 19);
				}
              }
            }
            if (!empty($member->skills->fchai) && $fight_data->type != "duel" && $fight_data->type != "duelnpc") {
              for ($f = 0; $f < $member->skills->fchai; $f++) {
                if($member->switchoff_1 == 20 OR $member->switchoff_2 == 20 OR $member->switchoff_3 == 20)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 20);
				}
              }
            }
            if (!empty($member->skills->fdest)) {
              for ($f = 0; $f < $member->skills->fdest; $f++) {
                if($member->switchoff_1 == 21 OR $member->switchoff_2 == 21 OR $member->switchoff_3 == 21)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 21);
				}
              }
            }
            if (!empty($member->skills->fdead)) {
              for ($f = 0; $f < $member->skills->fdead; $f++) {
                if($member->switchoff_1 == 22 OR $member->switchoff_2 == 22 OR $member->switchoff_3 == 22)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 22);
				}
              }
            }//&& $fight_data->type != "duel" && $fight_data->type != "duelnpc"
            if (!empty($member->skills->ftnrg) ) {
              for ($f = 0; $f < $member->skills->ftnrg; $f++) {
                if($member->switchoff_1 == 23 OR $member->switchoff_2 == 23 OR $member->switchoff_3 == 23)
				{
					
				}
				else
				{
					array_push($member->forcesdie, 23);
				}
              }
            }
			
            shuffle($member->forcesdie);
			shuffle($member->forcesdie);
			
            if($fight_data->type == "duel" OR $fight_data->type == "coop")
            {
                $member->char->heroic = 0;
            }
            elseif($member->npc == "y")
            {
                $member->char->heroic = 0;
            }
            else
            {
                if($member->char->heroic == 0)
                {
                    $member->char->heroic = $member->char->health * 27 / 100;
                }
                elseif($member->char->heroic == 1)
                {
                    $member->char->heroic = $member->char->health * 17 / 100;
                }
				elseif($member->char->heroic == 2)
				{
					$member->char->heroic = $member->char->health * 7 / 100;
				}
            }

            //NPC Variation
            if($member->npc == "y")
            {
                if($member->skills->level == 150)
                {
                    $member->skills->level = rand(122,155);
                    $member->skills->tac = rand(75,125);
                    $member->skills->itl = rand(65,120);
                    $member->char->health = round(2333*$member->skills->level/100);
                }
                elseif($member->skills->level == 100)
                {
                    $member->skills->level = rand(66,133);
                    $member->skills->tac = rand(65,125);
                    $member->skills->itl = rand(55,100);
                    $member->char->health = round(2555*$member->skills->level/100);
                }
                elseif($member->skills->level == 75)
                {
                    $member->skills->level = rand(66,133);
                    $member->skills->tac = rand(65,125);
                    $member->skills->itl = rand(50,100);
                    $member->char->health = round(2444*$member->skills->level/100);
                }
                elseif($member->skills->level == 50)
                {
                    $member->skills->level = rand(44,88);
                    $member->skills->tac = rand(55,75);
                    $member->skills->itl = rand(45,100);
                    $member->char->health = round(2333*$member->skills->level/100);
                }
                //stärkerer
                elseif($member->skills->userid == 12) {
                    $member->skills->level = rand($user_for_npc_1->skills->level,$user_for_npc_1->skills->level*1.2);
					$member->skills->cns = rand($user_for_npc_1->skills->level*0.8,$user_for_npc_1->skills->level);
                    $member->skills->tac = rand($user_for_npc_1->skills->level*0.7,$user_for_npc_1->skills->level*0.9);
                    $member->skills->itl = rand($user_for_npc_1->skills->level*0.8,$user_for_npc_1->skills->level);
					$member->skills->agi = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.8);
					$member->skills->lsa = rand($user_for_npc_1->skills->level*0.8,$user_for_npc_1->skills->level);
					$member->skills->lsd = rand($user_for_npc_1->skills->level*1.3,$user_for_npc_1->skills->level*1.5);
					$member->skills->spi = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.8);
					$member->skills->dex = rand($user_for_npc_1->skills->level*0.8,$user_for_npc_1->skills->level);
                    $member->char->health = round(($member->skills->level * 2) + ($member->skills->cns * 3) + 20);
					$member->char->mana = round(($member->skills->level * 1.5) + ($member->skills->spi * 4) + ($member->skills->itl / 2.5) + 10);
					$member->wpn->mindmg = $user_for_npc_1->wpn->mindmg;
					$member->wpn->maxdmg = round($user_for_npc_1->wpn->maxdmg*0.9);
                }
                //schwächerer
                elseif($member->skills->userid == 11) {
                    $member->skills->level = rand($user_for_npc_1->skills->level*0.8,$user_for_npc_1->skills->level);
					$member->skills->cns = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.75);
                    $member->skills->tac = rand($user_for_npc_1->skills->level*0.5,$user_for_npc_1->skills->level*0.65);
                    $member->skills->itl = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.75);
					$member->skills->agi = rand($user_for_npc_1->skills->level*0.4,$user_for_npc_1->skills->level*0.55);
					$member->skills->lsa = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.75);
					$member->skills->lsd = rand($user_for_npc_1->skills->level*1.1,$user_for_npc_1->skills->level*1.25);
					$member->skills->spi = rand($user_for_npc_1->skills->level*0.4,$user_for_npc_1->skills->level*0.55);
					$member->skills->dex = rand($user_for_npc_1->skills->level*0.6,$user_for_npc_1->skills->level*0.75);
                    $member->char->health = round(($member->skills->level * 2) + ($member->skills->cns * 3) + 20);
					$member->char->mana = round(($member->skills->level * 1.5) + ($member->skills->spi * 4) + ($member->skills->itl / 2.5) + 10);
					$member->wpn->mindmg = round($user_for_npc_1->wpn->mindmg*0.8);
					$member->wpn->maxdmg = round($user_for_npc_1->wpn->maxdmg*0.7);
                }
                //DROID
				elseif($member->skills->level == 3)
                {
                    $member->skills->level = $user_for_npc_1->skills->level;
					$member->skills->cns = $user_for_npc_1->skills->level*0.7;
                    $member->skills->tac = $user_for_npc_1->skills->level*0.5;
                    $member->skills->itl = $user_for_npc_1->skills->level*0.7;
					$member->skills->agi = $user_for_npc_1->skills->level*0.5;
					$member->skills->lsa = $user_for_npc_1->skills->level*0.8;
					$member->skills->lsd = $user_for_npc_1->skills->level;
					$member->skills->spi = $user_for_npc_1->skills->level*0.5;
					$member->skills->dex = $user_for_npc_1->skills->level*0.7;
                    $member->char->health = round(($member->skills->level * 2) + ($member->skills->cns * 3) + 20);
					$member->char->mana = round(($member->skills->level * 1.5) + ($member->skills->spi * 4) + ($member->skills->itl / 2.5) + 10);
					$member->wpn->mindmg = $user_for_npc_1->wpn->mindmg;
					$member->wpn->maxdmg = round($user_for_npc_1->wpn->maxdmg*0.7);
                }
                elseif($member->skills->level == 2)
                {
                    $member->skills->level = rand(2,12);
					$member->skills->cns = rand(13,33);
                    $member->skills->tac = rand(5,35);
                    $member->skills->itl = rand(5,35);
					$member->skills->agi = rand(3,45);
                    $member->char->health = round(1999*$member->skills->level/100);
                }
				//BIG RAT
				elseif($member->skills->level == 1)
                {
                    $member->skills->level = rand(15,30);
					$member->skills->cns = rand(25,40);
                    $member->skills->tac = rand(20,40);
                    $member->skills->itl = rand(20,40);
					$member->skills->agi = rand(30,45);
					$member->skills->lsa = rand(35,50);
					$member->skills->lsd = rand(50,50);
					$member->skills->spi = rand(10,15);
					$member->skills->dex = rand(20,35);
                    $member->char->health = round(2333*$member->skills->level/100);
					$member->char->mana = ($member->skills->level * 1.5) + ($member->skills->spi * 4) + ($member->skills->itl / 2.5) + 10 ;
                }
				//REEK
				elseif($member->skills->level == 4)
                {
                    $member->skills->level = rand(35,50);
					$member->skills->cns = $member->skills->level;
                    $member->skills->tac = $member->skills->level*1.2;
                    $member->skills->itl = $member->skills->level*1.2;
					$member->skills->agi = $member->skills->level*1.3;
					$member->skills->lsa = $member->skills->level*1.4;
					$member->skills->lsd = $member->skills->level*1.3;
					$member->skills->spi = $member->skills->level*0.4;
					$member->skills->dex = $member->skills->level*1.4;
                    $member->char->health = round(2666*$member->skills->level/100);
					$member->char->mana = ($member->skills->level * 1.5) + ($member->skills->spi * 4) + ($member->skills->itl / 2.5) + 10 ;
                }
            }
            //Fight variablen init
            $member->bonus = 0;
            $member->malus = 0;

            $member->fcmalus = 0;
            $member->fcmalus2 = 0;

            $member->hcmalus = 0;
            $member->hcmalus2 = 0;

            $member->ccmalus = 0;
            $member->ccmalus2 = 0;

            $member->cmmalus = 0;
            $member->cmmalus2 = 0;

            $member->playerstate = "";
            $member->playerstatetime2 = "";
            $member->playerstate2 = "";
            $member->playerstatetime3 = "";
            $member->playerstate3 = "";
            $member->playerstatetime4 = "";
            $member->playerstate4 = 0;
            $member->playerstatetime5 = "";
            $member->playerstate5 = "";
            $member->playerstatetime5 = "";
            $member->protectstate = "";
            $member->absorbstate = "";
            $member->lsdstate = 0;
            $member->playersroutine = 0;
            $member->playersroutine_ammount = "";
            $member->players_last_c = "";
            $member->players_last_cc = "";
            $member->pcastsoff = 0;
            $member->pcastsdef = 0;
            $member->manaconoff = 0;
            $member->manacondef = 0;
            $member->staffdamage = 0;
            $member->offdamage = 0;
            $member->defdamage = 0;
			$member->kbonus = 0;
			
            for ($i=0; $i < 24; $i++)
            { 
                $member->playerscast[$i] = 0;
            }

            //Zu Team zuweisen und level zählen
            if($member->teamid == 0)
            {
                $member->teamPosition = count($team0);
                $team0[] = $member;
                $team0_level += $member->skills->level;
            }
            elseif($member->teamid == 1)
            {
                $member->teamPosition = count($team1);
                $team1[] = $member;
                $team1_level += $member->skills->level;                
            }
        //Ende Durchlauf jedes Members    
        }
        
        $players = $i;

        $damage_1 = "0";
        $damage_2 = "0";
        $evade_1 = "0";
        $evade_2 = "0";
        $fighters0 ="";
        $fighters1 ="";

        for ($i = 0; $i < count($team0); $i++)
        {
            if (count($team0) == 1)
            {

            }
            elseif (count($team0)-1 == $i)
            {
                $fighters0 .= " and ";
            }
            elseif ($i > 0)
            {
                $fighters0 .= ", ";
            }
            $tmp = $team0[$i];
            $fighters0 .= $tmp->char->username."(".$tmp->skills->level.")";
            $team0mittel=$team0_level/count($team0);
        }
          for ($i = 0; $i < count($team1); $i++)
        {
            if (count($team1) == 1)
            {

            }
            elseif (count($team1)-1 == $i)
            { 
                $fighters1 .= " and "; 
            }
            elseif ($i > 0) 
            { 
                $fighters1 .= ", "; 
            }
            $tmp = $team1[$i];
            $fighters1 .= $tmp->char->username."(".$tmp->skills->level.")";
            $team1mittel=$team1_level/count($team1);
        }
        $fight_report = "$fighters0 vs. $fighters1<br><br>";

        $team_0 = $team0;
        $team_1 = $team1;
        $i = rand(0,1);
        $quitfight = 0;

        //Main Fight
        while (((count($team0) > 0) && (count($team1) > 0)) && ($quitfight==0))
        {
            $i++;
            //Fighter picken
            if ($i % 2 != 0)
            {
                $rand_member_team1 = array_rand($team1);
                $rand_member_team0 = array_rand($team0);
                $p = $team1[$rand_member_team1];
                $pv = $team0[$rand_member_team0];
                $team_p = $team1;
                $team_pv = $team0;

                $ftc = $rand_member_team1." von (".count($team_p)." Team1) vs. ".$rand_member_team0." von (".count($team_pv)." Team0)";
            }
            else
            {
                $rand_member_team1 = array_rand($team1);
                $rand_member_team0 = array_rand($team0);
                $p = $team0[$rand_member_team0];
                $pv = $team1[$rand_member_team1];
                $team_p = $team0;
                $team_pv = $team1;

                $ftc = $rand_member_team0." von (".count($team_p)." Team0) vs. ".$rand_member_team1." von (".count($team_pv)." Team1)";
            }

            
            
            if($comments) $fight_report .= $ftc." - round:".$i."<br>";

            //kampfwerte berechnen
            for ($i2 = 0; $i2 < 2; $i2++)
            {
                $p1=$p;
                $p2=$pv;
                $t1=$team_p;
                $t2=$team_pv;
              
                if($i2 % 2 != 0)
                {
                    $pv = $p1;
                    $p = $p2;
                    $team_pv = $t1;
                    $team_p = $t2;
                }
                else
                {
                    $pv = $p2;
                    $p = $p1;
                    $team_pv = $t2;
                    $team_p = $t1;
                }
                if($comments) $fight_report .= "p= ".$p->char->userid." - pv= ".$pv->char->userid."";
                if($comments) $fight_report .= "Werte fuer ".$p->teamPosition." von Team".$p->teamid." berechnet: ";

                //Hitchance
                $att =    pow($p->skills->agi,0.7) * 7
                        + pow($p->skills->dex,0.7) * 10
                        + pow($p->skills->lsa,0.7) * 20
                        + pow($p->skills->tac,0.7) * 5
                        + pow($p->skills->itl,0.7) * 4
                        + pow($p->skills->cns,0.7) * 3;

                $def =    pow($pv->skills->agi,0.7) * 10
                        + pow($pv->skills->dex,0.7) * 10
                        + pow($pv->skills->lsd,0.7) * 25
                        + pow($pv->skills->tac,0.7) * 9
                        + pow($pv->skills->cns,0.7) * 3;

                if ($att != 0)
                {
                    $diff = 100 * ($att - $def) / max($att, $def);
                    if ($diff < 0)
                    {
                        $hilf = -1;
                    }
                    else
                    {
                        $hilf = 1;
                    }
                $chance = round(50 + 50 * $hilf * (1 - pow(0.98, abs($diff))));
                }
                else
                {
                    $chance = 0;
                }

                $p->hitchance = $chance;
                if($comments) $fight_report .= "<br>HC1: ".$p->hitchance;
                
                if ( $p->hitchance < 15 ) { $p->hitchance = "15"; }
                elseif ( $p->hitchance > 90 ) { $p->hitchance = "90"; }
                
                //Fightchance
                $p->fightchance = round(9.5 * sqrt(($p->skills->lsa*2/3+$p->skills->agi*1/3+$p->skills->dex*1/3) * 55 / $p->skills->level));
                if ($p->skills->lsa < 1) { $p->fightchance = 0; }
                if ($p->fightchance > 95) { $p->fightchance = 95; }
                
                if($comments)   $fight_report .= " - FC1: ".$p->fightchance;
                
                //Castchance
                $p->castchance = round(9.5 * sqrt(($p->skills->spi*2/3+$p->skills->itl/3) * 55 / $p->skills->level));

                if ($p->skills->spi < 1) { $p->castchance = 0; }
                if ($p->castchance > 95) { $p->castchance = 95; }
                
                if($comments) $fight_report .= " - CC1: ".$p->castchance;

                //Hitchance - Mage
                $att =  pow($p->skills->dex,0.7) * 13 +
                        pow($p->skills->spi,0.7) * 15 +
                        pow($p->skills->itl,0.7) * 11;

                $def =  pow($pv->skills->agi,0.7) * 13 +
                        pow($pv->skills->tac,0.7) * 15 +
                        pow($pv->skills->spi,0.7) * 13 +
                        pow($pv->skills->itl,0.7) * 13;

                if ($att != 0)
                {
                    $diff = 100 * ($att - $def) / max($att, $def);
                    if ($diff < 0)
                    {
                        $hilf = -1;
                    }
                    else
                    {
                        $hilf = 1;
                    }
                    $chance = round(50 + 50 * $hilf * (1 - pow(0.98, abs($diff))));
                }
                else
                {
                    $chance = 0;
                }

                $p->hitchance_magic = $chance;

                if($comments) $fight_report .= " - HM1: ".$p->hitchance_magic;
                $docast = rand(1, 100);
                
                if ( $p->hitchance_magic < 15 )
                { 
                    $p->hitchance_magic = 15;
                }
                elseif ( $p->hitchance_magic > 90 )
                { 
                    $p->hitchance_magic = 90; 
                }
            
                //Klassifizierung
                //Mage
                if(( ($p->castchance/(2/3)) + ($p->hitchance_magic/(1/3)) / 2) > (($p->fightchance/(2/3)) + ($p->hitchance/(1/3)) / 2))
                {
                    $aaa=round(((($p->castchance/(2/3) + $p->hitchance_magic/(1/3))/2)
                                - (($p->fightchance/(2/3) + $p->hitchance/(1/3))/2)));

                    $aa=($p->skills->spi+$p->skills->itl/1.5+$p->skills->agi+$p->skills->dex)
                            -($p->skills->lsa+$p->skills->lsd+$p->skills->dex/1.5+$p->skills->agi/1.5);
                    $a=0;

                    //gegen extremskillung magier
                    if($p->skills->agi > ($p->skills->level*0.8))
                    { 
                        $p->fcmalus = round(($p->skills->agi*100) / ($p->skills->level*0.8)-100);
                        if($p->fcmalus > 33)
                        {
                            $p->fcmalus =  33;
                        }
                        if($p->fightchance - $p->fcmalus > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus;
                        }
                    }
                    if($p->skills->lsa > ($p->skills->level*0.7))
                    { 
                        $p->fcmalus = round(($p->skills->lsa*100) / ($p->skills->level*0.7)-100);
                        if($p->fcmalus > 33)
                        {
                            $p->fcmalus =  33;
                        }
                        if($p->fightchance - $p->fcmalus > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus;	
                        }
                    }
                    if($p->skills->dex > ($p->skills->level*0.7))
                    { 
                        $p->hcmalus=round(($p->skills->dex*100)/($p->skills->level*0.7)-100);
                        if($p->hcmalus>33)
                        {
                            $p->hcmalus =  33;
                        }
                        if($p->hitchance-$p->hcmalus > 15)
                        {
                            $p->hitchance=$p->hitchance-$p->hcmalus;	
                        }
                    }
                    if($p->skills->spi > ($p->skills->level*1.3))
                    { 
                        $p->ccmalus=round(($p->skills->spi*100)/($p->skills->level*1.3)-100);
                        if($p->ccmalus>33)
                        {
                            $p->ccmalus =  33;
                        }
                        if($p->castchance-$p->ccmalus > 15)
                        {
                            $p->castchance=$p->castchance-$p->ccmalus;	
                        }
                    }
                    if($p->skills->itl > ($p->skills->level*1.2))
                    { 
                        $p->cmmalus=round(($p->skills->itl*100)/($p->skills->level*1.2)-100);
                        if($p->cmmalus>33)
                        {
                            $p->cmmalus =  33;
                        }
                        if($p->hitchance_magic-$p->cmmalus > 15)
                        {
                            $p->hitchance_magic=$p->hitchance_magic-$p->cmmalus;	
                        }
                    }
                    if($p->skills->cns > ($p->skills->level*1.5))
                    { 
                        $p->ccmalus=round(($p->skills->cns*100)/($p->skills->level*1.5)-100);
                        if($p->ccmalus>33)
                        {
                            $p->ccmalus =  33;
                        }
                        if($p->castchance-$p->ccmalus > 15)
                        {
                            $p->castchance=$p->castchance-$p->ccmalus;	
                        }
                    }
                    if($p->maxhealth > ($p->skills->level*2 + $p->skills->cns * 3))
                    { 
                        $p->ccmalus2=round(($p->maxhealth*100)/(($p->skills->level*2 + $p->skills->cns * 3 + 20))-100);
                        $p->fcmalus2=round(($p->maxhealth*100)/(($p->skills->level*2 + $p->skills->cns * 3 + 20))-100);
                        if($p->ccmalus2 > 50)
                        {
                            $p->ccmalus2 =  50;
                        }
                        if($p->fcmalus2 > 50)
                        {
                            $p->fcmalus2 =  50;
                        }
                        if($p->ccmalus2 < 15) 
                        {
                            $p->ccmalus2 =  0;
                        }
                        if($p->fcmalus2 < 15)
                        {
                            $p->fcmalus2 =  0;
                        }
                        if($p->castchance - $p->ccmalus2 > 15)
                        {
                            $p->castchance = $p->castchance - $p->ccmalus2;	
                        }
                        if($p->fightchance - $p->fcmalus2 > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus2;	
                        }
						$p->ccmalus = $p->ccmalus + $p->ccmalus2 ;
                        $p->fcmalus = $p->fcmalus + $p->fcmalus2 ;
                    }
                    //relativieren
                    if ( $p->fightchance < 15 && $p->skills->lsa > 0) { $p->fightchance = 15; }
                    elseif  ( $p->fightchance > 70 ) { $p->fightchance = 70; }
                    if ( $p->castchance < 15 && $p->skills->spi > 0 ) { $p->castchance = 15; }
                    elseif ( $p->castchance > 80 ) { $p->castchance = 80; }
                    if ( $p->hitchance < 15 ) { $p->hitchance = 15; }
                    elseif ( $p->hitchance > 70 ) { $p->hitchance = 70; }
                    if ( $p->hitchance_magic < 25 ) { $p->hitchance_magic = 25; }
                    elseif ( $p->hitchance_magic > 80 ) { $p->hitchance_magic = 80; }    
                }
                //ab hier fighter
                else
                {
                    $aaa=round(((($p->fightchance/(2/3) + $p->hitchance/(1/3))/2) - (($p->castchance/(2/3) + $p->hitchance_magic/(1/3))/2)));
                    $aa=($p->skills->lsa+$p->skills->lsd+$p->skills->dex/1.5+$p->skills->agi/1.5)-($p->skills->spi+$p->skills->agi+$p->skills->dex);
                    $a=1;
                    if($p->skills->agi > ($p->skills->level*1.2))
                    { 
                        $p->fcmalus=round(($p->skills->agi*100)/($p->skills->level*1.1)-100);
                        if($p->fcmalus > 33) 
                        {
                            $p->fcmalus =  33;
                        }
                        if($p->fightchance-$p->fcmalus > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus;	
                        }
                    }
                    if($p->skills->lsa > ($p->skills->level*1.1))
                    { 
                        $p->fcmalus=round(($p->skills->lsa*100)/($p->skills->level*1.1)-100);
                        if($p->fcmalus > 33) 
                        {
                            $p->fcmalus =  33;
                        }
                        if($p->fightchance-$p->fcmalus > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus;	
                        }
                    }
                    if($p->skills->dex > ($p->skills->level*1.1))
                    { 
                        $p->hcmalus=round(($p->skills->dex*100)/($p->skills->level*1.1)-100);
                        if($p->hcmalus > 33)
                        {
                            $p->hcmalus =  33;
                        }
                        if($p->hitchance-$p->hcmalus > 15)
                        {
                            $p->hitchance = $p->hitchance - $p->hcmalus;	
                        }
                    }
                    if($p->skills->spi > ($p->skills->level*0.8))
                    { 
                        $p->ccmalus=round(($p->skills->spi*100)/($p->skills->level*0.8)-100);
                        if($p->ccmalus > 33)
                        {
                            $p->ccmalus =  33;
                        }
                        if($p->castchance-$p->ccmalus > 15)
                        {
                            $p->castchance = $p->castchance - $p->ccmalus;	
                        }
                    }
                    if($p->skills->itl > ($p->skills->level*1.2))
                    { 
                        $p->cmmalus=round(($p->skills->itl*100)/($p->skills->level*1.2)-100);
                        if($p->cmmalus > 33)
                        {
                            $p->cmmalus =  33;
                        }
                        if($p->hitchance_magic-$p->cmmalus > 15)
                        {
                            $p->hitchance_magic = $p->hitchance_magic - $p->cmmalus;	
                        }
                    }
                    if($p->skills->cns > ($p->skills->level*1.5))
                    { 
                        $p->ccmalus=round(($p->skills->cns*100)/($p->skills->level*1.5)-100);
                        if($p->ccmalus > 33)
                        {
                            $p->ccmalus =  33;
                        }
                        if($p->castchance-$p->ccmalus > 15)
                        {
                            $p->castchance = $p->castchance - $p->ccmalus;	
                        }
                    }
                    if($p->maxhealth > ($p->skills->level*2 + $p->skills->cns * 3))
                    { 
                        $p->ccmalus2=round(($p->maxhealth*100)/(($p->skills->level*2 + $p->skills->cns * 3 + 20))-100);
                        $p->fcmalus2=round(($p->maxhealth*100)/(($p->skills->level*2 + $p->skills->cns * 3 + 20))-100);
                        if($p->ccmalus2 > 50)
                        {
                            $p->ccmalus2 =  50;
                        }
                        if($p->fcmalus2 > 50)
                        {
                            $p->fcmalus2 =  50;
                        }
                        if($p->ccmalus2 < 15)
                        {
                            $p->ccmalus2 =  0;
                        }
                        if($p->fcmalus2 < 15)
                        {
                            $p->fcmalus2 =  0;
                        }
                        if($p->castchance - $p->ccmalus2 > 15)
                        {
                            $p->castchance = $p->castchance - $p->ccmalus2;	
                        }
                        if($p->fightchance - $p->fcmalus2 > 15)
                        {
                            $p->fightchance = $p->fightchance - $p->fcmalus2;	
                        }
						$p->ccmalus = $p->ccmalus + $p->ccmalus2 ;
						$p->fcmalus = $p->fcmalus + $p->fcmalus2 ;
                    }
                    //relativieren
                    if ( $p->fightchance < 15 && $p->skills->lsa > 0) { $p->fightchance = 15; }
                    elseif  ( $p->fightchance > 80 ) { $p->fightchance = 80; }
                    if ( $p->castchance < 25 && $p->skills->spi > 0 ) { $p->castchance = 25; }
                    elseif ( $p->castchance > 70 ) { $p->castchance = 70; }
                    if ( $p->hitchance < 25 ) { $p->hitchance = 25; }
                    elseif ( $p->hitchance > 80 ) { $p->hitchance = 80; }
                    if ( $p->hitchance_magic < 15 ) { $p->hitchance_magic = 15; }
                    elseif ( $p->hitchance_magic > 70 ) { $p->hitchance_magic = 70; }
                }

                $p->malus="";
                if(isset($p->fcmalus) && $p->fcmalus > 0 )
                {
                    $p->malus .= $p->fcmalus;
                }
                else
                {
                    $p->malus .="0";
                }
                $p->malus .=",";
                if(isset($p->hcmalus) && $p->hcmalus > 0)
                {
                    $p->malus .= $p->hcmalus;
                }
                else
                {
                    $p->malus .="0";
                }
                $p->malus .=",";
                if(isset($p->ccmalus) && $p->ccmalus > 0)
                {
                    $p->malus .= $p->ccmalus;
                }
                else
                {
                    $p->malus .="0";
                }
                $p->malus .=",";
                if(isset($p->cmmalus) && $p->cmmalus > 0)
                {
                    $p->malus .= $p->cmmalus;
                }
                else
                {
                    $p->malus .="0";
                }
                $p->malus .="";


                if($comments) $fight_report .= "<br>HC2: ".$p->hitchance;
                if($comments) $fight_report .= " - FC2: ".$p->fightchance;
                if($comments) $fight_report .= " - CC2: ".$p->castchance;
                if($comments) $fight_report .= " - HM2: ".$p->hitchance_magic;

                //jetzt noch die detailklasse mit dem itl-bonus & mana malus

                if($a==0)
                { //mage
                    if($aaa>=70)
                    { //prim�r
                        $p->itlbonus = round(2*sqrt($p->skills->level) + 2 * sqrt($p->itl));
                        if($aa < 0)
                        {
                            $aa = 0; 
                        }
                        $p->manaaddcon = round($aa/10/2);
                        $p->bonus = $aaa."% M,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus;
                        if ($comments) $fight_report .=  " dyn: ".$p->char->username." ".$aaa."% Caster,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus."<br>";
                    }
                    else
                    { //BM
                        $p->itlbonus = round(2.5*sqrt($p->skills->level) + 3 * sqrt($p->itl));
                        if($aa < 0)
                        {
                            $aa = 0; 
                        }
                        $p->manaaddcon = round($aa/10/2);
                        $p->bonus = $aaa."% MBM,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus;
                        if ($comments)$fight_report .=  " dyn: ".$p->char->username." ".$aaa."% Caster,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus."<br>";
                    }
                }
                elseif($a==1)
                {   //fighter
                    if($aaa>=70)
                    { //prim�r
                        $p->itlbonus = round(2*sqrt($p->skills->level) + 4 * sqrt($p->skills->itl));
                        if($aa < 0)
                        {
                            $aa = 0; 
                        }
                        $p->manaaddcon = round($aa/10/2);
                        $p->bonus = $aaa."% F,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus;
                        if ($comments)$fight_report .=  " dyn: ".$p->char->username." ".$aaa."% Fighter,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus."<br>";
                    }
                    else
                    { //BM
                        $p->itlbonus = round(2.5*sqrt($p->skills->level) + 5 * sqrt($p->skills->itl));
                        if($aa < 0)
                        {
                            $aa = 0; 
                        }
                        $p->manaaddcon = round($aa/10/2);
                        $p->bonus = $aaa."% FBM,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus;
                        if ($comments)$fight_report .=  " dyn: ".$p->char->username." ".$aaa."% Fighter,".$p->itlbonus.",".$p->manaaddcon.",".$p->malus."<br>";
                    }
                }
            //Ende for schleife kampfwerte berechnen
            }
            //Hier der Teil mit debug in die Tabelle jedi_kwl
            $jedi_kwl = $this->JediKwl->newEntity();
            $jedi_kwl->date = time();
            $jedi_kwl->name_1 = $p->char->username;
            $jedi_kwl->bonus_1 = $p->bonus;
            $jedi_kwl->fc_1 = $p->fightchance;
            $jedi_kwl->hc_1 = $p->hitchance;
            $jedi_kwl->cc_1 = $p->castchance;
            $jedi_kwl->hm_1 = $p->hitchance_magic;
            $jedi_kwl->cns_1 = $p->skills->cns;
            $jedi_kwl->agi_1 = $p->skills->agi;
            $jedi_kwl->spi_1 = $p->skills->spi;
            $jedi_kwl->int_1 = $p->skills->itl;
            $jedi_kwl->tac_1 = $p->skills->tac;
            $jedi_kwl->dex_1 = $p->skills->dex;
            $jedi_kwl->lsa_1 = $p->skills->lsa;
            $jedi_kwl->lsd_1 = $p->skills->lsd;

            $jedi_kwl->name_2 = $pv->char->username;
            $jedi_kwl->bonus_2 = $pv->bonus;
            $jedi_kwl->fc_2 = $pv->fightchance;
            $jedi_kwl->hc_2 = $pv->hitchance;
            $jedi_kwl->cc_2 = $pv->castchance;
            $jedi_kwl->hm_2 = $pv->hitchance_magic;
            $jedi_kwl->cns_2 = $pv->skills->cns;
            $jedi_kwl->agi_2 = $pv->skills->agi;
            $jedi_kwl->spi_2 = $pv->skills->spi;
            $jedi_kwl->int_2 = $pv->skills->itl;
            $jedi_kwl->tac_2 = $pv->skills->tac;
            $jedi_kwl->dex_2 = $pv->skills->dex;
            $jedi_kwl->lsa_2 = $pv->skills->lsa;
            $jedi_kwl->lsd_2 = $pv->skills->lsd;
            $this->JediKwl->save($jedi_kwl);
            //Schaden
            $stabschaden = round(
                            $p->wpn->mindmg
                            +($p->wpn->maxdmg - $p->wpn->mindmg)
                                *(5*$p->skills->dex*rand(1,100)/100+10*$p->skills->lsa*rand(1,100)/100-20
                                *$pv->skills->lsd*rand(1,100)/100-7*$pv->skills->cns*rand(1,100)/100)
                            /$p->skills->level
                            /10);

            if ( $stabschaden > $p->wpn->maxdmg )
            {
                $stabschaden = $p->wpn->maxdmg ; 
            }
            if ( $stabschaden < $p->wpn->mindmg )
            {
                $stabschaden = $p->wpn->mindmg ;
            }
            if ($p->npc == "y")
            {
                //npc variation
				if($stabschaden==10) { $stabschaden=rand(15,30); }
                if($stabschaden==50) { $stabschaden=rand(33,59); }
                if($stabschaden==75) { $stabschaden=rand(66,84); }
                if($stabschaden==100) { $stabschaden=rand(88,109); }
				
            }
            if ($p->char->health > 0)
            {
                //$fight_report .=  $att." zu ".$def."<br>";
                //  $faamount = $p->skills->lsa + $p->skills->spi;
                    //check ob stati auslaufen

                if($p->playerstate5 == "jumped" && $p->playerstatetime5 > 0)
                {
                    $p->playerstatetime5 = $p->playerstatetime5 -1;
                }
                elseif($p->playerstate5 == "jumped")
                {
                    $p->playerstate5 = "";
                    $p->skills->agi = $p->skills->agi - $p->playerstatewert5;
                    $p->skills->itl = $p->skills->itl - round($p->playerstatewert5/2);
                    //if($comments) 
                    $fight_report .= "<span class=\"bf\">".$p->char->username." lost Force Jump effect on agility and defense. (-".round($p->playerstatewert5/2).")</span><br>";
                }
                    // rundenz�hler
                if($p->playersroutine_ammount > 0)
                { 
                    $check = $p->playersroutine_ammount;
                }
                else
                {
                    $check = 1;
                }
                
                if($p->playersroutine > $check )
                {
                    //$fight_report .= "<span class=\"bl\">DEBUG0: ".$p->char->username." passed routines-check ".$p->playersroutine." times! (max: ".$check.")<br></span>";
                    if ($check+3 > $p->playersroutine) 
                    {
                        $pv->playerstatetime = 1; $p->playerstatetime2 = 1 ;
                        //$fight_report .= "<span class=\"bl\">DEBUG2: ".$p->char->username." passed routines-check ".$p->playersroutine." times! (max: ".$check.") AND SHORTENED<br></span>";
                    }
                    $p->playersroutine = $p->playersroutine + 1;
                    if($pv->playerstate == "invisible" )
                    {    // routine for invisible players
                        $die = rand(0,1);
                        if ($die == 0 || $pv->playerstatetime <= 0)
                        {
                            $pv->playerstate = "";
                            $fight_report .= "<span class=\"gn\">".$pv->char->username." returned from invisibility.</span><br>";
                            $p->playersroutine = 0;
                            $p->playersroutine_ammount = 0;
                        }
                    }
                    if($pv->playerstate == "projection" )
                    {     // routine for projections
                        $die = rand(0,2);
                      
                        if ($die == 0 || $pv->playerstatetime <= 0)
                        {
                            $pv->playerstate = "";
                            $fight_report .= "<span class=\"gn\">All projections of ".$pv->char->username." disappeared.</span><br>";
                            $p->playersroutine = 0;
                            $p->playersroutine_ammount = 0;
                        }
                    }
                    if($p->playerstate2 == "blinded" )
                    {    // routine for blind
                        $die = rand(0,2);
                        if ($die == 0 || $p->playerstatetime2 <= 0)
                        {
                            $p->playerstate2 = "";
                            $fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." eyes regenerated.</span><br>";
                            $p->playersroutine = 0;
                            $p->playersroutine_ammount = 0;
                        }
                    }
                    if($p->playerstate2 == "confused" )
                    {     // routine for confuse
                        $die = rand(0,3);
                        if ($die == 0 || $p->playerstatetime2 <= 0)
                        {
                            $p->playerstate2 = "";
                            $fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." mind freed from confusion.</span><br>";
                            $p->playersroutine = 0;
                            $p->playersroutine_ammount = 0;
                        }
                    }
                }
                elseif($p->playerstate2 == "confused" || $p->playerstate2 == "blinded" || $pv->playerstate == "projection" || $pv->playerstate == "invisible" )
                {
                    $p->playersroutine = $p->playersroutine + 1;
                    //$fight_report .= "<span class=\"bl\">DEBUG1: ".$p->char->username." passed routines-check ".$p->playersroutine." times! (max: ".$check.")<br></span>";
                    //$fight_report .= "<span class=\"bl\">DEBUG: ".$p->char->username." passed routines-check ".$p->playersroutine." times!<br></span>";
                }
                if($p->playersroutine > 5)
                {                
                    $realdate = date("d.m.y H:i:s",time());
                    $sr =	" '',";
                    $sr.= "'".$p->char->userid."',";
                    $sr.= "'".$p->playersroutine."',";
                    $sr.= "'".$fight_data->fightid."',";
                    if($pv->playerstate !="")
                    {
                        #$sr5.= "'".$pv->playerstate.""; 
                    }
                    else
                    {
                        $sr.= "'-";
                    }
                    $sr.= ",";
                    if($p->playerstate2 !="")
                    {
                        #$sr5.= "".$p->playerstate2."',";
                    }
                    else
                    {
                        $sr.= "-',";
                    }
                    $sr.= "'".$realdate."'";

                    #mysql_query("INSERT INTO jedi_kwl_log2 (kwl2id, userid, locked, fight, reason, dtime) VALUES ($sr)");
                }
				elseif($p->playersroutine > 15)
				{
					$p->playersroutine = 0;
				}
                
                //ende
                $fightart ="hit";
                //tactic ber�cksichtigen
                if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,10) >= 5) )
                {
                    $fightart ="hit";
                    if($p->fightchance > $p->castchance && (rand(0,1) == 0))
                    {
                        if($pv->hitchance > $p->hitchance)
                        {
                            if($p->char->mana > 25)
                            {
                                $fightart="cast";
                                if($comments) $fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution through cast!<br></span>";
                            }
                            else
                            { 
                                $fightart="hit";
                                if($comments) 	$fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution through cast but there is no mana!<br></span>";
                            }
                        }
                        else
                        {
                            $fightart="hit";
                            $p->fightchance + 15; 
                            $p->hitchance + 25; 
                            if($comments) $fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution through hit!<br></span>";
                        }
                    }
                    elseif($p->fightchance < $p->castchance && (rand(0,1)==0) )
                    {
                        if($p->char->mana > 25)
                        {
                            $fightart="cast";
                            if($comments) $fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution through cast!<br></span>";
                        }
                        else
                        { 
                            $fightart="hit";
                            $p->fightchance + 15; 
                            $p->hitchance + 25; 
                            if($comments)  	$fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution through cast but there is no mana!<br></span>";
                        }
                    }
                }
				
				// Kampfeinstellungen
				if($p->char->stance > $pv->char->stance) 
				{
					//h�here einstellung H�here Haltung st�rkere aber langsamere Attacken und Abwehr.
					$p->fightchance = $p->fightchance-3;
					$p->hitchance = $p->hitchance+7;
					//${"tac_$p"} = ${"tac_$p"} + round(${"tac_$p"}/20);
					if($p->kbonus == 0) 
					{	
						$p->skills->cns = $p->skills->cns + round($p->skills->cns/11);
						$p->kbonus = 1 ;
					}
					if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,10)>=5) )
					{
						$stabschaden = $stabschaden + round($stabschaden/rand(11,23));
					}
					if ( $stabschaden > $p->wpn->maxdmg ) 
					{
						$stabschaden = $p->wpn->maxdmg ; 
					}
					if ( $stabschaden < $p->wpn->mindmg ) 
					{
						$stabschaden = $p->wpn->mindmg ; 
					}
				}
				elseif($p->char->stance < $pv->char->stance)
				{
					//tiefere einstellun= Tiefere Haltung erlaubt schnellere aber schw�chere Attacken und Abwehr. 
					$p->fightchance = $p->fightchance+7;
					$p->hitchance = $p->hitchance-3;
					//${"tac_$p"} = ${"tac_$p"} + round(${"tac_$p"}/10);
					if($p->kbonus == 0) 
					{	
						$p->skills->agi = $p->skills->agi + round($p->skills->agi/11);
						$p->kbonus = 1;
					}
					
					if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,10)>=5) )
					{
						$stabschaden = $stabschaden + round($stabschaden/rand(17,27));
					}
					if ( $stabschaden > $p->wpn->maxdmg ) 
					{
						$stabschaden = $p->wpn->maxdmg ; 
					}
					if ( $stabschaden < $p->wpn->mindmg ) 
					{
						$stabschaden = $p->wpn->mindmg ; 
					}
				}
				//initiative
				if($p->char->initative  > $pv->char->initative ) 
				{
					//h�here einstellung agressiv
					$p->fightchance = $p->fightchance + 5;
					$p->hitchance = $p->hitchance - 5;
					//${"tac_$p"} = ${"tac_$p"} - round(${"tac_$p"}/10);	
				}
				elseif($p->char->initative  < $pv->char->initative )
				{
					//tiefere einstellung= ruhig. 
					$p->fightchance = $p->fightchance - 5;
					$p->hitchance = $p->hitchance + 5;
					//${"tac_$p"} = ${"tac_$p"} + round(${"tac_$p"}/10);	
				}
	
                $p->playerat++;
                
                $fa = rand(1,100);
                
                if ($p->playerstate == "rage")
                {
                    $p->fightchance + 10; $p->hitchance + 5;
                }

                if ($fa <= $p->fightchance || ($fightart == "hit" && $p->fightchance > 25))
                {
                    $p->playerfc++;
                    if($p->lsdstate > 1)
                    {
                        $p->lsdstate = $p->lsdstate - 1;
                    }
                    elseif($p->lsdstate == 1)
                    {
                        $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); unset($p->lsdstate);
                    }

                    if ($pv->char->health > 0)
                    {
                        $dohit2 = null;
                        $dohit = rand(1, 100);
                        if($dohit < $p->hitchance)$p->playerhc++;
                     
                        if ($p->playerstate2 == "blinded" && ($dohit < ($p->hitchance/2)))
                        {   // routine for blinded players
                            $die = rand(0,8);
                            if ($die > 0 || $p->playersroutine < 3)
                            {
                                $p->playerstatetime2 = $p->playerstatetime2 - 1;
                                $dohit = 101;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." runs around silly because ".$p->heshe." is blinded.</span><br>";
                            }
                            else
                            {
                                $p->playerstate2 = "";
                                $p->playersroutine = 0;
                                $fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." eyes regenerated.</span><br>";
                            }
                        }
                        elseif ($p->playerstate2 == "confused"  && ($dohit < ($p->hitchance/2)))
                        {   // routine for confused players
                            $die = rand(0,9);
                            if ($die > 0 || $p->playersroutine < 3)
                            {
                                $p->playerstatetime2 = $p->playerstatetime2 - 1;
                                $dohit = 101;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." runs around totally confused.</span><br>";
                            }
                            else
                            {
                                $p->playerstate2 = "";
                                $p->playersroutine = 0;
                                $fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." mind freed from confusion.</span><br>";
                            }
                        }
                        elseif ($p->playerstate2 !="") 
                        {
                            $dohit2 = 101;
                        }

                        if ($pv->playerstate == "invisible"  && ($dohit < ($p->hitchance/2)))
                        {    // routine for invisible players
                            $die = rand(0,6);
                            if ($die > 0 || $p->playersroutine < 3)
                            {
                                $pv->playerstatetime = $pv->playerstatetime - 1;
                                $dohit = 101;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." tried to hit ".$pv->char->username.", but ".$p->heshe." can not see ".$pv->herhim.".</span><br>";
                            }
                            else
                            {
                                $pv->playerstate = "";
                                $p->playersroutine = 0;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." found ".$pv->char->username.".</span><br>";
                            }
                        }
                        elseif ($pv->playerstate == "projection"  && ($dohit < ($p->hitchance/2)))
                        {     // routine for projections
                            $die = rand(0,7);
                            if ($die > 0 || $p->playersroutine < 3)
                            {
                                $pv->playerstatetime = $pv->playerstatetime - 1;
                                $dohit = 101;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." tried to hit ".$pv->char->username.", but ".$p->heshe." has just hit a projection of ".$pv->herhim.".</span><br>";
                            }
                            else
                            {
                                $pv->playerstate = "";
                                $p->playersroutine = 0;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." found the real ".$pv->char->username.".</span><br>";
                            }
                        }
                        elseif ($pv->playerstate =="rage")
                        {
                            $dohit2 = 101; 
                        }

                        if ( $dohit < $p->hitchance && !$pv->protectstate )
                        {
                            //zusatzschaden durch tac und dex und waffe
                            //erstmal ne runde w�rfeln,..
                            //$tac=$p->skills->tac*100/$p->skills->level;$tac >= 10  && ($p->prefereddef != 99 || $p->preferedoff != 99)
                            if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(1,10)>=3))
                            {
                                // Boni kassieren
                                //$p->fightchance + 50; 
                                //$p->hitchance + 30; 
                                // waffe einbeziehen 
                                if( $p->wpn->reqskill*1.1 <= $p->skills->dex && (rand(1,10)<=2)) 
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->maxdmg - $p->wpn->mindmg)/2.2);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks critical from left side. (+".$bonus.")</span><br>";
                                }
                                elseif( $p->wpn->reqskill*1.2 <= $p->skills->dex & (rand(1,10)<=1))
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->maxdmg - $p->wpn->mindmg)/1.5);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks critical from right side. (+".$bonus.")</span><br>";
                                }
                                elseif(rand(1,10)<=3)
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->reqskill/22)+$spanne/5);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks from behind. (+".$bonus.")</span><br>";
                                }

                                if ( $stabschaden > $p->wpn->maxdmg ) { $stabschaden = $p->wpn->maxdmg ; }
                                if ( $stabschaden < $p->wpn->mindmg ) { $stabschaden = $p->wpn->mindmg ; }
                                // if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0)){}
                            }
                            //ende zusatzschaden
                            $dodmg = $stabschaden;
                            //$dodmg = rand(${"pf_$p"}[mindmg] ,${"pf_$p"}[maxdmg] );
                
                            //snowball
                            /*      if (${"snow_$p"} == "yes") {
                            $fight_report .= $p->char->username." successfully hit ".$pv->char->username." with a <font color=blue>snowball</span> and causes ".$dodmg." points of Cold Damage.<br>"; $pv->char->health = $pv->char->health - $dodmg;
                            */ //   }
                            //  else {
                            if($p->playerstate2 == "blinded")
                            {
                                $how = "hit";
                                $how2 = " by chance";
                                $dodmg = round($dodmg/1.7);
                            }
                            elseif ($p->playerstate2 == "confused")
                            {
                                $how = "hit";
                                $how2 = " by chance";
                                $dodmg = round($dodmg/1.9);
                            }
                            elseif ($pv->playerstate == "invisible")
                            {
                                $how = "hit";
                                $how2 = " by chance";
                                $dodmg = round($dodmg/1.5);
                            }
                            elseif ($pv->playerstate == "projection")
                            {
                                $how = "hit";
                                $how2 = " by chance";
                                $dodmg = round($dodmg/1.5);
                            }
                            elseif ($p->playerstate == "rage")
                            {
                                $how = "hit critically";
                                $how2 = " in rage";
                                $add = round($dodmg*0.17);
                                $docastdmg = 1.5*rand(0.5*($p->skills->frage/2*1.3+6.9)/(1+1.5e-2*$p->skills->frage),($p->skills->frage/2*2.3+9.9)/(1+1.5e-2*$p->skills->frage/1.5));
                                if($add < (round($docastdmg/9)) ){ $add=round($docastdmg/9); }
                                
                                $dodmg = $dodmg + $add;
                                if ( $dodmg > $p->wpn->maxdmg ) { $dodmg = $p->wpn->maxdmg ; }
                                if ( $dodmg < $p->wpn->mindmg ) { $dodmg = $p->wpn->mindmg ; }

                                if ($p->playerstate3 > "2")
                                {
                                    $p->playerstate = "" ;  $p->playerstate3 = "" ; $how = "successfully hit"; $how2 = "";
                                }
                                else
                                {
                                    $p->playerstate3 = $p->playerstate3 + "1";
                                }
                                //$fight_report .= $p->playerstate3." ";
                            }
                            else
                            {
                                $how = "successfully hit";
                                $how2 = "";   
                            }
                        
                            if ($p->playerstate2 == "confused")
                            {
                                $teamside = rand(0,1);
                                $tmpteam = ${"team_$teamside"};
                                $tmp2 = array_rand($tmpteam);
                                $tmp1 = $tmpteam[$tmp2];
                                unset($tmpteam[$tmp2]);
                                    //eigener treffer
                                    if($tmp1 == $p)
                                    {
                                        $fight_report .= "<span class=\"bl\">".$p->char->username." hurls by ".$p->herhim."self and causes ".$dodmg." points of damage.</span><br>";
                                        $p->char->health = $p->char->health - $dodmg;
                                    }
                                    elseif(!$tmp1->protectstate)
                                    {
                                        $fight_report .= "<span class=\"bl\">".$p->char->username." $how ".$tmp1->char->username." with confused mind and causes ".$dodmg." points of damage.</span><br>";
                                        $tmp1->char->health = $tmp1->char->health - $dodmg;
                                    }
                                    else
                                    {
                                        $fight_report .= "<span class=\"bl\">".$p->char->username." screams loud out because of terrible headache.  (-".$p->playerstatetime2." HP)</span><br>";
                                        $p->char->health = $p->char->health - $p->playerstatetime2;
                                    }
                            }
                            else
                            {
                                //standard-treffer
                                $fight_report .= "<span class=\"bl\">".$p->char->username." $how ".$pv->char->username."$how2 and causes ".$dodmg." points of damage.";
                                if($p->playerstate == "rage" && $add) { $fight_report .= " (+".$add.")"; unset($add);}
                                $fight_report .= "</span><br>";
                                $pv->char->health = $pv->char->health - $dodmg;
                            }
                
                        }
                        elseif (($pv->protectstate) && ($dohit < $p->hitchance))
                        {
                            //zusatzschaden durch tac und dex und waffe
                            //erstmal ne runde w�rfeln, ob ne zufalls force oder eine bestimmte dran ist...
                            //$tac=$p->skills->tac*100/$p->skills->level;$tac >= 10  && ($p->prefereddef != 99 || $p->preferedoff != 99)
                            if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(1,10)>=3))
                            {
                                // Boni kassieren
                                //$p->fightchance + 50; 
                                //$p->hitchance + 30; 
                                // waffe einbeziehen 
                    
                                if( $p->wpn->reqskill*1.1 <= $p->skills->dex && (rand(1,10)<=2))
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->maxdmg - $p->wpn->mindmg)/2.2);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks critical from left side. (+".$bonus.")</span><br>";
                                }
                                elseif( $p->wpn->reqskill*1.2 <= $p->skills->dex & (rand(1,10)<=1))
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->maxdmg - $p->wpn->mindmg)/1.5);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks critical from right side. (+".$bonus.")</span><br>";
                                }
                                elseif(rand(1,10)<=3)
                                {
                                    //stabschadens spanne ermitteln
                                    $spanne=  $p->wpn->maxdmg - $p->wpn->mindmg;
                                    $bonus =  round(($p->wpn->reqskill/22)+$spanne/5);
                                    $stabschaden = $stabschaden + $bonus;
                                    $fight_report .= "<span class=\"bl\">".$p->char->username." attacks from behind. (+".$bonus.")</span><br>";
                                }
                                if ( $stabschaden > $p->wpn->maxdmg ) { $stabschaden = $p->wpn->maxdmg ; }
                                if ( $stabschaden < $p->wpn->mindmg ) { $stabschaden = $p->wpn->mindmg ; }
                                // if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0)){}
                            }
                            //ende zusatzschaden
                            if ($p->playerstate == "rage")
                            {
                                $docastdmg = 1.5*rand(0.5*($p->skills->frage/2*1.3+6.9)/(1+1.5e-2*$p->skills->frage),($p->skills->frage/2*2.3+9.9)/(1+1.5e-2*$p->skills->frage/1.5));
                                $add = round($dodmg*0.17);
                                if($add < (round($docastdmg/9)) ){ $add=round($docastdmg/9); }
                                $dodmg = $stabschaden;
                                $dodmg = $dodmg + $add;
                                $how2 = " in rage";
                                if ( $dodmg > $p->wpn->maxdmg ) { $dodmg = $p->wpn->maxdmg ; }
                                if ($p->playerstate3 > "2")
                                {
                                    $p->playerstate = "" ;  $p->playerstate3 = "" ;
                                }
                                else
                                {
                                    $p->playerstate3 = $p->playerstate3+1;
                                }
                                //$fight_report .= $p->playerstate3." ";
                            }
                            else
                            {
                                $dodmg = $stabschaden;
                                $how2 = "";
                            }
                            //neue version mit restschaden
                            if (($pv->protectstatetime - $dodmg)< 1)
                            {
                                $fight_report .= "<span class=\"gn\">".$p->char->username." hit ".$pv->char->username.$how2." but the protection shield neutralized some damage of ".$dodmg.".";
                                
                                if($p->playerstate == "rage" && $add)
                                {
                                    $fight_report .= " (+".$add.")"; unset($add);
                                }
                                $fight_report .= "</span><br>";
                                $fight_report .= "<span class=\"gn\">".$pv->char->username.add_s($pv->char->username)." protection has worn off.</span><br>";
                                unset($pv->protectstate);
                                $restdmg=($pv->protectstatetime - $dodmg)* -1;
                                if($restdmg>0)
                                {
                                    $fight_report .= "<span class=\"bl\">But the hit was so powerful, that ".$pv->heshe." takes ".$restdmg." points of damage.</span><br>"; $pv->char->health = $pv->char->health - $restdmg;
                                } 
                            }
                            else
                            {
                                $pv->protectstatetime = $pv->protectstatetime - $dodmg;
                                $fight_report .= "<span class=\"gn\">".$p->char->username." hit ".$pv->char->username." but the protection shield neutralized the damage of ".$dodmg."  (".$pv->protectstatetime." left).";
                                if($p->playerstate == "rage" && $add) { $fight_report .= " (+".$add.")"; unset($add);}
                                $fight_report .= "</span><br>";
                            }
                
                        }
                        elseif ($dohit2 != 101)
                        {
                            $achance = rand(1,100);
                            if (($p->hitchance <= 35) && ($achance > $p->hitchance))
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." tried to hit ".$pv->char->username.", but failed.</span><br>";
                                $pv->evade = $pv->evade + 1;
                                if($p->lsdstate == 1)
                                {
                                    $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); unset($p->lsdstate);
                                }
                            }
                            elseif (($p->hitchance <= 50) && ($achance > $p->hitchance))
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." tried to hit ".$pv->char->username.", but ".$pv->char->username." blocked the attack.</span><br>";
                                $pv->evade = $pv->evade + 1;
                                if($p->lsdstate == 1)
                                {
                                    $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); unset($p->lsdstate);
                                }
                            }
                            elseif (($p->hitchance <= 70) && ($achance < $p->hitchance))
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." tried to hit ".$pv->char->username.", but ".$pv->char->username." evaded.</span><br>";
                                $pv->evade = $pv->evade + 1;
                                if($p->lsdstate == 1)
                                {
                                    $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); unset($p->lsdstate);
                                }
                            }
                            elseif (($p->hitchance >= 85) && ($achance < $p->hitchance))
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." walks around ".$pv->char->username.".</span><br>";
                                $pv->evade = $pv->evade + 1;
                                if($p->lsdstate == 1)
                                {
                                    $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); unset($p->lsdstate);
                                }
                            }
                            elseif($achance > $p->hitchance)
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." looks for good chance to hit ".$pv->char->username.".</span><br>";
                                $pv->evade = $pv->evade + 1;
                                if($p->lsdstate == 1)
                                {
                                    $p->skills->lsd = $p->skills->lsd-round($p->skills->lsa/4); $p->lsdstate = 0;
                                }
                            }
                            elseif($achance < $p->skills->lsd)
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." takes a defensive position. ( +".round(rand($p->skills->lsa/3,$p->skills->lsa/3.5)).")</span><br>";
                                $p->skills->lsd = $p->skills->lsd+round($p->skills->lsa/3); $p->lsdstate = 2;
                            }
                            else
                            {
                                $fight_report .= "<span class=\"g\">".$p->char->username." waits a moment.</span><br>";
                            }//".round($p->skills->lsd/3).",
                        }
                    }
                }
            }
            //mächte
   if ($pv->char->health > 0) {
    $ca = rand(1,100);
    
    if ($ca <= $p->castchance  || ($fightart == "cast"  && $p->castchance > 1)) 
    {

        if (!empty($p->forcesdie))
        {
            
            //erstmal ne runde w�rfeln, ob ne zufalls force oder eine bestimmte dran ist...
            //$tac=$p->skills->tac*100/$p->skills->level;$tac >= 10  && ($p->prefereddef != 99 || $p->preferedoff != 99)
            if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,10)<=6))
            {
                // Boni kassieren
                //$p->castchance + 30; 
                $p->hitchance_magic + 35; 
                //clever sein und mana checken
                if($p->char->mana > 20) 
                {
                    // roll the die and decide my force to use
                    if(rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0))
                    {
                        if($p->skills->side > 0 && rand(0,4) == 0 && $p->char->prefereddef != 99)
                        {
                            //if($comments) 
                            $fight_report .= "<span class=\"bl\">".$p->char->username." was strong influenced by the light side and took ".$p->herhis." preferred Force.</span><br>";
                            $atdef = 0;
                        }
                        elseif($p->skills->side < 0 && rand(0,4) == 0 && $p->char->preferedoff != 99)
                        {
                            //if($comments) 
                            $fight_report .= "<span class=\"bl\">".$p->char->username." was strong influenced by the dark side and took ".$p->herhis." preferred Force.</span><br>";
                            $atdef = 1;
                        }
                        elseif($p->char->prefereddef != 99 || $p->char->preferedoff != 99)
                        {
                            // if($comments)   
                            $fight_report .= "<span class=\"bl\">".$p->char->username." used one of ".$p->herhis." preferred Forces.</span><br>";
                            if($p->char->prefereddef == 99)
                            {
                                $atdef=1;
                            }
                            elseif($p->char->preferedoff == 99)
                            {
                                $atdef = 0;
                            }
                            else
                            {
                                $atdef = rand(0,1);
                            }
                        }
                        else
                        {
                            //Machtwahl ausgeschalten, Nahkampfbonus!".round($p->skills->lsd/3).",
                            $fight_report .= "<span class=\"g\">".$p->char->username." preferred to use no Force and takes a defensive position. ( +".round(rand($p->skills->lsa/3,$p->skills->lsa/3.5)).")</span><br>"; 
                            $p->skills->lsd = $p->skills->lsd+round($p->skills->lsa/3); 
                            $p->lsdstate = 2;
                        }
                    }
                    else
                    {
		                $atdef = 2;
                    }
                    
                    if($atdef == 0 && $p->char->prefereddef != 99){
                        //if($comments) 	$fight_report .= $p->char->username." trifft taktische Auswahl: Def-Force ".$p->prefereddef."<br>";
                        $doforce = array_search($p->char->prefereddef,$p->forcesdie);
                        $forcernd = $p->forcesdie[$doforce];
                    }elseif($atdef == 1 && $p->char->preferedoff != 99){
                        //if($comments) $fight_report .= $p->char->username." trifft taktische Auswahl: Of-Force ".$p->preferedoff."<br>";
                        $doforce = array_search($p->char->preferedoff,$p->forcesdie);
                        $forcernd = $p->forcesdie[$doforce];

                    }elseif($atdef == 2){
                        if($comments) 	$fight_report .= $p->char->username." tried to act logical:<br>";
                        $ausgabe = rand(1,3);
                        if($ausgabe == 1) { $fight_report .= "<span class=\"bl\">".$p->char->username." checked all options.</span><br>"; 
                        }elseif($ausgabe == 2) { $fight_report .= "<span class=\"bl\">".$p->char->username." looks around to take more advantage.</span><br>";
                        }elseif($ausgabe == 3) {$fight_report .= "<span class=\"bl\">".$p->char->username." waits a moment and got an idea.</span><br>";
                        }
                        
                        //$fight_report .= "<span class=\"bl\">".$p->char->username." tried to act with caution!<br></span>";
                        //Standard mit TAC - eigenen Status checken:
                            //revitalize
                            if(isset($deadtm_p) && array_search('14',$p->forcesdie))  {
                                $doforce = array_search('14',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Revitalize.<br></font>";
                            //energize
                            }elseif($p->char->mana < 100 && array_search('23',$p->forcesdie) ) {
                                $doforce = array_search('23',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Team Energize.<br></font>";
                            //teamheal
                            }elseif($p->char->health*100/$p->maxhealth< 75 && array_search('11',$p->forcesdie) && count($team_p) > 1) {
                                $doforce = array_search("11",$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Heal.<br></font>";
                            //nur heal
                            }elseif($p->char->health*100/$p->maxhealth< 75 && array_search('10',$p->forcesdie)) {
                                $doforce = array_search("10",$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) $fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Team Heal.<br></font>";
                            //drain
                            }elseif($p->char->health*100/$p->maxhealth< 50 && $pv->char->health*100/$pv->maxhealth > 50  && array_search('18',$p->forcesdie)) {
                                $doforce = array_search('18',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) $fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Drain.<br></font>";
                            //absorb
                            }elseif($p->absorbstate == "" && array_search('13',$p->forcesdie)) {
                                $doforce = array_search('13',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Absorb.<br></font>";
                            //protect
                            }elseif($p->protectstate == "" && array_search('12',$p->forcesdie)) {
                                $doforce = array_search('12',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Protect.<br></font>";
                            //projection 
                            }elseif($p->playerstate == "" && array_search('7',$p->forcesdie)) {
                                $doforce = array_search('7',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) $fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Projection.<br></font>";
                            //persuasion	
                            }elseif($p->playerstate == "" && array_search('6',$p->forcesdie)) {
                                $doforce = array_search('6',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Persuasion.<br></font>";
                            //Fseei
                            }elseif(($p->playerstate3 == "" || $p->playerstate2 != "") && array_search('4',$p->forcesdie) ) {
                                $doforce = array_search('4',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) $fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Seeing.<br></font>";
                            //confuse
                            }elseif($pv->playerstate2 == "" && array_search('9',$p->forcesdie)) {
                                $doforce = array_search('9',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Confuse.<br></font>";
                            //blind
                            }elseif($pv->playerstate2 == "" && array_search('8',$p->forcesdie)) {
                                $doforce = array_search('8',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Blind.<br></font>";
                            //jump
                            }elseif($p->playerstate5 == "" && array_search('1',$p->forcesdie)) {
                                $doforce = array_search('1',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Jump.<br></font>";
                            //speed
                            }elseif($p->playerstate4 == "" && array_search('0',$p->forcesdie) ) {
                                $doforce = array_search('0',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Speed.<br></font>";
                            //deadly sight
                            }elseif( count($team_pv) > 1 && array_search('22',$p->forcesdie) ) {
                                $doforce = array_search('22',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Deadly Sight.<br></font>";
                            //chain
                            }elseif( count($team_pv) > 1 && array_search('20',$p->forcesdie) ) {
                                $doforce = array_search('20',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Cain Lightning.<br></font>";
                            //rage
                            }
                            elseif($p->playerstate == "" && $p->char->health*100/$p->maxhealth > 25 && array_search('16',$p->forcesdie) )
                            {
                                $doforce = array_search('16',$p->forcesdie);
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments) 	$fight_report .= "<font color=\"goldenrod\">".$p->char->username." decided to use Force Rage.<br></font>";
                            
                            //zufall
                            }
                            else
                            {
                                $doforce = array_rand($p->forcesdie,1); 
                                $forcernd = $p->forcesdie[$doforce];
                                
                                if($comments)  
                                $fight_report .= "<font color=\"goldenrod\">".$p->char->username." has no idea.<br></font>";
                            }
                        }
                }
                else
                {
                    //wenn wir auch kein mana haben, wir gehen in abwehrstellung und sind ein st�ck mehr auf der hut
                    $p->skills->lsd = $p->skills->lsd + ceil($p->skills->lsa/4.9) ;
				  if($p->skills->lsd > $p->skills->level * 1.5 ) { $p->skills->lsd = ceil(($p->skills->level * 1.5)); }
				  if($p->skills->lsa > $p->skills->level * 1.5 ) { $p->skills->lsa = ceil(($p->skills->level * 1.5)); }
                    
                    $forcernd = 99;
                }
            }
            else
            {
                #$fight_report .= "<font color=\"goldenrod\">".$p->char->username." berl&auml;sst es dem zufall -.-<br></font>";
                // roll the die and decide my force to use - Standard ohne Tac
                $doforce = array_rand($p->forcesdie,1); 
                $forcernd = $p->forcesdie[$doforce];
            } 
            $docastdmg2nd = "";
	 // Force Speed

      if ( $p->skills->fspee > 0 && $forcernd == 0 )
      {
        $faktor = 1.0;
		$last = $p->playerslast_c;
        if($p->playerslast_c == 0)
        {
            $p->playerslast_c = 0; $p->playerslast_cc++; 
		    $faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fspee/9)),1);
		
            if($faktor < 0.1 )
            {
                $faktor = 0.1; 
            } 
        }
        else { $p->playerslast_c = 0;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		
		if($comments) $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(($faktor*1 + ($p->skills->fspee / 10 / $faktor)),($faktor*5 + ($p->skills->fspee / 7 / $faktor)));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 4)+$p->manaaddcon;
		
        if ($manacon <= $p->char->mana) {

          if ($docast <= $p->hitchance_magic && rand(0,8) >= 1) {
            $p->playerstate4 = $p->playerstate4+1;
			$p->char->mana = $p->char->mana - $manacon;
			
			$fight_report .= "<span class=\"b\">".$p->char->username." cast Force Speed, feeling more agile and faster against the opponents. (".$docastdmg.")<br></span>"; //".$pv->char->username.".<br>";
          
			//$p->playerstate4 = "faster";
			//${"playerstatetime4_$p"} = $docastdmg;
			//$p->skills->dex = $p->skills->dex + $docastdmg;
            $p->skills->agi = $p->skills->agi + $docastdmg;
            //$p->skills->lsa = $p->skills->lsa + ($docastdmg / 2);
          }
          else { $fight_report .= "<span class=\"bf\">".$p->char->username." tried to cast Force Speed but got interrupted.  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; }
        }
        else {
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Speed. (".$manacon." needed)<br></span>";
        }
      }
	  	 // Force jump

      if ( $p->skills->fjump > 0 && $forcernd == 1 ) {
	  $faktor=1.0;
	  	$last = $p->playerslast_c;
	  	if($p->playerslast_c == 1){ $p->playerslast_c = 1; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fjump/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 1;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		
		if($comments) $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(($faktor*1 + ($p->skills->fjump / 6 / $faktor)),($faktor*10 + ($p->skills->fjump / 6 / $faktor)));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
			
           	//fightstats
			$p->pcastsdeff = $p->pcastsdeff+1;
			$p->manacondef = $p->manacondef + $manacon ;
			//$p->manaconoff= $p->manaconoff + $manacon ;
			$p->defdamage = $p->defdamage + $docastdmg ;
			//$p->offdamage = $p->offdamage + $docastdmg ;
            //$p->playerstate5 = $p->playerstate5+1;
			
			if ( $docast <= $p->hitchance_magic && rand(0,7) >= 1) {
				if(($p->playerstate5 == "jumped") && rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0)){
					$docastdmg = round($faktor * (($p->skills->fjump / 6) + $p->skills->level/15));
					$p->skills->tac = $p->skills->tac + $docastdmg;
					$p->skills->lsd = $p->skills->lsd + round($docastdmg/2);
				$fight_report .= "<span class=\"b\">".$p->char->username." acts by ".$p->herhis." experience, jumps backwards. (".$docastdmg.")</span><br>"; 
				}else{	
			$p->char->mana = $p->char->mana - $manacon;
			//$malus=rand($docastdmg-1, $docastdmg+(10 + $p->skills->frage / 10));
			//$p->char->health = $p->char->health - $malus; //der kleine malus neben den 3 Boni
			if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
			$fight_report .= "<span class=\"b\">".$p->char->username." cast Force Jump, jumping around ".$p->herhis." opponents. (".$docastdmg.")</span><br>"; // (".$docastdmg.") ".$pv->char->username.".<br>";
			}else{
			$fight_report .= "<span class=\"b\">".$p->char->username." cast Force Jump, jumping around ".$p->herhis." opponent. (".$docastdmg.")</span><br>"; // (".$docastdmg.") ".$pv->char->username.".<br>";
           	}           
		   	//check ob jump gesetzt ist
			if($p->playerstate5 == "jumped" && $p->playerstatetime5 > 0){
			//l�schen
			$p->skills->agi = $p->skills->agi - $p->playerstatewert5;
			//$p->skills->lsd = $p->skills->lsd + $docastdmg;,
			$p->skills->itl = $p->skills->itl - $p->playerstatewert5/2;
    		//$p->skills->lsa = $p->skills->lsa + ($docastdmg / 2);
			if($comments) $fight_report .= "<span class=\"b\">".$p->char->username." lost the old Force Jump effect on agility and defense. (-".$p->playerstatewert5.")</span><br>";// (+".$p->playerstate2*$docastdmg*2.5." skills)<br>";
	
			}
			//hilfausgabe		
			if($comments) $fight_report .= "<span class=\"b\">".$p->char->username." adds ".$docastdmg." points to his agility and defense.</span><br>";// (+".$p->playerstate2*$docastdmg*2.5." skills)<br>";

			$p->playerstate5 = "jumped";
			$p->playerstatetime5 = $docastdmg/2 + $p->skills->level/15;
			$p->playerstatewert5 = $docastdmg;
			$p->playerstatej5 = "jumped x ".$p->playerstatewert5;
			//$p->skills->dex = $p->skills->dex + $docastdmg;
            $p->skills->agi = $p->skills->agi + $docastdmg;
			//$p->skills->lsd = $p->skills->lsd + $docastdmg;,
			$p->skills->itl = $p->skills->itl + $docastdmg*2;
            //$p->skills->lsa = $p->skills->lsa + ($docastdmg / 2);
			}
          } else { $fight_report .= "<span class=\"bf\">".$p->char->username." tried to cast Force Jump but got interrupted.  (-".$manacon.")</span><br>"; $p->char->mana = $p->char->mana - $manacon; }
        }
        else {
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Jump. (".$manacon." needed)</span><br>";
        }
      }
	  // Force Pull

      elseif ( $p->skills->fpull > 0 && $forcernd == 2 ) {
        //$docastdmg = rand(($p->skills->fpush*0.4),($p->skills->fpush*0.85));
        $faktor = 1.1;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 2){ $p->playerslast_c = 2; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fpull/9)),1); 
		if($faktor < 0.1) {$faktor = 0.1;  }
		}else { $p->playerslast_c = 2;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]+1;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fpull/2*2.3+13.9)/(1+1.5e-2*$p->skills->fpull),($p->skills->fpull/2*2.3+6.9)/(1+1.5e-2*$p->skills->fpull/2));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
        if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 5)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {
		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Pull');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Pull');

			}elseif ($pv->absorbstate) {
            $fight_report .= "<span class=\"bf\">".$p->char->username." tried to pull ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br></span>";
            $p->char->mana = $p->char->mana - $manacon;
            $pv->char->mana = $pv->char->mana + $manacon/1.5;
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            if ($pv->absorbstatetime <= 0) {
              unset($pv->absorbstate);
              $fight_report .= "<span class=\"b\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br></span>";
            }
          }
          else {
            if ( $docast <= $p->hitchance_magic  && rand(0,6) >= 1 ) {
				$fight_report .= "<span class=\"b\">".$p->char->username." cast Force Pull and slings ".$pv->char->username." to the floor inflicting ".$docastdmg." points of damage.<br></span>";
				$pv->char->health = $pv->char->health - $docastdmg;
				$p->char->mana = $p->char->mana - $manacon;
				//$p->skills->lsa = round($p->skills->lsa + $p->skills->level/15);
				//$pv->skills->lsa = round($pv->skills->lsa + $pv->skills->level/30);
				$pv->skills->lsa = $pv->skills->lsa + round(0.4*sqrt($pv->skills->level) + 0.1 * sqrt($pv->skills->lsa));
				$p->skills->lsa = $p->skills->lsa + round(0.4*sqrt($p->skills->level) + 0.7 * sqrt($p->skills->lsa));
				//$fight_report .= $p->skills->lsa."vs.".$pv->skills->lsa."<br>";
			}else{ 
				$fight_report .= "<span class=\"bf\">".$p->char->username." cast Force Pull, but ".$pv->char->username." defended the attack. (-".$manacon.")<br></span>"; 
				$p->char->mana = $p->char->mana - $manacon; 
				$pv->skills->lsa = $pv->skills->lsa + round(0.4*sqrt($pv->skills->level));
				$p->skills->lsa = $p->skills->lsa + round(0.4*sqrt($p->skills->level) + 0.4 * sqrt($p->skills->lsa));
				}
				
          }
        }
        else {
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Pull. (".$manacon." needed)<br></span>";
        }
      }

      // Force Push

      elseif ( $p->skills->fpush > 0 && $forcernd == 3 ) {
        //$docastdmg = rand(($p->skills->fpush*0.4),($p->skills->fpush*0.85));
        $faktor = 1.1;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 3){ $p->playerslast_c = 3; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fpush/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 3;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fpush/2*2.3+9.9)/(1+1.5e-2*$p->skills->fpush),($p->skills->fpush/2*2.3+6.9)/(1+1.5e-2*$p->skills->fpush/2));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 5)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {
		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 6);
				if($die==0 && $p->playersroutine >= 3) { $pv->playerstate = ""; $p->playersroutine = 0;}
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Push');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,8);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Push');

			}elseif ($pv->absorbstate) {
            $fight_report .= "<span class=\"bf\">".$p->char->username." tried to push ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br></span>";
            $p->char->mana = $p->char->mana - $manacon;
            $pv->char->mana = $pv->char->mana + round($manacon/1.5);
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            if ($pv->absorbstatetime <= 0) {
              unset($pv->absorbstate);
              $fight_report .= "<span class=\"b\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br></span>";
            }
          }else{
            if ( $docast <= $p->hitchance_magic  && rand(0,5) >= 1) {
			$fight_report .= "<span class=\"b\">".$p->char->username." cast Force Push and slings ".$pv->char->username." to the floor inflicting ".$docastdmg." points of damage.<br></span>";
			$pv->char->health = $pv->char->health - $docastdmg;
			$p->char->mana = $p->char->mana - $manacon;
			//$p->skills->lsd = round($p->skills->lsd + $p->skills->level/30);
			//$pv->skills->lsa = round($pv->skills->lsa - $pv->skills->level/30);
			$pv->skills->lsa = $pv->skills->lsa - round(0.4*sqrt($pv->skills->level) + 0.2 * sqrt($pv->skills->lsa));
			if($pv->skills->lsa<= 0){ $pv->skills->lsa = 1 ;}
			$p->skills->lsd = $p->skills->lsd + round(0.4*sqrt($p->skills->level) + 0.6 * sqrt($p->skills->lsd));
			//$fight_report .= $p->skills->lsd."vs.".$pv->skills->lsa."<br>";
			//$fight_report .= $p->skills->lsd."vs.".$pv->skills->lsa;
			}else{ 
			$fight_report .= "<span class=\"bf\">".$p->char->username." cast Force Push, but ".$pv->char->username." defended the attack.  (-".$manacon.")<br></span>"; 
			$p->char->mana = $p->char->mana - $manacon; 
			$pv->skills->lsa = $pv->skills->lsa - round(0.4*sqrt($pv->skills->level) + 0.1 * sqrt($pv->skills->lsa));
			if($pv->skills->lsa<= 0){ $pv->skills->lsa = 1 ;}
			$p->skills->lsd = $p->skills->lsd + round(0.4*sqrt($p->skills->level) + 0.3 * sqrt($p->skills->lsd));
			}
          }
        }else{
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Push. (".$manacon." needed)<br></span>";
        }
      }

	   // Force Seeing

      elseif ( $p->skills->fseei > 0 && $forcernd == 4 ) {
	    $faktor = 1.2;
        $last = $p->playerslast_c;
		if($p->playerslast_c == 4){ $p->playerslast_c = 4; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fseei/9)),1);
		if($faktor < 0.1) {$faktor = 0.1 ;}
		}else { $p->playerslast_c = 4;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fseei/2*2.3+6.9)/(1+1.5e-2*$p->skills->fseei/2),($p->skills->fseei/2*2.3+6.9)/(1+1.5e-2*$p->skills->fseei/3));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 3)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
			if(($p->playerstate2=="blinded" || $p->playerstate2=="confused") && rand(0, 8) > 1){
				$p->playerstate2 = "";

				$fight_report .= "<span class=\"b\">".$p->char->username." neutralized all negative conditions of ".$p->herhim."self. (-".$manacon.")<br></span>";

			}elseif ( $docast <= 0.7*$p->hitchance_magic && rand(0, 8) > 1 ) {
		  		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$pv->playerstate = "";
				$fight_report .= "<span class=\"b\">".$p->char->username." uncovered ".$pv->char->username.add_s($pv->char->username)." condition. (-".$manacon.")<br></span>";
				}else{
				$p->playerstate3 = "prevent";
				$fight_report .= "<span class=\"b\">".$p->char->username." tried to uncover ".$pv->char->username.add_s($pv->char->username)." condition, but there was nothing wrong. (-".$manacon.")<br></span>";
				}
		  		$p->char->mana = $p->char->mana - $manacon;
			}elseif ( $docast <= 0.4*$p->hitchance_magic && rand(0, 8) > 1 ) {

				if($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$p->playerstate2 = "";
				$fight_report .= "<span class=\"b\">".$p->char->username." neutralized all negative conditions of ".$p->herhim."self. (-".$manacon.")<br></span>";
			 	}else{
				$p->playerstate3 = "prevent";
				$fight_report .= "<span class=\"b\">".$p->char->username." tried to neutralize negative conditions of ".$p->herhim."self, but there was nothing wrong. (-".$manacon.")<br></span>";
				}
				$p->char->mana = $p->char->mana - $manacon;
			}else{
			$fight_report .= "<span class=\"bf\">".$p->char->username." tried to seeing in force, but ".$pv->char->username." interrupted ".$p->herhis." try seeing something more. (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
        	 }
		 }else{
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Seeing. (".$manacon." needed)<br></span>";
        }
      }
// Force Saber Throw    (force 5)

      elseif(($p->skills->fsabe > 0 && $p->char->item_hand > 0) &&  $forcernd == 5) {
        $faktor = 0.9;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 5){ $p->playerslast_c = 5; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fsabe/9)),1);
		if($faktor < 0.1) {$faktor = 0.1 ; }
		}else { $p->playerslast_c = 5;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";

		$docastdmg = $faktor*rand(0.5*($p->skills->fsabe/2*2.3+6.9)/(1+1.5e-2*$p->skills->fsabe/2),($p->skills->fsabe/2*2.3+6.9)/(1+1.5e-2*$p->skills->fsabe/2));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 4)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {
			
			//fightstats
			$p->pcastsoff = $p->pcastsoff+1;
			//$p->manacondef = $p->manacondef + $manacon ;
			//$p->manaconoff= $p->manaconoff + $manacon ;
			//$p->defdamage = $p->defdamage + $docastdmg ;
			//$p->offdamage = $p->offdamage + $docastdmg ;
			$p->manaconoff= $p->manaconoff + $manacon ;
			$p->offdamage = $p->offdamage + $docastdmg ;
			$dmg=round($docastdmg+$stabschaden/3);
			$fdmg=$docastdmg;
			$pdmg=round($stabschaden/3);
		
			if(($pv->playerstate=="invisible" || $pv->playerstate=="projection") && $docast <= $p->hitchance_magic){
				$die = rand(0, 6);
				if($die==0 && $p->playersroutine >= 3) { $pv->playerstate = ""; $p->playersroutine = 0;}
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Saber Throw');
				$p->char->mana = $p->char->mana - $manacon;
				$p->staffcount_f = $p->staffcount_f+1;
			}elseif(($p->playerstate2=="blinded" || $p->playerstate2=="confused") && $docast <= $p->hitchance_magic){
				$die = rand(0,8);
				if($die==0 && $p->playersroutine >= 3){ $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Saber Throw');
				$p->char->mana = $p->char->mana - $manacon;
				$p->staffcount_f = $p->staffcount_f+1;
			}elseif($p->iswpn == 0){
			
				$fight_report .= "<span class=\"bf\">".$p->char->username." can not throw ".$p->herhis." weapon, cause ".$p->heshe." do not wear one.  (-".$manacon.") (".$p->char->item_hand.")<br>"; 
				$p->char->mana = $p->char->mana - $manacon; 
    		}elseif (($pv->absorbstate || $pv->protectstate) && $docast <= $p->hitchance_magic) {
				
				if($pv->absorbstate &&  $pv->protectstate == ""){
					$p->staffdamage = $p->staffdamage + $pdmg; 
            		$fight_report .= "<span class=\"bf\">".$p->char->username." throws ".$p->himher." weapon and hit ".$pv->char->username." but some damage got absorbed. (".$manacon.")<br>";
            		$fight_report .= "<span class=\"bf\">But the weapon damaged ".$p->himher." with ".$pdmg." points.</span><br>";
					$pv->char->health = $pv->char->health - $pdmg;
					$p->char->mana = $p->char->mana - $manacon;
            		$pv->char->mana = $pv->char->mana + $manacon/1.5;
            			if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            				$pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            			if ($pv->absorbstatetime <= 0) {
				 			unset($pv->absorbstate);
              				$fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
            			}
				}elseif($pv->absorbstate == "" &&  $pv->protectstate == "protect"){
				
					$p->staffcount_f = $p->staffcount_f+1;
					$fight_report .= "<span class=\"bf\">".$p->char->username." throws ".$p->hisher." weapon, hit ".$pv->char->username." but the protection shield neutralized some damage of ".$dmg.".</span><br>";
      				$fight_report .= "<span class=\"bf\">The Force damaged ".$pv->himher." with ".$fdmg." points.</span><br>";
					$pv->char->health = $pv->char->health - $fdmg;
					$p->char->mana = $p->char->mana - $manacon;
      				if (($pv->protectstatetime - $pdmg)< 1){
						$fight_report .= "<span class=\"gn\">".$pv->char->username.add_s($pv->char->username)." protection has worn off.</span><br>";
        				unset($pv->protectstate);
      					$restdmg= round(($pv->protectstatetime - $pdmg)* -1);
					if($restdmg>0){
						$fight_report .= "<span class=\"bl\">But the hit was so powerful, that ".$pv->heshe." takes ".$restdmg." points of damage.</span><br>"; 
						$pv->char->health = $pv->char->health - $restdmg;
		 				}
					}			
         		}elseif($pv->absorbstate == "absorb" &&  $pv->protectstate == "protect"){
					$p->staffcount_f = $p->staffcount_f+1; 
					$fight_report .= "<span class=\"bf\">".$p->char->username." throws ".$p->hisher." weapon, hit ".$pv->char->username." but the damage of ".$dmg." got absorbed and neutralized.</span><br>";
      				$p->char->mana = $p->char->mana - $manacon;
            		$pv->char->mana = $pv->char->mana + $manacon/1.5;
					if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            			$pv->absorbstatetime = $pv->absorbstatetime - $manacon;	
					if (($pv->protectstatetime - $pdmg)< 1){
						$fight_report .= "<span class=\"gn\">".$pv->char->username.add_s($pv->char->username)." protection has worn off.</span><br>";
        				unset($pv->protectstate);
					}
      					
            		if ($pv->absorbstatetime <= 0) {
				 		unset($pv->absorbstate);
              			$fight_report .= "<span class=\"bl\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
            			}
				}
			}else{
          		if ($docast <= $p->hitchance_magic) { 
					$p->staffdamage = $p->staffdamage + $stabschaden/3; 
					//${"staffcount_$p"} = ${"staffcount_$p"}+1; 
					$fight_report .= "<span class=\"b\">".$p->char->username." throws ".$p->herhis." weapon, hitting ".$pv->char->username." with it and damages ".$pv->herhim." for ".$dmg." points of damage.<br>"; 
					$pv->char->health = $pv->char->health - $dmg; 
					$p->char->mana = $p->char->mana - $manacon;
					if(rand(1,10) > 6 ){
						$docastdmg = round($docastdmg * ($p->skills->level/99/2.3));
						if($docastdmg < 1 ){ $docastdmg = 1;}
						$teamside = rand(0,1);
						if(${"team_$teamside"} ==  $team_p && $p->char->innocents == 1){
						$tmpteam = ${"team_$teamside"};
						$tmp2 = array_rand($tmpteam);
						$tmp1 = $tmpteam[$tmp2];
						unset($tmpteam[$tmp2]);
						//eigener treffer und rundengegner ausschliessen
						if($tmp1 != $p && $tmp1 !=$pv){
							if($tmp1->char->username != ""){
								$fight_report .= "<span class=\"r2\">One end of ".$p->herhis." weapon hitting ".$tmp1->char->username." for ".$docastdmg." points cause ".$tmp1->heshe." was distracted or too near. <br>"; 
								$tmp1->char->health = $tmp1->char->health - $docastdmg; 
							}
						}
					}
				}
				}else{ 
					$fight_report .= "<span class=\"bf\">".$p->char->username." throws ".$p->herhis." weapon, but misses.  (-".$manacon.")<br>"; 
					$p->char->mana = $p->char->mana - $manacon; 
					if(rand(1,10) > 7 && $p->char->innocents == 0){
						$docastdmg = round($docastdmg * ($p->skills->level/99/2.3));
						if($docastdmg < 1 ){ $docastdmg = 1;}
						$teamside = rand(0,1);
						$tmpteam = ${"team_$teamside"};
						if(${"team_$teamside"} ==  $team_p && $p->char->innocents == 1){
						$tmp2 = array_rand($tmpteam);
						$tmp1 = $tmpteam[$tmp2];
						unset($tmpteam[$tmp2]);
						//eigener treffer und rundengegner ausschliessen
						if($tmp1 != $p && $tmp1 !=$pv){
							if($tmp1->char->username != ""){
								$fight_report .= "<span class=\"r2\">One end of ".$p->herhis." weapon hitting ".$tmp1->char->username." for ".$docastdmg." points cause ".$tmp1->heshe." was distracted or too near. <br>"; 
								$tmp1->char->health = $tmp1->char->health - $docastdmg; 
								}
						}
					}
				}
    		}
		}
		}else{
          $fight_report .= "<span class=\"bf\">".$p->char->username." has not enough Mana to cast Force Saber Throw. (".$manacon." needed)<br>";
        }
      }

      // Force Persuasion

      elseif ( $p->skills->fpers > 0 && $forcernd == 6 ) {
        $faktor = 0.9;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 6){ $p->playerslast_c = 6; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fpers/9)),1); 
		if($faktor < 0.1) {$faktor = 0.1 ;} 
		}elseif($last == 7){ $p->playerslast_c = 6; $p->playerslast_cc++;
		}else{ $p->playerslast_c = 6;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		//if($p->playerslast_cc>0){$docast = rand(1,3+$p->playerslast_cc);}else{$docast = rand(1,2);}
		//$docastdmg = rand(($p->skills->fpers/6),($p->skills->fpers/4));
		$docastdmg = $faktor*rand(0.7*($p->skills->fpers/2*2.3+0.9)/(1+1.5e-2*$p->skills->fpers),($p->skills->fpers/2*2.3+1.9)/(1+1.5e-2*$p->skills->fpers/2));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 4)+$p->manaaddcon;
		
        if ($manacon <= $p->char->mana) {
          if($p->playerstate2=="confused" && $pv->playerstate=="" ){
		  if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." realised ".$p->herhis." try to persuade the team! (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
        	}else{
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." realised ".$p->herhis." try to persuade ".$pv->herhim."! (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
        	}
		  	$die = rand(0,8);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
		  }elseif($pv->playerstate3 == "prevent" && $p->playerstate!="invisible" ){
				$pv->playerstate3 = "";
				$p->playerstate = "";
    
		  	if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." was warned of ".$p->herhis." try to persuade the team. (-".$manacon.")&nbsp;<br>"; 
				$p->char->mana = $p->char->mana - $manacon;
        	}else{
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." was warned of ".$p->herhis." try to persuade ".$pv->herhim.". (-".$manacon.")&nbsp;<br>"; 
				$p->char->mana = $p->char->mana - $manacon;
        	}
		  }elseif(($p->playerstate == "invisible") && rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0)){
					$p->skills->tac = $p->skills->tac + round($docastdmg/$p->skills->level);
					$p->playerstatetime = $docastdmg;
					$p->char->mana = $p->char->mana - $manacon;
					if($pv->playersroutine) { unset ($pv->playersroutine); }
				$fight_report .= "<span class=\"gn\">".$p->char->username." acts by ".$p->herhis." experience, reinforces the effect of persuasion. (".$docastdmg.")</span><br>"; 
		  }elseif($docast <= $p->hitchance_magic  && rand(0,(2+$p->playerslast_cc)) <= 2) {
		  	if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
		  	$fight_report .= "<span class=\"gn\">".$p->char->username." vanished from ".$p->herhis." opponents line of sight. (".$docastdmg.")<br></span>";
			$p->playerstate = "invisible";
			$p->playerstatetime = $docastdmg;
			$p->char->mana = $p->char->mana - $manacon;
			
			}else{
			$fight_report .= "<span class=\"gn\">".$p->char->username." vanished from ".$pv->char->username.add_s($pv->char->username)." line of sight. (".$docastdmg.")<br></span>";
			$p->playerstate = "invisible";
			$p->playerstatetime = $docastdmg;
			$p->char->mana = $p->char->mana - $manacon;
			}
			$p->playersroutine_ammount = routinenammount($manacon,$p->skills->level,$pv->skills->level);
			if($comments)  $fight_report .= "<font color=\"goldenrod\">Wertigkeit zum Routinencheck: ".$p->playersroutine_ammount."</font><br>";
			//if($p->playersroutine) { unset ($p->playersroutine); }
		  }else{
		  	if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." realised ".$p->herhis." try to persuade the team. (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
        	}else{
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to vanish, but ".$pv->char->username." realised ".$p->herhis." try to persuade ".$pv->herhim.". (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
        	}
		  }
		 }else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Persuasion. (".$manacon." needed)<br></span>";
        }
      }
     // Force Projection

      elseif ( $p->skills->fproj > 0 && $forcernd == 7 ) {
        
		$faktor = 1.0;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 7){ $p->playerslast_c = 7; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fproj/9)),1);  
		if($faktor < 0.1) { $faktor = 0.1; }
		}elseif($last == 6){ $p->playerslast_c = 7; $p->playerslast_cc++;
		}else{ $p->playerslast_c = 7;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]."  mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
        
		//$docastdmg = rand(($p->skills->fproj/4),($p->skills->fproj/2));
		$docastdmg = $faktor*rand(0.7*($p->skills->fproj/2*2.3+1.9)/(1+1.5e-2*$p->skills->fproj),($p->skills->fproj/2*2.3+3.9)/(1+1.5e-2*$p->skills->fproj/2));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 3)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
          if($p->playerstate2=="confused"){
		
			$fight_report .= "<span class=\"gf\">".$p->char->username." cast a projection of ".$p->herhim."self, but failed.  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
     
		  	$die = rand(0,8);
				if($die==0 && $p->playersroutine >= 3) {
				$p->playerstate2 = "";  $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					$p->playerstate2 = "" ; $p->playersroutine = 0;
					$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
				}
		  	 }elseif($pv->playerstate3=="prevent" && $p->playerstate != "projection"){
			$pv->playerstate3 = "";
			$p->playerstate = "";
			$fight_report .= "<span class=\"gf\">".$p->char->username." cast a projection of ".$p->herhim."self, but ".$pv->char->username." was warned by the Force.  (-".$manacon.")&nbsp;<br>"; 
			$p->char->mana = $p->char->mana - $manacon; 
		
		  }elseif(($p->playerstate == "projection") && rand(0,$p->skills->level) < ($p->skills->tac) && (rand(0,1)==0)){
					$p->skills->tac = $p->skills->tac + round($docastdmg/$p->skills->level);
					$p->playerstatetime = $docastdmg;
					$p->char->mana = $p->char->mana - $manacon;
					if($pv->playersroutine) { unset ($pv->playersroutine); }
				$fight_report .= "<span class=\"gn\">".$p->char->username." acts by ".$p->herhis." experience, reinforces the effect of projection. (".$docastdmg.")</span><br>"; 
		  }elseif($docast <= $p->hitchance_magic && rand(0,(2+$p->playerslast_cc)) <= 2) { 
		  $fight_report .= "<span class=\"gn\">".$p->char->username." cast a projection of ".$p->herhim."self. (".$docastdmg.")<br></span>";
		  $p->playerstate = "projection";
		  $p->playerstatetime = $docastdmg;
		  $p->char->mana = $p->char->mana - $manacon;
		  //if($p->playersroutine) { unset ($p->playersroutine); }
		  	$p->playersroutine_ammount = routinenammount($manacon,$p->skills->level,$pv->skills->level);
			if($comments)  $fight_report .= "<font color=\"goldenrod\">Wertigkeit zum Routinencheck: ".$p->playersroutine_ammount."</font><br>";
		  }else{
		  $fight_report .= "<span class=\"gf\">".$p->char->username." cast a projection of ".$p->herhim."self, but ".$pv->char->username." recognized that.  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;  }
        }else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Projection. (".$manacon." needed)<br></span>";
        }
      }

     // Force Blind

      elseif ( $p->skills->fblin > 0 && $forcernd == 8 ) {
        $faktor = 1.1;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 8){ $p->playerslast_c = 8; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fblin/9)),1); 
		if($faktor < 0.1){ $faktor = 0.1;} 
		}elseif($last == 9){ $p->playerslast_c = 8; $p->playerslast_cc++;
		}else{ $p->playerslast_c = 8;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		//$docastdmg = rand(($p->skills->fblin/2.2),($p->skills->fblin*2.15+5));
		$docastdmg = $faktor*rand(0.7*($p->skills->fblin/2*2.3+4.9)/(1+1.5e-2*$p->skills->fblin),($p->skills->fblin/2*2.3+5.9)/(1+1.5e-2*$p->skills->fblin/2));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
			
          if($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
		  
		  	$fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Blind on ".$pv->char->username.".  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; 
		 
		  	$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "";
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
					}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					if($p->playerstatetime2<=0){ 
					$p->playerstate2 = "";
					$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
					}
				}
		  	  
		  }elseif($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
		  	$fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Blind on ".$pv->char->username.".  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; 
		   	$die = rand(0, 6);
				if($die==0 && $p->playersroutine >= 3) {
					$pv->playerstate2 = "";
					$fight_report .= "<span class=\"gf\">".$p->char->username." found ".$pv->char->username.".<br></span>";  
		 			}
				if ($die != 0 && $pv->playerstatetime2 > 0) {
        			$pv->playerstatetime2 = $pv->playerstatetime2 - 1;
					if($pv->playerstatetime2<=0){ 
					$pv->playerstate2 = "";
					$fight_report .= "<span class=\"gf\">".$p->char->username." found ".$pv->char->username.".<br></span>";  
		 			}
				}
		  	}elseif($pv->playerstate3=="prevent"){
				$pv->playerstate3 = "";
				$fight_report .= "<span class=\"gf\">".$p->char->username." cast Force Blind on ".$pv->char->username.", but ".$pv->char->username." was warned. (-".$manacon.")&nbsp;<br>"; 
				$p->char->mana = $p->char->mana - $manacon;   
		  }elseif($docast <= $p->hitchance_magic  && rand(0,(2+$p->playerslast_cc))<= 1) { 
		  $fight_report .= "<span class=\"gn\">".$p->char->username." blinds ".$pv->char->username." with Force Blind. (".$docastdmg.")<br></span>";
		  $pv->playerstate2 = "blinded";
		  $pv->playerstatetime2 = $docastdmg;;
		  $p->char->mana = $p->char->mana - $manacon; 
		  if($pv->playersroutine) { unset ($pv->playersroutine); }
		  	$pv->playersroutine_ammount = routinenammount($manacon,$p->skills->level,$pv->skills->level);
			if($comments)  $fight_report .= "<font color=\"goldenrod\">Wertigkeit zum Routinencheck: ".$pv->playersroutine_ammount."</font><br>";
		  }else{ 
		  $fight_report .= "<span class=\"gf\">".$p->char->username." cast Force Blind on ".$pv->char->username.", but ".$pv->char->username." counteres the effect.  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; 
		  }
        }else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Blind. (".$manacon." needed)<br></span>";
        }
      }

     // Force Confuse

      elseif ( $p->skills->fconf > 0 && $forcernd == 9 ) {
        $faktor = 1.3;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 9){ $p->playerslast_c = 9; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fconf/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}elseif($last == 8){ $p->playerslast_c = 9; $p->playerslast_cc++;
		}else{ $p->playerslast_c = 9;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		//$docastdmg = rand(($p->skills->fconf/2), ($p->skills->fconf*2+10));
		$docastdmg = $faktor*rand(0.7*($p->skills->fconf/2*2.3+6.9)/(1+1.5e-2*$p->skills->fconf),($p->skills->fconf/2*2.3+7.9)/(1+1.5e-2*$p->skills->fconf/2));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		//if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 3)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
          if($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
		  	$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "";
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
					}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					if($p->playerstatetime2<=0){ 
					$p->playerstate2 = "";
					$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>"; 
					}
				}
		  	  $fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Confuse on ".$pv->char->username.".  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; 
		 
		  }elseif($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
		  	$fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Confuse.  (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon; 
		   	$die = rand(0, 8);
				if($die==0 && $p->playersroutine >= 3) {
					$pv->playerstate2 = "";
					$fight_report .= "<span class=\"gf\">".$p->char->username." found ".$pv->char->username.".<br></span>";  
		 			}
				if ($die != 0 && $pv->playerstatetime2 > 0) {
        			$pv->playerstatetime2 = $pv->playerstatetime2 - 1;
					if($pv->playerstatetime2<=0){ 
					$pv->playerstate2 = "";
					$fight_report .= "<span class=\"gf\">".$p->char->username." found ".$pv->char->username.".<br></span>";  
		 			}
				}
		  	}elseif($pv->playerstate3=="prevent"){
				$pv->playerstate3 = "";
				$fight_report .= "<span class=\"gf\">".$p->char->username." tried to confuse ".$pv->char->username." with Force Confuse, but ".$pv->char->username." was warned. (-".$manacon.")&nbsp;<br>"; 
				$p->char->mana = $p->char->mana - $manacon; 
      			 
		  }elseif( $docast <= $p->hitchance_magic && rand(0,(2+$p->playerslast_cc))<= 1) { $fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Confuse, totaly confusing ".$pv->char->username.". (".$docastdmg.")<br></span>";
		  $pv->playerstate2 = "confused";
		  $pv->playerstatetime2 = $docastdmg;
		  $p->char->mana = $p->char->mana - $manacon; 
		  if($pv->playersroutine) { unset ($pv->playersroutine); }
		 	$pv->playersroutine_ammount = routinenammount($manacon,$p->skills->level,$pv->skills->level);
			if($comments)  $fight_report .= "<font color=\"goldenrod\">Wertigkeit zum Routinencheck: ".$pv->playersroutine_ammount."</font><br>";
		  }else{ 
		  $fight_report .= "<span class=\"gf\">".$p->char->username." tried to confuse ".$pv->char->username." with Force Confuse, but failed. (-".$manacon.")<br></span>"; $p->char->mana = $p->char->mana - $manacon;
		   }
        }else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Confuse. (".$manacon." needed)<br></span>";
        }
      }

      // Force Heal

      elseif ( $p->skills->fheal > 0 && $forcernd == 10 ) {
       	$faktor = 1.4;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 10){ $p->playerslast_c = 10; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fheal/9)),1); 
		if($faktor < 0.1) {$faktor = 0.1;}
		//}elseif($last == 11){ $p->playerslast_c = 9; $p->playerslast_cc++;
		}else { $p->playerslast_c = 10;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fheal/2*2.3+3.9)/(1+1.5e-2*$p->skills->fheal),($p->skills->fheal/2*2.3+7.9)/(1+1.5e-2*$p->skills->fheal/1.5));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
          if($p->playerstate2=="confused"){
		  	  	 $fight_report .= "<span class=\"gf\">".$p->char->username." tried to cast Force Heal, but failed. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
			$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>";
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
		  }elseif ($docast <= $p->hitchance_magic  && rand(0,4) >= 1) {
            $fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Heal, regenerating ".$docastdmg." points of health.<br>";
            $p->char->health = $p->char->health + $docastdmg; $p->char->mana = $p->char->mana - $manacon;
            if ($p->char->health > $p->maxhealth) { $p->char->health = $p->maxhealth; }
          }else{
            $fight_report .= "<span class=\"gf\">".$p->char->username." cast Force Heal, but ".$pv->char->username." interrupted  ".$p->herhis." try to regenerate.  (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon; }
          }else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Heal. (".$manacon." needed)<br>";
        }
      }

      // Force Team Heal

      elseif ( $p->skills->fteam > 0 && $forcernd == 11 ) {
        $faktor = 1.5;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 11){ $p->playerslast_c = 11; $p->playerslast_cc++; 
		$clmalus=round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fteam/9)),1); 
		$faktor=$faktor-$clmalus; 
		if($faktor < 0.1 ) { $faktor = 0.1; }
		}else { $p->playerslast_c = 11;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fteam/3*2.3+7.9)/(1+1.5e-2*$p->skills->fteam),($p->skills->fteam/3*2.3+9.9)/(1+1.5e-2*$p->skills->fheal/1.5));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round(count($team_p)*($docastdmg / 5));
        if ($manacon <= $p->char->mana) {
          if($p->playerstate2=="confused"){
		  	
		  	 $fight_report .= "<span class=\"gf\">".$p->char->username." tried to cast Force Team Heal, but failed. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
				$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>";
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
		  }elseif ($docast <= $p->hitchance_magic  && rand(0,3) >= 1) {
            $minrep = 2;
            $maxrep = count($team_p);
            if ($maxrep > 4) { $maxrep = 5; }
			if ($maxrep == 1) {
			//nur heal casten
				$faktor = $faktor + 0.2;
				//$faktor=$faktor-$clmalus; 
				$docastdmg = $faktor*rand(0.7*($p->skills->fheal/2*2.3+3.9)/(1+1.5e-2*$p->skills->fheal),($p->skills->fheal/2*2.3+7.9)/(1+1.5e-2*$p->skills->fheal/1.5));
				if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
				$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
				if ($docastdmg < 1) { $docastdmg = 1; }
				if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        		$manacon = round($docastdmg / 4)+$p->manaaddcon;
    			$fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Heal, regenerating ".$docastdmg." points of health.<br>";
            	$p->char->health = $p->char->health + $docastdmg; $p->char->mana = $p->char->mana - $manacon;
            	if ($p->char->health > $p->maxhealth) { $p->char->health = $p->maxhealth;} 
			}else{
			$fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Team Heal, regenerating<br>";
            $tmprepeats = rand($minrep, $maxrep);
            $tmpteam = $team_p;
            for ($th = 1; $th <= $tmprepeats; $th++) {
              $tmp2 = array_rand($tmpteam);
              $tmp1 = $tmpteam[$tmp2];
              unset($tmpteam[$tmp2]);
              if($tmp1 == $p){$docastdmg = round($docastdmg-((33*$docastdmg)/100));
			  }else{ 
			  	if(($p->skills->level/1.3) > $docastdmg ){ $docastdmg = round(rand($docastdmg,($p->skills->level/1.3)));}else{ $docastdmg = round(rand(($p->skills->level/1.3),$docastdmg)); }}
			  $manacon = round($docastdmg / 5)+$p->manaaddcon;
              $fight_report .= $docastdmg." points of health of ".$tmp1->char->username.".<br>";
			  $tmp1->health = $tmp1->char->health + $docastdmg;
			  $p->char->mana = $p->char->mana - $manacon;
			  if ($tmp1->health > $tmp1->maxhealth) { $tmp1->health = $tmp1->maxhealth; }
				}
			}
          } else {
		  	$manacon = $manacon * count($team_p);
		  	$fight_report .= "<span class=\"gf\">".$p->char->username." tried to cast Force Team Heal, but ".$pv->char->username." interrupted  ".$p->herhis." try to regenerate the team. (-".$manacon.")<br>"; 
			$p->char->mana = $p->char->mana - $manacon; }
        } else {
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force team Heal. (".$manacon." needed)<br>";
        }
      }
      // Force Protection

      elseif ( $p->skills->fprot > 0 && $forcernd == 12 ) {
	    $faktor = 1.7;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 12){ $p->playerslast_c = 12; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fprot/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 12;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = round($faktor*rand(0.7*($p->skills->fprot/2*2.3+6.9)/(1+1.5e-2*$p->skills->fprot),($p->skills->fprot/2*2.3+7.9)/(1+1.5e-2*$p->skills->fprot/2)));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
		  if($p->playerstate2=="confused"){
		
		  	 $fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Protect. (-".$manacon.")<br>";  $p->char->mana = $p->char->mana - $manacon;
				$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) 	{ $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>";
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
				
				}
		  }elseif ($docast <= $p->hitchance_magic && rand(0,3) >= 1) {
          $fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Protect. (".$docastdmg.")<br>"; $p->protectstate = "protect"; $p->protectstatetime = $docastdmg;  $p->char->mana = $p->char->mana - $manacon;
   		 }else{
		  $fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Protect. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
		 }
		}else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Protect. (".$manacon." needed)<br>";
        }
      }

      // Force absorb

      elseif ( $p->skills->fabso > 0 && $forcernd == 13 ) {
	    $faktor = 1.9;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 13){ $p->playerslast_c = 13; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fabso/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 13;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fabso/2*2.3+6.9)/(1+1.5e-2*$p->skills->fabso/1.5),($p->skills->fabso/2*2.3+9.9)/(1+1.5e-2*$p->skills->fabso/2));
		if($p->skills->side > 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 3)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
		  if($p->playerstate2=="confused"){
		  	
		  	 $fight_report .= "<span class=\"gf\">".$p->char->username." failed to cast Force Absorb. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
			$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>";
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
		  }elseif ($docast <= $p->hitchance_magic && rand(0,4) >= 1) {
          $fight_report .= "<span class=\"gn\">".$p->char->username." cast Force Absorb, creating a leeching shielding barrier around ".$p->herhim."self. (".$docastdmg.")<br>"; $p->absorbstate = "absorb"; $p->absorbstatetime = $docastdmg;  $p->char->mana = $p->char->mana - $manacon;
          }else{
		   $fight_report .= "<span class=\"gf\">".$p->char->username." cast Force Absorb, but ".$pv->char->username." interrupted  ".$p->herhis." try to create a shield barrier. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
          }
		}else{
          $fight_report .= "<span class=\"gf\">".$p->char->username." has not enough Mana to cast Force Absorb. (".$manacon." needed)<br>";
        }
      }

      // Force Revitalize   (force 14)

      elseif($p->skills->frvtl > 0  &&  $forcernd == 14) {
        $faktor = 2.4;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 14){ $p->playerslast_c = 14; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->frvtl/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 14;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->frvtl/2*2.3+6.9)/(1+1.5e-2*$p->skills->frvtl),($p->skills->frvtl/2*2.3+6.9)/(1+1.5e-2*$p->skills->frvtl/1.5));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
      	$manacon = round(count($team_p)*($docastdmg / 3))+($p->manaaddcon * count($team_p));
		//$manacon = round($docastdmg / 4)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {
			
			if($p->playerstate=="confused"){
				
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Revitalize');
				$die = rand(0,12);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;
				$fight_report .= "<span class=\"gn\">".$p->char->username.add_s($p->char->username)." condition regenerated.<br></span>";
				}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
			}elseif($docast <= ($p->hitchance_magic+30)){
			
		
			
			if(isset($deadtm_p) && isset($p->rsr)) { 
        //}elseif($docast <= ${"pf_$p"}[hitchance_magic] && isset(${"deadtm_$p"})) { 
		    	//fightstats
				$p->pcastsdef = $p->pcastsdef+1;
				//ende
				
           
                      
            //$tmpteam = $team_p;
			foreach ($deadtm_p as $key => $value) {
			//echo $value.$p->rsr."<br>";
			if($value != $p ) {
			//$fight_report .=  "Team passed, RS-Id=:".$p->rsr ; 
			$tmp1 = $p->rsr;
			$tmpkey = $key;
			//$fight_report .=  "Empty Teamposition: ".$tmpkey.;
			//$fight_report .=  "INFO rsr: ".$tmp1.", von ".$p." , key: ".$tmpkey." org: ".$deadtm_p['$tmpkey']; 
			}
			//if($value == $p){
			//$tmpkey_p = $key + 1;
			//}
			//if(!isset($tmpkey_p)) {
			//	$tmpkey_p = $key + 1;			
			//	}
			}
			
           if($tmpkey == '0'){$tmpkey_p = '1' ;}else{ $tmpkey_p = '0' ;}
		//   if(${'rsr_$value'}==$tmp1){
		 //  $fight_report .=  "rsr: ".$tmp1.", von: ".${'rsr_$value'}; 
		  // }
		  // if(in_array($p, $team_2) == true) {
			//		$team_2[$tmpkey] = $tmp1;
					//array_push($team0, $tmp1);
					//$fight_report .=  " T2 (".$p.".) belebt: ".$tmp1."";
					//ksort($team0);
				//	}elseif(in_array($p, $team0) == true){
			//		$team0[$tmpkey] = $tmp1;
					//array_push($team_2, $tmp1);
					//ksort($team_2);
				//	$fight_report .=  " T1 (".$p.".) belebt: ".$tmp1."";
					//}
				//$fight_report .=  "( a: ".$team_p[0].",".$team_p[1]." )";
			//}
		   }else{	
					$tmpteam = $team_p;
					$tmp2 = array_rand($tmpteam);
					$tmp1 = $tmpteam[$tmp2];
					unset($tmpteam[$tmp2]);
					}
		   if($tmp1->char->username != $p->char->username){
			  $fight_report .= "<span class=\"o\">".$p->char->username." cast Force Revitalize";
			  $docastdmg = $faktor*rand(0.7*($p->skills->frvtl/2*2.3+6.9)/(1+1.5e-2*$p->skills->frvtl),($p->skills->frvtl*2.3+6.9)/(1+1.5e-2*$p->skills->frvtl/1.5));
			  $docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
      		  $manacon = round(($docastdmg/2.5)+$p->manaaddcon);
			  	$p->manacondef = $p->manacondef + $manacon ;
				//$p->manaconoff= $p->manaconoff + $manacon ;
				$p->defdamage = $p->defdamage + $docastdmg ;
				//$p->offdamage = $p->offdamage + $docastdmg ;
				//$fight_report .=  "( b: ".$team_p[0].",".$team_p[1]." )";
				//$fight_report .=  $p.array_key_exists($p, $team0)." und t2: ".$p. array_key_exists($p, $team_2) ;
				
				if(isset($p->rsr) == false){
					$showdmg = round($docastdmg / 4);
				
					$fight_report .=  ", revitalizes the team with ".$showdmg." points. (-".$manacon.")<br></span>"; 
					//$tmp1->char->health = $docastdmg; 
					$p->char->mana = $p->char->mana - $manacon;
            		$p->skills->cns = $p->skills->cns + round($docastdmg/3);
					$p->skills->agi = $p->skills->agi + round($docastdmg/4);
					$tmp1->skills->cns = $tmp1->skills->cns + round($docastdmg/4);
					$tmp1->skills->agi = $tmp1->skills->agi + round($docastdmg/5);
				
				 }elseif ($docastdmg > 10 && isset($deadtm_p) && isset($p->rsr)){
					$fight_report .=  ", resurrecting ".$tmp1->char->username." with ".$docastdmg." points of health. (-".$manacon.", ".$tmp1."-".$p->rsr.")<br></span>"; 
					$tmp1->playerstate ="";
					$tmp1->playerstate2 ="";
					$tmp1->playerstate3 ="";
					$tmp1->char->health = $docastdmg; 
					$p->char->mana = $p->char->mana - $manacon;
            		//unset(${"deadtm_p"});
					//unset(${"deadtm_p"});
					 if(in_array($p, $team1) == true) {
					if(isset($tmpkey)){
						$team1[$tmpkey] = $tmp1;
						}
					if(isset($tmpkey_p)){
						$team1[$tmpkey_p] = $p;
						}	
					//array_push($team0, $tmp1);
					//$fight_report .=  " (T2 ".$tmp1.")";
					//ksort($team0);
					}elseif(in_array($p, $team0) == true){
					if(isset($tmpkey)){
						$team0[$tmpkey] = $tmp1;
						}
					if(isset($tmpkey_p)){
						$team0[$tmpkey_p] = $p;
						}	
					//array_push($team_2, $tmp1);
					//ksort($team_2);
					//$fight_report .=  " (T1 ".$tmp1.")";
					}
					//$fight_report .=  "DEBUG: ETP: ".$tmpkey.", OTP: ".$tmpkey_p." - NT1: ".$team0[0].",".$team0[1]." - NT2: ".$team_2[0].",".$team_2[1]." <br>";
					unset($tmp1,  $p->rsr, $deadtm_p, $tmpkey, $tmpkey_p);
				
				//$fight_report .=  "( a: ".$team_p[0].",".$team_p[1]." )";
					$p->skills->cns = $p->skills->cns + round($docastdmg/3);
					$p->skills->agi = $p->skills->agi + round($docastdmg/4);
					$tmp1->skills->cns = $tmp1->skills->cns + round($docastdmg/4);
					$tmp1->skills->agi = $tmp1->skills->agi + round($docastdmg/5);
				}elseif(isset($deadtm_p) && !isset($p->rsr)){
					$fight_report .=  ", but ".$tmp1->char->username." could not be resurrected. (-".$manacon.") <br></span>"; 
					unset($tmp1,$tmpkey);
					//$tmp1->char->health = 0; 
					$p->char->mana = $p->char->mana - $manacon;
					//$p->skills->cns = $p->skills->cns + round($docastdmg/3);
					//$p->skills->agi = $p->skills->agi + round($docastdmg/4);
						}
					}else{
						$fight_report .= "<span class=\"of\">".$p->char->username." tried to cast Force Revitalize, but ".$p->herhis." concentration was not enough.<br>"; 
						$p->char->mana = round(($p->char->mana - $manacon)*0.5); 
						}
			//		}
				
          }else{ 
		   $fight_report .= "<span class=\"of\">".$p->char->username." tried to cast Force Revitalize, but ".$pv->char->username." interrupted  ".$p->herhis." try to recharge the teammate.<br>"; 
		   $p->char->mana = round(($p->char->mana - $manacon)*0.7); }
        }else{
          $fight_report .= "<span class=\"of\">".$p->char->username." has not enough Mana to cast Force Revitalize. (".$manacon." needed)<br>";
        }
      }

      // Force Throw    (force 15)

      elseif ( $p->skills->fthro > 0 && $forcernd == 15 ) {
        $faktor = 1.0;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 15){ $p->playerslast_c = 15; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fthro/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 15;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.5*($p->skills->fthro/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthro/2),($p->skills->fthro/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthro/2));
		//$docastdmg = $faktor*rand(0.5*($p->skills->fsabe/2*2.3+6.9)/(1+1.5e-2*$p->skills->fsabe/2),($p->skills->fsabe/2*2.3+6.9)/(1+1.5e-2*$p->skills->fsabe/2));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 4)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {
			if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Throw');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) 	{ $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
				
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Throw');

			}elseif ($pv->protectstate && $docast <= $p->hitchance_magic){
      			$dodmg = $docastdmg;
	  			//$dodmg = rand(${"pf_$p"}[mindmg], ${"pf_$p"}[maxdmg]);
      			if (($pv->protectstatetime - $dodmg)< 1){
				$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Throw, hitting ".$pv->char->username." but the protection shield neutralized some damage of ".$dodmg.".</span><br>";$p->char->mana = $p->char->mana - $manacon;
      			$fight_report .= "<span class=\"gn\">".$pv->char->username.add_s($pv->char->username)." protection has worn off.</span><br>";
        		unset($pv->protectstate);
      			$restdmg=($pv->protectstatetime - $dodmg)* -1;
					if($restdmg>0){
					$fight_report .= "<span class=\"r2\">Some debris hitting ".$pv->char->username." and damages ".$pv->herhim." for ".$restdmg." points of damage.</span><br>"; $pv->char->health = $pv->char->health - $restdmg;
		 			}
				}else{
      			$pv->protectstatetime = $pv->protectstatetime - $dodmg;
				$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Throw, hitting ".$pv->char->username." but the protection shield neutralized the damage of ".$dodmg."  (".$pv->protectstatetime." left).</span><br>";$p->char->mana = $p->char->mana - $manacon;
      			}
		  }elseif($docast <= $p->hitchance_magic){

			$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Throw, hitting ".$pv->char->username." with some debris and damages ".$pv->herhim." for ".$docastdmg." points of damage.<br>"; 
			$pv->char->health = $pv->char->health - $docastdmg; 
			$p->char->mana = $p->char->mana - $manacon;
			if(rand(1,10) > 0){
			$docastdmg = round($docastdmg * ($p->skills->level/$pv->skills->level/2.3));
			if($docastdmg < 1 ){ $docastdmg = 1;}
			if($docastdmg > $p->skills->level ){ $docastdmg = rand($p->skills->level,$pv->skills->level);}
			$teamside = rand(0,1);
            $tmpteam = ${"team_$teamside"};
            
			if($tmpteam ==  $team_p && $p->char->innocents == 1){
			$tmp2 = array_rand($tmpteam);
			$tmp1 = $tmpteam[$tmp2];
			unset($tmpteam[$tmp2]);
                //eigener treffer und rundengegner ausschliessen
				if($tmp1 != $p && $tmp1 != $pv){
					if($tmp1->char->username != ""){
						$fight_report .= "<span class=\"r2\">Some of debris and crates hitting ".$tmp1->char->username." for ".$docastdmg." points cause ".$tmp1->heshe." was distracted or too near. <br>"; 
						$tmp1->char->health = $tmp1->char->health - $docastdmg; 
						}
                    }
				}
			 }
		  }else{ 
			$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Throw, throwing debris and crates, but ".$pv->char->username." is able to quickly evade them. (-".$manacon.")<br>"; 
			$p->char->mana = $p->char->mana - $manacon;
			if(rand(1,10) > 6){
			$docastdmg = round($docastdmg * ($p->skills->level/$pv->skills->level/2.3));
			if($docastdmg < 1 ){ $docastdmg = 1;}
			if($docastdmg > $p->skills->level ){ $docastdmg = rand($p->skills->level,$pv->skills->level);}
			$teamside = rand(0,1);
			if(${"team_$teamside"} ==  $team_p && $p->char->innocents == 1){
			$tmpteam = ${"team_$teamside"};
			$tmp2 = array_rand($tmpteam);
			$tmp1 = $tmpteam[$tmp2];
			unset($tmpteam[$tmp2]);
				//eigener treffer und rundengegner ausschliessen
				if($tmp1 != $p && $tmp1 !=$pv){
					if($tmp1->char->username != ""){
						$fight_report .= "<span class=\"r2\">Some of debris and crates hitting ".$tmp1->char->username." for ".$docastdmg." points cause ".$tmp1->heshe." was distracted or too near. <br>"; 
						$tmp1->char->health = $tmp1->char->health - $docastdmg; 
						}
					}
				}
			}
		  }
        }else{
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Throw. (".$manacon." needed)<br>";
        }
      }

      // Force Rage

      elseif ( $p->skills->frage > 0 && $forcernd == 16 ) {
	    $faktor = 1.1;
        $last = $p->playerslast_c;
		if($p->playerslast_c == 16){ $p->playerslast_c = 16; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->frage/9)),1);
		if($faktor < 0.1 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 16;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*(10+ $p->skills->frage / 10) ;
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
		$manacon = round($docastdmg / 3)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {

          if ($docast <= $p->hitchance_magic) {
            $p->playerstate3 = $p->playerstate3 = 0;
			$p->char->mana = $p->char->mana - $manacon;
			if($p->playerslast_cc == 0) {$teiler = 1;} else {$teiler = $p->playerslast_cc;} 
			$malus=rand($docastdmg-1, ($docastdmg+(10 + $p->skills->frage / 10)/ $teiler));
			if($malus>=$p->char->health){$malus=round($p->char->health/2);}
			if($malus==1){$malus=0;}
			$p->char->health = $p->char->health - $malus; //der kleine malus neben den 3 Boni
				if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"){
				$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Rage, feeling so much stronger, agile, agressive and mad against the opponents.<br>"; //".$pv->char->username.".<br>";
				}else{
				$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Rage, feeling so much stronger, agile, agressive and mad against ".$pv->char->username."<br>"; //".$pv->char->username.".<br>";
       
				}
				     if($malus!=0){
			$fight_report .= "<span class=\"r2\">".$p->char->username." pays this with ".$malus." points of health.<br>";// (+".$p->playerstate2*$docastdmg*2.5." skills)<br>";
			}
			//eigenen zustand setzen
			$p->playerstate = "rage";
			$p->playerstatetime = $docastdmg;
			//anderen eigenen zustand aufheben
			$p->playerstate2 = "";
			//if($pv->playerstate) {$pv->playerstate = "1";}
			
			$p->playerstatetime3 = $docastdmg;
			$p->skills->dex = $p->skills->dex + $docastdmg;
            $p->skills->agi = $p->skills->agi + $docastdmg;
            $p->skills->lsa = $p->skills->lsa + ($docastdmg / 2);
          }
          else { $fight_report .= "<span class=\"rf\">".$p->char->username." tried to cast Force Rage but got interrupted.  (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon; 
		  if($p->playerstate != ""){
				if($p->playerstate == "invisible" ) {    // routine for invisible players
					$fight_report .= "<span class=\"gn\">".$p->char->username." returned from invisibility.</span><br>";
					}
				if($p->playerstate == "projection" ) {     // routine for projections
					$fight_report .= "<span class=\"gn\">All projections of ".$p->char->username." disappeared.</span><br>";
					}
					$p->playerstate = "";
				}
		  }
        }
        else {
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Rage. (".$manacon." needed)<br>";
        }
      }

      // Force Grip

      elseif ( $p->skills->fgrip > 0 && $forcernd == 17 ) {
        $faktor = 1.1;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 17){ $p->playerslast_c = 17; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fgrip/9)),1);  
		if($faktor < 0.1) {$faktor = 0.1;}
		}else { $p->playerslast_c = 17;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.5*($p->skills->fgrip/2*2.3+6.9)/(1+1.5e-2*$p->skills->fgrip),($p->skills->fgrip/2*3.3+9.9)/(1+1.5e-2*$p->skills->fgrip/2));
		//$docastdmg2nd = $docastdmg * 1.5;
		$docastdmg2nd = round(($docastdmg * 1.3)*(1/(1.2+ ($p->playerslast_cc/10))));
        $docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
        $docastdmg2nd = round($docastdmg2nd + ($docastdmg2nd * $p->itlbonus / 100));
		if($p->skills->side < 0) { $docastdmg2nd + round($docastdmg2nd * (0.2 * abs($p->skills->side / 32768 * 100))); }
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 4)+$p->manaaddcon;
		$manacon2nd = round($docastdmg2nd / 4)+$p->manaaddcon;

        if ($manacon <= $p->char->mana) {

		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				if(rand(1, 4) > 1) { $manacon= round($manacon * 0.9); }else{ $manacon=round($manacon2nd * 0.9);} 
            	$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Grip');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) 	{ $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
				
				}
				if(rand(1, 4) > 1) { $manacon= round($manacon * 0.9); }else{ $manacon=round($manacon2nd * 0.9);} 
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Grip');
				//$p->char->mana = $p->char->mana - $manacon;
			}elseif ($pv->absorbstate  && $docast <= 1.2*$p->hitchance_magic) {
			 	if(rand(1, 4) > 1) { $manacon= round($manacon * 0.9); }else{ $manacon=round($manacon2nd * 0.9);} 
                $drittel = round($manacon / 1.5);
            $fight_report .= "<span class=\"rf\">".$p->char->username." tried to choke ".$pv->char->username." with a Force Grip, but the cast got absorbed. (".$drittel.")<br>";
            $p->char->mana = $p->char->mana - $drittel;
            $pv->char->mana = $pv->char->mana + $drittel;
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            if ($pv->absorbstatetime <= 0) {
              unset($pv->absorbstate);
              $fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
            }
          }else{
            if ($docast <= 0.6*$p->hitchance_magic && (rand(0,(2+$p->playerslast_cc))<= 1 && $p->playerslast_cc <= 2)) {
              $fight_report .= "<span class=\"r2\">".$p->char->username." hurls ".$pv->char->username.add_s($pv->char->username)." body against a nearby wall and damages ".$pv->herhim." for ".$docastdmg2nd." points of damage.<br>";
              $pv->char->health = $pv->char->health - $docastdmg2nd;
			  $p->char->mana = $p->char->mana - $manacon2nd;
            }elseif ($docast <= 0.9*$p->hitchance_magic && rand(0,(2+$p->playerslast_cc))<= 1) {
              $fight_report .= "<span class=\"r2\">".$p->char->username." lifts ".$pv->char->username.add_s($pv->char->username)." body with a Force Grip and chokes ".$pv->herhim." for ".$docastdmg." points of damage.<br>";
              $pv->char->health = $pv->char->health - $docastdmg;
              $p->char->mana = $p->char->mana - $manacon;
            }else{
				if(rand(1, 4) > 1) { $manacon= round($manacon * 0.9); }else{ $manacon=round($manacon2nd * 0.9);} 
              $fight_report .= "<span class=\"rf\">".$p->char->username." tried to choke ".$pv->char->username." with a Force Grip, but failed. (-".$manacon.")<br>";
              $p->char->mana = $p->char->mana - $manacon;
            }
          }
       } else {
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Grip. (".$manacon." needed)<br>";
        }
      }

      // Force Drain

      elseif ( $p->skills->fdrai > 0 && $forcernd == 18 ) {
        $faktor = 1.1;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 18){ $p->playerslast_c = 18; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fdrai/9)),1);  
		if($faktor < 0.1) { $faktor = 0.1;}
		}else { $p->playerslast_c = 18;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.6*($p->skills->fdrai/2*2.3+3.9)/(1+1.5e-2*$p->skills->fdrai/2),($p->skills->fdrai/2*2.3+9.9)/(1+1.5e-2*$p->skills->fdrai/2));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		$manacon = round($docastdmg / 3)+$p->manaaddcon; // 1/4 d. Schadens = mana verbrauch
        if ($docastdmg > ($pv->char->health / 3.3)) { $docastdmg = round($pv->char->health / 3.3); } // Schaden = max 1/4 gegner health
		if ($docastdmg == 0 ) { $docastdmg = 1; }
		if ($manacon <= $p->char->mana) {
		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Drain');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Drain');

			}elseif ($pv->absorbstate && $docast <= $p->hitchance_magic) {
            $fight_report .= "<span class=\"rf\">".$p->char->username." tried to drain ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br>";
            $p->char->mana = $p->char->mana - $manacon;
            $pv->char->mana = $pv->char->mana + $manacon/1.5;
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
          }
          else {
            //if ($docast <= $p->hitchance_magic) { $fight_report .= "<span class=\"r2\">".$p->char->username." drains ".$pv->char->username." for ".$docastdmg." points of health, converting the health to energy.<br>"; $p->char->mana = $p->char->mana - $manacon + $docastdmg; if ($p->char->mana > $p->maxmana) { $p->char->mana = $p->maxmana; } $pv->char->health = $pv->char->health - $docastdmg; }
            if ($docast <= $p->hitchance_magic) { 
			$fight_report .= "<span class=\"r2\">".$p->char->username." drains ".$pv->char->username." for ".$docastdmg." points of health and feels so much better.<br>"; 
			$p->char->mana = $p->char->mana - $manacon; 
			$p->char->health = $p->char->health + round($docastdmg/1.5); 
			if ($p->char->health > $p->maxhealth) { $p->char->health = $p->maxhealth; } 
			$pv->char->health = $pv->char->health - $docastdmg;
			}else { 
			 $manacon=round($manacon / 1.2);
			$fight_report .= "<span class=\"rf\">".$p->char->username." tried to hit ".$pv->char->username." with a Force Drain, but failed.  (-".$manacon.")<br>"; 
			$p->char->mana = $p->char->mana - $manacon; }
          }

        }else {
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Drain. (".$manacon." needed)<br>";
        }
      }

      // Force Thunder Bolt

      elseif ( $p->skills->fthun > 0 && $forcernd == 19 ) {
        $faktor = 1.3;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 19){ $p->playerslast_c = 19; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fthun/9)),1); 
		if($faktor < 0.1){ $faktor = 0.1;}
		}else{ $p->playerslast_c = 19;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fthun/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthun),($p->skills->fthun/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthun/2));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Thunderbolt');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Thunderbolt');

			}elseif ($pv->absorbstate && $docast <= $p->hitchance_magic) {
            $fight_report .= "<span class=\"rf\">".$p->char->username." tried to cast Force Thunder Bolt and hit ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br>";
            $p->char->mana = $p->char->mana - $manacon;
            $pv->char->mana = $pv->char->mana + $manacon/1.5;
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            if ($pv->absorbstatetime <= 0) {
              unset($pv->absorbstate);
              $fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
            }
         }else{
         	if ($docast <= $p->hitchance_magic) {
              $fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Thunder Bolt and hit ".$pv->char->username." for ".$docastdmg." points of damage.<br>";
              $pv->char->health = $pv->char->health - $docastdmg; $p->char->mana = $p->char->mana - $manacon;
        	}else{
			  $manacon=round($manacon * 0.9);
              $fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Thunder Bolt and tried to hit ".$pv->char->username.", but failed. (-".$manacon.")<br>";
              $p->char->mana = $p->char->mana - $manacon;
			  }
			}
        }else{
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Thunder Bolt. (".$manacon." needed)<br>";
        }
      }

      // Force Chainlightning

	elseif ( $p->skills->fchai > 0 && $forcernd == 20 ) {
		$faktor = 1.1;
		//$clmalus = 0;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 20){ $p->playerslast_c = 20; $p->playerslast_cc++; 
		$clmalus=round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fchai/9)),1); 
		$faktor=$faktor-$clmalus; 
		if($faktor < 0) {$faktor = 0.1;} 
		}else { $p->playerslast_c = 20;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
	  $docastdmg = count($team_pv)*($faktor*rand(0.7*($p->skills->fchai/2*2.3+6.9)/(1+1.5e-2*$p->skills->fchai),($p->skills->fchai/2*2.3+6.9)/(1+1.5e-2*$p->skills->fchai/2)));
	  if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
	  $docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
	  $manacon = round($docastdmg / 4)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
          if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Chain Lightning');
				$p->char->mana = $p->char->mana - $manacon;

			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Chain Lightning');

			}elseif ($docast <= $p->hitchance_magic) {
		  	 $minrep = 2;
            $maxrep = count($team_pv);
            if ($maxrep > 5) { $maxrep = 5; } 
			if ($maxrep == 1) {
			//only thunder
				//$faktor = 1.3;
				$faktor = $faktor + 0.2;
				//$faktor = $faktor - $clmalus;
				if($faktor<0) { $faktor = 0.1; }
				$docastdmg = $faktor*rand(0.7*($p->skills->fthun/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthun),($p->skills->fthun/2*2.3+6.9)/(1+1.5e-2*$p->skills->fthun/2));
				if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
				$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
				if ($docastdmg < 1) { $docastdmg = 1; }
				if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
				$manacon = round($docastdmg / 4)+$p->manaaddcon;
			
				if ($pv->absorbstate) {
				$fight_report .= "<span class=\"rf\">".$p->char->username." tried to cast Force Thunder Bolt and hit ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br>";
				$p->char->mana = $p->char->mana - $manacon;
				$pv->char->mana = $pv->char->mana + $manacon/1.5;
				if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
				$pv->absorbstatetime = $pv->absorbstatetime - $manacon;
				if ($pv->absorbstatetime <= 0) {
				unset($pv->absorbstate);
				$fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
				}
				}else{
					$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Thunder Bolt and hit ".$pv->char->username." for ".$docastdmg." points of damage.<br>";
					$pv->char->health = $pv->char->health - $docastdmg; $p->char->mana = $p->char->mana - $manacon;
				}
			}else{
			$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Chain Lightning hitting<br>";
           
            $tmprepeats = rand($minrep, $maxrep);
            $tmpteam = $team_pv;
            for ($th = 1; $th <= $tmprepeats; $th++) {
              $tmp2 = array_rand($tmpteam);
              $tmp1 = $tmpteam[$tmp2];
              unset($tmpteam[$tmp2]);
			  $faktor = $faktor-(($th/10)/2);
			  if($faktor<0) { $faktor = 0.1; }
              $docastdmg = round($faktor*rand(0.7*($p->skills->fchai/2*2.3+3.9)/(1+1.5e-2*$p->skills->fchai),($p->skills->fchai/2*2.3+6.9)/(1+1.5e-2*$p->skills->fchai/2)));
			  if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
			  $docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
			  $manacon = round($docastdmg / 4)+$p->manaaddcon;
			  if ($tmp1->absorbstate) {
                $fight_report .= "<span class=\"rf\">".$tmp1->char->username.", but the cast got absorbed. (".$manacon.")<br>";
                $p->char->mana = $p->char->mana - $manacon;
                $tmp1->char->mana = $tmp1->char->mana + $manacon/1.5;
                if ($tmp1->char->mana > $tmp1->maxmana) { $tmp1->char->mana = $tmp1->maxmana; }
                $tmp1->absorbstatetime = $tmp1->absorbstatetime - $manacon;
                if ($tmp1->absorbstatetime <= 0) {
                  unset($tmp1->absorbstate);
                  $fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$tmp1->char->username.add_s($tmp1->char->username)." absorb shield.</span><br>";
                }
              }else{
                $fight_report .= "<span class=\"r2\">".$tmp1->char->username." for ".$docastdmg." points of damage.<br>";
				$tmp1->char->health = $tmp1->char->health - $docastdmg; $p->char->mana = $p->char->mana - $manacon;
              		}
            	}
			}
          }elseif(count($team_pv) > 1){
		  	$manacon = $manacon * count($team_pv);
			$manacon = round($manacon * 0.9);
            $fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Chain Lightning and tried to hit one of ".$p->herhis." opponents, but failed. (-".$manacon.")</span><br>";
			$p->char->mana = $p->char->mana - $manacon;
          }else{
			$manacon = round($manacon * 0.9);
            $fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Thunder Bolt and tried to hit ".$pv->char->username.", but failed. (-".$manacon.")<br>";
            $p->char->mana = $p->char->mana - $manacon;
			}
        }else{
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Chain Lightning. (".$manacon." needed)</span><br>";
        }
      }

      // Force Destruction

      elseif ( $p->skills->fdest > 0 && $forcernd == 21 ) {
        $faktor = 1.4;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 21){ $p->playerslast_c = 21; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fdest/9)),1); 
		if($faktor < 0.1) $faktor = 0.1; 
		}else { $p->playerslast_c = 21;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fdest/2*2.3+6.9)/(1+1.5e-2*$p->skills->fdest/1.5),($p->skills->fdest/2*2.3+9.9)/(1+1.5e-2*$p->skills->fdest/2));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg2nd = round(($docastdmg * 1.4)*(1/(1.2+ ($p->playerslast_cc/10))));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
        $docastdmg2nd = round($docastdmg2nd + ($docastdmg2nd * $p->itlbonus / 100));
		if ($docastdmg < 1) { $docastdmg = 1; }
		if ($docastdmg2nd < 1) { $docastdmg2nd = 1; }
        $manacon = round($docastdmg / 3.5)+$p->manaaddcon;
        $manacon2nd = round($docastdmg2nd / 3.5)+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
			if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Destruction');
				$p->char->mana = $p->char->mana - $manacon;
			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Destruction');
			}elseif ($pv->absorbstate && $docast <= 0.7*$p->hitchance_magic){
		  	if ($docast >= 0.7*$p->hitchance_magic && rand(1, 6) == 1) {$manacon=$manacon2nd;}
            $fight_report .= "<span class=\"rf\">".$p->char->username." tried to cast Force Destruction and hit ".$pv->char->username." but the cast got absorbed. (".$manacon.")<br>";
            $p->char->mana = $p->char->mana - $manacon;
            $pv->char->mana = $pv->char->mana + $manacon/1.5;
            if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
            $pv->absorbstatetime = $pv->absorbstatetime - $manacon;
            if ($pv->absorbstatetime <= 0) {
              unset($pv->absorbstate);
              $fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$pv->char->username.add_s($pv->char->username)." absorb shield.<br>";
            }
          }else{
			if ($docast <= 0.3*$p->hitchance_magic && rand(1, 6) > 1 && $p->playerslast_cc < 3) {
              $fight_report .= "<span class=\"r2\">".$p->char->username." hit ".$pv->char->username." with an immense exploding energyball of pure Force and Destruction and damages ".$pv->herhim." for ".$docastdmg2nd." points of damage.<br>";
              $pv->char->health = $pv->char->health - $docastdmg2nd; $p->char->mana = $p->char->mana - $manacon2nd;
			 }elseif ($docast <= 0.7*$p->hitchance_magic && rand(1, 6) > 1) {
              $fight_report .= "<span class=\"r2\">".$p->char->username." hit ".$pv->char->username." with a powerful Force of Destruction shockwave and damages ".$pv->herhim." for ".$docastdmg." points of damage.<br>";
              $pv->char->health = $pv->char->health - $docastdmg; $p->char->mana = $p->char->mana - $manacon;
            
			}else{
			 if ($docast <= 0.8*$p->hitchance_magic){
              $fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Destruction but ".$pv->char->username." evades the shockwave. (-".$manacon.")<br>";
              $manacon=round($manacon * 0.9);
			  $p->char->mana = $p->char->mana - $manacon;
			  }else{
			   $manacon=round($manacon * 0.6);
			  $fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Destruction but ".$p->heshe." was interrupted in ".$p->hisher." concentration. (-".$manacon.")<br>";
              $p->char->mana = $p->char->mana - $manacon;
					}
				}
			}
         }else{
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Destruction. (".$manacon." needed)<br>";
        }
      }

      // Force Deadly Sight

      elseif ( $p->skills->fdead > 0 && $forcernd == 22 ) {
        $minrep = 2;
        $maxrep = count($team_pv);
        $faktor = 1.4;
		$last = $p->playerslast_c;
		if($p->playerslast_c == 22){ $p->playerslast_c = 22; $p->playerslast_cc++; 
        $clmalus=round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->fdead/9)),1); 
		$faktor=$faktor-$clmalus; 
		if($faktor < 0 ) { $faktor = 0.1; } 
		}else { 
		$p->playerslast_c = 22;  $p->playerslast_cc = 0;
		}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		
		$docastdmg = $faktor*rand(0.7*($p->skills->fdead/2*2.3+3.9)/(1+1.5e-2*$p->skills->fdead),($p->skills->fdead/2*2.3+5.9)/(1+1.5e-2*$p->skills->fdead/2));
		if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
        //$manacon = round($docastdmg / 3)+$p->manaaddcon;
		if ($docastdmg < 1) { $docastdmg = 1; }
		$manacon = round(count($team_pv)*(($docastdmg / 3.7)+$p->manaaddcon));
        if ($manacon <= $p->char->mana) {
		if($pv->playerstate=="invisible" || $pv->playerstate=="projection"){
				$manacon=round($manacon/2.3);
				$die = rand(0, 5);
				if($die==0 && $p->playersroutine >= 3) $pv->playerstate = "";
				if ($die != 0 && $pv->playerstatetime > 0) {
        			$pv->playerstatetime = $pv->playerstatetime - 1;
					if($pv->playerstatetime<=0){ $pv->playerstate = "";}
				}
				$fight_report .= getstateopponent($pv->playerstate,$pv->char->username,$pv->herhim,$p->char->username,$manacon,'Force Deadly Sight');
				$p->char->mana = $p->char->mana - $manacon;
			}elseif($p->playerstate2=="blinded" || $p->playerstate2=="confused"){
				$die = rand(0,6);
				$manacon=round($manacon/2.3);
				if($die==0 && $p->playersroutine >= 3) { $p->playerstate2 = "" ; $p->playersroutine = 0;}
				if ($die != 0 && $p->playerstatetime2 > 0) {
        			$p->playerstatetime2 = $p->playerstatetime2 - 1;
					
				}
				$fight_report .= getstateself($p->playerstate2,$p->char->username,$p->heshe,$pv->char->username,$manacon,'Force Deadly Sight');
		    }elseif ($docast <= 0.4*$p->hitchance_magic) {
                if ($maxrep > 8) { $maxrep = 8; } 
				if ($maxrep == 1) { 
					if ($pv->absorbstate && $docast <= 0.4*$p->hitchance_magic) {
						$fight_report .= "<span class=\"rf\">".$p->char->username." tried to cast Force Deadly Sight, but the cast got absorbed. (".$manacon.")<br>";
						$p->char->mana = $p->char->mana - $manacon;
						$pv->char->mana = $pv->char->mana + $manacon/1.5;
						if ($pv->char->mana > $pv->char->maxmana) { $pv->char->mana = $pv->char->maxmana; }
						$pv->absorbstatetime = $pv->absorbstatetime - $manacon;
						}
					//$faktor = 1.3;
					//$faktor=$faktor-$clmalus;
					if($faktor < 0 ) { $faktor = 0.1; } 
				  	$docastdmg = $faktor*rand(0.7*($p->skills->fdead/2*2.3+2.9)/(1+1.5e-2*$p->skills->fdead),($p->skills->fdead/2*2.3+4.9)/(1+1.5e-2*$p->skills->fdead/2));
					if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		           	$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
            	  	$manacon = round($docastdmg / 3.7);
                	$fight_report .= "<span class=\"r2\">".$p->char->username." looks at ".$pv->char->username.", letting ".$pv->herhim." feel the heat of the force, inflicting ".$docastdmg." points of damage.<br>";
					$pv->char->health = $pv->char->health - $docastdmg;
					$p->char->mana = $p->char->mana - $manacon;
				}else{
					$fight_report .= "<span class=\"r2\">".$p->char->username." cast Force Deadly Sight hitting<br>";
					$tmprepeats = rand($minrep, $maxrep);
					$tmpteam = $team_pv;
					for ($th = 1; $th <= $tmprepeats; $th++) {
                  $tmp2 = array_rand($tmpteam);
                  $tmp1 = $tmpteam[$tmp2];
                  unset($tmpteam[$tmp2]);
				  if(rand(0,1) == 0){ $faktor = $faktor-($th/16); }else{ $faktor = $faktor+($th/16);  }
               	  if($faktor < 0 ) { $faktor = 0.1; } 
				  $docastdmg = $faktor*rand(0.7*($p->skills->fdead/2*2.3+2.9)/(1+1.5e-2*$p->skills->fdead),($p->skills->fdead/2*2.3+4.9)/(1+1.5e-2*$p->skills->fdead/2));
				  if($p->skills->side < 0) { $docastdmg + round($docastdmg * (0.2 * abs($p->skills->side / 32768 * 100))); }
		          $docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
            	  $manacon = round($docastdmg / 3.7);
                  if ($tmp1->absorbstate) {
                    $fight_report .= $tmp1->char->username.", but the cast got absorbed. (".$manacon.")<br>";
                    $p->char->mana = $p->char->mana - $manacon;
                    $tmp1->char->mana = $tmp1->char->mana + $manacon/1.5;
                    if ($tmp1->char->mana > $tmp1->maxmana) { $tmp1->char->mana = $tmp1->maxmana; }
                    $tmp1->absorbstatetime = $tmp1->absorbstatetime - $manacon;
                    if ($tmp1->absorbstatetime <= 0) {
                      unset($tmp1->absorbstate);
                      $fight_report .= "<span class=\"r2\">".$p->char->username." breaks ".$tmp1->char->username.add_s($tmp1->char->username)." absorb shield.<br>";
                    }
                  }else{
                    $fight_report .= "<span class=\"r2\">".$tmp1->char->username." for ".$docastdmg." points of damage.<br>"; 
					$tmp1->char->health = $tmp1->char->health - $docastdmg; 
					$p->char->mana = $p->char->mana - $manacon;
                  }
                }
              }
					
            }else{
				if ($fight_data->type != "duel" && $fight_data->type != "duelnpc"  && $maxrep > 1) { 
					if($docast <= 0.5 * $p->hitchance_magic){
						$manacon=round($manacon / 1.7);
						$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Deadly Sight but the opponents were able to escape ".$p->herhis." line of sight. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
					}else{
						$manacon=round($manacon / 2.7);
						$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Deadly Sight but the opponents are not on focus of ".$p->herhis." eyes. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
					}
				}else{
					if($docast <= 0.5*$p->hitchance_magic){
						$manacon=round($manacon / 1.3);
						$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Deadly Sight but ".$pv->char->username." was able to escape ".$p->herhis." line of sight. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
					}else{
						$manacon=round($manacon / 1.7);
						$fight_report .= "<span class=\"rf\">".$p->char->username." cast Force Deadly Sight but ".$pv->char->username." was not on focus of ".$p->herhis." eyes. (-".$manacon.")<br>"; $p->char->mana = $p->char->mana - $manacon;
					}
				}
			}
        }else{
          $fight_report .= "<span class=\"rf\">".$p->char->username." has not enough Mana to cast Force Deadly Sight. (".$manacon." needed)<br>";
        }
      }

// force team energize
      elseif( $p->skills->ftnrg > 0 && $forcernd == 23 ) {
	  $faktor=1.6;
	    $last = $p->playerslast_c;
		if($p->playerslast_c == 23){ $p->playerslast_c = 23; $p->playerslast_cc++; 
		$faktor=$faktor-round(($p->playerslast_cc/($p->skills->level*1.7)*($p->skills->ftnrg/9)),1); 
		if($faktor < 0 ) { $faktor = 0.1; } 
		}else { $p->playerslast_c = 23;  $p->playerslast_cc = 0;}
        $p->playerscast[$forcernd]++;
		if($comments)  $fight_report .= "<font color=\"goldenrod\">Cast Nr. ".$p->playerscast[$forcernd]." mit Faktor ".$faktor." bei ".$p->playerslast_cc."ter Wdh. (Lastcast: ".$last.") <br></font>";
		$docastdmg = rand(($p->skills->ftnrg/4), ($p->skills->ftnrg*1.6));
		$docastdmg = round($docastdmg + ($docastdmg * $p->itlbonus / 100));
		$manacon = round(count($team_p)*($docastdmg / 5))+$p->manaaddcon;
        if ($manacon <= $p->char->mana) {
          if ( $docast <= 0.8*$p->hitchance_magic) {
		    if ($fight_data->type != "duel" && $fight_data->type != "duelnpc" ) {
            $fight_report .= "<span class=\"o\">".$p->char->username." cast Force Team Energize, recharging<br>";
            $minrep = 2;
            $maxrep = count($team_p);
            if ($maxrep > 4) { $maxrep = 5; } if ($maxrep == 1) { $minrep = 1; }
            $tmprepeats = rand($minrep, $maxrep);
            $tmpteam = $team_p;
            for ($th = 1; $th <= $tmprepeats; $th++) {
              $tmp2 = array_rand($tmpteam);
              $tmp1 = $tmpteam[$tmp2];
              unset($tmpteam[$tmp2]);
              $docastdmg = rand(($p->skills->ftnrg/4), ($p->skills->ftnrg*1.6)); 
			  $manacon = round($docastdmg / 4);
              $fight_report .= "+".$docastdmg." points of mana of ".$tmp1->char->username." (-".$manacon.").<br>"; 
				$tmp1->char->mana = $tmp1->char->mana + $docastdmg;
				if ($tmp1->char->mana > $tmp1->maxmana) { $tmp1->char->mana = $tmp1->maxmana; }
				$tmp1->skills->spi = $tmp1->skills->spi + round($docastdmg/3);
				$tmp1->skills->itl = $tmp1->skills->itl + round($docastdmg/4);
				$p->char->mana = $p->char->mana - $manacon;
            	}
			}else{
				$docastdmg = rand(($p->skills->ftnrg/4), ($p->skills->ftnrg*1.6)); 
				$manacon = round($docastdmg / 4);
                $fight_report .= "<span class=\"o\">".$p->char->username." cast Force Team Energize, recharging ".$p->herhimself." +".$docastdmg." points of mana.(-".$manacon.")<br>";
			 	$p->char->mana = $p->char->mana + $docastdmg;
                if ($p->char->mana > $p->maxmana) { $p->char->mana = $p->maxmana; }				
				$p->skills->spi = $p->skills->spi + round($docastdmg/3);
				$p->skills->itl = $p->skills->itl + round($docastdmg/4);
				$p->char->mana = $p->char->mana - $manacon;
			}
          }else{ $fight_report .= "<span class=\"of\">".$p->char->username." tried to cast Force Team Energize, but ".$pv->char->username." interrupted  ".$p->herhis." try to recharge";  if ($fight_data->type != "duel" && $fight_data->type != "duelnpc" ){ $fight_report .= " the team.<br>";}else{$fight_report .= ".<br>";} $p->char->mana = $p->char->mana - $manacon; }
        }else{
          $fight_report .= "<span class=\"of\">".$p->char->username." has not enough Mana to cast Force Team Energize. (".$manacon." needed)<br>";
        }
      }
	}
    $fight_report .= "</span>";   
	}
}
        // check for dead or fleeing members
        foreach($team_pv as $key => $value)
        {
            if ($value->char->health <= 0)
            {
                $winnerid = $p->char->userid;
	 
                $fight_report .= "<span class=\"bl\">".$value->char->username." has been knocked out. (".$value->teamPosition.",".$value->teamid.")<br> ";
                
				if($value->npc == "y" && $value->char->userid == 2)
				{
					$p->killedRat += 1;
				}
				elseif($value->npc == "y" && $value->char->userid == 1)
				{
					$p->killedGiantRat += 1;
				}
                elseif($value->npc == "y" && $value->char->userid == 8) {
                    $p->killedDroid += 1;
                }
                elseif($value->npc == "y" && $value->char->userid == 9) {
                    $p->killedReek += 1;
                }
                elseif($value->npc == "y" && $value->char->userid == 11) {
                    $p->killedLeft += 1;
                }
                elseif($value->npc == "y" && $value->char->userid == 12) {
                    $p->killedRight += 1;
                }
				
				if ($i % 2 != 0) 
                { 
                    $deadtm_2=$team1;
                    foreach($deadtm_2 as $key2 => $value2)
                    {
                        if($value2!=$value){ $value2->rsr = $value; }
                        //  $fight_report .= "<span class=\"bl\">".${"username_$value2"}." have set rsr-flag for ".$value." (Team 2)<br>";
                    }
                    unset($team1[array_search($value, $team1)]);   
                }
                else
                { 
                    $deadtm_1=$team0;
                    foreach($deadtm_1 as $key2 => $value2) 
                    {
                        if($value2!=$value){ $value2->rsr = $value; }
                        //$fight_report .= "<span class=\"bl\">".${"username_$value2"}." have set rsr-flag for ".$value." (Team 1)<br>";
                    }
                    unset($team0[array_search($value, $team0)]);   
                }

                if ($fight_data->type == "house" || $fight_data->type == "npc" || $fight_data->type == "duelnpc" || $fight_data->type == "coopnpc") 
                {
                    #$timecheat++; if ($timecheat > 59) { $timecheat = 60; }
                    if (!$value->npc == "y") 
                    {
                        #$tmpuserid = $value->char->userid;
                        #$query = mysql_query("SELECT * FROM jedi_user_clonebackups WHERE userid = $tmpuserid");
                        #list($userid) = mysql_fetch_row($query);
                        #if ($userid)
                        #{
                            #mysql_query("INSERT INTO jedi_user_skills SELECT * FROM jedi_user_clonebackups WHERE userid = $tmpuserid");
                            #mysql_query("DELETE FROM jedi_user_clonebackups WHERE userid = $tmpuserid");
                            #$text = "<span class=\"r\">".${"username_$p"}." has killed ". ${"username_$value"}."</span>";
                            #$time = time() - 60 + $timecheat;
                            #mysql_query("INSERT INTO jedi_events (eventtype, eventdate, eventtext) VALUES (11, $time, '$text')");
                            //  $credits = rand((getcash(${"userid_$p_v"}) * 5 / 100), (getcash(${"userid_$p_v"}) * 10 / 100));
                            //deccash(${"userid_$p_v"}, $credits);
                            // inccash(${"userid_$p"}, $credits);
                            // $fight_report .= ${"username_$p"}." took some credits ($credits).<br>";
                        #}
                        #else 
                        #{
                            // mysql_query("UPDATE jedi_user_chars SET status = 'dead' WHERE userid = $tmpuserid");
                            #$text = "<span class=\"r\"><b>".${"username_$p"}." has permanently killed ".${"username_$value"}.".</b></span>";
                            #$time = time() - 60 + $timecheat;
                            #mysql_query("INSERT INTO jedi_events (eventtype, eventdate, eventtext) VALUES (11, $time, '$text')");
                            //  $credits = getcash(${"userid_$p_v"});
                            // deccash(${"userid_$p_v"}, $credits);
                            // inccash(${"userid_$p"}, $credits);
                            // $fight_report .= ${"username_$p"}." took all his credits ($credits).<br>";
                        #}
                    }
                }
    }elseif($value->char->heroic !=99 && ($value->char->health <= $value->char->heroic)) {
	  $winnerid = $p->char->userid;
      $fight_report .= "<span class=\"bl\">".$value->char->username." fled.<br>";
      if ($i % 2 != 0) { unset($team1[array_search($value, $team1)]); }
      else { unset($team0[array_search($value, $team0)]); }
			}
    //$fight_report .= "health: ".${"health_$p"}."-".${"health_$p_v"}." Mana: ".${"mana_$p"}."-".${"mana_$p_v"}."<br>";
	//$fight_report .= "h: ".${"health_$p"}." t:".${"health_$p_v"}." Mana: ".${"mana_$p"}."-".${"mana_$p_v"}."<br>";
    }
    

            //ENde while-schleife kampf an sich
        }
        $fight_report .= "<span class=\"bl\"><br><br>";

        for ($iteamcnt = 0; $iteamcnt < 2; $iteamcnt++)
        {
            if ($iteamcnt == 0)
            {
                $iteam_ = $team0;
                $iteam = $team_1;
                $iteamlevelown = $team1_level;
                $iteamlevelthem = $team0_level; 
                $iteamlmt = $team0mittel;  
                $fw = $fighters1; 
            }
            else
            {
                $iteam_ = $team1;
                $iteam = $team_0;
                $iteamlevelown = $team0_level;
                $iteamlevelthem = $team1_level;
                $iteamlmt  = $team1mittel;
                $fw = $fighters0;
            }
            
            if (empty($iteam_))
            {
                if (count($iteam) == 1)
                {
          
                    $fight_report .= "$fw has won the fight.<br>";
                    $winnerteam = $fw." has";
          
                }
                else
                {
                    $fight_report .= "$fw have won the fight.<br>";
                    $winnerteam = $fw." have";
                }

                //Gewinnerteam durchlaufen
                foreach($iteam as $key => $value)
                {
					$win = $value->teamid;
                    if($win == 0) 
                    {
                        $win = "team_0";
                        $loose = "team_1";
                    }
                    else 
                    {
                        $win = "team_1";
                        $loose = "team_0";
                    }
                    if($fight_data->type == "coop" OR $fight_data->type == "duel")
					{
						if(count($iteam) == 1)
						{
							$headline = $$win[0]->char->username." (".$$win[0]->skills->level.") has beaten ".$$loose[0]->char->username." (".$$loose[0]->skills->level.")";
						}
						else
						{
							$headline = $$win[0]->char->username." (".$$win[0]->skills->level.") and ".$$win[1]->char->username." (".$$win[1]->skills->level.") have beaten ".$$loose[0]->char->username." (".$$loose[0]->skills->level.") and ".$$loose[1]->char->username." (".$$loose[1]->skills->level.")";
						}
					}
					else
					{
						$headline = "npc-fight";
					}
					if($value->npc == "y")
					{
						$fight_report .= "rem. health: ".$value->char->health."<br>";
					}
                    //Event
                    if($fight_data->type2 == "event")
                    {
						$win = $value->teamid;
                        if($win == 0) $loose = "team_1";
                        else $loose = "team_0";
                        $event = $this->JediEventsSingleRanking->get($value->char->userid);
                        $event_enemy = $this->JediEventsSingleRanking->get($$loose[0]->userid);
						
                        if($event_enemy->points > 0)
                        {
                            //Erwartungswert attacker
                            $Ea = 1 / (1 + (pow(10,($event_enemy->points - $event->points) / 200)));
                            $fight_report .= "Ea ".$Ea."<br>";
                            //Erwartungswert defender
                            $Eb = 1 - $Ea;
                            $fight_report .= "Eb ".$Eb."<br>";

                            if ($win == 0) {
                                $event->points = $event->points + 20 * (1 - $Ea);
                                $event_enemy->points = $event_enemy->points + 20 * (0 - $Eb);
                            }
                            else {
                                $event->points = $event->points + 20 * (1 - $Eb);
                                $event_enemy->points = $event_enemy->points + 20 * (0 - $Ea);
                            }
                            $this->JediEventsSingleRanking->save($event);
                            $this->JediEventsSingleRanking->save($event_enemy);
                        }
                    }
                    if($value->npc == "n")
                    {
                        $neg_1 = "0";
                        $xp_bonus_1_1 = "";
                        $xp_bonus_1_2 = "";
                        $xp_bonus_1_3 = "";
						$xp_bonus_1_4 = "";
                        $bonus_1_1 = "";
						$bonus_1_2 = "";
						$bonus_1_3 = "";
						$bonus_1_4 = "";
						$bonus_1 = "0";
                        $bonus_11 = "0";
						$bonus_kill = null;
                        
                        $userid = $value->char->userid;
                        //Usermodel holen
                        $user = $this->JediUserChars->get($userid);
                        $user->skills = $this->JediUserSkills->get($userid);

                        if($this->JediUserStatistics->find()->where(['userid' => $userid])->first() == null)
                        {
                            $statistics = $this->JediUserStatistics->newEntity();
                        }
                        else
                        {
                            $statistics = $this->JediUserStatistics->get($userid);
                        }
                        $statistics->userid = $userid;

                        //energywerte setzen
                        if($fight_data->type == "duel" && $value->npc == "n")
                        {
                            $user->energy -= 1;
                        }
                        //Wenn kampf gegen ratte
                        elseif(($fight_data->type == "duelnpc" OR $fight_data->type =="coopnpc") && $npcid == "2")
                        {
                            $user->energy -= 1;
                        }
                        elseif($value->npc == "n")
                        {
                            $user->energy -= 2;
                        }

                        //		zustand freir�umen
                        if($fight_data->type == "duel" || $fight_data->type == "coop")
                        {
                            $user->actionid = 0;
                            $user->targetid = 0;
                            $user->targettime = 0;
                            $user->lastaccess = time();
                            
                            // put win stats to db
                            $statistics->totalwins += 1;
                            $statistics->arenawins += 1;
							$fight_rep_type = "a";
                        }
                        elseif($fight_data->type == "duelnpc" || $fight_data->type == "coopnpc")
                        {
                            $fight_rep_type = "l";
                            // put win stats to db
                            $statistics->totalwins += 1;
                            
							if($npcid == 2)
							{
								$statistics->killedRat += $value->killedRat;
                                $statistics->npcwins += 1;
							}
							elseif($npcid == 1)
							{
								$statistics->killedGiantRat += $value->killedGiantRat;
                                $statistics->raidwins += 1;
							}
                            elseif($npcid == 8) {
                                $statistics->killedDroid += $value->killedDroid;
                                $statistics->npcwins += 1;
                            }
                            elseif($npcid == 9) {
                                $statistics->killedReek += $value->killedReek;
                                $statistics->raidwins += 1;
                            }
                            elseif($npcid == 11) {
                                $statistics->killedLeft += 1;
                                $statistics->npcwins += 1;
                            }
							elseif($npcid == 12) {
                                $statistics->killedRight += 1;
                                $statistics->npcwins += 1;
                            }
                        }

                        if (count($iteam) >= 1)
                        { 
                            if ($fight_data->type == "duelnpc" || $fight_data->type == "coopnpc")
                            {
                                $bonus_11 = $iteamlmt * 0.27; 
                            }
                            else
                            {
                                $bonus_11 = $iteamlmt * 0.66; 
                            }
                        }

                        if ($fight_data->type == "duel" )
                        {
                            $maxstr = $iteamlevelthem + round($iteamlevelthem * 27 / 100); 
                        }

                        $bonus_1 = (($value->skills->level / 1.55 + $bonus_11 / 4) * ($iteamlmt * 0.44) + 25);
                        
                        if ($value->char->health < 0)
                        {
                            $neg_1 = $value->char->health * 2; 
                        }
                        if($fight_data->type == "duelnpc" OR $fight_data->type == "coopnpc")
                        {
                            if ($npcid == 2)
                            { 
                                $bonus_1 = $bonus_1 * 0.86;

								$bonus_kill = round($value->skills->level * ($value->killedRat) / 5);
                            }
							elseif ($npcid == 1)
							{
								$bonus_1 = $bonus_1 * 1.25;
								$bonus_kill = round($value->skills->level * ($value->killedGiantRat) / 2);
							}
                            elseif ($npcid == 11) {
                                $bonus_1 = $bonus_1 * 0.8;
                            }
                            elseif ($npcid == 12) {
                                $bonus_1 = $bonus_1 * 1.2;
                            }							
                        }
                        $xpcalc = 20 + $value->skills->level + $bonus_1 + $neg_1 + $bonus_kill;
                        $xpcalc2 = rand($xpcalc - $value->skills->level, $xpcalc);
                
                        if ($value->tempBonus["tmpxp"]) 
                        {
                            $xpcalc2 = $xpcalc2 + $value->tempBonus["tmpxp"]; $xp_bonus_1_1 = "(+".$value->tempBonus["tmpxp"].")"; 
                        }
                        if ($value->tempBonus["tmppxp"] && $xpcalc2 > 0) 
                        {
                            $xpcalc2 = $xpcalc2 + $xpcalc2 * $value->tempBonus["tmppxp"] / 100;
                            $xp_bonus_1_2 = "(+".round($xpcalc2 * $value->tempBonus["tmppxp"] / 100).")"; 
                        }
                        if ($value->tempBonus["tmplxp"]) 
                        {
                            $xpcalc2 = $xpcalc2 + $value->tempBonus["tmplxp"] * $value->skills->level;
                            $xp_bonus_1_3 = "(+".$value->tempBonus["tmplxp"] * $value->skills->level.")"; 
                        }
                        if (($value->skills->level >= 15) && ($value->skills->level > $fight_data->maxstr) && $xpcalc2 >= 0 && $fight_data->maxstr > 0) 
                        {
                            $xpcalc2 = ($xpcalc2 * 5 / 100);
                        }

                        $xp = round($xpcalc2);

                        if ($comments)  $fight_report .= "Aktionen: ".($value->playerfc/$value->playerat * 100) ."% /  ". ($value->playerhc/$value->playerat * 100) ."% <br>";
                        
						if($fight_data->type2 != "event")
						{							
							$fight_report .= $value->char->username." (".$value->char->health.") gained: ".$xp."xp. ".$xp_bonus_1_1." ".$xp_bonus_1_2." ".$xp_bonus_1_3."<br>";
                        }
						else
						{
							$fight_report .= $value->char->username." (".$value->char->health.")<br>";
						}
                        if ($value->npc == "n")
                        {
                            $user->skills->xp += $xp; 
                        }
                        else
                        { 
                            #incxp_npc(${"userid_$value"}, $xp); 
                        }

                        if ($fight_data->bet > 0)
                        {
                            $betgain = round($fight_data->bet * $players / count($iteam));
                            $user->cash += $betgain;
                        }

                        if ($fight_data->type == "house" || $fight_data->type == "npc" || $fight_data->type == "duelnpc" || $fight_data->type == "coopnpc")
                        {
                            #$health = $value->char->health;
                            $mana = $value->char->mana;

                            if ($value->npc == "y") 
                            {
                                
                            } 
                            else 
                            {
                                #$user->health = $health;
                                $user->mana = $mana;
                                /*
                                if($health <= 0)
                                {
                                    $user->health = 0;
                                    //ab ins bett
                                    $sleeptime = 60 * 60 ;
                                    $timeleft = $sleeptime + time();

                                    $base = $value->char->base;

                                    if($base != 0)
                                    {
                                        $user->actionid = 1;
                                        $user->targetid = $base;
                                        $user->targettime = $timeleft;
                                        #mysql_query("UPDATE jedi_city_apartments SET sleep = 'yes', sleepingsince = $now, sleepingfor = '$sleeptime' WHERE houseid = $base");
                                    }
                    
                                }
                            	*/
                            }     
                        }
                        else
                        {
                            $mana = $value->char->mana;

                            if ($value->npc == "y")
                            {
                            //$tbl = "jedi_npc_chars";
                            } 
                            else 
                            {
                                $user->mana = $mana;
                            }
                        }
                
                        if ($value->char->padaid == $value->char->userid) 
                        {
                            $meister = $this->JediUserSkills->get($value->char->meisterid);
                            $xp = $xp * 5 / 100;
                            if ($value->npc == "n") 
                            {
                                $meister->xp += $xp; 
                                $this->JediUserSkills->save($meister);
                            }
                        }
                        elseif ($value->char->meisterid == $value->char->userid)
                        {
                            $pada = $this->JediUserSkills->get($value->char->padaid);
                            $xp = $xp * 3 / 100;
                            if ($value->npc == "n") 
                            {
                                $pada->xp += $xp;
                                $this->JediUserSkills->save($pada);
                            }
                        }

                        if($value->npc == "n" && $fight_data->type2 != "event")
                        {
                            //Die ermittelten Werte für $user in die Datenbank schreiben
                            $this->JediUserChars->save($user);
                            $this->JediUserSkills->save($user->skills);
                            $this->JediUserStatistics->save($statistics);

                            if(($fight_data->type == "duelnpc" OR $fight_data->type == "coopnpc") AND $fight_data->type2 != "quest")
                            {
                                //Jeder vom Gewinnerteam hat die chance auf ein loot
                                $looting[$value->char->userid] = $this->looting($fight_data->type, $fight_data->type2, $npcid, $value->char->tmpcast, $value->char->userid, $value->char->username);
                            
                                //Fleisch verkaufen
                                if($npcid == 2)
                                {
                                    $cr = rand(20,40);
                                    if($fight_data->type == "coopnpc") $cr = floor($cr * rand(15,25) / 10);
                                }
                                else
                                {
                                    $cr = rand(50,75);
                                    if($fight_data->type == "coopnpc") $cr = floor($cr * rand(15,25) / 10);
                                }
                                $user->cash += $cr;
                                $this->JediUserChars->save($user);
                            }
                        }
                    }
                }
                if ($fight_data->bet > 0) 
                {
                    if (count($iteam) > 1) 
                    {
                        $fight_report .= "Every teammember gained $betgain credits for winning the bet.<br>"; 
                    }
                    else
                    {
                        $fight_report .= "You gained $betgain credits for winning the bet.<br>"; 
                    }
                }

                $fight_report .= "<br>";
            }
            else
            {
                if (count($iteam) == 1)
                {
                    $fight_report .= "$fw has lost the fight.<br>";
                    $looserteam = $fw;
                }
                else
                {
                    $fight_report .= "$fw have lost the fight.<br>";
                    $looserteam = $fw;
                }
                
                //Verliererteam durchgehen
                foreach($iteam as $key => $value) 
                {
                    $neg_1 = "0";
                    $xp_bonus_1_1 = "";
                    $xp_bonus_1_2 = "";
                    $xp_bonus_1_3 = "";
                    $bonus_1 = "0";
                    $bonus_11 = "0";
                    $userid = $value->char->userid;

                    if($value->npc == "n")
                    {
                        //Usermodel holen
                        $user = $this->JediUserChars->get($userid);
                        $user->skills = $this->JediUserSkills->get($userid);

                        if($this->JediUserStatistics->find()->where(['userid' => $userid])->first() == null)
                        {
                            $statistics = $this->JediUserStatistics->newEntity();
                        }
                        else
                        {
                            $statistics = $this->JediUserStatistics->get($userid);
                        }
                        $statistics->userid = $userid;
                    }

                    //energywerte setzen
                    if($fight_data->type == "duel" && $value->npc == "n")
                    {
                        $user->energy -= 1;
                    }
                    elseif(($fight_data->type == "duelnpc" OR $fight_data->type == "coopnpc") && $npcid == "2" && $value->npc == "n")
                    {
                        $user->energy -= 1;
                    }
                    elseif($value->npc == "n")
                    {
                        $user->energy -= 2;
                    }
                    //		zustand freir�umen
                    if(($fight_data->type == "duel" || $fight_data->type == "coop") && $value->npc == "n")
                    {
                        $user->actionid = 0;
                        $user->targetid = 0;
                        $user->targettime = 0;
                        $user->lastaccess = time();

                        // put loose stats to db
                        $statistics->totallosts += 1;
                        $statistics->arenalosts += 1;
						$fight_rep_type = "a";
                    }
                    elseif(($fight_data->type == "duelnpc" || $fight_data->type == "coopnpc") && $value->npc == "n")
                    {
                        $statistics->totallosts += 1;
						$fight_rep_type = "l";

                        // put loose stats to db
						if($npcid == 2)
						{
							$statistics->killedRat += $value->killedRat;
                            $statistics->npclosts += 1;
						}
                        elseif($npcid == 1)
                        {
                            $statistics->killedGiantRat += $value->killedGiantRat;
                            $statistics->raidlosts += 1;
                        }
                        elseif($npcid == 8) {
                            $statistics->killedDroid += $value->killedDroid;
                            $statistics->npclosts += 1;
                        }
                        elseif($npcid == 9) {
                            $statistics->killedReek += $value->killedReek;
                            $statistics->raidlosts += 1;
                        }
                        elseif($npcid == 11 OR $npcid == 12) {
                            $statistics->npclosts += 1;
                        }
						
                        
                    }
                    $bonus_1 = (($iteamlevelown / 2.3) * ($iteamlmt / 8.8));

                    if ($value->char->health < 0) 
                    {
                        $neg_1 = $value->char->health; 
                    }
                    $xpcalc = 33 + $value->skills->level + $bonus_1 + $neg_1;
                    $xpcalc2 = rand($xpcalc - $value->skills->level, $xpcalc);
                    if ($value->xpbonus) 
                    {
                        $xpcalc2 = $xpcalc2 + $value->xpbonus; 
                        $xp_bonus_1_1 = "(+".$value->xpbonus.")"; 
                    }
                    if ($value->pxpbonus && $xpcalc2 > 0) 
                    {
                        $xpcalc2 = $xpcalc2 + $xpcalc2 * $value->pxpbonus / 100;
                        $xp_bonus_1_2 = "(+".round($xpcalc2 * $value->pxpbonus / 100).")"; 
                    }
                    if ($value->lxpbonus) 
                    {
                        $xpcalc2 = $xpcalc2 + $value->lxpbonus * $value->skills->level; 
                        $xp_bonus_1_3 = "(+".$value->lxpbonus * $value->skills->level.")"; 
                    }
                
                    if (($value->skills->level >= 15) && ($value->skills->level > $fight_data->maxstr) && $xpcalc2 >= 0 && $fight_data->maxstr > 0) 
                    {
                        $xpcalc2 = ($xpcalc2 * 5 / 100);
                    }
            
                    $xp = round($xpcalc2);

                    #if($comments)   $fight_report .="Aktionen: ".( $value->playerfc/$value->playerat * 100)."% /  ".( $value->playerhc/$value->playerat * 100)."% <br>";
                    
                    if($fight_data->type2 != "event")
					{							
						$fight_report .= $value->char->username." (".$value->char->health.") gained: ".$xp."xp. ".$xp_bonus_1_1." ".$xp_bonus_1_2." ".$xp_bonus_1_3."<br>";
					}
					else
					{
						$fight_report .= $value->char->username." (".$value->char->health.")<br>";
					}
					
                    if ($value->npc == "n") 
                    { 
                        $user->skills->xp += $xp;
                    }
                    else
                    { 
                        #incxp_npc(${"userid_$value"}, $xp); 
                    }
                    if ($fight_data->type == "house" || $fight_data->type == "npc" || $fight_data->type == "duelnpc" || $fight_data->type == "coopnpc")
                    {
                        $health = $value->char->health;
                        $mana = $value->char->mana;

                        if ($value->npc == "y")
                        {
                             //$tbl = "jedi_npc_chars";
                        } 
                        else 
                        {
                            $user->health = $health;
                            $user->mana = $mana;
                            
							
                            if($health <= 0)
                            {
                                $user->health = 0;
								/*
                                //ab ins bett
                                $sleeptime = 60 * 60 ;
                                $timeleft = $sleeptime + time();

                                $base = $value->char->base;

                                if($base != 0)
                                {
                                    $user->actionid = 1;
                                    $user->targetid = $base;
                                    $user->targettime = $timeleft;
                                    #mysql_query("UPDATE jedi_city_apartments SET sleep = 'yes', sleepingsince = $now, sleepingfor = '$sleeptime' WHERE houseid = $base");
									
                                }*/
                            }  
                        }
                    }
                    else
                    {
                        $mana = $value->char->mana;
                        if ($value->npc == "y") 
                        {
                             //$tbl = "jedi_npc_chars";
                        } 
                        else 
                        {
                            $user->mana = $mana;
                        }
                    }

                    if ($value->char->padaid == $value->char->userid) 
                    {
                        $meister = $this->JediUserSkills->get($value->char->meisterid);
                        $xp = $xp * 5 / 100;
                        if ($value->npc == "n") 
                        {
                            $meister->xp += $xp; 
                            $this->JediUserSkills->save($meister);
                        }
                    }
                    elseif ($value->char->meisterid == $value->char->userid)
                    {
                        $pada = $this->JediUserSkills->get($value->char->padaid);
                        $xp = $xp * 3 / 100;
                        if ($value->npc == "n") 
                        {
                            $pada->xp += $xp;
                            $this->JediUserSkills->save($pada);
                        }
                    }

                    if($value->npc == "n" && $fight_data->type2 != "event")
                    {
                        //Die ermittelten Werte für $user in die Datenbank schreiben
                        $this->JediUserChars->save($user);
                        $this->JediUserSkills->save($user->skills);
                        $this->JediUserStatistics->save($statistics);
                    }
                }
                $fight_report .= "<br>";	
            }
        }
        //lootings für den Kampfbericht
        if(isset($looting))
        {
            foreach($looting as $key => $loot)
            {
                if(isset($loot[1]))
                {                
                    $fight_report .= $loot[0];

                    if($fight_data->type2 != "raid")
                    {
                        $stat_line_1 = explode(",",$loot[1]->stat1);
                        $stat_line_2 = explode(",",$loot[1]->stat2);
                        $stat_line_3 = explode(",",$loot[1]->stat3);
                        $stat_line_4 = explode(",",$loot[1]->stat4);
                        $stat_line_5 = explode(",",$loot[1]->stat5);

                        $stat_line_1 = implode(" ",$stat_line_1);
                        $stat_line_2 = implode(" ",$stat_line_2);
                        $stat_line_3 = implode(" ",$stat_line_3);
                        $stat_line_4 = implode(" ",$stat_line_4);
                        $stat_line_5 = implode(" ",$stat_line_5);
                        $type = $loot[1]->type;   
                        $img = $loot[1]->img;

                        $fight_report .= "folgendes Item nimmst du in dein Inventar auf<br>
                        <div class='card' style='width: 12rem;'>
                        <img src= '.\images\items\\$type\\$img.jpg' class='card-img-top'>".              
                        #$this->getController()->Html->image($loot[1]->img.".jpg", ['pathPrefix' => "webroot/img/items/".$loot[1]->type."/", 'class' => 'card-img-top'])."
                            "<div class='card-body'>
                            <h5 class='card-title'>".$loot[1]->name."</h5>
                            <p class='card-text'>
                                    qlvl: ".$loot[1]->qlvl."<br>";
                                    if($loot[1]->reql) $fight_report .= "req. level: ".$loot[1]->reql."<br>";
                                    if($loot[1]->reqs != 0) $fight_report .= "req. skill: ".$loot[1]->reqs."<br>";
                                    if($loot[1]->mindmg != 0) $fight_report .= "damage: ".$loot[1]->mindmg. " - ".$loot[1]->maxdmg."<br>";
                                    if($loot[1]->stat1) $fight_report .= $stat_line_1."<br>";
                                    if($loot[1]->stat2) $fight_report .= $stat_line_2."<br>";
                                    if($loot[1]->stat3) $fight_report .= $stat_line_3."<br>";
                                    if($loot[1]->stat4) $fight_report .= $stat_line_4."<br>";
                                    if($loot[1]->stat5) $fight_report .= $stat_line_5."<br>";
                                    $fight_report .= "value: ".$loot[1]->price."
                            </p>
                            </div>
                        </div>";
                    }
                }
            }    
        }

        //wenigstens das Fleisch wird verkauft
        if(isset($cr))
        {
            $fight_report .= "Du verkaufst die Überreste und bekommst $cr Credits";
        }

        $md5 = md5(uniqid(rand()));

        if($fight_data->type2 == "" OR $fight_data->type2 == "quest")
        {
            $db_fight_report = $this->JediFightReports->newEntity();
            $db_fight_report->zeit = time();
            $db_fight_report->md5 = $md5;
            $db_fight_report->report = $fight_report;
			$db_fight_report->headline = $headline;
			$db_fight_report->type = $fight_rep_type;
            $this->JediFightReports->save($db_fight_report);
        }
        elseif($fight_data->type2 == "event")
        {
            $db_fight_report = $this->JediEventsSingleFightReports->newEntity();
            $db_fight_report->zeit = time();
            $db_fight_report->md5 = $md5;
            $db_fight_report->report = $fight_report;
            $db_fight_report->attacker = $team_0[0]->char->userid;
            $db_fight_report->defender = $team_1[0]->char->userid;
            $db_fight_report->headline = $headline;
            $this->JediEventsSingleFightReports->save($db_fight_report);
        }        

        $query = $this->JediFightLocks->query();
        $query->delete()
            ->where(['fightid' => $id])
            ->execute();

        foreach($team_0 as $key => $value) 
        {
            $userid = $value->char->userid;
            if ($value->npc == "n" && $fight_data->type2 != "event")
            {
                $user = $this->JediUserChars->get($userid);
                $user->lastfightid = $md5;
                $this->JediUserChars->save($user);
            }
        }
        foreach($team_1 as $key => $value)
        {
            $userid = $value->char->userid;
            if ($value->npc == "n" && $fight_data->type2 != "event")
            {
                $user = $this->JediUserChars->get($userid);
                $user->lastfightid = $md5;
                $this->JediUserChars->save($user);
            }
        }
        /*
        if ($fight_data->type == "duelnpc" || $fight_data->type == "coopnpc" ) 
        {
            if ($npcid == "2")
            {
                $this->getController()->redirect(['controller' => 'city', 'action' => 'layer', 'view']);
            }
			if ($npcid == 8 || $npcid == 9)
            {
                $this->getController()->redirect(['controller' => 'city', 'action' => 'layer2', 'view']);
            }
            elseif ($npcid == 1 || $npcid == "4" || $npcid == "5" || $npcid == "6" || $npcid == "7") 
            {
                $this->getController()->redirect(['controller' => 'city', 'action' => 'layer', 'view']);
            }
        }
        */
        if($fight_data->type2 == "quest") {
            return [$win, $loose, $fight_report];
        }
        if(isset($loot))
        {
            return $loot;
        }
        
   
    //Ende Funktion fight()
    }

    public function looting ($type, $type2, $npcid, $tmpcast, $userid, $username)
    {
        if ($type == "coopnpc")
        {
            $basis = 15;
        }
        else
        {
            $basis = 10;
        }
    
        if ($tmpcast >= 15)
        {
            $tmpcast = 15;
        }
        else
        {
            $tmpcast = $tmpcast;
        }
        
        $chance = $basis + $tmpcast;
    
        $itemchance = rand(1,100); // 1,100 wären bei basis 10 eine Chance von 10%
		if($npcid == 8) $itemchance = 1;
        //Hier der Looterfolg
        if($itemchance <= $chance)
        {
            if($type == "coopnpc" && $npcid == 2)
            {
                $lootitem = "rats";
            }
            elseif($type == "duelnpc" && $npcid == 2)
            {
                $lootitem = "rat";
            }
			elseif($npcid == 1 OR $npcid == 11)
			{
				$lootitem = "giant-rat";
			}
			elseif($npcid == 8 OR $npcid == 12)
			{
				$lootitem = "droid";
			}
			elseif($npcid == 9)
			{
				$lootitem = "reek";
			}
            elseif($npcid == 4 || $npcid == 5 || $npcid == 6 || $npcid == 7)
            {
                $lootitem = "ranc".$npcid;
            }

            $items = $this->Loot->loot($lootitem, $userid, $type2);
            $itemchance2 = ceil(($chance - $basis) * 100 / 15);
            
            if($chance > $basis)
            {
                $fight_report = "<b>$username </b>found a Item through using Force Seeing! ($itemchance2 %)<br>";
            }
            else
            {
                $fight_report = "Wow! A Item!<b> $username </b>might find more using Force Seeing!<br>";
            }
            return array($fight_report, $items);
        }
        else
        {
            return false;
        }
    }
}
function add_s ($name)
{
    $a = "s";    
    $last = substr("$name", -1);
    //echo $last;    
    if ($last == "s") {
        
        return;
        }else{
        return $a;} 
}
function routinenammount($manacon,$level_p="-",$level_p_v="-") {
    $mana = $manacon*4;
    
    if($mana <= '33') {
    $amount= 1; 
    }elseif($mana <= '66') {
    $amount= 2; 
    }elseif($mana <= '99') {
    $amount= 3; 
    }elseif($mana > '99') {
    $amount= 4; 
    }
    
    if ($level_p/$level_p_v < 0.61){
     $amount=$amount-3;
     }elseif($level_p/$level_p_v < 0.73){
     $amount=$amount-2;
     }elseif($level_p/$level_p_v < 0.85){
     $amount=$amount-1;
     }
    // if($amount < 0) $amount = 0;
    
    return $amount;
    }

function getstateopponent($playerstate_p_v,$p_v,$sex,$p,$manacon="-",$force="-") {
    if($force=="-") $force="the force";
    if($force=="Force Pull" || $force=="Force Push" || $force=="Force Saber Throw"){ $color="bf"; }else{ $color="rf";}
  switch ($playerstate_p_v) {
      case "":	return "<span class=\"gn\">".$p." found ".$p_v." while preparing a cast of ".$force.".</span><br>";
    break;
     case "invisible":	return "<span class=\"".$color."\">".$p." successfully cast ".$force." against ".$p_v.", but the cast was off target. (-".$manacon.")</span><br>";
    break;
    case "projection":	return "<span class=\"".$color."\">".$p." successfully cast ".$force." against ".$p_v.", but ".$p." has just hit a projection of ".$sex.". (-".$manacon.")</span><br>";
    break;
    }
}
function getstateself($playerstate_p,$p,$sex,$p_v,$manacon="-",$force="-") {
    if($force=="-") $force="the force";
    if($force=="Force Pull" || $force=="Force Push" || $force=="Force Saber Throw"){ $color="bf"; }else{ $color="rf";}
  switch ($playerstate_p) {
    case "":	return "<span class=\"gn\">".$p.add_s($p)." condition regenerated.</span><br>";
    break;
     case "confused": return "<span class=\"".$color."\">".$p." can not aim ".$force." because ".$sex." is confused.</span><br>";
    break;
    case "blinded":	 return "<span class=\"".$color."\">".$p." can not aim ".$force." because ".$sex." is blinded.</span><br>";
    break;
    }
}

?>