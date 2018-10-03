<?php
	class ChargereportsController extends AppController{
		var $name = 'Chargereports';
		var $uses = array();
		
		
		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
		
		
		/**
		 * 查看报表数据
		 */
		public function charge_reports(){
			$this->loadModel('Cdr');
			$login_res = $this->Session->read('sst_reseller_id');
			
			//---------------参数-------------//
			$start_time = empty($_REQUEST['st'])?'null':"'".$_REQUEST['st']."'";
			$end_time = empty($_REQUEST['et'])?'null':"'".$_REQUEST['et']."'";
			$reseller_id = empty($_REQUEST['reseller'])?$login_res:$_REQUEST['reseller'];
			//-------------------------------//
			
			$sql = "select * from payment_report($reseller_id,$start_time,$end_time)";
			$result = $this->Cdr->query($sql);
			
			$this->set('result',$result[0][0]['payment_report']);
			
			$this->Cdr->generateTree($reseller_id);
			$this->set('r_reseller',Cdr::$show_reseller);
		}
		
		public function view_payments(){
			$ids = '';
			if (empty($_REQUEST['ids'])) {
				$ids = $this->Session->read('charge_report_ids');
			} else {
				$ids = $_REQUEST['ids'];
				$this->Session->write('charge_report_ids',$ids);
			}
			
			$this->loadModel('Cdr');
			$currPage = empty($_REQUEST['page'])?1:$_REQUEST['page'];
			$pageSize = empty($_REQUEST['size'])?10:$_REQUEST['size'];
		
			//分页信息
			require_once 'MyPage.php';
			$page = new MyPage();
			
			$totalrecords = 0;
		
			$sql = "select count(account_payment_id) as c from account_payment where account_payment_id in ($ids)";
			$totalrecords = $this->Cdr->query($sql);
	 	
			$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
			$page->setCurrPage($currPage);//当前页
			$page->setPageSize($pageSize);//页大小
			
			
			$currPage = $page->getCurrPage()-1;
			$pageSize = $page->getPageSize();
			$offset = $currPage * $pageSize;
			
			$sql = "select payment_time,amount,payment_method,result,cause from account_payment where account_payment_id in($ids)";
			
			$sql .= " limit '$pageSize' offset '$offset'";
			
			$results = $this->Cdr->query($sql);
			
			$page->setDataArray($results);//Save Data into $page
			
			$this->set('p',$page);
		}
	} 
?>