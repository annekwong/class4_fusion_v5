<?php

class SwitchProfile extends AppModel
{

    var $name = 'SwitchProfile';
    var $useTable = "switch_profile";
    var $primaryKey = "id";

    public function get_voip_server_name($server_id)
    {
        $sql = "select name from voip_gateway where id = {$server_id}";
        $result = $this->query($sql);
        return $result[0][0]['name'];
    }
    
    public function check_data($server_id, $profile_name, $ip, $port)
    {
        $sql = "select count(*) from switch_profile where voip_gateway_id = {$server_id} 
                and ( profile_name = '{$profile_name}' or (sip_ip = '{$ip}' and sip_port = {$port}))";
        $result = $this->query($sql);
        if ($result[0][0]['count'])
            return false;
        else
            return true;
    }
    
    public function get_report_server()
    {
        $sql = "SELECT distinct report_port,report_ip FROM switch_profile WHERE report_port is not null AND  report_ip is not null";
        $result = $this->query($sql);
        return $result;
    }
    
    public function getPortByReportip($report_ip)
    {
        $sql = "SELECT lan_port FROM switch_profile WHERE report_ip = '{$report_ip}'";
        $result = $this->query($sql);
        return $result[0][0]['lan_port'];
    }

}

?>
