<?php 

class DetectionsController extends AppController
{
    var $name = "Detections";
    var $uses = array('FraudDetection','FraudDetectionLog','FraudDetectionLogDetail');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('fraud_detection');
    }

    public function init_fraud_detection()
    {
        $this->set('email_to_arr',$this->FraudDetection->get_email_to_arr());
    }

    public function fraud_detection()
    {
        $this->pageTitle = __('Fraud Detection',true);
        $this->init_fraud_detection();
        $conditions = array(
        );
        $get_rule_name = $this->_get('rule_name');
        if($get_rule_name)
            $conditions = "FraudDetection.rule_name like '%" . trim($this->_get('rule_name')) . "%'";
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('rule_name' => 'asc');
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
            'fields' => array(
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('FraudDetection');
        if(empty($this->data) && !$get_rule_name)
        {
            $model_name = "FraudDetection";
            $msg = "Fraud Detection";
            $add_url = "add_fraud_detection";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }

    public function add_fraud_detection($encode_id = '')
    {
        $this->init_fraud_detection();
        $this->loadModel('Mailtmp');
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
        $this->set('tmp',$this->FraudDetection->get_mail_template());
        if($this->RequestHandler->ispost())
        {
            $fraud_detection = $this->data;
            $fraud_detection['ingress_ids'] = ($fraud_detection['select_all'] == '1') ? NULL :implode(',',$this->params['form']['ingress_trunks']);
            $fraud_detection['update_by'] = $_SESSION['sst_user_name'];
            $fraud_detection['update_on'] = date('Y-m-d H:i:sO');
            $fraud_detection['active'] = 1;
            unset($fraud_detection['select_all']);
            if($encode_id)
                $fraud_detection['id'] = base64_decode($encode_id);
            $flg = $this->FraudDetection->save($fraud_detection);
            if ($flg === false)
            {
                $this->Session->write('m', $this->FraudDetection->create_json(101, __('Save Failed!', true)));
                $this->redirect('add_fraud_detection/'.$encode_id);
            }
            $mail_tmp = $this->params['form']['mail_template'];
            $mail_tmp['id'] = 1;
            $this->Mailtmp->save($mail_tmp);
            if ($encode_id){
                $action = 'modified';
            }
            else {
                $action = 'created';
            }
            $this->Session->write('m', $this->FraudDetection->create_json(201, __('The fraud detection [' . $fraud_detection['rule_name'] . '] is ' . $action . ' successfully!', true,array(__('fraud detection',true),$fraud_detection['rule_name']))));
            $this->redirect('fraud_detection');
        }
        $selected_ingress = array();
        $selected_all = false;
        if($encode_id)
        {
            $id = base64_decode($encode_id);
            $fraud_detection_info = $this->FraudDetection->findById($id);
            $this->data = $fraud_detection_info['FraudDetection'];
            if($this->data['ingress_ids']){
                $selected_ingress = explode(',',$this->data['ingress_ids']);
            }
            $selected_all = empty($selected_ingress) ? true : false;
        }
        $this->set('ingresses_info',$this->FraudDetection->get_client_ingress_group());
        $this->set('selected_ingress',$selected_ingress);
        $this->set('selected_all',$selected_all);
    }

    public function delete_fraud_detection($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($encode_id);
        $fraud_detection = $this->FraudDetection->findById($id);
        $flg = $this->FraudDetection->del($id);
        if($flg === false)
        {
            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Delete Failed!', true)));
            $this->redirect('fraud_detection');
        }
        $this->Session->write('m', $this->FraudDetection->create_json(201, __('The %s[%s] is deleted successfully!', true,array(__('fraud detection',true),$fraud_detection['FraudDetection']['rule_name']))));
//        $this->Session->write('m', $this->FraudDetection->create_json(201, __('Delete succ    essfully!', true)));
        $this->redirect('fraud_detection');
    }


    public function fraud_detection_log()
    {
        $this->pageTitle = __('Fraud Detection Log',true);
        $conditions = array(
        );
        $get_rule_name = $this->_get('rule_name');
        if($get_rule_name)
            $conditions = "FraudDetection.rule_name like '%" . trim($this->_get('rule_name')) . "%'";
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('create_on' => 'DESC');
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
            'fields' => array(
                'FraudDetectionLog.id','FraudDetectionLog.create_on','FraudDetectionLog.create_by','FraudDetectionLog.status',
                'FraudDetectionLog.finish_time','FraudDetection.rule_name',
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'FraudDetection',
                    'table' => 'fraud_detection',
                    'type'  => 'inner',
                    'conditions' => array(
                        'FraudDetection.id = FraudDetectionLog.fraud_detection_id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('FraudDetectionLog');
        $this->set('create_by_arr',$this->FraudDetectionLog->get_create_by_arr());
        $this->set('status_arr',$this->FraudDetectionLog->get_status_arr());
    }

    public function fraud_detection_log_detail($encode_log_id)
    {
        $this->pageTitle = __('Fraud Detection Log Detail',true);
        if(!$encode_log_id)
            $this->redirect('fraud_detection_log');
        $conditions = array(
            'FraudDetectionLog.id' => base64_decode($encode_log_id)
        );
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;
        $order_arr = array('FraudDetectionLogDetail.id' => 'asc');
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
            'fields' => array(
                'FraudDetectionLogDetail.ingress_id','FraudDetectionLogDetail.block_type','FraudDetectionLogDetail.limit_value',
                'FraudDetectionLogDetail.actual_value','FraudDetectionLogDetail.partner_email_msg','FraudDetectionLogDetail.partner_email_status',
                'FraudDetectionLogDetail.partner_email','FraudDetectionLogDetail.system_email_msg','FraudDetectionLogDetail.system_email_status',
                'FraudDetectionLogDetail.system_email','FraudDetectionLogDetail.is_block','FraudDetectionLogDetail.is_send_email',
                'FraudDetection.rule_name','Resource.alias','FraudDetection.ingress_ids','FraudDetection.email_to',
                'FraudDetection.hourly_minute','FraudDetection.hourly_revenue','FraudDetection.daily_minute','FraudDetection.daily_revenue'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'FraudDetectionLog',
                    'table' => 'fraud_detection_log',
                    'type'  => 'inner',
                    'conditions' => array(
                        'FraudDetectionLog.id = FraudDetectionLogDetail.fraud_detection_log_id'
                    ),
                ),
                array(
                    'alias' => 'FraudDetection',
                    'table' => 'fraud_detection',
                    'type'  => 'inner',
                    'conditions' => array(
                        'FraudDetection.id = FraudDetectionLog.fraud_detection_id'
                    ),
                ),
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type'  => 'left',
                    'conditions' => array(
                        'Resource.resource_id = FraudDetectionLogDetail.ingress_id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('FraudDetectionLogDetail');
        $selected_trunks = 'All';
        if(isset($this->data[0]['FraudDetection']['ingress_ids']) && $this->data[0]['FraudDetection']['ingress_ids']){
            $this->loadModel('prresource.Gatewaygroup');
            $ingress_ids = $this->data[0]['FraudDetection']['ingress_ids'];
            $sql = "SELECT alias FROM resource WHERE resource_id in ($ingress_ids)";
            $aliases = $this->Gatewaygroup->query($sql);
            $alias_arr = [];
            foreach($aliases as $alias){
                $alias_arr[] = $alias[0]['alias'];
            }
            $selected_trunks = implode('; ', array_filter($alias_arr));
        }
        $this->set('selected_trunks', $selected_trunks);
        $this->set('limit_values_fields', ['hourly_minute','hourly_revenue','daily_minute','daily_revenue']);
        $this->set('block_type_arr',$this->FraudDetectionLogDetail->get_block_type_arr());
    }

    public function judge_fraud_rule_name_unique($is_ajax = true)
    {
        if($is_ajax)
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $name = $this->_post('rule_name');
            $rule_id = $this->_post('rule_id');
            $conditions = array(
                'rule_name' => $name,
            );
            if($rule_id)
                $conditions['id != ?'] = $rule_id;
            $data = $this->FraudDetection->find('count',array(
                'conditions' => $conditions,
            ));
            echo json_encode($data);
        }
        else
        {
            if(empty($template_arr))
                return 1;
            $name = $template_arr['template_name'];
            if(!$name)
                return 1;
            $template_id = $template_arr['template_id'];
            $conditions = array(
                'name' => $name,
            );
            if($template_id)
                $conditions['resource_template_id != ?'] = $template_id;
            $data = $this->FraudDetection->find('count',array(
                'conditions' => $conditions,
            ));
            return $data;

        }
    }

    public function disable_fraud($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->FraudDetection->find('count',array('id' => $id));
        if (!$count)
        {
            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Illegal operation',true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => false
        );

        $rule_name = $this->FraudDetection->find(array('id' => $id))['FraudDetection']['rule_name'];

        if ($this->FraudDetection->save($update_arr) === false)
            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Disable failed',true)));
        else
            $this->Session->write('m', $this->FraudDetection->create_json(201, __('The rule name [' . $rule_name . '] is deactivated successfully!',true)));
        $this->redirect('fraud_detection');
    }


    public function enable_fraud($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->FraudDetection->find('count',array('id' => $id));
        if (!$count)
        {
            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Illegal operation',true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => true
        );

        $rule_name = $this->FraudDetection->find(array('id' => $id))['FraudDetection']['rule_name'];

        if ($this->FraudDetection->save($update_arr) === false)
            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Enable failed',true)));
        else{
            $this->Session->write('m', $this->FraudDetection->create_json(201, __('The rule name [' . $rule_name . '] is activated successfully!',true)));
        }
        $this->redirect('fraud_detection');
    }

    public function bad_number_detection() {
        $this->pageTitle = __('Bad ANI / DNIS Detection',true);

//        $data = $this->FraudDetection->query("SELECT * FROM bad_number_detection_rules");
//        $this->set('data', $data);
    }

    public function bad_number_detection_list() {

    }

    public function get_trunk_list()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $isIngress = $this->_post('is_ingress');
        if (isset($isIngress) && $isIngress == 1) {
            $trunks = $this->FraudDetection->query("SELECT resource_id, name, alias FROM resource WHERE ingress = '1'");
        } else {
            $trunks = $this->FraudDetection->query("SELECT resource_id, name, alias FROM resource WHERE egress = '1' AND active = 't'");
        }
        echo json_encode($trunks);
    }

    public function delete_bad_number_detection($encode_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($encode_id);
//        $name = $this->FraudDetection->query("SELECT name FROM bad_number_detection_rules WHERE id = '{$id}'");
//        $flg = $this->FraudDetection->query("DELETE FROM bad_number_detection_rules WHERE id = '{$id}'");
//        if($flg === false)
//        {
//            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Delete Failed!', true)));
//            $this->redirect('bad_number_detection');
//        }
//        $this->Session->write('m', $this->FraudDetection->create_json(201, __('The %s[%s] is deleted successfully!', true,array(__('fraud detection',true),$name[0][0]['name']))));
//        $this->Session->write('m', $this->FraudDetection->create_json(201, __('Delete succ    essfully!', true)));
        $this->redirect('bad_number_detection');
    }

    public function run_bad_number_detection($encode_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $id = base64_decode($encode_id);
        $startTime = date('Y-m-d');
        $current_time = date("Y-m-d H:i:s");
//        $rule = $this->FraudDetection->query("SELECT * FROM bad_number_detection_rules WHERE id = '{$id}'");
//        if ($rule) {
//            $this->FraudDetection->query("UPDATE bad_number_detection_rules SET last_executed = '{$current_time}' WHERE id = '{$id}'");
//            $trunkList = explode(',',$rule[0][0]['trunk_list']);
//            $apiHost = 'http://209.126.127.64:9090/api/';
//            $apiHost .= isset($rule[0][0]['target']) && $rule[0][0]['target'] == 0 ? 'GetANI' : 'GetDNIS';
//            if (isset($rule[0][0]['from_last']) && !empty($rule[0][0]['from_last'])) {
//                $startTime = date('Y-m-d', strtotime('-' . $rule[0][0]['from_last'] . ' minute'));
//            }
//            $apiUrl = $apiHost . '?start_time="' . $startTime . '"';
//
//            $postApiKeyValues = array(
//                'valid_attempts' => 'min_attempt',
//                'ip' => 'ip',
//                'p200' => 'code_200',
//                'p404' => 'code_404',
//                'p503' => 'code_503',
//                'p480' => 'code_480',
//                'p486' => 'code_486',
//                'p487' => 'code_487',
////                'p45' => 'code_45',
////                'asr',
////                'acd',
//            );
//
//            foreach ($postApiKeyValues as $k => $v) {
//                $postValue = $rule[0][0][$k];
//                if (isset($postValue) && !empty($postValue)) {
//                    $apiUrl .= '&' . $v . '=' . $postValue;
//                }
//            }
//
//            $apiResponse = file_get_contents($apiUrl);
//            $aniNumbers = json_decode($apiResponse);
//
//            if (!empty($aniNumbers) && !empty($trunkList)) {
//                foreach ($aniNumbers as $num) {
//                    if ($rule[0][0]['target'] == 0) {
//                        $number = $num->ani;
//                    } else {
//                        $number = $num->dnis;
//                    }
////                    $num->ani;
//                    foreach ($trunkList as $trunkId) {
//                        if ($rule[0][0]['block_type'] == 1) { // ingress
////                            $resource = $this->FraudDetection->query("SELECT name, alias, client_id FROM resource WHERE ingress = '1' AND resource_id = '{$trunkId}'");
////                            // ''
////                            $client_id = $resource[0][0]['client_id'];
//                            $this->FraudDetection->query("insert into resource_block (ingress_res_id, ani_prefix, create_time, action_type)
//                                                          values ('{$trunkId}', '{$number}', '{$current_time}', 5)");
//                        } elseif ($rule[0][0]['block_type'] == 2) { // egress
////                            $resource = $this->FraudDetection->query("SELECT name, alias, client_id FROM resource WHERE egress = '1' AND resource_id = '{$trunkId}'");
////                            $client_id = $resource[0][0]['client_id'];
//                            $this->FraudDetection->query("insert into resource_block (engress_res_id, ani_prefix, create_time, action_type)
//                                                          values ('{$trunkId}', '{$number}', '{$current_time}', 5)");
//                        } elseif ($rule[0][0]['block_type'] != 0) {
//                            $this->Session->write('m', $this->FraudDetection->create_json(101, __('Something went wrong!', true)));
//                            $this->redirect('bad_number_detection');
//                        }
////                        $this->FraudDetection->query("SELECT resource_id, name, alias, client_id FROM resource WHERE ingress = '1'");
////                        insert into resource_block ( resource_id, ani digits, block by, manual ) values ( trunk id, number to block , '<Bad Number Rule>',  )
//                    }
//
//                }
//                $this->Session->write('m', $this->FraudDetection->create_json(201, __('Numbers were blocked!', true)));
//                $this->redirect('bad_number_detection');
//            } else {
//                $this->Session->write('m', $this->FraudDetection->create_json(101, __('API did not return any number!', true)));
//                $this->redirect('bad_number_detection');
//            }
//        }
    }

    public function edit_bad_number_detection($encode_id) {
        $id = base64_decode($encode_id);
        if($this->RequestHandler->ispost()) {
            $trunkList = $_POST['trunk_list'];
            $trunk_list = implode(',',$trunkList);
            $name = $_POST['name'];
            $target = $_POST['target'];
            $ip = $_POST['ip'];
            $valid_attempts = $_POST['valid_attempts'];
            $block_type = $_POST['block_type'];
            $exec_every = $_POST['exec_every'];
            $from_last = $_POST['from_last'];

//            $sql = "UPDATE bad_number_detection_rules SET name='{$name}',target='{$target}',valid_attempts='{$valid_attempts}',ip='{$ip}',";
//            if (isset($_POST['p200'])) $sql .= "p200='{$_POST['p200']}',";
//            if (isset($_POST['p404'])) $sql .= "p404='{$_POST['p404']}',";
//            if (isset($_POST['p503'])) $sql .= "p503='{$_POST['p503']}',";
//            if (isset($_POST['p480'])) $sql .= "p480='{$_POST['p480']}',";
//            if (isset($_POST['p486'])) $sql .= "p486='{$_POST['p486']}',";
//            if (isset($_POST['p487'])) $sql .= "p487='{$_POST['p487']}',";
//            if (isset($_POST['p45'])) $sql .= "p45='{$_POST['p45']}',";
//            if (isset($_POST['asr'])) $sql .= "asr='{$_POST['asr']}',";
//            if (isset($_POST['acd'])) $sql .= "acd='{$_POST['acd']}',";
//            $sql .= "block_type='{$block_type}',trunk_list='{$trunk_list}',exec_every='{$exec_every}',from_last='{$from_last}' WHERE id = '{$id}'";
//            $this->FraudDetection->query($sql);

            $this->Session->write('m', $this->FraudDetection->create_json(201, __('Rule updated!', true)));
            $this->redirect('bad_number_detection');
        }
//        $rule = $this->FraudDetection->query("SELECT * FROM bad_number_detection_rules WHERE id = '{$id}'");
//        $this->set('data', $rule);
    }

    public function add_bad_number_detection() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        if($this->RequestHandler->ispost()) {
//            echo "<pre>";
//            die(var_dump($this->_post('trunk_list')));

            $trunkList = $_POST['trunk_list'];
            $trunk_list = implode(',',$trunkList);
            $name = $_POST['name'];
            $target = $_POST['target'];
            $ip = $_POST['ip'];
            $valid_attempts = $_POST['valid_attempts'];
            $block_type = $_POST['block_type'];
            $exec_every = $_POST['exec_every'];
            $from_last = $_POST['from_last'];

//            $sql = "INSERT INTO bad_number_detection_rules (name,target,valid_attempts,ip,";
//            if (isset($_POST['p200'])) $sql .= "p200,";
//            if (isset($_POST['p404'])) $sql .= "p404,";
//            if (isset($_POST['p503'])) $sql .= "p503,";
//            if (isset($_POST['p480'])) $sql .= "p480,";
//            if (isset($_POST['p486'])) $sql .= "p486,";
//            if (isset($_POST['p487'])) $sql .= "p487,";
//            if (isset($_POST['p45'])) $sql .= "p45,";
//            if (isset($_POST['asr'])) $sql .= "asr,";
//            if (isset($_POST['acd'])) $sql .= "acd,";
//            $sql .= "block_type,trunk_list,exec_every,from_last) VALUES ('{$name}','{$target}','{$valid_attempts}','{$ip}',";
//            if (isset($_POST['p200'])) $sql .= "'{$_POST['p200']}',";
//            if (isset($_POST['p404'])) $sql .= "'{$_POST['p404']}',";
//            if (isset($_POST['p503'])) $sql .= "'{$_POST['p503']}',";
//            if (isset($_POST['p480'])) $sql .= "'{$_POST['p480']}',";
//            if (isset($_POST['p486'])) $sql .= "'{$_POST['p486']}',";
//            if (isset($_POST['p487'])) $sql .= "'{$_POST['p487']}',";
//            if (isset($_POST['p45'])) $sql .= "'{$_POST['p45']}',";
//            if (isset($_POST['asr'])) $sql .= "'{$_POST['asr']}',";
//            if (isset($_POST['acd'])) $sql .= "'{$_POST['acd']}',";
//            $sql .= "'{$block_type}','{$trunk_list}','{$exec_every}','{$from_last}') RETURNING id";
//            $ret_id = $this->FraudDetection->query($sql);
//            $this->run_bad_number_detection(base64_encode($ret_id[0][0]['id']));
        }
    }

}
