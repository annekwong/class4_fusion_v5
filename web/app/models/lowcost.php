<?php
class Lowcost extends AppModel {
	var $name = 'lowcost';
	var $useTable = 'low_cost_strategy';
	var $primaryKey = 'low_cost_strategy_id';
	
	/**
	 * 分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllLowcosts($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null) {
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(low_cost_strategy_id) as c from low_cost_strategy where 1=1";
		if (! empty ( $search ))
			$sql .= " and strategy_name like '%$search%'";
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
		$sql = "select * from low_cost_strategy where 1=1";
		
		if (! empty ( $search ))
			$sql .= " and strategy_name like '%$search%'";
		if (! empty ( $reseller_id ))
			$sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		//////////////////////////////////////////
		

		return $page;
	}
	
	
	public function add(){
		$strategy_name = $_REQUEST['strategy_name'];
		$low_cost = $_REQUEST['low_cost'];
		$gift_point = empty($_REQUEST['gift_point'])?'null':$_REQUEST['gift_point'];
		$gift_amount = empty($_REQUEST['gift_amount'])?'null':$_REQUEST['gift_amount'];
		$gift_money = empty($_REQUEST['gift_money'])?'null':$_REQUEST['gift_money'];
		$start_time = empty($_REQUEST['start_time'])?'null':"'".$_REQUEST['start_time']."'";
		$end_time = empty($_REQUEST['end_time'])?'null':"'".$_REQUEST['end_time']."'";
		$reseller_id = $_SESSION['sst_reseller_id'];
		
		$qs = $this->query("insert into low_cost_strategy(low_cost,gift_point,gift_amount,
												gift_money,reseller_id,strategy_name,start_time,end_time)
												values ($low_cost,$gift_point,$gift_amount,$gift_money,$reseller_id,'$strategy_name',$start_time,$end_time)");
		
		if (count($qs) == 0) return __('add_suc',true)."|true";
		else return __('add_fail',true)."|false";
	}
	
	public function update(){
		$strategy_name = $_REQUEST['strategy_name'];
		$low_cost = $_REQUEST['low_cost'];
		$gift_point = empty($_REQUEST['gift_point'])?'null':$_REQUEST['gift_point'];
		$gift_amount = empty($_REQUEST['gift_amount'])?'null':$_REQUEST['gift_amount'];
		$gift_money = empty($_REQUEST['gift_money'])?'null':$_REQUEST['gift_money'];
		$start_time = empty($_REQUEST['start_time'])?'null':"'".$_REQUEST['start_time']."'";
		$end_time = empty($_REQUEST['end_time'])?'null':"'".$_REQUEST['end_time']."'";
		$id = $_REQUEST['id'];
		
		$qs = $this->query("update low_cost_strategy set low_cost = $low_cost,gift_point=$gift_point,
															gift_amount=$gift_amount,gift_money=$gift_money,strategy_name='$strategy_name',
															start_time=$start_time,end_time=$end_time
															where low_cost_strategy_id = '$id'");
		
		if (count($qs) == 0) return __('update_suc',true)."|true";
		else return __('update_fail',true)."|false";
	}
	
	public function del($id){
		$qs = $this->query("delete from low_cost_strategy where low_cost_strategy_id in ($id)");
		return count($qs) == 0;
	}
}
?>