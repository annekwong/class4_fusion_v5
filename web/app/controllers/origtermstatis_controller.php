<?php

/**
 * 
 * 
 * 进出统计
 * @author root
 *
 */
class OrigtermstatisController extends AppController {

    var $name = 'Origtermstatis';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html');

    const CASE_WHEN_EGRESS_COST = "case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end  ";
    const CASE_WHEN_INGRESS_CLIENT_COST = " case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end";

    #report field
    /* 	var $report_sql_field=	array(
      'org_bill_minute'=>"sum(ingress_bill_minutes::numeric) as org_bill_minute",
      'org_total_cost'=>" sum(  case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end  ) as org_total_cost ",
      'term_total_cost'=>"	  sum(  case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end   ) as term_total_cost  ",
      'profit'=>"(sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)-sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end))::numeric as profit",
      'org_avg_rate'=>"  ( sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)/NULLIF(sum(ingress_bill_minutes::numeric),0) ) ::numeric as org_avg_rate",

      'term_avg_rate'=>"( sum( case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end )/NULLIF(sum(egress_bill_minutes::numeric),0) ) ::numeric as term_avg_rate",
      'term_bill_minute'=>"  sum(egress_bill_minutes::numeric) as term_bill_minute",
      'profit_percentage'=>"(
      (
      sum(case  ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end )-sum( case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end ) ) *100/
      NULLIF(sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end),0) )::numeric as profit_percentage",

      'notzero_calls'=>" count(NULLIF(call_duration , '0')) as notzero_calls",
      'total_calls'=>" count(*) as total_calls",
      'total_duration'=>" (sum (NULLIF(call_duration,'0')::numeric )/60)::numeric as total_duration",
      'succ_calls'=>"count(case answer_time_of_date when '0' then null else answer_time_of_date end) as succ_calls",//"count(  case release_cause when '19' then 'Normal hang up' else null end  ) as succ_calls ",
      'busy_calls'=>"  count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls",
      'notchannel_calls'=>" count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end) as notchannel_calls",

      'asr_std'=>"(count(  case release_cause when '19' then 'Normal hang up' else null end )*100::numeric/ NULLIF((count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric as asr_std",
      'asr_cur'=>"(count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(count(*),0)) ::numeric as asr_cur ",
      'acd_std'=>"((sum (NULLIF(call_duration,'0')::numeric )/60) / NULLIF( count( case answer_time_of_date when '0' then null else answer_time_of_date end),0 ))::numeric as acd_std",
      'acd_cur'=>"((sum (NULLIF(call_duration,'')::numeric )/60)/ NULLIF( count( NULLIF(call_duration , '0') ) ,0) )::numeric as acd_cur"
      ); */

    var $report_sql_field = array(
        'org_bill_minute' => " sum(orig_bill_minute) as org_bill_minute",
        'org_total_cost' => " sum(ingress_cost) as org_total_cost ",
        'term_total_cost' => " sum(egress_cost) as term_total_cost  ",
        'profit' => " (sum(ingress_cost)-sum(egress_cost))::numeric as profit",
        'org_avg_rate' => " ( case sum(orig_bill_minute) when 0 then 0 else sum(ingress_cost)/sum(orig_bill_minute) end ) ::numeric as org_avg_rate",
        'term_avg_rate' => " ( case sum(term_bill_minute::numeric) when 0 then 0 else sum(egress_cost)/sum(term_bill_minute::numeric) end ) as term_avg_rate",
        'term_bill_minute' => " sum(term_bill_minute) as term_bill_minute",
        'profit_percentage' => " ( case sum(ingress_cost) when 0 then 0 else	(sum(ingress_cost)-sum(egress_cost)) *100/NULLIF(sum(ingress_cost),0) end)::numeric as profit_percentage",
        'notzero_calls' => " sum(not_zero_calls) as notzero_calls",
        'total_calls' => " sum(ca) as total_calls",
        'total_duration' => " sum(call_duration) as total_duration",
        'succ_calls' => " sum(ok_calls) as succ_calls ",
        'busy_calls' => "  sum(busy_calls) as busy_calls",
        'notchannel_calls' => " sum(no_channel_calls) as notchannel_calls",
        'asr_std' => " ( case sum(ca) when 0 then 0 else sum(ok_calls)*100/ sum(ca) end )::numeric as asr_std",
        'asr_cur' => " ( case sum(ca) when 0 then 0 else sum(not_zero_calls)::numeric *100/NULLIF(sum(ca),0) end) ::numeric as asr_cur ",
        'acd_std' => " (case sum(no_channel_calls) when 0 then 0 else sum(call_duration) / NULLIF(sum(no_channel_calls),0) end)::numeric as acd_std",
        'acd_cur' => " (case sum(not_zero_calls) when 0 then 0 else sum(call_duration)/ NULLIF(sum(not_zero_calls), 0) end)::numeric as acd_cur",
        'pdd' => " sum(pdd::numeric) as pdd"
    );

    function get_chart_field_sql($field) {
        $arr = array(
            'total_cost' => array(
                'org_total_cost' => "sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end) as org_total_cost",
                'term_total_cost' => "sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end ) as term_total_cost",
                'profit' => "(sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)-sum(egress_cost::numeric))::numeric as profit"
            ),
            'bill_time' => array(
                'org_bill_minute' => "sum(ingress_bill_minutes::numeric) as org_bill_minute",
                'term_bill_minute' => "  sum(egress_bill_minutes::numeric) as term_bill_minute",
                'total_duration' => " (sum (NULLIF(call_duration,'0')::numeric )/60)::numeric as total_duration"
            ),
            'asr_acd' => array(
                'asr_std' => "( case  sum(is_final_call::integer) when 0 then 0 else count(nullif(answer_time_of_date,'0'))*100::numeric/ NULLIF( sum(is_final_call::integer),0) end)::numeric as asr_std",
                'asr_cur' => "( case sum(is_final_call::integer) when 0 then 0 else count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(sum(is_final_call::integer),0) end) ::numeric as asr_cur ",
                'acd_std' => "( case count(case trunk_id_termination when '' then null else '1' end) when 0 then 0 else (sum (NULLIF(call_duration,'0')::numeric )/60) / NULLIF( count(NULLIF(answer_time_of_date,'0')),0 ) end)::numeric as acd_std",
                'acd_cur' => "(case count( NULLIF(call_duration , '0') ) when 0 then 0 else (sum (NULLIF(call_duration,'')::numeric )/60)/ NULLIF( count( NULLIF(call_duration , '0') ) ,0) end)::numeric as acd_cur"
            ),
            'call_count' => array(
                'notzero_calls' => " count(NULLIF(call_duration , '0')) as notzero_calls",
                'total_calls' => " sum(is_final_call::integer) as total_calls",
                'succ_calls' => "   count(case trunk_id_termination when '' then null else '1' end) as succ_calls ",
                'busy_calls' => "  count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls",
                'notchannel_calls' => " count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end) as notchannel_calls",
            )
        );

        if (isset($arr[$field])) {

            return $arr[$field];
        } else {

            return $arr['total_cost'];
        }
    }

    function inflector_humanize_chart_field($field) {
        $arr = array(
            'total_cost' => array(
                'org_total_cost' => 'Total Cost Orig',
                'term_total_cost' => "Total Cost Term",
                'profit' => "Profit"
            ),
            'bill_time' => array(
                'org_bill_minute' => "Time Billed Orig",
                'term_bill_minute' => "Time Billed Term",
                'total_duration' => "Time Total"
            ),
            'asr_acd' => array(
                'asr_std' => "ASR Std",
                'asr_cur' => "ASR Cur ",
                'acd_std' => "ACD Std",
                'acd_cur' => "ACD Cur"
            ),
            'call_count' => array(
                'notzero_calls' => "Calls Not Zero	",
                'total_calls' => " Calls  Total",
                'succ_calls' => "Calls  Success",
                'busy_calls' => "Calls Busy",
                'notchannel_calls' => "Calls No Channel"
            )
        );

        if (isset($arr[$field])) {

            return $arr[$field];
        } else {

            return $arr['total_cost'];
        }
    }

    function create_amchart_csv($field) {
        $where = $this->capture_report_condtions('orig_term_report');
        $order = $this->capture_report_order('orig_term_report');
        $field_sql_arr = $this->get_chart_field_sql($field);
        extract($this->capture_report_join('orig_term_report', ''));

        if (is_array($field_sql_arr)) {
            foreach ($field_sql_arr as $key => $value) {
                $sql = " copy (
				select to_char(time, 'YYYY-MM-DD HH24') as group_time,$value
		  from client_cdr $join 
		  where $where  group by group_time having 1=1 
		  order by group_time desc
			 ) to '" . Configure::read('database_actual_export_path') . "/orig_term_report_$key.csv'  csv ";
                $this->Cdr->query($sql);
                copy(Configure::read('database_export_path')  . "/orig_term_report_$key.csv", APP . 'webroot' . DS . 'amstock' . DS . "orig_term_report_$key.csv");
            }
        }
    }

    function flash_setting() {
        Configure::write('debug', 0);
        $field = !empty($_GET ['f']) ? $_GET ['f'] : 'duration';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "orig_term_report_$field" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
    }

    function create_amchart_setting_xml($field) {
        $humanize_field_arr = $this->inflector_humanize_chart_field($field);



        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out = '<settings>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_base.xml");
        $out.= '<data_sets>';
        $i = 0;
        foreach ($humanize_field_arr as $key => $value) {
            $out .= "<data_set did=\"$i\">\n";
            $out .= " <title>$value</title>\n";
            $out .= " <short>$value $value</short>\n";
            $out .= " <description>   $value;</description>\n";
            $out .= "<file_name>orig_term_report_$key.csv</file_name>\n";
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
            $i++;
        }
        $out.= '</data_sets>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_charts.xml");

        $out .= '</settings>';


        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "orig_term_report_$field" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    //读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    //初始化查询参数
    function init_query() {
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('code_deck', $this->Cdr->find_code_deck());
        $this->set('currency', $this->Cdr->find_currency());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
    }

    function capture_report_order($report_type) {
        $order = $this->_order_condtions(Array('group_time', 'profit', 'total_duration', 'org_bill_minute', 'org_total_cost', 'org_avg_rate', 'term_bill_minute', 'term_total_cost', 'term_avg_rate', 'asr_std', 'asr_cur', 'acd_std', 'acd_cur', 'total_calls', 'notzero_calls', 'succ_calls', 'busy_calls', 'notchannel_calls'));
        if (empty($order)) {
            $order = 'order by 1,2';
        } else {
            $order = 'order by ' . $order;
        }

        return $order;
    }

    function get_datas($type = '', $params = array()) {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('orig_term_report');
        $order = $this->capture_report_order('orig_term_report');
        extract($this->capture_report_join('orig_term_report', ''));
        $having = '';
        $fileter_where = '';
        if (isset($_GET ['searchkey'])) {

            if (!empty($_GET ['query'] ['asr_std_min']) || !empty($_GET ['query'] ['asr_std_max']) || !empty($_GET ['query'] ['asr_cur_min']) || !empty($_GET ['query'] ['asr_cur_max']) || !empty($_GET ['query'] ['profit_min']) || !empty($_GET ['query'] ['profit_max'])) {
                $having .= " ";
            }
            if (isset($_GET ['query'] ['asr_cur_min'])) {
                $asr_cur_min = $_GET ['query'] ['asr_cur_min'];
                if (!empty($asr_cur_min)) {
                    /* 			$having .= " 
                      and
                      (
                      ( count(NULLIF(call_duration , '0')) *100/NULLIF(count(*),0)) ::numeric
                      )
                      >$asr_cur_min"; */
                    $having .= " and  ( sum(call_duration)*60*100/NULLIF(sum(ca),0)) ::numeric ) > $asr_cur_min";

                    $fileter_where .= "asr_cur > $asr_cur_min";
                }
            }
            if (isset($_GET ['query'] ['asr_cur_max'])) {
                $asr_cur_max = $_GET ['query'] ['asr_cur_max'];
                if (!empty($asr_cur_max)) {
                    /* 	$having .= "  and (
                      ( count(NULLIF(call_duration , '0')) *100/NULLIF(count(*),0)) ::numeric
                      )<$asr_cur_max"; */
                    $having .= "  and (( sum(call_duration)*60*100/NULLIF(sum(ca),0)) ::numeric ) < $asr_cur_max";

                    $fileter_where .= "asr_cur<$asr_cur_max";
                }
            }
            if (isset($_GET ['query'] ['asr_std_min'])) {
                $asr_std_min = $_GET ['query'] ['asr_std_min'];
                if (!empty($asr_std_min)) {
                    /* $having .= "  and (
                      (count(nullif(answer_time_of_date,'0'))*100::numeric/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric

                      )>$asr_std_min"; */
                    $having .= "  and ( (sum(ok_calls)*100::numeric/ NULLIF( (sum(ca)-sum(no_channel_calls)),0) )::numeric ) > $asr_std_min";

                    $fileter_where .= "asr_std<$asr_std_min";
                }
            }
            if (isset($_GET ['query'] ['asr_std_max'])) {
                $asr_std_max = $_GET ['query'] ['asr_std_max'];
                if (!empty($asr_std_max)) {
                    /* 	$having .= "  and (
                      (count(nullif(answer_time_of_date,'0'))*100::numeric/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric
                      )<$asr_std_max"; */
                    $having .= " and (	(sum(ok_calls)*100::numeric/ NULLIF((sum(ca)),0) )::numeric ) < $asr_std_max";

                    $fileter_where .= "asr_std<$asr_std_max";
                }
            }
            if (isset($_GET ['query'] ['profit_min'])) {
                $profit_min = $_GET ['query'] ['profit_min'];
                if (!empty($profit_min)) {
                    /* $having .= "  and (
                      ( ( sum(ingress_client_cost::numeric)-sum(egress_cost::numeric) ) *100/ NULLIF(sum(ingress_client_cost::numeric),0) )::numeric
                      )>$profit_min"; */
                    $having .= "  and (	( ( sum(ingress_cost)-sum(egress_cost) ) *100/ NULLIF(sum(ingress_cost),0) )::numeric )>$profit_min";
                }
            }
            if (isset($_GET ['query'] ['profit_max'])) {
                $profit_max = $_GET ['query'] ['profit_max'];
                if (!empty($profit_max)) {
                    /* $having .= "  and (
                      ( ( sum(ingress_client_cost::numeric)-sum(egress_cost::numeric) ) *100/ NULLIF(sum(ingress_client_cost::numeric),0) )::numeric
                      )<$profit_max"; */
                    $having .= "  and (	( (sum(ingress_cost)-sum(egress_cost) ) *100/ NULLIF(sum(ingress_cost),0) )::numeric )<$profit_max";
                }
            }
        }

        $column = implode(',', array_values($this->report_sql_field));
        //$statistic_cdr = 'client_cdr';
        $statistic_cdr = $this->getTodayStatistic();
        if (!empty($group_by_where)) {
            $order .= ',' . $group_by_where;
        }

        if (!empty($group_by_where)) {
            $org_sql = "select   $group_by_field,$column	from $statistic_cdr $join  where $where  group by  $group_by_where  having 1=1   $having $order";
        } else {
            $org_sql = "select   $group_by_field,$column	from $statistic_cdr $join  where $where    having 1=1   $having $order";
        }
        $org_list = $this->Cdr->query($org_sql);
        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }

    function create_amchart_flash() {
        $this->create_amchart_csv('total_cost');
        $this->create_amchart_csv('bill_time');
        $this->create_amchart_csv('asr_acd');
        $this->create_amchart_csv('call_count');

        $this->create_amchart_setting_xml('total_cost');
        $this->create_amchart_setting_xml('bill_time');
        $this->create_amchart_setting_xml('asr_acd');
        $this->create_amchart_setting_xml('call_count');
    }

    function get_all_country() {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));

        $org_sql = "select  distinct  term_country  from client_cdr $join  where $where";
        //$org_sql="select  distinct  term_country  from statistic_cdr $join  where $where";
        $org_list = $this->Cdr->query($org_sql);

        $tmp_arr = array();
        if (!empty($org_list)) {
            $size = count($org_list);
            foreach ($org_list as $key => $value) {
                $tmp_arr[] = $value[0]['term_country'];
            }
        }
        return $tmp_arr;
    }

    function index() {
        $this->redirect('summary_reports');
    }

    function summary_reports() {


        //Configure::write('debug',0);

        $show_comm = true;
        $show_subgroups = 'false';
        $show_subtotals = 'false';
//		$this->_session_get ( isset ( $_GET ['searchkey'] ) );
        $this->pageTitle = "Statistics/Orig-Term Report
		";
        $t = getMicrotime();
        $this->init_query();

        $login_type = $_SESSION ['login_type'];
        extract($this->Cdr->get_real_period());

        //取数据  	 sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
        extract($this->get_datas());
        //画图的数据文件
        $image_file = $this->create_image_csv();
        $this->set('image_file', basename($image_file));

        $country_arr = $this->get_all_country();
        $this->set('country_arr', $country_arr);

        $this->set('field', 'org_bill_minute');



        $grpup_size = count($group_by_field_arr);
        if (isset($_GET ['query'] ['output_subgroups'])) {

            if (!empty($_GET ['query'] ['output_subgroups']) && $grpup_size > 1) {

                $show_comm = false; //普通表格显示
                $show_subgroups = 'true'; //以subgroup方式显示  
                $this->set('show_subgroups', $show_subgroups);
                $this->set('group_size', $grpup_size); //分组的个数
            }
        }
        $grpup_size = count($group_by_field_arr);
        if (isset($_GET ['query'] ['output_subtotals'])) {
            if (!empty($_GET ['query'] ['output_subtotals']) && $grpup_size > 1) {

                $show_subtotals = 'true'; //以subgtotal方式显示 
                $this->set('show_subtotals', $show_subtotals);
                $this->set('group_size', $grpup_size); //分组的个数
            }
        }

        $this->set('show_comm', $show_comm);
        $this->set('show_subtotals', $show_subtotals);
        extract($this->get_start_end_time());
        if (isset($_GET ['query'] ['output'])) {
            $file_name = $this->create_doload_file_name('Orig-TermReport', $start, $end);
            //下载
            if ($_GET ['query'] ['output'] == 'csv') {
                Configure::write('debug', 0);
                $this->layout = '';
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                $this->Cdr->export__sql_data('download Orig-Term Report ', $org_sql, $file_name);
                exit();
            } elseif ($_GET ['query'] ['output'] == 'xls') {
                Configure::write('debug', 0);
                $this->layout = '';
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                //$this->Cdr->export_xls_sql_data ('download Orig-Term Report ', $sql, 'report' );
                if ($this->Cdr->download_xls_by_sql($org_sql, array('objectives' => 'Orig-Term-report', 'file_name' => $file_name))) {
                    exit(1);
                }
            } elseif ($_GET ['query'] ['output'] == 'delayed') {
                Configure::write('debug', 0);
                $this->layout = '';
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                $this->Cdr->export__sql_data('download Orig-Term Report ', $org_sql, 'report');
                exit();
            } else {
                $this->set("client_org", $org_list);
                $this->set("group_by_field_arr", $group_by_field_arr);
            }
        } else {
            //get 请求
            //web显示


            $this->set("client_org", $org_list);
            $this->set("group_by_field_arr", $group_by_field_arr);
        }
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    function image_test() {
        //var_dump($_REQUEST);
        Configure::write('debug', 0);
        $this->layout = 'csv';
        require_once("vendors/image_test.php");
        //App::import ( 'Vendor', 'image_test');
        //$this->layout='csv';
    }

//生成image所需查询结果文件，返回文件名
    function create_image_csv() {
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
        if (!empty($result)) {
            $return = "/tmp/image_" . date("Ymd-His") . "_" . rand(0, 99);
            file_put_contents($return, json_encode($result));
        }
        return $return;
    }

}