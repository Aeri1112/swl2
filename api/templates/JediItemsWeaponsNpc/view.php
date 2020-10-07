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
            <?= $this->Html->link(__('Edit Jedi Items Weapons Npc'), ['action' => 'edit', $jediItemsWeaponsNpc->itemid], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Jedi Items Weapons Npc'), ['action' => 'delete', $jediItemsWeaponsNpc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsWeaponsNpc->itemid), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Jedi Items Weapons Npc'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Jedi Items Weapons Npc'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jediItemsWeaponsNpc view content">
            <h3><?= h($jediItemsWeaponsNpc->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Position') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->position) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Img') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->img) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uni') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->uni) ?></td>
                </tr>
                <tr>
                    <th><?= __('Crafted') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->crafted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nodrop') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->nodrop) ?></td>
                </tr>
                <tr>
                    <th><?= __('Consumable') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->consumable) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat1') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->stat1) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat2') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->stat2) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat3') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->stat3) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat4') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->stat4) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat5') ?></th>
                    <td><?= h($jediItemsWeaponsNpc->stat5) ?></td>
                </tr>
                <tr>
                    <th><?= __('Itemid') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->itemid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ownerid') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->ownerid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizex') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->sizex) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizey') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->sizey) ?></td>
                </tr>
                <tr>
                    <th><?= __('Price') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->price) ?></td>
                </tr>
                <tr>
                    <th><?= __('Qlvl') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->qlvl) ?></td>
                </tr>
                <tr>
                    <th><?= __('Weight') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->weight) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reql') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->reql) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reqs') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->reqs) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mindmg') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->mindmg) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maxdmg') ?></th>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->maxdmg) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
