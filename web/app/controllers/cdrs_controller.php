<?php
class CdrsController extends AppController{
	var $helper = array('html','javascript');
	
	
	
	
	
	
	
	
//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		
   parent::beforeFilter();
	}

	
	
	public function cdrs_list(){
		if (!empty($_REQUEST['search'])){
			
			$currPage = 1;
			$pageSize = 100;
			$search = null;
			$last_conditons = '';
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
		
		
			$table_name = 'reseller_cost';
			$table = 'reseller';
			$search_id = 'reseller_id';
			$reseller_or_client_id = '';
			$client_or_reseller = empty($_REQUEST['ct'])?0:$_REQUEST['ct'];
			$last_conditons .= "&ct=$client_or_reseller";
			if ($client_or_reseller == 0) {
				$table_name = 'client_cost';
				$table='client';
				$search_id='client_id';
			} else if ($client_or_reseller == 1){
				$table_name = 'account_cost';
				$table='card';
				$search_id='card_id';
			}
			$conditions = '';
			if (!empty($_REQUEST['rc'])){
				if ($client_or_reseller != 1){
					$ids = $this->Cdr->query("select $search_id as id from $table where name = '{$_REQUEST['rc']}'");
					$reseller_or_client_id = $ids[0][0]['id'];
					$conditions .= " and cost.$search_id = '{$reseller_or_client_id}'";
				} else {
					$ids = $this->Cdr->query("select $search_id as id from $table where card_number = '{$_REQUEST['rc']}'");
					$reseller_or_client_id = $ids[0][0]['id'];
					$conditions .= " and cost.account_id = '{$reseller_or_client_id}'";
				}
				$last_conditons .= "&rc=".$_REQUEST['rc'];
			}
			
			
			if (!empty($_REQUEST['st'])){
				$conditions .= " and (SELECT TIMESTAMP WITH TIME ZONE 'epoch' + cdr.start_time_of_date::integer *INTERVAL '1 second')  >= '{$_REQUEST['st']}'";
				$last_conditons .= "&st=".$_REQUEST['st'];
			}
			
			if (!empty($_REQUEST['et'])){
				$conditions .= " and (SELECT TIMESTAMP WITH TIME ZONE 'epoch' + cdr.start_time_of_date::integer *INTERVAL '1 second')  <= '{$_REQUEST['et']}'";
				$last_conditons .= "&et=".$_REQUEST['et'];
			}
			
			if (!empty($_REQUEST['rt'])){
				$conditions .= " and cost.rate_table_id = '{$_REQUEST['rt']}'";
				$last_conditons .= "&rt=".$_REQUEST['rt'];
			}
			
			if (!empty($_REQUEST['tp'])) {
				$lim = 1;
				if ($_REQUEST['tp'] == 'orig')$lim=0;
				$conditions .= " and cost.type = '$lim'";
				$last_conditons .= "&tp=".$_REQUEST['tp'];
			}
			
			if (!empty($_REQUEST['rs'])) {
				if ($_REQUEST['rs'] == 'success') $conditions .= " and cdr.answer_time_of_date is not null";
				else $conditions .= " and cdr.answer_time_of_date is null";
				$last_conditons .= "&rs=".$_REQUEST['rs'];
			}
			
			if (!empty($_REQUEST['cus'])) {
				$conditions .= " and cdr.release_cause_from_protocol_stack = '{$_REQUEST['cus']}'";
				$last_conditons .= "&cus=".$_REQUEST['cus'];
			}
			
			if (!empty($_REQUEST['cost'])) {
				if ($_REQUEST['cost'] == 'nonzero') $conditions .= " and cost.cost > '0'";
				else $conditions .= " and cost.cost = '0'";
				
				$last_conditons .= "&cost=".$_REQUEST['cost'];
			}
			
			if (!empty($_REQUEST['dst'])) {
				$conditions .= " and cdr.origination_destination_number like '%{$_REQUEST['dst']}%'";
				$last_conditons .= "&dst=".$_REQUEST['dst'];
			}
			
			if (!empty($_REQUEST['src'])) {
				$conditions .= " and cdr.origination_source_number like '%{$_REQUEST['src']}%'";
				$last_conditons .= "&src=".$_REQUEST['src'];
			}
			
			if (!empty($_REQUEST['stat'])) {
				if ($_REQUEST['stat'] == 'suc') $conditions .= " and cost.bill_result = '1'";
				else $conditions .= " and cost.bill_result = '2'";
				
				$last_conditons .= "&stat=".$_REQUEST['stat'];
			}
			
			if (!empty($_REQUEST['dura'])){
				if ($_REQUEST['dura'] == 'zero') $conditions .= " and cdr.call_duration = '0'";
				else if ($_REQUEST['dura'] == 'cus'){
					if (!empty($_REQUEST['dust'])) {
						$conditions .= " and cdr.call_duration >= '{$_REQUEST['dust']}'";
						$last_conditons .= "&dust=".$_REQUEST['dust'];
					}
					
					if (!empty($_REQUEST['duet'])) {
						$conditions .= " and cdr.call_duration <= '{$_REQUEST['duet']}'";
						$last_conditons .= "&duet=".$_REQUEST['duet'];
					}
				}
				else  $conditions .= " and cdr.call_duration > '0'";
				
				$last_conditons .= "&dura=".$_REQUEST['dura'];
			}
			
			if (!empty($_REQUEST['cid'])) {
				$conditions .= " and cost.cdr_id like '%{$_REQUEST['cid']}%'";
				$last_conditons .= "&cid=".$_REQUEST['cid'];
			}
			
			$result = $this->Cdr->cdr_search($currPage,$pageSize,$conditions,$table,$search_id,$reseller_or_client_id,$table_name);
			$this->set('p',$result);
			$this->set('fields',explode(",",$_REQUEST['showf']));
			$last_conditons .= "&showf=".$_REQUEST['showf'];
			$this->set('last_conditons',$last_conditons);
			$this->set('hasData',count($result->getDataArray()));
			$this->set('search',true);
		} 
		
		$rate_sql = "select rate_table_id,name from rate_table";
		$res = $this->Session->read('sst_reseller_id');
		if (!empty($res)) $rate_sql .= " where reseller_id = '$res'";
		$this->set('rates',$this->Cdr->query($rate_sql));
		$this->set('nt',date('Y-m-d',time()+6*60*60));
	}
	
	
	
public function cdrs_list_of_client($client_id){
	$this->set('client_id',$client_id);
			$_REQUEST['showf'] = "account,call_duration,rate,bill_result";
			$currPage = 1;
			$pageSize = 100;
			$search = null;
			$last_conditons = '';
			
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
		
		
			$table_name = 'client_cost';
			$table = 'client';
			$search_id = 'client_id';
			$reseller_or_client_id = $client_id;
			$conditions = '';
					//$ids = $this->Cdr->query("select $search_id as id from $table where name = '{$_REQUEST['rc']}'");
					//$reseller_or_client_id = $ids[0][0]['id'];
					$conditions .= " and cost.$search_id = '{$reseller_or_client_id}'";
			
			
			if (!empty($_REQUEST['st'])){
				$conditions .= " and (SELECT TIMESTAMP WITH TIME ZONE 'epoch' + cdr.start_time_of_date::integer *INTERVAL '1 second')  >= '{$_REQUEST['st']}'";
				$last_conditons .= "&st=".$_REQUEST['st'];
			}
			
			if (!empty($_REQUEST['et'])){
				$conditions .= " and (SELECT TIMESTAMP WITH TIME ZONE 'epoch' + cdr.start_time_of_date::integer *INTERVAL '1 second')  <= '{$_REQUEST['et']}'";
				$last_conditons .= "&et=".$_REQUEST['et'];
			}
			
			if (!empty($_REQUEST['rt'])){
				$conditions .= " and cost.rate_table_id = '{$_REQUEST['rt']}'";
				$last_conditons .= "&rt=".$_REQUEST['rt'];
			}
			
			if (!empty($_REQUEST['tp'])) {
				$lim = 1;
				if ($_REQUEST['tp'] == 'orig')$lim=0;
				$conditions .= " and cost.type = '$lim'";
				$last_conditons .= "&tp=".$_REQUEST['tp'];
			}
			
			if (!empty($_REQUEST['rs'])) {
				if ($_REQUEST['rs'] == 'success') $conditions .= " and cdr.answer_time_of_date is not null";
				else $conditions .= " and cdr.answer_time_of_date is null";
				$last_conditons .= "&rs=".$_REQUEST['rs'];
			}
			
			if (!empty($_REQUEST['cus'])) {
				$conditions .= " and cdr.release_cause_from_protocol_stack = '{$_REQUEST['cus']}'";
				$last_conditons .= "&cus=".$_REQUEST['cus'];
			}
			
			if (!empty($_REQUEST['cost'])) {
				if ($_REQUEST['cost'] == 'nonzero') $conditions .= " and cost.cost > '0'";
				else $conditions .= " and cost.cost = '0'";
				
				$last_conditons .= "&cost=".$_REQUEST['cost'];
			}
			
			if (!empty($_REQUEST['dst'])) {
				$conditions .= " and cdr.origination_destination_number like '%{$_REQUEST['dst']}%'";
				$last_conditons .= "&dst=".$_REQUEST['dst'];
			}
			
			if (!empty($_REQUEST['src'])) {
				$conditions .= " and cdr.origination_source_number like '%{$_REQUEST['src']}%'";
				$last_conditons .= "&src=".$_REQUEST['src'];
			}
			
			if (!empty($_REQUEST['stat'])) {
				if ($_REQUEST['stat'] == 'suc') $conditions .= " and cost.bill_result = '1'";
				else $conditions .= " and cost.bill_result = '2'";
				
				$last_conditons .= "&stat=".$_REQUEST['stat'];
			}
			
			if (!empty($_REQUEST['dura'])){
				if ($_REQUEST['dura'] == 'zero') $conditions .= " and cdr.call_duration = '0'";
				else if ($_REQUEST['dura'] == 'cus'){
					if (!empty($_REQUEST['dust'])) {
						$conditions .= " and cdr.call_duration >= '{$_REQUEST['dust']}'";
						$last_conditons .= "&dust=".$_REQUEST['dust'];
					}
					
					if (!empty($_REQUEST['duet'])) {
						$conditions .= " and cdr.call_duration <= '{$_REQUEST['duet']}'";
						$last_conditons .= "&duet=".$_REQUEST['duet'];
					}
				}
				else  $conditions .= " and cdr.call_duration > '0'";
				
				$last_conditons .= "&dura=".$_REQUEST['dura'];
			}
			
			if (!empty($_REQUEST['cid'])) {
				$conditions .= " and cost.cdr_id like '%{$_REQUEST['cid']}%'";
				$last_conditons .= "&cid=".$_REQUEST['cid'];
			}
			
			$result = $this->Cdr->cdr_search($currPage,$pageSize,$conditions,$table,$search_id,$reseller_or_client_id,$table_name);
			$this->set('p',$result);
			$this->set('fields',explode(",",$_REQUEST['showf']));
			$last_conditons .= "&showf=".$_REQUEST['showf'];
			$this->set('last_conditons',$last_conditons);
			$this->set('hasData',count($result->getDataArray()));
			$this->set('search',true);
			$this->set('nt',date('Y-m-d',time()+6*60*60));
	}
	
	
	/*
	 *  Select resellers to choose
	 */
	public function choose_resellers(){
		$this->layout = '';
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] ))$currPage = $_REQUEST ['page'];
		
		if (! empty ( $_REQUEST ['size'] ))$pageSize = $_REQUEST ['size'];
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$results = $this->Cdr->choose_resellers ( $currPage, $pageSize,$search,$reseller_id);
		
		$this->set ( 'p', $results );
	}
	
	/*
	 *  Select clients to choose
	 */
	public function choose_clients(){
		$this->layout = '';
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] ))$currPage = $_REQUEST ['page'];
		
		if (! empty ( $_REQUEST ['size'] ))$pageSize = $_REQUEST ['size'];
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$results = $this->Cdr->choose_clients ( $currPage, $pageSize,$search,$reseller_id);
		
		$this->set ( 'p', $results );
	}
	
	/*
	 *  Select cards to choose
	 */
	public function choose_cards(){
		$this->layout = '';
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] ))$currPage = $_REQUEST ['page'];
		
		if (! empty ( $_REQUEST ['size'] ))$pageSize = $_REQUEST ['size'];
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$results = $this->Cdr->choose_cards ( $currPage, $pageSize,$search,$reseller_id);
		
		$this->set ( 'p', $results );
	}
}