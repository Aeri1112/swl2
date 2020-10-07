<?php
switch ($force) {
    case 'fspee':
        $title = "Force Speed";
        $text = "Erhöht Angriffsgeschwindigkeit und beschleunigt Reisen";
        break;
    case 'fpull':
        $title = "Force Pull";
        $text = "Nützlich um entfernte Gegenstände an sich zu bringen";
        break;
    case 'fseei':
        $title = "Force Seeing";
        $text = "Gegenskill zu Force Blind und Projection. Erhöht Chance Gegenstände zu finden";
        break;
    case 'fjump':
        $title = "Force Jump";
        $text = "Um über Abgründe zu gelangen und im Kampf wesentlich wendiger zu sein";
        break;
    case 'fpush':
        $title = "Force Push";
        $text = "Neutraler Angriff der Gegner gegen eine Wand schleudert. Gegenskill zu Grip";
        break;
    case 'fsabe':
        $title = "Force Saberthrow";
        $text = "Text";
        break;
    case 'fpers':
        $title = "Force Persuasion";
        $text = "Vermag den Gegner von deiner Abwesenheit zu überzeugen. (Effekt: Unsichtbarkeit) Verringert Preise in Geschäften.";
        break;
    case 'fblin':
        $title = "Force Blind";
        $text = "Blendet Gegner";
        break;
    case 'fheal':
        $title = "Force Heal";
        $text = "Heilt Schäden";
        break;
    case 'fprot':
        $title = "Froce Protect";
        $text = "Erzeugt ein temporäres Schutzfeld, das physischen Schaden mindert. Machtschaden wird darüberhinaus auch minimal gemindert";
        break;
    case 'fproj':
        $title = "Force Projection";
        $text = "Erzeugt eine Projektion seiner selbst, die den Gegner ablenken kann";
        break;
    case 'fconf':
        $title = "Force Confuse";
        $text = "Erzeugt Verwirrtheitszustände bei der angewandten Person";
        break;
    case 'fteam':
        $title = "Force Team Heal";
        $text = "Heilt Gruppe";
        break;
    case 'fabso':
        $title = "Force Absorb";
        $text = "Erzeugt ein Temporäres Schutzfeld Feld das Machtschaden fast komplett absorbiert und in Mana umwandelt";
        break;
    case 'fthro':
        $title = "Force Throw";
        $text = "Wirft herumliegende Teile gegen Gegner";
        break;
    case 'fgrip':
        $title = "Force Grip";
        $text = "Würgt Gegner, mit ausreichender Fertigkeit lässt sich der Gegner auch anheben und wegschleudern";
        break;
    case 'fthun':
        $title = "Force Thunderbolt";
        $text = "Entlädt Blitze auf bestimmte Ziele";
        break;
    case 'fdest':
        $title = "Force Destruction";
        $text = "Kraftvolle Schockwelle, die bei ausreichender Fertigkeit zu Explosionen führen kann";
        break;
    case 'frage':
        $title = "Force Rage";
        $text = "Erhöht Angriffsstärke und Geschwindikeit drastisch, auf kosten von Lebensenergie und Mana";
        break;
    case 'fdrai':
        $title = "Force Drain";
        $text = "Stiehlt Lebensenergie von Gegnern und wandelt sie in eigene um";
        break;
    case 'fchai':
        $title = "Force Chainlightning";
        $text = "Breitgefächerte Blitze, die durch Materie dringen und somit mehrere Ziele treffen können";
        break;
    case 'fdead':
        $title = "Force deadly sight";
        $text = "Fokusierte Objekte im Sichtfeld können mit dieser Fähigkeit stark erhitzt werden";
        break;
    case 'frvtl':
        $title = "Force Revitalize";
        $text = "Text";
        break;
    case 'ftnrg':
        $title = "Force Team Energize";
        $text = "Text";
        break;
    
    default:
        # code...
        break;
}
//Grundsatz
if($skills->rfp > 0)
{
    //letzte Reihe fdark geskillt
    if(($force == "fpers" OR $force == "fproj"
        OR $force == "fblin" OR $force == "fconf"
        OR $force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fdead > 0 OR $skills->fdest > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //dritte reihe fdark geskillt
    elseif(($force == "fblin" OR $force == "fconf"
        OR $force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fthun > 0 OR $skills->fchain > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //zweite reihe fdark geskillt
    elseif(($force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fgrip > 0 OR $skills->fdrai > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //erste Reihe fdark geskillt
    elseif(($force == "fprot" OR $force == "fabso")
        AND ($skills->fthro > 0 OR $skills->frage > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); 
    }
	elseif($force == "fteam" AND $skills->fheal == 0)
	{
		echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
	}
    //letzte Reihe flight geskillt
    elseif(($force == "fthro" OR $force == "frage"
        OR $force == "fgrip" OR $force == "fdrai"
        OR $force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fabso > 0 OR $skills->fprot > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //dritte reihe flight geskillt
    elseif(($force == "fgrip" OR $force == "fdrai"
        OR $force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fheal > 0 OR $skills->fteam > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //zweite reihe flight geskillt
    elseif(($force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fblin > 0 OR $skills->fconf > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //erste Reihe flight geskillt
    elseif(($force == "fdead" OR $force == "fdest")
        AND ($skills->fpers > 0 OR $skills->fproj > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); 
    }
	elseif($force == "fchai" AND $skills->fthun == 0)
	{
		echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
	}
    elseif(($force == "frvtl" OR $force == "ftnrg" OR $force == "fsabe")
        AND $skills->lvl < 75)
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); 
    }
    else
    {
        echo $this->Form->create($skills, ['url' => false]);
        echo $this->Form->hidden('train', ['value' => $force]);
        echo $this->Form->submit("$force.gif", ['class' => 'align-self-center mr-3']);
        echo $this->Form->end();
    }
}
else
{
    //letzte Reihe fdark geskillt
    if(($force == "fpers" OR $force == "fproj"
        OR $force == "fblin" OR $force == "fconf"
        OR $force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fdead > 0 OR $skills->fdest > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //dritte reihe fdark geskillt
    elseif(($force == "fblin" OR $force == "fconf"
        OR $force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fthun > 0 OR $skills->fchain > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //zweite reihe fdark geskillt
    elseif(($force == "fheal" OR $force == "fteam"
        OR $force == "fprot" OR $force == "fabso")
        AND ($skills->fgrip > 0 OR $skills->fdrai > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //erste Reihe fdark geskillt
    elseif(($force == "fprot" OR $force == "fabso")
        AND ($skills->fthro > 0 OR $skills->frage > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); 
    }
	elseif($force == "fteam" AND $skills->fheal == 0)
	{
		echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
	}
    //letzte Reihe flight geskillt
    elseif(($force == "fthro" OR $force == "frage"
        OR $force == "fgrip" OR $force == "fdrai"
        OR $force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fabso > 0 OR $skills->fprot > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //dritte reihe flight geskillt
    elseif(($force == "fgrip" OR $force == "fdrai"
        OR $force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fheal > 0 OR $skills->fteam > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //zweite reihe flight geskillt
    elseif(($force == "fthun" OR $force == "fchai"
        OR $force == "fdead" OR $force == "fdest")
        AND ($skills->fblin > 0 OR $skills->fconf > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    //erste Reihe flight geskillt
    elseif(($force == "fdead" OR $force == "fdest")
        AND ($skills->fpers > 0 OR $skills->fproj > 0))
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); 
    }
	elseif($force == "fchai" AND $skills->fthun == 0)
	{
		echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
	}
    elseif(($force == "frvtl" OR $force == "ftnrg" OR $force == "fsabe")
        AND $skills->lvl < 75)
    {
        echo $this->Html->image($force."_g.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
    else
    {
        echo $this->Html->image("$force.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
    }
}
?>
<div class="media-body">
    <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#<?= $force ?>Modal"><?php echo $title; ?> <?php if($skills->$force > 0) echo $skills->$force+$tempBonusForces["tmp$force"]; ?></h5>
    <?php
		if($skills->$force > 0)
		{
			echo "Basis: ".$skills->$force." ";
			if($tempBonusForces["tmp$force"] > 0) echo "Items: ".$tempBonusForces["tmp$force"]."";
			//perm. Bonus: 0 temp. Bonus: 0";
		}
	?>
</div>
<!-- Modal -->
<div class="modal fade" id="<?= $force ?>Modal" tabindex="-1" role="dialog" aria-labelledby="<?= $force ?>ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?= $force ?>ModalLabel"><?= $title ?></h5>
      </div>
      <div class="modal-body">
        <?= $text ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>