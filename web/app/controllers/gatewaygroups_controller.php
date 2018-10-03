<?php

class GatewaygroupsController extends AppController
{

    var $name = 'Gatewaygroups';
    var $uses = Array('ServerPlatform', 'Gatewaygroup', 'Systemparam');
    var $defaultHelper = 'appGetewaygroup';
    var $helpers = array('javascript', 'html', 'appGetewaygroup', 'common');

    function index()
    {
        $this->redirect('egress_report');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
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
        parent::beforeFilter();
    }

    function init_codes($res_id)
    {
        $this->set('nousecodes', $this->Gatewaygroup->findNousecodecs($res_id));
        $this->set('usecodes', $this->Gatewaygroup->findUsecodecs($res_id));
    }

    public function download_codepart()
    {
        $rate_table_id = $this->params ['pass'] [0];
        $download_sql = "select    code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds
		from  rate  where rate_table_id=$rate_table_id";
        $this->Rate->export__sql_data('Download', $download_sql, 'rate');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    public function download_egress()
    {

        Configure::write('debug', 0);
        $download_sql = "select client.name  as  client_name ,alias, resource.name,ingress,egress,active,t38,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where egress=true ";
        $this->Gatewaygroup->export__sql_data('download Egress', $download_sql, 'egress');
        $this->layout = '';
    }

    public function download_ingress()
    {

        Configure::write('debug', 0);
        $download_sql = "select resource.name,ingress,client.name as  client_name ,egress,active,t38,alias,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where ingress=true ";
        $this->Gatewaygroup->export__sql_data(__('DownloadIngress', true), $download_sql, 'ingress');

        $this->layout = '';
    }

    public function view_cdr()
    {
        $this->set('p', $this->Gatewaygroup->view_all_cdr(
                        $this->params['pass'][0], $this->params['pass'][1], $this->_order_condtions(array('payment_time', 'amount', 'client_payment_id')
        )));
    }

    //查看指定号段的cdr
    public function code_cdr()
    {
        $start_code = $this->params ['pass'] [0];
        $end_code = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("select * from  cdr   where  origination_destination_number >'$start_code' and  origination_destination_number <'$end_code'");

        empty($_GET ['page']) ? $currPage = 1 : $currPage = $_GET ['page'];
        empty($_GET ['size']) ? $pageSize = 100 : $pageSize = $_GET ['size'];
        //模糊搜索
        if (isset($_POST ['searchkey']))
        {
            $results = $this->Gatewaygroup->likequery($_POST ['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST ['searchkey']);
            $this->set('p', $results);
            return;
        }
        //高级搜索 
        if (!empty($this->data ['Gatewaygroup']))
        {
            $results = $this->Gatewaygroup->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            //普通查询
            $results = $this->Gatewaygroup->code_cdr($currPage, $pageSize, $start_code, $end_code);
        }
        $this->set('p', $results);
    }

    public function del_all_codepart()
    {
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("delete from  code_part  where egress_id=$egress_id");

        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function del__codepart()
    {
        $id = $this->params ['pass'] [0];
        $this->Gatewaygroup->query("delete from  code_part  where code_part_id=$id");
        $egress_id = $_SESSION ['codepartengress'];
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function update_codepart()
    {
        $start_code = $_GET ['start'];
        $end_code = $_GET ['end'];
        $card = $_GET ['card'];
        $id = $_GET ['id'];
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("update  code_part set start_code='$start_code',end_code='$end_code',account_id=$card,egress_id=$egress_id
			   where code_part_id=$id");
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    //读取该模块的执行和修改权限

    function edit()
    {
        $this->init_info();
        empty($this->params ['pass'] [0]) ? $res_id = $_POST ['resource_id'] : $res_id = $this->params ['pass'] [0];
        $this->init__info_byResID($res_id);
        if (!empty($this->params ['form']))
        {
            $flag = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if (empty($flag))
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $ingress = $_POST ['_ingress'];
                $egress = $_POST ['_egress'];
                if ($ingress == 'true')
                {
                    $this->redirect(array('controller' => 'gatewaygroups', 'action' => 'view_ingress'));
                }
                else
                {
                    $this->redirect(array('controller' => 'gatewaygroups', 'action' => 'view_egress'));
                }
            }
        }
        else
        {
            $this->init_info();
        }
    }

    public function init__info_byResID($res_id)
    {
        $this->set('g', $this->Gatewaygroup->findResByres_id($res_id));
        $this->set('res_ip', $this->Gatewaygroup->findAllres_ip($res_id));
        $this->set('res_direct', $this->Gatewaygroup->findresdirectByRes_id($res_id));
        // $this->set('res_product',$this->Gatewaygroup->findresproductByRes_id($res_id));
        $this->set('user_codes', $this->Gatewaygroup->findUsecodecs($res_id));
        $this->set('nouser_codes', $this->Gatewaygroup->findNousecodecs($res_id));
    }

    function init_info()
    {
        $this->set('c', $this->Gatewaygroup->findClient());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
        $this->set('d', $this->Gatewaygroup->findcodecs());
        $this->set('p', $this->Gatewaygroup->findAllProduct());
        $this->set('rate', $this->Gatewaygroup->findAllRate());
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('route_policy', $this->Gatewaygroup->find_routepolicy());
        $this->loadModel('Blocklist');
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
        $this->loadModel('Client');
        $this->set('product', $this->Client->findAllProduct());
        $this->set('dyn_route', $this->Client->findDyn_route());
        $this->set('routepolicy', $this->Client->query("select * from route_strategy"));
    }

    function add()
    {
        if (!empty($this->params ['form']))
        {
            $flag = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if ($flag == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $ingress = $_POST ['_ingress'];
                $egress = $_POST ['_egress'];
                if ($ingress == 'true')
                {
                    $this->redirect(array('controller' => 'gatewaygroups', 'action' => 'view_ingress'));
                }
                else
                {
                    $this->redirect(array('controller' => 'gatewaygroups', 'action' => 'view_egress'));
                }
            }
        }
        else
        {
            $this->init_info();
        }
    }

    /*
     * 删除网关
     */

    function del()
    {
        $type = $this->params ['pass'] [1]; //类型        
        if ($this->Gatewaygroup->del($this->params ['pass'] [0]))
        {
            $this->Gatewaygroup->create_json_array('', 201, __('The trunk is deleted successly.', true));
        }
        else
        {
            $this->Gatewaygroup->create_json_array('', 101, __('Fail to delete trunk.', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
        $this->redirect(array('action' => $type));
    }

    function del_selected()
    {
        if ($this->Gatewaygroup->del($_REQUEST ['ids']))
        {
            $this->Gatewaygroup->create_json_array('', 201, __('Your options are deleted successfully!', true));
        }
        else
        {
            $this->Gatewaygroup->create_json_array('', 101, __('Your options are deleted unsuccessfully!', true));
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $type = $_REQUEST ['type'];
        $project_name = Configure::read('project_name');
        if ($project_name == 'exchange')
        {
            $this->redirect(array('action' => $type));
        }
        else
        {

            $this->redirect(array('plugin', 'action' => $type));
        }
    }

    /**
     * 查询ip
     */
    public function ajax_ip()
    {
        Configure::write('debug', 0);
        $list = $this->Gatewaygroup->query("select  digits from  resource_direction  where resource_id='{$this->params ['pass'] [0]}' order by direction_id");
        // direction
        $prefix_list = [];
        foreach($list as $prefix){
            if($prefix[0]['digits']){
                $prefix_list[] = $prefix[0]['digits'];
            }
        }
        //tech prefix
        $list = $this->Gatewaygroup->query("select  tech_prefix from  resource_prefix  where resource_id='{$this->params ['pass'] [0]}'");

        foreach($list as $prefix){
            if($prefix[0]['tech_prefix']){
                $prefix_list[] = $prefix[0]['tech_prefix'];
            }
        }
        $prefix_line = !empty($prefix_list) ? implode('/', $prefix_list) : '';
        $resource_ips = $this->Gatewaygroup->findAllres_ip($this->params ['pass'] [0]);
        foreach($resource_ips as &$item){
            $item[0]['prefix'] = $prefix_line;
        }
        $this->set('extensionBeans', $resource_ips);
    }

    /**
     * 落地网关
     */
    public function view_egress()
    {
        $this->init_info();
        $this->set('p', $this->Gatewaygroup->findAll_egress(
                        $this->_order_condtions(
                                array('client_name', 'resource_id', 'alias', 'capacity', 'cps_limit', 'active', 'ip_cnt')
                        )
                )
        );
    }

    public function view_ingress()
    {
        $this->pageTitle = "Routing/Ingress Trunk";
        $this->init_info();
        $this->set('p', $this->Gatewaygroup->findAll_ingress(
                        $this->_order_condtions(
                                array('client_name', 'resource_id', 'capacity', 'cps_limit', 'ip_cnt', 'client_id')
                        )
        ));
    }

    function dynamic_egress()
    {
        Configure::write('debug', 0);
        $ids = $_POST['ids'];
        $this->set('p', $this->Gatewaygroup->searchdyna($ids));
    }

    function dis_able($type = null)
    {
        Configure::write('debug', 0);
        $id = base64_decode($this->params ['pass'] [0]);
        $page = $this->params ['pass'] [1];
        $flg = $this->Gatewaygroup->dis_able($id);

        $project_name = Configure::read('project_name');
        //此处防止搜索页面跳转
        if ($this->params['url']['jump'] == "no")
        {
            $this->Gatewaygroup->create_json_array('', 201, __('Inactived successfully!', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            //$this->redirect(array('plugin' => 'prresource', 'action' => $page));
            $this->redirect($_SERVER['HTTP_REFERER']);
        }

        //end
        if ($this->params['isAjax'])
        {
            echo $flg;
        }
        else
        {
            if ($project_name != 'exchange')
            {
                $this->Gatewaygroup->create_json_array('', 201, __('Inactived successfully!', true));
                $this->Session->write('m', Gatewaygroup::set_validator());
//                $this->redirect(array('plugin' => 'prresource', 'action' => $page));
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    function active()
    {
        Configure::write('debug', 0);
        $id = base64_decode($this->params ['pass'] [0]);
        $page = $this->params ['pass'] [1];
        $this->Gatewaygroup->active($id);
        $project_name = Configure::read('project_name');
        //此处防止搜索页面跳转
        if ($this->params['url']['jump'] == "no")
        {
            $this->Gatewaygroup->create_json_array('', 201, __('Actived successfully!', true));
            $this->Session->write('m', Gatewaygroup::set_validator());
            //$this->redirect(array('plugin' => 'prresource', 'action' => $page));
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
        //end
        if ($this->params['isAjax'])
        {
            echo 'true';
        }
        else
        {
            if ($project_name != 'exchange')
            {
                $this->Gatewaygroup->create_json_array('', 201, __('Actived successfully!', true));
                $this->Session->write('m', Gatewaygroup::set_validator());
//                $this->redirect(array('plugin' => 'prresource', 'action' => $page));
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    /**
     *
     *
     * @param unknown_type $id
     */
    function delete($id)
    {
        $this->Gatewaygroup->delete($id);
        $this->Session->setFlash('');
        $this->redirect(array('action' => 'view'));
    }

    //电路使用报表---这个报表显示每一个落地网关的电路使用情况。
    function egress_report($type = 'egress')
    {
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Gatewaygroup->query("select * from users where user_id = {$user_id} ");
        $this->set('all_termination',$res[0][0]['all_termination']);
         if($res[0][0]['all_termination'] == 'f'){
             $type == 'ingress';
         }
//        $sql = "SELECT server_id, ip,port,info_ip,info_port FROM server_platform WHERE server_type = 2";
        $sql = "select id, name, lan_ip, lan_port from voip_gateway";
        $server_infos = $this->Gatewaygroup->query($sql);
        if (isset($_GET['server_info']) && !empty($_GET['server_info']))
        {
            $server_id = $_GET['server_info'];
//            $sql = "SELECT info_ip,info_port FROM server_platform WHERE server_id = $server_id";
            $sql = "select lan_ip, lan_port from voip_gateway where id = {$server_id}";
            $server_info_results = $this->Gatewaygroup->query($sql);
            $ip = $server_info_results[0][0]['lan_ip'];
            $port = $server_info_results[0][0]['lan_port'];
            $server_flg = 1;
        }
        else
        {
            $server_id = $server_infos[0][0]['id'];
            $ip = $server_infos[0][0]['lan_ip'];
            $port = $server_infos[0][0]['lan_port'];
        }
        $res_id_result = array();
        if ($ip && $port)
        {
            $sql = "select distinct(res_id) from qos_resource where server_ip='{$ip}' 
and server_port ='{$port}'";
            $res_id_result = $this->Gatewaygroup->query($sql, false);
        }
        $res_id_arr = array();
        foreach ($res_id_result as $key => $array)
        {
            sort($array[0]);
            $res_id_arr = array_merge($res_id_arr, $array[0]);
        }
        if ($res_id_arr)
        {
            $res_id_string = implode(',', $res_id_arr);
            $res_id_sql = "Resource.resource_id in ({$res_id_string})";
        }
        else
        {
            $res_id_sql = "";
        }
        //有server_id 但是所在$server_id得不到IP和port
        if(isset($server_flg) && ! $res_id_sql)
        {
            $res_id_sql = "Resource.resource_id in (0)";
        }
        if ($type == 'egress')
        {
            $resources = $this->Gatewaygroup->findAll_egress_id();
        }
        else
        {
            $resources = $this->Gatewaygroup->findAll_ingress_id();
        }
        $clients = $this->Gatewaygroup->findClient();
        $this->pageTitle = "Statistics/Trunk Monitor";
        $this->loadModel('Resource');
        $conditions = Array($type => true);
        $conditions = array_merge($conditions, Array($res_id_sql, $this->_get_search(), $this->_get_name(), $this->_get_id(), $this->_get_client_id(), $this->_get_permissions()));
        //$order = $this->_order_condtions(array('ip_cnt'));

        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('alias');
        $lists = $this->paginate('Resource');
        $this->set('Resource', $this->Resource);
        $this->set('resources', $resources);
        $this->set('clients', $clients);
        $this->set('lists', $lists);
        $this->set('type', $type);
        $this->set('server_infos', $server_infos);
        $this->set('server_id', $server_id);
        $this->set('ip', $ip);
        $this->set('port', $port);
    }

    /**
     * 查询ip
     */
    public function ajax_host_report()
    {
        Configure::write('debug', 0);
        $res_id = $this->params ['pass'] [0];
        $this->set('extensionBeans', $this->Gatewaygroup->query("select  ip ,fqdn,port , use_cnt from resource_ip
		left join (select count(* ) as use_cnt,  egress_id,callee_ip_address from real_cdr   group by egress_id  ,callee_ip_address )  a  
	 	on  a.egress_id=resource_id::text and a.callee_ip_address::text=resource_ip.ip::text
		where resource_id=$res_id   order  by use_cnt "));
    }

    function add_resouce_ingress($pass0 = null)
    {
        if (!empty($this->data ['Gatewaygroup']))
        {
            $this->data['Gatewaygroup']['ingress'] = true;
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, $_POST, $this->params['form']['accounts']);


            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());


            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {

                $this->Session->write('resource_id', $resource_id);
                $this->Session->write('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->Session->write('gress', 'ingress');
                if ($this->_get('viewtype') == 'wizard')
                {
                    $this->Gatewaygroup->create_json_array('', 201, __('Trunk Save Success', true));
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange')
                    {
                        $this->xredirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                    else
                    {
                        $this->xredirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                }
                else
                {
                    $this->xredirect("/gatewaygroups/edit_resouce_ingress?gress=ingress&resource_id=$resource_id");
                }
            }
        }
        else
        {
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->init_info();
        }
    }

    function add_resouce_egress()
    {
        if (!empty($this->data ['Gatewaygroup']))
        {
            $this->data['Gatewaygroup']['egress'] = true;
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, $_POST, array_keys_value($this->params, 'form.accounts'));
            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['alias'];
                $_SESSION ['gress'] = 'egress';
                if ($this->_get('viewtype') == 'wizard')
                {
                    $this->Gatewaygroup->create_json_array('', 201, __('Trunk Save Success', true));
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange')
                    {
                        $this->xredirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                    else
                    {
                        $this->xredirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                }
                else
                {
                    $this->redirect("/gatewaygroups/edit_resouce_egress?gress=egress&resource_id=$resource_id");
                }
            }
        }
        else
        {
            $this->init_info();
        }
    }

    function edit_resouce_ingress()
    {
        Configure::write('debug', 2);
        $this->pageTitle = "Edit Ingress";
        if (!empty($this->data ['Gatewaygroup']))
        {
            $this->data['Gatewaygroup']['ingress'] = true;
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'));
            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->init_codes($resource_id);
            }
            else
            {
                $this->Session->write('resource_id', $resource_id);
                $this->Session->write('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Edit success');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->data['Gatewaygroup']['resource_id'] = $resource_id;
                $this->set('post', $this->data);

                $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
                $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
                $this->set('resouce_list', $this->Gatewaygroup->find_resource());
                $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));

                $this->init_info();
                $this->init_codes($resource_id);
                $this->xredirect("/gatewaygroups/edit_resouce_ingress/$resource_id");
            }
        }
        else
        {
            if (!empty($this->params ['pass']))
            {
                $resource_id = $this->params ['pass'] [0];
                $this->Session->write('resource_id', $resource_id);
                $this->Session->write('gress', 'ingress');
            }
            else
            {
                $resource_id = $this->Session->read('resource_id');
            }
            $this->init_info();
            $this->init_codes($resource_id);
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data['Gatewaygroup'] = $tmp1 [0] [0];
            $this->set('post', $data);
            $hosts = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
            $this->set('hosts', $hosts);

            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));
        }
    }

    function edit_resouce_egress()
    {
        if (!empty($this->data ['Gatewaygroup']))
        {
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, $this->params['form']['accounts']);

            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {

                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['alias'];

                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Edit successfully');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                $this->set('post', $this->data);
                $this->init_info();
            }
        }
        else
        {
            if (!empty($this->params ['pass']))
            {
                $resource_id = $this->params ['pass'] [0];
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['gress'] = 'egress';
            }
            else
            {
                $resource_id = $_SESSION ['resource_id'];
            }
            $this->init_info();
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data ['Gatewaygroup'] = $tmp1[0][0];
            $this->set('post', $data);
        }
        $this->init_codes($resource_id);
        $hosts = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $this->set('hosts', $hosts);
    }

    function _add_host_impl()
    {
        $resource_id = $this->Session->read("resource_id");
        $resource_where = '';
        if (!empty($resource_id))
        {
            $resource_where = " and resource_id=$resource_id";
        }
        $list = $this->Gatewaygroup->query($sql);
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $list = $this->Gatewaygroup->query("select alias from  resource where 1=1 $resource_where ");
        $_SESSION ['resource_name'] = $list[0][0]['alias'];
    }

    function add_host()
    {
        $this->_add_host_impl();
    }

    function add_host_ingress()
    {
        $this->_add_host_impl();
    }

    function add_direction()
    {
        $resource_id = $_SESSION ['resource_id'];
        $list = $this->Gatewaygroup->query("select  direction_id,  time_profile_id,type,dnis,action,digits,number_length,number_type  from  resource_direction  where resource_id='$resource_id' order by direction_id");
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
    }

    function add_direction_post()
    {
        $direction = ($_SESSION ['gress'] == 'ingress') ? '1' : '2';
        if (isset($_POST ['resource_id']))
        {
            $res_id = $_POST ['resource_id'];
            $tmp = array_keys_value($this->params, 'form.accounts');
            $size = count($tmp);
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$res_id");
            foreach ($tmp as $el)
            {
                $time_profile_id = $el ['time_profile_id'];
                $type = $el ['type'];
                $dnis = $el ['dnis'];
                $action = $el ['action'];
                $digits = $el ['digits'];
                if ($action == 3 || $action == 4)
                {
                    $digits = $el['deldigits'];
                }
                $number_type = $el ['number_type'];
                if ($number_type == '0')
                {
                    $sql = "insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type)
					  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',0)";
                    $this->Gatewaygroup->query($sql);
                }
                else
                {
                    $number_length = $el ['number_length'];
                    $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)
					  values($direction,$res_id,'$time_profile_id','$type','$dnis'::prefix_range,'$action','$digits','$number_type','$number_length')");
                }
            }
            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action, Action Successfully !');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->xredirect("/gatewaygroups/add_direction?gress={$_POST ['gress']}");
    }

    /**
     *
     *
     * post request
     */
    function add_host_post()
    {

        //	Configure::write('debug',0);
        if (isset($_POST ['resource_id']))
        {
            $res_id = $_POST ['resource_id'];
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id in (select resource_ip_id  from  resource_ip  where resource_id=$res_id)");
            $this->Gatewaygroup->query("delete from  resource_ip  where resource_id=$res_id");

            $tmp = isset($_POST ['accounts']) ? $_POST ['accounts'] : '';
            //$checkip = $this->legalIP($res_id,$tmp);

            $checkip = true;
            if ($checkip === true)
            {
                $size = count($tmp);
                $i = 0;
                foreach ($tmp as $el)
                {
                    $ip = $el ['ip'];
                    $netmask = $el ['netmask'];
                    $port = $el ['port'];
                    $ip1 = "'" . $ip . "/" . $netmask . "'"; //普通ip
                    $ip2 = "'" . $ip . "/" . $netmask . "'"; //域名


                    $list = $this->Gatewaygroup->query(" select  {$ip1}");
                    if (empty($list))
                    {
                        $this->Gatewaygroup->create_json_array('#ip-ip-' . ($i + 1), 101, 'Please fill IP field correctly (only IP allowed).');
                        $this->Session->write("m", Gatewaygroup::set_validator());
                        $this->redirect("/gatewaygroups/add_host?gress={$_POST ['gress']}");
                    }
                    $r = $this->Gatewaygroup->query("insert into resource_ip (ip,resource_id,fqdn,port)
						  values('$ip',$res_id,$ip1,$port)");
                    if (!is_array($r))
                    {
                        $this->Gatewaygroup->rollback();
                        $this->Gatewaygroup->create_json_array('#ip-ip-' . ($i + 1), 201, 'Please fill IP field correctly (only IP allowed).');
                    }
                    $i++;
                }


                $this->Gatewaygroup->commit();
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action Success');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/gatewaygroups/add_host?gress={$_POST ['gress']}");
            }
            else
            {
                $this->Gatewaygroup->create_json_array('', 101, "IP:" . $checkip . "Already Exists");
            }
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        //	$this->redirect ( "/gatewaygroups/add_host/$res_id" );
    }

    public function legalIP($res_id, $ips)
    {
        $old_ip = $this->Gatewaygroup->query("select * from resource_ip where resource_id = $res_id");
        $repeat_array = "";
        foreach ($ips as $el)
        {
            $_not_exists = true;
            for ($i = 0; $i < count($old_ip); $i++)
            {
                if ($old_ip[$i][0]['ip'] == $el['ip'])
                {
                    $_not_exists = false;
                }
            }
            if (!isset($el ['need_register']))
            {
                if ($_not_exists)
                {
                    $qs = $this->Gatewaygroup->query("select resource_ip_id from resource_ip where ip = '{$el['ip']}'");
                    if (count($qs) > 0)
                    {
                        $repeat_array = str_ireplace($el['ip'], '', $repeat_array);
                        $repeat_array .= $el['ip'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
            }
        }
        return !empty($repeat_array) ? $repeat_array : true;
    }

    function add_host_time($id = null)
    {
        $this->pageTitle = "Capacity";

        if (!empty($id))
        {
            $sql = "select alias as name, client.name as carrier from resource  join resource_ip on resource.resource_id=resource_ip.resource_id LEFT JOIN client on resource.client_id=client.client_id where resource_ip.resource_ip_id=$id";
            $name = $this->Gatewaygroup->query($sql);
            if (!empty($name[0][0]['name']))
            {
                $this->set('name', $name[0][0]['name']);
            }
            else
            {
                $this->set('name', '');
            }
            if (!empty($name[0][0]['carrier']))
            {
                $this->set('carrier', $name[0][0]['carrier']);
            }
            else
            {
                $this->set('carrier', '');
            }
        }
        if (!empty($this->params ['pass']))
        {
            $res_ip_id = $this->params ['pass'] [0];
            $ip = isset($this->params ['pass'] [1]) ? $this->params ['pass'] [1] : '';
            $_SESSION ['resource_ip'] = $ip;
            $_SESSION ['resource_ip_id'] = $res_ip_id;
        }
        else
        {
            $res_ip_id = $_SESSION ['resource_ip_id'];
            $ip = $_SESSION ['resource_ip'];
        }

        $list = $this->Gatewaygroup->query("select  limit_id,ip_id,cps,capacity ,time_profile_id   from   resource_ip_limit   where ip_id=$res_ip_id    order by limit_id");

        $this->set("resource_ip_id", $res_ip_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
    }

    public function removeLimit() {

        Configure::write('debug', 0);
        if($this->RequestHandler->isPost()) { 
            $this->Gatewaygroup->query("DELETE FROM resource_ip_limit WHERE limit_id={$_POST['id']}");
            echo 1;
        }
        exit;
    }

    /**
     *
     * 对接网关的主被叫转换规则 配置
     */
    function add_translation_time()
    {
        if (!empty($this->params ['pass']))
        {
            $res_id = $this->params ['pass'] [0];
            $_SESSION ['resource_id'] = $res_id;
        }
        else
        {
            $res_id = $_SESSION ['resource_id'];
        }
        $list = $this->Gatewaygroup->query("select ref_id,resource_id,translation_id,time_profile_id from resource_translation_ref   where resource_id=$res_id    order by ref_id");
        $this->set("resource_id", $res_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
    }

    function view_did()
    {
        if (!empty($this->params ['pass']))
        {
            $resource_id = $this->params ['pass'] [0];
            $_SESSION ['resource_id'] = $resource_id;
            $_SESSION ['gress'] = 'egress';
        }
        else
        {
            $resource_id = $_SESSION ['resource_id'];
        }

        $list = $this->Gatewaygroup->query("select  card_id,(select card_number from card where card_id = card_code_part.card_id) as account,id,did,card_sip_id ,sip_code,resource_id ,active  from   card_code_part where resource_id = $resource_id      order by id");
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_sipcode());
        $this->set('resource_id', $resource_id);
    }

    function active_did()
    {
        $id = $this->params ['pass'] [0];
        $t = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("update card_code_part set active=$t  where id=$id");
        if ($t == 'false')
        {
            $str = "DID已经被禁用";
        }
        else
        {
            $str = "DID已经被启动";
        }
        $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, $str);
        $this->Session->write("m", Gatewaygroup::set_validator());
        $this->redirect("/gatewaygroups/view_did/");
    }

    /**
     *
     *
     * class4配置
     */
    function add_server()
    {
        $this->pageTitle = "Switch/VoIP Gateway";
        if (isset($_POST ['is_post']))
        {
            $this->add_server_post();
        }
        $this->set("host", $this->ServerPlatform->find('all', array(
                    'fields' => array('ServerPlatform.server_id', 'ServerPlatform.ip', 'ServerPlatform.port',
                        'ServerPlatform.info_ip', 'ServerPlatform.info_port', 'ServerPlatform.name', 'ServerPlatform.sip_ip', 'ServerPlatform.sip_port', 'ServerPlatform.sip_path')
        )));
    }

    function add_server_post()
    {
        if (!$_SESSION['role_menu']['Switch']['gatewaygroups:add_server']['model_w'])
        {
            $this->redirect_denied();
        }
        for ($i = 0; $i < count($this->params['form']['accounts']['ip']); $i++)
        {
            $this->data[$i] = Array(
                'name' => $this->params['form']['accounts']['name'][$i],
                'ip' => $this->params['form']['accounts']['ip'][$i],
                'port' => $this->params['form']['accounts']['port'][$i],
                'info_ip' => $this->params['form']['accounts']['info_ip'][$i],
                'info_port' => $this->params['form']['accounts']['info_port'][$i],
                'sip_ip' => $this->params['form']['accounts']['sip_ip'][$i] == '' ? NULL : $this->params['form']['accounts']['sip_ip'][$i],
                'sip_port' => $this->params['form']['accounts']['sip_port'][$i],
                'sip_path' => $this->params['form']['accounts']['sip_path'][$i],
                'server_type' => '2');
        }
        if ($this->ServerPlatform->update_all_server($this->data))
        {
            $this->ServerPlatform->create_json_array('', 201, 'server save success');
        }
    }

    function add_host_time_post()
    {
        if (isset($_POST ['resource_ip_id']))
        {
            $res_ip = $_POST ['resource_ip_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
	    $existingRecordsCount = $this->Gatewaygroup->query("select count(*) as cnt from resource_ip_limit where ip_id=$res_ip")[0][0]['cnt'];
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id=$res_ip");
            foreach ($tmp as $el)
            {
                $time_profile_id = $el ['time_profile_id'];
                $cps = !empty($el ['cps']) ? $el ['cps'] : 'null';
                $capacity = !empty($el ['capacity']) ? $el ['capacity'] : 'null';
                if (empty($time_profile_id))
                {
                    $time_profile_id = 'null';
                }
                $this->Gatewaygroup->query("insert into resource_ip_limit (ip_id,cps,capacity,time_profile_id)
					  values($res_ip,$cps,$capacity,$time_profile_id)");
            }
	    $msg = $size == $existingRecordsCount ? 'Capacity Modified Successfully!' : __('Capacity,Action Successfully!', true);
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, $msg);
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/add_host_time/" . $_POST['resource_ip_id']);
    }

    /**
     *
     * 配置 对接网关的主被叫转换规则 
     *
     */
    function add_translation_time_post()
    {
        if (isset($_POST ['resource_id']))
        {
            $res_id = $_POST ['resource_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  resource_translation_ref  where resource_id=$res_id");
            foreach ($tmp as $el)
            {
                $time_profile_id = $el['time_profile_id'];
                if (empty($time_profile_id))
                {
                    $time_profile_id = 'null';
                }
                $translation_id = $el['translation_id'];
                $this->Gatewaygroup->query("insert into resource_translation_ref (resource_id,translation_id,time_profile_id)
						  values($res_id,$translation_id,$time_profile_id)");
            }
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, __('Dataconfigurationsuccess', true));
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->redirect("/gatewaygroups/add_translation_time/");
    }

    function view_did_post()
    {
        if (isset($_POST ['resource_id']))
        {
            $resource_id = $_POST ['resource_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  card_code_part  where resource_id=$resource_id");
            foreach ($tmp as $el)
            {
                $card_sip_id = $el ['card_sip_id'];
                $did = $el ['code'];

                $list = $this->Gatewaygroup->query("select card_id from  card  where card_number='{$el['card_id']}'");
                $card_id = $list [0] [0] ['card_id'];
                $sip_code = $this->Gatewaygroup->query("select sip_code from card_sip where card_sip_id = $card_sip_id");

                $this->Gatewaygroup->query("insert into card_code_part (resource_id,card_sip_id,did,card_id,sip_code)
					  values($resource_id,$card_sip_id,'$did',$card_id,'{$sip_code[0][0]['sip_code']}')");
            }

            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action Success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/view_did/");
    }

    public function edit_ip()
    {
        //	Configure::write ( 'debug', 0 );
        $username = $_REQUEST ['user_name'];
        $password = $_REQUEST ['pass'];
        $id = $_REQUEST ['id'];
        $qs = $this->Gatewaygroup->query("update resource_ip set username = '$username',password='$password' where resource_ip_id = $id");
        if (count($qs) == 0)
        {
            $this->Gatewaygroup->create_json_array('', 201, __('manipulated_suc', true));
        }
        else
        {
            $this->Gatewaygroup->create_json_array('', 101, __('manipulated_fail', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
    }

    /**
     * 根据帐户号码查询SIP
     */
    public function get_sip_by_card($card_number)
    {
        //Configure::write('debug',0);
        $rs = $this->Gatewaygroup->query("select card_sip_id,sip_code from card_sip where card_id = (select card_id from card where card_number = '$card_number')");
        echo str_ireplace("\"", "'", json_encode($rs));
    }

    function _get_search() {
        $search = array_keys_value($this->params, 'url.search');
        if (!empty($search)) {
            return "Resource.alias like '%$search%'";
        }
        return;
    }

    function _get_name()
    {
        $name = array_keys_value($this->params, 'url.name');
        if (!empty($name))
        {
            return "Resource.name like '%$name%'";
        }
        return "";
    }

    function _get_id()
    {
        $id = array_keys_value($this->params, 'url.id');
        if (!empty($id))
        {
            return "Resource.alias like '%$id%'";
        }
        return "";
    }

    function _get_client_id()
    {
        $client_id = array_keys_value($this->params, 'url.query.id_clients');
        if (!empty($client_id))
        {
            return "Resource.client_id = '$client_id'";
        }
        return "";
    }

    function _get_permissions()
    {
        if (isset($_SESSION['sst_client_id']))
        {
            $client_id = $_SESSION['sst_client_id'];
            if (!empty($client_id))
            {
                return "Resource.client_id = '$client_id'";
            }
            return "";
        }
    }

    public function delete_resource_prefix($id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        if (!empty($id))
        {


            //发邮件
            $sql = "select resource_prefix.product_id,code_cps,tech_prefix,client.is_send_trunk_update,client.login,client.client_id,client.email,client.company,resource.alias from resource_prefix left join resource on resource_prefix.resource_id = resource.resource_id left join client on resource.client_id = client.client_id where id = $id ";
            $rst = $this->Gatewaygroup->query($sql);
            $pass_data[0]['old']['product_id'] = $rst[0][0]['product_id'];
            $pass_data[0]['old']['code_cps'] = $rst[0][0]['code_cps'];
            $pass_data[0]['old']['tech_prefix'] = $rst[0][0]['tech_prefix'];
            $pass_data[0]['action_type'] = "Delete";
            $pass['pass_data'] = $pass_data;
            $pass['login'] = $rst[0][0]['login'];
            $pass['company'] = $rst[0][0]['company'];
            $pass['alias'] = $rst[0][0]['alias'];
            $pass['email'] = $rst[0][0]['email'];
            $pass['client_id'] = $rst[0][0]['client_id'];

            $php_path = Configure::read('php_exe_path');
            $pass = base64_encode(serialize($pass));


            $delete_sql = "delete from resource_prefix where id=$id";
            $this->Gatewaygroup->query($delete_sql);

            if($rst[0][0]['is_send_trunk_update']){
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php send_email 2 $pass /dev/null";

                $info = $this->Systemparam->find('first',array(
                    'fields' => array('cmd_debug'),
                ));

                if(Configure::read('cmd_debug'))
                {
                    file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);
                }

                shell_exec($cmd);
            }


            echo 'OK!';
        }
    }

}

?>
