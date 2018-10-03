<?php
$login_type = $session->read('login_type');
$clientType = $session->read('carrier_panel.Client.client_type');
if ($login_type == 3 && $clientType != 1) :
    $project_name = Configure::read('project_name');

    $menu_page_indexes = ['is_panel_accountsummary', 'is_panel_ratetable', 'is_panel_trunks', 'is_panel_products', 'is_panel_balance', 'is_panel_paymenthistory', 'is_panel_onlinepayment', 'is_panel_invoices', 'is_panel_cdrslist', 'is_panel_summaryreport'];
    if ($project_name == 'partition') :

        if (isset($_SESSION['carrier_panel'])) :
            $post = $_SESSION['carrier_panel']['Client'];
            ?>
            <style>
                .navbar.main .topnav > li.mega-menu .mega-sub h4{
                    font-size: 18px;
                }
            </style>
            <ul class="topnav pull-left">
                <?php
                $show_switch_ip = Configure::read('portal.show_switch_ip');
                if($show_switch_ip):
                    ?>
                    <li class="dropdown dd-1">
                        <a data-toggle="dropdown" href="javascript:void(0)" class="glyphicons circle_info"><?php __('Switch IP'); ?><i></i></a>
                        <ul class="dropdown-menu pull-right" style="z-index: 100000">
                            <?php foreach ($switch_server as $switch_server_ip): ?>
                                <li><span class="details"><a class="text-regular"><?php echo $switch_server_ip; ?><i></i></a></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif;?>
                <li class="dropdown"><a href="<?php echo $this->webroot ?>clients/carrier"class="glyphicons dashboard"><i></i> Dashboard</a></li>

                <?php
                // Menu hide/show
                    $show_menu = false;
                    foreach($menu_page_indexes as $index):
                        if(!empty($post[$index])):
                            $show_menu = true; break;
                        endif;
                    endforeach;
                ?>
                <?php if($show_menu):?>
                <li class="mega-menu">
                    <a href="#" class="glyphicons sheriffs_star"><i></i> <?php __('Menu') ?></a>
                    <div class="mega-sub">
                        <div class="mega-sub-inner">
                            <div class="row-fluid">

                                <?php if (!empty($post['is_panel_accountsummary']) || !empty($post['is_panel_ratetable']) || !empty($post['is_panel_trunks']) || !empty($post['is_panel_products'])): ?>
                                    <div class="span2">
                                        <h4><i class="icon-cogs icon-fixed-width text-primary"></i> <?php __('Management') ?></h4>
                                        <ul class="icons-ul">
                                            <?php if (!empty($post['is_panel_accountsummary'])) : ?>
                                                <li><a href="<?php echo $this->webroot ?>clients/carrier/true"><span class="icon-li icon-arrow-right"></span> <?php __('Account Summary')?></a></li>
                                            <?php endif; ?>



<!--                                            --><?php //if (!empty($post['is_panel_ratetable'])): ?>
<!--                                                <li><a href="--><?php //echo $this->webroot ?><!--clientrates/view_rate"><span class="icon-li icon-arrow-right"></span> --><?php //__('RateTable') ?><!-- </a></li>-->
<!--                                            --><?php //endif ?>

                                            <?php if (!empty($post['is_panel_trunks'])): ?>
                                                <?php if (Configure::read('did.enable') && $post['client_type'] != null): ?>
                                                    <li ><a href="<?php echo $this->webroot ?>did/orders/ingress_trunk"><span class="icon-li icon-arrow-right"></span> <?php __('Trunks') ?> </a></li>
                                                <?php else: ?>
                                                    <li <?php echo (!$_SESSION['ingress_cnt']) ? 'class = "no-event"' : '';?>><a href="<?php echo $this->webroot ?>clients/view_egress"><span class="icon-li icon-arrow-right"></span> <?php __('Trunks') ?> </a></li>
                                                <?php endif; ?>
                                            <?php endif; ?>


                                            <?php if (!empty($post['is_panel_products'])): ?>
                                                <li><a href="<?php echo $this->webroot ?>product_management/carrier_product_list"><span class="icon-li icon-arrow-right"></span> <?php __('Termination product') ?> </a></li>
                                            <?php endif; ?>


                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($post['is_panel_balance']) || !empty($post['is_panel_paymenthistory']) || (Configure::read('payline.enable_paypal') && !empty($post['is_panel_onlinepayment'])) || !empty($post['is_panel_invoices'])): ?>
                                    <div class="span2">
                                        <h4><i class="icon-calendar icon-fixed-width text-primary"></i> <?php __('Billing') ?></h4>
                                        <ul class="icons-ul">

                                            <?php if (!empty($post['is_panel_paymenthistory'])): ?>
                                                <li><a href="<?php echo $this->webroot ?>clients/clients_payment/"><span class="icon-li icon-arrow-right"></span> <?php __('Payment History')?></a></li>
                                            <?php endif ?>

                                            <?php if (Configure::read('payline.enable_paypal') && !empty($post['is_panel_onlinepayment'])): ?>
                                                <li><a target="_blank" href="<?php echo $this->webroot ?>clients/client_pay"><span class="icon-li icon-arrow-right"></span> <?php __('Online Payment') ?> </a></li>
                                            <?php endif; ?>

                                            <?php if (!empty($post['is_panel_invoices'])): ?>
                                                <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/view"><span class="icon-li icon-arrow-right"></span> <?php __('Invoices') ?> </a></li>
                                            <?php endif; ?>

                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($post['is_panel_cdrslist']) || !empty($post['is_panel_summaryreport'])): ?>
                                    <div class="span2">
                                        <h4><i class="icon-list-alt icon-fixed-width text-primary"></i> <?php __('Reports') ?></h4>
                                        <ul class="icons-ul">
                                            <?php if (!empty($post['is_panel_cdrslist'])) : ?>
                                                <li><a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports"><span class="icon-li icon-arrow-right"></span> <?php __('CDRsList') ?></a></li>
                                            <?php endif ?>
                                            <?php if (!empty($post['is_panel_summaryreport'])) : ?><li>
                                                <a href="<?php echo $this->webroot ?>reports_db/user_summary"><span class="icon-li icon-arrow-right"></span> <?php __('Summary Report') ?></a></li>
                                            <?php endif ?>
                                            <?php if (!empty($post['is_panel_cid_blocking'])) : ?><li>
                                                <a href="<?php echo $this->webroot ?>cid_blocking/index"><span class="icon-li icon-arrow-right"></span> <?php __('CID Blocking') ?></a></li>
                                            <?php endif ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

<!--                                --><?php //if (!empty($post['is_panel_sippacket'])): ?>
<!--                                    <div class="span2">-->
<!--                                        <h4><i class="icon-link icon-fixed-width text-primary"></i> --><?php //__('SIP PACKET Search') ?><!--</h4>-->
<!--                                        <ul class="icons-ul">-->
<!--                                            --><?php //if (!empty($post['is_panel_sippacket'])) : ?>
<!--                                                <li><a href="--><?php //echo $this->webroot ?><!--cdrreports_db/sip_packet"><span class="icon-li icon-arrow-right"></span> --><?php //__('SIP PACKET Search')?><!--</a></li>-->
<!--                                            --><?php //endif ?>
<!---->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                --><?php //endif; ?>

<!--
                                <?php if (Configure::read('did.enable') && $post['client_type'] != null): ?>
                                    <div class="span2">
                                        <h4><i class="icon-exchange icon-fixed-width text-primary"></i> <?php __('DID Management') ?></h4>
                                        <ul class="icons-ul">
                                            <li><a href="<?php echo $this->webroot ?>did/did_portal/my_did"><span class="icon-li icon-arrow-right"></span> <?php __('My DID Number')?></a></li>
                                            <li><a href="<?php echo $this->webroot ?>did/did_request/index"><span class="icon-li icon-arrow-right"></span> <?php __('Order New DID')?></a></li>
                                            <li><a href="<?php echo $this->webroot ?>did/did_assign/listing"><span class="icon-li icon-arrow-right"></span> <?php __('My Orders')?></a></li>
                                            <li><a href="<?php echo $this->webroot ?>did/did_assign/listing"><span class="icon-li icon-arrow-right"></span> <?php __('Trunk Group')?></a></li>
                                            <li><a href="<?php echo $this->webroot ?>did/did_assign/listing"><span class="icon-li icon-arrow-right"></span> <?php __('Call Records')?></a></li>
                                            <li><a href="<?php echo $this->webroot ?>did/did_assign/listing"><span class="icon-li icon-arrow-right"></span> <?php __('Reports')?></a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                -->
                            </div>
                        </div>
                    </div>
                </li>
                <?php endif;?>
            </ul>
            <?php
        endif;
    endif;
endif;
?>