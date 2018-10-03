<?php

class ConnectBackend
{
    private $connection;
    private $ip;
    private $port;
    private $error;
    private $result;

    function __construct()
    {

    }


    public function get_connect($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        if (empty($ip) || empty($port))
            return false;
        try {
            $this->connection = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        } catch (Exception $e) {
            $this->error = "socket_create() failed :reason:" . socket_strerror(socket_last_error());
            return false;
        }
        socket_set_option($this->connection, SOL_SOCKET, SO_REUSEADDR, 1);

        $rst = @socket_connect($this->connection, $this->ip, $this->port);
        if ($rst === false) {
            $this->error = "socket_connect() failed :reason:" . socket_strerror(socket_last_error());
//            $this->close_connect();
            return false;
        }


        socket_set_option($this->connection, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 5, "usec" => 0));//接受超时
        socket_set_option($this->connection, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 3, "usec" => 0));//发送超时
        $login_username = 'dnl_cli';
        $password = '20160130@7D*sd';
        $login_str = md5($login_username . ":" . $password);
        try {
            $login_cmd = "login $login_username $login_str\r\n";
            @socket_write($this->connection, $login_cmd, strlen($login_cmd));
        } catch (Exception $e) {
            $this->error = "socket_write() failed :reason:" . socket_strerror(socket_last_error());
            $this->close_connect();
            return false;
        }
        $error_flg = 0;
        $content = '';
        while ($out = @socket_read($this->connection, 2)) {
            if ($out === FALSE) {
                $error_flg = 1;
                break;
            }
            $content .= $out;
            if (strpos($content, "Welcome to DNL switch") !== FALSE) {
                break;
            }
            unset($out);
        }
        if ($error_flg) {
            $this->error = "socket_read() failed :reason:" . socket_strerror(socket_last_error());
            $this->close_connect();
            return false;
        }
        return true;
    }


    public function send_cmd($cmd, $end_str = "\r\n")
    {
        try {
            $login_cmd = $cmd . "\r\n";
            @socket_write($this->connection, $login_cmd, strlen($login_cmd));
        } catch (Exception $e) {
            $this->error = "socket_write() failed :reason:" . socket_strerror(socket_last_error());
            $this->close_connect();
            return false;
        }

        $content = '';
        $error_flg = 0;

        if (strpos($cmd, 'call_simulation') !== false)
            $end_str = "<Call Simulation Test progress>Done</Call Simulation Test progress>\r\n";

        while ($out = @socket_read($this->connection, 1024)) {
            if ($out === FALSE) {
                $error_flg = 1;
                break;
            }
            $content .= $out;

            if ($end_str && strpos($content, $end_str) !== FALSE) {
                break;
            }
            unset($out);
        }

        if ($error_flg) {
            $this->error = "socket_read() failed :reason:" . socket_strerror(socket_last_error());
            $this->close_connect();
            return false;
        }

        if ($end_str) {
            $this->result = strstr($content, $end_str, TRUE);
        } else {
            $this->result = $content;
        }

        return true;
    }

    public function get_result()
    {
        return $this->result;
    }

    public function get_error()
    {
        return $this->error;
    }

    public function close_connect()
    {
        socket_close($this->connection);
    }

    function _strip_invalid_xml_chars($in)
    {
        $out = "";
        $length = strlen($in);
        for ($i = 0; $i < $length; $i++) {
            $current = ord($in{$i});
            if (($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF))) {
                $out .= chr($current);
            }
        }
        return $out;
    }


    /*
     * 调用位置 app_controller->_get_system_limit
     * 参数：$sip_ip, $sip_port
     * 返回值： null 或 array(
                'max_channel_limit'
                'max_cps_limit'
            );
     */
    public function backend_get_system_limit($sip_ip, $sip_port)
    {
        $content = '';
        if ($this->get_connect($sip_ip, $sip_port) !== false) {
            $cmd = "get_license_limit";
            if ($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        } else
            return array();

        if ($content === '') {
            $system_limits = null;
        } else {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content));

            if (isset($data[1]) && isset($data[2])) {
                $system_limits = array(
//                'call_limit' => strip_tags($data[1]),
                    'max_channel_limit' => @strip_tags($data[1]),
//                'cps_limit' => strip_tags($data[2]),
                    'max_cps_limit' => @strip_tags($data[2]),
                    'license_date' => strip_tags($data[5]),
                );
            } else {
                $system_limits = null;
            }

        }


        $this->close_connect();

        return $system_limits;
    }

    /*
     * 调用位置 shell/dashboard->get_dashboard_current_data
     * 参数： $sip_ip, $sip_port
     * 返回值：
     *      false or array(
                'cps'，
                'call'，
                'channel'
            )
     */
    /*public function backend_get_dashboard_current_data($sip_ip, $sip_port){
        $content = '';
        if($this->get_connect($sip_ip, $sip_port) !== false)
        {
            $cmd = "get_system_call_statist";
            if($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        }

        if($content === ''){
            $system_limits = false;
        } else {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content));

            $system_limits_tem = array();
            $system_limits_tem = array(
                'cps' => strip_tags($data[0]),
                'call' => strip_tags($data[1]),
                'channel' => strip_tags($data[2])
            );
        }

        $system_limits['current_cps'] = str_replace('cps=','',$system_limits_tem['cps']) + 0;
        $system_limits['current_channel'] = str_replace('chan=','',$system_limits_tem['channel']) + 0;
        $system_limits['current_call'] = str_replace('call=','',$system_limits_tem['call']) + 0;

        $this->close_connect();

        return $system_limits;
    }*/

    /*
         * 调用位置 monitorsreports/get_sys_limit
         * 参数： $sip_ip, $sip_port
         * 返回值：
         *      array(
                    'call'，
                    'channel',
                    'o_chan',
                    't_chan',
                    'curr_cps',
                    'curr_call',
                    'curr_chan',
                    'cps_24hr',
                    'cps_7day',
                    'cps_rece',
                    'chan_24hr',
                    'chan_7day',
                    'chan_rece',
                    'call_24hr',
                    'call_7day',
                    'call_rece',
                    'max_cps',
                    'max_chan'
                )
         */
    public function backend_get_qos_monitor($sip_ip, $sip_port)
    {
        $content1 = '';
        $content2 = '';
        $content3 = '';
        if ($this->get_connect($sip_ip, $sip_port) !== false) {
            $cmd = "get_system_call_statist";
            if ($this->send_cmd($cmd) !== false)
                $content1 = $this->get_result();

            $cmd = "get_system_peak_statist";
            if ($this->send_cmd($cmd) !== false)
                $content2 = $this->get_result();
            $cmd = "get_license_limit";
            if ($this->send_cmd($cmd) !== false)
                $content3 = $this->get_result();
            $rst_arr = array('status' => 1);

        } else {
            $rst_arr = array('status' => 0, 'error' => $this->get_error());
        }

        //pr($content1,$content2);

        $content1_arr = array('call' => 0, 'chan' => 0, 'o_chan' => 0, 't_chan' => 0);
        if ($content1 !== '') {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content1));
            foreach ($data as $temp) {
                $temp_arr = explode("=", $temp);
                if (array_key_exists($temp_arr[0], $content1_arr))
                    $content1_arr[$temp_arr[0]] = $temp_arr[1];
            }
        }

        $rst_arr = array_merge($rst_arr, $content1_arr);

        $content2_arr = array('curr_cps' => 0,
            'curr_call' => 0,
            'curr_chan' => 0,
            'cps_24hr' => 0,
            'cps_7day' => 0,
            'cps_rece' => 0,
            'chan_24hr' => 0,
            'chan_7day' => 0,
            'chan_rece' => 0,
            'call_24hr' => 0,
            'call_7day' => 0,
            'call_rece' => 0,
        );
        if ($content2 !== '') {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content2));

            foreach ($data as $temp) {
                $temp_arr = explode("=", $temp);
                if (array_key_exists($temp_arr[0], $content2_arr))
                    $content2_arr[$temp_arr[0]] = $temp_arr[1];
            }
        }

        $rst_arr = array_merge($rst_arr, $content2_arr);

        $content3_arr = array('max_cps' => 0,
            'max_chan' => 0,
        );
        if ($content3 !== '') {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content3));


            $content3_arr = array(
                'max_chan' => @strip_tags($data[0]),
                'max_cps' => @strip_tags($data[1]),
            );
        }

        $rst_arr = array_merge($rst_arr, $content3_arr);


        $this->close_connect();

        return $rst_arr;
    }

    public function getSystemCallStatistics($ip, $port)
    {
        $result = false;

        if ($this->get_connect($ip, $port) !== false) {
            $cmd = "get_system_call_statistics";
            if ($this->send_cmd($cmd) !== false)
                $result = $this->get_result();
        }

        if ($result) {
            $result = explode("\n", $this->_strip_invalid_xml_chars($result));
            $resultAssoc = array();

            foreach ($result as $key => $item) {
                if ($item) {
                    $temp = explode('=', $item);
                    $resultAssoc[$temp[0]] = $temp[1];
                }
            }

            $result = $resultAssoc;
        }

        return $result;
    }


    /*
     * 调用位置 systemlimits/configuration
     * 参数：$sip_ip, $sip_port
     * 返回值： $system_limits = array(
                'sys_cap_limit'
                'sys_cps_limit'
                'lic_cap_limit'
                'lic_cps_limit'
            );
     */
    public function backend_get_switch_configuration($sip_ip, $sip_port)
    {
        $content = '';
        if ($this->get_connect($sip_ip, $sip_port) !== false) {
            $cmd = "get_license_limit";
            if ($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        }

        if ($content === '') {
            $system_limits = array(
                'sys_cap_limit' => '',
                'sys_cps_limit' => '',
                'lic_cap_limit' => '',
                'lic_cps_limit' => '',
                'expire' => '',
            );
        } else {
            $data = explode("\n", $this->_strip_invalid_xml_chars($content));

            $system_limits = array(
                'sys_cap_limit' => @strip_tags($data[1]),
                'sys_cps_limit' => @strip_tags($data[2]),
                'lic_cap_limit' => @strip_tags($data[3]),
                'lic_cps_limit' => @strip_tags($data[4]),
                'expire' => @strip_tags($data[5]),
            );
        }


        $this->close_connect();

        return $system_limits;
    }


    /*
     * 调用位置 systemlimits/ajax_update
     * 参数：$sip_ip, $sip_port,$ingress_cps_limit,$ingress_cap_limit
     * 返回值： true or false
     * 如果 false： 有错误信息error
     */
    public function backend_set_switch_configuration($sip_ip, $sip_port, $ingress_cps_limit, $ingress_cap_limit)
    {
        $content = '';
        if ($this->get_connect($sip_ip, $sip_port) !== false) {
            $cmd = "set_system_limit $ingress_cps_limit $ingress_cap_limit";
            if ($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        }

        $rst = false;
        if (strpos($content, 'OK') !== false) {
            $rst = true;
        } else {
            $this->error = trim($content);
        }


        $this->close_connect();

        return $rst;
    }

    /*
     * 调用位置 active_calls/reports
     * 参数：$switch_name, $field, $filter, $count
     * 返回值： array('total'=>,0=>array(),1=>array(),...) or false
     * 如果 false： 有错误信息error
     */
    public function backend_get_active_call($switch_name, $field, $filter, $count)
    {
        $content = '';

        $active_ip = Configure::read('active_call.active_call_server_ip');
        $active_port = Configure::read('active_call.active_call_server_port');

        if (!$active_ip | !$active_port) {
            $this->error = "The active call server is not or configuration failed!";
            return false;
        }
        if ($this->get_connect($active_ip, $active_port) !== false) {
            $cmd = "get_active_call $switch_name $field $filter $count\r\n";
            if (Configure::read('cmd.debug')) {
                file_put_contents('/tmp/cmd_debug', $cmd);
            }
            if ($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        }

        if ($content === '') {
            $this->error = "The active call server is not or configuration failed!";
            return false;
        } else {

            $rst = array();
            $data = explode("\n", $this->_strip_invalid_xml_chars($content));
            $total = array_pop($data);
            $total = explode(':', $total);
            if (isset($total[2])) {
                $total = $total[2];
            }
            $rst['total'] = $total;
            foreach ($data as $k => $v) {
                $rst[$k] = explode(";", $v);
            }
        }


        $this->close_connect();


        return $rst;
    }

    /*
     * 调用位置 active_calls/channel
     * 参数：$sip_ip, $sip_port,$uuid
     * 返回值： true or false
     * 如果 false： 有错误信息error
     */
    public function backend_kill_channel($sip_ip, $sip_port, $uuid)
    {
        $content = '';
        if ($this->get_connect($sip_ip, $sip_port) !== false) {
            $cmd = "kill_channel $uuid";
            if ($this->send_cmd($cmd) !== false)
                $content = $this->get_result();
        }

        $rst = false;
        if (strpos($content, 'OK') !== false) {
            $rst = true;
        } else {
            $this->error = trim($content);
        }


        $this->close_connect();

        return $rst;
    }

}
