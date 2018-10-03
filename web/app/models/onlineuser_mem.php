<?php

class Onlineuser extends AppModel
{

    var $name = 'Onlineuser';
    var $useTable = 'online_users';
    var $primaryKey = 'online_id';

    function add_online_user($data)
    {
        $data['Onlineuser']['create_time'] = date("Y-m-d   H:i:s");
        $this->save($data ['Onlineuser']);
        $online_id = $this->getlastinsertId();
        return $online_id;
    }

    /**
     * 普通查询
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAll($currPage = 1, $pageSize = 10)
    {
        $login_type = $_SESSION['login_type'];
//damin
        if ($login_type == 1)
        {
            $sql1 = "select count(online_id) as c from online_users ";
            $sql2 = " ";
        }
//reseller
        if ($login_type == 2)
        {
            $reseller_id = $_SESSION['sst_reseller_id'];
            $sql1 = "select count(online_id) as c from online_users where  reseller_id=$reseller_id ";
            $sql2 = "  where  e.reseller_id=$reseller_id";
        }
//client
        if ($login_type == 3)
        {
            $client_id = $_SESSION['sst_client_id'];
            $sql1 = "select count(online_id) as c from online_users  where  client_id=$client_id ";
            $sql2 = "  where  e.client_id=$client_id";
        }

        if ($login_type == 4)
        {
            $card_id = $_SESSION['sst_card_id'];
            $sql1 = "select count(online_id) as c from online_users  where  card_id=$card_id ";
            $sql2 = "  where  e.card_id=$card_id";
        }

//user
        if ($login_type == 5 || $login_type == 6)
        {
            $user_id = $_SESSION['sst_user_id'];
            $sql1 = "select count(online_id) as c from online_users where  user_id=$user_id ";
            $sql2 = "  where e.user_id=$user_id";
        }






//分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($sql1);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  user_name  ,login_time,re.name as  reseller_name   ,user_type  from  online_users  as onl
left join (select name,reseller_id   from  reseller)  re  on  re.reseller_id=onl.reseller_id  $sql2  " . "order by online_id    desc  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 普通查询(通过角色)
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAllby_role($currPage = 1, $pageSize = 10, $role_id)
    {

//分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c from users  where role_id =$role_id");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	

	where u.role_id=$role_id
	order by user_id  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function likequeryby_role($key, $currPage = 1, $pageSize = 10, $role_id)
    {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c 
	 	from users  where  users.role_id=$role_id  and( users.name 
	 	ilike $condition  or ilike like $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=users.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=users.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=$role_id 
	 	and role_name ilike $condition ))
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	 

	
	where u.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=u.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=u.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=u.role_id 
	 	and role_name ilike $condition )
	
	
	
	order by u.user_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function getIngressResource()
    {
        $sql = "SElECT resource_id, alias FROM resource WHERE ingress=true ORDER BY alias ASC";
        return $this->query($sql);
    }

    function getEgressResource()
    {
        $sql = "SElECT resource_id, alias FROM resource WHERE egress=true ORDER BY alias ASC";
        return $this->query($sql);
    }

    function likequery($key, $currPage = 1, $pageSize = 10)
    {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c 
	 	from users  where users.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=users.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=users.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=users.role_id 
	 	and role_name ilike $condition )
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	 

	
	where u.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=u.reseller_id 
	 	and reseller.name like $condition )
	 		 	or (select count(*)>0 from client where client.client_id=u.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=u.role_id 
	 	and role_name ilike $condition )
	
	
	
	order by u.user_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function Advancedquery($data, $currPage = 1, $pageSize = 10)
    {

//解析搜索条件
        $condition = "where   ";
        $i = 0;
        $len = intval(count($data['User']));


        foreach ($data['User'] as $key => $value)
        {

//判断是否存在搜索条件
            if ($value == '')
            {
                continue;
            }
            $tmp = "users." . $key . "='" . $value . "'  and   ";
            $condition = $condition . $tmp;
            $i++;
        }


        $where = substr($condition, 0, strrpos($condition, 'a'));
//pr($where);
//分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c from users  $where");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select users.user_id,users.name,reseller_name,role_name ,users.create_time,users.password,users.active
		    from  users 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=users.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=users.role_id	 
	$where   
	order by users.user_id  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function get_network_total_single($date, $server)
    {
        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }
        $sql = "SELECT report_time, sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel  FROM qos_total WHERE  report_time = '{$date}' {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";
        return $this->query($sql);
    }

    private function get_time_by_duration($duration)
    {
        $duration = (int) $duration;
        switch ($duration)
        {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
            case 6:
                $time = "15 days";
                break;
            case 7:
                $time = "30 days";
                break;
            case 8:
                $time = "60 days";
                break;
        }
        return $time;
    }

    public function get_network_total($duration, $server)
    {
        $time = $this->get_time_by_duration($duration);
        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }
//        $sql = "SELECT to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel FROM qos_total WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}'
//AND CURRENT_TIMESTAMP $server_condition GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00') ORDER BY report_time ASC";
        $sql = "SELECT to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel FROM qos_total WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}'
AND CURRENT_TIMESTAMP $server_condition GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00') ORDER BY report_time ASC";
        if ($duration >= 7)
        {
            $sql = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT report_time, sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel FROM qos_total WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}'
AND CURRENT_TIMESTAMP $server_condition GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
        }
        return $this->query($sql);
    }

    public function get_network_total_report_single($date, $server)
    {
        $server_condition = $this->get_server_conditions($server);
        $sql = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time  FROM qos_route_report WHERE  report_time = '{$date}' {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";
        return $this->query($sql);
    }

    public function get_network_total_report($duration, $server)
    {
        $time = $this->get_time_by_duration($duration);
        $server_condition = $this->get_server_conditions($server);
        $server_condition = '';


        $sql = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time ,sum(case direction when 0 then cost else 0 end) as ingress_cost,sum(case direction when 1 then cost else 0 end) as egress_cost"
            . ",sum(pdd) as pdd FROM qos_route_report WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' "
            . "AND CURRENT_TIMESTAMP {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";

        return $this->query($sql);
    }

    public function get_draw_trunk_data_report_single($direction, $date, $server, $trunk, $show_type, $old_time)
    {
        $server_condition = $this->get_server_conditions($server);
        $server_condition .= " AND direction = {$direction}";
        $sql = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time  FROM qos_route_report WHERE  report_time = '{$date}' "
            . " {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";
        $data = $this->query($sql);
        $report_time_arr = array();
        $return_data = array();
        $ready_data = array();
        if (!empty($data))
        {
            $bill_time = intval($data[0][0]['bill_time']);
            $not_zero_calls = intval($data[0][0]['not_zero_calls']);
            $busy_calls = intval($data[0][0]['busy_calls']);
            $total_calls = intval($data[0][0]['total_calls']);
            $cancel_calls = intval($data[0][0]['cancel_calls']);
//            $ingress_client_cost_total = $item[0]['ingress_client_cost_total'];
//            $egress_cost_total = $item[0]['egress_cost_total'];
//            $pdd = $item[0]['pdd'];
            $report_time = strtotime($data[0][0]['report_time']) * 1000;
            $report_time_arr[$report_time] = array($report_time, 0);
            $ready_data['report_time'] = $report_time;
            $ready_data['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
            $ready_data['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
            $ready_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
            $ready_data['pdd'] = "";
            $ready_data['profitability'] = "";
            $ready_data['revenue'] = "";
        }
        $return_data[] = $ready_data[$show_type];
    }

    public function get_draw_trunk_data_report($direction, $duration, $server, $trunk, $show_type,$zero_data = array())
    {
        $time = $this->get_time_by_duration($duration);
        $server_condition = $this->get_server_conditions($server);
        $server_condition = '';
        $server_condition .= " AND direction = {$direction}";

        $sql = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time  ,sum(case direction when 0 then cost else 0 end) as ingress_cost,sum(case direction when 1 then cost else 0 end) as egress_cost"
            . ",sum(pdd) as pdd  FROM qos_route_report WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP "
            . " {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";

        $data = $this->query($sql);

        $acd_zero_data = $zero_data;
        $abr_zero_data = $zero_data;
        $asr_zero_data = $zero_data;
        $pdd_zero_data = $zero_data;
        $revenue_zero_data = $zero_data;
        $profitability_zero_data = $zero_data;

        $show_acd = 'acd';
        $show_abr = 'abr';
        $show_asr = 'asr';
        $show_pdd = 'pdd';

        $show_revenue = 'revenue';
        $show_profitability = 'profitability';


        if($show_type == 'qos'){
            $draw_data = array(
                $show_acd => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_abr => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_asr => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_pdd => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),



            );
        } else {
            $draw_data = array(

                $show_revenue => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_profitability => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 5,
                        )
                    )
                ),

            );
        }

        $report_time_arr = array();
        $ready_data = array();
        foreach ($data as $key => $item)
        {
            $bill_time = intval($item[0]['bill_time']);
            $not_zero_calls = intval($item[0]['not_zero_calls']);
            $busy_calls = intval($item[0]['busy_calls']);
            $total_calls = intval($item[0]['total_calls']);
            $cancel_calls = intval($item[0]['cancel_calls']);
            $ingress_client_cost_total = floatval($item[0]['ingress_cost']);
            $egress_cost_total = floatval($item[0]['egress_cost']);
            $revenue = $ingress_client_cost_total - $egress_cost_total;
            $pdd = $item[0]['pdd'];
            $report_time = strtotime($item[0]['report_time']) * 1000;
            $report_time_arr[$report_time] = array($report_time, 0);
            $ready_data[$key]['report_time'] = $report_time;
            $ready_data[$key]['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 4) : 0;
            $ready_data[$key]['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 4) : 0;
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
            $ready_data[$key]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 4) : 0;
            $ready_data[$key]['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$key]['revenue'] = $ingress_client_cost_total;
            $ready_data[$key]['profitability'] = !empty($ingress_client_cost_total) ? round($revenue / $ingress_client_cost_total, 5)*100 : 0;
        }


        if($show_type == 'qos'){
            foreach ($ready_data as $item)
            {
                $time_key = $item['report_time'];
                if (array_key_exists($time_key,$acd_zero_data)){
                    $acd_zero_data[$time_key][1] = (float)$item[$show_acd];
                    $abr_zero_data[$time_key][1] = (float)$item[$show_abr];
                    $asr_zero_data[$time_key][1] = (float)$item[$show_asr];
                    $pdd_zero_data[$time_key][1] = (float)$item[$show_pdd];
                }

//                array_push($draw_data[$show_acd][0]['data'], array($item['report_time'], (float) $item[$show_acd]));
//                array_push($draw_data[$show_abr][0]['data'], array($item['report_time'], (float) $item[$show_abr]));
//                array_push($draw_data[$show_asr][0]['data'], array($item['report_time'], (float) $item[$show_asr]));
//                array_push($draw_data[$show_pdd][0]['data'], array($item['report_time'], (float) $item[$show_pdd]));
            }
        } else {
            foreach ($ready_data as $item)
            {
                $time_key = $item['report_time'];
                if (array_key_exists($time_key,$revenue_zero_data)){
                    $revenue_zero_data[$time_key][1] = (float)$item[$show_revenue];
                    $profitability_zero_data[$time_key][1] = (float)$item[$show_profitability];
                }
//                array_push($draw_data[$show_revenue][0]['data'], array($item['report_time'], (float) $item[$show_revenue]));
//                array_push($draw_data[$show_profitability][0]['data'], array($item['report_time'], (float) $item[$show_profitability]));
            }
        }

        sort($acd_zero_data);
        sort($abr_zero_data);
        sort($asr_zero_data);
        sort($pdd_zero_data);
        sort($revenue_zero_data);
        sort($profitability_zero_data);
        $draw_data[$show_acd][0]['data'] = $acd_zero_data;
        $draw_data[$show_abr][0]['data'] = $abr_zero_data;
        $draw_data[$show_asr][0]['data'] = $asr_zero_data;
        $draw_data[$show_pdd][0]['data'] = $pdd_zero_data;
        $draw_data[$show_revenue][0]['data'] = $revenue_zero_data;
        $draw_data[$show_profitability][0]['data'] = $profitability_zero_data;

        $select_trunk = array();
        if (!ctype_digit($trunk))
        {
            $limit = "";
            switch ($trunk)
            {
                case "top5":
                    $limit = "LIMIT 5";
                    break;
                case "top10":
                    $limit = "LIMIT 10";
                    break;
                case "top15":
                    $limit = "LIMIT 15";
                    break;
                case "top20":
                    $limit = "LIMIT 20";
                    break;
                case "all":
                    $limit = "";
                    break;
            }
            $sql = "SELECT resource_id, (SELECT alias FROM resource WHERE resource_id = qos_route_report.resource_id limit 1) as name FROM qos_route_report 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND resource_id is not null $server_condition  GROUP BY resource_id ORDER BY sum(total_calls) DESC {$limit}";
            $result = $this->query($sql);


            foreach ($result as $item)
            {
                $select_trunk['report']['res_id'][] = $item[0]['resource_id'];
                $call_data2 = array();
                $sql_item = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
                    . ",sum(bill_time) as bill_time,sum(pdd) as pdd  FROM qos_route_report WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP "
                    . " AND resource_id = {$item[0]['resource_id']} {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";

                $item_results = $this->query($sql_item);


                $ready_data2 = array();
                foreach ($item_results as $key => $item_results_item)
                {
                    $bill_time = intval($item_results_item[0]['bill_time']);
                    $not_zero_calls = intval($item_results_item[0]['not_zero_calls']);
                    $busy_calls = intval($item_results_item[0]['busy_calls']);
                    $total_calls = intval($item_results_item[0]['total_calls']);
                    $cancel_calls = intval($item_results_item[0]['cancel_calls']);
//            $ingress_client_cost_total = $item_results_item[0]['ingress_client_cost_total'];
//            $egress_cost_total = $item_results_item[0]['egress_cost_total'];
                    $pdd = $item_results_item[0]['pdd'];
                    $report_time = strtotime($item_results_item[0]['report_time']) * 1000;
                    $ready_data2[$key]['report_time'] = $report_time;
                    $ready_data2[$key]['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 4) : 0;
                    $ready_data2[$key]['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 4) : 0;
                    $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                    $ready_data2[$key]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 4) : 0;
                    $ready_data2[$key]['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
                    $ready_data2[$key]['profitability'] = "";
                    $ready_data2[$key]['revenue'] = "";
                }
//                $test_arr = array();

                $item_acd_zero_data = $zero_data;
                $item_abr_zero_data = $zero_data;
                $item_asr_zero_data = $zero_data;
                $item_pdd_zero_data = $zero_data;
                $item_revenue_zero_data = $zero_data;
                $item_profitability_zero_data = $zero_data;

                $report_time_arr2 = array();
                if($show_type == 'qos') {
                    foreach ($ready_data2 as $item_results_item) {

                        $time_key = $item_results_item['report_time'];
                        if (array_key_exists($time_key,$item_acd_zero_data)){
                            $item_acd_zero_data[$time_key][1] = (float)$item_results_item[$show_acd];
                            $item_abr_zero_data[$time_key][1] = (float)$item_results_item[$show_abr];
                            $item_asr_zero_data[$time_key][1] = (float)$item_results_item[$show_asr];
                            $item_pdd_zero_data[$time_key][1] = (float)$item_results_item[$show_pdd];
                        }
//                        $report_time_key = $item_results_item['report_time'];
//                        if (key_exists($report_time_key, $report_time_arr)) {
////                        $test_arr[] = array($report_time_key,$item_results_item[$show_type]);
//                            $report_time_arr2[$report_time_key][$show_acd] = array($report_time_key, (float)$item_results_item[$show_acd]);
//                            $report_time_arr2[$report_time_key][$show_abr] = array($report_time_key, (float)$item_results_item[$show_abr]);
//                            $report_time_arr2[$report_time_key][$show_asr] = array($report_time_key, (float)$item_results_item[$show_asr]);
//                            $report_time_arr2[$report_time_key][$show_pdd] = array($report_time_key, (float)$item_results_item[$show_pdd]);
//                        }
                    }
                    sort($item_acd_zero_data);
                    sort($item_abr_zero_data);
                    sort($item_asr_zero_data);
                    sort($item_pdd_zero_data);
//                    foreach ($report_time_arr2 as $report_time_arr_item) {
//                        $call_data2[$show_acd][] = $report_time_arr_item[$show_acd];
//                        $call_data2[$show_abr][] = $report_time_arr_item[$show_abr];
//                        $call_data2[$show_asr][] = $report_time_arr_item[$show_asr];
//                        $call_data2[$show_pdd][] = $report_time_arr_item[$show_pdd];
//                    }
                    array_push($draw_data[$show_acd], array(
                        'name' => $item[0]['name'],
                        'data' => $item_acd_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_abr], array(
                        'name' => $item[0]['name'],
                        'data' => $item_abr_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_asr], array(
                        'name' => $item[0]['name'],
                        'data' => $item_asr_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_pdd], array(
                        'name' => $item[0]['name'],
                        'data' => $item_pdd_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));

                }else{
                    foreach ($ready_data2 as $item_results_item) {
                        $time_key = $item_results_item['report_time'];
                        if (array_key_exists($time_key,$item_revenue_zero_data)){
                            $item_revenue_zero_data[$time_key][1] = (float)$item_results_item[$show_revenue];
                            $item_profitability_zero_data[$time_key][1] = (float)$item_results_item[$show_profitability];
                        }
//                        $report_time_key = $item_results_item['report_time'];
//                        if (key_exists($report_time_key, $report_time_arr)) {
////                        $test_arr[] = array($report_time_key,$item_results_item[$show_type]);
//                            $report_time_arr2[$report_time_key][$show_revenue] = array($report_time_key, (float)$item_results_item[$show_revenue]);
//                            $report_time_arr2[$report_time_key][$show_profitability] = array($report_time_key, (float)$item_results_item[$show_profitability]);
//                        }
                    }
//                    foreach ($report_time_arr2 as $report_time_arr_item) {
//                        $call_data2[$show_revenue][] = $report_time_arr_item[$show_revenue];
//                        $call_data2[$show_profitability][] = $report_time_arr_item[$show_profitability];
//                    }

                    sort($item_revenue_zero_data);
                    sort($item_profitability_zero_data);
                    array_push($draw_data[$show_revenue], array(
                        'name' => $item[0]['name'],
                        'data' => $item_revenue_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_profitability], array(
                        'name' => $item[0]['name'],
                        'data' => $item_profitability_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 5,
                        )
                    ));

                }
            }
            $draw_data['select_trunk'] = $select_trunk;
            return $draw_data;
        }
        else
        {
            $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $call_data3 = array();
                $sql_item = "SELECT report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
                    . ",sum(bill_time) as bill_time,sum(pdd) as pdd  FROM qos_route_report WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP "
                    . " AND resource_id = {$item[0]['res_id']} {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";

                $item_results = $this->query($sql_item);
                $ready_data3 = array();
                foreach ($item_results as $key => $item_results_item)
                {
                    $bill_time = intval($item_results_item[0]['bill_time']);
                    $not_zero_calls = intval($item_results_item[0]['not_zero_calls']);
                    $busy_calls = intval($item_results_item[0]['busy_calls']);
                    $total_calls = intval($item_results_item[0]['total_calls']);
                    $cancel_calls = intval($item_results_item[0]['cancel_calls']);
//            $ingress_client_cost_total = $item_results_item[0]['ingress_client_cost_total'];
//            $egress_cost_total = $item_results_item[0]['egress_cost_total'];
                    $pdd = $item_results_item[0]['pdd'];
                    $report_time = strtotime($item_results_item[0]['report_time']) * 1000;
                    $ready_data3[$key]['report_time'] = $report_time;
                    $ready_data3[$key]['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 4) : 0;
                    $ready_data3[$key]['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 4) : 0;
                    $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                    $ready_data3[$key]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 4) : 0;
                    $ready_data3[$key]['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
                    $ready_data3[$key]['profitability'] = "";
                    $ready_data3[$key]['revenue'] = "";
                }

                $item_acd_zero_data = $zero_data;
                $item_abr_zero_data = $zero_data;
                $item_asr_zero_data = $zero_data;
                $item_pdd_zero_data = $zero_data;
                $item_revenue_zero_data = $zero_data;
                $item_profitability_zero_data = $zero_data;
                if($show_type == 'qos') {
                    foreach ($ready_data3 as $item_results_item) {

                        $time_key = $item_results_item['report_time'];
                        if (array_key_exists($time_key,$item_acd_zero_data)){
                            $item_acd_zero_data[$time_key][1] = (float)$item_results_item[$show_acd];
                            $item_abr_zero_data[$time_key][1] = (float)$item_results_item[$show_abr];
                            $item_asr_zero_data[$time_key][1] = (float)$item_results_item[$show_asr];
                            $item_pdd_zero_data[$time_key][1] = (float)$item_results_item[$show_pdd];
                        }

//                        $report_time_key = $item_results_item['report_time'];
//                        if (key_exists($report_time_key, $report_time_arr)) {
////                        $test_arr[] = array($report_time_key,$item_results_item[$show_type]);
//                            $report_time_arr3[$report_time_key][$show_acd] = array($report_time_key, (float)$item_results_item[$show_acd]);
//                            $report_time_arr3[$report_time_key][$show_abr] = array($report_time_key, (float)$item_results_item[$show_abr]);
//                            $report_time_arr3[$report_time_key][$show_asr] = array($report_time_key, (float)$item_results_item[$show_asr]);
//                            $report_time_arr3[$report_time_key][$show_pdd] = array($report_time_key, (float)$item_results_item[$show_pdd]);
//                        }
                    }
                    sort($item_acd_zero_data);
                    sort($item_abr_zero_data);
                    sort($item_asr_zero_data);
                    sort($item_pdd_zero_data);

//                    foreach ($report_time_arr3 as $report_time_arr_item) {
//                        $call_data3[$show_acd][] = $report_time_arr_item[$show_acd];
//                        $call_data3[$show_abr][] = $report_time_arr_item[$show_abr];
//                        $call_data3[$show_asr][] = $report_time_arr_item[$show_asr];
//                        $call_data3[$show_pdd][] = $report_time_arr_item[$show_pdd];
//                    }
                    array_push($draw_data[$show_acd], array(
                        'name' => $item[0]['name'],
                        'data' => $item_acd_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_abr], array(
                        'name' => $item[0]['name'],
                        'data' => $item_abr_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_asr], array(
                        'name' => $item[0]['name'],
                        'data' => $item_asr_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_pdd], array(
                        'name' => $item[0]['name'],
                        'data' => $item_pdd_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));

                }else{
                    foreach ($ready_data3 as $item_results_item) {

                        $time_key = $item_results_item['report_time'];
                        if (array_key_exists($time_key,$item_revenue_zero_data)){
                            $item_revenue_zero_data[$time_key][1] = (float)$item_results_item[$show_revenue];
                            $item_profitability_zero_data[$time_key][1] = (float)$item_results_item[$show_profitability];
                        }

//                        $report_time_key = $item_results_item['report_time'];
//                        if (key_exists($report_time_key, $report_time_arr)) {
////                        $test_arr[] = array($report_time_key,$item_results_item[$show_type]);
//                            $report_time_arr3[$report_time_key][$show_revenue] = array($report_time_key, (float)$item_results_item[$show_revenue]);
//                            $report_time_arr3[$report_time_key][$show_profitability] = array($report_time_key, (float)$item_results_item[$show_profitability]);
//                        }
                    }
//                    foreach ($report_time_arr3 as $report_time_arr_item) {
//                        $call_data3[$show_revenue][] = $report_time_arr_item[$show_revenue];
//                        $call_data3[$show_profitability][] = $report_time_arr_item[$show_profitability];
//                    }

                    sort($item_revenue_zero_data);
                    sort($item_profitability_zero_data);
                    array_push($draw_data[$show_revenue], array(
                        'name' => $item[0]['name'],
                        'data' => $item_revenue_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    ));
                    array_push($draw_data[$show_profitability], array(
                        'name' => $item[0]['name'],
                        'data' => $item_profitability_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 5,
                        )
                    ));
                }

            }
        }

        return $draw_data;
    }

    public function get_network_call_atempts($start_time, $end_time, $type, $server)
    {
        $duration = (int) $duration;
        $type = (int) $type;

        $draw_data = array(
            'call' => array(
                array(
                    'name' => 'CALL',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
            'cps' => array(
                array(
                    'name' => 'CPS',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
            'channel' => array(
                array(
                    'name' => 'Channel',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
        );

        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }

        switch ($type)
        {
            case 1:
                $field = "channels";
                break;
            case 2:
                $field = "ingress_channels";
                break;
            case 3:
                $field = "egress_channels";
                break;
        }

        $sql = "SELECT report_time, sum({$field}) as channels  FROM qos_total WHERE  report_time BETWEEN '{$start_time}' and '{$end_time}' and {$field} is not null {$server_condition} GROUP BY report_time  ORDER BY report_time ASC";

        $result = $this->query($sql);
        $draw_lines = array();
        foreach ($result as $item)
        {
            array_push($draw_data['call'][0]['data'], array(strtotime($item[0]['report_time']) * 1000, $item[0]['channels'] == null ? 0 : (float) $item[0]['channels']));
        }

        return $draw_data;
    }

    public function get_draw_trunk_data_single($type, $duration, $date, $trunk, $trunk_ip, $server, $old_time)
    {
        $time = $this->get_time_by_duration($duration);
        $return_data = array();
        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }

        if (empty($trunk_ip))
        {
            $sql_total = "SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_resource WHERE "
                . "report_time = '{$date}' AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
        }
        else
        {
            $sql_total = "SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_ip WHERE "
                . "report_time = '{$date}' AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
        }
        $total_results = $this->query($sql_total);
        if ($total_results)
        {
            $return_data['call'][] = $total_results[0][0]['call'];
            $return_data['cps'][] = $total_results[0][0]['cps'];
            $return_data['channel'][] = $total_results[0][0]['channel'];
        }
        else
        {
            $return_data['call'][] = 0;
            $return_data['cps'][] = 0;
            $return_data['channel'][] = 0;
        }


        // ORIG
        if (!empty($trunk_ip))
        {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $sql_item = "SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_ip "
                    . "WHERE report_time = '{$date}' AND ip_id = {$item[0]['resource_ip_id']} AND direction = {$type} $server_condition"
                    . " GROUP BY report_time ORDER BY report_time ASC";
                $item_results = $this->query($sql_item);
                $call_item = 0;
                $cps_item = 0;
                $channel_item = 0;
                foreach ($item_results as $item_item)
                {
                    $call_item = (float) $item_item[0]['call'];
                    $cps_item = (float) $item_item[0]['cps'];
                    $channel_item = (float) $item_item[0]['channel'];
                }
                $return_data['call'][] = $call_item;
                $return_data['cps'][] = $cps_item;
                $return_data['channel'][] = $channel_item;
            }
        }
        else
        {
            if (!ctype_digit($trunk))
            {
                $limit = "";
                switch ($trunk)
                {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN '{$old_time}'::TIMESTAMP with time zone - interval '{$time}' AND '{$old_time}'::TIMESTAMP with time zone AND direction = {$type}"
                    . "$server_condition GROUP BY res_id ORDER BY sum(call) DESC {$limit}";
                $call_result = $this->query($sql);
                foreach ($call_result as $item)
                {
                    $sql_item = "SELECT report_time, sum(call) as call FROM qos_resource WHERE report_time = '{$date}' "
                        . "AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
                    $item_results = $this->query($sql_item);
                    $call_item = 0;
                    foreach ($item_results as $item_item)
                    {
                        $call_item = (float) $item_item[0]['call'];
                    }
                    $return_data['call'][] = $call_item;
                }

                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN '{$old_time}'::TIMESTAMP with time zone - interval '{$time}' AND '{$old_time}'::TIMESTAMP with time zone AND direction = {$type} "
                    . "$server_condition GROUP BY res_id ORDER BY sum(cps) DESC {$limit}";

                $cps_result = $this->query($sql);
                foreach ($cps_result as $item)
                {
                    $sql_item = "SELECT report_time, sum(cps) as cps FROM qos_resource WHERE report_time = '{$date}' "
                        . "AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
                    $item_results = $this->query($sql_item);
                    $cps_item = 0;
                    foreach ($item_results as $item_item)
                    {
                        $cps_item = (float) $item_item[0]['cps'];
                    }
                    $return_data['cps'][] = $cps_item;
                }

//beign channel
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN '{$old_time}'::TIMESTAMP with time zone - interval '{$time}' AND '{$old_time}'::TIMESTAMP with time zone AND direction = {$type}"
                    . " GROUP BY res_id ORDER BY sum(channels) DESC {$limit}";
                $channel_result = $this->query($sql);
                foreach ($channel_result as $item)
                {
                    $sql_item = "SELECT report_time, sum(channels) as channel FROM qos_resource WHERE report_time = '{$date}'"
                        . " AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
                    $item_results = $this->query($sql_item);
                    $channel_item = 0;
                    foreach ($item_results as $item_item)
                    {
                        $channel_item = (float) $item_item[0]['channel'];
                    }
                    $return_data['channel'][] = $channel_item;
                }
// end channel
            }
            else
            {
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
                $result = $this->query($sql);
                foreach ($result as $item)
                {
                    $sql_item = "SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_resource WHERE"
                        . " report_time = '{$date}' AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition GROUP BY report_time ORDER BY report_time ASC";
                    $item_results = $this->query($sql_item);
                    $call_item = 0;
                    $cps_item = 0;
                    $channel_item = 0;
                    foreach ($item_results as $item_item)
                    {
                        $call_item = (float) $item_item[0]['call'];
                        $cps_item = (float) $item_item[0]['cps'];
                        $channel_item = (float) $item_item[0]['channel'];
                    }

                    $return_data['call'][] = $call_item;
                    $return_data['cps'][] = $cps_item;
                    $return_data['channel'][] = $channel_item;
                }
            }
        }
        return $return_data;
    }

    public function get_draw_trunk_data($type, $duration, $trunk, $trunk_ip, $server,$zero_data = array())
    {


        $time = $this->get_time_by_duration($duration);
        $draw_data = array(
            'call' => array(
                array(
                    'name' => 'Total',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
            'cps' => array(
                array(
                    'name' => 'Total',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
            'channel' => array(
                array(
                    'name' => 'Total',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                )
            ),
        );


        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }

        if (empty($trunk_ip))
        {
            $sql_total = "SELECT to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_resource WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
            if ($duration >= 7)
                $sql_total = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_resource WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
        } else
        {
            $sql_total = "SELECT to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_ip WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
            if ($duration >= 7)
                $sql_total = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_ip WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
        }
        $total_results = $this->query($sql_total);

        $total_call_zero_data = $zero_data;
        $total_cps_zero_data = $zero_data;
        $total_channel_zero_data = $zero_data;

        foreach ($total_results as $total_item)
        {
//            if ($total_item[0]['call'] > 0)

            $time_key = strtotime($total_item[0]['report_time']) * 1000;
            if (array_key_exists($time_key,$total_call_zero_data)){
                $total_call_zero_data[$time_key][1] = intval($total_item[0]['call']);
                $total_cps_zero_data[$time_key][1] = intval($total_item[0]['cps']);
                $total_channel_zero_data[$time_key][1] = intval($total_item[0]['channel']);
            }
//            array_push($draw_data['call'][0]['data'], array(strtotime($total_item[0]['report_time']) * 1000, (float) $total_item[0]['call']));
////            if ($total_item[0]['cps'] > 0)
//            array_push($draw_data['cps'][0]['data'], array(strtotime($total_item[0]['report_time']) * 1000, (float) $total_item[0]['cps']));
////            if ($total_item[0]['channel'] > 0)
//            array_push($draw_data['channel'][0]['data'], array(strtotime($total_item[0]['report_time']) * 1000, (float) $total_item[0]['channel']));
        }
        sort($total_call_zero_data);
        sort($total_cps_zero_data);
        sort($total_channel_zero_data);
        $draw_data['call'][0]['data'] = $total_call_zero_data;
        $draw_data['cps'][0]['data'] = $total_cps_zero_data;
        $draw_data['channel'][0]['data'] = $total_channel_zero_data;


// ORIG
        $select_trunk = array();
        if (!empty($trunk_ip))
        {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
//                $call_data = array();
//                $cps_data = array();
//                $channel_data = array();

                $sql_item = "SELECT
to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_ip
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND ip_id = {$item[0]['resource_ip_id']} AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
                if ($duration >= 7)
                {
                    $sql_item = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT
report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_ip
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND ip_id = {$item[0]['resource_ip_id']} AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
                }

                $item_results = $this->query($sql_item);

                $ip_call_zero_data = $zero_data;
                $ip_cps_zero_data = $zero_data;
                $ip_channel_zero_data = $zero_data;

                foreach ($item_results as $item_item)
                {
                    $time_key = strtotime($item_item[0]['report_time']) * 1000;
                    if (array_key_exists($time_key,$ip_call_zero_data)){
                        $ip_call_zero_data[$time_key][1] = intval($item_item[0]['call']);
                        $ip_cps_zero_data[$time_key][1] = intval($item_item[0]['cps']);
                        $ip_channel_zero_data[$time_key][1] = intval($item_item[0]['channel']);
                    }
////                    if ($item_item[0]['call'] > 0)
//                    array_push($call_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['call']));
////                    if ($item_item[0]['cps'] > 0)
//                    array_push($cps_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['cps']));
////                    if ($item_item[0]['channel'] > 0)
//                    array_push($channel_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['channel']));
                }
                sort($ip_call_zero_data);
                sort($ip_cps_zero_data);
                sort($ip_channel_zero_data);


                array_push($draw_data['call'], array(
                    'name' => $item[0]['ip'],
                    'data' => $ip_call_zero_data,
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                ));

                array_push($draw_data['cps'], array(
                    'name' => $item[0]['ip'],
                    'data' => $ip_cps_zero_data,
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                ));

                array_push($draw_data['channel'], array(
                    'name' => $item[0]['ip'],
                    'data' => $ip_channel_zero_data,
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                ));
            }
        }
        else
        {
            if (!ctype_digit($trunk))
            {
                $limit = "";
                switch ($trunk)
                {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}"
                    . "$server_condition GROUP BY res_id ORDER BY sum(call) DESC {$limit}";
                $result = $this->query($sql);
                foreach ($result as $item)
                {
                    $select_trunk['call']['res_id'][] = $item[0]['res_id'];
                    $call_data = array();

                    $sql_item = "SELECT
to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
                    if ($duration >= 7)
                    {
                        $sql_item = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT
report_time, sum(call) as call
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    }
                    $item_results = $this->query($sql_item);
                    $item_call_zero_data = $zero_data;

                    foreach ($item_results as $item_item)
                    {
                        $time_key = strtotime($item_item[0]['report_time']) * 1000;
                        if (array_key_exists($time_key,$item_call_zero_data)){
                            $item_call_zero_data[$time_key][1] = intval($item_item[0]['call']);
                        }
//                        if ($item_item[0]['call'] > 0)
//                        array_push($call_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['call']));
                    }
                    sort($item_call_zero_data);

                    array_push($draw_data['call'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_call_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));
                }

                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type} "
                    . "$server_condition GROUP BY res_id ORDER BY sum(cps) DESC {$limit}";

                $result = $this->query($sql);
                foreach ($result as $item)
                {
                    $select_trunk['cps']['res_id'][] = $item[0]['res_id'];
//                    $cps_data = array();

                    $sql_item = "SELECT
to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(cps) as cps
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
                    if ($duration >= 7)
                    {
                        $sql_item = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT
report_time, sum(cps) as cps
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    }

                    $item_results = $this->query($sql_item);

                    $item_cps_zero_data = $zero_data;

                    foreach ($item_results as $item_item)
                    {
                        $time_key = strtotime($item_item[0]['report_time']) * 1000;
                        if (array_key_exists($time_key,$item_cps_zero_data)){
                            $item_cps_zero_data[$time_key][1] = intval($item_item[0]['cps']);
                        }
//                        if ($item_item[0]['cps'] > 0)
//                        array_push($cps_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['cps']));
                    }

                    sort($item_cps_zero_data);

                    array_push($draw_data['cps'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_cps_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));
                }

//beign channel
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource "
                    . "WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}"
                    . " GROUP BY res_id ORDER BY sum(channels) DESC {$limit}";
                $result = $this->query($sql);
                foreach ($result as $item)
                {
                    $select_trunk['channel']['res_id'][] = $item[0]['res_id'];
//                    $channel_data = array();


                    $sql_item = "SELECT
to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(channels) as channel
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
                    if ($duration >= 7)
                    {
                        $sql_item = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT
report_time, sum(channels) as channel
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    }

                    $item_results = $this->query($sql_item);
                    $item_channel_zero_data = $zero_data;

                    foreach ($item_results as $item_item)
                    {
                        $time_key = strtotime($item_item[0]['report_time']) * 1000;
                        if (array_key_exists($time_key,$item_channel_zero_data)){
                            $item_channel_zero_data[$time_key][1] = intval($item_item[0]['channel']);
                        }
//                        if ($item_item[0]['channel'] > 0)
//                        array_push($channel_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['channel']));
                    }

                    sort($item_channel_zero_data);

                    array_push($draw_data['channel'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_channel_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));
                }
// end channel
            }
            else
            {
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
                $result = $this->query($sql);
                foreach ($result as $item)
                {
                    $call_data = array();
                    $cps_data = array();
                    $channel_data = array();


                    $sql_item = "SELECT
to_char(report_time,'YYYY-MM-DD HH24:MI:00') as report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY to_char(report_time,'YYYY-MM-DD HH24:MI:00')
ORDER BY report_time ASC";
                    if ($duration >= 7)
                    {
                        $sql_item = "SELECT to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT
report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_resource
WHERE
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP
AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition
GROUP BY report_time) AS t GROUP BY to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    }

                    $item_results = $this->query($sql_item);

                    $item_call_zero_data = $zero_data;
                    $item_cps_zero_data = $zero_data;
                    $item_channel_zero_data = $zero_data;

                    foreach ($item_results as $item_item)
                    {

                        $time_key = strtotime($item_item[0]['report_time']) * 1000;
                        if (array_key_exists($time_key,$item_call_zero_data)){
                            $item_call_zero_data[$time_key][1] = intval($item_item[0]['call']);
                            $item_cps_zero_data[$time_key][1] = intval($item_item[0]['cps']);
                            $item_channel_zero_data[$time_key][1] = intval($item_item[0]['channel']);
                        }
////                        if ($item_item[0]['call'] > 0)
//                        array_push($call_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['call']));
////                        if ($item_item[0]['cps'] > 0)
//                        array_push($cps_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['cps']));
////                        if ($item_item[0]['channel'] > 0)
//                        array_push($channel_data, array(strtotime($item_item[0]['report_time']) * 1000, (float) $item_item[0]['channel']));
                    }

                    sort($item_call_zero_data);
                    sort($item_cps_zero_data);
                    sort($item_channel_zero_data);

                    array_push($draw_data['call'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_call_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));

                    array_push($draw_data['cps'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_cps_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));

                    array_push($draw_data['channel'], array(
                        'name' => $item[0]['name'],
                        'data' => $item_channel_zero_data,
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    ));
                }
            }
        }

        $draw_data['select_trunk'] = $select_trunk;
        return $draw_data;
    }

    /*
     * $type 0 Orig 1 Term
     */

    public function get_draw_trunk_data1($type, $duration, $trunk, $trunk_ip)
    {
        $draw_lines_call = "";
        $draw_lines_cps = "";

        $duration = (int) $duration;

        switch ($duration)
        {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
        }


        if (empty($trunk_ip))
        {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps  FROM qos_resource  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
        }
        else
        {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps  FROM qos_ip  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
        }




        $total_results = $this->query($sql_total);
        $draw_lines_call .= "<series type=\"line\" label=\"Total\">";
        $draw_lines_cps .= "<series type=\"line\" label=\"Total\">";
        foreach ($total_results as $total_item)
        {
//            if ($item_item[0]['call'] > 0)
            $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['call']);
//            if ($item_item[0]['cps'] > 0)
            $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['cps']);
        }
        $draw_lines_call .= "</series>";
        $draw_lines_cps .= "</series>";


// ORIG
        if (!empty($trunk_ip))
        {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps
    FROM qos_ip 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND ip_id  = {$item[0]['resource_ip_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item)
                {
//                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
//                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
            }
        }
        else
        {
            if (!ctype_digit($trunk))
            {
                $limit = "";
                switch ($trunk)
                {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}  GROUP BY res_id ORDER BY sum(call) DESC {$limit}";
            }
            else
            {
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
            }

            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps
    FROM qos_resource 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND res_id = {$item[0]['res_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item)
                {
//                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
//                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
            }
        }


        $content_call = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Channel" />
  <data>
      {$draw_lines_call}
  </data>
</chart>
EOT;
        $content_cps = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CPS" />
  <data>
      {$draw_lines_cps}
  </data>
</chart>
EOT;
        return array('call' => $content_call, 'cps' => $content_cps);
    }

    public function get_asr_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls, 
    sum(ingress_busy_calls) as busy_calls, 
    sum(ingress_cancel_calls) as cancel_calls 
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls, 
    sum(egress_busy_calls) as busy_calls, 
    sum(egress_cancel_calls) as cancel_calls 
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = ($item[0]['busy_calls'] + $item[0]['cancel_calls'] + $item[0]['not_zero_calls']) == 0 ? 0 : round($item[0]['not_zero_calls'] / ($item[0]['busy_calls'] + $item[0]['cancel_calls'] + $item[0]['not_zero_calls']) * 100, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="ASR" />
  <data>
      <series type="line" label="ASR">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_acd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(duration) as duration,
    sum(not_zero_calls) as not_zero_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(duration) as duration,
    sum(not_zero_calls) as not_zero_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = round($item[0]['not_zero_calls'] == 0 ? 0 : $item[0]['duration'] / $item[0]['not_zero_calls'] / 60, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="ACD" />
  <data>
      <series type="line" label="ACD">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_calls_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }

            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_total_calls) as total_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }

            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }

            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_total_calls) as total_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = round($item[0]['total_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Calls" />
  <data>
      <series type="line" label="Total Calls">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_billable_time_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_bill_time) as bill_time
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_bill_time) as bill_time
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = round($item[0]['bill_time'] / 60, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Billable Time" />
  <data>
      <series type="line" label="Total Billable Time">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_pdd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls,
    sum(pdd) as pdd
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls,
    sum(pdd) as pdd
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = round($item[0]['not_zero_calls'] == 0 ? 0 : $item[0]['pdd'] / $item[0]['not_zero_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="PDD" />
  <data>
      <series type="line" label="PDD">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_cost_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_call_cost) as call_cost,
    sum(lnp_cost) as lnp_cost
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_call_cost) as call_cost
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        if ($report_type == 0)
        {
            foreach ($result as $item)
            {
                $line = round($item[0]['call_cost'] + $item[0]['lnp_cost'], 5);
                array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
            }
        }
        else
        {
            foreach ($result as $item)
            {
                $line = round($item[0]['call_cost'], 5);
                array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
            }
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Cost" />
  <data>
      <series type="line" label="Total Cost">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_margin_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(lnp_cost) as lnp_cost,
    sum(ingress_call_cost) as ingress_call_cost,
    sum(egress_call_cost) as egress_call_cost
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(lnp_cost) as lnp_cost,
    sum(ingress_call_cost) as ingress_call_cost,
    sum(egress_call_cost) as egress_call_cost
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = 0;
            if ($item[0]['ingress_call_cost'] + $item[0]['lnp_cost'] != 0){
                $line = round(($item[0]['ingress_call_cost'] + $item[0]['lnp_cost'] - $item[0]['egress_call_cost'])/($item[0]['ingress_call_cost'] + $item[0]['lnp_cost']), 5);
            }
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Margin" />
  <data>
      <series type="line" label="Margin">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_call_attemp($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str)
    {

        if ($report_type == 0)
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }
        else
        {
            $where = array();
            if (!empty($country))
            {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination))
            {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk))
            {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }

            if (!empty($egress_trunk))
            {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls
    from " . CDR_TABLE . " 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item)
        {
            $line = round($item[0]['not_zero_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Not Zero Call" />
  <data>
      <series type="line" label="Call attempt">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }


    public function get_ajax_text1($time){

        $tz = $this->get_sys_timezone();


        if($time==1){
//            $end_time = $this->get_report_maxtime(date('Y-m-d H:i:00',strtotime("-2 hours")), date('Y-m-d H:i:00'));
//            $start_time = date('Y-m-d H:i:00',strtotime($end_time) - 3600);
            return array(
                'non_zero_calls' => 0,
                'bill_time' => 0,
                'ingress_cost' => 0,
                'egress_cost' => 0,
                'busy_calls' => 0,
                'cancel_calls' => 0,
            );
        } elseif($time == 2){
            $end_time = strtotime(date('Y-m-d H:i:00')) - 60 - 1;
            $start_time = strtotime(date('Y-m-d H:i:00')) - 24*3600 - 60;
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;

        } else{
            $end_time = strtotime(date('Y-m-d H:i:00')) - 60 - 1;
            $start_time = strtotime( date('Y-m-d 00:00:00', strtotime('-6 day')) );
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;
        }



        $sql = $this->ajax_text1_get_report($start_time,$end_time);

        $rst = $this->query($sql);
        $data = array();
        $data['non_zero_calls'] = $rst[0][0]['not_zero_calls'] + 0;
        $data['bill_time'] = $rst[0][0]['bill_time'];
        $data['acd'] = !empty($rst[0][0]['not_zero_calls']) ? number_format($rst[0][0]['bill_time'] / $rst[0][0]['not_zero_calls'] / 60 * 100, 2) : 0;
        $asr_ = intval($rst[0][0]['busy_calls']) + intval($rst[0][0]['cancel_calls']) + intval($rst[0][0]['not_zero_calls']);
        $data['asr'] = !empty($asr_) ? number_format($rst[0][0]['not_zero_calls'] / $asr_ * 100, 2) : 0;
        $tem = $rst[0][0]['ingress_cost'] - $rst[0][0]['egress_cost'];
        $data['ingress_cost'] = $rst[0][0]['ingress_cost'];
        $data['egress_cost'] = $rst[0][0]['egress_cost'];
        $data['revenue'] = $rst[0][0]['ingress_cost'];
        $data['profitability'] = !empty($rst[0][0]['ingress_cost']) ? number_format($tem / $rst[0][0]['ingress_cost'] * 100, 2) : 0;
        $data['revenue'] = number_format($data['revenue'], 2);

        return $data;
    }


    public function get_ajax_text1_bak($time){
        $tz = $this->get_sys_timezone();


        if($time==1){
            $end_time = strtotime(date('Y-m-d H:i:00')) - 60 - 1;

            $start_time = strtotime(date('Y-m-d H:i:00')) - 3600 - 60;

            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;

        } elseif($time == 2){
            $end_time = strtotime(date('Y-m-d H:i:00')) - 60 - 1;

            $start_time = strtotime(date('Y-m-d H:i:00')) - 24*3600 - 60;

            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;
        } else{
            $end_time = strtotime(date('Y-m-d H:i:00')) - 60 - 1;
            $start_time = strtotime( date('Y-m-d 00:00:00', strtotime('-6 day')) );
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;

            $tem_time = $this->get_report_maxtime($start_time, $end_time);
            if(strtotime($tem_time) > (strtotime(date('Y-m-d H:00:00')) - 24*3600)){
                $qos_start_time = strtotime($tem_time);
                $report_end_time = $qos_start_time - 3600;
            } else {
                $qos_start_time = strtotime(date('Y-m-d 00:00:00'));
                $report_end_time = $qos_start_time - 3600;
            }
        }



        if($time != 3){

            $sql = <<<EOD
select
* ,
(
SELECT
sum(cost)
FROM
qos_route_report
WHERE
report_time BETWEEN '$start_time' AND '$end_time' and direction = 1
) as egress_cost
from
(
SELECT
sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls,
sum(cancel_calls) as cancel_calls,
sum(busy_calls) as busy_calls,
sum(bill_time) as bill_time,
sum(cost) as ingress_cost
FROM
qos_route_report
WHERE
report_time BETWEEN '$start_time' AND '$end_time' and direction = 0
) as qt1


EOD;

        } else{
            $qos_start_time = date('Y-m-d H:i:s', $qos_start_time);
            $report_end_time = date('Y-m-d H:i:s', $report_end_time);



            $qos_start_time .=  ' ' . $tz;
            $report_end_time .=  ' ' . $tz;

            //今天的从qos取
            $sql1 = <<<EOD
select
* ,
(
SELECT
sum(cost)
FROM
qos_route_report
WHERE
report_time BETWEEN '$qos_start_time' AND '$end_time' and direction = 1
) as egress_cost
from
(
SELECT
sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls,
sum(cancel_calls) as cancel_calls,
sum(busy_calls) as busy_calls,
sum(bill_time) as bill_time,
sum(cost) as ingress_cost
FROM
qos_route_report
WHERE
report_time BETWEEN '$qos_start_time' AND '$end_time' and direction = 0
) as qt1
EOD;
            //其他从report_detail
            $sql2 = $this->ajax_text1_get_report($start_time,$report_end_time);

            //合并
            $sql = "select
            sum(not_zero_calls) as not_zero_calls,
            sum(total_calls) as total_calls,
            sum(cancel_calls) as cancel_calls,
            sum(busy_calls) as busy_calls,
            sum(bill_time) as bill_time,
            sum(ingress_cost) as ingress_cost,
            sum(egress_cost) as egress_cost from
            (
            ($sql1) union all ($sql2)
            ) as tmp3";
        }

        $rst = $this->query($sql);
        $data = array();
        $data['non_zero_calls'] = $rst[0][0]['not_zero_calls'] + 0;
        $data['acd'] = !empty($rst[0][0]['not_zero_calls']) ? number_format($rst[0][0]['bill_time'] / $rst[0][0]['not_zero_calls'] / 60, 2) : 0;
        $asr_ = intval($rst[0][0]['busy_calls']) + intval($rst[0][0]['cancel_calls']) + intval($rst[0][0]['not_zero_calls']);
        $data['asr'] = !empty($asr_) ? number_format($rst[0][0]['not_zero_calls'] / $asr_ * 100, 2) : 0;
        $tem = $rst[0][0]['ingress_cost'] - $rst[0][0]['egress_cost'];
        $data['revenue'] = $rst[0][0]['ingress_cost'];
        $data['profitability'] = !empty($rst[0][0]['ingress_cost']) ? number_format($tem / $rst[0][0]['ingress_cost'] * 100, 2) : 0;
        $data['revenue'] = number_format($data['revenue'], 2);

        return $data;
    }


    public function ajax_text1_get_report($start_time, $end_time){


        $in_field =
            " not_zero_calls,ingress_total_calls, ingress_call_cost, egress_call_cost, ingress_busy_calls, ingress_cancel_calls,
             ingress_bill_time ";

        $out_field = " sum(not_zero_calls) as not_zero_calls,
        sum(ingress_total_calls) as total_calls,
        sum(ingress_cancel_calls) as cancel_calls,
        sum(ingress_busy_calls) as busy_calls,
        sum(ingress_bill_time) as bill_time,
        sum(ingress_call_cost) as ingress_cost,
        sum(egress_call_cost) as egress_cost
         ";






        $sst_user_id = $_SESSION['sst_user_id'];
        $where = " report_time between '$start_time' and '$end_time' ";



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report2%');

        if(count($date_arr) == 1){
            //$table = 'cdr_report'.$date_arr[0];
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where ";
        } else {
            $sql = '';
            foreach($date_arr as $val){
                //$table_name = 'cdr_report'.$val;
                $table_name = CDR_TABLE . $val;
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where";
            }

            $sql = "select $out_field from ( $sql ) as tmp2 ";
        }

        //$rst = $this->query($sql);
        return $sql;
    }

    public function get_ajax_table1(){
        $tz = $this->get_sys_timezone();

        $end_time = date('Y-m-d H:00:00',time());

        $start_time = date('Y-m-d H:00:00',time() - 24*3600);

        $tem_time = $this->get_report_maxtime($start_time, $end_time);
        $tem_time = strstr($tem_time,'+',true);
        if ($tem_time)
        {
            $end_time = strtotime($tem_time);

            $start_time = $end_time - 23*3600;

            $end_time = date('Y-m-d H:59:59',strtotime($tem_time));
        }
        else
        {
            $start_time = time() - 23*3600;
        }
        $tmp_end = strtotime($end_time);
        $date_arr = range($start_time*1000,$tmp_end*1000,3600*1000);

        $start_time = date('Y-m-d H:00:00',$start_time);
        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;
        $data = array();

        $rst_in = $this->ajax_table1_get_report(0,$start_time,$end_time);
        $rst_e = $this->ajax_table1_get_report(1,$start_time,$end_time);

        $tem = array();
        $sum_in = array();
        foreach ($rst_in as $key => $item) {
            if (!$item[0]['ingress_client_id']){
                continue;
            }

            $ingress_cost_total = floatval($item[0]['ingress_call_cost']);

            $item[0]['report_time'] = strtotime($item[0]['report_time'])*1000;

            $tem[$item[0]['ingress_client_id']]['trend'][$item[0]['report_time']] = array($item[0]['report_time'],$ingress_cost_total);
            if(!isset($sum_in[$item[0]['ingress_client_id']]))
                $sum_in[$item[0]['ingress_client_id']] = 0;
            $sum_in[$item[0]['ingress_client_id']] += $ingress_cost_total;
        }
        $rst_in = $tem;

        $tem = array();
        $sum_e = array();
        foreach ($rst_e as $key => $item) {
            if (!$item[0]['egress_client_id']){
                continue;
            }

            $egress_cost_total = floatval($item[0]['egress_call_cost']);
            $item[0]['report_time'] = strtotime($item[0]['report_time'])*1000;

            $tem[$item[0]['egress_client_id']]['trend'][$item[0]['report_time']] = array($item[0]['report_time'],$egress_cost_total);
            if(!isset($sum_e[$item[0]['egress_client_id']]))
                $sum_e[$item[0]['egress_client_id']] = 0;
            $sum_e[$item[0]['egress_client_id']] += $egress_cost_total;
        }
        $rst_e = $tem;

        arsort($sum_in);
        $sum_in = array_slice($sum_in,0,20,true);
        arsort($sum_e);
        $sum_e = array_slice($sum_e,0,20,true);


//        echo "<pre>";  print_r($sum_e);
        foreach($date_arr as $date_item){
            $i = 0;
            foreach($sum_in as $sum_k => $sum_item){


                if(array_key_exists($date_item,$rst_in[$sum_k]['trend'])){
                    $data['clients'][$i][$sum_k]['trend'][] = $rst_in[$sum_k]['trend'][$date_item];
                } else {
                    $data['clients'][$i][$sum_k]['trend'][] = array($date_item,0);
                }

                $data['clients'][$i][$sum_k]['revenue'] = $sum_item;
                $i ++;
            }


            $i = 0;
            foreach($sum_e as $sum_k => $sum_item){

                if(array_key_exists($date_item,$rst_e[$sum_k]['trend'])){
                    $data['vendors'][$i][$sum_k]['trend'][] = $rst_e[$sum_k]['trend'][$date_item];
                } else {
                    $data['vendors'][$i][$sum_k]['trend'][] = array($date_item,0);
                }

                $data['vendors'][$i][$sum_k]['revenue'] = $sum_item;
                $i ++;
            }
        }
        $keys_in = array_keys($sum_in);
        $keys_e = array_keys($sum_e);
        $clients = '';
        if(!empty($keys_in))
            $clients .= "'" . implode("','",$keys_in) . "'";
        if(!empty($keys_e)){
            if(empty($clients)){
                $clients .= "'" . implode("','",$keys_e) . "'";
            } else{
                $clients .= ',' . "'" . implode("','",$keys_e) . "'";
            }
        }

        $client_arr = $this->query("select client_id,name from client where client_id in ($clients)");

        $arr = array();
        foreach($client_arr as $item){
            $id = intval($item[0]['client_id']);
            $arr[$id] = array($item[0]['name']);
        }
        $client_arr = $arr;
        $data['clients_name'] = $client_arr;
        $data['time_interval'] = $start_time . ' ~ ' . $end_time;
        return $data;
    }
    public function ajax_table1_get_report($in_or_e,$start_time,$end_time){


        if($in_or_e){
            $in_field = " egress_client_id,report_time,ingress_call_cost,egress_call_cost ";
            $out_field = " egress_client_id,report_time,sum(ingress_call_cost) as ingress_call_cost,sum(egress_call_cost) as egress_call_cost ";

            $group = " group by egress_client_id,report_time ";
            $where = " report_time between '$start_time' and '$end_time' and egress_client_id is not null ";
            $order_limit = " order by egress_call_cost desc";
        } else {
            $in_field = " ingress_client_id,report_time,ingress_call_cost,egress_call_cost ";
            $out_field = " ingress_client_id,report_time,sum(ingress_call_cost) as ingress_call_cost,sum(egress_call_cost) as egress_call_cost ";

            $group = " group by ingress_client_id,report_time ";
            $where = " report_time between '$start_time' and '$end_time' and ingress_client_id is not null ";
            $order_limit = "order by ingress_call_cost desc";
        }



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report2%');

        if(count($date_arr) == 1){
            //$table = 'cdr_report'.$date_arr[0];
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where $group $order_limit ";
        } else {
            $sql = '';
            foreach($date_arr as $val){
                //$table_name = 'cdr_report'.$val;
                $table_name = CDR_TABLE . $val;
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where";
            }

            $sql = "select $out_field from ( $sql ) as tmp2  $group $order_limit ";
        }
        $rst = $this->query($sql);
        return $rst;
    }


    //point
    public function get_network_point($time, $server)
    {
        $search_time = date('Y-m-d H:i:s' ,strtotime($time));
        $search_end_time = date('Y-m-d H:i:s' ,strtotime($time) +59);
        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }
//        $sql = "SELECT sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel FROM qos_total
//WHERE to_char(report_time,'YYYY-MM-DD HH24:MI:00') = '$time' $server_condition";
        $sql = "SELECT sum(call) as call, sum(ingress_cps) as cps, sum(channels) as channel FROM qos_total
WHERE report_time between '$search_time' and '$search_end_time' $server_condition";
        return $this->query($sql);
    }

    public function get_network_report_point($time, $server)
    {
        $search_time = date('Y-m-d H:i:s' ,strtotime($time));
        $search_end_time = date('Y-m-d H:i:s' ,strtotime($time) + 59 );
        $server_condition = $this->get_server_conditions($server);


        $sql = "SELECT sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time ,sum(case direction when 0 then cost else 0 end) as ingress_cost,sum(case direction when 1 then cost else 0 end) as egress_cost"
            . ",sum(pdd) as pdd FROM qos_route_report WHERE  report_time between '$search_time' and '$search_end_time' {$server_condition}";


        return $this->query($sql);
    }

    //trunk
    public function get_trunk_point($type, $time, $trunk, $trunk_ip, $server)
    {
        $show_time = strtotime($time) * 1000;
        $search_time = date('Y-m-d H:i:s' ,strtotime($time) );
        $search_end_time = date('Y-m-d H:i:s' ,strtotime($time) + 59 );
        $draw_data = array(
            'call' => array(

            ),
            'cps' => array(

            ),
            'channel' => array(

            ),
        );


        if ($server != NULL)
        {
            $server_condition = " AND server_ip = '{$server[0]}' AND server_port = $server[1]";
        }
        else
        {
            $server_condition = '';
        }

        if (empty($trunk_ip))
        {
            $sql_total = "SELECT sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_resource WHERE
report_time between '$search_time' and '$search_end_time' AND direction = {$type} $server_condition ";

        } else
        {
            $sql_total = "SELECT sum(call) as call, sum(cps) as cps, sum(channels) as channel FROM qos_ip WHERE
report_time between '$search_time' and '$search_end_time' AND direction = {$type} $server_condition ";

        }
        $total_results = $this->query($sql_total);


        $draw_data['call'][] = array($show_time, (float) $total_results[0][0]['call']);
        $draw_data['cps'][] = array($show_time, (float) $total_results[0][0]['cps']);
        $draw_data['channel'][] = array($show_time, (float) $total_results[0][0]['channel']);



// ORIG
        if (!empty($trunk_ip))
        {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $call_data = array();
                $cps_data = array();
                $channel_data = array();

                $sql_item = "SELECT
sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_ip
WHERE report_time between '$search_time' and '$search_end_time' AND ip_id = {$item[0]['resource_ip_id']} AND direction = {$type} $server_condition ";


                $item_results = $this->query($sql_item);
                $draw_data['call'][] = array($show_time, (float) $item_results[0][0]['call']);
                $draw_data['cps'][] = array($show_time, (float) $item_results[0][0]['cps']);
                $draw_data['channel'][] = array($show_time, (float) $item_results[0][0]['channel']);
            }

        }
        else
        {
            if (!ctype_digit($trunk))
            {
                $limit = "";
                switch ($trunk)
                {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $select_trunk = $_POST['select_trunk'];


                $res_id_arr = $select_trunk['call']['res_id'];
                foreach ($res_id_arr as $res_id)
                {
                    $call_data = array();

                    $sql_item = "SELECT
sum(call) as call
FROM qos_resource
WHERE report_time between '$search_time' and '$search_end_time' AND res_id = {$res_id} AND direction = {$type} $server_condition ";

                    $item_results = $this->query($sql_item);

                    $draw_data['call'][] = array($show_time, (float) $item_results[0][0]['call']);



                }


                $res_id_arr = $select_trunk['cps']['res_id'];


                foreach ($res_id_arr as $res_id)
                {
                    $cps_data = array();

                    $sql_item = "SELECT
sum(cps) as cps
FROM qos_resource
WHERE report_time between '$search_time' and '$search_end_time' AND res_id = {$res_id} AND direction = {$type} $server_condition ";


                    $item_results = $this->query($sql_item);
                    $draw_data['cps'][] = array($show_time, (float) $item_results[0][0]['cps']);

                }

//beign channel
                $res_id_arr = $select_trunk['channel']['res_id'];


                foreach ($res_id_arr as $res_id)
                {
                    $channel_data = array();


                    $sql_item = "SELECT
sum(channels) as channel
FROM qos_resource
WHERE report_time between '$search_time' and '$search_end_time'
AND res_id = {$res_id} AND direction = {$type} $server_condition ";


                    $item_results = $this->query($sql_item);
                    $draw_data['channel'][] = array($show_time, (float) $item_results[0][0]['channel']);
                }
// end channel
            }
            else
            {
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
                $result = $this->query($sql);

                foreach ($result as $item)
                {
                    $call_data = array();
                    $cps_data = array();
                    $channel_data = array();


                    $sql_item = "SELECT
sum(call) as call, sum(cps) as cps, sum(channels) as channel
FROM qos_resource
WHERE report_time between '$search_time' and '$search_end_time' AND res_id = {$item[0]['res_id']} AND direction = {$type} $server_condition ";

                    $item_results = $this->query($sql_item);

                    $draw_data['call'][] = array($show_time, (float) $item_results[0][0]['call']);
                    $draw_data['cps'][] = array($show_time, (float) $item_results[0][0]['cps']);
                    $draw_data['channel'][] = array($show_time, (float) $item_results[0][0]['channel']);

                }
            }
        }

        return $draw_data;
    }

    public function get_trunk_report_point($direction, $time, $server, $trunk, $show_type)
    {
        $show_time = strtotime($time) * 1000;
        $search_time = date('Y-m-d H:i:s' ,strtotime($time) );
        $search_end_time = date('Y-m-d H:i:s' ,strtotime($time) + 59 );

        $draw_data = array(
            'acd' => array(

            ),
            'abr' => array(

            ),
            'asr' => array(

            ),
            'pdd' => array(

            ),
            'revenue' => array(

            ),
            'profitability' => array(

            )

        );


        $server_condition = $this->get_server_conditions($server);
        $server_condition .= " AND direction = {$direction}";

        $sql = "SELECT sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
            . ",sum(bill_time) as bill_time  ,sum(case direction when 0 then cost else 0 end) as ingress_cost,sum(case direction when 1 then cost else 0 end) as egress_cost"
            . ",sum(pdd) as pdd  FROM qos_route_report WHERE  report_time between '$search_time' and '$search_end_time' "
            . " {$server_condition} ";

        $data = $this->query($sql);





        $bill_time = intval($data[0][0]['bill_time']);
        $not_zero_calls = intval($data[0][0]['not_zero_calls']);
        $busy_calls = intval($data[0][0]['busy_calls']);
        $total_calls = intval($data[0][0]['total_calls']);
        $cancel_calls = intval($data[0][0]['cancel_calls']);
        $ingress_client_cost_total = floatval($data[0][0]['ingress_cost']);
        $egress_cost_total = floatval($data[0][0]['egress_cost']);
        $revenue = $ingress_client_cost_total - $egress_cost_total;
        $pdd = $data[0][0]['pdd'];
        $ready_data['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
        $ready_data['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
        $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
        $ready_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
        $ready_data['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
        $ready_data['revenue'] = $ingress_client_cost_total;
        $ready_data['profitability'] = !empty($ingress_client_cost_total) ? round($revenue / $ingress_client_cost_total, 2)*100 : 0;


        $draw_data['acd'][] = array($show_time, $ready_data['acd']);
        $draw_data['abr'][] = array($show_time, $ready_data['abr']);
        $draw_data['asr'][] = array($show_time, $ready_data['asr']);
        $draw_data['pdd'][] = array($show_time, $ready_data['pdd']);
        $draw_data['revenue'][] = array($show_time, $ready_data['revenue']);
        $draw_data['profitability'][] = array($show_time, $ready_data['profitability']);


        if (!ctype_digit($trunk))
        {
            $limit = "";
            switch ($trunk)
            {
                case "top5":
                    $limit = "LIMIT 5";
                    break;
                case "top10":
                    $limit = "LIMIT 10";
                    break;
                case "top15":
                    $limit = "LIMIT 15";
                    break;
                case "top20":
                    $limit = "LIMIT 20";
                    break;
                case "all":
                    $limit = "";
                    break;
            }

            $select_trunk = $_POST['select_trunk'];
            $res_id_arr = $select_trunk['report']['res_id'];

            foreach ($res_id_arr as $res_id)
            {
                $call_data2 = array();
                $sql_item = "SELECT sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
                    . ",sum(bill_time) as bill_time,sum(pdd) as pdd  FROM qos_route_report WHERE  report_time between '$search_time' and '$search_end_time' "
                    . " AND resource_id = {$res_id} {$server_condition} ";

                $item_results = $this->query($sql_item);

                $ready_data2 = array();

                $bill_time = intval($item_results[0][0]['bill_time']);
                $not_zero_calls = intval($item_results[0][0]['not_zero_calls']);
                $busy_calls = intval($item_results[0][0]['busy_calls']);
                $total_calls = intval($item_results[0][0]['total_calls']);
                $cancel_calls = intval($item_results[0][0]['cancel_calls']);
//            $ingress_client_cost_total = $item_results_item[0]['ingress_client_cost_total'];
//            $egress_cost_total = $item_results_item[0]['egress_cost_total'];
                $pdd = $item_results[0][0]['pdd'];
                $ready_data2['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
                $ready_data2['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
                $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                $ready_data2['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
                $ready_data2['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
                $ready_data2['profitability'] = "";
                $ready_data2['revenue'] = "";


                $draw_data['acd'][] = array($show_time, $ready_data2['acd']);
                $draw_data['abr'][] = array($show_time, $ready_data2['abr']);
                $draw_data['asr'][] = array($show_time, $ready_data2['asr']);
                $draw_data['pdd'][] = array($show_time, $ready_data2['pdd']);
                $draw_data['revenue'][] = array($show_time, $ready_data2['revenue']);
                $draw_data['profitability'][] = array($show_time, $ready_data2['profitability']);
            }

        }
        else
        {
            $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
            $result = $this->query($sql);
            foreach ($result as $item)
            {
                $call_data3 = array();
                $sql_item = "SELECT sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(cancel_calls) as cancel_calls,sum(busy_calls) as busy_calls"
                    . ",sum(bill_time) as bill_time,sum(pdd) as pdd  FROM qos_route_report WHERE  report_time between '$search_time' and '$search_end_time' "
                    . " AND resource_id = {$item[0]['res_id']} {$server_condition} ";

                $item_results = $this->query($sql_item);
                $ready_data3 = array();

                $bill_time = intval($item_results[0][0]['bill_time']);
                $not_zero_calls = intval($item_results[0][0]['not_zero_calls']);
                $busy_calls = intval($item_results[0][0]['busy_calls']);
                $total_calls = intval($item_results[0][0]['total_calls']);
                $cancel_calls = intval($item_results[0][0]['cancel_calls']);
//            $ingress_client_cost_total = $item_results_item[0]['ingress_client_cost_total'];
//            $egress_cost_total = $item_results_item[0]['egress_cost_total'];
                $pdd = $item_results[0][0]['pdd'];
                $ready_data3['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
                $ready_data3['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
                $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                $ready_data3['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
                $ready_data3['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
                $ready_data3['profitability'] = "";
                $ready_data3['revenue'] = "";

                $draw_data['acd'][] = array($show_time, $ready_data3['acd']);
                $draw_data['abr'][] = array($show_time, $ready_data3['abr']);
                $draw_data['asr'][] = array($show_time, $ready_data3['asr']);
                $draw_data['pdd'][] = array($show_time, $ready_data3['pdd']);
                $draw_data['revenue'][] = array($show_time, $ready_data3['revenue']);
                $draw_data['profitability'][] = array($show_time, $ready_data3['profitability']);

            }
        }

        return $draw_data;
    }

    public function get_report_maxtime($start_time, $end_time)
    {
        //分表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report2%');
        $org_sql ='';
        foreach($date_arr as $value){
            //$table_name = "cdr_report".$value;
            $table_name = CDR_TABLE . $value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  select report_time  from   {$table_name}  where report_time between '{$start_time}' and '{$end_time}'";

        }

        $sql = "SELECT max(report_time) + interval '1 hour' as end_time FROM ( $org_sql ) as tmp";
        $result = $this->query($sql);
        return $result[0][0]['end_time'];
    }


    public function get_server_conditions($server)
    {
        $server_condition = '';
        if ($server != NULL)
        {
            $voip_gateway_info = $this->query("select id from voip_gateway where lan_ip = '{$server[0]}' and lan_port = " . intval($server[1]));
            if ($voip_gateway_info){
                $server_ip_sql_arr = array();
                $voip_gateway_id = $voip_gateway_info[0][0]['id'];
                $switches_info = $this->query("select sip_ip FROM switch_profile WHERE voip_gateway_id =" .$voip_gateway_id);
                foreach ($switches_info as $switch_info){
                    $server_ip_sql_arr[] = "server_ip = '{$switch_info[0]['sip_ip']}'";
                }
                $server_condition = " AND (" .implode(' or ', $server_ip_sql_arr) . ")";
            }
        }
        return $server_condition;
    }

}
