<?php
App::import('Helper','Order.AppOrder');
class AppOrderResponsesHelper extends AppOrderHelper{
	
	function sell_order_response_status($status) {
		$array = array(SellOrderResponse::RESPONSE_STATUS_CREATE => "create" , SellOrderResponse::RESPONSE_STATUS_SELLER_CONFIRM => "Seller confirm",
			SellOrderResponse::RESPONSE_STATUS_CONTRACT => "Confirm",SellOrderResponse::RESPONSE_STATUS_SELLER_REJECT => "Seller reject",
			SellOrderResponse::RESPONSE_STATUS_BUYEE_CANCEL => "Buyee cancel",SellOrderResponse::RESPONSE_STATUS_EXPIRED => "Expired");
		return isset($array[$status]) ? $array[$status] : '';	
	}
	
	function buy_order_response_status($status) {
		$array = array(BuyOrderResponse::RESPONSE_STATUS_CREATE => "create" , BuyOrderResponse::RESPONSE_STATUS_BUYER_CONFIRM => "Buyer confirm",
			BuyOrderResponse::RESPONSE_STATUS_CONTRACT => "Confirm",BuyOrderResponse::RESPONSE_STATUS_BUYER_REJECT => "Buyer reject",
			BuyOrderResponse::RESPONSE_STATUS_SELLEE_CANCEL => "Sellee cancel",BuyOrderResponse::RESPONSE_STATUS_EXPIRED => "Expired");
		return isset($array[$status]) ? $array[$status] : '';	
	}

	function buy_order_response_operation($buy_order_response){
		$opts  = array(
			array('action' => "confirm" , 'label' => "confirm",'img' => "images/status_closed.gif"),
			array('action' => "reject" , 'label' => "reject",'img' => "images/no.png"),
			array('action' => "contract" , 'label' => "confirm",'img' => "images/status_closed.gif"),
			array('action' => "cancel" , 'label' => "cancel",'img' => "images/no.png")			
		);
		$m = new BuyOrderResponse();
		$returning = array();
		foreach($opts as $opt){
			$method = "is_ready_to_{$opt['action']}";
			if($m->{$method}($buy_order_response)){
				$returning[] = $this->_buy_order_response_operation_button($buy_order_response,$opt);
			}
		}
		return join("\n",$returning);
	}
	
	function _buy_order_response_operation_button($buy_order_response,$opt){
		$buy_order_response = array_keys_value($buy_order_response,"BuyOrderResponse");
		$buy_order_response['action'] = $opt['action'];
		$onclick = "return App.Common.updateDivByAjax(\"".Router::url(array('plugin' => "order",'controller'=>"order_responses",'action'=>"ajax_buy_response_operation"))."\",\"#response_".array_keys_value($buy_order_response,"id")."\",".json_encode($buy_order_response).",{\"confirm\":\"Are you sure?\"})";
		$img = array_keys_value($opt,"img");
		if(empty($img)){
			return "<a href=\"#\" onclick='$onclick'>{$opt['label']}</a>" ;
		}else{
			return "<a href=\"#\" onclick='$onclick'><img src=\"{$this->webroot}{$opt['img']}\">{$opt['label']}</a>" ;
		}
	}
	
	
	function sell_order_response_operation($sell_order_response){
		$opts  = array(
			array('action' => "confirm" , 'label' => "confirm",'img' => "images/status_closed.gif"),
			array('action' => "reject" , 'label' => "reject",'img' => "images/no.png"),
			array('action' => "contract" , 'label' => "confirm",'img' => "images/status_closed.gif"),
			array('action' => "cancel" , 'label' => "cancel",'img' => "images/no.png")			
		);
		$m = new SellOrderResponse();
		$returning = array();		
		foreach($opts as $opt){
			$method = "is_ready_to_{$opt['action']}";
			if($m->{$method}($sell_order_response)){
				$returning[] = $this->_sell_order_response_operation_button($sell_order_response,$opt);
			}
		}
		return join("\n",$returning);
	}
	
	function _sell_order_response_operation_button($sell_order_response,$opt){
		$sell_order_response = array_keys_value($sell_order_response,"SellOrderResponse");
		$sell_order_response['action'] = $opt['action'];
		$onclick = "return App.Common.updateDivByAjax(\"".Router::url(array('plugin' => "order",'controller'=>"order_responses",'action'=>"ajax_sell_response_operation"))."\",\"#response_".array_keys_value($sell_order_response,"id")."\",".json_encode($sell_order_response).",{\"confirm\":\"Are you sure?\"})";
		$img = array_keys_value($opt,"img");
		if(empty($img)){
			return "<a href=\"#\" onclick='$onclick'>{$opt['label']}</a>" ;
		}else{
			return "<a href=\"#\" onclick='$onclick'><img src=\"{$this->webroot}{$opt['img']}\">{$opt['label']}</a>" ;
		}
	}
	
	function private_sell_operation($order,$o_order){
		$opts  = array(
			array('action' => "contract" , 'label' => "confirm",'img' => "images/status_closed.gif"),
		);
		$m = new Contract();
		$returning = array();							
		foreach($opts as $opt){
			$method = "is_ready_to_private_{$opt['action']}";
			if($m->{$method}(Contract::CONTRACT_TYPE_SELL,$order,$o_order)){
				$returning[] = $this->_private_order_response_operation_button('sell',$order,$o_order,$opt);
			}
		}
		return join("\n",$returning);
	}
	
	function private_buy_operation($order,$o_order){
		$opts  = array(
			array('action' => "contract" , 'label' => "confirm",'img' => "images/status_closed.gif"),
		);
		$m = new Contract();
		$returning = array();							
		foreach($opts as $opt){
			$method = "is_ready_to_private_{$opt['action']}";
			if($m->{$method}(Contract::CONTRACT_TYPE_BUY,$order,$o_order)){
				$returning[] = $this->_private_order_response_operation_button('buy',$order,$o_order,$opt);
			}
		}
		return join("\n",$returning);
	}
	
	function _private_order_response_operation_button($type,$order,$o_order,$opt){
		$order = array_keys_value($order,"0");
		$order['action'] = $opt['action'];
		$order["deal_order_id"] = array_keys_value($o_order,'0.id');
		$onclick = "return App.Common.updateDivByAjax(\"".Router::url(array('plugin' => "order",'controller'=>"order_responses",'action'=>"ajax_private_{$type}_operation"))."\",\"#order_".array_keys_value($o_order,"0.id")."\",".json_encode($order).",{\"confirm\":\"Are you sure?\"})";
		$img = array_keys_value($opt,"img");
		if(empty($img)){
			return "<a href=\"#\" onclick='$onclick'>{$opt['label']}</a>" ;
		}else{
			return "<a href=\"#\" onclick='$onclick'><img src=\"{$this->webroot}{$opt['img']}\">{$opt['label']}</a>" ;
		}
	}
	
	
}
