<?php

class UploadAniController extends AppController
{
    var $name = "UploadAni";
    var $uses = array('UploadAniLog');
    var $components = array('RequestHandler');
    
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
    }
    
    public function index()
    {
        $this->pageTitle = "Configuration/Upload ANI";
        if ($this->RequestHandler->ispost()) {
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $upload_file = $path . DS . trim($_POST['myfile_guid']) .".csv";
            $backend_ani_path = Configure::read("backed.ani_file");
            // write log
            
            $upload_log = array(
                'UploadAniLog' => array(
                    'upload_by' => $_SESSION['sst_user_name'],
                    'file_path' => trim($_POST['myfile_guid']),
                 )
            );
                        
            $this->UploadAniLog->save($upload_log);
            
            copy($upload_file, $backend_ani_path);
            
            $backend_ip   = Configure::read('backend.ip');
            $backend_port = Configure::read('backend.port');

            //$content = "";
            $cmd = "load_ani_list_api";
            $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
            if (socket_connect($socket, $backend_ip, $backend_port)) {
                socket_write($socket, $cmd, strlen($cmd));
            }
            /*
            while ($out = socket_read($socket, 2048)) {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
             * 
             */
            socket_close($socket);
            
            $this->Session->write('m', $this->UploadAniLog->create_json(201, __('The ANI file is uploaded successfully!', true)));
            $this->xredirect('/upload_ani');
        }
    }
    
    public function history()
    {
        $this->pageTitle = "Configuration/Upload ANI Log";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        
        $this->data = $this->paginate('UploadAniLog');
    }
    
    public function down_ani_file($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $upload_ani_log = $this->UploadAniLog->findById($id);
        $file_path = $upload_ani_log['UploadAniLog']['file_path'];
        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
        $upload_file = $path . DS . $file_path .".csv";
        
        
        $filename = basename($upload_file);
        
        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        //让Xsendfile发送文件
        readfile($upload_file);
    }
    
}