<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>carrier_template">
        <?php echo __('Template', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>carrier_template">
        <?php __('Carrier Template') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Carrier Template') ?></h4>
</div>
<div class="separator bottom"></div>
<?php if($_SESSION['role_menu']['Template']['carrier_template']['model_w']){ ?>
    <div class="buttons pull-right newpadding">
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>carrier_template/add/">
            <i></i>
            <?php __('Create New'); ?>
        </a>
    </div>
<?php } ?>

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
                    <th><?php echo $appCommon->show_order('template_name', __('Template Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                    <th><?php echo $appCommon->show_order('create_by', __('Created By', true)) ?></th>
                    <th><?php echo $appCommon->show_order('update_on', __('Last Updated', true)) ?></th>
                    <th><?php echo $appCommon->show_order('used_by', __('Used By', true)) ?></th>
                    <th><?php __('Action'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['CarrierTemplate']['template_name']; ?></td>
                        <td><?php echo $item['CarrierTemplate']['create_on']; ?></td>
                        <td><?php echo $item['CarrierTemplate']['create_by']; ?></td>
                        <td><?php echo $item['CarrierTemplate']['update_on']; ?></td>
                        <td>
                            <?php if($item[0]['used_by']): ?>
                                <a href="#myModal_template_list" data-toggle="modal" class="myModal_template_a" template_id="<?php echo $item['CarrierTemplate']['id']; ?>">
                                    <?php echo $item[0]['used_by']; ?>
                                </a>
                            <?php else: ?>
                                <?php echo $item[0]['used_by']; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                        <?php if($_SESSION['role_menu']['Template']['carrier_template']['model_w']): ?>
                            <?php if($item[0]['used_by']): ?>
                                <a title="<?php __('Re-apply') ?>" onclick="return myconfirm('<?php __('sure to re-apply') ?>', this)" href="<?php echo $this->webroot; ?>carrier_template/reapply/<?php echo base64_encode($item['CarrierTemplate']['id']) ?>" >
                                    <i class="icon-mail-reply-all"></i>
                                </a>
                            <?php endif; ?>
                            <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>carrier_template/edit/<?php echo base64_encode($item['CarrierTemplate']['id']) ?>" >
                                <i class="icon-edit"></i>
                            </a>
                            <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>carrier_template/delete/<?php echo base64_encode($item['CarrierTemplate']['id']) ?>'>
                                <i class="icon-remove"></i>
                            </a>

                        <?php endif; ?>
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

<div id="myModal_template_list" style="width: 800px;margin-left: -350px" class="modal hide">
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
            $("#myModal_template_list").find('.modal-body').load("<?php echo $this->webroot; ?>carrier_template/ajax_get_used/"+template_id);
        });
    });
</script>
