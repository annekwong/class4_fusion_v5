<?php

class ProductManagementController extends AppController
{

    var $name = 'ProductManagement';
    var $uses = Array('ProductRouteRateTable','ProductClientsRef', 'ProductAgentsRef', 'Systemparam');
    var $helpers = array('javascript', 'html', 'common');
    var $components = array('RequestHandler');

    public function index()
    {
        $this->pageTitle = 'Management/Product';
        $conditions = array();
        $this->paginate = array(
            'fields' => 'ProductRouteRateTable.product_name,ProductRouteRateTable.description,ProductRouteRateTable.update_on,ProductRouteRateTable.update_by,
            ProductRouteRateTable.tech_prefix,RateTable.name,RouteStrategy.name,ProductRouteRateTable.route_strategy_id,ProductRouteRateTable.rate_table_id,ProductRouteRateTable.id,ProductRouteRateTable.is_private',
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ProductRouteRateTable.rate_table_id',
                    )
                ),
                array(
                    'table' => 'route_strategy',
                    'alias' => 'RouteStrategy',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RouteStrategy.route_strategy_id = ProductRouteRateTable.route_strategy_id',
                    )
                )
            ),
            'order' => array(
                //'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('ProductRouteRateTable');
        // carriers
        $sql = "select client_id ,name from client where status=true AND client_type is null AND name != '' order by name ASC ";
        $r = $this->ProductRouteRateTable->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['client_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        $this->set('carriers',$l);
    }

    public function add_edit($id = ''){

        if(!empty($_POST)){
            $save_data = $this->data['ProductRouteRateTable'];
            $save_data['update_by'] = $_SESSION['sst_user_name'];
            $conditions = array('product_name' => $save_data['product_name']);

            if(!empty($_POST['product_id'])){
                $save_data['id'] = $_POST['product_id'];
                $conditions['NOT']['id'] = $_POST['product_id'];
            }

            $product_name_exist = $this->ProductRouteRateTable->find('count',array('conditions' => $conditions));
            if($product_name_exist !=0 && !$id){
                 $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('Product Name[' . $save_data['product_name'] . '] is already in use!', true)));
                $this->redirect('add_edit');
            }



            $existingRecords = $this->ProductRouteRateTable->find('first', array(
               'conditions' =>  $conditions
            ));

            if(!empty($existingRecords)) {
                $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('The product [' . $save_data['product_name'] . '] is already in use!', true)));
                $this->redirect('index');
            }

            $this->ProductRouteRateTable->begin();
            $this->ProductRouteRateTable->save($save_data);
            // update related data
            if (isset($save_data['id'])) {
                $this->updateRelatedData($save_data);
            }

            //保存product_clients_ref
            if(empty($_POST['product_id'])) {
                $_POST['product_id'] = $this->ProductRouteRateTable->getLastInsertID();
            }

            $assign_ids = $_POST['data']['ProductRouteRateTable']['assign_ids'];
            $assign_agent_ids = $_POST['data']['ProductRouteRateTable']['assign_agent_ids'];
            $sql = "delete from product_clients_ref where product_id = {$_POST['product_id']}";
            $this->ProductRouteRateTable->query($sql);
            $sql = "delete from product_agents_ref where product_id = {$_POST['product_id']}";
            $this->ProductRouteRateTable->query($sql);

            if($_POST['data']['ProductRouteRateTable']['is_private'] && (!empty($assign_ids) || $assign_agent_ids)){
                // get client by agent ids
                if(!empty($assign_agent_ids)){

                    $this->loadModel('AgentClients');

                    foreach($assign_agent_ids as $aid){
                        $tmp = array();
                        $tmp['product_id'] = $_POST['product_id'];
                        $tmp['agent_id'] = $aid;
                        $data[] = $tmp;
                    }
                    $this->ProductAgentsRef->saveAll($data);

                    $agent_client_ids = $this->AgentClients->getClientByAgents($assign_agent_ids);
                    $assign_ids = array_unique(array_merge($assign_ids, $agent_client_ids));
                }

                $data = array();

                foreach($assign_ids as $aid){
                    $tmp = array();
                    $tmp['product_id'] = $_POST['product_id'];
                    $tmp['client_id'] = $aid;
                    $data[] = $tmp;
                }
                $this->ProductClientsRef->saveAll($data);
            }

            $this->ProductClientsRef->commit();
            if($id){
                $action = 'modified';
            }
            else{
                $action = 'created';
            }
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('Product [' . $save_data['product_name'] . ']' . $action . ' successfully!', true)));
            $this->redirect('index');
        }

        $this->pageTitle = 'Management/Product/Add';
        $this->data = array();
        $this->loadModel('Agent');

        if(!empty($id)){
            $this->data = $this->ProductRouteRateTable->findById($id);

            // clients
            $rst = $this->ProductClientsRef->findAllByProductId($id);
            $assign_ids = array();
            foreach($rst as $item){
                $assign_ids[] = $item['ProductClientsRef']['client_id'];
            }
            $this->data['ProductRouteRateTable']['assign_ids'] = $assign_ids;

            // agents
            $rst = $this->ProductAgentsRef->findAllByProductId($id);
            $assign_agent_ids = array();
            foreach($rst as $item){
                $assign_agent_ids[] = $item['ProductAgentsRef']['agent_id'];
            }
            $this->data['ProductRouteRateTable']['assign_agent_ids'] = $assign_agent_ids;

        }

        $sql = "select client_id ,name from client where status=true order by name ASC ";
        $r = $this->ProductRouteRateTable->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['client_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        $routePlan = $this->ProductRouteRateTable->find_routepolicy();
        $rateTables = $this->ProductRouteRateTable->find_all_rate_table();
        $selectedRateTable = isset($this->data['ProductRouteRateTable']['rate_table_id']) ? $this->data['ProductRouteRateTable']['rate_table_id'] : '';
        $selectedRouteStrategy = isset($this->data['ProductRouteRateTable']['route_strategy_id']) ? $this->data['ProductRouteRateTable']['route_strategy_id'] : '';
        $this->set('carriers',$l);
        $this->set('agents',$this->Agent->get_agents());
        $this->set('route_plan', $routePlan);
        $this->set('rate_table', $rateTables);
        $this->set('product_id',$id);
        $this->set('selectedRateTable', $selectedRateTable);
        $this->set('selectedRouteStrategy', $selectedRouteStrategy);
    }


    public function product_edit_panel($product_id = '')
    {
        Configure::write('debug',0);
        if($product_id)
            $this->data = $this->ProductRouteRateTable->findById($product_id);
        $this->set('route_plan',$this->ProductRouteRateTable->find_routepolicy());
        $this->set('rate_table',$this->ProductRouteRateTable->find_all_rate_table());
    }

    public function save_product($product_id = '')
    {
        Configure::write('debug',0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $save_data = $this->data['ProductRouteRateTable'];
        $save_data['update_by'] = $_SESSION['sst_user_name'];
        if($product_id)
            $save_data['id'] = $product_id;
        $flg = $this->ProductRouteRateTable->save($save_data);
        if ($flg === false)
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('Failed!', true)));
        else
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('Succeed!', true)));
        $this->redirect('index');
    }

    public function ajax_check_product_name()
    {
        Configure::write('debug',0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $name = $_POST['product_name'];
        $prefix = $_POST['prefix'];
        $id = intval($_POST['product_id']);
        $count = $this->ProductRouteRateTable->judge_name_exist($name,$id);
        if($count)
            __('Product name exist');
        else
        {
            $count = $this->ProductRouteRateTable->judge_prefix_exist($name,$id);
            if($count)
                __('Prefix exist');
            else
                echo $count;
        }

    }

    public function delete_product($encode_product_id)
    {
        Configure::write('debug',0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $product_id = base64_decode($encode_product_id);
        $product_name = $this->ProductRouteRateTable->find('first',array('id',$product_id))['ProductRouteRateTable']['product_name'];
        $flg = $this->ProductRouteRateTable->del($product_id);

        $sql = "delete from product_clients_ref where product_id = {$product_id}";
        $this->ProductRouteRateTable->query($sql);
        if ($flg === false)
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('Failed!', true)));
        else
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('The product [' . $product_name . '] is deleted successfully!', true)));
        $this->redirect('index');
    }

    public function carrier_product_list()
    {
        /*$is_change_product = Configure::read('portal.change_product');
        if(!$is_change_product){
            $this->redirect('/clients/carrier_dashboard');
        }*/

        if($this->RequestHandler->ispost()){
            $return_url = $this->params['url']['url'] . '&' . $this->params['getUrl'];
            $product_ids = $_POST['myModal_product_id'];
            $sip_ips = $_POST['myModal_sip_ip'];
            $accounts = $_POST['accounts'];
            if(empty($product_ids) || empty($sip_ips) || empty($accounts)){
                $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('Failed!', true)));
                $this->redirect("/$return_url");
            }
            $productName = '';
            foreach ($product_ids as $product_id) {
                $product_info = $this->ProductRouteRateTable->findAllById($product_id);
                $route_strategy_ids[] = $product_info[0]['ProductRouteRateTable']['route_strategy_id'];
                $rate_table_ids[] = $product_info[0]['ProductRouteRateTable']['rate_table_id'];
                $tech_prefixes[] = $product_info[0]['ProductRouteRateTable']['tech_prefix'];
                $productName .= $product_info[0]['ProductRouteRateTable']['product_name'] . "_";
            }

            $rst = $this->ProductRouteRateTable->query("select orig_rate_table_id,profit_margin,enough_balance,client.client_id,client.name from client left join users on client.user_id = users.user_id where users.user_id = {$_SESSION['sst_user_id']}");
            $client_id = $rst[0][0]['client_id'];
            $client_name = $rst[0][0]['name'];
//            $ingress_name = 'portal_' . $client_name . '_' . date('Ymd_His');
//            $ingress_name = $_POST['myModal_trunk_name'];
            $ingress_name = $client_name . '_' . $productName . $client_id;


            $profit_margin = $rst[0][0]['profit_margin'];
            $enough_balance = $rst[0][0]['enough_balance'] ? 'true' : 'false';
            $data = array();
            $data['Gatewaygroup']['client_id'] = $client_id;
            $data['Gatewaygroup']['alias'] = $ingress_name;
            $data['Gatewaygroup']['ingress'] = true;
            $data['Gatewaygroup']['egress'] = false;
            //$data['Gatewaygroup']['route_strategy_id'] = $route_strategy_id;
            //$data['Gatewaygroup']['rate_table_id'] = $rate_table_id;
            $data['Gatewaygroup']['enough_balance'] = $enough_balance;
//            $data['Gatewaygroup']['update_at'] = date("Y-m-d H:i:s");
//            $data['Gatewaygroup']['update_by'] = $_SESSION['sst_user_name'];

            for ($i = 0; $i < count($product_ids); $i++) {
                $product_id = $product_ids[$i];
                $sip_ip = $sip_ips[$i];
                $tech_prefix = $tech_prefixes[$i];
                $rate_table_id = $rate_table_ids[$i];
                $route_strategy_id = $route_strategy_ids[$i];
                $_POST['resource']['id'] = array();
                $_POST['resource']['id'][] = '';
                $_POST['resource']['tech_prefix'] = array();
                $_POST['resource']['tech_prefix'][] = $tech_prefix;
                $_POST['resource']['product_id'] = array();
                $_POST['resource']['product_id'][] = $product_id;
                $_POST['resource']['rate_table_id'] = array();
                $_POST['resource']['rate_table_id'][] = $rate_table_id;
                $_POST['resource']['route_strategy_id'] = array();
                $_POST['resource']['route_strategy_id'][] = $route_strategy_id;
                $_POST['reg_type'] = 0;

                Configure::write('debug', 0);
                $this->loadModel('prresource.Gatewaygroup');
//                $this->Gatewaygroup->begin();
                // echo "<pre>";
                // die(var_dump($data, $_POST, $this->Gatewaygroup->saveOrUpdate_resource($data,$_POST)));
                $res_id = $this->Gatewaygroup->saveOrUpdate_resource($data, $_POST);
                $this->Gatewaygroup->saveHost($accounts, $res_id, 0);
                $this->Gatewaygroup->saveResouce($_POST['resource'], $res_id);

                //保存sip_ip
                //$sip_ip = $sip_ip;
                if (!empty($sip_ip)) {
                    $sip_ip = explode(':', $sip_ip);
                    if (count($sip_ip) == 2) {
//                        $this->Gatewaygroup->query("
//                          insert into allowed_sendto_ip (resource_id,direction,sip_profile_ip,sip_profile_port)
//                         select {$res_id},0,'{$sip_ip[0]}',{$sip_ip[1]}
//                         WHERE NOT EXISTS (SELECT * from allowed_sendto_ip where resource_id = {$res_id} and direction = 0 and sip_profile_ip = '{$sip_ip[0]}' and  sip_profile_port = {$sip_ip[1]} )  ");
                    }
                }

//                $this->Gatewaygroup->commit();
            }

            Configure::write('debug',0);
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('Success!', true)));
            /*//保持trunk
            $this->ProductRouteRateTable->query("insert into resource(alias,client_id,ingress,egress,route_strategy_id,rate_table_id,enough_balance,update_at,update_by) values('$ingress_name',$client_id,true,false,$route_strategy_id,$rate_table_id,$enough_balance,CURRENT_TIMESTAMP(0),'$client_name')");
            $res_id = $this->ProductRouteRateTable->getlastinsertId();
            //保存ip
            $count = count($accounts['ip']);*/


        }
        $order_arr = array('ProductRouteRateTable.product_name' => 'ASC');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $rst = $this->ProductRouteRateTable->query("select client.client_id from client left join users on client.user_id = users.user_id where users.user_id = {$_SESSION['sst_user_id']}");
        $client_id = 0;
        if (!empty($rst)) {
            $client_id = $rst[0][0]['client_id'];
            if (!$client_id && $_SESSION['carrier_panel']['Client']['user_id']) {
                $rst = $this->ProductRouteRateTable->query("select client.client_id from client left join users on client.user_id = users.user_id where users.user_id = {$_SESSION['carrier_panel']['Client']['user_id']}");
                $client_id = $rst[0][0]['client_id'];
            }
        }
        $this->pageTitle = 'Management/Product';
        $conditions = 'ProductRouteRateTable.is_private = false';
        if ($client_id) {
            $conditions .= ' or ProductRouteRateTable.id in (select product_id from product_clients_ref where client_id = '.$client_id.')';
        }
        $this->paginate = array(
            'fields' => array('ProductRouteRateTable.product_name','ProductRouteRateTable.description','ProductRouteRateTable.id',
                'ProductRouteRateTable.tech_prefix','ProductRouteRateTable.rate_table_id'),
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $products = $this->ProductRouteRateTable->find('all', array(
            'fields' => 'ProductRouteRateTable.product_name,ProductRouteRateTable.description,ProductRouteRateTable.update_on,ProductRouteRateTable.update_by,
            ProductRouteRateTable.tech_prefix,RateTable.name,RouteStrategy.name,ProductRouteRateTable.route_strategy_id,ProductRouteRateTable.rate_table_id,ProductRouteRateTable.id,ProductRouteRateTable.is_private',
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ProductRouteRateTable.rate_table_id',
                    )
                ),
                array(
                    'table' => 'route_strategy',
                    'alias' => 'RouteStrategy',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RouteStrategy.route_strategy_id = ProductRouteRateTable.route_strategy_id',
                    )
                )
            ),
            'order' => array(
                //'id' => 'desc',
            )
        ));

        $resource_ip_arr = [];
        $pr_name = [];
        $this->loadModel('prresource.Gatewaygroup');
        foreach($products as $product){
            $pr_name[$product['ProductRouteRateTable']["id"]] = $product['ProductRouteRateTable']['product_name'];
            $resource_ip_arr[$product['ProductRouteRateTable']["id"]] = $this->Gatewaygroup->get_resource_ip_by_product_id($product['ProductRouteRateTable']["id"]);
        }
        $this->data = $this->paginate('ProductRouteRateTable');
        $voip_arr = $this->ProductRouteRateTable->query("select sip_port,sip_ip from switch_profile left join voip_gateway on switch_profile.voip_gateway_id = voip_gateway.id");
        $this->set('voip_arr',$voip_arr);
        $this->set('products',$products);
        $this->set('resource_ip_arr',$resource_ip_arr);
        $this->set('pr_name',$pr_name);
        $this->set('client_id',$client_id);
    }

    public function ajax_check_alias(){
        Configure::write('debug',0);
        $alias = $_GET['alias'];
        $this->loadModel('prresource.Gatewaygroup');
        $rst = $this->Gatewaygroup->check_alias(null,$alias);
        if($rst){
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }

    public function send_rate(){

        $product_id = '';
        if ($this->_get('product')){
            $product_id = base64_decode($this->_get('product'));
        }
        // send rate product page
        if ($product_id) {
            $this->loadModel('Rate');
            $this->loadModel('RateTable');
            $this->loadModel('Mailtmp');
            $preservedData = array('email_cc' => '', 'subject' => '', 'content' => '',
                'format' => '', 'zipped' => '', 'start_effective_date' => '', 'email_template' => '');
            $this->set('preservedData', $preservedData);

            $product_info = $this->ProductRouteRateTable->findById($product_id);
            if (!$product_info){
                $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('product not exists', true)));
                $this->redirect("send_rate");
            }

            $rate_table_id = $product_info['ProductRouteRateTable']['rate_table_id'];
            $sql = "SELECT name,jur_type FROM rate_table WHERE rate_table_id = {$rate_table_id}";
            $rate_info = $this->Rate->query($sql);
            $schema = $this->RateTable->get_schema($rate_info[0][0]['jur_type']);
            $options = array();
            $default_fields = array();
            foreach($schema as $field_name => $value){
                $options[$field_name] =  isset($value['name']) ?  Inflector::humanize($value['name']) :  Inflector::humanize($field_name);
                if(isset($value['default_fields']))
                    $default_fields[] =  $field_name;
            }
            $this->set('schema',$options);
            $this->set('default_fields',$default_fields);
            $this->set('rate_table_id',$rate_table_id);
            $this->set('product_name',$product_info['ProductRouteRateTable']['product_name']);
            $this->set('is_private',$product_info['ProductRouteRateTable']['is_private'] ? 1 : 0);
            $mail_senders = $this->Mailtmp->get_mail_senders();
            $this->set('mail_senders', $mail_senders);
        }


        $this->set('product_id',$product_id);
        $this->set('product_list',$this->ProductRouteRateTable->product_route_rate_table());
        $rate_email_template = $this->Mailtmp->find_all_rate_email_template();
        $rate_email_template['save_temporary'] = __('Do Not Use Template',true);
        $this->set('rate_email_template', $rate_email_template);


    }

    public function send_rate_record($product_id = null)
    {
        Configure::write('debug', 0);
        if (!$product_id){
            $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('product not exists', true)));
            $this->redirect("send_rate");
        }
        $this->loadModel('Rate');
        $download_method = isset($_POST['data']['download_method']) ? $_POST['data']['download_method'] : 0;
        $extra_params = [
            'content' => base64_encode($this->data['content']),
            'subject' => base64_encode($this->data['subject']),
            'email_cc' =>  $this->data['email_cc']
        ];
        $extra_params = escapeshellarg(serialize($extra_params));

        $this->autoRender = false;
        $this->autoLayout = false;
        $post_data = $_POST;
        $rate_table_id = $post_data['rate_table_id'];
        $send_type = $this->_post('send_type');

        // deadline when sending to own rec.
        if($send_type == 1){
            $post_data['download_deadline'] = date('Y-m-d', strtotime('+1 year'));
        }

        if (empty($this->data['content']) || empty($this->data['subject']))
        {
            $this->Rate->create_json_array('', 101, __('The information should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/product_management/index");
        }
        if (empty($post_data['resources']) && !$send_type)
        {
            $this->Rate->create_json_array('', 101, __('There is no Carrier using this rate table!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/product_management/index");
        }

        if (!$this->isnotEmpty($post_data, array('format', 'rate_table_id')))
        {
            $this->Rate->create_json_array('', 101, __('The keyword not found!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/product_management/index");
        }

        if( ($_POST['data']['download_method'] == 2) && (empty($post_data['download_deadline']) )){
            $this->Rate->create_json_array('', 101, __('The Field download_deadline should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/product_management/index");
        }
        // rate_id | email_cc | subject | content
        $this->loadModel('SendRatePreservedData');

        $preservedDataExist = $this->SendRatePreservedData->find('count',array(
            'conditions' => array(
                'rate_id' => $rate_table_id
            ),
        ));
        $temp_id = $_POST['data']['email_template'];
        if (strcmp($temp_id,'save_temporary')){
            $temp_id = 0;
        }

        $_POST['zipped'] = isset($_POST['zipped']) ? $_POST['zipped'] : 'false';

        if (!is_null($preservedDataExist) && $preservedDataExist) { // update existing data
            $this->SendRatePreservedData->query("UPDATE send_rate_preserved_data
                SET email_cc = '{$this->data['email_cc']}', subject = '{$this->data['subject']}', content = '{$this->data['content']}',
                format = '{$_POST['format']}', zipped = '{$_POST['zipped']}', start_effective_date = '{$_POST['start_effective_date']}', email_template = '{$temp_id}'
                WHERE rate_id = {$rate_table_id}");
        } else { // add new record
            $this->SendRatePreservedData->query("INSERT INTO send_rate_preserved_data(rate_id, email_cc, subject, content, format, zipped, start_effective_date, email_template)
                VALUES({$rate_table_id}, '{$this->data['email_cc']}', '{$this->data['subject']}', '{$this->data['content']}', '{$_POST['format']}', '{$_POST['zipped']}', '{$_POST['start_effective_date']}', '{$temp_id}')");
        }

        $flg_zip = 0;
        if (isset($post_data['zipped']))
        {
            $flg_zip = 1;
        }

        // get client emails
        $client_emails = [];
        foreach($post_data['client_emails'] as $res_email){
          $res_email_info = explode('::', $res_email);
          if(!empty($res_email_info) && in_array($res_email_info[0], $post_data['resources'])){
              $client_emails[] = $res_email_info[1];
          }
        }
        $start_effective_date = isset($post_data['start_effective_date']) && $post_data['start_effective_date'] ? $post_data['start_effective_date'] : date('Y-m-d',strtotime('+1 day'));
        $email_template = $post_data['data']['email_template'];
        $download_deadline = isset($post_data['download_deadline']) ? $post_data['download_deadline'] : '';
        $is_email_alert = isset($post_data['is_email_alert']) ? true : false;
        $is_disable = isset($post_data['is_disable']) ? true : false;
        $send_specify_email = isset($post_data['send_specify_email']) && $post_data['send_specify_email'] ? $post_data['send_specify_email'] : '';
        $send_specify_email = $send_specify_email ?: implode(';', array_unique($client_emails));
        $resource_id_unique = array_unique($post_data['resources']);
        // only one resource when sending to own recipient
        if ($send_type){
            $resource_id_unique = [$resource_id_unique[0]];
        }
        
        $new_email_template_arr = $this->data;
        if(!$email_template || !strcmp($email_template,'save_temporary'))
        {
            $new_email_template_arr['name'] = "rate_email_template".time();
        }


        $headers = $this->data['headers'];
        if(isset($headers['change_status'])) {
            unset($headers['change_status']);
        }
        $headers = implode(',', $headers);
        $new_email_template_arr['headers'] = $headers;

        $this->loadModel('RateEmailTemplate');

        $flg = $this->RateEmailTemplate->save($new_email_template_arr);
        if($flg === false)
        {
            $this->Rate->create_json_array('', 101, __('Failed', true));
            $this->Session->write('m', Rate::set_validator());

            $this->redirect("/product_management/index");
        }

        $email_template = $this->RateEmailTemplate->getLastInsertID();

        $format = $post_data['format'];
        $rate_table_id = $post_data['rate_table_id'];

        $RateSendLogArr = array(
            'rate_table_id' => $rate_table_id,
            'format' => $format,
            'zip' => $flg_zip,
            'status' => 1,
            'email_template_id' => $email_template,
            'create_time' => date('Y-m-d H:i:sO'),
            'start_effective_date' => $start_effective_date,
            'download_deadline' => $download_deadline,
            'download_method' => $download_method,
            'is_email_alert' => $is_email_alert,
            'is_disable' => $is_disable,
            'is_temp' => false,
            'headers' => $headers,
            'send_type' => $send_type,
            'send_specify_email' => $send_specify_email,
            'resource_ids' => implode(',',$resource_id_unique),
            'sent_area' => 2
        );
        $this->loadModel('RateSendLog');
        $insert_flg = $this->RateSendLog->save($RateSendLogArr);
        if ($insert_flg === false)
        {
            $this->Rate->create_json_array('', 101, __('Insert log failed!', true));
            $this->Session->write('m', Rate::set_validator());

            $this->redirect("/product_management/index");
        }
        $log_id = $this->RateSendLog->getLastInsertID();

        $php_path = Configure::read('php_exe_path');

        $cmd = APP . "../cake/console/cake.php ratesend {$log_id} $download_method $extra_params > /dev/null &";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));
        if(Configure::read('cmd.debug'))
        {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }
        $res = shell_exec($cmd);
        $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('The email is sent in!', true)));
        $this->redirect("/rates/send_rate_log");
    }

    public function get_rate_client_user_info(){
        Configure::write('debug',0);
        if ($this->_post('is_private')) {
            $client_list = $this->ProductRouteRateTable->get_client_list_by_product($this->_post('product_id'),$this->_post('carrier_type'));
        } else {
            $client_list = $this->ProductRouteRateTable->get_client_list_by_product_public($this->_post('product_id'),$this->_post('carrier_type'));
        }
        $this->set('client_list', $client_list);
    }

    public function assign()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $post = $this->params['form'];
        if (!$post['product_id']){
            $this->jsonResponse(['status'=> false, 'msg'=> 'Product does not exist!']);
        }
        if (!$post['client_id']){
            $this->jsonResponse(['status'=> false, 'msg'=> 'You must select Carrier.']);
        }
        // resource creating ...
        $this->loadModel('prresource.Gatewaygroup');
        $resource_id = $this->createResource($post['product_id'], $post['client_id'], $post['trunk_name']);
        //pre($resource_id);
        if($resource_id == 'fail'){
            $this->jsonResponse(['status'=> false, 'msg'=> 'Resource creating failed!']);
        }
        // client product ref.
        $tmp = [];
        $tmp['product_id'] = $post['product_id'];
        $tmp['client_id'] = $post['client_id'];
        $flg = $this->ProductClientsRef->save($tmp);
        if($flg === false){
            $this->Gatewaygroup->query("delete from resource where resource_id=$resource_id");
            $this->jsonResponse(['status'=> false, 'msg'=> 'Product assigning failed!']);
        }
        // store resource IPs
        $ip_list = array_unique(array_filter($post['ip']));
        $port_list = array_unique(array_filter($post['port']));

        if(!empty($ip_list)){
            $this->loadModel('ResourceIp');
            foreach($ip_list as $key => $ip){
                $res_ip[] = array(
                    'ip' => $ip,
                    'port' => $port_list[$key] ? : 5060,
                    'resource_id' => $resource_id
                );
            }
            $this->ResourceIp->saveAll($res_ip);
        }


        $this->jsonResponse(['status'=> true, 'msg'=> 'Product assigned successfully!']);

    }

    private function createResource($product_id, $client_id, $alias)
    {
            Configure::write('debug', 0);
            if (!$this->judge_name($alias, 100))
            {
                $this->jsonResponse(['status'=> false, 'msg'=> 'Ingress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!']);
            }

            // get pr. info
            $sql = "select tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table  where id='{$product_id}'";
            $product_info = $this->ProductRouteRateTable->query($sql);
            if (empty($product_info)){
                $this->jsonResponse(['status'=> false, 'msg'=> 'Product does not exist!']);
            }

            $gateway_data = [];
            $resource = [];
            $post = [];
            $gateway_data["Gatewaygroup"]["ingress"] = true;
            $gateway_data["Gatewaygroup"]["egress"] = false;
            $gateway_data["Gatewaygroup"]["alias"] = $alias;
            $gateway_data["Gatewaygroup"]["client_id"] = $client_id;
            $gateway_data["Gatewaygroup"]["media_type"] = 2;
            $gateway_data["Gatewaygroup"]["select2"] = null;

            $resource["id"] = [];
            $resource["id"][] = "";
            $resource["code"] = [];
            $resource["code"][] = "";
            $resource["code_cps"] = [];
            $resource["code_cps"][] = "";
            $resource["code_cap"] = [];
            $resource["code_cap"][] = "";
            $resource["product_id"] = [];
            $resource["product_id"][] = $product_id;
            $resource["tech_prefix"] = [];
            $resource["rate_table_id"] = [];
            $resource["route_strategy_id"] = [];
            $resource["tech_prefix"][] = $product_info[0][0]["tech_prefix"];
            $resource["rate_table_id"][] = $product_info[0][0]["rate_table_id"];
            $resource["route_strategy_id"][] = $product_info[0][0]["route_strategy_id"];

            $post["data"] = $gateway_data;
            $post["resource"] = $resource;
            $post["t38"] = true;
            $post["reg_type"] = 0;

            $resource_id = $this->Gatewaygroup->saveOrUpdate($gateway_data, $post);
            if ($resource_id === true)
            {
                $this->jsonResponse(['status'=> false, 'msg'=> 'Resource creating failed!']);
            }
            return $resource_id;

    }

    private function updateRelatedData($data){
        $this->loadModel('ResourcePrefix');
        $sql = "UPDATE resource_prefix SET tech_prefix='{$data["tech_prefix"]}', rate_table_id='{$data["rate_table_id"]}', route_strategy_id='{$data["route_strategy_id"]}' WHERE product_id='{$data["id"]}'";
        $this->ResourcePrefix->query($sql);
    }

}
