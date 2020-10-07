<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsJewelryNpc $jediItemsJewelryNpc
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Jedi Items Jewelry Npc'), ['action' => 'edit', $jediItemsJewelryNpc->itemid], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Jedi Items Jewelry Npc'), ['action' => 'delete', $jediItemsJewelryNpc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsJewelryNpc->itemid), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Jedi Items Jewelry Npc'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Jedi Items Jewelry Npc'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jediItemsJewelryNpc view content">
            <h3><?= h($jediItemsJewelryNpc->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Position') ?></th>
                    <td><?= h($jediItemsJewelryNpc->position) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($jediItemsJewelryNpc->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Img') ?></th>
                    <td><?= h($jediItemsJewelryNpc->img) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uni') ?></th>
                    <td><?= h($jediItemsJewelryNpc->uni) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nodrop') ?></th>
                    <td><?= h($jediItemsJewelryNpc->nodrop) ?></td>
                </tr>
                <tr>
                    <th><?= __('Consumable') ?></th>
                    <td><?= h($jediItemsJewelryNpc->consumable) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat1') ?></th>
                    <td><?= h($jediItemsJewelryNpc->stat1) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat2') ?></th>
                    <td><?= h($jediItemsJewelryNpc->stat2) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat3') ?></th>
                    <td><?= h($jediItemsJewelryNpc->stat3) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat4') ?></th>
                    <td><?= h($jediItemsJewelryNpc->stat4) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat5') ?></th>
                    <td><?= h($jediItemsJewelryNpc->stat5) ?></td>
                </tr>
                <tr>
                    <th><?= __('Itemid') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->itemid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ownerid') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->ownerid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizex') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->sizex) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizey') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->sizey) ?></td>
                </tr>
                <tr>
                    <th><?= __('Price') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->price) ?></td>
                </tr>
                <tr>
                    <th><?= __('Qlvl') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->qlvl) ?></td>
                </tr>
                <tr>
                    <th><?= __('Crafted') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->crafted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Weight') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->weight) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reql') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->reql) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reqs') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->reqs) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mindmg') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->mindmg) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maxdmg') ?></th>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->maxdmg) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
