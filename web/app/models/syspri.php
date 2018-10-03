<?php
class Syspri extends AppModel{
	var $name = 'Syspri';
	var $useTable = 'sys_pri';
	var $primaryKey = 'id';

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
		$sql_where.=" sys_pri.module_id=$module_id";
		$sql_where.=" and sys_module.id=$module_id";
		$sql_order = '';
		if (!empty($order_arr))
		{
			$sql_order = ' order by ';

			$order_sql_arr = array();
			foreach ($order_arr as $k=>$v)
			{
				$order_sql_arr[] = $k . ' ' . $v;
			}
			$sql_order .= implode(',',$order_sql_arr);
		}

		$sql = "select count(sys_pri.id) as c from sys_pri,sys_module where ".$sql_where;
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
		$sql = "select sys_pri.*,sys_module.module_name as module_name from sys_pri,sys_module where 1=1 and ".$sql_where.$sql_order;
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;exit();
		$results = $this->query($sql);

		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}


	function saveOrUpdateSubModule($data,$post_arr){

		$id=$this->getkeyByPOST('id',$post_arr['data']['Syspri']);
		$data['Syspri']['pri_url'] = addslashes($data['Syspri']['pri_url']);
		if($id!=''){
			$data['Syspri']['id']=$id;
			//$pri_name=$data ['Syspri']['pri_name'];
			$this->save ( $data );

			//$this->query("update sys_pri set pri_name='$pri_name'  where id=$id");

		}else{
			//pr($data ['Sysmodule']);exit();
			$this->query("select setval('sys_pri_id_seq', max(id)) from sys_pri; ");
			$this->save ( $data );//添加角色

			$id = $this->getlastinsertId ();

		}
		//$this->save_privilege($module_id,$post_arr);//添加权限

		return $id;

	}


	public function judge_module_name($pri_name)
	{
		$result = $this->query("SELECT id FROM sys_pri WHERE pri_name = '{$pri_name}'");
		if(isset($result[0][0]['id']))
		{
			return FALSE;
		}
		return TRUE;
	}
}
?>