<?php
	class SchedulersController extends AppController{
	 	var $name = 'Schedulers';
		var $uses = array();

		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
	}		
		
		public function schedulers_list(){
			$this->pageTitle="Switch/Task Schedulers";
			$this->loadModel('Cdr');
			$this->set('schedulers',$this->Cdr->query("select * from task_manage"));
		}
		
		public function status ($id,$value){
			$this->loadModel('Cdr');
			$qs = $this->Cdr->query("update task_manage set active = $value where id = $id");
			if (count($qs) == 0){
				$this->Cdr->create_json_array('',201,__('manipulated_suc',true));
			} else {
				$this->Cdr->create_json_array('',101,__('manipulated_fail',true));
			}
			$this->Session->write('m',Cdr::set_validator());
			$this->redirect('/schedulers/schedulers_list');
		}
		
		public function edit(){
			Configure::write('debug',0);
			$active = $_REQUEST['active'];
			$run_type = $_REQUEST['run_type'];
			$run_interval = $_REQUEST['run_interval'];
			$once_or_every = $_REQUEST['once_or_every'];
			$id = $_REQUEST['id'];
			
			$this->loadModel('Cdr');
			$qs = $this->Cdr->query("update task_manage set active = $active,
															run_type = $run_type,run_interval=$run_interval,
															once_or_every=$once_or_every
															where id = $id");
			if (count($qs) == 0){echo 'true';}else{echo 'false';};
		}
	 } 
?>