<?php
class Refillpool extends AppModel{
	var $name = 'Refillpool';
	var $useTable = 'rate_table';
	var $primaryKey = 'rate_table_id';
	
	
public function 	getAllSeries(){
	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 13:	$pageSize = $_GET['size'];
		 
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 $privilege='';
	 //reseller
	 if($login_type==2){
	 		$privilege="  and(reseller_id={$_SESSION['sst_reseller_id']}) ";

	 }

		
	 
//模糊搜索
	 $like_where=!empty($_POST['search'])?" and (name like '%{$_POST['search']}%' 
	  or  credit_card_series_id::text like '%{$_POST['search']}%'  or value::text like '%{$_POST['search']}%'
	  or prefix <@  '{$_POST['search']}'  or start_num::text like '%{$_POST['search']}%'
	  )":'';
 	 //起始金额
	 $start_amount_where=!empty($_POST['start_amount'])?"  and (value::numeric>{$_POST['start_amount']})":'';
//结束金额
	 $end_amount_where=!empty($_POST['end_amount'])?"     and (value::numeric<{$_POST['end_amount']})":'';
 	 
	 // 前缀
	  $prefix_where=!empty($_POST['prefix'])?" and (prefix <@'{$_POST['prefix']}')":'';

		 // 开始编号
	  $start_num_where=!empty($_POST['start_num'])?" and (start_num::text like '%{$_POST['start_num']}%')":'';
	  $name_where=!empty($_POST['name'])?" and (name like '%{$_POST['name']}%')":'';
	
 	 //按时间搜索
 	 $date_where='';
 	 pr($_POST);
 	 if(!empty($_POST['start_date'])||!empty($_POST['end_date'])){
 	  $start =!empty($_POST['start_date'])?$_POST['start_date']:date ( "Y-m-1  00:00:00" );
	  $end = !empty($_POST['end_date'])?$_POST['end_date']:date ( "Y-m-d 23:59:59" );
    $date_where="  and  (expire_date  between   '$start'  and  '$end')";
 	    
 	 }
	 $totalrecords = $this->query("select count(credit_card_series_id) as c from credit_card_series where 1=1 
	  $like_where  $start_amount_where  $end_amount_where     $prefix_where $start_num_where $name_where   $privilege  ");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select  credit_card_series_id,name,value,password_length,expire_date, 
(select count(credit_card_id) from credit_card where credit_card_series_id = c.credit_card_series_id) as cards, prefix,

start_num from  credit_card_series  as  c where 1=1  
		 $like_where  $start_amount_where  $end_amount_where     $prefix_where $start_num_where $name_where   $privilege  ";
		

	 $sql .= " order by credit_card_series_id     desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;

}
	
	
	
/**
	 *  分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllSeries_tmp($currPage=1,$pageSize=15,$search=null,$reseller_id=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		
		$sql = "select count(credit_card_series_id) as c from credit_card_series where 1=1";
		if (!empty($search))$sql .= " and name like '%$search%'";
		if (!empty($reseller_id))$sql .= " and reseller_id = '$reseller_id'";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select credit_card_series_id,name,value,password_length,expire_date,
							(select count(credit_card_id) from credit_card where credit_card_series_id = c.credit_card_series_id) as cards,
							prefix,start_num from credit_card_series as c where 1=1";
		
		if (!empty($search))$sql .= " and name like '%$search%'";
		if (!empty($reseller_id))$sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	/*
	 * 得到费率   充值策略   送话费的策略
	 */
	public function getRates($reseller_id){
		$rates = "select rate_table_id,name from rate_table";
		if (!empty($reseller_id)) $rates .= " where reseller_id = '$reseller_id'";
		return $this->query($rates);
	}
	
	public function getById($id){
		return $this->query("select credit_card_series_id,name,expire_date,
																prefix,start_num,value,password_length from credit_card_series
																where credit_card_series_id = '$id'");
	}
	
	
	
/**
	 *  分页查询Card
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllCards($currPage=1,$pageSize=15,$search=null,$id,$advanceSearch){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		if (!empty($search)) {
			$totalrecords = $this->query("select count(credit_card_id) as c from credit_card where credit_card_series_id ='$id' and card_number like '%$search%' $advanceSearch");			
		} else {
			$totalrecords = $this->query("select count(credit_card_id) as c from credit_card where credit_card_series_id ='$id' $advanceSearch");
		}
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		$sql = "select credit_card_id,card_number,used_date,pin,
							(select name from credit_card_series where credit_card_series_id = credit_card.credit_card_series_id) as series,
							expire_date,create_date,effective_date,
							(select name from reseller where reseller_id = credit_card.reseller_id) as reseller,value from credit_card
							where credit_card_series_id = '$id'";
		
		
		if (!empty($advanceSearch)) {
			$sql .= $advanceSearch;
		} 
		
		if (!empty($search)) {
			$sql .= " and card_number like '%$search%' limit '$pageSize' offset '$offset'";
		} else {
			$sql .= " limit '$pageSize' offset '$offset'";
		}
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getAllResellers($reseller_id){
		$sql = "select reseller_id,name from reseller";
		if (!empty($sql)) $sql .= " where reseller_id = '$reseller_id' or parent = '$reseller_id'";
		return $this->query($sql);
	}
	
	public function getAllRates($reseller_id){
		$sql = "select rate_table_id,name from rate_table";
		if (!empty($sql)) $sql .= " where reseller_id = '$reseller_id'";
		return $this->query($sql);
	}
	
	public function getCardById($card_id){
		return $this->query("select credit_card_id,pin,credit_card_series_id,card_number,expire_date,reseller_id,value from credit_card where credit_card_id = '$card_id'");
	}
}