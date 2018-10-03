<?php

class CallMonitorController extends AppController
{

    var $name = "CallMonitor";
    var $uses = array('CallMonitor');
    var $components = array('RequestHandler');

    public function beforeFilter()
    {
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
    
    public function index()
    {
        $this->pageTitle = "Tools/Call Monitor";
        
        $order_arr = array('id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
        );
        
        if ($this->RequestHandler->isPost())
        {
            $server = $_POST['server'];
            $ani = $_POST['ani'];
            $dnis = $_POST['dnis'];
            $remote_ip = $_POST['remote_ip'];
            $remote_port = $_POST['remote_port'];
            
            $server_info = $this->CallMonitor->get_server_info($server);
            
            /*
            $call_monitor_port = Configure::read('backend.port');
            $call_monitor_path = Configure::read('call_monitor.server_path');
            $call_monitor_ip   = Configure::read('call_monitor.wan_ip');
            */
            $call_monitor_ip   = $server_info['sip_ip'];
            $call_monitor_port = $server_info['sip_port'];
            $call_monitor_path = $server_info['sip_capture_path'];
            
            $cmd = "-h {$call_monitor_ip} -p {$call_monitor_port} -F '{$call_monitor_path}' -d any ";
            $cmds = array();
            if ($ani)
                array_push($cmds, "-o {$ani}");
            if ($dnis)
                array_push($cmds, "-t {$dnis}");
            if ($remote_ip)
                array_push($cmds, "-H {$remote_ip}");
            if ($remote_port)
                array_push($cmds, "-P {$remote_port}");
            $cmd_str = implode(' ', $cmds);            
            $cmd = $cmd . $cmd_str;
            
            $data['CallMonitor'] = array(
                'ani' => $ani,
                'dnis' => $dnis,
                'remote_ip' => $remote_ip,
                'remote_port' => $remote_port,
            );
            $this->CallMonitor->save($data);
            $this->send_cmd($cmd, 'start');
            $this->Session->write('m', $this->CallMonitor->create_json(201, __('The Call Monitor is started successfully!', true)));
            $this->redirect('/call_monitor');
        }
        /*
        $count = $this->CallMonitor->find('count', array(
            'conditions' => array('CallMonitor.status' => 0),
        ));
         * 
         */
        $servers = $this->CallMonitor->get_servers();
        $this->set('servers', $servers);
        //$this->set("count", $count);        
        $this->data = $this->paginate('CallMonitor');
    }
    
    public function stop($id)
    {
        $data = $this->CallMonitor->findById($id);
        $data['CallMonitor']['status'] = 1;
        $data['CallMonitor']['end_time'] = date("Y-m-d H:i:s");
        $this->CallMonitor->save($data);
        $this->send_cmd('', 'stop');
        $this->redirect('/call_monitor');
    }
    
    public function send_cmd($cmd, $action)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $ip = Configure::read('backend.ip');
        $port = Configure::read('call_monitor.port');
        
        $json = array('id' => 1, 'cmd' => $cmd, 'action' => $action);
        $json = json_encode($json);
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, $ip, $port)) {
            socket_write($socket, $json, strlen($json));
        }else{
            socket_close($socket);
        }
        while ($out = socket_read($socket, 2048)) {
            print($out);
        }
        
        socket_close($socket);
        
    }

}