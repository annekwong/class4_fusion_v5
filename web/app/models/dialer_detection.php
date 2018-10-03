<?php

class DialerDetection extends AppModel {
    
    var $name = 'DialerDetection';
    var $useTable = "dialer_detection"; 
    var $primaryKey = "id";
    
    
    public function data_list ($order_sql)
    {
        $sql = "SELECT * FROM dialer_detection {$order_sql}";
        $data = $this->query($sql);
        return $data;
    }
    
    public function execution_log_count($where)
    {
        $sql = "SELECT count(*) as sum FROM execution_log {$where}";
        $data = $this->query($sql);
        return $data[0][0]['sum'];
    }
    
    public function execution_log_list($where,$order_sql,$pageSize,$offset)
    {
        $sql = "SELECT * FROM execution_log {$where} {$order_sql}";
        $sql .= "  limit '$pageSize' offset '$offset'";
        $data = $this->query($sql);
        return $data;
    }
    
    public function ani_blocking_log_count($where)
    {
//        $sql = "SELECT count(*) as sum FROM ani_blocking_log {$where}";
//        $data = $this->query($sql);
//        return $data[0][0]['sum'];
    }
    
    public function ani_blocking_log_list($where,$order_sql,$pageSize,$offset)
    {
//        $sql = "SELECT * FROM ani_blocking_log {$where} {$order_sql}";
//        $sql .= "  limit '$pageSize' offset '$offset'";
//        $data = $this->query($sql);
//        return $data;
    }
    
}