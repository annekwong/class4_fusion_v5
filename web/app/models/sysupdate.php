<?php
class Sysupdate extends AppModel {
	var $name = 'Sysupdate';
	var $useTable = 'system_update';
	var $primaryKey = 'system_update_id';
	
	public function getList($currPage = 1, $pageSize = 15) {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(system_update_id) as c from system_update";
		
		$totalrecords = $this->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select *,(select name from reseller where reseller_id = system_update.reseller_id) as reseller from system_update limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
	
	public function add(){
		$soft_name = $_REQUEST['soft_name'];
		$reseller_id = $_REQUEST['reseller_id'];
		$phone_type = $_REQUEST['phone_type'];
		$soft_version = $_REQUEST['soft_version'];
		$soft_link = $_REQUEST['soft_link'];
		
		$qs = $this->query("insert into system_update(soft_name,reseller_id,phone_type,soft_version,soft_link)
											 values('$soft_name',$reseller_id,$phone_type,'$soft_version','$soft_link')");
		
		if (count($qs) == 0) {
			return __('add_suc',true)."|true";
		}
		
		return __('add_fail',true)."|false";
	}
	
	public function update(){
		$soft_name = $_REQUEST['soft_name'];
		$reseller_id = $_REQUEST['reseller_id'];
		$phone_type = $_REQUEST['phone_type'];
		$soft_version = $_REQUEST['soft_version'];
		$soft_link = $_REQUEST['soft_link'];
		$id = $_REQUEST['id'];
		
		$qs = $this->query("update system_update set soft_name='$soft_name',reseller_id=$reseller_id,
															phone_type=$phone_type,soft_version='$soft_version',soft_link='$soft_link'
															where system_update_id = $id");
		
		if (count($qs) == 0) {
			return __('update_suc',true)."|true";
		}
		
		return __('update_fail',true)."|false";
	}
}
?>