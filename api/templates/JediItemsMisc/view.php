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
            <?= $this->Html->link(__('Edit Jedi Items Misc'), ['action' => 'edit', $jediItemsMisc->itemid], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Jedi Items Misc'), ['action' => 'delete', $jediItemsMisc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsMisc->itemid), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Jedi Items Misc'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Jedi Items Misc'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jediItemsMisc view content">
            <h3><?= h($jediItemsMisc->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Position') ?></th>
                    <td><?= h($jediItemsMisc->position) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($jediItemsMisc->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Img') ?></th>
                    <td><?= h($jediItemsMisc->img) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uni') ?></th>
                    <td><?= h($jediItemsMisc->uni) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nodrop') ?></th>
                    <td><?= h($jediItemsMisc->nodrop) ?></td>
                </tr>
                <tr>
                    <th><?= __('Consumable') ?></th>
                    <td><?= h($jediItemsMisc->consumable) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat1') ?></th>
                    <td><?= h($jediItemsMisc->stat1) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat2') ?></th>
                    <td><?= h($jediItemsMisc->stat2) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat3') ?></th>
                    <td><?= h($jediItemsMisc->stat3) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat4') ?></th>
                    <td><?= h($jediItemsMisc->stat4) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stat5') ?></th>
                    <td><?= h($jediItemsMisc->stat5) ?></td>
                </tr>
                <tr>
                    <th><?= __('Itemid') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->itemid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uniqueid') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->uniqueid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Droptime') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->droptime) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ownerid') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->ownerid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizex') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->sizex) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sizey') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->sizey) ?></td>
                </tr>
                <tr>
                    <th><?= __('Price') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->price) ?></td>
                </tr>
                <tr>
                    <th><?= __('Qlvl') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->qlvl) ?></td>
                </tr>
                <tr>
                    <th><?= __('Crafted') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->crafted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Weight') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->weight) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reql') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->reql) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reqs') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->reqs) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mindmg') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->mindmg) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maxdmg') ?></th>
                    <td><?= $this->Number->format($jediItemsMisc->maxdmg) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
