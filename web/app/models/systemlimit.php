<?php
class Systemlimit extends AppModel{
	var $name = 'Systemlimit';
	var $useTable = "system_limit";
	//var $primaryKey = "res_block_id";
	
	

	public function jurisdiction_view($country_id,$order=null){
	empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
	empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
		  if(empty($order))
			  {
			  	$order='id desc';
			  }
			require_once 'MyPage.php';
			$page = new MyPage();
	 		$login_type=$_SESSION['login_type'];
	 		$privilege='';//权限条件
			//模糊搜索
	 		$like_where=!empty($_GET['search'])?" and (name like '%{$_GET['search']}%'  or  id::text like '%{$_GET['search']}%'  or
	  	alias like '%{$_GET['search']}%')":'';
	  	$name_where=!empty($_GET['name'])?" and (name='{$_GET['name']}')":'';
	 		$totalrecords = $this->query("select count(*) as c from jurisdiction where  jurisdiction_country_id=$country_id 
	  	$like_where     $name_where  $privilege");
			$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
			$page->setCurrPage($currPage);//当前页
			$page->setPageSize($pageSize);//页大小
			$currPage = $page->getCurrPage()-1;
			$pageSize = $page->getPageSize();
			$offset=$currPage*$pageSize;
			$sql = "select  * from  jurisdiction where jurisdiction_country_id=$country_id	$like_where  $name_where  $privilege  ";
			$sql.=" order by $order";
	 		$sql .= " 	limit '$pageSize' offset '$offset'";
			$results = $this->query($sql);
			$page->setDataArray($results);
			return $page;
	}	

	

		/**
	 * 查询系统容量
	 */
	function findsystemlimit(){
		$r= $this->query("select ingress_call_limit,ingress_cps_limit from system_limit  offset 0  limit 1");
		if(empty($r)){
			$r = $this->query("INSERT INTO system_limit (ingress_call_limit, ingress_cps_limit) VALUES (".(isset($_POST['ingress_call_limit'])?$_POST['ingress_call_limit']:0).", ".(isset($_POST['ingress_cps_limit'])?$_POST['ingress_cps_limit']:0).")");
			
		}
		
		return $r;
	}	
	





}