<?php
App::import('Model','Order.OrderResponse');
App::import ( 'Model', 'Order.SellOrder' );
App::import ( 'Model', 'Order.Contract' );
class SellOrderResponse extends OrderResponse {
	const RESPONSE_STATUS_CREATE = 0;
	const RESPONSE_STATUS_SELLER_CONFIRM = 1;
	const RESPONSE_STATUS_CONTRACT = 2;

	const RESPONSE_STATUS_EXPIRED = 100;
	const RESPONSE_STATUS_SELLER_REJECT = 101;
	const RESPONSE_STATUS_BUYEE_CANCEL = 102;

	var $useTable = false;
//	var $name = "SellOrderResponse";
//	var $uses = array ("Order.SellOrder" );
	var $belongsTo = array("Order.SellOrder");
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
				'message' => 'commit minutes must be less then commit minutes',
				'last' => true )
			 ),
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

	function commit_minutes_quantity($check) {
		$check = ( float ) $check ['commit_minutes'];
		$m = new SellOrder ();
		$buy_order = $m->find_public_order_by_id ( $this->data ['SellOrderResponse'] ['sell_order_id'] );
		if ($m->is_commit ( $buy_order )) {
			return $check <= array_keys_value ( $buy_order, "0.commit_minutes" );
		} else {
			return true;
		}
	}

	function beforeSave() {
		$this->data ['SellOrderResponse'] ['update_time'] = gmtnow ();
		return true;
	}

	function _get_value($data, $field) {
		return array_keys_value ( $data, "0.$field" ) === null ? array_keys_value ( $data, "SellOrderResponse.$field" ) : array_keys_value ( $data, "0.$field" );
	}

	function is_ready_to_confirm($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return $status === self::RESPONSE_STATUS_CREATE && (int)$client_id != (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_reject($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return ($status === self::RESPONSE_STATUS_CREATE || $status === self::RESPONSE_STATUS_SELLER_CONFIRM) && (int)$client_id != (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_cancel($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return ($status === self::RESPONSE_STATUS_CREATE || $status === self::RESPONSE_STATUS_SELLER_CONFIRM ) && (int)$client_id == (int)$_SESSION['sst_client_id'];
	}

	function is_ready_to_contract($data) {
		$status = ( int ) $this->_get_value ( $data, 'status' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return $status === self::RESPONSE_STATUS_SELLER_CONFIRM && (int)$client_id == (int)$_SESSION['sst_client_id'];
	}

	function do_confirm(&$data) {
		$data ['SellOrderResponse'] ['status'] = self::RESPONSE_STATUS_SELLER_CONFIRM;
		return $this->save ( $data );
	}

	function do_reject(&$data) {
		$data ['SellOrderResponse'] ['status'] = self::RESPONSE_STATUS_SELLER_REJECT;
		return $this->save ( $data );
	}

	function do_cancel(&$data) {
		$data ['SellOrderResponse'] ['status'] = self::RESPONSE_STATUS_BUYEE_CANCEL;
		return $this->save ( $data );
	}

	function do_contract(&$data) {
		$data ['SellOrderResponse'] ['status'] = self::RESPONSE_STATUS_CONTRACT;
		$this->begin();
		if($this->save ( $data )){
			$d = array("Contract" => array(
			'contract_type' => Contract::CONTRACT_TYPE_SELL,'order_id' => $data['SellOrderResponse']['sell_order_id'],
			'order_response_id' => $data['SellOrderResponse']['id'] , 'status' => Contract::CONTRACT_STATUS_CONTRACT,
			'is_commit' => $data['SellOrderResponse']['is_commit'] , 'commit_minutes' => $data['SellOrderResponse']['commit_minutes'],
			'create_time' => gmtnow(),'update_time' => gmtnow(),'expire_time'=>$data['SellOrderResponse']['expire_time'],
			'client_id' => $data['SellOrderResponse']['client_id'],'is_private' => false
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

	/**
	 * @param integer $client_id
	 * @param integer $order_id
	 */
	function has_sold_this_order($order_id,$client_id){
//		$cnt =  $this->query("SELECT count(*) from sell_order_response where client_id=$client_id and sell_order_id = $order_id");
//		if(empty($cnt)){
//			return false;
//		}else{
//			return array_keys_value($cnt,'0.0.count') > 0;
//		}
	}

	function find_by_id($id) {
		if (empty ( $id )) {
			return array ();
		} else {
			$responses = $this->find ( "SellOrderResponse.id = $id" );
			if (empty ( $responses )) {
				return array ();
			} else {
				return $responses;
			}
		}
	}

	function find_by_sell_order_id($sell_order_id) {
		if (empty ( $sell_order_id )) {
			return array ();
		} else {
			return $this->findAll ( "SellOrderResponse.sell_order_id = $sell_order_id" );
		}
	}

	function find_orders_by_client_id($client_id){
		if (empty ( $client_id )) {
			return array ();
		} else {
			return $this->findAll ( "SellOrderResponse.client_id = $client_id" );
		}
	}

	function find_orders_for_manage($conditions=null){
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		$currPage = empty($_GET['page'])? 1  : $_GET['page'];
		$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
//		$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM sell_order_response");
//		$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$results =  $this->findAll($conditions,null,'SellOrderResponse.create_time DESC', "'$pageSize' OFFSET '$offset'");
	   	$page->setDataArray($results);
		return $page;
	}

}

