<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Template', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $page_name; ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo $page_name; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>template/add_resource/<?php echo $type; ?>">
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
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php echo $appCommon->show_order('name', __('Template Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_by', __('Create By', true)) ?></th>
                    <th><?php echo $appCommon->show_order('update_on', __('Last Update', true)) ?></th>
                    <th><?php echo $appCommon->show_order('used_by', __('Used By', true)) ?></th>
                    <th><?php __('Action'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['ResourceTemplate']['name']; ?></td>
                        <td><?php echo $item['ResourceTemplate']['create_on']; ?></td>
                        <td><?php echo $item['ResourceTemplate']['create_by']; ?></td>
                        <td><?php echo $item['ResourceTemplate']['update_on']; ?></td>
                        <td>
                            <?php if($item[0]['used_by']): ?>
                                <a href="#myModal_template_list" data-toggle="modal" class="myModal_template_a" template_id="<?php echo $item['ResourceTemplate']['resource_template_id']; ?>">
                                    <?php echo $item[0]['used_by']; ?>
                                </a>
                            <?php else: ?>
                                <?php echo $item[0]['used_by']; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>template/add_resource/<?php echo $type; ?>/<?php echo base64_encode($item['ResourceTemplate']['resource_template_id']) ?>" >
                                <i class="icon-edit"></i>
                            </a>
                            <?php if($item[0]['used_by']): ?>
                                <a title="<?php __('Re-apply') ?>" onclick="return myconfirm('<?php __('sure to re-apply') ?>', this)" href="<?php echo $this->webroot; ?>template/reapply_resource/<?php echo base64_encode($item['ResourceTemplate']['resource_template_id']) ?>/<?php echo $type; ?>" >
                                    <i class="icon-mail-reply-all"></i>
                                </a>
                            <?php endif; ?>
                            <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>template/delete_template/<?php echo base64_encode($item['ResourceTemplate']['resource_template_id']) ?>/<?php echo $type; ?>'>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="myModal_template_list" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Used List'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(".myModal_template_a").click(function(){
            var template_id = $(this).attr('template_id');
            $("#myModal_template_list").find('.modal-body').load("<?php echo $this->webroot; ?>template/ajax_get_resource_used/"+template_id+"/1");
        });
    });
</script>