<?php
class Recommend Extends AppModel{
	var $name = 'Recommend';
	var $useTable = 'recommend_strategy';
	var $primaryKey = 'recommend_strategy_id';
	
public function getList($currPage=1,$pageSize=15,$search=null,$reseller_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(recommend_strategy_id) as c from recommend_strategy where 1=1";
		if (!empty($search)) $sql .= " and strategy_name like '%$search%'";
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
		$sql = "select * from recommend_strategy where 1=1";
		
		if (!empty($search)) $sql .= " and strategy_name like '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function add_recommend(){
		$strategy_name = $_REQUEST['strategy_name'];
		$gift_type = $_REQUEST['gift_type'];
		$basic_amount = empty($_REQUEST['basic_amount'])?0:$_REQUEST['basic_amount'];
		$gift_amount = empty($_REQUEST['gift_amount'])?0:$_REQUEST['gift_amount'];
		$gift_point = empty($_REQUEST['gift_point'])?0:$_REQUEST['gift_point'];
		$start_time = empty($_REQUEST['start_time'])?'null':"'".$_REQUEST['start_time']."'";
		$end_time = empty($_REQUEST['end_time'])?'null':"'".$_REQUEST['end_time']."'";
		$reseller_id = $_SESSION['sst_reseller_id'];
		$by_first_payment = $_REQUEST['by_first_payment'];
		
		$sql = "insert into recommend_strategy (strategy_name,gift_type,basic_amount,gift_amount,gift_point,reseller_id,start_time,end_time,by_first_payment)
						  values('$strategy_name',$gift_type,$basic_amount,$gift_amount,$gift_point,$reseller_id,$start_time,$end_time,$by_first_payment)";
		
		$qs = $this->query($sql);
		
		if (count($qs)  == 0)
			return __('addrecsuc',true)."|true";
		else 
			return __('addrecfail',true)."|false";
	}
	
	public function update_recommend(){
		$strategy_name = $_REQUEST['strategy_name'];
		$gift_type = $_REQUEST['gift_type'];
		$basic_amount = empty($_REQUEST['basic_amount'])?0:$_REQUEST['basic_amount'];
		$gift_amount = empty($_REQUEST['gift_amount'])?0:$_REQUEST['gift_amount'];
		$gift_point = empty($_REQUEST['gift_point'])?0:$_REQUEST['gift_point'];
		$start_time = empty($_REQUEST['start_time'])?'null':"'".$_REQUEST['start_time']."'";
		$end_time = empty($_REQUEST['end_time'])?'null':"'".$_REQUEST['end_time']."'";
		$id = $_REQUEST['id'];
		$by_first_payment = $_REQUEST['by_first_payment'];
		
		$sql = "update recommend_strategy set strategy_name='$strategy_name',
							gift_type=$gift_type,basic_amount=$basic_amount,gift_amount=$gift_amount,
							gift_point=$gift_point,start_time=$start_time,end_time=$start_time,
							by_first_payment=$by_first_payment
							where recommend_strategy_id = $id";
		
		$qs = $this->query($sql);
		
		if (count($qs)  == 0)
			return __('updaterecsuc',true)."|true";
		else 
			return __('updaterecfail',true)."|false";
	}
}