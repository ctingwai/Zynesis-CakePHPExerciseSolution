<h2>Administrator Login</h2>
<?php
$this->Html->addCrumb('Administrator Login', Router::url(array('action' => 'login'), true));
echo '<p>You are here: ' . $this->Html->getCrumbs(' > ', 'Home') . '</p>';

echo $this->Session->flash('auth');
echo $this->Form->create('Administrator');
?>
<fieldset>
	<legend><?php __('Please enter your username and password'); ?></legend>
	<?php
	echo $this->Form->input('username');
	echo $this->Form->input('password');
	?>
</fieldset>
<?php echo $this->Form->end(__('Login')) ?>
