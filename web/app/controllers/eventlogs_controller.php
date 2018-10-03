<?php

/**
 * 事件日志
 * @author root
 *
 */
class EventlogsController extends AppController{
	
	var $name = 'Eventlogs';
	var $helpers = array('javascript','html');
	

function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
	parent::beforeFilter();
	
}	


public function export(){
	$this->Eventlog->query("COPY event_log TO '/tmp/eve.csv'  CSV HEADER ;");
			//	header("HTTP/1.1 301 Moved Permanently");
	header('Content-type: application/html');
    header('Content-Disposition: attachment; filename="downloaded.html"');
    readfile('downloaded.txt');
    echo "这是一个下载文件";
	 //显示登陆对话框  
/*header('HTTP/1.1 401 Unauthorized');  
 header('WWW-Authenticate: Basic realm="Top Secret"');  
 print 'Text that will be displayed if the user hits cancel or ';  
 print 'enters wrong login data';  */
}
		public  function ajax_content(){
			Configure::write('debug' ,0);
			$event_log_id=$this->params['pass'][0];
		 $this->set ('extensionBeans',$this->Eventlog->query("select content from event_log where event_log_id=$event_log_id"));
	
	}
	

	
	/**
	 * 初始化信息
	 */
	function init_info(){
			$this->set('reseller',$this->Dynamicroute->findReseller());
			$this->set('egress',$this->Dynamicroute->findAllEgress());
			$this->set('user',$this->Dynamicroute->findAllUser());
				

		
	}


	function del(){
		$id=$this->params['pass'][0];
		//删除数据库记录
		$this->Importlog->query("delete from event_log    where event_log_id=$id");
		$this->Session->write('m',$this->Eventlog->create_json(101,__('del_suc',true)));
		$this->redirect (array ('action' => 'view' ) );
	}
	
	
	
	/**
	 * 按时间端删除
	 */
		function delbytime(){
		$start=$this->params['pass'][0];
		$end=$this->params['pass'][1];

				 $login_type=$_SESSION['login_type'];
	 //admin
	 if($login_type==1){
	$this->Smsrecord->query("delete from event_log    where   time  between  '$start'  and  '$end'");
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	  $this->Smsrecord->query("delete from event_log where reseller_id=$reseller_id   and time  between  '$start'  and  '$end'");
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	 $this->Smsrecord->query("delete from event_log where client_id=$client_id   and time  between  '$start'  and  '$end'");
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
 $this->Smsrecord->query("delete from event_log where user_id=$user_id   and time  between  '$start'  and  '$end'");

}

		$this->Session->write('m',$this->Eventlog->create_json(101,__('del_suc',true)));
		$this->redirect (array ('action' => 'view' ) );
		
	}
	
	
	
	function delall(){
		
		 $login_type=$_SESSION['login_type'];
	 //admin
	 if($login_type==1){
	$this->Smsrecord->query("delete from event_log  ");
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	  $this->Smsrecord->query("delete from event_log where reseller_id=$reseller_id ");
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	 $this->Smsrecord->query("delete from event_log where client_id=$client_id ");
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
 $this->Smsrecord->query("delete from event_log where user_id=$user_id ");

}

		$this->Session->write('m',$this->Eventlog->create_json(101,__('del_suc',true)));
		$this->redirect (array ('action' => 'view' ) );
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
				$results = $this->Eventlog->findAll($currPage,$pageSize);
		}
		

		$this->set('p',$results);
			
	}
	
	
/*
	 * 池列表
	 */
	public function events_list(){
		$this->pageTitle="Configuration/Events ";
		$currPage = 1;
		$pageSize = 100;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		$adv_search = '';
		if (!empty($_REQUEST['type'])){
			$adv_search .= " and type = '{$_REQUEST['type']}'";
			$this->set('last_conditons',"&type={$_REQUEST['type']}");
		}
		
		$results = $this->Eventlog->getAllEvents ( $currPage, $pageSize,$adv_search);
		$this->set ( 'p', $results );
	}
	
	public function del_event($id){
		if ($id == 'all'){
			$id = "select event_log_id from event_log";
		}
		
		if ($id == 'selected'){
			$id = $_REQUEST['ids'];
		}
		
		if ($this->Eventlog->del($id)){
			$this->Eventlog->create_json_array('',201,__('del_suc'));
		} else {
			$this->Eventlog->create_json_array('',101,__('del_fail'));
		}
		
		$this->Session->write('m',Eventlog::set_validator());
		$this->redirect('/eventlogs/events_list');
	}

}
	


?>
