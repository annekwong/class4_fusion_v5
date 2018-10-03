<?php

class CallmonitorController extends AppController {
    
    var $name = "Callmonitor";
    var $uses = array();
    var $components = array('RequestHandler');
    
    public function beforeFilter() {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    public function index() {
        $this->pageTitle = "Tools/Call Monitor";
        $status = $this->_get_status();
        $this->set('status', $status);
    }
    
    private function _get_status() {
        $status = trim($this->send_to_server("status_sip_capture\r\n"));
        if (strpos($status, 'false') !== false)
            return false;
        else
            return true;
    }
    
    private function send_to_server($data)
    {
        $ip = Configure::read('backend.ip');
        $port = Configure::read('backend.port');
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            socket_close($socket);
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        }
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            socket_close($socket);
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        socket_write($socket, $data, strlen($data));
        $reply = "";
        do {
            $recv = "";
            $recv = socket_read($socket, 2048);
            if($recv != "")
                $reply .= $recv;
        } while (strpos($reply, "~!@#$%^&*()") === FALSE);
        socket_close($socket);
        
        $return = strstr($reply, "~!@#$%^&*()", TRUE);
        return $return;
    }
    
    public function start() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        
        echo $this->send_to_server("start_sip_capture\r\n");
        echo $this->send_to_server("start_rtpdump\r\n");
    }
    
    public function stop() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        echo $this->send_to_server("stop_sip_capture\r\n");
        echo $this->send_to_server("stop_rtpdump\r\n");
    }
    
    public function poll()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 2008 01:00:00 GMT");
        
        define('RECORD_LINE', 18); // 多少行为一个记录集
        
        $sip_file = Configure::read('call_monitor.sip_path');
        
        $data = array(
            'records' => array(),
            'tell' => 0,
        );
        $tell = isset($_POST['tell']) ? (int)$_POST['tell'] : 0;
        $num = 1;
        
        parse_str($_POST['query'], $query);
        
        if (file_exists($sip_file))
        {
            do
            {
                $handle = @fopen($sip_file, 'r');
                flock($handle,  LOCK_SH);
                if ($handle)
                {
                    $row = array();
                    /* 文件指针跳转 */
                    fseek($handle, $tell); 
                    while (!feof($handle))
                    {
                        $buffer = fgets($handle, 4096);
                        if (trim($buffer, " \n") != "")
                        {
                            list($key, $value) = sscanf($buffer, "%s = %s\n");
                            if ($key == 'orig_time' || $key == 'term_time')
                                $value = @(date('Y-m-d H:i:s',intval(substr($value, 0, 10)))) or 0;
                            $row[$key] = $value;
                            $num++;
                            if ($num % RECORD_LINE == 0)
                            {
                                /* begin filter */
                                if ($query['orig_from_ip'] && strpos($row['orig_src_ip'], $query['orig_from_ip']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['orig_to_ip'] && strpos($row['orig_dst_ip'], $query['orig_to_ip']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['orig_ani'] && strpos($row['orig_ani'], $query['orig_ani']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['orig_dnis'] && strpos($row['orig_dnis'], $query['orig_dnis']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['term_from_ip'] && strpos($row['term_src_ip'], $query['term_from_ip']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['term_to_ip'] && strpos($row['term_dst_ip'], $query['term_to_ip']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['term_ani'] && strpos($row['term_ani'], $query['term_ani']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                if ($query['term_dnis'] && strpos($row['term_dnis'], $query['term_dnis']) === FALSE)
                                {
                                    $row = array();
                                    $num = 1;
                                    continue;
                                }
                                
                                array_push($data['records'], $row);
                                $row = array();
                                $num = 1;
                            }
                        }
                    }
                    $tell = ftell($handle);
                    fclose($handle);
                }
            } while ($num != 1);

            $data['tell'] = $tell;
        }
        
        echo json_encode($data);
    }
    
    
    public function clear()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->send_to_server("clear_sip_capture\r\n");
        $this->send_to_server("clear_rtpdump\r\n");
    }
    
    public function download_rtp($type, $orig_callid) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        define('RECORD_LINE', 9); // 多少行为一个记录集
        $num = 1;
        $filename = '';
        
        $sip_file = Configure::read('call_monitor.rtp_path');
        
        $handle = @fopen($sip_file, 'r');
        flock($handle,  LOCK_SH);
        if ($handle)
        {
            $row = array();
            while (!feof($handle))
            {
                $buffer = fgets($handle, 4096);
                if (trim($buffer, " \n") != "")
                {
                    list($key, $value) = sscanf($buffer, "%s = %s\n");
                    $row[$key] = $value;
                    $num++;
                    if ($num % RECORD_LINE == 0)
                    {
                        if ($row['callid'] == $orig_callid)
                        {
                            $filename = $row['filename'];
                            if ($type == 'audio')
                            {
                                $filename = str_replace('ring', 'active', $filename);
                            }
                            break;
                        }
                        $row = array();
                        $num = 1;
                    }
                }
            }
            fclose($handle);
        }
        
        if (empty($filename) || !file_exists($filename))
        {
            echo "File not exist!";
        }
        else
        {
            ob_clean(); 
            $basename = basename($filename);
            header("Content-Type: application/octet-stream");   
            header("Content-Disposition: attachment; filename={$basename}");   
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
            header('Expires:0');   
            header('Pragma:public');
            readfile($filename);
            return;
        }
    }
    
    public function download_aud() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        ob_clean(); 
        $file = base64_decode($_GET['file']);
        if(file_exists($file)) {
            $filename = basename($file);
            header("Content-Type: application/octet-stream");   
            header("Content-Disposition: attachment; filename={$filename}");   
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
            header('Expires:0');   
            header('Pragma:public');
            readfile($file);
            return;
        }
        echo 'The file doesn\'t exist!';
    }
    
    public function view_sip() {
        $this->pageTitle = "Tools/Call Monitor";
        $file = base64_decode($_GET['file']);
        if(file_exists($file)) {
            $cmd = "tcpdump -v -r {$file}";
            $result = shell_exec($cmd);
        } else {
            $result = "";
        }
        $this->set("result", $result);
    }
    
}

?>
