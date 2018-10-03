<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rule', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Block', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Block Log', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<?php
$mydata = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <ul class="tabs">
                <li>
                    <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rules">
                        <i></i><?php __('Rule'); ?>			
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_log">
                        <i></i><?php __('Block'); ?>			
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/block_trouble_ticket">
                        <i></i><?php __('Trouble Tickets'); ?>			
                    </a>
                </li>
            </ul>    
        </div>
        <div class="widget-body">

            <?php
            if (!count($mydata))
            {
                ?>
                <div class="msg center">
                    <br />
                    <h3><?php echo __('no_data_found') ?></h3>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('block_on', __('Blocked On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('block_by', __('Blocked By', true)) ?></th>
                            <th><?php echo $appCommon->show_order('code_detail', __('Code Name/Code', true)) ?></th>
                            <!--<th><?php echo $appCommon->show_order('re_enable_time', __('Expected Re-enable Time', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('asr', __('ASR', true)) ?></th>
                            <th><?php echo $appCommon->show_order('abr', __('ABR', true)) ?></th>
                            <th><?php echo $appCommon->show_order('acd', __('ACD', true)) ?></th>
                            <th><?php echo $appCommon->show_order('pdd', __('PDD', true)) ?></th>
                            <th><?php echo $appCommon->show_order('margin', __('Margin', true)) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mydata as $data_item)
                        {
                            ?>
                            <tr>
                                <td><?php echo $data_item[0]['block_on']; ?></td>
                                <td><?php echo $data_item[0]['block_by']; ?></td>
                                <td><?php echo $data_item[0]['code_detail']; ?></td>
                                <!--<td><?php echo $data_item[0]['re_enable_time']; ?></td>-->
                                <td><?php echo $data_item[0]['asr']; ?></td>
                                <td><?php echo $data_item[0]['abr']; ?></td>
                                <td><?php echo $data_item[0]['acd']; ?></td>
                                <td><?php echo $data_item[0]['pdd']; ?></td>
                                <td><?php echo $data_item[0]['margin']; ?></td>
                            </tr>
    <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
<?php } ?>
        </div>
    </div>