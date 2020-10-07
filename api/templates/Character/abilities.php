<?php
if($skills->rsp > 0)
{
    echo "<div class='text-center'> Du hast noch ".$skills->rsp." Punkt(e) zu verteilen</div>";
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'cns']);
                        echo $this->Form->submit('cns.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else
                        echo $this->Html->image("cns.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']);
                    ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#cnsModal">Constitution (Konstitution) <?= $skills->cns+$tempBonus["tmpcns"] ?></h5>
                        <?php
							if($skills->cns > 0) echo "Basis: ".$skills->cns."";
							if($tempBonus["tmpcns"] > 0) echo " Items: ".$tempBonus["tmpcns"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media my-4">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'spi']);
                        echo $this->Form->submit('spi.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else
                        echo $this->Html->image("spi.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#spiModal">Spirit (Geist) <?= $skills->spi+$tempBonus["tmpspi"] ?></h5>
                        <?php
							if($skills->spi > 0) echo "Basis: ".$skills->spi."";
							if($tempBonus["tmpspi"] > 0) echo " Items: ".$tempBonus["tmpspi"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media my-4">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'tac']);
                        echo $this->Form->submit('tac.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else
                        echo $this->Html->image("tac.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#tacModal">Tactic (Taktik) <?= $skills->tac+$tempBonus["tmptac"] ?></h5>
                        <?php
							if($skills->tac > 0) echo "Basis: ".$skills->tac."";
							if($tempBonus["tmptac"] > 0) echo " Items: ".$tempBonus["tmptac"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'lsa']);
                        echo $this->Form->submit('lsa.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else                    
                        echo $this->Html->image("lsa.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#lsaModal">Lightsaber Attack (Lichtschwert Angriff) <?= $skills->lsa+$tempBonus["tmplsa"] ?></h5>
                        <?php
							if($skills->lsa > 0) echo "Basis: ".$skills->lsa."";
							if($tempBonus["tmplsa"] > 0) echo " Items: ".$tempBonus["tmplsa"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'agi']);
                        echo $this->Form->submit('agi.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else                    
                        echo $this->Html->image("agi.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#agiModal">Agility (Mobilität, Gewandheit) <?= $skills->agi+$tempBonus["tmpagi"] ?></h5>
                        <?php
							if($skills->agi > 0) echo "Basis: ".$skills->agi."";
							if($tempBonus["tmpagi"] > 0) echo " Items: ".$tempBonus["tmpagi"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media my-4">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'itl']);
                        echo $this->Form->submit('itl.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else                    
                        echo $this->Html->image("itl.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#itlModal">Intelligence (Intelligenz, Wissen) <?= $skills->itl+$tempBonus["tmpitl"] ?></h5>
                        <?php
							if($skills->itl > 0) echo "Basis: ".$skills->itl."";
							if($tempBonus["tmpitl"] > 0) echo " Items: ".$tempBonus["tmpitl"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media my-4">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'dex']);
                        echo $this->Form->submit('dex.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else                    
                        echo $this->Html->image("dex.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#dexModal">Dexterity (Geschicklichkeit) <?= $skills->dex+$tempBonus["tmpdex"] ?></h5>
                        <?php
							if($skills->dex > 0) echo "Basis: ".$skills->dex."";
							if($tempBonus["tmpdex"] > 0) echo " Items: ".$tempBonus["tmpdex"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
                <li class="media">
                    <?php
                    if($skills->rsp > 0)
                    {
                        echo $this->Form->create($skills, ['url' => false]);
                        echo $this->Form->hidden('train', ['value' => 'lsd']);
                        echo $this->Form->submit('lsd.gif', ['class' => 'align-self-center mr-3']);
                        echo $this->Form->end();
                    }
                    else                    
                        echo $this->Html->image("lsd.gif", ['class' => 'align-self-center mr-3', 'pathPrefix' => 'webroot/img/']); ?>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1" data-toggle="modal" data-target="#lsdModal">Lightsaber Defence (Lichtschwert Abwehr) <?= $skills->lsd+$tempBonus["tmplsd"] ?></h5>
                        <?php
							if($skills->lsd > 0) echo "Basis: ".$skills->lsd."";
							if($tempBonus["tmplsd"] > 0) echo " Items: ".$tempBonus["tmplsd"]."";
							//echo perm. Bonus: 0 temp. Bonus: 0";
						?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="cnsModal" tabindex="-1" role="dialog" aria-labelledby="cnsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cnsModalLabel">Constitution (Konstitution)</h5>
      </div>
      <div class="modal-body">
        Lebensenergie, Körperkraft, Ausdauer und Schadenspotential im Nahkampf
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="spiModal" tabindex="-1" role="dialog" aria-labelledby="spiModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="spiModalLabel">Spirit (Geist)</h5>
      </div>
      <div class="modal-body">
        Manapool, Machteinsatz, Einsatzgeschwindigkeit und Einsatzstärke
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="tacModal" tabindex="-1" role="dialog" aria-labelledby="tacModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tacModalLabel">Tactic (Taktik)</h5>
      </div>
      <div class="modal-body">
        Taktische Fähigkeiten im Kampf. Diese Fähigkeit macht nur einen geringfügigen, aber eventuell entscheidenen Unterschied. (Später auch für Podrennen)
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="lsaModal" tabindex="-1" role="dialog" aria-labelledby="lsaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lsaModalLabel">Lightsaber Attack (Lichtschwert Angriff)</h5>
      </div>
      <div class="modal-body">
        Esentiell für die Trefferquote mit allen Nahkampfwaffen
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="agiModal" tabindex="-1" role="dialog" aria-labelledby="agiModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agiModalLabel">Agility (Mobilität, Gewandheit)</h5>
      </div>
      <div class="modal-body">
        Wie schnell man sich fortbewegt und wie gut man Angriffe ausführt und ausweicht
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="itlModal" tabindex="-1" role="dialog" aria-labelledby="itlModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itlModalLabel">Intelligence (Intelligenz, Wissen)</h5>
      </div>
      <div class="modal-body">
        Wichtig zum Bau von Gegenständen, entscheidet über deren Qualität. Ebenfalls benötigt für effektiven Machteinsatz. (Der prozentuale Wert in Klammern gibt den absoluten Schadensbonus auf alle Machtangriffe an.)
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="dexModal" tabindex="-1" role="dialog" aria-labelledby="dexModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dexModalLabel">Dexterity (Geschicklichkeit)</h5>
      </div>
      <div class="modal-body">
        Geschicklichkeit ist eine Grundvorraussetztung für den Einsatz von Nahkampfwaffen in Bezug auf Treffergenauigkeit und Schadenspotential. Darüberhinaus verbessert es leicht den Umgang mit Mächten und die Qualität von gebauten Gegenständen.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="lsdModal" tabindex="-1" role="dialog" aria-labelledby="lsdModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lsdModalLabel">Lightsaber Defence (Lichtschwert Abwehr)</h5>
      </div>
      <div class="modal-body">
        Die Gegenfähigkeit zu Lightsaber Attack dient ausschliesslich zum Ausweichen von Nahkampfangriffen.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>