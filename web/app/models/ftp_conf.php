<?php

class FtpConf extends AppModel
{

    var $name = 'FtpConf';
    var $useTable = "ftp_conf";
    var $primaryKey = "id";

    public function copy_data($id, $name)
    {
        $insert_sql = "INSERT INTO ftp_conf (server_ip,server_port,username,password,frequency,fields,headers"
                . ",contain_headers,file_type,ingress_carriers,egress_carriers,ingress_carriers_all"
                . ",egress_carriers_all,duration,ingress_release_cause,egress_release_cause"
                . ",conditions,ingresses,egresses,ingresses_all,egresses_all,time"
                . ",alias,server_dir,max_lines,active,every_hours,file_breakdown) SELECT "
                . "server_ip,server_port,username,password,frequency,fields,headers"
                . ",contain_headers,file_type,ingress_carriers,egress_carriers,ingress_carriers_all"
                . ",egress_carriers_all,duration,ingress_release_cause,egress_release_cause"
                . ",conditions,ingresses,egresses,ingresses_all,egresses_all,time"
                . ",'{$name}',server_dir,max_lines,active,every_hours,file_breakdown"
                . " FROM ftp_conf where id = {$id} RETURNING id";
        $insert_data = $this->query($insert_sql);
        if(!$insert_data)
        {
            return false;
        }
        return true;
    }

}
