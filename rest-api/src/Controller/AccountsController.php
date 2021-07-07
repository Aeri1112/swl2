<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Mailer\Mailer;
use Cake\Event\EventInterface;
use Cake\Auth\Storage;
use Cake\Http\ServerRequest;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountsController extends AppController
{    
    public function login()
    {   
        $this->viewBuilder()->setLayout('react');

        $rest_json = file_get_contents("php://input");
        $_POST = json_decode($rest_json, true);

        $this->request = $this->request->withData("accountname",$_POST["accountname"])->withData("password",$_POST["password"]);
        $this->request->allowMethod(['get', 'post', 'options']);

        $result = $this->Authentication->getResult();
        var_dump($result);
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            #return $this->redirect(['controller' => 'Character', 'action' => 'overview']);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function create()
    {
        $this->viewBuilder()->setLayout('ajax');

        $process = $this->request->getData('process');
        $shuffle = $this->request->getData('shuffle');
        #$s_userid = $_SESSION['s_userid'];
        $s_charname1 = $this->request->getData('s_charname1');
        $s_sex1 = $this->request->getData('s_sex1');
        $s_age1 = $this->request->getData('s_age1');
        $s_side1 = $this->request->getData('s_side1');

        $char = $this->loadModel("JediUserChars")->get($this->Auth->User("id"));

        if($shuffle OR $process)
        {
            if (!$s_charname1) 
            {
                $name_e = "<font color=\"red\">$reg_11</font>";
                $this->set("stop","yes");
                $this->stop = "yes";
            }
            elseif (strlen($s_charname1) > 14) 
            {
                $name_e = "<font color=\"red\">$reg_38</font>";
                $this->set("stop","yes");
                $this->stop = "yes";
            }
            /*
            elseif (!ereg('^[A-Z]{1}[a-z]{2,8}([\ ][A-Z]{1}[a-z]{2,8})?$', $s_charname1)) 
            {
                $name_e = "<font color=\"red\">$reg_37</font>";
                $this->set("stop","yes");
                $this->stop = "yes";
            }
            */
            else 
            {
                $_SESSION['s_charname'] = $s_charname1;
            }

                $_SESSION['s_sex'] = $s_sex1;
            
                $_SESSION['s_side'] = $s_side1;

            $username_taken = $this->loadModel("JediUserChars")->find()->where(['username' => $s_charname1])->first();
            if ($username_taken)
            {
                $this->Flash->error(__('This username is already taken.'));
                $this->set("stop","yes");
                $this->stop = "yes";
            }
        }
        else
        {
            $this->set("stop","yes");
            $this->stop = "yes";
        }

        if ($_SESSION['s_shuffle'] != 'nah' OR $this->request->getData('shuffle')) 
        {
            $_SESSION["s_charname"] = null;
            $planets = "unknown,Alderaan,Arbra,Bespin,Corellia,Coruscant,Dagobah,Endor,Geonosis,Hoth,Kamino,Kashyyyk,Myrkr,Naboo,Nar Shaddaa,Ord Mantell,Raxus Prime,Tatooine,Yavin 4";
            $planet_array = explode(",", $planets);
            #srand((double)microtime() * 1000000);
            $_SESSION['s_homeworld'] = ($planet_array[rand(0, 18)]);

            $race = rand(1, 12);
            switch ($race) {
                case 1:
                $_SESSION['s_race'] = "Besalisk";
                $_SESSION['s_age'] = rand(21, 37);
                $_SESSION['s_height'] = rand(174, 203);
                break;
                case 2:
                $_SESSION['s_race'] = "Ewok";
                $_SESSION['s_age'] = rand(3, 20);
                $_SESSION['s_height'] = rand(65, 125);
                $_SESSION['s_homeworld'] = "Endor";
                break;
                case 3:
                $_SESSION['s_race'] = "Gungan";
                $_SESSION['s_age'] = rand(15, 26);
                $_SESSION['s_height'] = rand(170, 205);
                $_SESSION['s_homeworld'] = "Naboo";
                break;
                case 4:
                $_SESSION['s_race'] = "Gran";
                $_SESSION['s_age'] = rand(19, 39);
                $_SESSION['s_height'] = rand(173, 199);
                break;
                case 5:
                $_SESSION['s_race'] = "Human";
                $_SESSION['s_age'] = rand(16, 45);
                $_SESSION['s_height'] = rand(165, 200);
                break;
                case 6:
                $_SESSION['s_race'] = "Klatooinian";
                $_SESSION['s_age'] = rand(17, 33);
                $_SESSION['s_height'] = rand(158, 185);
                break;
                case 7:
                $_SESSION['s_race'] = "Rodian";
                $_SESSION['s_age'] = rand(17, 33);
                $_SESSION['s_height'] = rand(150, 170);
                break;
                case 8:
                $_SESSION['s_race'] = "Trandoshan";
                $_SESSION['s_age'] = rand(13, 35);
                $_SESSION['s_height'] = rand(187, 211);
                break;
                case 9:
                $_SESSION['s_race'] = "Twilek";
                $_SESSION['s_age'] = rand(23, 41);
                $_SESSION['s_height'] = rand(189, 212);
                break;
                case 10:
                $_SESSION['s_race'] = "Wookiee";
                $_SESSION['s_age'] = rand(18, 34);
                $_SESSION['s_height'] = rand(185, 216);
                $_SESSION['s_homeworld'] = "Kashyyyk";
                break;
                case 11:
                $_SESSION['s_race'] = "Yuuzhan Vong";
                $_SESSION['s_age'] = rand(22, 32);
                $_SESSION['s_height'] = rand(171, 192);
                break;
                case 12:
                $_SESSION['s_race'] = "Zabrak";
                $_SESSION['s_age'] = rand(15, 36);
                $_SESSION['s_height'] = rand(159, 209);
                break;
            }

            if ($_SESSION['s_sex'] == "f") {
                $_SESSION['s_height'] = round($_SESSION['s_height'] - ($_SESSION['s_height'] * 11 / 100));
            }

            $suggestname = "";
            $pass_source_vokal = "a,b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,t,v,w,x,z";
            $pass_source_konso = "e,i,o,u,y";
            $pass_array1 = explode(",", $pass_source_vokal);
            $pass_array2 = explode(",", $pass_source_konso);
            #srand((double)microtime() * 1000000);
            $length = rand(2, 4);
            for ($i_ = 0; $i_ < $length; $i_++) {
                $suggestname .= ($pass_array1[rand(0, 20)]);
                $suggestname .= ($pass_array2[rand(0, 4)]);
            }

            if (empty($_SESSION['s_charname'])) {
                $suggestname = ucfirst(strtolower($suggestname));
                $_SESSION['s_charname'] = $suggestname;
            }

            $_SESSION['s_shuffle'] = "nah";
            $this->set("stop","yes");
            $this->stop = "yes";
        }  

        if ($this->request->getData('process') && (!isset($this->stop)))
        {
			//Gratis Weapon
            $weapon = $this->loadModel("JediItemsWeapons")->newEmptyEntity();
            $weapon->ownerid = $char->userid;
            $weapon->position = "eqp";
            $weapon->name = "Training Staff";
            $weapon->img = "wood1";
            $weapon->price = 100;
            $weapon->reql = 1;
            $weapon->reqs = 0;
            $weapon->mindmg = 1;
            $weapon->maxdmg = 10;
            $this->JediItemsWeapons->save($weapon);
			
			$weapon_id = $this->JediItemsWeapons->find()->last();
				
            //insert to chars db and other
            $char->username = $_SESSION["s_charname"];
            $char->status = "unactivated";
            $char->sex = $_SESSION["s_sex"];
            $char->age = $_SESSION["s_age"];
            $char->species = $_SESSION["s_race"];
            $char->homeworld = $_SESSION["s_homeworld"];
            $char->size = $_SESSION["s_height"];
            $char->health = 22;
            $char->mana = 12;
            $char->energy = 50;
			$char->item_hand = $weapon_id["itemid"];
            $char->fpreferences = "0,0,0,0,0,0,0";
            $this->JediUserChars->save($char);

            //skills
            $char->skill = $this->loadModel("JediUserSkills")->newEmptyEntity();
            $char->skill->userid = $char->userid;
            $char->skill->level = 1;
            $char->skill->rsp = 5;
            $char->skill->rfp = 3;
            if($_SESSION["s_side"] == "d")
            {
                $char->skill->side = -768;
            }   
            else
            {
                $char->skill->side = 768;
            }
            $this->JediUserSkills->save($char->skill);

            //statistics
            $stat = $this->loadModel("JediUserStatistics")->newEmptyEntity();
            $stat->userid = $char->userid;
            $this->JediUserStatistics->save($stat);

            $this->redirect(['controller' => 'character', 'action' => 'overview']);
        }     
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Accounts', 'action' => 'login']);
        }
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $accounts = $this->paginate($this->Accounts);

        $this->set(compact('accounts'));
    }

    /**
     * View method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $account = $this->Accounts->get($id, [
            'contain' => [],
        ]);

        $this->set('account', $account);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel("JediUserChars");
        $this->viewBuilder()->setLayout('ajax');

        $account = $this->Accounts->newEmptyEntity();
        if ($this->request->is('post')) 
        {
            $account = $this->Accounts->patchEntity($account, $this->request->getData());
            if ($this->Accounts->save($account)) 
            {
                //ein paar Werte bereits setzen und Mail verschicken
                $char = $this->JediUserChars->newEmptyEntity();
                $char->userid = $account->id;
                $char->status = "newuser";
                $char->lastaccess = time();
                $this->JediUserChars->save($char);

                //send Mail
                
                $mailer = new Mailer('default');
                $mailer->setFrom(['info@starwarslegends.com'])
                    ->setTo($account->email)
                    ->setSubject('Welcome')
                    ->setSender('info@starwarslegends.com')
                    ->viewBuilder()
                        ->setTemplate('default')
                        ->setLayout('default');
                $mailer->deliver("Willkommen, diese Mail ist vorerst nur ein Platzhalter um die Funktionalität zu testen. Später wird das sicher mal seinen Sinn haben.");
                
                $this->Flash->success(__('The account has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The account could not be saved. Please, try again.'));
        }
        $this->set(compact('account'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $account = $this->Accounts->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $account = $this->Accounts->patchEntity($account, $this->request->getData());
            if ($this->Accounts->save($account)) {
                $this->Flash->success(__('The account has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The account could not be saved. Please, try again.'));
        }
        $this->set(compact('account'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $account = $this->Accounts->get($id);
        if ($this->Accounts->delete($account)) {
            $this->Flash->success(__('The account has been deleted.'));
        } else {
            $this->Flash->error(__('The account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'login']);
    }
}
