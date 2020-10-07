<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsJewelryNpc[]|\Cake\Collection\CollectionInterface $jediItemsJewelryNpc
 */
?>
<div class="jediItemsJewelryNpc index content">
    <?= $this->Html->link(__('New Jedi Items Jewelry Npc'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Jedi Items Jewelry Npc') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('itemid') ?></th>
                    <th><?= $this->Paginator->sort('ownerid') ?></th>
                    <th><?= $this->Paginator->sort('position') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('img') ?></th>
                    <th><?= $this->Paginator->sort('sizex') ?></th>
                    <th><?= $this->Paginator->sort('sizey') ?></th>
                    <th><?= $this->Paginator->sort('price') ?></th>
                    <th><?= $this->Paginator->sort('qlvl') ?></th>
                    <th><?= $this->Paginator->sort('uni') ?></th>
                    <th><?= $this->Paginator->sort('crafted') ?></th>
                    <th><?= $this->Paginator->sort('nodrop') ?></th>
                    <th><?= $this->Paginator->sort('weight') ?></th>
                    <th><?= $this->Paginator->sort('reql') ?></th>
                    <th><?= $this->Paginator->sort('reqs') ?></th>
                    <th><?= $this->Paginator->sort('consumable') ?></th>
                    <th><?= $this->Paginator->sort('mindmg') ?></th>
                    <th><?= $this->Paginator->sort('maxdmg') ?></th>
                    <th><?= $this->Paginator->sort('stat1') ?></th>
                    <th><?= $this->Paginator->sort('stat2') ?></th>
                    <th><?= $this->Paginator->sort('stat3') ?></th>
                    <th><?= $this->Paginator->sort('stat4') ?></th>
                    <th><?= $this->Paginator->sort('stat5') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jediItemsJewelryNpc as $jediItemsJewelryNpc): ?>
                <tr>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->itemid) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->ownerid) ?></td>
                    <td><?= h($jediItemsJewelryNpc->position) ?></td>
                    <td><?= h($jediItemsJewelryNpc->name) ?></td>
                    <td><?= h($jediItemsJewelryNpc->img) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->sizex) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->sizey) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->price) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->qlvl) ?></td>
                    <td><?= h($jediItemsJewelryNpc->uni) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->crafted) ?></td>
                    <td><?= h($jediItemsJewelryNpc->nodrop) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->weight) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->reql) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->reqs) ?></td>
                    <td><?= h($jediItemsJewelryNpc->consumable) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->mindmg) ?></td>
                    <td><?= $this->Number->format($jediItemsJewelryNpc->maxdmg) ?></td>
                    <td><?= h($jediItemsJewelryNpc->stat1) ?></td>
                    <td><?= h($jediItemsJewelryNpc->stat2) ?></td>
                    <td><?= h($jediItemsJewelryNpc->stat3) ?></td>
                    <td><?= h($jediItemsJewelryNpc->stat4) ?></td>
                    <td><?= h($jediItemsJewelryNpc->stat5) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $jediItemsJewelryNpc->itemid]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $jediItemsJewelryNpc->itemid]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $jediItemsJewelryNpc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsJewelryNpc->itemid)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
