<?php

class Product extends AppModel
{

    var $name = 'Product';
    var $useTable = 'product';
    var $primaryKey = 'product_id';

    public function getAllProducts($currPage = 1, $pageSize = 15, $search = null, $order = null, $name = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = 0;

        if (empty($order))
        {
            $order = "name asc";
        }

        $resource_where = " AND name != 'ORIGINATION_STATIC_ROUTE'";

        if (isset($_GET['resource_id']))
            $resource_where = "and exists (
  SELECT * FROM product_items WHERE product_items.product_id = product.product_id AND exists (SELECT * FROM product_items_resource 
  WHERE product_items_resource.item_id = product_items.item_id and resource_id = {$_GET['resource_id']})
)";
        $sql = "select count(product_id) as c from product where dynamic_route_id  is  null $resource_where";
        if (!empty($search))
            $sql .= " and (name ilike '%$search%'  or  (select count(item_id)>0 from product_items   where  digits::text like '$search%')   )";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";




        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select product.product_id as id,route_lrn,
                product.name as name,
                product.modify_time as m_time,product.code_type as code_type,product.code_deck_id as code_deck_id ,
                (
                        select count(item_id) from product_items 
                        where product_id = product.product_id
                ) as routes,
                (
                select count(*) as ingress from ( 

                select distinct route_strategy.name from route_strategy left join route 

                on route.route_strategy_id = route_strategy.route_strategy_id where route.static_route_id = product.product_id 

                ) as tbl) as ingress,product.update_by
                from product where dynamic_route_id  is  null $resource_where";
        if (!empty($name))
            $sql.=" and name = '$name'";
        if (!empty($search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";
        if ($order != null)
        {
            $sql.=" order by  $order ";
        }
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    /*
     * 添加Product
     */

    public function addProduct($name = null, $reseller_id = 0, $code_type = 0, $code_deck_id = 0, $route_lrn = 0)
    {

        $nowtime = date('Y-m-d H:i:s', time() + 6 * 60 * 60);
        $sql = "insert into product (name,modify_time, update_by,code_type,code_deck_id, route_lrn) values('$name','$nowtime', '{$_SESSION['sst_user_name']}',{$code_type},{$code_deck_id},{$route_lrn}) RETURNING product_id";

        if (!empty($reseller_id))
        {
            $sql = "insert into product (name,modify_time,reseller_id,update_by,route_lrn) values('$name','$nowtime','$reseller_id','{$_SESSION['sst_user_name']}', {$route_lrn}) RETURNING product_id";
        }//echo $sql;
        $c = $this->query($sql);

        if (!empty($c))
        {
            return $c[0][0]['product_id'];
        }
        return false;
    }

    /**
     * 根据ID删除某Product
     * @param $id Product ID
     */
    public function del_product($id = null)
    {
        if (!empty($id))
        {
            if ($this->del($id))
            {
                return true;
            }
            return false;
        }
    }

    /*
     * 根据name获取id
     * 返回ID
     */

    public function get_id($name)
    {
        $sql = "SELECT product_id from product where name = {$name}";
        $id = $this->query($sql);
        return $id;
    }

    /**
     * 修改Product的名字
     * @param $id Product ID
     * @param $name Product Name
     */
    public function modify_name($id = null, $name = null, $route_lrn = null, $code_deck_id = null, $codeType = null)
    {
        $lastmodify = date('Y-m-d H:i:s');
        $sql = "update product set name = '$name',route_lrn = {$route_lrn},modify_time = '$lastmodify', update_by = '{$_SESSION['sst_user_name']}', code_deck_id = '{$code_deck_id}', code_type = '{$codeType}' where product_id = '$id'";
        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return $lastmodify;
        }
        else
        {
            return false;
        }
    }

    /**
     *  分页查询Product对应的Route
     * @param int $id Product ID
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getRoutesByProduct($id = null, $currPage = 1, $pageSize = 15, $search = null, $order = null)
    {
        if (empty($order))
        {
            $order = "item_id  desc";
        }
        $countsql = "select count(item_id) as c from product_items where product_id = '$id'";

        $_SESSION['product_search'] = "";
        if (!empty($search))
        {
            $countsql .= " and digits <@ '$search'";
            $_SESSION['product_search'] = "and StaticRoute.digits <@ '$search'";
        }

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($countsql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        if ($totalrecords[0][0]['c'] == 0)
        {
            $page->setDataArray(array());
            return $page;
        }

        //查询Product
        $sql = "
							select item_id,alias,digits,strategy,
								(select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
								array(
							 select resource.alias from resource left join product_items_resource on resource.resource_id = product_items_resource.resource_id
							 where resource.ingress='t' AND product_items_resource.item_id =product_items.item_id order by product_items_resource.id asc
								) as ingress_alias,
								array(
							 select resource.alias from resource left join product_items_resource on resource.resource_id = product_items_resource.resource_id
							 where resource.egress='t' AND product_items_resource.item_id =product_items.item_id order by product_items_resource.id asc
								) as egress_alias,update_at,update_by
							from product_items
							where product_id = '$id'
						 ";

        if (!empty($search))
        {
            $sql .= " and digits <@ '$search' limit '$pageSize' offset '$offset'";
        }
        else
        {
            $sql .= " order by $order  limit '$pageSize' offset '$offset'";
        }
        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    public function getRoutesCodeNameByProduct($id = null, $currPage = 1, $pageSize = 15, $search = null, $order = null)
    {


        if (empty($order))
        {
            $order = "group_code_name.code_name  asc";
        }
        $countsql = "select code_name as c from product_items where product_id = '$id' group by code_name";

        $_SESSION['product_search'] = "";
        if (!empty($search))
        {
            $countsql .= " having code_name like '%$search%'";
            $_SESSION['product_search'] = "and StaticRoute.code_name <@ '%$search%'";
        }

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($countsql);

        $page->setTotalRecords(count($totalrecords)); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        if (count($totalrecords) == 0)
        {
            $page->setDataArray(array());
            return $page;
        }

        //查询Product
        $sql = "
							select (select update_at from product_items where product_id ='$id' and code_name = group_code_name.code_name  order by update_at desc limit 1) as update_at, array(select item_id from product_items where product_id ='$id' and code_name = group_code_name.code_name) as item_id, group_code_name.code_name as code_name,group_code_name.strategy as strategy,
group_code_name.time_profile as time_profile,group_code_name.alias as alias,group_code_name.update_by as update_by
 from (select item_id,digits,strategy,code_name,
	(select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
	array(
 select resource.alias from 
 resource left join product_items_resource on resource.resource_id = product_items_resource.resource_id
 where product_items_resource.item_id =product_items.item_id order by product_items_resource.id asc
	) as alias,update_at,update_by
from product_items
where product_id = '$id') as group_code_name group by group_code_name.code_name,group_code_name.strategy,
group_code_name.time_profile,group_code_name.alias,group_code_name.update_by
						 ";

        if (!empty($search))
        {
            $sql .= " having group_code_name.code_name like '%$search%' limit '$pageSize' offset '$offset'";
        }
        else
        {
            $sql .= " order by $order  limit '$pageSize' offset '$offset'";
        }

        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    /*
     * 根据删除一条记录
     */

    function deleteById($id = null)
    {
        if (!empty($id))
        {
            $sql = "delete from product_items where item_id = '$id'";
            $c = $this->query($sql);
            if (count($c) == 0)
            {
                return true;
            }
        }
        return false;
    }

    function deleteByIds($ids)
    {
        if (!empty($ids))
        {
            $sql = "delete from product_items where item_id in ($ids)";
            $c = $this->query($sql);
            if (count($c) == 0)
            {
                return true;
            }
        }
        return false;
    }

    /*
     * 删除所有
     */

    public function deleteAll($id = null)
    {
        $sql = "delete from product_items where product_id = '$id'";
        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return true;
        }
        return false;
    }

    /*
     * 删除选中的
     */

    public function deleteSelected($ids = null)
    {
        $sql = "delete from product_items where item_id in ($ids)";
        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return true;
        }
        return false;
    }

    /*
     * 查询所有的Resource
     */

    public function getResource()
    {
        $sql = "select resource_id,alias as name from resource where egress = true";
        $r = $this->query($sql);
        return $r;
    }

    /*
     * Query all time profiles
     */

    public function getTimeProfiles()
    {
        $sql = "select time_profile_id as time_id,* from time_profile order by name ASC ";
        $r = $this->query($sql);
        return $r;
    }

    /*
     * 根据ID查询Route信息
     */

    public function getRouteById($id = null)
    {
        $sql = "select item_id,alias,digits,strategy,time_profile_id 
								from product_items where item_id = '$id'";

        $r = $this->query($sql);
        return $r;
    }

    /*
     * 根据product_id 得到下面所有的Route信息
     */

    public function getDownInfoById($product_id)
    {
        if (!empty($product_id))
        {
            $sql = "select item_id,alias,digits,
										case 
											when strategy = 0 then 'Weight'
											when strategy = 1 then 'Top-Down'
											when strategy = 2 then 'Round-Robin'
										end as strategy,
										(
										select start_date from time_profile 
										where time_profile_id = product_items.time_profile_id
										) as start_date,
										(
										select end_date from time_profile 
										where time_profile_id = product_items.time_profile_id
										) as end_date,
										(select name from resource where resource_id = resource_id_1) as Route1,
										(select name from resource where resource_id = resource_id_2) as Route2,
										(select name from resource where resource_id = resource_id_3) as Route3,
										(select name from resource where resource_id = resource_id_4) as Route4,
										(select name from resource where resource_id = resource_id_5) as Route5,
										(select name from resource where resource_id = resource_id_6) as Route6,
										(select name from resource where resource_id = resource_id_7) as Route7,
										(select name from resource where resource_id = resource_id_8) as Route8,
										percentage_1,percentage_2,percentage_3,percentage_4,
										percentage_5,percentage_6,percentage_7,percentage_8
							from product_items  where product_id = '$product_id'";

            return $this->query($sql);
        }
        return array();
    }

    /*
     * 得到所有的Product信息
     */

    public function getAllProduct($reseller_id)
    {
        $sql = "select product_id,name from product";
        echo $reseller_id;
        exit();
        if (!empty($reseller_id))
            $sql .= " where reseller_id = '$reseller_id'";
        $ps = $this->query($sql);
        $d = "[";
        $loop = count($ps);
        for ($i = 0; $i < $loop; $i++)
        {
            $id = $ps[$i][0]['product_id'];
            $name = $ps[$i][0]['name'];

            $d .= "{id:'$id',name:'$name'},";
        }
        if (strlen($d) != 1)
        {
            $d = substr($d, 0, strlen($d) - 1) . "]";
        }
        else
        {
            $d = $d . "]";
        }
        return $d;
    }

    /*
     * Swap products
     */

    function xproduct_items($a, $b)
    {
        try
        {
            $this->begin();
            $alist = $this->x_query("select item_id from product_items where product_id=$a");
            $blist = $this->x_query("select item_id from product_items where product_id=$b");
            $aid = array_keys_value($alist, '0.0.item_id');
            $bid = array_keys_value($blist, '0.0.item_id');
            if (!empty($bid))
            {
                $this->x_query("update product_items set product_id = '$a' where item_id=$bid");
            }
            if (!empty($aid))
            {
                $this->x_query("update product_items set product_id = '$b' where item_id=$aid");
            }
            $this->commit();
        }
        catch (Exception $e)
        {
            $this->rollback();
            return false;
        }
    }

    public function swapProduct($product_id_a = null, $product_id_b = null)
    {
        if (empty($product_id_a) || empty($product_id_b))
        {
            return false;
        }
        //All routes of product a
        $product_a_routes = $this->query("select item_id from product_items where product_id = '$product_id_a'");

        //All routes of product b
        $product_b_routes = $this->query("select item_id from product_items where product_id = '$product_id_b'");

        //Count all id of routes of product
        $id_of_route_of_product = "";
        $loop = count($product_a_routes);
        for ($i = 0; $i < $loop; $i++)
        {
            $id_of_route_of_product .= "'" . $product_a_routes[$i][0]['item_id'] . "',";
        }
        $id_of_route_of_product = substr($id_of_route_of_product, 0, strlen($id_of_route_of_product) - 1);

        $id_of_route_of_product_b = '';
        $loop = count($product_b_routes);
        for ($i = 0; $i < $loop; $i++)
        {
            $id_of_route_of_product_b .= "'" . $product_b_routes[$i][0]['item_id'] . "',";
        }
        $id_of_route_of_product_b = substr($id_of_route_of_product_b, 0, strlen($id_of_route_of_product_b) - 1);


        //Begin transaction
        //Swapping products
        $this->begin();
        try
        {
            $qs = $this->query("update product_items set product_id = '$product_id_b' where item_id in ($id_of_route_of_product)");

            $qs1 = $this->query("update product_items set product_id = '$product_id_a' where item_id in ($id_of_route_of_product_b)");

            if (count($qs) == 0 && count($qs1) == 0)
            {
                $this->commit();
                return true;
            }
        }
        catch (Exception $e)
        {
            $this->rollback();
            return false;
        }
    }

    /*
     * 通过ID获取name
     */

    public function getNameByids($ids)
    {
        $sql = "SELECT name from Product where product_id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }

    //通过egress id 得到static route
    public function get_staticrouteBytrunk($resource_id, $condition = "")
    {
        $resource_id = (int) $resource_id;
        $sql = <<<EOD
SELECT product.product_id,items.digits,product.name FROM product_items AS items LEFT JOIN  
product_items_resource AS items_r ON items.item_id = items_r.item_id 
LEFT JOIN product AS product ON items.product_id = product.product_id
WHERE items_r.resource_id = {$resource_id} {$condition};
EOD;
        $result = $this->query($sql);
        return $result;
    }

}
