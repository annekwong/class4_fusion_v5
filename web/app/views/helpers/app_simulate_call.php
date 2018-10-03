<?php
	App::import("Model","Client");
class AppSimulateCallHelper extends AppHelper {	
	
	var $action_dnis_arr=array();//被叫号array
	var $action_ani_arr=array();//主叫号array
	
	var $egress_action_ani_arr=array();//主叫号array
	var $egress_action_dnis_arr=array();//被叫号array
	
	
	
	#显示转换之后的号码
	function show_action_ani_sub($key,$src_dnis,$action_type,$digit){
		
		if($key!=1){
			$src_dnis=$this->action_ani_arr[$key-1];//准备转换的号码
			}
			//加前缀
			if ($action_type == 1) {
				$action_dnis= $digit . $src_dnis;
			}
			//加后缀
			if ($action_type == 2) {
				$action_dnis= $src_dnis . $digit;
			}
			
			//减去前缀
			if ($action_type == 3) {
				$action_dnis= substr ( $src_dnis, $digit );
			}
			
			//减去后缀
			$start = -$digit;
			if ($action_type == 4) {
				$action_dnis= substr ( $src_dnis, 0, $start );
			}
			
	if(!isset($this->action_ani_arr[$key])){
			$this->action_ani_arr[$key]=$action_dnis;
	}
	
	return $action_dnis;
		
	}
	
	
	
	
		#egress显示转换之后的号码
	function egress_show_action_ani_sub($key,$src_dnis,$action_type,$digit){
		
		if($key!=1){
			$src_dnis=$this->egress_action_ani_arr[$key-1];//准备转换的号码
			}
			//加前缀
			if ($action_type == 1) {
				$action_dnis= $digit . $src_dnis;
			}
			//加后缀
			if ($action_type == 2) {
				$action_dnis= $src_dnis . $digit;
			}
			
			//减去前缀
			if ($action_type == 3) {
				$action_dnis= substr ( $src_dnis, $digit );
			}
			
			//减去后缀
			$start = -$digit;
			if ($action_type == 4) {
				$action_dnis= substr ( $src_dnis, 0, $start );
			}
			
		
	if(!isset($this->egress_action_ani_arr[$key])){
			$this->egress_action_ani_arr[$key]=$action_dnis;
	}
	
	return $action_dnis;
		
	}
	
	
	
	#显示转换之后的号码
	function show_action_dnis_sub($key,$src_dnis,$action_type,$digit){
		
		if($key!=1){
			$src_dnis=$this->action_dnis_arr[$key-1];//准备转换的号码
			}
			//加前缀
			if ($action_type == 1) {
				$action_dnis= $digit . $src_dnis;
			}
			//加后缀
			if ($action_type == 2) {
				$action_dnis= $src_dnis . $digit;
			}
			
			//减去前缀
			if ($action_type == 3) {
				$action_dnis= substr ( $src_dnis, $digit );
			}
			
			//减去后缀
			$start = -$digit;
			if ($action_type == 4) {
				$action_dnis= substr ( $src_dnis, 0, $start );
			}
			
	if(!isset($this->action_dnis_arr[$key])){
			$this->action_dnis_arr[$key]=$action_dnis;
	}
	
	return $action_dnis;
		
	}
	
	
	
	
	
		#显示转换之后的号码
	function egress_show_action_dnis_sub($key,$src_dnis,$action_type,$digit){
		
		if($key!=1){
			$src_dnis=$this->egress_action_dnis_arr[$key-1];//准备转换的号码
			}
			//加前缀
			if ($action_type == 1) {
				$action_dnis= $digit . $src_dnis;
			}
			//加后缀
			if ($action_type == 2) {
				$action_dnis= $src_dnis . $digit;
			}
			
			//减去前缀
			if ($action_type == 3) {
				$action_dnis= substr ( $src_dnis, $digit );
			}
			
			//减去后缀
			$start = -$digit;
			if ($action_type == 4) {
				$action_dnis= substr ( $src_dnis, 0, $start );
			}
			
	if(!isset($this->egress_action_dnis_arr[$key])){
			$this->egress_action_dnis_arr[$key]=$action_dnis;
	}
	
	return $action_dnis;
		
	}
	
	
	#显示转换之前的号码
	function egress_show_action_dnis_pre($key,$dnis){
		if($key==0){
		return $dnis;
		
		}
		if(isset($this->eggress_action_dnis_arr[$key])){
			return	$this->eggress_action_dnis_arr[$key];
	}else{
		return  $dnis;
	}
	
	}
	

		#显示转换之前的号码
	function show_action_ani_pre($key,$dnis){
		if($key==0){
		return $dnis;
		}
		if(isset($this->action_ani_arr[$key])){
			return	$this->action_ani_arr[$key];
	}else{
		return  $dnis;
	}
	
	}
	
	
	
	function egress_show_action_ani_pre($key,$dnis){
		if($key==0){
		return $dnis;
		}
		if(isset($this->egress_action_ani_arr[$key])){
			return	$this->egress_action_ani_arr[$key];
	}else{
		return  $dnis;
	}
	
	}
	
	function  show_action_type($type){
		switch ($type){
			case 1:return "AddPrefix";
			case 2:return "Addsuffix";
			case 3:return "DelPrefix";
			case 4:return "Delsuffix";
		}
	

	
	}
	
	
	function  show_mapping_type($type){
		switch ($type){
			case 0:return "Ignore";
			case 1:return "Compare";
			case 2:return "Replace";
			
		}
	

	
	}
	function format_host_options($hosts){
		$options = array();
		foreach($hosts as $host){
			$ip = $host[0]['ip'];
			$port = $host[0]['port'];				
			$options[$ip.':'.$port]=$ip.':'.$port;
		}			
		return $options;
	}
	
	function format_route_strategy_options($route_strategies){
		$options = array();
		foreach($route_strategies as $route_strategy){							
			$options[$route_strategy['RouteStrategy']['route_strategy_id']] = $route_strategy['RouteStrategy']['name'];
		}			
		return $options;
	}
	
	
	
	
	function format_release_cause($key){
	$arr=array(
	'0'=>'RF_INVAILED_ARGS',
	'1'=>'RF_POOL_SESSION',
	'2'=>'RF_IN_SYS_LIMIT',
	'3'=>'RF_INGRESS_IP_CHECK',
	'4'=>'RF_INGRESS_RESOURCE',
	'5'=>'RF_ROUTE_STRATAGY',
	'6'=>'RF_PRODUCT_NOT_FOUND',
	'7'=>'RF_IN_RESORUCE_LIMIT',
	'8'=>'RF_RESOURCE_CODEC',
	'9'=>'RF_INGRESS_LRN_BLOCK',
	'10'=>'RF_INGRESS_RATE',
	'11'=>'RF_EGRESS_NOT_FOUND',
	'12'=>'RF_NORMAL'

	);
	if(isset($arr[$key]))
	{
		return  "<span  style='color:red'>$arr[$key]</span>" ;
	}
	else
	{
	return null;
	}
	}
	
	
	
	function isNumber($val) {
		if(!is_array($val)){
		if (ereg ( "^[0-9]+$", $val )) {
			return true;
		} else {
			return false;
		}
		}
	}	
	
	
function  format_carrier($client_id){
	if($this->isNumber($client_id)){
	$model=new Client ();
	$list=$model->query("select name  from  client where  client_id=$client_id");
	if(isset($list[0][0]['name'])&&!empty($list[0][0]['name'])){
	  return $list[0][0]['name'];
	
	}else{
	return $client_id;
	}
	}else{
		return $client_id;
	}
}	



	
function  format_trunk($id){
	//pr($id);
		if($this->isNumber($id))
		{
		$model=new Client ();
		$list=$model->query("select  alias as name  from  resource where  resource_id=$id");
		if(isset($list[0][0]['name'])&&!empty($list[0][0]['name']))
				{
		 				return $list[0][0]['name'];
				}
				else
						{
							return '';
						}
		}
		else
		{
			
		$str='';
		if(is_array($id)){
				foreach ($id  as $key=>$value){
				  $str.= $this->find_resource_name($value)."<br/>";
				}
		return $str;
		}else{
		 return   $this->find_resource_name($id);
		
		
		}

		
		
		
		
		}
}	





function find_resource_name($id){	
		 $arr=explode(' ',$id);
		if(isset($arr[0])){
			$resource_id=$arr[0];
			if($this->isNumber($resource_id)){
			$model=new Client ();
			$list=$model->query("select  alias as name  from  resource where  resource_id=$resource_id");
			if(isset($list[0][0]['name'])&&!empty($list[0][0]['name'])){
	     $name= $list[0][0]['name'];
	     array_shift($arr);
	     return $name."  " .join(' ',$arr);
	
	}else{
	return $id;
	}
		}
		}
		
		


}


	
function  format_host_id($id){
		if($this->isNumber($id)){
	$model=new Client ();
	$list=$model->query("select  ip as name  from  resource_ip where  resource_ip_id=$id");
	if(isset($list[0][0]['name'])&&!empty($list[0][0]['name'])){
	  return $list[0][0]['name'];
	
	}else{
	return '';
	}
		}
}	



	
function  format_static_route_id($id){
		if($this->isNumber($id)){
	$model=new Client ();
	$list=$model->query("select   name  from  product where  product_id=$id");
	if(isset($list[0][0]['name'])&&!empty($list[0][0]['name'])){
	  return $list[0][0]['name'];
	
	}else{
	return $id;
	}
		}
}	



function  format_dy_route_id($id){
		if($this->isNumber($id))
		{
			$model=new Client ();
			$list=$model->query("select   name  from  dynamic_route where  dynamic_route_id=$id");
			if(isset($list[0][0]['name'])&&!empty($list[0][0]['name']))
				{
	  			return $list[0][0]['name'];
	
				}
				else
				{
					return '';
				}
		}
else
{
		
	return $id;
	
		}
}	



function  format_route_plan_id($id){
		if($this->isNumber($id))
		{
			$model=new Client ();
			$list=$model->query("select   name  from  route_strategy where  route_strategy_id=$id");
			if(isset($list[0][0]['name'])&&!empty($list[0][0]['name']))
				{
	  			return $list[0][0]['name'];
	
				}
				else
				{
					return '';
				}
		}
else
{
		
	return $id;
	
		}
}	



function  format_rate_table($id){
		if($this->isNumber($id))
		{
			$model=new Client ();
			$list=$model->query("select   name  from  rate_table where  rate_table_id=$id");
			if(isset($list[0][0]['name'])&&!empty($list[0][0]['name']))
				{
	  			return $list[0][0]['name'];
	
				}
				else
				{
					return $id;
				}
		}
else
{
		
	return $id;
	
		}
}	





function format_orig_error($value){
		switch($value){
			case 0:return 'Invalid Argument';
			case 1:return 'System Limit  Cap Exceeded';
			case 2:return 'System Limit Cps Exceeded';
			case 3:return 'Unauthorized IP Address';
			case 4:return 'No Ingress Resource Found';
			case 5:return 'No Product Found';
			case 6:return 'Trunk Limit Cap Exceeded';
			case 7:return 'Trunk Limit Cps Exceeded';
			case 8:return 'IP Limit Cap Exceeded';
			case 9:return 'IP Limit cps Exceeded';
			case 10:return 'Invalid Codec Negotiation';
			
			case 11:return 'Block due to LRN';
			case 12:return 'Ingress Rate Not Found';
			case 13:return 'Egress Trunk Not Found';
			
			case 14:return 'From egress response 404';
			case 15:return 'From egress response 486';
			case 16:return 'From egress response 487';
			case 17:return 'From egress response 200';
			case 18:return 'All egress not available';
			case 19:return 'Invalid Codec Negotiation';
			case 20:return 'Normal';
			default : return "Unknown";
		}


}
}