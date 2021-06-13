<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Rest\Controller\RestController;


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
		//wenn der User sich noch nicht entschieden hat
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
				}
				else {
					$masters[$key] = $value;
					$masters[$key]->allianceData = $alliance;
					$masters[$key]->skills = $skills;
					$masters[$key]->char = $char;
					$masters[$key]->reqToMe = $reqToMe;
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
}
?>