<h2 style='display:inline-block'>Products</h2>
<!-- Admin login button -->
<span style='float:right;position:relative;top:10px'>
<?php
if(AuthComponent::user('id'))
	echo $this->Html->link('Logout', array('controller' => 'Administrators', 'action' => 'logout'));
else
	echo $this->Html->link('Admin login', array('controller' => 'Administrators', 'action' => 'login'));
?>
</span>
<?php echo '<p>You are here: ' . $this->Html->getCrumbs(' > ', 'Home') . '</p>' ?>
<!-- Sort products buttons -->
<div style='margin-bottom:20px;text-align:center'>
<?php
echo $this->Form->create(
	'Product', array(
		'inputDefaults' => array(
			'div' => false, 'label' => false
		), 
		'action' => 'index'
	)
);
echo 'Sort By: ' . $this->Form->input(
	'sort', array(
		'type' => 'select',
		'options' => array('Name', 'Price'),
		'style' => 'margin-right: 20px'
	)
);
echo "Order: " . $this->Form->input(
	'order', array(
		'type' => 'select',
		'options'=> array('Ascending', 'Descending'),
		'style' => 'margin-right: 50px'
	)
);
echo $this->Form->end(array('div' => false, 'label' => 'Sort', 'style' => 'position:relative;bottom:5px'));
?>
</div>

<!-- Products listing -->
<table>
	<tr>
		<th>Product</th>
		<th>Price</th>
	</tr>
	<?php foreach($products as $product): ?>
	<tr>
		<td>
			<?php 
			$image = $this->Html->getImgPath($product['Product']['title']);
			echo $this->Html->image($image, array(
				'width' => '30',
				'height' => '30',
				'style' => 'float:left',
				'url' => array(
					'action' => 'view',
					//Named parameter
					'pid' => $product['Product']['id']
				)
			));
			echo $this->Html->link($product['Product']['title'],
				array(
					'action' => 'view',
					//Named parameter
					'pid' => $product['Product']['id']
				)
			);
			?>
		</td>
		<td>
		<?php
		echo $product['Product']['price'];
		echo '<span style="float:right">';
		//Delete button for administrator
		if(AuthComponent::user('id')) {
			echo $this->Html->link(
				'Delete this item',
				array(
					'action' => 'remove',
					'id' => $product['Product']['id']
				)
			);
		}
		echo '</span>';
		?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<div style='text-align:center;font-size:150%'>
<?php
//Add new product button for administrator
if(AuthComponent::user('id'))
	echo $this->Html->link('Add new product', array('action' => 'add'));
?>
</div>

<?php unset($product) ?>
