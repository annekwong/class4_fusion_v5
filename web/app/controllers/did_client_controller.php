<?php

class DidClientController extends AppController
{
    var $uses = array('Client', 'Gatewaygroup', 'ResourceIp', 'did.Did', 'Resource', 'did.DidBillingRel', 'Cdr', 'DefaultFields', 'EmailLog');
    var $helpers = array('AppGatewaygroup');
    private $clientId;

    public function beforeFilter()
    {
        parent::beforeFilter();
//        $this->layout = 'did_client';
        Configure::write('debug', 0);
        $this->clientId = $_SESSION['sst_client_id'];
    }

    public function index()
    {
        $this->redirect('/did_client/dashboard');
    }

    public function dashboard()
    {
        $encodedWord = base64_encode("DID");
        $dids = $this->DidBillingRel->find('all', array(
            'fields' => array('DidBillingRel.did'),
            'conditions' => array(
                "ingress_res_id in (SELECT resource_id FROM resource WHERE client_id = {$_SESSION['sst_client_id']} AND alias LIKE '%_{$encodedWord}_%') AND (end_date IS NULL OR end_date > now())"
            )
        ));

        $clientResources = $this->Resource->find('all', array(
            'fields' => array('resource_id'),
            'conditions' => array(
                'client_id' => $this->clientId
            )
        ));
        $listResources = array();

        foreach ($clientResources as $clientResource) {
            array_push($listResources, $clientResource['Resource']['resource_id']);
        }
        $this->set('clientTrunks', implode(",", $listResources));
        $this->set('dids', $dids);
    }

    public function order_new_number()
    {
        $this->loadModel('Jurisdiction');
        $this->loadModel('did.DidBillingPlan');

        $countries = $this->Jurisdiction->find('all');

        $this->set('countries', $countries);

        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;

            $numbers = $_POST['number'];
            $clientTrunk = $this->Resource->find('first', array(
                'fields' => array('resource_id'),
                'conditions' => array(
                    'client_id' => $_SESSION['sst_client_id']
                )
            ));

            Configure::write('debug', 2);
            foreach ($numbers as $key => $number) {
                $relData = $this->Did->getRelDataById($number);
                $result = $this->Did->assign_did($clientTrunk['Resource']['resource_id'], $relData[0][0]['vendor_trunk_id'], $relData[0][0]['did_number'], $relData[0][0]['vendor_billing_plan_id'], $relData[0][0]['client_billing_plan_id'], false, $number);
            }

            if ($result) {
                $this->loadModel('Systemparam');

                $systemNocEmail = $this->Systemparam->find('first', array(
                    'fields' => 'noc_email'
                ));

                if (!empty($systemNocEmail['Systemparam']['noc_email'])) {
                    $this->loadModel('Mailtmp');
                    $this->loadModel('did.DidBillingPlan');

                    $mailTemplate = $this->Mailtmp->find('first', array(
                        'fields' => array('did_order_from', 'did_order_cc', 'did_order_subject', 'did_order_content')
                    ));
                    $billingPlanData = $this->DidBillingPlan->find('first', array(
                        'conditions' => array(
                            'id' => $relData[0][0]['client_billing_plan_id']
                        )
                    ));
                    $mailTemplate['Mailtmp']['did_order_content'] = str_replace(
                        array(
                            '{company_name}',
                            '{did}',
                            '{setup_fee}',
                            '{monthly_fee}',
                            '{per_min_rate}'
                        ),
                        array(
                            $_SESSION['carrier_panel']['Client']['company'],
                            $relData[0][0]['did'],
                            $billingPlanData['DidBillingPlan']['did_price'],
                            $billingPlanData['DidBillingPlan']['monthly_charge'],
                            $billingPlanData['DidBillingPlan']['min_price'],
                        ),
                        $mailTemplate['Mailtmp']['did_order_content']
                    );

                    $emailResult = $this->VendorMailSender->send(
                        $mailTemplate['Mailtmp']['did_order_subject'],
                        $mailTemplate['Mailtmp']['did_order_content'],
                        $systemNocEmail['Systemparam']['noc_email'],
                        $mailTemplate['Mailtmp']['did_order_cc'],
                        $mailTemplate['Mailtmp']['did_order_from']
                    );

                    $this->EmailLog->save(array(
                        'send_time' => date('Y-m-d H:i:s'),
                        'client_id' => $_SESSION['sst_client_id'],
                        'email_addresses' => $systemNocEmail['Systemparam']['noc_email'],
                        'type' => 43,
                        'status' => $emailResult['status'],
                        'error' => $emailResult['error'],
                        'subject' => $mailTemplate['Mailtmp']['did_order_subject'],
                        'content' => $mailTemplate['Mailtmp']['did_order_content']
                    ));
                }
            }
            Configure::write('debug', 0);
            ob_clean();

            return $result ? '1' : '0';
        }
    }

    public function trunks()
    {
        $encodedWord = base64_encode("DID");
        $trunks = $this->Resource->find('all', array(
            'fields' => array(
                'resource_id', 'alias', 'price_per_max_channel', 'price_per_actual_channel', 'active', 'status'
            ),
            'conditions' => array(
                'client_id' => $this->clientId,
                'NOT' => array(
                    "alias like '%_{$encodedWord}_%'"
                )
            )
        ));

        foreach ($trunks as $key => $trunk) {
            $trunks[$key]['ResourceIp'] = $this->ResourceIp->find('all', array(
                'fields' => array('ip', 'port'),
                'conditions' => array(
                    'resource_id' => $trunk['Resource']['resource_id']
                )
            ));
        }
//        die(var_dump($trunks));
        $this->set('data', $trunks);
    }

    public function dids()
    {
        $encodedWord = base64_encode('DID');

        $data = $this->DidBillingRel->find('all', array(
            'fields' => array(
                'DidBillingRel.id', 'DidBillingRel.did_number', 'Resource.alias'
            ),
            'conditions' => array(
                "client_trunk_id in (SELECT resource_id FROM resource WHERE client_id = {$_SESSION['sst_client_id']} AND alias LIKE '%_{$encodedWord}_%') AND (end_date IS NULL OR end_date > now())"
            ),
            'joins' => array(
                array(
                    'table' => 'code',
                    'alias' => 'Code',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'cast(Code.code as varchar(255)) = cast(DidBillingRel.did_number as varchar(255))'
                    )
                ),
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidBillingRel.client_trunk_id = Resource.resource_id'
                    )
                )
            )
        ));

        foreach ($data as &$item) {
            $item['DidBillingRel']['type'] = $this->Did->didGetType($item['DidBillingRel']['did_number']);
        }

        $this->set('data', $data);

        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);

            echo json_encode($data);
            exit;
        }
    }

    public function did_edit_panel($id)
    {
        Configure::write('debug', 0);

        $encodedWord = base64_encode('DID');
        $data = $this->DidBillingRel->find('first', array(
            'fields' => array(
                'DidBillingRel.id', 'DidBillingRel.client_trunk_id', 'DidBillingRel.did_number', 'Code.country', 'Code.state', 'Resource.alias'
            ),
            'conditions' => array(
                "id = $id"
            ),
            'joins' => array(
                array(
                    'table' => 'code',
                    'alias' => 'Code',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'cast(Code.code as varchar(255)) = cast(DidBillingRel.did_number as varchar(255))'
                    )
                ),
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidBillingRel.client_trunk_id = Resource.resource_id'
                    )
                )
            )
        ));

        $data['DidBillingRel']['type'] = $this->Did->didGetType($data['DidBillingRel']['did_number']);
        $clientId = $_SESSION['sst_client_id'];
        $trunks = $this->Resource->find('all', array(
            'fields' => array('resource_id', 'alias'),
            'conditions' => array(
                'client_id' => $clientId,
                'NOT' => array(
                    "alias like '%_{$encodedWord}_%'"
                )
            )
        ));

        $this->set('data', $data);
        $this->set('trunks', $trunks);

        if ($this->RequestHandler->isPost()) {
            $relData = $this->Did->getRelDataById($id);
            $result = $this->Did->assign_did($_POST['trunk'], $relData[0][0]['vendor_trunk_id'], $relData[0][0]['did_number'], $relData[0][0]['vendor_billing_plan_id'], $relData[0][0]['client_billing_plan_id'], false, $id);

            if ($result) {
                $this->Session->write('m', $this->Did->create_json(201, 'Successfully'));
            } else {
                $this->Session->write('m', $this->Did->create_json(101, 'Failed'));
            }
            $this->redirect('dids');
        }
    }

    public function reports()
    {
        $this->redirect('reports_did');
    }

    public function reports_did()
    {
        $dids = $this->DidBillingRel->find('all', array(
            'fields' => array('DidBillingRel.did_number'),
            'conditions' => array(
                "client_trunk_id in (SELECT resource_id FROM resource WHERE client_id = {$_SESSION['sst_client_id']} AND alias LIKE '%_{$encodedWord}_%') AND (end_date IS NULL OR end_date > now())"
            )
        ));
        $clientResources = $this->Resource->find('all', array(
            'fields' => array('resource_id'),
            'conditions' => array(
                'client_id' => $this->clientId
            )
        ));
        $listResources = array();

        foreach ($clientResources as $clientResource) {
            array_push($listResources, $clientResource['Resource']['resource_id']);
        }
        $this->set('clientTrunks', implode(",", $listResources));
        $this->set('defaultFields', $this->DefaultFields->getFields('did_client_reports_did'));
        $this->set('dids', $dids);
    }

    public function cdr()
    {
        $this->initNewReport();
        $field = array();
        $report_fields = array();
        $clientResources = $this->Resource->find('all', array(
            'fields' => array('resource_id'),
            'conditions' => array(
                'client_id' => $this->clientId
            )
        ));
        $listResources = array();

        foreach ($clientResources as $clientResource) {
            array_push($listResources, $clientResource['Resource']['resource_id']);
        }

        $cloudSharkUrl = '108.165.2.57';
        $cloudSharkToken = 'd0c7536f5e2c8c66d9de884183ee4c4e';

        $this->set('cloudSharkUrl', $cloudSharkUrl);
        $this->set('cloudSharkToken', $cloudSharkToken);
        $this->set('report_fields', $report_fields);
        $this->set('cdr_field', $field);
//        $this->set('defaultFields', $this->DefaultFields->getFields('did_client_cdr'));
        $this->set('clientTrunks', implode(",", $listResources));
    }

    public function profile()
    {

        if ($this->RequestHandler->isPost()) {
            $save = array(
                'client_id' => $this->clientId,
                'company' => $_POST['company'],
                'email' => $_POST['email']
            );

            if ($_POST['password'] && $_POST['confirmPassword'] && $_POST['password'] == $_POST['confirmPassword']) {
                $save['password'] = $_POST['password'];

                $this->loadModel('User');

                $userPassword = md5($_POST['password']);
                $this->User->query("UPDATE users SET password = '{$userPassword}' WHERE client_id = {$this->clientId}");
            }
            $saveResult = $this->Client->save($save);

            if($saveResult) {
                $this->Session->write('m', $this->Client->create_json(201, 'Profile updated successfully'));
            } else {
                $this->Session->write('m', $this->Client->create_json(101, 'Profile update failed. Please try again'));
            }

        }

        $client = $this->Client->find('first', array(
            'conditions' => array(
                'client_id' => $this->clientId
            )
        ));

        $this->set('client', $client);
    }

    public function checkEmail()
    {
        if ($this->RequestHandler->isGet()) {
            $email = $_GET['email'];

            $result = $this->Client->find('count', array(
                'conditions' => array(
                    'email' => $email,
                    'NOT' => array(
                        'client_id' => $this->clientId
                    )
                )
            ));

            echo $result > 0 ? 'false' : 'true';
        }
        exit;
    }

    public function ajaxGetRates()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = false;

        if ($this->RequestHandler->isPost()) {
            $this->loadModel('RateTable');

            $rateTableId = $_POST['id'];
            $rateTableId = base64_decode($rateTableId);

            $rates = $this->RateTable->find('all', array(
                'conditions' => array(
                    'rate_table_id' => $rateTableId
                )
            ));

            echo json_encode($rates);
        }
        exit;
    }

    public function changeTrunkStatus($id, $type)
    {
//        $save = array(
//            'resource_id' => base64_decode($id),
//            'active' => $type == 1 ? true : false
//        );
//        $saveResult = $this->Gatewaygroup->save($save);

        $active = $type == 1 ? 'true' : 'false';
        $sql = "UPDATE resource SET active = '{$active}' WHERE client_id = {$this->clientId}";
        $saveResult = $this->Gatewaygroup->query($sql);

        if ($saveResult !== false) {
            $changeText = $type == 1 ? 'activated' : 'deactivated';
            $this->Session->write('m', $this->Gatewaygroup->create_json(201, "Trunk {$changeText} successfully!"));
        } else {
            $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Failed!"));
        }

        $this->redirect('trunks');
    }

    public function trunk_edit_panel($id = null)
    {
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {

            if ($id) {
                $resourceId = base64_decode($id);

                $count = $this->Gatewaygroup->find('count', array(
                    'conditions' => array(
                        'alias' => $this->data['Gatewaygroup']['alias'],
                        'NOT' => array(
                            'resource_id' => $resourceId
                        )
                    )
                ));

                if ($count > 0) {
                    $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Trunk [{$this->data['Gatewaygroup']['alias']}] already exists!"));
                } else {
                    $this->data['Gatewaygroup']['client_id'] = $this->clientId;
                    $this->data['Gatewaygroup']['resource_id'] = $resourceId;

                    $this->Gatewaygroup->begin();
                    $saveResult = $this->Gatewaygroup->save($this->data);

                    if ($saveResult) {
                        $this->ResourceIp->deleteAll(array(
                            'resource_id' => $resourceId
                        ));
                        $arraySaveIp = array();

                        foreach ($this->data['ips'] as $key => $ip) {
                            array_push($arraySaveIp, array(
                                'ip' => $ip,
                                'port' => $this->data['ports'][$key],
                                'resource_id' => $resourceId
                            ));
                        }
                        $saveResult = $this->ResourceIp->saveAll($arraySaveIp);

                        if ($saveResult) {
                            $this->Gatewaygroup->commit();
                            $this->Session->write('m', $this->Gatewaygroup->create_json(201, "Trunk [{$this->data['Gatewaygroup']['alias']}] updated successfully!"));
                        } else {
                            $this->Gatewaygroup->rollback();
                            $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Update failed!"));
                        }

                    } else {
                        $this->Gatewaygroup->rollback();
                        $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Update failed!"));
                    }
                }

            } else {
                $count = $this->Gatewaygroup->find('count', array(
                    'conditions' => array(
                        'alias' => $this->data['Gatewaygroup']['alias']
                    )
                ));

                if ($count > 0) {
                    $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Trunk [{$this->data['Gatewaygroup']['alias']}] already exists!"));
                } else {
                    $this->data['Gatewaygroup']['client_id'] = $this->clientId;

                    $this->Gatewaygroup->begin();
                    $saveResult = $this->Gatewaygroup->save($this->data);

                    if ($saveResult) {
                        $resourceId = $this->Gatewaygroup->getLastInsertId();
                        $arraySaveIp = array();

                        foreach ($this->data['ips'] as $key => $ip) {
                            array_push($arraySaveIp, array(
                                'ip' => $ip,
                                'port' => $this->data['ports'][$key],
                                'resource_id' => $resourceId
                            ));
                        }
                        $saveResult = $this->ResourceIp->saveAll($arraySaveIp);

                        if ($saveResult) {
                            $this->Gatewaygroup->commit();
                            $this->Session->write('m', $this->Gatewaygroup->create_json(201, "Trunk [{$this->data['Gatewaygroup']['alias']}] created successfully!"));
                        } else {
                            $this->Gatewaygroup->rollback();
                            $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Create failed!"));
                        }

                    } else {
                        $this->Gatewaygroup->rollback();
                        $this->Session->write('m', $this->Gatewaygroup->create_json(101, "Create failed!"));
                    }
                }
            }

            $this->redirect('trunks');
        }

        if ($id) {
            $resourceId = base64_decode($id);
            $this->data = $this->Gatewaygroup->find('first', array(
                'conditions' => array(
                    'resource_id' => $resourceId
                )
            ));

            $resourceIps = $this->ResourceIp->find('all', array(
                'fields' => array('ip', 'port'),
                'conditions' => array(
                    'resource_id' => $resourceId
                )
            ));

            $this->data["Gatewaygroup"]["ip"] = $resourceIps;
        }
    }

    public function ajaxGetOrderDids()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;

            $country = $_POST['country'];
            $searchBy = $_POST['searchBy'];
            $prefix = $_POST['prefix'];

            if (!class_exists('Did')) {
                $this->loadModel('did.Did');
            }
            $prefix = $searchBy == 0 ? null : $prefix;
            $data = $this->Did->getUnassignedDids($prefix, $country);

            echo json_encode($data);
        }
        exit;
    }

    public function cancel_did($encodedId)
    {
        $id = base64_decode($encodedId);

        $result = $this->DidBillingRel->save(array(
            'id' => $id,
            'client_trunk_id' => null,
            'enable_for_clients' => true
        ));

        if ($result) {
            $this->Session->write('m', $this->Did->create_json(201, 'Successfully'));
        } else {
            $this->Session->write('m', $this->Did->create_json(101, 'Failed'));
        }
        $this->redirect('dids');
    }

    public function initNewReport()
    {
        $user_id = $_SESSION['sst_user_id'];

        $this->init_query();

        $res = $this->Cdr->query("select report_group,outbound_report from users where user_id = {$user_id} ");

        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);

        $system_parameter = $this->Cdr->query("SELECT is_hide_unauthorized_ip FROM system_parameter limit 1");
        $is_hide_unauthorized_ip = isset($this->params['url']['is_hide_unauthorized_ip']) ? $this->params['url']['is_hide_unauthorized_ip'] : $system_parameter[0][0]['is_hide_unauthorized_ip'];

        $this->set('is_hide_unauthorized_ip', $is_hide_unauthorized_ip);

        if (!empty($this->params['pass'][0])) {
            $rate_type = $this->params['pass'][0];
            if ($rate_type == 'all') {
                $this->set('rate_type', 'all');
            } elseif ($rate_type == 'spam') {
                $this->set('rate_type', 'spam');
            } else {
                $this->set('rate_type', 'all');
            }
        } else {
            $this->set('rate_type', 'all');
        }

        extract($this->get_start_end_time());

        $dateStart = $start;
        $dateEnd = $end;

        $this->set('start', $dateStart);
        $this->set('end', $dateEnd);
    }

    function init_query()
    {
        $this->set('all_carrier', $this->Cdr->findClient());
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('all_rate_table', $this->Cdr->find_all_rate_table());
        $this->set('currency', $this->Cdr->find_currency1());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());
        $this->set('all_host', $this->Cdr->find_all_resource_ip());
        $this->set('cdr_field', $this->Cdr->find_field());

        if (!empty($_GET['ingress_alias'])) {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    public function downloadRates($didId)
    {
        $this->autoRender = false;
        $this->layout = false;

        $billingRule = $this->DidBillingRel->find('first', array(
            'fields' => array('DidBillingPlan.rate_table_id'),
            'conditions' => array(
                'DidBillingRel.id' => $didId,
                'client_trunk_id' => null
            ),
            'joins' => array(
                array(
                    'table' => 'did_billing_plan',
                    'alias' => 'DidBillingPlan',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidBillingPlan.id = DidBillingRel.client_billing_plan_id'
                    )
                )
            )
        ));

        if ($billingRule && !empty($billingRule)) {
            $this->loadModel('Export');
            $this->loadModel('Download');
            $sql = "SELECT code, code_name, country, rate FROM rate WHERE rate_table_id = {$billingRule['DidBillingPlan']['rate_table_id']}";
            $filename = Configure::read('database_export_path') . DS . 'rate_download' . DS . 'client_rate_' . uniqid() . '.csv';
            $exportResult = $this->Export->csv($sql, $filename);

            if ($exportResult['error'] == 1) {
                $this->Session->write('m', $this->Export->create_json(101, $exportResult['msg']));
                $this->redirect('order_new_number');
            }

            $this->Download->csv($filename);
        }

        exit;
    }

    public function ajaxGetSip()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {
            $callId = trim($_POST['callId']);
            $time = explode('-', trim($_POST['time']));
            $duration = trim($_POST['duration']);
            $switchIp = trim($_POST['switchIp']);
            $resTime = "{$time[0]}-{$time[1]}-{$time[2]} {$time[3]}";

            $data = array(
                'start' => $resTime,
                'callid' => $callId,
                'switch_ip' => $switchIp,
                'duration' => $duration
            );
//            die(var_dump($data));
            $apiUrl = Configure::read('pcap_url');
            $query_url = "{$apiUrl}/api/v1.0/create_query";
            $result_data = $this->postAPIData($query_url, $data);
            $arr = json_decode($result_data,true);

            if(isset($arr['error'])) {
                return json_encode(array('self_status' => 0, 'msg' => $arr['error']));
            }

            $this->loadModel('SipRequest');

            $requestData = array(
                'username'   => $_SESSION['sst_user_name'],
                'query_key'  => $arr['query_key'],
                'switch_ip'  => $switchIp,
                'call_id'    => $callId,
                'duration'   => $duration,
                'start_time' => strtotime($resTime),
                'date'       => date('Y-m-d H:i:s'),
                'client_id'  => $_SESSION['sst_client_id']
            );

            $this->SipRequest->save($requestData);

            return json_encode(array('self_status' => 1, 'msg' => 'Succeed'));
        }
    }

    private function postAPIData($url, $data)
    {
        $ch = curl_init();
        $timeout = 300;
        $headers =  array(
            'Content-Type: application/json'
        );
        $dataJson = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);

        array_push($this->curlQueries, array(
            'request'  => $dataJson . $url,
            'response' => $handles
        ));
        curl_close($ch);
        return $handles ? $handles : json_encode(array('error' => 'Could not establish connection'));
    }

    private function checkQueryResults($data)
    {
        $apiUrl = Configure::read('pcap_url');
        $ch = curl_init();
        $timeout = 300;
        $headers = array(
            'Content-Type: application/json'
        );
        $dataJson = json_encode($data);
        $url = "{$apiUrl}/api/v1.0/show_query";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);

        curl_close($ch);
        return $handles;
    }
}