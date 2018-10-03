<?php
class AlertReport extends AppModel{
	var $name = 'AlertReport';
	var $useTable = 'alert_exec_log';
	var $primaryKey = 'id';

	/**
	 * 
	 * @param $currPage
	 * @param $pageSize
	 * @param $search_arr 条件搜索数组（暂时没用）
	 * @param $search_type 1模糊查找，其他精确查找
	 * @param $event_type  1--disable trunk
				2--disable host
				3--enable trunk
				4--enable host
				5--disable code trunk
				6--enable code trunk
				7--change priority
				8--email
	 * @param $res_type 1 ingress else egress
	 */
	public function disable_trunk_report($currPage, $pageSize, $search_arr, $search_type,$event_type=1, $res_type=1, $search_where="")
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		if ($search_type == 1)
		{
		//if (!empty($search_arr['name'])) $sql_where .= " and name like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{
			//$sql_where = " and  (name like '%".addslashes($search_arr['name'])."%')";
		}
		$sql_b_where = '';
		
		
		switch ($event_type)
		{
			case 10:									//no destination report
				$sql = "select count(*) as c from (

                                        select product.name, product_items.digits from product_items

                                        inner join product on product_items.product_id = product.product_id

                                        where product_items.item_id not in (select item_id from product_items_resource)

                                        union


                                        select dynamic_route.name, '' as digits from dynamic_route


                                        where dynamic_route.dynamic_route_id not in (select dynamic_route_id from dynamic_route_items) ) as tbl";
				break;
			case 9:									//no alternative route report
				$sql ="select count(*) as c from alternative_route_report";
				break;
			case 8:
				break;
			case 7:
				$sql = "select count(1) as c from priority_view where 1=1 " . $sql_where;
				break;
			case 6:
				break;
			case 5:
				break;
			case 4:
				break;
			case 3:
				break;
			case 2:
				break;
			case 1:
				if ($res_type == 1)
				{
					$sql_b_where .= " and ingress = true";
				}
				else
				{
					$sql_b_where .= " and egress = true";
				}
				$sql = "select count(1) as c from disabled_trunk where (event_type = 1 or event_type =  2)".$sql_b_where . $sql_where;
				break;
			default:
				if ($res_type == 1)
				{
					$sql_b_where .= " and resource.ingress = true";
				}
				else
				{
					$sql_b_where .= " and resource.egress = true";
				}
				$sql = "SELECT 
count(*) AS c

FROM alert_event 
JOIN  alert_rule ON alert_event.alert_rule_id = alert_rule.id 
JOIN  resource ON alert_event.res_id = resource.resource_id WHERE 1=1 " . $sql_b_where . $sql_where . $search_where;			
		}
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		switch ($event_type)
		{
			case 10:
                                $sql = "select product.name, product_items.digits from product_items

                                inner join product on product_items.product_id = product.product_id

                                where product_items.item_id not in (select item_id from product_items_resource)

                                union


                                select dynamic_route.name, '' as digits from dynamic_route


                                where dynamic_route.dynamic_route_id not in (select dynamic_route_id from dynamic_route_items)";
				break;
			case 9:
                                $sql = "select * from alternative_route_report";
				break;
			case 8:
				break;
			case 7:
				$sql = "select * from priority_view where 1=1" . $sql_where;
				break;
			case 6:
				break;
			case 5:
				break;
			case 4:
				break;
			case 3:
				break;
			case 2:
				break;
			case 1:				
				$sql = "select * from disabled_trunk where (event_type = 1 or event_type =  2)" . $sql_b_where . $sql_where;
				break;	
			default:	
				$sql = "SELECT 

alert_rule.name as rule_name,
resource.alias,
alert_event.host_id,
alert_event.alert_action_id,
alert_event.event_time,
resource.route_strategy_id,
alert_event.old_priority,
alert_event.new_priority,
case alert_event.event_type 
when 1 then 'true' when 2 then 'true' when 5 then 'true' else 'false' end as bool

FROM alert_event 
JOIN  alert_rule ON alert_event.alert_rule_id = alert_rule.id 
JOIN  resource ON alert_event.res_id = resource.resource_id WHERE 1=1" . $sql_b_where . $sql_where . $search_where;			
		}
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;exit();
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
        
        public function non_trunk($currPage, $pageSize) {
            require_once 'MyPage.php';
            $page = new MyPage();
            $totalrecords = 0;
            $sql = "SELECT count(*) as c FROM no_dest_trunk";
            $totalrecords = $this->query($sql);
            $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
            $page->setCurrPage($currPage);//当前页
            $page->setPageSize($pageSize);//页大小
            $currPage = $page->getCurrPage()-1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;
            $sql = "SELECT * FROM no_dest_trunk limit '$pageSize' offset '$offset'";
            $results = $this->query($sql);
            $page->setDataArray($results);
            return $page;
        }
	
	
	public function ViewExecutionLog($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = " WHERE alert_rule.name != ''";
		if ($search_type == 1)
		{
			//if (!empty($search_arr['name'])) $sql_where .= " and name like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{
			//$sql_where = " and  (name like '%".addslashes($search_arr['name'])."%')";
		}
		
		$sql = "select count(*) as c FROM alert_exec_log LEFT JOIN alert_event ON alert_exec_log.id = alert_event.alert_exec_id LEFT JOIN alert_rule ON alert_event.alert_rule_id = alert_rule.id".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "SELECT 
	alert_exec_log.start_time, alert_exec_log.end_time, alert_rule.name, alert_exec_log.id,
	(
		SELECT count(*) FROM alert_event WHERE alert_event.alert_exec_id = alert_exec_log.id
	) as cnt
        FROM alert_exec_log LEFT JOIN alert_event ON alert_exec_log.id = alert_event.alert_exec_id LEFT JOIN alert_rule ON alert_event.alert_rule_id = alert_rule.id".$sql_where;
		
		$sql .= " order by id desc limit '$pageSize' offset '$offset'";        
                
		$results = $this->query($sql);
                
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function ListEvent($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = ' and alert_exec_id='.$search_arr['alert_exec_id'];
		if ($search_type == 1)
		{
			//if (!empty($search_arr['name'])) $sql_where .= " and name like '%".addslashes($search_arr['name'])."%'";
		}
		else
		{
			//$sql_where = " and  (name like '%".addslashes($search_arr['name'])."%')";
		}
		
		$sql = "select count(id) as c from alert_event where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from alert_event where 1=1".$sql_where;
		
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
        
        
    public function deleteAll() {

        $result = $this->query("select id from alert_rule");
        foreach ($result as $val) {
            $alert_rule_id = $val[0]['id'];
            $this->query("delete  from alert_rule where id  = {$alert_rule_id}" );
        }
        return true;
    }

    public function deleteSelected($ids) {

        $sql_2 = "delete from alert_rule where id in ($ids)";

        $res_2 = $this->query($sql_2);
        return true;
    }
    
    public function getNameByID($ids){
        $sql = "SELECT name FROM alert_rule where id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }
	
	
}
	