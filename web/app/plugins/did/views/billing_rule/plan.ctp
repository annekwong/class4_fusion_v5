<style>
    .list tbody tr span {margin:0 10px;}

    input.checkbox-left {
        margin: 0 auto;
        display: block;
    }
</style>


<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/billing_rule/plan"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/billing_rule/plan"><?php echo __('Billing Rule') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Billing Rule') ?></h4>

</div>
<div class="separator bottom"></div>
<?php
if ($_SESSION['role_menu']['Origination']['billing_rule']['model_w'])
{
    ?>

    <div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);"><i></i>
            <?php __('Create New') ?>
        </a>
        <a class="btn btn-primary btn-icon glyphicons remove" href="javascript:void(0);" onclick="deleteAll('<?php echo $this->webroot ?>did/billing_rule/delete_rules/all');"><i></i>
            Delete All
        </a>
        <a class="btn btn-primary btn-icon glyphicons remove" href="javascript:void(0);" onclick="ex_deleteSelected('billing_rules', '<?php echo $this->webroot ?>did/billing_rule/delete_rules/selected', 'billing rule');"><i></i>
            Delete Selected
        </a>
    </div>
<?php } ?>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
<!--        <div class="widget-head">-->
<!--            <ul>-->
<!--                <li class="active" ><a href="--><?php //echo $this->webroot; ?><!--did/billing_rule/plan" class="glyphicons left_arrow"><i></i>--><?php //__('Billing Rule'); ?><!--</a></li>-->
<!--                <li><a href="--><?php //echo $this->webroot; ?><!--did/billing_rule/special_code" class="glyphicons right_arrow"><i></i>--><?php //__('Special Code'); ?><!--</a></li>-->
<!--            </ul>-->
<!--        </div>-->
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br /><h2><?php echo __('no_data_found', true); ?></h2></div>
                    <table id="billing_rules" class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="display:none;">

                        <thead>
                            <tr>
                                 <th style="padding: 5px;" rowspan="2"><input type="checkbox"  class="checkAll checkbox-left"  value=""/></th>
                                <th style="min-width: 100px;" rowspan="2"><?php __('Name') ?></th>
                                <th rowspan="2"><?php __('Type Rate') ?></th>
                                <th colspan="2"><?php __('Recurring Price/DID') ?></th>
<!--                                <th>--><?php //__('Price/Channel Limit') ?><!--</th>-->
                                <th class="price_table" rowspan="2"><?php __('Price/Minute') ?></th>
                                <th rowspan="2"><?php __('Payphone Subcharge') ?></th>
                                <th colspan="2"><?php __('Fee Per Port') ?></th>
                                <th rowspan="2"><?php __('Setup Fee') ?></th>
                                <th rowspan="2"><?php __('Action') ?></th>
                            </tr>
                            <tr>
                                <th><?php __('Rate') ?></th>
                                <th><?php __('Billing') ?></th>
                                <th><?php __('Rate') ?></th>
                                <th><?php __('Billing') ?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <table id="billing_rules" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th style="padding: 5px;" rowspan="2"><input type="checkbox"  class="checkAll checkbox-left"  value=""/></th>
                                <th style="min-width: 100px;" rowspan="2"><?php __('Name') ?></th>
                                <th rowspan="2"><?php __('Type Rate') ?></th>
                                <th colspan="2"><?php __('Recurring Price/DID') ?></th>
                                <!--                                <th>--><?php //__('Price/Channel Limit') ?><!--</th>-->
                                <th class="price_table" rowspan="2"><?php __('Price/Minute') ?></th>
                                <th rowspan="2"><?php __('Payphone Subcharge') ?></th>
                                <th colspan="2"><?php __('Fee Per Port') ?></th>
                                <th rowspan="2"><?php __('Setup Fee') ?></th>
                                <th rowspan="2"><?php __('Action') ?></th>
                            </tr>
                            <tr>
                                <th><?php __('Rate') ?></th>
                                <th><?php __('Billing') ?></th>
                                <th><?php __('Rate') ?></th>
                                <th><?php __('Billing') ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($this->data as $item): ?>
                                <tr>
                                    <td><input type="checkbox" class="checkbox-left" value="<?php echo $item['DidBillingPlan']['id']; ?>"/></td>
                                    <td><?php echo $item['DidBillingPlan']['name']; ?></td>
                                    <td>
                                        <?php echo $item['DidBillingPlan']['rate_type']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $item['DidBillingPlan']['monthly_charge'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $item['DidBillingPlan']['price_type'];
                                        ?>
                                    </td>
<!--                                    <td>--><?php //if(empty($item['DidBillingPlan']['rate_table_id'])) echo $item['DidBillingPlan']['channel_price']; ?><!--</td>-->
                                    <td>
                                    <?php
                                        echo $item['DidBillingPlan']['min_price'] ? $item['DidBillingPlan']['min_price'] : '';
                                    ?>
                                    </td>
<!--                                    <td>--><?php //if(empty($item['DidBillingPlan']['rate_table_id'])) echo $item['DidBillingPlan']['billed_channels']; ?><!--</td>-->
                                    <td><?php if(!empty($item['DidBillingPlan']['rate_table_id'])) echo $item['DidBillingPlan']['payphone_subcharge']; ?></td>
                                    <td>
                                        <?php
                                        echo $item['DidBillingPlan']['fee_per_port'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $item['DidBillingPlan']['pay_type'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $item['DidBillingPlan']['did_price'];
                                        ?>
                                    </td>
                                    <!--td>
                                        <?php echo isset($item['DidBillingPlan']['rate_table_id']) && $item['DidBillingPlan']['rate_table_id'] ? $item['DidBillingPlan']['rate_table_name'] : ''; ?>
                                    </td-->
                                    <td>
                                        <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item['DidBillingPlan']['id'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>

                                        <a onclick="return confirm('Are you sure to delete it?');" title="<?php __('Delete') ?>" class="delete" href='<?php echo $this->webroot; ?>did/billing_rule/delete_rule/<?php echo base64_encode($item['DidBillingPlan']['id']) ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                        <?php  if(!empty($item['DidBillingPlan']['rate_table_id']) && isset($item['DidBillingPlan']['rate_type']) && $item['DidBillingPlan']['rate_type'] !== "Fixed Rate"): ?>
<!--                                            <a title="--><?php //__('Edit Rates') ?><!--" target="_blank" href='--><?php //echo $this->webroot; ?><!--clientrates/view/--><?php //echo base64_encode($item['DidBillingPlan']['rate_table_id']) ?><!--'>-->
                                            <a title="<?php __('Edit Rates') ?>" href='<?php echo $this->webroot; ?>did/billing_rule/view_rates/<?php echo base64_encode($item['DidBillingPlan']['rate_table_id']) ?>'>
                                                <i class="icon-fire"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php  if(!empty($item['DidBillingPlan']['rate_table_id']) && isset($item['DidBillingPlan']['rate_type']) && $item['DidBillingPlan']['rate_type'] == "Fixed Rate"): ?>
                                            <a title="<?php __('Min Time and Interval') ?>" id="apply_min_interval" data-rate-table-name = "<?php echo $item['DidBillingPlan']['rate_table_name']; ?>"; data-rate-table="<?php echo $item['DidBillingPlan']['rate_table_id']; ?>" href="#modalAddRateDetails" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<form id="modalAddRateDetails_form">
    <div id="modalAddRateDetails" class="modal hide" style="width:450px; margin-left: -200px;">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Rate Table'); ?></h3>
        </div>
        <div class="modal-body">
            <table class="table dynamicTable tableTools table-bordered  table-white">
                <tr>
                     <input type="hidden" name="rate_table_id" id="rate_table_id" value="1">
                    <td><?php __('Min Time')?></td>
                    <td>
                        <input name = "min_time" type="number" min="1" class="num-not-zero" id="min_time" value="1">
                    </td>
                </tr>
                <tr>
                    <td><?php __('Interval')?></td>
                    <td>
                        <input  name="interval" type="number" min="1" class="num-not-zero" id="interval" value="1">
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="apply_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script>
    function checknumber(input, name)
    {
        var re = /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/; //判断字符串是否为数字 
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an number only!", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }
    jQuery(function() {

        setTimeout(function(){
          $('.ColVis_collection .ColVis_radio input').each(function(index, val){
                     if(!$(this).is(':checked')){
                         $(this).click();
                     }
                })
        }, 1000)


        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table#billing_rules').trAdd({
                ajax: "<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel",
                action: "<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel",
                'insertNumber': 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                    }
                },
                onsubmit: function() {
                    var did_price = $("#DidBillingPlanDidPrice").val();
                    var min_price = $("#DidBillingPlanMinPrice").val();
                    var name = $("#DidBillingPlanName").val();
                    if (!name.trim())
                    {
                        jGrowl_to_notyfy("The field name can not be NULL!", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (did_price)
                    {
                        if (!checknumber(did_price, 'DID Price'))
                        {
                            return false;
                        }
                    }
                    if (!checknumber(min_price, 'MIN Price'))
                    {
                        return false;
                    }

                    return true;
                }
            });
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function() {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel/' + jQuery(this).attr('control'),
                saveType: 'edit',
                onsubmit: function() {
                    var did_price = $("#DidBillingPlanDidPrice").val();
                    var min_price = $("#DidBillingPlanMinPrice").val();
                    var name = $("#DidBillingPlanName").val();
                    if (!name.trim())
                    {
                        jGrowl_to_notyfy("The field name can not be NULL!", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (did_price)
                    {
                        if (!checknumber(did_price, 'DID Price'))
                        {
                            return false;
                        }
                    }
                    if (!checknumber(min_price, 'MIN Price'))
                    {
                        return false;
                    }

                    return true;
                }
            });
        });

<?php if (!count($this->data)): ?>
            $("#add").click();
<?php endif; ?>
    });

    $(document).on('click', function(){
        $('#save').attr('title', "Save");
    });
</script>

<script>
    $(document).ready(function () {
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        $('#apply_min_interval').on('click', function(){
            let rate_table = $(this).attr('data-rate-table');
            let rate_table_name = $(this).attr('data-rate-table-name');
            $('#modalAddRateDetails').find('#min_time').val(1);
            $('#modalAddRateDetails').find('#interval').val(1);
            if(rate_table){
                $('#modalAddRateDetails').find('#rate_table_id').val(rate_table);
                $('#modalAddRateDetails').find('h3').text('Rate Table: ' + rate_table_name);
            }
        })

        $('#apply_submit').on('click', function(){
            $.ajax({
                url: "<?php echo $this->webroot ?>did/billing_rule/apply_details",
                type: 'post',
                data: $("#modalAddRateDetails_form").serialize(),
                dataType:'JSON',
                success: function(data) {
                   if (data && data.status)
                    {
                       jGrowl_to_notyfy('<?php __('Min Time and Interval applied successfully!'); ?>', {theme: 'jmsg-success'});
                    } else {
                       jGrowl_to_notyfy('<?php __('Rate Table could not found !'); ?>', {theme: 'jmsg-error'});
                    }
                    $('#modalAddRateDetails').modal('hide');
                }
            });
        });

    });
</script>
