<?php
class Role extends AppModel{
	var $name = 'Role';
	var $useTable = "role";
	var $primaryKey = "role_id";
	function batchupdate($data){
		$old_role_id=$data['Role']['old_role_id'];
		$new_role_id=$data['Role']['new_role_id'];
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
		function findRole(){
		$r= $this->query("select role_id ,role_name from role");
		$size=count($r);		
		$l=array();
   for ($i=0;$i<$size ;$i++){
   	$key=$r[$i][0]['role_id'];
     $l[$key]=$r[$i][0]['role_name'];
      }
     return $l;
	}

	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_role($data,$post_arr){
  $error_flag=false;//错误信息标志
	
	  $role_id=$this->getkeyByPOST('role_id',$post_arr);
	  	$name=$data['Role']['role_name'];

    
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
	($role_id=='')?$sql="select count(*) from role where role_name=$name ":
	$sql="select count(*) from role where role_name=$name  and role_id<>$role_id";
	$c= $this->query($sql);
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

	$role_id=$this->getkeyByPOST('role_id',$post_arr);

	if($role_id!=''){
		 $data['Role']['role_id']=$role_id;
		 $role_name=$data ['Role']['role_name'];
		// $this->save ( $data ['Role']);
		 $this->query("update role set role_name='$role_name'  where role_id=$role_id");
		 //更新角色
	}else{
		$this->save ( $data ['Role'] );//添加角色
		$role_id = $this->getlastinsertId ();
		
	}
	$this->save_privilege($role_id,$post_arr);//添加权限
	
return $role_id;
	
	}

	

		/**
	 * 给某个新建的角色添加权限
	 * @param unknown_type $res_id
	 * @param unknown_type $post_arr
	 */
function save_privilege($role_id,$post_arr){
	    $system_function_id_arr = $post_arr['system_function_id'];
	    $readable_arr = $post_arr['readable'];
	    $writable_arr = $post_arr['writable'];
	    $executable_arr =$post_arr['executable'];
	    $func_type_arr =$post_arr['func_type'];
			$all_size=intval(count($system_function_id_arr));//总记录
     $this->query("delete from role_privilege  where role_id=$role_id");//删除原有权限
				for($i = 0; $i < $all_size; $i ++) {
					
					//路由配置
/*					if($func_type_arr[$i]==5){
					 if($executable_arr[$i]==true){
					 $readable_arr[$i]='true';
					 }else{
					 	 $readable_arr[$i]='false';
					 }
					}*/
					$this->query("insert into role_privilege (role_id,system_function_id,writable,readable,executable)
					  values($role_id,$system_function_id_arr[$i],$writable_arr[$i],$readable_arr[$i],$executable_arr[$i])");	
					}
									
				
					
}
	/**
	 * 查找所有系统功能的名字
	 * @param unknown_type $client_id
	 */
	function findAllSysFuncName(){
		$type=$_SESSION['login_type'];//登录身份(1,管理员2.代理商)
		if($type==1){
			$sql="select system_function_id ,func_name from system_function   where  develop_status>2  order by func_type";
		}else{
			$role_id=$_SESSION['sst_role_id'];
			$sql="select p.system_function_id, f.func_name  from role_privilege   as p
   			left  join (select  system_function_id,func_name ,func_type from system_function)f  on p.system_function_id=f.system_function_id
  			where  p.role_id=$role_id   and p.readable=true order by f.func_type
			";
		}
		$r= $this->query($sql);
		$size=count($r);		
		$l=array();
	   	for ($i=0;$i<$size ;$i++){
	   		$key=$r[$i][0]['system_function_id'];
	   	    $value=$r[$i][0]['func_name']; 
	     	$l[$key]=__($value,true);
	    }
	    return $l;
	}	
	function findAllSysFunc(){
		$type=$_SESSION['login_type'];//登录身份(1,管理员2.代理商)
		if($type==1){
			$sql="select system_function_id ,func_name,func_type from system_function  where develop_status>2  order by func_type";
		}else{
			$role_id=$_SESSION['sst_role_id'];
			$sql="select p.system_function_id, f.func_name ,f.func_type from role_privilege   as p
   			left  join (select  system_function_id,func_name,func_type from system_function)f  on p.system_function_id=f.system_function_id
   			where  p.role_id=$role_id   and p.readable=true order by f.func_type
			";
		}
		$r=$this->query($sql);
		return $r;
	}
	
	/**
	 * 查看某个角色的权限
	 * @param unknown_type $role_id
	 */
function findRoleAllsys_func($role_id){

return 
/*$this->query("select role_privilege_id, a.system_function_id  ,role_id,writable,readable,executable,b.func_name,b.is_exe  from 
  role_privilege  as  a
left join (select   system_function_id,func_name ,is_exe,func_type from  system_function )as b  on  a.system_function_id=b.system_function_id  
  
  where role_id=$role_id   order by func_type");*/


$this->query("select a.role_privilege_id, b.system_function_id  ,a.role_id,a.writable,a.readable,a.executable,b.func_name,b.is_exe,func_type  from 
 
  system_function  as b
  
  left join (select   role_privilege_id,system_function_id ,role_id,writable,readable,executable from  role_privilege   where role_id=$role_id)
  as a  on  a.system_function_id=b.system_function_id  

    order by func_type");


}	
	

	

	
	

/**
 * 删除角色
 * @param unknown_type $id
 *   @return 返回正在使用的用户量
 */	
function del($role_id){
	
	$list=$this->query("select count(*)  from  users where   role_id=$role_id");
	if(empty($list[0][0]['count'])){
	$this->query("delete from role_privilege where role_id=$role_id");
	$this->query("delete from role where role_id=$role_id");
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
	public function findAll($currPage=1,$pageSize=10,$order=''){
		if(!empty($order))
		{
			$order='order by	'.$order;
		}else{
		    $order="order by role_name asc";
		}
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();		
	 	$totalrecords = $this->query("select count(role_id) as c from role  where role_id<>0");
	 
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select role.role_id,role_name,view_pw ,active,a.role_cnt
		  			  from  role 
							left join (select count(*)as  role_cnt,role_id from users  group by  role_id) a   on  a.role_id=role.role_id 
							where role.role_id<>0
		$order	limit '$pageSize' offset '$offset'";		
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
function likequery($key,$currPage=1,$pageSize=10){
	
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(role_id) as c
	 	from role    
	 	 where role_name   ilike $condition");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
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
	
	
	
	/**
 * 高级搜索
 * @param unknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function Advancedquery($data,$currPage=1,$pageSize=10){
		
		//解析搜索条件
		$condition="where   ";
		$i=0;
		$len=intval(count($data['Blocklist']));
		
		
		foreach ($data['Blocklist'] as $key=>$value){

			//判断是否存在搜索条件
			if($value==''){
		  	continue;
			}
		$tmp= "resource_block.".$key."='".$value."'  and   "; 
		$condition=$condition.$tmp;
		$i++;
		}

		  
$where=substr($condition,0,strrpos($condition, 'a'));
		//pr($where);
		
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(res_block_id) as c from resource_block  $where");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		
		    $where
	order by res_block_id  	limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	





}
