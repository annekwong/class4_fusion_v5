<?php 
App::import("Model","Cdr");
class AppAnalysisHelper extends AppHelper {	
	
	
	
	
	
function get_ingress_prefix(){
		$model=new Cdr();
		$sql="	select  tech_prefix  from  resource_prefix    where   resource_id  =(select resource_id from resource  where ingress=true  order by alias  asc   limit  1)";
			if (! empty ( $_GET ['ingress_alias'] ) ) {
					$sql="	select  tech_prefix  from  resource_prefix    where  resource_id  =(select resource_id from resource  where resource_id={$_GET ['ingress_alias']})";
		
				
			}
		$r = $model->query($sql);
		$size=count($r);		
		$l=array("0"=>NULL);
   for ($i=0;$i<$size ;$i++){
    $key=$r[$i][0]['tech_prefix'];
    $l[$key]=$r[$i][0]['tech_prefix'];
      }
   return $l;
	
	
	
}	
	
	
/**
 * 查找  Comparison   最匹配的费率
 * @param unknown_type $code
 */
	function  get_com_rate($code,$rate_table_id){
		$model=new Cdr();
		$sql="	select  rate.rate   from rate  where   rate.code @> '$code'  and  rate_table_id=$rate_table_id  order  by  rate";
		$code_list=$model->query($sql);
		return $code_list;
		
	}
	
function get_rate_tble_name($rate_table_id){
		$model=new Cdr();
		$sql="	select  name from rate_table  where  rate_table_id=$rate_table_id;";  
		$code_list=$model->query($sql);
		return $code_list[0][0]['name'];
}	
	
/**
 * 查找  Lcr code  最匹配的费率
 * @param unknown_type $code
 */
	function  get_lcr_rate($code){
		$model=new Cdr();
	/*	
		$sql="	select  code,(select  name from rate_table  where  rate_table_id= rate.rate_table_id)as  table_name      ,rate.rate   
		  from rate  where   rate.code @> '$code'  order  by  rate,table_name";
	  */	
$sql= <<<EOT
select  code,(select  name from rate_table  where  rate_table_id= rate.rate_table_id)as  table_name ,rate.rate,array (select        alias      from  resource  where  egress=true  and rate_table_id=rate.rate_table_id)  as  egress_name   
from  rate  where   rate.code <@ '$code'	and rate_table_id  in (select    distinct  rate_table_id  from    resource    
		where egress=true and  rate_table_id is  not  null )  order  by  rate,table_name
EOT;
		$code_list=$model->query($sql);
		return $code_list;
		
	}
	
	/**
	 * 计算code 对应的费率和ingress  rate 的利润
	 * @param unknown_type $code
	 * @param unknown_type $rate
	 */
function get_rate_profit($code,$rate){
		$ingress_rate = 0;
		$profit=0.00;
		if (! empty ( $_GET ['ingress_alias'] ) && ! empty ( $_GET ['prefix'] )) {
			$model = new Cdr ();
			$sql = "select rate from rate where rate_table_id=(select  rate_table_id from  resource_prefix 
		 where  resource_id={$_GET['ingress_alias']}   and  tech_prefix='{$_GET['prefix']}'   limit  1 ) and  code @> '$code'  limit  1";
			$code_list = $model->query ( $sql );
			if (! empty ( $code_list [0] [0] ['rate'] )) {
				$ingress_rate = $code_list [0] [0] ['rate'];
			
			}
			if (empty ( $ingress_rate )) {
				$profit= 0.00;
			}else{
			$profit = ((double)$rate-(double)$ingress_rate) * 100 / (double)$ingress_rate;
			$profit=number_format($profit,2);

			}
		
		}else{
				$profit= 0.00;
			
			
		}
		return   $profit;
}	
	

}
?>
