<?php

class Orig extends AppModel
{
    var $name = 'Orig';
    var $useTable = 'ingress_did_repository';
    var $primaryKey = 'number';
    
    public function get_ingress_id($account_id)
    {
    	$sql = "select resource_id from resource where ingress = true and account_id = '{$account_id}'";
    	$result = $this->query($sql);
    	if(empty($result))
    	{
    		return null;
    	}
    	else
    	{
    		return $result[0][0]['resource_id'];
    	}
    	
    }
    
    
    public function get_egress_id($account_id)
    {
        $sql = "select resource_id from resource where egress = true and account_id = '{$account_id}'";
    	$result = $this->query($sql);
    	if(empty($result))
    	{
    		return null;
    	}
    	else
    	{
    		return $result[0][0]['resource_id'];
    	}
    }
    
    public function is_already_buy_me($number, $egress_id)
    {
    	$sql = "select count(*) from ingress_did_repository where number = '{$number}' and status = 2 and egress_id = {$egress_id}";
    	$result = $this->query($sql);
    	$count = $result[0][0]['count'];
    	if($count > 0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    public function get_billing_rule($ingress_id)
    {
        $sql = "select billing_rule from resource where resource_id = $ingress_id";
        $result = $this->query($sql);
        return $result[0][0]['billing_rule'];
    }
    
    public function is_exists_number($number)
    {
        $sql = "select count(*) from ingress_did_repository where number = '{$number}' and status = 1";
        $result = $this->query($sql);
        $count = $result[0][0]['count'];
        if($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
            
    
}