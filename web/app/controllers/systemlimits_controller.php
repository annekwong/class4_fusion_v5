<?php

class SystemlimitsController extends AppController
{

    var $name = 'Systemlimits';
    var $components = array('PhpTelnet');
    var $uses = array("Systemlimit");

    function index()
    {
        $this->redirect('view');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    public function upload_code2()
    {
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Systemlimit->import_data(__('UploadRate', true)); //上传数据
        $this->Systemlimit->create_json_array("", 201, __('rateUploadSuccess', true));
        $this->Session->write('m', Systemlimit::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    public function upload_license()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['license']['tmp_name']))
        {
            $license_path = Configure::read('license.path');
            $destpath = $license_path . DS . 'dnl_softswitch.license';
            $sourcepath = $_FILES['license']['tmp_name'];
            move_uploaded_file($sourcepath, $destpath);
            $this->Systemlimit->create_json_array("", 201, 'Your Licence File is Uploaded successfully');
            $this->Session->write('m', Systemlimit::set_validator());
        }
        else
        {
            $this->Systemlimit->create_json_array("", 101, 'You did not select a file to be uploaded!');
            $this->Session->write('m', Systemlimit::set_validator());
        }
        $this->redirect('/systemlimits/view');
    }

    public function reload()
    {
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sip_ip = $_POST['sip_ip'];
        $sip_port = $_POST['sip_port'];
        $max_call_limit = $_POST['max_call_limit'];
        $max_cps_limit = $_POST['max_cps_limit'];
        $old_call_limit = $_POST['old_call_limit'];
        $old_cps_limit = $_POST['old_cps_limit'];
        $switch_name = $_POST['switch_name'];
        $cmd = "reload_switch_license";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, $sip_ip, $sip_port))
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
        $this->save_license_modification_log($switch_name, $old_call_limit, $max_call_limit, 3);
        $this->save_license_modification_log($switch_name, $old_cps_limit, $max_cps_limit, 4);
        echo 1;
    }

    public function down_license_key()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        $license_path = Configure::read('license.path');
        $destpath = $license_path . DS . 'dnl_softswitch.key';
        $filename = basename($destpath);

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        readfile($destpath);
    }

    //上传code	
    public function import_rate()
    {
        $rate_table_id = $this->params['pass'][0];
        $list = $this->Systemlimit->query("select name   from  jurisdiction where   id=$rate_table_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate()
    {
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select    alias,prefix   from  jurisdiction_prefix  where jurisdiction_id=$rate_table_id";
        $this->Systemlimit->export__sql_data(__('Jurisdiction Prefix ', true), $download_sql, 'prefix');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function view()
    {
        $this->pageTitle = "Switch/System Limit Configuration";
        //$this->set('post', $this->Systemlimit->findsystemlimit());

        $content = "";
        $cmd = "get_license_limit";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048))
            {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE)
                {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
        }


        socket_close($socket);

        $dates = explode("\n", $this->_strip_invalid_xml_chars($content));

        $this->set('date', $dates);
    }



    public function configuration()
    {
        $sip_infos = array();
        $this->pageTitle = "Switch/System Limit Configuration";
        $sip_servers = $this->Systemlimit->query("select distinct switch_name,lan_ip, lan_port from switch_profile order by switch_name");
        $is_back_run = false;
        foreach ($sip_servers as $sip_server)
        {
            $sip_ip = $sip_server[0]['lan_ip'];
            $sip_port = $sip_server[0]['lan_port'];

            $switch_name = $sip_server[0]['switch_name'];
            $conn = $this->get_connect_obj();
            $system_limit = $conn->backend_get_switch_configuration($sip_ip,$sip_port);
            if(!empty($system_limit)) $is_back_run = true;

            array_push($sip_infos, array_merge(array(
                'sip_ip' => $sip_ip,
                'sip_port' => $sip_port,
                'switch_name' => $switch_name,
            ), $system_limit));
        }
        $this->set("sip_infos", $sip_infos);
        $this->set("is_back_run", $is_back_run);
    }

    public function service_download()
    {
        if (isset($this->params['pass'][0]))
        {
            $id = $this->params['pass'][0];
            $this->set('post', $this->Systemlimit->query("select  * from  service_download  where service_download_id=$id"));
        }
    }

    public function delete_service()
    {
        $id = $this->params['pass'][0];
        $this->Systemlimit->query("delete   from  service_download  where   service_download_id=$id");
        $this->redirect("/systemlimits/service_view/");
    }

    //游戏和铃声下载设置
    public function service_view()
    {
        $this->set('post', $this->Systemlimit->query("select  * from  service_download"));
    }

    //计费区域管理
    function jurisdiction_view_save()
    {
        Configure::write('debug', 0);
        $data['id'] = array_keys_value($this->params, 'form.id', '');
        $data['name'] = array_keys_value($this->params, 'form.name');
        $data['alias'] = array_keys_value($this->params, 'form.alias');
        $this->loadModel('Jurisdiction');
        if ($this->Jurisdiction->save($data))
        {
            $this->params['message']['success'][] = '';
        }
    }

    public function jurisdiction_view()
    {
        if ($this->RequestHandler->isPost())
        {
            $this->jurisdiction_view_save();
        }
        $country_id = $this->params['pass'][0];
        $this->set('country_id', $country_id);
        $this->set('p', $this->Systemlimit->jurisdiction_view($country_id, $this->_order_condtions(array('id', 'name', 'alias'))));
        if (array_keys_value($this->params, 'isAjax'))
        {
            $this->render('jurisdiction_view_ajax');
        }
        //$this->set('post',$this->Systemlimit->query("select  * from  jurisdiction"));
    }

    public function jurisdiction_view_prefix()
    {
        $jurisdiction_id_where = '';

        if (isset($this->params['pass'][0]))
        {
            $jurisdiction_id_where = "where jurisdiction_id={$this->params['pass'][0]}";
            $_SESSION['jurisdiction_id'] = $this->params['pass'][0];
            $jurisdiction_id = $this->params['pass'][0];
        }
        else
        {
            $jurisdiction_id = $_SESSION['jurisdiction_id'];
            $jurisdiction_id_where = "where jurisdiction_id={$jurisdiction_id}";
        }
        $this->set('p', $this->Systemlimit->findAll_jurisdictionprefix($jurisdiction_id));
    }

    public function delete_jurisdiction()
    {

        if (isset($this->params['pass'][0]))
        {
            $id = $this->params['pass'][0];
            $this->Systemlimit->begin();
            $this->Systemlimit->query("delete from  jurisdiction_prefix  where jurisdiction_id=$id");
            $this->set('post', $this->Systemlimit->query("delete from  jurisdiction  where id=$id"));
            $this->Systemlimit->commit();
            $this->Systemlimit->create_json_array("", 201, __('delete Success', true));
            $this->Session->write('m', Systemlimit::set_validator());
            $this->redirect("/systemlimits/jurisdiction_view/");
        }
    }

    public function add_jurisdiction_prefix()
    {
        $r = $this->Systemlimit->query("select * from jurisdiction");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        $this->set('l', $l);
        if (isset($this->params['pass'][0]))
        {
            $id = $this->params['pass'][0];
            $this->set('post', $this->Systemlimit->query("select  * from  jurisdiction_prefix  where id=$id"));
        }
    }

    //更新系统容量设置
    public function ajax_update()
    {
        if (!$_SESSION['role_menu']['Switch']['systemlimits']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $ingress_cap_limit = $_POST['ingressC'];
        $ingress_cps_limit = $_POST['ingressP'];
        $sip_ip = $_POST['sip_ip'];
        $sip_port = $_POST['sip_port'];
        $old_cap_limit = $_POST['old_cap_limit'];
        $old_cps_limit = $_POST['old_cps_limit'];
        $switch_name = $_POST['switch_name'];

        $conn = $this->get_connect_obj();
        $rst = $conn->backend_set_switch_configuration($sip_ip,$sip_port,$ingress_cps_limit,$ingress_cap_limit);
        if ($rst === true)
        {
            $this->save_license_modification_log($switch_name,$old_cap_limit, $ingress_cap_limit, 1);
            $this->save_license_modification_log($switch_name,$old_cps_limit, $ingress_cps_limit, 2);
            $this->set('extensionBeans', 1);
        }
        else{
            $err = $conn->get_error();
            $this->set('extensionBeans', "Server Exception: $err");
        }


    }

    /**
     *
     * @param type $old_value
     * @param type $new_value
     * @param type $type   1 为 call_limit 2 为cps_limit
     * @return boolean
     */
    public function save_license_modification_log($switch_name,$old_value, $new_value, $type)
    {
        if (is_null($switch_name) || is_null($old_value) || is_null($new_value) || !in_array($type, array('1', '2','3','4')))
        {
            return FALSE;
        }
        $old_value = intval($old_value);
        $new_value = intval($new_value);
        if($old_value == $new_value)
        {
            return FALSE;
        }

        $modify_by = $_SESSION['sst_user_name'];
        $sql = "INSERT INTO license_modification_log (modify_on, modify_by, old_value, new_value, type,switch_name) "
            . "VALUES (CURRENT_TIMESTAMP(0),'{$modify_by}',{$old_value},{$new_value},{$type},'{$switch_name}')";

        return $this->Systemlimit->query($sql);
    }

    public function test_cmd()
    {
        $result = $this->send("c4_config_system_limit u");
        pr($result);
    }

    public function ajax_update_service()
    {
        Configure::write('debug', 0);
        $service_download_id = $_POST['service_download_id'];
        $name = $_POST['name'];
        $url = $_POST['url'];
        $sql = "insert into service_download(service_download_id,name,url)values($service_download_id,'$name','$url') ";
        $this->Systemlimit->query($sql);
        $this->set('extensionBeans', '1');
    }

    public function ajax_update_jurisdiction_prefix()
    {
        Configure::write('debug', 0);
        $id = $_POST['id'];
        $alias = $_POST['alias'];
        $prefix = $_POST['prefix'];
        $jurisdiction_id = $_SESSION['jurisdiction_id'];
        $id_where = ereg("^[0-9]+$", $id) ? " and  id<>$id" : '';
        //验证prefix重复
        $list = $this->Systemlimit->query("select count(*)  from jurisdiction_prefix  where prefix= '$prefix'  ");
        if ($list[0][0]['count'] > 0)
        {
            $this->set('extensionBeans', '3');
        }
        else
        {

            if (empty($id))
            {
                $sql = "insert into jurisdiction_prefix(alias,prefix,jurisdiction_id)values('$alias','$prefix',$jurisdiction_id) ";
            }
            else
            {
                $sql = " update jurisdiction_prefix  set  alias='$alias',prefix='$prefix',jurisdiction_id=$jurisdiction_id where id=$id  ";
            }

            $this->Systemlimit->query($sql);
            $this->set('extensionBeans', '1');
        }
    }

    public function ajax_update_jurisdiction()
    {
        Configure::write('debug', 0);
        $id = $_POST['id'];
        $alias = $_POST['alias'];
        $name = $_POST['name'];
        /* 			$id='';
          $alias='78787';
          $name='7878'; */
        $id_where = ereg("^[0-9]+$", $id) ? " and  id<>$id" : '';
        //验证prefix重复
        $list = $this->Systemlimit->query("select count(*)  from jurisdiction  where name= '$name'  $id_where");
        $list2 = $this->Systemlimit->query("select count(*)  from jurisdiction  where alias= '$alias'  $id_where");
        if ($list[0][0]['count'] > 0)
        {
            pr($list[0][0]['count']);
            $this->set('extensionBeans', '3');
            return;
        }
        if ($list2[0][0]['count'] > 0)
        {
            $this->set('extensionBeans', '4');
            return;
        }

        $sql = empty($id) ? "insert into jurisdiction(alias,name)values('$alias','$name') " : "update jurisdiction set alias='$alias',  name='$name'  where id=$id";
        $this->Systemlimit->query($sql);
        $this->set('extensionBeans', '1');
    }

    /*
     * 发送命令到Socket 并返回执行命令结果
     */

    private function send($cmd = null)
    {
        $result = $this->PhpTelnet->getResult("api " . $cmd);
        return $result;
    }


}
