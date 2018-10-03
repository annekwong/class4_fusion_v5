<?php
	class CdrbackupsController extends AppController{
		var $uses = array('Cdr');
		var $components = array('PhpDownload');
		
		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
		
		public function backup(){
			$this->pageTitle="Configuration/CDR Backup";
			if (isset($this->params['url']['filter_range_ti_start_time_of_date_start'])) {				
				$this->_catch_exception_msg(array('CdrbackupsController','_backup_impl'));
			}			
		}
		
		function _backup_impl($params = array()){
			Configure::write('debug',0);
//  			$this->layout='';
			$conditions = $this->_filter_conditions(array('range_ti_start_time_of_date'));
			if(!empty($conditions) && $this->Cdr->download_by_sql("SELECT * FROM client_cdr".date("Ymd")." WHERE 1=1 AND $conditions",array('objectives'=>'client_cdr'))){
				exit(1);
			}else{					
				$this->Cdr->create_json_array('',101,'please choice time');
				$this->Session->write('m',Cdr::set_validator());
//				$this->layout='default';
//				$this->autoRender = true;
			}
		}
		
//		public function download(){
////		Configure::write('debug',0);
//  		$this->layout='';
//		if (!empty($this->params['form'])) {				
//				$f = $this->params['form'];
//				$t = $f['t'];
//				$sql_cmd = "select * from cdr where 1=1";
//				if ($t == 0){//自定义
//					if (!empty($f['start_time'])){
//						$start_time = $f['start_time'];
//						$sql_cmd .= " and start_time_of_date >= '$start_time'";
//					}
//						
//					if (!empty($f['end_time'])){
//						$end_time = $f['end_time'];
//						$sql_cmd .= " and start_time_of_date <= '$end_time'";
//					}
//				} else {
//					$wm = $f['wm'];
//					$n = date('Y-m-d',time()+6*60*60).' 00:00:00';
//					if ($wm == 'w'){
//						$sql_cmd .= " and start_time_of_date <= (timestamp '$n' - interval '$t week')::character varying";
//					} else {
//						$sql_cmd .= " and start_time_of_date <= (timestamp '$n' - interval '$t month')::character varying";
//					}	
//				}
//				$this->loadModel('Cdr');
//				$this->Cdr->export__sql_data('Download Cdr',$sql_cmd,'cdr_backup');
////				$qs = $this->Cdr->query("copy ($sql_cmd) to '$file_dir$file_name'");
////				if (count($qs) == 0)
////					$this->Cdr->create_json_array('',201,__('backupsuc',true));
////				else
////					$this->Cdr->create_json_array('',101,__('backupfail',true));
////				
////				$this->Session->write('m',Cdr::set_validator());
////				$this->redirect('/cdrbackups/backup');
//			}
//		}
	} 
?>