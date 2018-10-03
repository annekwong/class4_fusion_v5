<?php
class Realcdr extends  AppModel{
//	var $name = 'Realcdr';
	var $useTable = false;
//	var $primaryKey = 'real_cdr_id';
	
	
	//回拨CDR
	
	
	public function get_callback_info($currPage,$pageSize,$reseller_id,$condition=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		$totalrecords = 0;
//		$sql = "select count(real_cdr_id) as c from real_cdr where 1=1  and call_type='1'   $condition";
//		if (!empty($reseller_id)) {
//			$sql .= " and reseller_id = '$reseller_id'";
//		}
//		$totalrecords = $this->query($sql);
//		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		//查询Client groups
//		$sql = "select ani,dnis,
//								caller_ip_address as ani_address,
//							(select name from resource where resource_id = real_cdr.ingress_id::integer) as ingress,
//							(select name from resource where resource_id = real_cdr.egress_id::integer) as egress,
//							ans_time_a as start_time,
//							(select name from reseller where reseller_id = real_cdr.reseller_id::integer) as reseller,
//							(select name from client where client_id = real_cdr.client_id::integer) as client,
//							(select name from codecs where id = real_cdr.codec_id::integer) as codec
//							from real_cdr where 1=1 and call_type='1' $condition";
//		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
//		$sql .= " limit '$pageSize' offset '$offset'";
//		$results = $this->query($sql);
        $results = array();
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		return $page;
	}
	public function get_real_info($currPage,$pageSize,$condition=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
//		$sql = "select count(real_cdr_id) as c from real_cdr where 1=1 $condition";
//		$totalrecords = $this->query($sql);
//
//		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
//		$sql = "
//select caller_packets,callee_packets,caller_bytes,callee_bytes,
//
//(select card_number from card
// where card_id = case real_cdr.account_id when '' then -1 else real_cdr.account_id::integer end) as account,
//
// ani,dnis,caller_ip_address as ani_address,server_ip,uuid_a,
// (select name from resource where resource_id = case ingress_id when ''
//then -1 else ingress_id::integer end) as ingress,
// (select name from resource where resource_id = case egress_id when ''
//then -1 else egress_id::integer end) as egress,
// (SELECT case ans_time_a when '' then null else  TIMESTAMP WITH TIME ZONE 'epoch' + ans_time_a::bigint/1000000 *
//INTERVAL '1 second' end) as start_time,
// (select name from reseller where reseller_id = case
//real_cdr.reseller_id when '' then -1 else real_cdr.reseller_id::integer
//end) as reseller,
//  (select name from client where client_id = case real_cdr.client_id
//when '' then -1 else real_cdr.client_id::integer end) as client,
//ingress_codec,egress_codec
//							from real_cdr where call_type != '2' $condition";
//
//
//		$sql .= " limit '$pageSize' offset '$offset'";
//
//		$results = $this->query($sql);
		$results = array();
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function search_info($reseller_id){
		$ingress_sql = "select resource_id,name from resource where ingress = true";
		$egress_sql = "select resource_id,name from resource where egress = true";
		$client_sql = "select client_id,name from client";
		$account_sql = "select card_id,card_number from card";
		if (!empty($reseller_id)){
			$ingress_sql .= " and reseller_id = $reseller_id";
			$egress_sql .= " and reseller_id = $reseller_id";
			$client_sql .= " where reseller_id = $reseller_id";
			$account_sql .= " where reseller_id = $reseller_id";
		}
		
		return array(
										 $this->query($ingress_sql),
										 $this->query($egress_sql),
										 array(),
										 $this->query($client_sql),
										 $this->query($account_sql)
										 );
	}
	
	
	
	/**
	 * 查询当前会议
	 * @param unknown_type $currPage
	 * @param unknown_type $pageSize
	 * @param unknown_type $login_type
	 */
public function get_conferences($currPage,$pageSize,$login_type,$adv_search){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
//		$sql = "select count(real_cdr_id) as c from real_cdr where uuid_b in (select uuid_a from real_cdr where call_type = '2'  and uuid_a = uuid_b group by uuid_a) $adv_search";
//
//		if ($login_type == 2){
//			$sql .= " and reseller_id = '{$_SESSION['sst_reseller_id']}'";
//		} else if ($login_type == 4){
//			$sql .= " and account_id = '{$_SESSION['sst_card_id']}'";
//		}
//
//		$totalrecords = $this->query($sql);
//
//		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
//		$sql = "select (select card_number from card where card_id = account_id::integer) as account,
//(select name from reseller where reseller_id = real_cdr.reseller_id::integer) as reseller,
//	ans_time_a,conf_id,uuid_a,
//(select count(real_cdr_id) from real_cdr
//where  uuid_b in (select uuid_a from real_cdr where call_type = '2'  and uuid_a = uuid_b group by uuid_a)) as nums
//from real_cdr
//where uuid_b in (select uuid_a from real_cdr where call_type = '2'  and uuid_a = uuid_b group by uuid_a) $adv_search";
//
//if ($login_type == 2){
//			$sql .= " and reseller_id = '{$_SESSION['sst_reseller_id']}'";
//		} else if ($login_type == 4){
//			$sql .= " and account_id = '{$_SESSION['sst_card_id']}'";
//		}
//
//		$sql .= " limit '$pageSize' offset '$offset'";
//
//		$results = $this->query($sql);
		$results = array();
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	/**
	 * 查询会议成员
	 * @param unknown_type $conf_id
	 */
	public function get_conf_member($conf_id) {
//		return $this->query("select uuid_b,dnis,ans_time_a,conf_id from real_cdr where conf_id = '$conf_id'");
	}
	
	/**
	 * 查询Sip成员当前参加的会议
	 * @param unknown_type $sip_id
	 */
	public function getSipOwnConf($sip_id){
//		return $this->query("select uuid_a,uuid_b,dnis,ans_time_a from real_cdr where conf_id = (select conf_id from real_cdr where card_sip_id = '$sip_id')");
	}
	
	public function get_search_info($reseller_id){
		$sql = "select reseller_id,name from reseller";
		$sql_1 = "select card_id,card_number from card";
		if (!empty($reseller_id)){$sql .= " where parent = $reseller_id";$sql_1 .= " where reseller_id = $reseller_id";}
		return array($this->query($sql),$this->query($sql_1));
	}
	
	
/**
	 * 查询当前会议
	 * @param unknown_type $currPage
	 * @param unknown_type $pageSize
	 * @param unknown_type $login_type
	 */
public function get_conferences_history($currPage,$pageSize,$login_type,$adv_search){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(cdr_id) as c from cdr where call_type='2' $adv_search";
		
		if ($login_type == 2){
			$sql .= " and account_id in (select card_id::character varying from card where reseller_id = '{$_SESSION['sst_reseller_id']}')";
		} else if ($login_type == 4){
			$sql .= " and account_id = '{$_SESSION['sst_card_id']}'";
		}
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select (select card_number from card where card_id = account_id::integer) as account,
(select name from reseller where reseller_id = (select reseller_id from card where card_id=account_id::integer)) as reseller,
answer_time_of_date,conf_id,termination_destination_number from cdr
where call_type = '2' $adv_search";
		
		if ($login_type == 2){
			$sql .= " and account_id in (select card_id::character varying from card where reseller_id = '{$_SESSION['sst_reseller_id']}')";
		} else if ($login_type == 4){
			$sql .= " and account_id = '{$_SESSION['sst_card_id']}'";
		}
		
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
}