<?php
/**
 * 
 * 
 * 
 * 
 * @author wangxujun
 *
 */	
	class SearchHanderComponent extends Object {



		function get_report_search_condition(){
			
			$org_client_where = ! empty ( $_GET ['query'] ['id_clients'] ) ? "and ingress_client_id='{$_GET ['query'] ['id_clients']}'" : '';
			$term_client_where = ! empty ( $_GET ['query'] ['id_clients_term'] ) ? "and egress_client_id='{$_GET ['query'] ['id_clients_term']}'" : '';
		
			if (isset ( $_GET ['query'] ['code_name'] )) {
				$org_code_name = $_GET ['query'] ['code_name'];
				if (! empty ( $org_code_name )) {
					$org_code_name_where = "and orig_code.code_name='$org_code_name'";
					$org_code_join = "left  join rate  as  orig_code on orig_code.rate_id::text=client_cdr.ingress_rate_id";
				}
			}
			if (isset ( $_GET ['query'] ['code'] )) {
				$org_code = $_GET ['query'] ['code'];
				if (! empty ( $org_code )) {
					$org_code_where = "and orig_code.code='$org_code'";
					$org_code_join = "left  join rate  as  orig_code on orig_code.rate_id::text=client_cdr.ingress_rate_id";
				}
			}
			if (isset ( $_GET ['query'] ['code_name_term'] )) {
				$term_code_name = $_GET ['query'] ['code_name_term'];
				if (! empty ( $term_code_name )) {
					$term_code_name_where = "and term_code.code_name='$term_code_name'";
					$term_code_join = "left  join rate  as  term_code on term_code.rate_id=client_cdr.egress_rate_id";
				}
			}
			if (isset ( $_GET ['query'] ['code_term'] )) {
				$term_code = $_GET ['query'] ['code_term'];
				if (! empty ( $term_code )) {
					$term_code_where = "and term_code.code='$term_code'";
					$term_code_join = "left  join rate  as  term_code on term_code.rate_id=client_cdr.egress_rate_id";
				}
			}
			
			
			
		
			if (! empty ( $_GET ['query'] ['asr_std_min'] ) || ! empty ( $_GET ['query'] ['asr_std_max'] ) || ! empty ( $_GET ['query'] ['asr_cur_min'] ) || ! empty ( $_GET ['query'] ['asr_cur_max'] ) || ! empty ( $_GET ['query'] ['profit_min'] ) || ! empty ( $_GET ['query'] ['profit_max'] )) 

			{
				$having .= "having true  ";
			}
			if (isset ( $_GET ['query'] ['asr_cur_min'] )) {
				$asr_cur_min = $_GET ['query'] ['asr_cur_min'];
				if (! empty ( $asr_cur_min )) {
					$having .= " 
					 and
					  (
				 ( count(NULLIF(call_duration , '0')) *100/NULLIF(count(*),0)) ::numeric(20,2) 
					  )
					>$asr_cur_min";
					
					$fileter_where .= "asr_cur>$asr_cur_min";
				}
			}
			if (isset ( $_GET ['query'] ['asr_cur_max'] )) {
				$asr_cur_max = $_GET ['query'] ['asr_cur_max'];
				if (! empty ( $asr_cur_max )) {
					$having .= "  and (
				 ( count(NULLIF(call_duration , '0')) *100/NULLIF(count(*),0)) ::numeric(20,2) 
					)<$asr_cur_max";
					
					$fileter_where .= "asr_cur<$asr_cur_max";
				
				}
			}
			if (isset ( $_GET ['query'] ['asr_std_min'] )) {
				$asr_std_min = $_GET ['query'] ['asr_std_min'];
				if (! empty ( $asr_std_min )) {
					$having .= "  and (
			  (count(nullif(answer_time_of_date,'0'))*100::numeric(20,2)/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric(20,2) 

        )>$asr_std_min";
					
					$fileter_where .= "asr_std<$asr_std_min";
				}
			}
			if (isset ( $_GET ['query'] ['asr_std_max'] )) {
				$asr_std_max = $_GET ['query'] ['asr_std_max'];
				if (! empty ( $asr_std_max )) {
					$having .= "  and (
	  (count(nullif(answer_time_of_date,'0'))*100::numeric(20,2)/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric(20,2) 
        )<$asr_std_max";
					
					$fileter_where .= "asr_std<$asr_std_max";
				}
			}
			if (isset ( $_GET ['query'] ['profit_min'] )) {
				$profit_min = $_GET ['query'] ['profit_min'];
				if (! empty ( $profit_min )) {
					$having .= "  and (
			( ( sum(ingress_client_cost::numeric)-sum(egress_cost::numeric) ) *100/ NULLIF(sum(ingress_client_cost::numeric),0) )::numeric(20,2) 
        )>$profit_min";
				}
			}
			if (isset ( $_GET ['query'] ['profit_max'] )) {
				$profit_max = $_GET ['query'] ['profit_max'];
				if (! empty ( $profit_max )) {
					$having .= "  and (
			( ( sum(ingress_client_cost::numeric)-sum(egress_cost::numeric) ) *100/ NULLIF(sum(ingress_client_cost::numeric),0) )::numeric(20,2) 
        )<$profit_max";
				
				}
			}
			
		}

	}
	
?>