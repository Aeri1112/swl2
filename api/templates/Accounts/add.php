<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('cake.css') ?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Account $account
 */
?>
<div class="login-box">
        <h1>Register Here</h1>
        <?= $this->Form->create($account) ?>
        <?= $this->Form->control('accountname', ['placeholder' => 'Enter Accountname']); ?>
        <?= $this->Form->control('email', ['placeholder' => 'Enter Email']); ?>
        <?= $this->Form->control('password', ['placeholder' => 'Enter Password']); ?>
        <?= $this->Form->submit('Register') ?>
        <?= $this->Form->end() ?>  
</div>
