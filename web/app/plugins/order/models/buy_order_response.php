<?php
App::import ( 'Model', 'Order.OrderResponse' );
App::import ( 'Model', 'Order.BuyOrder' );
App::import ( 'Model', 'Order.Contract' );
class BuyOrderResponse extends OrderResponse {
	const RESPONSE_STATUS_CREATE = 0;
	const RESPONSE_STATUS_BUYER_CONFIRM = 1;
	const RESPONSE_STATUS_CONTRACT = 2;

	const RESPONSE_STATUS_EXPIRED = 100;
	const RESPONSE_STATUS_BUYER_REJECT = 101;
	const RESPONSE_STATUS_SELLEE_CANCEL = 102;
	var $useTable = false;
	var $name = "BuyOrderResponse";
	var $uses = array ("Order.BuyOrder" );
	var $belongsTo = array("Order.BuyOrder",'Resource');
	var $validate = array (
		'commit_minutes' => array (
			'numeric' => array (
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => 'commit minute is integer',
				'last' => true ),
			'quantity' => array (
				'required' => true,
				'allowEmpty' => true,
				'rule' => 'commit_minutes_quantity',
				'message' => 'commit minutes must be less then commit minutes of order',
				'last' => true )
			 ),
//		'expire_time' => array (
//			'v' => array (
//				'required' => true,
//				'allowEmpty' => true,
//				'rule' => 'expire_time_validte',
//				'message' => 'expire time must be less then expire time of order',
//				'last' => true )
//			 ),
		 'resource_id' => array(
				'numeric' => array(
					'required' => true,
					'rule' => 'numeric',
					'message' => 'choice resource',
					'last' => true
					),
				'comparison' => array(
					'required' => true,
					'rule' => array('comparison',">", 0),
					'message' => 'choice resource',
					'last' => true
				)
			)
		);

//	function expire_time_validte($check){
//		$check = $this->datetime_from_string($check ['expire_time']);
//		$m = new BuyOrder ();
//		$buy_order = $m->find_public_order_by_id ( $this->data ['BuyOrderResponse'] ['buy_order_id'] );
//		if ($m->is_commit ( $buy_order ) && $m->is_private($buy_order)) {
//			return $check <= $this->datetime_from_string(array_keys_value ( $buy_order, "0.commit_minutes" ));
//		} else {
//			return true;
//		}
//	}

	function commit_minutes_quantity($check) {
		$check = ( float ) $check ['commit_minutes'];
		$m = new BuyOrder ();
		$buy_order = $m->find_public_order_by_id ( $this->data ['BuyOrderResponse'] ['buy_order_id'] );
		if ($m->is_commit ( $buy_order ) && $m->is_private($buy_order)) {
			return $check <= array_keys_value ( $buy_order, "0.commit_minutes" );
		} else {
			return true;
		}
	}

	/**
	 * @param integer $client_id
	 * @param integer $order_id
	 */
	function has_bought_this_order($order_id,$client_id){
//		$cnt =  $this->query("SELECT count(*) from buy_order_response where client_id=$client_id and buy_order_id = $order_id");
		if(empty($cnt)){
			return false;
		}else{
			return array_keys_value($cnt,'0.0.count') > 0;
		}
	}

	function beforeSave() {
		$this->data ['BuyOrderResponse'] ['update_time'] = gmtnow ();
		return true;
	}

	function _get_value($data, $field) {
		return array_keys_value ( $data, "0.$field" ) === null ? array_keys_value ( $data, "BuyOrderResponse.$field" ) : array_keys_value ( $data, "0.$field" );
	}

	function is_ready_to_confirm($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return $status === self::RESPONSE_STATUS_CREATE && (int)$client_id != (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_reject($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return ($status === self::RESPONSE_STATUS_CREATE || $status === self::RESPONSE_STATUS_BUYER_CONFIRM) && (int)$client_id != (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_cancel($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return ($status === self::RESPONSE_STATUS_CREATE || $status === self::RESPONSE_STATUS_BUYER_CONFIRM ) && (int)$client_id == (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_contract($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return $status === self::RESPONSE_STATUS_BUYER_CONFIRM && (int)$client_id == (int)$_SESSION['sst_client_id'];
		return true;
	}

	function do_confirm(&$data) {
		$data ['BuyOrderResponse'] ['status'] = self::RESPONSE_STATUS_BUYER_CONFIRM;
		return $this->save ( $data );
	}

	function do_reject(&$data) {
		$data ['BuyOrderResponse'] ['status'] = self::RESPONSE_STATUS_BUYER_REJECT;
		return $this->save ( $data );
	}

	function do_cancel(&$data) {
		$data ['BuyOrderResponse'] ['status'] = self::RESPONSE_STATUS_SELLEE_CANCEL;
		return $this->save ( $data );
	}

	function do_contract(&$data) {
		$data ['BuyOrderResponse'] ['status'] = self::RESPONSE_STATUS_CONTRACT;
		$this->begin();
		if($this->save ( $data )){
			$d = array("Contract" => array(
			'contract_type' => Contract::CONTRACT_TYPE_BUY,'order_id' => $data['BuyOrderResponse']['buy_order_id'],
			'order_response_id' => $data['BuyOrderResponse']['id'] , 'status' => Contract::CONTRACT_STATUS_CONTRACT,
			'is_commit' => $data['BuyOrderResponse']['is_commit'] , 'commit_minutes' => $data['BuyOrderResponse']['commit_minutes'],
			'create_time' => gmtnow(),'update_time' => gmtnow(),'expire_time'=>$data['BuyOrderResponse']['expire_time'],
			'client_id' => $data['BuyOrderResponse']['client_id'],'is_private' => false
			));
			$c = new Contract();
			if($c->save($d)){
				$this->commit();
				return true;
			}else{
				$this->rollback();
				return false;
			}
		}else{
			$this->rollback();
			return false;
		}
	}

	function find_by_id($id) {
		if (empty ( $id )) {
			return array ();
		} else {
			$responses = $this->find ( "BuyOrderResponse.id = $id" );
			if (empty ( $responses )) {
				return array ();
			} else {
				return $responses;
			}
		}
	}

	function find_by_buy_order_id($buy_order_id) {
		if (empty ( $buy_order_id )) {
			return array ();
		} else {
			return $this->findAll ( "BuyOrderResponse.buy_order_id = $buy_order_id" );
		}
	}

	function find_orders_by_client_id($client_id){
		if (empty ( $client_id )) {
			return array ();
		} else {
			return $this->findAll ( "BuyOrderResponse.client_id = $client_id" );
		}
	}

	function find_orders_for_manage($conditions=null){
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		$currPage = empty($_GET['page'])? 1  : $_GET['page'];
		$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
//		$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM buy_order_response");
//		$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$results =  $this->findAll($conditions,null,'BuyOrderResponse.create_time DESC', "'$pageSize' OFFSET '$offset'");
	   	$page->setDataArray($results);
		return $page;
	}

}

