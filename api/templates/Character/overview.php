<?= $this->Flash->render() ?>
<?php
	if(isset($quest) && !isset($this->request->getParam("?")["noquest"]))
	{
		echo $step_details["einleitungstext"];
		if(isset($quest_output))
		{
			$form = $quest_output[0];
			$time = $quest_output[1];
			$lefttime = $quest_output[2];
			$progress = $quest_output[3];
			$miss = $quest_output[4];
			$win = $quest_output[5];
			$cancel_form = $quest_output[6];
			
			echo $form;
			
			if($progress >= 0 && $lefttime > 0)
			{
				echo "<div id='count'></div>";
				?>
				<script>
					countdown('count', 0, <?= $lefttime ?>);				
				</script>

				<div class="progress">
					<div id="progress" class="progress-bar progress-bar-striped" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<script>
					progress(<?= $progress ?>, <?= $time ?>, <?= $lefttime ?>);					
				</script>
			<?php
			}
			
			if($miss == true)
			{
				echo "<br>".$step_details["misserfolgstext"];
			}
			elseif($win == true)
			{
				echo "<br>".$step_details["erfolgstext"];
			}
			if($progress == null && $lefttime == null)
			{
				echo "<br>".$this->Html->link(
					'skip',
					'?noquest=true',
					['class' => 'btn btn-primary']
				);
			}
		}
		
	}
	else
	{
?>
	<div class="row">
		<div class="col-6">
			<div>
				Name: <?= $char->username ?>
			</div>
			<div>
				Spezies: <?= $char->species ?>
			</div>
			<div>
				Alter: <?= $char->age ?>
			</div>
			<div>
				Größe: <?= $char->size ?>
			</div>
			<div>
				Heimatwelt: <?= $char->homeworld ?>
			</div>
		</div> 
		<div class="col-6">
			<div>
				Level: <?= $skills->level ?>
			</div>
			<div>
				Allianz: <?php if($char->alliance != "0") echo $char->alliance; else echo "keine"; ?>
			</div>
			<div>
				Rang: <?= $char->rank ?>
			</div>
			<div>
				Meister: <?php if($char->masterid != "0") echo $char->masterid; else echo "keinen"; ?>
			</div>
		</div> 
	</div>
	<div class="row text-center">
		<div class="col">
			Action <?php if(isset($levelUp)) echo "Glückwunsch - Levelup!"; ?>
		</div>
	</div>
	<br>
	<div class="container col-md-6 p-0 progress-group">
	  <div class="progress-group-header align-items-end">
		<i class="cil-globe far progress-group-icon">
		<?php echo $this->Html->image("transfer.png", ['pathPrefix' => 'webroot/img/']); ?>
		</i>  
		<div>Ausrichtung</div>
		<div class="ml-auto font-weight-bold mr-2"><?= $side["side"] ?></div>
		<div class="text-muted small">(<?= $side["perc"] ?>%)</div>
	  </div>
	  <div class="progress-group-bars">
		<div class="progress progress-xs">
		  <div class="progress-bar" role="progressbar" style="background: linear-gradient(to right, #dc3545 <?= (40+$side["perc"]) ?>%, white <?= $side["white_begin"] ?>%, #28a745 <?= (60-$side["perc"]) ?>%); width:100%" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	  </div>
	</div>
	<div class="container col-md-6 p-0 progress-group">
	  <div class="progress-group-header align-items-end">
		<i class="cil-globe far progress-group-icon">
		<?php echo $this->Html->image("heart.png", ['pathPrefix' => 'webroot/img/']); ?>
		</i>  
		<div>Health</div>
		<div class="ml-auto font-weight-bold mr-2"><?= $char->health ?></div>
		<div class="text-muted small">(<?= round(($char->health*100)/$skills->max_health,0) ?>%)</div>
	  </div>
	  <div class="progress-group-bars">
		<div class="progress progress-xs">
		  <div class="progress-bar bg-danger" role="progressbar" style="width: <?= ($char->health*100)/$skills->max_health ?>%" aria-valuenow="<?= ($char->health*100)/$skills->max_health ?>" aria-valuemin="0" aria-valuemax=<?= $skills->max_health ?>></div>
		</div>
	  </div>
	</div>
	<div class="container col-md-6 p-0 progress-group">
	  <div class="progress-group-header align-items-end">
		<i class="cil-globe far progress-group-icon">
		<?php echo $this->Html->image("poison.png", ['pathPrefix' => 'webroot/img/']); ?>
		</i> 
		<div>Mana</div>
		<div class="ml-auto font-weight-bold mr-2"><?= $char->mana ?></div>
		<div class="text-muted small">(<?= round(($char->mana*100)/$skills->max_mana,0) ?>%)</div>
	  </div>
	  <div class="progress-group-bars">
		<div class="progress progress-xs">
		  <div class="progress-bar bg-info" role="progressbar" style="width: <?= ($char->mana*100)/$skills->max_mana ?>%" aria-valuenow="<?= ($char->mana*100)/$skills->max_mana ?>" aria-valuemin="0" aria-valuemax=<?= $skills->max_mana ?>></div>
		</div>
	  </div>
	</div>
	<div class="container col-md-6 p-0 progress-group">
	  <div class="progress-group-header align-items-end">
	  <i class="cil-globe far progress-group-icon">
		<?php echo $this->Html->image("energy.png", ['pathPrefix' => 'webroot/img/']); ?>
		</i>
		<div>Energy</div>
		<div class="ml-auto font-weight-bold mr-2"><?= $char->energy ?></div>
		<div class="text-muted small">(<?= round(($char->energy*100)/$skills->max_engery,0) ?>%)</div>
	  </div>
	  <div class="progress-group-bars">
		<div class="progress progress-xs">
		  <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($char->energy*100)/$skills->max_engery ?>%" aria-valuenow="<?= ($char->energy*100)/$skills->max_engery ?>" aria-valuemin="0" aria-valuemax=<?= $skills->max_engery ?>></div>
		</div>
	  </div>
	</div>
	<div class="container col-md-6 p-0 progress-group">
	  <div class="progress-group-header align-items-end">
	  <i class="cil-globe far progress-group-icon">
		<?php echo $this->Html->image("growth.png", ['pathPrefix' => 'webroot/img/']); ?>
		</i>
		<div>Experience</div>
		<div class="ml-auto font-weight-bold mr-2"><?= $skills->xp ?></div>
		<div class="text-muted small">(<?= round(($skills->xp*100)/$skills->next_level_xp,2) ?>%)</div>
	  </div>
	  <div class="progress-group-bars">
		<div class="progress progress-xs">
		  <div class="progress-bar bg-warning" role="progressbar" style="width: <?= ($skills->xp*100)/$skills->next_level_xp ?>%" aria-valuenow="<?= ($skills->xp*100)/$skills->next_level_xp ?>" aria-valuemin="0" aria-valuemax=<?= $skills->xp ?>></div>
		</div>
	  </div>
	</div>
<?php
	}
	
	if($this->request->getAttribute('identity')->id == 20)
	{
		echo "<div id='pic' class='container-fluid p-0 m-0 position-relative'>";
		echo $this->Html->image("bar1.jpg", ['width' => '100%', 'style' => 'position:absolute; vertical-align:sub; z-index:-1;', 'pathPrefix' => 'webroot/img/city/']);
		echo $this->Html->image("questfigur1.png", ['width' => '100%', 'style' => 'vertical-align:sub;', 'pathPrefix' => 'webroot/img/city/']);
		echo "<div class='position-absolute align-text-top' style='top: 62.5%; left: 3.5%; width:67%; font-size: calc(5px + 7 * ((100vw - 360px) / 640));'>
			Lorem ipsum dolor sit amet,
			consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
			sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
			no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
			sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
			At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
			no sea takimata sanctus est Lorem ipsum dolor sit amet.
			</div>";
		echo "</div>";
	}
?>