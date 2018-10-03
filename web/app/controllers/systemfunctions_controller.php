<?php

/**
 * 系统功能
 * @author root
 *
 */
class SystemfunctionsController extends AppController{
	
	var $name = 'Systemfunctions';
	var $helpers = array('javascript','html');
	

public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->data ['Complain'] )) {
			   $this->data['Complain']['user_id']=$this->Session->read('user_id');
				  $this->data['Complain']['modify_time']=date ( "Y-m-d   H:i:s" );
				  $this->data['Complain']['status']=1;
        $this->Complain->save ($this->data); //保存
				  $this->redirect("/complains/view");

		} else {
	 $this->Complain->complain_id = $this->params ['pass'][0];
			$this->set('post', $this->Complain->read());
		

		
		}
	}
	
	
	

	
	
	/**
	 * 添加
	 */
	function add() {

			if (! empty ($this->data ['Systemfuntion'] )) {
	
        $this->Systemfuntion->save ($this->data); //保存
				  $this->redirect("/systemfunctions/view");
			
		} else {
			
		}
    
	
	}

	function del(){
		$id=$this->params['pass'][0];
		$this->Giftscore->query("delete from complain where complain_id=$id");
		$this->Session->write('m',$this->Complain->create_json(101,__('delcomplainsucc',true)));
		$this->redirect("/complains/view");
	}
	

	function close(){
		$id=$this->params['pass'][0];
				$this->Complain->query("update  complain set status=2 where complain_id=$id");
		$this->Session->write('m',$this->Complain->create_json(101,__('closedcomplainsucc',true)));
		$this->redirect("/complains/view");
		
	}

	
	
	public function view(){
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Complain->likequery($_POST['searchkey'],$currPage,$pageSize);
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
				$results = $this->Systemfunction->findAll($currPage,$pageSize);
		}
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
