<?php

class RoutingAnalysis extends AppModel
{
    var $name = 'RoutingAnalysis';
    var $useTable = 'client';
    var $primaryKey = 'client_id';
    
    public function get_carriers()
    {
        $sql = "select client_id, name from client order by name asc";
        return $this->query($sql);
    }
    
    
    public function get_ingress_trunks($client_id)
    {
        $sql = "select resource_id, alias from resource where ingress = true and client_id = $client_id order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_ingress_prefixes($ingress_id)
    {
        $sql = "select route_strategy_id, tech_prefix from resource_prefix where resource_id = $ingress_id order by tech_prefix asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_route_egress($route_strategy_id, $code)
    {
        $sql = "select egress_id from route_egress($route_strategy_id, '$code') as (egress_id integer)";
        return $this->query($sql);
    }
    
    public function get_egress_info($egress_id)
    {
        $sql = "select (select name from client where client_id = resource.client_id) as client_name, alias as trunk_name,rate_table_id from resource where resource_id = {$egress_id} and rate_table_id is not null limit 1";
        $result = $this->query($sql);
        if (!empty($result))
            return $result[0][0];
        else {
            return null;
        }        
    }
    
    public function get_rates_count($rate_table_ids, $code)
    {
        if (empty($rate_table_ids))
        {
            return 0;
        }
        else 
        {
            $rate_table_id_str = implode(',', $rate_table_ids);
            $sql = "select count(*) from rate where rate_table_id in ({$rate_table_id_str}) and code <@ '$code'";
            $result = $this->query($sql);
            return $result[0][0]['count'];
        }
    }
    
    public function get_rates($rate_table_ids, $code, $pageSize, $offset)
    {
        if (empty($rate_table_ids))
        {
            return array();
        }
        else
        {
            $rate_table_id_str = implode(',', $rate_table_ids);
            
            $sql = "select code, code_name, rate, rate_table_id from rate where rate_table_id in ({$rate_table_id_str}) and code <@ '$code' order by code asc limit '$pageSize' offset '$offset'";
            $result  = $this->query($sql);
            return $result;
        }
    }
}

?>
