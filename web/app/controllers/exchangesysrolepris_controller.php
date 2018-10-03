<?php

/**
 *
 * @author root
 *
 */
class ExchangesysroleprisController extends AppController {

    var $name = 'Exchangesysrolepris';
    var $helpers = array('javascript', 'html');

    //读取该模块的执行和修改权限
    function index() {
        $this->redirect('view_sysrolepri');
    }

    function active_role($role_id=null, $name=null) {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Exchangesysrolepri->query("update  role  set  active=true  where  role_id=$id");
        $this->Exchangesysrolepri->query("update  users  set  active=true  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Exchangesysrolepri->create_json_array('', 201, __('The Role [%s] is actived successfully', true, $name));
        $this->xredirect('/roles/view');
    }

    function dis_role($role_id=null, $name=null) {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Exchangesysrolepri->query("update  role  set  active=false  where  role_id=$id");
        $this->Exchangesysrolepri->query("update  users  set  active=false  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Exchangesysrolepri->create_json_array('', 201, __('The Role [%s] is disabled successfully', true, $name));
        $this->xredirect('/roles/view');
    }

    function check_form($id) {
        $role_name = $this->data['Exchangesysrole']['role_name'];
        $c = $this->Exchangesysrolepri->check_name($id, $role_name);
        if ($c != 0) {
            $this->Exchangesysrolepri->create_json_array('#RoleRoleName', 101, __('The Role [%s] has already been used', true, $role_name));
            $this->Session->write('m', $this->Exchangesysrolepri->set_validator());
            return false;
        }
        return true;
    }

    /*
      function add_sysrolepri($id=null){
      $this->pageTitle = "Exchange Manage/Add  Role";
      $this->set('name',$this->select_role_name($id));
      $this->_catch_exception_msg(array($this,'_add_impl'),array('id' => $id));
      $this->render('add_sysrolepri');
      }
     */

    function add_sysrolepri($type='agent',$id=null) {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $type = empty($this->params['pass'][0]) ? 'agent' : $this->params['pass'][0];
        $role_type = array('exchange'=>0,'agent'=>1,'partition'=>2);
        $this->set('type',$type);
        $this->set('role_type',$role_type);

        $this->pageTitle = "Exchange Manage/Add  Role";
        $this->set('role_name', $this->select_role_name($id));

        $this->init_module($role_type[$type]);
        /* if (!empty($_POST['data']['Sysrole']['role_name']))
          {
          $this->saveOrUpdate_rolepri($role_id=null);
          } */
        $this->init_info_byroleId($id);
        $this->_catch_exception_msg(array($this, '_add_impl'), array('id' => $id,'type'=>$type));
        //$this->render('add_sysrolepri');
    }


    function _add_impl($params=array()) {
        #post
        $name = $this->data['Exchangesysrole']['role_name'];
        if ($this->RequestHandler->isPost()) {

            $type = $params['type'];
            $role_id = $this->Exchangesysrolepri->saveOrUpdate_rolepri($this->data, $_POST);
            if (isset($this->data['Exchangesysrole']['role_id']) && !empty($this->data['Exchangesysrole']['role_id'])) {
                //$this->_create_or_update_role_data ($params);

                if ($role_id > 0) {
                    $this->set_tip("Then Role[".$name."] modified Successfully!");
                    $this->redirect('add_sysrolepri/'.$type.'/'.$role_id);
                }
            } else {
                if (!$this->check_form('')) {
                    return;
                }
                $this->set_tip("The Role[".$name."] is added successfully!");
                $this->redirect('add_sysrolepri/'.$type.'/'.$role_id);
                //$this->Exchangesysrolepri->create_json_array('',101,'Please add permission');
            }
        }
        #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
//                $sql = "select count(*) as cnt from exchange_sys_role_pri where role_id={$params['id']}";
//                $cnt = $this->Exchangesysrolepri->query($sql);
                //pr($this->data);
                //$this->data = $this->Exchangesysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$params['id']}"));

//                if (empty($cnt[0][0]['cnt'])) {
//                    $this->Exchangesysrolepri->create_json_array('', 101, 'Please add permission');
                    //throw new Exception("Permission denied");
//                }
            }
        }
    }

    function _create_or_update_role_data($params=array()) {
        #update
        if (isset($params['id']) && !empty($params['id'])) {
            $id = (int) $params ['id'];
            if (!$this->check_form($id)) {
                return;
            }
            //	$this->data = $this->Exchangesysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$id}"));
            $this->data ['Exchangesysrole'] ['id'] = $id;
            if ($this->Exchangesysrolepri->saveOrUpdate_rolepri($this->data)) {
                $this->set_tip("The Role [".$this->data['Exchangesysrole']['role_name']."] is modified successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
        # add
        else {
            if (!$this->check_form('')) {
                return;
            }
            if ($id = $this->Exchangesysrolepri->saveOrUpdate_rolepri($this->data)) {
                //$id = $this->Exchangesysrolepri->getlastinsertId ();
                $this->set_tip("The Role [".$this->data['Exchangesysrole']['role_name']."] is created successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
    }

    function set_tip($info) {
        $this->Exchangesysrolepri->create_json_array('', 201, $info);
        $this->Session->write('m', $this->Exchangesysrolepri->set_validator());
    }

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $this->Session->write('executable', false);
            $this->Session->write('writable', false);
        }
        parent::beforeFilter();
    }

    //权限测试

    function role_privilege_test() {

        $reseller_id = '';
        $client_id = '';
        $account_id = '';
        $arr = array();
        //初始化不同身份的sql
        $arr['admin'] = "select * from  users";
        $arr['reseller'] = "select * from  users where reseller_id =$reseller_id";
        $arr['client'] = "select * from users where  client_id=$client_id";
        $arr['account'] = "select * from users where account_id=$account_id";
        $_SESSION['arr'] = $arr;
    }

    //登录
    function login() {
        $login_type = $_SESSION['login_type']; //当前用户身份
        $cur_sql = $_SESSION['arr'][$login_type . 'find_user'];

        //查看用户
        $this->query($cur_sql);
    }

    function init_info_byroleId($role_id) {
        $this->set('sysrolepri', $this->Exchangesysrolepri->findSysrolepri($role_id));
    }

    function edit() {
        if (!empty($this->data ['Exchangesysrolepri'])) {
            $flag = $this->Exchangesysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            if (empty($flag)) {
                $this->set('m', ExchangeExchangesysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $this->Exchangesysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Rolehasbeenmodifiedsuccessfully', true));
                $this->Session->write("m", Exchangesysrolepri::set_validator());
                $this->redirect('/roles/view?edit_id=' . $this->params['form']['role_id']); // succ
            }
        } else {
            $this->Exchangesysrolepri->role_id = $this->params ['pass'][0];
            $post = $this->Exchangesysrolepri->read();
            $this->set('post', $post);
            $this->init_info_byroleId($this->params['pass'][0]);
        }
    }

    /**
     * 批量修改角色
     */
    function batchupdate() {
        if (!empty($this->data ['Exchangesysrolepri'])) {
            $error_flag = $this->Exchangesysrolepri->batchupdate($this->data); //保存
            if (empty($error_flag)) {
                $this->redirect(array('action' => 'view'));
            } else {
                $this->set('m', ExchangeExchangesysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->set('role', $this->Exchangesysrolepri->findRole());
            }
        } else {

            $this->set('role', $this->Exchangesysrolepri->findRole());
        }
    }

    /**
     * 添加
     */
    function add() {
        if (!empty($this->data ['Exchangesysrolepri'])) {
            $flag = $this->Exchangesysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            if (empty($flag)) {
                //添加失败
                $this->set('m', Exchangesysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            } else {
                $this->Exchangesysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Roleshavecreatesuccess', true));
                $this->Session->write("m", Exchangesysrolepri::set_validator());
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            }
        }
        $this->init_info();
    }

    function del() {
        $size = $this->Exchangesysrolepri->del($this->params['pass'][0],$this->params['pass'][2]);
        if (empty($size)) {
            $this->Exchangesysrolepri->logging(1, 'Role', "Role Name:{$this->params['pass'][1]}");
            $this->Session->write('m', $this->Exchangesysrolepri->create_json(201, /* $this->params['pass'][1].' '. */ __('The Role[%s] deleted successfully!', true, $this->params['pass'][1])));
        } else {
            $this->Session->write('m', $this->Exchangesysrolepri->create_json(101, __('The Role[%s] deleted failed!', true, $this->params['pass'][1])));
        }
        $this->redirect(array('action' => 'view_sysrolepri'));
    }

    /**
     *
     * 查询角色
     */
    public function view_sysrolepri($type='agent') {
        $this->pageTitle = "Exchange Manage/Roles";
        $type = empty($this->params['pass'][0]) ? 'agent' : $this->params['pass'][0];

        $role_type = array('exchange'=>0,'agent'=>1,'partition'=>2);

        $this->set('type',$type);
        $order = $this->_order_condtions(Array('role_name', 'active', 'role_cnt'));
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //模糊搜索
        if (isset($_GET['searchkey'])) {
            $results = $this->Exchangesysrolepri->likequery($_GET['searchkey'], $currPage, $pageSize,$role_type[$type]);
            $this->set('searchkey', $_GET['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data['Exchangesysrolepri'])) {

            //var_dump($this->data['Exchangesysrolepri']);
            $results = $this->Exchangesysrolepri->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            if (!empty($_REQUEST['edit_id'])) {
//                $sql = "select exchange_sys_role.*,(select count(*) as  role_cnt from users where role_id = exchange_sys_role.role_id)
//		    from  exchange_sys_role where
//                    exchange_sys_role.type = {$role_type[$type]} and
//                    exchange_sys_role.role_id = {$_REQUEST['edit_id']} $order";

//                $result = $this->Exchangesysrolepri->query($sql);
                //分页信息
                $result = array();
                require_once 'MyPage.php';
                $results = new MyPage ();
                $results->setTotalRecords(1); //总记录数
                $results->setCurrPage(1); //当前页
                $results->setPageSize(1); //页大小
                $results->setDataArray($result);
                $this->set('edit_return', true);
            } else {
                //
                $results = $this->Exchangesysrolepri->findAll($currPage, $pageSize, $order,$role_type[$type]);
            }
        }
        $this->set('p', $results);
    }

    public function select_role_name($id=null) {

        if (!empty($id)) {
//            $sql = "select role_name as role_name,view_all,delete_invoice,delete_payment,
//                            delete_credit_note,delete_debit_note,reset_balance,
//                            modify_credit_limit,modify_min_profit,view_cost_and_rate
//                            from exchange_sys_role where role_id=$id";
//            $result = $this->Exchangesysrolepri->query($sql);
            if (!empty($result)) {
                return $result;
            }
        }
    }

    public function _users_arr($role_id=null) {
        if (!empty($role_id)) {

        }
    }

//获取用户登录角色权限模块
    public function init_module($role_type) {
        if (true) {
            $return = array();
//            $sql = "select exchange_sys_pri.*,  exchange_sys_module.module_name from
//                exchange_sys_pri left join exchange_sys_module
//                on exchange_sys_pri.module_id = exchange_sys_module.id
//                where exchange_sys_pri.flag = true  and  exchange_sys_module.type = {$role_type}
//                order by exchange_sys_module.order_num asc";
//            $list = $this->Exchangesysrolepri->query($sql);
            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $return[$v[0]['module_name']][] = $v[0];
                }
            }
            $results = $return;
            //$_SESSION['role_menu'] = $return;
        }
        $this->set('sysmodule', $results);
    }

    function add_sysrole($role_id=null) {
        $this->loadModel('Exchangesysrole');
        $role_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        //var_dump($this->data);exit;
        if (!empty($this->data ['Exchangesysrole'])) {
            //pr($_POST); exit();
            $return = $this->Exchangesysrolepri->saveOrUpdate_role($this->data, $_POST); //保存
            if (!empty($return)) {
                $this->set('post', $this->data);
                $this->Exchangesysrole->create_json_array('', 201, 'Add successfully');
                //$this->Session->write('m',Sysrole::set_validator());
                //$this->redirect ( array ('controller' => 'sysrolepris', 'action' => 'view_sysrolepri') ); // succ
            } else {
                $this->Exchangesyspri->create_json_array('', 101, 'Add fail');
            }
        }
    }

}

?>
