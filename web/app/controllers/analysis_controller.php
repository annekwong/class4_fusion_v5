<?php
class AnalysisController extends AppController {
    var $name = 'Analysis';
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('Rate'); 
    
    public function index() {
        if($this->RequestHandler->isPost()) {
            $ratetables = isset($_POST['ratetable'])?$_POST['ratetable']:array();
            $ratetablestr = implode(",",$ratetables);
            
            $bool = '';
            $temp = '';
            
            if(empty($_POST['ratetables'])) {
                if($_POST['report_type'] == '0') {
                    $bool = 'false';
                } else {
                    $bool = 'true';
                }
            } else {
                $temp = $_POST['ratetables'] . ',';
                if($_POST['report_type'] == '0') {
                    $bool = 'n_false';
                } else {
                    $bool = 'n_true';
                }
            }
            
            $cmd = "rate_analysis {$bool}|{$temp}{$ratetablestr}";
            
            $fileinfo = $this->_socket($cmd);
            if (empty($fileinfo)) {
                $this->set('p', '');
            } else {
                setcookie('rate_analysis', trim($fileinfo));
                if($_POST['show_type'] == '1') {
                    $this->down();
                }
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $this->_ready($filename, $filelines, $maxfields);
            }
        } else {
            if(isset($_GET['page']) && isset($_COOKIE['rate_analysis'])) {
                $fileinfo = $_COOKIE['ratefinder'];
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $this->_ready($filename, $filelines, $maxfields);
            }
        }
        $ratetables = $this->Rate->ready_rate();
        $trunks = $this->Rate->ready_resource();
        $this->set('ratetables', $ratetables);
        $this->set('trunks', $trunks);
    }
    
    public function _ready($filename,$filelines,$maxfields) {
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        //$startline = ($currPage - 1) * $pageSize;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords ( $filelines ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
	$pageSize = $page->getPageSize();
        $result = $this->_line_content($filename, $currPage, $pageSize, $filelines);
        $page->setDataArray ( $result );
        $this->set('p',$page);
        $this->set('maxfields',$maxfields);
    }
    
    public function _line_content($filename, $startline, $endline, $filelines) {
        $arr = array();
        $fp = fopen($filename, "r");
        for ($i = 0; $i <= $startline - 1; $i++) {
            fgets($fp);
        }
        //for ($i = $startline; $i <= $endline; $i++) {
        for ($i = 1; $i <= $endline; $i++) {
            array_push($arr, fgetcsv($fp));
            if($filelines == $i) break;
        }
        return $arr;
    }
    
    public function _socket($cmd) {
        $content = "";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(socket_connect($socket,Configure::read("backend.ip"),Configure::read("backend.port"))) {
             @socket_write($socket, $cmd, strlen($cmd));
        }else{
            $this->Rate->create_json_array('', 101, 'host lookup failed');
            $this->Session->write('m', Rate::set_validator());
        }
        while ($out = @socket_read($socket, 2048)) {
           $content .= $out;
           if(strpos($out, "~!@#$%^&*()") !== FALSE) {
               break;
           }
           unset($out);
        }
        socket_close($socket);
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        return $content;
    }
    
    public function down() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        header("Content-Type: text/csv");   
        header("Content-Disposition: attachment; filename=rate_analysis.csv");   
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
        header('Expires:0');   
        header('Pragma:public');   
        $fileinfo = $_COOKIE['rate_analysis'];
        $fileinfo_arr = explode(",", $fileinfo);
        $filename = $fileinfo_arr[0];
        $filelines = $fileinfo_arr[1];
        $maxfields = $fileinfo_arr[2];
        echo 'Code,Min,Max,Avg,';
        for($i=1;$i<=$maxfields;$i++){
            echo 'Trunk-' . $i .',';
        }
        echo "\n";
        echo file_get_contents($filename);
    }
    
    public function ready_ratetables($resource_id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT rate_table_id || ':' || name as id,name FROM rate_table WHERE rate_table_id IN (SELECT rate_table_id FROM resource_prefix WHERE resource_id = {$resource_id})";
        $result = $this->Rate->query($sql);
        echo json_encode($result);
    }
}
?>