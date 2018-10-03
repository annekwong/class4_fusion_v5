<?php

class DipsController extends AppController
{

    var $name = "Dips";
    var $uses = array('Cdr','CdrExportLog','CdrRead');
    var $helpers = array('javascript', 'html', 'AppCdr');

    public function index($id = null, $cli_id = null)
    {
        $this->select_name($cli_id);
        $this->pageTitle = "Statistics/Spam Report ";
        $t = getMicrotime();
        if (!empty($this->params['pass'][0]))
        {
            $rate_type = $this->params['pass'][0];
            if ($rate_type == 'all')
            {
                $this->set('rate_type', 'all');
            }
            elseif ($rate_type == 'spam')
            {
                $this->set('rate_type', 'spam');
            }
            else
            {
                $this->set('rate_type', 'all');
            }
        }
        else
        {
            $rate_type = 'all';
            $this->set('rate_type', 'all');
        }
        if ($rate_type == 'spam')
        {
            $report_type = "spam_report";
        }
        else
        {
            $report_type = "cdr_search";
        }
        $this->init_query();
        //extract($this->Cdr->get_real_period());
        extract($this->get_start_end_time());
        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, ''));
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->CdrRead->query($count_sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        $org_page_sql = $org_sql . $page_where;
        if (isset($_GET ['query'] ['output']))
        {
            extract($this->get_start_end_time());
            $file_name = $this->create_doload_file_name('cdr', $start, $end);
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('DipsController', '_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name,'start'=>$start,'end'=>$end));
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('DipsController', '_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'delayed')
            {
                //delayed csv
            }
            else
            {
                //web显示
                $results = $this->CdrRead->query($org_page_sql);
                $page->setDataArray($results);
                $this->set('p', $page);
            }
        }
        else
        {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];

            if ($is_preload)
            {
                $results = $this->CdrRead->query($org_page_sql);
            }
            else
            {
                $results = array();
            }
            $page->setDataArray($results);
            $this->set('p', $page);
        }
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    function init_query()
    {
        $this->set('all_carrier', $this->Cdr->findClient());
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('all_rate_table', $this->Cdr->find_all_rate_table());
//        $this->set('currency', $this->Cdr->find_currency());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
        $this->set('all_host', $this->Cdr->find_all_resource_ip());
        $this->set('cdr_field', $this->Cdr->find_field());
    }

    public function select_name($id = null)
    {
        if (empty($id))
        {
            $this->set('name', '');
        }
        else
        {
            $sql = "select name from client where client_id=$id";
            $name = $this->Cdr->query($sql);
            if (empty($name))
            {
                $this->set('name', '');
            }
            else
            {
                $this->set('name', $name[0][0]['name']);
            }
        }
    }

    function get_datas($report_type = '', $process_field)
    {

        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);

        $spam_where = '';
        if ($report_type == 'spam_report')
        {
            $spam_where = "and  release_cause <> '19' ";
        }
        $where = $where . $spam_where . 'and lrn_number_vendor in (1, 2, 3) and is_final_call = 1'; //TODO 这里是否能实现？
        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order2();

        /*
          $release_cause = "case  lrn_number_vendor
          when    '1'     then   'Client'
          when    '2'     then   'NPDB'
          when    '3'     then   'CACHE'  end  as
          lrn_number_vendor";
         */


        //default  
        if (empty($show_field))
        {

            //默认的 显示字段

            $show_field = "trunk_id_origination,origination_source_host_name,to_timestamp(start_time_of_date/ 1000000) as start_time_of_date,lrn_dnis,origination_source_number as  ani,origination_destination_number as dnis, origination_destination_host_name as switch_ip";
        }

        $inner_show_fields = "trunk_id_origination,origination_source_host_name,start_time_of_date,lrn_dnis,origination_source_number,origination_destination_number, origination_destination_host_name";
        $show_field_array = array('trunk_id_origination', 'origination_source_host_name', 'start_time_of_date', 'lrn_dnis', 'ani', 'dnis', 'switch_ip');
        //cdr 显示字段
        if (isset($_GET ['query'] ['fields']))
        {
            $show_field = '';
            $show_field_array = $_GET ['query'] ['fields'];
            $sql_field_array = $show_field_array;
            $sql_field_array = $this->sql_field_array_help($sql_field_array);
            if (!empty($sql_field_array))
            {
                $show_field = join(',', $sql_field_array);
            }
        }

        $this->set('show_field_array', $show_field_array);
        #other  report cdr
        if (isset($this->params['pass'][0]))
        {
            #查看client的cdr
            if ($this->params['pass'][0] == 'client')
            {
                $this->pageTitle = "Statistics/LNP Dip Record";
                if (!empty($this->params['pass'][1]))
                {
                    $where.= " and (ingress_client_id='{$this->params['pass'][1]}'  or  egress_client_id='{$this->params['pass'][1]}')";
                }
            }
            #查看断开码对应的cdr
            if ($this->params['pass'][0] == 'disconnect')
            {

                if (!empty($this->params['pass'][1]))
                {
                    if ($this->params['pass'][2] == 'org')
                    {

                        $where.= "and   release_cause ='{$this->params['pass'][3]}'  and    binary_value_of_release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                    }
                    else
                    {
                        $where.= " and   release_cause ='{$this->params['pass'][3]}' and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                    }
                }
            }
            #download mismatch cdr
            if ($this->params['pass'][0] == 'mismatch')
            {
                if ($this->params['pass'][1] == 'unknowncarriers')
                {
                    $where.= " and ingress_client_bill_result=2";
                }
                if ($this->params['pass'][1] == 'unknownratetable')
                {
                    $where.= " and ingress_client_bill_result=3";
                }
                if ($this->params['pass'][1] == 'unknownrate')
                {
                    $where.= " and ingress_client_bill_result=4";
                }
            }
        }


        if (!empty($process_field))
        {
            $show_field = $process_field;
        }
        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz']))
        {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        }
        $table_name = "client_cdr";
        $table_data_arr = $this->x_get_date_result_admin($start,$end,$table_name);
        $single_sql_arr = array();
        $single_count_arr = array();
        foreach ($table_data_arr as $table_data)
        {
            $tmp_where = str_replace($table_name,$table_data,$where);
            $single_sql_arr[] = " SELECT $inner_show_fields FROM $table_data WHERE $tmp_where ";
            $single_count_arr[] = " SELECT count(*) as sum FROM $table_data  WHERE $tmp_where ";
        }
        $union_sql = implode("UNION ALL",$single_sql_arr);
        $count_union_sql = implode("UNION ALL",$single_count_arr);
        $count_sql = "select sum(sum) as c from ($count_union_sql) as t1";
        $org_sql = "select $show_field from ({$union_sql}) as t1 $order ";

//        pr('$org_sql is <br />'.$org_sql);
        return compact('org_sql', 'count_sql');
    }

    function sql_field_array_help($arr)
    {
        /*$release_cause = "case  release_cause
	 	
	 	when    '0'     then   'Invalid Argument'
  	when    '1'     then   'System Limit Exceeded'
  	when    '2'     then   'SYSTEM_CPS System Limit Exceeded'
  	when    '3'     then   'Unauthorized IP Address'
  	when    '4'     then   ' No Ingress Resource Found'
		when    '5'     then   'No Product Found '
		when    '6'     then   'Trunk Limit CAP Exceeded'
		when    '7'     then   'Trunk Limit CPS Exceeded'
		when    '8'     then   'IP Limit  CAP Exceeded'
		when    '9'     then   'IP Limit CPS Exceeded 	'
		when    '10'    then   'Invalid Codec Negotiation'
		when    '11'    then   'Block due to LRN'
		when   '12' 			then  'Ingress Rate Not Found'  
		when   '13' 			then  ' Egress Trunk Not Found'  
		when   '14' 			then  'From egress response 404'  
		when   '15' 			then  'From egress response 486 '  
		when   '16' 			then  'From egress response 487 	'  
		when   '17' 			then  'From egress response 200 '  
		when   '18' 			then  'All egress not available'  
		when   '19' 			then  'Normal hang up' 
		when   '20' 			then  'Ingress Resource disabled'   
		when   '21' 			then  'Balance Use Up'   
		when   '22' 			then  'No Routing Plan Route'   
		when   '23' 			then  'No Routing Plan Prefix'   
		when   '24' 			then  'Ingress Rate No configure'
                when   '25'                     then 'Invalid Codec Negotiation'
                when   '26'                     then 'No Codec Found'
                when   '27'                     then 'All egress no confirmed'
                when   '28'                     then 'LRN response no exist DNIS'
		else    'other'  end  as
		release_cause";*/
        $release_cause = " release_cause ";
        $t_arr = array();
        foreach ($arr as $key => $value)
        {
            $t_arr[$key] = $value;
            if ($value == 'start_time_of_date')
            {
                $t_arr[$key] = "to_timestamp(start_time_of_date/1000000) ::bigint) as start_time_of_date";
            }
            if ($value == 'answer_time_of_date')
            {
                $t_arr[$key] = "case answer_time_of_date when '0' then null else to_timestamp(substring(answer_time_of_date from 1 for 10) ::bigint) end as answer_time_of_date";
            }
            if ($value == 'release_tod')
            {
                $t_arr[$key] = "to_timestamp(release_tod/ 1000000) as release_tod";
            }


            if ($value == "egress_id")
            {
                $t_arr[$key] = "(select alias from resource where resource_id = client_cdr.egress_id and egress = true limit 1) as egress_id";
            }

            if ($value == "ingress_id")
            {
                $t_arr[$key] = "(select alias from resource where resource_id = client_cdr.ingress_id and ingress = true limit 1) as ingress_id";
            }

            if ($value == "egress_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = client_cdr.egress_rate_table_id and client_cdr.egress_rate_table_id is not null) as  egress_rate_table_id";
            }


            if ($value == "ingress_client_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = client_cdr.ingress_client_rate_table_id::integer and client_cdr.ingress_client_rate_table_id != '') as  ingress_client_rate_table_id";
            }

            if ($value == "ingress_client_currency_id")
            {
                $t_arr[$key] = "(select code from currency where currency_id = client_cdr.ingress_client_currency_id and client_cdr.ingress_client_currency_id is not null) as ingress_client_currency_id";
            }

            if ($value == "ingress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = client_cdr.ingress_client_id and client_cdr.ingress_client_id is not null) as ingress_client_id";
            }

            if ($value == "egress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = client_cdr.egress_client_id and client_cdr.egress_client_id is not null) as egress_client_id";
            }

//          if($value=="ingress_dnis_type") {
//              $t_arr[$key] = "case ingress_dnis_type when '0' then 'dnis' when '1' then 'lrn' when '2' then 'lrn block' end as ingress_dnis_type";
//          }


            if ($value == 'release_cause')
            {
                $t_arr[$key] = $release_cause;
            }

            if ($value == 'ingress_rate_type')
            {
                $t_arr[$key] = "case ingress_rate_type when 1 then 'inter' when 2 then 'inter'  when 4 then 'error' when 5 then 'local' else 'others' end as ingress_rate_type";
            }
            if ($value == 'egress_rate_type')
            {
                $t_arr[$key] = "case egress_rate_type when 1 then 'inter' when 2 then 'intra'   when 4 then 'error' when 5 then 'local' else 'others' end as egress_rate_type";
            }
        }
        return $t_arr;
    }

    function _download_impl($params = array())
    {
        //Configure::write('debug', 0);
        extract($params);
//        var_dump($params);die;
        $job_id = $this->Cdr->download_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name, 'start' => $start, 'end' => $end,'is_dipp'=>true));
        if ($job_id)
        {
            //exit(1);
            $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] of exports of CDRs between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/dips/export_log');
        }
        else
        {
            $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/dips/index');
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
    
    function export_log()
    {
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CdrExportLog.id' => 'desc',
            ),
            'conditions'=>array(
                'is_dipp' => true
            ),
        );
        if (isset($_SESSION['sst_client_id']))
        {
            $this->paginate['conditions']['user_id'] =  $_SESSION['sst_client_id'];
        }
        $status = array("Waiting", "In Progress", "Query", "Compress", "Done", "Canceled");
        $this->set('status', $status);
        $this->data = $this->paginate('CdrExportLog');
    }

}

?>
