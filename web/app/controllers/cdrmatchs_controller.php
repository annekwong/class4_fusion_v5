<?php

class CdrmatchsController extends AppController {
    var $name = 'Cdrmatchs';
    var $uses = array('Cdrmatch');
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
        $carriers = $this->Cdrmatch->getcarriers();
        $this->set('carriers', $carriers);
    }
    
    /*
    public function show() {
        // 验证文件提交
        if(is_uploaded_file($_FILES['cdrfile']['tmp_name'])) {
            if($_FILES['cdrfile']['type'] != "text/csv") {
                $this->Cdrmatch->create_json_array('#cdrmatchsearch',101,__('Document type error!',True));
                $this->Session->write("m",Cdrmatch::set_validator());
                $this->redirect (array ('action' => 'index' ));
            } else {
                $upload_dir = '/tmp/exports/';
                $name = "cdr_match_" . substr(md5(microtime()), 0, 5) . '.csv';
                $destname = $upload_dir . $name;
                $result = move_uploaded_file($_FILES['cdrfile']['tmp_name'], $destname);
                if($result == 1) {
                    $cmd = "cdr-audit {$_POST['carrier']},{$_POST['starttime']} {$_POST['gmt']},{$_POST['endtime']} {$_POST['gmt']},{$_POST['type']},{$destname}";
                    $socketstr = $this->_socket($cmd);
                    $info = explode(",", $socketstr);
                    if($info[0] == "0") { 
                        $this->set("filename", $info[1]);
                        $this->set("cmd", $cmd);
                        $this->set("flag", TRUE);
                    } else {
                        $this->set("flag", FALSE);
                    }
                }
            }
        }
    }
    */
    
    public function show() {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $starttime= $_POST['starttime'] ; $endtime = $_POST['endtime']; $gmt = $_POST['gmt']; $carrier = $_POST['carrier']; $type = $_POST['type'];
        $israte = 'FALSE';
        if(isset($_POST['is_compare_rate'])) {
            $israte = "TRUE";
        }
        
        $filename = uniqid('cdr_recon') . '.csv';
        
        $uni_cdr_file = Configure::read('database_actual_export_path') . $filename;
        if($type == 'vendor_cdr') {
            $this->Cdrmatch->compare_vendor($starttime, $endtime, $gmt, $carrier, $uni_cdr_file); 
        } else {
            $this->Cdrmatch->compare_client($starttime, $endtime, $gmt, $carrier, $uni_cdr_file);
        }     
        
        $web_db_file = Configure::read('database_export_path') . DS . $filename;
        
        if(is_uploaded_file($_FILES['cdrfile']['tmp_name'])) {
            $upload_dir = APP .'upload/cdr_reconciliation/';
            $name = "cdr_match_" . substr(md5(microtime()), 0, 5) . '.csv';
            $destname = $upload_dir . $name;
            $result = move_uploaded_file($_FILES['cdrfile']['tmp_name'], $destname);
            $format = $_POST['format'];
            if($result == 1) {
                $current_time = date("Y-m-d H:i:s");
                $diff_result_file = APP .'upload/cdr_reconciliation/' . uniqid('diff_result_') . '.csv';
                if ($format === "0") {
//                    $sql = <<<EOT
//                    INSERT INTO cdr_compare(source_filename, diff_filename, create_time, format, diff_cdr_file)
//                    VALUES ('{$destname}', '{$web_db_file}',  '{$current_time}', {$format}, '{$diff_result_file}');
//EOT;
                }  elseif($format === "1") {
//                    $sql = <<<EOT
//                    INSERT INTO cdr_compare(source_filename, diff_filename, create_time, format, diff_report_file)
//                    VALUES ('{$destname}', '{$web_db_file}', '{$current_time}', {$format}, '{$diff_result_file}');
//EOT;
                }
//                $this->Cdrmatch->query($sql);
                $this->Cdrmatch->create_json_array('', 201, __('Succeed!',true));
                $this->Session->write("m",Cdrmatch::set_validator ());
                $this->redirect ( "/cdrmatchs/showlist/" );
            }
        }
    }
    
    public function showlist() {
        //$status = array('-1'=> 'failed','unexecutive', 'executive', 'complete');
        $status = array(
            '-1' => 'open CDR file failed',
            '-2' => 'file format error',
            '-3' => 'unkown error',
            'unexecutive', 'executive', 'complete'
        );
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $counts = $this->Cdrmatch->show_list_count();
        $page = new MyPage ();
        $page->setTotalRecords ($counts); 
        $page->setCurrPage ($currPage);
        $page->setPageSize ($pageSize); 
        $currPage = $page->getCurrPage()-1;
        $pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
        $data = $this->Cdrmatch->show_list($pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p',$page);
        $this->set('status', $status);
    }
    
    public function download() {
        $file = $_GET['file'];
        Configure::write('debug','0');
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $basename = basename($file);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$basename}");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($file);
    }
    
    
    public function _socket($cmd) {
       error_reporting(E_ALL);
       $content = "";
       $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
       if($socket === false) {
           socket_close($socket);
           echo "socket_create failed: reason: " . socket_strerror(socket_last_error()) . "\n";
       }
       $result = socket_connect($socket, Configure::read("CdrMatch.ip"), intval(Configure::read("CdrMatch.port")));
       if ($result === false) {
           socket_close($socket);
           echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
       }
       socket_write($socket, $cmd, strlen($cmd));
       while ($out = socket_read($socket, 2048)) {
           $content .= $out;
           if(strpos($out, "~!@#$%^&*()") !== FALSE) {
               break;
           }
           unset($out);
       }
       socket_close($socket);
       $content = strstr($content, ",~!@#$%^&*()", TRUE);
       return $content;
    }
    
    public function export() {
        Configure::write('debug',0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $filename = $_POST['filename'];
        $cmd_arr = $this->_readycmd($_POST['cmd']);
        switch($_POST['type']) {
            case 'pdf':
                $this->_generatePDF($filename, $cmd_arr);  
                break;
            case 'xls':
                $this->_generateXLS($filename, $cmd_arr);
                break;
            case 'html':
                $this->_generateHTML2($filename, $cmd_arr);
                break;
        }
    }
    
    public function _readycmd($cmd) {
        $cmd = str_replace("cdr-audit ", "", $cmd);
        $cmd_arr = explode(",", $cmd);
        $result = $this->Cdrmatch->getcarrier(intval($cmd_arr[0]));
        $cmd_arr[0] = $result[0][0]['name'];
        if($cmd_arr[3] == 'client_cdr') {
            $cmd_arr[3] = 'Client CDR';
        } else {
            $cmd_arr[3] = 'Vendor CDR';
        }
        return $cmd_arr;
    }
    
    public function _generateXLS($filename, $cmd_arr) {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=cdr-compare.xls");
        echo $this->_generateHTML($filename, $cmd_arr);  
    }
    
    public function _generateHTML2($filename, $cmd_arr) {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=cdr-compare.html");
        echo $this->_generateHTML($filename, $cmd_arr);  
    }
    
    public function _generatePDF($filename, $cmd_arr) {
        App::import("Vendor","tcpdf",array('file'=>"tcpdf/pdf.php"));
        $pdf = create_PDF("cdr-compare", $this->_generateHTML($filename, $cmd_arr));
        return $pdf;
    }
    
    public function _generateHTML($filename, $cmd_arr) {
        $type = "";
        if($_POST['type'] == 'client_cdr'): $type = 'Client'; else : $type = 'Vendor'; endif; 
        $simplexml = simplexml_load_file($filename);
        $content = '';
        $content .= <<<EOD
        <table border="1" style="font-size:18px">
            <tr>
                <td>Carrier</td>
                <td>{$cmd_arr[0]}</td>
            </tr>
            <tr>
                <td>Start Time</td>
                <td>{$cmd_arr[1]}</td>
            </tr>
            <tr>
                <td>End Time</td>
                <td>{$cmd_arr[2]}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>{$cmd_arr[3]}</td>
            </tr>     
        </table>
        <br />
        <table border="1" style="font-size:18px;">
        <thead>
            <tr>
                <td rowspan="2">Code Name</td>
                <td colspan="4">System CDR</td>
                <td colspan="4">$type CDR</td>
                <td colspan="4">Diff</td>
            </tr>
            <tr>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
            </tr>
        </thead>
        <tbody>
EOD;
        foreach($simplexml->p as $p) {
            $content .= '<tr>';
            foreach($p->attributes() as $val) {
                $content .= "<td>" . $val . "</td>";
            }
            $content .= '</tr>';
        }
        $content .= '</tbody></table>';
        return $content;
    }
    
    function changecarrer($type) {
        Configure::write('debug',0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $result = $this->Cdrmatch->getcarriertype($type);
        $arr = array();
        foreach($result as $val) {
            $arr[] = $val[0];            
        }
        echo json_encode($arr);
    }
    
    function example_file() {
        Configure::write('debug',0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        header("Content-type:text/csv");
        header("Content-Disposition:filename=example.csv");
        $output = <<<EOD
duration,cost,rate,dnis,time,code_name
21,3.500000,10.000000,6666,2011-08-29 03:02:43+00,China
EOD;
        echo $output;
    }
    
    
}

?>
