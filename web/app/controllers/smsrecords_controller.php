<?php

/**
 * 短信记录
 * @author root
 *
 */
class SmsrecordsController extends AppController{
	
	var $name = 'Smsrecords';
	var $helpers = array('javascript','html');
	

	//读取该模块的执行和修改权限
	public function beforeFilter() {
		$this->checkSession ( "login_type" ); //核查用户身份
		$login_type = $this->Session->read ( 'login_type' );
		if ($login_type == 1) {
			//admin
			$this->Session->write ( 'executable', true );
			$this->Session->write ( 'writable', true );
		} else {
			$limit = $this->Session->read ( 'sst_smsrecord' );
			$this->Session->write ( 'executable', $limit ['executable'] );
			$this->Session->write ( 'writable', $limit ['writable'] );
		}
		parent::beforeFilter();
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
		$this->Importlog->query("delete from error_message_info  where errorid=$id");
		$this->Importlog->query("delete from error_info where id=$id");
		//删除上传的文件
		$this->Session->write('m',$this->Importlog->create_json(101,__('alluploadsuccdel',true)));
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
	$this->Smsrecord->query("delete from sms_record    where   time  between  '$start'  and  '$end'");
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	  $this->Smsrecord->query("delete from sms_record where reseller_id=$reseller_id   and time  between  '$start'  and  '$end'");
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	 $this->Smsrecord->query("delete from sms_record where client_id=$client_id   and time  between  '$start'  and  '$end'");
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
 $this->Smsrecord->query("delete from sms_record where user_id=$user_id   and time  between  '$start'  and  '$end'");

}

		$this->Session->write('m',$this->Smsrecord->create_json(101,__('del_suc',true)));
		$this->redirect (array ('action' => 'view' ) );
		
	}
	
	
	
	function delall(){
		
		 $login_type=$_SESSION['login_type'];
	 //admin
	 if($login_type==1){
	$this->Smsrecord->query("delete from sms_record  ");
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	  $this->Smsrecord->query("delete from sms_record where reseller_id=$reseller_id ");
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	 $this->Smsrecord->query("delete from sms_record where client_id=$client_id ");
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
 $this->Smsrecord->query("delete from sms_record where user_id=$user_id ");

}

		$this->Session->write('m',$this->Smsrecord->create_json(101,__('del_suc',true)));
		$this->redirect (array ('action' => 'view' ) );
	}
		

	
	/**
	 * find sms_record
	 */
	public function view(){
				//	$this->init_info ();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 13:	$pageSize = $_GET['size'];
		$results = array();
		 $adv_search = '';
		 $last_conditons = '';
		 if (!empty($this->params['form'])) {
		 		$f = $this->params['form'];
		 		if (!empty($f['search'])) {
		 			$results = $this->Smsrecord->findAll($currPage,$pageSize,$f['search']);
		 			$this->set('search',$f['search']);
		 		} else {
		 			if (!empty($f['search_num'])) {
		 				$adv_search .= " and e.receive_code like '%{$f['search_num']}%'";
		 				$last_conditons .= "&search_num={$f['search_num']}";
		 			}
		 			
		 			if (!empty($f['search_acc'])) {
		 				$adv_search .= " and (select card_number from card where card_id = e.card_id) = '{$f['search_acc']}'";
		 				$last_conditons .= "&search_acc={$f['search_acc']}";
		 			}
		 			
		 			$this->set('last_conditons',$last_conditons);
		 			$this->set('searchform',$f);
		 			
		 			$results = $this->Smsrecord->findAll($currPage,$pageSize,'',$adv_search);
		 		}
		 } else {
		 	$results = $this->Smsrecord->findAll($currPage,$pageSize);
		 }
		 
		
		

		$this->set('p',$results);
			
	}
	
	
	
}
	


?>
