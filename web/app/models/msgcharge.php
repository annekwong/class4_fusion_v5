<?php
class Msgcharge extends AppModel {
	var $name = 'Msgcharge';
	var $useTable = 'sms_charges';
	var $primaryKey = 'msg_charges_id';
	
	public function getCharges($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null) {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = array ();
		
		$sql = "select count(msg_charges_id) as c from sms_charges where 1=1";
		if (! empty ( $reseller_id ))
			$sql .= " and reseller_id = '$reseller_id'";
		if (! empty ( $search ))
			$sql .= " and charge_name like '%$search%'";
		
		$totalrecords = $this->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		//查询Product
		$sql = "select * from sms_charges where 1=1";
		
		if (! empty ( $reseller_id ))
			$sql .= " and reseller_id = '$reseller_id'";
		if (! empty ( $search ))
			$sql .= " and charge_name like '%$search%'";
		
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
	
	public function add_charge() {
		$charge_name = $_REQUEST ['charge_name'];
		$msg_type = $_REQUEST ['msg_type'];
		$msg_rate = $_REQUEST ['msg_rate'];
		$free_count = $_REQUEST ['free_count'];
		
		$check_exists = "select msg_charges_id from sms_charges where charge_name = '$charge_name'";
		
		$exists = $this->query ( $check_exists );
		
		if (count ( $exists ) > 0) {
			return __ ( 'msgchargenameexists', true ) . "|false";
		}
		
		$qs = $this->query ( "insert into sms_charges(msg_type,msg_rate,free_count,charge_name)
											values('$msg_type','$msg_rate','$free_count','$charge_name')" );
		
		if (count ( $qs ) == 0)
			return __ ( 'add_suc', true ) . "|true";
		return __ ( 'add_fail', true ) . "|false";
	}
	
	public function edit_charge() {
		$charge_name = $_REQUEST ['charge_name'];
		$msg_type = $_REQUEST ['msg_type'];
		$msg_rate = $_REQUEST ['msg_rate'];
		$free_count = $_REQUEST ['free_count'];
		$id = $_REQUEST ['id'];
		
		$old_name = $this->query ( "select charge_name from sms_charges where msg_charges_id = $id" );
		if ($old_name [0] [0] ['charge_name'] != $charge_name) {
			$check_exists = "select msg_charges_id from sms_charges where charge_name = '$charge_name'";
			
			$exists = $this->query ( $check_exists );
			
			if (count ( $exists ) > 0) {
				return __ ( 'msgchargenameexists', true ) . "|false";
			}
		}
		
		$qs = $this->query ( "update sms_charges set msg_type = '$msg_type',msg_rate='$msg_rate',
															free_count='$free_count',charge_name='$charge_name' where msg_charges_id = '$id'" );
		
		if (count ( $qs ) == 0)
			return __ ( 'update_suc', true ) . "|true";
		return __ ( 'update_fail', true ) . "|false";
	}
	
	public function del_charge($id) {
		$qs_count = 0;
		$qs = $this->query ( "select card_id from card where msg_one_to_many = $id" );
		$qs_count += count ( $qs );
		
		$qs = $this->query ( "select card_id from card where msg_find_pwd = $id" );
		$qs_count += count ( $qs );
		
		$qs = $this->query ( "select card_id from card where msg_view_balance = $id" );
		$qs_count += count ( $qs );
		
		$qs = $this->query ( "select card_id from card where msg_acc_reg = $id" );
		$qs_count += count ( $qs );
		
		$qs = $this->query ( "select card_id from card where msg_one_to_one = $id" );
		$qs_count += count ( $qs );
		
		if ($qs_count > 0)
			return false;
		else {
			$this->query ( "delete from sms_charges where msg_charges_id = $id" );
			return true;
		}
	}
}