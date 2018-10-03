<?php

/**
 * 
 * @author root
 *
 */
class OnlineusersController extends AppController{
	
	var $name = 'Onlineusers';
	var $helpers = array('javascript','html');
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	
	/**
	 * 初始化信息
	 */
	function init_info(){
				$this->set('reseller',$this->Onlineuser->findReseller());
				$this->set('client',$this->Onlineuser->findClient());
			// $this->set('role',$this->Onlineuser->findRole());
	}
	
	/**
	 * 初始化参数
	 */
	function init_param(){
		$this->Session->write("r_role_id",$this->params ['pass'][0]);
			$this->Session->write("r_role_name",$this->params ['pass'][1]);
	
	}
	
	

	
	

	
	

	public function view(){

		$this->init_info();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->User->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['User'])){
			$results = $this->User->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			
			//
				$results = $this->Onlineuser->findAll($currPage,$pageSize);
		}
		

	

		$this->set('p',$results);
			
	}
	
	


	
	
		/**
	 * 查询某个角色的用户和代理商
	 */
	public function viewroleuser(){
		
	
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		
		 	  	$results = $this->User->likequeryby_role($_POST['searchkey'],$currPage,$pageSize,$this->Session->read("r_role_id"));
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['User'])){
			
		
			$results = $this->User->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			
				$this->init_param();

				$results = $this->User->findAllby_role($currPage,$pageSize,$this->Session->read("r_role_id"));
		}
		

	

		$this->set('p',$results);
			
	}
	


}
	


?>
