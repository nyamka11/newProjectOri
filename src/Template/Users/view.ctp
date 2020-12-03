<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<?= $this->element("header") ?>
<div class="container users view large-9 medium-8 columns content mt-5">
    <div class="row">
        <div class="col-6"><h3><?= h($user->firstname) ?> <?= h($user->lastname) ?></h3></div>
        <div class="col-6">
            <div class="float-right">
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ["class"=>"btn btn-info mb-3"]) ?> 
                <?= $this->Form->postLink(__('Delete'), 
                        ['action' => 'delete', $user->id], 
                        ['class'=>"btn btn-danger mb-3",'confirm' => __('Are you sure you want to delete # {0}?', $user->id)],
                    )  ?>
                <?= $this->Html->link(__('List'), ['action' => 'index'],  ["class"=>"btn btn-info mb-3"]) ?> 
                <?= $this->Html->link(__('New'), ['action' => 'add'],  ["class"=>"btn btn-info mb-3"]) ?> 

                
            </div>
        </div>
    </div>

    <ul class="list-group">
        <li class="list-group-item"><b><?= __('Level') ?></b> : <?= h($user->level) ?></li>
        <li class="list-group-item"><b><?= __('Username') ?></b> : <?= h($user->username) ?></li>
        <li class="list-group-item"><b><?= __('Email') ?></b> : <?= h($user->email) ?></li>
        <li class="list-group-item"><b><?= __('Phone') ?></b> : <?= h($user->phone) ?></li>
        <li class="list-group-item"><b><?= __('CreateDate') ?></b> : <?= h($user->createDate) ?></li>
        <li class="list-group-item"><b><?= __('UpdateDate') ?></b> : <?= h($user->updateDate) ?></li>
    </ul>
</div>
