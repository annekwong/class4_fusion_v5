<?php
App::import('Helper','Order.AppOrder');
class AppOrderBrowsersHelper extends AppOrderHelper{
	function buy_order_list_send_response($item){
		return "<a target=\"_blank\" href=\"".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'buy','id'=>$item['id']))."\">Sell</a>";
	}
	
	function sell_order_list_send_response($item){
		return "<a target=\"_blank\" href=\"".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'sell','id'=>$item['id']))."\">Buy</a>";
	}
	
	function private_buy_order_list_send_response($item){
		return "<a target=\"_blank\" href=\"".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'private_buy','id'=>$item['id']))."\">Match</a>";
	}
	
	function private_sell_order_list_send_response($item){
		return "<a target=\"_blank\" href=\"".Router::url(array('plugin'=>'order','controller'=>'order_responses','action'=>'private_sell','id'=>$item['id']))."\">Match</a>";
	}
}
