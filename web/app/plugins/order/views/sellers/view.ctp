<style>
    #optional_col span{

    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Direct Seller Enrollment') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Direct Seller Enrollment') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <!--
                    <div align="left">
                    <table>
                    <tr>
                    <td style="text-align:center; padding-bottom:10px;">
                    <form action="" method="GET">
            <?php //echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
                    </form>
                    </td>			
                            </tr>
                            </table>
                    </div>
            -->
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
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="margin-top:5px;">
                    <thead>
                        <tr>
                            <th><?php echo __('Request Date', true); ?></th>
                            <th><?php echo __('Company Name', true); ?></th>			
                            <th><?php echo __('Account Name', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Exchange Manage']['sellers']['model_w']) { ?>
                                <th><?php echo __('action', true); ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>	
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            $res = $mydata[$i];
                            ?>
                            <tr rel="tooltip" id="res_<?php echo $mydata[$i][0]["dse_id"]; ?>">
                                <td><?php echo $mydata[$i][0]["request_time"]; ?></td>
                                <td><?php echo $mydata[$i][0]["company_name"]; ?></td>
                                <td><?php echo $mydata[$i][0]["name"]; ?></td>
                                <?php if ($_SESSION['role_menu']['Exchange Manage']['sellers']['model_w']) { ?>
                                    <td>
                                        <?php if ($mydata[$i][0]['action'] == 1) { ?>
                                            <a onclick="return confirm('Are You Sure Disapprove This Direct Seller Enrollment');"  
                                               href="<?php echo $this->webroot ?>order/sellers/dis_able/<?php echo $mydata[$i][0]['dse_id'] ?>" title="<?php echo __('disable') ?>">
                                                <i title="<?php echo __('wangtodisable') ?>" class="icon-check"></i>
                                            </a>
                                        <?php } else { ?>
                                            <a  onclick="return confirm('Are You Sure Approve This Direct Seller Enrollment');"  
                                                href="<?php echo $this->webroot ?>order/sellers/active/<?php echo $mydata[$i][0]['dse_id'] ?>" title="<?php echo __('disable') ?>">
                                                <i title="<?php echo __('wangtoactive') ?>" class="icon-unchecked"></i></a>
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
