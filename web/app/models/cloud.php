<?php

class Cloud extends AppModel
{

//    var $name = 'Cloud';
    var $useTable = false;
//    var $primaryKey = "id";

    function get_cdr_count($sst_user_id, $where){
//        $sql = "SELECT
//count(*)
//FROM  cdr_compare_cloud  {$where}";
//        $result = $this->query($sql);
//        return $result[0][0]['count'];
        return 0;
    }
    
    function get_cdr($sst_user_id, $order_by, $where, $pageSize, $offset){
//        $sql = <<<EOT
//SELECT * from cdr_compare_cloud
//{$where}
//$order_by LIMIT {$pageSize} OFFSET {$offset}
//EOT;
//        return $this->query($sql);
        return array();
    }
    
}
