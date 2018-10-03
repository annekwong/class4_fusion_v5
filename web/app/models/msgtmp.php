<?php
	class Msgtmp extends AppModel{
		var $name = 'Msgtmp';
		var $useTable = 'msg_template';
		var $primaryKey = 'msg_template_id';
		
		/**
		 * 查看短信费率模板
		 */
		public function view_content(){
			$view_balance = "select * from msg_template where tmp_type = 1";
			$find_pass = "select * from msg_template where tmp_type = 2";
			$acc_reg = "select * from msg_template where tmp_type = 3";
			
			return array($this->query($view_balance),
											 $this->query($find_pass),
											 $this->query($acc_reg));
		}
		
		
		/**
		 * 
		 * @param 提交的表单数据  $f
		 */
		public function update_tmp($f){
			$balance_tmp = $f['balance'];//查询余额模板
			$pass_tmp = $f['pass'];//找回密码模板
			$qs_count = 0;
			$qs = $this->query("delete from msg_template where tmp_type in (1,2)");
			$qs_count += count($qs);
			
			$qs = $this->query("insert into msg_template (tmp_type,tmp_content) values(1,'$balance_tmp')");
			$qs_count += count($qs);
			
			$qs = $this->query("insert into msg_template (tmp_type,tmp_content) values(2,'$pass_tmp')");
			$qs_count += count($qs);
			
			return $qs_count == 0;
		}
	} 
?>