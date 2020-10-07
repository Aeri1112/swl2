<?php
	$stat_line_1 = explode(",",$loot->stat1);
	$stat_line_2 = explode(",",$loot->stat2);
	$stat_line_3 = explode(",",$loot->stat3);
	$stat_line_4 = explode(",",$loot->stat4);
	$stat_line_5 = explode(",",$loot->stat5);

	$stat_line_1 = implode(" ",$stat_line_1);
	$stat_line_2 = implode(" ",$stat_line_2);
	$stat_line_3 = implode(" ",$stat_line_3);
	$stat_line_4 = implode(" ",$stat_line_4);
	$stat_line_5 = implode(" ",$stat_line_5);
	$type = $loot->type;   
	$img = $loot->img;

	$item = "folgendes Item nimmst du in dein Inventar auf<br>
	<div class='card' style='width: 12rem;'>
	<img src= '..\..\webroot\img\items\\$type\\$img.jpg' class='card-img-top'>".              
	#$this->getController()->Html->image($loot[1]->img.".jpg", ['pathPrefix' => "webroot/img/items/".$loot[1]->type."/", 'class' => 'card-img-top'])."
		"<div class='card-body'>
		<h5 class='card-title'>".$loot->name."</h5>
		<p class='card-text'>
				qlvl: ".$loot->qlvl."<br>";
				if($loot->reql) $item .= "req. level: ".$loot->reql."<br>";
				if($loot->reqs != 0) $item .= "req. skill: ".$loot->reqs."<br>";
				if($loot->mindmg != 0) $item .= "damage: ".$loot->mindmg. " - ".$loot->maxdmg."<br>";
				if($loot->stat1) $item .= $stat_line_1."<br>";
				if($loot->stat2) $item .= $stat_line_2."<br>";
				if($loot->stat3) $item .= $stat_line_3."<br>";
				if($loot->stat4) $item .= $stat_line_4."<br>";
				if($loot->stat5) $item .= $stat_line_5."<br>";
				$item .= "value: ".$loot->price."
		</p>
		</div>
	</div>";
	echo $item;
?>