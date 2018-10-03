<?php
class ClientgroupsController extends AppController
{
	var $name = 'Clientgroups';
	var $helpers = array('javascript','html');
	var $uses = array('Clientgroup');

	
//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_config_ClientGroup');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();//调用父类方法
	}
	
	/*
	 * 分页查询客户组
	 */
	public function group_list(){
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
				$sql = "select (select name from rate_table where rate_table_id = client_group.rate_table_id) as rate,client_group_id,name,invoice_note from client_group
				 where client_group_id= {$_REQUEST['edit_id']}
	  		";
			$result = $this->Clientgroup->query ( $sql );
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
				$results = $this->Clientgroup->getAllGroups ( $currPage, $pageSize,$search,$reseller_id);
			}
		
		$this->set ( 'p', $results );
	}
	
	
	/*
	 * 添加和修改客户组的数据验证
	 */
	private function validate_client_group($f,$needcheckrepeat,$url){
		$hasError = false;
		$name = $f['name'];//名字
		if (empty($name)) {
			$hasError = true;
			$this->Clientgroup->create_json_array("#name",101,__('group_name_null',true));
		} else {
			if (!preg_match('/^[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_]+$/',$name)) {
				$hasError = true;
				$this->Clientgroup->create_json_array("#name",101,__('group_name_format',true));
			}
		}
		
		if ($needcheckrepeat == true) {
			$qs = $this->Clientgroup->query("select client_group_id from client_group where name = '$name'");
			if (count($qs) > 0) {
				$hasError = true;
				$this->Clientgroup->create_json_array("#name",101,__('group_name_exists',true));
			}
		}
		
		if ($hasError == true) {
			$this->Session->write('backform',$f);
			$this->Session->write('m',Clientgroup::set_validator());
			$this->redirect($url);
		}
	}
	/*
	 * 添加客户组
	 */
	public function add_client_group(){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$this->validate_client_group($f,true,'/clientgroups/add_client_group');
			
			if (!$this->Clientgroup->save($f)) {
				$this->Session->write('backform',$f);
				$this->Clientgroup->create_json_array('',101,__('add_client_group_failed',true));
				$this->Session->write('m',Clientgroup::set_validator());
				$this->redirect('/clientgroups/add_client_group');
			} else {
				$this->Clientgroup->create_json_array('',201,__('add_client_group_success',true));
				$this->Session->write('m',Clientgroup::set_validator());
				$this->redirect('/clientgroups/group_list');
			}
		}
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$this->set('rates',$this->Clientgroup->getRates($reseller_id));
	}
	
	/*
	 * 修改客户组
	 */
	public function edit_client_group($group_id){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			
			//是否需要验证名字是否重复
			$group_id = $f['client_group_id'];
			$name = $f['name'];
			$qs = $this->Clientgroup->query("select name from client_group where client_group_id = '$group_id'");
			if ($name == $qs[0][0]['name']) {
				$this->validate_client_group($f,false,'/clientgroups/edit_client_group/'.$group_id);
			} else {
				$this->validate_client_group($f,true,'/clientgroups/edit_client_group/'.$group_id);
			}
			
			
			if (!$this->Clientgroup->save($f)) {
				$this->Session->write('backform',$f);
				$this->Clientgroup->create_json_array('',101,__('edit_client_group_failed',true));
				$this->Session->write('m',Clientgroup::set_validator());
				$this->redirect('/clientgroups/edit_client_group');
			} else {
				$this->Clientgroup->create_json_array('',201,__('edit_client_group_success',true));
				$this->Session->write('m',Clientgroup::set_validator());
				$this->redirect('/clientgroups/group_list?edit_id='.$group_id);
			}
		} else {
			$this->set('group',$this->Clientgroup->getGroupById($group_id));
			$reseller_id = $this->Session->read('sst_reseller_id');
			$this->set('rates',$this->Clientgroup->getRates($reseller_id));
		}
	}
	
	/*
	 * 删除客户组
	 */
	public function del_client_group($group_id){
		if (!empty($group_id)) {
			if ($group_id == 'all'){
				$group_id = "select client_group_id from client_group";
				$reseller = $this->Session->read("sst_reseller_id");
				if (!empty($reseller)){$group_id .= " where reseller_id = $reseller";}
			}
			
			if ($group_id == 'selected'){
				$group_id = $_REQUEST['ids'];
			}
			if (!$this->Clientgroup->del($group_id)) {
				$this->Clientgroup->create_json_array('',101,__('del_client_group_failed',true));
			} else {
				$this->Clientgroup->create_json_array('',201,__('del_client_group_success',true));
			}
			$this->Session->write('m',Clientgroup::set_validator());
		}
		$this->redirect('/clientgroups/group_list');
	}
}

?>