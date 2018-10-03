
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<div id="title">
        <h1><?php echo __('addmodule')?></h1>
</div>
<div id="container">
    <!-- DYNAMIC -->
    	<?php echo $form->create ('Systemfunction', array ('action' => 'add' ));?>
   
<table class="form"  style="width: 649px;">
<col style="width: 40%;"><col style="width: 60%;">
<tbody><tr>
    <td class="label"><?php echo __('functionname')?>:</td>
    <td class="value">
    
   	<?php echo $form->input('func_name',  	array('label'=>false ,'div'=>false,'type'=>'text', 'class'=>'input in-text'));?>
   	
   	这个模块名称在国际化文件里要一定找的到，主要用在给角色划分权限
    </td>
</tr>



<tr>
    <td class="label"><?php echo __('functiontype')?>:</td>
    <td class="value">
    
     		<?php

     		$reseller=array('1'=>__('manage',true),'2'=>__('retail',true),'3'=>__('statis',true),'4'=>__('tool',true),'5'=>__('routeSet',true),
     		'6'=>__('systemc',true),'7'=>__('system',true));
     		echo $form->input('func_type',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select'));?>
        

    </td>
</tr>

<tr>
    <td class="label"><?php echo __('functionurl')?>:</td>
    <td class="value">
    	<?php echo $form->input('func_url',  	array('label'=>false ,'div'=>false,'type'=>'text','value'=>'systemfunctions/view', 'class'=>'input in-password'));?>
    	url格式:systemfunctions/view(systemfunctions为控制器名字,view方法名)
    </td>
</tr>
<tr>
    <td class="label"><?php echo __('imagename')?>:</td>
    <td class="value">
    
    	<?php echo $form->input('image_name',  	array('label'=>false ,'div'=>false,'type'=>'text','value'=>'menuIcon_013.gif', 'class'=>'input in-password'));?>
图片必须是webroot/img下的图片
    </td>
</tr>

<tr>
    <td class="label"><?php echo __('key_118n')?>:</td>
    <td class="value">
    
    	<?php echo $form->input('key_118n',  	array('label'=>false ,'div'=>false,'type'=>'password', 'class'=>'input in-password'));?>

    </td>
</tr>

<tr>
    <td class="label"><?php echo __('readable')?>:</td>
    <td class="value">
    
  
     		<?php

     		$reseller=array('true'=>'true',	'false'=>'false');
     		echo $form->input('is_read',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<tr>
    <td class="label"><?php echo __('writable')?>:</td>
    <td class="value">
     		<?php

     		$reseller=array('true'=>'true',	'false'=>'false');
     		echo $form->input('is_write',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>

<tr>
    <td class="label"><?php echo __('executable')?>:</td>
    <td class="value">
    
     		<?php

     		$reseller=array('true'=>'true',	'false'=>'false');
     		echo $form->input('is_exe',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>
</tbody></table>
<div class="form-buttons"><input type="submit" value="<?php __('submit')?>" class="input in-submit"></div>
   <!-- DYNAMIC -->
</div>
