<?php 

class LnpRequestController extends DidAppController
{
    var $name = 'LnpRequest';
    var $uses = array('did.DidRepos', 'did.DidAssign', 'Resource', 'Cdr', 'did.DidRequest', 'did.LnpRequest', 'did.LnpRequestDetail');
    var $components = array('RequestHandler', 'Session');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        parent::beforeFilter();
        if ($login_type == 3)
            return true;
    }
    
    public function index()
    {
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'LnpRequest.request_date' => 'desc',
            ),
            'conditions' => array(),
        );
        if ($_SESSION['login_type'] == 3) {
            array_push($this->paginate['conditions'], "LnpRequest.user_id = {$_SESSION['sst_user_id']}");
        }
        $this->set('status', array('Waiting', 'Completed'));
        $this->data = $this->paginate('LnpRequest');
    }
    
    public function detail($request_id)
    {
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'LnpRequestDetail.number' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'lnp_request',
                    'alias' => "LnpRequest",
                    'type' => 'INNER',
                    'conditions' => array(
                        'LnpRequest.id = LnpRequestDetail.request_id',
                    ),
                ),
            ),
            'conditions' => array('LnpRequestDetail.request_id' => $request_id),
        );
        if ($_SESSION['login_type'] == 3) {
            array_push($this->paginate['conditions'], "LnpRequest.user_id = {$_SESSION['sst_user_id']}");
        }
        $this->set('status', array('Waiting', 'Completed', 'Failed'));
        $this->data = $this->paginate('LnpRequestDetail');
    }
    
    public function assign($request_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($_SESSION['login_type'] == 1) {
            $this->LnpRequest->updateAll(
                    array('LnpRequest.status' => 1),
                    array('LnpRequest.id' => $request_id)
            );     
            $this->LnpRequest->updateAll(
                    array('LnpRequestDetail.status' => 1),
                    array('LnpRequestDetail.request_id' => $request_id)
            );  
            $this->Session->write('m', $this->LnpRequest->create_json(201, __('The LNP Request #' . $request_id . ' is confirmed successfully!', true)));
            $this->redirect('/did/lnp_request/index/');
        } else {
            $this->redirect('/did/lnp_request/index/');
        }
    }
    
    public function get_file($request_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $request = $this->LnpRequest->findById($request_id);
        $filename = $request['LnpRequest']['file'];
        $fullpath = APP .  'webroot' .  DS . 'upload' . DS . 'lnp_request' . DS . $filename;
        
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
        readfile($fullpath);
    }
    
    public function push()
    {
        $this->pageTitle = "LNP Request";
        if ($this->RequestHandler->isPost()) {
            $user_id = $_SESSION['sst_user_id'];
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                $filename = $_SESSION['sst_user_name'] . '_' . date("Y_m_d_H_i_s") . uniqid() .'.doc';
                $destpath = APP .  'webroot' .  DS . 'upload' . DS . 'lnp_request' . DS . $filename;
                $sourcepath = $_FILES['upload_file']['tmp_name'];
                
                move_uploaded_file($sourcepath, $destpath);
                
                $request_type = $_POST['request_type'];
                $number_to_port = $_POST['number_to_port'];
                $range_to  = $_POST['range_to'];
                $multiple_numbers_request = $_POST['multiple_numbers_request'];
                $count = 0;
                $numbers = array();
                
                if ($request_type == 0) {
                    if (!empty($range_to)) {
                        $numbers = range($number_to_port, $range_to);
                        $count = count($numbers);
                    } else {
                        array_push($numbers, $number_to_port);
                        $count = 1;
                    }                    
                    
                } else if ($request_type == 1) {
                    $numbers = explode(',', $multiple_numbers_request);
                    $count = count($numbers);
                }
                
                $request_id = $this->LnpRequest->create_request($user_id, $count, $request_type, $filename); 
                
                foreach ($numbers as $number) {
                    $this->LnpRequest->create_request_detail($request_id, $number);
                }
                
                $this->Session->write('m', $this->LnpRequest->create_json(201, __('The LNP Request #' . $request_id . ' is assigned successfully!', true)));
                $this->redirect('/did/lnp_request/index/');
            }
        }
    }
    
}
