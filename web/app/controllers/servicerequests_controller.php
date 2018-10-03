<?php
class ServicerequestsController extends AppController {
	var $uses = array ();
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	public function requests_list($account_id=null,$series_id=null) {
		$this->loadModel ( 'Card' );
		$reseller_id = $this->Session->read ( 'sst_reseller_id' );
		$currPage = null;
		$pageSize = null;
		
		if (!empty($_REQUEST['page'])) $currPage = $_REQUEST['page'];
		if (!empty($_REQUEST['size'])) $pageSize = $_REQUEST['size'];
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(id) as c from card_service where result = 0";
		if (! empty ( $reseller_id )){
			$sql .= " and reseller_id = '$reseller_id'";
		}
			
		if (!empty($account_id)){
			$sql .= " and card_id = $account_id";
		}
		
		$totalrecords = $this->Card->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		//$page = $page->checkRange($page);//检查当前页范围
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		$sql = "select id,(select card_number from card where card_id = card_service.card_id) as card,
								(select service_name from value_add_service where service_id = card_service.service_id) as service,
								request_time from card_service where result = 0";
		if (! empty ( $reseller_id )){
			$sql .= " and reseller_id = $reseller_id";
		}
			
		if (!empty($account_id)){
			$sql .= " and card_id = $account_id";
			$this->set('extralSearch',$account_id);
		}
		
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->Card->query ( $sql );
		
		if (!empty($series_id)){
			if ($series_id == 'res'){
				$this->set('res',$_REQUEST['res_id']);
				$this->set('backurl',"/exchange/cardpools/cards_list_by_reseller/{$_REQUEST['res_id']}");
			} else {
				$this->set('series',$series_id);
				$this->set('backurl',"/exchange/cardpools/cards_list/$series_id");
			}
		}
		else {
			$this->set('backurl',"/exchange/cardpools/cards_list_all");
		}
		
		$page->setDataArray ( $results ); //Save Data into $page

		$this->set ( 'p', $page );
	}
	
	public function agree_request($id,$extral=null,$extral_series=null,$res=null){
		$this->loadModel('Card');
		if ($id == 'selected'){$id = $_REQUEST['ids'];}
		
		$qs = $this->Card->query("update card_service set result = 1,success_time=current_timestamp(0) where id in($id)");
		if (count($qs) == 0)
			$this->Card->create_json_array('',201,__('determinesuc',true));
		else 
			$this->Card->create_json_array('',101,__('determinefail',true));
		
		$this->Session->write('m',Card::set_validator());
		if ($res != null){
				$this->redirect('/servicerequests/requests_list/'.$extral.'/res?res_id='.$res);
		}
		if (!empty($extral) && !empty($extral_series)){
				$this->redirect('/servicerequests/requests_list/'.$extral.'/'.$extral_series);
		} else {
			$this->redirect('/servicerequests/requests_list');			
		}
	}
}
?>