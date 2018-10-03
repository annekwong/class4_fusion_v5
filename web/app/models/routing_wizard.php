<?php

class RoutingWizard extends AppModel {
    var $name = 'RoutingWizard';
    var $useTable = "routing_wizard_list";
    var $primaryKey = "id";

    public function get_clients()
    {
        $sql = "select client_id, name FROM client WHERE status is true ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_ingress_trunks($client_id=0)
    {

        $sql = empty($client_id) ? "SELECT resource_id, alias FROM resource WHERE ingress=true ORDER BY alias asc" :
            "SELECT resource_id, alias FROM resource WHERE ingress=true and is_virtual is not true AND alias is not null and client_id=$client_id ORDER BY alias asc";
        return $this->query($sql);
    }

    public function get_ingress_ips($trunk_id=0)
    {

        $sql = empty($trunk_id) ? "SELECT ip, port FROM resource_ip ORDER BY resource_ip_id asc" :
            "SELECT ip, port FROM resource_ip WHERE resource_id=$trunk_id ORDER BY resource_ip_id asc";
        return $this->query($sql);
    }
    public function get_rate_generation_templates()
    {

        $sql = "SELECT id, name FROM rate_generation_template order by name ASC";
        return $this->query($sql);
    }

    public function get_code_decks()
    {
        $sql = "SELECT code_deck_id, name FROM code_deck ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_currencies()
    {
        $sql = "SELECT currency_id, code FROM currency WHERE active = true ORDER by currency_id ASC";
        return $this->query($sql);
    }

    public function alreay_exists_ratetable($name)
    {
        $sql = "SELECT count(*) FROM rate_table WHERE name = '{$name}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }

    public function get_client_email_by_ratetable($id)
    {
        $sql = "SELECT DISTINCT resource.resource_id,resource.alias,client.rate_email,client.email FROM resource INNER JOIN
client on resource.client_id = client.client_id LEFT JOIN resource_prefix on resource.resource_id = resource_prefix.resource_id
WHERE resource_prefix.rate_table_id = {$id} AND resource.ingress = TRUE or resource.rate_table_id = {$id}";
        $ingress_mail_arr = $this->query($sql);
        //pr($ingress_mail_arr);
        return $ingress_mail_arr;
    }

    public function create_carrier($client_data)
    {
        $default_currency_info = $this->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $default_currency_id = $default_currency_info[0][0]['sys_currency'];
        $client_data['currency_id'] = $default_currency_id;
        $Client = new Client();
        $data = array();
        $data['Client'] = $client_data;

        $unlimited_credit = $data['Client']['unlimited_credit'] == "0" ? false : true;
        $data['Client']['enough_balance'] = $unlimited_credit ? "true" : "false";
        $data['Client']['update_at'] = date("Y-m-d H:i:s");
        $data['Client']['update_by'] = $_SESSION['sst_user_name'];

        $rst = $Client->validate_client($data,$_POST);
        if($rst){
            return false;
        }
        $data = $Client->save($data);
        $return['client_id'] = $Client->getLastInsertID();
//        $return['client_id'] = $data['Client']['client_id'];
//        $return['name'] = $data['Client']['name'];
        $sql = "INSERT INTO client_balance (client_id) VALUES ({$return['client_id']})";
        $this->query($sql);
        return $return;
    }

    public function create_trunk($trunk_name, $client_id, $rate_table = 0)
    {
        if (empty($trunk_name))
        {
            $this->create_json_array('#alias', 201, __('gate_red_id', true));
            return false; //有错误信息
        }
        if (!empty($alias))
        {
            $c = $this->query("select count(*) from resource where alias=$alias");
            if ($c != 0)
            {
                $this->create_json_array('#alias', 101, __($alias . 'is already in use!', true));
                return false; //有错误信息
            }
        }

        $list = $this->query("select enough_balance from  client  where  client_id=$client_id");
        $enough_balance = $list[0][0]['enough_balance'] ? 'true' : 'false';
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];

        $sql = "INSERT INTO resource (alias, ingress, client_id, enough_balance,update_at,update_by) VALUES ('{$trunk_name}', true, {$client_id},'{$enough_balance}','{$update_at}','{$update_by}') RETURNING resource_id";
        if ($rate_table)
        {
            $sql = "INSERT INTO resource (alias, client_id, rate_table_id, enough_balance,update_at,update_by) VALUES ('{$trunk_name}', true, {$client_id},{$rate_table},'{$enough_balance}','{$update_at}','{$update_by}') RETURNING resource_id";
        }

        $data = $this->query($sql);
        return $data[0][0]['resource_id'];
    }

    public function create_ip_port($resource_id, $ips, $ports)
    {
        if (count($ips))
        {
            $sql_arr = array();
            foreach ($ips as $key => $ip)
            {
                array_push($sql_arr, "INSERT INTO resource_ip (resource_id, port, ip,reg_type,direction) VALUES ({$resource_id}, {$ports[$key]}, '{$ip}',0,0)");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }


    public function create_route_strategy($name)
    {
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route_strategy(name, update_by) values('$name', '$update_by') returning route_strategy_id";
        $data = $this->query($sql);
        return $data[0][0]['route_strategy_id'];
    }

    public function create_dynamic($name)
    {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "INSERT INTO dynamic_route(name, routing_rule, time_profile_id, lcr_flag, update_at, update_by)
            VALUES ('$name', '4', DEFAULT, '1', '$update_at', '$update_by') returning dynamic_route_id;";
        $data = $this->query($sql);
        return $data[0][0]['dynamic_route_id'];
    }

    public function create_dynamic_item_egress($dynamic_id, $resources)
    {
        $this->query("DELETE FROM dynamic_route_items WHERE dynamic_route_id = {$dynamic_id}");
        if (count($resources))
        {
            $sql_arr = array();
            foreach ($resources as $resource)
            {
                if($dynamic_id && $resource) {
                    array_push($sql_arr, "insert into dynamic_route_items (dynamic_route_id, resource_id)
                            values ($dynamic_id,$resource)");
                }

            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }

    public function create_ratetable($name, $code_deck_id, $currency_id, $rate_type, $isus, $rate_type1,$is_origination = "false")
    {
        if($is_origination !== "false")
        {
            $is_origination = "true";
        }

        if ($isus)
        {
            $sql = "SELECT id FROM jurisdiction_country WHERE name = 'US'";
            $data = $this->query($sql);
            if (empty($data))
            {
                $sql = "INSERT INTO jurisdiction_country(name) VALUES ('US') returning id";
                $data = $this->query($sql);
            }
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jurisdiction_prefix,
                noprefix_min_length, noprefix_max_length, prefix_min_length, prefix_max_length, jur_type,origination,is_virtual)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, {$data[0][0]['id']}, CURRENT_TIMESTAMP, '1', '10', '10', '11', '11', $rate_type1,$is_origination,true) RETURNING rate_table_id";
        }
        else
        {
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jur_type,origination,is_virtual)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, NULL, CURRENT_TIMESTAMP, $rate_type1,$is_origination,true) RETURNING rate_table_id";
        }
        $result = $this->query($sql);
        if (empty($result))
        {
            return false;
        }
        return $result[0][0]['rate_table_id'];
    }

}


?>
