<?php
	class Bonus extends AppModel{
		var $name = 'Bonus';
		var $useTable = 'bonus_strategy';
		var $primaryKey = 'bonus_strategy_id';
		
		public function getAll($edit_id=null){
			if (!empty($edit_id)){
				return $this->query("select * from bonus_strategy where bonus_strategy_id = $edit_id");
			}
			return $this->query("select * from bonus_strategy");
		}
		
		public function add(){
			$name = $_REQUEST['name'];//策略名称
			$payment = $_REQUEST['payment'];//充值金额
			$point = $_REQUEST['point'];//换取的积分
			
			//检查是否已经存在
			$exists = $this->query("select bonus_strategy_id from bonus_strategy where name='$name'");
			if (count($exists) > 0) {
				return __('strategyexists',true)."|false";
			}
			
			$qs = $this->query("insert into bonus_strategy (name,refill_amount,gift_points,reseller_id)
															values('$name',$payment,$point,{$_SESSION['sst_reseller_id']})");
			
			if (count($qs) == 0){
				return __('add_suc',true)."|true";
			}
			
			return __('add_fail',true)."|false";
		}
		
		public function update(){
			$name = $_REQUEST['name'];//策略名称
			$payment = $_REQUEST['payment'];//充值金额
			$point = $_REQUEST['point'];//换取的积分
			$id = $_REQUEST['id'];
			
			$old = $this->query("select name from bonus_strategy where bonus_strategy_id = $id");
			if ($old[0][0]['name'] != $name){
				//检查是否已经存在
				$exists = $this->query("select bonus_strategy_id from bonus_strategy where name='$name'");
				if (count($exists) > 0) {
					return __('strategyexists',true)."|false";
				}
			}
			
			$qs = $this->query("update bonus_strategy set name='$name',refill_amount=$payment,gift_points=$point where bonus_strategy_id = $id");
			
			if (count($qs) == 0){
				return __('update_suc',true)."|true";
			}
			
			return __('update_fail',true)."|false";
		}
	} 
?>