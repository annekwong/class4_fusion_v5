
<tr class="period-block">
    <!--
    <td colspan="8" style="width:auto;"><table class="in-date" <?php if(!isset($report_name)): ?>style="width: 98%" <?php endif; ?>>
    -->
            <td colspan="8" style="width:auto;"><table class="in-date">
            <tbody>
                <tr>
                    <td class="align_right padding-r10"><?php __('time') ?> </td>
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
                            'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod', 'style' => 'width:90px;', 'div' => false, 'type' => 'select', 'selected' => $s));
                        ?>
                    </td>
                    <td style="width:200px"><input type="text" id="query-start_date-wDt"
                                                   class="in-text input  wdate" onchange="setPeriod('custom')"
                                                   readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                   name="start_date" style="width: 80px;" >&nbsp;<input type="text" id="query-start_time-wDt"
                                                   onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                    <?php if (isset($newReport)) echo "onfocus=\"WdatePicker({dateFmt:'HH:00:00'})\""; ?>
                                                   readonly="readonly" style="width: 80px;" value="00:00:01"
                                                   name="start_time" class="input in-text wdate"></td>
                    <td style="width:auto;">&mdash;</td>
                    <td style="width:200px"><input type="text" id="query-stop_date-wDt"
                                                   class="in-text input  wdate" onchange="setPeriod('custom')"
                                                   readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                   name="stop_date" style="width: 80px;">&nbsp;<input type="text" id="query-stop_time-wDt"
                                                   onchange="setPeriod('custom')" readonly="readonly"
                                                    <?php if (isset($newReport)) echo "onfocus=\"WdatePicker({dateFmt:'HH:00:00'})\""; ?>
                                                   onkeydown="setPeriod('custom')" style="width: 80px;"
                                                   value="23:59:59" name="stop_time" class="input in-text wdate"></td><td>in</td><td><select id="query-tz"
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
                    <?php
                    if ($group_time && 'usagedetails' != $this->params['controller'] && 'usagedetails_db' != $this->params['controller'] &&  'premature_abandon' != $this->params['action']){
                        ?>
                        <td><?php
                            $r = array('' => __('alltime', true), 'YYYY-MM-DD  HH24:00:00' => __('byhours', true), 'YYYY-MM-DD' => __('byday', true), 'YYYY-MM' => __('bymonth', true), 'YYYY' => __('byyear', true));
                            if (!empty($_GET))
                            {
                                if (isset($_GET['group_by_date']))
                                {
                                    $s = $_GET['group_by_date'];
                                }
                                else
                                {
                                    $s = '';
                                }
                            }
                            else
                            {
                                $s = '';
                            }
                            echo $form->input('group_by_date', array('options' => $r, 'label' => false, 'id' => 'query-group_by_date', 'style' => 'width: 120px;', 'name' => 'group_by_date',
                                'div' => false, 'type' => 'select', 'selected' => $s));
                            ?></td>
                        <?php } ?>
                    <td>
<?php echo $gettype ?>  

                        <input type="submit" value="<?php echo __('query', true); ?>" id="formquery"  	class="btn btn-primary margin-bottom10">
                        <?php
                        if (isset($report_name))
                        {
//                            echo $this->element('scheduled_report/index', array('report_name', $report_name));
                        }
                        ?>
                    </td>
                    
                    <?php
                        if('premature_abandon' == $this->params['action']){
                            echo "<td><div style='width:120px;' ></div></td><td><div style='width:120px;' ></div></td>";
                        }
                    ?>
                </tr>
            </tbody>
        </table></td>
</tr>
