<?php

class AdvanceSettingController extends AppController
{

    var $name = "AdvanceSetting";
    var $uses = array('Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('/advance_setting/web');
    }

    public function script()
    {
        $data = array();

        $conf_file = Configure::read('script.conf');
        $conf_contents = file_get_contents($conf_file);

        $allow_keys = array(
            'web_url',
            'log_file_dir',
            'log_level',
            'db_name',
            'db_host',
            'db_port',
            'db_username',
            'db_password',
            'remote_ip',
            'remote_port',
            'recover_bill_ip',
            'recover_bill_port',
            'recover_local_ip',
            'cdr_softswitch'
        );

        if ($this->RequestHandler->isPost())
        {
            foreach ($allow_keys as $allow_key)
            {
                switch ($allow_key)
                {
                    case 'remote_ip':
                        $conf_contents = preg_replace('/remote_ip\s+(.*)/', "remote_ip {$_POST[$allow_key]}',", $conf_contents);
                        break;
                    case 'remote_port':
                        $conf_contents = preg_replace('/remote_port\s+(.*)/', "remote_port {$_POST[$allow_key]}',", $conf_contents);
                        break;
                    default:
                        $conf_contents = preg_replace('/' . $allow_key . '=(.*)/', "{$allow_key}={$_POST[$allow_key]}',", $conf_contents);
                        break;
                }
            }
            file_put_contents($conf_file, $conf_contents);
            $this->Systemparam->create_json_array('', 201, __('The setting of Script is modified successfully!', true));
            $this->Session->write("m", Systemparam::set_validator());
            $this->redirect('/advance_setting/script');
        }


        $pattern = '/(.*?)=(.*)/';
        preg_match_all($pattern, $conf_contents, $matches, PREG_SET_ORDER);

        foreach ($matches as $item)
        {
            if (in_array($item[1], $allow_keys))
            {
                $data[$item[1]] = $item[2];
            }
        }

        $pattern = '/remote_ip\s+(.*)/';
        preg_match($pattern, $conf_contents, $matches);
        $data['remote_ip'] = $matches[1];
        $pattern = '/remote_port\s+(.*)/';
        preg_match($pattern, $conf_contents, $matches);
        $data['remote_port'] = $matches[1];

        $this->set('data', $data);
    }

    public function web()
    {
        //echo '<pre>';print_r($_POST);die;
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
//        pr($sections);die;
        $this->set('data', $sections);
        $password = $sections['db']['password'];
        $storage_server_ftp_password = $sections['storage_server']['ftp_password'];
        if ($this->RequestHandler->isPost())
        {
            $db_file_path = APP . DS . 'config' . DS . 'database.php';
            foreach ($_POST as $key => $value)
            {
                if (is_array($value))
                {
                    foreach ($value as $key1 => $val)
                    {
                        if(!strcmp($key1, "password") && empty($val))
                        {
                            $val = $password;
                        }
                        if (!strcmp($val, 'on'))
                        {
                            $val = 1;
                        }
                        $sections[$key][$key1] = $val;
                        if(!strcmp($key, "storage_server") && !strcmp($key1, "ftp_password"))
                        {
                            if(!empty($val))
                                $storage_server_ftp_password = "\"".$val."\"";
                            $sections['storage_server']['ftp_password'] = $storage_server_ftp_password;
                        }
                    }
                }
            }
            if (isset($sections['script_ftp_cdr']['cdr_head']))
            {
                $sections['script_ftp_cdr']['cdr_head'] = "\"" . $sections['script_ftp_cdr']['cdr_head'] . "\"";
            }
            if (isset($sections['script_ftp_cdr']['cdr_alias']))
            {
                $sections['script_ftp_cdr']['cdr_alias'] = "\"" . $sections['script_ftp_cdr']['cdr_alias'] . "\"";
            }
            $result = $this->write_ini_file($sections, CONF_PATH, true);
            if ($result)
            {
                $this->Systemparam->create_json_array('', 201, __('The setting of Web is modified successfully!', true));
                $this->Session->write("m", Systemparam::set_validator());
                $this->redirect('/advance_setting/web');
            }
            else
            {
                
            }
        }
//        $data = array();
//        $pattern = '/\'(\w.*)\'\s+=>\s+\'(.*)\'/';
//        preg_match_all($pattern, $db_file_contents, $matches);
//        
////     echo "<pre>";print_r($db_file_contents);die; CONF_PATH,
//        foreach ($matches[1] as $key => $item) {
//            if (array_key_exists($item, $db_keys)) {
//                $data[$db_keys[$item]] = $matches[2][$key];
//            }
//        }
//
//
//        $pattern = '/\$config\[\'(\w+)\'\]\[\'(\w+)\'\]\s+=\s+(.*);/';
//        preg_match_all($pattern, $myconf_file_contents, $matches, PREG_SET_ORDER);
//        //print_r($matches);
//
//
////echo "<pre>";print_r($matches);die;
//        
//        foreach ($matches as $key=>$item) {
//            $key = $item[1] . "." . $item[2];
//            if (array_key_exists($key, $myconf_keys)) {
//                $data[$myconf_keys[$key]] = trim($item[3], "'\"");
//            }
//        }
//
//        $pattern = '/\'host_ip\' => \'(.*)\',/';
//        preg_match($pattern, $myconf_file_contents, $matches);
//        $data['sip_capture_ip'] = $matches[1];
//        $pattern = '/\'port\' => \'(.*)\',/';
//        preg_match($pattern, $myconf_file_contents, $matches);
//        $data['sip_capture_port'] = $matches[1];
//
//        $pattern = '/\$config\[\'active_call\'\]\[\'billing_server\'\]\s+=\s+array\(.*\'(.*)\',.*\);/ms';
//        preg_match($pattern, $myconf_file_contents, $matches);
//        $billing_server = explode(':', $matches[1]);
//        $data['billing_server_ip'] = $billing_server[0];
//        $data['billing_server_port'] = $billing_server[1];
//
//        $pattern = '/Configure::write\(\'php_exe_path\', \'(.*)\'\);/';
//        preg_match($pattern, $myconf_file_contents, $matches);
//        $data['php_path'] = $matches[1];
//
//        $pattern = '/Configure::write\(\'database_export_path\', \'(.*)\'\);/';
//        preg_match($pattern, $myconf_file_contents, $matches);
//        $data['web_db_export_path'] = $matches[1];
//
//        //echo "<pre>";print_r($data);
//        $this->set('data', $data);
    }

    public function backend()
    {
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        //pr($sections);die;
        unset($sections['switch-sdp']);
        $this->set('data', $sections);
        $log_level_arr = array(
            0 => 'emergency',
            1 => 'alert',
            2 => 'crit',
            3 => 'err',
            4 => 'warning',
            5 => 'notice',
            6 => 'info',
            7 => 'debug'
        );
        $this->set('log_level_arr', $log_level_arr);
        $billing_log_level_arr = array(
            1 => 'error',
            2 => 'warning',
            3 => 'notice',
            4 => 'info',
            5 => 'debug'
        );
        $this->set('billing_log_level_arr', $billing_log_level_arr);
        if ($this->RequestHandler->isPost())
        {
            $db_file_path = APP . DS . 'config' . DS . 'database.php';

            foreach ($_POST as $key => $value)
            {
                if (is_array($value))
                {
                    if (strcmp($key, 'switch_lnp'))
                    {
                        foreach ($value as $key1 => $val)
                        {
                            if (!strcmp($val, 'on'))
                            {
                                $val = 'true';
                            }
                            $sections[$key][$key1] = $val;
                        }
                    }
                    else
                    {
                        foreach ($value as $key1 => $val)
                        {
                            if (!strcmp($val, 'on'))
                            {
                                $val = 'yes';
                            }
                            $sections[$key][$key1] = $val;
                        }
                    }
                }
            }
            $result = $this->write_ini_file($sections, CONF_PATH, true);
            if ($result)
            {
                $this->Systemparam->create_json_array('', 201, __('The setting of Web is modified successfully!', true));
                $this->Session->write("m", Systemparam::set_validator());
                $this->redirect('/advance_setting/backend');
            }
            else
            {
                
            }
        }
    }

    function write_ini_file($assoc_arr, $path, $has_sections = FALSE)
    {
        $content = "";
        if ($has_sections)
        {
            foreach ($assoc_arr as $key => $elem)
            {
                $content .= "[" . $key . "]\n";
                foreach ($elem as $key2 => $elem2)
                {
                    if (is_array($elem2))
                    {
                        for ($i = 0; $i < count($elem2); $i++)
                        {
                            //$content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                            $content .= $key2 . "[]=" . $elem2[$i] . "\n";
                        }
                    }
                    else if ($elem2 == "")
                        $content .= $key2 . " = \n";
                    //else $content .= $key2." = \"".$elem2."\"\n"; 
                    else
                        $content .= $key2 . "=" . $elem2 . "\n";
                }
            }
        }
        else
        {
            foreach ($assoc_arr as $key => $elem)
            {
                if (is_array($elem))
                {
                    for ($i = 0; $i < count($elem); $i++)
                    {
                        $content .= $key2 . "[] = \"" . $elem[$i] . "\"\n";
                    }
                }
                else if ($elem == "")
                    $content .= $key2 . " = \n";
                else
                    $content .= $key2 . " = \"" . $elem . "\"\n";
            }
        }

        if (!$handle = @fopen($path, 'w'))
        {
            $this->Session->write('m', $this->Systemparam->create_json(101, 'Insufficient permissions configuration file!'));
            return false;
        }
        if (!fwrite($handle, $content))
        {
            return false;
        }
        fclose($handle);
        return true;
    }

    public function check_db_mount()
    {
        $path = "/home/voipmonitor/pcap";
    }

}
