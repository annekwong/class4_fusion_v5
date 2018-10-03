<?php

class Gatewaygroup extends AppModel {

    var $name = 'Gatewaygroup';
    var $useTable = "resource";
    var $primaryKey = "resource_id";

    function add_resource_rate($resource_id) {
        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);
        App::import("Model", 'Resourcerate');
        $resource_model = new Resourcerate;
        foreach ($tmp as $el) {
            $data['Resourcerate'] = $el;
            $data['Resourcerate']['resource_id'] = $resource_id;
            $resource_model->save($data ['Resourcerate']);
            $resource_model->id = false;
        }
        if (!empty($delete_rate_id)) {
            $this->query("delete  from  resource_rate_table_ref where id in($delete_rate_id)");
        }
        $this->create_json_array('#ClientOrigRateTableId', 201, 'Action Success');
    }

    public function egress_report_all() {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(client_id::integer={$_SESSION['sst_client_id']}) ";
        }
        //模糊搜索
        $like_where = !empty($_GET['search']) ? " and (alias ilike '%{$_GET['search']}%'  or  name ilike '%{$_GET['search']}%'  or
	  resource.resource_id::text ilike '%{$_GET['search']}%')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (resource.client_id::integer={$_GET ['query'] ['id_clients']})" : '';
        $name_where = !empty($_GET['name']) ? " and (name='{$_GET['name']}')" : '';
        $alias_where = !empty($_GET['id']) ? " and (alias='{$_GET['id']}')" : '';
        $totalrecords = $this->query("select count(resource_id) as c from resource where egress=true 
   $like_where    $name_where  $alias_where   $client_where      $privilege");
        $order = !empty($_GET['order']) ? "order by {$_GET['order']}" : "";
        $order.=!empty($_GET['order']) ? "   {$_GET['sc']}" : "";
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
//        $sql = "select resource.alias,resource.resource_id,resource.name,cps_limit,capacity, a.ip_cnt,real.cdr_cnt from resource left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id left join (select count(*)as cdr_cnt,egress_id from real_cdr group by egress_id) real on real.egress_id=resource.resource_id::varchar where egress=true
//		$like_where $name_where  $alias_where $client_where $privilege $order";
//        $sql .= "  	limit '$pageSize' offset '$offset'";
//        $results = $this->query($sql);
        $results = array();
        $page->setDataArray($results);
        return $page;
    }
    
    public function get_egress_resources() {
        $sql = "SELECT alias FROM resource WHERE egress = true ORDER BY alias ASC";
        return $this->query($sql);
    }
    
    public function get_ingress_resources() {
        $sql = "SELECT alias FROM resource WHERE ingress = true ORDER BY alias ASC";
        return $this->query($sql);
    }
    
    public function get_clients() {
        $sql = "SELECT client_id,name FROM client ORDER BY name";
        return $this->query($sql);
    }

    public function getAddInfo($reseller_id) {
        $where = '';
        if (!empty($reseller_id)) {
            $where = " where reseller_id = {$reseller_id}";
        }
        $res_sql = "select reseller_id,name from reseller $where";
        $codec_sql = "select card_id,card_number from card ";
        return array($this->query($codec_sql));
    }

    /**
     * 验证信息
     */
    function validate_res($data, $post_arr) {
        $error_flag = 'false'; //错误信息标志
        $res_id = $this->getkeyByPOST('resource_id', $post_arr);
        $client_id = $data['Gatewaygroup']['client_id'];
        if (isset($data['Gatewaygroup']['translation_id'])) {
            $translation_id = $data['Gatewaygroup']['translation_id'];
        }
        $alias = $data['Gatewaygroup']['alias'];
        $capacity = $data['Gatewaygroup']['capacity'];
        $cps_limit = $data['Gatewaygroup']['cps_limit'];
        if (empty($client_id)) {
            $this->create_json_array('#GatewaygroupClientId', 101, __('gate_client', true));
            $error_flag = 'true'; //有错误信息
        }
        //判空 
        if (!empty($cps_limit)) {
            if (!ereg("^[0-9]+$", $cps_limit)) {
                $this->create_json_array('#totalCPS', 101, 'Must be allowed to address a number of figures');
                $error_flag = 'true'; //有错误信息
            }
            IF ($cps_limit < 0) {
                $this->create_json_array('#totalCPS', 101, 'Can not be allowed to address a number of negative');
                $error_flag = 'true'; //有错误信息
            }
        }
        //判空 
        if (!empty($capacity)) {
            if (!ereg("^[0-9]+$", $capacity)) {
                $this->create_json_array('#totalCall', 101, 'Allow the number of calls must be a number');
                $error_flag = 'true'; //有错误信息
            }
            IF ($capacity < 0) {
                $this->create_json_array('#totalCall', 101, 'Allow the number of calls can not be negative');
                $error_flag = 'true'; //有错误信息
            }
        }
        if (empty($alias)) {
            $this->create_json_array('#alias', 301, __('gate_red_id', true));
            $error_flag = 'true'; //有错误信息
        }
        if (!empty($alias)) {
            $c = $this->check_alias($res_id, $alias);
            if ($c != 0) {
                $this->create_json_array('#alias', 301, __('gate_red_id2', true));
                $error_flag = 'true'; //有错误信息
            }
        }
        return $error_flag;
    }

    /**
     * 验证alias
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_alias($res_id, $a) {
        $alias = "'" . $a . "'";
        empty($res_id) ? $sql = "select count(*) from resource where alias=$alias " :
                        $sql = "select count(*) from resource where alias=$alias  and resource_id<>$res_id";
        $c = $this->query($sql);
        if (empty($c)) {
            return 0;
        } else {
            return $c[0][0]['count'];
        }
    }

    /*
     * 验证网关名字
     */

    function check_res_name($res_id, $a) {
        $name = "'" . $a . "'";
        empty($res_id) ? $sql = "select count(*) from resource where name=$name " :
                        $sql = "select count(*) from resource where name=$name  and resource_id<>$res_id";
        $c = $this->query($sql);
        if (empty($c)) {
            return 0;
        } else {
            return $c[0][0]['count'];
        }
    }

    /**
     * 添加和更新网关组   
     * 
     */
    function saveOrUpdate($data, $post_arr, $account=Array()) {
        $msgs = $this->validate_res($data, $post_arr); //错误信息
        if ($msgs == 'true') {
            return 'fail'; //add fail
        }
        $res_id = $this->saveOrUpdate_resource($data, $post_arr); //添加或者更新
        $this->saveHost($account, $res_id);
        //echo $post_arr;
        /* pr($data);
          pr("-------_________________---------");
          pr($post_arr); */
        $this->saveResouce($post_arr['resource'], $res_id);
        $this->add_res_codecs($res_id, $data['Gatewaygroup']['select2']);
        return $res_id;
    }

    function saveOrUpdate_pro($res_id, $post_arr) {
        if (empty($post_arr ['proId'][0])) {
            //	return;
        }
        if (isset($post_arr['ref_id'])) {
            $ref_id_arr = $post_arr['ref_id'];
            $update_size = intval(count($ref_id_arr)); //更新记录	   
        } else {
            $update_size = 0;
        }
        $proId_arr = $post_arr ['proId'];
        $prefix_arr = $post_arr ['prefix'];
        $all_size = intval(count($proId_arr));
        $add_size = $all_size - $update_size; //添加记录
        //批量更新
        for ($i = 0; $i < $update_size; $i++) {
            empty($prefix_arr[$i]) ? $d = 'NULL' : $d = "'" . $prefix_arr[$i] . "'" . "::prefix_range";
            $this->query("update  resource_product_ref  set  product_id=$proId_arr[$i],digit=$d  
			  where ref_id=$res_id");
        }
        //批量添加
        for ($i = $update_size; $i < $all_size; $i++) {
            //empty($prefix_arr[$i])? $d='NULL' :  $d="'".$prefix_arr[$i]."'"."::prefix_range";
            if (empty($prefix_arr[$i])) {
                $sql = "insert into resource_product_ref (resource_id,product_id) 
					  values($res_id,$proId_arr[$i])";
            } else {
                $d = "'" . $prefix_arr[$i] . "'" . "::prefix_range";
                $sql = "insert into resource_product_ref (resource_id,product_id,digit) 
						  values($res_id,$proId_arr[$i],$d)";
            }
            $this->query($sql);
        }
        //抓去界面上删除的host
        if (!empty($post_arr['delProduct'])) {
            $del_arr = substr($post_arr['delProduct'], 1);
            $this->query("delete from resource_product_ref  where  ref_id in($del_arr )");
        }
    }

//添加号码转换
    function saveOrUpdate_direction($res_id, $post_arr) {
        if (isset($post_arr['direction_id'])) {
            $direction_id_arr = $post_arr['direction_id'];
            $update_size = intval(count($direction_id_arr)); //更新记录 
        } else {
            $update_size = 0; //更新记录为0  ，添加操作 
        }
        $direct_arr = $post_arr ['direct'];
        $match_arr = $post_arr ['match'];
        $action_arr = $post_arr ['action'];
        $digit_arr = $post_arr ['digit'];
        $all_size = intval(count($direct_arr));
        $add_size = $all_size - $update_size; //添加记录
        //批量更新
        for ($i = 0; $i < $update_size; $i++) {
            $match_arr[$i] = trim($match_arr[$i]);
            $digit_arr[$i] = trim($digit_arr[$i]);
            if (empty($match_arr[$i]) && empty($digit_arr[$i])) {
                continue;
            }
            empty($match_arr[$i]) ? $dnis = 'NULL' : $dnis = "'" . $match_arr[$i] . "'" . "::prefix_range";
            empty($digit_arr[$i]) ? $d = 'NULL' : $d = "'" . $digit_arr[$i] . "'";
            $this->query("update  resource_direction set time_profile_id=$direct_arr[$i],action=$action_arr[$i],digits=$d,dnis=$dnis  
					  where direction_id =$direction_id_arr[$i]");
        }
        //批量添加
        for ($i = $update_size; $i < $all_size; $i++) {
            empty($match_arr[$i]) ? $dnis = 'NULL' : $dnis = "'" . $match_arr[$i] . "'" . "::prefix_range";
            empty($digit_arr[$i]) ? $d = 'NULL' : $d = "'" . $digit_arr[$i] . "'";

            if ($direct_arr[$i] == '') {
                $this->query("insert into resource_direction (resource_id,action,digits,dnis) 
					  values($res_id,$action_arr[$i],$d,$dnis)");
            } else {
                $this->query("insert into resource_direction (resource_id,time_profile_id,action,digits,dnis) 
					  values($res_id,$direct_arr[$i],$action_arr[$i],$d,$dnis)");
            }
        }
        //抓去界面上删除的host
        if (!empty($post_arr['deldirectionid'])) {
            $del_arr = substr($post_arr['deldirectionid'], 1);

            $this->query("delete from resource_direction  where  direction_id in($del_arr )");
        }
    }

    /*
     * 添加和更新res_ip
     */

    function saveOrUpdate_res_ip($res_id, $post_arr) {
        if (isset($post_arr['ip_id'])) {
            $ip_id_arr = $post_arr ['ip_id'];
            $update_size = intval(count($ip_id_arr)); //更新记录 
        } else {
            $update_size = 0; //更新记录为0  ，添加操作 
        }

        $ip_arr = $post_arr ['ip'];
        $netmask_arr = $post_arr ['netmask'];
        $port_arr = $post_arr ['port'];
        $capa_arr = $post_arr ['capa'];
        $cps_arr = $post_arr ['cps'];
        $username_arr = $post_arr['username'];
        $need_register_arr = $post_arr['need_register'];
        $pass_arr = $post_arr['pass'];
        $time_profile_id_arr = $post_arr ['time_profile_id'];

        $all_size = intval(count($ip_arr));
        $add_size = $all_size - $update_size; //添加记录
        //批量更新 res_ip
        for ($i = 0; $i < $update_size; $i++) {
            $ip = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'" . "::ip4r";
            empty($capa_arr[$i]) ? $ca = 'NULL' : $ca = $capa_arr[$i];
            empty($cps_arr[$i]) ? $cps = 'NULL' : $cps = $cps_arr[$i];
            empty($port_arr[$i]) ? $port = 'NULL' : $port = $port_arr[$i];
            empty($time_profile_id_arr[$i]) ? $time_profile_id_arr[$i] = 'null' : null;
            $register = $need_register_arr[$i];
            if ($register == 'true') {
                $this->query("update  resource_ip  set fqdn=$ip,port=$port,capacity=$ca,cps_limit=$cps,username=$username_arr[$i],
				 	password=$pass_arr[$i],need_register=true,
					time_profile_id=$time_profile_id_arr[$i] where resource_ip_id=$ip_id_arr[$i]");
            } else {
                $this->query("update  resource_ip  set ip=$ip,port=$port,capacity=$ca,cps_limit=$cps,need_register=false,
					time_profile_id=$time_profile_id_arr[$i] where resource_ip_id=$ip_id_arr[$i]");
            }
        }
        //批量添加res_ip
        for ($i = $update_size; $i < $all_size; $i++) {
            $ip = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'" . "::ip4r";
            $ip1 = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'";
            empty($capa_arr[$i]) ? $ca = 'NULL' : $ca = $capa_arr[$i];
            empty($cps_arr[$i]) ? $cps = 'NULL' : $cps = $cps_arr[$i];
            empty($port_arr[$i]) ? $port = 'NULL' : $port = $port_arr[$i];
            empty($time_profile_id_arr[$i]) ? $time_profile_id_arr[$i] = 'null' : null;
            $register = $need_register_arr[$i];
            if ($register == 'true') {
                $this->query("insert into resource_ip (resource_id,fqdn,port,capacity,cps_limit,time_profile_id,username,password,need_register)  
					  values($res_id,$ip1,$port,$ca,$cps,$time_profile_id_arr[$i],'$username_arr[$i]','$pass_arr[$i]',true)");
            } else {
                $this->query("insert into resource_ip (resource_id,ip,port,capacity,cps_limit,time_profile_id,need_register)  
					  values($res_id,$ip,$port,$ca,$cps,$time_profile_id_arr[$i],false)");
            }
        }



        //抓去界面上删除的host
        if (!empty($post_arr['delHost'])) {
            $del_arr = substr($post_arr['delHost'], 1);

            $this->query("delete from resource_ip  where  resource_ip_id in($del_arr )");
        }
    }

    /**
     * 添加网关组
     */
    function saveOrUpdate_resource($data, $post_arr) {
        $client_id = $data['Gatewaygroup']['client_id'];
        $list = $this->query("select orig_rate_table_id,profit_margin,enough_balance from  client  where  client_id=$client_id");
        $rate_table_id = $list[0][0]['orig_rate_table_id'];
        $data['Gatewaygroup']['profit_margin'] = $list[0][0]['profit_margin'];
        if ($data['Gatewaygroup']['egress'] == 'true') {
            
        } else {
            $data['Gatewaygroup']['enough_balance'] = $list[0][0]['enough_balance'];
        }
        $res_id = $this->getkeyByPOST('resource_id', $post_arr);
        if (!empty($res_id)) {
            $data['Gatewaygroup']['resource_id'] = $res_id;
            $this->save($data ['Gatewaygroup']);
        } else {
            $this->save($data ['Gatewaygroup']);
            $res_id = $this->getlastinsertId();
            if ($data['Gatewaygroup']['ingress'] == 'true') {
                $this->query("insert into  route_strategy(name)values('{$data['Gatewaygroup']['alias']}.route.{$res_id}');");
                $list = $this->query("select route_strategy_id from  route_strategy  order by route_strategy_id desc  limit  1;");
                $route_strategy_id = $list[0][0]['route_strategy_id'];
                $this->query("update  resource  set  route_strategy_id=$route_strategy_id  where  resource_id=$res_id ;");
                $list = $this->query("select time_profile_id from  time_profile  where type=0   limit  1");
                if (empty($list[0][0]['time_profile_id'])) {
                    $this->query("insert into  time_profile(name,type)values('{$data['Gatewaygroup']['alias']}.profile.{$res_id}',0)");
                    $list = $this->query("select time_profile_id from  time_profile  order by time_profile_id desc  limit  1");
                    $time_profile_id = $list[0][0]['time_profile_id'];
                } else {
                    $time_profile_id = $list[0][0]['time_profile_id'];
                }
                $this->query("insert into  product(name)values('{$data['Gatewaygroup']['alias']}.static.{$res_id}')");
                $list = $this->query("select product_id from  product  order by product_id desc  limit  1");
                $product_id = $list[0][0]['product_id']; 
                $this->query("insert into  route(route_type,static_route_id,route_strategy_id) values(2,$product_id,$route_strategy_id)");
            }
        }
        return $res_id;
    }

    /*
     * 添加res_codes
     */

    function add_res_codecs($res_id, $codecs) {
        $this->query("delete  from  resource_codecs_ref  where  resource_id=$res_id");
        if (empty($res_id) || empty($codecs)) {
            return;
        }
        $size = intval(count($codecs));
        foreach ($codecs as $key => $value) {
            $this->query("insert into resource_codecs_ref (resource_id,codec_id)values($res_id,$value)");
        }
    }

    /**
     * 
     */
    function findAllRate() {
        $r = $this->query("select rate_table_id,name from rate_table");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['rate_table_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 
     */
    function getAllRate() {
        $r = $this->query("select rate_table_id,name from rate_table");
        return $r;
    }

    /**
     *  查询静态路由表
     */
    function findAllProduct() {
        $r = $this->query("select product_id,name from product");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['product_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 查询所有codecs
     */
    function findcodecs() {
        $r = $this->query("select id ,name   from codecs");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 查询号码转换
     */
    function findDigitMapping() {
        $r = $this->query("select  translation_id,translation_name from  digit_translation");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['translation_id'];
            $l[$key] = $r[$i][0]['translation_name'];
        }
        return $l;
    }

    /**
     * 查询客户
     */
    /*
    function findClient() {
        $r = $this->query("select client.client_id ,client.name from client");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['client_id'];
            $l[$key] = $r[$i][0]['name'];
        }

        return $l;
    }
    */
    /**
     * 查询客户
     */
    function getClient() {
        $r = $this->query("select client.client_id ,client.name from client");
        return $r;
    }

    /**
     * 删除
     * @param unknown_type $id
     */
    function del($id) {
        $qs_count = 0;
        /* 	$qs = $this->query("delete  from item_resource_ref where resource_id in($id)");
          $qs_count += count($qs); */

        $qs = $this->query("delete from resource_ip where resource_id in($id)");
        $qs_count += count($qs);
        $qs = $this->query("delete from resource_direction where resource_id in( $id)");
        $qs_count += count($qs);

        $qs = $this->query("delete from resource_block where ingress_res_id in ($id) or engress_res_id in ($id) ");
        $qs_count += count($qs);

        /* 	$qs = $this->query("delete from code_part where egress_id in ( $id)");
          $qs_count += count($qs); */

        $qs = $this->query("delete from resource_codecs_ref where resource_id in( $id)");
        $qs_count += count($qs);

//        $qs = $this->query("delete from test_device where resource_id in( $id)");
//        $qs_count += count($qs);

        $qs = $this->query("delete from resource where resource_id in($id)");
        $qs_count += count($qs);

        return $qs_count == 0;
    }

    /**
     * 禁用一个resource
     * @param unknown_type $id
     */
    function dis_able($id) {
        $sql = "SELECT alias FROM resource WHERE resource_id = {$id}";
        $data = $this->query($sql);
        $rollback_msg = "Modify Trunk [" . $data[0][0]['alias'] . "] operation have been rolled back!";
        $rollback_sql = "update resource set active= true where resource_id=$id";
        $this->logging(4, 'Trunk', "Trunk Name:{$data[0][0]['alias']}",$rollback_sql,$rollback_msg);
        return $this->query("update resource set active= false where resource_id=$id");
    }

    function active($id) {
        $sql = "SELECT alias FROM resource WHERE resource_id = {$id}";
        $data = $this->query($sql);
        $rollback_msg = "Modify Trunk [" . $data[0][0]['alias'] . "] operation have been rolled back!";
        $rollback_sql = "update resource set active= false where resource_id=$id";
        $this->logging(3, 'Trunk', "Trunk Name:{$data[0][0]['alias']}",$rollback_sql,$rollback_msg);
        return $this->query("update resource set active= true  where resource_id=$id");
    }

    /**
     * 查询res_IP地址
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function findAllres_ip($res_id) {
        $rs = $this->query("select * from resource_ip where resource_id=$res_id  order by resource_ip_id ");
        $size = count($rs);
        for ($index = 0; $index < $size; $index++) {
            empty($rs[$index][0]['capacity']) ? ($rs[$index][0]['capacity'] = '') : ($rs[$index][0]['capacity'] = $rs[$index][0]['capacity']);
            empty($rs[$index][0]['cps_limit']) ? ($rs[$index][0]['cps_limit'] = '') : ($rs[$index][0]['cps_limit'] = $rs[$index][0]['cps_limit']);
        }
        return $rs;
    }

    function ajax_host_report($res_id) {
//        $rs = $this->query("select    ip ,capacity,port , use_cnt from resource_ip
//		left join (select count(* ) as use_cnt,  egress_id,callee_ip_address from real_cdr   group by egress_id  ,callee_ip_address )  a
//		on  a.egress_id=resource_id and a.callee_ip_address=resource_ip.ip
//	where resource_id=$res_id");
//        return $rs;
    }

    public function findAll_egress($order=null, $conditions='') {
        if (empty($order)) {
            $order = "resource_id  desc";
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(client_id={$_SESSION['sst_client_id']}) ";
        }
        $like_where = !empty($_GET['search']) ? " and (resource.name ilike '%{$_GET['search']}%'  or  resource.resource_id::text ilike '%{$_GET['search']}%' 
		  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  like '%{$_GET['search']}%' )
	    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  like '%{$_GET['search']}%' )
	    or  alias like '%{$_GET['search']}%')" : '';
        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (client.client_id::text='{$_GET ['query'] ['id_clients']}')" : '';
        $product_where = !empty($_GET['product_id']) ? " and resource.route_strategy_id in (select route_strategy_id from route where static_route_id ={$_GET['product_id']})" : "";
        $totalrecords = $this->query("select count(resource.resource_id) as c from resource 
	 	 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
    left join rate_table on resource.rate_table_id=rate_table.rate_table_id
    left join route_strategy on route_strategy.route_strategy_id=resource.route_strategy_id
	 where egress=true $conditions
	  $like_where  $product_where  $name_where    $client_where  $id_where    $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource.alias,resource.resource_id , resource.name ,cps_limit ,capacity,ingress,egress,active, a.ip_cnt , resource.client_id,proto
    ,client.name as client_name,rate_table.name as rate_table_name,rate_table.rate_table_id as rate_table_id	
    from  resource 
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
    left join rate_table on resource.rate_table_id=rate_table.rate_table_id
		 where egress=true $conditions
		$like_where  $product_where  $name_where    $client_where  $id_where    $privilege  ";
        $sql.="   order by  $order ";
        $sql .= " limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function findAll_ingress($order=null, $conditions='') {
        if (empty($order)) {
            $order = "resource_id  desc";
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(client_id={$_SESSION['sst_client_id']}) ";
        }
//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (resource.name like '%{$_GET['search']}%'  or resource.alias like '%{$_GET['search']}%' or resource.resource_id::text like '%{$_GET['search']}%' 
	  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  like '%{$_GET['search']}%' )
    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  like '%{$_GET['search']}%' )
	    or  alias like '%{$_GET['search']}%')" : '';
        $product_where = !empty($_GET['product_id']) ? " and resource.route_strategy_id in (select route_strategy_id from route where static_route_id ={$_GET['product_id']})" : "";
        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (client.client_id::text='{$_GET ['query'] ['id_clients']}')" : '';
        $totalrecords = $this->query("select count(resource.resource_id) as c from resource
 			left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
     left join route_strategy on route_strategy.route_strategy_id=resource.route_strategy_id
	 where ingress=true $conditions
	  $like_where  $product_where  $name_where    $client_where  $id_where    $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $pageSize = $page->getPageSize();
        $currPage = $page->getCurrPage() - 1;
        $offset = $currPage * $pageSize;
        $sql = "select resource.product_id, resource.alias,resource.resource_id , resource.name ,cps_limit,capacity,ingress,egress,active, a.ip_cnt , resource.client_id,proto
    ,client.name as client_name	,route_strategy.route_strategy_id as route_strategy_id,route_strategy.name as route_strategy_name,proto
    from  resource
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
     left join route_strategy on route_strategy.route_strategy_id=resource.route_strategy_id
		 where ingress=true $conditions 
		$like_where  $product_where  $name_where    $client_where $id_where     $privilege  ";
        $sql.=" order by   $order ";
        $sql .= "	limit '$pageSize' offset '$offset'";
        //pr($sql);
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    function searchdyna($id) {
        return $this->query("select dynamic_route_items.id, resource.alias,resource.active,dynamic_route_items.resource_id, client.name
                            ,resource.cps_limit,resource.capacity from dynamic_route_items 
							left join resource 
							on dynamic_route_items.resource_id = resource.resource_id
							left join client
							on resource.client_id  = client.client_id
							where dynamic_route_items.dynamic_route_id = {$id} order by resource.alias asc");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询网关组
     */
    function findResByres_id($res_id) {
        return $this->query("select * from resource where resource_id=$res_id");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询号码转换
     */
    function findresdirectByRes_id($res_id) {
        return $this->query("select * from resource_direction  where resource_id=$res_id order by direction_id asc");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 静态路由表
     */
    function findresproductByRes_id($res_id) {
        return $this->query("select * from resource_product_ref  where resource_id=$res_id");
    }

    /**
     * 查询没有被使用的codecs
     * @param unknown_type $res_id
     */
    function findNousecodecs($res_id) {
        $r = $this->query("select id,name  from  codecs  where  id not in(select codec_id  from  resource_codecs_ref  where  codec_id  is  not null  and  resource_id=$res_id )");
        $size = count($r);
        if ($size == 0) {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 通过res_id查询codes
     */
    function findUsecodecs($res_id) {
        $r = $this->query("select   codec_id,   codecs.name as codec_name   from   resource_codecs_ref    ref  left join codecs  on  codecs.id=ref.codec_id
where  codec_id  is  not null  and  resource_id=$res_id");
        $size = count($r);
        if ($size == 0) {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['codec_id'];
            $l[$key] = $r[$i][0]['codec_name'];
        }
        return $l;
    }

    /**
     * 查看某个host的cdr
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function view_all_cdr($currPage=1, $pageSize=10, $res_id) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
//        $totalrecords = $this->query("select count(real_cdr_id) as c from real_cdr  where  egress_id::text='$res_id'  ");

//        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
//        $sql = "select  ani,dnis ,to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a,callee_ani,callee_dnis   from  real_cdr
//		   where  egress_id::text='$res_id'
//	order by real_cdr_id   	limit '$pageSize' offset '$offset'";
//
//        $results = $this->query($sql);

        $results = array();
        $page->setDataArray($results);
        return $page;
    }

    public function code_cdr($currPage=1, $pageSize=10, $start_code, $end_code) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $where = " where  origination_destination_number >'$start_code' and  origination_destination_number <'$end_code'";
        $totalrecords = $this->query("select count(cdr_id) as c from cdr  $where  ");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  origination_source_number,origination_destination_number ,answer_time_of_date,termination_source_number,termination_destination_number   from  cdr $where

	order by cdr_id   	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function saveHost($account, $id) {
        $count = count($account['ip']);
        $this->bindModel(Array('hasMany' => Array('ResourceIp')));
        $this->ResourceIp->deleteAll(Array("resource_id='$id'"));
        for ($i = 0; $i < $count; $i++) {
            $data = Array();
            $data['resource_id'] = $id;
            $data['port'] = $account['port'][$i];
            if (is_ip($account['ip'][$i])) {
                $data['ip'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['ip'] = $account['ip'][$i] . '/' . array_keys_value($account, 'need_register.' . $i);
                }
            } else {
                $data['fqdn'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['fqdn'] = $account['ip'][$i] . '/' . array_keys_value($account, 'need_register.' . $i);
                }
            }
            $this->ResourceIp->save($data); 
            $this->ResourceIp->id = false;
        }
    }

    function find_resouce_prefix($resource_id=null) {
        //select
        if (!empty($resource_id)) {
            $rp_sql = "select id ,resource_id,tech_prefix,route_strategy_id,rate_table_id from resource_prefix  where resource_id = $resource_id order by id desc";
            $resouce_prefix_list = $this->query($rp_sql);
            return $resouce_prefix_list;
        }
    }

    function find_rate_table() {
        $rate_table_sql = " select rate_table_id as id ,name from rate_table";
        $rate_list = $this->query($rate_table_sql);
        return $rate_list;
    }

    function find_resource() {
        $sql = "select resource_id as id ,alias as name from resource";
        $resource_list = $this->query($sql);
        return $resource_list;
    }

    function find_route_strategy() {
        $route_sql = "select route_strategy_id as id ,name from route_strategy";
        $rout_list = $this->query($route_sql);
        return $rout_list;
    }

    function saveResouce($mydata=null, $resource_id=null) {
        if (!empty($mydata)) {
            for ($i = 0; $i < count($mydata['id']); $i++) {
                if (!empty($mydata['id'][$i])) {
                    $sql = "update resource_prefix set resource_id=" . $resource_id . ",tech_prefix='" . $mydata['tech_prefix'][$i] . "',route_strategy_id=" . $mydata['route_strategy_id'][$i] . ",rate_table_id=" . $mydata['rate_table_id'][$i] . " where id=" . $mydata['id'][$i];
                    $this->query($sql);
                } else {
                    $sql = "insert into resource_prefix(resource_id,tech_prefix,route_strategy_id,rate_table_id) values(" . $resource_id . ",'" . $mydata['tech_prefix'][$i] . "'," . $mydata['route_strategy_id'][$i] . "," . $mydata['rate_table_id'][$i] . ") ";
                    $this->query($sql);
                }
            }
        }
    }
    
    

}