<?php

class Dynamicroute extends AppModel
{

    var $name = 'Dynamicroute';
    var $useTable = "dynamic_route";
    var $primaryKey = "dynamic_route_id";

    function findAllUser()
    {
        $r = $this->query("select time_profile_id ,name from time_profile  order by time_profile_id");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['time_profile_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 查询落地网关
     */
    function findAllEgress()
    {
        $r = $this->query("select alias ,resource_id from resource where egress  is true order by alias");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    function findDynamicAllEgress($dynamicroute_id)
    {
        $r = $this->query("SELECT dynamic_route_items.resource_id, resource.alias FROM dynamic_route_items 

LEFT JOIN resource on dynamic_route_items.resource_id =  resource.resource_id

WHERE dynamic_route_id = {$dynamicroute_id} ORDER by resource.alias");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    /**
     * 验证客户信息
     * @return true 有错误信息
     *     false 没有错误信息
     */
    function validate_dynamic($data, $post_arr)
    {
        $error_flag = false; //错误信息标志
        $dynamic_route_id = array_keys_value($data, 'Dynamicroute.dynamic_route_id');
//        $name = $data['Dynamicroute']['name'];
        $rule = $data['Dynamicroute']['routing_rule'];

        //判空 
//        if (empty($name))
//        {
//            die(var_dump('name'));
//            $this->create_json_array('#DynamicrouteName', 101, __('pleaseinputroutename', true));
//            $error_flag = true; //有错误信息
//        }
        if (empty($rule))
        {
//            die(var_dump('rule')); 
            $this->create_json_array('#DynamicrouteRoutingRule', 101, __('pleaseselectrouterule', true));
            $error_flag = true; //有错误信息
        }

        //对落地网关判空 
        $temp = Array();

        if (isset($post_arr['engress_res_id']))
        {
            foreach ($post_arr['engress_res_id'] as $key => $value)
            {
                if (empty($value))
                {
                    $this->create_json_array('#DynamicrouteEngressResId', 101, __('pleaseselectegress', true));
                    $error_flag = true;
                }
                if (isset($temp[$value]) && $temp[$value] != '')
                {
                    $this->create_json_array('#DynamicrouteEngressResId', 101, __('egress is repeat', true));
                    $error_flag = true;
                }
                $temp[$value] = $value;
            }
        }
        //验证重复
        $c = $this->check_name($dynamic_route_id, $name);
        if ($c != 0)
        {
            $this->create_json_array('#DynamicrouteName', 301, __('routenameexist', true));
            $error_flag = true;
        }
        return $error_flag;
    }

    /**
     * 验证策略名字不能重复
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_name($dynamic_route_id, $name)
    {
        $name = "'" . $name . "'";
        empty($dynamic_route_id) ? $sql = "select count(*) from dynamic_route where name=$name " :
                        $sql = "select count(*) from dynamic_route where name=$name  and dynamic_route_id<>$dynamic_route_id";
        $c = $this->query($sql);
        if (empty($c))
        {
            return 0;
        }
        else
        {
            return $c[0][0]['count'];
        }
    }

    function jsresource($item_id)
    {
        $sql = "select dynamic_route_items.resource_id, resource.client_id 
            from dynamic_route_items
            left join resource on
            resource.resource_id=dynamic_route_items.resource_id 
            where dynamic_route_items.dynamic_route_id  = {$item_id} order by dynamic_route_items.id asc";
        $result = $this->query($sql);
        return $result;
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     * @return 
     */
    function saveOrUpdate($data, $post_arr)
    {
        $msgs = $this->validate_dynamic($data, $post_arr); //验证客户信息
        if (!empty($msgs))
        {
            return false; //add fail
        }
        else
        {
            $dynamic_route_id = $this->saveOrUpdate_dynamic($data, $post_arr); //添加或者更新
            return $dynamic_route_id; //add succ
        }
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     */
    function saveOrUpdate_dynamic($data, $post_arr)
    {
        $dynamic_route_id = array_keys_value($data, 'Dynamicroute.dynamic_route_id');
        $lcr_flag = array_keys_value($data, 'Dynamicroute.lcr_flag');
        // $user = $_SESSION['sst_user_id'];
        $this->begin();
        if (!empty($dynamic_route_id))
        {
            //更新client
            $data ['Dynamicroute']['fcr_flag'] = $lcr_flag;
            $data['Dynamicroute']['dynamic_route_id'] = $dynamic_route_id;
            $data['Dynamicroute']['update_at'] = date("Y-m-d H:i:s");
            $data['Dynamicroute']['update_by'] = $_SESSION['sst_user_name'];
            $this->save($data ['Dynamicroute']);
        }
        else
        {
            //添加动态路由 
            $data['Dynamicroute']['update_at'] = date("Y-m-d H:i:s");
            $data['Dynamicroute']['update_by'] = $_SESSION['sst_user_name'];
            $this->save($data ['Dynamicroute']);
            $dynamic_route_id = $this->getlastinsertId();
        }
        $this->saveOrUpdate_egress($dynamic_route_id, $post_arr['engress_res_id']);
        $this->commit();
        return $dynamic_route_id;
    }

    function saveOrUpdate_egress($dynamic_route_id, $post_arr)
    {
        if ($dynamic_route_id) {
            $this->query("delete from dynamic_route_items where dynamic_route_id = {$dynamic_route_id}");
        }
        foreach ($post_arr as $val)
        {
            if ($val != '')
            {
                if($dynamic_route_id && $val) {
                    $sql = "insert into dynamic_route_items (dynamic_route_id, resource_id) values ({$dynamic_route_id},{$val})";
                }
                $this->query($sql);
            }
        }
    }

    /**
     * 查询Reseller
     */
    function findReseller()
    {
        $r = $this->query("select reseller.reseller_id ,reseller.name from reseller");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['reseller_id'];
            $l[$key] = $r[$i][0]['name'];
        }

        return $l;
    }

    function fff()
    {
        $rs = $this->query("select  resource_id_1,resource_id_2,resource_id_3,resource_id_4,
resource_id_5,resource_id_6,resource_id_7,resource_id_8  from  	 dynamic_route  where dynamic_route_id=17 ");
        $res_id = '';
        if (isset($rs[0][0]))
        {
            foreach ($rs[0][0] as $k => $v)
            {
                if (!empty($v))
                {
                    $res_id.=$v . ",";
                }
            }

            $res_id = substr($res_id, 0, -1);
            pr($res_id);
        }
    }

    /**
     * 查询指定动态路由的落地网关
     * @param unknown_type $dynamic_route_id
     */
    function findEgress($dynamic_route_id)
    {


        $rs = $this->query("select  resource_id_1,resource_id_2,resource_id_3,resource_id_4,
resource_id_5,resource_id_6,resource_id_7,resource_id_8  from  	 dynamic_route  where dynamic_route_id=$dynamic_route_id ");
        $res_id = '';
        if (isset($rs[0][0]))
        {
            foreach ($rs[0][0] as $k => $v)
            {
                if (!empty($v))
                {
                    $res_id.=$v . ",";
                }
            }
            $res_id = substr($res_id, 0, -1);
        }

        $rs = $this->query("select * from resource_ip where resource_id  in ($res_id)");
        $size = count($rs);
        for ($index = 0; $index < $size; $index++)
        {
            if (strrpos($rs[$index][0]['ip'], "/"))
            {
                list($ip, $net) = split('[/]', $rs[$index][0]['ip']);
                $rs[$index][0]['host'] = $ip;
                $rs[$index][0]['netmask'] = $net;
            }
            else
            {
                $rs[$index][0]['host'] = $rs[$index][0]['ip'];
                $rs[$index][0]['netmask'] = '';
            }

            empty($rs[$index][0]['capacity']) ? ($rs[$index][0]['capacity'] = 'Unlimited') : ($rs[$index][0]['capacity'] = $rs[$index][0]['capacity']);
            empty($rs[$index][0]['cps_limit']) ? ($rs[$index][0]['cps_limit'] = 'Unlimited') : ($rs[$index][0]['cps_limit'] = $rs[$index][0]['cps_limit']);
        }
        return $rs;
    }

    /**
     * 删除
     * @param unknown_type $id
     */
    function del($id)
    {
        $this->begin();
        $data = $this->query("delete from dynamic_route where dynamic_route_id = $id returning dynamic_route_id");
        if($id) {
            $this->query("delete from dynamic_route_items where dynamic_route_id = $id");
        }
        $this->commit();
        return $data;
    }

    public function findAll($order = null, $dynamic_route_id = false)
    {
        if (empty($order))
        {
            $order = "name asc";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        //client
        if ($login_type == 3)
        {
            $privilege = " and (client_id = {$_SESSION['sst_client_id']})";
        }
//模糊搜索
        $like_where = (!empty($_GET['search']) && strcmp('Search', $_GET['search'])) ? " and (name ilike '%{$_GET['search']}%'  or  dynamic_route_id::text ilike '%{$_GET['search']}%'  )" : '';
        $name_where = (!empty($_GET['name'])) ? " and (name  ilike '%{$_GET['name']}%')" : '';
        $routing_rule_where = (!empty($_GET['routing_rule'])) ? " and (routing_rule  = {$_GET['routing_rule']})" : '';
        //路由伙伴
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (id={$_GET ['query'] ['id_clients']})" : '';
        //按时间搜索
        $date_where = '';
        if (isset($_GET['start_date']) || isset($_GET['end_date']))
        {
            $start = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-1  00:00:00");
            $end = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d 23:59:59");
            $date_where = "  and  (create_time  between   '$start'  and  '$end')";
        }

        $dynamic_route_where = '';
        if($dynamic_route_id){
            $dynamic_route_where = " AND dyn.dynamic_route_id='{$dynamic_route_id}' ";
        }

        $resouce_where = '';
        if (isset($_GET['resource_id']))
        {
            $resouce_where = "and exists (select * from dynamic_route_items where dynamic_route_id = dyn.dynamic_route_id and resource_id = {$_GET['resource_id']})";
        }
        
        $totalrecords = $this->query("select count(dynamic_route_id) as c from dynamic_route as dyn where 1=1 AND is_virtual is not true $resouce_where
	$like_where  $routing_rule_where    $client_where $name_where     $privilege  ");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select dyn.dynamic_route_id,dyn.name,routing_rule,case lcr_flag when 1 then '15 Minutes' when 2 then '30 Minutes' when 3 then '1 Hour' when 4 then '1 Day' end as lcr_flag,update_at,update_by,
		(select name from time_profile where time_profile.time_profile_id=dyn.time_profile_id) as time_profile_id,
        (select count(*) as use_count  from(

select count(*) from route_strategy left join route on route_strategy.route_strategy_id = route.route_strategy_id

where route.dynamic_route_id = dyn.dynamic_route_id group by route_strategy.route_strategy_id) as tbl) as use_count
		from  dynamic_route  as dyn  
  		where 1=1 $dynamic_route_where AND is_virtual is not true $resouce_where
		$like_where  $routing_rule_where    $date_where  $client_where  $name_where       $privilege  ";
        $sql .= "      order by $order  	limit '$pageSize' offset '$offset'";


        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    /**
     * 模糊查询
     * @param unknown_type $condition
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function likequery($key, $currPage = 1, $pageSize = 10)
    {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(dynamic_route_id) as c
	 	from dynamic_route  as b  

	 	where name   like $condition 
	 	or (select count(*)>0 from users   where user_id=b.create_user 	 	and name like $condition )
	 	
	 	or(select  count(*)>0 from reseller where reseller_id =b.reseller_id and name like $condition)
	 	
	 		 	or(select count(*)>0 from resource where alias like $condition 
	 	and egress is true and resource_id in(select   distinct engress_res_id  
	 	from resource_dynamic_route_ref   as ref where ref.dynamic_route_id=b.dynamic_route_id))
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select dyn.dynamic_route_id,dyn.name , a.engress_cnt ,routing_rule,u.user_name,r_name
		    from  dynamic_route  as dyn
left join (select count(*)as  engress_cnt,dynamic_route_id from resource_dynamic_route_ref    group by  dynamic_route_id) a   
on  a.dynamic_route_id=dyn.dynamic_route_id

left join(select name  as user_name ,user_id  from users) u on u.user_id =dyn.create_user 

  left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=dyn.reseller_id
  
  	 	where dyn.name   like $condition 
	 	or (select count(*)>0 from users   where user_id=dyn.create_user 	 	and name like $condition )
	 	
	 	or(select  count(*)>0 from reseller where reseller_id =dyn.reseller_id and name like $condition)
	 	
	 	or(select count(*)>0 from resource where alias like $condition 
	 	and egress is true and resource_id in(select   distinct engress_res_id  
	 	from resource_dynamic_route_ref   as ref where ref.dynamic_route_id=dyn.dynamic_route_id))
  
  
  
	order by dyn.dynamic_route_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 高级搜索
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function Advancedquery($data, $currPage = 1, $pageSize = 10)
    {

        //解析搜索条件
        $condition = "where   ";
        $i = 0;
        $len = intval(count($data['Dynamicroute']));

        $engress_res_id = '';
        //组装where条件
        foreach ($data['Dynamicroute'] as $key => $value)
        {
            //没有搜索条件就退出
            if ($value == '')
            {
                continue;
            }
            //提取落地网关ID
            if ($key == 'engress_res_id')
            {
                $engress_res_id = $data['Dynamicroute']['engress_res_id'];
                continue;
            }
            else
            {
                $tmp = "dyn." . $key . "='" . $value . "'  and   ";
            }

            $condition = $condition . $tmp;
            $i++;
        }

        $where = substr($condition, 0, strrpos($condition, 'a')); //去掉最后1个and,如果没有找到and strrpos返回0
        //不考虑落地网关的搜索
        if (empty($engress_res_id))
        {
            $c_sql = "select count(dynamic_route_id) as c from dynamic_route as dyn	$where";
            $sql = "select dyn.dynamic_route_id,dyn.name , a.engress_cnt ,routing_rule,u.user_name,r_name
		           from  dynamic_route  as dyn
              left join (select count(*)as  engress_cnt,dynamic_route_id from resource_dynamic_route_ref    group by  dynamic_route_id) a   
              on  a.dynamic_route_id=dyn.dynamic_route_id

              left join(select name  as user_name ,user_id  from users) u on u.user_id =dyn.create_user 

              left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=dyn.reseller_id
               $where";
        }
        else
        {
            //通过落地网关搜索
            if (empty($where))
            {
                $c_sql = "select count(dynamic_route_id) as c from dynamic_route as dyn
				where 	(select  count(*)>0 from resource_dynamic_route_ref  as res_dyn
	 	 where res_dyn.dynamic_route_id =dyn.dynamic_route_id and engress_res_id = $engress_res_id)	 ";

                $sql = "select dyn.dynamic_route_id,dyn.name , a.engress_cnt ,routing_rule,u.user_name,r_name
		           from  dynamic_route  as dyn
              left join (select count(*)as  engress_cnt,dynamic_route_id from resource_dynamic_route_ref    group by  dynamic_route_id) a   
              on  a.dynamic_route_id=dyn.dynamic_route_id

              left join(select name  as user_name ,user_id  from users) u on u.user_id =dyn.create_user 

              left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=dyn.reseller_id
              	where 	(select  count(*)>0 from resource_dynamic_route_ref  as res_dyn
	 	 where res_dyn.dynamic_route_id =dyn.dynamic_route_id and engress_res_id = $engress_res_id)	 ";
            }
            else
            {
                //所有字段搜索


                $c_sql = "select count(dynamic_route_id) as c from dynamic_route as dyn
				 $where  or 	(select  count(*)>0 from resource_dynamic_route_ref  as res_dyn
	 	 where res_dyn.dynamic_route_id =dyn.dynamic_route_id and engress_res_id = $engress_res_id)	 ";

                $sql = "select dyn.dynamic_route_id,dyn.name , a.engress_cnt ,routing_rule,u.user_name,r_name
		           from  dynamic_route  as dyn
              left join (select count(*)as  engress_cnt,dynamic_route_id from resource_dynamic_route_ref    group by  dynamic_route_id) a   
              on  a.dynamic_route_id=dyn.dynamic_route_id

              left join(select name  as user_name ,user_id  from users) u on u.user_id =dyn.create_user 

              left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=dyn.reseller_id
            			 $where  or 	(select  count(*)>0 from resource_dynamic_route_ref  as res_dyn
	 	 where res_dyn.dynamic_route_id =dyn.dynamic_route_id and engress_res_id = $engress_res_id)	 ";
            }
        }


        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($c_sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $results = $this->query($sql . "order by dyn.dynamic_route_id  	limit '$pageSize' offset '$offset'");

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询网关组
     */
    function findResByres_id($res_id)
    {
        return $this->query("select * from resource where resource_id=$res_id");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询号码转换
     */
    function findresdirectByRes_id($res_id)
    {
        return $this->query("select * from resource_direction  where resource_id=$res_id");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查找落地网关
     */
    function findEgressbydynamic_id($res_id)
    {

        $list = $this->query("select resource_id from dynamic_route_items  where dynamic_route_id={$res_id}");

        $arr = array();

        foreach ($list as $val)
        {
            array_push($arr, $val[0]['resource_id']);
        }
        return $arr;
    }

    public function insert_override($dynamic_id, $digits, $percentage, $egress_trunk)
    {
        $sql = "INSERT INTO dynamic_route_override(dynamic_route_id, digits, resource_id, percentage)
             VALUES ({$dynamic_id}, '{$digits}', {$egress_trunk}, {$percentage})";
        return $this->query($sql);
    }

    public function get_overrides($dynamic_id, $search, $where, $pageSize, $offset)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        $sql = "SELECT * FROM dynamic_route_override WHERE dynamic_route_id = {$dynamic_id} {$search} {$where} LIMIT {$pageSize} OFFSET {$offset}";
//echo $sql;
        return $this->query($sql);
    }

    public function get_overrides_count($dynamic_id, $search, $where)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        $sql = "SELECT count(*) FROM dynamic_route_override WHERE dynamic_route_id = {$dynamic_id} {$search} {$where}";
        $result = $this->query($sql);
        if (empty($result))
        {
            return 0;
        }
        else
        {
            return $result[0][0]['count'];
        }
    }

    public function check_override_total($dynamic_id, $override_id = '')
    {
        if (!empty($override_id))
        {
            $override_id = " AND override_id != {$override_id}";
        }
        $sql = "SELECT COALESCE(sum(percentage), 0) as total FROM dynamic_route_override WHERE dynamic_route_id = {$dynamic_id} {$override_id}";
        $result = $this->query($sql);
        return $result[0][0]['total'];
    }

    public function insert_pri($dynamic_id, $digits, $resource_pri, $egress_trunk)
    {
        $sql = "INSERT INTO dynamic_route_pri(dynamic_route_id, digits, resource_id, resource_pri)
             VALUES ({$dynamic_id}, '{$digits}', {$egress_trunk}, {$resource_pri})";
        return $this->query($sql);
    }

    public function update_pri($pri_id, $digits, $resource_pri, $egress_trunk)
    {
        $sql = "UPDATE dynamic_route_pri SET digits = '{$digits}' , resource_id = '{$egress_trunk}',resource_pri = $resource_pri WHERE id = {$pri_id}";
        $this->query($sql);
    }

    public function check_pri_count($dynamic_id, $digits, $egress_trunk, $id = '')
    {
        $sql = "SELECT count(*) FROM dynamic_route_pri WHERE dynamic_route_id = {$dynamic_id} AND digits='{$digits}' AND resource_id = {$egress_trunk}";
        if (!empty($id))
            $sql .= "AND id != {$id}";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    public function insert_qos($dynamic_id, $digits, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price)
    {
        $sql = "INSERT INTO dynamic_route_qos(dynamic_route_id, digits,min_asr, max_asr, min_abr, max_abr, min_acd, max_acd, min_pdd, max_pdd, max_aloc, min_aloc, limit_price)
             VALUES ($dynamic_id, '$digits',$min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price)";
        return $this->query($sql);
    }

    public function update_qos($qos_id, $prefix, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price)
    {
        $sql = "UPDATE dynamic_route_qos SET digits = '$prefix', min_asr = $min_asr, max_asr = $max_asr, min_abr = $min_abr, max_abr = $max_abr, 
            min_acd = $min_acd, max_acd = $max_acd, min_pdd = $min_pdd, max_pdd = $max_pdd, max_aloc = $max_aloc, min_aloc = $min_aloc, limit_price = $limit_price WHERE id = $qos_id";
        return $this->query($sql);
    }

    public function check_digit_count($dynamic_id, $digits, $qos_id = '')
    {
        $sql = "SELECT count(*) FROM dynamic_route_qos WHERE digits = '{$digits}' AND dynamic_route_id = {$dynamic_id}";
        if (!empty($qos_id))
            $sql .= " AND id != {$qos_id}";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    public function get_pris($dynamic_id, $search, $where, $pageSize, $offset)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        $sql = "SELECT * FROM dynamic_route_pri WHERE dynamic_route_id = {$dynamic_id} {$search} {$where} LIMIT {$pageSize} OFFSET {$offset}";
        return $this->query($sql);
    }

    public function get_qoss($dynamic_id, $search, $where, $pageSize, $offset)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        if (!empty($where))
        {
            $where = "AND {$where}";
        }
        $sql = "SELECT * FROM dynamic_route_qos WHERE dynamic_route_id = {$dynamic_id} $search $where LIMIT {$pageSize} OFFSET {$offset}";
        return $this->query($sql);
    }

    public function get_qoss_count($dynamic_id, $search, $where)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        if (!empty($where))
        {
            $where = "AND {$where}";
        }
        $sql = "SELECT count(*) FROM dynamic_route_qos WHERE dynamic_route_id = {$dynamic_id} {$search} {$where}";
        $result = $this->query($sql);
        if (empty($result))
        {
            return 0;
        }
        else
        {
            return $result[0][0]['count'];
        }
    }

    public function check_override_count($dynamic_id, $digits, $egress_trunk, $id = '')
    {
        $sql = "SELECT count(*) FROM dynamic_route_override WHERE dynamic_route_id = {$dynamic_id} AND digits = '{$digits}' AND resource_id = {$egress_trunk}";
        if (!empty($id))
            $sql .= " AND id != {$id}";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    public function get_pris_count($dynamic_id, $search, $where)
    {
        if (!empty($search))
        {
            $search = "AND digits::text ilike '{$search}%'";
        }
        $sql = "SELECT count(*) FROM dynamic_route_pri WHERE dynamic_route_id = {$dynamic_id} {$search} {$where}";
        $result = $this->query($sql);
        if (empty($result))
        {
            return 0;
        }
        else
        {
            return $result[0][0]['count'];
        }
    }

    public function delete_override($override_id)
    {
        $sql = "DELETE FROM dynamic_route_override WHERE id = {$override_id}";
        $this->query($sql);
    }

    public function delete_priority($priority_id)
    {
        $sql = "DELETE FROM dynamic_route_pri WHERE id = {$priority_id}";
        $this->query($sql);
    }

    public function delete_mul_priority($ids)
    {
        $sql = "DELETE FROM dynamic_route_pri WHERE id in ({$ids})";
        echo $sql;
        $this->query($sql);
    }

    public function delete_pri($pri_id)
    {
        $sql = "DELETE FROM dynamic_route_pri WHERE id = {$override_id}";
        $this->query($sql);
    }

    public function delete_qos($qos_id)
    {
        $sql = "DELETE FROM dynamic_route_qos WHERE id = {$qos_id}";
        $this->query($sql);
    }

    public function update_override($override_id, $prefix, $egress_trunk, $percentage)
    {
        $sql = "UPDATE dynamic_route_override SET digits = '{$prefix}', resource_id = {$egress_trunk}, percentage = {$percentage} WHERE id = {$override_id}";
        $this->query($sql);
    }

    public function findIdByName($name)
    {
        $name = "'" . $name . "'";
        $sql = "select dynamic_route_id from dynamic_route where name=$name ";
        $c = $this->query($sql);
        if (empty($c))
        {
            return 0;
        }
        else
        {
            return $c[0][0]['dynamic_route_id'];
        }
    }

    public function get_dynamicrouteBytrunk($id)
    {
        $login_type = $_SESSION['login_type'];
        $privilege = "";
        if ($login_type == 3)
        {
            $privilege = " and (client_id = {$_SESSION['sst_client_id']})";
        }
//模糊搜索
        $like_where = (!empty($_GET['search']) && strcmp('Search', $_GET['search'])) ? " and (dyn.name ilike '%{$_GET['search']}%'  or  dyn.dynamic_route_id::text ilike '%{$_GET['search']}%'  )" : '';
        $name_where = (!empty($_GET['name'])) ? " and (dyn.name  ilike '%{$_GET['name']}%')" : '';
        $routing_rule_where = (!empty($_GET['routing_rule'])) ? " and (routing_rule  = {$_GET['routing_rule']})" : '';



        $sql = "select dyn.dynamic_route_id,dyn.name,routing_rule,
case lcr_flag when 1 then '15 Minutes' when 2 then '30 Minutes' when 3 then '1 Hour' when 4 then '1 Day' end as lcr_flag,

(select name from time_profile where time_profile.time_profile_id=dyn.time_profile_id) as time_profile_id
 from dynamic_route as dyn inner join dynamic_route_items as b on dyn.dynamic_route_id = b.dynamic_route_id where 1=1 and resource_id = {$id}"
                . " {$privilege} {$like_where} {$name_where} {$routing_rule_where} order by name asc ";
        $dynamic_data = $this->query($sql, false);
        return $dynamic_data;
    }

    public function delete_item($item_id)
    {
        $item_id = intval($item_id);
        $sql = "DELETE FROM dynamic_route_items WHERE id = $item_id";
        return $this->query($sql);
    }

}
