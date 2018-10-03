<?php

class ReportsController extends AppController
{

    var $name = "Reports";
    var $uses = array('Cdrs', 'Cdr', 'Resource', 'UsageReportDelivery', 'ReportDeliveryHistory', 'SwitchProfile','CdrsRead');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        parent::beforeFilter();
    }

    public function user_summary()
    {
        $type = 1;
        $this->pageTitle = "Reports/Summary Report";

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

        array_push($where_arr, "ingress_client_id = {$_SESSION['sst_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
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
                    $sql = $this->Cdrs->get_user_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->query($sql);
                }
                else
                {
                    $sql1 = $this->Cdrs->get_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
                    $sql2 = $this->Cdrs->get_user_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
                }
            }
            else
            {
                $sql = $this->Cdrs->get_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

//var_dump($data);
//$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $this->set("type", $type);
    }

    public function summary($type = 1)
    {
//pr($this->params['url']);die;
        $this->pageTitle = "Statistics/Summary Report";

        $this->get_report_data($type);

        if (isset($this->params['url']['query']))
        {
            if (isset($this->params['url']['show_type']) && $this->params['url']['show_type'] == '1')
            {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=summary_report_{$this->params['url']['start_date']}_{$this->params['url']['stop_date']}.csv");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('summary_down_csv');
            }
            else if (isset($this->params['url']['show_type']) && $this->params['url']['show_type'] == '2')
            {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=summary_report_{$this->params['url']['start_date']}_{$this->params['url']['stop_date']}.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('summary_down_xls');
            }
        }
    }

    public function usagereport($type = 1)
    {
        $this->pageTitle = "Statistics/Usage Report";

        $this->get_report_data($type);

        if (isset($this->params['url']['query']))
        {
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
    }

    public function location()
    {
        $this->pageTitle = "Statistics/Location Report";

        $this->get_report_data(1);

        if (isset($this->params['url']['query']))
        {
            if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
            {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");

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
    }

    public function profit($type = 1)
    {
        $this->pageTitle = "Statistics/Profitability Analysis";

        $this->get_report_data($type);

        if (isset($this->params['url']['query']))
        {

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

    public function did()
    {
        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->pageTitle = "Statistics/DID Report";

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $outer_field_arr = array();
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
            array_push($field_arr, "report_time");
            array_push($outer_field_arr,"to_char(report_time, '{$_GET['group_by_date']}') as group_time  ");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");

        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");

        if (isset($_GET['did']) && !empty($_GET['did']))
            array_push($where_arr, "did like '%{$_GET['did']}%'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_id') {

                        array_push($field_arr, "ingress_id");
                        array_push($outer_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    }
                    elseif ($group_select == 'egress_id') {

                        array_push($field_arr, "egress_id");
                        array_push($outer_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    }
                    else {

                        array_push($field_arr, $group_select);
                        array_push($outer_field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        $fields = "";
        $outer_fields = '';
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($outer_field_arr))
        {
            $outer_fields = implode(',', $outer_field_arr) . ",";
        }

        if (count($group_arr))
        {
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
            'ingress_id' => 'Vendor',
            'egress_id' => 'Client',
            'did' => 'DID'
        );

        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if (isset($_GET['show_type']) || $is_preload)
        {
            //date 数组
            $date_arr = $this->_get_date_result_admin($start_date,$end_date,'did_report2%');
            $data = $this->CdrsRead->get_did_report($start_date, $end_date, $date_arr, $fields, $outer_fields, $groups, $orders, $wheres);
            if (empty($data)) {
                $this->set('show_nodata', false);
            }

            $dt = new DateTime($start_date);
            $tz = $dt->getTimezone();
            foreach ($data as &$item) {
                if (isset($item[0]['group_time'])) {
                    $dt = new DateTime($item[0]['group_time']);
                    $dt->setTimezone($tz);
                    $date = $dt->format("Y-m-d H:i:s P");
                    $item[0]['group_time'] = $date;
                }
            }
            
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        $this->set('vendors', $this->Cdr->findAll_origination_vendor('true'));
        $this->set('clients', $this->Cdr->findAll_origination_client('true'));
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);

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

        $this->get_report_data($type);

        if (isset($this->params['url']['query']))
        {

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
    }

    public function add_report_delivery()
    {
        $data = $this->Cdrs->query("select name, client_id from client order by name asc");

        $this->set('clients', $data);

        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['form'];
            if (!isset($post_arr['is_all_carrier']) && empty($post_arr['carrier']))
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'The carrier can not be empty!'));
                $this->redirect('add_report_delivery');
            }
            if (!isset($post_arr['email_to']) && empty($post_arr['email_to']))
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'The email can not be empty!'));
                $this->redirect('add_report_delivery');
            }
            $insert_arr = array(
                'type' => 1,
                'month' => 1,
                'week' => 'M',
                'time' => 0,
                'carrier' => '',
                'all_ingress' => 0,
                'all_egress' => 0,
                'ingressid' => '',
                'egressid' => '',
                'time_bucket' => 1,
                'code_bucket' => 1,
                'skip_empty' => 1,
                'email_to' => '',
                'is_all_carrier' => 0
            );

            foreach ($post_arr as $key => $value)
            {

                if (key_exists($key, $insert_arr))
                {

                    $insert_arr[$key] = $value;
                }
            }

            $carreir = implode(',', $post_arr['carrier']);
            $insert_arr['carrierid'] = $carreir;
            unset($insert_arr['carrier']);
            $insert_arr['ingressid'] = substr($insert_arr['ingressid'], 1);
            $insert_arr['egressid'] = substr($insert_arr['egressid'], 1);

            $data = $this->UsageReportDelivery->save($insert_arr);

            if ($data)
            {
//                $this->Session->write('m', $this->Cdrs->create_json(201, 'successfully!'));
                $this->redirect('report_delivery');
            }
            else
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'Save failed!'));
                $this->redirect('add_report_delivery');
            }
        }
    }

    public function report_delivery()
    {
        $list = $this->UsageReportDelivery->find('all', array('order' => array("id")));

        foreach ($list as $key => $value)
        {
            if (!$value['UsageReportDelivery']['hour'])
            {
                $value['UsageReportDelivery']['hour'] = 0;
            }
            $value['UsageReportDelivery']['hour'] .= ":00";

            switch ($value['UsageReportDelivery']['type'])
            {
                case 1 :
                    $type = 'daily';
                    $frequency = $value['UsageReportDelivery']['hour'];
                    break;
                case 2 :
                    $type = 'weekly';
                    $frequency = $value['UsageReportDelivery']['week'] . '/' . $value['UsageReportDelivery']['hour'];
                    break;
                case 3 :
                    $type = 'monthly';
                    $frequency = $value['UsageReportDelivery']['month'] . '/' . $value['UsageReportDelivery']['week'] . '/' . $value['UsageReportDelivery']['hour'];
                    break;
                default : $type = "";
                    $frequency = "";
            }
            $list[$key]['UsageReportDelivery']['type'] = $type;
            $list[$key]['UsageReportDelivery']['frequency'] = $frequency;

            switch ($value['UsageReportDelivery']['time_bucket'])
            {
                case 1 : $time_bucket = 'hourly';
                    break;
                case 2 : $time_bucket = 'daily';
                    break;
                default : $time_bucket = "";
            }
            $list[$key]['UsageReportDelivery']['time_bucket'] = $time_bucket;

            switch ($value['UsageReportDelivery']['code_bucket'])
            {
                case 1 : $code_bucket = 'by code';
                    break;
                case 2 : $code_bucket = 'by code name';
                    break;
                default : $code_bucket = "";
            }
            $list[$key]['UsageReportDelivery']['code_bucket'] = $code_bucket;

            if ($value['UsageReportDelivery']['is_all_carrier'])
            {
                $list[$key]['UsageReportDelivery']['carrier'] = 'All';
            }
            else
            {
                $list[$key]['UsageReportDelivery']['carrier'] = 'Detail';
            }

            if ($value['UsageReportDelivery']['ingressid'])
            {
                $list[$key]['UsageReportDelivery']['ingress'] = 'Detail';
            }
            else
            {
                $list[$key]['UsageReportDelivery']['ingress'] = 'Null';
            }

            if ($value['UsageReportDelivery']['egressid'])
            {
                $list[$key]['UsageReportDelivery']['egress'] = 'Detail';
            }
            else
            {
                $list[$key]['UsageReportDelivery']['egress'] = 'Null';
            }
        }

        $this->set('list', $list);
    }

    public function show_detail($type, $id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        $data = $this->UsageReportDelivery->find(array('id' => $id));

        switch ($type)
        {

            case 'Carrier' :
                $sql = "select name from client where client_id in({$data['UsageReportDelivery']['carrierid']})";
                break;
            case 'Ingress' :
                $sql = "select alias as name from resource where resource_id in({$data['UsageReportDelivery']['ingressid']})";
                break;
            case 'Egress' :
                $sql = "select alias as name from resource where resource_id in({$data['UsageReportDelivery']['egressid']})";
                break;
            default : $sql = "";
        }
        $list = array();
        if ($sql)
        {
            $list = $this->UsageReportDelivery->query($sql);
        }
        $this->set('list', $list);

        $this->set('type', $type);
    }

    public function modify_report_delivery($id)
    {
        if ($this->RequestHandler->isPost())
        {

            $post_arr = $this->params['form'];
            if (!isset($post_arr['is_all_carrier']) && empty($post_arr['carrier']))
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'The carrier can not be empty!'));
                $this->redirect('add_report_delivery');
            }//echo '<pre>';print_r($post_arr);die;
            if (!isset($post_arr['email_to']) && empty($post_arr['email_to']))
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'The email can not be empty!'));
                $this->redirect('add_report_delivery');
            }
            $insert_arr = array(
                'type' => 1,
                'month' => 1,
                'week' => 'M',
                'time' => 0,
                'carrier' => '',
                'all_ingress' => 0,
                'all_egress' => 0,
                'ingressid' => '',
                'egressid' => '',
                'time_bucket' => 1,
                'code_bucket' => 1,
                'skip_empty' => 1,
                'email_to' => '',
                'is_all_carrier' => 0,
                'id' => ''
            );

            foreach ($post_arr as $key => $value)
            {

                if (key_exists($key, $insert_arr))
                {

                    $insert_arr[$key] = $value;
                }
            }

            $carreir = implode(',', $post_arr['carrier']);
            $insert_arr['carrierid'] = $carreir;
            unset($insert_arr['carrier']);
            $insert_arr['ingressid'] = substr($insert_arr['ingressid'], 1);
            $insert_arr['egressid'] = substr($insert_arr['egressid'], 1);
//print_r($insert_arr);die;

            $data = $this->UsageReportDelivery->save($insert_arr);

            if ($data)
            {
                $this->Session->write('m', $this->Cdrs->create_json(201, 'successfully!'));
                $this->redirect('report_delivery');
            }
            else
            {
                $this->Session->write('m', $this->Cdrs->create_json(101, 'Save failed!'));
                $this->redirect('add_report_delivery');
            }
        }

        if (!$id)
        {
            $this->redirect('report_delivery');
        }
        $id = base64_decode($id);
        $data = $this->Cdrs->query("select name, client_id from client order by name asc");
        $this->set('clients', $data);

        $info = $this->UsageReportDelivery->find(array('id' => $id));
        $carrier_arr = explode(',', $info['UsageReportDelivery']['carrierid']);
        $info['UsageReportDelivery']['carrierarr'] = array_flip($carrier_arr);

        $ingress_arr = "";
        if ($info['UsageReportDelivery']['ingressid'])
        {
            $sql = "select resource_id,alias as name from resource where resource_id in({$info['UsageReportDelivery']['ingressid']})";
            $ingress_arr = $this->UsageReportDelivery->query($sql);
        }
        $this->set('ingress', $ingress_arr);

        if ($info['UsageReportDelivery']['egressid'])
        {
            $sql = "select resource_id,alias as name from resource where resource_id in({$info['UsageReportDelivery']['egressid']})";
        }
        $egress_arr = $this->UsageReportDelivery->query($sql);
        $this->set('egress', $egress_arr);
//echo $id;die;
        $this->set('id', $id);
        $this->set('info', $info['UsageReportDelivery']);
    }

    public function delete_report_delivery($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$id)
        {
            $this->redirect('report_delivery');
        }
        $id = base64_decode($id);
        $data = $this->UsageReportDelivery->delete(intval($id));

        if ($data)
        {
            $this->Session->write('m', $this->Cdrs->create_json(201, 'Delete successfully!'));
        }
        else
        {
            $this->Session->write('m', $this->Cdrs->create_json(101, 'Delete failed!'));
        }
        $this->redirect('report_delivery');
    }

    public function action_report_delivery($id, $type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$id)
        {
            $this->redirect('report_delivery');
        }

        $save_arr = array(
            'id' => base64_decode($id),
            'action' => $type
        );
        $action = 'Stop';
        if ($type)
        {
            $action = 'Start';
        }
        $data = $this->UsageReportDelivery->save($save_arr);
        if ($data)
        {
            $this->Session->write('m', $this->Cdrs->create_json(201, "{$action} successfully!"));
        }
        else
        {
            $this->Session->write('m', $this->Cdrs->create_json(101, "{$action} failed!"));
        }
        $this->redirect('report_delivery');
    }

    public function history_report_delivery($id)
    {
        if (!$id)
        {
            $this->redirect('report_delivery');
        }
        $id = base64_decode($id);
        $sql = "select
                count(*)
                from report_delivery_history where report_delivery_id = '{$id}'";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->ReportDeliveryHistory->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select * from report_delivery_history  where report_delivery_id = '{$id}' order by id LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->ReportDeliveryHistory->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    public function download_report_delivery($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$id)
        {
            $this->redirect('report_delivery');
        }
        $data = $this->ReportDeliveryHistory->find(array('id' => $id));

        $file = $data['ReportDeliveryHistory']['content'];
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $file);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        exit;
    }

    public function ajax_get_trunk()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['form'];

            if (!isset($post_arr['value']) || empty($post_arr['value']))
            {
                echo 0;
            }
            $ingress_conditions = "ingress =true and client_id in({$post_arr['value']}) AND trunk_type2 = 0";

            $egress_conditions = "egress =true and client_id in({$post_arr['value']}) AND trunk_type2 = 0";
//echo $ingress_conditions;
            $ingress_options = Array(
                'conditions' => $ingress_conditions,
                'fields' => Array('resource_id', 'alias'),
                'order' => 'alias asc'
            );

            $egress_options = Array(
                'conditions' => $egress_conditions,
                'fields' => Array('resource_id', 'alias'),
                'order' => 'alias asc'
            );

            $ingress_arr = $this->Resource->find('all', $ingress_options);

            $ingress_data = array();
            foreach ($ingress_arr as $value)
            {

                $ingress_data[] = $value['Resource'];
            }
            $egress_data = array();
            $egress_arr = $this->Resource->find('all', $egress_options);

            $egress_data = array();
            foreach ($egress_arr as $value)
            {

                $egress_data[] = $value['Resource'];
            }

            $result_arr['ingress'] = $ingress_data;
            $result_arr['egress'] = $egress_data;

            echo json_encode($result_arr);
        }
        else
        {
            echo 0;
        }
    }

    public function call_duration_report()
    {
        $this->pageTitle = "Statistics/Long/Short/Call Reports";
        extract($this->Cdr->get_start_end_time());

        //$table_name = 'cdr_report';
        $table_name = CDR_TABLE;

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
                    $sql = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
                    $incoming_calls_arr = $this->Cdrs->query($sql);
                }
                else
                {
                    $sql1 = $this->Cdrs->get_inout_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
                    $sql2 = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
                    $incoming_calls_arr = $this->Cdrs->get_inout_from_two($sql1, $sql2, $orders, $show_fields);
                }
            }
            else
            {
                $sql = $this->Cdrs->get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
                $incoming_calls_arr = $this->Cdrs->query($sql);
            }

            $outgoing_calls_arr = $this->summary(2);
            $data = $incoming_calls_arr;
            $data[0][0]['out_total_calls'] = $outgoing_calls_arr[0][0]['total_calls'];
            $data[0][0]['out_not_zero_calls'] = $outgoing_calls_arr[0][0]['not_zero_calls'];
            $data[0][0]['out_success_calls'] = $outgoing_calls_arr[0][0]['success_calls'];
            $data[0][0]['out_busy_calls'] = $outgoing_calls_arr[0][0]['busy_calls'];
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





//$this->print_rr($this->params['url']);
        extract($this->Cdr->get_start_end_time());
        $table_name = 'client_cdr';

        $tz = $tz;
        $start_date = $start_date;
        $end_date = $end_date;
        $this->set('show_nodata', '1');

        $ingress_trunk_arr = $this->Cdrs->findIngressClient();
        $egress_trunk_arr = $this->Cdrs->findEgressClient();
        $this->set('egress_clients', $egress_trunk_arr);
        $this->set('ingress_clients', $ingress_trunk_arr);

        $egress_arr = $this->Cdrs->findAll_egress();
        $ingress_arr = $this->Cdrs->findAll_ingress();
        $this->set('egress_trunks', $egress_arr);
        $this->set('ingress_trunks', $ingress_arr);


        $call_duration_data = $this->Cdrs->get_call_duration();
        $this->set('data', $call_duration_data);
        $this->set('start_date', $start_date . $tz);
        $this->set('end_date', $end_date . $tz);
    }

    public function status()
    {
        $start = date('Y-m-d', time() - 7 * 24 * 3600) . " 00:00:00";
        $end = date('Y-m-d') . " 23:59:59";
        $gmt = "+0000";
        if (isset($_GET['start']))
        {
            $start = $_GET['start'] . " 00:00:00";
        }
        if (isset($_GET['end']))
        {
            $end = $_GET['end'] . " 23:59:59";
        }
        if (isset($_GET['gmt']))
        {
            $gmt = $_GET['gmt'];
        }
        $this->set('gmt',$gmt);
        $where = "WHERE report_time BETWEEN TIMESTAMP '{$start} {$gmt}' and TIMESTAMP '{$end} {$gmt}'";
        $sql1 = "select report_time::date as time, max(cps) as max_cps from
qos_total $where group by report_time::date order by time desc;";


        $data = $this->Cdr->query($sql1);


        //分表
        $sql2 = "select report_time::date as time, count(*) as call_count from
".CDR_TABLE." $where group by report_time::date order by 1;";

        //生成日期数组
//        pr($end);
        $time_data = $this->_get_date_result_admin($start . $gmt, $end . $gmt, 'cdr_report2%');
        $sql = "";
        foreach($time_data as $key=>$value) {
            //$table_name = "cdr_report" . $value;
            $table_name = CDR_TABLE . $value;

            $union = "";
            if (!empty($sql)) {
                $union = " union all ";
            } else {
                $union = '';
            }

            $sql .= <<<EOD
                        {$union}  select report_time::date from
                        {$table_name}
                        $where
EOD;
        }

        $sql = <<<EOD
                        select report_time  as time, count(*) as call_count from
                        (
                        $sql
                        )as tmp group by report_time order by 1
EOD;





        $data_call_count = $this->CdrsRead->query($sql);
        foreach ($data as $key => $data_item)
        {
            $data[$key][0]['call_count'] = 0;
            if (!$data[$key][0]['max_cps'])
            {
                $data[$key][0]['max_cps'] = 0;
            }
            foreach ($data_call_count as $data_count_item)
            {
                if (!strcmp($data_count_item[0]['time'], $data_item[0]['time']))
                {
                    $data[$key][0]['call_count'] = $data_count_item[0]['call_count'];
                }
            }
        }
//pr($data);
        $this->set('data', $data);
    }

    public function inout_report()
    {
        $this->pageTitle = "Statistics/Inbound/Outbound Report";

        extract($this->Cdr->get_start_end_time());

        $this->get_report_data('term');

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
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

    public function get_report_data($type)
    {
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;
        $show_fields = array();
        $group_select_arr = array();
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

//group by date  
        $send_date_group = "";
        if (isset($get_data['group_by_date']) && !empty($get_data['group_by_date']))
        {
            $send_date_group = " GROUP {$get_data['group_by_date']}";
            $show_fields['group_time'] = 'group_time';
        }
//        condition ingress_client_id 
        $ingress = "";
        if (isset($get_data['ingress_client_id']) && !empty($get_data['ingress_client_id']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where ingress=true AND trunk_type2 = 0 and resource.client_id = {$get_data['ingress_client_id']}";
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
            $condition[] = "ingress_rate_type {$get_data['ingress_rate_type']}";
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
                    . " where egress=true AND trunk_type2 = 0 and resource.client_id = {$get_data['egress_client_id']}";
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
            $condition[] = "egress_rate_type {$get_data['egress_rate_type']}";
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
            $group_select = " GROUP_BY " . implode(' ', $group_select_arr) . " END ";
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
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);

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
        $server_key = 0;
        foreach ($report_server as $key => $report_server_item)
        {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $ip = $report_server_item[0]['report_ip'];
            $port = $report_server_item[0]['report_port'];
            $flg = @socket_connect($socket, $ip, $port);

            if (!$flg)
            {
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

            $start_qie = date("Y-m-d H:i:sO", strtotime($start_date));
            $end_qie = date("Y-m-d H:i:sO", strtotime($end_date));
            $send = "GET DATA {$data_type} BEGIN {$start_qie} END {$end_qie}";
            if ($condition)
            {
                $send .= " CONDITION " . implode(' ', $condition) . " END ";
            }
            if ($group_select)
            {
                $send .= " " . $group_select;
            }
            if ($send_date_group)
            {
                $send .= " " . $send_date_group;
            }
//             echo $send . "<br />";
            if (Configure::read('cmd.debug'))
            {
                $this->set('send', $send);
            }

            socket_write($socket, $send);
//                $data = socket_read($socket, 1024);
//                                var_dump($data);die;
            $i = '';
            $data = array();
            $data_str = "";
            do
            {
                $data_str .= @socket_read($socket, 4096);
            }
            while (strpos($data_str, "\r\n\r\n") === false);

            $data_arr = explode("\n", $data_str);

//            pr($data_arr);die;
            foreach ($data_arr as $data_arr_key => $data_item_str)
            {
                if (!trim($data_item_str))
                {
                    unset($data_arr[$data_arr_key]);
                    continue;
                }

                $i ++;
                $data_item_arr = explode(',', $data_item_str);
//                pr($data_arr);
//                pr($simple_arr);
                if (empty($field))
                {
                    if (strcmp($data_type, 'detail'))
                    {
                        $data_item = array_combine($simple_arr, $data_item_arr);
                        $title_field_arr = $simple_arr;
                    }
                    else
                    {
                        $data_item = array_combine($detail_arr, $data_item_arr);
                        $title_field_arr = $detail_arr;
                    }
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
                    $trunk_name = $this->Resource->query("SELECT alias FROM resource WHERE resource_id = {$data_item['ingress_id']}");
                    $data_item['ingress_id_name'] = isset($trunk_name[0][0]['alias']) ? $trunk_name[0][0]['alias'] : "--";
                }
                if (!empty($data_item['egress_id']))
                {
                    $trunk_name = $this->Resource->query("SELECT alias FROM resource WHERE resource_id = {$data_item['egress_id']}");
                    $data_item['egress_id_name'] = isset($trunk_name[0][0]['alias']) ? $trunk_name[0][0]['alias'] : "--";
                }
                if (!empty($data_item['ingress_client_id']))
                {
                    $client_name = $this->Resource->query("SELECT name FROM client WHERE client_id = {$data_item['ingress_client_id']}");
//                        pr($client_name);die;
                    $data_item['ingress_client_id_name'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "--";
                }
                if (!empty($data_item['egress_client_id']))
                {
                    $client_name = $this->Resource->query("SELECT name FROM client WHERE client_id = {$data_item['egress_client_id']}");
                    $data_item['egress_client_id_name'] = isset($client_name[0][0]['name']) ? $client_name[0][0]['name'] : "--";
                }

                $data[][0] = $data_item;
            }
            socket_close($socket);
//                echo "<hr />";pr($data);
//合并总共的数据 

            if ($server_key)
            {
                $count_data = count($first_data);
                foreach ($data as $data_items)
                {
                    foreach ($first_data as $first_data_key => $first_data_items)
                    {
                        if ($count_data < 0)
                        {
                            break;
                        }
                        if ($first_data_items[0])
                        {
                            foreach ($first_data_items[0] as $data_key => $value)
                            {
                                if (!in_array($data_key, $unique_field_arr))
                                {
//echo $first_data_key."<br >".$data_key; 
                                    $first_data[$first_data_key][0][$data_key] += $data[$first_data_key][0][$data_key];
                                }
                            }
                        }
                    }
                }
            }
            else// 第一组数据 
            {
                if (empty($data))
                {
                    continue;
                }
                $first_data = $data;
            }
            $server_key ++;
//            pr($first_data);
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
//          pr($first_data);
//die;
        $this->set('data', $first_data);
    }

//    }
}

?>
