
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 pb-5 mb-5">
        <?= $this->Flash->render() ?>
        <h1 class="display-4 text-center">Hamamatsu smart city</h1>
        <div class="card mt-5">
                <div class="card-body p-5">
                <h3 class="m-0">Login</h3>
                <div class="d-flex justify-content-center mt-1" style="max-height:200px">
                    <?= $this->Html->image("svg/undraw_Login_re_4vu2.svg",['width' => '250','class'=>'mb-1']) ?>
                </div>
                <br/>
                <?php echo $this->Form->create() ?>
                <div class="form-group">
                    <?php echo $this->Form->input('username',['class'=>'form-control']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('password',['class'=>'form-control']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Html->link('Нууц үгээ мартсан?',['action'=>'forgotpassword']); ?>
                </div>
                <?php
                    echo $this->Form->button('Login',['class'=>'btn btn-success mr-3']);
                    echo $this->Html->link('Register',['action'=>'register'],['class'=>'btn btn-primary']); 
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>

<style>
    h4 {
        width: 100%; 
        text-align: center; 
        border-bottom: 1px solid #cacaca; 
        line-height: 0.1em;
        margin: 10px 0 20px; 
    } 

    h4 span { 
        background:#fff; 
        padding:0 10px; 
        color:#6d6d6d;
    }
</style>