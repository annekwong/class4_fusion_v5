<script type="text/javascript">
//Confused,optimize later.
function checkForm(){
	var arr = [];
	var result = true;
	var unique = function(ip){
		for (i=0;i<arr.length;i++) {
			if (arr[i] == ip)
				return false;
		}
		return true;
	};
	$('#rows-ip tr').each(function(){
		if (this.cells[0].getElementsByTagName("input")[1]){
			var v = this.cells[0].getElementsByTagName("input")[1].value;
			if (unique(v))
				arr.push(v);
			else
				result = false;
			
		}else{
			var v = this.cells[0].getElementsByTagName("input")[0].value;
			if (unique(v))
				arr.push(v);
			else
				result = false;
			
		}
	});
	if (!result){jGrowl_to_notyfy("Not allowed to add the same IP",{theme:'jmsg-alert'});}
	return result;	
}
</script>
<form  action="<?php echo $this->webroot?>gatewaygroups/add_host_post" onsubmit="return checkForm();"  id="hostForm"  method="post">

<input type="hidden" id="resource_id" value="<?php if(!empty($resource_id)){echo  $resource_id ;}else{  echo '';}?>" name="resource_id" class="input in-hidden">
<input type="hidden" id="gress" value="<?php echo $_GET['gress']?>" name="gress" class="input in-hidden">

<fieldset>
<legend><a onclick="addItem('ip');return false;" href="#">
<img width="10" height="10" src="<?php echo $this->webroot?>images/add-small.png"> <?php echo __('Add Host',true);?></a></legend>
<center>
<table class="list list-form" style="width:1014px;">
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
  	
  	<tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1;?>" style="">
    <td style="width: 200px;" class="value">
       <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['resource_ip_id']?>" class="input in-hidden">
        <input type="text" style="width: 200px;" value="<?php  
        

        	$check='false';
        	//普通ip
                 	if(strrpos($host[$i][0]['fqdn'],"/")){
   	    list($ip, $net) = split('[/]', $host[$i][0]['fqdn']);
   	    $host_ip=$ip;
   	    $netmask=$net;
   	}else{
   	     $host_ip=$host[$i][0]['ip'];
   	     $netmask='';
   	}
        echo $host_ip;
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
  <a title="<?php echo __('Capacity',true);?>"  href="<?php echo $this->webroot?>gatewaygroups/add_host_time/<?php echo $host[$i][0]['resource_ip_id']?>/<?php echo $host_ip?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png"></a>
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

<div id="footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
   <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit">
    </div>
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

    addItem('name', {"id":33066,"name":"account_3","ips":null,"tech_prefix":null,"password":null,"id_voip_hosts":null,"proxy_mode":null,"auth_type":"name","ani":null,"accname":"account_3","protocol":null,"port":null,"orig_enabled":true,"term_enabled":true,"orig_capacity":null,"term_capacity":null,"orig_rate_table":null,"term_rate_table":null,"id_dr_plans":null,"dr_groups":[]});
    addItem('ani', {"id":33067,"name":"account_4","ips":null,"tech_prefix":null,"password":null,"id_voip_hosts":22,"proxy_mode":null,"auth_type":"ani","ani":null,"accname":null,"protocol":null,"port":null,"orig_enabled":true,"term_enabled":true,"orig_capacity":null,"term_capacity":null,"orig_rate_table":null,"term_rate_table":null,"id_dr_plans":null,"dr_groups":[]});


//]]>
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
      	jQuery('input[check=Ip]').xkeyvalidate({type:'checkIp'});
      	jQuery('input[check=Num]').xkeyvalidate({type:'Num'});
          } );
     jQuery(document).ready(function(){
         		var re=true;
        jQuery('#hostForm').submit(function(){
	        	jQuery('#hostForm ').find('input[check=Ip]:visible').map(function(){
	            	if(!jQuery.xkeyvalidate(this,{type:'checkIp'}))
	            						{
											re=false;
	            						}
	            			});
    						return re;
                       });
               });


    
</script>