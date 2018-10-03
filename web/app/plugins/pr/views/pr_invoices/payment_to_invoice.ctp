<style type="text/css">
    .myform {
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 0.97em;
        margin: 0 auto;
        width: 100%;
        border-top: 1px solid #ebebeb;
    }
    .myform .label {
        width:40%;
        text-align:right;
        padding-right:12px;
    }
    .myform .value {
        width:60%;
        text-align:left;
    }

    .ColVis_collection > .btn-primary{
    	width: 166px !important;
    }

    .ColVis_collection > .ColVis_title{
    	color: #333333 !important;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Payment') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Payment') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-inverse btn-icon glyphicons circle_arrow_left" href="javascript:history.go(-1)">
            <i></i>
            <?php echo __('Back') ?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Invoice Number')?></th>
                        <th><?php __('Invoice Amount')?></th>
                        <th><?php __('Due Amount')?></th>
                        <th><?php __('Period')?></th>
                        <th><?php __('Due Date')?></th>
                        <th><?php __('Pay Amount')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $invoice_info[0][0]['invoice_number']; ?></td>
                        <td><?php echo round($invoice_info[0][0]['invoice_amount'], 2); ?></td>
                        <td><?php echo round($invoice_info[0][0]['due_amount'], 2); ?></td>
                        <td><?php echo $invoice_info[0][0]['invoice_start']; ?>~<?php echo $invoice_info[0][0]['invoice_end']; ?></td>
                        <td><?php echo $invoice_info[0][0]['due_date']; ?></td>
                        <td><?php echo round($invoice_info[0][0]['pay_amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <form name="myform" method="post">
                <table class="myform footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <colgroup>
                    <col width="40%">
                    <col width="60%">
                    </colgroup>
                    <tr>
                        <td class="right"><?php __("Payment Date"); ?>* </td>
                        <td><input class="validate[required]" type="text" name="payment_date" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" style="width:300px;" /></td>
                    </tr>
                    <tr>
                        <td class="right"><?php __("Payment"); ?>* </td>
                        <td><input class="validate[required,custom[integer]]" type="text"  style="width:300px;" name="payment" /></td>
                    </tr>
                    <tr>
                        <td class="right"><?php __("Note"); ?> </td>
                        <td><textarea name="note" style="width:300px;height:80px;"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center">
                            <input type="submit" value="Submit" class="btn btn-primary" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
	$(document).click(function(){
		$('.ColVis_Button').addClass('btn btn-primary').attr('data-toggle','dropdown');
		if($('.ColVis_Button > span:nth-child(2)').html() != ' '){
			$('.ColVis_Button').append('<span> </span>');
		}
		if($('.ColVis_Button > span:nth-child(3)').html() == undefined){
			$('.ColVis_Button').append('<span class="caret"></span>');
		}
		$('.TableTools_collection .TableTools_Button').removeClass('ColVis_Button').removeClass('TableTools_Button').css({'margin-left':'0'});
		$('.TableTools_collection').css({'position':'absolute','padding':'0','border':'0'});
	}).on('DOMNodeInserted', function(){
		$('body').click();
	});
</script>