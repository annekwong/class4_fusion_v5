<?php 
APP::import('Model','User');
class AppUsersHelper extends AppHelper {
	var $options_user_type=Array(
		User::USER_USER_TYPE_ADMINISTRATOR=>'Administrator',
		User::USER_USER_TYPE_AGENTS=>'Agents',
		User::USER_USER_TYPE_CARRIER=>'Carrier',
		User::USER_USER_TYPE_CARDS=>'Cards',
		User::USER_USER_TYPE_EXPERIENCE=>'Experience'
	);	
	function user_type($list)
	{
		return array_keys_value($this->options_user_type,array_keys_value($list,'User.user_type',0));
	}
}
?>
