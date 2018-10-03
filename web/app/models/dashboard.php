
<?php

class Dashboard extends AppModel
{

    var $name = 'Dashboard';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';

    var $sys_time = 0;

    public function __construct(){
        $this->sys_time = time();
    }

//处理 第一部分：  ajax_text1
    public function ajax_text1($time){
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        $end_time = strtotime(date('Y-m-d H:00:00',$end_time));

        if($time==1){
            $start_time = $end_time - 3600;
//            $end_time = $end_time - 1;
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);
        } else {
//            $end_time = $end_time - 1;
            $end_time = date('Y-m-d H:i:s', $end_time);
            $start_time = date('Y-m-d H:i:s', strtotime($end_time . "-1 days"));
        }

        $start_time .=  ' ' . $tz;
        // calls are recording not by GMT
        $end_time =  date("Y-m-d H:i:s", strtotime('+4 hour')).' ' . $tz;

        $sql = $this->ajax_table1_get_report($start_time,$end_time);
        $result = $this->query($sql);
        $data = array(
            'non_zero' => 0,
            'calls' => 0,
            'spending' => 0,
            'volume' => 0
        );

        foreach ($result as $item) {
            $data['non_zero'] += $item[0]['not_zero_calls'];
            $data['calls'] += $item[0]['ingress_total_calls'];
            $data['spending'] += $item[0]['ingress_call_cost'];
            $data['volume'] += $item[0]['ingress_bill_time'] / 60;
        }

        foreach($data as $k => $item){


            $item = $item + 0;
            $data[$k] = round($item,2);

        }

        return $data;

    }
    //
    public function ajax_text1_get_qos($start_time,$end_time){

        $field =" sum(not_zero_calls) as non_zero, sum(bill_time) / 60 as volume,
            sum(total_calls) as calls, sum(cost) as spending ";



        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
        $client_id = $this->query($sql);
        $client_id = $client_id[0][0]['client_id'];
        if ($client_id) {
            $where = "report_time between '$start_time' and '$end_time'  and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false)";
        }
        $sql = "select $field from qos_route_report where $where ";

        return $sql;
    }


//处理 第一部分：  ajax_text1


    /* 第二部分 */
    //第二部分：  ajax_chart1
    public function ajax_add_chart1($tab_value){
        $field = " report_time, sum(total_calls) as val ";
        switch($tab_value){
            case 'call_attempts':
                $field = " report_time, sum(total_calls) as val ";
                break;
            case 'volume':
                $field = " report_time, sum(bill_time) / 60 as val ";
                break;
            case 'non_zero':
                $field = " report_time, sum(not_zero_calls) as val ";
                break;
            case 'cost':
                $field = " report_time, sum(cost) as val ";
                break;
        }


        $group = " group by report_time";

        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
        $client_id = $this->query($sql);
        $client_id = $client_id[0][0]['client_id'];

//        $max_time = $_POST['max_time'];
//        $i = $_POST['i'];
        $interval = $_POST['interval'];


//        $max_time = date('Y-m-d H:i:sO',strtotime($max_time)+$interval*$i);
        $iden = $_POST['iden'];
        $time = $this->get_client_dashboard_time($iden);
        $time = strtotime($time) + $interval;
        $max_time = $time * 1000;

        $time = date('Y-m-d H:i:00',$time);
        $this->set_client_dashboard_time($iden,$time);
        if ($client_id) {
            $where = "report_time = '$max_time' and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false)";
        }
        $sql = "select $field from qos_route_report where $where $group order by report_time";

        $data = $this->query($sql);
        $rst = array();
        $tem_time = $max_time;
        $rst['point'] = array($tem_time,$data[0][0]['val']+0);


        return $rst;
    }

    public function ajax_chart1($time,$tab_value){

        //处理时间
        //$time 1 -》 24小时，7 -》 7天，15 -》 15天，30 -》 30天，60 -》 60天，
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        switch($time){
            case 1:
                $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
                $start_time = $end_time - 24*3600+60;
                break;
            case 7:
                $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
                $start_time = strtotime(date('Y-m-d H:00:00',$end_time)) - 7*24*3600+3600;
                break;
            case 15:
                $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
                $start_time = strtotime(date('Y-m-d H:00:00',$end_time)) - 15*24*3600+3600;

                break;
            case 30:
                $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
                $start_time = strtotime(date('Y-m-d H:00:00',$end_time)) - 30*24*3600+3600;
                break;
            case 60:
                $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
                $start_time = strtotime(date('Y-m-d H:00:00',$end_time)) - 60*24*3600+3600;

                break;

        }
        //时间点数组
        if($time == 1){
            $data_time_arr = range( $start_time*1000, $end_time*1000, 60000);
        } else {
            $data_time_arr = range( $start_time*1000, $end_time*1000, 3600000);
        }







        //最后24小时从qos取,其他从cdr_report_detail


        if($time== 1){
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;

            $sql = $this->ajax_chart1_get_qos($time,$start_time,$end_time,$tab_value);
        } else {
            $qos_start_time = $end_time - 23*3600;
            $report_end_time = $end_time - 24*3600;  //多一个小时，因为汇总以:00为准

            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);
            $qos_start_time = date('Y-m-d H:i:s', $qos_start_time);
            $report_end_time = date('Y-m-d H:i:s', $report_end_time);

            $start_time .=  ' ' . $tz;
            $end_time .=  ' ' . $tz;

            $sql1 = $this->ajax_chart1_get_qos($time,$qos_start_time,$end_time,$tab_value);

            //cdr
            $sql2 = $this->ajax_chart1_get_report($time,$start_time,$report_end_time,$tab_value);

            $sql = " ($sql1) union ($sql2) ";


        }

        $data = $this->query($sql);

        $tem = array();
        foreach($data as $k => $item) {
            $item[0]['time'] = strtotime($item[0]['report_time']) * 1000;
            $item[0]['val'] = $item[0]['val'] + 0;


            $tem[$item[0]['time']] = array($item[0]['time'], $item[0]['val']);



        }

        $rst = array();
//        //如果数据点没有数据，置空
        foreach($data_time_arr as $v){
            if(array_key_exists($v,$tem)){
                $rst['point'][] = $tem[$v];
            } else {
                $rst['point'][] = array($v,0);
            }
        }

        //$rst['max_time'] = $end_time;
        $max_time = strtotime($end_time) - 60;
        $max_time = date('Y-m-d H:i:00',$max_time);
        $rst['iden'] = $_SESSION['sst_user_id'] . '_' . date('Ymd_His');
        $this->set_client_dashboard_time($rst['iden'],$max_time);



        return $rst;

    }


    //ajax_chart1_get_qos
    public function ajax_chart1_get_qos($time,$start_time,$end_time,$tab_value){

        $group = " group by report_time";
        $field =" report_time ";
        if($time != 1){
            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:00:00') ";
            $field = " to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time ";
        }


        switch($tab_value){
            case 'call_attempts':
                $field .= ", sum(total_calls) as val ";
                break;
            case 'volume':
                $field .= ", sum(bill_time) / 60 as val ";
                break;
            case 'non_zero':
                $field .= ", sum(not_zero_calls) as val ";
                break;
            case 'cost':
                $field .= ", sum(cost) as val ";
                break;
            default:
                $field .= ", sum(total_calls) as val ";
        }







        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
        $client_id = $this->query($sql);
        $client_id = $client_id[0][0]['client_id'];
        if ($client_id) {
            $where = "report_time between '$start_time' and '$end_time'  and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false)";
        }
        $sql = "select $field from qos_route_report where $where $group ";

        return $sql;
    }



    // ajax_chart1 从cdr_report_detail 取数据
    public function ajax_chart1_get_report($time,$start_time, $end_time, $tab_value){

        $group = " group by report_time ";
        $out_field =" report_time ";
        if($time != 1){
            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:00:00') ";
            $out_field = " to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time ";
        }


        switch($tab_value){
            case 'call_attempts':
                $in_field = " report_time, ingress_total_calls ";
                $out_field .= ", sum(ingress_total_calls) as val ";
                break;
            case 'volume':
                $in_field = " report_time, ingress_bill_time ";
                $out_field .= ", sum(ingress_bill_time) / 60 as val ";
                break;
            case 'non_zero':
                $in_field = " report_time, not_zero_calls ";
                $out_field .= ", sum(not_zero_calls) as val ";
                break;
            case 'cost':
                $in_field = " report_time, ingress_call_cost ";
                $out_field .= ", sum(ingress_call_cost) as val ";
                break;
            default:
                $in_field = " report_time, ingress_total_calls ";
                $out_field .= ", sum(ingress_total_calls) as val ";
        }





        $sst_user_id = $_SESSION['sst_user_id'];
        $where = " report_time between '$start_time' and '$end_time' and ingress_client_id = (SELECT client_id FROM users WHERE user_id = $sst_user_id ) ";



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report_detail2%');

        if(count($date_arr) == 1){
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where $group ";
        } else {
            $sql = '';
            foreach($date_arr as $val){
                $table_name = CDR_TABLE . $val;
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where";
            }

            $sql = "select $out_field from ( $sql ) as tmp2 $group ";
        }

        //$rst = $this->query($sql);
        return $sql;
    }

    /* 第二部分 */


//处理 第三部分：  ajax_text2
    public function ajax_text2($time = 24){
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        $end_time = strtotime(date('Y-m-d H:00:00',$end_time));

        if($time==1){
            $start_time = $end_time - 3600;
//            $end_time = $end_time - 1;
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);
        } else {
//            $end_time = $end_time - 1;
            $end_time = date('Y-m-d H:i:s', $end_time);
            $start_time = date('Y-m-d H:i:s', strtotime($end_time . "-1 days"));
        }

        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;


//        $sql = $this->ajax_text2_get_qos($start_time,$end_time);
        $sql = $this->ajax_table1_get_report($start_time,$end_time);
        $result = $this->query($sql);
        $data = array(
            'ingress_bill_time' => 0,
            'not_zero_calls' => 0,
            'ingress_total_calls' => 0
        );

        foreach ($result as $item) {
            $data['ingress_bill_time'] += $item[0]['ingress_bill_time'];
            $data['not_zero_calls'] += $item[0]['not_zero_calls'];
            $data['ingress_total_calls'] += $item[0]['ingress_total_calls'];
        }


        $bill_time = $data['ingress_bill_time'] + 0;
        $not_zero_calls = $data['not_zero_calls'] + 0;
        $totalCalls = $data['ingress_total_calls'] + 0;

        $ready_data = array();
        $ready_data['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;

//        $asr_ = $busy_calls + $cancel_calls + $not_zero_calls;
        $asr_ = $totalCalls;
        $ready_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;

        return $ready_data;



    }

    //
    public function ajax_text2_get_qos($start_time,$end_time){

        $field =" sum(not_zero_calls) as not_zero_calls, sum(bill_time) as bill_time,
            sum(busy_calls) as busy_calls, sum(cancel_calls) as cancel_calls ";



        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
        $client_id = $this->query($sql);
        $client_id = $client_id[0][0]['client_id'];
        if ($client_id) {
            $where = "report_time between '$start_time' and '$end_time'  and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false)";
        }
        $sql = "select $field from qos_route_report where $where ";

        return $sql;
    }


//处理 第三部分：  ajax_text2


//第四部分：  ajax_table1
    //$time 1->1小时， 24->24小时
    public function ajax_table1($time, $sort, $limit, $trunk_id = false){
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        $end_time = strtotime(date('Y-m-d H:00:00',$end_time));

        if($time==1){
            $start_time = $end_time - 3600;
//            $end_time = $end_time - 1;
            $start_time = date('Y-m-d H:i:s', $start_time);
            $end_time = date('Y-m-d H:i:s', $end_time);
        } else {
//            $end_time = $end_time - 1;
            $end_time = date('Y-m-d H:i:s', $end_time);
            $start_time = date('Y-m-d H:i:s', strtotime($end_time . "-1 days"));
        }

        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;
        $sql = $this->ajax_table1_get_report($start_time,$end_time, false, $trunk_id) . $sort . $limit;
        $rst = $this->query($sql);

        $data = array();
        foreach($rst as $k => $v){
            $data[$k]['code_name'] = $v[0]['ingress_code_name'];
            $data[$k]['attempt'] = $v[0]['ingress_total_calls'] + 0;
            $data[$k]['non_zero'] = $v[0]['not_zero_calls'] + 0;
            $data[$k]['min'] = round($v[0]['ingress_bill_time'] / 60, 2);
            if (!($_SESSION['login_type'] == 3 && isset($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate']))) {
                $data[$k]['cost'] = round($v[0]['ingress_call_cost'], 2);
            }



            $ingress_bill_time = $v[0]['ingress_bill_time'] + 0;
            $not_zero_calls = $v[0]['not_zero_calls'] + 0;



            $data[$k]['acd'] = !empty($not_zero_calls) ? round($ingress_bill_time / $not_zero_calls / 60, 2) : 0;

            $asr_ = $v[0]['ingress_total_calls'];
            $data[$k]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
        }

        $data['time_intval'] = $start_time . ' ~ ' . $end_time;



        return $data;
    }

    public function count_ajax_table1($time, $trunk_id = false){
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        $end_time = strtotime(date('Y-m-d H:00:00',$end_time));

        if($time==1){
            $start_time = $end_time - 3600;
            $end_time = $end_time - 1;
            $start_time = date('Y-m-d H:i:s', $start_time);
        } else {
            $end_time = $end_time - 1;
            $start_time = date('Y-m-d 00:00:00');
        }

        $end_time = date('Y-m-d H:i:s', $end_time);

        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;

        $sql = $this->ajax_table1_get_report($start_time,$end_time, true, $trunk_id);
        $rst = $this->query($sql);

        return count($rst);
    }



    public function ajax_table1_get_report($start_time, $end_time, $is_count = false, $trunk_id = false){
        if($is_count){
            $in_field = 'ingress_code_name';
            $out_field = 'ingress_code_name';

        }else{
            $in_field =
                ' ingress_code_name, ingress_total_calls, not_zero_calls, ingress_call_cost, ingress_busy_calls, ingress_cancel_calls, ingress_bill_time ';

            $out_field = " ingress_code_name,
        sum(not_zero_calls) as not_zero_calls,
        sum(ingress_total_calls) as ingress_total_calls,
        sum(ingress_call_cost) as ingress_call_cost,
        sum(ingress_bill_time) as ingress_bill_time,
        sum(ingress_busy_calls) as ingress_busy_calls,
        sum(ingress_cancel_calls) as ingress_cancel_calls ";
        }

        $trunk_where = "";
        if($trunk_id){
            $trunk_where = " AND ingress_id = '{$trunk_id}' ";
        }

        $group = " group by ingress_code_name ";

        $sst_client_id = $_SESSION['sst_client_id'];
        $where = " report_time between '$start_time' and '$end_time' $trunk_where and (ingress_client_id = $sst_client_id OR egress_client_id = $sst_client_id)";



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report_detail2%');

        if(count($date_arr) == 1){
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where $group ";
        } else {
            $sql = '';
            foreach($date_arr as $val){
                $table_name = CDR_TABLE . $val;
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where ";
            }

            $sql = " select $out_field from ( $sql ) as tmp2 $group ";
        }
        //$rst = $this->query($sql);
        return $sql;
    }

//第四部分：  ajax_table1


//第五部分：  ajax_table2
    //$time 1->24小时， 2->7天， 3->4周， 4->3个月, $trunk
    public function ajax_table2($time, $trunk, $sort){
        $tz = $this->get_sys_timezone();
        $end_time = $this->sys_time;
        $end_time = strtotime(date('Y-m-d H:i:00',$end_time));
        if($time==24){
            $start_time = date('Y-m-d H:i:s', strtotime("-1 days"));
        } else {
            $start_time = date('Y-m-d H:i:s', strtotime("-7 days"));
        }

        $qos_start_time = strtotime(date('Y-m-d 00:00:00', $end_time));
        $report_end_time = $qos_start_time - 3600;

        $end_time = date('Y-m-d H:i:s', $end_time);
        $qos_start_time = date('Y-m-d H:i:s', $qos_start_time);
        $report_end_time = date('Y-m-d H:i:s', $report_end_time);

        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;
        $qos_start_time .=  ' ' . $tz;
        $report_end_time .=  ' ' . $tz;

        if($time == 1){
            $sql = $this->ajax_table2_get_qos($time,$start_time, $end_time,$trunk) . $sort;
        } else {
            //今天的数据从qos取，以前的从report取
            $sql1 = $this->ajax_table2_get_qos($time,$qos_start_time, $end_time,$trunk);
            $sql2 = $this->ajax_table2_get_report($time,$start_time, $report_end_time,$trunk);

            $field = "report_time, sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls,
        sum(cost) as cost, sum(bill_time) as bill_time,
        sum(busy_calls) as busy_calls, sum(cancel_calls) as cancel_calls";
            $sql = " select $field from ( ($sql1) union all ($sql2) ) as tmp3 group by report_time $sort";
        }
        $rst = $this->query($sql);

        $data = array();
        foreach($rst as $k => $v){
            $data[$k]['time_period'] = $v[0]['report_time'];
            $data[$k]['attempt'] = $v[0]['total_calls'] + 0;
            $data[$k]['non_zero'] = $v[0]['not_zero_calls'] + 0;
            $data[$k]['min'] = round($v[0]['bill_time'] / 60, 2);
//            if ($_SESSION['login_type'] == 3 && isset($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'])) {
            $data[$k]['cost'] = round($v[0]['cost'], 2);
//            }

            $bill_time = $v[0]['bill_time'] + 0;
            $not_zero_calls = $v[0]['not_zero_calls'] + 0;
            $busy_calls = $v[0]['busy_calls'] + 0;
            $cancel_calls = $v[0]['cancel_calls'] + 0;

            $data[$k]['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;

            $asr_ = $busy_calls + $cancel_calls + $not_zero_calls;
            $data[$k]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
        }

        if($time == 3){
            $data_time_arr = array();
            for($i=11; $i>=0; $i--){
                $tmp = date("W") - $i;
                $data_time_arr[$tmp]['start_time'] = date('Y-m-d', mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7*$i,date("Y")));
                $data_time_arr[$tmp]['end_time'] = date('Y-m-d', mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7*$i+6,date("Y")));
            }

            foreach($data as $k => $v){
                $data[$k]['time_period'] = $data_time_arr[$v['time_period']]['start_time'] .' ~ '.$data_time_arr[$v['time_period']]['end_time'];
            }
        }



        return $data;
    }

    //ajax_chart2_get_qos
    public function ajax_table2_get_qos($time,$start_time,$end_time,$trunk){



        if($time == 1){
            $group = " group by to_char(report_time,'YYYY-MM-DD HH24:00:00') ";
            $field = " to_char(report_time,'YYYY-MM-DD HH24:00:00') as report_time, ";
        } elseif($time == 2){
            $group = " group by to_char(report_time,'YYYY-MM-DD 00:00:00') ";
            $field = " to_char(report_time,'YYYY-MM-DD 00:00:00') as report_time, ";
        } elseif($time == 3){
            $group = " group by extract(WEEK FROM report_time) ";
            $field = " extract(WEEK FROM report_time) as report_time, ";
        } else {
            $group = " group by report_time ";
            $field = " report_time::text as report_time, ";
        }



        $field .= " sum(not_zero_calls) as not_zero_calls,
        sum(total_calls) as total_calls,
        sum(cost) as cost,
        sum(bill_time) as bill_time,
        sum(busy_calls) as busy_calls,
        sum(cancel_calls) as cancel_calls ";






        if(!empty($trunk) && $trunk != '0'){
            $trunk = " and resource_id = $trunk ";
        } else {
            $sst_user_id = $_SESSION['sst_user_id'];
            $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
            $client_id = $this->query($sql);
            $client_id = $client_id[0][0]['client_id'];
            if ($client_id) {
                $trunk = " and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false) ";
            }
        }









        $where = "report_time between '$start_time' and '$end_time' $trunk ";

        $sql = "select $field from qos_route_report where $where $group ";

        return $sql;
    }


    // ajax_chart2 从cdr_report_detail 取数据
    public function ajax_table2_get_report($time, $start_time, $end_time,$trunk){
        if($time == 1){
            $group = " group by to_char(report_time,'YYYY-MM-DD HH24:00:00') ";
            $out_field = " to_char(report_time,'YYYY-MM-DD HH24:00:00') as report_time, ";
        } elseif($time == 2){
            $group = " group by to_char(report_time,'YYYY-MM-DD 00:00:00') ";
            $out_field = " to_char(report_time,'YYYY-MM-DD 00:00:00') as report_time, ";
        } elseif($time == 3){
            $group = " group by extract(WEEK FROM report_time) ";
            $out_field = " extract(WEEK FROM report_time) as report_time, ";
        } else {
            $group = " group by report_time ";
            $out_field = " report_time::text as report_time, ";
        }

        $in_field =
            " report_time,ingress_total_calls, not_zero_calls, ingress_call_cost, ingress_busy_calls, ingress_cancel_calls, ingress_bill_time ";

        $out_field .= " sum(not_zero_calls) as not_zero_calls,
        sum(ingress_total_calls) as total_calls,
        sum(ingress_call_cost) as cost,
        sum(ingress_bill_time) as bill_time,
        sum(ingress_busy_calls) as busy_calls,
        sum(ingress_cancel_calls) as cancel_calls ";



        if(!empty($trunk) && $trunk != '0'){
            $trunk = " and ingress_id = $trunk ";
        } else {
            $trunk = '';
        }


        $sst_user_id = $_SESSION['sst_user_id'];
        $where = " report_time between '$start_time' and '$end_time' and ingress_client_id = (SELECT client_id FROM users WHERE user_id = $sst_user_id ) $trunk ";



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report_detail2%');

        if(count($date_arr) == 1){
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where $group ";
        } else {
            $sql = '';
            foreach($date_arr as $val){
                $table_name = CDR_TABLE . $val;
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where";
            }

            $sql = "select $out_field from ( $sql ) as tmp2 $group ";
        }

        //$rst = $this->query($sql);
        return $sql;
    }


//第五部分：  ajax_table2

//第六部分：  ajax_chart2
    public function ajax_chart2($time, $which_value){

        //处理时间
        //$time 1 -》 按天，2 -》 按周，3 -》 按月，
        $tz = $this->get_sys_timezone();
        switch($time){
            case 1:
                $end_time = strtotime(date('Y-m-d H:i:00'));
                $start_time = $end_time - 3600;
                $data_time_arr = range($start_time, $end_time, 60);
                break;
            case 2:
                $end_time = strtotime(date('Y-m-d H:00:00'));
                $start_time = $end_time - 3600 * 24;
                $data_time_arr = range($start_time, $end_time, 3600);
                break;
            case 3:
                $end_time = strtotime(date('Y-m-d 00:00:00'));
                $start_time = $end_time - 3600 * 24 * 7;
                $data_time_arr = range($start_time, $end_time, 3600 * 24);
                break;

        }

        //时间点数组
//        $data_time_arr = range( strtotime(date('Y-m-d h:i:s',$start_time))*1000, strtotime(date('Y-m-d h:i:s', $end_time))*1000, 24*3600000);

        $qos_start_time = strtotime(date('Y-m-d h:i:s', $end_time));
        $report_end_time = $qos_start_time - 3600;

        $start_time = date('Y-m-d H:i:s', $start_time);
        $end_time = date('Y-m-d H:i:s', $end_time);
        $qos_start_time = date('Y-m-d H:i:s', $qos_start_time);
        $report_end_time = date('Y-m-d H:i:s', $report_end_time);

        $start_time .=  ' ' . $tz;
        $end_time .=  ' ' . $tz;
        $qos_start_time .=  ' ' . $tz;
        $report_end_time .=  ' ' . $tz;

        //今天的数据从qos取，以前的从report取
//        $sql1 = $this->ajax_chart2_get_qos($time, $start_time, $end_time, $which_value);
        $sql2 = $this->ajax_chart2_get_report($time, $start_time, $end_time, $which_value);
//        $sql = "select report_time,sum(val) as val from( ($sql1) union all ($sql2) ) as tmp3 group by report_time ";
        $data = [];
        if($sql2){
            $sql = "select report_time,sum(val) as val from( $sql2 ) as tmp3 group by report_time ";
            $data = $this->query($sql);
        }



        //处理数据
        $rst = array();

        $tem = array();
//        if($time != 2){
        foreach($data as $k => $item){
            $item[0]['report_time'] = strtotime($item[0]['report_time']);

            $item[0]['val'] = $item[0]['val'] + 0;


            $tem[$item[0]['report_time']]['val'] = array($item[0]['report_time'],$item[0]['val']);


        }

//            die(var_dump($tem, $data_time_arr));
        //如果数据点没有数据，置空
        foreach($data_time_arr as $key => $v){

            if(array_key_exists($v,$tem)){
                $rst['val'][] = $tem[$v]['val'];

            } else {
                $rst['val'][] = array($v,0);
            }
        }

        foreach ($rst['val'] as $key => $item) {
            $rst['val'][$key][0] *= 1000;
        }
//        } else {
//            $arr_tmp = array();
//            foreach($data as $k => $item){
//
//
//                $item[0]['val'] = $item[0]['val'] + 0;
//
//
//
//
//                $arr_tmp[$item[0]['report_time']]['val'] = $item[0]['val'];
//
//
//            }
//
//            //如果数据点没有数据，置空
//            foreach($data_time_arr as $v){
//                $wk = date('W',$v/1000);
//
//                if(array_key_exists($wk,$arr_tmp)){
//                    $rst['val'][] = array($v,$arr_tmp[$wk]['val']);
//
//                } else {
//                    $rst['val'][] = array($v,0);
//                }
//            }
//        }


        return $rst;

    }

    //ajax_chart2_get_qos
    public function ajax_chart2_get_qos($time,$start_time,$end_time,$which_value){

        if($time == 1){
            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:MI:00') ";
            $field = " to_char(report_time, 'YYYY-MM-DD HH24:MI:00') as report_time ";
        } elseif($time == 2){
            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:00:00') ";
            $field = " to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time ";
        } else {
            $group = " group by to_char(report_time, 'YYYY-MM-DD 00:00:00') ";
            $field = " to_char(report_time, 'YYYY-MM-DD 00:00:00') as report_time ";
        }


        switch($which_value){
            case 'minutes':
                $field .= ", sum(bill_time) / 60 as val ";
                break;
            case 'cost':
                $field .= ", sum(cost) as val ";
                break;
        }






        if ($_SESSION['login_type'] == 2)
        {
            $where = <<<WHERE
report_time between '$start_time' and '$end_time' and exists( select 1 from agent_clients inner join resource on
agent_clients.client_id = resource.client_id where agent_id = {$_SESSION['sst_agent_info']['Agent']['agent_id']})
WHERE;
        }
        else
        {
            $sst_user_id = $_SESSION['sst_user_id'];
            $sql = "SELECT client_id FROM users WHERE user_id = $sst_user_id";
            $client_id = $this->query($sql);
            $client_id = $client_id[0][0]['client_id'];
            if ($client_id) {
                $where = "report_time between '$start_time' and '$end_time'  and resource_id in (select resource_id from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false)";
            }
        }
        $sql = "select $field from qos_route_report where $where $group ";

        return $sql;
    }


    // ajax_chart2 从cdr_report_detail 取数据
    public function ajax_chart2_get_report($time,$start_time, $end_time, $which_value){
        if($time == 1){
            $group = " group by to_char(report_time, 'YYYY-MM-DD 00:00:00') ";
            $field = " to_char(report_time, 'YYYY-MM-DD 00:00:00') as report_time ";
        } elseif($time == 2){
            $group = " group by extract(WEEK FROM report_time) ";
            $field = " extract(WEEK FROM report_time) as report_time ";
        } else {
            $group = " group by to_char(report_time, 'YYYY-MM-01 00:00:00') ";
            $field = " to_char(report_time, 'YYYY-MM-01 00:00:00') as report_time ";
        }




        $in_field =
            ' report_time ';
        if($time == 1){
            $out_field = " to_char(report_time, 'YYYY-MM-DD HH24:MI:00') as report_time ";

            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:MI:00') ";
        } elseif($time==2) {
            $out_field = " to_char(report_time, 'YYYY-MM-DD HH24:00:00') as report_time ";

            $group = " group by to_char(report_time, 'YYYY-MM-DD HH24:00:00') ";


        } else {
            $out_field = " to_char(report_time, 'YYYY-MM-DD 00:00:00') as report_time ";

            $group = " group by to_char(report_time, 'YYYY-MM-DD 00:00:00') ";
        }

        switch($which_value){
            case 'minutes':
                $in_field .= " ,ingress_bill_time ";
                $out_field .= " ,sum(ingress_bill_time) / 60 as val";
                break;
            case 'cost':
                $in_field .= " ,ingress_call_cost ";
                $out_field .= " ,sum(ingress_call_cost) as val ";
                break;
        }

        if ($_SESSION['login_type'] == 2)
        {
            $agent_id_check = "";
            if(isset($_SESSION['sst_agent_info']['Agent']['agent_id']) && $_SESSION['sst_agent_info']['Agent']['agent_id']){
                $agent_id_check = " AND agent_id = '{$_SESSION['sst_agent_info']['Agent']['agent_id']}'";
            }
            $where = <<<WHERE
report_time between '$start_time' and '$end_time' and EXISTS (SELECT 1 FROM agent_clients where client_id = ingress_client_id $agent_id_check)
WHERE;
        }
        else
        {
            $sst_user_id = $_SESSION['sst_user_id'];
            $where = " report_time between '$start_time' and '$end_time' and ingress_client_id = (SELECT client_id FROM users WHERE user_id = $sst_user_id ) ";
        }



        //判断是否使用多个表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report2%');
        $sql = '';
        if(count($date_arr) == 1 && $this->table_exists(CDR_TABLE . $date_arr[0])){
            //$table = 'cdr_report'.$date_arr[0];
            $table = CDR_TABLE . $date_arr[0];
            $sql = "select $out_field from $table where $where $group ";
        } else {
            foreach($date_arr as $val){
                //$table_name = 'cdr_report'.$val;
                $table_name = CDR_TABLE . $val;
                if(!$this->table_exists($table_name)){
                    continue;
                }
                $union = ' union all ';
                if(empty($sql))
                    $union = '';
                $sql .= " $union select $in_field from $table_name where $where";
            }
            if($sql) {
                $sql = "select $out_field from ( $sql ) as tmp2 $group ";
            }
        }
        return $sql;
    }
//第六部分：  ajax_chart2


    public function getAgentQosData($time_type,$which_value,$series_type)
    {
        $end_time = date('Y-m-d H:i:sO');
        switch ($time_type){
            case '1':
                $start_time = date('Y-m-d H:i:sO',strtotime('-1 hours'));
                break;
            case '2':
                $start_time = date('Y-m-d H:i:sO',strtotime('-1 days'));
                break;
            case '3':
                $start_time = date('Y-m-d H:i:sO',strtotime('-7 days'));
                break;
            case '4':
                $start_time = date('Y-m-d H:i:sO',strtotime('-15 days'));
                break;
            case '5':
                $start_time = date('Y-m-d H:i:sO',strtotime('-30 days'));
                break;
            case '6':
                $start_time = date('Y-m-d H:i:sO',strtotime('-60 days'));
                break;
            default:
                $start_time = date('Y-m-d H:i:sO',strtotime('-1 days'));
        }


        $more_series_type_arr = array('all','top5');
        if (in_array(strtolower($series_type),$more_series_type_arr))
        {
            switch (strtolower($series_type))
            {
                case "top5":
                    $limit = "LIMIT 5";
                    break;
                case "all":
                    $limit = '';
                    break;
                default :
                    $limit = "LIMIT 5";
            }
            $field = $which_value;
            switch($which_value){
                case 'call_attempts':
                    $field = "call";
                    break;
                case 'channel':
                    break;
                case 'cps':
                    break;
            }
            $sql = <<<SQL
SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource
WHERE report_time between '$start_time' and '$end_time' and exists( select 1 from agent_clients inner join resource on
agent_clients.client_id = resource.client_id where agent_id = {$_SESSION['sst_agent_info']['Agent']['agent_id']} AND
resource.resource_id = res_id)
and direction = 0 GROUP BY res_id ORDER BY sum($field) DESC {$limit}
SQL;
            $result = $this->query($sql);
            $draw_data = array(
                $which_value => array(
                    array(
                        'name' => 'Total',
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        )
                    )
                ),
            );
            foreach ($result as $item)
            {
                $select_trunk['call']['res_id'][] = $item[0]['res_id'];
                $call_data = array();

                $sql_item = <<<SQLITEM
SELECT to_char(report_time - interval '1 mins','YYYY-MM-DD HH24:MI:00') as report_time,SUM($field) AS $field
FROM qos_resource WHERE report_time between '$start_time' and '$end_time' and direction = 0 AND res_id = {$item[0]['res_id']}
GROUP BY to_char(report_time - interval '1 mins','YYYY-MM-DD HH24:MI:00') ORDER BY report_time ASC
SQLITEM;
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item)
                    array_push($call_data, array(strtotime($item_item[0]['report_time']) * 1000, intval($item_item[0][$field])));

                array_push($draw_data[$which_value], array(
                    'name' => $item[0]['name'],
                    'data' => $call_data,
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    )
                ));
            }
            return $draw_data;


        }
        else
        {
            $field = 'report_time';
            $group = 'report_time';
            $total_fields = $group;
            switch($which_value){
                case 'call_attempts':
                    $field .= ",call";
                    $total_fields .= ',sum(call) as call_attempts';
                    break;
                case 'channels':
                    $field .= ",channels";
                    $total_fields .= ',sum(channels) as channels';
                    break;
                case 'cps':
                    $field .= ",cps";
                    $total_fields .= ',sum(cps) as cps';
                    break;
            }
            $name = __('Total',true);
            $extra_where = '';
            if (strcmp(strtolower($series_type),'total'))
            {
                $client_id = intval($series_type);
                $client_info = $this->query("select name from client where client_id = $client_id");
                $name = $client_info[0][0]['name'];
                $extra_where = " AND resource.client_id = $client_id";
            }
            $where = <<<WHERE
report_time between '$start_time' and '$end_time' and exists( select 1 from agent_clients inner join resource on
agent_clients.client_id = resource.client_id where agent_id = {$_SESSION['sst_agent_info']['Agent']['agent_id']} AND
resource.resource_id = res_id $extra_where)
and direction = 0 ORDER BY report_time ASC
WHERE;
            $sql = "select $total_fields FROM (select $field from qos_resource where $where ) as t GROUP BY $group";
//            echo $sql;
            $result_data = $this->query($sql);
            $draw_data = array(
                $which_value => array(
                    array(
                        'name' => $name,
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 0,
                        ),
//
                    )
                ),
            );

            foreach ($result_data as $item) {
                array_push($draw_data[$which_value][0]['data'], array(strtotime($item[0]['report_time']) * 1000, intval($item[0][$which_value])));
            }
            return $draw_data;
        }

    }


}
