<?php

class DidRepos extends DidAppModel
{
    var $name = 'DidRepos';
    var $useTable = 'ingress_did_repository';
    var $primaryKey = 'number';
    
    
    public function get_ingress($ingress_id = '')
    {
        $data = array();
        if (!empty($ingress_id)) {
            $other_conditions =  " and resource_id = $ingress_id";
        } else {
            $other_conditions = '';
        }
        
        //$sql = "select resource_id, alias from resource where ingress=true $other_conditions  and trunk_type2 = 1 order by alias asc";
        $sql = "select resource_id, alias from resource where ingress=true and trunk_type2=1 $other_conditions  order by alias asc";
        $result = $this->query($sql);
        foreach ($result as $item) 
        {
            $data[$item[0]['resource_id']] = $item[0]['alias'];
        }
        
        return $data;
    }
    
    public function get_egress($client_id = '')
    {
    	$data = array();
        if (!empty($client_id)) {
            $other_conditions =  " and client_id = $client_id";
        } else {
            $other_conditions = '';
        }
    	//$sql = "select resource_id, alias from resource where egress=true $other_conditions  and trunk_type2 = 1 order by alias asc";
        $sql = "select resource_id, alias from resource where egress=true and trunk_type2=1  $other_conditions order by alias asc";
    	$result = $this->query($sql);
    	foreach ($result as $item)
    	{
    		$data[$item[0]['resource_id']] = $item[0]['alias'];
    	}
    	return $data;
    }
    
    public function check_num($number)
    {
        $sql = "select count(*) from ingress_did_repository where number = '{$number}'";
        $result = $this->query($sql);
        if ((int)$result[0][0]['count'] > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function get_countries()
    {
        $sql = "select country from ingress_did_repository group by country order by 1";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_group_state()
    {
        /*
        $count_sql = "select count(*) from (select state as name, count(*) as count from ingress_did_repository where status = 1 group by state order by name) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
        
        */
        $sql = "select state as name, count(*) as count from ingress_did_repository where status = 1 and country = 'US' group by state order by name";
        $result = $this->query($sql);
        return $result;        
    }
    
    public function get_group_area_code()
    {
        /*
        $count_sql = "select count(*) from (select substring(number from 0 for 5)  as name,count(*) as count from ingress_did_repository where status = 1 group by substring(number from 0 for 5) order by name ) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
        */
        
        $sql = "select substring(number from 0 for 4)  as name,count(*) as count from ingress_did_repository where status = 1 and country = 'US' group by substring(number from 0 for 4) order by name";
        $result = $this->query($sql);
        return $result;   
    }
    
    public function get_group_lata()
    {
        /*
        $count_sql = "select count(*) from (select lata as name, count(*) as count from ingress_did_repository where status = 1 group by lata order by name) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
        */
        
        $sql = "select lata as name, count(*) as count from ingress_did_repository where status = 1 and country = 'US' group by lata order by name limit {$pageSize} offset {$offset}";
        $result = $this->query($sql);
        return $result;  
    }
    
    public function get_did_by_state($state, $page, $pageSize)
    {
        
        $count_sql = "select count(*) from (select number, state, country, city, rate_center,lata,created_time from ingress_did_repository where state = '{$state}' and country = 'US' and status = 1) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
       
        
        $sql = "select number, state, country, city, rate_center,lata,created_time from ingress_did_repository where state = '{$state}' and country = 'US' and status = 1 order by number asc limit {$pageSize} offset {$offset}";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_did_by_area_code($area_code, $page, $pageSize)
    {
        $count_sql = "select count(*) from (select number, state, country, city, rate_center,lata,created_time from ingress_did_repository 
where substring(number from 0 for 5) = '{$area_code}' and status = 1) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
        
        $sql = "select number, state, country, city, rate_center,lata,created_time from ingress_did_repository 
where substring(number from 0 for 5) = '{$area_code}' and status = 1 order by number asc limit {$pageSize} offset {$offset}";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_did_by_lata($lata, $page, $pageSize)
    {
        $count_sql = "select count(*) from (select number, state, country, city, rate_center,lata,created_time from ingress_did_repository where lata = '{$lata}' and country = 'US' and status = 1) as t";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
        
        $sql = "select number, state, country, city, rate_center,lata,created_time from ingress_did_repository where lata = '{$lata}' and country = 'US' and status = 1 order by number asc limit {$pageSize} offset {$offset}";
        $result = $this->query($sql);
        return $result;
    }
    
    public function custome_search($post, $page, $pageSize)
    {
        $conditions = array();
        
        array_push($conditions, 'status = 1');
        
        if (!empty($post['number']))
            array_push($conditions, "number = '{$post['number']}'");
            
        if (!empty($post['rate_center']))
            array_push($conditions, "rate_center = '{$post['rate_center']}'");
        /*
        if (!empty($post['locality']))
            array_push($conditions, "rate_center = '{$post['locality']}'");
         */
        if (!empty($post['state_province']))
            array_push($conditions, "state = '{$post['state_province']}'");
        if (!empty($post['area_code']))
            array_push($conditions, "substring(number from 0 for 5) = '{$post['area_code']}'");
        if (!empty($post['lata']))
            array_push($conditions, "lata = '{$post['lata']}'");
        
        if (count($conditions))
            $conditon = "where " . implode(' and ', $conditions);
        else
            $conditon = '';
        
        
        $count_sql = "select count(*) from ingress_did_repository {$conditon}";
        $count_result = $this->query($count_sql);
        
        $numrows = $count_result[0][0]['count'];
        $pages = ceil($numrows/$pageSize);
        $offset = $pageSize * ($page - 1);
       
        $sql = "select number, state, country, city, rate_center,lata, created_time from ingress_did_repository {$conditon} order by number asc limit {$pageSize} offset {$offset}";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_number_info($number)
    {
        $sql = "select number, state, country, city, rate_center,lata from ingress_did_repository where number = '{$number}' limit 1";
        $result = $this->query($sql);
        
        if (!empty($result))
            return $result[0][0];
        else
            return false;
    }
    
    public function get_users_egresses($client_id)
    {
        $sql = <<<EOT
    select resource.resource_id, resource.alias as name, resource_ip.ip, resource_ip.port, 

resource_direction.digits as prefix from resource 

left join resource_ip on resource_ip.resource_id = resource.resource_id

left join resource_direction on resource_direction.resource_id = resource.resource_id

where resource.egress = true and resource.trunk_type2 = 1 and resource.client_id = {$client_id}
EOT;
        $result = $this->query($sql);
        return $result;
    }
    
    
    public function get_resource_name($resource_id)
    {
        $sql = "select alias from resource where resource_id = $resource_id";
        $result = $this->query($sql);
        return $result[0][0]['alias'];
    }
    
    public function get_group_count($country, $rate_center, $state, $city, $lata)
    {
        $country = empty($country) ? 'is null' : " = '{$country}'";
        $rate_center = empty($rate_center) ? 'is null' : " = '{$rate_center}'";
        $state = empty($state) ? 'is null' : " = '{$state}'";
        $city = empty($city) ? 'is null' : " = '{$city}'";
        $lata = empty($lata) ? 'is null' : " = '{$lata}'";
        
        $sql = "select count(*) from ingress_did_repository where status = 1 and country {$country} and rate_center {$rate_center}
                and state {$state} and city {$city} and lata {$lata}";
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }
    
    public function get_vendor_name($ingress_id)
    {
        $sql = "select name from client where client_id = (select client_id from resource where resource_id = $ingress_id)";
        $result = $this->query($sql);
        return $result[0][0]['name'];
    }
}

?>