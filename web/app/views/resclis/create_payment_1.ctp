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
            <td style="text-align:right;"><?php echo __('Payment Received At',true);?>:</td>
            <td style="text-align:left !important;">
                <input type="text" name="receiving_time" value="<?php echo date("Y-m-d H:i:s"); ?>"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" class="wdate" />
            </td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
        <tr  id="amount_tr">
        <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        	<td style="text-align:right;"><?php echo __('Amount',true);?>:</td>
            <td style="text-align:left !important;"><input type="text" id="amount" name="amount" style="width:200px;" /></td>
            <td style="width:10%;">&nbsp;</td><td style="width:10%;">&nbsp;</td>
        </tr>
        
        <tr class="invoce_control">
            <td style="text-align:right;"></td>
            <td style="text-align:left;" colspan="4">
                <input type="button" id="add_invoice" value="Add Invoice" />
                <table class="list">
                    <thead>
                        <tr>
                            <td><?php echo __('Invoice',true);?></td>
                            <td><?php echo __('Invoice Amount',true);?></td>
                            <td><?php echo __('Due Amount',true);?></td>
                            <td><?php echo __('Period',true);?></td>
                            <td><?php echo __('Due Date',true);?></td>
                            <td><?php echo __('Pay Amount',true);?></td>
                            <td><?php echo __('action',true);?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr class="cloned">
                        <td>
                            <select name="invoices[]" class="invoices" style="width:200px;">
                                <?php foreach($invoices as $invoice): ?>
                                <option value="<?php echo $invoice[0]['invoice_id']; ?>"><?php echo $invoice[0]['invoice_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <a href="###" class="delete_invoice">
                                 <i class='icon-remove'></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" align="right"><?php __('Total')?>:<span id="total_amount"></span></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <td style="width:10%;">&nbsp;</td>
        </tr>
       
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
    $('#payment_type').change(function() {
        if($(this).val() == '0') {
            $('.invoce_control').hide();
            $('#amount_tr').show();
        } else {
            $('.invoce_control').show();
            $('#amount_tr').hide();
        }
    });

    $('#add_invoice').click(function() {
        $('.cloned:first').clone(true).appendTo('table.list');
        total_amount();
    });

    $('.invoices').live('change', function() {
        var invoice_id = $(this).val();
        var $this = $(this);
        if(invoice_id == null) {
            invoice_id = -1;
        }
        $.ajax({
            url: '<?php echo $this->webroot; ?>resclis/invoice_info/' + invoice_id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var $tr = $this.parent().parent();
                var length = data.length;
                
                $.each(data, function(index, value) {
                    var invoice_amount = decimal(parseFloat(value[0]['invoice_amount']), 2);
                    var due_amount = decimal(parseFloat(value[0]['due_amount']), 2);
                    $tr.find('td:nth-child(2)').text(invoice_amount);
                    $tr.find('td:nth-child(3)').text(due_amount);
                    $tr.find('td:nth-child(4)').text(value[0]['invoice_start'] + '~' + value[0]['invoice_end']);
                    $tr.find('td:nth-child(5)').text(value[0]['due_date']);
                    var input = $('<input type="text" name="invoice_payamount[]" class="invoice_payamount wdate input in-text in-input" />');
                    input.blur(function() {
                        total_amount();
                    });
                    $tr.find('td:nth-child(6)').append(input);
                });
                total_amount();
            }
        });
    });
    
    

    
    
    function total_amount() {
        if($('#payment_type').val() == '1') {
            var total_amount = $('#total_amount');
            var total = 0;

            $('table tr.cloned').each(function(index, value) {
                var val = parseFloat($(this).find('input.invoice_payamount').val());
                if(!isNaN(val))
                    total += val;
            });
            
            total_amount.text(total);
        }
    }
    
    
    
    
    $('#client_id').change(function() {
        var client_id = $(this).val();
        $.ajax({
            url: '<?php echo $this->webroot; ?>resclis/get_client_invoice/' + client_id + '/<?php echo $type; ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var invoices = $('.invoices');
                invoices.empty();
                $.each(data, function(index, value) {
                    invoices.append('<option value="'+value[0]['invoice_id']+'">'+value[0]['invoice_name']+'</option>');
                });
                invoices.change();
            }
        });
    });
    
    
    $('#myform').submit(function() {
        var flag = true;
        if($('#payment_type').val() == '1') {
            $('table.list tr.cloned').each(function() {
                var $this = $(this);
                var due_amount = parseFloat($('td:nth-child(3)', $this).text());
                var pay_amount = parseFloat($('input.invoice_payamount', $this).val());
                if(isNaN(pay_amount)) {
                    jGrowl_to_notyfy('The pay amount must be a number!',{theme:'jmsg-error'});
                    flag = false;
                }
                if(pay_amount > due_amount) {
                    jGrowl_to_notyfy('The pay amount cant not more than due_amount!',{theme:'jmsg-error'});
                    flag = false;
                }
            });
        }
        return flag;
    });
    
    $('.delete_invoice').click(function() {
        if($('tr.cloned').size() > 1) {
            $(this).parent().parent().remove();
        }
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
        $('#client_id').change();
        $('#payment_type').change();
    };
    loadit();
});
</script>