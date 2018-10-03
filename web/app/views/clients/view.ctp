<?php echo $this->element("clients/title")?>
<div id="container">
<?php echo $this->element("clients/search")?>
<div id="list_div">
<?php if($_SESSION['login_type']==1){?>
<?php echo $this->element("clients/list")?>
<?php }else{?>
<?php echo $this->element("clients/list_one")?>
<?php }?>
</div>
<?php echo $this->element("clients/update_password")?>
</div>
<script type="text/javascript">
function show_hide_static(){
	   if(jQuery('#show_static').attr('checked')){
	   		jQuery('img[static=0]').attr("style",'display:inline');	
	   }else{
	        jQuery('img[static=0]').attr("style",'display:none');
	           }
	}
    jQuery('#show_static').change(function(){
				show_hide_static();
	   });
    show_hide_static();
</script>


<?php if (0){?>
<?php  $appCommon->set_back_url();?>
<style type="text/css">
.list {
	font-size:1em;
	background:url("../images/list-row-1.png") repeat-x scroll center bottom #FDFDFD;
	height:37px;
	width:100%;
	border:0px solid #809DBA;
	border-collapse:collapse;
}
.list tbody td {
	border-right:1px solid #E3E5E6;
	border-left:1px solid #809DBA;
	line-height:1.6;
	padding:1px 4px;
}
</style>
<script type="text/javascript">
function edit_balance(){
	var resid = document.getElementById("tmpcliid").value;
	var way = document.getElementById("rate_per_min_action").value;
	var balance = document.getElementById("rate_per_min_value").value;
	jQuery.post("<?php $this->webroot?>clients/edit_balance/"+new Date(),{cliid:resid,way:way,balance:balance},function(data){
		if (data.trim()=='format'){
			jGrowl_to_notyfy('<?php echo __('amountformat')?>',{theme:'jmsg-alert'});
		} else if (data.trim()!='false'){
			jGrowl_to_notyfy('<?php echo __('update_suc')?>',{theme:'jmsg-success',life:100,beforeClose:function(){location=location.pathname+"?edit_id="+data.trim();}});
		} else {
			jGrowl_to_notyfy('<?php echo __('update_fail')?>',{theme:'jmsg-alert'});
		}
	});
}
</script>
<?php $w = $session->read('writable');?>
<div id="title">
 <h1><?php  __('Finance')?>&gt;&gt;<?php echo __('client')?></h1>
	<ul id="title-search">
		 <?php if($_SESSION['login_type']=='1'){?>
    <li>
	    	<form  method="get"   onsubmit=" loading();">
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText"
	    		 title="<?php if (!empty($_GET['search'])) {echo $_GET['search'];}else{ echo __('namesearch');}?>" value="<?php if (!empty($_GET['search'])) echo $_GET['search'];?>" name="search">
	    	</form>
    </li>
    <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    <?php }?>
  </ul>
 	 <ul id="title-menu">
    <?php if ($w == true) {?><li>
    <?php echo $this->element("createnew",Array('url'=>'clients/add'))?>
    </li><?php }?>
       <?php if (isset($extraSearch)) {?>
       <li>
	    	<a class="link_back" href="resellers/reseller_list">
	    		<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
	    		&nbsp;<?php echo __('goback')?>
	    	</a>
	    </li>
       <?php }?>
       <?php if (isset($edit_return)) {?>
        <li>
	    			<a class="link_back" href="<?php echo $this->webroot?>clients/view">
	    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
	    				&nbsp;<?php echo __('goback')?>
	    			</a>
	    		</li>
	    		<li>
	    			<a class="link_back"href="#" onclick="history.go(-1)">
	    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
	    				&nbsp;<?php echo __('back')?>
	    			</a>
	    		</li>
        <?php }?>
	  
        </ul>
    </div>
<div id="container" style="width=960px;min-width: 960px;">
<div id="cover"></div>
<div id="cover_tmp"></div>
<dl id="editbalance" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('editbalance')?></dd>
	<dd style="margin-top:10px;">
						<select style="width:100px;height:18px;" id="rate_per_min_action" name="rate_per_min_action" class="input in-select">
        			<option value="set"><?php echo __('setto')?></option>
        			<option value="inc"><?php echo __('incfor')?></option>
        			<option value="dec"><?php echo __('decfor')?></option>
        			<option value="perin"><?php echo __('persinc')?></option>
        			<option value="perde"><?php echo __('persdec')?></option>
        		</select>
        		<input type="text" id="rate_per_min_value" class="in-decimal input in-text" value="0.000" name="rate_per_min_value" style="height:18px;float:right;width:100px;">
	</dd>
	<dd><input style="display:none" id="tmpcliid"/></dd>
	<dd style="margin-top:10px; margin-left:20%;width:150px;height:auto;">
		<input type="button" onclick="edit_balance();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('editbalance');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="display:none;width: 100%;margin:0 0 10px">
	<form method="get">
	<input type="hidden"  name="adv_search" value="1"/>
<table  style="width:auto;">
<tbody>
<tr>
    <td>
				<label style="display: none"><?php echo __('Name')?>:</label>
				<input class="input in-text" name="name" id="name"style="display: none" />
  		</td>
    <td>
				<label><?php echo __('Client type')?>:</label>
				<select name="client_type">
				<option value=''>All</option>
				<option value="<?php echo Client::CLIENT_CLIENT_TYPE_INGRESS?>">ORIG Carrier</option>
				<option value="<?php echo Client::CLIENT_CLIENT_TYPE_EGRESS?>">TERM Carrier</option>
				</select>
  		</td>

    <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
	<?php //*********************查询条件********************************?>

	
	<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<?php if($_SESSION['login_type']==1){?>
<div id="toppage"></div>
<div id="cover"></div>
<?php }?>
		<div style="min-width:980px;">	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 10%;">
							<?php if($_SESSION['login_type']==1){?>
				<col style="width:10%"/>
				<col style="width:10%"/>
				<?php }?>
			<thead>
				<tr>
    			<td> <?php echo $appCommon->show_order('client_id',__('ID',true))?></td>
    			<td> <?php echo $appCommon->show_order('client_name',__('Name',true));?></td>
    			<?php if($_SESSION['login_type']==1){?>
    				<td>Login as  Carriers  </td>
    				<?php }?>
    			<td> <?php echo $appCommon->show_order('balance',__('Mutual Balance',true))?></td>
    			<td> <?php echo $appCommon->show_order('balance',__('Available Balance',true));?></td>
				  <td><?php echo $appCommon->show_order('mode',__('mode',true));?></td>
				 	<td><?php __('ingress')?></td>
				 	<td><?php __('egress')?></td>
				 		<?php if($_SESSION['login_type']==1){?>
       	<td><?php __('thumbnail')?></td>
       <td class="last"><?php echo __('action')?></td>
       <?php }?>
			</tr>
		 </thead>
		<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
			<tr class="<?php if(empty($mydata[$i][0]['balance'])){echo "s-active nobalance";}?>">
		   <td  align="center">
 					<a  href="<?php  if($_SESSION['login_type']=='1'){$client_id=$mydata[$i][0]['client_id'];echo "<?php echo $this->webroot?>clients/edit/$client_id";}?>">
		    		<?php echo $mydata[$i][0]['client_id']?>	
		     </a>
		   </td>
		   <td  align="center"> 
		   	<?php if($_SESSION['login_type']==1){?>
		   	 	<a  href="<?php echo $this->webroot?>clients/edit/<?php echo $mydata[$i][0]['client_id']?>">
		    		<?php echo $mydata[$i][0]['client_name']?>	
		    	</a>
		   	<?php }else{?>
		   	<?php echo $mydata[$i][0]['client_name']?>	
		   	<?php }?>  
		  
		   </td>
		   	<?php if($_SESSION['login_type']==1){?>
		   	   <td  align="center">   
		   	<a    target="_blank"  href="/client<?php echo $this->webroot?>homes/auth_user?client_id=<?php echo $mydata[$i][0]['client_id']?>">
		    				<img width="16" height="16" src="<?php echo $this->webroot?>images/bLogins.gif">
		    	</a>
		   </td>
		   <?php }?>
		   <td>
		     <?php $my_pi = number_format($mydata[$i][0]['mutual_balance'], 3);  echo  $my_pi;?>
		   </td>
		   <td >
		     <?php $my_pi = number_format($mydata[$i][0]['balance'], 3);  echo  $my_pi;?>
		   </td>

		   	<td align="center">
		   		<?php 
		   			if(array_keys_value($mydata,$i.'.0.mode')==1){echo __('Prepaid');}
		   			elseif(array_keys_value($mydata,$i.'.0.mode')==2){echo __('postpaid');}
		   			else{echo '';}
		   				?>
		   	</td>
		   		<td style="width: auto"><?php echo array_keys_value($mydata,$i.'.0.egress_count',0);?></td>
		  		<td><?php echo array_keys_value($mydata,$i.'.0.ingress_count',0);?></td>
		  	
		  		<?php if($_SESSION['login_type']==1){?>
		   <td  align="center">
		     <a title='<?php __('transationdetail')?>' href="<?php echo $this->webroot?>clientpayments/add_payment/<?php    echo $mydata[$i][0]['client_id']?>">
		   		<img width="16" height="16" src="<?php echo $this->webroot?>images/balanceOperations.gif">
		    	</a>
		     <a title='<?php __('viewcdrlist')?>' href="<?php echo $this->webroot?>cdrreports/summary_reports/client/<?php    echo $mydata[$i][0]['client_id']?>">
		   		 <img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png">
		    	</a>
		     <a title='<?php __('refills')?>' href="<?php echo $this->webroot?>resclis/make_payment_one/client/<?php    echo $mydata[$i][0]['client_id']?>">
		   		<img width="16" height="16" src="<?php echo $this->webroot?>images/m_refill.gif">
		    	</a>
		    </td>
       <td class="last">
                          <div>
						<?php if ($w == true) {?>
           <?php if ($mydata[$i][0]['status']==1){?>
              <a  href="<?php echo $this->webroot?>clients/dis_able/<?php echo $mydata[$i][0]['client_id']?>" >
                <img  title=" <?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
						     </a>
           <?php }?>
           <?php if ($mydata[$i][0]['status']==0){?>
               <a  href="<?php echo  $this->webroot?>clients/active/<?php echo $mydata[$i][0]['client_id']?>">
      							<img  title=" <?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png">
						      </a>
       			<?php }?>
      					 <a title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>clients/edit/<?php echo $mydata[$i][0]['client_id']?>">
      							<i class="icon-edit"></i>
    			 				 <a title="<?php echo __('del')?>" onclick="confirm('Are you sure you remove?')"  href="<?php echo $this->webroot?>clients/del/<?php echo $mydata[$i][0]['client_id']?>"><i class="icon-remove"></i></a><?php }?>
                            </div>
          </td>
          <?php }?>
				</tr>

				<?php }?>
	</table>
	</div>
		<?php if($_SESSION['login_type']==1){?>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
<?php }?>
</div>
<?php }?>

