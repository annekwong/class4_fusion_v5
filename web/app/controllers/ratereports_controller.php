<?php


class RatereportsController extends AppController {
	var $name = 'Ratereports';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html' );
	
	//查询封装
	
	
	const INGRESS_CALL="case ingress_id when '' then null else ingress_id end";
	const EGRESS_CALL="case egress_id when '' then null else egress_id end";

	//读取该模块的执行和修改权限
	public function beforeFilter() {
		$this->checkSession ( "login_type" ); //核查用户身份
		$login_type = $this->Session->read ( 'login_type' );
		if ($login_type == 1) {
			//admin
			$this->Session->write ( 'executable', true );
			$this->Session->write ( 'writable', true );
		} else {
			$limit = $this->Session->read ( 'sst_retail_rcardpools' );
			$this->Session->write ( 'executable', $limit ['executable'] );
			$this->Session->write ( 'writable', $limit ['writable'] );
		}
		parent::beforeFilter ();
	}


	
	
		function create_amchart_csv($report_type,$field){
		$field_sql=$this->get_field_sql($report_type,$field);
		$where = $this->capture_report_condtions ($report_type);
		extract ($this->capture_report_join ($report_type,''));
			$sql=" copy (
			select to_char(time, 'YYYY-MM-DD HH24') as group_time,$field_sql
		  from client_cdr $join 
		  where $where  group by group_time having 1=1 
		  order by group_time desc
			 ) to '".Configure::read('/tmp/exportsdatabase_actual_export_path') ."/$report_type"."$field.csv'  csv ";
			$this->Cdr->query($sql);
			copy(Configure::read('database_export_path') . "/$report_type"."$field.csv",APP.'webroot'.DS.'amstock'.DS."$report_type"."$field.csv");
		
	}
	function  create_amchart_setting_xml($report_type,$field){
		$humanize_field=Inflector::humanize($field);
		
	
		
	 $out='<?xml version="1.0" encoding="UTF-8"?>'."\n";
   $out = '<settings>';
   $out.=	file_get_contents(APP.'views'.DS.'locationreports'.DS."amstock_settings_base.xml");
   $out.= '<data_sets>';

			$out .= "<data_set did=\"0\">\n";
			$out .= " <title>$humanize_field</title>\n";
			$out .= " <short>$humanize_field $humanize_field</short>\n";
			$out .= " <description>   $humanize_field;</description>\n";
			$out .= "<file_name>$report_type"."$field.csv</file_name>\n";
			$out .= "<main_drop_down selected=\"true\"></main_drop_down>\n   	  
       					<compare_list_box selected=\"false\"></compare_list_box>\n
       <csv>
         <reverse>true</reverse>
         <separator>,</separator>
         <date_format>YYYY-MM-DD hh:mm:ss</date_format>
         <decimal_separator>.</decimal_separator>
         <columns>
           <column>date</column>
           <column>close</column>  
         </columns>
       </csv>\n";
			$out .= "</data_set>\n";
	
	  $out.= '</data_sets>';	
		 $out.=	file_get_contents(APP.'views'.DS.'locationreports'.DS."amstock_settings_charts.xml");
   $out .= '</settings>';
   

	 $xml_file=APP.'webroot'.DS.'amstock'.DS."$report_type"."$field"."_amstock_settings.xml";
   $fp=fopen($xml_file,'w');
   fwrite($fp,$out);
   fclose($fp);
	}
	
	
	
	
			function flash_setting($report_type) {
		Configure::write ( 'debug', 0 );
		$field = ! empty ( $_GET ['f'] ) ? $_GET ['f'] : 'duration';
		$report_type= ! empty ( $_GET ['report_type'] ) ? $_GET ['report_type'] : 'orig_rate_report';
		$xml_file = APP . 'webroot' . DS . 'amstock' . DS . "$report_type"."$field" . "_amstock_settings.xml";
		echo file_get_contents ( $xml_file );
	}
	
	//初始化查询参数
	function init_query() {
		$this->set ( 'ingress_carrier', $this->Cdr->findIngressClient () );
		$this->set ( 'egress_carrier', $this->Cdr->findEgressClient () );
		$this->set ( 'currency', $this->Cdr->find_currency () );
		$this->set ( 'server', $this->Cdr->find_server () );
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_id () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_id () );
                
                if(!empty($_GET['ingress_alias'])){
                     $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
                     $this->set('tech_perfix',$res);
                }

	
	}
	
	function create_amchart_flash($report_type) {
		$this->create_amchart_csv ( $report_type, 'cdr_count' );
		$this->create_amchart_csv ( $report_type, 'duration' );
		$this->create_amchart_csv ( $report_type, 'cdr_count_percentage' );
		$this->create_amchart_csv ( $report_type, 'duration_percentage' );
		
		$this->create_amchart_setting_xml ($report_type, 'cdr_count' );
		$this->create_amchart_setting_xml ($report_type, 'duration' );
		$this->create_amchart_setting_xml ( $report_type,'cdr_count_percentage' );
		$this->create_amchart_setting_xml ($report_type, 'duration_percentage' );
	
	}	
	
	

	
	function  get_field_sql($report_type,$field){
			extract ( $this->Cdr->get_real_period () );
			$where = $this->capture_report_condtions ($report_type );
			extract ( $this->capture_report_join ($report_type,'' ) );
//	$arr=array(
//	'cdr_count'=>"count(*) as cdr_size",
//	'cdr_count_percentage'=>"(count(*)::numeric/ 	 	nullif((	select count(*)  from  client_cdr $join where $where	),0))::numeric(20,3)  as  call_count_percentage",
//	'duration'=>"sum(NULLIF(call_duration,'')::numeric)/60 as call_duration",
//	'duration_percentage'=>"(sum(NULLIF(call_duration,'')::numeric)/NULLIF((select sum(NULLIF(call_duration,'')::numeric)  from  client_cdr $join where $where),0))::numeric(20,2)  as  call_duration_percentage"
//
//	);
		$arr=array(
	'cdr_count'=>"sum(ca) as cdr_size",
	'cdr_count_percentage'=>"(sum(ca)::numeric/ 	 	nullif((	select sum(ca)  from  statistic_cdr $join where $where	),0))::numeric(20,3)  as  call_count_percentage",
	'duration'=>"sum(call_duration) as call_duration",
	'duration_percentage'=>"(sum(call_duration)/NULLIF((select sum(call_duration)  from  statistic_cdr $join where $where),0))::numeric(20,2)  as  call_duration_percentage"

	);
	
	if(isset($arr[$field])){
	
	return  $arr[$field];
	}else{
	
	return $arr['duration'];
	}
	
	}
	
	public  function get_call_case_when($report_type){
	  if($report_type=='orig_rate_report'){
	  //return self::INGRESS_CALL;
	  	return "ingress_ca";
	  }else{
	  //return  self::EGRESS_CALL;
	  		return "egress_ca";
	  }
		
	}
	
	function get_datas($report_type = '',$order_field) {
		extract ( $this->Cdr->get_real_period () );
		$where = $this->capture_report_condtions ($report_type );
		$order = $this->capture_report_order ();
	
		extract ( $this->capture_report_join ($report_type,'') );
		$call_case_when=$this->get_call_case_when($report_type);
		$today_statistic = $this->getTodayStatistic();
		//call count占的百分比
//		$per_callcount = "(count($call_case_when)::numeric/ 	 	nullif((	select count($call_case_when)  from  client_cdr $join where $where	),0))::numeric(20,3)  as  call_count_percentage";
			$per_callcount = "(sum($call_case_when)/ 	 	nullif((	select sum($call_case_when)  from  $today_statistic $join where $where	),0))::numeric(20,3)  as  call_count_percentage";
		
		//call_duration 占的百分比
		//$per_call_duration = "(sum(NULLIF(call_duration,'')::numeric)/NULLIF((select sum(NULLIF(call_duration,'')::numeric)  from  client_cdr $join where $where),0))::numeric(20,2)  as  call_duration_percentage";
		$per_call_duration = "(sum(call_duration)/NULLIF((select sum(call_duration)  from  $today_statistic $join where $where),0))::numeric(20,2)  as  call_duration_percentage";

		$having = '';
		
		//$column = "count($call_case_when) as cdr_size,$per_callcount,sum(NULLIF(call_duration,'')::numeric)/60 as call_duration,$per_call_duration";
		$column = "sum($call_case_when) as cdr_size,$per_callcount,sum(call_duration) as call_duration,$per_call_duration";
		
		
		if (! empty ( $group_by_where )) {
			$this->set('is_group','true');
			$org_sql = "select   $group_by_field,$column	from $today_statistic $join   left join rate_table ON $order_field::text =rate_table.rate_table_id::text where $where  group by  $group_by_where    $order";
			
		
		} else {
			$this->set('is_group','false');
			$org_sql = "select   $group_by_field,$column	from $today_statistic $join  where $where      $order";
		
		}
		
		//$sum_sql=  "select count($call_case_when) as cdr_size,$per_callcount,sum(NULLIF(call_duration,'')::numeric)/60 as call_duration,$per_call_duration from client_cdr   $join where $where  ";
		$sum_sql=  "select  sum($call_case_when) as cdr_size,$per_callcount,sum(call_duration) as call_duration,$per_call_duration from $today_statistic   $join where $where  ";
		
		$org_list = $this->Cdr->query ( $org_sql );
		return compact ( 'org_sql', 'org_list', 'group_by_field_arr','sum_sql');
	}
		
	

	
		function capture_report_order() {
		$order=$this->_order_condtions(
		Array('date','rate_table_name','cdr_size','call_count_percentage','call_duration','call_duration_percentage')
		);
		if(empty($order))
		{
			$order='order by 1,2 desc';	
		}
		else
		{
			$order='order by '.$order;	
		}
		
		return $order;
	}
	
	

	
	

	

	
	
		function get_all_country(){
		extract ( $this->Cdr->get_real_period () );
		$where = $this->capture_report_condtions ('location_report');
			extract ($this->capture_report_join ('location_report',''));

		$org_sql="select  distinct  term_country  from client_cdr $join  where $where";
		//$org_sql="select  distinct  term_country  from statistic_cdr $join  where $where";
		$org_list = $this->Cdr->query ($org_sql );
	
		$tmp_arr=array();
		if(!empty($org_list)){
		$size=count($org_list);
		foreach ($org_list as  $key=>$value){
		    $tmp_arr[]=$value[0]['term_country'];
		
		}

			
		}
	return $tmp_arr;
	}	
		function  index()
	{
		$this->redirect('summary_reports');
	}
	function summary_reports() {
		
		$this->set ( 'show_comm', true );
		//$this->_session_get(isset ( $_GET ['searchkey'] ));
		$this->pageTitle="Statistics/Usage Report";
		$t = getMicrotime();
		
		if (! empty ( $this->params ['pass'] [0] )) {
			$rate_type = $this->params ['pass'] [0];
			if ($rate_type == 'org') {
			
				$this->set ( 'rate_type', 'org' );
				$order_field = 'ingress_client_rate_table_id';
			
			} elseif ($rate_type == 'term') {
			
				$this->set ( 'rate_type', 'term' );
				$order_field = 'egress_rate_table_id';
			} else {
			
				$this->set ( 'rate_type', 'org' );
				$rate_type = 'org';
				$order_field = 'ingress_client_rate_table_id';
			
			}
		} else {
		
			$rate_type = 'org';
			$this->set ( 'rate_type', 'org' );
			$order_field = 'ingress_client_rate_table_id';
		
		}
		$this->init_query ();
		//extract($this->Cdr->get_real_period());
		extract($this->get_start_end_time());
		//取数据  	 sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
		if($rate_type=='org'){
		$report_type='orig_rate_report';
		}else{
		
			$report_type='term_rate_report';
		}
		
		$this->set ( "report_type", $report_type );
		extract ( $this->get_datas ( $report_type, $order_field ) );
//		$this->create_amchart_flash ( $report_type );
		$image_file = $this->create_image_csv();
		$this->set('image_file', basename($image_file)); 
		
		$country_arr = $this->get_all_country ();
		$this->set('country_arr', $country_arr);
		$this->set('field', 'ca');



					$org_list = $this->Cdr->query ( $sum_sql);
		   $this->set ("sum_data", $org_list);
				if(isset($_GET ['query'] ['output'])){
			//下载
				$file_name=$this->create_doload_file_name('UsageReport',$start,$end);
				if (	$_GET ['query'] ['output']== 'csv'){
						Configure::write('debug',0);
					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
						$this->_catch_exception_msg(array('RatereportsController','_reports_download_impl'),array('download_sql' => $org_sql,'file_name'=>$file_name));
	  
  	$this->layout='csv';

				}
				elseif (	$_GET ['query'] ['output']== 'xls'){
						Configure::write('debug',0);
					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
						$this->_catch_exception_msg(array('RatereportsController','_reports_download_xls'),array('download_sql' => $org_sql,'file_name'=>$file_name));
	  
  	$this->layout='csv';

				}
				elseif (	$_GET ['query'] ['output']== 'delayed'){
//						Configure::write('debug',0);
//					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名						
//						$this->_catch_exception_msg(array('RatereportsController','_reports_download_impl'),array('download_sql' => $org_sql));
//	  
//  	$this->layout='csv';

				}else{
				//web显示
						$org_list = $this->Cdr->query ( $org_sql );
		$this->set ("client_org", $org_list);
		$this->set ( "group_by_field_arr", $group_by_field_arr );
				
				}
		
		}else{
			$org_list = $this->Cdr->query ($org_sql );

		$this->set ("client_org", $org_list);
		$this->set ( "group_by_field_arr", $group_by_field_arr );
				}
		

	$this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));     
	}
	
	function _reports_download_impl($params=array()){
		extract($params);
		if($this->Cdr->download_by_sql($download_sql,array('objectives'=>'rate_reports','file_name'=>$file_name))){
			exit(1);
		} 
	}
	
	function _reports_download_xls($params=array()){
		extract($params);
		if($this->Cdr->download_xls_by_sql($download_sql,array('objectives'=>'rate_reports','file_name'=>$file_name))){
			exit(1);
		} 
	}
	
		function image_test()
	{
		//var_dump($_REQUEST);
		//Configure::write('debug',0);
		//$this->layout='csv';
		require_once("vendors/image_test.php");
		//App::import ( 'Vendor', 'image_test');
		//$this->layout='csv';
	}
	
//生成image所需查询结果文件，返回文件名
	function create_image_csv() 
	{		
		$return = '';
		//create数据文件	
		$where = $this->capture_report_condtions ('location_report');
		extract ($this->capture_report_join ('location_report',''));
		$today_statistic = $this->getTodayStatistic();
		
		 
			$sql="select to_char(time, 'YYYY-MM-DD HH24') as group_time, term_country, sum(ca) as ca, sum(ok_calls) as ok_calls,
			sum(call_duration) as call_duration, sum(ingress_cost) as ingress_cost, sum(egress_cost) as egress_cost, sum(orig_bill_minute)
			 as orig_bill_minute, sum(term_bill_minute) as term_bill_minute, sum(ingress_ca) as ingress_ca, sum(egress_ca) as egress_ca,
			 sum(not_zero_calls) as not_zero_calls, sum(busy_calls) as busy_calls, sum(no_channel_calls) as no_channel_calls
		  from $today_statistic  
		  where $where group by group_time, term_country having 1=1 
		  order by group_time desc";
		$result = $this->Cdr->query($sql);
		if (!empty($result))
		{
			$return = "/tmp/image_".date("Ymd-His")."_".rand(0,99);
			file_put_contents($return, json_encode($result));
		}
		return $return;
	}
	
	
	
}