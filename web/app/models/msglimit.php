<?php
	class Msglimit extends AppModel{
		var $name = 'Msglimit';
		var $useTable = 'msg_limit';
		var $primaryKey = 'msg_limit_id';
		
		public function getLimits($edit_id=null){
			if (!empty($edit_id)){
				return $this->query("select * from msg_limit where msg_limit_id = $edit_id");
			}
			return $this->query("select * from msg_limit");
		}
		
		public function add(){
			$limit = $_REQUEST['limit'];//限制字符
			$replace = $_REQUEST['reaplace'];//替换字符
			
			//检查是否已经存在
			$exists = $this->query("select msg_limit_id from msg_limit where limit_char='$limit'");
			if (count($exists) > 0) {
				return __('limitcharalready',true)."|false";
			}
			
			$qs = $this->query("insert into msg_limit (limit_char,replace_char) values('$limit','$replace')");
			
			if (count($qs) == 0){
				return __('add_suc',true)."|true";
			}
			
			return __('add_fail',true)."|false";
		}
		
		public function update(){
			$limit = $_REQUEST['limit'];//限制字符
			$replace = $_REQUEST['reaplace'];//替换字符
			$id = $_REQUEST['id'];
			
			$old = $this->query("select limit_char from msg_limit where msg_limit_id = $id");
			if ($old[0][0]['limit_char'] != $limit){
				//检查是否已经存在
				$exists = $this->query("select msg_limit_id from msg_limit where limit_char='$limit'");
				if (count($exists) > 0) {
					return __('limitcharalready',true)."|false";
				}
			}
			
			$qs = $this->query("update msg_limit set limit_char='$limit',replace_char='$replace' where msg_limit_id = $id");
			
			if (count($qs) == 0){
				return __('update_suc',true)."|true";
			}
			
			return __('update_fail',true)."|false";
		}
	} 
?>