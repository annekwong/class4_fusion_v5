<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier',true);?>[<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Ingress Trunk',true);?><?php echo  $this->element('title_name',array('name'=>$name));?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('DisconnectCode'); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('DisconnectCode') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("ingress_tab",array('active_tab'=>'dis_code'))?>
        </div>
        <div class="widget-body">
  <fieldset style="width:100%;">
    <form id="form" method="post">
      <?php echo  $appGetewaygroup->echo_resource_hidden($resource_id,$gress);?>
      <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="width:100%" >
          <thead>
              <tr>
            <th style="width:30%;  font-weight:bold;"><?php echo __('Description',true);?></th>
            <th class="value" style="width:400px;font-weight:bold;"> <?php echo __('Response',true);?> </th>
            <th class="value" style="width:500px;font-weight:bold;"> <?php echo __('Code',true);?> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; <?php echo __('Default code',true);?> </th>
            <th class="value" style="width:400px;font-weight:bold;"> <?php echo __('Default Response',true);?> </th>
          </tr>
          </thead>
        <tbody>
          
          <tr>
            <td style="width:30%"><?php echo __('Invalid Argument',true);?></td>
            <td class="value" style="width:400px"> <?php echo __('Not Found',true);?> <?php echo $form->input('CALL_ARGS',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$CALL_ARGS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('CALL_ARGS_code',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$CALL_ARGS_code))?>404 </td>
            <td class="value" style="width:400px"> <?php echo __('Not Found',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('System Limit CAP Exceeded',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> <?php echo $form->input('SYSTEM_CAP',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$SYSTEM_CAP))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('SYSTEM_CAP_code',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$SYSTEM_CAP_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('System Limit CPS Exceeded',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> <?php echo $form->input('SYSTEM_CPS',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$SYSTEM_CPS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('SYSTEM_CPS_code',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$SYSTEM_CPS_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Unauthorized IP Address',true);?></td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> <?php echo $form->input('INGRESS_IP_CHECK',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_IP_CHECK))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_IP_CHECK_code',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_IP_CHECK_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('No Ingress Resource Found',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> <?php echo $form->input('INGRESS_RESOURCE',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RESOURCE))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RESOURCE_code',Array('type'=>'hidden','readonly'=>'true','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RESOURCE_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('No Product Found',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('PRODUCT_CHECK',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$PRODUCT_CHECK))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('PRODUCT_CHECK_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$PRODUCT_CHECK_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"> <?php echo __('Trunk Limit CAP Exceeded',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_CAP',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_CAP))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_CAP_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_CAP_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Trunk Limit CPS Exceeded',true);?>  </td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_CPS',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_CPS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_CPS_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_CPS_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('IP Limit CAP Exceeded',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_IP_CAP',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_IP_CAP))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_IP_CAP_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_IP_CAP_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"> <?php echo __('IP Limit CPS Exceeded',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_IP_CPS',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_IP_CPS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('IN_RESORUCE_IP_CPS_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$IN_RESORUCE_IP_CPS_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Invalid Codec Negotiation',true);?>  </td>
            <td class="value" style="width:400px"><?php echo $form->input('RESOURCE_CODEC',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$RESOURCE_CODEC))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('RESOURCE_CODEC_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$RESOURCE_CODEC_code))?>415 </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Block due to LRN',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_LRN_BLOCK',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_LRN_BLOCK))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_LRN_BLOCK_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_LRN_BLOCK_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Ingress Rate Not Found',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RATE',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RATE))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RATE_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RATE_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Egress Trunk Not Found',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('EGRESS_NOT_FOUND',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_NOT_FOUND))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('EGRESS_NOT_FOUND_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_NOT_FOUND_code))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <!--
					
					

					
					
										<tr>
						<td style="width:30%">From egress response 404
						</td>
								<td class="value" style="text-align:center;width:50px">
		
						</td>
						<td style="width:25px;">  </td>
						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE404',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE404))?>
						</td>
							  			<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE404_code',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE404_code))?>404
						</td>
						<td class="value" style="width:400px">
					<?php echo __('Not Found',true);?>
					
						</td>
					</tr>
					
										<tr>
						<td style="width:30%">From egress response 486
						</td>
								<td class="value" style="text-align:center;width:50px">
		
						</td>
						<td style="width:25px;">  </td>
						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE486',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE486))?>
					
						</td>
							  
							  		<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE486_code',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE486_code))?>486 
					
						</td>
						<td class="value" style="width:400px">
					Busy Here
					
						</td>
					</tr>
					
					
										<tr>
						<td style="width:30%"> From egress response 487
						</td>
								<td class="value" style="text-align:center;width:50px">
		
						</td>
						<td style="width:25px;">  </td>
						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE487',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE487))?>
					
						</td>
							  						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE487_code',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE487_code))?>503
					
						</td>
						<td class="value" style="width:400px">
					<?php echo __('Service Unavailable',true);?>
					
						</td>
						
					</tr>
					
										--><!--
										
										
										
										
						<tr>
						<td style="width:30%">From egress response 200
						</td>
								<td class="value" style="text-align:center;width:50px">
		
						</td>
						<td style="width:25px;">  </td>
						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE200',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE200))?>
					
						</td>
							 						<td class="value" style="width:400px">
							<?php echo $form->input('EGRESS_RESPONSE200_code',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$EGRESS_RESPONSE200_code))?>200
					
						</td> 
						<td class="value" style="width:400px">
						OK
						</td>
					</tr>
					
					
										-->
          <tr>
            <td style="width:30%"><?php echo __('All egress not available',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('ALL_EGRESS_FAILED',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$ALL_EGRESS_FAILED))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('ALL_EGRESS_FAILED_code',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$ALL_EGRESS_FAILED_code))?>503 </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Ingress Resource disabled',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RESOURCE_DISABLED',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RESOURCE_DISABLED))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RESOURCE_DISABLED_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RESOURCE_DISABLED_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Balance Use Up',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('BALANCE_USE_UP',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$BALANCE_USE_UP))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('BALANCE_USE_UP_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$BALANCE_USE_UP_CODE))?>402 </td>
            <td class="value" style="width:400px"> <?php echo __('Paymen Reqired',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('No Routing Plan Route',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('NO_ROUTING_PLAN_ROUTE',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NO_ROUTING_PLAN_ROUTE))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('NO_ROUTING_PLAN_ROUTE_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NO_ROUTING_PLAN_ROUTE_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('No Routing Plan Prefix',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('NO_ROUTING_PLAN_PREFIX',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NO_ROUTING_PLAN_PREFIX))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('NO_ROUTING_PLAN_PREFIX_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NO_ROUTING_PLAN_PREFIX_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Ingress Rate No configure',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RATE_NO_CONFIGURE',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RATE_NO_CONFIGURE))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('INGRESS_RATE_NO_CONFIGURE_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$INGRESS_RATE_NO_CONFIGURE_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Termination Invalid Codec Negotiation',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Termination_Invalid_Codec_Negotiation',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Termination_Invalid_Codec_Negotiation))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Termination_Invalid_Codec_Negotiation_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Termination_Invalid_Codec_Negotiation_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('No Codec Found',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('No_Codec_Found',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$No_Codec_Found))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('No_Codec_Found_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$No_Codec_Found_CODE))?><?php echo __('[415]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('All egress no confirmed',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('All_egress_no_confirmed',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$All_egress_no_confirmed))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('All_egress_no_confirmed_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$All_egress_no_confirmed_CODE))?><?php echo __('[503]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('LRN response no exist DNIS',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('LRN_response_no_exist_DNIS',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$LRN_response_no_exist_DNIS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('LRN_response_no_exist_DNIS_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$LRN_response_no_exist_DNIS_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Carrier CAP Limit Exceeded',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Carrier_CAP_Limit_Exceeded',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Carrier_CAP_Limit_Exceeded))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Carrier_CAP_Limit_Exceeded_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Carrier_CAP_Limit_Exceeded_CODE))?><?php echo __('[503]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Carrier CPS Limit Exceeded',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Carrier_CPS_Limit_Exceeded',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Carrier_CPS_Limit_Exceeded))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Carrier_CPS_Limit_Exceeded_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Carrier_CPS_Limit_Exceeded_CODE))?><?php echo __('[503]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Host Alert Reject',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Host_Alert_Reject',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Host_Alert_Reject))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Host_Alert_Reject_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Host_Alert_Reject_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Resource Alert Reject',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Resource_Alert_Reject',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Resource_Alert_Reject))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Resource_Alert_Reject_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Resource_Alert_Reject_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('Resource Reject H323',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Resource_Reject_H323',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Resource_Reject_H323))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Resource_Reject_H323_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Resource_Reject_H323_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('180 Negotiation SDP Failed',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('I180_Negotiation_SDP_Failed',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I180_Negotiation_SDP_Failed))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('I180_Negotiation_SDP_Failed_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I180_Negotiation_SDP_Failed_CODE))?><?php echo __('[415]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('183 Negotiation SDP Failed',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('I183_Negotiation_SDP_Failed',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I183_Negotiation_SDP_Failed))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('I183_Negotiation_SDP_Failed_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I183_Negotiation_SDP_Failed_CODE))?><?php echo __('[415]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('200 Negotiation SDP Failed',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('I200_Negotiation_SDP_Failed',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I200_Negotiation_SDP_Failed))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('I200_Negotiation_SDP_Failed_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$I200_Negotiation_SDP_Failed_CODE))?><?php echo __('[415]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Unsupported Media Type',true);?> </td>
          </tr>
          <tr>
            <td style="width:30%"><?php echo __('LRN Block Higher Rate',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('LRN_Block_Higher_Rate',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$LRN_Block_Higher_Rate))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('LRN_Block_Higher_Rate_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$LRN_Block_Higher_Rate_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Trunk Block ANI',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_ANI',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_ANI))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_ANI_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_ANI_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Trunk Block DNIS',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_DNIS',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_DNIS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_DNIS_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_DNIS_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Trunk Block ALL',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_ALL',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_ALL))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Trunk_Block_ALL_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Trunk_Block_ALL_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Block ANI',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_ANI',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_ANI))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_ANI_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_ANI_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Block DNIS',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_DNIS',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_DNIS))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_DNIS_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_DNIS_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          
          <tr>
            <td style="width:30%"><?php echo __('Block ALL',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_ALL',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_ALL))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('Block_ALL_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$Block_ALL_CODE))?><?php echo __('[403]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Forbidden',true);?> </td>
          </tr>
          <!--
          <tr>
            <td style="width:30%"><?php echo __('T38 Reject',true);?> </td>
            <td class="value" style="width:400px"><?php echo $form->input('T38_Reject',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$T38_Reject))?></td>
            <td class="value" style="width:400px"><?php echo $form->input('T38_Reject_CODE',Array('class'=>'validate[custom[onlyNumberSp]]','div'=>false,'maxLength'=>32,'label'=>false,'value'=>$T38_Reject_CODE))?><?php echo __('[503]',true);?> </td>
            <td class="value" style="width:400px"> <?php echo __('Service Unavailable',true);?> </td>
          </tr>
          -->
          
          <!--
										<tr>
						<td style="width:30%">Normal 
						</td>
								<td class="value" style="text-align:center;width:50px">
		
						</td>
						<td style="width:25px;">  </td>
						<td class="value" style="width:400px">
							<?php echo $form->input('NORMAL',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NORMAL))?>
					
						</td>
							  				<td class="value" style="width:400px">
							<?php echo $form->input('NORMAL_code',Array('div'=>false,'maxLength'=>32,'label'=>false,'value'=>$NORMAL_code))?>200
					
						</td>
						<td class="value" style="width:400px">
						OK
					
						</td>
						<td><input type="submit" id="sub" value="<?php echo __('submit')?>"  class="input in-submit"></td>
					</tr>

				-->
        </tbody>
      </table>
    </form>
  </fieldset>
  <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
  <div id="form_footer" class="buttons-group center">
    <input type="submit" id="sub" value="<?php echo __('submit')?>"  class="input in-submit btn btn-primary">
  </div>
  <?php }?>
</div> 
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(
		function()
		{
			jQuery('#sub').click(
				function(){
					
					jQuery('#form').submit();
				}
			);
			jQuery('input[maxLength=3]').xkeyvalidate({type:'Num'});
		}
);
</script> 

