<?php

class DidAssign extends DidAppModel
{
    var $name = 'DidAssign';
    var $useTable = 'did_assign';
    var $primaryKey = 'number';
    
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
    
    public function add_new_number($number, $product_id)
    {
        $sql = "insert into product_items (product_id, digits, strategy) values ({$product_id} , '{$number}' , 1) returning item_id";
        $result = $this->query($sql);
        return $result[0][0]['item_id'];
    }
    
    public function delete_number($number, $product_id)
    {
        $sql = "select item_id from  product_items where product_id = '{$product_id}' and digits = '{$number}' limit 1";
        $result = $this->query($sql);
        if (!empty($result))
        {
            $item_id = $result[0][0]['item_id'];
            $sql = "delete from product_items_resource where item_id = {$item_id}; delete from product_items where item_id = {$item_id}";
            return $this->query($sql);
        }else
        {
            return true;
        }
    }
    
    public function add_new_resouce($item_id, $egress_id)
    {
        $sql = "insert into product_items_resource(item_id, resource_id) values ({$item_id}, {$egress_id})";
        $this->query($sql);
    }
    
  
    
    public function add_assign($number, $egress_id)
    {
        $sql = "insert into did_assign (number, egress_id, created_time, assigned_time, status, ingress_id)
values ('{$number}', {$egress_id}, (select created_time from ingress_did_repository where number = '{$number}'), CURRENT_TIMESTAMP(0), 1, (select ingress_id from ingress_did_repository where number = '{$number}')); 
        update ingress_did_repository set egress_id = {$egress_id}, status = 2,updated_time = CURRENT_TIMESTAMP(0) where number = '{$number}';";
        $this->query($sql);
    }
    
    public function remove_assign($number)
    {
        $sql = "delete from did_assign where number = '$number'; update ingress_did_repository set status = 1 where number = '{$number}'";
        $this->query($sql);
    }
    
    public function get_egress()
    {
        $data = array();
        $sql = "select resource_id, alias from resource where egress=true and trunk_type2 = 1 order by alias asc";
        $result = $this->query($sql);
        foreach ($result as $item) 
        {
            $data[$item[0]['resource_id']] = $item[0]['alias'];
        }
        return $data;
    }
    
    public function get_number($country, $state, $city, $rate_center, $number)
    {
    	$where_arr = array();
    	if(!empty($country))
    		array_push($where_arr, "country = '{$country}'");
    	if(!empty($state))
    		array_push($where_arr, "state = '{$state}'");
    	if(!empty($city))
    		array_push($where_arr, "city = '{$city}'");
    	if(!empty($rate_center))
    		array_push($where_arr, "rate_center = '{$rate_center}'");
        if(!empty($number))
                array_push($where_arr, "number::text like '%{$number}%'");
    	array_push($where_arr, "status = 1");
    	array_push($where_arr, "egress_id is null");
    	$where = implode(" and ", $where_arr);
    	$sql = "select number from ingress_did_repository where {$where} order by number asc";
    	$result = $this->query($sql);
    	return $result;
    }
}

?>