<?php 

class Quickcdr extends AppModel
{
    var $name = 'Quickcdr';
    var $useTable = "quick_cdr"; 
    var $primaryKey = "id";
    
    public function get_clients()
    {
        $clients = array();
        $sql = "select client_id, name from client order by name";
        $result = $this->query($sql);
        foreach ($result as $item)
        {
            $clients[$item[0]['client_id']] = $item[0]['name'];
        }
        
        return $clients;
    }
}