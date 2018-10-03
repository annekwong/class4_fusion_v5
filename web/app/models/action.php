<?php
class Action extends AppModel{
	var $name = 'Action';
	var $useTable = 'alert_action';
	var $primaryKey = 'id';
	
	public function ListAction($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		if ($search_type == 1)
		{
		if (!empty($search_arr['name'])) $sql_where .= " and alert_action.name like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{
			$sql_where = " and  (alert_action.name like '%".addslashes($search_arr['name'])."%')";
		}
		
		$sql = "select count(id) as c from alert_action where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select alert_action.*,trouble_tickets_template.name as template_name from alert_action left join trouble_tickets_template on alert_action.trouble_tickets_template = trouble_tickets_template.id where 1=1".$sql_where;
		
		$sql .= "order by name asc limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getActionNameArr()
	{
		$return = array();
		$sql = "select id,name from alert_action ORDER BY id DESC";
		$results = $this->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['name'];
		}
		return $return;
		
	}
        
        public function getClientNameArr()
	{
		$return = array();
		$sql = "SELECT client_id, name FROM client ORDER BY name ASC";
		$results = $this->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['client_id']] = $v[0]['name'];
		}
		return $return;
		
	}
	
	public function getActionInfoArr()
	{
		$return = array();
		$sql = "select * from alert_action";
		$results = $this->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0];
		}
		return $return;
		
	}
}
?>