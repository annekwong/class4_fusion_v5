<?php

class CdrreportsDbController extends AppController
{

    var $name = 'CdrreportsDb';
    var $uses = array('Cdr', 'CdrExportLog', 'CdrsRead', 'CdrRead', 'Systemparam', 'SipRequest', 'ApiLog');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');
    var $siteIp;
    var $curlQueries;
    var $apiUrl;

    public function __construct()
    {
        Configure::load('myconf');
        parent::__construct();
        $this->siteIp = getHostByName(getHostName());
        $tmpIp = Configure::read('pcap.api.ip');

        if (!empty($tmpIp)) {
            $this->siteIp = $tmpIp;
        }
        $this->apiUrl = Configure::read('pcap_url');
        $this->curlQueries = array();
    }


    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $allowedActions = array('get_file', 'get_export_file', 'export_log_down', 'get_export_rows', 'get_proccess_info', 'get_pcap_file');

        if (in_array($this->params['action'], $allowedActions))
            return true;
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');

        if ($login_type == 3 && $this->params['action'] == 'sip_requests') {
            return true;
        }

        if (1) {//($login_type==1){
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
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

    public function check_email()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT email FROM users where user_id = {$user_id}";
        $re_reportlt = $this->Cdr->query($sql);
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
        foreach ($arr as $key => $value) {
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
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
        $this->set('all_host', $this->Cdr->find_all_resource_ip());
        $this->set('cdr_field', $this->Cdr->find_field());

        if (!empty($_GET['ingress_alias'])) {
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
        if (isset($_GET['cdr_release_cause']) && $_GET['cdr_release_cause'] != '') {

            return "and release_cause={$_GET ['cdr_release_cause']}";
        } else {
            return '';
        }
    }

    function capture_report_order()
    {
        $order = $this->_order_condtions(
            array_keys($this->Cdr->find_field())
        );
        if (empty($order)) {
            $order = 'order by time desc';
        } else {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function capture_report_order_1()
    {
        $order = $this->_order_condtions(
            array_keys($this->Cdr->find_field())
        );
        if (empty($order)) {
            $order = 'order by time desc';
        } else {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function get_datas($report_type = '', $process_field)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        $spam_where = '';
        if ($report_type == 'spam_report') {
            $spam_where = "and  release_cause = 3 ";
        }
        $where = $where . $spam_where;
        if (empty(trim($where))) {
            $where = " 1=1 ";
        }
        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order();
        $release_cause = ' release_cause ';

        $trunk_type = "case trunk_type when 1 then 'class4' when 2 then 'exchange' end as trunk_type";

        $binary_value_of_release_cause_from_protocol_stack = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";

//        $commission = " ingress_client_cost / (1+tax) as  commission ";

        $route_id = "(select route_id from route where route_strategy_id = client_cdr.route_plan and 
            (static_route_id = client_cdr.static_route or (static_route_id is null and client_cdr.static_route is null)) and
            (dynamic_route_id = client_cdr.dynamic_route or (dynamic_route_id is null and client_cdr.dynamic_route is null)) 
            limit 1) as route_id";
        //default

        if (empty($show_field)) {
            //默认的 显示字段

//            $show_field = "$route_id,tax,{$commission},call_duration,origination_destination_host_name,trunk_id_termination,trunk_id_origination,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call,{$trunk_type}";
//            $show_field = "tax,call_duration,origination_destination_host_name,trunk_id_termination,trunk_id_origination,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call";
            $show_field = "call_duration,origination_destination_host_name,ingress_id as ingress_name,egress_id as egress_name,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call";
            if (isset($_GET['open_callmonitor']) && $_GET['open_callmonitor'] == 1)
                $show_field = $show_field . ',id';

            if ($report_type == 'spam_report')
                $show_field = "origination_destination_number,origination_source_host_name,origination_source_number,time";
        }

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3') {

            /* $show_field_array=array(
              'origination_source_host_name', 'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
              );
             */
            $show_field_array = array(
                'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
            );
            $show_field_array_bak = array_keys($this->Cdr->find_client_cdr_field());
            $show_field_array = array_merge($show_field_array, $show_field_array_bak);
            $show_field_array = explode(',', implode(',', $show_field_array));
            //$show_field_array = array_keys($this->Cdr->find_client_cdr_field());

            $this->loadModel('Systemparam');
            $incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields(true);
            $incoming_data = $this->Systemparam->get_daily_cdr_fields(2);
            $allowedFields = array_keys($incoming_data);

            $show_field_array = $allowedFields ? : $show_field_array;
            $show_field = implode(',', $show_field_array);

            $dynamic_route = '(select name from dynamic_route where dynamic_route_id = dynamic_route) as dynamic_route';
            $show_field = str_replace("dynamic_route",$dynamic_route,$show_field);
            $ingress_client_rate_table_name = '(select name from rate_table where rate_table_id = ingress_client_rate_table_id) as ingress_client_rate_table_id';
            $show_field = str_replace("ingress_client_rate_table_id",   $ingress_client_rate_table_name, $show_field);

            $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields(true);
            $outgoing_data = $this->Systemparam->get_daily_cdr_fields(3);
            if (empty($outgoing_data)) {
                $outgoing_data = array(
                    'origination_source_number' => 'ORIG src Number',
                    'origination_destination_number' => 'ORIG DST Number',
                    'call_duration' => 'Call Duration',
                    'answer_time_of_date' => 'Answer Time',
                    'release_cause' => 'Release Cause',
                    'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress'
                );
            }

            $incoming_data = $this->Systemparam->get_daily_cdr_fields(2);
            if (empty($incoming_data)) {
                $incoming_data = array(
                    'origination_source_number' => 'ORIG src Number',
                    'origination_destination_number' => 'ORIG DST Number',
                    'call_duration' => 'Call Duration',
                    'answer_time_of_date' => 'Answer Time',
                    'release_cause' => 'Release Cause',
                    'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress'
                );
            }
            $this->set('incoming_cdr_fields', $incoming_cdr_fields);
            $this->set('outgoing_cdr_fields', $outgoing_cdr_fields);
            if (isset($this->params['url']['query']['fields'])) {
                $incoming_show_fields = $this->params['url']['query']['fields'];
                $outgoing_show_fields = $this->params['url']['query']['fields'];
            } else {
                $incoming_show_fields = array_keys($incoming_data);
                $outgoing_show_fields = array_keys($outgoing_data);
            }
            $this->set('incoming_data', $incoming_show_fields);
            $this->set('outgoing_data', $outgoing_show_fields);

            if(empty($show_field)) {
                $show_field = "answer_time_of_date,call_duration,release_tod,final_route_indication,trunk_id_origination,ingress_client_cost,ingress_client_rate,origination_destination_number,start_time_of_date";
            }
        } else {
//            $show_field_array = array('tax', 'commission', 'call_duration', 'trunk_id_termination', 'trunk_id_origination', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name', 'trunk_type','route_id');
//            $show_field_array = array('tax', 'call_duration', 'trunk_id_termination', 'trunk_id_origination', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name');

            if(isset($_SESSION['login_type']) && $_SESSION['login_type'] == '2'){
                $show_field_array = array('ingress_id as ingress_name', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time','egress_bill_time');
            }else{
                $show_field_array = array( 'call_duration', 'egress_id as egress_name', 'ingress_id as ingress_name', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name');
            }

            if (isset($_GET['open_callmonitor']) && $_GET['open_callmonitor'] == 1)
                array_push($show_field_array, 'id');

            if ($report_type == 'spam_report')
                $show_field_array = array('origination_destination_number', 'origination_source_host_name', 'origination_source_number', 'time');
        }

        if (!empty($process_field)) {
            $show_field = $process_field;
            $show_field_array = explode(',', $process_field);
        }

        $replace_fields = [
            'is_final_call' => 'final_route_indication'
        ];

        foreach($replace_fields as $new_field => $old_field){
            if(in_array($old_field, $show_field_array)){
                $show_field_array[array_search($old_field, $show_field_array)] = $new_field;
            }
        }

        $sql_field_array = $show_field_array;
        $sql_field_array = $this->sql_field_array_help($sql_field_array);
        if (!empty($sql_field_array)) {
            $show_field = join(',', $sql_field_array);
        }

        $this->set('show_field_array', $show_field_array);

        #other  report cdr
        if (isset($this->params['pass'][0])) {
            #查看client的cdr
            if ($this->params['pass'][0] == 'client') {
                $this->pageTitle = "Statistics/CDR Search ";
                if (!empty($this->params['pass'][1])) {
                    $where .= " and (ingress_client_id='{$this->params['pass'][1]}'  or  egress_client_id='{$this->params['pass'][1]}')";
                }
            }
            #查看断开码对应的cdr
            if ($this->params['pass'][0] == 'disconnect') {

                if (!empty($this->params['pass'][1])) {
                    if ($this->params['pass'][2] == 'org') {

                        $where .= "and   release_cause ='{$this->params['pass'][3]}' and is_final_call = 1  and    binary_value_of_release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                    } else {
                        //$where.= " and   release_cause ='{$this->params['pass'][3]}' and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                        //$where.= " and   release_cause  is null and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";
                        $where .= "and   release_cause ='{$this->params['pass'][3]}'  and    release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";
                    }
                }
            }
            #download mismatch cdr
            if ($this->params['pass'][0] == 'mismatch') {
                if ($this->params['pass'][1] == 'unknowncarriers') {
                    $where .= " and ingress_client_bill_result='2'";
                }
                if ($this->params['pass'][1] == 'unknownratetable') {
                    $where .= " and ingress_client_bill_result='3'";
                }
                if ($this->params['pass'][1] == 'unknownrate') {
                    $where .= " and ingress_client_bill_result='4'";
                }
            }
        }


        //生成分表查询语句
        extract($this->get_start_end_time());
        $time_data = $this->_get_date_result_admin($start, $end, 'client_cdr2%');
        $count_sql = "";
        $org_sql = "";

//        $show_field = "id," . $show_field;
        $show_web_pcap_field = "concat_ws('||',origination_call_id,COALESCE(termination_call_id,'0'),origination_destination_host_name,origination_profile_port,start_time_of_date,release_tod, orig_call_duration) as pcap_field";
        $show_simulate_field = "concat_ws('||',origination_destination_host_name,origination_profile_port,origination_source_host_name,origination_source_number,origination_destination_number,ingress_id,start_time_of_date,pdd) as simulate_field";
        $show_egress_info_field = "concat_ws('||',(select alias from resource where resource_id = egress_id),egress_rate,release_cause_from_protocol_stack) as egress_info_field";

        if (!isset($_GET ['query'] ['output']) || $_GET ['query'] ['output'] == 'web') {
            if(!empty($show_field)) {
                $show_field = "id,$show_web_pcap_field,$show_simulate_field,$show_egress_info_field,$show_field";
            } else {
                $show_field = "id,$show_web_pcap_field,$show_simulate_field,$show_egress_info_field";
            }
        }

//        if (count($time_data) < 4)
//        {
        $count_got = false;
        for ($i = count($time_data) - 1; $i >= 0; $i--) {
            $table_name = "client_cdr" . $time_data[$i];

            $union = "";
            if (!empty($org_sql)) {
                $union = " union all ";
            }

            $org_sql .= " {$union}  (select $show_field  from   {$table_name}  $join     where   $where $order) ";
            $org_sql = str_replace('client_cdr.', $table_name . ".", $org_sql);
            $org_sql = str_replace('client_cdr.', $table_name . ".", $org_sql);
            $org_sql = str_replace(',ingress_id as ingress_name', ",(select alias from resource where resource_id = ingress_id) as ingress_name", $org_sql);
            $org_sql = str_replace(',egress_id as egress_name', ",(select alias from resource where resource_id = egress_id) as egress_name", $org_sql);
//            $org_sql = str_replace(',time', "," . $table_name.".time as time", $org_sql);

        }

        return compact('org_sql', 'where', 'show_field', 'show_field_array');
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
        if ($order == 'order by id desc') {
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
                when   48 then 'Allowed Send To IP Failed'
                when   52 then 'Switch Profile CAP Limit Exceeded'
                when   53 then 'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause";
        $show_field = "call_duration,rerate_time,trunk_id_origination,origination_destination_number,origination_source_number,$release_cause,release_cause_from_protocol_stack,binary_value_of_release_cause_from_protocol_stack,time";
        $show_field_array = array('call_duration', 'rerate_time', 'trunk_id_origination', 'origination_destination_number', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'binary_value_of_release_cause_from_protocol_stack', 'time');
        //cdr 显示字段
        if (isset($_GET ['query'] ['fields'])) {
            $show_field = '';
            $show_field_array = $_GET ['query'] ['fields'];
            $sql_field_array = $show_field_array;
            $sql_field_array = $this->sql_field_array_help($sql_field_array);
            if (!empty($sql_field_array)) {
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
translation_ani,item_id,ingress_rate_id,egress_rate_id,rerate_time,orig_code,orig_code_name,orig_country,term_code,term_code_name,term_country,ingress_rate_effective_date,egress_rate_effective_date,egress_erro_string,lrn_number_vendor,lnp_dipping_cost,is_final_call,egress_code_asr,egress_code_acd,q850_cause,q850_cause_string";
        //$org_sql = "select $re_rating_org_sql $re_rating_term_sql 'rerating' as  rerating  from   client_cdr 	$join where   $where  $order  ";


        $org_sql = "select $tmp_sql from  client_cdr 	$join where $where $order  ";

        $rerate_rate_table = !empty($_GET['rerate_rate_table']) ? "{$_GET['rerate_rate_table']}" : '';

        $rerate_time = !empty($_GET['rerate_time']) ? "{$_GET['rerate_time']}" : '';

        //update cdr
        if ($process_type == 'update_cdr') {
            //$this->Cdr->query($org_sql);
        }
        return compact('org_sql', 'count_sql', 'rerate_type', 'where', 'rerate_rate_table', 'rerate_time');
    }

    function index()
    {
        $this->redirect('summary_reports');
    }

    function save_fields_ajax()
    {
//        print_r($_POST['query-fields']);die;
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $flg = $this->Cdr->query("UPDATE users SET report_fields = '" . implode(',', $_POST['query-fields']) . "' WHERE user_id = '{$this->Session->read('sst_user_id')}'");
            if ($flg !== NULL) {
                echo 'Success!';
            } else {
                echo 'Failed!';
            }
        }
        exit;
    }

    public function saveNewFields()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost() && isset($this->params['form']['fields'])) {
            $this->Session->write('newCdrFields', $this->params['form']['fields']);
            echo 1;
        }
        exit;
    }

    function summary_reports_new()
    {
        extract($this->get_start_end_time());

        $dateStart = $start;
        $dateEnd = $end;
        $report_fields = array(
            'time',
            'termination_destination_number',
            'release_cause,pdd',
            'orig_call_duration',
            'call_duration',
            'ingress_id as ingress_name',
            'egress_id as egress_name',
            'origination_source_number',
            'origination_destination_number',
            'origination_source_host_name',
            'termination_source_host_name',
            'release_cause_from_protocol_stack',
            'binary_value_of_release_cause_from_protocol_stack',
            'answer_time_of_date',
            'origination_destination_host_name'
        );

        $this->initNewReport();

        $field = array();

        $this->set('start', $dateStart);
        $this->set('end', $dateEnd);
        $this->set('quey_time', 32);
        $this->set('report_fields', $report_fields);
        $this->set('cdr_field', $field);
    }

    public function initNewReport()
    {
        $user_id = $_SESSION['sst_user_id'];

        $this->init_query();

        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");

        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        $system_parameter = $this->Cdr->query("SELECT is_hide_unauthorized_ip FROM system_parameter limit 1");
        $is_hide_unauthorized_ip = isset($this->params['url']['is_hide_unauthorized_ip']) ? $this->params['url']['is_hide_unauthorized_ip'] : $system_parameter[0][0]['is_hide_unauthorized_ip'];

        $this->set('is_hide_unauthorized_ip', $is_hide_unauthorized_ip);

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
    }

    function summary_reports($id = null, $cli_id = null)
    {
        // on some servers it's 30 sec, temporary solution ...
        ini_set('max_execution_time', 300);

        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $cloudSharkUrl = '108.165.2.57';
        $this->set('cloudSharkUrl', $cloudSharkUrl);
        $this->select_name($cli_id);
        $system_parameter = $this->Cdr->query("SELECT is_hide_unauthorized_ip FROM system_parameter limit 1");
        $is_hide_unauthorized_ip = isset($this->params['url']['is_hide_unauthorized_ip']) ? $this->params['url']['is_hide_unauthorized_ip'] : $system_parameter[0][0]['is_hide_unauthorized_ip'];
        $this->set('is_hide_unauthorized_ip', $is_hide_unauthorized_ip);

        $this->pageTitle = "Statistics/CDRs List";

        $t = getMicrotime();

        if (isset($_SESSION['login_type']) && ($_SESSION['login_type'] == '3' || $_SESSION['login_type'] == '2'))
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

        /*
          $sip_status = "";
          $cmd = "sipcapture_set_flag other";
          $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
          if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
          socket_write($socket, $cmd, strlen($cmd));
          }
          while ($out = socket_read($socket, 2048)) {
          $sip_status .= $out;
          if (strpos($out, "~!@#$%^&*()") !== FALSE) {
          break;
          }
          unset($out);
          }
          $sip_status = strstr($sip_status, "~!@#$%^&*()", TRUE);


          $this->set('sip_capture_status', $sip_status);
         *
         */

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


        $converted_start_date = date('Y-m-d', strtotime($start));
        $converted_start_time = date('H:i:s', strtotime($start));
        $converted_stop_date = date('Y-m-d', strtotime($end));
        $converted_stop_time = date('H:i:s', strtotime($end));

        $this->set("report_type", $report_type);

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        if (isset($_GET['query']['fields']) && !empty($_GET['query']['fields'])){
            $query_fields = ['report_fields' => implode(',', $_GET['query']['fields'])];
            $report_fields = [[$query_fields]];
        }else{
            $report_fields = $this->Cdr->query("SELECT report_fields FROM users WHERE user_id = '{$this->Session->read('sst_user_id')}'");
        }

        extract($this->get_datas($report_type, $report_fields[0][0]['report_fields']));

        if($report_fields[0][0]['report_fields'] == 'true' || empty($report_fields[0][0]['report_fields'])){
            $report_fields[0][0]['report_fields'] = 'time,termination_destination_number,release_cause,pdd,orig_call_duration,call_duration,ingress_id as ingress_name,egress_id as egress_name,origination_source_number,origination_destination_number,origination_source_host_name,termination_source_host_name,release_cause_from_protocol_stack,binary_value_of_release_cause_from_protocol_stack,answer_time_of_date,origination_destination_host_name';
        }
        require_once 'MyPage.php';
        $page = new MyPage ();
        $max_limit = 1000;
//        $totalrecords = $this->Cdr->query($count_sql);
//        $max_limit = ($max_limit > $totalrecords [0] [0] ['c']) ? $totalrecords [0] [0] ['c'] : $max_limit;
        $page->setTotalRecords($max_limit); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        if (!isset($show_field) || (isset($show_field) && empty($show_field))) {
            $show_field = $report_fields;
        }
        $org_page_sql = $org_sql;

        if(!(isset($_GET ['query'] ['output']) && $_GET ['query'] ['output'] == 'csv')) {
            $org_page_sql .= $page_where;
        }
        $this->set('show_nodata', true);
        if (isset($_GET ['query'] ['output']))
        {
            if (!empty($_GET['query']['fields'])) {
                $show_field = implode(',',$_GET['query']['fields']);
            }
//            $show_field = str_replace('ingress_rate_type', "(case ingress_rate_type when 1 then 'inter' when 2 then 'intra' when 4 then 'error' when 5 then 'local' else 'others') end as ingress_rate_type", $show_field);
//            $show_field = str_replace('egress_rate_type', "(case egress_rate_type when 1 then 'inter' when 2 then 'intra' when 4 then 'error' when 5 then 'local' else 'others') end as egress_rate_type", $show_field);

            //下载
            $file_name = $this->create_doload_file_name('cdr', $start, $end);
            $replace_field_name = $this->Cdr->find_field();

            if ($_GET ['query'] ['output'] == 'api') {
                $fields = '';
                $apiStart = $converted_start_date . ' ' . $converted_start_time;
                $apiEnd = $converted_stop_date . ' ' . $converted_stop_time;
                $ftpHost = trim($_GET['real_ftp_host']);
                $ftpPort = trim($_GET['real_ftp_port']);
                $ftpUser = trim($_GET['real_ftp_user']);
                $ftpPassword = $_GET['real_ftp_password'];
                $ftpPath = trim($_GET['real_ftp_path'], '/');
                $ftpUrl = "ftp://{$ftpUser}:{$ftpPassword}@{$ftpHost}:{$ftpPort}/{$ftpPath}";
                $_GET['query']['output'] = 'web';

                if (!empty($_GET['query']['fields']))
                    $fields = implode(',',$_GET['query']['fields']);
                $this->redirect(array('controller' => 'cdrreports_db', 'action' => 'get_cdr/1',
                    '?' => array(
                        'start_time' => $apiStart,
                        'end_time' => $apiEnd,
                        'fields' => $fields,
                        'ftp' => $ftpUrl,
                        'get' => $_GET['query']
                    )));
            }
            elseif ($_GET ['query'] ['output'] == 'csv')
            {
                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $org_sql_replace = $replace_arr[0];
                $replace_fields_arr = $replace_arr[1];

////                $show_field_new = str_replace('client_cdr', 'client_cdr20170921', $show_field);
//                $tmpRes = $this->CdrRead->query("SELECT {$show_field} FROM client_cdr20170921 WHERE {$where}");
//
//                die(var_dump($tmpRes, $show_field, $where));
                $show_field = str_replace(' release_cause ', $this->getReplaceReleaseCauses(), $show_field);
                $job_id = $this->CdrRead->download_cdr(array('file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr, 'sql' => $org_page_sql));
                if ($job_id)
                {
                    $php_path = Configure::read('php_exe_path');
                    $cmd = "{$php_path} " . ROOT . "/cake/console/cake.php export_cdr {$job_id} > /dev/null/ &";
                    $res = shell_exec($cmd);
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                else
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                $org_sql_replace = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_xls'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'email')
            {
                $address = $_GET['send_mail_address'];
                $fields = '';
                $apiStart = $converted_start_date . ' ' . $converted_start_time;
                $apiEnd = $converted_stop_date . ' ' . $converted_stop_time;
                $mailSubject = isset($_GET['real_send_subject']) ? $_GET['real_send_subject'] : 'test';
                $mailContent = isset($_GET['real_send_content']) ? $_GET['real_send_content'] : 'test';
                $_GET['query']['output'] = 'web';

                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $replace_fields_arr = $replace_arr[1];
//                die(var_dump($address, $file_name, $start, $end, $show_field, $where, $replace_fields_arr));
                $job_id = $this->CdrRead->download_cdr(array('send_mail' => $address,'file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
                if ($job_id)
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                else
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }

                if (!empty($_GET['query']['fields']))
                    $fields = implode(',',$_GET['query']['fields']);
                $this->redirect(array('controller' => 'cdrreports_db', 'action' => 'get_cdr/0',
                    '?' => array(
                        'start_time' => $apiStart,
                        'end_time' => $apiEnd,
                        'fields' => $fields,
                        'mail_subject' => $mailSubject,
                        'mail_content' => $mailContent,
                        'mail_to' => $address,
                        'get' => $_GET['query']
                    )));


//                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
//                $org_sql_replace = $replace_arr[0];
//                $replace_fields_arr = $replace_arr[1];
////                pr($org_sql_replace,$show_field, $replace_field_name);die;
////                pr($start,$end,$show_field,$where);die;
////                Configure::write('debug', 0);
////                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr',$replace_fields_arr));
////                pr($start,$end,$show_field,$where,$replace_fields_arr);die;
//                $job_id = $this->CdrRead->download_cdr(array('send_mail' => $address,'file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
//                if ($job_id)
//                {
//                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
//                    $this->Session->write("m", Cdr::set_validator());
//                    $this->redirect('export_log');
//                }
//                else
//                {
//                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
//                    $this->Session->write("m", Cdr::set_validator());
//                    $this->redirect('export_log');
//                }
            }
            else
            {
                $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
                $is_preload_result = $this->Cdr->query($sql);
                $is_preload = $is_preload_result[0][0]['is_preload'];
                if ($is_preload && ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 3))
                {
                    $org_page_sql = str_replace('route_id,', '', $org_page_sql);
                }
                //         Configure::write('debug', 2);
                $results = $this->CdrsRead->query($org_page_sql);
                $this->set('results_count',count($results));
                $page->setDataArray($results);
                $this->set('p', $page);
            }

//            die(var_dump(json_encode($results)));
        }
        else
        {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];


            if ($is_preload && ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 3))
            {
//                $sql = "SELECT time FROM client_cdr20161202";
//                $tmRes = $this->CdrsRead->query($sql);
//                die(var_dump($tmRes));
                $org_page_sql = str_replace('route_id,', '', $org_page_sql);
                $this->set('show_nodata', true);

                $results = $this->CdrsRead->query($org_page_sql);
                $this->set('results_count',count($results));
            }
            else
            {
                $this->set('show_nodata', false);
                $results = array();
            }
            $page->setDataArray($results);
            $this->set('p', $page);
        }

        $report_fields = $this->Cdr->query("SELECT report_fields FROM users WHERE user_id = '{$this->Session->read('sst_user_id')}'");

        if($report_fields[0][0]['report_fields'] == 'true' || empty($report_fields[0][0]['report_fields'])){
            $report_fields[0][0]['report_fields'] = 'time,termination_destination_number,release_cause,pdd,orig_call_duration,call_duration,ingress_id as ingress_name,egress_id as egress_name,origination_source_number,origination_destination_number,origination_source_host_name,termination_source_host_name,release_cause_from_protocol_stack,binary_value_of_release_cause_from_protocol_stack,answer_time_of_date,origination_destination_host_name';
        }

        if (isset($report_fields)) {
            $report_fields = explode(',', $report_fields[0][0]['report_fields']);
        }
        $this->set('report_fields',$report_fields);

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $this->_get_cdr_email_template();
        //  $this->set('name',$this->queryName($cli_id));
        //  $this->set('cdrr_name',$this->Cdr->query("select name from client where client_id=".$this->params['pass'][1] .""));
    }

    function summary_reports2($id = null, $cli_id = null)
    {
        //add user enable outbound report  and enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->select_name($cli_id);
        $system_parameter = $this->Cdr->query("SELECT is_hide_unauthorized_ip FROM system_parameter limit 1");
        $is_hide_unauthorized_ip = isset($this->params['url']['is_hide_unauthorized_ip']) ? $this->params['url']['is_hide_unauthorized_ip'] : $system_parameter[0][0]['is_hide_unauthorized_ip'];
        $this->set('is_hide_unauthorized_ip', $is_hide_unauthorized_ip);

        //get action start time
        $t = getMicrotime();

        //set Page Title and cdr_type
        $this->pageTitle = "Statistics/Spam Report ";
        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3') {
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            if ($cdr_type == 'buy') {
                $cdr_type_active = 'term_service_buy';
                $this->pageTitle = "Reports/Term. Service Buy";
            } else{
                $cdr_type_active = 'term_service_sell';
                $this->pageTitle = "Reports/Term. Service Sell";
            }
            $this->set('cdr_type', $cdr_type_active);
        }
        //set rate_type
        if (!empty($this->params['pass'][0])) {
            $rate_type = $this->params['pass'][0];
            if ($rate_type == 'all') {
                $this->set('rate_type', 'all');
            } elseif ($rate_type == 'spam') {
                $this->set('rate_type', 'spam');
            } else {
                $this->set('rate_type', 'all');
            }
        } else {
            $rate_type = 'all';
            $this->set('rate_type', 'all');
        }
        //set report type
        if ($rate_type == 'spam') {
            $report_type = "spam_report";
        } else {
            $report_type = "cdr_search";
        }

        $this->init_query();
        //extract($this->Cdr->get_real_period());
        extract($this->get_start_end_time());
        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, ''));
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        $org_page_sql = $org_sql . $page_where;
        $this->set('show_nodata', true);

        //select output type
        if (isset($_GET ['query'] ['output'])) {
            //download filename
            $file_name = $this->create_doload_file_name('cdr', $start, $end);
            $replace_field_name = $this->Cdr->find_field();

            if ($_GET ['query'] ['output'] == 'api') {

                $fields = '';
                if (!empty($_GET['query']['fields']))
                    $fields = implode(',',$_GET['query']['fields']);
                $this->redirect(array('controller' => 'cdrreports_db', 'action' => 'get_cdr',
                    '?' => array(
                        'start_time' => $start,
                        'end_time' => $end,
                        'fields' => $fields
                    )));

            } elseif ($_GET ['query'] ['output'] == 'csv') {

                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $org_sql_replace = $replace_arr[0];
                $replace_fields_arr = $replace_arr[1];
//                pr($org_sql_replace,$show_field, $replace_fields_arr,$replace_field_name);die;
//                pr($start,$end,$show_field,$where);die;
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr',$replace_fields_arr));
//                pr($start,$end,$show_field,$where,$replace_fields_arr);die;
                $job_id = $this->CdrRead->download_cdr(array('file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
                if ($job_id) {
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                } else {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }

            } elseif ($_GET ['query'] ['output'] == 'xls') {

                //xls down
                $org_sql_replace = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_xls'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name));

            } elseif ($_GET ['query'] ['output'] == 'email') {

                $address = $_GET['send_mail_address'];
                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $org_sql_replace = $replace_arr[0];
                $replace_fields_arr = $replace_arr[1];
//                pr($org_sql_replace,$show_field, $replace_field_name);die;
//                pr($start,$end,$show_field,$where);die;
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr',$replace_fields_arr));
//                pr($start,$end,$show_field,$where,$replace_fields_arr);die;
                $job_id = $this->CdrRead->download_cdr(array('send_mail' => $address,'file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
                if ($job_id)
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                else
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
            } else {
                //web output
                $results = $this->get_cdr_results($org_page_sql, $start, $end, $pageSize, $offset, $where);
                $this->set('results_count',count($results));
                $page->setDataArray($results);
                $this->set('p', $page);
            }
        } else {
            // check is preload
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];

            //load results
            if ($is_preload && $_SESSION['login_type'] == 1) {
                $this->set('show_nodata', true);

                $results = $this->get_cdr_results($org_page_sql, $start, $end, $pageSize, $offset, $where);

                $this->set('results_count',count($results));
            } else {
                $this->set('show_nodata', false);
                $results = array();
            }
            $page->setDataArray($results);
            $this->set('p', $page);
        }

        //get user report fields
        $report_fields = $this->Cdr->query("SELECT report_fields FROM users WHERE user_id = '{$user_id}'");
        if (isset($report_fields)) {
            $report_fields = explode(',', $report_fields[0][0]['report_fields']);
        }
        $this->set('report_fields',$report_fields);

        //calculate action query time
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));


        $this->_get_cdr_email_template();
        //  $this->set('name',$this->queryName($cli_id));
        //  $this->set('cdrr_name',$this->Cdr->query("select name from client where client_id=".$this->params['pass'][1] .""));
    }

    private function get_cdr_results($org_page_sql, $start, $end, $limit, $offset, $where )
    {
        $results = $this->CdrsRead->query($org_page_sql);

        return $results;
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
            array_push($aryRange, 'client_cdr' . date('Ymd',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange, 'client_cdr' . date('Ymd',$iDateFrom));
            }
        }
        return $aryRange;
    }

    function sip_packet($id = null, $cli_id = null)
    {
        if($_SESSION['login_type'] == '3') {
            $this->redirect('/clients/carrier_dashboard');
        }
//        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->CdrRead->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->select_name($cli_id);
        $system_parameter = $this->CdrRead->query("SELECT is_hide_unauthorized_ip FROM system_parameter limit 1");
        $is_hide_unauthorized_ip = isset($this->params['url']['is_hide_unauthorized_ip']) ? $this->params['url']['is_hide_unauthorized_ip'] : $system_parameter[0][0]['is_hide_unauthorized_ip'];
        $this->set('is_hide_unauthorized_ip', $is_hide_unauthorized_ip);

        $this->pageTitle = "Statistics/Spam Report ";

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

        /*
          $sip_status = "";
          $cmd = "sipcapture_set_flag other";
          $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
          if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
          socket_write($socket, $cmd, strlen($cmd));
          }
          while ($out = socket_read($socket, 2048)) {
          $sip_status .= $out;
          if (strpos($out, "~!@#$%^&*()") !== FALSE) {
          break;
          }
          unset($out);
          }
          $sip_status = strstr($sip_status, "~!@#$%^&*()", TRUE);


          $this->set('sip_capture_status', $sip_status);
         *
         */

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

        //var_dump($_GET['query']['fields']);
        $process_field = "time,start_time_of_date,release_tod,origination_source_number,origination_destination_number,origination_call_id,termination_source_number,termination_destination_number,termination_call_id,orig_call_duration,origination_destination_host_name,origination_profile_port,termination_source_host_name";
        if($_SESSION['login_type'] == 3)
            $process_field = "trunk_id_origination,".$process_field;
        $this->init_query();
        //extract($this->Cdr->get_real_period());
        extract($this->get_start_end_time());
        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, $process_field));

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        $org_page_sql = $org_sql . $page_where;
//        $org_page_sql = str_replace('union all', 'intersect', $org_page_sql);
//        die(var_dump($cli_id));
//        if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['stop_date']) && !empty($_GET['stop_date'])) {
//            $datesRange = $this->createDateRangeArray($_GET['start_date'], $_GET['stop_date']);
//////            die(var_dump($datesRange));
//////            $datesRange = implode(',', $datesRange);
//////            $org_sql = str_replace('client_cdr', $datesRange, $org_sql);
////
//            $newSql = "";
//            foreach ($datesRange as $key => $date) {
//                $tmpSql = $org_page_sql;
//                $newSql .= '(' . str_replace('client_cdr', $date, $tmpSql) . ')';
//                if ($key != count($datesRange) - 1) {
//                    $newSql .= ' UNION ';
//                }
//            }
//            $org_page_sql = $newSql;
//        }
//        die(var_dump($org_page_sql));
        $this->set('show_nodata', true);

        if (isset($_GET ['query'] ['output']))
        {
            //下载
            if ($_GET ['query'] ['output'] == 'csv')
            {
                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $org_sql_replace = $replace_arr[0];
                $replace_fields_arr = $replace_arr[1];
//                pr($org_sql_replace,$show_field, $replace_field_name);die;
//                pr($start,$end,$show_field,$where);die;
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr',$replace_fields_arr));
//                pr($start,$end,$show_field,$where,$replace_fields_arr);die;
                $job_id = $this->CdrRead->download_cdr(array('file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
                if ($job_id)
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                else
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                $org_sql_replace = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_xls'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'email')
            {
                $address = $_GET['send_mail_address'];
                $replace_arr = $this->translate_cdr_field($org_sql, $show_field, $replace_field_name);
                $org_sql_replace = $replace_arr[0];
                $replace_fields_arr = $replace_arr[1];
//                pr($org_sql_replace,$show_field, $replace_field_name);die;
//                pr($start,$end,$show_field,$where);die;
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql_replace, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr',$replace_fields_arr));
//                pr($start,$end,$show_field,$where,$replace_fields_arr);die;
                $job_id = $this->CdrRead->download_cdr(array('send_mail' => $address,'file_name' => $file_name,'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where,'replay_fields_arr' => $replace_fields_arr));
                if ($job_id)
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                else
                {
                    $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
                    $this->Session->write("m", Cdr::set_validator());
                    $this->redirect('export_log');
                }
                {
                    //web显示
                    //echo $org_page_sql;die;
                    $results = $this->CdrRead->query($org_page_sql);
                    $page->setDataArray($results);
                    $this->set('p', $page);
                }
            }else{
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
                $this->set('show_nodata', false);
                $results = array();
            }
//            die(var_dump($results));
            $page->setDataArray($results);
            $this->set('p', $page);
        }
//        die(var_dump($results));
//        pr($show_field_array);die;


        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        //  $this->set('name',$this->queryName($cli_id));
        //  $this->set('cdrr_name',$this->Cdr->query("select name from client where client_id=".$this->params['pass'][1] .""));
    }


    function _upload_api($api, $file)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ch = curl_init();
        $data = array('name' => 'Foo', 'file' => "@" . $file); // '@/opt/sdfsdf.pcap'
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    function _get_file($call_id, $time,$type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::load('myconf');
        $path = Configure::read('voip_moniter.pcap_path');
        //var_dump($path);
        $last = date("Y-m-d H:i", strtotime('-1 minute', strtotime($time)));
        //$last = str_replace('-', '/', $last);
        $last = str_replace(' ', '/', $last);

        $next = date("Y-m-d H:i", strtotime('+1 minute', strtotime($time)));
        //$next = str_replace('-', '/', $next);
        $next = str_replace(' ', '/', $next);
        $current = date('Y-m-d H:i', strtotime($time));
        //$current = str_replace('-', '/', $current);
        $current = str_replace(' ', '/', $current);
        $current = str_replace(':', '/', $current);
        $next = str_replace(':', '/', $next);
        $last = str_replace(':', '/', $last);
        $fpath = "";
        //var_dump($last,$current,$next);

        $file_path = $path . $current;
        //echo $file_path;
        $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
        //echo $cmd;exit;
        $type_f = ($type == 1)?'SIP':'RTP';
        // $fpath = trim(shell_exec($cmd));
        $fpath = $file_path."/".$type_f."/".$call_id.".pcap";
//	echo $fpath;
        // echo $cmd;exit;
        if (!file_exists($fpath))
        {
            $file_path = $path . $next;
            $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
            // $fpath = trim(shell_exec($cmd));
            $fpath = $file_path."/".$type_f."/".$call_id.".pcap";
            if (!file_exists($fpath))
            {
                $file_path = $path . $last;
                $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
                // $fpath = trim(shell_exec($cmd));
                $fpath = $file_path."/".$type_f."/".$call_id.".pcap";
                //echo $fpath;
                if (!file_exists($fpath))
                {
                    $fpath = "";
                }
            }
        }
        // var_dump($fpath);
        return $fpath;
    }

    function _search_api($api, $call_id,$type)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $ch = curl_init();
        //$file_name = basename($file_name);

//        $id = $this->Cdr->query("select * from class4_call_id_cloud_shark_id_map where call_id = '{$call_id}' and type = {$type} ");
        if(!empty($id[0][0]['cloud_shark_id'])){



            $api = "{$api}?search[id][]={$id[0][0]['cloud_shark_id']}";
            //$data = array('name'=>'Foo','file'=>"@/opt/sdfsdf.pcap");// '@/opt/sdfsdf.pcap'
            curl_setopt($ch, CURLOPT_URL, $api);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        }else{
            return false;
        }
    }

    function get_sip_from_cdr_list(){
        $this->set('debug', Configure::read('debug'));
        Configure::write('debug', 0);
        $cloudSharkUrl = '108.165.2.57';
        $cloudSharkToken = 'd0c7536f5e2c8c66d9de884183ee4c4e';

        $this->set('cloudSharkUrl', $cloudSharkUrl);
        $this->set('cloudSharkToken', $cloudSharkToken);

        if (empty($this->apiUrl) || $this->apiUrl == 'http://' || !$this->is_api_connect()) {
            $this->jsonResponse(array('status' => false ,'data' =>'PCAP URL is not configured. Please check the config.' ));
        }
        $type = $this->params['form']['type'];
        $data = $this->params['form']['search'];
        $data_arr = explode('||',$data);
        $switch_ip = $data_arr[2];
        $switch_port = base64_encode($data_arr[3]);
        if ($type == 1){
            $call_id = base64_encode($data_arr[1]);
        }else{
            $call_id = base64_encode($data_arr[0]);
        }

        $start_time = $data_arr[4];
        $end_time = $data_arr[5];


        $this->params['getUrl'] = <<<STR
switch_ip=$switch_ip&switch_port=$switch_port&call_id=$call_id&start_time=$start_time&end_time=$end_time
STR;
        $this->set('url', "switch_ip=$switch_ip&switch_port=$switch_port&call_id=$call_id&start_time=$start_time&end_time=$end_time");
        $this->set('domainName', $this->getUrl());
        $this->jsonResponse(array('status' => true ,'data' =>$this->render('get_sip')));
    }

    /**
     *  通过cdr再次模拟呼叫
     */
    function get_simulate_from_cdr(){
        Configure::write('debug', 0);
        $data = $this->params['form']['search'];
        $data_arr = explode('||',$data);
        $switch_ip = $data_arr[0];
        $switch_port = $data_arr[1];
        $host = $data_arr[2];
        $port = 0;
        $ani = $data_arr[3];
        $dnis = $data_arr[4];
        $ingress_id = $data_arr[5];
        $start_time = $data_arr[6];
        $pdd = $data_arr[7];
        $this->set('start_time', $start_time ? date("Y-m-d H:i:s", $start_time/1000000) : '');
        $this->set('pdd', $pdd);
        $resource_ip_sql = "select port FROM resource_ip where resource_id = $ingress_id and ip = '$host'";
        $resource_ip_info = $this->Cdr->query($resource_ip_sql);
        if ($resource_ip_info){
            $port = $resource_ip_info[0][0]['port'];
            $this->set('ingress_host',$host.":".$port);
        }else{
            $this->set('ingress_host',$host);
        }
        $sql = "select lan_ip,lan_port FROM voip_gateway where id = (select voip_gateway_id FROM switch_profile WHERE sip_ip = '$switch_ip' AND sip_port = $switch_port)";
        $server_info = $this->Cdr->query($sql);
        $server_ip = $server_info[0][0]['lan_ip'];
        $server_port = $server_info[0][0]['lan_port'];

        $cmd = "call_simulation $host,$port,$ani,$dnis,";

        App::import("Vendor", "connect_backend", array('file' => "connect_backend.php"));
        $backend_connect = new ConnectBackend();
        if($backend_connect->get_connect($server_ip, $server_port) !== false)
        {
            if($backend_connect->send_cmd($cmd) !== false)
            {
                $content = $backend_connect->get_result();
            }
        }
        $backend_connect->close_connect();
        if (!$content)
        {
            echo $cmd."<br />";
            __('Unable to connect to the back end engine at %s and Port %s.', false, array($server_ip,$server_port));
            die;
//            $this->Productitem->create_json_array("", 101, __('Unable to connect to the back end engine at %s and Port %s.', true, array($server_ip,$server_port)));
        }
        $data = iconv('ISO-8859-1',"UTF-8//TRANSLIT",$content);

        $this->set('xdata', $data);
    }

    function get_sip(){

        $cloudSharkUrl = '108.165.2.57';
        $cloudSharkToken = 'd0c7536f5e2c8c66d9de884183ee4c4e';
        $this->set('cloudSharkUrl', $cloudSharkUrl);
        $this->set('cloudSharkToken', $cloudSharkToken);

        $_POST = $_GET;
        $sipResult = $this->ajax_get_sip();
        $sipResult = json_decode($sipResult, true);
        $msgStatus = $sipResult['self_status'] == 0 ? 101 : 201;
        if(empty($sipResult['msg'])) {
            $sipResult['msg'] = 'SIP Request created successfully!';
        }
        $this->Session->write('m', $this->SipRequest->create_json($msgStatus, $sipResult['msg']));
        $this->redirect('/cdrreports_db/sip_requests');
    }

    public function ajax_get_public_sip_download(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::load('myconf');
        if(!$this->_get('token')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no token' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('session_key')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no session key' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        $token = $this->_get('token');
        $data = array();
        $get_url = $this->apiUrl.'/api/makepublic?token='.$token . '&session_key='. $this->_get('session_key') ;
        $result_data = $this->postAPIData($get_url,json_encode($data));

        $arr = json_decode($result_data,true);
        if ($arr){
            $arr['status'] = 1;
        }else{
            $arr['status'] = 0;
        }
        return json_encode($arr);

    }

    public function sip_download(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::load('myconf');
        if(!$this->_get('token')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no token' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('session_key')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no session key' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('call_id')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no call id' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('switch_ip')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no switch ip' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        $download_path = Configure::read('database_export_path') . DS . 'pcap_download';
        $file_name = $this->_get('switch_ip').'_'.$this->_get('call_id').'.pcap';

        if (is_dir($download_path)){
            $download_file = $download_path . DS . $file_name;
        }else{
            $download_file = Configure::read('database_export_path') . DS . $file_name;
        }

        if (!file_exists($download_file)){
            $token = $this->_get('token');
            $data = array(
                'session_key' => $this->_get('session_key')
            );
            $get_url = $this->apiUrl.'/api/download?token='.$token;
            $result_data = $this->postAPIData($get_url,json_encode($data));
            if (is_null($result_data)){
//                错误
                echo __('Connect Failed!', true);die;
                $this->Session->write('m', $this->Cdr->create_json(101, __('Connect Failed!', true)));
                $this->redirect('sip_packet');
            }
            file_put_contents($download_file,$result_data);
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".basename($download_file)."\";" );
        header("Content-Transfer-Encoding: binary");
        ob_clean();
        flush();
        readfile($download_file);die;
    }

    private function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    private function getTokenData($switchIp, $clientIp)
    {
        $tokenStr = "http://{$this->siteIp}:8000/api/v1.0/show_tokens";
        $tokenResult = file_get_contents($tokenStr);
        $tokenResult = json_decode($tokenResult, true);
        $tokenData = array();
        foreach ($tokenResult['tokens'] as $item) {
            if($item['client_ip'] == $clientIp && $item['switch_ip'] == $switchIp) {
                $tokenData = $item;
            }
        }
        return $tokenData;
    }

    private function createToken($switchIp, $clientIp)
    {
        $tokenStr = "http://{$this->siteIp}:8000/api/v1.0/create_token?switch_ip=$switchIp&client_ip=$clientIp";
        file_get_contents($tokenStr);
    }

    public function refreshResult()
    {
        Configure::write('debug', 0);
        $newData = $_POST['data'];
        $queryResults = $this->checkQueryResults($newData);
        $decodedResults = json_decode($queryResults, true);
        if(empty($decodedResults['query'][0]['url']) || $decodedResults['query'][0]['url'] == 'NULL') {
            $this->jsonResponse(array('self_status' => 2, 'data' => $newData , 'tmp' => $decodedResults, 'queries' => $this->curlQueries ));
        } else {
            $this->jsonResponse(array('self_status' => 1, 'download_url' => $decodedResults['query'][0]['url'], 'data' => array('msg' => 'Succeed'), 'queries' => $this->curlQueries ));
        }

    }

    private function getNameByQueryKey($array, $queryKey)
    {
        foreach ($array as $item) {
            if($item['SipRequest']['query_key'] == $queryKey)
                return $item['SipRequest']['username'];
        }

        return '';
    }

    private function getDateByQueryKey($array, $queryKey, $queuedTime)
    {
        foreach ($array as $item) {
            if($item['SipRequest']['query_key'] == $queryKey)
                return $queuedTime;
        }

        return '';
    }

    private function getIdByQueryKey($array, $queryKey)
    {
        foreach ($array as $item) {
            if($item['SipRequest']['query_key'] == $queryKey)
                return $item['SipRequest']['id'];
        }

        return '';
    }

    private function showQueries($data)
    {
        $ch = curl_init();
        $timeout = 300;
        $dataJson = json_encode($data);
        $headers = array(
            'Content-Type: application/json'
        );
        $url = "{$this->apiUrl}/api/v1.0/show_queries";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);
        curl_close($ch);
        return $handles;
    }

    public function sip_copy()
    {
        Configure::write('debug', 0);

        if($this->RequestHandler->isGet()) {

            $prefix = uniqid('pcap');
            $filename = $prefix . '.html';
            $redirectUrl = $_GET['link'];

            $htmlOut = "<html><body><script>location.href='{$redirectUrl}';</script></body></html>";

            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/octet-stream; ");
            header("Content-Transfer-Encoding: binary");

            echo $htmlOut;
        }
        exit;
    }

    public function send_pcap()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $from = $_POST['from'];
            $email_adress = $_POST['emails'];
            $subject = $_POST['subject'];
            $content = $_POST['pcap_content'];
            $pcap_url = $_POST['pcap_url'];
            $content .= "<br><br>PCAP url: {$pcap_url}";
            $toMails = explode(';', trim($email_adress));

            $sendResult = $this->VendorMailSender->send($subject, $content, $toMails, array(0 => $from));

            if ($sendResult['status'] != 1) {
                $this->Cdr->create_json_array('#query-smartPeriod', 201, "Email was sent successfully");
            }


            $this->Session->write("m", Cdr::set_validator());
        }
        $this->redirect('/cdrreports_db/sip_requests');
    }

    public function deleteSipRequest($id)
    {
        $decodedId = base64_decode($id);
        $tmpData = $this->SipRequest->del($decodedId);
        if($tmpData) {
            $this->Session->write('m', $this->SipRequest->create_json(201, 'Request deleted successfuly!'));
        } else {
            $this->Session->write('m', $this->SipRequest->create_json(101, 'Delete failed!'));
        }
        $this->redirect('/cdrreports_db/sip_requests');
    }

    public function redoSipRequest($id)
    {
        $decodedId = base64_decode($id);
        $currentRequest = $this->SipRequest->find('first', array('conditions' => array('id' => $decodedId)));
        $currentRequest['SipRequest']['start_time'] .= 1000000;

        $newData = array(
            'switch_ip' => $currentRequest['SipRequest']['switch_ip'],
            'duration' => $currentRequest['SipRequest']['duration'],
            'start_time' => $currentRequest['SipRequest']['start_time'],
            'call_id' => base64_encode($currentRequest['SipRequest']['call_id'])
        );

        $_POST = $newData;
        $sipResult = $this->ajax_get_sip();
        $sipResult = json_decode($sipResult, true);
        $msgStatus = $sipResult['self_status'] == 0 ? 101 : 201;
        $message = 'Redone failed!';
        if(empty($sipResult['msg'])) {
            $message = 'Request redone successfully!';
        }

//        $this->checkQueryResults($newData);
//        $tmpRes = $this->SipRequest->query("UPDATE sip_requests SET date = CURRENT_TIMESTAMP WHERE id={$currentRequest['SipRequest']['id']}");
        $this->Session->write('m', $this->SipRequest->create_json($msgStatus, $message));
        $this->redirect('/cdrreports_db/sip_requests');
    }

    public function sip_requests()
    {
        if($_SESSION['login_type'] == '3') {
            // did client sip requests
            //$this->redirect('/clients/carrier_dashboard');
        }
        $timeZone = $this->Systemparam->find('first', array(
            'fields' => array('sys_timezone')
        ));

        Configure::write('debug', 2);

        if (!isset($_SESSION['sst_client_id'])) {
            $sipRequests = $this->SipRequest->find('all');
        } else {
            $sipRequests = $this->SipRequest->find('all', array(
                'conditions' => array(
                    'client_id' => $_SESSION['sst_client_id']
                )
            ));
        }

        $client_ip = $this->siteIp;
        $data = array(
            'switch_ip' => $client_ip
        );

        $requestQueries = $this->showQueries($data);
//        die(var_dump($this->apiUrl));
        $requestQueries = json_decode($requestQueries, true);
        $requestQueries = $requestQueries['query'];
        $resultQueries = array();
        if(!empty($requestQueries)) {

            foreach ($requestQueries as $key => &$requestQuery) {
                $requestQuery['db_id'] = $this->getIdByQueryKey($sipRequests, $requestQuery['query_key']);

                if (!$requestQuery['db_id']) {
                    unset($requestQueries[$key]);
                } else {
                    $requestQuery['username'] = $this->getNameByQueryKey($sipRequests, $requestQuery['query_key']);
                    $requestQuery['queued_time'] = $this->getDateByQueryKey($sipRequests, $requestQuery['query_key'], $requestQuery['queued_time']);

                    if (!empty($requestQuery['queued_time'])) {
                        $tmpTime = strtotime($requestQuery['queued_time']);
                        $requestQuery['queued_time'] = date('Y-m-d H:i:s', $tmpTime) ." " . $timeZone['Systemparam']['sys_timezone'];
                    }
                    if (empty($requestQuery['status'])) {
                        $requestQuery['msg'] = 'Waiting';
                    }
                    if (!empty($requestQuery['complete_time'])) {
                        $tmpTime = strtotime($requestQuery['complete_time']);
                        $requestQuery['complete_time'] = date('Y-m-d H:i:s', $tmpTime) . " " . $timeZone['Systemparam']['sys_timezone'];
                    }

                    if (isset($requestQuery['url'])) {
                        $url_array = parse_url($requestQuery['url']);
//                    $requestQuery['segment'] = $url_array['scheme'].':// || '.$requestQuery['switch_ip'].' || '.':'.$url_array['port'].' || '.$url_array['path'];
                        $requestQuery['scheme'] = $url_array['scheme'];
                        $requestQuery['segment'] = $requestQuery['url'];
                    }

                    // if (!empty($requestQuery['db_id']) && $requestQuery['db_id'] != FALSE) {
                    array_push($resultQueries, $requestQuery);
                    // }
                }
            }
        }
        $requestQueries = array_reverse($resultQueries);

        $tempArray = array();
        foreach ($requestQueries as $key => $row)
        {
            $tempArray[$key] = $row['queued_time'];
        }
        array_multisort($tempArray, SORT_ASC, $requestQueries);

        if($_SESSION['login_type'] == 3) {
            $resultQueries = array();
            $username = $_SESSION['sst_user_name'];
            foreach ($requestQueries as $requestQuery) {
                if($requestQuery['username'] == $username) {
                    array_push($resultQueries, $requestQuery);
                }
            }
            $requestQueries = $resultQueries;
        }
        $cloudSharkUrl = '108.165.2.57';

        $cloudSharkToken = 'd0c7536f5e2c8c66d9de884183ee4c4e';

        $this->loadModel('Mailtmp');
        $this->set('mailSenders', $this->Mailtmp->find_mail_senders());
        $this->set('requestQueries', $requestQueries);
        $this->set('cloudSharkUrl', $cloudSharkUrl);
        $this->set('cloudSharkToken', $cloudSharkToken);
        $this->set('domainName', $this->getUrl());
    }

    public function get_pcap_file(){
        Configure::write('debug', 0);
        $download_path = Configure::read('database_export_path') . DS . 'cdr_download';
        $file_name = 'Pcap_'.date('Y_m_d_H_i_s').'.pcap';

        if (is_dir($download_path)){
            $download_file = $download_path . DS . $file_name;
        }else{
            $download_file = Configure::read('database_export_path') . DS . $file_name;
        }

        $result_data = false;
        if (isset($_GET['segment'])) {
            $segment = base64_decode($_GET['segment']);
            $url = implode('', explode(' || ', $segment));
            $result_data = file_get_contents($url);
        }
        
        if ($this->isJson($result_data)) {
            $decodedData = json_decode($result_data, true);

            if (isset($decodedData['error'])) {
                $this->Session->write('m', $this->Cdr->create_json(101, $decodedData['error']));
                $this->redirect('/cdrreports_db/sip_requests');
            }
        }

        if (!$result_data){
            $this->Session->write('m', $this->Cdr->create_json(101, __('Download PCAP file failed!', true)));
            $this->redirect('/cdrreports_db/sip_requests');
        }
        file_put_contents($download_file,$result_data);
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$file_name."\";" );
        header("Content-Transfer-Encoding: binary");
        ob_clean();
        flush();
        readfile($download_file);die;
    }

    /**
     * @return string
     */
    function ajax_get_sip(){
        Configure::write('debug', 0);
        Configure::load('myconf');

        $this->autoRender = false;
        $this->autoLayout = false;

        if (empty($this->apiUrl) || $this->apiUrl == 'http://') {
            return json_encode(array('self_status' => 0 ,'msg' =>'PCAP URL is not configured. Please check the config.' ));
        }
        if(!$_POST['call_id']){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no call id' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$_POST['switch_ip']){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0, 'msg' =>'no switch ip' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$_POST['start_time']){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no start time' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }

        $start_time = date('Y-m-d H:i:s', strtotime($_POST['start_time']));
        $call_id = base64_decode($_POST['call_id']);
        $switch_ip = $_POST['switch_ip'];
        $duration = $_POST['duration'];
        $data = array(
            'start' => $start_time,
            'callid' => $call_id,
            'switch_ip' => $switch_ip,
            'duration' => $duration?: 0
        );
        $query_url = "{$this->apiUrl}/api/v1.0/create_query";
        $result_data = $this->postAPIData($query_url, $data);
        $arr = json_decode($result_data,true);

        if(isset($arr['error'])) {
            return json_encode(array('self_status' => 0, 'msg' => $arr['error'], 'queries' => $this->curlQueries ));
        }

        $requestData = array(
            'username'   => $_SESSION['sst_user_name'],
            'query_key'  => $arr['query_key'],
            'switch_ip'  => $switch_ip,
            'call_id'    => $call_id,
            'duration'   => $duration,
            'start_time' => intval($_POST['start_time']),
            'date'       => date('Y-m-d H:i:s'),
            'client_id'  => $_SESSION['sst_client_id']
        );

        $this->SipRequest->save($requestData);

        $newData = array(
            'switch_ip' => $data['switch_ip'],
            'query_key' => $arr['query_key']
        );
        $queryResults = $this->checkQueryResults($newData);
        $decodedResults = json_decode($queryResults, true);
        if(!isset($decodedResults['query']['url']) || (isset($decodedResults['query']['url']) && (empty($decodedResults['query']['url']) || $decodedResults['query']['url'] == null))) {
            return json_encode(array('self_status' => 2, 'data' => $newData, 'queries' => $this->curlQueries ));
        } else {
            return json_encode(array('self_status' => 1, 'data' => array('msg' => 'Succeed'), 'download_url' => $decodedResults['query']['url'], 'queries' => $this->curlQueries ));
        }
        return json_encode(array());
    }

    private function checkQueryResults($data)
    {
        $ch = curl_init();
        $timeout = 300;
        $headers = array(
            'Content-Type: application/json'
        );
        $dataJson = json_encode($data);
        $url = "{$this->apiUrl}/api/v1.0/show_query";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);

        array_push($this->curlQueries, array(
            'request'  => $dataJson . $url,
            'response' => $handles
        ));
        curl_close($ch);
        return $handles;
    }

    function postAPIData($url, $data)
    {
        $ch = curl_init();
        $timeout = 300;
        $headers =  array(
            'Content-Type: application/json'
        );
        $dataJson = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);

        array_push($this->curlQueries, array(
            'request'  => $dataJson . $url,
            'response' => $handles
        ));
        curl_close($ch);
        return $handles ? $handles : json_encode(array('error' => 'Could not establish connection'));
    }


    function get_sip_bak($id,$pcap_type, $type = 'ingress'){
        Configure::load('myconf');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($id);
        $time = $_GET['time'];

        $duration = $_GET['duration']?$_GET['duration']:0;
        $switch_ip = $_GET['switch_ip'];


        $url = Configure::read('cloud_shark.view_api');
        $cloud_api = Configure::read('cloud_shark.cloud_api');
        if($type == 'ingress'){
            $origination_call_id = base64_decode($_GET['origination_call_id']);
            $call_ids = $origination_call_id;
        }else {
            $termination_call_id = base64_decode($_GET['termination_call_id']);
            $call_ids = $termination_call_id;
        }

        $url = $url.$cloud_api.$switch_ip.'/'.$call_ids;

        $header = get_headers($url,1);
        if (strpos($header[0],'301') || strpos($header[0],'302')) {
            if(is_array($header['Location'])) {
                $url = $header['Location'][count($header['Location'])-1];
            }else{
                $info = $header['Location'];
            }
        }

        if(!empty($info)){
            $url = Configure::read('cloud_shark.wireshark').$info;
        }else{
            echo "File not found";exit;
        }

        Header("Location: " . $url);
        exit;
//        $id = $this->Cdr->query("select * from class4_call_id_cloud_shark_id_map where call_id = '{$call_ids}' and type = {$pcap_type} ");
        if(!empty($id[0][0]['cloud_shark_id'])){
            $cloud_shark_id = $id[0][0]['cloud_shark_id'];
        }else{
            $cloud_shark_id="";
        }

        $request = array(
            '0'=>'time='.base64_encode($time),
            '1'=>'call_id='.base64_encode($call_ids),
            '2'=>'type='.base64_encode($pcap_type),
            '3'=>'cloud_shark_id='.$cloud_shark_id,
            '4'=>'duration='.base64_encode($duration),
            '5'=>'switch_ip='.base64_encode($switch_ip)

        );

        $request_1 = array(
            '0'=>'start_time='.base64_encode($time),
            '1'=>'caller_id='.base64_encode($call_ids),
            '2'=>'duration='.base64_encode($duration),
            '3'=>'switch_ip='.base64_encode($switch_ip)
        );


        $url_c = implode('&',$request);
        $url_c = "{$cloud_api}?".$url_c;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url_c);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($data);

        if(!empty($res->status) && $res->status == 'yes'){
            $url_c = implode('&',$request_1);
            Header("Location:{$url}?$url_c ");
        }else{
            echo "Can not Connection to Central System.";
        }
        exit;

        //var_dump($url_c,$res);

//        if(!empty($res->status) && $res->status == 'yes' && !empty($res->id)){
//
//            if(!empty($res->msg) && !empty($res->id) && $res->msg == 'not_has'){
//                //echo "insert ";
//                $this->Cdr->query("insert into class4_call_id_cloud_shark_id_map (call_id,cloud_shark_id,type) values ('{$call_ids}','{$res->id}',{$pcap_type})");
//            }else{
//                //echo "no insert";
//            }
//            Header("Location:{$url}{$res->id} ");
//        }else{
//            echo __("not_find_pcap");
//        }
    }

    function _get_sip_old($id,$pcap_type, $type = 'ingress')
    {
        Configure::load('myconf');
        Configure::write('debug', 2);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($id);
        $time = $_GET['time'];
        //var_dump($id,$time,$time1);exit;
//echo " select  *  from client_cdr  where time = '{$time}' and id = {$id}  ";exit;
        // $res = $this->Cdr->query(" select  *  from client_cdr  where time = '{$time}' and id = {$id}  ");
        if ($type == 'ingress')
        {
            $origination_call_id =base64_decode($_GET['origination_call_id']);
            $call_ids = $origination_call_id;
        }
        else
        {
            $termination_call_id = base64_decode($_GET['termination_call_id']);
            $call_ids = $termination_call_id;
        }

//	$res = $this->_search_api($search_api, $call_ids,$pcap_type);
        $url = Configure::read('cloud_shark.view_api');
        $api = Configure::read('cloud_shark.upload_api');
        $search_api = Configure::read('cloud_shark.search_api');
        $res = $this->_search_api($search_api, $call_ids,$pcap_type);
        $res = json_decode($res);

        if (!empty($res->captures[0]->id))
        {
            Header("Location:{$url}{$res->captures[0]->id} ");
        }
        else
        {
            if ($type == 'ingress')
            {
                $origination_call_id =base64_decode($_GET['origination_call_id']);
                $file = $this->_get_file($origination_call_id, $time,$pcap_type);
                $call_ids = $origination_call_id;
            }
            else
            {
                $termination_call_id = base64_decode($_GET['termination_call_id']);
                $file = $this->_get_file($termination_call_id, $time,$pcap_type);
                $call_ids = $termination_call_id;
            }
            if (file_exists($file))
            {
                $res = $this->_upload_api($api, $file);
                $res_error = $res;
                $res = json_decode($res);
                if (!empty($res->id))
                {
//                    $this->Cdr->query("insert into class4_call_id_cloud_shark_id_map (call_id,cloud_shark_id,type) values ('{$call_ids}','{$res->id}',{$pcap_type})");
                    Header("Location:{$url}{$res->id} ");
                }
                else
                {
                    echo $res_error;
                }
            }
            else
            {
                echo __("not_find_pcap");
            }
        }
    }

    function get_result()
    {
        Configure::write('debug', 0);

        $call_id = $this->_post('call_id');
        $time = $this->_post('time');

        $res = $this->Cdr->query("select egress_id,(select alias from resource where resource_id = egress_id) as egress_name,origination_call_id  from client_cdr".date("Ymd")." where time = '{$time}' and origination_call_id = '{$call_id}' and is_final_call = 0 ");

        $result = array();
        if (count($res) != 0)
        {
            $result = $res[0];
        }
        $this->set('results', $result);
        //echo json_encode($result);
    }

    function consolidated_cdr($id = null, $cli_id = null)
    {

//        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->select_name($cli_id);

        $this->pageTitle = "Statistics/Spam Report ";

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

        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz']))
        {

            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        }
        else
        {
            #report deault query time
            extract($this->Cdr->get_real_period());
        }

        //$this->init_query();
        //extract($this->Cdr->get_real_period());
        //extract($this->get_start_end_time());
        $this->set("report_type", $report_type);
        //extract($this->get_datas($report_type, ''));
        // extract($this->Cdr->get_real_period());

        $order = $this->capture_report_order_1();


        /*$release_cause = "case  release_cause
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
                when   48 then 'Allowed Send To IP Failed'
                when   52 then 'Switch Profile CAP Limit Exceeded'
                when   53 then 'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause";*/
        $release_cause = " release_cause ";




        $show_field = "time,(select alias from resource where resource_id = ingress_id) as trunk_id_origination ,origination_call_id,origination_destination_number,origination_source_number,{$release_cause},(select alias from resource where resource_id = egress_id) as egress_id";
        $show_field_array = array(
            'time', 'trunk_id_origination', 'origination_call_id', 'origination_destination_number', 'origination_source_number', 'release_cause', 'egress_id'
        );

        $show_field_left = "ingress_id,egress_id,release_cause,time,origination_call_id,origination_destination_number,origination_source_number";

        $this->set('show_field_array', $show_field_array);

        //$org_sql = "select $show_field  from   client_cdr      where  time  between  '$start'  and  '$end' and is_final_call = 1   ";

        $this->set("start", $start);
        $this->set("end", $end);



        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];




        $sst_user_id = $_SESSION['sst_user_id'];


        //分表
        //生成日期数组
//        pr($end);
        $time_data = $this->_get_date_result_admin($start, $end, 'client_cdr2%');
//        pr($time_data);exit;

        $sql = "";
        foreach($time_data as $key=>$value){
            $table_name = "client_cdr".$value;

            $union = "";
            if(!empty($sql)){
                $union = " union all ";
            }else {
                $union = '';
            }

            $sql .= <<<EOD
                        {$union}  select $show_field_left  from
                        {$table_name} where
                        time  between  '$start'  and  '$end' and is_final_call = 1
EOD;







            /* $org_sql = "select time,origination_call_id,origination_destination_number,origination_source_number,case release_cause when 0 then 'Unknown Exception'
              when 1 then 'System CPS Limit Exceeded' when 2 then 'SYSTEM_CPS System Limit Exceeded' when 3 then 'Unauthorized IP Address'
              when 4 then ' No Ingress Resource Found' when 5 then 'No Product Found ' when 6 then 'Trunk Limit Call Exceeded'
              when 7 then 'Trunk Limit CPS Exceeded' when 8 then 'IP Limit CAP Exceeded' when 9 then 'IP Limit CPS Exceeded '
              when 10 then 'Invalid Codec Negotiation' when 11 then 'Block due to LRN' when 12 then 'Ingress Rate Not Found'
              when 13 then ' Egress Trunk Not Found' when 14 then 'Egress Returns 404' when 15 then 'Egress Returns 486' when 16
              then 'Egress Returns 487' when 17 then 'Egress Returns 200' when 18 then 'All Egress Unavailable' when 19 then 'Normal hang up'
              when 20 then 'Ingress Resource disabled' when 21 then 'Zero Balance' when 22 then 'No Route Found' when 23 then 'Invalid Prefix'
              when 24 then 'Ingress Rate Missing' when 25 then 'Invalid Codec Negotiation' when 26 then 'No Codec Found' when 27
              then 'All Egress Failed' when 28 then 'LRN Response Missing' when 29 then 'Carrier Call Limit Exceeded' when
              30 then 'Carrier CPS Limit Exceeded' when 31 then 'Rejected Due to Host Alert' when 32 then 'Rejected Due to Trunk Alert'
              when 33 then 'H323 Not Supported' when 34 then '180 Negotiation Failure' when 35 then '183 Negotiation Failute' when 36
              then '200 Negotiation Failure' when 37 then 'Block LRN with Higher Rate' when 38 then 'Ingress Block ANI' when 39
              then 'Ingress Block DNIS' when 40 then 'Ingress Block ALL' when 41 then 'Global Block ANI' when 42 then 'Global Block DNIS'
              when 43 then 'Global Block ALL' when 44 then 'T38 Reject' else 'other' end as release_cause
              from
              client_cdr where time between '2014-10-20 01:00:00 +00' and '2014-10-26 00:59:59 +00'
              and is_final_call = 1 "; */







            if ($_SESSION ['login_type'] == 3)
            {
                $filter_client = '';
            }
            else
            {
//                $sql .= " and
//                (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists
//                (SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
//                and (role_name = 'admin' or sys_role.view_all = true)))";
                $filter_sql = $this->get_user_limit_filter();
                $sql .= $filter_sql;
            }
        }



        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";

        $org_page_sql = $sql  . $page_where;


        $org_sql = <<<EOD
select time,
(select alias from resource where resource_id = ingress_id) as trunk_id_origination ,
origination_call_id,origination_destination_number,origination_source_number,
case  release_cause
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
                when   48 then 'Allowed Send To IP Failed'
                when   52 then 'Switch Profile CAP Limit Exceeded'
                when   53 then 'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause,
		(select alias from resource where resource_id = egress_id) as egress_id from(
		$sql
		) as tmp
EOD;


        $org_page_sql = <<<EOD
select time,
(select alias from resource where resource_id = ingress_id) as trunk_id_origination ,
origination_call_id,origination_destination_number,origination_source_number,
case  release_cause
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
                when   48 then 'Allowed Send To IP Failed'
                when   52 then 'Switch Profile CAP Limit Exceeded'
                when   53 then 'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause,
		(select alias from resource where resource_id = egress_id) as egress_id from(
		$org_page_sql
		) as tmp
EOD;




//pr($org_sql);exit;


        $this->set('show_nodata', true);

        if (isset($_GET ['query'] ['output']))
        {
            //下载
            $file_name = $this->create_doload_file_name('cdr', $start, $end);

            if ($_GET ['query'] ['output'] == 'csv')
            {
                //Configure::write('debug',0);
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $show_field, 'where' => $where));
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'email')
            {

            }
            else
            {
                //web显示
                //echo $org_page_sql;die;
//                pr($org_page_sql);die;
                $results = $this->Cdr->query($org_page_sql);
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
                $results = $this->Cdr->query($org_page_sql);
            }
            else
            {
                $this->set('show_nodata', false);
                $results = array();
            }
            $page->setDataArray($results);
            $this->set('p', $page);
        }


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
                $mailer->SMTPSecure = 'tls';
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
        $this->redirect('/cdrreports_db/mail_send_log');
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
        if (!$_SESSION['role_menu']['Tools']['cdrreports_db:rerating']['model_x'])
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
            $this->redirect("/cdrreports_db/rerating_list");
        }
        $this->set('sip_capture_status', 'off');

        $this->set("action_type", $action_type);

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;


        require_once 'MyPage.php';
        $page = new MyPage ();
        $max_limit = 1000;
        if (isset($_GET ['query'] ['output']))
        {
            $page->setTotalRecords($max_limit); //总记录数
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
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            }
            elseif ($_GET ['query'] ['output'] == 'xls')
            {
                //xls down
                Configure::write('debug', 0);
                $this->layout = 'csv';
                $this->_catch_exception_msg(array('CdrreportsDbController', '_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));
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

    function _download_impl2($params = array())
    {
        //Configure::write('debug', 0);
        extract($params);
        $job_id = $this->Cdr->download_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name, 'start' => $start, 'end' => $end, 'fields' => $fields, 'where' => $where));
        if ($job_id)
        {
            //exit(1);
            $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] of exports of CDRs between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/cdrreports_db/export_log');
        }
    }

    function _download_impl($params = array())
    {
//        Configure::write('debug', 0);
        extract($params);
        $address = isset($send_mail) ? $send_mail : '';
        $job_id = $this->Cdr->download_by_sql($download_sql, array('objectives' => 'client_cdr', 'file_name' => $file_name, 'start' => $start, 'end' => $end,'send_mail' => $address));
        if ($job_id)
        {
            //exit(1);
            $this->Cdr->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] export of CDR between %s and %s is being performed successfully!', true, array($job_id, $start, $end)));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/cdrreports_db/export_log');
        }
        else
        {
            $this->Cdr->create_json_array('#query-smartPeriod', 101, __('The job failed!', true));
            $this->Session->write("m", Cdr::set_validator());
            $this->redirect('/cdrreports_db/summary_reports');
        }
    }

    function export_log()
    {
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CdrExportLog.id' => 'desc',
            ),
        );

        if ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3) {
            $this->paginate['conditions'] = array('user_id' => $_SESSION['sst_user_id']);
        }
//        if (isset($_SESSION['sst_client_id']))
//        {
//            $this->paginate['conditions'] = array('user_id' => $_SESSION['sst_client_id']);
//        }
//        elseif ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3)
//        {
//            $this->paginate['conditions'] = array('user_id' => $_SESSION['sst_user_id']);
//        }

        if (isset($this->params['url']['time']) && $this->params['url']['time'] && isset($this->params['url']['end_time']) && $this->params['url']['end_time'])
        {
            $this->paginate['conditions'][] = "export_time >= '" . $this->params['url']['time'] . "'";
            $this->paginate['conditions'][] = "export_time <= '" . $this->params['url']['end_time'] . "'";
        }


        $this->set('get_data', $this->params['url']);


        $switch_profiles = array();
        $sql = "select switch_name,profile_name from switch_profile";
        $result = $this->CdrExportLog->query($sql);
        foreach ($result as $item)
        {
            if (isset($switch_profiles[$item[0]['switch_name']]))
            {
                $switch_profiles[$item[0]['switch_name']] .= ",{$item[0]['profile_name']}";
            }
            else
            {
                $switch_profiles[$item[0]['switch_name']] = "{$item[0]['profile_name']}";
            }
        }

        $this->set("switch_profilers", $switch_profiles);

        $status = array("Waiting", "In Progress", "Query", "Compressing", "Done", "Canceled",'Stopping',-1 => 'Error',-2 => 'Stop');
        $this->set('status', $status);
//        die(var_dump($this->paginate));
        $this->data = $this->paginate('CdrExportLog');
//        die(var_dump($this->data));
        $this->_get_cdr_email_template();
    }

    public function export_download_error()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $cdr_export_log = base64_decode($this->params['url']['key']);
        $data = $this->CdrExportLog->find('first',array(
            'fields' => 'error_msg',
            'conditions' => array(
                'id' => $cdr_export_log
            ),
        ));
        $error_msg = $data['CdrExportLog']['error_msg'];
        $filename = "cdr_download_error.log";
        $file_path = realpath(ROOT . '/../download/cdr_download') ."/". $filename;
        file_put_contents($file_path,$error_msg);
        $filename = basename(($filename));

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        else if (preg_match("/Firefox/", $ua))
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        else
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        @readfile($file_path);
    }

    public function get_process_info()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $cdr_export_log = base64_decode($this->params['url']['key']);
        $data = $this->CdrExportLog->find('first',array(
            'fields' => 'file_name',
            'conditions' => array(
                'id' => $cdr_export_log
            ),
        ));
        $filename = $data['CdrExportLog']['file_name'] . '.progress';
        $file_path = realpath(ROOT . '/../download/cdr_download') ."/". $filename;
        $filename = basename(($filename));

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        else if (preg_match("/Firefox/", $ua))
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        else
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        @readfile($file_path);
    }

    public function export_log_stop($is_dipping = '')
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $conditions = array('id' => $id);
        if ($this->Session->read('login_type') == 2)
            $conditions['user_id'] = $this->Session->read('sst_user_id');
        $log = $this->CdrExportLog->find('first',array(
            'conditions' => $conditions,
        ));
        if (empty($log))
            $this->redirect_denied();
        $log['CdrExportLog']['status'] = 6;
        $this->CdrExportLog->save($log);
        $this->CdrExportLog->create_json(201, __('stop_download_cdr_job[%s]', true, $id));
        $this->Session->write("m", CdrExportLog::set_validator());
        if ($is_dipping)
        {
            $this->redirect('/dips/export_log');
        }
        $this->redirect('/cdrreports_db/export_log');
    }


    public function export_log_kill($is_dipping = '')
    {
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $conditions = array('id' => $id);
        if ($this->Session->read('login_type') == 2)
            $conditions['user_id'] = $this->Session->read('sst_user_id');
        $log = $this->CdrExportLog->find('first',array(
            'conditions' => $conditions,
        ));
        if (empty($log))
            $this->redirect_denied();
        $backend_pid = (int) $log['CdrExportLog']['backend_pid'];
        if ($backend_pid > 0)
        {
            $cmd = "kill -9 $backend_pid";
            shell_exec($cmd);
        }
        $log['CdrExportLog']['status'] = 5;
        $this->CdrExportLog->save($log);
        $this->CdrExportLog->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] is canceled successfully!', true, $id));
        $this->Session->write("m", CdrExportLog::set_validator());
        if ($is_dipping)
        {
            $this->redirect('/dips/export_log');
        }
        $this->redirect('/cdrreports_db/export_log');
    }

    public function export_log_restart()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $log = $this->CdrExportLog->findById($id);
        $log['CdrExportLog']['status'] = 0;
        $this->CdrExportLog->save($log);
        $this->CdrExportLog->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] is restarted successfully!', true, $id));
        $this->Session->write("m", CdrExportLog::set_validator());
        $this->redirect('/cdrreports_db/export_log');
    }

    public function export_log_down()
    {
        Configure::write('debug', 0);
//        Configure::load('myconf');
        $this->autoLayout = false;
        $this->autoRender = false;
        $key = $this->params['key'];
        if (!$key){
            $this->Session->write('m', $this->CdrExportLog->create_json(101, __('Illegal operation',true)));
            $this->redirect('export_log');
        }
        $key_str = base64_decode($key);
        $encode_id = substr($key_str,strpos($key_str,'=')+1);
        $id = base64_decode($encode_id);
        $conditions = array('id' => $id);
        if ($this->Session->read('login_type') == 2)
            $conditions['user_id'] = $this->Session->read('sst_user_id');
        $log = $this->CdrExportLog->find('first',array(
            'conditions' => $conditions,
        ));
        if (empty($log))
            $this->redirect_denied();
        $filename = $log['CdrExportLog']['file_name'].'.zip';
//        $filename = str_replace('.csv','.tar.bz2',$log['CdrExportLog']['file_name']);
        $file_path = realpath(ROOT . '/../download/cdr_download') ."/". $filename;
        if (!file_exists($file_path)){
            $filename = $log['CdrExportLog']['file_name'].'.bz2';
            $file_path = realpath(ROOT . '/../download/cdr_download') ."/". $filename;
        }

//        $filename = basename(($file_name));

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
        ob_clean();
        flush();
        $x_sendfile_supported = in_array('mod_xsendfile', apache_get_modules());
        if (file_exists($file_path) && !headers_sent() && $x_sendfile_supported)
        {
            header("X-Sendfile: $file_path");
        }
        else
        {
            @readfile($file_path);
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
        );
        $this->set('status', $status);
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
        $page->setTotalRecords(1000); //总记录数
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
        $route_id = "(select route_id from route where route_strategy_id = client_cdr.route_plan and 
            (static_route_id = client_cdr.static_route or (static_route_id is null and client_cdr.static_route is null)) and
            (dynamic_route_id = client_cdr.dynamic_route or (dynamic_route_id is null and client_cdr.dynamic_route is null)) 
            limit 1) as route_id";
//        $commission = " ingress_client_cost/(1+tax) as commission ";

        /*$release_cause = "case  release_cause

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
                when   48 then 'Allowed Send To IP Failed'
                when   52 then 'Switch Profile CAP Limit Exceeded'
                when   53 then 'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause";*/
        $release_cause = " release_cause ";
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
                $t_arr[$key] = "case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end as answer_time_of_date";
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
                $t_arr[$key] = "(select alias from resource where resource_id = egress_id and egress = true limit 1) as egress_id";
            }

            if ($value == "ingress_id")
            {
                $t_arr[$key] = "(select alias from resource where resource_id = ingress_id and ingress = true limit 1) as ingress_id";
            }

            if ($value == "egress_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = egress_rate_table_id and egress_rate_table_id is not null) as  egress_rate_table_id";
            }


            if ($value == "ingress_client_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = ingress_client_rate_table_id and ingress_client_rate_table_id is not null) as  ingress_client_rate_table_id";
            }

            if ($value == "ingress_client_currency_id")
            {
                $t_arr[$key] = "(select code from currency where currency_id = ingress_client_currency_id and ingress_client_currency_id is not null) as ingress_client_currency_id";
            }

            if ($value == "ingress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = ingress_client_id and ingress_client_id is not null) as ingress_client_id";
            }

            if ($value == "egress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = egress_client_id and egress_client_id is not null) as egress_client_id";
            }

            if ($value == "route_plan")
            {
                $t_arr[$key] = "(select name from route_strategy where route_strategy_id =  route_plan) as route_plan";
            }
            if ($value == "dynamic_route")
            {
                $t_arr[$key] = "(select name from dynamic_route where dynamic_route_id =  dynamic_route) as dynamic_route";
            }
            if ($value == "static_route")
            {
                $t_arr[$key] = "(select name from product where product_id = static_route) as static_route";
            }

            if ($value == "ingress_dnis_type")
            {
                $t_arr[$key] = "case ingress_dnis_type when '0' then 'dnis' when '1' then 'lrn' when '2' then 'lrn block' end as ingress_dnis_type";
            }
            if ($value == 'lrn_number_vendor')
            {
                $t_arr[$key] = "case lrn_number_vendor when 1 then 'client' when 2 then 'lrn server' when 3 then 'cache' when 0 then 'dnis' else 'others' end as lrn_number_vendor";
            }
            if ($value == 'release_cause')
            {
                $t_arr[$key] = $release_cause;
            }

//            if ($value == 'commission')
//            {
//                $t_arr[$key] = $commission;
//            }

            if ($value == 'ingress_rate_type')
            {
                $t_arr[$key] = "(case ingress_rate_type when 1 then 'inter' when 2 then 'intra' when 4 then 'error' when 5 then 'local' else 'others' end) as ingress_rate_type";
            }
            if ($value == 'egress_rate_type')
            {
                $t_arr[$key] = "(case egress_rate_type when 1 then 'inter' when 2 then 'intra'  when 4 then 'error' when 5 then 'local' else 'others' end) as egress_rate_type";
            }
//            if ($value == 'route_id')
//            {
//                $t_arr[$key] = $route_id;
//            }

            if (isset($_GET['currency']) && !empty($_GET['currency']))
            {
                $sql = "SELECT rate FROM currency_updates WHERE currency_id = {$_GET['currency']}";
                $cur_info = $this->Cdr->query($sql);
                $rate = $cur_info[0][0]['rate'];
                if ($value == 'egress_cost')
                {
                    $t_arr[$key] = "round(egress_cost / (SELECT rate FROM currency_updates WHERE currency_id = egress_client_currency_id) * {$rate}, 5) as egress_cost";
                }
                if ($value == 'egress_rate')
                {
                    $t_arr[$key] = "round(egress_rate / (SELECT rate FROM currency_updates WHERE currency_id = egress_client_currency_id) * {$rate}, 5) as egress_rate";
                }
                if ($value == 'ingress_client_cost')
                {
                    $t_arr[$key] = "round(ingress_client_cost / (SELECT rate FROM currency_updates WHERE currency_id = ingress_client_currency_id) * {$rate}, 5) as ingress_client_cost";
                }
                if ($value == 'ingress_client_rate')
                {
                    $t_arr[$key] = "round(ingress_client_rate / (SELECT rate FROM currency_updates WHERE currency_id = ingress_client_currency_id) * {$rate}, 5) as ingress_client_rate";
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
     *
     * @param type $org_sql                         old sql
     * @param type $show_field
     * @param type $replace_field_name
     * @return type         translated sql
     *
     *
     */
    private function translate_cdr_field($org_sql, $show_field, $replace_field_name)
    {
        $show_field_arr = explode(",", $show_field);
//        pr($show_field_arr);
//        pr($replace_field_name);
        foreach ($show_field_arr as $show_field_item)
        {
            $show_field_item = trim($show_field_item);
            pr(array_key_exists($show_field_item, $replace_field_name),$show_field_item);
            if (!strcmp($show_field_item,'ingress_id as ingress_name')){
                $show_field_replace_arr[] = "(select alias from resource where resource_id = ingress_id) as \"Ingress Alias\"";
            }elseif (!strcmp($show_field_item,'egress_id as egress_name')){
                $show_field_replace_arr[] = "(select alias from resource where resource_id = egress_id) as \"Egress Alias\"";
            }elseif (strpos($show_field_item,' as ') !== false){
                $tmp_data = explode(' as ',$show_field_item);

//                CLAS-1167
                switch ($tmp_data[1]){
                    case 'start_time_of_date':
                    case 'release_tod':
                    case 'time':
                        $tmp_data[0] .= '::timestamp without time zone';
                        $show_field_item =  $tmp_data[0] . ' as ' .  $tmp_data[1];
                        break;
                    case 'answer_time_of_date':
                        $tmp_data[0] = 'case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000)::timestamp without time zone end';
                        $show_field_item =  $tmp_data[0] . ' as ' .  $tmp_data[1];
                        break;
                }

                if (array_key_exists($tmp_data[1], $replace_field_name)){
                    $show_field_replace_arr[] = "{$tmp_data[0]} as \"{$replace_field_name[$tmp_data[1]]}\"";
                }else{
                    $show_field_replace_arr[] = $show_field_item;
                }
            }else{
                if (array_key_exists($show_field_item, $replace_field_name))
                {
                    if ($show_field_item == 'time'){
                        $show_field_replace_arr[] = "{$show_field_item}::timestamp without time zone as \"{$replace_field_name[$show_field_item]}\"";
                    }elseif($show_field_item == 'release_cause'){
                        $show_field_replace_arr[] = <<<RELEASE
CASE release_cause WHEN
0 then  'Invalid Argument' When
1 then  'System CAP Limit Exceeded' When
2 then  'System CPS Limit Exceeded' When
3 then  'Unauthorized IP Address' When
4 then  'No Ingress Resource Found' When
5 then  'No Product Found' When
6 then  'Trunk CAP Limit Exceeded' When
7 then  'Trunk CPS Limit Exceeded' When
8 then  'IP CAP Limit Exceeded' When
9 then  'IP CPS Limit Exceeded' When
10 then  'Invalid Codec Negotiation' When
11 then  'Block due to LRN' When
12 then  'Ingress Rate Not Found' When
13 then  'Egress Trunk Not Found' When
14 then  'From egress response 404' When
15 then  'From egress response 486' When
16 then  'From egress response 487' When
17 then  'From egress response 200' When
18 then  'All egress not available' When
19 then  'Normal' When
20 then  'Ingress Resource disabled' When
21 then  'Balance Use Up' When
22 then  'No Routing Plan Route' When
23 then  'No Routing Plan Prefix' When
24 then  'Ingress Rate No configure' When
25 then  'Invalid Codec Negotiation' When
26 then  'No Codec Found' When
27 then  'All egress no confirmed' When
28 then  'LRN response no exist DNIS' When
29 then  'Carrier CAP Limit Exceeded' When
30 then  'Carrier CPS Limit Exceeded' When
31 then  'Host Alert Reject' When
32 then  'Resource Alert Reject' When
33 then  'Resource Reject H323' When
34 then  '180 Negotiation SDP Failed' When
35 then  '183 Negotiation SDP Failed' When
36 then  '200 Negotiation SDP Failed' When
37 then  'LRN Block Higher Rate' When
38 then  'Ingress Block ANI' When
39 then  'Ingress Block DNIS' When
40 then  'Ingress Block ALL' When
41 then  'Global Block ANI' When
42 then  'Global Block DNIS' When
43 then  'Global Block ALL' When
44 then  'T38 Reject' When
45 then  'Partition CAP Limit Exceeded' When
46 then  'Partition CPS Limit Exceeded' When
47 then  'LRN Loop Detected' When
48 then  'Reject partition' When
49 then  'Resource Loop Detected' When
50 then  'Code CAP Limit Exceeded' When
51 then  'Code CPS Limit Exceeded' When
52 then  'Switch Profile CAP Limit Exceeded' When
53 then  'Switch Profile CPS Limit Exceeded' When
54 then  'Not Allowed Send To IP' When
55 then  'LRN Dipping Failed' When
56 then  'System call limit' When
57 then  'Egress Block ANI' When
58 then  'Egress Block DNIS' When
59 then  'Egress Block ALL' When
60 then  'Resource Block ANI' When
61 then  'Resource Block DNIS' When
62 then  'Resource Block ALL'
else    'other'  end  as "Release Cause"
RELEASE;
                    }else{
                        $show_field_replace_arr[] = "{$show_field_item} as \"{$replace_field_name[$show_field_item]}\"";
                    }
                }
                else
                {
                    $show_field_replace_arr[] = $show_field_item;
                }
            }

        }
//        pr($show_field_replace_arr);die;
        $show_field_replace = implode(",", $show_field_replace_arr);
//                var_dump($show_field);
//                echo "<br />";
//                var_dump($show_field_replace);
//                echo "<br />";
//                var_dump($org_sql);

        $replace_count = 1;
//        pr($org_sql,$show_field,$show_field_replace);die;
//        var_dump(strpos($org_sql,$show_field));die;
        $org_sql_replace = str_replace($show_field, $show_field_replace, $org_sql,$replace_count);

//                echo "<br />";
//                var_dump($org_sql_replace);
        return array($org_sql_replace,$show_field_replace);
    }

    public function reprocess($encode_log_id)
    {
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $log_id = base64_decode($encode_log_id);
        $conditions = array('id' => $log_id);
        if ($this->Session->read('login_type') == 2)
            $conditions['user_id'] = $this->Session->read('sst_user_id');
        $log_info = $this->CdrExportLog->find('count',array('conditions' => $conditions));
        if (!$log_info)
        {
            $this->Session->write('m', $this->CdrExportLog->create_json(101, __('Log info is not exist!', true)));
            $this->redirect('export_log');
        }
        $update_arr = array(
            'id' => $log_id,
            'status' => 0,
        );
        $this->CdrExportLog->save($update_arr);
        putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
        putenv('LC_ALL=en_US.UTF-8');
        putenv('LANG=en_US.UTF-8');
        putenv('LD_LIBRARY_PATH=/usr/local/lib:/usr/lib:/usr/local/lib64:/usr/lib64');
        Configure::load('myconf');
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $scriptPath = file_exists("$script_path/class4_cdr_export.pyc") ? "$script_path/class4_cdr_export.pyc" : "$script_path/class4_cdr_export.py";

        $cmd = "python3 $scriptPath -c $script_conf -i $log_id  > /dev/null 2>&1 & echo $!";

        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));

        if (Configure::read('cmd.debug')) {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }
        $output = shell_exec($cmd);
        $pid = trim($output);
        $this->CdrExportLog->query("update cdr_export_log set pid = $pid where id = $log_id");
        $this->Session->write('m', $this->CdrExportLog->create_json(201, sprintf(__('You Job [#%s] is scheduled to execute in the queue.',true),$pid)));
        $this->redirect('export_log');
    }

    public function send_cdr_mail()
    {
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        if (!$this->RequestHandler->isPost())
        {
            $this->Session->write('m', $this->CdrExportLog->create_json(101, __('illegal operation!', true)));
            $this->redirect('export_log');
        }
        $log_id = $this->params['form']['log_id'];
        $save_data = $this->data;
        $save_data['id'] = $log_id;

        if ($this->CdrExportLog->save($save_data) === false)
        {
            $this->Session->write('m', $this->CdrExportLog->create_json(101, __('Update send mail failed!', true)));
            $this->redirect('export_log');
        }

        $email = $save_data['send_mail'];
        $mailTemplate = $this->CdrExportLog->query('SELECT download_cdr_subject, download_cdr_content, download_cdr_cc, download_cdr_from from mail_tmplate limit 1');
        $subject = $mailTemplate[0][0]['download_cdr_subject'];
        $content = $mailTemplate[0][0]['download_cdr_content'];
        $cc = $mailTemplate[0][0]['download_cdr_cc'];
        $from = $mailTemplate[0][0]['download_cdr_from'];
        $downloadUrl = FULL_BASE_URL . DS . 'cdrreports_db/downloadCdr/' . base64_encode($log_id);
        $downloadLink = "<a href='{$downloadUrl}'>{$downloadUrl}</a>";
        $content = str_replace('{download_link}', $downloadLink, $content);
        $content = str_replace('{username}', $this->Session->read('sst_user_name'), $content);
        $sendResult = $this->VendorMailSender->Send($subject, $content, $email, $cc, $from);

        if ($sendResult['status'] == 0)
            $this->Session->write('m', $this->CdrExportLog->create_json(201, __('You Job [#%s] is scheduled to execute in the queue.',true,array($pid))));
        else
            $this->Session->write('m', $this->CdrExportLog->create_json(101, __('Send mail failed!', true)));
        $this->redirect('export_log');

    }

    public function downloadCdr($encodedLogId)
    {
        $logId = base64_decode($encodedLogId);

        $log = $this->CdrExportLog->find('first', array(
            'conditions' => array(
                'id' => $logId
            )
        ));

        $file = Configure::read('database_export_path') . DS . "cdr_download" . DS . $log['CdrExportLog']['file_name'];
        $quoted = sprintf('"%s"', addcslashes(basename($file), '"\\'));
        $size   = filesize($file);

        ob_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);

        readfile($file);

        exit;
    }

    function download_csv($exportLogId)
    {
        $this->loadModel('Download');

        $record = $this->CdrExportLog->find('first', array(
            'conditions' => array(
                'id' => $exportLogId
            )
        ));

        $filename = $record['CdrExportLog']['file_name'];

        $filename = Configure::read('database_export_path') . DS . "cdr_download" . DS . $filename;
        $this->Download->csv($filename);
    }

    public function _get_cdr_email_template(){
        $this->loadModel('Mailtmp');
        $this->set('mail_senders', $this->Mailtmp->find_mail_senders());
        $sql = "select download_cdr_from,download_cdr_subject,download_cdr_content,download_cdr_cc FROM mail_tmplate limit 1";
        $data = $this->Mailtmp->query($sql);
        $this->set('download_cdr_template', isset($data[0][0]) ? $data[0][0] : '');
    }

    private function getQueryCdrResult($queryKey)
    {
        $url = "{$this->apiUrl}/api/v1.0/show_query_cdr";
        $ch = curl_init();
        $data = array(
            'switch_ip' => $this->siteIp,
            'query_key' => $queryKey
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);






















        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        die(var_dump($result));

    }

    public function get_cdr($type = 0) {
        $username = $_SESSION['sst_user_name'];
        $url = "http://{$this->siteIp}:8000/api/v1.0/create_query_cdr";
        $ch = curl_init();
        $data = array(
            'start' => $_GET['start_time'],
            'end'   => $_GET['end_time'],
            'switch_ip' => $this->siteIp,
            'search_filter' => $_GET['fields'],
            'result_filter' => $_GET['fields'],
            'username' => $username
        );

        if($type == 0) {
            $data['email_to'] = $_GET['mail_to'];
            $data['cdr_subject'] = $_GET['mail_subject'];
            $data['cdr_body'] = $_GET['mail_content'];
        } else {
            $data['ftp_to'] = $_GET['ftp'];
            $data['email_to'] = "yaskevichyaroslav@gmail.com";
            $data['cdr_subject'] = "test";
            $data['cdr_body'] = "test";
        }

//        echo '<pre>';
//        die(var_export(json_encode($data)));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if(isset($result['error'])) {
            $this->Session->write('m', $this->CdrExportLog->create_json(101, $result['error']));
        } else if(isset($result['query_key'])) {
            if($type == 0) {
                $this->Session->write('m', $this->CdrExportLog->create_json(201, "CDR was sent to {$_GET['mail_to']}"));
            } else {
                $this->Session->write('m', $this->CdrExportLog->create_json(201, "Request for export to {$_GET['ftp']} created successfully!"));
            }
//            $this->getQueryCdrResult(isset($result['query_key'][0]) ? $result['query_key'][0] : $result['query_key']);
        }

        $getQuery = http_build_query($_GET['get']);

        $this->redirect("/cdrreports_db/summary_reports/?{$getQuery}");
    }

    function ajax_get_cdr(){

        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //Configure::load('myconf');
        $url = Configure::read('cdr_url');

        if(!$this->_get('start_time')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no start time' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('end_time')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no end time' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        $start_time = date('Y-m-d H:i:s',strtotime($this->_get('start_time')));
        $end_time = date('Y-m-d H:i:s',strtotime($this->_get('end_time')));
        $fields = $this->_get('fields');

        $this->loadModel('SwitchProfile');
        $res = $this->SwitchProfile->query("SELECT cdr_token_alias, cdr_token FROM system_parameter");

        $token = $res[0][0]['cdr_token'];
        $alias = $res[0][0]['cdr_token_alias'];
//        pr($switch_ip,$call_id,$start_time,$end_time);die;
        $data = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'output_fields' => $fields,
        );
//        查询pcap
        $query_url = $url.'/cdrextraction/query.php/'.$alias.'/?key='.$token;
        $result_data = $this->postAPIData($query_url,json_encode($data));

        $arr = json_decode($result_data,true);
        $arr = $arr[0];

        if(!isset($arr['session_key'])){
            if (Configure::read('debug')){
                pr($arr);die;
            }
            return json_encode(array('self_status' => 0 ,'msg' =>'connect failed' ));

        }

//        获取状态
        $i = 0;
        while(1){
            //$get_status_data = $arr;
            $get_status_url = $url.'/cdrextraction/status.php?key='.$token.'&session_key='.$arr['session_key'];
            $status_result_data = $this->postAPIData($get_status_url);
            $status_result_arr = json_decode($status_result_data,true);
            if ($status_result_arr['msg'] == 'running'){
                if ($i > 60){
                    json_encode(array('self_status' => 0 ,'msg' =>'query time out' ));
                }
                sleep(1);
                $i ++;
                continue;
            }
            break;
        }

        if ($status_result_arr['msg'] == 'not found'){
            $status_result_arr['self_status'] = 0;
        }elseif ($status_result_arr['msg'] == 'complete'){
            $status_result_arr['self_status'] = 1;
        }

        $status_result_arr['download_url'] = $url.'/cdrextraction/download.php?key='.$token .'&session_key='.$arr['session_key'];
        $status_result_arr['token'] = $token;
        $status_result_arr['session_key'] = $arr['session_key'];

        return json_encode($status_result_arr);
    }

    public function ajax_get_public_cdr_download(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $url = Configure::read('cdr_url');
        if(!$this->_get('token')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no token' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('session_key')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no session key' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        $token = $this->_get('token');
        $data = array();
        $get_url = $url.'/cdrextraction/makepublic.php?key='.$token . '&session_key='. $this->_get('session_key') ;
        $result_data = $this->postAPIData($get_url,json_encode($data));

        $arr = json_decode($result_data,true);
        if ($arr){
            $arr['status'] = 1;
        }else{
            $arr['status'] = 0;
        }
        return json_encode($arr);

    }

    public function cdr_download(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $url = Configure::read('cdr_url');
        if(!$this->_get('token')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no token' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        if(!$this->_get('session_key')){
            if (Configure::read('debug')){
                return json_encode(array('self_status' => 0 ,'msg' =>'no session key' ));
            }
            return json_encode(array('self_status' => 0 ,'msg' =>__('Illegal operation',true) ));
        }
        $download_path = Configure::read('database_export_path') . DS . 'cdr_download';
        $file_name = $this->_get('start_time').'_'.$this->_get('end_time').'.cdr';

        if (is_dir($download_path)){
            $download_file = $download_path . DS . $file_name;
        }else{
            $download_file = Configure::read('database_export_path') . DS . $file_name;
        }

//        if (!file_exists($download_file)){
        $token = $this->_get('token');
        $get_url = $url.'/cdrextraction/download.php?key='.$token.'&session_key='.$this->_get('session_key');
        $result_data = $this->postAPIData($get_url);
        if (is_null($result_data)){
//                错误
            echo __('Connect Failed!', true);die;
            $this->Session->write('m', $this->Cdr->create_json(101, __('Connect Failed!', true)));
            $this->redirect('sip_packet');
        }
        file_put_contents($download_file,$result_data);
//        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".basename($download_file)."\";" );
        header("Content-Transfer-Encoding: binary");
        ob_clean();
        flush();
        readfile($download_file);die;
    }

    public function test()
    {
//        $url = "http://{$_SERVER['SERVER_ADDR']}:8890/?start=1481882400&end=1482318000&step=60&method=non_zero_count";
        $url = "http://192.99.10.113:8890/?start=1481882400&end=1482318000&step=60&method=non_zero_count";
        $ch = curl_init();
        $data = array(
            'start' => 1481882400,
            'end'   => 1482318000,
            'step' => '60',
            'method' => 'non_zero_count'
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if($result && $result['code'] == 200) {

//            App::import("Vendor", "WebSocket", array('file' => "WebSocket/Lib/Network/Http/WebSocket.php"));
//            App::import('Plugin/WebSocket/Lib/Network/Http', 'WebSocket', array('file'=>'WebSocket.php'));
//            $websocket = new WebSocket(array('port' => 8080, 'scheme'=>'ws'));
//            die(var_dump($websocket));
//            $this->set('url', "ws://{$_SERVER['SERVER_ADDR']}:{$result['port']}/");
            $this->set('url', "ws://192.99.10.113:{$result['port']}/");
            $this->render('websock');

        }

        return $result;
    }

    function ajaxCdrRequest()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = false;
        if ($this->RequestHandler->isPost()) {

            $type = isset($_POST['output']) ? $_POST['output'] : 1;
            unset($_POST['output']);

            App::import('Vendor', 'RequestFactory', array('file' => 'api/cdr/class.request_factory.php'));

            $cdr = new RequestFactory();
            $res = $cdr->run($type, $_POST);

            ob_clean();
            return $res;
        }
        exit;
    }

    public function cdr_export_log_new()
    {
        $this->loadModel('CdrApiExportLog');

        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;

            $result = false;

            $item = $this->CdrApiExportLog->find('first', array(
                'conditions' => array(
                    'id' => $_POST['id']
                )
            ));

            if ($item) {
                App::import('Vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));
                $callDetailReportAsync = new CallDetailReportAsync();

                $result = $callDetailReportAsync->download($item['CdrApiExportLog']['request_id']);
            }

            return $result;
        }

        $data = $this->CdrApiExportLog->find('all', array(
            'conditions' => array(
                'user_id' => $_SESSION['sst_user_id']
            ),
            'order' => 'id DESC'
        ));

        if (!empty($data)) {
            App::import('Vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));
            $callDetailReportAsync = new CallDetailReportAsync();

            foreach ($data as &$item) {
                $result = $callDetailReportAsync->checkStatus($item['CdrApiExportLog']['request_id']);

                if ($result) {
                    $item['CdrApiExportLog']['status'] = json_decode($result, true);
                }
            }
        }

        $this->set('data', $data);
    }

    public function cdr_summary_reports()
    {
        extract($this->get_start_end_time());

        $dateStart = $start;
        $dateEnd = $end;
        $report_fields = array(
            'time',
            'termination_destination_number',
            'release_cause,pdd',
            'orig_call_duration',
            'call_duration',
            'ingress_id as ingress_name',
            'egress_id as egress_name',
            'origination_source_number',
            'origination_destination_number',
            'origination_source_host_name',
            'termination_source_host_name',
            'release_cause_from_protocol_stack',
            'binary_value_of_release_cause_from_protocol_stack',
            'answer_time_of_date',
            'origination_destination_host_name'
        );

        $this->initNewReport();

        $field = array();

        $this->set('start', $dateStart);
        $this->set('end', $dateEnd);
        $this->set('quey_time', 32);
        $this->set('report_fields', $report_fields);
        $this->set('cdr_field', $field);
    }

    public function get_view()
    {
        Configure::write('debug', 0);

        $id = base64_decode($_GET['key']);
        $item = $this->CdrExportLog->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        $directory = $item['CdrExportLog']['file_dir'];
//        $directory = "/home/yaro/class4-v5.0/download/cdr_download/11_1500019782";
        $files = array();

        if (!empty($directory)) {
            if ($handle = opendir($directory)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {

                        array_push($files, $entry);
                    }
                }
                closedir($handle);
            }
        }
        $this->set('logs', $files);
        $this->set('id', $_GET['key']);
    }

    public function export_log_item_down(){
        Configure::write('debug', 0);
        Configure::load('myconf');
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $filename = urldecode($_GET['file']);
        $item = $this->CdrExportLog->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        $directory = $item['CdrExportLog']['file_dir'];
//        $directory = "/home/yaro/class4-v5.0/download/cdr_download/11_1500019782";
        $path = $directory . DS . $filename;
        $filesize = filesize($path);

        header("Content-Description: File Transfer");
        header("Content-Type: application/otcet-stream");
        header('Content-Length: '.$filesize);
        header("Content-Range: 0-".($filesize-1)."/".$filesize);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        ob_clean();

        set_time_limit(0);
        readfile($path);
        exit(0);
    }

    public function get_export_rows()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($this->RequestHandler->isPost()) {
            $status = array("Waiting", "In Progress", "Query", "Compress", "Done", "Canceled");
            $items = $this->CdrExportLog->find('all', array(
                'fields' => array('id', 'status', 'finished_time', 'completed_days', 'total_days', 'file_rows', 'file_name'),
                'conditions' => array(
                    'id' => $_POST['ids']
                )
            ));

            foreach ($items as &$item) {
                $item['CdrExportLog']['textStatus'] = $status[$item['CdrExportLog']['status']];
                $item['CdrExportLog']['file_size'] = '';

                if ($item['CdrExportLog']['status'] == 4 || $item['CdrExportLog']['status'] == -2) {
                    $file_name = realpath(Configure::read('database_export_path') . DS . "cdr_download" . DS . $item['CdrExportLog']['file_name']);
                    $item['CdrExportLog']['file_size'] = $this->to_readable_size(@filesize($file_name));
                }
            }

            return json_encode($items);
        }
    }

    private function to_readable_size($size)
    {
        $size = (float) $size;
        switch (true)
        {
            case $size <= 24:
                return 'Empty';
            case $size < 1024:
                return sprintf(__n('%d Byte', '%d Bytes', $size, true), $size);
            case round($size / 1024) < 1024:
                return sprintf(__('%d KB', true), $this->precision($size / 1024, 0));
            case round($size / 1024 / 1024, 2) < 1024:
                return sprintf(__('%.2f MB', true), $this->precision($size / 1024 / 1024, 2));
            case round($size / 1024 / 1024 / 1024, 2) < 1024:
                return sprintf(__('%.2f GB', true), $this->precision($size / 1024 / 1024 / 1024, 2));
            default:
                return sprintf(__('%.2f TB', true), $this->precision($size / 1024 / 1024 / 1024 / 1024, 2));
        }
    }

    function precision($number, $precision = 3)
    {
        return sprintf("%01.{$precision}f", $number);
    }

    private function is_api_connect(){

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->apiUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode == 200 ? true : false;
    }

    private function getReplaceReleaseCauses()
    {
        $releaseCauseSql = "case  release_cause
	 	when    0     then   'Unknown Exception'
        when    1     then   'System CPS Limit Exceeded'
        when    2     then   'SYSTEM_CPS System Limit Exceeded'
        when    3     then   'Unauthorized IP Address'
        when    4     then   'No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit Call Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  Call Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when   10     then   'Invalid Codec Negotiation'
		when   11     then   'Block due to LRN'
		when   12     then   'Ingress Rate Not Found'  
		when   13 	  then   'Egress Trunk Not Found'  
		when   14 	  then   'Egress Returns 404'  
		when   15 	  then   'Egress Returns 486'  
		when   16 	  then   'Egress Returns 487'  
		when   17 	  then   'Egress Returns 200'  
		when   18 	  then   'All Egress Unavailable'  
		when   19 	  then   'Normal hang up' 
		when   20 	  then   'Ingress Resource disabled'   
		when   21 	  then   'Zero Balance'   
		when   22 	  then   'No Route Found'   
		when   23 	  then   'Invalid Prefix'   
		when   24 	  then   'Ingress Rate Missing'
		when   25     then   'Invalid Codec Negotiation'
		when   26     then   'No Codec Found'
		when   27     then   'All Egress Failed'
		when   28     then   'LRN Response Missing'
		when   29     then   'Carrier Call Limit Exceeded'
		when   30     then   'Carrier CPS Limit Exceeded'
		when   31     then   'Rejected Due to Host Alert'
		when   32     then   'Rejected Due to Trunk Alert'
		when   33     then   'H323 Not Supported'
		when   34     then   '180 Negotiation Failure'
		when   35     then   '183 Negotiation Failute'
		when   36     then   '200 Negotiation Failure'
		when   37     then   'Block LRN with Higher Rate'
        when   38     then   'Ingress Block ANI'
        when   39     then   'Ingress Block DNIS'
        when   40     then   'Ingress Block ALL'
        when   41     then   'Global Block ANI'
        when   42     then   'Global Block DNIS'
        when   43     then   'Global Block ALL'
        when   44     then   'T38 Reject'
        when   48     then   'Allowed Send To IP Failed'
        when   52     then   'Switch Profile CAP Limit Exceeded'
        when   53     then   'Switch Profile CPS Limit Exceeded'
		else    'other'  end  as
		release_cause";

        return $releaseCauseSql;
    }

}
