<?php
class OrderCode extends OrderAppModel {
	var $useTable = 'order_code';
	const ORDER_TYPE_BUY = 1;
	const ORDER_TYPE_SELL = 2;
	
	function find_buy_code_by_order_id($order_id){
		if(empty($order_id)){
			return array();
		}
		return $this->findAll("order_type = ".self::ORDER_TYPE_BUY." AND order_id = ".$order_id);
	}
	
	function find_sell_code_by_order_id($order_id){
		if(empty($order_id)){
			return array();
		}
		return $this->findAll("order_type = ".self::ORDER_TYPE_SELL." AND order_id = ".$order_id);
	}
	
	function find_all_code_name_by_country_and_client_id($order_type,$country,$client_id,$order_id=null){
		$ext_conditions = '';
		if($order_id){
			$ext_conditions = " AND order_id <> $order_id";
		}
		$code_names =  $this->findAll("order_type = $order_type AND country = '$country' AND client_id = $client_id $ext_conditions",'DISTINCT code_name');
		return array_map(create_function('$d','return $d["OrderCode"]["code_name"];'),$code_names);
	}
}

