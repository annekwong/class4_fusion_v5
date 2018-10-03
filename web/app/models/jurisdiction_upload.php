<?php

class JurisdictionUpload extends AppModel {
	var $name = 'JurisdictionUpload';
	var $useTable = 'jurisdiction_prefix';
	var $primaryKey = 'id';
	var $order = "id DESC";

	var $validate = array(			
			'jurisdiction_name' => array(
				'blank' => array(
					'required' => false,
					'rule' => 'notEmpty',
					'message' => 'jurisdiction name cannot be NULL!',						
					'last' => true
					),
				'alphaNumeric' => array(
					'required' => true,
					'rule' => '/^[\w\-\_\s]+$/',
					'message' => 'jurisdiction name must contain numeric characters only.'
				)				
			),
			
			
						'jurisdiction_country_name' => array(
				'blank' => array(
					'required' => false,
					'rule' => 'notEmpty',
					'message' => 'state  cannot be NULL!',						
					'last' => true
					),
				'alphaNumeric' => array(
					'required' => true,
					'rule' => '/^[\w\-\_\s]+$/',
					'message' => 'state  must contain numeric characters only.'
				)				
			),
			'prefix' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'prefix cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'prefix must contain numeric only.'
				)		
			),







		);
		

		
		
		
	var $default_schema = Array(
			'jurisdiction_name' => array( 'type' => 'string', 'null' => '', 'default' => '', 'length' => '50'),
			'jurisdiction_country_name' => array('name'=>'State', 'type' => 'string', 'null' => '', 'default' => '', 'length' => '50'),
			'prefix' => array( 'type' => 'string', 'null' => '', 'default' => '', 'length' => '50'),

		);

	static $time_profiles = null;
	static $codes = array();
	
/////////////////////////// for download /////////////	
	

	
// for upload check duplicate
	function check_duplicate_for_upload($data){
		//return false;
		return $this->find("first",array('conditions' => "prefix ='".$data['JurisdictionUpload']['prefix']."'"));	
	}
		// for effective
//		function check_duplicate_for_upload($data){
//		if(!isset(self::$codes[$data[$this->alias]['rate_table_id']])){
///			$this->find("all",array('conditions' => "rate_table_id = ".$data['RateTable']['rate_table_id'] , 'fields' => 'code'));
//			$rate_codes = $this->query("SELECT code FROM rate WHERE rate_table_id = ".$data[$this->alias]['rate_table_id']);
//			self::$codes[$data[$this->alias]['rate_table_id']] = array();
//			foreach($rate_codes as $rate_code){
//				self::$codes[$data[$this->alias]['rate_table_id']][] = $rate_code[0]['code'];	
//			}
//		}
//		return in_array($data[$this->alias]['code'],self::$codes[$data[$this->alias]]);
//	}
//
	function after_save_for_upload($data){
		$country=$data['JurisdictionUpload']['jurisdiction_name'];
		$state=$data['JurisdictionUpload']['state'];
		$prefix=$data['JurisdictionUpload']['prefix'];
			
		#add  jur country
		$d=array();
		App::import("Model",'Jurisdictioncountry');
		$model = new Jurisdictioncountry;
		$d['Jurisdictioncountry']['name']=$data['JurisdictionUpload']['jurisdiction_name'];
		$t=$model->find("first",array('conditions' => "name ='".$country."'"));
		if(!$t)
		{
			$model->save($d);
			$country_id=$model->getLastInsertID();
			
		}else{
				$country_id=$t['Jurisdictioncountry']['id'];
		}	
		
		#add  jur 
		$d=array();
		App::import("Model",'Jurisdiction');
		$model = new Jurisdiction;
		$d['Jurisdiction']['alias']=$data['JurisdictionUpload']['state'];
		$d['Jurisdiction']['name']=$data['JurisdictionUpload']['state'];
		$d['Jurisdiction']['jurisdiction_country_id']=$country_id;
		$t=$model->find("first",array('conditions' => "name ='".$state."'"));
		if(!$t)
		{
			$model->save($d);
			$jur_id=$model->getLastInsertID();
			
		}else{
				$jur_id=$t['Jurisdiction']['id'];
		}	
		
		
				#add  jur_prefix 
		$d=array();
		App::import("Model",'Jurisdictionprefix');
		$model = new Jurisdictionprefix;
		$d['Jurisdictionprefix']['alias']=$data['JurisdictionUpload']['prefix'];
		$d['Jurisdictionprefix']['prefix']=$data['JurisdictionUpload']['prefix'];
		$d['Jurisdictionprefix']['jurisdiction_country_id']=$country_id;
		$d['Jurisdictionprefix']['jurisdiction_id']=$jur_id;
		$t=$model->find("first",array('conditions' => "prefix ='".$prefix."'"));
		if(!$t)
		{
			$model->save($d);
		}else{
		}	
		
//		if(!isset(self::$codes[$data[$this->alias]['rate_table_id']])){
//			self::$codes[$data[$this->alias]['rate_table_id']] = array();
//		}
//		self::$codes[$data[$this->alias]['rate_table_id']][] = $data[$this->alias]['code'];
	}
	

}
?>