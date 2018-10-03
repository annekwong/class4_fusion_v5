<?php
class Log extends AppModel{
	var $name = 'Log';
	var $useTable = 'search_logs';
	var $primaryKey = 'id';
	
	public function ListLog($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		
		require_once 'MyPage.php';
                
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		if ($search_type == 1)
		{

			if (!empty($search_arr['type']))
			{
				$sql_where .= " and type like '%".addslashes($search_arr['type'])."%'";
			}
			if (!empty($search_arr['search_val']))
			{
				$sql_where .= " and  search_val like '%".addslashes($search_arr['search_val'])."%'";
			}

			if (!empty($search_arr['start_date']))
			{
				$sql_where .= " and  search_time >= '".($search_arr['start_date'])."'";
			}
			if (!empty($search_arr['end_date']))
			{
				$sql_where .= " and  search_time::text <= '".($search_arr['end_date'])."'";
			}
			if (!empty($search_arr['name']))
			{
				$sql_where .= " and  client.name like '%".addslashes($search_arr['name'])."%'";
			}
			
		}
		else
		{
			if (!empty($search_arr['search']))
			{
				$sql_where .= " and (type like '%".addslashes($search_arr['search'])."%' or search_val like '%".addslashes($search_arr['search'])."%' or client.name like '%".addslashes($search_arr['search'])."%')";
			}
		}
		
		
		$sql = "select count(id) as c from search_logs left join client on search_logs.client_id=client.client_id where true".$sql_where;
		$totalrecords = $this->query($sql);
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
		$sql = "select search_logs.*,client.name from search_logs left join client on search_logs.client_id=client.client_id where true".$sql_where." order by search_time desc";
		
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
}
?>