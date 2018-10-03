<?php $action_type = empty($_GET['action_type']) ? '2' : $_GET['action_type']; ?>

<?php
$action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
$w = isset($action['writable']) ? $action['writable'] : '';
?>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('View Invoice Email') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('View Invoice Email')?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <?php //*********************  条件********************************?>
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
            if (empty($mydata)) {
                ?>
                <div class="msg center"><h3><?php  echo __('no_data_found') ?>.</h3></div>
            <?php } else {
                ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('send_time', __('Send Time', true)); ?></th>
                            <th><?php echo $appCommon->show_order('invoice_no', __('Invoice_Num', true)); ?></th>
                            <th><?php echo $appCommon->show_order('mail_sub', __('Mail Subject', true)); ?></th>
                            <th><?php echo $appCommon->show_order('mail_content', __('Mail Content', true)); ?></th>
                            <th><?php echo $appCommon->show_order('pdf_file', __('Pdf File', true)); ?></th>
                            <th><?php echo $appCommon->show_order('send_address', __('Send_Address', true)); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="producttab">
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => '<font style="color:#FF6D06;">Complete</font>', 3 => '<font style="color:#FF0000;">Refused</font>');
                            ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['send_time'] ?></td>
                                <td><?php echo $mydata[$i][0]['invoice_no'] ?></td>
                                <td ><?php echo $mydata[$i][0]['mail_sub'] ?></td>
                                <td ><a href="javascript:void(0);" title="<?php echo $mydata[$i][0]['mail_content']; ?>"><?php echo substr($mydata[$i][0]['mail_content'], 0, 25) ?></a></td>
                                <td ><?php echo $mydata[$i][0]['pdf_file'] ?></td>
                                <td><?php echo $mydata[$i][0]['send_address'] ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>
        </div>
        <div id="pop-div" class="pop-div" style="display:none;">
            <div class="pop-thead">
                <span></span>

                <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
            </div>
            <form id="myform" method="post">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
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
                        <td class="label label4"><?php echo __('Invoice Date', true); ?>(show):</td>
                        <td class="value value4">
                            <input type="text" id="invoice_time" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="invoice_time" realvalue="">
                        </td>

                    </tr>
                </table>

                <fieldset style="margin-top: 15px;">

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
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
                                                                if (!empty($_POST)) {
                                                                    if (isset($_POST['smartPeriod'])) {
                                                                        $s = $_POST['smartPeriod'];
                                                                    } else {
                                                                        $s = 'curDay';
                                                                    }
                                                                } else {

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