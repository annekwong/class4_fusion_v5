<?php

class SystemparamsController extends AppController
{

    var $name = 'Systemparams';
    var $components = array('PhpTelnet', 'RequestHandler');
    var $uses = array(
        'Curr', 'Systemparam', 'GlobalFailover', 'TerminationGlobalFailover', 'OriginationGlobalFailover', 'GlobalRouteError',
        'Mailtmp', 'FtpCdrLog', 'FtpConf', 'FtpCdrLogDetail', 'FtpServerLog', 'StorageConf', 'ApiLog'
    );

    function index()
    {
        $this->redirect('view');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        parent::beforeFilter();
    }

    public function find_all_currency()
    {
        $r = $this->Systemparam->query("select currency_id ,code from currency where active=true");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['currency_id'];
            $l [$key] = $r [$i] [0] ['code'];
        }
        return $l;
    }

    private function getStorageSaveArray($post)
    {
        $result = array();
        if(isset($post) && $post['storage_type'] != '') {
            $type = $post['storage_type'];
//            $result['storage_type'] = $type;
//            if(isset($post['pcap_id'])) {
//                $result['id'] = $post['pcap_id'];
//            }
//            if(isset($post['cdr_id'])) {
//                $result['id'] = $post['cdr_id'];
//            }
            switch($type) {
                case '0':
                    $result['storage_path'] = $post['storage_path'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['pcap'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['local'];
                    break;
                case '1':
                    $result['storage_path'] = $post['storage_path'];
                    $result['remote_server_ip'] = $post['remote_server_ip'];
                    $result['remote_server_port'] = $post['remote_server_port'];
                    $result['username'] = $post['username'];
                    $result['password'] = $post['password'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['pcap'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['sftp'];
                    break;
                case '2':
                    $result['storage_path'] = $post['storage_path'];
                    $result['ftp_ip'] = $post['ftp_ip'];
                    $result['ftp_port'] = $post['ftp_port'];
                    $result['username'] = $post['username'];
                    $result['password'] = $post['password'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['pcap'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['ftp'];
                    break;
                case '3':
                    $result['google_drive_key'] = $post['google_drive_key'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['pcap'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['gcs'];
                    break;
                case '4':
                    $result['storage_days'] = $post['storage_days'];
                    $result['storage_days_local'] = $post['storage_days_local'];
                    $result['storage_path'] = $post['storage_path'];
                    $result['ftp_ip'] = $post['ftp_ip'];
                    $result['ftp_port'] = $post['ftp_port'];
                    $result['username'] = $post['username'];
                    $result['password'] = $post['password'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['cdr'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['ftp'];
                    break;
                case '5':
                    $result['storage_days'] = $post['storage_days'];
                    $result['storage_days_local'] = $post['storage_days_local'];
                    $result['google_drive_key'] = $post['google_drive_key'];
                    $result['google_drive_name'] = $post['google_drive_name'];
                    $result['google_drive_name'] = $post['google_drive_name'];
                    $result['conf_type'] = StorageConf::PARSER_TYPE['cdr'];
                    $result['storage'] = StorageConf::STORAGE_TYPE['gcs'];
                    break;
            }
        }
        return $result;
    }

    private function getStorageTypeNumber($type, $storage){
        if($type == 'pcap'){
            return array_search($storage, array_keys(StorageConf::STORAGE_TYPE));
        }else{
            if($storage == 'ftp'){
                return 4;
            }else{
                return 5;
            }
        }
    }

    public function advance()
    {

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
//        pr($sections);die;

        $tmpData = $this->StorageConf->find('first',array(
            'conditions' => array(
                'conf_type' => StorageConf::PARSER_TYPE['pcap']
            ),
        ));
        $tmpData['StorageConf']['storage_type'] = $this->getStorageTypeNumber('pcap', $tmpData['StorageConf']['storage']);
        $this->set('pcapData', $tmpData['StorageConf']);

        $tmpData = $this->StorageConf->find('first',array(
            'conditions' => array(
                'conf_type' => StorageConf::PARSER_TYPE['cdr']
            ),
        ));
        $tmpData['StorageConf']['storage_type'] = $this->getStorageTypeNumber('cdr', $tmpData['StorageConf']['storage']);
        $this->set('cdrData', $tmpData['StorageConf']);
        $public_ip= trim(shell_exec("ip addr show eth0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1"));
        $public_ip= isset($public_ip) && $public_ip ? $public_ip: $_SERVER['REMOTE_ADDR'];
        $this->set('data', $sections);
        $this->set('public_ip', [$public_ip]);
        $password = $sections['db']['password'];
        $second_pwd = $sections['web_db2']['password'];
        $storage_server_ftp_password = isset($sections['storage_server']['ftp_password']) ? $sections['storage_server']['ftp_password'] : '';
        $api_urls = [
            'api_pcap_url' => Configure::read('pcap_url'),
            'api_invoice_url' => Configure::read('invoice_url'),
            'api_invoice_orig_url' => Configure::read('invoice_orig_url'),
            'api_import_url' => Configure::read('import_url'),
            'cdr_archival_url' => Configure::read('archival_url'),
            'real_time_and_reporting_url' => Configure::read('cdr_url')
        ];

        $this->set('apiUrls', $api_urls);
        if ($this->RequestHandler->isPost())
        {
            $pcapArray = $this->getStorageSaveArray($_POST["pcap"]);
            $cdrArray = $this->getStorageSaveArray($_POST["cdr"]);
            $this->StorageConf->query("TRUNCATE storage_config");
            if(!empty($pcapArray)) {

                $this->StorageConf->save($pcapArray);
            }
            if(!empty($cdrArray)) {

                $this->StorageConf->save($cdrArray);
            }
            $db_file_path = APP . DS . 'config' . DS . 'database.php';
            // api urls
            $api_pcap_url = $this->addhttp($_POST['api_pcap_url']);
            $api_invoice_url = $this->addhttp($_POST['api_invoice_url']);
            $api_invoice_orig_url = $this->addhttp($_POST['api_invoice_orig_url']);
            $api_import_url =  $this->addhttp($_POST['api_import_url']);
            $cdr_archival_url =  $this->addhttp($_POST['cdr_archival_url']);
            $real_time_and_reporting_url =  $this->addhttp($_POST['real_time_and_reporting_url']);
            $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
            $sections['pcap_api']['url'] = $api_pcap_url;
            $sections['invoice']['url'] = $api_invoice_url;
            $sections['invoice']['orig_url'] = $api_invoice_orig_url;
            $sections['import']['url'] = $api_import_url;
            $sections['cdr_archival_url']['url'] = $cdr_archival_url;
            $sections['cdr_api']['url'] = $real_time_and_reporting_url;
            $sections['web_switch']['license_ip'] = $_POST['web_switch']['license_ip'];
            $sections['web_switch']['lrn_request_ip'] = $_POST['web_switch']['lrn_request_ip'];

            $this->write_ini_file($sections, CONF_PATH, true);

            foreach ($_POST as $key => $value)
            {
                if (is_array($value))
                {
                    foreach ($value as $key1 => $val)
                    {
                        if(!strcmp($key1, "password") && empty($val))
                        {
                            switch($key){
                                case 'db':
                                    $val = $password;
                                    break;
                                case 'web_db2':
                                    $val = $second_pwd;
                                    break;
                            }
                        }
                        if (!strcmp($val, 'on'))
                        {
                            $val = 1;
                        }
                        $sections[$key][$key1] = $val;
                        if(!strcmp($key, "storage_server") && !strcmp($key1, "ftp_password"))
                        {
                            if(!empty($val))
                                $storage_server_ftp_password = $val;
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
            if (isset($sections['storage_server']['ftp_password']))
            {
                $sections['storage_server']['ftp_password'] = "\"" . $sections['storage_server']['ftp_password'] . "\"";
            }
            $result = $this->write_ini_file($sections, CONF_PATH, true);

            if ($result)
            {
                $this->Systemparam->create_json_array('', 201, __('The Setting of Web is modified successfully!', true));
                $this->Session->write("m", Systemparam::set_validator());
                $this->redirect('advance');
            }
            else
            {
                $this->redirect('advance');
            }
        }
    }

    public function change_logo()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['logoimg']['tmp_name']))
        {
            $destpath = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';
            //$invoice_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'ilogo.png';
            //$invoice_path = APP . "vendors/tcpdf/images/ilogo.png";
            //$invoice_path = WWW_ROOT . 'upload' . DS . 'html' . DS . 'ilogo.png';
            $sourcepath = $_FILES['logoimg']['tmp_name'];
            switch ($_FILES['logoimg']['type'])
            {
                case 'image/png':
                    header("Content-Type: image/png");
                    $im = imagecreatefrompng($sourcepath);
                    imagesavealpha($im, true);
                    break;
                case 'image/jpeg':
                    header("Content-Type: image/jpeg");
                    $im = imagecreatefromjpeg($sourcepath);
                    break;
                case 'image/gif':
                    header("Content-Type: image/gif");
                    $im = imagecreatefromgif($sourcepath);
                    break;
            }
            imagepng($im, $destpath);
            //imagepng($im, $invoice_path);
            imagedestroy($im);
            $this->Systemparam->create_json_array("", 201, 'Your Logo is uploaded successfully!');
            $this->Session->write('m', Systemparam::set_validator());
        }
        $this->redirect('/systemparams/view');
    }

    public function change_icon()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['iconimg']['tmp_name']))
        {
            $destpath = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'favicon.ico';
            $sourcepath = $_FILES['iconimg']['tmp_name'];

            if (strpos($_FILES['iconimg']['type'], 'ico') !== false)
            {
                move_uploaded_file($sourcepath, $destpath);
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', false);
                header('Pragma: no-cache');
                $this->Systemparam->create_json_array("", 201, 'Your Icon is Uploaded successfully');
                $this->Session->write('m', Systemparam::set_validator());
            }
            else
            {
                $this->Systemparam->create_json_array("", 101, 'You did not upload an icon file!');
                $this->Session->write('m', Systemparam::set_validator());
            }
        }
        $this->redirect('/systemparams/view');
    }

    public function change_certfile()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['certifile']['tmp_name']))
        {
            $destpath = APP . 'webroot' . DS . 'upload' . DS . 'yourpay' . DS . 'YOURCERT.perm';
            $sourcepath = $_FILES['certifile']['tmp_name'];
            move_uploaded_file($sourcepath, $destpath);
            $this->Systemparam->create_json_array("", 201, 'Succeed!');
            $this->Session->write('m', Systemparam::set_validator());
        }
        $this->redirect('/systemparams/view');
    }

    public function auto_cdr_fields_setting()
    {
        $incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields();
        $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields();
        $all_cdr_fields = array_merge($incoming_cdr_fields,$outgoing_cdr_fields);
        if ($this->RequestHandler->isPost())
        {
            $incoming_list = isset($this->params['form']['ingress_fields']) ? $this->params['form']['ingress_fields'] : 0;
            $outgoing_list = isset($this->params['form']['egress_fields']) ? $this->params['form']['egress_fields'] : 0;
            $sql = "DELETE FROM daily_cdr_fields WHERE type = 0 or type = 1";
            $this->Systemparam->query($sql);
            $sql_arr1 = array();
            if (!empty($incoming_list))
            {
                foreach ($incoming_list as $incoming_item)
                {
                    array_push($sql_arr1, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (0, '{$incoming_item}', '$incoming_cdr_fields[$incoming_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr1));
            }
            $sql_arr2 = array();
            if (!empty($outgoing_list))
            {
                foreach ($outgoing_list as $outgoing_item)
                {
                    array_push($sql_arr2, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (1, '{$outgoing_item}', '$outgoing_cdr_fields[$outgoing_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr2));
            }
            $this->Session->write('m', $this->Systemparam->create_json(201, 'The CDR Generation Format Fields are modified successfully!'));
        }

        $incoming_data = $this->Systemparam->get_daily_cdr_fields(0);
        $outgoing_data = $this->Systemparam->get_daily_cdr_fields(1);
        unset($all_cdr_fields['callduration_in_ms']);
        //$all_cdr_fields['origination_call_id'] = 'Orig Call ID';
        //$all_cdr_fields['termination_call_id'] = 'Term Call Id';

        $this->set('incoming_cdr_fields', $incoming_cdr_fields);
        $this->set('outgoing_cdr_fields', $outgoing_cdr_fields);
        $this->set('incoming_data', $incoming_data);
        $this->set('outgoing_data', $outgoing_data);
        $this->set('all_cdr_fields',$all_cdr_fields);
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function view()
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        $this->pageTitle = "Configuration/System Setting";
        $list = $this->Systemparam->query("select * from system_parameter");
//        die(var_dump($list));

        /*
          $content = "";
          $cmd = "sipcapture_set_flag other";
          $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
          if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
          socket_write($socket, $cmd, strlen($cmd));
          }
          while ($out = socket_read($socket, 2048)) {
          $content .= $out;
          if (strpos($out, "~!@#$%^&*()") !== FALSE) {
          break;
          }
          unset($out);
          }
          $content = strstr($content, "~!@#$%^&*()", TRUE);

          socket_close($socket);
          $this->set('sip_capture_status', $content);


          $content = "";
          $cmd = "rtpdump_set_flag other";
          $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
          if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
          socket_write($socket, $cmd, strlen($cmd));
          }
          while ($out = socket_read($socket, 2048)) {
          $content .= $out;
          if (strpos($out, "~!@#$%^&*()") !== FALSE) {
          break;
          }
          unset($out);
          }
          $content = strstr($content, "~!@#$%^&*()", TRUE);

          socket_close($socket);

          $this->set('rtpdump_status', $content);
         *
         */

        //	pr($list);
        if (empty($list))
        {
            $list = $this->Systemparam->query("select  *  from  currency  where  code='USA' ;");
            if (empty($list))
            {
                $this->data['Curr']['code'] = 'USA';
                $this->Curr->save($this->data['Curr']['code']);
                $currency_id = $this->Curr->$this->getLastInsertID();
                $sql1 = "insert  into currency_updates (currency_id,rate)values($currency_id,1); ";
                $this->Systemparam->query($sql1);
                $sql1 = "insert  into system_parameter (sys_timezone,sys_currency)values('+0000','USA'); ";
                $this->Systemparam->query($sql1);
                $sys_currency_rate = '1';
                $sys_currency = 'USA';
                $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');
                $this->Systemparam->query($sql1);
            }
        }

        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

        if (file_exists($logo_path))
        {
            $logo = $this->webroot . 'upload/images/logo.png';
        }
        else
        {
            $logo = $this->webroot . 'images/logo.png';
        }

        $this->set('logo', $logo);

//        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'favicon.ico';

//        if (file_exists($logo_path))
//        {
//            $favicon = $this->webroot . 'upload/images/favicon.ico';
//        }
//        else
//        {
//            $favicon = $this->webroot . 'favicon.ico';
//        }
        $favicon = $this->webroot . 'favicon.ico';
        $this->set('favicon', $favicon);

//      获取lrn setting
        $this->loadModel('C4Lrn');
        $lrn_data = $this->C4Lrn->find('first');
        if (!$lrn_data)
        {
            $lrn_data = array(
                'C4Lrn' => array(
                    'srv1_ip' => '',
                    'srv1_port' => '',
                    'srv2_ip' => '',
                    'srv2_port' => '',
                )
            );
        }
        $this->set('lrn_data',$lrn_data);
        //获取code deck
        $this->loadModel('Rate');
        $info = $this->Systemparam->find('first',array(
            'fields' => array('login_page_content','login_fit_image','sys_id'),
        ));
        $this->set('login_page_content',$info['Systemparam']['login_page_content']);
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currency', $this->find_all_currency());
        $sysparams = $this->Systemparam->findsysparam();
        $this->set('post', $sysparams);
        $voip_gateway_sql = "select profile_name,id,pcap_token,sip_ip,sip_port FROM switch_profile order by profile_name asc";
        $this->set('switches',$this->Systemparam->query($voip_gateway_sql));
    }

    //更新系统参数

    public function set_capture($status)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $cmd = "sipcapture_set_flag $status";
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
        $this->xredirect('/systemparams/view');
    }

    public function set_rptdump($status)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $cmd = "rtpdump_set_flag $status";
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
        $this->xredirect('/systemparams/view');
    }

    public function failover($type = 'all')
    {
        parent::beforeFilter();
        switch ($type)
        {
            case 'all':
                $name = "Default Failover Rule";
                $table_name = 'global_failover';
                break;
            case 'orig':
                $name = "Default Origination Failover Rule";
                $table_name = 'origination_global_failover';
                break;
            case 'term':
                $name = "Default Termination Failover Rule";
                $table_name = 'termination_global_failover';
                break;
            default :
                $name = "Default Failover Rule";
                $table_name = 'global_failover';
                $type = 'all';
        }

        $this->set('titie_name', $name);
        $this->set('type', $type);
        $this->pageTitle = "Configuration/$name";
        $list = $this->Systemparam->query("select * from  $table_name order by id");

        $this->set("host", $list);
    }

    /**
     *
     * @param type in ( orig , term)
     *  将origination_global_failover 表数据 复原为 global_failover 数据
     *  将termination_global_failover 表数据 复原为 global_failover 数据
     *
     */
    public function reset_failover($type = 'orig')
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        if (!strcmp($type, 'orig'))
        {
            $table_name = 'origination_global_failover';
        }
        elseif (!strcmp($type, 'term'))
        {
            $table_name = 'termination_global_failover';
        }
        else
        {
//            $this->Systemparam->create_json_array('', 101, __('Failed!', true));
//            $this->Session->write("m", Systemparam::set_validator());
//            $this->redirect("/systemparams/failover/all");
            return 0;
        }
        $result = $this->Systemparam->reset_failover($table_name);
        if ($result)
        {
//            $this->Systemparam->create_json_array('', 201, __('Successfully!', true));
//            $this->Session->write("m", Systemparam::set_validator());
//            $this->redirect("/systemparams/failover/{$type}");
            switch ($type)
            {
                case 'all':
                    $name = "Default Failover Rule";
                    $table_name = 'global_failover';
                    break;
                case 'orig':
                    $name = "Default Origination Failover Rule";
                    $table_name = 'origination_global_failover';
                    break;
                case 'term':
                    $name = "Default Termination Failover Rule";
                    $table_name = 'termination_global_failover';
                    break;
                default :
                    $name = "Default Failover Rule";
                    $table_name = 'global_failover';
                    $type = 'all';
            }
            $list = $this->Systemparam->query("select * from  {$table_name} order by id");
            $this->set("type", $type);
            $this->set("host", $list);
            $this->render("/systemparams/failover_table");
        }
        else
        {
//            $this->Systemparam->create_json_array('', 101, __('Failed!', true));
//            $this->Session->write("m", Systemparam::set_validator());
//            $this->redirect("/systemparams/failover/{$type}");
            return 0;
        }
    }

    public function add_rule_post($type = 'orig')
    {
        switch ($type)
        {
            case 'orig':
                $model_name = "OriginationGlobalFailover";
                $table_name = 'origination_global_failover';
                break;
            case 'term':
                $model_name = "TerminationGlobalFailover";
                $table_name = 'termination_global_failover';
                break;
            default :
                $model_name = "OriginationGlobalFailover";
                $table_name = 'origination_global_failover';
                $type = "orig";
        }

        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
        $size = count($tmp);

        if (!empty($tmp))
        {
            $flg = "";
            $error_count = 0;

            //防止有重复的code
            $code_arr = array();
            foreach ($tmp as $tmp_arr)
            {
                if (in_array($tmp_arr['from_sip_code'], $code_arr))
                {
                    $this->$model_name->create_json_array('#ClientOrigRateTableId', 101, "The return code {$tmp_arr['from_sip_code']} is already defined!");
                    $this->Session->write("m", $model_name::set_validator());
                    $this->redirect("/systemparams/failover/{$type}");
                }
                array_push($code_arr, $tmp_arr['from_sip_code']);
            }
            $this->loadModel('ResourceNextRouteRule');
            if (!strcmp($type, 'term'))
                $trunk_id_arr = $this->ResourceNextRouteRule->findAll_egress_id();
            else
                $trunk_id_arr = $this->ResourceNextRouteRule->findAll_ingress_id();


            $save_all_data = array();
            foreach ($tmp as $key => $el)
            {
                $save_all_data[] = $el;
                if ($_POST['is_all_trunk'])
                {
                    foreach ($trunk_id_arr as $trunk_id => $trunk_value)
                    {
                        if ($trunk_id)
                        {
                            $this->data['ResourceNextRouteRule'][] = array(
                                'route_type' => $el['failover_strategy'],
                                'reponse_code' => $el['from_sip_code'],
                                'return_code' => $el['to_sip_code'],
                                'return_string' => $el['to_sip_string'],
                                'resource_id' => $trunk_id,
                            );
                        }
                    }
                }
//                $this->data[$model_name]['id'] = false;
            }
            $modelName = preg_replace('/([a-z])([A-Z])/s','$1 $2', $model_name);
            $flg = $this->$model_name->saveAll($save_all_data);
            if ($flg === false)
            {
                $this->$model_name->create_json_array('#ClientOrigRateTableId', 101, "The {$modelName} is modified failed !");
                $this->Session->write("m", $model_name::set_validator());
                $this->redirect("/systemparams/failover/{$type}");
            }
        }
        if (!empty($delete_rate_id))
        {
            $this->$model_name->query("delete  from  {$table_name} where id in($delete_rate_id)");
        }
        $this->$model_name->create_json_array('#ClientOrigRateTableId', 201, "The {$modelName} is modified successfully!");
        $this->Session->write("m", $model_name::set_validator());
        $this->redirect("/systemparams/failover/{$type}");
    }

    public function global_route_error()
    {
        $this->pageTitle = "Configuration/Global Route Error";
        $list = $this->Systemparam->query("select * from  global_route_error order by id");
        $this->set("host", $list);
    }

    public function reset_failover_error()
    {
        $defalut_data = $this->Systemparam->query("SELECT default_to_sip_code,default_to_sip_string,id FROM global_route_error;");
        $sql = "";
        foreach ($defalut_data as $defalut_data_item)
        {
            $to_sip_code = $defalut_data_item[0]['default_to_sip_code'];
            $to_sip_string = $defalut_data_item[0]['default_to_sip_string'];
            $id = $defalut_data_item[0]['id'];
            $sql .= "UPDATE global_route_error SET to_sip_code = {$to_sip_code}, to_sip_string = '{$to_sip_string}' WHERE id = {$id};";
        }
        $result = $this->Systemparam->query($sql);
        if ($result !== FALSE)
        {
            $this->Systemparam->create_json_array('', 201, __('Succeed!', true));
        }
        else
        {
            $this->Systemparam->create_json_array('', 101, __('Failed!', true));
        }
        $this->Session->write("m", Systemparam::set_validator());
        $this->redirect("/systemparams/global_route_error");
    }

    public function add_error_post()
    {
        $data = $this->params['form']['accounts'];
        foreach ($data as $data_item)
        {
            $this->data['GlobalRouteError'] = $data_item;
            $this->GlobalRouteError->save($this->data['GlobalRouteError']);
            $this->data['GlobalRouteError']['id'] = false;
        }
        $this->GlobalRouteError->create_json_array('#ClientOrigRateTableId', 201, "The Global Route Error is modified successfully!");
        $this->Session->write("m", GlobalRouteError::set_validator());
        $this->redirect("/systemparams/global_route_error");
    }

    public function test_update()
    {
        $_POST['switch_ip'] = '192.168.1.115';
        $_POST['switch_port'] = 5060;
        $_POST['system_admin_email'] = 'w@163.com';
        $_POST['loginemail'] = 'w@163.com';

        $_POST ['smtphost'] = '192.168.1.115';
        $_POST ['smtpport'] = '5060';
        $_POST ['emailusername'] = '88';
        $_POST ['emailpassword'] = '99';
        $_POST ['fromemail'] = '999999';
        $_POST ['emailname'] = '22222222';



        $_POST ['currency'] = 'USA';
        $_POST ['timezone'] = '-800';
        $_POST ['mail_host'] = '192.168.1.115';
        $_POST ['mail_from'] = '5555';

        $this->ajax_update();
    }

    public function ajax_update()
    {
        if (!$_SESSION['role_menu']['Configuration']['systemparams']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $info = $this->Systemparam->find('first',array(
            'fields' => array('login_page_content','login_fit_image','sys_id'),
        ));
        $is_change_logo = $_POST['is_change_logo'];
        $is_change_icon = $_POST['is_change_icon'];

        if ($is_change_icon)
        {
            $icon_path = APP . 'webroot' . DS . 'favicon.ico';
            $tmp_icon_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'tmp' . DS . 'favicon_tmp.ico';
            rename($tmp_icon_path,$icon_path);
        }

        if ($is_change_logo)
        {
            $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . '/logo.png';
            $tmp_logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'tmp' . DS . 'logo_tmp.png';
            rename($tmp_logo_path,$logo_path);
        }

        $switch_ip = $_POST['switch_ip'];
        $switch_port = $_POST['switch_port'];
        $system_admin_email = $_POST['system_admin_email'];
        $loginemail = $_POST ['loginemail'];
        $smtphost = $_POST ['smtphost'];
        $smtpport = $_POST ['smtpport'];
        $emailusername = $_POST ['emailusername'];
        $emailpassword = $_POST ['emailpassword'];
        $fromemail = $_POST ['fromemail'];
        $emailname = $_POST ['emailname'];
        $smtp_secure = $_POST['smtp_secure'];
        $report_count = $_POST['report_count'];
        $search_code_deck = empty($_POST['search_code_deck']) ? '0' : $_POST['search_code_deck'];
        $welcome_message = $_POST['welcome_message'];
        $currency = $_POST ['currency'];
        $timezone = $_POST ['timezone'];
        $realm = $_POST['realm'];
        $workstation = $_POST['workstation'];
        $mail_host = $_POST ['mail_host'];
        $mail_from = $_POST ['mail_from'];
        $ftp_username = $_POST ['ftp_username'];
        $ftp_pass = $_POST ['ftp_pass'];
        $cc_pinLength = $_POST ['cc_pinLength'];
        $dateFormat = $_POST ['dateFormat'];
        $datetimeFormat = $_POST ['datetimeFormat'];
        $csv_delimiter = $_POST ['csv_delimiter'];
        $invoices_tplNo = $_POST ['invoices_tplNo'];
        $invoices_lastNo = $_POST ['invoices_lastNo'];
        $invoices_fields = $_POST ['invoices_fields'];
        $invoices_delay = $_POST ['invoices_delay'];
        $invoices_separate = $_POST ['invoices_separate'];
        $invoices_cdr_fields = $_POST ['invoices_cdr_fields'];
        $dr_period = $_POST ['dr_period'];
        $radius_log_routes = $_POST ['radius_log_routes'];
        $lowBalance_period = $_POST ['lowBalance_period'];
        $events_deleteAfterDays = $_POST ['events_deleteAfterDays'];
        $stats_rotamte_delay = $_POST ['stats_rotate_delay'];
        $rates_deleteAfterDays = $_POST ['rates_deleteAfterDays'];
        $cdrs_deleteAfterDays = $_POST ['cdrs_deleteAfterDays'];
        $logs_deleteAfterDays = $_POST ['logs_deleteAfterDays'];
        $backup_period = $_POST ['backup_period'];
        $backup_leave_last = $_POST ['backup_leave_last'];
        $events_notFoundAccount = $_POST ['events_notFoundAccount'];
        $events_notFoundTariff = $_POST ['events_notFoundTariff'];
        $events_unprofitable = $_POST ['events_unprofitable'];
        $events_alertsZeroTime = $_POST ['events_alertsZeroTime'];
        $lowBalance_period = $_POST ['lowBalance_period'];
        $loginPageContent = $_POST ['login_page_content'];
        $pdf_tpl = $_POST['pdf_tpl'];
//        $tpl_number = $_POST['tpl_number'];
        $finance_email = $_POST['finance_email'];
        $noc_email = $_POST['noc_email'];
        $withdraw_email = $_POST['withdraw_email'];
        $ftp_email = $_POST['ftp_email'];
        //var_dump($withdraw_email);
        //exit;
        $qos_sample_period = $_POST['qos_sample_period'] == '' ? 'NULL' : $_POST['qos_sample_period'];
        $minimal_call_attempt_required = $_POST['minimal_call_attempt_required'] == '' ? 'NULL' : $_POST['minimal_call_attempt_required'];
        $low_call_attempt_handling = $_POST['low_call_attempt_handling'] == '' ? 'NULL' : $_POST['low_call_attempt_handling'];
        $landing_page = $_POST['landing_page'];
        $invoice_name = $_POST['invoice_name'];
        $bar_color = $_POST['bar_color'];
        $inactivity_timeout = empty($_POST['inactivity_timeout']) ? 0 : $_POST['inactivity_timeout'];
        $is_preload = $_POST['is_preload'];
        $yourpay_store_number = $_POST['yourpay_store_number'];
//        $paypal_account = $_POST['paypal_account'];
        $switch_alias = $_POST['switch_alias'];
        $ingress_pdd_timeout = $_POST['ingress_pdd_timeout'] ? $_POST['ingress_pdd_timeout'] : 'default';
        $egress_pdd_timeout = $_POST['egress_pdd_timeout'] ? $_POST['egress_pdd_timeout'] : 'default';
        $ring_timeout = $_POST['ring_timeout'];
        $call_timeout = $_POST['call_timeout'];

        $is_show_mutual_balance = $_POST['show_mutual_balance'];
        $is_hide_unauthorized_ip = (int) $_POST['is_hide_unauthorized_ip'];
        $require_comment = (int) $_POST['require_comment'];
        $auto_rate_smtp = $_POST['auto_rate_smtp'];
        $auto_rate_smtp_port = (int)$_POST['auto_rate_smtp_port'];
        $auto_rate_username = $_POST['auto_rate_username'];
        $auto_rate_pwd = $_POST['auto_rate_pwd'];
        $cmd_debug = $_POST['cmd_debug'];
        $auto_rate_mail_ssl = (int)$_POST['auto_rate_mail_ssl'];
        $default_us_ij_rule = (int) $_POST['default_us_ij_rule'];
        $default_billing_decimal = (int) $_POST['default_billing_decimal'] ? (int) $_POST['default_billing_decimal'] : 6;
        // prepare base_url
        $base_url = $this->prepareBaseURL($_POST['base_url']);

        $cdr_token = $_POST['cdr_token'];
        $cdr_token_alias = $_POST['cdr_token_alias'];
        $signup_content = $_POST['signup_content'];
        $enable_client_download_rate = $_POST['enable_client_download_rate'];
        $enable_client_delete_trunk = $_POST['enable_client_delete_trunk'];
        $enable_client_disable_trunk = $_POST['enable_client_disable_trunk'];

//        $api_pcap_url = $_POST['api_pcap_url'];
//        $api_invoice_url = $_POST['api_invoice_url'];
//        $api_import_url = $_POST['api_import_url'];

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $sections['web_base']['url'] = $base_url;
//        $sections['pcap_api']['url'] = $api_pcap_url;
//        $sections['invoice']['url'] = $api_invoice_url;
//        $sections['import']['url'] = $api_import_url;
        $this->write_ini_file($sections, CONF_PATH, true);


        $list = $this->Systemparam->query("select  count(*) as  c from system_parameter");
        if (empty($list) || empty($list [0] [0] ['c']))
        {
            $sql = "insert  into system_parameter (sys_timezone,sys_currency)values('$timezone','$currency'); ";
            $this->Systemparam->query($sql);
        }
        $loginPageContentArray = array(
            'sys_id' => $info['Systemparam']['sys_id'],
            'login_page_content' => $_POST['login_page_content']
        );
        $this->Systemparam->save($loginPageContentArray);

        $sql = "update system_parameter set enable_client_download_rate = '{$enable_client_download_rate}', enable_client_delete_trunk = '{$enable_client_delete_trunk}', enable_client_disable_trunk = '{$enable_client_disable_trunk}', signup_content = '{$signup_content}', ingress_pdd_timeout = $ingress_pdd_timeout, egress_pdd_timeout = $egress_pdd_timeout, 
                 ring_timeout = $ring_timeout, call_timeout = $call_timeout,is_show_mutual_balance = $is_show_mutual_balance,
                withdraw_email='" . addslashes($withdraw_email) . "',sys_timezone='$timezone',yourpay_store_number='{$yourpay_store_number}',
		sys_currency='$currency' ,smtphost='$smtphost' ,smtpport='$smtpport' ,emailusername='$emailusername' , smtp_secure={$smtp_secure}, inactivity_timeout = {$inactivity_timeout},is_preload = {$is_preload},switch_alias = '{$switch_alias}',
		emailpassword='$emailpassword',fromemail='$fromemail',emailname='$emailname',ftp_email='$ftp_email' ,loginemail='$loginemail',system_admin_email='$system_admin_email',landing_page = $landing_page, invoice_name = '{$invoice_name}', bar_color = '$bar_color', 
		realm = '{$realm}', workstation =  '{$workstation}',qos_sample_period = {$qos_sample_period}, cdr_token = '{$cdr_token}', cdr_token_alias = '{$cdr_token_alias}',"
            . " report_count = {$report_count}, minimal_call_attempt_required = {$minimal_call_attempt_required},is_hide_unauthorized_ip = {$is_hide_unauthorized_ip},"
            . "low_call_attempt_handling = {$low_call_attempt_handling}, default_code_deck = {$search_code_deck},finance_email='" . addslashes($finance_email) . "',  welcome_message = '" . addslashes($welcome_message) . "',noc_email='" . addslashes($noc_email) . "'
		,require_comment = {$require_comment}, cmd_debug = '{$cmd_debug}', auto_rate_smtp = '{$auto_rate_smtp}',auto_rate_smtp_port = {$auto_rate_smtp_port},auto_rate_username = '{$auto_rate_username}',"
            . "auto_rate_pwd = '{$auto_rate_pwd}',auto_rate_mail_ssl = {$auto_rate_mail_ssl},default_us_ij_rule = {$default_us_ij_rule}, default_billing_decimal = {$default_billing_decimal}, base_url='{$base_url}'";
        $result = $this->Systemparam->query($sql);
        #configure mail server
        Configure::write('smtp_settings', array(
                'sendmailtype' => 1,
                'smtphost' => $smtphost,
                'smtpport' => $smtpport,
                'loginemail' => $loginemail,
                'emailusername' => $emailusername,
                'emailpassword' => $emailpassword,
                'fromemail' => $fromemail,
                'emailname' => $emailname
            )
        );

        $list = $this->Systemparam->query("
	   select rate  from currency_updates where currency_id = (
   				select  currency_id  from  currency  where code='$currency'
			) and modify_time=(
  					select max(modify_time) from currency_updates where currency_id = (
   select  currency_id  from  currency  where code='$currency'
  )
  )
  
  ");
        $sys_currency = $currency;
        $sys_currency_rate = !empty($list[0][0]['rate']) ? $list[0][0]['rate'] : '';
        $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');
        if($result === false)
            $this->set('extensionBeans', '0');
        else
        {
            $this->loadModel('C4Lrn');
            $lrn_save_data = array(
                'srv1_ip' => $_POST['master_lrn_ip'],
                'srv1_port' => $_POST['master_lrn_port'],
                'srv2_ip' => $_POST['slave_lrn_ip'],
                'srv2_port' => $_POST['slave_lrn_port']
            );
            $lrn_data = $this->C4Lrn->find('first');
            if ($lrn_data)
                $lrn_save_data['id'] = $lrn_data['C4Lrn']['id'];

            $lrn_flg = $this->C4Lrn->save($lrn_save_data);
            if ($lrn_flg === false)
                $this->set('extensionBeans', '2');
            else
                $this->set('extensionBeans', '1');

            $_SESSION['sys_timezone'] = $timezone;
        }
    }

    private function prepareBaseURL($url){
        if ($url) {
            $parse = parse_url($url);

            if(!$parse['scheme']) {
                $url = 'http://' . $url;
            }
            if(!$parse['port']){
                $url = $url . ':' . $_SERVER['SERVER_PORT'] . '/';
            }
            if (substr($url, -1) == '/') {
                $url = substr($url, 0, strlen($url) - 1);
            }
            $url .='/';

        }
        return $url;
    }

    public function testsmtp1()
    {
        $info = "";
        if ($this->RequestHandler->IsPost())
        {
            $sendAddress = $_POST['email'];
            $cmd = "php " . APP . "testmail.php {$sendAddress}";
            $info = shell_exec($cmd);
        }
        $this->set('info', $info);
    }

    public function testsmtp()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($this->RequestHandler->IsPost()) {
            set_time_limit(10);

            $email_adress = $_POST['email'];
            $subject = "Test";
            $content = "It's test message";
            $sendResult = $this->VendorMailSender->send($subject, $content, $email_adress);
            ob_clean();
            if ($sendResult['status'] != 1) {
                $returnArray = array(
                    'code' => 201,
                    'msg' => 'Test message was sent successfully'
                );
            }else{
                $returnArray = array(
                    'code' => 101,
                    'msg' => $sendResult['error']
                );
            }

            echo json_encode($returnArray);
        }

        exit;
    }

    public function upload_license()
    {

        if ($this->RequestHandler->IsPost())
        {
            if (is_uploaded_file($_FILES['license']['tmp_name']))
            {
                $destination = Configure::read('system.license');
                $result = move_uploaded_file($_FILES['license']['tmp_name'], $destination);
                if ($result)
                {
                    $this->Systemparam->create_json_array('', 201, __('Succeed!', true));
                }
                else
                {
                    $this->Systemparam->create_json_array('', 101, __('Failed!', true));
                }
                $this->Session->write("m", Systemparam::set_validator());
            }
        }
    }

    public function allow_cdr_fields()
    {
        $incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields();
        $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields();
        $all_cdr_fields = array_merge($incoming_cdr_fields,$outgoing_cdr_fields);
        if ($this->RequestHandler->isPost())
        {
            $incoming_list = isset($this->params['form']['ingress_fields']) ? $this->params['form']['ingress_fields'] : 0;
            $outgoing_list = isset($this->params['form']['egress_fields']) ? $this->params['form']['egress_fields'] : 0;
            $sql = "DELETE FROM daily_cdr_fields WHERE type = 2 or type = 3";
            $this->Systemparam->query($sql);
            $sql_arr1 = array();
            if (!empty($incoming_list))
            {
                foreach ($incoming_list as $incoming_item)
                {
                    array_push($sql_arr1, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (2, '{$incoming_item}', '$incoming_cdr_fields[$incoming_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr1));
            }
            $sql_arr2 = array();
            if (!empty($outgoing_list))
            {
                foreach ($outgoing_list as $outgoing_item)
                {
                    array_push($sql_arr2, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (3, '{$outgoing_item}', '$outgoing_cdr_fields[$outgoing_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr2));
            }
            $this->Session->write('m', $this->Systemparam->create_json(201, 'The Carrier Portal CDR Fields are modified successfully!'));
        }

        $incoming_data = $this->Systemparam->get_daily_cdr_fields(2);
        $outgoing_data = $this->Systemparam->get_daily_cdr_fields(3);
        unset($all_cdr_fields['callduration_in_ms']);
        // $all_cdr_fields['origination_call_id'] = 'Orig Call ID';
        // $all_cdr_fields['termination_call_id'] = 'Term Call ID';
        $this->set('incoming_cdr_fields', $incoming_cdr_fields);
        $this->set('outgoing_cdr_fields', $outgoing_cdr_fields);
        $this->set('incoming_data', $incoming_data);
        $this->set('outgoing_data', $outgoing_data);
        $this->set('all_cdr_fields',$all_cdr_fields);
    }

    public function allow_cdr_fields_bak()
    {

        if ($this->RequestHandler->IsPost())
        {
            $selected_fields = $this->params['form']['allow_cdr_fields'];
            $allow_cdr_fields = implode(';', $selected_fields);
            $sql = "UPDATE system_parameter SET allow_cdr_fields = '{$allow_cdr_fields}'";
            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('The Allow CDR Fields is modified succesfully', true));
            $this->Session->write("m", Systemparam::set_validator());
        }

        $result = $this->Systemparam->query("SELECT allow_cdr_fields FROM system_parameter LIMIT 1");

        $allow_cdr_fields = explode(';', $result[0][0]['allow_cdr_fields']);


        $arr = array(
//            'switch_ip' => 'Switch IP',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_delay_second' => 'Orig Delay Second',
            'term_delay_second' => 'Term Delay Second',
            'release_cause' => 'Release Cause',
            'start_time_of_date' => 'Start Time',
            'answer_time_of_date' => 'Answer Time',
            'egress_code_asr' => 'Egress Code ASR',
            'egress_code_acd' => 'Egress Code ACD',
            'release_tod' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response To Ingress',
            'first_release_dialogue' => 'ORIG/Term Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'origination_call_id' => 'Origination Call ID',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'trunk_id_termination' => 'Egress Alias',
            'termination_source_number' => 'Term src Number',
            'termination_destination_number' => 'Term DST Number',
            'termination_destination_host_name' => 'Term IP',
            'termination_codec_list' => 'Term Codecs',
            'termination_source_host_name' => 'Outbound IP address',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Call duration in ms',
            'ingress_id' => 'Ingress ID',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'lnp_dipping_cost' => 'Lnp dipping Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'egress_id' => 'Egress Name',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate' => 'Egress Rate',
            'egress_cost' => 'Egress Cost',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_client_id' => 'Egress Client Name',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_erro_string' => 'Egress Trunk Trace',
            'ingress_rate_id' => 'Ingress Rate ID',
            'egress_rate_id' => 'Egress Rate ID',
            'orig_country' => 'Orig Country',
            'orig_code_name' => 'Orig Code Name',
            'orig_code' => 'Orig Code',
            'term_country' => 'Term Country',
            'term_code_name' => 'Term Code Name',
            'term_code' => 'Term Code',
//            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Source',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'Orig Media IP Ani',
            'origination_remote_payload_udp_address' => 'Orig Media Port Ani',
            'termination_remote_payload_ip_address' => 'Term Media IP',
            'termination_remote_payload_udp_address' => 'Term Media Port DNIS',
            'origination_destination_host_name' => 'Class4_IP',
            'origination_local_payload_ip_address' => 'Origination Local Payload IP',
            'origination_local_payload_udp_address' => 'Origination Local Payload Port',
            'termination_local_payload_ip_address' => 'Termination Local Payload IP',
            'termination_local_payload_udp_address' => 'Termination Local Payload Port',
            'trunk_type' => 'Trunk Type',
            'origination_destination_host_name' => 'Origination Profile IP',
            'origination_profile_port' => 'Origination Profile Port',
            'termination_source_host_name' => 'Termination Profile IP',
            'termination_profile_port' => 'Termination Profile Port',
//            'par_id' => 'par_id',
            'paid_user' => 'paid_user',
            'rpid_user' => 'rpid_user',
            'q850_cause' => 'Q850 Cause Code',
            'q850_cause_string' => 'Q850 Cause'
        );

        $this->set('fields', $arr);
        $this->set('allow_cdr_fields', $allow_cdr_fields);
    }

    public function invoice_setting()
    {
        $this->pageTitle = "Configuration/Invoice Setting";

        $cdr_fields = array_merge($incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields(), $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields());

        $this->set('cdr_fields', $cdr_fields);

        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'ilogo.png';

        if (file_exists($logo_path))
        {
            $logo = $this->webroot . 'upload/images/ilogo.png?'.uniqid();
        }
        else
        {
            $logo = $this->webroot . 'images/logo.png';
            $logo_path = APP . 'webroot' . DS . 'images' . DS . 'ilogo.png';
        }

        $this->set('logo', $logo);
        $this->set('root_path', $logo_path);

        $sql = "SELECT invoice_name, tpl_number, pdf_tpl, company_info,overlap_invoice_protection,send_cdr_fields,
invoice_send_mode,company_info_location,invoice_decimal_digits FROM system_parameter LIMIT 1";
        $data = $this->Systemparam->query($sql);

        if ($this->RequestHandler->IsPost())
        {
            if ($this->params['form']['invoice_log_guid'] == 1)
            {
                $invoice_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'ilogo.png';
                $tmp_invoice_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'tmp' . DS . 'ilogo_tmp.png';
                rename($tmp_invoice_path,$invoice_path);
//                $sourcepath = $_FILES['logoimg']['tmp_name'];
//                switch ($_FILES['logoimg']['type'])
//                {
//                    case 'image/png':
//                        $im = imagecreatefrompng($sourcepath);
//                        break;
//                    case 'image/jpeg':
//                        $im = imagecreatefromjpeg($sourcepath);
//                        break;
//                    case 'image/gif':
//                        $im = imagecreatefromgif($sourcepath);
//                        break;
//                }
//                imagepng($im, $invoice_path);
//                imagedestroy($im);
            }

            $selected_cdr_fields = isset($_POST['cdr_fields']) ? $_POST['cdr_fields'] : array();
            $selected_cdr_fields = implode(',', $selected_cdr_fields);

            $invoice_name = $_POST['invoice_name'];
            $tpl_number = isset($_POST['tpl_number'])? intval($_POST['tpl_number']) : 0;
            $pdf_tpl = str_replace("'", "''", $_POST['pdf_tpl']);
            $company_info = str_replace("'", "''", $_POST['company_info']);
            $overlap_invoice_protection = isset($_POST['overlap_invoice_protection']) ? 'true' : 'false';
            $company_info_location = isset($_POST['company_info_location']) ? $_POST['company_info_location'] : 0;
            $send_mode = $_POST['send_mode'];
            $invoice_decimal_digits = $_POST['invoice_decimal_digits'];
            $sql = "UPDATE system_parameter SET invoice_name = '{$invoice_name}', 
                    tpl_number = {$tpl_number}, company_info_location=$company_info_location  , pdf_tpl = '{$pdf_tpl}',
                    company_info = '{$company_info}', overlap_invoice_protection = {$overlap_invoice_protection},
                    send_cdr_fields='{$selected_cdr_fields}', invoice_send_mode=$send_mode,invoice_decimal_digits = $invoice_decimal_digits";
            $this->Systemparam->query($sql);

            if ($invoice_name && (int) $data[0][0]['invoice_name'] != $invoice_name)
            {
                $invoice_next_value = (int) $invoice_name - 1;
                $sql = "select setval('class4_seq_invoice_no'::regclass, $invoice_next_value)";
                $this->Systemparam->query($sql);
            }

            $this->Systemparam->create_json_array('', 201, __('The Invoice Setting is modified successfully!', true));
            $this->Session->write("m", Systemparam::set_validator());
            $this->redirect('/systemparams/invoice_setting');
        }
        //$sql = "SELECT invoice_name, tpl_number, pdf_tpl, company_info,overlap_invoice_protection,send_cdr_fields,invoice_send_mode,company_info_location FROM system_parameter LIMIT 1";
        //$data = $this->Systemparam->query($sql);
        $this->set('data', $data);
    }

    public function ftp_conf_change_status($id)
    {
        //Configure::write('debug', 0);

        if (!intval($id)) {
            $id = base64_decode($id);
        }

        $this->autoLayout = false;
        $this->autoRender = false;
        $ftpconf = $this->FtpConf->findById($id);

//        $id_deconde = base64_decode($id);
//        $ftpconf = $this->FtpConf->findById($id_deconde);
        if ($ftpconf['FtpConf']['active'])
        {
            $ftpconf['FtpConf']['active'] = false;
            $this->FtpConf->create_json_array("", 201, __('The FTP Configuration [%s] is inactived successfully !', true, $ftpconf['FtpConf']['alias']));
        }
        else
        {
            $ftpconf['FtpConf']['active'] = true;
            $this->FtpConf->create_json_array("", 201, __('The FTP Configuration [%s] is actived successfully !', true, $ftpconf['FtpConf']['alias']));
        }

        $this->FtpConf->save($ftpconf);

        $this->xredirect(array('controller' => 'systemparams', 'action' => 'ftp_conf'));
    }

    public function test_ftp($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        if (!intval($id)) {
            $id = base64_decode($id);
        }

        $ftp_conf = $this->FtpConf->findById($id);
        $explodedAddress = explode('://', $ftp_conf['FtpConf']['server_ip']);

        switch ($explodedAddress[0]) {
            case 'sftp':
                $conn_id = ssh2_connect($explodedAddress[1], $ftp_conf['FtpConf']['server_port']);
                $login_result = ssh2_auth_password($conn_id, $ftp_conf['FtpConf']['username'], $ftp_conf['FtpConf']['password']);
                break;
            case 'ftp':
                $conn_id = ftp_connect($explodedAddress[1], $ftp_conf['FtpConf']['server_port'], 10);
                $login_result = ftp_login($conn_id, $ftp_conf['FtpConf']['username'], $ftp_conf['FtpConf']['password']);
                break;
            default:
                $conn_id = false;
                $login_result = false;
                break;
        }

        // check connection
        if ((!$conn_id) || (!$login_result))
        {
            if (!$conn_id)
            {
                echo "FTP connection is failed! ";
            }
            if (!$login_result)
            {
                echo "Unable to connect to {$ftp_conf['FtpConf']['server_ip']} for User {$ftp_conf['FtpConf']['username']}!";
            }
            exit;
        }
        else
        {
            echo "Connected to {$ftp_conf['FtpConf']['server_ip']} for User {$ftp_conf['FtpConf']['username']}!";
        }

        // close the FTP stream 
        ftp_close($conn_id);
    }

    public function ftp_conf_edit($id = '')
    {
        $this->pageTitle = "Configuration/FTP Configuration";
        $id = base64_decode($id);
        $requestData = array(
            'start_time' => '-1',
            'print_only_headers' => true
        );

        App::import('Vendor', 'RequestFactory', array('file' => 'api/cdr/class.request_factory.php'));

        $cdr = new RequestFactory();
        $headers = $cdr->run(1, $requestData, false);
        $headers = str_replace('?', ',',$headers);
        $arr = explode(',', $headers);

        if ($this->RequestHandler->IsPost())
        {
            $alias = $_POST['alias'];
            $time = isset($_POST['time']) ? $_POST['time'] : '';
            $server_ip = $_POST['server_ip'];
            $server_port = $_POST['server_port'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $frequency = $_POST['frequency'];

            $fields = isset($_POST['fields']) ? $_POST['fields'] : array();
            if($this->check_ftp_job_name($alias,$id)){
                die;
                $this->Session->write('m', $this->Systemparam->create_json(101, __('The FTP CDR [%s] is already in use!', true, $alias)));
                $this->redirect('ftp_conf_edit/'.base64_encode($id));
            }
            $headers = array();
            foreach ($fields as $field)
            {
                array_push($headers, $arr[$field]);
            }
            $headers = implode(',', $headers);

            $fields = implode(",", $fields);


            //$headers = $_POST['headers'];
            $include_headers = $_POST['contain_headers'];
            $file_type = $_POST['file_type'];
            $server_dir = $_POST['server_dir'];
            $max_lines = $_POST['max_lines'];
            /*
              $ingress_carriers = @implode(',', $_POST['ingress_carriers']) or '';
              $egress_carriers = @implode(',', $_POST['egress_carriers']) or '';
              $ingress_carriers_all = isset($_POST['ingress_carriers_all']) ? 'true' : 'false';
              $egress_carriers_all  = isset($_POST['egress_carriers_all']) ? 'true' : 'false';
             *
             */
            $duration = $_POST['duration'];
            $every_hours = isset($_POST['every_hours']) ? $_POST['every_hours'] : NULL;
            $every_minutes = isset($_POST['every_minutes']) ? $_POST['every_minutes'] : NULL;
            $every_day = isset($_POST['every_day']) ? $_POST['every_day'] : 0;
            $ingress_release_cause = isset($_POST['ingress_release_cause']) ? $_POST['ingress_release_cause'] : array('0');
            $egress_release_cause = isset($_POST['egress_release_cause']) ? $_POST['egress_release_cause'] : array('0');

            $ingresses = @implode(',', $_POST['ingresses']) or '';
            $egresses = @implode(',', $_POST['egresses']) or '';
            $ingresses_all = isset($_POST['ingresses_all']) ? 'true' : 'false';
            $egresses_all = isset($_POST['egresses_all']) ? 'true' : 'false';
            $file_breakdown = $_POST['file_breakdown'];



            $conditions = array();
            /*
              if ($ingress_carriers_all === 'false')
              {
              if (empty($ingress_carriers))
              {
              $this->Systemparam->create_json_array('', 101, __('You must choose at least one carrier!',true));
              $this->Session->write("m",Systemparam::set_validator());
              $this->redirect('/systemparams/ftp_conf');
              }

              array_push($conditions, "ingress_client_id in  ($ingress_carriers)");
              }

              if ($egress_carriers_all === 'false')
              {
              if (empty($egress_carriers))
              {
              $this->Systemparam->create_json_array('', 101, __('You must choose at least one carrier!',true));
              $this->Session->write("m",Systemparam::set_validator());
              $this->redirect('/systemparams/ftp_conf');
              }
              array_push($conditions, "egress_client_id in  ($egress_carriers)");
              }
             */
            if ($ingresses_all === 'false')
            {
                if (empty($ingresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one ingress trunk!', true));
                    $this->Session->write("m", Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_edit/' . $id);
                }
                array_push($conditions, " ingress_id in  ($ingresses)");
            }

            if ($egresses_all === 'false')
            {
                if (empty($egresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one egress trunk!', true));
                    $this->Session->write("m", Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_edit/' . $id);
                }
                array_push($conditions, "egress_id in  ($egresses)");
            }

            if ($duration == 1)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end>0");
            }
            else if ($duration == 2)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end=0");
            }

            if (in_array('0', $ingress_release_cause))
            {
                $ingress_release_cause = '0';
            }
            else
            {
                $ingress_release_cause_conditions = array();
                foreach ($ingress_release_cause as $ingress_release_item)
                {
                    array_push($ingress_release_cause_conditions, "binary_value_of_release_cause_from_protocol_stack like''%{$ingress_release_item}%''");
                }
                $ingress_release_cause_condtion = "(" . implode(' or ', $ingress_release_cause_conditions) . ")";
                array_push($conditions, $ingress_release_cause_condtion);

                $ingress_release_cause = implode(',', $ingress_release_cause);
            }

            if (in_array('0', $egress_release_cause))
            {
                $egress_release_cause = '0';
            }
            else
            {
                $egress_release_cause_conditions = array();
                foreach ($egress_release_cause as $egress_release_item)
                {
                    array_push($egress_release_cause_conditions, "release_cause_from_protocol_stack like''%{$egress_release_item}%''");
                }
                $egress_release_cause_condtion = "(" . implode(' or ', $egress_release_cause_conditions) . ")";
                array_push($conditions, $egress_release_cause_condtion);

                $egress_release_cause = implode(',', $egress_release_cause);
            }

            $conditions_str = implode(' and ', $conditions);


            $sql = "update ftp_conf set server_ip = '$server_ip', server_port = '$server_port', username = '$username', password = '$password', 
                    frequency = $frequency, fields = \$\$$fields\$\$, headers = '$headers', contain_headers = {$include_headers}, file_type = {$file_type}, 
                    ingresses = '{$ingresses}', egresses = '{$egresses}', duration = {$duration}, 
                    ingress_release_cause = '{$ingress_release_cause}', egress_release_cause = '{$egress_release_cause}', ingresses_all = {$ingresses_all},
                    egresses_all = {$egresses_all}, conditions = '{$conditions_str}', alias = '{$alias}', time = '{$time}', server_dir = '{$server_dir}', max_lines = {$max_lines}, "
                . "every_hours = {$every_hours}, file_breakdown = {$file_breakdown},every_minutes = {$every_minutes},every_day = {$every_day} where id = {$id}"
                . " returning *";
            $return = $this->Systemparam->query($sql);
            if ($return)
            {
                $this->Systemparam->create_json_array('', 201, __('The FTP CDR [%s] is modified successfully!', true, $alias));
                $this->Session->write("m", Systemparam::set_validator());
            }
            else
            {
                $this->Systemparam->create_json_array('', 101, __('The FTP CDR [%s] is modified failed!', true, $alias));
                $this->Session->write("m", Systemparam::set_validator());
            }
        }

        $sql = "SELECT * FROM ftp_conf where id = {$id} LIMIT 1";
        $data = $this->Systemparam->query($sql);

        asort($arr);
        $fields_arr = explode(',', $data[0][0]['fields']);
        $headers_arr = explode(',', $data[0][0]['headers']);
        $default_fields = $fields_arr;
        if(!empty( array_filter($headers_arr) )) {
            $default_fields = array_combine($fields_arr, array_filter($headers_arr));
        }
        foreach ($arr as $key => $value)
        {
            if (isset($default_fields[$key]))
            {
                $default_fields[$key] = $arr[$key];
                unset($arr[$key]);
            }
        }
        $ingress_carriers = $this->Systemparam->get_carriers('ingress');
        $egress_carriers = $this->Systemparam->get_carriers('egress');
        $ingresses = $this->Systemparam->get_resource('ingress');
        $egresses = $this->Systemparam->get_resource('egress');
        $this->set('ingress_carriers', $ingress_carriers);
        $this->set('egress_carriers', $egress_carriers);
        $this->set('ingresses', $ingresses);
        $this->set('egresses', $egresses);
        $this->set('default_fields', $default_fields);
        $this->set('back_selects', $arr);
        $this->set('data', $data);
    }

    public function check_ftp_job_name($name, $id = ''){
        $this->loadModel('FtpConf');

        $count = $this->FtpConf->find('count',array(
            'conditions' => array(
                'alias' => $name,
                'id !=?' => intval($id),
            ),
        ));
        return $count;
    }


    public function ftp_conf_create()
    {
        Configure::write('debug', 2);
        $this->pageTitle = "Configuration/FTP Configuration";

        $requestData = array(
            'start_time' => '-1',
            'print_only_headers' => true
        );

        App::import('Vendor', 'RequestFactory', array('file' => 'api/cdr/class.request_factory.php'));

        $cdr = new RequestFactory();
        $headers = $cdr->run(1, $requestData, false);

        if(empty($headers)){
            $this->Session->write('m', $this->Systemparam->create_json(101, __('API connection to get fields failed!', true)));
        }

        $arr = strpos($headers, '?') !== false ? explode('?', $headers) : explode(',', $headers);

        if ($this->RequestHandler->IsPost()) {
            $alias = $_POST['alias'];
            $time = isset($_POST['time']) ? $_POST['time'] : '';
            $server_ip = $_POST['server_ip'];
            $server_port = $_POST['server_port'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $frequency = $_POST['frequency'];
            $fields = isset($_POST['fields']) ? $_POST['fields'] : array();
            $headers = array();

            if ($this->check_ftp_job_name($alias)) {
                $this->Session->write('m', $this->Systemparam->create_json(101, __('The FTP CDR [%s] is already in use!', true, $alias)));
                $this->redirect('ftp_conf_create');
            }

            foreach ($fields as $field) {
                array_push($headers, $arr[$field]);
            }
            $headers = implode(',', $headers);

            $fields = implode(',', $fields);

            $fields_show = implode(",", $_POST['fields']);

            $include_headers = $_POST['contain_headers'];
            $file_type = $_POST['file_type'];
            $server_dir = $_POST['server_dir'];
            $max_lines = $_POST['max_lines'];
            /*
              $ingress_carriers = @implode(',', $_POST['ingress_carriers']) or '';
              $egress_carriers = @implode(',', $_POST['egress_carriers']) or '';
              $ingress_carriers_all = isset($_POST['ingress_carriers_all']) ? 'true' : 'false';
              $egress_carriers_all  = isset($_POST['egress_carriers_all']) ? 'true' : 'false';
             *
             */
            $duration = $_POST['duration'];
            $every_hours = isset($_POST['every_hours']) ? $_POST['every_hours'] : NULL;
            $every_minutes = isset($_POST['every_minutes']) ? $_POST['every_minutes'] : NULL;
            $every_day = isset($_POST['every_day']) ? $_POST['every_day'] : 0;
            $ingress_release_cause = isset($_POST['ingress_release_cause']) ? $_POST['ingress_release_cause'] : array('0');
            $egress_release_cause = isset($_POST['egress_release_cause']) ? $_POST['egress_release_cause'] : array('0');
            $ingresses = @implode(',', $_POST['ingresses']) or '';
            $egresses = @implode(',', $_POST['egresses']) or '';
            $ingresses_all = isset($_POST['ingresses_all']) ? 'true' : 'false';
            $egresses_all = isset($_POST['egresses_all']) ? 'true' : 'false';
            $file_breakdown = $_POST['file_breakdown'];

            $conditions = array();
            if ($ingresses_all === 'false') {
                if (empty($ingresses)) {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one ingress trunk!', true));
                    $this->Session->write("m", Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_create');
                }
                array_push($conditions, " ingress_id in  ($ingresses)");
            }

            if ($egresses_all === 'false') {
                if (empty($egresses)) {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one egress trunk!', true));
                    $this->Session->write("m", Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_create');
                }
                array_push($conditions, "egress_id in  ($egresses)");
            }

            if ($duration == 1) {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end>0");
            } else if ($duration == 2) {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end=0");
            }


            if (in_array('0', $ingress_release_cause)) {
                $ingress_release_cause = '0';
            } else {
                $ingress_release_cause_conditions = array();
                foreach ($ingress_release_cause as $ingress_release_item) {
                    array_push($ingress_release_cause_conditions, "binary_value_of_release_cause_from_protocol_stack like''%{$ingress_release_item}%''");
                }
                $ingress_release_cause_condtion = "(" . implode(' or ', $ingress_release_cause_conditions) . ")";
                array_push($conditions, $ingress_release_cause_condtion);

                $ingress_release_cause = implode(',', $ingress_release_cause);
            }


            if (in_array('0', $egress_release_cause)) {
                $egress_release_cause = '0';
            } else {
                $egress_release_cause_conditions = array();
                foreach ($egress_release_cause as $egress_release_item) {
                    array_push($egress_release_cause_conditions, "release_cause_from_protocol_stack like''%{$egress_release_item}%''");
                }
                $egress_release_cause_condtion = "(" . implode(' or ', $egress_release_cause_conditions) . ")";
                array_push($conditions, $egress_release_cause_condtion);

                $egress_release_cause = implode(',', $egress_release_cause);
            }

            $conditions_str = implode(' and ', $conditions);


            $sql = "insert into ftp_conf (server_ip,server_port,username,password,frequency,fields,headers,contain_headers,file_type,ingresses,
                    egresses,duration,ingress_release_cause,egress_release_cause,ingresses_all,egresses_all,conditions,alias,time, server_dir,max_lines, 
                    every_hours, file_breakdown,every_minutes,every_day) values ('$server_ip',
                    '$server_port','$username','$password',$frequency,\$\$$fields\$\$, '$headers', {$include_headers},{$file_type}, '{$ingresses}','{$egresses}',
                    {$duration}, '{$ingress_release_cause}','{$egress_release_cause}',{$ingresses_all},{$egresses_all},'{$conditions_str}','{$alias}',"
                . "'{$time}', '{$server_dir}', {$max_lines}, {$every_hours}, {$file_breakdown},{$every_minutes},{$every_day}
                    )";

            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('The FTP CDR [%s] is created successfully!', true, $alias));
            $this->Session->write("m", Systemparam::set_validator());
            $this->redirect('/systemparams/ftp_conf');


//            switch ($frequency) {
//                case '1':
//                    $requestData['frequency'] = 24;
//                    $requestData['time'] = strtotime($time) - strtotime('TODAY');
//                    break;
//                case '2':
//                    $requestData['frequency'] = 24 * 7;
//                    break;
//                case '3':
//                    $requestData['frequency'] = $every_hours;
//                    break;
//            }

        }

        $data = array(
            array(
                array(
                    'server_ip' => '',
                    'server_port' => '',
                    'username' => '',
                    'password' => '',
                    'frequency' => 1,
                    'fields' => '',
                    'headers' => '',
                    'contain_headers' => 1,
                    'file_type' => 1,
                    'duration' => '',
                    'ingress_release_cause' => '0',
                    'egress_release_cause' => '0',
                    'conditions' => '',
                    'ingresses' => '',
                    'egresses' => '',
                    'ingresses_all' => 0,
                    'egresses_all' => 0,
                    "time" => '00:00',
                    'alias' => '',
                    'every_hours' => 1,
                    'every_minutes' => 15,
                    'every_day' => '',
                    'file_breakdown' => 0,
                )
            )
        );



        asort($arr);
        $ingress_carriers = $this->Systemparam->get_carriers('ingress');
        $egress_carriers = $this->Systemparam->get_carriers('egress');
        $ingresses = $this->Systemparam->get_resource('ingress');
        $egresses = $this->Systemparam->get_resource('egress');
        $this->set('ingress_carriers', $ingress_carriers);
        $this->set('egress_carriers', $egress_carriers);
        $this->set('ingresses', $ingresses);
        $this->set('egresses', $egresses);
        $this->set('back_selects', $arr);
        $this->set('data', $data);
    }

    public function ftp_conf_delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($id);
        $ftp = $this->FtpConf->findById($id);
        $alias = $ftp['FtpConf']['alias'];
        $this->FtpConf->del($id);
        $this->Systemparam->create_json_array('', 201, __('The FTP CDR [%s] is deleted successfully!', true, $alias));
        $this->Session->write("m", Systemparam::set_validator());
        $this->redirect('/systemparams/ftp_conf');
    }

    public function ftp_trigger($encode_id)
    {
        Configure::write('debug', 0);
        $id = base64_decode($encode_id);
        if ($this->RequestHandler->IsPost() && $this->params['form']['is_post'])
        {
            if (!$id)
            {
                $this->Systemparam->create_json_array('', 101, __('alias cannot be empty', true));
            }
            else
            {
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];

                $record = $this->FtpConf->findById($id);
                $request = array(
                    'ftp_url' => $record['FtpConf']['server_ip'],
                    'ftp_port' => $record['FtpConf']['server_port'],
                    'ftp_dir' => $record['FtpConf']['server_dir'],
                    'ftp_user' => $record['FtpConf']['username'],
                    'ftp_password' => $record['FtpConf']['password'],
                    'ftp_file_breakdown' => $record['FtpConf']['file_breakdown'],
                    'field' => $record['FtpConf']['fields'],
                    'ingress_id' => $record['FtpConf']['ingresses'],
                    'egress_id' => $record['FtpConf']['egresses'],
                    'start_time' => strtotime($start_time),
                    'end_time' => strtotime($end_time),
                    'ftp_file_name' => date('Y-m-d__H_i_s', strtotime($start_time)) . '_' . date('Y-m-d__H_i_s', strtotime($end_time))
                );

//                if ($record['FtpConf']['egress_release_cause']) {
//                    $tempRcause = explode(',', $record['FtpConf']['egress_release_cause']);
//                    foreach ($tempRcause as $key => $item) {
//                        if (substr($item, -1) != '*') {
//                            $tempRcause[$key] .= '*';
//                        }
//                    }
//                    $request['egress_rcause'] = implode(',', $tempRcause);
//                }
//
//                if ($record['FtpConf']['ingress_release_cause']) {
//                    $tempRcause = explode(',', $record['FtpConf']['ingress_release_cause']);
//                    foreach ($tempRcause as $key => $item) {
//                        if (substr($item, -1) != '*') {
//                            $tempRcause[$key] .= '*';
//                        }
//                    }
//                    $request['ingress_rcause'] = implode(',', $tempRcause);
//                }

                $request['egress_rcause'] = $record['FtpConf']['egress_release_cause'];
                $request['ingress_rcause'] = $record['FtpConf']['ingress_release_cause'];

                if ($record['FtpConf']['duration'] == 0 && $record['FtpConf']['duration'] !== NULL) {
                    $request['non_zero'] = 0;
                } elseif ($record['FtpConf']['duration'] == 1) {
                    $request['non_zero'] = 1;
                }

                if (isset($record['FtpConf']['file_type'])) {
                    $compressionArray = ['gz', 'tar.gz', 'tar.bz2', 'tar.bz2'];
                    $request['ftp_compress'] = $compressionArray[$record['FtpConf']['file_type']];
                }

                App::import('Vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));

                $callDetailReportAsync = new CallDetailReportAsync;
                $result = $callDetailReportAsync->ftpRequest($request);

                if ($result) {
                    $this->Systemparam->create_json_array('', 201, __('FTP Job has been triggered successfully!', true));
                } else {
                    $this->Systemparam->create_json_array('', 101, __('Failed', true));
                }
                $this->Session->write("m", Systemparam::set_validator());
                $this->redirect("ftp_log");
            }
        }
        $ftp_info = $this->Systemparam->get_single_ftp($id);
        $this->set('ftp_info', $ftp_info);
    }

    public function ftp_cdr_log()
    {
        $this->pageTitle = "Configuration/FTP Configuration";


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
        $conditions = array();

        if (isset($this->params['url']['time']) && $this->params['url']['time'] && isset($this->params['url']['end_time']) && $this->params['url']['end_time'])
        {
            $conditions[] = "FtpCdrLog.ftp_start_time >= '" . $this->params['url']['time'] . "'";
            $conditions[] = "FtpCdrLog.ftp_start_time <= '" . $this->params['url']['end_time'] . "'";
        }

        $this->set('get_data', $this->params['url']);

        $this->paginate = array(
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->set('status', array(-1 => 'Error on uploading', -2 => 'Server Error', -3 => 'FTP Upload Error', -4 => 'Killed', 1 => 'Success', 2 => 'Waiting', 3 => 'In Progress', 4 => 'Completed'));
        $this->data = $this->paginate('FtpCdrLog');

        foreach ($this->data as &$item)
        {
            $item['details'] = $this->FtpCdrLogDetail->find('all', array(
                'conditions' => array(
                    'ftp_cdr_log_id' => $item['FtpCdrLog']['id'],
                )
            ));
        }
    }

    public function ftp_log()
    {
        $this->loadModel('CdrApiExportLog');

        $this->pageTitle = "Configuration/FTP Configuration";

        $data = $this->CdrApiExportLog->find('all', array(
            'conditions' => array(
//                'user_id' => $_SESSION['sst_user_id'],
                'type' => 2
            ),
            'order' => 'id DESC'
        ));

        if (!empty($data)) {
            App::import('Vendor', 'CallDetailReportAsync', array('file' => 'api/cdr/class.async_cdr.php'));
            $callDetailReportAsync = new CallDetailReportAsync();

            foreach ($data as &$item) {
                if ($item['CdrApiExportLog']['status'] == 0) {
                    $result = $callDetailReportAsync->checkStatus($item['CdrApiExportLog']['request_id']);
//                    var_dump($result);
                    if ($result) {
                        $decodedResult = json_decode($result, true);

                        if (isset($decodedResult['status'])) {
                            if ($decodedResult['status'] == 'Complete') {
                                $item['CdrApiExportLog']['status'] = 1;
                                $this->CdrApiExportLog->save($item);
                            } else {
                                $item['CdrApiExportLog']['status'] = $decodedResult['status'];
                            }
                        } elseif (isset($decodedResult['code'])) {
                            if ($decodedResult['code'] == '404') {
                                $item['CdrApiExportLog']['status'] = 4;
                                $this->CdrApiExportLog->save($item);
                            }
                        }
                    }
                }
            }
        }

        $this->set('data', $data);
    }

    public function kill_ftp_job($ftp_log_id)
    {
//        $loaded_extensions = get_loaded_extensions();
//        if (!in_array('redis', $loaded_extensions))
//        {
//            $this->Systemparam->create_json_array('', 101, __('Failed!', true));
//            $this->Session->write("m", Systemparam::set_validator());
//            $this->redirect("ftp_log");
//        }
//        $redis = new Redis();
//        $redis->connect(Configure::read('redis.host'), Configure::read('redis.port'));
//        $client_name = Configure::read('client.name');
//        $redis_key = "cdr_ftp[$client_name]";
//        $redis_cdr_ftp = $redis->lrange($redis_key, "1", "-1");
//        $redis_item = "";
//        foreach ($redis_cdr_ftp as $cdr_ftp_item)
//        {
//            $arr_cdr_ftp_item = json_decode($cdr_ftp_item);
//            if (!isset($arr_cdr_ftp_item->ftp_log_id) || empty($arr_cdr_ftp_item->ftp_log_id) || ($ftp_log_id != $arr_cdr_ftp_item->ftp_log_id))
//            {
//                continue;
//            }
//            $redis_item = $cdr_ftp_item;
//            break;
//        }
//        if ($redis_item)
//        {
//            $flg = $redis->lrem($redis_key, $redis_item);
//            if ($flg)
//            {
////                succeed
//                $sql = "UPDATE ftp_cdr_log set status = -4 WHERE id = $ftp_log_id";
//                $result = $this->Systemparam->query($sql);
//                $this->Systemparam->create_json_array('', 201, __('The FTP job is killed successfully!', true));
//            }
//            else
//            {
////            failed
//                $this->Systemparam->create_json_array('', 101, __('Failed!', true));
//            }
//        }
//        else
//        {
////            succeed
//            $sql = "UPDATE ftp_cdr_log set status = -4 WHERE id = $ftp_log_id";
//            $result = $this->Systemparam->query($sql);
//            $this->Systemparam->create_json_array('', 201, __('The FTP job is killed successfully!', true));
//        }
//        $redis->close();
        $this->Systemparam->create_json_array('', 101, __('Redis not found!', true));
        $this->Session->write("m", Systemparam::set_validator());
        $this->redirect("ftp_log");
    }

    public function ftp_resume($ftp_log_id)
    {
        $ftp_log = $this->FtpCdrLog->findById($ftp_log_id);
        $pid = $ftp_log['FtpCdrLog']['pid'];
        $id = $ftp_log['FtpCdrLog']['id'];
        if (empty($pid))
        {
            $this->Systemparam->create_json_array('', 101, __('The FTP job pid is not found!', true));
            $this->Session->write("m", Systemparam::set_validator());
        }
        else
        {
            $cmd = "kill -9 $pid";
            $result = shell_exec($cmd);
            $this->ftp_redo($ftp_log_id, "resume");
        }
        $this->redirect("ftp_log");
    }

    public function ftp_log_detail($ftp_log_id)
    {
        $this->pageTitle = "Configuration/FTP Configuration";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'asc',
            ),
            'conditions' => array(
                'ftp_cdr_log_id' => $ftp_log_id
            ),
        );
        $this->data = $this->paginate('FtpCdrLogDetail');
    }

    public function ftp_download_local($ftp_log_detail_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ftp_log_detail = $this->FtpCdrLogDetail->findById($ftp_log_detail_id);
        $file_path = $ftp_log_detail['FtpCdrLogDetail']['local_file_path'];
        $file_name = basename($file_path);

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($file_name);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        readfile($file_path);
    }

    public function ftp_log_delete($ftp_log_detail_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ftp_log_detail_id = base64_decode($ftp_log_detail_id);
        $sql = "select *,ftp_cdr_log_detail.ftp_dir as ftp_dir_real from ftp_cdr_log_detail left join 

ftp_cdr_log on ftp_cdr_log_detail.ftp_cdr_log_id = ftp_cdr_log.id where ftp_cdr_log_detail.id = {$ftp_log_detail_id}";
        $result = $this->FtpCdrLogDetail->query($sql);
        $sql1 = "select * from ftp_conf where id = {$result[0][0]['ftp_conf_id']}";
        $result1 = $this->FtpCdrLogDetail->query($sql1);
        $server_file = $result[0][0]['ftp_dir_real'] . '/' . $result[0][0]['file_name'];

        // set up basic connection

        $conn_id = ftp_connect($result1[0][0]['server_ip'], $result1[0][0]['server_port']);

        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "Fail");
        }
        $basename = basename($server_file);

        // login with username and password
        $login_result = ftp_login($conn_id, $result1[0][0]['username'], $result1[0][0]['password']);

        if ($login_result)
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "Fail");
        }

        // try to download $server_file and save to $local_file

        if (ftp_delete($conn_id, $server_file))
        {
            $this->FtpServerLog->insert_log("DEL {$server_file}", "SUCCESS");
            $this->FtpCdrLogDetail->del($ftp_log_detail_id);
            $this->FtpCdrLogDetail->create_json_array('', 201, __('The FTP CDR FILE [%s] is deleted successfully', true, $basename));
            $this->Session->write("m", FtpCdrLogDetail::set_validator());
            $this->redirect('/systemparams/ftp_log');
        }
        else
        {
            echo "Could not delete $server_file";
            $this->FtpServerLog->insert_log("DEL {$server_file}", "Fail");
        }

        // close the connection
        ftp_close($conn_id);
    }

    public function ftp_download_all($ftp_log_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select *,ftp_cdr_log_detail.ftp_dir as ftp_dir_real from ftp_cdr_log_detail left join 
ftp_cdr_log on ftp_cdr_log_detail.ftp_cdr_log_id = ftp_cdr_log.id where ftp_cdr_log.id = {$ftp_log_id}";
        $result = $this->FtpCdrLogDetail->query($sql);
        $sql1 = "select * from ftp_conf where id = {$result[0][0]['ftp_conf_id']}";
        $result1 = $this->FtpCdrLogDetail->query($sql1);
        $unique_folder = APP . 'tmp' . DS . 'ftp' . DS . uniqid('ftp_merge');
        if (!file_exists($unique_folder))
        {
            mkdir($unique_folder);
        }


        // set up basic connection
        $conn_id = ftp_connect($result1[0][0]['server_ip'], $result1[0][0]['server_port']);

        ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 1000);

        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "Fail");
        }

        // login with username and password
        $login_result = ftp_login($conn_id, $result1[0][0]['username'], $result1[0][0]['password']);

        if ($login_result)
        {
            ftp_pasv($conn_id, true);
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "Fail");
        }

        foreach ($result as $item)
        {
            $local_file = $unique_folder . DS . $item[0]['file_name'];
            $server_file = $item[0]['ftp_dir_real'] . '/' . $item[0]['file_name'];

            if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY))
            {
                $this->FtpServerLog->insert_log("GET {$server_file}", "SUCCESS");
            }
            else
            {
                $this->FtpServerLog->insert_log("GET {$server_file}", "Fail");
            }
        }

        // uncompress files
        putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
        putenv('LC_ALL=en_US.UTF-8');
        putenv('LANG=en_US.UTF-8');

        $cmd = "gzip -d $unique_folder/*.csv.tar.gz";
        shell_exec($cmd);
        $unique_file_path = $unique_folder . DS . ".result.csv.gz";
        $cmd = "cat $unique_folder/*.csv.tar | gzip > $unique_file_path";
        shell_exec($cmd);

        ob_clean();

        header("Content-type: application/octet-stream");

        $file_name = $result1[0][0]['alias'] . '.csv.gz';

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($file_name);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        readfile($unique_file_path);
    }

    public function ftp_download($ftp_log_detail_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select *,ftp_cdr_log_detail.ftp_dir as ftp_dir_real from ftp_cdr_log_detail left join 

ftp_cdr_log on ftp_cdr_log_detail.ftp_cdr_log_id = ftp_cdr_log.id where ftp_cdr_log_detail.id = {$ftp_log_detail_id}";
        $result = $this->FtpCdrLogDetail->query($sql);

        $sql1 = "select * from ftp_conf where id = {$result[0][0]['ftp_conf_id']}";
        $result1 = $this->FtpCdrLogDetail->query($sql1);

        // define some variables


        $local_file = $path = APP . 'tmp' . DS . 'ftp' . DS . $result[0][0]['file_name'];
        $server_file = $result[0][0]['ftp_dir_real'] . '/' . $result[0][0]['file_name'];


        // set up basic connection
        $conn_id = ftp_connect($result1[0][0]['server_ip'], $result1[0][0]['server_port']);
        ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 1000);


        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "Fail");
        }

        // login with username and password
        $login_result = ftp_login($conn_id, $result1[0][0]['username'], $result1[0][0]['password']);
        ftp_pasv($conn_id, true);

        if ($login_result)
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "Fail");
        }

        // try to download $server_file and save to $local_file
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY))
        {

            $this->FtpServerLog->insert_log("GET {$server_file}", "SUCCESS");
            ob_clean();

            header("Content-type: application/octet-stream");

            //处理中文文件名
            $ua = $_SERVER["HTTP_USER_AGENT"];
            $encoded_filename = rawurlencode($result[0][0]['file_name']);
            if (preg_match("/MSIE/", $ua))
            {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            }
            else if (preg_match("/Firefox/", $ua))
            {
                header("Content-Disposition: attachment; filename*=\"utf8''" . $result[0][0]['file_name'] . '"');
            }
            else
            {
                header('Content-Disposition: attachment; filename="' . $result[0][0]['file_name'] . '"');
            }
            readfile($local_file);
        }
        else
        {
            $this->FtpServerLog->insert_log("GET {$server_file}", "Fail");
            echo "There was a problem\n";
        }

        // close the connection
        ftp_close($conn_id);
    }

    public function ftp_conf()
    {
        $this->pageTitle = "Configuration/FTP Configuration";
        $condition = array('id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            $field = $order_arr[0];
            $sort = $order_arr[1];
            $condition = array($field => $sort);
        }
        $this->paginate = array(
            'limit' => (isset($this->params['url']['size']) && $this->params['url']['size']) ? $this->params['url']['size'] : 100,
            'order' => $condition,
        );
        $this->data = $this->paginate('FtpConf');
        if (empty($this->data))
        {
            $msg = "FTP Job";
            $add_url = "/systemparams/ftp_conf_create";
            $model_name = "Systemparam";
            $this->to_add_page($model_name,$msg,$add_url);
        }
    }

    public function ftp_conf_copy()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $conf_id = base64_decode($this->params['url']['id']);
        $name = $this->params['url']['name'];
        $ftpdata = $this->FtpConf->findByAlias($name);
        if ($ftpdata)
        {
            $this->Session->write('m', $this->Systemparam->create_json(101, "The FTP CDR [{$name}] is already in use!"));
            $this->redirect('/systemparams/ftp_conf');
        }
        $result = $this->FtpConf->copy_data($conf_id, $name);
        if ($result)
        {
            $this->Session->write('m', $this->Systemparam->create_json(201, "The data is copied successfully!"));
        }
        else
        {
            $this->Session->write('m', $this->Systemparam->create_json(201, "The data is copied failed!"));
        }
        $this->redirect('/systemparams/ftp_conf');
    }

    public function ftp_server_log()
    {
        if (isset($_GET['start']))
        {
            $start_time = $_GET['start'];
            $timezone = $_GET['gmt'];
        }
        else
        {
            $start_time = date("Y-m-d 00:00:00");
            $timezone = "+00";
        }

        if (isset($_GET['end']))
        {
            $end_time = $_GET['end'];
        }
        else
        {
            $end_time = date("Y-m-d 23:59:59");
        }

        $order_arr = array('time' => 'desc');
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
        $this->pageTitle = "Configuration/FTP Server Log";
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => array(
                "time between '$start_time {$timezone}' and '$end_time $timezone'",
            )
        );
        $this->data = $this->paginate('FtpServerLog');
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }

    public function ftp_log_detail_message($id)
    {
        $this->autoLayout = false;
        $detail = $this->FtpCdrLogDetail->findById($id);
        $message = $detail['FtpCdrLogDetail']['detail'];
        $message = str_replace("\n", "<br />", $message);
        $this->set('message', $message);
    }

    public function payment_setting()
    {
        $sql = "select sys_id, daily_payment_confirmation, daily_payment_email, notify_carrier, notify_carrier_cc,stripe_account,paypal_account,stripe_public_account,paypal_service_charge,stripe_service_charge,auto_carrier_notification,payment_received_confirmation,charge_type, paypal_test_mode"
            . " from system_parameter";
        $data = $this->Systemparam->query($sql);
        $this->set('tmp', $data);

        $mail_senders = $this->Mailtmp->get_mail_senders();


        $this->set('mail_senders', $mail_senders);

        if ($this->RequestHandler->IsPost())
        {
            $post_arr = $this->params['form'];

            if (isset($post_arr['daily_payment_confirmation']) && $post_arr['daily_payment_confirmation'])
            {
                $post_arr['daily_payment_confirmation'] = true;
            }
            else
            {
                $post_arr['daily_payment_confirmation'] = false;
                unset($post_arr['daily_payment_mail']);
            }

            if (isset($post_arr['notify_carrier']) && $post_arr['notify_carrier'])
            {
                $post_arr['notify_carrier'] = true;
            }
            else
            {
                $post_arr['notify_carrier'] = false;
                unset($post_arr['notify_carrier_cc']);
            }
            unset($post_arr['payment_sent_from']);

            unset($post_arr['payment_setting_subject']);

            unset($post_arr['payment_content']);

            if (isset($post_arr['auto_carrier_notification']) && $post_arr['auto_carrier_notification']) {
                $post_arr['auto_carrier_notification'] = true;
            } else {
                $post_arr['auto_carrier_notification'] = false;
            }

            $post_arr['sys_id'] = $data[0][0]['sys_id'];
            $post_arr['paypal_test_mode'] = isset($post_arr['paypal_test_mode']) ? true : false;

            $this->Systemparam->save($post_arr);
            $affect = $this->Systemparam->getAffectedRows();
            if ($affect)
            {
                $this->Session->write('m', $this->Systemparam->create_json(201, 'The Payment Setting is modified successfully!'));
                $this->redirect('/systemparams/payment_setting');
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

    public function api_configuration()
    {
        $this->pageTitle = 'API Configuration::Configuration';



        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);

        $api_key = $sections['web_base']['system_token'];



        $this->set('api_key', $api_key);
    }

    public function regenerate_api_key()
    {
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);

        $sections['web_base']['system_token'] = substr(md5(rand()), 0, 7);

        if (isset($sections['script_ftp_cdr']['cdr_head']))
        {
            $sections['script_ftp_cdr']['cdr_head'] = "\"" . $sections['script_ftp_cdr']['cdr_head'] . "\"";
        }
        if (isset($sections['script_ftp_cdr']['cdr_alias']))
        {
            $sections['script_ftp_cdr']['cdr_alias'] = "\"" . $sections['script_ftp_cdr']['cdr_alias'] . "\"";
        }
        if (isset($sections['storage_server']['ftp_password']))
        {
            $sections['storage_server']['ftp_password'] = "\"" . $sections['storage_server']['ftp_password'] . "\"";
        }
        $result = $this->write_ini_file($sections, CONF_PATH, true);
        if ($result === false)
            $this->Session->write('m', $this->Systemparam->create_json(101, 'The Configuration is failed!'));
        else
            $this->Session->write('m', $this->Systemparam->create_json(201, 'The Configuration is successful!'));
        $this->redirect('/systemparams/api_configuration');
    }

    public function ajax_update_token(){

        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
//        pr($this->params['form']);die;
        $this->loadModel('SwitchProfile');
        $switch_info = array();
        foreach ($this->params['form']['switch_id'] as $key => $switch_item){
            $switch_info[$key]['id'] = $switch_item;
            $switch_info[$key]['pcap_token'] = $this->params['form']['pcap_token'][$key];
        }
        $flg = $this->SwitchProfile->saveAll($switch_info);
        if ($flg === false){
            return json_encode(array('status' => 0));
        }else{
            return json_encode(array('status' => 1));
        }


    }

    public function login_page_content(){
        Configure::write('debug', 2);
        $info = $this->Systemparam->find('first',array(
            'fields' => array('login_page_content','login_fit_image','sys_id', 'login_captcha', 'allow_registration'),
        ));
//        pr($this->params);die;
        if ($this->RequestHandler->isPost()){
            $save_data = array(
                'sys_id' => $info['Systemparam']['sys_id'],
                'login_page_content' => $this->params['data']['login_page_content'],
                'login_fit_image' => $_POST['login_fit_image']
            );
            $loginCaptcha = $_POST['login_captcha'] ? 'true': 'false';
            $res = $this->Systemparam->query("UPDATE system_parameter SET login_captcha = {$loginCaptcha} WHERE sys_id = {$info['Systemparam']['sys_id']}");

            $allow_registration = $_POST['allow_registration'] ? 'true': 'false';
            $res = $this->Systemparam->query("UPDATE system_parameter SET allow_registration = {$allow_registration} WHERE sys_id = {$info['Systemparam']['sys_id']}");

            if ($this->Systemparam->save($save_data) === false) {
                $this->Session->write('m', $this->Systemparam->create_json(101, 'The Configuration is failed!'));
            }
            else{
                $this->Session->write('m', $this->Systemparam->create_json(201, 'The Configuration is successfully!'));
            }

//            $is_change_background = $_POST['is_change_background'];
//            if ($is_change_background)
//            {
//                $background_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'background.png';
//                $tmp_background_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'tmp' . DS . 'background_tmp.png';
//                rename($tmp_background_path,$background_path);
//            }

            $this->redirect('login_page_content');
        }
        $this->set('login_page_content',$info['Systemparam']['login_page_content']);
        $this->set('login_fit_image',$info['Systemparam']['login_fit_image']);
        $this->set('login_captcha',$info['Systemparam']['login_captcha']);
        $this->set('allow_registration',$info['Systemparam']['allow_registration']);

        $background_tmp_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS. 'tmp'. DS. 'background_tmp.png';
        $background_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'background.png';
        if (file_exists($background_tmp_path)) {
            $background = $this->webroot . 'upload/images/tmp/background_tmp.png';
        } elseif (file_exists($background_path)) {
            $background = $this->webroot . 'upload/images/background.png';
        } else {
            $background = '';
        }
        $this->set('background', $background);
    }

    public function remove_login_backround_image() {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $background_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'background.png';
        if (file_exists($background_path)) {
            $result = unlink($background_path);
        } else {
            $result = false;
        }
        $this->redirect('login_page_content');
    }

    public function checkApiCalls()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $data = [];
        ob_start();
        foreach ($this->params['form']['apiUrls'] as $key => $url) {
            $data[$key] = false;
            $url = rtrim($url, '/\\');

            if ($key == 'realTime') {
                App::import('vendor', 'ApiConnector', array('file' => 'api/cdr/connector.php'));

                $apiConnector = new ApiConnector($url);
                $data[$key] = $apiConnector->isConnected();
            } else {
                switch ($key)
                {
                    case 'api_invoice_url':
                        $url .= '/apitest';
                        break;
                    case 'api_invoice_orig_url':
                        $url .= '/ping';
                        break;
                    case 'api_import_url':
                        // removing last 'importfile' part
                        $url = explode('/', $url);
                        array_pop($url);
                        $url = implode('/', $url).'/apitest';
                        break;
                }

                if(!$url){
                    $data[$key] = false;
                    continue;
                }

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $res = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->ApiLog->addRequest($url, null, null, 1, $httpcode);

                if($res  === false){
                    continue;
                }
                curl_close($ch);

                if(($key == 'api_invoice_url' || $key == 'api_import_url')  && $res=='running'){
                    $data[$key] = true;
                }elseif($httpcode == '200'){
                    $data[$key] = true;
                }
            }
            sleep(1);
        }
        ob_end_flush();
        $this->jsonResponse($data);
    }

    public function checkDBConnection()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $conn_config = $this->params['form'];
        $dbconn = pg_connect("host={$conn_config['hostaddr']} port={$conn_config['port']} dbname={$conn_config['dbname']} user={$conn_config['user']} password={$conn_config['password']}");
        if($dbconn === false){
            $this->jsonResponse(['status' => false, 'msg' => 'Unconnected']);
        }else{
            $this->jsonResponse(['status' => true, 'msg' => 'Connected']);
        }

    }

    private function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }


}