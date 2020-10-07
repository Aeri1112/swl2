<?php

	if($img == "mana")
	{
		$img = "poison";
		$skill = "mana";
		$title = "Mana";
		$bg = "bg-info";
		$max = $skills['max_mana'];
	}
	elseif($img == "health")
	{
		$img = "heart";
		$skill = "health";
		$title = "Health";
		$bg = "bg-danger";
		$max = $skills['max_health'];
	}
	elseif($img == "energy")
	{
		$img = "energy";
		$skill = "energy";
		$title = "Energy";
		$bg = "bg-success";
		$max = $skills['max_engery'];
	}
?>
<div class="container col-md-6 p-0 progress-group">
  <div class="progress-group-header align-items-end">
    <i class="cil-globe far progress-group-icon">
    <?php echo $this->Html->image("$img.png", ['pathPrefix' => 'webroot/img/']); ?>
    </i> 
    <div><?= $title ?></div>
    <div class="ml-auto font-weight-bold mr-2"><?= $char->$skill ?></div>
    <div class="text-muted small">(<?= round(($char->$skill*100)/$max,0) ?>%)</div>
  </div>
  <div class="progress-group-bars">
    <div class="progress progress-xs">
      <div class="progress-bar <?= $bg ?>" role="progressbar" style="width: <?= ($char->$skill*100)/$max ?>%" aria-valuenow="<?= ($char->$skill*100)/$max ?>" aria-valuemin="0" aria-valuemax=<?= $max ?>></div>
    </div>
  </div>
</div>