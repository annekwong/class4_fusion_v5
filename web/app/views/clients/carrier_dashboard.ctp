<style>
    .tabsbar.tabsbar-2 ul li.active a i:before {
        /*color: #E5412D;*/
    }

    .btn-primary {
        /*background: #E5412D;*/
        /*border-color: #E5412D;*/
        color: #FFF;
        border: 0px;
    }

    .btn-group > .btn{
        border: 0px;
    }
    .btn-group > .btn:hover {
        z-index: auto;
        border: 0px;
        color: #ffffff;
    }

    /*.btn-default:hover {*/
    /*background: #E5412D;*/
    /*border-color: #E5412D;*/
    /*border: 0px;*/
    /*color: #ffffff;*/
    /*}*/

    .input-group-btn.open .btn-primary.dropdown-toggle, .btn-primary.disabled, .btn-primary[disabled], .btn-primary:hover, .btn-primary:focus {
        /*background: #DD301B;*/
        color: #FFF;
        /*border-color: #E5412D;*/
        border: 0px;
    }

    .table-primary thead th {

        /*background-color: #E5412D;*/

    }

    h3.glyphicons i:before, h2.glyphicons i:before {
        /*color: #E5412D;*/
    }

    .dropdown-menu > li {
        clear: both;
        color: #333333;
        display: block;
        font-weight: normal;
        line-height: 20px;
        padding: 3px 20px;
        white-space: nowrap;
    }

    .dropdown-menu > li:hover, .dropdown-menu > li:focus {
        /*background-color: #f5f5f5;*/
        color: #fff;
        text-decoration: none;
    }

    .pagination ul > .active > a, .pagination ul > .active > span {
        /*background: #E5412D none repeat scroll 0 0;*/
        /*border-color: #E5412D;*/
        color: #fff;
    }

    .pagination ul > li > a:hover, .pagination ul > li.primary > a {
        /*background: #E5412D none repeat scroll 0 0;*/
        /*border-color: #E5412D;*/
        color: #fff;
        text-shadow: none;
    }

    .pagination ul > li > a:hover, .pagination ul > li > a:focus, .pagination ul > .active > a, .pagination ul > .active > span {
        /*background-color: #E5412D;*/
    }

    .row-fluid [class*="span"] {
        margin: 0;
    }

    .scroll-y {
        height: 250px;
        overflow-y: auto;
    }

    .disabled {
        pointer-events:none;
        opacity:0.6;
    }

</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client Dashboard') ?></li>
</ul>
<div>
    <hr/>
</div>
<div class="innerLR">
    <div class="innerB">
        <h1 class="margin-none pull-left "><?php __('Current Day Traffic') ?> &nbsp;<i
                    class="fa fa-fw fa-pencil text-muted"></i></h1>

        <div class="btn-group pull-right">
            <a href="<?php echo $this->webroot; ?>clients/carrier_dashboard" class="btn btn-primary"><i
                        class="fa fa-fw fa-bar-chart-o"></i> <?php __('Analytics') ?></a>
            <a href="<?php echo $this->webroot; ?>clients/carrier/true" class="btn btn-default"><i
                        class="fa fa-fw fa-user"></i> <?php __('Account') ?></a>
            <a href="<?php echo $this->webroot; ?>clients/messages" class="btn btn-default"><i
                        class="fa fa-fw fa-dashboard"></i> <?php __('Messages') ?></a>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Widget -->
            <div class=" widget widget-body-white ">
                <div class="widget-body padding-none ">

                    <!--                    第一部分：  ajax_text1   type:1-->
                    <div class="innerAll" style="margin: 15px;border: 1px solid #efefef">
                        <div class="btn-group btn-group-sm pull-right" style="margin: 20px">

                            <!--                                    时间点 -->
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="text-time1-button"
                                                                                            value="24"><?php __('Last 24-Hour') ?></span> <span
                                        class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" id="text-time1">
                                <li value="1">Last Hour</li>
                                <li value="24">Last 24-Hour</li>
                            </ul>
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_text1_refresh">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a></button>
                        </div>
                        <div class="clearfix"></div>

                        <div class="row row-merge" style="margin:0;border: 1px solid #efefef">
                            <div class="span3" style="width: 25%;margin-left: 0;">
                                <div class="innerAll inner-2x text-center">
                                    <h5><?php __('Volume') ?> (min)</h5>
                                    <h4 class="text-medium text-primary text-condensed" id="text-volume"
                                        style="height: 35px">
                                        <img src='<?php echo $this->webroot ?>images/check_waiting.gif'/>
                                    </h4>
                                </div>
                            </div>
                            <div class="span3" style="width: 25%;margin-left: 0;">
                                <div class="innerAll  inner-2x bg-gray text-center">
                                    <h5><?php __('Spending') ?> (USD)</h5>
                                    <h4 class="text-medium text-primary text-condensed" id="text-spending"
                                        style="height: 35px">
                                        <img src='<?php echo $this->webroot ?>images/check_waiting.gif'/>
                                    </h4>
                                </div>
                            </div>
                            <div class="span3" style="width: 25%;margin-left: 0;">
                                <div class="innerAll inner-2x text-center">
                                    <h5><?php __('Calls') ?></h5>
                                    <h4 class="text-medium text-primary text-condensed" id="text-calls"
                                        style="height: 35px">
                                        <img src='<?php echo $this->webroot ?>images/check_waiting.gif'/>
                                    </h4>
                                </div>
                            </div>
                            <div class="span3" style="width: 25%;margin-left: 0;">
                                <div class="innerAll  inner-2x bg-gray text-center">
                                    <h5><?php __('Non-Zero') ?></h5>
                                    <h4 class="text-medium text-primary text-condensed" id="text-non-zero"
                                        style="height: 35px">
                                        <img src='<?php echo $this->webroot ?>images/check_waiting.gif'/>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>

                    <div class="innerAll">
                        <div class="box-generic">

                            <!-- Tabs Heading -->
                            <div class="tabsbar tabsbar-2 row-fluid">
                                <ul class="row-merge">
                                    <!--                                    <li class="span3 glyphicons no-js stats tab-opt active" style="width: 25%"><a-->
                                    <!--                                            href="#tab1-4" data-toggle="tab" class=""><i></i>-->
                                    <!--                                            --><?php //__('Call Attempts') ?><!--</a>-->
                                    <!--                                    </li>-->
                                    <li class="span3 glyphicons no-js stats tab-opt active" style="width: 100%"><a
                                                href="#tab2-4"
                                                data-toggle="tab"><i></i>
                                            <?php __('Connected Calls') ?></a>
                                    </li>
                                    <!--                                    <li class="span3 glyphicons no-js stats tab-opt" style="width: 25%"><a-->
                                    <!--                                            href="#tab3-4"-->
                                    <!--                                            data-toggle="tab"><i></i>-->
                                    <!--                                            --><?php //__('Volume') ?><!--</a>-->
                                    <!--                                    </li>-->
                                    <!--                                    <li class="span3 glyphicons no-js stats tab-opt" style="width: 50%"><a-->
                                    <!--                                            href="#tab4-4"-->
                                    <!--                                            data-toggle="tab"><i></i> --><?php //__('Cost') ?>
                                    <!--                                        </a>-->
                                    <!--                                    </li>-->
                                </ul>
                            </div>
                            <!-- // Tabs Heading END -->

                            <div class="tab-content row-fluid" id="ajax_chart1_isload" value="1">

                                <!--                    第二部分：  ajax_chart1   type:2-->
                                <div class="btn-group pull-right">
                                    <button class="btn btn-primary btn-sm innerLR " id="ajax_chart1_refresh">

                                        <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                                    </button>
                                </div>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-primary btn-sm innerLR "  data-toggle="dropdown" style="margin-right: 25px">
                                        <span id="chart-trunk-button"  value="null"><?php __('All Trunks') ?></span>
                                        <span  class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu <?php if (count($ingress_list) > 6) echo 'scroll-y' ?>" role="menu" id="chart-trunk">
                                        <li value="0">All Trunks</li>
                                        <li value="-1">Top 5 Trunks</li>
                                        <li value="-2">Top 10 Trunks</li>
                                        <li value="-3">All Active Trunks</li>
                                        <li class="disabled">--------------</li>
                                        <?php foreach ($ingress_list as $v): ?>
                                            <li value="<?php echo $v[0]['resource_id'] ?>"><?php echo $v[0]['alias'] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="btn-group btn-group-sm pull-right" style="margin-right: 20px;">

                                    <!--                                    时间点 -->
                                    <button class="btn btn-primary btn-sm innerLR "
                                            data-toggle="dropdown"><span
                                                id="chart-time1-button"
                                                value="1"><?php __('Last 24-Hour') ?></span>
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu" id="chart-time1">
                                        <li value="1">Last 24-Hour</li>
                                        <li value="7">Last 7-Days</li>
                                        <li value="15">Last 15-Days</li>
                                        <li value="30">Last 30-Days</li>
                                        <li value="60">Last 60-Days</li>
                                    </ul>

                                </div>


                                <div class="clearfix"></div>
                                <!-- Tab content -->

                                <!--                                <div class="tab-pane span12" id="tab1-4">-->
                                <!---->
                                <!--                                    <div id="chart-call-attempts" value="call_attempts" tip="" title="Call Attempts"-->
                                <!--                                         style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>-->
                                <!--                                </div>-->

                                <!-- // Tab content END -->

                                <!-- Tab content -->
                                <div class="tab-pane span12" id="tab2-4">
                                    <!--label><?php __('Trunk List') ?>:</label>
                                    <select id="trunks" class="input-medium" style="width: 120px">
                                        <?php foreach ($trunks as $trunk) {
                                        echo '<option value="'.$trunk[0]['resource_id'].'">'.$trunk[0]['alias'].'</option>';
                                    }
                                    ?>
                                    </select-->
                                    <div id="chart-connected-calls" value="connected-calls" tip="" title="Connected Calls"
                                         style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>
                                    <!--                                    <div id="chart-non-zero" value="non_zero" tip="" title="Non Zero"-->
                                    <!--                                         style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>-->
                                </div>
                                <!-- // Tab content END -->

                                <!-- Tab content -->
                                <!--                                <div class="tab-pane span12" id="tab3-4">-->
                                <!--                                    <div id="chart-volume" value="volume" tip="(/min)" title="Volume"-->
                                <!--                                         style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>-->
                                <!--                                </div>-->
                                <!-- // Tab content END -->

                                <!-- Tab content -->
                                <div class="tab-pane span12" id="tab4-4">
                                    <div id="chart-cost" value="cost" tip="(USD)" title="Cost"
                                         style="height: 400px; min-width: 600px;margin: 0 auto 0"></div>
                                </div>
                                <!-- // Tab content END -->

                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- //Widget -->

            <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>

            <div class="row-fluid">
                <div class="span8 pull-left" style="width: 68%">

                    <div class=" innerAll half">

                        <div >
                            <h2 class="pull-left glyphicons table "><i></i> <?php __('Demographic') ?>
                                <small id="ajax_table1_time_intval"></small>
                            </h2>
                        </div>

                        <!--                    第四部分：  ajax_table1   type:4-->
                        <div class="btn-group btn-group-sm pull-right">



                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="table1-trunk-button"
                                                                                            value="0"><?php __('All Trunks') ?></span> <span
                                        class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" id="table1-trunk">
                                <li value="0"><?php __('All Trunks') ?></li>
                                <?php foreach ($ingress_list as $v): ?>
                                    <li value="<?php echo $v[0]['resource_id'] ?>"><?php echo $v[0]['alias'] ?></li>
                                <?php endforeach; ?>
                            </ul>


                            <button class="btn btn-primary btn-sm innerLR " id="ajax_table1_refresh">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                            </button>

                        </div>
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="table-time1-button"
                                                                                            value="24"><?php __('Last 24-Hour') ?></span> <span
                                        class="caret"></span></button>
                            <ul class="dropdown-menu" id="table-time1" role="menu">
                                <li value="1"><?php __('Last Hour') ?></li>
                                <li value="24"><?php __('Last 24 Hours') ?></li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="overflow_x">
                        <table
                                class="list table-hover footable table table-striped tableTools table-bordered  table-white table-primary footable-loaded default">

                            <!-- Table heading -->
                            <thead>
                            <tr id="demographic-th" value="0">
                                <th><?php __('Code Name') ?></th>
                                <!--                                <th>-->
                                <!--                                    <a href="#" onclick="return false;"-->
                                <!--                                       class="sorting btn btn-primary btn-icon sustom_sort demographic-sort"-->
                                <!--                                       value="ingress_code_name">--><?php //__('Code Name') ?><!--</a>-->
                                <!--                                </th>-->
                                <th><?php __('Attempt') ?></th>
                                <!--                                <th>-->
                                <!--                                    <a href="#" onclick="return false;"-->
                                <!--                                       class="sorting btn btn-primary btn-icon sustom_sort demographic-sort"-->
                                <!--                                       value="ingress_total_calls">--><?php //__('Attempt') ?><!--</a>-->
                                <!--                                </th>-->
                                <th><?php __('Non Zero') ?></th>
                                <th><?php __('Min') ?></th>
                                <th><?php __('Cost') ?></th>
                                <th><?php __('ASR') ?></th>
                                <th><?php __('ACD') ?></th>

                            </tr>

                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody id="demographic-tbody">
                            <tr class="demographic-clone-tr">
                                <td></td>
                                <td></td>
                                <td></td>

                                <td></td>
                                <td></td>

                                <td></td>
                                <td></td>
                            </tr>


                            </tbody>
                            <!-- // Table body END -->

                        </table>
                        <!-- // Table END -->
                        <div class="row-fluid separator">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <ul class="pagination demographic-ul" page-now="1" page-opt="0" page-num="0">
                                    <li>
                                        <a class="page-first" onclick="return false;" value="first"
                                           id="first_page">««</a>
                                    </li>
                                    <li>
                                        <a class="page-prev" onclick="return false;" value="prev" id="prev_page">«</a>
                                    </li>
                                    <li class="demographic-page-num">
                                        <a href="#" onclick="return false;" value="" class="page"></a>
                                    </li>
                                    <li class="demographic-add-page">
                                        <a class="page-next" onclick="return false;" value="next" id="next_page">»</a>
                                    </li>
                                    <li>
                                        <a class="page-last" onclick="return false;" value="last" id="last_page">»»</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>

                    <div class=" innerAll half">

                        <div class="pull-left">
                            <h2 class="pull-left glyphicons table  "><i></i> <?php __('Traffic Breakdown') ?>
                            </h2>
                        </div>
                        <!--                    第五部分：  ajax_table2   type:5-->
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown" style="margin-right: 25px"><span id="table-trunk-button"
                                                                                            value="0"><?php __('All Trunks') ?></span> <span
                                        class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" id="table-trunk">
                                <li value="0"><?php __('All Trunks') ?></li>
                                <?php foreach ($ingress_list as $v): ?>
                                    <li value="<?php echo $v[0]['resource_id'] ?>"><?php echo $v[0]['alias'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_table2_refresh">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                            </button>
                        </div>
                        <div class="btn-group btn-group-sm pull-right"
                             style="width: 20px;height: 30px;background-color: white">
                        </div>
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR "
                                    data-toggle="dropdown"><span id="table-time2-button"
                                                                 value="24"><?php __('Last 24-Hour') ?></span> <span
                                        class="caret"></span></button>
                            <ul class="dropdown-menu" id="table-time2" role="menu">
                                <li value="24"><?php __('Last 24 Hours') ?></li>
                                <li value="7"><?php __('Last 7 days') ?></li>
                            </ul>


                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="overflow_x">
                        <table
                                class="list table-hover footable table table-striped tableTools table-bordered  table-white table-primary footable-loaded default">

                            <!-- Table heading -->
                            <thead>
                            <tr id="traffic-th" value="0">
                                <th><?php __('Time Period') ?></th>
                                <!--                                <th>-->
                                <!--                                    <a href="#" onclick="return false;"-->
                                <!--                                       class="sorting btn btn-primary btn-icon sustom_sort traffic-sort"-->
                                <!--                                       value="report_time">--><?php //__('Time Period') ?><!--</a>-->
                                <!--                                </th>-->
                                <th><?php __('Attempt') ?></th>
                                <!--                                <th>-->
                                <!--                                    <a href="#" onclick="return false;"-->
                                <!--                                       class="sorting btn btn-primary btn-icon sustom_sort traffic-sort"-->
                                <!--                                       value="total_calls">--><?php //__('Attempt') ?><!--</a>-->
                                <!--                                </th>-->
                                <th><?php __('Non Zero') ?></th>
                                <th><?php __('Min') ?></th>
                                <th><?php __('Cost') ?></th>
                                <th><?php __('ASR') ?></th>
                                <th><?php __('ACD') ?></th>

                            </tr>

                            </thead>
                            <!-- // Table heading END -->

                            <!-- Table body -->
                            <tbody id="traffic-tbody">
                            <tr class="traffic-clone-tr">
                                <td></td>
                                <td></td>
                                <td></td>

                                <td></td>
                                <td></td>

                                <td></td>
                                <td></td>


                            </tr>


                            </tbody>
                            <!-- // Table body END -->

                        </table>
                        <!-- // Table END -->
                        <!--                        <div class="row-fluid separator">-->
                        <!--                            <div class="pagination pagination-large pagination-right margin-none">-->
                        <!--                                <ul class="pagination traffic-ul" page-now="1" page-opt="0" page-num="0">-->
                        <!--                                    <li>-->
                        <!--                                        <a class="page-first" onclick="return false;" value="first"-->
                        <!--                                           id="first_page">««</a>-->
                        <!--                                    </li>-->
                        <!--                                    <li>-->
                        <!--                                        <a class="page-prev" onclick="return false;" value="prev" id="prev_page">«</a>-->
                        <!--                                    </li>-->
                        <!--                                    <li class="traffic-page-num">-->
                        <!--                                        <a href="#" onclick="return false;" value="" class="page"></a>-->
                        <!--                                    </li>-->
                        <!--                                    <li class="traffic-add-page">-->
                        <!--                                        <a class="page-next" onclick="return false;" value="next" id="next_page">»</a>-->
                        <!--                                    </li>-->
                        <!--                                    <li>-->
                        <!--                                        <a class="page-last" onclick="return false;" value="last" id="last_page">»»</a>-->
                        <!--                                    </li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="span4 pull-right" style="width: 30%;min-width: 320px;margin-left: 0;">


                    <!--                    第三部分：  ajax_text2   type:3-->
                    <div class=" innerAll half">


                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-primary btn-sm innerLR " id="ajax_text2_refresh">

                                <a href="" onclick="return false" style="color: #fff;">Refresh <i class="fa fa-refresh"></i></a>
                            </button>
                        </div>
                        <h2 class="pull-left glyphicons compass "><i></i> <?php __('ASR/ACD') ?></h2>

                        <div class="clearfix"></div>
                    </div>

                    <div style="100%;margin-top: 10px">
                        <div class="widget innerAll text-center">
                            <h4><?php __('Last 24 Hours ASR') ?></h4>

                            <p class="innerTB inner-2x text-xlarge text-condensed strong text-primary"
                               id="text-asr"></p>
                        </div>
                    </div>
                    <div style="100%">
                        <div class="widget innerAll text-center">
                            <h4><?php __('Last 24 Hours ACD') ?></h4>

                            <p class="innerTB inner-2x text-xlarge text-condensed strong text-primary"
                               id="text-acd"></p>
                        </div>
                    </div>


                    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>

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


        <!-- //End Widget -->
    </div>
    <!-- //End Col -->
</div>


</div>

<script src="<?php echo $this->webroot; ?>highstock/highstock.js"></script>
<script src="<?php echo $this->webroot; ?>highstock/modules/exporting.js"></script>
<script>
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    //自动更新时间
    var auto_time_ajax_chart1 = 1000 * 60;
    var auto_time_other = 5 * 60 * 1000;
    var setinterval_ajax_chart1;


    //第一部分：  ajax_text1
    function ajax_text1() {
        var non_zero = $('#text-non-zero');
        var calls = $('#text-calls');
        var spending = $('#text-spending');
        var volume = $('#text-volume');


        non_zero.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        calls.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        spending.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        volume.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");

        //获得时间段
        var time = $('#text-time1-button').attr('value');


        $.getJSON(
            "<?php echo $this->webroot ?>clients/get_dashboard_data/1/" + time,
            function (data) {


                non_zero.html(data['non_zero']);
                calls.html(data['calls']);
                spending.html(data['spending']);
                volume.html(data['volume']);
            }
        );


    }


    //第二部分：  ajax_chart1
    //type=true,本地刷新
    //var local_data = null;
    function ajax_chart1() {


        //获得时间段
        var time = $('#chart-time1-button').attr('value');


        var tab_div = arguments[0];
        var tab_value = tab_div.attr('value');
        var tip = tab_div.attr('tip');
        var title = tab_div.attr('title');
        var trunk = $('#chart-trunk-button').attr('value');
//        var i = 1;


        clearInterval(setinterval_ajax_chart1);

        var show_arr = new Array();
        show_arr[0] = ['chart_call','Connected Calls','call'];
        var minTickInterval = 1;
        if (tab_value == 'connected-calls') {
            jQuery.ajax({
                'url': "<?php echo $this->webroot ?>homes/get_draws_data_client",
                'type': "POST",
                'data': {
                    'trunk': trunk,
                    'duration': time
                },
                "dataType": "json",
                "success": function (data) {
                    $('#chart-connected-calls').highcharts('StockChart', {
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
                            }, /* {
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
                            minTickInterval: minTickInterval
                        },
                        title: {
                            text: show_arr[0][1]
                        },
                        series: data[show_arr[0][2]]
                    });
                }
            });$
        } else if (time == 1) {
            $.post(
                "<?php echo $this->webroot ?>clients/get_dashboard_data/2/" + time + "/" + 1,
                {'tab_value': tab_value},
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
                                            "<?php echo $this->webroot ?>clients/get_dashboard_data/2/" + time,
                                            {'interval': auto_time_ajax_chart1 / 1000,'tab_value': tab_value,'iden':iden},
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
                            data: data['point'],
                            color: "#E5412D"
                        }]
                    });

                },
                'json'
            );
        } else {
            $.post(
                "<?php echo $this->webroot ?>clients/get_dashboard_data/2/" + time + "/" + 1,
                {'tab_value': tab_value},
                function (data) {

                    var max_time = data['max_time'];

                    tab_div.highcharts('StockChart', {
                        chart: {
                            type: 'spline',
                            animation: Highcharts.svg, // don't animate in old IE
                            marginRight: 10
                        },
                        rangeSelector: {
                            selected: 8,
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
                            data: data['point'],
                            color: "#E5412D"
                        }]
                    });

                },
                'json'
            );
        }


    }


    //第三部分：  ajax_text2
    function ajax_text2() {
        var asr = $('#text-asr');
        var acd = $('#text-acd');
        asr.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");
        acd.html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");

        $.getJSON(
            "<?php echo $this->webroot ?>clients/get_dashboard_data/3",
            function (data) {
                asr.html(data['asr']);
                acd.html(data['acd']);
            }
        );


    }

    //第四部分：  ajax_table1
    // page_now 当前页
    // page_num 页面总数
    // page_opt 翻页操作
    // sort排序
    function ajax_table1() {
        $('.demographic-clone-tr:not(:first)').remove();
        var tr = $('.demographic-clone-tr:first').show();


        var page = $('.demographic-page-num:first');

        var page_info = $('.demographic-ul');

        //获得时间段
        var time = $('#table-time1-button').attr('value');

        var sort = $('#demographic-th').attr('value');


        var clone;

        var add_page;

        var page_opt = page_info.attr('page-opt');
        var page_now = page_info.attr('page-now');
        var page_num = page_info.attr('page-num');
        var trunk_id = $('#table1-trunk-button').attr('value');
        tr.find('td').html("<img src='<?php echo $this->webroot?>images/check_waiting.gif'/>");


        //初始化操作
        if (page_opt == '0') {
            //tr.remove();
            $('.demographic-page-num:not(:first)').remove();
            page.remove();


            $.post(
                "<?php echo $this->webroot ?>clients/get_dashboard_data/4/" + time,{
                    'sort': sort,
                    'trunk_id': trunk_id
                },
                function (data) {
                    if (data) {
                        $.each(data['data'], function (index, item) {
                            clone = tr.clone();
                            clone.find('td:eq(0)').html(item['code_name']);
                            clone.find('td:eq(1)').html(item['attempt']);
                            clone.find('td:eq(2)').html(item['non_zero']);
                            clone.find('td:eq(3)').html(item['min']);
                            clone.find('td:eq(4)').html(item['cost']);
                            clone.find('td:eq(5)').html(item['asr']);
                            clone.find('td:eq(6)').html(item['acd']);

                            clone.appendTo('#demographic-tbody');
                        });
                    }

                    $('#ajax_table1_time_intval').html(data['time_intval']);


                    for (var i = 0; i < data['page_num']; i++) {
                        add_page = page.clone();
                        add_page.find('a').html(i + 1);
                        add_page.find('a').attr('value', i + 1);
                        add_page.insertBefore('.demographic-add-page');
                    }

                    $('.demographic-page-num').removeClass('active');
                    $('.demographic-page-num:first').addClass('active');

                    page_info.attr({'page-num': data['page_num'], 'page-now': data['page_now']});

                    $('.demographic-clone-tr:first').hide();
                },
                'json')
        } else {
            //翻页
            $.post(
                "<?php echo $this->webroot ?>clients/get_dashboard_data/4/" + time, {
                    'sort': sort,
                    'page_opt': page_opt,
                    'page_now': page_now,
                    'page_num': page_num
                },
                function (data) {
//                    $('#demographic-tbody tr:not(0)').remove();
                    //tr.remove();

                    if (data['data']) {

                        $.each(data['data'], function (index, item) {
                            clone = tr.clone();
                            clone.find('td:eq(0)').html(item['code_name']);
                            clone.find('td:eq(1)').html(item['attempt']);
                            clone.find('td:eq(2)').html(item['non_zero']);
                            clone.find('td:eq(3)').html(item['min']);
                            clone.find('td:eq(4)').html(item['cost']);
                            clone.find('td:eq(5)').html(item['asr']);
                            clone.find('td:eq(6)').html(item['acd']);

                            clone.appendTo('#demographic-tbody');
                        });
                    }

                    var class_now = data['page_now'] - 1;
                    $('.demographic-page-num').removeClass('active');
                    $('.demographic-page-num:eq(' + class_now + ')').addClass('active');

                    page_info.attr('page-now', data['page_now']);

                    $('.demographic-clone-tr:first').hide();
                },
                'json');
        }


    }


    //第五部分：  ajax_table2
    // page_now 当前页
    // page_num 页面总数
    // page_opt 翻页操作
    function ajax_table2() {
        $('.traffic-clone-tr:not(:first)').remove();
        var tr = $('.traffic-clone-tr:first').show();

        //var page = $('.traffic-page-num:first');

        //var page_info = $('.traffic-ul');

        //获得时间段
        var time = $('#table-time2-button').attr('value');
        var trunk = $('#table-trunk-button').attr('value');

        var sort = $('#traffic-th').attr('value');


        var clone;

        tr.find('td').html("<img src='<?php echo $this->webroot?>images/check_waiting.gif'/>");
//        tr.html("<img src='<?php //echo $this->webroot?>//images/check_waiting.gif' />");
        //var add_page;

        //var page_opt = page_info.attr('page-opt');
        //var page_now = page_info.attr('page-now');
        //var page_num = page_info.attr('page-num');


        //初始化操作
//        if (page_opt == '0') {

//            $('.traffic-page-num:not(:first)').remove();
//            page.remove();

        $.post(
            "<?php echo $this->webroot ?>clients/get_dashboard_data/5/" + time, {'sort': sort,'trunk': trunk},
            function (data) {
                $.each(data['data'], function (index, item) {
                    clone = tr.clone();
                    clone.find('td:eq(0)').html(item['time_period']);
                    clone.find('td:eq(1)').html(item['attempt']);
                    clone.find('td:eq(2)').html(item['non_zero']);
                    clone.find('td:eq(3)').html(item['min']);
                    clone.find('td:eq(4)').html(item['cost']);
                    clone.find('td:eq(5)').html(item['asr']);
                    clone.find('td:eq(6)').html(item['acd']);

                    clone.appendTo('#traffic-tbody');
                });

//                    for (var i = 0; i < data['page_num']; i++) {
//                        add_page = page.clone();
//                        add_page.find('a').html(i + 1);
//                        add_page.find('a').attr('value', i + 1);
//                        add_page.insertBefore('.traffic-add-page');
//                    }
//
//                    $('.traffic-page-num').removeClass('active');
//                    $('.traffic-page-num:first').addClass('active');
//
//                    page_info.attr({'page-num': data['page_num'], 'page-now': data['page_now']});

                $('.traffic-clone-tr:first').hide();
            },
            'json')
//        } else {
//            //翻页
//            $.post(
//                "<?php //echo $this->webroot ?>//clients/get_dashboard_data/5/" + time, {
//                    sort: sort,
//                    trunk: trunk
//                    //page_opt: page_opt,
//                    //page_now: page_now,
//                    //page_num: page_num
//                },
//                function (data) {
//
//
//                    $.each(data['data'], function (index, item) {
//                        clone = tr.clone();
//                        clone.find('td:eq(0)').html(item['time_period']);
//                        clone.find('td:eq(1)').html(item['attempt']);
//                        clone.find('td:eq(2)').html(item['non_zero']);
//                        clone.find('td:eq(3)').html(item['min']);
//                        clone.find('td:eq(4)').html(item['cost']);
//                        clone.find('td:eq(5)').html(item['asr']);
//                        clone.find('td:eq(6)').html(item['acd']);
//
//                        clone.appendTo('#traffic-tbody');
//                    });
//
//
//                    //var class_now = data['page_now'] - 1;
////                    $('.traffic-page-num').removeClass('active');
////                    $('.traffic-page-num:eq(' + class_now + ')').addClass('active');
////
////                    page_info.attr('page-now', data['page_now']);
//
//                    $('.traffic-clone-tr:first').hide();
//                },
//                'json');
//        }


    }

    //第六部分：  ajax_chart2
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
            "<?php echo $this->webroot ?>clients/get_dashboard_data/6/" + time,
            {'which_value': 'minutes'},
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
            "<?php echo $this->webroot ?>clients/get_dashboard_data/6/" + time,
            {'which_value': 'cost'},
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


    $(function () {
        Highcharts.setOptions({
            global: {
                useUTC: true
            }
        });


        //*第一部分：  ajax_text1
        ajax_text1();
        setInterval('ajax_text1();', auto_time_other);
        //ajax_text1 修改时间段
        $('#text-time1 li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#text-time1-button').html(html);
            $('#text-time1-button').attr('value', value);
            ajax_text1();
        });

        $('#ajax_text1_refresh').click(function () {
            ajax_text1();
        })

        //*第二部分：  ajax_chart1

        //切换
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
        //ajax_chart1 修改时间段
        $('#chart-time1 li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#chart-time1-button').html(html);
            $('#chart-time1-button').attr('value', value);

            $('.tab_active').click();
        });

        //刷新
        $('#ajax_chart1_refresh').click(function () {
            $('.tab_active').click();
        })


        //*第三部分：  ajax_text2
        ajax_text2();
        setInterval('ajax_text2();', auto_time_other);
        //刷新
        $('#ajax_text2_refresh').click(function () {
            ajax_text2();
        })


        //*第四部分：  ajax_table1
        ajax_table1();

        //翻页操作
        var page_info = $('.demographic-ul');
        page_info.find('a').live('click', function () {
            var val = $(this).attr('value');
            page_info.attr('page-opt', val);
            ajax_table1();
            return false;
        });
        //修改时间段
        $('#table-time1 li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#table-time1-button').html(html);
            $('#table-time1-button').attr('value', value);
            page_info.attr('page-opt', '0');
//            $('.demographic-sort').removeClass('sorting_asc sorting_desc');
//            $('.demographic-sort').addClass('sorting');
            ajax_table1();
        });
        //排序
        $('.demographic-sort').click(function () {
            var sthis = $(this);

            var name = sthis.attr('value');
            var sort;
            //如果已经为desc，则做asc操作
            if (sthis.hasClass('sorting_desc')) {
                $('.demographic-sort').removeClass('sorting_asc sorting_desc');
                $('.demographic-sort').addClass('sorting');
                sthis.removeClass('sorting');
                sthis.addClass('sorting_asc');

                sort = ' asc ';


            } else {
                $('.demographic-sort').removeClass('sorting_asc sorting_desc');
                $('.demographic-sort').addClass('sorting');
                sthis.removeClass('sorting');
                sthis.addClass('sorting_desc');

                sort = ' desc ';
            }

            sort = ' ' + name + sort;
            page_info.attr('page-opt', '1');
            $('#demographic-th').attr('value', sort);
            ajax_table1();

        });

        $('#ajax_table1_refresh').click(function () {
            page_info.attr('page-opt', '0');
            ajax_table1();
        })


        //*第五部分：  ajax_table2
        ajax_table2();
//        //翻页操作
//        var table2_page_info = $('.traffic-ul');
//        table2_page_info.find('a').live('click', function () {
//            var val = $(this).attr('value');
//            table2_page_info.attr('page-opt', val);
//            ajax_table2();
//            return false;
//        });
        //修改时间段
        $('#table-time2 li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#table-time2-button').html(html);
            $('#table-time2-button').attr('value', value);
//            table2_page_info.attr('page-opt', '0');
            $('.traffic-sort').removeClass('sorting_asc sorting_desc');
            $('.traffic-sort').addClass('sorting');
            ajax_table2();
        });
        //修改trunk
        $('#table-trunk li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#table-trunk-button').html(html);
            $('#table-trunk-button').attr('value', value);
//            table2_page_info.attr('page-opt', '0');
            $('.traffic-sort').removeClass('sorting_asc sorting_desc');
            $('.traffic-sort').addClass('sorting');
            ajax_table2();
        });
        $('#table1-trunk li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#table1-trunk-button').html(html);
            $('#table1-trunk-button').attr('value', value);
//            table1_page_info.attr('page-opt', '0');
            $('.traffic-sort').removeClass('sorting_asc sorting_desc');
            $('.traffic-sort').addClass('sorting');
            ajax_table1();
        });
        $('#chart-trunk li').click(function () {
            var value = $(this).attr('value');
            var html = $(this).html();
            $('#chart-trunk-button').html(html);
            $('#chart-trunk-button').attr('value', value);
            $('.traffic-sort').removeClass('sorting_asc sorting_desc');
            $('.traffic-sort').addClass('sorting');
        });
        $('#chart-trunk li').first().click();
        $('#ajax_chart1_refresh').click();
        //排序
        $('.traffic-sort').click(function () {
            var sthis = $(this);

            var name = sthis.attr('value');
            var sort;
            //如果已经为desc，则做asc操作
            if (sthis.hasClass('sorting_desc')) {
                $('.traffic-sort').removeClass('sorting_asc sorting_desc');
                $('.traffic-sort').addClass('sorting');
                sthis.removeClass('sorting');
                sthis.addClass('sorting_asc');

                sort = ' asc ';


            } else {
                $('.traffic-sort').removeClass('sorting_asc sorting_desc');
                $('.traffic-sort').addClass('sorting');
                sthis.removeClass('sorting');
                sthis.addClass('sorting_desc');

                sort = ' desc ';
            }

            sort = ' ' + name + sort;
            //table2_page_info.attr('page-opt', '1');
            $('#traffic-th').attr('value', sort);
            ajax_table2();

        });

        //刷新
        $('#ajax_table2_refresh').click(function () {
            ajax_table2();
        })

        //*第六部分：  ajax_chart2
        ajax_chart2_minutes();
        ajax_chart2_cost();
        //setInterval('ajax_chart2();', auto_time_ajax_chart1);
        //修改时间段
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
        setInterval(function () {
            $(".fakeloader").hide();
        }, 2000);


    });


</script>