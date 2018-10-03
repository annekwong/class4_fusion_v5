<style>

select,textarea, input[type="text"]{margin-bottom: 0}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Editing finance') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Editing finance') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php echo $this->element('xback', Array('backUrl' => 'finances/view')) ?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">



            <div class="container">
                <?php //$urls=split('/',$this->params['url']['url'],2)?>
                <?php $id = array_keys_value($this->params, 'pass.0') ?>
                <?php echo $form->create('Finance', array('action' => 'edit_finance', 'onsubmit' => "return checkform();")); ?>
                <?php //echo $form->create ('Finance', array ('action' =>$urls[1],'onsubmit'=>'return checkform();'));?>
                <table class="form list table dynamicTable tableTools table-bordered  table-white">
                    <tbody> 
                        <tr>
                            <td class="right"><?php __('Serial Number'); ?></td>
                            <td><b><?php echo $p['action_number']; ?></b></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Submit Date'); ?></td>
                            <td><?php echo $p['action_time']; ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Transaction Type') ?></td>
                            <td>

                                <select id="type" name="data[Finance][action_type]">
                                    <option value=""><?php echo __('select') ?></option>
                                    <option value="2" <?php echo (!empty($p['action_type']) && $p['action_type'] == 2) ? 'selected' : ''; ?>><?php echo __('Wire In', true); ?></option>
                                    <option value="1" <?php echo (!empty($p['action_type']) && $p['action_type'] == 1) ? 'selected' : ''; ?>><?php echo __('Wire Out', true); ?></option>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td class="right"><?php __('Method') ?></td>
                            <td>
                                <select id="method" name="data[Finance][action_method]">
                                    <option value=""><?php echo __('select') ?></option>
                                    <option value="1" <?php echo (!empty($p['action_method']) && $p['action_method'] == 1) ? 'selected' : ''; ?>> <?php  __('Bank Wire') ?></option>
                                    <option value="2" <?php echo (!empty($p['action_method']) && $p['action_method'] == 2) ? 'selected' : ''; ?>> <?php  __('Paypal') ?></option>
                                </select>
                            </td>
                        </tr>

                        <?php if ($p['action_type'] == 1): ?>
                            <tr>
                                <td class="right"><?php echo __('Bank/Paypal Account', true); ?> </td>
                                <td><input type="text" id="account" name="data[Finance][account]" value="<?php printf("%s", $p['account']); ?>" class="in-input in-text" style="width:210px;"/></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="right"><?php echo __('Transaction Amount', true); ?> </td>
                            <td><input type="text" id="amount" name="data[Finance][amount]" value="<?php printf("%0.2f", $p['amount']); ?>" class="in-input in-text" style="width:210px;" readonly="readonly"/></td>
                        </tr>

                        <tr>
                            <td class="right"><?php echo __('Actual Amount', true); ?> </td>
                            <td><input type="text" id="actual_amount" name="data[Finance][actual_amount]" value="<?php printf("%0.2f", $p['actual_amount']); ?>" class="in-input in-text validate[required,custom[number]]"  style="width:210px;" /></td>
                        </tr>

                        <tr>
                            <td class="right"><?php echo __('Transaction Fee', true); ?> </td>
                            <td>
                                <input type="text" id="fee"  name="data[Finance][action_fee]" value="<?php printf("%0.2f", $p['action_fee']); ?>"  class="in-input in-text validate[required,custom[number]]"  style="width:210px;"/></td>
                        </tr>

                        <tr>
                            <td class="right"><?php __('Status') ?></td>
                            <td>
                                <select id="status" name="data[Finance][status]">
                                    <?php if ($p['action_type'] == 2 || ($p['action_type'] == 1 && $p['status'] != 1)): ?>
                                        <option value="2" <?php echo (!empty($p['status']) && $p['status'] == 2) ? 'selected' : ''; ?>><?php __('Completed') ?></option><?php endif; ?>
                                    <?php if ($p['action_type'] == 1): ?>                
                                        <option value="3" <?php echo (!empty($p['status']) && $p['status'] == 3) ? 'selected' : ''; ?>><?php __('Refused') ?></option>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="form_footer" class="center">
                    <input type="hidden" name="data[Finance][id]" value="<?php echo $id; ?>" />
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <?php if ($_SESSION['role_menu']['Finance']['finances']['model_w']) { ?> <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary">
                    <?php } ?>
                    <input type="button" value="<?php __('Cancel')?>" onClick="history.go(-1);" class="btn btn-default">
                </div>
                <?php echo $form->end(); ?> 
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function checkform() {
        var flag = true;
        var amount = $('#amount').val();
        var fee = $('#fee').val();
        if (!/^\d[\.|\d]*$/.test(amount))
        {
            jQuery(this).jGrowlError('Amount, invalide format!', {theme: 'jmsg-error'});
            flag = false;
        }
        if (!/^\d[\.|\d]*$/.test(fee))
        {
            jQuery(this).jGrowlError('Fee, invalide format!', {theme: 'jmsg-error'});
            flag = false;
        }
        if (parseFloat($("#actual_amount").val()) > parseFloat($("#amount").val()) && $('#type').val() === '2') {
            jQuery(this).jGrowlError('Actual Amount cannot be greater than Transaction Amount!', {theme: 'jmsg-error'});
            flag = false;
        }


        return flag;
    }

    $(document).ready(function() {
        $("#type").focus(function() {
            $(this).attr('defaultIndex', $(this).attr('selectedIndex'));
        });
        $("#type").change(function() {
            $(this).attr('selectedIndex', $(this).attr('defaultIndex'));
        });
    });

</script>