<?php
	class JurisdictioncountrysController extends AppController{
		
		//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
		if($login_type==1){					//admin
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
				$this->Jurisdictioncountry->create_json_array('#ClientOrigRateTableId',101,'Please fill \"alais\" field correctly (only  digits allowed).');
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
		$delete_rate_id=$_POST['delete_rate_id'];
		$delete_rate_id=substr($delete_rate_id,1);
		$tmp = (isset($_POST ['rates']))?$_POST ['rates']:'';
		$size = count ( $tmp );
			try{
			$this->Jurisdictioncountry->begin();
				$this->Jurisdictioncountry->query("delete from  jurisdiction_upload ");
			$this->Jurisdictioncountry->deleteAll('1=1');
			if(empty($tmp)){
				$tmp=Array();				
			}
			foreach ( $tmp as $el ) {
				$this->data['Jurisdictioncountry'] = $el;
				
				

	
		
				if(!$this->Jurisdictioncountry->xsave( $this->data ['Jurisdictioncountry'] )){
					throw new Exception("name is already in use! ");
				}
		   		$this->data['Jurisdictioncountry']['id'] = false;
		   		
			}
			$this->Jurisdictioncountry->commit();
			$this->Jurisdictioncountry->create_json_array ('#ClientOrigRateTableId', 201, 'Action Success' );
			}catch(Exception $e){
				//$this->Jurisdictioncountry->create_json_array ($e->getMessage(), 101, 'Action Error' );
				$this->Jurisdictioncountry->rollback();
			}
		 $this->xredirect ("/jurisdictioncountrys/view?page={$_GET['page']}&size={$_GET['size']}" );
	}
	
		public function  view() {
		$this->set('p',$this->Jurisdictioncountry->view($this->_order_condtions(array('id','name'))));
			//$this->set('post',$this->Systemlimit->query("select  * from  jurisdiction"));
	}
	
	} 
?>
