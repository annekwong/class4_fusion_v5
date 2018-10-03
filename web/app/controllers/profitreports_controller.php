<?php

class ProfitreportsController extends AppController
{

    var $name = 'Profitreports';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html');

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
        } else
        {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit ['executable']);
            $this->Session->write('writable', $limit ['writable']);
        }
        parent::beforeFilter();
    }

    function create_amchart_setting_xml($report_type, $field)
    {
        $humanize_field_arr = $this->inflector_humanize_chart_field($field);



        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out = '<settings>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_base.xml");
        $out.= '<data_sets>';
        $i = 0;
        foreach ($humanize_field_arr as $key => $value)
        {
            $out .= "<data_set did=\"$i\">\n";
            $out .= " <title>$value</title>\n";
            $out .= " <short>$value $value</short>\n";
            $out .= " <description>   $value;</description>\n";
            $out .= "<file_name>profit_report_$key.csv</file_name>\n";
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


        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "profit_report_$field" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    function flash_setting()
    {
        Configure::write('debug', 0);
        $field = !empty($_GET ['f']) ? $_GET ['f'] : 'duration';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "profit_report_$field" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
    }

    //初始化查询参数
    function init_query()
    {
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('currency', $this->Cdr->find_currency());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
    }

    function get_field_sql($report_type, $field)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $arr = array(
            'profit' => array(
                'profit' => "(sum(ingress_client_cost)-sum(egress_cost)) as profit",
                'profit_percentage' => "((sum(ingress_client_cost)-sum(egress_cost) ) *100/ sum(ingress_client_cost)) as profit_percentage",
            ),
            'call_count' => array(
                'notzero_calls' => " count(call_duration) as notzero_calls",
                'total_calls' => " count(*) as total_calls",
                'succ_calls' => " count(answer_time_of_date) as succ_calls ",
            ),
            'duration' => array(
                'duration' => "sum(call_duration)/60 as call_duration",
                'duration_percentage' => "(sum(call_duration)/(select sum(call_duration)  from  client_cdr $join where $where)) as  call_duration_percentage"
            )
        );

        if (isset($arr[$field]))
        {

            return $arr[$field];
        } else
        {

            return $arr['duration'];
        }
    }

    function inflector_humanize_chart_field($field)
    {
        $arr = array(
            'profit' => array(
                'profit' => "Profit",
                'profit_percentage' => "Profit Percentage",
            ),
            'call_count' => array(
                'notzero_calls' => "Calls Not Zero	",
                'total_calls' => " Calls  Total",
                'succ_calls' => "Calls  Success"
            ),
            'duration' => array(
                'duration' => "Call Duration",
                'duration_percentage' => "Duration Percentage"
            )
        );

        if (isset($arr[$field]))
        {
            return $arr[$field];
        } else
        {

            return $arr['duration'];
        }
    }

    function get_datas($report_type, $type)
    {

        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);

        $order = $this->capture_report_order();
        if ($type == 1)
        {
            extract($this->capture_report_join($report_type, 'egress_alias'));
        } else
        {
            extract($this->capture_report_join($report_type, 'ingress_alias'));
            $where .= " AND is_final_call=1";
        }

        //call count占的百分比

        $per_callcount = "(count(*)::real/ 
( select count(*) from client_cdr $join where $where	))  as  call_count_percentage";

        //call_duration 占的百分比
        $per_call_duration = " case when (select sum(call_duration) 
from client_cdr $join where $where) = 0 then 0 else (sum(call_duration)::real/(select sum(call_duration) 
from client_cdr $join where $where))  end as  call_duration_percentage";





        $having = '';

        $column = "sum(ingress_client_cost) as ingress_cost,sum(egress_cost) as egress_cost,case when sum(ingress_client_cost) = 0 then 0 else (sum(ingress_client_cost) -
sum(egress_cost) *100/sum(ingress_client_cost)) end as profit_percentage, 
(sum(ingress_client_cost) -
sum(egress_cost)) as profit, 
count(ingress_id) as total_calls, 
count(case release_cause when 19 then 'Normal hang up' else null end) as succ_calls, 
count(call_duration) as notzero_calls,$per_callcount,sum(call_duration)/60 
as call_duration,$per_call_duration";
        if (!empty($group_by_where))
        {
            $this->set('is_group', 'true');
            $org_sql = "select   $group_by_field,$column	from client_cdr $join    where $where  group by  $group_by_where  $order";
        } else
        {
            $this->set('is_group', 'false');
            $org_sql = "select   $group_by_field,$column	from client_cdr $join  where $where      $order";
        }
        $org_list = $this->Cdr->query($org_sql);

        $sum_sql = "select   $column	from client_cdr $join  where $where      ";
        $sum_list = $this->Cdr->query($sum_sql);
        $this->set('sum_list', $sum_list);
        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }
    

    function capture_report_order()
    {
        $order = $this->_order_condtions(
                Array('date', 'rate_table_name', 'cdr_size', 'call_count_percentage', 'call_duration', 'call_duration_percentage', 'ingress_alias', 'profit', 'total_calls', 'notzero_calls', 'succ_calls', 'termination_source_host_name', 'egress_host', 'term_rate', 'term_country', 'term_code', 'term_code_name', 'term_client_name', 'ingress_host', 'orig_rate', 'orig_country', 'orig_code', 'orig_code_name', 'egress_alias', 'orig_client_name')
        );
        if (empty($order))
        {
            $order = 'order by 1,2 desc';
        } else
        {
            $order = 'order by ' . $order;
        }

        return $order;
    }

    function summary_reports($type = 0)
    {

        $this->set('show_comm', true);
        //	$this->_session_get(isset ( $_GET ['searchkey'] ));
        $this->pageTitle = "Statistics/Profitability Analysis";
        $t = getMicrotime();
        $this->init_query();
        //extract($this->Cdr->get_real_period());
        extract($this->get_start_end_time());
        //取数据  	 sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
        $report_type = 'cdr_search';
        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, $type));
        //$this->create_amchart_flash ( $report_type );
        if (isset($_GET ['query'] ['output']))
        {
            //下载
            $file_name = $this->create_doload_file_name('ProfitabilityReport', $start, $end);
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
                $this->_catch_exception_msg(array('ProfitreportsController', '_reports_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name));

                $this->layout = 'csv';
            } elseif ($_GET ['query'] ['output'] == 'xls')
            {
                Configure::write('debug', 0);
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
                $this->_catch_exception_msg(array('ProfitreportsController', '_reports_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));

                $this->layout = 'csv';
            } elseif ($_GET ['query'] ['output'] == 'delayed')
            {
//						Configure::write('debug',0);
//					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
//						$this->_catch_exception_msg(array('RatereportsController','_reports_download_impl'),array('download_sql' => $org_sql));
//	  
//  	$this->layout='csv';
            } else
            {
                //web显示

                $this->set("client_org", $org_list);
                $this->set("group_by_field_arr", $group_by_field_arr);
            }
        } else
        {


            $this->set("client_org", $org_list);
            $this->set("group_by_field_arr", $group_by_field_arr);
        }


        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    function _reports_download_impl($params = array())
    {
        extract($params);
        if ($this->Cdr->download_by_sql($download_sql, array('objectives' => 'rate_reports', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

    function _reports_download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'rate_reports', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

}