<?php
class Contract extends OrderAppModel {
	var $useTable = 'contract';

	const CONTRACT_TYPE_BUY = 1;
	const CONTRACT_TYPE_SELL = 2;

	const CONTRACT_STATUS_CONTRACT = 1;
	const CONTRACT_STATUS_EXPIRED = 2;
	const CONTRACT_STATUS_HOLD_BY_CREATOR = 3;
	const CONTRACT_STATUS_HOLD_BY_FOLLOW = 4;
	const CONTRACT_STATUS_HOLD_FOR_ORDER_CHANGE = 5;

	var $validate = array (
		'commit_minutes' => array (
			'numeric' => array (
				'on' => 'create',
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => 'commit minute is integer',
				'last' => true ),
			'quantity' => array (
				'on' => 'create',
				'required' => true,
				'allowEmpty' => true,
				'rule' => 'commit_minutes_quantity',
				'message' => 'commit minutes must be less then commit minutes of order',
				'last' => true )
			 ),

		 'resource_id' => array(
				'numeric' => array(
			 		'on' => 'create',
					'required' => true,
					'rule' => 'numeric',
					'message' => 'choice resource',
					'last' => true
					),
				'comparison' => array(
					'on' => 'create',
					'required' => true,
					'rule' => array('comparison',">", 0),
					'message' => 'choice resource',
					'last' => true
				)
			)
		);
		
		
function find_contract_order_number_by_order($order_id){
	return  $this->query("select  confirm_order_number,client_id   from  contract  where    order_id=$order_id");

}		
		
		
		
	function get_order_code($order_id)
    {

//        $code_name = array();
//        $list = $this->query("select  distinct code_name   from  order_code  where order_id={$order_id}");
//        foreach ($list as $key => $value) {
//            if (!empty($list[$key][0]['code_name'])) {
//                array_push($code_name, $list[$key][0]['code_name']);
//            }
//
//        }
//        return implode(",", $code_name);
        return '';

    }
		
	function beforeSave(){
		$this->data ['Contract'] ['update_time'] = gmtnow ();
		return true;
	}
	function commit_minutes_quantity($check) {
//		pr($this->data);
//		pr($check);
		$commit_minutes = ( float ) $check ['commit_minutes'];
		if($this->data['Contract']['contract_type'] == self::CONTRACT_TYPE_BUY){
			$m = new BuyOrder ();
		}else{
			$m = new SellOrder ();
		}
		$order = $m->find_public_order_by_id ( $this->data['Contract']['order_id'] );
		if ($m->is_commit ( $order )) {
			return $commit_minutes <= array_keys_value ( $order, "0.commit_minutes" );
		} else {
			return true;
		}
	}

	function has_bought_this_order($order_id,$client_id=null){
		$ext_condtions = '';
		if(!empty($client_id)){
			$ext_condtions = ' AND client_id ='.$client_id;
		}

		return $this->findCount("contract_type = ".self::CONTRACT_TYPE_BUY ." $ext_condtions AND order_id = $order_id");
	}
	function has_sold_this_order($order_id,$client_id=null){
		$ext_condtions = '';
		if(!empty($client_id)){
			$ext_condtions = ' AND client_id='.$client_id;
		}
		return $this->findCount("contract_type = ".self::CONTRACT_TYPE_SELL ." $ext_condtions AND order_id = $order_id");
	}
	function find_detail_by_id($contract_id,$contract_type){
		$this->query("update contract  set  user_id ={$_SESSION['sst_user_id']}  where contract.id = $contract_id");
		
		if($contract_type == self::CONTRACT_TYPE_BUY){
//			return $this->query("SELECT buy_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id , contract.confirm_order_number as confirm_order_number,users.name as user_name
//			 	FROM buy_order
//					INNER JOIN contract ON (contract.order_id = buy_order.id)
//					left join users on users.user_id=contract.user_id
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_BUY." AND contract.id = $contract_id
//				");
		}else{
//			return $this->query("SELECT sell_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id, contract.confirm_order_number as confirm_order_number,users.name as user_name
//			 	FROM sell_order
//					INNER JOIN contract ON (contract.order_id = sell_order.id)
//					left join users on users.user_id=contract.user_id
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_SELL." AND contract.id = $contract_id
//				ORDER by contract.update_time
//		");
		}
	}

	function find_contract_by_client_id($client_id,$type,$order=null){
		if(empty($order)){
			$order = 'update_time DESC';
		}
		if($type == 'buy'){
//			$buy_type_conditions="buy_order.client_id = $client_id";
			$sell_type_conditions="contract.client_id = $client_id";
		}else{
			$buy_type_conditions="contract.client_id = $client_id";
//			$sell_type_conditions="sell_order.client_id = $client_id";
		}
//		return $this->query("
//			SELECT buy_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id, contract.confirm_order_number as confirm_order_number,users.name as user_name
//			 	FROM buy_order
//					INNER JOIN contract ON (contract.order_id = buy_order.id)
//					left join users on users.user_id=contract.user_id
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_BUY." AND $buy_type_conditions
//			UNION ALL (
//				SELECT sell_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id, contract.confirm_order_number as confirm_order_number,users.name as user_name
//			 	FROM sell_order
//					INNER JOIN contract ON (contract.order_id = sell_order.id)
//					left join users on users.user_id=contract.user_id
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_SELL." AND $sell_type_conditions
//			)
//			ORDER BY $order
//		");
	}

//	function find_buy_contract_by_client_id($client_id,$type){
//		if($type == 'buy'){
//			$type_conditions="buy_order.client_id = $client_id";
//		}else{
//			$type_conditions="contract.client_id = $client_id";
//		}
//		return $this->query("
//			SELECT buy_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id
//			 	FROM buy_order
//					INNER JOIN contract ON (contract.order_id = buy_order.id)
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_BUY." AND $type_conditions
//				ORDER BY contract.update_time
//		");
//	}
//
//	function find_sell_contract_by_client_id($client_id,$type){
//		if($type == 'buy'){
//			$type_conditions="contract.client_id = $client_id";
//		}else{
//			$type_conditions="sell_order.client_id = $client_id";
//		}
//		return $this->query("
//			SELECT sell_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id
//			 	FROM sell_order
//					INNER JOIN contract ON (contract.order_id = sell_order.id)
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_SELL." AND $type_conditions
//				ORDER BY contract.update_time
//		");
//	}

	function find_contract_by_manage($country=null,$order=null){
		if(empty($order)){
			$order = 'update_time DESC';
		}
		$buy_order_conditions="";
		$sell_order_conditions="";
		if(!empty($country)){
//			$buy_order_conditions="AND buy_order.country = '$country'";
//			$sell_order_conditions="AND sell_order.country = '$country'";
		}
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		$currPage = empty($_GET['page'])? 1  : $_GET['page'];
		$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
		$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM contract");
		$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
//		$results =  $this->query("
//			SELECT buy_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id, contract.confirm_order_number as confirm_order_number
//			 	FROM buy_order
//					INNER JOIN contract ON (contract.order_id = buy_order.id)
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_BUY." $buy_order_conditions
//			UNION ALL (
//			SELECT sell_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id, contract.confirm_order_number as confirm_order_number
//			 	FROM sell_order
//					INNER JOIN contract ON (contract.order_id = sell_order.id)
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_SELL." $sell_order_conditions
//			)
//			ORDER BY $order
//			LIMIT '$pageSize' OFFSET '$offset'
//		");
//	   	$page->setDataArray($results);
		return $page;
	}

//	function find_sell_contract_by_manage($conditions){
//		if(!empty($conditions)){
//			$conditions="AND $conditions";
//		}
//		return $this->query("
//			SELECT sell_order.*,
//				contract.create_time AS contract_create_time,contract.update_time AS contract_update_time,
//				contract.status AS contract_status,contract.expire_time AS contract_expire_time,
//				contract.is_commit AS contract_is_commit,contract.commit_minutes AS contract_commit_minutes,
//				contract.client_id AS contract_client_id,contract.contract_type AS contract_type,
//				contract.id AS contract_id
//			 	FROM sell_order
//					INNER JOIN contract ON (contract.order_id = sell_order.id)
//				WHERE contract.contract_type = ".self::CONTRACT_TYPE_SELL." $conditions
//				ORDER BY contract.update_time
//		");
//	}

	function _get_value($data, $field) {
		return array_keys_value ( $data, "0.$field" ) === null ? array_keys_value ( $data, "Contract.$field" ) : array_keys_value ( $data, "0.$field" );
	}

	function do_hold(&$data){
	
		$contract_client_id = $this->_get_value ( $data, 'contract_client_id' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		
		$data ['0'] ['user_id'] = $_SESSION['sst_user_id'];
		if((int)$contract_client_id == (int)$_SESSION['sst_client_id']){
			$data ['0'] ['contract_status'] = self::CONTRACT_STATUS_HOLD_BY_FOLLOW;
		}elseif((int)$client_id == (int)$_SESSION['sst_client_id']){
			$data ['0'] ['contract_status'] = self::CONTRACT_STATUS_HOLD_BY_CREATOR;
			
		}else{
			throw new Exception("非法操作");
		}
		$d = array('Contract'=>array('id'=>array_keys_value($data,"0.contract_id"),'status'=>array_keys_value($data,"0.contract_status")));
		return $this->save ( $d );
	}
	function do_contract(&$data){
		$status = ( int ) $this->_get_value ( $data, 'contract_status' );
		$contract_client_id = $this->_get_value ( $data, 'contract_client_id' );
		$client_id = $this->_get_value ( $data, 'client_id' );
			$data ['0'] ['user_id'] = $_SESSION['sst_user_id'];
		if($status === self::CONTRACT_STATUS_HOLD_BY_CREATOR && (int)$client_id = (int)$_SESSION['sst_client_id']){
			$data ['0'] ['contract_status'] = self::CONTRACT_STATUS_CONTRACT;
		}elseif(($status === self::CONTRACT_STATUS_HOLD_BY_FOLLOW && (int)$contract_client_id == (int)$_SESSION['sst_client_id']) ||
			   ($status === self::CONTRACT_STATUS_HOLD_FOR_ORDER_CHANGE && (int)$contract_client_id == (int)$_SESSION['sst_client_id'])){
			$data ['0'] ['contract_status'] = self::CONTRACT_STATUS_CONTRACT;
		}else{
			throw new Exception("非法操作");
		}
		$d = array('Contract'=>array('id'=>array_keys_value($data,"0.contract_id"),'status'=>array_keys_value($data,"0.contract_status")));
		return $this->save ( $d );
	}
	
	function do_delete(&$data){
		$id = array_keys_value($data,"0.contract_id");
			$data ['0'] ['user_id'] = $_SESSION['sst_user_id'];
		$data = array();
		return $this->del($id);
	}

	function is_ready_to_hold($data){
		$status = ( int ) $this->_get_value ( $data, 'contract_status' );
		$is_private = ( int ) $this->_get_value ( $data, 'is_private' );
		return $status === self::CONTRACT_STATUS_CONTRACT && !$is_private;
	}


	function is_ready_to_contract($data){
		$status = ( int ) $this->_get_value ( $data, 'contract_status' );
		$contract_client_id = $this->_get_value ( $data, 'contract_client_id' );
		$client_id = $this->_get_value ( $data, 'client_id' );
		return ($status === self::CONTRACT_STATUS_HOLD_BY_CREATOR && (int)$client_id == (int)$_SESSION['sst_client_id']) ||
			   ($status === self::CONTRACT_STATUS_HOLD_BY_FOLLOW && (int)$contract_client_id == (int)$_SESSION['sst_client_id']) ||
			   ($status === self::CONTRACT_STATUS_HOLD_FOR_ORDER_CHANGE && (int)$contract_client_id == (int)$_SESSION['sst_client_id']);
	}
	
	function is_ready_to_delete($data){
		return true;
	}


	function has_contract($type,$order_id,$client_id){
		$cnt =  $this->query("SELECT count(*) from contract where contract_type = $type and client_id=$client_id and order_id = $order_id");
		if(empty($cnt)){
			return false;
		}else{
			return array_keys_value($cnt,'0.0.count') > 0;
		}
	}

	function is_ready_to_private_contract($type,$order,$o_order){
		return !($this->has_contract($type,array_keys_value($o_order,'0.id'),array_keys_value($order,'0.client_id')) ||
				$this->has_contract($type,array_keys_value($order,'0.id'),array_keys_value($o_order,'0.client_id')));
	}

	function find_sell_contract_by_order_id($order_id){
		return $this->findAll("contract_type = ".self::CONTRACT_TYPE_SELL . " AND order_id = ".$order_id);
	}

	function find_buy_contract_by_order_id($order_id){
		return $this->findAll("contract_type = ".self::CONTRACT_TYPE_BUY . " AND order_id = ".$order_id);
	}

	function update_status_by_update_order($type,$order_id){
		$contracts = $this->findAll("contract_type = ".$type . " AND order_id = ".$order_id);

//		pr($contracts);
//更新order对应的所有合同
		foreach($contracts as $contract){
			$t = $this->query("select nextval('contract_confirm_order_number_seq'::regclass) as number");
			$d = array('Contract'=>
				array(
					'id'=>array_keys_value($contract,"Contract.id"),
					'status'=> self::CONTRACT_STATUS_HOLD_FOR_ORDER_CHANGE,
					'confirm_order_number' => $t[0][0]['number']
				)				 
			);
			
			$this->id = false;
			$this->save ( $d );
		pr("update  public  succ");
		}
	}
}

