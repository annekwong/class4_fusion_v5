<?php
App::import('Model','Order.Order');
class SellOrder extends Order {
	var $useTable = false;
//	var $name = "SellOrder";
	var $validate = array(
				'rate' => array(
					'blank' => array(
						'required' => true,
						'rule' => 'notEmpty',
						'message' => 'rate is not blank',
						'last' => true
						),
					'money' => array(
						'rule' => array('float'),
						'required' => true,
						'message' => 'rate error'
						),
					'comparison' => array(
					'required' => true,
					'rule' => array('comparison',">", 0),
					'message' => 'rate must be more than 0'
					)
				),
				'expire_time' => array(
					'blank' => array(
						'required' => true,
						'rule' => 'notEmpty',
						'message' => 'expire time is not blank',
						'last' => true
						)
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
						'message' => 'commit minutes must be more than  0',
						'last' => true
					)
				)
			);

	function is_commit($sell_order,$model = "SellOrder"){
		return array_keys_value($sell_order,"0.is_commit") === null ?  array_keys_value($sell_order,"$model.is_commit") : array_keys_value($sell_order,"0.is_commit");
	}

	function is_private($sell_order,$model = "SellOrder"){
		return array_keys_value($sell_order,"0.is_private") === null ?  array_keys_value($sell_order,"$model.is_private") : array_keys_value($sell_order,"0.is_private");
	}

	function  beforeValidate(){
		if(!$this->is_private($this->data) || !$this->is_commit($this->data)){
			$this->data ['SellOrder'] ['expire_time'] = gmtnow();
		}
		if(empty($this->data ['SellOrder'] ['create_time'])){
			$this->data ['SellOrder'] ['create_time'] = gmtnow();
		}
		$rate = array_keys_value($this->data,"SellOrder.rate",0);
		$this->data ['SellOrder'] ['rate'] = sprintf("%.5f",$rate);
	}

	function beforeSave(){
		$this->data ['SellOrder'] ['commit_minutes'] = (int)array_keys_value($this->data,'SellOrder.commit_minutes');
		$this->data ['SellOrder'] ['update_time'] = gmtnow();
		return true;
	}

	function get_browser_conditions(){
//		return " sell_order.is_private = false  AND sell_order.status=".self::STATUS_READY;
	}

	function find_public_order_except_client_id($client_id,$ext_conditions = '',$order=null){
	
		if(empty($order)){
			$order = 'sell_order.id DESC';
		}
		$conditions = '';
//		if(!empty($client_id)){
//			$conditions = "AND sell_order.client_id <> $client_id";
//		}
		if (!empty($_REQUEST['match_type']) && 'match' == $_REQUEST['match_type'])
		{
			$conditions = ' and (select count(*) from contract where contract_type = 2 and order_id = sell_order.id) > 0';
		}
		elseif (!empty($_REQUEST['match_type']) && 'no_match' == $_REQUEST['match_type'])
		{
			$conditions = ' and (select count(*) from contract where contract_type = 2 and order_id = sell_order.id) = 0';
		}
		else
		{
			
		}
		
		if(!empty($ext_conditions)){
			 $conditions = $conditions . " AND $ext_conditions";
		}

		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		$currPage = empty($_GET['page'])? 1  : $_GET['page'];
		$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
//		$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM sell_order WHERE ".$this->get_browser_conditions()." $conditions");
//		$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
//		if($_SESSION['login_type'] == 3){
//			$results = $this->query(
//			"SELECT sell_order.* ,(select count(1) FROM contract WHERE contract.order_id = sell_order.id AND contract.client_id = '$client_id')  as deal
//				FROM sell_order
//				WHERE ".$this->get_browser_conditions()." $conditions ORDER BY $order LIMIT '$pageSize' OFFSET '$offset'");
//		}else{
//			$results = $this->query(
//			"SELECT sell_order.*, (select count(*) from contract where contract_type = 2 and order_id = sell_order.id) as contract_num
//				FROM sell_order
//				WHERE ".$this->get_browser_conditions()." $conditions ORDER BY $order LIMIT '$pageSize' OFFSET '$offset'");
//		}
        $results = array();
		$page->setDataArray($results);
		return $page;
	}

	function find_private_order($ext_conditions='',$order=null){
		if(empty($order)){
			$order = 'sell_order.id DESC';
		}
		$conditions = '';
		if(!empty($ext_conditions)){
			 $conditions = $conditions . " AND $ext_conditions";
		}
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		$currPage = empty($_GET['page'])? 1  : $_GET['page'];
		$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
//		$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM sell_order WHERE "."is_private = true AND active=true AND status=".self::STATUS_READY." $conditions");
//		$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
//		$results =  $this->query("
//			SELECT * FROM sell_order
//				WHERE "."is_private = true AND active=true AND status=".self::STATUS_READY." $conditions
//				ORDER by $order
//				LIMIT '$pageSize' OFFSET '$offset'
//				");
        $results = array();
	   	$page->setDataArray($results);
		return $page;
	}

	function find_public_order_by_id($id){
//		 $orders =  $this->query("SELECT * FROM sell_order WHERE id=$id AND ".$this->get_browser_conditions());
//		 if(empty($orders)){
//		 	return null;
//		 }else{
//		 	return $orders[0];
//		 }
	}

	
	function find_private_order_by_id($id){
//		$orders =  $this->query("SELECT * FROM sell_order WHERE id=$id AND is_private = true AND active=true AND status=".self::STATUS_READY);
//		if(empty($orders)){
//			return null;
//		}else{
//			return $orders[0];
//		}
	}

	function find_private_order_by_country_except_client_id($country,$client_id){
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		if(!empty($country)){
			$currPage = empty($_GET['page'])? 1  : $_GET['page'];
			$pageSize = empty($_GET['size'])? self::DEFAULT_PAGE_SIZE  : $_GET['size'];
//			$totalrecords = $this->query("SELECT COUNT(*) AS cnt FROM sell_order WHERE  is_private = true AND active=true AND status=".self::STATUS_READY." AND country='$country' AND client_id <> $client_id");
//			$page->setTotalRecords($totalrecords[0][0]['cnt']);//总记录数
			$page->setCurrPage($currPage);//当前页
			$page->setPageSize($pageSize);//页大小
			$currPage = $page->getCurrPage()-1;
			$pageSize = $page->getPageSize();
			$offset=$currPage*$pageSize;
//			$results =  $this->query(
//				"SELECT * FROM sell_order
//					WHERE is_private = true AND active=true AND status=".self::STATUS_READY." AND country='$country'  AND client_id <> $client_id
//					ORDER by create_time DESC
//					LIMIT '$pageSize' OFFSET '$offset'
//					");
            $results = array();
		   	$page->setDataArray($results);
		}
		return $page;
	}


	function find_by_client_id_and_response_cnt($client_id,$conditions,$order=null){
		if(empty($order)){
			$order = 'sell_order.id DESC';
		}
		if(!empty($conditions))
		{
			$conditions=" and $conditions";
		}
		if(empty($client_id)){
			return array();
		}else{
//			return $this->query("
//			SELECT sell_order.*,contract_cnt.cnt as response_cnt,users.name as  user_name FROM sell_order
//					LEFT JOIN (
//						SELECT COUNT(1) AS cnt,contract.order_id
//						FROM contract
//						WHERE contract_type = ". Contract::CONTRACT_TYPE_SELL ."
//						GROUP BY contract.order_id
//					) AS contract_cnt
//					ON (contract_cnt.order_id = sell_order.id)
//
//					left join  users on  users.user_id=sell_order.user_id
//				WHERE sell_order.client_id = $client_id $conditions
//				ORDER by $order
//			");
//			("SellOrder.client_id = $client_id");
		}
	}

	function _get_value($data, $field) {
		return array_keys_value ( $data, "0.$field" ) === null ? array_keys_value ( $data, "SellOrder.$field" ) : array_keys_value ( $data, "0.$field" );
	}

	function is_ready_to_delete($data){
		return (( int ) $this->_get_value ( $data, 'response_cnt' )) == 0;
	}

}

