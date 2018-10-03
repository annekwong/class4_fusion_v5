<?php 
if($resource_id){
	$saveType='Edit';
}else{
	$saveType='Add';
}
$Titletype=_filter_array(Array('ingress'=>'Ingress','egress'=>'Egress'),$type);
?>
<div id="title">
  <h1><span><?php echo $saveType?>&nbsp;&nbsp;<?php echo $Titletype?>&nbsp;&nbsp;<?php __('Trunk')?></span></h1>
    <ul id="title-menu" />
    	<li>
    		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_<?php echo $type ?>?<?php echo $$hel->getParams('getUrl')?>">
    			<img width="16" height="16" src="<?php echo $this->webroot?>img/rerating_queue.png" alt="Back">&nbsp;<?php echo __('goback',true);?>
    		</a>
    	</li>
  	 </ul>
</div>