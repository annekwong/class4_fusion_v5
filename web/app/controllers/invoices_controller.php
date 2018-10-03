<?php
class InvoicesController extends AppController {
	//var $components = array('PhpSendMail');
	#send invoice  mail
	public  function send_invoice(){
			//$this->PhpSendMail->send_mail('wangxj@mail.yht.com','contract','contract contract','/tmp/exports/client_cdr_1294990147.csv');
	}
	public function  add_invocie_item($invoice_id){
		$flag=true;
		if(isset($_POST['positions'])){
			foreach ($_POST['positions'] as  $key=>$value){
				$name=isset($value['name'])?$value['name']:'';
				$price=isset($value['price'])?$value['price']:'0';
				if(empty($name)||empty($price)){
					continue;
				}else{
					$sql="insert into invoice_item(invoice_id,item,price)values($invoice_id,'$name',$price);";
				   $r=	$this->Invoice->query($sql);
                       //插入异常
				   if(!is_array($r)){
				   	$flag=false;
				   	break;
				   }
				}
			}
		}
		return $flag;
	}
	function download_cdr(){
	//	Configure::write('debug',0);
		if(!empty($this->params['pass'][0])){
			 $this->Invoice->invoice_id = $this->params ['pass'] [0];
			 $data=$this->Invoice->read();//var_dump($data);exit;
			 $start=$data['Invoice']['invoice_start'];
			 $end=$data['Invoice']['invoice_end'];
			 $client_id=$data['Invoice']['client_id'];
			 if(!empty($start)&&!empty($end)){
			 		$sql = "SELECT * FROM client_cdr".date("Ymd")." WHERE ingress_client_id={$client_id}::text AND time >= '{$start}' AND time <= '{$end}'";
			   //$sql="select *  from  client_cdr where time  between   '$start'  and  '$end' ";
				  $this->layout='csv';
				  $this->Invoice->export__sql_data('download Cdr',$sql,'cdr');
				  $this->layout='csv';
				 exit();
			  }
		}else{
			$this->redirect('/invoices/view/');
			
		}
	}
function download_file(){
		Configure::write('debug',0);
		if(!empty($this->params['pass'][0])){
			  $cdr_path = str_replace('|', '/', $this->params ['pass'] [0]);
			 
				  $this->layout='csv';
				  header ( "Content-type: application/octet-stream;charset=utf8" );
					header ( "Accept-Ranges: bytes" );		
					header ( "Content-Disposition: attachment; filename=" . basename($cdr_path) );
					echo file_get_contents($cdr_path);
					$this->layout='csv';
				 exit();
		}
		return true;
	}
	public function download_rate($invoice_id=null){
	  	Configure::write('debug',0);
	  	$id_where='';
	  	if(isset($_POST['ids'])){
	  		 $id_str='';
		    foreach ($_POST['ids'] as $key=> $value) {
       		$id_str.="$value,";
					}
	  			$id_str=substr($id_str,0,-1);
	  			$id_where="where invoice_id in ($id_str)";
		  	}
			$download_sql="select *,(select name from client where client_id = invoice.client_id) as client from invoice  $id_where";
			$this->Invoice->export__sql_data('Download Invoice',$download_sql,'invoice');
	  	$this->layout='csv';
			exit();
	}	
	
	function mass_update(){
		if(!isset($_POST['ids'])){
			 $this->redirect("/invoices/view/1");
		}
		$id_str='';
		foreach ($_POST['ids'] as $key=> $value) {
    $id_str.="$value,";
		}
		$id_str=substr($id_str,0,-1);
 		$action=$_POST['action'];
 		if($action=='0'  or  $action=='1'  or $action=='2' ){
		 	 $this->Invoice->query("update  invoice set state=$action  where invoice_id  in($id_str)");
		 	 if($action=='1'){}
		 }
	 if($action=='3'  ){
 	 		$this->Invoice->query("delete from  invoice  where invoice_id  in($id_str)");
 		}
 		$this->redirect("/invoices/view/1");
	}
		//初始化查询参数
	function init_query() {
		$this->set ( 'currency', $this->Invoice->find_currency () );
	}
//生成pdf	
public function  createpdf_invoice($invoice_number){
	App::import("Model",'Invoice');
	$invoice_model = new Invoice;	
	$html = $invoice_model->generate_pdf_content($invoice_number);
	Configure::write('debug',0);
	$this->autoRender = false;
	App::import("Vendor","tcpdf",array('file'=>"tcpdf/pdf.php"));
	create_PDF(Configure::read('database_export_path') . "/invoice",$html);	
	//echo $html;
}
//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_account_invoice');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	public function validate(){
//		pr($_POST);
			$error_flag = 'false';
			$client_id = $_POST ['query'] ['id_clients'];
			$invoice_number = $_POST ['invoice_number'];
			$state = $_POST ['state'];
			$type = $_POST ['type'];
			$due_date = $_POST ['due_date'];
			$start_date = $_POST ['start_date']; //开始日期
			$stop_date = $_POST ['stop_date']; //结束日期
     if(!empty( $invoice_number)){
	        $c=$this->Invoice->query("select  count(*)  from invoice  where  invoice_number='$invoice_number';");
        if($c[0][0]['count']>0){
	       	$this->Invoice->create_json_array ( '#invoice_number', 101, 'invoice Number  Repeatability');
	   	  	$error_flag = true; 
    				}
             }
			if(empty($client_id)){
					$this->Invoice->create_json_array ( '#query-id_clients_name', 101, __ ( 'clientnamenull', true ) );
		   	$error_flag = true; 
			}
			if(empty($due_date)){
					$this->Invoice->create_json_array ( '#due_date', 101, 'Due Date is  null');
		   	$error_flag = true; 
			}
			if(empty($due_date)){
					$this->Invoice->create_json_array ( '#due_date', 101, 'Due Date is  null');
		   	$error_flag = true;
			}
			return $error_flag;
	}
	
	
	public function add(){
			if (!empty($_POST)) {
				$error_flag	=$this->validate();
				if($error_flag!='1'){
					$this->data['Invoice']['client_id'] = $_POST ['query'] ['id_clients'];
					$this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number'])?"{$_POST['invoice_number']}":substr(getMicrotime(),-1,9)."yht";
					$this->data['Invoice']['state'] = $_POST ['state'];
					$this->data['Invoice']['type'] = $_POST ['type'];
					$this->data['Invoice']['create_type'] = 1;
					$this->data['Invoice']['due_date'] = $_POST ['due_date'];
					$start_date = $_POST ['start_date']; //开始日期
					$start_time = $_POST ['start_time']; //开始时间
					$stop_date = $_POST ['stop_date']; //结束日期
					$stop_time = $_POST ['stop_time']; //结束时间
					$tz = $_POST ['query']['tz']; //结束时间
					$this->data['Invoice']['invoice_start'] = $start_date . '  ' . $start_time.' '.$tz; //开始时间
					$this->data['Invoice']['invoice_end'] = $stop_date . '  ' . $stop_time.' '.$tz; //结束时间
					$list=$this->Invoice->query("select  balance   from  c4_client_balance  where client_id='{$_POST ['query'] ['id_clients']}'");
					$this->data['Invoice']['current_balance']=!empty($list[0][0]['balance'])?$list[0][0]['balance']:'0.000';
					$r=$this->Invoice->query("select  *  from  create_client_invoice_exchange(
					{$this->data['Invoice']['client_id']},
					'{$this->data['Invoice']['invoice_start']}',
					'{$this->data['Invoice']['invoice_end']}',
					'{$this->data['Invoice']['invoice_number']}',
					'{$this->data['Invoice']['due_date']}')");
					if(!is_array($r)) {
			    	$this->Invoice->rollback();
			     $this->Invoice->create_json_array('#ClientOrigRateTableId',201,'Create failddd');
						$this->set ('m', Invoice::set_validator ()); //向界面设置验证信息
						$this->set ('post', $this->data );
						return ;
					}else{
							$this->Invoice->commit();
					}
				}else{
					$this->Invoice->create_json_array('#ClientOrigRateTableId',201,'Create fail');
					$this->set ('m', Invoice::set_validator ()); //向界面设置验证信息
					$this->set ('post', $this->data );
					return ;
				}
			 $pdf_name = $this->createpdf_invoice($this->data['Invoice']['invoice_number']);//生成invoice
			  pg_query("update invoice set pdf_path='{$pdf_name}'");
		} else {
			$this->init_query();
		}
	}
	public function  redirect_edit($invoice_id){
	 $project_name=Configure::read('project_name');
	 if($project_name!='exchange'){
	 		$this->redirect("/pr/pr_invoices/edit/$invoice_id");			
	 }else{
  		$this->redirect("/invoices/edit/$invoice_id");			
	  }
	}
	public function edit(){
		if (! empty ( $_POST )) {
   	$invoice_id = $_POST ['invoice_id'];
//			$state = $_POST ['state'];
//			$credit=$_POST['credit'];
   	if(empty($invoice_id)){
				$this->Invoice->create_json_array ( '#credit', 101, 'invoice is corrupted or does not exists.');
				$this->Session->write("m",Invoice::set_validator ());
		   $this->redirect("/invoices/edit/$invoice_id");			
			}
//			$invoice_info = $this->Invoice->query("select total_amount from invoice where invoice_id=".intval($invoice_id));
			
//			if(!preg_match('/^[+\-]?\d+(.\d+)?$/',$credit)){
//				$this->Invoice->create_json_array ( '#credit', 101, 'Invoice has zero credit, no sense in creation.');
//				$this->Session->write("m",Invoice::set_validator ());
//		   $this->redirect("/invoices/edit/$invoice_id");			
//			}
//			elseif ($invoice_info[0][0]['total_amount'] < $credit)
//			{
//				$this->Invoice->create_json_array ( '#credit', 101, 'Invoice credit must less than invoice amount.');
//				$this->Session->write("m",Invoice::set_validator ());
//		   $this->redirect("/invoices/edit/$invoice_id");
//			}
			//$r=$this->Invoice->query("update invoice set state={$state}, credit_amount={$credit}  where  invoice_id=$invoice_id");
			if (0){//(!is_array($r)) {
			 $this->Invoice->create_json_array('#ClientOrigRateTableId',101,'Database  Error');
			 $this->Session->write("m",Invoice::set_validator ());
      $this->redirect_edit($invoice_id);			
			} else {
				$file_arr = $this->_move_upload_file('Invoice');
				if (!empty($file_arr))
				{
					$this->Invoice->query("update invoice set cdr_path='{$file_arr}'  where  invoice_id=$invoice_id");
				}
				$this->Invoice->create_json_array('#ClientOrigRateTableId',201,'Edit success');
				$this->Session->write("m",Invoice::set_validator ());
				$this->redirect_edit($invoice_id);
			}
		} else {
			$this->init_query();
			$invoice_id=$this->params['pass'][0];
			$list=$this->Invoice->query("SELECT invoice_id,invoice_number,state,type,invoice.client_id,invoice.invoice_time,invoice.invoice_start,
			                            invoice.invoice_end, invoice.total_amount::numeric(20,2)  as amount1,   invoice.paid,invoice.due_date,
                                 invoice.pay_amount::numeric(20,2),invoice.credit_amount::numeric(20,2),current_balance::numeric(20,2),client.name as client_name,invoice.cdr_path  from  invoice 
			                             left join client on client.client_id=invoice.client_id where invoice_id=$invoice_id   ");
			if(count($list)==0){
				$this->Invoice->create_json_array('#ClientOrigRateTableId',101,' invoice is corrupted or does not exists.');
				$this->Session->write("m",Invoice::set_validator ());
				$this->redirect('/invoices/view');	
			}
			$this->set('list',$list);
		}
	}
	function invoice_options($client_id=null){
		Configure::write('debug',2);
		$this->layout='ajax';
		$conditions=Array(' paid=false ');
		if(!empty($client_id)){
			$conditions[]="client_id =$client_id";
		}
		$start_date=$this->_get('start_date');
		if(!empty($start_date)){
			$conditions[]="invoice_start>'$start_date'";
		}
		$end_date=$this->_get('end_date');
		if(!empty($end_date)){
			$conditions[]="invoice_end<'$end_date'";
		}
		$invoice_type=$this->_get('invoice_type');
		if('' != $invoice_type){
			if(intval($invoice_type)===1){
				$conditions[]="type = 1";
			}else{
				$conditions[]="type = 0";
			}
		}
		$invoice_paid=$this->_get('invoice_paid');
		if(!empty($invoice_paid)){
			if($invoice_paid==1){
				$conditions[]="pay_amount >= total_amount";
			}else{
				$conditions[]="pay_amount < total_amount";
			}
		}
		$invoice_overdue=$this->_get('invoice_overdue');
		if(!empty($invoice_overdue)){
			$time=date('Y-m-d');
			if($invoice_overdue==2){
				$conditions[]="due_date>='$time'";
			}else{
				$conditions[]="due_date<'$time'";
			}
		}
		$active=$this->_get('active');
		if(!empty($active)){
			$conditions[]="paid =$active";
		}
		$conditions=join($conditions,' and ');
		if(!empty($conditions)){
			$conditions="where $conditions";
		}
		$this->set('Invoice',$this->Invoice->query("select * from invoice $conditions"));
	}

	
		

	
	
	public function view(){
		$create_type='0';
		if(isset($this->params['pass'][0])){
			$create_type=$this->params['pass'][0];
		}
		$results = $this->Invoice->getInvoices ($create_type,$this->_order_condtions(array('invoice_number','paid','type','client','invoice_start','total_amount','invoice_time')));
		$this->set ( 'p', $results);
		$this->set ( 'create_type', $create_type);
	}
	
	public function del($id){
		if ($this->Invoice->del($id)){
			$this->Invoice->create_json_array('',201,__('del_suc',true));
		} else {
			$this->Invoice->create_json_array('',101,__('del_fail',true));
		}
		$this->Session->write('m',Invoice::set_validator());
		$this->redirect('/invoices/view');
	}
	
function _move_upload_file($model){
		$model_name = $model;
		App::import("Core","Folder");
		$path = APP.'tmp'.DS.'upload'.DS.$model_name.DS.gmdate("Y-m-d",time());				
		if(new Folder($path,true,0777)){
			//$file[0] = $path . DS . time() . ".pdf";
			$file = $path . DS . time() . ".csv";
			//move_uploaded_file($_FILES ["file"] ["tmp_name"], $file);
//			if (!move_uploaded_file($_FILES ["attach"] ["tmp_name"], $file[0]))
//			{
//				$file[0] = '';
//			}
			if (!move_uploaded_file($_FILES ["attach_cdr"] ["tmp_name"], $file))
			{
				$file = '';
			}
			return $file;
		}else{
			throw new Exception("Create File Error,Please Contact Administrator.");
		}
	}
	function invoice_info($id=null){
		Configure::write('debug',0);
		$this->layout='ajax';
		$conditions=Array();
		if(!empty($id)){
			$conditions[]="invoice.invoice_id='$id'";
		}
		$invoice_number=$this->_get('invoice_number');
		if($invoice_number){
			$conditions[]="invoice.invoice_number='$invoice_number'";
		}
		$conditions=join($conditions,' and ');
		if(!empty($conditions)){
			$conditions=' and '.$conditions;
		}
		$sql="select invoice.client_id, client.name  as client_name ,invoice_number,invoice_start,invoice_end,pay_amount,current_balance,due_date,paid
from invoice left join client on invoice.client_id =client.client_id where 1=1 $conditions" ;
		$this->data=$this->Invoice->query($sql);
	}
	
}
?>