<?php if(!isset($rel)){
	$rel = strtolower(preg_replace('/[^\da-zA-Z]+/','_',$title));
}
if(!isset($value)){
	$value = array_keys_value($statistics,$rel,'-');
}

?>
<div rel="<?php echo $rel?>" class="metric <?php echo isset($class) ? $class : ''?>">
	<div class="title">
		<div class="text"><?php echo $title?></div>          
	</div>        
	<div class="number value"><?php echo $value?></div>
	<!--div class="number delta-negative"></div -->        
</div>