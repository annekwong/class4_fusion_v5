<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
//选择代理商或者客户或者卡  由子页面调用
function choose(tr){
document.getElementById('res_name').value = tr.cells[1].innerHTML.trim();
document.body.removeChild(document.getElementById("infodivv"));
closeCover('cover_tmp');
	}

function showClients ()
{
    var val = $('#us_type').val();
    var url = null;
    if (val == "cli") 
    			url = "<?php echo $this->webroot?>cdrs/choose_clients";
    else if (val == "res")
    			url = "<?php echo $this->webroot?>cdrs/choose_resellers";
    else
        url = "<?php echo $this->webroot?>cdrs/choose_cards";
    cover('cover_tmp');
		 loadPage(url,500,400);
}
</script>
<div id="title">
            <h1>
      <?php echo __('complain')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        
<?php $w = $session->read('writable');?>
    
<ul id="title-search">
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
   </ul>
    
        <ul id="title-menu"><!--
      <li><a href="<?php echo $this->webroot?>salestrategs/view">
      <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> 
      <?php __('returnsalesstrategy')?>
      </a></li>
     
       -->
       <?php if ($w == true) {?>
       <li><a class="link_btn" href="<?php echo $this->webroot?>complains/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('addcomplain')?></a></li>
       
       <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>complains/del/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a  class="link_btn"rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>complains/del/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
    <?php }?>
       
       <!--
       <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>
       
        --></ul>
        

    </div>
<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('viewcomplain')?>
<a style="float:right;" href="javascript:void(0)" onclick="closeCover('viewmessage');" title="<?php echo __('close')?>">
<i class="icon-remove"></i></a></div>
	<div style="margin-top:10px;">
	
	<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>

	</div>
</dl>
<div id="container">
<div id="cover"></div>
<div id="cover_tmp"></div>
	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch" style="width:100%;margin-left:1px;">
	<?php echo $form->create ('Complain', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>

<tr>
    <td>
    		<label><?php echo __('titile')?>:</label>
      <input style="height:20px;width:120px;" name="search_title" id="search_title"/>
    </td>
    
    <td>
    		<label><?php echo __('submittime')?>:</label>
      <input style="height:20px;width:120px;" class="Wdate" name="search_submittime_s" id="search_submittime_s" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
      			--
      <input style="height:20px;width:120px;" class="Wdate" name="search_submittime_e" id="search_submittime_e" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
    </td>
    
    <td>
    		<label><?php echo __('modifytime')?>:</label>
      <input style="height:20px;width:120px;" class="Wdate" name="search_modifytime_s" id="search_modifytime_s" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
      			--
      <input style="height:20px;width:120px;" class="Wdate" name="search_modifytime_e" id="search_modifytime_e" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
    </td>
    
    <td>
    		<label><?php echo __('status')?>:</label>
      <select  style="height:20px;width:120px;" name="search_status" id="search_status">
      	<option value=""><?php echo __('all')?></option>
      	<option value="1"><?php echo __('open')?></option>
      	<option value="2"><?php echo __('closed')?></option>
      	<option value="3"><?php echo __('resolved')?></option>
      </select>
    </td>
    
    <?php $login_type = $session->read('login_type');
    if ($login_type == 1) {
    ?>
    <td>
    <label><?php echo __('user')?></label>
    <select id="us_type" name="us_type" style="width:60px;height:20px;">
						<option value="res"><?php echo __('Reseller')?></option>
						<option value="cli"><?php echo __('client')?></option>
						<option value="acc"><?php echo __('account')?></option>
					</select>
					<input class="input in-text" name="res_name" id="res_name" readonly style="width:120;height:20px" onclick="showClients()" id="query-id_clients_name" type="text"><img width="9" height="9" onclick="$('#res_name').val('');" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <?php }?>
<td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('submit')?>"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
		<div id="cover"></div>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
<col width="3%">
<col width="13%">
<col width="13%">
<col width="13%">
<col width="10%">
<col width="20%">
<col width="10%">

<thead>
<tr>

  <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
  		<td><?php echo __('account')?></td>
    <td ><?php echo __('createtime')?></td>
    <td ><?php echo __('modifytime')?></td>
     <td ><?php echo __('titile')?></td>
      <td ><?php echo __('content')?></td>
      <td ><?php echo __('status')?></td>
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody id="producttab">




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['complain_id']?>"/></td>
<td>
		<?php
			if ($mydata[$i][0]['user_type'] == 1){
				echo "<img style='float:left;' src='<?php echo $this->webroot?>images/menuIcon.png'/>&nbsp;&nbsp;".$mydata[$i][0]['reseller_name'];
			}  else if ($mydata[$i][0]['user_type'] == 2){
				echo "<img style='float:left;' src='<?php echo $this->webroot?>images/menuIcon.gif'/>&nbsp;&nbsp;".$mydata[$i][0]['client_name'];
			} else if  ($mydata[$i][0]['user_type'] == 3){
				echo "<img style='float:left;' src='<?php echo $this->webroot?>images/list.png'/>&nbsp;&nbsp;".$mydata[$i][0]['account'];
			}
		?>
</td>
<td><?php  echo $mydata[$i][0]['create_time'];?></td>
<td><?php  echo $mydata[$i][0]['modify_time'];?></td>
<td><?php  echo $mydata[$i][0]['title'];?></td>
<td>
		<?php  echo substr($mydata[$i][0]['content'],0,30)."...";?>
		<a href="javascript:void(0)" onclick="cover('viewmessage');document.getElementById('CompleteContent').innerHTML=<?php echo "'".$mydata[$i][0]['content']."'"?>;">
		
		<?php echo __('viewdetail')?></a>
</td>


<?php  if( $mydata[$i][0]['status']==3){?>
<td  style="color: green"><?php  echo __('resolved'); ?></td>
<?php }?>



<?php  if( $mydata[$i][0]['status']==2){?>
<td  style="color: black"><?php  echo __('closed'); ?></td>
<?php }?>


<?php  if( $mydata[$i][0]['status']==1){?>
<td  style="color: orange"><?php  echo __('open'); ?></td>
<?php }?>





      <td class="last">

     
     
     

     
  <a style="float:left;margin-left:35px;" title="<?php __('viewmessage')?>"   
          href="<?php echo $this->webroot?>complainfeedbacks/view/<?php echo $mydata[$i][0]['complain_id']?>/<?php echo $mydata[$i][0]['status']?>/<?php echo $mydata[$i][0]['user_type']?>">
         <img alt="viewmessage" src="<?php echo $this->webroot?>images/title-search.png">
     </a>
      <?php if ($w == true) {?>  
      <?php  if( $mydata[$i][0]['status']!=2){?>
      <a   title="<?php echo __('edit')?>"  style="float: left;margin-left: 15px;" href="<?php echo $this->webroot?>complains/edit/<?php echo $mydata[$i][0]['complain_id']?>">
      <i class="icon-edit"></i></a>
      <?php
      } 
      ?>
          <a   title="<?php echo __('del')?>" onclick="return confirm('<?php echo __('confirmdel')?>');" style="float: left;margin-left: 15px;"
    
     href="<?php echo $this->webroot?>complains/del/<?php echo $mydata[$i][0]['complain_id']?>"><i class="icon-remove"></i></a>
     
     
     <?php  if( $mydata[$i][0]['status']==3){?>
       <a  title="<?php __('closedcomplain')?>" style="float:left;margin-left:15px;"   onclick="return confirm('<?php echo __('confirmclosedcomplain')?>');" 
         href="<?php echo $this->webroot?>complains/close/<?php echo $mydata[$i][0]['complain_id']?>">
     <img src="<?php echo $this->webroot?>images/flag-null.png"/></a>
<?php }?>




<?php  if( $mydata[$i][0]['status']==2){?>
       <a  title="<?php __('closedcomplain')?>" style="float:left;margin-left:15px;"    onclick="return confirm('<?php echo __('confirmclosedcomplain')?>');" 
         href=''>
         <img src="<?php echo $this->webroot?>images/flag-null.png"/>
     </a>
<?php }?>


<?php  if( $mydata[$i][0]['status']==1){?>
       <a  title="<?php __('closedcomplain')?>" style="float:left;margin-left:15px;"    onclick="return confirm('<?php echo __('confirmclosedcomplain')?>');" 
          href="<?php echo $this->webroot?>complains/close/<?php echo $mydata[$i][0]['complain_id']?>">
     <img src="<?php echo $this->webroot?>images/flag-null.png"/></a>
<?php }?><?php }?>
     
     
      
      </td>
  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>
<div id="tmppage">
<?php echo $this->element('page');?>


</div>
<?php }?>


</div>
<div>

<!-- 高级搜索条件 -->
<?php
			if (!empty($searchform)) {
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($searchform);
			 foreach($d as $k) { ?>
						<script>if (document.getElementById("<?php echo $k?>"))document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }?>
				<script>document.getElementById("advsearch").style.display="block";</script>
<?php }?>
</div>
    
