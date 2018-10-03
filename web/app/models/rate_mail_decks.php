<?php

class RateMailDecks extends AppModel
{

    var $name = "RateMailDecks";
    var $useTable = 'rate_mail_decks';
    var $primaryKey = 'id';
    
    public function get_egresses($client_id)
    {
        $sql = "select resource_id, alias from resource where client_id = {$client_id} and egress = true order by alias";
        $result = $this->query($sql);
        return ($result);
    }
    
    public function get_ratetable_by_egress_id($egress_id)
    {
        $sql = "select rate_table_id from resource where resource_id = $egress_id and egress = true";
        $result = $this->query($sql);
        return $result[0][0]['rate_table_id'];
    }
    
    public function get_status($rate_upload_id)
    {
        if ($rate_upload_id == NULL)
            return 'Unprocessed';
        else {
            $sql = "select status from import_rate_status where id = {$rate_upload_id} limit 1";
            $result = $this->query($sql);
            if ($result[0][0]['status'] < 5)
            {
                return 'In Progress';
            }
            else 
            {
                return 'Completed';
            }
        }
    }
    
}