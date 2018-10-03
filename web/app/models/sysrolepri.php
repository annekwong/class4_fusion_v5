<?php

class Sysrolepri extends AppModel
{

    var $name = 'Sysrolepri';
    var $useTable = "sys_role_pri";

    //var $primaryKey = "role_id";

    /* function batchupdate($data){
      $old_role_id=$data['Sysrole']['old_role_id'];
      $new_role_id=$data['Sysrole']['new_role_id'];
      $error_flag=false;//有错误信息
      if(empty($old_role_id)){
      $this->create_json_array('#RoleOldRoleId',101,__('pleaseselectusingrole',true));
      $error_flag=true;//有错误信息
      }
      if(empty($new_role_id)){
      $this->create_json_array('#RoleNewRoleId',101,__('pleaseselectbatchrole',true));
      $error_flag=true;//有错误信息
      }
      if($old_role_id==$new_role_id){
      $this->create_json_array('#RoleNewRoleId',101,__('pleaseselectbatchrole',true));
      $error_flag=true;//有错误信息
      }
      if(empty($error_flag)){
      $this->query("update users set role_id=$new_role_id  where role_id=$old_role_id;");
      $error_flag=false;
      }
      return $error_flag;//返回错误信息
      }
     */
    function findRole()
    {
        $r = $this->query("select role_id ,role_name, view_all from sys_role");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['role_id'];
            $l[$key] = $r[$i][0]['role_name'];
        }
        return $l;
    }

    /**
     * 验证
     * @return true 有错误信息
     *     false 没有错误信息
     */
    function validate_role($data, $post_arr)
    {
        $error_flag = false; //错误信息标志

        $role_id = $this->getkeyByPOST('role_id', $post_arr['data']['Sysrole']);
        $name = $data['Sysrole']['role_name'];


        if (empty($name))
        {
            $this->create_json_array('#RoleRoleName', 101, __('pleaseinputrolename', true));
            $error_flag = true; //有错误信息
        }


        $c = $this->check_name($role_id, $name);
        if ($c != 0)
        {
            $this->create_json_array('#RoleRoleName', 301, __('rolenameexist', true));
            $error_flag = true;
        }

        return $error_flag;
    }

    /**
     * 验证角色名称不能重复
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_name($role_id, $name)
    {



        ($role_id == '') ? $sql = "select count(*) from sys_role where role_name='$name' " :
            $sql = "select count(*) from sys_role where role_name='$name'  and role_id<>$role_id";
        $c = $this->query($sql);
        if (empty($c))
        {
            return 0;
        }
        else
        {
            return $c[0][0]['count'];
        }
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     * @return
     */
    function saveOrUpdate($data, $post_arr)
    {
        $msgs = $this->validate_role($data, $post_arr); //验证
        if (!empty($msgs))
        {
            return false; //add fail
        }
        else
        {
            $role_id = $this->saveOrUpdate_role($data, $post_arr); //添加或者更新
            return true; //add succ
        }
    }

    /**
     * 添加Role or 更新Role

     */
    function saveOrUpdate_role($data, $post_arr)
    {
        $role_name = $data ['Sysrole']['role_name'];
        if (isset($data['Sysrole']['view_all']) && $data['Sysrole']['view_all'] == 'on')
            $view_all = 'true';
        else
            $view_all = 'false';
        if (isset($data['Sysrole']['delete_invoice']) && $data['Sysrole']['delete_invoice'] == 'on')
            $delete_invoice = 1;
        else
            $delete_invoice = 0;

        if (isset($data['Sysrole']['delete_payment']) && $data['Sysrole']['delete_payment'] == 'on')
            $delete_payment = 1;
        else
            $delete_payment = 0;

        if (isset($data['Sysrole']['delete_credit_note']) && $data['Sysrole']['delete_credit_note'] == 'on')
            $delete_credit_note = 1;
        else
            $delete_credit_note = 0;

        if (isset($data['Sysrole']['delete_debit_note']) && $data['Sysrole']['delete_debit_note'] == 'on')
            $delete_debit_note = 1;
        else
            $delete_debit_note = 0;

        if (isset($data['Sysrole']['reset_balance']) && $data['Sysrole']['reset_balance'] == 'on')
            $reset_balance = 1;
        else
            $reset_balance = 0;

        if (isset($data['Sysrole']['modify_credit_limit']) && $data['Sysrole']['modify_credit_limit'] == 'on')
            $modify_credit_limit = 1;
        else
            $modify_credit_limit = 0;

        if (isset($data['Sysrole']['modify_min_profit']) && $data['Sysrole']['modify_min_profit'] == 'on')
            $modify_min_profit = 1;
        else
            $modify_min_profit = 0;

        if (isset($data['Sysrole']['view_cost_and_rate']) && $data['Sysrole']['modify_min_profit'] == 'on')
            $view_cost_and_rate = 1;
        else
            $view_cost_and_rate = 0;

        $role_id = $this->getkeyByPOST('role_id', $post_arr['data']['Sysrole']);
        $log_id = "";
        if ($this->check_name($role_id, $role_name) == 0)
        {
            if ($role_id != '')
            {
                $log_id = $this->logging(2, 'Role', "Role Name:{$role_name}");
                $data['Sysrole']['role_id'] = $role_id;
                //更新角色
            }
            else
            {

                //$this->save ( $data ['Sysrole'] );//添加角色
                $this->query("insert into sys_role (role_name) values ('$role_name')");
                $role_info = $this->query("select * from sys_role where role_name = '$role_name' limit 1");
                $role_id = !empty($role_info) ? $role_info[0][0]['role_id'] : 0;
            }

            if ($role_id)
            {
                $this->query("update sys_role set role_name='$role_name', view_all = {$view_all}, delete_invoice = $delete_invoice,  
                                    delete_payment = $delete_payment, delete_credit_note = $delete_credit_note, delete_debit_note = $delete_debit_note, reset_balance = $reset_balance, modify_credit_limit = $modify_credit_limit,
                                    modify_min_profit = $modify_min_profit, view_cost_and_rate = $view_cost_and_rate where role_id=$role_id");
                $rollback_sql = "DELETE FROM sys_role WHERE role_id = {$role_id}";
                $rollback_msg = "Create Role [" . $role_name . "] operation have been rolled back!";
                $log_id = $this->logging(0, 'Role', "Role Name:{$role_name}",$rollback_sql,$rollback_msg);
            }
        }
        $return['role_id'] = $role_id;
        $return['log_id'] = $log_id;
        return $return;
    }

    /**
     * 添加/更新Role and Role privilege

     */
    function saveOrUpdate_rolepri($data, $post_arr)
    {

        $return = $this->saveOrUpdate_role($data, $post_arr);
        $role_id = $return['role_id'];
        $log_id = $return['log_id'];
        if ($role_id != '')
        {
            //var_dump($data['Sysrolepri']);
            //更新角色
            $this->query("delete from sys_role_pri where role_id = {$role_id}");
            if (!empty($data['Sysrolepri']))
            {

                foreach ($data['Sysrolepri'] as $k => $v)
                {
                    if (!empty($v))
                    {
                        $data_rolepri = array();
                        $data_rolepri['Sysrolepri']['role_id'] = intval($role_id);
                        $data_rolepri['Sysrolepri']['pri_name'] = "'" . $k . "'";
                        $data_rolepri['Sysrolepri']['model_r'] = empty($v['model_r']) ? "'f'" : "'t'";
                        $data_rolepri['Sysrolepri']['model_w'] = empty($v['model_w']) ? "'f'" : "'t'";
                        $data_rolepri['Sysrolepri']['model_x'] = empty($v['model_x']) ? "'f'" : "'t'";
                        //$this->save($data_rolepri);
                        $values = implode(", ", $data_rolepri['Sysrolepri']);
                        $this->query("insert into sys_role_pri (role_id, pri_name, model_r, model_w, model_x) values (" . $values . ")");
                    }
                }
            }
        }

        $return['role_id'] = $role_id;
        $return['log_id'] = $log_id;
        return $return;
    }

    /**
     * 删除角色
     * @param unknown_type $id
     *   @return 返回正在使用的用户量
     */
    function del($role_id)
    {

        $list = $this->query("select count(*)  from  users where   role_id=$role_id");
        if (empty($list[0][0]['count']))
        {
            $this->query("delete from sys_role_pri where role_id=$role_id");
            $this->query("delete from sys_role where role_id=$role_id");
            return $list[0][0]['count'];
        }
        else
        {
            return $list[0][0]['count'];
        }
    }

    /**
     * 普通查询
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAll($currPage = 1, $pageSize = 10, $role_sql = '', $order = '')
    {
        if (!empty($order))
        {
            $order = 'order by	' . $order;
        }
        else
        {
            $order = "order by role_name asc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(role_id) as c from sys_role");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select sys_role.*, (select count(1) from users where role_id = sys_role.role_id) as role_users from sys_role where role_name like '%{$role_sql}%' $order	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 模糊查询
     * @param unknown_type $condition
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function likequery($key, $currPage = 1, $pageSize = 10)
    {

        $condition = "'%" . $key . "%'";
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(role_id) as c
	 	from role    
	 	 where role_name   ilike $condition");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select role.role_id,role_name,view_pw,a.role_cnt
		    from  role 
left join (select count(*)as  role_cnt,role_id from users    group by  role_id) a   on  a.role_id=role.role_id

	 	 where role_name   like $condition 
	

	order by role.role_id desc	limit '$pageSize' offset '$offset'";
        //	or (select count(*)>0 from users   where users.role_id=role.role_id )
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function findSysrolepri($role_id)
    {
        $return = array();

        $role_id = intval($role_id);
        if (!empty($role_id))
        {
            $role_pri = $this->query("select * from sys_role_pri where role_id = '{$role_id}'");
            if (!empty($role_pri))
            {
                foreach ($role_pri as $k => $v)
                {
                    $return[$v[0]['pri_name']] = $v[0];
                }
            }
        }
        return $return;
    }

}
