<?php

class RatemailhistorysController extends AppController {
    
    var $name = "Ratemailhistorys";    // 控制器名称
    var $helpers = array('Javascript','Html', 'Text');  // 帮助库
    var $components = array('RequestHandler');  // 组建-请求
    var $uses = array('Ratemailhistory'); // 使用Model
    
    public function index() {
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $counts = $this->Ratemailhistory->find('count');
        $page = new MyPage ();
        $page->setTotalRecords ( $counts ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
	$pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
    	if(isset($_GET['order_by'])){
    		$arrPx=explode("-", $_GET['order_by']);
        	$order = 'Ratemailhistory.'.$arrPx[0]." ".$arrPx[1];
        }else{
        	$order = 'Ratemailhistory.send_date DESC';
        }
        $results = $this->Ratemailhistory->find('all', array(
            'fields' => array('Ratemailhistory.id', 'Ratemailhistory.send_date', 'Ratemailhistory.send_to'), //字段名数组
            'order' => array($order), //定义顺序的字符串或者数组
            'limit' => $pageSize, 
            'page' => $currPage
        ));
        
        $page->setDataArray ( $results );
        $this->set('p',$page);
    }
    
    public function detail($id) {
        $data = $this->Ratemailhistory->find('first', array(
            'fields' => array('Ratemailhistory.files', 'Ratemailhistory.mail_content'),
            'conditions' => array('Ratemailhistory.id' => $id)
        ));
        $this->set('data', $data);
    }
    
    public function down($file) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $file = base64_decode($file);
        $filename = basename($file);
        $content = file_get_contents($file);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=$filename");
        echo $content;
    }
    
    public function delete($id) {
        $this->Ratemailhistory->del($id);
        $this->Ratemailhistory->create_json_array('#myform',201,__('Deleted successfully',true));
        $this->Session->write("m",Ratemailhistory::set_validator());
        $this->redirect ( "/ratemailhistorys" );
    }
    
    
}

?>
