<?php
class ActiveCall extends AppModel
{

    var $name = 'ActiveCall';
    var $useTable = FALSE;

    public function default_show_fields()
    {
        return array
            (
            'call_duration' => 1,
            'type' => 0
            , 'search_ip' => 0
            , 'search_port' => 0
            , 'origination_protocol' => 0
            , 'origination_trunk_type' => 0
            , 'agent_type' => 0
            , 'origination_buyer_test' => 0
            , 'origination_trunk_type2' => 0
            , 'origination_billing_method' => 0
            , 'origination_local_port' => 0
            , 'origination_remote_port' => 0
            , 'origination_ani' => 1
            , 'origination_dnis' => 1
            , 'origination_uuid_a' => 0
            , 'origination_alias' => 0
            , 'agent_id' => 0
            , 'origination_local_ip' => 0
            , 'origination_remote_ip' => 0
            , 'origination_client_id' => 1
            , 'origination_sip_callid' => 0
            , 'origination_campaign_id' => 0
            , 'origination_resource_id' => 1
            , 'origination_resource_ip_id' => 1
            , 'origination_start_epoch' => 1
            , 'termination_protocol' => 0
            , 'termination_trunk_type' => 0
            , 'termination_trunk_type2' => 0
            , 'termination_billing_method' => 0
            , 'termination_local_port' => 0
            , 'termination_remote_port' => 0
            , 'termination_ani' => 0
            , 'termination_dnis' => 0
            , 'termination_uuid_a' => 0
            , 'termination_alias' => 0
            , 'termination_local_ip' => 0
            , 'termination_client_id' => 0
            , 'termination_prefix_id' => 0
            , 'termination_remote_ip' => 0
            , 'termination_other_uuid_a' => 0
            , 'termination_sip_callid' => 0
            , 'termination_product_id' => 0
            , 'termination_resource_id' => 1
            , 'termination_resource_ip_id' => 1
            , 'termination_start_epoch' => 0
            , 'origination_seconds' => 0
            , 'origination_interval' => 0
            , 'origination_us_port' => 0
            , 'origination_min_time' => 0
            , 'origination_them_port' => 0
            , 'origination_grace_time' => 0
            , 'origination_uuid_b' => 0
            , 'origination_code' => 0
            , 'origination_us_ip' => 0
            , 'origination_codec' => 1
            , 'origination_them_ip' => 0
            , 'origination_country' => 0
            , 'origination_code_name' => 0
            , 'origination_trans_ani' => 0
            , 'origination_trans_dnis' => 0
            , 'origination_lrn_number' => 0
            , 'origination_currency_id' => 0
            , 'origination_rate_table_id' => 0
            , 'origination_rate' => 1
            , 'origination_setup_fee' => 0
            , 'origination_answer_epoch' => 0
            , 'termination_seconds' => 0
            , 'termination_interval' => 0
            , 'termination_media_type' => 0
            , 'termination_us_port' => 0
            , 'termination_min_time' => 0
            , 'termination_them_port' => 0
            , 'termination_grace_time' => 0
            , 'termination_uuid_b' => 0
            , 'termination_code' => 0
            , 'termination_us_ip' => 0
            , 'termination_codec' => 1
            , 'termination_country' => 0
            , 'termination_them_ip' => 0
            , 'termination_code_name' => 0
            , 'termination_other_uuid_b' => 0
            , 'termination_currency_id' => 0
            , 'termination_rate_table_id' => 0
            , 'termination_rate' => 1
            , 'termination_setup_fee' => 0
            , 'termination_answer_epoch' => 0
            , 'origination_us_bytes' => 0,
            'origination_us_packets' => 0,
            'origination_them_bytes' => 0,
            'origination_them_packets' => 0,
            'termination_us_bytes' => 0,
            'termination_us_packets' => 0,
            'termination_them_bytes' => 0,
            'termination_them_packets' => 0,
        );
    }

    public function ignores()
    {
        return array(
            'type',
            'search_ip',
            'search_port',
            'origination_buyer_test',
            'termination_trunk_type',
            'termination_trunk_type2',
            'termination_prefix_id',
            'origination_setup_fee',
            'termination_setup_fee',
            'origination_us_bytes',
            'origination_us_packets',
            'origination_them_bytes',
            'origination_them_packets',
            'termination_us_bytes',
            'termination_us_packets',
            'termination_them_bytes',
            'termination_us_packets',
        );
    }

    public function fields()
    {
        return array(
            'call_duration' => 'call_duration',
            'type' => 'type',
            'search_ip' => 'search_ip',
            'search_port' => 'search_port',
            /* origination */
            'origination_protocol' => 'origination_protocol',
            'origination_trunk_type' => 'switch_type',
//            'agent_type' => 'commission',
            'origination_buyer_test' => 'origination_buyer_test', // hidden
            'origination_trunk_type2' => 'origination_trunk_type',
            'origination_billing_method' => 'origination_billing_method',
            'origination_local_port' => 'origination_local_port',
            'origination_remote_port' => 'origination_remote_port',
            'origination_ani' => 'origination_ani',
            'origination_dnis' => 'origination_dnis',
            'origination_uuid_a' => 'origination_uuid',
            'origination_alias' => 'origination_alias',
            'agent_id' => 'agent_id', // hidden
            'origination_local_ip' => 'origination_local_ip',
            'origination_remote_ip' => 'origination_remote_ip',
            'origination_client_id' => 'origination_client',
            'origination_sip_callid' => 'origination_sip_callid',
            'origination_campaign_id' => 'origination_campaign',
            'origination_resource_id' => 'origination_resource',
            'origination_resource_ip_id' => 'origination_resource_ip',
            'origination_start_epoch' => 'origination_start_epoch',
            /* termination */
            'termination_protocol' => 'termination_protocol',
            'termination_trunk_type' => 'termination_trunk_type', // hidden
            'termination_trunk_type2' => 'termination_trunk_type2', // hidden
            'termination_billing_method' => 'termination_billing_method',
            'termination_local_port' => 'termination_local_port',
            'termination_remote_port' => 'termination_remote_port',
            'termination_ani' => 'termination_ani',
            'termination_dnis' => 'termination_dnis',
            'termination_uuid_a' => 'termination_uuid',
            'termination_alias' => 'termination_alias',
            'termination_local_ip' => 'termination_local_ip',
            'termination_client_id' => 'termination_client',
            'termination_prefix_id' => 'termination_prefix', // hidden
            'termination_remote_ip' => 'termination_remote_ip',
            'termination_other_uuid_a' => 'termination_other_uuid',
            'termination_sip_callid' => 'termination_sip_callid',
            'termination_product_id' => 'termination_product',
            'termination_resource_id' => 'termination_resource',
            'termination_resource_ip_id' => 'termination_resource_ip',
            'termination_start_epoch' => 'termination_start_epoch',
            /* origination */
            'origination_seconds' => 'origination_seconds',
            'origination_interval' => 'origination_interval',
            'origination_us_port' => 'origination_us_port',
            'origination_min_time' => 'origination_min_time',
            'origination_them_port' => 'origination_them_port',
            'origination_grace_time' => 'origination_grace_time',
            'origination_uuid_b' => 'origination_uuid',
            'origination_code' => 'origination_code',
            'origination_us_ip' => 'origination_us_ip',
            'origination_codec' => 'origination_codec',
            'origination_them_ip' => 'origination_them_ip',
            'origination_country' => 'origination_country',
            'origination_code_name' => 'origination_code_name',
            'origination_trans_ani' => 'origination_trans_ani',
            'origination_trans_dnis' => 'origination_trans_dnis',
            'origination_lrn_number' => 'origination_lrn_number',
            'origination_currency_id' => 'origination_currency',
            'origination_rate_table_id' => 'origination_rate_table',
            'origination_rate' => 'origination_rate',
            'origination_setup_fee' => 'origination_setup_fee', // hidden
            'origination_answer_epoch' => 'origination_answer_epoch',
            /* termination */
            'termination_seconds' => 'termination_seconds',
            'termination_interval' => 'termination_interval',
            'termination_media_type' => 'termination_media_type',
            'termination_us_port' => 'termination_us_port',
            'termination_min_time' => 'termination_min_time',
            'termination_them_port' => 'termination_them_port',
            'termination_grace_time' => 'termination_grace_time',
            'termination_uuid_b' => 'termination_uuid',
            'termination_code' => 'termination_code',
            'termination_us_ip' => 'termination_us_ip',
            'termination_codec' => 'termination_codec',
            'termination_country' => 'termination_country',
            'termination_them_ip' => 'termination_them_ip',
            'termination_code_name' => 'termination_code_name',
            'termination_other_uuid_b' => 'termination_other_uuid',
            'termination_currency_id' => 'termination_currency',
            'termination_rate_table_id' => 'termination_rate_table',
            'termination_rate' => 'termination_rate',
            'termination_setup_fee' => 'termination_setup_fee', // hidden
            'termination_answer_epoch' => 'termination_answer_epoch',
            'origination_us_bytes' => 'origination_us_bytes',
            'origination_us_packets' => 'origination_us_packets',
            'origination_them_bytes' => 'origination_them_bytes',
            'origination_them_packets' => 'origination_them_packets',
            'termination_us_bytes' => 'termination_us_bytes',
            'termination_us_packets' => 'termination_us_packets',
            'termination_them_bytes' => 'termination_them_bytes',
            'termination_them_packets' => 'termination_them_packets',
        );
    }

    public function get_clients()
    {
        $data = array();
        $sql = "select client_id, name from client order by name asc";
        $result = $this->query($sql);
        foreach ($result as $row)
        {
            $data[$row[0]['client_id']] = $row[0]['name'];
        }
        return $data;
    }

    public function get_resources($type = "ingress", $client_id = NULL)
    {
        $data = array();
        if ($client_id != NULL)
        {
            $conditions = " and client_id = {$client_id}";
        }
        else
        {
            $conditions = '';
        }
        $sql = "select resource_id, alias from resource where {$type} = true {$conditions} order by alias asc";
        $result = $this->query($sql);
        foreach ($result as $row)
        {
            $data[$row[0]['resource_id']] = $row[0]['alias'];
        }
        return $data;
    }

    public function get_resource_ips($type = "ingress", $resource_id = NULL)
    {
        $data = array();
        if ($resource_id != NULL)
        {
            $conditions = " and resource.resource_id = {$resource_id}";
        }
        else
        {
            $conditions = '';
        }
        $sql = "select resource_ip_id, ip from resource_ip inner join resource on resource_ip.resource_id = resource.resource_id where resource.{$type} = true {$conditions} order by ip asc";
        $result = $this->query($sql);
        foreach ($result as $row)
        {
            if (!in_array($row[0]['ip'], $data))
            {
                $data[$row[0]['resource_ip_id']] = $row[0]['ip'];
            }
        }
        return $data;
    }

    public function get_rate_tables()
    {
        $data = array();
        $sql = "select rate_table_id, name from rate_table order by name asc";
        $result = $this->query($sql);
        foreach ($result as $row)
        {
            $data[$row[0]['rate_table_id']] = $row[0]['name'];
        }
        return $data;
    }

    public function get_switch_name()
    {
        /*$sql = "SELECT DISTINCT(active_call_ip, active_call_port),active_call_ip,active_call_port,lan_ip,lan_port "
                . "FROM voip_gateway"
                . " WHERE active_call_ip is not null AND active_call_port is not null;";*/
        $sql = "select DISTINCT(switch_name) from switch_profile";
        $data = $this->query($sql);
        $return_arr = array();
        foreach ($data as $data_item)
        {
//            $value = $data_item[0]['active_call_ip'] . ":" . $data_item[0]['active_call_port'];
//            $key = $data_item[0]['lan_ip'] . ":" . $data_item[0]['lan_port'];

            $return_arr[] = $data_item[0]['switch_name'];
        }
//        pr($return_arr); //die;
        return $return_arr;
    }

    public function get_resource_ip($id)
    {
        $resource_ip_id = intval($id);

        $data = $this->query("SELECT ip FROM resource_ip WHERE resource_ip_id = $resource_ip_id");

        $ip = isset($data[0][0]['ip']) ? $data[0][0]['ip'] : null;

        return $ip;
    }

}
