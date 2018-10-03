
    <style type="text/css">
				.width100{width:100px;}
			</style>
<div id="title">
  <h1>
  
<?php __('systemcap')?>
  

  </h1>

    
  <ul id="title-menu">
<li>
    			<a href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_host">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
  </ul>
</div>
<div id="container">
<form  action="<?php echo $this->webroot ?>gatewaygroups/add_host_time_post"   method="post">
<input type="hidden" id="resource_ip_id" value="<?php   if(!empty($resource_ip_id)){echo  $resource_ip_id ;}else{  echo '';}?>" name="resource_ip_id" class="input in-hidden">
<fieldset>
<legend>
<a onclick="addItem('ip');return false;" href="#">
<img src="<?php echo $this->webroot ?>images/add.png"><?php echo __('add',true);?>    <?php __('systemcap')?></a></legend>
<table class="list list-form"  style="width: 700px;">
<thead>
    <tr>
        <td width="12%"><span rel="helptip" class="helptip" id="ht-100002"><?php __('timeprofile')?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('Name of an account in JeraSoft yht system (for statistics and reports)')?></span></td>
        <td width="8%"><span rel="helptip" class="helptip" id="ht-100003"><?php echo __('cps',true);?></span><span class="tooltip" id="ht-100003-tooltip"><?php __('Gateway IP-adress. You can specify multiple adresses by dividing them with `;`')?></span></td>
        <td width="8%"><span rel="helptip" class="helptip" id="ht-100004">Call</span><span class="tooltip" id="ht-100004-tooltip"><?php __('Technical prefix, that is used to identify users, when multiple clients use same gateway')?></span></td>
        <td width="8%" class="last">&nbsp;</td>
    </tr>
</thead>
<tbody class="rows" id="rows-ip">
<?php  $size=count($host);
  for($i=0;$i<$size;$i++){?>
  	<tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1?>" style="">
    <td style="width: 200px;" class="value">
       <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['limit_id']?>" class="input in-hidden">
    			<?php 
    			$ii=$i+1;
    			echo $form->input('client_id',array('options'=>$timepro,  'id'=>"ip-time_profile_id-$ii",'name'=>"accounts[$ii][time_profile_id]",'selected'=>$host[$i][0]['time_profile_id'],
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
       <td style="width: 350px;" class="value">
       <input type="text" value="<?php echo $host[$i][0]['cps'];?>" class="input in-text" name="accounts[<?php echo $i+1?>][cps]" id="ip-cps-<?php echo $i+1?>"></td>

    <td style="width: 300px;" class="value"><input type="text"       value="<?php echo $host[$i][0]['capacity']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][capacity]" id="ip-capacity-<?php echo $i+1?>"></td>

    <td style="width: 200px;" class="value last"><a href="#" rel="delete"  onclick=" $(this).closest('tr').remove();"><i class="icon-remove"></i></a></td>
</tr>
 <?php }?> 
<?php //  用id="tpl-ip"表示  准备复制的hang ?>
<tr style="display:none;" id="tpl-ip" class="  row-2">
    <td class="value"  style="width: 200px;">
           			<?php 
      			echo $form->input('client_id',array('options'=>$timepro,'name'=>"_accounts[%n][time_profile_id]",'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'empty'=>' '));?>
    </td>
       <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][cps]" class="input in-text"></td>
    <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][capacity]" class="input in-text"></td>
    <td class="value last"  style="width: 200px;"><a rel="delete" href="#"><i class="icon-remove"></i></a></td>
</tr>
</tbody>
</table>
</fieldset>

<div id="form_footer">
        <input type="submit" value="<?php __('submit')?>" class="input in-submit">
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
    var tRow = $('#tpl-'+type).clone();//复制准备好的行
    tRow.attr('id', 'row-'+lastId).show();//设置显示
    // set names / values循环行内的每个表单元素
    tRow.find('input,select').each(function () {
        var el = $(this);//当前表单元素
        //准备行的名字  _accounts[%n][id]  替换为accounts[6][id]
        var name = $(this).attr('name').substring(1).replace('%n', lastId);//设置名字(将名字中的%n替换为lastId)  accounts[6][id]
        var field = name.substring(name.lastIndexOf('[')+1, name.length-1);  //id
        el.attr('id', type+'-'+field+'-'+lastId);//设置id  ip-id-6
        el.attr('name', name);
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
<!--
jQuery(document).ready(function(){
	addItem('ip');
});

//-->
</script>
</div>
