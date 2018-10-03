<?php 

class FtpServerLog extends AppModel
{
    var $name = 'FtpServerLog';
    var $useTable = "ftp_server_log"; 
    var $primaryKey = "id";
    
    public function insert_log($cmd, $response)
    {
        $sql = "INSERT INTO ftp_server_log(cmd, response) values ('$cmd', '$response')";
        $this->query($sql);
    }
}
