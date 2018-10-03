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
    <li><?php echo __('Trunk Priority') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Trunk Priority') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)"><i></i> <?php __('Create New') ?></a>
        <a id="delete_selected" class="btn btn-primary btn-icon glyphicons remove" href="javascript:void(0)"><i></i> <?php __('Delete Selected'); ?></a>
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
                        <label><?php echo __('Priority Range', true); ?>:</label>
                        <input type="text" class="input-small" name="p_start" value="<?php echo $common->set_get_value('p_start') ?>" />
                        ~
                        <input type="text" class="input-small" name="p_end" value="<?php echo $common->set_get_value('p_end') ?>" />
                    </div>
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>

            <?php
            $pris = $p->getDataArray();
            ?>
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><input id="chk" type="checkbox" /></th>
                        <th><?php __('Prefix') ?></th>
                        <th><?php __('Egress Trunk') ?></th>
                        <th><?php __('Priority') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="add_panel">
                        <td>
                        </td>
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
                            <select name="resource_pri">
                                <?php for ($i = 1; $i < 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="save" title="<?php __('Save') ?>">
                                <i class='icon-save'></i>
                            </a>
                            <a href="javascript:void(0)" id="cancel" title="<?php __('Cancel') ?>">
                                <i class='icon-remove'></i>
                            </a>
                        </td>
                    </tr>
                    <?php foreach ($pris as $pri): ?>
                        <tr>
                            <td>
                                <input type="checkbox" value="<?php echo $pri[0]['id']; ?>" />
                            </td>
                            <td><?php echo $pri[0]['digits']; ?></td>
                            <td><?php echo $egress_trunks[$pri[0]['resource_id']]; ?></td>
                            <td><?php echo $pri[0]['resource_pri']; ?></td>
                            <td>
                                <a href="javascript:void(0)" class="edit" control="<?php echo $pri[0]['id']; ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <a class="delete_priority" onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;"
                                   href="<?php echo $this->webroot; ?>dynamicroutes/delete_priority/<?php echo base64_encode($pri[0]['id']); ?>/<?php echo base64_encode($dynamic_id); ?>">
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
        if (parseInt(input) > parseInt(max)) {
            jGrowl_to_notyfy(name + " can not greater than " + max + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkless(input, min, name) {
        if (parseInt(input) < parseInt(min)) {
            jGrowl_to_notyfy(name + " can not less than " + min + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    $(function() {


        $('#add').click(function() {
            $('#add_panel').show();
        });

        $('#cancel').click(function() {
            $('#add_panel input').val('');
            $('#add_panel').hide();
        });

        $('#save').click(function() {
            var digits = $('#add_panel input:eq(0)').val();
            var resource_pri = $('#add_panel select:eq(1)').val();
            var egress_trunk = $('#add_panel select:eq(0)').val();
            var dynamic_id = <?php echo $dynamic_id ?>;
            if (digits && !checkint(digits, "Prefix")) {
                return false;
            }
            $.ajax({
                url: '<?php echo $this->webroot; ?>dynamicroutes/create_pri',
                type: 'POST',
                dataType: 'text',
                data: {digits: digits, resource_pri: resource_pri, egress_trunk: egress_trunk, dynamic_id: dynamic_id},
                success: function(data) {
                    if ($.trim(data) == '2') {
                        jGrowl_to_notyfy("Trunk duplicate!", {theme: 'jmsg-error'});
                    } else {
                        window.location.reload();
                    }
                }
            });
        });

        var editHandle = function() {
            var $this = $(this);
            var $tr = $this.parents('tr').clone(true);
            var delete_btn = $this.next();
            $this.html("<i class='icon-save'></i>");
            $this.next().replaceWith("<a href='javascript:void(0)' title='Cancel'><i class='icon-remove'></i></a>");
            //alert();
            $this.next().click(function() {
                $this.parents('tr').replaceWith($tr);
                $this.parents('tr').find('.edit').bind('click',editHandle);
                return false;
            });
            var $prefix = $this.parent().siblings('td:eq(1)');
            var $trunk = $this.parent().siblings('td:eq(2)');
            var $resource_pri = $this.parent().siblings('td:eq(3)');
            $prefix.html('<input class="input in-text in-input" value="' + $.trim($prefix.text()) + '">');
            var trunk_tag = $('#add_panel select:eq(0)').clone(true);
            trunk_tag.find('option').each(function(){
                if ($(this).text() == $trunk.text()){
                    $(this).attr('selected', true);
                }
            });
            $trunk.html(trunk_tag);
            var resource_pri_tag = $('#add_panel select:eq(1)').clone(true);
            resource_pri_tag.find('option[value="' + $resource_pri.text() + '"]').attr('selected', true);
            $resource_pri.html(resource_pri_tag);
            $trunk.html(trunk_tag);
            $this.bind('click', function() {
                $this = $(this);
                var digits = $this.parent().siblings('td:eq(0)').children().eq(0).val();
                var $prefix = $this.parent().siblings('td:eq(1)');
                var $trunk = $this.parent().siblings('td:eq(2)');
                var $resource_pri = $this.parent().siblings('td:eq(3)');
                var id = $this.attr('control');
                var prefix = $prefix.find('input').val();
                var trunk = $trunk.find('select').val();
                var resource_pri = $resource_pri.find('select').val();
                var dynamic_id = '<?php echo $dynamic_id ?>';
                if (!checkint(digits, "Prefix")) {
                    return false;
                }
                $.ajax({
                    'url': '<?php echo $this->webroot ?>dynamicroutes/update_pri',
                    'type': 'POST',
                    'dataType': 'text',
                    'data': {id: id, prefix: prefix, trunk: trunk, resource_pri: resource_pri, dynamic_id: dynamic_id},
                    'success': function(data) {
                        if ($.trim(data) == '1') {
                            jGrowl_to_notyfy("Succeeded", {theme: 'jmsg-success'});
                            $prefix.text(prefix);
                            $trunk.text($trunk.find('option[value="' + trunk + '"]').text());
                            $resource_pri.text($resource_pri.find('option[value="' + resource_pri + '"]').text());
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

//        $(".save").live('click', function() {
//            $this = $(this);
//            var digits = $this.parent().siblings('td:eq(0)').children().eq(0).val();
//            var $prefix = $this.parent().siblings('td:eq(1)');
//            var $trunk = $this.parent().siblings('td:eq(2)');
//            var $resource_pri = $this.parent().siblings('td:eq(3)');
//            var id = $this.attr('control');
//            var prefix = $prefix.find('input').val();
//            var trunk = $trunk.find('select').val();
//            var resource_pri = $resource_pri.find('select').val();
//            var dynamic_id = <?php //echo $dynamic_id ?>//;
//            if (!checkint(digits, "Prefix")) {
//                return false;
//            }
//            $.ajax({
//                'url': '<?php //echo $this->webroot ?>//dynamicroutes/update_pri',
//                'type': 'POST',
//                'dataType': 'text',
//                'data': {id: id, prefix: prefix, trunk: trunk, resource_pri: resource_pri, dynamic_id: dynamic_id},
//                'success': function(data) {
//                    if ($.trim(data) == '1') {
//                        jGrowl_to_notyfy("Succeeded", {theme: 'jmsg-success'});
//                        $prefix.text(prefix);
//                        $trunk.text($trunk.find('select option:selected').text());
//                        $resource_pri.text($resource_pri.find('select option:selected').text());
//                        $this.html('<i class="icon-edit"></i>');
//                        $this.unbind('click');
//                        $this.bind('click', editHandle);
//                    } else {
//                        jGrowl_to_notyfy("Prefix duplicate!", {theme: 'jmsg-error'});
//                    }
//                }
//            });
//        });

        $('a.edit').live('click', editHandle);

        $('#chk').click(function() {
            $('table.list tbody input:checkbox').attr('checked', $(this).attr('checked'));
        });

        $('#delete_selected').click(function() {
            var delete_list = new Array();
            $('table.list tbody input:checkbox:checked').each(function(index, item) {
                delete_list.push($(this).val());
            });
            var delete_str = delete_list.join(',');
	if (delete_str == '') {
		jGrowl_to_notyfy("You haven't selected anything to delete!", {theme: 'jmsg-error'});
		return false;
	}
            $.ajax({
                'url': "<?php echo $this->webroot ?>dynamicroutes/delete_mul_priority/<?php echo $dynamic_id ?>",
                                'type': 'POST',
                                'dataType': 'text',
                                'data': {'ids': delete_str},
                                'success': function(data) {
                                    jGrowl_to_notyfy("Successfully!", {theme: 'jmsg-success'});
                                    window.setTimeout('window.location.reload();', 1000);
                                }
                            });
                        });
                    });
</script>
