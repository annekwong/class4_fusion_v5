<?php

class BalanceLogs extends AppModel {

    var $name = 'BalanceLogs';
    var $useTable = 'balance_log';
    var $primaryKey = 'id';
    
    public function clients()
    {
        $data = array();
        $results = $this->query("SELECT client_id, name FROM client ORDER BY name asc");
        foreach ($results as $item) {
            $data[$item[0]['client_id']] = $item[0]['name'];
        }
        return $data;
    }

}