<!--导入所有reoprt页面的input和select样式文件-->
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>mutual_statements/summary_reports/">
        <?php echo __('Finance', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>mutual_statements/summary_reports/">
        <?php echo __('Past Due Summary') ?></a></li>
</ul>

<?php echo $this->element('magic_css_three'); ?>
<div id="title">

    <!--    <ul id="title-search">
            <li>
    <?php //********************模糊搜索**************************?>
                
            </li>
            <li title="<?php //echo __('advancedsearch')      ?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
        </ul>
        <ul id="title-menu">
    <?php //if (isset($extraSearch)) { ?>
                <li> <a class="link_back" href="<?php //echo $extraSearch      ?>" onClick="history.go(-1)"> <img width="16" height="16"  alt="" src="<?php //echo $this->webroot      ?>images/icon_back_white.png"> &nbsp;<?php //echo __('goback')      ?> </a> </li>
    <?php //} ?>
        </ul>-->

</div>
<div class="separator bottom"></div>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Past Due Summary') ?></h4>
</div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div id="container">

                <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                    <div style="margin:0px auto; text-align:center;">
                        <form method="get" name="myform">
                            <select name="overdue">
                                <option value="0" <?php echo!isset($_GET['overdue']) || $_GET['overdue'] == 0 ? 'selected="selected"' : ''; ?>><?php __('Overdue > 0')?></option>
                                <option value="1" <?php echo isset($_GET['overdue']) && $_GET['overdue'] == 1 ? 'selected="selected"' : ''; ?>><?php __('No Overdue')?></option>
                                <option value="2" <?php echo isset($_GET['overdue']) && $_GET['overdue'] == 2 ? 'selected="selected"' : ''; ?>><?php __('All')?></option>
                            </select>
                            &nbsp;&nbsp;<?php  __('Client Name'); ?>:&nbsp;&nbsp;
<!--                            <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" placeholder="--><?php //echo __('namesearch') ?><!--" value="--><?php //if (!empty($search)) echo $search; ?><!--" name="search">-->
                            <select name="search">
                            <?php
                                foreach ($clientsList as $item) {
                            ?>
                                    <option value="<?php echo $item['Client']['name']; ?>" <?php if(isset($search) && $search == $item['Client']['name']) echo 'selected'; ?>><?php echo $item['Client']['name']; ?></option>
                            <?php
                                }
                            ?>
                            </select>

                            <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
                        </form>
                    </div>
                </fieldset>
                <div class="separator"></div>
                <?php if(isset($p)): ?>

                    <?php //*********************  条件********************************?>
                    <fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
                        <form action="" method="get">
                            <input name="advsearch" type="hidden"/>
                            <table style="width:100%">
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                    </fieldset>
                    <!-- <div id="toppage"></div>-->
                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    if (empty($mydata))
                    {
                        ?>
                        <div class="msg center">
                            <h2><?php echo __('no_data_found', true); ?></h2>
                        </div>
                    <?php
                    }
                    else
                    {
                        ?>

                        <div class="clearfix"></div>
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>

                            <tr>
                                <th rowspan="2" style="min-width: 110px;" ><?php echo $appCommon->show_order('name', __('Client Name', true)); ?></th>

                                <th rowspan="2"><?php echo $appCommon->show_order('balance', __('Balance', true)); ?></th>
                                <th colspan="5"><?php echo __('Carrier Past Due', true); ?></th>
<!--                                <th colspan="5">--><?php //echo __('Outgoing Overdue', true); ?><!--</th>-->


                                <th  rowspan="2"><?php echo __('Action', true); ?></th>
                            </tr>

                            <tr>
                                <th><?php echo __('Total', true); ?></th>
                                <th><?php echo __('7 days', true); ?></th>
                                <th><?php echo __('15 days', true); ?></th>
                                <th><?php echo __('30 days', true); ?></th>
                                <th><?php echo __('Over 30 days', true); ?></th>

<!--                                <th>--><?php //echo __('Total', true); ?><!--</th>-->
<!--                                <th>--><?php //echo __('7 days overdue', true); ?><!--</th>-->
<!--                                <th>--><?php //echo __('15 days overdue', true); ?><!--</th>-->
<!--                                <th>--><?php //echo __('30 days overdue', true); ?><!--</th>-->
<!--                                <th>--><?php //echo __('> 30 days overdue', true); ?><!--</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i = 0; $i < $loop; $i++)
                            {

                                $payment_tmp = empty($mydata[$i][0]['last_payment']) ? array('', '') : explode('|', $mydata[$i][0]['last_payment']);
                                $invoice_tmp = empty($mydata[$i][0]['last_invoice']) ? array('', '') : explode('|', $mydata[$i][0]['last_invoice']);
                                ?>
                                <tr>
                                    <td><?php echo $mydata[$i][0]['name']; ?></td>
                                    <td><?php
                                        if ($mydata[$i][0]['balance'] < 0)
                                            printf("(%.3f) USD", abs($mydata[$i][0]['balance']));
                                        else
                                            printf("%.3f USD", $mydata[$i][0]['balance']);
                                        ?> </td>

                                    <td><?php
                                        if ($mydata[$i][0]['incoming_overdue'] == 0)
                                        {
                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
                                        }
                                        else
                                        {
                                            printf("%.3f USD", $mydata[$i][0]['incoming_overdue']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($mydata[$i][0]['incoming_overdue_7'] == 0)
                                        {
                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
                                        }
                                        else
                                        {
                                            printf("%.3f USD", $mydata[$i][0]['incoming_overdue_7']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($mydata[$i][0]['incoming_overdue_15'] == 0)
                                        {
                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
                                        }
                                        else
                                        {
                                            printf("%.3f USD", $mydata[$i][0]['incoming_overdue_15']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($mydata[$i][0]['incoming_overdue_30'] == 0)
                                        {
                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
                                        }
                                        else
                                        {
                                            printf("%.3f USD", $mydata[$i][0]['incoming_overdue_30']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($mydata[$i][0]['incoming_overdue_gt_30'] == 0)
                                        {
                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
                                        }
                                        else
                                        {
                                            printf("%.3f USD", $mydata[$i][0]['incoming_overdue_gt_30']);
                                        }
                                        ?></td>


<!--                                    <td>--><?php
//                                        if ($mydata[$i][0]['outgoing_overdue'] == 0)
//                                        {
//                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
//                                        }
//                                        else
//                                        {
//                                            printf("%.3f USD", $mydata[$i][0]['outgoing_overdue']);
//                                        }
//                                        ?><!--</td>-->
<!--                                    <td>--><?php
//                                        if ($mydata[$i][0]['outgoing_overdue_7'] == 0)
//                                        {
//                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
//                                        }
//                                        else
//                                        {
//                                            printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_7']);
//                                        }
//                                        ?><!--</td>-->
<!--                                    <td>--><?php
//                                        if ($mydata[$i][0]['outgoing_overdue_15'] == 0)
//                                        {
//                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
//                                        }
//                                        else
//                                        {
//                                            printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_15']);
//                                        }
//                                        ?><!--</td>-->
<!--                                    <td>--><?php
//                                        if ($mydata[$i][0]['outgoing_overdue_30'] == 0)
//                                        {
//                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
//                                        }
//                                        else
//                                        {
//                                            printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_30']);
//                                        }
//                                        ?><!--</td>-->
<!--                                    <td>--><?php
//                                        if ($mydata[$i][0]['outgoing_overdue_gt_30'] == 0)
//                                        {
//                                            echo "<span style='color: #9F9F9F;'>0 USD</span>";
//                                        }
//                                        else
//                                        {
//                                            printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_gt_30']);
//                                        }
//                                        ?><!--</td>-->
                                    <!--<td><a href="<?php echo $this->webroot; ?>mutual_statements/detail_report/<?php echo $mydata[$i][0]['client_id']; ?>">detail</a></td>-->
                                    <td>
                                        <a title="<?php __('Detail'); ?>" href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_egress_detail/<?php echo base64_encode($mydata[$i][0]['client_id']); ?>">
                                            <i class="icon-list"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>














                        <!--<table class="list">
                                  <thead>
                                    <tr>
                                     
                                      <td><?php echo $appCommon->show_order('name', __('Client Name', true)); ?></td>
                                      <td><?php echo __('Last payment', true); ?></td>
                                      <td><?php echo __('Last Invoice', true); ?></td>
                                      <td><?php echo $appCommon->show_order('name', __('Balance', true)); ?></td>
                                      <td><?php echo __('Overdue', true); ?></td>
                                      <td><?php echo __('7 days overdue', true); ?></td>
                                      <td><?php echo __('15 days overdue', true); ?></td>
                                      <td><?php echo __('30 days overdue', true); ?></td>
                                      <td><?php echo __('> 30 days overdue', true); ?></td>
                                      <td><?php echo __('Detail', true); ?></td>
                                    </tr>
                                  </thead>
                                  <tbody>
                    <?php
                        for ($i = 0; $i < $loop; $i++)
                        {
                            $payment_tmp = empty($mydata[$i][0]['last_payment']) ? array('', '') : explode('|', $mydata[$i][0]['last_payment']);
                            $invoice_tmp = empty($mydata[$i][0]['last_invoice']) ? array('', '') : explode('|', $mydata[$i][0]['last_invoice']);
                            ?>
                                                                <tr>
                                                                  <td><?php echo $mydata[$i][0]['name']; ?> </td>
                                                                  <td><?php
                            if (empty($payment_tmp[0]))
                                echo '';
                            else
                                printf("%.3f USD", $payment_tmp[0]); echo "<br />", $payment_tmp[1];
                            ?> </td>
                                                                  <td><?php
                            if (empty($invoice_tmp[0]))
                                echo '';
                            else
                                printf("%.3f USD", $invoice_tmp[0]); echo "<br />", $invoice_tmp[1];
                            ?> </td>
                                                                  <td><?php
                            if ($mydata[$i][0]['balance'] < 0)
                                printf("(%.3f) USD", abs($mydata[$i][0]['balance']));
                            else
                                printf("%.3f USD", $mydata[$i][0]['balance']);
                            ?> </td>
                                                                  <td><?php printf("%.3f USD", $mydata[$i][0]['overdue']); ?></td>
                                                                  <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_7']); ?></td>
                                                                  <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_15']); ?></td>
                                                                  <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_30']); ?></td>
                                                                  <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_gt_30']); ?></td>
                                                                  <td><a href="<?php echo $this->webroot; ?>mutual_statements/detail_report/<?php echo $mydata[$i][0]['client_id']; ?>">detail</a></td>
                                                                </tr>
    <?php } ?>
                                  </tbody>
                                </table>-->

                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>