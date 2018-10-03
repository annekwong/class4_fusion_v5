<?php

/**
 * 
 * @author weifeng
 * 	Daily Origination Usage Detail Report
 * 	Daily Termination Usage Detail Report 
 *
 */
class UsagedetailsController extends AppController
{

    var $name = 'Usagedetails';
    var $uses = array('Cdr', 'Cdrs', 'SwitchProfile');
    var $helpers = array('javascript', 'html', 'common');

    function index()
    {
        $this->redirect('orig_summary_reports');
    }

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if (1)
        {//($login_type==1){  
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function beforeRender()
    {
        $_SESSION['time2'] = time();
    }

    function search_exist($arr, $v1, $v2)
    {
        foreach ($arr as $key => $val)
        {
            if (in_array($v1, $val) && in_array($v2, $val))
            {

                return $key;
            }
        }
        return FALSE;
    }

    /**
     * 
     * @param type $arr  数组 
     * @param type $v1   判断 数组的值 
     * @param type $k1   判断 数组的键 
     * @return boolean
     * 
     * 
     * 如果数组 $arr下的二维数组 中 有个键为 $k1 的值为 $v1  则返回当前数组的KEY  否则为false 
     */
    function search_exist_key($arr, $v1, $k1)
    {
        foreach ($arr as $key => $val)
        {
            //if(in_array($v1, $val) && in_array($v2, $val)) {
            if ($val[$k1] == $v1)
            {
                return $key;
            }
        }
        return FALSE;
    }

    function orig_summary_reports()
    {
        $this->pageTitle = "Origination Usage Detail/Spam Report";
        $this->get_report_data('orig');
        $t = getMicrotime();

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('org_report_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('org_report_xls');
        }
    }

    function term_summary_reports()
    {
        $this->pageTitle = "Termination Usage Detail/Spam Report ";

        $this->get_report_data('term');
        $t = getMicrotime();

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('term_report_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('term_report_xls');
        }
    }

    function search_exist1($arr, $v)
    {
        foreach ($arr as $key => $val)
        {
            foreach ($val as $item)
            {
                if (in_array($v, $item))
                {
                    return $key;
                }
            }
        }
        return FALSE;
    }

    function daily_orig_summary()
    {
        $this->pageTitle = "Daily Origination Summary Report";
        $t = getMicrotime();
        $this->get_report_data('orig');
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('daily_orig_report_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('daily_orig_report_xls');
        }
    }

    function daily_term_summary()
    {
        $this->pageTitle = "Daily Termination Summary Report";
        $t = getMicrotime();
        $this->get_report_data('term');
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('daily_term_report_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('daily_term_report_xls');
        }
    }

    function group_time($start = null, $end = null)
    {
        $return = array();
        $start = empty($start) ? date("Y-m-d") : date("Y-m-d", strtotime($start));
        $end = empty($end) ? date("Y-m-d") : date("Y-m-d", strtotime($end));

        for ($i = strtotime($end); $i >= strtotime($start); $i -= 3600 * 24)
        {
            $return[] = date("Ymd", $i);
        }

        return $return;
    }

    function _download_impl($params = array())
    {

        Configure::write('debug', 0);
        extract($params);
        if ($this->Cdr->download_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

    function _download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

    public function get_report_data($type)
    {
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;
        $show_fields = array();
        $field = array();
        $get_data = $this->params['url'];
        if (isset($get_data['start_date']) && !empty($get_data['start_date']))
            $start_date = $get_data['start_date'] . ' ' . $get_data['start_time'];
        if (isset($get_data['stop_date']) && !empty($get_data['stop_date']))
            $end_date = $get_data['stop_date'] . ' ' . $get_data['stop_time'];
        if (isset($get_data['query']['tz']) && !empty($get_data['query']['tz']))
            $gmt = substr($get_data['query']['tz'], 0, 3);
        $start_date .= $gmt;
        $end_date .= $gmt;
        $data_type = "simple";

        $condition = array();


        $ingress = "";
        if (isset($get_data['ingress_client_id']) && !empty($get_data['ingress_client_id']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where ingress=true and resource.client_id = {$get_data['ingress_client_id']}";
            $ingress = $this->Cdr->query($sql); //pr($ingress);die;
            $condition[] = "ingress_client_id {$get_data['ingress_client_id']}";
        }
        $this->set('ingress', $ingress);
//        condition ingress_id 
        if (!empty($get_data['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($get_data['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($get_data['ingress_id']);

            $this->set('ingress_options', $ingress_options);
            $condition[] = "ingress_id {$get_data['ingress_id']}";
        }

//condition rout_prefix 
        if (isset($get_data['route_prefix']) && $get_data['route_prefix'] != 'all')
        {
            $condition[] = "route_prefix {$get_data['route_prefix']}";
        }

//condition ingress_country 
        if (isset($get_data['orig_country']) && $get_data['orig_country'])
        {
            $data_type = "detail";
            $orig_country = str_replace(" ", "%20", $get_data['orig_country']);
            $condition[] = "ingress_country {$orig_country}";
        }

//condition INGRESS_CODE_NAME 
        if (isset($get_data['orig_code_name']) && $get_data['orig_code_name'])
        {
            $data_type = "detail";
            $code_name = str_replace(" ", "%20", $get_data['orig_code_name']);
            $condition[] = "ingress_code_name {$code_name}";
        }

//condition INGRESS_CODE  
        if (isset($get_data['orig_code']) && $get_data['orig_code'])
        {
            $data_type = "detail";
            $condition[] = "ingress_code {$get_data['orig_code']}";
        }

//condition I INGRESS_RATE_TYPE         
        if (isset($get_data['orig_rate_type']) && $get_data['orig_rate_type'] != '0')
        {
            $data_type = "detail";
            $condition[] = "ingress_rate_type {$get_data['orig_rate_type']}";
        }

//condition I INGRESS_RATE_TABLE_ID 
        if (isset($get_data['ingress_rate_table']) && $get_data['ingress_rate_table'] != 'all')
        {
            $data_type = "detail";
            $condition[] = "ingress_rate_table_id {$get_data['ingress_rate_table']}";
        }

//condition I ROUTE_STRATEGY_ID         
        if (isset($get_data['ingress_routing_plan']) && $get_data['ingress_routing_plan'] != 'all')
        {
            $data_type = "detail";
            $condition[] = "route_strategy_id {$get_data['ingress_routing_plan']}";
        }

//  condition egress_client_id 
        $egress = "";
        if (isset($get_data['egress_client_id']) && !empty($get_data['egress_client_id']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where egress=true and resource.client_id = {$get_data['egress_client_id']}";
            $egress = $this->Cdr->query($sql);
            $condition[] = "egress_client_id {$get_data['egress_client_id']}";
        }
        $this->set('egress', $egress);

//condition egress_id 
        if (isset($get_data['egress_id']) && $get_data['egress_id'])
        {
            $condition[] = "egress_id {$get_data['egress_id']}";
        }

//condition egress_country 
        if (isset($get_data['term_country']) && $get_data['term_country'])
        {
            $data_type = "detail";
            $term_country = str_replace(" ", "%20", $get_data['term_country']);
            $condition[] = "egress_country {$term_country}";
        }

//condition EGRESS_CODE_NAME 
        if (isset($get_data['term_code_name']) && $get_data['term_code_name'])
        {
            $data_type = "detail";
            $code_name = str_replace(" ", "%20", $get_data['term_code_name']);
            $condition[] = "egress_code_name {$code_name}";
        }

//condition EGRESS_CODE  
        if (isset($get_data['term_code']) && $get_data['term_code'])
        {
            $data_type = "detail";
            $condition[] = "egress_code {$get_data['term_code']}";
        }

//condition  EGRESS_RATE_TYPE         
        if (isset($get_data['term_rate_type']) && $get_data['term_rate_type'] != '0')
        {
            $condition[] = "egress_rate_type {$get_data['term_rate_type']}";
            $data_type = "detail";
        }

//condition  EGRESS_RATE_TYPE         
        if (isset($get_data['show_inter_intra']))
        {
            $data_type = "detail";
        }

//condition  EGRESS_RATE_TYPE         
        if (isset($get_data['ingress_profile_ip']) && $get_data['ingress_profile_ip'])
        {
            $data_type = "detail";
            $condition[] = "ingress_profile_ip {$get_data['ingress_profile_ip']}";
        }

//condition  EGRESS_RATE_TYPE         
        if (isset($get_data['egress_profile_ip']) && $get_data['egress_profile_ip'])
        {
            $data_type = "detail";
            $condition[] = "egress_profile_ip {$get_data['egress_profile_ip']}";
        }

//        $show_LRN
        $show_LRN = 0;
        if (isset($get_data['show_LRN']))
        {
            $data_type = "detail";
            $show_LRN = 1;
            $this->set('show_LRN', $show_LRN);
        }

        $group_select = "";
        $group_select_arr = isset($get_data['group_select']) ? $get_data['group_select'] : array();
        if (!strcmp($type, 'term'))
        {
            $group_select_arr[] = 'egress_client_id';
        }
        else
        {
            $group_select_arr[] = 'ingress_client_id';
        }
        foreach ($group_select_arr as $key => $group_select)
        {
            if (!$group_select)
            {
                unset($group_select_arr[$key]);
            }
            else
            {
                $alias_arr = array(
                    'ingress_id', 'egress_id', 'ingress_client_id',
                    'egress_client_id'
                );
                $group_key = $group_select;
                if (in_array($group_select, $alias_arr))
                {
                    $group_key = $group_select . "_name";
                }
                $show_fields[$group_key] = $group_select;
                $data_type = "detail";
            }
        }
        if ($group_select_arr)
        {
            $group_select = " GROUP_BY " . implode(' ', $group_select_arr) . " END";
        }

//        pr($group_select_arr);
        $this->set('group_select_arr', $group_select_arr);

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code',
            'origination_destination_host_name' => 'Orig Server',
            'termination_source_host_name' => 'Term Server',
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);


        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();

//

        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();


        $this->set('servers', $this->Cdr->find_server());
        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
//$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start', $start_date);
        $this->set('end', $end_date);

        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);


        $simple_arr = array(
            'group_time',
            'ingress_id', 'egress_id', 'ingress_client_id',
            'egress_client_id', 'route_prefix', 'lrn_calls',
            'not_zero_calls', 'egress_busy_calls', 'ingress_busy_calls',
            'egress_total_calls', 'ingress_total_calls',
            'egress_cancel_calls', 'ingress_cancel_calls',
            'egress_success_calls', 'ingress_success_calls',
            'lnp_cost', 'egress_call_cost', 'ingress_call_cost',
            'pdd', 'duration', 'egress_bill_time', 'ingress_bill_time'
        );

        $detail_arr = array(
            'group_time',
            'ingress_id', 'egress_id', 'ingress_client_id',
            'egress_client_id', 'route_prefix', 'ingress_country',
            'ingress_code', 'ingress_code_name', 'egress_country',
            'egress_code', 'egress_code_name', 'ingress_rate_type',
            'egress_rate_type', 'ingress_rate_table_id', 'egress_rate_table_id',
            'route_strategy_id', 'ingress_profile_ip', 'egress_profile_ip',
            'egress_jur_type', 'ingress_jur_type', 'release_cause_to',
            'release_cause_from', 'release_cause_route', 'agent_id',
            'call_2h', 'call_3h', 'call_4h', 'call_12s', 'call_18s', 'call_24s',
            'lrn_calls', 'not_zero_calls', 'not_zero_calls_6', 'not_zero_calls_30',
            'egress_busy_calls', 'ingress_busy_calls', 'egress_total_calls',
            'ingress_total_calls', 'egress_cancel_calls', 'ingress_cancel_calls',
            'egress_success_calls', 'ingress_success_calls', 'pdd', 'duration',
            'duration_6', 'duration_30', 'egress_bill_time', 'ingress_bill_time',
            'lnp_cost', 'egress_call_cost', 'ingress_call_cost'
        );

        $unique_field_arr = array(
            'group_time',
            'ingress_id', 'egress_id', 'ingress_client_id',
            'egress_client_id', 'route_prefix', 'ingress_country',
            'ingress_code', 'ingress_code_name', 'egress_country',
            'egress_code', 'egress_code_name', 'ingress_rate_type',
            'egress_rate_type', 'ingress_rate_table_id', 'egress_rate_table_id',
            'route_strategy_id', 'ingress_profile_ip', 'egress_profile_ip',
            'egress_jur_type', 'ingress_jur_type', 'release_cause_to',
            'release_cause_from', 'release_cause_route', 'agent_id',
            'egress_client_id_name', 'ingress_client_id_name',
            'ingress_id_name', 'egress_id_name',
        );

//        if (isset($this->params['url']['query']))
//        {
        $report_server = $this->SwitchProfile->get_report_server();
        $first_data = array();
        $j = 0;
        foreach ($report_server as $server_key => $report_server_item)
        {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $ip = $report_server_item[0]['report_ip'];
            $port = $report_server_item[0]['report_port'];
            $flg = socket_connect($socket, $ip, $port);
//$flg = @socket_connect($socket, '192.168.1.107', '3300');
//           var_dump($flg);
//            echo "<br >";
            if (!$flg)
            {
                socket_close($socket);
                continue;
            }
            $local_ip = "";
            socket_getsockname($socket, $local_ip);

            $out = @socket_read($socket, 23, PHP_NORMAL_READ);
//        echo $out;die;
            if (strcmp($out, "welcome $local_ip $"))
            {//如果不匹配 
                continue;
            }
            $start_qie = date("Y-m-d H:i:sO",strtotime($start_date));
            $end_qie = date("Y-m-d H:i:sO",strtotime($end_date));
            $send = "GET DATA detail BEGIN {$start_qie} END {$end_qie}";
            if ($condition)
            {
                $send .= " CONDITION " . implode(' ', $condition) . " END";
            }
            if ($group_select)
            {
                $send .= " " . $group_select;
            }

            $send .= " GROUP by_day";
            if (Configure::read('cmd.debug'))
            {
                $this->set('send', $send);
            }
//          echo $send . "<br />";
            socket_write($socket, $send);
//                $data = socket_read($socket, 1024);
//              
            $data = array();

            $data_str = "";
            do
            {
                $data_str .= @socket_read($socket, 4096);
            }
            while (strpos($data_str, "\r\n\r\n") === false);

            $data_arr = explode("\n", $data_str);

            foreach ($data_arr as $data_arr_key => $data_item_str)
            {
                if (!trim($data_item_str))
                {
                    unset($data_arr[$data_arr_key]);
                    continue;
                }
                $data_item_arr = explode(",", $data_item_str);
//                pr($detail_arr);
//                pr($data_item_arr);die;
                if (empty($field))
                {
                    $data_item = array_combine($detail_arr, $data_item_arr);
                    $title_field_arr = $detail_arr;
                }
                else
                {
                    $data_item = array_combine($field, $data_item_arr);
                    $title_field_arr = $field;
                }
                $data_item['ingress_id_name'] = "--";
                $data_item['egress_id_name'] = "--";
                $data_item['ingress_client_id_name'] = "--";
                $data_item['egress_client_id_name'] = "--";
                if (!empty($data_item['ingress_id']))
                {
                    $trunk_name = $this->Cdr->query("SELECT alias FROM resource WHERE resource_id = {$data_item['ingress_id']}");
                    $data_item['ingress_id_name'] = isset($trunk_name[0][0]['alias']) ? $trunk_name[0][0]['alias'] : "";
                }
                if (!empty($data_item['egress_id']))
                {
                    $trunk_name = $this->Cdr->query("SELECT alias FROM resource WHERE resource_id = {$data_item['egress_id']}");
                    $data_item['egress_id_name'] = isset($trunk_name[0][0]['alias']) ? $trunk_name[0][0]['alias'] : "";
                }
                if (!empty($data_item['ingress_client_id']))
                {
                    $client_name = $this->Cdr->query("SELECT name FROM client WHERE client_id = {$data_item['ingress_client_id']}");
//                        pr($client_name);die;
                    $data_item['ingress_client_name'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "";
                }
                if (!empty($data_item['egress_client_id']))
                {
                    $client_name = $this->Cdr->query("SELECT name FROM client WHERE client_id = {$data_item['egress_client_id']}");
                    $data_item['egress_client_name'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "";
                }

                $data[][0] = $data_item;
            }
//            pr($data_arr);
//                pr($simple_arr);
//                die;
//          pr($data);
            socket_close($socket);
//            echo "<hr />";
//            pr($data);
//            die;
//            处理数据  
            $result = array();
            foreach ($data as $item)
            {
                //daily report 数据处理 
                $exists_key = $this->judge_group($group_select_arr, $item[0], $result);

                $result = $this->manipulation_data($result, $item, $type, $exists_key, $group_select_arr);
            }

//            echo "result", $j++;
//pr($result);
//             die;
            if ($j)
            {
                $count = count($first_data);
//               echo $count; die;
                $i = 0;
                foreach ($result as $result_items)   //循环 新数据 
                { //循环 当前得到的数据 
                    $push_flg = 0;
                    $i++;
                    $simple_key = 0;
                    foreach ($first_data as $first_data_key => $first_data_item)
                    { //新数据的某一部分是否在总数据中存在  存在$simple_key 为 1； 
//                        pr($result_items);
//                        pr($first_data_item);
//                        die;
//                        echo $first_data_key."<br />";
                        if ($i > $count)
                        {
                            break;
                        }
//                        echo 7;
                        foreach ($group_select_arr as $group_select_arr_value)
                        {
//                            pr($first_data_item);
//                            pr($result_items);
//                            var_dump(strcmp($first_data_item[$group_select_arr_value], $result_items[$group_select_arr_value]));
//                            echo "<hr />";
                            if (!strcmp($first_data_item[$group_select_arr_value], $result_items[$group_select_arr_value]))
                            {//同一组 
                                $simple_key = 1;
                                continue;
                            }
                            $simple_key = 0;
                            break;
                        }

                        if ($simple_key)
                        {//在同一组 
                            $first_data[$first_data_key]['total_time'] += $result_items['total_time'];
                            $first_data[$first_data_key]['calls_30'] += $result_items['calls_30'];
                            $first_data[$first_data_key]['time_30'] += $result_items['time_30'];
                            $first_data[$first_data_key]['calls_6'] += $result_items['calls_6'];
                            $first_data[$first_data_key]['time_6'] += $result_items['time_6'];
                            $first_data[$first_data_key]['not_zero_calls'] += $result_items['not_zero_calls'];

                            //判断时间相同部分 
                            foreach ($result_items['years'] as $time_key1 => $time_value1)
                            {
                                $years_merge_flg = 0;
                                foreach ($first_data_item['years'] as $time_key => $time_value)
                                {
//                                    echo $time_key1 . "||||" . $time_key;
                                    if (!strcmp($time_key1, $time_key))
                                    {// 同一个时间 
                                        $first_data[$first_data_key]['years'][$time_key]['bill_time'] += $time_value1['bill_time'];
                                        $first_data[$first_data_key]['years'][$time_key]['total_calls'] += $time_value1['total_calls'];
                                        $first_data[$first_data_key]['years'][$time_key]['not_zero_calls'] += $time_value1['not_zero_calls'];
                                        $first_data[$first_data_key]['years'][$time_key]['total_time'] += $time_value1['total_time'];
                                        $years_merge_flg = 1;
                                        break;
                                    }
                                }
                                if (!$years_merge_flg)
                                {
                                    $time_push_arr = array(
                                        $time_key1 => $time_value1
                                    );
                                    array_push($first_data[$first_data_key]['years'], $time_push_arr);
                                }
//                                pr($first_data);
//                                echo "-------------<hr />"; 
                            }
                            $push_flg = 0;
                            break;
                        }
                        else
                        {
                            $push_flg = 1;
                            continue;
                        }
//                        
                    }

                    if ($push_flg)
                    {
                        array_push($first_data, $result_items);
                    }
                }
                //die;
            }
            else// 第一组数据 
            {
                if(empty($result))
                {
                    continue;
                }
                $first_data = $result;
            }
            $j++;
//            pr($first_data);die;
        }
        if (!$first_data)
        {
            $first_data = array();
            $this->set('show_nodata', true);
        }
        else
        {
            $this->set('show_nodata', false);
        }
//     pr($first_data);
//die;
        $this->set('data', $first_data);
    }

    /**
     * 
     * @param type $group_arr   分组条件  
     * @param type $item        新数据   :单个的一维数组 
     * @param type $result      整个数据  ： 多个的二维数组 
     * @return boolean
     */
    public function judge_group($group_arr, $item, $result, $debug = false)
    {
        if (!$result)
        {
            return false;
        }

        $flg = 0;
        $return = false;
        foreach ($result as $key => $result_item)
        {
            foreach ($group_arr as $group_value)
            {
                if ($debug)
                {
                    pr($result_item[$group_value]);
                    pr($item[$group_value]);
                    var_dump(strcmp($result_item[$group_value], $item[$group_value]));
                }

                if (strcmp($result_item[$group_value], $item[$group_value]))
                {// 不是同组
                    $flg = 1;
                    break;
                }
                $flg = 0;
            }
            if (!$flg)
            {// 是和当前同组 
                $return = $key;
                break;
            }
        }
        if ($debug)
        {
            if ($return === false)
            {
                echo "group_arr";
                pr($group_arr);
                echo '$item';
                pr($item);
                echo '$result';
                pr($result);
                echo "<hr />";
            }
        }
        return $return;
    }

    /**
     * 
     * @param type $result   整个数据  ： 多个的二维数组 
     * @param type $item       新数据   :单个的一维数组  
     * @param type $type        orig or  term 
     * @param type $exists_key   是否 为新数据 还是需要合并 
     * @param type $group_select_arr
     * 
     */
    public function manipulation_data($result, $item, $type, $exists_key, $group_select_arr, $debug = false)
    {
        if ($debug)
        {
            echo "result";
            pr($result);
            echo "item";
            pr($item);
            echo "type";
            pr($type);
            echo 'exists_key';
            pr($exists_key);
        }

        if (!strcmp($type, 'term'))
        {
            $bill_time = $item[0]['egress_bill_time'];
            $total_calls = $item[0]['egress_total_calls'];
            $client_name = isset($item[0]['egress_client_name']) ? $item[0]['egress_client_name'] : "";
        }
        else
        {
            $bill_time = $item[0]['ingress_bill_time'];
            $total_calls = $item[0]['ingress_total_calls'];
            $client_name = isset($item[0]['ingress_client_name']) ? $item[0]['ingress_client_name'] : "";
        }

        if ($exists_key !== FALSE)
        {
            $result[$exists_key]['total_time'] += $item[0]['duration'];
            $result[$exists_key]['calls_30'] += $item[0]['not_zero_calls_30'];
            $result[$exists_key]['time_30'] += $item[0]['duration_30'];
            $result[$exists_key]['calls_6'] += $item[0]['not_zero_calls_6'];
            $result[$exists_key]['time_6'] += $item[0]['duration_6'];
            //$result[$exists_key]['bill_time'] += $item[0]['bill_time'];
            //$result[$exists_key]['total_calls'] += $item[0]['total_calls'];
            $result[$exists_key]['not_zero_calls'] += $item[0]['not_zero_calls'];

            $time = substr($item[0]['group_time'], 0, 10);


            $result[$exists_key]['years'][$time] = array(
                'bill_time' => $bill_time,
                'total_calls' => $total_calls,
                'not_zero_calls' => $item[0]['not_zero_calls'],
                'total_time' => $item[0]['duration'],
            );
        }
        else
        {
            $time = substr($item[0]['group_time'], 0, 10);

            $result_arr = array(
                'client_name' => $client_name,
                'total_time' => $item[0]['duration'],
                'calls_30' => $item[0]['not_zero_calls_30'],
                'time_30' => $item[0]['duration_30'],
                'calls_6' => $item[0]['not_zero_calls_6'],
                'time_6' => $item[0]['duration_6'],
                //'bill_time' => $item[0]['bill_time'],
                //'total_calls' => $item[0]['total_calls'],
                'not_zero_calls' => $item[0]['not_zero_calls'],
                'years' => array($time => array(
                        'bill_time' => $bill_time,
                        'total_calls' => $total_calls,
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'total_time' => $item[0]['duration'],
                    )),
            );


            // 取分组信息

            $alias_arr = array(
                'ingress_id', 'egress_id',
            );

            foreach ($group_select_arr as $group_select_arr_value)
            {
                if (in_array($group_select_arr_value, $alias_arr))
                {
                    $group_key = $group_select_arr_value . "_name";
                    $result_arr[$group_select_arr_value] = isset($item[0][$group_key]) ? $item[0][$group_key] : "";
                }
                else
                {
                    $result_arr[$group_select_arr_value] = $item[0][$group_select_arr_value];
                }
            }
            array_push($result, $result_arr);
        }
        return $result;
    }

}

?>
