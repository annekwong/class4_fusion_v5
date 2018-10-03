
<?php

class PartitionController extends AppController
{
    
    var $name = 'Partition';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $uses = array("prresource.Gatewaygroup","Par", "Client", 'Clients', 'Credit', 'Orderuser');

    function index()
    {
        $this->pageTitle = 'Management/Partitions';
        
        $where = "1=1";
        if(!empty($_GET['search'])){
            $where .= " and username ilike '%{$_GET['search']}%' ";
        }
        
//        $sql = "select count(*) from exchange_par_account where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
//        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
//        $order_sql = "order by exchange_par_account.username ASC";

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
//        $sql = "SELECT exchange_par_account.*,switch_profile.sip_ip,switch_profile.sip_port from exchange_par_account
//        left join switch_profile on switch_profile.id = exchange_par_account.service_ip_id
//        where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
//        $data = $this->Client->query($sql);
        $data = array();
        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    
    
    function par_plan()
    {
        $this->pageTitle = 'Management/Partition Plan';
        
        $where = "1=1";
        
      
//        $sql = "select count(*) from exchange_par_plan where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count']+1;
        require_once 'MyPage.php';
        $page = new MyPage ();
//        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by id desc";

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
//        $sql = "SELECT * from exchange_par_plan  where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
//        $data = $this->Client->query($sql);
        $data = array();
        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function save_par_pwd()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $pwd = md5($_POST['pwd']);
        $agent_name = $_POST['name'];
      
//        $this->Client->query("update exchange_par_account set pwd = '{$pwd}' where id = {$id} ");
        $this->Client->logging(2, 'Partition', "Partition Name:{$agent_name} (Change password)");
        $this->Client->create_json_array('', 201, __('The Partition [%s] is modified successfully!', true, $agent_name));
        $data['status'] = "success";
        
        echo json_encode($data);
    }

    
    public function save_partition()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $agent_name = $_POST['name'];
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $domain = $_POST['domain'];
        $status = $_POST['status'];
        
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
            $data['status'] = "email_error";
        }else{
            if (!empty($agent_name))
            {
//                $res = $this->Client->query("select count(*) from exchange_par_account  where (username = '{$agent_name}' or domain_name = '{$domain}') and id != {$id} ");

//                if ($res[0][0]['count'] != 0)
//                {
//                    $data['status'] = "isHave";
//                } else
//                {
//                    $this->Client->query("update exchange_par_account set  username = '{$agent_name}',
//                    company_name = '{$company_name}' , email = '{$email}'
//                    ,phone_number = '{$phone_number}',domain_name = '{$domain}',status = {$status}
//                    where id = {$id} ");
//                    $this->Client->logging(2, 'Partition', "Partition Name:{$agent_name}");
//                    $this->Client->create_json_array('', 201, __('The Partition [%s] is modified successfully!', true, $agent_name));
//                    $data['status'] = "success";
//                }
            } else
            {
                $data['status'] = "isEmpty";
            }
        }
        echo json_encode($data);
    }
    
    
    
    
    
    
    
    public function add_plan(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        
        $agent_name = $_POST['money'];
        //$company_name = $_POST['cost_min'];
        //$remain_minute = intval($agent_name/$company_name);
        
        $port = $_POST['port'];
        $type = $_POST['type'];
        $minutes = $_POST['minutes'];
        
        $data = array('status'=>'');

        //var_dump($company_name);
        
         if(!is_numeric($agent_name) && ( !is_numeric($port) || $type == 1 ) && (!is_numeric($minutes) || $type == 0 ) && $agent_name != 0 && $port != 0 && ( $minutes != 0  || $type == 0) ){
            $data['status'] = "email_error";
         }else{
             
            if (!empty($agent_name) && (!empty($port) || $type == 1 ) &&  (!empty($minutes) || $type == 0 ) )
            {
                
                if($type == 0){
//                    $res = $this->Client->query("insert into exchange_par_plan (port,money,type)
//                                    values
//                                    ({$port},{$agent_name},{$type})");
                }else{
//                    $res = $this->Client->query("insert into exchange_par_plan (money,type,minutes)
//                                    values
//                                    ({$agent_name},{$type},{$minutes})");
                }
                
                
                $this->Client->logging(0, 'Plan', "Plan:{$agent_name}");
                $this->Client->create_json_array('', 201, __('The Plan [%s] is created successfully', true, $agent_name));
                $data['status'] = "success";
            } else
            {
                $data['status'] = "isEmpty";
            }
        }
        echo json_encode($data);
    }
    
    public function save_pwd()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $pwd = md5($_POST['pwd']);
        $agent_name = $_POST['name'];
      
        $this->Client->query("update agent_client set pwd = '{$pwd}' where client_id = {$id} ");
        $this->Client->logging(2, 'Agent', "Agent Name:{$agent_name} (Change password)");
        $this->Client->create_json_array('', 201, __('The Agent [%s] is modified successfully!', true, $agent_name));
        $data['status'] = "success";
        
        echo json_encode($data);
    }

    
    public function save_plan()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $agent_name = $_POST['money'];
        //$company_name = $_POST['cost_min'];
        //$remain_minute = intval($agent_name/$company_name);
        
        
        $port = $_POST['port'];
        $type = $_POST['type'];
        $minutes = $_POST['minutes'];
        
        
        if(!is_numeric($agent_name) && (!is_numeric($port) || $type == 1 ) && (!is_numeric($minutes) || $type == 0 ) && $agent_name != 0 && $port != 0 && ( $minutes != 0  || $type == 0) ){
            $data['status'] = "email_error";
        }else{
            if (!empty($agent_name) && ( !empty($port) || $type == 1 ) &&  (!empty($minutes) || $type == 0 ) )
            {
                
                if($type == 0){
//                   $this->Client->query("update exchange_par_plan
//                        set port='{$port}',
//                        money = '{$agent_name}',
//                        type = {$type}
//                where id = {$id} ");
                }else{
//                    $this->Client->query("update exchange_par_plan
//                        money = '{$agent_name}',
//                        minutes = '{$minutes}',
//                        type = {$type}
//                where id = {$id} ");
                }
                
                
                $this->Client->logging(2, 'Plan', "Plan:{$agent_name}");
                $this->Client->create_json_array('', 201, __('The Plan [%s] is modified successfully!', true, $agent_name));
                $data['status'] = "success";
            } else
            {
                $data['status'] = "isEmpty";
            }
        }
        
        echo json_encode($data);
    }
    

    function set_parm(){
        $this->pageTitle = 'Management/System Parameter';
        
//        $sql = "SELECT * from exchange_par_system_parm limit 1  ";

        //echo $sql;
//        $data = $this->Client->query($sql);
        $data = array();
        if(!empty($_POST)){
            
            $ip_month_money = $_POST['ip_month_money'];
            
           
                if(!is_numeric($ip_month_money)){
                    $this->Client->create_json_array('', 101, __('The USD must be numeric.', true));
                }else{
                    if(count($data) == 0){
                        
//                        $this->Client->query("insert into exchange_par_system_parm (ip_month_money) values ({$ip_month_money})  ");
                    }else{
//                        $this->Client->query("update exchange_par_system_parm set ip_month_money = {$ip_month_money} where id = {$data[0][0]['id']}  ");
                    }
                    
                    $this->Client->create_json_array('', 201, __('The System Parameter is modified successfully!', true));
                }
            
        }
        
        
        
        if(count($data) == 0){
            $data = array(
                'ip_month_money'=>1,
            );
        }else{
            $data = $data[0][0];
        }
        
        $this->set('data', $data);
    }
    
     public function agent_finance(){
        $this->pageTitle = 'Management/Agent Finance';
        
        $where = "1=1";
        /*if(!empty($_GET['submit'])){
            $agent_name .= " and name ilike '%{$_GET['submit']}%' ";
        }*/
        
        if (isset($_GET['submit']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "action_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and action_number like '%{$name}%'";
            } else
            {
                $where = "action_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }
        
//        $sql = "select count(*) from exchange_finance_agent where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
//        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

//        $sql = "SELECT * from exchange_finance_agent
//        where {$where} order by id desc LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
//        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);
        $data = array();
        $page->setDataArray($data);
        $this->set('p', $page);
        
    }
    
    public function confirm_finance(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        
        echo json_encode(array('status'=>'success'));
        
    }
    
    public function get_agent_finance_message(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        
//        $clients =  $this->Client->query("select exchange_finance_agent_clients.*, order_user.name
//                from exchange_finance_agent_clients
//                left join order_user on order_user.client_id = exchange_finance_agent_clients.client_id
//                where exchange_finance_agent_id = {$id} ");
        $clients = array();
        echo json_encode($clients);
        
    }
    
    public function agent_set(){
        Configure::write('debug', 0);
        
        $this->pageTitle = 'Management/Agents Setting';
      
        
//        $sql = "select count(*) from exchange_agent_email_type ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
//        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

//        $sql = "SELECT * from exchange_agent_email_type order by id LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
//        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);
        $data = array();
        $page->setDataArray($data);
        $this->set('p', $page);
        
        
        
        
    }
    
     public function add_agent_set(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        
        
        $email_type = $_POST['email_type'];
        $email_action = $_POST['email_action'];
        
        $data = array('status'=>'');

       
       
//        $res = $this->Client->query("insert into exchange_agent_email_type (email_type,email_action)
//                            values
//                            ({$email_type},{$email_action})");
        //$this->Client->logging(0, 'Agent Setting', "Agent Name:{$agent_name}");
        //$this->Client->create_json_array('', 201, __('The action created successfully', true));
        $data['status'] = "success";
        
        
        echo json_encode($data);
    }
    
    public function del_plan($id){
//        $res = $this->Client->query("delete from exchange_par_plan where id = {$id} ");
        //$this->Client->create_json_array('', 201, __('The Plan is removed successfully', true));
        $this->redirect("/partition/par_plan/");
    }
    
    public function save_agent_set()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        
        $email_type = $_POST['email_type'];
        $email_action = $_POST['email_action'];
        
        
        
//        $this->Client->query("update exchange_agent_email_type set email_type = {$email_type},
//        email_action = {$email_action}
//        where id = {$id} ");
        //$this->Client->logging(2, 'Agent', "Agent Name:{$agent_name}");
        //$this->Client->create_json_array('', 201, __('The Agent [%s] is modified successfully!', true, $agent_name));
        $data['status'] = "success";
               
        
        echo json_encode($data);
    }
    
    
    public function admin_login()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $admin_login_key = time();
        
//        $res = $this->Client->query("select * from exchange_par_account  where id =  {$_GET['par_id']} ");
        
//        $domain_name = $res[0][0]['domain_name'];
        
//        $sql = "update exchange_par_account set admin_login_key ='" . md5($admin_login_key) . "' where id = " . $_GET['par_id'];
//        $this->Client->query($sql);
        $location = Configure::read('admin_login');
        
      
        
//        header('Location:http://' . $domain_name . '/'.$location.'?par_id=' . $_GET['par_id'] . '&login_key=' . $admin_login_key);
    }
    
    

}

?>
