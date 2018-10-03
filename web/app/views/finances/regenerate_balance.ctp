<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/regenerate_balance">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/regenerate_balance">
        <?php echo __('Regenerate Balance', true); ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Regenerate Balance', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <!--    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="--><?php //echo $this->webroot?><!--syspris/view_syspri/--><?php // echo $module_id;?><!--"><i></i> --><?php //__('Back'); ?><!--</a>-->
</div>
<div class="clearfix"></div>

<?php //****************************************************页面主体?>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php echo $form->create ('finances', array ('action' => 'regenerate_balance' ));?>

            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tbody>
                <tr>
                    <td class="align_right"><?php echo __('Carrier Name',true);?> </td>
                    <td>
                        <?php echo $form->input('carrier_name', array('options'=>$clients,'label'=>false ,'div'=>false,'type'=>'select'));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Balance On',true);?>* </td>
                    <td>
                        <?php echo $form->input('balance_on', array('label'=>false ,'div'=>false,'type'=>'text','class'=>'validate[required] Wdate width220'));?>
                    </td>
                </tr>
                <tr id="current_balance_tr" class="hide">
                    <td class="align_right"></td>
                    <td>
                        <input type="text" id="current_balance" readonly class="width220" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('New Balance',true);?>* </td>
                    <td>
                        <?php echo $form->input('new_balance', array('label'=>false ,'div'=>false,'type'=>'text','class'=>'width220 validate[required,custom[number]]'));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Regenerate Actual Balance',true);?> </td>
                    <td>
                        <?php echo $form->input('is_actual', array('options'=>(array('1'=> __('True',true),'0'=> __('False',true))),'label'=>false ,'div'=>false,'type'=>'select'));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Regenerate Mutual Balance',true);?> </td>
                    <td>
                        <?php echo $form->input('is_mutual', array('options'=>(array('1'=> __('True',true),'0'=> __('False',true))),'label'=>false ,'div'=>false,'type'=>'select'));?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div id="form_footer" class="button-groups center separator">
                <input type="submit" class="btn btn-primary" value="<?php echo __('submit')?>" />
		<input type="reset"  value="<?php __('Revert')?>" class="btn btn-default" />
            </div>
            <?php echo $form->end();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    function getCurrentBalance()
    {
        var old_date_val = $dp.cal.getDateStr();
        var new_date_val = $dp.cal.getNewDateStr();
        var client_id = $("#financesCarrierName").val();
//        var test_str = new_date_val+" client id :" + client_id;
        if(!new_date_val)
            $("#current_balance_tr").hide();
        else {
            $("#current_balance_tr").show();
            var actual_head = "Actual Balance(" + new_date_val + ")";
            $("#current_balance_tr").children().eq(0).html(actual_head);
            if (old_date_val != new_date_val) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>finances/ajax_get_current_balance",
                    data: {'client_id': client_id, "date": new_date_val},
                    dataType: 'json',
                    success: function (data) {
                        $("#current_balance").val(data.balance);
                    }
                });
            }
        }
    }
    $(function(){
        $("#financesCarrierName").change(function(){
            var client_id = $(this).val();
            var date = $("#financesBalanceOn").val();
            if(date) {
                $("#current_balance_tr").show();
                var actual_head = "Actual Balance(" + date + ")";
                $("#current_balance_tr").children().eq(0).html(actual_head);
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>finances/ajax_get_current_balance",
                    data: {'client_id': client_id, "date": date},
                    dataType: 'json',
                    success: function (data) {
                        $("#current_balance").val(data.balance);
                    }
                });
            }
        }).trigger('change');


        $("#financesBalanceOn").click(function(){
            var old_date = $(this).val();
            WdatePicker({
                dateFmt:'yyyy-MM-dd',
                lang:'en',
                maxDate:'<?php echo date('Y-m-d'); ?>',
                onpicking:getCurrentBalance
            });
        });
    })
</script>
