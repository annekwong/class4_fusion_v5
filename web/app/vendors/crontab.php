<?php

class Crontab
{

    private $connection;
    private $path;
    private $port_path;
    private $error;
                function __construct()
    {
        $path_length = strrpos(__FILE__, "/");
        $this->path = substr(__FILE__, 0, $path_length) . '/';
        Configure::load('myconf');
        $script_path = Configure::read('script.path');
        $this->port_path = $script_path . "/tmp/crontab.port";
        $port = file_get_contents($this->port_path);
        if(!intval($port))
        {// 查看脚本是否未开启 
            $this->error = "Not found the port.";
            return false;
        }
        
        $this->connection = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        $ip = "127.0.0.1";
        $flg = @socket_connect($this->connection, $ip, $port);
        if (!$flg)
        {
//                            链接失败   report server is not open 
            $this->error = "Connect failed.";
            return false;
        }
    }

    public function append_cronjob($cronjob_time, $cronjob_cmd)
    {
        if(empty($cronjob_time) || empty($cronjob_cmd))
        {
            $this->error = "Parameters can not be empty";
            return false;
        }
        $send_arr = array(
            'action'    => 'insert',
            'time'      => $cronjob_time,
            'command'   => $cronjob_cmd,
        );
        $send = json_encode($send_arr)."\n\n";
        
        @socket_write($this->connection, $send);
        
        $return = @socket_read($this->connection, 10);
        
        socket_close($this->connection);
        
        $result_arr = json_decode($return,TRUE);
        
        if(strcmp('Done!', $result_arr))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function remove_cronjob($cronjob_time, $cronjob_cmd)
    {
        if(empty($cronjob_time) || empty($cronjob_cmd))
        {
            $this->error = "Parameters can not be empty";
            return false;
        }
        $send_arr = array(
            'action'    => 'delete',
            'time'      => $cronjob_time,
            'command'   => $cronjob_cmd,
        );
        $send = json_encode($send_arr)."\n\n";
        
        @socket_write($this->connection, $send);
        
        $return = @socket_read($this->connection, 10);
        
        socket_close($this->connection);
        
        $result_arr = json_decode($return,TRUE);
        
        if(strcmp('Done!', $result_arr))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    
    public function get_list()
    {
        
        $send_arr = array(
            'action'    => 'list',
        );
        $send = json_encode($send_arr)."\n\n";
        
        @socket_write($this->connection, $send);
        
        $return = @socket_read($this->connection, 4096);
        
        socket_close($this->connection);
        $result_arr = json_decode($return,TRUE);
        return $result_arr;
    }
    
    public function get_error()
    {
        return $this->error;
    }
    
    

}
