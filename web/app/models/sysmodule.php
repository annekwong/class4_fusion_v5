<?php
class Sysmodule extends AppModel{
	var $name = 'Sysmodule';
	var $useTable = 'sys_module';
	var $primaryKey = 'id';
	
	public function ListModule($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0, $order_arr=array())
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		/*if ($search_type == 1)
		{
			if (!empty($search_arr['action_type']))
			{
				$sql_where .= " and  action_type = ".intval($search_arr['action_type']);
			}
			if (!empty($search_arr['status']))
			{
				$sql_where .= " and  exchange_finance.status = ".intval($search_arr['status']);
			}
			if (!empty($search_arr['descript']))
			{
				$sql_where .= " and descript like '%".addslashes($search_arr['descript'])."%'";
			}
			if (!empty($search_arr['start_date']))
			{
				$sql_where .= " and  action_time >= '".addslashes($search_arr['start_date'])."'";
			}
			if (!empty($search_arr['end_date']))
			{
				$sql_where .= " and  action_time <= '".addslashes($search_arr['end_date'])."'";
			}
		}
		else
		{
			if (!empty($search_arr['search']))
			{
				$sql_where .= " and  (action_number ilike '%".addslashes($search_arr['search'])."%' or descript like '%".addslashes($search_arr['search'])."%' or client.name ilike '%".addslashes($search_arr['search'])."%')";
			}
		}
		*/
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}
		
		$sql = "select count(id) as c from sys_module ".$sql_where;
		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
        $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from sys_module where 1=1".$sql_where.$sql_order;	
		$sql .= " limit '$pageSize' offset '$offset'";

		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}

	function saveOrUpdate($data,$post_arr){
        $rst=$this->saveOrUpdate_module($data,$post_arr);//添加或者更新
	  	 return $rst;
	  }


	function saveOrUpdate_module($data,$post_arr){
	    $module_id=$this->getkeyByPOST('module_id',$post_arr);
        if(empty($module_id) && isset($post_arr['data']['Sysmodule']['id']))
            $module_id = base64_decode($post_arr['data']['Sysmodule']['id']);

        if($module_id!=''){
             $data['Sysmodule']['id']=$module_id;
             $module_name=$data ['Sysmodule']['module_name'];
             $order_num=$data ['Sysmodule']['order_num'];

             $rst = $this->query("update sys_module set module_name='$module_name',order_num=$order_num  where id=$module_id");

        }else{
            $this->query("select setval('sys_module_id_seq', max(id)) from sys_module; ");
            $rst = $this->save ( $data ['Sysmodule'] );//添加角色
            $module_id = $this->getlastinsertId ();

        }

        //$this->save_privilege($module_id,$post_arr);//添加权限

        return $rst;
	
	}

/*
function save_privilege($module_id,$post_arr){
	$module_name = $post_arr['data']['Sysmodule']['module_name'];
	$this->query("insert into sys_module (id,module_name) values($module_id,$module_name");
}
*/

function del($module_id){
	
	//$list=$this->query("select count(*)  from  sys_module where  id=$module_id");
	//if(empty($list[0][0]['count'])){
		$this->query("delete from sys_module where id=$module_id");
		//return $list[0][0]['count'];
	//}else{
	//	return $list[0][0]['count'];
	//}
}

/**
*查看子模块
*/

public function ListSubModule($module_id,$currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0, $order_arr=array())
	{
		require_once 'MyPage.php';
                
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		/*if ($search_type == 1)
		{
			if (!empty($search_arr['action_type']))
			{
				$sql_where .= " and  action_type = ".intval($search_arr['action_type']);
			}
			if (!empty($search_arr['status']))
			{
				$sql_where .= " and  exchange_finance.status = ".intval($search_arr['status']);
			}
			if (!empty($search_arr['descript']))
			{
				$sql_where .= " and descript like '%".addslashes($search_arr['descript'])."%'";
			}
			if (!empty($search_arr['start_date']))
			{
				$sql_where .= " and  action_time >= '".addslashes($search_arr['start_date'])."'";
			}
			if (!empty($search_arr['end_date']))
			{
				$sql_where .= " and  action_time <= '".addslashes($search_arr['end_date'])."'";
			}
		}
		else
		{
			if (!empty($search_arr['search']))
			{
				$sql_where .= " and  (action_number ilike '%".addslashes($search_arr['search'])."%' or descript like '%".addslashes($search_arr['search'])."%' or client.name ilike '%".addslashes($search_arr['search'])."%')";
			}
		}
		*/
		$sql_where.=" module_id=$module_id";
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}
		
		$sql = "select count(id) as c from sys_pri where ".$sql_where;
		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
        $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from sys_pri where 1=1 and ".$sql_where.$sql_order;	
		$sql .= " limit '$pageSize' offset '$offset'";

		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	function saveOrUpdateSubModule($data,$post_arr){
		//pr($post_arr); exit();
	  	 $id=$this->saveOrUpdate_submodule($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }


	function saveOrUpdate_submodule($data,$post_arr){

	$id=$this->getkeyByPOST('id',$post_arr);

	if($id!=''){
		 $data['Sysmodule']['id']=$id;
		 $pri_name=$data ['Sysmodule']['pri_name'];

		 $this->query("update sys_pri set pri_name='$pri_name'  where id=$id");
		 
	}else{
			//pr($data ['Sysmodule']);exit();
		$this->save ( $data ['Sysmodule'] );//添加角色
		
		$id = $this->getlastinsertId ();
		
	}
	//$this->save_privilege($module_id,$post_arr);//添加权限
	
return $id;
	
	}
	
	function del_submodule($id){
	
	//$list=$this->query("select count(*)  from  sys_module where  id=$module_id");
	//if(empty($list[0][0]['count'])){
		$this->query("delete from sys_pri where id=$id");
		//return $list[0][0]['count'];
	//}else{
	//	return $list[0][0]['count'];
	//}
}
}
?>