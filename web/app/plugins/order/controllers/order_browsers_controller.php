<?php
class OrderBrowsersController extends OrderAppController{
	var $name = 'OrderBrowsers';
	var $uses = array();
	var $helpers = array('Order.AppOrderBrowsers');
function  index()
	{
		$this->redirect('buy');
	}
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
       parent::beforeFilter();
	}
	
	function getOrderUser()
	{
		$return = array();
		$user_arr = $this->BuyOrder->query("select * from order_user where status = 3 and client_id is not null");
		if (!empty($user_arr))
		{
			foreach ($user_arr as $k=>$v)
			{
				$return[$v[0]['client_id']] = $v[0]['name'];
			}
		}
		return $return;
	}
	

	function buy($orderid=''){
		if(isset($orderid)&&!empty($orderid)){
			$this->order_detail($orderid);
		}else{
		$id=isset($_GET['id']);
		$filter=
		$this->pageTitle = "Select Country";
		//var_dump($this->_filter_conditions(array('country'=>'buy_order.inordercode_country','code_name'=>'buy_order.inordercode_code_name','acd','asr')));
		$p = $this->BuyOrder->find_public_order_except_client_id(
				$this->Session->read('sst_client_id'),
				$this->_filter_conditions(array('country'=>'buy_order.inordercode_country','code_name'=>'buy_order.inordercode_code_name','code'=>'buy_order.inordercode_code','acd','asr','client_id'=>'buy_order.client_id','start_date'=>'buy_order.rt_create_time','end_date'=>'buy_order.lt_create_time','active'=>'buy_order.active','purged'=>'buy_order.purged'),'buy_order'),
				$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time','active','purged'),'buy_order')
		);
		$this->set('p',$p);
		$orders = $p->getDataArray();
		$this->set('orders',$orders);
		$order_codes = array();
		foreach($orders as $order){
			$order_id = array_keys_value($order,'0.id');
			$order_codes[$order_id] = $this->OrderCode->find_buy_code_by_order_id($order_id);
		}
		$this->set('order_codes',$order_codes);
		$order_user_arr = $this->getOrderUser();
		$this->set('order_user', $order_user_arr);
		$this->set('do_action','buy');
		$this->set('is_private',false);
		$this->render("order");
		}
	}
	
	
	function ajax_codename_options(){
		Configure::write('debug',0);
		$this->layout='ajax';
		$this->autoRender=FALSE;
		$this->autoLayout=FALSE;
		$return = array();
		$country = !empty($_REQUEST['country']) ? $_REQUEST['country'] : '';
		$codename_arr = $this->BuyOrder->query("select name,code from code where country = '{$country}'");
		if (!empty($codename_arr))
		{
			foreach ($codename_arr as $k=>$v)
			{
				$return[$v[0]['name']] = $v[0]['name'];
			}
		}
		return json_encode($return);
	}
	
	function ajax_code_options(){
		Configure::write('debug',0);
		$this->layout='ajax';
		$this->autoRender=FALSE;
		$this->autoLayout=FALSE;
		$return = array();
		$codename = !empty($_REQUEST['codename']) ? $_REQUEST['codename'] : '';
		$code_arr = $this->BuyOrder->query("select code from code where name = '{$codename}'");
		if (!empty($code_arr))
		{
			foreach ($code_arr as $k=>$v)
			{
				$return[$v[0]['code']] = $v[0]['code'];
			}
		}
		return json_encode($return);
	}
	
	function sell($orderid=''){
		if(isset($orderid)&&!empty($orderid)){
			$this->order_detail($orderid);
		}else{
		$this->pageTitle = "Select Country";
		$p = $this->SellOrder->find_public_order_except_client_id(
			$this->Session->read('sst_client_id'),
			$this->_filter_conditions(array('country'=>'sell_order.inordercode_country','code_name'=>'sell_order.inordercode_code_name','code'=>'sell_order.inordercode_code','acd','asr','client_id'=>'sell_order.client_id','start_date'=>'sell_order.rt_create_time','end_date'=>'sell_order.lt_create_time','is_select'=>'sell_order.is_select','active'=>'sell_order.active','purged'=>'sell_order.purged'),'sell_order'),
			$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time','is_select','active','purged'),'sell_order')
		);
		$this->set('p',$p);
		$orders = $p->getDataArray();
		$this->set('orders',$orders);
		$order_codes = array();
		foreach($orders as $order){
			$order_id = array_keys_value($order,'0.id');
			$order_codes[$order_id] = $this->OrderCode->find_sell_code_by_order_id($order_id);
			
		}
		$this->set('order_codes',$order_codes);
		$order_user_arr = $this->getOrderUser();
		$this->set('order_user', $order_user_arr);
		$this->set('do_action','sell');
		$this->set('is_private',false);
		$this->render("order");
		}
	}

	function private_buy(){
		$this->pageTitle = "Search Private Buy";
		$p = $this->BuyOrder->find_private_order(
			$this->_filter_conditions('buy_order',array('country','acd','asr')),
			$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time'),'buy_order')
		);
		$this->set('p',$p);
		$orders = $p->getDataArray();
		$this->set('orders',$orders);
		$order_codes = array();
		foreach($orders as $order){
			$order_id = array_keys_value($order,'0.id');
			$order_codes[$order_id] = $this->OrderCode->find_buy_code_by_order_id($order_id);
		}
		$this->set('order_codes',$order_codes);
		$this->set('do_action','buy');
		$this->set('is_private',true);
		$this->render("private_order");

	}

	function private_sell(){
		$this->pageTitle = "Search Private Sell";
		$p = $this->SellOrder->find_private_order(
			$this->_filter_conditions('sell_order',array('country','acd','asr')),
			$this->_order_condtions(array('id','country','rate','acd','asr','create_time','expire_time'),'buy_order')
		);
		$this->set('p',$p);
		$orders = $p->getDataArray();
		$this->set('orders',$orders);
		$order_codes = array();
		foreach($orders as $order){
			$order_id = array_keys_value($order,'0.id');
			$order_codes[$order_id] = $this->OrderCode->find_sell_code_by_order_id($order_id);
		}
		$this->set('order_codes',$order_codes);
		$this->set('do_action','sell');
		$this->set('is_private',true);
		$this->render("private_order");
	}

//	==============================

/*	function _filter_conditions($table,$array){
		if(!is_array($array)){
			$array = array($array);
		}
		$returning = array();
		if(empty($this->data)){
			return null;
		}
		foreach($array as $column){
			$method = "_filter_".trim(strtolower("$column"));
			if(method_exists($this,$method)){
				$v = $this->{$method}($table);
				if($v){
					$returning[] = $v;
				}
			}else{
				trigger_error("$method was not defined",E_USER_ERROR);
			}
		}
		return join(" AND ",$returning);
	}*/

/*	function _filter_country($table){
		$country = (string)array_keys_value($this->data,"filter.country");
		$country = trim($country);
		if(!empty($country)){
			return "$table.country = '$country'";
		}
		return null;
	}*/

	public function changeaction(){
		if (!$_SESSION['role_menu']['Exchange Manage']['order_browsers']['model_w'])
		{
			$this->redirect_denied();
		}
		$this->layout='ajax';
		Configure::write('debug', 0);
		$this->autoRender = FALSE;
		$this->autoLayout = FALSE;
		$order_type=$_REQUEST['order_type'];
		$id = intval($_REQUEST['id']);
		$action_type = $_REQUEST['action_type'];
		$action = $_REQUEST['action'];
		$select_val = intval($_REQUEST['select_val']);
		if($order_type=='buy'){
			$qs = $this->BuyOrder->query("update buy_order set {$action_type} = '{$action}' where id = {$id}");
		}else{
			if(isset($action)&&!empty($action)){
				//echo "update sell_order set {$action_type} = '{$action}' where id = {$id}";
//				$qs = $this->BuyOrder->query("update sell_order set {$action_type} = '{$action}' where id = {$id}");
			}else{
				//echo "update sell_order set is_select = {$select_val} where id = {$id}";
//				$qs = $this->BuyOrder->query("update sell_order set is_select = {$select_val} where id = {$id}");
				
			}
		}
		//var_dump($qs);

		if ($qs !== false){
			require_once(APP.'vendors/mail_order_change.php');
			echo "true";
		} else {
			echo "false";
		}
	}
	
	function order_detail($orderid){
		$this->pageTitle = "View order detail";
		$id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
		$do_action = empty($this->params['action']) ? 'buy' : $this->params['action'];
		$this->set('do_action',$do_action);
		
		if (!empty($id))
		{		
			$order_type = $do_action == 'buy' ? 1 : 2;
			$order_info = $this->BuyOrder->query("select * from {$do_action}_order where id = {$id}");
			if (!empty($order_info[0][0]['id']))
			{
				$code_name_arr = array();
				$code_arr = array();
				$order_code = $this->BuyOrder->query("select * from order_code where order_type = {$order_type} and order_id = {$id}");
				if (!empty($order_code))
				{
					foreach ($order_code as $k=>$v)
					{
						if (!in_array($v[0]['code_name'], $code_name_arr))
						{
							$code_name_arr[] = $v[0]['code_name'];
						}
						
						$code_arr[] = $v[0]['code'];
					}
				}
				
				
				$order_info[0][0]['code_name_true'] = implode(",", $code_name_arr);
				$order_info[0][0]['code_name'] = substr($order_info[0][0]['code_name_true'], 0, 15) . '...';
				$order_info[0][0]['code_true'] = implode(",", $code_arr);
				$order_info[0][0]['code'] = substr($order_info[0][0]['code_true'], 0, 15) . '...';
			}
			$this->set('code', $code_arr);
			$this->set('code_name', $code_name_arr);
			$this->set('order_arr', $order_info[0][0]);
			$this->render('order_detail');
		}
	}

}

