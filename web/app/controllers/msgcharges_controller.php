<?php
class MsgchargesController extends AppController {
	var $name = 'Msgcharges';
	var $helper = array ('javascript', 'html' );
	
	
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_smsrate');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	public function msg_charge_list() {
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
	
		
		if (!empty($_REQUEST['edit_id'])||ereg("^[0-9]+$",$_REQUEST['edit_id'])){
	
				$sql = "select * from sms_charges where msg_charges_id  = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Msgcharge->query ( $sql );
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
		$results = $this->Msgcharge->getCharges ( $currPage, $pageSize,$search,$reseller_id);
			}
		$this->set ( 'p', $results );
	}
	
		public function add_charge(){
			Configure::write('debug',0);
			$qs = $this->Msgcharge->add_charge();
			echo $qs;
		}
		
		public function edit_charge(){
			Configure::write('debug',0);
			$qs = $this->Msgcharge->edit_charge();
			echo $qs;
		}
		
		public function del_charge($id){
			$qs = $this->Msgcharge->del_charge($id);
			if ($qs == true)
				$this->Msgcharge->create_json_array('',201,__('del_suc',true));
			else 
				$this->Msgcharge->create_json_array('',101,__('using',true));
				
			$this->Session->write('m',Msgcharge::set_validator());
			$this->redirect('/msgcharges/msg_charge_list');
		}
}