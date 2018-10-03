<?php
class OrderContractsController extends OrderAppController{
	var $name = 'OrderContracts';
	var $uses = array("Order.Contract");
	var $helpers = array('Order.AppOrderContracts','Order.AppOrderBrowsers');
var $components = array('PhpSendMail');
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		parent::beforeFilter();

	}

	
	#hold住order
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
		if(!in_array($action,array('hold','contract','delete'))){
			throw new Exception("Permission denied");
		}
		
		$m = new Contract();
		$method = "do_$action";
		$contract = $contract[0];
		if($m->{$method}($contract)){
			if($action=='hold'){$action='held';}
			if($action=='contract'){$action='confirmed';}
			if($action=='delete'){$action='cancelled';}
			if($_SESSION['do_action']=='buy'){
			$do_action="buyer";
			}else{
			$do_action="seller";
			}
			
	
					$this->_send_mail_for_client(array_keys_value($this->params,"url.confirm_order_number"),array_keys_value($this->params,"url.id"),$action,$do_action,array_keys_value($this->params,"url.contract_client_id"));
					$this->_send_mail_for_client(array_keys_value($this->params,"url.confirm_order_number"),array_keys_value($this->params,"url.id"),$action,$do_action,array_keys_value($this->params,"url.client_id"));
			if(empty($contract)){
				echo 'do_delete';
				exit();	
			}else{
				
				
				$this->set('contract',$contract);
			}
		}else{
			throw new Exception("Save fail");
		}
	}

	function _render_contract_codes($contracts){
		$order_codes = array();
		foreach($contracts as $contract){
			$order_id = array_keys_value($contract,'0.id');
			if($contract[0]['contract_type'] == Contract::CONTRACT_TYPE_BUY){
				$order_codes[array_keys_value($contract,'0.contract_id')] = $this->OrderCode->find_buy_code_by_order_id($order_id);
			}else{
				$order_codes[array_keys_value($contract,'0.contract_id')] = $this->OrderCode->find_sell_code_by_order_id($order_id);
			}
		}
		$this->set('order_codes',$order_codes);
	}
	function manage($do_action='All'){
		$this->pageTitle = "All Confirmed Orders";
		$d = empty($this->data) ? array() : $this->data;
		$p = $this->Contract->find_contract_by_manage(
			array_keys_value($d,"filter.country"),
			$this->_order_condtions(array('contract_id','country','rate','contract_create_time','contract_update_time','contract_expire_time'))
			);
		$this->set('p',$p);	
		$this->set('do_action' ,$do_action);	
		$this->_render_contract_codes($p->getDataArray());
		$this->render('manage_contract');
	}

	function buy(){
		$this->pageTitle = "Confirmed Buy Order";
		$this->set('do_action','buy');
		$contracts = $this->Contract->find_contract_by_client_id(
				$this->Session->read('sst_client_id'),'buy',
				$this->_order_condtions(array('contract_id','country','rate','contract_create_time','contract_update_time','contract_expire_time','user_name'))
				);
		$this->set('contracts',$contracts);
		$this->_render_contract_codes($contracts);
		$this->set('do_action' , "buy");
		$_SESSION['do_action']='buy';
		$this->render('contract');
	}

	function sell(){
		$this->pageTitle = "Confirmed Sell Order";
		$this->set('do_action','sell');
		$contracts = $this->Contract->find_contract_by_client_id(
				$this->Session->read('sst_client_id'),'sell',
				$this->_order_condtions(array('contract_id','country','rate','contract_create_time','contract_update_time','contract_expire_time'))
				);
		$this->set('contracts',$contracts);
		$this->_render_contract_codes($contracts);
		$this->set('do_action' , "sell");
			$_SESSION['do_action']='sell';
		$this->render('contract');
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

