<?php
	class ServicesController extends AppController{
		
		//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_locationPrefixes');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
		public function services_list(){
			$currPage = 1;
			$pageSize = 100;
			$search = null;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		if (!empty($_REQUEST['edit_id'])){
				$sql = "select *,(select name from reseller where reseller_id = value_add_service.reseller_id) as reseller from value_add_service
				 where service_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Service->query ( $sql );
			//分页信息
				require_once 'MyPage.php';
				$results = new MyPage ();
				$results->setTotalRecords ( 1 ); //总记录数
				$results->setCurrPage ( 1 ); //当前页
				$results->setPageSize ( 1 ); //页大小
				$results->setDataArray ( $result );
			$this->set('edit_return',true);
			} else {
		$reseller_id = $this->Session->read('sst_reseller_id');
		$results = $this->Service->getList ( $currPage, $pageSize,$search,$reseller_id);
			}
		
		$this->set ( 'p', $results );
		}
		
		public function add(){
			if (!empty($this->params['form'])){
				$f = $this->params['form'];
				$f['reseller_id'] = $this->Session->read('sst_reseller_id');
				$this->validate_service($f,'/services/add');
				
				if ($this->Service->save($f)) {
					$this->Service->create_json_array('',201,__('addservicesuc',true));
				}else{
					$this->Service->create_json_array('',101,__('addservicefail',true));
				}
				
				$this->Session->write('m',Service::set_validator());
				$this->redirect('/services/services_list');
			} else {
				$reseller_id = $this->Session->read('sst_reseller_id');
				$rs = $this->Service->query("select * from get_reseller($reseller_id)");
				//$this->set('r_reseller',$this->Service->set_reseller('reseller_id',$rs[0][0]['get_reseller']));
			}
		}
		
		public function edit($id){
			if (!empty($this->params['form'])){
				$f = $this->params['form'];
				$id = $f['service_id'];
				$this->validate_service($f,'/services/edit/'.$id);
				
				if ($this->Service->save($f)) {
					$this->Service->create_json_array('',201,__('editservicesuc',true));
				}else{
					$this->Service->create_json_array('',101,__('editservicefail',true));
				}
				
				$this->Session->write('m',Service::set_validator());
				$this->redirect('/services/services_list?edit_id='.$id);
			} else {
				$reseller_id = $this->Session->read('sst_reseller_id');
				$service = $this->Service->getbyid($id);
				$rs = $this->Service->query("select * from get_reseller($reseller_id)");
				//$this->set('r_reseller',$this->Service->set_reseller('reseller_id',$rs[0][0]['get_reseller'],$service[0][0]['reseller_id']));
				$this->set('service',$service);
			}
		}
		
		private function validate_service($f,$url){
			$has_error = false;
			$service_name = $f['service_name'];
			if (empty($service_name)) {
				$has_error = true;
				$this->Service->create_json_array('',101,__('enterservicename',true));
			} else {
				if (!preg_match('/^[_\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff]+$/',$service_name)){
					$has_error = true;
					$this->Service_create_json_array('',101,__('servicenameformat',true));
				}
			}
			
			$billing_time = $f['billing_time'];
			if (empty($billing_time)){
				$has_error = true;
				$this->Service->create_json_array('',101,__('billingtimenotnull',true));
			} else {
				if (!preg_match('/^\d+$/',$billing_time)){
					$has_error = true;
					$this->Service->create_json_array('',101,__('integeronly',true));
				}
			}
			
			$cost = $f['cost'];
			if (empty($cost)){
				$has_error = true;
				$this->Service->create_json_array('',101,__('entercost',true));
			} else {
				if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/',$cost)) {
					$has_error = true;
					$this->Service->create_json_array('',101,__('costformat',true));
				}
			}
			
			$effective_amount = $f['effective_amount'];
			
			if (!empty($effective_amount)) {
				if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/',$effective_amount)) {
					$has_error = true;
					$this->Service->create_json_array('',101,__('effectiveamount',true));
				}
			}
			
			if ($has_error == true) {
				$this->Session->write('backform',$f);
				$this->Session->write('m',Service::set_validator());
				$this->redirect($url);
			}
		}
		
		public function del_service($id){
			if ($this->Service->del($id))
				$this->Service->create_json_array('',201,__('del_suc',true));
			else
				$this->Service->create_json_array('',101,__('usingstate',true));
				
			$this->Session->write('m',Service::set_validator());
			$this->redirect('/services/services_list');
		}
	} 
?>