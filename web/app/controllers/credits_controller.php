<?php
class CreditsController extends AppController{
	var $name = 'Credits';
	var $components = array('RequestHandler');
	
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();
	}

	function credit_view(){
		$this->pageTitle="Finance/Credit Application";
		
		$currPage = 1;
			$pageSize = 100;
			$search_arr = array();
			$order_arr = array();
			if (!empty($_REQUEST['order_by']))
			{
				$order_by = explode("-", $_REQUEST['order_by']);
				$order_arr[$order_by[0]] = $order_by[1];
			}
			
			if (!empty($_REQUEST['search']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';				
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				$search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
				$search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
				$search_arr['action_type'] = !empty($_REQUEST['tran_type']) ? intval($_REQUEST['tran_type']) : 0;
				$search_arr['status'] = !empty($_REQUEST['tran_status']) ? intval($_REQUEST['tran_status']) : 0;
				$search_arr['descript'] = !empty($_REQUEST['descript']) ? $_REQUEST['descript'] : '';
			}
			
//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
		$temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
		 
		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                
                $_SESSION['paging_row'] = $pageSize;
	
		$results = $this->Credit->ListCredit ($currPage, $pageSize, $search_arr, $search_type, $order_arr);
		$this->set ( 'p', $results);
		pr($results);
	}


	function  credit_detail($id=null){
		$this->pageTitle = "View Credit Application";
		$this->render('view_credit');
	}

}
?>
