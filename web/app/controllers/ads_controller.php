<?php
class AdsController extends AppController {
	var $name = 'Ads';

	
	
	
	// sad
	/*
	 * 分页查询客户组
	 */
	public function ads() {
		
		if (! empty ( $this->params ['form'] )) {
			$f = $this->params ['form'];
			if ($this->Ad->save ( $f )) {
				$this->Ad->create_json_array ( '', 201, __ ( 'add_suc', true ) );
			} else {
				$this->Ad->create_json_array ( '', 101, __ ( 'add_fail', true ) );
			}
			$this->set ( 'm', Ad::set_validator () );
		}
		
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
		
		$results = $this->Ad->getAllAds ( $currPage, $pageSize, $search );
		
		$this->set ( 'p', $results );
	}
	
	public function del_ad($id){
		if ($id == 'all'){
			$id = "select system_ads_id from system_ads";
		}
		if ($id == 'selected'){
			$id = $_REQUEST['ids'];
		}
		if($this->Ad->del($id)){
			$this->Ad->create_json_array('',201,__('del_suc',true));
		} else {
			$this->Ad->create_json_array('',101,__('del_fail',true));
		}
		$this->Session->write('m',Ad::set_validator());
		$this->redirect('/ads/ads');
	}
	
	public function write_session(){
		Configure::write('debug',0);
		$this->Session->write('ads','');
	}
}
?>