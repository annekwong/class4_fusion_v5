<?php
class OrderResponsesController extends OrderAppController{
	var $name = 'OrderResponses';
	var $uses = array('order.Contract');
	var $helpers = array('Order.AppOrderResponses');
	var $components = array('PhpSendMail');

	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		parent::beforeFilter();
	}

	/**
	 * 管理员审核私有交易
	 */
	function ajax_private_buy_operation(){
	
		Configure::write("debug","0");
		$this->layout = '';
		$this->set('do_action' , "buy");
		$this->_catch_exception_msg(array('OrderResponsesController','_ajax_private_buy_operation_impl'));
		$this->render('ajax_private_buy_or_sell_operation');
	}

	/**
	 * 管理员审核私有交易  匹配sell
	 */
	function ajax_private_sell_operation(){
		Configure::write("debug","0");
		$this->layout = '';
		$this->set('do_action' , "sell");
		$this->_catch_exception_msg(array('OrderResponsesController','_ajax_private_sell_operation_impl'));
		$this->render('ajax_private_buy_or_sell_operation');
	}

		/**
	 * 管理员审核私有交易
	 */
	function _ajax_private_buy_operation_impl(){
			
		$buy_order= $this->BuyOrder->find_private_order_by_id(array_keys_value($this->params,'url.id'));
		$sell_order= $this->SellOrder->find_private_order_by_id(array_keys_value($this->params,'url.deal_order_id'));
	
		$buy_order = array_keys_value($buy_order,'0');
		$sell_order = array_keys_value($sell_order,'0');
	
		if(empty($buy_order) || empty($sell_order)){
			throw new Exception("Permission denied");
		}
		$d = array("Contract" => array(
			'contract_type' => Contract::CONTRACT_TYPE_BUY,'order_id' => $buy_order['id'],
			'order_response_id' => $sell_order['id'] , 'status' => Contract::CONTRACT_STATUS_CONTRACT,
			'is_commit' => $sell_order['is_commit'] , 'commit_minutes' => $sell_order['commit_minutes'],
			'create_time' => gmtnow(),'update_time' => gmtnow(),'expire_time'=>$sell_order['expire_time'],
			'client_id' => $sell_order['client_id'],'is_private' => true,'resource_id'=>$sell_order['resource_id']
			));
		
		$c = new Contract();
		$this->set('order',array($buy_order));
		$this->set('o_order',array($sell_order));
		 return $this->Contract->save($d,false);
	}

	function _ajax_private_sell_operation_impl(){
		$sell_order= $this->SellOrder->find_private_order_by_id(array_keys_value($this->params,'url.id'));
		$buy_order= $this->BuyOrder->find_private_order_by_id(array_keys_value($this->params,'url.deal_order_id'));
		$sell_order = array_keys_value($sell_order,'0');
		$buy_order = array_keys_value($buy_order,'0');
		if(empty($buy_order) || empty($sell_order)){
			throw new Exception("Permission denied");
		}
		
		//帮助sell建立合同
		$d = array("Contract" => array(
			'contract_type' => Contract::CONTRACT_TYPE_SELL,'order_id' => $sell_order['id'],
			'order_response_id' => $buy_order['id'] , 'status' => Contract::CONTRACT_STATUS_CONTRACT,
			'is_commit' => $buy_order['is_commit'] , 'commit_minutes' => $buy_order['commit_minutes'],
			'create_time' => gmtnow(),'update_time' => gmtnow(),'expire_time'=>$buy_order['expire_time'],
			'client_id' => $buy_order['client_id'],'is_private' => true,
		'resource_id'=>$buy_order['resource_id']
			));
		$c = new Contract();
		$this->set('order',array($sell_order));
		$this->set('o_order',array($buy_order));
		return $this->Contract->save($d);
	}

	function ajax_buy_response_operation(){
		Configure::write("debug","0");
		$this->layout = '';
		$this->set('do_action' , "buy");
		$this->_catch_exception_msg(array('OrderResponsesController','_ajax_buy_response_operation_impl'));
		$this->render('ajax_buy_or_sell_response_operation');
	}

	function _ajax_buy_response_operation_impl(){
		$this->set('referer',$this->referer());
		$buy_order_response_id = (float)array_keys_value($this->params,"url.id");
		$action = strtolower(trim(array_keys_value($this->params,"url.action")));

		if($buy_order_response_id <= 0 || empty($action)){
				throw new Exception("Permission denied");
		}
		$buy_order_response = $this->BuyOrderResponse->find_by_id($buy_order_response_id);
		if(empty($buy_order_response)){
			throw new Exception("Permission denied");
		}
		$buy_order = $this->BuyOrder->find_public_order_by_id(array_keys_value($buy_order_response,"BuyOrderResponse.buy_order_id"));

		if(in_array($action,array('confirm','reject'))){
			if(array_keys_value($buy_order,"0.client_id") != $this->Session->read('sst_client_id')){
				throw new Exception("Permission denied");
			}
		}elseif(in_array($action,array('cancel','contract'))){
			if(array_keys_value($buy_order_response,"0.client_id") == $this->Session->read('sst_client_id')){
				throw new Exception("Permission denied");
			}
		}else{
			throw new Exception("Permission denied");
		}
		$m = new BuyOrderResponse();
		$method = "do_$action";
		if($m->{$method}($buy_order_response)){
			$this->set('order',$buy_order);
			$this->set('order_response',$buy_order_response);
			$resource_id = array_keys_value($buy_order_response,'BuyOrderResponse.resource_id');
			$resource_ids = array($resource_id);
			$resource_ids = array_filter($resource_ids,create_function('$d','return (int)$d>0;'));
			$resource_ids = array_unique($resource_ids);
			$this->set('resource_infos',$this->Resource->get_acd_asr_etc($resource_ids));
		}else{
			throw new Exception("Save fail");
		}
	}

	function buy_response($id=null){
		$this->_catch_exception_msg(array('OrderResponsesController','_buy_response_impl'),array('id'=>$id));
		$this->set('do_action' , "buy");
		$this->render('buy_or_sell_response');
	}

	function _buy_response_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			$buy_order = $this->BuyOrder->find_public_order_by_id($params['id']);
			$this->set('order',$buy_order);
			if(empty($buy_order)){
				throw new Exception("Permission denied");
			}else{
				$order_responses = $this->BuyOrderResponse->find_by_buy_order_id(array_keys_value($buy_order,"0.id"));
				$this->set('order_responses',$order_responses);
				$resource_ids = array_map(create_function('$d','return $d["BuyOrderResponse"]["resource_id"];'),$order_responses);
				$resource_ids = array_filter($resource_ids,create_function('$d','return (int)$d>0;'));
				$resource_ids = array_unique($resource_ids);
				$this->set('resource_infos',$this->Resource->get_acd_asr_etc($resource_ids));
				$this->set('order_code',$this->OrderCode->find_buy_code_by_order_id($params['id']));
			}
		}else{
			throw new Exception("Permission denied");
		}
	}

	function ajax_sell_response_operation(){
		Configure::write("debug","0");
		$this->layout = '';
		$this->_catch_exception_msg(array('OrderResponsesController','_ajax_sell_response_operation_impl'));
		$this->set('do_action' , "sell");
		$this->render('ajax_buy_or_sell_response_operation');
	}

	function _ajax_sell_response_operation_impl(){
		$this->set('referer',$this->referer());
		$sell_order_response_id = (float)array_keys_value($this->params,"url.id");
		$action = strtolower(trim(array_keys_value($this->params,"url.action")));

		if($sell_order_response_id <= 0 || empty($action)){
				throw new Exception("Permission denied");
		}
		$sell_order_response = $this->SellOrderResponse->find_by_id($sell_order_response_id);
		if(empty($sell_order_response)){
			throw new Exception("Permission denied");
		}
		$sell_order = $this->SellOrder->find_public_order_by_id(array_keys_value($sell_order_response,"SellOrderResponse.sell_order_id"));

		if(in_array($action,array('confirm','reject'))){
			if(array_keys_value($sell_order,"0.client_id") != $this->Session->read('sst_client_id')){
				throw new Exception("Permission denied");
			}
		}elseif(in_array($action,array('cancel','contract'))){
			if(array_keys_value($sell_order_response,"0.client_id") == $this->Session->read('sst_client_id')){
				throw new Exception("Permission denied");
			}
		}else{
			throw new Exception("Permission denied");
		}
		$m = new SellOrderResponse();
		$method = "do_$action";
		if($m->{$method}($sell_order_response)){
			$this->set('order',$sell_order);
			$this->set('order_response',$sell_order_response);
			$resource_id = array_keys_value($sell_order_response,'SellOrderResponse.resource_id');
			$resource_ids = array($resource_id);
			$resource_ids = array_filter($resource_ids,create_function('$d','return (int)$d>0;'));
			$resource_ids = array_unique($resource_ids);
			$this->set('resource_infos',$this->Resource->get_acd_asr_etc($resource_ids));
		}else{
			throw new Exception("Save fail");
		}
	}

	function sell_response($id=null){
		$this->_catch_exception_msg(array('OrderResponsesController','_sell_response_impl'),array('id'=>$id));
		$this->set('do_action' , "sell");
		$this->render('buy_or_sell_response');
	}

	function _sell_response_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			$sell_order = $this->SellOrder->find_public_order_by_id($params['id']);
			$this->set('order',$sell_order);
			if(empty($sell_order)){
				throw new Exception("Permission denied");
			}else{
				$order_responses = $this->SellOrderResponse->find_by_sell_order_id(array_keys_value($sell_order,"0.id"));
				$this->set('order_responses',$order_responses);
				$resource_ids = array_map(create_function('$d','return $d["SellOrderResponse"]["resource_id"];'),$order_responses);
				$resource_ids = array_filter($resource_ids,create_function('$d','return (int)$d>0;'));
				$resource_ids = array_unique($resource_ids);
				$this->set('resource_infos',$this->Resource->get_acd_asr_etc($resource_ids));
				$this->set('order_code',$this->OrderCode->find_sell_code_by_order_id($params['id']));
			}
		}else{
			throw new Exception("Permission denied");
		}
	}

//	===================================================
	function buy($id=null) {
		$this->_catch_exception_msg(array('OrderResponsesController','_buy_impl'),array('id'=>$id));
		$this->set('do_action' , "buy");
		$this->set('resources',$this->Resource->find_client_egress($this->Session->read('sst_client_id')));
		$this->render('buy_or_sell');
	}

	function _buy_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			if ($this->Contract->has_bought_this_order($params['id'],$this->Session->read('sst_client_id'))){
				$this->set('has_deal',true);
			}else if (!$this->Client->check_enough_balance($this->Session->read('sst_client_id'))){
				$this->set('not_enough_money',true);
			}else{
				if ($this->RequestHandler->isPost ()) {
					$this->_create_or_update_buy_order_response ($params);
					return;
				}
			}
			$buy_order = $this->BuyOrder->find_public_order_by_id($params['id']);
			if(empty($buy_order)){
				throw new Exception("Permission denied");
			}else{
				$this->set('order',$buy_order);
				$this->data = $this->BuyOrderResponse->create();
				$this->set('order_code',$this->OrderCode->find_buy_code_by_order_id($params['id']));
			}
		}else{
			throw new Exception("Permission denied");
		}
	}

	function _create_or_update_buy_order_response($params=array()){
			$buy_order = $this->BuyOrder->find_public_order_by_id($params['id']);
			if(empty($buy_order)){
				throw new Exception("Permission denied");
			}else{
				if(!isset($this->data['Contract'])){
					$this->data['Contract'] = array();
				}
				$this->data['Contract']['client_id'] = $this->Session->read('sst_client_id');
				$this->data['Contract']['order_id'] = array_keys_value($buy_order,"0.id");
				$this->data['Contract']['is_commit'] = array_keys_value($buy_order,"0.is_commit");
				$this->data['Contract']['contract_type'] = Contract::CONTRACT_TYPE_BUY;
				$this->data['Contract']['status'] = Contract::CONTRACT_STATUS_CONTRACT;
				$this->data['Contract']['create_time'] = gmtnow();
				$this->data['Contract']['update_time'] = gmtnow();
				$this->data['Contract']['expire_time'] = array_keys_value($buy_order,"0.expire_time");
				$this->data['Contract']['is_private'] = false;
				if(!array_keys_exists($this->data,'Contract.commit_minutes')){
					$this->data['Contract']['commit_minutes'] = 0;
				}
				if($this->Contract->save($this->data)){
					$this->_send_mail_for_client($this->Contract->getLastInsertID(),$params['id'],'confirmed','seller',$_SESSION['sst_client_id']);
					$this->_send_mail_for_client($this->Contract->getLastInsertID(),$params['id'],'confirmed','seller',$buy_order[0]['client_id']);
				
					$this->BuyOrder->create_json_array('',201,"save success!");
					$this->Session->write('m',$this->BuyOrder->set_validator());
					$this->redirect ( array ('id' => $params['id'] ) );
				};
				$this->set('order_code',$this->OrderCode->find_buy_code_by_order_id($params['id']));
				$this->set('order',$buy_order);
			}
	}
//	======================================================
	function sell($id=null) {
		$this->_catch_exception_msg(array('OrderResponsesController','_sell_impl'),array('id'=>$id));
		$this->set('do_action' , "sell");
		$this->set('resources',$this->Resource->find_client_ingress($this->Session->read('sst_client_id')));
		$this->render('buy_or_sell');
	}

	function _sell_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			if ($this->Contract->has_sold_this_order($params['id'],$this->Session->read('sst_client_id'))){
				$this->set('has_deal',true);
			}else if (!$this->Client->check_enough_balance($this->Session->read('sst_client_id'))){
				$this->set('not_enough_money',true);
			}else{
				if ($this->RequestHandler->isPost ()) {
					$this->_create_or_update_sell_order_response ($params);
					return;
				}
			}
			$sell_order = $this->SellOrder->find_public_order_by_id($params['id']);
			if(empty($sell_order)){
				throw new Exception("Permission denied");
			}else{
				$this->set('order',$sell_order);
				$this->data = $this->SellOrderResponse->create();
				$this->set('order_code',$this->OrderCode->find_sell_code_by_order_id($params['id']));
			}
		}else{
			throw new Exception("Permission denied");
		}
	}

	
function to_client_mail(){


}	
	
	function _create_or_update_sell_order_response($params=array()){
			$sell_order = $this->SellOrder->find_public_order_by_id($params['id']);//找到sell_order
			if(empty($sell_order)){
				throw new Exception("Permission denied");
			}else{
				if(!isset($this->data['Contract'])){
					$this->data['Contract'] = array();
				}
				$this->data['Contract']['client_id'] = $this->Session->read('sst_client_id');
				$this->data['Contract']['order_id'] = array_keys_value($sell_order,"0.id");
				$this->data['Contract']['is_commit'] = array_keys_value($sell_order,"0.is_commit");
				$this->data['Contract']['contract_type'] = Contract::CONTRACT_TYPE_SELL;
				$this->data['Contract']['status'] = Contract::CONTRACT_STATUS_CONTRACT;
				$this->data['Contract']['create_time'] = gmtnow();
				$this->data['Contract']['update_time'] = gmtnow();
				$this->data['Contract']['expire_time'] = array_keys_value($sell_order,"0.expire_time");
				$this->data['Contract']['is_private'] = false;
				if(!array_keys_exists($this->data,'Contract.commit_minutes')){
					$this->data['Contract']['commit_minutes'] = 0;
				}

				if($this->Contract->save($this->data)){
					$this->_send_mail_for_client($this->Contract->getLastInsertID(),$params['id'],'confirmed','buyer',$_SESSION['sst_client_id']);
					$this->_send_mail_for_client($this->Contract->getLastInsertID(),$params['id'],'confirmed','buyer',$sell_order[0]['client_id']);
					$this->SellOrder->create_json_array('',201,"save success!");
					$this->Session->write('m',$this->SellOrder->set_validator());
					$this->redirect ( array ('id' => $params['id'] ) );
				};

				$this->set('order',$sell_order);
			}
	}

//	==================================
	function private_buy($id=null){
		$this->_catch_exception_msg(array('OrderResponsesController','_private_buy_impl'),array('id'=>$id));
		$this->set('do_action' , "buy");
		$this->render('private_buy_or_sell');
	}

	function _private_buy_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			$buy_order = $this->BuyOrder->find_private_order_by_id($params['id']);
			if(empty($buy_order)){
				throw new Exception("Permission denied");
			}else{
				$this->set('order',$buy_order);
				$this->set('order_code',$this->OrderCode->find_buy_code_by_order_id($params['id']));
				$p = $this->SellOrder->find_private_order_by_country_except_client_id(array_keys_value($buy_order,"0.country"),array_keys_value($buy_order,"0.client_id"));
				$this->set('o_orders',$p->getDataArray());
				$this->set('p',$p);
				$order_codes = array();
				foreach($p->getDataArray() as $order){
					$order_id = array_keys_value($order,'0.id');
					$order_codes[$order_id] = $this->OrderCode->find_sell_code_by_order_id($order_id);
				}
				$this->set('order_codes',$order_codes);
			}
		}else{
			throw new Exception("Permission denied");
		}
	}

	function private_sell($id=null){
		$this->_catch_exception_msg(array('OrderResponsesController','_private_sell_impl'),array('id'=>$id));
		$this->set('do_action' , "sell");
		$this->render('private_buy_or_sell');
	}

	function _private_sell_impl($params=array()){
		$params['id'] = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
		$params['id'] = (int)$params['id'];
		if($params['id'] > 0){
			$buy_order = $this->SellOrder->find_private_order_by_id($params['id']);
			if(empty($buy_order)){
				throw new Exception("Permission denied");
			}else{
				$this->set('order',$buy_order);
				$this->set('order_code',$this->OrderCode->find_sell_code_by_order_id($params['id']));
				$p = $this->BuyOrder->find_private_order_by_country_except_client_id(array_keys_value($buy_order,"0.country"),array_keys_value($buy_order,"0.client_id"));
				$this->set('o_orders',$p->getDataArray());
				$this->set('p',$p);
				$order_codes = array();
				foreach($p->getDataArray() as $order){
					$order_id = array_keys_value($order,'0.id');
					$order_codes[$order_id] = $this->OrderCode->find_buy_code_by_order_id($order_id);
				}
				$this->set('order_codes',$order_codes);
			}
		}else{
			throw new Exception("Permission denied");
		}
	}
	
	
	
	
	public function test(){
		pr($this->SellOrder->find_public_order_by_id(140));
	
	
	}
	#send mail for  client
 	function _send_mail_for_client($contact_id,$order_id,$action,$type,$client_id){
 		$list=$this->Client->query("select  confirm_order_number from  contract where  id=$contact_id");
 		$order_number=$list[0][0]['confirm_order_number'];
		extract($this->Client->get_client_all_email($client_id));
		$code_name=$this->Contract->get_order_code($order_id);
		$title='Noticfication Message';
		$content="Dear {$client_name},\n\n\n
Sent by cs@intlcx.com
 ---------------------------------------------------------------------------------------
Noticfication Message\n
Order#   {$order_number}     ( {$code_name} )   has been {$action} by {$type}.\n
Please don't reply this email\n
 ---------------------------------------------------------------------------------------\n\n
Regards,\n
Henry";
		
		foreach ($email  as  $key=>$value){
			if(!empty($value)){
				$this->PhpSendMail->send_mail($value,$title,$content);
			}
		}
		


	}
}

