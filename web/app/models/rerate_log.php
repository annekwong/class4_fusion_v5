<?php
class RerateLog extends AppModel{
    var $name = 'RerateLog';
    var $useTable = 'rerate_log';
    var $primaryKey = 'rerate_log_id';

    public function get_trunk_by_log_id($log_id,$type)
    {
        $sql = "SELECT $type FROM rerate_log WHERE rerate_log_id = $log_id";
        $resource_data = $this->query($sql);
        $resource_str = $resource_data[0][0][$type];
        $resource_sql = "SELECT alias FROM resource WHERE resource_id in($resource_str)";
        $data = $this->query($resource_sql);
        return $data;
    }


}
