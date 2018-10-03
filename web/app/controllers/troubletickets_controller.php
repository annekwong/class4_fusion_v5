<?php

class TroubleticketsController extends AppController {
    
    var $name = "Troubletickets"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('Clientcdr');
    
    public function route_report() {
        if(isset($_GET['isQuery'])) {
            empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
            empty($_GET['size'])?$pageSize = 100:$pageSize = $_GET['size'];
            $start = $_GET['start'];     
            $end = $_GET['end'];
            $gmt = $_GET['gmt'];
            $call_count = isset($_GET['call_count']) && !empty($_GET['call_count']) ? "and call_count <= " . $_GET['call_count'] : "";
            $asr = isset($_GET['asr']) && !empty($_GET['asr']) ? "and asr <= " . $_GET['asr'] : "";
            $acd = isset($_GET['acd']) && !empty($_GET['acd']) ? "and acd <= " . $_GET['acd'] : "";
            $pdd = isset($_GET['pdd']) && !empty($_GET['pdd']) ? "and pdd >= " . $_GET['pdd'] : "";
            require_once 'MyPage.php';
            $page = new MyPage();
            $count = $this->Clientcdr->troubletickets_report_count($start, $end,$gmt,$call_count, $asr, $acd, $pdd);
            $page->setTotalRecords($count);
            $page->setCurrPage($currPage);
            $page->setPageSize($pageSize);
            $currPage = $page->getCurrPage()-1;
            $pageSize = $page->getPageSize();
            $results = $this->Clientcdr->troubletickets_report($start, $end, $gmt,$call_count, $asr, $acd, $pdd, $currPage, $pageSize);
            $page->setDataArray($results);
            $this->set('p', $page);
        }
    }
    
    public function sendtt() {
    }
    
}


?>
