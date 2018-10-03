<div id="daily-metrics-title" class="metrics-title">
			<span class="monthly_title"><?php echo __('Report Metrics',true);?> </span>	        	   
	</div>
	<?php echo $this->element('metric',array('rel' => 'call_duration' , 'title' =>'Duration','value' => array_keys_value($statistics,'call_duration','-'),'percent' => '1'))?>
	<?php echo $this->element('metric',array('rel' => 'asr' , 'title' =>'Call Count','value' => array_keys_value($statistics,'asr','-'),'percent' => '1'))?>
	<?php echo $this->element('metric',array('rel' => 'acd' , 'title' =>'Revenue','value' => array_keys_value($statistics,'acd','-'),'percent' => '1'))?>
	<?php echo $this->element('metric',array('rel' => 'call_attempt' , 'title' =>'Total Cost','value' => array_keys_value($statistics,'call_attempt','-'),'percent' => '1'))?>
	<?php echo $this->element('metric',array('rel' => 'success_call' , 'title' =>'Profit','value' => array_keys_value($statistics,'success_call','-'),'percent' => '1'))?>
	<?php echo $this->element('metric',array('rel' => 'failed_call' , 'title' =>'Profit %','value' => array_keys_value($statistics,'failed_call','-'),'percent' => '1'))?>
