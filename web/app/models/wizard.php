<?php

class Wizard extends AppModel
{

    var $name = 'Wizard';
    var $useTable = 'client';
    var $primaryKey = "client_id";

    public function get_clinet_name_by_id($client_id)
    {
        $sql = "select name FROM client where client_id = '{$client_id}'";
        $data = $this->query($sql);
        return $data[0][0]['name'];
    }

    public function get_clients()
    {
        $sql = "select client_id, name FROM client  WHERE status='true'ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_codecs()
    {
        $sql = "SELECT id,name FROM codecs ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_egress_trunks()
    {
        $sql = "SELECT resource_id, alias FROM resource WHERE egress=true ORDER BY alias asc";
        return $this->query($sql);
    }

    public function create_carrier($client_data)
    {
        $default_currency_info = $this->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $default_currency_id = $default_currency_info[0][0]['sys_currency'];
        $client_data['currency_id'] = $default_currency_id;
        $data = $this->save($client_data);
        $client_id = $this->getLastInsertID();
        $return['client_id'] = $client_id;
        $return['name'] = $data['Wizard']['name'];

        $this->clientBalanceOperation($return['client_id'], 0, 0);

        return $return;
    }

    public function create_trunk($trunk_name, $type, $cps_limit, $call_limit, $client_id, $rate_table = 0)
    {
        $sql = "INSERT INTO resource (alias, {$type}, cps_limit, capacity, client_id) VALUES ('{$trunk_name}', true, {$cps_limit}, 
                {$call_limit}, {$client_id}) RETURNING resource_id";
        if ($rate_table)
        {
            $sql = "INSERT INTO resource (alias, {$type}, cps_limit, capacity, client_id,rate_table_id) VALUES ('{$trunk_name}', true, {$cps_limit}, 
                {$call_limit}, {$client_id},{$rate_table}) RETURNING resource_id";
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
                array_push($sql_arr, "INSERT INTO resource_ip (resource_id, port, ip) VALUES ({$resource_id}, {$ports[$key]}, '{$ip}')");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }

    public function create_codes($resource_id, $codecs)
    {
        if (count($codecs))
        {
            $sql_arr = array();
            foreach ($codecs as $codec)
            {
                array_push($sql_arr, "INSERT INTO resource_codecs_ref (resource_id, codec_id) VALUES ({$resource_id}, {$codec})");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }

    public function create_product($name)
    {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into product (name,modify_time, update_by,code_type, route_lrn) 
            values('{$name}','{$update_at}', '{$update_by}',0,0) RETURNING product_id";
        $data = $this->query($sql);
        
        return $data[0][0]['product_id'];
    }

    public function create_product_item($product_id, $host_routing)
    {
        $sql = "insert into product_items (product_id, digits, strategy)
        values ({$product_id}, '', '{$host_routing}') RETURNING item_id;";
        $data = $this->query($sql);
        
        return $data[0][0]['item_id'];
    }

    public function create_product_item_egress($product_item_id, $resources)
    {
        if (count($resources))
        {
            $sql_arr = array();
            foreach ($resources as $resource)
            {
                array_push($sql_arr, "insert into product_items_resource (item_id, resource_id, by_percentage) values
                            ($product_item_id, $resource, NULL)");
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

    public function create_route_static($product_id, $route_strategy_id)
    {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route (digits,dynamic_route_id,static_route_id,route_type,route_strategy_id,lnp,lrn_block,
            dnis_only,intra_static_route_id,inter_static_route_id,jurisdiction_country_id,update_at,update_by) 
            values ('',NULL,$product_id,2,$route_strategy_id,false,false,false,NULL,NULL,NULL, '$update_at', '$update_by')";
        $data = $this->query($sql);
        return $data[0][0]['route_id'];
    }

    public function create_resource_prefix($resource_id,$route_strategy_id, $rate_table_id)
    {
        $sql = "insert into resource_prefix (resource_id,route_strategy_id, rate_table_id, tech_prefix) 
                VALUES ($resource_id,$route_strategy_id, $rate_table_id, '');";
        $this->query($sql);
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
        if (count($resources))
        {
            $sql_arr = array();
            foreach ($resources as $resource) {
                if($dynamic_id && $resource){
                array_push($sql_arr, "insert into dynamic_route_items (dynamic_route_id, resource_id) 
                            values ($dynamic_id,$resource)");
                }
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }

    public function create_route_dynamic($dynamic_id, $route_strategy_id)
    {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route (digits,dynamic_route_id,static_route_id,route_type,route_strategy_id,lnp,lrn_block,
            dnis_only,intra_static_route_id,inter_static_route_id,jurisdiction_country_id,update_at,update_by) 
            values ('',$dynamic_id,NULL,1,$route_strategy_id,false,false,false,NULL,NULL,NULL, '$update_at', '$update_by')";
        $this->query($sql);
    }

    public function save_carrier_by_template($save_data)
    {
        return $this->save($save_data);
    }

    public function update_resource_by_template($field_set_arr,$resource_id)
    {
        $update_by_template_sql = "SET " .implode(",",$field_set_arr);
        $sql = "UPDATE resource " . $update_by_template_sql . " WHERE resource_id = " . $resource_id;
        $flg = $this->query($sql);
        return $flg;
    }

    public function create_user($user_name, $password,$client_id)
    {
        $pwd = md5($password);
        $data = $this->query("SELECT user_id FROM users WHERE client_id = $client_id");
        if(isset($data[0][0]['user_id']))
        {
            $sql = "INSERT INTO users (name,password,user_type,client_id) VALUES ('$user_name','$pwd',3,$client_id)";
        }else{
            $sql = "UPDATE users SET name = '{$user_name}' , password = '$pwd' WHERE client_id = $client_id";
        }
        $flg = $this->query($sql);
        return $flg;
    }

}

?>
