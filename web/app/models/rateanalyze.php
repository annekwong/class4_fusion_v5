<?php
class Rateanalyze extends AppModel{
	var $name = 'Rateanalyze';
	var $useTable = 'rate_table';
	var $primaryKey = 'rate_table_id';
	
	public function match_rate($currPage,$pageSize,$reseller_id,$condition,$rate_tables){
		require_once 'MyPage.php';
		$page = new MyPage();
		$totalrecords = 0;
		$sql = "select  count(rate_id) as c from rate where rate_table_id in ($rate_tables) $condition";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$totalrecords = $this->query($sql);
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		$sql = "
				select (select name from time_profile where time_profile_id = rate.time_profile_id) as time_p,
				*,(select name from rate_table where rate_table_id = rate.rate_table_id) as table_name 
				from rate where rate_table_id in ($rate_tables) $condition
		";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		$results = $this->query($sql);
		$page->setDataArray($results);//Save Data into $page
		return $page;
	}
	
	public function get_tables($reseller_id){
		$sql = "select rate_table_id,name from rate_table";
		if (!empty($reseller_id)) $sql .= " where reseller_id=$reseller_id";
		return $this->query($sql);
	}
}