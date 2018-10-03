<?php
class Cdr extends AppModel
{
    var $name = 'Cdr';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';

    function find_currency1()
    {
        $sql = "SELECT currency_id, code FROM currency ORDER BY code ASC";
        return $this->query($sql);
    }

    function find_products()
    {
        $returns = array();
        $sql = "select product_id,name from product";
        $result = $this->query($sql);
        foreach ($result as $item)
        {
            $returns[$item[0]['product_id']] = $item[0]['name'];
        }
        return $returns;
    }

    public function dynamic_routes()
    {
        $returns = array();
        $sql = "select dynamic_route_id, name from dynamic_route";
        $result = $this->query($sql);
        foreach ($result as $item)
        {
            $returns[$item[0]['dynamic_route_id']] = $item[0]['name'];
        }
        return $returns;
    }

    public function route_strategies()
    {
        $returns = array();
        $sql = "select route_strategy_id, name from route_strategy";
        $result = $this->query($sql);
        foreach ($result as $item)
        {
            $returns[$item[0]['route_strategy_id']] = $item[0]['name'];
        }
        return $returns;
    }

    public function get_start_end_time()
    {
        $r = $this->query("SELECT report_count FROM system_parameter LIMIT 1");
        $report_count = $r[0][0]['report_count'];
        $tz = $this->get_sys_timezone();
        $current_time = time();
        if ($report_count == '0')
        {
            $start_date = date('Y-m-d H:i:s', $current_time - (60 * 60));
        }
        else
        {
            $start_date = date("Y-m-d 00:00:00");
        }
        $end_date = date('Y-m-d 23:59:59');
        return compact('start_date', 'end_date', 'tz');
    }

    function code_based_fields(){
        return array( 'asr', 'acd', 'pdd', 'sd_count', 'sdp', 'total_duration', 'total_billable_time', 'total_cost',
            'inter_cost', 'intra_cost', 'local_cost', 'ij_cost', 'average_rate', 'total_calls', 'not_zero_calls', 'busy_calls'
        );
    }

    function code_based_mapping(){
        return array(
            'country code' => 'Country Code',
            'asr' => 'ASR',
            'acd' => 'ACD',
            'pdd' => 'PDD',
            'sd_count' => 'SD Count',
            'sdp' => 'SDP',
            'total_duration' => 'Actual Time',
            'total_billable_time' => 'Billable Time',
            'total_cost' => 'Total Cost',
            'inter_cost' => 'Inter Cost',
            'intra_cost' => 'Intra Cost',
            'local_cost' => 'Local Cost',
            'ij_cost' => 'IJ Cost',
            'average_rate' => 'Avg Rate',
            'total_calls' => 'Total Calls',
            'not_zero_calls' => 'Not Zero Calls',
            'busy_calls' => 'Busy Calls'
        );
    }

    function find_realcdrfield()
    {
        $arr = array(
            'ingress_codec' => 'ingress_codec',
            'egress_codec' => 'egress_codec',
            'server_ip' => 'server_ip',
            'caller_media_ip' => 'caller_media_ip',
            'callee_media_ip' => 'callee_media_ip',
            //'uuid_a'=>'uuid_a',
            //'uuid_b'=>'uuid_b',
            //'ans_time_a'=>'ans_time_a',
            'ans_time_b' => 'ans_time_b',
            '(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id' => 'egress_id',
            '(select name from client where client_id::text = real_cdr.client_id) as client_id' => 'client_id',
            '(select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id' => 'ingress_id',
            //'substring((CURRENT_TIMESTAMP-to_timestamp(substring(real_cdr.ans_time_b from 1 for 10)::bigint))::text from 1 for 8) as duration' => 'duration',
            'EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration' => 'duration',
            'callee_ip_address' => 'callee_ip_address',
            'callee_ani' => 'callee_ani',
            'callee_dnis' => 'callee_dnis',
            'caller_media_port' => 'caller_media_port',
            'caller_media_ip' => 'caller_media_ip',
            'callee_media_port' => 'callee_media_port',
            'callee_media_ip' => 'callee_media_ip',
            'server_orig_port' => 'server_orig_port',
            'server_term_port' => 'server_term_port',
            'ani' => 'ani',
            'dnis' => 'dnis',
            'caller_ip_address' => 'caller_ip_address',
            'orig_caller_packets' => 'orig_caller_packets',
            'orig_caller_bytes' => 'orig_caller_bytes',
            'orig_callee_packets' => 'orig_callee_packets',
            'orig_callee_bytes' => 'orig_callee_bytes',
            'term_caller_packets' => 'term_caller_packets',
            'term_caller_bytes' => 'term_caller_bytes',
            'term_callee_packets' => 'term_callee_packets',
            'term_callee_bytes' => 'term_callee_bytes',
            'a_interval' => 'a_interval',
            'b_interval' => 'b_interval',
            'a_rate' => 'a_rate',
            'b_rate' => 'b_rate',
            'caller_media_port' => 'orig_media_port',
            'caller_media_ip' => 'caller_media_ip',
            'callee_media_port' => 'term_media_port dnis',
            'callee_media_ip' => 'callee_media_ip',
            'orig_code' => 'orig_code',
            'orig_code_name' => 'orig_code_name',
            'orig_country' => 'orig_country',
            'term_code' => 'term_code',
            'term_code_name' => 'term_code_name',
            'term_country' => 'term_country'
        );

        return $arr;
    }

    /*
      'ingress_codec'=>'ingress_codec',
      'egress_codec'=>'egress_codec',
      'server_ip'=>'server_ip',
      //'uuid_a'=>'uuid_a',
      //'uuid_b'=>'uuid_b',
      //'ans_time_a'=>'ans_time_a',
      'ans_time_b'=>'ans_time_b',
      '(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id'=>'egress_id',
      '(select name from client where client_id::text = real_cdr.client_id) as client_id'=>'client_id',
      '(select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id'=>'ingress_id',
      //'substring((CURRENT_TIMESTAMP-to_timestamp(substring(real_cdr.ans_time_b from 1 for 10)::bigint))::text from 1 for 8) as duration' => 'duration',
      'EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration' => 'duration',
      'callee_ip_address'=>'callee_ip_address',
      'callee_ani'=>'callee_ani',
      'callee_dnis'=>'callee_dnis',
      'caller_media_port'=>'caller_media_port',
      'caller_media_ip'=>'caller_media_ip',
      'callee_media_port'=>'callee_media_port',
      'callee_media_ip'=>'callee_media_ip',
      'server_orig_port'=>'server_orig_port',
      'server_term_port'=>'server_term_port',
      'ani'=>'ani',

      'dnis'=>'dnis',
      'caller_ip_address'=>'caller_ip_address',
      'orig_caller_packets'=>'orig_caller_packets',
      'orig_caller_bytes'=>'orig_caller_bytes',
      'orig_callee_packets'=>'orig_callee_packets',
      'orig_callee_bytes'=>'orig_callee_bytes',
      'term_caller_packets'=>'term_caller_packets',
      'term_caller_bytes'=>'term_caller_bytes',
      'term_callee_packets'=>'term_callee_packets',
      'term_callee_bytes'=>'term_callee_bytes',
      'a_interval'=>'a_interval',
      'b_interval'=>'b_interval',
      'a_rate'=>'a_rate',
      'b_rate'=>'b_rate
     */

    function find_client_cdr_field()
    {

//        $arr = array(
//            'connection_type' => 'W/O Media',
//            'release_cause' => 'Release Cause',
//            'start_time_of_date' => 'Start Time',
//            'answer_time_of_date' => 'Answer Time',
//            'egress_code_asr' => 'Egress CODE ASR',
//            'egress_code_acd' => 'Egress CODE ACD',
//            'release_tod' => 'End Time',
//            'release_cause_from_protocol_stack' => 'Response From Egress',
//            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
//            'first_release_dialogue' => 'ORIG/TERM Release',
//            'trunk_id_origination' => 'Ingress Alias',
//            'origination_source_number' => 'ORIG src Number',
//            'origination_destination_number' => 'ORIG DST number',
//            'origination_source_host_name' => 'ORIG IP',
//            'origination_codec_list' => 'ORIG Codecs',
//            'final_route_indication' => 'Final Route',
//            'routing_digits' => 'Translation DNIS',
//            'translation_ani' => 'Translation ANI',
//            'lrn_dnis' => 'LRN Number',
//            'call_duration' => 'Call Duration',
//            'pdd' => 'PDD(ms)',
//            'ring_time' => 'Ring Time(s)',
//            'callduration_in_ms' => 'Callduration in_ms',
//            'ingress_client_bill_time' => 'Ingress Client Bill Time',
//            'ingress_client_bill_result' => 'Ingress Client Bill Result',
//            'ingress_bill_minutes' => 'Ingress bill Minutes',
//            'ingress_client_cost' => 'Ingress Client Cost',
//            'time' => 'Time',
//            'ingress_dnis_type' => 'Ingress DNIS Type',
//            'ingress_rate_type' => 'Ingress Rate Type',
//            'rerate_time' => 'Rerate Time',
//            'lrn_number_vendor' => 'LRN Source',
//            'route_plan' => 'Routing Plan Name',
//            'dynamic_route' => 'Dynamic Route Name',
//            'static_route' => 'Static Route Name',
//            'origination_remote_payload_ip_address' => 'orig_media_ip ani',
//            'origination_remote_payload_udp_address' => 'orig_media_port ani',
//            'termination_remote_payload_ip_address' => 'term_media_ip',
//            'termination_remote_payload_udp_address' => 'term_media_port dnis',
//            'ingress_client_rate' => 'Ingress Client Rate',
//            'trunk_type' => 'Trunk Type',
//        );

        $arr = array(
//            'switch_ip' => 'Switch IP',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_delay_second' => 'Orig Delay Second',
            'term_delay_second' => 'Term Delay Second',
            'release_cause' => 'Release Cause',
            'start_time_of_date' => 'Start Time',
            'answer_time_of_date' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            'release_tod' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'origination_call_id' => 'Origination Call ID',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'trunk_id_termination' => 'Egress Alias',
            'termination_source_number' => 'TERM src Number',
            'termination_destination_number' => 'TERM DST Number',
            'termination_destination_host_name' => 'TERM IP',
            'termination_codec_list' => 'TERM Codecs',
            'termination_source_host_name' => 'Outbound IP address',
            'is_final_call' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(ms)',
            'callduration_in_ms' => 'Call duration in ms',
            'ingress_id' => 'Ingress ID',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'lnp_dipping_cost' => 'Lnp dipping Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'egress_id' => 'Egress Name',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate' => 'Egress Rate',
            'egress_cost' => 'Egress Cost',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_client_id' => 'Egress Client Name',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_erro_string' => 'Egress Trunk Trace',
            'ingress_rate_id' => 'Ingress Rate ID',
            'egress_rate_id' => 'Egress Rate ID',
            'orig_country' => 'Orig Country',
            'orig_code_name' => 'Orig Code Name',
            'orig_code' => 'Orig Code',
            'term_country' => 'Term Country',
            'term_code_name' => 'Term Code Name',
            'term_code' => 'Term Code',
//            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Source',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'Orig Media IP ANI',
            'origination_remote_payload_udp_address' => 'Orig Media Port ANI',
            'termination_remote_payload_ip_address' => 'Term Media IP',
            'termination_remote_payload_udp_address' => 'Egress Media Port',
            'origination_destination_host_name' => 'Class4_IP',
            'origination_local_payload_ip_address' => 'Origination Local Payload IP',
            'origination_local_payload_udp_address' => 'Origination Local Payload Port',
            'termination_local_payload_ip_address' => 'Termination Local Payload IP',
            'termination_local_payload_udp_address' => 'Termination Local Payload Port',
            'trunk_type' => 'Trunk Type',
            'origination_destination_host_name' => 'Origination Profile IP',
            'origination_profile_port' => 'Origination Profile Port',
            'termination_source_host_name' => 'Termination Profile IP',
            'termination_profile_port' => 'Termination Profile Port',
//            'par_id' => 'par_id',
            'paid_user' => 'paid_user',
            'rpid_user' => 'rpid_user',
            'q850_cause' => 'q850 Cause Code',
            'q850_cause_string' => 'q850 Cause',
//            'route_id' => 'Route ID'
        );
        $arr_default = array(
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'call_duration' => 'Call Duration',
            'time' => 'Time',
            'release_cause' => 'Release Cause',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
//            'route_id' => 'Route ID'
        );

        $result = $this->query("SELECT allow_cdr_fields FROM system_parameter LIMIT 1");

        $allow_cdr_fields = explode(';', $result[0][0]['allow_cdr_fields']);

        foreach ($arr as $key => $val)
        {
            if (!in_array($key, $allow_cdr_fields))
            {
                unset($arr[$key]);
            }
        }

        $arr = array_merge($arr_default, $arr);

        return $arr;
    }

    function find_field()
    {

        $arr_agent = [
            'orig_call_duration' => 'Duration',
            'origination_source_number' => 'ANI',
            'origination_destination_number' => 'DNIS',
            'egress_bill_time' => 'Billed Duration'
        ];

        $arr = array(
//          'switch_ip' => 'Switch IP',
            'tax' => 'Tax',
//            'commission'=>'Commission',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_delay_second' => 'Orig Delay Second',
            'term_delay_second' => 'Term Delay Second',
            'release_cause' => 'Release Cause',
            'start_time_of_date' => 'Start Time',
            'answer_time_of_date' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            'release_tod' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'ingress_id as ingress_name' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'origination_call_id' => 'Origination Call ID',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'egress_id as egress_name' => 'Egress Alias',
//            'trunk_id_termination' => 'Egress Alias',
            'termination_source_number' => 'TERM src Number',
            'termination_destination_number' => 'TERM DST Number',
            'termination_destination_host_name' => 'TERM IP',
            'termination_codec_list' => 'TERM Codecs',
            'termination_source_host_name' => 'Outbound IP address',
            'is_final_call' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(ms)',
            'callduration_in_ms' => 'Call duration in ms',
            'ingress_id' => 'Ingress ID',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'lnp_dipping_cost' => 'Lnp dipping Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'egress_id' => 'Egress Name',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate' => 'Egress Rate',
            'egress_cost' => 'Egress Cost',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_client_id' => 'Egress Client Name',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_erro_string' => 'Egress Trunk Trace',
            'ingress_rate_id' => 'Ingress Rate ID',
            'egress_rate_id' => 'Egress Rate ID',
            'orig_country' => 'Orig Country',
            'orig_code_name' => 'Orig Code Name',
            'orig_code' => 'Orig Code',
            'term_country' => 'Term Country',
            'term_code_name' => 'Term Code Name',
            'term_code' => 'Term Code',
//            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Source',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'Orig Media IP ANI',
            'origination_remote_payload_udp_address' => 'Orig Media Port ANI',
            'termination_remote_payload_ip_address' => 'Term Media IP',
            'termination_remote_payload_udp_address' => 'Egress Media Port',
            'origination_destination_host_name' => 'Class4_IP',
            'origination_local_payload_ip_address' => 'Origination Local Payload IP',
            'origination_local_payload_udp_address' => 'Origination Local Payload Port',
            'termination_local_payload_ip_address' => 'Termination Local Payload IP',
            'termination_local_payload_udp_address' => 'Termination Local Payload Port',
            'trunk_type' => 'Trunk Type',
            'origination_destination_host_name' => 'Orig Switch IP',
            'origination_profile_port' => 'Origination Profile Port',
            'termination_source_host_name' => 'Termination Profile IP',
            'termination_profile_port' => 'Termination Profile Port',
//            'par_id' => 'par_id',
            'paid_user' => 'paid_user',
            'rpid_user' => 'rpid_user',
            'q850_cause' => 'q850 Cause Code',
            'q850_cause_string' => 'q850 Cause',
//            'route_id'  => 'Route ID',
            'conf_id'   => 'Ingress Register User',
            'order_id'  => 'Egress Register User',
        );

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '2')
        {
            return $arr_agent + $arr;
        }

        asort($arr);
        return $arr;
    }

    public function export_cdr($cdr_filters, $return_fields, $start, $end)
    {
        $cdr_filters_encoded = json_encode($cdr_filters);
        $return_fields_encoded = json_encode($return_fields);
        $sql = "INSERT INTO cdr_export_log(fields, conditions, cdr_start_time, cdr_end_time) VALUES($$$return_fields_encoded$$, $$$cdr_filters_encoded$$, '$start', '$end') returning id";
        $result = $this->query($sql);
        return $result[0][0]['id'];
    }

    public function cdr_filters_orders()
    {
        $filters = array();
        $orders = array();

        if (isset($_GET['orig_carrier_select']) and $_GET['orig_carrier_select'] != '')
        {
            $filters['ingress_client_id'] = (int) $_GET['orig_carrier_select'];
            $orders['ingress_client_id'] = 1;
        }

        if (isset($_GET['ingress_alias']) and $_GET['ingress_alias'] != '' and $_GET['ingress_alias'] != 0)
        {
            $filters['ingress_id'] = (int) $_GET['ingress_alias'];
            $orders['ingress_id'] = 1;
        }

        if (isset($_GET['route_prefix']) and $_GET['route_prefix'] != '')
        {
            $filters['route_prefix'] = $_GET['route_prefix'];
        }


        if (isset($_GET ['query'] ['country']) and $_GET ['query']['country'] != '')
        {
            $filters['orig_country'] = new MongoRegex("/{$_GET['query']['country']}/i");
        }

        if (isset($_GET ['query'] ['code']) and $_GET ['query']['code'] != '')
        {
            $filters['orig_code'] = new MongoRegex("/{$_GET['query']['code']}/i");
        }

        if (isset($_GET['disconnect_cause_ingress']) and $_GET['disconnect_cause_ingress'] != '')
        {
            $filters['binary_value_of_release_cause_from_protocol_stack'] = new MongoRegex("/^{$_GET ['query']['disconnect_cause_ingress']}/");
        }

        if (isset($_GET ['query'] ['duration']) and $_GET ['query']['duration'] != '')
        {
            $filters['call_duration'] = array('$gt', 0);
            $orders['call_duration'] = 1;
        }

        if (isset($_GET['cdr_release_cause']) and $_GET['cdr_release_cause'] != '')
        {
            $filters['release_cause'] = (int) $_GET['cdr_release_cause'];
        }

        if (isset($_GET ['query']['src_number']) and $_GET ['query']['src_number'] != '')
        {
            $filters['origination_source_number'] = new MongoRegex("/^{$_GET['query']['src_number']}.*/");
            $orders['origination_source_number'] = 1;
        }

        if (isset($_GET ['query']['dst_number']) and $_GET ['query']['dst_number'] != '')
        {
            $filters['origination_destination_number'] = new MongoRegex("/^{$_GET['query']['dst_number']}.*/");
            $orders['origination_destination_number'] = 1;
        }

        if (isset($_GET ['query']['orig_call_id']) and $_GET ['query']['orig_call_id'] != '')
        {
            $filters['origination_call_id'] = $_GET ['query']['orig_call_id'];
        }


        if (isset($_GET['term_carrier_select']) and $_GET['term_carrier_select'] != '')
        {
            $filters['egress_client_id'] = (int) $_GET['term_carrier_select'];
            $orders['egress_client_id'] = 1;
        }

        if (isset($_GET['egress_alias']) and $_GET['egress_alias'] != '')
        {
            $filters['egress_id'] = (int) $_GET['egress_alias'];
            $orders['egress_id'] = 1;
        }

        if (isset($_GET ['query']['interval_from']) and $_GET ['query']['interval_from'] != '')
        {
            $filters['call_duration'] = array('$gte', (int) $_GET ['query']['interval_from']);
        }

        if (isset($_GET ['query']['interval_to']) and $_GET ['query']['interval_to'] != '')
        {
            $filters['call_duration'] = array('$lt', (int) $_GET ['query']['interval_to']);
        }


        if (isset($_GET ['query']['country_term']) and $_GET ['query']['country_term'] != '')
        {
            $filters['term_country'] = new MongoRegex("/{$_GET['query']['country_term']}/i");
        }

        if (isset($_GET ['query']['code_term']) and $_GET ['query']['code_term'] != '')
        {
            $filters['term_code'] = new MongoRegex("/{$_GET['query']['code_term']}/i");
        }

        if (isset($_GET['disconnect_cause']) and $_GET['disconnect_cause'] != '')
        {
            $filters['release_cause_from_protocol_stack'] = new MongoRegex("/^{$_GET ['query']['disconnect_cause']}/");
        }

        if (isset($_GET ['query'] ['cost']))
        {
            $orig_cost = $_GET ['query'] ['cost'];
            if (!empty($orig_cost))
            {
                if ($orig_cost == 'nonzero')
                {
                    $filters['ingress_client_cost'] = array('$gt', 0);
                }
                if ($orig_cost == 'zero')
                {
                    $filters['ingress_client_cost'] = 0;
                }
            }
        }

        if (isset($_GET['server_ip']) and $_GET['server_ip'] != '')
        {
            $filters['origination_destination_host_name'] = $_GET['server_ip'];
        }

        if (isset($_GET ['query']['term_src_number']) and $_GET ['query']['term_src_number'] != '')
        {
            $filters['termination_source_number'] = new MongoRegex("/^{$_GET['query']['term_src_number']}.*/");
        }

        if (isset($_GET ['query']['term_dst_number']) and $_GET ['query']['term_dst_number'] != '')
        {
            $filters['termination_destination_number'] = new MongoRegex("/^{$_GET['query']['term_dst_number']}.*/");
        }

        if (isset($_GET ['query']['term_call_id']) and $_GET ['query']['term_call_id'] != '')
        {
            $filters['termination_call_id'] = (int) $_GET ['query']['term_call_id'];
        }


        return array($filters, $orders);
    }

    /**
     * 模糊查询
     * @param unknown_type $condition
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function likequery_refill($key, $currPage = 1, $pageSize = 10)
    {

        $condition = "'%" . $key . "%'";
        $order = $this->_get_order();
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(account_payment_id) as c 	from class4_view_card_payment   as  account_payment
	 	where   account_payment_id::text like $condition or  payment_time::text   like $condition 
	 	or payment_method1 like $condition or platform_trace like $condition or refill_type1 like $condition 
	 	or amount::text like $condition  
	 	or (select count(*)>0 from card  where account_payment.account_id::text =card.card_id::text and card.card_number::varchar like $condition )

	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select (select card_number from card where card_id::text = account_payment.account_id::text) as account,* from
		 class4_view_card_payment as account_payment  
		 where   account_payment_id::text like $condition or  payment_time::text   like $condition 
	 	or payment_method1 like $condition or platform_trace like $condition or refill_type1 like $condition 
	 	or amount::text like $condition  
	 	or (select count(*)>0 from card  where account_payment.account_id::text =card.card_id::text and card.card_number::varchar like $condition )
          $order
		  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     *  分页查询Reseller
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function choose_resellers($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;


        $sql = "select count(reseller_id) as c from reseller where 1=1";
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id' or parent = '$reseller_id'";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select reseller_id,name from reseller where 1=1";

        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id' or parent = '$reseller_id'";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    /**
     *  分页查询Client
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function choose_clients($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;


        $sql = "select count(client_id) as c from client where 1=1";
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select client_id,name from  client where 1=1";

        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    /**
     *  分页查询Client
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function choose_cards($currPage = 1, $pageSize = 15, $search = null, $reseller_id = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;


        $sql = "select count(card_id) as c from card where 1=1";
        if (!empty($search))
            $sql .= " and card_number like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select card_id,card_number from  card where 1=1";

        if (!empty($search))
            $sql .= " and card_number like '%$search%'";
        if (!empty($reseller_id))
            $sql .= " and reseller_id = '$reseller_id'";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    public function cdr_search($currPage, $pageSize, $conditions, $table, $search_id, $reseller_or_client_id, $table_name)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;


        $sql = "select count(*) as c from $table_name as cost join cdr on cost.cdr_id::integer=cdr.cdr_id::integer where 1=1";
        if (!empty($conditions))
            $sql .= $conditions;

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $show_fields = explode(',', $_REQUEST['showf']); //显示的字段

        $query_fields = "";

        for ($i = 0; $i < count($show_fields); $i++)
        {
            if ($show_fields[$i] == 'bill_result')
            {
                $result_suc = __('success', true);
                $result_fail = __('failed', true);
                $show_fields[$i] = "case when bill_result='1' then '{$result_suc}' else '{$result_fail}' end as bill_result";
            }
            else if ($show_fields[$i] == 'cdr_id')
            {
                $show_fields[$i] = "cost.cdr_id";
            }
            else if ($show_fields[$i] == 'rate')
            {
                $show_fields[$i] = "cost.rate as rate";
            }
            else if ($show_fields[$i] == 'call_duration')
            {
                $show_fields[$i] = "cost.call_duration";
            }
            else if ($show_fields[$i] == "start_time_of_date" || $show_fields[$i] == 'answer_time_of_date' || $show_fields[$i] == 'release_tod')
            {
                $show_fields[$i] = "timestamp 'epoch'+  {$show_fields[$i]}::bigint* interval '1 second' as {$show_fields[$i]}";
            }
            else if ($show_fields[$i] == 'account')
            {
                //查询Client groups
                $middle_sql = "(select name from $table where $table.$search_id::integer = cost.$search_id::integer) as account";
                if ($table == 'card')
                {
                    $middle_sql = "(select card_number from card where card.card_id = cost.account_id::integer) as account";
                }
                $query_fields .= "," . $middle_sql;
                continue;
            }
            $query_fields .= "," . $show_fields[$i];
        }

        $sql = "select cost.cdr_id as cdr_id $query_fields from $table_name as cost 
							join cdr on cost.cdr_id::integer = cdr.cdr_id::integer 
		 					where 1=1";

        if (!empty($_SESSION['sst_card_id']))
        {
            $sql .= " and cost.account_id = '{$_SESSION['sst_card_id']}'";
        }


        if (!empty($conditions))
            $sql .= $conditions;



        //下载判断

        if (!empty($_REQUEST['output']))
        {
            if ($_REQUEST['output'] == 'csv')
            {

                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                $this->export__sql_data('导出cdr', $sql, 'cdr');
                Configure::write('debug', 0);
                $this->layout = '';
            }
            else
            {
                $sql .= " limit '$pageSize' offset '$offset'";
                $results = $this->query($sql);
                $page->setDataArray($results); //Save Data into $page
                return $page;
            }
        }
        else
        {
            $sql .= " limit '$pageSize' offset '$offset'";
            $results = $this->query($sql);
            $page->setDataArray($results); //Save Data into $page
            return $page;
        }
    }

//j重写导出

    function ex_download_by_sql($sql, $options = array())
    {
        if (empty($sql))
        {
            return false;
        }
        $database_export_path = Configure::read('database_export_path');
        if (empty($database_export_path))
        {
            $database_export_path = "/tmp/exports";
        }
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id']))
        {
            $user_id = $_SESSION ['sst_user_id'];
        }

        $objectives = '';
        if (isset($options['objectives']) && !empty($options['objectives']))
        {
            $objectives = $options['objectives'];
        }
        if (isset($options['file_name']) && !empty($options['file_name']))
        {
            $file_name = $options['file_name'];
        }
        else
        {
            if (!empty($objectives))
            {
                $file_name = $objectives . '_' . time() . '.csv';
            }
            else
            {
                $file_name = 'download_' . time() . '.csv';
            }
        }

        $copy_db_file = Configure::read('database_actual_export_path') . DS . $file_name;
        $copy_file = $database_export_path . DS . $file_name;

        $copy_sql = "COPY ($sql)  TO   '$copy_db_file'  CSV HEADER "; //daochu
        $this->query($copy_sql); //导出数据	
        App::import('Model', 'Importlog');

        if (!file_exists($copy_file))
        {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_FAIL;
            $file_size = 0;
        }
        else
        {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS;
            $file_size = filesize($copy_file);
        }

        $user = new Importlog ();
        $data = array();
        $data ['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = gmtnow();
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $copy_file;

        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;

        $data ['Importlog']['download_sql'] = $sql;
        $data ['Importlog']['type'] = Importlog::ERROR_TYPE_DOWNLOAD;
        $data ['Importlog']['filesize'] = $file_size;
        $user->save($data ['Importlog']);
        if ($status == Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS)
        {
            $this->ex_download_csv($copy_file, $file_name); //下载
            return true;
        }
        else
        {
            throw new Exception('Server Configure Error,Please Contact Administrator');
            return false;
        }
    }

    public function ex_download_csv($download_file, $file_name)
    {
        $file_size = filesize($download_file);
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $file_size");
        header("Content-Disposition: attachment; filename=" . $file_name);
        $fp = fopen($download_file, "r");
        $buffer_size = 1024;
        $cur_pos = 0;
        while (!feof($fp) && $file_size - $cur_pos > $buffer_size)
        {
            $buffer = fread($fp, $buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }

        $buffer = fread($fp, $file_size - $cur_pos);
        echo $buffer;
        fclose($fp);
        return true;
    }

    public function findTechPerfix($resource_id)
    {
        $res = $this->query("select tech_prefix from resource_prefix where resource_id = " . $resource_id);
        return $res;
    }

    public function  get_orig_summary_reports($start_date, $end_date, $time_data, $gmt, $where = "", $group_filed = "")
    {
        $sql = "";
        foreach($time_data as $key=>$value){
            $table_name = CDR_TABLE . $value;
            
            $union = "";
            if(!empty($sql)){   
                $union = " union all ";
            }
            
            $sql .= <<<EOD
                        {$union}  select report_time::date,ingress_client_id,ingress_id,
                        {$group_filed}
                        duration, not_zero_calls_30, duration_30, not_zero_calls_6, duration_6,
                        ingress_bill_time, ingress_total_calls, not_zero_calls
                        from    
                        {$table_name}  
                        where   report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' {$where} 
EOD;
        }
        
        $sql = <<<EOD
                    select 
                    report_time::date,ingress_client_id,ingress_id, {$group_filed}
                    sum(duration) as total_time,
                    sum(not_zero_calls_30) as calls_30,
                    sum(duration_30) as time_30,
                    sum(not_zero_calls_6) as calls_6,
                    sum(duration_6) as time_6,
                    sum(ingress_bill_time) as bill_time,
                    sum(ingress_total_calls) as total_calls, 
                    sum(not_zero_calls) as not_zero_calls ,
                    (SELECT alias FROM resource WHERE resource_id = ingress_id) as resource_name, 
                    (SELECT name FROM client WHERE client_id = ingress_client_id) as client_name
                    from (
                    $sql
                    ) as result
                    group by ingress_id,report_time::date,  {$group_filed}  ingress_client_id
                    order by 1,2
                    
EOD;
        $result = $this->query($sql);
        return $result;
    }

    public function get_term_summary_reports($start_date, $end_date, $time_data, $gmt, $where = "", $group_filed = "")
    {
        $sql = "";
        foreach($time_data as $key=>$value){
            $table_name = CDR_TABLE . $value;
            
            $union = "";
            if(!empty($sql)){   
                $union = " union all ";
            }
            
            $sql .= <<<EOD
                        {$union}  select report_time::date,egress_client_id,egress_id,
                        {$group_filed}
                        duration, not_zero_calls_30, duration_30, not_zero_calls_6, duration_6,
                        egress_bill_time, egress_total_calls, not_zero_calls
                        from    
                        {$table_name}  
                        where   report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' {$where} 
EOD;
        }
        
        $sql = <<<EOD
                    select 
                    report_time::date,egress_client_id,egress_id, {$group_filed}
                    sum(duration) as total_time,
                    sum(not_zero_calls_30) as calls_30,
                    sum(duration_30) as time_30,
                    sum(not_zero_calls_6) as calls_6,
                    sum(duration_6) as time_6,
                    sum(egress_bill_time) as bill_time,
                    sum(egress_total_calls) as total_calls, 
                    sum(not_zero_calls) as not_zero_calls ,
                    (SELECT alias FROM resource WHERE resource_id = egress_id) as resource_name, 
                    (SELECT name FROM client WHERE client_id = egress_client_id) as client_name
                    from (
                    $sql
                    ) as result
                    group by egress_id,report_time::date, {$group_filed}  egress_client_id
                    order by 1,2
                    
EOD;
        $result = $this->query($sql);
        return $result;
    }

    public function get_daily_orig_summary($start_date, $end_date, $time_data, $gmt)
    {
        $sql = "";
        foreach($time_data as $key=>$value){
            $table_name = CDR_TABLE . $value;
            
            $union = "";
            if(!empty($sql)){   
                $union = " union all ";
            }
            
            $sql .= <<<EOD
                        {$union}  select report_time::date,ingress_client_id,ingress_id,
                        duration, 
                        ingress_bill_time
                        from    
                        {$table_name}  
                        where   report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' 
EOD;
        }
        
        $sql = <<<EOD
                    select 
                    ingress_id,ingress_client_id,report_time::date, 
                    sum(duration) as total_time,
                    sum(ingress_bill_time) as bill_time,
                    (SELECT alias FROM resource WHERE resource_id = ingress_id) as resource_name, 
                    (SELECT name FROM client WHERE client_id = ingress_client_id) as client_name
                    from (
                    $sql
                    ) as result
                    group by ingress_id,report_time::date, ingress_client_id
                    order by 1,2
                    
EOD;
        $result = $this->query($sql);
        return $result;
    }

    public function get_daily_term_summary($start_date, $end_date, $time_data, $gmt)
    {
        $sql = "";
        foreach($time_data as $key=>$value){
            $table_name = CDR_TABLE . $value;
            
            $union = "";
            if(!empty($sql)){   
                $union = " union all ";
            }
            
            $sql .= <<<EOD
                        {$union}  select report_time::date,egress_client_id,egress_id,
                        duration, 
                        egress_bill_time
                        from    
                        {$table_name}  
                        where   report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' 
EOD;
        }
        
        $sql = <<<EOD
                    select 
                    egress_id,egress_client_id,report_time::date, 
                    sum(duration) as total_time,
                    sum(egress_bill_time) as bill_time,
                    (SELECT alias FROM resource WHERE resource_id = egress_id) as resource_name, 
                    (SELECT name FROM client WHERE client_id = egress_client_id) as client_name
                    from (
                    $sql
                    ) as result
                    group by egress_id,report_time::date, egress_client_id
                    order by 1,2
                    
EOD;
        $result = $this->query($sql);
        return $result;
    }

    public function get_report_maxtime($start_time, $end_time)
    {
        //分表
        $date_arr = $this->_get_date_result_admin($start_time,$end_time,'cdr_report2%');
        $org_sql ='';
        foreach($date_arr as $value){
            //$table_name = "cdr_report".$value;
            $table_name = CDR_TABLE.$value;
            if(!$this->table_exists(CDR_TABLE . $value)){
                continue;
            }
            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  select report_time  from   {$table_name}  where report_time between '{$start_time}' and '{$end_time}'";

        }

        $sql = "SELECT max(report_time) + interval '1 hour' as end_time FROM ( $org_sql ) as tmp";
        $result = $this->query($sql);
        return $result[0][0]['end_time'];
    }

    public function get_cdr_export_log_count($where)
    {
        $sql = "SELECT count(*) FROM mail_cdr_log $where";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    public function get_cdr_export_log($pageSize, $offset, $where)
    {
        $sql = "SELECT * FROM mail_cdr_log $where ORDER BY id DESC LIMIT $pageSize OFFSET $offset";
        return $this->query($sql);
    }

    public function get_cdr_export_log_detail($id)
    {
        $sql = "select * from mail_cdr_log_detail where mail_cdr_log_id = {$id} order by id asc";
        return $this->query($sql);
    }
    
    /*
     *
     * Daily Channel Usage Report
     */
    public function get_all_daily_channel_usage_report($start_date, $end_date, $gmt, $serverIp)
    {
        $sql = "select report_time::date, max(ingress_channels) from qos_total where report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' and server_ip = '{$serverIp}' group by report_time::date order by 1";
        $result = $this->query($sql);
        return $result;
    }
    
    /*
     *
     * Daily Channel Usage Report
     */
    public function get_page_daily_channel_usage_report($start_date, $end_date, $gmt, $offset, $serverIp)
    {
        $sql = "select report_time::date, max(ingress_channels) from qos_total where report_time between '{$start_date} {$gmt}' and '{$end_date} {$gmt}' and server_ip = '{$serverIp}' group by report_time::date order by 1 limit 100 offset $offset";
        $result = $this->query($sql);
        return $result;
    }

}
