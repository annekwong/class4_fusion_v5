<style>
    #optional_col span{

    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Trunk Management') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Trunk Management') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <div class="filter-bar">

                <form  method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                   
                </form>
            </div>



            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>

            <!-- list -->
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)):
                ?>
                <?php echo $this->element('common/no_result') ?>
            <?php else: ?>
                <div style="clear:both;"></div>

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list_id">
                    <thead>
                        <tr>
                            <th><?php echo __('carrier', true); ?></th>
                            <th><?php echo __('alias', true); ?></th>			
                            <th><?php echo __('type', true); ?></th>	
                            <th><?php echo __('host', true); ?></th>
                            <th><?php echo __('tech_prefix', true); ?></th>
                            <th><?php echo __('create_time', true); ?></th>
                            <th><?php echo __('update_time', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) { ?>
                                <th><?php echo __('status', true); ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>	
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            $res = $mydata[$i];
                            ?>
                            <tr rel="tooltip" id="res_<?php echo array_keys_value($res, "0.resource_id") ?>">
                                <td><?php echo array_keys_value($res, "0.client_name") ?></td>
                                <td><?php echo array_keys_value($res, "0.alias") ?></td>
                                <td><?php echo array_keys_value($res, "0.ingress") == 't' ? 'Ingress' : 'Egress'; ?></td>	
                                <td><?php echo trim(array_keys_value($res, "0.ip_port"), "{}"); ?></td>
                                <td><?php echo trim(array_keys_value($res, "0.tech_prefix"), "{}") ?></td>
                                <td><?php echo array_keys_value($res, "0.create_time") ?></td>
                                <td><?php echo array_keys_value($res, "0.update_time") ?></td>
                                <?php if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) { ?>
                                    <td>
                                        <?php if ($mydata[$i][0]['active'] == 1) { ?>
                                            <a onclick="return confirm('<?php echo __('confirmdisablegate') ?>');"  
                                               href="<?php echo $this->webroot ?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id'] ?>/view_egress" title="<?php echo __('disable') ?>">
                                                <i title="<?php echo __('wangtodisable') ?>" class="icon-check"></i>
                                            </a>
                                        <?php } else { ?>
                                            <a  onclick="return confirm('<?php echo __('confirmactivegate') ?>');"  
                                                href="<?php echo $this->webroot ?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id'] ?>/view_egress" title="<?php echo __('disable') ?>">
                                                <i title="<?php echo __('wangtoactive') ?>" class="icon-unchecked"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- list end -->	

            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            $('#optional_col input[type=checkbox]').bind('click', function() {
                if (this.checked) {
                    $("td[rel=order_list_col_" + this.value + "]").show();
                } else {
                    $("td[rel=order_list_col_" + this.value + "]").hide();
                }
                var val = this.checked ? 'true' : 'false';
                var col = this.value;
                App.Common.updateDivByAjax("<?php echo Router::url(array('plugin' => $this->plugin, 'controller' => $this->params['controller'], 'action' => 'ajax_def_col')) ?>", "none", {'action': 'browsers', 'col_name': col, 'value': val});
            });
        });
    }
    )(jQuery);
</script>
