<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Summary Report') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Summary Report') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <ul>
                    <li>
                        <a href="<?php echo $this->webroot; ?>reports_db/summary/1" class="glyphicons left_arrow">
                            <i></i>
                            <?php __('Origination') ?>
                        </a>
                    </li>

                    <li <?php if ($type == 1) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/cascade_summary/1"
                           class="glyphicons left_arrow">
                            <i></i>
                            <?php __('Origination Cascade Report') ?>
                        </a>
                    </li>

                    <?php
                    if ($is_term == 't') {
                        ?>
                        <li>
                            <a href="<?php echo $this->webroot; ?>reports_db/summary/2" class="glyphicons right_arrow">
                                <i></i>
                                <?php __('Termination') ?>
                            </a>
                        </li>
                        <li <?php if ($type == 2) echo 'class="active"'; ?>>
                            <a href="<?php echo $this->webroot; ?>reports_db/cascade_summary/2"
                               class="glyphicons right_arrow">
                                <i></i>
                                <?php __('Termination Cascade Report') ?>
                            </a>
                        </li>
                        <?php
                    }


                    ?>
                </ul>
        </div>
        <div class="widget-body">
            <?php if ($show_nodata): ?>
                <h1 style="font-size:14px;" class="pull-left"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1>
            <?php endif; ?>
<!--            --><?php //if ($data): ?>
<!--                <div class="toggle-button pull-right" id="switch_group"   data-toggleButton-style-enabled="primary" data-toggleButton-label-enabled="--><?php //__('Group By Date')?><!--" data-toggleButton-label-disabled="--><?php //__('Group By Carrier')?><!--" data-toggleButton-height="30" data-toggleButton-width="200">-->
<!--                    <input type="checkbox" --><?php //echo isset($_GET['group_by']) && $_GET['group_by']=='carrier' ? '' : 'checked="checked"' ?><!-- />-->
<!--                </div>-->
<!--            --><?php //endif; ?>
            <div class="clearfix"></div>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?>
                    <div class="msg center"><h2><?php __('no_data_found') ?></h2></div>
                <?php endif; ?>
            <?php else: ?>
                <div class="overflow_x" style="max-height: 450px">
                    <table class="list table table-bordered  table-white table-primary" style="color:#4B9100;">
                        <thead>
                        <tr>
                            <!--group by 的字段-->
                            <th  rowspan="2"><?php echo $group_by[0]; ?></th>
                            <th  rowspan="2"><?php echo $group_by[1]; ?></th>
                            <!--//group by 的字段-->

<!--                            <th  rowspan="2">--><?php //__('ABR')?><!--</th>-->
                            <th  rowspan="2"><?php __('ASR')?></th>
                            <th  rowspan="2"><?php __('ACD')?></th>
<!--                            <th  rowspan="2">--><?php //__('ALOC')?><!--</th>-->
                            <th  rowspan="2"><?php __('PDD')?></th>
<!--                            <th  rowspan="2">--><?php //__('NER')?><!--</th>-->

                            <!--ingress-->
                            <?php if ($type == '1'): ?>
                                <th  rowspan="2"><?php __('NPR Count')?></th>
                                <th  rowspan="2"><?php __('NPR')?></th>
                                <th  rowspan="2"><?php __('NRF Count')?></th>
                                <th  rowspan="2"><?php __('NRF')?></th>
                                <th  rowspan="2"><?php __('Revenue')?></th>
                                <th  rowspan="2"><?php __('Profit')?></th>
                                <th  rowspan="2"><?php __('Margin')?></th>
                                <th  rowspan="2"><?php __('PP Min')?></th>
                                <th  rowspan="2"><?php __('PP K Calls')?></th>


                            <?php endif; ?>
                            <!--//ingress-->
                            <!--egress-->

                            <!--//egress-->
                            <th  rowspan="2"><?php __('SD Count')?></th>
                            <th  rowspan="2"><?php __('SDP')?></th>
                            <th  rowspan="2"><?php __('Limited')?></th>
                            <th colspan="2"><?php __('Time')?></th>

                            <th  rowspan="2"><?php __('Total Cost')?></th>

                            <th  rowspan="2"><?php __('Inter Cost')?></th>
                            <th  rowspan="2"><?php __('Intra Cost')?></th>
                            <!--rate_display_as-->
                            <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                                <th  rowspan="2"><?php __('Actual Rate')?></th>
                            <?php else: ?>
                                <th  rowspan="2"><?php __('Avg Rate')?></th>
                            <?php endif; ?>
                            <!--//rate_display_as-->
                            <th colspan="4"><?php __('Calls')?></th>


                        </tr>
                        <tr>
                            <th><?php __('Total Duration')?></th>
                            <th><?php __('Total Billable Time')?></th>

                            <th><?php __('Total')?></th>
                            <th><?php __('Not Zero')?></th>
                            <th><?php __('Success')?></th>
                            <th><?php __('Busy')?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- 显示数据                       -->

                        <?php
//                        echo '<pre>';
//                        die(var_dump($data));
                        $i = -1;
                        foreach($data as $k => $item):
                            $i++;
                            ?>
                            <!--                        <span class="switch_body">-->
                            <?php foreach($item as $k2 => $item2):?>
                            <tr class="<?php echo $k2 == 0 ? 'item_total' : 'item_self item_' . $i;?>">
                                <!--group by 的字段-->
                                <td <?php echo $k2 == 0 ? '' : 'class="center"';?>>
                                    <?php
                                    echo $k2 == 0 ?
                                        '<a href="javascript:void(0)" class="switch_a switch_'.$i.'" data-value="'.$i.'"><i class="icon-plus" style="margin-right: 20px"></i></a> ' . (empty($k) ? '--' : $k)
                                        :
                                        '<i class="icon-arrow-right"></i>';
                                    ?>
                                </td>
                                <td><?php echo $k2 == 0 ? '<a href="javascript:void(0)" class="count_a" data-value="'.$i.'" title="counts">' . (count($item) - 1) . '</a>' : (empty($item2['self']) ? '--' : $item2['self']); ?></td>
                                <!--//group by 的字段-->


<!--                                <td>--><?php //echo number_format($item2['abr'], 2); ?><!--%</td>-->
                                <td><?php echo number_format($item2['asr'], 2); ?>%</td>
                                <td><?php echo number_format($item2['acd'], 2); ?></td>
<!--                                <td>--><?php //echo number_format($item2['aloc'], 2); ?><!--</td>-->
                                <td><?php echo number_format($item2['pdd'], 2); ?></td>
<!--                                <td>--><?php //echo number_format($item2['ner'], 2); ?><!--%</td>-->
                                <!--ingress-->
                                <?php if ($type == '1'): ?>
                                    <td><?php echo number_format($item2['npr_count'], 0); ?></td>
                                    <td><?php echo number_format($item2['npr'], 2); ?>%</td>
                                    <td><?php echo number_format($item2['nrf_count'], 0); ?></td>
                                    <td><?php echo number_format($item2['nrf'], 2); ?>%</td>
                                    <td><?php echo number_format($item2['revenue'], 2); ?></td>
                                    <td><?php echo number_format($item2['profit'], 2); ?></td>
                                    <td><?php echo number_format($item2['margin'], 2); ?></td>
                                    <td><?php echo number_format($item2['pp_min'], 5); ?></td>
                                    <td><?php echo number_format($item2['pp_k_calls'], 5); ?></td>
                                <?php endif; ?>
                                <!--//ingress-->
                                <td><?php echo number_format($item2['sd_count'], 0); ?></td>
                                <td><?php echo number_format($item2['sdp'], 2); ?>%</td>
                                <td><?php echo number_format($item2['limited'], 2); ?></td>

                                <td><?php echo number_format($item2['duration'], 2); ?></td>
                                <td><?php echo number_format($item2['bill_time'], 2); ?></td>

                                <td><?php echo number_format($item2['call_cost'], 5); ?></td>

                                <!--                                    --><?php //if (isset($_GET['show_inter_intra'])): ?>
                                <td><?php echo number_format($item2['inter_cost'], 5); ?></td>
                                <td><?php echo number_format($item2['intra_cost'], 5); ?></td>
                                <!--                                    --><?php //endif; ?>

                                <td><?php echo number_format($item2['rate'],2) ?></td>

                                <td><?php echo number_format($item2['total_calls']); ?></td>
                                <td><?php echo number_format($item2['not_zero_calls']); ?></td>
                                <td><?php echo number_format($item2['success_calls']); ?></td>
                                <td><?php echo number_format($item2['busy_calls']); ?></td>
                            </tr>
                        <?php endforeach;?>
                            <!--                        </span>-->

                        <?php endforeach; ?>
                        <!-- //显示数据                       -->
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>


            <form id="search_form"
                  method="get" action="<?php echo $this->webroot?>reports_db/cascade_summary/<?php echo $type?>">
                <input type="hidden" id="group_by_input" name="group_by" value="date"/>
                <?php
                echo $form->select('Cdr.field', $select_fields, $select_show_fields, array('id' => 'query-fields',
                    'style' => 'display:none;width:150px; height: 150px;', 'name' => 'query[fields]',
                    'type' => 'select', 'multiple' => true), false);
                ?>
                <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>

                    <div class="clearfix"></div>
                    <table class="form" style="width:100%">

                        <tbody>
                        <tr class="period-block">
                            <td colspan="8" style="width:auto;">
                                <table class="in-date">
                                    <tbody>
                                    <tr>
                                        <td class="align_right padding-r10">Period: </td>
                                        <td style="width:100px; text-align: left;">
                                            <select name="smartPeriod" onchange="setPeriod(this.value)"
                                                    id="query-smartPeriod" style="width:90px;">
                                                <option value="custom">Custom</option>
                                                <option value="curDay" selected="selected">Today</option>
                                                <option value="prevDay">Yesterday</option>
                                                <option value="curWeek">Current week</option>
                                                <option value="prevWeek">Previous week</option>
                                                <option value="curMonth">Current month</option>
                                                <option value="prevMonth">Previous month</option>
                                                <!--                                                <option value="curYear">Current year</option>-->
                                                <!--                                                <option value="prevYear">Previous year</option>-->
                                            </select>
                                        </td>
                                        <td style="width:100px">
                                            <input type="text" id="query-start_date-wDt"
                                                   class="in-text input  wdate"
                                                   onchange="setPeriod('custom')"
                                                   readonly="readonly"
                                                   onkeydown="setPeriod('custom')" value=""
                                                   name="start_date"
                                                   style="width: 80px;"/>
                                            <input style="display: none" type="text" id="query-start_time-wDt"
                                                   onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                   readonly="readonly" style="width: 80px;" value="00:00:01"
                                                   name="start_time" class="input in-text">
                                        </td>
                                        <td style="width:auto;"> — </td>
                                        <td style="width:100px">
                                            <input type="text" id="query-stop_date-wDt"
                                                   class="in-text input  wdate"
                                                   onchange="setPeriod('custom')"
                                                   readonly="readonly"
                                                   onkeydown="setPeriod('custom')" value=""
                                                   name="stop_date"
                                                   style="width: 80px;"/>
                                            <input style="display: none" type="text" id="query-stop_time-wDt"
                                                   onchange="setPeriod('custom')" readonly="readonly"
                                                   onkeydown="setPeriod('custom')" style="width: 80px;"
                                                   value="23:59:59" name="stop_time" class="input in-text">
                                        </td>
                                        <td class="align_right padding-r10"><?php __('Rate display as')?>: </td>
                                        <td></td>
                                        <td colspan="3">
                                            <select id="ingress_routing_plan" style="width: 200px" name="rate_display_as">
                                                <option value="0" <?php echo $common->set_get_select('rate_display_as', 0); ?>><?php __('Average')?></option>
                                                <option value="1" <?php echo $common->set_get_select('rate_display_as', 1); ?>><?php __('Actual')?></option>
                                            </select>
                                        </td>

                                        <!--
                                        <td rowspan="3" class="align_right padding-r10" rowspan="2"><?php __('Show Fields')?>: </td>
                                        <td rowspan="3">

                                            <?php
                                        echo $form->select('Cdr.field', $select_fields, $select_show_fields, array('id' => 'query-fields', 'style' => 'width:150px; height: 150px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                                        ?>
                                        </td>
-->

                                        <td rowspan="3">
                                            <input style="margin-left: 20px" type="submit" value="Query" id="formquery"
                                                   class="btn btn-primary margin-bottom10">
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </fieldset>
            </form>

        </div>
    </div>
</div>

<style>
    .item_total td{
        background-color: #fff !important;
        font-weight: bold !important;

    }
    .item_total td a{
        font-weight: bold !important;
    }

    .icon-plus:before{
        cursor: pointer !important;
    }

    .item_self{
        display: none;
    }
    .item_self td{
        background-color: #fff !important;
    }
</style>
<script>

    function switch_group(){

        if(!$('#switch_group input').is(':checked'))
            $('#group_by_input').attr('value','carrier');
        else
            $('#group_by_input').attr('value','date');

//        console.log($('#switch_group input').is(':checked'));
    }

    $(function(){

        $(".ColVis_collection").find('button').live('click',function(){
//            var collection_div = $(".ColVis_collection").find('.ColVis_radio');
            var is_checked = $(this).find(":checkbox").is(":checked");
            var index = $(this).index();
            $("#query-fields").find('option').eq(index).attr('selected',is_checked)
//            console.log('index is :' +index + '; checked is : '+b);

        });

        switch_group();
        $('#switch_group').change(function(){
            switch_group();
            $('#search_form').submit();
        });

        $('.switch_a').click(function(){
            var $this = $(this);
            var item = '.item_' + $this.data('value');
//           console.log(item);
            $(item).slideToggle('600');
//            $.sleep(400);
            $this.find('i').toggleClass('icon-minus');
        });

        $('.count_a').click(function(){
            var item = '.switch_' + $(this).data('value');
            $(item).click();
        });

        $.last_running_function = function(){
            var selected_arr = <?php echo json_encode($select_show_fields);?>;
            selected_arr = eval(selected_arr);
            $('.ColVis_radio').find('input[checked="checked"]').parents('button').click();
            var button = $('.ColVis_collection button');

//        var head = button.find('.ColVis_title');
            $.each(selected_arr,function(i,v){
//            $("span :contains("+v+")").parents('button').click();
                button.eq(v).click();

                if($('.dynamicTable').parent().hasClass('overflow_x')){

                    var pwidth = $('.dynamicTable').parent().width();
                    var twidth = $('.dynamicTable').width();
                    if((twidth - pwidth) > 10){
                        $('.dynamicTable').width(pwidth);
                    }
                }
//                console.log(v);
            });
        }

    });
</script>

