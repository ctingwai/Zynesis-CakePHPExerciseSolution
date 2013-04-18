<h2>Add Product</h2>
<?php
$this->Html->addCrumb('Add Product', Router::url(array('action' => 'add'), true));
echo '<p>You are here: ' . $this->Html->getCrumbs(' > ', 'Home') . '</p>';
//Add product form
echo $this->Form->create('Product', array('type' => 'file'));
echo $this->Form->input('title');
echo $this->Form->input('price');
echo $this->Form->input('Product.image', array('type' => 'file'));
echo $this->Form->end('Save Product');
?>
