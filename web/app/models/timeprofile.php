<?php
class Timeprofile extends AppModel{
	var $name = 'Timeprofile';
	var $useTable = 'time_profile';
	var $primaryKey = 'time_profile_id';
	
	/*
	 * 分页查询Time profiles
	 */
	public function getAllProfiles($currPage,$pageSize,$search,$reseller_id,$order=''){
		if(!empty($order)){
		       $order='order by'. $order;
		}else {
		  $order ="order by $order name asc ";
		}
		require_once 'MyPage.php';
		$page = new MyPage();
		$totalrecords = 0;
		$sql = "select count(time_profile_id) as c from time_profile where 1=1";
		if (!empty($search)) $sql .= " and name ilike '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$totalrecords = $this->query($sql);
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		$sql = "
			select * from time_profile where 1=1 
		";
		if (!empty($search)) $sql .= " and name ilike '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= "$order limit '$pageSize' offset '$offset'";
		//pr($sql);
		$results = $this->query($sql);
		$page->setDataArray($results);//Save Data into $page
		return $page;
	}
	
	/*
	 * 根据ID查找Time profile
	 */
	public function getProfileById($id){
		$timeprofile = $this->query("select * from time_profile where time_profile_id = '$id' order by name asc");
		return $timeprofile;
	}
        
        function afterFind($results){
            foreach($results as $key=>$result){
                if(empty($result['Timeprofile']['time_zone'])){
                    $results[$key]['Timeprofile']['time_zone'] = '+00';
                }
            }
            //var_dump($results);
            return $results;
        }
}