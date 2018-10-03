<?php

class BlockAni extends AppModel
{

    var $name = 'BlockAni';
    var $useTable = 'block_ani';
    var $primaryKey = 'id';
    
    public function get_resources()
    {
        $sql = "select resource_id, alias from resource order by alias asc";
        $result = $this->query($sql);
        $data = array();
        foreach($result as $item)
        {
            $data[$item[0]['resource_id']] = $item[0]['alias'];
        }
        return $data;
        
    }
    
    public function get_resource_by_type($type)
    {
        $sql = "select resource_id, alias from resource where $type = true order by alias asc";
        $result = $this->query($sql);
        $data = array();
        foreach($result as $item)
        {
            $data[$item[0]['resource_id']] = $item[0]['alias'];
        }
        return $data;
    }
    
    public function get_client_id($id)
    {
        $sql = "select client_id from resource where resource_id = {$id}";
        $result = $this->query($sql);
        return $result[0][0]['client_id'];
    }

}