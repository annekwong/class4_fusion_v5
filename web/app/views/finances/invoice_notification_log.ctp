<?php $action_type = empty($_GET['action_type']) ? '2' : $_GET['action_type']; ?>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/invoice_notification_log">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/invoice_notification_log">
        <?php echo __('Invoice Notification Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Invoice Notification Log') ?></h4>    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form   id="like_form"  action=""  method="get">
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search  input in-text " title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <input type="hidden" name="action_type" value="<?php echo $action_type; ?>" />

                    <div>
                        <label><?php __('Client') ?>:</label>
                        <select  name="client_name" class="in-select select" >
                            <option value=""></option>
                            <?php
                            foreach ($client as $key => $client_item)
                            {
                                ?>
                                <option value="<?php echo $client_item ?>" <?php
                                        if (isset($get_data['client_name']) && !strcmp($client_item, $get_data['client_name']))
                                        {
                                            ?>selected="selected"<?php } ?> ><?php echo $client_item ?></option>
<?php } ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Time') ?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                               if (isset($get_data['time_start']))
                               {
                                   echo $get_data['time_start'];
                               }
                               ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php
                        if (isset($get_data['time_end']))
                        {
                            echo $get_data['time_end'];
                        }
                               ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn search_submit"><?php __('Query') ?></button>
                    </div>
<?php
$action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
$w = isset($action['writable']) ? $action['writable'] : '';
?>
                </form>

            </div>
            
            <div class="clearfix"></div>
<?php //*********************  条件********************************  ?>
            <!--<fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
              <form action="" method="get">
                <input name="advsearch" type="hidden"/>
                <input name="action_type" type="hidden" value="<?php echo $action_type ?>"/>
                <table style="width:100%">
                  <tbody>
                    <tr>
                      
                      <td><label><?php echo __('Transaction Date', true); ?> :</label>
                        <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo!empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''; ?>">
                        --
                        <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo!empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''; ?>"></td>
                     
                        <td><label><?php echo __('status', true); ?>:</label>
                        <select id="tran_status" name="tran_status">
                          <option value=""><?php echo __('select') ?></option>
                          <option value="1" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 1) ? 'selected' : ''; ?>>Waiting</option>
                          <option value="2" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 2) ? 'selected' : ''; ?>>Completed</option>
<?php if ($action_type == 1): ?>
                                                              <option value="0" <?php echo (isset($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 0) ? 'selected' : ''; ?>>Confirmed</option>
                                                              <option value="3" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 3) ? 'selected' : ''; ?>>Refused</option>
<?php endif; ?>
                        </select></td>
                      <td><input type="submit" value="<?php echo __('submit') ?>" class="input in-submit"></td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </fieldset>-->

            <!-- <div id="toppage"></div>-->
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);

            if (empty($mydata))
            {
                ?>

                <div class="msg center">
                    <br/>
                    <h3><?php  echo __('no_data_found') ?>.
                    </h3>
                </div>
<?php
}
else
{
    ?>

                <!-- Table -->
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('invoice_time', __('Date Time', true)); ?></th>
                            <th><?php echo $appCommon->show_order('name', __('Carrier', true)); ?></th>
                            <th><?php echo $appCommon->show_order('total_amount', __('Amount', true)); ?></th>
                            <th><?php echo $appCommon->show_order('due_date', __('Due Date', true)); ?></th>
                            <th><?php __('Action')?></th>



                        </tr>
                    </thead>
                    <tbody id="producttab">
                                <?php
                                for ($i = 0; $i < $loop; $i++)
                                {
                                    $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => '<font style="color:#FF6D06;">Complete</font>', 3 => '<font style="color:#FF0000;">Refused</font>');
                                    ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['invoice_time'] ?></td>
                                <td>
        <?php echo $mydata[$i][0]['name'] ?>
                                </td>
                                <td><?php echo $mydata[$i][0]['total_amount'] ?></td>
                                <td><?php echo $mydata[$i][0]['due_date'] ?></td>

                                <td>
                                    <!--a href="<?php echo $this->webroot ?>finances/view_invoice_email/<?php echo $mydata[$i][0]['invoice_number']; ?>" title="<?php __('view email')?>">
                                        <i class="icon-envelope-alt"></i>
                                    </a-->
                                    <a href="<?php echo $this->webroot ?>finances/createpdf_invoice/<?php echo $mydata[$i][0]['invoice_number']; ?>/0" title="<?php __('download')?>">
                                        <i class="icon-file-text"></i>
                                    </a>
                                    <a href="<?php echo $this->webroot ?>finances/invoice_notification_log/<?php echo $mydata[$i][0]['invoice_id']; ?>" title="<?php __('send mail')?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                </td>
                            </tr>
    <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>            <?php } ?>
        </div>
        <div id="pop-div" class="pop-div" style="display:none;">
            <div class="pop-thead">
                <span></span>

                <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
            </div>
            <form id="myform" method="post">
                <table>
                    <tr>
                        <td ><?php __('select carriers')?>:</td>
                        <td >
                            <select id="selectCarrier">
                                <option value="all">
                                    <?php __('All Carriers')?>
                                </option>
                                <option value="select">
                                    <?php __('Selected Carriers')?>
                                </option>
                            </select> 
                        </td>


                        <td ><?php __('Due Date')?>:</td>
                        <td  >
                            <input type="text" id="due_date" value="" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="due_date" realvalue="">
                        </td>

                        <td style="text-align: left;"><?php __('Type')?>:</td>
                        <td  style="text-align: left;">
                            <select name="type" >
                                <option value="0"><?php __('received')?></option>
                                <option value="1"><?php __('sent')?></option>
                            </select>
                        </td>
                        <td class="label label4"><?php echo __('Invoice Date', true); ?>(<?php __('show')?>):</td>
                        <td class="value value4">
                            <input type="text" id="invoice_time" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="invoice_time" realvalue="">
                        </td>

                    </tr>
                </table>

                <fieldset style="margin-top: 15px;">

                    <table>
                        <tr class="period-block">
                            <td class="label"><?php __('time') ?>:</td>
                            <td colspan="5" class="value">
                                <table class="in-date"><tbody>
                                        <tr>
                                            <td>
                                                <table class="in-date">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-right: 15px;">

                                                                <?php
                                                                $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true),
                                                                    'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
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
                                                                    'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'style' => 'width: 130px;', 'name' => 'smartPeriod',
                                                                    'div' => false, 'type' => 'select', 'selected' => $s));
                                                                ?>

                                                            </td>
                                                            <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
                                                                       value="" name="start_date"  style="margin-left: 0px; width: 156px;"></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody></table>

                                            </td>
                                            <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                       readonly="readonly" 
                                                       style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
                                            <td>&mdash;</td>
                                            <td>
                                                <table class="in-date">
                                                    <tbody><tr>
                                                            <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
                                                                       readonly="readonly" 
                                                                       onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </td>
                                            <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
                                                       readonly="readonly" 
                                                       onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>


                                            <td style="padding: 0pt 10px;"><?php echo __('in', true); ?></td>
                                            <td><select class="input in-select" name="query[tz]" style="width: 100px;" id="query-tz">
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
                                                    <option value="+0000"   selected="selected">GMT +00:00</option>
                                                    <option value="+0100">GMT +01:00</option>
                                                    <option value="+0200" >GMT +02:00</option>
                                                    <option value="+0300">GMT +03:00</option>
                                                    <option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
                                        </tr></tbody></table>

                            </td>

                        </tr>
                    </table>

                </fieldset>
                <input type="submit" id="reset_balance_btn" class="input in-submit in-button" value="submit" onclick="checkDate('<?php echo $this->webroot ?>finances/generate_invoice');" />
            </form>
            <div class="pop-content" id="pop-content"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function GenerateInvoiceSelected() {
        showDiv('pop-div', '800', '200', '');
    }

    function checkDate(url) {

        var ids = '';
        var chx = document.getElementById('producttab').getElementsByTagName("input");
        var loop = chx.length;


        if ($("#selectCarrier").val() == "select") {

            for (var i = 0; i < loop; i++) {
                var c = chx[i];
                if (c.type == "checkbox") {
                    if (c.checked == true && c.value != '') {
                        ids += c.value + ",";
                    }
                }
            }
            if (ids == '' || ids.length < 1)
            {
                jGrowl_to_notyfy("Please select the carrier", {theme: 'jmsg-error'});
                $("#selectCarrier").attr('value', 'all');
                closeDiv('pop-div')
                return false;
            }
            else
            {

                ids = ids.substring(0, ids.length - 1);// 去掉最后逗号
                url = url + "/" + ids;
                $("#myform").attr('action', url);
            }
        } else {
            for (var i = 0; i < loop; i++) {
                var c = chx[i];
                ids += c.value + ",";
            }
            ids = ids.substring(0, ids.length - 1);
            url = url + "/" + ids;
            $("#myform").attr('action', url);

        }

    }

    $(function() {
        $("#myfor").submit(function() {
            checkDate('<?php echo $this->webroot ?>finances/generate_invoice');
        });
    });



</script>