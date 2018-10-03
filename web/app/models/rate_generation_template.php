<?php

class RateGenerationTemplate extends AppModel
{

    var $name = 'RateGenerationTemplate';
    var $useTable = 'rate_generation_template';
    var $primaryKey = 'id';


    public function get_with_rate_egress()
    {
        $sql = "SELECT resource_id,alias,rate_table_id,client.name FROM resource INNER JOIN client ON resource.client_id = client.client_id
 WHERE egress = true and resource.is_virtual is not true AND rate_table_id is not NULL ORDER BY alias ASC ";
        $data = $this->query($sql);
        $return_arr = array();
        foreach ($data as $item)
        {
            $key = $item[0]['resource_id'] . ',' .$item[0]['rate_table_id'];
            $return_arr[$item[0]['name']][$key] = $item[0]['alias'];
        }
        return $return_arr;
    }
}
