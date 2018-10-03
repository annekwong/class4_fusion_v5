<?php
	class BonusesController extends AppController{
		var $name = 'Bonuses';
			//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_paymentpoint');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
		public function bonus_list(){
			$edit_id = null;
			if (!empty($_REQUEST['edit_id'])) {
				$edit_id = $_REQUEST['edit_id'];
				$this->set('edit_return',true);
			}
			$this->set('bonus',$this->Bonus->getAll());
		}
		
		public function add(){
			Configure::write('debug',0);
			$qs = $this->Bonus->add();
			echo $qs;
		}
		
		public function update(){
			Configure::write('debug',0);
			$qs = $this->Bonus->update();
			echo $qs;
		}
		
		public function del($id){
			if ($this->Bonus->del($id)) {
				$this->Bonus->create_json_array('',201,__('del_suc',true));
			} else {
				$this->Bonus->create_json_array('',101,__('del_fail',true));
			}
			$this->Session->write('m',Bonus::set_validator());
			$this->redirect('/msglimits/limit_list');
		}
	} 
?>