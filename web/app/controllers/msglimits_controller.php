<?php
	class MsglimitsController extends AppController{
		var $name = 'Msglimits';
		
		
		
			//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_msglimitrule');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
		
		public function limit_list(){
			$edit_id = null;
			if (!empty($_REQUEST['edit_id'])) {
				$edit_id = $_REQUEST['edit_id'];
				$this->set('edit_return',true);
			}
			$this->set('limits',$this->Msglimit->getLimits($edit_id));
		}
		
		public function add_limit(){
			Configure::write('debug',0);
			$qs = $this->Msglimit->add();
			echo $qs;
		}
		
		public function update_limit(){
			Configure::write('debug',0);
			$qs = $this->Msglimit->update();
			echo $qs;
		}
		
		public function del_limit($id){
			if ($this->Msglimit->del($id)) {
				$this->Msglimit->create_json_array('',201,__('del_suc',true));
			} else {
				$this->Msglimit->create_json_array('',101,__('del_fail',true));
			}
			$this->Session->write('m',Msglimit::set_validator());
			$this->redirect('/msglimits/limit_list');
		}
	} 
?>