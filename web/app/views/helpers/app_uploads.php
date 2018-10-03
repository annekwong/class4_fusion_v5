<?php 
class AppUploadsHelper extends AppHelper {


	function show_upload_title($str){
		if($str=='digit_translation'){
			return 'Routing ';
			
		} elseif ($str=="static_route") {
                    return "Routing ";
                } elseif ($str=="product_item") {
                    return "Routing ";
                }
                else{
			
			
			return $str;
		}
		
		
	}
	
	
}
?>
