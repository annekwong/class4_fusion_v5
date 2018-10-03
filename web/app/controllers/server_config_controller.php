<?php

class ServerConfigController extends AppController
{

    var $name = "ServerConfig";
    var $uses = array('ServerConfig');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function index()
    {
//        $this->loadModel('VersionInfo');

//        $uuids = array();
//        $versionInfo = $this->VersionInfo->find('all', array(
//            'fields' => array('serial_number', 'switch_name')
//        ));

//        foreach ($versionInfo as $item) {
//            if ($item['VersionInfo']['serial_number']) {
//                $tempSerial = explode('-', $item['VersionInfo']['serial_number']);
//                $ipInfo = explode(':', $tempSerial[4]);
//                array_pop($tempSerial);
//
//                array_push($uuids, array(
//                    'uuid' => implode('-', $tempSerial),
//                    'ip' => $ipInfo[0],
//                    'port' => $ipInfo[1],
//                    'switch' => $item['VersionInfo']['switch_name']
//                ));
//            }
//        }

        $this->pageTitle = "Configuration/VoIP Gateway";
        $this->paginate = array(
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('ServerConfig');
        foreach ($this->data as $item_key =>$this_data_item)
        {
            $item_lan_ip = $this_data_item['ServerConfig']['lan_ip'];
            $item_lan_port = $this_data_item['ServerConfig']['lan_port'];
            $is_active = $this->ServerConfig->connection_test($item_lan_ip,$item_lan_port);
            $this->data[$item_key]['ServerConfig']['active'] = $is_active;
//            $this->data[$item_key]['ServerConfig']['uuid'] = '';
//
//            foreach ($uuids as $uuid) {
//                if ($this->data[$item_key]['ServerConfig']['name'] == $uuid['switch'] || ($uuid['ip'] == $item_lan_ip && $uuid['port'] == $item_lan_port)) {
//                    $this->data[$item_key]['ServerConfig']['uuid'] = $uuid['uuid'];
//                }
//            }

        }
    }

    public function action_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if (empty($this->data['ServerConfig']['name']))
            {
                $this->Session->write('m', $this->ServerConfig->create_json(201, __('The VoIP Server name is null!', true)));
                $this->xredirect("/server_config/index");
            }
            if ($this->ServerConfig->exists_name($this->data['ServerConfig']['name'], $id))
            {
                $this->Session->write('m', $this->ServerConfig->create_json(101, __('The VoIP Server Name [%s] is already exists!', true, $this->data['ServerConfig']['name'])));
                $this->xredirect("/server_config/index");
            }
            if ($id != null)
            {
                $this->data['ServerConfig']['id'] = $id;
                $name = $this->data['ServerConfig']['name'];
                $lan_ip = $this->data['ServerConfig']['lan_ip'];
                $lan_port = (int) $this->data['ServerConfig']['lan_port'];
                $active_call_ip = $this->data['ServerConfig']['active_call_ip'];
                $active_call_port = (int) $this->data['ServerConfig']['active_call_port'];
                $sip_capture_ip = $this->data['ServerConfig']['sip_capture_ip'];
                $sip_capture_port = (int) $this->data['ServerConfig']['sip_capture_port'];
                $sip_capture_path = $this->data['ServerConfig']['sip_capture_path'];

                $flg = $this->ServerConfig->query("update switch_profile  set lan_ip = '{$lan_ip}' , "
                    . "lan_port = {$lan_port} "
                    . ", active_call_ip = '{$active_call_ip}' "
                    . ", active_call_port = {$active_call_port} "
                    . ", sip_capture_ip = '{$sip_capture_ip}' "
                    . ", sip_capture_port = {$sip_capture_port} "
                    . ", sip_capture_path = '{$sip_capture_path}'   where voip_gateway_id = {$id} ");

                $flg1 = $this->ServerConfig->query("update voip_gateway  set lan_ip = '{$lan_ip}' , "
                    . "lan_port = {$lan_port} "
                    . ", active_call_ip = '{$active_call_ip}' "
                    . ", active_call_port = {$active_call_port} "
                    . ", sip_capture_ip = '{$sip_capture_ip}' "
                    . ", sip_capture_port = {$sip_capture_port} "
                    . ", sip_capture_path = '{$sip_capture_path}',name ='{$name}'   where id = {$id} ");

                if ($flg === false || $flg1 === false)
                {
                    $this->Session->write('m', $this->ServerConfig->create_json(101, __('The VoIP Server [%s] is modified failed!', true, $this->data['ServerConfig']['name'])));
                }
                else
                {
                    $this->Session->write('m', $this->ServerConfig->create_json(201, __('The VoIP Server [%s] is modified successfully!', true, $this->data['ServerConfig']['name'])));
                }
                $log_type = 2;
            }
            else
            {
                $name = $this->data['ServerConfig']['name'];
                $lan_ip = $this->data['ServerConfig']['lan_ip'];
                $lan_port = (int) $this->data['ServerConfig']['lan_port'];
                if (empty ($name) || empty($lan_ip) || empty($lan_port))
                {
                    $this->Session->write('m', $this->ServerConfig->create_json(101, __('The Info can not be null',true)));
                    $this->xredirect("/server_config/index");
                }
                $flg = $this->ServerConfig->save($this->data['ServerConfig']);
                if ($flg === false)
                    $this->Session->write('m', $this->ServerConfig->create_json(101, __('The VoIP Server [%s] is created failed!', true, $this->data['ServerConfig']['name'])));
                else
                    $this->Session->write('m', $this->ServerConfig->create_json(201, __('The VoIP Server [%s] is created successfully!', true, $this->data['ServerConfig']['name'])));
                //$insert_sql = "INSERT INTO voip_gateway (name) VALUES ('{$this->params['data']['ServerConfig']['name']}')";
                //$this->ServerConfig->query($insert_sql);
                //var_dump($this->data['ServerConfig']);exit;
                $log_type = 0;
            }
            if ($flg !== false)
            {
                $log_id = $this->ServerConfig->logging($log_type, 'Voip Gateway', "Name:{$this->data['ServerConfig']['name']}");
                $url_flug = "server_config-index";
                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/server_config-index");
            }
            else
            {
                $this->xredirect("/server_config/index");
            }
        }
        $this->data = $this->ServerConfig->find('first', Array('conditions' => Array('id' => $id)));
    }

    public function delete($encode_id)
    {
        $this->loadModel('SwitchProfile');
        $id = base64_decode($encode_id);

        $this->SwitchProfile->query("DELETE FROM switch_profile WHERE voip_gateway_id=$id");
        $this->data = $this->ServerConfig->find('first', Array('conditions' => Array('id' => $id)));
        $flg = $this->ServerConfig->delete($id);
        if ($flg)
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The VoIP Gateway [%s] is deleted successfully!', true, $this->data['ServerConfig']['name'])));
        }
        else
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The VoIP Gateway [%s] is deleted failed!', true, $this->data['ServerConfig']['name'])));
        }

        $this->xredirect("/server_config/index");
    }

    public function _check_server_name()
    {

    }

}

?>