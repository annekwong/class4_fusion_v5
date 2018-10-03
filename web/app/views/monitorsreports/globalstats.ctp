<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('QoS Monitor') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('QoS Monitor') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
<!--        <div class="widget-head">
            <?php //echo $this->element('qos/qos_tab', array('active_tab' => 'global')) ?> 
        </div>-->
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
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Switch Server', true); ?>:</label>
                        <select id="server_info" style="width:180px;">
                            <?php foreach ($limit_servers as $limit_server): ?>
                                <option value="<?php echo $limit_server[0]['lan_ip'] . ':' . $limit_server[0]['lan_port'] ?>"><?php echo $limit_server[0]['sip_ip'] . ':' . $limit_server[0]['sip_port'] ?></option>
                            <?php endforeach; ?>
                            <!--            <option value="all">All Server</option>-->
                        </select>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            
            <div class="clearfix"></div>
    
    
   
    <div style="width: 100%;; margin: 0px ">
         <?php //echo $this->element('element_stock'); ?>
        <!--
        <fieldset>
          <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> Overall</legend>
          <table class="list list-form">
            <thead>
              <tr>
                <td width="20%"></td>
                <td width="20%"><?php echo __('currently') ?></td>
                <td width="20%"><?php echo __('Max') ?></td>
              </tr>
            </thead>
            <tbody  id='currentSys'>
              <tr class="row-1">
                <td><?php echo __('totalcall') ?>s</td>
                <td class="in-decimal"><strong style="color: green;" id="current_calls">0</strong></td>
                <td class="in-decimal"><strong style="color: green;" id="system_max_calls">0</strong></td>
              </tr>
              <tr class="row-2">
                <td><?php echo __('totalpermin') ?></td>
                <td class="in-decimal"><strong style="color: red;" id="current_cps">0</strong></td>
                <td class="in-decimal"><strong style="color: red;" id="system_max_cps">0</strong></td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        -->
        <div class="clearfix"></div>
        <fieldset>
            <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php __('Session')?></legend>
            <table class="list list-form   footable table table-striped table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th width="20%"></th>
            <!--            <th width="20%"><?php echo __('Total Limit') ?></th>-->
                        <th width="20%"><?php echo __('Ingress Channel') ?></th>
                        <th width="20%"><?php echo __('Egress Channel') ?></th>
                        <th width="20%"><?php echo __('Total Channel') ?></th>
                        <th width="20%"><?php echo __('Total Calls') ?></th>
                    </tr>
                </thead>
                <tbody  id='currentSys'>
                    <tr class="row-1">
                        <td></td>
            <!--            <td class="in-decimal"><strong style="color: green;" id="session_limit">0</strong></td>-->
                        <td class="in-decimal"><strong style="color: green;" id="ingress_channel">0</strong></td>
                        <td class="in-decimal"><strong style="color: green;" id="egress_channel">0</strong></td>
                        <td class="in-decimal"><strong style="color: green;" id="session_count">0</strong></td>
                        <td class="in-decimal"><strong style="color: green;" id="total_call">0</strong></td>
                    </tr>
                </tbody>
            </table>
        </fieldset> 


        <div style="width: 100%;; margin: 0px ">
            <fieldset>
                <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'><?php echo __('Point in time', true); ?></legend>
                <table class="list list-form footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo __('currently') ?></th>
                            <th><?php echo __('24hrpeak') ?></th>
                            <th><?php echo __('7dayspeak') ?></th>
                            <th><?php echo __('recentpeak') ?></th>
                            <th><?php echo __('Max') ?></th>
                        </tr>
                    </thead>
                    <tbody  id='syslimit'>
                        <tr class="row-1">
                            <td><?php echo __('Ingress Channel', true); ?></td>
                            <td class="in-decimal"><strong style="color: green;">0</strong></td>
                            <td class="in-decimal"><strong style="color: green;">0</strong></td>
                            <td class="in-decimal"><strong style="color: green;">0</strong></td>
                            <td class="in-decimal"><strong style="color: green;">0</strong></td>
                            <td class="in-decimal"><strong style="color: green;">0</strong></td>
                        </tr>
                        <tr class="row-1">
                            <td><?php echo __('Ingress CPS', true); ?></td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0</td>
                            <td class="in-decimal">0</td>
                        </tr>
                        <tr class="row-1">
                            <td><?php echo __('Calls', true); ?></td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0 </td>
                            <td class="in-decimal">0</td>
                            <td class="in-decimal"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
<!--            <fieldset>
                <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php echo __('Historical', true); ?></legend>
                <table class="list nowrap with-fields  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th width="10%" rowspan="2" rel="0"></th>
                            <th class="cset-1" colspan="4">15 <?php echo __('minutes', true) ?></th>
                            <th colspan="4" class="cset-2">1 <?php echo __('hour', true) ?></th>
                            <th colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour', true) ?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></th>
                        </tr>
                        <tr>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay') ?>&nbsp;</th>
                             
                           <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                             
                            
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls') ?>&nbsp;</th>
                            <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay') ?>&nbsp;</th>
                                
                            <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                            
                            
                            
                            <th width="6%" class="cset-3" rel="10">&nbsp;<?php echo __('avgduration') ?></th>
                            <th width="6%" class="cset-3 last" rel="10" >&nbsp; <?php echo __('ABR') ?> &nbsp;</th>
                            <th width="6%" class="cset-3" rel="10" >&nbsp; <?php echo __('calls') ?> &nbsp;</th>
                            <th width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo __('calldelay') ?></th>
                            
                           <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                            </tr>
                    </thead>
                    <tbody class="orig-calls"   id='tbodyOfShowTable'>
                        <tr class="subheader row-1">
                            <td align="left" class="last" colspan="17"  style="text-align: left;"><?php echo __('Total Calls', true); ?></td>
                        </tr>
                        <tr class="row-2">
                            <td class="in-decimal"><strong   style='color:#4B9100'></strong></td>
                            <td class="in-decimal"  id="acd_15"><?php echo round($historys[0][0]['acd1'], 2) ?></td>
                            <td class="in-decimal"  id="asr_15"><?php echo round($historys[0][0]['asr1'], 2); ?></td>
                            <td class="in-decimal"  id="ca_15"><?php echo $historys[0][0]['ca1'] ?></td>
                            <td class="in-decimal"   id="pdd_15"><?php echo $historys[0][0]['pdd1'] ?></td>
                            
                  <td class="in-decimal"   id="profit_15">0</td>
                 
                            
                            <td class="in-decimal"  id="acd_1h"><?php echo round($historys[0][0]['acd2'], 2) ?></td>
                            <td class="in-decimal"  id="asr_1h"><?php echo round($historys[0][0]['asr2'], 2) ?></td>
                            <td class="in-decimal"  id="ca_1h"><?php echo $historys[0][0]['ca2'] ?></td>
                            <td class="in-decimal"   id="pdd_1h"><?php echo $historys[0][0]['pdd2'] ?></td>
                            
                 <td class="in-decimal"   id="profit_1h">0</td>
                 
                            
                            <td class="in-decimal"  id="acd_24h"><?php echo round($historys[0][0]['acd3'], 2) ?></td>
                            <td class="in-decimal"  id="asr_24h"><?php echo round($historys[0][0]['asr3'], 2) ?></td>
                            <td class="in-decimal"  id="ca_24h"><?php echo $historys[0][0]['ca3'] ?></td>
                            <td class="in-decimal last"   id="pdd_24h"><?php echo $historys[0][0]['pdd3'] ?></td>
                            
                  <td class="in-decimal last"   id="profit_24h">0</td>
                
                            </tr>
                    </tbody>
                </table>
            </fieldset>-->
            <fieldset>
                <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'></legend>
            </fieldset>
        </div>
    </div>


    <script type="text/javascript" src="<?php echo $this->webroot ?>js/My97DatePicker/WdatePicker.js"></script> 
    <script type="text/javascript" src="<?php echo $this->webroot ?>js/chart.js"></script> 
</div>
    </div></div>

<script type="text/javascript">
    function set_sys_limit(data) {
        if(data != 'null') {
            data = eval('(' + data + ')');
            //        $('#current_calls').text(data.curr_cap);
            //        $('#system_max_calls').text(data.max_cap);
            //        $('#current_cps').text(data.curr_cps);
            //        $('#system_max_cps').text(data.max_cps);
            $('#syslimit tr:nth-child(1) td:nth-child(2) strong').eq(0).text(data.curr_chan);
            $('#syslimit tr:nth-child(1) td:nth-child(3) strong').eq(0).text(data['chan_24hr']);
            $('#syslimit tr:nth-child(1) td:nth-child(4) strong').eq(0).text(data['chan_7day']);
            $('#syslimit tr:nth-child(1) td:nth-child(5) strong').eq(0).text(data['chan_rece']);
            $('#syslimit tr:nth-child(1) td:nth-child(6) strong').eq(0).text(data['max_chan']);

            $('#syslimit tr:nth-child(2) td:nth-child(2)').eq(0).text(data.curr_cps);
            $('#syslimit tr:nth-child(2) td:nth-child(3)').eq(0).text(data['cps_24hr']);
            $('#syslimit tr:nth-child(2) td:nth-child(4)').eq(0).text(data['cps_7day']);
            $('#syslimit tr:nth-child(2) td:nth-child(5)').eq(0).text(data['cps_rece']);
            $('#syslimit tr:nth-child(2) td:nth-child(6)').eq(0).text(data['max_cps']);
        
            $('#syslimit tr:nth-child(3) td:nth-child(2)').eq(0).text(data.curr_call);
            $('#syslimit tr:nth-child(3) td:nth-child(3)').eq(0).text(data['call_24hr']);
            $('#syslimit tr:nth-child(3) td:nth-child(4)').eq(0).text(data['call_7day']);
            $('#syslimit tr:nth-child(3) td:nth-child(5)').eq(0).text(data['call_rece']);
        
            $('#session_count').text(data['chan']);
            $('#ingress_channel').text(data['o_chan']);
            $('#egress_channel').text(data['t_chan']);
            $('#total_call').text(data['call']);
        } else {
            //        $('#current_calls').text(0);
            //        $('#system_max_calls').text(0);
            //        $('#current_cps').text(0);
            //        $('#system_max_cps').text(0);
            $('#syslimit tr:nth-child(1) td:nth-child(2) strong').eq(0).text(0);
            $('#syslimit tr:nth-child(1) td:nth-child(3) strong').eq(0).text(0);
            $('#syslimit tr:nth-child(1) td:nth-child(4) strong').eq(0).text(0);
            $('#syslimit tr:nth-child(1) td:nth-child(5) strong').eq(0).text(0);
            $('#syslimit tr:nth-child(1) td:nth-child(6) strong').eq(0).text(0);

            $('#syslimit tr:nth-child(2) td:nth-child(2)').eq(0).text(0);
            $('#syslimit tr:nth-child(2) td:nth-child(3)').eq(0).text(0);
            $('#syslimit tr:nth-child(2) td:nth-child(4)').eq(0).text(0);
            $('#syslimit tr:nth-child(2) td:nth-child(5)').eq(0).text(0);
            $('#syslimit tr:nth-child(2) td:nth-child(6)').eq(0).text(0);
        
        
            $('#syslimit tr:nth-child(3) td:nth-child(2)').eq(0).text(0);
            $('#syslimit tr:nth-child(3) td:nth-child(3)').eq(0).text(0);
            $('#syslimit tr:nth-child(3) td:nth-child(4)').eq(0).text(0);
            $('#syslimit tr:nth-child(3) td:nth-child(5)').eq(0).text(0);
        
        
            $('#session_count').text(0);
            //        $('#session_limit').text(0);
            $('#ingress_channel').text(0);
            $('#egress_channel').text(0);
            $('#total_call').text(0);
        }
    }

    $(function() {

        $('#server_info').change(function() {
            var server_info = $(this).val();
            var server_info_arr = server_info.split(':');
            var ip = server_info_arr[0];
            var port = server_info_arr[1];
            $.ajax({
                'url' : "<?php echo $this->webroot; ?>monitorsreports/get_sys_limit",
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {'ip':ip, 'port':port},
                'success' : function(data) {
                    set_sys_limit(data);
                }
            });
        });


        $('#server_info').change();

        var interv = null;

        $('#changetime').change(function() {
            if(interv) 
                window.clearInterval(interv);
            var time = $(this).val() * 1000;
            interv = window.setInterval("loading();window.location.reload()", time); 
        });

        $('#changetime').change();

    });
</script>