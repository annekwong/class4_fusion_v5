<?php
class Location extends AppModel {
	var $name = 'Location';
	var $useTable = 'location';
	var $primaryKey = 'id';
	
	/**
	 * 分页查询注册电话
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllLocations($currPage = 1, $pageSize = 15, $search = null) {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(id) as c from location where 1=1";
		if (! empty ( $search ))
			$sql .= " and username like '%$search%'";
		
		$totalrecords = $this->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select id,username,last_modified,user_agent,socket from location where 1=1";
		
		if (! empty ( $search ))
			$sql .= " and username like '%$search%'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
}
?>