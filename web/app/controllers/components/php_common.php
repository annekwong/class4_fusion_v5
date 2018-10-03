<?php
class PhpCommonComponent {
	var $message=Array();
	function format_ip($ip,$default = null){
		$ip = preg_replace('/[^\d\.]+/i','',$ip);
		$ips = array();
		foreach(explode(".",$ip) as $item){
			$ips[] = (int)$item;
		}
		$data = join('.',$ips);
		return ($data === '0') ? $default : $data;			
	}
	
	function is_ip($ip){
		$ip = (string)$this->format_ip($ip);
		preg_match('/^(?:\d{1,3}\.){3}\d{1,3}$/',$ip,$matches);
		if(empty($matches))
			return false;
		foreach(explode(".",$ip) as $item){
			$item = (int)$item;
			if($item > 255)
				return false;
		}
		return true;			
	}
	
	function format_mac($mac){
		return strtoupper(preg_replace('/[^\d\:a-f]+/i','',$mac));
	}
	
	function is_mac($mac){
//		00:12:11:11:11:11
		$mac = $this->format_mac($mac);
		preg_match("/^(?:[0-9A-F]{2}:){5}[0-9A-F]{2}$/i",$mac,$matches);
		return !empty($matches);
	}
	
	function format_port($port,$default = null){
		$port = preg_replace('/[^\d\-]+/i','',$port);
		$ports = explode('-',(string)$port);	
		if(count($ports)>2)
			$ports = array_slice($ports,0,2);
		if(count($ports)==2){
			$ports[0] = (int)$ports[0];
			$ports[1] = (int)$ports[1];
			if($ports[0] > $ports[1]){
				$t = $ports[0];
				$ports[0] = $ports[1];
				$ports[1] = $t;
			}
		}
		if(count($ports)== 1){
			$ports[0] = (int)$ports[0];
		}
		if(empty($ports)){
			return $default;
		}else{
			return join('-',$ports);
		}
	}
	
	function is_port($port){		
		$port = (string)$this->format_port($port);
		preg_match('/^(?:[0-9]+(?:\-[0-9]+)?)$/i',$port,$matches);
		return !empty($matches);
	}
	
		
	function is_blank($val){
		if(is_string($val)){
			preg_match('/\S/',$val,$matches);
			if(empty($matches))
				return true;
			else
     return false;				
		}
		return  empty($val) ;
	}
	
	function is_number_in_range($number,$number_min,$number_max){
		$number = (int)$number; 
		$number_min = (int)$number_min;
		$number_max = (int)$number_max;
		if($number_min > $number_max){
			$t = $number_min;
			$number_min = $number_max;
			$number_max = $t;
		}
		return !($number < $number_min || $number > $number_max);
	}
	function validate($fun,$value,$options=Array())
	{
		$def_options=Array('key'=>null,'message'=>$value.'不符合规范！');
		$options=array_merge($def_options,$options);
		$result=$this->$fun($value);
		if(!$result)
		{
			if(!empty($options['key']))
			{
				$this->message[$options['key']]=$options['message'];
			}
			$this->message['num'][]=$options['message'];
		}
		return $result;
	}	
	function hasMessage()
	{
		if(!empty($this->message))
		{
			return true;
		}
		return false;
	
	}
	function getMessage()
	{
		return $this->message;
	}
	function getMessageCount()
	{
		return count($this->message);
	}
}