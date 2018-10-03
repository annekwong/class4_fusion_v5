<?php
class OrderMyOrdersController extends OrderAppController{
	var $name = 'OrderMyOrders';
	var $uses = array();
	var $helpers = array('Order.AppOrderMyOrders','Order.appOrderResponses','Order.AppOrderContracts','Order.AppOrderBrowsers');


	public function beforeFilter(){
		  $this->checkSession ( "login_type" );//核查用户身份
     parent::beforeFilter();
	}
	function sell(){
		$order_responses = $this->BuyOrderResponse->find_orders_by_client_id($this->Session->read('sst_client_id'));
//		$this->_contract_sell();
		$this->set('order_responses', $order_responses);
		$this->set('do_action','sell');
		$this->render('order');
	}

	function buy(){
		$order_responses = $this->SellOrderResponse->find_orders_by_client_id($this->Session->read('sst_client_id'));
//		$this->_contract_buy();
		$this->set('order_responses',$order_responses );
		$this->set('do_action','buy');
		$this->render('order');
	}

	function manage_sell(){
		$order_responses = $this->BuyOrderResponse->find_orders_for_manage($this->_filter_conditions('BuyOrder',array('country')));
		$this->set('p', $order_responses);
		$this->set('do_action','sell');
		$this->render('manage_order');
	}

	function manage_buy(){
		$order_responses = $this->SellOrderResponse->find_orders_for_manage($this->_filter_conditions('SellOrder',array('country')));
		$this->set('p',$order_responses );
		$this->set('do_action','buy');
		$this->render('manage_order');
	}

	function ajax_contract_operation(){
		Configure::write("debug","0");
		$this->layout = '';
		$this->_catch_exception_msg(array('OrderContractsController','_ajax_contract_operation_impl'));
		$this->set('buy_or_sell',array_keys_value($this->params,"url.buy_or_sell"));
		$this->set('do_action' , "sell");
	}
	function _ajax_contract_operation_impl(){
		$contract_id = (float)array_keys_value($this->params,"url.contract_id");
		$contract_type = (float)array_keys_value($this->params,"url.contract_type");
		$contract = $this->Contract->find_detail_by_id($contract_id,$contract_type);
		if(empty($contract)){
			throw new Exception("Permission denied");
		}
		$action = strtolower(trim(array_keys_value($this->params,"url.action")));
		if(!in_array($action,array('hold','contract'))){
			throw new Exception("Permission denied");
		}
		$m = new Contract();
		$method = "do_$action";
		$contract = $contract[0];
		if($m->{$method}($contract)){
			$this->set('contract',$contract);
		}else{
			throw new Exception("Save fail");
		}
	}

}
