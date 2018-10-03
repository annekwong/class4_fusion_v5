<?php

/**
 *
 * @author root
 *
 */
class SysroleprisController extends AppController
{

    var $name = 'Sysrolepris';
    var $helpers = array('javascript', 'html');
    var $uses = array('Sysrolepri', 'Sysrole');

    //读取该模块的执行和修改权限
    function index()
    {
        $this->redirect('view_sysrolepri');
    }

    function active_role($role_id = null, $name = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Sysrolepri->query("update  role  set  active=true  where  role_id=$id");
        $this->Sysrolepri->query("update  users  set  active=true  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Sysrolepri->create_json_array('', 201, __('The Role [%s] is actived successfully', true, $name));
        $this->xredirect('/roles/view');
    }

    function dis_role($role_id = null, $name = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Sysrolepri->query("update  role  set  active=false  where  role_id=$id");
        $this->Sysrolepri->query("update  users  set  active=false  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Sysrolepri->create_json_array('', 201, __('The Role [%s] is disabled successfully', true, $name));
        $this->xredirect('/roles/view');
    }

    function check_form($id)
    {
        $role_name = $this->data['Sysrole']['role_name'];
        $c = $this->Sysrolepri->check_name($id, $role_name);
        if ($c != 0)
        {
            $this->Sysrolepri->create_json_array('#RoleRoleName', 101, __('The Role [%s] has already been used', true, $role_name));
            $this->Session->write('m', $this->Sysrolepri->set_validator());
            return false;
        }
        return true;
    }

    /*
      function add_sysrolepri($id=null){
      $this->pageTitle = "Configuration/Add  Role";
      $this->set('name',$this->select_role_name($id));
      $this->_catch_exception_msg(array($this,'_add_impl'),array('id' => $id));
      $this->render('add_sysrolepri');
      }
     */

    function add_sysrolepri($id = null)
    {

        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_r'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Configuration/Add  Role";
        $id = base64_decode($id);
        $this->set('role_name', $this->select_role_name($id));
        $this->init_module();
        /* if (!empty($_POST['data']['Sysrole']['role_name']))
          {
          $this->saveOrUpdate_rolepri($role_id=null);
          } */
        $this->init_info_byroleId($id);
        $this->_catch_exception_msg(array($this, '_add_impl'), array('id' => $id));
        //$this->render('add_sysrolepri');
    }

    function _add_impl($params = array())
    {
        #post
        $name = $this->data['Sysrole']['role_name'];
        if ($this->RequestHandler->isPost())
        {
            $return = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST);
            $role_id = $return['role_id'];
            if (isset($this->data['Sysrole']['role_id']) && !empty($this->data['Sysrole']['role_id']))
            {
                //$this->_create_or_update_role_data ($params);
                if ($role_id > 0)
                {
                    $role_id = base64_encode($role_id);
                    $this->set_tip("The Role [" . $name . "] is modified successfully!");
                    $this->redirect('/sysrolepris/view_sysrolepri');
               //     $url_flug = "sysrolepris-add_sysrolepri-{$role_id}";
                   // $this->modify_log_noty($return['log_id'], $url_flug);
//                    $this->xredirect("/logging/index/{$return['log_id']}/sysrolepris-add_sysrolepri-{$role_id}");
                    //$this->redirect('add_sysrolepri/' . $role_id);
                }
            }
            else
            {
                if (!$this->check_form(''))
                {
                    return;
                }
                $role_id = base64_encode($role_id);
                $this->set_tip("The Role [" . $name . "] is created successfully!");
                $this->redirect('/sysrolepris/view_sysrolepri');
             //   $url_flug = "sysrolepris-add_sysrolepri-{$role_id}";
              //  $this->modify_log_noty($return['log_id'], $url_flug);
//                $this->xredirect("/logging/index/{$return['log_id']}/sysrolepris-add_sysrolepri-{$role_id}");
                //$this->redirect('add_sysrolepri/' . $role_id);
                //$this->Sysrolepri->create_json_array('',101,'Please add permission');
            }
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $sql = "select count(*) as cnt from sys_role_pri where role_id='{$params['id']}'";
                $cnt = $this->Sysrolepri->query($sql);
                //pr($this->data);
                //$this->data = $this->Sysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$params['id']}"));

                if (empty($cnt[0][0]['cnt']))
                {
                    $this->Sysrolepri->create_json_array('', 101, 'Please add permission');
                    //throw new Exception("Permission denied");
                }
            }
        }
    }

    function _create_or_update_role_data($params = array())
    {
        #update
        if (isset($params['id']) && !empty($params['id']))
        {
            $id = (int) $params ['id'];
            if (!$this->check_form($id))
            {
                return;
            }
            //	$this->data = $this->Sysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$id}"));
            $this->data ['Sysrole'] ['id'] = $id;
            $return_flg = $this->Sysrolepri->saveOrUpdate_rolepri($this->data);
            if ($return_flg['role_id'])
            {
                $this->set_tip("The Role [" . $this->data['Sysrole']['role_name'] . "] is modified successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
        # add
        else
        {
            if (!$this->check_form(''))
            {
                return;
            }
            if ($return = $this->Sysrolepri->saveOrUpdate_rolepri($this->data))
            {
                $id = $return['role_id'];
                $this->set_tip("The Role [" . $this->data['Sysrole']['role_name'] . "] is created successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
    }

    function set_tip($info)
    {
        $this->Sysrolepri->create_json_array('', 201, $info);
        $this->Session->write('m', $this->Sysrolepri->set_validator());
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
            $this->Session->write('executable', false);
            $this->Session->write('writable', false);
        }
        parent::beforeFilter();
    }

    //权限测试

    function role_privilege_test()
    {

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
    function login()
    {
        $login_type = $_SESSION['login_type']; //当前用户身份
        $cur_sql = $_SESSION['arr'][$login_type . 'find_user'];

        //查看用户
        $this->query($cur_sql);
    }

    function init_info_byroleId($role_id)
    {
        $this->set('sysrolepri', $this->Sysrolepri->findSysrolepri($role_id));
    }

    function edit()
    {
        if (!empty($this->data ['Sysrolepri']))
        {
            $return = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            $flag = $return['role_id'];
            if (empty($flag))
            {
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $this->Sysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Rolehasbeenmodifiedsuccessfully', true));
                $this->Session->write("m", Sysrolepri::set_validator());
                $this->redirect('/roles/view?edit_id=' . $this->params['form']['role_id']); // succ
            }
        }
        else
        {
            $this->Sysrolepri->role_id = $this->params ['pass'][0];
            $post = $this->Sysrolepri->read();
            $this->set('post', $post);
            $this->init_info_byroleId($this->params['pass'][0]);
        }
    }

    /**
     * 批量修改角色
     */
    function batchupdate()
    {
        if (!empty($this->data ['Sysrolepri']))
        {
            $error_flag = $this->Sysrolepri->batchupdate($this->data); //保存
            if (empty($error_flag))
            {
                $this->redirect(array('action' => 'view'));
            }
            else
            {
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->set('role', $this->Sysrolepri->findRole());
            }
        }
        else
        {

            $this->set('role', $this->Sysrolepri->findRole());
        }
    }

    /**
     * 添加
     */
    function add()
    {
        if (!empty($this->data ['Sysrolepri']))
        {
            $flag = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            if (empty($flag['role_id']))
            {
                //添加失败
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            }
            else
            {
                $this->Sysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Roleshavecreatesuccess', true));
                $this->Session->write("m", Sysrolepri::set_validator());
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            }
        }
        $this->init_info();
    }

    function del()
    {
        $id = base64_decode($this->params['pass'][0]);
        $old_data = $this->Sysrole->findByRoleId($id);
        $old_pri_data = $this->Sysrolepri->findByRoleId($id);
        $size = $this->Sysrolepri->del($id);
        if (empty($size))
        {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $old_data['Sysrole'];
            unset($old_data_arr['role_id']);
            foreach ($old_data_arr as $key => $value)
            {
                if ($value)
                {
                    $filed_arr[] = $key;
                    $str_arr = array(
                        'role_name', 'role_info', 'view_all'
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

            $rollback_sql = "INSERT INTO sys_role ({$filed_str}) VALUES ({$value_str}) RETURNING role_id;&&";
            $pri_name = $old_pri_data['Sysrolepri']['pri_name'];
            $model_r = $old_pri_data['Sysrolepri']['model_r'] ? "true" : "false";
            $model_w = $old_pri_data['Sysrolepri']['model_w'] ? "true" : "false";
            $model_x = $old_pri_data['Sysrolepri']['model_x'] ? "true" : "false";
            $value_str_1 = "{role_id},'{$pri_name}',{$model_r},{$model_w},{$model_x}";
            $rollback_sql .= "INSERT INTO sys_role_pri (role_id,pri_name,model_r,model_w,model_x) VALUES ({$value_str_1})";
            $rollback_msg = "Delete Role [" . $this->params['pass'][1] . "] operation have been rolled back!";

            $rollback_extra_info = json_encode(array('type' => 5));
            $log_id = $this->Sysrolepri->logging(1, 'Role', "Role Name:{$this->params['pass'][1]}", $rollback_sql, $rollback_msg, $rollback_extra_info);
            $this->Session->write('m', $this->Sysrolepri->create_json(201, /* $this->params['pass'][1].' '. */ __('The Role [%s] is deleted successfully!', true, $this->params['pass'][1])));
            $url_flug = "sysrolepris-view_sysrolepri";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/sysrolepris-view_sysrolepri");
        }
        else
        {
            $this->Session->write('m', $this->Sysrolepri->create_json(101, __('The Role [%s] is deleted failed!', true, $this->params['pass'][1])));
        }
        $this->redirect(array('action' => 'view_sysrolepri'));
    }

    /**
     *
     * 查询角色
     */
    public function view_sysrolepri()
    {

        $this->pageTitle = "Configuration/Roles";
        $order = $this->_order_condtions(Array('role_name', 'active', 'role_users'));
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //模糊搜索
        if (isset($_GET['searchkey']))
        {
            $results = $this->Sysrolepri->likequery($_GET['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_GET['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索
        if (!empty($this->data['Sysrolepri']))
        {


            $results = $this->Sysrolepri->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            if (!empty($_REQUEST['edit_id']))
            {
                $sql = "select sys_role.*,(select count(*) as  role_cnt from users where role_id = sys_role.role_id)
		    from  sys_role where sys_role.role_id = {$_REQUEST['edit_id']} $order";

                $result = $this->Sysrolepri->query($sql);
                //分页信息

                require_once 'MyPage.php';
                $results = new MyPage ();
                $results->setTotalRecords(1); //总记录数
                $results->setCurrPage(1); //当前页
                $results->setPageSize(1); //页大小
                $results->setDataArray($result);
                $this->set('edit_return', true);
            }
            else
            {

                $role_sql = "";

                if (!empty($_GET['search']) && strcmp('Search', $_GET['search']))
                {
                    $role_sql = $_GET['search'];
                }
                //
                $results = $this->Sysrolepri->findAll($currPage, $pageSize, $role_sql, $order);
            }
        }
        $this->set('p', $results);
        $dnl_support_role_name_sql = "SELECT role_name FROM sys_role INNER JOIN users ON users.role_id = sys_role.role_id WHERE users.name = 'dnl_support'";
        $dnl_support_role_name_result = $this->Sysrolepri->query($dnl_support_role_name_sql);
        $dnl_support_role_name = '';
        if($dnl_support_role_name_result)
            $dnl_support_role_name = $dnl_support_role_name_result[0][0]['role_name'];
        $this->set('dnl_role_name',$dnl_support_role_name);
    }

    public function select_role_name($id = null)
    {

        if (!empty($id))
        {
            $sql = "select role_name as role_name,view_all,delete_invoice,delete_payment,
                            delete_credit_note,delete_debit_note,reset_balance,
                            modify_credit_limit,modify_min_profit,view_cost_and_rate
                            from sys_role where role_id=$id";
            $result = $this->Sysrolepri->query($sql);
            if (!empty($result))
            {
                return $result;
            }
        }
    }

    public function _users_arr($role_id = null)
    {
        if (!empty($role_id))
        {

        }
    }

//获取用户登录角色权限模块
    public function init_module()
    {
        if (true)
        {
            $return = array();
            $sql = "select sys_pri.*,  sys_module.module_name from sys_pri left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true  order by sys_module.order_num asc";
            $list = $this->Sysrolepri->query($sql);
            if (!empty($list))
            {
                foreach ($list as $k => $v)
                {
                    $return[$v[0]['module_name']][] = $v[0];
                }
            }
            $results = $return;
            //$_SESSION['role_menu'] = $return;
        }
        $this->set('sysmodule', $results);
    }

    function add_sysrole($role_id = null)
    {
        $this->loadModel('Sysrole');
        $role_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        //var_dump($this->data);exit;
        if (!empty($this->data ['Sysrole']))
        {
            //pr($_POST); exit();
            $return = $this->Sysrolepri->saveOrUpdate_role($this->data, $_POST); //保存
            if (!empty($return['role_id']))
            {
                $this->set('post', $this->data);
                $this->Sysrole->create_json_array('', 201, 'Add successfully');
                //$this->Session->write('m',Sysrole::set_validator());
                //$this->redirect ( array ('controller' => 'sysrolepris', 'action' => 'view_sysrolepri') ); // succ
            }
            else
            {
                $this->Syspri->create_json_array('', 101, 'Add fail');
            }
        }
    }

}

?>
