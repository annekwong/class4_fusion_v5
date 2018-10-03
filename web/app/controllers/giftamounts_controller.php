<?php

/**
 * 冲值赠送话费规则
 * @author root
 *
 */
class GiftamountsController extends AppController{
	
	var $name = 'Giftamounts';
	var $helpers = array('javascript','html');
	

	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->data ['Giftamount'] )) {
		$id=$this->data['Giftamount']['sales_strategy_id'];
			$flag = $this->Giftamount->update_g($this->data); //保存
			if (empty ( $flag )) {
				$this->set ( 'm', Giftamount::set_validator ()); //向界面设置验证信息
				$this->set ( 'post', $this->data );
				
			} else {
				$this->Giftamount->create_json_array('',201,__('update_suc',true));
				$this->Session->write('m',Giftamount::set_validator());
				$this->redirect("/Salestrategs/findgift_amount/$id");
			}
		} else {
	 $this->Giftamount->sales_strategy_charges_id = $this->params ['pass'][0];
			$this->set('post', $this->Giftamount->read());
		$this->loadModel('Salestrateg');
				$this->set('ratetable',$this->Salestrateg->findRateTable());

		
		}
	}
	
	
	

	
	
	/**
	 * 添加
	 */
	function add() {

			if (! empty ($this->data ['Giftamount'] )) {
				$id=$this->data['Giftamount']['sales_strategy_id'];
				
			$flag = $this->Giftamount->save ($this->data); //保存
			
			if (!$flag) {
				//添加失败
				$this->set ('m', Giftamount::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				//$this->init_info ();
			} else {
				$this->Giftamount->create_json_array('',201,__('add_suc',true));
				$this->Session->write('m',Giftamount::set_validator());
				$this->redirect("/Salestrategs/findgift_amount/$id");
			}
		} else {
						$id=$this->params['pass'][0];//充值策略
				$this->set('id',$id);
				$this->loadModel('Salestrateg');
				$this->set('ratetable',$this->Salestrateg->findRateTable());
		}
    
	
	}

	function del(){
		$id=$this->params['pass'][0];
		$sale_id=$this->params['pass'][1];
	  $this->Giftamount->sales_strategy_charges_id = $this->params ['pass'][0];
	 $post=$this->Giftamount->read();
		$this->Giftamount->query("delete from sales_strategy_charges where sales_strategy_charges_id=$id");
		$this->Session->write('m',$this->Giftamount->create_json(201,'充'.$post['Giftamount']['refill_amount'].'元送'.$post['Giftamount']['gift_amount'].'的送话费规则已经被删除'));
		$this->redirect("/Salestrategs/findgift_amount/$sale_id");
	}
	

	
	/**
	 * 查询客户
	 */
	public function view(){
		
		$this->init_info();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Blocklist->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['Blocklist'])){
			
		
			$results = $this->Blocklist->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			
			//
				$results = $this->Salestrateg->findAll($currPage,$pageSize);
		}
		

	

		$this->set('p',$results);
			
	}
	
	
	public function findgift_amount(){
			$id=$this->params['pass'][0];//充值策略id
				$this->set('id',$id);
		$this->init_info();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Blocklist->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['Blocklist'])){
			
		
			$results = $this->Blocklist->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			
			//
				$results = $this->Salestrateg->findgift_amount($currPage,$pageSize,$id);
		}
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
