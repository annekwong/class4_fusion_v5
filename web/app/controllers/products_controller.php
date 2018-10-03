<?php

class ProductsController extends AppController
{

    var $name = 'Products';
    var $uses = array('Product', 'Productitem', 'Resource');
    var $helpers = array('javascript', 'html', 'AppProduct');

    function index()
    {
        $this->redirect('product_list');
    }

    public function upload_code2()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_x'])
        {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Product->import_data(__('UploadRoute', true)); //上传数据
        $this->Product->create_json_array("", 201, __('rateUploadSuccess', true));
        $this->Session->write('m', Product::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    public function qos($item_id, $product_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $data = $this->Productitem->findByItemId($item_id);
        if ($this->RequestHandler->ispost())
        {
            $redirect = '/products/route_info/' . $product_id;
            $this->check_qos_data($this->data['min_asr'],$this->data['max_asr'],__('ASR',true),$redirect);
            $this->check_qos_data($this->data['min_abr'],$this->data['max_abr'],__('ABR',true),$redirect);
            $this->check_qos_data($this->data['min_acd'],$this->data['max_acd'],__('ACD',true),$redirect);
            $this->check_qos_data($this->data['min_pdd'],$this->data['max_pdd'],__('PDD',true),$redirect);
            $this->check_qos_data($this->data['min_aloc'],$this->data['max_aloc'],__('ALOC',true),$redirect);

            $insert_data['StaticRoute'] = $this->data;
            $insert_data['StaticRoute']['strategy'] = $data['StaticRoute']['strategy'];
            $insert_data['StaticRoute']['item_id'] = $item_id;
            $insert_data['StaticRoute']['update_by'] = $this->Session->read('sst_user_name');
            $flg = $this->Productitem->save($insert_data);
            if ($flg === false)
                $this->Session->write('m', $this->Product->create_json(101, __('The Trunk Priority of Code[%s] is modifield failed.', true, $data['StaticRoute']['digits'])));
            else
                $this->Session->write('m', $this->Product->create_json(201, __('The Trunk Priority of Code[%s] is modifield successfully.', true, $data['StaticRoute']['digits'])));
            $this->redirect('/products/route_info/' . $product_id);
        }
        else
            $this->data = $data['StaticRoute'];
    }

    private function check_qos_data($min_data,$max_data,$name,$redirect)
    {
        if($min_data && $max_data)
        {
            if ((int)$min_data > (int)$max_data)
            {
                $this->Session->write('m', $this->Product->create_json(101, __($name.' MIN can not great than MAX.', true)));
                $this->redirect($redirect);
            }
        }
    }


    //上传code	
    public function import_rate()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_x'])
        {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        if ($rate_table_id) {
            $list = $this->Product->query("select name   from  product where   product_id=$rate_table_id ");
        }
        $this->set("code_name", isset($list[0][0]['name']) ? $list[0][0]['name'] : '');
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_x'])
        {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select    alias,digits, case when strategy>2 then 1 ELSE strategy end ,time_profile_id
		

		from  product_items  where product_id=$rate_table_id";
        $this->Product->export__sql_data(__('DownloadProduct', true), $download_sql, 'pro');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_route_table');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    /*
     * 分页查询
     */

    function _render_product_list_swapproducts()
    {
        $product_id_1 = array_keys_value($this->params, 'form.productA');
        $product_id_2 = array_keys_value($this->params, 'form.productB');
        if (empty($product_id_1) || empty($product_id_2))
        {
            return;
        }
        $this->Product->xproduct_items($product_id_1, $product_id_2);
    }

    public function product_list($name = null)
    {

        $this->pageTitle = "Routing/Static Route Table";
        $order = $this->_order_condtions(array('product_id', 'name', 'modify_time', 'routes', 'ingress'));
        $this->_render_product_list_swapproducts();
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}

        if (!empty($_REQUEST['search']) && strcmp('Search....', $_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        if (isset($name))
        {
            $results = $this->Product->getAllProducts($currPage = null, $pageSize = null, $search = null, $order = null, $name);
        }
        else
        {
            $results = $this->Product->getAllProducts($currPage, $pageSize, $search, $order);
        }
        $this->set('p', $results);
        if (array_keys_value($this->params, 'isAjax'))
        {
            $this->render('product_list_ajax');
        }
        $this->set('code_decks', $this->Product->query('select * from code_deck'));
    }

    /*
     * 添加Product
     */

    public function add_product()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }

        $name = trim($_REQUEST ['name0']);
        if (empty($name))
        {
            $this->Session->write('m', $this->Product->create_json(101, __('The field name cannot be NULL.', true)));
            $this->Session->write('product_name', ' ');
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }
        $pattern = '/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]{1,62}[0-9a-zA-Z_]$/';
        if (!preg_match($pattern, $name))
        {
            $this->Session->write('m', $this->Product->create_json(101, __('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 64 characters in length!', true)));
            $this->Session->write('product_name', $name);
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }
        if (strlen($name) >= 65)
        {
            $this->Session->write('m', $this->Product->create_json(101, __('pro_name_len', true)));
            $this->Session->write('product_name', $name);
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }

        //Check if the name has already exists or not exists
        $ns = $this->Product->query("select product_id from product where name = '$name'");

        if (count($ns) > 0)
        {
            $this->Session->write('m', $this->Product->create_json(101, __($name . 'is already in use!', true)));
            $this->Session->write('product_name', $name);
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }

        $code_type = $_REQUEST['name1'];
        $code_deck_id = $_REQUEST['name2'] == '' ? 'NULL' : $_REQUEST['name2'];
        $route_lrn = $_REQUEST['name3'];
        $rese_id = $this->Session->read('sst_reseller_id');
        $product_id = $this->Product->addProduct($name, $rese_id, $code_type, $code_deck_id, $route_lrn);
        if ($product_id)
        {
            $this->Product->log('add_product');
            //$product_id = $this->Product->get_id($name);
            $rollback_sql = "DELETE FROM product WHERE product_id = {$product_id}";
            $rollback_msg = "Create Static Route [" . $name . "] operation have been rolled back!";
            $this->Session->write('m', $this->Product->create_json(201, __('The Static Route[%s] is created successfully.', true, $name)));
            $log_id = $this->Product->logging(0, 'Static Route', "Static Route Name:{$name}", $rollback_sql, $rollback_msg);
            $url_flug = "products-route_info-{$product_id}";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/products-route_info-{$product_id}");
        }
        else
        { //添加失败
            $this->Session->write('m', $this->Product->create_json(101, __('pro_add_failed', true)));
            $this->Session->write('product_name', $name);
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }
    }

    /*
     * 根据ID删除
     */

    public function delbyid($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
//        pr($this->Product->query("select rollback from modif_log order by id desc limit 1"));
//        die;
        /*
          //判断该路由表是否有路由
          $ps = $this->Product->query("select item_id from product_items where product_id = $id");
          if (count($ps) > 0) {
          $this->Session->write('m',$this->Product->create_json(101,__('pro_has_routes',true)));
          $this->redirect ( array ('controller' => 'products', 'action' => 'product_list' ) );
          exit();
          }
         */
        $routeplan_use_count = $this->Product->query("SELECT COUNT(*) FROM route WHERE static_route_id = {$id}");

        if ($routeplan_use_count[0][0]['count'] > 0)
        {
            $this->Session->write('m', $this->Product->create_json(101, "Warnig: The static route is currently being used and cannot be deleted. Please select the `route count` link of the target static route , to see which routing plans are using this static route.", true));
            $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
        }

        //$this->Product->query("delete from resource_product_ref where product_id = $id");
        //删除
        $this->data = $this->Product->find('first', Array('conditions' => Array("product_id=$id")));
        $old_product_item = $this->Productitem->findAll(array('product_id' => $id));
//        pr($old_product_item);
        //$this->_do_del_rollback($old_product_item);
        if ($this->Product->del_product($id) != true)
        {
            $this->Session->write('m', $this->Product->create_json(101, __('Fail to delete Static route.', true)));
        }
        else
        {
            $this->Product->log('delete_product');
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $this->data['Product'];
            unset($old_data_arr['product_id']);
            unset($old_data_arr['modify_time']);
            $old_data_arr['update_by'] = $_SESSION['sst_user_name'];
            foreach ($old_data_arr as $key => $value)
            {
                if ($value)
                {
                    $filed_arr[] = $key;
                    $str_arr = array(
                        'update_by', 'name', 'introduction'
                    );
                    if (in_array($key, $str_arr))
                    {
                        $value_arr[] = "'" . $value . "'";
                    }
                    else
                    {
                        $value_arr[] = $value;
                    }
                }
            }
            $filed_str = implode(',', $filed_arr);
            $value_str = implode(',', $value_arr);

            $rollback_sql = "INSERT INTO product ({$filed_str}) VALUES ({$value_str}) RETURNING product_id;&&";
            $rollback_sql .= $this->_do_del_rollback($old_product_item);
            $rollback_msg = "Delete Static Route [" . $this->data['Product']['name'] . "] operation have been rolled back!";
            $rollback_extra_info = json_encode(array('type' => 3));
            $log_id = $this->Product->logging(1, 'Static Route', "Static Route Name:{$this->data['Product']['name']}", $rollback_sql, $rollback_msg, $rollback_extra_info);
            $this->Session->write('m', $this->Product->create_json(201, __('The Static route [%s] is deleted successfully!', true, $this->data['Product']['name'])));

            $url_flug = "products-product_list";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/products-product_list");
        }
        $this->redirect(array('controller' => 'products', 'action' => 'product_list'));
    }

    /**
     * 修改Product的名字
     */
    public function modifyname()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        if (!empty($_REQUEST ['id']))
        {
            if (!empty($_REQUEST ['name']))
            {
                $id = $_REQUEST ['id']; //ID
                $name = $_REQUEST ['name']; //Name
                $route_lrn = $this->data['Product']['route_lrn'];
                $code_deck_id = $this->data['Product']['code_deck_id'];
                $codeType = $this->data['Product']['code_type'];
//                $pattern = '/^[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_\-][\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_\-\| ]+[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_\-]$/';
                $pattern = '/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]{1,62}[0-9a-zA-Z_]$/';
                if (!preg_match($pattern, $name))
                {
                    $this->Session->write('m', $this->Product->create_json(101, __('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 64 characters in length! ', true)));
                    $this->xredirect("/products/product_list");
                }
                if (strlen($name) >= 65)
                {
                    $this->Session->write('m', $this->Product->create_json(101, __('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 64 characters in length! ', true)));
                    $this->xredirect("/products/product_list");
                }
                $oldns_name = '';
                if ($id) {
                    $oldns = $this->Product->query("select name from product where product_id = '$id'");
                    $oldns_name = trim($oldns[0][0]['name']);
                }
                if (strcmp(trim($name), $oldns_name) != 0)
                {
                    $ns = $this->Product->query("select product_id from product where name = '$name'");
                    if (count($ns) > 0)
                    {
                        $this->Session->write('m', $this->Product->create_json(101, __($name . 'is already in use!', true)));
                        $this->xredirect("/products/product_list");
                    }
                }
                $old_data = $this->Product->findByProductId(array($id));
//                if(empty($old_data['Product']['route_lrn']))
//                {
//                    $old_data['Product']['route_lrn'] = 0;
//                }

                $r = $this->Product->modify_name($id, $name, $route_lrn, $code_deck_id, $codeType);
                if ($r != false)
                {
                    $old_name = $old_data['Product']['name'];
                    $old_route_lrn = $old_data['Product']['route_lrn'];
                    $lastmodify = date('Y-m-d H:i:s');
                    $rollback_sql = "UPDATE product SET name = '$old_name',route_lrn = {$old_route_lrn},modify_time = '$lastmodify', update_by = '{$_SESSION['sst_user_name']}' where product_id = '$id'";
                    $rollback_msg = "Modify Static Route [" . $name . "] operation have been rolled back!";
                    $this->Product->log('edit_product');
                    $this->Session->write('m', $this->Product->create_json(201, __('The Static Route [%s] is modified successfully!', true, $name)));
                    $log_id = $this->Product->logging(2, 'Static Route', "Static Route Name:{$name}", $rollback_sql, $rollback_msg);
                    $url_flug = "products-product_list";
                    $this->modify_log_noty($log_id, $url_flug);
//                    $this->xredirect("/logging/index/{$log_id}/products-product_list");
                    echo $r;
                }
                else
                {
                    echo __('Fail to modify Static Route.', true);
                }
            }
            else
            {
                $this->Session->write('m', $this->Product->create_json(101, __('The field name cannot be NULL.', true)));
                $this->xredirect("/products/product_list");
            }
        }
    }

    /**
     * 查询该Product对应的Route
     * @param int $id
     */
    public function route_info($id = null)
    {
        if (empty($id))
        {
            $empty_data = $this->Product->query("SELECT product_id FROM product WHERE name ='ORIGINATION_STATIC_ROUTE'");
            if (isset($empty_data[0][0]['product_id']))
                $id = $empty_data[0][0]['product_id'];
            else
            {
                $insert_product_data = $this->Product->query("INSERT INTO product (name) values ('ORIGINATION_STATIC_ROUTE') returning product_id");
                $id = $insert_product_data[0][0]['product_id'];
            }
            $this->params['pass'][0] = $id;
        }
        if (!empty($id))
        {
            $currPage = 1;
            $pageSize = 100;

            $code_type = $this->Product->query("select code_type from  product where product_id=$id order by name asc");
            $this->set('name', $this->Product->query("select name from  product where product_id=$id order by name asc"));

            if (!empty($_REQUEST ['page']))
            {
                $currPage = $_REQUEST ['page'];
            }
            if (!empty($_REQUEST ['size']))
            {
                $pageSize = $_REQUEST ['size'];
            }
            if ($code_type[0][0]['code_type'] == 0)
            {
                $search = null;
                if (!empty($_REQUEST ['search']))
                {
                    if($_REQUEST ['search'] == 'Enter Prefix'){
                        $search = null;
                    }
                    else{
                        $search = $_REQUEST ['search'];
                    }
                    $this->set('search', $search);
                }
                $rs = $this->Product->getRoutesByProduct($id, $currPage, $pageSize, $search, $this->_order_condtions(array('item_id', 'digits', 'strategy', 'time_profile')));
                $this->set('p', $rs);
            }
            else
            {
                $search = null;
                if (!empty($_REQUEST ['search']))
                {
                    $search = $_REQUEST ['search'];
                    $this->set('search', $search);
                }
                $rs = $this->Product->getRoutesCodeNameByProduct($id, $currPage, $pageSize, $search, $this->_order_condtions(array('item_id', 'digits', 'strategy', 'time_profile', 'code_name')));
                //exit();
                $this->set('p', $rs);
            }

            $this->loadModel('TimeProfile');
//            $tmpData = $this->TimeProfile->query("SELECT * FROM resource_direction WHERE digits='1234' LIMIT 1");
//            die(var_dump($tmpData));

            $this->set('code_type', $code_type[0][0]['code_type']);
            $TimeProfile = $this->TimeProfile->find('all', Array('fields' => Array('time_profile_id', 'name'),'order'=>array('name asc')));
            $this->set('TimeProfile', $TimeProfile);
            $this->set('id', $id);
            $viewListUpdate = $this->Session->read("viewListUpdate");
            $this->Session->write("viewListUpdate", null);
            $this->set('viewListUpdate', $viewListUpdate);
            $this->loadModel('Resource');
            $Resource = $this->Resource->find('all', Array('fields' => Array('alias', 'resource_id'), 'conditions' => Array('egress' => true), 'order' => array('alias ASC')));
            $this->set('Resource', $Resource);


            /*
              if (!empty($_REQUEST['edit_id'])){
              $sql = "select item_id,alias,digits,strategy,
              (select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
              array(select alias from resource where resource_id in (select resource_id from product_items_resource where item_id = product_items.item_id order by id asc)) as alias
              from product_items
              where item_id = {$id}
              ";
              $result = $this->Product->query ( $sql );
              //分页信息
              require_once 'MyPage.php';
              $rs = new MyPage ();
              $rs->setTotalRecords ( 1 ); //总记录数
              $rs->setCurrPage ( 1 ); //当前页
              $rs->setPageSize ( 1 ); //页大小
              $rs->setDataArray ( $result );
              $this->set('edit_return',true);
              } else {
             *  /* } */
        }
    }

    /*
     * 删除一条路由记录
     */

    public function del($id = null, $pid = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->Product->deleteById($id) != true)
        {//失败
            $this->Session->write('m', $this->Product->create_json(101, __('Fail to delete Static Route.', true)));
        }
        else
        {//成功
            $this->Productitem->deletere($id);
            $this->Product->log('delete_product');

            $this->Session->write('m', $this->Product->create_json(201, __('The Static Route is deleted successfully.', true)));
        }

        $this->redirect('/products/route_info/' . $pid);
    }

    public function del_code_name($id = null, $pid = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }

        $id = explode("{", $id);
        $id = explode("}", $id[1]);
        $ids = $id[0];

        if ($this->Product->deleteByIds($ids) != true)
        {//失败
            $this->Session->write('m', $this->Product->create_json(101, __('Fail to delete Static Route.', true)));
        }
        else
        {//成功
            $this->Productitem->deletereByIds($ids);
            $this->Product->log('delete_product');
            $this->Session->write('m', $this->Product->create_json(201, __('The Static Route is deleted successfully.', true)));
        }

        $this->redirect('/products/route_info/' . $pid);
    }

    /*
     * 删除所有路由
     */

    public function delall()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $_REQUEST ['id'];
        if ($this->Product->deleteAll($id) != true)
        {
            $this->Session->write('m', $this->Product->create_json(101, __('cannot_del', true)));
        }
        else
        {
            $this->Productitem->deletereAll($id);
            $this->Product->log('deleteall_product_items');

            $this->Session->write('m', $this->Product->create_json(201, __('routes_del_success', true)));
        }
        $this->redirect('/products/route_info/' . $id);
    }

    /*
     * 删除所有选中路由
     */

    public function delselected()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST ['ids'];

        $id = $_REQUEST ['id'];

        $ids = str_ireplace('{', "", $ids);
        $ids = str_ireplace('}', "", $ids);


        if ($this->Product->deleteSelected($ids) != true)
        {
            $this->Session->write('m', $this->Product->create_json(101, __('cannot_del', true)));
        }
        else
        {
            $this->Productitem->deletereByIds($ids);
            $this->Product->log('deleteselect_product');

            $this->Session->write('m', $this->Product->create_json(201, __('del_some_route_succ', true)));
        }

        $this->redirect('/products/route_info/' . $id);
    }

    /*
     * 添加和修改路由的数据验证
     */

    private function validate_route($id, $checkrepeat_name, $checkrepeat_prefix, $url)
    {
        $has_error = false;
        $digits = empty($this->params['form']['digits']) ? 'null' : "'" . $this->params['form']['digits'] . "'"; //Prefix
        if ($digits != 'null')
        {
            if (!preg_match("/[0-9]+/", $digits))
            {
                $has_error = true;
                $this->Productitem->create_json_array('#digits', 101, 'Please fill prefix field correctly (only  digits allowed).');
            }
        }
        if ($checkrepeat_prefix == true)
        {
            $ds = $this->Product->query("select item_id from product_items where digits = $digits  and product_id = '$id'");
            if (count($ds) > 0)
            {
                $has_error = true;
                $this->Productitem->create_json_array('#digits', 101, __('prefix_exists', true));
            }
        }
        if ($this->params['form']['strategy'] == 0)
        {
            $percentages = 0;
            $tmp_per = 0;
            for ($i = 1; $i <= 8; $i++)
            {
                $percentage = !empty($this->params['form']["percentage_$i"]) ? $this->params['form']["percentage_$i"] : '';
                $tmp_per = $tmp_per + $percentage;
                if (!empty($percentage))
                {
                    if (!preg_match('/^[0-9]+$/', $percentage))
                    {
                        $has_error = true;
                        $this->Productitem->create_json_array("#percentage_$i", 101, __('weight_integer', true));
                    }
                    else
                    {
                        $percentages += $percentage;
                    }
                }
            }
        }
        /* 			if ($has_error == false) {
          if ($this->params['form']['strategy']==0) {
          pr($percentages);
          if($percentages < 95 || $percentages >100) {
          $has_error = true;
          $this->Productitem->create_json_array('',101,__('route2',true));
          }
          }
          } */
        if ($has_error == true)
        {
            $this->Session->write('backform', $this->params['form']);
            $this->Session->write('m', Productitem::set_validator());
            $this->redirect($url);
        }
    }

    private function validate_route_code_name($id, $checkrepeat_name, $checkrepeat_prefix, $url)
    {
        $has_error = false;

        $code_name = empty($this->params['form']['code_name']) ? 'null' : "'" . $this->params['form']['code_name'] . "'"; //Prefix
        /* $digits = empty($this->params['form']['digits']) ? 'null' : "'" . $this->params['form']['digits'] . "'"; //Prefix
          if ($digits != 'null') {
          if (!preg_match("/[0-9]+/", $digits)) {
          $has_error = true;
          $this->Productitem->create_json_array('#digits', 101, 'Please fill prefix field correctly (only  digits allowed).');
          }
          } */
        if ($checkrepeat_prefix == true)
        {
            $ds = $this->Product->query("select * from product_items where code_name = $code_name  and product_id = '$id'");
            if (count($ds) > 0)
            {
                $has_error = true;
                $this->Productitem->create_json_array('#digits', 101, __('code_name_exists', true));
            }
        }
        if ($this->params['form']['strategy'] == 0)
        {
            $percentages = 0;
            $tmp_per = 0;
            for ($i = 1; $i <= 8; $i++)
            {
                $percentage = !empty($this->params['form']["percentage_$i"]) ? $this->params['form']["percentage_$i"] : '';
                $tmp_per = $tmp_per + $percentage;
                if (!empty($percentage))
                {
                    if (!preg_match('/^[0-9]+$/', $percentage))
                    {
                        $has_error = true;
                        $this->Productitem->create_json_array("#percentage_$i", 101, __('weight_integer', true));
                    }
                    else
                    {
                        $percentages += $percentage;
                    }
                }
            }
        }
        /* 			if ($has_error == false) {
          if ($this->params['form']['strategy']==0) {
          pr($percentages);
          if($percentages < 95 || $percentages >100) {
          $has_error = true;
          $this->Productitem->create_json_array('',101,__('route2',true));
          }
          }
          } */
        if ($has_error == true)
        {
            $this->Session->write('backform', $this->params['form']);
            $this->Session->write('m', Productitem::set_validator());
            $this->redirect('/products/route_info/' . $id);
        }
    }

    /*
     * 添加路由
     */

    public function add_route($product_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->params ['form']))
        { //保存数据

            if (empty($product_id))
            {
                $id = $this->params ['form'] ['product_id']; //Product ID
            }
            else
            {
                $id = $product_id;
                $id = $this->params ['form'] ['product_id'] = $product_id;
            }
            $this->validate_route($id, true, false, '/products/add_route/' . $id);

            if (!$this->Productitem->saveinto($this->params ['form']))
            {
                $this->Session->write('m', $this->Product->create_json(101, __('route_add_failed', true)));
            }
            else
            {
                $this->Productitem->log('add_route');
                $this->Session->write('m', $this->Product->create_json(201, __('Static Route created successfully!', true)));
            }
            $this->redirect('/products/route_info/' . $id);
        }
        else
        { //查询
            $this->set('resource', $this->Product->getResource()); //查询所有的Egress
            $this->set('timeprofiles', $this->Product->getTimeProfiles()); //查询所有的Time profiles
            $this->set('pid', $product_id);
        }
    }

    public function add_route_code_name($product_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->params ['form']))
        { //保存数据
            if (empty($product_id))
            {
                $id = $this->params ['form'] ['product_id']; //Product ID
            }
            else
            {
                $id = $product_id;
                $id = $this->params ['form'] ['product_id'] = $product_id;
            }


            $this->validate_route_code_name($id, true, true, '/products/add_route/' . $id);

            $code_deck_id = $this->Productitem->query("select code_deck_id from product where product_id = '$id'");
            if (!$this->Productitem->saveintoCodeName($this->params ['form'], $code_deck_id[0][0]['code_deck_id']))
            {
                $this->Session->write('m', $this->Product->create_json(101, __('route_add_failed', true)));
            }
            else
            {
                $this->Productitem->log('add_route');
                $this->Session->write('m', $this->Product->create_json(201, __('Static Route created successfully!', true)));
            }

            $this->redirect('/products/route_info/' . $id);
        }
        else
        { //查询
            $this->set('resource', $this->Product->getResource()); //查询所有的Egress
            $this->set('timeprofiles', $this->Product->getTimeProfiles()); //查询所有的Time profiles
            $this->set('pid', $product_id);
        }
    }

    /*
     * 修改Route
     */

    public function edit_route($id = null, $pid = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        //	pr($this->params['form']);

        if (!empty($this->params ['form']))
        { //保存数据
            if ($pid)
            {
                $this->params ['form'] ['product_id'] = $pid;
            }
            else
            {
                $pid = $this->params ['form'] ['product_id']; //Product ID
            }
            if ($id)
            {
                $this->params['form']['item_id'] = $id;
            }
            else
            {
                $id = $this->params['form']['item_id'];
            }
            $digits = trim($this->params['form']['digits']); //前缀
            //原来的名字
            $ns = $this->Product->query("select alias from product_items where item_id = '$id'");

            //原来的前缀
            $ds = $this->Product->query("select digits from product_items where item_id = '$id'");


            $url = '/products/edit_route/' . $id;

            if (strcmp($digits, $ds[0][0]['digits']) == 0)
            {//用户修改了名字没有修改前缀的情况
                $this->validate_route($id, true, false, $url); //不检查前缀是否已存在
            }
            else if (strcmp($digits, $ds[0][0]['digits']) != 0)
            {//用户修改了前缀没有修改名字的情况
                $this->validate_route($id, false, true, $url);
            }
            else if (strcmp($digits, $ds[0][0]['digits']) != 0)
            {//用户既修改了名字又修改了前缀的情况
                $this->validate_route($id, true, true, $url);
            }
            else
            {
                $this->validate_route($id, false, false, $url);
            }

//			$this->Productitem->query("update product_items set resource_id_1=null,resource_id_2=null,resource_id_3=null,
//			resource_id_4=null,resource_id_5=null,resource_id_6=null,resource_id_7=null,resource_id_8=null
//			,percentage_1=null,percentage_2=null,percentage_3=null,percentage_4=null,percentage_5=null,percentage_6=null,
//			percentage_7=null,percentage_8=null where item_id = $id");

            for ($index = 1; $index < 9; $index++)
            {
                $resource_key = "resource_id_{$index}";
                if (!isset($this->params ['form'][$resource_key]) || empty($this->params ['form'][$resource_key]))
                {
                    $this->params ['form'][$resource_key] = NULL;
                }
                $per_key = "percentage_{$index}";
                if (!isset($this->params ['form'][$per_key]) || empty($this->params ['form'][$per_key]))
                {
                    $this->params ['form'][$per_key] = NULL;
                }
            }
            if (!$this->Productitem->updateinto($this->params ['form'], $id))
            {
                $this->Session->write('m', $this->Product->create_json(101, __('route_add_failed', true)));
            }
            else
            {
                $this->Productitem->updatere($this->params ['form']);
                $this->Productitem->log('edit_route');
                $this->Session->write('m', $this->Product->create_json(201, __('Static Route modified successfully!', true)));
            }
            $this->redirect('/products/route_info/' . $pid);
        }
        else
        {//查询数据
            $this->set('route', $this->Product->getRouteById($id)); //根据ID查询Route的信息
            $this->set('resource', $this->Product->getResource()); //查询所有的Egress

            $this->set('timeprofiles', $this->Product->getTimeProfiles()); //查询所有的Time profiles
            $this->set('id', $id);
            $this->set('pid', $pid);
        }
    }

    public function edit_route_code_name($code_name = null, $pid = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        //	pr($this->params['form']);

        if (!empty($this->params ['form']))
        { //保存数据
            if ($pid)
            {
                $this->params ['form'] ['product_id'] = $pid;
            }
            else
            {
                $pid = $this->params ['form'] ['product_id']; //Product ID
            }
            /* if ($id) {
              $this->params['form']['item_id'] = $id;
              } else {
              $id = $this->params['form']['item_id'];
              } */
            /* $digits = trim($this->params['form']['digits']); //前缀
              //原来的名字
              $ns = $this->Product->query("select alias from product_items where item_id = '$id'");

              //原来的前缀
              $ds = $this->Product->query("select digits from product_items where item_id = '$id'");


              $url = '/products/edit_route/' . $id;

              if (strcmp($digits, $ds[0][0]['digits']) == 0) {//用户修改了名字没有修改前缀的情况
              $this->validate_route($id, true, false, $url); //不检查前缀是否已存在
              } else if (strcmp($digits, $ds[0][0]['digits']) != 0) {//用户修改了前缀没有修改名字的情况
              $this->validate_route($id, false, true, $url);
              } else if (strcmp($digits, $ds[0][0]['digits']) != 0) {//用户既修改了名字又修改了前缀的情况
              $this->validate_route($id, true, true, $url);
              } else {
              $this->validate_route($id, false, false, $url);
              } */

//			$this->Productitem->query("update product_items set resource_id_1=null,resource_id_2=null,resource_id_3=null,
//			resource_id_4=null,resource_id_5=null,resource_id_6=null,resource_id_7=null,resource_id_8=null
//			,percentage_1=null,percentage_2=null,percentage_3=null,percentage_4=null,percentage_5=null,percentage_6=null,
//			percentage_7=null,percentage_8=null where item_id = $id");

            for ($index = 1; $index < 9; $index++)
            {
                $resource_key = "resource_id_{$index}";
                if (!isset($this->params ['form'][$resource_key]) || empty($this->params ['form'][$resource_key]))
                {
                    $this->params ['form'][$resource_key] = NULL;
                }
                $per_key = "percentage_{$index}";
                if (!isset($this->params ['form'][$per_key]) || empty($this->params ['form'][$per_key]))
                {
                    $this->params ['form'][$per_key] = NULL;
                }
            }



            if (!$this->Productitem->updateintoCodeName($this->params ['form'], $code_name))
            {
                $this->Session->write('m', $this->Product->create_json(101, __('route_add_failed', true)));
            }
            else
            {
                $this->Productitem->updaterecodeName($this->params ['form'], $code_name);
                $this->Productitem->log('edit_route');
                $this->Session->write('m', $this->Product->create_json(201, __('Static Route modified successfully!', true)));
            }

            $this->redirect('/products/route_info/' . $pid);
        }
        else
        {//查询数据
            $this->set('route', $this->Product->getRouteById($id)); //根据ID查询Route的信息
            $this->set('resource', $this->Product->getResource()); //查询所有的Egress

            $this->set('timeprofiles', $this->Product->getTimeProfiles()); //查询所有的Time profiles
            $this->set('id', $id);
            $this->set('pid', $pid);
        }
    }

    /*
     * Insert data into DB
     */

    private function insert($alias, $digits, $strategy, $time_profile, $route1, $route2, $route3, $route4, $route5, $route6, $route7, $route8, $weight1, $weight2, $weight3, $weight4, $weight5, $weight6, $weight7, $weight8, $product_id, $sttime, $ettime, $minlen, $maxlen, $startw, $endw)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $sql = "insert into product_items
																										(
																											alias,digits,strategy,time_profile_id,
																										start_time,end_time,
																										start_week,end_week,
																										min_len,max_len
																										) 
																										values('$alias','$digits',
																										$strategy,$time_profile,
																										$weight1,$weight2,
																										$weight3,$weight4,
																										$weight5,$weight6,
																										$weight7,$weight8,$product_id,$sttime,$ettime,$startw,$endw,$minlen,$maxlen)
																								";
        $qs = $this->Product->query($sql);
        return $qs;
    }

    public function upload($product_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);

        $query_result_sets = 0; //执行结果


        $r = $this->Product->getUploadData('browse');

        $action = $this->params ['form'] ['handleStyle']; //Overwrite  Remove  Refresh


        $isroll = $this->params ['form'] ['isRoll']; //是否需要Rollback

        $this->Product->begin(); //开启事务


        $route_ids = "";
        if ($action == 3)
        {
            //原来的路由
            $old_routes = $this->Product->query("select item_id from product_items where product_id = '$product_id'");
            $loop = count($old_routes);
            for ($i = 0; $i < $loop; $i++)
            {
                $route_ids .= "'" . $old_routes [$i] [0] ['item_id'] . "',";
            }
            $route_ids = substr($route_ids, 0, strlen($route_ids) - 1);

            $qs = $this->Product->query("delete from product_items where item_id in ($route_ids)");

            if (count($qs) != 0)
            {
                $query_result_sets++;
            }
        }

        $each_count = 1; //第几行
        $upload_error = "["; //错误信息字符串
        $hasError = false; //是否有格式错误
        $affectRows = count($r);

        //需要验证的字段
        //从国际化文件读取  方便前台处理错误信息显示时做国际化
        $n = __('alias', true);
        $dg = __('digits', true);
        $st = __('start_date', true);
        $et = __('end_date', true);
        $ptg = __('percentage', true);
        $policy = __('policy', true);
        $todt = __('todtype', true);
        $stodt = __('starttod', true);
        $edot = __('enddot', true);
        $stime = __('sttime', true);
        $etime = __('ettime', true);
        $minlength = __('minlength', true);
        $maxlength = __('maxlength', true);

        foreach ($r as $d)
        {
            $errorFlag = false;
            $alias = $d->alias; //名称
            //查询是否已经存在该名称的路由
            $as = $this->Product->query("select item_id from product_items where alias = '$alias' and product_id = '$product_id'");
            //文件中的路由名称为空、含有不合法字符、长度大于等于30、已经存在的情况
            //记录错误信息
            if (empty($alias))
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('upload_route_is_null', true);
                $upload_error .= "{row:$each_count,name:'$n',msg:'$msg'},";
            }

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $alias))
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('upload_route_format', true);
                $upload_error .= "{row:$each_count,name:'$n',msg:'$msg'},";
            }

            if (strlen($alias) >= 15)
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('upload_route_len', true);
                $upload_error .= "{row:$each_count,name:'$n',msg:'$msg'},";
            }

            if (count($as) > 0)
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('exists', true);
                $upload_error .= "{row:$each_count,name:'$n',msg:'$msg'},";
            }


            $digits = $d->digit; //前缀
            //判断是否存在
            $dig_s = $this->Product->query("select item_id from product_items where digits = '$digits' and product_id = '$product_id'");
            //文件中的路由前缀为空、不是数字、长度大于20、已经存在的情况
            //记录错误信息
            if (empty($digits))
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('upload_route_is_null', true);
                $upload_error .= "{row:$each_count,name:'$dg',msg:'$msg'},";
            }

            if (!preg_match('/^[0-9]{1,11}$/', $digits))
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('upload_route_format', true);
                $upload_error .= "{row:$each_count,name:'$dg',msg:'$msg'},";
            }

            if (count($dig_s) > 0)
            {
                $hasError = true;
                $msg = __('exists', true);
                $errorFlag = true;
                $upload_error .= "{row:$each_count,name:'$dg',msg:'$msg'},";
            }

            $strategy = $d->strategy; //策略
//			if ($strategy == 'top-down') {
//				$strategy = 1;
//			} else if ($strategy == 'round-robin') {
//				$strategy = 2;
//			} else if ($strategy == 'weight') {
//				$strategy = 0;
//			} else {
//				$strategy = 1;
//			}
            if ($strategy != 0 && $strategy != 1 && $strategy != 2)
            {
                $hasError = true;
                $msg = __('policyerror', true);
                $errorFlag = true;
                $upload_error .= "{row:$each_count,name:'$policy',msg:'$msg'},";
            }
            $start_date = $d->startdate; //开始时间
            $date_er = false;
            if (!empty($start_date))
            {
                if (!preg_match('/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})/', $start_date))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $date_er = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$st',msg:'$msg'},";
                }
            }
            $end_date = $d->enddate; //结束时间
            if (!empty($start_date))
            {
                if (!preg_match('/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})/', $end_date))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $date_er = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$et',msg:'$msg'},";
                }
            }
            $time_profile = 'null';
            if ($date_er == false && !empty($start_date) && !empty($end_date))
            {
                $timeprofiles = $this->Product->query("select time_profile_id from time_profile 
																			where start_date = '$start_date'
																			and end_date = '$end_date'");
                if (count($timeprofiles) > 0)
                {
                    $time_profile = $timeprofiles [0] [0] ['time_profile_id'];
                }
                else
                {
                    //创建一个Time_profile?
                }
            }
            $route1 = trim($d->tid1);
            $routes1 = $this->Product->query("select resource_id from resource where name = '$route1'");
            if (count($routes1) > 0)
            {
                $route1 = $routes1 [0] [0] ['resource_id'];
            }
            else
            {
                $route1 = 'null';
            }
            $route2 = trim($d->tid2);
            $routes2 = $this->Product->query("select resource_id from resource where name = '$route2'");
            if (count($routes2) > 0)
            {
                $route2 = $routes2 [0] [0] ['resource_id'];
            }
            else
            {
                $route2 = 'null';
            }
            $route3 = trim($d->tid3);
            $routes3 = $this->Product->query("select resource_id from resource where name = '$route3'");
            if (count($routes3) > 0)
            {
                $route3 = $routes3 [0] [0] ['resource_id'];
            }
            else
            {
                $route3 = 'null';
            }
            $route4 = trim($d->tid4);
            $routes4 = $this->Product->query("select resource_id from resource where name = '$route4'");
            if (count($routes4) > 0)
            {
                $route4 = $routes4 [0] [0] ['resource_id'];
            }
            else
            {
                $route4 = 'null';
            }
            $route5 = trim($d->tid5);
            $routes5 = $this->Product->query("select resource_id from resource where name = '$route5'");
            if (count($routes5) > 0)
            {
                $route5 = $routes5 [0] [0] ['resource_id'];
            }
            else
            {
                $route5 = 'null';
            }
            $route6 = trim($d->tid6);
            $routes6 = $this->Product->query("select resource_id from resource where name = '$route6'");
            if (count($routes6) > 0)
            {
                $route6 = $routes6 [0] [0] ['resource_id'];
            }
            else
            {
                $route6 = 'null';
            }
            $route7 = trim($d->tid7);
            $routes7 = $this->Product->query("select resource_id from resource where name = '$route7'");
            if (count($routes7) > 0)
            {
                $route7 = $routes7 [0] [0] ['resource_id'];
            }
            else
            {
                $route7 = 'null';
            }
            $route8 = trim($d->tid8);
            $routes8 = $this->Product->query("select resource_id from resource where name = '$route8'");
            if (count($routes8) > 0)
            {
                $route8 = $routes8 [0] [0] ['resource_id'];
            }
            else
            {
                $route8 = 'null';
            }

            $weight1 = $d->percentage1;

            if ($weight1 == '' || strtolower($weight1) == 'none' || strtolower($weight1) == 'null')
            {
                $weight1 = 'null';
            }

            if (!empty($weight1) && $weight1 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight1))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 1',msg:'$msg'},";
                }
            }

            $weight2 = $d->percentage2;

            if ($weight2 == '' || strtolower($weight2) == 'none' || strtolower($weight2) == 'null')
            {
                $weight2 = 'null';
            }

            if (!empty($weight2) && $weight2 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight2))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 2',msg:'$msg'},";
                }
            }

            $weight3 = $d->percentage3;
            if ($weight3 == '' || strtolower($weight3) == 'none' || strtolower($weight3) == 'null')
            {
                $weight3 = 'null';
            }

            if (!empty($weight3) && $weight3 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight3))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 3',msg:'$msg'},";
                }
            }

            $weight4 = $d->percentage4;
            if ($weight4 == '' || strtolower($weight4) == 'none' || strtolower($weight4) == 'null')
            {
                $weight4 = 'null';
            }

            if (!empty($weight4) && $weight4 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight4))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 4',msg:'$msg'},";
                }
            }

            $weight5 = $d->percentage5;
            if ($weight5 == '' || strtolower($weight5) == 'none' || strtolower($weight5) == 'null')
            {
                $weight5 = 'null';
            }

            if (!empty($weight5) && $weight5 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight5))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 5',msg:'$msg'},";
                }
            }

            $weight6 = $d->percentage6;
            if ($weight6 == '' || strtolower($weight6) == 'none' || strtolower($weight6) == 'null')
            {
                $weight6 = 'null';
            }

            if (!empty($weight6) && $weight6 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight6))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 6',msg:'$msg'},";
                }
            }

            $weight7 = $d->percentage7;
            if ($weight7 == '' || strtolower($weight7) == 'none' || strtolower($weight7) == 'null')
            {
                $weight7 = 'null';
            }

            if (!empty($weight7) && $weight7 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight7))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 7',msg:'$msg'},";
                }
            }

            $weight8 = $d->percentage8;
            if ($weight8 == '' || strtolower($weight8) == 'none' || strtolower($weight8) == 'null')
            {
                $weight8 = 'null';
            }

            if (!empty($weight8) && $weight8 != 'null')
            {
                if (!preg_match('/^[0-9]+$/', $weight8))
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('upload_route_format', true);
                    $upload_error .= "{row:$each_count,name:'$ptg 8',msg:'$msg'},";
                }
            }

            $todtype = $d->todtype;

            if ($todtype == '' || $todtype == 'null' || $todtype == 'none')
            {
                $todtype = 'null';
            }

            if ($todtype != 0 && $todtype != 1 && $todtype != 2)
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('todtypeerror', true);
                $upload_error .= "{row:$each_count,name:'$todt 8',msg:'$msg'},";
            }

            $start_tod = $d->starttod;

            if ($start_tod == '' || $start_tod == 'null' || $start_tod == 'none')
            {
                $start_tod = 'null';
            }

            if ($start_tod != 0 && $start_tod != 1 && $start_tod != 2 && $start_tod != 3 && $start_tod != 4 && $start_tod != 5 && $start_tod != 6)
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('starttoderror', true);
                $upload_error .= "{row:$each_count,name:'$stodt',msg:'$msg'},";
            }

            $end_tod = $d->endtod;

            if ($end_tod == '' || $end_tod == 'null' || $end_tod == 'none')
            {
                $end_tod = 'null';
            }

            if ($end_tod != 0 && $end_tod != 1 && $end_tod != 2 && $end_tod != 3 && $end_tod != 4 && $end_tod != 5 && $end_tod != 6)
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('starttoderror', true);
                $upload_error .= "{row:$each_count,name:'$edot',msg:'$msg'},";
            }

            if ($todtype == 0 || $todtype == 2)
            {
                if ($start_tod != 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttoderror', true);
                    $upload_error .= "{row:$each_count,name:'$stodt',msg:'$msg'},";
                }

                if ($end_tod != 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('endtoderror', true);
                    $upload_error .= "{row:$each_count,name:'$edot',msg:'$msg'},";
                }
            }

            if ($todtype == 1)
            {
                if ($start_tod == '' || $start_tod > $end_tod)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttoderror2', true);
                    $upload_error .= "{row:$each_count,name:'$stodt',msg:'$msg'},";
                }

                if ($end_tod == '' || $end_tod < $start_tod)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttoderror3', true);
                    $upload_error .= "{row:$each_count,name:'$edot',msg:'$msg'},";
                }
            }

            $st_time = $d->starttime;
            $et_time = $d->endtime;
            if (empty($st_time))
            {
                $st_time = 'null';
            }

            if (empty($et_time))
            {
                $et_time = 'null';
            }

            if ($todtype == 0)
            {
                if ($st_time != 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttimeerror', true);
                    $upload_error .= "{row:$each_count,name:'$stime',msg:'$msg'},";
                }
                if ($et_time != 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttimeerror2', true);
                    $upload_error .= "{row:$each_count,name:'$etime',msg:'$msg'},";
                }
            }

            if ($todtype == 1 || $todtype == 2)
            {
                if ($st_time == '' || $st_time == null || $st_time > $et_time)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttimeerror1', true);
                    $upload_error .= "{row:$each_count,name:'$stime',msg:'$msg'},";
                }

                if ($et_time == '' || $et_time == null || $et_time < $st_time)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('starttimeerror3', true);
                    $upload_error .= "{row:$each_count,name:'$stime',msg:'$msg'},";
                }
            }


            $min_length = $d->minlength;
            $max_length = $d->maxlength;

            if (!empty($min_length))
            {
                if ($min_length < 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('minlen0', true);
                    $upload_error .= "{row:$each_count,name:'$minlength',msg:'$msg'},";
                }
            }
            else
                $min_length = 'null';

            if (!empty($max_length))
            {
                if ($max_length < 0)
                {
                    $hasError = true;
                    $errorFlag = true;
                    $msg = __('maxlen0', true);
                    $upload_error .= "{row:$each_count,name:'$maxlength',msg:'$msg'},";
                }
            }
            else
                $max_length = 'null';


            //有错误继续验证下一行
            if ($errorFlag == true)
            {
                $affectRows--;
            }


            //插入数据
            switch ($action)
            {
                case 1 : //Overwrite
                    $isexists = $this->Product->query("
																					select item_id from product_items 
																					where digits = '$digits' and product_id = '$product_id'
																				");
                    if (count($isexists) > 0)
                    {
                        $item_id = $isexists [0] [0] ['item_id'];
                        $qs = $this->Product->query("update product_items 
																										set alias='$alias',digits='$digits',
																										strategy=$strategy,time_profile_id=$time_profile,
																										start_time='$st_time',end_time='$et_time',
																										start_week=$start_tod,end_week=$end_tod,
																										min_len=$min_length,max_len=$max_length
																										where item_id = $item_id");
                        //记录是否出错
                        if (count($qs) != 0)
                        {
                            $query_result_sets++;
                        }
                    }
                    else
                    {
                        $qs = $this->insert($alias, $digits, $strategy, $time_profile, $route1, $route2, $route3, $route4, $route5, $route6, $route7, $route8, $weight1, $weight2, $weight3, $weight4, $weight5, $weight6, $weight7, $weight8, $product_id, $st_time, $et_time, $min_length, $max_length, $start_tod, $end_tod);
                        //记录是否有错误	
                        if (count($qs) != 0)
                        {
                            $query_result_sets++;
                        }
                    }
                    break;

                case 2 : //Remove
                    $isexists = $this->Product->query("
																					select item_id from product_items 
																					where digits = '$digits' and product_id = '$product_id'
																				");
                    if (count($isexists) > 0)
                    {
                        continue;
                    }
                    else
                    {
                        $qs = $this->insert($alias, $digits, $strategy, $time_profile, $route1, $route2, $route3, $route4, $route5, $route6, $route7, $route8, $weight1, $weight2, $weight3, $weight4, $weight5, $weight6, $weight7, $weight8, $product_id, $st_time, $et_time, $min_length, $max_length, $start_tod, $end_tod);
                        //记录是否有错误	
                        if (count($qs) != 0)
                        {
                            $query_result_sets++;
                        }
                    }
                    break;

                case 3 : //Refresh
                    $qs = $this->insert($alias, $digits, $strategy, $time_profile, $route1, $route2, $route3, $route4, $route5, $route6, $route7, $route8, $weight1, $weight2, $weight3, $weight4, $weight5, $weight6, $weight7, $weight8, $product_id, $st_time, $et_time, $min_length, $max_length, $start_tod, $end_tod);
                    //记录是否有错误	
                    if (count($qs) != 0)
                    {
                        $query_result_sets++;
                    }
                    break;
            }

            $each_count++;
        }

        $this->Session->write('upload_commited_rows', $affectRows);

        //有行数据格式错误  返回错误信息
        if ($hasError == true)
        {
            $upload_error = substr($upload_error, 0, strlen($upload_error) - 1) . "]";
            $this->Session->write("upload_route_error", $upload_error);

            if ($isroll == 'true')
            { //需要回滚
                $this->Product->rollback();
                $this->Session->write('upload_commited_rows', 0);
            }
            else
            { //忽略错误提交
                $this->Product->commit();
            }

            $this->redirect('/products/route_info/' . $product_id);
            exit();
        }
        else
        {
            $this->Product->commit();
        }

//		if ($query_result_sets != 0) { //有错误
//			if ($isroll == 'true') { //需要回滚
//				$this->Product->rollback ();
//				$this->Session->write('upload_commited_rows',0);
//			} else { //忽略错误提交
//				$this->Product->commit ();
//			}
//		} else {
//			$this->Product->commit ();
//		}
//		
        $this->redirect('/products/route_info/' . $product_id);
    }

    /*
     * 下载某Product的所有Routes
     */

    function copy()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->_get('id');
        $name = $this->_get('name');
        $list = $this->Product->find('first', Array('conditions' => Array('product_id' => $id)));

        if (empty($list))
        {
            $this->Session->write('m', $this->Product->create_json(101, 'Failed!'));
            $this->xredirect('/products/product_list');
        }
        $this->Product->id = false;
        $list['Product']['name'] = $name;
        unset($list['Product']['product_id']);
        $flg = 0;
        $this->Product->begin();
        if (!$this->Product->save($list))
        {
            $this->Product->rollback();
        }
        $newId = $this->Product->getLastInsertId();

        $this->loadModel('Productitem');
        $lists = $this->Productitem->find('all', Array('conditions' => Array('product_id' => $id)));

        foreach ($lists as $list)
        {
            $old_item = $list['StaticRoute']['item_id'];
            $list['StaticRoute']['product_id'] = $newId;
            $list['StaticRoute']['item_id'] = false;
            if (!$this->Productitem->save($list))
            {
                $this->Product->rollback();
            }

            $sql = "select item_id from product_items where product_id = '{$newId}' order by item_id desc";
            $item = $this->Productitem->query($sql, false);
            if (!isset($item[0][0]['item_id']))
            {
                $this->Product->rollback();
            }

            $sql = "insert into product_items_resource(resource_id, item_id) select resource_id,{$item[0][0]['item_id']} from product_items_resource where item_id = {$old_item};";
            $this->Productitem->query($sql, false);
//            if (!$this->Product->getLastInsertId())
//            {
//                $this->Product->rollback();
//            }
        }
        $this->Product->commit();

//        if ($flg)
//        {
//            $this->Session->write('m', $this->Product->create_json(201, 'The Static Route has been copied successfully!'));
//        }
//        else
//        {
//            $this->Session->write('m', $this->Product->create_json(101, 'Copy failed!'));
//        }
        $this->xredirect('/products/product_list');
    }

    function updateselect($id)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($_POST['action'] == 'delete')
        {
            $this->Product->query("delete from product_items where item_id in ({$_POST['select_id']})");
            $this->Product->query("delete from product_items_resource where item_id in ({$_POST['select_id']})");
            $this->Product->create_json_array('', 101, __('delete successfully!', true));
            $this->Session->write('m', Product::set_validator());
            $this->redirect('/products/route_info/' . $id);
        }

        if ($_POST['strategy'])
        {
            $set_sql = " strategy = {$_POST['strategy']}";
        }
        if ($_POST["time_profile"] != "0")
        {
            if ($set_sql)
            {
                $sql .= ", time_profile_id = {$_POST['time_profile']}";
            }
            else
            {
                $set_sql = " time_profile_id = {$_POST['time_profile']}";
            }
        }
        if ($set_sql)
        {
            $sql = "update product_items set {$set_sql} where item_id in ({$_POST['select_id']})";
            $this->Product->query($sql);
        }

        $this->Product->query("delete from product_items_resource where item_id in ({$_POST['select_id']})");
        $arr = explode(',', $_POST['select_id']);
        foreach ($arr as $aid)
        {
            if ($_POST['trunk'][0] != '0')
            {
                foreach ($_POST['trunk'] as $key => $trunk)
                {
                    $percent = (isset($_POST['percent'][$key]) && $_POST['percent'][$key] != '') ? $_POST['percent'][$key] : 'NULL';
                    $sql2 = "insert into product_items_resource (item_id, resource_id, by_percentage)
					values({$aid}, $trunk, $percent)";
                    $this->Product->query($sql2);
                }
            }
        }
        $this->Product->log('updateselect_product_items');

        $this->Product->create_json_array('', 201, __('update successfully!', true));
        $this->Session->write('m', Product::set_validator());
        $this->redirect('/products/route_info/' . $id);
    }

    function updateselectCodeName($id)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $select_ids = str_ireplace('{', "", $_POST['select_id']);
        $select_ids = str_ireplace('}', "", $select_ids);

        if ($_POST['action'] == 'delete')
        {

            $this->Product->query("delete from product_items where item_id in ({$select_ids})");
            $this->Product->query("delete from product_items_resource where item_id in ({$select_ids})");
            $this->Product->create_json_array('', 101, __('delete successfully!', true));
            $this->Session->write('m', Product::set_validator());
            $this->redirect('/products/route_info/' . $id);
        }


        $sql = "update product_items set
			strategy = {$_POST['strategy']}";
        if ($_POST["time_profile"] != "0")
        {
            $sql .= ", time_profile_id = {$_POST['time_profile']}";
        }
        $sql .=" where item_id in ({$select_ids})";


        $this->Product->query($sql);
        $this->Product->query("delete from product_items_resource where item_id in ({$select_ids})");
        $arr = explode(',', $select_ids);



        foreach ($arr as $aid)
        {
            if ($_POST['trunk'][0] != '0')
            {
                foreach ($_POST['trunk'] as $key => $trunk)
                {
                    $percent = (isset($_POST['percent'][$key]) && $_POST['percent'][$key] != '') ? $_POST['percent'][$key] : 'NULL';
                    $sql2 = "insert into product_items_resource (item_id, resource_id, by_percentage)
					values({$aid}, $trunk, $percent)";
                    $this->Product->query($sql2);
                }
            }
        }

        $this->Product->log('updateselect_product_items');

        $this->Product->create_json_array('', 201, __('update successfully!', true));
        $this->Session->write('m', Product::set_validator());
        $this->redirect('/products/route_info/' . $id);
    }

    function check_name($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $conditions = Array();
        $name = $this->_get('name');
        if (!empty($name))
        {
            $conditions[] = "name = '$name'";
        }
        if (!empty($id))
        {
            $conditions[] = "id <> $id";
        }
        $list = $this->Product->find('count', Array('conditions' => $conditions));
        if ($list > 0)
        {
            echo "false";
        }
    }

    public function download($product_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = '';

        $datas = $this->Product->getDownInfoById($product_id); //下载文件显示的数据
        //没有数据
        if (count($datas) == 0)
        {
            $this->Session->write('m', $this->Product->create_json(101, __('no_data_found', true)));
            $this->redirect('/products/route_info/' . $product_id);
            exit();
        }

        //文件第一行显示的标题
        $title = "ID,Alias,Prefix,Strategy,Start Date,End Date,Route1,Route2,Route3,Route4,Route5,Route6,Route7,Route8,Weight1,Weight2,Weight3,Weight4,Weight5,Weight6,Weight7,Weight8";

        $this->Product->downLoadFile($title, $datas, time());
    }

    /*
     * Get all products
     */

    public function getallproducts()
    {
        Configure::write('debug', 0);
        $reseller_id = $this->Session->read('sst_reseller_id');
        $rs = $this->Product->getAllProduct($reseller_id);
        echo $rs;
    }

    /*
     * 交换Product
     */

    public function swapproducts($product_id_1, $product_id_2)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        if ($this->Product->swapProduct($product_id_1, $product_id_2))
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
    }

    /*
     * 下载文件模板
     */

    public function downloadtemplate($type)
    {
        Configure::write('debug', 0);
        $this->layout = '';
        $datas = array();
        $title = '';
        if (!empty($type))
        {
            if ($type == 't')
            {
                $datas = array(
                    array(array(
                        '10010', '10011', '100101', '100111', '0', '0'
                    )),
                    array(array(
                        '0755', '00755', '100101', '100111', '1', '1'
                    )),
                    array(array(
                        '0739', '07396', '100101', '100111', '2', '2'
                    ))
                );

                $title = "origani,origdnis,transani,transdnis,aniaction,dnisaction";
            }
            else
            {
                $datas = array(
                    array(array(
                        '0755', '中华人民共和国', '广东', '深圳'
                    )),
                    array(array(
                        '0739', '中华人民共和国', '湖南', '邵阳'
                    )),
                    array(array(
                        '085', 'English', 'England', 'Cumbria'
                    ))
                );

                $title = "code,country,state,city";
            }
        }
        else
        {
            $datas = array(
                array(array(
                    'static_route', '0755', '0', '2010-06-18 12:00:00', '2010-06-21 12:00:00', 'alias_1', 'alias_2', 'alias_3', 'alias_4', 'alias_5', 'alias_6', 'alias_7', 'alias_8', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')),
                array(array(
                    'static_route_1', '0739', '1', '2010-06-18 12:00:00', '2010-06-21 12:00:00', '', '', '', '', '', '', '', '', '10', '10', '15', '15', '15', '10', '10', '10', '', '', '', '', '', '', '')),
                array(array(
                    'static_route_2', '0731', '2', '2010-06-18 12:00:00', '2010-06-21 12:00:00', 'alias_1', 'alias_2', 'alias_3', 'alias_4', 'alias_5', 'alias_6', 'alias_7', 'alias_8', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''))
            );

            //文件第一行显示的标题
            $title = "alias,digit,strategy,startdate,enddate,tid1,tid2,tid3,tid4,tid5,tid6,tid7,tid8,percentage1,percentage2,percentage3,percentage4,percentage5,percentage6,percentage7,percentage8,todtype,starttime,endtime,starttod,endtod,minlength,maxlength";
        }

        $this->Product->downLoadFile($title, $datas, 'template');
    }

    /*
     * 删除所有
     */

    function js_save()
    {
        $this->layout = "ajax";
    }

    public function del_all_pro()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->Product->begin();
        $qs_c = 0;
        $qs = $this->Product->query("delete from product_items");
        $qs_c += count($qs);
        $qs = $this->Product->query("delete from product");
        $qs_c += count($qs);
//		$qs = $this->Product->query("delete from resource_product_ref");
//		$qs_c += count($qs);

        if ($qs_c == 0)
        {
            $this->Product->create_json_array('', 201, __('delallprosuc', true));
            $this->Product->commit();
            $this->Product->log('deleteall_product');
        }
        else
        {
            $this->Product->create_json_array('', 101, __('delallprofail', true));
            $this->Product->rollback();
        }

        $this->Session->write('m', Product::set_validator());
        $this->redirect('/products/product_list');
    }

    /*
     * 删除选中路由表
     */

    public function del_selected_pro()
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrName = $this->Product->getNameByids($ids);
        foreach ($arrName as $name)
        {
            $tip.=$name[0][name] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $this->Product->begin();
        $qs_c = 0;
        $qs = $this->Product->query("delete from product_items where product_id in ($ids)");
        $qs_c += count($qs);
        $qs = $this->Product->query("delete from product where product_id in ($ids)");
        $qs_c += count($qs);
//	$qs =	$this->Product->query("delete from resource_product_ref where product_id in ($ids)");
//	$qs_c += count($qs);

        if ($qs_c == 0)
        {
            $this->Product->create_json_array('', 201, __('The Static Routing %s is deleted successfully.', true, $tip));
            $this->Product->commit();
            $this->Product->log('deleteselect_product_items');
        }
        else
        {
            $this->Product->create_json_array('', 101, __('Fail to delete Static Routing.', true));
            $this->Product->rollback();
        }

        $this->Session->write('m', Product::set_validator());
        $this->redirect('/products/product_list');
    }

    function static_js_save()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    function js_save_prefix($trunk_type, $id = null)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        $this->layout = 'ajax';
        if ($id)
        {
            $this->data = $this->Productitem->find('first', Array('conditions' => Array('item_id' => $id)));
            $this->data['sel'] = $this->Productitem->jsresource($id);
        }

        $resources = $this->Resource->find('all', array(
            'conditions' => array('Resource.egress' => true, 'Resource.trunk_type2' => $trunk_type),
        ));

        $client_resources = array();

        foreach ($resources as $resource_item)
        {
            $client_resources[$resource_item['Resource']['client_id']][$resource_item['Resource']['resource_id']] = $resource_item['Resource']['alias'];
        }

        $this->set('client_resources', $client_resources);
        if ($trunk_type == 1)
            $this->_render_set_options('TimeProfile,Client,Resource', Array('Client' => Array('order' => 'name asc', 'conditions' => Array('(select count(*) as sum from resource where egress=true and resource.client_id="Client".client_id)>0', 'client_type =1')), 'Resource' => Array('order' => 'alias asc')));
        else
            $this->_render_set_options('TimeProfile,Client,Resource', Array('Client' => Array('order' => 'name asc', 'conditions' => Array('(select count(*) as sum from resource where egress=true and resource.client_id="Client".client_id)>0', 'client_type is null')), 'Resource' => Array('order' => 'alias asc')));
    }

    function js_save_code_name($product_id, $code_name = null)
    {

        Configure::write('debug', 0);
        $sst_user_id = $_SESSION['sst_user_id'];
        $this->layout = 'ajax';
        if ($code_name)
        {
            $this->data = $this->Productitem->find('first', Array('conditions' => Array('code_name' => $code_name)));
            $this->data['sel'] = $this->Productitem->jsresource($this->data['StaticRoute']['item_id']);
        }


        $code_deck_id = $this->Productitem->query("select code_deck_id from product where product_id = '$product_id'");

        $this->set('codes', $this->Productitem->query("select name from code where name <> '' and code_deck_id = " . $code_deck_id[0][0]['code_deck_id'] . " group by name order by name asc"));
        $this->_render_set_options('TimeProfile,Client,Resource', Array('Client' => Array('order' => 'name asc', 'conditions' => Array('(select count(*) from resource where egress=true and resource.client_id="Client".client_id)>0')), 'Resource' => Array('order' => 'alias asc')));
    }

    function check_route_info_name($id = null)
    {
        $conditions = Array();
        if (!empty($id))
        {
            $conditions[] = "item_id <> $id";
        }
        $name = $this->_get('name');
        if (!empty($name))
        {
            if ($name == 'empty')
            {
                $name = "";
            }
            $conditions[] = "digits = '$name'";
        }
        $product_id = $this->_get('product_id');
        if (!empty($product_id))
        {
            $conditions[] = "product_id='$product_id'";
        }
        $count = $this->Productitem->find('count', Array('conditions' => $conditions));
        if ($count > 0)
        {
            echo 'false';
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    public function data_edit($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $this->data = $this->Product->find('first', Array('conditions' => Array("product_id=$id")));
        $this->set('code_decks', $this->Product->query('select * from code_deck'));
    }

    //取得rate和cpslimit & call limit 
    public function get_rate($trunk_id, $prefix = '')
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;

        if ($trunk_id) {
            $sql = "SELECT rate FROM rate 
WHERE 
effective_date < now() and (end_date > now() or end_date is null) 
AND rate_table_id = (SELECT rate_table_id FROM resource where resource_id = {$trunk_id}) AND code = '{$prefix}'";
            $results = $this->Product->query($sql);
            $json_arr = array(
                'rate' => 0,
                'cps_limit' => '',
                'capacity' => ''
            );

            if (isset($results[0][0]['rate']))
            {
                $json_arr['rate'] = $results[0][0]['rate'];
            }
            $sql = "SELECT cps_limit, capacity from resource where resource_id = '{$trunk_id}'";
            $result_resource = $this->Product->query($sql);

            if ($result_resource)
            {
                $json_arr['cps_limit'] = $result_resource[0][0]['cps_limit'];
                $json_arr['capacity'] = $result_resource[0][0]['capacity'];
            }

            echo json_encode($json_arr);
        } else {
            echo false;
        }
    }

    //判断新增时间段是否和product所有时间段有交集
    function check_time_profile($digits, $time_profile_id = null, $product_id = null, $id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        if (!empty($time_profile_id) && !empty($product_id))
        {
            $sql = "select t.type,t.start_week , t.end_week  ,t.start_time, t.end_time
                from product p ,product_items pt,time_profile t 
                where p.product_id = pt.product_id 
                and pt.time_profile_id = t.time_profile_id 
                and p.product_id = '$product_id' and pt.digits = '$digits'";

            if (!empty($id))
            {
                $sql = $sql . " and pt.item_id <> " . $id;
            }

            $pro_same_time = $this->Product->query($sql . " and t.time_profile_id = " . $time_profile_id);

            if (count($pro_same_time) >= 1)
            {
                return 'not'; //时间段已经存在.
            }
            else
            {
                $pro_times = $this->Product->query($sql);
                $pro_new_time = $this->Product->query("select type ,start_week , end_week , start_time , end_time from time_profile where time_profile_id = " . $time_profile_id);
                //var_dump($pro_times);
                if (count($pro_times) > 0)
                {
                    if ($pro_new_time[0][0]['type'] == 0)
                    {//新添加的时间段类型为all的时候
                        return "not";
                    }
                    else if ($pro_new_time[0][0]['type'] == 1)
                    {//新添加的时间段类型weekly的时候
                        foreach ($pro_times as $pro_time)
                        {
                            if ($pro_time[0]['type'] == 0)
                            {//原有的时间类型有all的时候
                                return "not";
                                break;
                            }
                            else if ($pro_time[0]['type'] == 2)
                            {//原有时间类型为daily的时候
                                if ($this->get_time($pro_new_time[0][0]['start_time']) == 0 || $this->get_time($pro_new_time[0][0]['end_time']) == 0)
                                {
                                    return "not";
                                    break;
                                }
                                else
                                {
                                    $a = $this->get_time($pro_time[0]['start_time'], 0);
                                    $b = $this->get_time($pro_time[0]['end_time'], 0);
                                    $c = $this->get_time($pro_new_time[0][0]['start_time'], 0);
                                    $d = $this->get_time($pro_new_time[0][0]['end_time'], 0);
                                    $e = $this->get_time('00:00:00', 0);
                                    $g = $this->get_time('23:59:59', 0);

                                    $week_diff = abs($pro_new_time[0][0]['start_week'] - $pro_new_time[0][0]['end_week']);

                                    if ($week_diff > 1)
                                    {
                                        return "not";
                                        break;
                                    }
                                    else
                                    {
                                        $is_mix_start = $this->is_mix_time($c, $g, $a, $b);
                                        $is_mix_start = $this->is_mix_time($e, $d, $a, $b);
                                        if ($is_mix_start || $is_mix_start)
                                        {
                                            return "not";
                                            break;
                                        }
                                    }
                                }
                            }
                            else
                            {//原有时间类型为weekly的时候
                                $pro_new_start_time = ($pro_new_time[0][0]['start_time'] == 0) ? "00:00:00" : $pro_new_time[0][0]['start_time'];
                                $pro_new_end_time = ($pro_new_time[0][0]['end_time'] == 0) ? "23:59:59" : $pro_new_time[0][0]['end_time'];
                                $pro_start_time = ($pro_time[0]['start_time'] == 0) ? "00:00:00" : $pro_time[0]['start_time'];
                                $pro_end_time = ($pro_time[0]['end_time'] == 0) ? "00:00:00" : $pro_time[0]['end_time'];

                                $pro_new_week_diff = abs($pro_new_time[0][0]['start_week'] - $pro_new_time[0][0]['end_week']);
                                $pro_week_diff = abs($pro_time[0]['start_week'] - $pro_time[0]['end_week']);

                                //2个时间段的开始周相差多少天
                                $pro_week_start_diff = abs($pro_new_time[0][0]['start_week'] - $pro_time[0]['start_week']);

                                if ($pro_new_time[0][0]['start_week'] > $pro_time[0]['start_week'])
                                {
                                    if ($this->is_mix_time($this->get_time($pro_new_start_time, $pro_week_start_diff), $this->get_time($pro_new_end_time, $pro_new_week_diff + $pro_week_start_diff), $this->get_time($pro_start_time, 0), $this->get_time($pro_end_time, 0 + $pro_week_diff)))
                                    {
                                        return "not";
                                        break;
                                    }
                                }
                                else
                                {
                                    if ($this->is_mix_time($this->get_time($pro_start_time, $pro_week_start_diff), $this->get_time($pro_end_time, $pro_week_diff + $pro_week_start_diff), $this->get_time($pro_new_start_time, 0), $this->get_time($pro_end_time, 0 + $pro_new_week_diff)))
                                    {
                                        return "not";
                                        break;
                                    }
                                }
                            }
                        }

                        //遍历结束没有交集
                        return "yes";
                    }
                    else
                    {//新添加的时间段类型为daily的时候
                        foreach ($pro_times as $pro_time)
                        {
                            if ($this->get_time($pro_time[0]['start_time']) == 0 || $this->get_time($pro_time[0]['end_time']) == 0)
                            {
                                return 'not';
                                break;
                            }
                            else
                            {
                                $a = $this->get_time($pro_time[0]['start_time'], 0);
                                $b = $this->get_time($pro_time[0]['end_time'], 0);
                                $c = $this->get_time($pro_new_time[0][0]['start_time'], 0);
                                $d = $this->get_time($pro_new_time[0][0]['end_time'], 0);
                                $e = $this->get_time('00:00:00', 0);
                                $g = $this->get_time('23:59:59', 0);

                                if ($pro_time[0]['type'] == 1)
                                {//原有的时间类型有weekly的时候
                                    $week_diff = abs($pro_time[0]['start_week'] - $pro_time[0]['end_week']);

                                    if ($week_diff > 1)
                                    {
                                        return "not";
                                        break;
                                    }
                                    else
                                    {
                                        $is_mix_start = $this->is_mix_time($a, $g, $c, $d);
                                        $is_mix_start = $this->is_mix_time($e, $b, $c, $d);
                                        if ($is_mix_start || $is_mix_start)
                                        {
                                            return "not";
                                            break;
                                        }
                                    }
                                }
                                else
                                {//原有的时间类型有daily的时候
                                    $is_mix = $this->is_mix_time($a, $b, $c, $d);
                                    if ($is_mix)
                                    {
                                        return "not";
                                        break;
                                    }
                                }
                            }
                        }
                        //循环对比之后，判断没有交集
                        return "yes";
                    }
                }
                else
                {
                    return "yes";
                }
            }
        }
        else
        {
            return 'not';
        }
    }

    //比较2个时间段是否有交集
    function is_mix_time($begintime1, $endtime1, $begintime2, $endtime2)
    {
        $status = $begintime2 - $begintime1;
        if ($status > 0)
        {
            $status2 = $begintime2 - $endtime1;
            if ($status2 > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $status2 = $begintime1 - $endtime2;
            if ($status2 > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    //把时间换成当天的这个时间的时间锉
    function get_time($time, $week_diff)
    {
        if ($time == "")
        {
            return 0;
        }
        else
        {
            $str_time = explode(":", $time);
            return mktime($str_time[0], $str_time[1], $str_time[2], date('m') + $week_diff, date('d'), date('Y'));
        }
    }

    //恢复product item
    function _do_del_rollback($data_arr)
    {
        $rollback_sql = "";
        foreach ($data_arr as $item)
        {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $item['StaticRoute'];
            unset($old_data_arr['item_id']);
            unset($old_data_arr['product_id']);
            unset($old_data_arr['update_at']);
            $old_data_arr['update_by'] = $_SESSION['sst_user_name'];
            foreach ($old_data_arr as $key => $value)
            {
                if ($value)
                {
                    $filed_arr[] = $key;
                    $int_arr = array(
                        'strategy', 'time_profile_id', 'min_len', 'max_len', 'min_pdd', 'max_pdd'
                    );
                    if (in_array($key, $int_arr))
                    {
                        if (!$value)
                        {
                            $value = 0;
                        }
                        $value_arr[] = $value;
                    }
                    else
                    {
                        $value_arr[] = "'" . $value . "'";
                    }
                }
            }
            $filed_arr[] = "product_id";
            $value_arr[] = "{product_id}";
            $filed_str = implode(',', $filed_arr);
            $value_str = "(" . implode(',', $value_arr) . ")";
            $rollback_sql .= "INSERT INTO product_items ({$filed_str}) VALUES {$value_str};";
        }

//        pr($rollback_sql);
////        pr($rollback_sql);
////        pr($value_str_arr);
////        pr($value_str_sql);
//        die;
        if (empty($value_arr))
        {
            return "";
        }
        return $rollback_sql;
    }

}
