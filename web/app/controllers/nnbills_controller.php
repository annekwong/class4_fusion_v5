<?php
class NnbillsController extends AppController{
	var $name = 'Nnbills';
	var $uses = array();
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	function send(){
		$this->set('amount',$this->params['form']['amount']);
	}
	
	function receive(){}
	
	function show(){}
	
	function index(){}
}