<?php

class LrnsettingsController extends AppController
{

    var $name = 'Lrnsettings';
    var $components = array('PhpTelnet');

    function index()
    {
        $this->redirect('view');
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function view()
    {
        $this->pageTitle = "Configuration/LRN Setting";
        $this->set('post', $this->Lrnsetting->findlrn());
        Configure::load('myconf');
        $backend_ip   = Configure::read('backend.ip');
        $backend_port = Configure::read('backend.port');
        
        $content = "";
        $cmd = "get_option_status";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (@socket_connect($socket, $backend_ip, $backend_port)) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = @socket_read($socket, 2048)) {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
            socket_close($socket);
        }else{
            socket_close($socket);
        }

        $data = array();
        $splited_by_line = explode("\n", $content);
        foreach ($splited_by_line as $splited_item) {
            $splited_item = trim($splited_item);
            $splited_item = trim($splited_item, '.');
            if (empty($splited_item))
            {
                continue;
            }
            $splited_by_space = explode(', ', $splited_item);

            if (count($splited_by_space) !== 6)
            {
                continue;
            }
            $row = array();

            $server_info = explode(' ', $splited_by_space[0]);
            $status_info = explode(' ', $splited_by_space[1]);
            $response_info = explode(' ', $splited_by_space[2]);
            $max_response_info = explode(' ', $splited_by_space[3]);
            $row['server'] = $server_info[1];
            $row['status'] = $status_info[1];
            $row['response_time'] = $response_info[1];
            $row['max_response_time'] = $max_response_info[1];
            $row['dynamic_timeout'] = $splited_by_space[4];
            $row['dynamic_filter'] = $splited_by_space[5];

            array_push($data, $row);
        }

        $content = "";
        $cmd = "use_option_pdd";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (@socket_connect($socket, $backend_ip, $backend_port)) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = @socket_read($socket, 2048)) {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
        }
        socket_close($socket);
        preg_match('/current (.*)\./', $content, $matches);
        if (count($matches) == 2 && $matches[1] === 'true')
        {
            $dynamic_max_timeout = true;
        }
        else {
            $dynamic_max_timeout = false;
        }

        $content = "";
        $cmd = "check_option_pdd";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (@socket_connect($socket, $backend_ip, $backend_port)) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = @socket_read($socket, 2048)) {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
        }

        socket_close($socket);

        preg_match('/current (.*)\./', $content, $matches);
        if (count($matches) == 2 && $matches[1] === 'true')
        {
            $dynamic_filter = true;
        }
        else {
            $dynamic_filter= false;
        }
        $this->set('dynamic_max_timeout', $dynamic_max_timeout);
        $this->set('dynamic_filter', $dynamic_filter);
        $this->set("lrn_infos", $data);
    }
    
    public function doaction($action, $type)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::load('myconf');
        $backend_ip   = Configure::read('backend.ip');
        $backend_port = Configure::read('backend.port');
        
        $content = "";
        if ($action == 'dynamic_max_timeout') {
            $cmd = 'use_option_pdd';
        } else if ($action == 'dynamic_filter') {
            $cmd = 'check_option_pdd';
        }
        if ($type == 0) {
            $cmd .= ' false';
        } elseif ($type == 1) {
            $cmd .= ' true';
        }
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, $backend_ip, $backend_port)) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048)) {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
        }

        socket_close($socket);
        $this->redirect('/lrnsettings/view');
    }

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit ['executable']);
            $this->Session->write('writable', $limit ['writable']);
        }
        parent::beforeFilter();
    }

    //更新系统容量设置
    public function ajax_update()
    {
        if (!$_SESSION['role_menu']['Configuration']['lrnsettings']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);

        $ip1 = !empty($_POST['ip1']) ? $_POST['ip1'] : NULL;
        $ip2 = !empty($_POST['ip2']) ? $_POST['ip2'] : NULL;

        $port1 = !empty($_POST['port1']) ? $_POST['port1'] : NULL;
        $port2 = !empty($_POST['port2']) ? $_POST['port2'] : NULL;

        $timeout1 = !empty($_POST['timeout1']) ? $_POST['timeout1'] : NULL;
        $timeout2 = !empty($_POST['timeout2']) ? $_POST['timeout2'] : NULL;

        $this->data['Lrnsetting']['ip1'] = $ip1;
        $this->data['Lrnsetting']['ip2'] = $ip2;
        $this->data['Lrnsetting']['port1'] = $port1;
        $this->data['Lrnsetting']['port2'] = $port2;
        $this->data['Lrnsetting']['timeout1'] = $timeout1;
        $this->data['Lrnsetting']['timeout2'] = $timeout2;
        //	$sql="insert into lrn (ip1,ip2,port1,port2,timeout1,timeout2)values('$ip1'::ip4r,'$ip2'::ip4r,$port1,$port2,$timeout1,$timeout2) ";
        //echo $sql;
        $this->Lrnsetting->query("delete from lrn ");
        $this->Lrnsetting->save($this->data);

        //	$this->send(" update_lnp_conf \n"."\n");
        $this->set('extensionBeans', '1');
    }

    /*
     * 发送命令到Socket 并返回执行命令结果
     */

    private function send($cmd = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['lrnsettings']['model_x']) {
            $this->redirect_denied();
        }
        $result = $this->PhpTelnet->getResult("api " . $cmd);
        return $result;
    }

}