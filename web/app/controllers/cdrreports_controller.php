<?php

class CdrreportsController extends AppController
{

    var $name = 'Cdrreports';
    var $uses = array('Cdr', 'CdrExportLog', 'Cdrrerate', 'Cdrs', 'SwitchProfile', 'CdrListExportLog', 'Systemparam');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        if ($this->params['action'] == 'get_file' || $this->params['action'] == 'get_export_file' || $this->params['action'] == 'export_log_down' || 'export_log_down_file' == $this->params['action'])
            return true;
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

    public function check_email()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT email FROM users where user_id = {$user_id}";
        $result = $this->Cdr->query($sql);
        $email = $result[0][0]['email'];
        echo json_encode(array('email' => $email));
    }

    public function update_email()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $email = $_POST['email'];
        $user_id = $_SESSION['sst_user_id'];
        $sql = "UPDATE users SET email = '{$email}' WHERE user_id = {$user_id}";
        $this->Cdr->query($sql);
        echo 1;
    }

    function join_str($separator, $arr)
    {
        $t = array();
        foreach ($arr as $key => $value)
        {
            $t[] = "'" . $value . "'";
        }
        return join(',', $t);
    }

    function init_query()
    {
        $this->set('all_carrier', $this->Cdr->findClient());
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('all_rate_table', $this->Cdr->find_all_rate_table());
        $this->set('currency', $this->Cdr->find_currency1());
        $this->set('server', $this->Cdr->find_server());
        $this->set('report_server', $this->Cdr->find_report_server());
        $this->set('defalut_report_server', '192.168.1.107:3300');
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
        $this->set('all_host', $this->Cdr->find_all_resource_ip());
        $this->set('cdr_field', $this->Cdr->find_field());

        if (!empty($_GET['ingress_alias']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    public function download()
    {
        $file = $_GET['file'];
        Configure::write('debug', '0');
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $basename = basename($file);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$basename}");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($file);
    }

    function get_release_condition()
    {
        if (isset($_GET['cdr_release_cause']) && $_GET['cdr_release_cause'] != '')
        {

            return "and release_cause={$_GET ['cdr_release_cause']}";
        }
        else
        {
            return '';
        }
    }

    function capture_report_order()
    {
        $order = $this->_order_condtions(
                array_keys($this->Cdr->find_field())
        );
        if (empty($order))
        {
            $order = 'order by time desc';
        }
        else
        {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function get_datas($report_type = '', $process_field)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        $spam_where = '';
        if ($report_type == 'spam_report')
        {
            $spam_where = "and  release_cause = 3 ";
        }
        $where = $where . $spam_where;
        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order();


        $release_cause = "case  release_cause
	 	when    0    then   'Unknown Exception'
                when    1     then   'System CPS Limit Exceeded'
                when    2     then   'SYSTEM_CPS System Limit Exceeded'
                when    3     then   'Unauthorized IP Address'
                when    4     then   ' No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit Call Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  Call Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when   10    then   'Invalid Codec Negotiation'
		when   11    then   'Block due to LRN'
		when   12 			then  'Ingress Rate Not Found'  
		when   13 			then  ' Egress Trunk Not Found'  
		when   14 			then  'Egress Returns 404'  
		when   15 			then  'Egress Returns 486'  
		when   16 			then  'Egress Returns 487'  
		when   17 			then  'Egress Returns 200'  
		when   18 			then  'All Egress Unavailable'  
		when   19 			then  'Normal hang up' 
		when   20 			then  'Ingress Resource disabled'   
		when   21 			then  'Zero Balance'   
		when   22 			then  'No Route Found'   
		when   23 			then  'Invalid Prefix'   
		when   24 			then  'Ingress Rate Missing'
		when   25                     then 'Invalid Codec Negotiation'
		when   26                     then 'No Codec Found'
		when   27                     then 'All Egress Failed'
		when   28                     then 'LRN Response Missing'
		when   29    then 'Carrier Call Limit Exceeded'
		when   30    then 'Carrier CPS Limit Exceeded'
		when   31   then 'Rejected Due to Host Alert'
		when   32   then 'Rejected Due to Trunk Alert'
		when   33   then 'H323 Not Supported'
		when   34   then '180 Negotiation Failure'
		when   35   then '183 Negotiation Failute'
		when   36  then '200 Negotiation Failure'
		when   37  then 'Block LRN with Higher Rate'
                when   38 then 'Ingress Block ANI'
                when   39 then 'Ingress Block DNIS'
                when   40 then 'Ingress Block ALL'
                when   41 then 'Global Block ANI'
                when   42 then 'Global Block DNIS'
                when   43 then 'Global Block ALL'
                when   44 then 'T38 Reject'
		else    'other'  end  as
		release_cause";

        $trunk_type = "case trunk_type when 1 then 'class4' when 2 then 'exchange' end as trunk_type";

        $binary_value_of_release_cause_from_protocol_stack = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";

        //default  
        if (empty($show_field))
        {

            //默认的 显示字段


            $show_field = "call_duration,origination_destination_host_name,trunk_id_termination,trunk_id_origination,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call,{$trunk_type}";
            if (isset($_GET['open_callmonitor']) && $_GET['open_callmonitor'] == 1)
                $show_field = $show_field . ',id';

            if ($report_type == 'spam_report')
                $show_field = "origination_destination_number,origination_source_host_name,origination_source_number,time";
        }

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3')
        {
            /* $show_field_array=array(
              'origination_source_host_name', 'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
              );
             */
            $show_field_array = array(
                'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
            );
            $show_field_array_bak = array_keys($this->Cdr->find_client_cdr_field());
            $show_field_array = array_intersect($show_field_array, $show_field_array_bak);
            $show_field_array = explode(',', implode(',', $show_field_array));
            //$show_field_array = array_keys($this->Cdr->find_client_cdr_field());
            $show_field = implode(',', $show_field_array);
        }
        else
        {
            $show_field_array = array('call_duration', 'trunk_id_termination', 'trunk_id_origination', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name', 'trunk_type');
            if (isset($_GET['open_callmonitor']) && $_GET['open_callmonitor'] == 1)
                array_push($show_field_array, 'id');

            if ($report_type == 'spam_report')
                $show_field_array = array('origination_destination_number', 'origination_source_host_name', 'origination_source_number', 'time');
        }
        //cdr 显示字段
        if (isset($_GET ['query'] ['fields']))
        {
            $show_field = '';
            $show_field_array = $_GET ['query'] ['fields'];
            $sql_field_array = $show_field_array;
            //array_push($sql_field_array, 'id');
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
                $this->pageTitle = "Statistics/CDR Search ";
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
                        //$where.= " and   release_cause ='{$this->params['pass'][3]}' and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                        //$where.= " and   release_cause  is null and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";
                        $where.= "and   release_cause ='{$this->params['pass'][3]}'  and    release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";
                    }
                }
            }
            #download mismatch cdr
            if ($this->params['pass'][0] == 'mismatch')
            {
                if ($this->params['pass'][1] == 'unknowncarriers')
                {
                    $where.= " and ingress_client_bill_result='2'";
                }
                if ($this->params['pass'][1] == 'unknownratetable')
                {
                    $where.= " and ingress_client_bill_result='3'";
                }
                if ($this->params['pass'][1] == 'unknownrate')
                {
                    $where.= " and ingress_client_bill_result='4'";
                }
            }
        }


        if (!empty($process_field))
        {
            $show_field = $process_field;
        }
        $count_sql = "select count(*) as c from   client_cdr $join    where  $where";
        $org_sql = "select $show_field  from   client_cdr $join     where   $where  $order  ";
        return compact('org_sql', 'count_sql', 'where', 'show_field', 'show_field_array');
    }

    function get_rerate_field()
    {
        return "id,
							origination_source_number as ani,
							origination_destination_number  as dnis,
							call_duration as duration,
							ingress_client_rate as  new_orig_rate,
							ingress_client_cost  as new_orig_rate_cost,
							egress_rate  as new_term_rate
							,egress_cost  as new_term_rate_cost
							,start_time_of_date  as begin_time
							,release_tod  as  end_time
							,rerate_time   

		";
    }

    /**
     * @return  rerate cdr data
     * @param unknown_type $report_type
     * @param unknown_type $order_field
     */
    function get_rerate_cdrdatas($report_type = '', $process_type)
    {

        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);

        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order();
        if ($order == 'order by id desc')
        {
            $order = " order by rerate_time desc";
        }

        $release_cause = "case  release_cause
	 	when    0    then   'Unknown Exception'
                when    1     then   'System CPS Limit Exceeded'
                when    2     then   'SYSTEM_CPS System Limit Exceeded'
                when    3     then   'Unauthorized IP Address'
                when    4     then   ' No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit Call Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  CAP Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when   10    then   'Invalid Codec Negotiation'
		when   11    then   'Block due to LRN'
		when   12 			then  'Ingress Rate Not Found'  
		when   13 			then  ' Egress Trunk Not Found'  
		when   14 			then  'Egress Returns 404'  
		when   15 			then  'Egress Returns 486'  
		when   16 			then  'Egress Returns 487'  
		when   17 			then  'Egress Returns 200'  
		when   18 			then  'All Egress Unavailable'  
		when   19 			then  'Normal hang up' 
		when   20 			then  'Ingress Resource disabled'   
		when   21 			then  'Zero Balance'   
		when   22 			then  'No Route Found'   
		when   23 			then  'Invalid Prefix'   
		when   24 			then  'Ingress Rate Missing'
		when   25                     then 'Invalid Codec Negotiation'
		when   26                     then 'No Codec Found'
		when   27                     then 'All Egress Failed'
		when   28                     then 'LRN Response Missing'
		when   29    then 'Carrier Call Limit Exceeded'
		when   30    then 'Carrier CPS Limit Exceeded'
		when   31   then 'Rejected Due to Host Alert'
		when   32   then 'Rejected Due to Trunk Alert'
		when   33   then 'H323 Not Supported'
		when   34   then '180 Negotiation Failure'
		when   35   then '183 Negotiation Failute'
		when   36  then '200 Negotiation Failure'
		when   37  then 'Block LRN with Higher Rate'
                when   38 then 'Ingress Block ANI'
                when   39 then 'Ingress Block DNIS'
                when   40 then 'Ingress Block ALL'
                when   41 then 'Global Block ANI'
                when   42 then 'Global Block DNIS'
                when   43 then 'Global Block ALL'
                when   44 then 'T38 Reject'
		else    'other'  end  as
		release_cause";
        $show_field = "call_duration,rerate_time,trunk_id_origination,origination_destination_number,origination_source_number,$release_cause,release_cause_from_protocol_stack,binary_value_of_release_cause_from_protocol_stack,time";
        $show_field_array = array('call_duration', 'rerate_time', 'trunk_id_origination', 'origination_destination_number', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'binary_value_of_release_cause_from_protocol_stack', 'time');
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

        /*
          #主叫计费
          if(!empty($_GET['new_orig_rate_table'])&&empty($_GET['new_term_rate_table'])){
          $rerate_type='orig_rerate';
          }


          #被叫计费
          if(empty($_GET['new_orig_rate_table'])&&!empty($_GET['new_term_rate_table'])){
          $rerate_type='term_rerate';
          }

          #主被叫 都计费
          if(!empty($_GET['new_orig_rate_table'])&&!empty($_GET['new_term_rate_table'])){
          $rerate_type='orig_term_rerate';

          }
         * 
         */
        $rerate_type = $_GET['rerate_type'];
        $this->set('rerate_type', $rerate_type);

        $f_c = "id||';'||origination_source_number||';'||origination_destination_number||';'||call_duration||';'||ingress_client_rate||';'||ingress_client_cost||';'||egress_rate||';'||egress_cost||';'||start_time_of_date||';'||release_tod||';'||ingress_dnis_type||';'||lrn_dnis||';'||routing_digits||';'||time||';'||translation_ani||';'||egress_dnis_type";
        $re_rating_org_sql = !empty($_GET['new_orig_rate_table']) ? "  $process_type($f_c,1,{$_GET['new_orig_rate_table']}) as orig_cost" : '';
        $re_rating_term_sql = !empty($_GET['new_term_rate_table']) ? " $process_type($f_c,2,{$_GET['new_term_rate_table']}) as term_cost" : '';

        $count_sql = "select count(*) as c from   client_cdr $join    where  $where";

        $tmp_sql = "version_number,connection_type,session_id,release_cause,start_time_of_date,answer_time_of_date,release_tod,minutes_west_of_greenwich_mean_time,release_cause_from_protocol_stack,binary_value_of_release_cause_from_protocol_stack,first_release_dialogue,trunk_id_origination,voip_protocol_origination,origination_source_number,origination_source_host_name,origination_destination_number,origination_destination_host_name,origination_call_id,origination_remote_payload_ip_address,origination_remote_payload_udp_address,origination_local_payload_ip_address,origination_local_payload_udp_address,origination_codec_list,origination_ingress_packets,origination_egress_packets,origination_ingress_octets,origination_egress_octets,origination_ingress_packet_loss,origination_ingress_delay,origination_ingress_packet_jitter,trunk_id_termination,voip_protocol_termination,termination_source_number,termination_source_host_name,termination_destination_number,termination_destination_host_name,
termination_call_id,termination_remote_payload_ip_address,termination_remote_payload_udp_address,termination_local_payload_ip_address,termination_local_payload_udp_address,termination_codec_list,termination_ingress_packets,termination_egress_packets,termination_ingress_octets,termination_egress_octets,termination_ingress_packet_loss,termination_ingress_delay,termination_ingress_packet_jitter,final_route_indication,routing_digits,call_duration,pdd,ring_time,callduration_in_ms,conf_id,call_type,ingress_id,ingress_client_id,ingress_client_rate_table_id,ingress_client_currency_id,ingress_client_rate,ingress_client_currency,ingress_client_bill_time,ingress_client_bill_result,ingress_client_cost,egress_id,egress_rate_table_id,egress_rate,egress_cost,egress_bill_time,egress_client_id,egress_client_currency_id,egress_client_currency,egress_six_seconds,egress_bill_minutes,egress_bill_result,ingress_bill_minutes,ingress_dnis_type,ingress_rate_type,lrn_dnis,egress_dnis_type,egress_rate_type,
translation_ani,item_id,ingress_rate_id,egress_rate_id,rerate_time,orig_code,orig_code_name,orig_country,term_code,term_code_name,term_country,ingress_rate_effective_date,egress_rate_effective_date,egress_erro_string,lrn_number_vendor,lnp_dipping_cost,is_final_call,egress_code_asr,egress_code_acd";
        //$org_sql = "select $re_rating_org_sql $re_rating_term_sql 'rerating' as  rerating  from   client_cdr 	$join where   $where  $order  ";
        $org_sql = "select $tmp_sql from  client_cdr 	$join where $where $order  ";

        $rerate_rate_table = !empty($_GET['rerate_rate_table']) ? "{$_GET['rerate_rate_table']}" : '';

        $rerate_time = !empty($_GET['rerate_time']) ? "{$_GET['rerate_time']}" : '';

        //update cdr
        if ($process_type == 'update_cdr')
        {
            //$this->Cdr->query($org_sql);
        }
        return compact('org_sql', 'count_sql', 'rerate_type', 'where', 'rerate_rate_table', 'rerate_time');
    }

    function index()
    {
        $this->redirect('summary_reports');
    }

    function summary_reports($id = null, $cli_id = null)
    {

        $this->select_name($cli_id);

        $this->pageTitle = "Statistics/Spam Report";

        $t = getMicrotime();

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3')
        {
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            if ($cdr_type == 'buy')
            {
                $cdr_type_active = 'term_service_buy';
                $this->pageTitle = "Reports/Term. Service Buy";
            }
            else
            {
                $cdr_type_active = 'term_service_sell';
                $this->pageTitle = "Reports/Term. Service Sell";
            }
            $this->set('cdr_type', $cdr_type_active);
        }


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

        $this->set('show_nodata', true);

//         得到 得数据的 send 命令   
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;
        $show_fields = array();
        $field = array();
        $condition = array();
        $get_data = $this->params['url'];
        if (isset($get_data['start_date']) && !empty($get_data['start_date']))
            $start_date = $get_data['start_date'] . ' ' . $get_data['start_time'];
        if (isset($get_data['stop_date']) && !empty($get_data['stop_date']))
            $end_date = $get_data['stop_date'] . ' ' . $get_data['stop_time'];
        if (isset($get_data['query']['tz']) && !empty($get_data['query']['tz']))
            $gmt = substr($get_data['query']['tz'], 0, 3);
        $start_date .= $gmt;
        $end_date .= $gmt;

        if ($rate_type == 'spam')
        {
            $get_data['cdr_release_cause'] = 3;
        }
        //    quanxian
        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3')
        {
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            $client_id = $_SESSION['carrier_panel']['Client']['client_id'];
            if ($cdr_type == 'buy')
            {
                $get_data['orig_carrier_select'] = $client_id;
            }
            else
            {
                $get_data['term_carrier_select'] = $client_id;
            }
            $this->set('cdr_type', $cdr_type_active);
        }

//        condition ingress_client_id 
        $ingress = "";
        if (isset($get_data['orig_carrier_select']) && !empty($get_data['orig_carrier_select']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where ingress=true AND trunk_type2 = 0 and resource.client_id = {$get_data['orig_carrier_select']}";
            $ingress_result = $this->Cdr->query($sql); //pr($ingress);die;
            $condition[] = "ingress_client_id {$get_data['orig_carrier_select']}";
            $size = count($ingress_result);
            $ingress = array();
            for ($i = 0; $i < $size; $i++)
            {
                $key = $ingress_result [$i] [0] ['resource_id'];
                $ingress [$key] = $ingress_result [$i] [0] ['alias'];
            }
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
        if (isset($get_data['route_prefix']) && $get_data['route_prefix'] != 'all' && $get_data['route_prefix'])
        {
            $condition[] = "route_prefix {$get_data['route_prefix']}";
        }

//condition ingress_country 
        if (isset($get_data['query']['country']) && $get_data['query']['country'])
        {

            $val_kong = str_ireplace(' ', '%20', $get_data['query']['country']);
            $condition[] = "ingress_country {$val_kong}";
        }

//condition INGRESS_CODE_NAME 
        if (isset($get_data['query']['code_name']) && $get_data['query']['code_name'])
        {
            $val_kong = str_ireplace(' ', '%20', $get_data['query']['code_name']);
            $condition[] = "ingress_code_name {$val_kong}";
        }

//condition INGRESS_CODE  
        if (isset($get_data['query']['code']) && $get_data['query']['code'])
        {
            $condition[] = "ingress_code {$get_data['query']['code']}";
        }

//condition RESPONSE_TO_INGRESS  
        if (isset($get_data['query']['res_status_ingress']) && $get_data['query']['res_status_ingress'])
        {
            $condition[] = "response_to_ingress {$get_data['query']['res_status_ingress']}";
        }

//condition release_cause  
        if (isset($get_data['cdr_release_cause']) && $get_data['cdr_release_cause'])
        {
            $condition[] = "release_cause {$get_data['cdr_release_cause']}";
        }

//condition INGRESS_ANI  
        if (isset($get_data['query']['src_number']) && $get_data['query']['src_number'])
        {
            $condition[] = "ingress_ani {$get_data['query']['src_number']}";
        }

//condition INGRESS_DNIS  
        if (isset($get_data['query']['dst_number']) && $get_data['query']['dst_number'])
        {
            $condition[] = "ingress_dnis {$get_data['query']['dst_number']}";
        }



//  condition egress_client_id 
        $egress = "";
        if (isset($get_data['term_carrier_select']) && !empty($get_data['term_carrier_select']))
        {
            $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
                    . " where egress=true AND trunk_type = 0 and resource.client_id = {$get_data['term_carrier_select']}";
            $egress_result = $this->Cdr->query($sql);
            $condition[] = "egress_client_id {$get_data['term_carrier_select']}";
            $size = count($egress_result);
            $ingress = array();
            for ($i = 0; $i < $size; $i++)
            {
                $key = $egress_result [$i] [0] ['resource_id'];
                $egress [$key] = $egress_result [$i] [0] ['alias'];
            }
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
            $val_kong = str_ireplace(' ', '%20', $get_data['query']['country_term']);
            $condition[] = "egress_country {$val_kong}";
        }

//condition EGRESS_CODE_NAME 
        if (isset($get_data['query']['code_name_term']) && $get_data['query']['code_name_term'])
        {
            $val_kong = str_ireplace(' ', '%20', $get_data['query']['code_name_term']);
            $condition[] = "egress_code_name {$val_kong}";
        }

//condition EGRESS_CODE  
        if (isset($get_data['query']['code_term']) && $get_data['query']['code_term'])
        {
            $condition[] = "egress_code {$get_data['query']['code_term']}";
        }

//condition response_to_egress  
        if (isset($get_data['query']['res_status']) && $get_data['query']['res_status'])
        {
            $condition[] = "response_to_egress {$get_data['query']['res_status']}";
        }


//condition EGRESS_ANI  
        if (isset($get_data['query']['term_src_number']) && $get_data['query']['term_src_number'])
        {
            $condition[] = "egress_ani {$get_data['query']['term_src_number']}";
        }

//condition EGRESS_DNIS  
        if (isset($get_data['query']['term_dst_number']) && $get_data['query']['term_dst_number'])
        {
            $condition[] = "egress_dnis {$get_data['query']['term_dst_number']}";
        }

        if (isset($get_data ['query'] ['duration']))
        {
            $duration = $get_data ['query'] ['duration'];
            if (!empty($duration))
            {
                if ($duration == 'nonzero')
                { //call_duration
                    // $duration_where = " and case  when call_duration is null then 0  else call_duration end>0";
                    $condition[] = "  INGRESS_DURATION 1  ";
                }
                if ($duration == 'zero')
                {
                    //$duration_where = " and case  when call_duration is null then 0  else call_duration end=0";
                    $condition[] = "  INGRESS_DURATION 0  ";
                }
            }
        }

        if (isset($get_data ['query'] ['cost']))
        {
            $duration = $get_data ['query'] ['cost'];
            if (!empty($duration))
            {
                if ($duration == 'nonzero')
                { //call_duration
                    // $duration_where = " and case  when call_duration is null then 0  else call_duration end>0";
                    $condition[] = "  EGRESS_COST_MIN 0  EGRESS_COST_MAX 10000 ";
                }
                if ($duration == 'zero')
                {
                    //$duration_where = " and case  when call_duration is null then 0  else call_duration end=0";
                    $condition[] = "  EGRESS_COST_MIN -1  EGRESS_COST_MAX 0.000001  ";
                }
            }
        }

        if (isset($get_data ['query'] ['interval_from']) && isset($get_data ['query'] ['interval_to']))
        {
            $interval_from = $get_data ['query'] ['interval_from'];
            $interval_to = $get_data ['query'] ['interval_to'];
            
            if(empty($interval_from)){
                $interval_from = 0;
            }
            if(!empty($interval_to)){
                $condition[] = "  INGRESS_DURATION_INTERVAL_MIN {$interval_from}  INGRESS_DURATION_INTERVAL_MAX  {$interval_to} ";
            }
        }
        
         /*if (isset($get_data ['query'] ['origination_source_host_name'])) {
            $duration = $get_data ['query'] ['origination_source_host_name'];
             if (!empty($duration)) {
                  $condition[] = "  INGRESS_PROFILE_IP {$duration}   ";
             }
        }*/
        

        $cdr_arr = array(
            'connection_type', 'session_id', 'release_cause', 'start_time_of_date',
            'answer_time_of_date', 'release_tod', 'minutes_west_of_greenwich_mean_time',
            'release_cause_from_protocol_stack',
            'binary_value_of_release_cause_from_protocol_stack',
            'first_release_dialogue',
            'trunk_id_origination',
            'voip_protocol_origination',
            'origination_source_number',
            'origination_source_host_name',
            'origination_destination_number',
            'origination_destination_host_name',
            'origination_call_id',
            'origination_remote_payload_ip_address',
            'origination_remote_payload_udp_address',
            'origination_local_payload_ip_address',
            'origination_local_payload_udp_address',
            'origination_codec_list',
            'origination_ingress_packets',
            'origination_egress_packets',
            'origination_ingress_octets',
            'origination_egress_octets',
            'origination_ingress_packet_loss',
            'origination_ingress_delay',
            'origination_ingress_packet_jitter',
            'trunk_id_termination',
            'voip_protocol_termination',
            'termination_source_number',
            'termination_source_host_name',
            'termination_destination_number',
            'termination_destination_host_name',
            'termination_call_id',
            'termination_remote_payload_ip_address',
            'termination_remote_payload_udp_address',
            'termination_local_payload_ip_address',
            'termination_local_payload_udp_address',
            'termination_codec_list',
            'termination_ingress_packets',
            'termination_egress_packets',
            'termination_ingress_octets',
            'termination_egress_octets',
            'termination_ingress_packet_loss',
            'termination_ingress_delay',
            'termination_ingress_packet_jitter',
            'final_route_indication',
            'routing_digits',
            'call_duration',
            'pdd',
            'ring_time',
            'callduration_in_ms',
            'conf_id',
            'call_type',
            'ingress_id',
            'ingress_client_id',
            'ingress_client_rate_table_id',
            'ingress_client_currency_id',
            'ingress_client_rate',
            'ingress_client_currency',
            'ingress_client_bill_time',
            'ingress_client_bill_result',
            'ingress_client_cost',
            'egress_id',
            'egress_rate_table_id',
            'egress_rate',
            'egress_cost',
            'egress_bill_time',
            'egress_client_id',
            'egress_client_currency_id',
            'egress_client_currency',
            'egress_six_seconds',
            'egress_bill_minutes',
            'egress_bill_result',
            'ingress_bill_minutes',
            'ingress_dnis_type',
            'ingress_rate_type',
            'lrn_number_vendor',
            'lrn_dnis',
            'egress_dnis_type',
            'egress_rate_type',
            'item_id',
            'translation_ani',
            'ingress_rate_id',
            'egress_rate_id',
            'orig_code',
            'orig_code_name',
            'orig_country',
            'term_code',
            'term_code_name',
            'term_country',
            'ingress_rate_effective_date',
            'egress_rate_effective_date',
            'egress_erro_string',
            'order_id',
            'order_type',
            'lnp_dipping_cost',
            'is_final_call',
            'egress_code_asr',
            'egress_code_acd',
            'static_route',
            'dynamic_route',
            'route_plan',
            'route_prefix',
            'orig_delay_second',
            'term_delay_second',
            'orig_call_duration',
            'trunk_type',
            'origination_profile_port',
            'termination_profile_port',
            'o_trunk_type2',
            'o_billing_method',
            't_trunk_type2',
            't_billing_method',
            'campaign_id',
            'tax',
            'agent_id',
            'agent_rate',
            'agent_cost',
            'orig_jur_type',
            'term_jur_type',
            'ring_epoch',
            'end_epoch',
            'paid_user',
            'rpid_user',
            'timeout_type',
        );


        $field_arr = isset($this->params['url']['query']['fields']) ? $this->params['url']['query']['fields'] : array();

        foreach ($field_arr as $key => $value)
        {
            if (!strcmp('time', $value))
            {
                $field_arr[$key] = 'release_tod';
            }
        }

        $start_qie = date("Y-m-d H:i:sO",strtotime($start_date));
        $end_qie = date("Y-m-d H:i:sO",strtotime($end_date));
        //$send = "GET DATA cdr BEGIN {$start_date} END {$end_date}";
        $send = "GET DATA cdr BEGIN {$start_qie} END {$end_qie}";
        
        if ($condition)
        {
            $send .= " CONDITION " . implode(' ', $condition) . " END ";
        }

        if ($field_arr)
        {
            $send .= " FIELD " . implode(' ', $field_arr) . " END ";
        }

        $this->get_cdrreport_data($send, $start_date, $end_date);

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        //  $this->set('name',$this->queryName($cli_id));
        //  $this->set('cdrr_name',$this->Cdr->query("select name from client where client_id=".$this->params['pass'][1] .""));
    }

    public function get_file($file)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $file_path = base64_decode($file);
        $basename = basename($file_path);
        header("Content-type: application/x-tar");
        header("Content-Disposition: attachment; filename={$basename}");
        header("Content-Description: CDR Record");
        readfile($file_path);
    }

    public function get_export_file($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::load('myconf');
        $sql = "select filename from mail_cdr_log_detail where id = {$id}";
        $result = $this->Cdr->query($sql);
        $export_filename = $result[0][0]['filename'];
        $path = Configure::read('export_cdr.path');

        $file = $path . DS . $export_filename;

        $filename = basename($file);

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        $x_sendfile_supported = in_array('mod_xsendfile', apache_get_modules());

        if (!headers_sent() && $x_sendfile_supported)
        {
            //让Xsendfile发送文件
            header("X-Sendfile: $file");
        }
        else
        {
            @readfile($file);
        }
    }

    public function connect_back_sender($where, $fields, $show_field_array, $address)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $user_id = $_SESSION['sst_user_id'];
        //$user = $_SESSION['sst_user_name'];
        $email = $address;
        $field_names = implode(',', $show_field_array);

        $script_path = Configure::read('script.path');


        $script_file = $script_path . DS . 'class4_cdr_down.pl';
        $script_log = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_log} -t \"{$email}\" -i {$user_id} -s \"{$where}\" -d \"{$fields}\" -l \"{$field_names}\" -m '{$_GET ['start_date']} {$_GET ['start_time']}' -n '{$_GET ['stop_date']} {$_GET ['stop_time']}' -p '{$_GET ['query']['tz']}' >/dev/null 2 >&1  & echo $!";

        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));

        if (Configure::read('cmd.debug'))
        {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }
        $pid = shell_exec($cmd);
        $pid = intval($pid);
        $this->Cdr->create_json_array('#query-smartPeriod', 201, __("The Backend Job with id # %s is created. A email will be sent to %s when it is the file is ready!", true, array($pid, $address)));
        $this->Session->write("m", Cdr::set_validator());
        $this->redirect('/cdrreports/summary_reports');
    }

    public function re_sendmail()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = $_POST['id'];
        $email = $_POST['email'];
        $result = $this->Cdr->query("select mail_subject, mail_content from mail_cdr_log where id = {$id}");

        if (strpos($email, ',') !== false)
        {
            $email_adress = explode(',', $email);
        }
        else if (strpos($email, ';') !== false)
        {
            $email_adress = explode(';', $email);
        }
        else
        {
            $email_adress = array($email);
        }

        $subject = $result[0][0]['mail_subject'];
        $content = $result[0][0]['mail_content'];

        $email_info = $this->Cdr->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false')
        {
            $mailer->IsMail();
        }
        else
        {
            $mailer->IsSMTP();
        }
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        switch ($email_info[0][0]['smtp_secure'])
        {
            case 1:
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        $mailer->IsHTML(true);
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        foreach ($email_adress as $send_address)
        {
            $send_address = trim($send_address);
            $mailer->AddAddress($send_address);
        }
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $mailer->Send();
        echo json_encode(array('status' => 1));
    }

    public function delete_email_log($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $this->Cdr->query("delete from mail_cdr_log_detail where mail_cdr_log_id = {$id}; delete from mail_cdr_log where id = {$id};");

        $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Export Log #[%s] is deleted successfully!', true, $id));
        $this->Session->write("m", Cdr::set_validator());
        $this->redirect('/cdrreports/mail_send_log');
    }

    public function mail_send_log()
    {
        $user_id = $_SESSION['sst_user_id'];
        $where = '';
        if ($_SESSION ['login_type'] == 3)
        {
            $where = "WHERE user_id = {$user_id}";
            $this->pageTitle = "Reports/CDR Export Log";
        }
        else
        {
            $this->pageTitle = "Statistics/CDR Export Log";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $count = $this->Cdr->get_cdr_export_log_count($where);
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Cdr->get_cdr_export_log($pageSize, $offset, $where);

        foreach ($data as &$item)
        {
            $item[0]['details'] = $this->Cdr->get_cdr_export_log_detail($item[0]['id']);
        }

        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('pageSize', $pageSize);
        $this->set('offset', $offset);
    }

    public function refresh_mail_send_log()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $user_id = $_SESSION['sst_user_id'];
        $where = '';
        if ($_SESSION ['login_type'] == 3)
        {
            $where = "WHERE user_id = {$user_id}";
        }
        $pageSize = $_POST['pageSize'];
        $offset = $_POST['offset'];
        $data = $this->Cdr->get_cdr_export_log($pageSize, $offset, $where);
        echo json_encode($data);
    }

    public function down_mail_cdr_export_file($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "SELECT file_path FROM mail_cdr_log WHERE id = {$id}";
        $result = $this->Cdr->query($sql);
        $file = $result[0][0]['file_path'];
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }

    function process_rerate($org_sql, $rerate_type, $where, $rerate_rate_table, $rerate_time)
    {
        if (!$_SESSION['role_menu']['Tools']['cdrreports:rerating']['model_x'])
        {
            $this->redirect_denied();
        }
        $this->Cdr->query("DELETE FROM client_cdr".date("Ymd")." WHERE {$where}");
        $uniq_name = uniqid('rerating');
        $dest_path = Configure::read('database_actual_export_path') . "/{$uniq_name}.csv";
        $dest_actual_path = Configure::read('database_export_path') . "/{$uniq_name}.csv";
        $generate_csv_sql = "COPY({$org_sql}) TO '{$dest_path}' WITH HEADER DELIMITER AS '?' CSV";
        $bin_path = Configure::read('rerating.bin');
        $conf_path = Configure::read('rerating.conf');
        $cmd = "{$bin_path} -d {$conf_path} -t {$rerate_type} -f {$dest_actual_path} {$rerate_time} {$rerate_rate_table}  2>&1";

        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));

        if (Configure::read('cmd.debug'))
        {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }

        shell_exec($cmd);
        $this->Cdr->create_json_array('#query-smartPeriod', 201, __('Succeed!', true));
        $this->Session->write("m", Cdr::set_validator());
    }

    public function save_rerate($rerate_type, $rerate_rate_time, $rate_table_id, $where_condition)
    {
        $rerate_rate_time = empty($rerate_rate_time) ? 'default' : "'{$rerate_rate_time}'";
        $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
        $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        if ($rerate_type == 3)
        {
            $sql = "INSERT INTO cdr_rerate (rerate_type, rerate_rate_time, rate_table_id, rate_table_name, start_time, end_time,where_condition)
                VALUES(1, {$rerate_rate_time}, $rate_table_id, (SELECT name FROM rate_table WHERE rate_table_id = {$rate_table_id}), '{$start}', '{$end}', " . '$$' . $where_condition . '$$' . ")";
            $this->Cdr->query($sql);
            $sql = "INSERT INTO cdr_rerate (rerate_type, rerate_rate_time, rate_table_id, rate_table_name, start_time, end_time,where_condition)
                VALUES(2, {$rerate_rate_time}, $rate_table_id, (SELECT name FROM rate_table WHERE rate_table_id = {$rate_table_id}), '{$start}', '{$end}', " . '$$' . $where_condition . '$$' . ")";
            $this->Cdr->query($sql);
        }
        else
        {
            $sql = "INSERT INTO cdr_rerate (rerate_type, rerate_rate_time, rate_table_id, rate_table_name, start_time, end_time,where_condition)
            VALUES($rerate_type, {$rerate_rate_time}, $rate_table_id, (SELECT name FROM rate_table WHERE rate_table_id = {$rate_table_id}), '{$start}', '{$end}', " . '$$' . $where_condition . '$$' . ")";
            $this->Cdr->query($sql);
        }
    }

    function rerating($id = null, $cli_id = null)
    {
        //	Configure::write('debug',0);
        //	$this->_session_get(isset ( $_GET ['searchkey'] ));
        $this->select_name($cli_id);
        $this->pageTitle = "Statistics/Spam Report ";

        $t = getMicrotime();
        $report_type = "cdr_search";
        $this->init_query();
        extract($this->Cdr->get_real_period());
        $this->set("report_type", $report_type);


        $action_type = isset($_GET['action_type']) ? $_GET['action_type'] : 'query';

        switch ($action_type)
        {
            case 'Rerating':extract($this->get_rerate_cdrdatas($report_type, 'rerate_cdr'));
                break;
            case 'Process':extract($this->get_rerate_cdrdatas($report_type, 'update_cdr'));
                break;
            case 'query':extract($this->get_datas($report_type, ''));
                break;
            default : extract($this->get_datas($report_type));
                break;
        }


        //echo $count_sql;   


        if ($action_type == 'Process')
        {



            //$this->process_rerate($org_sql,$rerate_type, $where, $rerate_rate_table, $rerate_time);
            $this->save_rerate($rerate_type, $rerate_time, $rerate_rate_table, $where);
            $str = $this->get_rerate_field();
            extract($this->get_datas($report_type, $str));
            $this->Cdr->create_json_array('#query-smartPeriod', 201, __('Succeed!', true));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect("/cdrreports/rerating_list");
        }
        $this->set('sip_capture_status', 'off');

        $this->set("action_type", $action_type);

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;


        require_once 'MyPage.php';
        $page = new MyPage ();
        if (isset($_GET ['query'] ['output']))
        {
            $totalrecords = $this->Cdr->query($count_sql);
            $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        }
        else
        {
            $page->setTotalRecords(0);
        }
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
            //下载
            extract($this->get_start_end_time());
            $file_name = $this->create_doload_file_name('cdr', $start, $end);
            if ($_GET ['query'] ['output'] == 'csv')
            {
                Configure::write('debug', 0);
                $this->layout = 'csv';
                $this->_catch_exception_msg(array('CdrreportsController', '_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                Configure::write('debug', 0);
                $this->layout = 'csv';
                $this->_catch_exception_msg(array('CdrreportsController', '_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'delayed')
            {
                //delayed csv
            }
            else
            {
                //web显示
                $results = $this->Cdr->query($org_page_sql);
                $page->setDataArray($results);
                $this->set('p', $page);
            }
        }
        else
        {
            /*
              $results = $this->Cdr->query ($org_page_sql );
              $page->setDataArray($results);
              $this->set('p',$page);
             * 
             */
            $this->set('p', $page);
        }

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        //  $this->set('name',$this->queryName($cli_id));
        //  $this->set('cdrr_name',$this->Cdr->query("select name from client where client_id=".$this->params['pass'][1] .""));
    }

    function _download_impl($params = array())
    {
        Configure::write('debug', 0);
        extract($params);
        $job_id = $this->Cdr->download_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name, 'start' => $start, 'end' => $end));
        if ($job_id)
        {
            //exit(1);
            $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Log[#%s] of exports of CDRs between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/cdrreports/export_log');
        }
        else
        {
            $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/cdrreports/summary_reports');
        }
    }

    function export_log()
    {

        $conditions = array(
            'is_dipp !=' => true
        );

        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        if (isset($get_data['time_start']) && $get_data['time_start'])
        {
            $conditions[] = "export_time >= '" . $get_data['time_start'] . "'";
        }

        if (isset($get_data['time_end']) && $get_data['time_end'])
        {
            $conditions[] = "export_time <= '" . $get_data['time_end'] . "'";
        }


        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CdrExportLog.id' => 'desc',
            ),
            'conditions' => $conditions
        );
        if (isset($_SESSION['sst_client_id']))
        {
            $this->paginate['conditions']['user_id'] = $_SESSION['sst_client_id'];
        }
        $status = array("Waiting", "In Progress", "Query", "Compress", "Done", "Canceled");
        $this->set('status', $status);
        $this->data = $this->paginate('CdrExportLog');
    }

    public function export_log_kill()
    {
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $log = $this->CdrExportLog->findById($id);
        $backend_pid = (int) $log['CdrExportLog']['backend_pid'];
        if ($backend_pid > 0)
        {
            $sql = "SELECT pg_terminate_backend({$backend_pid})";
            $this->CdrExportLog->query($sql);
        }
        $log['CdrExportLog']['status'] = 5;
        $this->CdrExportLog->save($log);
        $this->CdrExportLog->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] is canceled successfully!', true, $id));
        $this->Session->write("m", CdrExportLog::set_validator());
        $this->redirect('/cdrreports/export_log');
    }

    public function export_log_files()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $id = base64_decode($_POST['key']);
        $log = $this->CdrExportLog->findById($id);
        $file_path = $log['CdrExportLog']['file_dir'];

        $files = scandir($file_path);
        $this->set("files", $files);
        $this->set("key", $_POST['key']);
    }

    public function export_log_down_file()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        #$key = urldecode($_GET['key']);
        #App::import('Helper', 'AppCommon'); 
        #$appCommon = new AppCommonHelper();
        #$id = $appCommon->dencrypt($key);
        $id = base64_decode($_GET['key']);

        $file_name = trim($_GET['file']);

        $log = $this->CdrExportLog->findById($id);
        //$file_path = $log['CdrExportLog']['file_path'];
        $dir_path = $log['CdrExportLog']['file_dir'];

        $file_path = $dir_path . DIRECTORY_SEPARATOR . $file_name;

        $filename = basename(($file_path));

        ob_clean();
        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        if (in_array('mod_xsendfile', apache_get_modules()))
        {
            //让Xsendfile发送文件
            header("X-Sendfile: $file_path");
        }
        else
        {
            readfile($file_path);
        }
    }

    public function export_log_down()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        #$key = urldecode($_GET['key']);
        #App::import('Helper', 'AppCommon'); 
        #$appCommon = new AppCommonHelper();
        #$id = $appCommon->dencrypt($key);
        $id = base64_decode($_GET['key']);
        $log = $this->CdrExportLog->findById($id);
        $file_path = $log['CdrExportLog']['file_path'];
        $filename = basename(($file_path));

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        if (in_array('mod_xsendfile', apache_get_modules()))
        {
            //让Xsendfile发送文件
            header("X-Sendfile: $file_path");
        }
        else
        {
            readfile($file_path);
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

    public function get_ingress_host_by_client_id()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = $_POST['client_id'];
        $sql = "SELECT resource_ip.ip FROM resource_ip 
INNER JOIN resource ON resource_ip.resource_id = resource.resource_id WHERE resource.client_id = $client_id AND resource.ingress = true";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get_egress_host_by_client_id()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = $_POST['client_id'];
        $sql = "SELECT resource_ip.ip FROM resource_ip 
INNER JOIN resource ON resource_ip.resource_id = resource.resource_id WHERE resource.client_id = $client_id AND resource.egress = true";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get_ingress_host_by_ingress_id()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ingress_id = $_POST['ingress_id'];
        $sql = "SELECT ip FROM resource_ip WHERE resource_id = $ingress_id";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get__ingress_rate_table_by_client()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = $_POST['client_id'];
        $sql = "SELECT resource_prefix.rate_table_id,rate_table.name  FROM resource_prefix 
LEFT JOIN rate_table ON rate_table.rate_table_id = resource_prefix.rate_table_id  INNER JOIN resource 
ON resource.resource_id = resource_prefix.resource_id 
WHERE  resource.ingress = true AND resource.client_id ={$client_id}";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get__egress_rate_table_by_client()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = $_POST['client_id'];
        $sql = "SELECT resource.rate_table_id, rate_table.name FROM resource LEFT JOIN rate_table ON resource.rate_table_id = rate_table.rate_table_id
WHERE resource.egress = true AND resource.client_id = $client_id";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get__ingress_rate_table_by_ingress()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ingress_id = $_POST['ingress_id'];
        $sql = "SELECT resource_prefix.rate_table_id,rate_table.name  FROM resource_prefix 
LEFT JOIN rate_table ON rate_table.rate_table_id = resource_prefix.rate_table_id 
WHERE resource_prefix.resource_id = $ingress_id";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function get__egress_rate_table_by_egress()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $egress_id = $_POST['egress_id'];
        $sql = "SELECT resource.rate_table_id, rate_table.name FROM resource LEFT JOIN rate_table ON resource.rate_table_id = rate_table.rate_table_id
WHERE resource.egress = true AND resource.resource_id = $egress_id";
        $data = $this->Cdr->query($sql);
        echo json_encode($data);
    }

    public function rerating_list()
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $sql_count = "SELECT count(*) FROM cdr_rerate";
        $counts = $this->Cdr->query($sql_count);
        $page = new MyPage ();
        $page->setTotalRecords($counts[0][0]['count']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "SELECT * FROM cdr_rerate ORDER BY id DESC LIMIT {$pageSize} OFFSET {$offset}";
        $data = $this->Cdr->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);
        $status = array(
            '0' => 'waiting',
            '1' => 'complete',
            '2' => 'processing',
            '3' => 'backup cdr',
            '4' => 'delete cdr',
            '5' => 'relating',
            '6' => 'redo report',
            '-1' => 'rerate exec file error',
            '-2' => 'rerate exec conf error',
            '-3' => 'rerate cdr backup conf error',
            '-4' => 'open cdr backup file error',
            '-5' => 'cdr backup error',
            '-6' => 'delete cdr error',
            '-7' => 'rerate exec error',
            '-8' => 'killed'
        );
        $this->set('status', $status);
    }

    public function kill_rerating($pid = '', $id = '')
    {
        if (!$pid || !$id)
        {
            $this->Session->write('m', $this->Cdrrerate->create_json(101, 'The job is stopped failed!'));
            $this->redirect('/cdrreports/rerating_list');
        }
        $cmd = "kill -9 {$pid}";
        $out = shell_exec($cmd);

        if ($out)
        {
            $this->Session->write('m', $this->Cdrrerate->create_json(101, 'The job is stopped failed!'));
            $this->redirect('/cdrreports/rerating_list');
        }

        $sql = "update cdr_rerate set status = '-8' where id = '{$id}'";

        $this->Cdrrerate->query($sql);

        $affectnum = $this->Cdrrerate->getAffectedRows();
        if (!$affectnum)
        {
            $this->Session->write('m', $this->Cdrrerate->create_json(101, 'The status of this record has not been modified!'));
            $this->redirect('/cdrreports/rerating_list');
        }

        $this->Session->write('m', $this->Cdrrerate->create_json(201, 'Succeed!'));
        $this->redirect('/cdrreports/rerating_list');
    }

    /**
     * 
     * 
     * Ajax get   spam  report data
     * @param unknown_type $id
     * @param unknown_type $cli_id
     */
    function spam_ajax_data($id = null, $cli_id = null)
    {
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $this->select_name($cli_id);
        $this->pageTitle = "Statistics/Spam Report ";
        $report_type = "spam_report";
        $t = getMicrotime();
        $this->set('rate_type', 'spam');
        $this->set("report_type", $report_type);

        $this->init_query();
        extract($this->Cdr->get_real_period());

        extract($this->get_datas($report_type, ''));






        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->Cdr->query($count_sql);
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
        $results = $this->Cdr->query($org_page_sql);
        $page->setDataArray($results);
        $this->set('p', $page);
    }

    function sql_field_array_help($arr)
    {
        $release_cause = "case  release_cause
	 	
	 	when    0    then   'Unknown Exception'
                when    1     then   'System CPS Limit Exceeded'
                when    2     then   'SYSTEM_CPS System Limit Exceeded'
                when    3     then   'Unauthorized IP Address'
                when    4     then   ' No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit Call Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  CAP Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when   10    then   'Invalid Codec Negotiation'
		when   11    then   'Block due to LRN'
		when   12 			then  'Ingress Rate Not Found'  
		when   13 			then  ' Egress Trunk Not Found'  
		when   14 			then  'Egress Returns 404'  
		when   15 			then  'Egress Returns 486'  
		when   16 			then  'Egress Returns 487'  
		when   17 			then  'Egress Returns 200'  
		when   18 			then  'All Egress Unavailable'  
		when   19 			then  'Normal hang up' 
		when   20 			then  'Ingress Resource disabled'   
		when   21 			then  'Zero Balance'   
		when   22 			then  'No Route Found'   
		when   23 			then  'Invalid Prefix'   
		when   24 			then  'Ingress Rate Missing'
		when   25                     then 'Invalid Codec Negotiation'
		when   26                     then 'No Codec Found'
		when   27                     then 'All Egress Failed'
		when   28                     then 'LRN Response Missing'
		when   29    then 'Carrier Call Limit Exceeded'
		when   30    then 'Carrier CPS Limit Exceeded'
		when   31   then 'Rejected Due to Host Alert'
		when   32   then 'Rejected Due to Trunk Alert'
		when   33   then 'H323 Not Supported'
		when   34   then '180 Negotiation Failure'
		when   35   then '183 Negotiation Failute'
		when   36  then '200 Negotiation Failure'
		when   37  then 'Block LRN with Higher Rate'
                when   38 then 'Ingress Block ANI'
                when   39 then 'Ingress Block DNIS'
                when   40 then 'Ingress Block ALL'
                when   41 then 'Global Block ANI'
                when   42 then 'Global Block DNIS'
                when   43 then 'Global Block ALL'
                when   44 then 'T38 Reject'
		else    'other'  end  as
		release_cause";
        $t_arr = array();
        foreach ($arr as $key => $value)
        {
            $t_arr[$key] = $value;
            if ($value == 'start_time_of_date')
            {
                $t_arr[$key] = "to_timestamp(start_time_of_date/1000000) as start_time_of_date";
            }
            if ($value == 'answer_time_of_date')
            {
                $t_arr[$key] = "case answer_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end as answer_time_of_date";
            }

            if ($value == 'trunk_type')
            {
                $t_arr[$key] = "case trunk_type when 1 then 'class4' when 2 then 'exchange' end as trunk_type";
            }

            if ($value == 'release_tod')
            {
                $t_arr[$key] = "to_timestamp(release_tod/1000000) as release_tod";
            }

            if ($value == 'binary_value_of_release_cause_from_protocol_stack')
            {
                $t_arr[$key] = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";
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

                $t_arr[$key] = "(select name from rate_table where rate_table_id = client_cdr.ingress_client_rate_table_id and client_cdr.ingress_client_rate_table_id is not null) as  ingress_client_rate_table_id";
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

            if ($value == "route_plan")
            {
                $t_arr[$key] = "(select name from route_strategy where route_strategy_id =  client_cdr.route_plan) as route_plan";
            }
            if ($value == "dynamic_route")
            {
                $t_arr[$key] = "(select name from dynamic_route where dynamic_route_id =  client_cdr.dynamic_route) as dynamic_route";
            }
            if ($value == "static_route")
            {
                $t_arr[$key] = "(select name from product where product_id = client_cdr.static_route) as static_route";
            }

//          if($value=="ingress_dnis_type") {
//              $t_arr[$key] = "case ingress_dnis_type when '0' then 'dnis' when '1' then 'lrn' when '2' then 'lrn block' end as ingress_dnis_type";
//          }
            if ($value == 'lrn_number_vendor')
            {
                $t_arr[$key] = "case lrn_number_vendor when 1 then 'client' when 2 then 'lrn server' when 3 then 'cache' when 0 then 'dnis' else 'others' end as lrn_number_vendor";
            }
            if ($value == 'release_cause')
            {
                $t_arr[$key] = $release_cause;
            }

            if ($value == 'ingress_rate_type')
            {
                $t_arr[$key] = "case ingress_rate_type when 1 then 'inter' when 2 then 'intra' when 4 then 'error' when 5 then 'local' else 'others' end as ingress_rate_type";
            }
            if ($value == 'egress_rate_type')
            {
                $t_arr[$key] = "case egress_rate_type when 1 then 'inter' when 2 then 'intra'  when 4 then 'error' when 5 then 'local' else 'others' end as egress_rate_type";
            }


            if (isset($_GET['currency']) && !empty($_GET['currency']))
            {
                $sql = "SELECT rate FROM currency_updates WHERE currency_id = {$_GET['currency']}";
                $cur_info = $this->Cdr->query($sql);
                $rate = $cur_info[0][0]['rate'];
                if ($value == 'egress_cost')
                {
                    $t_arr[$key] = "round(egress_cost / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.egress_client_currency_id) * {$rate}, 5) as egress_cost";
                }
                if ($value == 'egress_rate')
                {
                    $t_arr[$key] = "round(egress_rate / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.egress_client_currency_id) * {$rate}, 5) as egress_rate";
                }
                if ($value == 'ingress_client_cost')
                {
                    $t_arr[$key] = "round(ingress_client_cost / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.ingress_client_currency_id) * {$rate}, 5) as ingress_client_cost";
                }
                if ($value == 'ingress_client_rate')
                {
                    $t_arr[$key] = "round(ingress_client_rate / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.ingress_client_currency_id) * {$rate}, 5) as ingress_client_rate";
                }
            }
        }
        return $t_arr;
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

    public function cdr_capture1($cdr_id)
    {
        $monitor_path = Configure::read('call_monitor.path');
        //$perl = APP.'vendors/shells/sip_scenario.pl';
        //$sip_path = Configure::read('vendortest.sip_capture');
        $query = "SELECT start_time_of_date,time,origination_call_id,termination_call_id , to_char(to_timestamp(substring(start_time_of_date from 1 for 10) ::bigint), 'YYYY/MM/DD/HH24/MI') as time FROM client_cdr".date("Ymd")." WHERE id = {$cdr_id};";
        $result = $this->Cdr->query($query);
        $datepath = $result[0][0]['time'];
        $real_path = $monitor_path . DS . 'sip_capture' . DS . $datepath;
        $include_text = $result[0][0]['start_time_of_date'] . '-*-' . $result[0][0]['origination_call_id'];
        $files = $this->searchfile($real_path, $include_text);
        if (count($files))
        {
            $file = array_shift($files);
            $source_file = "{$real_path}/{$file}";
            /*
              $cmd = "{$perl} {$source_file}";
              $basename = basename($source_file, '.pcap');
              $drawfile = WWW_ROOT . "upload/" . $basename . ".html";
              if(!file_exists($drawfile)) {
              shell_exec("cd ".WWW_ROOT . "upload/;" . $cmd);
              }
              $this->set('drawfile', $drawfile);
             */
            $cmd = "tcpdump -v -r {$source_file}";
            $data = shell_exec($cmd);
            $this->set('drawfile', $data);
        }
        else
        {
            $this->set('drawfile', "");
        }
    }

    public function cdr_capture($cdr_id, $type)
    {
        //date_default_timezone_set("Etc/GMT");
        //$monitor_path = Configure::read('call_monitor.web_path');
        $perl = APP . 'vendors/shells/sip_scenario.pl';
        $query = "SELECT origination_call_id, termination_call_id , case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end as time,origination_source_host_name, origination_profile_port  FROM client_cdr".date("Ymd")." WHERE id = {$cdr_id};";
        $result = $this->Cdr->query($query);
        $time = $result[0][0]['time'];
        $date_path = date("Y/m/d/H/", strtotime($time));
        $sql = "select sip_capture_path from switch_profile where sip_ip = '{$result[0][0]['origination_source_host_name']}' and sip_port = {$result[0][0]['origination_profile_port']}";
        $switch_profiler_result = $this->Cdr->query($sql);
        $monitor_path = $switch_profiler_result[0][0]['sip_capture_path'];

        $real_path = $monitor_path . DS . 'sip_capture' . DS . $date_path;
        //echo $real_path;
        if (file_exists($real_path))
        {
            if ($type == 'ingress')
                $include_text = '*' . $result[0][0]['origination_call_id'] . "*";
            else
                $include_text = '*' . $result[0][0]['termination_call_id'] . "*";
            $file = $this->searchfile($real_path, $include_text);
            if ($file)
            {
                $source_file = trim($file);
                $cmd = "{$perl} {$source_file}";
                $basename = basename($source_file, '.pcap');
                $drawfile = WWW_ROOT . "upload/pcap/" . $basename . ".html";
                if (!file_exists($drawfile))
                {
                    shell_exec("cd " . WWW_ROOT . "upload/pcap/;" . $cmd);
                }
                $this->set('drawfile', $drawfile);
            }
            else
            {
                $this->set('drawfile', "");
            }
        }
        else
        {
            $this->set('drawfile', "");
        }
    }

    function down_rtpwav($cdr_id, $type)
    {
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $query = "SELECT origination_call_id, termination_call_id , case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end as time,origination_source_host_name, origination_profile_port  FROM client_cdr".date("Ymd")." WHERE id = {$cdr_id};";
        $result = $this->Cdr->query($query);
        $time = $result[0][0]['time'];
        $date_path = date("Y/m/d/H/", strtotime($time));
        $sql = "select sip_capture_path from switch_profile where sip_ip = '{$result[0][0]['origination_source_host_name']}' and sip_port = {$result[0][0]['origination_profile_port']}";
        $switch_profiler_result = $this->Cdr->query($sql);
        $monitor_path = $switch_profiler_result[0][0]['sip_capture_path'];

        $real_path = $monitor_path . DS . 'sip_capture' . DS . $date_path;
        $real_rtp_path = $monitor_path . DS . 'rtp_capture' . DS . $date_path;
        if (file_exists($real_path))
        {
            if ($type == 'ingress')
                $include_text = '*' . $result[0][0]['origination_call_id'] . "*";
            else
                $include_text = '*' . $result[0][0]['termination_call_id'] . "*";
            $file = $this->searchfile($real_path, $include_text);
            if ($file)
            {

                $file_path_info = pathinfo(trim($file));
                $include_text = $file_path_info['filename'] . "*";

                $file = $this->searchfile($real_rtp_path, $include_text);

                if ($file)
                {
                    $file = trim($file);
                    $unique_file = WWW_ROOT . 'upload/rtp_wav/' . uniqid('wav');
                    $dest_wav_file = $unique_file . '-media-1.wav';

                    $videosnarf_bin = Configure::read('call_monitor.videosnarf');

                    $cmd = "{$videosnarf_bin} -i '$file' -o '{$unique_file}' -f 'dst host {$result[0][0]['origination_source_host_name']}'";
                    shell_exec($cmd);

                    $filename = $file_path_info['filename'] . '.wav';


                    header("Content-type: application/octet-stream");

                    //处理中文文件名
                    $ua = $_SERVER["HTTP_USER_AGENT"];
                    $encoded_filename = rawurlencode($filename);
                    if (preg_match("/MSIE/", $ua))
                    {
                        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                    }
                    else if (preg_match("/Firefox/", $ua))
                    {
                        header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
                    }
                    else
                    {
                        header('Content-Disposition: attachment; filename="' . $filename . '"');
                    }

                    //让Xsendfile发送文件
                    readfile($dest_wav_file);
                }
            }
        }
    }

    function down_sippcap($cdr_id, $type)
    {
        //date_default_timezone_set("Etc/GMT");
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $query = "SELECT origination_call_id, termination_call_id , case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end as time,origination_source_host_name, origination_profile_port  FROM client_cdr".date("Ymd")." WHERE id = {$cdr_id};";
        $result = $this->Cdr->query($query);
        $time = $result[0][0]['time'];
        $date_path = date("Y/m/d/H/", strtotime($time));
        $sql = "select sip_capture_path from switch_profile where sip_ip = '{$result[0][0]['origination_source_host_name']}' and sip_port = {$result[0][0]['origination_profile_port']}";
        $switch_profiler_result = $this->Cdr->query($sql);
        $monitor_path = $switch_profiler_result[0][0]['sip_capture_path'];

        $real_path = $monitor_path . DS . 'sip_capture' . DS . $date_path;
        $real_rtp_path = $monitor_path . DS . 'rtp_capture' . DS . $date_path;

        if (file_exists($real_path))
        {
            if ($type == 'ingress')
                $include_text = '*' . $result[0][0]['origination_call_id'] . "*";
            else
                $include_text = '*' . $result[0][0]['termination_call_id'] . "*";
            $file = $this->searchfile($real_path, $include_text);
            if ($file)
            {
                $source_file = trim($file);


                $filename = basename($source_file);

                header("Content-type: application/octet-stream");

                //处理中文文件名
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = rawurlencode($filename);
                if (preg_match("/MSIE/", $ua))
                {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                }
                else if (preg_match("/Firefox/", $ua))
                {
                    header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
                }
                else
                {
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                }

                //让Xsendfile发送文件
                readfile($source_file);
            }
        }
    }

    function searchfile($path, $pattern)
    {
        /*
          $return = array();
          $files = scandir($path);
          if (is_array($files) && count($files) > 2 && !empty($pattern)) {
          $pattern = "*{$pattern}*";
          array_shift($files);
          array_shift($files);
          foreach ($files as $file) {
          if (fnmatch($pattern, $file)) {
          array_push($return, $file);
          }
          }
          }
         * 
         */
        $cmd = "find {$path} -name '{$pattern}'";
        $result = shell_exec($cmd);
        return $result;
    }

    public function rerate()
    {
        if ($this->RequestHandler->isPost())
        {
            $tmp_file = $_FILES['upfile']['tmp_name'];
            if (is_uploaded_file($tmp_file))
            {
                $filename = uniqid() . '.csv';
                $dest_path = WWW_ROOT . 'upload/rerating/' . $filename;
                $result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest_path);
                $type = $_POST['type'];
                $time = empty($_POST['time']) ? '' : "-T {$_POST['time']}";
                $rate_table_id = empty($_POST['rate_table_id']) ? '' : "-r {$_POST['rate_table_id']}";
                $table_name = empty($_POST['table_name']) ? '' : "-N {$_POST['table_name']}";
                $bin_path = Configure::read('rerating.bin');
                $conf_path = Configure::read('rerating.conf');
                $cmd = "{$bin_path} -d {$conf_path} -t {$type} -f {$dest_path} {$time} {$rate_table_id} {$table_name} 2>&1";
                shell_exec($cmd);
                $this->Cdr->create_json_array('#query-smartPeriod', 201, __('Succeed!', true));
                $this->Session->write("m", Cdr::set_validator());
            }
        }
        $sql = "SELECT rate_table_id, name FROM rate_table ORDER BY name ASC";
        $ratetables = $this->Cdr->query($sql);
        $this->set('ratetables', $ratetables);
    }

    public function getTechPerfix()
    {
        $this->autoRender = false;
        $this->layout = false;
        Configure::write('debug', 0);

        if (!empty($_POST['ingId']))
        {
            $ingress_id = $_POST['ingId'];

            $sql = "select id, tech_prefix from resource_prefix where resource_id = {$ingress_id}";
            $prefixes = $this->Cdr->query($sql);
            $sql = "select distinct (select name from rate_table where rate_table.rate_table_id = resource_prefix.
    rate_table_id) as rate_table_name, rate_table_id from resource_prefix where resource_id = {$ingress_id} and  rate_table_id is not null";
            $rate_tables = $this->Cdr->query($sql);
            $sql = "select distinct (select name from route_strategy where route_strategy_id 
    = resource_prefix.route_strategy_id) as route_strategy_name, route_strategy_id from resource_prefix  where resource_id = {$ingress_id} and  route_strategy_id is not null";
            $routing_plans = $this->Cdr->query($sql);

            $res = array(
                'prefixes' => $prefixes,
                'rate_tables' => $rate_tables,
                'routing_plans' => $routing_plans,
            );

            return json_encode($res);
        }
    }

    /**
     * Consolidated CDR
     */
    public function cdr_consolidated()
    {
        $this->index();

        $this->pageTitle = "Statistics/Consolidated CDR";
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 1000;
        $where = "";
        if ($this->isnotEmpty($this->params['url'], array('ingress_ani')) && strcasecmp('Ingress_ANI', $this->params['url']['ingress_ani']))
        {
            $where .= " AND origination_source_number like '%{$this->params['url']['ingress_ani']}%'";
            $this->set('ingress_ani', $this->params['url']['ingress_ani']);
        }
        if ($this->isnotEmpty($this->params['url'], array('ingress_dnis')) && strcasecmp('Ingress_DNIS', $this->params['url']['ingress_dnis']))
        {
            $where .= " AND origination_destination_number like '%{$this->params['url']['ingress_dnis']}%'";
            $this->set('ingress_dnis', $this->params['url']['ingress_dnis']);
        }
        $today = date("Y-m-d");
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
        {
            $where .= " AND time >= '{$this->params['url']['time_start']}'";
            $this->set('time_start', $this->params['url']['time_start']);
        }
        else
        {
            $where .= " AND time >= '{$today}'";
            $this->set('time_start', $today);
        }
        if ($this->isnotEmpty($this->params['url'], array('time_end')))
        {
            $where .= " AND time <= '{$this->params['url']['time_end']} 23:59:59'";
            $this->set('time_end', $this->params['url']['time_end']);
        }
        else
        {
            $where .= " AND time <= '{$today} 23:59:59'";
            $this->set('time_end', $today);
        }


//        $sql = "select count(*) as sum from
//            (select count(case when trunk_id_termination is not null then 1 else 0 end ) ,time
//                ,origination_source_number ,trunk_id_origination,origination_destination_number,release_cause, 
//                binary_value_of_release_cause_from_protocol_stack,ingress_id from client_cdr where ingress_id is not null {$where} group by origination_source_number 
//                ,trunk_id_origination,binary_value_of_release_cause_from_protocol_stack,ingress_id,origination_destination_number,release_cause,time)as t1 ";
        //$totalrecords = $this->Cdr->query($sql);
        $totalrecords = 1000;
        //$page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setTotalRecords($totalrecords);
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $sql = "select count(case when trunk_id_termination is not null then 1 else 0 end ), time
                ,origination_source_number, origination_destination_number,release_cause,binary_value_of_release_cause_from_protocol_stack 
                ,ingress_id,trunk_id_origination from client_cdr
                where ingress_id is not null {$where} group by origination_call_id, origination_source_number 
                ,ingress_id,trunk_id_origination ,origination_destination_number,release_cause,binary_value_of_release_cause_from_protocol_stack,time"
                . " order by ingress_id desc";
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql .= "  limit '$pageSize' offset '$offset'";
        $data_arr = $this->Cdr->query($sql);

        $return_cause = array(
            "0" => 'Unknown Exception',
            "1" => 'System CPS Limit Exceeded',
            "2" => 'SYSTEM_CPS System Limit Exceeded',
            "3" => 'Unauthorized IP Address',
            "4" => 'No Ingress Resource Found',
            "5" => 'No Product Found',
            "6" => 'Trunk Limit Call Exceeded',
            "7" => 'Trunk Limit CPS Exceeded',
            "8" => 'IP Limit  Call Exceeded',
            "9" => 'IP Limit CPS Exceeded',
            "10" => 'Invalid Codec Negotiation',
            "11" => 'Block due to LRN',
            "12" => 'Ingress Rate Not Found',
            "13" => 'Egress Trunk Not Found',
            14 => 'Egress Returns 404',
            15 => 'Egress Returns 486',
            16 => 'Egress Returns 487',
            17 => 'Egress Returns 200',
            18 => 'All Egress Unavailable',
            19 => 'Normal hang up',
            20 => 'Ingress Resource disabled',
            21 => 'Zero Balance',
            22 => 'No Route Found',
            23 => 'Invalid Prefix',
            24 => 'Ingress Rate Missing',
            25 => 'Invalid Codec Negotiation',
            26 => 'No Codec Found',
            27 => 'All Egress Failed',
            28 => 'LRN Response Missing',
            29 => 'Carrier Call Limit Exceeded',
            30 => 'Carrier CPS Limit Exceeded',
            31 => 'Rejected Due to Host Alert',
            32 => 'Rejected Due to Trunk Alert',
            33 => 'H323 Not Supported',
            34 => '180 Negotiation Failure',
            35 => '183 Negotiation Failute',
            36 => '200 Negotiation Failure',
            37 => 'Block LRN with Higher Rate',
            38 => 'Ingress Block ANI',
            39 => 'Ingress Block DNIS',
            40 => 'Ingress Block ALL',
            41 => 'Global Block ANI',
            42 => 'Global Block DNIS',
            43 => 'Global Block ALL',
            44 => 'T38 Reject',
        );

        //$this->print_rr($data_arr);
        $this->set('return_cause', $return_cause);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function get_egress_attempt()
    {
        $get_arr = $this->params['url'];


        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;

        $sql = "select count(*) as sum from  client_cdr".date("Ymd")." where origination_source_number = '{$get_arr['origination_source_number']}' 
            and origination_destination_number = '{$get_arr['origination_destination_number']}' 
                and time ='{$get_arr['time']}' and binary_value_of_release_cause_from_protocol_stack = '{$get_arr['binary_value_of_release_cause_from_protocol_stack']}' "
                . " and release_cause = '{$get_arr['release_cause']}'";

        $totalrecords = $this->Cdr->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围



        $sql = "select call_duration,trunk_id_origination,trunk_id_termination,pdd,release_cause,orig_call_duration,origination_destination_host_name
            ,release_cause_from_protocol_stack,origination_destination_number,origination_source_number,
            binary_value_of_release_cause_from_protocol_stack,trunk_type,time
            from client_cdr  where origination_source_number = '{$get_arr['origination_source_number']}' 
            and origination_destination_number = '{$get_arr['origination_destination_number']}' 
                and time ='{$get_arr['time']}' and binary_value_of_release_cause_from_protocol_stack = '{$get_arr['binary_value_of_release_cause_from_protocol_stack']}' "
                . " and release_cause = '{$get_arr['release_cause']}'";

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql .= "  limit '$pageSize' offset '$offset'";
        $egress_arr = $this->Cdr->query($sql);


        $page->setDataArray($egress_arr);
        $this->set('p', $page);
    }

    public function cdr_list_export_down()
    {
        $id = base64_decode($_GET['key']);
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $log = $this->CdrListExportLog->findById($id);
        $file_path = $log['CdrListExportLog']['file_path'];
        $filename = basename($file_path);
        header("Content-type: application/octet-stream");
        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        readfile($file_path);
    }

    public function cdr_list_export()
    {
        $this->pageTitle = "CDR Export Log";


        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $conditions = array(
            'CdrListExportLog.job_start_time BETWEEN ? and ?' => array($start_time, $end_time)
        );

        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CdrListExportLog.id' => 'desc',
            ),
            'conditions' => $conditions
        );
        if (isset($_SESSION['sst_client_id']))
        {
            $this->paginate['conditions']['client_id'] = $_SESSION['sst_client_id'];
        }
        $status = array("Waiting", "In Progress", "Completed");
        $this->set('status', $status);
        $this->data = $this->paginate('CdrListExportLog');
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }
 

}
