<?php 

class OrdersController extends DidAppController
{
    var $name = 'Orders';
    var $uses = array('did.DidRepos', 'did.DidAssign', 'Resource', 'Cdr', 'did.DidRequest');
    var $components = array('RequestHandler', 'Session');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        Configure::load('myconf');
        return true;
        parent::beforeFilter();
        //if ($login_type == 3)
    }
    
    public function clear_display()
    {
        /* Do not auto View and display debug info */
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
    }
    
    public function index()
    {
        /* Default redirect to browse action */
        
        $this->xredirect('/did/orders/browse');
    }
    
    
    public function browse()
    {
        $this->pageTitle = "DID Orders/Search";
        
        $countries = $this->DidRepos->get_countries();
        $this->set('countries', $countries);
    }
    
    public function search($type)
    {
        $this->clear_display();
        $data = null;
        $type = (int)$type;  
        $page = intval($_POST['page']);
        $pageSize = 100;
        
        switch ($type)
        {
            case 1:
                $data = $this->DidRepos->get_group_state($page, $pageSize);
                break;
            case 2:
                $data = $this->DidRepos->get_group_area_code($page, $pageSize);
                break;
            case 3:
                $data = $this->DidRepos->get_group_lata($page, $pageSize);
                break;
            case 4:
                $data = $this->DidRepos->custome_search($_POST, $page, $pageSize);
                break;
        }
        
        echo json_encode($data);
    }
    
    public function search_listing($type)
    {
        $this->clear_display();
        $data = null;
        $type = (int)$type;
        $input =  $_POST['text'];
        $page = intval($_POST['page']);
        $pageSize = 100;
        
        switch ($type)
        {
            case 1:
                
                $data =  $this->DidRepos->get_did_by_state($input, $page, $pageSize);
                break;
            case 2:
                $data =  $this->DidRepos->get_did_by_area_code($input, $page, $pageSize);
                break;
            case 3:
                $data =  $this->DidRepos->get_did_by_lata($input, $page, $pageSize);
                break;
        }
        
        echo json_encode($data);
    }
    
    public function put_into_cart()
    {
        $this->clear_display();
        App::import('Vendor', 'short_cart');
        $number = $_POST['number'];
        $control_result = false;
        
        if ($this->Session->check('shopping_cart'))
        {
            $cart = unserialize($this->Session->read('shopping_cart'));
        }
        else
        {   
            $cart = new ShoppingCart();
        }
        
        $product = $this->DidRepos->get_number_info($number);
        
        if ($product != false)
        {
            $control_result = $cart->add($product);
        } 
        else
        {
            $control_result = false;
        }
        
        $this->Session->write('shopping_cart', serialize($cart));
        
        return json_encode(array('result' => $control_result));
        
    }
    
    public function shopping_cart()
    {        
        
        $this->pageTitle = "DID Orders/Shopping Cart";
        App::import('Vendor', 'short_cart');
        $client_id = $this->Session->read('sst_client_id');
        
        if ($this->Session->check('shopping_cart'))
        {
            $cart = unserialize($this->Session->read('shopping_cart'));
        }
        else
        {   
            $cart = new ShoppingCart();
        }
        $data = $cart->getAll();
        if ($this->RequestHandler->ispost())
        {
            $removes  = isset($_POST['remove']) ? $_POST['remove'] : array();
            $egresses = $_POST['egresses_id'];
            //$product_id = $this->DidAssign->check_default_static();
            $len = count($data);
            
            $user_id = $_SESSION['sst_user_id'];
            // create new request
            $request_id = $this->DidRequest->create_request($user_id);
            
            for($i = 0; $i < $len ; $i++)
            {
                if (isset($removes[$i]) && $removes[$i] == 1)
                    continue;
                /*
                $item_id = $this->DidAssign->add_new_number($data[$i]['number'], $product_id);
                $this->DidAssign->add_new_resouce($item_id, $egresses[$i]);
                $this->DidAssign->add_assign($data[$i]['number'], $egresses[$i]);
                 * 
                 */
                // begin insert to request
                
                $this->DidRequest->create_request_detail($request_id, $data[$i]['number'], $egresses[$i]);
                
            }
            
            $cart = new ShoppingCart();
            $this->Session->write('shopping_cart', serialize($cart));
            $this->Session->write('m', $this->DidAssign->create_json(201, __('Your Request ' . $request_id . ' has been submitted', true)));
            $this->xredirect("/did/did_request/index");
        }
        $egresses = $this->DidRepos->get_users_egresses($client_id);
        $this->set('egresses', $egresses);
        $this->set('data', $data);
    }
    
    public function shopping_cart_mutiples()
    {
        $this->pageTitle = "DID Orders/Shopping Cart";
        App::import('Vendor', 'short_cart');
        $client_id = $this->Session->read('sst_client_id');
        
        if ($this->Session->check('shopping_cart'))
        {
            $cart = unserialize($this->Session->read('shopping_cart'));
        }
        else
        {   
            $cart = new ShoppingCart();
        }
        $data = $cart->getAll();
        if ($this->RequestHandler->ispost())
        {
            $removes  = isset($_POST['remove']) ? $_POST['remove'] : array();
            $egresses = $_POST['egresses_id'];
            $country = $_POST['country'];
            $rate_center = $_POST['rate_center'];
            $state = $_POST['state'];
            $city = $_POST['city'];
            $lata = $_POST['lata'];
            $amount = $_POST['amount'];
            
            $len = count($data);
            $user_id = $_SESSION['sst_user_id'];
            // create new request
            $request_id = $this->DidRequest->create_request($user_id);
            
            
            for($i = 0; $i < $len ; $i++)
            {
                if (isset($removes[$i]) && $removes[$i] == 1)
                    continue;
                $this->DidRequest->create_new_mutiples($request_id, $country[$i], $rate_center[$i], $state[$i], $city[$i], $lata[$i], $amount[$i], $egresses[$i]);
                
            }
            
            $cart = new ShoppingCart();
            $this->Session->write('shopping_cart', serialize($cart));
            $this->Session->write('m', $this->DidAssign->create_json(201, __('Your Request ' . $request_id . ' has been submitted', true)));
            $this->xredirect("/did/did_request/index");
        }
        
        $data_multiples = array();
        $id = 0;
        
        foreach ($data as $item) {
            $country = $item['country'];
            $rate_center = $item['rate_center'];
            $state = $item['state'];
            $city = $item['city'];
            $lata = $item['lata'];
            
            foreach ($data_multiples as $item_multiples) {
                if ($item_multiples['country'] == $country && $item_multiples['rate_center'] && $rate_center
                    && $item_multiples['state'] == $state && $item_multiples['city'] == $city
                    && $item_multiples['lata'] == $lata ) 
                    continue 2;
            }
            
            $count = $this->DidRepos->get_group_count($country, $rate_center, $state, $city, $lata);
            
            
            array_push($data_multiples, array(
                'count'   => $count,
                'country' => $country,
                'rate_center' => $rate_center,
                'state' => $state,
                'city' => $city,
                'lata' => $lata,
                'id'   => $id,
            ));
            
            $id++;
        }
        unset($data);
        
        $egresses = $this->DidRepos->get_users_egresses($client_id);
        $this->set('egresses', $egresses);
        $this->set('data', $data_multiples);
    }
    
    public function delete_cart_item($number)
    {
        //$this->clear_display();
        
        App::import('Vendor', 'short_cart');
        
        if ($this->Session->check('shopping_cart'))
        {
            $cart = unserialize($this->Session->read('shopping_cart'));
        }
        else
        {   
            $cart = new ShoppingCart();
        }
        $product = array('number' => $number);
        $cart->delete($product);
        
        $this->Session->write('shopping_cart', serialize($cart));
        
        $this->xredirect('/did/orders/shopping_cart');
    }
    
    
    public function ingress_trunk()
    {
        $this->pageTitle = "Trunk Management/Orig. Service";
        
        $client_id = $this->Session->read('sst_client_id');
        
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once MODELS.DS.'MyPage.php';
        $page = new MyPage();
        
        $count = $this->Resource->find('count', array('conditions' => array('Resource.client_id' => $client_id, 'Resource.egress' => true, 'Resource.trunk_type2' => 1),));

        $page->setTotalRecords ( $count ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
	$pageSize = $page->getPageSize();
        
        $data = $this->Resource->find('all',
            array(
                'fields'     => array('Resource.resource_id', 'Resource.alias', 'ResourceIp.ip', 'ResourceDirection.digits'),
                'conditions' => array('Resource.client_id' => $client_id, 'Resource.ingress' => true, 'Resource.trunk_type2' => 1),
                'order'      => array('Resource.alias ASC'),
                'limit'      => $pageSize,
                'page'       => $currPage,
                'joins'      => array(
                    array(
                        'table' => 'resource_ip',
                        'alias' => "ResourceIp",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceIp.resource_id = Resource.resource_id',
                        ),
                    ),
                    array(
                        'table' => 'resource_direction',
                        'alias' => "ResourceDirection",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceDirection.resource_id = Resource.resource_id',
                        ),
                    ),
                ),
            )
        );
        
        $page->setDataArray($data);
        $this->set('p',$page);
    }
    
    public function egress_trunk()
    {
        $this->pageTitle = "Trunk Management/Term. Service";
        
        $client_id = $this->Session->read('sst_client_id');
        
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once MODELS.DS.'MyPage.php';
        $page = new MyPage();
        
        $count = $this->Resource->find('count', array('conditions' => array('Resource.client_id' => $client_id, 'Resource.egress' => true, 'Resource.trunk_type2' => 1),));

        $page->setTotalRecords ( $count ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
	$pageSize = $page->getPageSize();
        
        $data = $this->Resource->find('all',
            array(
                'fields'     => array('Resource.resource_id', 'Resource.alias', 'ResourceIp.ip', 'ResourceDirection.digits'),
                'conditions' => array('Resource.client_id' => $client_id, 'Resource.egress' => true, 'Resource.trunk_type2' => 1),
                'order'      => array('Resource.alias ASC'),
                'limit'      => $pageSize,
                'page'       => $currPage,
                'joins'      => array(
                    array(
                        'table' => 'resource_ip',
                        'alias' => "ResourceIp",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceIp.resource_id = Resource.resource_id',
                        ),
                    ),
                    array(
                        'table' => 'resource_direction',
                        'alias' => "ResourceDirection",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceDirection.resource_id = Resource.resource_id',
                        ),
                    ),
                ),
            )
        );
        
        $page->setDataArray($data);
        $this->set('p',$page);
       
    }
    
    public function trunk_panel($id = null, $trunk_type = 'egress')
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            
            $name = trim($this->data['Resource']['name']);
            $ip = trim($this->data['Resource']["ip"]);
            $mask = trim($this->data['Resource']['mask']);
            $prefix = $this->data['Resource']['prefix'];
            $client_id = $this->Session->read('sst_client_id');
            if (!empty($mask))
                $ip = $ip . '/' . $mask;
            //var_dump($id);die;
            if(!empty($id))
            {
                $sql = "";
                if($name)
                {
                    $sql .= "update resource set alias = '{$name}' where resource_id = {$id};";
                }
                if($ip)
                {
                    $sql .="update resource_ip set ip = network('{$ip}') where resource_id = {$id};";
                }
                if($prefix)
                {
                    $sql .= "update resource_direction set digits = '{$prefix}' where resource_id = {$id};";
                }
                $this->Resource->query($sql);
                $this->Session->write('m', $this->Resource->create_json(201, __('The Trunk [' . $name . '] is modified successfully!', true)));
            }
            else
            {
                $sql = "insert into resource (alias, client_id, {$trunk_type},trunk_type2) values ('$name', $client_id, true, 1) returning resource_id";
                //echo $sql;die;
                $result = $this->Resource->query($sql);
                $sql = "insert into resource_ip (ip, resource_id) values (network('$ip'), {$result[0][0]['resource_id']})";
                $this->Resource->query($sql);
                $sql = "insert into resource_direction (direction, action, digits, resource_id, type) values (2, 1, {$prefix}, {$result[0][0]['resource_id']}, 1)";
                $this->Resource->query($sql);
                $this->Session->write('m', $this->Resource->create_json(201, __('The Trunk [' . $name . '] is created successfully!', true)));
            }
            $this->xredirect("/did/orders/{$trunk_type}_trunk");
        }
        $this->data = $this->Resource->find('first',
            array(
                'fields'     => array('Resource.resource_id', 'Resource.alias', 'ResourceIp.ip', 'ResourceDirection.digits'),
                'conditions' => array('Resource.resource_id' => $id),
                'joins'      => array(
                    array(
                        'table' => 'resource_ip',
                        'alias' => "ResourceIp",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceIp.resource_id = Resource.resource_id',
                        ),
                    ),
                    array(
                        'table' => 'resource_direction',
                        'alias' => "ResourceDirection",
                        'type'  => 'LEFT',
                        'conditions' => array(
                            'ResourceDirection.resource_id = Resource.resource_id',
                        ),
                    ),
                ),
            )
        );
        $this->set('id', $id);
    }
    
    public function delete_trunk($id)
    {
        $this->clear_display();
        $data = $this->Resource->find('first',
            array(
                'fields'     => array('Resource.resource_id', 'Resource.alias', 'ResourceIp.ip', 'ResourceDirection.digits'),
                'conditions' => array('Resource.resource_id' => $id),
            )
        );
        $sql = "delete from resource_direction where resource_id = {$id};delete from resource_ip where resource_id = {$id};delete from resource where resource_id = {$id};";
        $this->Resource->query($sql);
        $this->Session->write('m', $this->Resource->create_json(201, __('The Trunk [' . $data['Resource']['alias'] . '] is created successfully!', true)));
        $this->xredirect("/did/orders/ingress_trunk");
    }
    
    public function report($type="buy")
    {
        $client_id = $this->Session->read('sst_client_id');
        
        $this->pageTitle = "Trunk Management/Orig. Service";
        
        $this->set('cdr_field', $this->Cdr->find_field());
        $this->set('currency', $this->Cdr->find_currency1());
        $this->set('rate_type', 'all');

        $t = getMicrotime();
        
        extract($this->Cdr->get_real_period());
        
        $report_type = 'cdr_search';
        
        $where = $this->capture_report_condtions($report_type);
        $spam_where = '';
        if ($report_type == 'spam_report') {
            $spam_where = "and  release_cause = 3 ";
        }
        $where = $where . $spam_where;
        extract($this->capture_report_join($report_type, ''));
        $order = $this->capture_report_order();
        
        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3') {
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            if ($cdr_type == 'buy') {
                $cdr_type_active = 'orig_service_buy';
                $this->pageTitle = "Reports/Orig. Service Buy";
            } else {
                $cdr_type_active = 'orig_service_sell';
                $this->pageTitle = "Reports/Orig. Service Buy";
            }
            $this->set('cdr_type', $cdr_type_active);
        }



        /*$release_cause = "case  release_cause
	 	when    0    then   'Invalid Argument'
                when    1     then   'System Limit Exceeded'
                when    2     then   'SYSTEM_CPS System Limit Exceeded'
                when    3     then   'Unauthorized IP Address'
                when    4     then   ' No Ingress Resource Found'
		when    5     then   'No Product Found '
		when    6     then   'Trunk Limit CAP Exceeded'
		when    7     then   'Trunk Limit CPS Exceeded'
		when    8     then   'IP Limit  CAP Exceeded'
		when    9     then   'IP Limit CPS Exceeded 	'
		when   10    then   'Invalid Codec Negotiation'
		when   11    then   'Block due to LRN'
		when   12 			then  'Ingress Rate Not Found'  
		when   13 			then  ' Egress Trunk Not Found'  
		when   14 			then  'From egress response 404'  
		when   15 			then  'From egress response 486 '  
		when   16 			then  'From egress response 487 	'  
		when   17 			then  'From egress response 200 '  
		when   18 			then  'All egress not available'  
		when   19 			then  'Normal hang up' 
		when   20 			then  'Ingress Resource disabled'   
		when   21 			then  'Balance Use Up'   
		when   22 			then  'No Routing Plan Route'   
		when   23 			then  'No Routing Plan Prefix'   
		when   24 			then  'Ingress Rate No configure'
		when   25                     then 'Invalid Codec Negotiation'
		when   26                     then 'No Codec Found'
		when   27                     then 'All Egress Failed'
		when   28                     then 'LRN response no exist DNIS'
		when   29    then 'Carrier CAP Limit Exceeded'
		when   30    then 'Carrier CPS Limit Exceeded'
		when   31   then 'Host Alert Reject'
		when   32   then 'Resource Alert Reject'
		when   33   then 'Resource Reject H323'
		when   34   then '180 Negotiation SDP Failed'
		when   35   then '183 Negotiation SDP Failed'
		when   36  then '200 Negotiation SDP Failed'
		when   37  then 'LRN Block Higher Rate'
           
		else    'other'  end  as
		release_cause";*/
        $release_cause = " release_cause ";
        
        $trunk_type = "case trunk_type when 1 then 'class4' when 2 then 'exchange' end as trunk_type";

        $binary_value_of_release_cause_from_protocol_stack = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";

        //default  
        if (empty($show_field)) {

            //默认的 显示字段

            $show_field = "id,call_duration,origination_destination_host_name,trunk_id_termination,trunk_id_origination,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call,{$trunk_type}";
            if ($report_type == 'spam_report') 
                $show_field = "origination_destination_number,origination_source_host_name,origination_source_number,time";
        }

        if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == '3') {
            /* $show_field_array=array(
              'origination_source_host_name', 'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
              ); 
            */
            $show_field_array = array(
                'origination_source_number', 'origination_destination_number', 'call_duration', 'time', 'release_cause', 'binary_value_of_release_cause_from_protocol_stack'
            );
            $show_field_array_bak = array_keys($this->Cdr->find_client_cdr_field());
            $show_field_array = array_intersect($show_field_array, $show_field_array_bak);
            $show_field_array = explode(',',implode(',',$show_field_array));
            //$show_field_array = array_keys($this->Cdr->find_client_cdr_field());
            $show_field = implode(',',  $show_field_array);
        } else {
            $show_field_array = array('call_duration', 'trunk_id_termination', 'trunk_id_origination', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name', 'trunk_type');
            if ($report_type == 'spam_report') 
                $show_field_array = array('origination_destination_number','origination_source_host_name','origination_source_number','time');
        }
        //cdr 显示字段
        if (isset($_GET ['query'] ['fields'])) {
            $show_field = '';
            $show_field_array = $_GET ['query'] ['fields'];
            $sql_field_array = $show_field_array;
            $sql_field_array = $this->sql_field_array_help($sql_field_array);
            if (!empty($sql_field_array)) {
                $show_field = join(',', $sql_field_array);
            }
        }
        

        $this->set('show_field_array', $show_field_array);
        #other  report cdr
        if (isset($this->params['pass'][0])) {
            #查看client的cdr
            if ($this->params['pass'][0] == 'client') {
                $this->pageTitle = "Statistics/CDR Search ";
                if (!empty($this->params['pass'][1])) {
                    $where.= " and (ingress_client_id='{$this->params['pass'][1]}'  or  egress_client_id='{$this->params['pass'][1]}')";
                }
            }
            #查看断开码对应的cdr
            if ($this->params['pass'][0] == 'disconnect') {

                if (!empty($this->params['pass'][1])) {
                    if ($this->params['pass'][2] == 'org') {

                        $where.= "and   release_cause ='{$this->params['pass'][3]}'  and    binary_value_of_release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                    } else {
                        //$where.= " and   release_cause ='{$this->params['pass'][3]}' and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";  //断开码条件
                        //$where.= " and   release_cause  is null and release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'";
                        $where.= "and   release_cause ='{$this->params['pass'][3]}'  and    release_cause_from_protocol_stack like '%{$this->params['pass'][1]}%'"; 
                        
                    }
                }
            }
            #download mismatch cdr
            if ($this->params['pass'][0] == 'mismatch') {
                if ($this->params['pass'][1] == 'unknowncarriers') {
                    $where.= " and ingress_client_bill_result='2'";
                }
                if ($this->params['pass'][1] == 'unknownratetable') {
                    $where.= " and ingress_client_bill_result='3'";
                }
                if ($this->params['pass'][1] == 'unknownrate') {
                    $where.= " and ingress_client_bill_result='4'";
                }
            }
        }


        if (!empty($process_field)) {
            $show_field = $process_field;
        }
        $count_sql = "select count(*) as c from   client_cdr $join    where  $where and t_trunk_type2 = 1";
        $org_sql = "select $show_field  from   client_cdr $join     where   $where and t_trunk_type2 = 1  $order  ";
        
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once MODELS.DS.'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        $org_page_sql = $org_sql . $page_where;

        $this->set('show_nodata', true);

        if (isset($_GET ['query'] ['output'])) {
            //下载
            $file_name = $this->create_doload_file_name('cdr', $start, $end);

            if ($_GET ['query'] ['output'] == 'csv') {
                //Configure::write('debug',0);
                $this->_catch_exception_msg(array('CdrreportsController', '_download_impl'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            } elseif ($_GET ['query'] ['output'] == 'xls') {
                //xls down
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('CdrreportsController', '_download_xls'), array('download_sql' => $org_sql, 'file_name' => $file_name));
            } elseif ($_GET ['query'] ['output'] == 'email') {
                $this->connect_back_sender($where, $show_field, $show_field_array);
                exit;
            } {
                //web显示
                $results = $this->Cdr->query($org_page_sql);
                $page->setDataArray($results);
                $this->set('p', $page);
            }
        } else {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            
            if($is_preload)
            {
            
            $results = $this->Cdr->query($org_page_sql);
            }
            else
            {
                $this->set('show_nodata', false);
                $results = array();
            }
            $page->setDataArray($results);
            $this->set('p', $page);
        }
        
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $this->set("report_type", $report_type);
    }
    
     public function connect_back_sender($where, $fields, $show_field_array) {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT email FROM users where user_id = {$user_id}";
        $result = $this->Cdr->query($sql);
        $email = $result[0][0]['email'];
        $field_names = implode(',', $show_field_array);
        
        $script_path = Configure::read('script.path');
       
        
        $script_file = $script_path .DS . 'class4_cdr_down.pl';
        $script_log =  Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_log} -t \"{$email}\" -i {$user_id} -s \"{$where}\" -d \"{$fields}\" -l \"{$field_names}\" -m '{$_GET ['start_date']} {$_GET ['start_time']}' -n '{$_GET ['stop_date']} {$_GET ['stop_time']}' -p '{$_GET ['query']['tz']}'  &";
        shell_exec($cmd);
        $this->Cdr->create_json_array('#query-smartPeriod', 201, __('Succeed!', true));
        $this->Session->write("m", Cdr::set_validator());
        $this->redirect('/did/orders/report');
    }
    
}
