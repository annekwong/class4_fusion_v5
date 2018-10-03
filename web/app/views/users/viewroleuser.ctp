<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>
      <?php echo __('usermanager')?>&nbsp;&nbsp;&nbsp;[<?php echo __('Role')?>:<?php echo $session->read("r_role_name");?>]     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>users/viewroleuser"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
        <ul id="title-menu">        
        <li>
    			<a  class="link_back"href="<?php echo $this->webroot?>roles/view">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">	&nbsp;<?php echo __('goback',true);?>    			</a>
    		</li>
      <li><a class="link_btn" href="<?php echo $this->webroot?>blocklists/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> 
      <?php echo __('Batchupdate')?></a></li>
     
       <li><a class="link_btn"  href="<?php echo $this->webroot?>users/add">
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
<td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('Search',true);?>"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="6%">
<col width="18%">
<col width="12%">
<col width="8%">
<col width="8%">
<col width="8%">
<!--<col width="8%">
--><thead>
<tr>
 <td ><?php echo __('active')?></td>
 
     <td ><a href="?orderby=id&amp;itemsPerPage=10"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    &nbsp;<?php echo __('username')?>&nbsp;<a href="?orderby=id_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td ><a href="?orderby=id&amp;itemsPerPage=10"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    &nbsp;<?php echo __('Reseller')?>&nbsp;<a href="?orderby=id_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td ><a href="?orderby=id&amp;itemsPerPage=10"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    &nbsp;<?php echo __('RolesName')?>&nbsp;<a href="?orderby=id_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
 
    
    
    <td ><?php echo __('createtime')?></td><!--
     <td ><?php echo __('userpassword')?></td>
    --><td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center">
                <?php if ($mydata[$i][0]['active']==1){?>
                 
                    <img width="16" height="16" title=" <?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">

            <?php }?>
            
                     <?php if ($mydata[$i][0]['active']==0){?>
          
      <img width="16" height="16" title=" <?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png">

       <?php }?>
					
					</td>

    <td align="center">
    <a   title="<?php __('viewuser')?>"   href="<?php echo $this->webroot?>users/edit/<?php echo $mydata[$i][0]['user_id']?>">
					
					<?php echo $mydata[$i][0]['name']?></a></td>
					
					
					    <td align="center">
    <a      href="javascript:">
					
					<?php  
					
					if(empty($mydata[$i][0]['reseller_name'])){
						echo "root";
					}else{
						echo  $mydata[$i][0]['reseller_name'];
					}
					 ?></a></td>

    <td align="center"><?php echo $mydata[$i][0]['role_name']?></td>
    <td><?php  echo $mydata[$i][0]['create_time'];?></td>


<!--<td><?php echo $mydata[$i][0]['password']?></td>

    
      --><td class="last">
      <a   title="<?php echo __('edituser')?>"  style="float: left;margin-left: 40px;" href="<?php echo $this->webroot?>users/edit/<?php echo $mydata[$i][0]['user_id']?>">
      <i class="icon-edit"></i></a>
          <a  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>users/del/<?php echo $mydata[$i][0]['user_id']?>"><i class="icon-remove"></i></a>
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
