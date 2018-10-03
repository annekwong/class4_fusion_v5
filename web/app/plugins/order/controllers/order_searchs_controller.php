<?php
class OrderSearchsController extends OrderAppController{
	var $name = 'OrderSearchs';
	var $uses = array();
	var $helpers = array('Order.AppOrderSearchs');
	
	
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();
	}
	function index(){

	}
}
