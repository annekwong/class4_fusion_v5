<?php
class RecommendnumsController extends AppController {
	var $name = 'Recommendnums';
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	public function recommend() {
		if (! empty ( $this->params ['form'] )) {
			$nums = $this->params ['form'] ['num'];
			if ($this->Recommendnum->add_num ( $nums )) {
				$this->Recommendnum->create_json_array ( '', 201, __ ( 'yourrequestcommited', true ) );
			} else {
				$this->Recommendnum->create_json_array ( '', 101, __ ( 'yourrequestrollback', true ) );
			}
			$this->Session->write ( 'm', Recommendnum::set_validator () );
			$this->redirect ( '/recommendnums/recommend' );
		}
	}
	
	/**
	 * 代理商查看被推荐的号码
	 */
	public function view_recommend_num() {
		$currPage = 1;
		$pageSize = 100;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		$adv_search = '';
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$last_conditions = '';
			
			if (!empty($f['search_card'])) {
				$adv_search .= " and account_id = (select card_id from card where card_number = '{$f['search_card']}')";
				$last_conditions .= "&search_card={$f['search_card']}";
			}
			
			if (!empty($f['search_st'])) {
				$adv_search .= " and recommend_time >= '{$f['search_st']}'";
				$last_conditions .= "&search_st={$f['search_st']}";
			}
			
			if (!empty($f['search_et'])) {
				$adv_search .= " and recommend_time <= '{$f['search_et']}'";
				$last_conditions .= "&search_et={$f['search_et']}";
			}
			
			$this->set('searchForm',$f);
			$this->set('last_conditions',$last_conditions);
		}
		
		$reseller_id = $this->Session->read ( 'sst_reseller_id' );
		$results = $this->Recommendnum->view_recommend ( $currPage, $pageSize, $reseller_id ,$adv_search);
		
		$this->set ( 'p', $results );
	}
}
?>