<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $w = $session->read('writable');?>
<div id="title">
	<h1><?php __('Routing')?>&gt;&gt;<?php echo __('ingress')?></h1>
	<ul id="title-search">
        <li>
        	<form  action="<?php echo $this->webroot?>gatewaygroups/view_ingress"  method="get">
	        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
	        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="search">
            <input type="submit" name="submit" value="" class="search_submit"/>
	       </form>
        </li>
    </ul>
    <ul id="title-menu">
    <?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
    	<li>
			<a class="link_back" href="<?php echo $this->webroot?>clients/index">
				<img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>		
			</li>
		<?php }?>
		<?php if(array_keys_value($this->params,'url.viewtype','') != 'product'){?>
			<li>
				<a class="link_back" href="<?php echo $this->webroot?>products/product_list">
					<img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?>
				</a>		
			</li>
		<?php }?>
			<?php if ($w == true) {?>
			<li>
				<a class="link_btn" href="<?php echo $this->webroot ?>uploads/ingress"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/import.png"><?php __('Import')?></a>
			</li>
			<li><a class="link_btn" href="<?php echo $this->webroot ?>downloads/ingress"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/export.png"><?php __('download')?></a></li>

			
				
							<li>
				<a  class="link_btn" href="<?php echo $this->webroot?>gatewaygroups/add_resouce_ingress<?php  if(isset($_GET ['query'] ['id_clients'])) {echo "?query[id_clients]={$_GET ['query'] ['id_clients']}&viewtype=client";}?>">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('addvoipgateway')?>
      </a>
     </li>
				<?php }?>
			<li>
				<a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('container','<?php echo $this->webroot?>gatewaygroups/del_selected?type=view_ingress', 'ingress trunk');">
			   <i class="icon-remove"></i> <?php echo __('deleteselected')?>
			 	</a>
			</li>
		</ul>
  </div>
	<div id="container">
		<div id="cover"></div>
	<dl id="edit_ip" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('register')?></dd>
    <dd style="margin-top:10px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('username')?></span>:<input id="ip_username" name="ip_username" style="height:20px;width:200px;float:right">
    			<input id="ip_id" style="display:none"/>
    </dd>
    <dd style="margin-top:20px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('password')?></span>:<input id="ip_pass" name="ip_pass" style="height:20px;width:200px;float:right">
    </dd>
	<dd style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
		<input type="button" onclick="updateIp();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('edit_ip');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
<?php if(array_keys_value($this->params,'url.viewtype','') != 'product'){?>
	<ul class="tabs">
		<?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
		  <li ><a href="<?php echo $this->webroot ?>clients/edit/<?php echo $_GET ['query'] ['id_clients']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/> <?php __('basicinfo')?></a></li>   
    <li ><a href="<?php echo $this->webroot?>gatewaygroups/view_egress?query[id_clients]=<?php echo $_GET ['query'] ['id_clients']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php __('egress')?></a></li> 
	    <li  class="active"><a href="<?php echo $this->webroot?>gatewaygroups/view_ingress?query[id_clients]=<?php echo $_GET ['query'] ['id_clients']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php __('ingress')?></a></li> 
	    <?php }else{?>
	    	<li ><a href="<?php echo $this->webroot?>gatewaygroups/view_egress"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php __('egress')?></a></li> 
	    <li  class="active"><a href="<?php echo $this->webroot?>gatewaygroups/view_ingress"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php __('ingress')?></a></li> 
	    <?php }?>
	</ul>
<?php }?>
<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="width: 100%">
	 <form  action=""  method="get">
<table  align="right">
<tbody>
<tr><!--
    <td>
    		<label><?php echo __('gatewayname')?>:</label>
     <?php echo $form->input('name',array('name'=>'name','label'=>false,'div'=>false,'type'=>'text'))?><input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
  		</td>
    <td>
    		<label><?php echo __('gatewayID')?><input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden"> :</label>
				<input  name="id" value="" id="id" type="text">
    </td>
    --><td id="client_cell" class="value">
   	 <label><?php echo __('Carriers')?>:</label>
      <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 53%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
      <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
      <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td class="buttons"><input type="submit" value="<?php __('Search')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<div id="toppage"></div>	
	<?php //*********************查询条件********************************?>
<?php //*********************表格头*************************************?>
		<div>	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">
			<thead>
				<tr>
					<td style="width:6%"><input type="checkbox" onclick="checkAll(this,'container');" value=""/></td>
		 			<td style="width:6%"><?php echo __('host_ip')?>&nbsp;</td>
    			<td style="width:6%">
    			    <?php echo $appCommon->show_order('client_name',__('Carriers',true)) ?>
    			</td>
    			<td style="width:6%">	
    			    <?php echo $appCommon->show_order('resource_id',__('ID',true)) ?>
    			</td>
    			<td style="width:6%">	<?php echo __('alias',true);?></td>
    			<td style="width:6%">
    		   	<?php echo $appCommon->show_order('capacity', __('calllimit',true)) ?>
    			</td>
    			<td style="width:6%">
    			    <?php echo $appCommon->show_order('cps_limit', __('cps',true)) ?>
    			</td>
       <td style="width:6%"><?php echo __('active')?></td>
       <td style="width:6%">
           <?php echo $appCommon->show_order('ip_cnt', __('ofingress',true)) ?>
    			</td>
       <td style="width:6%">  
           <?php echo $appCommon->show_order('client_id',__('ofusers',true))?>
    			</td>
    			<td style="width:6%"><?php __('Routing Plan')?></td>
    			<td style="width:6%"><?php __('proto')?></td>
  			 <td class="last"><?php echo __('action')?></td>
				</tr>
			</thead>
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
			<tbody>
				<tr class="row-<?php echo $i%2+1;?>">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id']?>"/></td>
				<td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/></td >
				<td  align="center">
		    		<a  href="<?php echo $this->webroot?>clients/index?filter_id=<?php echo $mydata[$i][0]['client_id']?>"  title="<?php echo __('edit')?>">
		    			<?php echo $mydata[$i][0]['client_name']?>	
		    		</a>
		   </td>
			  <td  align="center">
				    <a  href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>"  title="<?php echo __('edit')?>">
				    	<?php echo $mydata[$i][0]['resource_id']?>	
		    		</a>
		    </td>
		    <td  align="center">
				    <a  href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>"  title="<?php echo __('edit')?>">
				    	<?php echo $mydata[$i][0]['alias']?>	
		    		</a>
		    </td>
		    <td  align="center"><?php  if(empty($mydata[$i][0]['capacity'])) {echo __("Unlimited");}else{echo  $mydata[$i][0]['capacity']; }?></td>
		    <td ><?php  if(empty($mydata[$i][0]['cps_limit'])) {echo __("Unlimited");}else{echo  $mydata[$i][0]['cps_limit']; }?></td>
		    <td align="center">
		    <?php if($mydata[$i][0]['active']==1){?>
		    <a onclick="return confirm('<?php echo __('confirmdisablegate')?>');"  
			      href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
		    	 	<img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
		  		</a>
		   <?php }else{?>
		     <a onclick="return confirm('<?php echo __('confirmactivegate')?>');"  
			      href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
							<img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png"><?php }?>
					</a>
		  </td>
		  <td align="center"><?php echo $mydata[$i][0]['ip_cnt']?></td>
		  <td align="center"><?php  if(empty($mydata[$i][0]['client_id'])) {echo 0;}else{echo  1; }?></td>
		  <td><a href="<?php echo $this->webroot?>routestrategys/strategy_list?id=<?php echo $mydata[$i][0]['route_strategy_id']?>"><?php echo $mydata[$i][0]['route_strategy_name']?></a></td>
	    	<td><?php echo $appGetewaygroup->proto($mydata[$i])?></td>
	     <td align="center"><?php if ($w == true) {?>
                 <a  title="Edit" href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>?<?php echo $this->params['getUrl']?>"  title="<?php echo __('edit')?>">
	             <i class="icon-edit"></i> </a>
	                      &nbsp;
	         <a   title="<?php echo __('del')?>" onclick="return confirm('Are you sure to delete this object?');"
				      href="<?php echo $this->webroot?>gatewaygroups/del/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('del')?>">
				        <i class="icon-remove"></i>
				      </a><?php }?>
	       </td>
				</tr>
				<tr style="height:0px">
						<td colspan=20>
							<div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2">
								<table>
									<tr>
										<td>
											<div id="ipTable<?php echo $i?>" class=" jsp_resourceNew_style_3"></div>
											<script type="text/javascript">
				            createTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['resource_id']?>,<?php echo $i?>);
				          </script>
				         </td>
									</tr>
								</table>
							 </div>
						</td>
				</tr>
		</tbody>
			<?php }?>
	</table>
	</div>
	<?php //*********************表格头*************************************?>		
			<div id="tmppage"><?php echo $this->element('page');?></div>
<?php }?>
</div>
	<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
}
//]]>
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('table tbody:nth-child(2n+1) tr').addClass('row-1').removeClass('row-2');
	jQuery('table tbody:nth-child(2n+1) tr').addClass('row-2').removeClass('row-1');
	});
</script>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button">
</div>
