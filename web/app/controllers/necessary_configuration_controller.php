<?php

class NecessaryConfigurationController extends Controller
{

    var $name = "NecessaryConfiguration";
    var $helpers = array('javascript', 'html', 'form', 'Session', 'App', "AppCommon", 'Xpaginator', 'Xform', 'AppImportExportLog', 'AppCurrs');
    var $components = array('RequestHandler');
    var $uses = array('Curr', 'Codedeck', 'ImportExportLog', 'Mailtmp', 'Systemparam', 'Paymentterm', 'ServerConfig', 'LrnSetting');
    var $is_auto_upload = false;

    public function beforeFilter()
    {
        Configure::load('myconf');
        $login_type = $this->Session->read('login_type');
        if ($login_type != 1)
            $this->redirect('/home/logout');
    }

    public function index()
    {
        $this->redirect("currs");
    }

    public function currs($user_id = "")
    {
        if (!$user_id)
        {
            $user_id = $this->Session->read('sst_user_id');
        }
        if ($this->RequestHandler->ispost())
        {
            if (!isset($this->data['Curr']['active']))
            {
                $this->data['Curr']['active'] = "";
            }
            $code = $this->data['Curr']['code'];
            if ($this->check_curr_code($code, ''))
            {
                if ($this->Curr->add($this->data))
                {
                    $this->Curr->create_json_array('', 201, __('The Currency [%s] is created successfully!', true, $code));
                }
                else
                {
                    $this->Curr->create_json_array('', 101, __('Fail to create Currency.', true));
                }
            }
            else
            {
                $this->Curr->create_json_array('', 101, 'The currency [%s] is already in use!');
            }
            $this->Session->write('m', Curr::set_validator());
        }
        $this->paginate['fields'] = Array(
            'currency_id', 'code', 'active', 'update_by',
            '(select count(rate_table_id) from rate_table where currency_id = "Curr"."currency_id")::float AS "Curr__rates"',
            '(select rate from currency_updates where currency_id = "Curr"."currency_id" and modify_time=(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id")) as "Curr__rate"',
            '(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id") as "Curr__last_modify"',
            '(select count(rate_table_id) from rate_table where currency_id ="Curr"."currency_id") as "Curr__usage"'
        );
        $this->data = $this->paginate('Curr');
        $data_count = $this->Curr->query("SELECT count(*) as sum FROM currency where active = true limit 1");
        $this->set('data_count', $data_count[0][0]['sum']);
        $this->set('user_id', $user_id);

        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);
    }

    function check_curr_code($code, $id)
    {
        $id_where = '';
        if (!empty($id))
        {
            $id_where = "and  currency_id<>$id";
        }
        $list = $this->Curr->query("select  code   from  currency   where  code='$code'  $id_where");
        if (!empty($list[0][0]['code']))
        {
            return false;
        }
        else
        {

            return true;
        }
    }

    public function codes_deck_default($code_deck_id)
    {
        if (!$this->RequestHandler->isPost())
            $this->redirect("codes_deck/{$code_deck_id}");
        $default_file_type = $this->params['form']['default_file_type'];
        switch ($default_file_type)
        {
            case 1:
                $upload_file_name = "a-z.csv";
                break;
            case 2:
                $upload_file_name = "us.csv";
                break;
            case 3:
                $upload_file_name = "ocn_lata.csv";
                break;
            default : $this->redirect("codes_deck/{$code_deck_id}");
        }
        $upload_file = WWW_ROOT . "example/default_code_deck/" . $upload_file_name;
        $model_name = "Code";
        $this->params['form']['with_headers'] = 'on';
        $this->params['form']['duplicate_type'] = 0;
        $this->params['form']['myfile_filename'] = $upload_file_name;
        $this->is_auto_upload = true;
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_PROCESSING, '', array("code_deck_id" => $code_deck_id));
        $this->redirect("mailtmp/{$_SESSION['sst_user_id']}");
    }

    public function codes_deck($code_deck_id, $user_id = "")
    {
        if (!$user_id)
            $user_id = $this->Session->read('sst_user_id');
        $this->set('user_id', $user_id);
        $code_deck_info = $this->Codedeck->findByCodeDeckId($code_deck_id);
        $this->set('code_deck_info', $code_deck_info);
        $example_file = "code_deck";
        $this->set('example_file', $example_file);
        $this->set('a_z_code_deck_id', $code_deck_id);
        $this->set('user_id', $user_id);
        $this->_upload("Code", array("code_deck_id" => $code_deck_id), $this->webroot . "necessary_configuration/codes_deck/" . $code_deck_id . "/{$user_id}");
    }

    function _upload($model_name, $save_ext_attributes = array(), $back_url = '')
    {
        if ($this->RequestHandler->isPost())
        {
            ini_set("max_execution_time", "3600");
            $this->_do_upload($model_name, $save_ext_attributes); #upload
            $this->set('statistics', $this->statistics);
        }
    }

    function _do_upload($model_name, $save_ext_attributes = array())
    {
        $this->_catch_exception_msg(array('NecessaryConfigurationController', '_do_upload_impl'), $model_name, $save_ext_attributes);
    }

    function _do_upload_impl($model_name, $save_ext_attributes = array())
    {
        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';

        if (!isset($_POST['flg']) || empty($_POST['flg']))
        {
            $_POST['flg'] = "myfile";
        }
        $guid = $_POST["{$_POST['flg']}_guid"];

        $upload_file = $path . DS . trim($guid) . ".csv";
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_PROCESSING, '', $save_ext_attributes);
        //	$this->_process($model,$upload_file,$save_ext_attributes);# process  upload  file
    }

    function upload_type_array($model)
    {
        $arr = array(
            'Ingress' => '1',
            'Egress' => '2',
            'ResourceIp' => '3',
            'ResourceDirection' => '4',
            'ResourceTranslation' => '5',
            'DigitTranslation' => '6',
            'ResourceBlock' => '7',
            'JurisdictionUpload' => '8',
            'Code' => '9',
            'RateTable' => '10',
            'Productitem' => '11',
            'Route' => '12',
            'DidSpecialCode' => '19',
            'DID' => '13',
            'Payment' => '15',
            'Invoice' => '16',
            'ResourceNextRouteRule' => '17',
            'ResourceReplaceAction' => '18',
            'UsOcnLata' => '20',
            'BlockOnly' => '21',
            'RandomAniGeneration' => '22',
        );

        if (isset($arr[$model]))
        {
            return $arr[$model];
        }
        else
        {

            return null;
        }
    }

    function _log($model_name, $upload_file, $status = ImportExportLog::STATUS_SUCCESS, $error_file = '', $save_ext_attributes = array())
    {
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id']))
        {
            $user_id = $_SESSION ['sst_user_id'];
        }
        $export_log = new ImportExportLog();
        $data = array();
        $data ['ImportExportLog'] = array();
//        $data ['ImportExportLog']['status']= $status;
        if (isset($this->statistics['log_id']))
        {
            $id = (int) $this->statistics['log_id'];
        }
        else
        {
            $id = 0;
        }
        if ($id <= 0)
        {
            $data ['ImportExportLog']['ext_attributes'] = array();
            $data ['ImportExportLog']['ext_attributes']['save_ext_attributes'] = $save_ext_attributes;
            $data ['ImportExportLog']['ext_attributes']['rollback_on_error'] = $this->_is_rollback_on_error();
            $data ['ImportExportLog']['ext_attributes']['with_headers'] = $this->_is_with_headers();
            $data ['ImportExportLog']['duplicate_type'] = $this->_get_duplicate_type();
            //$data ['ImportExportLog']['auto_enddate'] = isset($this->params['form']['auto_enddate']) && $this->params['form']['auto_enddate'] == '0' ? 0 : 1;
            //$data ['ImportExportLog']['custom_end_date'] = isset($this->params['form']['is_custom_enddate']) && $this->params['form']['is_custom_enddate'] != '0' ? $this->params['form']['custom_date'] : NULL;
            $data ['ImportExportLog']['error_rollback'] = $this->_is_rollback_on_error();
            $data ['ImportExportLog']['time'] = gmtnow();
            $data ['ImportExportLog']['obj'] = $model_name;
            $data ['ImportExportLog']['file_path'] = $upload_file;
            if (empty($error_file))
            {
                $error_file = $upload_file . '.error';
                if ($this->is_auto_upload)
                {
                    $error_file = APP . "/tmp/upload/csv/" . time() . '_' . uniqid() . ".csv.error";
                }
                new File($error_file, true, 0777);
            }
            $data ['ImportExportLog']['error_file_path'] = $error_file;
            $data ['ImportExportLog']['user_id'] = $user_id;
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = $this->upload_type_array($model_name);
            $data ['ImportExportLog']['upload_table'] = strtolower($model_name) . time();
            $data ['ImportExportLog']['foreign_id'] = empty($save_ext_attributes) ? "0" : intval($save_ext_attributes);
            $data ['ImportExportLog']['foreign_name'] = $this->get_foreign_name($model_name, $save_ext_attributes);
            $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";

            if (!empty($save_ext_attributes))
            {
                foreach ($save_ext_attributes as $key => $value)
                {
                    $data ['ImportExportLog']['foreign_id'] = intval($value);
                }
            }
        }
        else
        {
            $data ['ImportExportLog']['id'] = $id;
            $data ['ImportExportLog']['finished_time'] = gmtnow();
            if ($this->statistics['failure'] > 0)
            {
                $data ['ImportExportLog']['error_file_path'] = $error_file;
            }
            else
            {
                $data ['ImportExportLog']['error_file_path'] = '';
            }
        }


        //pr($export_log);

        $export_log->save($data);
        $this->statistics['log_id'] = $export_log->id;
        //$export_log->query("UPDATE import_export_logs set auto_enddate = {$data ['ImportExportLog']['auto_enddate']} WHERE id = {$export_log->id}");
        //pr($this->statistics);
        //$this->_exe_upload_shell($model_name);
//        $this->_exec_script($export_log->id, $save_ext_attributes);
    }

    function _catch_exception_msg($callback, $params = array())
    {
        $op = func_get_args();
        array_shift($op);
        $error_msg_name = 'exception_msg';
        if (is_array($params) && isset($params ['exception_msg_name']) && !empty($params ['exception_msg_name']))
            $error_msg_name = $params ['exception_msg_name'];
        $this->_set_multi_string_view_vars($error_msg_name, '');

        try
        {
            return call_user_func_array($callback, $op);
            //$this->wrap_call_user_func_array($callback, $op);
        }
        catch (Exception $e)
        {
            $this->_set_multi_string_view_vars($error_msg_name, $e->getMessage());
        }
    }

    function _set_multi_string_view_vars($one, $two = null)
    {
        $data = array();
        if (is_array($one))
        {
            if (is_array($two))
            {
                $data = array_combine($one, $two);
            }
            else
            {
                $data = $one;
            }
        }
        else
        {
            $data = array($one => $two);
        }
        foreach ($data as $name => $value)
        {
            if ($two === null && is_array($one))
            {
                if (!isset($this->viewVars[Inflector::variable($name)]))
                    $this->viewVars[Inflector::variable($name)] = '';
                if (!empty($this->viewVars[Inflector::variable($name)]))
                    $this->viewVars[Inflector::variable($name)] .= "<br/>";
                $this->viewVars[Inflector::variable($name)] .= $value;
            } else
            {
                if (!isset($this->viewVars[$name]))
                    $this->viewVars[$name] = '';
                if (!empty($this->viewVars[$name]))
                    $this->viewVars[$name] .= "<br/>";
                $this->viewVars[$name] .= $value;
            }
        }
    }

    function _is_rollback_on_error($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.ext_attributes.rollback_on_error');
        }
        if (isset($this->params['form']['rollback_on_error']) && $this->params['form']['rollback_on_error'] == 'on')
        {
            return true;
        }
        return false;
    }

    function _is_with_headers($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.ext_attributes.with_headers');
        }
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            return true;
        }
        return false;
    }

    function _get_duplicate_type($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.duplicate_type');
        }
        $duplicate_type = strtolower(trim($this->params['form']['duplicate_type']));

        if (!in_array($duplicate_type, array('ignore', 'overwrite', 'delete', 'delete all', 'delete_all')))
        {
            $duplicate_type = 'ignore';
        }
        return $duplicate_type;
    }

    function get_foreign_name($model_name, $save_ext_attributes)
    {

        if (empty($save_ext_attributes) || count($save_ext_attributes) == 0)
        {
            return '';
        }
        else
        {
            foreach ($save_ext_attributes as $key => $value)
            {
                $id = $value;
            }
        }
        App::import("Model", $model_name);
        $model = new $model_name();

        if (method_exists($model, 'get_foreign_name'))
        {
            $id = intval($id);
            $list = $model->get_foreign_name($id);
            if (!empty($list[0][0]['name']))
            {
                return $list[0][0]['name'];
            }
        }
        else
        {

            return '';
        }
    }

    public function mailtmp($user_id = "")
    {
        $this->pageTitle = "Switch/Mail Template";
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];
        if ($this->RequestHandler->isPost())
        {
            if ($this->Mailtmp->save($this->data))
            {
                $this->Mailtmp->create_json_array('', 201, __('configmailtmpsuc', true));
            }
            else
            {
                $this->Mailtmp->create_json_array('', 101, __('configmailtmpfail', true));
            }
            $this->Session->write('m', Mailtmp::set_validator());
            $this->redirect("/necessary_configuration/setup_billing/$user_id");
        }

        $mail_senders = $this->Mailtmp->find_mail_senders();
        $this->set('mail_senders', $mail_senders);

        $this->set('tmp', $this->Mailtmp->query("select * from mail_tmplate"));
        $this->set('user_id', $user_id);
        $this->set('is_necessary', 1);

        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);

        $this->set('mail_template_arr',$this->Mailtmp->get_mail_template_arr());

        $mail_data = $this->Mailtmp->query("select * from mail_tmplate limit 1");
        $this->data = $mail_data[0][0];

        $this->render("/mailtmps/mail");
    }

    public function setup_billing($user_id = "")
    {
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];
        $billing_sql = "select paypal_account,stripe_account,sys_id from system_parameter limit 1";
        $data = $this->Systemparam->query($billing_sql);
        $this->set('data', $data);

        $this->set('user_id', $user_id);
        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);

        if (!$data[0][0]['paypal_account'] || !$data[0][0]['stripe_account'])
        {
            $this->redirect("/necessary_configuration/setup_mail_config/$user_id");
        }
        if ($this->RequestHandler->ispost())
        {
            $save_data['sys_id'] = $data[0][0]['sys_id'];
            $save_data['paypal_account'] = $this->params['form']['paypal_account'];
            $save_data['stripe_account'] = $this->params['form']['stripe_account'];
            $result = $this->Systemparam->save($save_data);
            if ($result === false)
            {
                $this->Systemparam->create_json_array('', 101, __('failed', true));
                $this->Session->write('m', Systemparam::set_validator());
            }
            else
            {
                $this->Systemparam->create_json_array('', 201, __('The account is created successfully!', true));
                $this->Session->write('m', Systemparam::set_validator());
                //$this->redirect("/necessary_configuration/setup_mail_config/$user_id");
            }
        }
    }

    public function setup_mail_config($user_id = "")
    {
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];

        $data_sql = "select smtphost,smtpport,emailusername,emailpassword,loginemail,fromemail,emailname,smtp_secure,sys_id from system_parameter limit 1";
        $data = $this->Systemparam->query($data_sql);
        if ($this->RequestHandler->ispost())
        {
            $save_data = $this->data;
            $save_data['sys_id'] = $data[0][0]['sys_id'];
            $result = $this->Systemparam->save($save_data);
            if ($result === false)
            {
                $this->Systemparam->create_json_array('', 101, __('failed', true));
                $this->Session->write('m', Systemparam::set_validator());
            }
            else
            {
                $this->Systemparam->create_json_array('', 201, "The mail server is created successfully!");
                $this->Session->write('m', Systemparam::set_validator());
                $this->redirect("/necessary_configuration/setup_payment_term/$user_id");
            }
        }

        $this->set('data', $data);
        $secure_arr = array(
            0 => '',
            1 => 'TLS',
            2 => 'SSL'
        );

        $this->set('secure_arr', $secure_arr);
        if (in_array("", $data[0][0]))
        {
            $this->redirect("/homes/init_login_url/$user_id/1");
        }

        $this->set('user_id', $user_id);
        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);
    }

    public function setup_payment_term($user_id = "")
    {
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];
        $sql = "select payment_term_id,name,type,days,grace_days,notify_days,more_days,finance_rate,"
                . "(select count(client_id) from client where client.payment_term_id=payment_term.payment_term_id) as clients "
                . "from payment_term ";
        $data = $this->Paymentterm->query($sql);
        $this->set('mydata', $data);
        $this->set('user_id', $_SESSION['sst_user_id']);
        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);
    }

    public function setup_voip_gateway($user_id = "")
    {
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];
        if ($this->RequestHandler->ispost())
        {
            $save_data = $this->data;
//            $save_data['sys_id'] = $data[0][0]['sys_id'];
            $result = $this->ServerConfig->save($save_data);
            if ($result === false)
            {
                $this->ServerConfig->create_json_array('', 101, __('failed', true));
                $this->Session->write('m', ServerConfig::set_validator());
            }
            else
            {
                $gate_way_id = $this->ServerConfig->getLastInsertID();
                $this->ServerConfig->create_json_array('', 201, __('succeed', true));
                $this->Session->write('m', ServerConfig::set_validator());
                $this->redirect("/switch_profiler/index/$gate_way_id/1");
            }
        }

        $this->set('user_id', $_SESSION['sst_user_id']);
        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);
    }

    public function setup_lrn($user_id = "")
    {
        $this->loadModel('LrnSetting');
        $this->loadModel('LrnItem');
        if (!$user_id)
            $user_id = $_SESSION['sst_user_id'];
        $strategies = array('Topdown', 'Round Robin', 'Minimal PDD');
        $this->set('strategies', $strategies);
        $this->set('user_id', $_SESSION['sst_user_id']);
        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);

        if ($this->RequestHandler->ispost())
        {
            $lrn_setting_data = $this->params['form']['lrn'];
            $this->LrnSetting->begin();
            $flg = $this->LrnSetting->save($lrn_setting_data);
            if ($flg === false)
            {
                $this->LrnSetting->rollback();
                $this->ServerConfig->create_json_array('', 101, __('failed', true));
                $this->Session->write('m', LrnSetting::set_validator());
                $this->redirect("setup_lrn/{$user_id}");
            }
            $lrn_id = $this->LrnSetting->getLastInsertID();
            $lrn_item_data = $this->params['form']['lrnitem'];
            $lrn_item_data[0]['group_id'] = $lrn_id;
            $lrn_item_data[1]['group_id'] = $lrn_id;
            $item_flg = $this->LrnItem->saveAll($lrn_item_data);
            if ($item_flg === false)
            {
                $this->LrnSetting->rollback();
                $this->LrnSetting->create_json_array('', 101, __('failed', true));
                $this->Session->write('m', LrnSetting::set_validator());
                $this->redirect("setup_lrn/{$user_id}");
            }
            $this->LrnSetting->commit();
            $this->LrnSetting->create_json_array('', 201, __('succeed', true));
            $this->Session->write('m', LrnSetting::set_validator());
            $this->redirect("/necessary_configuration/setup_jurisdiction/$user_id");
        }
    }

    public function setup_jurisdiction($user_id = "")
    {
        if (!$user_id)
            $user_id = $this->Session->read('sst_user_id');
        $this->set('user_id', $user_id);
        $this->set('type', 5);
        $this->set('example_file', 'jurisdiction');
        $this->pageTitle = "Upload/Jur Country";
        $this->_upload("JurisdictionUpload", array(), $this->webroot . "jurisdictionprefixs/view");
        $upload_file = $this->get_ftp_file('jurisdiction');
        if (!$upload_file)
            $upload_file = '';
        $this->set('upload_file',$upload_file);

        //        code deck
        $code_deck_exist = $this->Curr->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->Curr->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $this->set('a_z_code_deck_id', $a_z_code_deck_id);

    }

    public function jurisdiction_default()
    {
        if (!$this->RequestHandler->isPost())
            $this->redirect("setup_jurisdiction");
        $upload_file = $this->params['form']['upload_default_file'];
//        die(var_dump($upload_file));
        if (!$upload_file)
        {
            $this->Session->write('m', $this->LrnSetting->create_json(101, __('ftp server failed,you may upload you own.!', true)));
            $this->redirect("/necessary_configuration/setup_jurisdiction/".$this->Session->read('sst_user_id'));
        }
        $model_name = "JurisdictionUpload";
        $this->params['form']['with_headers'] = 'on';
        $this->params['form']['duplicate_type'] = 0;
        $this->params['form']['myfile_filename'] = Configure::read('storage_server.jurisdiction_file_name');
        $this->is_auto_upload = true;
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_PROCESSING, '', array());
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=jurisdiction.csv');
        header('Pragma: no-cache');
        readfile($upload_file);
//        $this->redirect("/homes/init_login_url/{$_SESSION['sst_user_id']}/1");
    }

    public function get_ftp_file($type = '')
    {
//        Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
        $this->loadModel('FtpServerLog');
        switch ($type)
        {
            case "jurisdiction":
                $file_name = Configure::read('storage_server.jurisdiction_file_name');
                $file_dir = Configure::read('storage_server.ftp_jurisdiction_dir');
                break;
            default :
                $file_name = "";
                $file_dir = "";
        }
        if (!$file_name)
            return '';

        $ftp_dir = Configure::read('storage_server.ftp_dir');
        $ftp_ip = Configure::read('storage_server.ip');
        $ftp_port = Configure::read('storage_server.port');
        $ftp_user = Configure::read('storage_server.user');
        $storage_server_ftp_pwd = Configure::read('storage_server.password');
        $ftp_password = str_replace("\"", "", $storage_server_ftp_pwd);
        // define some variables 
        $local_file = $path = APP . 'tmp' . DS . 'ftp' . DS . $file_name;
//        $server_file = $ftp_dir. $file_dir. "/" . $file_name;
        $server_file = $file_dir. "/" . $file_name;
        // set up basic connection
        $conn_id = ftp_connect($ftp_ip, $ftp_port);
        ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 1000);


        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$ftp_ip}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$ftp_ip}", "Fail");
        }

        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user, $ftp_password);
        ftp_pasv($conn_id, true);

        if ($login_result)
            $this->FtpServerLog->insert_log("AUTH {$ftp_user}", "SUCCESS");
        else
            $this->FtpServerLog->insert_log("AUTH {$ftp_user}", "Fail");
        try
        {
            ftp_get($conn_id, $local_file, $server_file, FTP_ASCII);
        }
        catch (Exception $e)
        {
            $this->FtpServerLog->insert_log("GET {$server_file}", "Fail");
            ftp_close($conn_id);
            return '';
        }
        $this->FtpServerLog->insert_log("GET {$server_file}", "SUCCESS");
        ob_clean();
        // close the connection
        ftp_close($conn_id);
        return $local_file;
    }

}
