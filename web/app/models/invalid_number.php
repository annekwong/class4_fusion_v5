<?php
class InvalidNumber extends AppModel{
	var $name = 'InvalidNumber';
	var $useTable = 'invalid_number_detection';
	var $primaryKey = 'id';

	var $check_cycle = array(
		5 => 5,10=>10,15=>15,30=>30,60=>60
	);
	var $return_codes = array(
		401 => 401,402=>402,403=>403,404=>404,503=>503,603=>603
	);

	var $mailTemplateTags = array(
		'rule_name','switch_alias','client_name','ingress_name',
		'invalid_number_detected'
	);
}
?>