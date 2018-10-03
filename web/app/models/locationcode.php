<?php
class Locationcode extends AppModel{
	var $name = 'Locationcode';
	var $useTable = 'location_code';
	var $primaryKey = 'location_id';
	
	
	public function getAllLocations($currPage=1,$pageSize=15,$search=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		
		$sql = "select count(location_id) as c from location_code where 1=1";
		if (!empty($search)) $sql .= " and (country_code like '%$search%' or state_code like '%$search%' or city_code like '%$search%')";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from location_code where 1=1";
		
		if (!empty($search)) $sql .= " and (country_code like '%$search%' or state_code like '%$search%' or city_code like '%$search%')";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function save_location(){
		
		
/*				$country_code = '10002';
		$country = 'aaaa';
		$state_code = 'aaaaaa';
		$state = 'cccc';
		$city_code = '100003';
		$city = 'yyjyy';
		$number = '222';*/
		
		$country_code = $_REQUEST['country_code'];
		$country = $_REQUEST['country'];
		$state_code = $_REQUEST['state_code'];
		$state = $_REQUEST['state'];
		$city_code = $_REQUEST['city_code'];
		$city = $_REQUEST['city'];
		$number = $_REQUEST['number'];
		
		$exists = $this->query("select location_id from location_code where country_code = '$country_code' and state_code = '$state_code' and city_code = '$city_code'");
		if (count($exists) > 0){
		
		return __('threecodeunique',true)."|false";
		} 
		
		$sql = "insert into location_code (country,country_code,state,state_code,city,city_code,number)
							 values('$country','$country_code','$state','$state_code','$city','$city_code','$number')";
		
		$qs = $this->query($sql);
		if (count($qs) == 0) return __('addloccodesuc',true)."|true";
		else return __('addloccodefail',true)."|false";
	}
	
	public function update_location(){
		$country_code = $_REQUEST['country_code'];
		$country = $_REQUEST['country'];
		$state_code = $_REQUEST['state_code'];
		$state = $_REQUEST['state'];
		$city_code = $_REQUEST['city_code'];
		$city = $_REQUEST['city'];
		$location_id = $_REQUEST['location_id'];
		$number = $_REQUEST['number'];
		
		$old = $this->query("select country_code,state_code,city_code from location_code where location_id = '$location_id'");
		if ($country_code != $old[0][0]['country_code']
				||$state_code != $old[0][0]['state_code']
				||$city_code != $old[0][0]['city_code']) {
					
			$exists = $this->query("select location_id from location_code where country_code = '$country_code' and state_code = '$state_code' and city_code = '$city_code'");
			if (count($exists) > 0) return __('threecodeunique',true)."|false";
		}
		
		
		$sql = "update location_code set country_code = '$country_code',country='$country',
							state_code='$state_code',state='$state',
							city_code='$city_code',city='$city',number='$number'
							where location_id = '$location_id'";	
			
		$qs = $this->query($sql);
		if (count($qs) == 0) return __('update_suc',true)."|true";
		else return __('update_fail',true)."|false";
	}
	
	public function del($id){
		$qs = $this->query("delete from location_code where location_id in ($id)");
		return count($qs) == 0;
	}
}