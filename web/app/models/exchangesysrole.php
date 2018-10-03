<?php
class Exchangesysrole extends AppModel{
//	var $name = 'Exchangesysrole';
	var $useTable = false;
//	var $primaryKey = 'role_id';
	
	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_role($data,$post_arr){
  $error_flag=false;//错误信息标志
	
	  $role_id=$data['Exchangesysrole']['role_id'];
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
	
	
	$name="'".$name."'";
//	($role_id=='')?$sql="select count(*) from exchange_sys_role where role_name=$name ":
//	$sql="select count(*) from exchange_sys_role where role_name=$name  and role_id<>$role_id";
//	$c= $this->query($sql);
	if(empty($c)){
	return 0;
	}else{
	return $c[0][0]['count'];}
}	




/**
 * 添加Client or 更新Client
 * @param unknown_type $data
 * @param unknown_type $post_arr
 * @return 
 */

	function saveOrUpdateSysrole($data,$post_arr){
		$msgs=$this->validate_role($data,$post_arr);//验证
		if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $role_id=$this->saveOrUpdate_sysrole($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }
	  }


	function saveOrUpdate_sysrole($data,$post_arr){
	$role_id=$this->getkeyByPOST('role_id',$post_arr['data']['Exchangesysrole']);
	//var_dump($role_id);exit;
	if($role_id!=''){
		 $data['Exchangesysrole']['role_id']=$role_id;
		 $role_name=$data ['Exchangesysrole']['role_name'];
		 //$role_info=$data ['Exchangesysrole']['role_info'];
//		 $this->query("update exchange_sys_role set role_name='$role_name' where role_id=$role_id");
		 
	}else{
		$this->save ( $data ['Exchangesysrole'] );//添加角色
		$role_id = $this->getlastinsertId ();
		
	}
	//$this->save_privilege($module_id,$post_arr);//添加权限
	//echo $role_id;exit;
return $role_id;
	
	}

public function findSysrole($role_id)
{
	$return = array();
	
	$role_id = intval($role_id);
	if (!empty($role_id))
	{
//		$role_pri = $this->query("select * from exchange_sys_role where role_id = {$role_id}");
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
?>