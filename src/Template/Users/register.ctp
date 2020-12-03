<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row pb-5 mb-5">
    <div class="col-md-4"></div>
    <div class="col-md-4 pb-5">
        <?= $this->Flash->render() ?>
        <div class="card">
            <div class="card-body p-5">
                <h3 class="m-0">Register</h3>
                <div class="d-flex justify-content-center mt-30" style="max-height:200px">
                    <?= $this->Html->image("svg/undraw_secure_login_pdn4.svg",['width' => '200','class'=>'mb-1']) ?>
                </div>
                <?php echo $this->Form->create($user) ?>
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
                    <?php echo $this->Form->input('username',['class'=>'form-control','required']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('email',['class'=>'form-control','required']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('password',['class'=>'form-control','required']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('password_match',['class'=>'form-control','type'=>'password','required']) ?>
                </div><br/>
                <?php
                    echo $this->Form->button('Save',['class'=>'btn btn-success mr-3']);
                    echo $this->Html->link('Back to login',['action'=>'login'],['class'=>'btn btn-primary']);
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>