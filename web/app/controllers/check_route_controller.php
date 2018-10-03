
<?php

class CheckRouteController extends AppController
{
    var $name = 'CheckRoute';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $components = array('RequestHandler');
    var $uses = array("Client", 'CheckRoute','Mailtmp','Cdr','EgressTest');

    function sip_packet()
    {
        $this->pageTitle = 'Tools/SIP PACKET Search';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        extract($this->Cdr->get_start_end_time());
        $start_date = $start_date;
        $end_date = $end_date;
        $gmt = $tz;

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        /* if (isset($_GET['filter_payment_term_id']))
         {
            // $where .= " AND client.payment_term_id =" . intval($_GET['filter_payment_term_id']);
         }*/

        $sst_user_id = $_SESSION['sst_user_id'];

        $this->set('show_nodata', true);
        session_write_close();
        $count = $this->CheckRoute->get_cdr_packcount($sst_user_id,$start_date,$end_date );

        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'order by  time  desc';
        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }
        $data = $this->CheckRoute->get_cdr_pack($sst_user_id, $order_by,$start_date,$end_date, $pageSize, $offset);
        //var_dump($data);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('get_data', $this->params['url']);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('lang', $this->Session->read("Config.language"));
    }

    function index()
    {
        $this->pageTitle = 'Tools/Check Route';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $where = "";

        if (isset($this->params['url']['time']) && $this->params['url']['time'] && isset($this->params['url']['end_time']) && $this->params['url']['end_time'])
        {
            $where = " where start_time >= '" . $this->params['url']['time'] . "'";
            $where .= " and  start_time <= '" . $this->params['url']['end_time'] . "'";
        }

        /* if (isset($_GET['filter_payment_term_id']))
         {
            // $where .= " AND client.payment_term_id =" . intval($_GET['filter_payment_term_id']);
         }*/

        $sst_user_id = $_SESSION['sst_user_id'];
        $count = $this->CheckRoute->get_cdr_count($sst_user_id, $where);

        if($count == 0)
        {
            $msg = "";
            $add_url = "add";
            $model_name = "CheckRoute";
            $this->to_add_page($model_name,$msg,$add_url);
        }
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'order by  start_time  desc';
        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }
        $data = $this->CheckRoute->get_cdr($sst_user_id, $order_by, $where, $pageSize, $offset);
        //var_dump($data);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('get_data', $this->params['url']);
        $this->set('lang', $this->Session->read("Config.language"));
    }


    public function showalldetail_old($id){
        $this->pageTitle = 'Tools/Check Route';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $where = "where egress_test_id = {$id} ";

        if (isset($this->params['url']['time']) && $this->params['url']['time'] && isset($this->params['url']['end_time']) && $this->params['url']['end_time'])
        {
            $where = " where start_time >= '" . $this->params['url']['time'] . "'";
            $where .= " and  start_time <= '" . $this->params['url']['end_time'] . "'";
        }

        /* if (isset($_GET['filter_payment_term_id']))
         {
            // $where .= " AND client.payment_term_id =" . intval($_GET['filter_payment_term_id']);
         }*/

        $sst_user_id = $_SESSION['sst_user_id'];
        $count = $this->CheckRoute->get_cdr_count1($sst_user_id, $where);

        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'order by  start_time  desc';
        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }
        $data = $this->CheckRoute->get_cdr1($sst_user_id, $order_by, $where, $pageSize, $offset);
        //var_dump($data);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('get_data', $this->params['url']);
        $this->set('lang', $this->Session->read("Config.language"));


    }


    public function showalldetail($encode_id){

        $this->pageTitle = 'Tools/Check Route';
        $id = intval(base64_decode($encode_id));
        $conditions = array('EgressTest.id' => $id);

        $sst_user_id = $_SESSION['sst_user_id'];

        $this->set('lang', $this->Session->read("Config.language"));
        $this->loadModel('EgressTest');
//        $egress_test_info = $this->EgressTest->find('first',array(
//            'fields' => array(
//                'EgressTest.start_time','EgressTest.end_time','EgressTest.code_name','EgressTest.success_calls',
//                'EgressTest.total_calls','EgressTest.create_by','Resource.alias','EgressTest.egress_id','EgressTest.id'
//            ),
//            'joins' => array(
//                array(
//                    'alias' => 'Resource',
//                    'table' => 'resource',
//                    'type' => 'inner',
//                    'conditions' => array(
//                        'Resource.resource_id = EgressTest.egress_id'
//                    ),
//                ),
//            ),
//            'conditions' => $conditions
//        ));
//        pr($egress_test_info);die;

//        $this->set('egress_test_info',$egress_test_info);


    }


    public function create_result_pdf($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = intval(base64_decode($encode_id));

        $this->loadModel('EgressTest');
        $html = $this->EgressTest->get_result_pdf_content($id);
        App::import("Vendor", "other", array('file' => 'wkhtmltopdf.php'));
        $binexe = APP . 'binexec' . DS . 'wkhtmltopdf' . DS . 'wkhtmltopdf-amd64';
        $randomhtml = WWW_ROOT . 'upload' . DS . 'html' . DS . uniqid() . '.html';
        file_put_contents($randomhtml, $html);
        $base_path = Configure::read('database_export_path');
        $filename = uniqid() . '.pdf';
        $result_file = $base_path . DS . $filename;
        $cmd = "$binexe -s Letter $randomhtml $result_file";
        shell_exec($cmd);
        $data = file_get_contents($result_file);
        header('Content-Type: application-x/force-download');
        header('Content-Length: ' . strlen($data));
        header('Content-Disposition: inline; filename="'.$filename.'"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        ini_set('zlib.output_compression', '0');
        readfile($result_file);
        die;
    }


    function _ddd(){


        Configure::load('myconf');
        $sip_path = Configure::read('sipp.sipp_exe');
        //var_dump($sip_path);

        $rule_id = isset($this->args[0]) ? (int) $this->args[0] : "";

        $numbers =  isset($this->args[1]) ? trim($this->args[1]) : "";

        $rule_id = 89;

        $numbers = "65116892,65116892";


        if (!$rule_id)
        {
            return false;
        }

        $rule_info_item = $this->CheckRoute->find(array('id' => $rule_id));

        if(empty($rule_info_item)){
            return false;
        }

//        $res = $this->CheckRoute->query("select * from  egress_test_result where egress_test_id = {$rule_id}  ");

        if(empty($res)){
            return false;
        }

        $values = array();
        foreach($res as $value){
            $values[$value[0]['dnis']] = $value[0]['id'];
        }

        //var_dump($rule_info_item);
        file_put_contents('/tmp/sipp.log', "\r\n\r\n" . date('Y-m-d H:i:s') . " Start Script  Numbser:\r\n" . $numbers . "\r\n  Trunk ID : {$rule_info_item['CheckRoute']['egress_id']}  \r\n" , FILE_APPEND);

        $numbers = explode(',', $numbers);
        foreach($numbers as $value){
            $call_id = md5(uniqid()) . '@' . "192.168.1.232";
            $cmd = " $sip_path -sf uac.xml -i 192.168.1.138 -p 6666 -m 1 -d 3000 -key  ani 117891245678  -s {$value} 192.168.1.232:5060  -cid_str {$call_id}  ";
            shell_exec($cmd);
            echo $cmd."<br/>";
//            $this->CheckRoute->query("update egress_test_result set end_time = current_timestamp(0) ,call_id = '{$call_id}' where id = {$values[$value]}  ");
        }

//        $this->CheckRoute->query("update egress_test set end_time = current_timestamp(0)  where id = $rule_id  ");

        file_put_contents('/tmp/invalid_number.log', date('Y-m-d H:i:s') . " End Script   \r\n" , FILE_APPEND);
        exit;

    }


    public function add()
    {
        $this->set('egresses_info',$this->CheckRoute->get_client_egress_group());
        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');


            if(empty($_POST['egress_id'])){
                $this->Mailtmp->create_json_array('', 201, "Egress Trunk can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            if(empty($_POST['sec'])){
                $this->Mailtmp->create_json_array('', 201, "Second can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            if(empty($_POST['numbers'])){
                $this->Mailtmp->create_json_array('', 201, "Number can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            $egress_id = $_POST['egress_id'];
            $sec = $this->_post('sec');

            $numbers = $this->_post('numbers');
            $numbers = explode("\n", $numbers);
            $numbers = array_unique($numbers);

            foreach($numbers as $kye=>$num){
                $numbers[$kye] = preg_replace("/\s/","",trim($num));
            }

            $total_call = count($numbers);
            $ingress_id = $this->_check_ingress();
            Configure::load('myconf');
            $test_ani = Configure::read('check_route.ani');
            foreach($egress_id as $value){
                $this->_check_egress($value,$ingress_id);
                //RETURNING id
//                $res= $this->CheckRoute->query("insert into egress_test (egress_id,total_calls,sec,create_by) values ({$value},{$total_call},{$sec},'{$this->Session->read('sst_user_name')}') RETURNING id ");
//                $res_id = $res[0][0]['id'];

//                $numbers_str = implode(',',$numbers);
//                $php_path = Configure::read('php_exe_path');
//                $cmd = "{$php_path} " . APP . "../cake/console/cake.php sipp {$res_id} {$numbers_str}  > /dev/null &";

                //var_dump($res,$cmd);
//                foreach($numbers as $num){
//                    $this->CheckRoute->query(" insert into egress_test_result (egress_test_id,start_time,ani,dnis) values ({$res_id},current_timestamp(0),'{$test_ani}','$num')  ");
//                }
                //echo time();
                //echo "<br/>";
//                $res = shell_exec($cmd);
                //echo time();

            }


            $this->redirect('index');
        }
    }

    public function test_again($encode_id)
    {
        $id = base64_decode($encode_id);
        $data = $this->EgressTest->find('first',array(
            'conditions' => array(
                'id' => $id,
            ),
        ));
        if (empty($data))
        {
            $this->Session->write('m', $this->EgressTest->create_json(101, __('Illegal operation!',true)));
            $this->redirect('showalldetail/'.$encode_id);
        }
        $egress_id = $data['EgressTest']['egress_id'];
        $number_arr = array();
        foreach ($data['EgressTestResult']  as $item)
        {
            $number_arr[] = $item['dnis'];
        }
        $total_calls = count($number_arr);
        $numbers_str = implode(',',$number_arr);
        $sec = $data['EgressTest']['sec'];
//        $res= $this->CheckRoute->query("insert into egress_test (egress_id,total_calls,sec,create_by) values ({$egress_id},{$total_calls},{$sec},'{$this->Session->read('sst_user_name')}') RETURNING id ");
//        $res_id = $res[0][0]['id'];
//
//        $php_path = Configure::read('php_exe_path');
//        $cmd = "{$php_path} " . APP . "../cake/console/cake.php sipp {$res_id} {$numbers_str}  > /dev/null &";
//        Configure::load('myconf');
//        $test_ani = Configure::read('check_route.ani');
//        //var_dump($res,$cmd);
//        foreach($number_arr as $num){
//            $this->CheckRoute->query(" insert into egress_test_result (egress_test_id,start_time,ani,dnis) values ({$res_id},current_timestamp(0),'{$test_ani}','$num')  ");
//        }
        $this->Session->write('m', $this->EgressTest->create_json(201, __('Test again!',true)));
        $this->redirect('index');
    }


    public function add_old(){
        $this->set('type', 14);
        $this->set('egress',$this->Cdr->findAll_egress_id());

        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');

            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';

            if(empty($_POST['egress_id'])){
                $this->Mailtmp->create_json_array('', 201, "Egress Trunk can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            if(empty($_POST['sec'])){
                $this->Mailtmp->create_json_array('', 201, "Second can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            if(empty($_POST['numbers'])){
                $this->Mailtmp->create_json_array('', 201, "Number can not be empty.");
                $this->Session->write('m', Mailtmp::set_validator());
                $this->xredirect('/check_route/add');
            }

            $egress_id = $_POST['egress_id'];
            $sec = $this->_post('sec');

            $numbers = $this->_post('numbers');
            $numbers = explode("\n", $numbers);
            $numbers = array_unique($numbers);

            foreach($numbers as $kye=>$num){
                $numbers[$kye] = preg_replace("/\s/","",trim($num));
            }

            $total_call = count($numbers);
            $ingress_id = $this->_check_ingress();
            Configure::load('myconf');
            $test_ani = Configure::read('check_route.ani');
            foreach($egress_id as $value){
                $this->_check_egress($value,$ingress_id);
                //RETURNING id
//                $res= $this->CheckRoute->query("insert into egress_test (egress_id,total_calls,sec,create_by) values ({$value},{$total_call},{$sec},'{$this->Session->read('sst_user_name')}') RETURNING id ");
//                $res_id = $res[0][0]['id'];
//
//                $numbers_str = implode(',',$numbers);
//                $php_path = Configure::read('php_exe_path');
//                $cmd = "{$php_path} " . APP . "../cake/console/cake.php sipp {$res_id} {$numbers_str}  > /dev/null &";
//
//                //var_dump($res,$cmd);
//                foreach($numbers as $num){
//                    $this->CheckRoute->query(" insert into egress_test_result (egress_test_id,start_time,ani,dnis) values ({$res_id},current_timestamp(0),'{$test_ani}','$num')  ");
//                }
//                //echo time();
//                //echo "<br/>";
//                $res = shell_exec($cmd);
                //echo time();

            }


            $this->xredirect('/check_route/index');
        }
    }


    function _check_egress($egress,$ingress){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $flag = true;

        $res = $this->CheckRoute->query("select * from resource_prefix  where resource_id = {$ingress} and tech_prefix = '{$egress}'  ");
        //var_dump($res);
        if(empty($res)){
            $flag = false;
            //echo "111";
        }else{
            $res = $this->CheckRoute->query("select * from route_strategy  where route_strategy_id = {$res[0][0]['route_strategy_id']} ");
            if(empty($res)){
                $flag = false;
                //echo "222";
            }else{
                $res = $this->CheckRoute->query("select * from route  where route_strategy_id = {$res[0][0]['route_strategy_id']} ");
                if(empty($res)){
                    $flag = false;
                    //echo "333";
                }else{
                    //echo "select * from dynamic_route_items  where dynamic_route_id = {$res[0][0]['dynamic_route_id']}  and resource_id = {$egress} ";
                    $res = $this->CheckRoute->query("select * from dynamic_route_items  where dynamic_route_id = {$res[0][0]['dynamic_route_id']}  and resource_id = {$egress} ");
                    if(empty($res)){
                        $flag = false;
                        //echo "444";
                    }
                }
            }
        }

        if(!$flag){
            $name = uniqid(time());
            $route_strategy_id = $this->CheckRoute->query(" insert into route_strategy (name) values ('{$name}') RETURNING route_strategy_id ");
            $route_strategy_id = $route_strategy_id[0][0]['route_strategy_id'];

            $sql = "SELECT nextval('dynamic_route_dynamic_route_id_seq'::regclass) as nid";
            $query = $this->CheckRoute->query($sql);

            $data = array(
                'dynamic_route_id' => $query[0][0]['nid'],
                'name' => $query[0][0]['nid'],
                'routing_rule' => 4,
            );

            $dynamic_route_id = $this->CheckRoute->query("insert into dynamic_route (dynamic_route_id,name,routing_rule) values ({$query[0][0]['nid']},'{$query[0][0]['nid']}',4) RETURNING dynamic_route_id  ");
            $dynamic_route_id = $dynamic_route_id[0][0]['dynamic_route_id'];


            $sql = "INSERT INTO route( dynamic_route_id, route_type, route_strategy_id, code_deck_type)
                    VALUES({$dynamic_route_id}, 1,{$route_strategy_id}, 0)";
            $this->CheckRoute->query($sql);

            $sql_item = "INSERT INTO dynamic_route_items(dynamic_route_id, resource_id) VALUES ({$dynamic_route_id}, {$egress})";
            $this->CheckRoute->query($sql_item);


            $rate_table_name = "rate_table_".$ingress;

            $rate_table = $this->CheckRoute->query("select * from rate_table where name =  '{$rate_table_name}' ");
            if(!empty($rate_table)){
                $rate_table_id = $rate_table[0][0]['rate_table_id'];
            }else{
                $current_id = $this->CheckRoute->query("select * from currency where code = 'USA' ");
                $current_id = $current_id[0][0]['currency_id'];
                $rate_table = $this->CheckRoute->query("insert into rate_table (name,currency_id) values ('{$rate_table_name}',{$current_id})  RETURNING rate_table_id  ");
                $rate_table_id = $rate_table[0][0]['rate_table_id'];
            }

            $rates = $this->CheckRoute->query("select count(*) from rate where code in ('1','2','3','4','5','6','7','8','9') and rate = 100 and rate_table_id = {$rate_table_id}  ");
            if($rates[0][0]['count'] != 9){
                $sql = "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'1',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'2',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'3',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'4',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'5',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'6',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'7',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'8',100,current_timestamp(0));"
                    . "insert into rate (rate_table_id,code,rate,effective_date) values ({$rate_table_id},'9',100,current_timestamp(0)); " ;
                $this->CheckRoute->query($sql);
            }



            $sql = "INSERT INTO resource_prefix(resource_id, tech_prefix, route_strategy_id,rate_table_id)
                    VALUES ({$ingress}, '{$egress}', {$route_strategy_id},{$rate_table_id})";
            $this->CheckRoute->query($sql);



        }

    }

    function _check_ingress(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //$config['check_route']['carrier_name'] = "Check Route";
        //$config['check_route']['trunk_name'] = "check_route_ingress_trunk";
        Configure::load('myconf');
        $test_carrier = Configure::read('check_route.carrier_name');
        $test_ingress = Configure::read('check_route.trunk_name');
        $test_ip = Configure::read('check_route.ip');

        $res = $this->CheckRoute->query("select client_id from  client where name =  '{$test_carrier}' ");
        if(empty($res)){

            //$res= $this->CheckRoute->query("insert into egress_test (egress_id,total_calls) values ({$value},{$total_call}) RETURNING id ");
            //$res_id = $res[0][0]['id'];
            $current_id = $this->CheckRoute->query("select * from currency where code = 'USA' ");
            $current_id = $current_id[0][0]['currency_id'];
            $client = $this->CheckRoute->query("insert into client (name,currency_id,unlimited_credit,mode,enough_balance) values ('{$test_carrier}',{$current_id},'t',2,'t') RETURNING client_id ");
            $client_id = $client[0][0]['client_id'];
            $this->CheckRoute->clientBalanceOperation($client_id, 10, 2);
        }else{
            $client_id = $res[0][0]['client_id'];
        }

        $trunk = $this->CheckRoute->query("select resource_id from  resource where client_id = {$client_id}  and alias = '{$test_ingress}' ");
        if(empty($res)){
            $trunk = $this->CheckRoute->query("insert into resource (alias,client_id,ingress,egress,enough_balance,media_type) values ('{$test_ingress}',{$client_id},'t','f','t',2)  RETURNING resource_id  ");
        }

        $ingress_ip = $this->CheckRoute->query("select * from resource_ip where resource_id = {$trunk[0][0]['resource_id']}  ");
        if(empty($ingress_ip)){
            $this->CheckRoute->query(" insert into resource_ip (resource_id,ip,port) values ({$trunk[0][0]['resource_id']},'{$test_ip}',5060 ) ");
        }else{
            if($test_ip != $ingress_ip[0][0]['ip']){
                $this->CheckRoute->query(" update  resource_ip set ip ='{$test_ip}' where resource_id = {$trunk[0][0]['resource_id']} and resource_ip_id = {$ingress_ip[0][0]['resource_ip_id']} ");
            }
        }



        return $trunk[0][0]['resource_id'];
    }

    function del_cdr($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = _filter_array(Array('false' => false, 'true' => true));
//        $this->CheckRoute->query("delete from egress_test_result where egress_test_id = {$id}");
//        $this->CheckRoute->query("delete from egress_test where id = {$id}");
        echo 'true';
    }

    function download($id,$type){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
//        $res = $this->CheckRoute->query("select *  from cdr_compare_cloud where id = {$id}");
        if(!empty($res)){

            $file = "";
            if($type == 1){
                $file = $res[0][0]['source_filename'];
            }else if($type == 2){
                $file = $res[0][0]['diff_filename'];
            }else if($type == 3){
                $file = $res[0][0]['match_cdr_file'];
            }else if($type == 4){
                $file = $res[0][0]['mismatch_cdr_file'];
            }else if($type == 5){
                $file = $res[0][0]['left_right_cdr_file'];
            }else if($type == 6){
                $file = $res[0][0]['aggregated_analysis_file'];
            }
            $basename = basename($file);
            ob_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename={$basename}");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            readfile($file);
        }
    }


    function get_result_index(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $id = $this->_post('cdr_id');
//        $res = $this->CheckRoute->query("select *  from egress_test where id = {$id}");

//        echo json_encode($res[0][0]);
    }

    function get_result(){
        Configure::write('debug', 0);
        $call_id = $this->_post('call_id');
        $time = $this->_post('time');
        $time = str_replace('-', '', $time);
        $time = explode(' ',$time);

        $table = "client_cdr".$time[0];

        /*$release_cause = "case  release_cause
    when    0    then   'Unknown Exception'
           when    1     then   'System CPS Limit Exceeded'
           when    2     then   'SYSTEM_CPS System Limit Exceeded'
           when    3     then   'Unauthorized IP Address'
           when    4     then   ' No Ingress Resource Found'
   when    5     then   'No Product Found '
   when    6     then   'Trunk Limit Call Exceeded'
   when    7     then   'Trunk Limit CPS Exceeded'
   when    8     then   'IP Limit  Call Exceeded'
   when    9     then   'IP Limit CPS Exceeded 	'
   when   10    then   'Invalid Codec Negotiation'
   when   11    then   'Block due to LRN'
   when   12 			then  'Ingress Rate Not Found'
   when   13 			then  ' Egress Trunk Not Found'
   when   14 			then  'Egress Returns 404'
   when   15 			then  'Egress Returns 486'
   when   16 			then  'Egress Returns 487'
   when   17 			then  'Egress Returns 200'
   when   18 			then  'All Egress Unavailable'
   when   19 			then  'Normal hang up'
   when   20 			then  'Ingress Resource disabled'
   when   21 			then  'Zero Balance'
   when   22 			then  'No Route Found'
   when   23 			then  'Invalid Prefix'
   when   24 			then  'Ingress Rate Missing'
   when   25                     then 'Invalid Codec Negotiation'
   when   26                     then 'No Codec Found'
   when   27                     then 'All Egress Failed'
   when   28                     then 'LRN Response Missing'
   when   29    then 'Carrier Call Limit Exceeded'
   when   30    then 'Carrier CPS Limit Exceeded'
   when   31   then 'Rejected Due to Host Alert'
   when   32   then 'Rejected Due to Trunk Alert'
   when   33   then 'H323 Not Supported'
   when   34   then '180 Negotiation Failure'
   when   35   then '183 Negotiation Failute'
   when   36  then '200 Negotiation Failure'
   when   37  then 'Block LRN with Higher Rate'
           when   38 then 'Ingress Block ANI'
           when   39 then 'Ingress Block DNIS'
           when   40 then 'Ingress Block ALL'
           when   41 then 'Global Block ANI'
           when   42 then 'Global Block DNIS'
           when   43 then 'Global Block ALL'
           when   44 then 'T38 Reject'
   else    'other'  end  as
   release_cause";*/
        $release_cause = " release_cause ";


        $sql = "SELECT origination_call_id,egress_id,origination_source_host_name,termination_destination_host_name,(select alias from resource where resource_id = egress_id) as egress_name,
ring_time,pdd, to_timestamp(substring(start_time_of_date::text from 1 for 10)::bigint) 
AS start_time,time AS end_time, orig_code_name AS code_name, ingress_client_cost as cost,
 origination_source_number AS source_number, origination_destination_number AS destination_number,
  $release_cause ,release_cause_from_protocol_stack as response, call_duration,ingress_client_rate AS rate,
         ingress_client_cost AS cost FROM $table WHERE origination_call_id like '{$call_id}%'";

        //$res = $this->Cdr->query("select *  from client_cdr where time = '{$time}' and origination_call_id = '{$call_id}' and is_final_call = 0 ");
        //echo $sql;
        $res = $this->Cdr->query($sql);
        //var_dump($res);
        $result = array();
        if(count($res) != 0){
            $result = $res[0];
        }
        $this->set('results',$result);
    }

    public function get_sip($encode_id)
    {
        Configure::load('myconf');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $return_arr = array();
        $return_arr['status'] = 0;
        $id = base64_decode($encode_id);
        $this->loadModel('EgressTestResult');
        $count = $this->EgressTestResult->find('count',array('conditions' => array('id' => $id,'cdr_time is not null')));
        if (!$count)
        {
            $return_arr['msg'] = __('Data not exists',true);
            return json_encode($return_arr);
        }

        $result_data = $this->EgressTestResult->findById($id);
        $time = $result_data['EgressTestResult']['time'];
        $duration = $result_data['EgressTestResult']['duration'];
        $switch_ip = $result_data['EgressTestResult']['switch_ip'];
        $url = Configure::read('cloud_shark.view_api');
        $cloud_api = Configure::read('cloud_shark.cloud_api');
        $origination_call_id = base64_decode($result_data['EgressTestResult']['call_id']);
        $call_ids = $origination_call_id;

//        $id = $this->Cdr->query("select * from class4_call_id_cloud_shark_id_map where call_id = '{$call_ids}' and type = 1 ");
        if(!empty($id[0][0]['cloud_shark_id']))
            $cloud_shark_id = $id[0][0]['cloud_shark_id'];
        else
            $cloud_shark_id="";

        $request = array(
            '0'=>'time='.base64_encode($time),
            '1'=>'call_id='.base64_encode($call_ids),
            '2'=>'type='.base64_encode(1),
            '3'=>'cloud_shark_id='.$cloud_shark_id,
            '4'=>'duration='.base64_encode($duration),
            '5'=>'switch_ip='.base64_encode($switch_ip)

        );

        $request_1 = array(
            '0'=>'start_time='.base64_encode($time),
            '1'=>'caller_id='.base64_encode($call_ids),
            '2'=>'duration='.base64_encode($duration),
            '3'=>'switch_ip='.base64_encode($switch_ip)
        );


        $url_c = implode('&',$request);
        $url_c = "{$cloud_api}?".$url_c;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url_c);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($data);

        if(!empty($res->status) && $res->status == 'yes'){
            $url_c = implode('&',$request_1);
            $return_url = "{$url}?$url_c";
            $return_arr['msg'] = $return_url;
            $return_arr['status'] = 1;
//            Header("Location:{$url}?$url_c ");
        }else{
            $return_arr['msg'] = __('Can not Connection to Central System.',true);
        }
        return json_encode($return_arr);
        //var_dump($url_c,$res);

//        if(!empty($res->status) && $res->status == 'yes' && !empty($res->id)){
//
//            if(!empty($res->msg) && !empty($res->id) && $res->msg == 'not_has'){
//                //echo "insert ";
//                $this->Cdr->query("insert into class4_call_id_cloud_shark_id_map (call_id,cloud_shark_id,type) values ('{$call_ids}','{$res->id}',{$pcap_type})");
//            }else{
//                //echo "no insert";
//            }
//            Header("Location:{$url}{$res->id} ");
//        }else{
//            echo __("not_find_pcap");
//        }
    }


    function get_sip_old($id){
        Configure::load('myconf');
        Configure::write('debug', 2);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($id);
//        $res = $this->Cdr->query(" select  *  from egress_test_result where id = {$id}  ");
        $res = array();
        $url = Configure::read('cloud_shark.view_api');
        $api = Configure::read('cloud_shark.upload_api');
        $search_api = Configure::read('cloud_shark.search_api');
        //var_dump($res);
        if(empty($res)){
            echo __('no_result');
        }else{
            if(!empty($res[0][0]['call_id'])){
                $file = $this->_get_file($res[0][0]['call_id'],$res[0][0]['end_time']);
                //$file = "/opt/sdfsdf.pcap";
                if(file_exists($file)){
                    $res =$this->_search_api($search_api,$file);
                    $res_error = $res;
                    $res = json_decode($res);
                    $cap = $res->captures;

                    if(!empty($cap)){
                        Header("Location:{$url}{$cap[0]->id} " );
                    }else{
                        $res =$this->_upload_api($api,$file);
                        $res_error = $res;
                        $res = json_decode($res);
                        if(!empty($res->id)){
//                            $this->Cdr->query("update egress_test_result set pcap_id = '{$res->id}' where id = {$id} ");
                            Header("Location:{$url}{$res->id} " );
                        }else{
                            echo $res_error;
                        }
                    }

                }else{
                    echo __("not_find_pcap");
                }
            }else{
                echo __("no_call_id");
            }
        }
    }

    function _search_api($api,$file_name){
        $this->autoRender = false;
        $this->autoLayout = false;
        $ch =curl_init();
        $file_name =  basename($file_name);
        $api = "{$api}?search[filename][]={$file_name}";
        //$data = array('name'=>'Foo','file'=>"@/opt/sdfsdf.pcap");// '@/opt/sdfsdf.pcap'
        curl_setopt($ch,CURLOPT_URL,$api);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,array());
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    function _upload_api($api,$file){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ch =curl_init();
        $data = array('name'=>'Foo','file'=>"@".$file);// '@/opt/sdfsdf.pcap'
        curl_setopt($ch,CURLOPT_URL,$api);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    function _get_file($call_id,$time) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::load('myconf');
        $path = Configure::read('voip_moniter.pcap_path');
        //var_dump($path);
        $last = date("Y-m-d H",strtotime('-1 hour',strtotime($time)));
        //$last = str_replace('-', '/', $last);
        $last = str_replace(' ', '/', $last);

        $next = date("Y-m-d H",strtotime('+1 hour',strtotime($time)));
        //$next = str_replace('-', '/', $next);
        $next = str_replace(' ', '/', $next);
        $current = date('Y-m-d H',strtotime($time));
        //$current = str_replace('-', '/', $current);
        $current = str_replace(' ', '/', $current);
        $fpath = "";
        //var_dump($last,$current,$next);

        $file_path = $path.$current;
        //echo $file_path;
        $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
        $fpath = trim(shell_exec($cmd));
        //echo $cmd;
        if(!file_exists($fpath)){
            $file_path = $path.$next;
            $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
            $fpath = trim(shell_exec($cmd));

            if(!file_exists($file_path)){
                $file_path = $path.$last;
                $cmd = "find {$file_path} -name '{$call_id}*.pcap'";
                $fpath = trim(shell_exec($cmd));
                if(!file_exists($file_path)){
                    $fpath = "";
                }
            }
        }
        //var_dump($fpath);
        return $fpath;
    }


    function getResult(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $cdr_id = $this->_post('call_id');
//        $res = $this->CheckRoute->query("select *  from cdr_compare_cloud where id = {$cdr_id}");

//        if($res[0][0]['diff_duration_type'] == 2){
//            $res[0][0]['df_total_duration'] = number_format(($res[0][0]['df_total_duration']/60), 2, '.' ,'');
//        }else{
//            $res[0][0]['df_total_duration'] = number_format($res[0][0]['df_total_duration'], 2, '.' ,'');
//        }
//        $res[0] = $res[0][0];
        $res = array();
        echo json_encode($res);
    }

    public function get_file_top(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->layout = 'ajax';
        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
        $file_path = $_POST["file_path"];
        $file_path=preg_replace("/\s/","",$file_path);
        //$old_file = $_POST["old_file"];
        //var_dump($file_path);
        $file_path = $path . DS . $file_path . ".csv";
        // var_dump($file_path);
        if(!file_exists($file_path)){
            $data['status'] = "no_file";
        }else{
            $data=$this->get_file($file_path);
        }
        echo json_encode($data);
    }

    function get_file1($file_path,$old_file=''){
        //sleep(5);
        $data['data'] = array();
        $data['cols'] = 0;

        $data['status'] = "success";


        if(!file_exists($file_path)){
            $data['status'] = "no_file";
        }else{
            @unlink($old_file);
            //读取文件
            $fp = fopen($file_path, "r");
            $line = 0;
            $pos = 0;
            $t = " ";
            $data1 = "";

            while (!feof($fp) && $line<11) {
                while ($t != "\n" & !feof($fp)) {
                    fseek($fp, $pos, SEEK_SET);
                    $t = fgetc($fp);
                    $data1 .= $t;
                    $pos ++;
                }

                $headers = explode(',', $data1);
                $data['data'][] = $headers;
                if(count($headers) > $data['cols']){
                    $data['cols'] = count($headers);
                }

                $data1 = "";
                $t = " ";
                $line ++;
            }
            fclose ($fp);
        }
        return $data;
    }

    public function support(){
        $this->pageTitle = "Switch/Mail Template";
        if (!empty($this->params['form']))
        {

            //var_dump($this->params['form']);
            App::import('Vendor', 'nmail/phpmailer');
            $mailer = new phpmailer();

            $email_id = $this->params['form']['invoice_from'];

            if(empty($email_id)){
                $sql = "SELECT * from system_parameter";
            }else{
                $sql = "SELECT name as emailname,smtp_host as smtphost, smtp_port as smtpport, loginemail,username as emailusername, password as emailpassword, email as fromemail, secure as smtp_secure FROM mail_sender WHERE id = {$email_id} ";
            }

            $email_info = $this->Mailtmp->query($sql);
            //var_dump($email_info);
            $mailer->IsSMTP();
            $mailer->SMTPAuth = true;
            $mailer->IsHTML(true);
            $mailer->From = $email_info[0][0]['fromemail'];
            $mailer->FromName = $email_info[0][0]['emailname'];
            $mailer->Host = $email_info[0][0]['smtphost'];
            $mailer->Port = intval($email_info[0][0]['smtpport']);
            $mailer->Username = $email_info[0][0]['emailusername'];
            $mailer->Password = $email_info[0][0]['emailpassword'];
            //$mailer->SMTPSecure = 'tls';
            // $mailer->SMTPSecure = 'ssl';

            $send_address = trim($this->params['form']['to_email']);
            $mailer->AddAddress($send_address);
            $mailer->Subject = $this->params['form']['invoice_subject'];
            $mailer->Body = $this->params['form']['invoice_content'];
            //$mailer->Send();

            if ($mailer->Send())
            {
                $this->Mailtmp->create_json_array('', 201, __('Successfully!', true));
                $this->Session->write('m', Mailtmp::set_validator());
            }
            else
            {
                $this->Mailtmp->create_json_array('', 201, __('Failed!', true));
                $this->Session->write('m', Mailtmp::set_validator());
            }

        }
        Configure::load('myconf');
        $to_email = Configure::read('email.support_email');
        $this->set('email',$to_email);
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);

        //$this->set('tmp', $this->Mailtmp->query("select * from mail_tmplate"));
    }

    public function _down_test(){

        $copy_file = "/opt/test_excel.xls";
        $handle = fopen($copy_file, "w");
        //fwrite($handle, "size\tsize1\t\n");
        //fwrite($handle, $sql."\t\n");
        $result = array();

        for($i=0;$i<10000;$i++){
            $result[] = array('12121212121212121212','3232323232323232323','c','d','e','f','g');
        }

        $size = count($result);
        if ($size > 0)
        {
            $w_words = implode("\t", array_keys($result[0]));
            fwrite($handle, $w_words);
            fwrite($handle, "\n");
        }
        for ($i = 0; $i < $size; $i++)
        {
            /*
              foreach($result[$i][0] as &$item)
              {
              $item = '"' . $item;
              }
             * 
             */
            //fwrite($handle, $i.chr(9));
            $w_words = implode("\t", $result[$i]);
            fwrite($handle, $w_words);
            fwrite($handle, "\n");
        }
        fclose($handle);

        $this->_download_xls($copy_file,'test_excel.xls');

    }


    public function _download_xls($download_file, $file_name)
    {
        $file_name = str_replace(".csv", ".xls", $file_name);
        $file_size = filesize($download_file);
        header("Content-type:application/vnd.ms-excel;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $file_size");
        header("Content-Disposition:attachment; filename=" . $file_name);

        $fp = fopen($download_file, "r");
        $buffer_size = 1024;
        $cur_pos = 0;
        while (!feof($fp) && $file_size - $cur_pos > $buffer_size)
        {
            $buffer = fread($fp, $buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }

        $buffer = fread($fp, $file_size - $cur_pos);
        echo $buffer;
        fclose($fp);
        return true;
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        if ($this->params['action'] == 'notify')
            return true;
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if (PRI)
        {
            $this->Session->write('executable', $_SESSION['role_menu']['Management']['clients']['model_x']);
            $this->Session->write('writable', $_SESSION['role_menu']['Management']['clients']['model_w']);
        }
        else
        {
            if ($login_type == 1)
            {
                $this->Session->write('executable', true);
                $this->Session->write('writable', true);
            }
            else
            {
                $limit = $this->Session->read('sst_wholesale');
                $this->Session->write('executable', $limit['executable']);
                $this->Session->write('writable', $limit['writable']);
            }
        }
        if (!$_SESSION['role_menu']['Management']['clients']['model_r'])
        {
            $this->redirect_denied();
        }
        parent::beforeFilter();
    }


}

?>
