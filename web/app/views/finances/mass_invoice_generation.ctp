<?php $action_type = empty($_GET['action_type']) ? '2' : $_GET['action_type']; ?>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Mass Invoice Generation', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Mass Invoice Generation', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="###">
                            <i class="icon-long-arrow-down"></i> 
                        </a>
                    </div>
                </form>
            </div>   
            <div class="clearfix"></div>
            <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance')?></h3></div>
                <div class="widget-body" style="height:70px;">
                    <form action="" method="get" id="search_panel"  >
                        <div class="filter-bar">
                            <input type="hidden" name="advsearch" class="input in-hidden">
                            <input type="hidden" id="is_export" name="is_export" value="0">


                            <!-- start 批量生成invoice -->

                            <table>
                                <tr>
                                    <td ><?php __('Select Carriers')?>:</td>
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
                                    <td class="label4"><?php echo __('Invoice Date(show)', true); ?>:</td>
                                    <td class="value value4">
                                        <input type="text" id="invoice_time" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="invoice_time" realvalue="">
                                    </td>

                                </tr>
                            </table>



                            <table>
                                <tr class="period-block">
                                    <td class="label4"><?php __('time') ?>:</td>
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
                                                               onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">

                                                    </td>

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
                                    <td>
                                        <input type="submit" id="reset_balance_btn" class="input btn-primary btn" value="<?php __('submit')?>" style="color:#fff;padding:4px 12px;margin-left: 80px;"/>
                                    </td>
                                </tr>
                            </table>
                    </form>
                </div>
            </div>
        </div>
        <!--  end 批量生成invoice -->
        <?php
        $mydata = $p->getDataArray();
        $loop = count($mydata);
        if (empty($mydata))
        {
            ?>
            <div class="msg center">
                <h2><?php  echo __('no_data_found') ?>.</h2>
            </div>
<?php }
else
{
    ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="checkAllOrNot(this, 'producttab');" value=""/></th>
                        <th><?php echo $appCommon->show_order('name', __('Carrier', true)); ?></th>
                        <th><?php echo $appCommon->show_order('allowed_credit', __('Credit', true)); ?></th>
                        <th><?php echo $appCommon->show_order('balance', __('Balance', true)); ?></th>
                        <th><?php __('Past  Due  Amount')?></th>
                        <th><?php echo $appCommon->show_order('invoice_end', __('Invoiced  Till', true)); ?></th>
                        <th>Un- invoiced  Amount</th>
                        <th><?php echo $appCommon->show_order('payment_term', __('Payment Term', true)); ?></th>
                        <th><?php echo $appCommon->show_order('next_invoiced', __('Next Invoiced Date', true)); ?></th>

                    </tr>
                </thead>
                <tbody id="producttab">
    <?php
    for ($i = 0; $i < $loop; $i++)
    {
        $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => '<font style="color:#FF6D06;">Complete</font>', 3 => '<font style="color:#FF0000;">Refused</font>');
        ?>
                        <tr>
                            <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['client_id'] ?>"/></td>
                            <td>
        <?php echo $mydata[$i][0]['name'] ?>
                            </td>
                            <td><?php echo $mydata[$i][0]['allowed_credit'] ?></td>
                            <td><?php echo $mydata[$i][0]['balance'] ?></td>
                            <td>
        <?php
        echo round($mydata[$i][0]['total_amount'] - $mydata[$i][0]['credit_note'] + $mydata[$i][0]['debit_note'] - $mydata[$i][0]['pay_amount'], 2);
        ?>
                            </td>
                            <td><?php echo $mydata[$i][0]['invoice_end'] ?></td>
                            <td></td>
                            <td><?php echo $mydata[$i][0]['payment_term'] ?></td>
                            <td><?php echo $mydata[$i][0]['next_invoiced'] ?></td>
                        </tr>
    <?php } ?>
                </tbody>
            </table>
            <div class="bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                </div> 
            </div>
                        <?php } ?>
        <!--start 查询-->
        <div style="clear:both;height:20px;"></div>
        <form method="get" id="myform3">
            <table>
                <col width="13%">
                <col width="23%">
                <col width="15%">
                <col width="18%">
                <col width="10%">
                <col width="20%">
                <tr>
                    <td class="align_right"><?php __('Carrier Name')?></td>
                    <td><input type="text" name="carrier_name" style="width:220px;"  value="<?php
                            if (!empty($_REQUEST['carrier_name']))
                            {
                                echo $_REQUEST['carrier_name'];
                            }
                            ?>"/></td>

                    <td class="align_right"><?php __('Payment term')?> </td>
                    <td>
                        <select name="payment_term" id="payment_term">
<?php
foreach ($payment_terms as $payment_term)
{
    ?>
                                <option value="<?php echo $payment_term[0]['payment_term_id'] ?>"><?php echo $payment_term[0]['name'] ?></option>
                                   <?php
                               }
                               ?>
                        </select>
                    </td>

                    <td class="align_right"><?php __('Uninvoiced Amount')?> >= </td>
                    <td>
                        <input type="text" id="unin_amount" name ="unin_amount" value="<?php
                               if (!empty($_REQUEST['unin_amount']))
                               {
                                   echo $_REQUEST['unin_amount'];
                               }
                               ?>" />
                    </td>
                </tr>

                <tr>
                    
                    <td class="align_right"><?php __('Next Invoiced Date')?></td>
                    <td>
                        <select name="next_invoice_date" id="next_invoice_date"> 
                            <option value="<"><?php __('before today')?></option>
                            <option value="="><?php __('today')?></option>
                            <option value=">"><?php __('after today')?></option>
                        </select>
                    </td>

                    <td class="align_right">
                        <?php __('Invoiced Tille')?> 
                    </td>
                    <td>
                        <select name="invoice_compare" id="invoice_compare">
                            <option value="<"><?php __('before')?></option>
                            <option value=">"><?php __('after')?></option>
                        </select>
                    </td>
                    <td>
                        <input value="<?php
                        if (!empty($_REQUEST['invoice_till_date']))
                        {
                            echo $_REQUEST['invoice_till_date'];
                        }
                               ?>" name="invoice_till_date" type="text" value="" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  realvalue="">
                    </td>
                    <td><input value="<?php
                        if (!empty($_REQUEST['invoice_till_time']))
                        {
                            echo $_REQUEST['invoice_till_time'];
                        }
                               ?>" onfocus="WdatePicker({dateFmt: 'HH:mm:ss'});"  class="input in-text in-input" type="text" name="invoice_till_time" value="00:00:00" style="width: 60px;" readonly="readonly" >
                        &nbsp;&nbsp;
                    <select class="input in-select" name="query[tz]" style="width: 100px;" id="querytz">
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
                            <option value="+0000" selected="selected">GMT +00:00</option>
                            <option value="+0100">GMT +01:00</option>
                            <option value="+0200" >GMT +02:00</option>
                            <option value="+0300">GMT +03:00</option>
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
                    <td class="align_right"><?php __('Balance')?> </td>
                    <td>
                        <select style="width:50px;" name="bal" id="bal">
                            <option value=">">></option>
                            <option value="=">=</option>
                            <option value="<"><</option>
                        </select>
                        <input type="text" id="balance" name="balance" style="width:153px" value="<?php
                        if (!empty($_REQUEST['balance']))
                        {
                            echo $_REQUEST['balance'];
                        }
                               ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align:center;">
                        <input type="submit" id="reset_balance_btn" class="input in-submit in-button btn btn-primary" value="<?php __('submit')?>" />
                    </td>
                </tr>
            </table>

        </form>





        <!--end  查询 -->


    </div>
</div>
</div>



<script type="text/javascript">
    /* function GenerateInvoiceSelected() {
     showDiv('pop-div','800','200','');
     }
     */
    function checkDate(url) {

        var ids = '';
        var chx = document.getElementById('producttab').getElementsByTagName("input");
        var loop = chx.length;

        var res = true;

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
                //$("#selectCarrier").attr('value','all');
                res = false;
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
        return res;

    }

    $(function() {
        $("#myform").submit(function() {
            return checkDate('<?php echo $this->webroot ?>finances/generate_invoice');
        });

        var payment_term = "<?php
                        if (!empty($_REQUEST['payment_term']))
                        {
                            echo $_REQUEST['payment_term'];
                        }
                        else
                        {
                            echo '';
                        }
                               ?>";

        if (payment_term != '') {
            $('#payment_term').attr('value', payment_term);
        }


        var invoice_compare = "<?php
                        if (!empty($_REQUEST['invoice_compare']))
                        {
                            echo $_REQUEST['invoice_compare'];
                        }
                        else
                        {
                            echo '';
                        }
                               ?>";

        if (invoice_compare != '') {
            $('#invoice_compare').attr('value', invoice_compare);
        }

        var querytz = "<?php
                        if (!empty($_REQUEST['query']['tz']))
                        {
                            echo $_REQUEST['query']['tz'];
                        }
                        else
                        {
                            echo '';
                        }
                               ?>";

        if (querytz != '') {
            $('#querytz').attr('value', querytz);
        }


        var bal = "<?php
                        if (!empty($_REQUEST['bal']))
                        {
                            echo $_REQUEST['bal'];
                        }
                        else
                        {
                            echo '';
                        }
                               ?>";

        if (bal != '') {
            $('#bal').attr('value', bal);
        }

        var next_invoice_date = "<?php
                        if (!empty($_REQUEST['next_invoice_date']))
                        {
                            echo $_REQUEST['next_invoice_date'];
                        }
                        else
                        {
                            echo '';
                        }
                               ?>";

        if (next_invoice_date != '') {
            $('#next_invoice_date').attr('value', next_invoice_date);
        }


        $("#myform3").submit(function() {
            var unin_amount_val = $("#unin_amount").val();
            if (isNaN(unin_amount_val)) {
                jGrowl_to_notyfy("the Uninvoiced Amount must be number", {theme: 'jmsg-error'});
                return false;
            }
            var balance_val = $("#balance").val();
            if (isNaN(balance_val)) {
                jGrowl_to_notyfy("the Balance must be number", {theme: 'jmsg-error'});
                return false;
            }

        });

    });



</script>