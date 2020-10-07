<?php
	echo $this->Flash->render();
	
	if($no_alliance == true)
	{
		?>
		<table class="table table-sm table-striped">
			<thead>
				<tr>
					<th>
						Name
					</th>
					<th>
						Kürzel
					</th>
					<th>
						Seite
					</th>
					<th>
						Aktion
					</th>
				</tr>
			</thead>
			<tbody>
		<?php
		foreach($alliances as $key => $alliance)
		{
			echo "<tr>";
				echo "<td>";
					echo $alliance->name;
				echo "</td>";
				echo "<td>";
					echo $alliance->short;
				echo "</td>";
				echo "<td>";
					echo $alliance->alignment;
				echo "</td>";
				echo "<td>";
					echo $this->Html->link(
						'view',
						'/alliances/view/'.$alliance->id.''
					);
					echo " | ";
					echo $this->Html->link(
						'join',
						'/alliances/join/'.$alliance->id.''
					);
				echo "</td>";
			echo "</tr>";
		}
		?>
			</tbody>
		</table>
		<?php
		echo "<div class='container d-flex justify-content-center'>";
			echo $this->Html->link(
				'erstellen',
				'/alliances/create'
			);
		echo "</div>";
	}
	else //Ausgabe Allianceseite
	{
		echo "<div class='d-flex justify-content-center'>Willkommen bei ".$alliance->name."!</div><br>";
		
		if($alli_fight != null)
		{
			echo "<div class='d-flex justify-content-center'>Die Teilnahme an einem Raid ist möglich!</div><br>";
		}
		elseif($raid_running == true)
		{
			echo "<div class='d-flex justify-content-center'>Raid-Teilnehmende sind bereits gestartet</div><br>";
		}
		
		//Bild
		echo "<div class='col-auto p-0 d-flex justify-content-center'><br>";echo $this->Html->image($alliance->pic, ['fullBase' => true, 'class' => 'img-fluid']);echo"</div><br>";
		
		//Beschreibung
		echo "<div class='d-flex justify-content-center'><br>".$alliance->description."</div><br>";
				
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