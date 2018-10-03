<?php
class RefillsController extends AppController{
	var $name = 'Refills';
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	public function index(){
		$sql_platform = "select payment_platform_new_id,platform_name 
							from payment_platform_new 
							where status = true";
		$supports_platform = $this->Refill->query($sql_platform);
		$sql_trace = "select trace_name,img from payment_trace 
											where payment_platform_new_id = {$supports_platform[0][0]['payment_platform_new_id']}";
		$this->set('supports_platform',$supports_platform);
		$this->set('supports_traces',$this->Refill->query($sql_trace));
	}
	public function next($type){
		$this->set('type',$type);
	}
}