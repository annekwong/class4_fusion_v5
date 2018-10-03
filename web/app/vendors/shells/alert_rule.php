<?php

class AlertRuleShell extends Shell
{

    var $uses = array('AlertRules');
    var $rule_name = "";

    function main()
    {
        App::import('Vendor', 'nmail/phpmailer');
        $rule_id = isset($this->args[0]) ? (int) $this->args[0] : "";
        if (!$rule_id)
        {
            return false;
        }
        $rule_info_item = $this->AlertRules->find(array('id' => $rule_id));

        $now_time = date("Y-m-d H:i:s");
        $execution_schedule = $rule_info_item['AlertRules']['execution_schedule'];
        $this->rule_name = $rule_info_item['AlertRules']['rule_name'];
        if (!$execution_schedule)
        {
            return false;
        }
        $time = $rule_info_item['AlertRules']['sample_size'] * 60;
//        $start_time = date("Y-m-d H:i:s", strtotime($now_time) - $time * 2);
//        $end_time = date("Y-m-d H:i:s", strtotime($now_time) - $time);
        $start_time = date("Y-m-d H:i:s", strtotime($now_time) - $time);
        $end_time = date("Y-m-d H:i:s", strtotime($now_time));
//        $start_time = '2015-01-11 00:00:00';
//        $end_time = '2015-01-12 00:00:00';
        $trunk_type = $rule_info_item['AlertRules']['trunk_type'];
        $res_id = $rule_info_item['AlertRules']['res_id'];

        $error_flg = "";
        $all_trunk = $rule_info_item['AlertRules']['all_trunk'];
        $where_time = "WHERE time > '{$start_time}' AND time <=  '{$end_time}' ";
        switch ($trunk_type)
        {
            case '1': //ingress 

                $group = "GROUP BY ingress_id,orig_code";
                $group_field = "ingress_id";
                $where_trunk = "";
                if ($all_trunk)
                {
                    $where_trunk .= " AND ingress_id is not null";
                }
                else
                {
                    $where_trunk .= " AND ingress_id in ($res_id)";
                }
                break;
            case '2'://egress 

                $group = "GROUP BY egress_id,term_code";
                $group_field = "egress_id";
                $where_trunk = "";
                if ($all_trunk)
                {
                    $where_trunk .= " AND egress_id is not null";
                }
                else
                {
                    $where_trunk .= " AND egress_id in ($res_id)";
                }
                break;
            default : $error_flg = 1;
        }
        if ($error_flg)
        {
            return false;
        }

        $include = $rule_info_item['AlertRules']['include'];
        $where_code = "";
        switch ($include)
        {
            case '1': //Specific Codes  

                $in_codes_arr = explode(',', $rule_info_item['AlertRules']['in_codes']);
                foreach ($in_codes_arr as $key => $in_codes)
                {
                    $in_codes_arr[$key] = "'" . $in_codes . "'";
                }
                $in_codes_arr1 = implode(',', $in_codes_arr);

                if ($trunk_type == 1)
                {
                    $where_code .= " AND orig_code in ($in_codes_arr1) ";
                }
                else
                {
                    $where_code .= " AND term_code in ($in_codes_arr1) ";
                }
                break;
            case '2'://Specific Code Names 
                $where_code = "";
                $in_code_name_arr = explode(',', $rule_info_item['AlertRules']['in_code_name_id']);
                foreach ($in_code_name_arr as $key => $in_code_name)
                {
                    $in_code_name_arr[$key] = "'" . $in_code_name . "'";
                }
                $in_code_name_arr1 = implode(',', $in_code_name_arr);

                if ($trunk_type == 1)
                {
                    $where_code .= " AND orig_code_name in ($in_code_name_arr1) ";
                }
                else
                {
                    $where_code .= " AND term_code_name in ($in_code_name_arr1) ";
                }
                break;
        }

        $exclude = $rule_info_item['AlertRules']['exclude'];
        switch ($exclude)
        {
            case '1': //Specific Codes  
                $ex_codes_arr = explode(',', $rule_info_item['AlertRules']['ex_codes']);
                foreach ($ex_codes_arr as $key => $ex_codes)
                {
                    $ex_codes_arr[$key] = "'" . $ex_codes . "'";
                }
                $ex_codes_arr1 = implode(',', $ex_codes_arr);

                if ($trunk_type == 1)
                {
                    $where_code .= " AND orig_code not in ($ex_codes_arr1) ";
                }
                else
                {
                    $where_code .= " AND term_code not in ($ex_codes_arr1) ";
                }
                break;
            case '2'://Specific Code Names  

                $ex_code_name_arr = explode(',', $rule_info_item['AlertRules']['ex_code_name_id']);
                foreach ($ex_code_name_arr as $key => $ex_code_name)
                {
                    $ex_code_name_arr[$key] = "'" . $ex_code_name . "'";
                }
                $ex_code_name_arr1 = implode(',', $ex_code_name_arr);

                if ($trunk_type == 1)
                {
                    $where_code .= " AND orig_code_name not in ($ex_code_name_arr1) ";
                }
                else
                {
                    $where_code .= " AND term_code_name not in ($ex_code_name_arr1) ";
                }
                break;
        }

        $sql = "SELECT count(*) as sum FROM client_cdr {$where_time}  {$where_trunk}  {$where_code};";
        $count = $this->AlertRules->query($sql);
        //echo $count."/r/n";
        if ($count[0][0]['sum'] <= $rule_info_item['AlertRules']['min_call_attempt'])
        {
            file_put_contents('/tmp/run.log', date('Y-m-d H:i:s') . "\r\n" . $sql . "\r\n" . "({$count[0][0]['sum']} <= {$rule_info_item['AlertRules']['min_call_attempt']})\r\n\r\n", FILE_APPEND);
            return false;
        }
        file_put_contents('/tmp/run.log', date('Y-m-d H:i:s') . "\r\n" . $sql . "\r\n" . "({$count[0][0]['sum']} >= {$rule_info_item['AlertRules']['min_call_attempt']})\r\n", FILE_APPEND);
//        $where_1 = "";
//        if (strcmp('1', $rule_info_item['AlertRules']['acd']))
//        {
//            $where_1 = " AND t2.acd1 {$rule_info_item['AlertRules']['acd']} {$rule_info_item['AlertRules']['acd_value']} ";
//        }
//        if (strcmp('1', $rule_info_item['AlertRules']['asr']))
//        {
//            $where_1 = " AND t2.asr1 {$rule_info_item['AlertRules']['asr']} {$rule_info_item['AlertRules']['asr_value']} ";
//        }
//        if (strcmp('1', $rule_info_item['AlertRules']['abr']))
//        {
//            $where_1 = " AND t2.abr1 {$rule_info_item['AlertRules']['abr']} {$rule_info_item['AlertRules']['abr_value']} ";
//        }
//        if (strcmp('1', $rule_info_item['AlertRules']['pdd']))
//        {
//            $where_1 = " AND t2.pdd1 {$rule_info_item['AlertRules']['pdd']} {$rule_info_item['AlertRules']['pdd_value']} ";
//        }
//        if (strcmp('1', $rule_info_item['AlertRules']['profitability']))
//        {
//            $where_1 = " AND t2.profitability1 {$rule_info_item['AlertRules']['profitability']} {$rule_info_item['AlertRules']['profitability_value']} ";
//        }
//        $field = "CASE not_zero_calls WHEN '0' THEN null ELSE round(duration/not_zero_calls/60,2) END as acd1,"
//                . "CASE total_calls WHEN '0' THEN null ELSE round(not_zero_calls/total_calls*100,2) END as abr1,"
//                . "CASE (busy_calls + cancel_calls + not_zero_calls) WHEN '0' THEN null ELSE round((not_zero_calls/(busy_calls + cancel_calls + not_zero_calls)) * 100, 2) END as asr1,"
//                . "CASE not_zero_calls WHEN '0' THEN null ELSE round(pdd/not_zero_calls) END as pdd1,"
//                . "case ingress_client_cost_total WHEN '0' THEN null ELSE (ingress_client_cost_total-egress_cost_total)/ingress_client_cost_total*100 END as profitability1,"
//                . "{$group_field}";
//        $sql_1 = "SELECT t2.acd1 as acd,t2.abr1 as abr,t2.asr1 as asr,t2.pdd1 as pdd,"
//                . "t2.profitability1 as profitability,"
//                . "{$group_field} FROM("
//                . "SELECT $field FROM ("
//                . "SELECT sum(call_duration) as duration,"
//                . "count(case when call_duration > 0 then 1 else null end) as not_zero_calls,"
//                . "count(case when binary_value_of_release_cause_from_protocol_stack like '486%' then 1 else null end) as busy_calls,"
//                . "count(*) as total_calls,"
//                . "count( case when binary_value_of_release_cause_from_protocol_stack like '487%' then 1 else null end ) as cancel_calls,"
//                . "sum(case when call_duration > 0 then pdd else 0 end) as pdd,sum(ingress_client_cost) as ingress_client_cost_total,
//                sum(egress_cost) as egress_cost_total,{$group_field} FROM client_cdr  {$where_time}  {$where_trunk}  {$where_code}"
//                . "{$group}  )as t1)as t2 WHERE 1=1 {$where_1} ";
//        $data = $this->AlertRules->query($sql_1, false);



        $sql_2 = "SELECT sum(call_duration) as duration,"
                . "count(case when call_duration > 0 then 1 else null end) as not_zero_calls,"
                . "count(case when binary_value_of_release_cause_from_protocol_stack like '486%' then 1 else null end) as busy_calls,"
                . "count(*) as total_calls,"
                . "count( case when binary_value_of_release_cause_from_protocol_stack like '487%' then 1 else null end ) as cancel_calls,"
                . "sum(case when call_duration > 0 then pdd else 0 end) as pdd,
                    sum(ingress_client_cost) as ingress_client_cost_total,
                sum(egress_cost) as egress_cost_total,{$group_field} FROM client_cdr  {$where_time}  {$where_trunk}  {$where_code}"
                . "{$group}";


        $first_data = $this->AlertRules->query($sql_2);

        $disable_scope = $rule_info_item['AlertRules']['disable_scope'];
        $create_time = date("Y-m-d H:i:s");
        var_dump($first_data);
        echo "----------------------------------\n";
        foreach ($first_data as $first_data_items)
        {
            $extra_filed_arr = array(
                'problem_egress_carrier' => '',
                'problem_egress_trunk' => '',
                'problem_ingress_carrier' => '',
                'problem_ingress_trunk' => '',
                'time_period' => $start_time . "--" . $end_time,
                'problem_destination_list_with_stat' => '',
            );

            $second_data = array();
            foreach ($first_data_items as $first_data_item)
            {
                $duration = intval($first_data_item['duration']);
                $not_zero_calls = intval($first_data_item['not_zero_calls']);
                $busy_calls = intval($first_data_item['busy_calls']);
                $total_calls = intval($first_data_item['total_calls']);
                $cancel_calls = intval($first_data_item['cancel_calls']);
                $ingress_client_cost_total = $first_data_item['ingress_client_cost_total'];
                $egress_cost_total = $first_data_item['egress_cost_total'];
                $pdd = $first_data_item['pdd'];

                $extra_filed_arr['problem_destination_list_with_stat'] = "Call Attempt:{$count[0][0]['sum']};Non Zero Call:{$not_zero_calls}";

                $second_data['acd'] = !empty($not_zero_calls) ? round($duration / $not_zero_calls / 60, 2) : 0;

                $second_data['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;

                $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                $second_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;

                $second_data['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;

                $second_data['profitability'] = !empty($ingress_client_cost_total) ? ($ingress_client_cost_total - $egress_cost_total) / $ingress_client_cost_total * 100 : 0;

                $second_data['revenue'] = $ingress_client_cost_total - $egress_cost_total;

                $extra_filed_arr['problem_destination_list_with_stat'] .= "ASR:{$second_data['asr']};ACD:{$second_data['acd']}";
            }

            if (strcmp('1', $rule_info_item['AlertRules']['revenue']))
            {
                if (!$this->judge_num($second_data['revenue'], $rule_info_item['AlertRules']['revenue_value'], $rule_info_item['AlertRules']['revenue']))
                {
                    continue;
                }
            }

            if (strcmp('1', $rule_info_item['AlertRules']['acd']))
            {
                if (!$this->judge_num($second_data['acd'], $rule_info_item['AlertRules']['acd_value'], $rule_info_item['AlertRules']['acd']))
                {
                    continue;
                }
            }
            if (strcmp('1', $rule_info_item['AlertRules']['asr']))
            {
                if (!$this->judge_num($second_data['asr'], $rule_info_item['AlertRules']['asr_value'], $rule_info_item['AlertRules']['asr']))
                {
                    continue;
                }
            }
            if (strcmp('1', $rule_info_item['AlertRules']['abr']))
            {
                if (!$this->judge_num($second_data['abr'], $rule_info_item['AlertRules']['abr_value'], $rule_info_item['AlertRules']['abr']))
                {
                    continue;
                }
            }
            if (strcmp('1', $rule_info_item['AlertRules']['pdd']))
            {
                if (!$this->judge_num($second_data['pdd'], $rule_info_item['AlertRules']['pdd_value'], $rule_info_item['AlertRules']['pdd']))
                {
                    continue;
                }
            }
            if (strcmp('1', $rule_info_item['AlertRules']['profitability']))
            {
                if (!$this->judge_num($second_data['profitability'], $rule_info_item['AlertRules']['profitability_value'], $rule_info_item['AlertRules']['profitability']))
                {
                    continue;
                }
            }

            if (strcmp($group_field, 'ingress_id'))
            {
                $code_filed = "term_code";
                $code_name_filed = "term_code_name";
                $trunk_id = $first_data_items[0]['egress_id'];
                $where_2 = " AND egress_id = {$first_data_items[0]['egress_id']}";

                $trunk_alias = $this->AlertRules->query("SELECT client_id,alias FROM resource WHERE resource_id = {$first_data_items[0]['egress_id']}");
                if ($trunk_alias)
                {
                    $extra_filed_arr['problem_egress_trunk'] = $trunk_alias[0][0]['alias'];
                    $client_name = $this->AlertRules->query("SELECT name FROM client WHERE client_id = {$trunk_alias[0][0]['client_id']}");
                    $extra_filed_arr['problem_egress_carrier'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "";
                }
            }
            else
            {
                $code_filed = "orig_code";
                $code_name_filed = "orig_code_name";
                $trunk_id = $first_data_items[0]['ingress_id'];
                $where_2 = " AND ingress_id = {$first_data_items[0]['ingress_id']}";

                $trunk_alias = $this->AlertRules->query("SELECT client_id,alias FROM resource WHERE resource_id = {$first_data_items[0]['ingress_id']}");
                if ($trunk_alias)
                {
                    $extra_filed_arr['problem_ingress_trunk'] = $trunk_alias[0][0]['alias'];
                    $client_name = $this->AlertRules->query("SELECT name FROM client WHERE client_id = {$trunk_alias[0][0]['client_id']}");
                    $extra_filed_arr['problem_ingress_carrier'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "";
                }
            }

            $code_by_trunk_sql = "SELECT DISTINCT({$code_filed}) as code FROM client_cdr {$where_time} {$where_code} {$where_2}";
            $code_data = $this->AlertRules->query($code_by_trunk_sql, false);
            $code_name_by_trunk_sql = "SELECT DISTINCT({$code_name_filed}) as code_name FROM client_cdr {$where_time} {$where_code} {$where_2}";
            $code_name_data = $this->AlertRules->query($code_name_by_trunk_sql, false);
            echo $first_data_items[0]['ingress_id']."\n";
            echo $code_by_trunk_sql."\n";
            var_dump($code_data);
            foreach ($code_data as $code_data_item)
            {
                $code_detail_arr[] = $code_data_item[0]['code'];
            }
            $code_detail = implode(",", $code_detail_arr);





            $step3_type = $rule_info_item['AlertRules']['step3_type'];

            $block_by = $rule_info_item['AlertRules']['rule_name'];
            $asr = $second_data['asr'] ? $second_data['asr'] : 0;
            $abr = $second_data['abr'] ? $second_data['abr'] : 0;
            $acd = $second_data['acd'] ? $second_data['acd'] : 0;
            $pdd = $second_data['pdd'] ? $second_data['pdd'] : 0;
            $margin = $second_data['profitability'] ? $second_data['profitability'] : 0;
            echo "fasdfsdfsdfsd\n";
            if (!strcmp($step3_type, '2'))
            {
                echo "block\n";
                //            插入block_log表
                $re_enable_time_sql = '';
                $re_enable_time_sql_value = '';
                if (!strcmp('2', $rule_info_item['AlertRules']['auto_enable_type']) && $rule_info_item['AlertRules']['auto_enable'])
                {
                    $temp = intval($rule_info_item['AlertRules']['auto_enable']) * 60;
                    $re_enable_time = date("Y-m-d H:i:s", time() + $temp);
                    $re_enable_time_sql = ',re_enable_time';
                    $re_enable_time_sql_value = ",'" . $re_enable_time . "'";
                }

                $log_sql = "INSERT INTO block_log (block_by,asr,abr,acd,pdd,margin,code_detail" . $re_enable_time_sql . ") "
                        . "VALUES('{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}'" . $re_enable_time_sql_value . ") "
                        . "RETURNING log_id";
                $block_log_arr = $this->AlertRules->query($log_sql, false);
                $fle = print_r($block_log_arr, true);
                file_put_contents('/tmp/run.log', $fle . "\r\n\r\n", FILE_APPEND);
                $block_log_id = intval($block_log_arr[0][0]['log_id']);
                switch ($disable_scope)
                {
                    case '1'://Disable Entire Trunk 
                        $sql = "";
                        if (strcmp($group_field, 'ingress_id'))
                        {
                            $client = $this->AlertRules->query("SELECT client_id FROM resource where resource_id = {$first_data_items[0]['egress_id']}");
                            $client_id = $client[0][0]['client_id'];
                            foreach ($code_data as $code_items)
                            {
//                                if (empty($code_items[0]['code']))
//                                {
//                                    continue;
//                                }
                                $exist_sql = "SELECT count(*) as sum FROM resource_block WHERE ingress_res_id = {$first_data_items[0]['egress_id']} AND digit = '{$code_items[0]['code']}'";
                                $exist = $this->AlertRules->query($exist_sql);
                                if ($exist[0][0]['sum'])
                                {
                                    continue;
                                }
                                $sql .= "INSERT INTO resource_block (egress_client_id,engress_res_id,digit,create_time,block_log_id,action_type,update_by) "
                                        . "VALUES($client_id,'{$first_data_items[0]['egress_id']}','{$code_items[0]['code']}','{$create_time}',$block_log_id,1,'Rule[{$this->rule_name}]');";
                            }
                            if (empty($sql))
                            {
                                $error_flg = 1;
                            }
                        }
                        else
                        {
                            $client = $this->AlertRules->query("SELECT client_id FROM resource where resource_id = {$first_data_items[0]['ingress_id']}");
                            $client_id = $client[0][0]['client_id'];
                            foreach ($code_data as $code_items)
                            {
//                                if (empty($code_items[0]['code']))
//                                {
//                                    continue;
//                                }
                                $exist_sql = "SELECT count(*) as sum FROM resource_block WHERE ingress_res_id = {$first_data_items[0]['ingress_id']} AND digit = '{$code_items[0]['code']}'";
                                $exist = $this->AlertRules->query($exist_sql);
                                if ($exist[0][0]['sum'])
                                {
                                    continue;
                                }
                                $sql .= "INSERT INTO resource_block (ingress_client_id,ingress_res_id,digit,create_time,block_log_id,action_type,update_by) "
                                        . "VALUES($client_id,'{$first_data_items[0]['ingress_id']}','{$code_items[0]['code']}','{$create_time}',$block_log_id,1,'Rule[{$this->rule_name}]');";
                            }
                            if (empty($sql))
                            {
                                $error_flg = 1;
                            }
                        }

                        break;
                    case '2'://Disable Specific Code 
                        $sql = $this->code_sql($code_data, $group_field, $first_data_items, $block_log_id);
                        if (empty($sql))
                        {
                            $error_flg = 1;
                        }
                        break;
                    case '3': //Disable Specific Code Name
                        $sql = "";
                        foreach ($code_name_data as $code_names)
                        {
                            $search_sql = "SELECT code FROM code WHERE name = '{$code_names[0]['code_name']}'";
                            $code_arr = $this->AlertRules->query($search_sql, false);
                            $sql .= $this->code_sql($code_arr, $group_field, $first_data_items, $block_log_id);
                        }
                        $code_detail_arr = array();
                        foreach ($code_name_data as $code_name_data_item)
                        {
                            $code_detail_arr[] = $code_name_data_item[0]['code_name'];
                        }
                        $code_detail = implode(",", $code_detail_arr);
                        break;
                    default : $error_flg = 1;
                }
                if ($error_flg)
                {
                    $this->AlertRules->query("DELETE FROM block_log WHERE log_id = $block_log_id");
                    continue;
                }
                //block 
                $this->AlertRules->query($sql, false);
            }
            elseif (!strcmp($step3_type, '1'))
            {//发邮件 
//                增加 标签 {problem_egress_carrier}
//                {problem_egress_trunk}
//                {problem_ingress_carrier}
//                {problem_ingress_trunk}
//                {time_period}
//                {problem_destination_list_with_stat}
                echo "mail\n";
                switch ($rule_info_item['AlertRules']['trouble_ticket_sent_to'])
                {
                    case '1':  // Your Own NOC Email
                        $file_path = $this->create_file($trunk_id, $code_data, $code_name_data);

                        $sys_sql = "SELECT noc_email FROM system_parameter offset 0 limit 1";
                        $noc_email = $this->AlertRules->query($sys_sql, false);

                        $email = $noc_email[0][0]['noc_email'];

                        $mail_flg = $this->send_email($rule_info_item, $file_path, $email, $extra_filed_arr);
                        $fle = print_r($mail_flg, true);
                        file_put_contents('/tmp/run.log', "Own NOC " . $fle . "\r\n\r\n", FILE_APPEND);
                        if ($mail_flg)
                        {
//                            $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                            $this->AlertRules->query($log_sql, false);
                        }
                        break;
                    case '2':  //Partner’s NOC Email 
                        $res_sql = "SELECT client.noc_email,client.client_id FROM client as client inner join resource as resource ON client.client_id = resource.client_id  WHERE resource_id = $trunk_id";
                        $noc_email = $this->AlertRules->query($res_sql, false);
                        if ($noc_email[0][0]['noc_email'])
                        {
                            $file_path = $this->create_file($trunk_id, $code_data, $code_name_data);
                            $email = $noc_email[0][0]['noc_email'];
                            $mail_flg = $this->send_email($rule_info_item, $file_path, $email, $extra_filed_arr);
                            $fle = print_r($mail_flg, true);
                            file_put_contents('/tmp/run.log', "Partner’s NOC " . $fle . "\r\n", FILE_APPEND);
                            if ($mail_flg)
                            {
                                //保持记录到email_log
                                $subject = $rule_info_item['AlertRules']['trouble_ticket_subject'] . ";  " . implode(";", $extra_filed_arr);
                                $content = $rule_info_item['AlertRules']['trouble_ticket_content'];
                                $current_datetime = date("Y-m-d H:i:s");
                                $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content) values('$current_datetime','{$noc_email[0][0]['client_id']}', '{$noc_email[0][0]['noc_email']}', 22,0,0,'$subject','$content')";
                                $this->AlertRules->query($sql);

//                                $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                                $this->AlertRules->query($log_sql, false);
                            }
                        }
                        file_put_contents('/tmp/run.log', "\r\n", FILE_APPEND);
                        break;
                    case '3': //both 

                        $file_path1 = $this->create_file($trunk_id, $code_data, $code_name_data);
                        $sys_sql = "SELECT noc_email FROM system_parameter offset 0 limit 1";
                        $sys_email = $this->AlertRules->query($sys_sql, false);

                        $s_email = $sys_email[0][0]['noc_email'];

                        $mail_flg1 = $this->send_email($rule_info_item, $file_path1, $s_email, $extra_filed_arr);
                        $fle = print_r($mail_flg1, true);
                        file_put_contents('/tmp/run.log', "both NOC " . $fle . "\r\n", FILE_APPEND);
                        if ($mail_flg1)
                        {
//                            $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                            $this->AlertRules->query($log_sql, false);
                        }


                        $res_sql = "SELECT client.noc_email,client.client_id FROM client as client inner join resource as resource ON client.client_id = resource.client_id  WHERE resource_id = $trunk_id";
                        $noc_email = $this->AlertRules->query($res_sql, false);
                        if ($noc_email[0][0]['noc_email'])
                        {
                            $file_path = $this->create_file($trunk_id, $code_data, $code_name_data);
                            $email = $noc_email[0][0]['noc_email'];
                            $mail_flg = $this->send_email($rule_info_item, $file_path, $email, $extra_filed_arr);
                            $fle = print_r($mail_flg, true);
                            file_put_contents('/tmp/run.log', "BOTH NOC " . $fle . "\r\n", FILE_APPEND);
                            if ($mail_flg)
                            {
                                //保持记录到email_log
                                $subject = $rule_info_item['AlertRules']['trouble_ticket_subject'] . ";  " . implode(";", $extra_filed_arr);
                                $content = $rule_info_item['AlertRules']['trouble_ticket_content'];
                                $current_datetime = date("Y-m-d H:i:s");
                                $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content) values('$current_datetime','{$noc_email[0][0]['client_id']}', '{$noc_email[0][0]['noc_email']}', 22,0,0,'$subject','$content')";
                                $this->AlertRules->query($sql);

//                                $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                                $this->AlertRules->query($log_sql, false);
                            }
                        }
                        file_put_contents('/tmp/run.log', "\r\n", FILE_APPEND);
                        break;
                }
            }
        }

        //pr($data);
        //block

        /** Include PHPExcel */
//        foreach ($data as $data_item)
//        {
//            if (strcmp($group_field, 'ingress_id'))
//            {
//                $code_filed = "term_code";
//                $code_name_filed = "term_code_name";
//                $trunk_id = $data_item[0]['egress_id'];
//                $where_2 = " AND egress_id = {$data_item[0]['egress_id']}";
//            }
//            else
//            {
//                $code_filed = "orig_code";
//                $code_name_filed = "orig_code_name";
//                $trunk_id = $data_item[0]['ingress_id'];
//                $where_2 = " AND ingress_id = {$data_item[0]['ingress_id']}";
//            }
//
//            $code_by_trunk_sql = "SELECT DISTINCT({$code_filed}) as code FROM client_cdr {$where_time} {$where_code} {$where_2}";
//            $code_data = $this->AlertRules->query($code_by_trunk_sql, false);
//            $code_name_by_trunk_sql = "SELECT DISTINCT({$code_name_filed}) as code_name FROM client_cdr {$where_time} {$where_code} {$where_2}";
//            $code_name_data = $this->AlertRules->query($code_name_by_trunk_sql, false);
//            switch ($disable_scope)
//            {
//                case '1'://Disable Entire Trunk 
//                    $sql = "";
//                    if (strcmp($group_field, 'ingress_id'))
//                    {
//                        foreach ($code_data as $code_items)
//                        {
//                            $sql .= "INSERT INTO resource_block (egress_res_id,digit,create_time) VALUES('{$data_item[0]['egress_id']}','{$code_items[0]['code']}','{$create_time}');";
//                        }
//                    }
//                    else
//                    {
//                        foreach ($code_data as $code_items)
//                        {
//                            $sql .= "INSERT INTO resource_block (ingress_res_id,digit,create_time) VALUES('{$data_item[0]['ingress_id']}','{$code_items[0]['code']}','{$create_time}');";
//                        }
//                    }
//
//                    break;
//                case '2'://Disable Specific Code 
//                    $sql = $this->code_sql($code_data, $group_field, $data_item);
//
//                    break;
//                case '3': //Disable Specific Code Name
//                    $sql = "";
//                    foreach ($code_name_data as $code_names)
//                    {
//                        $search_sql = "SELECT code FROM code WHERE name = '{$code_names[0]['code_name']}'";
//                        $code_arr = $this->AlertRules->query($search_sql, false);
//                        $sql .= $this->code_sql($code_arr, $group_field, $data_item);
//                    }
//                    break;
//                default : $error_flg = 1;
//            }
//            if ($error_flg)
//            {
//                continue;
//            }
//            //block 
//            $this->AlertRules->query($sql, false);
////            插入block_log表
//            $block_by = $rule_info_item['AlertRules']['rule_name'];
//            $asr = $data_item[0]['asr'] ? $data_item[0]['asr'] : 0;
//            $abr = $data_item[0]['abr'] ? $data_item[0]['abr'] : 0;
//            $acd = $data_item[0]['acd'] ? $data_item[0]['acd'] : 0;
//            $pdd = $data_item[0]['pdd'] ? $data_item[0]['pdd'] : 0;
//            $margin = $data_item[0]['profitability'] ? $data_item[0]['profitability'] : 0;
//            $code_detail = "";
//            $log_sql = "INSERT INTO block_log (block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//            $this->AlertRules->query($log_sql, false);
//
//            $step3_type = $rule_info_item['AlertRules']['step3_type'];
//            if (strcmp($step3_type, '2'))
//            {//发邮件 
//                switch ($rule_info_item['AlertRules']['trouble_ticket_sent_to'])
//                {
//                    case '1':  // Your Own NOC Email
//                        $file_path = $this->create_file($trunk_id, $code_data, $code_name_data);
//
//                        $sys_sql = "SELECT noc_email FROM system_parameter offset 0 limit 1";
//                        $noc_email = $this->AlertRules->query($sys_sql, false);
//
//                        $email = $noc_email[0][0]['noc_email'];
//
//                        $mail_flg = $this->send_email($rule_info_item, $file_path, $email);
//                        if ($mail_flg)
//                        {
//                            $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                            $this->AlertRules->query($log_sql, false);
//                        }
//
//                        break;
//                    case '2':  //Partner’s NOC Email 
//                        $res_sql = "SELECT noc_email,client_id FROM client WHERE client_id IN ("
//                                . "SELECT client_id  FROM resource WHERE resource_id IN ({$res_id}) GROUP BY client_id )";
//                        $noc_email_arr = $this->AlertRules->query($res_sql, false);
//                        foreach ($noc_email_arr as $noc_email)
//                        {
//                            if ($noc_email[0]['noc_email'])
//                            {
//                                $file_path = $this->create_file($noc_email[0]['client_id']);
//
//                                $email = $noc_email[0][0]['noc_email'];
//
//                                $mail_flg = $this->send_email($rule_info_item, $file_path, $email);
//                                if ($mail_flg)
//                                {
//                                    $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                                    $this->AlertRules->query($log_sql, false);
//                                }
//                            }
//                        }
//
//                        break;
//                    case '3': //both 
//
//                        $file_path1 = $this->create_file($trunk_id, $code_data, $code_name_data);
//
//                        $sys_sql = "SELECT noc_email FROM system_parameter offset 0 limit 1";
//                        $sys_email = $this->AlertRules->query($sys_sql, false);
//
//                        $s_email = $sys_email[0][0]['noc_email'];
//
//                        $mail_flg1 = $this->send_email($rule_info_item, $file_path1, $s_email);
//                        if ($mail_flg1)
//                        {
//                            $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0][0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                            $this->AlertRules->query($log_sql, false);
//                        }
//
//
//                        $res_sql = "SELECT noc_email,client_id FROM client WHERE client_id IN ("
//                                . "SELECT client_id  FROM resource WHERE resource_id IN ({$res_id}) GROUP BY client_id )";
//                        $noc_email_arr = $this->AlertRules->query($res_sql, false);
//                        foreach ($noc_email_arr as $noc_email)
//                        {
//                            if ($noc_email[0]['noc_email'])
//                            {
//                                $file_path = $this->create_file($noc_email[0]['client_id']);
//
//                                $email = $noc_email[0][0]['noc_email'];
//
//                                $mail_flg = $this->send_email($rule_info_item, $file_path, $email);
//                                if ($mail_flg)
//                                {
//                                    $log_sql = "INSERT INTO block_trouble_ticket (email,block_by,asr,abr,acd,pdd,margin,code_detail) VALUES('{$noc_email[0]['noc_email']}','{$block_by}','{$asr}','{$abr}','{$acd}','{$pdd}','{$margin}','{$code_detail}')";
//                                    $this->AlertRules->query($log_sql, false);
//                                }
//                            }
//                        }
//
//                        break;
//                }
//            }
//        }
    }

    public function send_email($rule_info_item, $file_path, $email, $extra_filed_arr)
    {
        $send_mailer_id = $rule_info_item['AlertRules']['trouble_ticket_sent_from'];
        if ($send_mailer_id) {
            $email_info = $this->AlertRules->query('SELECT email as "from", smtp_host, smtp_port,username,loginemail, password, name, secure FROM mail_sender WHERE id =' . $send_mailer_id);
        } else {
            $email_info = $this->AlertRules->query('select fromemail as "from", smtphost as "smtp_host", smtpport as "smtp_port", emailusername as "username", loginemail, emailpassword as "password", emailname as "name", smtp_secure as "secure" from system_parameter limit 1');
        }
        
        
        if (empty($email))
        {
            echo "1\r\n";
            return false;
        }
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false')
        {
            $mailer->IsMail();
        }
        else
        {
            $mailer->IsSMTP();
        }

        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        switch ($email_info[0][0]['secure'])
        {
            case 1:
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
//                    case 3:
//                        $mailer->AuthType = 'NTLM';
//                        $mailer->Realm = $email_info[0][0]['realm'];
//                        $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        $mailer->IsHTML(true);
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtp_host'];
        $mailer->Port = intval($email_info[0][0]['smtp_port']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $subject = $rule_info_item['AlertRules']['trouble_ticket_subject'] . ";  " . implode(";", $extra_filed_arr);

        $mailer->Subject = $subject;
        $mailer->Body = $rule_info_item['AlertRules']['trouble_ticket_content'];
        $mailer->AddAttachment($file_path);
        $mailer->AddAddress($email);

        if ($mailer->Send())
        {
            unset($mailer);
            return true;
        }
        $mailer_error = $mailer->ErrorInfo;
        var_dump($mailer_error);
        unset($mailer);
        return false;
    }

    public function code_sql($code_data, $group_field, $data_item, $block_log_id)
    {
        $create_time = date("Y-m-d H:i:s");
        $sql_value = "";
        if (isset($code_data))
        {
            foreach ($code_data as $code)
            {
//                if (empty($code[0]['code']))
//                {
//                    continue;
//                }
                if (strcmp($group_field, 'ingress_id'))
                {
                    $exist_sql = "SELECT count(*) as sum FROM resource_block WHERE ingress_res_id = {$data_item[0]['egress_id']} AND digit = '{$code[0]['code']}'";
                    $exist = $this->AlertRules->query($exist_sql);
                    if ($exist[0][0]['sum'])
                    {
                        continue;
                    }
                    $client = $this->AlertRules->query("SELECT client_id FROM resource where resource_id = {$data_item[0]['egress_id']}");
                    $client_id = $client[0][0]['client_id'];
                    $sql_value_arr[] = "({$client_id},'{$data_item[0]['egress_id']}','{$code[0]['code']}','{$create_time}',{$block_log_id},1,'Rule[{$this->rule_name}]')";
                }
                else
                {
                    $exist_sql = "SELECT count(*) as sum FROM resource_block WHERE ingress_res_id = {$data_item[0]['ingress_id']} AND digit = '{$code[0]['code']}'";
                    $exist = $this->AlertRules->query($exist_sql);
                    if ($exist[0][0]['sum'])
                    {
                        continue;
                    }
                    $client = $this->AlertRules->query("SELECT client_id FROM resource where resource_id = {$data_item[0]['egress_id']}");
                    $client_id = $client[0][0]['client_id'];
                    $sql_value_arr[] = "({$client_id},'{$data_item[0]['ingress_id']}','{$code[0]['code']}','{$create_time}',{$block_log_id},1,'Rule[{$this->rule_name}]')";
                }
            }
            $sql_value = implode(',', $sql_value_arr);
        }
        if (empty($sql_value))
        {
            return "";
        }
        if (strcmp($group_field, 'ingress_id'))
        {
            $sql = "INSERT INTO resource_block (ingress_client_id,ingress_res_id,digit,create_time,block_log_id,action_type,update_by) VALUES {$sql_value};";
        }
        else
        {
            $sql = "INSERT INTO resource_block (egress_client_id,engress_res_id,digit,create_time,block_log_id,action_type,update_by) VALUES {$sql_value};";
        }
        return $sql;
    }

    public function create_file($trunk_id, $code_data, $code_name_data)
    {
        require_once dirname(__FILE__) . '/../phpexcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'trunk');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'code name');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'code');
        foreach ($code_data as $code_data_item)
        {
            $codes_data_arr[] = $code_data_item[0]['code'];
        }
        $trunk_name = $this->AlertRules->query("SELECT alias FROM resource WHERE resource_id = {$trunk_id}; ", false);
        var_dump($trunk_id);
        foreach ($code_name_data as $code_names)
        {
            $search_sql = "SELECT code FROM code WHERE name = '{$code_names[0]['code_name']}'";
            $code_arr = $this->AlertRules->query($search_sql, false);
            $block_code_arr = array();
            foreach ($code_arr as $code)
            {
                if (in_array($code, $codes_data_arr))
                {
                    $block_code_arr[] = $code;
                }
            }
            $block_code_arr_all = array_merge($block_code_arr, $codes_data_arr);
            $codes = implode(',', $block_code_arr_all);
            $i = 2;
            $a = "A" . $i;
            $b = "B" . $i;
            $c = "C" . $i;
            $objPHPExcel->getActiveSheet()->setCellValue($a, $trunk_name[0][0]['alias']);
            $objPHPExcel->getActiveSheet()->setCellValue($b, $code_names[0]['code_name']);
            $objPHPExcel->getActiveSheet()->setCellValue($c, $codes);
            $i++;
        }

        $file_path = "/tmp/block_list.xlsx";
        $objWriter->save($file_path);
        return $file_path;
    }

    /**
     * 
     * @param type $num1
     * @param type $num2
     * @param type $flg
     * @return boolean
     * 
     * 判断两个数是否符合第三个参数的$flg 的符号 比较 
     * 符合返回TRUE  
     * 
     */
    public function judge_num($num1, $num2, $flg)
    {
        $num1 = floatval($num1);
        $num2 = floatval($num2);
        switch ($flg)
        {
            case '<':
                if ($num1 < $num2)
                {
                    return TRUE;
                }
                return FALSE;
            case '>':
                if ($num1 > $num2)
                {
                    return TRUE;
                }
                return FALSE;
            case '=':
                if ($num1 == $num2)
                {
                    return TRUE;
                }
                return FALSE;
            default :return FALSE;
        }
    }

}
