<?php
class PhpCommonComponent {

	function format_ip($ip){
		$ip = preg_replace('/[^\d\.]+/i','',$ip);
		$ips = array();
		foreach(explode(".",$ip) as $item){
			$ips[] = (int)$item;
		}
		$data = join('.',$ips);
		return ($data === '0') ? '' : $data;			
	}
	
	function is_ip($ip){
		$ip = $this->format_ip($ip);
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
	
	function format_port($port){
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
		if(count($ports)== 0){
			$ports[0] = (int)$ports[0];
		}
		return join('-',$ports);
	}
	
	function is_port($port){		
		$port = $this->format_port($port);
		preg_match('/^(?:[0-9]+(?:\-[0-9]+)?)$/i',$port,$matches);
		return !empty($matches);
	}
	
	function format_username($username){
		return preg_replace('/[\s]+/','',$username);	
	}
	
	function is_username($username){
		preg_match('/\s/',$username,$matches);
		return empty($matches) ? true : false;
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
	
	function get_form_int($params,$key,$opt=array()){
		if(!isset($params[$key]) || $this->is_blank($params[$key])){
			if(isset($opt['default'])){
				return $opt['default'];				
			}else{
				return null;
			}
		}	
		preg_match('/[^\d\.\+\-]/',$params [$key],$m);		
		if(!empty($m))
		{
			return null;
		}
			$number = ( int ) $params [$key];
			// 如果定了 validate_callback 就执行这个callback 如果为否 表示验证失败	
			// 如果有 validate_callback 一定要传 this , this 当前用的class中的this			
			//例子 array('this' => &$this, 'validate_callback' => "_is_valid_mtu"
			if(isset($opt['validate_callback']) && isset($opt['this']) && !call_user_method_array($opt['validate_callback'],$opt['this'],array($opt['this'],$number))) 			
				return null;
			return $number;		
	}
	
	function get_form_port($params,$key,$opt=array()){
		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
		 	if(isset($opt['default'])){
				return $opt['default'];
			}else{
				return null;
			}
		}		
		$string = $this->format_port($params[$key]);
		if($this->is_port($string)){
			if(isset($opt['validate_callback']) && isset($opt['this']) && !call_user_method_array($opt['validate_callback'],$opt['this'],array($opt['this'],$string))) 			
				return null;
			return $string;
		}else{
			return null;
		}	
	}
	
	function get_form_string($params,$key,$opt=array()){
		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
		 	if(isset($opt['default'])){
				return $opt['default'];
			}else{
				return null;
			}
		}		
		$string = trim($params[$key]);
		if(!$this->is_blank($string)){
			if(isset($opt['validate_callback']) && isset($opt['this']) && !call_user_method_array($opt['validate_callback'],$opt['this'],array($opt['this'],$string))) 			
				return null;
			return $string;
		}else{
			return null;
		}	
	}
	
	function get_form_ip($params,$key,$opt=array()){
		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
			if(isset($opt['default'])){		
				return $opt['default'];
			}else{
				return null;
			}
		}	
		$ip  = $this->format_ip($params[$key]);
		if($this->is_ip($ip)){
			return $ip;
		}else{
			return null;
		}
	}
	
	function get_form_mac($params,$key,$opt=array()){
		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
			if(isset($opt['default'])){		
				return $opt['default'];
			}else{
				return null;
			}
		}
		$mac = $this->format_mac($params[$key]);
		if($this->is_mac($mac)){
			return $mac;
		}else{
			return null;
		}
	}
	
	function get_form_checkbox($params,$key,$opt=array()){
		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
			if(isset($opt['default'])){		
				return $opt['default'];
			}else{
				return array();
			}
		}
		$values = $params[$key];
		if(!is_array($values))
			$values = array($values);
		$values = array_map(create_function('$d','return trim($d);'),$values);
		$values = array_filter($values,create_function('$d','return !empty($d);'));		
		if(!empty($values)){
			return $values;
		}else{
			return array();
		}
	}
	
// TODO get_form_xxx 需要重构，下面是重构的代码，还没有测试
//	function _deal_form_info_but_blank($params,$key,$callback,$opt){
//		if((!isset($params[$key]) || $this->is_blank($params[$key]))){
//			if(isset($opt['default'])){		
//				return $opt['default'];
//			}else{
//				if(isset($opt['is_required']) && $opt['is_required'])
//					throw new Exception('非法访问')
//				return '';
//			}
//		}
//		return call_user_fun_array($callback,$opt);
//	}
	
}