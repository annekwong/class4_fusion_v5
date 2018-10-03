<?php

/**
 * 积分换话费规则
 * @author root
 *
 */
class ComplainfeedbacksController extends AppController{
	
	var $name = 'Complainfeedbacks';
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
   $complain_id=$this->params['pass'][0];
   $content=$this->params['pass'][1];
   $status=$this->params['pass'][2];
   $statuss=$this->params['pass'][3];
   $this->data['Complainfeedback']['complain_id']=$complain_id;
   $this->data['Complainfeedback']['content']=$content;
   $this->data['Complainfeedback']['followed_up_on']=date( "Y-m-d   H:i:s" );
   $this->data['Complainfeedback']['status']=$status;
	$login_type = $this->Session->read('login_type');
				  if ($login_type == 2) {
				  	$this->data['Complainfeedback']['user_type'] = 1;
				  	$this->data['Complainfeedback']['user_id'] = $this->Session->read('sst_reseller_id');
				  	} else if ($login_type == 3) {
				  		$this->data['Complainfeedback']['user_type'] = 2;
				  		$this->data['Complainfeedback']['user_id'] = $this->Session->read('sst_client_id');
				  		} else {
				  			$this->data['Complainfeedback']['user_type'] = 3;
				  			$this->data['Complainfeedback']['user_id'] = $this->Session->read('sst_card_id');
				  		}
   $this->Complainfeedback->save($this->data);
   $this->redirect("/complainfeedbacks/view/$complain_id/$statuss");

	
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
	
	public function del_feedback($id,$complain_id,$status){
		if ($id == 'all'){
			$id = "select feedback_id from complain_feedback where complain_id = $complain_id";
		}
		
		if ($id == 'selected'){
			$id = $_REQUEST['ids'];
		}
		if ($this->Complainfeedback->del($id)){
			$this->Complainfeedback->create_json_array('',201,__('del_suc',true));
		} else {
			$this->Complainfeedback->create_json_array('',101,__('del_fail',true));
		}
		
		$this->Session->write('m',Complainfeedback::set_validator());
		$this->redirect('/complainfeedbacks/view/'.$complain_id.'/'.$status);
	}
	

	

	
	
	public function view(){
			$id=$this->params['pass'][0];//投诉建议id
			$status=$this->params['pass'][1];//投诉建议id
				$this->set('id',$id);
				$this->set('status',$status);
				
				$user_type = $this->params['pass'][2];
	
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
				$results = $this->Complainfeedback->findAll($currPage,$pageSize,$id,$user_type);
		}
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
