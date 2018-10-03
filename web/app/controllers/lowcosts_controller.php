<?php
	class LowcostsController extends AppController{
		
		
			//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_lowcoststrategy');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
		public function lowcost_view(){
			$currPage = null;
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
				$sql = "select * from low_cost_strategy where low_cost_strategy_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Lowcost->query ( $sql );
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
				$results = $this->Lowcost->getAllLowcosts ( $currPage, $pageSize,$search,$reseller_id);
			}
			
			$this->set ( 'p', $results );
		}
		
		public function add_lowcost(){
			Configure::write('debug',0);
			$qs = $this->Lowcost->add();
			echo $qs;
		}
		
		public function update_lowcost(){
			Configure::write('debug',0);
			$qs = $this->Lowcost->update();
			echo $qs;
		}
		
		public function del_lowcost($id){
			if ($id == 'all'){
				$id = "select low_cost_strategy_id from low_cost_strategy";
				$reseller_id = $this->Session->read('sst_reseller_id');
				if (!empty($reseller_id)){$id .= " where reseller_id = $reseller_id";}
			}
			
			if ($id == 'selected') {
				$id = $_REQUEST['ids'];
			}
			
			$qs = $this->Lowcost->query("select card_id from card where low_cost_strategy_id in( $id)");
			$qs1 = $this->Lowcost->query("select card_series_id from card_series where low_cost_strategy_id in( $id)");
			if (count($qs) > 0 || count($qs1) > 0)
				$this->Lowcost->create_json_array('',101,__('',true));
			else{
				if ($this->Lowcost->del($id)){
					$this->Lowcost->create_json_array('',201,__('del_suc',true));
				}else{
					$this->Lowcost->create_json_array('',101,__('del_fail',true));
				}
			}
			
			$this->Session->write('m',Lowcost::set_validator());
			$this->redirect('/lowcosts/lowcost_view');
		}
	} 
?>