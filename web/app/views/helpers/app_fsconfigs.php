<?php 
class AppFsconfigsHelper extends AppHelper {
	var $options_configs_type=Array();
	var $options_configs_text_type=Array();	
	function options_configs_type($lists)
	{
		foreach($lists as $list)
		{
	//		$this->options_configs_type[$list['Fsconfig']['type']]=$list['Fsconfig']['return_code'];
		}
		return $this->options_configs_type;
	}
	function options_configs_text_type($lists)
	{
		foreach($lists as $list)
		{
			//$this->options_configs_text_type[$list['Fsconfig']['type']]=$list['Fsconfig']['return_text'];
		}
		return $this->options_configs_text_type;
	}
}
?>
