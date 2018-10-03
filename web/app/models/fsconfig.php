<?php
class Fsconfig extends AppModel{
	var $name = 'Fsconfig';
	var $useTable = 'sip_error_code';
	var $primaryKey='sip_error_code_id';
	

	
	
	public function get_config_info(){
	//	$fails = "select route_fail_strategy_id from route_fail_strategy";
			$fails = "";
		$sip_codes_1 = "select * from sip_error_code where sip_error_code_id = 1";
		$sip_codes_2 = "select * from sip_error_code where sip_error_code_id = 2";
		$sip_codes_3 = "select * from sip_error_code where sip_error_code_id = 3";
		$sip_codes_4 = "select * from sip_error_code where sip_error_code_id = 4";
		return array(array(),$this->query($sip_codes_1),$this->query($sip_codes_2),$this->query($sip_codes_3),$this->query($sip_codes_4));
	}
	function _save_code($type,$code,$text)
	{
		if(empty($text))
		{
			$text='Temporarily Failure';
		}
		$this->invalidFields();
		$rec='/^[0-9]{1,}$/';
		if(!preg_match($rec,$code))
		{
			return false;
		}
		$data=Array('return_code'=>$code,'return_text'=>$text);
		$list=$this->find('first');
		if(!empty($list))
		{
			$data['sip_error_code_id']=$list['Fsconfig']['sip_error_code_id'];	
		}
		if($this->save($data)===false)
		{
			throw new Exception("数据插入失败！");
		}
		$this->id=false;
		return true;
	}
	public function updateSipCode($f){
		$return_codes = array();
		try{
			$this->begin();
			$this->_save_code(self::SIP_ERROR_CODE_TYPE_SIP_NOROUTE_CODE,$f['sip_noroute_code'],$f['sip_noroute_text']);
			$this->_save_code(self::SIP_ERROR_CODE_TYPE_SIP_NOINGRESS_CODE,$f['sip_noingress_code'],$f['sip_noingress_text']);
			$this->_save_code(self::SIP_ERROR_CODE_TYPE_SIP_CONNECTEDFAIL_CODE,$f['sip_connectedfail_code'],$f['sip_connectedfail_text']);
			$this->commit();
		}catch(Exception $e){
			$this->rollback();
			return false;
		}
		return true;
	}
	
	public function config_fail($id) {
		$this->begin();
		$qs = $this->query('delete from route_fail_strategy');
		$qss = $this->query("insert into route_fail_strategy (route_fail_strategy_id) values ('$id')");
		if (count($qs) == 0  && count($qss) == 0)
			$this->commit();
		else $this->rollback();
		
		return count($qs)+count($qss) == 0;
	}
	
/*	public function get_internationals(){
		$sql = "select international_prefix_id,prefix,prefix_type
							 from international_prefix";
		return $this->query($sql);
	}*/
	
	public function add(){
		$prefix = $_REQUEST['prefix'];
		$prefix_type = $_REQUEST['prefix_type'];
		$check_exists_sql = "select international_prefix_id
																 from international_prefix 
																 where prefix <@ '$prefix'";
		$check_exists = $this->query($check_exists_sql);
		if (count($check_exists) > 0) {
			return __('configuredalready',true)."|false";
		} 
		$insert_sql = "insert into international_prefix(prefix,prefix_type) 
												values('$prefix',$prefix_type)";
		$result = $this->query($insert_sql);

		if (count($result) == 0){
			return __('configuredsuc',true)."|true";
		}
		return __('configuredfail',true)."|false";
	}
	
	
	public function add_forbidden(){
		$prefix = $_REQUEST['prefix'];
		$check_exists_sql = "select forbidden_number_id
																 from forbidden_number 
																 where forbidden_number = '$prefix'";
		$check_exists = $this->query($check_exists_sql);
		if (count($check_exists) > 0) {
			return __('fconfiguredalready',true)."|false";
		} 
		$insert_sql = "insert into forbidden_number(forbidden_number) 
												values('$prefix')";
		$result = $this->query($insert_sql);

		if (count($result) == 0){
			return __('configuredfsuc',true)."|true";
		}
		return __('configuredffail',true)."|false";
	}
	
	public function add_forbidden_ani(){
		$prefix = $_REQUEST['prefix'];
		$check_exists_sql = "select forbidden_ani_id
																 from forbidden_ani 
																 where forbidden_number = '$prefix'";
		$check_exists = $this->query($check_exists_sql);
		if (count($check_exists) > 0) {
			return __('fconfiguredalready',true)."|false";
		} 
		$insert_sql = "insert into forbidden_ani(forbidden_number) 
												values('$prefix')";
		$result = $this->query($insert_sql);

		if (count($result) == 0){
			return __('configuredfsuc',true)."|true";
		}
		return __('configuredffail',true)."|false";
	}
	public function update(){
		$prefix = $_REQUEST['prefix'];
		$prefix_type = $_REQUEST['prefix_type'];
		$id = $_REQUEST['id'];
			$check_exists_sql = "select international_prefix_id
																 from international_prefix 
																 where prefix <@ '$prefix'  and  international_prefix_id<>$id";
			$check_exists = $this->query($check_exists_sql);
			if (count($check_exists) > 0) {
				return __('configuredalready',true)."|false";
			} 
			
			$update_sql = "update international_prefix set prefix = '$prefix',prefix_type=$prefix_type
													where international_prefix_id =  $id";
			
			$qs = $this->query($update_sql);
			
			if (count($qs)==0){
				return __('update_suc',true)."|true";
			}
			return __('update_fail',true)."|false";
	
	}
	
	
	public function update_forbidden(){
		$prefix = $_REQUEST['prefix'];
		$id = $_REQUEST['id'];
		
		$check_change_sql = "select forbidden_number from forbidden_number 
																where forbidden_number_id =  $id";
		
		$qs = $this->query($check_change_sql);
		if ($qs[0][0]['prefix'] != $prefix){
			$check_exists_sql = "select forbidden_number_id
																 from forbidden_number 
																 where forbidden_number = '$prefix'";
			$check_exists = $this->query($check_exists_sql);
			if (count($check_exists) > 0) {
				return __('fconfiguredalready',true)."|false";
			} 
			
			$update_sql = "update forbidden_number set forbidden_number = '$prefix'
													where forbidden_number_id =  $id";
			
			$qs = $this->query($update_sql);
			
			if (count($qs)==0){
				return __('update_suc',true)."|true";
			}
			return __('update_fail',true)."|false";
		}
	}
	public function update_forbidden_ani(){
		$prefix = $_REQUEST['prefix'];
		$id = $_REQUEST['id'];
		
		$check_change_sql = "select forbidden_number from forbidden_ani 
																where forbidden_ani_id =  $id";
		
		$qs = $this->query($check_change_sql);
		if ($qs[0][0]['prefix'] != $prefix){
			$check_exists_sql = "select forbidden_ani_id
																 from forbidden_ani 
																 where forbidden_number = '$prefix'";
			$check_exists = $this->query($check_exists_sql);
			if (count($check_exists) > 0) {
				return __('fconfiguredalready',true)."|false";
			} 
			
			$update_sql = "update forbidden_ani set forbidden_number = '$prefix'
													where forbidden_ani_id =  $id";
			
			$qs = $this->query($update_sql);
			
			if (count($qs)==0){
				return __('update_suc',true)."|true";
			}
			return __('update_fail',true)."|false";
		}
	}
	
	public function del_it($id,$type){
		$type == 1?
			$sql = "delete from international_prefix where international_prefix_id = $id"
		:$sql = "delete from forbidden_number where forbidden_number_id =  $id";
		
		$qs = $this->query($sql);
		if (count($qs)==0) $this->create_json_array('',201,__('del_suc',true));
		else $this->create_json_array('',101,__('del_fail',true));
		$_SESSION['m'] = Fsconfig::set_validator();
	}
	
	public function get_forbidden(){
		return $this->query("select forbidden_number_id,forbidden_number from forbidden_number");
	}
}