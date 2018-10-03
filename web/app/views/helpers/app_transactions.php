<?php 
class AppTransactionsHelper extends AppHelper {
	var $options_user=Array();
	function options_user($user){
		if(empty($this->options_user))
		{
			foreach($user as $u)
			{
				$this->options_user[$u['User']['user_id']]=$u['User']['name'];
			}
		}
		return $this->options_user;
	}
	function payment_method($type,$user=Array())
	{
		if(empty($this->options_user))
		{
			$this->options_user($user);
		}
		return array_keys_value($this->options_user,$type,'admin'); 
	}
}
?>
