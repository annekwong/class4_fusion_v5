<?php
class LocationReport extends AppModel {
	var $name = 'LocationReport';
	var $useTable = 'client_cdr';
	var $primaryKey = 'id';
	var $order = "id DESC";
	
	var $default_schema = array(
//	'ref_id' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11),
    'translation_id' => array ( 'name' => 'Translation Name', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    'ani' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    'dnis' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    'action_ani' => array (  'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 30, ),
    'action_dnis' => array (  'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 30, ),
    'ani_method' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    'dnis_method' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    );
	
    
    
	function get_datas($time,$options=array()) {		
		$ext = $this->parse_ext($options);
		pr($ext);
		extract($ext);
		
		$options['col'] = strtolower(trim($options['col']));
		
			//$options=array('total_duration','total_calls','total_cdr_cost','total_egress_cost','profit','profit_percentage');
		foreach ($options  as  $key=>$value){
				switch($options[$key]){
			case 'total_duration':
				$select=" (sum(case when call_duration='' then 0 else call_duration::numeric end )/60 ) ::numeric(20,2) as total_duration";
				break;
			case 'acd':
				$select="SUM( CASE WHEN call_duration=''   THEN  0  ELSE call_duration::numeric END )/60/count(*)  AS  acd";						
				break;
			case 'call_attempt':
				$select='count(*) as call_attempt';				
				break;
			case 'success_call':
				$select='COUNT( CASE WHEN answer_time_of_date::bigint>0  THEN  answer_time_of_date  ELSE NULL END )::numeric AS success_call';				
				break;
			case 'failed_call':
				$select='COUNT( CASE WHEN answer_time_of_date::bigint=0  THEN  answer_time_of_date  ELSE NULL END )::numeric AS failed_call';				
				break;
			case 'call_duration':
				$select="(SUM (CASE WHEN call_duration=''   THEN  0  ELSE call_duration::numeric END))::numeric/60   AS  call_duration";				
				break;
			case 'avg_buy_price':
				$select="SUM(ingress_client_cost::numeric)/((SUM (CASE WHEN call_duration='' THEN  0  ELSE call_duration::numeric END))::numeric/60) AS avg_buy_price";				
				break;
			case 'avg_sell_price':
				$select="SUM(egress_cost::numeric)/((SUM (CASE WHEN call_duration=''   THEN  0  ELSE call_duration::numeric END))::numeric/60) AS avg_sell_price";				
				break;
			case 'total_buy_volume':
				$select='SUM(ingress_client_cost::numeric)  AS  total_buy_volume';				
				break;
			case 'total_sell_volume':				
				$select='SUM(egress_cost::numeric)  AS total_sell_volume';
				break;
			default :
				$select="(SUM (CASE WHEN call_duration=''   THEN  0  ELSE call_duration::numeric END))::numeric/60   AS  call_duration";
				break;		
		}	
			
			
		}
		
		$sql = $this->get_cdr_sql(compact('ext_select','ext_conditions','select','time'));
//		echo $sql;	
		return $this->query($sql);		
	}
	
	
    
    
    
    
    
    
    
    
    
	
//'0－－忽略
//1－－部分替换
//2－－全部替换';
	const METHOD_STATUS_IGNORE = 0;
	const METHOD_STATUS_COMPARE = 1;
	const METHOD_STATUS_REPLACE = 2;
	
	function _format_method($value){
		switch($value){
			case self::METHOD_STATUS_IGNORE: return 'Rgnore';
			case self::METHOD_STATUS_COMPARE: return 'Compare';
			case self::METHOD_STATUS_REPLACE: return 'Replace';
			default : return 'Unknown';
		}
	}
	
	function format_ani_method_for_download($value,$data){
		return $this->_format_method($value);
	}
	
	function format_dnis_method_for_download($value,$data){
		return $this->_format_method($value);
	}
}
?>