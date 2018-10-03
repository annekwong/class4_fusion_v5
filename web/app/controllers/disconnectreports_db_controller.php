<?php
class DisconnectreportsDbController extends AppController
{
    var $name = 'DisconnectreportsDb';
    var $uses = array('Cdr', 'Cdrs','CdrsRead');
    var $helpers = array('javascript', 'html', 'AppDisDb', 'Common');

    public function mismatch_cause_list($key)
    {
        $array = array(
            'RF_INVAILED_ARGS' => 'Invalid Argument',
            'RF_POOL_SESSION' => 'Internal Error',
            'RF_IN_SYS_LIMIT' => 'System Limit Exceeded',
            'RF_INGRESS_IP_CHECK' => 'Unauthorized IP Address',
            'RF_INGRESS_RESOURCE' => 'No Ingress Resource Found',
            'RF_ROUTE_STRATAGY' => ' No Routing Plan Found',
            'RF_PRODUCT_NOT_FOUND' => 'No Product Found',
            'RF_IN_RESORUCE_LIMIT' => 'Ingress Trunk Limit Exceeded',
            'RF_RESOURCE_CODEC' => 'Invalid Codec Negotiation',
            'RF_INGRESS_LRN_BLOCK' => 'Block due to LRN',
            'RF_INGRESS_RATE' => 'Original Rate Not Found',
            'RF_EGRESS_NOT_FOUND' => 'Egress Trunk Not Found',
            'RF_NORMAL12' => 'Normal'
        );
        $v = isset($array[$key]) ? $array[$key] : '';
        return $v;
    }

    function index()
    {
        $this->redirect('summary_reports');
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
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

    //初始化查询参数
    function init_query()
    {
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());

        if (!empty($_GET['ingress_alias']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    function create_amchart_csv($report_type, $field)
    {
        $field_sql = $this->get_field_sql($report_type, $field);
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $sql = " copy (
			select to_char(time, 'YYYY-MM-DD HH24') as group_time,$field_sql
		  from client_cdr $join 
		  where $where  group by group_time having 1=1 
		  order by group_time desc
			 ) to '" . Configure::read('database_actual_export_path') . "/$report_type" . "$field.csv'  csv ";
        $this->Cdr->query($sql);
        copy(Configure::read('database_export_path') . "/$report_type" . "$field.csv", APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field.csv");
    }

    function create_amchart_flash($report_type)
    {
        $this->create_amchart_csv($report_type, 'cdr_count');
        $this->create_amchart_csv($report_type, 'cdr_count_percentage');


        $this->create_amchart_setting_xml($report_type, 'cdr_count');
        $this->create_amchart_setting_xml($report_type, 'cdr_count_percentage');
    }

    function flash_setting($report_type)
    {
        Configure::write('debug', 0);
        $field = !empty($_GET ['f']) ? $_GET ['f'] : 'cdr_count';
        $report_type = !empty($_GET ['report_type']) ? $_GET ['report_type'] : 'orig_discon_report';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
    }

    function create_amchart_setting_xml($report_type, $field)
    {
        $humanize_field = Inflector::humanize($field);
        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out = '<settings>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_base.xml");
        $out.= '<data_sets>';

        $out .= "<data_set did=\"0\">\n";
        $out .= " <title>$humanize_field</title>\n";
        $out .= " <short>$humanize_field $humanize_field</short>\n";
        $out .= " <description>   $humanize_field;</description>\n";
        $out .= "<file_name>$report_type" . "$field.csv</file_name>\n";
        $out .= "<main_drop_down selected=\"true\"></main_drop_down>\n   	  
       					<compare_list_box selected=\"false\"></compare_list_box>\n
       <csv>
         <reverse>true</reverse>
         <separator>,</separator>
         <date_format>YYYY-MM-DD hh:mm:ss</date_format>
         <decimal_separator>.</decimal_separator>
         <columns>
           <column>date</column>
           <column>close</column>  
         </columns>
       </csv>\n";
        $out .= "</data_set>\n";

        $out.= '</data_sets>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_charts.xml");
        $out .= '</settings>';


        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    function get_field_sql($report_type, $field)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $arr = array(
            'cdr_count' => " count(*)  as  disconnect",
            'cdr_count_percentage' => "(count(*)::numeric/(select count(*)  from  client_cdr $join where $where))::numeric(20,3)  as  per",
        );

        if (isset($arr[$field]))
        {

            return $arr[$field];
        }
        else
        {

            return $arr['cdr_count'];
        }
    }

    function get_datas($report_type = '', $order_field)
    {
        $release_cause = " release_cause ";
        extract($this->Cdr->get_real_period());
        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz']))
        {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        }
        if ($report_type == 'term_discon_report')
        {
            $distinctive_field = "egress_total_calls";
            $client_cdr_where_other = '';
            $distinctive_release_cause_field = "release_cause_from_protocol_stack";
        }
        else
        {
            $distinctive_field = "ingress_total_calls";
            $client_cdr_where_other = ' AND is_final_call = 1';
            $distinctive_release_cause_field = "binary_value_of_release_cause_from_protocol_stack";
        }
        //        判断选择时间段从哪取数据
//        echo "start time : ".$start. "<br />";
//        echo "end time : ".$end. "<br />";
        $report_max_time = $this->Cdr->get_report_maxtime($start, $end);
//        var_dump($report_max_time);
        $select_time_end = strtotime($end);
        $is_from_client_cdr = false;
        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start;
        }
        $system_max_end = strtotime($report_max_time);
//        $select_time_end = 0;
//        $system_max_end = 1;
//        $is_from_client_cdr = true;
//        $report_max_time = $end;
        if ($select_time_end > $system_max_end)
        {
            if ($is_from_client_cdr)
            {
                $result = $this->get_data_from_client_cdr($distinctive_release_cause_field,$report_max_time,$end,$report_type,$client_cdr_where_other,$release_cause);
                $org_sql = $result['org_sql'];
                $group_by_field_arr = $result['group_by_field_arr'];
            }
            else
            {
//                两部分
                $result_client_cdr = $this->get_data_from_client_cdr($distinctive_release_cause_field,$report_max_time,$end,$report_type,$client_cdr_where_other,$release_cause);
                $client_cdr_org_sql = $result_client_cdr['org_sql'];
                $group_by_field_arr = $result_client_cdr['group_by_field_arr'];

                $result_report = $this->get_data($start,$report_max_time,$distinctive_release_cause_field,$distinctive_field,$report_type,$release_cause);
                $report_org_sql = $result_report['org_sql'];
                $group_by_field_arr = $result_report['group_by_field_arr'];
                $group_by_where = $result_report['group_by_where'];
                $search_str = 'binary_value_of_release_cause_from_protocol_stack';
                $replace_str = 'release_cause_from_protocol_stack';
                $group_by_where = str_replace($search_str,$replace_str,$group_by_where);
                $total_fields = $group_by_where .",cause,real_release_cause,sum(all_count) as all_count,sum(my_count) as my_count,
                sum(disconnect) as disconnect";
                $total_groups = $group_by_where .",cause,real_release_cause";
//                echo $client_cdr_org_sql."<br />";
//                echo $report_org_sql."<br />";
                $org_sql = <<<str
SELECT $total_fields FROM (
($client_cdr_org_sql) UNION ALL ($report_org_sql)
) AS tmp GROUP BY $total_groups
str;

                $cdr_report_detail_where = $this->capture_report_condtions2($report_type,CDR_TABLE,$start,$report_max_time);
                $client_cdr_where = $this->capture_report_condtions($report_type,'client_cdr',$report_max_time,$end);
                if ($report_type == 'term_discon_report')
                {
                    $client_cdr_where_other = '';
                }
                else
                {
                    $client_cdr_where = trim($client_cdr_where);
                    $client_cdr_where_other = !empty($client_cdr_where) && $client_cdr_where != ' ' ? ' AND is_final_call = 1' : ' is_final_call = 1';
                }
                $client_cdr_where .= $client_cdr_where_other;
                $today_date = date("Ymd");
                $table_name = CDR_TABLE;
                $all_count_sql = <<<str
SELECT sum(total_calls) as all_count FROM(
(SELECT sum($distinctive_field) as total_calls FROM $table_name WHERE $cdr_report_detail_where)
UNION ALL
(SELECT count(*) as total_calls FROM client_cdr{$today_date} WHERE $client_cdr_where)) as tmp
str;
                $all_count_sql = str_replace('WHERE AND', 'WHERE', $all_count_sql);
                $all_count = $this->Cdr->query($all_count_sql);
                $this->set('all_count',$all_count[0][0]['all_count']);
            }
        }
        else
        {
            $result = $this->get_data($start,$end,$distinctive_release_cause_field,$distinctive_field,$report_type,$release_cause);
            $org_sql = $result['org_sql'];
            $group_by_field_arr = $result['group_by_field_arr'];
        }

        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }

    function get_data($start,$report_max_time,$distinctive_release_cause_field,$distinctive_field,$report_type,$release_cause)
    {
        $table_name = CDR_TABLE;
        $table_data_arr = $this->x_get_date_result_admin($start,$report_max_time,$table_name);
        $single_sql_arr = array();
        $per_single_sql_arr = array();
        foreach ($table_data_arr as $table_data)
        {
            $fields = "release_cause,$distinctive_release_cause_field,{$distinctive_field}";
            $out_group_fields = "split_part($distinctive_release_cause_field,':',1) as release_cause_from_protocol_stack,
	    split_part($distinctive_release_cause_field,':',2) as cause";
            $where = $this->capture_report_condtions2($report_type,$table_data,$start,$report_max_time);
            extract($this->capture_report_join3($report_type, '',$table_data));
            $inner_fields = '';
            foreach ($group_fields_else as $key => $value)
            {
                if($value)
                    $inner_fields .= ','.$value .' as '.$key;
                else
                    $inner_fields .= ','.$key;
                $out_group_fields .= ','.$key;
            }
            $fields .= $inner_fields;
            $single_sql_arr[] = " SELECT $fields FROM $table_data $join WHERE $where ";
            $per_single_sql_arr[] = " SELECT $distinctive_field FROM $table_data WHERE $where ";
        }
        $union_sql = implode('UNION ALL',$single_sql_arr);
        $per_union_sql = implode('UNION ALL',$per_single_sql_arr);
        $per = "(select sum($distinctive_field) from  ($per_union_sql) as t2) as all_count ";
        $column = " $per, release_cause  as  real_release_cause,sum($distinctive_field) as my_count,$release_cause, sum($distinctive_field)  as  disconnect";
        $order = $this->capture_report_order();
        if (!empty($group_by_where))
        {
            $this->set('is_group', 'true');
            $org_sql = "select   $out_group_fields,$column	from ($union_sql) as t1  group by  $group_by_where    $order";
        }
        else
        {
            $this->set('is_group', 'false');
            $org_sql = "select   $out_group_fields,$column from ($union_sql) as t1 $order";
        }
        return array('org_sql' => $org_sql,'group_by_field_arr' =>$group_by_field_arr,'group_by_where' => $group_by_where);
    }

    function get_data_from_client_cdr($distinctive_release_cause_field,$report_max_time,$end,$report_type,$client_cdr_where_other,$release_cause)
    {
        $single_sql_arr = array();
        $per_single_sql_arr = array();
        $fields = "release_cause,$distinctive_release_cause_field";
        $out_group_fields = "split_part($distinctive_release_cause_field,':',1) as release_cause_from_protocol_stack,
	    split_part($distinctive_release_cause_field,':',2) as cause";
        $table_name = "client_cdr";
        $client_cdr_table_arr = $this->x_get_date_result_admin($report_max_time,$end,$table_name);
        foreach ($client_cdr_table_arr as $client_cdr_table_item)
        {
            $client_cdr_where = $this->capture_report_condtions($report_type,$client_cdr_table_item,$report_max_time,$end);
            $client_cdr_where = trim($client_cdr_where);
            if(empty($client_cdr_where)) {
                $client_cdr_where_other = str_replace('AND', '', $client_cdr_where_other);
            }
            $client_cdr_where .= $client_cdr_where_other;
            extract($this->capture_report_join($report_type, '',$client_cdr_table_item));
            $single_sql_arr[] = " SELECT $group_by_field FROM $client_cdr_table_item $join WHERE $client_cdr_where ";
            $per_single_sql_arr[] = " SELECT count(*) as total_calls FROM $client_cdr_table_item WHERE $client_cdr_where  ";
        }
        $union_sql = implode('UNION ALL',$single_sql_arr);
        $per_union_sql = implode('UNION ALL',$per_single_sql_arr);
        $per = "(select sum(total_calls) from  ($per_union_sql) as t2) as all_count ";
        $column = " $per,release_cause  as  real_release_cause,count(*) as my_count,$release_cause, count(*)  as  disconnect";
        $order = $this->capture_report_order();
        if (!empty($group_by_where))
        {
            if(!empty($group_by_field_arr))
                $out_group_fields .= "," .implode(",",$group_by_field_arr);
            $this->set('is_group', 'true');
            $org_sql = "select  $out_group_fields,$column	from ($union_sql) as t1  group by  $group_by_where    $order";
        }
        else
        {
            $this->set('is_group', 'false');
            $org_sql = "select $out_group_fields,$column from ($union_sql) as t1 $order";
        }
        return array('org_sql' => $org_sql,'group_by_field_arr' =>$group_by_field_arr,'group_by_where' => $group_by_where);
    }

    function capture_report_order()
    {
        $order = $this->_order_condtions(
            Array('date', 'release_cause_from_protocol_stack', 'cause', 'disconnect', 'my_count', 'release_cause', 'group_time')
        );
        if (empty($order))
        {
            $order = ' order by 1,2';
            if (isset($this->params['url']['group_by_date']) && $this->params['url']['group_by_date'])
            {
                $order = ' order by group_time,1,2';
            }
        }
        else
        {
            $order = 'order by ' . $order;
        }
        $order = '';
        return $order;
    }

    function get_all_country()
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));

        $org_sql = "select  distinct  term_country  from client_cdr $join  where $where";
        $org_list = $this->Cdr->query($org_sql);

        $tmp_arr = array();
        if (!empty($org_list))
        {
            $size = count($org_list);
            foreach ($org_list as $key => $value)
            {
                $tmp_arr[] = $value[0]['term_country'];
            }
        }
        return $tmp_arr;
    }

    function summary_reports()
    {
        $this->pageTitle = "Statistics/Disconnect Causes";
        $t = getMicrotime();
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        $this->set('cdr_db', $this->Cdr);

        if (!empty($this->params['pass'][0]))
        {
            $rate_type = $this->params['pass'][0];
            if ($rate_type == 'org')
            {
                $join_rate_field = 'ingress_rate_id';
                $this->set('rate_type', 'org');
                $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1) as  release_cause_from_protocol_stack,
	    split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as   cause";
                $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack  ";
            }
            elseif ($rate_type == 'term')
            {
                $join_rate_field = 'egress_rate_id';
                $this->set('rate_type', 'term');
                $order_field = "split_part(release_cause_from_protocol_stack,':',1) as  release_cause_from_protocol_stack,
	     split_part(release_cause_from_protocol_stack,':',2) as  cause ";
                $group_by_where = "release_cause,release_cause_from_protocol_stack  ";
            }
            else
            {
                $this->set('rate_type', 'org');
                $join_rate_field = 'ingress_rate_id';
                $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1) as  release_cause_from_protocol_stack,
	          split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as  cause";
                $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack  ";
            }
        }
        else
        {
            $rate_type = 'org';
            $this->set('rate_type', 'org');
            $join_rate_field = 'ingress_rate_id';
            $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1)as release_cause_from_protocol_stack,
		          split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as  cause";
            $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack  ";
        }
        if (isset($this->params['pass'][1]))
        {
            if ($rate_type == 'org')
                $_GET ['group_by'][0] = "orig_client_name";
            elseif ($rate_type == 'term')
                $_GET ['group_by'][0] = "term_client_name";
        }


        $this->init_query();
        $login_type = $_SESSION ['login_type'];
        extract($this->Cdr->get_real_period());
        if ($rate_type == 'org')
        {
            $report_type = 'orig_discon_report';
        }
        else
        {
            $report_type = 'term_discon_report';
        }

        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, $order_field));
        $show_comm = true; //普通表格显示
        $show_subgroups = false; //以subgroup方式显示
        $show_subtotals = false; //以subgtotal方式显示
        $grpup_size = count($group_by_field_arr);
        $this->set('show_comm', $show_comm);
        $this->set('show_nodata', true);
        $this->set('report_name', "Disconnect Causes");
        $this->is_scheduled_report($org_sql, $start, $end, 1);
        if (isset($_GET ['query'] ['output']))
        {
            $file_name = $this->create_doload_file_name('DisconnectCauses', $start, $end);
            $replace_arr = [
                'release_cause_from_protocol_stack' => 'Release Cause',
                'count' => 'Counts',
                'percent' => 'Counts %',
                'real_release_cause' => 'SIP Response'
            ];
            if($rate_type == 'term'){
                unset($replace_arr['real_release_cause']);
            }
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
                $is_preload_result = $this->Cdr->query($sql);
                $is_preload = $is_preload_result[0][0]['is_preload'];
                $orderBy = '';
                if(isset($_GET['order_by'])) {
                    $orderBy = explode('-', $_GET['order_by']);
                    $orderBy = " ORDER BY {$orderBy[0]} {$orderBy[1]}";
                }
                $org_sql .= $orderBy;

                $file_path = realpath(ROOT . '/../download/disconnectreports_db/') . DS . $file_name;
                $org_list = $this->CdrsRead->query($org_sql);
                if (!empty($this->params['pass'][1]))
                {
                    $result = $this->change($org_list);
                    $org_list = $result;
                }

                App::import('Helper', 'AppHelper');

                $appHelper = new AppHelper(new View());

                $handle = fopen($file_path, 'w');
                fputcsv($handle, $replace_arr);

                foreach ($org_list as &$item) {
                    $item[0]['real_release_cause'] = $appHelper->show_release_cause($item[0]['real_release_cause']);
                    $item[0]['release_cause_from_protocol_stack'] = $item[0]['release_cause_from_protocol_stack'] . ':' . $item[0]['cause'];
                    $item[0]['count'] = $item[0]['my_count'];
                    $item[0]['percent'] = round($item[0]['my_count'] / $item[0]['all_count'] * 100, 4). '%' ;
                    $row = [];
                    foreach($replace_arr as $field => $title){
                        $row[] = $item[0][$field];
                    }
                    fputcsv($handle, $row);
                }
                fclose($handle);

                header('Content-Type: application/csv');
                header("Content-Disposition: attachment; filename={$file_name}");
                header('Pragma: no-cache');
                ob_clean();
                readfile($file_path);
                exit;
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                $path_info = pathinfo($file_name);
                Configure::write('debug', 0);
                $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
                $is_preload_result = $this->Cdr->query($sql);
                $is_preload = $is_preload_result[0][0]['is_preload'];
                $orderBy = '';
                if(isset($_GET['order_by'])) {
                    $orderBy = explode('-', $_GET['order_by']);
                    $orderBy = " ORDER BY {$orderBy[0]} {$orderBy[1]}";
                }
                $org_sql .= $orderBy;
                $file_path = realpath(ROOT . '/../download/disconnectreports_db/') . DS . $file_name;
                $org_list = $this->CdrsRead->query($org_sql);
                if (!empty($this->params['pass'][1]))
                {
                    $result = $this->change($org_list);
                    $org_list = $result;
                }

                App::import('Helper', 'AppHelper');

                $appHelper = new AppHelper(new View());

                $handle = fopen($file_path, 'w');
                fputcsv($handle, $replace_arr);

                foreach ($org_list as &$item) {
                    $item[0]['real_release_cause'] = $appHelper->show_release_cause($item[0]['real_release_cause']);
                    $item[0]['release_cause_from_protocol_stack'] = $item[0]['release_cause_from_protocol_stack'] . ':' . $item[0]['cause'];
                    $item[0]['count'] = $item[0]['my_count'];
                    $item[0]['percent'] = round($item[0]['my_count'] / $item[0]['all_count'] * 100, 4). '%' ;
                    $row = [];
                    foreach($replace_arr as $field => $title){
                        $row[] = $item[0][$field];
                    }
                    fputcsv($handle, $row);
                }
                fclose($handle);

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=" . $path_info['filename'] . ".xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                ob_clean();
                readfile($file_path);
                exit;
            }
            elseif ($_GET ['query'] ['output'] == 'delayed')
            {
                //Configure::write('debug',0);
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                //$this->Cdr->export__sql_data('DownloadDisconnectCauses',$org_sql,'report');

                $this->layout = 'csv';
            }
            else
            {
                //web显示
                $orderBy = '';
                if(isset($_GET['order_by'])) {
                    $orderBy = explode('-', $_GET['order_by']);
                    $orderBy = " ORDER BY {$orderBy[0]} {$orderBy[1]}";
                }
                $org_sql .= $orderBy;
                $org_list = $this->CdrsRead->query($org_sql);
//                echo "<hr />";
                if (!empty($this->params['pass'][1]))
                {
                    $result = $this->change($org_list);
                    $org_list = $result;
                }
                $this->set("client_org", $org_list);
                $this->set("group_by_field_arr", $group_by_field_arr);

                /* $image_file = $this->create_image_csv();
                  $this->set('image_file', basename($image_file));

                  $country_arr = $this->get_all_country ();
                  $this->set('country_arr', $country_arr); */
                $this->set('field', 'no_channel_calls');
            }
        }
        else
        {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            //echo $org_sql;
            $orderBy = '';
            if(isset($_GET['order_by'])) {
                $orderBy = explode('-', $_GET['order_by']);
                $orderBy = " ORDER BY {$orderBy[0]} {$orderBy[1]}";
            }
            $org_sql .= $orderBy;
            if ($is_preload)
                $org_list = $this->CdrsRead->query($org_sql);
            else
            {
                $org_list = array();

                $this->set('show_nodata', false);
            }
            if (!empty($this->params['pass'][1]))
            {
                $result = $this->change($org_list);
                $org_list = $result;
            }
            $this->set("client_org", $org_list);
            $this->set("group_by_field_arr", $group_by_field_arr);
            //$image_file = $this->create_image_csv();
            //$this->set('image_file', basename($image_file));

            $country_arr = array();
            $this->set('country_arr', $country_arr);
            $this->set('field', 'ca');
        }
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();

        // Get trunks map
        $trunkList = $this->Cdrs->query('SELECT alias, resource_id FROM resource');
        $resultList = [];

        foreach ($trunkList as $item) {
            $resultList[$item[0]['alias']] = $item[0]['resource_id'];
        }

        $this->set('trunksMap', $resultList);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);
    }

    function change($org_list)
    {
        $show_arr = array(
            '200', '403', '404', '408', '480', '481', '486', '487', '500', '503', '510', '604'
        );
        $this->set('release_cause_show', $show_arr);
        $name = "orig_client_name";
        $rate_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : "org";

        if ($rate_type == "term")
            $name = "term_client_name";

        $result = array();
        $total_sesult = array('all_count' => 0);
        $i = 0;
        foreach ($org_list as $org_list_item)
        {
            $item_my_count = (int) $org_list_item[0]['my_count'];
            $release_value = "Others";
            if (in_array($org_list_item[0]['release_cause_from_protocol_stack'], $show_arr))
                $release_value = $org_list_item[0]['release_cause_from_protocol_stack'];

            if (key_exists($org_list_item[0][$name], $result))
            {
                $result[$org_list_item[0][$name]]['all_count'] += $item_my_count;
                if (key_exists($release_value, $result[$org_list_item[0][$name]]))
                    $result[$org_list_item[0][$name]][$release_value] += $item_my_count;
                else
                    $result[$org_list_item[0][$name]][$release_value] = $item_my_count;
            }
            else
            {
                $result[$org_list_item[0][$name]] = array(
                    $release_value => $item_my_count,
                    'all_count' => $item_my_count,
                );
            }
            if (key_exists($release_value, $total_sesult))
                $total_sesult[$release_value] += $item_my_count;
            else
                $total_sesult[$release_value] = $item_my_count;
            $total_sesult['all_count'] += $item_my_count;
//            echo "$i<br />";
//            pr($result);
//            echo "<hr />";
//            $i ++;
        }
//        die;
        return array('result' => $result, 'total' => $total_sesult);
    }

    function _download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'report', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

    function image_test()
    {
        //var_dump($_REQUEST);
        //Configure::write('debug',0);
        //$this->layout='csv';
        require_once("vendors/image_test.php");
        //App::import ( 'Vendor', 'image_test');
        //$this->layout='csv';
    }

//生成image所需查询结果文件，返回文件名
    function create_image_csv()
    {
        $return = '';
        //create数据文件	
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));
        $today_statistic = $this->getTodayStatistic();


        $sql = "select to_char(time, 'YYYY-MM-DD HH24') as group_time, term_country, sum(ca) as ca, sum(ok_calls) as ok_calls,
			sum(call_duration) as call_duration, sum(ingress_cost) as ingress_cost, sum(egress_cost) as egress_cost, sum(orig_bill_minute)
			 as orig_bill_minute, sum(term_bill_minute) as term_bill_minute, sum(ingress_ca) as ingress_ca, sum(egress_ca) as egress_ca,
			 sum(not_zero_calls) as not_zero_calls, sum(busy_calls) as busy_calls, sum(no_channel_calls) as no_channel_calls
		  from $today_statistic  
		  where $where group by group_time, term_country having 1=1 
		  order by group_time desc";
        $result = $this->Cdr->query($sql);
        if (!empty($result))
        {
            $return = "/tmp/image_" . date("Ymd-His") . "_" . rand(0, 99);
            file_put_contents($return, json_encode($result));
        }
        return $return;
    }
}?>
