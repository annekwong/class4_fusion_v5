<style type="text/css">
.width100 {
	width:100px;
}
.list {
	font-size: 1em;
	width: 100%;
}
</style>
<div id="title">
  <h1><?php __('Add')?>
    <?php if(isset($client_name)){ echo array_keys_value($client_name,'0.0.name') ;}?>
    <?php __('payment')?>&gt;&gt; <font class="editname" title='Name'> <?php echo (empty($name[0][0]['name'])|| $name[0][0]['name']=='') ? '':'['.$name[0][0]['name'].']'; ?> </font> </h1>
  <ul id="title-menu">
    <?php if(array_keys_value($this->params,'url.viewType')=='bills'){?>
    <li> <a class="link_back"  href="<?php echo $this->webroot?>bills/summary"> <img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">&nbsp;<?php echo __('goback',true);?> </a> </li>
    <?php }else{?>
    <li> <a class="link_back" href='#'  onclick="history.go(-1);return false;"> <img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">&nbsp;<?php echo __('goback',true);?> </a> </li>
    <?php }?>
  </ul>
</div>
<div id="container">
  <form  action="<?php echo $this->webroot ?>clientpayments/add_payment_post"   method="post" id="form">
    <input type="hidden" value="<?php  echo  $invoice_client_id?>" name="invoice_client_id">
    <fieldset>
      <legend> <a onclick="addItem('ip');return false;" href="#"> <img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Payment',true);?> </a> </legend>
      <table class="list list-form"   id="list_table">
        <thead>
          <tr>
            <td width="12%"><?php echo __('Invoice',true);?></td>
            <td width="8%"><?php echo __('Transation Type',true);?></td>
            <td width="8%"><?php echo __('Transation Amt',true);?></td>
            <td width="8%"><?php echo __('Transation Date',true);?></td>
            <td width="8%" class="last">&nbsp;</td>
          </tr>
        </thead>
        <tbody class="rows" id="rows-ip">
          <tr style="" id="row-1" class="row-1">
            <td class="value"><?php 	
	   			$user_name=$_SESSION['sst_user_name'];
	   			echo $form->input('invoice_number',array('id'=>'ip-invoice_number-1',
	   			'name'=>'accounts[1][invoice_number]','selected'=>$invoice_no,
	   			'options'=>$invoice,'name'=>"accounts[1][invoice_number]",'empty'=>'',
	    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','style'=>'width:200px;'));
   					?></td>
            <td class="value"><?php 		$t=array('1'=>'Payment','2'=>'Credit','3'=>'Offset');
    			echo $form->input('payment_type',array('options'=>$t, 'name'=>"accounts[1][payment_type]",'id'=>'ip-payment_type-1',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
            <td class="value"><input type="text" id="ip-amount-1" name="accounts[1][amount]" class="input in-text" value="" check="Money" ></td>
            <td class="value"><input type="text" readonly="" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});"
			    style="width: 116px;" 
			     value="<?php echo date ( "Y-m-d   H:i:s" );?>" id="ip-payment_time-1"
			    name="accounts[1][payment_time]" class="input in-text wdate" realvalue=""></td>
            <td class="value last"><input type="hidden" id="ip-entered_by-1"   name="accounts[1][entered_by]" class="input in-text" value="<?php echo $user_name?>">
              <input type="hidden" readonly="" value="<?php echo date ( "Y-m-d   H:i:s" ) ;?>"  id="ip-entered_date-1"  
         name="accounts[1][entered_date]" name="accounts[1][payment_time]" class="input in-text wdate" realvalue="">
              <a onclick=" $(this).closest('tr').remove();" title="delete" rel="delete" href="#"><i class="icon-remove"></i></a></td>
          </tr>
          <?php //  用id="tpl-ip"表示  准备复制的hang ?>
          <tr style="display:none;" id="tpl-ip" class="  row-2">
            <td class="value"  style="width: 200px;"><?php 	echo $form->input('invoice_number',array('options'=>$invoice,'name'=>"_accounts[%n][invoice_number]",'style'=>'width:200px;;',
    			'label'=>false,'empty'=>'', 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
            <td class="value"  style="width: 300px;"><?php 		$t=array('1'=>'Payment','2'=>'Credit','3'=>'Offset');
    			echo $form->input('payment_type',array('options'=>$t, 'name'=>"_accounts[%n][payment_type]",
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
            <td style="width: 300px;" class="value"><input type="text"  class="input in-text" name="_accounts[%n][amount]"  check="Money"></td>
            <td style="width: 350px;" class="value"><input type="text" readonly="" style="width: 116px;" value="<?php echo date ( "Y-m-d   H:i:s" );?>"  name="_accounts[%n][payment_time]" class="input in-text wdate" realvalue=""></td>
            <td class="value last"  style="width: 200px;"><input type="hidden" value='<?php echo  $user_name ?>' name="_accounts[%n][enterd_by]" class="input in-text">
              <input type="hidden" readonly="" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});"  value="<?php echo date ( "Y-m-d   H:i:s" );?>"  
    style="width: 65%;"
     name="_accounts[%n][enterd_time]" class="input in-text wdate" realvalue="">
              <a rel="delete" href="#" title="delete"><i class='icon-remove'></i></a></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
    <!--<fieldset>
      <legend> 
      <a onclick="view_invoice(this);return false;" href="#" id="invoice_title">Close Invoice Info</a>
			<script type="text/javascript">
			   function view_invoice(){
				   	if(jQuery('#invoice_title').html()!='Close Invoice Info'){
					   			jQuery('#invoice_title').html('Close Invoice Info');
					   			jQuery('#invoice_info_list').show();
				   	}else{
										jQuery('#invoice_title').html('View Invoice Info');
										jQuery('#invoice_info_list').hide();
			   				}
			   		}
			</script>		
		</legend>
     <div style="display: block;" id="invoice_info_list"> 
        <table class="list list-form">
				<thead>
				    <tr>
				        <td width="20%"><?php echo __('Invoice',true);?></td>
				        <td>Carriers</td>
				        <td width="20%">
				        	Time
				       	</td>
				        <td width="20%">
				        	Type
				        </td>
				        <td width="20%" class="last">
				        	Is Overdue:
				        </td>
				    </tr>
				</thead>
				<tbody>
						 <tr style="height: 30px;" class="row-1">
				 					<td width="20%"/>
				        <td width="20%"/>
				        <td width="20%"/>
				        <td width="20%"/>
				       	<td width="20%" class="last"/>
				     </tr>
				</tbody>
			</table>				           
       
        Time:
        <input type="text" realvalue="2011-03-22" readonly="" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width: 60px;" name="start_date" class="input in-text wdate in-input" value=""/>
        －
        <input type="text" readonly="" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width: 60px;" name="end_date" class="wdate input in-text in-input" value=""/>
        Type:
        <select id="invoice_type" class="select in-select">
          <option value="">All</option>
          <option value="0">Buy</option>
          <option value="1">Sell</option>
        </select>
        Paid:
        <select id="invoice_paid" class="select in-select">
          <option value="">All Unpaid & Partially Bills</option>
          <option value="1">Partially Paid  Bills</option>
          <option value="2">Unpaid Bills</option>
        </select>
        Overdue:
        <select id="invoice_overdue" class="select in-select">
          <option value="">All</option>
          <option value="1">Yes</option>
          <option value="2">No</option>
        </select>
        <input type="button" onclick="filter()" value="Filter" class="input in-submit"/>
      
      </div>
    </fieldset>-->
    <div id="form_footer">
      <input type="submit" value="<?php __('submit')?>" class="input in-submit">
      <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit">
    </div>
  </form>
  <script type="text/javascript">
var invoice_options_get='';
//jQuery('select[id^=ip-invoice_number],select[id=invoice_number]').change(function(){
//		if(jQuery(this).val()==''){
//			return;
//		}
//		var data=jQuery.ajaxData('<?php echo $this->webroot?>invoices/invoice_info?invoice_number='+jQuery(this).val());
//		jQuery("<div id='temp'/>").html(data).appendTo('body');
//		jQuery('#invoice_info_list table tbody tr td:nth-child(1)').html(jQuery('#temp table tr td:nth-child(1)').html());
//		jQuery('#invoice_info_list table tbody tr td:nth-child(2)').html(jQuery('#temp table tr td:nth-child(2)').html());
//		jQuery('#invoice_info_list table tbody tr td:nth-child(3)').html(jQuery('#temp table tr td:nth-child(3)').html());
//		jQuery('#invoice_info_list table tbody tr td:nth-child(4)').html(jQuery('#temp table tr td:nth-child(4)').html());
//		jQuery('#invoice_info_list table tbody tr td:nth-child(5)').html(jQuery('#temp table tr td:nth-child(5)').html());
//		jQuery('#temp').remove();
//});
//jQuery('select[id^=ip-payment_type],select[id=payment_type]').change(function(){
//	if(jQuery(this).val()!=1){
//		jQuery(this).parent().parent().find('input[id^=ip-payment_time],input[id=payment_time]').hide();
//	}else{
//		jQuery(this).parent().parent().find('input[id^=ip-payment_time],input[id=payment_time]').show();
//	}
//});
//jQuery('table.list tr').live('click',function(){
	//jQuery(this).find('select[id^=ip-invoice_number],select[id=invoice_number]').change();
//});
jQuery('select[id=payment_type]').each(function(){
	jQuery(this).change(function(){
		if(jQuery(this).val()!='1'){
			jQuery(this).parent().parent().find('#invoice_number').hide().html('');
		}else{
			getInvoice();
			jQuery(this).parent().parent().find('#invoice_number').show();
		}
	});
});
function getInvoice(){
	var ajaxData=jQuery.ajaxData("<?php echo $this->webroot?>invoices/invoice_options/<?php echo array_keys_value($this->params,'pass.0')?>?"+invoice_options_get);
	jQuery('select[id^=ip-invoice_number]').each(function(){
			jQuery(this).html(jQuery(ajaxData).html());
	});
}
function filter(){
	invoice_options_get='start_date='+jQuery('#start_date').val();
	invoice_options_get+='&end_date='+jQuery('#end_date').val();
	invoice_options_get+='&invoice_type='+jQuery('#invoice_type').val();
	invoice_options_get+='&invoice_paid='+jQuery('#invoice_paid').val();
	invoice_options_get+='&invoice_overdue='+jQuery('#invoice_overdue').val();
	getInvoice();
}
jQuery('select[id^=ip-invoice_number]').change(function(){
    if(jQuery(this).val()==''){
        jQuery(this).parent().parent().find('select[id^=ip-payment_type]').find('option[value=3]').remove();
    }else{
    		jQuery(this).parent().parent().find('select[id^=ip-payment_type]').find('option[value=3]').remove();
        jQuery('<option>').html('Offset').val('3').appendTo(jQuery(this).parent().parent().find('select[id^=ip-payment_type]'));
    			}
}).change();
</script> 
  <script type="text/javascript" language="JavaScript">
//<![CDATA[
var lastId = '0';
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
    jQuery('select[id^=ip-invoice_number]').change(function(){
        if(jQuery(this).val()==''){
            jQuery(this).parent().parent().find('select[id^=ip-payment_type]').find('option[value=3]').remove();
        }else{
        		jQuery(this).parent().parent().find('select[id^=ip-payment_type]').find('option[value=3]').remove();
            jQuery('<option>').html('Offset').val('3').appendTo(jQuery(this).parent().parent().find('select[id^=ip-payment_type]'));
        			}
    }).change();
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
jQuery(document).ready(function(){
	jQuery('input[check=Money]').xkeyvalidate({type:'Money'}).attr('maxLength','16');
});
var mm=/[\u4e00-\u9fa5]+/;
jQuery(document).ready(function(){
   jQuery('#form').submit(function(){
			   var flag=true; 
			   var f=0;
       	jQuery('#form').find('input[check=Money]:visible').map(
             function(){
              if (mm.test(jQuery(this).val())){
                 jQuery(this).addClass('invalid');
                 jGrowl_to_notyfy('Transation Amt can only be digit!',{theme:'jmsg-error'});
                 flag=false;
                                  }

                    })
        var arr = new Array();
        $('#list_table').find('select[id*=ip-invoice_number]').each(function (){
            if($(this).val()==null||$(this).val()==''){
                f=1;

                	}
       	arr.push($(this).val());
	
	            		});
	        var arr2=$.uniqueArray(arr);
	        if(arr.length!=arr2.length){
	           		$('#list_table').find('select[id*=ip-invoice_number]').each(function (){
	           			jQuery(this).addClass('invalid');
	           			flag=false;
	
	                	});
         jGrowl_to_notyfy('Time Profile  Happen  Repeat.',{theme:'jmsg-error'});
            		}
        return flag;
           
        });
});   
</script> 
</div>
