<style type="text/css">

    input[type="text"] {
        width: 220px;
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
    <li><a href="<?php echo $this->webroot ?>homes/search_charts">
        <?php echo __('Charts') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Charts'); ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report') ?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>homes/search_charts" class="glyphicons charts">
                        <i></i><?php __('Charts') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report" class="glyphicons notes">
                        <i></i><?php __('Qos Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"
                       class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"
                       class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos') ?>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo $this->webroot ?><!--homes/alert" class="glyphicons alarm">-->
<!--                        <i></i>--><?php //__('Alert') ?>
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
        <div class="widget-body">
            <?php echo $this->element("homes/query_chart")?>
            <!--<form style="text-align:center;" action="<?php /*echo $this->webroot */?>homes/show_charts" method="post">
                <div id="report_box">
                    <table class="table dynamicTable tableTools table-bordered  table-striped table-white">
                        <col width="20%">
                        <col width="30%">
                        <col width="20%">
                        <col width="30%">
                        <tr>
                            <td class="right"><?php /*__('Statistical Information') */?></td>
                            <td>
                                <select name="type">
                                    <option value="0"><?php /*__('ASR') */?></option>
                                    <option value="1"><?php /*__('ACD') */?></option>
                                    <option value="2"><?php /*__('Total Calls') */?></option>
                                    <option value="3"><?php /*__('Total Billable Time') */?></option>
                                    <option value="4"><?php /*__('PDD') */?></option>
                                    <option value="5"><?php /*__('Total Cost') */?></option>
                                    <option value="6"><?php /*__('Margin') */?></option>
                                    <option value="7"><?php /*__('Call attempt') */?></option>
                                </select>
                            </td>
                            <td class="right"><?php /*__('Report Type') */?></td>
                            <td>
                                <select name="report_type">
                                    <option value="0"><?php /*__('Origination') */?></option>
                                    <option value="1"><?php /*__('Termination') */?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php /*__('Group Time') */?></td>
                            <td>
                                <select name="group_time">
                                    <option value="0"><?php /*__('Daily') */?></option>
                                    <option value="1"><?php /*__('Hourly') */?></option>
                                </select>
                            </td>
                            <td class="right"><?php /*__('Timezone') */?></td>
                            <td>
                                <select name="timezone">
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
                                    <option value="+0000" selected>GMT +00:00</option>
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
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php /*__('Start Date/Time') */?></td>
                            <td>
                                <input type="text" name="start_time"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})"
                                       value="<?php /*echo $date . ' ' . "00:00:00"; */?>"/>
                            </td>
                            <td class="right"><?php /*__('End Date/Time') */?></td>
                            <td>
                                <input type="text" name="end_time"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})"
                                       value="<?php /*echo $date . ' ' . "23:59:59"; */?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php /*__('Country') */?></td>
                            <td>
                                <input type="text" name="country" id="query-country"/>
                            </td>
                            <td class="right"><?php /*__('Destination') */?></td>
                            <td>
                                <input type="text" name="destination" id="query-code_name"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php /*__('Orig Filter') */?></td>
                            <td>
                                <select name="ingress_trunk">
                                    <option selected></option>
                                    <?php /*foreach ($ingress_trunks as $trunks): */?>
                                        <option
                                            value="<?php /*echo $trunks[0]['resource_id'] */?>"><?php /*echo $trunks[0]['alias'] */?></option>
                                    <?php /*endforeach; */?>
                                </select>
                            </td>
                            <td class="right"><?php /*__('Term Filter') */?></td>
                            <td>
                                <select name="egress_trunk">
                                    <option selected></option>
                                    <?php /*foreach ($egress_trunks as $trunks): */?>
                                        <option
                                            value="<?php /*echo $trunks[0]['resource_id'] */?>"><?php /*echo $trunks[0]['alias'] */?></option>
                                    <?php /*endforeach; */?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <br/>
                <input type="submit" value="<?php /*__('View') */?>" class="btn btn-primary"/>
            </form>-->

        </div>
    </div>
</div>