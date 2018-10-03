<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
  <style type="text/css">
.list {
font-size:1em;
background:url("../images/list-row-1.png") repeat-x scroll center bottom #FDFDFD;
height:37px;
width:100%;
border:0px solid #809DBA;
margin:0 auto 0px;
border-collapse:collapse;
}

.list tbody td {
border-right:1px solid #E3E5E6;
border-left:1px solid #809DBA;
line-height:1.6;
padding:1px 4px;
}
</style>
<?php $w = $session->read('writable');?>
<div id="title">
            <h1>

      <?php echo __('client')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li><form  action="<?php echo $this->webroot?>clients/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
        <ul id="title-menu">
      <!--  <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> List Items</a></li>-->
       <?php if ($w == true) {?><li><a class="link_btn" href="<?php echo $this->webroot?>clients/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createclient')?></a></li><?php }?>
       
       <?php if (isset($extraSearch)) {?>
       <li>
    			<a class="link_back" href="<?php echo $this->webroot?>resellers/reseller_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
       <?php }?>
       <!--  <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>-->
        </ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="width: 100%;">
	<?php echo $form->create ('Client', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>
<tr>
    <td><label><?php echo __('Name')?>:</label>
    
     <?php echo $form->input('name',array('label'=>false,'div'=>false,'type'=>'text'))?>
  </td>
    <td><label>Group:</label><select id="search-id_groups" name="search[id_groups]" class="input in-select"><option value=""></option><option value="2">Providers</option><option value="-1">Call Shop</option></select></td>
    <td><label><?php echo __('ORIG')?>:</label>
    
     		<?php echo $form->input('orig_rate_table_id',
 		array('options'=>$rate,'empty'=>__('selectratetable',true),'label'=>false ,'div'=>false,'type'=>'select'));?>
   
    
    </td>
   
    <td><label><?php echo __('dynamicrouteid')?>:</label>
                		<?php echo $form->input('dynamic_route_id',
 		array('options'=>$dyn_route,'empty'=>__('pleasedynselectroute',true),'label'=>false ,'div'=>false,'type'=>'select'));?>

    </td>
    <td class="buttons"><input type="submit" value="Search" class="input in-submit"></td>
</tr>
<tr>
    <td><label><?php echo __('Reseller')?>:</label>
        
 		<?php echo $form->input('reseller_id',
 		array('options'=>$r,'empty'=>__('pleaseselectreseller',true),'label'=>false ,'div'=>false,'type'=>'select'));?>
		
    

    </td>
    <td><label><?php echo __('status')?>:</label>
    
    <?php 
         $st=array('true'=>__('active',true), 'false'=>__('disable',true));
    echo $form->input('status',array('options'=>$st,'empty'=>__('pleaseselectstatus',true),'label'=>false,'div'=>false,'type'=>'select'))?>
    </td>
    <td><label><?php echo __('TERM')?>:</label>
    
         		<?php echo $form->input('term_rate_table_id',
 		array('options'=>$rate,'empty'=>__('selectratetable',true),'label'=>false ,'div'=>false,'type'=>'select'));?>

    </td>
 
    <td><label><?php echo __('staticrouteid')?>:</label>
    
                		<?php echo $form->input('static_route_id',
 		array('options'=>$product,'empty'=>__('pleasestaticselectroute',true),'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

<div id="toppage"></div>



<?php //*********************表格头*************************************?>
		<div>	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">
				<col style="width: 5.8%;">
				<col style="width: 5%;">
				<col style="width: 5%;">
				<col style="width: 10%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 7.5%;">
	<col style="width: 7.5%;">
				<col style="width: 15.5%;">

			<thead>
				<tr>
		 			<td  >
    			<?php echo __('transationdetail')?>&nbsp;</td>
    			<td>	<?php echo __('id',true);?></td>
    			<td></td>
  <td ><?php echo __('Name')?></td>
    
    <td>
    <?php echo __('Reseller')?></td>
    
    <td>
  <?php echo __('balance')?></td>
    
   
          <td>
    <?php echo __('ORIG')?></td>
   
             <td> <?php echo __('mode')?></td>
    
 <td> 查看话单</td>
   <td class="last"><?php echo __('action')?></td>
   
    
    
		</tr>
	</thead>
	</table>
	</div>
	<?php //*********************表格头*************************************?>	




		<?php //*********************循环输出的动态部分*************************************?>	
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
		<div id="resInfo<?php echo $i?>">
	<table class="list">
	<col style="width:6.5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<col style="width: 9.4%;">
	<col style="width: 9.4%;">
	<col style="width: 9.4%;">
	<col style="width: 7.5%;">
	<col style="width: 7.5%;">
	<col style="width: 15.5%;">

								
	<tbody>

				<tr class="row-<?php echo $i%2+1;?>">
				 <td  align="center"  style="font-weight: bold;">
			 <img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/>

		</td >
		    <td  align="center">
		    
		       <a  href="<?php echo $this->webroot?>clients/edit/<?php    echo $mydata[$i][0]['client_id']?>">
		    	<?php echo $mydata[$i][0]['client_id']?>	
		    		</a>
		    </td>
		    <td>
		    			<?php $login_type = $session->read('login_type');
		    							if ($login_type == 1) {?>
		    		<a title="<?php echo __('loginas')?>" target="_blank" href="<?php echo $this->webroot?>homes/auth_user?username=<?php echo $mydata[$i][0]['login']?>&password=<?php echo $mydata[$i][0]['password']?>&lang=chi"><img src="<?php echo $this->webroot?>images/bLogins.gif"/></a>
		    		<?php } else {?>
		    				<a href="javascript:void(0)"><img src="<?php echo $this->webroot?>images/bLogins_disabled.gif"/></a>
		    			<?php }?>
		    	</td>
		    <td  align="center"  >   <a  href="<?php echo $this->webroot?>clients/edit/<?php    echo $mydata[$i][0]['client_id']?>">
		    	<?php echo $mydata[$i][0]['name']?>	
		    		</a></td >
		   
		    <td  align="center"><?php echo $mydata[$i][0]['r_name']?></td>
		     <td ><?php   $my_pi = number_format($mydata[$i][0]['balance'], 3);  echo  $my_pi;?></td>


		    <td align="center">
		   <?php echo $mydata[$i][0]['orate_name']?>
		  </td>
		
		    <td align="center"><?php if($mydata[$i][0]['mode']==1){echo __('Prepaid');}if($mydata[$i][0]['mode']==2){echo __('postpaid');}else{echo '';}?></td>
		   

          		    <td  align="center">
		    
		       <a  href="<?php echo $this->webroot?>clients/edit/<?php    echo $mydata[$i][0]['client_id']?>">
		   <img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png">
		    		</a>
		    </td>

          
                <td class="last">
 
 <?php if ($w == true) {?>
                <?php if ($mydata[$i][0]['status']==1){?>
                  <a  href="<?php echo $this->webroot?>clients/dis_able/<?php echo $mydata[$i][0]['client_id']?>"  style="float: left; margin-left: 20px;">
                    <img width="16" height="16" title=" <?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
      
     </a>
            <?php }?>
            
                     <?php if ($mydata[$i][0]['status']==0){?>
                  <a  href="<?php echo  $this->webroot?>clients/active/<?php echo $mydata[$i][0]['client_id']?>"  style="float: left; margin-left: 20px;">
     
      <img width="16" height="16" title=" <?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png">
   
     </a>
       <?php }?>
    
       <a title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>clients/edit/<?php echo $mydata[$i][0]['client_id']?>"  style="float: left; margin-left: 40px;">
      <i class="icon-edit"></i></a>
    <a title="<?php echo __('del')?>"  href="<?php echo $this->webroot?>clients/del/<?php echo $mydata[$i][0]['client_id']?>"><i class="icon-remove"></i></a><?php }?>
          </td>
          

				</tr>
		
		</tbody>
	<tbody>
</tbody>
</table>
</div>


<?php //＊＊＊＊＊＊＊＊＊＊＊＊＊res_ip＊＊＊＊＊＊＊＊＊＊＊＊＊＊?>
			<div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2">
			<table>
				<tr>
					<td>
					<div id="ipTable<?php echo $i?>" class=" jsp_resourceNew_style_3">

					</div>
					<script type="text/javascript">
				            createClientTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['client_id']?>,<?php echo $i?>);
				          </script></td>
				</tr>
			</table>

			</div>
			<?php //＊＊＊＊＊＊＊＊＊＊＊＊＊res_ip＊＊＊＊＊＊＊＊＊＊＊＊＊＊?>
<?php }?>
<?php //*****************************************循环输出的动态部分*************************************?>	

	

			<div id="tmppage">
<?php echo $this->element('page');?>

</div>

</div>

    