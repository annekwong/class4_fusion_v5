<?php
class RealcdrsController extends AppController {
	
	//实时通话
	var $name = 'Realcdrs';
	var $helper = array ('javascript', 'html' );
	var $components = array ('PhpTelnet' );
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
		public function callback_cdr() {
		$reseller_id = $this->Session->read ( 'sst_reseller_id' );
		$condition = null;
		if (! empty ( $_REQUEST ['search'] )) {
			$last_conditions = '';
			$condition = '';
			if (! empty ( $_REQUEST ['st'] )) {
				$st = $_REQUEST ['st'];
				$condition .= " and ans_time_a >= '$st'";
				$last_conditions .= "&st=$st";
			}
			
			if (! empty ( $_REQUEST ['et'] )) {
				$et = $_REQUEST ['et'];
				$condition .= " and ans_time_a <= '$et'";
				$last_conditions .= "&et=$et";
			}
			
			if (! empty ( $_REQUEST ['dst'] )) {
				$dst = $_REQUEST ['dst'];
				$condition .= " and dnis = '$dst'";
				$last_conditions .= "&dst=$dst";
			}
			
			if (! empty ( $_REQUEST ['ani'] )) {
				$src = $_REQUEST ['src'];
				$condition .= " and ani = '$src'";
				$last_conditions .= "&src=$src";
			}
			
			if (! empty ( $_REQUEST ['ing'] )) {
				$ing = $_REQUEST ['ing'];
				$condition .= " and ingress_id = '$ing'";
				$last_conditions .= "&ing=$ing";
			}
			
			if (! empty ( $_REQUEST ['eg'] )) {
				$eg = $_REQUEST ['eg'];
				$condition .= " and egress_id = '$eg'";
				$last_conditions .= "&eg=$eg";
			}
			
			if (! empty ( $_REQUEST ['res'] )) {
				$res = $_REQUEST ['res'];
				$condition .= " and reseller_id = '$res'";
				$last_conditions .= "&res=$res";
			}
			
			if (! empty ( $_REQUEST ['cli'] )) {
				$cli = $_REQUEST ['cli'];
				$condition .= " and client_id = '$cli'";
				$last_conditions .= "&cli=$cli";
			}
			$last_conditions .= "&search=true";
			$this->set ( 'last_conditions', $last_conditions );
		}
		
		$search_info = $this->Realcdr->search_info ( $reseller_id );
		$this->set ( 'ingress', $search_info [0] );
		$this->set ( 'egress', $search_info [1] );
		$this->set ( 'reseller', $search_info [2] );
		$this->set ( 'client', $search_info [3] );
		
		$currPage = 1;
		$pageSize = 100;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		$results = $this->Realcdr->get_callback_info ( $currPage, $pageSize, $reseller_id, $condition );
		
		$this->set ( 'p', $results );
	}
	
	
	
	
	public function real_cdr() {
		$reseller_id = $this->Session->read ( 'sst_reseller_id' );
		$condition = null;
		if (! empty ( $_REQUEST ['search'] )) {
			$last_conditions = '';
			$condition = '';
			if (! empty ( $_REQUEST ['st'] )) {
				$st = $_REQUEST ['st'];
				$condition .= " and ans_time_a >= '$st'";
				$last_conditions .= "&st=$st";
			}
			
			if (! empty ( $_REQUEST ['et'] )) {
				$et = $_REQUEST ['et'];
				$condition .= " and ans_time_a <= '$et'";
				$last_conditions .= "&et=$et";
			}
			
			if (! empty ( $_REQUEST ['dst'] )) {
				$dst = $_REQUEST ['dst'];
				$condition .= " and dnis = '$dst'";
				$last_conditions .= "&dst=$dst";
			}
			
			if (! empty ( $_REQUEST ['src'] )) {
				$src = $_REQUEST ['src'];
				$condition .= " and ani = '$src'";
				$last_conditions .= "&src=$src";
			}
			
			if (! empty ( $_REQUEST ['ing'] )) {
				$ing = $_REQUEST ['ing'];
				$condition .= " and ingress_id = '$ing'";
				$last_conditions .= "&ing=$ing";
			}
			
			if (! empty ( $_REQUEST ['eg'] )) {
				$eg = $_REQUEST ['eg'];
				$condition .= " and egress_id = '$eg'";
				$last_conditions .= "&eg=$eg";
			}
			
			if (! empty ( $_REQUEST ['res'] )) {
				$res = $_REQUEST ['res'];
				$condition .= " and reseller_id = '$res'";
				$last_conditions .= "&res=$res";
			}
			
			if (! empty ( $_REQUEST ['cli'] )) {
				$cli = $_REQUEST ['cli'];
				$condition .= " and client_id = '$cli'";
				$last_conditions .= "&cli=$cli";
			}
			
			if (!empty($_REQUEST['dur'])){
				$dur = $_REQUEST['dur'];
				$condition .= " and  (current_timestamp -  ans_time_a::timestamp) > interval '$dur second' ";
				$last_conditions .= "&dur=$dur";
			}
			
			if (!empty($_REQUEST['acc'])){
				$acc = $_REQUEST['acc'];
				$condition .= " and  account_id = $acc ";
				$last_conditions .= "&acc=$acc";
			}
			
			$last_conditions .= "&search=true";
			$this->set ( 'last_conditions', $last_conditions );
		}
		
		$search_info = $this->Realcdr->search_info ( $reseller_id );
		$this->set ( 'ingress', $search_info [0] );
		$this->set ( 'egress', $search_info [1] );
	
		$this->set ( 'client', $search_info [3] );
		$this->set ( 'accounts', $search_info [4] );
		
		$currPage = 1;
		$pageSize = 100;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		$results = $this->Realcdr->get_real_info ( $currPage, $pageSize, $reseller_id, $condition );
		
		$this->set ( 'p', $results );
		
		if (!empty($this->params['form'])){
			$this->set('searchform',$this->params['form']);
		}
	}
	
	
	public function kill_member($uuid,$sipcode=null){
		$result = $this->PhpTelnet->getResult ( "api uuid_kill $uuid");
		if (!empty($sipcode)){
			$this->redirect('/accountsips/sip_code');
		} else {
			$this->redirect('/realcdrs/real_cdr');
		}
	}
	
	
}