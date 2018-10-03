<?php
class Cdrrerate extends AppModel{
	var $name = 'Cdrrerate';
	var $useTable = 'client_cdr';
	var $primaryKey = 'id';
	
	public function cdr_search($currPage=1, $pageSize=15, $search_arr=array())
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';		
		if (!empty($search_arr['start_time']))
		{
			$sql_where .= " and start_time_of_date::integer >= " . strtotime($search_arr['start_time']);
		}
		if (!empty($search_arr['end_time']))
		{
			$sql_where .= " and release_tod::integer <= " . strtotime($search_arr['end_time']);
		}
		if (!empty($search_arr['orig_client_id']))
		{
			$sql_where .= " and ingress_client_id = '" . addslashes($search_arr['orig_client_id']) . "'";
		}
		if (!empty($search_arr['term_client_id']))
		{
			$sql_where .= " and egress_client_id = '" . addslashes($search_arr['term_client_id']) . "'";
		}
		if (!empty($search_arr['orig_res']))
		{
			$sql_where .= " ingress_id = '" . addslashes($search_arr['orig_res']) . "'";
		}
		if (!empty($search_arr['orig_host']))
		{
			$sql_where .= " and origination_source_host_name = '" . addslashes($search_arr['orig_host']) . "'";
		}
		if (!empty($search_arr['term_res']))
		{
			$sql_where .= " and egress_id = '" . addslashes($search_arr['term_res']) . "'";
		}
		if (!empty($search_arr['term_host']))
		{
			$sql_where .= " and termination_source_host_name = '" . addslashes($search_arr['term_host']) . "'";
		}
		
		$sql_value = '';
		if (!empty($search_arr['orig_rate_table']))
		{
			//$sql_value .= ", (select rate from rate where rate_table_id=".intval($search_arr['orig_rate_table'])." limit 1) as orig_rate";
			$sql_value .= ", (SELECT * from rerate_cdr(id,1,".intval($search_arr['orig_rate_table']).") as cdr(cost integer) )  as orig_cost";
		}
		if (!empty($search_arr['term_rate_table']))
		{
			//$sql_value .= ", (select rate from rate where rate_table_id=".intval($search_arr['term_rate_table'])." limit 1) as term_rate";
			$sql_value .= ", (SELECT * from rerate_cdr(id,1,".intval($search_arr['orig_rate_table']).") as cdr(cost integer) ) as term_cost";
		}
		
		$sql = "select count(id) as c from client_cdr".date("Ymd")." where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select *".$sql_value." from client_cdr".date("Ymd")." where 1=1".$sql_where;
		
		$sql .= " limit '$pageSize' offset '$offset'";
		echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	function rerate_cdr($data)
	{
		$this->data['Cdrrerate'] = $data;
		if (!empty($this->data['Cdrrerate']['id']))
		{
			return $this->save($this->data);
		}
	}
	
}
?>	