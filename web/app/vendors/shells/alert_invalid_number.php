<?php

class AlertInvalidNumberShell extends Shell
{

    var $uses = array('InvalidNumber');

    function main()
    {
        //App::import('Vendor', 'nmail/phpmailer');
        $rule_id = isset($this->args[0]) ? (int) $this->args[0] : "";
        if (!$rule_id)
        {
            return false;
        }
        $rule_info_item = $this->InvalidNumber->find(array('id' => $rule_id));

        $now_time = date("Y-m-d H:i:s");
        $execution_schedule = $rule_info_item['InvalidNumber']['execution_schedule'];

        if (!$execution_schedule)
        {
            return false;
        }
        $time = $rule_info_item['InvalidNumber']['sample_size'] * 60;
        $start_time = date("Y-m-d H:i:s", strtotime($now_time) - $time * 2);
        $end_time = date("Y-m-d H:i:s", strtotime($now_time) - $time);
//        $start_time = '2014-10-16 08:31:01';
//        $end_time = '2014-10-16 08:32:01';
        //$trunk_type = $rule_info_item['InvalidNumber']['trunk_type'];
        $ingress_id = $rule_info_item['InvalidNumber']['ingress_id'];
        
        $error_msg = array(
            1=>'404',
            2=>'480',
            3=>'503',
            4=>'400'
        );
        
        $cause_code_criteria = key_exists($rule_info_item['InvalidNumber']['cause_code_criteria'],$error_msg)?$error_msg[$rule_info_item['InvalidNumber']['cause_code_criteria']]:$error_msg[1];
        
        $error_flg = "";
        //$all_trunk = $rule_info_item['InvalidNumber']['all_trunk'];
        //$where_time = "WHERE time > '{$start_time}' AND time <=  '{$end_time}' ";
        $where_time = "WHERE time  between  '{$start_time}'   and  '{$end_time}'   ";
        
        $group = "GROUP BY ingress_id,routing_digits";
        $group_field = "ingress_id,count(*) as count,routing_digits";
        $where_trunk = " AND ingress_id in ($ingress_id) and binary_value_of_release_cause_from_protocol_stack ilike '{$cause_code_criteria}%' ";
           
   
        

        $sql_2 = "SELECT {$group_field} FROM client_cdr  {$where_time}  {$where_trunk} "
                . "{$group}";
        file_put_contents('/tmp/invalid_number.log', "\r\n\r\n" . date('Y-m-d H:i:s') . " Start Script \r\n" . $sql_2 . "\r\n" , FILE_APPEND);


        $first_data = $this->InvalidNumber->query($sql_2);
        
        
        $block_by = $rule_info_item['InvalidNumber']['rule_name'];
        $create_time = date("Y-m-d H:i:s");
        $flag = false;
        if(!empty($first_data)){
            $second_data = array();
            $block_log_id = 0;
            foreach ($first_data as $first_data_item)
            {
                $first_data_item = $first_data_item[0];
                if($first_data_item['count'] < $rule_info_item['InvalidNumber']['threshold']){
                    continue;
                }
                
                $res = $this->InvalidNumber->query("select count(*) from resource_block where ingress_res_id = '{$first_data_item['ingress_id']}' and digit = '{$first_data_item['routing_digits']}' ") ;
                //var_dump($res);
                if($res[0][0]['count'] != 0){
                    continue;
                }
                
                $flag = true;
                if(empty($block_log_id)){
                     $log_sql = "INSERT INTO block_log (block_by,type) "
                        . "VALUES('{$block_by}',1) "
                        . "RETURNING log_id";
                    $block_log_arr = $this->InvalidNumber->query($log_sql, false);
                    $fle = print_r($block_log_arr, true);
                    file_put_contents('/tmp/invalid_number.log', $fle . "\r\n\r\n", FILE_APPEND);
                    $block_log_id = intval($block_log_arr[0][0]['log_id']);
                }
                $sql = "INSERT INTO resource_block (ingress_res_id,digit,create_time,block_log_id) VALUES('{$first_data_item['ingress_id']}','{$first_data_item['routing_digits']}','{$create_time}',$block_log_id);";
                $this->InvalidNumber->query($sql);
            }
        }
        
        if(!$flag){
            file_put_contents('/tmp/invalid_number.log', date('Y-m-d H:i:s') . " End Script   \r\n" , FILE_APPEND);
        }else{
             file_put_contents('/tmp/invalid_number.log', date('Y-m-d H:i:s') . " End Script block_log_id = $block_log_id  \r\n" , FILE_APPEND);
        }
    }

}
