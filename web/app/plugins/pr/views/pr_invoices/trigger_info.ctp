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
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/view">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/vendor_invoice">
        <?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>pr/pr_invoices/view" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
        <i></i>
        &nbsp;<?php echo __('goback', true); ?>
    </a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form action="" method="get">
                <div class="row-fluid separator">
                    <div class="span2 offset2" align="right">
                        <span><?php __('Trigger Invoice for')?>:</span>
                    </div>
                    <div class="span2">
                        <input type="text" value="<?php echo $selected_date; ?>" class="input in-text wdate" name="invoice_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',maxDate:'<?php echo date('Y-m-d'); ?>'});" readonly="">
                    </div>
                    <div class="span2">
                        <span><?php __('Status'); ?>:</span>
                        <?php echo $form->input('status',array('type'=>'select','selected' => $selected_status,'label'=>false,
                            'div' => false,'options'=>array(0 => 'All',1=>'No',2 => 'Yes'),'class'=>'width120','name'=>'status')); ?>
                    </div>
                    <div class="span2">
                        <input type="submit"  class="btn btn-primary" value="<?php __('Submit'); ?>">
                    </div>
                </div>
            </form>
            <?php if($selected_date): ?>
                <?php if(empty($result_data)): ?>
                    <div class="msg center">
                        <br />
                        <h2>
                            <?php if($selected_status == 0){
                                __($selected_date.' is no need to generate invoice carrier');
                            }else{
                                __('no_data_found');
                            } ?>
                        </h2>
                    </div>
                <?php else: ?>
                    <table id="mytable" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="checked_all" onclick="checkAll(this,'mytable');" /></th>
                            <th><?php __('Carrier Name'); ?></th>
                            <th><?php __('Invoicing Cycle') ?></th>
                            <th><?php __('Status'); ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($result_data as $result_data_item): ?>
                            <tr>
                                <td><input type="checkbox" class="checked_single" item_id="<?php echo $result_data_item['Clients']['client_id'] ?>" /></td>
                                <td><?php echo $result_data_item['Clients']['name'] ?></td>
                                <td>
                                    <?php
                                    if ($result_data_item['PaymentTerm']['type'] == 1)
                                    {
                                        echo str_replace('X', $result_data_item['PaymentTerm']['days'], __('Every', true));
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        echo $result_data_item['PaymentTerm']['days'];
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        echo 'day(s)';
                                    }
                                    elseif ($result_data_item['PaymentTerm']['type'] == 2)
                                    {
                                        //echo str_replace('X',$arrayDay[$result_data_item['PaymentTerm']['days']],__('onxdayofmonth',true));
                                        echo "Every";
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        //echo $arrayDay[$result_data_item['PaymentTerm']['days']];
                                        $prDate = explode(' ', $arrayDay[$result_data_item['PaymentTerm']['days']]);
                                        echo $prDate[0] . "<sup>" . $prDate[1] . "</sup>" . "&nbsp;&nbsp;&nbsp;of&nbsp;&nbsp;&nbsp;the&nbsp;&nbsp;&nbsp;month";
                                    }
                                    elseif ($result_data_item['PaymentTerm']['type'] == 3)
                                    {
                                        //echo str_replace('X',$arrayWeekDay[$result_data_item['PaymentTerm']['days']],__('onxdayofweek',true));
                                        echo "Every";
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        echo $arrayWeekDay[$result_data_item['PaymentTerm']['days']];
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        echo "of" . "&nbsp;&nbsp;&nbsp;" . "the" . "&nbsp;&nbsp;&nbsp;" . "week";
                                    }
                                    else
                                    {
                                        //echo str_replace('X',$result_data_item['PaymentTerm']['more_days'],__('someonxdayofmonth',true));

                                        echo "Every";
                                        echo "&nbsp;&nbsp;&nbsp;";

                                        $new_date = array();
                                        $mydates_array = explode(',', $result_data_item['PaymentTerm']['more_days']);
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
                                <td><?php echo $result_data_item['has_history'] ?></td>
                                <td>
                                    <a class="trigger_single" item_id="<?php echo $result_data_item['Clients']['client_id'] ?>" href="javascript:void(0)" title="Trigger">
                                        <i class="icon-expand"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <form id="trigger_form" action="<?php echo $this->webroot; ?>pr/pr_invoices/trigger" method="post">
                        <input type="hidden" name="invoice_date" value="<?php echo $selected_date; ?>" />
                        <input type="hidden" name="client_ids" class="client_ids" />
                        <div class="separator hide" id="mass_action">
                            <?php __('Trigger Selected'); ?>:
                            <select class="auto_sending" name="auto_sending" >
                                <option value="0"><?php __('Trigger Invoice without Email'); ?></option>
                                <option value="1"><?php __('Trigger Invoice with Email'); ?></option>
                            </select>
                            <input class="input btn-primary btn margin-bottom10" id="trigger_form_button" type="button" value="Submit">
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        var checked_size = $("#mytable").find(":checked").size();
        if(checked_size){
            $("#mass_action").show();
        }else{
            $("#mass_action").hide();
        }

        $("#mytable").find(":checkbox").click(function(){
            var checked_size = $("#mytable").find(":checked").size();
            if(checked_size){
                $("#mass_action").show();
            }else{
                $("#mass_action").hide();
            }
        });

        $("#trigger_form_button").click(function(){
            var $client_ids_input = $("#trigger_form").find(".client_ids").eq(0);
            $client_ids_input.val("");
            bootbox.confirm('<?php __("Are you sure to trigger invoice of  these carrier"); ?>?',function(result){
                if(result){
                    $("#mytable").find(":checked").each(function(){
                        var client_id = $(this).attr('item_id');
                        var $client_ids_input_val = $client_ids_input.val();
                        if($client_ids_input_val){
                            $client_ids_input.val($client_ids_input_val+","+client_id);
                        }else{
                            $client_ids_input.val(client_id);
                        }
                    });
                    console.log($client_ids_input.val());
                    $("#trigger_form").submit();
                }
            });
        });

        $(".trigger_single").click(function(){
            var $client_ids_input = $("#trigger_form").find(".client_ids").eq(0);
            var client_id = $(this).attr('item_id');
            $client_ids_input.val(client_id);
            bootbox.dialog("<?php __("Are you sure to trigger"); ?>?",[{
                "label" : "Cancel"
            },{
                "label" : "With Email",
                "class" : "btn-primary",
                "callback" : function(){
                    $("#trigger_form").find(".auto_sending").val(1);
                    $("#trigger_form").submit();
                }
            },{
                "label" : "Without Email",
                "class" : "btn-primary",
                "callback" : function(){
                    $("#trigger_form").find(".auto_sending").val(0);
                    $("#trigger_form").submit();
                }
            }]);



        });

    });
</script>
