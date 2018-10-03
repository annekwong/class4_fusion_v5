<?php
App::import("Model",'TranslationItem');
class DigitTranslation extends TranslationItem {
	function format_strategy_for_download($value,$data){
		switch($value){
//			case  0 : return 'Weight';
//			case  1 : return 'Top-Down';
//			case  2 : return 'Round-Robin';
//			default : return 'Unkown';		
			
			case  0 : return '0';
			case  1 : return '1';
			case  2 : return '2';
			default : return '0';			
		}
	}
		function get_foreign_name($id=null){
			if(!empty($id)){
        $list = $this->query("select translation_name  as name from  digit_translation where translation_id=$id");
        return $list;
			}else{
        	return ;
        	
        }
     
        }		
}
?>