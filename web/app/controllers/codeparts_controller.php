<?php

/**
 * 对接网关号段管理
 * @author root
 *
 */
class CodepartsController extends AppController{
	
	var $name = 'Codeparts';
	var $helpers = array('javascript','html');
	
	
	
//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		parent::beforeFilter();//调用父类方法
		
		
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_manager_client');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					
					
					
	}
	
public function del(){
	$id=$this->params['pass'][0];
	$this->Codepart->query("delete  from  code_part where  code_part_id=$id");
	$this->redirect('/codeparts/codepart');
	
}

public function codepart(){
	
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] )) $currPage = $_REQUEST ['page'];
		
		if (! empty ( $_REQUEST ['size'] )) $pageSize = $_REQUEST ['size'];
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		if(!empty($this->params['pass'][0])){
			$egress_id=$this->params['pass'][0];
			pr($egress_id);
			$_SESSION['codepartengress']=$egress_id;
		}else{
				$egress_id=	$_SESSION['codepartengress'];
			
		}
		
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$results = $this->Codepart->getcodeparts ( $currPage, $pageSize,$search,$egress_id);
		

		$this->set ( 'p', $results );
	
}	
	
	
public function add_codepart(){


		 if(!empty($this->data['Codepart'])){
			 $flag=	$this->Codepart->saveOrUpdate($this->data,$_POST);
			 if($flag=='fail'){
			 	
			 	$this->set ( 'm', Codepart::set_validator ()); //向界面设置验证信息
				$this->set ( 'post', $this->data);
			
			 }else{
			 $this->redirect('/codeparts/codepart');
		
			 } 	  
	
		 }else{
	
		 }
}	
	
public function edit(){


		 if(!empty($this->data['Codepart'])){
			 $flag=	$this->Codepart->saveOrUpdate($this->data,$_POST);
			 if($flag=='fail'){
			 	
			 	$this->set ( 'm', Codepart::set_validator ()); //向界面设置验证信息
				$this->set ( 'post', $this->data);
			
			 }else{
			 $this->redirect('/codeparts/codepart');
		
			 } 	  
	
		 }else{
	$id=$this->params['pass'][0];
 $this->data=$this->Codepart->read();


		 }
}	
	
//上传成功 记录上传
	public  function  upload_code2(){
		
		$code_deck_id=$_POST['upload_table_id'];
		$code_name=$_POST['code_name'];
	

	 $list= $this->Codepart->import_data("上传对接网关号段" );//上传数据
	 $this->Codepart->create_json_array("",201,'号段已经上传成功');
		$this->Session->write('m',Codepart::set_validator());
		$this->redirect('/importlogs/view');//验证上传数据
		
	
	}

	public function download_rate(){
	  	Configure::write('debug',0);
		$rate_table_id	=	$_SESSION['codepartengress'];
		$download_sql="select    start_code,end_code,rate,setup_fee,min_time,grace_time,interval,seconds,month_fee,active_fee	from  code_part  where ingress_id=$rate_table_id";
		$this->Codepart->export__sql_data('导出对接网关号段',$download_sql,'codepart');
  	$this->layout='';


}	
	//上传code	
	public  function  import_rate(){
	$rate_table_id	=	$_SESSION['codepartengress'];
$list= $this->Codepart->query("select name   from  resource where   resource_id=$rate_table_id ");
 $this->set("code_name",$list[0][0]['name']);
	$this->set("rate_table_id",$rate_table_id);
	
	}
}
	


?>
