<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div>
 <div id="cover_bb"></div>
<div id="title">
   <h1><?php __('Configuration')?>&gt;&gt;
      <?php echo __('EventLog');  ?>     
  </h1>
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action=""  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
    
    
    <?php  $action=$_SESSION['sst_sys_EventLog'];
    $w=$action['writable'];?>
        <ul id="title-menu">
        <?php if(!empty($w)){ 	?>
        
    
                
                
    <li><a class="link_btn" rel="popup"   href="<?php echo $this->webroot?>eventlogs/delall"  onclick="return confirm('<?php echo __('confirmdel')?>');"  ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li>
    <a  class="link_btn" rel="popup" href="javascript:void(0)" onclick="cover('addproduct')"><i class="icon-remove"></i> <?php echo __('delbytime')?></a>
    
    </li>
           
    
        <li>
   <a   class="link_btn"  title="<?php __('download')?>"  style="float: left; margin-left: 40px;"  href="<?php echo $this->webroot?>eventlogs/export/">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png">  <?php __('download')?></a>
    </li>
  
        <?php }?></ul>
        

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
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:50%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php  __('deletedateeventlog')?></div>
	<div style="margin-top:10px;">
	
			<table class="form" style="width: 449px;">
				<tbody>

				 <tr>
    				<td class="label label2"><?php echo __('startdate')?>:</td>
    				<td class="value value2"><input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="float:left;width:200px;" value="" name="start_date" class="input in-text"></td>
				 </tr>
				 <tr>
    				<td class="label label2"><?php echo __('enddate')?>:</td>
    				<td class="value value2"><input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="float:left;width:200px;" value="" name="end_date" class="input in-text"></td>
				 </tr>
				</tbody>
			</table>
	</div>
	<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;  padding-left: 40px;">
		<input type="button" onclick="delsmsbytime('ComplainContent','<?php echo $this->webroot?>eventlogs/delbytime');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>



<?php //******************************查看具体内容********************************?>
<dl id="addproduct2" class="tooltip-styled" style="display:none;position:absolute;left:20%;top:20%;z-idnex:99;width:500px;height:220px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php  __('viewcontent')?></div>
	<div style="margin-top:10px;">
	
			<table class="form" style="width: 449px;">
				<tbody>

				 <tr>
    				
    				<td >
    				
    				<textarea    id="eve_content" style="width: 448px; height: 114px;"></textarea>
    				</td>
				 </tr>

				</tbody>
			</table>
	</div>
	<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;  padding-left: 40px;">
	
		<input type="button" onclick="closeCover_bb('addproduct2');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>
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
    <td ><a href="?orderby=id&amp;itemsPerPage=10"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    &nbsp;ID&nbsp;<a href="?orderby=id_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td ><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc-x.png">&nbsp;<?php echo __('resellertype')?>&nbsp;<a href="?orderby=c.name_desc&amp;itemsPerPage=10">
    <img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td ><?php echo __('sender')?></td>
 
        <td><?php echo __('date')?></td>
     
         
          <td><?php echo __('info')?></td>
           
         
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center"><?php echo $mydata[$i][0]['event_log_id']?></td>
     <td align="center"><?php if( $mydata[$i][0]['type']==1){echo  __('alerts');}if( $mydata[$i][0]['type']==2){echo  __('notice');} if( $mydata[$i][0]['type']==3){echo  __('error');} ?></td>
     
     
      <td align="center"><?php if( $mydata[$i][0]['sender']==1){  echo 'class4';};if( $mydata[$i][0]['sender']==2){  echo 'class4:Limiter';};
      if( $mydata[$i][0]['sender']==3){  echo 'class4:Router';};if( $mydata[$i][0]['sender']==4){  echo 'Bill';};if( $mydata[$i][0]['sender']==5){  echo 'Bill:Account Checker';};?></td>
       <td align="center"><?php echo $mydata[$i][0]['action_date']?></td>
         <td align="center">
         
        <a  rel="popup"   title="<?php __('viewcontent')?>" href="javascript:void(0)" onclick="cover_bb('addproduct2','<?php echo $this->webroot?>eventlogs/ajax_content/<?php echo $mydata[$i][0]['event_log_id']?>.json')">
             <?php echo $mydata[$i][0]['message']?>
      </a>
    

      </td>
  

    
      <td class="last">

          <a  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>eventlogs/del/<?php echo $mydata[$i][0]['event_log_id']?>"><i class="icon-remove"></i></a>
     
     
      
          
     
     
      
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