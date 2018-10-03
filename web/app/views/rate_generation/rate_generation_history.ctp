<ul class="breadcrumb"> 
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rate Generation History') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Generation History') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_generation/rate_template"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php if (!count($data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('create_on', __('Rate Created On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('finished_time', __('Rate Finished Time', true)) ?></th>
                            <th><?php __('Created By') ?></th>
                            <th><?php __('Applied to Rate Table') ?></th>
                            <th><?php __('Status') ?></th>
                            <th><?php __('Progress') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $data_item): ?>
                            <tr>
                                <td><?php echo $data_item['RateGenerationHistory']['create_on'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistory']['finished_time'] ?></td>
                                <td><?php echo $data_item['RateGenerationHistory']['create_by'] ?></td>
                                <td>
                                    <?php if ($data_item['RateGenerationHistory']['is_applied']): ?>
                                        yes
                                    <?php else: ?>
                                        no
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($status[$data_item['RateGenerationHistory']['status']]) ? $status[$data_item['RateGenerationHistory']['status']] : ""; ?></td>
                                <td><?php echo $data_item['RateGenerationHistory']['progress'] ?></td>
                                <td>
                                    <?php if ($data_item['RateGenerationHistory']['finished_time']): ?>
                                    <a title="<?php __('Apply to Rate Table') ?>" href="<?php echo $this->webroot; ?>rate_generation/add_rate_generation_history_detail/<?php echo $this->params['pass'][0] ?>/<?php echo base64_encode($data_item['RateGenerationHistory']['id']) ?>" >
                                        <i class="icon-align-justify"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($data_item['RateGenerationHistory']['status'] == 2): ?>
                                    <a title="<?php __('View Rate') ?>" target="_blank" href="<?php echo $this->webroot; ?>rate_generation/view_rate_result/<?php echo base64_encode($data_item['RateGenerationHistory']['id']); ?>?template_id=<?php echo $this->params['pass'][0] ?>" >
                                        <i class="icon-list-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($data_item['RateGenerationHistory']['status']): ?>
                                        <a title="<?php __('Detail') ?>" href="<?php echo $this->webroot; ?>rate_generation/rate_generation_history_detail/<?php echo $this->params['pass'][0] ?>/<?php echo base64_encode($data_item['RateGenerationHistory']['id']) ?>" >
                                            <i class="icon-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>