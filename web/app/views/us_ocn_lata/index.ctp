<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery_002.js"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>us_ocn_lata/index">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>us_ocn_lata/index">
        <?php echo __('US OCN/LATA') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('US OCN/LATA') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Switch']['us_ocn_lata']['model_w'])
    {
        ?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> <?php __('Create New') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove"onclick="deleteAll('<?php echo $this->webroot ?>us_ocn_lata/del_all');" href="javascript:void(0)" rel="popup">
            <i></i> <?php __('Delete All') ?>
        </a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('us_ocn_lata_table', '<?php echo $this->webroot ?>us_ocn_lata/del_selected', 'us ocn lata');" href="javascript:void(0)" rel="popup">
            <i></i> <?php echo __('Delete Selected', true); ?>
        </a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>us_ocn_lata/index" class="glyphicons list">
                        <i></i> <?php echo __('List', true); ?>
                    </a>
                </li>
                <?php
                if ($_SESSION['role_menu']['Switch']['us_ocn_lata']['model_x'])
                {
                    ?>
                    <li>
                        <a href="<?php echo $this->webroot ?>uploads/us_ocn_lata"  class="glyphicons upload">
                            <i></i> <?php echo __('Import', true); ?>
                        </a>
                    </li> 
                    <li>
                        <a href="<?php echo $this->webroot ?>down/us_ocn_lata"  class="glyphicons download">
                            <i></i> <?php echo __('Export', true); ?>
                        </a>
                    </li>  
                <?php } ?> 
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" name="search" value="<?php echo $search; ?>" class="in-search input in-text">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button class="btn query_btn" name="submit"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="widget-body">
                <?php
                $data = $p->getDataArray();
                ?>
                <?php
                if (count($data) == 0)
                {
                    ?>
                    <br />
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                        <thead>
                            <tr>
                                <th><input id="selectAll"  type="checkbox"  value=""/></th>
                                <!-- <th><?php echo $appCommon->show_order('ocn', __('OCN', true)) ?></th> -->
                                <!-- <th><?php echo $appCommon->show_order('lata', __('LATA', true)) ?></th> -->
                                <th><?php echo $appCommon->show_order('npa', __('Term DNIS', true)) ?></th>
                                <th><?php echo $appCommon->show_order('nxx', __('NXX', true)) ?></th>
                                <th><?php echo $appCommon->show_order('a_block', __('A-BLOCK', true)) ?></th>
                                <th><?php echo $appCommon->show_order('effective_time', __('Effective Time', true)) ?></th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                    <?php
                }
                else
                {
                    ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="us_ocn_lata_table">
                        <thead>
                            <tr>
                                <th><input id="selectAll"  type="checkbox"  value=""/></th>
                                <!-- <th><?php echo $appCommon->show_order('ocn', __('OCN', true)) ?></th> -->
                                <!-- <th><?php echo $appCommon->show_order('lata', __('LATA', true)) ?></th> -->
                                <th><?php echo $appCommon->show_order('npa', __('NPA', true)) ?></th>
                                <th><?php echo $appCommon->show_order('nxx', __('NXX', true)) ?></th>
                                <th><?php echo $appCommon->show_order('a_block', __('A-BLOCK', true)) ?></th>
                                <th><?php echo $appCommon->show_order('effective_time', __('Effective Time', true)) ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><input type="checkbox" class="check_items" value="<?php echo $item[0]['id']; ?>" /></td>
                                    <!-- <td><?php echo $item[0]['ocn']; ?></td> -->
                                    <!-- <td><?php echo $item[0]['lata']; ?></td> -->
                                    <td><?php echo $item[0]['npa']; ?></td>
                                    <td><?php echo $item[0]['nxx']; ?></td>
                                    <td><?php echo $item[0]['a_block']; ?></td>
                                    <td><?php echo $item[0]['effective_time']; ?></td>
                                    <td>
                                        <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item[0]['id']; ?>" >
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a title="<?php __('Delete') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" href='<?php echo $this->webroot; ?>us_ocn_lata/delete_item/<?php echo base64_encode($item['0']['id']) ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#selectAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        $('a.edit_item').click(function() {
            $(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>us_ocn_lata/item_edit_panel/' + $(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>us_ocn_lata/item_edit_panel/' + $(this).attr('control'),
                saveType: 'edit',
//                onsubmit: function() {
//                    return 
//                }
            });
        });

        $('a#add').click(function() {
            $(".msg").hide();
            $("table.list").show();
            $("table.list tbody").trAdd({
                action: '<?php echo $this->webroot ?>us_ocn_lata/item_edit_panel/',
                ajax: '<?php echo $this->webroot ?>us_ocn_lata/item_edit_panel/',
                saveType: 'add',
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
//                onsubmit: function() {
//                    return 
//                }
            });
        });


<?php if (!count($data) && !isset($_GET['search'])): ?>
            $("#add").click();
<?php endif; ?>
    });

</script>