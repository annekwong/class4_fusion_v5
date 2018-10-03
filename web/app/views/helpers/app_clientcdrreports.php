<?php 
class AppClientcdrreportsHelper extends AppHelper {	
	function show_number($content){
		$fcontent = (float)$content;
		if($fcontent){
			if($fcontent < 0){
				return '(' .str_replace('-','',$content).')';
			}else{
				return $content;
			}
		}else{
			return '0';
		}
	}

	
	
function format_cdr_time($time) {
	if($time=='0'){return 0;}
	if($time==''){return '';}
	if(ereg("^[0-9]+$",$time)||strlen($time)>9){
    $time=substr($time,0,10);		
	    return 	date("Y-m-d",$time);
	}
	
}	
	
}
?>
