<style type="text/css">
    #add_panel {display:none;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Dynamic Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('QoS Parameters') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('QoS Parameters') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)"><i></i> <?php __('Create New') ?></a>
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
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search')?>" value="Search" name="search" />
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="javascript:void(0)">
                            <i class="icon-long-arrow-down"></i>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance') ?></h3></div>
                    <div class="widget-body">
                        <div class="filter-bar">

                            <!-- Filter -->
                            <div>
                                <label><?php echo __('ASR range', true); ?>:</label>
                                <input type="text" class="input-small" name="asr_min" value="<?php echo $common->set_get_value('asr_min') ?>" />
                                ~
                                <input type="text" class="input-small" name="asr_max" value="<?php echo $common->set_get_value('asr_max') ?>" >
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('ACD range', true); ?>:</label>
                                <input type="text" class="input-small"name="acd_min" value="<?php echo $common->set_get_value('acd_min') ?>" />
                                ~
                                <input type="text" class="input-small" name="acd_max" value="<?php echo $common->set_get_value('acd_max') ?>" />
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <!--
                            <div>
                                <label><?php echo __('ALOC range', true); ?>:</label>
                                <input type="text" class="input-small" name="aloc_min" value="<?php echo $common->set_get_value('aloc_min') ?>" />
                                ~
                                <input type="text" class="input-small" name="aloc_max" value="<?php echo $common->set_get_value('aloc_max') ?>" />
                            </div>
                            <div>
                                <label><?php echo __('PDD range', true); ?>:</label>
                                <input type="text" class="input-small" name="pdd_min" value="<?php echo $common->set_get_value('pdd_min') ?>" />
                                ~
                                <input type="text" class="input-small" name="pdd_max" value="<?php echo $common->set_get_value('pdd_max') ?>" />
                            </div>

                            <div>
                                <label><?php echo __('ABR range', true); ?>:</label>
                                <input type="text" class="input-small" name="abr_min" value="<?php echo $common->set_get_value('abr_min') ?>" />
                                ~
                                <input type="text" class="input-small" name="abr_max" value="<?php echo $common->set_get_value('abr_max') ?>" />
                            </div>
                            -->

                        </div>
                    </div>

                </div>
            </form>
            <div class="clearfix"></div>


            <?php
            $qoss = $p->getDataArray();
            ?>
            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php __('Prefix')?></th>
                    <th><?php __('Min ASR')?></th>
                    <th><?php __('Max ASR')?></th>
                    <th><?php __('Min ACD')?></th>
                    <th><?php __('Max ACD')?></th>
                    <th><?php __('Max Price')?></th>
                    <th><?php __('Action')?></th>
                </tr>
                </thead>
                <tbody>
                <tr id="add_panel">
                    <td>
                        <input type="text" name="digits" style="width:60px;" />

                    </td>
                    <td>
                        <input type="text" name="min_asr" style="width:40px;" />
                    </td>
                    <td>
                        <input type="text" name="max_asr" style="width:40px;" />
                    </td>
                    <td>
                        <input type="text" name="min_acd" style="width:40px;" />
                    </td>
                    <td>
                        <input type="text" name="max_acd" style="width:40px;" />
                    </td>
                    <td>
                        <input type="text" name="limit_price" style="width:40px;" />
                    </td>
                    <td>
                        <a href="javascript:void(0)" id="save" title="<?php __('Save') ?>">
                            <i class="icon-save"></i>
                        </a>
                        <a href="javascript:void(0)" id="cancel" title="<?php __('Cancel') ?>">
                            <i class='icon-remove'></i>
                        </a>
                    </td>
                </tr>
                <?php foreach ($qoss as $qos): ?>
                    <tr>
                        <td><?php echo $qos[0]['digits']; ?></td>
                        <td><?php echo $qos[0]['min_asr']; ?></td>
                        <td><?php echo $qos[0]['max_asr']; ?></td>
                        <td><?php echo $qos[0]['min_acd']; ?></td>
                        <td><?php echo $qos[0]['max_acd']; ?></td>
                        <td><?php echo $qos[0]['limit_price']; ?></td>
                        <td>
                            <a title="Edit" href="javascript:void(0)" class="edit" control="<?php echo $qos[0]['id']; ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <a onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;" href="<?php echo $this->webroot; ?>dynamicroutes/delete_qos/<?php echo base64_encode($qos[0]['id']); ?>/<?php echo base64_encode($dynamic_id); ?>" title="<?php __('Delete') ?>">
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

    function checkData(input, name)
    {
        var re = /^((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an number.", {theme: 'jmsg-error'});
            return false;
        }
//        if (parseFloat(input) > 1){
//            jGrowl_to_notyfy(name + " can not greater than 1 .", {theme: 'jmsg-error'});
//            return false;
//        }
        if (parseFloat(input) < 0.0001){
            jGrowl_to_notyfy(name + " can not less than 0.0001 .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }


    function checklarge(input, max, name)
    {
        if (parseFloat(input) > parseFloat(max)) {
            jGrowl_to_notyfy(name + " can not greater than " + max + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkless(input, min, name) {
        if (parseFloat(input) < parseFloat(min)) {
            jGrowl_to_notyfy(name + " can not less than " + min + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    $(function() {

        $(".delete_qos").click(function() {
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
            var min_asr = $('#add_panel input:eq(1)').val();
            var max_asr = $('#add_panel input:eq(2)').val();
            var min_acd = $('#add_panel input:eq(3)').val();
            var max_acd = $('#add_panel input:eq(4)').val();
            var limit_price = $('#add_panel input:eq(5)').val();
            var dynamic_id = '<?php echo $dynamic_id ?>';
            if (!checkint(digits, "Prefix")) {
                return false;
            }
            if (min_asr && !checkData(min_asr, "MIN ASR")) {
                return false;
            }
            if (max_asr && !checkData(max_asr, "MAX ASR")) {
                return false;
            }
            if (max_asr && min_asr) {
                if (!checkless(max_asr, min_asr, "MAX ASR")) {
                    return false;
                }
            }


            if (min_acd && !checkData(min_acd, "MIN ACD")) {
                return false;
            }
            if (max_acd && !checkData(max_acd, "MAX ACD")) {
                return false;
            }
            if (max_acd && min_acd) {
                if (!checkless(max_acd, min_acd, "MAX ACD")) {
                    return false;
                }
            }


            $.ajax({
                url: '<?php echo $this->webroot; ?>dynamicroutes/create_qos',
                type: 'POST',
                dataType: 'text',
                data: {digits: digits, min_asr: min_asr, max_asr: max_asr, min_abr: '', max_abr: '', min_acd: min_acd, max_acd: max_acd, min_pdd: '', max_pdd: '', min_aloc: '', max_aloc: '', limit_price: limit_price, dynamic_id: dynamic_id},
                success: function(data) {
                    if ($.trim(data) == '2') {
                        jGrowl_to_notyfy("The field Prefix duplicate!", {theme: 'jmsg-error'});
                    } else {
                        window.location.reload();
                    }
                }
            });
        });

        var editHandle = function() {
            var $this = $(this);
            var $tr = $this.parents('tr').clone(true);
            $this.html("<i class='icon-save'></i>");
            $this.next().attr('onclick', '');
            $this.next().click(function() {
                $this.parents('tr').replaceWith($tr);
                $this.parents('tr').find('.edit').bind('click',editHandle);
                return false;
            });
            var $prefix = $this.parent().siblings('td:eq(0)');
            var $min_asr = $this.parent().siblings('td:eq(1)');
            var $max_asr = $this.parent().siblings('td:eq(2)');
            var $min_acd = $this.parent().siblings('td:eq(3)');
            var $max_acd = $this.parent().siblings('td:eq(4)');
            var $limit_price = $this.parent().siblings('td:eq(5)');
            $prefix.html('<input style="width:60px;" class="input in-text in-input" value="' + $.trim($prefix.text()) + '">');
            $min_asr.html('<input style="width:40px;" class="input in-text in-input" value="' + $.trim($min_asr.text()) + '">');
            $max_asr.html('<input style="width:40px;" class="input in-text in-input" value="' + $.trim($max_asr.text()) + '">');
            $min_acd.html('<input style="width:40px;" class="input in-text in-input" value="' + $.trim($min_acd.text()) + '">');
            $max_acd.html('<input style="width:40px;" class="input in-text in-input" value="' + $.trim($max_acd.text()) + '">');
            $limit_price.html('<input style="width:40px;" class="input in-text in-input" value="' + $.trim($limit_price.text()) + '">');
            $this.unbind('click');
            $this.bind('click', function() {
                var qos_id = $this.attr('control');
                var prefix = $prefix.find('input').val();
                var min_asr = $min_asr.find('input').val();
                var max_asr = $max_asr.find('input').val();
                var min_acd = $min_acd.find('input').val();
                var max_acd = $max_acd.find('input').val();
                var limit_price = $limit_price.find('input').val();
                var dynamic_id = '<?php echo $dynamic_id ?>';
                if (!checkint(prefix, "Prefix")) {
                    return false;
                }
                if (min_asr && !checkData(min_asr, "MIN ASR")) {
                    return false;
                }
                if (max_asr && !checkData(max_asr, "MAX ASR")) {
                    return false;
                }
                if (max_asr && min_asr) {
                    if (!checkless(max_asr, min_asr, "MAX ASR")) {
                        return false;
                    }
                }


                if (min_acd && !checkData(min_acd, "MIN ACD")) {
                    return false;
                }
                if (max_acd && !checkData(max_acd, "MAX ACD")) {
                    return false;
                }
                if (max_acd && min_acd) {
                    if (!checkless(max_acd, min_acd, "MAX ACD")) {
                        return false;
                    }
                }

                $.ajax({
                    'url': '<?php echo $this->webroot ?>dynamicroutes/update_qos',
                    'type': 'POST',
                    'dataType': 'text',
                    'data': {qos_id: qos_id, prefix: prefix, min_asr: min_asr, max_asr: max_asr, min_abr: '', max_abr: '', min_acd: min_acd, max_acd: max_acd, min_pdd: '', max_pdd: '', min_aloc: '', max_aloc: '', limit_price: limit_price, dynamic_id: dynamic_id},
                    'success': function(data) {
                        if ($.trim(data) == '1') {
                            jGrowl_to_notyfy("Succeeded", {theme: 'jmsg-success'});
                            $prefix.text(prefix);
                            $min_asr.text(min_asr);
                            $max_asr.text(max_asr);
                            $min_acd.text(min_acd);
                            $max_acd.text(max_acd);
                            $limit_price.text(limit_price);
                            $this.html('<i class="icon-edit"></i>');
                            $this.unbind('click');
                            $this.bind('click', editHandle);
                        } else {
                            jGrowl_to_notyfy("Prefix duplicate!", {theme: 'jmsg-error'});
                        }
                    }
                });
            });
        }

        $('a.edit').bind('click', editHandle);
    });
</script>