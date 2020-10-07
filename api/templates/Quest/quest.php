<?php
	echo "<div class='container'>".$quest->name."<br>";
	
	//Erledigt oder Einführung - abhängig vom status
	echo $text."</div><br>";
	
	if($user_quest->status != 0)
	{
		foreach($quest_steps as $key => $step)
		{	
			echo "<div class='col-auto d-flex justify-content-between'>";
			
			echo $this->Html->link(
						$step->name,
						'/quest/step/'.$quest->quest_id.'/'.$step->step_id.''
					);
			echo "<span>".$user_step[$step->step_id]["status"]."</span>";
			echo "</div>";
		}
	}
	else
	{
		echo "<div class='container'>This quest is not available for you</div>";
	}
?>