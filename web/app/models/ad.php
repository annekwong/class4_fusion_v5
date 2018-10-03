<?php
class Ad extends AppModel {
	var $name = 'Ad';
	var $useTable = 'system_ads';
	var $primaryKey = 'system_ads_id';
	
	/**
	 * 分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllAds($currPage = 1, $pageSize = 15, $search = null, $adv_search = '') {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(system_ads_id) as c from system_ads where 1=1 $adv_search";
		if (! empty ( $search ))
			$sql .= " and title like '%$search%'";
		
		$totalrecords = $this->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from system_ads where 1=1 $adv_search";
		
		if (! empty ( $search ))
			$sql .= " and title like '%$search%'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
	
	public function del($id){
		$qs = $this->query("delete from system_ads where system_ads_id in ($id)");
		return count($qs) == 0;
	}
}
?>