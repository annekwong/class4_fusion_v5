<?php if(!isset($tabs) || empty($tabs)){?>
<?php }else{?>
<ul class="tabs">
	<?php foreach($tabs as $title=>$arr){?>
		<?php if(is_array($arr)){?>
		<li <?php if(array_keys_value($arr,'active',false)){?>class="active"<?php }?>>
			<a class="glyphicons no-js <?php echo array_keys_value($arr,'icon', '')?>" href="<?php echo $this->webroot?><?php echo array_keys_value($arr,'url',$this->params['url']['url'])?>" <?php if(array_keys_value($arr,'active',false)){?>onclick="return false"<?php }?>><i></i><?php echo $title?></a>
		</li>
		<?php }else{?>
		<li>
			<a href="<?php echo $this->webroot?><?php echo $arr?>"><?php echo $title?></a>
		</li>
		<?php }?>
	
	<?php }?>
</ul>
<?php }?>
<?php ?>