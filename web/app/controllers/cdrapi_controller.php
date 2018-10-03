<?php

class CdrapiController extends AppController
{
    var $uses = array('Cdr', 'CdrApiExportLog', 'StorageConf', 'DefaultFields');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');

//    public function __construct()
//    {
//        parent::__construct();
//
//    }

    public function beforeFilter()
    {
        Cache::clear();
        Configure::load('myconf');
        $this->Session->write('executable', true);
        $this->Session->write('writable', true);

        if ($_SESSION['login_type'] != 1 && !in_array($this->params['action'], array('export_log', 'ajaxRequest', 'download', 'ajaxSaveFields'))) {
            $this->redirect_denied();
        }

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1) {
            return true;
        }

        parent::beforeFilter();

    }

    function ajaxRequest()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = false;

        if ($this->RequestHandler->isPost()) {
            $type = isset($_POST['output']) ? $_POST['output'] : 1;
            unset($_POST['output']);

            App::import('Vendor', 'RequestFactory', array('file' => 'api/cdr/class.request_factory.php'));

            $cdr = new RequestFactory();
            $res = $cdr->run($type, $_POST);

            ob_clean();
            echo $res;
        }
        exit;
    }

    public function export_log($type = 1)
    {
        $conditions = array(
            'CdrApiExportLog.type' => $type
        );

        if ($_SESSION['login_type'] !== 1) {
            $conditions['CdrApiExportLog.user_id'] = $_SESSION['sst_user_id'];
        }

        $data = $this->CdrApiExportLog->find('all', array(
            'fields' => array(
                'CdrApiExportLog.id', 'CdrApiExportLog.request_id', 'CdrApiExportLog.start_time', 'CdrApiExportLog.end_time', 'CdrApiExportLog.status',
                'CdrApiExportLog.create_on', 'CdrApiExportLog.completed_records', 'CdrApiExportLog.total_records', 'User.name'
            ),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'CdrApiExportLog.user_id = User.user_id'
                    )
                )
            ),
            'order' => 'CdrApiExportLog.id DESC'
        ));

        if (!empty($data)) {
            App::import('Vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));
            $callDetailReportAsync = new CallDetailReportAsync();

            foreach ($data as &$item) {
                if ($item['CdrApiExportLog']['status'] == 0 && !empty($item['CdrApiExportLog']['request_id'])) {
                    $result = $callDetailReportAsync->checkStatus($item['CdrApiExportLog']['request_id']);
                    if ($result) {
                        $decodedResult = json_decode($result, true);

                        if (isset($decodedResult['status'])) {
                            $item['CdrApiExportLog']['status'] = $decodedResult['status'] == 'Complete' ? 1 : 0;

                            if ($item['CdrApiExportLog']['status'] == 1) {
                                $this->CdrApiExportLog->save($item);
                            }
                        }
                    }
                }
            }
        }

        $this->set('data', $data);
        $this->set('statuses', $this->CdrApiExportLog->status);
        $this->set('type', $type);
    }

    function summary_reports()
    {
        $report_fields = array(
            'time',
            'termination_destination_number',
            'release_cause,pdd',
            'orig_call_duration',
            'call_duration',
            'ingress_id as ingress_name',
            'egress_id as egress_name',
            'origination_source_number',
            'origination_destination_number',
            'origination_source_host_name',
            'termination_source_host_name',
            'release_cause_from_protocol_stack',
            'binary_value_of_release_cause_from_protocol_stack',
            'answer_time_of_date',
            'origination_destination_host_name'
        );
        $this->initNewReport();
        $field = array();

        $this->set('report_fields', $report_fields);
        $this->set('cdr_field', $field);
        $this->set('defaultFields', $this->DefaultFields->getFields('summary_reports'));
    }

    function did_call_log()
    {
        $report_fields = array(
            'time',
            'termination_destination_number',
            'release_cause,pdd',
            'orig_call_duration',
            'call_duration',
            'ingress_id as ingress_name',
            'egress_id as egress_name',
            'origination_source_number',
            'origination_destination_number',
            'origination_source_host_name',
            'termination_source_host_name',
            'release_cause_from_protocol_stack',
            'binary_value_of_release_cause_from_protocol_stack',
            'answer_time_of_date',
            'origination_destination_host_name'
        );
        $this->initNewReport();
        $field = array();

        $this->loadModel('did.DidBillingRel');

        $dids = $this->DidBillingRel->find('all', array(
            'fields' => array('did'),
            'group' => array('did')
        ));
        $arrayDids = array();

        foreach ($dids as $did) {
            array_push($arrayDids, $did['DidBillingRel']['did']);
        }

        $this->loadModel('did.Did');
        $this->set('ingresses', $this->Did->get_ingress());
        $this->set('carriers', $this->Did->get_carriers());
        $this->set('report_fields', $report_fields);
        $this->set('cdr_field', $field);
        $this->set('dids', implode(',', $arrayDids));
        $this->set('defaultFields', $this->DefaultFields->getFields('did_call_log'));

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
        $this->set('mapping', $this->Cdr->code_based_mapping());

        if (!empty($_GET['ingress_alias'])) {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    public function download($encodedId)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = false;

        $id = base64_decode($encodedId);
        $item = $this->CdrApiExportLog->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));

        if ($item['CdrApiExportLog']['type'] != 3 && !empty($item['CdrApiExportLog']['request_id'])) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=' . $item['CdrApiExportLog']['filename'] . '.csv');
            header('Pragma: no-cache');
            ob_clean();

            App::import('vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));
            $CallDetailReportAsync = new CallDetailReportAsync();
            $CallDetailReportAsync->download($item['CdrApiExportLog']['request_id']);
        } else {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $item['CdrApiExportLog']['filename'] . '.csv');
            header('Pragma: no-cache');
            ob_clean();

            $stream = fopen("{$item['CdrApiExportLog']['ftp_directory']}/{$item['CdrApiExportLog']['filename']}.csv", 'r');

            while (!feof($stream)) {
                echo fread($stream, 4096);
                flush();
            }
        }
        exit;
    }

    public function code_based_report()
    {
        extract($this->get_start_end_time());

        $dateStart = &$start;
        $dateEnd = &$end;
        $_GET['group_select'] = isset($_GET['group_select']) ? $_GET['group_select'] : array();
        $group = trim(implode(',', $_GET['group_select']), ',');
        $group = trim("orig_code,{$group}", ',');
        $ingresses = isset($_GET['ingress_alias']) && $_GET['ingress_alias'] ? implode(',', $_GET['ingress_alias']) : 'NULL';
        $egresses = isset($_GET['egress_alias']) && $_GET['egress_alias'] ? implode(',', $_GET['egress_alias']) : 'NULL';

        // define fields
        $fields =  $this->Cdr->code_based_fields();
        $saved_fields = $this->Cdr->query("SELECT code_based_fields FROM users WHERE user_id = '{$this->Session->read('sst_user_id')}'");
        if(isset($_GET['query']['fields']) && !empty($_GET['query']['fields'])){
            $fields = $_GET['query']['fields'];
        }elseif(!empty($saved_fields)){
            $fields = explode(',', $saved_fields[0][0]['code_based_fields']);
        }
        $this->set('fields', array_merge( ['country code'], $fields));

        if (!isset($_GET['query']['output']) || (isset($_GET['query']['output']) && $_GET['query']['output'] == 'web')) {

            App::import('Vendor', 'Report', array('file' => 'api/cdr/class.report.php'));

            $report = new Report();
            $data = array(
                'start_time' => strtotime($dateStart),
                'end_time' => strtotime($dateEnd),
                'group' => $group,
                'fields' => $fields
            );

            if ($ingresses && $ingresses != 'NULL') {
                $data['ingress_id'] = $ingresses;
            }

            if ($egresses && $egresses != 'NULL') {
                $data['egress_id'] = $egresses;
            }

            $result = $report->process($data, false);
            $this->set('data', $result);

        } elseif (isset($_GET['query']['output']) && $_GET['query']['output'] == 'csv') {
            $saveResult = $this->CdrApiExportLog->save(array(
                'user_id' => $_SESSION['sst_user_id'],
                'filename' => uniqid('code_based_report_'),
                'start_time' => strtotime($dateStart),
                'end_time' => strtotime($dateEnd),
                'ftp_directory' => Configure::read('database_export_path'),
                'type' => 3
            ));

            if ($saveResult) {
                $id = $this->CdrApiExportLog->getLastInsertId();
                $php_path = Configure::read('php_exe_path');
                $fields = escapeshellarg(serialize($fields));
                $cmd = "{$php_path} " . ROOT . "/cake/console/cake.php cdr_api_export {$id} {$_SESSION['sst_user_name']} {$_SESSION['sst_password']} {$ingresses} {$egresses} {$group} {$fields} > /dev/null &";
                shell_exec($cmd);

                $this->Session->write('m', $this->CdrApiExportLog->create_json(201, 'Request created successfully!'));
            } else {
                $this->Session->write('m', $this->CdrApiExportLog->create_json(101, 'Failed!'));
            }
            $this->redirect('/cdrapi/export_log/3');
        }
        $this->initNewReport();
    }

    public function save_fields(){
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $flg = $this->Cdr->query("UPDATE users SET code_based_fields = '" . implode(',', $_POST['query-fields']) . "' WHERE user_id = '{$this->Session->read('sst_user_id')}'");
            if ($flg !== NULL) {
                echo 'Success!';
            } else {
                echo 'Failed!';
            }
        }
        exit;
    }

    public function summary($type = 1)
    {
        $this->initNewReport();
        $this->set('type', $type);
        $this->set('defaultFields', $this->DefaultFields->getFields($type == 1 ? 'summary' : 'summary_term'));
    }

    public function qos_summary($type = 1)
    {
        $this->initNewReport();
        $this->set('type', $type);
        $this->set('defaultFields', $this->DefaultFields->getFields($type == 1 ? 'qos_summary' : 'qos_summary_term'));
    }

    public function disconnect_causes($type = 1)
    {
        $this->initNewReport();
        $this->set('type', $type);
        $this->set('defaultFields', $this->DefaultFields->getFields($type == 1 ? 'disconnect_causes' : 'disconnect_causes'));
    }

    public function profit($type = 1)
    {
        $this->initNewReport();
        $this->set('type', $type);
        $this->set('defaultFields', $this->DefaultFields->getFields($type == 1 ? 'profit' : 'profit_term'));
    }

    public function daily_usage($type = 1)
    {
        $this->initNewReport();
        $this->set('type', $type);
        $this->set('defaultFields', $this->DefaultFields->getFields($type == 1 ? 'daily_usage' : 'daily_usage_term'));
    }

    public function location()
    {
        $this->initNewReport();
        $this->set('defaultFields', $this->DefaultFields->getFields('location'));
    }

    public function inout_report()
    {
        $this->initNewReport();
        $this->set('defaultFields', $this->DefaultFields->getFields('inout_report'));
    }

    function ajaxSaveFields()
    {
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($this->RequestHandler->isPost()) {
            $fields = implode(',', $_POST['fields']);
            $name = $_POST['report'];
            $result = $this->DefaultFields->save(array(
                'report_name' => $name,
                'fields' => $fields
            ));

            return $result ? 'true' : 'false';
        }
    }

    /**
     * @param int $type (0 - DID Client, 1 - DID Vendor)
     */
    public function did_report($type = 0)
    {
        $this->loadModel('did.DidBillingRel');

        $dids = $this->DidBillingRel->find('all', array(
            'fields' => array('egress_res_id'),
            'group' => array('egress_res_id')
        ));
        $vendorsArray = array();

        foreach ($dids as $did) {
            array_push($vendorsArray, $did['DidBillingRel']['egress_res_id']);
        }

        $this->initNewReport();
        $this->set('vendors', implode(',', $vendorsArray));
        $this->set('type', $type);
    }

    public function ajaxGetDids()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($this->RequestHandler->isPost()) {
            $data = $this->DefaultFields->query("SELECT did FROM did_billing_rel");
            $result = array();

            foreach ($data as $item) {
                array_push($result, $item[0]['did']);
            }
            $result = array_unique($result);
            $data = array();

            foreach ($result as $item) {
                array_push($data, $item);
            }

            echo json_encode($data);
        }
        exit;
    }
}
