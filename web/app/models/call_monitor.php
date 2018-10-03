<?php 

class CallMonitor extends AppModel
{
//    var $name = 'CallMonitor';
    var $useTable = false;
//    var $primaryKey = "id";
    
    public function get_servers()
    {
        $sql = "select id, sip_ip, sip_port from switch_profile";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_server_info($server_id)
    {
        $sql = "SELECT sip_ip, sip_port, sip_capture_path FROM switch_profile WHERE id = $server_id";
        $result = $this->query($sql);
        return $result[0][0];
    }
}
