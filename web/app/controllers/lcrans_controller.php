<?php

class LcransController extends AppController {
    var $name = 'Lcrans';
    var $uses = array('clients');
    var $components = array('RequestHandler');
    var $helpers = array('javascript','html','AppClients');
    
    public function beforeFilter(){
            $this->checkSession ( "login_type" );//核查用户身份
            $login_type = $this->Session->read('login_type');
            if($login_type==1){
                    $this->Session->write('executable',true);
                    $this->Session->write('writable',true);
            }else{
                    $limit = $this->Session->read('sst_tools_sipcapture');
                    $this->Session->write('executable',$limit['executable']);
                    $this->Session->write('writable',$limit['writable']);
            }
            parent::beforeFilter();
    }
    
    public function index() {
        if($this->RequestHandler->isPost() && !empty($_POST['trunks']) && isset($_POST['trunks'])) {
            $trunkstr = implode(",",$_POST['trunks']);
            $cmd = "lcr_analysis {$_POST['type']}||||||$trunkstr";
            $fileinfo = $this->_socket($cmd);
            if($_POST['show_type'] == 1){
                $this->_download($fileinfo);
                return;
            }
            if (empty($fileinfo)) {
                $this->set('p', '');
            } else {
                setcookie('lcran', $fileinfo);
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $this->_ready($filename, $filelines, $maxfields);
            }
            
        } else {
            if(isset($_GET['page']) && isset($_COOKIE['lcran'])) {
                $fileinfo = $_COOKIE['lcran'];
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $this->_ready($filename, $filelines, $maxfields);
            }
        }
        $trunks = $this->clients->get_egress_trunk();
        $this->set('trunks', $trunks);
    }
    
    public function _download($fileinfo) {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $fileinfo_arr = explode(",", $fileinfo);
        $filename = $fileinfo_arr[0];
        header("Content-type:text/csv");
        header("Content-Disposition:filename=lcr_analysis.csv");
        $filelines = $fileinfo_arr[1];
        $maxfields = $fileinfo_arr[2];
        echo 'Code,Min,Max,Avg,';
        for($i=1;$i<=$maxfields;$i++){
            echo 'Trunk-' . $i .',';
        }
        echo "\n";
        $data = file_get_contents($filename);
        echo $data;
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
        $result = $this->_line_content($filename, $currPage, $pageSize);
        $page->setDataArray ( $result );
        $this->set('p',$page);
        $this->set('maxfields',$maxfields);
    }
    
    public function _socket($cmd) {
        $content = "";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
        }else{
            socket_close($socket);
            return '';
        }
        while ($out = socket_read($socket, 2048)) {
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
    
    public function _line_content($filename, $startline, $endline) {
        $arr = array();
        $fp = fopen($filename, "r");
        for ($i = 0; $i <= $startline - 1; $i++) {
            fgets($fp);
        }
        //for ($i = $startline; $i <= $endline; $i++) {
        for ($i = 1; $i <= $endline; $i++) {
            array_push($arr, fgetcsv($fp));
        }
        return $arr;
    }
   
    public function add_trunk($code, $trunk_name) {
	$this->autoLayout = false;
    }

    public function add_trunk_post() {
	Configure::write('debug', 0);
	$this->autoRender = false;
	$this->autoLayout = false;
        $trunk_result = $this->clients->query("select resource_id from resource where alias = '{$_POST['trunk']}'");
	$trunk_id = $trunk_result[0][0]['resource_id'];
	if($_POST['routetype'] == 'static') {
	    $item_result = $this->clients->query("insert into product_items(product_id, digits) values ({$_POST['name']}, '{$_POST['code']}') RETURNING item_id");
	    $item_id = $item_result[0][0]['item_id'];
	    $this->clients->query("insert into product_items_resource(item_id, resource_id) values ({$item_id}, {$trunk_id})");
	} else {
	    $this->clients->query("insert into dynamic_route_items(dynamic_route_id, resource_id) values ({$_POST['name']}, {$trunk_id})");
	}
	echo '1';
    }

    public function getstatic() {
	Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $results = $this->clients->query("select product_id as id, name from product order by name asc");
	echo json_encode($results);
    }
   
    public function getdynamic() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $results = $this->clients->query("select dynamic_route_id as id, name from dynamic_route order by name asc");
        echo json_encode($results);
    }
	
    
}

?>
