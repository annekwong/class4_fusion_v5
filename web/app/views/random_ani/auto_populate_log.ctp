<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
    table.in-date tr td{border-top: 0;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php echo __('Random ANI Group') ?>
        <?php echo isset($random_table['RandomAniTable']['name']) ? "[" . $random_table['RandomAniTable']['name'] . "]" : ""; ?>
    </li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Random ANI Group') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>random_ani/random_table"><i></i><?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a class="glyphicons justify" href="<?php echo $this->webroot; ?>random_ani/random_generation/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('ANI Number') ?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>uploads/random_generation/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Import') ?> 
                    </a>
                </li>
                <li>
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>random_ani/auto_populate/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Auto Populate') ?>  
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>random_ani/auto_populate_log/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Auto Populate Log') ?>  
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2>
                        <?php echo __('no data found', true); ?>
                    </h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th class="footable-first-column expand" data-class="expand">
                                <?php __('Random ANI GROUP'); ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('RandomAniPopulatedLog.start_time', __('Start Time', true)) ?>
                            </th>
                            <th><?php echo $appCommon->show_order('RandomAniPopulatedLog.finsh_time', __('Finshed Time', true)) ?></th>
                            <th><?php __('Prefix'); ?></th>
                            <th><?php __('Number of Digits') ?></th>
                            <th><?php __('Status'); ?></th>
                            <th><?php __('Total'); ?></th>
                            <th><?php __('Completed'); ?></th>
                            <th><?php __('Success'); ?></th>
                            <th><?php __('Duplicate') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="random_ani_tbody">
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['RandomAniTable']['name']; ?></td>
                                <td><?php echo $item[0]['start_time']; ?></td>
                                <td><?php echo $item[0]['finsh_time']; ?></td>
                                <td><?php echo $item[0]['prefix']; ?></td>
                                <td><?php echo $item[0]['number_of_digits']; ?></td>
                                <td><?php echo $appCommon->_isset($status[$item[0]['status']]); ?></td>
                                <td><?php echo $item[0]['total_num']; ?></td>
                                <td>
                                    <?php echo $item[0]['total_num'] ? ($item[0]['success_num'] + $item[0]['duplicate_num'])/$item[0]['total_num']*100 : 0; ?>%
                                </td>
                                <td><?php echo $item[0]['success_num']; ?></td>
                                <td><?php echo $item[0]['duplicate_num']; ?></td>
                                <td>
                                    <?php if (in_array($item[0]['status'], array(0, 1))): ?>
                                        <a  title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to kill'); ?>', this)"  href="<?php echo $this->webroot; ?>random_ani/auto_populate_kill/<?php echo base64_encode($item[0]['id']); ?>">
                                            <i class="icon-remove"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid">
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

    $(function() {
    });

</script>



