<?php

class Loopdetection extends AppModel {
    
    var $name = 'Loopdetection';
    var $useTable = "client_cdr"; 
    var $primaryKey = "id";
    
    public function get_data($duration=5, $count=5) {
        $sql = "SELECT 
ingress_id, egress_id,
(SELECT alias FROM resource WHERE resource_id = client_cdr.ingress_id) AS ingress_trunk, 
(SELECT alias FROM resource WHERE resource_id = client_cdr.egress_id) AS egress_trunk, 
origination_source_number, origination_destination_number, count(*)
FROM client_cdr 
WHERE time BETWEEN CURRENT_TIMESTAMP - interval '{$duration} minutes' AND CURRENT_TIMESTAMP
AND NOT EXISTS (SELECT * FROM resource_block WHERE resource_block.ingress_res_id = client_cdr.ingress_id
AND engress_res_id = client_cdr.egress_id AND ani_prefix = client_cdr.origination_source_number::prefix_range AND
digit = client_cdr.origination_destination_number::prefix_range
)
GROUP BY ingress_id,egress_id,origination_source_number,origination_destination_number
HAVING count(*) > {$count}";
        $data = $this->query($sql);
        return $data;
    }
    
    public function put_block_list($ingress_id, $egress_id, $ani, $dnis) {
        $sql = "INSERT INTO resource_block (ingress_res_id, engress_res_id, ingress_client_id, egress_client_id, ani_prefix, digit)
                VALUES ({$ingress_id}, {$egress_id}, (SELECT client_id FROM resource WHERE resource_id = {$ingress_id}),
                (SELECT client_id FROM resource WHERE resource_id = {$egress_id}), '{$ani}', '{$dnis}')";
        $this->query($sql);
    }
    
}

?>
