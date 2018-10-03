<?php

class RatemailsController extends AppController {
    
    var $name = "Ratemails";    // 控制器名称
    var $helpers = array('Javascript','Html', 'Text');  // 帮助库
    var $components = array('RequestHandler');  // 组建-请求
    var $uses = array('Ratemail','Ratemailhistory'); // 使用Model
    
    public function index() {
        $templates = $this->Ratemail->findAll();
        $this->set('templates', $templates);
    }
    
    public function add() {
        if($this->RequestHandler->isPost()) {
            $this->Ratemail->save($_POST);
            $this->Ratemail->create_json_array('#myform',201,__('The Rate Amendment Template [%s] is created Successfully!',true, $_POST['name']));
	    $this->Session->write("m",Ratemail::set_validator());
            $this->redirect ( "/ratemails" );
        }
    }
    
    public function edit($id) {
        if($this->RequestHandler->isPost()) {
            $_POST['id'] = $id;
            $this->Ratemail->save($_POST);
            $this->Ratemail->create_json_array('#myform',201,__('The Rate Amendment Template [%s] is modified Successfully!',true, $_POST['name']));
	    $this->Session->write("m",Ratemail::set_validator());
            $this->redirect ( "/ratemails" );
        }
        $template = $this->Ratemail->findById($id);
        $this->set('template', $template);
    }
    
    public function delete($id) {
        $ratemail = $this->Ratemail->findById($id);
        $this->Ratemail->del($id);
        $this->Ratemail->create_json_array('#myform',201,__('The Rate Amendment Template [%s] is deleted Successfully!',true, $ratemail['Ratemail']['name']));
        $this->Session->write("m",Ratemail::set_validator());
        $this->redirect ( "/ratemails" );
    }
    
    public function getcontent($id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $content = $this->Ratemail->getcontent($id);
        echo $content;
    }
    
    
    public function sendrate($client_id,$part_type=null) {
        
        if($part_type == null){
                if($this->RequestHandler->isPost()) {
                     $this->handleMail($_POST, $client_id);
                }
                $ingresss = $this->Ratemail->get_ingress();
                $templates = $this->Ratemail->find('all', array(
                'fields' => 'id, name'  
                ));

                $tech_prefix = $this->Ratemail->query("select * from resource_prefix left join rate_table on resource_prefix.rate_table_id = rate_table.rate_table_id
        where resource_id in (select resource_id from resource where client_id = {$client_id})
        ");
               
                $this->set('tech_prefixs',$tech_prefix);
                //$this->set('code_names',$code_name);
                $this->set('ingresss', $ingresss);
                $this->set('templates', $templates);
        }else if($part_type == 1){
            $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
            empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
            empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
            $_SESSION['paging_row'] = $pageSize;
            require_once 'MyPage.php';
            $counts = $this->Ratemailhistory->find('count',array('conditions'=>array('client_id'=>$client_id)));
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
                'page' => $currPage,
                'conditions'=>array('client_id'=>$client_id)
                
            ));

            $page->setDataArray ( $results );
            $this->set('p',$page);
        }else if($part_type == 2){
            $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
            empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
            empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
            $_SESSION['paging_row'] = $pageSize;
            require_once 'MyPage.php';
           
            $page = new MyPage ();
             
            $page->setCurrPage ( $currPage ); //当前页
            $page->setPageSize ( $pageSize ); //页大小
            $currPage = $page->getCurrPage()-1;
            $pageSize = $page->getPageSize();
            $offset=$currPage*$pageSize;
            
            if (!empty($_REQUEST['search']))			//模糊查询
            {
                    $search_type = 0;
                    $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
                    $templates = $this->Ratemail->find('all',array('limit'=>$pageSize,'page'=>$currPage,'conditions'=>array('name LIKE'=>"%{$search_arr['search']}%")));
                    $counts = $this->Ratemail->find('count',array('conditions'=>array('name LIKE'=>"%{$search_arr['search']}%")));
            }else{
                    $templates = $this->Ratemail->find('all',array('limit'=>$pageSize,'page'=>$currPage));
                     $counts = $this->Ratemail->find('count');
            }
			
            $page->setTotalRecords ( $counts );//总记录数
            $this->set('templates', $templates);
            $this->set('p',$page);
        }
        
        $this->set('part_type',$part_type);
        
        
        
    }
    
    public function get_tech($id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $results = $this->Ratemail->get_tech($id);
        echo json_encode($results);
    }
    
    public function get_table($id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $results = $this->Ratemail->get_table($id);
        echo json_encode($results);
    }
    
    public function handleMail($data,$client_id) {
        $this->loadModel('Ratemailhistory');
        $send_address = $this->Ratemail->get_carrier_mail($client_id);
        $email_info = $this->Ratemail->query('SELECT fromemail as "from", smtphost,loginemail, smtpport,emailusername as username, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        $template = $this->Ratemail->findById($data['template']);
        $content = $template['Ratemail']['content'];
        App::import('Vendor', 'nmail/phpmailer');
        App::import('Vendor', 'nmail/smtp');
        $mailer = new phpmailer();
        //$mailer->SMTPDebug=2;
        $mailer->IsSMTP();
	$mailer->SMTPAuth 	= $email_info[0][0]['loginemail']; 
        $mailer->IsHTML(true);
        switch ($email_info[0][0]['smtp_secure']) {
            case 1:
                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];

        }
	$mailer->From 		= $template['Ratemail']['from_address'];
        $mailer->FromName	= $template['Ratemail']['from_name'];
        $mailer->Host		= $email_info[0][0]['smtphost'];
        $mailer->Port		= intval($email_info[0][0]['smtpport']);		
        $mailer->Username	= $template['Ratemail']['from_address'];
        $mailer->Password	= $template['Ratemail']['mail_password'];
        $send_address_arr = explode(',', $send_address);
        foreach($send_address_arr as $send_address_item) {
            $mailer->AddAddress($send_address_item);
        }
        $mailer->Subject = $template['Ratemail']['subject'];
        $mailer->ClearAttachments();
        
        $conf_path = Configure::read('ratesend.rate_config');
        $bin_path = Configure::read('ratesend.rate_update');
        $num = count($data['rate_table']);
        //var_dump($send_address);
        $teach_prefix = array();
        
        $xls_files = array();
        
        
        
        
        
        //创建一个临时文件
        $filename=APP.'upload/temporary/codeName.txt';
        
        $fp=fopen($filename, "w+"); //打开文件指针，创建文件
        if(!empty($data['rate'])){
            foreach($data['rate'] as $rate){
                fwrite($fp, $rate."\n");
            }
        }
        
        fclose($fp); 

        for($i=0;$i < $num;$i++) {
            
            $rate_tables = explode(',',$data['rate_table'][$i]);
            //var_dump($rate_tables);
            array_push($teach_prefix, $rate_tables[1]);
            if(!empty($data['rate'])){
                $cmd = "{$bin_path} -r {$rate_tables[0]} -f {$conf_path} -c {$data['type']} -i {$filename}";
            }else{
                $cmd = "{$bin_path} -r {$rate_tables[0]} -f {$conf_path} -c {$data['type']}";
            }
            
            //var_dump($cmd);
            $out = shell_exec($cmd);
            
            //var_dump($out);
            $xls_file = trim($out);
            $file_name = basename($xls_file);
            $file_name = Configure::read('sendrate.repfix') . $file_name;
            $mailer->AddAttachment($xls_file, $file_name);
            array_push($xls_files, $xls_file);
        }
        
        $content = str_replace('{tech-prefix}', implode(',', $teach_prefix), $content);
        //var_dump($out);
        $mailer->Body = $content;
        
        $res = $mailer->Send();
        //var_dump($send_address);
        //var_dump($res);
        
        $data['Ratemailhistory'] = array(
            'send_to' => $send_address,
            'mail_content' => $content,
            'files' => implode(',', $xls_files)
        );
        
        $this->Ratemailhistory->save($data);
        
        $this->Ratemail->create_json_array('#myform',201,__('Succeeded',true));
        $this->Session->write("m",Ratemail::set_validator());
        
        //$this->xredirect('/clients');
        
    }
    
    
    public function test() {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        App::import('Vendor', 'mail/sendmail');
        $mailer = new phpmailer();
        $mailer->IsSMTP();
	$mailer->SMTPAuth 	= true; 
	$mailer->From 		= "hewx@mail.yht.com";
	$mailer->FromName	= "hewx";
	$mailer->Host		= "192.168.1.125";
	$mailer->Port		= 25;		//25
	$mailer->Username	= "hewx@mail.yht.com";
	$mailer->Password	= "SLKW873ds";
        $mailer->AddAddress('zhouwh@mail.yht.com');
        $mailer->Subject = "Hi";
        $mailer->Body = "How are you!";
        $mailer->AddAttachment("/tmp/test");
        $mailer->Send();
    }
    
    
        public function get_code_name($rate_table_id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $code_name = $this->Ratemail->query("select rate_id, code_name from rate where rate_table_id = {$rate_table_id}");
        echo json_encode($code_name);
    }
    
}


?>
