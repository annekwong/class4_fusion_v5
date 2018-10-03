<?php

/*
 * 重新统计CDR数据
 */

class RestatisticsController extends AppController {
    
    var $name = 'Restatistics';
    var $uses = array('Cdr');
    var $components = array('RequestHandler');
    var $helpers = array ('javascript', 'html','AppCdr');
    
    //读取该模块的执行和修改权限
    public function beforeFilter(){
        $this->checkSession ( "login_type" );//核查用户身份
        $login_type = $this->Session->read('login_type');
        parent::beforeFilter();
    }
    
    public function index() {
        if ($this->RequestHandler->isPost()) {
            $start = str_replace('-', '', $_POST['from']);
            $end = str_replace('-', '', $_POST['to']);
            $results = $this->Cdr->query("select re_total('$start', '$end') as total");
            if($results[0][0]['total'] == '1') {
                $cmd = "php " . APP. "statistic.php";
                $shell = shell_exec($cmd);
            }
        }
    }
    
} 

?>
