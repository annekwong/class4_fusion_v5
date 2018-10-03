<?php
class Dex extends AppModel{
//	var $name = 'Dex';
	var $useTable = false;
//	var $primaryKey = 'id';
	
public function ListDex($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0, $order_arr=array())
	{
		require_once 'MyPage.php';
                
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}
		
//		$sql = "select count(*) as c from dex_resource where true ".$sql_where;
//		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
   $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
//		$sql = "select dex.*, dex_resource.id as dex_resource_id, dex_resource.resource_id, (select alias from resource where resource_id = dex_resource.resource_id) as resource_alias from dex left join dex_resource on dex.id = dex_resource.dex_id where 1=1 ".$sql_where.$sql_order;
//		$sql .= " limit '$pageSize' offset '$offset'";
//		//echo $sql;exit();
//		$results = $this->query($sql);
		$results =array();
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
}
