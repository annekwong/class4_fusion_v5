<?php
#App::import("Model","ImportExportLog");
#App::import("Model","Cdr");
class AppDisHelper extends AppHelper {

	
	
	
function   get_groupby_arr($key){
    				$groupby=array(
					'orig_client_name'=>'orig_client_name',
					'ingress_alias'=>'ingress_alias',
					'orig_code_name'=>'query[code_name]',
					'orig_code'=>'query[code]',
    			'orig_country'=>'query[country]',  
    			'orig_rate'=>'query[id_rates]',  
					'ingress_host'=>'orig_host',
					'term_client_name'=>'term_client_name',
					'egress_alias'=>'egress_alias',
					'term_code_name'=>'query[code_name_term]',
					'term_code'=>'query[code_term]',
    			'term_country'=>'query[country_term]',
    			'term_rate'=>'query[id_rates_term]',    
					'egress_host'=>'term_host',
					'termination_source_host_name'=>'server_ip'
					
					);
	
	if(isset($groupby[$key])){
		  return $groupby[$key];
		
	}else{
		return  '';
		
	}
	
}	
	
	
function create_cdr_report_link($rate_type,$release_cause_from_protocol_stack,$real_release_cause,$disconnect,$group_link_arr,$pice){
	//browser   url

		if(isset($pice[1]))
		{
			$pice_para= '?'.$pice[1];
		}else
		{
			$pice_para='?abc=1';
		}
	 
	 
		//group by  url

	 
	 if(!empty($group_link_arr)){
	 	   foreach ($group_link_arr  as  $key =>$value){
	 	   	      $p1=$this->get_groupby_arr($key);
	 	   	      $pice_para=$pice_para."&$p1=$value";
	 	   	
	 	   }
	 	
	 	
	 }
	 
	 	$web_root=$this->webroot;
	 $link=<<<EOD
		<a href="{$web_root}cdrreports/summary_reports/disconnect/{$release_cause_from_protocol_stack}/{$rate_type}/{$real_release_cause}{$pice_para}">
		<strong>{$disconnect}</strong></a>
EOD;
	
	
	 return  $link;
	
	
	
	
}




}
?>