<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Dashboard') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Report') ?></h4>
    <div class="buttons pull-right">
        <a class="link_back btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot; ?>homes/report">
            <i></i><?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard')?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/search_charts"  class="glyphicons charts">
                        <i></i><?php __('Charts')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/auto_delivery"  class="glyphicons stroller">
                        <i></i><?php __('Auto Delivery')?>
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
            <h1 style="padding:0;margin:10px;font-size:14px;text-align:left;float: left">
                <?php
                echo $start_time . '&nbsp' . $end_time . '&nbsp' . $timezone . '(GMT)';
                ?>

            </h1>
<!--            <div id="llloading" style="float: left;padding-top: 15px;"><img  src="--><?php //echo $this->webroot?><!--/images/loading_green.gif" alt="loading..."/></div>-->
            <div class="clearfix"></div>
            <h2 id="showorhiden" style="padding:0;margin:10px;width:100%;font-size:12px;text-align:center;cursor:pointer;">
                <?php __('Show Short/Long Call')?>
            </h2>
            <table id="test"></table>
            <h2 class="msg center" style="display:none;"><?php __('No Result Is Available')?>.</h2>
            <script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
            <script>
                $(function() {
                    var ishide = true;
                    $('#test').treegrid({
                        title:'Results',
                        iconCls:'icon-search',
                        width:'100%',
                        height:460,
                        nowrap: false,
                        title:false,
                        rownumbers: true,
                        animate:true,
                        collapsible:true,
                        url:'<?php echo $this->webroot ?>reportss/get_egress_data?<?php echo $param; ?>',
                        idField:'id',
                        treeField:'originator',
                        frozenColumns:[[
                                {title:'Originator',field:'originator',width:300,
                                    formatter:function(value){
                                        if(!value) value = '[No special name]';
                                        return '<span style="color:red">'+value+'</span>';
                                    }
                                }
                            ]],
                        columns:[[
                                {field:'atts',title:'Atts',width:100},
                                {field:'cc',title:'Cc',width:100,rowspan:2},
                                {field:'mins',title:'Mins',width:100,rowspan:2},
                                {field:'abr',title:'ABR',width:100,rowspan:2},
                                {field:'asr',title:'ASR',width:100,rowspan:2},
                                {field:'acd',title:'ACD',width:100,rowspan:2},
                                {field:'pdd',title:'PDD',width:100,rowspan:2},
//                                {field:'cps',title:'CPS',width:100,rowspan:2},
                                {field:'rev',title:'Rev',width:100,rowspan:2},
                                {field:'cost',title:'Cost',width:100,rowspan:2},
                                {field:'margin',title:'Margin',width:100,rowspan:2},
                                {field:'max_channel_usage',title:'Max Channel Usage',width:130,rowspan:2},
                                {field:'max_channel_allowed',title:'Max Channel Allowed',width:140,rowspan:2},
                                {field:'percentage_of_trunk_usage',title:'Percentage Of Trunk Usage',width:160,rowspan:2}
                            ]],
                        onLoadSuccess:function(row,param){
                            if(!param && param[0].id == 0 && !param[0].children[0].children)
                            {
                                $('.panel').hide();
                                $('#showorhiden').hide();
                                $('.msg').show();
                            }
                            $('#llloading').hide();
                            $('#loading').hide();
                            if(ishide) {
                                $('td[field=call_6s]').hide();
                                $('td[field=call_12s]').hide();
                                $('td[field=call_18s]').hide();
                                $('td[field=call_24s]').hide();
                                $('td[field=call_30s]').hide();
                                $('td[field=call_2h]').hide();
                                $('td[field=call_3h]').hide();
                                $('td[field=call_4h]').hide();
                            }
                        },
                        onBeforeLoad:function(row,param){
                                
                            if (row){
                                var url = '';
                                if(row.type && row.type == 'country') {
                                    url = '<?php echo $this->webroot ?>reportss/get_egress_data2?egress_id='+row.egress_id+'&country='+row.originator+'&<?php echo $param; ?>';
                                } else if(row.type && row.type == 'code_name') {
                                    url = '<?php echo $this->webroot ?>reportss/get_egress_data3?egress_id='+row.egress_id+'&country='+row.country+'&code_name='+row.originator+'&<?php echo $param; ?>';
                                } else {
                                    url = '<?php echo $this->webroot ?>reportss/get_egress_data1?egress_id='+row.egress_id+'&<?php echo $param; ?>';
                                }
                                $(this).treegrid('options').url = url;
                            } else {
                                $(this).treegrid('options').url = '<?php echo $this->webroot ?>reportss/get_egress_data?<?php echo $param; ?>';
                                $('#loading').show();
                            }
                        }
                    });
                    $('#showorhiden').toggle(function(){
                        $(this).text('Hide Short/Long Call');
                        $('td[field=call_6s]').show();
                        $('td[field=call_12s]').show();
                        $('td[field=call_18s]').show();
                        $('td[field=call_24s]').show();
                        $('td[field=call_30s]').show();
                        $('td[field=call_2h]').show();
                        $('td[field=call_3h]').show();
                        $('td[field=call_4h]').show();
                        ishide = false;
                    }, function() {
                        $(this).text('Show Short/Long Call');
                        $('td[field=call_6s]').hide();
                        $('td[field=call_12s]').hide();
                        $('td[field=call_18s]').hide();
                        $('td[field=call_24s]').hide();
                        $('td[field=call_30s]').hide();
                        $('td[field=call_2h]').hide();
                        $('td[field=call_3h]').hide();
                        $('td[field=call_4h]').hide();
                        ishide = true;
                    });
                });
            </script>

            <?php echo $this->element("homes/query")?>
        </div>
    </div>
</div>