<?php
class TranslationItem extends AppModel {
	var $name = 'TranslationItem';
	var $useTable = 'translation_item';
	var $primaryKey = 'ref_id';
	var $order = "ref_id DESC";
	
	var $default_schema = array(
//	'ref_id' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11),
//    'translation_id' => array ( 'name' => 'Translation Name', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
//'translation_id' => array ( 'name' => 'Translation ID', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    'ani' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    'dnis' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    'action_ani' => array ('name'=>'Translated ANI',  'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 30, ),
    'action_dnis' => array ('name'=>'Translated DNIS', 'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 30, ),
    'ani_method' => array ('name'=>'ANI Action',  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    'dnis_method' => array ('name'=>'DNIS Action',  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
    );
	
	
//'0－－忽略
//1－－部分替换
//2－－全部替换';
	const METHOD_STATUS_IGNORE = 0;
	const METHOD_STATUS_COMPARE = 1;
	const METHOD_STATUS_REPLACE = 2;
	
	function _format_method($value){
		switch($value){
			case self::METHOD_STATUS_IGNORE: return 'I';
			case self::METHOD_STATUS_COMPARE: return 'C';
			case self::METHOD_STATUS_REPLACE: return 'R';
			default : return 'U';
		}
	}
	
	function format_ani_method_for_download($value,$data){
		return $this->_format_method($value);
	}
	
	function check($data) {
		$translation_id = $data['TranslationItem']['translation_id'];
		$ani = $data['TranslationItem']['ani'];
		$dnis = $data['TranslationItem']['dnis'];
		$action_ani = $data['TranslationItem']['action_ani'];
		$action_dnis = $data['TranslationItem']['action_dnis'];
		$sql = "SELECT count(*) FROM translation_item WHERE 
						 ani = '{$ani}' 
						AND dnis = '{$dnis}'and translation_id = $translation_id ";
                if (isset($data['TranslationItem']['ref_id']))
                    $sql .= " AND ref_id !=  {$data['TranslationItem']['ref_id']}";
		$result = $this->query($sql);
		return $result[0][0]['count'];
	}
	
	function format_dnis_method_for_download($value,$data){
		return $this->_format_method($value);
	}
}
?>
