<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Plan') ?><font  class="editname" title="Name">
            <?php echo empty($rs_name[0][0]['name']) || $rs_name[0][0]['name'] == '' ? '' : "[" . $rs_name[0][0]['name'] . "]"; ?>
        </font></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('LRN Block') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LRN Block') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']): ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add_block_route" href="javascript:void(0)"><i></i> <?php __('Create New') ?></a>
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove"
           onclick="deleteAll('<?php echo $this->webroot ?>routestrategys/del_route/all/<?php echo $id ?>?lrn_block=1');"
           href="javascript:void(0)"><i></i> <?php echo __('Delete All') ?></a>
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove"
           onclick="deleteSelected('lrn_block_tbody', '<?php echo $this->webroot ?>routestrategys/del_route/selected/<?php echo $id ?>?lrn_block=1', 'block row(s)');"
           href="javascript:void(0)"><i></i> <?php echo __('Delete Selected') ?></a>
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('uploads/route_plan_tabs',array('active' => 'block')) ?>
        </div>
        <div class="widget-body">
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br/><?php echo __('no_data_found', true); ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary hide">
                    <thead>
                    <tr>
                        <th class="footable-first-column expand" data-class="expand">
                            <input type="checkbox"  class="checkAll" onClick="checkAllOrNot(this, 'lrn_block_tbody');" value=""/>
                        </th>
                        <th><?php __('Code prefix') ?></th>
                        <th><?php echo $appCommon->show_order('update_at', __('Updated On', true)) ?></th>
                        <th><?php __('Create by')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody id="lrn_block_tbody">
                    </tbody>
                </table>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th class="footable-first-column expand" data-class="expand">
                            <input type="checkbox" class="checkAll" onClick="checkAllOrNot(this, 'lrn_block_tbody');" value=""/>
                        </th>
                        <th><?php __('Code prefix') ?></th>
                        <th><?php echo $appCommon->show_order('update_at', __('Updated On', true)) ?></th>
                        <th><?php __('Create by')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody id="lrn_block_tbody">
                    <?php foreach ($this->data as $data_item): ?>
                        <tr>
                            <td><input type="checkbox" value="<?php echo $data_item['Route']['route_id'] ?>"/></td>
                            <td><?php echo $data_item['Route']['digits']; ?></td>
                            <td><?php echo $data_item['Route']['update_at']; ?></td>
                            <td><?php echo $data_item['Route']['update_by']; ?></td>
                            <td>
                                <a title="<?php __('edit') ?>" route_id="<?php echo $data_item['Route']['route_id']; ?>"
                                   href="javascript:void(0)" class="edit_route">
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php echo __('del') ?>"  href="<?php echo $this->webroot ?>routestrategys/del_route/<?php echo $data_item['Route']['route_id'].'/'. $id; ?>?lrn_block=1"
                                   onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;"><i class="icon-remove"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#add_block_route').click(function() {
        $('.msg').hide();
        $('table.list').show();
        $('table.list tbody').trAdd({
            ajax: "<?php echo $this->webroot ?>routestrategys/lrn_block_edit_panel",
            action: "<?php echo $this->webroot ?>routestrategys/lrn_block_edit_panel",
            data:{'route_id':'','plan_id':'<?php echo $id; ?>'},
            'insertNumber': 'first',
            removeCallback: function() {
                if ($('table.list tr').size() == 1) {
                    $('table.list').hide();
                    $('.msg').show();
                }
            },
            onsubmit: function() {
                var a = $('#trAdd').find('input').validationEngine('validate');
                if (a == 1){
                    return false;
                }else{
                    return true;
                }
            }
        });
    });
    $('a.edit_route').click(function() {
        var route_id =  $(this).attr('route_id');
        $(this).parent().parent().trAdd({
            ajax: '<?php echo $this->webroot ?>routestrategys/lrn_block_edit_panel/' + route_id,
            action: "<?php echo $this->webroot ?>routestrategys/lrn_block_edit_panel",
            data:{'route_id':route_id,'plan_id':'<?php echo $id; ?>'},
            saveType: 'edit',
            onsubmit: function() {
                var a = $('#trAdd').find('input').validationEngine('validate');
                if (a == 1){
                    return false;
                }else{
                    return true;
                }
            },
        });
    });

    $('.checkAll').on('click', function(){
        $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
    
</script> 