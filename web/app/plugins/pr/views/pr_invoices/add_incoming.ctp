<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Create Incoming Invoice') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Create Incoming Invoice', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:history.go(-1)">
            <i></i><?php echo __('goback',true);?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
    <!--
    <form name="myform" method="post" id="myform">
    <table style="width:500px;margin:0 auto;">
        <tr>
            <td><?php echo __('Invoice No',true);?>.</td>
            <td><input type="text" name="invoice_number" id="invoice_number" /></td>
        </tr>
        <tr>
            <td><?php echo __('Carrier',true);?></td>
            <td>
                <select name="client_id" style="width:165px;">
                <?php foreach($clients as $client): ?>
                    <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Starting Period',true);?></td>
            <td><input type="text" value="<?php echo date("Y-m-d 00:00:00") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="start" /></td>
        </tr>
        <tr>
            <td><?php echo __('Ending Period',true);?></td>
            <td><input type="text" value="<?php echo date("Y-m-d 23:59:59") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end" /></td>
        </tr>
        <tr>
            <td><?php echo __('GMT',true);?></td>
            <td>
                <select name="gmt" style="width:165px;">
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
                <option selected="selected" value="+0000">GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option>
                <option value="+0400">GMT +04:00</option>
                <option value="+0500">GMT +05:00</option>
                <option value="+0600">GMT +06:00</option>
                <option value="+0700">GMT +07:00</option>
                <option value="+0800">GMT +08:00</option>
                <option value="+0900">GMT +09:00</option>
                <option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Invoice Amount',true);?></td>
            <td><input type="text" name="invoice_amount" id="invoice_amount" /></td>
        </tr>
        <tr>
            <td><?php echo __('Paid Amount',true);?></td>
            <td><input type="text" name="paid_amount" /></td>
        </tr>
        <tr>
            <td><?php echo __('Due Date',true);?></td>
            <td><input type="text" value="<?php echo date("Y-m-d") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" name="due_date" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="<?php echo __('submit',true);?>" /></td>
        </tr>
    </table>
     -->
    <form name="myform" method="post" id="myform" enctype="multipart/form-data">
    <table class="form table dynamicTable tableTools table-bordered  table-white">
        <tbody>
            <tr>
                <td class="right"><?php echo __('Carrier',true);?>* </td>
                <td>
                    <select name="client_id" style="width:165px;">
                    <?php foreach($clients as $client): ?>
                        <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td class="right"><?php echo __('Invoice No',true);?>. </td>
                <td><input type="text" name="invoice_number" id="invoice_number" class="validate[custom[onlyNumber]]" /></td>
                <td class="right"><?php echo __('Amount',true);?>* </td>
                <td><input type="text" name="invoice_amount" id="invoice_amount" class="validate[required,custom[number]] width120" /></td>
            </tr>
            <tr>
                <td class="right"><?php echo __('Invoice Period',true);?> </td>
                <td><input type="text" value="<?php echo date("Y-m-d 00:00:00") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="start" /></td>
                <td class="right"><?php echo __('To',true);?> </td>
                <td><input type="text" value="<?php echo date("Y-m-d 23:59:59") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end" /></td>
                <td class="right"><?php __('GMT'); ?> </td>
                <td>
                    <select name="gmt" style="width:165px;">
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
                    <option selected="selected" value="+0000">GMT +00:00</option>
                    <option value="+0100">GMT +01:00</option>
                    <option value="+0200">GMT +02:00</option>
                    <option value="+0300">GMT +03:00</option>
                    <option value="+0330">GMT +03:30</option>
                    <option value="+0400">GMT +04:00</option>
                    <option value="+0500">GMT +05:00</option>
                    <option value="+0600">GMT +06:00</option>
                    <option value="+0700">GMT +07:00</option>
                    <option value="+0800">GMT +08:00</option>
                    <option value="+0900">GMT +09:00</option>
                    <option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="right"><?php __('Invoice Date'); ?> </td>
                <td><input type="text" value="<?php echo date("Y-m-d") ?>" name="invoice_date" id="invoice_date" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" /></td>
                <td class="right"><?php __('Due Date'); ?> </td>
                <td><input type="text" value="<?php echo date("Y-m-d") ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" name="due_date" /></td>
                <td class="right"><?php __('Upload Invoice'); ?> </td>
                <td><input type="file" name="invoice_file" /></td>
            </tr>
            <tr>
                <td style="text-align: center" colspan="6"><input type="submit" class="btn btn-primary" value="<?php echo __('submit',true);?>" /></td>
            </tr>
        </tbody>
    </table>     
    </form>
</div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
//        $('#myform').submit(function() {
//            /*
//            if($('#invoice_number').val() == '') {
//                alert('The invoice number required!');
//                return false;
//            }
//            */
//            if($('#invoice_amount').val() == '') {
//                alert('The invoice amount required!');
//                return false;
//            }
//        });
    });
</script>    