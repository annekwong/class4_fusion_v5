<?php
class ImportExportLog extends AppModel {	
	var $order = "id DESC";
	var $belongsTo = array('User');
	
	const STATUS_TO_SERVER = 0;#上传文件到服务器
	const STATUS_SUCCESS = 6;//数据库验证完成
	
	const STATUS_PROCESSING = 10;	
	
	const LOG_TYPE_EXPORT = 0;
	const LOG_TYPE_IMPORT = 1;

	
	public function modifyStatusAndEndtime($id,$pid)
        {
           $now_time = date('Y-m-d H:i:s',time()); 
            
           $data_arr = array('id'=> $id,'pid'=> $pid, 'status'=> 11,'finished_time' =>$now_time ); 
           
           $this->save($data_arr);
           
           $result = $this->getAffectedRows();
           
           return $result;
        }
	
	function find_all_process_log(){
		return $this->find("all",array('conditions' => "status > -1 and status < 2 and time::date = current_date"));
		
	}
	
	function find_all_import(){
		return $this->find("all",array('conditions' => "log_type = ".self::LOG_TYPE_IMPORT));
	}
	
	
	#文件处理中
	function is_processing($log){
		return $log['ImportExportLog']['status'] ==8||$log['ImportExportLog']['status'] ==9||$log['ImportExportLog']['status'] ==7;
	}
	
	function beforeSave(){
		if(isset($this->data[$this->alias]['ext_attributes'])){
			$this->data[$this->alias]['ext_attributes'] = serialize($this->data[$this->alias]['ext_attributes']);
		} 
		return true;
	}
	
	function afterFind($results){
		foreach ($results as $key => $val) {
			 if (isset($val[$this->alias]['ext_attributes'])) {
			 	$results[$key][$this->alias]['ext_attributes'] = unserialize($results[$key][$this->alias]['ext_attributes']);
			 }else{
			 	$results[$key][$this->alias]['ext_attributes'] = array();
			 }
                         
                         if(array_key_exists('ImportExportLog', $val)){
                             //var_dump($val['ImportExportLog']['finished_time']);
                             //$results[$key]['ImportExportLog']['finished_time'] = '0';
                             $finished_time = $val['ImportExportLog']['finished_time'];
                             
                             $finished_time = explode("+",$finished_time);
                             $finished_time[0] = substr($finished_time[0], 0, 19);
                             $results[$key]['ImportExportLog']['finished_time'] = implode("+", $finished_time);
                         }
		}
		return $results;
	}
}
?>