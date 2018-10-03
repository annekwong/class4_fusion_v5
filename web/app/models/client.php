<?php

class Client extends AppModel
{

    var $name = 'Client';
    var $useTable = "client";
    var $primaryKey = "client_id";
    var $hasMany = array('Resource');

    CONST CLIENT_CLIENT_TYPE_INGRESS = 1;
    CONST CLIENT_CLIENT_TYPE_EGRESS = 2;

    var $default_schema = array(
        'name' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 40,),
        'mode' => array('type' => 'integer', 'null' => '', 'default' => 1, 'length' => '',),
        'orig_rate_table_id' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'term_rate_table_id' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'currency_id' => array('type' => 'integer', 'null' => '', 'default' => '', 'length' => '',),
        'allowed_credit' => array('type' => 'float', 'null' => '', 'default' => 0, 'length' => '',),
        'status' => array('type' => 'boolean', 'null' => '', 'default' => true, 'length' => '',),
        'auto_invoicing' => array('type' => 'boolean', 'null' => '', 'default' => true, 'length' => '',),
        'payment_term_id' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'invoice_format' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'attach_cdrs_list' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'cdr_list_format' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'last_invoiced' => array('type' => 'datetime', 'null' => 1, 'length' => '',),
        'notify_client_balance' => array('type' => 'float', 'null' => 1, 'default' => '', 'length' => '',),
//    'notify_admin_balance' => array  (   'type' => 'float',   'null' => 1,   'default' => '' ,   'length' => '' ,  ),
        'low_balance_notice' => array('type' => 'boolean', 'null' => '', 'default' => true, 'length' => '',),
        'zero_balance_notice' => array('type' => 'boolean', 'null' => '', 'default' => false, 'length' => '',),
        'zero_balance_notice_time' => array('type' => 'integer', 'null' => 0, 'default' => 0, 'length' => '',),
        'company' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 40,),
        'address' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'email' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'logo' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'login' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => 40,),
        'password' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => 50,),
        'is_panelaccess' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        /*'is_client_info' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_invoices' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_rateslist' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_summaryreport' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_cdrslist' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_mutualsettlements' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),
        'is_changepassword' => array('type' => 'boolean', 'null' => 1, 'default' => '', 'length' => '',),*/
        'role_id' => array('type' => 'integer', 'null' => '', 'default' => 2, 'length' => '',),
        'create_time' => array('type' => 'datetime', 'null' => '', 'length' => '',),
        'profit_margin' => array('type' => 'float', 'null' => 1, 'default' => '', 'length' => '',),
        'enough_balance' => array('type' => 'boolean', 'null' => 1, 'default' => false, 'length' => '',),
        'service_charge_id' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'noc_email' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'billing_email' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'rate_email' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'tax_id' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100,),
        'details' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 1000,),
        'invoice_show_details' => array('type' => 'boolean', 'null' => 1, 'default' => false, 'length' => '',),
        'invoice_past_amount' => array('type' => 'float', 'null' => 1, 'default' => 0, 'length' => '',),
        'is_link_cdr' => array('type' => 'boolean', 'null' => 1, 'default' => true, 'length' => '',),
        'is_daily_balance_notification' => array('type' => 'boolean', 'null' => 1, 'default' => false, 'length' => '',),
        'daily_balance_notification' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'daily_balance_recipient' => array('type' => 'integer', 'null' => 1, 'default' => '', 'length' => '',),
        'is_auto_balance' => array('type' => 'boolean', 'null' => 1, 'default' => false, 'length' => '',),
        'numer_of_days_balance' => array('type' => 'integer', 'null' => 1, 'default' => 1, 'length' => '',),
        'update_at' => array('type' => 'datetime', 'null' => '', 'length' => '',),
        'update_by' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => 40,),
    );
    var $xvalidatevar = Array(
        'name' => Array(
            'noEmpty' => 'Name cannot be NULL!',
            'length' => Array('length' => 16, 'message' => 'Name Prefix digits can not exceed 16 characters!'),
            'unique' => 'Name is unique!'
        ),
        'login' => Array(
            'noEmpty' => 'Login cannot be Null!',
            'en' => 'Login must contain alphanumeric characters only!',
            'length' => Array('length' => 16, 'message' => 'Login Prefix digits can not exceed 16 characters!'),
            'unique' => 'Login is unique!'
        ),
        'password' => Array(
            'noEmpty' => 'Password cannot be Null!',
            'en' => 'password must contain alphanumeric characters only!'
        ),
        'email' => Array(
            'email' => 'Email Emails must be a valid format.  The following Emails are not valid'
        ),
        'nocemail' => Array(
            'email' => 'Nocemail Emails must be a valid format.  The following Emails are not valid'
        ),
        'billingemail' => Array(
            'email' => 'Nocemail Emails must be a valid format.  The following Emails are not valid'
        ),
        'rateemail' => Array(
            'email' => 'Nocemail Emails must be a valid format.  The following Emails are not valid'
        )
    );

    function get_client_all_email($client_id)
    {
        $email = array();
        $client_name = '';
        $list = $this->query("select name,  email ,billing_email,rate_email,noc_email   from  client   where  client_id={$client_id}");
        if (isset($list[0][0]))
        {
            if (!empty($list[0][0]['name']))
            {
                $client_name = $list[0][0]['name'];
            }
            if (!empty($list[0][0]['email']))
            {
                array_push($email, $list[0][0]['email']);
            }
            if (!empty($list[0][0]['billing_email']))
            {
                array_push($email, $list[0][0]['billing_email']);
            }
            if (!empty($list[0][0]['rate_email']))
            {
                array_push($email, $list[0][0]['rate_email']);
            }
            if (!empty($list[0][0]['noc_email']))
            {
                array_push($email, $list[0][0]['noc_email']);
            }
        }

        return compact('client_name', 'email');
    }

    function find_all_valid()
    {
        return $this->find('all', array('conditions' => 'status=true'));
    }

    function get_digit_mappings()
    {
        $sql = "select translation_id,translation_name from digit_translation order by translation_name";
        $result = $this->query($sql);
        return $result;
    }

    function get_digit_mapping($resource_id)
    {
        $sql = "select translation_id from resource_translation_ref where resource_id = $resource_id limit 1";
        $result = $this->query($sql);
        if (!empty($result))
        {
            return $result[0][0]['translation_id'];
        }
        else
        {
            return null;
        }
    }

    //落地网关
    public function findAll_egress($client_id)
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3)
        {
            $privilege = "  and(resource.client_id={$_SESSION['sst_client_id']}) ";
        }

//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (resource.name like '%{$_GET['search']}%'  or  resource.client_id::text like '%{$_GET['search']}%' 
	  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  like '%{$_GET['search']}%' )
    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  like '%{$_GET['search']}%' ) or client.company like %{$_GET['search']}%
	    or  alias like '%{$_GET['search']}%')" : '';
        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (resource.client_id::integer={$_GET ['query'] ['id_clients']})" : '';
        $gate_client_where = !empty($client_id) ? "  and (resource.client_id::integer={$client_id})" : '';
        $totalrecords = $this->query("select count(resource_id) as c from resource where egress=true 
	  $like_where  $name_where    $client_where   $gate_client_where $id_where  $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $sql = "select resource.alias,resource.resource_id , resource.name ,resource.cps_limit,capacity,ingress,egress,active, a.ip_cnt , resource.client_id
    ,client.name as client_name	from  resource
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
		 where egress=true  
		$like_where  $name_where    $client_where  $gate_client_where $id_where   $privilege  ";
        $sql .= " limit '$pageSize' offset '$currPage'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function getclients_count($sst_user_id, $where)
    {
        $sql = "SELECT 
count(*)
FROM client
WHERE (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true))) {$where}";

        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    public function getclients($sst_user_id, $order_by, $where, $pageSize, $offset)
    {
        $sql = <<<EOT
SELECT client.client_id, client.name, client.company,client.is_panelaccess,
(select last_login_time from order_user where client_id = client.client_id) as last_login_time, 
client.status,client.mode,client.unlimited_credit,
client.allowed_credit, c4_client_balance.ingress_balance, c4_client_balance.egress_balance, c4_client_balance.balance,
(SELECT count(*) FROM resource WHERE client_id = client.client_id AND egress = TRUE) as egress_count, 
(SELECT count(*) FROM resource WHERE client_id = client.client_id AND ingress = TRUE) as ingress_count,
(SELECT group_name FROM carrier_group WHERE group_id = client.group_id) as group_name,group_id,
client.update_at, client.update_by FROM client 
left join c4_client_balance on client.client_id = c4_client_balance.client_id::integer
WHERE (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
{$where}
$order_by LIMIT {$pageSize} OFFSET {$offset}
EOT;
        return $this->query($sql);
    }

    public function getclients2($sst_user_id, $where, $pageSize, $offset)
    {
        $sql = <<<EOT
SELECT client.client_id, client.name, 
allowed_credit,cps_limit,call_limit,status,
update_at, update_by
,is_daily_balance_notification,notify_client_balance,notify_client_balance_type,client.mode,unlimited_credit,allowed_credit
,(select payment_time FROM client_payment where client_payment.client_id = client.client_id order by payment_time desc limit 1)
,(select amount FROM client_payment where client_payment.client_id = client.client_id order by payment_time desc limit 1)
,(case when client.mode = '1' then 'Prepaid' when client.mode = '2'  then 'Postpaid' else 'None' end) as client_terms 
,payment_term.name as payment_term_name
FROM client
LEFT JOIN payment_term ON payment_term.payment_term_id = client.payment_term_id

WHERE (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id)
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
{$where}
 LIMIT {$pageSize} OFFSET {$offset}
EOT;
        return $this->query($sql);
    }

    public function get_clients_invoice_history($sst_user_id, $order_by, $where, $pageSize, $offset){
        $sql = <<<EOT
        SELECT client.client_id, client.name,
        (SELECT name from payment_term where client.payment_term_id = payment_term.payment_term_id) as payment_term,
        (SELECT amount from client_payment where client.client_id = client_payment.client_id order by client_payment.payment_time desc limit 1) as last_invoice_amount,
        (SELECT invoice_time from invoice where client.client_id = invoice.client_id  order by invoice_time desc limit 1) as last_invoice_for,
        (SELECT invoice_start from invoice where client.client_id = invoice.client_id order by invoice_time desc limit 1) as invoice_start,
        (SELECT invoice_end from invoice where client.client_id = invoice.client_id order by invoice_time desc limit 1) as invoice_end,
        (SELECT invoice_number from invoice where client.client_id = invoice.client_id order by invoice_time desc limit 1) as invoice_number,
        (SELECT create_type from invoice where client.client_id = invoice.client_id  order by invoice_time desc limit 1) as type
        from client
        WHERE 
        exists(SELECT invoice_time FROM invoice WHERE client_id=client.client_id) AND (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
        OR 
        exists 
        (SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
        or sys_role.view_all = true)))
        {$where}
        group by client.client_id
        {$order_by}
        LIMIT {$pageSize} OFFSET {$offset}
EOT;
        return $this->query($sql);
    }

    public function get_balance($client_id)
    {
        $sql = "select * from current_balance({$client_id}) as (actual_ingress_balance numeric,actual_egress_balance numeric,
actual_total_balance numeric,mutual_ingress_balance numeric, mutual_egress_balance numeric, mutual_total_balance numeric)";
        $data = $this->query($sql);
        return $data[0][0];
    }

    public function findAll_ingress($client_id)
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3)
        {
            $privilege = "  and(resource.client_id={$_SESSION['sst_client_id']}) ";
        }
        //充值结果 
//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (resource.name like '%{$_GET['search']}%' or  resource.client_id::text like '%{$_GET['search']}%' 
	  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  like '%{$_GET['search']}%' )
    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  like '%{$_GET['search']}%' )
	    or  alias like '%{$_GET['search']}%')" : '';

        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (resource.client_id::integer={$_GET ['query'] ['id_clients']})" : '';
        $gate_client_where = !empty($client_id) ? "  and (resource.client_id::integer={$client_id})" : '';
        $totalrecords = $this->query("select count(resource_id) as c from resource where ingress=true 
	  $like_where  $name_where    $client_where  $gate_client_where $id_where   $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $sql = "select resource.alias,resource.resource_id , resource.name ,resource.cps_limit,capacity,ingress,egress,active, a.ip_cnt , resource.client_id
    ,client.name as client_name,(select rate_table_id from resource_prefix where resource_id = resource.resource_id limit 1) as rate_table_id	from  resource
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    left  join client   on client.client_id=resource.client_id
		 where ingress=true  
		$like_where  $name_where    $client_where $gate_client_where  $id_where   $privilege  ";
        $sql .= "      	limit '$pageSize' offset '$currPage'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 验证客户信息
     * @return true 有错误信息
     * false 没有错误信息
     */
    function validate_client($data, $post_arr, $mass_edit = false)
    {

        //	return $this->xvalidated($data['Client']);
        $error_flag = false; //错误信息标志
        $client_id = $this->getkeyByPOST('client_id', $post_arr);
        $name = $data ['Client'] ['name'];
        $email = $data ['Client'] ['email'];
        $nocemail = $data['Client']['noc_email'];
        $billingemail = $data['Client']['billing_email'];
        $rateemail = $data['Client']['rate_email'];
        $company = $data ['Client'] ['company'];
        $allowed_credit = $data ['Client'] ['allowed_credit']; //容许欠费
        //$profit_margin = $data ['Client'] ['profit_margin'];
        $login = isset($data ['Client'] ['login']) ? $data ['Client'] ['login'] : "";
//		$notify_admin_balance = $data ['Client'] ['notify_admin_balance'];
        $notify_client_balance = isset($data ['Client'] ['notify_client_balance']) ? $data ['Client'] ['notify_client_balance'] : "";
        $service_id = isset($data ['Client'] ['service_charge_id']) ? $data ['Client'] ['service_charge_id'] : "";

        if (!empty($email))
        {

            if (!preg_match("/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/", $email))
            {
                return $this->create_json(101, __('pleaseinputemail', true));
            }
        }
        if (!empty($nocemail))
        {
            if (!preg_match("/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/", $nocemail))
            {
                return $this->create_json(101, __('nocpleaseinputemail', true));
            }
        }
        if (!empty($billingemail))
        {
            if (!preg_match("/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/", $billingemail))
            {
                return $this->create_json(101, __('billingpleaseinputemail', true));
            }
        }
        if (!empty($rateemail))
        {
            if (!preg_match("/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/", $rateemail))
            {
                return $this->create_json(101, __('ratepleaseinputemail', true));
            }
        }
        $login = trim($login);
        if (!empty($login))
        {
            if (!preg_match('/^\w+$/', $login))
            {
                return $this->create_json(101, 'Please fill Login field correctly (only latin characters and digits allowed).');
            }
            $c = $this->check_login($client_id, $login);
            if ($c != 0 && !$q)
            {
                return $this->create_json(101, __('usernameexist', true));
            }
        }
        if (!empty($allowed_credit))
        {
            if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $allowed_credit))
            {
                return $this->create_json( 101, 'Please fill Allowed Credit field correctly (only  digits allowed).');
            }
        }
        /*
          if (!empty($profit_margin)) {
          if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $profit_margin)) {
          $this->create_json_array('#ClientProfitMargin', 101, 'Please fill Min. Profitability field correctly (only  digits allowed).');
          $error_flag = true;
          }
          }
         * 
         */
//		if (  !empty($notify_admin_balance)) {
//			if(!preg_match('/^[+\-]?\d+(.\d+)?$/',$notify_admin_balance)){
//					$this->create_json_array ('#ClientNotifyAdminBalance', 101, 'Please fill Notify admin field correctly (only  digits allowed).');
//					$error_flag = true;
//			}
//		}
        if (!empty($notify_client_balance))
        {
            if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $notify_client_balance))
            {
                return $this->create_json(101, 'Please fill Notify client: field correctly (only  digits allowed).');
            }
        }
        /* 		if (empty ( $orig_rate_table_id )) {
          $this->create_json_array ( '#ClientOrigRateTableId', 101, __ ( 'selectratetable', true ) );
          $error_flag = true;
          } */
        /*
          if (empty ( $service_id )) {
          $this->create_json_array ( '#ClientServiceChargeId', 101, 'Please fill Service Charge field' );
          $error_flag = true;
          } */
        $c = $this->check_name($client_id, $name);
        if ($c != 0 && !$mass_edit)
        {
            return $this->create_json(101, __('checkclientname', true));
        }
        /*
          $valie_data=$this->query("select * from client where name='".$data['Client']['name']."' and client_id <> {$client_id}");
          if(!empty($valie_data)){

          $this->create_json_array ( '#ClientName', 101, 'Client Name Exists' );
          $error_flag = true;
          }
         * 
         */
        return false;
    }

    function check_login($client_id, $name)
    {

        $name = "'" . $name . "'";
        empty($client_id) ? $sql = "select count(*) from client where login=$name " : $sql = "select count(*) from client where login=$name  and client_id<>$client_id";
        $c = $this->query($sql);
        if (empty($c))
        {
            return 0;
        }
        else
        {
            return $c [0] [0] ['count'];
        }
    }

    /**
     * 验证客户名字不能重复
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_name($client_id, $name)
    {
        $name = "'" . $name . "'";
        empty($client_id) ? $sql = "select count(*) from client where name=$name " : $sql = "select count(*) from client where name=$name  and client_id<>$client_id";
        $c = $this->query($sql);
        if (empty($c))
        {
            return 0;
        }
        else
        {
            return $c [0] [0] ['count'];
        }
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     * @return
     */
    function saveOrUpdate($data, $post_arr, $mass_edit = false, $edit = false, $isApproval = false)
    {

        $msgs = FALSE;

        $msgs = $this->validate_client($data, $post_arr, $mass_edit || $edit); //验证客户信息
        if ($msgs)
        {
            return $msgs; //add fail
        }
        else
        {
            $return = $this->saveOrUpdate_client($data, $post_arr, $mass_edit, $edit, $isApproval); //添加或者更新
            return $return; //add succ
        }
    }

    /**
     * 添加Client or 更新Client从order_user表
     * @param unknown_type $data
     * @param unknown_type $post_arr
     * @return
     */
    function saveOrUpdate_orderuser($data, $post_arr)
    {
        $return = array('client_id' => '', 'log_id' => '');
        if (!empty($post_arr['order_user_id']))
        {
            $order_user_info = $this->query("select * from order_user where id = " . intval($post_arr['order_user_id']));
            $data['Client']['login'] = $order_user_info[0][0]['name'];
            $data['Client']['password'] = $order_user_info[0][0]['password'];
        }
        else
        {
            return $return;
        }
        $msgs = $this->validate_client($data, $post_arr); //验证客户信息
        $msgs = 'true';
        if (!$msgs)
        {
            return $return; //add fail
        }
        else
        {
            $this->begin();
            $client_id = $this->saveOrUpdate_client($data, $post_arr); //添加或者更新
            if ($client_id)
            {
                $this->query("update client set user_id = " . intval($post_arr['order_user_id']) . " where client_id = " . $client_id);
                $this->query("update order_user set password = md5(password), status = 3, client_id = " . $client_id . " where id = " . intval($post_arr['order_user_id']));
                //payment credit
                $this->query("insert into client_payment (payment_time, amount, result, client_id, approved, current_balance, payment_type, email_sended) values (now(), 1, 't', {$client_id}, 't', 1, 2, 't') ");
                $this->clientBalanceOperation($client_id, 1, 1);

                $return['client_id'] = $client_id;
                //$this->query("insert into client_balance (balance, client_id) values ('1', '{$client_id}')");
            }
            else
            {
                $this->create_json_array('#ClientLogin', 301, __('Create Carrier Fail', true));
                return $return;
            }

            $this->commit();
            return $return; //add succ
        }
    }


    function add_test_credit($client_id,$test_credit)
    {
        $update_by = $_SESSION['sst_user_name'];
        $sql = <<<SQL
INSERT INTO client_payment(client_id, payment_type, amount,payment_time, result, receiving_time, description,update_by)
VALUES ($client_id,5,$test_credit,current_timestamp(0), TRUE, current_timestamp(0), 'test credit', '$update_by');
SQL;
        $this->query($sql);

        $this->clientBalanceOperation($client_id, $test_credit, 2);
    }
    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     */
    function saveOrUpdate_client($data, $post_arr, $mass_edit = false, $edit = false, $isApproval = false)
    {
        if ($mass_edit) {
            foreach ($post_arr['Client'] as $key => $value) {
                if ($key != 'client_id' && $key != 'name') {
                    if (!empty($value)) {
                        $data['Client'][$key] = $value;
                    }
                }
            }
        }

        //$data['Client'] ['profit_margin'] = $data ['Client'] ['profit_margin'] ? $data ['Client'] ['profit_margin'] : 0;
        $data ['Client'] ['create_time'] = date("Y-m-d   H:i:s"); //加密.
        if ($data['Client']['is_panelaccess'] == 0) {
            unset($data['Client']['login']);
            unset($data['Client']['password']);
        } else {
            $data['Client']['password'] = $isApproval == false ? md5($data['Client']['password']) : $data['Client']['password'];
        }
        $data['Client'] ['low_balance_number'] = isset($data['Client'] ['daily_balance_notification']) ? $data['Client'] ['daily_balance_notification'] : 0;
        $client_id = array_keys_value($post_arr, 'client_id', '');
        if (empty($client_id) && isset($data['Client']['client_id'])) {
            $client_id = $data['Client']['client_id'];
        }
        //$field_list = array_keys($data['Client']);
        $unlimited_credit = $data['Client']['unlimited_credit'] == "0" ? false : true;

        $data ['Client']['usage_detail_fields'] = isset($data['Client']['usage_detail_fields']) ? implode(',', $data['Client']['usage_detail_fields']) : '';
        $data ['Client']['enough_balance'] = "true";

        if (!empty($client_id))
        {
            $this->begin();
            $data['Client'] ['client_id'] = $client_id;
            $data['Client']['update_at'] = date("Y-m-d H:i:s");
            $data['Client']['update_by'] = $_SESSION['sst_user_name'];
            //rollback start
            $old_data = $this->find('first', array('client_id' => $client_id));

            $orig_data = $this->findByClientId($client_id);
            if ($data['Client']['allowed_credit'] != $orig_data['Client']['allowed_credit'] || $data['Client']['unlimited_credit'] != $orig_data['Client']['unlimited_credit'])
            {
                $modified_from = $orig_data['Client']['unlimited_credit'] ? 'NULL' : $orig_data['Client']['allowed_credit'] ? $orig_data['Client']['allowed_credit'] : 'NULL';
                $modified_to= $data['Client']['unlimited_credit']? 'NULL':$data['Client']['allowed_credit'];
                $sql = "insert into credit_log(modified_by, modified_from, modified_to, modified_on, carrier_name) VALUES ('{$_SESSION['sst_user_name']}', $modified_from, $modified_to, CURRENT_TIMESTAMP(0), '{$data['Client']['name']}')";
                $this->query($sql);
            }

            $rollback_data = array();

            $int_arr = array(
                'mode', 'orig_rate_table_id', 'term_rate_table_id', 'currency_id',
                'allowed_credit', 'payment_term_id', 'invoice_format', 'cdr_list_format'
            , 'notify_client_balance', 'notify_admin_balance', 'role_id', 'profit_margin'
            , 'service_charge_id', 'invoice_past_amount', 'mail_sended', 'scc_bellow'
            , 'scc_percent', 'scc_charge', 'user_id', 'profit_type', 'auto_invoice_type'
            , 'cps_limit', 'call_limit', 'daily_balance_notification', 'daily_balance_recipient'
            , 'low_balance_number', 'numer_of_days_balance', 'auto_daily_balance_recipient'
            , 'daily_cdr_generation_type', 'transaction_fee_id', 'include_payment_history'
            , 'include_available_credit', 'include_payment_history_days', 'auto_summary_not_zero'
            , 'auto_summary_group_by', 'tax', 'decimal_place', 'par_id', 'rate_value'
            , 'scc_type', 'auto_summary_hour', 'auto_summary_period', 'client_type'
            , 'invoice_use_balance_type', 'notify_client_balance_type'
            , 'decimals_num', 'low_balance_notification_time_type', 'low_balance_notification_time_cycle'
            , 'breakdown_by_rate_table','hourly_limit','daily_limit'
            , 'zero_balance_notice','zero_balance_notice_time'
            );

            $skippedFields = array('client_id', 'name');

            foreach ($data['Client'] as $key => $value)
            {
                if ($old_data['Client'][$key] != $value && !empty($old_data['Client'][$key]) && !in_array($key, $skippedFields))
                {
                    if (!in_array($key, $int_arr))
                    {
                        $rollback_data[] = $key . " = '" . $old_data['Client'][$key] . "'";
                    }
                    else
                    {
                        $rollValue = empty($old_data['Client'][$key]) ? 0 : $old_data['Client'][$key];
                        $rollback_data[] = $key . " = " . $rollValue;
                    }
                }
            }
            $rollback_update_sql = implode(',', $rollback_data);
            if($rollback_update_sql && $client_id){
                $rollback_sql = "UPDATE client SET {$rollback_update_sql} WHERE client_id = {$client_id};";
            }
            //rollback end
            //echo '<pre>';var_dump($data['Client']);die;
//            echo '<pre>';
//            die(var_dump($data['Client']));

            if ($this->save($data['Client']))
            {
                /*
                  if (isset($data ['Client'] ['profit_margin'])) {
                  $this->query("update resource  set  profit_margin={$data ['Client'] ['profit_margin']}  where  client_id=$client_id");
                  }
                 */
                if ($data['Client']['status'] == 'false')
                {
                    $disabled_trunk_sql = "UPDATE resource set active = false WHERE client_id = $client_id";
                    $this->query($disabled_trunk_sql);
                } else {
                    $active_trunk_sql = "UPDATE resource set active = true WHERE client_id = $client_id";
                    $this->query($active_trunk_sql);
                }
                $list = $this->query("select balance from c4_client_balance where client_id='$client_id';");
                if (isset($list[0][0]['balance'])) {
                    $balance = $list[0][0]['balance'];
                    $allowed_credit = $data['Client']['allowed_credit'];
                    if (!$unlimited_credit) {
                        if ($balance > $allowed_credit) {
                            $this->query("update client  set  enough_balance=true  where  client_id=$client_id");
                            $this->query("update resource   set  enough_balance=true  where  client_id=$client_id");
                        } else {
                            $this->query("update client  set  enough_balance=false  where  client_id=$client_id");
                            $this->query("update resource   set  enough_balance=false  where  client_id=$client_id");
                        }
                    } else {
                        $this->query("update resource   set  enough_balance=true  where  client_id=$client_id");
                        $this->query("update client  set  enough_balance=true  where  client_id=$client_id");
                    }

                    if (!empty($old_data['Client']['enough_balance'])) {
                        $old_data['Client']['enough_balance'] = $old_data['Client']['enough_balance'] ? 'true' : 'false';
                        $rollback_sql .= "UPDATE client SET enough_balance = {$old_data['Client']['enough_balance']} WHERE client_id = {$client_id};";
                        $rollback_sql .= "UPDATE resource SET enough_balance = {$old_data['Client']['enough_balance']} WHERE client_id = {$client_id};";
                    }

                    if (isset($data['Client']['scc_charge']) && !empty($data['Client']['scc_charge']) && !empty($old_data['Client']['scc_charge'])) {
                        $this->query("update client set scc_charge = {$data['Client']['scc_charge']} where client_id={$client_id}");
                        $rollback_sql .= "UPDATE client SET scc_charge = {$old_data['Client']['scc_charge']} WHERE client_id = {$client_id};";
                    }
                }

                $this->commit();
                if ($data['Client']['is_panelaccess'] == 1 && empty($post_arr['order_user_id']))
                {
                    $login = $data ['Client'] ['login'];
                    $password = $data ['Client'] ['password'];
                    $date = date("Y-m-d   H:i:s");
                    $user_id = $this->query("select client.user_id from users join client on users.user_id = client.user_id where client.client_id = {$client_id}");
                    if (isset($user_id[0][0]['user_id']))
                    {
                        $this->query("update users set  name = '{$login}' , password = '{$password}' where user_id = {$user_id[0][0]['user_id']} ");

                        if ($old_data['Client']['login'] && $old_data['Client']['password']) {
                            $rollback_sql .= "UPDATE users SET scc_charge = {$old_data['Client']['login']},password = {$old_data['Client']['password']} WHERE user_id = {$user_id[0][0]['user_id']};";
                        }
                   }
                    else
                    {
                        $list = $this->query("select count(*)  from  users  where name='$login'");

                        if (empty($list[0][0]['count']) || $list[0][0]['count'] == 0)
                        {
                            $user_id_results = $this->query("insert into users(name,password,client_id,create_time,user_type)values('$login','$password',$client_id,'$date',3) RETURNING user_id");;
                            $this->query("update client set user_id = {$user_id_results[0][0]['user_id']} where client_id = {$client_id}");

                            if (!empty($old_data['Client']['user_id'])) {
                                $rollback_sql .= "UPDATE client SET user_id = {$old_data['Client']['user_id']} WHERE client_id = {$client_id};";
                            }
                        }
                        else
                        {
                            if (!$edit) {
                                $this->create_json_array('#ClientLogin', 301, __('username exist', true));
                            }

                            return false;
                        }
                    }
                }
                $rollback_msg = "Modify  Carrier [" . $data['Client']['name'] . "] operation have been rolled back!";
                $log_id = $this->logging(2, 'Carrier', "Carrier's name:" . $data['Client']['name'],$rollback_sql,$rollback_msg);
            }
        }
        else
        {

            //---------------处理name的unique问题
            $client_info = $this->query("select client_id from client where name = '" . addslashes($data['Client']['name']) . "'");
            if (empty($client_info))
            {
                $this->begin();
                //$data ['Client'] ['client_id'] = $client_info[0][0]['client_id'];	
                $data['Client']['enough_balance'] =  "true";
                $data['Client']['update_at'] = date("Y-m-d H:i:s");
                $data['Client']['update_by'] = $_SESSION['sst_user_name'];
                if ($data['Client']['mode'] == 1)
                {
                    $test_credit = 0 - $data['Client']['allowed_credit'];
                    $data['Client']['allowed_credit'] = 0;
                }
                $res = $this->save($data['Client']);

                $client_id = $this->getlastinsertId();
                if (!empty($client_id))
                {
                    $userid = $_SESSION['sst_user_id'];
                    $rollback_sql = "DELETE FROM users_limit WHERE client_id = {$client_id} AND user_id = {$userid};";
                    $rollback_sql .= "DELETE FROM client WHERE client_id = {$client_id};";

                    $this->clientBalanceOperation($client_id, 0, 0);

                    if(isset($test_credit) && $test_credit)
                        $this->add_test_credit($client_id,$test_credit);
                    $this->query("INSERT INTO users_limit (user_id, client_id) VALUES ({$userid},{$client_id})");
                    $this->commit();
                    if ($data['Client']['is_panelaccess'] == 1 && !empty($data ['Client'] ['login']) && empty($post_arr['order_user_id']))
                    {
                        $login = $data ['Client'] ['login'];
                        $password = $data ['Client'] ['password'];
                        $date = date("Y-m-d   H:i:s");
                        $list = $this->query("select count(*)  from  users  where name='$login'");
                        if (empty($list[0][0]['count']) || $list[0][0]['count'] == 0) {
                            $user_id_results = $this->query("insert into users(name,password,client_id,create_time,user_type)values('$login','$password',$client_id,'$date',3) RETURNING user_id");
                            $rollback_sql .= "DELETE FROM users WHERE user_id = {$user_id_results[0][0]['user_id']};";

                            if (!empty($user_id_results[0][0]['user_id'])) {
                                $this->query("update client set user_id = {$user_id_results[0][0]['user_id']} where client_id = {$client_id}");
                            }
                            $this->commit();
                        }
                        else
                        {
                            if (!$edit) {
                                $this->create_json_array('#ClientLogin', 301, __('username exist', true));
                            }
                            $this->rollback();
                            return false;
                        }
                    }
                    $rollback_msg = "Create  Carrier [" . $data['Client']['name'] . "] operation have been rolled back!";
                    $log_id = $this->logging(0, 'Carrier', "Carrier's name:" . $data['Client']['name'], $rollback_sql, $rollback_msg);
                }
            }
            //--------------------------
            //App::import('Vendor', 'logging');
            //Logging::log($_SESSION['sst_user_id'], "Carriers", "add client({$client_id})",$this);	
        }
       
        $data['Client']['daily_cdr_generation'] = $data['Client']['daily_cdr_generation'] === 'false' ?  : 'true';
        $data['Client']['is_daily_balance_notification'] = $data['Client']['is_daily_balance_notification'] === '1' ? 'true' : 'false';
        $data['Client']['low_balance_notice'] = $data['Client']['low_balance_notice'] === '1' ? 'true' : 'false';
        $data['Client']['zero_balance_notice'] = $data['Client']['zero_balance_notice'] === '1' ? 'true' : 'false';
        $data['Client']['is_auto_summary'] = $data['Client']['is_auto_summary'] === '1' ? 'true' : 'false';
        $data['Client']['daily_balance_recipient'] = intval($data['Client']['daily_balance_recipient']);
        $data['Client']['zero_balance_notice_time'] = $data['Client']['zero_balance_notice_time'] ? $data['Client']['zero_balance_notice_time'] : 0;
        $notify_client_balance = empty($data['Client']['notify_client_balance']) ? 'NULL' : $data['Client']['notify_client_balance'];
        $daily_balance_notification = empty($data['Client']['daily_balance_notification']) ? 'NULL' : $data['Client']['daily_balance_notification'];
        $low_balance_number = empty($data['Client']['low_balance_number']) ? 'NULL' : $data['Client']['low_balance_number'];
        $zero_balance_notice_time = empty($data['Client']['zero_balance_notice_time']) ? 0 : $data['Client']['zero_balance_notice_time'];
//        $enable_payment_alert = isset($data['Client']['enable_payment_alert']) ? $data['Client']['enable_payment_alert'] : 'NULL';

        $this->query("UPDATE client SET is_daily_balance_notification = {$data['Client']['is_daily_balance_notification']}, 
                                low_balance_notice = {$data['Client']['low_balance_notice']}, notify_client_balance = {$notify_client_balance},
                                zero_balance_notice = {$data['Client']['zero_balance_notice']},
                                zero_balance_notice_time = {$zero_balance_notice_time},
                                daily_balance_notification = {$daily_balance_notification}, 
                                daily_balance_recipient = {$data['Client']['daily_balance_recipient']}, is_auto_summary = {$data['Client']['is_auto_summary']},
                                auto_send_zone = '{$data['Client']['auto_send_zone']}', low_balance_number = {$low_balance_number} WHERE client_id = {$client_id}");

        $return['client_id'] = $client_id;
        $return['log_id'] = $log_id;
        return $return;
    }

    function findAllRate()
    {
        $r = $this->query("select  rate.rate_id ,  rate_table.name from rate,rate_table where rate.rate_table_id=rate_table.rate_table_id ORDER BY name ASC ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['rate_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 查询静态路由表
     */
    function findAllProduct()
    {
        $r = $this->query("select product_id ,name from product ORDER by name ASC ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['product_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 查询静态路由表
     */
    function findAllProducts()
    {
        return $this->query("select product_id ,name from product ORDER BY name ASC ");
    }

    /**
     * 查询dyn_route
     */
    function findDyn_route()
    {
        $r = $this->query("select dynamic_route_id,name from dynamic_route order by name asc ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['dynamic_route_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 查询dyn_route
     */
    function findDyn_routes()
    {
        return $this->query("select dynamic_route_id,name from dynamic_route ORDER by name ASC ");
    }

    /**
     * 查询paymentTrem
     */
    function findPaymentTerm()
    {
        $r = $this->query("select payment_term_id,name from payment_term ORDER by name ASC ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['payment_term_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 查询mail_sender
     */

    function findsendemailTerm()
    {
        $r = $this->query("select id,name from mail_sender  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 查询Reseller
     */
    function findReseller()
    {
        $sql = "select reseller.reseller_id ,reseller.name from reseller";
        if (!empty($_SESSION ['sst_reseller_id']))
        {
            $reseller_id = $_SESSION ['sst_reseller_id'];
            $sql .= " where reseller_id = '$reseller_id' or parent = '$reseller_id'";
        }

        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['reseller_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }

    /**
     * 查询rate_table
     */
    function findRateTable()
    {
        $sql = "select rate_table_id ,name from rate_table";
        if (!empty($_SESSION ['sst_reseller_id']))
        {
            $reseller_id = $_SESSION ['sst_reseller_id'];
            $sql .= " where reseller_id = '$reseller_id' ";
        }
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['rate_table_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    function findRates()
    {
        $sql = "select rate_table_id ,name from rate_table";

        $r = $this->query($sql);
        return $r;
    }

    /**
     * 查询currency
     */
    function findCurrency()
    {

        $sql = "select currency_id ,code from currency where active=true ORDER by code ASC ";
        if (!empty($_SESSION ['sst_reseller_id']))
        {
            $reseller_id = $_SESSION ['sst_reseller_id'];
            $sql .= " and reseller_id = '$reseller_id' ";
        }

        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['currency_id'];
            $l [$key] = $r [$i] [0] ['code'];
        }

        return $l;
    }

    function findTransFees()
    {

        $sql = "select id ,name from transaction_fee order by name asc";
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        array_unshift($l, array(
            '' => '',
        ));
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    function findservice_charge()
    {

        $sql = "select service_charge_id ,name from service_charge ";
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['service_charge_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }

    /**
     * 删除
     * @param unknown_type $id
     */
    function del($id, $del_cdr = false)
    {

        $this->begin();
        $sql = "SELECT name FROM client WHERE client_id = {$id}";
        $data = $this->query($sql);
        $log_id = $this->logging(1, 'Carrier', "Carrier's name:" . $data[0][0]['name']);

        $is_rollback = false;
        $qs = $this->query("delete from users where client_id=$id");
        if($qs === false)
        {
            $this->rollback();
            return false;
        }
        $qs = $this->query("delete  from client where client_id=$id");
        if($qs === false)
        {
            $this->rollback();
            return false;
        }

        if ($del_cdr == 'true') {
            $qs = $this->query("delete from resource where client_id=$id");
            if($qs === false)
            {
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
        //App::import('Vendor', 'Logging');
        //Logging::log($_SESSION['sst_user_id'], "Carriers", "Delete client({$id})", $this);
    }

    /**
     * 禁用一个client
     * @param unknown_type $id
     */
    function dis_able($id)
    {
        $this->begin();
        $re = true;
        if (!$this->query("update client  set   status= false  where client_id=$id"))
        {
            $re = false;
        }
        if (!$this->query("update users  set   active= false  where client_id=$id  and user_type=3"))
        {
            $re = false;
        }
        if ($this->query("update resource  set   active= false  where client_id=$id"))
        {
            $re = false;
        }
        if ($re)
        {
            $this->commit();
        }
        else
        {
            $this->rollback();
        }
        return $re;
    }

    //激活一个客户
    function active($id)
    {
        try
        {
            $this->begin();
            $this->x_query("update users  set   active= true  where client_id=$id  and user_type=3");
            $this->x_query("update resource  set   active= true  where client_id=$id");
            $this->x_query("update client  set   status= true  where client_id=$id");
            $this->commit();
            return true;
        }
        catch (Exception $e)
        {
            $this->rollback();
            return false;
        }
    }

    /**
     * 查询客户
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAll($order = null)
    {
        pr('hhhhhhhhhh');
        if (empty($order))
        {
            $order = "client_id  desc";
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3)
        {
            $privilege = "  and(client_id::integer={$_SESSION['sst_client_id']}) ";
        }
        $like_where = !empty($_GET['search']) ? " and (client.name like '%{$_GET['search']}%'  or  client_id::text like '%{$_GET['search']}%'   or login like '%{$_GET['search']}%'  )" : '';
        $name_where = !empty($_GET['name']) ? "  and (name='{$_GET['name']}')" : '';
        $type_where = '';
        if (!empty($_GET['client_type']))
        {
            $client_type = $_GET['client_type'];
            $filler = Array(Client::CLIENT_CLIENT_TYPE_INGRESS => ' and (ingress_count>0) ', Client::CLIENT_CLIENT_TYPE_EGRESS => ' and (egress_count>0) ');
            $type_where = array_keys_value($filler, $client_type, '');
        }
        $rate_where = !empty($_GET ['query'] ['id_rates']) ? "  and (orig_rate_table_id={$_GET ['query'] ['id_rates']})" : '';
        $totalrecords = $this->query("select count(*) as c from client where 1=1 
	  $like_where  $name_where  $rate_where     $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select   client.login,client.password,client.client_id ,
							name as client_name,client.status,mode,
   					(select balance from c4_client_balance where client_id::integer =client.client_id ) as balance,
   					(select count(*)  from resource where  client_id=client.client_id and ingress=true)::float as ingress_count,
   					(select count(*)  from resource where  client_id=client.client_id and egress=true)::float as egress_count,
    			
 							(select current_balance from invoice where client_id=client.client_id order by invoice_id desc limit 1)as mutual_balance
   					from client  
    					where 1=1
		$like_where  $name_where  $rate_where  $type_where   $privilege  ";
        $sql .= "   order by $order   limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function findAll_ss()
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3)
        {
            $privilege = "  and(client_id::integer={$_SESSION['sst_client_id']}) ";
        }
        #pr($_GET['auto_invicing']);
        #是否为自动发票*********************************
        $all_invicing = "";
        $auto_invicing = !empty($_GET['auto_invicing']) ? "and  client.auto_invoicing = true" : "";
        $manual_invicing = !empty($_GET['manual_invicing']) ? "and  client.auto_invoicing = false " : "";
        if ((!empty($_GET['auto_invicing'])) && (!empty($_GET['manual_invicing'])))
        {
            //如果全选，则不管
            $auto_invicing = $manual_invicing = '';
        }
        #***************************************************************************
        $like_where = !empty($_GET['search']) ? " and (client.name like '%{$_GET['search']}%'  or  client.client_id::text like '%{$_GET['search']}%' )" : '';
        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients_name']) ? "  and (client.name='{$_GET ['query'] ['id_clients_name']}')" : '';
        $type_where = '';
        if ($_GET['type'] == 1)
        {
            $type_where = ' and auto_invoicing =false';
        }
        if ($_GET['type'] == 2)
        {
            $type_where = ' and auto_invoicing =true';
        }

        $totalrecords = $this->query("select count(client_id) as c from client where 1=1 $like_where  $name_where  $privilege  $client_where  $id_where  $type_where  ");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select client.client_id,name   from  client  where 1=1
		$like_where  $name_where    $client_where  $id_where   $privilege  $auto_invicing $manual_invicing $type_where";
        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function findAll_ss_package($currPage = 1, $pageSize = 10, $search_res = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $sql = "select count(package_id) as c from package";

        if (!empty($search_res))
        {
            $sql .= " where reseller_id = '$search_res'";
        }
        else
        {
            if (!empty($_SESSION ['sst_reseller_id']))
            {
                $reseller_id = $_SESSION ['sst_reseller_id'];
                $sql .= " where reseller_id = '$reseller_id'";
            }
        }

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select package_id,name   from  package	  	";

        if (!empty($search_res))
        {
            $sql .= " where  reseller_id = '$search_res'";
        }
        else
        {
            if (!empty($_SESSION ['sst_reseller_id']))
            {
                $reseller_id = $_SESSION ['sst_reseller_id'];
                $sql .= " where  reseller_id = '$reseller_id'";
            }
        }

        $sql .= "   order by package_id   limit $pageSize offset $offset";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function findAll_rss($currPage = 1, $pageSize = 10, $search_res = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $sql = "select count(reseller_id) as c from reseller";

        if (!empty($search_res))
        {
            $sql .= " where reseller_id = '$search_res'";
        }
        else
        {
            if (!empty($_SESSION ['sst_reseller_id']))
            {
                $reseller_id = $_SESSION ['sst_reseller_id'];
                $sql .= " where reseller_id = '$reseller_id'";
            }
        }

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select reseller.reseller_id,name   from  reseller	  	";

        if (!empty($search_res))
        {
            $sql .= " where  reseller.reseller_id = '$search_res'";
        }
        else
        {
            if (!empty($_SESSION ['sst_reseller_id']))
            {
                $reseller_id = $_SESSION ['sst_reseller_id'];
                $sql .= " where  reseller.reseller_id = '$reseller_id'";
            }
        }

        $sql .= "   order by reseller.reseller_id  desc   limit $pageSize offset $offset";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function findAll_codess($currPage = 1, $pageSize = 10)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $sql = "select count(code_id) as c from code";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select code,name,country   from  code	";
        $sql .= "   order by code_id  desc   limit $pageSize offset $offset";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function findAll_ratess()
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];

//模糊搜索
        $like_where = (!empty($_GET['search']) && strcmp('Keyword', $_GET['search']) ) ? " and (rate_table.name like '%{$_GET['search']}%'  or  rate_table_id::text like '%{$_GET['search']}%' )" : '';

        $name_where = !empty($_GET['name']) ? "  and (resource.name like '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';

        $client_where = !empty($_GET ['query'] ['id_clients_name']) ? "  and (client.name='{$_GET ['query'] ['id_clients_name']}')" : '';
        $totalrecords = $this->query("select count(rate_table_id) as c from rate_table
	 		left join (select  code_deck_id,name  as  code_name  from  code_deck )deck on deck.code_deck_id=rate_table.code_deck_id
		left join (select code as currency_code,currency_id  from  currency) curr  on curr.currency_id=rate_table.currency_id  where 1=1
	  $like_where  $name_where    $client_where  $id_where    ");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select rate_table_id,name  as table_name,code_name,currency_code   from  rate_table	
		left join (select  code_deck_id,name  as  code_name  from  code_deck )deck on deck.code_deck_id=rate_table.code_deck_id
		left join (select code as currency_code,currency_id  from  currency) curr  on curr.currency_id=rate_table.currency_id  where 1=1
		$like_where  $name_where    $client_where  $id_where      ";
        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function findAll_cardss($currPage = 1, $pageSize = 10)
    {
        $login_type = $_SESSION['login_type'];
        //damin
        if ($login_type == 1)
        {
            $sql = "select count(card_id) as c from card   where  reseller_id  IS NOT  NULL";
            $sql2 = "select card_id,card_number   from  card where   reseller_id IS NOT  NULL";
        }
        //reseller
        if ($login_type == 2)
        {
            $reseller_id = $_SESSION['sst_reseller_id'];
            $sql = "select count(card_id) as c from card  where reseller_id=$reseller_id";
            $sql2 = "   select card_id,card_number  from  card  where  reseller_id=$reseller_id  ";
        }



        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql2 .= "   order by card_id  desc   limit $pageSize offset $offset";
        $results = $this->query($sql2);
        $page->setDataArray($results);
        return $page;
    }

    /**
     *
     * 查找帐号池批次
     * @param $currPage
     * @param $pageSize
     */
    public function findAll_batchss($currPage = 1, $pageSize = 10, $card_series_id)
    {
        $login_type = $_SESSION['login_type'];

        $sql = "select count(series_batch_id) as c from series_batch  where card_series_id=$card_series_id";
        $sql2 = "select series_batch_id, start_num,end_num,generated_date,of_cards,of_cards_now from  series_batch  where card_series_id=$card_series_id   ";
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql2 .= "   order by series_batch_id  desc   limit $pageSize offset $offset";
        $results = $this->query($sql2);
        $page->setDataArray($results);
        return $page;
    }

    /**
     *
     * 查找帐号池
     * @param $currPage
     * @param $pageSize
     */
    public function findAll_seriess($currPage = 1, $pageSize = 10)
    {
        $login_type = $_SESSION['login_type'];
        //damin
        if ($login_type == 1)
        {
            $sql = "select count(card_series_id) as c from card_series";
            $sql2 = "select card_series_id,name   from  card_series ";
        }
        //reseller
        if ($login_type == 2)
        {
            $reseller_id = $_SESSION['sst_reseller_id'];
            $sql = "select count(card_series_id) as c from card_series  where reseller_id=$reseller_id";
            $sql2 = "   select card_series_id,name  from  card_series  where  reseller_id=$reseller_id  ";
        }



        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql2 .= "   order by card_series_id  desc   limit $pageSize offset $offset";
        $results = $this->query($sql2);
        $page->setDataArray($results);
        return $page;
    }

    /**
     * 按条件搜索客户
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function queryClient($data, $currPage = 1, $pageSize = 10, $search_res = null)
    {

        //解析搜索条件
        $condition = "where   ";
        $i = 0;
        $len = intval(count($data ['Client']));
        $adv_search = "&adv_search=1";
        foreach ($data ['Client'] as $key => $value)
        {
            if ($value == '')
            {
                continue;
            }
            $tmp = "client." . $key . "='" . $value . "'  and   ";
            $condition = $condition . $tmp;

            $adv_search .= "&$key=$value";
            $i++;
        }

        $where = substr($condition, 0, strrpos($condition, 'a'));



        if (!empty($search_res))
        {
            $where .= " and reseller_id = '$search_res'";
        }

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->query("select count(client_id) as c from client  $where");

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select client.client_id,name,client.status,mode,a.balance,c.orate_name,d.trate_name,r.r_name,
       client.call_international,client.call_internal
		    from  client
		    left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=client.reseller_id
		    left join (select balance,client_id  from c4_client_balance  ) a   on  a.client_id::integer=client.client_id
		    left join (select name  as orate_name,rate_table_id  from rate_table  ) c   on  c.rate_table_id=client.orig_rate_table_id
		    left join (select name  as trate_name,rate_table_id  from rate_table  ) d   on  d.rate_table_id=client.term_rate_table_id
		   $where
		";

        if (!empty($search_res))
        {
            $sql .= " and reseller_id = '$search_res'";
        }
        else
        {
            if (!empty($_SESSION ['sst_reseller_id']))
            {
                $reseller_id = $_SESSION ['sst_reseller_id'];
                $sql .= " and reseller_id = '$reseller_id'";
            }
        }

        $sql .= " limit $pageSize offset $offset";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return array($page, $adv_search);
    }

    /**
     *
     * @param unknown_type $res_id
     * @return 查询网关组
     */
    public function find_transations($currPage = 1, $pageSize = 10, $client_id)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        //damin
        if ($login_type == 1)
        {
            $sql1 = "select count(*) as c from transation ";
            $sql2 = " ";
        }
        //reseller
        if ($login_type == 2)
        {
            $reseller_id = $_SESSION['sst_reseller_id'];
            $sql1 = "select count(*) as c from transation where  id=$reseller_id  and user_type=2 ";
            $sql2 = " where  id=$reseller_id  and user_type=2";
        }
        //client
        if ($login_type == 3)
        {
            $client_id = $_SESSION['sst_client_id'];
            $sql1 = "select count(*) as c from transation where  id=$client_id  and user_type=3 ";
            $sql2 = "  where  id=$client_id  and user_type=3";
        }

        if ($login_type == 4)
        {
            $card_id = $_SESSION['card_id'];
            $sql1 = "select count(*) as c from transation where  id=$card_id  and user_type=4 ";
            $sql2 = "  where  id=$card_id  and user_type=4 ";
        }




        $totalrecords = $this->query($sql1);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $sql = "select *
		    from  transation a  $sql2
	order by create_time   desc  	limit '$pageSize' offset '$currPage'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function findResByres_id($res_id)
    {
        return $this->query("select * from resource where resource_id=$res_id");
    }

    /**
     *
     * @param unknown_type $res_id
     * @return 查询号码转换
     */
    function findresdirectByRes_id($res_id)
    {
        return $this->query("select * from resource_direction  where resource_id=$res_id");
    }

    /**
     *
     * @param unknown_type $res_id
     * @return 静态路由表
     */
    function findresproductByRes_id($res_id)
    {
        return $this->query("select * from resource_product_ref  where resource_id=$res_id");
    }

    public function checkCredit($client_id, $credit, $parent)
    {
        if ($parent != 0)
        {
            $parent_enough = $this->query("select enough_balance from client where client_id = $parent");
            if ($parent_enough[0][0]['enough_balance'] == false)
            {
                return false;
            }
        }

        $balance = $this->query(
            "select balance from c4_client_balance where client_id = '$client_id'
		 	order by client_balance_id desc limit 1");

        if (count($balance) > 0)
        {
            if ($balance[0][0]['balance'] < $credit)
                return false;
        } else
        {
            if ($credit >= 0)
                return false;
        }

        return true;
    }

    public function checkParent($parent)
    {
        $parent_enough = $this->query("select enough_balance from client where client_id = $parent");
        return $parent_enough[0][0]['enough_balance'];
    }

    function check_enough_balance($client_id)
    {
        $client_id = (int) $client_id;
        if ($client_id > 0)
        {
            $d = $this->find("first", array('conditions' => 'client_id = ' . $client_id));
            if (empty($d))
            {
                return false;
            }
            else
            {
                return $d[$this->alias]['enough_balance'];
            }
        }
    }

    /**
     *  order user regist to carrier
     * @param $id order_user id
     */
    public function user_registration($id)
    {
        $return = 0;
        $user_results = $this->query("select * from order_user where id=" . intval($id));
        $user_info = $user_results[0][0];
        $re = true;
        $this->begin();
        $client_arr = array(
            'client_id' => intval($user_info['client_id']),
            'name' => $user_info['name'],
            'currency_id' => 1,
            'allowed_credit' => -1,
            'company' => $user_info['company_name'],
            'email' => $user_info['corporate_contact_email'],
            'login' => $user_info['name'],
            'password' => $user_info['password'],
            'user_id' => intval($id)
        );

        $this->save(array('Client' => $client_arr));
        $client_id = $this->getlastinsertId();
        if (!empty($client_id))
        {
            $this->clientBalanceOperation($client_id, 0, 0);
            $this->query("update order_user set client_id = {$client_id}, status = 3 where id = " . intval($id));
        }
        else
        {
            $re = false;
        }

        if ($re)
        {
            $this->commit();
            $return = $client_id;
        }
        else
        {
            $this->rollback();
        }
        return $return;
    }

    public function ListCredit($currPage = 1, $pageSize = 15, $search_arr = array(), $search_type = 0, $order_arr = array())
    {
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;

        $sql_where = '';
        if ($search_type == 1)
        {

        }
        else
        {
            if (!empty($search_arr['search']))
            {
                //$sql_where .= " and  (action_number ilike '%".addslashes($search_arr['search'])."%' or descript like '%".addslashes($search_arr['search'])."%' or client.name ilike '%".addslashes($search_arr['search'])."%')";
            }
        }

        $sql_order = '';
        if (!empty($order_arr))
        {
            $sql_order = ' order by ';
            foreach ($order_arr as $k => $v)
            {
                $sql_order .= $k . ' ' . $v;
            }
        }

        $sql = "select count(id) as c from credit_application where true" . $sql_where;
        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select credit_application.*,client.name from credit_application left join client on credit_application.client_id=client.client_id where 1=1" . $sql_where . $sql_order;

        $sql .= " limit '$pageSize' offset '$offset'";
        //echo $sql;
        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    public function get_carrier_info($user_id)
    {
        $sql = "SELECT client.client_id, client.name, client.status, client.allowed_credit,client.unlimited_credit,

(SELECT count(*) FROM resource WHERE client_id = client.client_id AND egress = TRUE) as egress_count, 

(SELECT count(*) FROM resource WHERE client_id = client.client_id AND ingress = TRUE) as ingress_count,client.mode, c4_client_balance.ingress_balance, c4_client_balance.egress_balance, c4_client_balance.balance

FROM client LEFT JOIN c4_client_balance ON c4_client_balance.client_id::integer = client.client_id WHERE client.client_id = (SELECT client_id FROM users WHERE user_id = {$user_id})
";
        return $this->query($sql);
    }

    function get_unpaid_invoice_count($where)
    {
        $sql = <<<EOT
   SELECT 

count(*)

FROM invoice 

WHERE 

$where

EOT;
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    function get_unpaid_invoice($where, $pageSize, $offset)
    {
        $sql = <<<EOT
   SELECT 

invoice.invoice_number,

(SELECT name FROM client WHERE client_id = invoice.client_id) AS carrier_name,

invoice.invoice_time,

invoice.due_date,

invoice.total_amount,

invoice.pay_amount,

(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE invoice_number = invoice.invoice_number and (payment_type = 7 or payment_type = 8)) AS credit_note,

(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE invoice_number = invoice.invoice_number and (payment_type = 11 or payment_type = 12)) AS debit_note,

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number and (payment_type = 3 or payment_type = 4)) AS payment,

case when invoice.invoice_zone is null then invoice_start::text else (invoice_start AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_start, case when invoice.invoice_zone is null then invoice_end::text else (invoice_end AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_end,
            
type

FROM invoice 

WHERE 

$where

ORDER BY invoice.invoice_id DESC LIMIT $pageSize OFFSET $offset
EOT;
        return $this->query($sql);
    }

    public function get_client_payment($client_id, $start_time, $end_time, $type)
    {
        if ($type == 0)
        {
            $payment_type = '(4, 5)';
        }
        else
        {
            $payment_type = '(3, 6)';
        }
        $sql = <<<EOT
SELECT payment_time, receiving_time, amount, description FROM client_payment

WHERE client_id = '$client_id' AND payment_type in $payment_type AND payment_time BETWEEN '$start_time' AND '$end_time' 

ORDER BY receiving_time ASC 
EOT;
        return $this->query($sql);
    }

    public function get_client_cdr1($client_id, $start_time, $end_time, $prefix)
    {
        if ($prefix !== NULL)
        {
            if ($prefix == '')
                $prefix = '""';
            $prefix = " and ingress_prefix = '{$prefix}'";
        } else
        {
            $prefix = '';
        }
        $sql = "SELECT
date_trunc('hour', report_time) as report_time,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(lnp_cost) as lnp_cost,
sum(ingress_success_calls) as success_calls
from ".CDR_TABLE."
where report_time between '{$start_time}' and '{$end_time}' and ingress_client_id = $client_id $prefix GROUP BY date_trunc('hour', report_time) ORDER BY 1 ASC";
        return $this->query($sql);
    }

    public function get_client_cdr2($client_id, $start_time, $end_time, $prefix)
    {
        if ($prefix !== NULL)
        {
            $prefix = " and route_prefix  = '{$prefix}'";
        }
        else
        {
            $prefix = '';
        }

        $sql = "SELECT
date_trunc('hour', time) as report_time,      
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as call_cost,
sum(lnp_dipping_cost) as lnp_cost, 
count(case when egress_id is not null then 1 else null end) as success_calls
from client_cdr".date("Ymd")." where  is_final_call=1 and time between '{$start_time}' and '{$end_time}' and ingress_client_id = $client_id $prefix GROUP BY date_trunc('hour', time) ORDER BY 1 ASC";
        return $this->query($sql);
    }

    public function get_reset_balance($client_id, $time = '')
    {
        $where = '';
        if (!empty($time))
        {
            $where = " AND payment_time <= '{$time}' ";
        }
        $sql = "SELECT (amount + egress_amount) as amount, payment_time
FROM client_payment
WHERE payment_type = 14 AND client_id = {$client_id} {$where}  ORDER BY client_payment_id DESC LIMIT 1";
        return $this->query($sql);
    }

    public function get_begin_balance($reset_time, $start_time, $client_id)
    {
        $sql = <<<EOT
SELECT 
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE  payment_type in (4,5) AND client_id = $client_id  
AND payment_time between '$reset_time' and '$start_time')
+
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 8 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time')
-
(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE payment_type = 12 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time')
-
(select COALESCE(sum(d::numeric), 0)
from actual_trans('$reset_time', '$start_time', $client_id,11) 
as t(a text,b text,c text,d text))
-
(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE  payment_type in (3,6) AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') -

(SELECT COALESCE(sum(amount), 0) FROM client_payment 
WHERE payment_type = 7 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') -

(SELECT COALESCE(sum(amount), 0) FROM client_payment 
WHERE payment_type = 15 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') +  

(SELECT COALESCE(sum(amount), 0) FROM client_payment 
WHERE payment_type = 11 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') +
(select COALESCE(sum(d::numeric), 0) from actual_trans('$reset_time', '$start_time', $client_id,10) as t(a text,b text,c text,d text))
AS amount
EOT;
        $result = $this->query($sql);
        return $result[0][0]['amount'];
    }

    public function get_exchange_begin_balance($start_time, $client_id)
    {
//        $sql = <<<EOT
//select
//
//transaction_time::date, sum(amount) as amount
//
//from client_finance_transaction
//
//where client_id = {$client_id} and transaction_type = 0 and transaction_time::date < '{$start_time}'
//
//group by transaction_time::date, transaction_type, client_id
//
//order by 1 desc limit 1
//EOT;
//        $result = $this->query($sql);
        $result = array();
        if (empty($result))
            return 0;
        return $result[0][0]['amount'];
    }

    /*
      public function get_exchange_begin_balance($start_time, $end_time, $client_id)
      {
      $sql = <<<EOT
      select
      (select
      COALESCE(sum(actual_amount), 0) as amount
      from exchange_finance where client_id = {$client_id}
      and status = 2  and action_type = 2 and complete_time between '{$start_time}' and '{$end_time}')
      -
      (select
      COALESCE(sum(actual_amount), 0) as amount
      from exchange_finance where client_id = {$client_id}
      and status = 2  and action_type = 1 and action_time between '{$start_time}' and '{$end_time}')
      -
      (select COALESCE(sum(d::numeric), 0) from
      actual_trans('{$start_time}', '{$end_time}', {$client_id},10) as t(a text,b text,c text,d text))
      +
      (select COALESCE(sum(d::numeric), 0) from
      actual_trans('{$start_time}', '{$end_time}', {$client_id},11) as t(a text,b text,c text,d text)) as amount
      EOT;
      $result = $this->query($sql);
      return $result[0][0]['amount'];
      }
     */

    public function get_begin_balance_mutual($reset_time, $start_time, $client_id)
    {
        $sql = <<<EOT
SELECT 
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE  payment_type in (4,5) AND client_id = $client_id  
AND payment_time between '$reset_time' and '$start_time')

-
         
(SELECT COALESCE(sum(total_amount), 0) FROM invoice WHERE client_id = $client_id AND state = 0 AND type = 0 AND invoice_time between '$reset_time' and '$start_time')
         
+
(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE payment_type = 8 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time')
-
(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE payment_type = 12 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time')
-
(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE  payment_type in (3, 6) AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') +
         
(SELECT COALESCE(sum(total_amount), 0) FROM invoice WHERE client_id = $client_id AND type = 3 AND invoice_time between '$reset_time' and '$start_time') 
         
-

(SELECT COALESCE(sum(amount), 0) FROM client_payment 
WHERE payment_type = 8 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') +  

(SELECT COALESCE(sum(amount), 0) FROM client_payment 
WHERE payment_type = 11 AND client_id = $client_id 
AND payment_time between '$reset_time' and '$start_time') 
AS amount
EOT;
        $result = $this->query($sql);
        return $result[0][0]['amount'];
    }

    public function get_create_time($client_id)
    {
        $sql = "SELECT create_time FROM c4_client_balance WHERE client_id =  '$client_id'";
        $result = $this->query($sql);
        if(empty($result)){
            $sql = "SELECT create_time FROM client WHERE client_id =  '$client_id'";
            $result = $this->query($sql);

            $this->clientBalanceOperation($client_id, 0, 0);
        }
        return $result[0][0]['create_time'];
    }

    public function get_client_ingress_balance_record($client_id, $start_time, $end_time)
    {
        $sql = <<<EOT
SELECT sum(amount) as amount, payment_time::DATE as time, 1 as type FROM client_payment 
WHERE  payment_type in (4,5) AND client_id = $client_id
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE 
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 2 as type FROM client_payment 
WHERE payment_type = 8 AND client_id = $client_id  
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE  
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 3 as type FROM client_payment 
WHERE payment_type = 12 AND client_id = $client_id 
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE 
UNION
select  sum(d::numeric) as amount, a::DATE as time, 4 as type 
from actual_trans('$start_time', '$end_time', $client_id,11) 
as t(a text,b text,c text,d text) GROUP BY a::date           
EOT;
        return $this->query($sql);
    }

    public function get_client_egress_balance_record($client_id, $start_time, $end_time)
    {
        $sql = <<<EOT
SELECT sum(amount) as amount, payment_time::DATE as time, 1 as type FROM client_payment 
WHERE  payment_type in (3,6) AND client_id = $client_id
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE 
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 2 as type FROM client_payment 
WHERE payment_type = 7 AND client_id = $client_id  
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE  
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 3 as type FROM client_payment 
WHERE payment_type = 11 AND client_id = $client_id 
AND payment_time::DATE  BETWEEN '$start_time' AND '$end_time'
GROUP BY payment_time::DATE 
UNION
select  sum(d::numeric) as amount, a::DATE as time, 4 as type 
from actual_trans('$start_time', '$end_time', $client_id,10) 
as t(a text,b text,c text,d text) GROUP BY a::date           
EOT;
        return $this->query($sql);
    }

    public function get_client_name($client_id)
    {
        $sql = "SELECT name FROM client where client_id = {$client_id}";
        $data = $this->query($sql);
        $client_name = $data[0][0]['name'];
        return $client_name;
    }

    public function get_exchange_transaction($client_id, $start_date, $end_date)
    {
//        $sql = <<<EOT
//select
//
//transaction_time::date, sum(amount) as amount,transaction_type
//
//from client_finance_transaction
//
//where client_id = {$client_id} and transaction_time between '{$start_date}' and '{$end_date}'
//
//group by transaction_time::date, transaction_type, client_id
//
//order by 1 asc
//EOT;
//        return $this->query($sql);
    }

    /*
      public function get_exchange_transaction($client_id,$start_date, $end_date)
      {
      $sql = <<<EOT

      select
      sum(actual_amount) as amount,
      complete_time::DATE as time,
      1 as type
      from exchange_finance where client_id = {$client_id}
      and status = 2  and action_type = 2 and complete_time between '{$start_date}' and '{$end_date}' group by complete_time::DATE

      union

      select
      sum(actual_amount) as amount,
      action_time::DATE as time,
      2 as type
      from exchange_finance where client_id = {$client_id}
      and status = 2 and action_type = 1 and action_time between '{$start_date}' and '{$end_date}' group by action_time::DATE

      union

      select
      sum(d::numeric) as amount, a::DATE as time, 3 as type
      from actual_trans_exchange('{$start_date}', '{$end_date}', {$client_id}, 11)
      as
      t(a text,b text,c text,d text) GROUP BY a::date

      union

      select
      sum(d::numeric) as amount, a::DATE as time, 4 as type
      from actual_trans_exchange('{$start_date}', '{$end_date}', {$client_id}, 10)
      as
      t(a text,b text,c text,d text) GROUP BY a::date
      EOT;
      return $this->query($sql);
      }
     */

    public function get_prefixs($client_id)
    {
        $sql = "SELECT distinct tech_prefix FROM resource_prefix 
INNER JOIN resource ON resource.resource_id = resource_prefix.resource_id
WHERE resource.client_id = {$client_id}
ORDER BY tech_prefix ASC ";
        return $this->query($sql);
    }

    public function check_finance_info($id)
    {
        $sql = "select * from payline_history where invoice_id = '{$id}' limit 1";
        $result = $this->query($sql);
        return $result;
    }

    public function check_finance_info2($id)
    {
        $sql = "select * from payline_history where id = '{$id}' limit 1";
        $result = $this->query($sql);
        return $result;
    }

    function change_finance_status($id, $status, $payment_fee = '',$txn_id = '', $payer_email = '')
    {
        $payment_change = '';
        if (!empty($payment_fee))
        {
            $payment_change .= " ,fee = {$payment_fee}";
        }

        if (!empty($txn_id))
        {
            $payment_change .= " ,transaction_id = '{$txn_id}'";
        }

        if (!empty($payer_email))
        {
            $payment_change .= " ,paypal_id = '{$payer_email}'";
        }

        if($status == 2){
            $payment_change .= ",return_code='VERIFIED'";
        } else {
            $payment_change .= ",return_code='INVALID'";
        }

        $now = date("Y-m-d H:i:s");
        $sql = "update payline_history set status = {$status} {$payment_change},modified_time = '{$now}' where id = {$id}";

        file_put_contents('/tmp/test1', $sql, FILE_APPEND);
        $this->query($sql);
    }

    public function update_finance($client_id, $amount, $paypal_id)
    {
        $result = $this->clientBalanceOperation($client_id, $amount, 2, true);
        $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time,description)
    VALUES ({$client_id},5,{$amount}, {$result[0][0]['balance']},'now', TRUE, CURRENT_TIMESTAMP(0),'PaypalID({$paypal_id})')";
        file_put_contents('/tmp/test1', $sql, FILE_APPEND);
        $this->query($sql);
    }

    public function get_resource_ips($client_id)
    {
        $ips = array();
        $sql = "select ip from resource_ip where resource_id in (select resource_id from resource where client_id = {$client_id}) group by ip";
        $result = $this->query($sql);
        foreach ($result as $item)
        {
            array_push($ips, $item[0]['ip']);
        }
        return $ips;
    }

    public function get_ingress_resource_id($client_id)
    {
        $sql = "select resource_id from resource where ingress = true and client_id = {$client_id}";
        $result = $this->query($sql);
        if (count($result))
            return $result[0][0]['resource_id'];
        else
            return '';
    }

    public function get_egress_resource_id($client_id)
    {
        $sql = "select resource_id from resource where egress = true and client_id = {$client_id}";
        $result = $this->query($sql);
        if (count($result))
            return $result[0][0]['resource_id'];
        else
            return '';
    }

    public function download_balance($date, $group_id = false)
    {
        $database_export_path = Configure::read('database_export_path');
//        $database_actual_export_path = Configure::read('database_actual_export_path');

        $random_filename = uniqid('balance[' . $date . ']_') . '.csv';
        $add_where = $group_id ? " AND client.group_id='{$group_id}'" : '';
        if (strcmp($date,date('Y-m-d')))
        {
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename={$random_filename}");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            $sql = <<<EOT
\COPY (
select client.name,balance_history.mutual_ingress_balance,balance_history.mutual_egress_balance,
(mutual_ingress_balance+mutual_egress_balance) as total_mutual_balance,
balance_history_actual.actual_ingress_balance,balance_history_actual.actual_egress_balance,
(actual_ingress_balance+actual_egress_balance) as total_actual_balance
FROM balance_history left join client on client.client_id = balance_history.client_id
 left join balance_history_actual on balance_history_actual.client_id = client.client_id
where  balance_history.date = '{$date}' and balance_history_actual.date = '{$date}'
{$add_where}) TO '$database_export_path/{$random_filename}' CSV HEADER DELIMITER AS ','
EOT;
            $this->_get_psql_cmd($sql);
            $header = "{$date} Balance";
            readfile($database_export_path . DS . $random_filename);
            exit;
        }
        else
        {
            $client_sql = "SELECT name,client_id FROM client WHERE client.client_type is null and client.name != 'Check Route'
AND client.status = true {$add_where} order by client.name ASC";
            $client_list = $this->query($client_sql);
//            pr($client_list);die;
            $data = array();
            foreach ($client_list as $key => $client)
            {
                $client_id = $client[0]['client_id'];
                $data[$key] = $this->get_balance($client_id);
                $data[$key]['name'] = $client[0]['name'];
            }
            return $data;
        }
    }

    //carrier_template reapply
    function save_reapply($data){
        $data ['Client'] ['create_time'] = date("Y-m-d   H:i:s"); //加密.

        $data['Client'] ['low_balance_number'] = $data['Client']['daily_balance_notification'];
        $unlimited_credit = $data['Client']['unlimited_credit'] == "0" ? false : true;

        $data ['Client']['usage_detail_fields'] = isset($data['Client']['usage_detail_fields']) ? implode(',', $data['Client']['usage_detail_fields']) : '';


        $client_id = $data['Client']['client_id'];
        $data['Client']['update_at'] = date("Y-m-d H:i:s");
        $data['Client']['update_by'] = $_SESSION['sst_user_name'];

        $this->begin();

        $sql = "select * from client where client_id = $client_id";
        $res = $this->query($sql);
        $old_data['Client'] = $res[0][0];
        $orig_data = $old_data;
        //rollback start
        if ($data['Client']['allowed_credit'] != $orig_data['Client']['allowed_credit'] || $data['Client']['unlimited_credit'] != $orig_data['Client']['unlimited_credit'])
        {
            $modified_from = $orig_data['Client']['unlimited_credit']? 'NULL':$orig_data['Client']['allowed_credit'];
            $modified_to= $data['Client']['unlimited_credit']? 'NULL':$data['Client']['allowed_credit'];
            $sql = "insert into credit_log(modified_by, modified_from, modified_to, modified_on, carrier_name) VALUES ('{$_SESSION['sst_user_name']}', $modified_from, $modified_to, CURRENT_TIMESTAMP(0), '{$data['Client']['name']}')";
            $this->query($sql);
        }

        $rollback_data = array();
        foreach ($data['Client'] as $key => $value)
        {
            if( !isset($old_data['Client'][$key]) ){
                continue;
            }
            if ($old_data['Client'][$key] != $value)
            {
                $int_arr = array(
                    'mode', 'orig_rate_table_id', 'term_rate_table_id', 'currency_id',
                    'allowed_credit', 'payment_term_id', 'invoice_format', 'cdr_list_format'
                , 'notify_client_balance', 'notify_admin_balance', 'role_id', 'profit_margin'
                , 'service_charge_id', 'invoice_past_amount', 'mail_sended', 'scc_bellow'
                , 'scc_percent', 'scc_charge', 'user_id', 'profit_type', 'auto_invoice_type'
                , 'cps_limit', 'call_limit', 'daily_balance_notification', 'daily_balance_recipient'
                , 'low_balance_number', 'numer_of_days_balance', 'auto_daily_balance_recipient'
                , 'daily_cdr_generation_type', 'transaction_fee_id', 'include_payment_history'
                , 'include_available_credit', 'include_payment_history_days', 'auto_summary_not_zero'
                , 'auto_summary_group_by', 'tax', 'decimal_place', 'par_id', 'rate_value'
                , 'scc_type', 'auto_summary_hour', 'auto_summary_period', 'client_type'
                , 'invoice_use_balance_type', 'notify_client_balance_type'
                , 'decimals_num', 'low_balance_notification_time_type', 'low_balance_notification_time_cycle'
                , 'breakdown_by_rate_table','hourly_limit','daily_limit'
                );


                if (!in_array($key, $int_arr))
                {
                    $rollback_data[] = $key . " = '" . $old_data['Client'][$key] . "'";
                }
                else
                {
                    $rollback_data[] = $key . " = " . $old_data['Client'][$key];
                }
            }
        }
        $rollback_update_sql = implode(',', $rollback_data);
        if($rollback_update_sql && $client_id){
            $rollback_sql = "UPDATE client SET {$rollback_update_sql} WHERE client_id = {$client_id};";
        }
        //rollback end
        if ($this->save($data['Client']))
        {
            $list = $this->query("select balance from c4_client_balance where client_id='$client_id';");
            if (isset($list[0][0]['balance']))
            {
                $balance = $list[0][0]['balance'];
                $allowed_credit = $data['Client']['allowed_credit'];
                if (!$unlimited_credit)
                {
                    if ($balance > $allowed_credit)
                    {
                        $this->query("update client  set  enough_balance=true  where  client_id=$client_id");
                        $this->query("update resource   set  enough_balance=true  where  client_id=$client_id");
                    }
                    else
                    {
                        $this->query("update client  set  enough_balance=false  where  client_id=$client_id");
                        $this->query("update resource   set  enough_balance=false  where  client_id=$client_id");
                    }
                }
                else
                {
                    $this->query("update resource   set  enough_balance=true  where  client_id=$client_id");
                    $this->query("update client  set  enough_balance=true  where  client_id=$client_id");
                }
                $old_data['Client']['enough_balance'] = $old_data['Client']['enough_balance'] ? 'true' : 'false';
                $rollback_sql .= "UPDATE client SET enough_balance = {$old_data['Client']['enough_balance']} WHERE client_id = {$client_id};";
                $rollback_sql .= "UPDATE resource SET enough_balance = {$old_data['Client']['enough_balance']} WHERE client_id = {$client_id};";

                if (isset($data['Client']['scc_charge']))
                {
                    $this->query("update client set scc_charge = {$data['Client']['scc_charge']} where client_id={$client_id}");
                    $rollback_sql .= "UPDATE client SET scc_charge = {$old_data['Client']['scc_charge']} WHERE client_id = {$client_id};";
                }
            }

            $this->commit();
            $rollback_msg = "Modify  Carrier [" . $data['Client']['name'] . "] operation have been rolled back!";
            $log_id = $this->logging(2, 'Carrier', "Carrier's name:" . $data['Client']['name'],$rollback_sql,$rollback_msg);
        }
        else {
            return false;
        }

        $data['Client']['enable_payment_alert'] = $data['Client']['enable_payment_alert'] == '1' ? 'true' : 'false';
        $data['Client']['is_daily_balance_notification'] = $data['Client']['is_daily_balance_notification'] === '1' ? 'true' : 'false';
        $data['Client']['low_balance_notice'] = $data['Client']['low_balance_notice'] === '1' ? 'true' : 'false';
        $data['Client']['zero_balance_notice'] = $data['Client']['zero_balance_notice'] === '1' ? 'true' : 'false';
        $data['Client']['is_auto_summary'] = $data['Client']['is_auto_summary'] === '1' ? 'true' : 'false';
        $data['Client']['zero_balance_notice_time'] = $data['Client']['zero_balance_notice_time'] ? $data['Client']['zero_balance_notice_time'] : 0;
        $notify_client_balance = empty($data['Client']['notify_client_balance']) ? 'NULL' : $data['Client']['notify_client_balance'];
        $daily_balance_notification = empty($data['Client']['daily_balance_notification']) ? 'NULL' : $data['Client']['daily_balance_notification'];
        $low_balance_number = empty($data['Client']['low_balance_number']) ? 'NULL' : $data['Client']['low_balance_number'];
        $zero_balance_notice_time = empty($data['Client']['zero_balance_notice_time']) ? 0 : $data['Client']['zero_balance_notice_time'];

        $this->query("UPDATE client SET is_daily_balance_notification = {$data['Client']['is_daily_balance_notification']},
                                enable_payment_alert = {$data['Client']['enable_payment_alert']},
                                low_balance_notice = {$data['Client']['low_balance_notice']}, notify_client_balance = {$notify_client_balance},
                                zero_balance_notice = {$data['Client']['zero_balance_notice']},
                                zero_balance_notice_time = {$zero_balance_notice_time},
                                daily_balance_notification = {$daily_balance_notification},
                                daily_balance_recipient = {$data['Client']['daily_balance_recipient']}, is_auto_summary = {$data['Client']['is_auto_summary']},
                                auto_send_zone = '{$data['Client']['auto_send_zone']}', low_balance_number = {$low_balance_number} WHERE client_id = {$client_id}");

        //$return['client_id'] = $client_id;
        //$return['log_id'] = $log_id;
        return true;
    }

    public function get_client_balance($client_id) {
        $result = $this->query("select balance from c4_client_balance where client_id='{$client_id}'");
        return $result;
    }

    public function clients_payment_count($client_id, $start_time, $end_time){
        $payline_history = $this->query("SELECT count(*)
                                      FROM payline_history
                                      WHERE client_id = {$client_id} AND  created_time BETWEEN '{$start_time}' AND '{$end_time}'");
        $client_payment = $this->query("SELECT count(*)
                                      FROM client_payment
                                      WHERE client_id = {$client_id} AND receiving_time BETWEEN '{$start_time}' AND '{$end_time}'");
        $payline_history_cnt = isset($payline_history[0][0]['count']) ? $payline_history[0][0]['count'] : 0;
        $client_payment_cnt = isset($client_payment[0][0]['count']) ? $client_payment[0][0]['count'] : 0;
        return $payline_history_cnt + $client_payment_cnt;
    }

    public function client_payment($client_id, $start_time, $end_time, $pageSize, $offset)
    {
        Configure::write('debug', 2);
        $sql = <<<SQL
    SELECT chargetotal as amount, transaction_id, created_time as paid_on, fee, charge_amount, method
    FROM payline_history
    WHERE client_id = {$client_id} AND created_time BETWEEN '{$start_time}' AND '{$end_time}'
    UNION
    SELECT amount, '' as transaction_id, receiving_time as paid_on, '0' as fee, '0' as charge_amount,
    CASE payment_type
    WHEN 4 THEN 3
    WHEN 5 THEN 3
    WHEN 3 THEN 4
    END AS method
    FROM client_payment
    WHERE client_id = {$client_id} AND payment_time BETWEEN '{$start_time}' AND '{$end_time}'
    ORDER BY paid_on DESC
    LIMIT {$pageSize} OFFSET {$offset} 
SQL;
        $result = $this->query($sql);

        return $result;

    }


    public function data_with_values(){
        $sql = <<<EOT
SELECT 
client.name, client.orig_rate_table_id, client.orig_rate_table_id,abs(allowed_credit) as allowed_credit,last_invoiced,notify_client_balance,
zero_balance_notice_time, company,address,email,logo,login,password,role_id,create_time,profit_margin,service_charge_id,
noc_email,billing_email,rate_email,tax_id,details,invoice_past_amount,daily_balance_recipient,numer_of_days_balance,update_at,update_by,daily_balance_notification,
auto_invoicing,attach_cdrs_list,low_balance_notice,zero_balance_notice,is_panelaccess,enough_balance,invoice_show_details,
is_link_cdr,is_auto_balance,is_daily_balance_notification,
(case when client.mode = '1' then 'Prepaid' when client.mode = '2'  then 'Postpaid' else '' end) as mode,
(case when client.status = true then 'Active' when client.status = false  then 'Inactive' else '' end) as status,
(case when client.invoice_format = '1' then 'PDF' when client.invoice_format = '2'  then 'Excel' when client.invoice_format = '3'  then 'HTML' else '' end) as invoice_format,
(case when client.cdr_list_format = '1' then 'Excel' when client.cdr_list_format = '2'  then 'CSV' when client.cdr_list_format = '3'  then '3-zip' when client.cdr_list_format = '4'  then '4-tar.gz' else '' end) as cdr_list_format,
(SELECT code FROM currency WHERE client.currency_id=currency.currency_id) as currency_id,
('[' || (SELECT name FROM payment_term WHERE payment_term.payment_term_id=client.payment_term_id) || ']') as payment_term_id
FROM client
WHERE client_type IS NULL
ORDER BY name ASC
EOT;

        return $this->query($sql);
    }

    public function get_origination_clients(){
        $data = [];
        $sql = "SELECT client_id, name FROM client WHERE status = true AND client_type = 1 ORDER BY name;";
        $res = $this->query($sql);
        foreach ($res as $item){
            $data[$item[0]['client_id']] =  $item[0]['name'];
        }
        return $data;
    }

}
