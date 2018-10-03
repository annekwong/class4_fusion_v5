<?php
App::import('Model','Order.Order');
class UserTrunk extends AppModel{
	var $name = 'UserTrunk';
	var $useTable = 'resource';
	var $primaryKey = 'resource_id';
	
	
	public function ListOrderUserTrunks($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
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
			if (!empty($search_arr['name'])) $sql_where .= " and res.alias like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{
			$sql_where .= " and (client.name like '%" . addslashes($search_arr['search_value']) . "%' or res.alias like '%" . addslashes($search_arr['search_value']) . "%' or (select ip||':'||port from resource_ip where resource_id=res.resource_id limit 1) like '%" . addslashes($search_arr['search_value']) . "%' )";
		}
		
		$sql = "select count(*) as c from (select * from resource where client_id in (select client_id from order_user where client_id > 0)) as res left join client on res.client_id=client.client_id where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select res.*,client.client_id as real_client_id, client.name as client_name, array(select ip||':'||port from resource_ip where resource_id=res.resource_id) as ip_port, array(select tech_prefix from resource_prefix where resource_id=res.resource_id) as tech_prefix from (select * from resource where client_id in (select client_id from order_user where client_id > 0)) as res left join client on res.client_id=client.client_id where 1=1".$sql_where;
		
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
}
?>