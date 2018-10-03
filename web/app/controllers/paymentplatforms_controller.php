<?php

/**
 * 支付通道
 * @author root
 *
 */
class PaymentplatformsController extends AppController{
	
	var $name = 'Paymentplatforms';
	var $helpers = array('javascript','html');
	

function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
	parent::beforeFilter();
}	


	function add() {
			if (! empty ($this->data ['Paymentplatform'] )) {
			$flag = $this->Paymentplatform->saveOrUpdate ($this->data, $_POST); //保存
			if (empty ($flag)) {
				//添加失败
				$this->set ('m', Paymentplatform::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				
			} else {
				$this->redirect ( array ( 'action' => 'view' ) ); // succ
			}
		} else {
			
		}
    
    
	}

	function edit() {
			if (! empty ($this->data ['Paymentplatform'] )) {
			$flag = $this->Paymentplatform->saveOrUpdate ($this->data, $_POST); //保存
			if (empty ($flag)) {
				//添加失败
				$this->set ('m', Paymentplatform::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				
			} else {
	$this->redirect ( array ( 'action' => 'view' ) ); // succ

			}
		} else {
						$this->Paymentplatform->payment_platform_id = $this->params ['pass'][0];
			$post=$this->Paymentplatform->read ();
	 
			$this->set('post', $post);
		}
    
    
	}
	
	/**
	 * find sms_record
	 */
	public function view(){
		

				//	$this->init_info ();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 13:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Smsrecord->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['Dynamicroute'])){
			$results = $this->Dynamicroute->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			//普通查询
				$results = $this->Paymentplatform->findAll($currPage,$pageSize);
		}
		

		$this->set('p',$results);
			
	}
	
	
	
	



}
	


?>
