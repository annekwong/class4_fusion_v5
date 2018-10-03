<?php

class DynamicroutesController extends AppController
{

    var $name = 'Dynamicroutes';
    var $helpers = array('javascript', 'html', 'AppDynamicRoute', 'AppProduct', 'Common');
    var $uses = array('Client', 'Dynamicroute', 'Resource', 'DynamicRoute', 'Gatewaygroup');

    function index()
    {
        $this->redirect('view');
    }

    //读取该模块的执行和修改权限
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
            $limit = $this->Session->read('sst_route_DRPolicies');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    function ajax_egress()
    {
        Configure::write('debug', 0);
        $this->set('extensionBeans', $this->Dynamicroute->findEgress($this->params['pass'][0]));
    }

    /**
     * 初始化信息a
     */
    function init_info()
    {
//        $this->set('egresses', $this->Resource->findEgressWithTrunk());
//        $this->set('user', $this->Dynamicroute->findAllUser());
//        $this->set('clients', $this->Client->find_all_valid());
        $this->set('egresses', $this->Resource->findAllEgress());
        $this->set('user', $this->Dynamicroute->findAllUser());
        $this->set('clients', $this->Client->find_all_valid());
    }

    public function get_all_egress()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT 
resource_id, client_id from resource
WHERE egress=true and status=1 and trunk_type2=0 and is_virtual is not true and client_id is not null";
        $data = $this->Dynamicroute->query($sql);
        echo json_encode($data);
    }

    /**
     * 编辑客户信息
     */
    function edit($id)
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        if (strcmp($id, intval($id)))
        {
            $id = base64_decode($id);
        }
        $this->data['Dynamicroute']['dynamic_route_id'] = $id;
        if (!empty($this->data))
        {

            $old_data = $this->Dynamicroute->findByDynamicRouteId(array('dynamic_route_id' => $id));
            $old_egress_sql = "SELECT resource_id FROM dynamic_route_items WHERE dynamic_route_id = $id";
            $old_egress_data = $this->Dynamicroute->query($old_egress_sql);
            $flag = $this->Dynamicroute->saveOrUpdate($this->data, $_POST); //保存
            if (empty($flag))
            {
                //有错误信息
                $this->set('m', Dynamicroute::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            }
            else
            {
                //操作成功
                $this->Dynamicroute->log('edit_dynamic_route');
                $rollback_data = array();
                foreach ($this->data['Dynamicroute'] as $key => $value)
                {
                    if ($old_data['Dynamicroute'][$key] != $value && strcmp($key, 'update_by') && strcmp($key, 'update_at'))
                    {
                        if (is_string($value))
                        {
                            $rollback_data[] = $key . " = '" . $old_data['Dynamicroute'][$key] . "'";
                        }
                        else
                        {
                            $rollback_data[] = $key . " = " . $old_data['Dynamicroute'][$key];
                        }
                    }
                }
                $rollback_data[] = "update_by = '" . $_SESSION['sst_user_name'] . "'";
                $rollback_data[] = "update_at = '" . date("Y-m-d H:i:s") . "'";
                $rollback_update_sql = implode(',', $rollback_data);
                $rollback_sql = "UPDATE dynamic_route SET {$rollback_update_sql} WHERE dynamic_route_id = {$id};";
                if($id){
                    $rollback_sql .= "delete from dynamic_route_items where dynamic_route_id = {$id};";
                }
                $value_arr = array();
                foreach ($old_egress_data as $item)
                {
                    $egress_item = $this->Dynamicroute->query("SELECT resource_id FROM resource WHERE resource_id = {$item[0]['resource_id']}");
                    if ($egress_item)
                    {
                        $value_arr[] = "({$id},{$item[0]['resource_id']})";
                    }
                }
                if ($value_arr)
                {
                    $value_str = implode(",", $value_arr);
                    $rollback_sql .= "INSERT INTO dynamic_route_items (dynamic_route_id,resource_id) VALUES {$value_str}";
                }
                $rollback_msg = "Modify Dynamic Route [" . $this->data['Dynamicroute']['name'] . "] operation have been rolled back!";
                //$this->Dynamicroute->create_json_array('',201,__('Edit Dynamic Routing successfully!',true));
                $log_id = $this->Dynamicroute->logging(2, 'Dynamic Route', "Dynamic Route Name:{$this->data['Dynamicroute']['name']}", $rollback_sql, $rollback_msg);
                $this->Dynamicroute->create_json_array('', 201, __('The Dynamic Routing [%s] is modified successfully!', true, $this->data['Dynamicroute']['name']));
                $this->Session->write('m', Dynamicroute::set_validator());
                $url_flug = "dynamicroutes-view";
                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/dynamicroutes-view");
            }
        }
        else
        {

        }
        $this->set('post', $this->Dynamicroute->find('first', array('conditions' => 'dynamic_route_id = ' . $this->params ['pass'][0])));
        $this->set('res_dynamic', $this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
        $this->init_info();
        $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
    }

    /**
     * 添加客户信息
     */
    function add()
    {

        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->data ['Dynamicroute']))
        {
//            die(var_dump($this->data ['Dynamicroute']));

            $dynamic_route_id = $this->Dynamicroute->saveOrUpdate($this->data, $_POST); //保存
            if (empty($dynamic_route_id))
            {
                $this->set('m', Dynamicroute::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            }
            else
            {
                $this->Dynamicroute->log('add_dynamic_route');
                $rollback_sql = "DELETE FROM dynamic_route WHERE dynamic_route_id = {$dynamic_route_id};";
                $rollback_msg = "Create Dynamic Route [" . $this->data['Dynamicroute']['name'] . "] operation have been rolled back!";
                $log_id = $this->Dynamicroute->logging(0, 'Dynamic Route', "Dynamic Route Name:{$this->data['Dynamicroute']['name']}", $rollback_sql, $rollback_msg);
                $this->Dynamicroute->create_json_array("", 201, "The Dynamic Routing [{$this->data['Dynamicroute']['name']}] is created successfully!");
                $this->Session->write('m', Dynamicroute::set_validator());
                $url_flug = "dynamicroutes-view";

                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/dynamicroutes-view");
            }
        }
        else
        {

            $this->init_info();
            $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
        }
    }

    function del()
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = base64_decode($this->params['pass'][0]);
        $roueplan_use_count = $this->Dynamicroute->query("SELECT COUNT(*) FROM route where dynamic_route_id = {$id}");
        if ($roueplan_use_count[0][0]['count'] > 0)
        {
            $this->Dynamicroute->create_json_array("", 101, __('%s can_not_be_delete_because_be_used',true,array(__('dynamic_routing',true))));
        }
        else
        {
            $name = $this->params['pass'][1];
            $old_data_arr = $this->Dynamicroute->findByDynamicRouteId(array('dynamic_route_id' => $id));

            if ($this->Dynamicroute->del($id))
            {
                $filed_arr = array();
                $value_arr = array();
                $nname = $old_data_arr['Dynamicroute']['name'];
                unset($old_data_arr['Dynamicroute']['dynamic_route_id']);
                unset($old_data_arr['Dynamicroute']['update_by']);
                foreach ($old_data_arr['Dynamicroute'] as $key => $value)
                {
                    if ($value)
                    {
                        $filed_arr[] = $key;
                        if (is_string($value))
                        {
                            $value_arr[] = "'" . $value . "'";
                        }
                        else
                        {
                            $value_arr[] = $value;
                        }
                    }
                }
                $value_arr[] = "'" . $_SESSION['sst_user_name'] . "'";
                $filed_str = implode(',', $filed_arr);
                $value_str = implode(',', $value_arr);
                $filed_str .= ",update_by";
                $rollback_sql = "INSERT INTO dynamic_route ({$filed_str}) VALUES ({$value_str})";
                $rollback_msg = "Delete Dynamic Route [" . $this->params['pass'][1] . "] operation have been rolled back!";
                $this->Dynamicroute->log('delete_dynamic_route');
                $log_id = $this->Dynamicroute->logging(1, 'Dynamic Route', "Dynamic Route Name:{$name}", $rollback_sql, $rollback_msg);
                $this->Dynamicroute->create_json_array("", 201, 'The Dynamic Route [' . $nname . '] is deleted successfully!', $this->params['pass'][1]);
                $this->Session->write('m', Dynamicroute::set_validator());
                $url_flug = "dynamicroutes-view";
                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/dynamicroutes-view");
            }
            else
            {
                $this->Dynamicroute->create_json_array("", 101, 'The Dynamic Route [%s] is deleted failed.', $this->params['pass'][1]);
                $this->Session->write('m', Dynamicroute::set_validator());
                $url_flug = "dynamicroutes-view";
                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/dynamicroutes-view");
            }
        }
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect(array('action' => 'view'));
    }

    /**
     * 查询
     */
    function view()
    {
        $this->pageTitle = "Routing/Dynamic Routing";
        $this->init_info();
        $order = $this->_order_condtions_all(Array('dynamic_route_id', 'use_count', 'routing_rule', 'name', 'time_profile_id'));
        $carriers = $this->Dynamicroute->query("select distinct 
                                                        client.client_id as id, client.name 
                                                        from client
                                                        inner join resource on client.client_id = resource.client_id 
                                                        where resource.egress = true AND is_virtual is not true
                                                        order by client.name");
        $pdata = $this->Dynamicroute->findAll($order);
        foreach ($pdata->dataArray as &$item)
        {
            $item[0]['slist'] = $this->Gatewaygroup->searchdyna($item[0]['dynamic_route_id']);
        }
        $this->set('carriers', $carriers);
        $this->set('p', $pdata);

        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function qos_import($dynamic_id)
    {
        define("FILEPATH", APP . "upload/dynamic_up/");
        if ($this->RequestHandler->ispost())
        {
            if (is_uploaded_file($_FILES['upfile']['tmp_name']))
            {
                $dest_file = FILEPATH . uniqid('qos') . '.csv';
                $result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest_file);
                if ($result)
                {
                    $this->handle_import_qos($dest_file);
                }
            }
        }
    }

    public function handle_import_qos($dest_file)
    {

    }

    public function qos($dynamic_id)
    {
        $dynamic_id = base64_decode($dynamic_id);
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $search = "";
        if (isset($_GET['search']) && strcmp('Search', $_GET['search']))
        {
            $search = $_GET['search'];
        }
        $where = "";

        if (isset($_GET['advsearch']))
        {
            $where_arr = array();
            if (isset($_GET['asr_min']) && !empty($_GET['asr_min']))
            {
                array_push($where_arr, "min_asr >= {$_GET['asr_min']}");
            }
            if (isset($_GET['asr_max']) && !empty($_GET['asr_max']))
            {
                array_push($where_arr, "max_asr <= {$_GET['asr_max']}");
            }
            if (isset($_GET['acd_min']) && !empty($_GET['acd_min']))
            {
                array_push($where_arr, "min_acd >= {$_GET['acd_min']}");
            }
            if (isset($_GET['acd_max']) && !empty($_GET['acd_max']))
            {
                array_push($where_arr, "max_acd <= {$_GET['acd_max']}");
            }
            if (isset($_GET['aloc_min']) && !empty($_GET['aloc_min']))
            {
                array_push($where_arr, "min_aloc >= {$_GET['aloc_min']}");
            }
            if (isset($_GET['aloc_max']) && !empty($_GET['aloc_max']))
            {
                array_push($where_arr, "max_aloc <= {$_GET['aloc_max']}");
            }
            if (isset($_GET['pdd_min']) && !empty($_GET['pdd_min']))
            {
                array_push($where_arr, "min_pdd >= {$_GET['pdd_min']}");
            }
            if (isset($_GET['pdd_max']) && !empty($_GET['pdd_max']))
            {
                array_push($where_arr, "max_pdd <= {$_GET['pdd_max']}");
            }
            if (isset($_GET['abr_min']) && !empty($_GET['abr_min']))
            {
                array_push($where_arr, "min_abr >= {$_GET['abr_min']}");
            }
            if (isset($_GET['abr_max']) && !empty($_GET['abr_max']))
            {
                array_push($where_arr, "max_abr <= {$_GET['abr_max']}");
            }
            $where = implode(' AND ', $where_arr);
        }


        $counts = $this->Dynamicroute->get_qoss_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $pris = $this->Dynamicroute->get_qoss($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($pris);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_qos()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = empty($_POST['digits']) ? '' : $_POST['digits'];
        $min_asr = empty($_POST['min_asr']) ? 'NULL' : $_POST['min_asr'];
        $max_asr = empty($_POST['max_asr']) ? 'NULL' : $_POST['max_asr'];
        $min_abr = empty($_POST['min_abr']) ? 'NULL' : $_POST['min_abr'];
        $max_abr = empty($_POST['max_abr']) ? 'NULL' : $_POST['max_abr'];
        $min_acd = empty($_POST['min_acd']) ? 'NULL' : $_POST['min_acd'];
        $max_acd = empty($_POST['max_acd']) ? 'NULL' : $_POST['max_acd'];
        $min_pdd = empty($_POST['min_pdd']) ? 'NULL' : $_POST['min_pdd'];
        $max_pdd = empty($_POST['max_pdd']) ? 'NULL' : $_POST['max_pdd'];
        $min_aloc = empty($_POST['min_aloc']) ? 'NULL' : $_POST['min_aloc'];
        $max_aloc = empty($_POST['max_aloc']) ? 'NULL' : $_POST['max_aloc'];
        $limit_price = empty($_POST['limit_price']) ? 'NULL' : $_POST['limit_price'];

        $count = $this->Dynamicroute->check_digit_count($dynamic_id, $digits);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        //$dynamic_route= $this->Dynamicroute->findByDynamicId($dynamic_id);
        $this->Dynamicroute->insert_qos($dynamic_id, $digits, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function update_qos()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $qos_id = $_POST['qos_id'];
        $prefix = empty($_POST['prefix']) ? '' : $_POST['prefix'];
        $min_asr = empty($_POST['min_asr']) ? 'NULL' : $_POST['min_asr'];
        $max_asr = empty($_POST['max_asr']) ? 'NULL' : $_POST['max_asr'];
        $min_abr = empty($_POST['min_abr']) ? 'NULL' : $_POST['min_abr'];
        $max_abr = empty($_POST['max_abr']) ? 'NULL' : $_POST['max_abr'];
        $min_acd = empty($_POST['min_acd']) ? 'NULL' : $_POST['min_acd'];
        $max_acd = empty($_POST['max_acd']) ? 'NULL' : $_POST['max_acd'];
        $min_pdd = empty($_POST['min_pdd']) ? 'NULL' : $_POST['min_pdd'];
        $max_pdd = empty($_POST['max_pdd']) ? 'NULL' : $_POST['max_pdd'];
        $min_aloc = empty($_POST['min_aloc']) ? 'NULL' : $_POST['min_aloc'];
        $max_aloc = empty($_POST['max_aloc']) ? 'NULL' : $_POST['max_aloc'];
        $limit_price = empty($_POST['limit_price']) ? 'NULL' : $_POST['limit_price'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_digit_count($dynamic_id, $prefix, $qos_id);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        $this->Dynamicroute->update_qos($qos_id, $prefix, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price);
        echo 1;
    }

    public function priority($dynamic_id)
    {
        $dynamic_id = base64_decode($dynamic_id);
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $search = "";
        if (isset($_GET['search']) && strcmp('Search', $_GET['search']))
        {
            $search = $_GET['search'];
        }
        require_once 'MyPage.php';
        $where = "";
        if (isset($_GET['egress_trunk']) && !empty($_GET['egress_trunk']))
        {
            $where .= " AND resource_id = {$_GET['egress_trunk']}";
        }
        if (isset($_GET['p_start']) && !empty($_GET['p_start']))
        {
            $where .= " AND resource_pri >= {$_GET['p_start']}";
        }
        if (isset($_GET['p_end']) && !empty($_GET['p_end']))
        {
            $where .= " AND resource_pri <= {$_GET['p_end']}";
        }
        $counts = $this->Dynamicroute->get_pris_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $egress_trunks = $this->Dynamicroute->findDynamicAllEgress($dynamic_id);
        $pris = $this->Dynamicroute->get_pris($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($pris);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_pri()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = $_POST['digits'];
        $resource_pri = $_POST['resource_pri'];
        $egress_trunk = $_POST['egress_trunk'];
        $count = $this->Dynamicroute->check_pri_count($dynamic_id, $digits, $egress_trunk);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        $this->Dynamicroute->insert_pri($dynamic_id, $digits, $resource_pri, $egress_trunk);
        $dynamic_route = $this->Dynamicroute->findByDynamicId($dynamic_id);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function delete_pri($pri_id, $dynamic_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_pri where id = {$pri_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_pri($pri_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [%s] is deleted successfully', TRUE, $messg_info[0][0]['digits']));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$dynamic_id}");
    }

    public function delete_qos($encode_qos_id, $encode_dynamic_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $qos_id = base64_decode($encode_qos_id);
        $sql = "select digits from dynamic_route_qos where id = {$qos_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_qos($qos_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [%s] is deleted successfully', TRUE, $messg_info[0][0]['digits']));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/qos/{$encode_dynamic_id}");
    }

    public function override($dynamic_id)
    {
        $dynamic_id = base64_decode($dynamic_id);
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $search = "";
        if (isset($_GET['search']) && strcmp('Search', $_GET['search']))
        {
            $search = $_GET['search'];
        }
//        $search = isset($_GET['search']) ? $_GET['search'] : '';
        require_once 'MyPage.php';
        $where = "";
        if (isset($_GET['egress_trunk']) && !empty($_GET['egress_trunk']))
        {
            $where .= " AND resource_id = {$_GET['egress_trunk']}";
        }
        if (isset($_GET['p_start']) && !empty($_GET['p_start']))
        {
            $where .= " AND percentage >= {$_GET['p_start']}";
        }
        if (isset($_GET['p_end']) && !empty($_GET['p_end']))
        {
            $where .= " AND percentage <= {$_GET['p_end']}";
        }
//        var_dump($where);die;
        $counts = $this->Dynamicroute->get_overrides_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $egress_trunks = $this->Dynamicroute->findDynamicAllEgress($dynamic_id);
        $overrides = $this->Dynamicroute->get_overrides($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($overrides);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_override()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = $_POST['digits'];
        $percentage = $_POST['percentage'];
        $egress_trunk = $_POST['egress_trunk'];
        $count = $this->Dynamicroute->check_override_count($dynamic_id, $digits, $egress_trunk);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        $total = $this->Dynamicroute->check_override_total($dynamic_id);
        if (($total + $percentage) > 100)
        {
            echo 3;
            return;
        }
        $this->Dynamicroute->insert_override($dynamic_id, $digits, $percentage, $egress_trunk);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function delete_override($override_id, $dynamic_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_override where id = {$override_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_override($override_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [%s] is deleted successfully', TRUE, $messg_info[0][0]['digits']));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/override/".base64_encode($dynamic_id));
    }

    public function delete_priority($encode_priority_id, $encode_dynamic_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $priority_id = base64_decode($encode_priority_id);
        $sql = "select digits from dynamic_route_pri where id = {$priority_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_priority($priority_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [%s] is deleted successfully', TRUE, $messg_info[0][0]['digits']));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$encode_dynamic_id}");
    }

    public function delete_mul_priority($dynamic_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $delete_list = $_POST['ids'];
        $this->Dynamicroute->delete_mul_priority($delete_list);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$dynamic_id}");
    }

    public function update_override()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $override_id = $_POST['override_id'];
        $prefix = $_POST['prefix'];
        $percentage = $_POST['percentage'];
        $egress_trunk = $_POST['trunk'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_override_count($dynamic_id, $prefix, $egress_trunk, $override_id);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        $total = $this->Dynamicroute->check_override_total($dynamic_id, $override_id);
        if (($total + $percentage) > 100)
        {
            echo 3;
            return;
        }
        $this->Dynamicroute->update_override($override_id, $prefix, $egress_trunk, $percentage);
        echo 1;
    }

    public function update_pri()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $id = $_POST['id'];
        $prefix = $_POST['prefix'];
        $egress_trunk = $_POST['trunk'];
        $resource_pri = $_POST['resource_pri'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_pri_count($dynamic_id, $prefix, $egress_trunk, $id);
        if ($count > 0)
        {
            echo 2;
            return;
        }
        $this->Dynamicroute->update_pri($id, $prefix, $resource_pri, $egress_trunk);
        echo 1;
    }

    /**
     * 禁用客户
     */
    function dis_able()
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Client->dis_able($id);
        $this->redirect(array('action' => 'view'));
    }

    function active()
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Client->active($id);
        $this->redirect(array('action' => 'view'));
    }

    function ajax_dis_able()
    {
        $id = $this->params['pass'][0];
        if ($this->Client->dis_able($id))
        {
            echo "true";
        }
    }

    function ajax_active()
    {
        $id = $this->params['pass'][0];
        if ($this->Client->active($id))
        {
            echo 'true';
        }
    }

    function checkName($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $this->_get('name');
        $sql = "select count(*) from dynamic_route where name='$name'";
        if (!empty($id))
        {
            $sql.=" and dynamic_route_id <> $id";
        }
        $list = $this->Dynamicroute->query($sql);
        if ($list[0][0]['count'] > 0)
        {
            echo "false";
        }
    }

    function js_save($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->_render_set_options('Client', Array('Client' => Array('order' => 'name asc')), 1);


        $resources = $this->Resource->find('all', array(
            'conditions' => array('Resource.egress' => true, 'Resource.trunk_type2' => 0),
        ));

        $client_resources = array();

        foreach ($resources as $resource_item)
        {
            $client_resources[$resource_item['Resource']['client_id']][$resource_item['Resource']['resource_id']] = $resource_item['Resource']['alias'];
        }

        $this->set('client_resources', $client_resources);

        if ($id)
        {
            $post = $this->Dynamicroute->find('first', array('conditions' => 'dynamic_route_id = ' . $id));
            $this->set('post', $post);
            $this->set('sel', $this->Dynamicroute->jsresource($id));
            $this->set('res_dynamic', $this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
        }
        else
        {
            $this->set('post', Array());
        }

        //$this->init_info ();
        $this->set('user', $this->Dynamicroute->findAllUser());
    }

    function js_save_edit($id = null)
    {

        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->_render_set_options('Client', Array('Client' => Array('order' => 'name asc')), 1);


        $resources = $this->Resource->find('all', array(
            'conditions' => array('Resource.egress' => true),
        ));

        $client_resources = array();

        foreach ($resources as $resource_item)
        {
            $client_resources[$resource_item['Resource']['client_id']][$resource_item['Resource']['resource_id']] = $resource_item['Resource']['alias'];
        }

        $this->set('client_resources', $client_resources);

        if ($id)
        {
            $post = $this->Dynamicroute->find('first', array('conditions' => 'dynamic_route_id = ' . $id));
            $this->set('post', $post);
            $this->set('sel', $this->Dynamicroute->jsresource($id));
            $this->set('res_dynamic', $this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
        }
        else
        {
            $this->set('post', Array());
        }

        //$this->init_info ();
        $this->set('user', $this->Dynamicroute->findAllUser());
    }

    function massedit()
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $idx = explode(',', $_POST['ids']);
        switch ($_POST['edit_type'])
        {
            case 1 :       //添加 
                if (isset($_POST['trunks']))
                {
                    $i = 0;
                    foreach ($idx as $id)
                    {
                        foreach ($_POST['trunks'] as $val)
                        {
                            $data = $this->Dynamicroute->query("SELECT * FROM dynamic_route_items WHERE dynamic_route_id = {$id} AND resource_id = {$val} ");
                            if (!$data)
                            {
                                $i++;
                                $this->Dynamicroute->query("INSERT INTO dynamic_route_items (dynamic_route_id,
                                resource_id) VALUES ({$id}, {$val})");
                            }
                        }
                    }
                }
                break;
            case 2 :        //删除 
                if (isset($_POST['trunks']))
                {
                    foreach ($idx as $id)
                    {
                        foreach ($_POST['trunks'] as $val)
                        {
                            $data[$id] = $this->Dynamicroute->query("SELECT * FROM dynamic_route_items WHERE dynamic_route_id = {$id} AND resource_id = {$val} ");
                            if ($data[$id])
                            {
                                $this->Dynamicroute->query("DELETE FROM dynamic_route_items WHERE dynamic_route_id = {$id} AND resource_id = {$val}");
                            }
                        }
                    }
                }
                break;
            case 3 :        // 替换 
                $this->Dynamicroute->query("DELETE FROM dynamic_route_items WHERE dynamic_route_id IN ({$_POST['ids']})");
                if (isset($_POST['trunks']))
                {
                    foreach ($idx as $id)
                    {
                        foreach ($_POST['trunks'] as $val)
                        {
                            $this->Dynamicroute->query("INSERT INTO dynamic_route_items (dynamic_route_id,
                            resource_id) VALUES ({$id}, {$val})");
                        }
                    }
                }
                break;
            default :
                if (isset($_POST['trunks']) && $_POST['edit_type'])
                {
                    echo "The modify type is not selected!";
                    return;
                }
        }
        $update_arr = array();
        if ($_POST['routingrule'])
        {
            $update_arr[] = "routing_rule={$_POST['routingrule']}";
        }
        if ($_POST['timeprofile'])
        {
            $update_arr[] = "time_profile_id={$_POST['timeprofile']}";
        }
        $update_sql = implode(',', $update_arr);
        if ($update_sql)
        {
            $this->Dynamicroute->query("UPDATE dynamic_route SET {$update_sql} WHERE dynamic_route_id IN ({$_POST['ids']})");
        }
    }

    public function delete_selected()
    {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $ids = $_POST['ids'];
        $idss = implode(',', $ids);
        $sql = "DELETE FROM dynamic_route_items WHERE dynamic_route_id in ({$idss}); DELETE FROM dynamic_route WHERE dynamic_route_id in ({$idss}) returning name;";
        $data = $this->Dynamicroute->query($sql);
        $return_arr =array();
        if($data === false)
        {
            $return_arr['status'] = 0;
            echo json_encode($return_arr);
        }
        else
        {
            $dynamic_route_name = "";
            foreach ($data as $value)
            {
                $dynamic_route_name .= "," . $value[0]['name'];
            }
            $log_id = $this->Dynamicroute->logging(1, 'Dynamic Route', "Dynamic Route Name:{$dynamic_route_name}");
            $require = $this->Dynamicroute->query("SELECT require_comment FROM system_parameter LIMIT 1");
            $return_arr = array(
                'status' => 1,
                'log_id' => $log_id,
                'notify_flg' => intval($require[0][0]['require_comment'])
            );
            echo json_encode($return_arr);
        }
    }

    public function download($dynamic_route_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $dynamic_route_id = base64_decode($dynamic_route_id);
        $order = $this->_order_condtions_all(Array('dynamic_route_id', 'use_count', 'routing_rule', 'name', 'time_profile_id'));
        $data = $this->Dynamicroute->findAll($order, $dynamic_route_id);
        $dynamicroute = isset($data->dataArray[0][0]) ? $data->dataArray[0][0] : [];

        switch ($dynamicroute['routing_rule'])
        {

            case 4: $dynamicroute['routing_rule'] = "Largest ASR";
                break;

            case 5: $dynamicroute['routing_rule'] = "Largest ACD";
                break;

            case 6: $dynamicroute['routing_rule'] = "LCR";
                break;

            default : $dynamicroute['routing_rule'] = "";
        }


        $headers = [
            'Name',
            'Routing Rule',
            'Time Profile',
            'Usage Count',
            'QoS Cycle',
            'Update At',
            'Update By',
        ];

        $unique = date("Y_m_d_H_i_s");
        $filename = 'Dynamic_Routes_'.$unique.'.csv';
        $csvPath =  Configure::read('database_export_path').DS.$filename;
        $fp = fopen($csvPath, 'w+');
        fputcsv($fp, $headers);
        if(!empty($dynamicroute)){
            fputcsv($fp, [$dynamicroute['name'], $dynamicroute['routing_rule'], $dynamicroute['time_profile_id'], $dynamicroute['use_count'], $dynamicroute['lcr_flag'], $dynamicroute['update_at'], $dynamicroute['update_by']]);
        }

        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($csvPath);
        unlink($csvPath);
        die;

    }


    public function delete_item($encode_item_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $item_id = base64_decode($encode_item_id);
        $flg = $this->Dynamicroute->delete_item($item_id);
        if ($flg === false)
            $this->Dynamicroute->create_json_array("", 101, __('The Dynamic Routing item is deleted failed', TRUE));
        else
            $this->Dynamicroute->create_json_array("", 201, __('The Dynamic Routing item is deleted successfully', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/view");
    }

}

?>
