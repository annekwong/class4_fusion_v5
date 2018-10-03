<?php
class InterrequestsController extends AppController {
	var $name = 'Interrequests';
	var $uses = array ();
	
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	public function request_list($account_id=null,$series_id=null) {
		$this->loadModel ( 'Cdr' );
		$currPage = empty ( $_REQUEST ['page'] ) ? 1 : $_REQUEST ['page'];
		$pageSize = empty ( $_REQUEST ['size'] ) ? 10 : $_REQUEST ['size'];


		
		
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(id) as c from international_request where 1=1";
		if (!empty($account_id)){
			$sql .= " and account_id = $account_id";
			$this->set('extralSearch',true);
		}
		$totalrecords = $this->Cdr->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		$sql = "select request_time,id,(select card_number from card where card_id=international_request.account_id) as card from international_request where 1=1";
		
		if (!empty($account_id)){
			$sql .= " and account_id = $account_id";
			$this->set('extralSearch',true);
		}
		
		if (!empty($series_id)){
			if ($series_id=='res'){
				$this->set('backurl',"/exchange/cardpools/cards_list_by_reseller/{$_REQUEST['res_id']}");
				$this->set('res',$_REQUEST['res_id']);
			} else { 
				$this->set('series',$series_id);
				$this->set('backurl',"/exchange/cardpools/cards_list/$series_id");
			}
		}else {
			$this->set('backurl',"/exchange/cardpools/cards_list_all");
		}
		
		
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->Cdr->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		

		$this->set ( 'p', $page );
	}
	
	/**
	 * 同意某个请求
	 * @param  $req_id
	 */
	public function agree_request($req_id,$extral=null,$extral_series=null,$res=null) {
		$this->loadModel('Refill');
		if ($req_id == 'selected'){
			$req_id = $_REQUEST['ids'];
		}
		$qs_count = 0;
		$qs = $this->Refill->query("update card set international = true where card_id = (select account_id from international_request where id = $req_id )");
		$qs_count += count($qs);
		
		$qs = $this->Refill->query("delete from international_request where id = $req_id");
		$qs_count += count($qs);
		
		if ($qs_count == 0){
			$this->Refill->create_json_array('',201,__('manipulated_suc',true));
		} else {
			$this->Refill->create_json_array('',101,__('manipulated_fail',true));
		}
		$this->Session->write('m',Refill::set_validator());
		
		if ($res != null){
				$this->redirect('/interrequests/request_list/'.$extral.'/res?res_id='.$res);
		}
		
		if (!empty($extral) && !empty($extral_series)){
			$this->set('extralSearch',true);
			$this->redirect('/interrequests/request_list/'.$extral.'/'.$extral_series);
		} else {
			$this->redirect('/interrequests/request_list');			
		}
		
		$this->redirect('/interrequests/request_list');
	}
	
	/**
	 * 同意所有请求
	 */
	public function agree_all($account_id=null,$extral=null,$extral_series=null,$res=null){
		$this->loadModel('Refill');
		$qs_count = 0;
		$sql = "update card set international = true where card_id in (select account_id from international_request";
		if (!empty($account_id)){
			$sql .= " where account_id = $account_id";
		}
		$qs = $this->Refill->query($sql.")");
		$qs_count += count($qs);
		
		$qs = $this->Refill->query("delete from international_request");
		$qs_count += count($qs);
		
		if ($qs_count == 0){
			$this->Refill->create_json_array('',201,__('manipulated_suc',true));
		} else {
			$this->Refill->create_json_array('',101,__('manipulated_fail',true));
		}
		
		$this->Session->write('m',Refill::set_validator());
		if ($res != null){
				$this->redirect('/interrequests/request_list/'.$extral.'/res?res_id='.$res);
		}
		
		if (!empty($extral) && !empty($extral_series)){
			$this->set('extralSearch',true);
			$this->redirect('/interrequests/request_list/'.$extral.'/'.$extral_series);
		} else {
			$this->redirect('/interrequests/request_list');			
		}
		
		
		$this->redirect('/interrequests/request_list');
	}
}
?>