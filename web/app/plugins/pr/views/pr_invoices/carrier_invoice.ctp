<?php
//获取当月天数
#  $numDay = date("t",mktime(0,0,0,date('m'),date('d'),date('Y')));
$numDay = 31;
$arrayDay = array();
for ($i = 1; $i <= $numDay; $i++)
{
    if ($i == 1)
    {
        $arrayDay[1] = '1 st';
    }
    if ($i == 2)
    {
        $arrayDay[2] = '2 nd';
    }
    else
    {
        $arrayDay[$i] = $i . ' th';
    }
}
$arrayWeekDay = Array(0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday');
$select_weekday = isset($_GET['search_week']) && ($_GET['search_week'] !== '') ? intval($_GET['search_week']) : -1;
?>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/carrier_invoice">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/carrier_invoice">
        <?php echo $this->pageTitle; ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="widget-body">
            <div class="filter-bar">

                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier Name')?>:</label>
                        <select name="client_id" id="client_id">
                            <option value="0"></option>
                            <?php
                            foreach ($clients as $item) {
                                ?>
                                <option value="<?php echo $item['0']['client_id']; ?>"> <?php echo $item['0']['name']; ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Payment Terms')?>:</label>

                        <select name="payment_term_id" id="payment_term">
                            <option value=""></option>
                            <?php
                            foreach ($payment_terms as $item) {
                                ?>
                                <option value="<?php echo $item['Paymentterm']['payment_term_id']; ?>" <?php if(isset($get_data['payment_term_id']) && $get_data['payment_term_id'] == $item['Paymentterm']['payment_term_id']) echo 'selected'; ?> ><?php echo $item['Paymentterm']['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>

<!--                        <select id="search_week" name="search_week">-->
<!--                            <option value=""></option>-->
<!--                            --><?php
//                            foreach ($arrayWeekDay as $key => $value)
//                            {
//                                ?>
<!--                                <option value="--><?php //echo $key; ?><!--" --><?php //echo ($select_weekday == $key) ? "selected='selected'" : ''?><!-- >--><?php //echo $value; ?><!--</option>-->
<!--                            --><?php //} ?>
<!--                        </select>-->
                        <!--                    <input type="text"  class="in-search default-value input in-text defaultText" title="--><?php //echo __('Day') ?><!--"  value="--><?php //if (!empty($payment_terms)) echo $payment_terms; ?><!--" name="payment_terms">-->
                        <script type="text/javascript">
//                            $(function() {
//                                var search_week = "<?php
//                        if (!empty($search_week))
//                        {
//                            echo $search_week;
//                        }
//                        else
//                        {
//                            echo "";
//                        }
//                        ?>//";
//                                $("#search_week").val(search_week);
//                            });
                        </script>
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (!$loop)
            {
                ?>
                <div class="center msg">
                    <br />
                    <h3><?php echo __('no_data_found') ?></h3>
                </div>
            <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('name', __('Carrier Name', true)) ?></th>
                        <th><?php __('Invoicing Cycle')?></th>
                        <th><?php __('Last Invoice Date')?></th>
                        <th><?php __('Next Invoice Date')?></th>
                        <th><?php __('Last Invoice Amount')?></th>
                        <th><?php __('Last Invoice Period')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tr>
                            <td><?php echo $mydata[$i][0]['name']; ?></td>
                            <td>
                                <?php
                                if ($mydata[$i][0]['type'] == 1)
                                {
                                    echo str_replace('X', $mydata[$i][0]['days'], __('Every', true));
                                    echo "&nbsp;&nbsp;&nbsp;";
                                    echo $mydata[$i][0]['days'];
                                    echo "&nbsp;&nbsp;&nbsp;";
                                    echo 'day(s)';
                                }
                                elseif ($mydata[$i][0]['type'] == 2)
                                {
                                    //echo str_replace('X',$arrayDay[$mydata[$i][0]['days']],__('onxdayofmonth',true));
                                    echo "Every";
                                    echo "&nbsp;&nbsp;&nbsp;";
                                    //echo $arrayDay[$mydata[$i][0]['days']];
                                    $prDate = explode(' ', $arrayDay[$mydata[$i][0]['days']]);
                                    echo $prDate[0] . "<sup>" . $prDate[1] . "</sup>" . "&nbsp;&nbsp;&nbsp;of&nbsp;&nbsp;&nbsp;the&nbsp;&nbsp;&nbsp;month";
                                }
                                elseif ($mydata[$i][0]['type'] == 3)
                                {
                                    //echo str_replace('X',$arrayWeekDay[$mydata[$i][0]['days']],__('onxdayofweek',true));
                                    echo "Every";
                                    echo "&nbsp;&nbsp;&nbsp;";
                                    echo $arrayWeekDay[$mydata[$i][0]['days']];
                                    echo "&nbsp;&nbsp;&nbsp;";
                                    echo "of" . "&nbsp;&nbsp;&nbsp;" . "the" . "&nbsp;&nbsp;&nbsp;" . "week";
                                }
                                else
                                {
                                    //echo str_replace('X',$mydata[$i][0]['more_days'],__('someonxdayofmonth',true));

                                    echo "Every";
                                    echo "&nbsp;&nbsp;&nbsp;";

                                    $new_date = array();
                                    $mydates_array = explode(',', $mydata[$i][0]['more_days']);
                                    foreach ($mydates_array as $key => $value)
                                    {
                                        $val_arr = explode(' ', $arrayDay[$value]);
                                        $new_date[$key] = $val_arr[0] . "<sup>" . $val_arr[1] . "</sup>";
                                    }

                                    echo implode(',', $new_date);
                                    echo "&nbsp;&nbsp;&nbsp;of&nbsp;&nbsp;&nbsp;the&nbsp;&nbsp;&nbsp;month";
                                }
                                ?>
                            </td>
                            <td><?php echo $mydata[$i][0]['last_invoice_time']; ?></td>
                            <td><?php echo $mydata[$i][0]['next_invoice_date']; ?></td>
                            <td><?php echo $mydata[$i][0]['last_invoice_amount']; ?></td>
                            <td align="center"><small> <?php echo $mydata[$i][0]['last_invoice_start'] ?> -
                                    <?php echo $mydata[$i][0]['last_invoice_end'] ?> </small></td>
                            <td>
                                <?php if ($mydata[$i][0]['auto_invoicing']): ?>
                                    <a class="stop_invoice" href="javascript:void(0)" data-hasqtip="193" data-id="<?php echo $mydata[$i][0]['client_id']; ?>" title="<?php __('Stop auto invoice')?>" title="" aria-describedby="qtip-193">
                                        <i class="icon-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a class="start_invoice" href="javascript:void(0)" data-hasqtip="193" data-id="<?php echo $mydata[$i][0]['client_id']; ?>" title="<?php __('Start auto invoice')?>" title="" aria-describedby="qtip-193">
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>
                                <a title="<?php __('View History')?>" href="<?php echo $this->webroot; ?>invoice_history/view/<?php echo $mydata[$i][0]['client_id']; ?>?is_carrier_invoice=1"><i class="icon-list-alt"></i></a>
                                <a title="<?php __('Edit')?>" href="<?php echo $this->webroot; ?>clients/edit_invoice/<?php echo $mydata[$i][0]['client_id']; ?>" ><i class="icon-edit"></i></a>
                                <?php
                                if (strcmp("--", $mydata[$i][0]['last_invoice_time']))
                                {
                                    ?>
                                    <!--                                        <a  href="javascript:void(0)" data-hasqtip="193" data-id="--><?php //echo $mydata[$i][0]['invoice_id']; ?><!--" title="--><?php //__('re-generate')?><!--" title="" aria-describedby="qtip-193">-->
                                    <!--                                            <i class="icon-play-circle"></i>-->
                                    <!--                                        </a>-->
                                <?php } ?>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php } ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>
</div>
<script type="text/javascript">

    $(function() {
        var client_id = '<?php echo isset($_GET['client_id']) ? $_GET['client_id'] : ""?>';
        $('#client_id').val(client_id);
        $("a[title=re-generate]").click(function() {
            var $this = $(this);
            bootbox.confirm('Are you sure to re-generate?', function(result) {
                if (result) {
                    regenerate($this.data('id'));
                }
            });
        });


        $(".stop_invoice").click(function() {
            var $this = $(this);
            bootbox.confirm('<?php __('sure to stop'); ?>', function(result) {
                if (result) {
                    stop_auto_invoice($this.data('id'));
                }
            });
        });


        $(".start_invoice").click(function() {
            var $this = $(this);
            bootbox.confirm('<?php __('sure to start'); ?>', function(result) {
                if (result) {
                    start_auto_invoice($this.data('id'));
                }
            });
        });

    });

    function stop_auto_invoice(client_id) {
        $.get("<?php echo $this->webroot ?>clients/auto_invoice/stop", {"client_id": client_id}, function(d) {
           jGrowl_to_notyfy(d, {theme: 'jmsg-success'});
            window.location.reload();
        });
    }

    function start_auto_invoice(client_id) {

        $.get("<?php echo $this->webroot ?>clients/auto_invoice/start", {"client_id": client_id}, function(d) {
           jGrowl_to_notyfy(d, {theme: 'jmsg-success'});
            window.location.reload();
        });
    }

    function regenerate(invoice_id)
    {
        $.get("<?php echo $this->webroot ?>pr/pr_invoices/regenerate", {"invoice_id": invoice_id}, function(d) {
           jGrowl_to_notyfy(d, {theme: 'jmsg-success'});
            window.location.reload();
        });

    }
</script>
