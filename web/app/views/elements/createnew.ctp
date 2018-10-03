<?php 

	if(!isset($jsAdd) || !$jsAdd){$url=$this->webroot.$url;}
?>
<a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $url?>"><i></i> <?php __('Create New')?></a>
