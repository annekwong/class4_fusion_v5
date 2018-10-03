<?php
	class MsginterfacesController extends AppController{
		var $name = 'Msginterfaces';
		
		public function view(){
			$this->set('msginterfaces',$this->Msginterface->getAll());
		}
			//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_msginterface');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
		public function add(){
			Configure::write('debug',0);
			$qs = $this->Msginterface->add();
			echo $qs;
		}
		
		public function update(){
			Configure::write('debug',0);
			$qs = $this->Msginterface->update();
			echo $qs;
		}
		
		public function del_interface($id){
			if ($this->Msginterface->del($id)){
				$this->Msginterface->create_json_array('',201,__('del_suc',true));
			} else {
				$this->Msginterface->create_json_array('',101,__('del_fail',true));
			}
			$this->Session->write('m',Msginterface::set_validator());
			$this->redirect('/msginterfaces/view');
		}
		
		public function active($id){
			if ($this->Msginterface->active($id)){
				$this->Msginterface->create_json_array('',201,__('manipulated_suc',true));
			} else {
				$this->Msginterface->create_json_array('',101,__('manipulated_fail',true));
			}
			$this->Session->write('m',Msginterface::set_validator());
			$this->redirect('/msginterfaces/view');
		}
	} 
?>