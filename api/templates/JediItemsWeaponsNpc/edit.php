<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsWeaponsNpc $jediItemsWeaponsNpc
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $jediItemsWeaponsNpc->itemid],
                ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsWeaponsNpc->itemid), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Jedi Items Weapons Npc'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jediItemsWeaponsNpc form content">
            <?= $this->Form->create($jediItemsWeaponsNpc) ?>
            <fieldset>
                <legend><?= __('Edit Jedi Items Weapons Npc') ?></legend>
                <?php
                    echo $this->Form->control('ownerid');
                    echo $this->Form->control('position');
                    echo $this->Form->control('name');
                    echo $this->Form->control('img');
                    echo $this->Form->control('sizex');
                    echo $this->Form->control('sizey');
                    echo $this->Form->control('price');
                    echo $this->Form->control('qlvl');
                    echo $this->Form->control('uni');
                    echo $this->Form->control('crafted');
                    echo $this->Form->control('nodrop');
                    echo $this->Form->control('weight');
                    echo $this->Form->control('reql');
                    echo $this->Form->control('reqs');
                    echo $this->Form->control('consumable');
                    echo $this->Form->control('mindmg');
                    echo $this->Form->control('maxdmg');
                    echo $this->Form->control('stat1');
                    echo $this->Form->control('stat2');
                    echo $this->Form->control('stat3');
                    echo $this->Form->control('stat4');
                    echo $this->Form->control('stat5');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
