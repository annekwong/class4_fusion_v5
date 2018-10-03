<?php

class SystemLogsController extends AppController
{

    var $name = 'SystemLogs';
    var $helpers = array();
    var $uses = array();
    var $components = array();

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }

    public function index($type = 1)
    {
        Configure::write('debug', 0);
        
        switch ($type)
        {
            case 1:
                $current_log = 'switch_update_log';
                $log_file    = Configure::read('logfile.switch_log_path') . DS . 'routing_data.log';
                $title       = 'Switch Update Log';
                break;
            case 2:
                $current_log = 'switch_error_log';
                $log_file    = Configure::read('logfile.switch_log_path'). DS . 'dnl_softswitch.log';
                $title       = 'Switch Error Log';
                break;
            case 3:
                $current_log = 'billing_error_log';
                $log_file    = Configure::read('logfile.switch_log_path'). DS . 'billing_server.log';
                $title       = 'Billing Error Log';
                break;
            case 4:
                $current_log = 'script_log';
                $log_file    = Configure::read('logfile.script_log'). DS . 'class4.log';
                $title       = 'Script Log';
                break;
            case 5:
                $current_log = 'web_log';
                $log_file    = WWW_ROOT . "upload" . DS . "log" . DS . "web.log";
                $title       = 'Web Log';
                break;
            default:
                $current_log = 'switch_update_log';
                $log_file    = Configure::read('logfile.switch_log_path') . DS . 'routing_data.log';
                $title       = 'Switch Update Log';
                break;
        }
        $this->pageTitle = "Configuration/" . $title;
        if (!file_exists($log_file)) {
            $error = 'Log File do not exist, Please check your configurations!';
        } else {
            $error = '';
        }
        $this->set('current_log', $current_log);
        $this->set('log_file', $log_file);
        $this->set('sub_title', $title);
        $this->set('error', $error);
        
    }
}