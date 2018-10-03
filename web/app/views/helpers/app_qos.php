<?php
#App::import("Model","ImportExportLog");
App::import("Model","Cdr");
class AppQosHelper extends AppHelper {



	
	

function  get_carrier_profit($client_id,$period){
		$sql_ingress="select		sum(ingress_client_cost::numeric(20,5)) as ingress_cost   from  client_cdr 
									where    ingress_id  in(select resource_id::text  from  resource where  client_id=$client_id  and  ingress=true)    and ";
	
	
		$sql_egress="select		sum(egress_cost::numeric(20,5)) ::numeric(20,5) as egress_cost   from  client_cdr 
									where    egress_id  in(select resource_id::text  from  resource where  client_id=$client_id  and egress=true)    and ";
		
	if($period=='15min'){
		$sql_period="extract(epoch from time)::bigint  between extract(epoch from now())::bigint-(60*15)  and  extract(epoch from now())::bigint";
	}
	if($period=='1h'){
		$sql_period="extract(epoch from time)::bigint  between extract(epoch from now())::bigint-(3600)  and  extract(epoch from now())::bigint";
	}
	
	if($period=='24h'){
		$sql_period="extract(epoch from time)::bigint  between extract(epoch from now())::bigint-(3600*24)  and  extract(epoch from now())::bigint";
	}
	
	$sql_ingress.=$sql_period;
	$sql_egress.=$sql_period;
	
	$model=new Cdr();
	$ingress_list=$model->query($sql_ingress);
	$egress_list=$model->query($sql_egress);
	$ingress_cost=isset($ingress_list[0][0]['ingress_cost'])?$ingress_list[0][0]['ingress_cost']:0.00;
	$egress_cost=isset($egress_list[0][0]['egress_cost'])?$egress_list[0][0]['egress_cost']:0.00;
	return $ingress_cost-$egress_cost;
}




}
?>