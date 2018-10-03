<?php
class PrResourcesController extends PrAppController{
	var $name = 'PrResources';
	var $uses = array();
	var $helpers = array('Pr.AppPrResources');
function beforeFilter(){
	 $this->checkSession ( "login_type" );//核查用户身份
  	parent::beforeFilter();
}
	
	
	function index(){

	}
}
