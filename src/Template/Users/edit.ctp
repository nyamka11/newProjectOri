<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?= $this->element("header") ?>
<div class="container users form large-9 medium-8 columns content mt-5">
    <div class="row">
        <div class="col-6"><h3><?= __('Edit User') ?></h3></div>
        <div class="col-6">
            <div class="float-right">
                <?=  $this->Form->postLink(__('Delete'), 
                        ['action' => 'delete', $user->id], 
                        ['class'=>"btn btn-danger",'confirm' => __('Are you sure you want to delete # {0}?', $user->id)],
                    ) 
                ?>
            </div>
        </div>
    </div>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <?php echo $this->Form->input('firstname',['class'=>'form-control','required']) ?>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <?php echo $this->Form->input('lastname',['class'=>'form-control','required']) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= $this->Form->input('username',  array('type'=>'text','label' => "Username",'class'=>"w-100 form-control")); ?>
        </div>
        <div class="form-group">
            <?=  $this->Form->input('email',  array('type'=>'text','label' => "Email",'class'=>"w-100 form-control")); ?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('phone',  array('type'=>'text','label' => "Phone",'class'=>"w-100 form-control")); ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit'),["class"=>"btn btn-success"]) ?>
    <?= $this->Form->end() ?>
</div>
