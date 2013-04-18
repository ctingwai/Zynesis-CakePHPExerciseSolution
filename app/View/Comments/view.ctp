<h2>Comments:</h2>
<?php
//Use request action if comments not set
if(!isset($comments))
	$comments = $this->requestAction('Comments/view/pid:' . $this->passedArgs['pid']);

foreach ($comments as $comment) {
	echo '<div style="outline:#777777 solid; padding:10px; margin:10px">';
	echo '<strong>' . $comment['Comment']['user'] . '</strong> wrote: <br /><br />';
	echo $comment['Comment']['comment'];
	echo '</div>';
}
?>
<div style='margin:auto;width:50%'>
<?php
if(isset($this->passedArgs['pid'])) {
	echo $this->Form->create('Comment');
	echo $this->Form->inputs(array(
		'user' => array('label' => 'Name'),
		'comment' => array('label' => 'Comment')
	));
	echo $this->Form->end(array('label' => 'Submit', 'div' => array('style' => 'text-align:center')));
}
?>
</div>
