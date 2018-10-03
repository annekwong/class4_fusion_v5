<?php

class SwitchProfilerController extends AppController
{

    var $name = "SwitchProfiler";
    var $uses = array('SwitchProfile');
    var $helpers = array('Javascript', 'Html');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
    }

    public function index($server_id, $is_config = '')
    {
        $this->pageTitle = "Configuration/SIP Profile";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'voip_gateway_id' => $server_id
            ),
        );
        $voip_gateway_name = $this->SwitchProfile->get_voip_server_name($server_id);
        $this->set('server_id', $server_id);
        $this->set('status', array('INIT', 'ACTIVE', 'DEACTIVE', 'SHUTDOWN', 'DESDROY'));
        $this->set('gateway_name', $voip_gateway_name);
        $this->data = $this->paginate('SwitchProfile');

        $backendObject = $this->get_connect_obj();

        $sql = "select lan_ip, lan_port from voip_gateway where id = $server_id limit 1";
        $result = $this->SwitchProfile->query($sql);

        $lanIp = $result[0][0]['lan_ip'];
        $lanPort = $result[0][0]['lan_port'];
        $connectResult = $backendObject->get_connect($lanIp, $lanPort);

        if ($connectResult) {
            $result = $backendObject->send_cmd("sip_profile_show", null);

            if ($result !== false) {
                $result = $backendObject->get_result();
                $rows = explode("\r\n", $result);
                $arrayResult = [];

                for ($i = 2; $i < count($rows) - 2; $i++) {
                    array_push($arrayResult, $rows[$i]);
                }

                foreach ($arrayResult as $key => $item) {
                    $arrayResult[$key] = explode('|', trim($item));
                    foreach ($arrayResult[$key] as $subKey => $subItem) {
                        $arrayResult[$key][$subKey] = trim($subItem);
                    }
                }
            }
        }

        foreach ($this->data as $key => $item) {
            // Set status
            $filteredRows = array_filter($arrayResult, function ($searchRow) use ($item) {
                return (
                    $searchRow[0] == $item['SwitchProfile']['profile_name'] &&
                    $searchRow[2] == $item['SwitchProfile']['sip_ip'] &&
                    $searchRow[3] == $item['SwitchProfile']['sip_port']
                );
            });
            $filteredRows = array_values($filteredRows);
            $this->data[$key]['SwitchProfile']['status'] = !empty($filteredRows) ? $filteredRows[0][1] : '-';
            //===============

            $connectResult = $backendObject->get_connect($item['SwitchProfile']['lan_ip'], $item['SwitchProfile']['lan_port']);
            if ($connectResult) {
                $result = $backendObject->send_cmd("get_profile_ip_stat_info {$item['SwitchProfile']['sip_ip']}:{$item['SwitchProfile']['sip_port']}");

                if ($result !== false) {
                    $result = $backendObject->get_result();

                    if ($result) {
                        $explodedRes = explode('Incoming statistics: ', $result);
                        $explodedRes = explode(', ', $explodedRes[1]);
                        $cps = explode(': ', $explodedRes[0])[1];
                        $channel = explode(': ', $explodedRes[1])[1];

                        $this->data[$key]['SwitchProfile']['cps'] = $cps;
                        $this->data[$key]['SwitchProfile']['cap'] = $channel;
                    }
                }
            }
        }

        $this->set('is_config',$is_config);

        $this->set('user_id', $_SESSION['sst_user_id']);
        //        code deck
        $code_deck_exist = $this->SwitchProfile->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->SwitchProfile->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);

        if($this->RequestHandler->isPost() && isset($_POST['ajax'])) {
            Configure::write('debug', 0);
            $this->render('index_data');
        }
    }

    public function action_edit_panel($server_id, $id = 0,$is_config = '')
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            $voip_gateway_name = $this->SwitchProfile->get_voip_server_name($server_id);

            $this->data['SwitchProfile']['voip_gateway_id'] = $server_id;
            $this->data['SwitchProfile']['switch_name'] = $voip_gateway_name;

            $server = $this->SwitchProfile->query(" select * from voip_gateway where id = {$server_id} ");

            $this->data['SwitchProfile']['lan_ip'] = $server[0][0]['lan_ip'];
            $this->data['SwitchProfile']['lan_port'] = $server[0][0]['lan_port'];
            $this->data['SwitchProfile']['active_call_ip'] = $server[0][0]['active_call_ip'];
            $this->data['SwitchProfile']['active_call_port'] = $server[0][0]['active_call_port'];
            $this->data['SwitchProfile']['paid_replace_ip'] = $server[0][0]['paid_replace_ip'];
            $this->data['SwitchProfile']['sip_capture_ip'] = $server[0][0]['sip_capture_ip'];
            $this->data['SwitchProfile']['sip_capture_port'] = $server[0][0]['sip_capture_port'];
            $this->data['SwitchProfile']['sip_capture_path'] = $server[0][0]['sip_capture_path'];

            if ($id)
                $this->data['SwitchProfile']['id'] = $id;
            else
                $this->_check_data($server_id, $this->data['SwitchProfile']['profile_name'], $this->data['SwitchProfile']['sip_ip'], $this->data['SwitchProfile']['sip_port']);

            if ($this->SwitchProfile->save($this->data))
            {
                if ($id)
                {
                    $log_type = 2;
                    $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [%s] is modified successfully!', true, $this->data['SwitchProfile']['profile_name'])));
                }
                else
                {
                    $log_type = 0;
                    $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [%s] is created successfully!', true, $this->data['SwitchProfile']['profile_name'])));
                }
                $log_id = $this->SwitchProfile->logging($log_type, 'Switch Profile', "Name:{$this->data['SwitchProfile']['switch_name']}");
                $url_flug = "server_config-index";
                if (!$is_config)
                    $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/switch_profiler-index-" . $server_id);
            }
            else
            {
                if ($id)
                {
                    $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The SIP Profile [%s] is modified failed!', true, $this->data['SwitchProfile']['profile_name'])));
                }
                else
                {
                    $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The SIP Profile [%s] is created failed!', true, $this->data['SwitchProfile']['profile_name'])));
                }
            }
            $this->xredirect("/server_config/index");
        }
        $this->data = $this->SwitchProfile->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('server_id', $server_id);
        $this->set('id', $id);
        $this->set('isedit', $id != 0);
    }

    public function _check_data($server_id, $profile_name, $ip, $port)
    {
        Configure::write('debug', 0);
        $result = $this->SwitchProfile->check_data($server_id, $profile_name, $ip, $port);
        if (!$result)
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The SIP Profile must be unique!', true)));
            $this->xredirect("/switch_profiler/index/" . $server_id);
        }
    }

    public function delete($server_id, $id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->data = $this->SwitchProfile->find('first', Array('conditions' => Array('id' => $id)));
        $result = $this->SwitchProfile->del($id);
        if ($result)
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [%s] is deleted successfully!', true, $this->data['SwitchProfile']['switch_name'])));
            $log_id = $this->SwitchProfile->logging(1, 'Switch Profile', "Name:{$this->data['SwitchProfile']['switch_name']}");
            $url_flug = "server_config-index";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/switch_profiler-index-" . $server_id);
        }
        else
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The SIP Profile [%s] is deleted failed!', true, $this->data['SwitchProfile']['switch_name'])));
        }

        $this->xredirect("/server_config/index");
    }

    public function set_default_register($server_id, $id)
    {
        $this->SwitchProfile->updateAll(array('SwitchProfile.default_register' => 0), array("SwitchProfile.voip_gateway_id" => $server_id));
        $switch_profiler = $this->SwitchProfile->find('first', Array('conditions' => Array('id' => $id)));
        $switch_profiler['SwitchProfile']['default_register'] = 1;
        $this->SwitchProfile->save($switch_profiler);
        $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [%s] is set to default register successfully!', true, $switch_profiler['SwitchProfile']['switch_name'])));
        $this->xredirect("/server_config/index");
    }

    public function reload()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::load('myconf');
        $cmd = "sip_profile_start";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048))
            {
                if (strpos($out, "~!@#$%^&*()") !== FALSE)
                {
                    break;
                }
                unset($out);
            }
        }
        socket_close($socket);
        $this->Session->write('m', $this->SwitchProfile->create_json(201, __('Successfully!', true)));
        $this->xredirect("/server_config/index");
    }

}

?>
