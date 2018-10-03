<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>detections/bad_number_detection"><?php __('Bad ANI / DNIS Detection') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Bad ANI / DNIS Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>detections/add_bad_number_detection"><i></i><?php __('Create New')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("detection/bad_number_detection_tab",array('active' => 'list')); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <div>
                        <label><?php __('Rule Name') ?>:</label>
                        <input type="text" name="rule_name" value="<?php echo $appCommon->_get('rule_name'); ?>" />
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php if (!count($data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Rule Name'); ?></th>
                        <th><?php __('Last Executed On'); ?></th>
                        <th><?php __('Next Executed On'); ?></th>
                        <th><?php __('ANI or DNIS'); ?></th>
                        <th><?php __('Apply To'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><a href='<?php echo $this->webroot ?>detections/edit_bad_number_detection/<?php echo base64_encode($item['0']['id']) ?>'><?php echo $item[0]['name']; ?></a></td>
                            <td><?php echo $item[0]['last_executed']; ?></td>
                            <td>--</td>
                            <td><?php echo $item[0]['target'] == 0 ? 'ANI' : 'DNIS'?></td>
                            <td>--</td>
                            <td>
                                <a title="Edit" href='<?php echo $this->webroot ?>detections/edit_bad_number_detection/<?php echo base64_encode($item['0']['id']) ?>'>
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href='<?php echo $this->webroot ?>detections/delete_bad_number_detection/<?php echo base64_encode($item['0']['id']) ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                                <a title="Run" href='<?php echo $this->webroot ?>detections/run_bad_number_detection/<?php echo base64_encode($item['0']['id']) ?>'>
                                    <i class="icon-play"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
<!--                <div class="row-fluid separator">-->
<!--                    <div class="pagination pagination-large pagination-right margin-none">-->
<!--                        --><?php //echo $this->element('xpage'); ?>
<!--                    </div>-->
<!--                </div>-->
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

    });
</script>
