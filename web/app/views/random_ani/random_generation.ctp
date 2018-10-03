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
    <?php
    if ($_SESSION['role_menu']['Switch']['random_ani']['model_w'])
    {
        ?>
        <a  class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)"><i></i> <?php echo __('Create New') ?></a>
        <?php if (count($this->data) > 0) : ?>
            <a  class="btn btn-primary btn-icon glyphicons remove" relm="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>random_ani/delete_all_generation/<?php echo $random_table['RandomAniTable']['id']; ?>');"><i></i> <?php echo __('Delete All') ?></a>
            <a class="btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteSelected('random_ani_tbody', '<?php echo $this->webroot ?>random_ani/delete_selected_generation/<?php echo $random_table['RandomAniTable']['id']; ?>', 'Random ANI GROUP');"><i></i> <?php echo __('Delete Selected') ?></a>
        <?php endif; ?>
    <?php } ?>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>random_ani/random_table"><i></i>Back</a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li class="active">
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
                <li>
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
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
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
                            <th><?php echo $appCommon->show_order('RandomAniGeneration.ani_number', __('ANI', true)) ?></th>
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
                            <th><?php echo $appCommon->show_order('RandomAniGeneration.ani_number', __('ANI', true)) ?></th>
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
                                        <input type="checkbox" class="select" value="<?php echo $item['RandomAniGeneration']['id']; ?>" />
                                    </td>
                                <?php endif; ?>
                                <td><?php echo $item['RandomAniGeneration']['ani_number']; ?></td>
                                <td>
                                    <a title="<?php __('Edit') ?>" href="javascript:void(0)" random_table_id ="<?php echo $random_table['RandomAniTable']['id']; ?>" pri_id ="<?php echo $item['RandomAniGeneration']['id']; ?>" id='edit'>  
                                        <i class="icon-edit"></i>
                                    </a> 
                                    <a  title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this)"  href="<?php echo $this->webroot; ?>random_ani/delete_generation/<?php echo base64_encode($item['RandomAniGeneration']['random_table_id']); ?>/<?php echo base64_encode($item['RandomAniGeneration']['id']); ?>">
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
            <?php endif; ?>

            <div class="clearfix"></div>



        </div>
    </div>
</div>

<script type="text/javascript">

    $(function() {
        
        $("#selectAll").click(function(){
            var checked = $(this).attr('checked');
            if(!checked)
            {
                checked = false;
            }
            $(".select").attr('checked',checked);
        });
        jQuery('a[id=edit]').click(function() {
            var id = jQuery(this).attr('pri_id');
            var random_table_id = $(this).attr('random_table_id');
            jQuery(this).parent().parent().trAdd({
                action: "<?php echo $this->webroot ?>random_ani/js_save_generation/" + random_table_id + "/" + id,
                ajax: "<?php echo $this->webroot ?>random_ani/js_save_generation/" + random_table_id + "/" + id,
                saveType: 'edit',
                onsubmit: function() {
                    var ani_number = $("#RandomAniGenerationAniNumber").val();
                    if (/\D+/.test(ani_number)){
                        jGrowl_to_notyfy('<?php __('ANI is not number'); ?>',{'theme':'jmsg-error'});
                        return false;
                    }
                    return true;
                }
            });
        });

        jQuery('a[id=add]').click(function() {
            $('.msg').hide();
            $('table.list').show();
            var random_table_id = "<?php echo isset($random_table['RandomAniTable']['id']) ? $random_table['RandomAniTable']['id'] : ""; ?>";
            jQuery('table.list tbody').trAdd({
                action: "<?php echo $this->webroot ?>random_ani/js_save_generation/" + random_table_id,
                ajax: "<?php echo $this->webroot ?>random_ani/js_save_generation/" + random_table_id,
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function() {
                    var ani_number = $("#RandomAniGenerationAniNumber").val();
                    if (/\D+/.test(ani_number)){
                        jGrowl_to_notyfy('<?php __('ANI is not number'); ?>',{'theme':'jmsg-error'});
                        return false;
                    }
                    return true;
                }
            });
        });
    });

</script>



