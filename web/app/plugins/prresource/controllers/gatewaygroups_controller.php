<?php

class GatewaygroupsController extends AppController
{

    var $name = 'Gatewaygroups';
    var $helpers = array('javascript', 'html', 'appGetewaygroup');
    var $defaultHelper = 'appGetewaygroup';
    var $uses = array("Client", "Blocklist", "prresource.Gatewaygroup", "ResourceNextRouteRule", 'Resource', 'Dynamicroute', 'IpModifyLog', 'Product', 'Productitem', 'ResourceReplaceAction', 'ServerConfig', 'SwitchProfile', 'EgressProfile', 'Systemparam');
    var $rollback_flg = false;

    function index()
    {
        $this->redirect('view_egress');
    }

    function init_info()
    {
        $this->set('c', $this->Gatewaygroup->findClient());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
        $this->set('d', $this->Gatewaygroup->findcodecs());
        //$this->set('p', $this->Gatewaygroup->findAllProduct());
        $this->set('rate', $this->Gatewaygroup->findAllRate());

        $this->set('switch_sip_profiles', $this->Gatewaygroup->find_switch_profiles());
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('route_policy', $this->Gatewaygroup->find_routepolicy());
        $this->loadModel('Blocklist');
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
        $this->loadModel('Client');
        //$this->set('product', $this->Client->findAllProduct());
        //$this->set('dyn_route', $this->Client->findDyn_route());
        $this->set('routepolicy', $this->Client->query("select * from route_strategy"));
        //$this->set('staticlist', $this->Client->query("select product_id, name from product order by name asc"));
        //$this->set('dynamiclist', $this->Client->query("select dynamic_route_id, name from dynamic_route order by name"));
        $this->set('default_timeout', $this->Gatewaygroup->getTimeout());
        $this->loadModel('RandomAniTable');
        $this->set('random_table', $this->RandomAniTable->find_all());
    }

    public function replace_action($egress_id, $type = 'egress')
    {
        $egress_id = base64_decode($egress_id);
        if ($this->RequestHandler->ispost()) {
            $sql = "delete from resource_replace_action where resource_id = {$egress_id}";
            $this->Gatewaygroup->query($sql);
            if ($this->params['form']['change_type'])
                $cnt = count($_POST['dnis_prefix']);
            else
                $cnt = count($_POST['ani_prefix']);
            $insert_arr = array();

            for ($i = 0; $i < $cnt; $i++) {
                $ani_prefix = @$_POST['ani_prefix'][$i];
                $ani = @$_POST['ani'][$i];
                $ani_min_length = @$_POST['ani_min_length'][$i];
                $ani_max_length = @$_POST['ani_max_length'][$i];

                $dnis_prefix = @$_POST['dnis_prefix'][$i];
                $dnis = @$_POST['dnis'][$i];
                $dnis_min_length = @$_POST['dnis_min_length'][$i];
                $dnis_max_length = @$_POST['dnis_max_length'][$i];
                $change_type = (int)@$_POST['change_type'][$i];
                if ($change_type != 1) {//ani 必须有
                    if (empty($ani)) {
                        continue;
                    }
                } elseif ($change_type != 0) {//dnis 必须有
                    if (empty($dnis)) {
                        continue;
                    }
                }
                $insert_arr[] = array(
                    'resource_id' => $egress_id,
                    'ani_prefix' => $ani_prefix,
                    'ani' => $ani,
                    'ani_min_length' => $ani_min_length,
                    'ani_max_length' => $ani_max_length,
                    'dnis_prefix' => $dnis_prefix,
                    'dnis' => $dnis,
                    'dnis_min_length' => $dnis_min_length,
                    'dnis_max_length' => $dnis_max_length,
                    'type' => $change_type
                );
            }
            $flg = $this->ResourceReplaceAction->saveAll($insert_arr);
            if ($flg === false || empty($insert_arr)) {
                $this->Session->write('m', $this->ResourceReplaceAction->create_json(101, __('failed', true)));
            } else {
                $this->Session->write('m', $this->ResourceReplaceAction->create_json(201, __('success', true)));
            }
            $this->redirect('replace_action/' . base64_encode($egress_id) . '/' . $type);

        }


        $res = $this->Gatewaygroup->findByResourceId($egress_id);
        $client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$egress_id});");

        $type_arr = array(
            0 => 'ANI',
            1 => 'DNIS',
            2 => 'Both',
        );
        $this->set('type_arr', $type_arr);
        $sql = "select * from resource_replace_action where resource_id = {$egress_id}";
        $result = $this->Gatewaygroup->query($sql);
        //pre($result);
        $this->set('result', $result);
        $this->set('client_name', $client_name[0][0]['name']);
        $this->set('res', $res);
        $this->set('type', $type);
    }

    public function pass_trusk($res_id, $type)
    {
        $res_id = base64_decode($res_id);
        if ($this->data['Gatewaygroup']) {
            $this->data['Gatewaygroup']['resource_id'] = $res_id;
            $this->Gatewaygroup->save($this->data);
            $res = $this->Gatewaygroup->findByResourceId($res_id);
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', __('The Egress Trunk [%s] is modified successfully !', true, $res['Gatewaygroup'] ['alias']));
            $this->Session->write("m", Gatewaygroup::set_validator());
        } else {
            $res = $this->Gatewaygroup->findByResourceId($res_id);
        }

        $client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");

        $this->set('res', $res);
        $this->set('client_name', $client_name[0][0]['name']);
        $this->set('type', $type);
    }

    //billing
    public function billing($res_id, $type)
    {
        $res_id = base64_decode($res_id);
        if ($this->data['Gatewaygroup']) {
            $this->data['Gatewaygroup']['resource_id'] = $res_id;
            $this->Gatewaygroup->save($this->data);
            $res = $this->Gatewaygroup->findByResourceId($res_id);
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', __('The Egress Trunk [%s] is modified successfully !', true, $res['Gatewaygroup'] ['alias']));
            $this->Session->write("m", Gatewaygroup::set_validator());
        } else {
            $res = $this->Gatewaygroup->findByResourceId($res_id);
        }

        $client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");

        $this->set('post', $res);
        $this->set('client_name', $client_name[0][0]['name']);
        $this->set('type', $type);
    }


    public function allowed_send_to_ip($res_id, $type)
    {
        $res_id = base64_decode($res_id);
        //var_dump($this->data);
        //   exit;
        if ($this->RequestHandler->ispost()) {
            $this->data['Gatewaygroup']['resource_id'] = $res_id;
            $this->Gatewaygroup->query("delete from allowed_sendto_ip where resource_id = {$res_id} ");
            $sip_ips = $this->data['sip_ip'];
            if (!empty($sip_ips)) {
                foreach ($sip_ips as $val) {
                    $val = explode(':', $val);
                    if (count($val) == 2) {
                        $this->Gatewaygroup->query(" insert into allowed_sendto_ip (resource_id,direction,sip_profile_ip,sip_profile_port)   "
                            . " select {$res_id},0,'{$val[0]}',{$val[1]}  "
                            . " WHERE NOT EXISTS (SELECT * from allowed_sendto_ip where resource_id = {$res_id} and direction = 0 and sip_profile_ip = '{$val[0]}' and  sip_profile_port = {$val[1]} )  ");
                    }
                }
            }

            //$this->Gatewaygroup->save($this->data);
            $res = $this->Gatewaygroup->findByResourceId($res_id);
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', __('The Egress Trunk [%s] is modified successfully !', true, $res['Gatewaygroup'] ['alias']));
            $this->Session->write("m", Gatewaygroup::set_validator());

            //var_dump($sip_ips);
            //exit;
        } else {
            $res = $this->Gatewaygroup->findByResourceId($res_id);
        }

        $client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");
        $options = $this->Gatewaygroup->query("select * from voip_gateway");
        $results = $this->Gatewaygroup->query(" select voip_gateway.name,switch_profile.* from switch_profile "
            . "  left join voip_gateway on voip_gateway.id=switch_profile.voip_gateway_id  "
            . "where switch_profile.sip_ip||switch_profile.sip_port in ( select sip_profile_ip::text||sip_profile_port from allowed_sendto_ip where resource_id =  {$res_id} ) ");
        //var_dump($res);
//        die(var_dump($results));
//        $results = array();
        $this->set('results', $results);
        $this->set('options', $options);
        $this->set('res', $res);
        $this->set('client_name', $client_name[0][0]['name']);
        $this->set('type', $type);
    }

    function get_ip()
    {
        Configure::write('debug', 0);
        $id = addslashes($_POST['id']);
        $ip_id = $_POST['ip_id'];
        $ips = $this->Gatewaygroup->query("select * from switch_profile where voip_gateway_id = {$id} ");
        $this->set('ips', $ips);
        $this->set('ip_id', $ip_id);

    }

    public function sip_profile($encode_egress_id)
    {
        Configure::write('debug', 0);
        $egress_id = base64_decode($encode_egress_id);
        $egress_info = $this->Gatewaygroup->findByResourceId($egress_id);
        $switch_profiles = $this->Gatewaygroup->get_gateway_profiles();
        $ingressTrunks = $this->Gatewaygroup->findAll_ingress(
            $this->_order_condtions(
                array('client.name', 'client_id', 'resource_id', 'capacity', 'cps_limit', 'active', 'ip_cnt', 'profit_margin')
            )
        );
        $ingressTrunks = $ingressTrunks->dataArray;

        foreach ($switch_profiles as &$switch_profile) {
            $server_name = $switch_profile['name'];
            $count = $this->EgressProfile->find('count', array(
                'conditions' => array(
                    'server_name' => $server_name,
                    'egress_id' => $egress_id
                ),
            ));
            $switch_profile['egress_profile_count'] = $count;
            $egressProfiles = $this->EgressProfile->find('all', array(
                'fields' => array(
                    'EgressProfile.id',
                    'egress_id',
                    'profile_id',
                    'ingress_id',
                    'server_name',
                    'SwitchProfile.sip_ip',
                    'Gatewaygroup.alias',
                ),
                'conditions' => array(
                    'egress_id' => $egress_id
                ),
                'joins' => array(
                    array(
                        'alias' => 'SwitchProfile',
                        'table' => 'switch_profile',
                        'conditions' => array(
                            'SwitchProfile.id = EgressProfile.profile_id'
                        ),
                    ),
                    array(
                        'alias' => 'Gatewaygroup',
                        'table' => 'resource',
                        'conditions' => array(
                            'Gatewaygroup.resource_id = EgressProfile.ingress_id'
                        ),
                    ),
                ),
            ));
            // check if selected all
//            $profile_ids = [];
//            foreach ($egressProfiles as $egressProfile) {
//                $profile_ids[] = $egressProfile["EgressProfile"]["profile_id"];
//            }
//            if(count(array_unique($profile_ids)) == 1 && count($profile_ids) == count($ingressTrunks)){
//
//
//                $switch_profile['egress_profiles'] = [
//                    [
//                        'EgressProfile' => $egressProfiles[0]["EgressProfile"],
//                        'SwitchProfile'  => $egressProfiles[0]["SwitchProfile"],
//                        'Gatewaygroup'  => ['alias'=>'all']
//                    ]
//                ];
//            }else{
//                $switch_profile['egress_profiles'] = $egressProfiles;
//            }
            $switch_profile['egress_profiles'] = $egressProfiles;
        }
//        echo '<pre>';
//        die(var_dump($switch_profiles));
        $client_name = $this->Gatewaygroup->find('first', array(
            'fields' => array('Client.name'),
            'conditions' => array(
                'Gatewaygroup.resource_id' => $egress_id,
            ),
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'conditions' => array(
                        'Gatewaygroup.client_id = Client.client_id'
                    ),
                ),
            ),
        ));


        $this->set('ingressTrunks', $ingressTrunks);
        $this->set('client_name', $client_name['Client']['name']);
        $this->set('switch_profiles', $switch_profiles);
        $this->set('res', $egress_info);
        $this->set('resource_id', $encode_egress_id);
    }

    public function sip_profile_save_ip()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($this->RequestHandler->isPost()) {
            $id = $_POST['id'];
            $ip = $_POST['ip'];
            $sql = "UPDATE voip_gateway SET lan_ip='{$ip}' WHERE id={$id}";
            $flg = $this->Gatewaygroup->query($sql);
            if ($flg !== false) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function sip_profile_detail()
    {
        $this->loadModel('EgressProfile');
        $server_name = $this->params['url']['server_name'];
        $egress_id = base64_decode($this->params['url']['egress_id']);
        $this->params['pass'][0] = $this->params['url']['egress_id'];
        $conditions = array(
            'server_name' => $server_name,
            'egress_id' => $egress_id
        );
        $order_arr = array('Resource.alias' => 'ASC');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array(
            'fields' => array(
                'EgressProfile.id', 'Resource.alias', 'SwitchProfile.profile_name', 'SwitchProfile.sip_ip',
            ),
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.resource_id = EgressProfile.ingress_id'
                    ),
                ),
                array(
                    'alias' => 'SwitchProfile',
                    'table' => 'switch_profile',
                    'conditions' => array(
                        'EgressProfile.profile_id = SwitchProfile.id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('EgressProfile');
        $client_name = $this->Gatewaygroup->find('first', array(
            'fields' => array('Client.name', 'Gatewaygroup.alias'),
            'conditions' => array(
                'Gatewaygroup.resource_id' => $egress_id,
            ),
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'conditions' => array(
                        'Gatewaygroup.client_id = Client.client_id'
                    ),
                ),
            ),
        ));
        $this->set('client_name', $client_name['Client']['name']);
        $this->set('resource_name', $client_name['Gatewaygroup']['alias']);
        $this->set('server_name', $server_name);
        $this->set('back_url', 'prresource/gatewaygroups/sip_profile/' . $this->params['pass'][0]);
    }

    public function add_egress_profile()
    {
        $this->loadModel('EgressProfile');
        $egress_id = $this->params['form']['egress_id'];
        $server_name = $this->params['form']['server_name'];
        if ($this->params['isAjax']) {
            Configure::write('debug', 0);
            $ingress_arr = $this->Gatewaygroup->findAll_ingress_id(false);
            $egress_profile_ingress_arr = $this->EgressProfile->find('all', array(
                'fields' => array(
                    'ingress_id'
                ),
                'conditions' => array(
                    'egress_id' => $egress_id,
                    'server_name' => $server_name,
                )
            ));
            foreach ($egress_profile_ingress_arr as $egress_profile_ingress_item) {
                $tmp_ingress_id = $egress_profile_ingress_item['EgressProfile']['ingress_id'];
                if (array_key_exists($tmp_ingress_id, $ingress_arr)) {
                    unset($ingress_arr[$tmp_ingress_id]);
                }
            }
            $this->loadModel('SwitchProfile');
            $switch_profile_arr = $this->SwitchProfile->find('all', array(
                'fields' => array(
                    'SwitchProfile.profile_name', 'SwitchProfile.id', 'SwitchProfile.sip_ip'
                ),
                'conditions' => array(
                    'VoipGateway.name' => $server_name,
                ),
                'joins' => array(
                    array(
                        'alias' => 'VoipGateway',
                        'table' => 'voip_gateway',
                        'type' => 'inner',
                        'conditions' => array(
                            'VoipGateway.id = SwitchProfile.voip_gateway_id'
                        ),
                    ),
                )
            ));
            $this->set('switch_profile_arr', $switch_profile_arr);
            $this->set('ingress_arr', $ingress_arr);
        } else {
            $exist_data = $this->EgressProfile->find('first', array(
                'fields' => array(
                    'id'
                ),
                'conditions' => array(
                    'egress_id' => $egress_id,
                    'server_name' => $server_name,
                )
            ));
            if (isset($exist_data['EgressProfile']['id'])) {
                $this->EgressProfile->del($exist_data['EgressProfile']['id']);
            }

            $insert_arr = array();
            if (empty($this->data['ingress'])) {

                $insert_arr[] = array(
                    'egress_id' => $egress_id,
                    'server_name' => $server_name,
                    'profile_id' => $this->data['switch_profile'],
                );
            } else {
                foreach ($this->data['ingress'] as $key => $ingress) {
                    $insert_arr[$key] = array(
                        'egress_id' => $egress_id,
                        'server_name' => $server_name,
                        'profile_id' => $this->data['switch_profile'],
                        'ingress_id' => $ingress
                    );
                }
            }

            $flg = $this->EgressProfile->saveAll($insert_arr);
            if ($flg === false)
                $this->Session->write('m', $this->EgressProfile->create_json(101, __('Add Failed', true)));
            else
                $this->Session->write('m', $this->EgressProfile->create_json(201, __('Add successfully', true)));
            $this->Session->write('mm', 2);
            $this->redirect("sip_profile_detail?server_name={$server_name}&egress_id=" . base64_encode($egress_id));
        }
    }

    public function delete_egress_profile()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->loadModel('EgressProfile');
        $egress_profile_id = $_POST['profile_id'];
        $flg = $this->EgressProfile->delete($egress_profile_id);
        if ($flg === false)
            echo 0;
        else
            echo 1;
    }

    public function delete_egress_profile_seleted()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->loadModel('EgressProfile');
        $condition = array(
            'id' => explode(',', $this->params['url']['ids'])
        );
        $flg = $this->EgressProfile->deleteAll($condition);
        if ($flg === false)
            $this->Session->write('m', $this->EgressProfile->create_json(101, __('Delete Failed', true)));
        else
            $this->Session->write('m', $this->EgressProfile->create_json(201, __('Delete successfully', true)));
        $this->Session->write('mm', 2);
        $this->redirect("sip_profile_detail?" . $this->params['getUrl']);

    }

    public function delete_egress_profile_all()
    {
//        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->loadModel('EgressProfile');
        $condition = array(
            'egress_id' => base64_decode($this->params['url']['egress_id']),
            'server_name' => $this->params['url']['server_name']
        );
        $flg = $this->EgressProfile->deleteAll($condition);
        if ($flg === false)
            $this->Session->write('m', $this->EgressProfile->create_json(101, __('Delete Failed', true)));
        else
            $this->Session->write('m', $this->EgressProfile->create_json(201, __('Delete successfully', true)));
        $this->Session->write('mm', 2);
        $this->redirect("sip_profile_detail?" . $this->params['getUrl']);
    }

    public function sip_profile_bak($res_id)
    {
        $res_id = base64_decode($res_id);
        if ($this->RequestHandler->ispost()) {
            $profiles = $_POST['profiles'];
            $server_names = $_POST['server_names'];
            $ingresses = $_POST['ingress'];
            $this->Gatewaygroup->_update_profiles($res_id, $profiles, $server_names, $ingresses);
            $res = $this->Gatewaygroup->findByResourceId($res_id);
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', __('The Egress Trunk [%s] is modified successfully !', true, $res['Gatewaygroup'] ['alias']));
            $this->Session->write("m", Gatewaygroup::set_validator());
        } else {
            $res = $this->Gatewaygroup->findByResourceId($res_id);
        }


        $client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");

        $this->set('res', $res);
        $this->set('switch_profiles', $this->Gatewaygroup->get_gateway_profiles());
        pr($this->Gatewaygroup->get_gateway_profiles());
        /*
          $ingresses = $this->Gatewaygroup->find('all', array(
          'conditions' => array('Gatewaygroup.ingress' => true),
          'fields' => array('Gatewaygroup.resource_id', 'Gatewaygroup.alias'),
          'order' => array('Gatewaygroup.alias'),
          ));
         *
         */
        $this->set('ingresses', $this->Gatewaygroup->find_ingress_resource());
        $use_ingresses = $this->Gatewaygroup->get_profile_ingresses($res_id);
        $profiles = $this->Gatewaygroup->get_profiles($res_id);
        $this->set('sip_profiles', $profiles);
        $this->set('client_name', $client_name[0][0]['name']);
        $this->set('use_ingresses', $use_ingresses);
    }

    public function automatic_rate_processing($egress_id)
    {
//        $sql = "select * from automatic_rate where egress_id = {$egress_id}";
//        $result = $this->Gatewaygroup->query($sql);
        $result = array();
        $cols = array(
            'country',
            'code_name',
            'code',
            'rate',
            'inter_rate',
            'intra_rate',
            'local_rate',
            'effective_date',
        );

        if ($this->RequestHandler->ispost()) {
            $from_email = $_POST['from_email'];
            $day = $_POST['day'];
            $start_line = $_POST['start_line'];
            //$formats = implode(',', $_POST['formats']);
            $orders = $_POST['orders'];
            $change_orders = array_flip($orders);
            $new_orders = array();
            $num_columns = (int)$_POST['number_column'];
            for ($i = 1; $i <= $num_columns; $i++) {
                array_push($new_orders, isset($change_orders[$i]) ? $cols[$change_orders[$i]] : '');
            }

            $formats = implode(',', $new_orders);

            $end_date_type = $_POST['end_date_type'];
            $end_date_when = $_POST['end_date_when'];
            if (empty($result)) {
//                $sql = "insert into automatic_rate(from_email, day, start_line, format, end_date_type, end_date_when, egress_id) values ('{$from_email}', {$day}, {$start_line}, '{$formats}', {$end_date_type}, {$end_date_when}, {$egress_id})";
            } else {
//                $sql = "update automatic_rate set from_email = '{$from_email}', day = {$day}, start_line = {$start_line}, format = '{$formats}', end_date_type = {$end_date_type}, end_date_when = {$end_date_when} where id = {$result[0][0]['id']}";
            }
            $this->Gatewaygroup->query($sql);
            $sql = "select alias, (select name from client where client_id = resource.client_id) as name from resource where resource_id = {$egress_id}";
            $name_result = $this->Gatewaygroup->query($sql);
            $this->Gatewaygroup->create_json(201, __("The Trunk [%s]'s Automatic Rate Processing is modified successfully", true, $name_result[0][0]['name']));
            $this->Session->write("m", Gatewaygroup::set_validator());
            $this->redirect('/prresource/gatewaygroups/automatic_rate_processing/' . $egress_id);
        }
        $sql = "select alias, (select name from client where client_id = resource.client_id) as name from resource where resource_id = {$egress_id}";
        $name_result = $this->Gatewaygroup->query($sql);
        $this->set('name', $name_result[0][0]['alias']);
        $this->set('client_name', $name_result[0][0]['name']);
        /*
          $cols = array(
          'country' => 'Country',
          'code_name' => 'Code Name',
          'code' => 'Code',
          'rate' => 'Rate',
          'inter_rate' => 'Interstate Rate',
          'intra_rate' => 'Intrastate Rate',
          'local_rate' => 'Local Rate',
          'effective_date' => 'Effective Date'
          );
         *
         */
        $this->set('cols', $cols);
        if (empty($result)) {
            $this->set('data', NULL);
        } else {
            $result[0][0]['format'] = explode(',', $result[0][0]['format']);
            $this->set('data', $result[0][0]);
        }
    }

    public function get_schema_ingress()
    {
        $fields = array(
            'trunk_id' => 'resource.resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'cps_limit' => "cps_limit",
            'call_limit' => 'capacity',
//            'protocol' => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'ignore_early_media' => "(case when ignore_ring = false and ignore_early_media = false then 'None' when ignore_ring = true and ignore_early_media = true then '180 and 183' when ignore_ring =true and ignore_early_media = false then '180' case when ignore_ring = false and ignore_early_media = true then '183' end)",
            'active' => "(case active when true then 'true' else 'false' end)",
//            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rfc2833' => "(case rfc2833 when 1 then 'true' else 'false' end)",
            'dip_from' => "(case lnp_dipping when true then 'client' else 'server' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
//            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
            'rate_table_name' => '(SELECT name FROM rate_table WHERE rate_table_id = resource_prefix.rate_table_id)',
            'route_strategy_name' => '(SELECT name FROM route_strategy WHERE route_strategy_id = resource_prefix.route_strategy_id)',
            'tech_prefix' => 'resource_prefix.tech_prefix',
            'profit_margin' => 'resource.profit_margin',
        );

        return $fields;
    }

    public function get_schema_digit_mapping()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_translation_ref.resource_id)',
            'translation_name' => '(select translation_name from digit_translation where translation_id = resource_translation_ref.translation_id)',
            'time_profile_name' => "(select name from time_profile where time_profile_id = resource_translation_ref.time_profile_id)",
        );

        return $fields;
    }

    public function get_schema_host()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_ip.resource_id)',
            'ip' => 'ip',
            'port' => "port"
        );

        return $fields;
    }

    function save($type, $resource_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add Ingress Trunk";
        $type = _filter_array(Array('ingress' => 'ingress', 'egress' => 'egress'), $type);
        if ($this->isPost('data')) {
            if ($resource_id) {
                $this->data['Resource']['resource_id'] = $resource_id;
            }
            $this->data['Resource']['accounts'] = array_keys_value_empty($this->params, 'form.accounts', Array());
            if ($this->Resource->save($this->data)) {
                if (!$resource_id) {
                    $resource_id = $this->Resource->getlastinsertId();
                    ////$this->Gatewaygroup->log('add_ingress_trunk');
                    $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add success');
                } else {
                    ////$this->Gatewaygroup->log('edit_ingress_trunk');
                    $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Edit success');
                }
                $this->xredirect("/prresource/gatewaygroups/save/$type/$resource_id");
                return;
            }
            $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
            $this->set('post', $this->data);
        }
        if ($resource_id) {
            $this->loadModel('Resource');
            $this->data = $this->Resource->find('first', Array('conditions' => Array("resource_id=$resource_id")));
        }
        $this->init_info();
        $this->init_codes($resource_id);
        $this->set('type', $type);
        $this->set('resource_id', $resource_id);
    }

    public function delete_ip_id()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ip_id = $_POST['ip_id'];
        if ($ip_id) {
            $sql = "delete from resource_ip where resource_ip_id = {$ip_id} RETURNING ip,resource_id";
            $return = $this->Gatewaygroup->query($sql);
        }
        if ($return) {
            if (isset($ip_id) && $ip_id) {
                $sql = "delete from resource_ip where masked_from = {$ip_id}";
                $this->Gatewaygroup->query($sql);
            }

            $trunk_id = $return[0][0]['resource_id'];
            $email = $this->IpModifyLog->query("select email from client left join resource on client.client_id = resource.client_id where resource_id = $trunk_id");
            $email = $email[0][0]['email'];
            $log_sql = "INSERT INTO ip_modif_log (modify,old,new,update_by,trunk_id,email) VALUES(2,'" . $return[0][0]['ip'] . "','','" . $_SESSION['sst_user_name'] . "'," . $return[0][0]['resource_id'] . ",'$email') returning id";
            $log_info = $this->IpModifyLog->query($log_sql);
            $ip_modif_log_id = $log_info[0][0]['id'];
            $php_path = Configure::read('php_exe_path');
            if ($ip_modif_log_id) {
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php send_email 1 {$ip_modif_log_id} > /dev/null &";
            }
            $info = $this->Systemparam->find('first', array(
                'fields' => array('cmd_debug'),
            ));
            if (Configure::read('cmd_debug')) {
                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
            }
            echo $cmd . '<br />';
            shell_exec($cmd);
        }
        echo 1;
    }

    function init_codes($res_id = null)
    {
        if ($res_id) {
            $this->set('nousecodes', $this->Gatewaygroup->findNousecodecs($res_id));
            $this->set('usecodes', $this->Gatewaygroup->findUsecodecs($res_id));
        } else {
            $this->set('nousecodes', Array());
            $this->set('usecodes', Array());
        }
    }

    public function download_codepart()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params ['pass'] [0];
        $download_sql = "select    code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds
		from  rate  where rate_table_id=$rate_table_id";
        $this->Rate->export__sql_data('Download', $download_sql, 'rate');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    public function download_egress()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $download_sql = "select client.name  as  client_name ,alias, resource.name,ingress,egress,active,t38,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where egress=true ";
        $this->Gatewaygroup->export__sql_data('download Egress', $download_sql, 'egress');
        $this->layout = '';
    }

    public function download_ingress()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $download_sql = "select resource.name,ingress,client.name as  client_name ,egress,active,t38,alias,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where ingress=true ";
        $this->Gatewaygroup->export__sql_data(__('DownloadIngress', true), $download_sql, 'ingress');
        $this->layout = '';
    }

    public function view_cdr()
    {
        $res_id = $this->params ['pass'] [0];
        $this->init_info();
        empty($_GET ['page']) ? $currPage = 1 : $currPage = $_GET ['page'];
        empty($_GET ['size']) ? $pageSize = 100 : $pageSize = $_GET ['size'];
        //模糊搜索
        if (isset($_POST ['searchkey'])) {
            $results = $this->Gatewaygroup->likequery($_POST ['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST ['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索
        if (!empty($this->data ['Gatewaygroup'])) {
            $results = $this->Gatewaygroup->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            //普通查询
            $results = $this->Gatewaygroup->view_all_cdr($currPage, $pageSize, $res_id);
        }

        $this->set('p', $results);
    }

    //查看指定号段的cdr
    public function code_cdr()
    {
        $start_code = $this->params ['pass'] [0];
        $end_code = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("select * from  cdr   where  origination_destination_number >'$start_code' and  origination_destination_number <'$end_code'");

        empty($_GET ['page']) ? $currPage = 1 : $currPage = $_GET ['page'];
        empty($_GET ['size']) ? $pageSize = 100 : $pageSize = $_GET ['size'];
        //模糊搜索
        if (isset($_POST ['searchkey'])) {
            $results = $this->Gatewaygroup->likequery($_POST ['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST ['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索
        if (!empty($this->data ['Gatewaygroup'])) {
            $results = $this->Gatewaygroup->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            $results = $this->Gatewaygroup->code_cdr($currPage, $pageSize, $start_code, $end_code);
        }
        $this->set('p', $results);
    }

    public function del_all_codepart()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("delete from  code_part  where egress_id=$egress_id");
        ////$this->Gatewaygroup->log('delete_all_code_part');
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function del__codepart()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $this->Gatewaygroup->query("delete from  code_part  where code_part_id=$id");
        ////$this->Gatewaygroup->log('delete_code_part');
        $egress_id = $_SESSION ['codepartengress'];
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function update_codepart()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        $start_code = $_GET ['start'];
        $end_code = $_GET ['end'];
        $card = $_GET ['card'];
        $id = $_GET ['id'];
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("update  code_part set start_code='$start_code',end_code='$end_code',account_id=$card,egress_id=$egress_id
			   where code_part_id=$id");
        //$this->Gatewaygroup->log('update_code_part');
        $this->redirect("/gatewaygroups/codepart/$egress_id");
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
            $limit = $this->Session->read('sst_voipgateway');
            $this->Session->write('executable', $limit ['executable']);
            $this->Session->write('writable', $limit ['writable']);
        }
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_r']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isGet()) {
            $url = $this->get_curr_url();
            if (!isset($_SESSION['back_url'])) {
                $last_url = $url;
                $curr_url = $url;
                $_SESSION['back_url'] = $last_url;
                $_SESSION['curr_url'] = $curr_url;
            } else {
                if ($_SESSION['curr_url'] != $url) {
                    $_SESSION['curr_url'] = $url;
                }
                if (strpos($url, "view")) {
                    $_SESSION['back_url'] = $url;
                }
            }
        }
        parent::beforeFilter();
    }

    public function get_prefix($static_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $prefix_results = $this->Gatewaygroup->query("select digits from product_items where product_id = {$static_id}");
        echo json_encode($prefix_results);
    }

    /* 通过Resid查找
     *
     */

    public function init__info_byResID($res_id)
    {
        $this->set('g', $this->Gatewaygroup->findResByres_id($res_id));
        $this->set('res_ip', $this->Gatewaygroup->findAllres_ip($res_id));
        $this->set('res_direct', $this->Gatewaygroup->findresdirectByRes_id($res_id));
        // $this->set('res_product',$this->Gatewaygroup->findresproductByRes_id($res_id));
        $this->set('user_codes', $this->Gatewaygroup->findUsecodecs($res_id));
        $this->set('nouser_codes', $this->Gatewaygroup->findNousecodecs($res_id));
    }

    public function show_prefixs($static_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT digits FROM product_items WHERE product_id = {$static_id} ORDER by digits ASC";
        $prefixs = $this->Gatewaygroup->query($sql);
        $result = array();
        foreach ($prefixs as $prefix)
            array_push($result, $prefix[0]['digits']);
        echo json_encode($result);
    }

    public function add_prefix()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $prefix = $_POST['prefix'];
        $static_id = $_POST['static_id'];
        $sql_p = "SELECT count(*) FROM product_items WHERE digits = '{$prefix}' and product_id = {$static_id}";
        $count_r = $this->Gatewaygroup->query($sql_p);
        if ($count_r[0][0]['count'] > 0) {
            echo 0;
        } else {
            $sql = "INSERT INTO product_items(digits, strategy, product_id) VALUES('{$prefix}', 1, {$static_id})";
            $this->Gatewaygroup->query($sql);
            echo 1;
        }
    }

    /*
     * 删除网关
     */

    function del()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $resource_id = base64_decode($this->params['pass'][0]);
        $result = $this->Resource->findByResourceId($resource_id);
        $alias = $result['Resource']['alias'];
        $dynamic_count = $this->Gatewaygroup->query("SELECT COUNT(*) FROM dynamic_route_items WHERE resource_id = {$resource_id}");

        $static_count = $this->Gatewaygroup->query("SELECT COUNT(*) FROM product_items_resource WHERE resource_id = {$resource_id}");

        if ($dynamic_count[0][0]['count'] > 0 || $static_count[0][0]['count'] > 0) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress trunk is being used; therefore, it cannot be deleted.', true));
        } else {
            if ($result[0][0]['trunk_type2'] == 1) {//为origination
                $this->Gatewaygroup->begin();
                $del_sql = "DELETE FROM ingress_did_repository WHERE egress_id =$resource_id ";
                if ($this->Gatewaygroup->query($del_sql) === false) {
                    $this->rollback = true;
                    $this->Gatewaygroup->rollback();
                }
                $sql = "DELETE FROM users WHERE name = '{$result[0][0]['alias']}';"
                    . "delete from rate_table where rate_table_id = {$result[0][0]['rate_table_id']};delete from resource where resource_id = {$result[0][0]['resource_id']};delete from resource_ip where resource_id = {$result[0][0]['resource_id']};delete from resource_prefix where resource_id = {$result[0][0]['resource_id']};";
                if ($this->Gatewaygroup->query($sql) === false) {
                    $this->rollback = true;
                    $this->Gatewaygroup->rollback();
                }
                $flg = $this->Gatewaygroup->query("delete from users_limit where client_id = {$resource_id}");
                if ($flg === false) {
                    $this->rollback = true;
                    $this->Gatewaygroup->rollback();
                }
                if ($this->Client->del($resource_id, 'false') === false) {
                    $this->rollback = true;
                    $this->Gatewaygroup->rollback();
                }
                $this->Gatewaygroup->commit();
            } else {
                $this->rollback = $this->Gatewaygroup->del($resource_id);
            }
            if ($this->rollback !== false) {
                $this->Gatewaygroup->logging(1, 'Trunk', "Trunk Name:{$alias}");
                //$this->Gatewaygroup->log('delete_getwaygroup');
                $this->Gatewaygroup->create_json_array('', 201, __('The Trunk [%s] is deleted successfully', true, $alias));
            } else {
                $this->Gatewaygroup->create_json_array('', 101, __('Fail to delete Trunk', true));
            }
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $type = $this->params ['pass'] [1];

        if (isset($_GET['viewtype'])) {
            $this->redirect("/prresource/gatewaygroups/{$type}?query[id_clients]={$_GET['query']['id_clients']}&viewtype=client");
        } else {
            $this->redirect(array('action' => $type));
        }
    }

    public function get_schema_egress()
    {
        $fields = array(
            'trunk_id' => 'resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'call_limit' => 'capacity',
            'cps_limit' => "cps_limit",
//            'protocol' => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'active' => "(case active when true then 'true' else 'false' end)",
//            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rate_table_name' => '(select name from rate_table where rate_table_id = resource.rate_table_id)',
            'host_route_strategy' => "(case res_strategy when 1 then 'top-down' else 'round-robin' end)",
            'rfc2833' => "(case rfc2833 when 1 then 'true' else 'false' end)",
            'pass_dip_head' => "(case lnp_dipping when true then 'true' else 'false' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'profit_margin' => 'resource.profit_margin',
//            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
        );

        return $fields;
    }

    function del_selected()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $result = $this->Gatewaygroup->getAliasByids($_REQUEST ['ids']);
        $tip = '';
        foreach ($result as $alias) {
            $tip .= $alias[0]['alias'] . ',';
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        if ($this->Gatewaygroup->del($_REQUEST ['ids'])) {
            //$this->Gatewaygroup->log('delete_select_gatewaygroup');
            $this->Gatewaygroup->create_json_array('', 201, __('The Trunk %s is deleted successfully.', true, $tip));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('Fail to delete Trunk.', true));
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $type = $_REQUEST ['type'];
        $this->redirect(array('action' => $type));
    }

    /**
     * 查询ip
     */
    public function ajax_ip()
    {
        Configure::write('debug', 2);

        $this->set('extensionBeans', $this->Gatewaygroup->findAllres_ip($this->params ['pass'] [0]));
    }

    /**
     * 落地网关
     */
    public function view_egress()
    {
        $this->pageTitle = "Routing/Egress Trunk";
        $this->init_info();
        $this->select_naem($this->_get('query.id_clients'));
        $this->set('p', $this->Gatewaygroup->findAll_egress(
            $this->_order_condtions(
                array('client.name', 'client_id', 'resource_id', 'alias', 'capacity', 'cps_limit', 'active', 'ip_cnt')
            )
        ));
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
            Configure::write('debug', 2);
            $this->render("view_egress_ajax");
        }
        $fields = $this->get_schema_egress();
        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $fields = $this->get_schema_host();
        $keys = array_keys($fields);
        $this->set('host_fields', $keys);
        $fields = $this->get_schema_action();
        $keys = array_keys($fields);
        $this->set('action_fields', $keys);

        $this->set('type', 11);

        $arr = array();
        if ($this->params['getUrl']) {
            $arr = explode('&', $this->params['getUrl']);
            foreach ($arr as $k => $item) {

                if (strpos($item, 'size') !== false)
                    unset($arr[$k]);
            }
        }

        $page_size = isset($_GET['size']) ? $_GET['size'] : 100;


        $this->set('page_size', $page_size);
        $get_url = empty($arr) ? '' : implode('&', $arr);

        $this->set('get_url', $get_url);
    }

    public function view_ingress()
    {
        $this->pageTitle = "Routing/Ingress Trunk";
        $this->init_info();
        $this->select_naem($this->_get('query.id_clients'));
        $this->set('p', $this->Gatewaygroup->findAll_ingress(
            $this->_order_condtions(
                array('client.name', 'client_id', 'resource_id', 'capacity', 'cps_limit', 'active', 'ip_cnt', 'profit_margin')
            )
        ));

        $fields = $this->get_schema_ingress();
        $keys = array_keys($fields);
        $this->set('fields', $keys);

        $fields = $this->get_schema_host();
        $keys = array_keys($fields);
        $this->set('host_fields', $keys);

        $fields = $this->get_schema_action();
        $keys = array_keys($fields);
        $this->set('action_fields', $keys);

        $fields = $this->get_schema_digit_mapping();
        $keys = array_keys($fields);
        $this->set('digits_fields', $keys);

        $sql = "select rate_table.name,rate_table.rate_table_id,resource_prefix.resource_id from resource_prefix inner join rate_table"
            . " on resource_prefix.rate_table_id = rate_table.rate_table_id where resource_id is not null";
        $rate_table = $this->Gatewaygroup->query($sql);

        $rate_table_new = array();
        foreach ($rate_table as $value) {
            $new_key = $value[0]['resource_id'];
            if (key_exists($new_key, $rate_table_new)) {
                $rate_table_new[$new_key]['rate_table_id'] .= "," . $value[0]['rate_table_id'];
            } else {
                $rate_table_new[$new_key]['rate_table_id'] = $value[0]['rate_table_id'];
            }
            $rate_table_new[$new_key]['name'][] = $value[0]['name'];
        }
        $this->set('rate_table', $rate_table_new);

        $this->set('type', 12);


        $arr = array();
        if ($this->params['getUrl']) {
            $arr = explode('&', $this->params['getUrl']);
            foreach ($arr as $k => $item) {

                if (strpos($item, 'size') !== false)
                    unset($arr[$k]);
            }
        }

        $page_size = isset($_GET['size']) ? $_GET['size'] : 100;

        $this->set('page_size', $page_size);
        $get_url = empty($arr) ? '' : implode('&', $arr);

        $this->set('get_url', $get_url);
    }

    public function rate_tables($resource_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->loadModel('ResourcePrefix');
        $resources = $this->ResourcePrefix->get_list_by_resource($resource_id);
        //die(var_dump($resources));


        //$this->loadModel('RateEmailTemplate');
        $this->loadModel('Mailtmp');
        $rate_email_template = $this->Mailtmp->find_all_rate_email_template(__('Save as new template', true));
        $rate_email_template['save_temporary'] = __('Do Not Use Template', true);
        $this->set('rate_email_template', $rate_email_template);

        //$payments = $this->Invoice->get_client_payments($invoice['Invoice']['client_id'], $create_type);
        $this->set('resources', $resources);
        $this->set('resource_id', $resource_id);
        //$this->set('create_type', $create_type);
    }

    public function send_rate($resource_id)
    {
        if (!$resource_id) {
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }
        $this->loadModel('ResourcePrefix');
        $resource_id = base64_decode($resource_id);
        $resources = $this->ResourcePrefix->get_list_by_resource($resource_id);
        if (empty($resources)) {
            $this->Gatewaygroup->create_json_array('', 101, "Does not exist assigned Rate Table!");
            $this->Session->write("m", Gatewaygroup::set_validator());
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }

        $this->loadModel('Rate');
        $this->loadModel('RateEmailTemplate');
        $this->loadModel('Mailtmp');
        $this->loadModel('RateTable');

        foreach ($resources as $resource) {
            $ids[] = $resource['RateTable']['rate_table_id'];
            $rate_table_names[$resource['RateTable']['rate_table_id']] = $resource['RateTable']['name'];
        }

        foreach ($ids as $id) {
            $mail_arrays[$id] = $this->Rate->get_client_email_by_ratetable($id);

            $arr = array();
            foreach ($mail_arrays[$id] as $key => $mail_item) {
                $send_mail = $mail_item[0]['rate_email'];
                if (empty($mail_item[0]['rate_email']))
                    $send_mail = $mail_item[0]['email'];
                $mail_arrays[$id][$key][0]['send_mail'] = $send_mail;
                $mail_arrays[$id][$key][0]['active'] = $mail_arrays[$id][$key][0]['active'] ? 'Yes' : 'No';
                if (!$send_mail)
                    continue;
                if (!in_array($send_mail, $arr))
                    $arr[] = $send_mail;
            }

            if ($id) {
                $sql = "SELECT jur_type FROM rate_table WHERE rate_table_id = {$id}";
                $rate_infos[] = $this->Rate->query($sql);
            }
        }


        $rate_email_template = $this->Mailtmp->find_all_rate_email_template(__('Save as new template', true));
        $rate_email_template['save_temporary'] = __('Do Not Use Template', true);
        $this->set('rate_email_template', $rate_email_template);
        $this->set('rate_table_names', $rate_table_names);
        $this->set('rate_table_ids', $ids);
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
        $schema = $this->RateTable->get_schema($rate_infos[0][0][0]['jur_type']);
        $options = array();
        $default_fields = array();
        foreach ($schema as $field_name => $value) {
            $options[$field_name] = isset($value['name']) ? Inflector::humanize($value['name']) : Inflector::humanize($field_name);
            if (isset($value['default_fields']))
                $default_fields[] = $field_name;
        }
        $this->set('schema', $options);
        $this->set('default_fields', $default_fields);
        $this->set('use_infos', $mail_arrays);
    }

    public function send_rate_records()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $post_data = $this->params['form'];

        $download_method = isset($_POST['download_method']) ? $_POST['download_method'] : 1;
        $rate_table_ids = $post_data['rate_table_ids'];
        $email_templates = $post_data['email_template'];
        $headers = $this->data['headers'];
        // set default headers if empty
        if (!$headers) {
            $headers = ['code', 'code_name', 'country', 'effective_date', 'rate'];
            $headers = implode(',', $headers);
        }
        $extra_params = [
            'content' => base64_encode($this->data['content']),
            'subject' => base64_encode($this->data['subject']),
            'email_cc' => $this->data['email_cc'],
            'headers' => $headers
        ];
        $extra_params = escapeshellarg(serialize($extra_params));

        $this->loadModel('Rate');
        if (empty($this->data['content']) || empty($this->data['subject'])) {
            $this->Rate->create_json_array('', 101, __('The information should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            //$this->redirect("/rates/send_rate/".base64_decode($rate_table_id) ."/$route_wizard_id");
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }

        if (!$this->isnotEmpty($post_data, array('format', 'rate_table_ids'))) {
            $this->Rate->create_json_array('', 101, __('The keyword not found!', true));
            $this->Session->write('m', Rate::set_validator());
            //$this->redirect("/rates/send_rate/".base64_decode($rate_table_id) ."/$route_wizard_id");
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }

        if (($_POST['data']['download_method'] == 2) && (empty($_POST['download_deadline']))) {
            $this->Rate->create_json_array('', 101, __('The Field download_deadline should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            //$this->redirect("/rates/send_rate/".base64_decode($rate_table_id) ."/$route_wizard_id");
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }

        $flg_zip = 0;
        if (isset($post_data['zipped'])) {
            $flg_zip = 1;
        }
        $start_effective_date = isset($post_data['start_effective_date']) && $post_data['start_effective_date'] ? $post_data['start_effective_date'] : date('Y-m-d', strtotime('+1 day'));
        $download_deadline = isset($post_data['download_deadline']) ? $post_data['download_deadline'] : '';
        $is_email_alert = isset($post_data['is_email_alert']) ? true : false;
        $is_disable = isset($post_data['is_disable']) ? true : false;

        $send_type = $this->_post('send_type');
        $new_email_template_arr = $this->data;
        $new_email_template_arr['name'] = "rate_email_template" . time();

        $headers = implode(",", $post_data['datams2side__dx']);
        $new_email_template_arr['headers'] = $headers;

        $this->loadModel('RateEmailTemplate');
        $flg = $this->RateEmailTemplate->save($new_email_template_arr);
        $email_template = $this->RateEmailTemplate->getLastInsertID();
        if ($flg === false) {
            $this->Rate->create_json_array('', 101, __('Failed', true));
            $this->Session->write('m', Rate::set_validator());
            //$this->redirect("/rates/rates_list");
            $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
        }
        if ($send_type) {
//            保存client的email
            $this->loadModel('Client');
            foreach ($rate_table_ids as $id) {
                $client_info_save_arr = array();
                foreach ($post_data['client_info'][$id]['client_id'] as $key_item => $client_info_id) {
                    $rate_email = $post_data['client_info'][$id]['rate_email'][$key_item];
                    if ($rate_email) {
                        $client_info_save_arr[$client_info_id] = array(
                            'client_id' => $client_info_id,
                            'rate_email' => $rate_email
                        );
                    }
                }
                sort($client_info_save_arr);
                $this->Client->saveAll($client_info_save_arr);
            }
        }

        $this->loadModel('RateSendLog');
        Configure::write('debug', 2);
        foreach ($rate_table_ids as $id) {

            $send_specify_email = isset($post_data['send_specify_email']) && $post_data['send_specify_email'] ? $post_data['send_specify_email'] : '';
            if (!$send_specify_email) {
                // get client emails
                $client_emails = [];
                foreach ($post_data['client_emails'][$id] as $res_email) {
                    $res_email_info = explode('::', $res_email);
                    if (!empty($res_email_info) && in_array($res_email_info[0], $post_data['resource_id'][$id])) {
                        $client_emails[] = $res_email_info[1];
                    }
                }
                $send_specify_email = $send_specify_email ?: implode(';', array_unique($client_emails));
            }


            $resource_id_unique = array_unique($post_data['resource_id'][$id]);
            // only one resource when sending to own recipient
            if ($send_type) {
                $resource_id_unique = [$resource_id_unique[0]];
            }

            $format = $post_data['format'];
            $rate_table_id = $id;


            $RateSendLogArr = array(
                'rate_table_id' => $rate_table_id,
                'format' => $format,
                'zip' => $flg_zip,
                'status' => 1,
                'email_template_id' => $email_template,
                'create_time' => date('Y-m-d H:i:sO'),
                'start_effective_date' => $start_effective_date,
                'download_deadline' => $download_deadline,
                'download_method' => $download_method,
                'is_email_alert' => $is_email_alert,
                'is_disable' => $is_disable,
                'is_temp' => false,
                'headers' => $headers,
                'send_type' => $send_type,
                'send_specify_email' => $send_specify_email,
                'resource_ids' => implode(',', $resource_id_unique),
                'sent_area' => 3
            );
            $this->RateSendLog->create();
            $insert_flg = $this->RateSendLog->save($RateSendLogArr);
            if ($insert_flg === false) {
                $this->Rate->create_json_array('', 101, __('Insert log failed!', true));
                $this->Session->write('m', Rate::set_validator());
                $this->redirect(array('plugin' => 'prresource', 'action' => 'view_ingress'));
            }
            $log_id = $this->RateSendLog->getLastInsertID();
            $cmd = APP . "../cake/console/cake.php ratesend {$log_id} $download_method $extra_params";
            $info = $this->Systemparam->find('first', array(
                'fields' => array('cmd_debug'),
            ));
            if (Configure::read('cmd_debug')) {
                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
            }
            shell_exec($cmd);
        }
        $this->Rate->create_json_array('', 201, __('The emails are sent in!', true));
        $this->Session->write('m', Rate::set_validator());
        /*if($ereturn_url){
            $this->redirect(base64_decode($ereturn_url).'_send_rate_log');
        }*/
        $this->redirect("/rates/send_rate_log");
    }

    public function get_schema_action()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_direction.resource_id)',
            'time_profile_name' => '(select name from time_profile where time_profile_id = resource_direction.time_profile_id)',
            'target' => "(case type when 0 then 'ani' else 'dnis' end)",
            'code' => 'dnis',
            'action' => "(case action when 1 then 'add_prefix' when 2 then 'add_suffix' when 3 then 'del_prefix' when 4 then 'del_suffix' end)",
            'chars' => 'digits',
            'number_type' => "(case number_type when 0 then 'all' when 1 then '>' when 2 then '=' when 3 then '<' end)",
            'number_length' => 'number_length',
        );

        return $fields;
    }

    function dis_able()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = base64_decode($this->params ['pass'] [0]);
        $page = $this->params ['pass'] [1];
        $this->Gatewaygroup->dis_able($id);
        //$this->Gatewaygroup->log('disable_trunk');
        $this->redirect(array('plugin' => 'prresource', 'action' => $page));
    }

    function active()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = base64_decode($this->params ['pass'] [0]);
        $page = $this->params ['pass'] [1];

        $this->Gatewaygroup->active($id);
        //$this->Gatewaygroup->log('active_trunk');
        $this->redirect(array('plugin' => 'prresource', 'action' => $page));
    }

    /**
     *
     *
     * @param unknown_type $id
     */
    function delete($id)
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $sql = "SELECT alias FROM resource WHERE resource_id = {$id}";
        $data = $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->logging(1, 'Trunk', "Trunk Name:{$data[0][0]['alias']}");
        $this->Gatewaygroup->delete($id);
        //$this->Gatewaygroup->log('delete_trunk');
        $this->Session->setFlash('');
        $this->redirect(array('action' => 'view'));
    }

    //电路使用报表---这个报表显示每一个落地网关的电路使用情况。
    function egress_report()
    {
        $this->loadModel('Resource');
        $this->Resource->recursive = 2;
        $lists = $this->Resource->findAll();
        $this->set('lists', $lists);
    }

    /**
     * 查询ip
     */
    public function ajax_host_report()
    {
        Configure::write('debug', 2);
        $res_id = $this->params ['pass'] [0];
        $this->set('extensionBeans', $this->Gatewaygroup->query("select  ip ,fqdn,port , use_cnt from resource_ip 
		left join (select count(* ) as use_cnt,  egress_id,callee_ip_address from real_cdr   group by egress_id  ,callee_ip_address )  a  
	 	on  a.egress_id=resource_id::text and a.callee_ip_address::text=resource_ip.ip::text
		where resource_id=$res_id   order  by use_cnt "));
    }

    private function _get_product_arr($only_public = false, $client_id = false, $returnData = false)
    {
        if ($this->Session->read('login_type') == 3 || $client_id) {
            $self_client_id = $client_id ?: $this->Session->read('sst_client_id');
            $sql = <<<SQL
SELECT product_name, id, tech_prefix,rate_table_id,route_strategy_id,is_private from product_route_rate_table WHERE is_private = false
UNION ALL SELECT product_name, id, tech_prefix,rate_table_id,route_strategy_id,is_private FROM product_route_rate_table
 WHERE EXISTS (SELECT 1 FROM product_clients_ref WHERE client_id = $self_client_id AND product_id = product_route_rate_table.id)
 ORDER BY product_name ASC
SQL;
        } elseif ($only_public) {
            $sql = "select product_name, id, tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table WHERE is_private = false  ORDER by product_name ASC ";
        } else {
            $sql = "select product_name, id, tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table ORDER by product_name ASC ";
        }

        $items = $this->Gatewaygroup->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        foreach ($items as $item) {
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'route_strategy_id' => $item[0]['route_strategy_id'],
                'rate_table_id' => $item[0]['rate_table_id'],
                'tech_prefix' => $item[0]['tech_prefix']

            );

            $product_arr[$item[0]['id']] = $arr;
        }

        if ($returnData) {
            return $product_arr;
        }

        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);
    }

    /**
     * Taken from: http://stackoverflow.com/questions/4356289/php-random-string-generator
     * @param int $length
     * @return string
     */
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function getClientProducts($clientId)
    {
        $this->autoRender = false;
        $this->layout = false;
        Configure::write('debug', 0);

        $products = $this->_get_product_arr(false, $clientId, true);

        echo json_encode($products);
        exit;
    }

    /**
     *
     * 添加 ingress
     *
     *
     */
    function add_resouce_ingress($carrier_id = '')
    {
        $login_type = $this->Session->read('login_type');
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'] || ($login_type == 3 && !Configure::read('portal.add_ingress'))) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add Ingress Trunk";
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        $is_enable_type = Configure::read('system.enable_trunk_type');
        $this->set('is_enable_type', $is_enable_type);
        $this->loadModel('ResourceTemplate');
        $this->set('have_template', $this->ResourceTemplate->find('count', array('conditions' => array('trunk_type' => 0))));
        $this->set('back_url', $this->params['getUrl']);

        // only public and assigned private products
        $client_id = $this->_get('query.id_clients');
        if (isset($client_id) && $client_id) {
            $this->_get_product_arr(true, $client_id);
        } else {
            $this->_get_product_arr(true);
        }

        if ($client_id) {

            $sql = "select name, login from client where client_id = $client_id";
            $client_name = $this->Gatewaygroup->query($sql);
            $this->set('client_name', $client_name[0][0]['name']);
            if ($client_name[0][0]['login']) {
                $this->loadModel('Signup');
                $client_ip_info = $this->Signup->get_client_ip_info($client_name[0][0]['login']);
                $this->set('hosts', $client_ip_info);
            }

        }

        if ($carrier_id && Configure::read('portal.add_ingress')) {
            $sql = "select name from client where client_id = $carrier_id";
            $client_name = $this->Gatewaygroup->query($sql);
            $client_name = $client_name[0][0]['name'];
            $this->set('portal_client_id', $carrier_id);
            $this->set('portal_client_name', $client_name);
        }

        //post 请求
        if (!empty($this->data ['Gatewaygroup'])) {
            $this->data['Gatewaygroup']['t38'] = true;
            if (!$this->judge_name($this->data['Gatewaygroup']['alias'], 100)) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '101', 'Ingress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/add_resouce_ingress");
            }

            if ($carrier_id) {
                //  $this->data['Gatewaygroup']['alias'] = $this->generateRandomString(10);
                // $_POST['data']['Gatewaygroup']['alias'] = $this->generateRandomString(10);
                $this->data['Gatewaygroup']['client_id'] = $carrier_id;
                $_POST['data']['Gatewaygroup']['client_id'] = $carrier_id;
            }
            $ips = array_keys_value($this->params, 'form.accounts');
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, $ips, false, true);
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('ips', $ips);
            //添加fail
            $this->set('post', $this->data);
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->init_info();
            } else {
//                //cid blocking
//                if ($this->data['Gatewaygroup']['enfource_cid']) {
//                    $this->addRule($this->data['Gatewaygroup'], $resource_id);
//                }

                //添加成功
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->set('gress', ingress);
                //$this->Gatewaygroup->log('add_ingress_trunk');
                $this->Gatewaygroup->create_json_array('', 201, __('The Ingress Trunk[%s] is added successfully.', true, $this->data ['Gatewaygroup'] ['alias']));
                $this->Session->write("m", Gatewaygroup::set_validator());

                //portal
                if (isset($_POST['data']['Gatewaygroup']['portal_carrier']) || $carrier_id) {
                    $this->redirect("/clients/view_ingress");
                }

                if ($this->_get('viewtype') == 'wizard') {
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange') {
                        $this->xredirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    } else {
                        $this->xredirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                } else {
                    $this->redirect("/prresource/gatewaygroups/view_ingress?" . $this->data['Gatewaygroup']['back_url']);
                }
            }
        } else {
            $this->set('post', $this->data);
            $is_enable_type = Configure::read('system.enable_trunk_type');
            $this->set('is_enable_type', $is_enable_type);
            //get request
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('transation_fees', $this->Gatewaygroup->find_transation_fee());
            $this->init_info();
        }

        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates(1, 0);
        $rate_t = [];
        foreach ($results->dataArray as $item) {
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

    }

    private function check_if_exists($ip_addresses, $resource_id = false)
    {
        $ip_addresses_str = "'" . implode("','", $ip_addresses) . "'";
        $sql = "SELECT resource_ip.resource_id, resource.alias, resource_ip.ip  from resource_ip left join resource on resource.resource_id = resource_ip.resource_id 
                left join client on client.client_id = resource.client_id where ip in ($ip_addresses_str) AND client.client_type is NULL AND resource.ingress=true";
        if ($resource_id) {
            $sql .= " AND resource.resource_id<>$resource_id";
        }
        $result = $this->Gatewaygroup->query($sql);
        if (!empty($result)) {
            return $result[0][0];
        }
        return false;
    }

    /**
     *
     *
     * 添加egress
     *
     */
    function add_resouce_egress($carrier_id = '')
    {
        $this->set('title', 'Egress Trunk');
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        $is_enable_type = Configure::read('system.enable_trunk_type');
        $this->set('is_enable_type', $is_enable_type);
        $this->set('is_egress', true);
        $this->loadModel('ResourceTemplate');
        $this->set('have_template', $this->ResourceTemplate->find('count', array('conditions' => array('trunk_type' => 1))));
        $this->set('back_url', $this->params['getUrl']);

        $client_id = $this->_get('query.id_clients');
        if ($client_id) {
            $sql = "select name from client where client_id = $client_id";
            $client_name = $this->Gatewaygroup->query($sql);
            $this->set('client_name', $client_name[0][0]['name']);
        }

        if ($carrier_id && Configure::read('portal.add_egress')) {
            $sql = "select name from client where client_id = $carrier_id";
            $client_name = $this->Gatewaygroup->query($sql);
            $client_name = $client_name[0][0]['name'];
            $this->set('portal_client_id', $carrier_id);
            $this->set('portal_client_name', $client_name);
        }
        if (!empty($this->data ['Gatewaygroup'])) {
            $this->data['Gatewaygroup']['t38'] = true;

            if (!$this->judge_name($this->data['Gatewaygroup']['alias'], 100)) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '101', 'Egress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/add_resouce_egress");
            }
            //如果没有选择 rate table 则默认选择一个
//            if (empty($this->data ['Gatewaygroup']['rate_table_id']))
//            {
//                $rate_table_list = $this->Gatewaygroup->query("SELECT rate_table_id,name FROM rate_table  ORDER  BY  name ASC");
//                if ($rate_table_list)
//                {
//                    $this->data ['Gatewaygroup']['rate_table_id'] = $rate_table_list[0][0]['rate_table_id'];
//                }
//            }
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'), false, true);
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
//                //cid blocking
//                if ($this->data['Gatewaygroup']['enfource_cid']) {
//                    $this->addRule($this->data['Gatewaygroup'], $resource_id);
//                }

                //$this->Gatewaygroup->log('add_egress_trunk');
                //portal
                if (isset($_POST['data']['Gatewaygroup']['portal_carrier'])) {
                    $this->redirect("/clients/view_egress");
                }

                if ($this->_get('viewtype') == 'wizard') {
                    $this->Gatewaygroup->create_json_array('', 201, __('The Egress[%s] is created successfully.', true, $this->data['Gatewaygroup']['alias']));
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange') {
                        $this->redirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    } else {
                        $this->redirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                } else {
                    $this->Gatewaygroup->create_json_array('', 201, __('The Egress[%s] is created successfully.', true, $this->data['Gatewaygroup']['alias']));
                    $this->Session->write("m", Gatewaygroup::set_validator());
                    $this->redirect("/prresource/gatewaygroups/view_egress?" . $this->data['Gatewaygroup']['back_url']);
                }
            }
        } else {
            $this->init_info();
            $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
        }

        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates(1, 0);
        $rate_t = [];
        foreach ($results->dataArray as $item) {
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

    }

    private function addRule($data, $res_id)
    {
        $this->loadModel('AlertRules');

        $save_data = [];
        $save_data['auto_define'] = true;
        $save_data['res_id'] = $res_id;
        $save_data['rule_name'] = $data['alias'] . '_' . date("Y-m-d_H_i_s");
        $save_data['trunk_type'] = ($data['egress'] == 'true') ? 2 : 1;
        $save_data['all_trunk'] = false;

        $save_data['asr_value'] = $data['cid_min_asr'];
        $save_data['acd_value'] = $data['cid_min_acd'];
        $save_data['asr'] = '>';
        $save_data['acd'] = '>';

        $save_data['active'] = true;
        $save_data['sample_size'] = 60;
        $save_data['execution_schedule'] = 1;
        $save_data['specific_minutes'] = 10;
        $save_data['is_block'] = true;
        $save_data['is_email'] = false;
        $save_data['update_by'] = $_SESSION['sst_user_name'];
        $save_data['update_at'] = date("Y-m-d H:i:sO");

        $this->AlertRules->save($save_data);
    }

    /**
     *
     *
     * 修改ingress
     * @param unknown_type $res_id
     */
    function edit_resouce_ingress($res_id = null)
    {
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        $is_enable_type = Configure::read('system.enable_trunk_type');
        $this->set('is_enable_type', $is_enable_type);
        $resource_id = base64_decode($res_id);
        $this->set('resource_id', $resource_id);

        // only public and assigned private products
        $client_id = $this->_get('query.id_clients');
        if (!$client_id) {
            $client_arr = $this->Gatewaygroup->query("SELECT client_id FROM resource WHERE resource_id = {$resource_id}");
            if (!empty($client_arr)) {
                $client_id = $client_arr[0][0]['client_id'];
            }
        }

        if ($_SESSION['login_type']) {
            $this->_get_product_arr();
        } elseif ((isset($client_id) && $client_id)) {
            $this->_get_product_arr(false, $client_id);
        } else {
            $this->_get_product_arr(true);
        }

        if (!empty($this->data ['Gatewaygroup'])) {
            if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                $this->redirect_denied();
            }

            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'), false, true);

            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息

                $resource_id = $_POST['data']['Gatewaygroup']['resource_id'];
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data['Gatewaygroup']['alias']);
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                $this->set('post', $this->data);
                $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
                $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
                $this->set('resouce_list', $this->Gatewaygroup->find_resource());
                $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));


                $this->init_codes($resource_id);
                $this->init_info();
                $this->Session->write("m", Gatewaygroup::set_validator());
//                $this->redirect("/prresource/gatewaygroups/view_ingress");
                $this->redirect("/prresource/gatewaygroups/edit_resouce_ingress/" . base64_encode($resource_id) . "/ingress");
            } else {

//                //cid blocking
//                $enforce_cid = ($this->data['Gatewaygroup']['enfource_cid'] === 'true');
//                if (!$enforce_cid) {
//                    // delete
//                    $this->Gatewaygroup->query("delete from  alert_rules  where auto_define=true AND res_id='{$resource_id}'");
//                }else{
//                    // save or update
//                    if(!empty($this->Gatewaygroup->getRuleData($resource_id))) {
//                        $asr_value = $this->data['Gatewaygroup']['cid_min_asr'] ?: 0;
//                        $acd_value = $this->data['Gatewaygroup']['cid_min_acd'] ?: 0;
//                        $this->Gatewaygroup->query("update alert_rules set asr_value='{$asr_value}',acd_value='{$acd_value}' where auto_define=true AND res_id='{$resource_id}'");
//                    }else{
//                        $this->addRule($this->data['Gatewaygroup'], $resource_id);
//                    }
//                }

                $resource_id = base64_decode($this->get_param_pass(0));
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data['Gatewaygroup']['alias']);
                ////$this->Gatewaygroup->log('edit_ingress_trunk');
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, __('The Ingress Trunk [%s] is modified successfully!', true, $this->data['Gatewaygroup'] ['alias']));
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                /* $ignore_ring_early_media = $this->data['Gatewaygroup']['ignore_ring'] == 't' ?
                  ($this->data['Gatewaygroup']['ignore_early_media'] == 't' ? 1 : 2 ) : ($this->data['Gatewaygroup']['ignore_early_media'] == 't' ? 3 : 0);
                  $this->data['Gatewaygroup']['ignore_ring_early_media'] = $ignore_ring_early_media; */
                $this->set('post', $this->data);

                $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
                $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
                $this->set('resouce_list', $this->Gatewaygroup->find_resource());
                $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));


                $this->init_codes($resource_id);
                $this->init_info();
                $this->Session->write("mm", 2);
                $back_get_url = $this->_post('back_get_url');
                $this->redirect("/prresource/gatewaygroups/view_ingress?" . $back_get_url);
//                $this->redirect("/prresource/gatewaygroups/edit_resouce_ingress/" . base64_encode($resource_id) . "/ingress");
            }
        } else {
            //get  request
            if (!empty($this->params ['pass'][0])) {
                $resource_id = array_keys_value($this->params, 'pass.0');
                $resource_id = base64_decode($resource_id);
            } else {
                $this->redirect("/homes/bad_url/");
            }
            $this->init_info();
            $this->init_codes($resource_id);
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data ['Gatewaygroup'] = $tmp1 [0] [0];
            $ignore_ring_early_media = $tmp1 [0] [0]['ignore_ring'] == 't' ?
                ($tmp1 [0] [0]['ignore_early_media'] == 't' ? 1 : 2) : ($tmp1 [0] [0]['ignore_early_media'] == 't' ? 3 : 0);
            $data['Gatewaygroup']['ignore_ring_early_media'] = $ignore_ring_early_media;
            if ($data ['Gatewaygroup']['enfource_cid']) {
                $rule = $this->Gatewaygroup->getRuleData($resource_id);
                $data ['Gatewaygroup'] = !empty($rule) ? array_merge($data ['Gatewaygroup'], $rule) : $data ['Gatewaygroup'];
            }
            $this->set('post', $data);
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('transation_fees', $this->Gatewaygroup->find_transation_fee());
            $res_id = base64_decode($res_id);
            $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($res_id));
            $client_id = $data['Gatewaygroup']['client_id'];
            $sql = "select client_id,cps_limit,call_limit from client where client_id = {$client_id}";
            $limit_data = $this->Client->query($sql, false);
            $this->set('limit_data', $limit_data[0][0]);
        }


        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates(1, 0);
        $rate_t = [];
        foreach ($results->dataArray as $item) {
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

//        $hosts = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register,request_auth,
//            option_active,option_retry,option_interval,
// COALESCE((select state from register_of_record where username=resource_ip.username order by id desc limit 1), 0) as registered,
// (select expires_time from register_of_record where username=resource_ip.username order by id desc limit 1) as expires_time
//from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $hosts = $this->Gatewaygroup->query("select resource_id,mask,resource_ip_id,ip,port,fqdn,username,password,reg_status,reg_type,reg_srv_ip,reg_srv_port,profile_id,
 COALESCE((select state from register_of_record where username=resource_ip.username order by id desc limit 1), 0) as registered,
 expires,need_register
from  resource_ip where resource_id=$resource_id AND masked_from IS NULL order by resource_ip_id asc");
        $this->set('hosts', $hosts);
    }

    function edit_resouce_egress()
    {
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        $this->set('is_egress', true);
        if (!empty($this->data ['Gatewaygroup'])) {
            //如果没有选择 rate table 则默认选择一个
//            if (empty($this->data ['Gatewaygroup']['rate_table_id']))
//            {
//                $rate_table_list = $this->Gatewaygroup->query("SELECT rate_table_id,name FROM rate_table  ORDER  BY  name ASC");
//                if ($rate_table_list)
//                {
//                    $this->data ['Gatewaygroup']['rate_table_id'] = $rate_table_list[0][0]['rate_table_id'];
//                }
//            }

            if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                $this->redirect_denied();
            }
            if (!$this->judge_name($this->data['Gatewaygroup']['alias'], 100)) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '101', 'Egress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/edit_resouce_egress/" . base64_encode($this->data['Gatewaygroup']['resource_id']) . "/");
            }
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'), false, true);
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/edit_resouce_egress/" . base64_encode($this->data['Gatewaygroup']['resource_id']) . "/");

            } else {
//                //cid blocking
//                $enforce_cid = ($this->data['Gatewaygroup']['enfource_cid'] === 'true');
//                if (!$enforce_cid) {
//                    // delete
//                    $this->Gatewaygroup->query("delete from  alert_rules  where auto_define=true AND res_id='{$resource_id}'");
//                }else{
//                    // save or update
//                    if(!empty($this->Gatewaygroup->getRuleData($resource_id))) {
//                        $asr_value = $this->data['Gatewaygroup']['cid_min_asr'] ?: 0;
//                        $acd_value = $this->data['Gatewaygroup']['cid_min_acd'] ?: 0;
//                        $this->Gatewaygroup->query("update alert_rules set asr_value='{$asr_value}',acd_value='{$acd_value}' where auto_define=true AND res_id='{$resource_id}'");
//                    }else{
//                        $this->addRule($this->data['Gatewaygroup'], $resource_id);
//                    }
//                }

                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                $this->set('post', $this->data);
                $this->init_info();
                ////$this->Gatewaygroup->log('edit_egress_trunk');
                $this->Gatewaygroup->create_json_array('', 201, __('The Egress Trunk [%s] is modified successfully !', true, $this->data['Gatewaygroup'] ['alias']));
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->Session->write("mm", 2);
                $back_get_url = $this->_post('back_get_url');
                $this->redirect("/prresource/gatewaygroups/view_egress?" . $back_get_url);
            }
        } else {
            $resource_id = base64_decode($this->get_param_pass(0));
            $this->init_info();
            $this->init_codes($resource_id);
            $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());

            $profiles = $this->Gatewaygroup->get_profiles($resource_id);
            $this->set('sip_profiles', $profiles);
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data ['Gatewaygroup'] = $tmp1 [0] [0];

            if ($data ['Gatewaygroup']['enfource_cid']) {
                $rule = $this->Gatewaygroup->getRuleData($resource_id);
                $data ['Gatewaygroup'] = !empty($rule) ? array_merge($data ['Gatewaygroup'], $rule) : $data ['Gatewaygroup'];
            }
            $this->set('post', $data);
            $client_id = $data['Gatewaygroup']['client_id'];
            $sql = "select client_id,cps_limit,call_limit from client where client_id = {$client_id}";
            $limit_data = $this->Client->query($sql, false);
            $this->set('limit_data', $limit_data[0][0]);
        }

        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates(1, 0);
        $rate_t = [];
        foreach ($results->dataArray as $item) {
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

//        $hosts = $this->Gatewaygroup->query("select option_active,option_retry,option_interval,resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register,register_server_ip,register_server_port,request_auth,"
//            . "COALESCE((select state from register_of_record where username=resource_ip.username order by id desc limit 1), 0) as registered ,"
//            . "expires from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $hosts = $this->Gatewaygroup->query("select resource_id,mask,resource_ip_id,ip,port,fqdn,username,password,reg_status,reg_type,reg_srv_ip,reg_srv_port,profile_id,options_ping_inv,"
            . "COALESCE((select state from register_of_record where username=resource_ip.username order by id desc limit 1), 0) as registered ,"
            . "expires,need_register from  resource_ip where resource_id=$resource_id AND masked_from IS NULL order by resource_ip_id asc");
        $this->set('hosts', $hosts);
        $this->init_codes($resource_id);
    }


    function edit_egress_ingress_limit()
    {
        $resource_id = base64_decode($this->get_param_pass(0));

        $this->Gatewaygroup->resource_id = $resource_id;
        $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
        $data ['Gatewaygroup'] = $tmp1 [0] [0];
        $this->set('post', $data);
        $this->set('type', $data['Gatewaygroup']['egress'] ? 1 : 2);
        $list = $this->Gatewaygroup->query("select alias as name, (select name FROM client WHERE client_id = resource.client_id) as client_name from resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['name']);
        $this->set('client_name', $list[0][0]['client_name']);

        if (!empty($this->data ['Gatewaygroup'])) {
            $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, '', true);

            $this->Gatewaygroup->create_json_array('', 201, __('The Trunk [%s] is modified successfully !', true, $this->data['Gatewaygroup'] ['alias']));
            $this->Session->write("m", Gatewaygroup::set_validator());
            $this->Session->write("mm", 2);
            if ($data['Gatewaygroup']['egress']) {
                $this->redirect("/prresource/gatewaygroups/view_egress");
            } else {
                $this->redirect("/prresource/gatewaygroups/view_ingress");
            }
        }
    }

    function check_route_plan_error($resource_id, $route_plan_id)
    {
        #  find current  route plan prefix
        $is_repeat = false;
        //	$route_plan_id=147;
        $current_prefix_arr = array();
        $list = $this->Gatewaygroup->query(" select  digits from  route   where  route_strategy_id=$route_plan_id");
        if (isset($list[0][0]['digits']) && !empty($list[0][0]['digits'])) {
            foreach ($list as $key => $value) {
                $current_prefix_arr[$key] = $value[0]['digits'];
            }
        }


        #查找相同ip地址的ingress resource  route_plan 的前缀
        $ip_prefix_arr = array();
        //$resource_id=731;
        $list = $this->Gatewaygroup->query("
				select digits from route where route_strategy_id in ( 
				select  distinct  route_strategy_id from resource_ip 
				left join resource on resource.resource_id=resource_ip.resource_id 
				where ip  in (select distinct ip from resource_ip where resource_id=$resource_id) and  resource_ip.resource_id<>$resource_id
				and  route_strategy_id is not null
)
				  
				  ");
        if (isset($list[0][0]['digits']) && !empty($list[0][0]['digits'])) {
            foreach ($list as $key => $value) {
                $ip_prefix_arr[$key] = $value[0]['digits'];
            }
        }
        //	pr($ip_prefix_arr);
        #这里比较2个prefix数组
        foreach ($current_prefix_arr as $key => $value) {
            $prefix = $value;
            foreach ($ip_prefix_arr as $k2 => $v2) {
                $p2 = $v2;
                $list = $this->Gatewaygroup->query("select  '$prefix'::prefix_range		<@ '$p2'  as  t ;");
                if ($list[0][0]['t']) {
                    $is_repeat = true;
                    break;
                }
            }
        }

        return $is_repeat;
    }

    function add_host()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $this->init_info();
        $resource_id = $_SESSION ['resource_id'];
        $list = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $list = $this->Gatewaygroup->query("select alias,route_strategy_id from  resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['alias']);
        if (empty($_SESSION ['route_plan_id'])) {
            if (isset($list[0][0]['alias'])) {
                $_SESSION ['route_plan_id'] = $list[0][0]['route_strategy_id'];
            } else {
                $_SESSION ['route_plan_id'] = '';
            }
        }
        $_SESSION ['resource_name'] = $list[0][0]['alias'];
    }

    function add_rule($type)
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
        {
            //$this->redirect_denied();
        }
        $resource_id = base64_decode($this->get_param_pass(0));
        $type = $this->get_param_pass(1);
        $this->set_name_gress($resource_id);
        $list = $this->Gatewaygroup->query("select * from  resource_next_route_rule where resource_id=$resource_id    order by id");
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $this->set("type", $type);
    }

    function add_direction($resource_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $resource_id = base64_decode($resource_id);
        $resource = $this->Gatewaygroup->findByResourceId($resource_id);
        $title = $resource['Gatewaygroup']['ingress'] == 1 ? 'Ingress' : 'Egress';
        $list = $this->Gatewaygroup->query("select  direction_id,  time_profile_id,type,dnis,action,digits,number_length,number_type  from  resource_direction  where resource_id='$resource_id' order by direction_id");
        $this->set_name_gress($resource_id);
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $timepro = $this->Gatewaygroup->find_timeprofile();
        $timepro[''] = '';
        asort($timepro);
        $this->set('timepro', $timepro);
        $this->set('smaill_title', $title);
    }

    function add_lrn_action($resource_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $resource_id = base64_decode($this->get_param_pass(0));
        $this->set_name_gress($resource_id);
        $list = $this->Gatewaygroup->query("select  id,  direction,dnis,action,digits  from  resource_lrn_action  where resource_id='$resource_id' order by id");
        // $list = array();
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
    }

    public function add_rule_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
        {
            //$this->redirect_denied();
        }
        $res_id = $_POST ['resource_id'];
        $delete_rate_id = $_POST['delete_rate_id'];
        $addtype = $_POST['addtype'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
        $size = count($tmp);

        if (!empty($tmp))
        {
            foreach ($tmp as $el)
            {
                $model = new ResourceNextRouteRule;
                $this->data['ResourceNextRouteRule'] = $el;
                $this->data['ResourceNextRouteRule']['resource_id'] = $res_id;
                $model->save($this->data ['ResourceNextRouteRule']);
                $this->data['ResourceNextRouteRule']['id'] = false;
            }
        }
        if (!empty($delete_rate_id))
        {
            $this->ResourceNextRouteRule->query("delete  from  resource_next_route_rule where id in($delete_rate_id)");
            $this->ResourceNextRouteRule->create_json_array('#ClientOrigRateTableId', 201, __('Deleted Successfully!',true));
        }
        ////$this->Gatewaygroup->log('add_rule_report');
        if(empty($delete_rate_id)){
            $this->ResourceNextRouteRule->create_json_array('#ClientOrigRateTableId', 201, __('Added Successfully!',true));
        }
        $this->Session->write("m", ResourceNextRouteRule::set_validator());
        $this->redirect("/prresource/gatewaygroups/add_rule/" . base64_encode($res_id) . "/{$addtype}");
    }


    public function reset_failover($resource_id = NULL)
    {
        Configure::write('debug', 0);
        $resource_id = base64_decode($resource_id);
        $system_default = $this->Systemparam->query("select * from  global_failover order by id");

        foreach ($system_default as $el) {
            $this->data['ResourceNextRouteRule'][] = array(
                'route_type' => $el[0]['failover_strategy'],
                'reponse_code' => $el[0]['from_sip_code'],
                'return_code' => $el[0]['to_sip_code'],
                'return_string' => $el[0]['to_sip_string'],
                'resource_id' => $resource_id,
            );
        }
        if ($this->ResourceNextRouteRule->deleteAll(array('resource_id' => $resource_id))) {
            $flg = $this->ResourceNextRouteRule->saveAll($this->data['ResourceNextRouteRule']);
        }
        if ($flg) {
            $this->Systemparam->create_json_array('', 201, __('Succeed!', true));
        } else {
            $this->Systemparam->create_json_array('', 101, __('Failed!', true));
        }
        $this->Session->write("m", Systemparam::set_validator());
        $this->redirect("/prresource/gatewaygroups/add_rule/" . base64_encode($resource_id) . "/egress");
    }

    function add_lrn_action_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $direction = ($_POST['gress'] == 'ingress') ? '1' : '2';
        $res_id = $_POST ['resource_id'];

        if (isset($_POST['resource_id'])) {
            $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
            $arr = array();


            foreach ($tmp as $val) {
                array_push($arr, [$val['digits'], $val['dnis'], $val['direction'], $val['action']]);
            }
            $len1 = count($arr);
            $arr2 = array_unique($arr, SORT_REGULAR);
            $len2 = count($arr2);
            if ($len1 > $len2) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 301, 'Duplicated!');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/add_lrn_action/" . base64_encode($res_id));
            }

            $size = count($tmp);
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_lrn_action  where resource_id=$res_id");

            foreach ($tmp as $el) {
                $direction = $el ['direction'];
                $action = $el ['action'];
                $digits = $el ['digits'];
                $dnis = $el ['dnis'];
                $this->Gatewaygroup->query("insert into resource_lrn_action (direction,resource_id,dnis,action,digits)
					  values($direction,$res_id,'$dnis'::prefix_range,$action,'$digits')");
            }
            $this->Gatewaygroup->commit();
            ////$this->Gatewaygroup->log('add_lrn_action');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Succeeded');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->redirect("/prresource/gatewaygroups/add_lrn_action/" . base64_encode($res_id));
    }

    function del_lrn_action_post($resource_id = null, $id = null)
    {

        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }

        if (!empty($resource_id) && !empty($id)) {
            $this->Gatewaygroup->query("delete from  resource_lrn_action  where resource_id=" . $resource_id . " and id=" . $id);
        }

        $this->redirect("/prresource/gatewaygroups/add_lrn_action/" . base64_encode($resource_id));
    }

    function add_direction_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }//$this->print_rr($_POST);die;
        $res_id = $_POST ['resource_id'];
        if (isset($_POST['resource_id'])) {
            $list_gress = $this->Gatewaygroup->query("select egress,ingress  from  resource  where resource_id=$res_id");
            if (!empty($list_gress[0][0]['egress'])) {
                $direction = '2';
            } else {
                $direction = '1';
            }

            $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
            /*
              $size = count($tmp);
              $arr = array();
              if ($tmp) {
              foreach ($tmp as $value) {
              array_push($arr, $value['time_profile_id'] . $value['type'] . $value['dnis'] . $value['action'] . $value['digits'] . $value['num_type']);
              }
              }
              $len1 = count($arr);
              $arr2 = array_unique($arr);
              $len2 = count($arr2);
              if ($len1 > $len2) {
              $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 301, 'Actions cannot be duplicated.! ');
              $this->Session->write("m", Gatewaygroup::set_validator());
              $this->redirect("/prresource/gatewaygroups/add_direction/{$res_id}/");
              }
             *
             */
            $old_data = $this->Gatewaygroup->query("select direction_id, digits from resource_direction  where resource_id=$res_id");
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$res_id");
            $modif_log_id = "";
            if ($tmp) {
                foreach ($tmp as $el) {
                    $time_profile_id = isset($el ['time_profile_id']) ? $el ['time_profile_id'] : 'null';
                    $type = isset($el ['type']) ? $el ['type'] : '0';
                    $dnis = $el ['dnis'];
                    $action = isset($el ['action']) ? $el ['action'] : '1';
                    $digits = $el ['digits'];
                    if ($action == 3 || $action == 4) {
                        $digits = $el['deldigits'];
                    }
                    $number_type = isset($el ['number_type']) ? $el ['number_type'] : '0';
                    if (empty($time_profile_id)) {
                        $time_profile_id = 'null';
                    }


                    if ($number_type == '0') {
                        $data = "";
                        $data = $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)  
						  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',0,NULL) returning *");
                        if (!$data) {
                            $this->Gatewaygroup->rollback();
                            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 101, 'Failed');
                            $this->Session->write("m", Gatewaygroup::set_validator());
                            $this->redirect("/prresource/gatewaygroups/add_direction/" . base64_encode($res_id));
                        }
                    } else {
                        $number_length = $el ['number_length'] ? $el ['number_length'] : "0";
                        $insert_data = "";
                        $insert_data = $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)  
						  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',$number_type,$number_length) returning *");
                        echo "insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)  
						  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',$number_type,$number_length) returning *";
                        if (!$insert_data) {
                            $this->Gatewaygroup->rollback();
                            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 101, 'Failed');
                            $this->Session->write("m", Gatewaygroup::set_validator());
                            $this->redirect("/prresource/gatewaygroups/add_direction/" . base64_encode($res_id));
                        }
                    }


                    if ($action == 1) {
                        $old_prefix = "";
                        if ($el['id']) {
                            foreach ($old_data as $old_row) {
                                if ($old_row[0]['direction_id'] == $el['id']) {
                                    $old_prefix = $old_row[0]['digits'];
                                }
                            }
                        }

                        if (strcmp($old_prefix, $digits)) {
                            if (!empty($el['id'])) {
                                $modify = 3;
                            } else {
                                $modify = 4;
                            }
                            $modif_log_id .= $this->Gatewaygroup->log_trunk_modify($res_id, $modify, $old_prefix, $digits) . ',';

                        }
                    }

                }


            }


            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->send_modify_email($res_id, $modif_log_id);
            ////$this->Gatewaygroup->log('add_direct_action');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, __('Added successfully!', true));
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->redirect("/prresource/gatewaygroups/add_direction/" . base64_encode($res_id));
    }

    /**
     *
     *
     * post request
     */
    function add_host_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_id'])) {
            $res_id = $_POST ['resource_id'];
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id in (select resource_ip_id  from  resource_ip  where resource_id=$res_id)");
            $this->Gatewaygroup->query("delete from  resource_ip  where resource_id=$res_id");
            $tmp = isset($_POST ['accounts']) ? $_POST ['accounts'] : '';
            $checkip = true;
            if ($checkip === true) {
                $size = count($tmp);
                $i = 0;
                foreach ($tmp as $el) {
                    $ip = $el ['ip'];
                    $netmask = $el ['netmask'];
                    $port = $el ['port'];
                    /* $ip1 = "'" . $ip . "/" . $netmask . "'" . "::ip4r"; //普通ip
                      $ip2 = "'" . $ip . "/" . $netmask . "'"; //域名
                      $list=$this->Gatewaygroup->query ( " select  {$ip1}" );
                      if(empty($list)){
                      $this->Gatewaygroup->create_json_array ( '#ip-ip-'.($i+1), 101, 'Please fill IP field correctly (only IP allowed).' );
                      $this->Session->write ( "m", Gatewaygroup::set_validator () );
                      $this->redirect ( "/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&res_id={$res_id}" );
                      } */
                    $ip1 = null;
                    $ip2 = null;
                    $check = null;
                    if ($this->checkipaddres($ip)) {
                        $ip2 = $ip1 = "'" . $ip . "'";
                    } else {
                        $ip2 = "'" . $ip . "'";
                        $ip1 = 'null';
                    }

                    $check = $this->Gatewaygroup->query("select COUNT(*) as count_num  from resource_ip where resource_id = $res_id  and (fqdn=$ip2 OR IP =$ip1) and port=$port ");
                    if ($check[0][0]['count_num'] != 0) {//重复则返回
                        $this->Gatewaygroup->create_json_array('', 101, __('IP repeat', true));
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    }

                    $r = $this->Gatewaygroup->query("insert into resource_ip (ip,resource_id,fqdn,port)  
						  values($ip1,$res_id,$ip2,$port)");
                    if (!is_array($r)) {
                        $this->Gatewaygroup->rollback();
                        $this->Gatewaygroup->create_json_array('#ip-ip-' . ($i + 1), 201, 'Please fill IP field correctly (only IP allowed).');
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    }
                    $i++;
                }
                $this->Gatewaygroup->commit();

                #update  route plan

                if ($_GET['gress'] == 'ingress') {
                    $route_plan_id = $_POST['route_plan_id'];
                    $this->Session->write("route_plan_id", $route_plan_id);
                    if ($this->check_route_plan_error($res_id, $route_plan_id)) {
                        $this->Gatewaygroup->create_json_array('#route_plan_id', 101, "The same ip address ingress resource can not choose the same or overlapping prefix of the route plan.");
                        $this->Session->write("m", Gatewaygroup::set_validator());
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    } else {

                        $this->Gatewaygroup->query("update  resource  set route_strategy_id=$route_plan_id  where  resource_id=$res_id");
                    }
                }
                ////$this->Gatewaygroup->log('add_trunk_host');
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add Success');
                $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
            } else {
                $this->Gatewaygroup->create_json_array('', 101, "IP:" . $checkip . "Already Exists");
            }
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
    }

    public function legalIP($res_id, $ips)
    {
        $old_ip = $this->Gatewaygroup->query("select * from resource_ip where resource_id = $res_id");
        $repeat_array = "";
        foreach ($ips as $el) {
            $_not_exists = true;
            for ($i = 0; $i < count($old_ip); $i++) {
                if ($old_ip[$i][0]['ip'] == $el['ip']) {
                    $_not_exists = false;
                }
            }
            if (!isset($el ['need_register'])) {
                if ($_not_exists) {
                    $qs = $this->Gatewaygroup->query("select resource_ip_id from resource_ip where ip = '{$el['ip']}'");
                    if (count($qs) > 0) {
                        $repeat_array = str_ireplace($el['ip'], '', $repeat_array);
                        $repeat_array .= $el['ip'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
            }
        }
        return !empty($repeat_array) ? $repeat_array : true;
    }

    function add_host_time()
    {

        if (!empty($this->params ['pass'])) {
            $res_ip_id = $this->params ['pass'] [0];
            $ip = $this->params ['pass'] [1];
            $_SESSION ['resource_ip'] = $ip;
            $_SESSION ['resource_ip_id'] = $res_ip_id;
        } else {
            $res_ip_id = $_SESSION ['resource_ip_id'];
            $ip = $_SESSION ['resource_ip'];
        }
        $list = $this->Gatewaygroup->query("select  limit_id,ip_id,cps,capacity ,time_profile_id   from   resource_ip_limit   where ip_id=$res_ip_id    order by limit_id");
        $this->set("resource_ip_id", $res_ip_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
    }

    /**
     *
     * 对接网关的主被叫转换规则 配置
     */
    function set_name_gress($resource_id)
    {

        $list = $this->Gatewaygroup->query("select ingress , egress, alias as name, (select name FROM client WHERE client_id = resource.client_id) as client_name from resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['name']);
        $this->set('client_name', $list[0][0]['client_name']);
        if ($list[0][0]['ingress']) {
            $this->set('gress', 'ingress');
        } else {
            $this->set('gress', 'egress');
        }
    }

    function add_translation_time($id = null)
    {

        $res_id = base64_decode($this->get_param_pass(0));
        $this->set_name_gress($res_id);
        $list = $this->Gatewaygroup->query("select  ref_id,resource_id,translation_id,time_profile_id    from   resource_translation_ref   where resource_id=$res_id    order by ref_id");
        $this->set("resource_id", $res_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
    }

    function view_did()
    {
        if (!empty($this->params ['pass'])) {
            $resource_id = $this->params ['pass'] [0];
            $_SESSION ['resource_id'] = $resource_id;
            $_SESSION ['gress'] = 'egress';
        } else {
            $resource_id = $_SESSION ['resource_id'];
        }

        $list = $this->Gatewaygroup->query("select  card_id,(select card_number from card where card_id = card_code_part.card_id) as account,id,did,card_sip_id ,sip_code,resource_id ,active  from   card_code_part where resource_id = $resource_id      order by id");
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_sipcode());
        $this->set('resource_id', $resource_id);
    }

    function active_did()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $t = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("update card_code_part set active=$t  where id=$id");
        if ($t == 'false') {
            $str = "DID已经被禁用";
        } else {
            $str = "DID已经被启动";
        }
        $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, $str);
        $this->Session->write("m", Gatewaygroup::set_validator());
        $this->redirect("/gatewaygroups/view_did/");
    }

    /**
     *
     *
     * class4配置
     */
    function add_server()
    {

        $list = $this->Gatewaygroup->query("select server_id,server_type,ip,enable_register from   server_platform    order by server_id");
        $this->set("host", $list);
    }

    function add_server_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['is_post'])) {
            $tmp = $_POST ['accounts'];
            //pr($tmp);
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  server_platform ");
            foreach ($tmp as $el) {
                $ip = $el ['ip'];
                $ip1 = "'" . $ip . "'::ip4r"; //普通ip
                $this->Gatewaygroup->query("insert into server_platform (ip)  
					  values('$ip')");
            }
            ////$this->Gatewaygroup->log('add_server_platform');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/add_server/");
    }

    function add_host_time_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_ip_id'])) {
            $res_ip = $_POST ['resource_ip_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id=$res_ip");
            foreach ($tmp as $el) {
                $time_profile_id = $el ['time_profile_id'];
                $cps = !empty($el ['cps']) ? $el ['cps'] : 'null';
                $capacity = !empty($el ['capacity']) ? $el ['capacity'] : 'null';

                $this->Gatewaygroup->query("insert into resource_ip_limit (ip_id,cps,capacity,time_profile_id)  
					  values($res_ip,$cps,$capacity,$time_profile_id)");
            }
            ////$this->Gatewaygroup->log('add_resource_ip_limit');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action  Success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/prresource/gatewaygroups/add_host_time/");
    }

    /**
     *
     * 配置 对接网关的主被叫转换规则
     *
     */
    function check_trans_time_profile($time_profile_id)
    {
        $list = $this->Gatewaygroup->query("select count(*)as c  from resource_translation_ref where time_profile_id=$time_profile_id");
        if (empty($list) || $list[0][0]['c'] == 0) {
            return 'true';
        } else {
            return 'false';
        }
    }

    function add_translation_time_post()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_id'])) {
            $res_id = $_POST ['resource_id'];
            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  resource_translation_ref  where resource_id=$res_id");
            foreach ($tmp as $el) {
                $time_profile_id = $el ['time_profile_id'];
                $translation_id = $el ['translation_id'];
                /* 				if($this->check_trans_time_profile($time_profile_id)=='false'){
                  $this->Gatewaygroup->create_json_array ( '#ClientOrigRateTableId', 301, 'time profile is  exisit' );
                  $this->Session->write ( "m", Gatewaygroup::set_validator () );
                  $this->redirect ( "/prresource/gatewaygroups/add_translation_time?gress={$_POST ['gress']}" );

                  } */
                #
                if (empty($time_profile_id)) {
                    $time_profile_id = 'null';
                }
                $this->Gatewaygroup->query("insert into resource_translation_ref (resource_id,translation_id,time_profile_id)  
					  values($res_id,$translation_id,$time_profile_id)");
            }
            ////$this->Gatewaygroup->log('add_resource_translation_ref');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Ddigit Mapping,Action Successfully !');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/prresource/gatewaygroups/add_translation_time/" . base64_encode($res_id));
    }

    function view_did_post()
    {
        if (isset($_POST ['resource_id'])) {
            $resource_id = $_POST ['resource_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  card_code_part  where resource_id=$resource_id");
            foreach ($tmp as $el) {
                $card_sip_id = $el ['card_sip_id'];
                $did = $el ['code'];

                $list = $this->Gatewaygroup->query("select card_id from  card  where card_number='{$el['card_id']}'");
                $card_id = $list [0] [0] ['card_id'];
                $sip_code = $this->Gatewaygroup->query("select sip_code from card_sip where card_sip_id = $card_sip_id");

                $this->Gatewaygroup->query("insert into card_code_part (resource_id,card_sip_id,did,card_id,sip_code)  
					  values($resource_id,$card_sip_id,'$did',$card_id,'{$sip_code[0][0]['sip_code']}')");
            }

            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action  Success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/view_did/");
    }

    public function edit_ip()
    {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $username = $_REQUEST ['user_name'];
        $password = $_REQUEST ['pass'];
        $id = $_REQUEST ['id'];
        $qs = $this->Gatewaygroup->query("update resource_ip set username = '$username',password='$password' where resource_ip_id = $id");
        if (count($qs) == 0) {
            ////$this->Gatewaygroup->log('update_resource_ip');
            $this->Gatewaygroup->create_json_array('', 201, __('manipulated_suc', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('manipulated_fail', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
    }

    /**
     * 根据帐户号码查询SIP
     */
    public function get_sip_by_card($card_number)
    {
        Configure::write('debug', 2);
        $rs = $this->Gatewaygroup->query("select card_sip_id,sip_code from card_sip where card_id = (select card_id from card where card_number = '$card_number')");
        echo str_ireplace("\"", "'", json_encode($rs));
    }

    //校
    function checkipaddres($ipaddres)
    {
        $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
        if (preg_match($preg, $ipaddres))
            return true;
        return false;
    }

    public function select_naem($id = null)
    {
        if (!empty($id)) {
            $sql = "select name from client where client_id=$id ";
            $list = $this->Client->query($sql);
            $this->set('name', $list[0][0]['name']);
        } else {
            $this->set('name', '');
        }
    }

    public function ingress_capacity($id = null)
    {
        $id = base64_decode($id);
        if (!$id) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $name_arr = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
        $this->set('trunk_name', $name_arr[0][0]['alias']);

        $this->init_info();
        $this->set('resource_id', $id);
        $this->set('routing_rule', $this->_get('routing_rule'));

//        $this->Gatewaygroup->query("ALTER TABLE resource_capacity ADD COLUMN id BIGSERIAL PRIMARY KEY");
        $ingressCapacities = $this->Gatewaygroup->get_ingress_capacities($id);
        $this->set('mydata', $ingressCapacities);
//        die(var_dump($ingressCapacities));

    }

    public function dynamicroutes($id = null)
    {
        $id = base64_decode($id);
        if (!$id) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $name_arr = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
        $this->set('trunk_name', $name_arr[0][0]['alias']);

        $this->init_info();
        $dynamic_data = $this->Dynamicroute->get_dynamicrouteBytrunk($id);

        $this->set('resource_id', $id);

        $this->set('mydata', $dynamic_data);
        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function staticroutes($id = null)
    {
        $id = base64_decode($id);
        if (!$id) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $name_arr = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
        $this->set('trunk_name', $name_arr[0][0]['alias']);
        $this->init_info();

        $get_data = $this->params['url'];
        $condition = "";
        if (isset($get_data['static_route_name']) && !empty($get_data['static_route_name'])) {
            $condition .= " AND product.name = '{$get_data['static_route_name']}'";
        }
        if (isset($get_data['prefix']) && !empty($get_data['prefix'])) {
            $condition .= " AND product.product_id IN ( SELECT product_id FROM product_items WHERE digits::prefix_range <@ '{$get_data['prefix']}')";
        }
        $static_datas = $this->Product->get_staticrouteBytrunk($id, $condition);

        $static_datas_tmp = array();
        foreach ($static_datas as $static_data) {
            $name = $static_data[0]['name'];
            $digits = $static_data[0]['digits'];
            $product_id = $static_data[0]['product_id'];
            $static_datas_tmp[$name]['digits'][] = $digits;
            $static_datas_tmp[$name]['product_id'] = $product_id;
        }
        $result = array();
        foreach ($static_datas_tmp as $product_name => $static_datas_tmp_item) {
            $result[] = array(
                'name' => $product_name,
                'product_id' => $static_datas_tmp_item['product_id'],
                'digits' => implode(",", $static_datas_tmp_item['digits']),
            );
        }
        $this->set('resource_id', $id);

        $this->set('mydata', $result);
        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function ajax_get_trunk_static_info($resource_id, $product_id, $is_return = false)
    {
        if (!$is_return) {
            Configure::write('debug', 0);
            $static_route = $this->Gatewaygroup->query("SELECT product_id,name FROM product WHERE product_id != {$product_id} AND name != 'ORIGINATION_STATIC_ROUTE'");
            $this->set('static_route', $static_route);
            $this->set('resource_id', $resource_id);
        }
        $condition = " AND product.product_id = $product_id";
        $static_datas = $this->Product->get_staticrouteBytrunk($resource_id, $condition);
        $item_arr = array();
        foreach ($static_datas as $static_datas_item) {
            $item_arr['name'] = $static_datas_item[0]['name'];
            $item_arr['product_id'] = $static_datas_item[0]['product_id'];
            $item_arr['digits'][] = $static_datas_item[0]['digits'];
        }
        $item_arr['prefix'] = implode(",", $item_arr['digits']);
        $this->set('static_item_info', $item_arr);
        if ($is_return) {
            return $item_arr;
        }
    }

    public function edit_trunk_static($resource_id, $product_id)
    {
        Configure::write('debug', 0);
        $static_route = $this->Gatewaygroup->query("SELECT product_id,name FROM product where name != 'ORIGINATION_STATIC_ROUTE'");
        $this->set('static_route', $static_route);
        $item_arr = $this->ajax_get_trunk_static_info($resource_id, $product_id, true);
        $this->set('static_item_info', $item_arr);
        if ($this->RequestHandler->ispost()) {
            $static_route_id = $this->params['form']['static_route'];
            $static_change = false;
            if (strcmp($static_route_id, $product_id)) {//改变了静态路由
                $static_change = true;
            }
            $prefix = trim($this->params['form']['prefix']);
            $prefix_arr = explode(",", $prefix);
            /**
             * 判断是否有删除的 $delete_arr
             * 即$item_arr['digits'] 有
             * $prefix_arr 没有
             *
             * 判断是否有添加的 $add_arr
             * 即$prefix_arr有
             * $item_arr['digits'] 没有
             */
            if ($static_change) {
                $delete_arr = $item_arr['digits'];
                $add_arr = $prefix_arr;
            } else {
                $delete_arr = array_diff($item_arr['digits'], $prefix_arr);
                $add_arr = array_diff($prefix_arr, $item_arr['digits']);
                sort($delete_arr);
                sort($add_arr);
            }

//            删除
            $this->Gatewaygroup->begin();
            foreach ($delete_arr as $delete_digits) {
                if (!$delete_digits) {
                    continue;
                }
                $sel_sql = "SELECT item_id FROM product_items WHERE digits = '{$delete_digits}' AND product_id = {$product_id}";
                $item_result = $this->Gatewaygroup->query($sel_sql);
                $item_id = $item_result[0][0]['item_id'];
                $del_sql = "DELETE FROM product_items_resource WHERE resource_id = {$resource_id} AND item_id = {$item_id};"
                    . " DELETE FROM product_items WHERE item_id = {$item_id} AND NOT EXISTS (SELECT item_id FROM product_items_resource WHERE item_id = {$item_id})";
                $flg = $this->Gatewaygroup->query($del_sql);
                if ($flg === false) {
                    $this->rollback_flg = TRUE;
                    $this->Gatewaygroup->rollback();
                    break;
                }
            }

            //添加
            foreach ($add_arr as $prefix_item) {
                $prefix_item = trim($prefix_item);
                if (!$prefix_item) {
                    continue;
                }
                $sel_sql = "SELECT item_id FROM product_items WHERE digits = '{$prefix_item}' AND product_id = {$static_route_id}";
                $flg = $this->Gatewaygroup->query($sel_sql);
                if (!isset($flg[0][0]['item_id'])) {
                    $flg = $this->Gatewaygroup->query("INSERT INTO product_items (product_id,digits,strategy) VALUES ({$static_route_id},'{$prefix_item}',1) RETURNING item_id");
                }
                if ($flg !== false) {
                    $item_id = $flg[0][0]['item_id'];
                    $insert_flg = $this->Gatewaygroup->query("INSERT INTO product_items_resource (item_id,resource_id) VALUES ({$item_id},{$resource_id})");
//                    var_dump($insert_flg);
                    if ($insert_flg === false) {
                        $this->rollback_flg = TRUE;
                        $this->Gatewaygroup->rollback();
                    }
                } else {
                    $this->rollback_flg = TRUE;
                    $this->Gatewaygroup->rollback();
                }
            }

            $this->Gatewaygroup->commit();
            if ($this->rollback_flg) {
                $this->Gatewaygroup->create_json_array('', 101, __('edit trunk static failed', true));
            } else {
                $this->Gatewaygroup->create_json_array('', 201, __('edit trunk static successfully', true));
            }
            $this->Session->write('m', Gatewaygroup::set_validator());
            $encode_resource_id = base64_encode($resource_id);
            $this->redirect("/prresource/gatewaygroups/staticroutes/{$encode_resource_id}");
        }

//        die;
    }

    /**
     * @param $encodeResourseId
     * @param $encode_id
     */
    public function delete_sip_profile($encodeResourseId, $encode_id)
    {
        Configure::write('debug', 0);
        $id = base64_decode($encode_id);
        $sql = "DELETE FROM egress_profile WHERE id = {$id}";
        $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->create_json_array(201, '', "SIP Profile is deleted successfully!");
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->xredirect('/prresource/gatewaygroups/sip_profile/' . $encodeResourseId);
    }

    public function delete_ingress_capacity($encodeResourseId, $encode_id)
    {
        Configure::write('debug', 0);
        $id = base64_decode($encode_id);
        $resourseId = base64_decode($encodeResourseId);
        $sql = "DELETE FROM resource_capacity WHERE id = {$id}";
        $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->create_json_array(201, '', "Ingress Capacity deleted successfully!");
        Gatewaygroup::set_validator();
        $this->xredirect('/prresource/gatewaygroups/ingress_capacity/' . $encodeResourseId);
    }

    public function edit_sip($resource_id, $item_id)
    {
        Configure::write('debug', 0);
        $decodedId = base64_decode($item_id);
        $itemData = $this->EgressProfile->find('first', array(
            'conditions' => array(
                'id' => $decodedId
            )
        ));

        $ingressTrunks = $this->Gatewaygroup->findAll_ingress(
            $this->_order_condtions(
                array('client.name', 'client_id', 'resource_id', 'capacity', 'cps_limit', 'active', 'ip_cnt', 'profit_margin')
            )
        );
        $voipGateways = $this->ServerConfig->find('all');
        $ingressTrunks = $ingressTrunks->dataArray;
        $this->set('ingressTrunks', $ingressTrunks);
        $this->set('voipGateways', $voipGateways);
        $this->set('resource_id', $resource_id);
        $this->set('itemData', $itemData);

        if ($this->RequestHandler->isPost()) {
            $sips = $this->SwitchProfile->find('all');
            $egressProfiles = $this->EgressProfile->find('all');
            $egressId = base64_decode($resource_id);
            $voipGateway = $this->ServerConfig->find('first', array(
                'fields' => array('name'),
                'conditions' => array(
                    'id' => $_POST['voip_gateway']
                )
            ));
            $serverName = $voipGateway['ServerConfig']['name'];
            $profileId = $_POST['sip_profile'];

//            $switchProfile = $this->SwitchProfile->find('first', array(
//                'conditions' => array(
//                    'voip_gateway_id' => $_POST['voip_gateway'],
//                    'id' => $profileId
//                )
//            ));
//            $serverName = $switchProfile['SwitchProfile']['profile_name'];

            $ingressId = $_POST['ingress_id'] == 'All' ? '' : $_POST['ingress_id'];


            $saveData = [];

            if ($_POST['ingress_id'] == 'All') {
//                $this->EgressProfile->deleteAll(['profile_id' => $profileId]);
                foreach ($ingressTrunks as $ingress) {
                    // unique check
                    $this->EgressProfile->query("delete from egress_profile where egress_id = {$egressId} and server_name = '{$serverName}' and ingress_id = {$ingress[0]['resource_id']} ");

                    $saveData[] = [
                        'id' => $decodedId,
                        'egress_id' => $egressId,
                        'profile_id' => $profileId,
                        'ingress_id' => $ingress[0]['resource_id'],
                        'server_name' => $serverName,
                    ];

                }
                if ($this->EgressProfile->saveAll($saveData)) {
                    $this->Gatewaygroup->create_json_array('', 201, 'SIP profile is created successfully!');
                    $this->Session->write('m', Gatewaygroup::set_validator());
                    $this->xredirect('/prresource/gatewaygroups/sip_profile/' . $resource_id);
                }
            } else {
                // check if all
//                $ingress_cnt = count($ingressTrunks);
//                $res = $this->EgressProfile->query("SELECT count(*) cnt FROM egress_profile WHERE profile_id=$profileId");
//
//                if (isset($res[0][0]['cnt']) && $res[0][0]['cnt'] == $ingress_cnt) {
//                    $this->EgressProfile->deleteAll(['profile_id' => $profileId]);
//                }
                $ingressId = $_POST['ingress_id'];
                $saveData['EgressProfile']['id'] = $decodedId;
                $saveData['EgressProfile']['egress_id'] = $egressId;
                $saveData['EgressProfile']['profile_id'] = $profileId;
                $saveData['EgressProfile']['ingress_id'] = $ingressId;
                $saveData['EgressProfile']['server_name'] = $serverName;

                // unique check
//                $this->EgressProfile->query("delete from egress_profile where egress_id = {$egressId} and server_name = '{$serverName}' and ingress_id = {$ingressId} ");
                if ($this->EgressProfile->save($saveData)) {
                    $this->Gatewaygroup->create_json_array('', 201, 'SIP profile is created successfully!');
                    $this->Session->write('m', Gatewaygroup::set_validator());
                    $this->xredirect('/prresource/gatewaygroups/sip_profile/' . $resource_id);
                }
            }
        }
    }

    public function sip_add($resource_id)
    {
        Configure::write('debug', 0);
        $ingressTrunks = $this->Gatewaygroup->findAll_ingress(
            $this->_order_condtions(
                array('client.name', 'client_id', 'resource_id', 'capacity', 'cps_limit', 'active', 'ip_cnt', 'profit_margin')
            )
        );
        $voipGateways = $this->ServerConfig->find('all');
        $ingressTrunks = $ingressTrunks->dataArray;
        $this->set('ingressTrunks', $ingressTrunks);
        $this->set('voipGateways', $voipGateways);
        $this->set('resource_id', $resource_id);

        if ($this->RequestHandler->isPost()) {
            $sips = $this->SwitchProfile->find('all');
            $egressProfiles = $this->EgressProfile->find('all');
            $egressId = base64_decode($resource_id);
            $voipGateway = $this->ServerConfig->find('first', array(
                'conditions' => array(
                    'id' => $_POST['voip_gateway']
                )
            ));
            $serverName = $voipGateway['ServerConfig']['name'];
            $profileId = $_POST['sip_profile'];
//            $switchProfile = $this->SwitchProfile->find('first', array(
//                'conditions' => array(
//                    'voip_gateway_id' => $_POST['voip_gateway'],
//                    'id' => $profileId
//                )
//            ));
//            $serverName = $switchProfile['SwitchProfile']['profile_name'];

            $saveData = [];

            if ($_POST['ingress_id'] == 'All') {
//                $this->EgressProfile->deleteAll(['profile_id' => $profileId, 'egress_id' => $egressId, 'server_name' => $serverName]);
                foreach ($ingressTrunks as $ingress) {
                    // unique check
                    $this->EgressProfile->query("delete from egress_profile where egress_id = {$egressId} and ingress_id = {$ingress[0]['resource_id']} and server_name = '{$serverName}'");

                    $saveData[] = [
                        'egress_id' => $egressId,
                        'profile_id' => $profileId,
                        'ingress_id' => $ingress[0]['resource_id'],
                        'server_name' => $serverName,
                    ];

                }
                $res = $this->EgressProfile->saveAll($saveData);
//                die(var_dump($res));
                if ($res) {
                    $this->Gatewaygroup->create_json_array('', 201, 'SIP profile is created successfully!');
                    $this->Session->write('m', Gatewaygroup::set_validator());
                    $this->xredirect('/prresource/gatewaygroups/sip_profile/' . $resource_id);
                }
            } else {

                // check if all
//                $ingress_cnt = count($ingressTrunks);
//                $res = $this->EgressProfile->query("SELECT count(*) cnt FROM egress_profile WHERE profile_id=$profileId");
//                if (isset($res[0][0]['cnt']) && $res[0][0]['cnt'] == $ingress_cnt) {
//                    $this->EgressProfile->deleteAll(['profile_id' => $profileId]);
//                }

                $ingressId = $_POST['ingress_id'];

                $saveData['EgressProfile']['egress_id'] = $egressId;
                $saveData['EgressProfile']['profile_id'] = $profileId;
                $saveData['EgressProfile']['ingress_id'] = $ingressId;
                $saveData['EgressProfile']['server_name'] = $serverName;
                // unique check
                $this->EgressProfile->query("delete from egress_profile where egress_id = {$egressId} and server_name = '{$serverName}' and ingress_id = {$ingressId} ");

                if ($this->EgressProfile->save($saveData)) {
                    $this->Gatewaygroup->create_json_array('', 201, 'SIP profile is created successfully!');
                    $this->Session->write('m', Gatewaygroup::set_validator());
                    $this->xredirect('/prresource/gatewaygroups/sip_profile/' . $resource_id);
                }
            }

        }
    }

    public function get_sip_by_gateway($resource_id)
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $gatewayId = $_POST['gatewayId'];
            $sips = $this->SwitchProfile->find('all', array(
                'fields' => array('id', 'sip_ip'),
                'conditions' => array(
                    'voip_gateway_id' => $gatewayId
                )
            ));
            echo json_encode($sips);
        }
        exit;
    }

    public function add_ingress_capacity($resource_id)
    {
        Configure::write('debug', 0);
        $ingressTrunks = $this->Gatewaygroup->query("select resource_id, alias from resource where active='t' and ingress='t'");
        $this->set('ingress_trunks', $ingressTrunks);
        if ($this->RequestHandler->ispost()) {
            $formData = $this->params['form'];
            if (!is_numeric($formData['max_cps'])) {
                $this->Gatewaygroup->create_json_array('', 101, __('CPS Limit - numbers only!', true));
            } else if (!is_numeric($formData['max_cap'])) {
                $this->Gatewaygroup->create_json_array('', 101, __('Call Limit - numbers only!', true));
            } else {

                $is_exist = $this->Gatewaygroup->query("select * from resource_capacity where egress_id='{$resource_id}' and ingress_id='{$formData['ingress_id']}'");
                if (empty($is_exist)) {
                    $this->Gatewaygroup->query("INSERT INTO resource_capacity (egress_id, ingress_id, max_cps, max_cap)
                                            VALUES({$resource_id}, {$formData['ingress_id']}, {$formData['max_cps']}, {$formData['max_cap']})");
                    $this->Gatewaygroup->create_json_array('', 201, __('Ingress Capacity created successfully!', true));
                } else {
                    $this->Gatewaygroup->create_json_array('', 101, __('Ingress Capacity duplicated!', true));
                }
            }
            $this->Session->write('m', Gatewaygroup::set_validator());
            $encode_resource_id = base64_encode($resource_id);
            $this->redirect("/prresource/gatewaygroups/ingress_capacity/{$encode_resource_id}");
        }
    }

    public function edit_ingress_capacity($encode_resource_id, $encode_id)
    {
        Configure::write('debug', 0);
        $ingressTrunks = $this->Gatewaygroup->query("select resource_id, alias from resource where active='t' and ingress='t'");
        $id = base64_decode($encode_id);
        $sql = "SELECT * FROM resource_capacity WHERE id = {$id}";
        $editItem = $this->Gatewaygroup->query($sql);
        $this->set('ingress_trunks', $ingressTrunks);
        $this->set('edit_item', $editItem);

        if ($this->RequestHandler->isPost()) {
            $sql = "UPDATE resource_capacity SET ingress_id={$_POST['ingress_id']}, max_cps={$_POST['max_cps']}, max_cap={$_POST['max_cap']}";
            $this->Gatewaygroup->query($sql);
            $this->Gatewaygroup->create_json_array('', 201, __('Ingress Capacity modified successfully!', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $encode_resource_id = base64_encode($encode_resource_id);
            $this->redirect("/prresource/gatewaygroups/ingress_capacity/$encode_resource_id");
        }
    }

    public function delete_all_ingress_capacities($encode_resource_id)
    {
        $resourceId = base64_decode($encode_resource_id);
        $this->Gatewaygroup->query("DELETE FROM resource_capacity WHERE egress_id={$resourceId}");

        $this->Gatewaygroup->create_json_array('', 201, __('All Ingress Capacities are deleted successfully!', true));
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect("/prresource/gatewaygroups/ingress_capacity/{$encode_resource_id}");
    }

    public function delete_selected_ingress_capacities($encode_resource_id)
    {
        $ids = $this->params['url']['ids'];
        $resourceId = base64_decode($encode_resource_id);
        $this->Gatewaygroup->query("DELETE FROM resource_capacity WHERE egress_id={$resourceId} AND id in ({$ids})");

        $this->Gatewaygroup->create_json_array('', 201, __('Selected Ingress Capacities are deleted successfully!', true));
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect("/prresource/gatewaygroups/ingress_capacity/{$encode_resource_id}");
    }

    public function add_trunk_static($resource_id)
    {
        Configure::write('debug', 0);
        $static_route_sql = "SELECT product.product_id,product.name FROM product as product left join 
            product_items as product_items on product.product_id = product_items.product_id  where product.name != 'ORIGINATION_STATIC_ROUTE' AND not EXISTS 
(select item_id from product_items_resource as product_resource where resource_id = {$resource_id} and product_items.item_id = product_resource.item_id)";
        $static_route = $this->Gatewaygroup->query($static_route_sql);
        $this->set('static_route', $static_route);
        if ($this->RequestHandler->ispost()) {
            $static_route_id = $this->params['form']['static_route'];
            $prefix = $this->params['form']['prefix'];
            $prefix_arr = array(0 => "");
            if ($prefix) {
                $prefix_arr = explode(",", $prefix);
            }
            $this->Productitem->begin();
            foreach ($prefix_arr as $prefix_item) {
                $prefix_item = trim($prefix_item);
                $sel_sql = "SELECT item_id FROM product_items WHERE digits = '{$prefix_item}' AND product_id = {$static_route_id}";
                $flg = $this->Gatewaygroup->query($sel_sql);
                if (!isset($flg[0][0]['item_id'])) {
                    $flg = $this->Gatewaygroup->query("INSERT INTO product_items (product_id,digits,strategy) VALUES ({$static_route_id},'{$prefix_item}',1) RETURNING item_id");
                }
                if ($flg !== false) {
                    $item_id = $flg[0][0]['item_id'];
                    $insert_flg = $this->Gatewaygroup->query("INSERT INTO product_items_resource (item_id,resource_id) VALUES ({$item_id},{$resource_id})");
//                    var_dump($insert_flg);
                    if ($insert_flg === false) {
                        $this->rollback_flg = TRUE;
                        $this->Gatewaygroup->rollback();
                    }
                } else {
                    $this->rollback_flg = TRUE;
                    $this->Gatewaygroup->rollback();
                }
            }
            $this->Gatewaygroup->commit();
            if ($this->rollback_flg) {
                $this->Gatewaygroup->create_json_array('', 101, __('add trunk static failed', true));
            } else {
                $this->Gatewaygroup->create_json_array('', 201, __('Static Route is added successfully!', true));
            }
            $this->Session->write('m', Gatewaygroup::set_validator());
            $encode_resource_id = base64_encode($resource_id);
            $this->redirect("/prresource/gatewaygroups/staticroutes/{$encode_resource_id}");
        }

//        die;
    }

    public function delete_all_trunk_static($encode_resource_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $resource_id = base64_decode($encode_resource_id);
        $sql = "SELECT item_id FROM product_items_resource WHERE resource_id = {$resource_id}";
        $items_result = $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->begin();
        $del_items_resource_sql = "DELETE FROM product_items_resource WHERE resource_id = {$resource_id};";
        $del_items_resource_flg = $this->Gatewaygroup->query($del_items_resource_sql);
        if ($del_items_resource_flg === false) {
            $this->rollback_flg = true;
            $this->Gatewaygroup->rollback();
        }
        foreach ($items_result as $item_result) {
            $item_id = $item_result[0]['item_id'];
            $del_sql = " DELETE FROM product_items WHERE item_id = {$item_id} AND NOT EXISTS (SELECT item_id FROM product_items_resource WHERE item_id = {$item_id})";
            $flg = $this->Gatewaygroup->query($del_sql);
            if ($flg === false) {
                $this->rollback_flg = true;
                $this->Gatewaygroup->rollback();
                break;
            }
        }
        if ($this->rollback_flg) {
            $this->Gatewaygroup->create_json_array('', 101, __('deletetrunkstaticfailed', true));
        } else {
            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->create_json_array('', 201, __('All Static Trunks are deleted successfully!', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect("/prresource/gatewaygroups/staticroutes/{$encode_resource_id}");
    }

    public function delete_selected_trunk_static($encode_resource_id)
    {
        $id_arr = explode(",", $this->params['url']['ids']);
        $this->delete_trunk_static($encode_resource_id, $id_arr);
    }

    public function delete_trunk_static($encode_resource_id, $encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $resource_id = base64_decode($encode_resource_id);
        if (!is_array($encode_id)) {
            $product_id = base64_decode($encode_id);
            if (!$product_id) {
                $this->Gatewaygroup->create_json_array('', 101, __('deletetrunkstaticfailed', true));
                $this->Session->write('m', Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/staticroutes/{$encode_resource_id}");
            }
            $product_sql = "= {$product_id}";
        } else {//$encode_id 是一个包含所选全部的product id
            $product_id_arr = array();
            foreach ($encode_id as $key => $product_item) {
                $product_id_arr[] = (int)$product_item;
            }
            $product_sql = "in (" . implode(",", $product_id_arr) . ")";
        }

//        查出所有相关的code
        $sql = "SELECT items.item_id FROM product_items AS items LEFT JOIN product_items_resource AS items_r "
            . "ON items_r.item_id = items.item_id where items.product_id {$product_sql} AND items_r.resource_id = {$resource_id}";

        $items_result = $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->begin();
        foreach ($items_result as $item_result) {
            $item_id = $item_result[0]['item_id'];
            $del_items_resource = "DELETE FROM product_items_resource WHERE resource_id = {$resource_id} AND item_id = {$item_id};";
            $flg1 = $this->Gatewaygroup->query($del_items_resource);
            if ($flg1 === false) {
                $this->rollback_flg = true;
                $this->Gatewaygroup->rollback();
                break;
            }
            $del_item = "DELETE FROM product_items WHERE item_id = {$item_id} AND NOT EXISTS (SELECT item_id FROM product_items_resource WHERE item_id = {$item_id})";
            $flg2 = $this->Gatewaygroup->query($del_item);
            if ($flg2 === false) {
                $this->rollback_flg = true;
                $this->Gatewaygroup->rollback();
                break;
            }
        }
        if ($this->rollback_flg) {
            $this->Gatewaygroup->create_json_array('', 101, __('deletetrunkstaticfailed', true));
        } else {
            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->create_json_array('', 201, __('Selected Static Trunks are deleted successfully!', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect("/prresource/gatewaygroups/staticroutes/{$encode_resource_id}");
    }

    public function add_staticroutes($id = null)
    {
        if (!$id) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $id = base64_decode($id);
        $name_arr = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
        $this->set('trunk_name', $name_arr[0][0]['alias']);
        $this->pageTitle = "Carrier/Trunk/Static/addRouting";
        $this->init_info();
        $order = $this->_order_condtions_all(Array('dynamic_route_id', 'use_count', 'routing_rule', 'name', 'time_profile_id'));

        $pdata = $this->Dynamicroute->findAll($order);
        //print_r($pdata);
        $this->set('p', $pdata);
        $sql = "SELECT dynamic_route_id FROM dynamic_route_items WHERE resource_id = {$id}";
        $dynamic_data = $this->Dynamicroute->query($sql);
        $dynamic_route_id_arr = array();
        foreach ($dynamic_data as $items) {
            foreach ($items as $item) {
                $dynamic_route_id_arr[] = $item['dynamic_route_id'];
            }
        }
        $this->set('resource_id', $id);
        $this->set('dynamic_route_id_arr', $dynamic_route_id_arr);
        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function add_dynamicroutes($id = null)
    {
        if (!$id) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $id = base64_decode($id);
        $name_arr = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
        $this->set('trunk_name', $name_arr[0][0]['alias']);
        $this->pageTitle = "Carrier/Trunk/Dynamic/addRouting";
        $this->init_info();
        $order = $this->_order_condtions_all(Array('dynamic_route_id', 'use_count', 'routing_rule', 'name', 'time_profile_id'));

        $pdata = $this->Dynamicroute->findAll($order);
        $this->set('p', $pdata);
        $sql = "SELECT dynamic_route_id FROM dynamic_route_items WHERE resource_id = {$id}";
        $dynamic_data = $this->Dynamicroute->query($sql);
        $dynamic_route_id_arr = array();
        foreach ($dynamic_data as $items) {
            foreach ($items as $item) {
                $dynamic_route_id_arr[] = $item['dynamic_route_id'];
            }
        }
        $this->set('resource_id', $id);
        $this->set('dynamic_route_id_arr', $dynamic_route_id_arr);
        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function modify_dynamicroutes()
    {
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($this->isnotEmpty($this->params['form'], array('dynamic_route_id_arr'))) {
            $resource_id = $this->params['form']['resource_id'];
            $dynamic_route_id_arr = array();
            foreach ($this->params['form']['dynamic_route_id_arr'] as $key => $item) {
                $data = $this->Dynamicroute->query("SELECT * FROM dynamic_route_items WHERE dynamic_route_id = {$key} AND resource_id = {$resource_id} ");
                if (!$data) {
                    $this->Dynamicroute->query("INSERT INTO dynamic_route_items (dynamic_route_id,
                                resource_id) VALUES ({$key}, {$resource_id})");
                }
            }
            $this->Gatewaygroup->create_json_array('', 201, __('Add successfully', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('No new data is added', true));
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect("/prresource/gatewaygroups/dynamicroutes/" . base64_encode($resource_id));
    }

    public function del_select_dynamicroutes($id = null)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($id) {
            $trunk_info = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
            if (empty($trunk_info)) {
                $id_empty_flg = 1;
            }
        }
        if (isset($id_empty_flg) || empty($id)) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        if ($this->isnotEmpty($this->params['form'], array('dynamic_route_id_arr'))) {
            $resource_id = $this->params['form']['resource_id'];
            $dynamic_route_id_arr = array();
            foreach ($this->params['form']['dynamic_route_id_arr'] as $key => $item) {
                $data = $this->Dynamicroute->query("SELECT * FROM dynamic_route_items WHERE dynamic_route_id = {$key} AND resource_id = {$resource_id} ");
                if ($data) {
                    $this->Dynamicroute->query("DELETE FROM dynamic_route_items WHERE dynamic_route_id = {$key} AND resource_id = {$resource_id}");
                }
            }
            $this->Gatewaygroup->create_json_array('', 201, __('Delete successfully', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('No new data is deleted', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
        $resource_id = base64_encode($resource_id);
        $this->redirect("/prresource/gatewaygroups/dynamicroutes/{$resource_id}");
    }

    public function del_all_dynamicroutes($id = null)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($id) {
            $trunk_info = $this->Gatewaygroup->query("select alias from resource where resource_id = {$id}");
            if (empty($trunk_info)) {
                $id_empty_flg = 1;
            }
        }
        if (isset($id_empty_flg) || empty($id)) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress id is not exsit', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            $this->redirect("/prresource/gatewaygroups/view_egress");
        }
        $del_data = $this->Dynamicroute->query("DELETE FROM dynamic_route_items WHERE resource_id = {$id} RETURNING resource_id");
        if ($del_data) {
            $this->Gatewaygroup->create_json_array('', 201, __('Delete successfully', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('No new data is deleted', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
        $id = base64_encode($id);
        $this->redirect("/prresource/gatewaygroups/dynamicroutes/{$id}");
    }

    public function upload_rule($resource_id, $type = "egress")
    {
        $resource_id = base64_decode($resource_id);
        if (!in_array($type, array('egress', 'ingress'))) {
            $this->Gatewaygroup->create_json_array('', 101, __('Upload type is not found!', true));
        }
        $this->set_name_gress($resource_id);
        $this->set("type", $type);
    }

    public function upload_replace_action($resource_id, $type = "egress")
    {
        $resource_id = base64_decode($resource_id);
        if (!in_array($type, array('egress', 'ingress'))) {
            $this->Gatewaygroup->create_json_array('', 101, __('Upload type is not found!', true));
        }
        $this->set_name_gress($resource_id);
        $this->set("type", $type);
    }

    //Host Usage History
    public function host_usage()
    {

        //时间条件
        $now_time = date('Y-m-d H:i:s');
        $old_time = date('Y-m-d 00:00:00', strtotime('-6 days'));

        //select distinct(origination_source_host_name) from client_cdr20151014 WHERE time between '2015-10-01' and '2015-10-30'

        //查询使用的ingress host
        $used_ingress_host = $this->used_host($old_time, $now_time, true);

        //查询使用的egress host
        $used_egress_host = $this->used_host($old_time, $now_time, false);


        $conditions = array(
            'resource.is_virtual is not true and resource.trunk_type2 = 0'
        );
        //排序

        //查询所有的host
        $sql = "select resource_ip.ip,resource.resource_id,resource.alias,client.name from resource_ip left join resource on resource.resource_id = resource_ip.resource_id left JOIN client on client.client_id = resource.client_id WHERE resource.is_virtual is not true and resource.trunk_type2 = 0";

        $this->paginate = array(
            'fields' => array(
                'ResourceIp.ip', 'resource.resource_id', 'resource.alias', 'client.name'
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'resource.resource_id = ResourceIp.resource_id'
                    ),
                ),
                array(
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'client.client_id = resource.client_id'
                    ),
                )
            ),
            //'order' => $order_arr,
            'conditions' => $conditions

        );

        $this->loadModel('ResourceIp');
        $this->data = $this->paginate('ResourceIp');
        pr($this->data);
        exit;

        $this->set('used_ingress_host', $used_ingress_host);
        $this->set('used_egress_host', $used_egress_host);
    }

    public function used_host($start_date, $end_date, $is_ingress)
    {
        //分表
        $date_arr = $this->_get_date_result_admin($start_date, $end_date, 'client_cdr2%');

        if ($is_ingress) {
            $fields = "distinct(origination_source_host_name)";
            $out_fields = "distinct(origination_source_host_name) as host";
            $where = "";
        } else {
            $fields = "distinct(termination_destination_host_name)";
            $out_fields = "distinct(termination_destination_host_name) as host";
            $where = '';
        }


        $org_sql = '';
        foreach ($date_arr as $value) {
            $table_name = 'client_cdr' . $value;

            $union = "";
            if (!empty($org_sql)) {
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$fields}
from {$table_name}
where time between '{$start_date}' and '{$end_date}'
$where

";

        }

        $sql = "SELECT
{$out_fields}
from ( {$org_sql} ) as tmp";

        $rst = $this->Gatewaygroup->query($sql);

        $arr = array();
        foreach ($rst as $item) {
            if (empty($item[0]['host'])) continue;
            $arr[] = $item[0]['host'];
        }

        return $arr;
    }


    //log->sip register
    public function sip_register_log()
    {
//pr($_SESSION);
        $this->pageTitle = 'Sip Register Log';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time'])) {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $conditions = array(
            'uptime BETWEEN ? and ?' => array(strtotime($start_time), strtotime($end_time))
        );

        if (isset($_GET['username']) && !empty($_GET['username'])) {
            $conditions["username like ?"] = '%' . $_GET['username'] . '%';
        }

        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $conditions["EmailLog.type"] = $_GET['type'];
        }
        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $conditions["status"] = $_GET['status'];
        }

        $order_arr = array('id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array(
            'fields' => array(
                '*'
            ),
            'limit' => 100,
            // 'joins' => array(
            // array(
            // 'table' => 'resource_ip',
            // 'type' => 'inner',
            // 'conditions' => array(
            // 'resource_ip.username = sip_registrations.username'
            // ),
            // )
            // ),
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->loadModel('SipRegistrations');
        $this->loadModel('Gatewaygroup');
        $this->loadModel('ResourceIp');
        $this->loadModel('Clients');

        $data = $this->paginate('SipRegistrations');
        foreach ($data as &$d) {
            $trunkData = $this->Gatewaygroup->query("select resource.alias, client.name from resource  
                    left join client 
                        on client.client_id = resource.client_id 
                    left join resource_ip 
                        on resource_ip.resource_id = resource.resource_id
                        where resource_ip.username = '{$d[0]['username']}'
                    ");
            $d[0]['trunk_name'] = isset($trunkData[0][0]['alias']) ? $trunkData[0][0]['alias'] : '';
            $d[0]['carrier_name'] = isset($trunkData[0][0]['name']) ? $trunkData[0][0]['name'] : '';
        }
        $this->data = $data;
//        pr($this->data);
//        $this->set('clients', $this->EmailLog->get_carriers());
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $statuses = array('Un-register', 'Registered', 'Register Failed');
        $this->set('statuses', $statuses);
    }

    public function send_interop($encode_resource_id, $back_function = 'view_ingress')
    {
        $resource_id = base64_decode($encode_resource_id);

        if (!intval($resource_id)) {
            $this->Session->write('m', $this->Gatewaygroup->create_json(101, __('Operation failed!', true)));
            $this->redirect($back_function);
        }
        $count = $this->Gatewaygroup->find('count', array(
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Client.client_id = Gatewaygroup.client_id'
                    ),
                ),
            ),
            'conditions' => array(
                'Gatewaygroup.resource_id' => $resource_id,
                'email is not null'
            ),
        ));
        if (!$count) {
            $this->Session->write('m', $this->Gatewaygroup->create_json(101, __('Carrier mail is not exist!', true)));
            $this->redirect('vendor_invoice');
        }


        Configure::load('myconf');
        $php_path = Configure::read('php_exe_path');
        $cmd = "{$php_path} " . APP . "../cake/console/cake.php send_email 5 $resource_id /dev/null";
        $info = $this->Systemparam->find('first', array(
            'fields' => array('cmd_debug'),
        ));
        file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        shell_exec($cmd);
        $this->Session->write('m', $this->Gatewaygroup->create_json(201, __('email_is_being_sent', true)));
        $this->redirect($back_function);
    }

}

?>
