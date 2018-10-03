<?php

class DidRequest extends DidAppModel
{
    var $name = 'DidRequest';
    var $useTable = 'did_request';
    var $primaryKey = 'id';
    
    function create_request($user_id)
    {
        $sql = "insert into did_request (user_id) values ($user_id) returning id";
        $result = $this->query($sql);
        return $result[0][0]['id'];
    }
    
    function create_request_detail($request_id, $number, $egress_id)
    {
        $sql = "insert into did_request_detail(did_request_id, number, egress_id)
                values($request_id, '$number', '$egress_id')";
        $this->query($sql);
    }
    
    function create_new_mutiples($request_id, $country, $rate_center, $state, $city, $lata, $amount, $egress_id)
    {
        $country = empty($country) ? " is null" : " = '{$country}'";
        $rate_center = empty($rate_center) ? " is null" : " = '{$rate_center}'";
        $state = empty($state) ? " is null" : " = '{$state}'";
        $city = empty($city) ? " is null" : " = '{$city}'";
        $lata = empty($lata) ? " is null" : " = '{$lata}'";
        
        $sql = "insert into did_request_detail(did_request_id, number, egress_id)
select $request_id, number, $egress_id from ingress_did_repository where country {$country} and rate_center {$rate_center}
        state {$state} and city {$city} and lata {$lata} and status = 1 limit $amount";
        $this->query($sql);
    }
    
}