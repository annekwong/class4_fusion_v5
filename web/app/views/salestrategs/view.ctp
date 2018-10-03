<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>

      <?php echo __('salesstrategy')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        <?php $w = $session->read('writable');?>

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>blocklists/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
        <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>salestrategs/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
        <!--
      <li><a href="<?php echo $this->webroot?>blocklists/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> List Items</a></li>
     
       --><?php if ($w == true) {?><li><a class="link_btn"  href="<?php echo $this->webroot?>salestrategs/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li><?php }?><!--
       <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>
       
        --></ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('Salestrategy', array ('action' => 'view' ,'onsubmit'=>""));?>
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
<td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('search',true);?>"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="8%">
<col width="18%">
<col width="12%">
<col width="12%">
<col width="8%">
<col width="8%">
<col width="8%">
<col width="8%">
<thead>
<tr>
    <td ><a href="?orderby=id&amp;itemsPerPage=10"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    &nbsp;ID&nbsp;<a href="?orderby=id_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td ><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc-x.png">&nbsp;<?php echo __('route_name')?>&nbsp;<a href="?orderby=c.name_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
  
    <td ><?php echo __('giftcoupon')?></td>
      <td ><?php echo __('pointchangeamount')?></td>
     <td ><?php echo __('newrate_table')?></td>
       <td ><?php echo __('ofcards')?></td>
        <td ><?php echo __('extended_days')?>(<?php echo __('days')?>)</td>
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
    
					
 <a   href="<?php echo $this->webroot?>salestrategs/edit/<?php echo $mydata[$i][0]['sales_strategy_id']?>"   title="<?php echo __('viewsalesstrategy')?>">
				
					 <?php echo $mydata[$i][0]['sales_strategy_id']?></a></td>
    <td><?php echo $mydata[$i][0]['name']?></td>


<td>
 <a   href="<?php echo $this->webroot?>salestrategs/findgift_amount/<?php echo $mydata[$i][0]['sales_strategy_id']?>" 
   title="<?php echo __('viewgift_amount')?>">
			
					 <?php  if(empty($mydata[$i][0]['charges_cnt'])){echo "0";}else{echo $mydata[$i][0]['charges_cnt'];} ?>
					 [<?php __('viewgift_amount')?>]
					 </a></td>
					 
					 
					 <td>
 <a   href="<?php echo $this->webroot?>giftscores/view/<?php echo $mydata[$i][0]['sales_strategy_id']?>" 
   title="<?php echo __('viewpointchangeamount')?>">
			 <?php  if(empty($mydata[$i][0]['bonus_cnt'])){echo "0";}else{echo $mydata[$i][0]['bonus_cnt'];} ?>
					 [<?php __('viewpointchangeamount')?>]
					 </a></td>
<td><?php echo $mydata[$i][0]['rate_table_name']?></td>
<td>
<?php  if(empty($mydata[$i][0]['card_cnt'])){echo "0";}else{echo $mydata[$i][0]['card_cnt'];} ?>
					</td>
    <td><?php echo $mydata[$i][0]['extended_days']?></td>
      <td class="last">
      <?php if ($w == true) {?><a title="<?php echo __('edit')?>"   style="float: left;margin-left: 20px;" href="<?php echo $this->webroot?>salestrategs/edit/<?php echo $mydata[$i][0]['sales_strategy_id']?>">
      <i class="icon-edit"></i></a>
          <a title="<?php echo __('del')?>"  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>salestrategs/del/<?php echo $mydata[$i][0]['sales_strategy_id']?>/<?php echo $mydata[$i][0]['name']?>"><i class="icon-remove"></i></a><?php }?>
     
      
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

    