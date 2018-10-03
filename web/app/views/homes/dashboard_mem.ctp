<style>
    .innerLR .dropdown-menu > li {
        clear: both;
        color: #333333;
        display: block;
        font-weight: normal;
        line-height: 20px;
        padding: 3px 20px;
        white-space: nowrap;
    }

    .innerLR .dropdown-menu > li:hover, .innerLR .dropdown-menu > li:focus {
        background-color: #f5f5f5;
        /*color: #fff;*/
        text-decoration: none;
        font-weight: bolder;
    }

    .table-primary tbody td {
        background-color: #ffffff;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
            <?php echo __('Dashboard') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
            <?php echo __('Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Report'); ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li class="active">
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/search_charts" class="glyphicons charts">
                        <i></i><?php __('Charts') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/auto_delivery" class="glyphicons stroller">
                        <i></i><?php __('Auto Delivery') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report"  class="glyphicons notes">
                        <i></i><?php __('Qos Report')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"  class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"  class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/alert"  class="glyphicons alarm">
                        <i></i><?php __('Alert')?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">

            <!--daily-->
            <div class="row-fluid margin-bottom10" style="border: 1px solid #efefef">
                <!--daily-->
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <a href="" onclick="return false" class="widget-stats widget-stats-4">
                        <span class="txt text-center"><?php __('Daily Minutes'); ?></span>
                        <span class="count txt-single text-center" id="call_mins_value"
                              style="font-size: 20px;width: 100%">0</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <a href="" onclick="return false" class="widget-stats widget-stats-4" style="background-color: #f4f4f4">
                        <span class="txt text-center"><?php __('Daily Margin'); ?></span>
                        <span class="count txt-single price text-center" id="margin_value"
                              style="font-size: 20px;width: 100%">0</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <a href="" onclick="return false" class="widget-stats widget-stats-4">
                        <span class="txt text-center"><?php __('Daily Margin'); ?> %</span>
                    <span class="count txt-single text-center" id="margin_pre_value"
                          style="font-size: 20px;width: 100%">0</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <!-- end daily-->
                <!--    pie-->
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <!-- Stats Widget -->
                    <a href="" onclick="return false" class="widget-stats widget-stats-gray widget-stats-2 widget-stats-easy-pie txt">
                        <span class="txt-single" style="font-size: 16px;margin-bottom:5px"><?php __('Ingress Channel'); ?></span>

                        <div id="easy_pie_2" data-percent="0" class="easy-pie primary"><span class="value" style="color: #7faf00"></span></div>
                        <span class="txt" style="font-size: 13px;color: #e5412d;">
                            <?php __('MAX'); ?>: <span id="max_channel"><?php echo $max_channel; ?></span>
                        </span>

                        <div class="clearfix"></div>
                    </a>
                    <!-- // Stats Widget END -->
                </div>
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <!-- Stats Widget -->
                    <a href="" onclick="return false" class="widget-stats widget-stats-gray widget-stats-2 widget-stats-easy-pie txt"
                       style="background-color: #ffffff">
                        <span class="txt-single" style="font-size: 16px;margin-bottom:5px"><?php __('Ingress CPS '); ?></span>

                        <div id="easy_pie" value="" channel="<?php echo $max_channel ?>"
                             cps="<?php echo $max_cps; ?>" data-percent="0" class="easy-pie primary"><span class="value" style="color: #7faf00"></span></div>
                        <span class="txt" style="font-size: 13px;color: #e5412d;">
                            <?php __('MAX'); ?>: <span class="txt-single" id="max_cps"><?php echo $max_cps; ?></span>
                        </span>

                        <div class="clearfix"></div>
                    </a>
                    <!-- // Stats Widget END -->
                </div>
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <!-- Stats Widget -->
                    <a href="" onclick="return false" class="widget-stats widget-stats-gray widget-stats-2 widget-stats-easy-pie txt">
                        <span class="txt-single" style="font-size: 16px;margin-bottom:5px"><?php __('Egress CPS'); ?></span>

                        <div id="easy_pie_4" data-percent="0" class="easy-pie primary"><span class="value" style="color: #7faf00"></span></div>

                        <div class="clearfix"></div>
                    </a>
                    <!-- // Stats Widget END -->
                </div>
                <div class="span2" style="margin: 0;width: 14.2857142857%">
                    <!-- Stats Widget -->
                    <a href="" onclick="return false" class="widget-stats widget-stats-gray widget-stats-2 widget-stats-easy-pie txt" style="background-color: #ffffff">
                        <span class="txt-single" style="font-size: 16px;margin-bottom:5px"><?php __('CALL'); ?></span>

                        <div id="easy_pie_3" data-percent="100" class="easy-pie primary"><span class="value" style="color: #7faf00"></span>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                    <!-- // Stats Widget END -->

                </div>
            </div>
            <!--    end pie-->
            <div class="clearfix"></div>
            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>

            <!--ajax_text1-->
            <div class="btn-group btn-group-sm pull-right" style="margin-bottom: 20px">
                <button class="btn btn-primary btn-sm innerLR "
                        data-toggle="dropdown" style="margin-right: 25px"><span id="text1-button"
                                                                                value="1"><?php __('Last Hour') ?></span> <span
                        class="caret"></span></button>
                <ul class="dropdown-menu" id="text1-time" role="menu">
                    <li value="1"><?php __('Last Hour') ?></li>
                    <li value="2"><?php __('Last 24 Hours') ?></li>
                    <li value="3"><?php __('Last 7 Days') ?></li>
                </ul>
            </div>
            <div class="clearfix"></div>


            <div class="row-fluid margin-bottom10" style="border: 1px solid #efefef">
                <div class="span3">
                    <a href="#" onclick="return false" class="widget-stats widget-stats-4">
                        <span class="txt text-center"><?php __('Last Hour ASR in '); ?></span>
            <span class="count txt-single text-center" id="asr_value"
                  style="font-size: 20px;width: 100%;">0 (calls)</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="#" onclick="return false" class="widget-stats widget-stats-4" style="background-color: #f4f4f4">
                        <span class="txt text-center" id="acd_title"><?php __('Last Hour ACD in '); ?></span>
            <span class="count txt-single text-center" id="acd_value"
                  style="font-size: 20px;width: 100%;">0 (calls)</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="#" onclick="return false" class="widget-stats widget-stats-4">
                        <span class="txt text-center" id="revenue_title"><?php __('Last Hour Revenue in '); ?></span>
            <span class="count txt-single text-center" id="revenue_value"
                  style="font-size: 20px;width: 100%;">0 (calls)</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="#" onclick="return false" class="widget-stats widget-stats-4" style="background-color: #f4f4f4">
                        <span class="txt text-center"
                              id="profitability_title"><?php __('Last Hour Profitability in '); ?></span>
            <span class="count txt-single text-center" id="profitability_value"
                  style="font-size: 20px;width: 100%;">0 (calls)</span>
                        <!--<span class="glyphicons phone"><i></i></span>-->
                        <div class="clearfix"></div>
                    </a>
                </div>

            </div>
            <!--end ajax_text1-->


            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>

            <div class="bottom row-fluid">
                <form class="form-inline center">
                    <input id="data_type" type="hidden" value="1"/>

                    <label style=""><?php __('Show') ?>:</label>
                    <select id="show_type" style="width:120px;">
                        <?php foreach ($show_filed as $key => $show_filed_Item): ?>
                            <option value="<?php echo $key ?>"><?php echo $show_filed_Item ?></option>
                        <?php endforeach; ?>
                    </select>
                    &nbsp;


                    <label><?php __('Server') ?>:</label>
                    <select id="server" style="width: 120px">
                        <option value="" channel="<?php echo $max_channel ?>"
                                cps="<?php echo $max_cps; ?>"><?php __('All') ?></option>
                        <?php foreach ($limit_servers as $limit_server): ?>
                            <option channel="<?php echo $limit_server[0]['max_channel'] ?>"
                                    cps="<?php echo $limit_server[0]['max_cps'] ?>"
                                    value="<?php echo $limit_server[0]['lan_ip'] . ':' . $limit_server[0]['lan_port'] ?>"><?php echo $limit_server[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    &nbsp;

                    <label><?php __('Type') ?>:</label>
                    <select id="type" class="input-medium network_all" style="width: 110px">
                        <option selected="selected" value="1"><?php __('Network') ?></option>
                        <option value="2"><?php __('Orig Trunks') ?></option>
                        <option value="3"><?php __('Term Trunks') ?></option>
                    </select>
                    <select class="input-medium network_single hide" style="width: 110px">
                        <option selected="selected" value="1"><?php __('Network') ?></option>
                    </select>

                    &nbsp;

                    <span id="trunk_list_panel">
                        <label><?php __('Trunk List') ?>:</label>
                        <select id="trunks" class="input-medium" style="width: 120px">
                            <option value="top5"><?php __('Top') ?> 5</option>
                            <option value="top10"><?php __('Top') ?> 10</option>
                            <option value="top15"><?php __('Top') ?> 15</option>
                            <option value="top20"><?php __('Top') ?> 20</option>
                            <option value="all"><?php __('All') ?></option>
                        </select>

                        &nbsp;

                        <label class="trunks_ips"><?php __('Trunk IP List') ?>:</label>
                        <select class="trunks_ips" id="trunks_ips" class="input-medium" style="width: 150px">
                            <option value="0"><?php __('All') ?></option>
                        </select>
                    </span>

                    &nbsp;

                    <label><?php __('Interval') ?>:</label>
                    <select id="duration" class="interval_all" style="width: 120px">
                        <?php foreach ($date_intervals as $key => $date_interval): ?>
                            <option value="<?php echo $key; ?>"><?php echo $date_interval; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="interval_single" style="width: 120px">
                        <option value="1"><?php __('Last Hour'); ?></option>
                        <option value="2"><?php __('Last 24 Hours'); ?></option>
                    </select>

                    <!--                    <label><?php __('Period') ?>:</label>
                    <input type="text" id="start_time" value="<?php echo date("Y-m-d 00:00:00"); ?>" class="input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})">
                    ~
                    <input type="text" id="end_time" value="<?php echo date("Y-m-d 23:59:59"); ?>" class="input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})">-->

                    <button style="margin-left: 5px" onclick="
                            return false;" id="refresh" class="btn btn-icon btn-primary glyphicons refresh">
                        <i></i><?php __('Refresh'); ?></button>
                </form>
            </div>
            <div class="separator"></div>

            <div class="father" id="chart_group">

                <!--                <div id="chart_call" style="height: 400px; min-width: 600px;"></div>-->
            </div>
            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>

            <div style="padding: 15px;border: 1px solid #efefef">
                <div id="chart_cps" style="height: 400px; min-width: 600px;"></div>
            </div>

            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>

            <div style="padding: 15px;border: 1px solid #efefef">
                <div id="chart_channel" style="height: 400px; min-width: 600px"></div>
            </div>

            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>

            <!--            ajax_table1-->
            <div style="border: 1px solid #efefef;padding: 15px">
                <h2 style="margin-bottom: 20px" class="pull-left"><i class="fa fa-fw icon-star text-primary"></i> Trends <small id="table_time_interval"></small></h2>

                <div class="btn-group btn-group-sm pull-right" style="margin-right: 25px">
                    <button class="btn btn-primary btn-sm innerLR " id="ajax_table_refresh">

                        <a href="" onclick="return false;" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                    </button>
                </div>

                <div class="clearfix"></div>
                <div style="border: 1px solid #efefef;padding: 15px">
                    <div class="clearfix"></div>
                    <div class="overflow_x pull-left" style="width: 48%;">
                        <table
                            class="footable table table-striped  tableTools table-bordered  table-white table-primary footable-loaded default">
                            <caption align="top"><h3><?php __('Top 20 Clients')?></h3></caption>
                            <!-- Table heading -->
                            <thead>
                            <tr id="clients-th" value="0">
                                <th><?php __('Carrier Name') ?></th>
                                <th><?php __('Last 24h Revenue') ?></th>
                                <th><?php __('Last 24h Trend') ?></th>

                            </tr>

                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody id="clients-tbody">
                            <tr class="clients-clone-tr">
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>


                            </tbody>
                            <!-- // Table body END -->

                        </table>

                        <div class="clearfix"></div>
                    </div>
                    <div class="overflow_x pull-right" style="width: 48%;">
                        <table
                            class="footable table table-striped  tableTools table-bordered  table-white table-primary footable-loaded default">
                            <caption align="top"><h3><?php __('Top 20 Vendors')?></h3></caption>
                            <!-- Table heading -->
                            <thead>
                            <tr id="vendors-th" value="0">
                                <th><?php __('Carrier Name') ?></th>
                                <th><?php __('Last 24h Revenue') ?></th>
                                <th><?php __('Last 24h Trend') ?></th>

                            </tr>

                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody id="vendors-tbody">
                            <tr class="vendors-clone-tr">
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>


                            </tbody>
                            <!-- // Table body END -->

                        </table>

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>


            </div>

            <!--            end ajax_table1-->
        </div>
    </div>
</div>


<script src="<?php echo $this->webroot; ?>highstock/highstock.js"></script>
<script src="<?php echo $this->webroot; ?>highstock/modules/exporting.js"></script>
<!-- Easy-pie Plugin -->
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css"
      rel="stylesheet"/>
<script
    src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.js"></script>

<script type="text/javascript">
    /* daily */
    function get_daily(){
        var call_mins = $("#call_mins_value");
        var margin = $("#margin_value");
        var margin_pre = $("#margin_pre_value");
        call_mins.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        margin.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        margin_pre.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");

        $.getJSON(
            "<?php echo $this->webroot; ?>homes/ajax_get_daily",
            function(data){

                call_mins.html(data['call_mins']);
                margin.html(data['margin']);
                margin_pre.html(data['margin_pre']);
            }
        )

    }
    window.setInterval('get_daily();', 1000 * 60 );
    /* end daily */

    var color_arr = new Array();
    color_arr[0] = '#8a2be2';
    color_arr[1] = '#e5412d';
    var color_cnt = 0;

    var pie_data = {};
    pie_data.cps = 0;
    pie_data.channel = 0;
    pie_data.call = 0;


    /*pie */
    function update_easy_pie() {


        var color_switch = color_cnt % 2;
        color_cnt = color_cnt + 1;

        //pie
        /*$('#easy_pie').easyPieChart({
         size: 45,
         lineWidth: 3,
         barColor: color_arr[color_switch],
         trackColor: '#7faf00',
         scaleColor: false,
         animate: 100
         });
         $('#easy_pie_2').easyPieChart({
         size: 45,
         lineWidth: 3,
         barColor: color_arr[color_switch],
         trackColor: '#7faf00',
         scaleColor: false,
         animate: 100
         });
         $('#easy_pie_3').easyPieChart({
         size: 45,
         lineWidth: 3,
         barColor: color_arr[color_switch],
         trackColor: '#7faf00',
         scaleColor: false,
         animate: 100
         });*/


        /*$.ajax({
         'url': '<?php echo $this->webroot ?>homes/get_current_data',
         'type': 'POST',
         'dataType': 'json',
         'data': {'server': server},
         'success': function (data) {
         var current_cps = data.cps;
         var current_channel = data.channel;
         var current_call = data.call;

         var percent = parseInt(current_cps / max_cps * 100);


         $('#easy_pie').data('easyPieChart').update(percent);
         $('#easy_pie .value').html(current_cps);

         var x_percent = parseInt(current_channel / max_channel * 100);
         $('#easy_pie_2').data('easyPieChart').update(x_percent);
         $('#easy_pie_2 .value').html(current_channel);

         //                $('#easy_pie_3').data('easyPieChart').update(0);
         //                $('#easy_pie_3').data('easyPieChart').update(100);
         if(current_call == 0)
         $('#easy_pie_3').data('easyPieChart').update(0);
         else
         $('#easy_pie_3').data('easyPieChart').update(100);
         $('#easy_pie_3 .value').html(current_call);
         }
         });*/
        var obj = $('#easy_pie');
        var max_cps = obj.attr('cps');
        var max_channel = obj.attr('channel');
        var server = obj.attr('value');

        var current_ingress_cps = pie_data.ingress_cps;
        var current_egress_cps = pie_data.egress_cps;
        var current_channel = pie_data.channel;
        var current_call = pie_data.call;

        var percent = parseInt(current_ingress_cps / max_cps * 100);

        $('#easy_pie').data('easyPieChart').options.barColor = color_arr[color_switch];
        $('#easy_pie_4').data('easyPieChart').options.barColor = color_arr[color_switch];
        $('#easy_pie_2').data('easyPieChart').options.barColor = color_arr[color_switch];
        $('#easy_pie_3').data('easyPieChart').options.barColor = color_arr[color_switch];

        $('#easy_pie').data('easyPieChart').update(percent);
        $('#easy_pie .value').html(current_ingress_cps);

        if(current_egress_cps == 0)
            $('#easy_pie_4').data('easyPieChart').update(0);
        else
            $('#easy_pie_4').data('easyPieChart').update(100);
        $('#easy_pie_4 .value').html(current_egress_cps);

        var x_percent = parseInt(current_channel / max_channel * 100);
        $('#easy_pie_2').data('easyPieChart').update(x_percent);
        $('#easy_pie_2 .value').html(current_channel);

//                $('#easy_pie_3').data('easyPieChart').update(0);
//                $('#easy_pie_3').data('easyPieChart').update(100);
        if(current_call == 0)
            $('#easy_pie_3').data('easyPieChart').update(0);
        else
            $('#easy_pie_3').data('easyPieChart').update(100);
        $('#easy_pie_3 .value').html(current_call);


    }


    //获得数据
    function get_current_data(){
        var obj = $('#easy_pie');
        var server = obj.attr('value');

        $.ajax({
            'url': '<?php echo $this->webroot ?>homes/get_current_data',
            'type': 'POST',
            'dataType': 'json',
            'data': {'server': server},
            'success': function (data) {
                pie_data.ingress_cps = data.ingress_cps;
                pie_data.egress_cps = data.egress_cps;
                pie_data.channel = data.channel;
                pie_data.call = data.call;


            }
        });
    }


    /* end pie*/

    /* chart */



    var setinterval_data;
    //    var setinterval_chart_cps;
    //    var setinterval_chart_channel;
    //    var setinterval_chart_group = new Array();
    function chart_load() {

        var server = $('#server').val();
        var type = $('#type').val();
        var trunk = $('#trunks').val();
        var trunk_ip = $('#trunks_ips').val();
        var data_type = $('#data_type').val();
        var show_type = $('#show_type').val();
        var duration = $('#duration').val();
        var refresh_time = (new Date()).getTime();

        var chart_group = $('#chart_group');
        chart_group.html('');
        var show_arr = new Array();

        if(show_type == 'call'){
            show_arr[0] = ['chart_call','Connected Calls','call'];

        } else if(show_type == 'qos') {
            show_arr[0] = ['chart_acd','ACD','acd'];
            show_arr[1] = ['chart_abr','ABR','abr'];
            show_arr[2] = ['chart_asr','ASR','asr'];
            show_arr[3] = ['chart_pdd','PDD','pdd'];

        } else {
            show_arr[0] = ['chart_revenue','Revenue','revenue'];
            show_arr[1] = ['chart_profitability','Profitability','profitability'];
        }

        clearInterval(setinterval_data);
//        clearInterval(setinterval_chart_cps);
//        clearInterval(setinterval_chart_channel);
//        for($i=0;$i<setinterval_chart_group.length;$i++){
//            clearInterval(setinterval_chart_group[$i]);
//        }


        var chart_time_interval;

        var tmp = parseInt(duration);
        if(tmp < 7){
            chart_time_interval = 60 * 1000;
        } else {
            chart_time_interval = 6000 * 6000 * 1000;
        }


        //删除chart
        if($('#chart_channel').highcharts() && $('#chart_channel').highcharts().containerWidth)
            $('#chart_channel').highcharts().destroy();
        if($('#chart_cps').highcharts() && $('#chart_cps').highcharts().containerWidth)
            $('#chart_cps').highcharts().destroy();

        jQuery.ajax({
            'url': "<?php echo $this->webroot ?>homes/get_draws_data",
            'type': "POST",
            'data': {
                'type': type,
                'trunk': trunk,
                'trunk_ip': trunk_ip,
                'data_type': data_type,
                'server': server,
                'show_type': show_type,
                'duration': duration
            },
            "dataType": "json",
            "success": function (data) {
                //生成数据点
                var i=1;
                var max_time = data['max_time'];
                var select_trunk = data['select_trunk'];
                var iden = data['iden'];

                var cps_name = 'Ingress CPS';
                if(type == 3) cps_name = 'Egress CPS';







                //chart group
                for(var $ii=0; $ii<show_arr.length; $ii++){
                    var minTickInterval = null;
                    if (show_arr[$ii][2] == 'call')
                    {
                        minTickInterval = 1;
                    }
                    $('<div style="padding: 15px;margin-bottom:10px;border: 1px solid #efefef"><div id="'+show_arr[$ii][0]+'" value="'+ show_arr[$ii][2] +'" style="height: 400px; min-width: 600px"></div></div>').appendTo(chart_group);
                    $('#'+show_arr[$ii][0]).highcharts('StockChart', {
                        chart: {
                            type: 'spline',
                            animation: Highcharts.svg, // don't animate in old IE
                            marginRight: 10//,

                        },
                        legend: {
                            enabled: true,
                            align: 'right',
//                            backgroundColor: '#FCFFC5',
                            borderColor: 'black',
                            borderWidth: 0,
                            layout: 'vertical',
                            verticalAlign: 'top',
                            y: 100,
                            shadow: true
                        },
                        rangeSelector: {
                            selected: 12,
                            buttonTheme: {
                                width: 35
                            },
                            buttons: [{
                                type: 'minute',
                                count: 5,
                                text: '5min'
                            }, {
                                type: 'minute',
                                count: 30,
                                text: '30min'
                            }, {
                                type: 'minute',
                                count: 60,
                                text: '1h'
                            }, {
                                type: 'minute',
                                count: 180,
                                text: '3h'
                            }, {
                                type: 'minute',
                                count: 240,
                                text: '4h'
                            }, {
                                type: 'minute',
                                count: 360,
                                text: '6h'
                            }, {
                                type: 'minute',
                                count: 480,
                                text: '8h'
                            }, {
                                type: 'minute',
                                count: 720,
                                text: '12h'
                            }, {
                                type: 'day',
                                count: 1,
                                text: '24h'
                            }, {
                                type: 'day',
                                count: 2,
                                text: '2d'
                            }, {
                                type: 'day',
                                count: 7,
                                text: '7d'
                            }, {
                                type: 'month',
                                count: 1,
                                text: '1m'
                            },/* {
                             type: 'month',
                             count: 3,
                             text: '3m'
                             }, {
                             type: 'month',
                             count: 6,
                             text: '6m'
                             }, {
                             type: 'ytd',
                             text: 'YTD'
                             }, {
                             type: 'year',
                             count: 1,
                             text: '1y'
                             },*/ {
                                type: 'all',
                                text: 'All'
                            }]
                        },
                        yAxis: {
                            min: 0,
                            minTickInterval:minTickInterval
                        },
                        title: {
                            text: show_arr[$ii][1]
                        },
                        series: data[show_arr[$ii][2]]
                    });
                }



                $('#chart_cps').highcharts('StockChart', {
                    chart: {
                        type: 'spline',
                        animation: Highcharts.svg, // don't animate in old IE
                        marginRight: 10//,
//                        events: {
//                            load: function () {
//
//                                // set up the updating of the chart each second
//                                var series = this.series[0];
//                                setinterval_chart_cps = setInterval(function () {
//
//                                    var x = gdata['cps'][0];
//                                    var y = gdata['cps'][1];
//
//                                    series.addPoint([x, y], true, true);
//
//                                }, chart_time_interval);
//
//                            }
//                        }
                    },
                    legend: {
                        enabled: true,
                        align: 'right',
//                            backgroundColor: '#FCFFC5',
                        borderColor: 'black',
                        borderWidth: 0,
                        layout: 'vertical',
                        verticalAlign: 'top',
                        y: 100,
                        shadow: true
                    },
                    rangeSelector: {
                        selected: 12,
                        buttonTheme: {
                            width: 35
                        },
                        buttons: [{
                            type: 'minute',
                            count: 5,
                            text: '5min'
                        }, {
                            type: 'minute',
                            count: 30,
                            text: '30min'
                        }, {
                            type: 'minute',
                            count: 60,
                            text: '1h'
                        }, {
                            type: 'minute',
                            count: 180,
                            text: '3h'
                        }, {
                            type: 'minute',
                            count: 240,
                            text: '4h'
                        }, {
                            type: 'minute',
                            count: 360,
                            text: '6h'
                        }, {
                            type: 'minute',
                            count: 480,
                            text: '8h'
                        }, {
                            type: 'minute',
                            count: 720,
                            text: '12h'
                        }, {
                            type: 'day',
                            count: 1,
                            text: '24h'
                        }, {
                            type: 'day',
                            count: 2,
                            text: '2d'
                        }, {
                            type: 'day',
                            count: 7,
                            text: '7d'
                        }, {
                            type: 'month',
                            count: 1,
                            text: '1m'
                        },/* {
                         type: 'month',
                         count: 3,
                         text: '3m'
                         }, {
                         type: 'month',
                         count: 6,
                         text: '6m'
                         }, {
                         type: 'ytd',
                         text: 'YTD'
                         }, {
                         type: 'year',
                         count: 1,
                         text: '1y'
                         },*/ {
                            type: 'all',
                            text: 'All'
                        }]
                    },
                    yAxis: {
                        min: 0,
                        minTickInterval:1
                    },
                    title: {
                        text: cps_name
                    },
                    series: data['cps']
                });
                $('#chart_channel').highcharts('StockChart', {
                    chart: {
                        renderTo:'chart_channel',
                        type: 'spline',
                        animation: Highcharts.svg, // don't animate in old IE
                        marginRight: 10//,
//                        events: {
//                            load: function () {
//
//                                // set up the updating of the chart each second
//                                var series = this.series[0];
//                                setinterval_chart_channel = setInterval(function () {
//
//                                    var x = gdata['channel'][0];
//                                    var y = gdata['channel'][1];
//
//                                    series.addPoint([x, y], true, true);
//
//                                }, chart_time_interval);
//
//                            }
//                        }
                    },
                    legend: {
                        enabled: true,
                        align: 'right',
//                            backgroundColor: '#FCFFC5',
                        borderColor: 'black',
                        borderWidth: 0,
                        layout: 'vertical',
                        verticalAlign: 'top',
                        y: 100,
                        shadow: true
                    },
                    rangeSelector: {
                        selected: 12,
                        buttonTheme: {
                            width: 35
                        },
                        buttons: [{
                            type: 'minute',
                            count: 5,
                            text: '5min'
                        }, {
                            type: 'minute',
                            count: 30,
                            text: '30min'
                        }, {
                            type: 'minute',
                            count: 60,
                            text: '1h'
                        }, {
                            type: 'minute',
                            count: 180,
                            text: '3h'
                        }, {
                            type: 'minute',
                            count: 240,
                            text: '4h'
                        }, {
                            type: 'minute',
                            count: 360,
                            text: '6h'
                        }, {
                            type: 'minute',
                            count: 480,
                            text: '8h'
                        }, {
                            type: 'minute',
                            count: 720,
                            text: '12h'
                        }, {
                            type: 'day',
                            count: 1,
                            text: '24h'
                        }, {
                            type: 'day',
                            count: 2,
                            text: '2d'
                        }, {
                            type: 'day',
                            count: 7,
                            text: '7d'
                        }, {
                            type: 'month',
                            count: 1,
                            text: '1m'
                        },/* {
                         type: 'month',
                         count: 3,
                         text: '3m'
                         }, {
                         type: 'month',
                         count: 6,
                         text: '6m'
                         }, {
                         type: 'ytd',
                         text: 'YTD'
                         }, {
                         type: 'year',
                         count: 1,
                         text: '1y'
                         },*/ {
                            type: 'all',
                            text: 'All'
                        }]
                    },
                    yAxis: {
                        min: 0,
                        minTickInterval:1
                    },
                    title: {
                        text: 'CHANNEL'
                    },
                    series: data['channel']
                });

                setinterval_data = setInterval(function () {
                    $.post(
                        "<?php echo $this->webroot ?>homes/get_chart_point",
                        {
                            i:i,
                            max_time:max_time,
                            iden:iden,
                            type: type,
                            trunk: trunk,
                            trunk_ip: trunk_ip,
                            server: server,
                            show_type: show_type,
                            select_trunk:select_trunk
                        },
                        function (sdata) {
                            ++i;

                            var chart_cps = $('#chart_cps').highcharts();
                            for(var iii = 0;iii < sdata['cps'].length; iii++){
                                var series = chart_cps.series[iii];
                                var x = sdata['cps'][iii][0];
                                var y = sdata['cps'][iii][1];

                                series.addPoint([x, y], true, true);
                            }


                            var chart_channel = $('#chart_channel').highcharts();
                            for(var iii = 0;iii < sdata['channel'].length; iii++){

                                var series = chart_channel.series[iii];
                                var x = sdata['channel'][iii][0];
                                var y = sdata['channel'][iii][1];

                                series.addPoint([x, y], true, true);
                            }


                            //group
                            for(var $ii=0; $ii<show_arr.length; $ii++){
                                var group = $('#'+show_arr[$ii][0]).highcharts();
                                for(var iii = 0;iii < sdata[show_arr[$ii][2]].length; iii++){
                                    var series = group.series[iii];
                                    var x = sdata[show_arr[$ii][2]][iii][0];
                                    var y = sdata[show_arr[$ii][2]][iii][1];
                                    if (show_arr[$ii][0] == 'chart_call' || sdata['is_qos_time'] == 1){
                                        series.addPoint([x, y], true, true);
                                    }
                                }
                            }

                        },
                        'json');
                }, chart_time_interval);




            }
        });
    }




    /* end chart */

    $(function () {
        get_daily();

        var obj = $('#easy_pie');
        var max_cps = obj.attr('cps');
        var max_channel = obj.attr('channel');
        var server = obj.attr('value');

        $("#max_cps").html(max_cps);
        $("#max_channel").html(max_channel);

        get_current_data();
        window.setInterval('get_current_data();', 1000 * 60);
        window.setInterval('update_easy_pie();', 1000);



        //pie
        $('#easy_pie').easyPieChart({
            size: 45,
            lineWidth: 3,
            barColor: '#e5412d',
            trackColor: '#7faf00',
            scaleColor: false,
            animate: 100
        });
        $('#easy_pie_2').easyPieChart({
            size: 45,
            lineWidth: 3,
            barColor: '#e5412d',
            trackColor: '#7faf00',
            scaleColor: false,
            animate: 100
        });
        $('#easy_pie_3').easyPieChart({
            size: 45,
            lineWidth: 3,
            barColor: '#e5412d',
            trackColor: '#7faf00',
            scaleColor: false,
            animate: 100
        });
        $('#easy_pie_4').easyPieChart({
            size: 45,
            lineWidth: 3,
            barColor: '#e5412d',
            trackColor: '#7faf00',
            scaleColor: false,
            animate: 100
        });



        $('#refresh').click(function(){
            var obj = $("#server").find("option:selected");
            var max_cps = obj.attr('cps');
            var max_channel = obj.attr('channel');
            var server = obj.val();

            $('#easy_pie').attr({'value':server, 'cps': max_cps, 'channel': max_channel});
            update_easy_pie();
            chart_load();
        });



        $("#show_type").change(function () {
            var show_type_value = $(this).val();
            $(".trunks_ips").show();
            $(".interval_single").hide();
            $(".interval_all").attr('id', 'duration').show();
            $(".network_single").hide();
            $(".network_all").attr('id', 'type').show();
            $('#trunk_list_panel').show();
            if (show_type_value != 'call') {
                $("#server").val('').attr('disabled',true);
                $(".interval_all").attr('id', '').hide();
                $(".interval_single").attr('id', 'duration').show();
                $(".trunks_ips").hide();
                if (show_type_value == 'revenue_and_profitability') {
                    $(".network_single").attr('id', 'type').show();
                    $(".network_all").attr('id', '').hide();
                    $('#trunk_list_panel').hide();
                }
            }else{
                $("#server").removeAttr('disabled');
            }
            $('#type').change();
        }).trigger('change');
        $('#trunks').change(function () {
            var val = $(this).val();
            var $trunks_ips = $('#trunks_ips');
            if (val != 0) {
                $.ajax({
                    'url': "<?php echo $this->webroot ?>homes/get_trunk_ips/" + val,
                    'type': "GET",
                    "dataType": "json",
                    "success": function (data) {
                        $trunks_ips.empty();
                        $trunks_ips.append('<option value="0">All</option>');
                        $.each(data, function (index, value) {
                            $trunks_ips.append('<option value="' + value[0]['resource_ip_id'] + '">' + value[0]['ip'] + '</option>')
                        });
                    }
                });
            } else {
                $trunks_ips.empty();
                $trunks_ips.append('<option value="0" selected>All</option>');
            }
        });
        $('#type').change();
//        $("#show_type").change(function () {
//            //$('#chart_call').html('');
//            chart_load();
//        });
        $('#type').change(function () {
            var val = $(this).val();
            var $trunks = $('#trunks');
            var $trunks_panel = $('#trunk_list_panel');
            if (val != 1 && $('#data_type').val() == 1) {
                $trunks_panel.show();
                $.ajax({
                    'url': "<?php echo $this->webroot ?>homes/get_trunks/" + val,
                    'type': "GET",
                    "dataType": "json",
                    "success": function (data) {
                        $trunks.find('option:gt(5)').remove();
                        $.each(data, function (index, value) {
                            $trunks.append('<option value="' + value[0]['resource_id'] + '">' + value[0]['alias'] + '</option>')
                        });
                        var $trunks_ips = $('#trunks_ips');
                        $trunks_ips.empty();
                        $trunks_ips.append('<option value="0">All</option>');
                        $('#trunks').trigger('change');
                    }
                });
            } else {
                $trunks_panel.hide();
            }
        });
        $('#trunks').change(function () {
            var val = $(this).val();
            var $trunks_ips = $('#trunks_ips');
            if (val != 0) {
                $.ajax({
                    'url': "<?php echo $this->webroot ?>homes/get_trunk_ips/" + val,
                    'type': "GET",
                    "dataType": "json",
                    "success": function (data) {
                        $trunks_ips.empty();
                        $trunks_ips.append('<option value="0">All</option>');
                        $.each(data, function (index, value) {
                            $trunks_ips.append('<option value="' + value[0]['resource_ip_id'] + '">' + value[0]['ip'] + '</option>')
                        });
                    }
                });
            } else {
                $trunks_ips.empty();
                $trunks_ips.append('<option value="0" selected>All</option>');
            }
        });
        $('#type').change();
        $('#data_type').change(function () {
            var $this = $(this);
            if ($this.val() == '1') {
                $('#type').show();
            }
            else if ($this.val() == '2') {
                $('#trunk_list_panel').hide();
            }
        });
        $('#data_type').change();
        chart_load();
    });

</script>

<script>
    /*//ajax_text1*/
    function ajax_text1() {
        var asr_value = $('#asr_value');
        var acd_value = $('#acd_value');
        var revenue_value = $('#revenue_value');
        var profitability_value = $('#profitability_value');


        asr_value.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        acd_value.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        revenue_value.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        profitability_value.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");

        //获得时间段
        var time = $('#text1-button').attr('value');


        $.getJSON(
            "<?php echo $this->webroot ?>homes/get_admin_dashboard/1/" + time,
            function (data) {



                asr_value.html(data['asr'] + '% ('+data['non_zero_calls']+' calls)');
                acd_value.html(data['acd'] + '('+data['non_zero_calls']+' calls)');
                revenue_value.html('$' + data['revenue'] + ' ('+data['non_zero_calls']+' calls)');
                profitability_value.html(data['profitability'] + '% ('+data['non_zero_calls']+' calls)');
            }
        );
    }


    /*ajax_table*/
    function ajax_table(){
        $('.clients-clone-tr:not(:first)').remove();
        var tr = $('.clients-clone-tr:first').show();

        $('.vendors-clone-tr:not(:first)').remove();
        var tr_vendors = $('.vendors-clone-tr:first').show();






        var clone;
        var clone_vendors;

        var div_trend = "<div class='div_trend' style='height: 50px;width: 300px'></div>";

        tr.find('td').html("<img src='<?php echo $this->webroot?>images/check_waiting.gif'/>");
        tr_vendors.find('td').html("<img src='<?php echo $this->webroot?>images/check_waiting.gif'/>");

        $.post(
            "<?php echo $this->webroot ?>homes/get_admin_dashboard/2/", {},
            function (data) {
                $('#table_time_interval').html(data['time_interval']);

                if(data['clients']){
                    $.each(data['clients'], function (index, item) {
                        for(var k in item) {

                            clone = tr.clone();
                            clone.find('td:eq(0)').html(data['clients_name'][k]);
                            clone.find('td:eq(1)').html(item[k]['revenue']);

                            clone.find('td:eq(2)').html(div_trend);
                            clone.find('.div_trend').highcharts({
                                chart: {
                                    backgroundColor: '#fff',
                                    type: 'spline'
                                },
                                title: {
                                    text: null
                                },
                                xAxis: {

                                    type: 'datetime'
                                },
                                yAxis: {
                                    title: {
                                        text: null
                                    },
                                    min: 0
                                },
                                exporting: {
                                    enabled: false
                                },
                                tooltip: {
                                    valueDecimals: 2//,
                                    //valueSuffix: ' (/min)'
                                },
                                plotOptions: {
                                    spline: {
                                        lineWidth: 2,
                                        states: {
                                            hover: {
                                                lineWidth: 3
                                            }
                                        },
                                        marker: {
                                            enabled: false
                                        }

                                    }
                                },
                                series: [{
                                    name: 'Trend',
                                    data: item[k]['trend'],
                                    color: "#E5412D"

                                }]
                                ,
                                legend: {
                                    enabled: false
                                },
                                credits: {
                                    enabled: false
                                },
                                navigation: {
                                    menuItemStyle: {
                                        fontSize: '10px'
                                    }
                                }
                            });
                            clone.appendTo('#clients-tbody');
                        }
                    });
                }


                if(data['vendors']){
                    $.each(data['vendors'], function (index, item) {
                        for(var k in item) {
                            clone_vendors = tr_vendors.clone();
                            clone_vendors.find('td:eq(0)').html(data['clients_name'][k]);
                            clone_vendors.find('td:eq(1)').html(item[k]['revenue']);

                            clone_vendors.find('td:eq(2)').html(div_trend);
                            clone_vendors.find('.div_trend').highcharts({
                                chart: {
                                    backgroundColor: '#fff',
                                    type: 'spline'
                                },
                                title: {
                                    text: null
                                },
                                xAxis: {

                                    type: 'datetime'
                                },
                                yAxis: {
                                    title: {
                                        text: null
                                    },
                                    min:0
                                },
                                exporting: {
                                    enabled: false
                                },
                                tooltip: {
                                    valueDecimals: 2//,
                                    //valueSuffix: ' (/min)'
                                },
                                plotOptions: {
                                    spline: {
                                        lineWidth: 2,
                                        states: {
                                            hover: {
                                                lineWidth: 3
                                            }
                                        },
                                        marker: {
                                            enabled: false
                                        }

                                    }
                                },
                                series: [{
                                    name: 'Trend',
                                    data: item[k]['trend'],
                                    color: "#E5412D"

                                }]
                                ,
                                legend: {
                                    enabled: false
                                },
                                credits: {
                                    enabled: false
                                },
                                navigation: {
                                    menuItemStyle: {
                                        fontSize: '10px'
                                    }
                                }
                            });
                            clone_vendors.appendTo('#vendors-tbody');
                        }
                    });

                }



                $('.clients-clone-tr:first').hide();
                $('.vendors-clone-tr:first').hide();
            },
            'json')
    }
    /*end ajax_table*/



    $(function () {
        var time_interval = 60 * 1000 * 60;
        /*ajax_text1*/
        ajax_text1();
        setInterval('ajax_text1()', time_interval);

        //ajax_text1 修改时间段
        $('#text1-time li').click(function () {
            var asr_title = $('#asr_title');
            var acd_title = $('#acd_title');
            var revenue_title = $('#revenue_title');
            var profitability_title = $('#profitability_title');

            var value = $(this).attr('value');

            if (value == 1) {
                asr_title.html('Last Hour ASR in');
                acd_title.html('Last Hour ACD in');
                revenue_title.html('Last Hour Revenue in');
                profitability_title.html('Last Hour Profitability in');
            } else if (value == 2) {
                asr_title.html('Last 24 Hours ASR in');
                acd_title.html('Last 24 Hours ACD in');
                revenue_title.html('Last 24 Hours Revenue in');
                profitability_title.html('Last 24 Hours Profitability in');
            } else {
                asr_title.html('Last 7 Days ASR in');
                acd_title.html('Last 7 Days ACD in');
                revenue_title.html('Last 7 Days Revenue in');
                profitability_title.html('Last 7 Days Profitability in');
            }

            var html = $(this).html();
            $('#text1-button').html(html);
            $('#text1-button').attr('value', value);
            ajax_text1();
        });
        /*ajax_text1*/

        /*ajax_table1*/
        ajax_table();

        $('#ajax_table_refresh').click(function(){
            ajax_table();
        });
    })
</script>