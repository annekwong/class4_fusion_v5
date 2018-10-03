<?php
class UserTrunksController extends OrderAppController{
	var $name = 'UserTrunks';
	var $uses = array("Order.UserTrunk");
function  index()
	{
		$this->redirect('view');
	}
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();
	}
	
	function view()
	{		
		$this->pageTitle = "Trunk Management";
		$temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
		 
		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                
                $_SESSION['paging_row'] = $pageSize;
		
			$search_arr = array();
			if (!empty($_REQUEST['search']) && strcmp('Search....', $_REQUEST['search']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['search_value'] = $_REQUEST['search'];
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				
			}
		$results = $this->UserTrunk->ListOrderUserTrunks ($currPage, $pageSize, $search_arr, $search_type);
		$this->set('p',$results);		
	}
	
}
?>