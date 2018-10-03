<?php

class ProductRouteRateTable extends AppModel{

    var $name = 'ProductRouteRateTable';
    var $useTable = 'product_route_rate_table';
    var $primaryKey = 'id';

    public function judge_name_exist($name, $product_id)
    {
        return $this->find('count',array('conditions' => array(
            'product_name' => $name,
            'id != ?' => $product_id
        )));
    }

    public function judge_prefix_exist($prefix, $product_id)
    {
        return $this->find('count',array('conditions' => array(
            'product_name' => $prefix,
            'id != ?' => $product_id
        )));
    }

    public function get_not_used_by_trunk($resource_id)
    {
        $sql = "SELECT t1.id,product_name,t1.tech_prefix FROM product_route_rate_table as t1
  LEFT JOIN resource_prefix as t2 on t1.tech_prefix = t2.tech_prefix  AND t2.resource_id = $resource_id
   WHERE t2.tech_prefix is null AND t1.is_private='false'";
        return $this->query($sql);
    }

    public function insert_into_prefix($product_id,$resource_id)
    {
        $sql = "INSERT INTO resource_prefix (resource_id,tech_prefix,route_strategy_id,rate_table_id,product_id)
SELECT $resource_id,tech_prefix,route_strategy_id,rate_table_id,id FROM product_route_rate_table WHERE id = $product_id";

        return $this->query($sql);
    }

    public function get_client_list_by_product($product_id,$carrier_type = 0){
        $encodedWord = base64_encode('DID');

        switch ($carrier_type){
            case 0:
                $more_where = 'AND resource.active = true';
                break;
            case 1:
                $more_where = '';
                break;
            case 2:
                $more_where = 'AND resource.active = false';
                break;
            default:
                $more_where = 'AND resource.active = true';
        }

        $sql = <<<SQL
select client.client_id,client.name,client.rate_email,client.email,resource.resource_id,resource.alias,resource.active FROM 
(SELECT product_id, client_id FROM product_clients_ref GROUP BY product_id, client_id) as product_clients_ref 
INNER JOIN resource ON (product_clients_ref.client_id = resource.client_id AND resource.alias NOT LIKE '%_RElE_%' $more_where)
INNER JOIN client ON resource.client_id = client.client_id 
INNER JOIN resource_prefix ON resource_prefix.resource_id = resource.resource_id 
WHERE product_clients_ref.product_id = $product_id AND resource_prefix.product_id = $product_id and resource.ingress=true
SQL;
        return $this->query($sql);
    }

    public function get_client_list_by_product_public($product_id,$carrier_type = 0){
        switch ($carrier_type){
            case 0:
                $more_where = 'AND resource.active = true';
                break;
            case 1:
                $more_where = '';
                break;
            case 2:
                $more_where = 'AND resource.active = false';
                break;
            default:
                $more_where = 'AND resource.active = true';
        }

        $sql = <<<SQL
select client.client_id,client.name,client.rate_email,client.email,resource.resource_id,resource.alias,resource.active FROM 
resource_prefix
INNER JOIN resource ON (resource_prefix.resource_id = resource.resource_id AND resource.alias NOT LIKE '%_RElE_%' $more_where)
INNER JOIN client ON resource.client_id = client.client_id 
WHERE resource_prefix.product_id = $product_id and resource.ingress=true $more_where
SQL;
        return $this->query($sql);
    }

    public function get_product($private = false){

        $where = "";
        if ($private) {
            $where = " WHERE is_private = true";
        }

        $data = [];
        $sql = <<<SQL
SELECT id,product_name FROM product_route_rate_table $where
SQL;
        $res = $this->query($sql);
        foreach($res as $item){
            $data[$item[0]['id']] = $item[0]['product_name'];
        }
        return $data;
    }

}