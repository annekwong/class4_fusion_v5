<?php
class OrderPlacesController extends OrderAppController{
	var $name = 'OrderPlaces';
	var $uses = array();
	var $helpers = array('Order.AppOrderPlaces');
var $components = array('PhpSendMail');
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		 parent::beforeFilter();
	}

	function ajax_regions() {
		Configure::write('debug',0);
		$this->layout = "";
		$order_id = (float) array_keys_value($this->params,'url.order_id');
		$this->set('is_update',array_keys_value($this->params,'url.is_update'));
		$country = array_keys_value($this->params,'url.country');
		if (empty($country)) {
			$this->set ( 'order_codes', array () );
			$this->set ( 'codes', array () );
		} else {
			$this->set ( 'order_codes', array () );
			$this->set('codes',$this->Code->find_code_by_country_name($country));
		}
	}

	function buy($id=null){
		$this->pageTitle = "Place Buy Order";
		$this->set('is_update',false);
		$this->_catch_exception_msg(array('OrderPlacesController','_buy_impl'),array('id' => $id));
		$this->set('table','BuyOrder');
		$this->set('do_action','buy  order');		
		$this->render('place');
	}

	function _buy_impl($params=array()){
		if ($this->RequestHandler->isPost ()) {
			$this->_create_or_update_buy_data ($params);
		} else {
			if(isset($params['id']) && !empty($params['id'])){
				$this->data = $this->BuyOrder->find('first',array('conditions'=>"BuyOrder.id = {$params['id']}"));
				if(empty($this->data) || $this->data['BuyOrder']['client_id'] != $this->Session->read('sst_client_id')){
					throw new Exception("Permission denied");
				}
				$this->set('is_update',true);				
				$this->set('order_codes',$this->OrderCode->find_buy_code_by_order_id($params['id']));
				$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'BuyOrder.country')));
			}else{
				$this->data = $this->BuyOrder->create(array('is_private' => false,'is_commit' => true , 'active' => true,'create_time' => gmtnow(HOUR)));
				$this->set('order_codes',array());
				$this->set('codes',array());
			}
		}
		$this->set('resources',$this->Resource->find_client_ingress($this->Session->read('sst_client_id')));#self::TEST_CLIENT_ID));
		$this->set('countries',$this->Code->find_countries());
	}

	function _create_or_update_buy_data($params=array()) {
		$valid_start_time = true;		
		if(isset($this->data['BuyOrder']['create_time']) &&
				 !empty($this->data['BuyOrder']['create_time']) && 
				 strtotime($this->data['BuyOrder']['create_time']) < (time() - HOUR)){
//				 	throw new Exception("Start Time is too early to place order.");
				 	$this->BuyOrder->create_json_array('',101,"Start Time is too early to place order.");
					$this->Session->write('m',$this->BuyOrder->set_validator());
					$valid_start_time = false;
				 }
			
		if(isset($params['id']) && !empty($params['id'])){
			$id = ( int ) $params ['id'];			
			$rate = $this->data ['BuyOrder'] ['rate']; 			
			
			$this->data = $this->BuyOrder->find('first',array('conditions'=>"BuyOrder.id = {$id}"));
			$db_rate=$this->data ['BuyOrder'] ['rate'];
			$this->data ['BuyOrder'] ['rate'] = $rate;
			$this->data ['BuyOrder'] ['id'] = $id;
			$this->data ['BuyOrder'] ['user_id'] = $_SESSION['sst_user_id'];			 
			
			$country = array_keys_value($this->data,"BuyOrder.country");
					
			if(		$valid_start_time && $country && $this->data &&
					$this->data['BuyOrder']['client_id'] == $this->Session->read('sst_client_id') &&
//					$this->_check_order_codes(OrderCode::ORDER_TYPE_BUY,$country,$this->Session->read('sst_client_id'),$id) &&
					$this->BuyOrder->save ( $this->data )){
						
	
						
//				$this->_update_order_codes($country,$id,OrderCode::ORDER_TYPE_BUY);
				$this->Contract->update_status_by_update_order(Contract::CONTRACT_TYPE_BUY,$id);
												if($rate!=$db_rate){
				$this->update_buy_order_send_mail($id);		#send mail
				
				}
				$this->BuyOrder->create_json_array('',201,"save success! All contracts created by this order will be hold");
				$this->Session->write('m',$this->BuyOrder->set_validator());
				$this->redirect ( array ('id' => $id ) );
			}
			$this->set('is_update',true);
			$this->BuyOrder->create_json_array('',101,"save failure!  or region is exists.");
			$this->Session->write('m',$this->BuyOrder->set_validator());
			$this->set('order_codes',$this->OrderCode->find_buy_code_by_order_id($id));
			$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'BuyOrder.country')));
		}else{
			$this->data['BuyOrder']['client_id'] = $this->Session->read('sst_client_id');#self::TEST_CLIENT_ID;
			$country = array_keys_value($this->data,"BuyOrder.country");
				$this->data ['BuyOrder']['user_id']=$_SESSION['sst_user_id'];
			if(		$valid_start_time && $country &&					
					$this->_check_order_codes(OrderCode::ORDER_TYPE_BUY,$country,$this->Session->read('sst_client_id')) &&
				
					$this->BuyOrder->save ( $this->data )){
				$id = $this->BuyOrder->getlastinsertId ();
				$this->_update_order_codes($country,$id,OrderCode::ORDER_TYPE_BUY);
				$this->BuyOrder->create_json_array('',201,"save success!");
				$this->Session->write('m',$this->BuyOrder->set_validator());
				$this->redirect ( array ('id' => $id ) );
			}
			$this->BuyOrder->create_json_array('',101,"save failure!  or region is exists.");
			$this->Session->write('m',$this->BuyOrder->set_validator());
			$this->set('order_codes',array());
			$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'BuyOrder.country')));
		}
	}

	function _check_order_codes($order_type,$country,$client_id,$order_id=null){
		$regions = array_keys_value($this->data,"Order.Regions");
		$all_code_names = $this->OrderCode->find_all_code_name_by_country_and_client_id($order_type,$country,$client_id,$order_id);
		foreach($regions as $region){
			if(in_array($region,$all_code_names)){
				return false;
			}
		}
		return true;
	}

	function _update_order_codes($country,$order_id,$order_type){
		$regions = array_keys_value($this->data,"Order.Regions");
		$this->OrderCode->deleteAll("order_type = ".$order_type." AND order_id=".$order_id);
		if(!empty($country) && !empty($regions)){
			foreach($regions as $region){
				$codes = $this->Code->find_by_country_and_region($country,$region);
				if(!empty($codes)){
					foreach($codes as $code){
						if(!empty($code)){
							$this->OrderCode->save(
								array("OrderCode"=>array('order_id' => $order_id,
										'code_id' => array_keys_value($code,"0.code_id"),
										'code' => array_keys_value($code,"0.code"),
										'code_name' => array_keys_value($code,"0.name"),
										'country' => array_keys_value($code,"0.country"),
										'order_type' => $order_type,
										'client_id' => $this->Session->read('sst_client_id')
							)));
							$this->OrderCode->id = false;
						}
					}
				}
			}
		}
	}


	function sell($id=null){
		$this->pageTitle = "Place Sell Order";
		$this->set('is_update',false);
		$this->_catch_exception_msg(array('OrderPlacesController','_sell_impl'),array('id' => $id));
		$this->set('table','SellOrder');
		$this->set('do_action','sell ordier');
		$this->render('place');
	}

	
	/**
	 * 
	 * 保存订单
	 * @param $params
	 */
	function _sell_impl($params=array()){
		if ($this->RequestHandler->isPost ()) {
			$this->_create_or_update_sell_data ($params);//更新
		} else {
			if(isset($params['id']) && !empty($params['id'])){
				$this->data = $this->SellOrder->find('first',array('conditions'=>"SellOrder.id = {$params['id']}"));
				if(empty($this->data) || $this->data['SellOrder']['client_id'] != $this->Session->read('sst_client_id')){
					throw new Exception("Permission denied");
				}
				$this->set('order_codes',$this->OrderCode->find_sell_code_by_order_id($params['id']));
				$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'SellOrder.country')));
				$this->set('is_update',true);
			}else{
				$this->data = $this->SellOrder->create(array('is_private' => false,'is_commit' => true , 'active' => true,'create_time' => gmtnow(HOUR)));
				$this->set('order_codes',array());
				$this->set('codes',array());
			}
		}
		$this->set('resources',$this->Resource->find_client_egress($this->Session->read('sst_client_id')));#self::TEST_CLIENT_ID));
		$this->set('countries',$this->Code->find_countries());
	}

	function _create_or_update_sell_data($params=array()) {
		$valid_start_time = true;		
		if(isset($this->data['BuyOrder']['create_time']) &&
				 !empty($this->data['BuyOrder']['create_time']) && 
				 strtotime($this->data['BuyOrder']['create_time']) < (time() - HOUR)){
//				 	throw new Exception("Start Time is too early to place order.");
				 	$this->BuyOrder->create_json_array('',101,"Start Time is too early to place order.");
					$this->Session->write('m',$this->BuyOrder->set_validator());
					$valid_start_time = false;
				 }
				 
		if(isset($params['id']) && !empty($params['id'])){
			//更新sell_order订单
			$id = ( int ) $params ['id'];
			$this->data ['SellOrder'] ['id'] = $id;
			$rate = $this->data ['SellOrder'] ['rate'];#ui rate
			$this->data = $this->SellOrder->find('first',array('conditions'=>"SellOrder.id = {$id}"));#DB data
			$db_rate=$this->data ['SellOrder'] ['rate'];#DB rate
			$this->data ['SellOrder'] ['rate'] = $rate;
			$country = array_keys_value($this->data,"SellOrder.country");
			if(		$valid_start_time && $country && $this->data &&
					$this->data['SellOrder']['client_id'] == $this->Session->read('sst_client_id') &&
//					$this->_check_order_codes(OrderCode::ORDER_TYPE_SELL,$country,$this->Session->read('sst_client_id'),$id) &&
					$this->SellOrder->save ( $this->data )){
						
						
//				$this->_update_order_codes($country,$id,OrderCode::ORDER_TYPE_SELL);
	
				$this->Contract->update_status_by_update_order(Contract::CONTRACT_TYPE_SELL,$id);
				if($rate!=$db_rate){
				$this->update_sell_order_send_mail($id);		#send mail
				
				}
		
				$this->SellOrder->create_json_array('',201,"save success! All contracts created by this order will be hold");
				$this->Session->write('m',$this->SellOrder->set_validator());
				$this->redirect ( array ('id' => $id ) );
			}
			$this->set('is_update',true);
			$this->SellOrder->create_json_array('',101,"save failure! or region is exists.");
			$this->Session->write('m',$this->SellOrder->set_validator());
			$this->set('order_codes',$this->OrderCode->find_sell_code_by_order_id($id));
			$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'SellOrder.country')));
		}else{
			$this->data['SellOrder']['client_id'] = $this->Session->read('sst_client_id');#self::TEST_CLIENT_ID;
			$country = array_keys_value($this->data,"SellOrder.country");
			
			 $this->data['SellOrder']['user_id']=$_SESSION['sst_user_id']; 
			if(		$valid_start_time && $country &&
					$this->_check_order_codes(OrderCode::ORDER_TYPE_SELL,$country,$this->Session->read('sst_client_id')) &&
					$this->SellOrder->save ( $this->data )){
				$id = $this->SellOrder->getlastinsertId ();
				$this->_update_order_codes($country,$id,OrderCode::ORDER_TYPE_SELL);
				$this->SellOrder->create_json_array('',201,"save success!");
				$this->Session->write('m',$this->SellOrder->set_validator());
				$this->redirect ( array ('id' => $id ) );
			}
			$this->set('order_codes',array());
			$this->set('codes',$this->Code->find_code_by_country_name(array_keys_value($this->data,'SellOrder.country')));
			$this->SellOrder->create_json_array('',101,"save failure! or region is exists.");
			$this->Session->write('m',$this->SellOrder->set_validator());
		}

	}
	
	
	
	function update_sell_order_send_mail($order_id){
		$list=$this->Contract->find_contract_order_number_by_order($order_id);
		foreach ($list as  $key =>$value){
		#给自己mail 一个contract 一个mail
		$order_number=$value[0]['confirm_order_number'];
		$this->_send_mail_for_client($order_number,$order_id,'held','seller',$_SESSION['sst_client_id']);
		
		#给交易方mail 一个contract 一个mail
		$this->_send_mail_for_client($order_number,$order_id,'held','seller',$value[0]['client_id']);
		}
	}
	
	
	
	function update_buy_order_send_mail($order_id){
		$list=$this->Contract->find_contract_order_number_by_order($order_id);
		foreach ($list as  $key =>$value){
		#给自己mail 一个contract 一个mail
		$order_number=$value[0]['confirm_order_number'];
		$this->_send_mail_for_client($order_number,$order_id,'held','buyer',$_SESSION['sst_client_id']);
		
		#给交易方mail 一个contract 一个mail
		$this->_send_mail_for_client($order_number,$order_id,'held','buyer',$value[0]['client_id']);
		}
	}
	
		#send mail for  client
 	function _send_mail_for_client($order_number,$order_id,$action,$type,$client_id){
		extract($this->Client->get_client_all_email($client_id));
		$code_name=$this->Contract->get_order_code($order_id);
		$title='Noticfication Message';
		$content="
Dear {$client_name},\n\n\n
Sent by cs@intlcx.com
 ---------------------------------------------------------------------------------------
Noticfication Message\n
Order# {$order_number} ( {$code_name} ) has been {$action} by {$type}.\n
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

