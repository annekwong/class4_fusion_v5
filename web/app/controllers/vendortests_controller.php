<?php

class VendortestsController extends AppController {

    var $name = "Vendortests";
    var $uses = array('Vendortest');
    var $helpers = array ('Javascript', 'Html','Searchfile');

    public function index() {
        $data = $this->_readydata();
        $this->set('data', $data);
    }

    public function _readydata() {

        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $counts = $this->Vendortest->project_counts();
        $page = new MyPage ();
        $page->setTotalRecords ( $counts ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
        $pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
        $sql = "select 
vendortest_project.id, vendortest_project.project_name, vendortest_project.status,
(array_to_string(ARRAY(select code_name from vendortest_code where vendortest_project_id = vendortest_project.id), ',')) as code_name,
resource.alias,

TIMESTAMP WITH TIME ZONE 'epoch' + vendortest_project_summary.start_epoch * INTERVAL '1 second' AS start_epoch,
TIMESTAMP WITH TIME ZONE 'epoch' + vendortest_project_summary.end_epoch * INTERVAL '1 second' AS end_epoch

from vendortest_project 

LEFT JOIN vendortest_project_summary ON vendortest_project.id = vendortest_project_summary.vendortest_project_id

LEFT JOIN resource ON vendortest_project.trunk = resource.resource_id    
where order_type is null ORDER BY vendortest_project.id DESC limit '$pageSize' offset '$offset'";
        $projects = $this->Vendortest->query($sql);

        $page->setDataArray ( $projects );
        $this->set('p',$page);
        $this->set('pageSize', $pageSize);
        $this->set('offset', $offset);
    }

    public function fresh_data($pageSize, $offset) {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "select 
vendortest_project.id, vendortest_project.project_name, vendortest_project.status,
(array_to_string(ARRAY(select code_name from vendortest_code where vendortest_project_id = vendortest_project.id), ',')) as code_name,
resource.alias,

TIMESTAMP WITH TIME ZONE 'epoch' + vendortest_project_summary.start_epoch * INTERVAL '1 second' AS start_epoch,
TIMESTAMP WITH TIME ZONE 'epoch' + vendortest_project_summary.end_epoch * INTERVAL '1 second' AS end_epoch

from vendortest_project 

LEFT JOIN vendortest_project_summary ON vendortest_project.id = vendortest_project_summary.vendortest_project_id

LEFT JOIN resource ON vendortest_project.trunk = resource.resource_id    
where order_type is null ORDER BY vendortest_project.id DESC limit '$pageSize' offset '$offset'";
        $projects = $this->Vendortest->query($sql);
        echo json_encode($projects);
    }

    public function _getstatus($project_name) {
        $content = "";
        $state_code = "";
        $cmd = "egress_trunk_simulate ABC:CHECK:Project-{$project_name}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ));
        socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>30, "usec"=>0 ));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048)) {
                $content .= $out;
                if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
            $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<root>\n" . $content . "</root>";
            $content = $this->strip_invalid_xml_chars2($content);
            $xml = simplexml_load_string($content);
            $state_code = $xml->Check_State->State_Count;
        }
        socket_close($socket);
        $state = "";
        switch ($state_code) {
            case '0':
                $state = 'New';
                break;
            case '1':
                $state = 'Process';
                break;
            case "2":
                $state = 'Tested';
                break;
        }
        return $state;
    }

    function strip_invalid_xml_chars2($in) {
        $out = "";
        $length = strlen($in);
        for($i=0;$i<$length;$i++) {
            $current = ord($in{$i});
            if(($current == 0x9) || ($current == 0xA)
                || ($current == 0xD)
                || (($current >= 0x20)
                    && ($current <= 0xD7FF))
                || (($current >= 0xE000)
                    && ($current <= 0xFFFD))
                || (($current >= 0x10000)
                    && ($current <= 0x10FFFF))) {
                $out .= chr($current);
            } else {
                $out .= " ";
            }
        }
        return $out;
    }

    public function addproject() {
        if($this->RequestHandler->isPost()) {
            /*
            $project_name = $_POST['project_name'];
            $trunk = $_POST['egress_trunk'];
            $count = $this->Vendortest->check_project($_COOKIE['vendor_key'], $project_name);
            if($count == 0) {
                $id = $this->Vendortest->addproject($_COOKIE['vendor_key'], $project_name, $trunk);
                $this->redirect('/vendortests/addproject2/'.base64_encode($id));
            } else {
                $this->Vendortest->create_json_array('#ClientOrigRateTableId',101,__('Project has been existed!',true));
		$this->Session->write("m",Vendortest::set_validator ());
            }
            */
            $project = $_POST['project_name'];
            $trunk = $_POST['trunk'];
            $cal_number = $_POST['call_number'];
            $cal_time = $_POST['call_time'];
            $codec = $_POST['codec'];
            $project_id = $this->Vendortest->addproject($project, $trunk);
            //$code_id = $this->Vendortest->add_code($project_id, $code_name);
            $numbers = explode("\n", $_POST['numbers']);
            $code_name_ids = array();
            foreach ($numbers as $number) {
                if(!empty ($number)) {
                    $code_name_temp = $this->Vendortest->get_code_name_item($number);
                    if(in_array($code_name_temp, $code_name_ids)) {
                        $key = array_search($code_name_temp, $code_name_ids);
                        $code_id = $code_name_ids[$key];
                    } else {
                        $code_id = $this->Vendortest->add_code($project_id, $code_name_temp);
                        $code_name_ids[$code_id] = $code_name_temp;
                    }
                    $this->Vendortest->add_number($code_id, $number,$cal_number, $cal_time);
                    $this->_send_init($project_id, $number, $cal_number, $cal_time, $code_name_temp, $trunk, $codec);
                }
            }
            $this->start($project_id);
            $this->redirect('/vendortests');
        }
        $trunks = $this->Vendortest->get_trunk();
        $rate_table = $this->Vendortest->get_rate_table();
        $this->set('trunks', $trunks);
        $this->set('ratetables', $rate_table);
    }



    public function _send_init($project_id, $number, $cal_number, $cal_time, $code_name, $trunk, $codec) {
        $number = trim($number, "\r\n ");
        $ip_port = $this->Vendortest->get_trunk_ip($trunk);
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ));
        socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>30, "usec"=>0 ));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            $cmd = "egress_trunk_simulate ABC:INIT-{$ip_port['ip']}|{$ip_port['port']}|{$cal_number}|{$codec}|true|false|{$cal_time}|*|{$number}|{$code_name}|{$trunk}|{$project_id}";
            socket_write($socket, $cmd, strlen($cmd));
            echo $cmd . "\n";
        }
        while ($out = socket_read($socket, 2048)) {
            if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        socket_close($socket);
    }


    public function get_codename($rate_table_id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $result = $this->Vendortest->get_codename($rate_table_id);
        echo json_encode($result);
    }

    public function get_code() {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $result = $this->Vendortest->get_code($_POST['rate_table_id'], $_POST['code_name']);
        echo json_encode($result);
    }

    public function clientcdr($rate_table_id, $code_name) {
        /*
        $this->autoLayout = FALSE;
        $codes = $this->Vendortest->get_code($rate_table_id, $code_name);
        empty($_GET['page'])?$currpage = 1:$currpage = $_GET['page'];
        empty($_GET['size'])?$pageSize = 100:$pageSize = $_GET['size'];    
        require_once 'MyPage.php';
        $page = new MyPage();	
        $count = $this->Vendortest->get_clientcdr_counts($codes);
        $page->setTotalRecords($count);//总记录数
        $page->setCurrPage($currpage);//当前页
        $page->setPageSize($pageSize);//页大小
        $currPage = $page->getCurrPage()-1;
        $pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
        $clientcdrs = $this->Vendortest->get_clientcdr($codes,$pageSize,$offset)                               ;
        $page->setDataArray($clientcdrs);
        $this->set('p', $page);
        */
        $this->autoLayout = FALSE;
        $codes = $this->Vendortest->get_code($rate_table_id, $code_name);
        $clientcdrs = $this->Vendortest->get_clientcdr($codes);
        $this->set('clientcdrs', $clientcdrs);
    }


    public function generatekey() {
        $id =  uniqid('DNL');
        $this->Vendortest->generatekey($id);
        setcookie('vendor_key', $id, time()+60*60*24*30);
        $this->set('key', $id);
    }

    public function start($id) {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        /*
        $result = $this->Vendortest->start_code_cmd($id);
        foreach($result as $item) {
            $cmd = "egress_trunk_simulate ABC:RUN:CodeName-{$item[0]['project_name']}|{$item[0]['trunk']}|{$item[0]['code_name']}";
            $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
            if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
            }
            while ($out = socket_read($socket, 2048)) {
               if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                   break;
               }
               unset($out);
            }
            socket_close($socket);
        }
        $this->Vendortest->start_project($id);
        $this->redirect('/vendortests');
        */
        //$project_name = $this->Vendortest->get_project_name($id);
        $this->start_sip_capture($id);
        $cmd = "egress_trunk_simulate ABC:RUN:Project-{$id}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ));
        socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>30, "usec"=>0 ));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048)) {
                if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                    break;
                }
                unset($out);
            }
        }

        socket_close($socket);
        $this->Vendortest->start_project($id);
        //$this->redirect('/vendortests');
    }

    public function start_sip_capture($id) {
        $this->autoLayout = false;
        $this->autoRender = false;
        //$server_ip = "192.168.1.115";
        //$server_port = 8500;
        $server_ip = Configure::read('sip_capture.host_ip');
        $server_port = Configure::read('sip_capture.port');
        $trunk_id = $this->Vendortest->get_trunk_id($id);
        $ip_port = $this->Vendortest->get_trunk_ip($trunk_id);
        $system_ip_port = $this->Vendortest->get_system_ip_port();
        $numbers = $this->Vendortest->get_test_numbers($id);
        foreach($numbers as $number) {
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ));
            socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>30, "usec"=>0 ));
            $buf = "nonce\r\n";
            socket_sendto($socket, $buf, strlen($buf), 0, $server_ip, $server_port);
            $msg = "";
            socket_recvfrom($socket, $msg, 256, 0, $server_ip, $server_port);
            list($ack, $nonce) = explode("\r\n", $msg);
            $nciphertest = md5($nonce."denovo"."54wOlspwW892");
            $cmd = "ngrep -d any -S 2000 -W byline  -wi '{$number[0]['source_number']}|{$number[0]['test_number']}' host {$system_ip_port['ip']} and {$ip_port['ip']} and port {$system_ip_port['port']}  and {$ip_port['port']}";
            $buf = "start\r\n{$number[0]['call_time']}\r\n{$cmd}\r\n{$nciphertest}\r\n$nonce\r\n";
            socket_sendto($socket, $buf, strlen($buf), 0, $server_ip, $server_port);
            socket_recvfrom($socket, $msg, 256, 0, $server_ip, $server_port);
            socket_close($socket);
            list($ack, $flag, $filepath) = explode("\r\n", $msg);
            $this->Vendortest->insert_pcap_path($number[0]['id'], $filepath);
        }
    }

    public function delete($id) {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        /*
        $project_name = $this->Vendortest->get_project_name($id);
        $cmd = "egress_trunk_simulate ABC:CLEAR:Project-$project_name";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
           if(strpos($out, "~!@#$%^&*()") !== FALSE) {
               break;
           }
           unset($out);
        }
        socket_close($socket);
        */
        $project_name = $this->Vendortest->get_project_name($id);
        $this->Vendortest->delete_project($id);
        $this->Session->write('m',
            $this->Vendortest->create_json(201, __('The Project [%s] is deleted successfully!', true, $project_name)));
        $this->redirect('/vendortests');
    }

    public function delete_code($bid,$id) {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        /*
        $result = $this->Vendortest->delete_code_cmd($id);
        $cmd = "egress_trunk_simulate ABC:CLEAR:CodeName-{$result[0][0]['project_name']}|{$result[0][0]['trunk']}|{$result[0][0]['code_name']}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
           if(strpos($out, "~!@#$%^&*()") !== FALSE) {
               break;
           }
           unset($out);
        }
        socket_close($socket);
        */
        $this->Vendortest->delete_code($id);
        $this->redirect("/vendortests/summary/{$bid}");
    }
    /*
    public function summary($id) {
        $content = "";
        $result = $this->Vendortest->get_code_summary($id);
        foreach($result as $item) {
            $temp = "";
            $cmd = "egress_trunk_simulate ABC:GET:CodeName-{$id}|{$item[0]['trunk']}|{$item[0]['id']}";
            $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
            if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
            }
            while ($out = socket_read($socket, 2048)) {
               $temp .= $out;
               if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                   break;
               }
               unset($out);
            }
            socket_close($socket);
            $content .= strstr($temp, "~!@#$%^&*()", TRUE);
            unset($temp);
        }
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<root>\n" . $content . "</root>";        
        $this->set('content', $content);
        $this->set('data', $result);
    }   
    */

    public function summary($id) {
        $results = $this->Vendortest->get_summary($id);
        $this->set('data', $results);
    }

    public function detail($id) {
        $result = $this->Vendortest->get_detail($id);
        $this->set('data', $result);
    }



    public function report($id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $content = "";
        $result = $this->Vendortest->get_detail($id);
        $flag = 0;
        foreach($result as $item) {
            $temp = "";
            $cmd = "egress_trunk_simulate ABC:GET:Number-{$item[0]['project_name']}|{$item[0]['trunk']}|{$item[0]['code_name']}|{$item[0]['source_number']}|{$item[0]['test_number']}";
            $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
            socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ));
            socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>30, "usec"=>0 ));
            if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
                while ($out = socket_read($socket, 2048)) {
                    $temp .= $out;
                    if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                        break;
                    }
                    unset($out);
                }
            }
            socket_close($socket);
            $content .= strstr($temp, "~!@#$%^&*()", TRUE);
            unset($temp);
        }
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<root>\n" . $content . "</root>";
        $content = $this->_filter0x0($content);
        $xml = simplexml_load_string($content);
        $innerdata = "";
        foreach($xml->Summary as $summary) {
            $start_top = intval($summary->Start_TOD) == 0 ? 0 : date('Y-m-d H:i:s',intval($summary->Start_TOD));
            $innerdata .= "<tr>";
            $innerdata .="<td>{$start_top}</td>
                <td>{$result[$flag][0]['source_number']}</td>
                <td>{$result[$flag][0]['test_number']}</td>
                <td>{$summary->PDD}</td>
                <td>{$summary->Duration}</td>
                <td>{$summary->Duration}</td>
                <td>{$summary->Release_Cause}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>";
            $innerdata .= "</tr>";
            $flag++;
        }
        $str = <<<EOD
 <table border="1" align="center">
        <thead>
            <tr>
                <td>Initiated</td>
                <td>SRC Number</td>
                <td>DEST Number</td>
                <td>PDD</td>
                <td>CD</td>
                <td>DUR</td>
                <td>Diconnect Code / Reason</td>
                <td>Media Packets</td>
                <td>RBT</td>
                <td>Audio</td>
            </tr>
        </thead>
        <tbody>
            {$innerdata}
        </tbody>
    </table>
EOD;
        App::import("Vendor","tcpdf",array('file'=>"tcpdf/pdf.php"));
        $pdf = create_PDF("vendortest", $str);
    }

    public function _filter0x0($in) {
        $out = "";
        $length = strlen($in);
        for($i=0;$i<$length;$i++) {
            $current = ord($in{$i});
            if(($current == 0x9) || ($current == 0xA)
                || ($current == 0xD)
                || (($current >= 0x20)
                    && ($current <= 0xD7FF))
                || (($current >= 0xE000)
                    && ($current <= 0xFFFD))
                || (($current >= 0x10000)
                    && ($current <= 0x10FFFF))) {
                $out .= chr($current);
            } else {
                $out .= " ";
            }
        }
        return $out;
    }


    public function get_cdrnum($code_name) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        //$time = date('Y-m-d H:i:s', strtotime("-1 day"));
        $codes = $this->Vendortest->find_all_code($code_name);
        $cdr_num = $this->Vendortest->get_cdrnum($codes);
        echo json_encode($cdr_num);
    }


    public function activecode($id, $type) {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $this->Vendortest->activecode($id, $type);
        $this->redirect('/vendortests');
    }

    public function downaudio($file) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $file = base64_decode($file);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=audio.$ext");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile("{$file}");
    }

    public function viewcap($file) { 
        $file = base64_decode($file);
        $source_file = $file;
        $cmd = "tcpdump -v -r {$source_file}";
        $data = shell_exec($cmd);
        $this->set('drawfile', $data);
    }



}

?>
