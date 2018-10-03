<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/billing_rule/plan"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/billing_rule/plan">
        <?php echo __('Billing Rule') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/billing_rule/special_code">
        <?php echo __('Special Code') ?></a></li>
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
    </div>
<?php } ?>
<div class="buttons pull-right ">
    <a   class="btn btn-primary btn-icon glyphicons upload"   href="<?php echo $this->webroot; ?>uploads/special_code"><i></i>
<?php __('Upload') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li><a href="<?php echo $this->webroot; ?>did/billing_rule/plan" class="glyphicons left_arrow"><i></i><?php __('Billing Rule'); ?></a></li>
                <li class="active" ><a href="<?php echo $this->webroot; ?>did/billing_rule/special_code" class="glyphicons right_arrow"><i></i><?php __('Special Code'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br />
                        <h2>
    <?php echo __('no_data_found', true); ?>
                        </h2>
                    </div>

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">

                        <thead>
                            <tr>
                                <th><?php __('ANI Prefix') ?></th>
                                <th><?php __('Pricing') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
<?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php __('ANI Prefix') ?></th>
                                <th><?php __('Pricing') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>

                        <tbody>
    <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['DidSpecialCode']['code']; ?></td>
                                    <td><?php echo $item['DidSpecialCode']['pricing']; ?></td>
                                    <td>
                                        <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item['DidSpecialCode']['id'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>

                                        <a title="<?php __('Delete') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" class="delete" href='<?php echo $this->webroot; ?>did/billing_rule/delete_code/<?php echo base64_encode($item['DidSpecialCode']['id']) ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
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

<script>
    function checknumber(input, name)
    {
        var re = /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/; //判断字符串是否为数字 
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an number.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }
    jQuery(function() {
        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();

            jQuery('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>did/billing_rule/code_edit_panel",
                action: "<?php echo $this->webroot ?>did/billing_rule/code_edit_panel",
                'insertNumber': 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery(".msg").show();
                        jQuery('table.list').hide();
                    }
                },
                onsubmit: function() {
                    var pricing = $("#DidSpecialCodePricing").val();
                    var name = $("#DidSpecialCodeCode").val();
                    if (!name)
                    {
                        jGrowl_to_notyfy("Code can not be empty!.", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (/[^0-9]+/.test(name)){
                        jGrowl_to_notyfy("Code must be integer.", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (!checknumber(pricing, 'Pricing'))
                    {
                        return false;
                    }
                    return true;
                }
            });

            // var tr = $('#trAdd');
            // tr.clone().prependTo('tbody');
            // tr.remove();
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function() {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>did/billing_rule/code_edit_panel/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>did/billing_rule/code_edit_panel/' + jQuery(this).attr('control'),
                saveType: 'edit',
                onsubmit: function() {
                    var pricing = $("#DidSpecialCodePricing").val();
                    var name = $("#DidSpecialCodeCode").val();
                    if (!name)
                    {
                        jGrowl_to_notyfy("Code can not be empty!.", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (/[^0-9]+/.test(name)){
                        jGrowl_to_notyfy("Code must be integer.", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (!checknumber(pricing, 'Pricing'))
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

</script>