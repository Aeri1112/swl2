<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class altFightComponent extends Component
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
    function Abis($id)
	{
        $char = $this->JediUserChars->get($id);
        $char->skills = $this->JediUserSkills->get($id);
        
  		//$dbconn->query("SELECT * FROM test_abis WHERE user_id = '$id' LIMIT 1");
  		//$feld = mysql_fetch_array($dbconn->result);
		// Cns
		$abis['bCns'] = $char->skills->cns;
		#$abis['sCns'] = getAbi("sCns", $id);
		#$abis['rCns'] = getAbi("rCns", $id);
		#$abis['dCns'] = getAbi("dCns", $id);
		// Agi
		$abis['bAgi'] = $char->skills->agi;
		#$abis['sAgi'] = getAbi("sAgi", $id);
		#$abis['rAgi'] = getAbi("rAgi", $id);
		#$abis['dAgi'] = getAbi("dAgi", $id);
		// Spi
		$abis['bSpi'] = $char->skills->spi;
		#$abis['sSpi'] = getAbi("sSpi", $id);
		#$abis['rSpi'] = getAbi("rSpi", $id);
		#$abis['dSpi'] = getAbi("dSpi", $id);
		// Int
		$abis['bInt'] = $char->skills->itl;
		#$abis['sInt'] = getAbi("sInt", $id);
		#$abis['rInt'] = getAbi("rInt", $id);
		#$abis['dInt'] = getAbi("dInt", $id);
		// Tac
		$abis['bTac'] = $char->skills->tac;
		#$abis['sTac'] = getAbi("sTac", $id);
		#$abis['rTac'] = getAbi("rTac", $id);
		#$abis['dTac'] = getAbi("dTac", $id);
		// Dex
		$abis['bDex'] = $char->skills->dex;
		#$abis['sDex'] = getAbi("sDex", $id);
		#$abis['rDex'] = getAbi("rDex", $id);
		#$abis['dDex'] = getAbi("dDex", $id);
		// Lsa
		$abis['bLsa'] = $char->skills->lsa;
		#$abis['sLsa'] = getAbi("sLsa", $id);
		#$abis['rLsa'] = getAbi("rLsa", $id);
		#$abis['dLsa'] = getAbi("dLsa", $id);
		// Lsd
		$abis['bLsd'] = $char->skills->lsd;
		#$abis['sLsd'] = getAbi("sLsd", $id);
		#$abis['rLsd'] = getAbi("rLsd", $id);
		#$abis['dLsd'] = getAbi("dLsd", $id);
		
		// Finalle Werte
		$this->cns = $abis['bCns'];/* + $abis['sCns'] +
					(($abis['bCns'] > 0) ? $abis['rCns'] : 0) +
					(($abis['bCns'] > 0) ? $abis['dCns'] : 0); */
		$this->agi = $abis['bAgi']; /* + $abis['sAgi'] +
					(($abis['bAgi'] > 0) ? $abis['rAgi'] : 0) +
					(($abis['bAgi'] > 0) ? $abis['dAgi'] : 0); */
		$this->spi = $abis['bSpi']; /* + $abis['sSpi'] +
					(($abis['bSpi'] > 0) ? $abis['rSpi'] : 0) +
					(($abis['bSpi'] > 0) ? $abis['dSpi'] : 0); */
		$this->itl = $abis['bInt']; /* + $abis['sInt'] +
					(($abis['bInt'] > 0) ? $abis['rInt'] : 0) +
					(($abis['bInt'] > 0) ? $abis['dInt'] : 0); */
		$this->tac = $abis['bTac']; /* + $abis['sTac'] +
					(($abis['bTac'] > 0) ? $abis['rTac'] : 0) +
					(($abis['bTac'] > 0) ? $abis['dTac'] : 0); */
		$this->dex = $abis['bDex']; /* + $abis['sDex'] +
					(($abis['bDex'] > 0) ? $abis['rDex'] : 0) +
					(($abis['bDex'] > 0) ? $abis['dDex'] : 0); */
		$this->lsa = $abis['bLsa']; /* + $abis['sLsa'] +
					(($abis['bLsa'] > 0) ? $abis['rLsa'] : 0) +
					(($abis['bLsa'] > 0) ? $abis['dLsa'] : 0); */
		$this->lsd = $abis['bLsd']; /* + $abis['sLsd'] +
					(($abis['bLsd'] > 0) ? $abis['rLsd'] : 0) +
					(($abis['bLsd'] > 0) ? $abis['dLsd'] : 0);	*/
		if ($this->cns < 1) $this->cns = 1;
		if ($this->agi < 1) $this->agi = 1;
		if ($this->spi < 1) $this->spi = 1;
		if ($this->itl < 1) $this->itl = 1;
		if ($this->tac < 1) $this->tac = 1;
		if ($this->dex < 1) $this->dex = 1;
		if ($this->lsa < 1) $this->lsa = 1;
		if ($this->lsd < 1) $this->lsd = 1;
       
        return $abis;
    }  


    function Mights($id)
	{
        $char = $this->JediUserChars->get($id);
        $char->skills = $this->JediUserSkills->get($id);
			
			//$dbconn->query("SELECT * FROM test_mights WHERE user_id = '$id' LIMIT 1");
			//$feld = mysql_fetch_array($dbconn->result);
			// Speed
			
			$force['bSpee'] = $char->skills->fspee;
			#$force['sSpee'] = getForce("sSpee", $id);
			#$force['rSpee'] = getForce("rSpee", $id);
			#$force['dSpee'] = getForce("dSpee", $id); 
			$this->fspee = $force['bSpee']; /* +
						(($force['bSpee'] > 0) ? $force['sSpee'] : 0) +
						(($force['bSpee'] > 0) ? $force['rSpee'] : 0) +
						(($force['bSpee'] > 0) ? $force['dSpee'] : 0); */
			// Jump
			$force['bJump'] = $char->skills->fjump;
			#$force['sJump'] = getForce("sJump", $id);
			#$force['rJump'] = getForce("rJump", $id);
			#$force['dJump'] = getForce("dJump", $id); 
			$this->fjump = $force['bJump']; /* +
						(($force['bJump'] > 0) ? $force['sJump'] : 0) +
						(($force['bJump'] > 0) ? $force['rJump'] : 0) +
						(($force['bJump'] > 0) ? $force['dJump'] : 0); */
			// Pull
			$force['bPull'] = $char->skills->fpull;
			#$force['sPull'] = getForce("sPull", $id);
			#$force['rPull'] = getForce("rPull", $id);
			#$force['dPull'] = getForce("dPull", $id); 
			$this->fpull = $force['bPull']; /* +
						(($force['bPull'] > 0) ? $force['sPull'] : 0) +
						(($force['bPull'] > 0) ? $force['rPull'] : 0) +
						(($force['bPull'] > 0) ? $force['dPull'] : 0); */
			// Push
			$force['bPush'] = $char->skills->fpush;
			#$force['sPush'] = getForce("sPush", $id);
			#$force['rPush'] = getForce("rPush", $id);
			#$force['dPush'] = getForce("dPush", $id); 
			$this->fpush = $force['bPush']; /* +
						(($force['bPush'] > 0) ? $force['sPush'] : 0) +
						(($force['bPush'] > 0) ? $force['rPush'] : 0) +
						(($force['bPush'] > 0) ? $force['dPush'] : 0); */
			// Seeing
			$force['bSeei'] = $char->skills->fseei;
			#$force['sSeei'] = getForce("sSeei", $id);
			#$force['rSeei'] = getForce("rSeei", $id);
			#$force['dSeei'] = getForce("Seeid", $id); 
			$this->fseei = $force['bSeei']; /* +
						(($force['bSeei'] > 0) ? $force['sSeei'] : 0) +
						(($force['bSeei'] > 0) ? $force['rSeei'] : 0) +
						(($force['bSeei'] > 0) ? $force['dSeei'] : 0); */
			// Saber Throw
			$force['bSabe'] = $char->skills->fsabe;
			#$force['sSabe'] = getForce("sSabe", $id);
			#$force['rSabe'] = getForce("rSabe", $id);
			#$force['dSabe'] = getForce("dSabe", $id); 
			$this->fsabe = $force['bSabe']; /* +
						(($force['bSabe'] > 0) ? $force['sSabe'] : 0) +
						(($force['bSabe'] > 0) ? $force['rSabe'] : 0) +
						(($force['bSabe'] > 0) ? $force['dSabe'] : 0); */
			
			// Hell ------------------------------------------------------
			// Persuation
			$force['bPers'] = $char->skills->fpers;
			#$force['sPers'] = getForce("sPers", $id);
			#$force['rPers'] = getForce("rPers", $id);
			#$force['dPers'] = getForce("dPers", $id); 
			$this->fpers = $force['bPers']; /* +
						(($force['bPers'] > 0) ? $force['sPers'] : 0) +
						(($force['bPers'] > 0) ? $force['rPers'] : 0) +
						(($force['bPers'] > 0) ? $force['dPers'] : 0); */
			// Projection
			$force['bProj'] = $char->skills->fproj;
			#$force['sProj'] = getForce("sProj", $id);
			#$force['rProj'] = getForce("rProj", $id);
			#$force['dProj'] = getForce("dProj", $id); 
			$this->fproj = $force['bProj']; /* +
						(($force['bProj'] > 0) ? $force['sProj'] : 0) +
						(($force['bProj'] > 0) ? $force['rProj'] : 0) +
						(($force['bProj'] > 0) ? $force['dProj'] : 0); */
			// Blind
			$force['bBlin'] = $char->skills->fblin;
			#$force['sBlin'] = getForce("sBlin", $id);
			#$force['rBlin'] = getForce("rBlin", $id);
			#$force['dBlin'] = getForce("dBlin", $id); 
			$this->fblin = $force['bBlin']; /* +
						(($force['bBlin'] > 0) ? $force['sBlin'] : 0) +
						(($force['bBlin'] > 0) ? $force['rBlin'] : 0) +
						(($force['bBlin'] > 0) ? $force['dBlin'] : 0);	*/		
			// Confuse
			$force['bConf'] = $char->skills->fconf;
			#$force['sConf'] = getForce("sConf", $id);
			#$force['rConf'] = getForce("rConf", $id);
			#$force['dConf'] = getForce("dConf", $id); 
			$this->fconf = $force['bConf']; /* +
						(($force['bConf'] > 0) ? $force['sConf'] : 0) +
						(($force['bConf'] > 0) ? $force['rConf'] : 0) +
						(($force['bConf'] > 0) ? $force['dConf'] : 0); */			
			// Heal
			$force['bHeal'] = $char->skills->fheal;
			#$force['sHeal'] = getForce("sHeal", $id);
			#$force['rHeal'] = getForce("rHeal", $id);
			#$force['dHeal'] = getForce("dHeal", $id); 
			$this->fheal = $force['bHeal']; /* +
						(($force['bHeal'] > 0) ? $force['sHeal'] : 0) +
						(($force['bHeal'] > 0) ? $force['rHeal'] : 0) +
						(($force['bHeal'] > 0) ? $force['dHeal'] : 0);		*/				
			// Team Heal
			$force['bTeam'] = $char->skills->fteam;
			#$force['sTeam'] = getForce("sTeam", $id);
			#$force['rTeam'] = getForce("rTeam", $id);
			#$force['dTeam'] = getForce("dTeam", $id); 
			$this->fteam = $force['bTeam']; /* +
						(($force['bTeam'] > 0) ? $force['sTeam'] : 0) +
						(($force['bTeam'] > 0) ? $force['rTeam'] : 0) +
						(($force['bTeam'] > 0) ? $force['dTeam'] : 0); */
			// Protect
			$force['bProt'] = $char->skills->fprot;
			#$force['sProt'] = getForce("sProt", $id);
			#$force['rProt'] = getForce("rProt", $id);
			#$force['dProt'] = getForce("dProt", $id); 
			$this->fprot = $force['bProt']; /* +
						(($force['bProt'] > 0) ? $force['sProt'] : 0) +
						(($force['bProt'] > 0) ? $force['rProt'] : 0) +
						(($force['bProt'] > 0) ? $force['dProt'] : 0); */
			// Absorb
			$force['bAbso'] = $char->skills->fabso;
			#$force['sAbso'] = getForce("sAbso", $id);
			#$force['rAbso'] = getForce("rAbso", $id);
			#$force['dAbso'] = getForce("dAbso", $id); 
			$this->fabso = $force['bAbso']; /* +
						(($force['bAbso'] > 0) ? $force['sAbso'] : 0) +
						(($force['bAbso'] > 0) ? $force['rAbso'] : 0) +
						(($force['bAbso'] > 0) ? $force['dAbso'] : 0); */
			
			// Dunkel ------------------------------------------------------
			// Throw
			$force['bThro'] = $char->skills->fthro;
			#$force['sThro'] = getForce("sThro", $id);
			#$force['rThro'] = getForce("rThro", $id);
			#$force['dThro'] = getForce("dThro", $id); 
			$this->fthro = $force['bThro']; /* +
						(($force['bThro'] > 0) ? $force['sThro'] : 0) +
						(($force['bThro'] > 0) ? $force['rThro'] : 0) +
						(($force['bThro'] > 0) ? $force['dThro'] : 0); */
			// Rage
			$force['bRage'] = $char->skills->frage;
			#$force['sRage'] = getForce("sRage", $id);
			#$force['rRage'] = getForce("rRage", $id);
			#$force['dRage'] = getForce("dRage", $id); 
			$this->frage = $force['bRage']; /* +
						(($force['bRage'] > 0) ? $force['sRage'] : 0) +
						(($force['bRage'] > 0) ? $force['rRage'] : 0) +
						(($force['bRage'] > 0) ? $force['dRage'] : 0); */
			// Grip
			$force['bGrip'] = $char->skills->fgrip;
			#$force['sGrip'] = getForce("sGrip", $id);
			#$force['rGrip'] = getForce("rGrip", $id);
			#$force['dGrip'] = getForce("dGrip", $id); 
			$this->fgrip = $force['bGrip']; /* +
						(($force['bGrip'] > 0) ? $force['sGrip'] : 0) +
						(($force['bGrip'] > 0) ? $force['rGrip'] : 0) +
						(($force['bGrip'] > 0) ? $force['dGrip'] : 0); */
			// Drain
			$force['bDrai'] = $char->skills->fdrai;
			#$force['sDrai'] = getForce("sDrai", $id);
			#$force['rDrai'] = getForce("rDrai", $id);
			#$force['dDrai'] = getForce("dDrai", $id); 
			$this->fdrai = $force['bDrai']; /* +
						(($force['bDrai'] > 0) ? $force['sDrai'] : 0) +
						(($force['bDrai'] > 0) ? $force['rDrai'] : 0) +
						(($force['bDrai'] > 0) ? $force['dDrai'] : 0); */
			// Thunder Bolt
			$force['bThun'] = $char->skills->fthun;
			#$force['sThun'] = getForce("sThun", $id);
			#$force['rThun'] = getForce("rThun", $id);
			#$force['dThun'] = getForce("dThun", $id); 
			$this->fthun = $force['bThun']; /* +
						(($force['bThun'] > 0) ? $force['sThun'] : 0) +
						(($force['bThun'] > 0) ? $force['rThun'] : 0) +
						(($force['bThun'] > 0) ? $force['dThun'] : 0); */
			// Chain Lightning
			$force['bChai'] = $char->skills->fchai;
			#$force['sChai'] = getForce("sChai", $id);
			#$force['rChai'] = getForce("rChai", $id);
			#$force['dChai'] = getForce("dChai", $id); 
			$this->fchai = $force['bChai']; /* +
						(($force['bChai'] > 0) ? $force['sChai'] : 0) +
						(($force['bChai'] > 0) ? $force['rChai'] : 0) +
						(($force['bChai'] > 0) ? $force['dChai'] : 0); */
			// Destruction
			$force['bDest'] = $char->skills->fdest;
			#$force['sDest'] = getForce("sDest", $id);
			#$force['rDest'] = getForce("rDest", $id);
			#$force['dDest'] = getForce("dDest", $id); 
			$this->fdest = $force['bDest']; /* +
						(($force['bDest'] > 0) ? $force['sDest'] : 0) +
						(($force['bDest'] > 0) ? $force['rDest'] : 0) +
						(($force['bDest'] > 0) ? $force['dDest'] : 0); */
			
			// Deadly Sight
			$force['bDead'] = $char->skills->fdead;
			#$force['sDead'] = getForce("sDead", $id);
			#$force['rDead'] = getForce("rDead", $id);
			#$force['dDead'] = getForce("dDead", $id); 
			$this->fdead = $force['bDead']; /* +
						(($force['bDead'] > 0) ? $force['sDead'] : 0) +
						(($force['bDead'] > 0) ? $force['rDead'] : 0) +
						(($force['bDead'] > 0) ? $force['dDead'] : 0);	*/	
						
			// Meister ------------------------------------------------------
			// Vitalize
			$force['bRvtl'] = $char->skills->frvtl;
			#$force['sRvtl'] = getForce("sRvtl", $id);
			#$force['rRvtl'] = getForce("rRvtl", $id);
			#$force['dRvtl'] = getForce("dRvtl", $id); 
			$this->frvtl = $force['bRvtl']; /* +
						(($force['bRvtl'] > 0) ? $force['sRvtl'] : 0) +
						(($force['bRvtl'] > 0) ? $force['rRvtl'] : 0) +
						(($force['bRvtl'] > 0) ? $force['dRvtl'] : 0);	*/		
			// Energize
			$force['bTnrg'] = $char->skills->ftnrg;
			#$force['sTnrg'] = getForce("sTnrg", $id);
			#$force['rTnrg'] = getForce("rTnrg", $id);
			#$force['dTnrg'] = getForce("dTnrg", $id); 
			$this->ftnrg = $force['bTnrg']; /* +
						(($force['bTnrg'] > 0) ? $force['sTnrg'] : 0) +
						(($force['bTnrg'] > 0) ? $force['rTnrg'] : 0) +
						(($force['bTnrg'] > 0) ? $force['dTnrg'] : 0); */
		}				
    
    function User ($id)
	{	
        $user = $this->JediUserChars->get($id);
        $user->skills = $this->JediUserSkills->get($id);
		#$this->dbconn = $dbconn;
		#$dbconn->query("SELECT * FROM user WHERE id = '$id' LIMIT 1");
		#$feld = mysql_fetch_array($dbconn->result);
		$user->id = $user->userid;
		$user->nick = $user->username;
		$user->level = $user->skills->level;
		$user->xp = $user->skills->xp;
		// Waffenwerte
		$waffe_id = $user->item_hand;
		$waffe = $this->JediItemsWeapons->get($waffe_id);
		
		if ($waffe == null)
    		$user->wmin = 1;
		else
			$user->wmin = $waffe->mindmg;
		
		if ($waffe == null)
			$user->wmax = ceil($user->level/3);
		else
			$user->wmax = $waffe->maxdmg;
						
		$user->abis = $this->Abis($id);	// Abis auslesen und speichern
		$user->mights = $this->Mights($id); // Mights auslesen und speichern
		$user->health = $user->health;
		$user->mana = $user->mana;
		#$this->bxp = getStat("xp", $id);
		#$this->bpxp = getStat("pxp", $id);
        #$this->blxp = getStat("lxp", $id);
        return $user;
    }
    
    function Kampf()  // Konstruktor
    {
        $this->team = array();  // Team in array aufgeteilt
        $this->team[0] = array(); // Charaktere in team
        $this->rteam[0]['dschnitt'] = null;  // 0 Abi Klasse erstellt -> schnitt aller Chars/Team
        $this->rteam[0]['summe'] = null; // 0 Abi Klasse erstellt -> summe aller Chars/Team
        $this->team[1] = array(); // charaktere in team 2
        $this->rteam[1]['dschnitt'] = null; // 0 Abi Klasse erstellt -> schnitt aller Chars/Team
        $this->rteam[1]['summe'] = null;   // 0 Abi Klasse erstellt -> summe aller Chars/Team
    }
    
    // Mitglied einem Team zuordnen
    function addTeam($team, $class_user)  
    {
        array_push($this->team[$team], $class_user);
    }
    
    // nutzlose test funktion
    function showTest()
    {
        debug($this->team[0]);
    }
    
    // Schnitt der Teams berechnen
    function getAverage()
    {
        $this->getAverageTeam(0);
        $this->getAverageTeam(1);
    }
    
    // Schnitt eines Teams berechnen
    function getAverageTeam($team)
    {
        $tempCount = 0;
        for ($i=0; $i<count($this->team[$team]); $i++)
        {
        if ($this->team[$team][$i]->health <= 0) continue;
        $this->rteam[$team]['dschnitt']->skills->cns += $this->team[$team][$i]->skills->cns;
        $this->rteam[$team]['dschnitt']->skills->agi += $this->team[$team][$i]->skills->agi;
        $this->rteam[$team]['dschnitt']->skills->spi += $this->team[$team][$i]->skills->spi;
        $this->rteam[$team]['dschnitt']->skills->tac += $this->team[$team][$i]->skills->tac;
        $this->rteam[$team]['dschnitt']->skills->dex += $this->team[$team][$i]->skills->dex;
        $this->rteam[$team]['dschnitt']->skills->lsa += $this->team[$team][$i]->skills->lsa;
        $this->rteam[$team]['dschnitt']->skills->lsd += $this->team[$team][$i]->skills->lsd;
        $tempCount++;
        }
        
        if ($tempCount == 0) return;
        $this->rteam[$team]['dschnitt']->skills->cns /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->agi /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->spi /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->tac /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->dex /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->lsa /= $tempCount;
        $this->rteam[$team]['dschnitt']->skills->lsd /= $tempCount;
    }
    
    // Summe der Teams berechnen
    function getStumme()
    {
        $this->getSummeTeam(0);
        $this->getSummeTeam(1);
    }
    
    // Summe eines Teams berechnen
    function getSummeTeam($team)
    {
        for ($i=0; $i<count($this->team[$team]); $i++)
        {
        if ($this->team[$team][$i]->health <= 0) continue;
        $this->rteam[$team]['summe']->skills->cns += $this->team[$team][$i]->skills->cns;
        $this->rteam[$team]['summe']->skills->agi += $this->team[$team][$i]->skills->agi;
        $this->rteam[$team]['summe']->skills->spi += $this->team[$team][$i]->skills->spi;
        $this->rteam[$team]['summe']->skills->tac += $this->team[$team][$i]->skills->tac;
        $this->rteam[$team]['summe']->skills->dex += $this->team[$team][$i]->skills->dex;
        $this->rteam[$team]['summe']->skills->lsa += $this->team[$team][$i]->skills->lsa;
        $this->rteam[$team]['summe']->skills->lsd += $this->team[$team][$i]->skills->lsd;
        }
    } 
    
    // checkt ob team am leben
    function checkAlive($team)
    {
        $alive = 0;
        for ($i=0; $i<count($this->team[$team]); $i++)
        {
            #if(!is_a($this->team[$team][$i], "User")) continue;
            if ($this->team[$team][$i]->health <= 0) continue;
            $alive++;
        }
        return $alive;
    }
    
    function getAliveID($team)
    {	
        $new = array();
        for ($i=0; $i<count($this->team[$team]); $i++)
        {
            if(!is_a($this->team[$team][$i], "User")) continue;
            if ($this->team[$team][$i]->health <= 0) continue;
            array_push($new, $i);
        }
        $zufall = rand(0, count($new)-1);
        return $new[$zufall];
    }
    
    // aktionen berechnen pro fighter
    function getAktionen($id, $id2)
    {
        $act = $this->team[$id][$id2]->skills->agi*2.0 + (($this->team[$id][$id2]->skills->spi + 
                $this->team[$id][$id2]->skills->lsa) / 2)*1.3 + $this->team[$id][$id2]->skills->cns*0.3 + $this->team[$id][$id2]->skills->tac*0.1;
        if ($this->checkAlive($id) < $this->checkAlive($this->opposite($id)))   
            $faktor = ($this->checkAlive($this->opposite($id)) / $this->checkAlive($id))*0.8;
        else
            $faktor = 1;	
        return ceil($act * $faktor);          
    }
    
    // berechnen wer nun dran ist
    function setActions()
    {
        $save = array();
        $save['id'] = array();
        $save['team'] = array();
        $this->aktion = null;
        $this->aktion = array();
        $this->aktion['id'] = array();
        $this->aktion['team'] = array();
        $this->aktion['wert'] = array();

        for ($i=0; $i<count($this->team[0]); $i++)  // team 1 durchgehen
        {
            if ($this->team[0][$i]->health <= 0) continue;
            #if (!is_a($this->team[0][$i], "User")) continue;
            array_push($this->aktion['id'], $i);
            array_push($this->aktion['team'], 0);
            array_push($this->aktion['wert'], $this->getAktionen(0, $i));   
             
            for ($a=0; $a<$this->getAktionen(0, $i); $a++) 
            {
                array_push($save['id'], $i);
                array_push($save['team'], 0);
            }
        } 
        for ($i=0; $i<count($this->team[1]); $i++)  // team 2 druchgehen
        {
            if ($this->team[1][$i]->health <= 0) continue;
            #if (!is_a($this->team[1][$i], "User")) continue;
            array_push($this->aktion['id'], $i);
            array_push($this->aktion['team'], 1);
            array_push($this->aktion['wert'], $this->getAktionen(1, $i)); 
            for ($a=0; $a<$this->getAktionen(1, $i); $a++) 
            {
                array_push($save['id'], $i);
                array_push($save['team'], 1);
            }     
        } 
        
        $temp = count($save['id']);
        $temp = rand(0, $temp-1);
        
        $back['id'] = $save['id'][$temp];
        $back['team'] = $save['team'][$temp];
        return $back; // id und team-id werden zurueckgegeben
    }
    
    // erfolgreich, wenn a gegen b gewinnt.
    function istErfolgreich($a, $b)
    {
        $a = round($a, 0);
        $b = round($b, 0);
        $zufall = rand(0, $a + $b);
        if ($zufall < $a) return true;
        else return false;
    }
    
    // offensiv wert, nahkampf
    function schlagOffensiv($id, $id2)
    {
        $off = ($this->team[$id][$id2]->skills->lsa*1.5 + $this->team[$id][$id2]->skills->dex + $this->team[$id][$id2]->skills->cns*0.3 + $this->team[$id][$id2]->skills->agi 
                + $this->team[$id][$id2]->skills->tac*1.8);
        return ceil($off);        
    }
    
    // offensiv wert, macht
    function machtOffensiv($id, $id2)
    {
        $off = ($this->team[$id][$id2]->skills->itl*1.3 + $this->team[$id][$id2]->skills->spi + $this->team[$id][$id2]->skills->cns*0.2 + $this->team[$id][$id2]->skills->agi*0.7);
        return ceil($off);
    }
    
    // defensiv werr, nahkampf
    function schlagDefensiv($id, $id2)
    {
        $def = ($this->team[$id][$id2]->skills->lsd*1.8 + $this->team[$id][$id2]->skills->dex + $this->team[$id][$id2]->skills->cns*0.3 + $this->team[$id][$id2]->skills->agi*1.5 + $this->team[$id][$id2]->skills->tac*1.2);
        return ceil($def*1.7);        
    }
    
    // defensiv werr, macht
    function machtDefensiv($id, $id2)
    {
        $def = ($this->team[$id][$id2]->skills->spi*1.8 + $this->team[$id][$id2]->skills->agi*1.5 + $this->team[$id][$id2]->skills->cns*0.2) * (($this->team[$id][$id2]->skills->tac / 70) + 1)*2 ;
        return ceil($def*1.8);
    }
    
    // nahkampf ermuedung (koennte auch allgemeiner gestaltet werden)
    function muedeNah($id, $id2, $cns, $agi, $dex, $lsa, $lsd)
    {
        $this->team[$id][$id2]->skills->cns *= $cns;
        $this->team[$id][$id2]->skills->agi *= $agi;
        $this->team[$id][$id2]->skills->dex *= $dex;
        $this->team[$id][$id2]->skills->lsa *= $lsa;
        $this->team[$id][$id2]->skills->lsd *= $lsd;
    }
    
    // macht ermuedung (koennte auch allgemeiner gestaltet werden)
    function muedeMacht($id, $id2, $cns, $agi, $spi, $itl, $tac)
    {
        $this->team[$id][$id2]->skills->cns *= $cns;
        $this->team[$id][$id2]->skills->agi *= $agi;
        $this->team[$id][$id2]->skills->spi *= $spi;
        $this->team[$id][$id2]->skills->itl *= $itl;
        $this->team[$id][$id2]->skills->tac *= $tac;
    }
    
    
    function retAbi($id, $id2, $abi)
    {
        return $this->team[$id][$id2]->$abi;
    }
    
    function retForce($id, $id2, $force)
    {
        return $this->team[$id][$id2]->skills->$force;
    }
    
    function retTired($id, $id2, $abi)
    {
        $cns = $this->retAbi($id, $id2, "cns");
        $abi = $this->retAbi($id, $id2, $abi);
        return 1 - ceil($abi)/ceil($cns*2 + $abi);
    }
    
    
    // team id vertauschen
    function opposite($id)
    {
        if ($id == 0) return 1;
        else return 0;
    }
    
    // Gegner nach zufall aussuchen
    function getAliveEnemys($id)
    {
        $save = array();
        for ($i=0; $i<count($this->team[$id]); $i++)
        {
        if ($this->team[$id][$i] != "proj")		
            if ($this->team[$id][$i]->health <= 0) continue;
        array_push($save, $i);
        }
        return $save[rand(0, count($save)-1)];
    }
    
    // gesamtanzahl mächte
    function alleMachtpunkte($id, $id2)
    {
        $punkte = $this->team[$id][$id2]->skills->fspee +
                    $this->team[$id][$id2]->skills->fjump+
                    $this->team[$id][$id2]->skills->fpull +
                    $this->team[$id][$id2]->skills->fpush +
                    $this->team[$id][$id2]->skills->fseei +
                    $this->team[$id][$id2]->skills->fsabe +
                    $this->team[$id][$id2]->skills->fpers +
                    $this->team[$id][$id2]->skills->fproj +
                    $this->team[$id][$id2]->skills->fconf +
                    $this->team[$id][$id2]->skills->fblin +
                    $this->team[$id][$id2]->skills->fheal +
                    $this->team[$id][$id2]->skills->fteam +
                    $this->team[$id][$id2]->skills->fprot +
                    $this->team[$id][$id2]->skills->fabso +
                    $this->team[$id][$id2]->skills->fthro +
                    $this->team[$id][$id2]->skills->frage +
                    $this->team[$id][$id2]->skills->fgrip +
                    $this->team[$id][$id2]->skills->fdrai +
                    $this->team[$id][$id2]->skills->fthun +
                    $this->team[$id][$id2]->skills->fchai +
                    $this->team[$id][$id2]->skills->fdest +
                    $this->team[$id][$id2]->skills->fdead +
                    $this->team[$id][$id2]->skills->frvtl +
                    $this->team[$id][$id2]->skills->ftnrg;
            return $punkte;		 
    }
    
    // Macht oder Nahkampf
    function typeOfAttack($id, $id2)
    {
            if ($this->alleMachtpunkte($id, $id2) == 0)
            {
                return false;
            }	
            else
            {
                $macht = $this->team[$id][$id2]->skills->spi + $this->team[$id][$id2]->skills->itl;
                $nahkampf = $this->team[$id][$id2]->skills->dex + $this->team[$id][$id2]->skills->lsa;
                $zufall = rand(0, $macht + $nahkampf);
                if ($zufall < $macht)
                    return true;
                else
                    return false;	
            }
    }
    
    // Macht aussuchen
    function getMacht($id, $id2)
    {
        $macht = array();
        debug($this->team[0]);
        foreach($this->team[$id][$id2]->Mights as $key => $value) 
        {
            #if ($key == "dbconn") continue;
            for ($i=0; $i<$value; $i++)
            {
                array_push($macht, $key);
            }
        }
        
        $random = rand (0, count($macht)-1);
        return $macht[$random];
    }
    
    function machtPunkte($id, $id2, $macht)
    {
        $x = $this->team[$id][$id2]->Mights->$macht;
        $p = ceil(1/(9+95*exp(-$x/35))*(100 + $this->team[$id][$id2]->skills->itl) * 10);
        return $p;
    }
    
    function machtText($short)
    {
        switch($short)
        {
            case "fspee": return "Force Speed";
            case "fjump": return "Force Jump";
            case "fpull": return "Force Pull";
            case "fpush": return "Force Push";
            case "fseei": return "Force Seeing";
            case "fsabe": return "Force Saber Throw";
            case "fpers": return "Force Persuation";
            case "fproj": return "Force Projection";
            case "fblin": return "Force Blind";
            case "fconf": return "Force Confuse";
            case "fheal": return "Force Heal";
            case "fteam": return "Force Team Heal";
            case "fprot": return "Force Protect";
            case "fabso": return "Force Absorb";
            case "fthro": return "Force Throw";
            case "frage": return "Force Rage";
            case "fgrip": return "Force Grip";
            case "fdrai": return "Force Drain";
            case "fthun": return "Force Thunder";
            case "fchai": return "Force Thunder Bolt";
            case "fdest": return "Force Destruction";
            case "fdead": return "Force Deadly Sight";
            case "frvtl": return "Force Revitalize";
            case "ftnrg": return "Force Team Energize";
        }
    }
    
    function getColor($might)
    {
        switch($might)
        {
            case "fspee": ;
            case "fjump": ;
            case "fpull": ;
            case "fpush": ;
            case "fseei": ;
            case "fsabe": return "blue";
            case "fpers": ;
            case "fproj": ;
            case "fblin": ;
            case "fconf": ;
            case "fheal": ;
            case "fteam": ;
            case "fprot": ;
            case "fabso": return "green";
            case "fthro": ;
            case "frage": ;
            case "fgrip": ;
            case "fdrai": ;
            case "fthun": ;
            case "fchai": ;
            case "fdest": ;
            case "fdead": return "red";
            case "frvtl": ;
            case "ftnrg": return "orange";
            
        }
    }
    
    function getFailedColor($might)
    {
        switch($might)
        {
            case "fspee": ;
            case "fjump": ;
            case "fpull": ;
            case "fpush": ;
            case "fseei": ;
            case "fsabe": return "bf";
            case "fpers": ;
            case "fproj": ;
            case "fblin": ;
            case "fconf": ;
            case "fheal": ;
            case "fteam": ;
            case "fprot": ;
            case "fabso": return "gf";
            case "fthro": ;
            case "frage": ;
            case "fgrip": ;
            case "fdrai": ;
            case "fthun": ;
            case "fchai": ;
            case "fdest": ;
            case "fdead": return "rf";
            case "frvtl": ;
            case "ftnrg": return "of";
            
        }
    }
    
    
    function notOffensivCast($cast, $team, &$var)
    {
        switch($cast)
        {
            case "fseei": 
            case "fspee": 
            case "fjump": 
            case "fpers": 
            case "froj": 
            case "fblin": 
            case "fconf": 
            case "fheal": 
            case "fteam": 
            case "fprot": 
            case "fabso": 
            case "ftnrg": 
            case "frvtl": 	$var = $this->getAliveID($team);
                            return true;
            
        }
        return false;
    }
    
        function getColorFail($might)
    {
        switch($might)
        {
            case "fspee": ;
            case "fjump": ;
            case "fpull": ;
            case "fpush": ;
            case "fseei": ;
            case "fsabe": return "lightblue";
            case "fpers": ;
            case "fproj": ;
            case "fblin": ;
            case "fconf": ;
            case "fheal": ;
            case "fteam": ;
            case "fprot": ;
            case "fabso": return "lightgreen";
            case "fthro": ;
            case "frage": ;
            case "fgrip": ;
            case "fdrai": ;
            case "fthun": ;
            case "fchai": ;
            case "fdest": ;
            case "fdead": return "#FF9999";
            case "frvtl": ;
            case "ftnrg": return "yellow";
            
        }
    }
    
    function getSpeed($id, $id2)
    {
        return ceil(1/(9+95*exp(-$this->team[$id][$id2]->skills->fspee/35)) * (300 + $this->team[$id][$id2]->skills->itl));
    }
    function getJump($id, $id2)
    {
        return ceil(1/(9+95*exp(-$this->team[$id][$id2]->skills->fjump/35)) * (300 + $this->team[$id][$id2]->skills->itl));
    }
    function getRage($id, $id2)
    {
        return ceil(1/(9+95*exp(-$this->team[$id][$id2]->skills->frage/35)) * (1000 + $this->team[$id][$id2]->skills->itl));
    }
    
    function killProjection($id)
    {
        $new = array();
        for ($i=0; $i<(count($this->team[$id])-1); $i++)
        {
            $new[$i] = $this->team[$id][$i];
        }
        $this->team[$id] = &$new;
    }
    
    function countProjections($id)
    {
        $count = 0;
        for ($i=0; $i<count($this->team[$id]); $i++)
        {
            if (!is_a($this->team[$id][$i], "User")) $count++;
        }
        return $count;
    }
    
    function deleteObject($id)
    {
        $test = false;
        for ($i=0; $i<count($this->team[$id]); $i++)
        {	
            if (get_class($this->team[$id][$i]) == "user") continue;
            if ($this->team[$id][$i] == "proj") continue;
            $test = true;
        }
        echo $test. " ";
    }
    
    function output($id)
    {
        echo "<font color=orange>==______________________________________==<br>";
        for ($i=0; $i<count($this->team[$id]); $i++)
        {
            if (!is_a($this->team[$id][$i], "User")) 
                echo $this->team[$id][$i]." -> ".get_class($this->team[$id][$i])."<br>";
            else
                echo $this->team[$id][$i]->nick." -> ".get_class($this->team[$id][$i])."<br>";	
        }
        echo "==______________________________________==</font><br>";
    }
    
    function dmgWeapon($a, $v)
    {
        $dex_diff = $a->skills->dex - $v->skills->dex;
        $w_mittel = round(($a->wmin + $a->wmax) / 2,0);
        if ($dex_diff < 0)
        {
            $max = rand($a->wmin, $w_mittel);
        } 
        else
        {
            $max = rand($w_mittel, $a->wmax);
        }
        $cns_diff = $a->skills->cns - $v->skills->cns;
        if ($cns_diff < 0)
        {
            $max = rand($a->wmin, $max);
        } 
        else
        {
            $max = rand($max, $w_mittel);
        }
        return $max;
    }
    
    // Hauptfunktion -> Kampfablauf
    function runFight()
    {	
        $fightLog = "";
        // Kampfbedingung, beide Teams mind. 1 Kaempfer
        while ($this->checkAlive(0) > 0 && $this->checkAlive(1) > 0)
        {
        $dran = $this->setActions();  // wer dran ist
        $aid = $dran['id'];           // id angreifer
        $tid = $dran['team'];         // team-id angreifer
        $tid2 = $this->opposite($tid);  // team-id verteidiger
        $aid2 = $this->getAliveEnemys($tid2); // id verteidiger
        
        $machtNow = $this->getMacht($tid, $aid);
        $macht1 = $this->machtPunkte($tid, $aid, $machtNow);
        
        $att_type = $this->typeOfAttack($tid, $aid);
        if (($macht1/3) > $this->team[$tid][$aid]->mana)
        {
            if (rand(1,2) == 1 && $att_type == true)
            {
                $att_type = false;
            }
        }
        if (!$att_type)		// Art der attacke -> Nahkampf ?
        {	
            if ($this->team[$tid2][$aid2] == "proj")
            {
                $fightLog .= sprintf(getNah("hitno","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick);
                $this->killProjection($tid2);
                
                if ($this->team[$tid][$aid]->rage > 0)
                    $this->team[$tid][$aid]->rage--;
                $this->muedeNah($tid, $aid, 0.88, $this->retTired($tid, $aid, "agi"), 0.90, $this->retTired($tid, $aid, "lsa"), 1);   // ermuedung angreifer
            }
            // Erfolgreich zugeschlagen?
            elseif ($this->istErfolgreich($this->schlagOffensiv($tid, $aid), 
                $this->schlagDefensiv($tid2, $aid2)))
            {
                $dmg = $this->dmgWeapon($this->team[$tid][$aid], $this->team[$tid2][$aid2]);  // Schaden berechnen
                if ($this->team[$tid][$aid]->blind > 0)
                {
                    $fightLog .= sprintf(getNah("hitn","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, "blinded");
                    $this->team[$tid][$aid]->blind--;
                    if ($this->team[$tid][$aid]->rage > 0)
                    $this->team[$tid][$aid]->rage--;
                    if ($this->team[$tid][$aid]->blind == 0)
                        $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                }
                elseif ($this->team[$tid][$aid]-> conf > 0)
                {
                    $fightLog .= sprintf(getNah("hitn","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, "confused");
                    $this->team[$tid][$aid]->conf--;
                    if ($this->team[$tid][$aid]->rage > 0)
                    $this->team[$tid][$aid]->rage--;
                    if ($this->team[$tid][$aid]->conf == 0)
                        $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                }
                elseif ($this->team[$tid2][$aid2]->pers > 0)
                {
                    $fightLog .= sprintf(getNah("hitn","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, "off target");
                    $this->team[$tid2][$aid2]->pers--;
                    if ($this->team[$tid][$aid]->rage > 0)
                    $this->team[$tid][$aid]->rage--;
                    if ($this->team[$tid2][$aid2]->pers == 0)
                        $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                }
                elseif ($this->team[$tid2][$aid2]->prot > 0)
                {
                    if ($this->team[$tid][$aid]->rage > 0)
                    {
                        $this->team[$tid][$aid]->rage--;
                        $dmg += $this->team[$tid][$aid]->rageValue;
                    }	
                    if ($this->team[$tid2][$aid2]->prot >= $dmg)
                    {
                        $fightLog .= sprintf(getNah("hitprot","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                        $this->team[$tid2][$aid2]->prot -= $dmg;
                    }
                    else
                    {
                        $rest = $dmg - $this->team[$tid2][$aid2]->prot;
                        $fightLog .= sprintf(getNah("hitp","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $rest);
                        $this->team[$tid2][$aid2]->prot = 0;
                    }	
                }
                else
                {
                    if ($this->team[$tid][$aid]->rage > 0)
                    {
                        $this->team[$tid][$aid]->rage--;
                        $dmg += $this->team[$tid][$aid]->rageValue;
                        $fightLog .= sprintf(getNah("hitr","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg, $this->team[$tid][$aid]->rageValue);
                    }
                    else
                    {
                        $fightLog .= sprintf(getNah("hit","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                    }	
                    $this->team[$tid2][$aid2]->health -= $dmg;  // Schaden abziehen
                    
                    if ($this->team[$tid2][$aid2]->health <= 0) // tot ?
                    $fightLog .= $this->team[$tid2][$aid2]->nick." has been knocked out.<br>";  
                }
                
                $this->muedeNah($tid, $aid, 0.90, 0.90, 0.93, 0.92, 1);     // ermuedung angreifer
                $this->muedeNah($tid2, $aid2, 0.89, 0.95, 0.85, 1, $this->retTired($tid2, $aid2, "lsd"));   // ermuedung verteidiger
            }
            else  // fehlgeschlagener angriff
            {
                //echo "<font color=grey>".$this->team[$tid][$aid]->nick." failed to hit ".$this->team[$tid2][$aid2]->nick.".</font><br>";
                $fightLog .= sprintf(getNah("hitf","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick);
                
                if ($this->team[$tid2][$aid2]->jump > 0)
                {
                    $this->team[$tid2][$aid2]->jump--;
                    if ($this->team[$tid2][$aid2]->jump == 0)
                    {
                        $fightLog .= sprintf(getFor("jumpl","m"), $this->team[$tid2][$aid2]->nick);
                        $this->team[$tid2][$aid2]->skills->agi /= $this->team[$tid2][$aid2]->jumpValue;
                        $this->team[$tid2][$aid2]->skills->lsd /= $this->team[$tid2][$aid2]->jumpValue;
                        $this->team[$tid2][$aid2]->jumpValue = 1;
                    }	
                }
                $this->muedeNah($tid, $aid, 0.83, $this->retTired($tid, $aid, "agi"), 0.80, $this->retTired($tid, $aid, "lsa"), 1);   // ermuedung angreifer
                $this->muedeNah($tid2, $aid2, 0.99, 0.97, 0.98, 1, 0.98);  // erfmuedung verteidiger
            }   
        }
        else		// Machteinsatz !!
        {	
            
            
            $oINT = $this->team[$tid][$aid]->skills->itl;
            //$gINT = $this->team[$tid2][$aid2]->skills->itl;
            $manaVerbrauch = floor($macht1/3);
            if ($this->team[$tid][$aid]->mana < $manaVerbrauch)
            {
                $fightLog .= "<font color=#FF9999>".$this->team[$tid][$aid]->nick." failed to cast ".$this->machtText($machtNow)." because of no mana. (".$manaVerbrauch.")</font><br>";
            }
            elseif ($this->team[$tid2][$aid2] == "proj" && !$this->notOffensivCast($machtNow, $tid2, $aid2)) 
            {
                switch ($machtNow)
                {
                    case "fpull":	$fightLog .= sprintf(getFor("pullp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fpush":	$fightLog .= sprintf(getFor("pushp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fsabe":	$fightLog .= sprintf(getFor("sabep","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fthro":	$fightLog .= sprintf(getFor("throp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fgrip":	$fightLog .= sprintf(getFor("gripp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fdrai":	$fightLog .= sprintf(getFor("draip","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fthun":	$fightLog .= sprintf(getFor("thunp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fchai":	$fightLog .= sprintf(getFor("chaip","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fdead":	$fightLog .= sprintf(getFor("deadp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;
                    case "fdest":	$fightLog .= sprintf(getFor("destp","m"), $this->team[$tid][$aid]->nick, $manaVerbrauch);
                                    $this->killProjection($tid2);
                                    break;		
                                    
                }
                $this->team[$tid][$aid]->mana -= $manaVerbrauch;
                $this->muedeMacht($tid, $aid, 0.88, 0.80, 0.81, 0.70, 1);   // ermuedung angreifer
            }	
                // Erfolgreich zugeschlagen?
            elseif ($this->istErfolgreich($this->machtOffensiv($tid, $aid), 
                $this->machtDefensiv($tid2, $aid2)))
            {
                $this->team[$tid][$aid]->mana -= $manaVerbrauch;
                // einzelne Machtbehandlung
                switch ($machtNow)
                {
                    case "fpull"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pullb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pullc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pullo","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("pulla","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $fightLog .= sprintf(getFor("pulla2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $prozent = 1 - $macht1/100;
                                            $prozent2 = 1 + $macht1/100;
                                            $this->team[$tid][$aid]->skills->lsa = round($this->team[$tid][$aid]->skills->lsa * $prozent2);
                                            $this->team[$tid2][$aid2]->skills->lsd = round($this->team[$tid2][$aid2]->skills->lsd * $prozent);
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("pull","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fpush"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pushb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pushc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("pusho","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("pusha","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $fightLog .= sprintf(getFor("pusha2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $prozent = 1 - $macht1/100;
                                            $prozent2 = 1 + $macht1/100;
                                            $this->team[$tid][$aid]->skills->lsd = round($this->team[$tid][$aid]->skills->lsd * $prozent2);
                                            $this->team[$tid2][$aid2]->skills->lsa = round($this->team[$tid2][$aid2]->skills->lsa * $prozent);
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("push","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fsabe"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("sabeb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("sabec","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("sabeo","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->prot > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->prot >= $macht1)
                                            {
                                                $fightLog .= sprintf(getFor("sabeprot","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                                $this->team[$tid2][$aid2]->prot -= $macht1;
                                            }
                                            else
                                            {
                                                $rest = $macht1 - $this->team[$tid2][$aid2]->prot;
                                                $this->team[$tid2][$aid2]->health -= $rest;
                                                $fightLog .= sprintf(getFor("sabeprot2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $rest);
                                                $this->team[$tid2][$aid2]->prot = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("sabe","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fthro"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("throb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("throc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("throo","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->prot > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->prot >= $macht1)
                                            {
                                                $fightLog .= sprintf(getFor("throprot","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                                $this->team[$tid2][$aid2]->prot -= $macht1;
                                            }
                                            else
                                            {
                                                $rest = $macht1 - $this->team[$tid2][$aid2]->prot;
                                                $this->team[$tid2][$aid2]->health -= $rest;
                                                $fightLog .= sprintf(getFor("throprot2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $rest);
                                                $this->team[$tid2][$aid2]->prot = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("thro","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fgrip"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("gripb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("gripc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("gripo","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("gripa","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $fightLog .= sprintf(getFor("gripa2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("grip","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fthun"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("thunb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("thunc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("thuno","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("thuna","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $fightLog .= sprintf(getFor("thun2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {	
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("thun","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fchai"	: 	$punkte = ceil($macht1*0.8);
                                        //$toChain = $this->checkAlive($tid);
                                        if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("chaib","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("chaic","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]->mana < (2 * $manaVerbrauch) || $this->checkAlive($tid2) < 2)
                                        {
                                            $this->team[$tid][$aid]->mana += $manaVerbrauch;
                                            $machtP = $this->machtPunkte($tid, $aid, "fthun");
                                            $this->team[$tid2][$aid2]->health -= $machtP;
                                            $this->team[$tid][$aid]->mana -= floor($manaVerbrauch/3);
                                            if ($this->team[$tid][$aid]->blind > 0)
                                            {
                                                $fightLog .= sprintf(getFor("thunb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid][$aid]->blind--;
                                                if ($this->team[$tid][$aid]->blind == 0)
                                                    $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                            }
                                            elseif ($this->team[$tid][$aid]-> conf > 0)
                                            {
                                                $fightLog .= sprintf(getFor("thunc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid][$aid]->conf--;
                                                if ($this->team[$tid][$aid]->conf == 0)
                                                    $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                            }
                                            else
                                            {
                                                $fightLog .= sprintf(getFor("thun","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $machtP);
                                            }	
                                        }
                                        else
                                        {	
                                            $fightLog .= sprintf(getFor("chai","m"), $this->team[$tid][$aid]->nick);
                                            $this->team[$tid][$aid]->mana += $manaVerbrauch;
                                            for ($i=0; $i<count($this->team[$tid2]); $i++)
                                            {
                                                if ($this->team[$tid][$aid]->mana < $manaVerbrauch) break;
                                                elseif (!is_a($this->team[$tid2][$i], "User")) 
                                                {
                                                    array_pop($this->team[$tid2]);
                                                    $fightLog .= sprintf(getFor("chai2p","m"), $manaVerbrauch);
                                                    break;
                                                }
                                                elseif ($this->team[$tid2][$i]->health <= 0) continue;
                                                else
                                                {
                                                    if ($this->team[$tid2][$i]->pers > 0)
                                                    {
                                                        $fightLog .= sprintf(getFor("chaio","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $manaVerbrauch);
                                                        $this->team[$tid2][$i]->pers--;
                                                        if ($this->team[$tid2][$i]->pers == 0)
                                                            $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$i]->nick);
                                                    }
                                                    elseif ($this->team[$tid2][$i]->abso > 0)
                                                    {
                                                        if ($this->team[$tid2][$i]->abso >= $manaVerbrauch)
                                                        {
                                                            $fightLog .= sprintf(getFor("chaia","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $manaVerbrauch);
                                                            $this->team[$tid2][$i]->abso -= $manaVerbrauch;
                                                        }
                                                        else
                                                        {
                                                            $rest = $manaVerbrauch - $this->team[$tid2][$i]->abso;
                                                            $dmg = $rest * 3;
                                                            $this->team[$tid2][$i]->health -= $dmg;
                                                            $fightLog .= sprintf(getFor("chaia2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $dmg);
                                                            $this->team[$tid2][$i]->abso = 0;
                                                            if ($this->team[$tid2][$i]->health <= 0 && $i != $aid2) // tot ?
                                                                $fightLog .= $this->team[$tid2][$i]->nick." has been knocked out.<br>";
                                                        }	
                                                    }
                                                    else
                                                    {
                                                        $this->team[$tid2][$i]->health -= $punkte;
                                                        $fightLog .= sprintf(getFor("chai2","m"), $this->team[$tid2][$i]->nick, $punkte);
                                                        $this->team[$tid][$aid]->mana -= $manaVerbrauch;
                                                        $zw = (100 - rand(5,15))/100;
                                                        $punkte = round($punkte * $zw,0);
                                                        if ($this->team[$tid2][$i]->health <= 0 && $i != $aid2) // tot ?
                                                                $fightLog .= $this->team[$tid2][$i]->nick." has been knocked out.<br>";
                                                    }
                                                }
                                            }
                                        }
                                        break;
                    case "fdest"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("destb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("destc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("desto","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("desta","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $fightLog .= sprintf(getFor("desta2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("dest","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;
                    case "fdead"	: 	$punkte = ceil($macht1*0.8);
                                        //$toChain = $this->checkAlive($tid);
                                        if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("deadb","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("deadc","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]->mana < (2 * $manaVerbrauch) || $this->checkAlive($tid2) < 2)
                                        {
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $fightLog .= sprintf(getFor("dead3","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                                
                                        }
                                        else
                                        {	
                                            $fightLog .= sprintf(getFor("dead","m"), $this->team[$tid][$aid]->nick);
                                            $this->team[$tid][$aid]->mana += $manaVerbrauch;
                                            for ($i=0; $i<count($this->team[$tid2]); $i++)
                                            {
                                                if ($this->team[$tid][$aid]->mana < $manaVerbrauch) break;
                                                elseif (!is_a($this->team[$tid2][$i], "User")) 
                                                {
                                                    array_pop($this->team[$tid2]);
                                                    $fightLog .= sprintf(getFor("dead2p","m"), $manaVerbrauch);
                                                    break;
                                                }
                                                elseif ($this->team[$tid2][$i]->health <= 0) { continue; }
                                                else
                                                {
                                                    if ($this->team[$tid2][$i]->pers > 0)
                                                    {
                                                        $fightLog .= sprintf(getFor("deado","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $manaVerbrauch);
                                                        $this->team[$tid2][$i]->pers--;
                                                        if ($this->team[$tid2][$i]->pers == 0)
                                                            $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$i]->nick);
                                                    }
                                                    elseif ($this->team[$tid2][$i]->abso > 0)
                                                    {
                                                        if ($this->team[$tid2][$i]->abso >= $manaVerbrauch)
                                                        {
                                                            $fightLog .= sprintf(getFor("deada","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $manaVerbrauch);
                                                            $this->team[$tid2][$i]->abso -= $manaVerbrauch;
                                                        }
                                                        else
                                                        {
                                                            $rest = $manaVerbrauch - $this->team[$tid2][$i]->abso;
                                                            $dmg = $rest * 3;
                                                            $this->team[$tid2][$i]->health -= $dmg;
                                                            $fightLog .= sprintf(getFor("deada2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$i]->nick, $dmg);
                                                            $this->team[$tid2][$i]->abso = 0;
                                                            if ($this->team[$tid2][$i]->health <= 0 && $i != $aid2) // tot ?
                                                                $fightLog .= $this->team[$tid2][$i]->nick." has been knocked out.<br>";
                                                        }	
                                                    }
                                                    else
                                                    {
                                                        $this->team[$tid2][$i]->health -= $punkte;
                                                        $fightLog .= sprintf(getFor("dead2","m"), $this->team[$tid2][$i]->nick, $punkte);
                                                        $this->team[$tid][$aid]->mana -= $manaVerbrauch;
                                                        $zw = (100 - rand(5,15))/100;
                                                        $punkte = round($punkte * $zw,0);
                                                        if ($this->team[$tid2][$i]->health <= 0 && $i != $aid2) // tot ?
                                                                $fightLog .= $this->team[$tid2][$i]->nick." has been knocked out.<br>";
                                                    }
                                                }
                                            }
                                        }
                                        break;
                    case "fdrai"	: 	if ($this->team[$tid][$aid]->blind > 0)
                                        {
                                            $fightLog .= sprintf(getFor("draib","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->blind--;
                                            if ($this->team[$tid][$aid]->blind == 0)
                                                $fightLog .= sprintf(getFor("blinr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid][$aid]-> conf > 0)
                                        {
                                            $fightLog .= sprintf(getFor("draic","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid][$aid]->conf--;
                                            if ($this->team[$tid][$aid]->conf == 0)
                                                $fightLog .= sprintf(getFor("confr","m"), $this->team[$tid][$aid]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->pers > 0)
                                        {
                                            $fightLog .= sprintf(getFor("draio","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                            $this->team[$tid2][$aid2]->pers--;
                                            if ($this->team[$tid2][$aid2]->pers == 0)
                                                $fightLog .= sprintf(getFor("persr","m"), $this->team[$tid2][$aid2]->nick);
                                        }
                                        elseif ($this->team[$tid2][$aid2]->abso > 0)
                                        {
                                            if ($this->team[$tid2][$aid2]->abso >= $manaVerbrauch)
                                            {
                                                $fightLog .= sprintf(getFor("draia","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $manaVerbrauch);
                                                $this->team[$tid2][$aid2]->abso -= $manaVerbrauch;
                                            }
                                            else
                                            {
                                                $rest = $manaVerbrauch - $this->team[$tid2][$aid2]->abso;
                                                $dmg = $rest * 3;
                                                if ($dmg > ceil($this->team[$tid2][$aid2]->health * 0.5))
                                                    $dmg = ceil($this->team[$tid2][$aid2]->health * 0.5);
                                                $this->team[$tid2][$aid2]->health -= $dmg;
                                                $this->team[$tid][$aid]->health += ceil($dmg * 0.66);	
                                                $fightLog .= sprintf(getFor("draia2","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $dmg);
                                                $this->team[$tid2][$aid2]->abso = 0;
                                            }	
                                        }
                                        else
                                        {
                                            if ($macht1 > ceil($this->team[$tid2][$aid2]->health * 0.5))
                                                $macht1 = ceil($this->team[$tid2][$aid2]->health * 0.5);
                                            $this->team[$tid2][$aid2]->health -= $macht1;
                                            $this->team[$tid][$aid]->health += ceil($macht1 * 0.66);	
                                            $fightLog .= sprintf(getFor("drai","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                        }
                                        break;		
                    case "fpers"	: 	$low = ceil($macht1/50);
                                        $high = ceil($macht1/25);
                                        $rounds = rand($low, $high);
                                        $this->team[$tid][$aid]->pers = $rounds;
                                        //echo "<font color=green>".$this->team[$tid][$aid]->nick." persuaded the team. (".$macht1.")</font><br>";
                                        $fightLog .= sprintf(getFor("pers","m"), $this->team[$tid][$aid]->nick, $macht1);
                                    break;
                    case "fproj"	: 	$low = ceil($macht1/50);
                                        $high = ceil($macht1/25);
                                        $projs = rand($low, $high);
                                        $this->team[$tid][$aid]->proj += $projs;
                                        for ($i=0; $i<$projs; $i++)
                                            array_push($this->team[$tid], "proj");
                                        $fightLog .= sprintf(getFor("proj","m"), $this->team[$tid][$aid]->nick, $projs);
                                    break;
                    case "fblin"	: 	$low = ceil($macht1/50);
                                        $high = ceil($macht1/25);
                                        $rounds = rand($low, $high);
                                        $this->team[$tid2][$aid2]->blind = $rounds;
                                        $fightLog .= sprintf(getFor("blin","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                    break;
                    case "fconf"	: 	$low = ceil($macht1/50);
                                        $high = ceil($macht1/25);
                                        $rounds = rand($low, $high);
                                        $this->team[$tid2][$aid2]->conf = $rounds;
                                        $fightLog .= sprintf(getFor("conf","m"), $this->team[$tid][$aid]->nick, $this->team[$tid2][$aid2]->nick, $macht1);
                                    break;
                    case "fspee"	:   $punkte = $this->getSpeed($tid, $aid);
                                        $this->team[$tid][$aid]->skills->agi += $punkte;
                                        $fightLog .= sprintf(getFor("spee","m"), $this->team[$tid][$aid]->nick, $punkte);
                                    break;
                    case "fjump"	: 	$punkte = $this->getJump($tid, $aid);
                                        $temp = 1 + $punkte/100;
                                        $low = ceil($macht1/30);
                                        $high = ceil($macht1/15);
                                        $rounds = rand($low, $high);
                                        if ($this->team[$tid][$aid]->jump > 0)
                                        {
                                            $fightLog .= sprintf(getFor("jumpe","m"), $this->team[$tid][$aid]->nick, $punkte);
                                            $this->team[$tid][$aid]->jump += $rounds;
                                        }
                                        else
                                        {
                                            $this->team[$tid][$aid]->jumpValue = $temp; 
                                            $this->team[$tid][$aid]->jump = $rounds;
                                            $this->team[$tid][$aid]->skills->agi *= $temp;
                                            $this->team[$tid][$aid]->skills->lsd *= $temp;
                                            $fightLog .= sprintf(getFor("jump","m"), $this->team[$tid][$aid]->nick, $punkte);
                                        }	
                                    break;
                    case "fseei"	: 	$this->team[$tid][$aid]->conf = 0;
                                        $this->team[$tid][$aid]->blind = 0;
                                        $this->team[$tid2][$aid2]->pers = 0;
                                        if ($this->countProjections($tid2) > 0)
                                            $this->killProjection($tid2);
                                        $fightLog .= sprintf(getFor("seei","m"), $this->team[$tid][$aid]->nick, $macht1);
                                    break;
                    case "fheal"	: 	$this->team[$tid][$aid]->health += $macht1; 
                                        //echo "<font color=green>".$this->team[$tid][$aid]->nick." successfully heals his body with ".$this->machtText($machtNow)." for ".$macht1." points of health.</font><br>";
                                        $fightLog .= sprintf(getFor("heal","m"), $this->team[$tid][$aid]->nick, $macht1);
                                    break;
                    case "fteam"	: 	$punkte = ceil($macht1*0.8);
                                        $toHeal = $this->checkAlive($tid);
                                        if ($this->team[$tid][$aid]->mana < ($toHeal * $manaVerbrauch) || $toHeal == 1)
                                        {
                                            $this->team[$tid][$aid]->health += $macht1; 
                                            $fightLog .= sprintf(getFor("heal","m"), $this->team[$tid][$aid]->nick, $macht1);
                                        }
                                        else
                                        {	
                                            $fightLog .= sprintf(getFor("team","m"), $this->team[$tid][$aid]->nick);
                                            $this->team[$tid][$aid]->mana += $manaVerbrauch;
                                            for ($i=0; $i<count($this->team[$tid]); $i++)
                                            {
                                                if ($this->team[$tid][$i]->health <= 0) continue;
                                                $this->team[$tid][$i]->health += $punkte;
                                                $this->team[$tid][$aid]->mana -= $manaVerbrauch;
                                                $fightLog .= sprintf(getFor("team2","m"), $this->team[$tid][$i]->nick, $punkte);
                                            }
                                        }
                                    break;
                    case "fabso"	: 	$punkte = ceil($macht1*1.5);
                                        $this->team[$tid][$aid]->abso = ($this->team[$tid][$aid]->abso * 0.33) + $punkte;
                                        $fightLog .= sprintf(getFor("abso","m"), $this->team[$tid][$aid]->nick, $punkte);
                                    break;
                    case "fprot"	: 	$punkte = ceil($macht1*1.5);
                                        $this->team[$tid][$aid]->prot = ceil($this->team[$tid][$aid]->prot * 0.33) + $punkte;
                                        $fightLog .= sprintf(getFor("prot","m"), $this->team[$tid][$aid]->nick, $punkte);
                                    break;
                    case "frage"	:   $punkte = $this->getRage($tid, $aid);
                                        $temp = 1 + $punkte/100;
                                        $low = ceil($macht1/30);
                                        $high = ceil($macht1/15);
                                        $rounds = rand($low, $high);
                                        $hpmalus = $punkte;
                                        if ($hpmalus > $this->team[$tid][$aid]->health / 2)
                                            $hpmalus = floor($this->team[$tid][$aid]->health / 2);
                                        
                                        $this->team[$tid][$aid]->conf = 0;
                                        $this->team[$tid][$aid]->blind = 0;
                                        $this->team[$tid2][$aid2]->pers = 0;	
                                        
                                        $this->team[$tid][$aid]->rageValue = round($hpmalus/2,0); 
                                        $this->team[$tid][$aid]->rage = $rounds;
                                        $this->team[$tid][$aid]->skills->cns *= $temp;
                                        $this->team[$tid][$aid]->skills->agi *= $temp;
                                        $this->team[$tid][$aid]->skills->lsd *= $temp;
                                        $this->team[$tid][$aid]->health -= $hpmalus;
                                        $fightLog .= sprintf(getFor("rage","m"), $this->team[$tid][$aid]->nick, $hpmalus);
                                    break;
                    case "ftnrg"	: 
                                    break;
                    case "frvtl"	: 
                                    break;
                                    
                }
                //$dmg = rand($this->team[$tid][$aid]->wmin, $this->team[$tid][$aid]->wmax);  // Schaden berechnen
                //$this->team[$tid2][$aid2]->health -= $dmg;  // Schaden abziehen
                //echo $this->team[$tid][$aid]->nick." successfully hit ".$this->team[$tid2][$aid2]->nick." with ".$dmg." points of damage.<br>";
                //echo "<font color=red>".$this->team[$tid][$aid]->nick." successfully casts ".$this->machtText($machtNow)." against ".$this->team[$tid2][$aid2]->nick.".</font><br>";
                
                if ($this->team[$tid2][$aid2]->health <= 0) // tot ?
                $fightLog .= $this->team[$tid2][$aid2]->nick." has been knocked out.<br>";  
                $this->muedeMacht($tid, $aid, 0.95, $this->retTired($tid, $aid, "agi")*1.2, 0.90, 0.92, 1);     // ermuedung angreifer
                $this->muedeMacht($tid2, $aid2, 0.90, 0.91, $this->retTired($tid2, $aid2, "spi"), 1, 0.89);   // ermuedung verteidiger
            }
            else  // fehlgeschlagener angriff
            { 
                $this->team[$tid][$aid]->mana -= floor($manaVerbrauch/2);
                $fightLog .= "<span class='".$this->getFailedColor($machtNow)."'>".$this->team[$tid][$aid]->nick." failed to cast ".$this->machtText($machtNow)." against ".$this->team[$tid2][$aid2]->nick.".</span><br>";
                $this->muedeMacht($tid, $aid, 0.88, $this->retTired($tid, $aid, "agi"), $this->retTired($tid, $aid, "spi"), 0.75, 1);   // ermuedung angreifer
                $this->muedeMacht($tid2, $aid2, 0.99, 0.97, 0.95, 1, 0.98);  // erfmuedung verteidiger
            }
        }	   
        // Regeneration ...??
        }
        
        //////////////////////////////////////////
        //
        //  Ergebnis ausgabe
        //
        
        // xp berechnung
        $resthp = array();
        $restlvl = array();
        for ($a = 0; $a<2; $a++)
        {
            for ($i=0; $i<count($this->team[$a]); $i++)
            {
                if(!is_a($this->team[$a][$i], "User")) continue;
                array_push($resthp, $this->team[$a][$i]->health);
                array_push($restlvl, $this->team[$a][$i]->level);
            }
        }
        $maxlvl = max($restlvl);
        $minlvl = min($restlvl);
        $maxhp  = max($resthp);
        $minhp = min($resthp);
        $lvlschnitt = 0;
        for ($i=0; $i<count($restlvl); $i++)
        {
            $lvlschnitt += $restlvl[$i];
        }
        $lvlschnitt /= count($restlvl);
        $basiclvl = ($maxlvl + lvlschnitt) / 1.6;
        $winxp = round(($basiclvl)/(10-count($restlvl)),0) + rand(18,25);
        $losexp = round($winxp/8 + rand(18,25)/2.3,0);
        // ende xp berechnung
        
        // ---
        $fightLog .= "<br><br>";
        $saveW = "";
        $saveWc = 1;
        $saveL = "";
        
        if ($this->checkAlive(0) > 0) // team 1 gewonnen...
        {
        $temp = $this->team[0][0]->nick;
        $saveW = $this->team[0][0]->nick." (".$this->team[0][0]->level.")";
        for ($i=1; $i<count($this->team[0]); $i++)
        {
            if(is_a($this->team[0][$i], "User"))
            {
                $temp .= ", ".$this->team[0][$i]->nick;
                $saveW .= ", ".$this->team[0][$i]->nick." (".$this->team[0][$i]->level.")";
                $saveWc++;
            }	
        }
        $temp .= " won the fight.<br>";
        for ($i=0; $i<count($this->team[0]); $i++)
        {	
            if(is_a($this->team[0][$i], "User"))
            {
                $xpgot = $winxp + $this->team[0][$i]->level;
                $pxp = ceil($xpgot * ($this->team[0][$i]->bpxp/100));
                $bxp = $this->team[0][$i]->bxp;
                $lxp = $this->team[0][$i]->blxp * $this->team[0][$i]->level;
                $xpgot += $pxp + $bxp + $lxp;
                $xpbonus = $pxp + $bxp + $lxp;
                if ($xpbonus > 0) $xpbonus = " (+$xpbonus)";
                else $xpbonus = "";
                $temp .= $this->team[0][$i]->nick." (".$this->team[0][$i]->health."): ".$xpgot."xp.".$xpbonus."<br>";
                $this->team[0][$i]->win_mark = true;
                if ($this->give_xp)
                    if (!is_subclass_of($this->team[0][$i], "User")) setStat("akxp", $this->team[0][$i]->id, $this->team[0][$i]->xp + $xpgot);
            }	
        }
        $temp .= "<br>";
        }
        else  // team 1 verloren
        {
        $temp = $this->team[0][0]->nick;
        $saveL = $this->team[0][0]->nick." (".$this->team[0][0]->level.")";
        for ($i=1; $i<count($this->team[0]); $i++)
        {
            if(is_a($this->team[0][$i], "User"))
            {
                $temp .= ", ".$this->team[0][$i]->nick;
                $saveL .= ", ".$this->team[0][$i]->nick." (".$this->team[0][$i]->level.")";
            }	
        }
        $temp .= " lost the fight.<br>";
        for ($i=0; $i<count($this->team[0]); $i++)
        {
            if(is_a($this->team[0][$i], "User"))
            {
                $xpgot = $losexp + $this->team[0][$i]->level;	
                $pxp = ceil($xpgot * ($this->team[0][$i]->bpxp/100));
                $bxp = $this->team[0][$i]->bxp;
                $lxp = $this->team[0][$i]->blxp * $this->team[0][$i]->level;
                $xpgot += $pxp + $bxp + $lxp;
                $xpbonus = $pxp + $bxp + $lxp;
                if ($xpbonus > 0) $xpbonus = " (+$xpbonus)";
                else $xpbonus = "";
                $temp .= $this->team[0][$i]->nick." (".$this->team[0][$i]->health."): ".$xpgot."xp.".$xpbonus."<br>";
                $this->team[0][$i]->win_mark = false;
                if ($this->give_xp)
                    if (!is_subclass_of($this->team[0][$i], "User")) setStat("akxp", $this->team[0][$i]->id, $this->team[0][$i]->xp + $xpgot);
            }	
        }
        $temp .= "<br>"; 
        }
        
        if ($this->checkAlive(1) > 0) // team 2 gewonnen
        {
        $temp2 = $this->team[1][0]->nick;
        $saveW = $this->team[1][0]->nick." (".$this->team[1][0]->level.")";
        for ($i=1; $i<count($this->team[1]); $i++)
        {
            if(is_a($this->team[1][$i], "User"))
            {
                $temp2 .= ", ".$this->team[1][$i]->nick;
                $saveW .= ", ".$this->team[1][$i]->nick." (".$this->team[1][$i]->level.")";
                $saveWc++;
            }	
        }
        $temp2 .= " won the fight.<br>";
        for ($i=0; $i<count($this->team[1]); $i++)
        {
            if(is_a($this->team[1][$i], "User"))
            {
                $xpgot = $winxp + $this->team[1][$i]->level;
                $pxp = ceil($xpgot * ($this->team[1][$i]->bpxp/100));
                $bxp = $this->team[1][$i]->bxp;
                $lxp = $this->team[1][$i]->blxp * $this->team[1][$i]->level;
                $xpgot += $pxp + $bxp + $lxp;
                $xpbonus = $pxp + $bxp + $lxp;
                if ($xpbonus > 0) $xpbonus = " (+$xpbonus)";
                else $xpbonus = "";
                $temp2 .= $this->team[1][$i]->nick." (".$this->team[1][$i]->health."): ".$xpgot."xp.".$xpbonus."<br>";
                $this->team[1][$i]->win_mark = true;
                if ($this->give_xp)
                    if (!is_subclass_of($this->team[1][$i], "User")) setStat("akxp", $this->team[1][$i]->id, $this->team[1][$i]->xp + $xpgot);
            }	
        }
        $temp2 .= "<br>";
        }
        else  // team 2 verloren
        {
        $temp2 = $this->team[1][0]->nick;
        $saveL = $this->team[1][0]->nick." (".$this->team[1][0]->level.")";
        for ($i=1; $i<count($this->team[1]); $i++)
        {
            if(is_a($this->team[1][$i], "User"))
            {
                $temp2 .= ", ".$this->team[1][$i]->nick;
                $saveL .= ", ".$this->team[1][$i]->nick." (".$this->team[1][$i]->level.")";
            }	
        }
        $temp2 .= " lost the fight.<br>";
        for ($i=0; $i<count($this->team[1]); $i++)
        {
            if(is_a($this->team[1][$i], "User"))
            {
                $xpgot = $losexp + $this->team[1][$i]->level;
                $pxp = ceil($xpgot * ($this->team[1][$i]->bpxp/100));
                $bxp = $this->team[1][$i]->bxp;
                $lxp = $this->team[1][$i]->blxp * $this->team[1][$i]->level;
                $xpgot += $pxp + $bxp + $lxp;
                $xpbonus = $pxp + $bxp + $lxp;
                if ($xpbonus > 0) $xpbonus = " (+$xpbonus)";
                else $xpbonus = "";
                $temp2 .= $this->team[1][$i]->nick." (".$this->team[1][$i]->health."): ".$xpgot."xp.".$xpbonus."<br>";
                $this->team[1][$i]->win_mark = false;
                if ($this->give_xp)
                    if (!is_subclass_of($this->team[1][$i], "User")) setStat("akxp", $this->team[1][$i]->id, $this->team[1][$i]->xp + $xpgot);
            }	
        }
        $temp2 .= "<br>"; 
        }
        
        if ($saveWc == 1)
        {
            $this->report = $saveW." has beaten ".$saveL;
        }
        else
        {
            $this->report = $saveW." have beaten ".$saveL;
        }
        
        $fightLog .= $temp.$temp2;
        $this->fightLog = $this->headerAusgabe($this->team[0], $this->team[1]).$fightLog;
        echo $fightLog;
        //
        // Ende Ergebnisausgabe
        //
        /////////////////////////////////////////////////
        for ($a=0; $a<2; $a++)
        {
            for ($i=0; $i<count($this->team[$a]); $i++)
            {
                if ($this->use_mana)
                    if(is_a($this->team[$a][$i], "User")) {
                        if (!is_subclass_of($this->team[$a][$i], "User"))
                            setStat("akmana", $this->team[$a][$i]->id, $this->team[$a][$i]->mana);
                }		
            }	
        }
    }
    
    function headerAusgabe($t1, $t2)
    {
        $header = "";
        $header .= $t1[0]->nick." (".$t1[0]->level.")";
        for ($i=1; $i<count($t1); $i++)
        {
            if(!is_a($t1[$i], "User")) continue;
            $header .= ", ".$t1[$i]->nick." (".$t1[$i]->level.")";
        }
        $header .= " vs. ";
        $header .= $t2[0]->nick." (".$t2[0]->level.")";
        for ($i=1; $i<count($t2); $i++)
        {
            if(!is_a($t2[$i], "User")) continue;
            $header .= ", ".$t2[$i]->nick." (".$t2[$i]->level.")";
        }	
        $header .= "<br><br>";
        return  $header;
    }
}
// Ausgabevars
function getNah($var, $sex)
{	
	if ($sex == "m")
		$he = "he";
	else
		$he = "she";
	$a['hit']  = "<span class='bl'>%s successfully hit %s with %s points of damage.</span><br>";
	$a['hitr'] = "<span class='bl'>%s hit %s in rage with %s points of damage (+%s).</span><br>";
	$a['hitp'] = "<span class='gn'>%s partitionally hit %s with %s points of damage.</span><br>";
	$a['hitc'] = "<span class='bl'>%s hit by chance for %s points of damage.</span><br>";
	$a['hitf'] = "<span class='g'>%s failed to hit %s.</span><br>";
	$a['hite'] = "<span class='g'>%s tried to hit %s, but %s evaded.</span><br>";
	$a['hitb'] = "<span class='g'>%s hit %s, but %s blocked the attack.</span><br>";
	$a['hitn'] = "<span class='gn'>%s was not able to hit %s, because $he is %s.</span><br>";
	$a['hitno'] = "<span class='gn'>%s tried to hit an enemy, but $he just hit a projection.</span><br>";
	$a['hitprot'] = "<span class='gn'>%s hit %s, but the protection shield neutralized %s points of damage.</span><br>";
	return $a[$var];
}	
function getTac($var, $sex)
{	
	if ($sex == "m")	
		$his = "his";
	else
		$his = "her";
	$t['tacw'] = "<span class='g'>%s waited a moment.</span><br>";
	$t['taci'] = "<span class='g'>%s had an idea.</span><br>";
	$t['tacd'] = "<span class='g'>%s taked a defensiv position.</span><br>";
	$t['taco'] = "<span class='g'>%s taked an offensive position.</span><br>";
	$t['tacf'] = "<span class='g'>%s was strongly influenced by $his attitude.</span><br>";
	$t['tacc'] = "<span class='g'>%s concentrated on $his inner power.</span><br>";
	$t['tacleft'] = "<span class='bl'>%s attacked from the left. (+%s)</span><br>";
	$t['tacright'] = "<span class='bl'>%s attacked form the right. (+%s)</span><br>";
	$t['tacback'] = "<span class='bl'>%s attacked from behind. (+%s)</span><br>";
	return $t[$var];
}
function getFor($var, $sex)
{	
	if ($sex == "m")	
	{
		$he = "he";
		$his = "his";
		$him = "him";
		$himself = "himself";
	}	
	else
	{
		$he = "she";
		$his = "her";
		$him = "her";
		$himself = "herself";
	}	
	// Seeing
	$f['seei'] = "<span class='b'>%s feeled the force, focusing the enemies and discovering them. (%s)</span><br>";
	$f['seei2'] = "<span class='b'>%s could not see something iregular.</span><br>";
	$f['seeii'] = "<span class='bf'>%s tried to use Force Seeing, but got interrupted by %s. (%s)</span><br>";
	$f['seeif'] = "<span class='bf'>%s failed in seeing in force. (%s)</span><br>";
	$f['seeim'] = "<span class='bf'>%s tried to cast Force Seeing, but had not enough mana. (%s)</span><br>";
	// Jump
	$f['jump'] = "<span class='b'>%s strongened $his feet, jumping around his enemies. (%s)</span><br>";
	$f['jumpi'] = "<span class='bf'>%s tried to jump, but got interrupted by %s. (%s)</span><br>";
	$f['jumpb'] = "<span class='bf'>%s was not able to use Force Jump, because of blindness. (%s)</span><br>";
	$f['jumpc'] = "<span class='bf'>%s failed in jumping, because $his mind was confused. (%s)</span><br>";	
	$f['jumpm'] = "<span class='bf'>%s tried to cast Force Jump, but had not enough mana. (%s)</span><br>";
	$f['jumpl'] = "<span class='bf'>%s lost effect on Force Jump.</span><br>";
	$f['jumpe'] = "<span class='b'>%s extended the effect on Force Jump. (%s)</span><br>";
	// Speed
	$f['spee'] = "<span class='b'>%s's heart beated faster alouding $him to move faster. (%s)</span><br>";
	$f['speei'] = "<span class='bf'>%s tried to speed up, but got interrupted by %s. (%s)</span><br>";
	$f['speeb'] = "<span class='bf'>%s was not able to use Force Speed, beeing blind. (%s)</span><br>";
	$f['speec'] = "<span class='bf'>%s failed in casting Force Speed, because of confused mind. (%s)</span><br>";	
	$f['speem'] = "<span class='bf'>%s tried to cast Force Speed, but had not enough mana. (%s)</span><br>";
	// Pull
	$f['pull'] = "<span class='b'>%s pulled %s and slinged $him over the ground for %s points of damage.</span><br>";
	$f['pulli'] = "<span class='bf'>%s tried to pull %s, but got interrupted. (%s)</span><br>";
	$f['pulle'] = "<span class='bf'>%s tried to pull %s, but %s evaded. (%s)</span><br>";
	$f['pullo'] = "<span class='bf'>%s tried to pull %s, but was off target. (%s)</span><br>";
	$f['pullp'] = "<span class='bf'>%s tried to pull an enemy, but $he hit just a projection. (%s)</span><br>";
	$f['pullb'] = "<span class='bf'>%s was not able to use Force Pull, because $he was blinded. (%s)</span><br>";
	$f['pullc'] = "<span class='bf'>%s failed in casting Force Pull, because of confused mind. (%s)</span><br>";	
	$f['pulla'] = "<span class='gn'>%s succeded in casting Force Pull, but %s absorbed the attack. (%s)</span><br>";	
	$f['pulla2'] = "<span class='b'>%s successfully pulled and partitionally hit through %s's absorb shield for %s points of damage.</span><br>";	
	$f['pullm'] = "<span class='bf'>%s tried to cast Force Pull, but had not enough mana. (%s)</span><br>";
	// Push
	$f['push'] = "<span class='b'>%s pushed %s away harmed $him for %s points of damage.</span><br>";
	$f['pushi'] = "<span class='bf'>%s tried to push %s, but got interrupted. (%s)</span><br>";
	$f['pushe'] = "<span class='bf'>%s tried to push %s, but %s evaded. (%s)</span><br>";
	$f['pusho'] = "<span class='bf'>%s tried to push %s, but was off target. (%s)</span><br>";
	$f['pushp'] = "<span class='bf'>%s tried to push an enemy, but $he hit just a projection. (%s)</span><br>";
	$f['pushb'] = "<span class='bf'>%s was not able to use Force Push, because $he was blinded. (%s)</span><br>";
	$f['pushc'] = "<span class='bf'>%s failed in casting Force Push, because of confused mind. (%s)</span><br>";	
	$f['pusha'] = "<span class='gn'>%s succeded in casting Force Push, but %s absorbed the attack. (%s)</span><br>";	
	$f['pusha2'] = "<span class='b'>%s successfully pushed and partitionally hit through %s's absorb shield for %s points of damage.</span><br>";	
	$f['pushm'] = "<span class='bf'>%s tried to cast Force Push, but had not enough mana. (%s)</span><br>";
	// Sabe
	$f['sabe'] = "<span class='b'>%s threw his weapon damaging %s for %s.</span><br>";
	$f['sabe2'] = "<span class='r2'>Partinionally hitting %s with %s points of damage.</span><br>";
	$f['sabei'] = "<span class='bf'>%s tried to throw $his weapon, but got interrupted by %s (%s)</span><br>";
	$f['sabee'] = "<span class='bf'>%s tried to throw $his weapon, but %s evaded. (%s)</span><br>";
	$f['sabeo'] = "<span class='bf'>%s tried to throw $his weapon on %s, but was off target. (%s)</span><br>";
	$f['sabeb'] = "<span class='bf'>%s was not able to use Force Saber Throw, because $he was blinded. (%s)</span><br>";
	$f['sabec'] = "<span class='bf'>%s failed in throwing the weapon, because of confused mind. (%s)</span><br>";	
	$f['sabep'] = "<span class='bf'>%s successfully threw $his weapon, but hit just a projection. (%s)</span><br>";	
	$f['sabeprot'] = "<span class='b'>%s successfully threw $his weapon, but the Protection Shield of %s helped $him out. (%s)</span><br>";	
	$f['sabeprot2'] = "<span class='b'>%s successfully threw $his weapon and partitionally hit through %s's protect shield for %s points of damage.</span><br>";	
	$f['sabem'] = "<span class='bf'>%s tried to cast Force Saber Throw, but had not enough mana. (%s)</span><br>";
	// Throw
	$f['thro'] = "<span class='r2'>%s threw some debris damaging %s for %s.</span><br>";
	$f['thro2'] = "<span class='r2'>Partinionally hitting %s with %s points of damage.</span><br>";
	$f['throi'] = "<span class='rf'>%s tried to cast Force Throw, but got interrupted by %s (%s)</span><br>";
	$f['throe'] = "<span class='rf'>%s tried to throw debris on %s, but %s evaded. (%s)</span><br>";
	$f['throo'] = "<span class='rf'>%s tried to throw debris %s, but was off target. (%s)</span><br>";
	$f['throb'] = "<span class='rf'>%s was not able to use Force Throw, because $he was blinded. (%s)</span><br>";
	$f['throc'] = "<span class='rf'>%s failed in throwing debris, because of confused mind. (%s)</span><br>";
	$f['throp'] = "<span class='rf'>%s successfully threw some debris, but hit just a projection. (%s)</span><br>";	
	$f['throprot'] = "<span class='r2'>%s successfully threw some debris, but the Protection Shield of %s helped $him out. (%s)</span><br>";	
	$f['throprot2'] = "<span class='r2'>%s successfully threw some debris and partitionally hit through %s's protect shield for %s points of damage.</span><br>";	
	$f['throm'] = "<span class='rf'>%s tried to cast Force Throw, but had not enough mana. (%s)</span><br>";
	// Rage
	$f['rage'] = "<span class='r2'>%s feeled $his hatred raising $his power, fastening $his moves and letting rage overwhelm him<br>
				  and payed this with %s points of health.</span><br>";
	$f['ragei'] = "<span class='rf'>%s tried to lose $himself, but got interrupted. (%s)</span><br>";
	$f['ragem'] = "<span class='rf'>%s tried to cast Force Rage, but had not enough mana. (%s)</span><br>";
	// Grip
	$f['grip'] = "<span class='r2'>%s lifted %s and choked him for %s points of damage.</span><br>";
	$f['grip2'] = "<span class='r2'>%s hurled %s for %s points of damage.</span><br>";
	$f['grip3'] = "<span class='r2'>%s hurled %s agains a nearby wall damaging $him with %s</span><br>";
	$f['gripi'] = "<span class='rf'>%s tried to lift %s, but got interrupted. (%s)</span><br>";
	$f['gripe'] = "<span class='rf'>%s tried to choke %s, but %s evaded. (%s)</span><br>";
	$f['gripo'] = "<span class='rf'>%s tried to hurl %s, but was off target. (%s)</span><br>";
	$f['gripb'] = "<span class='rf'>%s was not able to use Force Grip, because $he was blinded. (%s)</span><br>";
	$f['gripp'] = "<span class='rf'>%s tried to hurl a projection. (%s)</span><br>";
	$f['gripc'] = "<span class='rf'>%s failed in casting Force Grip, because of confused mind. (%s)</span><br>";
	$f['gripa'] = "<span class='gn'>%s succeded in casting Force Grip, but %s absorbed the attack. (%s)</span><br>";	
	$f['gripa2'] = "<span class='r2'>%s successfully lifted and partitionally choked through %s's absorb shield for %s points of damage.</span><br>";
	$f['gripm'] = "<span class='rf'>%s tried to cast Force Grip, but had not enough mana. (%s)</span><br>";	
	// Drain
	$f['drai'] = "<span class='r2'>%s drained %s for %s points of damage.</span><br>";
	$f['draii'] = "<span class='rf'>%s tried to drain %s, but got interrupted. (%s)</span><br>";
	$f['draie'] = "<span class='rf'>%s tried to drain %s, but %s evaded. (%s)</span><br>";
	$f['draio'] = "<span class='rf'>%s tried to drain %s, but was off target. (%s)</span><br>";
	$f['draib'] = "<span class='rf'>%s was not able to use Force Drain, because $he was blinded. (%s)</span><br>";
	$f['draic'] = "<span class='rf'>%s failed in casting Force Drain, because of confused mind. (%s)</span><br>";	
	$f['draip'] = "<span class='rf'>%s tried to drain a projection. (%s)</span><br>";
	$f['draia'] = "<span class='gn'>%s succeded in casting Force Drain, but %s absorbed the attack. (%s)</span><br>";	
	$f['draia2'] = "<span class='r2'>%s successfully drained and partitionally hit through %s's absorb shield for %s points of damage.</span><br>";	
	$f['draim'] = "<span class='rf'>%s tried to cast Force Drain, but had not enough mana. (%s)</span><br>";
	// Thunder Bolt
	$f['thun'] = "<span class='r2'>%s casted Force Thunder Bolt and shocked %s for %s points of damage.</span><br>";
	$f['thuni'] = "<span class='rf'>%s tried to shock %s, but got interrupted. (%s)</span><br>";
	$f['thune'] = "<span class='rf'>%s tried to shock %s, but %s evaded. (%s)</span><br>";
	$f['thuno'] = "<span class='rf'>%s tried to shock %s, but was off target. (%s)</span><br>";
	$f['thunb'] = "<span class='rf'>%s was not able to use Force Thunder Bolt, because $he was blinded. (%s)</span><br>";
	$f['thunc'] = "<span class='rf'>%s failed in casting Force Thunder Bolt, because of confused mind. (%s)</span><br>";	
	$f['thunp'] = "<span class='rf'>%s tried to shock a projection. (%s)</span><br>";
	$f['thuna'] = "<span class='gn'>%s succeded in casting Force Thunder Bolt, but %s absorbed the attack. (%s)</span><br>";	
	$f['thuna2'] = "<span class='r2'>%s successfully casted Force Thunder Bolt and partitionally hit through %s's absorb shield for %s points of damage.</span><br>";
	$f['thunm'] = "<span class='rf'>%s tried to cast Force Thunder Bolt, but had not enough mana. (%s)</span><br>";	
	// Chain Lightning
	$f['chai'] = "<span class='r2'>%s succeded in casting Force Chain Lightning</span><br>";
	$f['chai2'] = "<span class='r2'>hitting %s for %s points of damage.</span><br>";
	$f['chai2p'] = "<span class='r2'>hitting just a projection. (%s)</span><br>";
	$f['chaii'] = "<span class='rf'>%s tried to cast Force Chain Lightning on %s, but got interrupted. (%s)</span><br>";
	$f['chaie'] = "<span class='rf'>%s tried to cast Force Chain Lightning on %s, but %s evaded. (%s)</span><br>";
	$f['chaio'] = "<span class='rf'>%s tried to cast Force CHain Lightning on %s, but was off target. (%s)</span><br>";
	$f['chaib'] = "<span class='rf'>%s was not able to use Force Chain Lightning, because $he was blinded. (%s)</span><br>";
	$f['chaic'] = "<span class='rf'>%s failed in casting Force Chain Lightning, because of confused mind. (%s)</span><br>";	
	$f['chaip'] = "<span class='rf'>%s tried to shock some projections. (%s)</span><br>";
	$f['chaia'] = "<span class='gn'>getting absorbed by %s. (%s)</span><br>";	
	$f['chaia2'] = "<span class='r2'>partitionally through %s's absorb shield for %s points of damage.</span><br>";	
	$f['chaim'] = "<span class='rf'>%s tried to cast Force Chain Lightning, but had not enough mana. (%s)</span><br>";
	// Destruction
	$f['dest'] = "<span class='r2'>%s created an imense Wave of Destruction hitting %s for %s points of damage.</span><br>";
	$f['dest2'] = "<span class='r2'>Collapsing with %s, damaging with %s.</span><br>";
	$f['dest2a'] = "Collapsing with %s, getting absorbed by %s. (%s)</span><br>";
	$f['dest2a2'] = "<span class='r2'>Collapsing with %s and partitionally breaking through %s's absorb shield for %s points of damage. (%s)</span><br>";
	$f['desti'] = "<span class='rf'>%s tried to cast Force of Destruction on %s, but got interrupted. (%s)</span><br>";
	$f['deste'] = "<span class='rf'>%s tried to cast Force of Destruction on %s, but %s evaded. (%s)</span><br>";
	$f['desto'] = "<span class='rf'>%s tried to cast Force of Destruction on %s, but was off target. (%s)</span><br>";
	$f['destb'] = "<span class='rf'>%s was not able to use Force of Destruction, because $he was blinded. (%s)</span><br>";
	$f['destc'] = "<span class='rf'>%s failed in casting Force of Destruction, because of confused mind. (%s)</span><br>";	
	$f['destp'] = "<span class='rf'>%s tried to destroy a projection. (%s)</span><br>";
	$f['desta'] = "<span class='gn'>%s succeded in casting Force of Destruction, but %s absorbed the attack. (%s)</span><br>";	
	$f['desta2'] = "<span class='r2'>%s successfully created a Wave of Destruction and partitionally hit through %s's absorb shield for %s points of damage.</span><br>";
	$f['destm'] = "<span class='rf'>%s tried to cast Force of Destruction, but had not enough mana. (%s)</span><br>";	
	// Deadly Sight
	$f['dead'] = "<span class='r2'>%s succeded in casting Force Deadly Sight.</span><br>";
	$f['dead2'] = "<span class='r2'>hitting %s for %s points of damage.</span><br>";
	$f['dead2p'] = "<span class='r2'>hitting just a projection. (%s)</span><br>";
	$f['dead3'] = "<span class='r2'>%s focused %s and let $him feel the heat damaging for %s points of damage.</span><br>";
	$f['deadi'] = "<span class='rf'>%s tried to cast Force Deadly Sight on %s, but got interrupted. (%s)</span><br>";
	$f['deadi'] = "<span class='rf'>%s tried to burn down %s, but %s evaded. (%s)</span><br>";
	$f['deado'] = "<span class='rf'>%s tried to burn down %s, but was off target. (%s)</span><br>";
	$f['deadb'] = "<span class='rf'>%s was not able to use Force Deadly Sight, because $he was blinded. (%s)</span><br>";
	$f['deadc'] = "<span class='rf'>%s failed in casting Force Deadly Sight, because of confused mind. (%s)</span><br>";	
	$f['deadp'] = "<span class='rf'>%s tried to burn down some projections. (%s)</span><br>";
	$f['deada'] = "<span class='gn'>getting absorbed by %s. (%s)</span><br>";	
	$f['deada2'] = "<span class='r2'>partitionally through %s's absorb shield for %s points of damage.</span><br>";	
	$f['deadm'] = "<span class='rf'>%s tried to cast Force Deadly Sight, but had not enough mana. (%s)</span><br>";
	// Persuation
	$f['pers'] = "<span class='gn'>%s vanished from his opponents line of sight. (%s)</span><br>";
	$f['persi'] = "<span class='gf'>%s tried vanish, but got interrupted by %s. (%s)</span><br>";
	$f['persr'] = "<span class='gn'>%s returned from invisibility.</span><br>";
	$f['persm'] = "<span class='gf'>%s tried to cast Force Persuation, but had not enough mana. (%s)</span><br>";
	// Projection
	$f['proj'] = "<span class='gn'>%s casted a Projection of $himself. (%s)</span><br>";
	$f['proji'] = "<span class='gf'>%s tried to cast a Projection, but got interrupted by %s. (%s)</span><br>";
	$f['projr'] = "<span class='gn'>All Projections of %s disappeared.</span><br>";
	$f['projm'] = "<span class='gf'>%s tried to cast Force Projection, but had not enough mana. (%s)</span><br>";
	// Blind
	$f['blin'] = "<span class='gn'>%s successfully blinded %s. (%s)</span><br>";
	$f['blini'] = "<span class='gf'>%s tried to blind %s, but got interrupted. (%s)</span><br>";
	$f['bline'] = "<span class='gf'>%s tried to blind %s, but %s evaded. (%s)</span><br>";
	$f['blino'] = "<span class='gf'>%s tried to blind %s, but was off target. (%s)</span><br>";
	$f['blinr'] = "<span class='gn'>%s's eyes regenerated.</span><br>";
	$f['blinm'] = "<span class='gf'>%s tried to cast Force Blind, but had not enough mana. (%s)</span><br>";
	// Confuse
	$f['conf'] = "<span class='gn'>%s successfully confused %s. (%s)</span><br>";
	$f['confi'] = "<span class='gf'>%s tried to confuse %s, but got interrupted. (%s)</span><br>";
	$f['confe'] = "<span class='gf'>%s tried to confuse %s, but %s evaded. (%s)</span><br>";
	$f['confo'] = "<span class='gf'>%s tried to confuse %s, but was off target. (%s)</span><br>";
	$f['confg'] = "<span class='r2'>%s hurled by $himself for %s points of damage.</span><br>";
	$f['confh'] = "<span class='b'>%s screamed out loud because of headache losing %s points of health.</span><br>";
	$f['confhit'] = "<span class='b'>%s hit %s with confused mind for %s points of damage.</span><br>";
	$f['confr'] = "<span class='gn'>%s's mind regenerated.</span><br>";
	$f['confm'] = "<span class='gf'>%s tried to cast Force Confuse, but had not enough mana. (%s)</span><br>";
	// Heal
	$f['heal'] = "<span class='gn'>%s healed $himself for %s points of health.</span><br>";
	$f['heali'] = "<span class='gf'>%s tried to heal $himself, but got interrupted by %s. (%s)</span><br>";
	$f['healm'] = "<span class='gf'>%s tried to heal $himself, but had not enough mana. (%s)</span><br>";
	// Team Heal
	$f['team'] = "<span class='gn'>%s casted Force Team Heal healing</span><br>";
	$f['team2'] = "<span class='gn'>%s for %s points of health.</span><br>";
	$f['teami'] = "<span class='gf'>%s tried heal the team, but got interrupted by %s. (%s)</span><br>";
	// Protect
	$f['prot'] = "<span class='gn'>%s created a Protect Shield. (%s)</span><br>";
	$f['proti'] = "<span class='gf'>%s tried to creat a Protect Shield, but got interrupted by %s. (%s)</span><br>";
	$f['protm'] = "<span class='gf'>%s tried to creat a Protect Shield, but had not enough mana. (%s)</span><br>";
	// Protect
	$f['abso'] = "<span class='gn'>%s created an Absorb Shield around $him. (%s)</span><br>";
	$f['absoi'] = "<span class='gf'>%s tried to creat an Absorb Shield, but got interrupted by %s. (%s)</span><br>";
	$f['absom'] = "<span class='gf'>%s tried to creat an Absorb Shield, but had not enough mana. (%s)</span><br>";
	// Team Energize
	$f['tnrg'] = "<span class='o'>%s casted Force Team Energize energizing</span><br>";
	$f['tnrg2'] = "<span class='o'>%s for %s points of mana (%s)</span><br>";
	$f['tnrg3'] = "<span class='o'>%s energized $himself with %s mana. (%s)</span><br>";
	$f['tnrgi'] = "<span class='of'>%s tried to energize the team, but got interrupted by %s. (%s)</span><br>";
	$f['tnrgm'] = "<span class='of'>%s tried to energize the team, but had not enough mana. (%s)</span><br>";
	// Force Revitalize
	$f['rvtl'] = "<span class='o'>%s casted Force Revitalize resurrecting %s with % points of health. (%s)</span><br>";
	$f['rvtl2'] = "<span class='o'>%s revitalized the team % points of health.</span><br>";
	$f['rvtli'] = "<span class='of'>%s tried to cast Force Revitalize, but got interrupted by %s. (%s)</span><br>";
	$f['rvtlm'] = "<span class='of'>%s tried to cast Force Revitalize, but had not enough mana. (%s)</span><br>";
	return $f[$var];
}