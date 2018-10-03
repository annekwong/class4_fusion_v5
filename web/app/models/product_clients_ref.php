<?php

class ProductClientsRef extends AppModel{

    var $name = 'ProductClientsRef';
    var $useTable = 'product_clients_ref';
    var $primaryKey = 'id';

    public function getClientCounts($product_id, $agent_id){

        $sql = <<<SQL
select  count(distinct agent_clients.client_id ) cnt from agent_clients
inner join product_clients_ref on agent_clients.client_id=product_clients_ref.client_id
where product_clients_ref.product_id=$product_id
and agent_clients.agent_id=$agent_id
SQL;
        $res = $this->query($sql);
        return $res[0][0]['cnt'];

    }

}