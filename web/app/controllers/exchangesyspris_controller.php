<?php
class ExchangesysprisController extends AppController{
	var $name = 'Exchangesyspris';
	var $uses=Array('Exchangesyspri');
	var $components = array('RequestHandler');
	function  index()
	{
		$this->redirect('view_syspri');
	}
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();
	}
	
	/**
	 *  获取模块数组
	 */
	public function getModules()
	{
		$return = array();
		
//		$module_list = $this->Exchangesyspri->query("select * from exchange_sys_module");
		if (!empty($module_list))
		{
			foreach ($module_list as $k=>$v)
			{
				$return[$v[0]['id']] = $v[0]['module_name'];
			}
		}
		
		return $return;
	}
	
/**
	*查看子模块
	*/
	function view_syspri($module_id=null){
		$module_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
		//echo $module_id;exit();
		$this->pageTitle="Exchange Manage/Modules";
		
			$currPage = 1;
			$pageSize = 100;
			$search_arr = array();
			$order_arr = array();
			if (!empty($_REQUEST['order_by']))
			{
				$order_by = explode("-", $_REQUEST['order_by']);
				$order_arr[$order_by[0]] = $order_by[1];
			}
			
			if (!empty($_REQUEST['search']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';				
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				$search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
				$search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
				$search_arr['action_type'] = !empty($_REQUEST['tran_type']) ? intval($_REQUEST['tran_type']) : 0;
				$search_arr['status'] = !empty($_REQUEST['tran_status']) ? intval($_REQUEST['tran_status']) : 0;
				$search_arr['descript'] = !empty($_REQUEST['descript']) ? $_REQUEST['descript'] : '';
			}
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			
                        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;
                        
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
		
		//echo $this->params['pass'][0];exit();
		$results = $this->Exchangesyspri->ListSubModule ($module_id,$currPage, $pageSize, $search_arr, $search_type, $order_arr);
		$this->set ( 'p', $results);		
		
	}
	
//初始化查询参数
	function init_query() {
		$this->set ( 'modules',  $this->getModules());
	}
	
	function add_syspri($module_id=null){
		$this->pageTitle = "Add module";
		$this->init_query ();
		
		$module_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
		
		if (! empty ($this->data ['Exchangesyspri'] )) {
			//pr($_POST); exit();
			$return = $this->Exchangesyspri->saveOrUpdateSubModule ($this->data, $_POST); //保存
			if (!empty ($return)) {
				$this->set ( 'post', $this->data);
				$this->Exchangesyspri->create_json_array('',201,'Add successfully');
				$this->Session->write('m',Exchangesyspri::set_validator());
				$this->redirect ( array ('controller' => 'exchangesyspris', 'action' => 'view_syspri'.'/'.$this->data ['Exchangesyspri']['module_id'] ) ); // succ
			} else {
			 	$this->Exchangesyspri->create_json_array('',101,'Add fail');
				
			}
		}
	}
	
	function  edit_syspri($id=null){
		$this->pageTitle = "Edit module";
		$this->init_query ();
		$id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
		//echo $id;exit();
		$this->_catch_exception_msg(array($this,'_add_syspri_impl'),array('id' => $id));
		$this->_render_syspri_save_options();
		$this->Session->write('m',Exchangesyspri::set_validator());
	}
	
	function _add_syspri_impl($params=array()){
		
		#post
		
		if ($this->RequestHandler->isPost())
		{		
				$this->_create_or_update_syspri_data ($this->params['form']);
		}
		#get
		 else
		  {
						if(isset($params['id']) && !empty($params['id']))
						{
							//pr($this->Exchangesyspri->find("first", Array('conditions'=>array('Syspri.id'=>$params['id']))));exit();
								$this->data = $this->Exchangesyspri->find("first", Array('conditions'=>array('Exchangesyspri.id'=>$this->params['pass'][1], 'Exchangesyspri.module_id'=>$this->params['pass'][0])));
								if(empty($this->data))
								{
											throw new Exception("Permission denied");
								}
								else
								{
									$this->set('p', $this->data['Exchangesyspri']);
								}
						}
						else
						{
							//void
						}
						
			}
	}
	
	function del_syspri()
	{
            $this->autoRender = false;
            $this->autoLayout = false;
		if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
		{
			$this->redirect_denied();
		}
		$id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
		if (!empty($id))
		{
//			$this->Exchangesyspri->query("delete from exchange_sys_role_pri where pri_id  = " . intval($id) );
//			$this->Exchangesyspri->query("delete from exchange_sys_pri where id = " . intval($id));
		}
		$this->redirect ( array ('controller' => 'exchangesyspris', 'action' => 'view_syspri'.'/'.$this->params['pass'][0] ) ); 
	}
	
	function _create_or_update_syspri_data($params=array()) {			#update		
		//var_dump($params);
		if(isset($params['id']) && !empty($params['id']))
		{
							$id = ( int ) $params ['id'];

							$this->data ['Module'] ['id'] = $id;
								
							if($this->Module->save ($this->data ))
							{
								
								$this->Module->create_json_array('',201,'Module , Edit successfully');
						   $this->xredirect('/exchangesysmodules/view_syspri');
							}
		}
		# add
		else
		{
				//void
		}
	}
	
	function _render_syspri_save_options()
	{
		$this->loadModel('Exchangesyspri');
		
		$this->set('SyspriList', $this->Exchangesyspri->find('all'));
	}
	
}
?>
