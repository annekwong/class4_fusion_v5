<?php
class Exchangesysrolepri extends AppModel{
//	var $name = 'Exchangesysrolepri';
	var $useTable = false;
	//var $primaryKey = "role_id";
	
	/*function batchupdate($data){
		$old_role_id=$data['Exchangesysrole']['old_role_id'];
		$new_role_id=$data['Exchangesysrole']['new_role_id'];
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
		function findRole(){
//		$r= $this->query("select role_id ,role_name, view_all from exchange_sys_role");
//		$size=count($r);
//		$l=array();
//   for ($i=0;$i<$size ;$i++){
//   	$key=$r[$i][0]['role_id'];
//     $l[$key]=$r[$i][0]['role_name'];
//      }
//     return $l;
	}

	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_role($data,$post_arr){
  $error_flag=false;//错误信息标志
	
	  $role_id=$this->getkeyByPOST('role_id',$post_arr['data']['Exchangesysrole']);
	  	$name=$data['Exchangesysrole']['role_name'];

    
			 if(empty($name)){
    	   $this->create_json_array('#RoleRoleName',101,__('pleaseinputrolename',true));
    	   $error_flag=true;//有错误信息
    }
    
    
	  $c=$this->check_name($role_id,$name);
    if($c!=0){
      $this->create_json_array('#RoleRoleName',301,__('rolenameexist',true));
      $error_flag=true;
    }

    return $error_flag;
	}
	
/**
 * 验证角色名称不能重复
 * @param unknown_type $res_id
 * @param unknown_type $a
 */	
function check_name($role_id,$name){
	
	
		
//	($role_id=='')?$sql="select count(*) from exchange_sys_role where role_name='$name' ":
//	$sql="select count(*) from exchange_sys_role where role_name='$name'  and role_id<>$role_id";
//	$c= $this->query($sql);
//	if(empty($c)){
//	return 0;
//	}else{
//	return $c[0][0]['count'];}
}	




/**
 * 添加Client or 更新Client
 * @param unknown_type $data
 * @param unknown_type $post_arr
 * @return 
 */
function saveOrUpdate($data,$post_arr){
	  $msgs=$this->validate_role($data,$post_arr);//验证
	  if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $role_id=$this->saveOrUpdate_role($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }
}


/**
 * 添加Role or 更新Role

 */
	function saveOrUpdate_role($data,$post_arr){
		$role_name=$data ['Exchangesysrole']['role_name'];
                $role_type=$data ['Exchangesysrole']['type'];
                if(isset($data['Exchangesysrole']['view_all']) && $data['Exchangesysrole']['view_all'] == 'on')
                    $view_all = 'true';
                else
                    $view_all = 'false';
                //print_r($data);
                if(isset($data['Exchangesysrole']['delete_invoice']) && $data['Exchangesysrole']['delete_invoice'] == 'on')
                    $delete_invoice = 1;
                else
                    $delete_invoice = 0;
                
                if(isset($data['Exchangesysrole']['delete_payment']) && $data['Exchangesysrole']['delete_payment'] == 'on')
                    $delete_payment = 1;
                else
                    $delete_payment = 0;
                
                if(isset($data['Exchangesysrole']['delete_credit_note']) && $data['Exchangesysrole']['delete_credit_note'] == 'on')
                    $delete_credit_note = 1;
                else
                    $delete_credit_note = 0;
                
                if(isset($data['Exchangesysrole']['delete_debit_note']) && $data['Exchangesysrole']['delete_debit_note'] == 'on')
                    $delete_debit_note = 1;
                else
                    $delete_debit_note = 0;
                
                if(isset($data['Exchangesysrole']['reset_balance']) && $data['Exchangesysrole']['reset_balance'] == 'on')
                    $reset_balance = 1;
                else
                    $reset_balance = 0;
                
                if(isset($data['Exchangesysrole']['modify_credit_limit']) && $data['Exchangesysrole']['modify_credit_limit'] == 'on')
                    $modify_credit_limit = 1;
                else
                    $modify_credit_limit = 0;
                
                if(isset($data['Exchangesysrole']['modify_min_profit']) && $data['Exchangesysrole']['modify_min_profit'] == 'on')
                    $modify_min_profit = 1;
                else
                    $modify_min_profit = 0;
                
		$role_id=$this->getkeyByPOST('role_id', $post_arr['data']['Exchangesysrole']);
		if ($this->check_name($role_id, $role_name) == 0)
		{
			if($role_id!=''){
                                 $this->logging(2, 'Role', "Role Name:{$role_name}");
				 $data['Exchangesysrole']['role_id']=$role_id;
				 
				// $this->save ( $data['Exchangesysrolepri']);
//				 $this->query("update exchange_sys_role set role_name='$role_name', view_all = {$view_all}, delete_invoice = $delete_invoice,
//                                 delete_payment = $delete_payment, delete_credit_note = $delete_credit_note, delete_debit_note = $delete_debit_note, reset_balance = $reset_balance, modify_credit_limit = $modify_credit_limit,
//                                 modify_min_profit = $modify_min_profit where role_id=$role_id");
				 //更新角色
                        }else{
                                $this->logging(0, 'Role', "Role Name:{$role_name}");
				//$this->save ( $data ['Exchangesysrole'] );//添加角色
//				$this->query("insert into exchange_sys_role (role_name,type) values ('$role_name',{$role_type})");
//				$role_info = $this->query("select * from exchange_sys_role where role_name = '$role_name' limit 1");
//				$role_id = !empty($role_info) ? $role_info[0][0]['role_id'] : 0;
				
			}
		}
		return $role_id;
	
	}

/**
 * 添加/更新Role and Role privilege

 */
	function saveOrUpdate_rolepri($data, $post_arr){

		$role_id=$this->saveOrUpdate_role($data, $post_arr);
	
		if($role_id!=''){
			 //var_dump($data['Exchangesysrolepri']);
			 //更新角色
//			 $this->query("delete from exchange_sys_role_pri where role_id = {$role_id}");
                        
			if (!empty($data['Exchangesysrolepri']))
			 {
			 	
			 	foreach ($data['Exchangesysrolepri'] as $k=>$v)
			 	{			 	
			 		if (!empty($v))
			 		{
			 			$data_rolepri = array();
                                                //$data_rolepri['Sysrolepri']['type'] = intval($v['type']);
				 		$data_rolepri['Sysrolepri']['role_id'] = intval($role_id);
				 		$data_rolepri['Sysrolepri']['pri_name'] = "'" . $k . "'";
                                                $data_rolepri['Sysrolepri']['pri_id'] = intval($v['pri_id']);
				 		$data_rolepri['Sysrolepri']['model_r'] = empty($v['model_r']) ? "'f'" : "'t'";
				 		$data_rolepri['Sysrolepri']['model_w'] = empty($v['model_w']) ? "'f'" : "'t'";
				 		$data_rolepri['Sysrolepri']['model_x'] = empty($v['model_x']) ? "'f'" : "'t'";
				 		//$this->save($data_rolepri);
                                                //var_dump($v);
				 		$values = implode(", ", $data_rolepri['Sysrolepri']);
//				 		$this->query("insert into exchange_sys_role_pri (role_id, pri_name, pri_id, model_r, model_w, model_x) values (" . $values . ")");
			 		
                                                //exit;
                                        }
			 		
			 	}
			 }
		}
		
	return $role_id;
	
	}	

	
/**
 * 删除角色
 * @param unknown_type $id
 *   @return 返回正在使用的用户量
 */	
function del($role_id,$type){
	
                if($type == 'exchange'){
                    $table_name = "order_user";
                }else if($type == 'agent'){
                    $table_name = "agent_client";
                }else if($type == 'partition'){
                    $table_name = "exchange_par_account";
                }   
    
	$list=$this->query("select count(1)  from  {$table_name} where   role_id=$role_id");
	if(empty($list[0][0]['count'])){
//	$this->query("delete from exchange_sys_role_pri where role_id=$role_id");
//	$this->query("delete from exchange_sys_role where role_id=$role_id");
	return $list[0][0]['count'];
	}else{
	return $list[0][0]['count'];
	}
}	




/**
 * 普通查询
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function findAll($currPage=1,$pageSize=10,$order='',$type){
		if(!empty($order))
		{
			$order='order by	'.$order;
		}else{
		    $order="order by role_name asc";
		}
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();		
//	 	$totalrecords = $this->query("select count(role_id) as c from exchange_sys_role where exchange_sys_role.type = {$type}  ");
	 
//		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
                $table_name = '';
                
                if($type == 0){
                    $table_name = "order_user";
                }else if($type == 1){
                    $table_name = "agent_client";
                }else if($type == 2){
                    $table_name = "exchange_par_account";
                }   
                
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
//		$sql = "select exchange_sys_role.*, (select count(1) from {$table_name} where role_id = exchange_sys_role.role_id) as role_users
//                from exchange_sys_role where exchange_sys_role.type = {$type}  $order	limit '$pageSize' offset '$offset'";
//		$results = $this->query($sql);
		
//		$page->setDataArray($results);
		return $page;
	}	
	
	
	
	
	
	/**
	 * 模糊查询
	 * @param unknown_type $condition
	 * @param unknown_type $currPage
	 * @param unknown_type $pageSize
	 */
function likequery($key,$currPage=1,$pageSize=10,$type){
	
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(role_id) as c
	 	from exchange_sys_role    
	 	 where exchange_sys_role.type = {$type} and role_name   ilike $condition");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
                
                $table_name = '';
                
                if($type == 0){
                    $table_name = "order_user";
                }else if($type == 1){
                    $table_name = "agent_client";
                }else if($type == 2){
                    $table_name = "exchange_par_account";
                }                
                
                
		$sql = "select exchange_sys_role.*,a.role_cnt
		    from  exchange_sys_role 
left join (select count(1)as  role_cnt,role_id from {$table_name}    group by  role_id) a   on  a.role_id=exchange_sys_role.role_id

	 	 where exchange_sys_role.type = {$type} and role_name   like $condition 
	

	order by exchange_sys_role.role_id desc	limit '$pageSize' offset '$offset'";
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
			$role_pri = $this->query("select * from exchange_sys_role_pri where role_id = {$role_id}");
			if (!empty($role_pri))
			{
				foreach ($role_pri as $k=>$v)
				{
					$return[$v[0]['pri_name']] = $v[0];
				}
			}
		}
		return $return;
	}

}
