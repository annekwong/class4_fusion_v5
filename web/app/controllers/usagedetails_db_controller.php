<?php

/**
 * 
 * @author weifeng
 * 	Daily Origination Usage Detail Report
 * 	Daily Termination Usage Detail Report 
 *
 */
class UsagedetailsDbController extends AppController
{

    var $name = 'UsagedetailsDb';
    var $uses = array('Cdr', 'CdrsRead', 'SwitchProfile','CdrRead');
    var $helpers = array('javascript', 'html', 'common');

    function index()
    {
        $this->redirect('orig_summary_reports');
    }

    public function __construct(){
        Configure::load('myconf');
        parent::__construct();
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
        parent::beforeRender();
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

    function search_exist_key($arr, $v1, $v2, $k1, $k2, $group_key_arr = array())
    {
        foreach ($arr as $key => $val)
        {
            //if(in_array($v1, $val) && in_array($v2, $val)) {
            if ($val[$k1] == $v1 and $val[$k2] == $v2)
            {
                foreach ($group_key_arr as $group_key => $group_arr)
                {
                    if ($val[$group_key] != $group_arr)
                    {
                        return FALSE;
                    }
                }
                return $key;
            }
        }
        return FALSE;
    }

    /**
     * Taken from: http://stackoverflow.com/questions/4312439/php-return-all-dates-between-two-dates-in-an-array
     * @param $strDateFrom
     * @param $strDateTo
     * @return array
     */
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

    private function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    function orig_summary_reports()
    {
        $this->pageTitle = "Origination Usage Detail/Spam Report";
        $this->set('cdr_db',$this->Cdr);
        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
//            init
        $ingress_clients = $this->CdrsRead->get_ingress_clients();
        $client_id = isset($this->params['url']['ingress_client_id']) ? $this->params['url']['ingress_client_id'] : "";
        $ingress_trunks = $this->CdrsRead->get_ingress_trunks($client_id);
        $this->set('servers', $this->Cdr->find_server());
        $this->set('ingress_clients', $ingress_clients);
        $this->set('ingress', $ingress_trunks);
        $rate_tables = $this->CdrsRead->get_rate_tables();
        $routing_plans = $this->CdrsRead->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        $t = getMicrotime();
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;
        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);

        $show_type = isset($this->params['url']['show_type']) ? $this->params['url']['show_type'] : 0;
        $this->set('show_type', $show_type);

        $filed_arr = array('client_name', 'resource_name');
        $get_data = array(
            'ingress_client_id' => '',
            'ingress_id' => '',
            'route_prefix' => 'all',
            'orig_country' => '',
            'orig_code_name' => '',
            'orig_code' => '',
            'ingress_jur_type' => '',
            'ingress_rate_table' => 'all',
            'ingress_routing_plan' => 'all',
            'ingress_profile_ip' => '',
            'group_select' => array(
                'ingress_client_id', 'ingress_id',
            ),
        );
        if (isset($_GET['show_type']))
        {
            $get_data = $this->params['url'];
        }

        $this->set('get_data', $get_data);
        if (isset($_GET['show_type']) || $is_preload)
        {
            $where = "";

            if ($get_data['ingress_client_id'])
            {
                $where .= " AND ingress_client_id = {$get_data['ingress_client_id']}";
            }

            if ($get_data['ingress_id'])
            {
                $where .= " AND ingress_id = {$get_data['ingress_id']}";
            }

            if ($get_data['route_prefix'] && $get_data['route_prefix'] != 'all')
            {
                $where .= " AND ingress_prefix = '{$get_data['route_prefix']}'";
            }

            if ($get_data['orig_country'])
            {
                $where .= " AND ingress_country = '{$get_data['orig_country']}'";
            }

            if ($get_data['orig_code_name'])
            {
                $where .= " AND ingress_code_name = '{$get_data['orig_code_name']}'";
            }

            if (isset($get_data['orig_code']) && $get_data['orig_code'])
            {
                $where .= " AND ingress_code = '{$get_data['orig_code']}'";
            }

            if ($get_data['ingress_rate_table'] && $get_data['ingress_rate_table'] != 'all')
            {
                $where .= " AND ingress_rate_table_id = {$get_data['ingress_rate_table']}";
            }

            if ($get_data['ingress_jur_type'])
            {
                $where .= " AND orig_jur_type = {$get_data['ingress_jur_type']}";
            }

            if ($get_data['ingress_routing_plan'] && $get_data['ingress_routing_plan'] != 'all')
            {
                $where .= " AND route_plan_id = {$get_data['ingress_routing_plan']}";
            }

            if ($get_data['ingress_profile_ip'])
            {
                $where .= " AND origination_destination_host_name = '{$get_data['ingress_profile_ip']}'";
            }

            foreach ($get_data['group_select'] as $key => $value)
            {
                if (empty($value) || !strcmp($value, 'ingress_client_id') || !strcmp($value, 'ingress_id'))
                {
                    unset($get_data['group_select'][$key]);
                }
                else
                {
                    array_push($filed_arr, $value);
                }
            }
            $group_filed = implode(",", $get_data['group_select']);

            if ($group_filed)
            {
                $group_filed = $group_filed . ",";
            }
            
            //生成日期数组
            $time_data = $this->_get_date_result_admin($start_date, $end_date, CDR_TABLE . '2%');
            $tmpDateRange = $this->createDateRangeArray($start_date, $end_date);
            $to_time = time();
            $from_time = strtotime($start_date);
            $mins = round(abs($to_time - $from_time) / 60,2);
//            die(var_dump($to_time, $from_time, $mins));
            if ($mins > 65) {
                $data = $this->CdrRead->get_orig_summary_reports($start_date, $end_date, $time_data, $gmt, $where, $group_filed);
            } else {
                $data = array();
            }

            $result = array();
            foreach ($data as $item)
            {
                $group_key_arr = array();
                foreach ($get_data['group_select'] as $group_item)
                {
                    $group_key_arr[$group_item] = $item[0][$group_item];
                }
                $exists_key = $this->search_exist_key($result, $item[0]['ingress_client_id'], $item[0]['ingress_id'], 'ingress_client_id', 'ingress_id', $group_key_arr);
                if ($exists_key !== FALSE)
                {
                    $result[$exists_key]['total_time'] += $item[0]['total_time'];
                    $result[$exists_key]['calls_30'] += $item[0]['calls_30'];
                    //$result[$exists_key]['time_30'] += $item[0]['time_30'];
                    $result[$exists_key]['calls_6'] += $item[0]['calls_6'];
                    //$result[$exists_key]['time_6'] += $item[0]['time_6'];
                    //$result[$exists_key]['bill_time'] += $item[0]['bill_time'];
                    //$result[$exists_key]['total_calls'] += $item[0]['total_calls'];
                    $result[$exists_key]['not_zero_calls'] += $item[0]['not_zero_calls'];
                    $result[$exists_key]['years'][$item[0]['report_time']] = array(
                        'bill_time' => $item[0]['bill_time'],
                        'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'total_time' => $item[0]['total_time'],
                    );
                }
                else
                {
                    $push_arr = array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'ingress_id' => $item[0]['ingress_id'],
                        'client_name' => $item[0]['client_name'],
                        'resource_name' => $item[0]['resource_name'],
                        'total_time' => $item[0]['total_time'],
                        'calls_30' => $item[0]['calls_30'],
                        //'time_30' => $item[0]['time_30'],
                        'calls_6' => $item[0]['calls_6'],
                        //'time_6' => $item[0]['time_6'],
                        //'bill_time' => $item[0]['bill_time'],
                        //'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'years' => array($item[0]['report_time'] => array(
                                'bill_time' => $item[0]['bill_time'],
                                'total_calls' => $item[0]['total_calls'],
                                'not_zero_calls' => $item[0]['not_zero_calls'],
                                'total_time' => $item[0]['total_time'],
                                'npr_count' => $item[0]['npr_count'],
                            )),
                    );
                    foreach ($get_data['group_select'] as $group_item)
                    {
                        if (!strcmp($group_item, 'egress_client_id'))
                        {
                            if ($item[0][$group_item])
                            {
                                $egress_client = $this->Cdr->query("SELECT name FROM client WHERE client_id = {$item[0][$group_item]}");
                            }
                            $item[0][$group_item] = isset($egress_client[0][0]['name']) ? $egress_client[0][0]['name'] : $item[0][$group_item];
                        }

                        if (!strcmp($group_item, 'egress_id'))
                        {
                            if ($item[0][$group_item])
                            {
                                $egress_client = $this->Cdr->query("SELECT alias FROM resource WHERE resource_id = {$item[0][$group_item]}");
                            }
                            $item[0][$group_item] = isset($egress_client[0][0]['alias']) ? $egress_client[0][0]['alias'] : $item[0][$group_item];
                        }

                        $push_arr[$group_item] = $item[0][$group_item];
                    }
                    array_push($result, $push_arr);
                }
            }
        }
        else
        {
            $result = array();
            $this->set('show_nodata', false);
        }

        if(!empty($result) && isset($_GET['order_by'])) {
            $explodedOrder = explode('-', $_GET['order_by']);
            if($explodedOrder[1] == 'asc') {
                $this->array_sort_by_column($result, 'calls_6');
            } else {
                $this->array_sort_by_column($result, 'calls_6', SORT_DESC);
            }
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'client_name' => 'Ingress Carrier',
            'resource_name' => 'Ingress Trunk',
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

        $this->set('replace_fields', $replace_fields);
        $this->set('filed_arr', $filed_arr);
        $this->set('data', $result);
        $this->set('start', $start_date);
        $this->set('end', $end_date);
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

//        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        //            init
        $egress_clients = $this->CdrsRead->get_egress_clients();
        $client_id = isset($this->params['url']['egress_client_id']) ? $this->params['url']['egress_client_id'] : "";
        $egress_trunks = $this->CdrsRead->get_egress_trunks($client_id);
        $this->set('servers', $this->Cdr->find_server());
        $this->set('egress_clients', $egress_clients);
        $this->set('egress', $egress_trunks);
        $rate_tables = $this->CdrsRead->get_rate_tables();
        $routing_plans = $this->CdrsRead->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        $t = getMicrotime();
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;
        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        $show_type = isset($this->params['url']['show_type']) ? $this->params['url']['show_type'] : 0;
        $this->set('show_type', $show_type);

        $filed_arr = array('client_name', 'resource_name');
        $get_data = array(
            'egress_client_id' => '',
            'egress_id' => '',
            'route_prefix' => 'all',
            'term_country' => '',
            'term_code_name' => '',
            'term_code' => '',
            'egress_jur_type' => '',
            'egress_rate_table' => 'all',
            'egress_routing_plan' => 'all',
            'egress_profile_ip' => '',
            'group_select' => array(
                'egress_client_id', 'egress_id',
            ),
        );
        if (isset($_GET['show_type']))
        {
            $get_data = $this->params['url'];
        }

        $this->set('get_data', $get_data);


        if (isset($_GET['show_type']) || $is_preload)
        {
            $where = "";

            if ($get_data['egress_client_id'])
            {
                $where .= " AND egress_client_id = {$get_data['egress_client_id']}";
            }

            if ($get_data['egress_id'])
            {
                $where .= " AND egress_id = {$get_data['egress_id']}";
            }

            if ($get_data['term_country'])
            {
                $where .= " AND egress_country = '{$get_data['term_country']}'";
            }

            if ($get_data['term_code_name'])
            {
                $where .= " AND egress_code_name = '{$get_data['term_code_name']}'";
            }

            if ($get_data['term_code'])
            {
                $where .= " AND egress_code = '{$get_data['term_code']}'";
            }

            if ($get_data['egress_jur_type'])
            {
                $where .= " AND term_jur_type = {$get_data['egress_jur_type']}";
            }

            if ($get_data['egress_profile_ip'])
            {
                $where .= " AND termination_source_host_name = '{$get_data['egress_profile_ip']}'";
            }


            foreach ($get_data['group_select'] as $key => $value)
            {
                if (empty($value) || !strcmp($value, 'egress_client_id') || !strcmp($value, 'egress_id'))
                {
                    unset($get_data['group_select'][$key]);
                }
                else
                {
                    array_push($filed_arr, $value);
                }
            }

            $group_filed = implode(",", $get_data['group_select']);

            if ($group_filed)
            {
                $group_filed = $group_filed . ",";
            }
            
            //生成日期数组
            $time_data = $this->_get_date_result_admin($start_date, $end_date, CDR_TABLE . '2%');
            $data = $this->CdrRead->get_term_summary_reports($start_date, $end_date, $time_data, $gmt, $where, $group_filed);
            $result = array();
            foreach ($data as $item)
            {
                $group_key_arr = array();
                foreach ($get_data['group_select'] as $group_item)
                {
                    $group_key_arr[$group_item] = $item[0][$group_item];
                }
                //$exists_key = $this->search_exist($result, $item[0]['egress_client_id'], $item[0]['egress_code_name']);
                $exists_key = $this->search_exist_key($result, $item[0]['egress_client_id'], $item[0]['egress_id'], 'egress_client_id', 'egress_id', $group_key_arr);
                if ($exists_key !== FALSE)
                {
                    $result[$exists_key]['total_time'] += $item[0]['total_time'];
                    $result[$exists_key]['calls_30'] += $item[0]['calls_30'];
                    $result[$exists_key]['time_30'] += $item[0]['time_30'];
                    $result[$exists_key]['calls_6'] += $item[0]['calls_6'];
                    $result[$exists_key]['time_6'] += $item[0]['time_6'];
                    //$result[$exists_key]['bill_time'] += $item[0]['bill_time'];
                    //$result[$exists_key]['total_calls'] += $item[0]['total_calls'];
                    $result[$exists_key]['not_zero_calls'] += $item[0]['not_zero_calls'];
                    $result[$exists_key]['years'][$item[0]['report_time']] = array(
                        'bill_time' => $item[0]['bill_time'],
                        'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'total_time' => $item[0]['total_time'],
                        'npr_count' => $item[0]['npr_count'],
                    );
                }
                else
                {
                    $push_arr = array(
                        'egress_client_id' => $item[0]['egress_client_id'],
                        'egress_id' => $item[0]['egress_id'],
                        'client_name' => $item[0]['client_name'],
                        'resource_name' => $item[0]['resource_name'],
                        'total_time' => $item[0]['total_time'],
                        'calls_30' => $item[0]['calls_30'],
                        'time_30' => $item[0]['time_30'],
                        'calls_6' => $item[0]['calls_6'],
                        'time_6' => $item[0]['time_6'],
                        //'bill_time' => $item[0]['bill_time'],
                        //'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'years' => array($item[0]['report_time'] => array(
                                'bill_time' => $item[0]['bill_time'],
                                'total_calls' => $item[0]['total_calls'],
                                'not_zero_calls' => $item[0]['not_zero_calls'],
                                'total_time' => $item[0]['total_time'],
                                'npr_count' => $item[0]['npr_count'],
                            )),
                    );
                    foreach ($get_data['group_select'] as $group_item)
                    {
                        if (!strcmp($group_item, 'ingress_client_id'))
                        {
                            if ($item[0][$group_item])
                            {
                                $egress_client = $this->Cdr->query("SELECT name FROM client WHERE client_id = {$item[0][$group_item]}");
                            }
                            $item[0][$group_item] = isset($egress_client[0][0]['name']) ? $egress_client[0][0]['name'] : $item[0][$group_item];
                        }

                        if (!strcmp($group_item, 'ingress_id'))
                        {
                            if ($item[0][$group_item])
                            {
                                $egress_client = $this->Cdr->query("SELECT alias FROM resource WHERE resource_id = {$item[0][$group_item]}");
                            }
                            $item[0][$group_item] = isset($egress_client[0][0]['alias']) ? $egress_client[0][0]['alias'] : $item[0][$group_item];
                        }

                        $push_arr[$group_item] = $item[0][$group_item];
                    }
                    array_push($result, $push_arr);
                }
            }
        }
        else
        {
            $result = array();
            $this->set('show_nodata', false);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'client_name' => 'Egress Carrier',
            'resource_name' => 'Egress Trunk',
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
        
        $this->set('replace_fields', $replace_fields);
        $this->set('filed_arr', $filed_arr);
        $this->set('data', $result);
        $this->set('start', $start_date);
        $this->set('end', $end_date);
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
        $this->set('cdr_db',$this->Cdr);
        $t = getMicrotime();
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;
        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        if (isset($_GET['show_type']) || $is_preload)
        {
            //生成日期数组
            $time_data = $this->_get_date_result_admin($start_date, $end_date, CDR_TABLE . '2%');
            $data = $this->CdrRead->get_daily_orig_summary($start_date, $end_date, $time_data, $gmt);
            $result = array();
            foreach ($data as $item)
            {
                $key = $this->search_exist1($result, $item[0]['ingress_client_id']);
                if ($key !== FALSE)
                {
                    $result[$key][$item[0]['report_time']] = array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'bill_time' => $item[0]['bill_time'],
                        'client_name' => $item[0]['client_name'],
                    );
                }
                else
                {
                    $result[] = array($item[0]['report_time'] => array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'bill_time' => $item[0]['bill_time'],
                        'client_name' => $item[0]['client_name'],
                    ));
                }
            }
        }
        else
        {
            $result = array();
            $this->set('show_nodata', false);
        }

        $this->set('data', $result);
        $this->set('start', $start_date);
        $this->set('end', $end_date);
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('daily_orig_report_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
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
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;
        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        if (isset($_GET['show_type']) || $is_preload)
        {
            //生成日期数组
            $time_data = $this->_get_date_result_admin($start_date, $end_date, CDR_TABLE . '2%');
            $data = $this->CdrRead->get_daily_term_summary($start_date, $end_date, $time_data, $gmt);
            $result = array();
            foreach ($data as $item)
            {
                $key = $this->search_exist1($result, $item[0]['egress_client_id']);
                if ($key !== FALSE)
                {
                    $result[$key][$item[0]['report_time']] = array(
                        'egress_client_id' => $item[0]['egress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'bill_time' => $item[0]['bill_time'],
                        'client_name' => $item[0]['client_name'],
                    );
                }
                else
                {
                    $result[] = array($item[0]['report_time'] => array(
                            'egress_client_id' => $item[0]['egress_client_id'],
                            'total_time' => $item[0]['total_time'],
                            'bill_time' => $item[0]['bill_time'],
                            'client_name' => $item[0]['client_name'],
                    ));
                }
            }
        }
        else
        {
            $result = array();
            $this->set('show_nodata', false);
        }
        $this->set('data', $result);
        $this->set('start', $start_date);
        $this->set('end', $end_date);
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


    /*
     *
     * Daily Channel Usage Report
     */
    public function daily_channel_usage_report(){

        $this->pageTitle = "Daily Channel Usage Report";

        $this->set('cdr_db',$this->Cdr);
        $t = getMicrotime();
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;
        $voipGateways = $this->Cdr->query('SELECT * FROM voip_gateway');
        $serverIp = $voipGateways[0][0]['lan_ip'];

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        if (isset($_GET['server_ip']) && !empty($_GET['server_ip']))
            $serverIp = $_GET['server_ip'];

        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        require_once 'MyPage.php';
        $page = new MyPage ();
        if (isset($_GET['show_type']) || $is_preload)
        {
            $data = $this->Cdr->get_all_daily_channel_usage_report($start_date, $end_date, $gmt, $serverIp);
            $counts = count($data);
            empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
            $page->setTotalRecords($counts);
            $page->setCurrPage($currPage);
            $page->setPageSize(100);
            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;

            $page_data = $this->Cdr->get_page_daily_channel_usage_report($start_date, $end_date, $gmt, $offset, $serverIp);
            $page->setDataArray($page_data);
        }
        else
            $this->set('show_nodata', false);


        $this->set('p', $page);
        $this->set('start', $start_date);
        $this->set('end', $end_date);
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $this->set('voipGateways', $voipGateways);
        $this->set('serverIp', $serverIp);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=Daily_Channel_Usage_Report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->set('data', $data);
            $this->render('daily_channel_usage_report_csv');
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
            header("Content-Disposition: attachment;filename=Daily_Channel_Usage_Report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->set('data', $data);
            $this->render('daily_channel_usage_report_xls');
        }

    }


}

?>