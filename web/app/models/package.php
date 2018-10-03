<?php
class Package extends AppModel{
	var $name = 'Package';
	var $useTable = 'package';
	var $primaryKey = 'package_id';
	
	public function getAllPackages($currPage,$pageSize,$search,$reseller_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(package_id) as c from package where 1=1";
		if (!empty($search)) $sql .= " and name like '%$search%'";
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
		$sql = "select (select count(package_rate_id) from package_rate where package_id = package.package_id) as rate_tmps,package_id,name,lease_type,fee,free_minute,gift_amount,(select name from reseller where reseller_id = package.create_user) as create_user,
		note,billing_policy,charge_standard,package_mins
		,(select name from rate_table where rate_table_id = package.over_rate_table_id) as over_rate from package where 1=1";
		
		if (!empty($search)) $sql .= " and name like '$search'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
/*
	 * 查看套餐是否被使用
	 */
	public function package_used($package_id) {
		$packages = $this->query("select count(*) as c from package_rate where package_id  = '$package_id'");
		if ($packages[0][0]['c'] > 0) {
			return "t";
		} else return "f";
	}

	/*
	 * 根据ID获得一条套餐记录
	 */
	public function getPackageById($package_id){
		return $this->query("select package_mins,reseller_id,package_id,name,lease_type,fee,free_minute,gift_amount,note,billing_policy,charge_standard from package where package_id = '$package_id'");
	}
	
public function getAllPackageRates($currPage,$pageSize,$package_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$totalrecords = $this->query("select count(*) as c from package_rate where package_id = '$package_id'");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select package_rate_id,package_id,
								(select name from rate_table where rate_table_id = pr.rate_table_id) as rate,
								(select name from time_profile where time_profile_id = pr.time_profile_id) as timeprofile,
								type from package_rate as pr where package_id = '$package_id'";
		
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getRatesAndTimeProfiles($reseller_id){
		$rate_sql = "select rate_table_id,name from rate_table";
		$timeprofile_sql = "select time_profile_id,name from time_profile";
		
		if (!empty($reseller_id)){
			$rate_sql .= " where  reseller_id = '$reseller_id'";
			$timeprofile_sql .= " where  reseller_id = '$reseller_id'";
		}
		
		$r = array();
		$rates = $this->query($rate_sql);
		array_push($r,$rates);
		array_push($r,$this->query($timeprofile_sql));
		return $r ;
	}
	
	public function getRates($reseller_id){
		$rate_sql = "select rate_table_id,name from rate_table";
		if (!empty($reseller_id)){
			$rate_sql .= " where reseller_id = $reseller_id";
		}
		return $this->query($rate_sql);
	}
	
	public function getAllReseller($reseller_id){
		return $this->query("select * from get_reseller($reseller_id)");
	}
}