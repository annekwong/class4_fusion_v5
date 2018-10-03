<?php
App::import('Helper','Order.AppOrder');
class AppOrderManagesHelper extends AppOrderHelper{
	function sell_order_list_operation($opt){
		$returning = '';
		$m = new SellOrder();
		if($m->is_ready_to_delete(array('SellOrder'=>$opt))){
			$opt['action'] = 'delete';
			$returning .= "\n" . $this->delete_button(array('onclick'=>"return App.Common.updateDivByAjax(\"".Router::url(array('plugin'=>'order','controller'=>'order_manages','action'=>'ajax_sell'))."\",\"#order_list\"," .json_encode($opt).",".json_encode(array('confirm' => __('Confirmdelete',true))).");"));
		}
		return $returning;
	}
	
	function buy_order_list_operation($opt){		
		$returning = '';
		$m = new BuyOrder();
		if($m->is_ready_to_delete(array('BuyOrder'=>$opt))){
			$opt['action'] = 'delete';
			$returning .= "\n" . $this->delete_button(array('onclick'=>"return App.Common.updateDivByAjax(\"".Router::url(array('plugin'=>'order','controller'=>'order_manages','action'=>'ajax_buy'))."\",\"#order_list\"," .json_encode($opt).",".json_encode(array('confirm' => __('Confirmdelete',true))).");"));
		}
		return $returning;
	}
	
	function sell_order_list_response($opt){
		$response_cnt = (int)$opt['response_cnt'];
		if($response_cnt === 0){
			return "No Response";
//		}elseif($response_cnt <= 10){
//			return "<a href=\"#\" onclick=\"return App.Common.updateDivByAjax(\"".Router::url(array('plugin'=>'order','controller'=>'order_manages','action'=>'ajax_buy'))."\">$response_cnt</a>";
		}else{
			return $response_cnt;
//			return "<a target=\"_blank\" href=".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'sell_response','id' => array_keys_value($opt,'id'))).">$response_cnt</a>";
		}
	}
	
	function buy_order_list_response($opt){
		$response_cnt = (int)$opt['response_cnt'];
		if($response_cnt === 0){
			return "No Response";
//		}elseif($response_cnt <= 10){
//			return "<a href=\"#\" onclick=\"return App.Common.updateDivByAjax(\"".Router::url(array('plugin'=>'order','controller'=>'order_manages','action'=>'ajax_buy'))."\">$response_cnt</a>";
		}else{
			return $response_cnt;
//			return "<a target=\"_blank\" href=".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'buy_response','id' => array_keys_value($opt,'id'))).">$response_cnt</a>";
		}
	}
	

}
