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
    <li><a href="<?php echo $this->webroot ?>random_ani/random_table">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>random_ani/random_table">
        <?php echo __('Random ANI Group') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Random ANI Group') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Switch']['random_ani']['model_w'])
    {
        ?>
        <a  class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)"><i></i> <?php echo __('Create New') ?></a>
        <?php if (count($this->data) > 0) : ?>
            <a  class="btn btn-primary btn-icon glyphicons remove" relm="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>random_ani/delete_alltable');"><i></i> <?php echo __('Delete All') ?></a>
            <a class="btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteSelected('random_ani_tbody', '<?php echo $this->webroot ?>random_ani/delete_selectedtable', 'Random ANI GROUP');"><i></i> <?php echo __('Delete Selected') ?></a>
        <?php endif; ?>
    <?php } ?>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2>
                        <?php echo __('no data found', true); ?>
                    </h2>
                </div>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                        <tr>
                            <?php
                            if ($_SESSION['role_menu']['Switch']['random_ani']['model_w'])
                            {
                                ?>
                                <th class="footable-first-column expand" data-class="expand"><?php
                                    if ($_SESSION['login_type'] == '1')
                                    {
                                        ?>
                                        <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'random_ani_tbody');" value=""/>
                                    <?php } ?></th>
                            <?php } ?>
                            <th><?php echo $appCommon->show_order('RandomAniTable.name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('RandomAniTable.create_time', __('Create Time', true)) ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="random_ani_tbody">
                    </tbody>
                </table>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <?php
                            if ($_SESSION['role_menu']['Switch']['random_ani']['model_w'])
                            {
                                ?>
                                <th class="footable-first-column expand" data-class="expand"><?php
                                    if ($_SESSION['login_type'] == '1')
                                    {
                                        ?>
                                        <input id="selectAll" class="select" type="checkbox" value=""/>
                                    <?php } ?></th>
                            <?php } ?>
                            <th><?php echo $appCommon->show_order('RandomAniTable.name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('RandomAniTable.create_time', __('Create Time', true)) ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="random_ani_tbody">
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <?php
                                if ($_SESSION['role_menu']['Switch']['random_ani']['model_w']):
                                    ?>
                                    <td>
                                        <input type="checkbox" class="select" value="<?php echo $item['RandomAniTable']['id']; ?>" >
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <a href="<?php echo $this->webroot; ?>random_ani/random_generation/<?php echo base64_encode($item['RandomAniTable']['id']); ?>" title="<?php __('View Detail'); ?>">
                                        <?php echo $item['RandomAniTable']['name']; ?>
                                    </a>
                                </td>
                                <td><?php echo $item['RandomAniTable']['create_time']; ?></td>
                                <td>
                                    <a href="<?php echo $this->webroot; ?>random_ani/random_generation/<?php echo base64_encode($item['RandomAniTable']['id']); ?>" title="<?php __('View Detail'); ?>">
                                        <i class="icon-align-justify"></i>
                                    </a>
                                    <a title="<?php __('Edit') ?>" href="javascript:void(0)" pri_id ="<?php echo $item['RandomAniTable']['id']; ?>" id='edit'>  
                                        <i class="icon-edit"></i>
                                    </a> 
                                    <a  title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this)"  href="<?php echo $this->webroot; ?>random_ani/delete_table/<?php echo base64_encode($item['RandomAniTable']['id']); ?>">
                                        <i class="icon-remove"></i>
                                    </a>  
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

        $("#selectAll").click(function() {
            var checked = $(this).attr('checked');
            if (!checked)
            {
                checked = false;
            }
            $(".select").attr('checked', checked);
        });

        jQuery('a[id=edit]').click(function() {
            var id = jQuery(this).attr('pri_id');
            jQuery(this).parent().parent().trAdd({
                action: "<?php echo $this->webroot ?>random_ani/js_save_table/" + id,
                ajax: "<?php echo $this->webroot ?>random_ani/js_save_table/" + id,
                saveType: 'edit',
                onsubmit: function() {
                    return true;
                }
            });
        });

        jQuery('a[id=add]').click(function() {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                action: "<?php echo $this->webroot ?>random_ani/js_save_table/",
                ajax: "<?php echo $this->webroot ?>random_ani/js_save_table/",
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function() {
                    return true;
                }
            });
        });

<?php if (!count($this->data)): ?>
            $("#add").click();
<?php endif; ?>
    });

</script>



