<?php 
class SystemsController extends AppController {
	var $name = 'Systems';
	var $helpers = array();
	var $uses = array();
	var $components =array();
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	function trouble_shoot(){
		  $this->pageTitle="Switch/Trouble Shoot";
	}
}
?>
