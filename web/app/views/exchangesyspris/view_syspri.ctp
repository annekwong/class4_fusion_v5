
<div id="title">
  <h1>
    <?php __('Exchange Manage')?>
    &gt;&gt;<?php echo __('Modules',true);?></h1>
  <ul id="title-menu">
  <?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesyspris:view_syspri']['model_w']) {?>
    <li> <a class="link_btn" title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>exchangesyspris/add_syspri/<?php echo $module_id=array_keys_value($this->params,'pass.0')?>"> <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?> </a> </li>
    <?php }?>
    <li> <a class="link_back" href="<?php echo $this->webroot?>exchangesysmodules/view_sysmodule"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/> &nbsp;<?php echo __('goback')?> </a> </li>
    
  </ul>
</div>
<div id="container">
  <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
  <div class="msg"><?php echo __('no_data_found',true);?></div>
  <?php }else{

?>
  <div id="toppage"></div>
  <table class="list">

    <thead>
      <tr>
        <td ><?php echo __('Privilege Name',true);?></td>
        <td ><?php echo __('Privilege List Value',true);?></td>
        <td ><?php echo __('Flag',true);?></td>
        <td><?php echo __('Module List Url',true);?></td>
        <td ><?php echo __('Parent Module',true);?></td>
         <?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesyspris:view_syspri']['model_w']) {?>
         <td  class="last"><?php echo __('action')?></td>
         <?php }?>
      </tr>
    </thead>
    <tbody>
      <?php 

			for ($i=0;$i<$loop;$i++){
		?>
      <tr class="row-1">
        <td align="center"><a style="width:80%;" title="" href="<?php echo $this->webroot?>exchangesyspris/edit_syspri/<?php echo $mydata[$i][0]['module_id']?>/<?php echo $mydata[$i][0]['id'];?>" class="link_width"> <?php echo $mydata[$i][0]['pri_name']?> </a></td>
        <td><?php echo $mydata[$i][0]['pri_val']?></td>
        <td><?php if($mydata[$i][0]['flag']==1){echo 'True';}else{echo 'False';}?></td>
        <td><?php echo $mydata[$i][0]['pri_url']?></td>
        <td><a href="<?php echo $this->webroot?>exchangesysmodules/view_sysmodule"><?php echo $mydata[$i][0]['module_name']?></a></td>
        <?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesyspris:view_syspri']['model_w']) {?>
        <td class="last">
		
          <a   title="<?php echo __('editrole')?>"  href="<?php echo $this->webroot?>exchangesyspris/edit_syspri/<?php echo $mydata[$i][0]['module_id']?>/<?php echo $mydata[$i][0]['id']?>"> <i class="icon-edit"></i> </a>
          
          <a title="<?php echo __('del')?>" onClick="return confirm('Are you sure to delete, roles <?php echo $mydata[$i][0]['pri_name'] ?> ? ');" href="<?php echo $this->webroot?>exchangesyspris/del_syspri/<?php echo $mydata[$i][0]['module_id']?>/<?php echo $mydata[$i][0]['id']?>"> <i class="icon-remove"></i> </a>
        
          
          </td> <?php }?>
      </tr>
      <?php }?>
    </tbody>
  </table>
</div>
<div>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
  <?php }?>
</div>
