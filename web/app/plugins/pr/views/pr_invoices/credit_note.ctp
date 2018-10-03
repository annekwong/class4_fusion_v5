<style>
#myform {
    display:none;
}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Invoices', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoices', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a href="<?php echo $this->webroot ?>pr/pr_invoices/view" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
            <i></i>
            &nbsp;<?php echo __('goback',true);?>
        </a>
        <a id="addbtn" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)">
        <i></i> <?php __('Create New')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
    <br /><br />
    <form name="myform" method="post" id="myform">
        <?php echo __('Note',true);?>:
        <input type="text" name="note" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo __('Amount',true);?>*:
        <input type="text" name="amount" class="validate[required,custom[number]]" />
        <input type="submit"  class="btn btn-primary margin-bottom10" value="<?php echo __('submit',true);?>" />
    </form>
    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php echo __('Entered On',true);?></th>
                <th><?php echo __('Entered By',true);?></th>
                <th><?php echo __('Amount',true);?></th>
                <th><?php echo __('Description',true);?></th>
                <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_credit_note'] == 1): ?>
                <th><?php __('Action'); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['payment_time']; ?></td>
                <td><?php echo $item[0]['name']; ?></td>
                <td><?php echo number_format($item[0]['amount'], 2); ?></td>
                <td><?php echo $item[0]['description']; ?></td>
                <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_credit_note'] == 1): ?>
                <td>
                    <a title="<?php echo __('edit') ?>" class="edit" href="javascript:void(0)" client_payment_id="<?php echo $item[0]['client_payment_id'] ?>">
                        <i class="icon-edit"></i>
                    </a>
                    <a title="Delete" href="<?php echo $this->webroot ?>pr/pr_invoices/delete_credit_note/<?php echo $item[0]['client_payment_id'] ?>/<?php echo $invoice_no ?>">
                        <i class="icon-remove"></i>
                    </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td  colspan="2"><b><?php echo __('Total',true);?></b></td>
                <td><?php echo number_format($total, 2); ?></td>
                <td colspan="3"></td>

            </tr>
        </tbody>
    </table>
</div>
    </div>
</div>

<script type="text/javascript">
$(function() {
   $('#addbtn').click(function() {
          jQuery('table.list tbody').trAdd({
             ajax: "<?php echo $this->webroot ?>pr/pr_invoices/edit_debit_note/",
             action: "<?php echo $this->webroot ?>pr/pr_invoices/credit_note/"+<?php echo $invoice_no ?>,
             'insertNumber': 'first',
             callback:function(options){},
             onsubmit:function(options){
                 if($('#debitAmount').validationEngine('validate')) return false;

                 return true;
             }
          });
          return false;
     });

    jQuery('.edit').click(
        function () {
            var client_payment_id = jQuery(this).attr('client_payment_id');
//            global_option = {};
//            global_option.id = res_block_id;
            jQuery(this).parent().parent().trAdd(
                {
                    ajax: "<?php echo $this->webroot ?>pr/pr_invoices/edit_credit_note/" + client_payment_id+"/<?php echo $invoice_no ?>",
                    action: "<?php echo $this->webroot ?>pr/pr_invoices/action_edit_credit_note/" + client_payment_id+"/<?php echo $invoice_no ?>",
                    saveType: 'edit',
                    callback:function(options){},
                    onsubmit:function(options){
//                        var ani_length = $("#ani_length").val();
//                        var dnis_length = $("#dnis_length").val();
//                        var ani_max_length = $("#ani_max_length").val();
//                        var dnis_max_length = $("#dnis_max_length").val();
                        if($('#creditAmount').validationEngine('validate')) return false;

                        return true;
                    }
                }
            );


            return false;
        }
    );
});
</script>