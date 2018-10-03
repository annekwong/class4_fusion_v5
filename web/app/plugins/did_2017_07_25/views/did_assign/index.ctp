<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client DID Assignment') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Client DID Assignment') ?><?php if (isset($vendor_name)) echo "[{$vendor_name}]"; ?></h4>
    
</div>
<div class="separator bottom"></div>
<?php if ($_SESSION['role_menu']['Origination']['did_reposs']['model_w']) { ?>
        <div class="buttons pull-right newpadding">
            <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>did/did_assign/create"><i></i>
                <?php __('Create New')?>
            </a>
        </div>
    <?php } ?>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="myform1">
                    <div>
                        <label><?php __('Name')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search')?>" value="Search" name="search">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit"><?php __('Query')?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>



            <div id="container">
                <fieldset style="margin-left: 1px; width: 100%; display: <?php echo isset($url_get['advsearch']) ? 'block' : 'none'; ?>;" id="advsearch" class="title-block">
                    <form method="get" id="search_panel">
                        <input type="hidden" name="advsearch" class="input in-hidden">
                        <table>
                            <tr>
                                <td><?php __('Vendor')?></td>
                                <td>
                                    <select name="ingress_id">
                                        <option value=""><?php __('All')?></option>
                                        <?php foreach ($ingresses as $key => $ingress): ?>
                                            <option <?php if (isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $ingress ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?php __('Client')?></td>
                                <td>
                                    <select name="egress_id">
                                        <option value=""><?php __('All')?></option>
                                        <?php foreach ($egresses as $key => $egress): ?>
                                            <option <?php if (isset($_GET['egress_id']) && $_GET['egress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $egress ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?php __('Number')?></td>
                                <td>
                                    <input type="text" name="number" value="<?php echo $common->set_get_value('number') ?>" />
                                </td>
                                <td>
                                    <input type="submit" value="<?php __('Search')?>" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </fieldset>
                <?php
                if (empty($this->data)):
                    ?>
                <div class="msg center"><h3><?php echo __('no_data_found', true); ?></h3></div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                        <thead>
                            <tr>
                                <th><?php __('DID')?></th>
                                <th><?php __('DID Vendor')?></th>
                                <th><?php __('DID Client')?></th>
                                <th><?php __('Created Time')?></th>
                                <th><?php __('Assigned Time')?></th>
                                <th><?php __('Country')?></th>
                                <th><?php __('Rate Center')?></th>
                                <th><?php __('State')?></th>
                                <th><?php __('City')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php __('DID')?></th>
                                <th><?php __('DID Vendor')?></th>
                                <th><?php __('DID Client')?></th>
                                <th><?php __('Created Time')?></th>
                                <th><?php __('Assigned Time')?></th>
                                <th><?php __('Country')?></th>
                                <th><?php __('Rate Center')?></th>
                                <th><?php __('State')?></th>
                                <th><?php __('City')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['DidAssign']['number']; ?></td>
                                    <td><?php echo $ingresses[$item['DidAssign']['ingress_id']]; ?></td>
                                    <td><?php echo $egresses[$item['DidAssign']['egress_id']]; ?></td>
                                    <td><?php echo $item['DidAssign']['created_time']; ?></td>
                                    <td><?php echo $item['DidAssign']['assigned_time']; ?></td>
                                    <td><?php echo $item['DidRepos']['country']; ?></td>
                                    <td><?php echo $item['DidRepos']['rate_center']; ?></td>
                                    <td><?php echo $item['DidRepos']['state']; ?></td>
                                    <td><?php echo $item['DidRepos']['city']; ?></td>
                                    <td>
                                        <a title="<?php __('Edit')?>" class="edit_item" href="###" control="<?php echo $item['DidAssign']['number'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>
                                        <!--
                                        <?php if ($item['DidAssign']['status'] == 0): ?>
                                            <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/1"> 
                                                <i class="icon-check-empty" title="Active"></i>
                                            </a>
                                        <?php elseif ($item['DidAssign']['status'] == 1): ?>
                                            <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/0"> 
                                                <i class="icon-check" title="<?php __('Inactive')?>"></i>
                                            </a>
                                        <?php endif; ?>

                                        -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
</div>  

            <script>
                $(function() {
                    jQuery('a.edit_item').click(function() {
                        jQuery(this).parent().parent().trAdd({
                            action: '<?php echo $this->webroot ?>did/did_assign/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                            ajax: '<?php echo $this->webroot ?>did/did_assign/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                            saveType: 'edit'
                        });
                    });
                });
            </script>