<style>
        /*.myModal_payment_response{*/
            /*width:521px;*/
        /*}*/
        .myModal_payment_response table {
            width: 100%;
        }
        .myModal_payment_response thead, .myModal_payment_response tbody, .myModal_payment_response tr, .myModal_payment_response td, .myModal_payment_response th {
            display: block;
            text-align:center;
         }
        .myModal_payment_response tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }
        .myModal_payment_response thead th {
            height: 30px;

            /*text-align: left;*/
        }
        .myModal_payment_response tbody {
            height: 180px;
            overflow-y: auto;
        }
        .myModal_payment_response thead {
            /* fallback */
        }
        .myModal_payment_response tbody td, .myModal_payment_response thead th {
            width: 45%;
            float: left;
        }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>payment_history">
        <?php if ($login_type == 3): ?>
            <?php echo __('Billing', true); ?>
        <?php else: ?>
            <?php echo __('Log', true); ?>
        <?php endif; ?>
        </a>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>payment_history">
        <?php echo __('Auto Payment Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Payment Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if($_SESSION['login_type'] == 3): ?>
            <div class="widget-head">
                <ul class="tabs">
                    <?php if(!empty($pay_type_arr[1])): ?>
                        <li>
                            <a id="paypal" class="glyphicons usd" href="<?php echo $this->webroot; ?>clients/client_pay">
                                <?php __('Paypal') ?>
                                <i></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(isset($pay_type_arr[2])): ?>

                        <li>

                            <a id="stripe" class="glyphicons usd" href="<?php echo $this->webroot; ?>clients/client_pay/3">
                                <?php __('Stripe') ?>
                                <i></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="active">
                        <a target="_blank" class="glyphicons book_open" href="<?php echo $this->webroot; ?>payment_history">
                            <?php __('Auto Payment Log')?>
                            <i></i>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
        <?php if($_SESSION['login_type'] == 1): ?>
            <?php if(strcmp($this->params['controller'],'payment_history')): ?>
            <div class="widget-head">
                <ul class="tabs">
                    <li>
                        <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/incoming">
                            <i></i><?php echo __('Received', true); ?>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/outgoing">
                            <i></i><?php echo __('Sent', true); ?>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot; ?>transactions/payment/upload">
                            <i></i><?php echo __('Upload', true); ?>
                        </a>
                    </li>
                    <li class="active">
                        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot; ?>payment_history">
                            <i></i><?php echo __('Auto Payment Log', true); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        <?php endif; ?>


        <div class="widget-body">
            <div class="filter-bar">

                <form action="" method="get">
                    <!-- Filter -->

                    <!-- // Filter END -->
                    <!-- Filter -->
                    <?php if($_SESSION['login_type'] != 3): ?>
                        <div>
                            <label><?php __('Client')?>:</label>
                            <select  name="client_id" class="in-select select" >
                                <option value=""></option>
                                <?php foreach ($client as $key => $client_item)
                                { ?>
                                    <option value="<?php echo $key ?>" <?php if (isset($get_data['client_id']) && !strcmp($key, $get_data['client_id']))
                                    { ?>selected="selected"<?php } ?> ><?php echo $client_item ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php endif;?>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Submitted Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php if(isset($get_data['submitted_time_start'])){ echo $get_data['submitted_time_start'];} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="submitted_time_start">
                        --
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php if(isset($get_data['submitted_time_end'])){ echo $get_data['submitted_time_end'];} ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="submitted_time_end">
                    </div>
                    <!-- Filter -->

                    <div>
                        <label><?php __('Status')?>:</label>
                        <select  name="status" class="in-select select input-small" >
                            <option value=""></option>
                            <option value="0" <?php if (isset($get_data['status']) && !strcmp('0', $get_data['status'])){ ?>selected="selected"<?php } ?>><?php __('Requested')?></option>
                            <option value="1" <?php if (isset($get_data['status']) && !strcmp('1', $get_data['status'])){ ?>selected="selected"<?php } ?>><?php __('Failed')?></option>
                            <option value="2" <?php if (isset($get_data['status']) && !strcmp('2', $get_data['status'])){ ?>selected="selected"<?php } ?>><?php __('Succeed')?></option>
                        </select>
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br /><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
            <!-- <div class="overflow_x"> -->
                <?php $login_type = $_SESSION['login_type']; ?>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary dynamicTable">
                    <thead>
                    <tr>
                        <?php if($login_type !=3){?>
                        <th><?php echo $appCommon->show_order('client.name', __('Client', true)) ?></th>
                        <?php }?>
                        <th><?php echo $appCommon->show_order('PaymentHistory.created_time', __('Requested Time', true)) ?></th>
                        <?php if($login_type !=3){?>
                        <th><?php echo $appCommon->show_order('PaymentHistory.modified_time', __('Response Time', true)) ?></th>
                         <?php }?>
                        <th><?php __('Method')?></th>
                        <th><?php echo $appCommon->show_order('PaymentHistory.chargetotal', __('Charge Total', true)) ?></th>
                        <th><?php __('Fee')?></th>
                        <th><?php __('Service Charge')?></th>
                        <th><?php echo $appCommon->show_order('PaymentHistory.status', __('Status', true)) ?></th>
                        <th><?php $appCommon->show_order('PaymentHistory.transaction_id', __('Transaction ID')) ?></th>
                         <?php if($login_type !=3){?>
                        <th><?php $appCommon->show_order('PaymentHistory.paypal_id', __('Paypal ID')) ?></th>
                        <!--th><?php echo $appCommon->show_order('PaymentHistory.cardnumber', __('Card Number', true)) ?></th>
                        <th><?php echo $appCommon->show_order('PaymentHistory.cardexpmonth', __('Card Expire Month', true)) ?></th>
                        <th><?php echo $appCommon->show_order('PaymentHistory.cardexpyear', __('Card Expire Year', true)) ?></th-->
                        <th><?php echo $appCommon->show_order('PaymentHistory.return_code', __('Return Code', true)) ?></th>
                        <th><?php echo $appCommon->show_order('PaymentHistory.error', __('Error Information', true)) ?></th>
                        <?php }?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach ($this->data as $item): ?>
                        <tr>
                            <?php if($login_type !=3){?>
                            <td><?php echo $item['client']['name']; ?></td>
                            <?php }?>
                            <td><?php echo $item['PaymentHistory']['created_time']; ?></td>
                             <?php if($login_type !=3){?>
                            <td><?php echo empty($item['PaymentHistory']['modified_time']) ? "--" : $item['PaymentHistory']['modified_time']; ?></td>
                            <?php }?>
                            <td><?php echo $method[$item['PaymentHistory']['method']]; ?> </td>
                            <td><?php echo $item['PaymentHistory']['chargetotal']; ?></td>
                            <td><?php echo $item['PaymentHistory']['fee']; ?></td>
                            <td><?php echo $item['PaymentHistory']['charge_amount']; ?></td>
                            <td><?php echo $status[$item['PaymentHistory']['status']]; ?></td>
                            <td>
                            <?php echo empty($item['PaymentHistory']['transaction_id']) ? "--" : $item['PaymentHistory']['transaction_id']; ?>
                            <?php if(isset($item['PaymentHistory']['response']) && $item['PaymentHistory']['response']):?>
                                <a data-toggle="modal" style=" margin-left: 10px;" href="#myModal_payment_response-<?php echo $item['PaymentHistory']['id']; ?>" title="<?php __('View Response'); ?>">
                                    <i class="icon-list-alt"></i>
                                </a>
                            <?php endif;?>
                            </td>
                            <?php if($login_type !=3){?>
                            <!--td><?php echo empty($item['PaymentHistory']['paypal_id']) ? "--" : $item['PaymentHistory']['paypal_id']; ?></td>
                            <td><?php echo empty($item['PaymentHistory']['cardnumber']) ? "--" : $item['PaymentHistory']['cardnumber']; ?></td>
                            <td><?php echo empty($item['PaymentHistory']['cardexpmonth']) ? "--" : $item['PaymentHistory']['cardexpmonth']; ?></td-->
                            <td><?php echo empty($item['PaymentHistory']['cardexpyear']) ? "--" : $item['PaymentHistory']['cardexpyear']; ?></td>
                            <td><?php echo $item['PaymentHistory']['return_code']; ?></td>
                            <td><?php echo $item['PaymentHistory']['error']; ?></td>
                             <?php }?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <!-- </div> -->
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php foreach ($this->data as $item): ?>
<?php if($item['PaymentHistory']['response']): ?>
<div id="myModal_payment_response-<?php echo $item['PaymentHistory']['id']; ?>" class="modal hide myModal_payment_response">
<div class="modal-body">
        <table class="table table-bordered table-primary">
            <thead>
            <tr>
                <th><?php __('Key'); ?></th>
                <th><?php __('Value'); ?></th>
            </tr>
            </thead>
            <tbody class=" webkit-scrollbar ">
            <?php foreach ( $item['PaymentHistory']['response'] as $key =>$value): ?>
                <tr>
                    <td><div><?php echo $key; ?></div></td>
                    <td><div><?php echo $value; ?></div></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
</div>
</div>
<?php endif; ?>
<?php endforeach; ?>