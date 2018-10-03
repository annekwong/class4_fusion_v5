<?php

/**
 * 
 * @author root
 *
 */
class UsersController extends AppController
{

    var $name = 'Users';
    var $helpers = array('javascript', 'html', 'AppUsers');
    var $uses = array("User", "Userauthip", 'Client', 'Orderuser', 'Clients'); //引用model

    /**
     * 
     * 更新密码
     * 
     */

    function add_carrier_user($id = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add  Carrier User";
        $this->_catch_exception_msg(array($this, '_add_carrier_impl'), array('id' => $id, 'type' => 'carrier'));
        $this->_render_carrier_save_options();
        $this->render('add_carrier_user');
    }

    function check_module()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $role_id = $_POST['role_id'];
        $module = $_POST['module'];

        $sql = "select role_name from sys_role where role_id = {$role_id}";
        $result = $this->User->query($sql);
        if ($result[0][0]['role_name'] == 'admin')
        {
            echo json_encode(array('count' => 1));
            exit(0);
        }

        $sql = "select count(*) from sys_role_pri inner join sys_pri on sys_role_pri.pri_name = sys_pri.pri_name  where role_id = '{$role_id}' and sys_pri.id = {$module}";
        $result = $this->User->query($sql);
        $json_return = array('count' => $result[0][0]['count']);
        echo json_encode($json_return);
    }

    function _add_carrier_impl($params = array())
    {

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_user_data($params);
        }
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->User->find('first', array('conditions' => "User.user_id = {$params['id']}"));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                }
            }
            else
            {
                
            }
        }
    }

    function changestatus($id, $status)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($status == 0)
        {
            $sql = "update order_user set status = 0 where id = {$id}";
        }
        else
        {
            $sql = "update order_user set status = 1 where id = {$id}";
        }
        $this->User->query($sql);
        $this->User->create_json_array('#UserName', 201, __('The User`s status is modified succesfully', true));
        $this->Session->write('m', $this->User->set_validator());

        $this->xredirect('/users/registration');
    }

    function add($id = null)
    {
        $role_id = $_SESSION['sst_role_id'];
        if (strcmp(intval($id), $id))
        {
            $id = base64_decode($id);
        }
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->init_info();

        $sql = "select view_all from sys_role where role_id = (select role_id from users where user_id = {$_SESSION['sst_user_id']})";

        $result = $this->User->query($sql);
        if ($result[0][0]['view_all'] == '1')
        {
            $view_all = true;
        }
        else
        {
            $view_all = false;
        }

        $this->set('view_all', $view_all);

        $report_group = "";
        $outbound_report = "";
        $all_termination = "";
        if (!empty($id))
        {
            $sql = "select* from users where user_id=$id";
            $name = $this->User->query($sql);

            if (!empty($name[0][0]))
            {
                $report_group = $name[0][0]['report_group'];
                $outbound_report = $name[0][0]['outbound_report'];
                $all_termination = $name[0][0]['all_termination'];
            }

            if (!empty($name[0][0]['name']))
            {
                $this->set('name', $name[0][0]['name']);
            }
            $carrier_limit = $this->User->get_carrier_limit($id);
            $this->set('limits', $carrier_limit);
            $this->set('hosts', $this->User->query("select  ip  from  user_auth_ip  where  user_id=$id"));

        }
        $this->set('report_group', $report_group);
        $this->set('outbound_report', $outbound_report);
        $this->set('all_termination', $all_termination);
        $this->pageTitle = "Add  User";
        $this->_add_impl(array('id' => $id));
        //$this->_catch_exception_msg(array('UsersController','_add_impl'),array('id' => $id));
        $this->_render_save_options();
        $role_id = "";
        if($id)
            $role_id = $this->data['User']['role_id'];
        $is_admin = $this->check_is_admin($role_id);
        if($is_admin)
            $role_id = "";
        $this->set('pris', $this->User->get_module($role_id));

        //添加关联user_id
        //$user_id = $this->User->saveOrUpdateHost($this->data, $_POST,array_keys_value($this->params,'form.accounts'));
        $this->render('add');
    }

    public function ajax_get_module()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $role_id = $_POST['role_id'];
        $is_admin = $this->check_is_admin($role_id);
        if($is_admin)
            $role_id = "";
        $data = $this->User->get_module($role_id);
        echo json_encode($data);
    }

    public function check_is_admin($role_id)
    {
        $this->loadModel('Sysrole');
        $is_admin = $this->Sysrole->find('count',array(
            'conditions' => array(
                'role_id' => $role_id,
                'role_name' => 'admin'
            )
        ));
        return $is_admin;
    }

    public function get_child_pri()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $module_id = $_POST['module_id'];
        $role_id = $_POST['role_id'];
        $is_admin = $this->check_is_admin($role_id);
        if($is_admin)
        {
            $sql = "select id, pri_val from sys_pri WHERE sys_pri.module_id = {$module_id} and pri_url != '' AND flag = TRUE";
            $result = $this->User->query($sql);
        }
        else
            $result = $this->User->get_child_pri($role_id,$module_id);
        echo json_encode($result);
    }

    function _add_impl($params = array())
    {
        //array('id'=>null)
        #post
        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_user_data($params);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->User->find('first', array('conditions' => "User.user_id = {$params['id']}"));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                }
            }
            else
            {
                
            }
        }
    }

    function set_user_type()
    {
        if ($_SESSION['login_type'] == 1)
        {
            $this->data['User']['user_type'] = 1;
        }
        if (isset($this->data['User']['client_id']) && !empty($this->data['User']['client_id']))
        {
            $this->data['User']['user_type'] = 3;
        }
    }

    function set_tip($info)
    {
        $this->User->create_json_array('', 201, $info);
        $this->Session->write('m', $this->User->set_validator());
    }

    function md5_password()
    {   
        // does not need to md5 in case of edit
        if (!$this->isValidMd5($this->data['User']['password'])) {
            $this->data['User']['password'] = md5($this->data['User']['password']);
        }
    }

    function isValidMd5($md5 ='') {
        return strlen($md5) == 32 && ctype_xdigit($md5);
    }

    function check_form($user_id)
    {
        $c = $this->User->check_name($user_id, $this->data['User']['name']);
        if ($c != 0)
        {
            $this->User->create_json_array('#UserName', 101, __($this->data['User']['name'] . ' name is already in use!', true));
            $this->Session->write('m', $this->User->set_validator());
            return false;
        }
        return true;
    }

    /**
     * 
     * Enter description here ...
     * @param unknown_type $params
     */
    function _create_or_update_user_data($params = array())
    {
        //array('id'=>null)
        $this->set_user_type();
        #update
        if (isset($params['id']) && !empty($params['id']))
        {


            $id = (int) $params ['id'];
            if (!$this->check_form($id))
            {
                return;
            }
            $this->data ['User'] ['user_id'] = $id;
            if (empty($this->data['User']['password']))
            {
                if (!empty($id))
                {
                    unset($this->data['User']['password']);
                }
                else
                {
                    //$this->set_tip(" Users Password Can't be Empty!");
                    $this->set_tip("The field Password cannot be NULL.");
                    $this->redirect(array());
                }
            }
            else
            {

                $this->md5_password();
            }



            if ($this->User->save($this->data['User']))
            {

                $log_id = $this->User->logging(2, 'User', "User Name:{$this->data['User']['name']}");

                //do   auth  ip  
                //$this->Userauthip->save_auth_ip($id);
                $this->save_auth_ip(array_keys_value($this->params, 'form.accounts'), $id);

                // Carrier权限分配

                if (isset($_POST['control_carrier']))
                {

                    $this->User->del_limit($id);

                    $values_arr = array();

                    foreach ($_POST['control_carrier'] as $item)
                    {
                        array_push($values_arr, "({$id}, {$item})");
                    }

                    $values_str = implode(',', $values_arr);

                    $this->User->carrer_limit($values_str);
                }
                //$this->set_tip(" Users, Edit successfullyfully !");

                $updateClient = array();
                foreach ($this->data['User'] as $col => $value) {
                    if (strpos($col, 'is_panel') !== false) {
                        $updateClient[] = "$col = '" . $value . "'";
                    }
                }
                $updateClientStr = implode(',', $updateClient);
                $this->loadModel('Client');
                if($this->data['User']['client_id'] && $updateClientStr){
                    $updateClientSql = "UPDATE client SET {$updateClientStr} WHERE client_id = {$this->data['User']['client_id']};";
                    $this->Client->query($updateClientSql);
                }

                $this->set_tip("The User [" . $this->data['User']['name'] . "] is modified successfully!");
                $url_flug = "users-add-" . base64_encode($id);
                if($params['type'] == 'carrier'){
                    $this->xredirect("/users/show_carrier");
                }else {
                    $this->modify_log_noty($log_id, $url_flug);
                }
            }
        }
        # add
        else
        {
            if (!$this->check_form(''))
            {
                return;
            }
            $this->md5_password();

//            echo '<pre>';
//                        print_r($this->data);
            if (!$this->isnotEmpty($this->data['User'], array('default_mod2')))
            {
                $this->data['User']['default_mod2'] = 0;
            }
            if (!$this->isnotEmpty($this->data['User'], array('default_mod')))
            {
                $this->data['User']['default_mod'] = 0;
            }
            if (!$this->isnotEmpty($this->data['User'], array('role_id')))
            {
                $this->data['User']['role_id'] = 0;
            }
            $this->User->createNewUser($this->data['User']);

            $sql = "select user_id from users where name = '{$this->data['User']['name']}'";

            $result = $this->User->query($sql);

            $id = $result[0][0]['user_id'];

            if ($id)
            {
                $log_id = $this->User->logging(0, 'User', "User Name:{$this->data['User']['name']}");
//                $id = $this->User->getlastinsertId();
                //do   auth  ip  
                $this->Userauthip->save_auth_ip($id);
                $this->save_auth_ip(array_keys_value($this->params, 'form.accounts'), $id);

                // Carrier权限分配

                if (isset($_POST['control_carrier']))
                {

                    $values_arr = array();

                    foreach ($_POST['control_carrier'] as $item)
                    {
                        array_push($values_arr, "({$id}, {$item})");
                    }

                    $values_str = implode(',', $values_arr);

                    $this->User->carrer_limit($values_str, $id);
                }

                //$this->set_tip(" Users, create successfully !");
                $this->set_tip("The User [" . $this->data['User']['name'] . "] is created successfully!");

                $url_flug = "users-index";
                if($params['type'] == 'carrier'){
                    $this->xredirect("/users/show_carrier");
                }else {
                    $this->modify_log_noty($log_id, $url_flug);
                }
            }
        }
    }

    function save_auth_ip($account = Array(), $id)
    {
        //pr($_POST['accounts']);
        //array_keys_value($this->params, 'form.accounts'), $id
        try
        {
            if (!empty($id))
            {
                $this->saveHost($account, $id);
            }
        }
        catch (Exception $e)
        {
            exit();
        }
        return;
    }

    function saveHost($account, $id)
    {
        //array_keys_value($this->params, 'form.accounts'), $id
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        $count = count($account['ip']);
        $this->User->bindModel(Array('hasMany' => Array('Userauthip')));
        $this->Userauthip->deleteAll(Array("user_id='$id'"));
        for ($i = 0; $i < $count; $i++)
        {
            $data = Array();
            $data['user_id'] = $id;

            if (is_ip($account['ip'][$i]))
            {
                $data['ip'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i))
                {
                    $data['ip'] = $account['ip'][$i] . '/' . array_keys_value($account, 'need_register.' . $i);
                }
            }
            $this->Userauthip->save($data);
            $this->Userauthip->id = false;
        }
    }

    function changepassword()
    {
        if (!$_SESSION['role_menu']['Configuration']['users:changepassword']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Configuration/Change password";
        $t = $_SESSION['login_type']; //登录身份
//		if($t==4){
//			$this->User->create_json_array('#UserOld',101,__('inputoldpassword',true));
//			$this->Session->write('m',User::set_validator ());
//			$this->redirect('/homes/logout');
//			
//		}

        if (!empty($this->data['User']))
        {
            $old = $this->data['User']['old']; //原来的密码
            $new = $this->data['User']['new']; //新mima
            $retype = $this->data['User']['retype'];
            $error_flag = false; //错误信息标志
            //验证
            if (empty($old))
            {
                $this->User->create_json_array('#UserOld', 101, __('inputoldpassword', true));
                $error_flag = true; //有错误信息
            }

            if (empty($new))
            {
                $this->User->create_json_array('#UserNew', 101, __('inputnewpassword', true));
                $error_flag = true; //有错误信息
            }

            if (empty($retype))
            {

                $this->User->create_json_array('#UserRetype', 101, __('inputconfirmpassword', true));

                $error_flag = true; //有错误信息
            }



            $pass = $_SESSION['sst_password']; //原密码
            if (!empty($old))
            {
                if ($old != $pass)
                {
                    $this->User->create_json_array('#UserOld', 101, __('oldpassworderror', true));
                    $error_flag = true; //有错误信息
                }
                if ($new != $retype)
                {
                    $this->User->create_json_array('#UserRetype', 101, "The input confirm password is not consistent!");
                    $error_flag = true; //有错误信息
                }
            }


            if ($error_flag == true)
            {
                $this->set('m', User::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
            }
            else
            {

                //根据不同的身份修改不同的密码


                if ($t == 1)
                {
                    $user_id = $_SESSION['sst_user_id'];
                    $this->User->query("update users set password =md5('$new') where  user_id=$user_id");
                }
                if ($t == 2)
                {
                    $reseller_id = $_SESSION['sst_reseller_id'];
                    $this->User->query("update reseller set login_pass ='$new'  where  reseller_id=$reseller_id");
                    $this->User->query("update users set password =md5('$new') where  reseller_id=$reseller_id");
                }

                if ($t == 3)
                {

                    $client_id = $_SESSION['sst_client_id'];
                    $this->User->query("update client set password ='$new' where client_id=$client_id");
                    $this->User->query("update users set password =md5('$new') where  client_id=$client_id");
                }

                if ($t == 4)
                {
                    $user_id = $_SESSION['sst_card_id'];
                    $this->User->query("update card set pin ='$new' where  card_id=$user_id");
                }
                if ($t == 5)
                {
                    $user_id = $_SESSION['sst_user_id'];
                    $this->User->query("update users set password =md5('$new') where  user_id=$user_id");
                }
                if ($t == 6)
                {
                    $user_id = $_SESSION['sst_user_id'];
                    $this->User->query("update users set password =md5('$new') where  user_id=$user_id");
                }

                $this->layout = "";
                $this->User->create_json_array('#UserRetype2', 201, __('passwordsucc', true));
                $this->set('m', User::set_validator()); //向界面设置验证信息

                $this->set('post', $this->data);
                $this->redirect('/homes/logout/');
            }
        }
    }

    /**
     * 批量修改角色
     */
    function batchupdate()
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->data ['User']))
        {
            $error_flag = $this->User->batchupdate($this->data); //保存
            if (empty($error_flag))
            {
                $this->Session->write('m', $this->User->create_json(201, 'Update Success'));
                $this->redirect(array('action' => 'view'));
            }
            else
            {
                $this->set('m', User::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->set('reseller', $this->User->findReseller());
            }
        }
        else
        {

            $this->set('reseller', $this->User->findReseller());
        }
    }

    /**
     * 初始化信息
     */
    function init_info()
    {

        $this->set('client', $this->User->findClient());
        if (PRI)
        {
            $this->set('role', $this->User->getSysRole());
        }
        else
        {
            $this->set('role', $this->User->getRole());
        }
    }

    /**
     * 初始化参数
     */
    function init_param()
    {
        $this->Session->write("r_role_id", $this->params ['pass'][0]);
        $this->Session->write("r_role_name", $this->params ['pass'][1]);
    }

    function edit($user_id)
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            $this->_render_edit_impl($user_id);
        }
        $this->data = $this->User->find('first', Array('conditions' => 'User.user_id=' . $user_id, 'fields' => Array('name', 'fullname', 'email', 'client_id', 'active', 'role_id')));
        $this->_render_save_options();
        $this->_render_save_params();
    }

    function _render_edit_impl($user_id)
    {
        $this->_format_user_data(Array('user_id' => $user_id));
        if ($this->User->xsave($this->data['User']))
        {
            $this->User->create_json_array('', 201, __('Edit successfully', true));
            $this->xredirect('/users/index?filter_id=' . $user_id);
        }
    }

    /**
     * 建立用户
     */
    function _render_save_options()
    {
        $this->loadModel('Client');
        $this->loadModel('Role');
        $this->set('ClientList', $this->Client->find('all', Array('fields' => Array('client_id', 'name'))));
        $this->set('RoleList', $this->Role->find('all', Array('fields' => Array('role_id', 'role_name'), 'order' => 'role_name')));
    }

    function _render_carrier_save_options()
    {
        $this->loadModel('Client');
        $this->loadModel('User');
        $this->loadModel('Carrier');
        $id = $this->params ['pass'] [0];
        $this->User->id = $id;
        $user = $this->User->read();

        $this->Client->id = $user['User']['client_id'];
        $client = $this->Client->read();

        $this->set('ClientList', $this->Client->find('all', Array('fields' => Array('client_id', 'name'))));
        
        $this->set('client', $client);
        //	$this->set('RoleList',$this->Role->find('all',Array('fields'=>Array('role_id','role_name'),'conditions'=>array('Role.role_type'=>2))));
    }

    function _render_save_params($options = Array())
    {
        $def_options = Array(
            'callback' => $this->webroot . 'users/index',
        );
        $this->params = array_merge($this->params, $def_options, $options);
    }

    function add_current()
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            $this->_render_add_impl();
        }
        $this->_render_save_options();
        $this->_render_save_params();
    }

    function add_last()
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            $this->_render_add_impl();
        }
        $this->_render_save_options();
        $this->_render_save_params();
    }

//	function add() {
//		if ($this->RequestHandler->isPost()) {
//			$this->_render_add_impl();
//		}
//		$this->_render_save_options();
//		$this->_render_save_params();
//	}
    function _render_add_impl()
    {
        $this->_format_user_data();
        if ($this->User->xsave($this->data['User']))
        {
            $user_id = $this->User->getlastinsertId();
            $this->User->create_json_array('', 201, __('save success', true));
            $this->xredirect('/users/index?filter_id=' . $user_id);
        }
    }

    function _format_default_data($options = Array())
    {
        $def_options = Array(
            'user_type' => User::USER_USER_TYPE_CARRIER,
            'create_user_id' => $this->Session->read('sst_user_id'),
            'create_time' => date("Y-m-d   H:i:s")
        );
        $this->data['User'] = array_merge($this->data['User'], $def_options, $options);
    }

    function _format_user_data($options = Array())
    {
        $this->_format_default_data($options);
        if (empty($this->data['User']['password']))
        {
            unset($this->data['User']['password']);
        }
    }

    function del($user_id)
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        $user_id = base64_decode($user_id);
        if ('admin' == trim($this->params['pass'][1]))
        {
            $this->Session->write('m', $this->User->create_json(101, __('You cannot delete the admin.', true)));
            $this->redirect(array('action' => 'index'));
        }
        $list = $this->User->query("select user_type from users where user_id=$user_id");
//		if($list[0][0]['user_type']==1){
//		 	$this->Session->write('m',$this->User->create_json(101,'The user can not remove the administrator'));
//			$this->redirect (array ('action' => 'index' ) );
//		}
        /*
          $list = $this->User->query("select del_able  from  role  where role_id  in (select role_id from users where user_id=$user_id)");
          if (empty($list[0][0]['del_able'])) {
          $this->Session->write('m', $this->User->create_json(101, __('delusernoprivalige', true)));
          } else {
         * 
         */
        $this->User->query("delete from users_limit where user_id = $user_id;");
        $size = $this->User->query("delete from  users where  user_id=$user_id");
        $log_id = $this->User->logging(1, 'User', "User Name:{$this->params['pass'][1]}");
        $this->Session->write('m', $this->User->create_json(201, 'The User [' . $this->params['pass'][1] . '] ' . __('delusersucc', true)));
        //}
        $url_flug = "users-index";
        $this->modify_log_noty($log_id, $url_flug);
//        $this->xredirect("/logging/index/{$log_id}/users-index");
    }

    /**
     * 查询客户
     */
    function _render_view_data()
    {
        $this->_render_index_bindModel();
        $this->_filter_index_conditions(Array('last_login_time is null'));
        $this->_order_index_conditions();
        $this->data = $this->paginate('User');
    }

    public function view()
    {
        $this->pageTitle = "Configuration/User Management";
        $this->_render_view_data();
        $this->_render_index_options();
        $this->set('have_add', 0);
    }

    function _render_index_bindModel()
    {
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['Client'] = Array('fields' => 'name');
        $this->User->bindModel($bindModel, false);
    }

    function _order_index_conditions()
    {
        $order_array = array('name' => 'User.name', 'user_type' => 'User.user_type', 'last_login_time' => 'User.last_login_time');
        $this->paginate['order'] = $this->_order_condtions($order_array, '', 'User.name asc');
    }

    function _filter_index_conditions($options = Array())
    {
        if(empty($_GET['search']) || $_GET['search'] == 'Search') {
            $filter_array = array('name' => 'User.search_name', 'id' => 'User.user_id', 'role_id' => 'User.i_role_id', 'active' => 'User.b_active');
        } else {
            $filter_array = array('name' => 'User.search_name', 'id' => 'User.user_id', 'role_id' => 'User.i_role_id', 'active' => 'User.b_active', 'search');
        }

        $conditions = $this->_filter_conditions($filter_array);
        if (!is_array($conditions))
        {
            $conditions = Array($conditions);
        }
        if (!empty($conditions))
        {
            $options = array_merge($options, $conditions);
        }
        $this->paginate['conditions'] = $options;
    }

    function _render_index_options()
    {
        $this->loadModel('Role');
        $this->set('RoleList', $this->Role->find('all', Array('fields' => Array('role_id', 'role_name'))));
    }

    function _render_index_data()
    {
        $this->_render_index_bindModel();
        $this->_filter_index_conditions();
        $this->_order_index_conditions();
        if (isset($_GET['role_id']))
        {
            $this->data = $this->paginate('User', array('User.role_id  ' => $_GET['role_id'], 'User.name <>' => 'dnl_support'));
        }
        else
        {

            $this->data = $this->paginate('User', array('User.role_id  <>' => '0', 'User.name <>' => 'dnl_support'));
        }
    }

    function index()
    {
        $this->pageTitle = "Configuration/User Management";
        $this->init_info();
        $this->_render_index_data();
        $this->_render_index_options();
        $this->set('have_add', 1);
    }

    public function show_carrier()
    {
        $this->pageTitle = "Configuration/Carrier Users";
        $this->_render_index_bindModel();
        $this->_filter_index_conditions(Array('user_type = 3'));
        $this->_order_index_conditions();
        $this->data = $this->paginate('User');
        $this->loadModel('Role');
        $this->set('have_add', 1);
        $this->set('RoleList', $this->Role->find('all', Array('fields' => Array('role_id', 'role_name'))));
    }

    public function show_online()
    {
        $this->pageTitle = "Configuration/Online Users";
        $this->_render_index_bindModel();
        $this->_filter_index_conditions(Array("CURRENT_TIMESTAMP(0) - last_seen < interval '5 minutes'", "User.name != 'dnl_support'"));
        $this->_order_index_conditions();
        $this->data = $this->paginate('User');
        $this->loadModel('Role');
        $this->set('have_add', 0);
        $this->set('RoleList', $this->Role->find('all', Array('fields' => Array('role_id', 'role_name'))));
    }

    function _render_last_login_data()
    {
        $this->_render_index_bindModel();
        $this->_filter_index_conditions(Array('last_login_time is not null'));
        $this->_order_index_conditions();
        $this->data = $this->paginate('User');
    }

    function last_login()
    {
        $this->pageTitle = "Configuration/User Management";
        $this->_render_last_login_data();
        $this->_render_index_options();
        /* $this->init_info ();
          $this->set('p',$this->User->findLastAccessUser()); */
    }

    function _filter_search()
    {
        $search = array_keys_value($this->params, 'url.search');
        if (!empty($search))
        {
            return "User.name like '%$search%'";
        }
        return null;
    }

    /**
     * 查询某个角色的用户和代理商
     */
    public function viewroleuser()
    {


        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {

            $results = $this->User->likequeryby_role($_POST['searchkey'], $currPage, $pageSize, $this->Session->read("r_role_id"));
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data['User']))
        {


            $results = $this->User->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
            pr($results);
        }
        else
        {

            $this->init_param();

            $results = $this->User->findAllby_role($currPage, $pageSize, $this->Session->read("r_role_id"));
        }




        $this->set('p', $results);
    }

    public function activeornot()
    {
        if (!$_SESSION['role_menu']['Configuration']['users']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $qs = $this->User->query("update users set active = $status where user_id = $id");
        if (count($qs) == 0)
        {
            echo 'true';
        }
        else
        {
            echo "false";
        }
    }

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

//exchange 管理注册
    function registration()
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //var_dump($_REQUEST);
        $search_arr = array();
        if (!empty($_REQUEST['search']) && strcmp('Search....', $_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search_value'] = $_REQUEST['search'];
            $search_arr['status'] = empty($_REQUEST['user_type']) ? null : $_REQUEST['user_type'];
        }
        else
        {                      //按条件搜索
            $search_type = 1;
            $search_arr['status'] = empty($_REQUEST['user_type']) ? null : $_REQUEST['user_type'];
        }

        $results = $this->User->ListOrderUsers($currPage, $pageSize, $search_arr, $search_type);
        //var_dump($results->dataArray[0]);
        $this->set('p', $results);
        $status_arr = array(1 => 'New', 2 => 'Hold', 3 => 'Accepted', 4 => 'Mail Validated');
        if (!empty($_GET['out_put']) && $_GET['out_put'] == 'csv')
        {
            Configure::write('debug', 0);
            $sql_where = '';
            if (isset($search_arr['status']))
            {
                if (!empty($search_arr['status']))
                {
                    if ($search_arr['status'] == 'all')
                    {
                        $sql_where .= " ";
                    }
                    else
                    {
                        $sql_where .= " and order_user.status = " . intval($search_arr['status']);
                    }
                }
            }


            if ($search_type == 1)
            {
                if (!empty($search_arr['name']))
                    $sql_where .= " and order_user.name like '%" . addslashes($search_arr['name']) . "%'";
            }
            else
            {
                $sql_where .= " and order_user.name like '%" . addslashes($search_arr['search_value']) . "%'";
            }

            $sql = "select order_user.*,client.client_id as real_client_id, client.status as client_status from order_user left join client on order_user.client_id=client.client_id where 1=1" . $sql_where;
            $results = $this->User->query($sql);



            $file_name = "registration_report.csv";
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Company Name" . ",";
            echo "Corporate Contact Name" . ",";
            echo "Corporate Contact Phone" . ",";
            echo "Corporate Email" . ",";
            echo "Name" . ",";
            ;
            echo "Status" . ",";
            echo "Primary Contact Email" . ",";
            echo "Technical Contact Email" . ",";
            echo "Billing Contact Email";

            echo "\n";
            foreach ($results as $value)
            {
                echo $value[0]['company_name'] . ",";
                echo $value[0]['corporate_contact_name'] . ",";
                echo $value[0]['corporate_contact_phone'] . ",";
                echo $value[0]['corporate_contact_email'] . ",";
                echo $value[0]['name'] . ",";
                echo $status_arr[$value[0]['status']] . ",";
                echo $value[0]['primary_email'] . ",";
                echo $value[0]['technical_email'] . ",";
                echo $value[0]['billing_email'];
                echo "\n";
            }
            exit();
        }
        else if (!empty($_GET['out_put']) && $_GET['out_put'] == 'xls')
        {
            Configure::write('debug', 0);
            $sql_where = '';
            if (isset($search_arr['status']))
            {
                if (!empty($search_arr['status']))
                {
                    if ($search_arr['status'] == 'all')
                    {
                        $sql_where .= " ";
                    }
                    else
                    {
                        $sql_where .= " and order_user.status = " . intval($search_arr['status']);
                    }
                }
            }


            if ($search_type == 1)
            {
                if (!empty($search_arr['name']))
                    $sql_where .= " and order_user.name like '%" . addslashes($search_arr['name']) . "%'";
            }
            else
            {
                $sql_where .= " and order_user.name like '%" . addslashes($search_arr['search_value']) . "%'";
            }

            $sql = "select order_user.*,client.client_id as real_client_id, client.status as client_status from order_user left join client on order_user.client_id=client.client_id where 1=1" . $sql_where;
            $results = $this->User->query($sql);


            $file_name = "registration_report.xls";

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");

            echo "Company Name" . "	";
            echo "Corporate Contact Name" . "	";
            echo "Corporate Contact Phone" . "	";
            echo "Corporate Email" . "	";
            echo "Name" . "	";
            echo "Status" . "	";
            ;
            echo "Primary Contact Email" . "	";
            echo "Technical Contact Email" . "	";
            echo "Billing Contact Email";

            echo "\n";


            foreach ($results as $value)
            {
                echo $value[0]['company_name'] . "	";
                echo $value[0]['corporate_contact_name'] . "	";
                echo $value[0]['corporate_contact_phone'] . "	";
                echo $value[0]['corporate_contact_email'] . "	";
                echo $value[0]['name'] . "	";
                echo $status_arr[$value[0]['status']] . "	";
                echo $value[0]['primary_email'] . "	";
                echo $value[0]['technical_email'] . "	";
                echo $value[0]['billing_email'];
                echo "\n";
            }
            exit();
        }
    }

    public function holdornot()
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $id = $_REQUEST['id'];
        $status = intval($_REQUEST['status']);
        $qs = $this->User->query("update order_user set status = $status where id = $id");
        if (count($qs) == 0)
        {
            echo 'true';
        }
        else
        {
            echo "false";
        }
    }

    function del_order_user($user_id)
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w'])
        {
            $this->redirect_denied();
        }
        $size = $this->User->query("delete from  order_user where  id=" . intval($user_id));
        $this->User->query("delete from  client where  user_id=" . intval($user_id));
        $this->Session->write('m', $this->User->create_json(201, $this->params['pass'][1] . __('del order_user success!', true)));

        $this->redirect(array('action' => 'registration'));
    }

    function view_orderuser()
    {
        //Configure::write('debug', 2);
        $counties = $this->Client->query("SELECT DISTINCT country FROM code where country != '' ORDER BY country ASC");
        $this->set('counties', $counties);
        $transaction_fees = $this->Client->query("SELECT * FROM transaction_fee  ORDER BY id ASC");
        $this->set('transaction_fees', $transaction_fees);

        $id = $this->params ['pass'][0];
        $p = $this->User->query("select order_user.* ,client.transaction_fee_id from order_user left join client on client.client_id = order_user.client_id where order_user.id={$id} limit 1");
        if (!empty($_POST))
        {

            $res1 = $this->Clients->edit_client();
            $res2 = $this->Orderuser->edit_user();

            //var_dump($res2);
            //exit();
            if (is_array($res2) && (count($res2) == 1))
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Sucessful edit!', true));
                $this->xredirect('/users/registration');
            }
            else
            {
                $this->Client->create_json_array('', 101, __('Fail edit!', true));
            }
        }
        $this->set("p", $p);
        $this->set('country', $this->User->findAll_country());
    }

    function reset_password()
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $order_user_id = empty($this->params ['pass'][0]) ? 0 : $this->params ['pass'][0];
        require_once(APP . 'vendors/mail_reset_order_user.php');
    }

    function update_orderuser($id)
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w'])
        {
            $this->redirect_denied();
        }
        $p = $this->User->query("select * from order_user where id={$id} limit 1");
        $this->set("p", $p);
        $password = empty($_REQUEST['password']) ? $p[0][0]['password'] : md5($_REQUEST['password']);

        $fieldList = array(
            'name' => "'" . addslashes($_REQUEST['username']) . "'",
            'password' => "'" . addslashes($password) . "'",
            //'question' 	=> "'".addslashes($_REQUEST['security_question'])."'",
            //'answer'			=> "'".addslashes($_REQUEST['security_answer'])."'",
            'company_name' => "'" . addslashes($_REQUEST['company_name']) . "'",
            'addr1' => "'" . addslashes($_REQUEST['address1']) . "'",
            'addr2' => "'" . addslashes($_REQUEST['address2']) . "'",
            'city' => "'" . addslashes($_REQUEST['city']) . "'",
            'province' => "'" . addslashes($_REQUEST['stateorprovince']) . "'",
            'post_code' => "'" . addslashes($_REQUEST['ziporpostcode']) . "'",
            'country' => "'" . addslashes($_REQUEST['country']) . "'",
            'corporate_contact_name' => "'" . addslashes($_REQUEST['corporatecontactname']) . "'",
            'corporate_contact_phone' => "'" . addslashes($_REQUEST['corporatecontactphone']) . "'",
            'corporate_contact_cell' => "'" . addslashes($_REQUEST['corporatecontactcell']) . "'",
            'corporate_contact_email' => "'" . addslashes($_REQUEST['corporatecontactemail']) . "'",
            'alternate_emails' => "'" . addslashes($_REQUEST['alternateemail']) . "'",
            'corporate_contact_fax' => "'" . addslashes($_REQUEST['corporatecontactfax']) . "'",
            'corporate_registration_id' => "''",
            'corporate_registration_country' => "'" . addslashes($_REQUEST['countryorregion']) . "'",
            'billing_contact_name' => "'" . addslashes($_REQUEST['billingname']) . "'",
            'billing_contact_phone' => "'" . addslashes($_REQUEST['billingphone']) . "'",
            'billing_contact_email' => "'" . addslashes($_REQUEST['billingemail']) . "'",
            'billing_contact_fax' => "'" . addslashes($_REQUEST['billingfax']) . "'",
            'paypal' => "'" . addslashes($_REQUEST['paypal']) . "'",
            'bank_name' => "'" . addslashes($_REQUEST['bankname']) . "'",
            'bank_address' => "'" . addslashes($_REQUEST['bankaddress']) . "'",
            'bank_city' => "'" . addslashes($_REQUEST['bankcity']) . "'",
            'bank_province' => "'" . addslashes($_REQUEST['stateorprovince']) . "'",
            'bank_country' => "'" . addslashes($_REQUEST['bankcountry']) . "'",
            'bank_post_code' => "'" . addslashes($_REQUEST['zipcode']) . "'",
            'bank_account_name' => "'" . addslashes($_REQUEST['accountname']) . "'",
            'bank_routing_number' => "'" . addslashes($_REQUEST['routingnumber']) . "'",
            'bank_account_number' => "'" . addslashes($_REQUEST['accountnumber']) . "'",
            'bank_swift' => "'" . addslashes($_REQUEST['swiftiban']) . "'",
            'bank_notes' => "'" . addslashes($_REQUEST['notes']) . "'",
            'noc_contract_phone' => "'" . addslashes($_REQUEST['noc_ontact_phone']) . "'",
            'noc_contract_email' => "'" . addslashes($_REQUEST['noc_contact_email']) . "'",
            'noc_contract_type' => "'" . addslashes($_REQUEST['noc_contract_type']) . "'",
            'noc_contract_im' => "'" . addslashes($_REQUEST['noc_contact_im']) . "'",
            'currency_preference' => "'" . addslashes($_REQUEST['currency_preference']) . "'"
        );

        $sql = "update order_user set ";
        $sql_field_value = array();
        foreach ($fieldList as $k => $v)
        {
            $sql_field_value[$k] = $k . ' = ' . $v;
        }
        $sql .= implode(", ", $sql_field_value);
        $sql .= " where id=$id";

        $sql_update = $sql;
        $qs = $this->User->query($sql_update);
        if (count($qs) == 0)
        {
            $this->User->create_json_array('', 201, __('Edit successfully', true));
        }
        else
        {
            $this->User->create_json_array('', 101, __('Edit fail!', true));
        }
        $this->Session->write("m", User::set_validator());
        $this->redirect('view_orderuser/' . $id);
    }


    public function ajax_change_password()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $new_password = $_POST['UserNew'];
        $user_id = base64_decode($_POST['user_id']);
	    $user_info = $this->User->findByUserId($user_id);
        $user = $user_info['User'];
        $save_arr = array(
            'user_id' => $user_id,
            'password' => md5($new_password)
        );
        $result = $this->User->save($save_arr);
        if($result === false)
        {
            $return_arr = array(
                'flg' => false,
                'msg' => __('Update Failed',true),
            );
        }
        else
        {
            $return_arr = array(
                'flg' => true,
                'msg' => 'The Password of User [' . $user['name'] . '] is updated successfully!',
            );
        }
        echo json_encode($return_arr);
    }


    public function ajax_check_exist()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $name = $this->params['url']['fieldValue'];
        $ajax_id = $this->params['url']['fieldId'];
        $user_id = substr($ajax_id,strrpos($ajax_id,'_')+1);
        $count = $this->User->find('count',array(
            'conditions' => array(
                'name' => $name,
                'user_id != ?' => intval($user_id),
            )
        ));
        if ($count)
            echo json_encode(array($ajax_id,false));
        else
            echo json_encode(array($ajax_id,true));
    }

    public function show_agent()
    {
        $this->pageTitle = __('Agent Management',true);
        $this->loadModel('Agent');
        $this->set('frequency_type',$this->Agent->get_frequency_type());
        $this->set('method_type',$this->Agent->get_method_type());
        $conditions = array(
        );
        $get_agent_name = $this->_get('agent_name');
        if($get_agent_name)
            $conditions = "Agent.agent_name like '%" . trim($this->_get('agent_name')) . "%'";
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('agent_name' => 'asc');
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

        $this->paginate = array(
            'fields' => array(
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('Agent');
        if(empty($this->data) && !$get_agent_name)
        {
            $model_name = "Agent";
            $msg = "Agent";
            $add_url = "save_agent";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }

}

?>
