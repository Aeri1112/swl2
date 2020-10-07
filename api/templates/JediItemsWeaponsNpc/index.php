<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Collection\CollectionInterface $jediItemsWeaponsNpc
 */
?>
<div class="jediItemsWeaponsNpc index content">
    <?= $this->Html->link(__('New Jedi Items Weapons Npc'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Jedi Items Weapons Npc') ?></h3>
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
                <?php foreach ($jediItemsWeaponsNpc as $jediItemsWeaponsNpc): ?>
                <tr>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->itemid) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->ownerid) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->position) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->name) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->img) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->sizex) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->sizey) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->price) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->qlvl) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->uni) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->crafted) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->nodrop) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->weight) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->reql) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->reqs) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->consumable) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->mindmg) ?></td>
                    <td><?= $this->Number->format($jediItemsWeaponsNpc->maxdmg) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->stat1) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->stat2) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->stat3) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->stat4) ?></td>
                    <td><?= h($jediItemsWeaponsNpc->stat5) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $jediItemsWeaponsNpc->itemid]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $jediItemsWeaponsNpc->itemid]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $jediItemsWeaponsNpc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsWeaponsNpc->itemid)]) ?>
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
