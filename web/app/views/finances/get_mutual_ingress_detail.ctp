<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Ingress Mutual Balance') ?>[<?php echo $client_name ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Ingress Mutual Balance')?>[<?php echo $client_name ?>]</h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="massadd" class="btn btn-icon btn-inverse glyphicons circle_plus" href="###"><i></i> <?php __('Mass Add') ?></a>
        <?php if ($_SESSION['role_menu']['Management']['clients']['model_x']) { ?>
            <a class="btn btn-primary btn-icon glyphicons file_export" href="<?php echo $this->webroot ?>downloads/carrier"><i></i> <?php __('Export') ?></a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
<!--
<div id="title">
  <h1>
<?php __('Finance') ?>
    &gt;&gt;
<?php __('Ingress Mutual Balance') ?>[<?php echo $client_name ?>]
  </h1>
     <ul id="title-menu">
         <li>
            <a class="link_btn" id="massadd" href="###">
                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/add.png"><?php __('Mass Add') ?>
            </a>
        </li>
<?php if ($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
            <li>
                <a class="link_btn" id="regenerate" href="###">
                        <img width="16" height="16" src="<?php echo $this->webroot ?>images/refresh.png"><?php __('Regenerate') ?>
                </a>
            </li>
<?php endif; ?>
    <li>
        <a class="link_back" href="<?php echo $this->webroot; ?>clients/index"> 
            <img width="16" height="16" alt="Back" src="<?php echo $this->webroot; ?>images/icon_back_white.png">&nbsp;Back 
        </a>
    </li>
    <li>
        <a href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_detail/<?php echo $this->params['pass'][0] ?>?export=1" title="Export" class="link_btn">
            <img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png" alt="Export">
            Export            
        </a>
    </li>
  </ul>
</div>
-->
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th colspan="3"><?php __('Beginning Balance on')?> <?php echo $start_time; ?> 00:00:00 <?php __('is')?> <?php echo $begin_balance; ?></th>
                        <th colspan="3"><?php __('Ending Balance on')?> <?php echo $end_time; ?> 23:59:59 <?php __('is')?> <?php echo $end_balance; ?></th>
                    </tr>
                    <tr>
                        <th><?php __('Date')?></th>
                        <th><?php __('Invoice Received')?></th>
                        <th><?php __('Payment Sent')?></th>
                        <th><?php __('Credit Note Received')?></th>
                        <th><?php __('Debit Note Received')?></th>
                        <th><?php __('Balance')?></th>
                    </tr>
                </thead>    

                <tbody>
                    <?php foreach ($financehistories as $financehistory): ?>
                        <tr>
                            <td><?php echo $financehistory['FinanceHistory']['date']; ?></td>
                            <td><?php echo $financehistory['FinanceHistory']['invoice_set']; ?></td>
                            <td><?php echo $financehistory['FinanceHistory']['payment_received']; ?></td>
                            <td><?php echo $financehistory['FinanceHistory']['credit_note_sent']; ?></td>
                            <td><?php echo $financehistory['FinanceHistory']['debit_note_sent']; ?></td>
                            <td><?php echo $financehistory['FinanceHistory']['mutual_ingress_balance']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($type_sum)): ?>
                        <tr>
                            <td><?php __('Total')?>:</td>
                            <td><?php echo $type_sum['invoice_set'] ?></td>
                            <td><?php echo $type_sum['payment_received'] ?></td>
                            <td><?php echo $type_sum['credit_note_sent'] ?></td>
                            <td><?php echo $type_sum['debit_note_sent'] ?></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $massadd = $('#massadd');
    var $regenerate = $('#regenerate');
    
    $regenerate.click(function() {
        $.post("<?php echo $this->webroot ?>finances/regenerate/<?php echo $client_id; ?>", function(data) {
            jGrowl_to_notyfy('Succeeded!',{theme:'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });
    
    
    $(function() {
        
        
        $massadd.click(function() {
            
            var $delete = $('.delete');
            var $payment_panel_received = null;
            var $payment_panel_sent = null;
            var $invoice_panel = null;
            var $massadd_panel = null;
            var $payment_type = $('.payment_type');
            var $back_url = null;
            var $myform = null;
            
            
            
            $dd.dialogui({  
                title: 'Mass Add',  
                width: 960,  
                height: 600,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot ?>finances/mass_add/<?php echo $client_id; ?>',  
                modal: true,
                toolbar: [{
                        text:'Add Payment Received',
                        iconCls:'icon-add',
                        handler:function(){
                            $payment_panel_received.clone().appendTo($massadd_panel);
                        }
                    }, '-',{
                        text:'Add Payment Sent',
                        iconCls:'icon-add',
                        handler:function(){
                            $payment_panel_sent.clone().appendTo($massadd_panel);
                        }
                    }, '-',{
                        text:'Add Incoming Invoice',
                        iconCls:'icon-add',
                        handler:function(){
                            $invoice_panel.clone().appendTo($massadd_panel);
                        }
                    }],
                buttons:[{
                        text:'Save',
                        handler:function(){
                            $myform.submit();
                        }
                    },{
                        text:'Close',
                        handler:function(){
                            $dd.dialogui('close');
                        }
                    }],
                onLoad: function() {
                    $massadd_panel = $('#massadd_panel');
                    $payment_panel_received = $('#payment_panel_received').remove();
                    $payment_panel_sent = $('#payment_panel_sent').remove();
                    $invoice_panel = $('#invoice_panel').remove();
                    $myform = $('#myform');
                    $back_url = $("#back_url").val("<?php echo $this->params['url']['url']; ?>");
                    
                    $payment_type.live('change', function() {
                        var $this = $(this);
                        
                        if ($this.val() == 0) {
                            $this.parent().next().hide();
                        } else {
                            $this.parent().next().show();
                        }
                    });
                        
                    $delete.live('click', function() {
                        $(this).parents('ul').remove();
                    });
                }
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot ?>finances/mass_add/<?php echo $client_id; ?>');  
        });
    });
</script>