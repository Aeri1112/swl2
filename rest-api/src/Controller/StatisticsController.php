<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Rest\Controller\RestController;
use Cake\Datasource\ConnectionManager;


class StatisticsController extends RestController {

    public function get() {
        $this->loadModel("JediUserStatistics");
        if($this->request->getParam('id') == $this->Auth->User("id")) {
            $stats = $this->JediUserStatistics->get($this->request->getParam('id'));
            $this->set("stats",$stats);
        }
        else {
            $this->set("error","false");
        }
    }

    public function setstat() {
        $this->loadModel("JediUserStatistics");
        $stats = $this->request->getData("stats");
        $streak = $this->request->getData("streak");
        $statsTable = $this->JediUserStatistics->get($this->Auth->User("id"));
        $statsTable->bjhands += $stats["hand"];
        $statsTable->bjtotalwins = $statsTable->bjtotalwins + $stats["win"] + $stats["win21"] + $stats["split21"];
        $statsTable->bj21 += $stats["bj21"];
        $statsTable->bjsplitwins += $stats["split"];
        $statsTable->bjdoublewins += $stats["double"];
        $statsTable->bjinsurancewins += $stats["ins"];
        $statsTable->bjearning += $stats["cash"];

        if($streak > $statsTable->bjwinningstreak) {
            $statsTable->bjwinningstreak = $streak;
        }

        $this->JediUserStatistics->save($statsTable);
    }

    public $paginate = [	
        'sortWhitelist' => [
            'level', 'username', 'side'
        ],
        // Other keys here.
        'limit' => 10,
        //'order' => ['level' => 'desc']
    ];

    public function ranking () {
        $connection = ConnectionManager::get('default');

        $this->loadModel("JediUserChars");
        $this->loadModel("JediUserSkills");
        $this->loadModel("JediUserStatistics");

        $type = $this->request->getQuery('type');

        if($type == "level") {
            $list = $this->JediUserSkills->find("all", ["contain" => ["JediUserChars"]])->select(["userid", $type,'JediUserChars.username'])->where(["JediUserChars.status" => "active"])->order([$type => "desc"]);
        }
        else if ($type == "arenawins") {
            $list = $this->JediUserStatistics->find("all", ["contain" => ["JediUserChars"]])->select(["JediUserStatistics.userid", "arenawins", "arenalosts", "JediUserChars.username"])->where(["JediUserChars.status" => "active"])->order(["arenawins" => "desc"]);
        }
        else {
            $list = $this->JediUserStatistics->find("all", ["contain" => ["JediUserChars"]])->select(["JediUserStatistics.userid", $type, "JediUserChars.username"])->where(["JediUserChars.status" => "active"])->order([$type => "desc"]);
        }
        $this->set("list",$this->paginate($list));

        $users = $this->set("players",$this->loadModel("JediUserChars")->find()->where(["status" => "active"])->count());

        $rats = $this->JediUserStatistics->find("all", ["contain" => ["JediUserChars"]])->select(["JediUserStatistics.userid", "killedRat", "JediUserChars.username"])->where(["JediUserChars.status" => "active"])->order(["killedRat" => "desc"])->limit(3);
        $this->set("rats",$rats);

        $loots = $this->JediUserStatistics->find("all", ["contain" => ["JediUserChars"]])->select(["JediUserStatistics.userid", "loots", "JediUserChars.username"])->where(["JediUserChars.status" => "active"])->order(["loots" => "desc"])->limit(3);
        $this->set("loots",$loots);

        $arena = $this->JediUserStatistics->find("all", ["contain" => ["JediUserChars"]])->select(["JediUserStatistics.userid", "arenawins", "arenalosts", "JediUserChars.username"])->where(["JediUserChars.status" => "active"])->order(["arenawins" => "desc"])->limit(3);
        $this->set("arena",$arena);

        $level = $this->JediUserSkills->find("all", ["contain" => ["JediUserChars"]])->select(["userid", "level",'JediUserChars.username'])->where(["JediUserChars.status" => "active"])->order(["level" => "desc"])->limit(3);
        $this->set("level",$level);
    }
}
?>