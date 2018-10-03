	<?php  $login_type = $_SESSION['login_type'];
	
	if($login_type==3){
	  		$project_name=Configure::read('project_name');
	  		if($project_name=='exchange'){
	  			if(isset($_SESSION['carrier_panel'])){
						$post=$_SESSION['carrier_panel'];
						
	?>
	
	<ul id="topmenu-menu" class="topmenu-left">
		<li><span><?php echo __('Buy',true);?></span>
			<ul style="display: none;">
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'sell'))?>"><?php __('Select Country')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_places",'action' => 'buy'))?>"><?php __('Place Buy Order')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_manages",'action' => 'buy'))?>"><?php __('Manage Buy Order')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_contracts",'action' => 'buy'))?>"><?php __('Confirmed Buy Order')?></a></li>
			</ul>
	</li>
	<li><span><?php echo __('Sell',true);?></span>
			<ul style="display: none;">
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'buy'))?>"><?php __('Select Country')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_places",'action' => 'sell'))?>"><?php __('Place Sell Order')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_manages",'action' => 'sell'))?>"><?php __('Manage Sell Order')?></a></li>
				<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_contracts",'action' => 'sell'))?>"><?php __('Confirmed Sell Order')?></a></li>
			</ul>
	</li>
	
	
	
	<li><span><?php __('Support')?></span>
			<ul style="display: none;">
				<li><a href="<?php echo $this->webroot?>clientcdrreports/credit_balance/"> <?php echo __('Credit Balance',true);?> </a></li>
				<li><a href="<?php echo $this->webroot?>invoices/view">	<?php __('Invoices')?></a></li>
				<li><a href="<?php echo $this->webroot?>users/changepassword"> <?php __('ChangePassword')?> </a></li>
				<li><a href="<?php echo $this->webroot?>clientcdrreports/summary_reports"> <?php echo __('Report',true);?> </a></li>
			</ul>
	</li><!--
	
	<li><span><?php __('Profiles')?></span>
			<ul style="display: none;">
				<li><a href="<?php echo $this->webroot; ?>protype/profiles"> Profile </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/profiles/country"> Country Profile </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/profiles/code_name"> Code Name Profile </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/profiles/code"> Code Profile </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/profiles/compare"> Compare Profile </a></li>
			</ul>
</li>
	
	<li><span><?php __('Analytics Tools')?></span>
			<ul style="display: none;">
				<li><a href="<?php echo $this->webroot; ?>protype/analytics/deal"> Deal Analytics </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/analytics/traffic"> Traffic Analytics </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/analytics/termination"> Termination Analytic </a></li>
				<li><a href="<?php echo $this->webroot; ?>protype/analytics/rank"> Ranked Destination </a></li>
			</ul>
	</li>
	--></ul>			
	<?php 
		
	
	
					}
	  		}
	}
	?>