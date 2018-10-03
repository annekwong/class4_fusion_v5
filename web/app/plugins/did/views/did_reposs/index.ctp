<style>
    table input {
        width:100px;
    }
    #container select{width: 100px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Vendor DID Repository', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Vendor DID Repository', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="newpadding">
    <div class="buttons pull-right">

        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus"
           id="add" title="<?php echo __('creataction') ?>"  href="javascript:void(0)" >
            <i></i>
            <?php __('Create New') ?>
        </a>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a class="list-export btn btn-primary btn-icon glyphicons file_import" title="<?php echo __('Upload') ?>"  href="<?php echo $this->webroot; ?>did/did_reposs/upload"><i></i><?php echo __('Upload') ?></a>
    </div>
    <div class="buttons pull-right">
        <form id="export_form" method="post" target="_blank" style="margin:0 10px 0 0">
            <input type="hidden" name="export_csv" value="1">
            <a class="list-export btn btn-primary btn-icon glyphicons file_export" id="export_csv">
                <i></i><?php __('Export'); ?>
            </a>
        </form>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a rel="popup" id="delete_selected" class="link_btn btn btn-primary btn-icon glyphicons remove" href="###">
            <i></i><?php __('Delete Selected') ?>
        </a>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a  class="link_btn btn btn-primary btn-icon glyphicons remove" href="<?php echo $this->webroot ?>did/did_reposs/delete_uploaded">
            <i></i><?php __('Delete Uploaded') ?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->

                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="###">
                            <i class="icon-long-arrow-down"></i>
                        </a>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="widget-body">
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance') ?></h3></div>

                    <form action="" method="get" id="search_panel"  >
                        <div class="filter-bar">
                            <input type="hidden" name="advsearch" class="input in-hidden">
                            <input type="hidden" id="is_export" name="is_export" value="0">

                            <div>
                                <label><?php echo __('Vendor', true); ?>:</label>
                                <select name="ingress_id">
                                    <option value=""><?php __('All') ?></option>
                                    <?php
                                    foreach ($ingresses as $key => $ingress)
                                    {
                                        ?>
                                        <option <?php if (isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $ingress ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Client', true); ?>:</label>
                                <select name="egress_id">
                                    <option value=""><?php __('All') ?></option>
                                    <?php
                                    foreach ($egresses as $key => $egress)
                                    {
                                        ?>
                                        <option <?php if (isset($_GET['egress_id']) && $_GET['egress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $egress ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Number', true); ?>:</label>
                                <td>
                                    <input type="text" name="number" value="<?php echo $common->set_get_value('number') ?>" />
                                </td>
                            </div>
                            <div>
                                <label><?php echo __('Show', true); ?>:</label>
                                <select name="show">
                                    <option value="" <?php echo $common->set_get_select('show', '', true) ?>><?php __('All') ?></option>
                                    <option value="1" <?php echo $common->set_get_select('show', 1) ?>><?php __('Assigned') ?></option>
                                    <option value="2" <?php echo $common->set_get_select('show', 2) ?>><?php __('Unassigned') ?></option>
                                </select>
                            </div>
                            <!-- // Filter END -->

                            <!-- Filter -->
                            <div>
                                <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                            </div>
                            <!-- // Filter END -->

                        </div>
                    </form>
                </div>
                <?php
                if (empty($this->data))
                {
                    ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="overflow-x: hidden;display: none;">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th><?php __('DID') ?></th>
                            <th><?php __('DID Vendor') ?></th>
                            <th><?php __('DID Client') ?></th>
                            <th><?php __('Created Time') ?></th>
                            <th><?php __('Assigned Time') ?></th>
                            <th><?php __('Country') ?></th>
                            <th><?php __('State') ?></th>
                            <th><?php __('City') ?></th>
                            <th><?php __('Action') ?></th>
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
                    <div class="clearfix"></div>
                    <div class="overflow_x">
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="overflow: auto;overflow-x: hidden">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th><?php __('DID') ?></th>
                                <th><?php __('DID Vendor') ?></th>
                                <th><?php __('DID Client') ?></th>
                                <th><?php __('Created Time') ?></th>
                                <th><?php __('Assigned Time') ?></th>
                                <th><?php __('Country') ?></th>
                                <th><?php __('State') ?></th>
                                <th><?php __('City') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            foreach ($this->data as $item)
                            {
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="multi_select" value="<?php echo $item['DidRepos']['number']; ?>">
                                    </td>
                                    <td><?php echo $item['DidRepos']['number']; ?></td>
                                    <td><?php echo $ingresses[$item['DidRepos']['ingress_id']]; ?></td>
                                    <td><?php echo $item['DidRepos']['egress_id'] ? $egresses[$item['DidRepos']['egress_id']] : ''; ?></td>
                                    <td><?php echo $item['DidRepos']['created_time']; ?></td>
                                    <td><?php echo $item['DidRepos']['updated_time']; ?></td>
                                    <td><?php echo $item['DidRepos']['country']; ?></td>
                                    <td><?php echo $item['DidRepos']['state']; ?></td>
                                    <td><?php echo $item['DidRepos']['city']; ?></td>
                                    <td>
                                        <?php if(!$item['DidRepos']['egress_id']): ?>
                                            <?php if ($item['DidRepos']['status'] == 1): ?>
                                                <a title="<?php __('Inactive') ?>" href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/0/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>">
                                                    <i class="icon-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a title="<?php __('Active') ?>" href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/1/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>">
                                                    <i class="icon-unchecked"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item['DidRepos']['number'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>

                                        <a title="<?php __('Delete') ?>" onclick="return myconfirm('Are you sure to delete the number[<?php echo $item['DidRepos']['number'] ?>] ?', this);" class="delete" href='<?php echo $this->webroot; ?>did/did_reposs/delete/<?php echo $item['DidRepos']['number'] ?>/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>'>
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
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>






<script type="text/javascript">
    jQuery(function() {


        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>",
                action: "<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>",
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function(options)
                {
                    var validate_flg = $("#trAdd").find('input[class*=validate]').validationEngine('validate');
                    if(validate_flg){
                        return false;
                    }
                    var ingress_id = $("#DidReposIngressId").val();
                    if (!ingress_id)
                    {
                        jGrowl_to_notyfy("<?php __("You need to add the vendor"); ?>", {theme: 'jmsg-error'});
                        return false;
                    }
                    var number = $('#DidReposNumber').val();
                    var is_exists = jQuery.ajaxData("<?php echo $this->webroot ?>did/did_reposs/chech_num/" + number);
                    if (is_exists.indexOf("true") != -1)
                    {
                        jGrowl_to_notyfy("The number [" + number + "] already exists!", {theme: 'jmsg-error'});
                        return false;
                    }
                    return true;
                }
            });
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function() {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                saveType: 'edit'
            });
        });

        var $selectAll = $('#selectAll');
        var $multi_select = $('.multi_select');
        var $delete_selected = $('#delete_selected');
        var $export_csv = $('#export_csv');
        var $export_form = $('#export_form');

        $selectAll.change(function() {
            $multi_select.attr('checked', $(this).attr('checked'));
        });


        $export_csv.click(function() {
            $export_form.submit();
        });


        $delete_selected.click(function() {
            var selected = new Array();
            $multi_select.each(function() {
                var $this = $(this);
                if ($this.is(':checked')) {
                    selected.push($this.val());
                }
            });
            if (!selected.length) {
                jGrowl_to_notyfy("You did not select any item!", {theme: 'jmsg-error'});
            }
            else
            {
                bootbox.confirm('Are you sure?', function(result) {
                    if (result)
                    {
                        $.ajax({
                            'url': '<?php echo $this->webroot; ?>did/did_reposs/mutiple_delete',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {'selecteds[]': selected},
                            'success': function(data) {
                                jGrowl_to_notyfy("The numbers you selected is deleted successfully!", {theme: 'jmsg-success'});
                                $multi_select.each(function () {
                                    var $this = $(this);
                                    if ($this.is(':checked')) {
                                        $this.closest('tr').remove();
                                    }
                                });
//                                window.setTimeout("window.location.reload();", 3000);
                            }
                        });
                    }
                })
            }
        });
        <?php if (!count($this->data) && !isset($_GET['search'])): ?>
        $("#add").click();
        <?php endif; ?>
    });
</script>
