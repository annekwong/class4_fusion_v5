<?php

class UploadBlockShell extends Shell
{

    var $uses = array('ImportExportLog');

    function main()
    {
        $log_id = (int) $this->args[0];
        $log_sql = "SELECT file_path,ext_attributes FROM import_export_logs WHERE id = {$log_id}";
        $log_info = $this->ImportExportLog->query($log_sql, false);
        $file_path = $log_info[0][0]['file_path'];
        $ext_attributes = unserialize($log_info[0][0]['ext_attributes']);
       
        $f = fopen($file_path, "r");
        while (!feof($f))
        {
            $number = fgets($f);
            $value_str = "({$ext_attributes['ingress_id']}, {$ext_attributes['egress_id']}, {$ext_attributes['ingress_carrier_id']}, {$ext_attributes['egress_carrier_id']}, '{$ext_attributes['create_by']}', '{$number}');";
            $sql = "INSERT INTO resource_block (ingress_res_id, engress_res_id, ingress_client_id, egress_client_id, create_by,{$ext_attributes['number_filed']}) VALUES $value_str";
            $this->ImportExportLog->query($sql);
            
        }
        fclose($f);
    }

}
