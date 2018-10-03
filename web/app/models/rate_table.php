<?php
// 名字被其他哥们儿占用了，  没办法。
class RateTable extends AppModel {
	var $name = 'RateTable';
	var $useTable = 'rate';
	var $primaryKey = 'rate_id';
	var $order = "rate_id DESC";

	var $validate = array(
//			'code_name' => array(
//				'blank' => array(
//					'required' => false,
//					'rule' => 'notEmpty',
//					'message' => 'code name cannot be NULL!',						
//					'last' => true
//					),
//				'alphaNumeric' => array(
//					'required' => true,
//					'rule' => '/^[\w\-\_\s]+$/',
//					'message' => 'code name must contain numeric characters only.'
//				)				
//			),
		'code' => array(
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
		'setup_fee' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'setup fee cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'setup fee must contain numeric only.'
			)
		),
		'min_time' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'min time cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'min time must contain numeric only.'
			)
		),
		'interval' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'interval cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'interval must contain numeric only.'
			)
		),
		'grace_time' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'grace time cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'grace time must contain numeric only.'
			)
		),
		'intra_rate' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'intrastate rate cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'intrastate rate must be float.'
			)
		),
		'inter_rate' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'interstate rate cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'interstate rate must be float.'
			)
		),
		'local_rate' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'local rate cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'local rate must be float.'
			)
		),
		'rate' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'rate cannot be NULL!',
				'last' => true
			),
			'float' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'rate must be float.'
			)
		),
	);

    var $default_schema = Array(
        'code' => array( 'type' => 'text', 'null' => '', 'default' => '', 'length' => '','default_fields' => 1),
        'code_name' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100,'default_fields' => 1),
        'country' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100,'default_fields' => 1),
        'rate' => array('type' => 'float', 'null' => '', 'default' => 0, 'length' => '','default_fields' => 1),
        'new_rate' => array('type' => 'float', 'null' => '', 'default' => 0, 'length' => ''),
        'setup_fee' => array( 'type' => 'float', 'null' => '', 'default' => 0, 'length' => ''),
        'effective_date' => array('type' => 'datetime', 'null' => '', 'default' => '','length' => '','default_fields' => 1,
            'sql' => "effective_date AT TIME ZONE INTERVAL '+00:00'"),
        'end_date' => array (  'type' => 'datetime',  'null' => 1,  'default' => '',  'length' => '',
            'sql' => "end_date AT TIME ZONE INTERVAL '+00:00'"),
        'min_time' => array (  'type' => 'integer',  'null' => '',  'default' => 0,  'length' => ''),
        'interval' => array (  'type' => 'integer',  'null' => '',  'default' => 1,  'length' => ''),
        'grace_time' => array (  'type' => 'integer',  'null' => '',  'default' => 0,  'length' => ''),
        'seconds' => array (  'type' => 'integer',  'null' => '',  'default' => 60,  'length' => ''),
        'time_profile_id' => array ( 'name' => "Profile", 'type' => 'integer',  'null' => '',  'default' => '',  'length' => '',
            'sql' => "(select name from time_profile where time_profile_id=rate.time_profile_id)"),
        'inter_rate' => array ('name' => 'Inter Rate', 'type' => 'float',  'null' => 1,  'default' => 0,  'length' => '','default_fields' => 1),
        'intra_rate' => array ('name' => 'Intra Rate', 'type' => 'float',  'null' => 1,  'default' => 0,  'length' => '','default_fields' => 1),
        'local_rate' => array (  'type' => 'float',  'null' => 1,  'default' => 0,  'length' => ''),
        'zone' => array ( 'name' => "RateZoneTime", 'type' => 'string',  'null' => '',  'default' => '',  'length' => ''),
        'ocn' => array ('type' => 'string',  'null' => 1,  'default' => '',  'length' => 10),
        'lata' => array ('type' => 'string',  'null' => 1,  'default' => '',  'length' => 10),
        'change_status' => array('type' => 'text', 'null' => '', 'default' => '', 'length' => '')
    );

    var $import_list = [
        'code' => array( 'type' => 'text', 'null' => '', 'default' => '', 'length' => '','default_fields' => 1),
        'code_name' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100,'default_fields' => 1),
        'country' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100,'default_fields' => 1),
        'rate' => array('type' => 'float', 'null' => '', 'default' => 0, 'length' => '','default_fields' => 1),
        'effective_date' => array('type' => 'datetime', 'null' => '', 'default' => '','length' => '','default_fields' => 1,
            'sql' => "effective_date AT TIME ZONE INTERVAL '+00:00'"),
        'end_date' => array (  'type' => 'datetime',  'null' => 1,  'default' => '',  'length' => '',
            'sql' => "end_date AT TIME ZONE INTERVAL '+00:00'"),
        'inter_rate' => array ('name' => 'Inter Rate', 'type' => 'float',  'null' => 1,  'default' => 0,  'length' => '','default_fields' => 1),
        'intra_rate' => array ('name' => 'Intra Rate', 'type' => 'float',  'null' => 1,  'default' => 0,  'length' => '','default_fields' => 1),
        'min_time' => array (  'type' => 'integer',  'null' => '',  'default' => 0,  'length' => ''),
        'interval' => array (  'type' => 'integer',  'null' => '',  'default' => 1,  'length' => ''),
        'local_rate' => array (  'type' => 'float',  'null' => 1,  'default' => 0,  'length' => ''),
    ];

	static $time_profiles = null;
	static $codes = array();

/////////////////////////// for download /////////////	


	function get_schema($jur_type = '')
	{
		$default_schema = $this->default_schema;
		switch ($jur_type)
		{
			case '0':
				$default_schema['change_status'] = array();
                unset($default_schema['intra_rate']);
                unset($default_schema['inter_rate']);
                unset($default_schema['local_rate']);
                unset($default_schema['ocn']);
                unset($default_schema['lata']);
                unset($default_schema['setup_fee']);
                unset($default_schema['grace_time']);
                unset($default_schema['seconds']);
                unset($default_schema['time_profile_id']);
                unset($default_schema['zone']);
//                unset($default_schema['change_status']);
                break;
			case '1':
				unset($default_schema['code_name']);
				unset($default_schema['country']);
                unset($default_schema['intra_rate']);
                unset($default_schema['inter_rate']);
                unset($default_schema['local_rate']);
				unset($default_schema['end_date']);
				unset($default_schema['lata']);
				unset($default_schema['setup_fee']);
				unset($default_schema['grace_time']);
				unset($default_schema['seconds']);
				unset($default_schema['time_profile_id']);
				unset($default_schema['zone']);
				unset($default_schema['change_status']);
				unset($default_schema['new_rate']);
                unset($default_schema['ocn']);
                unset($default_schema['lata']);
				break;
			case '2':
				$default_schema['rate']['name'] = 'IJ Rate';
                unset($default_schema['code_name']);
                unset($default_schema['country']);
                unset($default_schema['rate']);
                unset($default_schema['end_date']);
                unset($default_schema['lata']);
                unset($default_schema['setup_fee']);
                unset($default_schema['grace_time']);
                unset($default_schema['seconds']);
                unset($default_schema['time_profile_id']);
                unset($default_schema['zone']);
                unset($default_schema['change_status']);
                unset($default_schema['new_rate']);
                unset($default_schema['ocn']);
                unset($default_schema['lata']);
				break;
			case '3':
				$default_schema['rate']['name'] = 'IJ Rate';
				break;
			case '4':
				$default_schema['rate']['name'] = 'IJ Rate';
				unset($default_schema['intra_rate']);
				unset($default_schema['inter_rate']);
				unset($default_schema['local_rate']);
				break;
			default:$default_schema = $default_schema;
		}
		return $default_schema;
	}

	public function default_export_fields($jur_type = ''){
	    $default_fields = [
	        ['code', 'code_name', 'country', 'effective_date', 'end_date', 'interval', 'min_time'],
	        ['code', 'rate'],
	        ['code', 'inter_rate', 'intra_rate'],
        ];
	    return $default_fields[$jur_type];
    }

	//自定义下载方法
	function find_all_for_download($fields,$conditions,$order){
		$column=join(',',$fields);
		return  	$this->query("select  $column   from  rate,
	  ( select rate_table_id, code as table_code,max(effective_date) as max_effect from rate as RateTable
   where $conditions group by code,rate_table_id ) as table_rate  
   where rate.code=table_rate.table_code and rate.effective_date=table_rate.max_effect and rate.rate_table_id=table_rate.rate_table_id order by rate.code desc ");


	}



	/**
	 *
	 *
	 * 格式化时间段
	 * @param $value
	 * @param $data
	 */
	function format_time_profile_id_for_download($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$time_profiles){
			App::import("Model",'Timeprofile');
			$model = new Timeprofile;
			self::$time_profiles = $model->find("all");
		}
		foreach(self::$time_profiles as $time_profile ){
			if($time_profile['Timeprofile']['time_profile_id'] == $value){
				return $time_profile['Timeprofile']['name'];
			}
		}
	}




// for upload check duplicate
	function check_duplicate_for_upload($data){
		return $this->find("first",array('conditions' => "rate_table_id = ".$data['RateTable']['rate_table_id']." AND code = '{$data['RateTable']['code']}'"));
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
//	function after_save_for_upload($data){
//		if(!isset(self::$codes[$data[$this->alias]['rate_table_id']])){
//			self::$codes[$data[$this->alias]['rate_table_id']] = array();
//		}
//		self::$codes[$data[$this->alias]['rate_table_id']][] = $data[$this->alias]['code'];
//	}

	function format_time_profile_id_for_upload($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$time_profiles){
			App::import("Model",'Timeprofile');
			$model = new Timeprofile;
			self::$time_profiles = $model->find("all");
		}
		foreach(self::$time_profiles as $time_profile ){
			if($time_profile['Timeprofile']['name'] == $value){
				return $time_profile['Timeprofile']['time_profile_id'];
			}
		}
		throw new Exception('Profile '.$value." does not exist");
	}

	function get_foreign_name($id){
		return  $this->query(" select  name from  rate_table  where  rate_table_id=$id;");
	}
}
?>