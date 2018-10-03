<?php

class RoutestrategysController extends AppController
{
    var $name = 'Routestrategys';
    var $uses = array('Client', 'DynamicRoute', 'Resource', 'Dynamicroute', 'Routestrategy', 'Product', 'Productitem', 'Route', 'RoutingWizard', 'RateGenerationTemplate', 'Systemparam');
    var $components = array('RequestHandler');

    function test_case()
    {
        $this->Routestrategy->update_route();
    }

    function index()
    {
        $this->redirect('strategy_list');
    }

//上传成功 记录上传
    public function upload_code2()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Routestrategy->import_data("Upload Routing Strategies "); //上传数据
        $this->Routestrategy->create_json_array("", 201, 'Uploaded Successfully');
        $this->Session->write('m', Routestrategy::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    //上传	
    public function import_rate()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $list = $this->Routestrategy->query("select name   from  route_strategy where   route_strategy_id=$rate_table_id ");
        $this->set("code_name", array_keys_value($list, '0.0.name'));
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select    digits,route_type,dynamic_route_id,static_route_id	from  route  where route_strategy_id=$rate_table_id";
        $this->Routestrategy->export__sql_data('EXport Routing Strategies ', $download_sql, 'route');
        $this->layout = '';
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_ClientGroup');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function dynamic_strategy_list($dynamic_id)
    {
        $this->pageTitle = "Routing/Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        $dynamic_id = base64_decode($dynamic_id);
        if (!empty($_REQUEST ['page'])) {
            $currPage = $_REQUEST ['page'];
        }
        if (!empty($_REQUEST ['size'])) {
            $pageSize = $_REQUEST ['size'];
        }
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select *,(select count(route_id) from route where route_strategy_id = route_strategy.route_strategy_id) as routes
				 from route_strategy where route_strategy_id = {$_REQUEST['edit_id']}
	  	";
            $result = $this->Routestrategy->query($sql);
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Routestrategy->getAll_dynamic($dynamic_id, $currPage, $pageSize, $search, array_keys_value($this->params, 'url.id'), $this->_order_condtions(array('route_strategy_id', 'name', 'routes')), $this->_get('dynamic_route_id'));
        }
        $this->set('p', $results);
    }

    public function get_products($page, $search_name = '')
    {
        Configure::write('debug', 0);
        $pageSize = 200;
        $this->autoLayout = false;
        $reseller_id = $this->Session->read('sst_reseller_id');
        $products = $this->Routestrategy->get_products($reseller_id, $search_name, $page, $pageSize);
        $this->set('products', $products);
    }

    public function get_dynamics($page, $search_name = '')
    {
        Configure::write('debug', 0);
        $pageSize = 200;
        $this->autoLayout = false;
        $reseller_id = $this->Session->read('sst_reseller_id');
        $dynamics = $this->Routestrategy->get_dynamics($reseller_id, $search_name, $page, $pageSize);
        $this->set('dynamics', $dynamics);
    }

    public function strategy_list()
    {
        $this->pageTitle = "Routing/Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select *,(select count(*) from (select resource_id from resource_prefix where route_strategy_id = route_strategy.route_strategy_id and resource_id is not null group by resource_id) as t) as routes
				 from route_strategy where route_strategy_id = {$_REQUEST['edit_id']}  AND is_virtual is not true
	  	";

            $result = $this->Routestrategy->query($sql);
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Routestrategy->getAll($currPage, $pageSize, $search, array_keys_value($this->params, 'url.id'), $this->_order_condtions(array('route_strategy_id', 'name', 'routes')), $this->_get('dynamic_route_id'));
        }
        $this->set('p', $results);
        $this->set('require_comment', $this->Routestrategy->get_require_comment());
    }

    private function isBase64($variable)
    {
        if (base64_decode($variable) !== false) {
            return true;
        }
        return false;
    }

    /**
     * 查询策略详细信息
     */
    public function routes_list($id)
    {
        $this->pageTitle = "Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (strcmp($id, intval($id))) {
            $id = base64_decode($id);
//
        }
        if (!is_numeric($id)) {
            $this->redirect('/routestrategys/strategy_list');
        }
        $this->params['pass'][0] = base64_encode($id);
        if (!empty($_REQUEST ['page'])) {
            $currPage = $_REQUEST ['page'];
        }

        if (!empty($_REQUEST ['size'])) {
            $pageSize = $_REQUEST ['size'];
        }

        if (!empty($_REQUEST['search']) && strcmp('Search....', $_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $reseller_id = $this->Session->read('sst_reseller_id');

        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select
					route_id,(select name from product where product_id = route.static_route_id) as static_route,static_route_id, 
					(select name from dynamic_route where dynamic_route_id = route.dynamic_route_id) as dynamic_route,dynamic_route_id,
                                        (select name from product where product_id = route.intra_static_route_id) as intra_static_route,intra_static_route_id,
                                        (select name from product where product_id = route.inter_static_route_id) as inter_static_route,inter_static_route_id,
					(select name from route_strategy where route_strategy_id = route.route_strategy_id) as strategy,
					route_type,digits,route_type_flg from route where route_id = {$_REQUEST['edit_id']} and lrn_block = false
	  		";
            $result = $this->Routestrategy->query($sql);
            //分页信息
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Routestrategy->getAllRoutes($currPage, $pageSize, $search, $id);
        }
        $this->set('p', $results);

        $info = $this->Routestrategy->getAddInfo($reseller_id);
        /*
          $inter_intra_route = $info[0];
          array_unshift($inter_intra_route, array(
          array('product_id' => '', 'name' => '')
          ));
          $jur_countrys = $info[2];
          array_unshift($jur_countrys, array(
          array('id' => '', 'name' => '')
          ));
          $this->set('products', str_ireplace("\"", "'", json_encode($info[0])));
          $this->set('dynamics', str_ireplace("\"", "'", json_encode($info[1])));
          $this->set('inter_intra', str_ireplace("\"", "'", json_encode($inter_intra_route)));
         */
        array_unshift($info, array(
            array('id' => '', 'name' => '')
        ));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($info)));
        $this->set('id', $id);
        $this->set('rs_name', $this->Routestrategy->select_name($id));


        $carriers = $this->Dynamicroute->query("select distinct 
                                                        client.client_id as id, client.name 
                                                        from client
                                                        inner join resource on client.client_id = resource.client_id 
                                                        where resource.egress = true
                                                        order by client.name");
        $this->set('carriers', $carriers);
        $this->set('user', $this->Dynamicroute->findAllUser());
        //$this->set('p',$this->Dynamicroute->findAll($order));
        $route_type_arr = array(
            1 => __('dyroute', true),
            2 => __('staroute', true),
            3 => __('Static Routing JD', true),
            4 => __('stfirst', true),
            5 => __('Dynamic Routing - Static Routing JD', true),
            6 => __('dyfirst', true),
            7 => __('Static Routing JD - Dynamic routing', true),
        );
        $this->set('route_type', $route_type_arr);
    }

    /**
     * 添加路由策略
     */
    public function add()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->add();
        $this->Routestrategy->log('add_routestrategy');
        echo $qs;
    }

    /**
     * 修改路由策略
     */
    public function update()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->update();
        $this->Routestrategy->log('update_routestrategy');
        echo $qs;
    }

    public function test()
    {
        Configure::write('debug', 2);
        $_REQUEST['digits'] = '6665';
        $_REQUEST['static_route_id'] = '7049';
        $_REQUEST['dynamic_route_id'] = '';

        $_REQUEST['lnp'] = 'true';
        $_REQUEST['lrn_block'] = 'on';
        $_REQUEST['dnis_only'] = 'on';


        $_REQUEST['route_type'] = '1';
        $_REQUEST['pid'] = '242';

        $qs = $this->Routestrategy->add_route();
        echo $qs;
    }

    public function add_route()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->add_route();
        $this->Routestrategy->log('add_route');
        echo $qs;
    }

    public function route_unique($array)
    {
        $flg = $this->Route->find('count', array('conditions' => $array));
        return $flg;
    }

    private function prefixUnique($conditions)
    {
        $records = $this->Route->find('all', array(
            'conditions' => array(
                'route_id != ?' => $conditions['route_id != ?'],
                'route_strategy_id' => $conditions['route_strategy_id']
            )
        ));

        foreach ($records as $record) {
            if ($record['Route']['ani_prefix'] == $conditions['ani_prefix'] && $record['Route']['digits'] == $conditions['digits']) {
                return 1;
            }
        }

        return 0;
    }

    public function update_route()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $array['digits'] = ($_REQUEST['digits'] == '') ? null : trim($_REQUEST['digits']);
        $array['ani_prefix'] = ($_REQUEST['ani_prefix'] == '') ? null : trim($_REQUEST['ani_prefix']);
        $array['ani_min_length'] = empty($_REQUEST['ani_min_length']) ? null : trim($_REQUEST['ani_min_length']);
        $array['ani_max_length'] = empty($_REQUEST['ani_max_length']) ? null : trim($_REQUEST['ani_max_length']);
        $array['digits_min_length'] = empty($_REQUEST['digits_min_length']) ? null : trim($_REQUEST['digits_min_length']);
        $array['digits_max_length'] = empty($_REQUEST['digits_max_length']) ? null : trim($_REQUEST['digits_max_length']);
        $array[] = "route_id !=" . $_REQUEST['id'];
        pre($this->route_unique($array));
        if ($this->route_unique($array)) {
            echo __('ANI,DNIS,ANI MAX length,DNIS MAX length,ANI MIN length and DNIS MIN length Has been occupied', true) . "|false";
            return;
        }

        $qs = $this->Routestrategy->update_route();
        $this->Routestrategy->log('update_route');
        echo $qs;
    }

    public function del_strategy($id, $control = '')
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }

        //删除时名称提示
        $tip = '';
        switch ($id) {
            case 'all':
                $tip = '';
                $name = "delete all";
                break;
            case 'selected':
                $arrName = $this->Routestrategy->getNameByids($_REQUEST['ids']);
                foreach ($arrName as $name) {
                    $tip .= $name[0]['name'] . ",";
                }

                $tip = '[' . substr($tip, 0, -1) . ']';
                $name = $tip;
                break;
            default:
                $arrResult = $this->Routestrategy->select_name($id);
                $name = $arrResult[0][0]['name'];
                $tip = '[' . $name . ']';
        }
        $log_id = $this->Routestrategy->logging(1, 'Routing Plan', "Routing Plan Name:{$name}");

        if ($id == 'all') {
            $id = "select route_strategy_id from route_strategy";
        }

        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }


        $this->Routestrategy->query("delete   from  route    where route_strategy_id in( $id)");
        $this->Routestrategy->query("update  resource set  route_strategy_id=null     where route_strategy_id in ($id)");
        if ($this->Routestrategy->deleteAll(Array("route_strategy_id in ($id)"))) {
            $this->Routestrategy->log('delete_routestrategy');
            $this->Routestrategy->create_json_array('', 201, 'The route strategies are deleted successfully!');
            $this->Session->write('m', Routestrategy::set_validator());
            $url_flug = "routestrategys-strategy_list";
            $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/routestrategys-strategy_list");
        } else {
            $this->Routestrategy->create_json_array('', 101, __('Fail to delete Route plan.', true));
        }
        $this->Session->write('m', Routestrategy::set_validator());
        $this->redirect('/routestrategys/strategy_list');
    }

    public function del_strategy2($id, $flag)
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }

        if ($id == 'all') {
            $id = "select route_strategy_id from route_strategy";
        }

        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }

        if ($flag != 0) {
            $id = "select route_id from route where static_route_id = {$flag}";
        }

        $trunk_ingress_use_count = $this->Routestrategy->query("SELECT COUNT(*) FROM resource WHERE route_strategy_id in ({$id})");

        if ($trunk_ingress_use_count[0][0]['count'] > 0) {
            $this->Routestrategy->create_json_array('', 101, __('Routing strategies is being used; therefore, it cannot be deleted.', true));
        } else {

            $this->Routestrategy->query("delete   from  route    where route_strategy_id in( $id)");
            $this->Routestrategy->query("update  resource set  route_strategy_id=null     where route_strategy_id in ($id)");
            if ($this->Routestrategy->deleteAll(Array("route_strategy_id in ($id)"))) {
                $this->Routestrategy->log('delete_routestrategy');
                $this->Routestrategy->create_json_array('', 201, 'The Route plan is deleted  successfully !');
            } else {
                $this->Routestrategy->create_json_array('', 101, __('del_fail', true));
            }
        }
        $this->Session->write('m', Routestrategy::set_validator());
        if ($flag != 0) {
            $this->redirect('/products/product_list');
        } else {
            $this->redirect('/routestrategys/strategy_list');
        }
    }

    public function del_route($id, $pid)
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        if ($id == 'all') {
            $id = "select route_id from route where route_strategy_id = $pid";
            if ($this->_get('lrn_block'))
                $id .= ' and lrn_block = true';
        }
        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }
        $delete_logs = $this->Routestrategy->query("select digits, 
(select name from route_strategy 
where route_strategy.route_strategy_id = route.route_strategy_id) as name 
from route where route_id in ({$id})");

        $qs = $this->Routestrategy->query("delete from route where route_id in( $id)");
        if (count($qs) == 0) {
            $messages = array();
            foreach ($delete_logs as $delete_log) {
                array_push($messages, "Name:" . $delete_log[0]['name'] . " Prefix:" . $delete_log[0]['digits']);
            }
            $message = implode(',', $messages);
            $log_id = $this->Routestrategy->logging(1, 'Route', $message);
            $this->Routestrategy->create_json_array('', 201, __('route_del_success', true));
            $this->Session->write('m', Routestrategy::set_validator());
            if ($this->_get('lrn_block'))
                $url_flug = "routestrategys-lrn_block-" . base64_encode($pid);
            else
                $url_flug = "routestrategys-routes_list-" . base64_encode($pid);
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/routestrategys-routes_list-" . base64_encode($pid));
        } else {
            $this->Routestrategy->create_json_array('', 101, __('del_fail', true));
        }

        $this->Session->write('m', Routestrategy::set_validator());
        if ($this->_get('lrn_block'))
            $this->redirect("/routestrategys/lrn_block/" . base64_encode($pid));
        else
            $this->redirect("/routestrategys/routes_list/" . base64_encode($pid));
    }

    public function check_routing_plan($name = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';

        if (!empty($name)) {
            $sql = "select count(*) as count_num from route_strategy  where  name='$name'";
            $conut = $this->Routestrategy->query($sql);
            if ($conut[0][0]['count_num'] > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    function massedit()
    {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $temp = '';
        if ($_POST['routetype'] == 1) {
            $temp = "dynamic_route_id={$_POST['dynamic']},static_route_id=null";
        } elseif ($_POST['routetype'] == 2) {
            $temp = "static_route_id={$_POST['static']},dynamic_route_id=null";
        } elseif ($_POST['routetype'] == 3) {
            $temp = "dynamic_route_id={$_POST['dynamic']},static_route_id={$_POST['static']}";
        }
        $_POST['lnp'] = isset($_POST['lnp']) && $_POST['lnp'] == 'on' ? 'true' : 'false';
        $_POST['block'] = isset($_POST['block']) && $_POST['block'] == 'on' ? 'true' : 'false';
        $_POST['dnis'] = isset($_POST['dnis']) && $_POST['dnis'] == 'on' ? 'true' : 'false';
        $this->Routestrategy->query("UPDATE route SET {$temp},lnp={$_POST['lnp']}, 
                    lrn_block={$_POST['block']}, dnis_only={$_POST['dnis']}, route_type={$_POST['routetype']} WHERE route_id IN ({$_POST['idx']})");
    }

    function addDynamicRouting()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;


        //return var_dump($_POST);

        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }

        if (!empty($this->data ['Dynamicroute'])) {

            $isSameName = $this->Dynamicroute->check_name("", $this->data ['Dynamicroute']['name']);

            if ($isSameName) {
                return "isHavaName";
            } else {
                $flag = $this->Dynamicroute->saveOrUpdate($this->data, $_POST);
                $ids = $this->Dynamicroute->findIdByName($this->data ['Dynamicroute']['name']);
                return $ids;
            }

            //保存
            if (!empty($flag)) {
                return '';
            }
        } else {
            return '';
        }
    }

    function addStaticRouting()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;

        if (!$_SESSION['role_menu']['Routing']['products']['model_w']) {
            $this->redirect_denied();
        }

        $name = trim($_POST['name']);
        if (empty($name)) {

            return 'nameIsNull';
            /* $this->Session->write('m', $this->Product->create_json(101, __('The field name cannot be NULL.', true)));
              $this->Session->write('product_name', ' ');
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }
        $pattern = '/^(\w|\-|\_)*$/';
        if (!preg_match($pattern, $name)) {
            return "nameNotPreg";
            /*
              $this->Session->write('m', $this->Product->create_json(101, __('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        //Length < 30
        if (strlen($name) >= 30) {
            return 'nameLength';
            /* $this->Session->write('m', $this->Product->create_json(101, __('pro_name_len', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        //Check if the name has already exists or not exists
        $ns = $this->Product->query("select product_id from product where name = '$name'");

        if (count($ns) > 0) {
            return 'nameIsHave';
            /* $this->Session->write('m', $this->Product->create_json(101, __($name . 'is already in use!', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        $rese_id = $this->Session->read('sst_reseller_id');
        $result = $this->Product->addProduct($name, $rese_id);
        if ($result) {
            $this->Product->log('add_product');
            $product_id = $this->Product->get_id("'" . $name . "'");
            return $product_id[0][0]['product_id'];
            //$this->Session->write('m', $this->Product->create_json(201, __('The Static Route [' . $name . '] is created successfully.', true)));
            //$this->redirect(array('controller' => 'products', 'action' => 'route_info', $result));
        } else { //添加失败
            return 'no';
            /* $this->Session->write('m', $this->Product->create_json(101, __('pro_add_failed', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }
    }

    public function add_static_route($product_id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;

        if (!$_SESSION['role_menu']['Routing']['products']['model_w']) {
            $this->redirect_denied();
        }


        if (!empty($this->params ['form'])) { //保存数据
            if (empty($product_id)) {
                $id = $this->params ['form'] ['product_id']; //Product ID
            } else {
                $id = $product_id;
                $id = $this->params ['form'] ['product_id'] = $product_id;
            }
            $res = $this->validate_route($id);
            if ($res == 'yes') {
                if (!$this->Productitem->saveinto($this->params ['form'])) {
                    return 'no';
                } else {
                    return "yes";
                }
            } else if ($res == 'codeNll') {
                return "yes";
            } else {
                return $res;
            }
        }
    }

    private function validate_route($id)
    {
        $digits = empty($this->params['form']['digits']) ? 'null' : "'" . $this->params['form']['digits'] . "'"; //Prefix
        if ($digits != 'null') {
            if (!preg_match("/[0-9]+/", $digits)) {
                return "codePreg";
            }
        } else {
            return "codeNll";
        }

        $ds = $this->Product->query("select item_id from product_items where digits = $digits  and product_id = '$id'");

        if (count($ds) > 0) {
            return 'codeIsHave';
        }

        if ($this->params['form']['strategy'] == 0) {
            $percentageNum = 0;
            foreach ($this->params['form']['percentage'] as $percentage) {
                if (!preg_match('/^[0-9]+$/', $percentage)) {
                    return "PercentPreg";
                }
                $percentageNum += $percentage;
            }

            if ($percentageNum != 100) {
                return "PercentNo100";
            }
        }

        return 'yes';
    }

    /**
     * 新建路由的弹窗
     *
     */
    public function ajax_add_route($type, $product_id = 0, $pname = 0)
    {
        Configure::write('debug', 0);

        switch ($type) {
            case 'pop-static-div' :
                $div = 1;
                break;
            case 'pop-div' :
                $div = 2;
                break;
            case 'pop-static-div1' :
                $div = 3;
                break;
            default :
                $div = 0;
        }
        $this->set('div', $div);
        $this->set('product_id', $product_id);
        $this->set('pname', $pname);
        $carriers = $this->Dynamicroute->query("select distinct 
                                                        client.client_id as id, client.name 
                                                        from client
                                                        inner join resource on client.client_id = resource.client_id 
                                                        where resource.egress = true
                                                        order by client.name");
        $this->set('carriers', $carriers);
    }

    public function save_route_panel($id = '')
    {
        Configure::write('debug', 0);
        if ($this->data) {
            if ($this->_get('is_ajax')) {
                $this->autoLayout = false;
                $this->autoRender = false;
            }
            $route_id = intval($this->data['Routestrategys']['route_id']);


            $encode_strategy_id = base64_encode($this->data['Routestrategys']['route_strategy_id']);
            $unique_conditions['digits'] = ($this->data['Routestrategys']['digits'] == '') ? NULL : trim($this->data['Routestrategys']['digits']);
            $unique_conditions['ani_prefix'] = ($this->data['Routestrategys']['ani_prefix'] == '') ? NULL : trim($this->data['Routestrategys']['ani_prefix']);
            $unique_conditions['ani_min_length'] = empty($this->data['Routestrategys']['ani_min_length']) ? NULL : trim($this->data['Routestrategys']['ani_min_length']);
            $unique_conditions['ani_max_length'] = empty($this->data['Routestrategys']['ani_max_length']) ? NULL : trim($this->data['Routestrategys']['ani_max_length']);
            $unique_conditions['digits_min_length'] = empty($this->data['Routestrategys']['digits_min_length']) ? NULL : trim($this->data['Routestrategys']['digits_min_length']);
            $unique_conditions['digits_max_length'] = empty($this->data['Routestrategys']['digits_max_length']) ? NULL : trim($this->data['Routestrategys']['digits_max_length']);
            $unique_conditions['route_id != ?'] = intval($this->data['Routestrategys']['route_id']);
            $unique_conditions['route_strategy_id'] = $this->data['Routestrategys']['route_strategy_id'];

            // edit action
            if ($route_id) {
                unset($unique_conditions['route_id != ?']);

                if (!empty($this->data['Routestrategys']['dynamic_route_id'])) {
                    $unique_conditions['dynamic_route_id'] = $this->data['Routestrategys']['dynamic_route_id'];
                }
                if (!empty($this->data['Routestrategys']['static_route_id'])) {
                    $unique_conditions['static_route_id'] = $this->data['Routestrategys']['static_route_id'];
                }

                $unique_conditions['route_type'] = empty($this->data['Routestrategys']['route_type']) ? '2' : $this->data['Routestrategys']['route_type'];
                $unique_conditions['route_type_flg'] = empty($this->data['Routestrategys']['route_type_flg']) ? '2' : $this->data['Routestrategys']['route_type_flg'];
                if ($this->Route->update_route($unique_conditions, $route_id)) {
                    $this->Session->write('m', $this->Product->create_json(201, __('The Route is modified successfully!', true)));
                } else {
                    $this->Session->write('m', $this->Product->create_json(101, __('Could not modify!', true)));
                }
                $this->redirect('routes_list/' . $encode_strategy_id);
            }

            // commented per - CQB-32
            /*if($this->prefixUnique($unique_conditions['ani_prefix'], $unique_conditions['digits']))
            {
                if ($this->_get('is_ajax'))
                    return 0;
                $msg = __('ANI,DNIS has been occupied', true);
                $this->Session->write('m', $this->Product->create_json(101, $msg));
                $this->redirect('routes_list/'.$encode_strategy_id);
            }*/

            $flg = $this->data['Routestrategys']['route_type_flg'];

            if (in_array($flg, array('3', '5', '7'))) {
                $us_country_id = $this->Route->get_us_country_id();
                $this->data['Routestrategys']['jurisdiction_country_id'] = $us_country_id;
            } else
                $this->data['Routestrategys']['jurisdiction_country_id'] = '';


            // create new
            if ($this->route_unique($unique_conditions)) {
                if ($this->_get('is_ajax'))
                    return 0;
                $msg = __('ANI,DNIS,ANI MAX length,DNIS MAX length,ANI MIN length and DNIS MIN length Has been occupied', true);
                $this->Session->write('m', $this->Product->create_json(101, $msg));
                $this->redirect('routes_list/' . $encode_strategy_id);
            }

            $qs = $this->Route->add_route($this->data);
            $action_msg = 'created';
            if ($id)
                $action_msg = 'modified';
            if ($qs) {
                if ($this->_get('is_ajax'))
                    return 1;
                $this->Routestrategy->log('add_route');
                $this->Session->write('m', $this->Product->create_json(201, __('The Route is %s successfully.', true, $action_msg)));
            } else {
                if ($this->_get('is_ajax')) {
//                    Configure::write('debug', 1);
//                    var_dump($this->data);
//                    var_dump($qs);
                    return 0;
                }
                $this->Session->write('m', $this->Product->create_json(101, __('The Route is %s failed.', true, $action_msg)));
            }
            $this->redirect('routes_list/' . $encode_strategy_id);
        } else
            $encode_strategy_id = $_GET['routestrategys'];
        $route_info = $this->Route->findByRouteId($id);
        $this->data['Routestrategys'] = $route_info['Route'];
        $route_type_arr = array(
            __('dyroute', true) => array('value' => 1, 'flg' => 1),
            __('staroute', true) => array('value' => 2, 'flg' => 2),
            __('Static Routing JD', true) => array('value' => 2, 'flg' => 3),
            __('stfirst', true) => array('value' => 3, 'flg' => 4),
            __('Dynamic Routing - Static Routing JD', true) => array('value' => 3, 'flg' => 5),
            __('dyfirst', true) => array('value' => 4, 'flg' => 6),
            __('Static Routing JD - Dynamic routing', true) => array('value' => 4, 'flg' => 7),
        );
        $this->set('route_type', $route_type_arr);
        $route_strategy_id = base64_decode($encode_strategy_id);
        $this->set('route_strategy_id', $route_strategy_id);


        //dynamic_route
        $dynamic_routes = $this->DynamicRoute->find('all', array(
            'fields' => array('name', 'dynamic_route_id'),
            'limit' => 10,
            'conditions' => array(
//                'name like ?' => '%'.$search.'%',
                'is_virtual is not true'
            ),
        ));
        $dynamic_routes_arr = array();
        foreach ($dynamic_routes as $item) {
            $dynamic_routes_arr[$item['DynamicRoute']['dynamic_route_id']] = $item['DynamicRoute']['name'];
        }

        asort($dynamic_routes_arr);
        $this->set('dynamic_routes_arr', $dynamic_routes_arr);

        //static
        $static_routes = $this->Product->find('all', array(
            'fields' => array('name', 'product_id'),
            'limit' => 10,
            'conditions' => array(
                'name != ?' => 'ORIGINATION_STATIC_ROUTE',
//                'name like ?' => '%'.$search.'%'
            ),
        ));
        $static_routes_arr = array();
        foreach ($static_routes as $item) {
            $static_routes_arr[$item['Product']['product_id']] = $item['Product']['name'];
        }

        asort($static_routes_arr);
        $this->set('static_routes_arr', $static_routes_arr);

    }


    public function test_select2()
    {
        $static_routes = $this->Product->find('all', array(
            'fields' => array('name', 'product_id'),
            'limit' => 20,
            'conditions' => array(
                'name != ?' => 'ORIGINATION_STATIC_ROUTE',
            ),
        ));
        $static_routes_arr = array();
        foreach ($static_routes as $item) {
            $static_routes_arr[$item['Product']['product_id']] = $item['Product']['name'];
        }

        asort($static_routes_arr);
        $this->set('static_routes_arr', $static_routes_arr);
    }


    public function ajax_get_static_route()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $search = $_GET['search'];
        $page = $this->_get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $result = $this->Product->find('all', array(
            'fields' => array('name', 'product_id'),
            'limit' => $limit,
            'conditions' => array(
                'name != ?' => 'ORIGINATION_STATIC_ROUTE',
                'name like ?' => '%' . $search . '%'
            ),
            'order' => 'name asc',
            'offset' => $offset,
        ));
        $total_count = $this->Product->find('count', array(
            'conditions' => array(
                'name != ?' => 'ORIGINATION_STATIC_ROUTE',
                'name like ?' => '%' . $search . '%'
            ),
        ));

        $return = array(
            'total_count' => $total_count,
            'limit' => $limit,
            'result' => $result
        );
        echo json_encode($return);
    }

    public function ajax_get_dynamic_route()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $search = $_GET['search'];
        $page = $this->_get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $result = $this->DynamicRoute->find('all', array(
            'fields' => array('name', 'dynamic_route_id'),
            'limit' => $limit,
            'conditions' => array(
                'name like ?' => '%' . $search . '%',
                'is_virtual is not true'
            ),
            'order' => 'name asc',
            'offset' => $offset,
        ));
        $total_count = $this->DynamicRoute->find('count', array(
            'conditions' => array(
                'name like ?' => '%' . $search . '%',
                'is_virtual is not true'
            ),
        ));
//        $incomplete_results = false;
//        if (count($result) < $limit || $page * $limit == $total_count){
//            $incomplete_results = true;
//        }

        $return = array(
            'total_count' => $total_count,
            'limit' => $limit,
            'result' => $result,
//            'incomplete_results' => $incomplete_results
        );
        echo json_encode($return);
    }

    public function lrn_block($encode_id)
    {
        $this->pageTitle = __('LRN Block', true);
        if (!$encode_id)
            $this->redirect('strategy_list');
        $route_plan_id = base64_decode($encode_id);
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;
        $order_arr = array('update_at' => 'DESC');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'fields' => array(
                'digits', 'route_id', 'update_at', 'update_by'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => array(
                'lrn_block' => true,
                'dynamic_route_id is null',
                'static_route_id is null',
                'intra_static_route_id is null',
                'inter_static_route_id is null',
                'route_strategy_id' => $route_plan_id
            ),
        );
        $this->data = $this->paginate('Route');
        $this->set('rs_name', $this->Routestrategy->select_name($route_plan_id));
        $this->set('id', $route_plan_id);
    }

    public function lrn_block_edit_panel($route_id = '')
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $save_arr = array(
                'route_id' => $this->params['url']['route_id'],
                'digits' => $this->data['code'],
                'route_strategy_id' => $this->params['url']['plan_id'],
                'route_type' => 1,
                'lrn_block' => true,
                'update_by' => $this->Session->read('sst_user_name'),
            );
            if ($this->Route->save($save_arr) === false)
                $this->Session->write('m', $this->Route->create_json(101, __('Save Failed!', true)));
            else
                $this->Session->write('m', $this->Route->create_json(201, __('Save successfully!', true)));
            $this->redirect('lrn_block/' . base64_encode($this->params['url']['plan_id']));
        }
        if ($route_id) {
            $data = $this->Route->find('first', array(
                'fields' => array(
                    'digits'
                ),
                'conditions' => array(
                    'route_id' => $route_id,
                ),
            ));
            $this->data['code'] = $data['Route']['digits'];
        }
    }


    public function wizard($encheck_url = '')
    {
        $this->pageTitle = 'Routing Wizard';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        $is_add = true;

        if (isset($_GET['start_time'])) {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
            $is_add = false;
        }

        $conditions = array(
            'RoutingWizard.create_time BETWEEN ? and ?' => array($start_time, $end_time)
        );

        $size = 100;
        if (isset($_GET['size'])) {
            $size = $_GET['size'];
        }

        $order_arr = array('RoutingWizard.id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array(
            'fields' => array(
                "RoutingWizard.*", "client.name", "resource.alias", "rate_generation_template.name", "resource_prefix.tech_prefix", "rate_table.name"
            ),
            'limit' => $size,
            'joins' => array(
                array(
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'RoutingWizard.client_id = client.client_id'
                    ),
                ),
                array(
                    'table' => 'rate_generation_template',
                    'type' => 'left',
                    'conditions' => array(
                        'RoutingWizard.rate_generation_template_id = rate_generation_template.id'
                    ),
                ),
                array(
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'RoutingWizard.resource_id = resource.resource_id'
                    ),
                ),
                array(
                    'table' => 'rate_table',
                    'type' => 'left',
                    'conditions' => array(
                        'rate_table.rate_table_id = RoutingWizard.virtual_rate_table_id'
                    ),
                ),
                array(
                    'table' => 'resource_prefix',
                    'type' => 'left',
                    'conditions' => array(
                        'resource_prefix.resource_id = resource.resource_id',
                        'rate_table.rate_table_id = resource_prefix.rate_table_id',
                    ),
                )

            ),
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->data = $this->paginate('RoutingWizard');
        foreach ($this->data as $k => $item) {
            $resource_id = $item[0]['resource_id'];
            $sql = "select resource_ip_id,ip,port from resource_ip WHERE resource_id = $resource_id ";
            $rst = $this->RoutingWizard->query($sql);
            $this->data[$k][0]['resource_ips'] = $rst;

            $virtual_dynamic_route_id = $item[0]['virtual_dynamic_route_id'];
            $sql = "select dynamic_route_items.resource_id as vendor_id,alias as vendor_name,client.name as client_name from dynamic_route_items LEFT JOIN resource on resource.resource_id = dynamic_route_items.resource_id LEFT JOIN client ON client.client_id = resource.client_id WHERE dynamic_route_id = $virtual_dynamic_route_id ";
            $rst = $this->RoutingWizard->query($sql);
            $this->data[$k][0]['vendors'] = $rst;
        }

        //是否跳转到新建
        $is_add = false;
        if (!count($this->data)) $is_add = true;

        if ($is_add) {
            $this->xredirect('add_wizard');
        }
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $check_url = '';
        if ($encheck_url) {
            $check_url = base64_decode($encheck_url);
        }
        $this->set('check_url', $check_url);


    }

    public function add_wizard($id = '')
    {

        $this->pageTitle = 'Add Routing Wizard';
        $wizard_info = array();
        if (!empty($id)) {
            $this->pageTitle = 'Edit Routing Wizard';
            $sql = "select routing_wizard_list.id,client_id,routing_wizard_list.rate_generation_template_id,routing_wizard_list.resource_id,tech_prefix,virtual_dynamic_route_id ,virtual_rate_table_id,code_deck_id, currency_id, rate_type,jur_type
from routing_wizard_list
                  LEFT JOIN rate_table ON routing_wizard_list.virtual_rate_table_id = rate_table.rate_table_id
                  LEFT JOIN resource_prefix on (resource_prefix.resource_id = routing_wizard_list.resource_id and resource_prefix.rate_table_id = routing_wizard_list.virtual_rate_table_id) WHERE routing_wizard_list.id = $id";
            $rst = $this->RoutingWizard->query($sql);
            $wizard_info = $rst[0][0];
            $sql = "select ip,port from resource_ip where resource_id = {$wizard_info['resource_id']}";
            $rst = $this->RoutingWizard->query($sql);
            $wizard_info['ips'] = $rst;
            $sql = "select resource_id from dynamic_route_items where dynamic_route_id = {$wizard_info['virtual_dynamic_route_id']}";
            $rst = $this->RoutingWizard->query($sql);
            $wizard_info['vendors'] = $rst;
            $rst = $this->RoutingWizard->get_ingress_trunks($wizard_info['client_id']);
            $wizard_info['ingress_list'] = $rst;


        }

        $this->set('wizard_info', $wizard_info);

        //初始化
        $clients = $this->RoutingWizard->get_clients();
        $this->set('clients', $clients);

//        $egresses = $this->RoutingWizard->findAll_egress_id();
        $this->set('egresses', $this->RoutingWizard->get_client_egress_group());
        $else_where = " and exists (select 1 from resource_ip where reg_type = 0 and resource_id = resource.resource_id)";
        $this->set('ingresses', $this->RoutingWizard->findAll_ingress_id(false, $else_where));

        $rate_generation_templates = $this->RoutingWizard->get_rate_generation_templates();
        $this->set('rate_generation_templates', $rate_generation_templates);

        $this->set('rate_email_template', $this->RoutingWizard->find_all_rate_email_template());


        $default_currency_id = $this->RoutingWizard->query("SELECT sys_currency,default_us_ij_rule FROM system_parameter LIMIT 1");
        $currency_arr = $this->Client->findCurrency();
        $default_currency = isset($currency_arr[$default_currency_id[0][0]['sys_currency']]) ? $currency_arr[$default_currency_id[0][0]['sys_currency']] : $default_currency_id[0][0]['sys_currency'];
        $this->set('default_currency', $default_currency);
        $this->set('default_us_ij_rule', $default_currency_id[0][0]['default_us_ij_rule']);

        $code_decks = $this->RoutingWizard->get_code_decks();
        $this->set('code_decks', $code_decks);

        $currencies = $this->RoutingWizard->get_currencies();
        $this->set('currencies', $currencies);
        $no_egress_rate_table = $this->RoutingWizard->query('SELECT rate_table_id,name FROM rate_table where not EXISTS (select 1 from resource where rate_table_id = rate_table.rate_table_id) AND is_virtual is not true AND origination is not true');
        $this->set('rate_tables', $no_egress_rate_table);

        $rate_type_arr = array(
            __('A-Z', true),
            __('US Non-JD', true),
            __('US JD', true),
            __('OCN-LATA-JD', true),
            __('OCN-LATA-NON-JD', true)
        );
        $this->set('rate_type_arr', $rate_type_arr);


        if ($this->RequestHandler->ispost()) {


            $rand_str = date('YmdHis');

            if (!empty($_POST['id'])) {
                $edata = $this->data;
                $eid = $_POST['id'];
                $eips = isset($_POST['ips']) && count($_POST['ips']) ? $_POST['ips'] : array();
                $eports = isset($_POST['ports']) && count($_POST['ports']) ? $_POST['ports'] : array();
                $eprefix = $edata['Route']['prefix'];
                $evendors = $edata['Route']['vendors'];

                $this->RoutingWizard->begin();

                //trunk_ip
                $tmp = array();
                foreach ($eips as $k => $v) {
                    $ip = $eips[$k] . $eports[$k];
                    if (in_array($ip, $tmp)) {
                        $this->RoutingWizard->create_json_array('', 201, __('ip and port combination must be unique', true));
                        return;
                    }
                    $tmp[] = $ip;
                }
                $this->RoutingWizard->query("delete from resource_ip where resource_id = {$wizard_info['resource_id']}");
                $this->RoutingWizard->create_ip_port($wizard_info['resource_id'], $eips, $eports);


                //prefix
                $sql = "update resource_prefix set tech_prefix = '$eprefix' WHERE resource_id = {$wizard_info['resource_id']} and rate_table_id = {$wizard_info['virtual_rate_table_id']} ";
                $this->RoutingWizard->query($sql);

                //vendors
                $this->RoutingWizard->create_dynamic_item_egress($wizard_info['virtual_dynamic_route_id'], $this->data['Route']['vendors']);

                //保存rate table
                $code_deck = empty($_POST['code_deck']) ? 'NULL' : $_POST['code_deck'];
                $currency = empty($_POST['currency']) ? 'NULL' : $_POST['currency'];
                $type = $_POST['type'];
                $rate_type = (int)$_POST['rate_type'];
                $isus = $rate_type == 2 ? true : false;
                if ($currency == 'NULL') {
                    $this->RoutingWizard->rollback();
                    $this->RoutingWizard->create_json_array('', 101, __('You must create Currency first!', true));
                    $this->Session->write('m', RoutingWizard::set_validator());
                    return;
                }
                $jurisdiction = 'NULL';

                if ($this->params['form']['choose_rate_table'] == 0) {
                    if ($isus) {
                        $sql = "SELECT id FROM jurisdiction_country WHERE name = 'US'";
                        $data = $this->query($sql);
                        if (empty($data)) {
                            $sql = "INSERT INTO jurisdiction_country(name) VALUES ('US') returning id";
                            $data = $this->query($sql);
                        }
                        $sql = "update rate_table set code_deck_id = $code_deck, currency_id = $currency, rate_type = $type, jurisdiction_country_id = {$data[0][0]['id']}, jur_type = $rate_type WHERE rate_table_id = {$wizard_info['virtual_rate_table_id']}";
                    } else {
                        $sql = "update rate_table set code_deck_id = $code_deck, currency_id = $currency, rate_type = $type, jurisdiction_country_id = null, jur_type = $rate_type WHERE rate_table_id = {$wizard_info['virtual_rate_table_id']}";
                    }

                    $this->RoutingWizard->query($sql);
                }

                //update rate
                $eis_update_rate = isset($_POST['is_update_rate']) ? 1 : 0;
                if ($eis_update_rate) {
                    $erate_table_id = $wizard_info['virtual_rate_table_id'];
                    $rst = $this->copy_from_rate_template($erate_table_id);

                    if (!$rst) {
                        $this->RoutingWizard->rollback();
//                        $this->RoutingWizard->create_json_array('', 101, __('Edit Fail!', true));
                        $this->Session->write('m', RoutingWizard::set_validator());
                        return;
                    }
                    $rate_generation_history_id = $rst['rate_generation_history_id'];
                    $rate_generation_history_detail_id = $rst['rate_generation_history_detail_id'];


                    $rate_generation_template_id = $_POST['data']['Rate']['select_template_id'];


                    $encode_template_id = base64_encode($rate_generation_template_id);
                    $encode_history_id = base64_encode($rate_generation_history_id);
                    $check_url = $this->webroot . "rate_generation/rate_generation_history_detail/{$encode_template_id}/{$encode_history_id}";
                    $encheck_url = base64_encode($check_url);


                    $this->RoutingWizard->create_json_array('', 201, __('Edit Success and please check whether the code has been successfully generated!', true));

                    //插入routing_wizard_list
                    $sql = "update  routing_wizard_list set rate_generation_history_detail_id = $rate_generation_history_detail_id,create_time = current_timestamp(0), create_by = '{$_SESSION['sst_user_name']}'";
                    $this->RoutingWizard->query($sql);
                    $this->RoutingWizard->commit();
                    $this->Session->write('m', RoutingWizard::set_validator());
                    if ($this->params['form']['choose_rate_table'] == 0)
                        $this->xredirect("/routestrategys/wizard/$encheck_url");
                    else
                        $this->xredirect("/routestrategys/wizard");
                }
                //插入routing_wizard_list
                $sql = "update  routing_wizard_list set create_time = current_timestamp(0), create_by = '{$_SESSION['sst_user_name']}'";
                $this->RoutingWizard->query($sql);
                $this->RoutingWizard->create_json_array('', 201, __('Edit Success!', true));
                $this->RoutingWizard->commit();
                $this->Session->write('m', RoutingWizard::set_validator());

                $this->xredirect("/routestrategys/wizard");
                return;


            }

            //add
            $this->RoutingWizard->begin();
            $rollback_sql = "";
            //client
            if ($this->data['Client']['choose_client_type'] == 0) {
                $client_info = $this->data['Client'];
                $client_info['name'] = $client_info['input_client_name'];
                $client_info['allowed_credit'] = -abs(floatval($client_info['allowed_credit']));

                $carrier_arr = $this->RoutingWizard->create_carrier($client_info);
                if ($carrier_arr === false) {
                    $this->RoutingWizard->rollback();
                    return;
                }
                $client_id = $carrier_arr['client_id'];
//                $carrier_name = $carrier_arr['name'];
//                $rollback_sql .= "DELETE FROM client WHERE client_id = {$client_id};DELETE FROM client_balance WHERE client_id = {$client_id};";
            } else {
                $client_id = $this->data['Client']['select_client_id'];
            }

            //trunk

            if ($this->data['Trunk']['choose_trunk_type'] == 0) {
                //验证
                $trunk_name = $this->data['Trunk']['input_trunk_name'];
                $rst = $this->RoutingWizard->create_trunk($trunk_name, $client_id);
                if ($rst === false) {
                    $this->RoutingWizard->rollback();
                    return;
                }

                $resource_id = $rst;

//                $rollback_sql .= "DELETE FROM resource WHERE resource_id = {$resource_id};";
            } else {
                $resource_id = $this->data['Trunk']['select_trunk_id'];
                $trunk_name = $this->RoutingWizard->query("select alias from resource where resource_id = {$resource_id}");
                $trunk_name = $trunk_name[0][0]['alias'];
            }

            //ip
            $ips = isset($_POST['ips']) && count($_POST['ips']) ? $_POST['ips'] : array();
            $ports = isset($_POST['ports']) && count($_POST['ports']) ? $_POST['ports'] : array();
            $tmp = array();
            foreach ($ips as $k => $v) {
                $ip = $ips[$k] . $ports[$k];
                if (in_array($ip, $tmp)) {
                    $this->RoutingWizard->create_json_array('', 201, __('ip and port combination must be unique', true));
                    return;
                }
                $tmp[] = $ip;
            }
            $this->RoutingWizard->query("delete from resource_ip where resource_id = {$resource_id}");
            $this->RoutingWizard->create_ip_port($resource_id, $ips, $ports);
//            $rollback_sql .= "DELETE FROM resource_ip WHERE resource_id = {$resource_id};";

            //==       create  Dynamic Routing / routing plan  /rate table  ==//start

//                Dynamic Routing start
            $dynamic_route_name = $trunk_name . "_Virtual_Dynamic_Routing_" . $rand_str;
            $insert_dy_route_sql = "INSERT INTO dynamic_route (name,update_by,is_virtual) VALUES "
                . "('{$dynamic_route_name}','{$_SESSION['sst_user_name']}',true) RETURNING dynamic_route_id";
            $dy_route_arr = $this->RoutingWizard->query($insert_dy_route_sql);
            if ($dy_route_arr === false) {
                $this->RoutingWizard->rollback();
                $this->RoutingWizard->create_json_array('', 101, __('add Dynamic Routing failed!', true));
                $this->Session->write('m', RateTemplate::set_validator());
                return;
            }
            $dy_route_id = $dy_route_arr[0][0]['dynamic_route_id'];
            $rollback_sql .= "DELETE FROM dynamic_route WHERE dynamic_route_id = {$dy_route_id};";
            if (empty($this->data['Route']['vendors'])) {
                $this->RoutingWizard->rollback();
                $this->RoutingWizard->create_json_array('', 101, __('The Field Vendors is empty!', true));
                $this->Session->write('m', RateTemplate::set_validator());
                return;
            }
            $this->RoutingWizard->create_dynamic_item_egress($dy_route_id, $this->data['Route']['vendors']);
            $rollback_sql .= "DELETE FROM dynamic_route_items WHERE dynamic_route_id = {$dy_route_id};";
//                Dynamic Routing End
//                routing plan start
            $route_plan_name = $trunk_name . "_Virtual_Routing_Plan_" . $rand_str;
            $insert_route_plan_sql = "INSERT INTO route_strategy (name,update_by,is_virtual) VALUES "
                . "('{$route_plan_name}','{$_SESSION['sst_user_name']}',true) RETURNING route_strategy_id";
            $route_plan_arr = $this->RoutingWizard->query($insert_route_plan_sql);
            if ($route_plan_arr === false) {
                $this->RoutingWizard->rollback();
                $this->RoutingWizard->create_json_array('', 101, __('add Routing plan failed!', true));
                $this->Session->write('m', RateTemplate::set_validator());
                return;
            }
            $route_plan_id = $route_plan_arr[0][0]['route_strategy_id'];
            $rollback_sql .= "DELETE FROM route_strategy WHERE route_strategy_id = {$route_plan_id};";
//                routing plan END
//                route start
            $insert_route_sql = "INSERT INTO route (dynamic_route_id,route_type,route_strategy_id,update_by) VALUES "
                . "({$dy_route_id},1,{$route_plan_id},'{$_SESSION['sst_user_name']}') RETURNING route_id";
            $route_arr = $this->RoutingWizard->query($insert_route_sql);
            if ($route_arr === false) {
                $this->RoutingWizard->rollback();
                $this->RoutingWizard->create_json_array('', 101, __('add Route failed!', true));
                $this->Session->write('m', RoutingWizard::set_validator());
                return;
            }
            $route_id = $route_arr[0][0]['route_id'];
            $rollback_sql .= "DELETE FROM route WHERE route_id = {$route_id};";
//                route END

            if ($this->params['form']['choose_rate_table'] == 1) {
                $rate_table_id = $this->params['form']['exist_rate_table'];
                $rate_generation_history_id = 0;
                $rate_generation_history_detail_id = 0;
                $rate_generation_template_id = 0;
            } else {
                //                rate table start
                $rate_table_name = $trunk_name . "_Virtual_Rate_Table_" . $rand_str;
                $code_deck = empty($_POST['code_deck']) ? 'NULL' : $_POST['code_deck'];
                $currency = empty($_POST['currency']) ? 'NULL' : $_POST['currency'];
                $type = $_POST['type'];
                $rate_type = (int)$_POST['rate_type'];
                $isus = $rate_type == 2 ? true : false;
                if ($currency == 'NULL') {
                    $this->RoutingWizard->rollback();
                    $this->RoutingWizard->create_json_array('', 101, __('You must create Currency first!', true));
                    $this->Session->write('m', RoutingWizard::set_validator());
                    return;
                }
                $jurisdiction = 'NULL';

                if (!$this->RoutingWizard->alreay_exists_ratetable($rate_table_name)) {
                    $rate_table_id = $this->RoutingWizard->create_ratetable($rate_table_name, $code_deck, $currency, $type, $isus, $rate_type);
                    if ($rate_table_id == false) {
                        $this->RoutingWizard->rollback();
                        $this->RoutingWizard->create_json_array('', 101, __('The Rate Table[' . $rate_table_name . '] is added failure.', true));
                        $this->Session->write('m', RoutingWizard::set_validator());
                        return;
                    }

                    $rollback_sql .= "DELETE FROM rate_table WHERE rate_table_id = {$rate_table_id};";

                } else {
                    $this->RoutingWizard->create_json_array('', 101, __('The Rate Table[' . $rate_table_name . '] is exist.', true));
                    $this->RoutingWizard->rollback();
                    $this->Session->write('m', RoutingWizard::set_validator());
                    return;
                }


                //复制rate到rate_table
                $template_id = $this->data['Rate']['select_template_id'];
                if (empty($template_id)) {
                    $this->RoutingWizard->rollback();
                    $this->RoutingWizard->create_json_array('', 101, __('The Field Rate Generation Template is empty!', true));
                    $this->Session->write('m', RoutingWizard::set_validator());
                    return;
                }
                $rst = $this->copy_from_rate_template($rate_table_id);
                if (!$rst) {
                    $this->RoutingWizard->rollback();
//                $this->RoutingWizard->create_json_array('', 101, __('Add Fail!', true));
                    $this->Session->write('m', RoutingWizard::set_validator());
                    return;
                }
                $rate_generation_history_id = $rst['rate_generation_history_id'];
                $rate_generation_history_detail_id = $rst['rate_generation_history_detail_id'];
                $rate_generation_template_id = $_POST['data']['Rate']['select_template_id'];
            }


            $tech_prefix = trim($this->data['Route']['prefix']);
            //prefix
            $rst = $this->RoutingWizard->query("INSERT INTO resource_prefix (resource_id,tech_prefix,route_strategy_id,rate_table_id,product_id) VALUES($resource_id,'$tech_prefix',$route_plan_id,$rate_table_id,0) returning id");
            if ($rst === false) {
                $this->RoutingWizard->rollback();
                $this->RoutingWizard->create_json_array('', 101, __('add Resource Prefix failed!', true));
                $this->Session->write('m', RoutingWizard::set_validator());
                return;
            }
            $rollback_sql .= "DELETE FROM resource_prefix WHERE id = {$rst[0][0]['id']};";


            //插入routing_wizard_list
            $sql = "insert into routing_wizard_list(client_id, resource_id, rate_generation_template_id,rate_generation_history_id,rate_generation_history_detail_id,virtual_dynamic_route_id,virtual_route_plan_id,virtual_route_id,virtual_rate_table_id, create_time, create_by)
                values($client_id,$resource_id,$rate_generation_template_id,$rate_generation_history_id,$rate_generation_history_detail_id,$dy_route_id,$route_plan_id,$route_id,$rate_table_id,current_timestamp(0),'{$_SESSION['sst_user_name']}') returning id";
            $rst = $this->RoutingWizard->query($sql);
            $rollback_sql .= "DELETE FROM routing_wizard_list WHERE id = {$rst[0][0]['id']};";
            $sql = "update routing_wizard_list set rollback_sql = '$rollback_sql' where id = {$rst[0][0]['id']}";
            $this->RoutingWizard->query($sql);


            $this->RoutingWizard->commit();

            $this->RoutingWizard->create_json_array('', 201, __('Add Success and please check whether the code has been successfully generated!', true));
            $this->Session->write('m', RoutingWizard::set_validator());


            $encode_template_id = base64_encode($rate_generation_template_id);
            $encode_history_id = base64_encode($rate_generation_history_id);
            $check_url = $this->webroot . "rate_generation/rate_generation_history_detail/{$encode_template_id}/{$encode_history_id}";
            $encheck_url = base64_encode($check_url);
            if ($this->params['form']['choose_rate_table'] == 0)
                $this->xredirect("/routestrategys/wizard/$encheck_url");
            else
                $this->xredirect("/routestrategys/wizard");

        }
    }

    public function get_ingress($client_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->RoutingWizard->get_ingress_trunks($client_id);
        echo json_encode($data);
    }

    public function get_ingress_ips($trunk_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->RoutingWizard->get_ingress_ips($trunk_id);
        echo json_encode($data);
    }

    public function get_rate_templates()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->RoutingWizard->get_rate_generation_templates();
        echo json_encode($data);
    }

    public function copy_from_rate_template($rate_table_id)
    {
        $post_data = $_POST;
        $rate_generation_template_id = $post_data['data']['Rate']['select_template_id'];
        $sql = "select id from rate_generation_history where rate_generation_template_id = $rate_generation_template_id ORDER BY finished_time desc";
        $rate_generation_history_id = $this->RoutingWizard->query($sql);
        $rate_generation_history_id = $rate_generation_history_id[0][0]['id'];
        if (!empty($post_data['id'])) {
            if (empty($post_data['new_effective_date']) || empty($post_data['increase_effective_date']) || empty($post_data['decrease_effective_date'])) {
                $this->RoutingWizard->create_json_array('', 101, __('The field Effective Date is empty!', true));
                return false;
            }
        } else {
            if (empty($post_data['effective_date'])) {
                $this->RoutingWizard->create_json_array('', 101, __('The field Effective Date is empty!', true));
                return false;
            }
        }

        $insert_data = array();
        $rand_flg = md5($this->create_randon_str(1));
        $insert_data['create_on'] = date('Y-m-d H:i:sO');
        $insert_data['create_by'] = $_SESSION['sst_user_name'];
        $insert_data['rate_generation_history_id'] = $rate_generation_history_id;
        $insert_data['rate_table_id'] = $rate_table_id;
        $insert_data['effective_date_new'] = isset($post_data['new_effective_date']) ? $post_data['new_effective_date'] : $post_data['effective_date'];
        $insert_data['effective_date_increase'] = isset($post_data['increase_effective_date']) ? $post_data['increase_effective_date'] : $post_data['effective_date'];
        $insert_data['effective_date_decrease'] = isset($post_data['decrease_effective_date']) ? $post_data['decrease_effective_date'] : $post_data['effective_date'];
        $insert_data['is_send_mail'] = isset($post_data['is_send_email']) ? $post_data['is_send_email'] : 0;
        $insert_data['end_date'] = $post_data['end_date'];
        $insert_data['email_template_id'] = isset($post_data['email_template_id']) ? $post_data['email_template_id'] : '';
        $insert_data['end_date_method'] = isset($post_data['end_date_method']) ? $post_data['end_date_method'] : 3;
        $insert_data['rand_flg'] = $rand_flg;
        $this->loadModel('RateGenerationHistoryDetail');
        $flg = $this->RateGenerationHistoryDetail->save($insert_data);

        $rate_generation_history_detail_id = $this->RateGenerationHistoryDetail->getLastInsertId();
        if ($flg === false) {
            $this->RateGenerationHistoryDetail->create_json_array('', 101, __('Your Job to copy rates is failed!', true));
            return false;
        }
        $this->Session->write('m', RateGenerationHistoryDetail::set_validator());
//        $rate_generation_history_id = $this->RateGenerationHistoryDetail->getLastInsertId();
        $rst = array();
        $rst['rate_generation_history_id'] = $rate_generation_history_id;
        $rst['rate_generation_history_detail_id'] = $rate_generation_history_detail_id;
        return $rst;
    }

    public function del_wizard($id)
    {
        $flag = false;
        if (empty($id)) {
            $flag = true;
        }

        $sql = "select rollback_sql from routing_wizard_list where id = $id";
        $rst = $this->RoutingWizard->query($sql);

        $del_sql = $rst[0][0]['rollback_sql'];
        $rst = $this->RoutingWizard->query($del_sql);

        if ($rst === false) {
            $flag = true;
        }

        if ($flag) {
            $this->RoutingWizard->create_json_array('', 101, __('Delete Failed!', true));
        } else {
            $this->RoutingWizard->create_json_array('', 201, __('The Routing Wizard is deleted successfully!', true));
        }
        $this->Session->write('m', RoutingWizard::set_validator());

        $this->xredirect('/routestrategys/wizard');


    }

    public function create_randon_str($num)
    {
        $num_flg = intval($num);
        $rand_str = "";
        for ($i = 0; $i < $num_flg; $i++) {
            $rand_str .= chr(mt_rand(33, 126));
        }
        return $rand_str;
    }

    public function save_ip()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $ip = trim($_POST['ip']);
        $port = trim($_POST['port']);
        $resource_id = $_POST['resource_id'];

        if (empty($ip) || empty($port)) {
            $rst = 'The field ip,port Can not be empty!';
            echo json_encode($rst);
            exit;
        }

        //去重
        $sql = "select ip,port from resource_ip where resource_id = $resource_id";
        $ss = $this->RoutingWizard->query($sql);
        $ip_port = $ip . $port;
        foreach ($ss as $item) {
            if (!strcmp($item[0]['ip'] . $item[0]['port'], $ip_port)) {
                $rst = 'The ip,port is Unique!';
                echo json_encode($rst);
                exit;
            }
        }

        $sql = "insert into resource_ip(ip,port,resource_id,reg_type,direction) VALUES('$ip','$port',$resource_id,0,0)";
        $rr = $this->RoutingWizard->query($sql);
        if ($rr === false) {
            $rst = 'Add Fail';
        }

        $rst = 'true';

        echo json_encode($rst);

    }

    public function del_ip()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $resource_ip_id = $_POST['resource_ip_id'];


        if ($resource_ip_id) {
            $sql = "delete from resource_ip where resource_ip_id = $resource_ip_id";
            $rr = $this->RoutingWizard->query($sql);
        }
        if ($rr === false) {
            $rst = 'Add Fail';
        }

        $rst = 'true';

        echo json_encode($rst);

    }
}

?>
