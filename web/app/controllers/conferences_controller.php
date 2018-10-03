<?php
class ConferencesController extends AppController {
	var $name = 'Conferences';
	var $uses = array ();
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	public function conferences_list() {
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		$this->loadModel ( 'Realcdr' );
		
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
		
		$adv_search = '';
		$last_conditons = '';
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			if (!empty($f['search_res'])) {
				$adv_search .= " and real_cdr.reseller_id = '{$f['search_res']}'";
				$last_conditons .= "&search_res={$f['search_res']}";
			}
			
			if (!empty($f['search_acc'])) {
				$adv_search .= " and real_cdr.account_id = '{$f['search_acc']}'";
				$last_conditons .= "&search_acc={$f['search_acc']}";
			}
			
			$this->set('last_conditons',$last_conditons);
			$this->set('searchForm',$f);
		}
		
		$login_type = $this->Session->read ( 'login_type' );
		$results = $this->Realcdr->get_conferences ( $currPage, $pageSize, $login_type,$adv_search );
		
		$this->set ( 'p', $results );
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$search_info = $this->Realcdr->get_search_info($reseller_id);
		$this->set('res_s',$search_info[0]);
		$this->set('acc_s',$search_info[1]);
	}
	
	public function conf_member_list($conf_id,$uuid_a){
		$this->loadModel ( 'Realcdr' );
		$this->set('members',$this->Realcdr->get_conf_member($conf_id));
		$this->set('uuid_a',$uuid_a);
	}
	
	public function stop_conf($uuid_a){
		$result = $this->PhpTelnet->getResult ( "api uuid_kill $uuid_a");
		$this->redirect('/conferences/conferences_list');
	}
	
	public function kill_member($uuid_b,$conf_id,$uuid_a){
		$result = $this->PhpTelnet->getResult ( "api uuid_kill $uuid_b");
		$this->redirect('/conferences/conf_member_list/'.$conf_id.'/',$uuid_a);
	}
	
	public function sip_conf(){
		$this->loadModel ( 'Realcdr' );
		$sipid = $this->Session->read('card_sip_id');
		$this->set('conf',$this->Realcdr->getSipOwnConf($sipid));
	}
	
	/**
	 * 历史会议
	 */
	public function conferences_history() {
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		$this->loadModel ( 'Realcdr' );
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		$adv_search = '';
		$last_conditons = '';
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			if (!empty($f['search_st'])) {
				$adv_search .= " and cdr.answer_time_of_date >= '{$f['search_st']}'";
				$last_conditons .= "&search_st={$f['search_st']}";
			}
			
			
			if (!empty($f['search_et'])) {
				$adv_search .= " and cdr.answer_time_of_date <= '{$f['search_et']}'";
				$last_conditons .= "&search_et={$f['search_et']}";
			}
			
			if (!empty($f['search_num'])) {
				$adv_search .= " and cdr.termination_destination_number = '{$f['search_num']}'";
				$last_conditons .= "&search_num={$f['search_num']}";
			}
			
			$this->set('last_conditons',$last_conditons);
			$this->set('searchForm',$f);
		}
		
		$login_type = $this->Session->read ( 'login_type' );
		$results = $this->Realcdr->get_conferences_history ( $currPage, $pageSize, $login_type,$adv_search );
		
		$this->set ( 'p', $results );
	}
}
?>