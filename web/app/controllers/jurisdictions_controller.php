<?php
	class JurisdictionsController extends AppController{
		
		//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_locationPrefixes');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
		
	
	
	
	public  function validate_jur(){
	$flag='true';
	$tmp = (isset($_POST ['rates']))?$_POST ['rates']:'';
	$size = count ( $tmp );
	foreach ( $tmp as $el ) {
	$this->data['Jurisdiction'] = $el;
	if($this->data['Jurisdiction']['alias']==''){
	$this->Jurisdiction->create_json_array('#ClientOrigRateTableId',101,'Please fill \"alais\" field correctly (only  digits allowed).');
	$flag='false';
	}else{
	$c = $this->check_alias ($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['alias']);
  if ($c != 0) {
			$this->create_json_array ('#ClientName', 301, __ ( 'checkclientname', true));
			$error_flag = 'false';
					}

		}
			$c = $this->check_name($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['name']);
		    if ($c != 0) {
			$this->create_json_array ('#ClientName', 301, __ ( 'checkclientname', true));
			$error_flag = 'false';
				
			}
	
	return $flag;
}
	}
	public function add(){
		if(empty($_POST['jurisdiction_country_id'])){
			$this->redirect('/rates/rates_list/');
		}
			$country_id=$_POST['jurisdiction_country_id'];
			//check
/*		if($this->validate_rate()=='false'){
			$this->Session->write ( "m", Jurisdiction::set_validator () );
			$this->redirect ( "/systemlimits/jurisdiction_view/$country_id?page={$_GET['page']}&size={$_GET['size']}" );
		};*/
	
		$delete_rate_id=$_POST['delete_rate_id'];
		$delete_rate_id=substr($delete_rate_id,1);
		$tmp = (isset($_POST ['rates']))?$_POST ['rates']:'';
		$size = count ( $tmp );
			foreach ( $tmp as $el ) {
				
				$this->data['Jurisdiction'] = $el;
				$this->data['Jurisdiction']['jurisdiction_country_id'] = $country_id;
				

				
				$this->Jurisdiction->save( $this->data ['Jurisdiction'] );
		   $this->data['Jurisdiction']['id'] = false;
			}
			if(!empty($delete_rate_id)){
				$this->Jurisdiction->query("delete  from  jurisdiction where id in($delete_rate_id)");
			}
			
			$this->Jurisdiction->create_json_array ( '#ClientOrigRateTableId', 201, 'Action Success' );
			$this->Session->write ( "m", Jurisdiction::set_validator ());
		 $this->redirect ( "/systemlimits/jurisdiction_view/$country_id?page={$_GET['page']}&size={$_GET['size']}" );
	}
	
	
	
	
	
	
	
	} 
?>