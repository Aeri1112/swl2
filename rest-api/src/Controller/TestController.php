<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;
use Rest\Utility\JwtToken;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Das ist mein Rest Controller
 * Dazu diesesn Plugin installiert: https://github.com/sprintcube/cakephp-rest
 * In der config/routes.php werden die Routen definiert.
 * In der config/rest.php wird der JWT Auth definiert, sollte man später ändern.
 * Bei mir ist es so konfiguriert, dass es auf localhost root ebene läuft.
 * In config/app.php defiere ich ein paar CORS sachen, von dem Plugin https://github.com/ozee31/cakephp-cors
 * Das handhabt die CORS inhalte unter 'Cors' index.
 *
 * Btw. die Anfragemethoden und andere Feinheiten ignoriere ich erstmal.
 */
class TestController extends RestController
{
    public function test()
    {
        // der hier macht nicht viel, einfach nur um zu schauen.
        // da es auf rootebene läuft, were http://localhost/test die url um das hier aufzurufen bie mir, auch so in routes.php definiert
        $this->set('test', 'fufu');
    }

    /**
     * Um simpel zu bleiben führe ich keine Validierung durch, nur das was passiert, wenn alles passt.
     * http://localhost/test/login
     */
    public function login() {

        //möglichen Account holen
        $acc = $this->loadModel("Accounts")->find()->where(["accountname" => $this->request->getData("accountname")])->first();
        $passhash = (new DefaultPasswordHasher)->check($this->request->getData("password"),$acc->password);

        if ($passhash) {
            $payload = [
                'id' => $acc->id,
                'accountname' => $acc->accountname
            ];
            // erstelle den jwt token mit dem obigen payload, kann man sich wie ne session datei vorstellen
            // die eigenarten bei JWT musst die frontend seitig in der fetch.js ansehen.
            $token = JwtToken::generate($payload);

            $this->set(compact('token'));
            $this->set("user",$acc->id);
            $connection = ConnectionManager::get('default');
            $username = $connection->execute('SELECT username FROM jedi_user_chars WHERE userid = :id', ['id' => $acc->id])->fetch('assoc');
            $this->set("username",$username);
        }
        else {
            $this->set("error","wrong username or password");
        }
    }

    /**
     * Hier teste ich nur ob ich auch eingeloggt bin und ob ein fehler geworfen wird.
     * Genauere spezificationen auf der homepage des plugin anbieters
     * http://localhost/test/auth
     */
    public function auth()
    {
        $payload = $this->payload; // der payload eingetragen im login
        $this->set('hello', 'muahah');
        $this->set('payload', $payload);
    }

    public function sse() {

        // set headers for stream
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");

        // Is this a new stream or an existing one?
        $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
        if ($lastEventId == 0) {
            $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
        }

        echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
        echo "retry: 2000\n";

        // start stream
        while(true){

            if(connection_aborted()){
                exit();
            }

            else{

                // here you will want to get the latest event id you have created on the server, but for now we will increment and force an update
                $latestEventId = 1;

                if($lastEventId < $latestEventId)
                {
                    echo "id: " . $latestEventId . "\n";
                    echo "data: Howdy (".$latestEventId.") \n\n";
                    $lastEventId = $latestEventId;
                    ob_flush();
                    flush();

                }

                else{
                
                    // no new data to send
                    echo ": heartbeat\n\n";
                    ob_flush();
                    flush();
                    
                }

            }
    
            // 2 second sleep then carry on
            sleep(2);

        }      
    }
}