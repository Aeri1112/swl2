<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Datasource\ConnectionManager;
use Rest\Controller\RestController;


class CronController extends RestController {

    public function initialize(): void
    {
        parent::initialize();
		$this->loadComponent("Treasure");
		$this->loadModel("JediUserChars");
        $this->loadModel("JediEventsSingleRanking");
		$this->loadModel("JediEventsHighscoreRanking");
        $this->loadModel("JediEventsSingleFightReports");
        $this->loadModel("JediEvents");
		$this->loadModel("JediUserStatistics");
		$this->loadModel("JediItemsJewelry");
		$this->loadModel("JediItemsWeapons");
        $this->loadModel("JediItemsMisc");
    }

    public function dailyEvent ($token) {
        
        if($token == "cde389ed5f7c850f3c8efbf2759c65e15b92eee5738858f6de061ef1b0026129") {
            $newItem = $this->JediItemsMisc->newEntity();
            $newItem->ownerid = 20;
            $newItem->position = "inv";
            $newItem->name = "Rancor-Lootbox (S)";
            $newItem->sizex = 1;
            $newItem->consumable = 1;
            $newItem->stat1 = "Includes loot, as of a small rancor";
            $newItem->mindmg = 0;
            $newItem->maxdmg = 0;
            $newItem->img = "executivecase1";

            $this->JediItemsMisc->save($newItem);
        }
        else {
            return null;
        }
    }
}
?>