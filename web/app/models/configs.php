<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clients
 *
 * @author hewenxiang
 */
class configs extends AppModel {
    var $name = 'Configs';
    var $useTable = "client"; 
    var $primaryKey = "client_id";
    
    function insertResource($ingress, $egress, $alias, $carrier,$rate_table_id) {
        $sql = "insert into resource (ingress, egress, alias, client_id,rate_table_id, media_type) 
               values({$ingress}, {$egress}, '{$alias}', (select client_id from client where name = '{$carrier}'), {$rate_table_id}, 2)  RETURNING resource_id";
        $res_id = $this->query($sql);
        return $res_id;
    }
    
    
    function insertResource1($ingress, $egress, $alias, $client_id,$rate_table_id,$enough_balance) {
        $sql = "insert into resource (ingress, egress, alias, client_id,rate_table_id,enough_balance, media_type) 
               values({$ingress}, {$egress}, '{$alias}', {$client_id}, {$rate_table_id},'{$enough_balance}', 2)  RETURNING resource_id";
        $res_id = $this->query($sql);
        return $res_id;
    }
    
    
    function insertResource2($ingress, $egress, $alias, $carrier) {
        $res_id = $this->query("insert into resource (ingress, egress, alias, client_id, media_type) 
               values({$ingress}, {$egress}, '{$alias}', (select client_id from client where name = '{$carrier}'), 2)  RETURNING resource_id");
        return $res_id;
    }
    
    function insertResource3($ingress, $egress, $alias, $client_id,$enough_balance) {
        $res_id = $this->query("insert into resource (ingress, egress, alias, client_id,enough_balance, media_type) 
               values({$ingress}, {$egress}, '{$alias}', {$client_id},'{$enough_balance}', 2)  RETURNING resource_id");
        return $res_id;
    }
    
    function insertHosts($ip, $port, $id) {
        if(empty($port))
        {
            $port = 5060;
        }
        $this->query("insert into resource_ip (resource_id, ip, port) values ({$id}, '{$ip}', {$port})");
    }
    
    function insertHosts2($ip, $need_register, $port, $id) {
        if (empty($port)) 
            $port = '5060';
        $ip .= '/'.$need_register;
        $this->query("insert into resource_ip (resource_id, ip, port) values ({$id}, '{$ip}', {$port})");
    }
    
    function insertResourcePrefix($id, $rate_table_id, $route_strategy_id, $tech_prefix) {
        $this->query("insert into resource_prefix (resource_id, rate_table_id, route_strategy_id, tech_prefix)
            values({$id}, {$rate_table_id}, {$route_strategy_id}, '{$tech_prefix}')");
    }
    
    function get_egress_trunk() {
        $result = $this->query("select resource_id,alias from resource where egress = true order by alias");
        return $result;
    }
    
    function getAllowedCredit($client_id){
        $result = $this->query("select allowed_credit from client where client_id = ".$client_id);
        if(!empty($result)){
            return $result[0][0]['allowed_credit'];
        }
    }
    
    
    function checkIsHaveByName($name){
        $result = $this->query("select * from resource where alias = '".$name."'");
        return count($result);
    }
    
    function insert_client() {
        $data = array(
            'Clients' => array(
                    "name" => $_POST['username'],
                    "company" => $_POST['company_name'],
                    'corporate_contact_email' => $_POST['corporatecontactemail'],
                    'paypal' => $_POST['paypal'],
                    'noc_email' => $_POST['technical_email'],
                    'billing_email' => $_POST['billing_email'],
                    "currency_id" => 1,
                    'transaction_fee_id' => $_POST['transaction_fee_id']
                )
        );
        
        //var_dump($data);
        $this->save($data);
        return $this->id;
    }
    
    function edit_client() {
        $data = array(
            'Clients' => array(
                    'client_id' => $_POST['client_id'],
                    "name" => $_POST['username'],
                    "company" => $_POST['company_name'],
                    'corporate_contact_email' => $_POST['corporatecontactemail'],
                    'paypal' => $_POST['paypal'],
                    'noc_email' => $_POST['technical_email'],
                    'billing_email' => $_POST['billing_email'],
                    "currency_id" => 1,
                    'transaction_fee_id' => $_POST['transaction_fee_id']
                )
        );
        
        //var_dump($data);
        return $this->save($data);
    }
    
    function insert_codedeck($id) {
        return $this->query("INSERT INTO code_deck(name, client_id) VALUES ({$id}, {$id})");
    }
    
    public function create_balance($client_id) {
        return $this->clientBalanceOperation($client_id, 1, 0);
    }
    
    public function create_credit($client_id){
//        return $this->query("INSERT INTO credit_application(client_id) VALUES ({$client_id})");
    }
    
    

    
    
}

?>