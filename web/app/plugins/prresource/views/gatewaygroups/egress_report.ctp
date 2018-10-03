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
vertical-align: middle;
}
</style>
<?php $w = $session->read('writable');?>
<div id="title">
 <h1><span><?php __('Statistics')?></span><?php __('routeusage')?></h1>
 <ul id="title-search">
   <li>
		  <form  action=""  method="get">
		   <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($_POST['search'])){echo $_POST['search'];}else{ echo '';}?>"  onclick="this.value=''" name="search">
		  </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu"></ul>
</div>
<div id="container">
<fieldset class="title-block" id="advsearch"  style="width: 100%">
	 <form  action=""  method="get">
		<table  style="width:725px;">
			<tbody>
				<tr>
		    <td><label><?php echo __('gatewayname')?>:</label>
		    		<?php echo $form->input('name',array('name'=>'name','label'=>false,'div'=>false,'type'=>'text'))?>
		    </td>
		    <td>
		    		<label><?php echo __('gatewayID')?>:</label>
						<input  name="id" value="" id="id" type="text">
		    </td>
		    <td class="label"> <?php  __('Carriers')?>  </td>
		    <td id="client_cell" class="value">
		   	 <input type="hidden" id="query-id_clients" value="" name="query[id_clients]" class="input in-hidden">
		     	<input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 73%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
		      <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot ?>images/search-small.png">
		      <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot ?>images/delete-small.png">
		    </td>
		    <td class="buttons"><input type="submit" value="Search" class="input in-submit"></td>
				</tr>
			</tbody>
		</table>
	 </form>
</fieldset>
<div>	
	<table class="list"  style="border:1px solid #809DBA;height: 14px;">
		<thead>
			<tr>
					<td><?php echo __('host_ip')?>&nbsp;</td>
	    		<td>	<?php echo __('id',true);?></td>
	  			<td ><?php echo __('gatewayname')?></td>
	  			<td><?php __('GatewayType')?></td>
	   	  <td>
	   	  	<a href="?order=capacity&sc=asc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-asc.png">
	   	  	</a>
	   	  	<?php echo __('concurrentsize')?>
	   	  	<a href="?order=capacity&sc=desc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-desc.png">
	   	  	</a>
	   	  </td>
	      <td>
	      	<a href="?order=ip_cnt&sc=asc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-asc.png">
	   	  	</a>
	      	<?php echo __('ofingress')?>
	      	<a href="?order=ip_cnt&sc=desc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-desc.png">
	   	  	</a>
	      </td>
	      <td>
	      	<a href="?order=cdr_cnt&sc=asc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-asc.png">
	   	  	</a>		
	      	<?php echo __('usage')?>
	      	<a href="?order=cdr_cnt&sc=desc">
	   	  		<img height="10" width="10" src="<?php echo $this->webroot?>img/list-sort-desc.png">
	   	  	</a>
	      </td>
			</tr>
		</thead>
		<?php 	$mydata =$lists;	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
		<tbody>
			<tr class="row-<?php echo $i%2+1;?>">
				 <td  align="center"  style="font-weight: bold;">
				 		<img   id="image<?php echo $i; ?>" 	onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)" class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/>
					</td >
					<td  align="center">
			   	<?php echo $mydata[$i]['Resource']['alias']?>	
					</td>
					<td  align="center"  ><?php echo $mydata[$i]['Resource']['name']?></td >
					<td>
						<?php if($mydata[$i]['Resource']['ingress']){__('ingress');}?>
						<?php if($mydata[$i]['Resource']['egress']){__('egress');}?>
					</td>
					<td  align="center"><?php  if(empty($mydata[$i]['Resource']['capacity'])) {echo "0";}else{echo number_format( $mydata[$i]['Resource']['capacity'],0)*100/100; }?></td>
					<td align="center"><?php echo count($lists[$i]['ResourceIp'])?></td>
					<td align="center">
							<a  href="<?php echo $this->webroot?>gatewaygroups/view_cdr/<?php echo $mydata[$i]['Resource']['resource_id']?>"  title="<?php echo __('egresscall')?>">
								<?php echo count($mydata[$i]['InRealcdr'])+count($mydata[$i]['ERealcdr'])?>
							</a>
					</td>
			</tr>
			<tr style="height:0px">
					<td colspan=20>
							<div id="ipInfo<?php echo $i?>" style="display:none;margin:10px" class="jsp_resourceNew_style_2" >
								 <table>
											<tr>
												<th><?php echo __('host',true);?></th><th><?php echo __('ip',true);?></th><th><?php echo __('port',true);?></th><th><?php echo __('cps',true);?></th><th><?php echo __('Userd',true);?></th>
											</tr>
											<?php $iR=0?>
											<?php foreach($lists[$i]['ResourceIp'] as $resourceIp):?>
											<?php $iR++?>
											<tr>
												<td><?php echo $iR?></td>
												<td><?php echo $resourceIp['ip']?></td>
												<td><?php echo $resourceIp['port']?></td>
												<td><?php echo $resourceIp['fqdn']?></td>
												<td><?php echo array_keys_value_empty($resourceIp,'use_cnt',0)?></td>
											</tr>
											<?php endforeach?>
										</table>
							</div>
							<div style="height: 0px; clear: right;"></div>
					</td>
			</tr>
		</tbody>
		<?php }?>
	</table>
</div>
</div>
<script type="text/javascript">
//<![CDATA[

var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};

function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);

}



function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>