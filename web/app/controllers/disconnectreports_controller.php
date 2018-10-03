<?php

class DisconnectreportsController extends AppController
{

    var $name = 'Disconnectreports';
    var $uses = array('Cdr', 'Cdrs', 'SwitchProfile');
    var $helpers = array('javascript', 'html', 'AppDis', 'Common', 'AppCdr');

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

    function get_datas($report_type = '', $order_field, $group_by_where2 = '')
    {

        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions1($report_type);
        $order = $this->capture_report_order();
        extract($this->capture_report_join1($report_type, ''));
        /*$release_cause = "case  release_cause
	 	when    0    then   'Invalid Argument'
  	when    1    then   'System Limit Exceeded'
  	when    2    then   'SYSTEM_CPS System Limit Exceeded'
  	when    3     then   'Unauthorized IP Address'
  	when    4    then   ' No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit CAP Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  CAP Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when    10    then   'Invalid Codec Negotiation'
		when    11    then   'Block due to LRN'
		when   12 			then  'Ingress Rate Not Found'  
		when   13 			then  ' Egress Trunk Not Found'  
		when   14 			then  'From egress response 404'  
		when   15 			then  'From egress response 486 '  
		when   16 			then  'From egress response 487 	'  
		when   17 			then  'From egress response 200 '  
		when   18 			then  'All egress not available'  
		when   19 			then  'Normal'
		when   20 			then  'Ingress Resource disabled'   
		when   21 			then  'Balance Use Up'   
		when   22 			then  'No Routing Plan Route'   
		when   23 			then  'No Routing Plan Prefix'   
		when   24 			then  'Ingress Rate No configure'
		             when   25                     then 'Invalid Codec Negotiation'
                when   26                   then 'No Codec Found'
                when   27                     then 'All egress no confirmed'
                when   28                     then 'LRN response no exist DNIS'
		else    'other'  end  as
		release_cause";*/
        $release_cause = " release_cause ";

        //$per = "count(*) as my_count,(select count(*)  from  " . CDR_TABLE . " $join where $where) as  all_count";

        $having = '';

        if ($report_type == 'term_discon_report')
        {
            $per = "sum(egress_total_calls) as my_count,(select sum(egress_total_calls)  from  ".CDR_TABLE." $join where $where) as  all_count";
            //$column = "  $per,  count(*)  as  disconnect";
            $column = " release_cause  as  real_release_cause,   $per,  $release_cause, sum(egress_total_calls)  as  disconnect";
        }
        else
        {
            $per = "sum(ingress_total_calls) as my_count,(select sum(ingress_total_calls)  from  ".CDR_TABLE." $join where $where) as  all_count";
            $column = " release_cause  as  real_release_cause,   $per,  $release_cause, sum(ingress_total_calls)  as  disconnect";
        }


        if (count($group_by_field_arr))
        {
            $this->set('is_group', 'true');
            $org_sql = "select   $field_list,$column,$order_field	from ".CDR_TABLE." $join   where $where  group by  $group_by_where,$group_by_where2    $order";
        }
        else
        {
            $this->set('is_group', 'false');
            $org_sql = "select   $column,$order_field	from ".CDR_REPORT." $join  where $where  group by  $group_by_where,$group_by_where2    $order";
        }


        //$org_list = $this->Cdr->query($org_sql);
        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }

    function capture_report_order()
    {
        $order = $this->_order_condtions(
                Array('date', 'release_cause_from_protocol_stack', 'cause', 'disconnect', 'per', 'release_cause')
        );
        if (empty($order))
        {
            $order = ' order by 1,2';
        }
        else
        {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function get_all_country()
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));

        $org_sql = "select  distinct  term_country  from client_cdr $join  where $where";
        //$org_sql="select  distinct  term_country  from statistic_cdr $join  where $where";
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
        //$this->_session_get(isset($_GET ['searchkey']));
        $this->pageTitle = "Statistics/Disconnect Causes";
        $t = getMicrotime();

        $rate_type = empty($this->params['pass'][0]) ? "org" : $this->params['pass'][0];

        $this->get_report_data($rate_type);
//        extract($this->get_datas($report_type, $order_field, $group_by_where));
        // $this->create_amchart_flash($report_type);

        /*$release_cause = array(
            0 => 'Invalid Argument',
            1 => 'System Limit Exceeded',
            2 => 'SYSTEM_CPS System Limit Exceeded',
            3 => 'Unauthorized IP Address',
            4 => ' No Ingress Resource Found',
            5 => 'No Product Found',
            6 => 'Trunk Limit CAP Exceeded',
            7 => 'Trunk Limit CPS Exceeded',
            8 => 'IP Limit  CAP Exceeded',
            9 => 'IP Limit CPS Exceeded ',
            10 => 'Invalid Codec Negotiation',
            11 => 'Block due to LRN',
            12 => 'Ingress Rate Not Found',
            13 => ' Egress Trunk Not Found',
            14 => 'From egress response 404',
            15 => 'From egress response 486 ',
            16 => 'From egress response 487 	',
            17 => 'From egress response 200 ',
            18 => 'All egress not available',
            19 => 'Normal',
            20 => 'Ingress Resource disabled',
            21 => 'Balance Use Up',
            22 => 'No Routing Plan Route',
            23 => 'No Routing Plan Prefix',
            24 => 'Ingress Rate No configure',
            25 => 'Invalid Codec Negotiation',
            26 => 'No Codec Found',
            27 => 'All egress no confirmed',
            28 => 'LRN response no exist DNIS',
        );*/

        $release_cause = " release_cause ";

        $this->set('release_cause_list', $release_cause);

        extract($this->Cdr->get_start_end_time());

        if (isset($_GET ['query'] ['output']))
        {
            //下载
            $file_name = $this->create_doload_file_name('DisconnectCauses', $start_date, $end_date);
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                //$this->Cdr->export__sql_data('DownloadDisconnectCauses', $org_sql, $file_name);

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=$file_name");
                header("Content-Transfer-Encoding: binary");
                $this->autoLayout = FALSE;
                $this->render('disconnectreports_down_csv');
                //$this->layout = 'csv';
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                $path_info = pathinfo($file_name);
                //Configure::write('debug', 0);
                //$this->Cdr->export_xls_sql_data('DownloadDisconnectCauses', $org_sql, $file_name);
                //$this->_catch_exception_msg(array('DownloadDisconnectCauses','_download_xls'),array('download_sql' => $org_sql));

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=" . $path_info['filename'] . ".xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('disconnectreports_down_xls');

                $this->layout = 'csv';
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
                $this->set('field', 'no_channel_calls');
            }
        }
        else
        {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            if (!$is_preload)
            {
                $this->set('show_nodata', false);
            }
            $country_arr = $this->get_all_country();
            $this->set('country_arr', $country_arr);
            $this->set('field', 'ca');
        }
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);
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

    public function get_report_data($type)
    {
        //type  : $rate_type 
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
        $data_type = "detail";

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
        if (isset($get_data['orig_carrier_select']) && !empty($get_data['orig_carrier_select']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where ingress=true and resource.client_id = {$get_data['orig_carrier_select']}";
            $ingress = $this->Cdr->query($sql); //pr($ingress);die;
            $condition[] = "ingress_client_id {$get_data['orig_carrier_select']}";
        }
        $this->set('ingress', $ingress);
//        condition ingress_id 
        if (!empty($get_data['ingress_alias']))
        {
            $res = $this->Cdr->findTechPerfix($get_data['ingress_alias']);
            $this->set('tech_perfix', $res);

            $ingress_options = $this->Cdrs->get_ingress_options($get_data['ingress_alias']);

            $this->set('ingress_options', $ingress_options);
            $condition[] = "ingress_id {$get_data['ingress_alias']}";
        }

//condition rout_prefix 
        if (isset($get_data['ingress_prefix']) && $get_data['ingress_prefix'])
        {
            $condition[] = "route_prefix {$get_data['ingress_prefix']}";
        }

//condition ingress_country 
        if (isset($get_data['query']['country']) && $get_data['query']['country'])
        {
            $data_type = "detail";
            $condition[] = "ingress_country {$get_data['query']['country']}";
        }

//condition INGRESS_CODE_NAME 
        if (isset($get_data['query']['code_name']) && $get_data['query']['code_name'])
        {
            $data_type = "detail";
            $condition[] = "ingress_code_name {$get_data['query']['code_name']}";
        }

//condition INGRESS_CODE  
        if (isset($get_data['query']['code']) && $get_data['query']['code'])
        {
            $data_type = "detail";
            $condition[] = "ingress_code {$get_data['query']['code']}";
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
        if (isset($get_data['term_carrier_select']) && !empty($get_data['term_carrier_select']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where egress=true and resource.client_id = {$get_data['term_carrier_select']}";
            $egress = $this->Cdr->query($sql);
            $condition[] = "egress_client_id {$get_data['term_carrier_select']}";
        }
        $this->set('egress', $egress);

//condition egress_id 
        if (isset($get_data['egress_alias']) && $get_data['egress_alias'])
        {
            $condition[] = "egress_id {$get_data['egress_alias']}";
        }

//condition egress_country 
        if (isset($get_data['query']['country_term']) && $get_data['query']['country_term'])
        {
            $data_type = "detail";
            $condition[] = "egress_country {$get_data['query']['country_term']}";
        }

//condition EGRESS_CODE_NAME 
        if (isset($get_data['query']['code_name_term']) && $get_data['query']['code_name_term'])
        {
            $data_type = "detail";
            $condition[] = "egress_code_name {$get_data['query']['code_name_term']}";
        }

//condition EGRESS_CODE  
        if (isset($get_data['query']['code_term']) && $get_data['query']['code_term'])
        {
            $data_type = "detail";
            $condition[] = "egress_code {$get_data['query']['code_term']}";
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

        if (!strcmp($type, 'org'))
        {
            $this->set('rate_type', 'org');
            $group_select_arr[] = "release_cause_route";
            $group_select_arr[] = "release_cause_to";
        }
        elseif (!strcmp($type, 'term'))
        {
            $this->set('rate_type', 'term');
            $group_select_arr[] = "release_cause_route";
            $group_select_arr[] = "release_cause_from";
        }
        else
        {
            $this->set('rate_type', 'org');
            $group_select_arr[] = "release_cause_route";
            $group_select_arr[] = "release_cause_to";
        }

        if ($group_select_arr)
        {
            $group_select = " GROUP_BY " . implode(' ', $group_select_arr) . " END";
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
        $this->init_query();


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
//$flg = @socket_connect($socket, '192.168.1.107', '3300');
//            var_dump($flg);
//            echo "<br >";
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
            $start_qie = date("Y-m-d H:i:sO",strtotime($start_date));
            $end_qie = date("Y-m-d H:i:sO",strtotime($end_date));
            $send = "GET DATA {$data_type} BEGIN {$start_qie} END {$end_qie}";
            if ($condition)
            {
                $send .= " CONDITION " . implode(' ', $condition) . " END";
            }
            if ($group_select)
            {
                $send .= " " . $group_select;
            }
            if ($send_date_group)
            {
                $send .= " " . $send_date_group;
            }
//            $send .= "\n\r";
//            echo $send . "<br />";
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
//            echo '$data';
//            pr($data);
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
                if(empty($data))
                {
                    continue;
                }
                $first_data = $data;
            }
            $server_key ++;
        }
//        echo '$first_data';
//        pr($first_data);
//        die;
//        处理数据 disconnectreports 

        $return_data = array();
        $return_data_key = 0;
        $total_calls = 0;
        foreach ($first_data as $first_data_item)
        {
            foreach ($first_data_item[0] as $first_data_item_key => $first_data_item_value)
            {
                if (in_array($first_data_item_key, $group_select_arr))
                {
                    $return_data[$return_data_key][0][$first_data_item_key] = $first_data_item_value;
                }
            }
            if (!strcmp($type, 'term'))
            {
                $return_data[$return_data_key][0]['total_call'] = $first_data_item[0]['egress_total_calls'];
                $total_calls += $first_data_item[0]['egress_total_calls'];
            }
            else
            {
                $return_data[$return_data_key][0]['total_call'] = $first_data_item[0]['ingress_total_calls'];
                $total_calls += $first_data_item[0]['egress_total_calls'];
            }
            $return_data_key++;
        }
        $this->set('total_calls', $total_calls);
//        pr($total_calls);
//       pr($return_data);die;

        if (!$return_data)
        {
            $return_data = array();
            $this->set('show_nodata', true);
        }
        else
        {
            $this->set('show_nodata', false);
        }
//            pr($first_data);
//die;
        $this->set('data', $return_data);
    }

}
