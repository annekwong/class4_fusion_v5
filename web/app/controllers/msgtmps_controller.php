<?php
	class MsgtmpsController extends AppController{
		var $name = 'Msgtmps';
		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
	}		
		
		/**
		 * 查看
		 */
		public function view(){
			if (!empty($this->params['form'])){
				if ($this->Msgtmp->update_tmp($this->params['form'])){
					$this->Msgtmp->create_json_array('',201,__('setsuc',true));
				} else {
					$this->Msgtmp->create_json_array('',101,__('setfail',true));
				}
				$this->set('m',Msgtmp::set_validator());
			} else {
				$tmplates = $this->Msgtmp->view_content();
				$this->set('tmp_bal',$tmplates[0]);
				$this->set('tmp_findp',$tmplates[1]);
				$this->set('tmp_reg',$tmplates[2]);
			}
		}
	} 
?>