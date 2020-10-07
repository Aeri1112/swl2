<?php
	echo $this->Flash->render();
	if($open)
	{	
		echo "<div id='pic' class='container-fluid p-0 m-0 position-relative'>";
			echo $this->Html->image("bar1.jpg", ['width' => '100%', 'style' => 'vertical-align:sub;', 'pathPrefix' => 'webroot/img/city/']);
			echo "<div class='position-absolute align-text-top' style='top: 27%; left: 44%; width:11%;'>";
				echo $this->Html->link(
					'Mana 10 Cr.',
					'/city/bar/buy/m',
					['class' => 'neon pink font-weight-bolder', 'style' => 'font-size: calc(5px + 7 * ((100vw - 360px) / 640)); vertical-align:top; text-decoration: none;']
				);
			echo "</div>";
			echo "<div class='position-absolute align-text-top' style='top: 33%; left: 44%; width:11%;'>";
				echo $this->Html->link(
					'Health',
					'/city/bar/buy/h',
					['class' => 'neon pink font-weight-bolder', 'style' => 'font-size: calc(5px + 7 * ((100vw - 360px) / 640)); vertical-align:top; text-decoration: none;']
				);
			echo "</div>";
			echo "<div class='position-absolute align-text-top' style='top: 39%; left: 44%; width:11%;'>";
				echo $this->Html->link(
					'Energy 150 Cr.',
					'/city/bar/buy/e',
					['class' => 'neon pink font-weight-bolder', 'style' => 'font-size: calc(5px + 5 * ((100vw - 360px) / 640)); vertical-align:top; text-decoration: none;']
				);
			echo "</div>";

			//Drink gekauft - zeige drink
			if(isset($_SESSION["drink_mana"]) && $_SESSION["drink_mana"] > 0)
			{
				echo "<div class='position-absolute' style='top: 50%; left: 20%;'>";
				echo $this->Html->link(
					'trinken',
					'/city/bar/drink',
					['class' => 'neon pink font-weight-bolder', 'style' => 'font-size: 18px; text-decoration: none;']
				);
				echo "</div>";
			}
		echo "</div>";

			echo $this->element('bar', [
							"img" => "health"
						]);
			echo $this->element('bar', [
							"img" => "mana"
						]);
			echo $this->element('bar', [
							"img" => "energy"
						]);
	}
?>