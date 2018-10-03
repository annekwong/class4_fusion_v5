	<?php
$login_type = $session->read('login_type');
	if($login_type==3){
	  		$project_name=Configure::read('project_name');
	  		if($project_name=='wholesales'){
	  	  			if(isset($_SESSION['carrier_panel'])){
								$post=$_SESSION['carrier_panel']['Client'];
	?>
	
	
	<ul id="topmenu-menu" class="topmenu-left">
	<li><span><?php  __('Finance')?></span>
	
	
		
	<?php if(!empty($post['is_client_info'])||!empty($post['is_mutualsettlements'])||!empty($post['is_invoices'])||!empty($post['is_changepassword'])||
	!empty($post['is_rateslist'])){?>
	<ul style="display: none;">
		<?php if(!empty($post['is_client_info'])){?>
		<li><a href="<?php echo $this->webroot?>clients/view/"><?php __('Carriers')?></a></li>
		<?php }?>
	
		<?php if(!empty($post['is_mutualsettlements'])){?>
		<li><a  href="<?php echo $this->webroot?>clientmutualsettlements/summary_reports">	<?php __('MutualSettlements')?></a></li>
		<?php }?>
		<?php if(!empty($post['is_invoices'])){?>
		<li><a  href="<?php echo $this->webroot?>pr/pr_invoices/view">	<?php __('Invoices')?></a></li>
		<?php }?>

		
				<?php if(!empty($post['is_changepassword'])){?>
			<li><a href="<?php echo $this->webroot?>users/changepassword"> <?php __('ChangePassword')?> </a></li>
			<?php }?>
			
			
			<?php if(!empty($post['is_rateslist'])){?>
			<li><a href="<?php echo $this->webroot?>clientrates/view_carrier_rate"> <?php __('RateTable')?> </a></li>
			<?php }?>
	</ul>
	
	<?php }?>
	</li>
	<li>
		<span><?php __('Statistics')?></span>
		
			<?php if(!empty($post['is_spam'])||!empty($post['is_location'])||!empty($post['is_orig_term'])||!empty($post['is_summaryreport'])||!empty($post['is_usage'])
	||$post['is_cdrslist']){?>
		<ul style="display: none;">
		<?php if(!empty($post['is_spam'])){?><li><a href="<?php echo $this->webroot?>cdrreports/summary_reports/spam/"><?php __('Spam Report')?></a></li><?php }?>
		<?php if(!empty($post['is_location'])){?><li><a	href="<?php echo $this->webroot?>Locationreports/summary_reports"><?php __('LocationReport')?></a></li><?php }?>
		<?php if(!empty($post['is_orig_term'])){?><li><a href="<?php echo $this->webroot?>origtermstatis/summary_reports"><?php __('Orig-TermReport')?></a></li><?php }?>
		<?php if(!empty($post['is_summaryreport'])){?><li><a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports"><?php  __('SummaryReport')?></a></li><?php }?>
		<?php if(!empty($post['is_usage'])){?><li><a href="<?php echo $this->webroot?>ratereports/summary_reports"><?php __('UsageReport')?></a></li><?php }?>
		<?php if(!empty($post['is_cdrslist'])){?><li><a href="<?php echo $this->webroot?>cdrreports/summary_reports">	<?php __('CDRsList')?></a></li><?php }?>
		<?php if(!empty($post['is_qos'])){?><li><a href="<?php echo $this->webroot?>carrierqosreports/carrier/ingress"><?php __('QoSReport')?></a></li><?php }?>
		<?php if(!empty($post['is_discon'])){?><li><a href="<?php echo $this->webroot?>disconnectreports/summary_reports"><?php __('DisconnectCauses')?></a></li><?php }?>
		<?php if(!empty($post['is_bill_mismatch'])){?><li><a href="<?php echo $this->webroot?>mismatchesreports/mismatches_report"><?php __('MismatchesReport')?></a></li><?php }?>
		<?php if(!empty($post['is_active_call'])){?><li><a href="<?php echo $this->webroot?>realcdrreports/summary_reports"><?php __('Active Call Report')?></a></li><?php }?>
		<?php if(!empty($post['is_termin'])){?><li><a href="<?php echo $this->webroot?>gatewaygroups/egress_report"><?php __('TerminationReport')?>	</a></li><?php }?>
		</ul>
		
		<?php }?>
	</li>

			<?php if(!empty($post['is_rate_anal'])||!empty($post['is_capture'])||!empty($post['is_call_simulation'])||!empty($post['is_ingress_sim'])||!empty($post['is_egress_sim'])
	){?>

	<li><span><?php	echo __ ('Tools')?></span>
		<ul style="display: none;">
			<?php if(!empty($post['is_rate_anal'])){?><li><a	href="<?php	echo $this->webroot?>rateanalyzes/rate_analyzes">	<?php		__ ( 'RatesAnalysis' )?>				</a></li><?php }?>
			<?php if(!empty($post['is_capture'])){?><li><a href="<?php	echo $this->webroot?>sipcaptures/sip_capture">	<?php		__ ( 'SIPCapture' )?>						</a></li><?php }?>
			<?php if(!empty($post['is_call_simulation'])){?><li><a	href="<?php		echo $this->webroot?>simulatedcalls/simulated_call">	<?php		__ ( 'CallSimulation' )?>	</a></li><?php }?>
			<?php if(!empty($post['is_ingress_sim'])){?><li><a href="<?php	echo $this->webroot?>testdevices/test_device"><?php		__ ( 'IngressTrunkSimulation' )?></a></li><?php }?>
			<?php if(!empty($post['is_egress_sim'])){?><li><a href="<?php	echo $this->webroot?>audiotests/audio_test"><?php	__ ( 'EgressTrunkSimulation' )?></a></li><?php }?>
		</ul>
	</li>
	<?php }?>
	
	<?php  if(!empty($post['is_route'])){?>
		<li><span><?php __('Routing')?></span>
		<ul style="display: none;">
			<li><a href="<?php echo $this->webroot?>digits/view"><?php __('DigitTranslation')?>	</a></li>
			<?php if($project_name=='exchange'){?>
				<li><a href="<?php echo $this->webroot?>gatewaygroups/view_egress"><?php __('Trunks')?> </a></li>
			<?php }else{?>
				<li><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress"><?php __('Trunks')?> </a></li>
			<?php }?>
			<li><a href="<?php echo $this->webroot?>dynamicroutes/view"><?php __('DynamicRoute')?> </a></li>
			<li><a href="<?php echo $this->webroot?>products/product_list"> <?php __('StaticRoute')?></a></li>
			<li><a href="<?php echo $this->webroot?>blocklists/index"> <?php __('BlockList')?> </a></li>
			<li><a href="<?php echo $this->webroot?>routestrategys/strategy_list"><?php __('RoutingStrategies')?></a></li>
		</ul>
	</li>

	<?php 
	}
		
	
					}
	  		}
	  		
			}
	?>