
<?php

class ExchangeuserController extends AppController
{

    var $name = 'Exchangeuser';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $uses = array("prresource.Gatewaygroup","Agent", "Client", 'Clients', 'Credit', 'Orderuser');


    function index($type='agent')
    {
        $this->pageTitle = "Exchange Manage/Users";
        $type = empty($this->params['pass'][0]) ? 'agent' : $this->params['pass'][0];
        
        $this->set('type',$type);
        
        
        $field = "name";
        $table = "order_user";
        $table_id = 'client_id';
        if($type == 'agent'){
            $field = "name";
            $table = "agent_client";
            $table_id = 'client_id';
        }else if($type == 'exchange'){
            $field = "name";
            $table = "order_user";
            $table_id = 'id';
        }else if($type == 'partition'){
            $field = "username";
            $table = "exchange_par_account";
            $table_id = 'id';
        }
        
        $this->set('field',$field);
        
        $where = "1=1";
        if(!empty($_GET['search'])){
            $where .= " and {$field} ilike '%{$_GET['search']}%' ";
        }
        
        $sql = "select count(*) from {$table} where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

//        $sql = "SELECT {$table}.{$field},{$table}.last_login_time,{$table}.{$table_id} as client_id,
//        exchange_sys_role.role_name,exchange_sys_role.role_id from {$table}
//        left join exchange_sys_role on exchange_sys_role.role_id = {$table}.role_id
//        where {$where} order by {$field} LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
//        $data = $this->Client->query($sql);
        $data = array();
        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function get_roles(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $type = $_POST['type'];
        
        if($type == 'agent'){
            $type = 1;
        }else if($type == 'exchange'){
            $type = 0;
        }else if($type == 'partition'){
            $type = 2;
        }
        
//        $res = $this->Client->query("select * from exchange_sys_role  where type = {$type} order by role_name  ");
       $res = array();
        echo json_encode($res);
        
    }
    
    public function save_user(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        
        $id = $_POST['id'];
        $type = $_POST['type'];
        $role_id = empty($_POST['role_id'])?'null':$_POST['role_id'];
        
        $table = "order_user";
        $table_id = 'client_id';
        if($type == 'agent'){
            $table = "agent_client";
            $table_id = 'client_id';
        }else if($type == 'exchange'){
            $table = "order_user";
            $table_id = 'id';
        }else if($type == 'partition'){
            $table = "exchange_par_account";
            $table_id = 'id';
        }
        
        $this->Client->query("update {$table} set role_id = $role_id where {$table_id} = {$id} ");
        
        $data['status'] = "success";
        
        echo json_encode($data);
    }
    

}

?>
