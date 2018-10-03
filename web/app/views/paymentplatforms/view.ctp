<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div>
 <div id="cover_bb"></div>
<div id="title">
            <h1>
      <?php echo __('paymentgateway');  ?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search"><!--
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action=""  method="post">
        <input type="text" id="search-_q


        " class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        --><!--
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    --></ul>
    


    
    
    
    <?php  $action=$_SESSION['sst_sys_paymentgateway'];
    $w=$action['writable'];?>
        <ul id="title-menu">
        <?php if(!empty($w)){ 	?>
        <li><a      href="<?php echo $this->webroot?>paymentplatforms/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createpaymentflatform')?></a></li>
        <!--
        


    <li><a rel="popup"   href="<?php echo $this->webroot?>eventlogs/delall"  onclick="return confirm('<?php echo __('confirmdel')?>');"  ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li>

	    <a  rel="popup" href="javascript:void(0)" onclick="cover('addproduct')"><i class="icon-remove"></i> <?php echo __('delbytime')?></a>
    
    </li>
           
    
        <li>
   <a     title="<?php __('download')?>"  style="float: left; margin-left: 40px;"  href="<?php echo $this->webroot?>eventlogs/export/">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png">  <?php __('download')?></a>
    </li>
  
        --><?php }?></ul>
        

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
<col width="4%">
<col width="6%">
<col width="6%">
<col width="8%">
<col width="8%">
<col width="6%">


<thead>
<tr>
    <td ></td>
    <td ><?php echo __('canSupport')?></td>
    <td ><?php echo __('connectionaddress')?></td>
 
        <td><?php echo __('accountname')?></td>
     
         
          <td><?php echo __('password')?></td>
           
         
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center"><?php echo $mydata[$i][0]['name']?></td>
     <td align="center"><?php if( $mydata[$i][0]['support']==1){echo  __('yes');}else{echo  __('no');} ?></td>
     
     

         <td align="center"> <?php echo $mydata[$i][0]['ip']?> </td>
  
     <td align="center"> <?php echo $mydata[$i][0]['account']?> </td>
          <td align="center"> <?php echo $mydata[$i][0]['password']?> </td>
    
    
    
          <td class="last">
      <a   title="<?php echo __('edit')?>"  style="float: left;margin-left: 40px;" href="<?php echo $this->webroot?>paymentplatforms/edit/<?php echo $mydata[$i][0]['payment_platform_id']?>">
      <i class="icon-edit"></i></a>
          <a  onclick="alert('正在测试中，请等待');return false;" 
    
    href="#"><?php __('test')?></a>
     
     
      
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

    
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>