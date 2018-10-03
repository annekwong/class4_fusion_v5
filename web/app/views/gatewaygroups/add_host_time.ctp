<style>

    #form_footer {
        padding: 10px;
    }

</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier',true);?><?php echo empty($carrier)||$carrier==''?'':"[".$carrier."]"?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Egress Trunk',true);?><?php echo empty($name)||$name==''?'':"[".$name."]"?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Host Capacity',true);?></li>
</ul>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
    <div class="widget-body">
<form  action="<?php echo $this->webroot?>gatewaygroups/add_host_time_post"   method="post" id="trans_form">
<input type="hidden" id="resource_ip_id" value="<?php   if(!empty($resource_ip_id)){echo  $resource_ip_id ;}else{  echo '';}?>" name="resource_ip_id" class="input in-hidden">
<fieldset>
<legend>
    <a onclick="addItem('ip');return false;" href="#" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i>
    <?php echo __('add',true);?>   <?php __('systemcap')?></a></legend>

    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default"  id="list_table">
        <thead>
            <tr>
                <th width="12%"><span rel="helptip" class="helptip" id="ht-100002"><?php __('timeprofile')?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('Name of an account in JeraSoft yht system (for statistics and reports)')?></span></th>
                <th width="8%"><span rel="helptip" class="helptip" id="ht-100003"><?php __('cps')?></span><span class="tooltip" id="ht-100003-tooltip"><?php __('Gateway IP-adress. You can specify multiple adresses by dividing them with `;`')?></span></th>
                <th width="8%"><span rel="helptip" class="helptip" id="ht-100004"><?php __('Call')?></span><span class="tooltip" id="ht-100004-tooltip"><?php __('Technical prefix, that is used to identify users, when multiple clients use same gateway')?></span></th>

                <th width="8%" class="last"><?php __('Action')?>/th>
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
                                echo $form->input('client_id',array('options'=>$timepro,'empty'=>' ','id'=>"ip-time_profile_id-$ii",'name'=>"accounts[$ii][time_profile_id]",'selected'=>$host[$i][0]['time_profile_id'],
                                'style'=>'205px;',
                                'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
            </td>
            <td style="width: 350px;" class="value">
               <input type="text"  value="<?php echo $host[$i][0]['cps'];?>" class="input in-text" name="accounts[<?php echo $i+1?>][cps]" id="ip-cps-<?php echo $i+1?>" xkeyvalidate="Num" maxLength="16"></td>
            <td style="width: 300px;" class="value"><input type="text"       value="<?php echo $host[$i][0]['capacity']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][capacity]" xkeyvalidate="Num" maxLength="16" id="ip-capacity-<?php echo $i+1?>"></td>
            <td style="width: 200px;" class="value last"><a href="#" rel="delete"  onclick="removeRow(this, <?php echo $host[$i][0]['limit_id']?>)"><i class="icon-remove"></i></a></td>
        </tr>
         <?php }?>
        <?php //  用id="tpl-ip"表示  准备复制的hang ?>
        <tr style="display:none;" id="tpl-ip" class="  row-2">
            <td class="value"  style="width: 200px;">
               <?php 
                                echo $form->input('client_id',array('options'=>$timepro,'name'=>"_accounts[%n][time_profile_id]",'style'=>'205px;',
                                'label'=>false, 'class' =>'select','empty'=>' ' ,'div'=>false,'type'=>'select'));
                                ?>
            </td>
            <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][cps]" class="input in-text" xkeyvalidate="Num" maxLength="16" ></td>
            <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][capacity]" class="input in-text" xkeyvalidate="Num" maxLength="16"></td>
            <td class="value last"  style="width: 200px;"><a rel="delete" href="#"><i class="icon-remove"></i></a></td>
        </tr>
        </tbody>
    </table>
</fieldset>

<div id="form_footer" class="center" <?php if($size == 0) echo "style='display:none;'"?>>
    <input type="submit" value="<?php __('submit')?>" class="input in-submit btn btn-primary">
    <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit btn btn-default">
</div>
</form>
<script type="text/javascript" language="JavaScript">
//<![CDATA[

jQuery(document).ready(function() {
    jQuery('#trans_form').submit(function () {
        var flag = true;
        var arr = new Array();
        $('#list_table').find('select[id*=ip-time_profile_id]').each(function () {
            arr.push($(this).val());
        });
        var arr2 = $.uniqueArray(arr);
        if (arr.length != arr2.length) {
            $('#list_table').find('select[id*=ip-time_profile_id]').each(function () {
                jQuery(this).addClass('invalid');
                flag = false;
            });
            jGrowl_to_notyfy('Time Profile  Happen  Repeat.', {theme: 'jmsg-error'});
        }
        return flag;
    });
});
var lastId = '<?php  echo   $size;?>';
var dr_groups = [];
function addItem(type, row) {
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
    for (k in row) {
        if (row[k] == null) row[k] = '';
    }
    // prepare row
    var tRow = jQuery('#tpl-' + type).clone(true);//复制准备好的行
    tRow.attr('id', 'row-' + lastId).show();//设置显示
    // set names / values循环行内的每个表单元素
    tRow.find('input,select').each(function () {
        var el = jQuery(this);//当前表单元素
        //准备行的名字  _accounts[%n][id]  替换为accounts[6][id]
        var name = $(this).attr('name').substring(1).replace('%n', lastId);//设置名字(将名字中的%n替换为lastId)  accounts[6][id]
        var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);  //id
        el.attr('id', type + '-' + field + '-' + lastId);//设置id  ip-id-6
        el.attr('name', name);
//        对checkbox的处理
        if (el.attr('type') == 'checkbox') {
//给checkbox注册事件
            if (field == 'need_register') {
                el.click(function () {
                    if ($(this).attr("checked") == true) {
                        $(this).attr("value", 'true');
                    } else {
                        $(this).attr("value", 'false');
                    }
                });
            }
            if (typeof(row[field]) == 'object') {
                el.attr('checked', jQuery.inArray(1 * el.attr('value'), row[field]) != -1 ? 'checked' : '');
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
    if(confirm("Are you sure you want to delete, capacity? ")){
        $(this).closest('tr').remove(); //找到他最靠近的tr删除之
        var rowCount = $('#list_table tbody tr').length;
        if (rowCount - 1 == 0) {
            $("#form_footer").hide();
        }
       return false;
          }
    });
    buildParams(tRow);
    if (row['id']) {
        tRow.appendTo(jQuery('#rows-' + type));
    } else {
        tRow.prependTo(jQuery('#rows-' + type));//<tbody class="rows" id="rows-ip">  将tr加入tbody
    }

    // styles
    if (!row['id']) {
        initForms(tRow);
        initList();
    }
    $("#form_footer").show();
}
function buildParams(row) {
    var s = '';
    if (row.find('input[name*=orig_capacity]').val()) {
        s += ' / OC: ' + row.find('input[name*=orig_capacity]').val();
    }
    if (row.find('input[name*=term_capacity]').val()) s += ' / TC: ' + row.find('input[name*=term_capacity]').val();
    if (row.find('select[name*=protocol]').val()) s += ' / ' + row.find('select[name*=protocol] :selected').text();
    if (row.find('input[name*=proxy_mode]').val()) s += ' / P: ' + row.find('input[name*=proxy_mode]').val();
    if (row.find('select[name*=id_dr_plans]').val()) s += '<br/>RP: ' + row.find('select[name*=id_dr_plans] :selected').text();
    if (row.find('select[name*=orig_rate_table]').val()) s += '<br/>Orig RT: ' + row.find('select[name*=orig_rate_table] :selected').text();
    if (row.find('select[name*=term_rate_table]').val()) s += '<br/>Term RT: ' + row.find('select[name*=term_rate_table] :selected').text();
    var dr_group = '';
    row.find('input[name*=dr_groups]').each(function () {
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
    jQuery('.rows div.params-block:visible').hide().attr('id', '').each(function () {
         buildParams($(this).parent().parent());
    });
}

//live event handlers
jQuery('.rows #tpl-params-block div').live('click', function (e) {
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
   jQuery(document).ready(function() {
       jQuery("input[xkeyvalidate]").each(
           function () {
               jQuery(this).xkeyvalidate({type: 'Num'}).attr('maxLength', '8');
           }
       );
   });
   function removeRow(row, limitId = -1) {
       if (limitId == -1) {
           jQuery(row).closest('tr').remove();
       } else {
           if (confirm('Are you sure you want to delete this capacity?')) {
               $.post("<?php echo $this->webroot; ?>gatewaygroups/removeLimit",
                   {
                       id: limitId
                   },
                   function (data) {
                       if (data == 1) {
                           jQuery(row).closest('tr').remove();
                           jGrowl_to_notyfy('Capacity Deleted Successfully!', {theme: 'jmsg-success'});
                       } else {
                           jGrowl_to_notyfy('Failed to delete, try again!', {theme: 'jmsg-error'});
                       }
                   });
           }
       }
       var rowCount = $('#list_table tbody tr').length; 
       if (rowCount - 2 == 0) {
           $("#form_footer").hide();
       }
   }
jQuery(document).ready(function(){
	jQuery('#form').submit(function(){
	  var te=true;
    jQuery('#form').find('input[xkeyvalidate]:visible').map(
       function(){
           if(jQuery(this).val()==''){
               te = false;
			          	jQuery(this).addClass('invalid');
				         jGrowl_to_notyfy('cps and capacity cannot be NULL!',{theme:'jmsg-error'});
                             }
           if(/\.{2,}/.test(jQuery(this).val())){
        	     te = false;
         	 	  jQuery(this).addClass('invalid');
		           jGrowl_to_notyfy('cps and capacity Entered incorrectly!',{theme:'jmsg-error'});
                              }
            if(/\D/.test(jQuery(this).val())){
              	te = false;
         	 	   jQuery(this).addClass('invalid');
  		           jGrowl_to_notyfy('cps and capacity Entered incorrectly!',{theme:'jmsg-error'});        
                                 }
           return te;
                  } 
                    

    	    )
    return te;})
	
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('form').submit(
		function(){
			var re=true;
			jQuery('input[id^=ip-cps]').each(
				function(){
					if(/\D/.test(jQuery(this).val())){
						jQuery.jGrowlError('CPS, must be whole number!');
						jQuery(this).addClass("invalid");
						re=false;
					}
				}
			);
     jQuery('input[id^=ip-capacity]').each(function(){
		    	 if(/\D/.test(jQuery(this).val())){
						jQuery.jGrowlError('Capacity,must be whole number! ');
						jQuery(this).addClass("invalid");
						re=false;
					}
         });
			return re;
		}
	);
});

    $(function () {
        var rowCount = $('#list_table tbody tr').length;
        if (rowCount - 1 == 0) {
            $("#form_footer").hide();
        }
    });
</script>
</div>
    </div>
</div>
