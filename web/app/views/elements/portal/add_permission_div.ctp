
<div class="row">
    <div class="span3">
        <div><h4><?php echo htmlspecialchars( __('Management',true) )?></h4></div>
        <div>
            <ul>
                <li><?php
                    isset($check_data['is_panel_accountsummary']) && empty($check_data['is_panel_accountsummary']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_accountsummary', array('checked' => $au))
                    ?>
                    <?php echo __('Account Summary') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_ratetable']) && empty($check_data['is_panel_ratetable']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_ratetable', array('checked' => $au))
                    ?>
                    <?php echo __('RateTable') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_trunks']) && empty($check_data['is_panel_trunks']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_trunks', array('checked' => $au))
                    ?>
                    <?php echo __('Trunks') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_products']) && empty($check_data['is_panel_products']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_products', array('checked' => $au))
                    ?>
                    <?php echo __('Products') ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="span3">
        <div><h4><?php echo htmlspecialchars( __('Billing',true) )?></h4></div>
        <div>
            <ul>
                <li><?php
                    isset($check_data['is_panel_balance']) && empty($check_data['is_panel_balance']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_balance', array('checked' => $au))
                    ?>
                    <?php __('Balance') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_paymenthistory']) && empty($check_data['is_panel_paymenthistory']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_paymenthistory', array('checked' => $au))
                    ?>
                    <?php __('Payment History') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_onlinepayment']) && empty($check_data['is_panel_onlinepayment']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_onlinepayment', array('checked' => $au))
                    ?>
                    <?php __('Online Payment') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_invoices']) && empty($check_data['is_panel_invoices']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_invoices', array('checked' => $au))
                    ?>
                    <?php __('Invoices') ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="span3">
        <div><h4><?php echo htmlspecialchars( __('Reports',true) )?></h4></div>
        <div>
            <ul>
                <li>
                    <?php
                    isset($check_data['is_panel_cdrslist']) && empty($check_data['is_panel_cdrslist']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_cdrslist', array('checked' => $au))
                    ?>
                    <?php __('CDRs List') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_summaryreport']) && empty($check_data['is_panel_summaryreport']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_summaryreport', array('checked' => $au))
                    ?>
                    <?php __('Summary Report') ?>
                </li>
                <li>
                    <?php
                    isset($check_data['is_panel_cid_blocking']) && empty($check_data['is_panel_cid_blocking']) ? $au = 'false' : $au = 'checked';
                    echo $form->checkbox('is_panel_cid_blocking', array('checked' => $au))
                    ?>
                    <?php __('CID Blocking') ?>
                </li>
            </ul>
        </div>
    </div>
<!--    <div class="span3">-->
<!--        <div><h4>--><?php //echo htmlspecialchars( __('SIP PACKET Search',true) )?><!--</h4></div>-->
<!--        <div>-->
<!--            <ul>-->
<!--                <li>-->
<!--                    --><?php
//                    isset($check_data['is_panel_sippacket']) && empty($check_data['is_panel_sippacket']) ? $au = 'false' : $au = 'checked';
//                    echo $form->checkbox('is_panel_sippacket', array('checked' => $au))
//                    ?>
<!--                    --><?php //__('SIP PACKET Search') ?>
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
    <?php if (isset($have_did) && $have_did): ?>
        <div class="span3">
            <div><h4><?php echo htmlspecialchars( __('DID Management',true) )?></h4></div>
            <div>
                <ul>
                    <li>
                        <?php
                        isset($check_data['is_panel_mydid']) && empty($check_data['is_panel_mydid']) ? $au = 'false' : $au = 'checked';
                        echo $form->checkbox('is_panel_mydid', array('checked' => $au))
                        ?>
                        <?php __('My DID') ?>
                    </li>
                    <li>
                        <?php
                        isset($check_data['is_panel_didrequest']) && empty($check_data['is_panel_didrequest']) ? $au = 'false' : $au = 'checked';
                        echo $form->checkbox('is_panel_didrequest', array('checked' => $au))
                        ?>
                        <?php __('DID Request') ?>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>