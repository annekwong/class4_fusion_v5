<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_generation/rate_template">
        <?php __('Tool') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_generation/rate_template">
        <?php __('Rate Generation') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Generation') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>rate_generation/add_rate_template"><i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary dynamicTable">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('name', __('Rate Template', true)) ?></th>
                        <th><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                        <th><?php __('Created By') ?></th>
                        <th><?php echo $appCommon->show_order('last_generated', __('Last Generated', true)) ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $data_item)
                    {
                        ?>
                        <tr>
                            <td><a href="<?php echo $this->webroot; ?>rate_generation/add_rate_template/<?php echo base64_encode($data_item['RateGenerationTemplate']['id']) ?>" ><?php echo $data_item['RateGenerationTemplate']['name'] ?></a></td>
                            <td><?php echo $data_item['RateGenerationTemplate']['create_on'] ?></td>
                            <td><?php echo $data_item['RateGenerationTemplate']['create_by'] ?></td>
                            <td><?php echo $data_item['RateGenerationTemplate']['last_generated'] ?></td>
                            <td>
                                <a title="<?php __('Generation') ?>" onclick="return myconfirm('<?php __('Are you sure to generation?') ?>', this)" href="<?php echo $this->webroot; ?>rate_generation/do_generation/<?php echo base64_encode($data_item['RateGenerationTemplate']['id']) ?>" >
                                    <i class="icon-money"></i>
                                </a>
                                <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>rate_generation/add_rate_template/<?php echo base64_encode($data_item['RateGenerationTemplate']['id']) ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php __('View history') ?>" href="<?php echo $this->webroot; ?>rate_generation/rate_generation_history/<?php echo base64_encode($data_item['RateGenerationTemplate']['id']) ?>" >
                                    <i class="icon-list-alt"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>rate_generation/delete_rate_template/<?php echo base64_encode($data_item['RateGenerationTemplate']['id']) ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>