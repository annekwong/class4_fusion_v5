
<div id="title">
            <h1>  <?php __('CreatingInvoice')?>     </h1>
        <ul id="title-menu">
<li><a class="link_back" href="<?php echo $this->webroot?>invoices/view/1">
<img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/rerating_queue.png"><?php echo __('goback',true);?></a></li>    
         <!--   <li><a href="/admin/invoices/list"><img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/list.png"> List Items</a></li>
         <li><a class="list-export" href="/admin/invoices/csvExport"><img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/export.png"> Export to CSV</a></li>--><!--
         <li><a rel="popup" href="/admin/invoices/makeForm"><img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/add.png"> 创建</a></li>
        --></ul>
    </div>
<div class="container">
<?php echo $form->create ('Invoice', array ('action' => 'add' ));?>
<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<table class="form"  style="width: 60%">
<tbody><tr>
     <td class="label"> <?php  __('Carriers')?>  </td>
    <td id="client_cell" class="value">
        <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 73%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td class="label label4"><!--
    <?php __('total_amount')?> :
    --></td>
    <td class="value value4">
<!--<input type="text" id="total_amount" value=""  class="input in-text"  name="total_amount" >
    --></td>
</tr>
<tr>
    <td class="label label4"><?php __('InvoiceNo')?>:</td>
    <td class="value value4"><input type="text" id="invoice_number" style="width: 124px;" value="" name="invoice_number" class="input in-text"> <small class="note"><?php __('(empty = auto)')?></small></td>
    <td class="label label4"><?php __('State')?>:</td>
    <td class="value value4">
    
    <select id="state" name="state" class="input in-select">
    <option selected="selected" value="0"><?php __('normal')?></option>
    <option value="1"><?php __('to send')?> </option>
    <option value="2"><?php __('to verify')?> </option></select>
    </td>
</tr>
<tr>
    <td class="label label4"><?php echo __('Invoice Date',true);?> / <span rel="helptip" class="helptip" id="ht-100001"><?php __('Due (days)')?></span><span class="tooltip" id="ht-100001-tooltip"><?php __('A number of days, when invoice is expected to be paid')?></span>:</td>
    <td class="value value4">
<input type="text" id="due_date" value="" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="due_date" realvalue="">
    </td>
    <td class="label label4"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('type',true);?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('This field defines whether the invoice is incoming or outgoing')?></span>:</td>
    <td class="value value4">
    <select id="type" onchange="updateDirection()" name="type" class="input in-select">
    <option value="0"><?php __('Outgoing invoice')?></option><option value="1"><?php __('Incoming invoice')?></option></select></td>
</tr>
</tbody>
</table>
<fieldset style="margin-top: 15px;">
<legend>
       <span style="margin-left: 5px; border-left: 2px solid rgb(238, 238, 238);" id="output-block">
        &nbsp; output in &nbsp;<select id="output" style="width: 80px;" name="output" class="input in-select"><option value="pdf"><?php __('PDF')?></option><option value="xls"><?php __('Excel')?></option><option value="html"><?php __('HTML')?></option></select>                &nbsp;
       </span>
</legend>
<table>
<tr class="period-block">
    <td class="label"><?php __('time')?>:</td>
    <td colspan="5" class="value">
    <table class="in-date"><tbody>
    <tr>
    <td>
    <table class="in-date">
<tbody>
<tr>
<td style="padding-right: 15px;">
 		<?php
$r=array('custom'=>__('custom',true),    'curDay'=>__('today',true),    'prevDay'=>__('yesterday',true),    'curWeek'=>__('currentweek',true),    'prevWeek'=>__('previousweek',true),   'curMonth'=>__('currentmonth',true),
   'prevMonth'=>__('previousmonth',true),   'curYear'=>__('currentyear',true)    ,'prevYear'=>__('previousyear',true)  ); 	
if(!empty($_POST)){
	if(isset($_POST['smartPeriod'])){
		$s=$_POST['smartPeriod'];
	}else{
		$s='curDay';
	}
}else{
	$s='curDay';
}
echo $form->input('smartPeriod',
 		array('options'=>$r,'label'=>false ,
 		'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 130px;','name'=>'smartPeriod',
 		'div'=>false,'type'=>'select','selected'=>$s));?>
</td>
    <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
         value="" name="start_date"  style="margin-left: 0px; width: 156px;"></td>
    <td></td>
</tr>
</tbody></table>
    </td>
    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
    	readonly="readonly" 
         style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
    <td>&mdash;</td>
    <td><table class="in-date">
<tbody><tr>
    <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
    <td></td>
</tr>
</tbody></table>
    </td>
    <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>
     <td style="padding: 0pt 10px;"><?php __('in')?></td>
     <td><select class="input in-select" name="query[tz]" style="width: 100px;" id="query-tz">
     <option value="-1200">GMT -12:00</option>
     <option value="-1100">GMT -11:00</option>
     <option value="-1000">GMT -10:00</option>
     <option value="-0900">GMT -09:00</option>
     <option value="-0800">GMT -08:00</option>
     <option value="-0700">GMT -07:00</option>
     <option value="-0600">GMT -06:00</option>
     <option value="-0500">GMT -05:00</option>
     <option value="-0400">GMT -04:00</option>
     <option value="-0300">GMT -03:00</option>
     <option value="-0200">GMT -02:00</option>
     <option value="-0100">GMT -01:00</option>
     <option value="+0000"  selected="selected">GMT +00:00</option>
     <option value="+0100">GMT +01:00</option>
     <option value="+0200" >GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    </tr></tbody></table>
</td>
</tr>
</table>
<div style="" id="t-generate">
    <table class="form">
    <col style="width: 14%;">
    <col style="width: 86%;">
    <tbody><tr>
        <td class="label">&nbsp;</td>
        <td class="value"><input type="checkbox" checked="checked" id="stats" value="1" name="stats" class="input in-checkbox"> <label for="stats"><span rel="helptip" class="helptip" id="ht-100003"><?php __('Include statistics data to invoice')?></span><span class="tooltip" id="ht-100003-tooltip"><?php __('If enabled - includes statistic data into current invoice for selected period')?></span></label> <a class="note" onclick="$('#columns').toggle();return false;" href="#"><?php __('select columns')?> »</a></td>
    </tr>
    <tr>
        <td class="label"></td>
        <td class="value">
            <div style="padding: 0pt 0pt 15px 60px; overflow: hidden; display: none;" id="columns">
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" checked="checked" id="fields-account" value="account" name="fields[]" class="input in-checkbox">                <label for="fields-account"><?php __('Account')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-code_country" value="code_country" name="fields[]" class="input in-checkbox">                <label for="fields-code_country"><?php __('Country')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-code_name" value="code_name" name="fields[]" class="input in-checkbox">                <label for="fields-code_name"><?php __('Destination')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-code" value="code" name="fields[]" class="input in-checkbox">                <label for="fields-code"><?php __('Codes')?></label>
            </div>
                        <div style="clear: both;"></div>            <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-rate" value="rate" name="fields[]" class="input in-checkbox">                <label for="fields-rate"><?php __('Rate')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-calls" value="calls" name="fields[]" class="input in-checkbox">                <label for="fields-calls"><?php __('Calls')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-minutes" value="minutes" name="fields[]" class="input in-checkbox">                <label for="fields-minutes"><?php __('Minutes')?></label>
            </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                <input type="checkbox" id="fields-cost" value="cost" name="fields[]" class="input in-checkbox">                <label for="fields-cost"><?php echo __('Cost',true);?></label>
            </div>
                        </div>
        </td>
    </tr>
    </tbody><tbody id="products_block">
    <tr>
        <td class="label">&nbsp;</td>
        <td class="value"><input type="checkbox" id="products" value="1" name="products" class="input in-checkbox"> <label for="products"><span rel="helptip" class="helptip" id="ht-100004"><?php __('Include products charges to invoice')?></span><span class="tooltip" id="ht-100004-tooltip"><?php __('If enabled - includes data on ordered products into current invoice for selected period')?></span></label></td>
    </tr>
    <tr>
        <td class="label"></td>
        <td class="value"></td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td class="value"><div><input type="checkbox" id="cdr_generate" onchange="updateCdrOutput();" value="1" name="cdr_generate" class="input in-checkbox"> <label for="cdr_generate"><span rel="helptip" class="helptip" id="ht-100005"><?php __('Add CDRs list to the invoice')?></span><span class="tooltip" id="ht-100005-tooltip"><?php __('Creates CDR file for invoicing period and attaches it to invoice')?>.</span></label> 
        <a class="note" onclick="$('#cdr_columns').toggle();return false;" href="#"><?php __('select columns')?> »</a></div></td>
    </tr>
    <tr>
        <td class="label"></td>
        <td class="value">
            <div style="padding: 0pt 0pt 15px 60px; overflow: hidden; display: none;" id="cdr_columns">
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-call_time" value="call_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-call_time"><?php __('Call Date')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-gw_ip" value="gw_ip" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-gw_ip"><?php __('Gateway IP')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-gw_name" value="gw_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-gw_name"><?php __('Gateway Name')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-code_country" value="code_country" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code_country"><?php __('Country')?></label>
            </div>
                        <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-code_name" value="code_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code_name"><?php __('Code Name')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-code" value="code" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code"><?php echo __('code',true);?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-account" value="account" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-account"><?php __('Account Name')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-src_number" value="src_number" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-src_number"><?php __('Src Number')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-dst_number" value="dst_number" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-dst_number"><?php __('Dst Number')?></label>
            </div>
                        <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-session_time" value="session_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-session_time"><?php __('Session Time')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-billed_time" value="billed_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-billed_time"><?php __('Billed Time')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-rate" value="rate" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-rate"><?php __('Rate')?></label>
            </div>
                                    <div style="width: 120px; float: left;">
                <input type="checkbox" checked="checked" id="cdr_fields-cost" value="cost" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-cost"><?php echo __('Cost',true);?></label>
            </div>
                        <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                <input type="checkbox" id="cdr_fields-vh_name" value="vh_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-vh_name"><?php echo __('VoIP Gateway',true);?></label>
            </div>
                        </div>
        </td>        
    </tr>
    <tr id="cdr_output_row">
        <td class="label"><?php echo __('CDRs List Format',true);?>:</td>
        <td class="value"><select id="cdr_output" style="width: 100px;" name="cdr_output" class="input in-select"><option value="csv"><?php __('Excel CSV')?></option><option selected="selected" value="xls"><?php __('Excel XLS')?></option></select></td>
    </tr>
    
    <tr>
        <td class="label">&nbsp;</td>
        <td class="value">
            <fieldset style="margin-top: 10px;" class="listSection">
            <legend><?php echo __('Additional items',true);?> <a onclick="addItem(); return false;" href="#">
            <img width="10" height="10" src="<?php echo $this->webroot?>images/add-small.png"> <?php __('Add new item')?></a></legend>
            <table width="100%" id="add_positions" style="display: none;">
            <tbody><tr>
                <td colspan="2">
                <table class="list">
                <thead>
                <tr>
                    <td width="63%"><span rel="helptip" class="helptip" id="ht-100006"><?php echo __('Item',true);?></span><span class="tooltip" id="ht-100006-tooltip"><?php __('An additional invoice row that can hold any service or product in addition to main invoice body')?></span></td>
                    <td width="18%"><span rel="helptip" class="helptip" id="ht-100007">Price</span><span class="tooltip" id="ht-100007-tooltip"><?php __('Price value for respective additional invoice line item')?></span></td>
                    <td width="17%" class="last">&nbsp;</td>
                </tr>
                </thead>
                <tbody id="entries"><!--
                                           //模板行
                --><tr style="display: none;" id="tpl-entries" class="row-1">
                    <td calss="value"><input type="text" name="_positions[%n][name]" class="input in-text"></td>
                    <td class="value" style="text-align: center;"><input type="text" style="text-align: right;" check="Ip" name="_positions[%n][price]" class="input in-text" ></td>
                    <td class="last"><a rel="delete" href="#"><i class="icon-remove"></i></a></td>
                </tr>
                </tbody>
                </table>
                </td>
            </tr>
            </tbody></table>
            </fieldset>
        
        </td>
    </tr>
    <tr>
        <td >&nbsp;</td>
        <td class="value"><small class="note"><?php __('Please note, that if client has Tax Value specified, it will be added to the total value of the invoice')?>.</small></td>
    </tr>
    </tbody></table>
    
</div>
</fieldset>

<div id="form_footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
        <input type="button" value="<?php __('Revert')?>" onclick="winClose();" class="input in-button">
    </div>
	<?php echo $form->end();?>

<script type="text/javascript">
//&lt;![CDATA[


var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}
var lastId = 0;
function addItem(row)
{
    lastId++;
    if (!row || !row['id']) {
        row = {};
    }

    // fix row values
    for (k in row) { if (row[k] == null) row[k] = ''; }
   
    // prepare row
    var tRow = $('#tpl-entries').clone(true).appendTo($('#entries'));
    
    
    tRow.attr('id', 'row-'+lastId).show();
    jQuery(tRow).find('input[check=Ip]:visible').xkeyvalidate({type:'Ip'}).attr('maxLength','16');
    // set names / values
    tRow.find('input,select').each(function () {
        var name = $(this).attr('name').substring(1).replace('%n', lastId);
        var field = name.substring(name.lastIndexOf('[')+1, name.length-1);
        $(this).attr('id', field+'-'+lastId);
        $(this).attr('name', name);
        $(this).val(row[field]);
    });
    
    // remove of the row
    tRow.find('a[rel=delete]').click(function () {
        $(this).closest('tr').remove();
        return false;
    });
    
    // styles
    initForms(tRow);
    initList();
    $('#add_positions').show();
}                                                                                                                                                                                                

/**
 * Watch for type of invoice generation 
 */


function updateDirection() {
    if ($('#direction').val() == 'in') {
        $('#products_block').hide();
    } else {
        $('#products_block').show();
    }
}
updateDirection();

function updateCurrency() { 
    var currency = $('#id_currencies option:selected').text();
    $('#total_stats_cur').html(currency);
    $('#total_other_cur').html(currency);
}
updateCurrency();

function updateCdrOutput() {
    if ($('#cdr_generate').attr('checked')) {
        $('#cdr_output_row').show();
    } else {
        $('#cdr_output_row').hide();
    }
}
updateCdrOutput();

function setPeriod(type, current)
{         
    if (type != 'custom') {
       period = calcPeriod(type);
       $('#period_start-wDt').val(period['startDate']);
       $('#start_time-wDt').val(period['startTime']);
       $('#period_finish-wDt').val(period['stopDate']);
       $('#stop_time-wDt').val(period['stopTime']);
    }
  $('#smartPeriod').val(type);
}
setPeriod('prevWeek');


jQuery(document).ready(function(){
   jQuery('input[check=Ip]').xkeyvalidate({type:'Ip'}).attr('maxLength','16');
       jQuery('#invoice_number').xkeyvalidate({type:'Num'}).attr('maxLength','16');
 });

//]]&gt;
</script></div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
