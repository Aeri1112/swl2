<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Rest\Controller\RestController;
use Cake\I18n\Time;


/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PreferencesController extends RestController
{
	public function initialize(): void
    {
        parent::initialize();
		$this->loadModel("JediUserChars");
		$this->loadModel("JediUserSkills");
		$this->loadModel("JediAlliances");
		$this->loadModel("JediMasterrequestsText");
		$this->loadModel("JediMasterrequests");
		$this->loadModel("JediUserStatistics");
		$this->LoadModel('JediItemsJewelry');
		$this->LoadModel('JediItemsWeapons');
        $this->loadComponent('maxHealth');

		$this->user = $this->JediUserChars->get($this->Auth->User("id"));
		$this->user->skills = $this->JediUserSkills->get($this->Auth->User("id"));
    }
	
	public function fight()
	{
		$member = $this->JediUserChars->get($this->Auth->User("id"));
		$member->skills = $this->JediUserSkills->get($this->Auth->User("id"));
		
		if($this->request->is("post"))
		{
			$input = $this->request->getData()["formData"];
			$this->set("input",$input);
			
			if (!isset($input["switchoff_2"]))
			{
				$input["switchoff_2"] = "";
			}
			if (!isset($input["switchoff_3"]))
			{
				$input["switchoff_3"] = "";
			}
			
			$db_input = implode(",",$input);
			
			$member->fpreferences = $db_input;
			$this->JediUserChars->save($member);
		}
		
		//Ermittlung der Ausgabe
		//Preferences
		$fight_pref = explode(',',$member->fpreferences);
		$member->stance = $fight_pref[0];
		$member->initiative = $fight_pref[1];
		$member->heroic = $fight_pref[2];
		$member->innocents = $fight_pref[3];
		$member->surroundings = $fight_pref[4];
		$member->prefereddef = $fight_pref[5];
		$member->preferedoff = $fight_pref[6];
		$member->switchoff_1 = $fight_pref[7];
		$member->switchoff_2 = $fight_pref[8];
		$member->switchoff_3 = $fight_pref[9];
		
		$this->set("inno",$member->innocents);
		$this->set("stance",$member->stance);
		$this->set("initiative",$member->initiative);
		$this->set("heroic",$member->heroic);
		$this->set("surro",$member->surroundings);
		$this->set("prefereddef",$member->prefereddef);
		$this->set("preferedoff",$member->preferedoff);
		$this->set("switchoff_1",$member->switchoff_1);
		$this->set("switchoff_2",$member->switchoff_2);
		$this->set("switchoff_3",$member->switchoff_3);
		
		$fspee[0] = $member->skills->fspee; 
		$fspee[1] = 'fspee'; 
		$fspee[2] = '0';
		
		$fjump[0] = $member->skills->fjump;
		$fjump[1] = 'fjump';
		$fjump[2] = '1';
		
		$fpull[0] = $member->skills->fpull;
		$fpull[1] = 'fpull';
		$fpull[2] = '2'; 
		
		$fpush[0] = $member->skills->fpush;
		$fpush[1] = 'fpush';
		$fpush[2] = '3';
		
		$fseei[0] = $member->skills->fseei;
		$fseei[1] = 'fseei';
		$fseei[2] = '4'; 
		
		$fsabe[0] = $member->skills->fsabe;
		$fsabe[1] = 'fsabe';
		$fsabe[2] = '5'; 
		
		$fpers[0] = $member->skills->fpers;
		$fpers[1] = 'fpers';
		$fpers[2] = '6'; 
		
		$fproj[0] = $member->skills->fproj;
		$fproj[1] = 'fproj';
		$fproj[2] = '7';  
		
		$fblin[0] = $member->skills->fblin;
		$fblin[1] = 'fblin';
		$fblin[2] = '8';
		
		$fconf[0] = $member->skills->fconf;
		$fconf[1] = 'fconf';
		$fconf[2] = '9'; 
		
		$fheal[0] = $member->skills->fheal;
		$fheal[1] = 'fheal'; 
		$fheal[2] = '10'; 
		
		$fteam[0] = $member->skills->fteam;
		$fteam[1] = 'fteam'; 
		$fteam[2] = '11';
		
		$fprot[0] = $member->skills->fprot; 
		$fprot[1] = 'fprot'; 
		$fprot[2] = '12'; 
		
		$fabso[0] = $member->skills->fabso; 
		$fabso[1] = 'fabso'; 
		$fabso[2] = '13'; 
		
		$frvtl[0] = $member->skills->frvtl; 
		$frvtl[1] = 'frvtl'; 
		$frvtl[2] = '14';
		
		$fthro[0] = $member->skills->fthro; 
		$fthro[1] = 'fthro'; 
		$fthro[2] = '15';  
		
		$frage[0] = $member->skills->frage; 
		$frage[1] = 'frage'; 
		$frage[2] = '16'; 
		
		$fgrip[0] = $member->skills->fgrip; 
		$fgrip[1] = 'fgrip';
		$fgrip[2] = '17';
		
		$fdrai[0] = $member->skills->fdrai; 
		$fdrai[1] = 'fdrai'; 
		$fdrai[2] = '18'; 
		
		$fthun[0] = $member->skills->fthun; 
		$fthun[1] = 'fthun'; 
		$fthun[2] = '19'; 
		
		$fchai[0] = $member->skills->fchai; 
		$fchai[1] = 'fchai'; 
		$fchai[2] = '20';
		
		$fdest[0] = $member->skills->fdest; 
		$fdest[1] = 'fdest'; 
		$fdest[2] = '21';
		
		$fdead[0] = $member->skills->fdead; 
		$fdead[1] = 'fdead';
		$fdead[2] = '22';
		
		$ftnrg[0] = $member->skills->ftnrg; 
		$ftnrg[1] = 'ftnrg'; 
		$ftnrg[2] = '23';
		
		$def_forces = array($fspee, $fjump, $fseei, $fpers, $fproj, $fheal, $fteam, $fprot, $fabso, $frvtl, $frage, $ftnrg);
		$off_forces = array($fpull, $fpush, $fsabe, $fblin, $fconf, $fthro, $fgrip, $fdrai, $fthun, $fchai, $fdest, $fdead);
		$forces = array($fspee, $fjump, $fseei, $fpers, $fproj, $fheal, $fteam, $fprot, $fabso, $frvtl, $frage, $ftnrg,
						$fpull, $fpush, $fsabe, $fblin, $fconf, $fthro, $fgrip, $fdrai, $fthun, $fchai, $fdest, $fdead);

		$i = 0;
		foreach($off_forces as $key => $off_force)
		{
			if ($off_force[0] == 0)
			{
				unset($off_forces[$i]);
			}
			else
			{	
				$off_options[$off_forces[$i][2]] = $off_forces[$i][1];
			}
			$i++;
		}
		$off_options[99] = "keine";
		
		$i = 0;
		foreach($def_forces as $key => $def_force)
		{
			if ($def_force[0] == 0)
			{
				unset($def_forces[$i]);
			}
			else
			{
				$def_options[$def_forces[$i][2]] = $def_forces[$i][1];
			}
			$i++;
		}
		$def_options[99] = "keine";
		
		$i = 0;
		foreach($forces as $key => $force)
		{
			if ($force[0] == 0)
			{
				unset($forces[$i]);
			}
			else
			{
				$options[$forces[$i][2]] = $forces[$i][1];
			}
			$i++;
		}
		$options[99] = "keine";
		
		$this->set("options",$options);
		$this->set("def_options",$def_options);
		$this->set("off_options",$off_options);
	}

	public function pada() {

		$nomaster = null;
		$req = $this->JediMasterrequestsText->find()->where(["requester" => $this->user->userid])->first();
		$this->set("req",$req);
		//wenn der User noch kein Gesuch abgesetzt hat
		//sonst kann man sichs sparen
		if($this->user->masterid == 0) {
			//Wer hat ein request gestellt?
			$all = $this->JediMasterrequestsText->find()->toArray();
			$padas = [];
			$masters = [];
			$alliance = [];

			foreach ($all as $key => $value) {

				$skills = $this->JediUserSkills->find()->select(["userid","level","side"])->where(["userid" => $value->requester])->first();
				$char = $this->JediUserChars->find()->select(["userid","username","alliance","sex","species", "homeworld"])->where(["userid" => $value->requester])->first();
				//checken ob ich ein request habe
				$reqToMe = $this->JediMasterrequests->find()->where(["requester" => $value->requester])->where(["receiver" => $this->user->userid])->first();
				//checken ob ich ein request gesendet habe
				$reqFromMe = $this->JediMasterrequests->find()->where(["requester" => $this->user->userid])->where(["receiver" => $value->requester])->first();
				//infos bei möglicher allianz holen
				if($value->alliance != "0") {
					$alliance = $this->JediAlliances->find()->select(["name","short"])->where(["id" => $char->alliance])->first();
				}
				else {
					$alliance = [];
				}

				//aktivitätspunkte
				$value->activePoints = $this->activePoints($value->requester);

				if($skills->level < 75) {
					$padas[$key] = $value;
					$padas[$key]->allianceData = $alliance;
					$padas[$key]->skills = $skills;
					$padas[$key]->char = $char;
					$padas[$key]->reqToMe = $reqToMe;
					$padas[$key]->reqFromMe = $reqFromMe;
				}
				else {
					$masters[$key] = $value;
					$masters[$key]->allianceData = $alliance;
					$masters[$key]->skills = $skills;
					$masters[$key]->char = $char;
					$masters[$key]->reqToMe = $reqToMe;
					$masters[$key]->reqFromMe = $reqFromMe;
				}
			}
			usort($padas, function($a, $b) {
				return $a->skills->level <=> $b->skills->level;
			});
			usort($masters, function($a, $b) {
				return -1 * ($a->skills->level <=> $b->skills->level);
			});
			$nomaster = true;
			$this->set("level",$this->user->skills->level);
			$this->set("padas",$padas);
			$this->set("masters",$masters);
		}
		//Wenn ich einen Meister/Pada habe
		else {
			$masterPada = $this->JediUserChars->get($this->user->masterid);
			//aktivitätspunkte
			$masterPada->activePoints = $this->activePoints($this->user->masterid);
			//Abis
			$masterPada->abis = $this->JediUserSkills->find()->select(["cns","agi","lsa","lsd","dex","tac","spi","itl"])->where(["userid" => $this->user->masterid])->first();
			//Forces
			$masterPada->forces = $this->JediUserSkills->find()->select(["fspee", "fjump", "fseei", "fpush", "fpull", "fsabe", "fproj", "fpers", "fblin", "fconf", "fheal", "fteam", "fprot", "fabso", "fthro", "frage", "fgrip", "fdrai", "fthun", "fchai", "fdead", "fdest", "ftnrg", "frvtl"])
									->where(["userid" => $this->user->masterid])->first();

			$jewelry_model = $this->JediItemsJewelry->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->user->masterid]);
			$weapons_model = $this->JediItemsWeapons->find()->select(['stat1', 'stat2', 'stat3', 'stat4', 'stat5'])->where(['position' => 'eqp', 'ownerid' => $this->user->masterid]);
			$masterPada->tempBonusForces = $this->maxHealth->tempBonusForces($jewelry_model, $weapons_model); 
			$masterPada->tempBonus = $this->maxHealth->tempBonus($jewelry_model, $weapons_model);
			//für allgemeine skills
			$masterPada->skills = $this->JediUserSkills->get($this->user->masterid);
			//statistics
			$masterPada->stats = $this->JediUserStatistics->get($this->user->masterid);
			$masterPada->stats->npcPer = round($masterPada->stats->npcwins * 100 / ($masterPada->stats->npcwins + $masterPada->stats->npclosts),2);
			$masterPada->stats->arenaPer = round($masterPada->stats->arenawins * 100 / ($masterPada->stats->arenawins + $masterPada->stats->arenalosts),2);
			//infos bei möglicher allianz holen
			if($masterPada->alliance != "0") {
				$alliance = $this->JediAlliances->find()->select(["name","short"])->where(["id" => $masterPada->alliance])->first();
			}
			else {
				$alliance = [];
			}
			$masterPada->alliance = $alliance;

			$now = new Time($this->Accounts->get($this->user->masterid)->last_activity);
			$masterPada->account = $now->i18nFormat('yyyy, LLLL', null, "de-DE");

			$this->set("masterPada",$masterPada);
		}

		//wenn der User bereits einen Meister/Schüler hat
		if($this->user->masterid != 0) {

			$nomaster = false;
		}
		$this->set("nomaster",$nomaster);
	}

	public function forfeit($id) {
		$masterRequest = $this->JediMasterrequests->get($id);
		$masterRequest->status = 2;
		$this->JediMasterrequests->save($masterRequest);
	}

	public function accept($id) {
		//nochmal checken ob nicht einer schneller war
		$masterRequest = $this->JediMasterrequests->get($id);
		$user1 = $this->JediUserChars->get($masterRequest->requester);
		$user2 = $this->JediUserChars->get($masterRequest->receiver);

		if($user1->masterid == 0 && $user2->masterid == 0) {
			//Meister/Pada setzten
			$user1->masterid = $user2->userid;
			$user2->masterid = $user1->userid;
			
			$this->JediUserChars->save($user1);
			$this->JediUserChars->save($user2);
			//Alle requests von und zu diesen beiden löschen.
			$requests1 = $this->JediMasterrequests->find()->where(['OR' => [['requester' => $user1->userid], ['receiver' => $user1->userid]]]);
			$requests1->delete()->execute();
			$requests2 = $this->JediMasterrequests->find()->where(['OR' => [['requester' => $user2->userid], ['receiver' => $user2->userid]]]);
			$requests2->delete()->execute();
			//Die ANzeigen von beiden löschen.
			$request1 = $this->JediMasterrequestsText->find()->where(["requester" => $user1->userid]);
			$request2 = $this->JediMasterrequestsText->find()->where(["requester" => $user2->userid]);
			if($request1) {
				$request1->delete()->execute();
			}
			if($request2) {
				$request2->delete()->execute();
			}
		}
		else {
			$this->set("late",true);
		}
	}

	public function leave() {
		$otherUser = $this->JediUserChars->get($this->user->masterid);
		$otherUser->masterid = 0;
		$this->user->masterid = 0;
		$this->JediUserChars->save($otherUser);
		$this->JediUserChars->save($this->user);
	}

	public function request($id) {
		$request = $this->JediMasterrequests->newEntity();
		$request->requester = $this->user->userid;
		$request->receiver = $id;
		$request->status = 1;
		$this->JediMasterrequests->save($request);
	}

	public function offer() {
		$data = $this->request->getData();
		$offer = $this->JediMasterrequestsText->newEntity();
		$offer->requester = $this->user->userid;
		$offer->text = $data["text"];
		$this->JediMasterrequestsText->save($offer);
	}
//////////////////////////////////////////////////////////////////////////////////////////
	private function activePoints($userid) {
		//aktivitätspunkte berechnen
		$this->loadModel("Accounts");

		$char = $this->JediUserChars->get($userid);
		$char->skills = $this->JediUserSkills->get($userid);

		//base aktivität
		$active = 100;

		//abzug wg. nicht vergebener skillpunkte
		if($char->skills->rfp > 0 || $char->skills->rsp > 0) {
			$active -= 5;
		}

		//abzug wg. langer offline zeit
		//get last login
		$lastLogin = date_create($this->Accounts->get($userid)->last_activity->format("Y-m-d"));
		$now = date_create();
		$dateDiff = date_diff($now,$lastLogin)->days;

		$active -= $dateDiff;

		if($active < 0) {
			$active = 0;
		}
		return $active;
	}

	public function account() {

		//////////////////////////////MAIL///////////////////////////////////////
		if($this->request->getData("mail")) {
			//Email bereits verwendet?
			$check_mail = null;
			$check_mail = $this->loadModel("Accounts")->find()->where(["email" => $this->request->getData("mail")])->first();
			$this->set("check",$check_mail);
			if($check_mail == null) {
				$account = $this->loadModel("Accounts")->get($this->user->userid);
				$account->email = $this->request->getData("mail");
				$this->loadModel("Accounts")->save($account);
			}
		}
		/////////////////////////////PROFILBILD//////////////////////////////////////////
		$message = "";
		//if they DID upload a file...
		if($_FILES && $_FILES['input']['name'])
		{
			$name = $_FILES['input']['name'];
			$tmp_name = $_FILES['input']['tmp_name'];
			$position = strpos($name, ".");
			$fileextension = substr($name, $position + 1);
			$fileextension = strtolower($fileextension);

			//if no errors...
			if(!$_FILES['input']['error'])
			{
				$valid_file = true;
				//now is the time to modify the future file name and validate the file
				//Date as hash
				$now = Time::now()->i18nFormat("yyyyMMddHHmmss");
				$new_file_name = strtolower($this->user->userid.$now.".".$fileextension); //rename file
				if($_FILES['input']['size'] > (4096000)) //can't be larger than 1 MB
				{
					$valid_file = false;
					$message = 'Oops! Your file\'s size is to large.';
				}
				if (($fileextension !== "jpg") && ($fileextension !== "jpeg") && ($fileextension !== "png") && ($fileextension !== "bmp"))
				{
					$valid_file = false;
					$message = "The file extension must be .jpg, .jpeg, .png, or .bmp in order to be uploaded";
				}

				//if the file has passed the test
				if($valid_file)
				{
					//checking if file exsists
					if(file_exists($_SERVER["DOCUMENT_ROOT"].'/qyr/public/images/profile/'.$this->user->pic)) unlink($_SERVER["DOCUMENT_ROOT"].'/qyr/public/images/profile/'.$this->user->pic);
					//move it to where we want it to be
					if(move_uploaded_file($_FILES['input']['tmp_name'], $_SERVER["DOCUMENT_ROOT"].'/qyr/public/images/profile/'.$new_file_name)) {
						$message = 'Congratulations! Your file was accepted.';
						$this->user->pic = $new_file_name;
						$this->JediUserChars->save($this->user);
					}
				}
			}
			//if there is an error...
			else
			{
				//set that to be the returned message
				$message = 'Ooops! Your upload triggered the following error: '.$_FILES['input']['error'];
			}
		}
		$this->set("message",$message);
		$this->set("char",$this->user);
		$this->set("account",$this->loadModel("Accounts")->get($this->user->userid));
	}
}
?>