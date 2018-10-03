<?php
	class Msginterface extends AppModel{
		var $name = 'Msginterface';
		var $useTable = 'msg_interface';
		var $primaryKey = 'msg_interface_id';
		
		public function getAll(){
			return $this->query("select * from msg_interface");
		}
		
		public function add(){
			$username = empty($_REQUEST['username'])?'null':"'".$_REQUEST['username']."'";
			$pw = empty($_REQUEST['pw'])?'null':"'".$_REQUEST['pw']."'";
			$url = $_REQUEST['r_url'];
			
			$sql = "insert into msg_interface(username,pw,url) values($username,$pw,'$url')";
			
			$qs = $this->query($sql);
			
			if (count($qs) == 0){
				return __('add_suc',true)."|true"; 
			} else {
				return __('add_fail',true)."|false";
			}
		}
		
		public function update(){
			$username = empty($_REQUEST['username'])?'null':"'".$_REQUEST['username']."'";
			$pw = empty($_REQUEST['pw'])?'null':"'".$_REQUEST['pw']."'";
			$url = $_REQUEST['r_url'];
			$id = $_REQUEST['id'];
			
			$sql = "update msg_interface set username = $username,pw = $pw,url='$url' where msg_interface_id = $id";
			
			$qs = $this->query($sql);
			
			if (count($qs) == 0){
				return __('update_suc',true)."|true"; 
			} else {
				return __('update_fail',true)."|false";
			}
		}
		
		public function active($id){
			$qs_count = 0;
			$qs = $this->query("update msg_interface set status = false");
			$qs_count += count($qs);
			
			$this->query("update msg_interface set status = true where msg_interface_id = $id");
			$qs_count += count($qs);
			
			return $qs_count == 0;
		}
	} 
?>