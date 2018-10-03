<?php

/**
 * 
 * @author root
 *
 */
class RolesController extends AppController {

    var $name = 'Roles';
    var $helpers = array('javascript', 'html');

    //读取该模块的执行和修改权限
    function index() {
        $this->redirect('view');
    }

    function active_role($role_id=null, $name=null) {
        $id = $this->params['pass'][0];
        $this->Role->query("update  role  set  active=true  where  role_id=$id");
        $this->Role->query("update  users  set  active=true  where  role_id=$id");
        $name = empty($name) ? 'Roles' : $name;
        $this->Role->create_json_array('', 201, __('The Role [%s] is actived successfully! ', true, $name));
        $this->xredirect('/roles/view');
    }

    function dis_role($role_id=null, $name=null) {
        $id = $this->params['pass'][0];
        $this->Role->query("update  role  set  active=false  where  role_id=$id");
        $this->Role->query("update  users  set  active=false  where  role_id=$id");
        $name = empty($name) ? 'Roles' : $name;
        $this->Role->create_json_array('', 201, __('The Role [%s] is disabled successfully! ', true, $name));
        $this->xredirect('/roles/view');
    }

    function check_form($id) {
        $rale_name = $this->data['Role']['role_name'];
        $c = $this->Role->check_name($id, $rale_name);
        if ($c != 0) {
            $this->Role->create_json_array('#RoleRoleName', 101, __('The Role [%s] has already used!', true, $rale_name));
            $this->Session->write('m', $this->Role->set_validator());
            return false;
        }
        return true;
    }

    function add_role($id=null) {
        $this->pageTitle = "Configuration/Add  Role";
        $this->set('name', $this->select_role_name($id));
        $this->_catch_exception_msg(array($this, '_add_impl'), array('id' => $id));
        $this->render('add_role');
    }

    function _add_impl($params=array()) {
        #post
        if ($this->RequestHandler->isPost()) {

            $this->_create_or_update_role_data($params);
        }
        #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
                $this->data = $this->Role->find('first', array('conditions' => "Role.role_id = {$params['id']}"));

                if (empty($this->data)) {
                    throw new Exception("Permission denied");
                }
            } else {
                
            }
        }
    }

    function set_tip($info) {
        $this->Role->create_json_array('', 201, $info);
        $this->Session->write('m', $this->Role->set_validator());
    }

    function _create_or_update_role_data($params=array()) {
        #update        
        if (isset($params['id']) && !empty($params['id'])) {
            $id = (int) $params ['id'];
            if (!$this->check_form($id)) {
                return;
            }
            //	$this->data = $this->Role->find('first',array('conditions'=>"Role.role_id = {$id}"));
            $this->data ['Role'] ['id'] = $id;
            if ($this->Role->save($this->data)) {
                //pr($this->data);
                //$this->set_tip("Edit role Successfully!");
                $this->set_tip("The Role [".$this->data['Sysrole']['role_name']."] is modified successfully.");
                $this->redirect(array('id' => $id));
            }
        }
        # add
        else {
            if (!$this->check_form('')) {
                return;
            }
            if ($this->Role->save($this->data)) {
                $id = $this->Role->getlastinsertId();
                $this->set_tip("The Role [".$this->data['Sysrole']['role_name']."] is created successfully.");
                $this->redirect(array('id' => $id));
            }
        }
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

    /**
     * 初始化信息
     */
    function init_info() {
        $this->set('sysfuncName', $this->Role->findAllSysFuncName());
        $this->set('sysfunc', $this->Role->findAllSysFunc());
    }

    function init_info_byroleId($role_id) {
        $this->set('sysfuncName', $this->Role->findAllSysFuncName());
        $this->set('sysfunc', $this->Role->findRoleAllsys_func($role_id));
    }

    function edit() {
        if (!empty($this->data ['Role'])) {
            $flag = $this->Role->saveOrUpdate($this->data, $_POST); //保存
            if (empty($flag)) {
                $this->set('m', Role::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $this->Role->create_json_array('#ClientOrigRateTableId', 201, __('Rolehasbeenmodifiedsuccessfully', true));
                $this->Session->write("m", Role::set_validator());
                $this->redirect('/roles/view?edit_id=' . $this->params['form']['role_id']); // succ
            }
        } else {
            $this->Role->role_id = $this->params ['pass'][0];
            $post = $this->Role->read();
            $this->set('post', $post);
            $this->init_info_byroleId($this->params['pass'][0]);
        }
    }

    /**
     * 批量修改角色
     */
    function batchupdate() {
        if (!empty($this->data ['Role'])) {
            $error_flag = $this->Role->batchupdate($this->data); //保存
            if (empty($error_flag)) {
                $this->redirect(array('action' => 'view'));
            } else {
                $this->set('m', Role::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->set('role', $this->Role->findRole());
            }
        } else {

            $this->set('role', $this->Role->findRole());
        }
    }

    /**
     * 添加
     */
    function add() {
        if (!empty($this->data ['Role'])) {
            $flag = $this->Role->saveOrUpdate($this->data, $_POST); //保存
            if (empty($flag)) {
                //添加失败
                $this->set('m', Role::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            } else {
                $this->Role->create_json_array('#ClientOrigRateTableId', 201, __('Roleshavecreatesuccess', true));
                $this->Session->write("m", Role::set_validator());
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            }
        }
        $this->init_info();
    }

    function del() {
        $size = $this->Role->del($this->params['pass'][0]);
        if (empty($size)) {
            $this->Session->write('m', $this->Role->create_json(201, /* $this->params['pass'][1].' '. */ __('delrolesucc', true)));
        } else {
            $this->Session->write('m', $this->Role->create_json(101, $this->params['pass'][1] . __('thisrole', true) . $size . __('rolecurrentuser', true)));
        }
        $this->redirect(array('action' => 'view'));
    }

    /**
     * 
     * 
     * 
     * 
     * 查询角色
     */
    public function view() {
        $this->pageTitle = "Configuration/Roles";
        $this->init_info();
        $order = $this->_order_condtions(Array('role_name', 'active', 'role_cnt'));
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //模糊搜索
        if (isset($_GET['searchkey'])) {
            $results = $this->Role->likequery($_GET['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_GET['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data['Role'])) {


            $results = $this->Role->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            if (!empty($_REQUEST['edit_id'])) {
                $sql = "select role.role_id,role_name,view_pw,reseller_able ,a.role_cnt,role_type
		    from  role 
left join (select count(*)as  role_cnt,role_id from users  group by  role_id) a   on  a.role_id=role.role_id
	 where role.role_id = {$_REQUEST['edit_id']} $order";

                $result = $this->Role->query($sql);
                //分页信息
                require_once 'MyPage.php';
                $results = new MyPage ();
                $results->setTotalRecords(1); //总记录数
                $results->setCurrPage(1); //当前页
                $results->setPageSize(1); //页大小
                $results->setDataArray($result);
                $this->set('edit_return', true);
            } else {
                //
                $results = $this->Role->findAll($currPage, $pageSize, $order);
            }
        }
        $this->set('p', $results);
    }

    public function select_role_name($id=null) {
        if (empty($id)) {
            return '';
        }
        $sql = "select role_name as name from role where role_id=$id";
        return $this->Role->query($sql);
    }

    public function _users_arr($role_id=null) {
        if (!empty($role_id)) {
            
        }
    }

    /* 	function find_users($role_id=null)
      {
      Configure::write('debug',0);
      $this->layout='ajax';
      $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
      $role_id =empty($role_id)? intval($_REQUEST['role_id']):$role_id;
      #echo "<script type='text/javascript' > alert('adsfasdfasdf') </script>";
      //$users_str=null;
      //$name_str='';

      if (!empty($role_id))
      {
      $users_str=$this->Role->query("select name from users where role_id=$role_id ");
      //	$this->set('users', $this->Role->query("select name from users where role_id=$role_id "));
      }
      for ($i=0;$i<count($users_str) ;$i++){
      if(!empty($users_str[$i][0]['name']))
      $name_str.= $users_str[$i][0]['name']."  ";
      }
      echo $name_str;
      } */
}

?>
