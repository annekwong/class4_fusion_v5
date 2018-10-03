<?php
	class Service extends AppModel{
		var $name = 'Service';
		var $useTable = 'value_add_service';
		var $primaryKey = 'service_id';
		
	public function getList($currPage=1,$pageSize=15,$search=null,$reseller_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(service_id) as c from value_add_service where 1=1";
		if (!empty($search)) $sql .= " and service_name like '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select *,(select name from reseller where reseller_id = value_add_service.reseller_id) as reseller from value_add_service where 1=1";
		
		if (!empty($search)) $sql .= " and service_name like '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getbyid($id){
		return $this->query("select * from value_add_service where service_id = $id");
	}
	} 
?>