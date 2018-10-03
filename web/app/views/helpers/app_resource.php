<?php
class AppResourceHelper extends AppHelper {	
		function format_ingress_options($ingress){			
			$options = array();
			foreach($ingress as $item){
    			$options[$item[0]['resource_id']] = $item[0]['alias'];
			}
			return $options;
		}
}