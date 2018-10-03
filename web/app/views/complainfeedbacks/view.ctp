<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<div id="title">
            <h1>
      <?php echo __('complainmessage')?>      <?php   if($status==2){?><span  style="color: red;margin-left: 250px;"><?php __('nocancomplainfeedback')?></span><?php }?>
                        </h1>
        

    
<ul id="title-search"><!--
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>blocklists/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        --><!--
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    --></ul>
    
        <ul id="title-menu">
      <li><a class="link_back" href="<?php echo $this->webroot?>complains/view">
      <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> 
      <?php __('returncomplain')?>
      </a></li>
     
     <?php   if(($status==1)or($status==3)){?>
            <li><a  class="link_btn"    rel="popup" href="javascript:void(0)" onclick="cover('addproduct')"  >
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('addfeedback')?></a></li>
     
     <?php }?>

<?php if (count($p->getDataArray()) > 0) {?>
 <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>complainfeedbacks/del_feedback/all/<?php echo $id?>/<?php echo $status?>');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>complainfeedbacks/del_feedback/selected/<?php echo $id?>/<?php echo $status?>');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
    <?php }?>       
       
       <!--
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
<td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('Search',true);?>"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>


<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addfeedback')?></div>
	<div style="margin-top:10px;">
	
	<textarea id="ComplainContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" name="data[Complain][content]"></textarea>

	</div>
	<div style="text-align:center">
			<input checked type="radio" class="ss" name="status" value="1"/><?php echo __('open')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="2"/><?php echo __('closed')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="3"/><?php echo __('resolved')?>&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="addfeedback('ComplainContent','<?php echo $this->webroot?>complainfeedbacks/add/<?php echo $id?>',<?php echo $status?>);" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>


<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('viewmessage')?><a style="float:right;" href="javascript:void(0)" onclick="closeCover('viewmessage');" title="<?php echo __('close')?>"><i class="icon-remove"></i></a></div>
	<div style="margin-top:10px;">
	
	<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>

	</div>
</dl>

		<div id="toppage"></div>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
<col width="13%">
<col width="16%">
<col width="15%">
<col width="12%">
<col width="12%">
<col width="12%">
<col width="12%">

<thead>
<tr>

  <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td ><?php echo __('feedbackdate')?></td>
    <td ><?php echo __('content')?></td>
     <td ><?php echo __('user')?></td>
      <td ><?php echo __('routseller')?></td>
      <td ><?php echo __('newstatus')?></td>

    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody id="producttab">




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['feedback_id']?>"/></td>
<td><?php  echo $mydata[$i][0]['followed_up_on'];?></td>
<td>
		<?php  echo substr($mydata[$i][0]['content'],0,10)."...";?>
		<a href="javascript:void(0)" onclick="cover('viewmessage');document.getElementById('CompleteContent').innerHTML=<?php echo "'".$mydata[$i][0]['content']."'"?>;"><?php echo __('viewdetail')?></a>
</td>
<td><?php  echo empty($mydata[$i][0]['user_name'])?__('root',true):$mydata[$i][0]['user_name'];?></td>
<td><?php 

if($mydata[$i][0]['user_type']==1){
	echo __('root',true);
}else{
	echo empty($mydata[$i][0]['reseller_name'])?__('root',true):$mydata[$i][0]['reseller_name'];
}

?></td>


<?php  if( $mydata[$i][0]['fstatus']==3){?>
<td  style="color: red"><?php  echo __('resolved'); ?></td>
<?php }?>



<?php  if( $mydata[$i][0]['fstatus']==2){?>
<td  style="color: green"><?php  echo __('closed'); ?></td>
<?php }?>


<?php  if( $mydata[$i][0]['fstatus']==1){?>
<td  style="color: green"><?php  echo __('open'); ?></td>
<?php }?>



      <td class="last">
 
  
          <a   title="<?php echo __('del')?>" onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>complainfeedbacks/del_feedback/<?php echo $mydata[$i][0]['feedback_id']?>/<?php echo $mydata[$i][0]['complain_id']?>/<?php echo $mydata[$i][0]['status']?>"><i class="icon-remove"></i></a>
     
     
      
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
    
