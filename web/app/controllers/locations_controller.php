<?php
class LocationsController extends AppController {
	var $name = 'Locations';
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	public function reg_account() {
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		if (! empty ( $_REQUEST ['search'] )) {
			$search = $_REQUEST ['search'];
			$this->set ( 'search', $search );
		}
		
		$results = $this->Location->getAllLocations ( $currPage, $pageSize, $search );
		
		$this->set ( 'p', $results );
	}
}
?>