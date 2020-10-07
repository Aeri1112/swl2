<?php
	if($_SESSION["Auth"]["User"]["id"] == 20)
	{
		foreach($quests as $key => $quest)
		{
			echo "<div class='col-md-6 d-flex justify-content-between'>";
			
			if($user_quests[$quest->quest_id]["status"] != "not available yet")
			{
				echo $this->Html->link(
						$quest->name,
						'/quest/quest/'.$quest->quest_id.''
					);
			}
			else
			{
				echo "<span>".$quest->name."</span>";
			}
			echo "".$user_quests[$quest->quest_id]["status"]."";
			echo "</div>";
		}
	}
?>