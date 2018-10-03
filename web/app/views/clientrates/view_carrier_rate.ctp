<?php echo $this->element("search")?>
<ul id="title-menu">
	<li>
     <a class="link_btn list-export" href="<?php echo $this->webroot?>clientrates/view_carrier_rate/true">
     		<!--<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png">--> <?php echo __('download',true);?>
     </a>
  </li>
</ul>
<div style="margin-top: 20px;">
<form  method="get"  id="route_form">
&nbsp;
<?php echo __('Route Plan Prefix',true);?>: 
		<?php		  	echo $form->input('route_prefix',array('id'=>'route_prefix','onchange'=>" $(this).parent().submit();",
		'selected'=>isset($_GET['route_prefix'])?$_GET['route_prefix']:'','name'=>'route_prefix','style'=>'width:180px','empty'=>' ','options'=>$route_prefix,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));		?>
  </form>
 </div>
<?php if(isset($p)){?>
	<?php echo  $this->element('rate/client_rate');?>
<?php }else{?>

<div id="noRows1" class="msg"><?php echo __('no_data_found',true);?></div>

<?php }?>

