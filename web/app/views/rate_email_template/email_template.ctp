<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_email_template/email_template">
        <?php __('Template') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_email_template/email_template">
        <?php __('Rate Email Template') ?></a></li>
</ul>
<?php $data = $this->data;?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Email Template') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>rate_email_template/add_template"><i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
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
            <?php if (!count($data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('name', __('Template Name', true)) ?></th>
                            <th><?php __('From Email') ?></th>
                            <th><?php __('Email CC') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $data_item)
                        {
                            ?>
                            <tr>
                                <td><a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>rate_email_template/add_template/<?php echo base64_encode($data_item['RateEmailTemplate']['id']) ?>" ><?php echo $data_item['RateEmailTemplate']['name'] ?></a></td>
                                <td><?php echo isset($mail_sender[$data_item['RateEmailTemplate']['email_from']]) ? $mail_sender[$data_item['RateEmailTemplate']['email_from']] : $data_item['RateEmailTemplate']['email_from']; ?></td>
                                <td><?php echo $data_item['RateEmailTemplate']['email_cc'] ?></td>
                                <td>
                                    <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>rate_email_template/add_template/<?php echo base64_encode($data_item['RateEmailTemplate']['id']) ?>" >
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>rate_email_template/delete_template/<?php echo base64_encode($data_item['RateEmailTemplate']['id']) ?>'>
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>