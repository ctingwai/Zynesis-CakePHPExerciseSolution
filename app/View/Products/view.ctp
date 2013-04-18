<h2><?php echo $product['Product']['title'] ?></h2>
<?php
$this->Html->addCrumb('View Product', Router::url(array('action' => 'view')));
echo '<p>Your are here: ' . $this->Html->getCrumbs(' > ', 'Home') . '</p>';
?>
<div style="font-size:150%;text-align:center;margin-bottom:100px">
<?php
//Get product image
$image = $this->Html->getImgPath($product['Product']['title']);
echo $this->Html->image($image, array('width' => '150', 'height' => '150')) . '<br />';

echo '<strong>Product: </strong>' . $product['Product']['title'] . '<br />';
echo '<strong>Price: </strong>' . $this->Number->currency($product['Product']['price']);
unset($product);
?>
</div>
<?php
echo $this->element('../Comments/view', array(
	'pid' => $this->passedArgs['pid']
));
?>
