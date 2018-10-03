<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Ingress DID Repository', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Vendor DID Repository', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="newpadding">
<div class="buttons pull-right">

        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" 
           id="add" title="<?php echo __('creataction')?>"  href="###" style="margin-bottom: 10px;">
            <i></i>
            Create New 
        </a>
        <a class="list-export btn btn-primary btn-icon glyphicons upload" style="margin-bottom: 10px;" title="<?php echo __('Upload') ?>"  href="<?php echo $this->webroot; ?>did/did_reposs/upload"><?php echo __('Upload') ?></a>
        <form id="export_form" method="post" style="margin-bottom: 10px;">
            <input type="hidden" name="export_csv" value="1">
            <a class="list-export btn btn-primary btn-icon glyphicons file_export" id="export_csv">
                <i></i><?php __('Export'); ?>
            </a>
        </form>
        <a rel="popup" id="delete_selected" class="link_btn btn btn-primary btn-icon glyphicons remove" href="###">
            Delete Selected
        </a>
        <a  class="link_btn btn btn-primary btn-icon glyphicons remove" href="<?php echo $this->webroot ?>did/did_reposs/delete_uploaded">
            Delete Uploaded
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
                        <label>Search:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn">Query</button>
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
                <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i>Advance</h3></div>
                
                    <form action="" method="get" id="search_panel"  >
                        <div class="filter-bar">
                            <input type="hidden" name="advsearch" class="input in-hidden">
                            <input type="hidden" id="is_export" name="is_export" value="0">

                            <div>
                                <label><?php echo __('Vendor', true); ?>:</label>
                                <select name="ingress_id">
                                    <option value="">All</option>
                                    <?php foreach ($ingresses as $key => $ingress){ ?>
                                        <option <?php if (isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $ingress ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Client', true); ?>:</label>
                                <select name="egress_id">
                                    <option value="">All</option>
                                    <?php foreach ($egresses as $key => $egress){ ?>
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
                                    <option value="" <?php echo $common->set_get_select('show', '', true) ?>>All</option>
                                    <option value="1" <?php echo $common->set_get_select('show', 1) ?>>Assigned</option>
                                    <option value="2" <?php echo $common->set_get_select('show', 2) ?>>Unassigned</option>
                                </select>
                            </div>
                            <!-- // Filter END -->

                            <!-- Filter -->
                            <div>
                                <button name="submit" class="btn query_btn">Query</button>
                            </div>
                            <!-- // Filter END -->

                        </div>
                    </form>
                </div>
                <?php
                $data = $p->getDataArray();
                ?>
                <?php if (count($data) == 0) { ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <table class="list" style="display:none;">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>DID</th>
                                <th>DID Vendor</th>
                                <th>DID Client</th>
                                <th>Created Time</th>
                                <th>Assigned Time</th>
                                <th>Country</th>
                                <th>Rate Center</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                    <div class="clearfix"></div>
                    <fieldset>
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>DID</th>
                                    <th>DID Vendor</th>
                                    <th>DID Client</th>
                                    <th>Created Time</th>
                                    <th>Assigned Time</th>
                                    <th>Country</th>
                                    <th>Rate Center</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($this->data as $item) { ?>
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
                                        <td><?php echo $item['DidRepos']['rate_center']; ?></td>
                                        <td><?php echo $item['DidRepos']['state']; ?></td>
                                        <td><?php echo $item['DidRepos']['city']; ?></td>
                                        <td>
                                            <?php if ($item['DidRepos']['status'] == 0): ?>
                                                <a href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/1/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>"> 
                                                    <img src="<?php echo $this->webroot ?>images/flag-0.png" title="Active">
                                                </a>
                                            <?php elseif ($item['DidRepos']['status'] == 1): ?>
                                                <a href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/0/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>"> 
                                                    <img src="<?php echo $this->webroot ?>images/flag-1.png" title="Inactive">
                                                </a>
                                            <?php endif; ?>
                                            <a title="Edit" class="edit_item" href="###" control="<?php echo $item['DidRepos']['number'] ?>" >
                                                <i class="icon-edit"></i>
                                            </a>

                                            <a title="Delete" class="delete" href='<?php echo $this->webroot; ?>did/did_reposs/delete/<?php echo $item['DidRepos']['number'] ?>/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>'>
                                                <i class='icon-remove'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('xpage'); ?>
                            </div> 
                        </div>
                    </fieldset>
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
                                                if (confirm('Are you sure?')) {
                                                    $multi_select.each(function() {
                                                        var $this = $(this);
                                                        if ($this.is(':checked')) {
                                                            selected.push($this.val());
                                                        }
                                                    });
                                                }
                                                if (selected.length) {
                                                    $.ajax({
                                                        'url': '<?php echo $this->webroot; ?>did/did_reposs/mutiple_delete',
                                                    'type': 'POST',
                                                    'dataType': 'json',
                                                    'data': {'selecteds[]': selected},
                                                    'success': function(data) {
                                                        jGrowl_to_notyfy("The numbers you selected is deleted successfully!", {theme: 'jmsg-success'});
                                                        window.setTimeout("window.location.reload();", 3000);
                                                    }
                                                });
                                            } else {
                                                jGrowl_to_notyfy("You did not select any item!", {theme: 'jmsg-error'});
                                            }
                                        });
                                    });
</script>
