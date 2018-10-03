<?php

class SippShell extends Shell
{

    var $uses = array('CheckRoute');

    function main()
    {
        Configure::load('myconf');
        $sip_path = Configure::read('sipp.sipp_exe');
        $rule_id = isset($this->args[0]) ? (int) $this->args[0] : "";
        $numbers =  isset($this->args[1]) ? trim($this->args[1]) : "";

        $test_carrier = Configure::read('check_route.carrier_name');
        $test_ingress = Configure::read('check_route.trunk_name');
        $test_ip = Configure::read('check_route.ip');
        $test_ani = Configure::read('check_route.ani');

        if (!$rule_id)
        {
            file_put_contents('/tmp/sipp.log', "\r\n\r\n" . date('Y-m-d H:i:s') . "    no rule_id  \r\n" , FILE_APPEND);
            return false;
        }

        $rule_info_item = $this->CheckRoute->find(array('id' => $rule_id));

        if(empty($rule_info_item)){
            file_put_contents('/tmp/sipp.log', "\r\n\r\n" . date('Y-m-d H:i:s') . "    can not find record  \r\n" , FILE_APPEND);
            return false;
        }
        $trunk_id = $rule_info_item['CheckRoute']['egress_id'];
//        $res = $this->CheckRoute->query("select * from  egress_test_result where egress_test_id = {$rule_id}  ");

        if(empty($res)){
            file_put_contents('/tmp/sipp.log', "\r\n\r\n" . date('Y-m-d H:i:s') . "    can not find record result  \r\n" , FILE_APPEND);
            return false;
        }

        $values = array();
        foreach($res as $value){
            $values[$value[0]['dnis']] = $value[0]['id'];
        }

        //var_dump($rule_info_item);
        file_put_contents('/tmp/sipp.log', "\r\n\r\n" . date('Y-m-d H:i:s') . " Start Script  Numbser:\r\n" . $numbers . "\r\n  Trunk ID : {$rule_info_item['CheckRoute']['egress_id']}  \r\n" , FILE_APPEND);

        $switch_ip = $this->CheckRoute->query(" select sip_ip||':'||sip_port as ip from switch_profile limit 1 ");
        $switch_ip = $switch_ip[0][0]['ip'];

        $sec = !empty($rule_info_item['CheckRoute']['sec'])?$rule_info_item['CheckRoute']['sec']*1000:3;

        file_put_contents('/tmp/sipp.log', "\r\n\r\n ". $sec ." \r\n\r\n " , FILE_APPEND);

        $uac = realpath(ROOT.'/uac.xml');
        $numbers = explode(',', $numbers);
        $cdrTableName = date('Ymd');
        $not_zero_calls = 0;
        $busy_calls = 0;
        $cancel_calls = 0;
        $success_calls = 0;
        foreach($numbers as $value){
            $call_id = md5(uniqid()) . '@' . $switch_ip;
            $dnis = $trunk_id.$value;
            $cmd = " $sip_path -sf {$uac} -i {$test_ip}  -m 1 -d {$sec} -key  ani {$test_ani}  -s {$dnis} {$switch_ip}  -cid_str {$call_id}  ";
            $res = shell_exec($cmd);
            //echo $cmd."<br/>";
            $cdrData = array();
            $i = 1;
            while($i > 6)
            {
                sleep(10);
                $cdr_sql = <<<CDRSQL
SELECT answer_time_of_date,call_duration,release_cause_from_protocol_stack,
(case when call_duration > 0 then 1 else 0 end) as not_zero_calls,
(case when release_cause_from_protocol_stack like '486%' then 1 else 0 end) as busy_calls,
(case when release_cause_from_protocol_stack like '487%' then 1 else 0 end ) as cancel_calls,
(case when egress_id is not null then 1 else 0 end) as success_calls,
(case when call_duration > 0 then pdd else 0 end) as pdd
FROM client_cdr$cdrTableName
WHERE origination_call_id = $call_id
CDRSQL;
                $cdrData = $this->CheckRoute->query($cdr_sql);
                if ($cdrData)
                    break;
            }
            if (empty($cdrData))
                continue;
            $pdd = $cdrData[0][0]['pdd'];
            $answer_time = date('Y-m-d H:i:sO',$cdrData[0][0]['pdd']/1000000);
            $duration = $cdrData[0][0]['call_duration'];
            $call_result = $cdrData[0][0]['release_cause_from_protocol_stack'];
            if ($cdrData[0][0]['not_zero_calls'])
                $not_zero_calls += 1;
            if ($cdrData[0][0]['busy_calls'])
                $busy_calls += 1;
            if ($cdrData[0][0]['cancel_calls'])
                $cancel_calls += 1;
            if ($cdrData[0][0]['success_calls'])
                $success_calls += 1;

            file_put_contents('/tmp/sipp.log', "\r\n\r\n ". $cmd . "\r\n\r\n  {$res} \r\n\r\n " , FILE_APPEND);
//            $insert_sql = <<<INSERTSQL
//update egress_test_result set end_time = current_timestamp(0) ,call_id = '$call_id',pdd = $pdd,answer_time = '$answer_time',
//duration = $duration,call_result = '$call_result' where id = {$values[$value]}
//INSERTSQL;
//            $this->CheckRoute->query($insert_sql);
        }

        $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
        $asr = $_asr == 0 ? 0 : round($not_zero_calls / $_asr,4);

//        $total_sql = <<<TOTALSQL
//update egress_test set end_time = current_timestamp(0),success_calls = $success_calls ,asr = $asr  where id = $rule_id
//TOTALSQL;
//        $this->CheckRoute->query($total_sql);

        file_put_contents('/tmp/sipp.log', date('Y-m-d H:i:s') . " End Script   \r\n" , FILE_APPEND);
    }

}
