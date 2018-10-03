<?php


class CopycdrController extends AppController {
    
    var $name = "Copycdr"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('CdrLog');
    
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
        $this->pageTitle="Switch/CDR Import";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CdrLog.finish_time' => 'desc',
            ),
        );
        
        if (isset($_GET['showerror']))
            $this->paginate['conditions'] = array(
                'status !=' => 1,
            );
        
        $status = array(
            '-2' => 'copy cdr data error',
            '-1' => 'open fail',
            '1'  => 'import success',
            '2'  => 'file empty',
        );
        $this->data = $this->paginate('CdrLog');
        $this->set('status', $status);
    }
    
    public function get_error_info_detail() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        $sql = "SELECT error_info FROM cdr_log WHERE id = {$id}";
        $data = $this->CdrLog->query($sql);
        echo $data[0][0]['error_info'];
    }
    
    
}


?>
