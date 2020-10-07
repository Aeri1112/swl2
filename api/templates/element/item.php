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
	
	if(isset($class)) $class = $class;
	else $class = null;
	
	$img = $loot->img;

	$item = "
	<div class='$class col'>
	<div class='card'>
	<img src= '..\..\webroot\img\items\\$type\\$img.jpg' class='card-img-top mx-auto d-block mt-2' style='width:100px;'>".              
		"<div class='card-body text-center'>
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
	</div>
	</div>";
	echo $item;
?>