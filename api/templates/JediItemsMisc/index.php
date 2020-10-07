<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\JediItemsMisc[]|\Cake\Collection\CollectionInterface $jediItemsMisc
 */
?>
<div class="jediItemsMisc index content">
    <?= $this->Html->link(__('New Jedi Items Misc'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Jedi Items Misc') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('itemid') ?></th>
                    <th><?= $this->Paginator->sort('uniqueid') ?></th>
                    <th><?= $this->Paginator->sort('droptime') ?></th>
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
                <?php foreach ($jediItemsMisc as $jediItemsMisc): ?>
                <tr>
                    <td><?= $this->Number->format($jediItemsMisc->itemid) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->uniqueid) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->droptime) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->ownerid) ?></td>
                    <td><?= h($jediItemsMisc->position) ?></td>
                    <td><?= h($jediItemsMisc->name) ?></td>
                    <td><?= h($jediItemsMisc->img) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->sizex) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->sizey) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->price) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->qlvl) ?></td>
                    <td><?= h($jediItemsMisc->uni) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->crafted) ?></td>
                    <td><?= h($jediItemsMisc->nodrop) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->weight) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->reql) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->reqs) ?></td>
                    <td><?= h($jediItemsMisc->consumable) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->mindmg) ?></td>
                    <td><?= $this->Number->format($jediItemsMisc->maxdmg) ?></td>
                    <td><?= h($jediItemsMisc->stat1) ?></td>
                    <td><?= h($jediItemsMisc->stat2) ?></td>
                    <td><?= h($jediItemsMisc->stat3) ?></td>
                    <td><?= h($jediItemsMisc->stat4) ?></td>
                    <td><?= h($jediItemsMisc->stat5) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $jediItemsMisc->itemid]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $jediItemsMisc->itemid]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $jediItemsMisc->itemid], ['confirm' => __('Are you sure you want to delete # {0}?', $jediItemsMisc->itemid)]) ?>
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
