<?php

class ResellerClient extends AppModel
{

    var $name = 'ResellerClient';
    var $useTable = 'reseller_client';
    var $primaryKey = 'id';
    
    public function check_default_static()
    {
        $sql = "select product_id from product where name = 'ORIGINATION_STATIC_ROUTE'";
        $result = $this->query($sql);
        if(!empty($result))
        {
            return $result[0][0]['product_id'];
        }
        else
        {
            $sql = "insert into product(name) values ('ORIGINATION_STATIC_ROUTE') returning product_id";
            $result = $this->query($sql);
            return $result[0][0]['product_id'];
        }
    }
    
    public function check_default_route()
    {
        $sql = "select route_strategy_id from route_strategy where name = 'ORIGINATION_ROUTING_PLAN'";
        $result = $this->query($sql);
        if(!empty($result))
        {
            return $result[0][0]['route_strategy_id'];
        }
        else
        {
            $sql = "insert into route_strategy(name) values ('ORIGINATION_ROUTING_PLAN') returning route_strategy_id";
            $result = $this->query($sql);
            return $result[0][0]['route_strategy_id'];
        }
    }
    
    public function generate_prefix()
    {
        while (1) {
            $prefix = substr(number_format(time() * rand(),0,'',''),0,6);
            $sql = "select count(*) from reseller_client where prefix = '{$prefix}'";
            $result = $this->query($sql);
            if ($result[0][0]['count'] == 0)
                return $prefix;
        }
    }

}