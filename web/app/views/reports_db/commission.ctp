<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/commission">
        <?php __('Agent') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/commission">
        <?php echo __('Commission Report') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Commission Report') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <h1 style="font-size:14px;" class="pull-left"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1>
            <div class="pull-right" id="switch_group">
                <button class="btn btn-primary" style="background-color: #ccc;border-color: #ccc;color: #333" data-value="hourly"><?php __('Group By Hourly')?></button>
                <button class="btn btn-primary" style="background-color: #ccc;border-color: #ccc;color: #333" data-value="daily"><?php __('Group By Daily')?></button>
                <button class="btn btn-primary" style="background-color: #ccc;border-color: #ccc;color: #333" data-value="weekly"><?php __('Group By Weekly')?></button>
                <button class="btn btn-primary" style="background-color: #ccc;border-color: #ccc;color: #333" data-value="monthly"><?php __('Group By Monthly')?></button>
            </div>
            <div class="clearfix"></div>
            <?php
            $data = $p->getDataArray();
            if (empty($data)): ?>
                <div class="msg center"><h2><?php __('no_data_found') ?></h2></div>
            <?php else: ?>
                <div >
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                        <thead>
                        <tr>
                            <!--group by 的字段-->
                            <th><?php __('Date'); ?></th>
                            <!--th><?php __('Agent'); ?></th-->
                            <!--th><?php __('Carrier'); ?></th-->
                            <!--//group by 的字段-->

                            <th><?php __('Call Attempt')?></th>
                            <th><?php __('Non Zero')?></th>
                            <th><?php __('Minutes')?></th>
                            <th><?php __('Cost')?></th>

                            <!--th><?php __('ASR')?></th-->
                            <!--th><?php __('ACD')?></th-->

                            <th><?php __('Margin')?></th>
                            <th><?php __('Commission')?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- 显示数据                       -->

                        <?php
                        $i = -1;
                        foreach($data as $date => $agents):
                            $i++;
                            $j = - 1;
                            foreach($agents as $k => $agent):
                                $j++;
                            ?>
                            <?php foreach($agent as $k2 => $item2):?>
                            <tr class="<?php echo $k2 == 0 ? 'item_total' : 'item_self item_' . $i . '_' . $j;?>">
                                <!--group by 的字段-->
                                <td <?php echo $k2 == 0 ? '' : 'class="center"';?>>
                                    <?php
                                   /* echo $k2 == 0 ?
                                        '<a href="javascript:void(0)" class="switch_a switch_'.$i.'_'.$j.'" data-value="'.$i.'_'.$j.'"><i class="icon-plus" style="margin-right: 20px"></i></a> ' . (empty($date) ? '--' : $date)
                                        :
                                        '<i class="icon-arrow-right"></i>';*/
                                        echo (empty($date) ? '--' : $date);
                                    ?>
                                </td>
                                <!--td <?php echo $k2 == 0 ? '' : 'class="center"';?>><?php echo $k2 == 0 ? $item2['agent'] : '<i class="icon-arrow-right"></i>' ?></td-->
                                <!--td><?php echo $k2 == 0 ? '<a href="javascript:void(0)" class="count_a" data-value="'.$i.'_'.$j.'" title="counts">' . (count($agent) - 1) . '</a>' : (empty($item2['carrier']) ? '--' : $item2['carrier']); ?></td-->
                                <!--//group by 的字段-->


                                <td><?php echo number_format($item2['total_calls'], 0); ?></td>
                                <td><?php echo number_format($item2['not_zero_calls'], 0); ?></td>
                                <td><?php echo number_format($item2['minutes'], 2); ?></td>
                                <td><?php echo number_format($item2['cost'], 2); ?></td>
                                <!--td><?php echo number_format($item2['asr'], 2); ?>%</td-->
                                <!--td><?php echo number_format($item2['acd'], 2); ?>%</td-->
                                <td><?php echo number_format($item2['margin'], 2); ?></td>
                                <td><?php echo number_format($item2['commission'], 2); ?></td>
                            </tr>
                        <?php endforeach;?>
                            <!--                        </span>-->

                        <?php
                                endforeach;
                            endforeach;
                        ?>
                        <!-- //显示数据                       -->
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <form id="search_form"
                  method="get" >
                <input type="hidden" id="group_by_input" name="group_by" value="date"/>
                <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
                    <input type="hidden" name="group_by" id="group_by" value="<?php echo isset($_GET['group_by']) ? $_GET['group_by'] : 'daily';?>"/>
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
                                        <td style=""><input type="text" id="query-start_date-wDt"
                                                                       class="in-text input  wdate"
                                                                       onchange="setPeriod('custom')"
                                                                       readonly="readonly"
                                                                       onkeydown="setPeriod('custom')" value=""
                                                                       name="start_date"
                                                                       style="width: 110px;"/><input style="display:none;" type="text" id="query-start_time-wDt"
                                                                                                    onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                                                                    readonly="readonly" style="width: 110px;margin-left: 10px" value="00:00:01"
                                                                                                    name="start_time" class="input in-text">
                                        </td>
                                        <td style="width:12px;"> — </td>
                                        <td style=""><input type="text" id="query-stop_date-wDt"
                                                                       class="in-text input  wdate"
                                                                       onchange="setPeriod('custom')"
                                                                       readonly="readonly"
                                                                       onkeydown="setPeriod('custom')" value=""
                                                                       name="stop_date"
                                                                       style="width: 110px;"/><input style="display:none;" type="text" id="query-stop_time-wDt"
                                                                                                    onchange="setPeriod('custom')" readonly="readonly"
                                                                                                    onkeydown="setPeriod('custom')" style="width: 110px;margin-left: 10px"
                                                                                                    value="23:59:59" name="stop_time" class="input in-text">
                                        </td>
                                        <td>in</td>
                                        <td><select id="query-tz"
                                                               style="width: 120px;" name="query[tz]" class="input in-select">
                                                <option value="-1200">GMT -12:00</option>
                                                <option value="-1100">GMT -11:00</option>
                                                <option value="-1000">GMT -10:00</option>
                                                <option value="-0900">GMT -09:00</option>
                                                <option value="-0800">GMT -08:00</option>
                                                <option value="-0700">GMT -07:00</option>
                                                <option value="-0600">GMT -06:00</option>
                                                <option value="-0500">GMT -05:00</option>
                                                <option value="-0400">GMT -04:00</option>
                                                <option value="-0300">GMT -03:00</option>
                                                <option value="-0200">GMT -02:00</option>
                                                <option value="-0100">GMT -01:00</option>
                                                <option value="+0000">GMT +00:00</option>
                                                <option value="+0100">GMT +01:00</option>
                                                <option value="+0200">GMT +02:00</option>
                                                <option value="+0300">GMT +03:00</option>
                                                <option value="+0330">GMT +03:30</option>
                                                <option value="+0400">GMT +04:00</option>
                                                <option value="+0500">GMT +05:00</option>
                                                <option value="+0600">GMT +06:00</option>
                                                <option value="+0700">GMT +07:00</option>
                                                <option value="+0800">GMT +08:00</option>
                                                <option value="+0900">GMT +09:00</option>
                                                <option value="+1000">GMT +10:00</option>
                                                <option value="+1100">GMT +11:00</option>
                                                <option value="+1200">GMT +12:00</option>
                                            </select></td>






                                        <td>
                                            <input style="margin-left: 20px" type="submit" value="Query" id="formquery"
                                                   class="btn btn-primary margin-bottom10">
                                        </td>

                                    </tr>
                                    <tr>
                                        <!--td class="align_right padding-r10"><?php __('Carrier')?></td>
                                        <td colspan="4">
                                            <?php
                                            echo $form->select('carrier', $carrieres,$select_carrier, array('id' => 'carrier', 'name' => 'carrier', 'class' => 'multiselect validate[required]', 'type' => 'select', 'multiple' => true), false);
                                            ?>
                                        </td-->
                                        <td class="align_right padding-r10"><?php __('Agent')?>:</td>
                                        <td colspan="4">
                                            <?php
                                            echo $form->select('s_agent', $s_agents,$select_agent, array('id' => 's_agent', 'name' => 's_agent', 'class' => 'validate[required]', 'type' => 'select'), false);
                                            ?>
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



    $(function(){
        var group_by = '<?php echo isset($_GET['group_by']) ? $_GET['group_by'] : 'daily';?>';
        $('[data-value="'+group_by+'"]').attr('style','');
        $('#carrier').multiSelect({
            selectableHeader: "<div class='custom-header'>Optional Selection</div>",
            selectionHeader: "<div class='custom-header'>Selected Selection</div>"
        });

        $('#switch_group button').click(function(){
	    $('#switch_group button').attr('style','background-color: #ccc;border-color: #ccc;color: #333');
            var val = $(this).data('value');
	    $('[data-value="' + val + '"]').attr('style','');
            $('#group_by').val(val);	
            $('#search_form').submit();
        });

        $('.switch_a').click(function(){
            var $this = $(this);
            var item = '.item_' + $this.data('value');
//           console.log(item);
            $(item).slideToggle('600');
            $.sleep(400);
            $this.find('i').toggleClass('icon-minus');
        });

        $('.count_a').click(function(){
            var item = '.switch_' + $(this).data('value');
            $(item).click();
        });



    });
</script>


