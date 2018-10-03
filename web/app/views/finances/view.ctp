<?php $action_type = empty($_GET['action_type']) ? '2' : $_GET['action_type']; ?>



<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Finance') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Finance') ?></h4>
    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">
    <?php
    $action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
    $w = isset($action['writable']) ? $action['writable'] : '';
    ?>
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if ($action_type == 2) echo "class='active'"; ?>><a  class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>finances/view?action_type=2"><i></i><?php echo __('Wire In', true); ?> </a></li>
                <li <?php if ($action_type == 1) echo "class='active'"; ?> ><a  class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>finances/view?action_type=1">
                        <i></i><?php echo __('Wire Out', true); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Output') ?>:</label>
                        <select onchange="set_out_put1(this)" name="out_put" style="width:100px;" class="in-select select" id="output">
                            <option value="web"><?php __('Web') ?></option>
                            <option value="csv"><?php __('Excel CSV') ?></option>
                            <option value="xls"><?php __('Excel XLS') ?></option>
                        </select>
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
            <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance') ?></h3></div>
                <div class="widget-body">
                    <form action="" method="get" id="search_panel"  >
                        <div class="filter-bar">
                            <input type="hidden" name="advsearch" class="input in-hidden">
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Transaction Date', true); ?>:</label>
                                <input type="text" readonly onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo!empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''; ?>">
                                --
                                <input type="text" readonly onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" id="end_date" style="width:120px;" name="end_date" class="wdate input in-text" value="<?php echo!empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''; ?>">
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('status', true); ?>:</label>
                                <select id="tran_status" name="tran_status">
                                    <option value=""><?php echo __('select') ?></option>
                                    <option value="1" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 1) ? 'selected' : ''; ?>><?php __('Waiting') ?></option>
                                    <option value="2" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 2) ? 'selected' : ''; ?>><?php __('Completed') ?></option>
                                    <?php if ($action_type == 1): ?>
                                        <option value="4" <?php echo (isset($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 4) ? 'selected' : ''; ?>><?php __('In Process') ?></option>
                                        <option value="3" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 3) ? 'selected' : ''; ?>><?php __('Refused') ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->

                            <!-- // Filter END -->

                            <!-- Filter -->
                            <div>
                                <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                            </div>
                            <!-- // Filter END -->

                        </div>

                    </form>
                </div>
            </div>


            <!-- <div id="toppage"></div>-->
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)) {
                ?>
                <div class="msg center">
                    <br />
                    <h2><?php  echo __('no_data_found') ?>.</h2>
                </div>
            <?php } else {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">
                    <thead>
                        <tr>
                            <th><?php echo __('Serail Number', true); ?></th>
                            <th><?php echo $appCommon->show_order('action_type', __('Type', true)); ?></th>
                            <th><?php echo $appCommon->show_order('action_method', __('Method', true)); ?></th>
                            <th><?php echo $appCommon->show_order('amount', __('Amount', true)); ?></th>
                            <th><?php echo $appCommon->show_order('action_fee', __('Fee', true)); ?></th>
                            <th><?php echo $appCommon->show_order('action_time', __('Transaction Date', true)); ?></th>
                            <th><?php echo $appCommon->show_order('status', __('Status', true)); ?></th>
                            <th><?php echo $appCommon->show_order('complete_time', __('Completed Date', true)); ?></th>
                            <th><?php echo $appCommon->show_order('name', __('Carrier', true)); ?></th>
                            <?php if ($action_type == 2): ?>
                                <th><?php echo $appCommon->show_order('payer_company', __('Company', true)); ?></th>
                                <th><?php echo $appCommon->show_order('payer_email', __('Email', true)); ?></th>
                            <?php endif; ?>
                            <?php if ($_SESSION['role_menu']['Finance']['finances']['model_w']) { ?> <th><?php __('action') ?></th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => '<font style="color:#FF6D06;">Complete</font>', 3 => '<font style="color:#FF0000;">Refused</font>', 4 => '<font style="color:#FF0000;">In Process</font>');
                            ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['action_number']; ?> </td>
                                <td>
                                    <?php echo ($mydata[$i][0]['action_type'] == 1) ? 'Wire Out' : 'Wire In'; ?>
                                </td>
                                <td><?php echo ($mydata[$i][0]['action_method'] == 1) ? 'Bank Wire' : 'Paypal'; ?></td>
                                <td>$<?php echo number_format($mydata[$i][0]['amount'], 2); ?></td>
                                <td>$<?php echo number_format($mydata[$i][0]['action_fee'], 2); ?></td>
                                <td><?php echo $mydata[$i][0]['action_time']; ?></td>
                                <td><?php echo $status_val[$mydata[$i][0]['status']]; ?></td>
                                <td><?php echo $mydata[$i][0]['complete_time']; ?></td>
                                <td><?php echo $mydata[$i][0]['name']; ?></td>
                                <?php if ($action_type == 2): ?>
                                    <td><?php echo $mydata[$i][0]['payer_company']; ?></td>
                                    <td><?php echo $mydata[$i][0]['payer_email']; ?></td>
                                <?php endif; ?>
                                <?php if ($_SESSION['role_menu']['Finance']['finances']['model_w']) {
                                    ?><td>
                                        <?php if (($mydata[$i][0]['action_type'] == 1 && $mydata[$i][0]['status'] < 3) || $mydata[$i][0]['action_type'] == 2 || ($mydata[$i][0]['action_type'] == 1 && $mydata[$i][0]['status'] == 4)) { ?>
                                            <a href="<?php echo $this->webroot; ?>finances/notify_carrier/<?php echo $mydata[$i][0]['id']; ?>">
                                                <i class="icon-envelope"></i>
                                            </a>
                                            <a href="<?php echo $this->webroot ?>finances/edit_finance/<?php echo $mydata[$i][0]['id']; ?>";
                                               title="<?php echo __('edit') ?>"> <i class="icon-edit"></i> </a>
                                           <?php } ?>
                                    </td>
                                <?php }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>
        </div>
    </div>
</div>

        <script>
            function set_out_put1(obj) {
                $("#output1").attr('value', $(obj).val());
            }
        </script>