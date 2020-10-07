<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsMisc $jediItemsMisc
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $jediItemsMisc->itemid],
                ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsMisc->itemid), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Jedi Items Misc'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jediItemsMisc form content">
            <?= $this->Form->create($jediItemsMisc) ?>
            <fieldset>
                <legend><?= __('Edit Jedi Items Misc') ?></legend>
                <?php
                    echo $this->Form->control('uniqueid');
                    echo $this->Form->control('droptime');
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
