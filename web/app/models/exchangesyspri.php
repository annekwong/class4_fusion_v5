<?php
class Exchangesyspri extends AppModel{
//	var $name = 'Exchangesyspri';
	var $useTable = false;
//	var $primaryKey = 'id';

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
//		$sql_where.=" exchange_sys_pri.module_id=$module_id";
//		$sql_where.=" and exchange_sys_module.id=$module_id";
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}
		
//		$sql = "select count(exchange_sys_pri.id) as c from exchange_sys_pri,exchange_sys_module where ".$sql_where;
//		$totalrecords = $this->query($sql);
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
//		$sql = "select exchange_sys_pri.*,exchange_sys_module.module_name as module_name from exchange_sys_pri,exchange_sys_module where 1=1 and ".$sql_where.$sql_order;
//		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;exit();
//		$results = $this->query($sql);
		
//		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	
	function saveOrUpdateSubModule($data,$post_arr){

		$id=$this->getkeyByPOST('id',$post_arr['data']['Exchangesyspri']);
		$data['Syspri']['pri_url'] = addslashes($data['Exchangesyspri']['pri_url']);
		if($id!=''){
			 $data['Syspri']['id']=$id;
			 //$pri_name=$data ['Syspri']['pri_name'];
				$this->save ( $data );
				
			 //$this->query("update exchange_sys_pri set pri_name='$pri_name'  where id=$id");
			 
		}else{
				//pr($data ['Sysmodule']);exit();
			$this->save ( $data );//添加角色
			
			$id = $this->getlastinsertId ();
			
		}
		//$this->save_privilege($module_id,$post_arr);//添加权限
		
		return $id;
	
	}
}
?>