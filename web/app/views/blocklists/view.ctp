<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $w = $session->read('writable')?>
<div id="title">
    <h1>
      <?php __('Routing')?>&gt;&gt;
      <?php echo __('blocklist')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>img/qb-plus.png"></a>-->
    </h1>
			<ul id="title-search">
        <li>
        	<?php //****************************模糊搜索**************************?>
        	<form  action="<?php echo $this->webroot?>blocklists/view"  method="post">
        		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        	</form>
        </li>
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
        <ul id="title-menu">
         <?php if (isset($edit_return)) {?>
        <li>
    			<a href="<?php echo $this->webroot?>blocklists/view" class="link_back">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
        <!--
      <li><a href="<?php echo $this->webroot?>blocklists/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> List Items</a></li>
     
       -->
       <?php if ($w == true) {?>
       <li><a  class="link_btn" href="<?php echo $this->webroot?>blocklists/download"       ><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('download')?></a></li>
       <li><a   class="link_btn" href="<?php echo $this->webroot?>blocklists/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li>
       <?php }?>
     	</ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('Blocklist', array ('action' => 'view' ,'onsubmit'=>""));?>
<table  style="width: 620px;">
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
    <td class="buttons"><input type="submit" class="input in-submit" value="<?php __('Search')?>"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="8%">
<col width="12%">
<col width="12%">
<col width="12%">
<col width="10%">
<col width="8%">



<thead>
<tr>

<td><?php echo $appCommon->show_order('res_block_id',__('ID',true))?> </td>
<td><?php echo $appCommon->show_order('egress_name',__('egress',true))?> </td>
<td><?php echo $appCommon->show_order('ingress_name',__('ingress',true))?> </td>
<td><?php echo $appCommon->show_order('digit',__('prefix',true))?> </td>
<td><?php echo $appCommon->show_order('time_profile',__('time_profile',true))?> </td>
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">

<td><?php echo $mydata[$i][0]['res_block_id']?></td>
    <td align="center"><?php echo $mydata[$i][0]['egress_name']?></td>
    <td><?php
if(empty($mydata[$i][0]['ingress_name'])){
	echo __('All');
}else{
	echo $mydata[$i][0]['ingress_name'];
}
    
    ?></td>

<td><?php echo $mydata[$i][0]['digit']?></td>
<td><?php echo $mydata[$i][0]['time_profile']?></td>
    
      <td class="last">
     				<?php if ($w == true) {?>
     								<a title="<?php echo __('edit')?>"   style="float: left;margin-left: 40px;" href="<?php echo $this->webroot?>blocklists/edit/<?php echo $mydata[$i][0]['res_block_id']?>">
      <i class="icon-edit"></i></a>
          <a title="<?php echo __('del')?>"  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>blocklists/del/<?php echo $mydata[$i][0]['res_block_id']?>/<?php echo $mydata[$i][0]['digit']?>"><i class="icon-remove"></i></a>
     				<?php }?>
     
      
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
<?php }?>
</div>

    
	