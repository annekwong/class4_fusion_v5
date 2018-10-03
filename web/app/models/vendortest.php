<?php

class Vendortest extends AppModel {
//    var $name = 'Vendortest';
    var $useTable = false;
//    var $primaryKey = 'id';
    
    public function get_trunk() {
        $sql = "select resource_id,alias from resource where egress = true and status = 1 order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    public function get_code_name() {
        $sql = "select distinct name from code where 1=1 group by name";
        $result = $this->query($sql);
        return $result;
    }
	
	
    public function get_trunk_name($project_id) {
//        $sql = "select alias from resource where egress = true and status = 1 and rate_table_id =(
//select trunk from vendortest_project where id = {$project_id} );";
//        $result = $this->query($sql);
//        return $result[0][0]['alias'];
    }
    
    public function get_project_name($id) {
//        $sql = "select project_name from vendortest_project where id = {$id}";
//        $result = $this->query($sql);
//        return $result[0][0]['project_name'];
    }
    
    
    
    public function get_codename($trunk_id) {
        $sql = "select distinct code_name from rate where code_name != '' and rate_table_id = (select rate_table_id from resource  where resource_id = {$trunk_id})";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $val) {
            array_push($arr, $val[0]);
        }
        return $arr;
    }
    
    public function get_code($rate_table_id, $code_name) {
        $sql = "select code from rate  where rate_table_id = {$rate_table_id} and  code_name = '{$code_name}'  group by code";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $val) {
            array_push($arr, $val[0]);
        }
        return $arr;
    }
    /*
    public function get_clientcdr($codes,$pageSize,$offset) {
        $sql = "select origination_destination_number from client_cdr where answer_time_of_date::bigint  > 0 and  call_duration::bigint > 0 group by 
origination_destination_number having " ;
        $having_arr = array();
        foreach($codes as $code) {
            array_push($having_arr, "origination_destination_number like '{$code['code']}%'");
        }
        $having_str = implode(' or ', $having_arr);
        $sql .= $having_str;
        $sql .= " limit $pageSize offset $offset";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $val) {
            array_push($arr, $val[0]);
        }
        return $arr;
    }
    
    public function get_clientcdr_counts($codes) {
        $sql = "select count(1) as c from (select origination_destination_number from client_cdr where answer_time_of_date::bigint  > 0 and  call_duration::bigint > 0 group by 
origination_destination_number having " ;
        $having_arr = array();
        foreach($codes as $code) {
            array_push($having_arr, "origination_destination_number like '{$code['code']}%'");
        }
        $having_str = implode(' or ', $having_arr);
        $sql .= $having_str;
        $sql .= ') t';
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    */
    public function get_clientcdr($codes) {
        $sql = "select origination_destination_number from (select origination_destination_number,id from client_cdr".date("Ymd")." where answer_time_of_date::bigint  > 0 and  call_duration::bigint > 0 group by 
origination_destination_number,id having " ;
        $having_arr = array();
        foreach($codes as $code) {
            array_push($having_arr, "origination_destination_number like '{$code['code']}%'");
        }
        $having_str = implode(' or ', $having_arr);
        $sql .= $having_str;
        $sql .= " order by id desc) t group by origination_destination_number";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $val) {
            array_push($arr, $val[0]);
        }
        return $arr;
    }
    
    public function generatekey($id) {
        $sql = "insert into vendortest (vendortest_key) values ('{$id}')";
        $this->query($sql);
    }
    
    public function checkkey($vendor_key) {
        $sql = "SELECT count(*) as c from vendortest where vendortest_key = '{$vendor_key}'";
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    
    public function addproject($project_name, $trunk) {
//        $sql = "INSERT INTO vendortest_project(project_name, trunk) VALUES ('{$project_name}', {$trunk}) RETURNING id";
//        $result = $this->query($sql);
//        return $result[0][0]['id'];
    }
    
    public function  get_ratetable($id) {
//        $sql = "SELECT trunk FROM vendortest_project WHERE id = {$id}";
//        $result = $this->query($sql);
//        return $result[0][0]['trunk'];
    }
    
    public function add_code($vendortest_project_id, $code_name) {
//        $sql = "INSERT INTO vendortest_code(vendortest_project_id, code_name) VALUES ({$vendortest_project_id},'{$code_name}') RETURNING id";
//        $result = $this->query($sql);
//        return $result[0][0]['id'];
    }
    
    public function add_number($code_id, $number,$cal_number, $cal_time) {
//        $sql = "insert into vendortest_number (vendortest_code_id, test_number, source_number, call_time)
//            values ($code_id, $number, $cal_number, $cal_time)";
//        $this->query($sql);
    }
    
    public function get_trunk_ip($id) {
        $sql = "select ip,port from resource_ip where resource_id =  {$id}";
        $result = $this->query($sql);
        $arr = array();
        $arr['ip'] = $result[0][0]['ip'];
        $arr['port'] = $result[0][0]['port'];
        return $arr;
    }
    
    public function get_system_ip_port() {
        $sql = "select ip, port from  server_platform  where server_type=2";
        $result = $this->query($sql);
        $arr = array();
        $arr['ip'] = $result[0][0]['ip'];
        $arr['port'] = $result[0][0]['port'];
        return $arr;
    }
    
    public function start_project($id) {
//        $sql = "update vendortest_project set status = 1 where id = {$id}";
//        $this->query($sql);
    }
    
    public function delete_project($id) {
//        $sql = "
//delete from vendortest_number  where vendortest_code_id in
//(select id from vendortest_code where vendortest_project_id = {$id});
//
//delete from vendortest_code where vendortest_project_id = {$id};
//
//delete from vendortest_project where id = {$id};";
//        $this->query($sql);
    }
    
    public function get_code_summary($id) {
//        $sql = "select vendortest_code.id,vendortest_code.code_name,vendortest_project.project_name,
//vendortest_project.trunk
//from vendortest_code
//join vendortest_project on vendortest_project.id = vendortest_code.vendortest_project_id
//where vendortest_code.vendortest_project_id = {$id}";
//        $result = $this->query($sql);
//        return $result;
    }
    
    public function get_detail($id) {
//        $sql = "select
//
//to_char(to_timestamp(substring(start_epoch::text from 1 for 10)::bigint), 'YYYY/MM/DD/HH24/MI') as time, vendortest_number_summary.*, sip_capture_path
//
//from vendortest_number_summary
//
//INNER JOIN vendortest_number
//
//ON vendortest_number_summary.vendortest_code_id = vendortest_number.vendortest_code_id
//
//AND vendortest_number_summary.ani = vendortest_number.source_number
//
//AND vendortest_number_summary.dnis = vendortest_number.test_number where vendortest_number_summary.vendortest_code_id =  {$id}";
//        $result = $this->query($sql);
//        return $result;
    }
    
    public function delete_code_cmd($id) {
//        $sql = "select
//vendortest_project.project_name, vendortest_project.trunk, vendortest_code.code_name
//from vendortest_code
//join vendortest_project
//on vendortest_code.vendortest_project_id = vendortest_project.id
//where vendortest_code.id = {$id}";
//        $result = $this->query($sql);
//        return $result;
    }
    
    public function delete_code($id) {
//        $sql = "delete from vendortest_code where id = {$id};
//delete from vendortest_number where vendortest_code_id = {$id};";
//        $this->query($sql);
    }
    
    public function check_code($project_id, $code_name) {
//        $sql = "select count(*) as c from vendortest_code where vendortest_project_id = {$project_id} and code_name = '{$code_name}';";
//        $result = $this->query($sql);
//        return $result[0][0]['c'];
    }
    
    public function activecode($id, $type) {
//        $sql = "update vendortest_code set active = {$type} where id = {$id}";
//        $this->query($sql);
    }
    
    public function start_code_cmd($id) {
//        $sql = "select vendortest_project.project_name, vendortest_project.trunk,  vendortest_code.code_name
//                from
//                vendortest_code
//                join vendortest_project
//                on vendortest_code.vendortest_project_id = vendortest_project.id
//                where vendortest_code.vendortest_project_id = {$id} and vendortest_code.active = 0";
//        $result = $this->query($sql);
//        return $result;
    }
    
    public function check_project($key, $project_name) {
//        $sql = "select count(*) as c  from vendortest_project where vendortest_key = '{$key}' and project_name = '{$project_name}';";
//        $result = $this->query($sql);
//        return $result[0][0]['c'];
    }
    
    public function get_rate_table() {
        $sql = "select rate_table_id, name from rate_table";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_cdrnum($codes) {
        $str = '';
        foreach($codes as $code) {
            $str .= " and origination_destination_number like '{$code['code']}%'";
        }
        $date = date('Y-m-d');
        $sql = "select distinct origination_destination_number from client_cdr where true $str 
and answer_time_of_date::bigint  > 0 and  call_duration::bigint > 0 and time between '$date 00:00:00' and '$date 23:59:59'
limit 10";
        $result = $this->query($sql);
        return $result;
    }
    
    public function find_all_code($code_name) {
        $sql = "select distinct code from rate where code_name = '{$code_name}'";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $val) {
            array_push($arr, $val[0]);
        }
        return $arr;
    }
    
    public function project_counts() {
//        $sql = "select count(*) as c from vendortest_project where order_type is null";
//        $result = $this->query($sql);
//        return $result[0][0]['c'];
    }
    
    public function get_summary($id) {
//        $sql = <<<EOT
//        SELECT
//
//        vendortest_project_summary.vendortest_project_id as id,
//        vendortest_project_summary.start_epoch,
//        vendortest_project_summary.end_epoch,
//        vendortest_code.code_name,
//        vendortest_project_summary.acd_count AS call_count,
//        case vendortest_project_summary.acd_count when 0 then 0 else vendortest_project_summary.fas_count/vendortest_project_summary.acd_count end AS fas,
//        case vendortest_project_summary.asr_count when 0 then 0 else vendortest_project_summary.asr/vendortest_project_summary.asr_count end AS asr,
//        vendortest_project_summary.pdd,
//        vendortest_code.id AS code_id
//
//        FROM vendortest_project_summary
//
//        INNER JOIN vendortest_project
//
//        ON vendortest_project_summary.vendortest_project_id = vendortest_project.id
//
//        INNER JOIN vendortest_code ON vendortest_project_summary.vendortest_project_id = vendortest_code.vendortest_project_id
//
//        WHERE vendortest_project_summary.vendortest_project_id = {$id}
//EOT;
//        $results = $this->query($sql);
//        return $results;
    }
    
    public function check_summary_count($id) 
    {
//        $sql = "SELECT count(*) as c FROM vendortest_project INNER JOIN vendortest_project_summary ON vendortest_project.id = vendortest_project_summary.vendortest_project_id
//WHERE vendortest_project.id = {$id}";
//        $results = $this->query($sql);
//        return $results[0][0]['c'];
    }
    
    public function get_trunk_id($id) {
//        $sql = "select trunk from vendortest_project where id = $id";
//        $results = $this->query($sql);
//        return $results[0][0]['trunk'];
        
    }
    
    
    public function get_test_numbers($id) {
//        $sql = "SELECT vendortest_number.id,
//vendortest_number.test_number, vendortest_number.source_number, vendortest_number.call_time FROM vendortest_number
//INNER JOIN vendortest_code ON vendortest_number.vendortest_code_id = vendortest_code.id
//WHERE vendortest_project_id = $id";
//        $result = $this->query($sql);
//        return $result;
    }
    
    public function insert_pcap_path($number_id, $file_path) {
        $sql = "UPDATE vendortest_number SET sip_capture_path = '{$file_path}' WHERE id = {$number_id}";
        $this->query($sql);
    }
    
    public function get_code_name_item($number) {
        $sql = "SELECT name FROM code WHERE 
code_deck_id = (SELECT code_deck_id FROM code_deck WHERE client_id = 0)
AND
code::prefix_range @> PREFIX_RANGE '{$number}'  ORDER BY code DESC LIMIT 1";
        $result = $this->query($sql);
        if(empty($result))
            return 'undefined';
        else
            return $result[0][0]['name'];
    }
    
}

?>
