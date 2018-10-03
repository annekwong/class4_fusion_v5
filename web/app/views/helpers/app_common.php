<?php

App::import("Controller", "AppController");
App::import("Model", "Cdr");

class AppCommonHelper extends AppHelper
{

    var $helpers = array('form', 'time', 'number','Session');

    function del_date_timezone($string)
    {
        return substr($string, 0, -3);
    }

    function get_date_timezone($string)
    {
        return substr($string, -3);
    }

    public function get_release()
    {
//        $release_file = ROOT . DS . 'release.note';
//        $content = file_get_contents($release_file);
//        $content_arr = explode("\n", trim($content));
//        foreach ($content_arr as $item)
//        {
//            $item_arr = explode("=", $item);
//            if ('Web' == trim($item_arr[0]))
//            {
//                return $item_arr[1];
//            }
//        }
        $version = exec("git describe 2>/dev/null");
        if (empty($version)) $version = '';
        return $version;
    }

    public function git_info(){
        $cmd = "git log --pretty=\"%H\" -n1 HEAD";
        $gitID = shell_exec($cmd);
        $cmd = "git log --pretty=\"%ci\" -n1 HEAD";
        $date = shell_exec($cmd);
        if($date){
            $date = explode(' ', $date);
        }
        return $date[0] ? $date[0].'_'.$gitID : $gitID;
    }

    #核查url权限

    function check_login_redirect()
    {
        if (empty($_SESSION['login_type']))
        {
            $controller = new AppController();
            $controller->redirect('/homes/login');
        }
    }

    function check_sip_capture_exists($cdr_id)
    {
        $status = array('ingress' => false, 'egress' => false, 'ingress_rtp' => false, 'egress_rpt' => false);
        $model = new Cdr();
        $query = "SELECT origination_call_id, termination_call_id , case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end as time,origination_source_host_name, origination_profile_port  FROM client_cdr".date("Ymd")." WHERE id = {$cdr_id};";
        $result = $model->query($query);
        $time = $result[0][0]['time'];
        $date_path = date("Y/m/d/H/", strtotime($time));
        $sql = "select sip_capture_path from switch_profile where sip_ip = '{$result[0][0]['origination_source_host_name']}' and sip_port = {$result[0][0]['origination_profile_port']}";
        $switch_profiler_result = $model->query($sql);
        if (empty($switch_profiler_result))
            return $status;
        $monitor_path = $switch_profiler_result[0][0]['sip_capture_path'];

        $real_path = $monitor_path . DS . 'sip_capture' . DS . $date_path;
        $real_rtp_path = $monitor_path . DS . 'rtp_capture' . DS . $date_path;

        if (file_exists($real_path))
        {
            $include_text = '*' . $result[0][0]['origination_call_id'] . "*";
            $file = $this->searchfile($real_path, $include_text);
            if ($file)
            {
                $status['ingress'] = true;
                $file_path_info = pathinfo(trim($file));
                $include_text = $file_path_info['filename'] . "*";
                $file = $this->searchfile($real_rtp_path, $include_text);
                if ($file)
                    $status['ingress_rtp'] = true;
            }
            $include_text = '*' . $result[0][0]['termination_call_id'] . "*";
            $file = $this->searchfile($real_path, $include_text);
            if ($file)
            {
                $status['egress'] = true;
                $file_path_info = pathinfo(trim($file));
                $include_text = $file_path_info['filename'] . "*";
                $file = $this->searchfile($real_rtp_path, $include_text);
                if ($file)
                    $status['egress_rpt'] = true;
            }
        }
        return $status;
    }

    function searchfile($path, $pattern)
    {
        $cmd = "find {$path} -name '{$pattern}'";
        $result = shell_exec($cmd);
        return $result;
    }

    function cutomer_cdr_field_db($f, $v)
    {
        $model = new Cdr ();
        if (($f == 'time' || $f == 'answer_time_of_date' || $f == 'release_tod' || $f == 'start_time_of_date') && $v)
        {
            $tz = $model->get_sys_timezone();
            if (isset($_GET['query']['tz']))
            {
                $tz = $_GET ['query']['tz'];
            }
            return gmt_to_local_time($v, $tz) . " " . $tz;
        }
        if ($f == 'ingress_client_id')
        {
            if (exchange_isNumber($v))
            {
                $list = $model->query("select name from client where client_id=$v");
                return $list[0][0]['name'];
            }
            else
            {
                return $v;
            }
        }
        else
        {
            return $v;
        }
    }

    function cutomer_cdr_field($f, $v)
    {
        $model = new Cdr ();
        $time_arr = array(
            'start_time_of_date',
            'release_tod',
            'answer_time_of_date',
        );
        if ($f == 'time')
        {
            $tz = $model->get_sys_timezone();
            if (isset($_GET['query']['tz']))
            {
                $tz = $_GET ['query']['tz'];
            }
            return gmt_to_local_time($v, $tz) . " " . $tz;
        }
        if ($f == 'ingress_client_id')
        {
            if (exchange_isNumber($v))
            {
                $list = $model->query("select name from client where client_id=$v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'egress_client_id')
        {
            if (exchange_isNumber($v))
            {
                $list = $model->query("select name from client where client_id=$v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'ingress_id')
        {
            if (is_int($v))
            {
                $list = $model->query("select alias from resource where resource_id=$v and ingress = true");
                $name = isset($list[0][0]['alias']) ? $list[0][0]['alias'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'egress_id')
        {
            if (is_int($v))
            {
                $list = $model->query("select alias from resource where resource_id=$v and egress = true");
                $name = isset($list[0][0]['alias']) ? $list[0][0]['alias'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'static_route')
        {
            if ($v && intval($v))
            {
                $list = $model->query("select name from product where product_id =$v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'egress_rate_table_id')
        {
            if (intval($v))
            {
                $list = $model->query("select name from rate_table where rate_table_id = $v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'egress_rate_type' || $f == 'ingress_rate_type')
        {
            $rate_type_arr = array(
                0 => 'All',
                1 => 'INTER',
                2 => 'INTRA',
                3 => 'OTHER',
                4 => 'ERROR',
                5 => 'LOCAL',
            );
            $rate_type = isset($rate_type_arr[$v]) ? $rate_type_arr[$v] : $v;
            return $rate_type;
        }

        if ($f == 'ingress_client_bill_result')
        {
            $bill_result_arr = array(
                0 => '--',
                1 => 'Success',
                2 => 'Calls with unknown clients',
                3 => 'Rate table not found',
                4 => 'Rate not found',
                5 => 'Calls with unknown destinations',
            );
            $bill_result = isset($bill_result_arr[$v]) ? $bill_result_arr[$v] : $v;
            return $bill_result;
        }
        if ($f == 'egress_bill_result')
        {
            $bill_result_arr = array(
                0 => '--',
                1 => 'Success',
                2 => 'Account number not found',
                3 => 'Rate table not found',
                4 => 'Rate not found',
            );
            $bill_result = isset($bill_result_arr[$v]) ? $bill_result_arr[$v] : $v;
            return $bill_result;
        }
        if ($f == 'ingress_client_rate_table_id')
        {
            if (intval($v))
            {
                $list = $model->query("select name from rate_table where rate_table_id = $v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'route_plan')
        {
            if (intval($v))
            {
                $list = $model->query("select name from route_strategy where route_strategy_id = $v");
                $name = isset($list[0][0]['name']) ? $list[0][0]['name'] : $v;
                return $name;
            }
            else
            {
                return $v;
            }
        }
        if ($f == 'lrn_number_vendor')
        {
            $lrn_source_arr = array(
                0 => 'dnis',
                1 => 'client',
                2 => 'lrn server',
                3 => 'cache',
            );
            $lrn_source = isset($lrn_source_arr[$v]) ? $lrn_source_arr[$v] : 'other';
            return $lrn_source;
        }
        if (in_array($f, $time_arr))
        {
            $time = intval($v / 1000000);
            return date('Y-m-d H:i:sO', $time);
        }
        else
        {
            return $v;
        }
    }

    public function convertBool($val){
        $return = '';
        if ($val == '')
            return '';

        if($val == '1'){
            $return = 'Yes';
        }elseif($val == '0'){
            $return = 'No';
        }
        return $return;
    }

    function convert_error($val)
    {
        $return = '';
        if ($val == '')
            return '';
        $type1 = array(
            '0' => 'LRN Static Routing',
            '1' => 'DNIS Static Routing',
            '2' => 'LRN Dynamic Routing',
            '3' => 'DNIS Dynamic Routing',
        );
        $type2 = array(
            '4' => 'No Route',
            '5' => 'No Tech Prefix',
            '6' => 'No Route Trunk',
            '7' => 'No Route Egress',
            '8' => 'Block by time profile',
            '9' => 'Normal',
        );
        $type3 = array(
            '10' => 'Loop Detected',
            '11' => 'Egress Not Found',
            '12' => 'Not Egress Trunk',
            '13' => 'Trunk Disabled',
            '14' => 'No Host Found',
            '15' => 'LRN Block',
            '16' => 'T38 Reject',
            '17' => 'Prefix Blocked',
            '18' => 'Media Negotiation Failure',
            '19' => 'Rate Not Found',
            '20' => 'Rate is Empty',
            '21' => 'Rate Overflow',
            '22' => 'Normal',
            '23' => 'Get LRN Number Failure',
            '24' => 'Block Trunk Alert',
            '25' => 'Block Trunk Host Alert',
            '26' => 'Block Trunk Alert',
            '27' => 'LRN Block Higher Rate',
            '28' => 'Block Min ASR',
            '29' => 'Block Max ASR',
            '30' => 'Block Min ACD',
            '31' => 'Block Max ACD',
            '32' => 'Block Price Limit',
            '33' => 'Block Host Limit',
            '34' => 'Block Trunk CAP',
            '35' => 'Block Trunk CPS',
            '36' => 'Block Carrier CAP',
            '37' => 'Block Carrier CPS',
            '38' => 'Block Egress ANI',
            '39' => 'Block Egress DNIS',
            '40' => 'Block All Egress',
            '41' => 'Block Global ANI',
            '42' => 'Block Global DNIS',
            '43' => 'Block Global',
            '44' => 'Block Partition CAP',
            '45' => 'Block Partition CPS',
            '46' => 'Media Server Unavailable',
            '47' => 'Ignore SIP Profile LCR',
            '48' => 'Block Resouce ANI',
            '49' => 'Block Resouce DNIS',
            '50' => 'Block All Resouce',
            '51' => 'Balance Use Up',
            '52' => 'Block Egress and Ingress CAP',
            '53' => 'Block Egress and Ingress CPS',
            '54' => 'Block Egress ANI CPS',
            '55' => 'Block Egress ANI CAP',
            '56' => 'Block Egress DNIS CPS',
            '57' => 'Block Egress DNIS CAP',
        );
        //echo $val. "<br />";
        $all = array();
        $shuxian = explode('|', $val);
        foreach ($shuxian as $val)
        {
            $val = str_replace(';',',',$val);
            if (strpos($val, ','))
            {
                $arr = explode(',', $val);
                $first = explode(':', $arr[0]);
                $return .= $type1[$first[0]] . ':';
                $return .= $type2[$first[1]];
                for ($i = 1; $i < count($arr); $i++)
                {
                    $temp = explode(':', $arr[$i]);
                    $model = new Cdr ();
                    $resource_id = intval($temp[0]);
                    $alias = $model->query("SELECT alias FROM resource where resource_id = $resource_id");
                    //echo "----".$alias[0][0]['alias']."----";
                    $return .= ',' . (!empty($alias) ? $alias[0][0]['alias'] : 'None').':' ;
                    if (isset($temp[1]))
                        $return .= isset($type3[$temp[1]]) ? $type3[$temp[1]] : " ";
                    else
                        $return .= ' ';
                }
            }
            else
            {
                $first = explode(':', $val);
                if (isset($type1[$first[0]]))
                {
                    $return .= $type1[$first[0]] . ':';
                    $return .= $type2[$first[1]];
                }
                else
                {
                    $return = "";
                }
            }
            //echo "++++++++++++" . $return . "++++++++++++++++";
            array_push($all, $return);
            $return = '';
        }
        return implode('|', $all);
    }

    function convert_dnis_type($val)
    {

        switch ($val)
        {
            case "0":
                return "dnis";
            case "1":
                return "lrn";
            case "2":
                return "lrn block";
        }
    }

    function convert_rate_type($val)
    {

        switch ($val)
        {
            case "1":
                return "inter";
            case "2":
                return "intra";
            case "3":
                return "others";
        }
    }

    function redirect_permission()
    {

        //Configure::write('debug',0);
        if ($this->params['controller'] == 'homes')
        {
            return;
        }
        if (isset($_SESSION['role_menu']))
        {
            extract($_SESSION['role_menu']);


            #management menu
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'index')
            {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'add')
            {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'edit')
            {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'carrier')
            {
                $this->_empty_redirect($is_carriers);
            }


            if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_tran_view')
            {
                $this->_empty_redirect($is_transaction);
            }
            if ($this->params['controller'] == 'clientpayments' && $this->params['action'] == 'add_payment')
            {
                $this->_empty_redirect($is_transaction);
            }


            if ($this->params['controller'] == 'clientmutualsettlements' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_mutual_settlements);
            }
            if ($this->params['controller'] == 'invoices')
            {
                $this->_empty_redirect($is_invoices);
            }


            if ($this->params['plugin'] == 'pr' && $this->params['controller'] == 'pr_invoices' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_invoices);
            }

            if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_pay_view')
            {
                $this->_empty_redirect($is_payment);
            }
            if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment')
            {
                $this->_empty_redirect($is_payment);
            }
            if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment_one')
            {
                $this->_empty_redirect($is_payment);
            }



            if ($this->params['controller'] == 'bills' && $this->params['action'] == 'summary')
            {
                $this->_empty_redirect($is_unpaid_bills);
            }
            #report menu
            if ($this->params['controller'] == 'cdrreports' && $this->params['action'] == 'summary_reports' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'spam')
            {
                $this->_empty_redirect($is_spam_report);
            }
            if ($this->params['controller'] == 'locationreports')
            {
                $this->_empty_redirect($is_location_report);
            }
            if ($this->params['controller'] == 'Locationreports')
            {
                $this->_empty_redirect($is_location_report);
            }
            if ($this->params['controller'] == 'origtermstatis' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_origterm);
            }
            if ($this->params['controller'] == 'clientsummarystatis' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_summary_report);
            }
            if ($this->params['controller'] == 'ratereports' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_usage_report);
            }
            if ($this->params['controller'] == 'cdrreports')
            {

                $this->_empty_redirect($is_cdr_list);
            }
            if ($this->params['controller'] == 'monitorsreports')
            {
                $this->_empty_redirect($is_qos_report);
            }


            if ($this->params['controller'] == 'disconnectreports' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_disconnect_cause);
            }
            if ($this->params['controller'] == 'mismatchesreports' && $this->params['action'] == 'mismatches_report')
            {
                $this->_empty_redirect($is_billing_mismatch);
            }
            if ($this->params['controller'] == 'realcdrreports' && $this->params['action'] == 'summary_reports')
            {
                $this->_empty_redirect($is_active_call);
            }
            if ($this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'egress_report')
            {
                $this->_empty_redirect($is_termination_report);
            }
            #tools menu
            if ($this->params['controller'] == 'simulatedcalls' && $this->params['action'] == 'simulated_call')
            {
                $this->_empty_redirect($is_call_simulation);
            }
            if ($this->params['controller'] == 'testdevices' && $this->params['action'] == 'test_device')
            {
                $this->_empty_redirect($is_ingress_trunk_simulation);
            }
            if ($this->params['controller'] == 'audiotests' && $this->params['action'] == 'audio_test')
            {
                $this->_empty_redirect($is_egress_trunk_simulation);
            }
            if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'sip_capture')
            {
                $this->_empty_redirect($is_sip_capture);
            }
            if ($this->params['controller'] == 'analysis')
            {
                $this->_empty_redirect($is_rates_analysis);
            }

            #routeing menu
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'translation_details')
            {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'add_tran_detail')
            {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'digit_translation')
            {
                $this->_empty_redirect($is_digit_mapping);
            }

            if ($this->params['controller'] == 'gatewaygroups')
            {
                $this->_empty_redirect($is_trunk);
            }
            if ($this->params['plugin'] == 'prresource' && $this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'view_egress')
            {
                $this->_empty_redirect($is_trunk);
            }

            if ($this->params['controller'] == 'dynamicroutes' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_dynamic_routing);
            }


            if ($this->params['controller'] == 'products')
            {
                $this->_empty_redirect($is_static_route_table);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'static_route')
            {
                $this->_empty_redirect($is_static_route_table);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'product_item')
            {
                $this->_empty_redirect($is_static_route_table);
            }

            if ($this->params['controller'] == 'blocklists' && $this->params['action'] == 'index')
            {
                $this->_empty_redirect($is_block_list);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'block_list')
            {
                $this->_empty_redirect($is_block_list);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'block_list')
            {
                $this->_empty_redirect($is_block_list);
            }

            if ($this->params['controller'] == 'routestrategys')
            {
                $this->_empty_redirect($is_routing_plan);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'route_plan')
            {
                $this->_empty_redirect($is_routing_plan);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'route_plan')
            {
                $this->_empty_redirect($is_routing_plan);
            }
            #switch menu
            if ($this->params['controller'] == 'websessions' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_active_web_session);
            }
            if ($this->params['controller'] == 'servicecharges')
            {
                $this->_empty_redirect($is_service_charge);
            }
            if ($this->params['controller'] == 'paymentterms' && $this->params['action'] == 'payment_term')
            {
                $this->_empty_redirect($is_payment_term);
            }
            if ($this->params['controller'] == 'jurisdictionprefixs' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_jurisdiction);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'jur_country')
            {
                $this->_empty_redirect($is_jurisdiction);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'jur_country')
            {
                $this->_empty_redirect($is_jurisdiction);
            }

            if ($this->params['controller'] == 'systemlimits' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_capicity);
            }
            if ($this->params['controller'] == 'clientrates')
            {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'rates' && $this->params['action'] == 'rates_list')
            {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'rate')
            {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'rate')
            {
                $this->_empty_redirect($is_rate_table);
            }


            if ($this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'add_server')
            {
                $this->_empty_redirect($is_voip_gateway);
            }

            if ($this->params['controller'] == 'codedecks')
            {
                $this->_empty_redirect($is_code_deck);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'code_deck')
            {
                $this->_empty_redirect($is_code_deck);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'code_deck')
            {
                $this->_empty_redirect($is_code_deck);
            }


            if ($this->params['controller'] == 'timeprofiles' && $this->params['action'] == 'profile_list')
            {
                $this->_empty_redirect($is_time_profile);
            }
            if ($this->params['controller'] == 'timeprofiles' && $this->params['action'] == 'add_profile')
            {
                $this->_empty_redirect($is_time_profile);
            }

            if ($this->params['controller'] == 'currs' && $this->params['action'] == 'index')
            {
                $this->_empty_redirect($is_currency);
            }
            if ($this->params['controller'] == 'rates' && $this->params['action'] == 'currency')
            {
                $this->_empty_redirect($is_currency);
            }
            if ($this->params['controller'] == 'schedulers' && $this->params['action'] == 'schedulers_list')
            {
                $this->_empty_redirect($is_task_schedulers);
            }
            if ($this->params['controller'] == 'systems' && $this->params['action'] == 'trouble_shoot')
            {
                $this->_empty_redirect($is_trouble_shoot);
            }
            if ($this->params['controller'] == 'mailtmps' && $this->params['action'] == 'mail')
            {
                $this->_empty_redirect($is_mail_template);
            }
            #configure menu

            if ($this->params['controller'] == 'roles')
            {
                $this->_empty_redirect($is_role);
            }
            if ($this->params['controller'] == 'users')
            {
                $this->_empty_redirect($is_user);
            }
            if ($this->params['controller'] == 'eventlogs' && $this->params['action'] == 'events_list')
            {
                $this->_empty_redirect($is_event);
            }
            if ($this->params['controller'] == 'users' && $this->params['action'] == 'changepassword')
            {
                $this->_empty_redirect($is_change_password);
            }
            if ($this->params['controller'] == 'systemparams' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_setting);
            }
            if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'import')
            {
                $this->_empty_redirect($is_import_log);
            }
            if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'export')
            {
                $this->_empty_redirect($is_export_log);
            }
            if ($this->params['controller'] == 'cdrbackups' && $this->params['action'] == 'backup')
            {
                $this->_empty_redirect($is_cdr_backup);
            }
            if ($this->params['controller'] == 'lrnsettings' && $this->params['action'] == 'view')
            {
                $this->_empty_redirect($is_lrn_setting);
            }

            #buy  menu 
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'sell')
            {
                $this->_empty_redirect($is_buy_select_country);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'private_buy')
            {
                $this->_empty_redirect($is_search_private_buy);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_contracts' && $this->params['action'] == 'manage' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'Buy')
            {
                $this->_empty_redirect($is_buy_confirm_order);
            }

            #sell menu
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'buy')
            {
                $this->_empty_redirect($is_sell_select_country);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'private_sell')
            {
                $this->_empty_redirect($is_search_private_sell);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_contracts' && $this->params['action'] == 'manage' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'Sell')
            {
                $this->_empty_redirect($is_sell_confirm_order);
            }
        }
    }

    function format_class_use_name($str)
    {
        return str_replace(' ', '', ucwords(preg_replace('/[^0-9a-zA-Z]/', ' ', (string) $str)));
    }

    function format_wdatepicker_date($time)
    {
        if (!empty($time))
        {
            $time = $this->time->fromString($time);
            return date('Y-m-d', $time);
        }
        else
        {
            return '';
        }
    }

    function _get($key,$default = null)
    {
        $re = array_keys_value($this->params, 'url.' . $key, $default);
        if (is_array($re))
            return $default;
        return $re;
    }

    function precision($number, $precision = 3)
    {
        return sprintf("%01.{$precision}f", $number);
    }

    function to_readable_size($size)
    {
        $size = (float) $size;
        switch (true)
        {
            case $size <= 24:
                return 'Empty';
            case $size < 1024:
                return sprintf(__n('%d Byte', '%d Bytes', $size, true), $size);
            case round($size / 1024) < 1024:
                return sprintf(__('%d KB', true), $this->precision($size / 1024, 0));
            case round($size / 1024 / 1024, 2) < 1024:
                return sprintf(__('%.2f MB', true), $this->precision($size / 1024 / 1024, 2));
            case round($size / 1024 / 1024 / 1024, 2) < 1024:
                return sprintf(__('%.2f GB', true), $this->precision($size / 1024 / 1024 / 1024, 2));
            default:
                return sprintf(__('%.2f TB', true), $this->precision($size / 1024 / 1024 / 1024 / 1024, 2));
        }
    }

    function filter_date_range($name, $between = '', $options = Array())
    {
        return 'Start Time' . $this->form->input("", array('value' => $this->format_wdatepicker_date(array_keys_value($this->params, "url.filter_range_t_{$name}_start")),
                'name' => "filter_range_t_{$name}_start",
                'div' => false,
                'onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});",
                'class' => "input in-select wdate", 'readonly' => true, 'label' => false,
                'div' => false, 'type' => 'text', 'style' => "width:70px;height:19px")) . $between .
            'End Time' . $this->form->input("", array('value' => $this->format_wdatepicker_date(array_keys_value($this->params, "url.filter_range_t_{$name}_end")),
                'name' => "filter_range_t_{$name}_end",
                'onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});",
                'class' => "input in-select wdate", 'readonly' => true, 'label' => false,
                'div' => false, 'type' => 'text', 'style' => "width:70px;height:19px"));
    }

    function filter_date_range_ti($name, $between = '')
    {
        return 'Start Time' . $this->form->input("", array('value' => $this->format_wdatepicker_date(array_keys_value($this->params, "url.filter_range_ti_{$name}_start")),
                'name' => "filter_range_ti_{$name}_start",
                'onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});",
                'class' => "input in-select wdate", 'readonly' => true, 'label' => false,
                'div' => false, 'type' => 'text', 'style' => "width:100px")) . $between .
            'End Time' . $this->form->input("", array('value' => $this->format_wdatepicker_date(array_keys_value($this->params, "url.filter_range_ti_{$name}_end")),
                'name' => "filter_range_ti_{$name}_end",
                'onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});",
                'class' => "input in-select wdate", 'readonly' => true, 'label' => false,
                'div' => false, 'type' => 'text', 'style' => "width:100px"));
    }

    function filter_search($name, $options = Array())
    {
        return $this->form->input("", array_merge(array('value' => array_keys_value($this->params, "url.filter_search_$name"), 'label' => false, 'div' => false, 'type' => "text", 'name' => "filter_search_$name", 'class' => 'in-search default-value input in-text defaultText'), $options));
    }

    function auto_load_js()
    {
        $plugin_name = $this->format_class_use_name($this->params ['plugin']);
        $controller_name = $this->format_class_use_name($this->params ['controller']);
        $action_name = $this->format_class_use_name($this->params ['action']);
        if (!empty($controller_name) && !empty($action_name))
        {
            if (empty($plugin_name))
            {
                return <<<EOD
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		if(typeof App != 'undefined' && typeof App.{$controller_name} != 'undefined' && typeof App.{$controller_name}.on{$action_name}Load == 'function'){
				App.{$controller_name}.on{$action_name}Load();
		}
	});
})(jQuery);	    
</script>
EOD;
            }
            else
            {
                return <<<EOD
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		if(typeof App != 'undefined' && typeof App.{$plugin_name} != 'undefined' && typeof App.{$plugin_name}.{$controller_name} != 'undefined' && typeof App.{$plugin_name}.{$controller_name}.on{$action_name}Load == 'function'){
				App.{$plugin_name}.{$controller_name}.on{$action_name}Load();
		}
	});
})(jQuery);	    
</script>
EOD;
            }
        }
        return '';
    }

    function _base_path($action)
    {
        $base_path = $this->webroot;
        if (!empty($this->params ['plugin']))
        {
            $base_path .= $this->params ['plugin'] . '/';
        }
        if (!empty($this->params ['controller']))
        {
            $base_path .= $this->params ['controller'] . '/';
        }
        $base_path .= $action;
        return $base_path;
    }

    function _request_string($filter = null)
    {
        $params = $this->params['url'];
        unset($params['url']);
        unset($params['ext']);
        if (!empty($filter))
        {
            if (is_array($filter))
            {
                foreach ($filter as $key => $value)
                {
                    unset($params[$value]);
                }
            }
            else
            {
                unset($params[$filter]);
            }
        }
        return http_build_query($params);
    }

    #取出?query[id_clients]=156&viewtype=client

    function get_request_str()
    {
        return $this->_request_string();
    }

    function _request_hidden_input($filter = null)
    {
        $params = $this->params['url'];
        unset($params['url']);
        unset($params['ext']);
        if (!empty($filter))
        {
            unset($params[$filter]);
        }
        $html = '';
        foreach ($params as $name => $value)
        {
            if (is_array($value))
            {
                foreach ($value as $k2 => $v2)
                {
                    $html .= "<input type='hidden' name='$k2' value='$v2'/>";
                }
            }
            $html .= "<input type='hidden' name='$name' value='$value'/>";
        }
        return $html;
    }

    /**
     *
     *
     *
     *
     * @param $name  order field
     * @param $content
     */
    function show_order($name, $content = null)
    {
        if (empty($content))
        {
            $content = Inflector::humanize($name);
        }
        $base_href = $this->params['url']['url'];
        $order_by = isset($this->params['url']['order_by']) ? $this->params['url']['order_by'] : "";
        $request_string = $this->_request_string('order_by');
//        $desc_img = "{$this->webroot}images/p.png";
//        $asc_img = "{$this->webroot}images/p.png";
        $web_root = $this->webroot;

        //$return_str = "<span>$content</span>";

        if (!strcmp($order_by, "{$name}-asc"))
        {
            $return_str ="<a title='Sort' class='sorting_asc btn btn-primary btn-icon sustom_sort' href='" . $web_root . $base_href . "?order_by=" . $name . "-desc&" . $request_string . "'>{$content}</a>";
        }
        elseif (!strcmp($order_by, "{$name}-desc"))
        {
            $return_str ="<a title='Sort' class='sorting_desc btn btn-primary btn-icon sustom_sort' href='" . $web_root . $base_href . "?order_by=" . $name . "-asc&" . $request_string . "'>{$content}</a>";
        }
        else
        {
            $return_str ="<a  title='Sort' class='sorting btn btn-primary btn-icon sustom_sort' href='" . $web_root . $base_href . "?order_by=" . $name . "-desc&" . $request_string . "'>{$content}</a>";
        }
//        return <<<EOD
//		<span>$content</span>
//        <a class="sort_asc sort_sctive btn-primary btn-icon" href="{$web_root}{$base_href}?order_by={$name}-asc&{$request_string}"><i class='icon-long-arrow-down'></i></a>
//		
//		<a class="sort_dsc btn-primary btn-icon" href="{$web_root}{$base_href}?order_by={$name}-desc&{$request_string}"><i class='icon-long-arrow-up'></i></a>
//EOD;
        return $return_str;
    }

    function base_href()
    {
        return $this->params['url']['url'];
    }

    function get_curr_url()
    {
        $r_s = $this->_request_string();
        if (!empty($r_s))
        {
            $url = ($this->here) . "?" . ($this->_request_string());
        }
        else
        {
            $url = ($this->here);
        }
        return $url;
    }

    function set_back_url()
    {
        $url = $this->get_curr_url();
        $last_url = '';
        $curr_url = '';
        #第一次访问的url;
        if (!isset($_SESSION['back_url_arr']))
        {
            $last_url = $url;
            $curr_url = $url;
            $_SESSION['back_url_arr'] = compact($last_url, $curr_url);
        }
        else
        {
            #第2次访问的url;
            extract($_SESSION['back_url_arr']);
            #访问新页面
            if ($last_url != $url)
            {
                $curr_url = $url;
            }
            else
            {
                $last_url = $curr_url;
                $curr_url = $url;
            }

            $_SESSION['back_url_arr'] = compact($last_url, $curr_url);
        }
    }

    function show_back_href()
    {
        $img = "{$this->webroot}images/icon_back_white.png";
        $url = @$_SESSION['back_url'];
        return <<<EOD
	<a class="link_back btn btn-default btn-icon glyphicons left_arrow"  href="{$url}"><i></i> Back</a>
EOD;
    }

#生成分页隐藏域

    function show_page_hidden()
    {
        $page = (isset($_GET['page'])) ? $_GET['page'] : 0;
        $size = (isset($_GET['size'])) ? $_GET['size'] : 100;
        return <<<EOD
								<input   type="hidden"   id="exchange_page"  value="{$page}"    name="page"/>
								<input   type="hidden"   id="exchange_size"   value="{$size}"    name="size"/>
EOD;
    }

#显示货币

    function show_sys_curr()
    {
        extract($_SESSION['system_currency']);
        if (isset($_GET['currency']) && !empty($_GET['currency']))
        {
            $arr = explode('_', $_GET['currency']);
            $currency = $arr[1];
        }
        else
        {
            $currency = $sys_currency;
        }

        return <<<EOD
								({$currency})
EOD;
    }

#币率转换

    function currency_rate_conversion($cost)
    {

        if (empty($cost))
        {
            return 0.00000;
        }
        if (!ereg("^[0-9]+\.[0-9]+$", $cost))
        {
            //echo "ww";
            return $cost;
        }

        if ((isset($_GET['currency']) && !empty($_GET['currency'])))
        {
            $arr = explode('_', $_GET['currency']);
            $rate = $arr[0];
            $sys_currency_rate = $arr[2];
            //$rate=$currency;
            //return $rate;
            $t = $sys_currency_rate * $cost;
            if (empty($t))
            {
                return 0.00000;
            }
            else
            {
                return number_format($rate / $sys_currency_rate * $cost, 5);
            }
        }
        else
        {
            return number_format($cost, 5);
        }
    }

    /**
     *
     * 搜索结束后将搜索的条件，写回表单
     */
    function show_search_value()
    {
        $js = '';
        foreach ($_GET as $key => $value)
        {
            $js.="$('#{$key}').val('{$value}');\n";
        }
        return <<<EOD
							<script>	{$js}</script>
EOD;
    }

#填写表单值

    function show_form_value()
    {
        $model = new Cdr ();

        extract($model->get_ui_time());
        if ($report_count == '0')
            $smartPeriod = 'custom';
        else
            $smartPeriod = 'curDay';
        $start_date = $date;
        $start_time = $start;
        $stop_date = $date;
        $stop_time = $end;
        $tz = $model->get_sys_timezone();

        $period_select = '24h';
        $refresh_select = '3';
        $org_carrier_select = '';
        $term_carrier_select = '';
        $prefix = '';
        $cdr_release_cause = '';
        $asr_std_min = '';
        $asr_std_max = '';
        $asr_cur_min = '';
        $asr_cur_max = '';
        $search = '';
        $code_deck = '';
        $egress_alais = '';
        $ingress_alias = '';
        $currency = '';
        $client_type = '';
        $client_id = '';
        $client_id_term = '';
        $client_name_term = '';
        $reseller_id = '';
        $reseller_name = '';
        $card_id = '';
        $card_number = '';
        $client_name = '';
        $code = '';
        $code_name = '';
        $code_term = '';
        $code_name_term = '';
        $rate_id = '';
        $rate_name = '';



        $rate_id_term = '';
        $rate_name_term = '';

        $output = 'web';
        $serie_id = '';
        $serie_name = '';
        $batch_id = '';
        $batch_name = '';
        //group by 条件
        $group_by0 = '';
        $group_by1 = '';
        $group_by2 = '';
        $group_by3 = '';
        $group_by4 = '';
        $group_by5 = '';
        $query_fields = '';
        $interval_from = '';
        $interval_to = '';
        $dst_number = '';
        $orig_dnis_match = '';
        $orig_call_id = '';
        $term_call_id = '';
        $src_number = '';
        $res_status = '';
        $disconnect_cause = '';

        $res_status_ingress = '';
        $disconnect_cause_ingress = '';
        $query_cost = '';
        $query_duration = '';
        $query_country = '';
        $orig_src_number = '';
        $is_zero_call = '';
        $is_final_call = '';
        $server_ip = '';
        $report_type = '';
        $new_orig_rate_table = '';
        $new_term_rate_table = '';
        $query_country_term = '';
        $query_cost_term = '';
        $orig_host = '';
        $term_host = '';
        $term_src_number = '';
        $term_dst_number = '';

        $org_carrier_select = !empty($_GET ['orig_carrier_select']) ? $_GET ['orig_carrier_select'] : '';
        $term_carrier_select = !empty($_GET ['term_carrier_select']) ? $_GET ['term_carrier_select'] : '';
        if (isset($_SESSION ['refresh_select']))
        {
            $refresh_select = $_SESSION ['refresh_select'];
        }
        if (isset($_GET ['refresh_select']))
        {
            $refresh_select = $_GET ['refresh_select'];
        }
        if (isset($_SESSION ['period_select']))
        {
            $period_select = $_SESSION ['period_select'];
        }
        if (isset($_GET ['period_select']))
        {
            $period_select = $_GET ['period_select'];
        }
        if (isset($_GET ['prefix']))
        {
            $prefix = $_GET ['prefix'];
        }
        if (isset($_GET ['orig_host']))
        {
            $orig_host = $_GET ['orig_host'];
        }
        if (isset($_GET ['term_host']))
        {
            $term_host = $_GET ['term_host'];
        }

        if (isset($_GET ['cdr_release_cause']))
        {
            $cdr_release_cause = $_GET ['cdr_release_cause'];
        }
        if (isset($_GET ['report_type']))
        {
            $report_type = $_GET ['report_type'];
        }
        if (isset($_GET ['server_ip']))
        {
            $server_ip = $_GET ['server_ip'];
        }

        if (isset($_GET ['new_orig_rate_table']))
        {
            $new_orig_rate_table = $_GET ['new_orig_rate_table'];
        }
        if (isset($_GET ['new_term_rate_table']))
        {
            $new_term_rate_table = $_GET ['new_term_rate_table'];
        }

        if (isset($_GET ['query']['asr_std_min']))
        {
            $asr_std_min = $_GET ['query']['asr_std_min'];
        }
        if (isset($_GET ['query']['asr_std_max']))
        {
            $asr_std_max = $_GET ['query']['asr_std_max'];
        }
        if (isset($_GET ['query']['asr_cur_min']))
        {
            $asr_cur_min = $_GET ['query']['asr_cur_min'];
        }
        if (isset($_GET ['query']['asr_cur_max']))
        {
            $asr_cur_max = $_GET ['query']['asr_cur_max'];
        }

        if (isset($_GET ['code_deck']))
        {
            $code_deck = $_GET ['code_deck'];
        }
        if (isset($_GET ['egress_alias']))
        {
            $egress_alais = $_GET ['egress_alias'];
        }
        if (isset($_GET ['ingress_alias']))
        {
            $ingress_alias = $_GET ['ingress_alias'];
        }
        if (isset($_GET ['currency']))
        {
            $currency = $_GET ['currency'];
        }
        if (isset($_GET ['smartPeriod']))
        {
            $smartPeriod = $_GET ['smartPeriod'];
        }
        if (isset($_GET ['start_date']))
        {
            $start_date = $_GET ['start_date'];
        }
        if (isset($_GET ['stop_date']))
        {
            $stop_date = $_GET ['stop_date'];
        }
        if (isset($_GET ['start_time']))
        {
            $start_time = $_GET ['start_time'];
        }
        if (isset($_GET ['stop_time']))
        {
            $stop_time = $_GET ['stop_time'];
        }
        if (isset($_GET['query']['country']))
        {
            $query_country = $_GET ['query']['country'];
        }
        if (isset($_GET['orig_src_number']))
        {
            $orig_src_number = $_GET['orig_src_number'];
        }
        if (isset($_GET['is_zero_call']))
        {
            $is_zero_call = $_GET['is_zero_call'];
        }
        if (isset($_GET['is_final_call']))
        {
            $is_final_call = $_GET['is_final_call'];
        }
        if (isset($_GET['query']['country_term']))
        {
            $query_country_term = $_GET ['query']['country_term'];
        }

        if (isset($_GET['query']['duration']))
        {
            $query_duration = $_GET ['query']['duration'];
        }
        if (isset($_GET['query']['cost']))
        {
            $query_cost = $_GET ['query']['cost'];
        }
        if (isset($_GET['query']['cost_term']))
        {
            $query_cost_term = $_GET ['query']['cost_term'];
        }
        if (isset($_GET['query']['res_status']))
        {
            $res_status = $_GET ['query']['res_status'];
        }
        if (isset($_GET['query']['disconnect_cause']))
        {
            $disconnect_cause = $_GET ['query']['disconnect_cause'];
        }


        if (isset($_GET['query']['res_status_ingress']))
        {
            $res_status_ingress = $_GET ['query']['res_status_ingress'];
        }
        if (isset($_GET['query']['disconnect_cause_ingress']))
        {
            $disconnect_cause_ingress = $_GET ['query']['disconnect_cause_ingress'];
        }

        if (isset($_GET['query']['dst_number']))
        {
            $dst_number = $_GET ['query']['dst_number'];
        }
        if (isset($_GET['query']['orig_dnis_match']))
        {
            $orig_dnis_match = $_GET ['query']['orig_dnis_match'];
        }
        if (isset($_GET['query']['orig_call_id']))
        {
            $orig_call_id = $_GET ['query']['orig_call_id'];
        }
        if (isset($_GET['query']['term_call_id']))
        {
            $term_call_id = $_GET ['query']['term_call_id'];
        }

        if (isset($_GET['query']['src_number']))
        {
            $src_number = $_GET ['query']['src_number'];
        }
        if (isset($_GET['query']['interval_from']))
        {
            $interval_from = $_GET ['query']['interval_from'];
        }
        if (isset($_GET['query']['interval_to']))
        {
            $interval_to = $_GET ['query']['interval_to'];
        }
        if (isset($_GET['query']['tz']))
        {
            $tz = $_GET ['query']['tz'];
        }
        if (isset($_GET['query']['id_series']))
        {
            $serie_id = $_GET ['query']['id_series'];
        }
        if (isset($_GET['query']['id_series_name']))
        {
            $serie_name = $_GET ['query']['id_series_name'];
        }
        if (isset($_GET['query']['id_batchs_name']))
        {
            $batch_name = $_GET ['query']['id_batchs_name'];
        }
        if (isset($_GET['query']['id_batchs']))
        {
            $batch_id = $_GET ['query']['id_batchs'];
        }
        if (isset($_GET['query']['client_type']))
        {
            $client_type = $_GET['query']['client_type'];
        }
        if (isset($_GET['query']['id_clients']))
        {
            $client_id = $_GET['query']['id_clients'];
        }
        if (isset($_GET['query']['id_clients_name']))
        {
            $client_name = $_GET['query']['id_clients_name'];
        }
        if (isset($_GET['query']['id_clients_term']))
        {
            $client_id_term = $_GET['query']['id_clients_term'];
        }
        if (isset($_GET['query']['id_clients_name_term']))
        {
            $client_name_term = $_GET['query']['id_clients_name_term'];
        }
        if (isset($_GET['query']['code']))
        {
            $code = $_GET ['query']['code'];
        }
        if (isset($_GET['query']['code_name']))
        {
            $code_name = $_GET ['query']['code_name'];
        }
        if (isset($_GET['query']['code_term']))
        {
            $code_term = $_GET ['query']['code_term'];
        }
        if (isset($_GET['query']['code_name_term']))
        {
            $code_name_term = $_GET ['query']['code_name_term'];
        }
        if (isset($_GET['query']['output']))
        {
            $output = $_GET ['query']['output'];
        }
        if (isset($_GET['query']['id_rates']))
        {
            $rate_id = $_GET ['query']['id_rates'];
        }
        if (isset($_GET['query']['rate_name']))
        {
            $rate_name = $_GET ['query']['rate_name'];
        }


        if (isset($_GET['query']['term_src_number']))
        {
            $term_src_number = $_GET ['query']['term_src_number'];
        }
        if (isset($_GET['query']['term_dst_number']))
        {
            $term_dst_number = $_GET ['query']['term_dst_number'];
        }

        if (isset($_GET['query']['id_rates_term']))
        {
            $rate_id_term = $_GET ['query']['id_rates_term'];
        }
        if (isset($_GET['query']['rate_name_term']))
        {
            $rate_name_term = $_GET ['query']['rate_name_term'];
        }

        if (isset($_GET['group_by'][0]))
        {
            $group_by0 = $_GET ['group_by'][0];
        }
        if (isset($_GET['group_by'][1]))
        {
            $group_by1 = $_GET ['group_by'][1];
        }
        if (isset($_GET['group_by'][2]))
        {
            $group_by2 = $_GET ['group_by'][2];
        }
        if (isset($_GET['group_by'][0]))
        {
            $group_by0 = $_GET ['group_by'][0];
        }
        if (isset($_GET['group_by'][3]))
        {
            $group_by3 = $_GET ['group_by'][3];
        }
        if (isset($_GET['group_by'][4]))
        {
            $group_by4 = $_GET ['group_by'][4];
        }
        if (isset($_GET['group_by'][5]))
        {
            $group_by5 = $_GET ['group_by'][5];
        }
        $search = '';
        $search_rate = '';
        if (isset($_GET['search']) && !is_array($_GET['search']))
        {
            $search = $_GET ['search'];
        }
        if (isset($_GET['search']['_q']))
        {
            $search_rate = $_GET['search']['_q'];
        }


        return <<<EOD
<script>
$('#refresh_select').val('$refresh_select');
$('#period_select').val('$period_select');
$('#CdrOrigCarrierSelect').val('$org_carrier_select');
$('#CdrTermCarrierSelect').val('$term_carrier_select');
$('#prefix').val('$prefix');
$('#CdrOrigHost').val('$orig_host');
$('#CdrTermHost').val('$term_host');
$('#query-country').val('$query_country');
$('#query-country_term').val('$query_country_term');
$('#CdrNewOrigRateTable').val('$new_orig_rate_table');
$('#CdrNewTermRateTable').val('$new_term_rate_table');

			$('#CdrCdrReleaseCause').val('$cdr_release_cause');
			$('#CdrReportType').val('$report_type');
			$('#CdrServerIp').val('$server_ip');
			$('#query-asr_std_min').val('$asr_std_min');
			$('#query-asr_std_max').val('$asr_std_max');
			$('#query-asr_cur_min').val('$asr_cur_min');
			$('#query-asr_cur_max').val('$asr_cur_max');
			$('#CdrCodeDeck').val('$code_deck');
			$('#CdrIngressAlias').val('$ingress_alias');
			$('#CdrEgressAlias').val('$egress_alais');
			$('#query-smartPeriod').val('$smartPeriod');
	  	$('#CdrCurrency').val('$currency');
			$('#query-id_countrys').val('$query_country');
				$('#Country').val('$query_country');
			$('#query-cost').val('$query_cost');
			
			$('#query-cost_term').val('$query_cost_term');
			$('#query-duration').val('$query_duration');
			$('#query-res_status').val('$res_status');
			$('#query-disconnect_cause').val('$disconnect_cause');
			$('#query-res_status_ingress').val('$res_status_ingress');
			$('#query-disconnect_cause_ingress').val('$disconnect_cause_ingress');
			
			$('#query-dst_number').val('$dst_number');
			$('#query-orig_dnis_match').val('$orig_dnis_match');
			$('#query-orig_call_id').val('$orig_call_id');
			$('#query-term_call_id').val('$term_call_id');
			$('#query-src_number').val('$src_number');
                        $('#query-term_src_number').val('$term_src_number');
                        $('#query-term_dst_number').val('$term_dst_number');
			$('#query-interval_from').val('$interval_from');
			$('#query-interval_to').val('$interval_to');
			$('#search-_q').val('$search');
			$('#search-_q_rate').val('$search_rate');
			$('#query-tz').val('$tz');
			$('#query-start_date-wDt').val('$start_date');
			$('#query-start_time-wDt').val('$start_time');
			$('#query-stop_date-wDt').val('$stop_date');
			$('#query-stop_time-wDt').val('$stop_time');
			$('#query-client_type').val('$client_type');
			$('#query-id_clients').val('$client_id');
			$('#query-id_clients_name').val('$client_name');
			$('#query-id_clients_term').val('$client_id_term');
			$('#query-id_clients_name_term').val('$client_name_term');
			$('#query-id_resellers').val('$reseller_id');
			$('#query-id_resellers_name').val('$reseller_name');
			$('#query-code_name').val('$code_name');
			$('#orig_src_number').val('$orig_src_number');
			$('#is_zero_call').val('$is_zero_call');
			$('#is_final_call').val('$is_final_call');
			$('#code_name').val('$code_name');
			$('#query-code').val('$code');
			$('#code').val('$code');
			$('#query-code_name_term').val('$code_name_term');
			$('#query-code_term').val('$code_term');
			$('#query-output').val('$output');
			$('#query-id_rates_name').val('$rate_name');
			$('#query-id_rates').val('{$rate_id}');
			$('#query-id_rates_name_term').val('$rate_name_term');
			$('#query-id_rates_term').val('{$rate_id_term}');
			$('#CdrGroupBy1').val('$group_by0');
			$('#CdrGroupBy2').val('$group_by1');
			$('#CdrGroupBy3').val('$group_by2');
			$('#CdrGroupBy4').val('$group_by3');
			$('#CdrGroupBy5').val('$group_by4');
			$('#CdrGroupBy6').val('$group_by5');
			$("#query-start_date-wDt").click(function(){
                var max_date = $('#query-stop_date-wDt').val();
                WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:max_date});});
			$("#query-start_time-wDt").click(function(){
			WdatePicker({isShowToday:false, dateFmt:'HH:mm:ss',maxDate:['#F{WdateTime(1)}']});});
			$("#query-stop_date-wDt").click(function(){
				var min_date = $("#query-start_date-wDt").val();
				WdatePicker({dateFmt:'yyyy-MM-dd',minDate:min_date});});
			$("#query-stop_time-wDt").click(function(){
			WdatePicker({isShowToday:false, dateFmt:'HH:mm:ss',minDate:['#F{WdateTime(2)}']});});
			startWatchStopTime();

			function WdateTime(type){
                var start_date = $("#query-start_date-wDt").val();
                var end_date = $('#query-stop_date-wDt').val();
			    if (type == 1){
                    max_time = '23:59:59';
			        if (start_date == end_date){
                        max_time = $("#query-stop_time-wDt").val();
                    }
                    return max_time;
			    }else{
			        min_time = '00:00:00';
			        if (start_date == end_date){
                        min_time = $("#query-start_time-wDt").val();
                    }
                    return min_time;
			    }
			}
	</script>
EOD;
    }

    public function get_bar_color()
    {
        $model = new Cdr ();
        $sql = "SELECT bar_color FROM system_parameter LIMIT 1";
        $result = $model->query($sql);
        return $result[0][0]['bar_color'];
    }

    public function encrypt($plaintext)
    {
        # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
        # convert a string into a key
        # key is specified using hexadecimal
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        # show key size use either 16, 24 or 32 byte keys for AES-128, 192
        # and 256 respectively
        $key_size = strlen($key);
        //echo "Key size: " . $key_size . "\n";
        //$plaintext = "1";
        # create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential 
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);

        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;

        # encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);

        return $ciphertext_base64;
    }

    public function dencrypt($ciphertext_base64)
    {
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

        $ciphertext_dec = base64_decode($ciphertext_base64);

        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        # may remove 00h valued characters from end of plain text
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        return $plaintext_dec;
    }

    #Determine whether there is an array of keys

    public function _isset($array, $return = false)
    {
        $result = isset($array) ? $array : "";
        if (!$return)
        {
            echo $result;
        }
        else
        {
            return $result;
        }
    }



    //unpaid invoice
    function unpaid_invoice_number(){
        $user_id = $_SESSION['sst_user_id'];
        //$model = new AppController();
        $model = new Cdr ();
//
        $sql = "SELECT client_id FROM users WHERE user_id = {$user_id}";
        $client_id = $model->query($sql);
        $client_id = $client_id[0][0]['client_id'];


        //Unpaid Invoice
        $sql = "select count(*) as cnt from invoice where ( type = 0 or type = 2) and state = 9 and paid is not true
              and invoice.total_amount > 0 and  invoice.client_id = $client_id";
        $cnt = $model->query($sql);

        return $cnt[0][0]['cnt'];
    }

    //messages
    function messages_number(){
        $user_id = $_SESSION['sst_user_id'];
        //$model = new AppController();
        $model = new Cdr ();
//
        $sql = "SELECT client_id FROM users WHERE user_id = {$user_id}";
        $client_id = $model->query($sql);
        $client_id = $client_id[0][0]['client_id'];


        //
        $sql = "select count(*) as cnt from email_log where status != 1 and client_id = $client_id and (type= 9 or type= 21 or type=2) and is_view != 1";
        $cnt = $model->query($sql);

        return $cnt[0][0]['cnt'];
    }


    //alerts
    function alerts_number(){
        $user_id = $_SESSION['sst_user_id'];
        //$model = new AppController();
        $model = new Cdr();
//
        $sql = "SELECT client_id FROM users WHERE user_id = {$user_id}";
        $client_id = $model->query($sql);
        $client_id = $client_id[0][0]['client_id'];


        //
        $sql = "select count(*) as cnt from email_log where status != 1 and client_id = $client_id and (type= 1 or type= 22) and is_view != 1";
        $cnt = $model->query($sql);

        return $cnt[0][0]['cnt'];
    }

    function get_timezone_arr($only_two_char = false)
    {
        if ($only_two_char)
        {
            return array(
                '-12' => 'GMT -12:00',
                '-11' => 'GMT -11:00',
                '-10' => 'GMT -10:00',
                '-09' => 'GMT -09:00',
                '-08' => 'GMT -08:00',
                '-07' => 'GMT -07:00',
                '-06' => 'GMT -06:00',
                '-05' => 'GMT -05:00',
                '-04' => 'GMT -04:00',
                '-03' => 'GMT -03:00',
                '-02' => 'GMT -02:00',
                '-01' => 'GMT -01:00',
                '+00' => 'GMT +00:00',
                '+01' => 'GMT +01:00',
                '+02' => 'GMT +02:00',
                '+03' => 'GMT +03:00',
                '+03' => 'GMT +03:30',
                '+04' => 'GMT +04:00',
                '+05' => 'GMT +05:00',
                '+06' => 'GMT +06:00',
                '+07' => 'GMT +07:00',
                '+08' => 'GMT +08:00',
                '+09' => 'GMT +09:00',
                '+10' => 'GMT +10:00',
                '+11' => 'GMT +11:00',
                '+12' => 'GMT +12:00',
            );
        }
        else
        {
            return array(
                '-1200' => 'GMT -12:00',
                '-1100' => 'GMT -11:00',
                '-1000' => 'GMT -10:00',
                '-0900' => 'GMT -09:00',
                '-0800' => 'GMT -08:00',
                '-0700' => 'GMT -07:00',
                '-0600' => 'GMT -06:00',
                '-0500' => 'GMT -05:00',
                '-0400' => 'GMT -04:00',
                '-0300' => 'GMT -03:00',
                '-0200' => 'GMT -02:00',
                '-0100' => 'GMT -01:00',
                '+0000' => 'GMT +00:00',
                '+0100' => 'GMT +01:00',
                '+0200' => 'GMT +02:00',
                '+0300' => 'GMT +03:00',
                '+0330' => 'GMT +03:30',
                '+0400' => 'GMT +04:00',
                '+0500' => 'GMT +05:00',
                '+0600' => 'GMT +06:00',
                '+0700' => 'GMT +07:00',
                '+0800' => 'GMT +08:00',
                '+0900' => 'GMT +09:00',
                '+1000' => 'GMT +10:00',
                '+1100' => 'GMT +11:00',
                '+1200' => 'GMT +12:00',
            );
        }
    }

    function get_time_select()
    {
        return array(
            'custom' => __('custom', true),
            'curDay' => __('today', true),
            'prevDay' => __('yesterday', true),
            'curWeek' => __('currentweek', true),
            'prevWeek' => __('previousweek', true),
            'curMonth' => __('currentmonth', true),
            'prevMonth' => __('previousmonth', true),
            'curYear' => __('currentyear', true),
            'prevYear' => __('previousyear', true)
        );
    }

    function sub_string($string,$len = 20,$replace = '...')
    {
        $string = trim($string);
        if (strlen($string) <= intval($len))
            return $string;
        return substr($string,0,$len) . $replace;
    }


    public function get_session_data($key)
    {
        return $this->Session->read("$key");
    }

    public function get_response($code = '', $json = false)
    {
        $arr = array(
            "" => __('all',true) ,
            "200" => __('200success',true) ,
            "300" => __('300multiple',true) ,
            "301" => __('301moved_permanently',true) ,
            "302" => __('302moved_temporarily',true) ,
            "305" => __('305use_proxy',true) ,
            "380" => __('380alternative_service',true) ,
            "400" => __('400bad_request',true) ,
            "401" => __('401unauthorized',true) ,
            "402" => __('402payment_required',true) ,
            "403" => __('403forbidden',true) ,
            "404" => __('404not_found',true) ,
            "405" => __('405method_not_allowed',true) ,
            "406" => __('406not_acceptable',true) ,
            "407" => __('407proxy_authentication_required',true) ,
            "408" => __('408request_timeout',true) ,
            "410" => __('410gone',true) ,
            "413" => __('413request_entity_too_large',true) ,
            "414" => __('414request_url_too_long',true) ,
            "415" => __('415unsupported_media_type',true) ,
            "416" => __('416unsupported_url_scheme',true) ,
            "420" => __('420bad_extension',true) ,
            "421" => __('421extension_required',true) ,
            "423" => __('423interval_too_brief',true) ,
            "480" => __('480temporarily_unavailable',true) ,
            "481" => __('481call/transaction_does_not_exist',true) ,
            "482" => __('482loop_detected',true) ,
            "483" => __('483too_many_hops',true) ,
            "484" => __('484address_incomplete',true) ,
            "485" => __('485ambiguous',true) ,
            "486" => __('486busy_here',true) ,
            "487" => __('487request_terminated',true) ,
            "488" => __('488not_acceptable_here',true) ,
            "491" => __('491request_pending',true) ,
            "493" => __('493indecipherable',true) ,
            "500" => __('500server_internal error',true) ,
            "501" => __('501not_implemented',true) ,
            "502" => __('502bad_gateway',true) ,
            "503" => __('503service_unavailable',true) ,
            "504" => __('504server_time_out',true) ,
            "505" => __('505version_not_supported',true) ,
            "513" => __('513message_too_large',true) ,
            "600" => __('600busy_everywhere',true) ,
            "603" => __('603decline',true) ,
            "604" => __('604does_not_exist_anywhere',true) ,
            "606" => __('606not_acceptable',true) ,
        );
        if ($code)
            return isset($arr[$code]) ? $arr[$code] : '';
        return $json ? json_encode($arr) : $arr;
    }

    public function get_ftp_fields(){
        return array(
            "All" => "All" ,
            "200:OK" => "200:OK",
            "300" .":". __('300multiple',true) => "300" .":". __('300multiple',true),
            "301" .":". __('301moved_permanently',true) => "301" .":". __('301moved_permanently',true),
            "302" .":". __('302moved_temporarily',true) => "302" .":". __('302moved_temporarily',true),
            "305" .":". __('305use_proxy',true) => "305" .":". __('305use_proxy',true),
            "380" .":". __('305use_proxy',true) => "380" .":". __('305use_proxy',true),
            "400" .":". __('400bad_request',true) => "400" .":". __('400bad_request',true),
            "401" .":". __('401unauthorized',true) => "401" .":". __('401unauthorized',true),
            "402" .":". __('402payment_required',true) => "402" .":". __('402payment_required',true),
            "403" .":". __('403forbidden',true) => "403" .":". __('403forbidden',true),
            "404" .":". __('404not_found',true) => "404" .":". __('404not_found',true),
            "405" .":". __('405method_not_allowed',true) => "405" .":". __('405method_not_allowed',true),
            "406" .":". __('406not_acceptable',true) => "406" .":". __('406not_acceptable',true),
            "407" .":". __('407proxy_authentication_required',true) => "407" .":". __('407proxy_authentication_required',true),
            "408" .":". __('408request_timeout',true) => "408" .":". __('408request_timeout',true),
            "410" .":". __('410gone',true) => "410" .":". __('410gone',true),
            "413" .":". __('413request_entity_too_large',true) => "413" .":". __('413request_entity_too_large',true),
            "414" .":". __('414request_url_too_long',true) => "414" .":". __('414request_url_too_long',true),
            "415" .":". __('415unsupported_media_type',true) => "415" .":". __('415unsupported_media_type',true),
            "416" .":". __('416unsupported_url_scheme',true) => "416" .":". __('416unsupported_url_scheme',true),
            "420" .":". __('420bad_extension',true) => "420" .":". __('420bad_extension',true),
            "421" .":". __('421extension_required',true) => "421" .":". __('421extension_required',true),
            "423" .":". __('423interval_too_brief',true) => "423" .":". __('423interval_too_brief',true),
            "480" .":". __('480temporarily_unavailable',true) => "480" .":". __('480temporarily_unavailable',true),
            "481" .":". __('481call/transaction_does_not_exist',true) => "481" .":". __('481call/transaction_does_not_exist',true),
            "482" .":". __('482loop_detected',true) => "482" .":". __('482loop_detected',true),
            "483" .":". __('483too_many_hops',true) => "483" .":". __('483too_many_hops',true),
            "484" .":". __('484address_incomplete',true) => "484" .":". __('484address_incomplete',true),
            "485" .":". __('485ambiguous',true) => "485" .":". __('485ambiguous',true),
            "486" .":". __('486busy_here',true) => "486" .":". __('486busy_here',true),
            "487" .":". __('487request_terminated',true) => "487" .":". __('487request_terminated',true),
            "488" .":". __('488not_acceptable_here',true) => "488" .":". __('488not_acceptable_here',true),
            "491" .":". __('491request_pending',true) => "491" .":". __('491request_pending',true),
            "493" .":". __('493indecipherable',true) => "493" .":". __('493indecipherable',true),
            "500" .":". __('500server_internal error',true) => "500" .":". __('500server_internal error',true),
            "501" .":". __('501not_implemented',true) => "501" .":". __('501not_implemented',true),
            "502" .":". __('502bad_gateway',true) => "502" .":". __('502bad_gateway',true),
            "503" .":". __('503service_unavailable',true) => "503" .":". __('503service_unavailable',true),
            "504" .":". __('504server_time_out',true) => "504" .":". __('504server_time_out',true),
            "505" .":". __('505version_not_supported',true) => "505" .":". __('505version_not_supported',true),
            "513" .":". __('513message_too_large',true) => "513" .":". __('513message_too_large',true),
            "600" .":". __('600busy_everywhere',true) => "600" .":". __('600busy_everywhere',true),
            "603" .":". __('603decline',true) => "603" .":". __('603decline',true),
            "604" .":". __('604does_not_exist_anywhere',true) => "604" .":". __('604does_not_exist_anywhere',true),
            "606" .":". __('606not_acceptable',true) => "606" .":". __('606not_acceptable',true)
        );
    }

    public function get_hour_time_arr(){

        return array(
            '00:00',
            '01:00',
            '02:00',
            '03:00',
            '04:00',
            '05:00',
            '06:00',
            '07:00',
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00',
            '21:00',
            '22:00',
            '23:00',
        );
    }

    function xmlEscape($string) {
        return str_replace('&', '&amp;', $string);
    }

    function getUrl() {
        $port = $_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"];

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {

            $url = 'https://' . $_SERVER['SERVER_NAME']. $port . $this->webroot;
        }
        else
        {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $port . $this->webroot;
        }
        return $url;
    }

}
