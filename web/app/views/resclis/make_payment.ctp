<?php echo $this->element("resclis_make_payment_one/title")?>
<div id="container">
    <form   id="submitForm" action="<?php echo $this->webroot?>clientpayments/make_payment" method="post" >
        <fieldset>
            <legend>
                <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?><a onclick="addItem('ip');" href="javascript:void(0)">
                    <img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Payment',true);?>
                </a>
                <?php }?>
            </legend>
            <?php $user_id=$_SESSION['sst_user_id'];?>
            <table class="list list-form">
                <thead>
                    <tr>
                        <td width="13%"><?php echo __('carrier',true);?></td>
                        <td width="13%" id="type_tran"><?php echo __('Payment Type',true);?></td>
                        <td width="13%"><?php echo __('Invoices',true);?></td>
                        <td width="13%"><?php echo __('Payment Type',true);?></td>
                        <td width="13%"><?php echo __('Transaction Amt',true);?></td>
                        <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?><td width="13%" class="last"><?php echo __('action',true);?></td><?php }?>
                    </tr>
                </thead>
                <tbody class="rows" id="rows-ip">
                    <tr style="" id="row-1" class="row-1">
                        <td>
                            <?php 	
                            echo $form->input('client_id',array('name'=>'accounts[1][client_id]','options'=>$appResclis->options_client($client),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                            ?>
                        </td>
                        <td><?php echo $form->input("invoice_type",array('options'=>Array('0'=> __('Payment Sent',true), '1'=> __('Payment Received',true)),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'))?></td>
                        <td><?php echo $form->input("invoice_number",array('div'=>false,'label'=>false,'options'=>Array(),'name'=>'accounts[1][invoice_number]'))?></td>
                        <td> 
                            <?php 		
                            $t=array('1'=> __('Prepayment',true),'2'=> __('Invoice Payment',true));
                            echo $form->input('payment_type',array('options'=>$t, 'name'=>"accounts[1][payment_type]",'id'=>'ip-payment_type-1','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                            ?>
                        </td>
                        <td>
                            <input type="text" id="ip-amount-1" name="accounts[1][amount]" class="input in-text" value="" validate='Num'>
                        </td>

                        <td class="last">
                            <input type="hidden" readonly="" value="<?php echo date ( "Y-m-d   H:i:s" ) ;?>"  name="accounts[1][entered_date]" class="input in-text wdate" >
                                   <a onclick=" $(this).closest('tr').remove();" rel="delete" href="javascript:void(0)">
                                <i class="icon-remove"></i>
                            </a>
                            <input type="hidden" name="accounts[1][payment_method]" value="<?php echo $user_id?>" >
                        </td>
                    </tr>
                    <tr style="display:none;" id="tpl-ip" class="  row-2">
                        <td >
                            <?php 	echo $form->input('client_id',array('options'=>$appResclis->options_client($client),'name'=>"_accounts[%n][client_id]",'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                        </td>
                        <td>
                            <?php echo $form->input("invoice_type",array('options'=>Array('0'=>'Payment Sent', '1'=>'Payment Receive'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'))?>
                        </td>
                        <td><?php echo $form->input("invoice_number",array('div'=>false,'label'=>false,'options'=>Array(),'name'=>'_accounts[%n][invoice_number]'))?></td>
                        <td>
                            <?php 		
                            $t=array('1'=>'Payment','2'=>'Credit','3'=>'Offset');
                            echo $form->input('payment_type',array('options'=>$t, 'name'=>"_accounts[%n][payment_type]",'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                            ?>
                        </td>
                        <td>
                            <input type="text"  class="input in-text" name="_accounts[%n][amount]" validate='Num' >
                        </td>

                        <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?><td class="last">
                            <input type="hidden" value="<?php echo date ( "Y-m-d   H:i:s" );?>" name="_accounts[%n][enterd_time]" class="input in-text wdate">
                                   <a rel="delete" href="javascript:void(0)">
                                <i class="icon-remove"></i>
                            </a>
                            <input type="hidden" value='<?php echo $user_id?>' name="_accounts[%n][payment_method]" class="input in-text">
                        </td>
                        <?php }?>
                    </tr>
                </tbody>
            </table>  
        </fieldset>
        <fieldset>
            <legend>
                <!--<a id='invoice_title' href="#" onclick="view_invoice(this);return false;">
                        View Invoice Info
                </a>
                <script type="text/javascript">
                   function view_invoice(){
                                if(jQuery('#invoice_title').html()!='Close Invoice Info'){
                                                        jQuery('#invoice_title').html('Close Invoice Info');
                                                        jQuery('#invoice_info_list').show();
                                }else{
                                                                        jQuery('#invoice_title').html('View Invoice Info');
                                                                        jQuery('#invoice_info_list').hide();
                                                }
                                }
                </script>		
                --></legend>
            <div id="invoice_info_list" style="display:block">
                <!--<table  class="list list-form">
                        <thead>
                            <tr>
                                <td width="20%"><?php echo __('Invoice',true);?></td>
                                <td><?php echo __('Carriers',true);?></td>
                                <td width="20%">
                                        <?php echo __('Time',true);?>
                                </td>
                                <td width="20%">
                                        Type
                                </td>
                                <td width="20%">
                                        Is Overdue:
                                        
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                                         <tr style="height:30px">
                                                                <td width="20%"></td>
                                <td width="20%"></td>
                                <td width="20%"></td>
                                <td width="20%"></td>
                                <td width="20%"></td>
                             </tr>
                        </tbody>
                </table>
                -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo __('Time',true);?>:
                <input type="text" value="" class="input in-text wdate in-input" name="start_date" style="width: 60px;" id="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="" realvalue=""/>
                －
                <input type="text" value="" class="wdate input in-text in-input" name="end_date" style="width: 60px;" id="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly=""/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <?php echo __('Paid',true);?>:			
                <select id="invoice_paid">
                    <option value=''><?php __('All Unpaid & Partially Bills')?></option>
                    <option value='1'><?php __('Partially Paid  Bills')?></option>
                    <option value='2'><?php echo __('Unpaid Bills',true);?></option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo __('Overdue',true);?>:
                <select id="invoice_overdue">
                    <option value=''><?php __('All')?></option>
                    <option value='1'><?php __('Yes')?></option>
                    <option value='2'><?php __('No')?></option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" value='<?php __('Filter')?>' onclick="filter()"/>
            </div>
        </fieldset>
        <div id="form_footer">
            <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?>
            <input id='sub' type="submit" value="<?php __('submit')?>" class="input in-submit">
            <?php }?>
            <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit">
        </div>
    </form>
    <?php echo $this->element("resclis_make_payment_one/js")?>
</div>

<script type="text/javascript"><!--
    var invoice_options_get='';

    jQuery('#invoice_type').each(function(){
        jQuery(this).change(function(){
            var that=jQuery(this);
            var client_id = that.parent().parent().find('#client_id').val();
            jQuery.get('<?php echo $this->webroot?>invoices/invoice_options/'+client_id+'?'+invoice_options_get+'&invoice_type='+jQuery(this).val() ,function(data){
                that.parent().parent().find('#invoice_number').html('').append('<option value=""/>').append(jQuery(data).html()).change();
            });
        }).change();
    });

    jQuery('#client_id').each(function(){
        jQuery(this).change(function(){
            var that=jQuery(this);
            var invoice_type = that.parent().parent().find('#invoice_type').val();
            jQuery.get('<?php echo $this->webroot?>invoices/invoice_options/'+jQuery(this).val()+'?'+invoice_options_get+'&invoice_type='+invoice_type ,function(data){
                that.parent().parent().find('#invoice_number').html('').append('<option value=""/>').append(jQuery(data).html()).change();
            });
        }).change();
    });
    jQuery('select[id=invoice_number]').change(function(){
        if(jQuery(this).val()==''){
            return;
        }
        var data=jQuery.ajaxData('<?php echo $this->webroot?>invoices/invoice_info?invoice_number='+jQuery(this).val());
        jQuery("<div id='temp'/>").html(data).appendTo('body');
        jQuery('#invoice_info_list table tbody tr td:nth-child(1)').html(jQuery('#temp table tr td:nth-child(1)').html());
        jQuery('#invoice_info_list table tbody tr td:nth-child(2)').html(jQuery('#temp table tr td:nth-child(2)').html());
        jQuery('#invoice_info_list table tbody tr td:nth-child(3)').html(jQuery('#temp table tr td:nth-child(3)').html());
        jQuery('#invoice_info_list table tbody tr td:nth-child(4)').html(jQuery('#temp table tr td:nth-child(4)').html());
        jQuery('#invoice_info_list table tbody tr td:nth-child(5)').html(jQuery('#temp table tr td:nth-child(5)').html());
        jQuery('#temp').remove();
    })

    //jQuery('table.list tr').live('click',function(){
    //	jQuery(this).find('select[id=invoice_number]').change();
    //	jQuery(this).find('select[id^=ip-invoice_number]').change();
    //});

    jQuery('select[id^=ip-client_id]').live('change',function(){
        var that=jQuery(this);
        jQuery.get('<?php echo $this->webroot?>invoices/invoice_options/'+jQuery(this).val()+'?'+invoice_options_get,function(data){
            that.parent().parent().find('select[id^=ip-invoice_number]').html('').append('<option value=""/>').append(jQuery(data).html());
        });
    });
    function filter(){
        invoice_options_get='start_date='+jQuery('#start_date').val();
        invoice_options_get+='&end_date='+jQuery('#end_date').val();
        invoice_options_get+='&invoice_type='+jQuery('#invoice_type').val();
        invoice_options_get+='&invoice_paid='+jQuery('#invoice_paid').val();
        invoice_options_get+='&invoice_overdue='+jQuery('#invoice_overdue').val();
        jQuery('select[id^=ip-client_id],select[id=client_id]').each(function(){jQuery(this).change()});
    }
    jQuery('select[id^=ip-invoice_number],select[id=invoice_number]').change(function(){
        if(jQuery(this).val()==''){
            jQuery(this).parent().parent().find('select[id^=ip-payment_type],select[id^=payment_type]').find('option[value=3]').remove();
        }else{
            jQuery(this).parent().parent().find('select[id^=ip-payment_type],select[id^=payment_type]').find('option[value=3]').remove();
            jQuery('<option>').html('Offset').val('3').appendTo(jQuery(this).parent().parent().find('select[id^=ip-payment_type],select[id^=payment_type]'));
        }
    }).change();

    jQuery('select[id^=ip-payment_type],select[id=payment_type]').change(function(){
        //alert(jQuery(this).val()=='1');
        if (jQuery(this).val() == '1')
        {
            $("#type_tran").html('<?php echo __('Payment Type',true);?>');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=0]').html('Payment Sent');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=1]').html('Payment Received');
        }
        else if (jQuery(this).val() == '2')
        {
            $("#type_tran").html('Credit Type');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=0]').html('Credit Given');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=1]').html('Credit Received');
            //jQuery('<option>').html('Credit Given').val('0').appendTo(jQuery(this).parent().parent().find('select[id=invoice_type]'));
        }
        else if (jQuery(this).val() == '3')
        {
            $("#type_tran").html('Offset Type');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=0]').html('Vendor Balance');
            jQuery(this).parent().parent().find('select[id=invoice_type]').find('option[value=1]').html('Client Balance');
            //jQuery('<option>').html('Credit Given').val('0').appendTo(jQuery(this).parent().parent().find('select[id=invoice_type]'));
        }
    });
    --></script>
<script type="text/javascript"><!--
    //jQuery('input[validate=Num],select[validate=Num]').xkeyvalidate({type:'Num'}); 
    jQuery(document).ready(function(){
        jQuery('#submitForm').submit(function(){
            re = true;
            var amt_flag=true;
            jQuery('input[validate=Num]:visible').map(function(){
                if(jQuery(this).val()==''){
                    jQuery(this).addClass('invalid');
                    amt_flag=false;
                    re=false;
                }
                if(!/^\d+\.?\d{0,5}$/.test(jQuery(this).val())){
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('Transaction Amt,must contain numeric characters only!',{theme:'jmsg-error'});
                    re =false;           
                }
                if (/[\u4e00-\u9fa5]+/.test(jQuery(this).val())){
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('Transation Amt can only be digit!',{theme:'jmsg-error'});
                    re=false;
                }
                if(/\-{2,}/.test(jQuery(this).val())) {
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('Transactionmust contain numeric characters only.',{theme:'jmsg-error'});
                    re=false;
                }
            });
            jQuery('input[validate=Num]:visible').map(function(){
                if(/[^-\.\D]/g.test(jQuery('input[check=Money]').val())){
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('Please fill Amount field correctly (only digits allowed).',{theme:'jmsg-error'});
                    re =false;
                }
            });
            jQuery('input[validate=Num]:visible').map(function(){
                if(Number(jQuery(this).val())>10000000){
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('you meony is more than 10000000',{theme:'jmsg-error'});
                    re =false;
                }
            });
            if(amt_flag==false){
                jGrowl_to_notyfy('Transaction Amt is required',{theme:'jmsg-error'});
            }
            return re;
        });
    });
    //		jQuery('select[id^=ip-payment_type],select[id=payment_type]').change(function(){
    //			if(jQuery(this).val()!=1){
    //				jQuery(this).parent().parent().find('input[id^=ip-payment_time],input[id=payment_time]').hide();
    //			}else{
    //				jQuery(this).parent().parent().find('input[id^=ip-payment_time],input[id=payment_time]').show();
    //			}
    //		});
    --></script>