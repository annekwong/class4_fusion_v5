<?php
class CdrReportsController extends CdrAppController{
	var $name = 'CdrReports';
	var $uses = array();
	var $helpers = array('Cdr.AppCdrReports');
 function beforeFilter (){
 				$this->checkSession ( "login_type" );//核查用户身份
     	parent::beforeFilter();
 }
	
	
	function index(){

	}
}
