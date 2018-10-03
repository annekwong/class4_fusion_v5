<?php 
class AppResclisHelper extends AppHelper {	
		function options_client($lists)
		{
			$options=Array();
			foreach($lists as $list)
			{
				$options[$list['Client']['client_id']]=$list['Client']['name'];
			}
			return $options;
		}
}
?>
