<ul class="breadcrumb"> 
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rate Generation History Detail') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Generation History Detail') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_generation/rate_generation_history/<?php echo $this->params['pass'][0] ?>"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php if (!count($data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
            <div class="overflow_x">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th rowspan="2"><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                            <!--th rowspan="2"><?php echo $appCommon->show_order('finished_time', __('Finished Time', true)) ?></th-->
                            <th rowspan="2"><?php __('Rate Table') ?></th>
                            <th colspan="3"><?php __('Effective Date') ?></th>
                            <th rowspan="2"><?php __('Send Email') ?></th>
                            <th rowspan="2"><?php __('End Date Method') ?></th>
                            <th rowspan="2"><?php __('End Date') ?></th>
                            <th rowspan="2"><?php __('Email Template') ?></th>
                            <th rowspan="2"><?php __('Created By') ?></th>
                        </tr>
                        <tr>
                            <th><?php __('New') ?></th>
                            <th><?php __('Increase') ?></th>
                            <th><?php __('Decrease') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $data_item)
                        {
                            ?>
                            <tr>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['create_on'] ?></td>
                                <!--td><?php echo $data_item['RateGenerationHistoryDetail']['finished_time'] ?></td-->
                                <td><?php echo $data_item['RateTable']['name'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['effective_date_new'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['effective_date_increase'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['effective_date_decrease'] ?></td>
                                <td>
                                    <?php if ($data_item['RateGenerationHistoryDetail']['is_send_mail']): ?>
                                        yes
                                    <?php else: ?>
                                        no
                                    <?php endif; ?>
                                </td>
                                 <td><?php echo isset($end_date_method[$data_item['RateGenerationHistoryDetail']['end_date_method']]) ? $end_date_method[$data_item['RateGenerationHistoryDetail']['end_date_method']] : ''; ?></td>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['end_date'] ?></td>
                                <td><?php echo $data_item['RateEmailTemplate']['name'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistoryDetail']['create_by'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>