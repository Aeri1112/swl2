<?php
	echo $this->Flash->render();
	
	if($no_alliance == false)
	{
		echo "restliche Versuche: ".$alliance->attemps."<br>";
		//Raid läuft
		if($alli_fight != null)
		{
			echo "raid läuft ->";
			echo $this->Html->link(
				'teilnehmen',
				'/alliances/raid/join'
			);
			echo "<br><br>";
			echo "Teilnehmer:";
			
			echo "<div class='row'>";
			foreach($raid_members as $key => $raid_member)
			{
				echo "<div class='col-sm-3 pb-1 pr-1' style='min-height:100px;'>";
				
				?>
				<div class="progress progress-bar-vertical" >
					<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="height:<?= $raid_member->HealthPro ?>%" aria-valuenow="<?= $raid_member->HealthPro ?>" aria-valuemin="0" aria-valuemax="100">
					</div>
				</div>
				<div class="progress progress-bar-vertical">
				  <div class="progress-bar progress-bar-striped bg-primary " role="progressbar" style="height: <?= $raid_member->ManaPro ?>%" aria-valuenow="<?= $raid_member->ManaPro ?>" aria-valuemin="0" aria-valuemax="100">
				  </div>
				</div>
				
				<?php
				echo "<div class='col-sm-auto'>";
				echo "Name: "; echo $raid_member->username;
				echo "<br>";
				echo "Level: "; echo $raid_member->skills->level;
				echo "</div>";
				
				echo "</div>";
			}
			echo "</div>"; //row

			echo $this->Html->link('teilnahme abbrechen','/alliances/raid/cancel');
			
			if($is_leader == true)
			{
				echo "<br><br>Als Leader oder Co-Leader kannst du den Raid jederzeit starten.<br>";
				echo $this->Html->link(
					'starten',
					'/alliances/raid/start'
				);
				echo "<br>";
			}
			
		}
		elseif($raid_running == true)
		{
			echo "Kampf läuft bereits, schaue ins Layer<br>";
		}
		
		if($is_leader == true && $raid_running == false && !isset($alli_fight))
		{
			echo "Here you can start a raid with ally-members<br>";
			
			echo $this->Form->create();
			echo $this->Form->label('npc', 'NPC Enemy');	
			echo $this->Form->select("npc", [
											1 => "Giant Womp-Rat",
											9 => "Reek"
											], ["class" => "custom-select pl-0", "id" => "npc"]);
											
			echo $this->Form->submit('erstellen', ["class" => "mt-1 btn btn-primary"]);
			echo $this->Form->end();
		}
		
		echo "<div class='row d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Überblick',
				'/alliances'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Jagd',
				'/alliances/raid'
			);
			echo "</div>";
		
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Mitglieder',
				'/alliances/view/'.$alliance->id.''
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'auflisten',
				'/alliances/all'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Forschung',
				'alliances/research'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'verlassen',
				'alliances/leave'
			);
			echo "</div>";
		echo "</div>";
	}
?>