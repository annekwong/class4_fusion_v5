<?php
App::import('Model','Order.Order');
class Seller extends AppModel{
	var $name = 'Seller';
	var $useTable = 'direct_seller_enrollment';
	var $primaryKey = 'id';
	
	
	public function ListSellers($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		require_once MODELS.DS.'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		if (!empty($search_arr['status']))
		{
			//$sql_where .= " and status = " . intval($search_arr['status']);
		}
		else
		{
			//$sql_where .= " and status = 1";
		}
		if ($search_type == 1)
		{
			if (!empty($search_arr['name'])) $sql_where .= " and alias like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{

		}
		
		$sql = "select count(*) as c from direct_seller_enrollment left join order_user on direct_seller_enrollment.user_id=order_user.id where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select direct_seller_enrollment.id as dse_id, direct_seller_enrollment.request_time,direct_seller_enrollment.action,order_user.* from direct_seller_enrollment left join order_user on direct_seller_enrollment.user_id=order_user.id where 1=1".$sql_where;
		
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
		/**
		 * 禁用一个seller
		 * @param int $id seller id
		 */
	public function dis_able($id)
	{
		return $this->query("update direct_seller_enrollment set action = 0 where id = " . intval($id));
	}
	
	public function active($id)
	{
	  return $this->query("update direct_seller_enrollment set action = 1 where id = " . intval($id));
	}
	
}
?>