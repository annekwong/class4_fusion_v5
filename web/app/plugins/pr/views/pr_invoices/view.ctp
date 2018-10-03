<style>
    .ui-resizable{border:1px solid #7FAF00}
    .filter-bar{margin:0 auto 15px;}
</style>
<?php $w = $session->read('writable');
$d = $p->getDataArray();
?>

<?php
if (!isset($this->params['pass'][0]))
{
    $this->params['pass'][0] = 0;
}
?>

<div id="cover"></div>
<div id="cover_tmp"></div>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/view">
            <?php if($_SESSION['login_type'] == 3):?>
                <?php __('Client Portal') ?>
            <?php else:?>
            <?php __('Finance') ?></a></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/view">
            <?php echo __('Invoices', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoices', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right" style="padding:0 15px 10px 0;">
    <?php
    if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
    {
        ?>
        <?php
        if ($w == true)
        {
            if ($create_type == '1' || $create_type == '3' || $create_type == '5')
            {
                ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>pr/pr_invoices/add/<?php echo $create_type; ?>"><i></i><?php __('Create New')?> </a>
                <?php
            }
        }
    }
    ?>
    <?php if(count($d)): ?>
        <?php if ($session->read('login_type') == 1): ?>
            <a href="<?php echo $this->webroot ?>pr/pr_invoices/invoice_log" id="refresh_btn" class="link_btn btn btn-primary btn-icon glyphicons notes_2">
                <i></i><?php echo __('Invoice Log', true); ?>
            </a>
        <?php endif; ?>
        <a class="list-export btn btn-primary btn-icon glyphicons file_export" id="export_excel_btn">
            <i></i><?php __('Export'); ?>
        </a>
    <?php endif; ?>
</div>
<?php //if ($session->read('login_type') == 1): ?>
<!--    <div class="buttons pull-right" style="padding-right:10px;padding-bottom: 10px;">-->
<!--        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="--><?php //echo $this->webroot ?><!--uploads/payment_invoice"  id="upload_invoice">-->
<!--            <i></i>--><?php //__('Upload Vendor Invoice'); ?>
<!--        </a>-->
<!--    </div>-->
<?php //endif; ?>
<!--<div class="buttons pull-right" style="padding-right:10px;padding-bottom: 10px;">-->
<!--    <a class="list-export btn btn-primary btn-icon glyphicons file_export" href="--><?php //echo $this->webroot ?><!--down/summary?--><?php //echo $this->params['getUrl'] ?><!--"  id="down_summary">-->
<!--        <i></i>--><?php //__('Download Summary'); ?>
<!--    </a>-->
<!--</div>-->
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if ($_SESSION['login_type'] == '1'):?>
            <div class="widget-head">

                <ul>
                    <li <?php
                    if ($create_type == '0')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/0/<?php echo $did; ?>"><i></i><?php echo __('Auto Client Invoice') ?></a></li>
                    <li <?php
                    if ($create_type == '1')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/1/<?php echo $did; ?>"><i></i><?php echo __('Manual Client Invoice') ?></a></li>
                    <!--                    <li <?php
                    if ($create_type == '2')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/2"><i></i><?php echo __('Auto Outbound Invoice') ?></a></li>
                                        <li <?php
                    if ($create_type == '3')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js group" href="<?php echo $this->webroot ?>pr/pr_invoices/view/3"><i></i><?php echo __('Vendor Invoice') ?></a></li>-->
                    <!--    <li <?php
                    if ($create_type == '4')
                    {
                        echo "class='active'";
                    }
                    ?> ><a href="<?php echo $this->webroot ?>pr/pr_invoices/view/4"><img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"><?php echo __('Sent And Received Auto-generated Invoice') ?></a></li>-->
                    <!--    <li <?php
                    if ($create_type == '5')
                    {
                        echo "class='active'";
                    }
                    ?> ><a href="<?php echo $this->webroot ?>pr/pr_invoices/view/5"><img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"><?php echo __('Sent And Received Invoice') ?></a></li>-->

                    <!--<li <?php
                    if ($create_type == '1')
                    {
                        echo "class='active'";
                    }
                    ?> ><a href="<?php echo $this->webroot ?>pr/pr_invoices/view/1"  style="width: 125px;"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> <?php echo __('Manual Invoice') ?></a></li>
                    -->
                    <!--                <li><a class="glyphicons no-js right_arrow" href="--><?php //echo $this->webroot ?><!--pr/pr_invoices/vendor_invoice"><i></i>--><?php //echo __('Vendor Invoice') ?><!--</a></li>-->
                    <!--                <li><a class="glyphicons no-js right_arrow" href="--><?php //echo $this->webroot ?><!--pr/pr_invoices/incoming_invoice"><i></i>--><?php //echo __('Old Vendor Invoice') ?><!--</a></li>-->
                </ul>



            </div>
        <?php endif; ?>
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <!-- Filter -->
                    <div>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button  class="btn query_btn" id="search_button"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                    <?php if ($_SESSION['login_type'] == '1'): ?>
                        <div class="pull-right" title="Advance">
                            <a id="advance_btn" class="btn" href="###">
                                <i class="icon-long-arrow-down"></i>
                            </a>
                        </div>
                    <?php endif;?>
                </form>
            </div>
            <div class="clearfix"></div>
            <div id="advance_panel" style="display:none;" class="widget widget-heading-simple widget-body-gray">
                <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance')?></h3></div>
                <div class="widget-body">
                    <form action="" method="get" id="search_panel"  >
                        <div class="filter-bar">
                            <input type="hidden" name="advsearch" class="input in-hidden">
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Invoice No', true); ?>:</label>
                                <input type="text" class="input in-text"  name="invoice_number" value=""  id="invoice_number">
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('State', true); ?>:</label>
                                <select id="state" name="state" class="input in-select">
                                    <option selected="selected" value=""><?php __('all')?></option>
                                    <option value="9"><?php __('Sent')?></option>
                                    <option value="-1"><?php __('Void')?></option>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Type', true); ?>:</label>
                                <select id="type" onchange="updateDirection()" name="type" class="input in-select">
                                    <option value="" selected="selected"><?php __('all')?></option>
                                    <option value="0"><?php __('Client')?></option>
                                    <option value="1"><?php __('Vendor')?></option>
                                    <option value="2"><?php __('Vendor and Client')?></option>
                                </select>
                            </div>
                            <!-- // Filter END -->

                            <div>
                                <label><?php echo __('Invoice Date', true); ?>:</label>
                                <input type="text" class="input in-text wdate input-small" name="invoice_start" id="start_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="">
                                --
                                <input type="text" class="wdate input in-text input-small" name="invoice_end"  id="end_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="">
                            </div>
                            <!-- Filter -->
                            <div>
                                <button  class="btn query_btn"><?php __('Query')?></button>
                            </div>
                            <!-- // Filter END -->
                        </div>
                        <div class="filter-bar">
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Number of Day Overdue', true); ?>:</label>
                                <select id="due_inteval_type" name="due_inteval_type" style="width:80px" class="input in-select">
                                    <option value=""><?php __('all')?></option>
                                    <option value=">=">&gt;=</option>
                                    <option value="<=">&lt;= </option>
                                </select>
                                <input type="text" class="input in-text" name="due_inteval" value="" style="width: 60px;" id="due_inteval">
                            </div>
                            <!-- // Filter END -->
                            <!-- Filter -->
                            <div>
                                <label><?php echo __('Mode', true); ?>:</label>
                                <select name="pay_mode" class="input-small">
                                    <option></option>
                                    <option value="1" <?php if (isset($_GET['pay_mode']) and $_GET['pay_mode'] == '1') echo 'selected="selected"' ?>><?php __('PrePaid')?></option>
                                    <option value="2" <?php if (isset($_GET['pay_mode']) and $_GET['pay_mode'] == '2') echo 'selected="selected"' ?>><?php __('Post-pay')?></option>
                                </select>
                            </div>
                            <!-- // Filter END -->
                            <div>
                                <label><?php echo __('Carriers', true); ?></label>
                                <select name="query[client]" class="input-small">
                                    <option value=""><?php __('All')?></option>
                                    <?php foreach ($clients as $client): ?>
                                        <option <?php if (isset($_GET['query']['client']) && $_GET['query']['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <!--div class="filter-bar">
                            <!--div>
                                <label><?php echo __('Dispute', true); ?></label>
                                <select id="disputed" name="disputed" class="input in-select input-medium">
                                    <option selected="selected" value="0"><?php __('Non-Disputed')?></option>
                                    <option value="1"><?php __('Disputed')?></option>
                                    <option value="2"><?php __('Dispute Resolved')?></option>
                                </select>
                            </div-->

                        <!--div>
                                <label><?php echo __('Amt Paid', true); ?></label>
                                <select id="paid" name="paid" class="input in-select input-small">
                                    <option selected="selected" value="0"><?php __('all')?></option>
                                    <option value="false"><?php __('No Paid')?></option>
                                    <option value="true"><?php __('Already Paid')?></option>
                                </select>
                            </div-->

                        <!--div>
                                <label><?php echo __('Amout', true); ?>:</label>
                                <select id="invoice_amount" name="invoice_amount" class="input in-select" style="width:100px;">
                                    <option <?php
                        if (isset($_GET['invoice_amount']) && 0 == $_GET['invoice_amount'])
                        {
                            ?>selected="selected"<?php } ?> value="0"><?php __('All')?></option>
                                    <option <?php
                        if (!isset($_GET['invoice_amount']) || !empty($_GET['invoice_amount']))
                        {
                            ?>selected="selected"<?php } ?> value="1"><?php __('Non-Zero')?></option>
                                </select>
                            </div-->
                        </div-->
                    </form>
                </div>
            </div>
            <?php if (count($d) == 0): ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <script language="JavaScript" type="text/javascript">

                    var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
                    function showClients()
                    {
                        ss_ids_custom['client'] = _ss_ids_client;
                        winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);

                    }
                    function switchSelection(event)
                    {
                        var elem = $(event.target);
                        if(elem.attr("checked")) {
                            elem.closest('div').next().find('tbody :checkbox')
                                .attr('checked', 'checked');
                        }else{
                            elem.closest('div').next().find('tbody :checkbox').removeAttr('checked');
                        }
                    }

                    function selectCheckbox(event){
                        var elem = $(event.target);
                        if(!elem.attr("checked")) {
                            elem.closest('table').prev().find('thead :checkbox')
                                .removeAttr('checked');
                        }
                    }

                    $('#paid').val('<?php
                        $paid = isset($_GET['paid']) ? $_GET['paid'] : '';
                        echo $paid;
                        ?>');
                    $('#invoice_number').val('<?php
                        $paid = isset($_GET['invoice_number']) ? $_GET['invoice_number'] : '';
                        echo $paid;
                        ?>');
                    $('#type').val('<?php
                        $paid = isset($_GET['type']) ? $_GET['type'] : '';
                        echo $paid;
                        ?>');
                    $('#start_date').val('<?php
                        $paid = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                        echo $paid;
                        ?>');
                    $('#end_date').val('<?php
                        $paid = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                        echo $paid;
                        ?>');
                    $('#state').val('<?php
                        $paid = isset($_GET['state']) ? $_GET['state'] : '';
                        echo $paid;
                        ?>');
                </script>
                <form     id='download_invoice_form' method="post" action="<?php echo $this->webroot ?>pr/pr_invoices/mass_update/<?php echo $create_type; ?>">
                    <fieldset>
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary ">

                            <thead>
                            <tr>
                                <?php if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']): ?>
                                    <th>
                                        <input type="checkbox" id="selector-1" onchange="switchSelection(event)" value="1" name="selector" class="input in-checkbox">
                                    </th>
                                <?php endif; ?>
                                <th>
                                    <?php echo $appCommon->show_order('invoice_number', __('Invoice No', true)) ?>
                                    <?php __('Invoice Date'); ?>
                                </th>
                                <!--th><?php echo $appCommon->show_order('paid', __('Status', true)) ?></th-->
                                <th><?php echo $appCommon->show_order('client', __('Carriers', true)) ?></th>
                                <th><?php echo $appCommon->show_order('disputed', __('Invoice Period',true)) ?></th>
                                <th><?php echo $appCommon->show_order('total_amount', __('Amount Gross', true)) ?></th>
                                <?php if($_SESSION['login_type'] == 1):?>
                                    <th>&nbsp;<?php echo __('Amount Paid') ?></th>
                                <?php endif; ?>
                                <!--th>&nbsp;<?php echo __('Adjustment') ?></th-->
                                <th><?php echo $appCommon->show_order('due_date', __('Due Date', true)) ?></th>
                                <?php
                                if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']): ?>
                                    <th>&nbsp;</th>
                                    <th><?php __('State')?></th>
                                    <th class="last"><?php __('Action')?></th>
                                <?php elseif($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3): ?>
                                    <th class="last"><?php __('Action')?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $mydata = $p->getDataArray();
                            $loop = count($mydata);
                            for ($i = 0; $i < $loop; $i++):
                                $state = $mydata[$i][0]['state'];
                                ?>
                                <tr class="<?php echo ($state == -1) ? 'row-2 row-3' : 'row-1'; ?>">
                                    <?php if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']): ?>
                                        <td align="center"><input onchange="selectCheckbox(event)" type="checkbox" value="<?php echo $mydata[$i][0]['invoice_id'] ?>"
                                                                  id="ids-<?php echo $mydata[$i][0]['invoice_id'] ?>" name="ids[]"  class="input in-checkbox">
                                        </td>
                                    <?php endif; ?>
                                    <td rel="tooltip" id="ci_<?php echo $i ?>">
                                        <?php
                                        if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
                                        {
                                            ?>
                                            <!--<a  href="<?php echo $this->webroot ?>invoices/edit/<?php echo $mydata[$i][0]['invoice_id'] ?>" class="link_width"><b><?php echo $mydata[$i][0]['invoice_number'] ?></b></a>-->
                                            <a  href="###" title='<ul>
                                                    <li>start:<?php echo $mydata[$i][0]['generate_start_time']; ?></li>
                                                    <li>copy:<?php echo $mydata[$i][0]['generate_copy_time']; ?></li>
                                                    <li>statistics:<?php echo $mydata[$i][0]['generate_stats_time']; ?></li>
                                                    <li>end:<?php echo $mydata[$i][0]['generate_end_time']; ?></li>
                                                    </ul>' class="link_width"><b><?php echo $mydata[$i][0]['invoice_number'] ?></b></a>
                                            <?php
                                        }
                                        else
                                        {
                                            echo $mydata[$i][0]['invoice_number'];
                                        }
                                        ?>
                                        <br>
                                        <small title=""><?php echo $mydata[$i][0]['invoice_time'] ?></small></td>

                                    <!--td align="center">&nbsp;
                                <?php
                                    if ($state == -1)
                                        echo 'Void';
                                    else if ($state == 9)
                                        echo 'Sent<br>', $mydata[$i][0]['send_time'];
                                    else
                                        echo 'Normal'
                                    ?></td-->
                                    <td style="text-align:left;">
                                        <?php
                                        if (empty($mydata[$i][0]['res'])){
                                            echo $mydata[$i][0]['client'];
                                        }else{
                                            echo $mydata[$i][0]['res'];
                                        }
                                        ?>
                                    </td>
                                    <td align="center"><small> <?php echo $mydata[$i][0]['invoice_start'] ?><br>
                                            <?php echo $mydata[$i][0]['invoice_end'] ?> </small></td>
                                    <td align="right">
                                        <strong>
                                            <?php echo number_format($mydata[$i][0]['total_amount'], 2); ?>
                                        </strong>
                                        <br>
                                    </td>
                                    <?php if($_SESSION['login_type'] == 1):?>
                                        <td>
                                            <?php echo number_format($mydata[$i][0]['pay_amount'], 2); ?>
                                        </td>
                                    <?php endif; ?>
                                    <!--td>
                                <?php echo $mydata[$i][0]['credit_note']; ?>
                            </td-->
                                    <td align="center">
                                <span class="warn">
                                                <?php
                                                if (strpos($mydata[$i][0]['due_inteval'], 'days') && $mydata[$i][0]['due_inteval'] < 0)
                                                {
                                                    echo abs($mydata[$i][0]['due_inteval']);
                                                    ?>
                                                    days ago
                                                <?php } ?>
                                            </span> <br>
                                        <small><?php echo $mydata[$i][0]['due_date'] ?></small>
                                    </td>
                                    <?php if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']): ?>
                                        <td>
                                            <?php if (in_array($create_type, array(0, 1))): ?>
                                                <a  href="<?php echo $this->webroot ?>pr/pr_invoices/payment_to_invoice/<?php echo $mydata[$i][0]['invoice_id'] ?>/1/<?php echo $mydata[$i][0]['client_id'] ?>/<?php echo $create_type; ?>"></a>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php
                                            $state = $mydata[$i][0]['state'];
                                            if ($state == 9)
                                                echo __('Sent');
                                            else if ($state == -1)
                                                echo __('Void');
                                            else
                                                echo __('Normal');
                                            ?>
                                        </td>
                                        <td class="last">
                                            <?php if ($mydata[$i][0]['state'] != -1): ?>
                                                <a title="<?php __('re-generate')?>" class="re_generate" hit="<?php echo $mydata[$i][0]['invoice_id']; ?>" href="javascript:void(0)" >
                                                    <i class="icon-play-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($mydata[$i][0]['state'] != -1): ?>
                                                <?php if ($mydata[$i][0]['state'] == 0): ?>
                                                    <!--a href="<?php echo $this->webroot ?>pr/pr_invoices/change_type/<?php echo $mydata[$i][0]['invoice_id']; ?>/1/<?php echo $this->params['pass'][0]; ?>" title="Verify">
                                                <i class="icon-ok"></i>
                                            </a-->
                                                <?php endif; ?>
                                                <?php if ($mydata[$i][0]['state'] == 1 || $mydata[$i][0]['state'] == 9): ?>
                                                    <a class="send_invoice_mail" data-invoice-id="<?php echo $mydata[$i][0]['invoice_id']; ?>" data-invoice-type="<?php echo $create_type; ?>" href="<?php echo $this->webroot ?>pr/pr_invoices/resend/<?php echo $mydata[$i][0]['invoice_id']; ?>/<?php echo $create_type; ?>" title="<?php if ($mydata[$i][0]['state'] == 9): ?> <?php __('Resend')?><?php else: ?> Sent <?php endif; ?>">
                                                        <?php if ($mydata[$i][0]['state'] == 9): ?> <i class="icon-share-alt"></i> <?php else: ?> <i class="icon-share"></i>  <?php endif; ?>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo $this->webroot ?>pr/pr_invoices/change_type/<?php echo $mydata[$i][0]['invoice_id']; ?>/-1/<?php echo $this->params['pass'][0]; ?>" title="<?php __('Void')?>">
                                                    <i class="icon-stop"></i>
                                                </a>
                                                <?php
                                                if (in_array($create_type, array(0, 1))): ?>
                                                    <a href="<?php echo $this->webroot ?>pr/pr_invoices/credit_note/<?php echo $mydata[$i][0]['invoice_number']; ?>" title="<?php __('Credit Note')?>">
                                                        <i class="icon-plus-sign"></i>
                                                    </a>

                                                    <!--a href="<?php echo $this->webroot ?>pr/pr_invoices/debit/<?php echo $mydata[$i][0]['invoice_number']; ?>" title="<?php __('Debit')?>">
                                                <i class="icon-plus-sign"></i>
                                            </a-->
                                                    <a href="#myModal_PaymentList" data-toggle="modal" title="<?php __('Payment List')?>" class="payment_list" invoice_id="<?php echo $mydata[$i][0]['invoice_id']; ?>">
                                                        <i class="icon-money"></i>
                                                    </a>
                                                    <a href="###" title="<?php __('Apply Payment')?>" class="apply_payment" invoice_id="<?php echo $mydata[$i][0]['invoice_id']; ?>">
                                                        <i class="icon-dollar"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (isset($_SESSION['role_menu']['Payment_Invoice']['delete_invoice']) && $_SESSION['role_menu']['Payment_Invoice']['delete_invoice'] == 1): ?>
                                                <a title="<?php __('Delete')?>" onclick="myconfirm('<?php __('sure to delete') ?>',this);return false;" href="<?php echo $this->webroot ?>pr/pr_invoices/delete_invoice/<?php echo $mydata[$i][0]['invoice_id']; ?>/<?php echo $create_type ?>">
                                                    <i class="icon-remove"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a title="<?php echo __('download') ?>"
                                               href="<?php echo $this->webroot . 'pr/pr_invoices/createpdf_invoice/' . $mydata[$i][0]['invoice_number'] . '/'. $did ?>" >
                                                <i class="icon-file-text"></i>
                                            </a>
                                        </td>
                                    <?php elseif($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3): ?>
                                        <td>
                                            <a title="<?php echo __('download') ?>"
                                               href="<?php echo $this->webroot . 'pr/pr_invoices/createpdf_invoice/' . $mydata[$i][0]['invoice_number'] . '/'. $did ?>" >
                                                <i class="icon-file-text"></i>
                                            </a>
                                            <?php if($_SESSION['login_type'] == 3): ?>
                                                <a href="<?php echo $this->webroot . 'clients/client_pay/1/' . $mydata[$i][0]['client_id'] . '/'. base64_encode($mydata[$i][0]['invoice_id']) ?>"  title="<?php __('Pay'); ?>">
                                                    <i class="icon-usd"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <!--tr style="display:none;">
                            <td ><dl id="ci_<?php echo $i ?>-tooltip" class="tooltip1123">
                                    <div style="padding:10px;">
                                        <dd><b><?php echo __('State', true); ?>:</b></dd>
                                        <dd>
                                            <?php
                                $state = $mydata[$i][0]['state'];
                                if ($state == 9)
                                    echo 'sent';
                                else if ($state == -1)
                                    echo 'void';
                                else
                                    echo 'normal';
                                ?>
                                        </dd>
                                        <dt><b><?php echo __('Total', true); ?>:</b></dt>
                                        <dd> <?php echo $mydata[$i][0]['total_amount'] ?> USD</dd>
                                        <dt><b><?php echo __('Paid', true); ?>:</b></dt>
                                        <dd> <?php echo empty($mydata[$i][0]['paid']) ? '0.000' : $mydata[$i][0]['paid'] ?> <?php __('USD')?></dd>
                                        <dt><b><?php echo __('Period', true); ?>:</b></dt>
                                        <dd> <?php echo $mydata[$i][0]['invoice_start'] ?><br>
                                            <?php echo $mydata[$i][0]['invoice_end'] ?> </dd>
                                </dl></div>
                        </tr-->
                            <?php endfor; ?>
                            </tbody>
                        </table>
                        <?php if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']): ?>
                            <?php if ($w == true): ?>
                                <div style="margin: 10px 0px;">&nbsp;
                                    <?php echo __('action', true); ?>:
                                    <select id="action" name="action" class="input in-select"  style="width: 150px;">
                                        <option value=""></option>
                                        <!--option value="0"><?php __('Un-verify selected')?></option>
                <option value="1"><?php __('Verify Selected')?></option-->
                                        <option value="9"><?php __('Send Selected')?></option>
                                        <!--          <option value="3">delete invoices</option>-->
                                        <!--          <option value="00">Non-Disputed</option>-->
                                        <!--          <option value="11">Disputed</option>-->
                                        <!--          <option value="6">Dispute Resolved</option>-->
                                        <option value="-1"><?php __('Void Selected')?></option>
                                        <option value="8"><?php __('Download Selected')?></option>
                                    </select>
                                    <input type="email" name="email" id="email" placeholder="Enter email" style="display: none">
                                    <input type="submit" value="<?php __('Submit')?>" class="input btn-primary btn margin-bottom10">
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </fieldset>
                </form>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- DYNAREA -->

            <?php endif; ?>
        </div>
    </div>
</div>
<form action="<?php echo $this->webroot; ?>pr/pr_invoices/trigger" id="support_form" method="get">
    <div id="myModal_trigger" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Trigger Auto Invoice'); ?></h3>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <td colspan="2" class="center">
                        <?php echo __('Trigger Invoice for')?>
                        <input type="text" class="input in-text wdate" name="invoice_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',maxDate:'<?php echo date('Y-m-d'); ?>'});" readonly="">
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Enable Auto Invoice Email')?> </td>
                    <td>
                        <select name="auto_sending" >
                            <option value="1" ><?php __('Yes'); ?></option>
                            <option value="0" ><?php __('No'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="support_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<div id="myModal_PaymentList" class="modal hide" style="width:auto">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Payment List'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<div id="myModal_trigger_auto_invoice" class="modal hide" style="width:auto">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Please specify the date you want to trigger auto invoicign for:'); ?></h3>
    </div>
    <form style="margin: 0;" action="<?php echo $this->webroot ?>pr/pr_invoices/trigger_info" method="get">
        <div class="modal-body">
            <div style="float: left;margin-right: 15px;">
                <span>Trigger Invoice for:</span>
                <input type="text" value="" class="input in-text wdate" name="invoice_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',maxDate:'2016-10-18'});" readonly="">
            </div>
            <div style="float: left;">
                <span>Status:</span>
                <select name="status" class="width120" id="status">
                    <option value="0" selected="selected">All</option>
                    <option value="1">No</option>
                    <option value="2">Yes</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>
    </form>
</div>


<div id="dd"> </div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script language="Javascript" type="text/javascript">

    $(function() {

        $(".re_generate").click(function() {
            var id = $(this).attr('hit');
            bootbox.confirm('Are you sure to re-generate?', function(result)
            {
                if (result)
                {
                    regenerate(id);
                }
            });
        });

    });
    function regenerate(invoice_id)
    {
        $.get("<?php echo $this->webroot ?>pr/pr_invoices/regenerate", {"invoice_id": invoice_id}, function(d) {
            let theme = d.includes("Wrong") ? 'jmsg-error' : 'jmsg-success';
            jGrowl_to_notyfy(d, {theme: theme});
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        });
    }
    $(function() {
        $('#export_excel_btn').click(function() {
            $('#is_export').val('1');
            $('#search_panel').attr('target','_blank').submit();
        });
        $('#due_inteval').hide();
        $("#due_inteval_type").click(function() {
            if ($(this).val() == '') {
                $('#due_inteval').hide();
            } else {
                $('#due_inteval').show();
            }
        });
//    var due_inteval_type_value = $('#due_inteval_type').val();
//        if(! due_inteval_type_value)
//    {
//        $(this).next("#due_inteval").attr('disabled','disabled');
//
//    }
//    else
//    {
//        $("#due_inteval").romoveAttr('disabled');
//    }
//
//    $("#due_inteval_type").change(function(){
//
//        var due_inteval_type_value = $('#due_inteval_type').val();
//        if(! due_inteval_type_value)
//        {
//            $("#due_inteval").attr('disabled','disabled');
//        }
//        else
//        {
//            $("#due_inteval").romoveAttr('disabled');
//        }
//
//    });
        var $dd = $('#dd');
        var $payment_list = $('.payment_list');
        var $apply_payment = $('.apply_payment');
        $payment_list.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            $("#myModal_PaymentList").find('.modal-body').load('<?php echo $this->webroot ?>pr/pr_invoices/get_invoice_payments/' + invoice_id);
//            $dd.dialogui({
//                title: 'Payment List',
//                width: 960,
//                height: 600,
//                closed: false,
//                cache: false,
//                resizable: true,
//                href: '<?php //echo $this->webroot ?>//pr/pr_invoices/get_invoice_payments/' + invoice_id,
//                modal: true,
//                buttons: [{
//                    text: 'Close',
//                    handler: function() {
//                        $dd.dialogui('close');
//                    }
//                }]
//            });
//            $dd.dialogui('refresh', '<?php //echo $this->webroot ?>//pr/pr_invoices/get_invoice_payments/' + invoice_id);
        });
        $apply_payment.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            $dd.dialogui({
                title: 'Apply Payment',
                width: 960,
                height: 600,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>pr/pr_invoices/apply_payment/' + invoice_id + '/<?php echo $create_type; ?>',
                modal: true,
                buttons: [{
                    text: 'Submit',
                    handler: function() {
                        if ($('input[name="payment_ids[]"]:checked').length == 0) {
                            bootbox.alert("<?=__('apply_nothing_to_do')?>");
                        } else {
//                            var $payment_form = $('#payment_form');
//                            $payment_form.submit();

                            let paymentIds = [];
                            $('input[name="payment_ids[]"]:checked').each(function (element, value) {
                                paymentIds.push($(value).val());
                            });
                            paymentIds = paymentIds.join(',');
                            $.post('<?php echo $this->webroot ?>pr/pr_invoices/apply_payment/' + invoice_id + '/<?php echo $create_type; ?>',
                                {
                                    payment_ids : paymentIds
                                }, function (data) {
                                    if(data == 1) {
                                        location.href = '<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice';
                                    } else if(data == 2) {
                                        location.href = '<?php echo $this->webroot ?>pr/pr_invoices/view/<?php echo $create_type; ?>';
                                    }
                                });
                        }
                    }
                }, {
                    text: 'Close',
                    handler: function() {
                        $dd.dialogui('close');
                    }
                }]
            });
            $dd.dialogui('refresh', '<?php echo $this->webroot ?>pr/pr_invoices/apply_payment/' + invoice_id + '/<?php echo $create_type; ?>');
        });
        var $send_invoice_mail = $('.send_invoice_mail');
        $send_invoice_mail.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>pr/pr_invoices/send_invoice_mail/' + $this.attr('data-invoice-id') + '/' + $this.attr('data-invoice-id'),
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'title': 'Resend Invoice',
                        'width': '450px',
                        'height': 200,
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                                $form = $("#send_email_id");
                                $form.submit();
                            }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                                $(this).dialog("close");
                            }}],
                        'create': function(event, ui) {
                            $form = $('form', $dd);
                            $form.validationEngine();
                        }
                    });
                }
            );
            return false;
        });

        $(".void-invoice").click(function () {
            let invoiceId = $(this).data('id');

            $.post("http://<?php echo $_SERVER['SERVER_NAME']; ?>/pr/pr_invoices/voidInvoice", {
                invoiceId: invoiceId
            }, function (data) {
                showMessages_new("[{field:'', code: '201', msg: 'Successfully!'}]");
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            });
        });

        window.onbeforeunload = function(e) {
            $('.fakeloader').remove();
        };

        $('#action').on('change', function () {
            if ($(this).val() == 9) {
                $('#email').show();
            } else {
                $('#email').hide();
            }
        });

        $('#download_invoice_form').on('submit', function (event) {
            if ($('#action').val() == 9 && !$('#email').val().length) {
                event.preventDefault();
                return false;
            }
        });

    });
</script>
