<?php

class DashboardShell extends Shell
{
    var $uses = array('ServerConfig');

    function main()
    {
        $data_type = isset($this->args[0]) ? (int)$this->args[0] : "";
        $voip_gateway_id = isset($this->args[1]) ? (int)$this->args[1] : "";
        if (!$data_type || !$voip_gateway_id)
        {
            echo "Not enough arguments\n";
            return false;
        }
        switch ($data_type)
        {
            case 1 :
                $this->get_dashboard_current_data($voip_gateway_id);
                break;
            default:
                echo "arguments 1:[type] error\n";
                return false;
        }
    }

    /**
     * 得到当前系统的
     * ingress channel
     * CPS
     * Media Call Count
     */
    function get_dashboard_current_data($voip_gateway_id)
    {
        echo "START voip gateway id is {$voip_gateway_id}\n";


        $content = "";

        $voip_data = $this->ServerConfig->findById($voip_gateway_id);
        $sip_ip = $voip_data['ServerConfig']['lan_ip'];
        $sip_port = $voip_data['ServerConfig']['lan_port'];

        App::import("Vendor", "connect_backend", array('file' => "connect_backend.php"));
        $backend_connect = new ConnectBackend();



        $server = $sip_ip .":".$sip_port;
//        进入时的删除
        $delete_sql = "DELETE FROM current_dashboard_data WHERE server = '{$server}'";
        $this->ServerConfig->query($delete_sql);


        $select_time_sql = "SELECT select_time FROM current_dashboard_data WHERE select_time IS NOT NULL
 AND server = '{$server}' ORDER BY id DESC LIMIT 1";
        while(true)
        {
            $max_select_time_result = $this->ServerConfig->query($select_time_sql,false);
            var_dump($max_select_time_result);
            if (!$max_select_time_result)
            {
                $exist_sql = "SELECT COUNT(*) AS sum FROM current_dashboard_data WHERE server = '{$server}'";
                $exist_result = $this->ServerConfig->query($exist_sql,false);
                var_dump($exist_result[0][0]['sum']);
                if($exist_result[0][0]['sum'] > 10)
                {
                    echo "is not select and data more than 10 \n";
                    break;
                }
            }
            else
            {
                $max_select_time = $max_select_time_result[0][0]['select_time'];
                $time_sql = "SELECT (current_timestamp(0)- '{$max_select_time}' > '00:01:00') as flg";
                $time_result = $this->ServerConfig->query($time_sql,false);
                if($time_result[0][0]['flg'])
                {
                    echo "NO select than 1 minutes \n";
                    break;
                }
            }

            //return false or array()
            $rst = $backend_connect->backend_get_dashboard_current_data($sip_ip, $sip_port);
            if($rst){
                $current_cps = $rst['current_cps'];
                $current_channel = $rst['current_channel'];
                $current_call = $rst['current_call'];
            } else {
                $current_cps = 0;
                $current_channel = 0;
                $current_call = 0;
            }


            $insert_sql = "INSERT INTO current_dashboard_data (cps,channel,call,create_time,server) VALUES
({$current_cps},{$current_channel},{$current_call},CURRENT_TIMESTAMP(0),'{$server}' ) returning id";
            $insert_flg = $this->ServerConfig->query($insert_sql);
            if ($insert_flg)
                echo "insert data succeed\n";
            else
            {
                echo "insert data error\n";
                break;
            }
            sleep(3);
        }
//        退出时删除
        $this->ServerConfig->query($delete_sql);
        return true;
    }


}
