<?php

class ServerConfig extends AppModel
{

    var $name = 'ServerConfig';
    var $useTable = "voip_gateway";
    var $primaryKey = "id";
    
    public function exists_name($name, $id)
    {
        $sql = "select count(*) from voip_gateway where name = '{$name}'";
        
        if ($id != null)
            $sql .= " and id != {$id}";
            
            
        $result = $this->query($sql);
        
        if ($result[0][0]['count'] > 0)
            return true;
        else
            return false;
    }

}

?>
