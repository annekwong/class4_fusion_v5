<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Client') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client List') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Client List') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <!--<a class="btn btn-primary btn-icon glyphicons file_import" href="<?php echo $this->webroot ?>uploads/carrier"><i></i>Import</a>-->
    <?php
    if ($_SESSION['role_menu']['Management']['clients']['model_w'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>clients/add"><i></i> <?php __('Create New')?></a>
    <?php } ?>
    <?php
    if ($_SESSION['role_menu']['Management']['clients']['model_x'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons file_export" href="<?php echo $this->webroot ?>downloads/carrier"><i></i> <?php __('Export')?></a>
        <a class="btn btn-primary btn-icon glyphicons file_export" id="download_balance" href="javascript:void(0)"><i></i><?php __('Download Balance')?></a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php
$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
$data = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a class="glyphicons list"  href="<?php echo $this->webroot; ?>clients/index">
                        <i></i>
                        <?php __('Client List')?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons notes_2"  href="<?php echo $this->webroot; ?>clients/client_limit">
                        <i></i>
                        <?php __('Client Limit')?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons compass"  href="<?php echo $this->webroot; ?>clients/ingress_host">
                        <i></i>
                        <?php __('Ingress Host')?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Type')?>:</label>
                        <select  name="filter_client_type">
                            <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>><?php __('All Active Clients')?></option>
                            <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>><?php __('All Inactive Clients')?></option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Name')?>:</label>
                        <input type="text" name="search" id="search-_q" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <!-- Table -->
            <div class="overflow_x">
                <table class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <!-- Table heading -->
                    <thead>
                    <tr>
                        <th rowspan="2"><?php echo $appCommon->show_order('Name', __('Name', true)) ?></th>
                        <th colspan="2"><?php __('Traffic')?></th>
                        <th colspan="2"><?php __('Payment')?></th>
                        <th colspan="2"><?php __('Credit')?></th>
                        <th colspan="2"><?php __('Invoice')?></th>
                        <?php if($is_show_mutual_balance): ?>
                            <th colspan="2"><?php __('Balance')?></th>
                        <?php else: ?>
                            <th rowspan="2"><?php __('Balance')?></th>
                        <?php endif; ?>
                        <?php if($debug): ?>
                            <th rowspan="2">c4 client balance</th>
                        <?php endif; ?>
                        <th rowspan="2"><?php __('Credit Limit')?></th>
                        <th rowspan="2"><?php echo $appCommon->show_order('egress_count', __('Egress', true)) ?></th>
                        <th rowspan="2"><?php echo $appCommon->show_order('ingress_count', __('Ingress', true)) ?></th>
                        <th rowspan="2"><?php __('Action')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Sent')?></th>
                        <th><?php __('Recv')?></th>

                        <th><?php __('Sent')?></th>
                        <th><?php __('Recv')?></th>

                        <th><?php __('Sent')?></th>
                        <th><?php __('Recv')?></th>

                        <th><?php __('Sent')?></th>
                        <th><?php __('Recv')?></th>

                        <?php if($is_show_mutual_balance): ?>
                            <th><?php __('Mutual')?></th>
                            <th><?php __('Available')?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <!-- // Table heading END -->

                    <!-- Table body -->
                    <tbody id="is-balance" value="<?php echo $is_show_mutual_balance;?>">
                    <?php foreach ($data as $item): ?>
                        <tr style="<?php if ($item[0]['status'] == 0) echo 'background:#ccc;'; ?>" class="ajax_tr" value="<?php echo $item[0]['client_id'] ?>">
                            <td>
                                <a title="Last login:<?php echo $item[0]['last_login_time']; ?><br>Update at:<?php echo $item[0]['update_at'] ?><br>Update by:<?php echo $item[0]['update_by'] ?>" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>">
                                    <?php echo $item[0]['name']; ?>
                                </a>
                            </td>
                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>
                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>

                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>
                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>

                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>
                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>

                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>
                            <td class="ajax_td">
                                <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                            </td>

                            <?php if($is_show_mutual_balance): ?>
                                <td class="ajax_td">
                                    <a href="<?php echo $this->webroot ?>finances/get_mutual_ingress_egress_detail/<?php echo base64_encode($item[0]['client_id']) ?>">
                                        <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                                    </a>
                                </td>
                                <td class="ajax_td">
                                    <a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo base64_encode($item[0]['client_id']) ?>">
                                        <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                                    </a>
                                </td>
                            <?php else: ?>
                                <td class="ajax_td">
                                    <a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo base64_encode($item[0]['client_id']) ?>">
                                        <img src='<?php echo $this->webroot?>images/check_waiting.gif' />
                                    </a>
                                </td>
                            <?php endif; ?>
                            <?php if($debug): ?>
                                <td><?php echo !isset($item[0]['balance']) ? '--' : $item[0]['balance'] < 0 ? '(' . str_replace('-', '', number_format($item[0]['balance'], 3)) . ')' : number_format($item[0]['balance'], 3); ?></td>
                            <?php endif; ?>
                            <td><?php echo number_format(abs($item[0]['allowed_credit']), 5); ?></td>
                            <td>
                                <a class='egress_count' href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $item[0]['client_id'] ?>&viewtype=client" >
                                    <?php echo $item[0]['egress_count'] ?>
                                </a>
                            </td>
                            <td>
                                <a class='ingress_count' href='<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $item[0]['client_id'] ?>&viewtype=client'>
                                    <?php echo $item[0]['ingress_count'] ?>
                                </a>
                            </td>
                            <td>
                                <a title="Client Transaction" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>"><i class="icon-dollar"></i></a>
                                <?php if ($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                                    <a href="javascript:void(0)" hit="<?php echo $item[0]['client_id'] ?>" class="reset_balance" title="<?php __('Reset the balance')?>"><i class="icon-money"></i></a>
                                <?php endif; ?>
                                <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']): ?>
                                    <a class="auth_user_login" hit="<?php echo $item[0]['client_id'] ?>" href="<?php echo $this->webroot ?>homes/auth_user?client_id=<?php echo base64_encode($item[0]['client_id']) ?>&lang=<?php echo $lang; ?>" title="<?php __('Login by via this client')?>"><i class="icon-signin"></i></a>
                                    <a href="###" hit="<?php echo $item[0]['client_id'] ?>" class="change_password" title="<?php __('Change the password of the client')?>"><i class="icon-key"></i></a>

                                    <?php if ($item[0]['status'] == 1): ?>
                                        <a title="<?php __('Inactive the client')?>" onclick="return myconfirm('Are you sure to inactivate the client [<?php echo $item[0]['name'] ?>] ?', this)" href="<?php echo $this->webroot ?>clients/dis_able/<?php echo base64_encode($item[0]['client_id']) ?>?<?php echo $$hel->getParams('getUrl') ?>"><i class="icon-check"></i></a>
                                    <?php else: ?>
                                        <a title="Active the client" onclick="return myconfirm('Are you sure to active the client [ <?php echo $item[0]['name'] ?>] ?', this)" href="<?php echo $this->webroot ?>clients/active/<?php echo base64_encode($item[0]['client_id']) ?>?<?php echo $$hel->getParams('getUrl') ?>"><i class="icon-check-empty"></i></a>
                                    <?php endif; ?>
                                    <a href="#myModal_carrier_template" title="Save as Template" class="add_template" data-toggle="modal" resource="<?php echo $item[0]['client_id']?>">
                                        <i class="icon-bookmark-empty"></i>
                                    </a>
                                    <a title="<?php __('Edit')?>" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>"><i class="icon-edit"></i></a>
                                    <a title="<?php __('Delete')?>" hit2="<?php echo $item[0]['name']; ?>" hit="<?php echo $item[0]['client_id'] ?>" href="javascript:void(0)" class="delete_client"><i class="icon-remove"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                    <!-- // Table body END -->

                </table>
                <!-- // Table END -->
                <script>
                    $(function(){
                        var is_balance = $('#is-balance').attr('value');

                        $.each($(".ajax_tr"),function(){
                            var $this = $(this);
                            var value = $(this).attr('value');
                            var mutual_balance;
                            var actual_balance;

                            $.ajax({
                                'url':'<?php echo $this->webroot?>clients/get_ajax',
                                'type':'post',
                                'dataType':'json',

                                'data':{'client_id':value},
                                'success':function(data){
                                    //$(content).html(data['count']);
                                    $this.find('.ajax_td:eq(0)').html(data['unbilled_outgoing_traffic']);
                                    $this.find('.ajax_td:eq(1)').html(data['unbilled_incoming_traffic']);
                                    $this.find('.ajax_td:eq(2)').html(data['payment_sent']);
                                    $this.find('.ajax_td:eq(3)').html(data['payment_received']);
                                    $this.find('.ajax_td:eq(4)').html(data['credit_note_sent']);
                                    $this.find('.ajax_td:eq(5)').html(data['credit_note_received']);
                                    $this.find('.ajax_td:eq(6)').html(data['invoice_set']);
                                    $this.find('.ajax_td:eq(7)').html(data['invoice_received']);

                                    if(is_balance==1){
                                        mutual_balance = Math.abs(data['mutual_balance']);
                                        mutual_balance = mutual_balance.toFixed(5);
                                        if(data['mutual_balance'] < 0){
                                            mutual_balance = '(' + mutual_balance + ')';
                                        }

                                        actual_balance = Math.abs(data['actual_balance']);
                                        actual_balance = actual_balance.toFixed(5);
                                        if(data['actual_balance'] < 0){
                                            actual_balance = '(' + actual_balance + ')';
                                        }

                                        $this.find('.ajax_td:eq(8)').find('a').html(mutual_balance);
                                        $this.find('.ajax_td:eq(9)').find('a').html(actual_balance);
                                    } else {
                                        actual_balance = Math.abs(data['actual_balance']);
                                        actual_balance = actual_balance.toFixed(5);
                                        if(data['actual_balance'] < 0){
                                            actual_balance = '(' + actual_balance + ')';
                                        }
                                        $this.find('.ajax_td:eq(8)').find('a').html(actual_balance);
                                    }
                                }
                            });
                        })
                    })
                </script>
            </div>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>