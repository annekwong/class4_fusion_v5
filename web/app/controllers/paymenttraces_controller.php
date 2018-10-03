<?php
	class PaymenttracesController extends AppController{
		var $name = 'Paymenttraces';
		var $uses = array();
		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
		
		
		/**
		 * 查看支付平台
		 */
		public function view_trace(){
			$this->loadModel('Cdr');
			
			$sql = "select *,
														(
														 select count(trace_name) 
														 from payment_trace 
														 where payment_platform_new_id = p.payment_platform_new_id 
														) as traces
								from payment_platform_new as p";
			
			$this->set('traces',$this->Cdr->query($sql));
		}
		
		/**
		 * 启用或者禁用支付平台
		 * @param integer $id 平台ID
		 * @param boolean $active 更新状态
		 */
		public function active_or_not($id,$active){
			$this->loadModel('Cdr');
			
			$sql = "update payment_platform_new 
									set status = $active 
								where payment_platform_new_id = $id";
			$result = $this->Cdr->query($sql);
			
			count($result)>0
			? 
				$this->Cdr->create_json_array('',101,__('manipulated_fail',true))
			:
				$this->Cdr->create_json_array('',201,__('manipulated_suc',true));
			
			$this->Session->write('m',Cdr::set_validator());
			
			$this->redirect('/paymenttraces/view_trace');
		}
		
		/**
		 * 查看平台下支持的通道
		 * @param integer $trace_id
		 */
		public function view_way($trace_id){
			$this->loadModel('Cdr');
			
			$sql = "select * from payment_trace 
								where payment_platform_new_id = $trace_id";
			
			$this->set('ways',$this->Cdr->query($sql));
		}
		
	/**
		 * 启用或者禁用支付通道
		 * @param integer $id 平台ID
		 * @param boolean $active 更新状态
		 */
		public function trace_active_or_not($pid,$id,$active){
			$this->loadModel('Cdr');
			
			if ($active == 'true') {
				$trace_type = $this->Cdr->query("select trace_type from payment_trace where payment_trace_id = $id");
				$status = $this->Cdr->query("select status from payment_trace where payment_trace_id != $id and trace_type = '{$trace_type[0][0]['trace_type']}'");
				if ($status[0][0]['status'] == true) {
					$this->Cdr->create_json_array('',101,__('activedalready',true));
					$this->Session->write('m',Cdr::set_validator());
					$this->redirect('/paymenttraces/view_way/'.$pid); 
				}				
			}
			
			$sql = "update payment_trace
									set status = $active 
								where payment_trace_id = $id";
			$result = $this->Cdr->query($sql);
			
			count($result)>0
			? 
				$this->Cdr->create_json_array('',101,__('manipulated_fail',true))
			:
				$this->Cdr->create_json_array('',201,__('manipulated_suc',true));
			
			$this->Session->write('m',Cdr::set_validator());
			
			$this->redirect('/paymenttraces/view_way/'.$pid);
		}
	} 
?>