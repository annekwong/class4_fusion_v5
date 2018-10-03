<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>
        <?php __('Configuration')?>&gt;&gt;
      <?php echo __('sysfunction')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>roles/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li><!--
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    --></ul>
    
        <ul id="title-menu"><!--
      <li><a   title="<?php echo __('Batchupdateroletitle')?>"  href="<?php echo $this->webroot?>roles/batchupdate"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> 
      <?php echo __('Batchupdaterole')?></a></li>
     
       --><li><a class="link_btn"  title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>systemfunctions/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li><!--
       <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>
       
        --></ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('Blocklist', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>

<tr>
    <td><label><?php echo __('egress')?>:</label>
        
   		<?php echo $form->input('engress_res_id',  
 		array(   'options'=>$egress,'empty'=>__('pleaseselectengress',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
		
    

    </td>
    <td><label><?php echo __('ingress')?>:</label>
    
	    		<?php echo $form->input('ingress_res_id',
 		array('options'=>$ingress,'empty'=>__('All',true),'label'=>false ,
 		
 		'div'=>false,'type'=>'select'));?>
    </td><!--
    <td><label><?php echo __('clientname')?>:</label>
    
					    		<?php 
					    		echo $form->input('client_id',
 		array('options'=>$client,'label'=>false ,
 		
 		'empty'=>__('All',true),'onchange'=>'ajax_ingress("'.$this->webroot.'",this.value)',
 		
 		'div'=>false,'type'=>'select'));?>

    </td>
    --><td><label><?php echo __('prefix')?>:</label>
    
       		<?php echo $form->input('digit',
 		array('label'=>false ,'div'=>false,'type'=>'text'));?>
    
    </td>
<td class="buttons"><input type="submit" class="input in-submit" value="Search"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="12%">
<col width="12%">
<col width="18%">
<col width="8%">
<col width="8%">
<col width="8%">
<col width="8%">
<col width="8%">
<thead>
<tr>
    <td >&nbsp;<?php echo __('functionname')?>&nbsp;</td>
    <td ><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc-x.png">&nbsp;<?php echo __('functiontype')?>&nbsp;<a href="?orderby=c.name_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
     <td ><?php echo __('functionurl')?></td>
      <td>开发状态</td>
    <td ><?php echo __('readable')?></td>
     <td ><?php echo __('writable')?></td>
      <td ><?php echo __('executable')?></td>
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center">
    <a   title="<?php __('viewfunc')?>"   href="<?php echo $this->webroot?>roles/edit/<?php echo $mydata[$i][0]['system_function_id']?>">
					
					<?php $key= $mydata[$i][0]['key_118n'];  __($key) ?></a></td>
    <td><?php
if($mydata[$i][0]['func_type']==1){	echo __('manage');}
					if($mydata[$i][0]['func_type']==2){	echo '帐户';}
					if($mydata[$i][0]['func_type']==3){	echo __('statis');}
					if($mydata[$i][0]['func_type']==4){	echo __('tool');}
					if($mydata[$i][0]['func_type']==5){	echo __('routeSet');}
					if($mydata[$i][0]['func_type']==6){	echo __('systemc');}
					if($mydata[$i][0]['func_type']==7){	echo __('system');}
    
    ?></td>


<td> 	<?php echo $mydata[$i][0]['func_url']; ?></td>
   <td> 	<?php

   
					if($mydata[$i][0]['develop_status']==1){	echo '准备开发';}
					if($mydata[$i][0]['develop_status']==2){	echo '开发中';}
					if($mydata[$i][0]['develop_status']==3){	echo '开发完成';}
					if($mydata[$i][0]['develop_status']==4){	echo '修改BUG';}
					if($mydata[$i][0]['develop_status']==5){	echo '测试中';}
					if($mydata[$i][0]['develop_status']==6){	echo '没有BUG';}
		?></td> 
   
   
   
       <td><?php if(!empty($mydata[$i][0]['is_read'])){	echo __('readable');}else{echo __('noreadable');} ?></td>
             <td><?php if(!empty($mydata[$i][0]['is_write'])){	echo __('writable');}else{echo __('nowritable');} ?></td>
                   <td><?php if(!empty($mydata[$i][0]['is_exe'])){	echo __('executable');}else{echo __('noexecutable');} ?></td>
                     
      <td class="last">
      <a   title="<?php echo __('editrole')?>"  style="float: left;margin-left: 40px;" href="<?php echo $this->webroot?>roles/edit/<?php echo $mydata[$i][0]['system_function_id']?>">
      <i class="icon-edit"></i>
          <a  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>roles/del/<?php echo $mydata[$i][0]['system_function_id']?>"><i class="icon-remove"></i></a>
     
     
      
      </td>
  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>



</div>
<div>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>