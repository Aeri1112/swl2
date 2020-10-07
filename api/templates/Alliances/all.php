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
				echo "</td>";
			echo "</tr>";
		}
		?>
			</tbody>
		</table>
		<?php
		echo "<div class='row d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'zurück',
				'/alliances'
			);
			echo "</div>";
		echo "</div>";
		?>