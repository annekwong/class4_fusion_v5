<style type="text/css">
    #add_panel {display:none;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Dynamic Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Override') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Override') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="###"><i></i> <?php __('Create New') ?></a>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>dynamicroutes/view"><i></i> <?php __('Back'); ?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="get">
                <div class="filter-bar">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search" />
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php echo __('Egress Trunk', true); ?>:</label>
                        <select name="egress_trunk" class="input-small">
                            <option selected="selected"></option>
                            <?php foreach ($egress_trunks as $key => $egress_trunk): ?>
                                <option value="<?php echo $key ?>" <?php echo $common->set_get_select('egress_trunk', $key); ?>><?php echo $egress_trunk ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label><?php echo __('Percentage Range', true); ?>:</label>
                        <input type="text" class="input-small" name="p_start" value="<?php echo $common->set_get_value('p_start') ?>" />
                        ~
                        <input type="text" class="input-small" name="p_end" value="<?php echo $common->set_get_value('p_end') ?>" />
                    </div>

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>

            <?php
            $overrides = $p->getDataArray();
            ?>
            <div class="clearfix"></div>



            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Prefix'); ?></th>
                        <th><?php __('Egress Trunk'); ?></th>
                        <th><?php __('Percentage'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="add_panel">
                        <td>
                            <input type="text" name="digits" />
                        </td>
                        <td>
                            <select name="egress_trunk">
                                <?php foreach ($egress_trunks as $key => $val): ?>
                                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="percentage" />
                        </td>
                        <td>
                            <a href="###" id="save" title="<?php __('Save') ?>">
                                <i class="icon-save"></i>
                            </a>
                            <a href="###" id="cancel" title="<?php __('Cancel') ?>">
                                <i class='icon-remove'></i>
                            </a>
                        </td>
                    </tr>
                    <?php foreach ($overrides as $override): ?>
                        <tr>
                            <td><?php echo $override[0]['digits']; ?></td>
                            <td><?php echo $egress_trunks[$override[0]['resource_id']]; ?></td>
                            <td><?php echo $override[0]['percentage']; ?></td>
                            <td>
                                <a title="Edit" href="javascript:void(0)" class="edit" control="<?php echo $override[0]['id']; ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a class="delete_overrides" href="javascript:void(0)" url="<?php echo $this->webroot; ?>dynamicroutes/delete_override/<?php echo $override[0]['id']; ?>/<?php echo $dynamic_id ?>" title="<?php __('Delete') ?>">
                                    <i class='icon-remove'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function checkint(input, name)
    {
        var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an integer.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checklarge(input, max, name)
    {
        if (input > max) {
            jGrowl_to_notyfy(name + " can not greater than " + max + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkless(input, min, name) {
        if (input < min) {
            jGrowl_to_notyfy(name + " can not less than " + min + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    $(function() {

        $(".delete_overrides").click(function() {
            var url = $(this).attr('url');
            bootbox.confirm('Are you sure to delete the item?', function(result) {
                if (result) {
                    window.location.href = url;
                }
            });
        });


        $('#add').click(function() {
            $('#add_panel').show();
        });

        $('#cancel').click(function() {
            $('#add_panel input').val('');
            $('#add_panel').hide();
        });

        $('#save').click(function() {
            var digits = $('#add_panel input:eq(0)').val();
            var percentage = $('#add_panel input:eq(1)').val();
            var egress_trunk = $('#add_panel select:eq(0)').val();
            var dynamic_id = <?php echo $dynamic_id ?>;
            if (!checkint(digits, "Prefix")) {
                return false;
            }
            if (!checkint(percentage, "percentage")) {
                return false;
            }
            $.ajax({
                url: '<?php echo $this->webroot; ?>dynamicroutes/create_override',
                type: 'POST',
                dataType: 'text',
                data: {digits: digits, percentage: percentage, egress_trunk: egress_trunk, dynamic_id: dynamic_id},
                success: function(data) {
                    if ($.trim(data) == '2') {
                        jGrowl_to_notyfy("The field Prefix duplicate!", {theme: 'jmsg-error'});
                    }
                    else if ($.trim(data) == '3') {
                        jGrowl_to_notyfy("Total percent can not large than 100!", {theme: 'jmsg-error'});
                    }
                    else {
                        window.location.reload();
                    }
                }
            });
        });

        var editHandle = function() {
            var $this = $(this);
            var $tr = $this.parents('tr').clone(true);

            $this.html("<i class='icon-save'></i>");
            $this.next().click(function() {
                $this.parents('tr').replaceWith($tr);
                return false;
            }).find('img').attr('title', 'Cancel');
            $this.attr('title', 'Save');
            var $prefix = $this.parent().siblings('td:eq(0)');
            var $trunk = $this.parent().siblings('td:eq(1)');
            var $percentage = $this.parent().siblings('td:eq(2)');
            $prefix.html('<input class="input in-text in-input" value="' + $.trim($prefix.text()) + '">');
            $percentage.html('<input class="input in-text in-input" value="' + $.trim($percentage.text()) + '">');
            var trunk_tag = $('#add_panel select:eq(0)').clone(true);
            trunk_tag.find('option[text="' + $trunk.text() + '"]').attr('selected', true);
            $trunk.html(trunk_tag);
            $this.unbind('click');
            $this.bind('click', function() {
                var override_id = $this.attr('control');
                var prefix = $prefix.find('input').val();
                var trunk = $trunk.find('select').val();
                var percentage = $percentage.find('input').val();
                var dynamic_id = <?php echo $dynamic_id ?>;
                if (!checkint(prefix, "Prefix")) {
                    return false;
                }
                if (!checkint(percentage, "percentage")) {
                    return false;
                }
                $.ajax({
                    'url': '<?php echo $this->webroot ?>dynamicroutes/update_override',
                    'type': 'POST',
                    'dataType': 'text',
                    'data': {override_id: override_id, prefix: prefix, trunk: trunk, percentage: percentage, dynamic_id: dynamic_id},
                    'success': function(data) {
                        if ($.trim(data) == '1') {
                            jGrowl_to_notyfy("Succeeded", {theme: 'jmsg-success'});
                            $prefix.text(prefix);
                            $trunk.text($trunk.find('select option:selected').text());
                            $percentage.text(percentage);
                            $this.html('<i class="icon-edit"></i>');
                            $this.unbind('click');
                            $this.bind('click', editHandle);
                        }
                        else if ($.trim(data) == '3') {
                            jGrowl_to_notyfy("Total percent can not greater than 100!", {theme: 'jmsg-error'});
                        }
                        else {
                            jGrowl_to_notyfy("The field Prefix duplicate!", {theme: 'jmsg-error'});
                        }
                    }
                });
            });
        }

        $('a.edit').bind('click', editHandle);
    });
</script>