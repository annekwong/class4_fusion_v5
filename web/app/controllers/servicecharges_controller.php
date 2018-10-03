<?php
class ServicechargesController extends AppController{
	var $name = 'Servicecharges';
	var $helper = array('html','javascript');
		
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_config_paymentTerm');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
	
	
	
	
public function del(){
$this->Servicecharge->query("delete from  service_charge  where  service_charge_id={$this->params['pass'][0]}");
	$this->Session->write('m',$this->Servicecharge->create_json(201,'Delete Success'));
$this->redirect(array('action'=>'view'));
}	
	



public function add(){
  if(!empty($this->data)&&$this->Servicecharge->validate_data($this->data)=='false'){
         $this->Servicecharge->save($this->data['Servicecharge']);
         
				$this->Session->write('m',$this->Servicecharge->create_json(201,'Add Success'));
   $this->redirect(array('action'=>'view'));
  }else{
  	$this->set ('m', Servicecharge::set_validator ()); //向界面设置验证信息
  }

}	
	

public function edit(){
  if(!empty($this->data)&&$this->Servicecharge->validate_data($this->data)=='false'){
         $this->Servicecharge->save($this->data['Servicecharge']);
         	$this->Session->write('m',$this->Servicecharge->create_json(201,'Update Success'));
         
         $this->redirect(array('action'=>'view'));
  
  }else{
  	
  	$id=$this->params['pass'][0];
  $this->Servicecharge->service_charge_id=$id;
  $this->data=$this->Servicecharge->read();
  $this->set ('m', Servicecharge::set_validator ()); //向界面设置验证信息
  }

}	


		public function view(){
			$order=$this->_order_condtions(Array('service_charge_id','name','buy_rate','less_buy_rate_fee','greater_buy_rate_fee','sell_rate','less_sell_rate_fee','greater_sell_rate_fee'));
			
	    $this->set('p',$this->Servicecharge->findAll($order));
	}
}