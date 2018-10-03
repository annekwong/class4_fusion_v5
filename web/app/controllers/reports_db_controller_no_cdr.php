<?php

class ReportsDbController extends AppController
{

    var $name = "ReportsDb";
    var $uses = array('Cdrs', 'Cdr','CdrsRead','CdrRead','Reports');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        parent::beforeFilter();
    }

    public function summary($type = 1)
    {
        $this->pageTitle = "Statistics/Summary Report";

        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;
        $this->set('cdr_db', $this->Cdr);
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['server_ip']) && $_GET['server_ip'] != '')
        {

            array_push($where_arr, "origination_destination_host_name = '{$_GET['server_ip']}'");
            $table_name = CDR_TABLE;
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = CDR_TABLE;
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($out_field_arr, 'ingress_rate as actual_rate');
                array_push($field_arr, 'ingress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($out_field_arr, 'egress_rate as actual_rate');
                array_push($field_arr, 'egress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (!isset($_GET['show_type']) || $_GET['show_type'] == '0')
            $orders = '';

        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        $this->set('report_name', "Summary Report");
        if (isset($this->params['url']['is_scheduled_report']) && $this->params['url']['is_scheduled_report'])
        {
            $query_ = $this->Cdrs->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
            $query_tmp = str_replace($report_max_time, "[report_max_time]", $query_);
            $query = str_replace($end_date, "[end_time]", $query_tmp);

            $sql1_tmp = $this->Cdrs->get_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
            $sql2_tmp = $this->Cdrs->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
            $query_2 = $this->Cdrs->get_cdrs_two($sql1_tmp, $sql2_tmp, $type, $orders, $show_fields);
            $query2_tmp = str_replace($report_max_time, "[report_max_time]", $query_2);
            $query2_tmp_ = str_replace($start_date, "[start_date]", $query2_tmp);
            $query2 = str_replace($end_date, "[end_time]", $query2_tmp_);

            $query_3 = $this->Cdrs->get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
            $query3_tmp = str_replace($start_date, "[start_date]", $query_3);
            $query3 = str_replace($end_date, "[end_time]", $query3_tmp);

            $scheduled_report_data = $this->params['url']['scheduled_report'];
            $this->scheduled_report2($query, $scheduled_report_data, $query2, $query3);
        }
        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $sql = $this->CdrsRead->get_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

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
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        //选择显示field
        if($type == 1){
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('NPR Count', true),
                    7 => __('NPR', true),
                    8 => __('NRF Count', true),
                    9 => __('NRF', true),
                    10 => __('Revenue', true),
                    11 => __('Profit', true),
                    12 => __('Margin', true),
                    13 => __('PP Min', true),
                    14 => __('PP K Calls', true),
                    15 => __('SD Count', true),
                    16 => __('SDP', true),
                    17 => __('Limited', true),
                    18 => __('Total Duration', true),
                    19 => __('Total Billable Time', true),
                    20 => __('Total Cost', true),
                    21 => __('Inter Cost', true),
                    22 => __('Intra Cost', true),
                    23 => __('Actual Rate / Avg Rate', true),
                    24 => __('Total Calls', true),
                    25 => __('Not Zero Calls', true),
                    26 => __('Success Calls', true),
                    27 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    18,
                    19,
                    20,
                    23,
                    24,
                    25,
                    26,
                    27
                );




        } else {
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('SD Count', true),
                    7 => __('SDP', true),
                    8 => __('Limited', true),
                    9 => __('Total Duration', true),
                    10 => __('Total Billable Time', true),
                    11 => __('Total Cost', true),
                    12 => __('Inter Cost', true),
                    13 => __('Intra Cost', true),
                    14 => __('Actual Rate / Avg Rate', true),
                    15 => __('Total Calls', true),
                    16 => __('Not Zero Calls', true),
                    17 => __('Success Calls', true),
                    18 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    9,
                    10,
                    11,
                    14,
                    15,
                    16,
                    17,
                    18
                );
        }


        isset($_GET['query']['fields']) && ($select_show_fields = $_GET['query']['fields']);


//        pr($select_fields,$select_show_fields);die;
//        减去上一次查询的group数量
        if ($this->_get('last_group_fields_count')){
            foreach ($select_show_fields as $key => $show_field_value){
                if ($show_field_value < $this->_get('last_group_fields_count')){
                    unset($select_show_fields[$key]);
                }else{
                    $select_show_fields[$key] = $show_field_value - $this->_get('last_group_fields_count');
                }
            }
        }
        $default_select_show_fields = $select_show_fields;
        $this->set('default_select_fields', $select_fields);
        $this->set('default_select_show_fields', $default_select_show_fields);
//        pr($default_select_show_fields);
//        echo "<br />";
        if(!empty($show_fields)){
            $show_tmp = array_values($show_fields);
            $select_fields = array_merge($show_tmp, $select_fields);

            $cnt = count($show_fields);
            $cnt_arr = range(0,$cnt-1,1);

            foreach ($select_show_fields as $key => $show_field_value){
                $select_show_fields[$key] = $show_field_value + $cnt;
            }
//            array_walk($select_show_fields,function($v,$k,$ccnt){$select_show_fields[$k] = $v+$ccnt;}, $cnt);

            $select_show_fields = array_merge($cnt_arr, $select_show_fields);
        }

        $this->set('select_fields', $select_fields);
        $this->set('select_show_fields', $select_show_fields);
//        pr($show_fields,$select_fields,$select_show_fields,$default_select_show_fields);die;

//        $show_header_arr = $this->Reports->get_summary_report_header($select_show_fields,count($group_arr),$type);
//        pr($show_header_arr);die;
//        $this->set('show_header_arr',$show_header_arr);
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_xls');
        }
    }

    public function cascade_summary($type = 1){
        //时间
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'];

        $order_num = 0;
        $out_field_arr = array();
        $field_arr = array();
        $show_fields = array();
        $group_arr = array();
        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;


        if (!isset($_GET['group_by']) || $_GET['group_by'] == 'date')
        {
            if ($type == 1){
                $out_field_arr = array("group_time", "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                $field_arr = array("to_char(report_time, 'YYYY-MM-DD') as group_time","ingress_client_id");

                $show_fields['group_time'] = "group_time";
                $show_fields['ingress_client_id'] = "ingress_client_id";
                $group_arr = array("group_time", "ingress_client_id");


                $_GET['group_by_date'] = 'YYYY-MM-DD';
                $_GET['group_select'] = array('ingress_client_id');
            } else {
                $out_field_arr = array("group_time", "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                $field_arr = array("to_char(report_time, 'YYYY-MM-DD') as group_time","egress_client_id");
                $show_fields['group_time'] = "group_time";
                $show_fields['egress_client_id'] = "egress_client_id";
                $group_arr = array("group_time", "egress_client_id");

                $_GET['group_by_date'] = 'YYYY-MM-DD';
                $_GET['group_select'] = array('egress_client_id');
            }
            $group_by = array('Date','Carrier');
            $order_num++;
            $order_num++;
            $_GET['cascade_summary_group_by'] = 'date';
        } else{
            if ($type == 1){
                $out_field_arr = array("(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id", "group_time");
                $field_arr = array("to_char(report_time, 'YYYY-MM-DD') as group_time","ingress_client_id");

                $show_fields['ingress_client_id'] = "ingress_client_id";
                $show_fields['group_time'] = "group_time";
                $group_arr = array("ingress_client_id","group_time");


                $_GET['group_select'] = array('ingress_client_id');
                $_GET['group_by_date'] = 'YYYY-MM-DD';
            } else {
                $out_field_arr = array("(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id","group_time");
                $field_arr = array("to_char(report_time, 'YYYY-MM-DD') as group_time","egress_client_id");
                $show_fields['egress_client_id'] = "egress_client_id";
                $show_fields['group_time'] = "group_time";
                $group_arr = array("egress_client_id","group_time");

                $_GET['group_by_date'] = 'YYYY-MM-DD';
                $_GET['group_select'] = array('egress_client_id');
            }
            $group_by = array('Carrier','Date');
            $order_num++;
            $order_num++;
            $_GET['cascade_summary_group_by'] = 'carrier';
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = CDR_TABLE;
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($out_field_arr, 'ingress_rate as actual_rate');
                array_push($field_arr, 'ingress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($out_field_arr, 'egress_rate as actual_rate');
                array_push($field_arr, 'egress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        //data


        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
//            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
//                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
//            $compare_detail = array_intersect($group_arr, $from_detail);
//            if (count($compare_detail))

            $groups = "GROUP BY " . implode(',', $group_arr);
        }


        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


//        if (count($where_arr))
//        {
//            $wheres = " and " . implode(' and ', $where_arr);
//        }


        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->CdrRead->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];

        $data = array();
        if ($is_preload || $this->params['hasGet'])
        {
            $this->set('show_nodata', true);
            $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


            $select_time_end = strtotime($end_date);

            $is_from_client_cdr = false;
            if (empty($report_max_time))
            {
                $is_from_client_cdr = true;
                $report_max_time = $start_date;
            }
            $system_max_end = strtotime($report_max_time);

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->CdrsRead->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->CdrsRead->query($sql);
                }
                else
                {
                    $sql1 = $this->CdrsRead->get_cdrs($start_date, $report_max_time, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                    $sql2 = $this->CdrsRead->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    //pr($sql1);exit;
                    $sql = $this->CdrsRead->get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $sql = $this->CdrsRead->get_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->CdrsRead->query($sql);
            }

        }
        else
            $this->set('show_nodata', false);


        $tdata = array();
        $sum_arr = array();

        foreach($data as $k => $item){
            //group
            if(!isset($_GET['group_by']) || $_GET['group_by'] == 'date')
                $group_field = $item[0]['group_time'];
            else
                $group_field = $type == 1 ? $item[0]['ingress_client_id'] : $item[0]['egress_client_id'];

            if ($type == 1)
                $carrier_name = $item[0]['ingress_client_id'];
            else
                $carrier_name = $item[0]['egress_client_id'];
            $date = $item[0]['group_time'];
            $duration = $item[0]['duration'];
            $bill_time = $item[0]['bill_time'];
            $call_cost = $item[0]['call_cost'];
            $cancel_calls = $item[0]['cancel_calls'];
            $total_calls = $item[0]['total_calls'];
            $inter_cost = $item[0]['inter_cost'];
            $intra_cost = $item[0]['intra_cost'];
            $not_zero_calls = $item[0]['not_zero_calls'];
            $success_calls = $item[0]['success_calls'];
            $busy_calls = $item[0]['busy_calls'];
            $pdd = $item[0]['pdd'];
            $q850_cause_count = $item[0]['q850_cause_count'];
            $call_limit = $item[0]['call_limit'];
            $cps_limit = $item[0]['cps_limit'];
            $sd_count = $item[0]['sd_count'];

            @$sum_arr[$group_field]['sum_duration'] += $duration;
            @$sum_arr[$group_field]['sum_bill_time'] += $bill_time;
            @$sum_arr[$group_field]['sum_call_cost'] += $call_cost;
            @$sum_arr[$group_field]['sum_cancel_calls'] += $cancel_calls;
            @$sum_arr[$group_field]['sum_total_calls'] += $total_calls;
            @$sum_arr[$group_field]['sum_inter_cost'] += $inter_cost;
            @$sum_arr[$group_field]['sum_intra_cost'] += $intra_cost;
            @$sum_arr[$group_field]['sum_not_zero_calls'] += $not_zero_calls;
            @$sum_arr[$group_field]['sum_success_calls'] += $success_calls;
            @$sum_arr[$group_field]['sum_busy_calls'] += $busy_calls;
            @$sum_arr[$group_field]['sum_pdd'] += $pdd;
            @$sum_arr[$group_field]['sum_q850_cause_count'] += $q850_cause_count;
            @$sum_arr[$group_field]['sum_call_limit'] += $call_limit;
            @$sum_arr[$group_field]['sum_cps_limit'] += $cps_limit;
            @$sum_arr[$group_field]['sum_sd_count'] += $sd_count;

            $arr = array();

            $arr['carrier'] = $carrier_name;
            $arr['date'] = $date;
            $arr['self'] = isset($_GET['group_by']) && ($_GET['group_by'] == 'carrier') ? $date : $carrier_name;
            //公共数据
            $arr['abr'] = $total_calls == 0 ? 0 : $not_zero_calls / $total_calls * 100;
            $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
            $arr['asr'] = $_asr == 0 ? 0 : $not_zero_calls / $_asr * 100;
            $arr['acd'] = $not_zero_calls == 0 ? 0 : $duration / $not_zero_calls / 60;
            $arr['aloc'] = $arr['asr'] * $arr['acd'];
            $arr['pdd'] = $not_zero_calls == 0 ? 0 : $pdd / $not_zero_calls;
            $arr['ner'] = $total_calls == 0 ? 0 : $q850_cause_count / $total_calls * 100;
            $arr['duration'] = $duration / 60;
            $arr['bill_time'] = $bill_time / 60;
            $arr['call_cost'] = $call_cost;
            $arr['total_calls'] = $total_calls;
            $arr['not_zero_calls'] = $not_zero_calls;
            $arr['success_calls'] = $success_calls;
            $arr['busy_calls'] = $busy_calls;

            $arr['profit'] = $call_cost;
            $arr['limited'] = $cps_limit == 0 ? 0 :$call_limit / $cps_limit;

            $arr['sd_count'] = $sd_count;
            $arr['sdp'] = $not_zero_calls == 0 ? 0 : $sd_count / $total_calls * 100;



            //ingress
            if ($type == 1){
                $npr_value = $item[0]['npr_value'];
                $egress_cost = $item[0]['egress_cost'];
                $nrf_count = $item[0]['nrf_count'];
                @$sum_arr[$group_field]['sum_npr_value'] += $npr_value;
                @$sum_arr[$group_field]['sum_egress_cost'] += $egress_cost;
                @$sum_arr[$group_field]['sum_nrf_count'] += $nrf_count;


                $arr['npr_count'] =$npr_value;
                $arr['npr'] = $total_calls == 0 ? 0 : $npr_value / $total_calls * 100;
                $arr['nrf_count'] =$nrf_count;
                $arr['nrf'] = $total_calls == 0 ? 0 : $nrf_count / $total_calls * 100;


                $arr['revenue'] = $call_cost;
                $arr['profit'] = $call_cost - $egress_cost;
                $arr['margin'] = $call_cost == 0 ? 0 : $arr['profit'] / $call_cost;
                $arr['pp_min'] = $bill_time == 0 ? 0 : $arr['profit'] / ($bill_time / 60);
                $arr['pp_k_calls'] = $total_calls == 0 ? 0 : $arr['profit'] / (1000*$total_calls);
            }

            //egress

            //show_inter_intra
            $arr['inter_cost'] = $inter_cost;
            $arr['intra_cost'] = $intra_cost;
            @$sum_arr[$group_field]['sum_inter_cost'] += $inter_cost;
            @$sum_arr[$group_field]['sum_intra_cost'] += $intra_cost;


            //rate_display_as
            if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
            {
                $arr['rate'] = $item[0]['actual_rate'];
                @$sum_arr[$group_field]['sum_actual_rate'] += $item[0]['actual_rate'];
            } else {
                $arr['rate'] = $bill_time == 0 ? 0 : $call_cost / ($bill_time / 60);
            }


            $tdata[$group_field][] = $arr;
        }

        foreach($sum_arr as $k => $v){

            $duration = $v['sum_duration'];
            $bill_time = $v['sum_bill_time'];
            $call_cost = $v['sum_call_cost'];
            $cancel_calls = $v['sum_cancel_calls'];
            $total_calls = $v['sum_total_calls'];
            $inter_cost = $v['sum_inter_cost'];
            $intra_cost = $v['sum_intra_cost'];
            $not_zero_calls = $v['sum_not_zero_calls'];
            $success_calls = $v['sum_success_calls'];
            $busy_calls = $v['sum_busy_calls'];
            $pdd = $v['sum_pdd'];
            $q850_cause_count = $v['sum_q850_cause_count'];
            $call_limit = $v['sum_call_limit'];
            $cps_limit = $v['sum_cps_limit'];
            $sd_count = $v['sum_sd_count'];

            $arr = array();
            //公共数据
            $arr['abr'] = $total_calls == 0 ? 0 : $not_zero_calls / $total_calls * 100;
            $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
            $arr['asr'] = $_asr == 0 ? 0 : $not_zero_calls / $_asr * 100;
            $arr['acd'] = $not_zero_calls == 0 ? 0 : $duration / $not_zero_calls / 60;
            $arr['aloc'] = $arr['asr'] * $arr['acd'];
            $arr['pdd'] = $not_zero_calls == 0 ? 0 : $pdd / $not_zero_calls;
            $arr['ner'] = $total_calls == 0 ? 0 : $q850_cause_count / $total_calls * 100;
            $arr['duration'] = $duration / 60;
            $arr['bill_time'] = $bill_time / 60;
            $arr['call_cost'] = $call_cost;
            $arr['total_calls'] = $total_calls;
            $arr['not_zero_calls'] = $not_zero_calls;
            $arr['success_calls'] = $success_calls;
            $arr['busy_calls'] = $busy_calls;

            $arr['profit'] = $call_cost;
            $arr['limited'] = $cps_limit == 0 ? 0 :$call_limit / $cps_limit;

            $arr['sd_count'] = $sd_count;
            $arr['sdp'] = $not_zero_calls == 0 ? 0 : $sd_count / $total_calls * 100;



            //ingress
            if ($type == 1){
                $npr_value = $v['sum_npr_value'];
                $egress_cost = $v['sum_egress_cost'];
                $nrf_count = $v['sum_nrf_count'];



                $arr['npr_count'] =$npr_value;
                $arr['npr'] = $total_calls == 0 ? 0 : $npr_value / $total_calls * 100;
                $arr['nrf_count'] =$nrf_count;
                $arr['nrf'] = $total_calls == 0 ? 0 : $nrf_count / $total_calls * 100;


                $arr['revenue'] = $call_cost;
                $arr['profit'] = $call_cost - $egress_cost;
                $arr['margin'] = $call_cost == 0 ? 0 : $arr['profit'] / $call_cost;
                $arr['pp_min'] = $bill_time == 0 ? 0 : $arr['profit'] / ($bill_time / 60);
                $arr['pp_k_calls'] = $total_calls == 0 ? 0 : $arr['profit'] / (1000*$total_calls);
            }

            //egress

            //show_inter_intra
            $arr['inter_cost'] = $inter_cost;
            $arr['intra_cost'] = $intra_cost;


            //rate_display_as
            if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
            {
                $arr['rate'] = $v['sum_actual_rate'];
            } else {
                $arr['rate'] = $bill_time == 0 ? 0 : $call_cost / ($bill_time / 60);
            }


            array_unshift($tdata[$k],$arr);
        }

        krsort($tdata);







        //选择显示field
        if($type == 1){
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('NPR Count', true),
                    7 => __('NPR', true),
                    8 => __('NRF Count', true),
                    9 => __('NRF', true),
                    10 => __('Revenue', true),
                    11 => __('Profit', true),
                    12 => __('Margin', true),
                    13 => __('PP Min', true),
                    14 => __('PP K Calls', true),
                    15 => __('SD Count', true),
                    16 => __('SDP', true),
                    17 => __('Limited', true),
                    18 => __('Total Duration', true),
                    19 => __('Total Billable Time', true),
                    20 => __('Total Cost', true),
                    21 => __('Inter Cost', true),
                    22 => __('Intra Cost', true),
                    23 => __('Actual Rate / Avg Rate', true),
                    24 => __('Total Calls', true),
                    25 => __('Not Zero Calls', true),
                    26 => __('Success Calls', true),
                    27 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    9,
                    20,
                    21,
                    22,
                    25,
                    26,
                    27,
                    28,
                    29
                );






        }
        else {
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('SD Count', true),
                    7 => __('SDP', true),
                    8 => __('Limited', true),
                    9 => __('Total Duration', true),
                    10 => __('Total Billable Time', true),
                    11 => __('Total Cost', true),
                    12 => __('Inter Cost', true),
                    13 => __('Intra Cost', true),
                    14 => __('Actual Rate / Avg Rate', true),
                    15 => __('Total Calls', true),
                    16 => __('Not Zero Calls', true),
                    17 => __('Success Calls', true),
                    18 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    11,
                    12,
                    13,
                    16,
                    17,
                    18,
                    19,
                    20
                );
        }

        isset($_GET['query']['fields']) && ($select_show_fields = $_GET['query']['fields']);
        $select_fields = array_merge($group_by,$select_fields);

        $this->set('data', $tdata);

        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('group_by', $group_by);

        $this->set('select_fields', $select_fields);
        $this->set('select_show_fields', $select_show_fields);
        $this->set('type', $type);


        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select * from users where user_id = {$user_id} ");
        $this->set('is_term', $res[0][0]['all_termination']);


    }

    public function get_cascade_data(){
        Configure::write('debug',0);
        exit;
    }

    public function inout_report()
    {
        $this->pageTitle = "Statistics/Inbound/Outbound Report";


        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        extract($this->Cdr->get_start_end_time());

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;


        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();






        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            array_push($out_field_arr, "group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }


        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();



        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        $this->set('report_name', "Inbound Outbound Report");
        if (isset($this->params['url']['is_scheduled_report']) && $this->params['url']['is_scheduled_report'])
        {
            $query_ = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
            $query_tmp = str_replace($report_max_time, "[report_max_time]", $query_);
            $query = str_replace($end_date, "[end_time]", $query_tmp);

            $sql1_tmp = $this->Cdrs->get_inout_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
            $sql2_tmp = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
            $query_2 = $this->Cdrs->get_inout_from_two($sql1_tmp, $sql2_tmp, $orders, $show_fields);
            $query2_tmp = str_replace($report_max_time, "[report_max_time]", $query_2);
            $query2_tmp_ = str_replace($start_date, "[start_time]", $query2_tmp);
            $query2 = str_replace($end_date, "[end_time]", $query2_tmp_);

            $query_3 = $this->Cdrs->get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
            $query3_tmp = str_replace($start_date, "[start_time]", $query_3);
            $query3 = str_replace($end_date, "[end_time]", $query3_tmp);

            $scheduled_report_data = $this->params['url']['scheduled_report'];
            $this->scheduled_report2($query, $scheduled_report_data, $query2, $query3);
        }
        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $sql = $this->CdrsRead->get_inout_cdrs($start_date, $end_date, $fields,$out_fields, $groups, $orders, $wheres, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }



        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=inbound_outbound_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('inout_report_down_csv');
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
            header("Content-Disposition: attachment;filename=inbound_outbound_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('inout_report_down_xls');
        }
    }

    public function usagereport($type = 1)
    {
        $this->pageTitle = "Statistics/Usage Report";

        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;
        $this->set('cdr_db', $this->Cdr);
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }



        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }


        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $table_name = CDR_TABLE;
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $table_name = CDR_TABLE;
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $table_name = CDR_TABLE;
            $wheres = " and " . implode(' and ', $where_arr);
        }


        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'Ingress Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);

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

        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $data = $this->CdrsRead->get_usagereport($start_date, $end_date, $fields,$out_fields, $groups, $orders, $wheres, $type, $table_name);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }


        //$data = $this->Cdrs->get_usagereport($start_date, $end_date, $gmt, $fields, $groups, $orders, $wheres, $type, $table_name);

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();
        $this->set('type', $type);
        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=org_usage_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('org_usage_report_csv');
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
            header("Content-Disposition: attachment;filename=term_usage_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('term_usage_report_csv');
        }
    }

    public function location()
    {
        $this->pageTitle = "Statistics/Location Report";


        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        extract($this->Cdr->get_start_end_time());

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;


        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();




        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        $_GET['group_select'][0] = 'ingress_country';

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }


        $group_arr = array_unique($group_arr);
        $field_arr = array_unique($field_arr);
        $out_field_arr = array_unique($out_field_arr);

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();



        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $sql = $this->CdrsRead->get_inout_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }



        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=location_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('location_down_csv');
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
            header("Content-Disposition: attachment;filename=location_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('location_down_xls');
        }
    }

    public function profit($type = 1)
    {
        $this->pageTitle = "Statistics/Profitability Analysis";

        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;
        $this->set('cdr_db', $this->Cdr);
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }



        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if ($type == 1)
            $_GET['group_select'][0] = 'ingress_id';
        else
            $_GET['group_select'][0] = 'egress_id';

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id,ingress_client_id as delete_ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id,egress_client_id as delete_egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id,ingress_id as delete_ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id,egress_id as delete_egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        $this->set('report_name', "Profitability Analysis");
        if (isset($this->params['url']['is_scheduled_report']) && $this->params['url']['is_scheduled_report'])
        {
            $query_ = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
            $query_tmp = str_replace($report_max_time, "[report_max_time]", $query_);
            $query = str_replace($end_date, "[end_time]", $query_tmp);

            $sql1_tmp = $this->Cdrs->get_profit_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
            $sql2_tmp = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
            $query_2 = $this->Cdrs->get_profit_from_two($sql1_tmp, $sql2_tmp, $type, $orders, $show_fields);
            $query2_tmp = str_replace($report_max_time, "[report_max_time]", $query_2);
            $query2_tmp_ = str_replace($start_date, "[start_date]", $query2_tmp);
            $query2 = str_replace($end_date, "[end_time]", $query2_tmp_);

            $query_3 = $this->Cdrs->get_profit_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
            $query3_tmp = str_replace($start_date, "[start_date]", $query_3);
            $query3 = str_replace($end_date, "[end_time]", $query3_tmp);

            $scheduled_report_data = $this->params['url']['scheduled_report'];
            $this->scheduled_report2($query, $scheduled_report_data, $query2, $query3);
        }
        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $sql = $this->CdrsRead->get_profit_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=profit_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('profit_down_csv');
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
            header("Content-Disposition: attachment;filename=profit_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('profit_down_xls');
        }
    }

    public function premature_abandon($type = 1)
    {
        $this->pageTitle = "Statistics/Premature Abandon";

        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;
        $this->set('cdr_db', $this->Cdr);
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }



        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if ($type == 1)
            $_GET['group_select'][0] = 'ingress_id';
        else
            $_GET['group_select'][0] = 'egress_id';

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        //$this->set('report_name', "Profitability Analysis");
        /* if (isset($this->params['url']['is_scheduled_report']) && $this->params['url']['is_scheduled_report'])
          {
          $query_ = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
          $query_tmp = str_replace($report_max_time, "[report_max_time]", $query_);
          $query = str_replace($end_date, "[end_time]", $query_tmp);

          $sql1_tmp = $this->Cdrs->get_profit_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
          $sql2_tmp = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
          $query_2 = $this->Cdrs->get_profit_from_two($sql1_tmp, $sql2_tmp, $type, $orders, $show_fields);
          $query2_tmp = str_replace($report_max_time, "[report_max_time]", $query_2);
          $query2_tmp_ = str_replace($start_date, "[start_date]", $query2_tmp);
          $query2 = str_replace($end_date, "[end_time]", $query2_tmp_);

          $query_3 = $this->Cdrs->get_profit_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
          $query3_tmp = str_replace($start_date, "[start_date]", $query_3);
          $query3 = str_replace($end_date, "[end_time]", $query3_tmp);

          $scheduled_report_data = $this->params['url']['scheduled_report'];
          $this->scheduled_report2($query, $scheduled_report_data, $query2, $query3);

          } */
        if (isset($_GET['show_type']) || $is_preload)
        {

            /*
              if ($select_time_end > $system_max_end)
              {
              if ($is_from_client_cdr)
              {
              $sql = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
              $data = $this->Cdrs->query($sql);
              }
              else
              {
              $sql1 = $this->Cdrs->get_profit_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
              $sql2 = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
              $sql = $this->Cdrs->get_profit_from_two($sql1, $sql2, $type, $orders, $show_fields);
              $data = $this->Cdrs->query($sql);
              }
              }
              else
              {
              $sql = $this->Cdrs->get_profit_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
              $data = $this->Cdrs->query($sql);
              } */

            //$sql = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date);
            //$data = $this->Cdrs->query($sql);
            //var_dump($data);
            //exit;
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        //$this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        /* if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
          {
          header("Pragma: public");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Content-Type: text/csv");
          header("Content-Type: application/octet-stream");
          header("Content-Type: application/download");
          ;
          header("Content-Disposition: attachment;filename=premature_abandon_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
          header("Content-Transfer-Encoding: binary ");
          Configure::write('debug', 0);
          $this->autoLayout = FALSE;
          $this->render('profit_down_csv');
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
          header("Content-Disposition: attachment;filename=premature_abandon_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
          header("Content-Transfer-Encoding: binary ");
          Configure::write('debug', 0);
          $this->autoLayout = FALSE;
          $this->render('profit_down_xls');
          } */
    }

    function get_count()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $id = $this->_post('id');
        parse_str($_POST['data'], $parm);

        $start_date = $parm['start_date'] . ' ' . $parm['start_time'];
        $end_date = $parm['stop_date'] . ' ' . $parm['stop_time'];
        $gmt = $parm['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;
        $ingress_id = $parm['ingress_id'];

        $data = 0;
        switch ($id)
        {
            case 'no_0_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 0, 'no');
                break;
            case 'yes_0_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 0, 'yes');
                break;
            case 'no_1_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 1, 'no');
                break;
            case 'no_2_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 2, 'no');
                break;
            case 'no_3_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 3, 'no');
                break;
            case 'no_4_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 4, 'no');
                break;
            case 'no_5_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 5, 'no');
                break;
            case 'no_6_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 6, 'no');
                break;
            case 'yes_1_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 1, 'yes');
                break;
            case 'yes_2_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 2, 'yes');
                break;
            case 'yes_3_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 3, 'yes');
                break;
            case 'yes_4_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 4, 'yes');
                break;
            case 'yes_5_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 5, 'yes');
                break;
            case 'yes_6_c':
                $data = $this->Cdrs->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 6, 'yes');
                break;
            default:
                break;
        }

        echo json_encode(array('count' => $data));
    }

    public function get_count_all($is_total=false){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        //$id = $this->_post('id');
        parse_str($_POST['data'], $parm);

        $start_date = $parm['start_date'] . ' ' . $parm['start_time'];
        $end_date = $parm['stop_date'] . ' ' . $parm['stop_time'];
        $gmt = $parm['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;
        $ingress_id = $parm['ingress_id'];

        $data = array();

        if($is_total){
            $data['no_0_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 0, 'no');

            $data['yes_0_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 0, 'yes');

        } else {
            $data['no_1_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 1, 'no');

            $data['no_2_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 2, 'no');

            $data['no_3_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 3, 'no');

            $data['no_4_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 4, 'no');

            $data['no_5_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 5, 'no');

            $data['no_6_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 6, 'no');

            $data['yes_1_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 1, 'yes');

            $data['yes_2_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 2, 'yes');

            $data['yes_3_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 3, 'yes');

            $data['yes_4_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 4, 'yes');

            $data['yes_5_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 5, 'yes');

            $data['yes_6_c'] = $this->CdrsRead->get_premature_from_client_cdr($start_date, $end_date, $ingress_id, 6, 'yes');

        }



        echo json_encode($data);
    }

    public function bandwidth()
    {
        $this->pageTitle = "Statistics/Bandwidth Report";

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }



        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    else
                        array_push($field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }


        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
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
        if (isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_bandwidth_from_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->query($sql);
                }
                else
                {
                    $sql1 = $this->Cdrs->get_bandwidth($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
                    $sql2 = $this->Cdrs->get_bandwidth_from_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->get_bandwidth_two($sql1, $sql2, $orders, $show_fields);
                }
            }
            else
            {
                $sql = $this->Cdrs->get_bandwidth($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('bandwidth_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('bandwidth_down_xls');
        }
    }

    public function did($type = 1)
    {
        $this->pageTitle = "Statistics/DID Report";

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    else
                        array_push($field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        array_push($group_arr, 'did');

        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'ingress_ip', 'egress_ip');
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();


        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if (isset($_GET['show_type']) || $is_preload)
        {

            $data = $this->Cdrs->get_did_report($start_date, $end_date, $fields, $groups, $orders, $wheres, $type);
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('did_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('did_xls');
        }
    }

    public function qos_summary($type = 1)
    {
        $this->pageTitle = "Statistics/QoS Summary";

        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->set('cdr_db', $this->Cdr);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = CDR_TABLE;
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($out_field_arr, 'ingress_rate as actual_rate');
                array_push($field_arr, 'ingress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($out_field_arr, 'egress_rate as actual_rate');
                array_push($field_arr, 'egress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        if (isset($_GET['show_type']) || $is_preload)
        {
            $end_date = $report_max_time;
            if ($system_max_end == strtotime($start_date)){
                $data = array();
            }else{
                $end_date = date('Y-m-d H:i:s',strtotime($end_date) - 1);
                $sql = $this->CdrsRead->get_qos_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('qos_summary_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('qos_summary_down_xls');
        }
    }

    public function user_summary($type = 1)
    {
        $this->pageTitle = "Reports/Summary Report";

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;


        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    array_push($field_arr, $group_select);
                    array_push($out_field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if($type == 1){
            array_push($where_arr, "ingress_client_id = {$_SESSION['sst_client_id']}");
            if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
                array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        } else {
            array_push($where_arr, "egress_client_id = {$_SESSION['sst_client_id']}");
            if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
                array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        }


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "report_time");
            array_push($out_field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }
        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all') {
            if ($_GET['route_prefix'] == '') {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['term_code']) && !empty($_GET['term_code'])) {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if (isset($_GET['orig_code']) && !empty($_GET['orig_code'])) {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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
        $ingress_trunks = $this->Cdrs->get_ingress_trunks($_SESSION['sst_client_id']);
        $egress_trunks = $this->Cdrs->get_egress_trunks($_SESSION['sst_client_id']);
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
        $is_preload_result = $this->CdrsRead->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        $is_user = true;
        if (isset($_GET['show_type']) || $is_preload)
        {
            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->CdrsRead->get_user_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->CdrsRead->query($sql);
                }
                else
                {
                    $sql1 = $this->CdrsRead->get_cdrs($start_date, $report_max_time, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name, $is_user);
                    $sql2 = $this->CdrsRead->get_user_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $sql = $this->CdrsRead->get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $sql = $this->CdrsRead->get_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name, $is_user);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

//$this->set('switch_ips', $switch_ips);
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report,all_termination from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->set("all_termination", $res[0][0]['all_termination']);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $this->set("type", $type);
    }

    public function host_based_report($type = 1){
        $this->pageTitle = 'Host Based Report';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $wheres = '';
        if (isset($_GET['client']) && $_GET['client'] !== '')
        {
            $wheres = ($type == 1) ? "and ingress_client_id = ".$_GET['client'] : "and egress_client_id = ".$_GET['client'];
        }

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;





        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();


        //分表
        $date_arr = $this->Cdr->_app_get_date_result_admin($start_time,$end_time,'host_based_report2%');

        if ($type == 1){
            $org_sql = '';
            foreach($date_arr as $value){
                $table_date = 'host_based_report'.$value;

                $union = "";

                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
*
from {$table_date}
where report_time between '{$start_time}' and '{$end_time}'
{$wheres}

";

            }

            $sql = "SELECT
ingress_client_id,ingress_ip,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls,
sum(duration) as duration,
avg(ingress_avg_rate) as avg_rate
from  ( {$org_sql} ) as t1 group by ingress_client_id,ingress_ip order by ingress_client_id asc,ingress_ip asc";
            $count_sql = "SELECT ingress_client_id,ingress_ip from  ( {$org_sql} ) as t1 group by ingress_client_id,ingress_ip";


        } else if($type == 2) {
            $org_sql = '';
            foreach($date_arr as $value){
                $table_date = 'host_based_report'.$value;

                $union = "";

                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
*
from {$table_date}
where report_time between '{$start_time}' and '{$end_time}'
{$wheres}

";

            }

            $sql = "SELECT
egress_client_id,egress_ip,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls,
sum(duration) as duration,
avg(egress_avg_rate) as avg_rate
from  ( {$org_sql} ) as t1 group by egress_client_id,egress_ip order by egress_client_id asc,egress_ip asc";
            $count_sql = "SELECT egress_client_id,egress_ip from  ( {$org_sql} ) as t1 group by egress_client_id,egress_ip";


        }

        $count = $this->CdrsRead->query($count_sql);
        $count = count($count);
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $pageSize = $page->getPageSize();
        $currPage = $page->getCurrPage() - 1;
        $offset = $currPage * $pageSize;



        $sql .= " limit '$pageSize' offset '$offset'";
        $results = $this->CdrsRead->query($sql);
        $page->setDataArray($results);

        $data = array();

        if($type == 1){
            foreach($results as $v){
                $tem = array();
                $tem['client_id'] = $v[0]['ingress_client_id'];
                $tem['ip'] = $v[0]['ingress_ip'];
                $tem['total_call'] = $v[0]['total_calls'];
                $tem['not_zero_calls'] = $v[0]['not_zero_calls'];
                $tem['asr'] = ($v[0]['busy_calls'] + $v[0]['cancel_calls'] + $v[0]['not_zero_calls']) == 0 ? 0 : round($v[0]['not_zero_calls'] / ($v[0]['busy_calls'] + $v[0]['cancel_calls'] + $v[0]['not_zero_calls']) * 100, 2);
                $tem['acd'] = round($v[0]['not_zero_calls'] == 0 ? 0 : $v[0]['duration'] / $v[0]['not_zero_calls'] / 60, 2);
                $tem['pdd'] = round($v[0]['not_zero_calls'] == 0 ? 0 : $v[0]['pdd'] / $v[0]['not_zero_calls']);;
                $tem['cost'] = round($v[0]['call_cost'],2);
                $tem['avg_rate'] = number_format($v[0]['avg_rate'],6);

                $data[] = $tem;
            }
        } else {
            foreach($results as $v){
                $tem = array();
                $tem['client_id'] = $v[0]['egress_client_id'];
                $tem['ip'] = $v[0]['egress_ip'];
                $tem['total_call'] = $v[0]['total_calls'];
                $tem['not_zero_calls'] = $v[0]['not_zero_calls'];
                $tem['asr'] = ($v[0]['busy_calls'] + $v[0]['cancel_calls'] + $v[0]['not_zero_calls']) == 0 ? 0 : round($v[0]['not_zero_calls'] / ($v[0]['busy_calls'] + $v[0]['cancel_calls'] + $v[0]['not_zero_calls']) * 100, 2);
                $tem['acd'] = round($v[0]['not_zero_calls'] == 0 ? 0 : $v[0]['duration'] / $v[0]['not_zero_calls'] / 60, 2);
                $tem['pdd'] = round($v[0]['not_zero_calls'] == 0 ? 0 : $v[0]['pdd'] / $v[0]['not_zero_calls']);;
                $tem['cost'] = round($v[0]['call_cost'],2);
                $tem['avg_rate'] = number_format($v[0]['avg_rate'],6);

                $data[] = $tem;
            }
        }






        $this->set('clients', $this->Cdr->findClient());
        $this->set('type', $type);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('data', $data);
        $this->set('p', $page);

    }


    public function commission()
    {
        //
        $group_by = isset($_GET['group_by']) ? $_GET['group_by'] : 'daily';

        //时间
        $start_date = date('Y-m-d 00:00:00');
        $end_date = date('Y-m-d 23:59:59');
        $gmt = "+0000";


        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;




        //data
        $sql = "select agent.agent_id,agent.agent_name,agent_clients.client_id,client.name,agent.commission as commission2,agent_clients.commission as commission1 from
                agent left join agent_clients ON agent.agent_id=agent_clients.agent_id LEFT join client ON agent_clients.client_id=client.client_id
               WHERE agent.status=true ";
        $rst = $this->CdrsRead->query($sql);
        $agent_arr = array();
        foreach($rst as $item){
            $item[0]['commission'] = empty($item[0]['commission1']) ? $item[0]['commission2'] : $item[0]['commission1'];
            $agent_arr[$item[0]['client_id']] = $item[0];
        }
        $client_ids = isset($_GET['carrier']) ? $_GET['carrier'] : array_keys($agent_arr);
        $client_ids = implode(',', $client_ids);


        switch($group_by){
            case 'hourly':
                $fields = "to_char(report_time, 'YYYY-MM-DD HH:00:00') as group_time,ingress_client_id,";
                break;
            case 'daily':
                $fields = "to_char(report_time, 'YYYY-MM-DD') as group_time,ingress_client_id,";
                break;
            case 'weekly':
                $fields = "extract(WEEK FROM report_time) as group_time,ingress_client_id,";
                break;
            case 'monthly':
                $fields = "to_char(report_time, 'YYYY-MM-01') as group_time,ingress_client_id,";
                break;
        }

        $out_fields = "group_time,ingress_client_id as client_id,";
        $groups = "group by group_time,ingress_client_id";
        $orders ="order by 1,2";
        $wheres = "and ingress_client_id in ($client_ids)";
        //$table_name = "cdr_report";
        $table_name = CDR_TABLE;


        $sql = $this->CdrsRead->get_agent_report($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres,$table_name);
        $data = $this->CdrsRead->query($sql);










        $tdata = array();
        $sum_arr = array();

        foreach($data as $k => $item){


            $date = $item[0]['group_time'];
            $client_id = $item[0]['client_id'];

            $total_calls = $item[0]['total_calls'];
            $not_zero_calls = $item[0]['not_zero_calls'];
            $bill_time = $item[0]['bill_time'];
            $call_cost = $item[0]['call_cost'];
            $cancel_calls = $item[0]['cancel_calls'];
            $success_calls = $item[0]['success_calls'];
            $busy_calls = $item[0]['busy_calls'];
            $duration = $item[0]['duration'];




            $arr = array();


            if($group_by == 'weekly'){
                isset($first_day) || $first_day = strtotime(date('Y-01-01'));
                $date = strtotime('+'.($date-1).' week', $first_day);
                $base_w = date ( "w", $date);
                $date1 = strtotime('-'.$base_w.' day', $date);
                $date2 = strtotime('+6 day', $date1);
                $date = date('Y-m-d', $date1) . '~' . date('Y-m-d', $date2);
            }
            $arr['date'] = $date;
            if(!array_key_exists($client_id,$agent_arr)) continue;
            $arr['agent'] = $agent_arr[$client_id]['agent_name'];
            $arr['carrier'] = $agent_arr[$client_id]['name'];
            $arr['commission'] = $call_cost * $agent_arr[$client_id]['commission'] / 100;
//            $arr['self'] = isset($_GET['group_by']) && ($_GET['group_by'] == 'carrier') ? $date : $carrier_name;
            //公共数据
            $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
            $arr['asr'] = $_asr == 0 ? 0 : $not_zero_calls / $_asr * 100;
            $arr['acd'] = $not_zero_calls == 0 ? 0 : $duration / $not_zero_calls / 60;
            $arr['minutes'] = $bill_time / 60;
            $arr['cost'] = $call_cost;
            $arr['total_calls'] = $total_calls;
            $arr['not_zero_calls'] = $not_zero_calls;


            @$sum_arr[$arr['date']][$arr['agent']]['sum_bill_time'] += $bill_time;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_call_cost'] += $call_cost;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_cancel_calls'] += $cancel_calls;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_total_calls'] += $total_calls;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_not_zero_calls'] += $not_zero_calls;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_success_calls'] += $success_calls;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_busy_calls'] += $busy_calls;
            @$sum_arr[$arr['date']][$arr['agent']]['sum_duration'] += $duration;

            isset($sum_arr[$arr['date']][$arr['agent']]['commission']) || ($sum_arr[$arr['date']][$arr['agent']]['commission'] = $agent_arr[$client_id]['commission']);




            $tdata[$arr['date']][$arr['agent']][] = $arr;
        }



        foreach($sum_arr as $date => $agents){
            foreach($agents as $k =>$v){
                $duration = $v['sum_duration'];
                $bill_time = $v['sum_bill_time'];
                $call_cost = $v['sum_call_cost'];
                $cancel_calls = $v['sum_cancel_calls'];
                $total_calls = $v['sum_total_calls'];
                $not_zero_calls = $v['sum_not_zero_calls'];
                $success_calls = $v['sum_success_calls'];
                $busy_calls = $v['sum_busy_calls'];
                $commission = $v['commission'];

                $arr = array();

                //公共数据
                $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
                $arr['asr'] = $_asr == 0 ? 0 : $not_zero_calls / $_asr * 100;
                $arr['acd'] = $not_zero_calls == 0 ? 0 : $duration / $not_zero_calls / 60;
                $arr['minutes'] = $bill_time / 60;
                $arr['cost'] = $call_cost;
                $arr['not_zero_calls'] = $not_zero_calls;
                $arr['total_calls'] = $total_calls;
                $arr['commission'] = $call_cost * $commission / 100;

                $arr['date'] = $date;
                $arr['agent'] = $k;

                array_unshift($tdata[$date][$k],$arr);
            }


        }









        $this->set('data', $tdata);

        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('group_by', $group_by);






        //carrier
        $carrieres = array();
        foreach($agent_arr as $k => $item){
            $carrieres[$k] = $item['name'];
        }
        $this->set('carrieres',$carrieres);
        $select_carrier = isset($_GET['carrier']) ? $_GET['carrier'] : array_keys($carrieres);
        $this->set('select_carrier',$select_carrier);

    }


    public function agent_summary($type = 1)
    {
        if ($this->Session->read('login_type') != 2 || $type != 1)
            $this->redirect("/homes/logout");
        $this->pageTitle = "Statistics/Summary Report";
        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;
        $this->set('cdr_db', $this->Cdr);
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);

            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            }
            else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {

            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['server_ip']) && $_GET['server_ip'] != '')
        {

            array_push($where_arr, "origination_destination_host_name = '{$_GET['server_ip']}'");
        }


        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = CDR_TABLE;
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = CDR_TABLE;
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = CDR_TABLE;
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($out_field_arr, 'ingress_rate as actual_rate');
                array_push($field_arr, 'ingress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($out_field_arr, 'egress_rate as actual_rate');
                array_push($field_arr, 'egress_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = CDR_TABLE;
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

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

        $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);


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
        $this->set('report_name', "Summary Report");
        if (isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->CdrsRead->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->CdrsRead->query($sql);
                }
                else
                {
                    $sql1 = $this->CdrsRead->get_cdrs($start_date, $report_max_time, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                    $sql2 = $this->CdrsRead->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    //pr($sql1);exit;
                    $sql = $this->CdrsRead->get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $sql = $this->CdrsRead->get_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->CdrsRead->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

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
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        //选择显示field
        if($type == 1){
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('NPR Count', true),
                    7 => __('NPR', true),
                    8 => __('NRF Count', true),
                    9 => __('NRF', true),
                    10 => __('Revenue', true),
                    11 => __('Profit', true),
                    12 => __('Margin', true),
                    13 => __('PP Min', true),
                    14 => __('PP K Calls', true),
                    15 => __('SD Count', true),
                    16 => __('SDP', true),
                    17 => __('Limited', true),
                    18 => __('Total Duration', true),
                    19 => __('Total Billable Time', true),
                    20 => __('Total Cost', true),
                    21 => __('Inter Cost', true),
                    22 => __('Intra Cost', true),
                    23 => __('Actual Rate / Avg Rate', true),
                    24 => __('Total Calls', true),
                    25 => __('Not Zero Calls', true),
                    26 => __('Success Calls', true),
                    27 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    7,
                    18,
                    19,
                    20,
                    23,
                    24,
                    25,
                    26,
                    27
                );




        } else {
            $select_fields =
                array(
                    0 => __('ABR', true),
                    1 => __('ASR', true),
                    2 => __('ACD', true),
                    3 => __('ALOC', true),
                    4 => __('PDD', true),
                    5 => __('NER', true),
                    6 => __('SD Count', true),
                    7 => __('SDP', true),
                    8 => __('Limited', true),
                    9 => __('Total Duration', true),
                    10 => __('Total Billable Time', true),
                    11 => __('Total Cost', true),
                    12 => __('Inter Cost', true),
                    13 => __('Intra Cost', true),
                    14 => __('Actual Rate / Avg Rate', true),
                    15 => __('Total Calls', true),
                    16 => __('Not Zero Calls', true),
                    17 => __('Success Calls', true),
                    18 => __('Busy Calls', true),
                );

            $select_show_fields =
                array(
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    9,
                    10,
                    11,
                    14,
                    15,
                    16,
                    17,
                    18
                );
        }


        isset($_GET['query']['fields']) && ($select_show_fields = $_GET['query']['fields']);



        if(!empty($show_fields)){
            $show_tmp = array_values($show_fields);
            $select_fields = array_merge($show_tmp, $select_fields);

            $cnt = count($show_fields);
            $cnt_arr = range(0,$cnt-1,1);

            array_walk($select_show_fields,function(&$v,$k,$ccnt){$v = $v+$ccnt;}, $cnt);

            $select_show_fields = array_merge($cnt_arr, $select_show_fields);
        }

        $this->set('select_fields', $select_fields);
        $this->set('select_show_fields', $select_show_fields);


        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_xls');
        }
    }



}

?>
