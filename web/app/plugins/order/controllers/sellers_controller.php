<?php
class SellersController extends OrderAppController{
	var $name = 'Sellers';
	var $uses = array("Order.Seller");
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
		$this->pageTitle = "Seller Management";
		$temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
		 
		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                
                $_SESSION['paging_row'] = $pageSize;
		
			$search_arr = array();
			if (!empty($_REQUEST['searchkey']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['search_value'] = $_REQUEST['searchkey'];
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				
			}
		$results = $this->Seller->ListSellers ($currPage, $pageSize, $search_arr, $search_type);
		$this->set('p',$results);		
	}
	
	function dis_able($type=null) {
		if (!$_SESSION['role_menu']['Exchange Manage']['sellers']['model_w'])
		{
			$this->redirect_denied();
		}
		Configure::write('debug',0);
		$id = $this->params ['pass'] [0];
		$this->Seller->dis_able ( $id );
		$project_name=Configure::read('project_name');
		if($project_name!='exchange'){
			$this->redirect ( array ('plugin'=>'order','action' => 'view' ) );
		} else{
			if(!$this->params['isAjax']){$this->redirect ( array ('action' => 'view' ) );}else{echo 'true';}
		}
	}

	function active() {
		if (!$_SESSION['role_menu']['Exchange Manage']['sellers']['model_w'])
		{
			$this->redirect_denied();
		}
		Configure::write('debug',0);
		$id = $this->params ['pass'] [0];
		$this->Seller->active ( $id );
		$project_name=Configure::read('project_name');
		if($project_name!='exchange'){
			$this->redirect ( array ('plugin'=>'order','action' => 'view' ) );
		}else{
			if(!$this->params['isAjax']){$this->redirect ( array ('action' => 'view' ) );}else{echo 'true';}
		}
	}
	
}
?>