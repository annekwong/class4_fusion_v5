<?php

/**
 * 
 * 
 * 带宽统计
 * @author root
 *
 */
class BandwidthsController extends AppController
{

    var $name = 'Bandwidths';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html');

    function index()
    {
        $this->redirect('summary_reports');
    }

    function create_amchart_setting_xml()
    {
        $humanize_field_arr = $this->inflector_humanize_chart_field();
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
            $out .= "<file_name>bandwidth_$key.csv</file_name>\n";
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


        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "bandwidth" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    function create_amchart_flash()
    {
        $this->create_amchart_csv();
        $this->create_amchart_setting_xml();
    }

    function create_amchart_csv()
    {
        $where = $this->capture_report_condtions('summary_report');
        $order = $this->capture_report_order('summary_report');
        $field_sql_arr = $this->get_chart_field_sql();
        extract($this->capture_report_join('summary_report', ''));

        if (is_array($field_sql_arr))
        {
            foreach ($field_sql_arr as $key => $value)
            {
                $sql = " copy (
				select to_char(time, 'YYYY-MM-DD HH24') as group_time,$value
		  from client_cdr $join 
		  where $where  group by group_time having 1=1 
		  order by group_time desc
			 ) to '" . Configure::read('database_actual_export_path') .  "/bandwidth_$key.csv'  csv ";
                $this->Cdr->query($sql);
                copy( Configure::read('database_export_path') . "/bandwidth_$key.csv", APP . 'webroot' . DS . 'amstock' . DS . "bandwidth_$key.csv");
            }
        }
    }

    function get_chart_field_sql()
    {
        return $this->report_orig_sql_field;
    }

    var $report_orig_sql_field = array(
        'incoming_bandwidth' => "sum(origination_ingress_packets +termination_ingress_packets) as incoming_bandwidth",
        'outgoing_bandwidth' => "sum(origination_egress_packets +termination_egress_packets) as outgoing_bandwidth",
        'calls' => "count(id) as calls"
    );

    function inflector_humanize_chart_field()
    {

        return array(
            'incoming_bandwidth' => "Incoming Bandwith",
            'outgoing_bandwidth' => "outgoing Bandwidth",
            'calls' => "calls"
        );
    }

    function flash_setting()
    {
        Configure::write('debug', 0);
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "bandwidth" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
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
        parent::beforeFilter(); //调用父类方法
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

    function capture_report_order()
    {
        $order = $this->_order_condtions(
                Array('date', 'calls', 'incoming_bandwidth', 'outgoing_bandwidth')
        );
        if (empty($order))
        {
            $order = '  order by 1,2';
        } else
        {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function get_datas($report_type)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order();
        $column = implode(',', array_values($this->report_orig_sql_field));
        if (!empty($group_by_where))
        {
            $org_sql = "select   $group_by_field,$column	from client_cdr $join  where $where  group by  $group_by_where     $order";
        } else
        {
            $org_sql = "select   $group_by_field,$column	from client_cdr $join  where $where     $order";
        }
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        if(isset($_GET['query']['output']) || $is_preload)
        {
        
            $org_list = $this->Cdr->query($org_sql);
        }
        else
        {
            $org_list = array();
            $this->set('show_nodata', false);
        }


        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }

    function summary_reports()
    {
        $this->pageTitle = "Statistics/Bandwidth Report";
        $t = getMicrotime();
        //取数据  	 sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
        extract($this->get_datas('cdr_search'));
        //	$this->create_amchart_flash();
        $this->init_query();



        extract($this->get_start_end_time());

        if (isset($_GET ['query'] ['output']))
        {
            $file_name = $this->create_doload_file_name('BandwidthReport', $start, $end);
            //下载
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                $download_sql = $org_sql;
                $this->_catch_exception_msg(array('BandwidthsController', '_summary_reports_download_impl'), array('download_sql' => $download_sql, 'file_name' => $file_name));
                exit();
            } elseif ($_GET ['query'] ['output'] == 'xls')
            {
                Configure::write('debug', 0);
                $download_sql = $org_sql;
                $this->_catch_exception_msg(array('BandwidthsController', '_summary_reports_download_xls'), array('download_sql' => $download_sql, 'file_name' => $file_name));
                exit();
            } elseif ($_GET ['query'] ['output'] == 'delayed')
            {
//				Configure::write('debug',0);
//					$download_sql = $org_sql ."UNION ALL ($term_sql)";
//					$this->_catch_exception_msg(array('ClientsummarystatisController','_summary_reports_download_impl'),array('download_sql' => $download_sql));
//				exit ();
            } else
            {
                //web显示
                $this->set("client_org", $org_list);

                $this->set("group_by_field_arr", $group_by_field_arr);
            }
        } else
        {
            //get 请求
//web显示
            $this->set("client_org", $org_list);
            $this->set("group_by_field_arr", $group_by_field_arr);
        }


        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    function _summary_reports_download_impl($params = array())
    {
        extract($params);
        if ($this->Cdr->download_by_sql($download_sql, array('objectives' => 'summary_reports', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

    function _summary_reports_download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'summary_reports', 'file_name' => $file_name)))
        {
            exit(1);
        }
    }

}