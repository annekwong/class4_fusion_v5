<?php

class CheckRoute extends AppModel
{

//    var $name = 'CheckRoute';
    var $useTable = false;
//    var $primaryKey = "id";

    function get_cdr_packcount($sst_user_id,$start_date,$end_date){

        $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";


        $sql = "SELECT 
count(*)
FROM  client_cdr".date("Ymd")." where  time between '{$start_date}' and '{$end_date}'  {$filter_client}  ";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    function get_cdr_pack($sst_user_id, $order_by, $start_date,$end_date, $pageSize, $offset){

        $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";

        $today_date = date("Ymd");
        $sql = <<<EOT
SELECT * from client_cdr{$today_date} where  time between '{$start_date}' and '{$end_date}'
{$filter_client}
$order_by LIMIT {$pageSize} OFFSET {$offset}
EOT;
        return $this->query($sql);
    }


    function get_cdr_count($sst_user_id, $where){
        $sql = "SELECT 
count(*)
FROM  egress_test  {$where}";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    function get_cdr($sst_user_id, $order_by, $where, $pageSize, $offset){
//        $sql = <<<EOT
//SELECT *,(select alias from resource where resource.resource_id = egress_test.egress_id ) as egress_name from egress_test
//{$where}
//$order_by LIMIT {$pageSize} OFFSET {$offset}
//EOT;
//        return $this->query($sql);
    }


     function get_cdr_count1($sst_user_id, $where){
//        $sql = "SELECT
//count(*)
//FROM  egress_test_result  {$where}";
//        $result = $this->query($sql);
//        return $result[0][0]['count'];
    }

    function get_cdr1($sst_user_id, $order_by, $where, $pageSize, $offset){
//        $sql = <<<EOT
//SELECT * from egress_test_result
//{$where}
//$order_by LIMIT {$pageSize} OFFSET {$offset}
//EOT;
//        return $this->query($sql);
    }


    function get_client_egress()
    {
        $sql = <<<SQL
SELECT client.name,resource.alias,resource.resource_id FROM resource INNER JOIN client ON resource.client_id = client.client_id
WHERE resource.egress = true and alias is not null and trunk_type2 = 0 and alias != ''
SQL;
        $data = $this->query($sql);
        $return_arr = array();
        foreach ($data as $item)
        {
            $return_arr[$item[0]['name']][$item[0]['resource_id']] = $item[0]['alias'];
        }
        return $return_arr;

    }
}
