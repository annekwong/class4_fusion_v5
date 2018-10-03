<?php

class Cdrmatch extends AppModel {
    var $name = 'Cdrmatch';
    var $useTable = FALSE;
    
    public function getcarriers() {
        $result = $this->query("select distinct 
                                    client.client_id as id, client.name 
                                    from client
                                    inner join resource on client.client_id = resource.client_id 
                                    where resource.egress = true or resource.ingress = true
                                    order by client.name");
        return $result;
    }
    
    public function getcarrier($id) {
        $result = $this->query("select name from client where client_id = {$id}");
        return $result;
    }
    
    public function getcarriertype($type) {
        $sql = "select distinct 
                                    client.client_id as id, client.name 
                                    from client
                                    inner join resource on client.client_id = resource.client_id 
                                    where resource.{$type} = true
                                    order by client.name";
        $result = $this->query($sql);
        return $result;
    }
    
    public function compare_vendor($starttime, $endtime, $gmt, $carrier, $uni_cdr_file) {
        $today_date = date("Ymd");
        $sql = <<<EOT
        COPY(
select 

case answer_time_of_date when '0' then null else to_timestamp(substring(answer_time_of_date from 1 for 10) ::bigint)  

end as time,

call_duration as duration,egress_cost as cost,egress_rate as rate,termination_destination_number  as dnis,term_code_name as code_name

from client_cdr{$today_date} where time between '{$starttime} {$gmt}' and '{$endtime} {$gmt}' 

and egress_client_id='{$carrier}' and call_duration::integer > 0 order by time asc ) TO '{$uni_cdr_file}' DELIMITER  ',' CSV HEADER
    
EOT;
        $this->query($sql);
    }
    
    public function compare_client($starttime, $endtime, $gmt, $carrier, $uni_cdr_file) {
        $today_date = date("Ymd");
        $sql = <<<EOT
        COPY(
select 

case answer_time_of_date when '0' then null else to_timestamp(substring(answer_time_of_date from 1 for 10) ::bigint) end as 

time,call_duration as duration,ingress_client_cost as cost,ingress_client_rate as rate,origination_destination_number as dnis, orig_code_name as code_name 

from client_cdr{$today_date} where time between '{$starttime} {$gmt}' and '{$endtime} {$gmt}' 

and ingress_client_id='{$carrier}' and call_duration::integer > 0  order by time asc ) TO '{$uni_cdr_file}' DELIMITER  ',' CSV HEADER
    
EOT;
        $this->query($sql);
    }
    
    
    public function show_list($pageSize, $offset) {
//        $sql = <<<EOT
//     SELECT
//
//id,create_time,status,finish_time,format,is_rate,duration_diff,calltime_diff,diff_report_file,diff_cdr_file
//
//FROM
//
//cdr_compare ORDER BY create_time DESC LIMIT $pageSize OFFSET $offset
//
//EOT;
//        return $this->query($sql);
        return array();
    }
    
    public function show_list_count() {
//        $sql = <<<EOT
//     SELECT
//
//count(*)
//
//FROM
//
//cdr_compare
//
//EOT;
//        $result = $this->query($sql);
//        return $result[0][0]['count'];
        return 0;
    }
    
}


?>
