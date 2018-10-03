<?php

class ExchangeImportLogController extends AppController {

    var $name = 'ExchangeImportLog';
    var $helpers = array("AppImportExportLog", 'Paginator');
    var $uses = array('ImportExportLog');
    var $components = array();

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }

    public function index() {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
    }

    public function import_log() {
        //$this->autoLayout = FALSE;
        //$this->autoRender = FALSE;
        //$this->pageTitle='Import/Import Log';

        $where = "";
        if (!empty($_GET['file_name'])) {
            $where = "";
        }

        $sql = "select count(*) from import_rate_status where rate_table_id in(
select rate_table_id from resource where rate_table_id != 0) {$where}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->ImportExportLog->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //$this->Client->create_json_array('',101,__('Fail Registration!',true));
        $order_sql = "order by time desc";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }
        
        $sql = "select * from import_rate_status where rate_table_id in(
select rate_table_id from resource where rate_table_id != 0) 
{$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";


        $data = $this->ImportExportLog->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function download(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $file = $_GET["file"];
        $basename = basename($file);
        if(file_exists($file)){
            ob_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename={$basename}");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            readfile($file);
        }else{
            $this->ImportExportLog->create_json_array('',101,__('File does not exist!',true));
            $this->xredirect("/exchange_import_log/import_log");
        }
    }

}

?>
