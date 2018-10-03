<?php

class Ratemail extends AppModel {
    
    var $name = "Ratemail";
    
    var $useTable = 'rate_mail';
    
    var $primaryKey = 'id';
    
    public function get_ingress() {
        $results = $this->query("select resource_id,alias from resource where ingress = true order by alias asc");
        return $results;
    }
    
    public function get_tech($id) {
        $sql = "SELECT id,tech_prefix FROM resource_prefix WHERE resource_id = {$id}";
        $results = $this->query($sql);
        return $results;
    }
    
    public function get_table($id) {
        $sql = "
select resource_prefix.rate_table_id,rate_table.name from resource_prefix 

left join rate_table on resource_prefix.rate_table_id = rate_table.rate_table_id

where id = {$id}";
        $results = $this->query($sql);
        return $results;
    }
    
    public function get_carrier_mail($client_id) {
        $sql = "SELECT rate_email FROM client where client_id = {$client_id}";
        $result = $this->query($sql); 
        return $result[0][0]['rate_email'];
    }
    
    public function getcontent($id) {
        $sql = "SELECT content FROM rate_mail WHERE id = {$id}";
        $result = $this->query($sql); 
        return $result[0][0]['content'];
    }
    
}

?>
