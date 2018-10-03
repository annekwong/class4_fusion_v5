<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/commission_history">
        <?php __('Agent') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/commission_history">
        <?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">

</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <?php if($_SESSION['login_type'] == 1): ?>
                        <?php echo $form->input('agent_name',array('type' => 'select','options' => $agents,
                            'selected' =>$appCommon->_get('data.agent_name'))); ?>
                    <?php endif; ?>
                    <?php echo $form->input('amount',array('type' => 'select','options' => array('All','Non-Zero'),
                        'selected' =>$appCommon->_get('data.amount'),'default' => 1,'class' => 'width120')); ?>
                    <?php echo $form->input('status',array('type' => 'select','options' => array('All','Finished','Not finished'),
                        'selected' =>$appCommon->_get('data.status'),'default' => 0,'class' => 'width120')); ?>
                    <?php echo $this->element("common/log_query_datetime",array('datetime' => false)); ?>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary table_page_num">
                    <thead>
                    <tr>
                        <th><?php __('Agent Name') ?></th>
                        <th><?php __('Carrier') ?></th>
                        <th><?php echo $appCommon->show_order('AgentCommissionHistory.create_date', __('Created Date', true)) ?></th>
                        <th><?php __('Period') ?></th>
                        <th><?php __('Date') ?></th>
                        <th><?php __('Commission') ?></th>
                        <th><?php echo $appCommon->show_order('AgentCommissionHistory.amount', __('Amount', true)) ?></th>
                        <th><?php echo $appCommon->show_order('commission', __('Paid Amount', true)) ?></th>
                        <?php if($_SESSION['login_type'] == 1): ?>
                            <th><?php __('Action'); ?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>

                        <?php
                        $pay_amount = 0.00;
                        foreach ($item['payment'] as $payment){
                            $pay_amount += $payment['amount'];
                        }
                        $no_pay = bcsub($item['AgentCommissionHistory']['amount'],$pay_amount);
                        ?>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="switch_a" data-value="<?php echo $item['AgentCommissionHistory']['history_id'];?>"><i class="icon-plus" style="margin-right: 20px"></i></a>
                                <?php echo isset($agents[$item['AgentCommissionHistory']['agent_id']]) ? $agents[$item['AgentCommissionHistory']['agent_id']] : '--'; ?>
                            </td>
                            <td>
                                <a title="<?php __('Count'); ?>" href="javascript:void(0)" class="switch_count">
                                    <?php echo count($item['detail']); ?>
                                </a>
                            </td>
                            <td><?php echo $item['AgentCommissionHistory']['create_date']; ?></td>
                            <td><?php echo $item['AgentCommissionHistory']['start_time'] . '  --  ' . $item['AgentCommissionHistory']['end_time']; ?></td>
                            <td><?php echo $item['AgentCommissionHistory']['total_date'].__('Day',true).'(s)'; ?></td>
                            <td>--</td>
                            <td><?php echo $item['AgentCommissionHistory']['amount']; ?></td>
                            <td>
                                <a title="<?php __('Show Payment'); ?>" href="javascript:void(0)" class="switch_payment_a" data-value="<?php echo $item['AgentCommissionHistory']['history_id'];?>">
                                    <?php echo round($pay_amount,2); ?>
                                </a>
                            </td>
                            <?php if($_SESSION['login_type'] == 1): ?>
                                <td>
                                    <?php if ($no_pay > 0): ?>
                                        <a href="#myModal_addPayment" class="add_payment" no_pay="<?php echo $no_pay; ?>" data-value="<?php echo $item['AgentCommissionHistory']['history_id']; ?>"
                                           data-toggle="modal" title="<?php __('add Payment'); ?>">
                                            <i class="icon-usd"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>

                        <?php foreach ($item['detail'] as $detail): ?>
                            <tr class="switch_tr_<?php echo $detail['history_id']; ?> hide">
                                <td class="center"><i class="icon-arrow-right"></i></td>
                                <td><?php echo isset($clients[$detail['client_id']]) ? $clients[$detail['client_id']] : '--'; ?></td>
                                <td>--</td>
                                <td><?php echo $detail['start_time'] . '  --  ' . $detail['end_time']; ?></td>
                                <td><?php echo $detail['total_date'].__('Day',true).'(s)'; ?></td>
                                <td><?php echo $detail['commission']; ?>%</td>
                                <td><?php echo round($detail['client_cost']*$detail['commission']*0.01,2); ?></td>
                                <td>--</td>
                                <td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="payment_tr_<?php echo $detail['history_id']; ?> hide payment_tr">
                            <td colspan="9">
                                <div>
                                    <table class="table">
                                        <tr>
                                            <th><a href="javascript:void(0)" class="close_payment_tr"><i class="icon-minus"></i></a></th>
                                            <th><?php __('Amount'); ?></th>
                                            <th><?php __('Note'); ?></th>
                                            <th><?php __('Created by'); ?></th>
                                            <th><?php __('Created on'); ?></th>
                                        </tr>
                                        <?php foreach ($item['payment'] as $payment): ?>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $payment['amount']; ?></td>
                                                <td title="<?php echo $payment['note']; ?>">
                                                    <?php echo strlen($payment['note']) > 20 ?  substr($payment['note'],0,20).'...' : $payment['note']; ?>
                                                </td>
                                                <td><?php echo $payment['create_by']; ?></td>
                                                <td><?php echo $payment['create_on']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<form action="<?php echo $this->webroot; ?>agent/add_payment" method="post">
    <div id="myModal_addPayment" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Add Payment'); ?></h3>
        </div>
        <div class="modal-body">
            <div class="widget-body separator">
                <input type="hidden" name="payment_id" class="payment_id" />
                <table class="table table-bordered">
                    <tr>
                        <td class="right"><?php __('Amount'); ?>:</td>
                        <td>
                            <input type="text" class="amount_input" name="payment_amount" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Note'); ?>:</td>
                        <td>
                            <textarea name="payment_note"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script type="text/javascript">
    $(function() {
        $('.switch_count').click(function(){
            $(this).closest('tr').find('.switch_a').click();
        });

        $('.switch_a').click(function(){
            var $this = $(this);
            var item = '.switch_tr_' + $this.data('value');
//           console.log(item);
            $(item).slideToggle('600');
            $.sleep(400);
            $this.find('i').toggleClass('icon-minus');
        });

        $('.switch_payment_a').click(function(){
            var $this = $(this);
            var item = '.payment_tr_' + $this.data('value');
            $(item).slideToggle('600');
            $.sleep(400);
//            $this.find('i').toggleClass('icon-minus');
        });
        $('.close_payment_tr').click(function(){
            $(this).closest('.payment_tr').hide();
        });

        $(".add_payment").click(function(){
            $('#myModal_addPayment').find('.payment_id').val($(this).data('value'));
            var no_pay = $(this).attr('no_pay');
            $('#myModal_addPayment').find('.amount_input').addClass('validate[required,custom[number],max['+no_pay+']]');
        });
    });
</script>
