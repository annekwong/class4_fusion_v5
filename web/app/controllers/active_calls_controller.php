<?php

class ActiveCallsController extends AppController
{

    var $name = "ActiveCalls";
    var $uses = array('ActiveCall', 'Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function reports()
    {
        $this->pageTitle = "Statistics/Active Call Report";


        $this->init();
        $error_note = null;
        $fields = array(
            'start_time'=>'Start Time',
            'answer_time'=>'Answer Time',
            'orig_ani'=>'Orig Ani',
            'orig_dnis'=>'Orig Dnis',
            'orig_ip'=>'Orig Ip',
            'orig_profile_ip'=>'Orig Profile Ip',
            'orig_code'=>'Orig Code',
            'orig_rtp_ip'=>'Orig Rtp Ip',

            'orig_rtp_port'=>'Orig rtp Port',
            'orig_sw_rtp_ip'=>'Orig Sw Rtp Ip',
            'orig_sw_rtp_port'=>'Orig Sw Rtp Port',
            'ingress_carrier'=>'Ingress Carrier',
            'ingress_trunk'=>'Ingress Trunk',
            'lrn_number'=>'Lrn Number',
            'orig_rate_type'=>'Orig Rate Type',
            'orig_rate'=>'Orig Rate',
            'orig_rate_table'=>'Orig Rate Table',
            'term_ani'=>'Term Ani',
            'term_dnis'=>'Term Dnis',
            'term_ip'=>'Term Ip',
            'term_profile_ip'=>'Term Profile Ip',
            'term_code'=>'Term Code',
            'term_rtp_ip'=>'Term Rtp Ip',
            'term_rtp_port'=>'Term Rtp Port',
            'term_sw_rtp_ip'=>'Term Sw Rtp Ip',
            'term_sw_rtp_port'=>'Term Sw Rtp Port',

            'egress_carrier'=>'Egress Carrier',
            'egress_trunk'=>'Egress Trunk',
            'term_rate_type'=>'Term Rate Type',
            'term_rate'=>'Term Rate',
            'egress_rate_table'=>'Egress Rate Table',
            'routing_plan'=>'Routing Plan',
            'dynamic_route'=>'Dynamic Route',
            'statist_route'=>'Statist Route'
        );

        $get_data = $this->params['url'];

        //默认查询
        $search_show_fields = array(
            "answer_time",
            "orig_ani",
            "orig_dnis",
            "orig_ip",
            "ingress_carrier",
            "ingress_trunk",
            "lrn_number",
            "orig_rate",
            "term_ani",
            "term_dnis",
            "term_ip",
            "egress_carrier",
            "egress_trunk",
            "term_rate",
        );

        $switch_names = $this->ActiveCall->get_switch_name();
        $switch_name = $switch_names ? $switch_names[0] : '';
        $field = implode(',',$search_show_fields);


        $filter = '';
        if (isset($get_data['query']))
        {
            // Search
            $switch_name = trim($get_data['switch_name']);

            $ingress_carrier = trim($get_data['orig_carrier']);
            $egress_carrier = trim($get_data['term_carrier']);
            $ingress_trunk = trim($get_data['ingress']);
            $egress_trunk = trim($get_data['egress']);
            $orig_ip_id = trim($get_data['orig_ip']);
            $term_ip_id = trim($get_data['term_ip']);

            $orig_ip = $this->ActiveCall->get_resource_ip($orig_ip_id);
            $term_ip = $this->ActiveCall->get_resource_ip($term_ip_id);

            $orig_ani = trim($get_data['ani']);
            $term_dnis = trim($get_data['dnis']);

            $search_show_fields = $get_data['show_fields'];
            if (!empty($search_show_fields))
            {
                $field = implode(',',$search_show_fields);
            }


            $filter = array();
            if (!empty($ingress_carrier))
            {
                $filter[] = "ingress_carrier=$ingress_carrier";
            }
            if (!empty($egress_carrier))
            {
                $filter[] = "egress_carrier=$egress_carrier";
            }
            if (!empty($ingress_trunk))
            {
                $filter[] = "ingress_trunk=$ingress_trunk";
            }
            if (!empty($egress_trunk))
            {
                $filter[] = "egress_trunk=$egress_trunk";
            }
            if (!empty($orig_ip))
            {
                $filter[] = "orig_ip=$orig_ip";
            }
            if (!empty($term_ip))
            {
                $filter[] = "term_ip=$term_ip";
            }
            if (!empty($term_dnis))
            {
                $filter[] = "term_dnis=$term_dnis";
            }
            if (!empty($orig_ani))
            {
                $filter[] = "orig_ani=$orig_ani";
            }
            $filter = implode(',',$filter);
        }

        $filter = trim($filter);
        if(empty($filter))
            $filter = "all";

        require_once 'MyPage.php';
        $page = new MyPage ();
        empty($_GET['page']) ? $_GET['page'] = $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $_GET['size'] = $pageSize = 100 : $pageSize = $_GET['size'];

        $s = ($currPage-1) * $pageSize + 1;
        $e = $currPage * $pageSize;
        $count = "$s,$e";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));

        $this->set('info', $info);
        if(empty($switch_name)){
            $error_note = __('No Switch',true);
            $total = 0;
            $show_data = "";
        } else {
            $this->set('cmd_debug',Configure::read('cmd.debug') && Configure::read('debug'));
            #连接后台接口
            $back = $this->get_connect_obj();
            $rst = $back->backend_get_active_call($switch_name,$field,$filter,$count);
            if($rst === false || empty($rst) || empty($rst[0][0])) {
                $error_note = $back->get_error();
                $total = 0;
                $show_data = "";
            } else {
                $total = $rst['total'];
                unset($rst['total']);
                $show_data = $rst;
            }
        }

        $page->setTotalRecords($total); //总记录数

        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小


        if (!$show_data)
        {
            $show_data = array();
            $error_note = $error_note ? $error_note : "No data found";
            $this->set('error_note', $error_note);
        }
        else
        {
            $this->set('error_note', false);
        }
        $results = $show_data;
        $page->setDataArray($results);
        $this->set('p', $page);
        $this->set('fields', $fields);
        $this->set('search_show_fields', $search_show_fields);
    }

    /*public function reportsbak()
    {
        $this->pageTitle = "Statistics/Active Call Report";

        $this->init();
        $error_note = null;
        $field = array(
            "answer_time" => "Answer Time",
            "caller_ani" => "Caller Ani",
            "caller_dnis" => "Caller Dnis",
            "caller_source_ip" => "Caller Source Ip",
            "caller_source_port" => "Caller Source Port",
            "caller_destination_ip" => "Caller Destination Ip",
            "caller_destination_port" => "Caller Destination Port",
            "caller_codec" => "Caller Codec",
            "caller_call_id" => "Caller Call Id",
            "caller_trunk" => "Caller Trunk",
            "caller_trunk_type" => "Caller Trunk Type",
            "caller_carrier" => "Caller Carrier",
            "caller_rate" => "Caller Rate",
            "caller_rate_table" => "Caller Rate Table",
            "caller_protocol" => "Caller Protocol",
            "caller_packets" => "Caller Packets",
            "caller_route_prefix" => "Caller Route Prefix",
            "caller_lrn_number" => "Lrn Number",
            "called_ani" => "Called Ani",
            "called_dnis" => "Called Dnis",
            "called_source_ip" => "Called Source Ip",
            "called_source_port" => "Called Source Port",
            "called_destination_ip" => "Called Destination Ip",
            "called_destination_port" => "Called Destination Port",
            "called_codec" => "Called Codec",
            "called_call_id" => "Called Call Id",
            "called_trunk" => "Called Trunk",
            "called_trunk_type" => "Called Trunk Type",
            "called_carrier" => "Called Carrier",
            "called_rate" => "Called Rate",
            "called_rate_table" => "Called Rate Table",
            "called_protocol" => "Called Protocol",
            "called_packets" => "Called Packets",
        );

        $get_data = $this->params['url'];

        //默认查询
        $search_show_fields = array(
            "answer_time",
            "caller_ani",
            "caller_dnis",
            "caller_source_ip",
            "caller_call_id",
            "caller_trunk",
            "caller_carrier",
            "caller_rate",
            "caller_lrn_number",
            "called_ani",
            "called_dnis",
            "called_destination_ip",
            "called_call_id",
            "called_trunk",
            "called_carrier",
            "called_rate",
        );
        $condition = array();


        if (isset($get_data['query']))
        {
            // Search
            $orig_carrier = trim($get_data['orig_carrier']);
            $term_carrier = trim($get_data['term_carrier']);
            $ingress = trim($get_data['ingress']);
            $egress = trim($get_data['egress']);
            $orig_ip_id = trim($get_data['orig_ip']);
            $term_ip_id = trim($get_data['term_ip']);

            $orig_ip = $this->ActiveCall->get_resource_ip($orig_ip_id);
            $term_ip = $this->ActiveCall->get_resource_ip($term_ip_id);

            $dnis = trim($get_data['dnis']);
            $ani = trim($get_data['ani']);
            $search_show_fields = $get_data['show_fields'];
            //pr($search_show_fields);die;
            if (!empty($orig_carrier))
            {
//                $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
//                        . " where ingress=true AND trunk_type2 = 0 and resource.client_id = {$get_data['orig_carrier_select']}";
//                $ingress_result = $this->Cdr->query($sql); //pr($ingress);die;
                $condition[] = "caller_carrier {$orig_carrier}";
//                $size = count($ingress_result);
//                $ingress = array();
//                for ($i = 0; $i < $size; $i++)
//                {
//                    $key = $ingress_result [$i] [0] ['resource_id'];
//                    $ingress [$key] = $ingress_result [$i] [0] ['alias'];
//                }
            }
            if (!empty($term_carrier))
            {
//                $sql = "select  resource_id,alias  from  resource inner join client on resource.client_id = client.client_id"
//                        . " where egress=true AND trunk_type = 0 and resource.client_id = {$get_data['term_carrier_select']}";
//                $egress_result = $this->Cdr->query($sql);
                $condition[] = "called_carrier {$term_carrier}";
//                $size = count($egress_result);
//                $ingress = array();
//                for ($i = 0; $i < $size; $i++)
//                {
//                    $key = $egress_result [$i] [0] ['resource_id'];
//                    $egress [$key] = $egress_result [$i] [0] ['alias'];
//                }
            }
            if (!empty($ingress))
            {
                $condition[] = "caller_trunk {$ingress}";
            }
            if (!empty($egress))
            {
                $condition[] = "called_trunk {$egress}";
            }
            if (!empty($orig_ip))
            {
                $condition[] = "caller_ip {$orig_ip}";
            }
            if (!empty($term_ip))
            {
                $condition[] = "called_ip {$term_ip}";
            }
            if (!empty($dnis))
            {
                $condition[] = "dnis {$dnis}";
            }
            if (!empty($ani))
            {
                $condition[] = "ani {$ani}";
            }
        }

        $send = "GET";

        if ($condition)
        {
            $send .= " CONDITION " . implode(' ', $condition) . " END ";
        }

        if ($search_show_fields)
        {
            $send .= " FIELD " . implode(' ', $search_show_fields) . " END ";
        }
        $data = array();
        $this->set('fields', $field);
        $this->set('search_show_fields', $search_show_fields);
//        pr($search_show_fields);


        require_once 'MyPage.php';
        $page = new MyPage ();
######## ######## ######## ######## ######## ########       
########      判断链接 是否正确  start  
        $ip = "";
        $port = "";
        if (isset($this->params['url']['active_call_server']))
        {
            $report_ip_port_str = $this->params['url']['active_call_server'];
            $report_ip_port_arr = explode(":", $report_ip_port_str);
            $ip = $report_ip_port_arr[0];
            $port = $report_ip_port_arr[1];
        }
        else
        {
            $default_report = $this->ActiveCall->query("SELECT active_call_ip,active_call_port FROM voip_gateway WHERE active_call_ip is not null AND active_call_port is not null ORDER BY active_call_ip ASC");
            if($default_report)
            {
                $ip = $default_report[0][0]['active_call_ip'];
                $port = $default_report[0][0]['active_call_port'];
            }
            $i = 0;
            foreach ($default_report as $default_report_item)
            {
                $tmp_ip = $default_report_item[0]['active_call_ip'];
                $tmp_port = $default_report_item[0]['active_call_port'];
                if (!$tmp_ip || !$tmp_port)
                    continue;
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                $flg = @socket_connect($socket, $tmp_ip, $tmp_port);
                if($flg === false)
                    continue;
                socket_getsockname($socket, $local_ip);
                $socket_length = strlen($local_ip);
                socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 5, "usec" => 0));
                $socket_length += 10;
                $out = @socket_read($socket, $socket_length);
                if (strcmp($out, "welcome $local_ip #"))
                {//如果不匹配
                    socket_close($socket);
                    continue;
                }
                $ip = $tmp_ip;
                $port = $tmp_port;
                $this->params['url']['active_call_server'] = $ip . ":" . $port;
                socket_close($socket);
                break;
            }

        }
        if (!$ip || !$port)
        {
            //提示没有配置报表服务器 
            $error_note = "The active call server is not configured!Please configure the active call server.";
            $this->set('error_note', $error_note);
            $this->Session->write('m', $this->ActiveCall->create_json(101, $error_note));
            $results = array();
            $page->setDataArray($results);
            $this->set('p', $page);
            return;
        }
        $this->params['url']['active_call_server'] = $ip . ":" . $port;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $flg = @socket_connect($socket, $ip, $port);
        $local_ip = "";
        if (!$flg)
        {
//                            链接失败 /服务器未开  report server is not open 
            $results = array();
            $error_note = "The Active call Server not open!";
            $this->set('error_note', $error_note);
            $this->Session->write('m', $this->ActiveCall->create_json(101, $error_note));
            $page->setDataArray($results);
            $this->set('p', $page);
            socket_close($socket);
            return;
        }
        socket_getsockname($socket, $local_ip);

        $socket_length = strlen($local_ip);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 5, "usec" => 0));
        $socket_length += 10;
        $out = @socket_read($socket, $socket_length);
        if (strcmp($out, "welcome $local_ip #"))
        {//如果不匹配 
            $results = array();
            $error_note = "The Active call Server configuration error!";
            $this->set('error_note', $error_note);
            $this->Session->write('m', $this->ActiveCall->create_json(101, $error_note));
            $page->setDataArray($results);
            $this->set('p', $page);
            socket_close($socket);
            return;
        }


########              判断链接 是否正确  end   
######## ######## ######## ######## ######## ########   
########              获得总数   start  

        $count_send = "GET GET_LINE";
        @socket_write($socket, $count_send);
//                $data = socket_read($socket, 1024);
//                                pr($data);die;
        $count_str = "";
        do
        {
            $count_str .= @socket_read($socket, 4096);
        }
        while (strpos($count_str, "\r\n\r\n") === false);

        $totalrecords = trim($count_str);

########              获得总数   end          

        empty($get_data['page']) ? $currPage = 1 : $currPage = $get_data['page'];
        empty($get_data['size']) ? $pageSize = 100 : $pageSize = $get_data['size'];

        $_SESSION['paging_row'] = $pageSize;


        $page->setTotalRecords($totalrecords); //总记录数 

        $page->setCurrPage($currPage); //当前页 
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $nextPage = $page->getCurrPage();
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;

        if (!$totalrecords)
        {
            socket_close($socket);
        }
        else
        {
            $start_line = $currPage * $pageSize;

            $get_line = 100;

            $send .= " BEGIN_LINE {$start_line} LIMIT {$get_line}";
            //echo $send . "<br />";
            if (Configure::read('cmd.debug'))
            {
                $this->set('send', $send);
            }
            @socket_write($socket, $send);
//                $data = socket_read($socket, 1024);
//                                pr($data);die;
            $data_str = "";
            do
            {
                $data_str .= @socket_read($socket, 4096);
            }
            while (strpos($data_str, "\r\n\r\n") === false);

//        var_dump($data_str);

            $data_arr = explode("\n", $data_str);
//        pr($data_arr);

            $field_str = trim($data_arr[0]);


            $field_arr = explode('?', $field_str);
            unset($data_arr[0]);
            foreach ($data_arr as $data_item)
            {
                $data_item_arr = explode('?', $data_item);
//                pr($data_item_arr);die;
                if (trim($data_item_arr[0]))
                {
                    $data[][0] = array_combine($field_arr, $data_item_arr);
                }
            }
            socket_close($socket);
        }
//        $data[] = $data[0];
//        pr($data);
//        echo "<hr />";
        $show_data = $this->transform_active_call_data($data);
//        echo "<hr />";
//        pr($show_data);
//        die;
        if (!$show_data)
        {
            $show_data = array();
            $error_note = "No data found";
            $this->set('error_note', $error_note);
        }
        else
        {
            $this->set('error_note', false);
        }
        $results = $show_data;
        $page->setDataArray($results);
        $this->set('p', $page);
    }*/

    public function init()
    {
        //        添加user中的 enable outbound report  和 enable reporting grouping
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->ActiveCall->query("select report_group,outbound_report from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $get_data = $this->params['url'];
        $ingress_client_id = null;
        $egress_client_id = null;
        if (isset($get_data['orig_carrier']) && !empty($get_data['orig_carrier']))
        {
            $ingress_client_id = $get_data['orig_carrier'];
        }
        if (isset($get_data['term_carrier']) && !empty($get_data['term_carrier']))
        {
            $egress_client_id = $get_data['term_carrier'];
        }
        $ingress_id = null;
        $egress_id = null;
        if (isset($get_data['ingress']) && !empty($get_data['ingress']))
        {
            $ingress_id = $get_data['ingress'];
        }
        if (isset($get_data['egress']) && !empty($get_data['egress']))
        {
            $egress_id = $get_data['egress'];
        }
        $ingress_clients = $this->ActiveCall->findIngressClient();
        $egress_clients = $this->ActiveCall->findEgressClient();
        $ingress_resources = $this->ActiveCall->get_resources('ingress', $ingress_client_id);
        $egress_resources = $this->ActiveCall->get_resources('egress', $egress_client_id);
        $ingress_resource_ips = $this->ActiveCall->get_resource_ips('ingress',$ingress_id);
        $egress_resource_ips = $this->ActiveCall->get_resource_ips('egress',$egress_id);
        $switch_names = $this->ActiveCall->get_switch_name();

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        $this->set('ingress_resources', $ingress_resources);
        $this->set('egress_resources', $egress_resources);
        $this->set('ingress_resource_ips', $ingress_resource_ips);
        $this->set('egress_resource_ips', $egress_resource_ips);
        $this->set('switch_names', $switch_names);
    }

    private function transform_active_call_data($data)
    {
        foreach ($data as $key1 => $data_items)
        {
            foreach ($data_items[0] as $key => $data_item)
            {
                if (!strcmp($key, "ANSWER_TIME"))
                {
                    $time = intval($data_item / 1000000);
                    $show_value = date('Y-m-d H:i:sO', $time);
                }
                elseif (!strcmp($key, "CALLER_TRUNK") || !strcmp($key, "CALLED_TRUNK"))
                {
                    $turnk_arr = $this->ActiveCall->query("SELECT alias FROM resource WHERE resource_id = $data_item");
                    $show_value = isset($turnk_arr[0][0]['alias']) ? $turnk_arr[0][0]['alias'] : $data_item;
                }
                elseif (!strcmp($key, "CALLER_CARRIER") || !strcmp($key, "CALLED_CARRIER"))
                {
                    $client_arr = $this->ActiveCall->query("SELECT name FROM client WHERE client_id = $data_item");
                    $show_value = isset($client_arr[0][0]['name']) ? $client_arr[0][0]['name'] : $data_item;
                }
                elseif (!strcmp($key, "CALLER_TRUNK_TYPE") || !strcmp($key, "CALLED_TRUNK_TYPE"))
                {
                    $trunk_type = array('1' => 'Class4', '2' => 'Exchange');
                    $show_value = isset($trunk_type[$data_item]) ? $trunk_type[$data_item] : $data_item;
                }
                elseif (!strcmp($key, "CALLER_RATE_TABLE") || !strcmp($key, "CALLED_RATE_TABLE"))
                {
                    $rate_table_id = intval($data_item);
                    $list = $this->ActiveCall->query("SELECT name FROM rate_table WHERE rate_table_id = $rate_table_id");
                    $show_value = isset($list[0][0]['name']) ? $list[0][0]['name'] : $data_item;
                }
                elseif (!strcmp($key, "CALLER_PROTOCOL") || !strcmp($key, "CALLED_PROTOCOL"))
                {
                    $protocol = array('1' => 'SIP', '2' => 'H323');
                    $show_value = isset($protocol[$data_item]) ? $protocol[$data_item] : $data_item;
                }
                else
                {
                    $show_value = $data_item;
                }
                $data[$key1][0][$key] = $show_value;
            }
        }
        return $data;
    }

//    private function walk(&$data, $fields)
//    {
//
//        /* 候选值 */
//        $protocol = array('1' => 'SIP', '2' => 'H323');
//        $trunk_type = array('1' => 'Class4', '2' => 'Exchange');
//        $buyer_test = array('0' => 'False', '1' => 'True');
//        $trunk_type2 = array('0' => 'DNIS billing', '1' => 'ANI billing');
//        $billing_method = array('0' => 'by minute', '1' => 'by ports');
//        $clients = $this->ActiveCall->get_clients();
//        $ingress_resources = $this->ActiveCall->get_resources('ingress');
//        $egress_resources = $this->ActiveCall->get_resources('egress');
//        $ingress_resource_ips = $this->ActiveCall->get_resource_ips('ingress');
//        $egress_resource_ips = $this->ActiveCall->get_resource_ips('egress');
//        $rate_tables = $this->ActiveCall->get_rate_tables();
//
//        $this->set('clients', $clients);
//        $this->set('ingress_resources', $ingress_resources);
//        $this->set('egress_resources', $egress_resources);
//        $this->set('ingress_resource_ips', $ingress_resource_ips);
//        $this->set('egress_resource_ips', $egress_resource_ips);
//
//        /* 找到这些要替换值下标 */
//        $origination_protocol_index = array_search('origination_protocol', $fields);
//        $origination_trunk_type_index = array_search('origination_trunk_type', $fields);
//        $origination_buyer_test_index = array_search('origination_buyer_test', $fields);
//        $origination_trunk_type2_index = array_search('origination_trunk_type2', $fields);
//        $origination_billing_method_index = array_search('origination_billing_method', $fields);
//        $origination_client_id_index = array_search('origination_client_id', $fields);
//
//        //print_r($origination_client_id_index);exit;
//
//        $origination_resource_id_index = array_search('origination_resource_id', $fields);
//        $origination_resource_ip_id_index = array_search('origination_resource_ip_id', $fields);
//        $origination_start_epoch_index = array_search('origination_start_epoch', $fields);
//        $origination_rate_table_id_index = array_search('origination_rate_table_id', $fields);
//        $origination_answer_epoch_index = array_search('origination_answer_epoch', $fields);
//
//        $termination_protocol_index = array_search('termination_protocol', $fields);
//        $termination_trunk_type_index = array_search('termination_trunk_type', $fields);
//        $termination_trunk_type2_index = array_search('termination_trunk_type2', $fields);
//        $termination_billing_method_index = array_search('termination_billing_method', $fields);
//        $termination_client_id_index = array_search('termination_client_id', $fields);
//        $termination_resource_id_index = array_search('termination_resource_id', $fields);
//        $termination_resource_ip_id_index = array_search('termination_resource_ip_id', $fields);
//        $termination_start_epoch_index = array_search('termination_start_epoch', $fields);
//        $termination_rate_table_id_index = array_search('termination_rate_table_id', $fields);
//        $termination_answer_epoch_index = array_search('termination_answer_epoch', $fields);
//
//
//        $termination_uuid_a_index = array_search('termination_uuid_a', $fields);
//        $this->set('termination_uuid_a', $termination_uuid_a_index);
//        $current_timestamp = time();
//
//        foreach ($data as &$row)
//        {
//
//            array_unshift($row, ($current_timestamp - intval(substr($row[$origination_answer_epoch_index - 1], 0, 10))));
//
//            /* 替换值 */
//            $row[$origination_protocol_index] = $protocol[$row[$origination_protocol_index]];
//            $row[$origination_trunk_type_index] = $trunk_type[$row[$origination_trunk_type_index]];
//            $row[$origination_buyer_test_index] = $buyer_test[$row[$origination_buyer_test_index]];
//            $row[$origination_trunk_type2_index] = $trunk_type2[$row[$origination_trunk_type2_index]];
//            $row[$origination_billing_method_index] = $billing_method[$row[$origination_billing_method_index]];
//            $row[$origination_client_id_index] = $clients[$row[$origination_client_id_index]];
//            $row[$origination_resource_id_index] = $ingress_resources[$row[$origination_resource_id_index]];
//            $row[$origination_resource_ip_id_index] = $ingress_resource_ips[$row[$origination_resource_ip_id_index]];
//            $row[$origination_start_epoch_index] = @(date('Y-m-d H:i:s', intval(substr($row[$origination_start_epoch_index], 0, 10)))) or 0;
//            $row[$origination_rate_table_id_index] = $rate_tables[$row[$origination_rate_table_id_index]];
//            $row[$origination_answer_epoch_index] = @(date('Y-m-d H:i:s', intval(substr($row[$origination_answer_epoch_index], 0, 10)))) or 0;
//
//            $row[$termination_protocol_index] = $protocol[$row[$termination_protocol_index]];
//            $row[$termination_trunk_type_index] = $trunk_type[$row[$termination_trunk_type_index]];
//            $row[$termination_trunk_type2_index] = $trunk_type2[$row[$termination_trunk_type2_index]];
//            $row[$termination_billing_method_index] = $billing_method[$row[$termination_billing_method_index]];
//            $row[$termination_client_id_index] = $clients[$row[$termination_client_id_index]];
//            $row[$termination_resource_id_index] = $egress_resources[$row[$termination_resource_id_index]];
//            $row[$termination_resource_ip_id_index] = $egress_resource_ips[$row[$termination_resource_ip_id_index]];
//            $row[$termination_start_epoch_index] = @(date('Y-m-d H:i:s', intval(substr($row[$termination_start_epoch_index], 0, 10)))) or 0;
//            $row[$termination_rate_table_id_index] = $rate_tables[$row[$termination_rate_table_id_index]];
//            $row[$termination_answer_epoch_index] = @(date('Y-m-d H:i:s', intval(substr($row[$termination_answer_epoch_index], 0, 10)))) or 0;
//        }
//
//        return $data;
//    }

    /*
     * 通过Socket通信将通话记录kill掉
     * @param string $killid
     * @return back
     */

    function kill($switch_name,$e_uuid, $isRedirect = true)
    {

        pr($switch_name,$e_uuid);
        set_time_limit(0);
        //Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $uuid = base64_decode($e_uuid);
        $sql = "select lan_ip, lan_port from switch_profile where switch_name = '$switch_name' limit 1";
        $rst = $this->ActiveCall->query($sql);

        $sip = $rst[0][0]['lan_ip'];
        $sport = $rst[0][0]['lan_port'];
        $rst = false;

        if(!$uuid || !$sip || !$sport){
            $this->Session->write('m', $this->ActiveCall->create_json(101, 'Failure!'));
        } else {
            $back = $this->get_connect_obj();
            $rst = $back->backend_kill_channel($sip, $sport,$uuid);
            if($rst){
                $this->Session->write('m', $this->ActiveCall->create_json(201, 'Success!'));
            } else {
                $this->Session->write('m', $this->ActiveCall->create_json(101, 'Failure!'));
            }
        }

        /*$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        $sendStr = "kill_channel {$killid}";
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            socket_write($socket, $sendStr, strlen($sendStr));
            socket_read($socket, 1024, PHP_NORMAL_READ);
        }
        socket_close($socket);*/
        if ($isRedirect) {
            $this->xredirect("/active_calls/reports");
        } else {
            return $rst;
        }
    }

    function get_resources()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $client_id = $_POST['client_id'];
        $type = $_POST['type'];
        $resources = $this->ActiveCall->get_resources($type, $client_id);
        echo json_encode($resources);
    }

    function get_resource_ips()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $resource_id = $_POST['resource_id'];
        $type = $_POST['type'];
        $ips = $this->ActiveCall->get_resource_ips($type, $resource_id);
        echo json_encode($ips);
    }

    function killAll()
    {
        $field = "orig_ip,term_ip";
        $switch_names = $this->ActiveCall->get_switch_name();
        $switch_name = $switch_names ? $switch_names[0] : '';
        $back = $this->get_connect_obj();
        $rst = $back->backend_get_active_call($switch_name, $field, 'all', '1,100');
        unset($rst['total']);
        $show_data = $rst;
        $sql = "select lan_ip, lan_port from switch_profile where switch_name = '$switch_name' limit 1";
        $tmpRes = $this->ActiveCall->query($sql);
        $sip = $tmpRes[0][0]['lan_ip'];
        $sport = $tmpRes[0][0]['lan_port'];
        $countSuccess = 0;
        foreach ($show_data as $item) {
            $killResult = $this->kill($switch_name, base64_encode($item[0]), false);
            if ($killResult) {
                $countSuccess++;
            }
        }

        if ($countSuccess == count($show_data)) {
            $this->Session->write('m', $this->ActiveCall->create_json(201, 'Success!'));
        } else {
            $this->Session->write('m', $this->ActiveCall->create_json(101, 'Some jobs kill failed!'));
        }
        $this->xredirect("/active_calls/reports");
    }

}
