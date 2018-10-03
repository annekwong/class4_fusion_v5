<?php

class CurrsController extends AppController {

    var $name = 'Currs';
    var $helpers = array('javascript', 'html', 'AppCurrs');

    //读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_curreny');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter(); //调用父类方法
    }

    public function currency_list() {
        $order = $this->_order_condtions(Array('currency_id', 'code', 'rate', 'last_modify', 'usage'));
        $currPage = 1;
        $pageSize = 100;
        $search = null;
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
            $sql = "select currency_id,code,active,
							(select count(rate_table_id) from rate_table where currency_id = curr.currency_id) as rates,
							(select rate from currency_updates 
								where currency_id = curr.currency_id 	
								and modify_time=(select max(modify_time) from currency_updates where currency_id = curr.currency_id)) as rate,
							(select max(modify_time) from currency_updates 
								where currency_id = curr.currency_id 	
							) as last_modify,
							(select count(rate_table_id) from rate_table 
								where currency_id  = curr.currency_id) as usage
							from currency as curr where currency_id = {$_REQUEST['edit_id']} $order";
            $result = $this->Curr->query($sql);
            //分页信息
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $reseller_id = $this->Session->read('sst_reseller_id');
            $results = $this->Curr->getAllCurrencies($currPage, $pageSize, $search, $reseller_id, $order);
        }
        $this->set('p', $results);
    }

    /*
     * 禁用或启用Reseller
     */

    public function active_or_not() {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $type = $_REQUEST['type'];
        $currency_id = $_REQUEST['r_id'];
        $active = 'true';
        if ($type == 1) {
            $active = 'true';
        } else {
            $active = 'false';
        }

        $qs = $this->Curr->query("update currency set active = $active where currency_id = '$currency_id'");
        if (count($qs) == 0)
            echo 'true';
        else
            echo 'false';
    }

    function _render_set_active_impl($id, $type = true) {
        $this->data = Array();
        $this->data['currency_id'] = $id;
        $this->data['active'] = $type;
        return $this->Curr->xsave($this->data);
    }

    function active($id,$is_config = "") {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $sql = "select code from currency where currency_id = {$id}";
        $messg_info = $this->Curr->query($sql);
        if ($this->_render_set_active_impl($id, true)) {
            $this->Session->write('m', $this->Curr->create_json_array("", 201, __('The currency [%s] is activated successfully!', true, $messg_info[0][0]['code'])));
          
        } else {
            $this->Session->write('m', $this->Curr->create_json_array("", 101, __('The currency [%s] is activated unsuccessfully!', true, $messg_info[0][0]['code'])));
            
        }
        if($is_config)
        {
            $this->xredirect("/necessary_configuration/currs");
        }
        $this->xredirect("/currs/index");
    }

    function disabled($id,$is_config = "") {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $sql = "select code from currency where currency_id = {$id}";
        $messg_info = $this->Curr->query($sql);
        if ($this->_render_set_active_impl($id, false)) {
            
            $this->Session->write('m', $this->Curr->create_json_array("", 201, __('The currency [%s] is deactivated successfully!', true, $messg_info[0][0]['code'])));
            
        } else {
            $this->Session->write('m', $this->Curr->create_json_array("", 101, __('The currency [%s] is deactivated unsuccessfully!', true, $messg_info[0][0]['code'])));
            
        }
        if($is_config)
        {
            $this->xredirect("/necessary_configuration/currs");
        }
        $this->xredirect("/currs/index");
    }

    public function add_currency() {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        $code = $_REQUEST['code'];
        $active = $_REQUEST['active'];
        $a = '';
        if ($active == 'y')
            $a = 'true';
        else
            $a = 'false';
        $reseller_id = $this->Session->read('sst_reseller_id');
        $sql = "insert into currency (code,active,update_by) values('$code',$a, '{$_SESSION['sst_user_name']}')";
        if (!empty($reseller_id))
            $sql = "insert into currency (code,active,reseller_id) values('$code',$a,'$reseller_id')";
        $qs = $this->Curr->query($sql);
        if (count($qs) == 0)
            $this->Curr->create_json_array('', 201, __('addcurrencysuc', true));
        else
            $this->Curr->create_json_array('', 101, __('addcurrencyfail', true));
        $this->Session->write('m', Curr::set_validator());
        $this->redirect('/currs/currency_list');
    }

    public function edit_currency() {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        $code = $_REQUEST['code'];
        $active = $_REQUEST['active'];
        $id = $_REQUEST['id'];
        $a = '';
        if ($active == 'y')
            $a = 'true';
        else
            $a = 'false';

        $qs = $this->Curr->query("update currency set code = '$code',active = $a, update_by = '{$_SESSION['sst_user_name']}' where currency_id = '$id'");



        if (count($qs) == 0)
            $this->Curr->create_json_array('', 201, __('editcurrencysuc', true));
        else
            $this->Curr->create_json_array('', 101, __('editcurrencyfail', true));

        $this->Session->write('m', Curr::set_validator());

        $this->redirect('/currs/currency_list?edit_id=' . $id);
    }

    public function check_currency() {
        Configure::write('debug', 0);
        $code = $_REQUEST['code'];
        if (empty($code))
            echo __('entercurrencycode', true);
        else {
            if (!empty($_REQUEST['curr_id'])) {
                $id = $_REQUEST['curr_id'];
                $codes = $this->Curr->query("select code from currency where currency_id = '$id'");
                if ($code != $codes[0][0]['code']) {
                    $qs = $this->Curr->query("select currency_id from currency where code = '$code'");
                    if (count($qs) == 0)
                        echo 'true';
                    else
                        echo __('currencycodeexists', true);
                } else
                    echo 'true';
            } else {
                $qs = $this->Curr->query("select currency_id from currency where code = '$code'");
                if (count($qs) == 0)
                    echo 'true';
                else
                    echo __('currencycodeexists', true);
            }
        }
    }

    public function del_currency($currency_id) {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        if ($currency_id == 'all') {
            $currency_id = "select currency_id from currency";
        }

        if ($currency_id == 'selected') {
            $currency_id = $_REQUEST['ids'];
        }

        $qs = $this->Curr->query("select rate_table_id,name from rate_table where currency_id in ($currency_id)");
        $rate_table_names =array();
        foreach($qs as $qs_item)
        {
            array_push($rate_table_names, $qs_item[0]['name']); 
        }
        $rate_table_names_str = implode(',', $rate_table_names);
        $this->_render_js_save_data($currency_id);
        if (count($qs) > 0) {
            $this->Curr->create_json_array('', 101, __('this Currency [%s] has already been used in the following rate table: %s', true, array($this->data['Curr']['code'],$rate_table_names_str)));
        } else {
            if ($this->Curr->del($currency_id))
                $this->Curr->create_json_array('', 201, __('The Currency [%s] is deleted successfully.', true, $this->data['Curr']['code']));
            else
                $this->Curr->create_json_array('', 101, __('Fail to deleted Currency.', true));
        }
        $this->Session->write('m', Curr::set_validator());
        $this->redirect('/currs/index');
    }

    public function currency_update($id) {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        $this->layout = '';
        $currPage = 1;
        $pageSize = 5;

        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];

        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];

        $results = $this->Curr->getAllUpdates($currPage, $pageSize, $id);

        $this->set('p', $results);

        $this->set('currecy_id', $id);
        $this->set('nowtime', date('Y-m-d H:i:s', time() + 6 * 60 * 60));
    }

    public function update_rate() {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $date = $_REQUEST['date'];
        $rate = $_REQUEST['rate'];
        $id = $_REQUEST['currency_id'];
        $hasError = false;
        if (empty($date)) {
            echo __('chooseupdate', true) . "|false";
            exit();
        }
        if (empty($rate)) {
            echo __('entercurrency', true) . "|false";
            exit();
        } else {
            if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/', $rate)) {
                echo __('currencyrateformat', true) . "|false";
                exit();
            }
        }


        $used = $this->Curr->query("select * from currency_updates where modify_time = '$date' and currency_id='$id'");
        if (count($used) > 0) {
            echo __('timeexists', true) . "|false";
            exit();
        }

        $last_updates = $this->Curr->query("select rate from currency_updates 
																where modify_time = (select max(modify_time) from currency_updates where modify_time < '$date') 
																and currency_id = '$id'");

        $last_rate = '0.000';
        if (count($last_updates) == 0)
            $last_rate = '0.000';
        else
            $last_rate = $rate - $last_updates[0][0]['rate'];

        $qs = $this->Curr->query("insert into currency_updates (currency_id,rate,last_rate,modify_time)
																values('$id','$rate','$last_rate','$date')");


        if (count($qs) == 0)
            echo __('updatecurrratesuc', true) . "|true";
        else
            echo __('updatecurrratefail', true) . "|false";
    }

    public function del_currency_updates() {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $id = $_REQUEST['id'];
        $qs = $this->Curr->query("delete from currency_updates where currency_updates_id = '$id'");
        if (count($qs) == 0)
            echo __('del_curr_suc', true) . "|true";
        else
            echo __('del_fail', true) . "|false";
    }

    function check_code($code, $id) {
        $id_where = '';
        if (!empty($id)) {
            $id_where = "and  currency_id<>$id";
        }
        $list = $this->Curr->query("select  code   from  currency   where  code='$code'  $id_where");
        if (!empty($list[0][0]['code'])) {
            return false;
        } else {

            return true;
        }
    }

    function add() {

        //check code
        if(! isset($this->data['Curr']['active']))
        {
            $this->data['Curr']['active'] = "";
        }
        $code = $this->data['Curr']['code'];
        if ($this->check_code($code, '')) {
            if ($this->Curr->add($this->data)) {
                $this->Curr->create_json_array('', 201, __('The Currency [%s] is created successfully!', true, $code));
            } else {
                $this->Curr->create_json_array('', 101, __('Fail to create Currency.', true));
            }
        } else {
            $this->Curr->create_json_array('', 101, 'Currency name[' . $code . '] is already in use!');
        }

        $this->xredirect("/currs/index");
    }

    function edit($id) {
        $this->data['Curr']['currency_id'] = $id;
        if(! isset($this->data['Curr']['active']))
        {
            $this->data['Curr']['active'] = "";
        }
        $code = $this->data['Curr']['code'];
        if ($this->check_code($code, $id)) {
            if ($this->Curr->edit($this->data)) {
                $this->Curr->create_json_array('', 201, __('The Currency [%s] is modified successfully!', true, $code));
            } else {
                $this->Curr->create_json_array('', 101, __('Fail to modified Currency.', true));
            }
        } else {
            $this->Curr->create_json_array('', 101, 'Currency  name  is  exist !');
        }
        $this->xredirect("/currs/index");
    }

    function _render_index_conditions($options = Array()) {
        $conditions = $this->_filter_conditions(Array('search'));
        if (!is_array($conditions)) {
            $conditions = Array($conditions);
        }
        if (!empty($conditions)) {
            $options = array_merge($options, $conditions);
        }
        $this->paginate['conditions'] = $options;
    }

    function _render_index_order($options = Array()) {
        $conditions = $this->_order_condtions(Array('currency_id', 'code', 'usage' => '"Curr__usage"', 'last_modify' => '"Curr__last_modify"', 'rate' => '"Curr__rate"'), '', 'code asc');
        $this->paginate['order'] = $conditions;
    }

    function _render_index_fields() {
        $this->paginate['fields'] = Array(
            'currency_id', 'code', 'active', 'update_by',
            '(select count(rate_table_id) from rate_table where currency_id = "Curr"."currency_id")::float AS "Curr__rates"',
            '(select rate from currency_updates where currency_id = "Curr"."currency_id" and modify_time=(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id")) as "Curr__rate"',
            '(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id") as "Curr__last_modify"',
            '(select count(rate_table_id) from rate_table where currency_id ="Curr"."currency_id") as "Curr__usage"'
        );
    }

    function _render_index_data() {
        $this->_render_index_order();
        $this->_render_index_conditions();
        $this->_render_index_fields();
        $this->data = $this->paginate('Curr');
    }

    function index() {
        $this->pageTitle = "Switch/Currency";
        $this->_render_index_data();
    }

    function _render_js_save_data($id) {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        $options = Array();
        $options['conditions'] = Array('currency_id' => $id);
        $options['fields'] = Array(
            'currency_id', 'code', 'active',
            '(select count(rate_table_id) from rate_table where currency_id = "Curr"."currency_id")::float AS "Curr__rates"',
            '(select rate from currency_updates where currency_id = "Curr"."currency_id" and modify_time=(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id")) as "Curr__rate"',
            '(select max(modify_time) from currency_updates where currency_id = "Curr"."currency_id") as "Curr__last_modify"',
            '(select count(rate_table_id) from rate_table where currency_id ="Curr"."currency_id") as "Curr__usage"'
        );
        $this->data = $this->Curr->find('first', $options);
    }

    function js_save($id = null) {
        if (!$_SESSION['role_menu']['Switch']['currs']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!empty($id)) {
            $this->_render_js_save_data($id);
        }
    }

    function _render_history_data($id) {
        $this->loadModel('Currupdate');
        $this->data = $this->Currupdate->find('all', Array('conditions' => Array('currency_id' => $id), 'order' => array('modify_time', 'last_rate', 'rate')));
    }

    function history($id) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!empty($id)) {
            $this->_render_history_data($id);
        }
    }

    function _filter_search() {
        $search = $this->_get('search');
        if (!empty($search)) {
            return "code ilike '%$search%'";
        }
        return "";
    }

    function pages_num() {
        $sql = "";
        $pages = $this->Client->query($sql);
        if (!empty($pages[0][0]['count'])) {
            $this->set('pages', $pages[0][0]['count']);
        } else {
            $this->set('pages', "");
        }
    }

    function check_repeat_name($name, $curr_id = null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!empty($name)) {
            $sql = "select count(code) as name_count from  currency  where code='$name'";
            if ($curr_id) {
                $sql .= " and currency_id != {$curr_id}";
            }
            $count = $this->Curr->query($sql);
            if ($count[0][0]['name_count'] > 0) {
                echo 'false';
            } else {
                echo "true";
            }
        }
    }

}
