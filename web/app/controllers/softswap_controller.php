<?php
class SoftswapController extends AppController{
	var $name = 'Softswap';
	var $helpers = array('javascript','html');
	var $uses=Array();
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	function index()
	{
		
	}
	function add()
	{
		
	}
	function delete($id)
	{
		
	}
	function deleteall()
	{
	
	}
}
	


?>
