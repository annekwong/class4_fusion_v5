<style>
    .ajax_td img{
        margin: 0px;
        max-height: 17px;
    }

    #foo {
        min-width: 350px;
        border-radius: 5px 0px 0px 5px;
    }

    .referral .btn {
        margin-bottom: 10px;
        margin-left: -4px;
        border-radius: 0px 5px 5px 0px;
    }

    .referral label {
        font-weight: 600;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>clients/index"><?php __('Management') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>clients/index"><?php echo __('Carrier') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Carrier List') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>
<!--        <a class="btn btn-primary btn-icon glyphicon glyphicon-edit mass-edit"  href="#">-->
<!--            <i class="icon-edit"></i> --><?php //__('Mass Edit')?>
<!--        </a>-->
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>clients/add"><i></i> <?php __('Create New')?></a>
    <?php } ?>
    <?php if ($_SESSION['role_menu']['Management']['clients']['model_x']) { ?>
        <a class="btn btn-primary btn-icon glyphicons file_export" href="<?php echo $this->webroot ?>downloads/carrier"><i></i> <?php __('Export')?></a>
        <a class="btn btn-primary btn-icon glyphicons file_export" id="download_balance" href="javascript:void(0)"><i></i><?php __('Download Balance')?></a>
        <?php if (isset($this->params['url']['filter_client_type']) && $this->params['url']['filter_client_type'] == 2) : ?>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check reset-selected"  href="javascript:void(0)" >
                <i class="icon-money"></i> <?php __('Reset Selected')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check activate-all"  href="javascript:void(0)" >
                <i class="icon-check"></i> <?php __('Activate All')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check activate-selected"  href="javascript:void(0)">
                <i class="icon-check"></i> <?php __('Activate Selected')?>
            </a>
        <?php else: ?>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check reset-selected"  href="javascript:void(0)" >
                <i class="icon-money"></i> <?php __('Reset Selected')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check activate-all" href="javascript:void(0)" >
                <i class="icon-check"></i> <?php __('Activate All')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check deactivate-all"  href="javascript:void(0)" >
                <i class="icon-check"></i> <?php __('Deactivate All')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicon glyphicon-check deactivate-selected"  href="javascript:void(0)">
                <i class="icon-check"></i> <?php __('Deactivate Selected')?>
            </a>
        <?php endif; ?>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php
$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
$data = $p->getDataArray();
?>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.16/clipboard.min.js"></script>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if($_SESSION['login_type'] != 2): ?>
            <div class="widget-head">
                <ul>
                    <li class="active">
                        <a class="glyphicons list"  href="<?php echo $this->webroot; ?>clients/index">
                            <i></i>
                            <?php __('Carrier List')?>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons notes_2"  href="<?php echo $this->webroot; ?>clients/client_limit">
                            <i></i>
                            <?php __('Carrier Limit')?>
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
        <?php endif; ?>
        <div class="widget-body">
           
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Type')?>:</label>
                        <select  name="filter_client_type">
                            <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>><?php __('All Active Clients')?></option>
                            <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>>All Inactive Clients</option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php echo __('Terms', true); ?>:</label>
                        <select id="terms" name="terms" class="input in-select" style="width:100px;">
                            <option value="0" <?php echo $common->set_get_select('terms', 0, TRUE) ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('terms', 1) ?>><?php __('Prepaid')?></option>
                            <option value="2" <?php echo $common->set_get_select('terms', 2) ?>><?php __('Postpaid')?></option>
                        </select>
                        </select>
                    </div>
                    <!-- Filter -->
                    <div>
                        <label><?php __('Company')?>:</label>
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
            <div class="separator bottom"></div>
            <div class="overflow_x">
                <table class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary coercive-float" id="clientsTable">

                    <!-- Table heading -->
                    <thead>
                    <?php if($is_show_mutual_balance): ?>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th class="columnHidden"><?php echo $appCommon->show_order('Name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('Company', __('Company', true)) ?></th>
                            <th><?php __('Available')?> <?php __('Balance') ?></th>
                            <th><?php __('Credit Limit')?></th>
                            <?php if ($_SESSION['login_type'] == 2): ?>
                            <th ><?php __('Registered On')?></th>
                            <th ><?php __('Created On')?></th>
                            <th ><?php __('Status')?></th>
                            <?php endif; ?>
                            <?php if ($_SESSION['login_type'] != 2): ?>
                                <th ><?php echo $appCommon->show_order('egress_count', __('Egress', true)) ?></th>
                                <th ><?php echo $appCommon->show_order('ingress_count', __('Ingress', true)) ?></th>
                            <?php endif; ?>
                            <th ><?php __('Action')?></th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th class="columnHidden"><?php echo $appCommon->show_order('Name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('Company', __('Company', true)) ?></th>
                            <th><?php __('Balance')?></th>
                            <th><?php __('Credit Limit')?></th>
                            <?php if ($_SESSION['login_type'] == 2): ?>
                            <th rowspan="2"><?php __('Registered On')?></th>
                            <th rowspan="2"><?php __('Created On')?></th>
                            <th rowspan="2"><?php __('Status')?></th>
                            <?php endif; ?>
                            <?php if ($_SESSION['login_type'] != 2): ?>
                                <th><?php echo $appCommon->show_order('egress_count', __('Egress', true)) ?></th>
                                <th><?php echo $appCommon->show_order('ingress_count', __('Ingress', true)) ?></th>
                            <?php endif; ?>
                            <th><?php __('Action')?></th>
                        </tr>
                    <?php endif; ?>
                    </thead>
                    <!-- // Table heading END -->

                    <!-- Table body -->
                    <tbody id="is-balance" value="<?php echo $is_show_mutual_balance;?>">
                    <?php foreach ($data as $item): ?>
                        <tr style="<?php if ($item[0]['status'] == 0) echo 'background:#ccc;'; ?>" class="ajax_tr" value="<?php echo $item[0]['client_id'] ?>">
                            <td><input type="checkbox"></td>
                            <?php if($_SESSION['login_type'] == 2): ?>
                                <td>
                                    <a title="Last login:<?php echo $item[0]['last_login_time']; ?><br>Update at:<?php echo $item[0]['update_at'] ?><br>Update by:<?php echo $item[0]['update_by'] ?>" href="<?php echo $this->webroot ?>agent_portal/edit_client/<?php echo base64_encode($item[0]['client_id']) ?>">
                                        <?php echo $item[0]['name']; ?>
                                    </a>
                                </td>
                            <?php else: ?>
                                <td>
                                    <a title="Last login:<?php echo $item[0]['last_login_time']; ?><br>Update at:<?php echo $item[0]['update_at'] ?><br>Update by:<?php echo $item[0]['update_by'] ?>" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>">
                                        <?php echo $item[0]['name']; ?>
                                    </a>
                                </td>
                            <?php endif; ?>
                            <td>
                                <?php echo $item[0]['company']; ?>
                            </td>
                                <td>
                                    <a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo $item[0]['client_id']; ?>">
                                        <?php
                                        echo !isset($item[0]['balance']) ? '--' : $item[0]['balance'] < 0 ? '('.str_replace('-', '', number_format($item[0]['balance'], 3)).')' : number_format($item[0]['balance'], 3);
                                        ?>
                                    </a>
                                </td>
                            <td>
                                <?php
                                if (isset($item[0]['mode']) && $item[0]['mode'] == 1){
                                    echo "--";
                                }elseif(isset($item[0]['unlimited_credit']) && $item[0]['unlimited_credit']){
                                    __('Unlimited');
                                }else{
                                    echo number_format(abs($item[0]['allowed_credit']), 5);
                                }
                                ?>
                            </td>
                            <?php if ($_SESSION['login_type'] == 2): ?>
                            <td><?php echo $item[0]['registered_on']; ?></td>
                            <td><?php
                            // create time only for admin created client
                            echo !isset($item[0]['approved']) ? $item[0]['create_time']:'';
                            ?></td>
                            <td><?php echo isset($item[0]['approved'])? $signup_status[$item[0]['approved']]:''; ?></td>
                            <?php endif;?>
                            <?php if($_SESSION['login_type'] != 2): ?>
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
                                    <a onclick="return myconfirm('<?php __('Are you sure to send a low balance alert'); ?>?',this);" href="<?php echo $this->webroot; ?>clients/low_balance_alert/<?php echo base64_encode($item[0]['client_id']); ?>" title="<?php __('Send Low Balance Alert')?>"><i class="icon-envelope"></i></a>
                                    <a onclick="return myconfirm('<?php __('Are you sure to send a welcome letter'); ?>?', this, ['Yes', 'Skip']);" href="<?php echo $this->webroot; ?>clients/send_welcome/<?php echo base64_encode($item[0]['client_id']); ?>" title="<?php __('Send Welcome Letter')?>"><i class="icon-user" area-hidden="true"></i></a>
                                    <?php if ($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                                        <a href="javascript:void(0)" hit="<?php echo $item[0]['client_id'] ?>" class="reset_balance" title="<?php __('Reset The Balance')?>"><i class="icon-money"></i></a>
                                    <?php endif; ?>
                                    <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']): ?>
                                        <?php if (isset($item[0]['is_panelaccess'])) { ?>
                                        <a class="auth_user_login" hit="<?php echo $item[0]['client_id'] ?>" href="<?php echo $this->webroot ?>homes/auth_user?client_id=<?php echo base64_encode($item[0]['client_id']) ?>&lang=<?php echo $lang; ?>" title="<?php __('Login by via this client')?>"><i class="icon-signin"></i></a>
                                        <a href="###" hit="<?php echo $item[0]['client_id'] ?>" class="change_password" title="<?php __('Change the password of the client')?>"><i class="icon-key"></i></a>
                                        <?php } ?>

                                        <?php if ($item[0]['status'] == 1): ?>
                                            <a title="Deactivate The Client" class="inactivate-client" data-client-id="<?php echo base64_encode($item[0]['client_id']); ?>" data-client-name="<?php echo $item[0]['name'] ?>"><i class="icon-check"></i></a>
                                        <?php else: ?>
                                            <a title="Activate The Client" class="activate-client" data-client-id="<?php echo base64_encode($item[0]['client_id']); ?>" data-client-name="<?php echo $item[0]['name'] ?>"><i class="icon-check-empty"></i></a>
                                        <?php endif; ?>
                                        <a href="#myModal_carrier_template" title="Save As Template" class="add_template" data-toggle="modal" resource="<?php echo $item[0]['client_id']?>">
                                            <i class="icon-bookmark-empty"></i>
                                        </a>
                                        <a title="<?php __('Edit')?>" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>"><i class="icon-edit"></i></a>
                                        <a title="<?php __('Delete')?>" hit2="<?php echo $item[0]['name']; ?>" hit="<?php echo $item[0]['client_id'] ?>" href="###" class="delete_client"><i class="icon-remove"></i></a>
                                    <?php endif; ?>
                                </td>
                            <?php else: ?>
                                <td>
                                    <?php if($_SESSION['sst_agent_info']['Agent']['edit_permission']): ?>
                                        <a target="_blank" title="Client Transaction" href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo $item[0]['client_id']; ?>"><i class="icon-dollar"></i></a>
                                        <a target="_blank" title="Client Payment" href="<?php echo $this->webroot ?>clients/clients_payment/0/<?php echo $item[0]['client_id']; ?>"><i class="icon-money"></i></a>
                                        <a target="_blank" title="Pay For Client" href="<?php echo $this->webroot ?>clients/client_pay/1/<?php echo $item[0]['client_id']; ?>"><i class="icon-share-alt"></i></a>
                                        <a target="_blank" title="Client Invoice" href="<?php echo $this->webroot ?>pr/pr_invoices/view/<?php echo $item[0]['client_id']; ?>"><i class="icon-stop"></i></a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                    <!-- // Table body END -->

                </table>
            </div>
            <!-- // Table END -->

            <!-- </div> -->
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>

<input type="hidden" id="sort-type" value="<?= $appCommon->_get('url.filter_client_type'); ?>" />

<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<script type="text/javascript">
    $(function(){

        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        $('.activate-selected, .deactivate-selected').on('click', function(e){
            e.preventDefault();
            var ids = '';
            var selectedCount = 0;
            $.each($('tbody > tr:visible'), function(){
                if ($(this).find('input[type="checkbox"]').is(':checked')) {
                    if(typeof $(this).attr('value') !== 'undefined' && $(this).attr('value')){
                        ids += $(this).attr('value') + ',';
                    }

                    selectedCount++;
                }
            });
            if (!selectedCount) {
                jGrowl_to_notyfy("Please select at least one Client!", {theme: 'jmsg-error'});
            } else {
                var task = 'activate';
                var filter_client_type = 1;
                if ($(this).hasClass('deactivate-selected')) {
                    task = 'deactivate';
                    filter_client_type = 2;
                }

                $.ajax({
                    url: '<?php echo $this->webroot ?>clients/changeSelectedClientsActiveVal',
                    data: {ids: ids, task: task},
                    dataType: 'json',
                    success: function(res){
                        var result = res.result;
                        if(result == 'Deactivated'){
                            $('#is-balance tr').each(function(){
                                if(ids.indexOf($(this).attr('value')) !== -1){
                                    $(this).find('a.inactivate-client').removeClass('inactivate-client').addClass('activate-client').attr('title','Activate The Client');
                                    $('a.activate-client').attr('title','Activate The Client');
                                    $('a.inactivate-client').attr('title','Deactivate The Client');
                                    $(this).find('.icon-check').addClass('icon-check-empty').removeClass('icon-check');
                                }
                            });
                            result = 'deactivated';
                        }
                        $('a.activate-client').attr('title','Deactivate The Client');
                        $('a.inactivate-client').attr('title','Activate The Client');
                        jGrowl_to_notyfy("Selected Clients are " + result + " successfully!", {theme: 'jmsg-success'});
                        if ($('#sort-type').val() > 0) {
                            $('tbody > tr:visible').find('input[type="checkbox"]:checked').parents('tr').fadeOut(500);
                        }
                        location.reload();
                    }
                });
            }
        });

        $('.reset-selected').on('click', function(e){
            e.preventDefault();
            var ids = [];
            var selectedCount = 0;
            $.each($('tbody > tr:visible'), function(){
                if ($(this).find('input[type="checkbox"]').is(':checked')) {
                    if(typeof $(this).attr('value') !== 'undefined' && $(this).attr('value')){
                        ids.push($(this).attr('value'));
                    }

                    selectedCount++;
                }
            });
            if (!selectedCount) {
                jGrowl_to_notyfy("Please select at least one Client!", {theme: 'jmsg-error'});
            } else {
                if (!$('#dd').length) {
                    $(document.body).append("<div id='dd'></div>");
                }
                var $dd = $('#dd');
                var $form = null;
                $dd.load('<?php echo $this->webroot; ?>clients/reset_balance/1',
                    {},
                    function(responseText, textStatus, XMLHttpRequest) {
                        $dd.dialog({
                            'title': '<?php echo __('Reset Balance');?>',
                            'width': '450px',
                            'create': function(event, ui) {
                                $form = $('form', $dd);
                                $form.validationEngine();
                            },
                            'buttons': [
                                {text: "Reset", "class": "btn btn-primary", click: function() {
                                        $form = $('form', $dd);
                                        if (!$form.find('#balance').val()) {
                                            jGrowl_to_notyfy("Balance is required!", {theme: 'jmsg-error'});
                                            return false;
                                        }
                                        if (!$form.find('#begin_time').val()) {
                                            jGrowl_to_notyfy("Begin Time is required!", {theme: 'jmsg-error'});
                                            return false;
                                        }

                                        $form.append("<input type='hidden' name='ids' value='" + ids.join(',') + "'/>");
                                        $form.attr('action', "<?php echo $this->webroot; ?>clients/reset_balance/1");
                                        $form.submit();
                                    }},
                                {text: "Cancel", "class": "btn btn-inverse", click: function() {
                                        $dd.dialog("close");
                                    }}
                            ]
                        });
                    }
                );
            }
        });

        $('.activate-all, .deactivate-all').off('click').on('click', function(e){
            e.preventDefault();
            var task = 'activate';
            if ($(this).hasClass('deactivate-all')) {
                task = 'deactivate';
            }
            var ids = '';
            $.each($('tbody > tr:visible'), function(){
                if(typeof $(this).attr('value') !== 'undefined' && $(this).attr('value')){
                    ids += $(this).attr('value') + ',';
                }

            });
            if(ids){
                $.ajax({
                    url: '<?php echo $this->webroot ?>clients/changeClientsActiveAll',
                    data: {ids: ids, task: task},
                    dataType: 'json',
                    success: function(res){
                        var result = res.result;
                        if(result == 'Deactivated'){
                            result = 'deactivated';
                            $('a.inactivate-client').addClass('activate-client').removeClass('inactivate-client');
                            $('i.icon-check').addClass('icon-check-empty').removeClass('icon-check');
                        }else{
                            result = 'activated';
                            $('a.activate-client').addClass('inactivate-client').removeClass('activate-client');
                            $('i.icon-check-empty').addClass('icon-check').removeClass('icon-check-empty');
                        }
                        jGrowl_to_notyfy("All Clients are " + result + " successfully!", {theme: 'jmsg-success'});
                        /* if ($('#sort-type').val() == 0) {
                            $('tbody > tr:visible').find('.activate-client, .inactivate-client').find('i').switchClass('icon-check', 'icon-check-empty');
                            $.each($('tbody > tr:visible').find('.activate-client, .inactivate-client'), function(){
                                $(this).data('qtip').options.content.text = 'Activate the client';
                            })
                        } else {
                            $('tbody > tr:visible').find('input[type="checkbox"]').parents('tr').fadeOut(500);
                        } */
                        location.reload();
                    }
                });
            }
        });


        // $.each($(".ajax_tr"),function(){
        // var $this = $(this);
        // var value = $(this).attr('value');
        // var mutual_balance;
        // var actual_balance;
        //     $.ajax({
        //         'url':'<?php echo $this->webroot?>clients/get_ajax',
        //         'type':'post',
        //         'dataType':'json',

        //         'data':{'client_id':value},
        //         'success':function(data){
        //             //$(content).html(data['count']);
        //             $this.find('.traffic_s').html(data['unbilled_outgoing_traffic']);
        //             $this.find('.traffic_r').html(data['unbilled_incoming_traffic']);
        //             $this.find('.payment_s').html(data['payment_sent']);
        //             $this.find('.payment_r').html(data['payment_received']);
        //             $this.find('.credit_s').html(data['credit_note_sent']);
        //             $this.find('.credit_r').html(data['credit_note_received']);
        //             $this.find('.invoice_s').html(data['invoice_set']);
        //             $this.find('.invoice_r').html(data['invoice_received']);

        //             var $new_hide_tr = hide_clone_tr.clone().attr('id',value+'-cloneTr');
        //             $new_hide_tr.find('.traffic_s').html(data['unbilled_outgoing_traffic']);
        //             $new_hide_tr.find('.traffic_r').html(data['unbilled_incoming_traffic']);
        //             $new_hide_tr.find('.payment_s').html(data['payment_sent']);
        //             $new_hide_tr.find('.payment_r').html(data['payment_received']);
        //             $new_hide_tr.find('.credit_s').html(data['credit_note_sent']);
        //             $new_hide_tr.find('.credit_r').html(data['credit_note_received']);
        //             $new_hide_tr.find('.invoice_s').html(data['invoice_set']);
        //             $new_hide_tr.find('.invoice_r').html(data['invoice_received']);

        //             if(is_balance==1){
        //                 mutual_balance = Math.abs(data['mutual_balance']);
        //                 mutual_balance = mutual_balance.toFixed(5);
        //                 if(data['mutual_balance'] < 0){
        //                     mutual_balance = '(' + mutual_balance + ')';
        //                 }

        //                 actual_balance = Math.abs(data['actual_balance']);
        //                 actual_balance = actual_balance.toFixed(5);
        //                 if(data['actual_balance'] < 0){
        //                     actual_balance = '(' + actual_balance + ')';
        //                 }

        //                 $this.find('.mutual_td').find('a').html(mutual_balance);
        //                 $this.find('.actual_td').find('a').html(actual_balance);
        //                 $new_hide_tr.find('.mutual_td').html(mutual_balance);
        //                 $new_hide_tr.find('.actual_td').html(actual_balance);

        //             } else {
        //                 actual_balance = Math.abs(data['actual_balance']);
        //                 actual_balance = actual_balance.toFixed(5);
        //                 if(data['actual_balance'] < 0){
        //                     actual_balance = '(' + actual_balance + ')';
        //                 }
        //                 $this.find('.actual_td').find('a').html(actual_balance);
        //                 $new_hide_tr.find('.actual_td').html(actual_balance);
        //             }
        //             $new_hide_tr.appendTo('#hide_data_table');
        //         }
        //     });
        // });
    });
</script>
<script type="text/javascript">
    var options;
    var del_id;
    var del_name;
    function delete_client(obj) {
        del_id = jQuery(obj).attr('client_id');
        del_name = jQuery(obj).attr('client_name');
        options = jQuery.xshow({
            width: '350px',
            left: '30%',
            html: '<div style="font-size:14px">When the carrier is removed, all the relevant CDRs will be deleted.Please choose one of the following options:</div>' +
            '<div>' +
            '<br/>' +
            '<div style="font-size:12px"><a href="#" onclick="ajax_del_client(1,this);return false;">Confirm</a></div>' +
            '</div>'
        });
    }

    $(function() {
        var $reset_balance_btn = $('.reset_balance');
        var $change_password_btn = $('.change_password');
        var $download_balance_btn = $('#download_balance');
        var $delete_client_btn = $('.delete_client');
        $(".auth_user_login").click(function() {
            var href = $(this).attr('href');
            var id = $(this).attr('hit');
            var flg = true;
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>clients/ajax_check_login",
                data: "id=" + id,
                success: function(msg) {
                    if (msg == 0)
                    {
                        jGrowl_to_notyfy('This carrier does not open the Self-Service Portal or does not have login user and password!', {theme: 'jmsg-error'});
                        return false;
                    } else
                    {
                        window.location.href = href;
                    }
                }
            });
            return false;
        });

        $(".welcom").click(function() {
            //var href = $(this).attr('href');
            var id = $(this).attr('hit');
            var flg = true;
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>clients/send_welcom_letter/ajax",
                data: "id=" + id,
                success: function(msg) {
                    showMessages_new(msg);
                }
            });
            return false;
        });

        $download_balance_btn.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            var group_id = '<?php if(isset($group_id)) { echo $group_id;} ?>';
            $dd.load('<?php echo $this->webroot; ?>clients/download_balance',
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'width': '450px',
                        'height': 200,
                        'create': function(event, ui) {
                            $form = $('form', $dd);
                            $form.validationEngine();
                        },
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                            $form = $('form', $dd);
                            var input = $("<input>").attr("type", "hidden").attr("name", "group_id").val(group_id);
                            $form.append($(input));
                            $form.submit();
                            $(this).dialog("close");
                        }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                            $(this).dialog("close");
                        }}]

                    });
                }
            );

        });


        $reset_balance_btn.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>clients/reset_balance/' + $this.attr('hit'),
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'title': '<?php echo __('Reset Balance');?>',
                        'width': '450px',
                        'create': function(event, ui) {
                            $form = $('form', $dd);
                            $form.validationEngine();
                        },
                        'buttons': [
                            {text: "Reset", "class": "btn btn-primary", click: function() {
                                $form = $('form', $dd);
                                if (!$form.find('#balance').val()) {
                                    jGrowl_to_notyfy("Balance is required!", {theme: 'jmsg-error'});
                                    return false;
                                }
                                if (!$form.find('#begin_time').val()) {
                                    jGrowl_to_notyfy("Begin Time is required!", {theme: 'jmsg-error'});
                                    return false;
                                }
                                $form.submit();
                            }},
                            {text: "Cancel", "class": "btn btn-inverse", click: function() {
                                $dd.dialog("close");
                            }}
                        ]
                    });
                }
            );

        });
        $change_password_btn.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>clients/change_password/' + $this.attr('hit'),
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'width': '450px',
                        'height': 200,
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
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

        });

        $delete_client_btn.click(function() {
            var $this = $(this);
            var client_id = $this.attr('hit');
            var client_name = $this.attr("hit2");

            bootbox.dialog("When the carrier is removed, all the relevant CDRs will be deleted.", [{
                "label": "Confirm",
                "class": "btn-primary",
                "callback": function() {
                    var url = "<?php echo $this->webroot ?>clients/ajax_del/" + client_id + "/true";

                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        data: {'status': 'delete_client'},
                        beforeSend: function(XMLHttpRequest) {
                            $this.before('<i class="icon-spinner icon-spin icon-large" id="loading_i"></i>');//显示等待消息
                            $this.val('Processing...').attr('disabled',true);
                        },
                        success: function(data){
                            if(data.status == 1){
                                jQuery.jGrowlSuccess('The client [' + client_name + '] is deleted successfully.');
                                $this.closest('tr').remove();
                            }
                            else{
                                jQuery.jGrowlError('The client [' + client_name + '] is deleted failed!');
                            }
                            $this.siblings('.btn-cancel').click();
                            $this.val('Submit').attr('disabled',false);
                            $("#loading_i").remove();
                        }
                    });
                }
            },/* {
                "label": "Export all CDRs",
                "class": "btn-primary",
                "callback": function() {
                    var url = "<?php echo $this->webroot ?>clients/ajax_del/" + client_id;

                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        data: {'status': 'delete_client'},
                        beforeSend: function(XMLHttpRequest) {
                            $this.before('<i class="icon-spinner icon-spin icon-large" id="loading_i"></i>');//显示等待消息
                            $this.val('Processing...').attr('disabled',true);
                        },
                        success: function(data){
                            if(data.status == 1){
                                jQuery.jGrowlSuccess('The client [' + client_name + '] is deleted successfully.');
                                $this.closest('tr').remove();
                                window.open('<?php echo $this->webroot ?>cdrreports_db/summary_reports/' + client_id+'?query[output]=csv');
                            }
                            else{
                                jQuery.jGrowlError('The client [' + client_name + '] is deleted failed!');

                            }
                            $this.siblings('.btn-cancel').click();
                            $this.val('Submit').attr('disabled',false);
                            $("#loading_i").remove();
                        }
                    });
                }
            }, */{
                "label": "Cancel",
                'class':'btn-cancel',
                "callback": function() {

                }
            }]);
        });

    });
</script>



<!--    add template-->
<form action="<?php echo $this->webroot; ?>carrier_template/create_from_carrier" id="add_template_form"  method="post">
    <input type="hidden" value="" id="add_carrier_id" name="carrier_id" />
    <div id="myModal_carrier_template" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Save as Template'); ?></h3>
        </div>
        <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('Template Name')?> </td>
                    <td>
                        <input class="width220 validate[required]" name="template_name"  type="text" >
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script type="text/javascript">

    $(function() {
        $('.mass-edit').click(function(e){
            e.preventDefault();
            var ids = '';
            var selectedCount = 0;
            $.each($('tbody > tr:visible'), function(){
                if ($(this).find('input[type="checkbox"]').is(':checked')) {
                    ids += $(this).attr('value') + ',';
                    selectedCount++;
                }
            });
            if (!selectedCount) {
                jGrowl_to_notyfy("Please select at least one Client to edit!", {theme: 'jmsg-error'});
            } else {
                $(location).attr('href', '<?php echo $this->webroot; ?>clients/mass_edit/' + btoa(ids));
            }
        });

        $('.activate-client, .inactivate-client').click(function(){
            var action = '';
            var clientId = $(this).attr('data-client-id');
            var clientName = $(this).attr('data-client-name');
            var $that = $(this);
            if ($that.hasClass('inactivate-client')) {
                action = 'inactivate';
            } else {
                action = 'activate';
            }
            var confirmMessage = 'Are you sure to ' + action + ' the client [' + clientName + '] ?';
            bootbox.confirm(confirmMessage, function(result) {
                if (result) {
                    $.ajax({
                        url: '<?php echo $this->webroot ?>clients/' + action + 'Ajax',
                        type: 'post',
                        dataType: 'json',
                        data: {clientId: clientId},
                        success: function(res) {
                            if (res.success) {
                                var filter_client_type = '';
                                jGrowl_to_notyfy(res.message, {theme: res.theme});
                                if ($that.hasClass('inactivate-client')) {
                                    $that.attr('title','Activate The Client');
                                    $('a.inactivate-client').attr('title','Activate The Client');
                                    $that.switchClass('inactivate-client', 'activate-client');
                                    $that.find('i').switchClass('icon-check', 'icon-check-empty');
                                    filter_client_type = 2;
                                } else {
                                    $that.attr('title','Deactivate The Client');
                                    $('a.activate-client').attr('title','Deactivate The Client');
                                    $that.switchClass('activate-client', 'inactivate-client');
                                    $that.find('i').switchClass('icon-check-empty', 'icon-check');
                                    filter_client_type = 1;
                                }
                                location.reload();
                            }
                        }

                    });
                }
            });
        });

        $(".add_template").click(
            function () {
                var id = $(this).attr('resource');
                $("#add_carrier_id").val(id);
            });

    });

    $(document).on('DOMNodeInserted', function(){
        $('a.activate-client').attr('title','Activate The Client');
        $('a.inactivate-client').attr('title','Deactivate The Client');
    });

</script>
