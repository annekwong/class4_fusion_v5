<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>template/rate_upload_template">
        <?php echo __('Template', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>template/rate_upload_template">
        <?php echo $this->pageTitle; ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>template/add_rate_upload_template">
        <i></i>
        <?php __('Create New'); ?>
    </a>
</div>
<div class="separator"></div>
<div class="innerLR">
    <div class="widget widget-heading-simple widget-body-white">
        <div class="filter-bar">
            <form action="" method="get">
                <div>
                    <label><?php __('Template Name') ?>:</label>
                    <input type="text" name="template_name" value="<?php echo $appCommon->_get('template_name'); ?>" />
                </div>
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
            </form>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if(empty($this->data)): ?>
                <h2 class="msg center"><br /><?php __('No data found'); ?></h2>
            <?php else: ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php echo $appCommon->show_order('name', __('Template Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_by', __('Create By', true)) ?></th>
                    <th><?php echo $appCommon->show_order('update_on', __('Last Update', true)) ?></th>
                    <th><?php __('Action'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['RateUploadTemplate']['name']; ?></td>
                        <td><?php echo $item['RateUploadTemplate']['create_on']; ?></td>
                        <td><?php echo $item['RateUploadTemplate']['create_by']; ?></td>
                        <td><?php echo $item['RateUploadTemplate']['update_on']; ?></td>
                        <td>
                            <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>template/add_rate_upload_template/<?php echo base64_encode($item['RateUploadTemplate']['id']) ?>" >
                                <i class="icon-edit"></i>
                            </a>
                            <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>template/delete_rate_upload_template/<?php echo base64_encode($item['RateUploadTemplate']['id']) ?>'>
                                <i class="icon-remove"></i>
                            </a>
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
            <div class="clearfix"></div>
            <?php endif;?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
    });
</script>