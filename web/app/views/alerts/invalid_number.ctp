<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<?php
$detection_type = isset($this->params['pass'][0]) && $this->params['pass'][0] == 2 ? 2 : 1;
$mydata = $p->getDataArray();
$loop = count($mydata);
?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>alerts/invalid_number"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>alerts/invalid_number">
        <?php echo __('Invalid Number Detection') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invalid Number Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Monitoring']['alerts:invalid_number']['model_w'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>alerts/add_invalid_number/<?php echo $detection_type ?>"><i></i> <?php __('Create New') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>alerts/delete_rules_invalid_all/<?php echo $detection_type; ?>');" href="javascript:void(0)"><i></i> <?php __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('ruleId', '<?php echo $this->webroot ?>alerts/delete_rules_invalid_selected', 'rule');" href="javascript:void(0)"><i></i> <?php __('Delete Seleted') ?></a>
    <?php } ?>
    <?php
    if (isset($edit_return))
    {
        ?>
        <a href="<?php echo $this->webroot; ?>alerts/invalid_number" class="link_back btn btn-default btn-icon glyphicons left_arrow"><i></i> Back</a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("invalid_number_detection/tab",array('active' => 'basic_'.$detection_type)); ?>
        </div>
        <div class="widget-body">
            <?php if (empty($mydata)): ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                    <tr>
                        <th>
                            <?php if ($_SESSION['login_type'] == '1'): ?>
                                <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'ruleId');" value=""/>
                            <?php endif; ?></th>
                        <th><?php __('Rule Name'); ?> </th>
                        <th><?php __('Last Run Time'); ?></th>
                        <th><?php __('Next Run Time'); ?></th>
                        <th><?php echo __('Update By'); ?></th>
                        <th><?php echo __('Update At'); ?></th>
                        <th><?php __('Active'); ?> </th>
                        <th><?php echo __('action'); ?></th>
                    </tr>
                    </thead>
                    <tbody  id="ruleId">
                    <?php foreach ($mydata as $data_item): ?>
                        <?php
                        if($data_item[0]['active']){
                            if ($detection_type == 1){
                                if($data_item[0]['ani_last_run_time'])
                                    $next_run_time = $data_item[0]['ani_next_run_time'];
                                else
                                    $next_run_time = $data_item[0]['next_min'];
                            }else{
                                if($data_item[0]['dnis_last_run_time'])
                                    $next_run_time = $data_item[0]['dnis_next_run_time'];
                                else
                                    $next_run_time = $data_item[0]['next_min'];
                            }
                        }else{
                            $next_run_time = '--';
                        }
                        ?>
                        <tr>
                            <td style="text-align:center">
                                <?php if ($_SESSION['login_type'] == '1'): ?>
                                    <input class="select" type="checkbox" value="<?php echo $data_item[0]['id'] ?>"/>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php $this->webroot; ?>add_invalid_number/<?php echo $detection_type ?>/<?php echo base64_encode($data_item[0]['id']); ?>">
                                    <?php echo $data_item[0]['rule_name']; ?>
                                </a>
                            </td>
                            <td><?php echo $detection_type == 1 ? $data_item[0]['ani_last_run_time'] : $data_item[0]['dnis_last_run_time'];  ?></td>
                            <td><?php echo $next_run_time; ?> </td>
                            <td><?php echo $data_item[0]['update_by']; ?> </td>
                            <td><?php echo $data_item[0]['update_at']; ?> </td>
                            <td><?php echo $data_item[0]['active'] ? __('Yes',true):__('NO',true); ?> </td>
                            <td>
                                <a title="<?php __('Edit'); ?>" href="<?php $this->webroot; ?>add_invalid_number/<?php echo $detection_type ?>/<?php echo base64_encode($data_item[0]['id']); ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <?php if ($data_item[0]['active']): ?>
                                    <a title="<?php __('Inactive') ?>" onclick="return myconfirm('<?php __('sure to inactive'); ?>',this);"
                                       href="<?php echo $this->webroot; ?>alerts/disable_invalid/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                        <i class="icon-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a title="<?php __('Active') ?>"  onclick="return myconfirm('<?php __('sure to active'); ?>',this);"
                                       href="<?php echo $this->webroot; ?>alerts/enable_invalid/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>
                                <a onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;" href="<?php echo $this->webroot ?>alerts/delete_rules_invalid_selected_id/<?php echo base64_encode($data_item[0]['id']);?>"  title="Delete">
                                    <i class="icon-remove"></i>
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
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

    });
</script>