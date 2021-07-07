<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

class QuestComponent extends Component
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Quests = TableRegistry::get('Quests');
        $this->UserQuests = TableRegistry::get('UserQuests');
        $this->QuestSteps = TableRegistry::get('QuestSteps');
        $this->UserQuestSteps = TableRegistry::get('UserQuestSteps');
        $this->QuestBedingungen = TableRegistry::get('QuestBedingungen');	
		$this->JediUserSkills = TableRegistry::get("JediUserSkills");
		$this->JediUserChars = TableRegistry::get("JediUserChars");
		$this->JediItemsJewelry = TableRegistry::get("JediItemsJewelry");
		$this->connection = ConnectionManager::get('default');
		$this->payload = $this->config("payload");
    }
	
	public function aktiviere_quest()
	{
		$quests = $this->Quests->find()->where(["gelistet" => 1]);
		
		foreach($quests as $key => $quest)
		{
			$user_quest = $this->UserQuests->find()->where(["quest_id" => $quest->quest_id])->where(["user_id" => $this->payload->id])->first();
			
			//Wenn noch gar nichts in der DB
			if($user_quest == null)
			{
				$user_quest = $this->UserQuests->newEntity();
				$user_quest->quest_id = $quest->quest_id;
				$user_quest->user_id = $this->payload->id;
				$user_quest->status = 0;
				$this->UserQuests->save($user_quest);
				//sowie alle steps einfügen
				//steps zählen
				$quest_steps = $this->QuestSteps->find()->where(["quest_id" => $quest->quest_id])->count();
				
				for ($i=1; $i < $quest_steps+1; $i++)
				{ 
					$steps = $this->UserQuestSteps->newEntity();
					$steps->user_id = $this->payload->id;
					$steps->quest_id = $quest->quest_id;
					$steps->step_id = $i;
					$steps->status = 0;
					$this->UserQuestSteps->save($steps);
				}
				
			}
			//Wenn startbedingung erfüllt status des quest auf 1 sowie des ersten steps
			if($this->check_bedingung_quest($quest->quest_id, $this->payload->id) == true && $user_quest->status == 0)
			{
				$user_quest->status = 1;
				//Und den ersten Step auf 1 setzen
				$steps = $this->UserQuestSteps->newEntity();
				$steps->user_id = $this->payload->id;
				$steps->quest_id = $quest->quest_id;
				$steps->step_id = 1;
				$steps->status = 1;
				$this->UserQuestSteps->save($steps);		
				$this->UserQuests->save($user_quest);
			}
		}
	}
	
	public function pruefe_auf_quests($user_id, /*$hausnummer, */$haustyp)
	{
		# in:  $user_id, $hausnummer, $haustyp
		# out: 1 (Questseite wurde ausgegeben)
		#      0 (kein Quest verfuegbar, kein HTML-Output)
		#
		if ( $details = $this->quest_fortsetzen($user_id,/*$hausnummer,*/$haustyp) )
		{
			return [1, $details];
		}
		if ( $this->starte_naechsten_quest($user_id,/*$hausnummer, */$haustyp) )
		{
		 	return 1;
		}
		
		return 0;
	}
	
	public function starte_naechsten_quest($userid, /*$hausnummer, */$haustyp)
	{
	  # in:  $user_id, $hausnummer, $haustyp
	  # out: 1 (Questseite wurde ausgegeben)
	  #      0 (kein Quest verfuegbar, kein HTML-Output)
	  #
	  # lese aus "user_quests" alle Quests deren Status 1 ist
	  #
	  # streiche alle bei denen das Gebaeude nicht nicht passt
	  #
	  # sortiere nach quest_id (aufsteigend) und waehle per Schleife den
	  # ersten dessen startbedingung erfuellt ist
	  #
	  # - starte step 1 des quests:
	  #     starte_quest_step($user_id,$quest_id,'1');
	  #
	  # - return 1
	  #
	  # wenn kein Quest die Startbedingung erfuellt, dann
	  # return 0
	  #		
		$stmt = $this->connection->prepare("SELECT * FROM user_quests LEFT JOIN quest_steps ON user_quests.quest_id = quest_steps.quest_id WHERE user_quests.status = 1 AND quest_steps.step_id = 1 AND user_id = :userid ORDER BY user_quests.quest_id ASC");
		$stmt->execute(['userid' => $userid]);
		$quests = $stmt->fetchAll('assoc');
		
		if(!$quests)
		{
			return 0;
		}
		//Alle Quests durchgehen
		foreach ($quests as $quest)
		{
			if($quest['startgebaeude'] != "0" && ($haustyp == $quest['startgebaeude'] OR $quest['startgebaeude'] == 'any'))
			{
				if($this->check_bedingung_quest($quest['quest_id'], $userid))
				{
					#echo "Prüfung des Quest ".$quest['quest_id']." in starte_naechsten_quest(Quest auf 2 setzen)<br>";
					//Quest starten - also auf 2 setzten
					$stmt = $this->connection->prepare("UPDATE user_quests SET status = 2 WHERE user_id = :userid AND quest_id = :questid");
					$stmt->execute(['userid' => $userid, 'questid' => $quest['quest_id']]);
					
					$this->starte_quest_step($userid, $quest['quest_id'], $quest['step_id']);
					
					return 1;
				}
				else
				{
					return 0;	
				}
			}
			else
			{
				return 0;
			}	
		}
	}
	
	function quest_fortsetzen($user_id, /*$hausnummer, */$haustyp)
	{
		# in:  $user_id, $hausnummer, $haustyp
		# out: 1 (Questseite wurde ausgegeben)
		#      0 (kein Quest verfuegbar, kein HTML-Output)
		#
		# lese aus "user_quest_steps" alle Steps deren Status 2 ist
		#
		# streiche alle bei denen das Gebaeude nicht nicht passt
		#
		# sortiere nach step_id (absteigend) und nach quest_id (aufsteigend)
		#
		# pruefe die erledigt_bedingung der Steps in einer Schleife, bis
		# zum ersten Step bei dem die erledigt_bedingung erfuellt ist
		#
		# - setze den status des Steps von 2 nach 3
		# - setzt den status des folgesteps von 0 nach 1
		# EINSCHUB VON AERI
			# - setzt den status des folgesteps von 0 nach 2
			# einzelne steps haben keine überprüfung um sie von 1 auf 2 zu setzen
			# blieben die steps bei status 1 werden sie weder in quest_forsetzen noch in starte_naechsten_quest abgefragt
			# quest ist weiter aktiv, daher status 2 richtig
		# EINSCHUB ENDE
  		# - falls kein Folgestep existiert, setzt den Status des Quests auf 3
  		# - verteile die Belohnung des Steps falls vorhanden
		#
		# - wenn step nicht "hidden" ist
		#   dann Erfolgstext ausgeben
		#   sonst den Folgestep pruefen
		#
		# - return 1
		#
		# wenn bei keinem Step die erledigt_bedingung erfuellt ist,
		#   dann return 0;
		$char = $this->JediUserChars->get($user_id);
		$char->skills = $this->JediUserSkills->get($user_id);
		
		$stmt = $this->connection->prepare("SELECT * FROM quest_steps LEFT JOIN user_quest_steps ON quest_steps.quest_id = user_quest_steps.quest_id AND quest_steps.step_id = user_quest_steps.step_id WHERE user_quest_steps.status = 2 AND user_quest_steps.user_id = :userid ORDER BY quest_steps.step_id DESC, quest_steps.quest_id ASC");
		$stmt->execute(['userid' => $user_id]);
		$quests = $stmt->fetchAll('assoc');

		//Alle gefundenen Quest_steps durchgehen und erledigt_bedingung pruefen
		foreach ($quests as $quest)
		{
			if($quest['startgebaeude'] != "0" && ($haustyp == $quest['startgebaeude'] OR $quest['startgebaeude'] == 'any'))
			{			
				//Erledigt Bedingung der einzelen Steps durchgehen
				//Bei typ == simple kann direkt die erledigt_bedingung abgefragt werden und der Queststep beendet werden
				if($quest['typ'] == "simple")
				{
	
					return $quest;
				}
				/* Bei allen anderen quest_step typen muss erledigt_bedingung erfüllt sein, damit 1 returned wird und die questseite ausgegeben wird
				 * dort drin steckt dann die eigentliche Prüfung (nach wait/fight/choose/text kann quest_step auf 3 gesetzt werden)
				 */
				elseif ($quest['typ'] == "wait")
				{
					/*
					if (isset($_GET['cancel_quest']) && isset($_GET['sure']))
					{
					  mysql_query("UPDATE jedi_user_chars set actionid = '0', targetid = '0', targettime = '0' WHERE userid = '$user_id'");
					}
										
					if($char->actionid == 0)	
					{	
						echo"<form name='quest_wait' action='' method='POST' target=''>
						<input type='submit' name='wait' value='start' class='Button'>
						</form>";
					}
					
					//Bestätigt Countdown beginnt zu laufen
					if(isset($_POST['wait']) && $char->actionid == 0)
					{
						$time = time() + $quest['dauer'];
						mysql_query("UPDATE jedi_user_chars SET actionid = '3', targetid = '', targettime = '$time' WHERE userid = '$user_id'");
						//hier noch ein reload					
					}
					
					//Countdown läuft warten bis fertig
					if($char->actionid == 3)
					{
						$time = $quest['dauer'];
						$lefttime = $char->targettime - time();
						$progress = ($time - $lefttime) * 100 / $time;
						if($progress>"100")$progress="100";
						
						//Evtl. schon fertig
						if ($lefttime <= 0)
						{
							$chance = $quest['chance'];
							$sucess = rand(1,100);
							
							if($quest['hidden'] == '0' AND ($chance>$sucess))
							{
								echo"DEBUG: $chance / $sucess <br>";
								$this->endQuestStep($user_id, $quest['quest_id'], $quest['step_id']);
								mysql_query("UPDATE jedi_user_chars SET actionid = '0', targetid = '0', targettime = '0' WHERE userid = $user_id");
								
								echo $quest['erfolgstext'];
							}
							elseif($quest['hidden'] == '0' AND ($chance<$sucess))
							{
								echo"DEBUG: $chance / $sucess <br>";
								echo $quest['misserfolgstext'];								
							}
						}
						else
						{
						?>
					    <p class="Content">
	    				<?php echo $tpl_13.realtime($time); ?> h.<br>
					    <?php echo $tpl_14; ?><b id=bxx></b> h.<br><br>
						<?php echo $tpl_15.$result;?></p>
						<form action="" method="" target=""><input type="Checkbox" name="sure" value="yes"><input type="Submit" name="cancel_quest" value="Cancel"></form>
					    <table align="center" cellpadding="1" cellspacing="1" border="0" bgcolor="#cccccc" width="256">
					    <tr><td bgcolor="#ffffff">
					    <img src="../img/ui/progressbar.gif" height="8" width="<?php echo $progress * 252 / 100; ?>"></td></tr></table><br>
					    <center class="Content">completed: <?php echo round($progress,2); ?>%</center>
					    <?php
						}
					   	if ($lefttime)
					   	{
							#echo gettimer($lefttime);
						}
					}
					*/
					return $quest;	
					
				}
				elseif($quest['typ'] == "fight")
				{
					return $quest;
				}
				elseif($quest["typ"] == "puzzle") {
					return $quest;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 0;
			}	
		}
	}
	public function starte_quest_step($userid,$questid,$stepid)
	{
		//Quest-Step Nr. 1 starten - also auf 2 setzten
		$stmt = $this->connection->prepare("UPDATE user_quest_steps SET status = 2 WHERE user_id = :userid AND quest_id = :questid AND step_id = :step_id");
		$stmt->execute(['userid' => $userid, 'questid' => $questid, 'step_id' => $stepid]);
	}
	
	public function endQuestStep($user_id, $quest_id, $step_id)
	{
		#echo "geprüft wird Step ".$step_id." von ".$quest_id." in quest_forsetzen<br>";
		//Setzte status des Steps von 2 auf 3
		$stmt = $this->connection->prepare("UPDATE user_quest_steps SET status = 3 WHERE user_id = :userid AND quest_id = :questid AND step_id = :step_id");
		$stmt->execute(['userid' => $user_id, 'questid' => $quest_id, 'step_id' => $step_id]);
		
		//Setzte status des folgesteps von 0 auf 1 EINSCHUB VON AERI von 0 auf 2 (siehe oben)
		$stmt = $this->connection->prepare("UPDATE user_quest_steps SET status = 2 WHERE user_id = :userid AND quest_id = :questid AND step_id = :step_id");
		$stmt->execute(['userid' => $user_id, 'questid' => $quest_id, 'step_id' => $step_id+1]);
							
		//Es existiert kein folgestep, setzte status des quests auf 3
		if($this->getNextStep($quest_id, $step_id) == false)
		{
			#echo "es existiert kein Folgestep in quest_forsetzen<br>";
			$stmt = $this->connection->prepare("UPDATE user_quests SET status = 3 WHERE user_id = :userid AND quest_id = :questid");
			$stmt->execute(['userid' => $user_id, 'questid' => $quest_id]);						
		}
		//verteile Belohnung des Steps falls vorhanden
		$stmt = $this->connection->prepare("SELECT funktion FROM quest_belohnungen LEFT JOIN quest_steps ON quest_steps.belohnungs_id = quest_belohnungen.belohnungs_id WHERE quest_steps.quest_id = :quest_id AND quest_steps.step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id]);
		$funktion = $stmt->fetch('assoc');
		
		$stmt = $this->connection->prepare("SELECT belohnungs_menge FROM quest_steps WHERE quest_id = :quest_id AND step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id]);
		$parameter = $stmt->fetch('assoc');
		
		#$funktion['funktion']($user_id, $parameter['belohnungs_menge']);
		$this->translate_treasure_func($funktion["funktion"], $user_id, $parameter["belohnungs_menge"]);
		if($this->getNextStep($quest_id, $step_id) == true)
		{
			$next_step = $step_id + 1;
			if($this->check_bedingung_quest_step($quest_id, $next_step, $user_id))
			{
				#if($debug) echo "Prüfgung Folgestep";
				$this->starte_quest_step($user_id, $quest_id, $next_step);
			}
		}
	}
	
	public function getNextStep($quest_id, $step_id)
	{
		$stmt = $this->connection->prepare("SELECT * FROM quest_steps WHERE quest_id = :quest_id AND step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id+1]);
		$next_step = $stmt->fetch();
		return $next_step;
	}
	
	public function check_bedingung_quest($quest_id, $userid)
	{		
		$quest = $this->Quests->get($quest_id);
		$bedingung_id = $quest->startbedingung;
		$bedingung_func = $this->QuestBedingungen->find()->select(["funktion"])->where(["bedingungs_id" => $bedingung_id])->first();
		
		$startbedingung = $this->translate_func($bedingung_func->funktion, $userid);
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
	
	public function check_bedingung_quest_step($quest_id, $step_id, $userid)
	{		
		#$quest_step = $this->QuestSteps->find()->where(["step_id" => $step_id])->where(["quest_id" => $quest_id])->first();
		#$bedingung_id = $quest_step->erledigt_bedingung;
		#$bedingung_func = $this->QuestBedingungen->find()->select(["funktion"])->where(["bedingungs_id" => $bedingung_id])->first();
		
		$stmt = $this->connection->prepare("SELECT funktion FROM quest_bedingungen LEFT JOIN quest_steps ON quest_steps.erledigt_bedingung = quest_bedingungen.bedingungs_id WHERE quest_steps.quest_id = :quest_id AND quest_steps.step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id]);
		$bedingung = $stmt->fetch('assoc');
		
		$stmt = $this->connection->prepare("SELECT erledigt_parameter FROM quest_steps WHERE quest_id = :quest_id AND step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id]);
		$parameter = $stmt->fetch('assoc');
		$parameter = $parameter['erledigt_parameter'];
		
		$erledigtbedingung = $this->translate_func($bedingung["funktion"], $userid);

		if($bedingung["funktion"] == "abis" OR $bedingung["funktion"] == "mights") {
			//hier ist erledigtbedingung ein array aus den abis oder forces
			foreach ($erledigtbedingung as $key => $value) {
				if($value >= $parameter) {
					return true;
				}
			}
			return false;
		}
		elseif($bedingung["funktion"] == "location") {
			if($erledigtbedingung == $parameter) {
				return true;
			}
			else {
				return false;
			}
		}
		elseif($bedingung["funktion"] == "win") {
			return false;
		}
		elseif($erledigtbedingung >= $parameter)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	public function translate_func($func, $userid)
	{
		$char = $this->JediUserChars->get($userid);
		$char->skills = $this->JediUserSkills->get($userid);
		
		switch ($func) {
			case 'level':
				return $char->skills->level;
				break;
			case 'location':
				return $char->location;
				break;
			case 'abis':
				return [$char->skills->cns, $char->skills->agi, $char->skills->spi, $char->skills->itl, $char->skills->dex, $char->skills->tac, $char->skills->lsa, $char->skills->lsd];
			case 'mights':
				return [$char->skills->fspee, $char->skills->fjump, $char->skills->fpull, $char->skills->fpush, $char->skills->fseei, 
						$char->skills->fpers, $char->skills->fproj, $char->skills->fblin, $char->skills->fconf, $char->skills->fheal, $char->skills->fabso, $char->skills->fprot,
						$char->skills->frage, $char->skills->fthro, $char->skills->fdrai, $char->skills->fgrip, $char->skills->fthun, $char->skills->fdest, $char->skills->fdead];
			case 'health':
				return $char->health;
			default:
				return "NaN";
				break;
		}
	}
	
	public function translate_treasure_func($func, $userid, $parameter)
	{
		$char = $this->JediUserChars->get($userid);
		$char->skills = $this->JediUserSkills->get($userid);
		
		switch ($func) {
			case 'inccash':
				$char->cash += $parameter;
				$this->JediUserChars->save($char);
				return;
				break;
			case 'incxp':
				$char->skills->xp += $parameter;
				$this->JediUserSkills->save($char->skills);
				return;
			case 'incSp':
				$char->skills->rsp += 1;
				$this->JediUserSkills->save($char->skills);
				return;
			case 'incFp':
				$char->skills->rfp += 1;
				$this->JediUserSkills->save($char->skills);
				return;
			case 'starterBundle':
				$jewelry = $this->JediItemsJewelry->newEntity();
				$jewelry->ownerid = $userid;
				$jewelry->position = "inv";
				$jewelry->name = "Kampfhilfe";
				$jewelry->img = "gold1";
				$jewelry->sizex = 16;
				$jewelry->sizey = 16;
				$jewelry->price = 100;
				$jewelry->qlvl = 10;
				$jewelry->crafted = 0;
				$jewelry->weight = 10;
				$jewelry->reql = 2;
				$jewelry->reqs = 0;
				$jewelry->mindmg = 0;
				$jewelry->maxdmg = 0;
				$jewelry->stat1 = "inc,lsa,2";
				$this->JediItemsJewelry->save($jewelry);
				//NUmmer 2
				$jewelry = $this->JediItemsJewelry->newEntity();
				$jewelry->ownerid = $userid;
				$jewelry->position = "inv";
				$jewelry->name = "Machthilfe";
				$jewelry->img = "gold1";
				$jewelry->sizex = 16;
				$jewelry->sizey = 16;
				$jewelry->price = 100;
				$jewelry->qlvl = 10;
				$jewelry->crafted = 0;
				$jewelry->weight = 10;
				$jewelry->reql = 2;
				$jewelry->reqs = 0;
				$jewelry->mindmg = 0;
				$jewelry->maxdmg = 0;
				$jewelry->stat1 = "inc,spi,2";
				$this->JediItemsJewelry->save($jewelry);
				return;
			default:
				return "NaN";
				break;
		}
	}
	
	public function getStepText($userid)
	{
		$stmt = $this->connection->prepare("SELECT * FROM user_quests WHERE user_id = :user_id AND status = 2 LIMIT 1");
		$stmt->execute(['user_id' => $userid]);
		$res = $stmt->fetch("assoc");
		$quest_id = $res["quest_id"];
		
		$stmt = $this->connection->prepare("SELECT * FROM user_quest_steps WHERE quest_id = :quest_id AND user_id = :user_id AND status = 2 LIMIT 1");
		$stmt->execute(['quest_id' => $quest_id, 'user_id' => $userid]);
		$res = $stmt->fetch("assoc");
		$step_id = $res["step_id"];
		
		$stmt = $this->connection->prepare("SELECT * FROM quest_steps WHERE quest_id = :quest_id AND step_id = :step_id");
		$stmt->execute(['quest_id' => $quest_id, 'step_id' => $step_id]);
		$step_details = $stmt->fetch('assoc');
		
		return $step_details;
	}
	public function wait($userid)
	{
		$char = $this->JediUserChars->get($userid);
		$char->skills = $this->JediUserSkills->get($userid);
		
		$quest = $this->getStepText($userid);
		$form = null;
		$miss = null;
		$win = null;
		$lefttime = null;
		$time = null;
		$progress = null;
		$cancel = null;
		$countdown = null;
		
		if (isset($_GET['cancel_quest']) && isset($_GET['sure']))
		{
		  $stmt = $this->connection->prepare("UPDATE jedi_user_chars set actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
		  $stmt->execute(['user_id' => $userid]);
		}
									
		//Bestätigt Countdown beginnt zu laufen
		if(isset($_POST['wait']) && $char->actionid == 0)
		{
			$time = time() + $quest['dauer'];
			$stmt = $this->connection->prepare("UPDATE jedi_user_chars SET actionid = '3', targetid = 0, targettime = '$time' WHERE userid = :user_id");
			$stmt->execute(['user_id' => $userid]);
		}
		
		$char = $this->JediUserChars->get($userid);
		
		if($char->actionid == 0)	
		{			
			$form ="<form name='quest_wait' method='POST'>
			<input type='submit' name='wait' value='start' class='Button'>
			<input type='hidden' name='_csrfToken' autocomplete='off' value=".$_COOKIE["csrfToken"].">
			</form>";
		}
		
		//Countdown läuft warten bis fertig
		if($char->actionid == 3)
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
					
					$this->endQuestStep($userid, $quest['quest_id'], $quest['step_id']);
					
					$stmt = $this->connection->prepare("UPDATE jedi_user_chars SET actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
					$stmt->execute(['user_id' => $userid]);
				}
				elseif($quest['hidden'] == '0' AND ($chance < $sucess))
				{
					$miss = true;
					$win = false;
					
					$stmt = $this->connection->prepare("UPDATE jedi_user_chars SET actionid = 0, targetid = 0, targettime = 0 WHERE userid = :user_id");
					$stmt->execute(['user_id' => $userid]);					
				}
			}
			else
			{
				$cancel =
				"
					<form action='' method='' target=''>
					<input type='Checkbox' name='sure' value='yes'>
					<input type='Submit' name='cancel_quest' value='Cancel'>
					<input type='hidden' name='_csrfToken' autocomplete='off' value=".$_COOKIE["csrfToken"].">
					</form>
				";		
			}
		}
		return array($form, $time, $lefttime, $progress, $miss, $win, $cancel);
	}
}
?>