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
$arrayWeekDay = Array(0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wendsday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday');
?>

<ul class="breadcrumb">
    <li><?php __('Switch') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Payment Term') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
    {
        ?>
        <?php echo $this->element("createnew", Array('url' => 'paymentterms/add_payment_term/1')) ?>
    <?php } ?>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '6')); ?>
        <div class="widget-body">
            <?php
            if (count($mydata) == 0)
            {
                ?>
                <div class="msg center"  id="msg_div" >
                    <br /><h2><?php echo __('no_data_found') ?></h2>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="msg center"  id="msg_div"  style="display: none;">
                    <br /><h2><?php echo __('no_data_found') ?></h2>
                </div>
            <?php } ?>
            <?php
            if (count($mydata) == 0)
            {
                ?>
                <div  id="list_div"  style="display: none;">
                    <?php
                }
                else
                {
                    ?>
                    <div id="list_div">
                    <?php } ?>
                    <div class="clearfix"></div>
                    <table class="list  footable table table-striped tableTools table-bordered  table-white table-primary">

                        <thead>
                            <tr>
            <!--		    <th><?php echo $appCommon->show_order('payment_term_id', __('ID', true)) ?></th>-->
                                <th><?php echo $appCommon->show_order('name', __('Payment Term', true)) ?></th>
                                <th><?php __('Invoicing Cycle') ?></th>
                                <th><?php echo $appCommon->show_order('grace_days', __('Grace Period(Days)', true)) ?></th>
                                <th><?php echo $appCommon->show_order('notify_days', __('Notify(Days)', true)) ?></th>
                                <th><?php echo $appCommon->show_order('clients', __('Usage Count', true)) ?></th>
                        <!--<th><?php echo $appCommon->show_order('finance_rate', __('Finance Rate', true)) ?></th>-->
                                <?php
                                if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
                                {
                                    ?> <th class="last"><?php echo __('Action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="producttab">
                            <?php
                            $loop = count($mydata);
                            for ($i = 0; $i < $loop; $i++)
                            {
                                ?>
                                <tr class="row-1" style="text-align: center;">
    <!--		    <td  style="text-align:center;"><?php echo $mydata[$i][0]['payment_term_id'] ?></td>-->
                                    <td style="font-weight: bold;"> 
                                        <?php echo $mydata[$i][0]['name'] ?>
                                    </td>
                                    <td><?php
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
                                    <td><?php echo $mydata[$i][0]['grace_days'] ?></td>
                                    <td><?php echo $mydata[$i][0]['notify_days'] ?></td>
                                    <td><a href="<?php echo $this->webroot ?>clients/index?filter_payment_term_id=<?php echo $mydata[$i][0]['payment_term_id']; ?>"><?php echo $mydata[$i][0]['clients'] ?></a></td>
                            <!--<td><?php
                                    printf("%0.3f%%", $mydata[$i][0]['finance_rate']);
                                    ?></td>-->
                                    <td class="last">
    <!--                                            <a class="edit" title="<?php echo __('edit') ?>"  href="<?php echo $this->webroot ?>paymentterms/edit_payment_term/<?php echo $mydata[$i][0]['payment_term_id'] ?>" payment_term_id="<?php echo $mydata[$i][0]['payment_term_id'] ?>">
                                                    <i class="icon-edit"></i>
                                                </a>-->
                                        <a title="<?php echo __('del') ?>"  href="#" onclick="delConfirm_ex(this, '<?php echo $this->webroot ?>paymentterms/del_term/<?php echo $mydata[$i][0]['payment_term_id'] ?>', '<?php echo $mydata[$i][0]['name'] ?>');">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <!--<div class="center widget-body <?php /*if (!count($mydata))
                            {
                                echo "hide";
                            }; */?>" id="next_step">
                    <a href="<?php /*echo $this->webroot */?>homes/init_login_url/<?php /*echo $user_id; */?>/1"  class=" btn primary next"><?php /*__('Next') */?></a>
                </div>-->
            </div>
        </div>
    </div>
    <script type="text/javascript">
        /*
         * 删除一行
         */
        function delConfirm_ex(obj, url, name) {
            bootbox.confirm("Are you sure to delete  payment term " + name + "?", function (result) {
                if (result) {
                    window.location.href = url;
                }
            });
        }

        function  validation_data() {
            var ret = true;
            //var grace_days = jQuery('#PaymenttermGraceDays').val();				
            if (jQuery('#PaymenttermGraceDays').val() != '' && jQuery('#PaymenttermGraceDays') != null) {
                if (/\D/.test(jQuery('#PaymenttermGraceDays').val())) {
                    jQuery('#PaymenttermGraceDays').addClass('invalid');
                    jGrowl_to_notyfy("Grace Period(Days) , must be whole number! ", {theme: 'jmsg-error'});
                    ret = false;
                }
                if (jQuery('#PaymenttermGraceDays').val() > 90) {
                    jQuery('#PaymenttermGraceDays').addClass('invalid');
                    jGrowl_to_notyfy("Grace Period (Days), required  0 to 90! ", {theme: 'jmsg-error'});
                    ret = false;
                }
            }
            else
            {
                jQuery('#PaymenttermGraceDays').addClass('invalid');
                jGrowl_to_notyfy("Grace Period(Days) , can't be empty! ", {theme: 'jmsg-error'});
                ret = false;
            }
            if (jQuery('#PaymenttermNotifyDays').val() != '' || jQuery('#PaymenttermNotifyDays') != null) {
                if (/\D/.test(jQuery('#PaymenttermNotifyDays').val())) {
                    jQuery('#PaymenttermNotifyDays').addClass('invalid');
                    jGrowl_to_notyfy(" Notify(Days), must be whole number! ", {theme: 'jmsg-error'});
                    ret = false;
                }
                if (jQuery('#PaymenttermNotifyDays').val() > 90) {
                    jQuery('#PaymenttermNotifyDays').addClass('invalid');
                    jGrowl_to_notyfy("Notify(Days),required 0 to 90!", {theme: 'jmsg-error'});
                    ret = false;
                }
            }


            if (jQuery('#PaymenttermType').val() == '4') {
                if (jQuery('#PaymenttermDays2').val() == '' || jQuery('#PaymenttermDays2') == null) {
                    jQuery('#PaymenttermDays2').addClass('invalid');
                    jGrowl_to_notyfy("Some Days of Month,must be between 0 to 31!", {theme: 'jmsg-error'});
                    ret = false;

                }

                var arr = jQuery('#PaymenttermDays2').val().split(',');
                if (arr.length >= 1) {
                    for (var i = 0; i < arr.length; i++) {
                        if (arr[i] > 31) {
                            jQuery('#PaymenttermDays2').addClass('invalid');
                            jGrowl_to_notyfy("Some Days of Month,must be between 0 to 31!", {theme: 'jmsg-error'});
                            ret = false;
                            break;

                        }
                    }
                    ;

                }

            }

            if (jQuery('#PaymenttermType').val() == '1') {
                if (jQuery('#PaymenttermDays4').val() != '' && jQuery('#PaymenttermDays4') != null) {
                    //		 if(jQuery('#PaymenttermDays2').val()>32){
                    //			   jQuery('#PaymenttermDays2').addClass('invalid');
                    //			   jGrowl_to_notyfy("Period,must be between 0 to 31!",{theme:'jmsg-error'});
                    //			   ret= false;     
                    //			}
                }
                else
                {
                    jQuery('#PaymenttermDays2').addClass('invalid');
                    //jGrowl_to_notyfy("Period,can't be empty!",{theme:'jmsg-error'});
                    ret = false;
                }
            }

            return ret;
        }


        jQuery('#add').click(
                function () {
                    jQuery('#list_div').show();
                    jQuery('#msg_div').hide();
                    var action = jQuery(this).attr('href');
                    jQuery('table.list').trAdd(
                            {
                                'action': action,
                                'ajax': '<?php echo $this->webroot ?>paymentterms/js_save',
                                insertNumber: 'first',
                                removeCallback: function () {
                                    if (jQuery('#list_div tr').size() == 1) {
                                        jQuery('#list_div').hide();
                                        jQuery('#msg_div').show();
                                    }
                                },
                                'callback': function (options) {
                                    jQuery('#PaymenttermType').change(function () {
                                        if (jQuery(this).val() == 2) {


                                            jQuery('#PaymenttermDays').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        } else if (jQuery(this).val() == 1) {
                                            jQuery('#PaymenttermDays4').show().attr('name', 'data[Paymentterm][days]');

                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days]').val('');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                        } else if (jQuery(this).val() == 3) {
                                            jQuery('#PaymenttermDays3').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days1]').val('');
                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        } else {
                                            jQuery('#PaymenttermDays2').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days1]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        }
                                    }).change();
                                    jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
                                    jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
                                    jQuery('select').addClass('select in-select');
                                    jQuery('textarea').addClass('textarea in-textarea');
                                },
                                'onsubmit': function (options) {
                                    var re = true;
                                    var type = jQuery('#' + options.log).find('#PaymenttermType').val();
                                    var PaymenttermName = jQuery('#' + options.log).find('#PaymenttermName').val();
                                    var PaymenttermGraceDays = jQuery('#' + options.log).find('#PaymenttermGraceDays').val();
                                    var PaymenttermNotifyDays = jQuery('#' + options.log).find('#PaymenttermNotifyDays').val();
                                    var Days4 = jQuery('#' + options.log).find('#PaymenttermDays4').val();
                                    if (type == 1) {
                                        if ((Days4 == null || Days4 == '') && jQuery('#PaymenttermType').val() == 1) {
                                            jQuery.jGrowlError('Period must contain numeric characters only');
                                            jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                            re = false;

                                        }
                                    }
                                    if (/\D/.test(Days4) && jQuery('#PaymenttermType').val() == 1) {
                                        jQuery.jGrowlError(' Period must contain numeric characters only');
                                        jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                        re = false;
                                    }

                                    re = validation_data();


                                    /*if(/\D/.test(PaymenttermGraceDays) ){
                                     jQuery.jGrowlError('Grace Period must contain numeric characters only');
                                     jQuery('#'+options.log).find('#PaymenttermGraceDays').addClass('invalid');
                                     re=false;
                                     }
                                     if(/\D/.test(PaymenttermNotifyDays)){
                                     jQuery.jGrowlError('Grace Period must contain numeric characters only');
                                     jQuery('#'+options.log).find('#PaymenttermNotifyDays').addClass('invalid');
                                     re=false;
                                     }*/
                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 1 && /[^\d]/.test(jQuery('#' + options.log).find('#PaymenttermDays4').val()))
                                    {
                                        jQuery.jGrowlError('this is must number!');
                                        jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                        re = false;
                                    }
                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 4 && /[^\d,]/.test(jQuery('#' + options.log).find('#PaymenttermDays2').val()))
                                    {
                                        jQuery.jGrowlError('this is must number!');
                                        jQuery('#' + options.log).find('#PaymenttermDays2').addClass('invalid');
                                        re = false;

                                    }

                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 1 && jQuery('#' + options.log).find('#PaymenttermDays4').val() > 90) {
                                        jQuery.jGrowlError('Grace Period is max 90');
                                        jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                        re = false;
                                    }
                                    if (PaymenttermName == null || PaymenttermName == '') {
                                        jQuery.jGrowlError(' Payment name is required！');
                                        jQuery('#' + options.log).find('#PaymenttermName').addClass('invalid');
                                        return false;
                                    }

                                    var data = jQuery.ajaxData("<?php echo $this->webroot ?>paymentterms/paymentterm_name?paymentterm_name=" + PaymenttermName);
                                    if (!data.indexOf('false')) {
                                        jQuery.jGrowlError(PaymenttermName + ' name is already in use!');
                                        re = false;

                                    }

                                    if (/[^0-9A-Za-z-\_\s]+/.test(jQuery('#PaymenttermName').val()) || jQuery('#PaymenttermName').val().length > 100) {
                                        jQuery('#PaymenttermName').addClass('invalid');
                                        jGrowl_to_notyfy(" Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 100 characters in length.", {theme: 'jmsg-error'});
                                        re = false;

                                    }
                                    return re;
                                }
                            }
                    );
                    return false;
                }
        );
        jQuery('.edit').click(
                function () {
                    var action = jQuery(this).attr('href');
                    var payment_term_id = jQuery(this).attr('payment_term_id');

                    jQuery(this).parent().parent().trAdd(
                            {
                                'action': action,
                                'ajax': '<?php echo $this->webroot ?>paymentterms/js_save?id=' + payment_term_id,
                                'saveType': 'edit',
                                'callback': function (options) {
                                    jQuery('#PaymenttermType').change(function () {
                                        if (jQuery(this).val() == 2) {


                                            jQuery('#PaymenttermDays').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        } else if (jQuery(this).val() == 1) {
                                            jQuery('#PaymenttermDays4').show().attr('name', 'data[Paymentterm][days]');

                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days]').val('');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                        } else if (jQuery(this).val() == 3) {
                                            jQuery('#PaymenttermDays3').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days1]').val('');
                                            jQuery('#PaymenttermDays2').hide().attr('name', 'data[Paymentterm][days2]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        } else {
                                            jQuery('#PaymenttermDays2').show().attr('name', 'data[Paymentterm][days]');
                                            jQuery('#PaymenttermDays').hide().attr('name', 'data[Paymentterm][days1]').val('');
                                            jQuery('#PaymenttermDays3').hide().attr('name', 'data[Paymentterm][days3]').val('');
                                            jQuery('#PaymenttermDays4').hide().attr('name', 'data[Paymentterm][days4]').val('');
                                        }
                                    }).change();
                                    jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
                                    jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
                                    jQuery('select').addClass('select in-select');
                                    jQuery('textarea').addClass('textarea in-textarea');
                                },
                                'onsubmit': function (options) {
                                    var re = true;
                                    var type = jQuery('#' + options.log).find('#PaymenttermType').val();
                                    var PaymenttermName = jQuery('#' + options.log).find('#PaymenttermName').val();
                                    var PaymenttermGraceDays = jQuery('#' + options.log).find('#PaymenttermGraceDays').val();
                                    var PaymenttermNotifyDays = jQuery('#' + options.log).find('#PaymenttermNotifyDays').val();
                                    var PaymenttermPaymentTermId = jQuery('#' + options.log).find('#PaymenttermPaymentTermId').val();

                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 1 && /[^\d]/.test(jQuery('#' + options.log).find('#PaymenttermDays4').val()))
                                    {
                                        jQuery.jGrowlError('this is must number!');
                                        jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                        return false;
                                    }
                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 4 && /[^\d,]/.test(jQuery('#' + options.log).find('#PaymenttermDays2').val()))
                                    {
                                        jQuery.jGrowlError('this is must number!');
                                        jQuery('#' + options.log).find('#PaymenttermDays2').addClass('invalid');
                                        return false;
                                    }
                                    if (jQuery('#' + options.log).find('#PaymenttermType').val() == 1 && jQuery('#' + options.log).find('#PaymenttermDays4').val() > 90) {
                                        jQuery.jGrowlError('Grace Period is max 90');
                                        jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                        return false;
                                    }
                                    var Days4 = jQuery('#' + options.log).find('#PaymenttermDays4').val();
                                    if (type == 1)
                                    {
                                        if (Days4 == null || Days4 == '' && jQuery('#PaymenttermType').val() == 1) {
                                            jQuery.jGrowlError('Period must contain numeric characters only');
                                            jQuery('#' + options.log).find('#PaymenttermDays4').addClass('invalid');
                                            return false;
                                        }
                                    }

                                    if (/[^0-9A-Za-z-\_\s]+/.test(jQuery('#PaymenttermName').val()) || jQuery('#PaymenttermName').val().length > 100) {
                                        jQuery('#PaymenttermName').addClass('invalid');
                                        jGrowl_to_notyfy(" Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 100 characters in length.", {theme: 'jmsg-error'});
                                        return false;

                                    }
                                    if (jQuery.ajaxData('<?php echo $this->webroot ?>paymentterms/checkName?name=' + PaymenttermName + '&id=' + PaymenttermPaymentTermId) == 'false')
                                    {
                                        jQuery.jGrowlError(PaymenttermName + ' name is already in use!');
                                        jQuery('#' + options.log).find('#PaymenttermName').addClass('invalid');
                                        re = false;
                                    }

                                    re = validation_data();
                                    return re;
                                }
                            });
                    return false;
                }
        );
        $(function () {
//            $("div.navbar").hide();
            $.gritter.add({
                title: '<?php __('Setup Payment Terms'); ?>',
                text: '<?php __('Your system up and running, you need to set the Payment Terms.'); ?>',
                sticky: true
            });
<?php if (!$mydata): ?>
                $("#add").click();
<?php endif; ?>
        });

        $(document).on('DOMNodeInserted', function(){
            $('#delete').attr('title', 'Exit');
        });

        $(document).ready(function(){
            $('#PaymenttermType > option:first-child').text('Day of Month');
            $('#PaymenttermType > option:nth-child(4)').text('Some Days of Month');
            $('#PaymenttermType > option:nth-child(5)').text('Twice in a Month');
        });

        $('#add').on('click', function(){
            $('#PaymenttermType > option:first-child').text('Day of Month');
            $('#PaymenttermType > option:nth-child(4)').text('Some Days of Month');
            $('#PaymenttermType > option:nth-child(5)').text('Twice in a Month');
        });

    </script>
</div>
