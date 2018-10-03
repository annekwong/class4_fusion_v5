<style>
    #stats-period{display: inline-block}
    input[type="text"]{width: 220px;}
</style>

<?php echo $this->element('magic_css');?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot . $this->params['url']['url'] ?>"><?php __('Code Based Report'); ?></a>
    </li>
</ul>

<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>reports_db/code_based_report" class="glyphicons list">
                        <i></i>
                        <?php __('Report Request') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>reports_db/code_based_report_log" class="glyphicons book_open">
                        <i></i>
                        <?php __('Report Log') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <!-- start -->
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
                <div class="pull-right" title="Advance">
                    <a id="advance_btn" class="btn" href="javascript:void(0)">
                        <i class="icon-long-arrow-down"></i>
                    </a>
                </div>
                <div class="clearfix"></div>
                <?php
                    echo $form->create('CBR', array('type' => 'post', 'url' => '/reports_db/code_based_report', 'id' => 'report_form'));
                ?>
                <?php echo $this->element('search_report/search_js'); ?>
                <?php echo $this->element('search_report/search_hide_input'); ?>
                <table class="form">
                    <tr class="period-block">
                        <td colspan="8" style="width:auto;">
                            <table class="in-date">
                                <tbody>
                                    <tr style="text-align: center;">
                                        <td class="align_right padding-r10"><?php __('time') ?> </td>
                                        <td style="width:100px; text-align: left;">
                                        <?php
                                            $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true), 'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                            $s = 'curDay';
                                            echo $form->input('smartPeriod', array('options' => $r, 'label' => false,
                                                'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod', 'style' => 'width:90px;', 'div' => false, 'type' => 'select', 'selected' => $s));
                                        ?>
                                        </td>
                                        <td style="width:200px">
                                            <input type="text" id="query-start_date-wDt" class="in-text input  wdate" onchange="setPeriod('custom')"
                                                readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                name="start_date" style="width: 80px;" >&nbsp;
                                            <input type="text" id="query-start_time-wDt" class="hidden hide"
                                                   readonly="readonly" disabled value=""
                                                   name="" style="display: none;" >
                                            <td style="width:auto;">&mdash;</td>
                                            <td style="width:200px">
                                                <input type="text" id="query-stop_date-wDt"
                                                    class="in-text input  wdate" onchange="setPeriod('custom')"
                                                    readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                    name="stop_date" style="width: 80px;">&nbsp;
                                                <input type="text" id="query-stop_time-wDt" class="hidden hide"
                                                       readonly="readonly" disabled value=""
                                                       name="" style="display: none;" >
                                            </td>
                                            <td>
                                                <select style="width:120px;" name="show_type">
                                                    <option value="csv">CSV</option>
                                                    <option value="email">Email When Done</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="submit" value="<?php echo __('query', true); ?>" id="formquery"  	class="btn btn-primary margin-bottom10">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php echo ''; ?>
                </table>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <table class="form" style="width:100%">
                        <tr class="period-block" style="height:20px; line-height:20px;">
                            <td colspan="2" style="text-align:center; font-size:14px;"><b></b></td>
                            <td class="in-out_bound">&nbsp;</td>
                            <td colspan="2" style="text-align:center;font-size:14px;"><b></b></td>
                            <td class="in-out_bound">&nbsp;</td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Ingress Trunk')?></td>
                            <td class="value">
                                <select class="trunk_options_ingress use-select2" name="ingress_id" onchange="getTechPrefix(this);">
                                    <option></option>
                                    <?php foreach ($ingress_trunks as $ing) { ?>
                                        <option value="<?php echo $ing[0]['resource_id']; ?>" <?php
                                            if (isset($_GET['ingress_id']) && !strcmp($_GET['ingress_id'], $ing[0]['resource_id'])) {
                                                echo "selected='selected'";
                                            }
                                        ?>><?php echo $ing[0]['alias']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
                            <td class="align_right padding-r10"><?php __('Egress Trunk')?></td>
                            <td class="value">
                                <select class="trunk_options_egress use-select2" name="egress_id">
                                    <option></option>
                                    <?php foreach ($egress_trunks as $eg) { ?>
                                        <option value="<?php echo $eg[0]['resource_id']; ?>" <?php
                                            if (isset($_GET['egress_id']) && !strcmp($_GET['egress_id'], $eg[0]['resource_id'])){
                                                echo "selected='selected'";
                                            }
                                        ?>><?php echo $eg[0]['alias']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td rowspan="4">&nbsp;</td>
                            <td rowspan="4"><!--Group By:--></td>
                        </tr>
                    </table>
                </div>
            </fieldset>

            <script type="text/javascript">

                var $routeprefix = $("#CdrRoutePrefix");
                var $ingress_rate_table = $('#ingress_rate_table');
                var $ingress_routing_plan = $('#ingress_routing_plan');

                $(function() {
                    $('a#advance_btn').click(function(){
                        $('#show_hide_columns').attr('value','1');
                    });

                    $('.client_options_ingress').live('change', function() {
                        var $this = $(this);
                        value = $this.val();
                        var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=ingress&trunk_type2=0'});
                        data = eval(data);
                        var temp1 = $('.trunk_options_ingress').val();

                        $('.trunk_options_ingress').html('');
                        jQuery('<option>').appendTo($('.trunk_options_ingress'));
                        for (var i in data) {
                            var temp = data[i];
                            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_ingress'));
                        }
                        $('.trunk_options_ingress').val(temp1);
                    });

                    $('.client_options_egress').live('change', function() {
                        var $this = $(this);
                        value = $this.val();
                        var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=egress&trunk_type2=0'});
                        data = eval(data);
                        var temp1 = $('.trunk_options_ingress').val();

                        $('.trunk_options_egress').html('');
                        jQuery('<option>').appendTo($('.trunk_options_egress'));
                        for (var i in data) {
                            var temp = data[i];
                            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_egress'));
                        }
                        $('.trunk_options_egress').val(temp1);
                    });


                    $(".ColVis_collection").find('button').live('click',function(){
                        var is_checked = $(this).find(":checkbox").is(":checked");
                        var index = $(this).index();
                        $("#query-fields").find('option').eq(index).attr('selected',is_checked)
                    });
                })

                function getTechPrefix(obj) {
                    var $this = $(obj);
                    var val = $this.val();
                    $routeprefix.empty();
                    $ingress_rate_table.empty();
                    $ingress_routing_plan.empty();
                    $routeprefix.append("<option value='all'>All</option>");
                    $ingress_rate_table.append("<option value='all'>All</option>");
                    $ingress_routing_plan.append("<option value='all'>All</option>");
                    if (val != '0') {

                        $.post("<?php echo $this->webroot ?>cdrreports_db/getTechPerfix", {ingId: val},
                                function(data) {
                                    $.each(data.prefixes,
                                            function(index, content) {
                                                $routeprefix.append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                                            }
                                    );
                                    $.each(data.rate_tables,
                                            function(index, content) {
                                                $ingress_rate_table.append("<option value='" + content[0]['rate_table_id'] + "'>" + content[0]['rate_table_name'] + "</option>");
                                            }
                                    );
                                    $.each(data.routing_plans,
                                            function(index, content) {
                                                $ingress_routing_plan.append("<option value='" + content[0]['route_strategy_id'] + "'>" + content[0]['route_strategy_name'] + "</option>");
                                            }
                                    );
                                }, 'json');

                    }

                }

                function clear_prefix(obj) {
                    var $this = $(obj);
                    $(obj).prev().find('option:first').attr('selected', true);
                }

            </script>
            <!-- end -->

            <?php echo $form->end(); ?>

            <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
            <script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
            <script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
            <scirpt type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.center.js"></scirpt>
            <script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
        </div>
    </div>
</div>