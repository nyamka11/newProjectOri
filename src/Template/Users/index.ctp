<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<?= $this->element('header') ?>
<div class="users index large-9 medium-8 columns content table container mt-5">
    <div class="row">
        <div class="col-6"><h3><?= __('Users') ?></h3></div>
        <div class="col-6">
            <div class="float-right">
                <?= $this->Html->link(__('New User'), ['action' => 'add'], ["class"=>"btn btn-info mb-3"]) ?>
            </div>
        </div>
    </div>
    <table cellpadding="0" cellspacing="0" class="w-100">
        <thead class="bg-light">
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('createDate') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Number->format($user->id) ?></td>
                <td><?= h($user->username) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->phone) ?></td>
                <td><?= h($user->createDate) ?></td>
                <td class="actions w-100">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ["class"=>"btn btn-info"]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id],["class"=>"btn btn-secondary"]) ?>
                    <?= $this->Form->postLink(__('Delete'), 
                        ['action' => 'delete', $user->id], 
                        ['class'=>"btn btn-danger",'confirm' => __('Are you sure you want to delete # {0}?', $user->id)],
                    ) 
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
