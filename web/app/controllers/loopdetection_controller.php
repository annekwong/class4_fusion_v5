<?php

class LoopdetectionController extends AppController {
    
    var $name = "Loopdetection";
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('Loopdetection');
    
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    public function index() {
        $this->pageTitle = 'Statistics/Loop Detection';
        $duration = 5;
        $threshold = 5;
        if(isset($_GET['duration'])) {
            $duration = $_GET['duration'];
            $threshold = empty($_GET['threshold']) ? "0" : $_GET['threshold'];
        }
        $data = $this->Loopdetection->get_data($duration, $threshold);
        $this->set('data', $data);
        $this->set('duration', $duration);
        $this->set('threshold', $threshold);
    }
    
    public function put_block_list() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ingress_id = $_POST['ingress_id'];
        $egress_id = $_POST['egress_id'];
        $ani = $_POST['ani'];
        $dnis = $_POST['dnis'];
        $this->Loopdetection->put_block_list($ingress_id, $egress_id, $ani, $dnis);       
        echo 1;
    }
    
    
    
}

?>
