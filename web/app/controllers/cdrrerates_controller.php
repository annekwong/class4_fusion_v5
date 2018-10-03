<?php
class CdrreratesController extends AppController{
	var $name = 'Cdrrerates';
	var $components = array('RequestHandler');	
	var $uses = array("Cdrrerate");

private function _getResourceHostArr()
	{
		$return[0] = 'ALL';
		$sql = "select resource_ip_id as id, ip from resource_ip";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['ip'];
		}
		return $return;
	}
	
	private function _getResourceNameArr()
	{
		$return[0] = 'ALL';
		$sql = "select resource_id as id, alias from resource";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['alias'];
		}
		return $return;
	}
	
	private function _getRouteNameArr()
	{
		$return = array();
		$sql = "select route_strategy_id as id, name from route_strategy";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['name'];
		}
		return $return;
	}
	
	private function _getProductNameArr()
	{
		$return = array();
		$sql = "select product_id as id, name from product";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['name'];
		}
		return $return;
	}
	
	private function _getResourceRouteInfoArr()
	{
		$sql = "select * from route";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['route_strategy_id']] = $v[0];
		}
		return $return;
	}
	
	private function _getRateTableNameArr()
	{
		$sql = "select * from rate_table";
		$results = $this->Cdrrerate->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['rate_table_id']] = $v[0]['name'];
		}
		return $return;
	}
	
	//-----------------------------------
	function view()
	{
		//var_dump($_REQUEST);
		if (1)//(!empty($_REQUEST['search']))
		{			
			$currPage = 1;
			$pageSize = 100;			
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
			$search_arr = array();
			$search_arr['start_time'] = isset($_REQUEST['cdr_start']) ? $_REQUEST['cdr_start'] : '';
			$search_arr['end_time'] = isset($_REQUEST['cdr_end']) ? $_REQUEST['cdr_end'] : '';
			$search_arr['orig_client_id'] = isset($_REQUEST['query']['orig_id_clients']) ? $_REQUEST['query']['orig_id_clients'] : '';
			$search_arr['term_client_id'] = isset($_REQUEST['query']['term_id_clients']) ? $_REQUEST['query']['term_id_clients'] : '';
			$search_arr['orig_res'] = isset($_REQUEST['orig_res']) ? $_REQUEST['orig_res'] : '';
			$search_arr['orig_host'] = isset($_REQUEST['orig_host']) ? $_REQUEST['orig_host'] : '';
			$search_arr['term_res'] = isset($_REQUEST['term_res']) ? $_REQUEST['term_res'] : '';
			$search_arr['term_host'] = isset($_REQUEST['term_host']) ? $_REQUEST['term_host'] : '';
			$search_arr['orig_rate_table'] = isset($_REQUEST['orig_rate_table']) ? $_REQUEST['orig_rate_table'] : '';
			$search_arr['term_rate_table'] = isset($_REQUEST['term_rate_table']) ? $_REQUEST['term_rate_table'] : '';
			$search_arr['rerate_mothod'] = isset($_REQUEST['rerate_mothod']) ? $_REQUEST['rerate_mothod'] : 1;
			$search_arr['output_mothod'] = isset($_REQUEST['output_mothod']) ? $_REQUEST['output_mothod'] : 'html';
			
			$result = $this->Cdrrerate->cdr_search($currPage,$pageSize,$search_arr);
			$this->set('p',$result);
		}
		$name_join_arr['resource'] = $this->_getResourceNameArr();
		$name_join_arr['host'] = $this->_getResourceHostArr();
		$name_join_arr['rate_table'] = $this->_getRateTableNameArr();
		$this->set('name_join_arr', $name_join_arr);
	}
	
	function add_reratecdr()
	{
		$this->pageTitle = "Add/Edit Rerate Cdr";
		$id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
		$this->_catch_exception_msg(array('CdrreratesController','_add_reratecdr_impl'),array('id' => $id));
		$this->_render_reratecdr_save_options();
		$this->render('add_reratecdr');
		$this->Session->write('m',Cdrrerate::set_validator());
	}
	
		function _add_reratecdr_impl($params=array()){
		#post
		
		if ($this->RequestHandler->isPost())
		{		
				$this->_create_or_update_reratecdr_data ($this->params['form']);
		}
		#get
		 else
		  {
		  			
						if(isset($params['id']) && !empty($params['id']))
						{
								$this->data = $this->Cdrrerate->find("first",Array('conditions'=>array('Cdrrerate.id'=>$params['id'])));
								if(empty($this->data))
								{
											throw new Exception("Permission denied");
								}
								else
								{
											$this->set('p', $this->data['Cdrrerate']);//pr($this->data['Action']);	
											$name_join_arr['resource'] = $this->_getResourceNameArr();
											$name_join_arr['host'] = $this->_getResourceHostArr();
											$name_join_arr['rate_table'] = $this->_getRateTableNameArr();
											$this->set('name_join_arr', $name_join_arr);								
								}
						}
						else
						{
				
						}
						
			}
	}
	
		function _create_or_update_reratecdr_data($params=array()) {			#update
		
		if(isset($params['cdr_id']) && !empty($params['cdr_id']))
		{
							$id = ( int ) $params ['cdr_id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
							$this->data ['Cdrrerate'] ['id'] = $id;
							
							
												
							if($this->Cdrrerate->save ($this->data ))
							{								
								$this->Cdrrerate->create_json_array('',201,'save success');
								$this->Session->write('m',Cdrrerate::set_validator());
								$this->redirect ( array ('id' => $id ) );
							}
		}
		# add
		else
		{
			throw new Exception("Permission denied");
//						if(!$this->check_form('')){
//								return;
//							}	
														
//							if($this->Cdrrerate->save ( $this->data ))
//							{
//								$id = $this->Cdrrerate->getlastinsertId ();
//								$this->Cdrrerate->create_json_array('',201,'save success');
//								$this->Session->write('m',Cdrrerate::set_validator());
//								$this->redirect ( array ('id' => $id ) );
//							}
		}
	}
	
	function _render_reratecdr_save_options()
	{
		$this->loadModel('Action');
		$this->set('ReratecdrList',$this->Cdrrerate->find('all'));//,Array('fields'=>Array('id','name'))));
	}
	
	//----------------rerate cdr
	function rerate_cdr()
	{
		//var_dump($_REQUEST);
		$new_orig_rate_table = isset($_REQUEST['orig_rate_table']) ? $_REQUEST['orig_rate_table'] : '';
		$new_term_rate_table = isset($_REQUEST['term_rate_table']) ? $_REQUEST['term_rate_table'] : '';
		if (!empty($_POST['sel_id']))
		{
			foreach ($_POST['sel_id'] as $cdr_id)
			{
				$this->Cdrrerate->rerate($cdr_id, $new_orig_rate_table, $new_term_rate_table);
			}
		}
		$this->Session->write('m',Cdrrerate::set_validator());
		$this->redirect('view');
	}
}
?>