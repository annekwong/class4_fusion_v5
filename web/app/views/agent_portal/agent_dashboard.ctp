<link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/carrier/font-awesome.min.css">
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Agent Dashboard') ?></li>
</ul>
<div>
    <hr/>
</div>
<div class="innerLR">
    <div class="widget-body">
        <div class="col-md-8">
            <div class="box-generic">

                <!-- Tabs Heading -->
                <div class="tabsbar tabsbar-2">
                    <ul class="row-fluid row-merge">
                        <li class="span4 glyphicons stats tab-opt active"><a href="#tab1-3" data-toggle="tab"><i></i><?php __('Call'); ?></a>
                        </li>
                        <li class="span4 glyphicons stats tab-opt"><a href="#tab2-3" data-toggle="tab"><i></i> <span><?php __('Channel'); ?></span></a>
                        </li>
                        <li class="span4 glyphicons stats tab-opt"><a href="#tab3-3" data-toggle="tab"><i></i> <span><?php __('CPS '); ?></span></a>
                        </li>
                    </ul>
                </div>
                <!-- // Tabs Heading END -->

                <div class="tab-content innerAll">
                    <div class="btn-group btn-group-sm pull-right">
<span>
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px">
                                <span id="chart-series-button" value="total"><?php __('Total') ?></span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="chart-series" role="menu" style="font-size:14px;">
                                <li data-value="total"><?php __('Total') ?></li>
                                <li data-value="top5"><?php __('Top5') ?></li>
                                <li data-value="all"><?php __('All') ?></li>
                                <?php foreach($clients_balance as $client_balance): ?>
                                    <li data-value="<?php echo $client_balance[0]['client_id']; ?>">
                                        <?php echo $client_balance[0]['name']; ?></li>
                                <?php endforeach; ?>
                            </ul>
    </span>
                        <button class="btn btn-primary btn-sm innerLR "
                                data-toggle="dropdown" style="margin-right: 25px">
                            <span id="chart-time1-button" value="24"><?php __('Last 24 Hours') ?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" id="chart-time1" role="menu">
                            <li value="1"><?php __('Last Hour') ?></li>
                            <li value="2"><?php __('Last 24 Hours') ?></li>
                            <li value="3"><?php __('Last 7-Day') ?></li>
                            <li value="4"><?php __('Last 15-Day') ?></li>
                            <li value="5"><?php __('Last 30-Day') ?></li>
                            <!--                            <li value="6">--><?php //__('Last 60-Day') ?><!--</li>-->
                        </ul>
                        <button class="btn btn-primary btn-sm innerLR " id="ajax_chart1_refresh">
                            <a href="" onclick="return false" style="color: #fff;"><?php __('Refresh');?> <i class="fa fa-refresh"></i></a>
                        </button>
                    </div>


                    <div class="clearfix"></div>
                    <!-- Tab content -->
                    <div class="tab-pane span12 active" id="tab1-3">

                        <div id="chart-call-attempts" value="call_attempts" tip="" title="Call Attempts"
                             style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>
                    </div>

                    <!-- // Tab content END -->

                    <!-- Tab content -->
                    <div class="tab-pane span12" id="tab2-3">
                        <div id="chart-channel" value="channels" tip="" title="Channel"
                             style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>
                    </div>
                    <!-- // Tab content END -->

                    <!-- Tab content -->
                    <div class="tab-pane span12" id="tab3-3">
                        <div id="chart-cps" value="cps" tip="" title="CPS"
                             style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>
                    </div>
                    <!-- // Tab content END -->

                </div>
            </div>

            <div class="row-fluid">
                <div class="span8 pull-left">

                    <div class=" innerAll half">

                        <div class="pull-left">
                            <h2 class="pull-left glyphicons table "><i></i> <?php __('Balance') ?>
                            </h2>
                        </div>

                        <!--                    第四部分：  ajax_table1   type:4-->
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_table1_refresh">
                                <a href="javascript:void(0)" style="color: #fff;"><?php  __('Refresh'); ?><i class="fa fa-refresh"></i></a>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="">
                        <table
                            class="list table-hover footable table table-striped  table-bordered  table-white table-primary footable-loaded default" id="agent_balance_table">

                            <!-- Table heading -->
                            <thead>
                            <tr id="demographic-th" value="0">
                                <th><?php __('Client Name') ?></th>
                                <th><?php __('Available Balance') ?></th>
                            </tr>
                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody class="table_tbody">

                            <?php foreach($clients_balance as $client_balance): ?>
                                <tr>
                                    <td><?php echo $client_balance[0]['name']; ?></td>
                                    <td><?php echo number_format($client_balance[0]['balance'],2); ?></td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <!-- // Table body END -->

                        </table>
                        <!-- // Table END -->
                        <div class="clearfix"></div>
                    </div>

                    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>

                    <div class=" innerAll half">

                        <div class="pull-left">
                            <h2 class="pull-left glyphicons table "><i></i> <?php __('Usage') ?>
                                <small id="ajax_report_table_time_interval"></small>
                            </h2>
                        </div>

                        <!--                    第四部分：  ajax_table1   type:4-->
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="report_table_button"
                                                                                            value="24"><?php __('Last 24 Hours') ?></span> <span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu" id="report_table_time" role="menu">
                                <li value="1"><?php __('Last Hour') ?></li>
                                <li value="24"><?php __('Last 24 Hours') ?></li>
                            </ul>
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_report_table_refresh">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="">
                        <table
                            class="list table-hover footable table table-striped tableTools table-bordered  table-white table-primary footable-loaded default" id="agent_report_table">

                            <!-- Table heading -->
                            <thead>
                            <tr id="demographic-th" value="0">
                                <th><?php __('Client Name') ?></th>
                                <th><?php __('Minutes') ?></th>
                                <th><?php __('Non Zero Call') ?></th>
                                <th><?php __('Cost') ?></th>
                                <th><?php __('Avg Rate') ?></th>
                            </tr>

                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody class="table_tbody">
                            </tbody>
                            <!-- // Table body END -->

                        </table>
                        <!-- // Table END -->

                        <div class="clearfix"></div>
                    </div>


                    <div class="clearfix"></div>
                </div>

                <div class="span4 pull-left">
                    <div class=" innerAll half">


                        <!--                    第六部分：  ajax_chart2   type:6-->
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="minutes-time-button"
                                                                                            value="1"><?php __('Last Hour') ?></span> <span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" id="minutes-time">
                               <li value="1"><?php __('Last Hour') ?></li>
                              <li value="2"><?php __('Last 24-Hours') ?></li>
                              <li value="3"><?php __('Last 7-Days') ?></li>
                            </ul>
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_chart2_refresh_minutes">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a></button>
                        </div>
                        <h2 class="pull-left glyphicons charts "><i></i> <?php __('Minutes') ?></h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="widget widget-body-gray">
                        <div class="widget-body padding-none">
                            <div class="innerAll bg-white border-bottom">
                                <p class="margin-none"><span class="strong" id="minutes-head-title"></span><span
                                        class="strong text-primary" id="minutes-head-text"></span>
                                </p>
                            </div>
                            <div class="widget-body"
                                 style="background-color: white;padding-left:5px;padding-right: 5px">

                                <div id="chart_graph_minutes"
                                     style="height: 200px; min-width: 300px;max-width: 400px; margin: 0 auto 0"></div>

                            </div>
                        </div>
                    </div>
                    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>
                    <div class=" innerAll half">
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="cost-time-button"
                                                                                            value="1"><?php __('Last Hour') ?></span> <span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" id="cost-time">
                               <li value="1"><?php __('Last Hour') ?></li>
                              <li value="2"><?php __('Last 24-Hours') ?></li>
                              <li value="3"><?php __('Last 7-Days') ?></li>
                            </ul>
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_chart2_refresh_cost">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a></button>
                        </div>
                        <h2 class="pull-left glyphicons charts "><i></i> <?php __('Cost') ?></h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="widget widget-body-gray">
                        <div class="widget-body padding-none">
                            <div class="innerAll bg-white border-bottom">
                                <p class="margin-none"><span class="strong" id="cost-head-title"><span
                                            class="strong text-primary" id="minutes-head-text"></span>
                                </p>
                            </div>
                            <div class="widget-body"
                                 style="background-color: white;padding-left:5px;padding-right: 5px">

                                <div id="chart_graph_cost"
                                     style="height: 200px; min-width: 300px;max-width: 400px;  margin: 0 auto 0"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>highstock/highstock.js"></script>
<script src="<?php echo $this->webroot; ?>highstock/modules/exporting.js"></script>
<script type="text/javascript" >
    var auto_time_ajax_chart1 = 1000 * 60;
    var auto_time_other = 5 * 60 * 1000;
    var setinterval_ajax_chart1;
    $(function(){
        var o1Table = $("#agent_balance_table").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row-fluid'<'span3'l><'span4 offset5'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ per page",
            },
            "oTableTools": {
                "sSwfPath": commonPath + "theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            },

        });
        $("#ajax_table1_refresh").click(function(){
            if (o1Table){
                o1Table.fnDestroy();
            }
            $("#agent_balance_table").find('.table_tbody').html('');
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>agent_portal/ajax_get_agent_balance",
                dataType: 'json',
                success: function(data){
                    $.each( data.data, function(i, n){
                        $("#agent_balance_table").find('.table_tbody').append('<tr><td>'+ n[0]+'</td><td>'+ n[1]+'</td></tr>');
                    });
                    o1Table = $("#agent_balance_table").dataTable({
                        "sPaginationType": "bootstrap",
                        "sDom": "<'row-fluid'<'span3'l><'span4 offset5'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                        "oLanguage": {
                            "sLengthMenu": "_MENU_ per page",
                        },
                        "oTableTools": {
                            "sSwfPath": commonPath + "theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
                        },
                    });
                }
            });
        });




        ajax_get_report_table();

        function ajax_get_report_table()
        {
            var table2 = $('#agent_report_table').dataTable();
            if (table2){
                table2.fnDestroy();
            }
            $("#agent_report_table").find('.table_tbody').html('');
            var time_interval = $("#report_table_button").attr('value');
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>agent_portal/ajax_get_agent_report",
                data: {'time_interval':time_interval},
                dataType: 'json',
                success: function(data){
                   $("#agent_report_table").find('.table_tbody').html('');
                    $('#ajax_report_table_time_interval').html(data['time_interval']);
                    if (data.data_count == 0){
                        table2 = $('#agent_report_table').dataTable();
                    }else{
                        $.each( data.data, function(i, n){
                            $("#agent_report_table").find('.table_tbody').append('<tr><td>'+ n['client_name']+'</td><td>'+ n['bill_time']+'</td><td>'+ n['not_zero_calls']+'</td><td>'+ Number(n['call_cost']).toFixed(2)+'</td><td>'+ Number(n['avg_rate']).toFixed(2)+'</td></tr>');
                        });
                        table2 = $("#agent_report_table").dataTable({
                            "sPaginationType": "bootstrap",
                            "sDom": "<'row-fluid'<'span3'l><'span4 offset5'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                            "oLanguage": {
                                "sLengthMenu": "_MENU_ per page",
                            },
                            "oTableTools": {
                                "sSwfPath": commonPath + "theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
                            },
                        });
                    }
                }
            });
        }

        $("#ajax_report_table_refresh").click(function(){
            ajax_get_report_table();
        });


        $('#report_table_time li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#report_table_button').html(html);
            $('#report_table_button').attr('value', value);
            ajax_get_report_table();
        });

        ajax_chart2_minutes();
        ajax_chart2_cost();
        function ajax_chart2_minutes() {
            var minutes = $('#chart_graph_minutes');


            //获得时间段
            var time = $('#minutes-time-button').attr('value');

            //曲线的间距
            var intval;
            if (time == 1) {
                intval = 3600000;
            } else {
                intval = 24 * 3600000;
            }


            $.post(
                "<?php echo $this->webroot ?>agent_portal/get_dashboard_data",
                {'which_value': 'minutes','time_type':time,'chart_type': 2},
                function (data) {
                    minutes.highcharts({
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
                                text: 'Minutes',
                                style: {
                                    color: '#E5412D'
                                }
                            }
                        },
                        exporting: {
                            enabled: false
                        },
                        tooltip: {
                            valueDecimals: 2,
                            valueSuffix: ' (/min)'
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
                                },
                                pointInterval: intval, // one hour
                                pointStart: data['val'][0][0]
                            }
                        },
                        series: [{
                            name: 'Minutes',
                            data: data['val'],
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


                },
                'json'
            )
        }


        function ajax_chart2_cost() {
            var cost = $('#chart_graph_cost');

            //获得时间段
            var time = $('#cost-time-button').attr('value');

            //曲线的间距
            var intval;
            if (time == 1) {
                intval = 3600000;
            } else {
                intval = 24 * 3600000;
            }


            $.post(
                "<?php echo $this->webroot ?>agent_portal/get_dashboard_data",
                {'which_value': 'cost','time_type':time,'chart_type': 2},
                function (data) {
                    cost.highcharts({
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
                                text: 'Cost',
                                style: {
                                    color: '#E5412D'
                                }
                            }
                        },
                        exporting: {
                            enabled: false
                        },
                        tooltip: {
                            valueDecimals: 2,
                            valueSuffix: ' (/USD)'
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
                                },
                                pointInterval: intval, // one hour
                                pointStart: data['val'][0][0]
                            }
                        },
                        series: [{
                            name: 'Cost',
                            data: data['val'],
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


                },
                'json'
            );


        }

        $('#minutes-time li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#minutes-time-button').html(html);
            $('#minutes-time-button').attr('value', value);

            ajax_chart2_minutes();
        });
        $('#cost-time li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();

            $('#cost-time-button').html(html);
            $('#cost-time-button').attr('value', value);
            ajax_chart2_cost();
        });

        //刷新
        $('#ajax_chart2_refresh_minutes').click(function () {
            ajax_chart2_minutes();
        })
        $('#ajax_chart2_refresh_cost').click(function () {
            ajax_chart2_cost();
        })

        var width = $('.tab-content').width();
        $('.tab-pane').width(width);
        $('.tab-pane').hide();
        $('.tab-pane:eq(0)').show();
        $('.tab-opt').click(
            function () {
                var index = $('.tab-opt').index($(this));
                $('.tab-pane').hide();

                $('.tab-opt').removeClass('tab_active');
                $(this).addClass('tab_active');

                var tab = $('.tab-pane:eq(' + index + ')');
                tab.show();
                ajax_chart1(tab.find('div'));


            }
        );
        $('.tab-opt:first').click();
        $('#chart-time1 li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#chart-time1-button').html(html);
            $('#chart-time1-button').attr('value', value);

            $('.tab_active').click();
        });

        $('#chart-series li').click(function () {
            var value = $(this).data('value');
            var html = $(this).html();
            $('#chart-series-button').html(html);
            $('#chart-series-button').attr('value', value);

            $('.tab_active').click();
        });

        //刷新
        $('#ajax_chart1_refresh').click(function () {
            $('.tab_active').click();
        })
        function ajax_chart1() {


            //获得时间段
            var time = $('#chart-time1-button').attr('value');
            var series_type = $('#chart-series-button').attr('value');

            var tab_div = arguments[0];
            var tab_value = tab_div.attr('value');
            var tip = tab_div.attr('tip');
            var title = tab_div.attr('title');
//        var i = 1;


            clearInterval(setinterval_ajax_chart1);

            if (time == 100) {
                $.post(
                    "<?php echo $this->webroot ?>agent_portal/get_dashboard_data",
                    {'which_value': tab_value,'time_type':time,'chart_type': 1,'series_type':series_type},
                    function (data) {

//                    var max_time = data['max_time'];
                        var iden = data['iden'];

                        tab_div.highcharts('StockChart', {
                            chart: {
                                type: 'spline',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10,
                                events: {
                                    load: function () {

                                        // set up the updating of the chart each second
                                        var series = this.series[0];
                                        setinterval_ajax_chart1 = setInterval(function () {
                                            $.post(
                                                "<?php echo $this->webroot ?>agent_portal/get_dashboard_data",
                                                {'interval': auto_time_ajax_chart1 / 1000,'which_value': tab_value,'iden':iden,'time_type':time,'chart_type': 1},
                                                function (sdata) {
//                                                ++i;
                                                    var x = sdata['point'][0];
                                                    var y = sdata['point'][1];
                                                    //max_time = sdata['max_time'][0];
                                                    series.addPoint([x, y], true, true);
                                                },
                                                'json');
                                        }, auto_time_ajax_chart1);

                                    }
                                }
                            },
                            rangeSelector: {
                                selected: 0,
                                buttonTheme: {
                                    width: 35
                                },
                                buttons: [{
                                    type: 'hour',
                                    count: 0.5,
                                    text: '30m'
                                }, {
                                    type: 'hour',
                                    count: 1,
                                    text: '1h'
                                }, {
                                    type: 'day',
                                    count: 0.5,
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
                                }, {
                                    type: 'month',
                                    count: 2,
                                    text: '2m'
                                }, {
                                    type: 'all',
                                    text: 'All'
                                }]
                            },
                            title: {
                                text: title,
                                style: {
                                    color: "#E5412D"
                                }

                            },
                            tooltip: {
                                valueDecimals: 2,
                                valueSuffix: tip
                            },
                            series: [{
                                name: title,
                                data: data[tab_value],
                                color: "#E5412D"
                            }]
                        });

                    },
                    'json'
                );
            } else {
                $.post(
                    "<?php echo $this->webroot ?>agent_portal/get_dashboard_data",
                    {'which_value': tab_value,'time_type':time,'chart_type': 1,'series_type':series_type},
                    function (data) {
                        tab_div.highcharts('StockChart', {
                            chart: {
                                type: 'spline',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10//,
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
                                    type: 'all',
                                    text: 'All'
                                }]
                            },
                            yAxis: {
                                min: 0,
                                minTickInterval:1
                            },
                            title: {
                                text: title
                            },
                            series: data[tab_value],
                            color: "#E5412D"
                        });
                    },
                    'json'
                );
            }


        }
        setInterval(function () {
         $(".fakeloader").hide();
        }, 2000);


    });
</script>