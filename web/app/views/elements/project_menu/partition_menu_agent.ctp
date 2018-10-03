<?php
$login_type = $session->read('login_type');
if ($login_type == 2) :
    ?>
        <style>
            .navbar.main .topnav > li.mega-menu .mega-sub h4{
                font-size: 18px;
            }
        </style>
        <ul class="topnav pull-left">
            <li class="dropdown"><a href="<?php echo $this->webroot ?>agent_portal/agent_dashboard"class="glyphicons dashboard"><i></i> Dashboard</a></li>
            <li class="mega-menu">
                <a href="javascript:void(0)" class="glyphicons sheriffs_star"><i></i> <?php __('Menu') ?></a>
                <div class="mega-sub">
                    <div class="mega-sub-inner">
                        <div class="row-fluid">

                                <div class="span2">
                                    <h4><i class="icon-male icon-fixed-width text-primary"></i> <?php __('Client') ?></h4>
                                    <ul class="icons-ul">
                                        <li><a href="<?php echo $this->webroot ?>agent_portal/client_list"><span class="icon-li icon-arrow-right"></span> <?php __('Client List')?></a></li>
                                        <li><a href="<?php echo $this->webroot ?>agent_portal/products"><span class="icon-li icon-arrow-right"></span> <?php __('Products')?></a></li>
<!--                                        <li><a href="--><?php //echo $this->webroot ?><!--transactions/payment"><span class="icon-li icon-arrow-right"></span> --><?php //__('Client Payment')?><!--</a></li>-->
<!--                                        <li><a href="--><?php //echo $this->webroot ?><!--payment_history"><span class="icon-li icon-arrow-right"></span> --><?php //__('Client Transaction')?><!--</a></li>-->
                                    </ul>
                                </div>
                                <div class="span2">
                                    <h4><i class="icon-calendar icon-fixed-width text-primary"></i> <?php __('Statistics') ?></h4>
                                    <ul class="icons-ul">

                                            <li><a href="<?php echo $this->webroot ?>reports_db/agent_summary"><span class="icon-li icon-arrow-right"></span> <?php __('Detail Traffic Report')?></a></li>

                                            <li><a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports"><span class="icon-li icon-arrow-right"></span> <?php __('CDR Search')?></a></li>

<!--                                            <li><a  href="--><?php //echo $this->webroot ?><!--cdrreports_db/sip_packet"><span class="icon-li icon-arrow-right"></span> --><?php //__('PCAP Search') ?><!-- </a></li>-->

                                            <!--li><a href="<?php echo $this->webroot ?>agent/commission_history"><span class="icon-li icon-arrow-right"></span> <?php __('Commission History') ?> </a></li-->
                                    </ul>
                                </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <?php
endif;
?>