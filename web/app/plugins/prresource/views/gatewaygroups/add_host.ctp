<div id="title">
  <h1>
       <?php echo __('add',true);?>  <?php echo $_GET['gress']=="egress"? "Egress": "" ; echo $_GET['gress']=="ingress"?"Ingress":"" ;?>  <?php echo __('host',true);?>&gt;&gt;
       <font class="editname" title="Name"> <?php echo empty($name)||$name==''?'':"[".$name."]" ; ?></font>
  </h1>
  <ul id="title-menu">
			<li>
    			<a class="link_back" href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl']?>">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php __('goback')?>    			</a>
    		</li>
  </ul>
</div>
<div id="container">
<ul class="tabs">
	<li>
	<?php if($_GET['gress']=='egress'){?>
    	<a href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_egress?/<?php echo array_keys_value($this->params,'pass.0') ?>?<?php echo $this->params['getUrl'] ?>">
    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/><?php __('System Information')?>
    	</a>
    	<?php }else{?>
     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo array_keys_value($this->params,'pass.0') ?>?res_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
      <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?>
     </a>
  <?php }?>
	</li>
	<li  class="active">
    <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_host/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=<?php echo $_GET['gress']?>&<?php echo $this->params['getUrl'] ?>"> 
     		<img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"/><?php echo __('host',true);?>
     </a>  
	</li>
 <li>
   <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=<?php echo $_GET['gress']?>&res_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
      <img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php __('Action')?>
   </a>    
 </li>
 <li>
   <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=egress&resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
   		<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/>Next Rule
   </a>        
 </li>
 <?php if($_GET['gress']=='ingress'){?>
 <li>
   <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_translation_time/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&res_id=<?php echo array_keys_value($this->params,'pass.0')?>">
      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('DigitMapping')?>
   </a>
 </li>
 <li>
   <a href="<?php echo $this->webroot?>fsconfigs/config_info/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&resource_id=<?php echo array_keys_value($this->params,'pass.0')?>">
      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/>Disconnect Code
   </a>
 </li>
 <?php }?>
</ul>
<form  action="<?php echo $this->webroot?>prresource/gatewaygroups/add_host_post?gress=<?php echo $_GET['gress']?>" onsubmit="return checkForm();"  id="hostForm"  method="post">
<input type="hidden" id="resource_id" value="<?php if(!empty($resource_id)){echo  $resource_id ;}else{  echo '';}?>" name="resource_id" class="input in-hidden">
<input type="hidden" id="gress" value="<?php echo $_GET['gress']?>" name="gress" class="input in-hidden">
	<?php if($_GET['gress']=='ingress'){?>
		<fieldset><legend><?php __('Routing Plan')?></legend>
		<table class="form">
		<tr>
		    <td><?php __('Routing Plan')?>:</td>
		    <td>
		 			<?php
						$route_plan_id=$_SESSION['route_plan_id'];
		 				echo $form->input('route_strategy_id',
		 				array('id'=>'route_plan_id','name'=>'route_plan_id','options'=>$route_policy,'selected'=>$route_plan_id,'empty'=>'','label'=>false ,'style'=>'width:249px;','div'=>false,'type'=>'select'));
		 			?>
		    </td>
		</tr>
		</table>
		</fieldset>
<?php }?>
<fieldset>
<legend><a onclick="addItem('ip');return false;" href="#">
<img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Host',true);?></a></legend>
<center>
<table class="list list-form"  id="list_table">
<thead>
    <tr>
        <td width="12%"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('IP/FQDN',true);?></span><span class="tooltip" id="ht-100002-tooltip">Name of an account in JeraSoft yht system (for statistics and reports)</span></td>
        <td width="8%" <?php if($_SESSION['gress']=='egress'){?>style="display:none"<?php }?>><span rel="helptip" class="helptip" id="ht-100003"><?php __('netmask')?></span><span class="tooltip" id="ht-100003-tooltip">Gateway IP-adress. You can specify multiple adresses by dividing them with ";"</span></td>
        <td width="8%"><span rel="helptip" class="helptip" id="ht-100004"><?php __('port')?></span><span class="tooltip" id="ht-100004-tooltip">Technical prefix, that is used to identify users, when multiple clients use same gateway</span></td>
        <td width="8%" class="last"><?php __('systemcap')?></td>
        <td width="8%" class="last">&nbsp;</td>
    </tr>
</thead>
<tbody class="rows" id="rows-ip">
<?php  $size=count($host);
  for($i=0;$i<$size;$i++){?>
  	<tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1?>" style="">
    <td style="width: 200px;" class="value">
       <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['resource_ip_id'] ?>" class="input in-hidden">
        <input type="text" style="width: 200px;" value="<?php $check='false';
         if(strrpos($host[$i][0]['ip'],"/")){
   	    		list($ip, $net) = split('[/]', $host[$i][0]['ip']);
   	    		$host_ip=$ip;
   	    		$netmask=$net;
   				}else{
   	     	$host_ip=$host[$i][0]['ip'];
   	     	$netmask='';
   						}
        	echo empty($host_ip)? $host[$i][0]['fqdn']:$host_ip;
					?>" class="input in-text" check="Ip" name="accounts[<?php echo $i+1?>][ip]" id="ip-ip-<?php echo $i+1?>">
    </td>
    <td style="width: 334px;<?php  if($_SESSION['gress']=='egress'){?>display:none<?php }?>" class="value">
    			<?php 
    			$ii=$i+1;
    			$c=array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24);
    			echo $form->input('client_id',array('options'=>$c,  'id'=>"ip-netmask-$ii",'name'=>"accounts[$ii][netmask]",'selected'=>$netmask,
    			'style'=>'width:180px',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
   
       <td style="width: 350px;" class="value">
       <input type="text" value="<?php echo $host[$i][0]['port'];?>" class="input in-text" name="accounts[<?php echo $i+1?>][port]" id="ip-port-<?php echo $i+1?>" check="Num" maxLength='16'></td>
	  <td width="8%" class="last"  style="text-align: center;">
	  <a title="Capacity"  href="<?php echo $this->webroot?>gatewaygroups/add_host_time/<?php echo $host[$i][0]['resource_ip_id']?>/<?php echo $host_ip?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png"></a>
	  </td>
    <td style="width: 200px;" class="value last"><a href="#" title="delete" rel="delete"  onclick=" $(this).closest('tr').remove();"><i class="icon-remove"></i></a></td>
</tr>
 <?php }?> 
</tbody>
<tbody>
<?php //  用id="tpl-ip"表示  准备复制的hang ?>
<tr style="display:none;" id="tpl-ip" class="  row-2">
    <td class="value"  style="width: 200px;">
        <input type="text" name="_accounts[%n][ip]" class="input in-text" check="Ip" value=""  style="width: 200px;">
    </td>

    
    <td class="value"  style="width: 400px;<?php  if($_SESSION['gress']=='egress'){?>display:none<?php }?>">
<select style="width: 180px;"   name="_accounts[%n][netmask]"   id="netmask0" class="netmask0 input in-select"   >
							<option value="32">32</option>
							<option value="31">31</option>
							<option value="30">30</option>
							<option value="29">29</option>
							<option value="28">28</option>
							<option value="27">27</option>
							<option value="26">26</option>
							<option value="25">25</option>
							<option value="24">24</option>

						</select>
    </td>
    <td class="value"  style="width: 350px;"><input type="text" name="_accounts[%n][port]" class="input in-text" check="Num" maxLength="16" value="5060"></td>
  	 <td width="8%" class="last"  style="text-align: center;"></td>
    <td class="value last"  style="width: 200px;"><a title="delete" rel="delete" href="#"><i class="icon-remove"></i></a></td>
</tr>
</tbody>

</table>
</center>
</fieldset>
<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
<div id="form_footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
   <input type="reset" value="<?php echo __('Reset')?>"    class="input in-submit">
    </div>
    <?php }?>
</form>
<script type="text/javascript" language="JavaScript">
//<![CDATA[
var lastId = '<?php  echo   $size;?>';
var dr_groups = [];

function addItem(type, row)
{


    lastId++;
    if (!row || !row['id']) {
        row = {
            'auth_type': type,
            'name': 'account_' + lastId,
            'proxy_mode': '',
            'orig_enabled': 1,
            'term_enabled': 1
        };
    }
    // fix row values
    for (k in row) { if (row[k] == null) row[k] = ''; }
    
    // prepare row
    var tRow = $('#tpl-'+type).clone(true);//复制准备好的行
    tRow.attr('id', 'row-'+lastId).show();//设置显示
    // set names / values循环行内的每个表单元素
  
    tRow.find('input,select').each(function () {
        var el = $(this);//当前表单元素

                    //准备行的名字  _accounts[%n][id]  替换为accounts[6][id]
        var name = $(this).attr('name').substring(1).replace('%n', lastId);//设置名字(将名字中的%n替换为lastId)  accounts[6][id]
        var field = name.substring(name.lastIndexOf('[')+1, name.length-1);  //id
        el.attr('id', type+'-'+field+'-'+lastId);//设置id  ip-id-6
        el.attr('name', name);
        el.attr('style', $(this).attr('style'));
                     
//        对checkbox的处理
        if (el.attr('type') == 'checkbox') {
//给checkbox注册事件
        	if(field=='need_register'){
        	    el.click(function () {
        	    	if($(this).attr("checked")==true){
        	    		$(this).attr("value",'true');}else{$(this).attr("value",'false');}
        	    });
            	}

            
            if (typeof(row[field]) == 'object') {
                el.attr('checked', jQuery.inArray(1*el.attr('value'), row[field]) != -1 ? 'checked' : '');
                el.attr('name', el.attr('name') + '[]');
            } else {
                el.attr('checked', row[field] ? 'checked' : '');
            }
        } else {
            el.val(row[field]);
        }
    });
    
    // remove of the row  给删除用的图片链接注册事件 
    tRow.find('a[rel=delete]').click(function () {
        $(this).closest('tr').remove(); //找到他最靠近的tr删除之
        return false;
    });

    buildParams(tRow);
    if (row['id']) {
        tRow.appendTo($('#rows-'+type));
    } else {
        tRow.prependTo($('#rows-'+type));//<tbody class="rows" id="rows-ip">  将tr加入tbody
    }
    
    // styles
    if (!row['id']) {
        initForms(tRow);
        initList();
    }
    
}

function buildParams(row)
{
    var s = '';

    if (row.find('input[name*=orig_capacity]').val()){ s += ' / OC: '+row.find('input[name*=orig_capacity]').val();}
    if (row.find('input[name*=term_capacity]').val()) s += ' / TC: '+row.find('input[name*=term_capacity]').val();
    if (row.find('select[name*=protocol]').val()) s += ' / '+row.find('select[name*=protocol] :selected').text();
    if (row.find('input[name*=proxy_mode]').val()) s += ' / P: '+row.find('input[name*=proxy_mode]').val();
    if (row.find('select[name*=id_dr_plans]').val()) s += '<br/>RP: '+row.find('select[name*=id_dr_plans] :selected').text();
    if (row.find('select[name*=orig_rate_table]').val()) s += '<br/>Orig RT: '+row.find('select[name*=orig_rate_table] :selected').text();
    if (row.find('select[name*=term_rate_table]').val()) s += '<br/>Term RT: '+row.find('select[name*=term_rate_table] :selected').text();

    var dr_group = '';
    row.find('input[name*=dr_groups]').each(function() {
        if ($(this).attr('checked')) {
            if (dr_group != '') dr_group += ', ';
        	dr_group += dr_groups[$(this).val()];
        }
    });
    if (dr_group != '') {
        s += '<br/>G: ' + dr_group;
    }
    
	if (s.substring(0, 3) == ' / ') {
		s = s.substring(3);
	}
    if (s.substring(0, 5) == '<br/>') {
        s = s.substring(5);
    }
    if (!s) {
        s = '&mdash; &raquo;';
    }

    row.find('#tpl-params-text').html(s);
    return s;
}
function hideParams()
{
    $('.rows div.params-block:visible').hide().attr('id', '').each(function () {
         buildParams($(this).parent().parent());
    });
}

//live event handlers
$('.rows #tpl-params-block div').live('click', function (e) {
    e.stopPropagation();
});
$('.rows #tpl-params-block div a').live('click', function () {
    hideParams();
    return false;
});
$('.rows #tpl-params-link').live('click', function () {
    var vis = 0;
    var div = $(this).parent().find('div');
    if (div.is(':visible')) vis = 1;
    hideParams();
    if (!vis) {
        div.attr('id', 'tooltip').show();
    }
    return false;
});
$('.rows #tpl-delete-row').live('click', function () {
    $(this).closest('tr').remove();
    return false;
});
$(window).click(hideParams);



//]]>
</script>


<script type="text/javascript"><!--
jQuery(document).ready(function(){
	$('#list_table').find('input[id^=ip-port]').each(function (){
		jQuery(this).xkeyvalidate({type:'Num'}); 
		});
jQuery('#hostForm').submit(function(){
	var post_null=true;
	
        return  post_null;
         });
});
	//jQuery('input[check=Ip]').xkeyvalidate({type:'checkIp'});
  	jQuery('input[check=Num]').xkeyvalidate({type:'Num'});
    jQuery('form').submit(function(){
      var ip_flag=true;
      var flag=true;
      var port_flag=true;
      var ip_null=true;
      var post_null=true;
      var arr = new Array();
      var arr_post= new Array();
      var arr_cum=new Array();
      <?php if($_GET['gress']=='ingress'){?>
      	if(jQuery('#route_plan_id').val()==''||jQuery('#route_plan_id').val()==null){
		      		$('#route_plan_id').attr('class','invalid');
		      		jGrowl_to_notyfy('Route  Plan     is null.',{theme:'jmsg-alert'});
		      		return false;
                 	}
       <?php }?>
      
      	$('#list_table').find('input[id^=ip-ip]').each(function (){
		      	if($(this).val()==null||$(this).val()==''){
		      		$(this).attr('class','invalid');
		      		ip_null=false;
		                    	}
		      		arr.push($(this).val());
        		 });
      	jQuery('#list_table').find('input[check=Num]').each(function(){
            if(jQuery(this).val()==null||jQuery(this).val()==''){
                 jQuery(this).attr('class','invalid');
                 jGrowl_to_notyfy('Port not null.',{theme:'jmsg-error'});
                 post_null=false;
                                }
            arr_post.push(jQuery(this).val());
                          });
         for(var i = 0;arr.length>i;i++){
        	  arr_cum[i]=arr[i]+arr_post[i];
                       }
                         
        var arr3=jQuery.uniqueArray(arr_cum);
        if(arr3.length!=arr_cum.length){
		        $('#list_table').find('input').each(function (){
				       	$(this).attr('class','invalid');
				        	flag=false;
		                   });
        jGrowl_to_notyfy('IP Address and Post Happen  Repeat.',{theme:'jmsg-error'});
                   }
       if(ip_null==false){
       	 jGrowl_to_notyfy('IP Address   not  is null.',{theme:'jmsg-error'});
                  }

      	$('#list_table').find('input[id^=ip-port]').each(function (){
	      		if(isNaN($(this).val())){
	      			$(this).attr('class','invalid');
	      			port_flag=false;
	
	          		        }
        		});
		     
      jQuery('input[check=Ip]:visible').each(function(){
         if(jQuery('this').val!=''||jQuery('this').val!=null){
	           if(/[0-9A-Za-z-_\.]/.test(jQuery(this).val())){
	                                         
	           }else{
		        	   jQuery(this).attr('class','invalid');
		        	   jGrowl_to_notyfy('Please enter the correct IP address and domain name',{theme:'jmsg-error'});
		        	   ip_flag =false;
	                               }
                    }
              });
			if(port_flag=false){
				 jGrowl_to_notyfy('Port must contain digit only.',{theme:'jmsg-error'});
			}
        if(port_flag=true&&flag==true&&ip_null==true&&post_null==true&&ip_flag==true){
             return true;
        }else{

          return false;
                  }

           
       

        
    } );
--></script>
</div>



