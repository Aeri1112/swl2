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

use Rest\Controller\RestController;
use Rest\Utility\JwtToken;

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
        $payload = [
            'foo' => 'bar'
        ];
        // erstelle den jwt token mit dem obigen payload, kann man sich wie ne session datei vorstellen
        // die eigenarten bei JWT musst die frontend seitig in der fetch.js ansehen.
        $token = JwtToken::generate($payload);
        $this->set("request",$this->request->getData());
        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
        }
        $this->set("user",$this->Auth->user());
        $this->set(compact('token'));
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
}
