<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>lrnreports">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>lrnreports">
        <?php echo __('LRN Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<?php
$data = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo __('Date', true); ?></th>
                        <?php if ($isorder) : ?>
                            <th><?php echo __('ingress', true); ?></th>
                        <?php endif; ?>
                        <th><?php echo __('Client LRN', true); ?></th>
                        <th><?php echo __('Cache LNP Cnt', true); ?></th>
                        <th><?php echo __('Server LNP Cnt', true); ?></th>
                        <th><?php echo __('Cost/Hit', true); ?></th>
                        <th><?php echo __('LNP Charge', true); ?></th>
                        <th><?php echo __('Total Dip', true); ?></th>
                        <th><?php __('Succ. Dip') ?></th>
                        <th><?php __('LRN Dipping Failed'); ?></th>
                        <th><?php __('Dip with LRN'); ?></th>
                        <th><?php __('Dip w/o LRN'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?php echo $item[0]['time'] ?></td>
                            <?php if ($isorder) : ?>
                                <td>
                                    <?php if(isset($ingress_trunk[$item[0]['ingress_id']])): ?>
                                        <?php echo $ingress_trunk[$item[0]['ingress_id']]; ?>
                                    <?php else: ?>
                                        <?php echo $item[0]['ingress_id']; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <td><?php echo $item[0]['client_count'] ?></td>
                            <td><?php echo $item[0]['cache_count'] ?></td>
                            <td><?php echo $item[0]['lrn_server_count'] ?></td>
                            <td>0</td>
                            <td><?php echo $item[0]['lnp_charge'] ?></td>
                            <td>
                                <?php
                                echo $item[0]['total_count'];
                                ?>
                            </td>
                            <td>
                                <?php echo $item[0]['total_count'] - $item[0]['lrn_no_response'] ?>
                            </td>
                            <td><?php echo $item[0]['lrn_no_response'] ?></td>
                            <td><?php echo $item[0]['total_count'] - $item[0]['lrn_same'] ?></td>
                            <td><?php echo $item[0]['lrn_same'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="separator"></div>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>

            <?php endif; ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php echo __('search', true); ?></h4>
                <div title="Advance" class="pull-right">
                    <a href="###" class="btn" id="advance_btn">
                        <i class="icon-long-arrow-down"></i>
                    </a>
                </div>
                <div class="clearfix"></div>
                <form name="myform" id="myform" method="get">
                    <table class="form" width="100%">
                        <input type="hidden" id="isDown" name="isDown" value="FALSE" />
                        <tbody>
                        <tr class="period-block">

                            <td  colspan="5" style="width: 90%">
                                <table class="in-date">
                                    <tbody>
                                    <tr>
                                        <td style="width:88px; text-align:right;padding-right: 10px;"><?php echo __('Period', true); ?></td>
                                        <td><?php
                                            $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'curWeek' => __('currentweek', true), 'curMonth' => __('currentmonth', true));
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
                                                'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod', 'style' => 'width:100px;', 'div' => false, 'type' => 'select', 'selected' => $s));
                                            ?></td>
                                        <td><input type="text" id="query-start_date-wDt"
                                                   class="in-text input" onChange="setPeriod('custom')"
                                                   readonly="readonly" onKeyDown="setPeriod('custom')" value=""
                                                   name="start_date"  style="width:100px;" >
                                            &nbsp;
                                            <input type="text" id="query-start_time-wDt"
                                                   onchange="setPeriod('custom')" onKeyDown="setPeriod('custom')"
                                                   readonly="readonly" value="00:00:00"
                                                   name="start_time" class="input in-text" style="width:80px;"></td>
                                        <td>&mdash;</td>
                                        <td><input type="text" id="query-stop_date-wDt"
                                                   class="in-text input" onChange="setPeriod('custom')"
                                                   readonly="readonly" onKeyDown="setPeriod('custom')" value=""
                                                   name="stop_date" style="width:80px;">
                                            &nbsp;
                                            <input type="text" id="query-stop_time-wDt"
                                                   onchange="setPeriod('custom')" readonly="readonly"
                                                   onkeydown="setPeriod('custom')"
                                                   value="23:59:59" name="stop_time" class="input in-text" style="width:80px;"></td>
                                        <td>
                                            <select name="group_time" style="width: 120px;">
                                                <option value="0" <?php if (!isset($_GET['group_time']) || $_GET['group_time'] == 0) echo 'selected="selected"' ?>><?php __('By Day')?></option>
                                                <option value="1" <?php if (isset($_GET['group_time']) && $_GET['group_time'] == 1) echo 'selected="selected"' ?>><?php __('By Month')?></option>
                                            </select>&nbsp;
                                        </td>
                                        <td><select id="query-tz"
                                                    name="gmt" class="input in-select" style="width:120px;">
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
                                        <td class="align_right padding-r10" style="width:80px"><?php echo __('Output', true); ?></td>
                                        <td style="text-align:left;"><select name="show_type" style="width:160px;">
                                                <option value="0"><?php __('Web')?></option>
                                                <option value="1"><?php __('Excel CSV')?></option>
                                            </select></td>
                                        <td class="center"><input class="btn margin-bottom10 btn-primary" type="submit" value="<?php echo __('search', true); ?>" /></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <?php
                        /*
                          <td>Group By Ingress Trunk?</td>
                          <td>
                          <input type="checkbox" name="isorder" <?php if(isset($isorder)) echo 'checked'; ?> />
                          </td>
                         */
                        ?>
                        <tr id="advance_panel" style="display: none;">
                            <td  colspan="5" style="width: 90%">
                                <table>
                                    <col width="8%">
                                    <col width="7%">
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="70%">
                                    <tr>
                                        <td class="align_right padding-r10"><?php echo __('ingress', true); ?></td>
                                        <td style="text-align:left;"><select name="ingress_trunk" style="width:160px;">
                                                <option></option>
                                                <?php foreach ($ingress_trunk as $key => $trunk): ?>
                                                    <option <?php if (isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] == $key) echo 'selected'; ?> value="<?php echo $key; ?>"><?php echo $trunk; ?></option>
                                                <?php endforeach; ?>
                                            </select></td>
                                        <td class="align_right padding-r10"><?php echo __('Group By', true); ?></td>
                                        <td style=" text-align:left;"><select name="order_in" id="order_in" style="width:160px;">
                                                <option></option>
                                                <option value="1"><?php __('Ingress Trunk')?></option>
                                            </select></td>
                                        <td></td>
                                    </tr>
                                </table>
                        </tr>
                        <tr>

                        </tr>
                        </tbody>
                    </table>
                </form>
            </fieldset>
        </div>
    </div>
</div>
<script refer="refer" type="text/javascript">
    $(function() {
        $('#down').click(function() {
            $('#isDown').val('TRUE');
            $('#myform').submit();
        });

        $('#order_in').val('<?php echo isset($_GET['order_in']) ? $_GET['order_in'] : '' ?>');
    });
</script>
