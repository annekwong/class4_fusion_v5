<?php
class ClientpaymentsController extends AppController
{
	var $name = 'Clientpayments';
	var $helpers = array('javascript','html');
	var $uses = array('Clientpayment');
	

	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
	  parent::beforeFilter();//调用父类方法
	}
		
	
	
	
	function add_payment($client_id=0){
		$this->pageTitle="Add payment";
		$this->set('invoice_client_id',$client_id);
		$this->set('invoice_no', isset($_REQUEST['invoice_no'])?$_REQUEST['invoice_no']:'');
		$this->set('invoice',$this->Clientpayment->invoice_options(Array("client_id =$client_id","paid=false")));
		$this->set('name',$this->queryName($client_id));
	}
	
	function make_payment()
	{
		$add = true;
		$this->Clientpayment->begin();
			$tmp = (isset($_POST ['accounts']))?$_POST ['accounts']:'';
			$size = count ( $tmp );
			foreach ( $tmp as $el ) {
				$this->data['Clientpayment']['client_id'] = $el['client_id'];
				$this->data['Clientpayment']['payment_type'] = $el ['payment_type'];
				$this->data['Clientpayment']['amount'] = $el ['amount'];
				$this->data['Clientpayment']['payment_time'] = $el ['payment_time'];
				$this->data['Clientpayment']['result'] = 'true';
				$this->data['Clientpayment']['approved'] = 'true';
				$this->data['Clientpayment']['invoice_number']=$el['invoice_number'];
				if(!$this->Clientpayment->add_payment($this->data['Clientpayment'])){
					$this->Clientpayment->rollback();
					$add = false;
					//return;
				}
			}
			if ($add)
			{
				$this->Clientpayment->create_json_array ( '#ClientOrigRateTableId', 201, 'The Payment is created successfully!' );
			}
			$this->Session->write ( "m", Clientpayment::set_validator () );
			
			$this->Clientpayment->commit();
		 $this->redirect ( "/transactions/client_pay_view" );
	}
	function add_payment_post() {
		$add = true;
			if(empty($_POST['invoice_client_id'])){
			  $this->redirect ( "/transactions/client_pay_view/" );
			}
			
			$client_id=$_POST['invoice_client_id'];
			$tmp = (isset($_POST ['accounts']))?$_POST ['accounts']:'';
			$size = count ( $tmp );
			$validate = true;
			foreach ( $tmp as $va )
			{
				if ($va['payment_type'] !=3 && !preg_match("/^\d+(\.?\d*)$/", $va['amount']))
				{
					$validate = false;//var_dump($va['amount']);
					$this->Clientpayment->create_json_array ( '#ClientOrigRateTableId', 201, 'The Transation Amount must be float.' );
					$this->Session->write ( 'm', Clientpayment::set_validator () ); 
					$this->redirect ( "add_payment/$client_id");
				}
				elseif (!preg_match("/^\-?\d+(\.?\d*)$/", $va['amount']))
				{
					$validate = false;//var_dump($va['amount']);
					$this->Clientpayment->create_json_array ( '#ClientOrigRateTableId', 201, 'The Transation Amount must be numeric.' );
					$this->Session->write ( 'm', Clientpayment::set_validator () ); 
					$this->redirect ( "add_payment/$client_id");
				}
			}
			if ($validate)
			{
				$this->Clientpayment->begin();
				foreach ( $tmp as $el ) {
					$this->data['Clientpayment']['result'] ='true' ;
					$this->data['Clientpayment']['approved'] ='true' ;
					$this->data['Clientpayment']['client_id'] = $client_id;
					$this->data['Clientpayment']['invoice_number'] = isset($el ['invoice_number'])?$el['invoice_number']:'';
					$this->data['Clientpayment']['payment_type'] = $el ['payment_type'];
					$this->data['Clientpayment']['amount'] = empty($el['amount']) ? 0 : $el['amount'];
					$this->data['Clientpayment']['payment_time'] = $el ['payment_time'];
					if($this->data['Clientpayment']['payment_type']!=1){
						$this->data['Clientpayment']['payment_time']='';
					}
				}
				if(!$this->Clientpayment->add_payment($this->data['Clientpayment'])){
						$add = false;
						$this->Clientpayment->rollback();
						//return;
				}
				$this->Clientpayment->commit();
				if ($add)
				{
					$this->Clientpayment->create_json_array ( '#ClientOrigRateTableId', 201, 'The Payment is created successfully!' );
				}
				$this->Session->write ( "m", Clientpayment::set_validator () );
			  $this->redirect ( "/transactions/client_pay_view/" );
			}
	}
	
	
	public function queryName($id=null){
	 if(!empty($id)){
       $sql="select name from  client  where client_id=$id";
       $list=$this->Clientpayment->query($sql);
		return $list;}
	}

}

?>
