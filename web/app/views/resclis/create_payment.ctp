<?php echo $this->element("resclis_make_payment_one/title")?>

<style type="text/css">
.invoce_control {display:none;}
.form .label2{width:130px;}
.form .value2{width:auto;}
</style>

<div id="container">
    <form method="post" name="myform" id="myform" action="<?php echo $this->webroot; ?>resclis/create_payment/<?php echo $this->params['pass'][0]; ?>">
    <div style="text-align:center;">
    <table style="width:100%; margin:auto;">
        <tr>
        <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
            <td style="text-align:right;"><?php echo __('Carrier',true);?>:</td>
            <td style="text-align:left;">
                <select name="client" id="client_id" style="width:200px;">
                    <option></option>
                    <?php foreach($clients as $client): ?>
                    <option value="<?php echo $client[0]['client_id']; ?>"><?php echo $client[0]['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>   
        <tr>
        	<td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
            <td style="text-align:right;"><?php echo __('Type',true);?>:</td>
            <td style="text-align:left !important;">
                <select name="payment_type" id="payment_type" style="width:200px;">
                    <option value="0"><?php __('Prepayment')?></option>
                    <option value="1"><?php __('Invoice Payment')?></option>
                </select>
            </td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
         <tr>
        	<td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
            <td style="text-align:right;"><?php echo __($this->params['pass'][0] == 'incoming' ? 'Payment Received At' : __('Payment Sent Time',true));?>:</td>
            <td style="text-align:left !important;">
                <input type="text" name="receiving_time" value="<?php echo date("Y-m-d H:i:s"); ?>"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" class="wdate" />
            </td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        	<td style="text-align:right;"><?php echo __('Amount',true);?>:</td>
            <td style="text-align:left !important;"><input type="text" id="amount" name="amount" style="width:200px;" /></td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
            <td style="text-align:right;"><?php echo __('Note',true);?>:</td>
            <td style="text-align:left !important;"><input type="text" id="amount" name="note" style="width:200px;" /></td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
       
    </table>
        
        <table class="list" id="invoice_panel">
            <thead>
                <tr>
                    <th><?php echo __('Invoice Number',true);?></th>
                    <th><?php echo __('Invoice Amount',true);?></th>
                    <th><?php echo __('Pay Amount',true);?></th>
                    <th><?php echo __('Due Amount',true);?></th>
                    <th><?php echo __('Period',true);?></th>
                    <th><?php echo __('Given Amount',true);?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
     <div id="form_footer">
                <input type="submit" value="<?php echo __('submit',true);?>" />
     </div>
    </form>
</div>

<script type="text/javascript">

function decimal(num, v)
{
    var vv = Math.pow(10, v);
    return Math.round(num*vv)/vv;
}
    
$(function() {
    
    var $payment_type = $('#payment_type');
    var $client_id = $('#client_id');
    var $invoice_panel = $('#invoice_panel');
    var $amount = $('#amount');

    $payment_type.change(function() {
        var $this = $(this);
        var client_id = $client_id.val();
        if ($this.val() == 1)
        {
            $.ajax({
                url: '<?php echo $this->webroot; ?>resclis/get_unpaid_invoices/' + client_id + '/<?php echo $type; ?>',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var $tbody = $('tbody', $invoice_panel);
                    var total_amounts = parseFloat($amount.val());
                    if(isNaN(total_amounts))
                        total_amounts = 0;
                       
                    $tbody.empty();
                    $.each(data, function(index, item) {
                        var total_amount  = parseFloat(item[0]['total_amount']);
                        var pay_amount = parseFloat(item[0]['pay_amount']);
                        var due_amount = total_amount - pay_amount;
                        var current_amount = total_amounts >  due_amount ? due_amount: total_amounts;
                        var $tr = $('<tr />');
                        $tr.append('<input type="hidden" name="invoice_numbers[]" value="' + item[0]['invoice_number'] + '" />');
                        $tr.append('<input type="hidden" name="current_amounts[]" value="' + current_amount + '" />');
                        $tr.append('<input type="hidden" name="due_amounts[]" value="' + due_amount + '" />');
                        $tr.append('<td>' + item[0]['invoice_number'] + '</td>');
                        $tr.append('<td>' + item[0]['total_amount'] + '</td>');
                        $tr.append('<td>' + item[0]['pay_amount'] + '</td>');
                        $tr.append('<td>' + due_amount.toFixed(5) + '</td>');
                        $tr.append('<td>' + item[0]['invoice_start'] + '~' +  item[0]['invoice_end'] + '</td>');
                        $tr.append('<td>' + current_amount.toFixed(5)  +  '</td>');
                        $tbody.append($tr);
                        total_amounts -= current_amount;
                    });
                    
                    $invoice_panel.show();
                    
                }
            });
        }
        else
        {
            $invoice_panel.hide();
        }
    }).trigger('change');
    
    $client_id.change(function() {
        $payment_type.change();
    });
    
    $amount.keyup(function() {
        $payment_type.change();
    });
    
    
    var loadit = function() {
        var client_id = "<?php echo isset($_GET['client_id'])?$_GET['client_id']:'' ?>";
        var payment_type = "<?php echo isset($_GET['type'])?$_GET['type']:'' ?>";
        var invoice_no = "<?php echo isset($_GET['invoice_no'])?$_GET['invoice_no']:'' ?>";
        
        if(client_id != '') {
            $('#client_id option[value='+client_id+']').attr('selected', 'selected');
            $('#client_id').parent().parent().css('visibility', 'hidden')
        }
        if(payment_type != '') {
            $('#payment_type option[value='+payment_type+']').attr('selected', 'selected');
            $('#payment_type').parent().parent().css('visibility', 'hidden')
        }
        if(invoice_no != '') {
            $('.invoices option[value='+invoice_no+']').attr('selected', 'selected');
            $('#add_invoice').hide();
            $('td.last').hide();
        }
        //$('#client_id').change();
        //$('#payment_type').change();
    };
    loadit();
});
</script>