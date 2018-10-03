<?php 
	$project_name=Configure::read('project_name');

if (isset ( $exception_msg ) && $exception_msg) :	?>
<?php	echo $this->element ( 'common/exception_msg' );
	
	if(!array_keys_value($name,'0.0.name')==''){
		  $name="[".array_keys_value($name,'0.0.name')."]";
	}
	?>
<?php else : ?>
<?php 
//pr($this->data);
if(isset($this->data['Role']['role_name'])){
  $name_role=$this->data['Role']['role_name'];	
}else{
	
	$name_role='';
	
}
if($name_role!=''){
echo $this->element('layout/header',array('h1'=>'Configuration','h1_span'=>' Edit Role :<font color=\'red\' title=\'Name\' >'.$name_role.'</font> ','back_url'=>'roles/view'));}else{echo $this->element('layout/header',array('h1'=>'Configuration','h1_span'=>' Add Role <font color=\'red\' title=\'Name\' >'.$name_role.'</font> ','back_url'=>'roles/view'));}?>

<div class="container"> <?php echo $form->create ('Role', array ('action' => 'add_role' ,'name'=>'role_name'));?>
  <table class="cols">
    <col width="40%"/>
    <col width="40%"/>
    <col width="20%"/>
    <tr>
      <td style="vertical-align: top;"><!-- COLUMN 1 -->
        
        <?php //**********系统信息**************?>
        <fieldset>
          <legend></legend>
          <table class="form">
            <tr>
              <td><?php echo __('Role Name',true);?>:</td>
              <td><?php echo $form->input('role_name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'100','class'=>'input in-text'))?></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:32px;">
                  <div> <?php echo $form->checkbox('active')?>
                    <label for="cp_modules-c_info"><?php __('Active')?></label>
                  </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Management',true);?> &nbsp;</legend>
          
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:132px;">
                  <div> <?php echo $form->checkbox('is_carriers')?>
                    <label for="cp_modules-c_info"><?php echo __('Carriers',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_transaction')?><label for="cp_modules-c_info"><?php echo __('Transaction',true);?></label>  </div>
                  <div> <?php echo $form->checkbox('is_mutual_settlements')?>
                    <label for="cp_modules-c_invoices"><?php echo __('Mutual Settlements',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_invoices')?>
                    <label for="cp_modules-c_rates"><?php echo __('Invoices',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_payment')?>
                    <label for="cp_modules-c_stats_msa"><?php echo __('Payment',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_unpaid_bills')?>
                    <label for="cp_modules-c_stats_msa"><?php echo __('Unpaid Bills',true);?></label>
                     </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Statistics',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height: 222px;">
                  <div> <?php echo $form->checkbox('is_spam_report')?>
                    <label for="cp_modules-c_info"><?php echo __('Spam Report',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_location_report')?>
                    <label for="cp_modules-c_info"><?php echo __('Location Report',true);?></label>
                  </div>
                  <div> <?php echo $form->checkbox('is_origterm')?>
                    <label for="cp_modules-c_info"><?php echo __('Orig-Term Report',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_summary_report')?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('issummaryreport')?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_usage_report')?>
                    <label for="cp_modules-c_rates"><?php echo __('Usage Report',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_cdr_list')?>
                    <label for="cp_modules-c_stats_cdrs"><?php echo __('CDRs List',true);?></label>
                     </div>
                  <div> <?php echo $form->checkbox('is_qos_report')?>
                    <label for="cp_modules-c_stats_msa"><?php echo __('QoS Report',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_disconnect_cause')?>
                    <label for="cp_modules-c_chpass"><?php echo __('Disconnect Causes',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_billing_mismatch')?>
                    <label for="cp_modules-c_chpass"><?php echo __('Billing Mismatch Report',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_active_call')?>
                    <label for="cp_modules-c_chpass"><?php echo __('Active Call Report',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_termination_report')?>
                    <label for="cp_modules-c_chpass"><?php echo __('Termination Report',true);?></label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Tools',true);?> </legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:102px;">
                  <div> <?php echo $form->checkbox('is_rates_analysis' )?>
                    <label for="cp_modules-c_info"><?php echo __('Rates Analysis',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_call_simulation' )?>
                    <label for="cp_modules-c_info"><?php echo __('Call Simulation',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_ingress_trunk_simulation' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('Ingress Trunk Simulation',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_egress_trunk_simulation' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Egress Trunk simulation',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_sip_capture' )?>
                    <label for="cp_modules-c_info"><?php echo __('SIP Capture',true);?> </label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        
        <!-- / COLUMN 1 --></td>
      <td style="vertical-align: top;"><!-- COLUMN 2 -->
        
        <fieldset>
          <legend> <?php echo __('Routing',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:122px;">
                  <div> <?php echo $form->checkbox('is_digit_mapping' )?>
                    <label for="cp_modules-c_info"><?php echo __('Digit Mapping',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_trunk' )?>
                    <label for="cp_modules-c_info"><?php echo __('Trunk',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_dynamic_routing' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('Dynamic Routing',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_static_route_table' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Static Route Table',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_block_list' )?>
                    <label for="cp_modules-c_info"><?php echo __('Block List',true);?> </label>
                    </div>
                  <div> <?php echo $form->checkbox('is_routing_plan' )?>
                    <label for="cp_modules-c_info"><?php echo __('Routing Plan',true);?> </label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Switch',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:262px;">
                  <div> <?php echo $form->checkbox('is_active_web_session' )?>
                    <label for="cp_modules-c_info"><?php echo __('Active WEB session',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_payment_term' )?>
                    <label for="cp_modules-c_info"><?php echo __('Payment Term',true);?> </label>
                    </div>
                  <div> <?php echo $form->checkbox('is_jurisdiction' )?>
                    <label for="cp_modules-c_info"><?php echo __('Jurisdiction',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_capicity' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('Capicity',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_rate_table' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Rate Table',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_code_deck' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Code Deck',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_time_profile' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Time Profile',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_currency' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Currency',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_task_schedulers' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Task Schedulers',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_mail_template' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Mail Template',true);?></label>
                    </div>
                  <?php if($project_name!='partition'){?>
                  <div> <?php echo $form->checkbox('is_service_charge' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Service Charge',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_voip_gateway' )?>
                    <label for="cp_modules-c_rates"><?php echo __('VoIP Gateway',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_trouble_shoot' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Trouble Shoot',true);?></label>
                    </div>
                  <?php }?>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Configuration',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:192px;">
                  <div> <?php echo $form->checkbox('is_role' )?>
                    <label for="cp_modules-c_info"><?php echo __('Roles',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_user' )?>
                    <label for="cp_modules-c_info"><?php echo __('User',true);?> </label>
                    </div>
                  <div> <?php echo $form->checkbox('is_change_password' )?>
                    <label for="cp_modules-c_info"><?php echo __('Change Password',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_setting' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('Setting',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_import_log' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Import Log',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_export_log' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Export Log',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_cdr_backup' )?>
                    <label for="cp_modules-c_rates"><?php echo __('CDR Backup',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_lrn_setting' )?>
                    <label for="cp_modules-c_rates"><?php echo __('LRN Setting',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_event' )?>
                    <label for="cp_modules-c_rates"><?php echo __('Events',true);?></label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        
        <!-- / COLUMN 2 --></td>
      <?php if($project_name=='exchange'){?>
      <td style="vertical-align: top;"><!-- COLUMN 3 -->
        
        <?php //************************client panel**********************************?>
        <?php if($project_name!='partition'){?>
        <fieldset>
          <legend> <?php echo __('Supprot',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:22px;">
                  <div> <?php echo $form->checkbox('is_ticket' )?>
                    <label for="cp_modules-c_info"><?php echo __('Ticket',true);?></label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <?php }?>
        <fieldset>
          <legend> <?php echo __('Buy',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:122px;">
                  <div> <?php echo $form->checkbox('is_buy_select_country' )?>
                    <label for="cp_modules-c_info"><?php echo __('Select Country',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_search_private_buy' )?>
                    <label for="cp_modules-c_info"><?php echo __('Search Private Buy',true);?> </label>
                    </div>
                  <div> <?php echo $form->checkbox('is_buy_confirm_order' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('All Confirmed Orders',true);?></label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        <fieldset>
          <legend> <?php echo __('Sell',true);?>  &nbsp;</legend>
          <table class="form">
            <tr>
              <td colspan="2" class="value"><div class="cb_select" style="height:122px;">
                  <div> <?php echo $form->checkbox('is_sell_select_country' )?>
                    <label for="cp_modules-c_info"><?php echo __('Select Country',true);?></label>
                    </div>
                  <div> <?php echo $form->checkbox('is_search_private_sell' )?>
                    <label for="cp_modules-c_info"><?php echo __('Search Private Sell',true);?> </label>
                    </div>
                  <div> <?php echo $form->checkbox('is_sell_confirm_order' )?>
                    <label for="cp_modules-c_stats_summary"><?php echo __('All Confirmed Orders',true);?></label>
                    </div>
                </div></td>
            </tr>
          </table>
        </fieldset>
        
        <!-- / COLUMN 2  Report -->
        
        <?php //************************balancenotice**********************************?></td>
      <?php }?>
    </tr>
  </table>
  <?php  if ($_SESSION['role_menu']['Configuration']['roles']['model_w']) {?>
  <div id="form_footer">
    <input type="submit" id="submit" value="<?php echo __('submit')?>" class="input in-submit" />
    <input type="reset"  value="<?php echo __('reset')?>"  class="input in-submit"/>
  </div>
  <?php }?>
  <?php echo $form->end();?> 
  <script type="text/javascript">

//特殊表单验证（只能为数字（Float））
/*jQuery(document).ready(
		function(){
			 jQuery('#ClientName,#ClientLogin').xkeyvalidate({type:'strNum'});
				jQuery('#ClientAllowedCredit,#ClientNotifyClientBalance,#ClientNotifyAdminBalance').xkeyvalidate({type:'Ip'});		
      			jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'});
				jQuery('input[maxLength=32]').xkeyvalidate({type:'Email'});	
				jQuery('#ClientTaxId').xkeyvalidate({type:'Num'});
		}
);*/
</script> 
  <script type="text/javascript">
jQuery('#ClientLowBalanceNotice').disabled({id:'#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
</script> 
  <script type="text/javascript">
    jQuery(document).ready(function(){
    	      jQuery('#RoleAddRoleForm').submit(function(){
    	    	      te= true;
               if(/[^0-9A-Za-z-\_\.]{1,100}/.test(jQuery('#RoleRoleName').val())||jQuery('#RoleRoleName').val().length>100){
            	        jQuery('#RoleRoleName').addClass('invalid');
            	        jGrowl_to_notyfy('Role name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 16 characters!',{theme:'jmsg-error'});
          			        te = false;
                                              }
                if(jQuery('#RoleRoleName').val()==''){
               	 jQuery('#RoleRoleName').addClass('invalid');
      	          jGrowl_to_notyfy('The field name cannot be NULL.',{theme:'jmsg-error'});
    			           te = false;
                                             }
                /*if(isNaN(jQuery('#ClientAllowedCredit').val())){
          	        jQuery('#ClientAllowedCredit').addClass('invalid');
          	        jGrowl_to_notyfy(' Allowed Credit mast number',{theme:'jmsg-error'});
        			        te = false;
                                            }
                if(isNaN(jQuery('#ClientProfitMargin').val())){
                		jQuery('#ClientProfitMargin').addClass('invalid');
          	        jGrowl_to_notyfy(' Profit Margin Credit mast number',{theme:'jmsg-error'});
        			        te = false;
               								}
                if(isNaN(jQuery('#ClientNotifyClientBalance').val())){
		            		jQuery('#ClientNotifyClientBalance').addClass('invalid');
		      	        jGrowl_to_notyfy(' Notify client Credit mast number',{theme:'jmsg-error'});
		    			        te = false;
           									}
                if(isNaN(jQuery('#ClientNotifyAdminBalance').val())){
		            		jQuery('#ClientNotifyAdminBalance').addClass('invalid');
		      	        jGrowl_to_notyfy(' Notify admin Credit mast number',{theme:'jmsg-error'});
		    			        te = false;
	           								}
                if(/[^0-9A-Za-z-\_\.]+/.test(jQuery('#ClientName').val())){
          	        jQuery('#ClientLogin').addClass('invalid');
          	        jGrowl_to_notyfy(' Login must contain alphanumeric characters only.',{theme:'jmsg-error'});
        			        te = false;
                                            }
              
               if(!jQuery.xkeyvalidate('#ClientNotifyClientBalance',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientNotifyClientBalance').val())){
            	   jQuery('#ClientNotifyClientBalance').addClass('invalid');
            	   jGrowl_to_notyfy('Notify client must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
               if(!jQuery.xkeyvalidate('#ClientNotifyAdminBalance',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientNotifyAdminBalance').val())){
            	   jQuery('#ClientNotifyAdminBalance').addClass('invalid');
            	   jGrowl_to_notyfy('Notify admin must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
               if(jQuery('#ClientEmail').val()!='' && !jQuery.xkeyvalidate('#ClientEmail',{type:'Email',noMessage:true})){
                  	jQuery('#ClientEmail').addClass('invalid');
                 	 jGrowl_to_notyfy('Main e-mail must Email',{theme:'jmsg-error'});
                  	te=false;
              							}
             		if(jQuery('#ClientNocEmail').val()!='' && !jQuery.xkeyvalidate('#ClientNocEmail',{type:'Email',noMessage:true})){
                   	jQuery('#ClientNocEmail').addClass('invalid');
                  	 jGrowl_to_notyfy('NOC e-mail must Email',{theme:'jmsg-error'});
                   	te=false;
               							}
             		if(jQuery('#ClientBillingEmail').val()!='' && !jQuery.xkeyvalidate('#ClientBillingEmail',{type:'Email',noMessage:true})){
                       	jQuery('#ClientBillingEmail').addClass('invalid');
                      	 jGrowl_to_notyfy('Billing e-mail must Email',{theme:'jmsg-error'});
                       	te=false;
                   						}
             		if(jQuery('#ClientRateEmail').val()!='' && !jQuery.xkeyvalidate('#ClientRateEmail',{type:'Email',noMessage:true})){
                       	jQuery('#ClientRateEmail').addClass('invalid');
                      	 jGrowl_to_notyfy('Rates e-mail must Email',{theme:'jmsg-error'});
                       	te=false;
                   						}
            		if(!isNaN(jQuery('#ClientAllowedCredit').val())){
               if(!jQuery.xkeyvalidate('#ClientAllowedCredit',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientAllowedCredit').val())){
            	   jQuery('#ClientAllowedCredit').addClass('invalid');
            	   jGrowl_to_notyfy('Notify client must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
            							}
            		if(!isNaN(jQuery('#ClientProfitMargin').val())){
                  if(!jQuery.xkeyvalidate('#ClientProfitMargin',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientProfitMargin').val())){
                     jQuery('#ClientProfitMargin').addClass('invalid');
                     jGrowl_to_notyfy('Min. Profitability must contain numeric characters only',{theme:'jmsg-error'});
                     te=false;
                                                 }
  										}
            		
									if(/\D/.test(jQuery('#ClientTaxId').val())){
										jQuery('#ClientTaxId').addClass('invalid');
					        jGrowl_to_notyfy('Tax must nuber',{theme:'jmsg-error'});
					       te=false;
									}*/
                              
               return te;
                           });  
         });
</script> 
  <script type="text/javascript">
<!--
/*ClientPaymentTermId,#ClientInvoiceFormat,#ClientAttachCdrsList,#ClientCdrListFormat,#ClientLastInvoiced
   jQuery(document).ready(function(){
        jQuery('#ClientAutoInvoicing').click(function(){
            if(!jQuery('#ClientAutoInvoicing').attr('checked')){
               jQuery('#ClientPaymentTermId,#ClientInvoiceFormat,#ClientCdrListFormat,#ClientLastInvoiced,#ClientAttachCdrsList').attr('disabled',true);
               jQuery('#ClientLastInvoiced,#ClientAttachCdrsList,#ClientIsLinkCdr,#ClientInvoiceShowDetails').attr('disabled',true);
           }else{
               jQuery('#ClientPaymentTermId,#ClientInvoiceFormat,#ClientCdrListFormat,#ClientLastInvoiced,#ClientAttachCdrsList').attr('disabled',false);
               jQuery('#ClientLastInvoiced,#ClientAttachCdrsList,#ClientIsLinkCdr,#ClientInvoiceShowDetails').attr('disabled',false);
                              }
                      });
        });*/
//-->
</script> 
</div>
<?php endif;?>
