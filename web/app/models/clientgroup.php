<?php
class Clientgroup extends AppModel {
	var $name = 'Clientgroup';
	var $useTable = 'client_group';
	var $primaryKey = 'client_group_id';
	
	/**
	 * 分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllGroups($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null) {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(client_group_id) as c from client_group where 1=1";
		if (! empty ( $search ))
			$sql .= " and name like '%$search%'";
		if (! empty ( $reseller_id ))
			$sql .= " and reseller_id = '$reseller_id'";
		
		$totalrecords = $this->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select (select name from rate_table where rate_table_id = client_group.rate_table_id) as rate,client_group_id,name,invoice_note from client_group where 1=1";
		
		if (! empty ( $search ))
			$sql .= " and name like '%$search%'";
		if (! empty ( $reseller_id ))
			$sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
	
	/*
	 * 根据ID获得Group
	 */
	public function getGroupById($ownid) {
		$sql = "select  rate_table_id,client_group_id,name,invoice_note from client_group where client_group_id = '$ownid'";
		return $this->query ( $sql );
	}
	
	public function del($id) {
		$qs = $this->query ( "delete from client_group where client_group_id in ($id)" );
		return count ( $qs ) == 0;
	}
	
	public function getRates($reseller_id){
		$sql = "select rate_table_id,name from rate_table";
		if (!empty($reseller_id)) {
			$sql .= " where reseller_id = $reseller_id";
		}
		return $this->query($sql);
	}
}