
<?= $this->Flash->render() ?>
<div class="login-box">
        <h1>Login Here</h1>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('accountname', ['placeholder' => 'Enter Accountname']); ?>
        <?= $this->Form->control('password', ['placeholder' => 'Enter Password']); ?>
        <?= $this->Form->submit('Login') ?>
        <?= $this->Html->link('forget Password', ['action' => 'forget_password']) ?>
        <?php echo " - "; ?>
        <?= $this->Html->link('create Account', ['action' => 'add']) ?>
        <?= $this->Form->end() ?>  
</div>
