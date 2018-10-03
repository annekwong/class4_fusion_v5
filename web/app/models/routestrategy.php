<?php

class Routestrategy extends AppModel
{

    var $name = 'Routestrategy';
    var $useTable = 'route_strategy';
    var $primaryKey = 'route_strategy_id';

    /**
     *  分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getAll_dynamic($dynamic_id, $currPage = 1, $pageSize = 15, $search = null, $id = null, $order = null, $dynamic_route_id)
    {
        if (empty($order))
        {
            $order = "route_strategy_id  desc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = 0;
        $sql = "select count(route_strategy_id) as c from route_strategy where 1=1";
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select *,(select count(route_id) from route where route_strategy_id = route_strategy.route_strategy_id) as routes from route_strategy where 1=1";
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($id))
            $sql.="  and route_strategy_id=$id";
        if (!empty($dynamic_id))
        {
            $sql.=" and route_strategy_id in (select route_strategy_id from route where dynamic_route_id=$dynamic_id)";
        }


        $sql .= "  order by $order   limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    public function getAll($currPage = 1, $pageSize = 15, $search = null, $id = null, $order = null, $dynamic_route_id)
    {
        if (empty($order))
        {
            $order = "name  asc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = 0;
        $sql = "select count(route_strategy_id) as c from route_strategy where 1=1 and name !='ORIGINATION_ROUTING_PLAN' AND is_virtual is not true";
        if (!empty($search) && strcmp('Search....', $search))
            $sql .= " and name ilike '%$search%'";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select *,(select count(*) from (select resource_id from resource_prefix where route_strategy_id = route_strategy.route_strategy_id group by resource_id) as t) as routes from route_strategy where 1=1";
        if (!empty($search) && strcmp('Search....', $search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($id))
            $sql.="  and route_strategy_id=$id";
        if (!empty($dynamic_route_id))
        {
            $sql.=" and route_strategy_id in (select route_strategy_id from route where dynamic_route_id=$dynamic_route_id)";
        }
        if (!empty($_GET['filter_static']))
        {
            $sql .= " and (
                    select count(*) from route where static_route_id = {$_GET['filter_static']} 
                    and route_strategy_id = route_strategy.route_strategy_id
                    )  >  0";
        }
        $sql .= "  and name !='ORIGINATION_ROUTING_PLAN'";
        $sql .= "AND is_virtual is not true";
        $sql .= "  order by $order   limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        //echo $sql;
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    /**
     * 查询路由策略详细
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     * @param unknown_type $search
     */
    public function getAllRoutes($currPage = 1, $pageSize = 15, $search = null, $pid = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;


        $sql = "select count(route_id) as c from route where route_strategy_id = $pid and lrn_block = false";
        if (!empty($search))
            $sql .= " and( digits <@ '$search' or digits @> '$search'  or (select count(*)>0 from   route_strategy where name like '%{$search}%') )";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $orderby = '';

        if (isset($_GET['order_by']))
        {
            $orderarr = explode('-', $_GET['order_by']);
            $orderby = "order by {$orderarr[0]}  {$orderarr[1]}";
        }

        //查询Client groups
        $sql = "select
                route_id,(select name from product where product_id = route.static_route_id) as static_route, static_route_id,
                (select name from dynamic_route where dynamic_route_id = route.dynamic_route_id) as dynamic_route,dynamic_route_id,
                (select name from product where product_id = route.intra_static_route_id) as intra_static_route,intra_static_route_id,
                (select name from product where product_id = route.inter_static_route_id) as inter_static_route,inter_static_route_id,
                (select name from jurisdiction_country where jurisdiction_country.id = route.jurisdiction_country_id) as jurisdiction_country,
                (select name from route_strategy where route_strategy_id = route.route_strategy_id) as strategy,digits_min_length,digits_max_length,
                route_type,digits,lnp,lrn_block,dnis_only,update_at,update_by,ani_prefix,ani_min_length,ani_max_length,route_type_flg from route
                where  route_strategy_id = $pid  and lrn_block = false";

        if (!empty($search))
            $sql .= " and( digits <@ '$search' or digits @> '$search' or (select count(*)>0 from   route_strategy where name like '%{$search}%'))";

        $sql .= " $orderby limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);


        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    /*
     * 根据ID获得Group
     */

    public function getGroupById($ownid)
    {
        $sql = "select  * from route_strategy where route_strategy_id = '$ownid'";
        return $this->query($sql);
    }

    public function add()
    {
        $name = $_REQUEST['name'];
        if (empty($name))
        {
            return __('The field Name cannot be NULL.', true) . "|false";
        }

        $exists = $this->query("select route_strategy_id from route_strategy where name = '$name'");
        if (count($exists) > 0)
        {
            return __($name . ' is already in use!', true) . "|false";
        }
        $sql = "insert into route_strategy(name, update_by) values('$name', '{$_SESSION['sst_user_name']}') returning route_strategy_id";

        $qs = $this->query($sql);
        if ($qs)
        {
            $rollback_sql = "DELETE FROM route_strategy WHERE route_strategy_id = {$qs[0][0]['route_strategy_id']}";
            $rollback_msg = "Create Routing Plan [" . $name . "] operation have been rolled back!";
            $log_id = $this->logging(0, 'Routing Plan', "Routing Plan Name:{$name}", $rollback_sql, $rollback_msg);
            return __('The Routing Plan [%s] is created successfully!', true, $name) . "|true|" . base64_encode($qs[0][0]['route_strategy_id']) . "|" . $log_id;
        }
        return __('add_fail', true) . "|false";
    }

    public function update()
    {
        $name = $_REQUEST['name'];
        $id = $_REQUEST['id'];
        if (empty($name))
        {
            return __('The field Name cannot be NULL.', true) . "|false";
        }

        $oldname = $this->query("select name from route_strategy where route_strategy_id = $id");

        if ($oldname[0][0]['name'] != $name)
        {
            $exists = $this->query("select route_strategy_id from route_strategy where name = '$name'");
            if (count($exists) > 0)
            {
                return __($name . 'is already in use!', true) . "|false";
            }
        }

        $update_at = date("Y-m-d H:i:sO");

        $qs = $this->query("update route_strategy set name = '$name', update_by = '{$_SESSION['sst_user_name']}', update_at = '{$update_at}' where route_strategy_id = $id");

        if (count($qs) == 0)
        {
            $rollback_sql = "UPDATE route_strategy SET name = '{$oldname[0][0]['name']}', update_by = '{$_SESSION['sst_user_name']}' WHERE route_strategy_id = $id";
            $rollback_msg = "Edit Routing Plan [" . $oldname[0][0]['name'] . "] operation have been rolled back!";
            $log_id = $this->logging(2, 'Routing Plan', "Routing Plan Name:{$name}", $rollback_sql, $rollback_msg);
            return __('Edit Routing Plan successfully!', true) . "|true|" . $log_id;
        }
        return __('update_fail', true) . "|false";
    }

    public function getAddInfo($reseller_id)
    {
        /*
          $product_sql = "select product_id,name from product";
          $dynamic_sql = "select dynamic_route_id,name from dynamic_route";
          if (!empty($reseller_id)) {
          $product_sql .= " where reseller_id = $reseller_id order by name asc";
          $dynamic_sql .= " where reseller_id = $reseller_id order by name asc";
          }
          $product_sql .= " order by name asc";
          $dynamic_sql .= " order by name asc";
          $jur_country_sql = "SELECT id, name FROM jurisdiction_country ORDER BY name ASC";
          return array($this->query($product_sql), $this->query($dynamic_sql), $this->query($jur_country_sql));
         * 
         */
        $jur_country_sql = "SELECT id, name FROM jurisdiction_country ORDER BY name ASC";
        return $this->query($jur_country_sql);
    }

    function get_dynamics($reseller_id, $search_name, $page, $pageSize)
    {

        $dynamic_sql = "select dynamic_route_id,name from dynamic_route";

        $count_sql = "select count(*) from dynamic_route";

        $conditions = array();

        if (!empty($reseller_id))
        {
            array_push($conditions, "reseller_id = $reseller_id");
        }
        if (!empty($search_name))
        {
            array_push($conditions, "name ilike '%{$search_name}%'");
        }

        if (count($conditions))
        {
            $dynamic_sql .= " where " . implode(" and ", $conditions);
            $count_sql .= " where " . implode(" and ", $conditions);
        }


        $count_result = $this->query($count_sql);
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows / $pageSize);
        $offset = $pageSize * ($page - 1);

        $dynamic_sql .= " order by name asc limit {$pageSize} offset {$offset}";
        return $this->query($dynamic_sql);
    }

    function get_products($reseller_id, $search_name, $page, $pageSize)
    {
        $product_sql = "select product_id,name from product";
        $count_sql = "select count(*) from dynamic_route";
        $conditions = array();
        if (!empty($reseller_id))
        {
            array_push($conditions, "reseller_id = $reseller_id");
        }
        if (!empty($search_name))
        {
            array_push($conditions, "name ilike '%{$search_name}%'");
        }

        if (count($conditions))
        {
            $product_sql .= " where " . implode(" and ", $conditions);
            $count_sql .= " where " . implode(" and ", $conditions);
        }

        $count_result = $this->query($count_sql);
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows / $pageSize);
        $offset = $pageSize * ($page - 1);

        $product_sql .= "  order by name asc limit {$pageSize} offset {$offset}";
        return $this->query($product_sql);
    }



    public function update_route()
    {
        $digits = ($_REQUEST['digits'] == '') ? 'null' : "'" . $_REQUEST['digits'] . "'";
        $ani_digits = ($_REQUEST['ani_digits'] == '') ? 'null' : "'" . $_REQUEST['ani_digits'] . "'";
        $static_route_id = empty($_REQUEST['static_route_id']) ? 'null' : $_REQUEST['static_route_id'];
        $dynamic_route_id = empty($_REQUEST['dynamic_route_id']) ? 'null' : $_REQUEST['dynamic_route_id'];
        $intra_static_route_id = empty($_REQUEST['intra_static_route_id']) ? 'null' : $_REQUEST['intra_static_route_id'];
        $inter_static_route_id = empty($_REQUEST['inter_static_route_id']) ? 'null' : $_REQUEST['inter_static_route_id'];
        $jurisdiction_country_id = empty($_REQUEST['jurisdiction_country_id']) ? 'null' : $_REQUEST['jurisdiction_country_id'];
        switch ($_REQUEST['route_type']){
            case 5:
                $route_type = 2;
                break;
            case 6:
                $route_type = 3;
                break;
            case 7:
                $route_type = 4;
                break;
            default:
                $route_type = $_REQUEST['route_type'];
        }
        $id = $_REQUEST['id'];
        $pid = $_REQUEST['pid'];
        $ani_min_length = empty($_REQUEST['ani_min_length']) ? '0' : $_REQUEST['ani_min_length'];
        $ani_max_length = empty($_REQUEST['ani_max_length']) ? '32' : $_REQUEST['ani_max_length'];
        $digits_min_length = empty($_REQUEST['digits_min_length']) ? '0' : $_REQUEST['digits_min_length'];
        $digits_max_length = empty($_REQUEST['digits_max_length']) ? '32' : $_REQUEST['digits_max_length'];


//        $lnp = ($_REQUEST['lnp'] == 'true') ? 'true' : 'false';
//        $lrn_block = ($_REQUEST['lrn_block'] == 'true') ? 'true' : 'false';
//        $dnis_only = ($_REQUEST['dnis_only'] == 'true') ? 'true' : 'false';
        /* 		$digits='10000';
          $dynamic_route_id='20';
          $static_route_id='3';
          $route_type='3';
          $pid='88';
          $id='24';
         */
//	  if (empty($digits)||$digits=='null'){
//	    	return __('Prefix, cannot be null!',true)."|false";
//		}
//        if ($dnis_only == 'true' && $lnp == 'true')
//        {
//            return 'while DNIS Only selected  LRN and Block LRN  can  not  select' . "|false";
//        }
//        if ($dnis_only == 'true' && $lrn_block == 'true')
//        {
//            return 'while DNIS Only selected  LRN and Block LRN  can  not  select' . "|false";
//        }
//
//
//        if ($dnis_only == 'true' && $lrn_block == 'true' && $lnp == 'true')
//        {
//            return 'while DNIS Only selected  LRN and Block LRN  can  not  select' . "|false";
//        }

        /*
          $olddigits = $this->query("select digits from route where route_id = $id");
          if ("'" . $olddigits[0][0]['digits'] . "'" != $digits) {
          $exists = $this->query("select digits from route where digits::text = $digits::text and ani_prefix::text = {$ani_digits}::text and  route_strategy_id = $pid");
          if (count($exists) > 0) {
          return __($ani_digits .',' . $digits . 'is already in use!', true) . "|false";
          }
          }
         * 
         */

        $update_at = date("Y-m-d H:i:sO");
        $update_by = $_SESSION['sst_user_name'];

        $olddata = $this->query("SELECT * FROM route WHERE route_id=$id limit 1");
        $old_digits = ($olddata[0][0]['digits'] == '') ? 'null' : "'" . $olddata[0][0]['digits'] . "'";
        $old_ani_digits = ($olddata[0][0]['ani_prefix'] == '') ? 'null' : "'" . $olddata[0][0]['ani_prefix'] . "'";
        $old_static_route_id = empty($olddata[0][0]['static_route_id']) ? 'null' : $olddata[0][0]['static_route_id'];
        $old_dynamic_route_id = empty($olddata[0][0]['dynamic_route_id']) ? 'null' : $olddata[0][0]['dynamic_route_id'];
        $old_intra_static_route_id = empty($olddata[0][0]['intra_static_route_id']) ? 'null' : $olddata[0][0]['intra_static_route_id'];
        $old_inter_static_route_id = empty($olddata[0][0]['inter_static_route_id']) ? 'null' : $olddata[0][0]['inter_static_route_id'];
        $old_jurisdiction_country_id = empty($olddata[0][0]['jurisdiction_country_id']) ? 'null' : $olddata[0][0]['jurisdiction_country_id'];
        $old_route_type = $olddata[0][0]['route_type'];
        $old_ani_min_length = empty($olddata[0][0]['ani_min_length']) ? '0' : $olddata[0][0]['ani_min_length'];
        $old_ani_max_length = empty($olddata[0][0]['ani_max_length']) ? '32' : $olddata[0][0]['ani_max_length'];
        $old_digits_min_length = empty($olddata[0][0]['digits_min_length']) ? '0' : $olddata[0][0]['digits_min_length'];
        $old_digits_max_length = empty($olddata[0][0]['digits_max_length']) ? '32' : $olddata[0][0]['digits_max_length'];
        $old_lnp = ($olddata[0][0]['lnp'] == 'true') ? 'true' : 'false';
        $old_lrn_block = ($olddata[0][0]['lrn_block'] == 'true') ? 'true' : 'false';
        $old_dnis_only = ($olddata[0][0]['dnis_only'] == 'true') ? 'true' : 'false';
        $insert_sql = "update route set digits=$digits::text,ani_prefix=$ani_digits::text,dynamic_route_id=$dynamic_route_id,
		static_route_id=$static_route_id,route_type=$route_type, intra_static_route_id=$intra_static_route_id, 
                inter_static_route_id=$inter_static_route_id,jurisdiction_country_id=$jurisdiction_country_id, update_at='{$update_at}', "
            . "update_by ='{$update_by}',ani_min_length = {$ani_min_length}, ani_max_length= {$ani_max_length}  "
            . ",digits_min_length = {$digits_min_length}, digits_max_length= {$digits_max_length} where route_id=$id";
        $qs = $this->query($insert_sql);


        $name_result = $this->query("select name from  route_strategy where
route_strategy_id=(select route_strategy_id from route where route_id = {$id} limit 1);");


        if (count($qs) == 0)
        {
            $rollback_sql = "update route set digits=$old_digits::text,ani_prefix=$old_ani_digits::text,dynamic_route_id=$old_dynamic_route_id,lnp=$old_lnp,lrn_block=$old_lrn_block,dnis_only=$old_dnis_only,
		static_route_id=$old_static_route_id,route_type=$old_route_type, intra_static_route_id=$old_intra_static_route_id, 
                inter_static_route_id=$old_inter_static_route_id,jurisdiction_country_id=$old_jurisdiction_country_id,ani_min_length = {$old_ani_min_length}, ani_max_length= {$old_ani_max_length} "
                . ",digits_min_length = {$old_digits_min_length}, digits_max_length= {$old_digits_max_length} where route_id=$id";
            $rollback_msg = "Create Route operation have been rolled back!";
            $this->logging(2, 'Route', "Route Plan\'s Name:" . $name_result[0][0]['name'] . ", Route Prefix:" . trim($digits, "' "), $rollback_sql, $rollback_msg);

            return __('Edit Routing successfully!', true) . "|true";
        }
        return __('update_fail', true) . "|false";
    }

    public function select_name($id = null)
    {
        if (!empty($id))
        {
            $sql = "select * from route_strategy where route_strategy_id = $id";
            $rs_name = $this->query($sql);
            return $rs_name;
        }
    }

    /*
     * 通过ID批量获取名称
     */

    public function getNameByids($ids)
    {
        $sql = "SELECT name FROM route_strategy WHERE route_strategy_id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }

}
