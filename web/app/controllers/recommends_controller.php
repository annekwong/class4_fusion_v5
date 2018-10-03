<?php
class RecommendsController extends AppController{
	
	
	
	
		//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_CallPhonePrefixes');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
	
	public function reco_list(){
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
				$sql = "select * from recommend_strategy where recommend_strategy_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Recommend->query ( $sql );
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
		$results = $this->Recommend->getList ( $currPage, $pageSize,$search,$reseller_id);
			}
		$this->set ( 'p', $results );
	}	
	
	public function add(){
		Configure::write('debug',0);
		$qs = $this->Recommend->add_recommend();
		echo $qs;
	}
	
	public function update(){
		Configure::write('debug',0);
		$qs = $this->Recommend->update_recommend();
		echo $qs;
	}
	
	public function del_rec($id){
		if ($this->Recommend->del($id))
			$this->Recommend->create_json_array('',201,__('del_suc',true));
		else
			$this->Recommend->create_json_array('',101,__('del_fail',true));
		
		$this->Session->write('m',Recommend::set_validator());
		$this->redirect('/recommends/reco_list');
	}
}