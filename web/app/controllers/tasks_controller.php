<?php
class TasksController extends AppController{
	var $name = 'Tasks';
	//var $helpers = array('javascript','html','AppAlerts');
	var $components = array('RequestHandler');	
	var $uses = array("Task");
function  index()
	{
		$this->redirect('view');
	}
	public function view()
	{
			$currPage = 1;
			$pageSize = 100;
			$search_arr = array();
			if (!empty($_REQUEST['searchkey']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				$search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
			}
			
//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
		$temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
		 
		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                
                $_SESSION['paging_row'] = $pageSize;
	
		$results = $this->Task->ListSchedule($currPage, $pageSize, $search_arr, $search_type);
		$this->set ( 'p', $results);
	}	
	
public function edit_task($id=null)
	{		
		if (!$_SESSION['role_menu']['Configuration']['tasks']['model_w'])
		{
			$this->redirect_denied();
		}
		$this->pageTitle = "Edit Task Schedule";
		$id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
		if (!empty($id))
		{
			$this->_catch_exception_msg(array('TasksController','_edit_task_impl'),array('id' => $id));
			$this->_render_task_save_options();
			$this->render('edit_task');
			$this->Session->write('m',Task::set_validator());
		}
		else
		{
			$this->redirect('view');
		}
	}
	
	function _edit_task_impl($params=array()){
		#post
		
		if ($this->RequestHandler->isPost())
		{
		//var_dump($this->params['form']);exit;
						$this->_create_or_update_task_data ($this->params['form']);
		}
		#get
		 else
		  {
						if(isset($params['id']) && !empty($params['id']))
						{
								$this->data = $this->Task->find("first",Array('conditions'=>array('Task.id'=>$params['id'])));
								if(empty($this->data))
								{
											throw new Exception("Permission denied");
								}
								else
								{
									$this->set('p', $this->data['Task']);
								}
						}
						else
						{
				
						}
					}
	}
	
		function _create_or_update_task_data($params=array()) {			#update
			
		if(isset($params['task_id']) && !empty($params['task_id']))
		{
							$id = ( int ) $params ['task_id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
							$this->data ['Task'] ['id'] = $id;							
							$this->data['Task']['flag'] == 1 ? $this->data['Task']['flag'] = true : false;
							
							//var_dump($this->data);exit;
							if($this->Task->save ($this->data ))
							{
								$this->Task->create_json_array('',201,'save success');
								$this->Session->write('m',Task::set_validator());
								$this->redirect ( array ('id' => $id ) );
							}
		}
		# add
		else
		{
							throw new Exception("Permission denied");
		}
	}
	
	function _render_task_save_options()
	{
		$this->loadModel('Task');
		$this->set('TaskList',$this->Task->find('all'));//,Array('fields'=>Array('id','name'))));
	}

}