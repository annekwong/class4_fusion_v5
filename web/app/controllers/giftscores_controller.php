<?php

/**
 * 积分换话费规则
 * @author root
 *
 */
class GiftscoresController extends AppController{
	
	var $name = 'Giftscores';
	var $helpers = array('javascript','html');
	

	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->data ['Giftscore'] )) {
		$id=$this->data['Giftscore']['sales_strategy_id'];
			$flag = $this->Giftscore->update_g($this->data); //保存
			if (empty ( $flag )) {
				$this->set ( 'm', Giftscore::set_validator ()); //向界面设置验证信息
				$this->set ( 'post', $this->data );
				
			} else {
				$this->Giftscore->create_json_array('',201,__('update_suc',true));
				$this->Session->write('m',Giftscore::set_validator());
							$this->redirect("/giftscores/view/$id");
			}
		} else {
	 $this->Giftscore->sales_strategy_points_id = $this->params ['pass'][0];
			$this->set('post', $this->Giftscore->read());
		

		
		}
	}
	
	
	

	
	
	/**
	 * 添加
	 */
	function add() {

			if (! empty ($this->data ['Giftscore'] )) {
				$id=$this->data['Giftscore']['sales_strategy_id'];
				
			$flag = $this->Giftscore->update_g ($this->data); //保存
			
			if (empty ($flag)) {
				//添加失败
				$this->set ('m', Giftscore::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				//$this->init_info ();
			} else {
				$this->Giftscore->create_json_array('',201,__('add_suc',true));
				$this->Session->write('m',Giftscore::set_validator());
				$this->redirect("/giftscores/view/$id");
			}
		} else {
				$id=$this->params['pass'][0];//充值策略
				$this->set('id',$id);
		}
    
	
	}

	function del(){
		$id=$this->params['pass'][0];
		$sale_id=$this->params['pass'][1];
	  $this->Giftscore->sales_strategy_points_id = $this->params ['pass'][0];
	 $post=$this->Giftscore->read();
		$this->Giftscore->query("delete from sales_strategy_points where sales_strategy_points_id=$id");
		$this->Session->write('m',$this->Giftscore->create_json(101,$post['Giftscore']['bonus_credit'].'积分换话费'.$post['Giftscore']['gift_amount'].'元规则已经被删除'));
		$this->redirect("/giftscores/view/$sale_id");
	}
	

	

	
	
	public function view(){
			$id=$this->params['pass'][0];//充值策略id
				$this->set('id',$id);
	
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
				$results = $this->Giftscore->findAll($currPage,$pageSize,$id);
		}
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
