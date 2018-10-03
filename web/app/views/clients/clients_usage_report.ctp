<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Reports') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Usage Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Usage Report') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="widget-body">

            <div id="container">
                <?php if (!empty($data)): ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Date') ?></th>
                                <th><?php __('Minutes') ?></th>
                                <th><?php __('Number Calls') ?></th>
                                <th><?php __('Cost') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item[0]['report_time'] ?></td>
                                    <td><?php echo number_format($item[0]['bill_time'] / 60, 2); ?></td>
                                    <td><?php echo number_format($item[0]['success_calls']); ?></td>
                                    <td><?php echo number_format($item[0]['call_cost'] + $item[0]['lnp_cost'], 5); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div>
                <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/clients/clients_usage_report/", 'onsubmit' => "loading();")); ?>
                <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                    <div class="search_title">
                        <img src="<?php echo $this->webroot ?>images/search_title_icon.png">
                        <?php __('Search')?>
                    </div>
                    <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
                    <table class="form" style="width:100%">

                        <tr class="period-block">
                            <td style="width:80px; text-align:right;"><?php __('time') ?>
                                :</td>
                            <td colspan="8" style="width:auto;"><table class="in-date" style="width: 98%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:100px; text-align: left;">
                                                <?php
                                                $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true), 'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                                if (!empty($_POST))
                                                {
                                                    if (isset($_POST['smartPeriod']))
                                                    {
                                                        $s = $_POST['smartPeriod'];
                                                    }
                                                    else
                                                    {
                                                        $s = 'curDay';
                                                    }
                                                }
                                                else
                                                {

                                                    $s = 'curDay';
                                                }
                                                echo $form->input('smartPeriod', array('options' => $r, 'label' => false,
                                                    'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod', 'style' => 'width:80px;', 'div' => false, 'type' => 'select', 'selected' => $s));
                                                ?>
                                            </td>
                                            <td><input type="text" id="query-start_date-wDt"
                                                       class="in-text input" onchange="setPeriod('custom')"
                                                       readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                       name="start_date" style="width: 80px;" >&nbsp;<input type="text" id="query-start_time-wDt"
                                                       onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                       readonly="readonly" style="width: 60px;" value="00:00:01"
                                                       name="start_time" class="input in-text"></td><td style="width:auto;">&mdash;</td>
                                                       <td><input type="text" id="query-stop_date-wDt"
                                                                                                                                        class="in-text input" onchange="setPeriod('custom')"
                                                                                                                                        readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                                                                                                        name="stop_date" style="width: 80px;">&nbsp;<input type="text" id="query-stop_time-wDt"
                                                                                                                                        onchange="setPeriod('custom')" readonly="readonly"
                                                                                                                                        onkeydown="setPeriod('custom')" style="width: 60px;"
                                                                                                                                        value="23:59:59" name="stop_time" class="input in-text"></td><td><?php __('in')?></td>
                                                                                                                                        <td><select id="query-tz"
                                                                                                                                style="width: 100px;" name="timezone" class="input in-select">
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
                                                <?php __('Prefix')?>
                                                <select name="prefix">
                                                    <option value="all" selected><?php __('All')?></option>
                                                    <?php
                                                    foreach ($prefixs as $prefix):
                                                        ?>
                                                        <option <?php if (isset($_GET['prefix']) && $_GET['prefix'] == $prefix[0]['tech_prefix']) echo 'selected' ?>><?php echo $prefix[0]['tech_prefix']; ?></option>
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input type="submit" value="<?php echo __('query', true); ?>" id="formquery"  	class="btn btn-primary"></td>
                                        </tr>
                                    </tbody>
                                </table></td>
                        </tr>

                    </table>

                </fieldset>
                <?php echo $form->end(); ?>
            </div>
        </div>
    </div>
</div>



<script>
    $(function() {
        $('#query-start_date-wDt').val('<?php echo $start_date; ?>');
        $('#query-tz').val('<?php echo  isset($_GET['timezone']) ? $_GET['timezone'] : '+0000'; ?>');
    });
</script>