<?php
class User extends AppModel
{
    var $name = 'User';
    var $useTable = 'users';
    var $primaryKey = 'user_id';
    var $xvalidatevar = Array(
        'name' => Array(
            'noEmpty' => 'username is not null!',
            'length' => Array('message' => 'username is too length', 'length' => 66),
            'en' => 'username must numeri',
            'unique' => 'username is unique'
        ),
        'password' => Array(
            'noEmpty' => 'password is not null!',
            'length' => Array('message' => 'password is too length', 'length' => 66),
            'en' => 'password must numeri'
        ),
        'fullname' => Array(
            'length' => Array('message' => 'fullname is too length', 'length' => 66),
            'en' => 'fullname must numeri'
        ),
        'email' => Array(
            'email' => 'email is not format!'
        )
    );

    CONST USER_USER_TYPE_ADMINISTRATOR = 1;
    CONST USER_USER_TYPE_AGENTS = 2;
    CONST USER_USER_TYPE_CARRIER = 3;
    CONST USER_USER_TYPE_CARDS = 4;
    CONST USER_USER_TYPE_EXPERIENCE = 6;

    function _Encryption_password()
    {
        $this->data['User']['password'] = md5($this->data['User']['password']);
    }
    /*	function beforeSave()
        {
            $this->_Encryption_password();
            return true;
        }*/

    /*
 * 认证随身同系统用户
 */
    public function auth_user($username, $password)
    {
        $sql = "select users.user_id,users.role_id,users.client_id,user_type from users 
						 where active=true and 
						  users.name ='$username' and users.password = md5('$password')";
        $users = $this->query($sql);//var_dump($users);
        if (count($users) > 0) {
            if ($users[0][0]['user_type'] == 1) {
                return $users;
            }
            if (empty($users[0][0]['user_type'])) {
                return false;
            }


            if ($users[0][0]['user_type'] == 3) {
                if (empty($users[0][0]['client_id'])) {
                    return false;
                }
                $status = $this->query("select status from client where client_id = {$users[0][0]['client_id']}");
                if ($status[0][0]['status'] == false) {

                    $signRes = $this->query("SELECT * FROM signup WHERE login='$username' AND password='$password' and status=0");

                    if (empty($signRes)) {
                        return false;
                    }

                    $_SESSION['new_user'] = true;
                }
            }
            return $users;
        } else {
            $client = $this->auth_client($username, $password);
            if (!empty($client)) {
                return $client;
            } else {
                $signupInfo = $this->authNewUser($username, $password);
                if ($signupInfo) {
                    $signupInfo[0][0]['user_type'] = 6;
                }

                return $signupInfo;
            }
            return false;
        }
    }

    public function authNewUser($username, $password)
    {
        $sql = "select * from signup 
			    where  login ='$username' and password = '$password'";

        return $this->query($sql);
    }

    public function get_module($role_id)
    {
        $arr = array();
        if (!$role_id) {
            $sql = "SELECT id,module_name FROM sys_module";
        } else {
            $sql = "SELECT DISTINCT sys_module.id,sys_module.module_name FROM sys_role_pri
					INNER JOIN sys_pri ON sys_pri.pri_name = sys_role_pri.pri_name
					INNER JOIN sys_module ON sys_module.id = sys_pri.module_id WHERE sys_role_pri.role_id = {$role_id}
					AND sys_module.module_name is NOT NULL ";
        }
        $data = $this->query($sql);
        $arr[0] = '';
        foreach ($data as $item)
            $arr[$item[0]['id']] = $item[0]['module_name'];
        return $arr;
    }


    public function get_user_pri($user_id)
    {
        $arr = array();
        if ($user_id == NULL) {
            $where = '';
        } else {
            $where = "WHERE role_id = (SELECT role_id FROM users WHERE user_id = {$user_id})";
        }
        $sql = <<<EOT
    
SELECT sys_pri.id,sys_pri.pri_val FROM sys_role_pri 

JOIN sys_pri ON sys_role_pri.pri_name = sys_pri.pri_name

$where

EOT;
        $data = $this->query($sql);
        $arr[0] = '';
        foreach ($data as $item)
            $arr[$item[0]['id']] = $item[0]['pri_val'];
        return $arr;
    }


    /*
     * 验证用户登录成功或失败
     */
    public function auth($username, $password)
    {
        $users = $this->query("select user_id from users where name = '$username' and password = md5('$password')");
        if (count($users) > 0) return $users[0][0]['user_id'];
        else return false;
    }


    public function auth_client($username, $password)
    {

        $sql = "select role_id,client_id,   '3' as user_type from client where login = '$username' and password = '$password'";
        $users = $this->query($sql);
        if (count($users) > 0) {
            //no  role默认client角色是2
            if ($users[0][0]['role_id'] != 2) {
                return false;
            }


//将client 的用户名和密码插入user表
            $client_id = $users[0][0]['client_id'];
            $sql = "select count(*) from  users  where  name='$username' ";
            $users2 = $this->query($sql);
            $count = $users2[0][0]['count'];
            $password = md5("$password");
            if ($count == 0) {

                $this->query("insert into users(client_id,name,password,role_id,user_type)values($client_id,'$username','$password',2,3)");
            } else {
                $this->query("update  users  set  active=true,  password=md5('$password'),role_id=2,user_type=3  where client_id=$client_id and  name='$username'");
            }
            $sql = "select user_id,role_id,client_id,user_type from users where    active=true  and  name ='$username' and password = md5('$password')";

            $users = $this->query($sql);
            if (count($users) > 0) {
                //admin
                if ($users[0][0]['user_type'] == 1) {
                    return $users;

                    //判断用户类型
                }
                if (empty($users[0][0]['user_type'])) {
                    return false;

                }

                if (empty($users[0][0]['role_id'])) {
                    return false;
                }

                return $users;
            } else {

                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * 通过role_id查询角色信息
     * @param unknown_type $user_id
     */
    public function findRoleInfo_role_id($role_id)
    {
        $list = $this->query("select role_id,role_name,view_pw,reseller_able,default_sysfunc_id,default_func,func_url from role 
		left join (select system_function_id, func_name  as default_func,func_url from system_function )  sys on sys.system_function_id=role.default_sysfunc_id
		where role_id =$role_id");
        if (count($list) > 0) {
            return $list[0][0];
        } else {
            return false;
        }
    }


    /**
     * 通过用户查询角色信息
     * @param unknown_type $user_id
     */
    public function findRoleInfo_user_id($user_id)
    {
        $list = $this->query("select * from role 		where role_id in(select role_id from users where user_id=$user_id)");
        if (count($list) > 0) return $list[0][0];
        else return false;
    }


    /**
     * 通过角色查找权限信息
     * @param unknown_type $role_id
     */
    public function findPrivilegeInfo($role_id)
    {
        if (empty($role_id)) {
            return false;
        }

        //查询角色对每个模块的权限	---界面上控制权限用
        $list = $this->query("select  	a.readable ,a.writable,a.executable, b.func_name from  system_function  as b
  left join (select   system_function_id ,writable,readable,executable from  role_privilege   where role_id=$role_id) as a  
  on  a.system_function_id=b.system_function_id ");


        $size = count($list);

        //将每个模块的权限压入session
        for ($i = 0; $i < $size; $i++) {
            $key = "sst_" . $list[$i][0]['func_name'];
            $value = $list[$i][0];
            $_SESSION[$key] = $value;
            //	pr($value);
        }


        $flag = '';
//读取每个大模块-----------输出菜单
//路由伙伴模块	
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=1  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_clent'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_clent_read'] = 'true';
                break;
            }
        }

        //代理商模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=2  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_reseller'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_reseller_read'] = 'true';
                break;
            }
        }
        //零售帐户模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=3   and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_account'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_account_read'] = 'true';
                break;
            }
        }

        //统计模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=4  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_summary'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_summary_read'] = 'true';
                break;
            }
        }
        //工具模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=5  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_tools'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_tools_read'] = 'true';
                break;
            }
        }
        //路由配置模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=6  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_routeconfig'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_routeconfig_read'] = 'true';
                break;
            }
        }

        //系统配置模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=7  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_sysconfig'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_sysconfig_read'] = 'true';
                break;
            }
        }
        //系统管理模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=8  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_sysmanager'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_sysmanager_read'] = 'true';
                break;
            }
        }
        //策略管理模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=9  and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_stratemanager'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_stratemanager_read'] = 'true';
                break;
            }
        }
        //充值管理模块
        $list = $this->query("select   b.func_url,b.image_name,key_118n,a.readable ,a.executable  
	from  system_function  as b
  left join (select   system_function_id ,readable,executable from  role_privilege   where role_id=$role_id)as a
    on  a.system_function_id=b.system_function_id 
  where  b.func_type=10   and develop_status>2
  order by b.system_function_id");
        $_SESSION['sst_refillmanager'] = $list;
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if (!empty($list[$i][0]['readable'])) {
                $_SESSION['sst_refillmanager_read'] = 'true';
                break;
            }
        }
        return true;
    }

    function batchupdate($data)
    {
        $old_reseller_id = $data['User']['old_reseller_id'];
        $new_reseller_id = $data['User']['new_reseller_id'];
        $error_flag = false;//有错误信息
        if (empty($old_reseller_id)) {
            $this->create_json_array('#UserOldResellerId', 101, __('selbatchupdateuserreseller', true));
            $error_flag = true;//有错误信息
        }
        if (empty($new_reseller_id)) {
            $this->create_json_array('#UserNewResellerId', 101, __('selectbatchupdateuserreseller', true));
            $error_flag = true;//有错误信息
        }
        if ($old_reseller_id == $new_reseller_id) {
            $this->create_json_array('#UserNewResellerId', 101, __('selectbatchupdateuserreseller', true));
            $error_flag = true;//有错误信息
        }

        if (empty($error_flag)) {
            $this->query("update users set reseller_id=$new_reseller_id  where reseller_id=$old_reseller_id;");
            $error_flag = false;
        }

        return $error_flag;//返回错误信息


    }

    function validate_userform($data, $post_arr)
    {
        $error_flag = false;//错误信息标志

        $user_id = $this->getkeyByPOST('user_id', $post_arr);
        $name = $data['User']['name'];

        $email = $data['User']['email'];


        if (empty($name)) {
            $this->create_json_array('#UserName', 101, __('pleaseinputusername', true));
            $error_flag = true;//有错误信息
        }


        if (!empty($email)) {
            if (!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$", $email)) {
                $this->create_json_array('#UserEmail', 101, __('pleaseinputemail', true));
                $error_flag = true;//有错误信息

            }
        }


        $c = $this->check_name($user_id, $name);
        if ($c != 0) {
            $this->create_json_array('#UserName', 301, __('usernameexist', true));
            $error_flag = true;
        }

        return $error_flag;
    }


    function check_name($user_id, $name)
    {


        $name = "'" . $name . "'";
        empty($user_id) ? $sql = "select count(*) from users where name=$name " :
            $sql = "select count(*) from users where name=$name  and user_id<>$user_id";
        $c = $this->query($sql);
        if (empty($c)) {
            return 0;
        } else {
            return $c[0][0]['count'];
        }
    }


    function saveOrUpdate($data, $post_arr)
    {
        $data['User']['user_type'] = $_SESSION['login_type'];
        /*			    $reseller_id=$data['User']['reseller_id'];
                $client_id=$data['User']['client_id'];


                 if($reseller_id!=''){
                    $data['User']['user_type']='2';
                        $data['User']['role_id']='1';
                }
                if($reseller_id=='0'){
                    $data['User']['user_type']='1';
                    $data['User']['role_id']='0';
                }
                            if($client_id!=''){
                    $data['User']['user_type']='3';
                    $data['User']['role_id']='2';
                }*/


        $msgs = $this->validate_userform($data, $post_arr);//验证
        if (!empty($msgs)) {
            return false;//add fail
        } else {
            $user_id = $this->saveOrUpdate_user($data, $post_arr);//添加或者更新
            return true;//add succ
        }
    }


    /**
     * 添加Role or 更新Role
     */
    function saveOrUpdate_user($data, $post_arr)
    {

        $data['User']['user_type'] = $_SESSION['login_type'];
        if ($data['User']['client_id'] != '') {
            $data['User']['user_type'] = 3;
        }
        $data['User']['password'] = md5($data['User']['password']);
        $data['User']['create_user_id'] = $_SESSION['sst_user_id'];//加密
        $type = $_SESSION['login_type'];
        if ($type != 1) {
            $data['User']['user_type'] = 5;//不是系统管理员建的用户都是普通用户
        } else {
        }
        $user_id = $this->getkeyByPOST('user_id', $post_arr);
        $data['User']['create_time'] = date("Y-m-d   H:i:s");
        if (!empty($user_id)) {
            $data['User']['user_id'] = $user_id;
            $this->save($data ['User']);//更新角色
        } else {
            $this->save($data ['User']);//添加角色
            $user_id = $this->getlastinsertId();
        }
        return $user_id;
    }


    function carrer_limit($values_str, $user_id)
    {
        if ($user_id) {
            $this->query("DELETE FROM users_limit where user_id = {$user_id}");
            $this->query("INSERT INTO users_limit (user_id, client_id) VALUES {$values_str}");
        }
    }


    function del_limit($id)
    {
        if ($id) {
            $this->query("DELETE FROM users_limit WHERE user_id = {$id}");
        }
    }

    function get_carrier_limit($id)
    {
        $results = $this->query("SELECT client_id FROM users_limit WHERE user_id = {$id}");
        $arr = array();
        foreach ($results as $item) {
            array_push($arr, $item[0]['client_id']);
        }
        return $arr;
    }


    function findRole()
    {
        $r = $this->query("select role_id ,role_name from role  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['role_id'];
            $l[$key] = $r[$i][0]['role_name'];
        }

        return $l;
    }

    function getRole()
    {
        $return = array();
        $r = $this->query("select role_id ,role_name from role  ");
        foreach ($r as $k => $v) {
            $return[$v[0]['role_id']] = $v[0]['role_name'];
        }
        return $return;
    }

    function getSysRole()
    {

        $return = array();
        $r = $this->query("select role_id ,role_name from sys_role  order by role_name");
        foreach ($r as $k => $v) {
            $return[$v[0]['role_id']] = $v[0]['role_name'];
        }
        return $return;
    }

    /**
     * 查询客户
     */
    function findClient()
    {

        $r = $this->query("select client.client_id ,client.name from client  order by client.name");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['client_id'];
            $l[$key] = $r[$i][0]['name'];
        }

        return $l;
    }


    function findLastAccessUser()
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        $role_where = '';
        //权限条件
        if ($login_type == 3) {
            $privilege = " and (client_id = {$_SESSION['sst_client_id']})";
        }

        if (isset($_GET['role']) && $_GET['role'] != '') {
            $role_where = " and (users.role_id = {$_GET['role']})";
        }

//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (users.name like '%{$_GET['search']}%'  or  user_id::text like '%{$_GET['search']}%' 
	 or  users.client_id::text like '%{$_GET['search']}%' or fullname::text like '%{$_GET['search']}%')" : '';

        $name_where = !empty($_GET['name']) ? "  and (users.name::text like '%{$_GET['name']}%')" : '';
        $active_where = !empty($_GET['search_status']) ? "  and (users.active = '{$_GET['search_status']}')" : '';
        $rolesearch_where = !empty($_GET['search_role']) ? "  and (users.role_id = '{$_GET['search_role']}')" : '';

        //路由伙伴
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (id={$_GET ['query'] ['id_clients']})" : '';

        $totalrecords = $this->query("select count(user_id) as c from users where 1=1 
	  $like_where    $name_where  $client_where  $role_where $active_where $rolesearch_where  $privilege  ");
        $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
        $page->setCurrPage($currPage);//当前页
        $page->setPageSize($pageSize);//页大小

        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  user_id,users.name as user_name,client.name as client_name ,users.create_time,users.active,users.user_type,last_login_time,users.email as email ,users.login_ip as login_ip from  users
left join client  on  client.client_id=users.client_id		where 1=1  and last_login_time is not null
		$like_where    $name_where  $client_where   $role_where $active_where $rolesearch_where  $privilege";
        $sql .= " order by last_login_time     desc  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }


    public function findAll()
    {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        $role_where = '';
        //权限条件
        if ($login_type == 3) {
            $privilege = " and (client_id = {$_SESSION['sst_client_id']})";
        }

        if (isset($_GET['role']) && $_GET['role'] != '') {
            $role_where = " and (users.role_id = {$_GET['role']})";
        }

//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (users.name like '%{$_GET['search']}%'  or  user_id::text like '%{$_GET['search']}%' 
	 or  users.client_id::text like '%{$_GET['search']}%' or fullname::text like '%{$_GET['search']}%')" : '';

        $name_where = !empty($_GET['name']) ? "  and (users.name::text = '{$_GET['name']}')" : '';
        $active_where = !empty($_GET['search_status']) ? "  and (users.active = '{$_GET['search_status']}')" : '';
        $rolesearch_where = !empty($_GET['search_role']) ? "  and (users.role_id = '{$_GET['search_role']}')" : '';

        //路由伙伴
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (id={$_GET ['query'] ['id_clients']})" : '';

        $totalrecords = $this->query("select count(user_id) as c from users where users.role_id<>0 
	  $like_where    $name_where  $client_where  $role_where $active_where $rolesearch_where  $privilege  ");
        $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
        $page->setCurrPage($currPage);//当前页
        $page->setPageSize($pageSize);//页大小

        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  user_id,users.name as user_name,client.name as client_name ,users.create_time,users.active,users.user_type,last_login_time ,users.email as email from  users
left join client  on  client.client_id=users.client_id		where  users.role_id<>0 
		$like_where    $name_where  $client_where   $role_where $active_where $rolesearch_where  $privilege";
        $sql .= " order by user_type     desc  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }


    //exchange portal 管理注册
    public function ListOrderUsers($currPage = 1, $pageSize = 15, $search_arr = array(), $search_type = 0)
    {
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;

        $sql_where = '';
        if (isset($search_arr['status'])) {
            if (!empty($search_arr['status'])) {
                if ($search_arr['status'] == 'all') {
                    $sql_where .= " ";
                } else {
                    $sql_where .= " and order_user.status = " . intval($search_arr['status']);
                }
            }
        }

        if ($search_type == 1) {
            if (!empty($search_arr['name'])) $sql_where .= " and order_user.name like '%" . addslashes($search_arr['name']) . "%'";
        } else {
            $sql_where .= " and order_user.name like '%" . addslashes($search_arr['search_value']) . "%'";
        }

        $sql = "select count(*) as c from order_user left join client on order_user.client_id=client.client_id where 1=1" . $sql_where;
        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
        $page->setCurrPage($currPage);//当前页
        $page->setPageSize($pageSize);//页大小

        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select order_user.*,client.client_id as real_client_id, client.status as client_status from order_user left join client on order_user.client_id=client.client_id where 1=1" . $sql_where;

        $sql .= " limit '$pageSize' offset '$offset'";
        //echo $sql;
        $results = $this->query($sql);

        $page->setDataArray($results);//Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    public function findAll_country()
    {
        $sql1 = "select distinct country from code order by country ASC";
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['country'];
            $l[$key] = $r[$i][0]['country'];
        }
        return $l;

    }

    public function createNewUser($data)
    {
//            echo '<pre>';
//                        print_r($data);die;
//            $sql = "insert into users (name,password,fullname,email,default_mod2,default_mod,active,role_id,user_type) values ('{$data['name']}','{$data['password']}','{$data['fullname']}','{$data['email']}','{$data['default_mod2']}','{$data['default_mod']}','{$data['active']}','{$data['role_id']}','{$data['user_type']}')";

        $res = $this->save($data);

        return $this->getlastinsertId();

    }

    public function get_child_pri($role_id, $module_id)
    {
        $sql = "select sys_pri.id, sys_pri.pri_val from sys_pri INNER JOIN sys_role_pri ON sys_pri.pri_name = sys_role_pri.pri_name
WHERE sys_role_pri.role_id = $role_id AND sys_pri.module_id = $module_id and sys_pri.pri_url != '' AND sys_pri.flag = TRUE;";
        return $this->query($sql);
    }

}
?>