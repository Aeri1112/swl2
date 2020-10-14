<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;


class FightController extends AppController {

    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('main');
    }

    public function reade()
    {
        $fightid = $this->request->getParam("pass")[0];
        $reports = $this->loadModel("JediEventsSingleFightReports")->get($fightid);
        $this->set("report",$reports->report);
    }
	public function reada()
    {
        $fightid = $this->request->getParam("pass")[0];
		$reports = $this->loadModel("JediFightReports")->find()->where(["md5" => $fightid])->where(["type" => "a"])->first();
		
		if($reports->type == "a")
		{
			$this->set("report",$reports->report);
		}
    }
}
?>