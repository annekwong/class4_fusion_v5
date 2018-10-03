    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
   
<?php //********************************菜单*********************************8?>
	<?php echo $form->create ('Role', array ('action' => 'add' ));?>

	<div id="title">
    <h1><?php echo __('createrole')?></h1>
    <ul id="title-menu">

    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>roles/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>



<?php //****************************************************页面主体?>


<div class="container">



<table class="form">
<tbody>


<tr>
    <td class="label label2"><?php echo __('RolesName')?>:</td>
    <td class="value value2">
    
    
       		<?php echo $form->input('role_name',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text','maxLength'=>'16'));?>
    </td>
</tr>



<tr>
    <td class="label label2"><?php echo __('defaultmodule')?>:</td>
    <td class="value value2">
    
    
       		<?php echo $form->input('default_sysfunc_id',
 		array('options'=>$sysfuncName,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>

    </td>
</tr>




<tr>
    <td class="label label2"><?php echo __('isviewpw')?>:</td>
    <td class="value value2">
    
    
       		<?php 
       		
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('view_pw',
 		array('options'=>$pw,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>

    </td>
</tr>





<!--<tr>
    <td class="label label2"><?php echo __('userroleuserinfoedit')?>:</td>
    <td class="value value2">
    
    
       		<?php 
       		
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('edit_able',
 		array('options'=>$pw,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>

    </td>
</tr>


--><!--<tr>
    <td class="label label2"><?php echo __('userroleuserinfodel')?>:</td>
    <td class="value value2">
    
    
       		<?php 
       		
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('del_able',
 		array('options'=>$pw,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>

    </td>
</tr>

--><tr>
    <td class="label label2"><?php echo __('Rellerusable')?>:</td>
    <td class="value value2">
       <?php 
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('reseller_able',
 			array('options'=>$pw,'label'=>false ,
 			'div'=>false,'type'=>'select'));
 		?>
    </td>
</tr>
</tbody></table>

<fieldset><legend><?php echo __('funcmodule')?></legend>
<table class="list list-form">
<col style="width: 25%;"/>
<col style="width: 25%;"/>
<col style="width: 25%;"/>
<col style="width: 25%;"/>
<thead>
<tr>
    <td><?php echo __('moduleName')?></td>
    <td  style="text-align: left;">
     	<a id="checkallread" href="javascript:"><?php echo __('allcheck')?></a>
     	<a id="cancelallcheckread"  href="javascript:"><?php echo __('cancelallcheck')?></a>
 		<?php echo __('readable')?>
 	</td>
    <td  style="text-align: left;">
    	<a id="checkallwrite"  href="javascript:"><?php echo __('allcheck')?></a>
 		<a id="cancelallcheckwrite" href="javascript:"><?php echo __('cancelallcheck')?></a>
 		<?php echo __('writable')?>
    </td>
    <td  style="text-align: left;">
    	<a id="checkallexe"  href="javascript:"><?php echo __('allcheck')?></a>
    	<a id="cancelallcheckexe"  href="javascript:"><?php echo __('cancelallcheck')?></a>
 		<?php echo __('executable')?>
 	</td>
</tr>
</thead>
<tbody>
<?php 
	$size=count($sysfunc); 
	for($i=0;$i<$size;$i++){?>
	<tr class="row-<?php  echo ($i%2)+1?>">
		<td	class="<?php if((($sysfunc[$i][0]['func_type'])%2)==0){echo "pos";}else{echo "neg";}?>">
			<label><?php echo __($sysfunc[$i][0]['func_name'])?></label>
			<input type="hidden"  value="<?php echo $sysfunc[$i][0]['system_function_id']?>" name="system_function_id[]" class="input in-hidden"/> 
			<input type="hidden"  value="<?php echo $sysfunc[$i][0]['func_type']?>" name="func_type[]" class="input in-hidden"/> 
	    </td>
	    <?php //***************输出可读模块*********************?>
	    <td class="last">
	        <input type="checkbox" checked="checked"   rel="acl-<?php echo __($sysfunc[$i][0]['system_function_id'])?>" title="<?php echo __('modulecancletitle')?>" value="read" name="readable_check" class="input in-checkbox"/>
	        <input type="hidden"  value="true" name="readable[]" />
	       	<label for="entries-clients-aclNames-read"><?php echo __('readable')?></label>&nbsp;&nbsp;&nbsp;&nbsp;
	    </td>
	    <?php //�?>
	     <td class="pos">
	       <input type="checkbox" checked="checked"  rel="acl-<?php echo __($sysfunc[$i][0]['system_function_id'])?>" title="<?php echo __('modulecancletitle')?>" value="write" name="writable_check" class="input in-checkbox"/>
	       <input type="hidden"  value="true" name="writable[]" />       
	       <label for="entries-clients-aclNames-write"><?php echo __('writable')?></label>&nbsp;&nbsp;&nbsp;&nbsp;
	     </td> 
	     <td class="last">
	<?php //***************可执�?*******************?>
	 		<input type="checkbox" checked="checked"  title="<?php echo __('modulecancletitle')?>"  rel="acl-<?php echo __($sysfunc[$i][0]['system_function_id'])?>" value="write" name="executable_check" class="input in-checkbox"/>
	        <input type="hidden"  value="true" name="executable[]"  />       
	        <label for="entries-clients-aclNames-write"   style="color: green"><?php echo __('executable')?></label>&nbsp;&nbsp;&nbsp;&nbsp;
		 </td>
	</tr>
	<?php }?>
</tbody>
</table>

</fieldset>
    
<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" />
</div>
		<?php echo $form->end();?>
</div>
<script type="text/javascript" 	>
$(function () {
	repaintModules();//处理默认模块中不可以选的模块 
  $('input[type=checkbox]').click(repaintModules); //给所有的checkbox注册事件
  $("#checkallread").click(repaint_read_Modules);//给全选可读的链接注册事件
  $("#checkallwrite").click(repaint_write_Modules);//给全选可写的链接注册事件
  $("#checkallexe").click(repaint_exe_Modules);//给全选可执行的链接注册事�?
  
  $("#cancelallcheckread").click(repaint_read_Modules_cancel);//给全选可读的链接注册事件
  $("#cancelallcheckwrite").click(repaint_write_Modules_cancel);//给全选可写的链接注册事件
  $("#cancelallcheckexe").click(repaint_exe_Modules_cancel);//给全选可执行的链接注册事�?
});
</script>
<!--  -->
<script type="text/javascript">
   jQuery(document).ready(function(){
     jQuery('#RoleRoleName').xkeyvalidate({type:'strNum'});
	    });
</script>


