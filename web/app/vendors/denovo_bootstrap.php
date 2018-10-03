<?php 



	function  join_str($separator,$arr){
	$t=array();
	foreach ($arr   as $key=>$value){
	$t[]="'".$value."'";
	
	}

	return join($separator,$t);
	}
	

	function filter_empty_value($arr){
	
		$arr2=array();
		foreach ($arr as  $key=>$value){
		
			if(!empty($value))
			{
				$arr2[]=$value;
			}
			else
			{
				continue;
				
			}
			
			
		}
		
	return $arr2;	
	}

/**
	 * -------------------------------------------------------------------------------------------
	 * PHP����֤��
	 * ---------------------------------------------------------------------------------
	 */
	/*
	    -----------------------------------------------------------
	    �������ƣ�isNumber
	    ��Ҫ���������������Ƿ�Ϊ����
	    ���룺string
	    �����boolean
	    �޸���־��------
	    -----------------------------------------------------------
	    */
	function exchange_isNumber($val) {
		if (ereg ( "^[0-9]+$", $val )) {
			return true;
		} else {
			return false;
		}
	
	}
	
	/*
	    -----------------------------------------------------------
	    �������ƣ�isDate
	    ��Ҫ��������������Ƿ����0000-00-00
	    ���룺string
	    �����boolean
	    �޸���־��------
	    -----------------------------------------------------------
	    */
	function exchange_isDate($sDate) {
		if (ereg ( "^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$", $sDate )) {
			return true;
		} else {
			Return false;
		}
	}
	
	/*
	    -----------------------------------------------------------
	    �������ƣ�isTime
	    ��Ҫ��������������Ƿ����0000-00-00 00:00:00
	    ���룺string
	    �����boolean
	    �޸���־��------
	    -----------------------------------------------------------
	    */
	function exchange_is_date_Time($sTime) {
		if (ereg ( "/^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $sTime )) {
			Return true;
		} else {
			Return false;
		}
	}
	
	
	
	
	
	
	/*
	    -----------------------------------------------------------
	    �������ƣ�isTime
	    ��Ҫ��������������Ƿ����0000-00-00 00:00:00
	    ���룺string
	    �����boolean
	    �޸���־��------
	    -----------------------------------------------------------
	    */
	function exchange_isTime($sTime) {
			pr('oooooooooooooooooo');
		pr($sTime);
		if (ereg ("/.*[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $sTime )) {
				pr('rrrrrrrrrrrrrrrrr');
			Return true;
		} else {
			pr('error');
			Return false;
		}
	}
	
	
	
	
	/*
	    -----------------------------------------------------------
	    ��������:isIp($val)
	    ��Ҫ����:�������IP�Ƿ����Ҫ��
	    ����:string
	    ���:boolean
	    �޸���־:------
	    -----------------------------------------------------------
	    */
	function exchange_isIp($val) {
		return ( bool ) ip2long ( $val );
	}
	
	/*
	    -----------------------------------------------------------
	    �������ƣ�isName
	    ��Ҫ�����������ǳƺϷ��Լ�飬ֻ����������Ӣ��
	    ���룺string
	    �����boolean
	    �޸���־��------
	    -----------------------------------------------------------
	    */
	function exchange_isName($val) {
		if (preg_match ( "/^[\x80-\xffa-zA-Z0-9]{3,60}$/", $val )) //2008-7-24
		{
			return true;
		}
		return false;
	} //end func	



function local_time_to_gmt($time){
	return date("Y-m-d H:i:s", strtotime($time) - date("Z")). " +00";  
}
function isNFalse($var){
	if($var===false){
		return false;
	}
	return true;
}


function gmt_to_local_time($time,$tz){
	$arr=array(
	 	'-1200'=>'Pacific/Kwajalein', 
		'-1100'=>'Pacific/Samoa',  
	 	'-1000'=>'Pacific/Honolulu',  
	 	'-0900'=>'America/Juneau',  
	 	'-0800'=>'Etc/GMT+8',  
	 	'-0700'=>'America/Dawson_Creek',  
	 	'-0600'=>'America/Belize',  
		'-0500'=>'America/Bogota',  
	 	'-0400'=>'America/La_Paz',  
		'-0300'=>'America/Argentina/Buenos_Aires',  
		'-0200'=>'America/Noronha',// no cities here so just picking an hour ahead  
	 	'-0100'=>'Atlantic/Cape_Verde',
	 	'+0000'=>'Africa/Abidjan',  
	  '+0100'=>'Africa/Windhoek',  
	 	'+0200'=>'Africa/Blantyre',  
	  '+0300'=>'Africa/Addis_Ababa',  
	 	'+0400'=>'Etc/GMT-4',  
	 	'+0500'=>'Asia/Karachi',  
		'+0600'=>'Etc/GMT-6',   
	  '+0700'=>'Asia/Bangkok',  
	 '+0800'=>'Asia/Singapore',  
	 '+0900'=>'Asia/Tokyo',  
	 '+1000'=>'Pacific/Guam',  
	 '+1100'=>'Etc/GMT-11',  
	 '+1200'=>'Etc/GMT-12'  
	
	
	);
	if(isset($arr[$tz])){
		date_default_timezone_set($arr[$tz]);
	}else{
	date_default_timezone_set('Europe/London');
	}

	return 	date('Y-m-d H:i:s',strtotime($time));
}
function array_keys_exists($array,$keys){
	$t =  $array;
	if(!isset($t)||!is_array($t))
	{
		return false;
	}
	if(!is_array($keys)){
		$keys = explode('.', (string)$keys);
	}
	foreach ($keys as $key){
		if(array_key_exists($key,$t)){
			$t = $t[$key];
		}else{
			return false;
		}
	}
	return true;
}
function array_keys_value($array,$keys,$default = null){
	$t =  $array;
	if(!is_array($keys)){
		$keys = explode('.', (string)$keys);
	}
	foreach ($keys as $key){
		if(!isset($t) || empty($t) || !is_array($t)){
			return $default;
		}
		if(array_key_exists($key,$t)){
			$t = $t[$key];
		}else{
			return $default;
		}
	}
	
	return $t;
}
function array_keys_value_empty($array,$keys,$default = null){
	$temp=array_keys_value($array,$keys);
	if(empty($temp))
	{
		return $default;
	}
	return $temp;
}

function gmtnow($offset=0){
	return gmdate("Y-m-d H:i:s",time()+$offset);
}

function is_blank($val){   
	if(is_string($val)){   
		preg_match('/\S/',$val,$matches);
		if(empty($matches)){
			return true;   
		}else{               
			return false;
		}                   
	}
	return  empty($val) ;  
}
function is_ip($val){
	$ip="/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/";
	return preg_match($ip,$val);
}
function timetounix($time,$options=Array())
{
	$t=split(' ',$time);
	$date=split('-',$t[0]);
	$time=split(':',$t[1]);
	return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
}
function xempty($val){
	$val=$val.' ';
	return empty($val);
}
function defValue($val,$default=''){
	if(empty($val)){
		return $default;
	}
	return $val;
}
function _filter_array($arr,$val,$default=null){
	if(!is_array($arr)){
		$arr = explode('.', (string)$arr);
	}
	$filter_arr=Array();
	foreach($arr as $key=>$value){
		if($key===null){
			$key=$value;
		}
		$filter_arr[$key]=$value;
	}
	return array_keys_value($filter_arr,$val,$default);
}
function enumerate_return($enumerate,$re1,$re2=''){
	if($enumerate){
		return $re1;
	}else{
		return $re2;
	}
}

