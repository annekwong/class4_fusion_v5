<style type="text/css">
    .group-title.bottom {
        -moz-border-radius: 0 0 6px 6px;
        border-top: 1px solid #809DBA;
        margin: 15px auto 10px;
    }

    .list td.in-decimal {
        text-align: center;
    }

    .value input,.value select,.value textarea,.value .in-text,.value .in-password,.value .in-textarea,.value .in-select
    {
        -moz-box-sizing: border-box;
        width: 100px;;
    }

    .list {
        font-size: 1em;
        margin: 0 auto 20px;
        width: 100%;
    }

    #container .form {
        margin: 0 auto;
        width: 750px;
    }
    #cps_cap_info {
        font-size:14px;
        font-weight:bold;
        padding:10px;
    }
    #cps_cap_info span {padding:0 10px;}
    #cps_cap_info b {color:red;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('QoS Monitor') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo Inflector::humanize($h_title) ?> <?php __('Report')?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Statistics'); ?>&gt;&gt;<?php echo Inflector::humanize($h_title) ?> <?php __('Report')?> </h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>monitorsreports/carrier/<?php echo $this->params['pass'][0] ?>"><i></i><?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('qos/qos_tab', array('active_tab' => $this->params['pass'][0])) ?> 
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Refresh Every', true); ?>:</label>
                        <select id="changetime">
                            <option value="180">3 <?php __('minutes')?></option>
                            <option value="300">5 <?php __('minutes')?></option>
                            <option value="800">15 <?php __('minutes')?></option>
                        </select>
                    </div>
                    <div>
                        <label><?php echo __('Switch Server', true); ?>:</label>
                        <select id="server_info" style="width:180px;">
                            <?php foreach ($limit_servers as $limit_server): ?>
                                <!--            <option value="<?php echo $limit_server[0]['info_ip'] . ':' . $limit_server[0]['info_port'] ?>"><?php echo $limit_server[0]['ip'] . ':' . $limit_server[0]['port'] ?></option>-->
                                <option value="<?php echo $limit_server[0]['lan_ip'] . ':' . $limit_server[0]['lan_port'] ?>"><?php echo $limit_server[0]['sip_ip'] . ':' . $limit_server[0]['sip_port'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->

                    <!-- // Filter END -->
                </form>
            </div>
            <?php
            $mydata = $p->getDataArray();
            if (empty($mydata))
            {
                ?>
                <div class="msg"><?php echo __('no_data_found', true); ?></div>
                <?php
            }
            else
            {
                ?>
                <div id="toppage"></div>
                <br />
                <table class="list">
                    <tbody>
                        <tr>
                            <td><?php __('CPS')?>:</td>
                            <td id="cps"></td>
                            <td><?php __('Calls')?>:</td>
                            <td id="calls"></td>
                        </tr>
                    </tbody>
                </table>

                <table class="list nowrap with-fieldslist footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('ip', true); ?></th>
                            <th class="cset-1" colspan="4">15 <?php echo __('minutes', true) ?></th>
                            <th colspan="4" class="cset-2">1 <?php echo __('hour', true) ?></th>
                            <th colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour', true) ?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></th>
                        </tr>
                        <tr>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd15m', __('avgduration', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr15m', __('ABR', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca15m', __('calls', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd15m', __('calldelay', true)) ?>&nbsp;</th>
                            <!-- 
                                 <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                                   
                            -->
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd1h', __('avgduration', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr1h', __('ABR', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca11h', __('calls', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd1h', __('calldelay', true)) ?>&nbsp;</th>
                            <!--    
                                  <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                                  
                                  
                            -->
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd24h', __('avgduration', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr24h', __('ABR', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca124h', __('calls', true)) ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd24h', __('calldelay', true)) ?>&nbsp;</th>
                            <!--
                                 <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                            --></tr>
                    </thead>
                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++)
                    {


                        if ($i % 2 == 0)
                        {
                            ?>
                            <tbody    id='tbodyOfShowTable'>
                                <tr>
                                    <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
                                                                                                                             href="<?php echo $this->webroot; ?>monitorsreports/chart_ip/<?php echo $mydata[$i]['qos_name']; ?>/<?php echo $this->params['pass'][0]; ?>"  style='color:#4B9100'>
                                                                                                                                 <?php echo $mydata[$i]['ip']; ?>
                                                </a></span> </strong></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd1'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr1'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd2'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr2'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca2'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd2'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd3'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr3'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca3'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd3'], 0) ?></td>

                                </tr>
                            </tbody>
                            <?php
                        }
                        else
                        {
                            ?>
                            <tbody >
                                <tr class="row-2">
                                    <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
                                                                                                                             href="<?php echo $this->webroot; ?>monitorsreports/chart_ip/<?php echo $mydata[$i]['qos_name']; ?>/<?php echo $this->params['pass'][0]; ?>"  style='color:#4B9100'>
                                                                                                                                 <?php echo $mydata[$i]['ip']; ?>
                                                </a></span> </strong></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd1'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr1'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd2'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr2'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca2'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd2'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['acd3'] / 60, 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['asr3'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['ca3'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($mydata[$i]['pdd3'], 0) ?></td>
                                </tr>
                            </tbody>
                            <?php
                        }
                    }
                    ?>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>


<?php } ?>

<script type="text/javascript">

    $(function() {

        $('#server_info').change(function() {
            var type = "<?php echo $type; ?>";
            var resource_id = <?php echo $resource_id; ?>;
            var server_info = $(this).val();
            var server_info_arr = server_info.split(':');
            var ip = server_info_arr[0];
            var port = server_info_arr[1];
            $.ajax({
                'url': "<?php echo $this->webroot; ?>monitorsreports/get_trunk_count",
                'type': 'POST',
                'dataType': 'json',
                'data': {'ip': ip, 'port': port, 'type': type, 'resource_id': resource_id},
                'success': function(data) {
                    $('#cps').text(data[0]);
                    $('#calls').text(data[1]);
                }
            });
        });



        $('#server_info').change();

        var interv = null;

        $('#changetime').change(function() {
            if (interv)
                window.clearInterval(interv);
            var time = $(this).val() * 1000;
            interv = window.setInterval("loading();window.location.reload()", time);
        });

        $('#changetime').change();
    });

</script>

</div>




