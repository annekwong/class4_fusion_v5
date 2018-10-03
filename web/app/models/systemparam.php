<?php

class Systemparam extends AppModel
{

    //系统参数
    var $name = 'Systemparam';
    var $useTable = "system_parameter";
    var $primaryKey = "sys_id";

    function find_sip_server($server_ip_port)
    {
        $server_arr = explode(':', $server_ip_port);
        $r = $this->query("select ip ,port from   server_platform   where  server_type=2   and ip='$server_arr[0]' and port = $server_arr[1] limit 1");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['ip'];
            $l [$key] = $r [$i] [0] ['port'];
        }
        return $l;
    }

    function find_all_class4()
    {
        //$r = $this->query("select ip ,port, info_ip, info_port from   server_platform   where  server_type=2 ");
        $r = $this->query("select sip_ip, sip_port,lan_ip, lan_port from switch_profile");
        $size = count($r);
        $l = array();
        $l['all'] = 'All';
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i] [0] ['sip_ip'] . ':' . $r [$i] [0] ['sip_port'];
            $val = $r[$i][0]['sip_ip'] . ':' . $r[$i][0]['sip_port'];
            $l[$key] = $val;
        }
        return $l;
    }

    function get_daily_cdr_fields($type)
    {
        $sql = "SELECT field, label FROM daily_cdr_fields WHERE type = {$type}  ORDER BY id ASC";
        $data = $this->query($sql);
        $return = array();
        foreach ($data as $item)
        {
            if(in_array($type,array(2,3))){
                if(!strcmp($item[0]['label'],'Answer Time'))
                    $return['answer_time_of_date'] = $item[0]['label'];
                elseif(!strcmp($item[0]['label'],'End Time'))
                    $return['release_tod'] = $item[0]['label'];
                elseif(!strcmp($item[0]['label'],'Start Time'))
                    $return['start_time_of_date'] = $item[0]['label'];
                else
                    $return[$item[0]['field']] = $item[0]['label'];
            }else{
                $return[$item[0]['field']] = $item[0]['label'];
            }
        }
        return $return;
    }

    function get_incoming_cdr_fields($show_cdr = false)
    {
        $data = array(
            '(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)' => 'Answer Time',
            'call_duration' => 'Call Duration',
            'callduration_in_ms' => 'Call duration in ms',
            'origination_destination_host_name' => 'Switch IP',
            'dynamic_route' => 'Dynamic Route Name',
            '(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)' => 'End Time',
            'final_route_indication' => 'Final Route',
            'trunk_id_origination' => 'Ingress Alias',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_cost' => 'Ingress Client Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_id' => 'Ingress ID',
            'ingress_rate_id' => 'Ingress Rate ID',
            'ingress_rate_type' => 'Ingress Rate Type',
            'lrn_dnis' => 'LRN Number',
            'lrn_number_vendor' => 'LRN Number Vendor',
            'lnp_dipping_cost' => 'LNP dipping Cost',
            'origination_codec_list' => 'Orig Codecs',
            'origination_destination_number' => 'Orig DST Number',
            'origination_source_host_name' => 'Orig IP',
            'origination_source_number' => 'Orig SRC Number',
            'first_release_dialogue' => 'Orig/Term Release',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_code' => 'Orig Code',
            'orig_code_name' => 'Orig Code Name',
            'orig_country' => 'Orig Country',
            'orig_delay_second' => 'Orig Delay Second',
            'origination_remote_payload_ip_address' => 'Orig Media IP ANI',
            'origination_remote_payload_udp_address' => 'Orig Media Port ANI',
            'origination_call_id' => 'Origination Call ID',
            'pdd' => 'PDD(ms)',
            'release_cause' => 'Release Cause',
            'rerate_time' => 'Rerate Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response To Ingress',
            'ring_time' => 'Ring Time(ms)',
            'route_plan' => 'Routing Plan Name',
            '(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)' => 'Start Time',
            'static_route' => 'Static Route Name',
            'time' => 'Time',
            'translation_ani' => 'Translation ANI',
            'routing_digits' => 'Translation DNIS',
            'q850_cause' => 'Q850 Cause Code',
            'q850_cause_string' => 'Q850 Cause',
            'conf_id'   => 'Ingress Register User',
        );
        if($show_cdr)
        {
            unset($data['(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)']);
            unset($data['(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)']);
            unset($data['(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)']);
            $data_temp = array(
                'answer_time_of_date' => 'Answer Time',
                'release_tod' => 'End Time',
                'start_time_of_date' => 'Start Time'
            );
            $data = array_merge($data_temp,$data);
        }
        return $data;
    }

    public function get_outgoing_cdr_fields($show_cdr = false)
    {
        $data = array(
            '(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)' => 'Answer Time',
            'call_duration' => 'Call Duration',
            'callduration_in_ms' => 'Call duration in ms',
            'origination_destination_host_name' => 'Switch IP',
            'dynamic_route' => 'Dynamic Route Name',
            'trunk_id_termination' => 'Egress Alias',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_code_acd' => 'Egress Code ACD',
            'egress_code_asr' => 'Egress Code ASR',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_client_id' => 'Egress Client Name',
            'egress_cost' => 'Egress Cost',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_id' => 'Egress Name',
            'egress_rate' => 'Egress Rate',
            'egress_rate_id' => 'Egress Rate ID',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_erro_string' => 'Egress Trunk Trace',
            '(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)' => 'End Time',
            'final_route_indication' => 'Final Route',
            'lrn_dnis' => 'LRN Number',
            'lrn_number_vendor' => 'LRN Number Vendor',
            'lnp_dipping_cost' => 'LNP dipping Cost',
            'first_release_dialogue' => 'Orig/Term Release',
            'pdd' => 'PDD(ms)',
            'release_cause' => 'Release Cause',
            'rerate_time' => 'Rerate Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response To Ingress',
            'ring_time' => 'Ring Time(ms)',
            'route_plan' => 'Routing Plan Name',
            '(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)' => 'Start Time',
            'static_route' => 'Static Route Name',
            'termination_codec_list' => 'Term Codecs',
            'termination_destination_number' => 'Term DST Number',
            'termination_destination_host_name' => 'Term IP',
            'termination_source_number' => 'Term SRC Number',
            'term_code' => 'Term Code',
            'term_code_name' => 'Term Code Name',
            'term_country' => 'Term Country',
            'term_delay_second' => 'Term Delay Second',
            'termination_remote_payload_ip_address' => 'Term Media IP',
            'termination_remote_payload_udp_address' => 'Term Media Port DNIS',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'translation_ani' => 'Translation ANI',
            'routing_digits' => 'Translation DNIS',
            'q850_cause' => 'Q850 Cause Code',
            'q850_cause_string' => 'Q850 Cause',
            'order_id'  => 'Egress Register User',
        );
        if($show_cdr)
        {
            unset($data['(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)']);
            unset($data['(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)']);
            unset($data['(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)']);
            $data_temp = array(
                'answer_time_of_date' => 'Answer Time',
                'release_tod' => 'End Time',
                'start_time_of_date' => 'Start Time'
            );
            $data = array_merge($data_temp,$data);
        }

        return $data;
    }

    function findsysparam()
    {
        $r = $this->query("select * from system_parameter  offset 0  limit 1");
        return $r;
    }

    function get_carriers($type = 'ingress')
    {
        $sql = <<<EOT
    select distinct client.client_id, client.name from resource 
left join client on client.client_id = resource.client_id 
where {$type}=true order by client.name asc    
EOT;
        $result = $this->query($sql);
        return $result;
    }

    function get_resource($type = 'ingress')
    {
        $sql = "select resource_id, alias from resource where {$type} = true order by alias asc";
        $result = $this->query($sql);
        return $result;
    }

    public function get_all_ftp()
    {
        $sql = "select id, alias,frequency from ftp_conf order by alias";
        $result = $this->query($sql);
        return $result;
    }

    public function get_single_ftp($id)
    {
        $sql = "select id, alias,frequency from ftp_conf where id = {$id}";
        $result = $this->query($sql);
        return $result;
    }

    public function reset_failover($table_name)
    {
        if (!in_array($table_name, array('origination_global_failover', 'termination_global_failover')))
        {
            return FALSE;
        }
        $this->begin();

        if ($this->query("DELETE FROM {$table_name};") === false)
        {
            $this->rollback();
            return FALSE;
        }
        $insert_sql = "INSERT INTO {$table_name} (failover_strategy, from_sip_code, to_sip_code,to_sip_string) SELECT failover_strategy, from_sip_code, to_sip_code,to_sip_string FROM global_failover; ";
        if ($this->query($insert_sql) === false)
        {
            $this->rollback();
            return FALSE;
        }
        $this->commit();
        return TRUE;
    }

}
