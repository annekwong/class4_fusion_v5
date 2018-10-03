<?php
class OrderManagesController extends OrderAppController{
	var $name = 'OrderManages';
	var $uses = array();
	var $helpers = array('Order.AppOrderManages');

	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		parent::beforeFilter();

	}
	function buy(){
		$this->pageTitle = "Manage	Buy Order";
		$this->_catch_exception_msg(array('OrderManagesController','_buy_impl'));
		$this->set('do_action','buy');
		$this->render('manage');
	}

	function _buy_impl(){
		$orders = $this->BuyOrder->find_by_client_id_and_response_cnt(
			$this->Session->read('sst_client_id'),
			$this->_filter_conditions(Array('country'=>'search_country','asr','acd'),'buy_order'),
			$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time','update_time'),'buy_order')
		);
		$this->set('orders',$orders);
		$this->set('resources',$this->Resource->find_client_egress($this->Session->read('sst_client_id')));
		$order_codes = array();
		if(is_array($orders))
		{
			foreach($orders as $order){
				$order_id = array_keys_value($order,'0.id');
				$order_codes[$order_id] = $this->OrderCode->find_buy_code_by_order_id($order_id);
			}
		}
		$this->set('order_codes',$order_codes);
	}

	function ajax_buy(){
		$this->layout  = '';
		$this->_ajax_request(array('OrderManagesController','_ajax_buy_impl'));
		$this->set('do_action','buy');
		$this->render('ajax_manage');
	}

	function _ajax_buy_impl($params=array()){
		$action = $this->PhpCommon->get_form_string($this->params['url'],'action');
		if($action === "delete"){
			$id = $this->PhpCommon->get_form_int($this->params['url'],'id');
			if(!empty($id)){
				if(!$this->Contract->has_bought_this_order($id)){
					$this->BuyOrder->deleteAll("id = $id AND client_id = ".$this->Session->read('sst_client_id'));
					$this->OrderCode->deleteAll("order_id = $id and order_type=".OrderCode::ORDER_TYPE_BUY . " AND client_id = ".$this->Session->read('sst_client_id'));
				}
			}
		}elseif($action === 'delete_selected'){

		}
		$this->_buy_impl();
	}

	
	
	
	function sell(){
		$this->pageTitle = "Manage	Sell Order";
		$this->_catch_exception_msg(array('OrderManagesController','_sell_impl'));
		$this->set('do_action','sell');
		$this->render('manage');
	}

	function _sell_impl(){
		$orders = $this->SellOrder->find_by_client_id_and_response_cnt(
			$this->Session->read('sst_client_id'),
			$this->_filter_conditions(Array('country'=>'search_country','asr','acd'),'sell_order'),
			$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time','update_time'),'sell_order')
		);
		$this->set('orders',$orders);
		$this->set('resources',$this->Resource->find_client_egress($this->Session->read('sst_client_id')));
		$order_codes = array();
		foreach($orders as $order){
			$order_id = array_keys_value($order,'0.id');
			$order_codes[$order_id] = $this->OrderCode->find_sell_code_by_order_id($order_id);
		}
		$this->set('order_codes',$order_codes);
	}

	function ajax_sell(){
		$this->layout  = '';
		$this->_ajax_request(array('OrderManagesController','_ajax_sell_impl'));
		$this->set('do_action','sell');
		$this->render('ajax_manage');
	}

	function _ajax_sell_impl($params=array()){
		$action = $this->PhpCommon->get_form_string($this->params['url'],'action');
		if($action === "delete"){
			$id = $this->PhpCommon->get_form_int($this->params['url'],'id');
			if(!empty($id)){
				if(!$this->Contract->has_sold_this_order($id)){
					$this->SellOrder->deleteAll("id = $id AND client_id = ".$this->Session->read('sst_client_id'));
					$this->OrderCode->deleteAll("order_id = $id and order_type=".OrderCode::ORDER_TYPE_SELL."  AND client_id = ".$this->Session->read('sst_client_id'));
				}
			}
		}elseif($action === 'delete_selected'){

		}
		$this->_sell_impl();
	}
}

